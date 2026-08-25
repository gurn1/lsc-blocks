<?php
if( ! defined('ABSPATH')) :
  exit;
endif;
?>

<div class="wrap">

<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

<h2 class="nav-tab-wrapper">
  <?php foreach ( $pages as $page ) :
    $url = add_query_arg(
      [ 'page' => 'lsc-blocks-settings', 'tab' => $page->get_id() ],
      admin_url('options-general.php')
    );
    $is_active = $page === $active_page;
  ?>
    
    <a href="<?php echo esc_url($url); ?>" class="nav-tab <?php echo $is_active ? 'nav-tab-active' : ''; ?>">
      <?php echo esc_html( $page->get_label() ); ?>
    </a>
  <?php endforeach; ?>
</h2>

<?php $active_page->render(); ?>