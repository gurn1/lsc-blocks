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

class LSCAdminSettingsGoogleReviews extends LSCAbstractAdminSettings {

  /**
   * constructor
   */
  public function __construct() {
    $this->id = 'lsc-blocks-settings-google-reviews';
    $this->label = 'Google Reviews';
    $this->option_key = 'google-reviews';

    parent::__construct();
  }

  public function fields(): array {
    return [
      'api_key' => [ 
        'type' => 'password', 
        'label' => 'Google Places API Key', 
      ]
    ];
  }

}