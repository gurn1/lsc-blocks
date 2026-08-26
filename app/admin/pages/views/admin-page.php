<?php
if( ! defined('ABSPATH')) :
  exit;
endif;
?>

<div class="wrap">
  <h1><?php echo esc_html($this->page_title); ?></h1>

  <?php if( $this->page_description ) : ?>
    <p class="description">
      <?php echo esc_html($this->page_description); ?>
    </p>
  <?php endif; ?>

  <?php $this->build_content()->render(); ?>
</div>