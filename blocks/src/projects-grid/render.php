<?php

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

use lsc\blocks\app\core\LSCClass;
use lsc\blocks\app\controllers\LSCControllerProjects;

$projects = LSCClass::controller( LSCControllerProjects::class );

if( ! $projects ) :
  return;
endif;
?>

<section
  <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
  data-wp-interactive="lsc-projects-grid-wrapper"
  aria-label="<?php echo esc_attr__('Projects grid', 'lsc-blocks'); ?>"
  <?php // phpcs:ignore WordPress.Security.EscapeOutput
	echo wp_interactivity_data_wp_context([
    'selectedCategory'  => '',
    'html'          => '',
    'isLoading'         => false,
    'noItemsMessage'    => esc_html__('No items found', 'lsc-blocks'),
    'errorMessage'      => esc_html__('Something went wrong', 'lsc-blocks') 
  ]); ?>
>
	<?php $projects->filtering_template(); ?>

	<div class="lsc-projects-grid">
		<?php $projects->grid_template(); ?>
	</div>

	<div class="lsc-projects-loader" role="status" aria-live="polite" data-wp-bind--hidden="!context.isLoading"><span><?php echo esc_html__('Loading...', 'lsc-blocks'); ?></span></div>

</section>