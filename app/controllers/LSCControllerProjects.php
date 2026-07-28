<?php
/**
 * Controller for projects
 * 
 * @since 1.0.0
 */

namespace lsc\blocks\app\controllers;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

use lsc\blocks\app\abstracts\LSCAbstractController;
use lsc\blocks\app\models\LSCModelProjects;

class LSCControllerProjects extends LSCAbstractController {

  public static function identifier(): string {
    return 'projects';
  }

  protected static function model_class(): string {
    return LSCModelProjects::class;
  }
  
  public function register(): void {
    $this->register_post_type();
  }

  /**
   * Register post types
   * 
   * @since 1.0.0
   */
  public function register_post_type() {

    $post_type_name = $this->model::$post_type_name;
    $taxonomies = $this->model->taxonomies();

    register_post_type($post_type_name, $this->model::post_type_args());

    if( !empty($taxonomies) ) {
      foreach($taxonomies as $taxonomy) {
        register_taxonomy(
          $taxonomy['taxonomy'],
          $taxonomy['object_type'] ?? [$post_type_name],
          $taxonomy['args'] ?? []
        );
      }
    }

    if( get_option('lsc_plugin_activation') ) {
      flush_rewrite_rules();
      delete_option('lsc_plugin_activation');
    }
  }

  public static function route(): ?array {
    return [
      'endpoint' => 'projects-grid',
      'methods'  => 'GET'
    ];
  }

  /**
   * Request
   */
  public function request( \WP_REST_Request $request ): array {
    $category = sanitize_text_field( $request->get_param('category') );
    $taxonomy = sanitize_text_field( $request->get_param('taxonomy') );

    if ( ! $category ) {
      return [];
    }

    return [
      'tax_query' => [[
        'taxonomy' => $taxonomy,
        'field'    => 'slug',
        'terms'    => $category,
      ]],
    ];
  }

  /**
   * Handle response
   * 
   * @since 1.0.0
   */
  public function response( \WP_REST_Request  $data ): \WP_REST_Response {
    $args = $this->request( $data );

    ob_start();
    $this->grid_template( $args );
    $html = ob_get_clean();

    return rest_ensure_response( [ 'html' => $html ] );
  }

  /**
   * Get filtering template
   * 
   * @since 1.0.0
   * @return string|false
   */
  public function filtering_template($taxonomy = 'service_area', $args = []) {
    $terms =  $this->model->get_taxonomy_terms($taxonomy, $args);

    if( empty($terms) && !is_array($terms) ) {
      return false;
    }
    
    require LSC_VIEWS . 'partials/filtering.php';
  }

  /**
   * Get the grid template
   * 
   * @since 1.0.0
   * @return string|false
   */
  public function grid_template($params = [], $taxonomy = 'service_area') {
    $items = $this->model->get_items($params);

    if( empty($items) || !is_array($items) ) {
      echo esc_html__('No Items Found', 'lsc-blocks');
      return false;
    }

    require LSC_VIEWS . 'partials/grid.php';
  }
}