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
    return $this->all()[$key] ?? $default;
  }

  /**
   * Get all settings.
   */
  public function all(): array {
    $settings = get_option(self::OPTION_NAME, []);
    return is_array($settings) ? $settings : [];
  }

  /**
   * Read a nested value via dot notation
   */
  public function get_path(string $path, mixed $default = null): mixed {
    $value = $this->all();

    foreach ( explode('.', $path) as $segment ) {
      if ( ! is_array($value) || ! array_key_exists($segment, $value) ) {
        return $default;
      }
      $value = $value[$segment];
    }

    return $value;
  }

  /**
   * update values
   */
  public function set(string $key, mixed $value): bool {
    $all = $this->all();
    $all[$key] = $value;
    return update_option(self::OPTION_NAME, $all);
  }
}