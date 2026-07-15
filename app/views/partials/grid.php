<?php
/**
 * Template part for grid layout
 * 
 * @since 1.0.0
 */

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
?>

<?php foreach($items as $item) : // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals ?>
  <?php if( !is_object($item) ) : ?>
    <?php continue; ?>
  <?php endif; ?>

  <?php require LSC_VIEWS . 'partials/card.php'; ?>
<?php endforeach; ?>
