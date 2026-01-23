<?php
/**
 * Controller for projects
 * 
 * @since 1.0.0
 */

namespace hy\blocks\app\controllers;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

use hy\blocks\app\models\HYBModelProjects;
use hy\blocks\app\routes\HYBRouteProjects;

class HYBControllerProjects {

  protected static $_instance = null;
  
  public function __construct() {
    self::run();
  }

  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * Everything required on start
   * 
   * @since 1.0.0
   */
  public static function run() {
    add_action('init', [self::class, 'register_post_types']);

    self::register_routes();
  }

  /**
   * Register post types
   * 
   * @since 1.0.0
   */
  public static function register_post_types() {
    $post_type_name = HYBModelProjects::$post_type_name;
    $taxonomies = HYBModelProjects::taxonomies();

    register_post_type($post_type_name, HYBModelProjects::post_type_args());

    if( !empty($taxonomies) ) {
      foreach($taxonomies as $taxonomy) {
        register_taxonomy(
          $taxonomy['taxonomy'],
          $taxonomy['object_type'] ?? [static::$post_type_name],
          $taxonomy['args'] ?? []
        );
      }
    }

    if( get_option('hyb_plugin_activation') ) {
      flush_rewrite_rules();
      delete_option('hyb_plugin_activation');
    }
  }

  /**
   * Register routes
   * 
   * @since 1.0.0
   */
  public static function register_routes() {
    new HYBRouteProjects();
  }

  /**
   * Handle request
   * 
   * @since 1.0.0
   */
  public function get_grid_rest_request($request) {
    $category = sanitize_text_field($request->get_param('category'));
    $taxonomy = sanitize_text_field($request->get_param('taxonomy'));
    $args = [];

    if( $category ) {
      $args = [
        'tax_query' => [
          [
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => $category,
          ],
        ],
      ];
    }

    ob_start();
    $this->grid_template($args);
    $grid_html = ob_get_clean();

    return rest_ensure_response([
      'gridHtml'   => $grid_html,
    ]);
  }

  /**
   * Get filtering template
   * 
   * @since 1.0.0
   * @return string|false
   */
  public function filtering_template($taxonomy = 'service_area', $args = []) {
    $terms =  HYBModelProjects::get_taxonomy_terms($taxonomy, $args);

    if( empty($terms) && !is_array($terms) ) {
      return false;
    }
    
    require HYB_VIEWS . 'partials/filtering.php';
  }

  /**
   * Get the grid template
   * 
   * @since 1.0.0
   * @return string|false
   */
  public function grid_template($params = [], $taxonomy = 'service_area') {
    $items = HYBModelProjects::get_items($params);

    if( empty($items) || !is_array($items) ) {
      echo esc_html__('No Items Found', 'hiyield-blocks');
      return false;
    }

    require HYB_VIEWS . 'partials/grid.php';
  }
}