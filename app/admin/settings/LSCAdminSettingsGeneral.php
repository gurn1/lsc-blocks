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

    parent::__construct();
  }

  public function fields(): array {
    $options = [];

    foreach ( $this->get_block_manifest() as $block_name => $block_data ) {
      if( ! empty($block_data['parent']) ) {
        continue;
      }
      $options[$block_name] = $block_data['title'] ?? $block_name;
    }

    return [
      'disabled_blocks' => [
        'type'    => 'checkbox_group',
        'label'   => __('Disable Blocks', 'lsc-blocks'),
        'options' => $options,
      ],
    ];
  }

  protected function get_block_manifest(): array {
    $manifest_file = LSC_BLOCK_PATH . 'build/blocks-manifest.php';

    if ( ! file_exists($manifest_file) ) {
      return [];
    }

    return require $manifest_file;
  }

}