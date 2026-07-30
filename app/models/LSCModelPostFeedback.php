<?php
/**
 * Model for handling post feedback
 * 
 * @since 1.0.0
 */

namespace lsc\blocks\app\models;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

use lsc\blocks\app\abstracts\LSCAbstractModel;

class LSCModelPostFeedback extends LSCAbstractModel {

  protected const META_KEY = '_lsc_feedback_counts';

  public function get( int $post_id ): array {
    $counts = get_post_meta( $post_id, static::META_KEY, true);

    return wp_parse_args( $counts, [ 'yes' => 0, 'no' => 0 ] );
  }

  public function increment( int $post_id, string $vote): array {
    $counts = $this->get( $post_id );
    $counts[ $vote ]++;

    update_post_meta( $post_id, static::META_KEY, $counts );

    return $counts;
  }

}