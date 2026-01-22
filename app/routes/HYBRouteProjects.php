<?php
/**
 * Create a route for obtaining project data
 * 
 * @version 1.0.0
 */

namespace hy\blocks\app\routes;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

use hy\blocks\app\abstracts\HYBAbstractRoute;
use hy\blocks\app\controllers\HYBControllerProjects;

class HYBRouteProjects extends HYBAbstractRoute {

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
    $this->controller = HYBControllerProjects::instance();
    
    return $this->controller->get_grid_rest_request($request);
  }

  public function permissions() {
    return true;
  }

}