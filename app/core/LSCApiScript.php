<?php
/**
 * Outputs the REST API details for front-end scripts
 *
 * @version 1.0.0
 */

namespace lsc\blocks\app\core;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

class LSCApiScript {

  public function __construct() {
    add_action( 'wp_footer', [ $this, 'output' ] );
  }

  /**
   * Scripts
   *
   * @since 1.0.0
   */
  public function output(): void {

    $api_params = wp_json_encode([
      'root' => esc_url_raw( rest_url( trailingslashit(LSC_ROUTE_PATH) . LSC_ROUTE_VERSION . '/' ) ),
      'nonce' => sanitize_text_field(wp_create_nonce('wp_rest')),
    ]);

    // phpcs:ignore WordPress.Security.EscapeOutput
    printf('<script type="text/javascript">var LSC_API = %s</script>', $api_params);

  }
}