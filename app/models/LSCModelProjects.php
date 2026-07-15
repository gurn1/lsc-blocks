<?php
/**
 * Model for handling project data
 * 
 * @since 1.0.0
 */

namespace lsc\blocks\app\models;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

class LSCModelProjects {

  public static string $post_type_name = 'lscblocks-projects';

  /**
   * Define the post type arguments
   * 
   * @since 1.0.0
   * @return array
   */
  public static function post_type_args() {
    return [
      'label' => __('Projects', 'lsc-blocks'),
      'public' => true,
      'supports' => ['title', 'editor', 'thumbnail', 'excerpt']
    ];
  }

  /**
   * Define taxonomies for the post type
   * 
   * @since 1.0.0
   * @return array
   */
  public static function taxonomies() {
    return [
      [
        'taxonomy' => 'service_area',
        'object_type' => static::$post_type_name,
        'args' => [
          'label' => __('Service Areas', 'lsc-blocks'),
          'public' => true,
          'hierarchical' => true
        ]
      ]
    ];
  }

  /**
   * Get items
   * 
   * @since 1.0.0
   * @return array|WP_Error
   */
  public static function get_items($args = []) {
    $defaults = [
      'post_type' => static::$post_type_name,
      'posts_per_page' => -1,
    ];

    $args = wp_parse_args($args, $defaults);

    return get_posts($args);
  }

  /**
   * Get Taxonomy terms
   * 
   * @since 1.0.0
   * @return array|WP_Error
   */
  public static function get_taxonomy_terms($taxonomy, $args = []) {
    $defaults = [
      'taxonomy' => $taxonomy,
      'hide_empty' => false
    ];

    $args = wp_parse_args( $args, $defaults );

    return get_terms($args);
  }
}