<?php
if( ! defined('ABSPATH')) {
  exit;
}

use lsc\blocks\app\core\LSCClass;
use lsc\blocks\app\controllers\LSCControllerPostFeedback;

$controller = LSCClass::controller( LSCControllerPostFeedback::class );
$post_id    = $block->context['postId'] ?? get_the_ID();

if ( ! $controller || ! $post_id ) {
  return;
}

$counts   = $controller->count( (int) $post_id );
$question = sanitize_text_field($attributes['question'] ?? '');
$thanks   = sanitize_text_field($attributes['thankYouMessage'] ?? '');
?>

<div
  <?php echo get_block_wrapper_attributes(); ?>
  data-wp-interactive="lsc-feedback-widget"
  data-wp-init="callbacks.checkExistingVote"
  <?php echo wp_interactivity_data_wp_context([
    'postId'   => (int) $post_id,
    'hasVoted' => false,
    'thankYou' => $thanks,
    'counts'   => $counts,
  ]); ?>
>
  <div class="lsc-feedback-row" data-wp-bind--hidden="context.hasVoted">
    <span class="lsc-feedback-question"><?php echo esc_html($question); ?></span>

    <div class="lsc-feedback-buttons">
      <button
        type="button"
        class="lsc-feedback-btn"
        data-wp-on--click="actions.vote"
        data-vote="yes"
        aria-label="<?php esc_attr_e('Yes, this was helpful', 'lsc-blocks'); ?>"
      >
        <span class="lsc-feedback-emoji" aria-hidden="true">👍</span>
        <span class="lsc-feedback-count" data-wp-text="context.counts.yes"></span>
      </button>

      <button
        type="button"
        class="lsc-feedback-btn"
        data-wp-on--click="actions.vote"
        data-vote="no"
        aria-label="<?php esc_attr_e('No, this was not helpful', 'lsc-blocks'); ?>"
      >
        <span class="lsc-feedback-emoji" aria-hidden="true">👎</span>
        <span class="lsc-feedback-count" data-wp-text="context.counts.no"></span>
      </button>
    </div>
  </div>

  <p class="lsc-feedback-thanks" data-wp-bind--hidden="!context.hasVoted">
    <?php echo esc_html($thanks); ?>
  </p>
</div>