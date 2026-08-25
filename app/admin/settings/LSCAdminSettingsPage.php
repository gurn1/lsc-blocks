<?php
/**
 * Primary class for handling settings pages
 */

namespace lsc\blocks\app\admin\settings;

if( ! defined('ABSPATH')) {
  exit;
}

class LSCAdminSettingsPage {

  protected static array $pages = [
    LSCAdminSettingsGeneral::class,
    LSCAdminSettingsGoogleReviews::class
  ];

  protected array $instances = [];

  public function __construct() {
    foreach( static::$pages as $page_class ) {
      $this->instances[ $page_class ] = new $page_class();
    }

    add_action('admin_menu', [ $this, 'register_settings_page' ] );
  }

  public static function tabs(): array {
    return apply_filter('lsc_blocks_settings_tabs', static::$tabs);
  }

  public function register_settings_page(): void {
    add_options_page(
      __('LSC Blocks', 'lsc-blocks'),
      __('LSC Blocks', 'lsc-blocks'),
      'manage_options',
      'lsc-blocks-settings',
      [$this, 'render']
    );
  }

  public function render(): void {
    if( empty($this->instances) ) {
      return;
    }

    $pages = $this->instances;
    $current_id = sanitize_text_field( $_GET['tab'] ?? '' );

    $active_page = current( array_filter(
      $this->instances,
      fn( $page ) => $page->get_id() === $current_id
    ) ) ?: reset( $this->instances ); 

    require LSC_SETTINGS_VIEWS . 'settings-page.php';
  }

}