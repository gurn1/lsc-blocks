<?php
/**
 * Class for registering route
 * 
 * @version 1.1.0
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
   *
   * Supports either a single handler (the original shape - 'methods',
   * 'callback', 'permission_callback' directly on $config) or multiple
   * handlers on the same endpoint via $config['handlers'].
   */
  public function register(): void {
    $namespace = trailingslashit($this->path) . $this->version;
    $handlers  = $this->config['handlers'] ?? [ $this->config ];

    register_rest_route($namespace, $this->config['endpoint'], array_map(
      fn( $handler ) => [
        'methods'             => $handler['methods'] ?? 'GET',
        'callback'            => [ $this->controller, $handler['callback'] ?? 'response' ],
        'permission_callback' => $handler['permission_callback'] ?? [ $this, 'default_permissions' ],
        'args'                => $handler['args'] ?? [],
      ],
      $handlers
    ));
  }

  /**
   * Default nonce check for X-WP-Nonce
   */
  public function default_permissions($request): bool {
    return wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest') !== false;
  }
}