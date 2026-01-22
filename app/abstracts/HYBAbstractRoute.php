<?php
/**
 * Abstract class for registering routes
 * 
 * @version 1.0.0
 */

namespace hy\blocks\app\abstracts;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

abstract class HYBAbstractRoute {
  /**
   * Define the path
   */
  protected string $path = 'hiyield-blocks';

  /**
   * Define the route version
   */
  protected string $version = 'v1';

  function __construct() {
     add_action('rest_api_init', [$this, 'register']);
  }

  /**
   * Implement register method for child classes
   */
  abstract public function register(): void;

  /**
   * register the route with WP REST API
   */
  protected function register_route($route, $args) {
    $namespace = trailingslashit($this->path) . $this->version;

    foreach ($args as &$endpoint) {
      if (empty($endpoint['permission_callback'])) {
        $endpoint['permission_callback'] = [$this, 'default_permissions'];
      }
    }

    register_rest_route($namespace, $route, $args);
  }

  /**
   * Default nonce check for X-WP-Nonce
   */
  public function default_permissions($request) {
    $nonce = $request->get_header('X-WP-Nonce');

    if (!wp_verify_nonce($nonce, 'wp_rest')) {
      return false;
    }

    return $this->permissions();
  }

  /**
   * Permissions, set by the child, default true
   */
  protected function permissions() {
    return true;
  }
}