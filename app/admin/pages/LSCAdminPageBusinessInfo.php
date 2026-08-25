<?php
namespace lsc\blocks\app\admin\pages;

use lsc\blocks\app\abstracts\LSCAbstractAdminPage;
use lsc\blocks\app\abstracts\LSCAbstractAdminPageContent;
use lsc\blocks\app\admin\settings\LSCAdminSettingsBusinessInfo;

class LSCAdminPageBusinessInfo extends LSCAbstractAdminPage {

  protected string $parent_slug       = 'tools.php';
  protected string $page_title        = 'Business Info';
  protected string $menu_title        = 'Business Info';
  protected string $menu_slug         = 'lsc-blocks-business-info';
  protected string $page_description  = 'Set this once, then bind it into any Paragraph, Heading, or Button block anywhere on the site.';

  protected function build_content(): LSCAbstractAdminPageContent {
    return new LSCAdminSettingsBusinessInfo();
  }
}