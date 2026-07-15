<?php
/**
 * Create a route for obtaining project data
 * 
 * @version 1.0.0
 */

namespace lsc\blocks\app\routes;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

use lsc\blocks\app\abstracts\LSCAbstractRoute;
use lsc\blocks\app\controllers\LSCControllerProjects;

class LSCRouteProjects extends LSCAbstractRoute {

  protected $controller;
  
  public function register(): void {
    $this->register_route('projects-grid', [
      [
        'methods' => 'GET',
        'callback' => [$this, 'get_grid_request'],
      ]
    ]);
  }

  public function get_grid_request($request) {
    $this->controller = LSCControllerProjects::instance();
    
    return $this->controller->get_grid_rest_request($request);
  }

  public function permissions() {
    return true;
  }

}