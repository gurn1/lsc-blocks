<?php
if( ! defined('ABSPATH')) {
  exit;
}

use lsc\blocks\app\core\LSCClass;
use lsc\blocks\app\controllers\LSCControllerGoogleReviews;

$controller = LSCClass::controller( LSCControllerGoogleReviews::class );
$place_id   = sanitize_text_field($attributes['placeId'] ?? '');
$interval   = sanitize_text_field($attributes['refreshInterval'] ?? 'daily');
$slides_per_view = max(1, min(3, (int) ($attributes['slidesPerView'] ?? 1)));
$display_mode = in_array($attributes['displayMode'] ?? 'list', ['list', 'grid', 'slider'], true)
  ? $attributes['displayMode']
  : 'list';
$card_background_color = sanitize_hex_color( $attributes['cardBackgroundColor'] ?? '' );
$card_text_color = sanitize_hex_color( $attributes['cardTextColor'] ?? '' );

if ( ! $controller || empty($place_id) ) {
  return;
}

$style_parts = array_filter([
  $display_mode === 'slider' ? sprintf('--lsc-reviews-per-slide: %d;', $slides_per_view) : '',
  $card_background_color ? sprintf('--lsc-review-card-bg: %s;', esc_attr($card_background_color)) : '',
  $card_text_color ? sprintf('--lsc-review-card-text: %s;', esc_attr($card_text_color)) : '',
]);

$controller->ensure_scheduled( $place_id, $interval );

$data = $controller->reviews_for( $place_id );

if ( empty($data) ) :
  return; // nothing cached yet - the one-off fetch will populate this shortly
endif;
?>

<div <?php echo get_block_wrapper_attributes([
  'class' => 'lsc-block-reviews lsc-reviews-mode-' . $display_mode,
  'style' => implode(' ', $style_parts)
]); ?>>
  <div class="lsc-reviews-summary">
    <div class="lsc-reviews-rating-wpr">
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

    <?php if ( $display_mode === 'slider' ) : ?>
      <div class="lsc-reviews-slider-controls">
        <button type="button" class="lsc-reviews-prev" aria-label="<?php esc_attr_e('Previous review', 'lsc-blocks'); ?>">‹</button>
        <button type="button" class="lsc-reviews-next" aria-label="<?php esc_attr_e('Next review', 'lsc-blocks'); ?>">›</button>
      </div>
    <?php endif; ?>
  </div>

  <div class="lsc-reviews">
    <?php foreach ( $data['reviews'] as $review ) :
      $initial = mb_strtoupper( mb_substr($review['author'], 0, 1) );
    ?>
      <div class="lsc-review-card">
        <div class="lsc-review-header">
          <?php if ( ! empty($review['avatar']) ) : ?>
            <img
              class="lsc-review-avatar"
              src="<?php echo esc_url($review['avatar']); ?>"
              alt=""
              loading="lazy"
              width="40"
              height="40"
            />
          <?php else : ?>
            <span class="lsc-review-avatar lsc-review-avatar--initial" aria-hidden="true"><?php echo esc_html($initial); ?></span>
          <?php endif; ?>

          <div class="lsc-review-header-text">
            <span class="lsc-review-author"><?php echo esc_html($review['author']); ?></span>
            <span class="lsc-review-stars" aria-hidden="true">
              <?php echo str_repeat('★', (int) $review['rating']) . str_repeat('☆', 5 - (int) $review['rating']); ?>
            </span>
          </div>
        </div>
        <p class="lsc-review-text"><?php echo esc_html($review['text']); ?></p>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="lsc-reviews-footer">
    <span class="lsc-reviews-footer-text"><?php echo esc_html__('Powered by', 'lsc-blocks'); ?></span>
    <img
      class="lsc-reviews-footer-logo"
      src="<?php echo esc_url( LSC_URL . 'blocks/build/google-reviews/assets/google-logo.svg' ); ?>"
      alt="<?php echo esc_attr__('Google Maps', 'lsc-blocks'); ?>"
    />
  </div>
</div>