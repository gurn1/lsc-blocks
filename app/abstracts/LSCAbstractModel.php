<?php
/**
 * Abstract class for models
 *
 * @since 1.1.0
 */

namespace lsc\blocks\app\abstracts;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

abstract class LSCAbstractModel {

  /**
   * Optional - only set this if the model backs onto a custom post type.
   */
  public static string $post_type_name = '';

  /**
   * Optional - only override if $post_type_name is set.
   *
   * @return array
   */
  public static function post_type_args(): array {
    return [];
  }

  /**
   * Optional - only override if this post type has taxonomies to register.
   *
   * @return array
   */
  public static function taxonomies(): array {
    return [];
  }

  /**
   * Get items
   * @return array|\WP_Error
   */
  public function get_items( array $args = [] ) {
    $defaults = [
      'post_type'      => static::$post_type_name,
      'posts_per_page' => -1,
    ];

    return get_posts( wp_parse_args( $args, $defaults ) );
  }

  /**
   * Get taxonomy terms
   *
   * @return array|\WP_Error
   */
  public function get_taxonomy_terms( string $taxonomy, array $args = [] ) {
    $defaults = [
      'taxonomy'   => $taxonomy,
      'hide_empty' => false,
    ];

    return get_terms( wp_parse_args( $args, $defaults ) );
  }
}