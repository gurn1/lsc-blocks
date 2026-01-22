<?php
/**
 * Template part for filtering options
 * 
 * @since 1.0.0
 */

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
?>

<div class="hiyield-filter-group" aria-label="<?php echo esc_attr__('Filter posts by category', 'hiyield-blocks'); ?>">
  <div>  
    <label for="hiyield-product-grid-filter-select" class="sr-only"><?php echo esc_html__('Filter projects by category', 'hiyield-blocks'); ?></label>
    <select id="hiyield-product-grid-filter-select" data-taxonomy="<?php echo esc_attr($taxonomy); ?>" data-wp-on--change="actions.fetchGridData" data-wp-bind--value="context.selectedCategory"
    >
      <option value=""><?php echo esc_html_x('All', 'filter-option', 'hiyield-blocks'); ?></option>
      <?php foreach ( $terms as $term ) : ?>
        <option value="<?php echo esc_attr( $term->slug ); ?>">
          <?php echo esc_html( $term->name ); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
</div>