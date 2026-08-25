<?php
/**
 * Class for getting settings options
 * 
 * @version 1.1.0
 */

namespace lsc\blocks\app\core;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}


class LSCSettings {
  /**
   * Option key
   */
  public const OPTION_NAME = '_lsc_blocks_settings';

  /**
   * Get a setting.
   */
  public function get(string $key, mixed $default = null): mixed {
      $settings = get_option(self::OPTION_NAME, []);
      return $settings[$key] ?? $default;
  }

  /**
   * Get all settings.
   */
  public function all(): array {
      return get_option(self::OPTION_NAME, []);
  }
}