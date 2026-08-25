<?php
/**
 * Abstract for registering admin standalone pages
 * 
 * @version 1.0.0
 */

namespace lsc\blocks\app\abstracts;

if( ! defined('ABSPATH')) {
  exit;
}

abstract class LSCAbstractAdminPage {

  protected LSCAbstractAdminPageContent $content;

  protected string $parent_slug = 'tools.php';
  protected string $page_title  = '';
  protected string $menu_title  = '';
  protected string $page_description = '';
  protected string $menu_slug   = '';
  protected string $capability  = 'manage_options';

  public function __construct() {
    $this->content = $this->build_content();

    add_action('admin_menu', [$this, 'register_page']);
  }

  abstract protected function build_content(): LSCAbstractAdminPageContent;

  public function register_page(): void {
    add_submenu_page(
      $this->parent_slug,
      $this->page_title,
      $this->menu_title,
      $this->capability,
      $this->menu_slug,
      [$this, 'render']
    );
  }

  public function render(): void {
    require LSC_ADMIN_PAGE_VIEWS . 'admin-page.php';
  }
}