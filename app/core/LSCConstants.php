<?php
/**
 * Defines the plugin constants
 *
 * @version 1.0.0
 */

namespace lsc\blocks\app\core;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

class LSCConstants {

  /**
   * Set constants
   *
   * @since 1.0.0
   */
  public static function register(): void {
    // absolute path
    self::define( 'LSC_ABSPATH', trailingslashit(dirname(LSC_FILE)) );
    // admin url
    self::define( 'LSC_URL', self::plugin_url() );
    // path to blocks
    self::define( 'LSC_BLOCK_PATH', trailingslashit(LSC_ABSPATH . 'blocks') );
    // path to views
    self::define( 'LSC_VIEWS', trailingslashit(LSC_ABSPATH . 'app/views') );
    self::define( 'LSC_ADMIN_PAGE_VIEWS', trailingslashit(LSC_ABSPATH . 'app/admin/pages/views') );
    self::define( 'LSC_SETTINGS_VIEWS', trailingslashit(LSC_ABSPATH . 'app/admin/settings/views') );

    // Routes
    self::define( 'LSC_ROUTE_VERSION', 'v1' );
    self::define( 'LSC_ROUTE_PATH', 'lsc-blocks' );

    // Options
    self::define( 'LSC_OPTIONS', 'lsc_blocks_options');
  }

  /**
   * Define the constant if it's not already set
   *
   * @since 1.0.0
   */
  public static function define( string $name, $value ): void {
    if( ! defined($name) ) {
      define($name, $value); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
    }
  }

  /**
   * Get the plugin url.
   *
   * @since 1.0.0
   * @return string
   */
  public static function plugin_url(): string {
    return trailingslashit( plugins_url( '/', LSC_FILE ) );
  }
}