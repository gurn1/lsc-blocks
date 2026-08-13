<?php
if( ! defined('ABSPATH')) {
  exit;
}

use lsc\blocks\app\core\LSCClass;
use lsc\blocks\app\controllers\LSCControllerGoogleReviews;

$controller = LSCClass::controller( LSCControllerGoogleReviews::class );
$place_id   = sanitize_text_field($attributes['placeId'] ?? '');
$interval   = sanitize_text_field($attributes['refreshInterval'] ?? 'daily');

var_dump($place_id);
var_dump($interval);

if ( ! $controller || empty($place_id) ) {
  return;
}

$controller->ensure_scheduled( $place_id, $interval );

$data = $controller->reviews_for( $place_id );

if ( empty($data) ) {
  return; // nothing cached yet - the one-off fetch will populate this shortly
}
?>

<div <?php echo get_block_wrapper_attributes(); ?>>
  <div class="lsc-reviews-summary">
    <span class="lsc-reviews-rating"><?php echo esc_html( number_format_i18n($data['rating'], 1) ); ?></span>
    <span class="lsc-reviews-stars" aria-hidden="true">
      <?php echo str_repeat('★', (int) round($data['rating'])) . str_repeat('☆', 5 - (int) round($data['rating'])); ?>
    </span>
    <span class="lsc-reviews-count">
      <?php
      printf(
        /* translators: %d: number of Google reviews */
        esc_html( _n('(%d review)', '(%d reviews)', $data['total_reviews'], 'lsc-blocks') ),
        (int) $data['total_reviews']
      );
      ?>
    </span>
  </div>

  <div class="lsc-reviews-list">
    <?php foreach ( $data['reviews'] as $review ) : ?>
      <div class="lsc-review-card">
        <div class="lsc-review-header">
          <span class="lsc-review-author"><?php echo esc_html($review['author']); ?></span>
          <span class="lsc-review-stars" aria-hidden="true">
            <?php echo str_repeat('★', (int) $review['rating']) . str_repeat('☆', 5 - (int) $review['rating']); ?>
          </span>
        </div>
        <p class="lsc-review-text"><?php echo esc_html($review['text']); ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</div>