<?php
/**
 * Main class for the plugin
 * 
 * @version 1.0.0
 */

namespace lsc\blocks\app\core;

use \lsc\blocks\app\controllers\LSCControllerProjects;
use \lsc\blocks\app\controllers\LSCControllerPostFeedback;
use \lsc\blocks\app\controllers\LSCControllerGoogleReviews;
use \lsc\blocks\app\core\LSCBlockBindings;
use \lsc\blocks\app\admin\settings\LSCAdminSettingsPage;
use \lsc\blocks\app\admin\pages\LSCAdminPageBusinessInfo;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('LSCClass') ) {
  class LSCClass {
    public static $version = '1.1.0';

    public static $name = 'LSC Blocks';

    public static $slug = 'lsc-blocks';

	  protected static array $instances = [];

    protected static array $controllers = [
      LSCControllerProjects::class,
      LSCControllerPostFeedback::class,
      LSCControllerGoogleReviews::class,
    ];

    protected LSCSettings $settings;

    public function __construct() {
      LSCConstants::register();

      // add controllers
      self::init_controllers();

      $this->settings = new LSCSettings();

      new LSCBlockBindings($this->settings);

      new LSCAdminSettingsPage();
      new LSCAdminPageBusinessInfo();

      new LSCBlockRegistrar($this->settings);

      // scripts inline injection
      new LSCApiScript();
    }

    /**
     * Run the controllers
     * 
     * @since 1.0.0
     */
    public static function init_controllers() {
      foreach ( static::$controllers as $controller_class ) {
        $controller = new $controller_class();
        static::$instances[$controller_class] = $controller;

        $route_configs = $controller_class::route();
      
        if( $route_configs === null ) {
          continue;
        }

        foreach ( $route_configs as $route_config ) {
          new LSCRoute( $controller, $route_config );
        }
      }
    }

    /**
     * Fetch an already-bootstrapped controller instance. Views should use
     * this instead of instantiating a controller themselves.
     */
    public static function controller( string $controller_class ) {
      return static::$instances[$controller_class] ?? null;
    }

  }
}