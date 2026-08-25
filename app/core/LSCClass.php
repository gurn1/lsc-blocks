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
      self::constants();

      // add controllers
      self::init_controllers();

      $this->settings = new LSCSettings();

      // Temporary location for calling admin settings
      new LSCBlockBindings($this->settings);

      new LSCAdminSettingsPage();
      new LSCAdminPageBusinessInfo();

      // init plugin
      add_action('init', [__CLASS__, 'init']);

      // scripts inline injection
      add_action('wp_footer', [__CLASS__, 'add_inline_scripts']);
    }

    /**
     * Set constants
     * 
     * @since 1.0.0
     */
    public static function constants() {
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
     * Run on initilisation
     * 
     * @since 1.0.0
     */
    public static function init() {
      // add block manifest data 
      self::register_blocks();
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

    /**
     * Register blocks
     * 
     * @since 1.0.0
     */
    public static function register_blocks() {
      if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
        wp_register_block_metadata_collection( LSC_BLOCK_PATH . 'build', LSC_BLOCK_PATH . 'build/blocks-manifest.php' );
      }

      $manifest_data = require LSC_BLOCK_PATH . 'build/blocks-manifest.php';
      foreach ( array_keys( $manifest_data ) as $block_type ) {
        register_block_type( LSC_BLOCK_PATH . "build/{$block_type}" );
      }
    }


    /**
     * Scripts
     * 
     * @since 1.0.0
     */
    public static function add_inline_scripts() {

      $api_params = wp_json_encode([
        'root' => esc_url_raw( rest_url( trailingslashit(LSC_ROUTE_PATH) . LSC_ROUTE_VERSION . '/' ) ),
        'nonce' => sanitize_text_field(wp_create_nonce('wp_rest')),
      ]);
      
      // phpcs:ignore WordPress.Security.EscapeOutput
      printf('<script type="text/javascript">var LSC_API = %s</script>', $api_params);
    
    }

    /**
     * Define the constant if it's not already set
     * 
     * @since 1.0.0
     */
    public static function define($name, $value) {
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
    public static function plugin_url() {
      return trailingslashit( plugins_url( '/', LSC_FILE ) );
    }

  }
}