<?php
/**
 * Abstract for registering Settings page/tab
 * 
 * @version 1.0.0
 */

namespace lsc\blocks\app\admin\settings;

use lsc\blocks\app\abstracts\LSCAbstractAdminSettings;

if( ! defined('ABSPATH')) {
  exit;
}

class LSCAdminSettingsGeneral extends LSCAbstractAdminSettings {

  /**
   * constructor
   */
  public function __construct() {
    $this->id = 'lsc-blocks-settings-general';
    $this->label = 'General';
    $this->option_key = 'general';
  }

  public function fields(): array {
    return [];
  }

}