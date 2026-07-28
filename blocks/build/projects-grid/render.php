<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

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