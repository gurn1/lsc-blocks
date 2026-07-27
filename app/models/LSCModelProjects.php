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

use lsc\blocks\app\abstracts\LSCAbstractModel;

class LSCModelProjects extends LSCAbstractModel {

  public static string $post_type_name = 'lscblocks-projects';

  /**
   * Define the post type arguments
   */
  public static function post_type_args(): array {
    return [
      'label' => __('Projects', 'lsc-blocks'),
      'public' => true,
      'supports' => ['title', 'editor', 'thumbnail', 'excerpt']
    ];
  }

  /**
   * Define taxonomies for the post type
   */
  public static function taxonomies(): array {
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

}