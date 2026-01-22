<?php
/**
 * A template part for the card
 * 
 * @since 1.0.0
 */

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

$thumbnail_id = get_post_thumbnail_id($item->ID); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
$terms = get_the_terms($item->ID, $taxonomy); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
$tax_terms = $terms ? wp_list_pluck($terms, 'slug') : []; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
?>

<article class="hiyield-project-card" data-tax="<?php echo esc_attr(join(',', $tax_terms)); ?>">
  <a href="<?php echo esc_url(get_permalink($item->ID)); ?>">
    <?php if($thumbnail_id) : ?>
      <img class="grid-card-featured-image" src="<?php echo esc_url(wp_get_attachment_image_url($thumbnail_id, 'medium')); ?>" alt="<?php echo esc_attr($item->post_title . ' thumbnail'); ?>">
    <?php else : ?>
      <div class="grid-card-featured-image no-image"><?php echo esc_html__('No Image Found', 'hiyield-blocks'); ?></div>
    <?php endif; ?>
    
    <h3 class="grid-card-title"><?php echo esc_html($item->post_title); ?></h3>

    <?php if( $item->post_excerpt) : ?>
      <p class="grid-card-content"><?php echo esc_html($item->post_excerpt); ?></p>
    <?php elseif($item->post_content) : ?>
      <p class="grid-card-content">
      <?php 
        echo esc_html(
          wp_strip_all_tags(
            wp_trim_words( $item->post_content, 20, '...' )
          )
        );
      ?>
      </p>
    <?php endif; ?>
  </a>
</article>