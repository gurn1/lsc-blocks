<?php
/**
 * Class for registering route
 * 
 * @version 1.0.0
 */

namespace lsc\blocks\app\core;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

use lsc\blocks\app\abstracts\LSCAbstractController;

class LSCRoute {

  protected string $path = LSC_ROUTE_PATH;
  protected string $version = LSC_ROUTE_VERSION;

  public function __construct( protected LSCAbstractController $controller, protected array $config ) {
     add_action('rest_api_init', [$this, 'register']);
  }

  /**
   * Implement register method
   */
  public function register(): void {
    $namespace = trailingslashit($this->path) . $this->version;

    register_rest_route($namespace, $this->config['endpoint'], [
      'methods'             => $this->config['methods'] ?? 'GET',
      'callback'            => [$this->controller, 'response'],
      'permission_callback' => $this->config['permission_callback'] ?? [$this, 'default_permissions'],
    ]);
  }

  /**
   * Default nonce check for X-WP-Nonce
   */
  public function default_permissions($request): bool {
    return wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest') !== false;
  }

}