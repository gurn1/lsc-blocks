<?php
/**
 * Registers the compiled blocks
 *
 * @version 1.0.0
 */

namespace lsc\blocks\app\core;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

class LSCBlockRegistrar {

  protected LSCSettings $settings;

  public function __construct( LSCSettings $settings ) {
    $this->settings = $settings;

    add_action( 'init', [ $this, 'register' ] );
  }

  /**
   * Register blocks
   *
   * @since 1.0.0
   */
  public function register(): void {
    if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
      wp_register_block_metadata_collection( LSC_BLOCK_PATH . 'build', LSC_BLOCK_PATH . 'build/blocks-manifest.php' );
    }

    $disabled = $this->settings->get_path('general.disabled_blocks', []);

    $manifest_data = require LSC_BLOCK_PATH . 'build/blocks-manifest.php';
    foreach ( array_keys( $manifest_data ) as $block_type ) {
      if( in_array( $block_type, (array) $disabled, true ) ) {
        continue;
      }
      register_block_type( LSC_BLOCK_PATH . "build/{$block_type}" );
    }
  }
}