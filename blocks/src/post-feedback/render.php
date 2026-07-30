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

$counts   = $controller->model_instance_for_render_use ?? null; // see note below
$question = sanitize_text_field($attributes['question'] ?? '');
$thanks   = sanitize_text_field($attributes['thankYouMessage'] ?? '');
?>

<div
  <?php echo get_block_wrapper_attributes(); ?>
  data-wp-interactive="lsc-feedback-widget"
  <?php echo wp_interactivity_data_wp_context([
    'postId'    => $post_id,
    'hasVoted'  => false,
    'thankYou'  => $thanks,
  ]); ?>
>
  <p class="lsc-feedback-question" data-wp-bind--hidden="context.hasVoted">
    <?php echo esc_html($question); ?>
  </p>

  <div class="lsc-feedback-buttons" data-wp-bind--hidden="context.hasVoted">
    <button type="button" data-wp-on--click="actions.vote" data-vote="yes">
      <?php echo esc_html__('Yes', 'lsc-blocks'); ?>
    </button>
    <button type="button" data-wp-on--click="actions.vote" data-vote="no">
      <?php echo esc_html__('No', 'lsc-blocks'); ?>
    </button>
  </div>

  <p class="lsc-feedback-thanks" data-wp-bind--hidden="!context.hasVoted">
    <?php echo esc_html($thanks); ?>
  </p>
</div>