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

class LSCAdminSettingsBusinessInfo extends LSCAbstractAdminSettings {

  /**
   * constructor
   */
  public function __construct() {
    $this->id = 'lsc-blocks-settings-business-info';
    $this->label = 'Google Reviews';
    $this->option_key = 'business-info';

    parent::__construct();
  }

  public function fields(): array {
    return [
      'phone'   => [ 'type' => 'text', 'label' => __('Phone', 'lsc-blocks') ],
      'email'   => [ 'type' => 'email', 'label' => __('Email', 'lsc-blocks') ],
      'address' => [ 'type' => 'textarea', 'label' => __('Address', 'lsc-blocks') ],
      'hours'   => [
        'type'  => 'hours',
        'label' => __('Opening Hours', 'lsc-blocks'),
        'days'  => [
          'monday'    => __('Monday', 'lsc-blocks'),
          'tuesday'   => __('Tuesday', 'lsc-blocks'),
          'wednesday' => __('Wednesday', 'lsc-blocks'),
          'thursday'  => __('Thursday', 'lsc-blocks'),
          'friday'    => __('Friday', 'lsc-blocks'),
          'saturday'  => __('Saturday', 'lsc-blocks'),
          'sunday'    => __('Sunday', 'lsc-blocks'),
        ],
      ],
    ];
  }

}