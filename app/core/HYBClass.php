<?php
/**
 * Main class for the plugin
 * 
 * @version 1.0.0
 */

namespace hy\blocks\app\core;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('HYBClass') ) {
  class HYBClass {
    public static $version = '1.0.0';

    public static $name = 'Hiyield Blocks';

    public static $slug = 'hiyield-blocks';

	  protected static $_instance = null;

    public function __construct() {
      self::constants();

      // add controllers
      self::init_controllers();

      // init plugin
      add_action('init', [__CLASS__, 'init']);

      // scripts inline injection
      add_action('wp_footer', [__CLASS__, 'add_inline_scripts']);
    }

    public static function instance() {
      if ( is_null( self::$_instance ) ) {
        self::$_instance = new self();
      }
      return self::$_instance;
    }

    /**
     * Set constants
     * 
     * @since 1.0.0
     */
    public static function constants() {
      // absolute path
      self::define( 'HYB_ABSPATH', trailingslashit(dirname(HYB_FILE)) );
      // admin url
      self::define( 'HYB_URL', self::plugin_url() );
      // path to blocks
      self::define( 'HYB_BLOCK_PATH', trailingslashit(HYB_ABSPATH . 'blocks'));
      // path to views
      self::define( 'HYB_VIEWS', trailingslashit(HYB_ABSPATH . 'app/views'));
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
      // init projects controller
      \hy\blocks\app\controllers\HYBControllerProjects::instance();
    }

    /**
     * Register blocks
     * 
     * @since 1.0.0
     */
    public static function register_blocks() {
      if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
        wp_register_block_metadata_collection( HYB_BLOCK_PATH . 'build', HYB_BLOCK_PATH . 'build/blocks-manifest.php' );
      }

      $manifest_data = require HYB_BLOCK_PATH . 'build/blocks-manifest.php';
      foreach ( array_keys( $manifest_data ) as $block_type ) {
        register_block_type( HYB_BLOCK_PATH . "build/{$block_type}" );
      }
    }


    /**
     * Scripts
     * 
     * @since 1.0.0
     */
    public static function add_inline_scripts() {

      $api_params = wp_json_encode([
        'root' => esc_url_raw( rest_url('hiyield-blocks/v1/') ),
        'nonce' => sanitize_text_field(wp_create_nonce('wp_rest')),
      ]);
      
      // phpcs:ignore WordPress.Security.EscapeOutput
      printf('<script type="text/javascript">var HYB_API = %s</script>', $api_params);
    
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
      return trailingslashit( plugins_url( '/', HYB_FILE ) );
    }
  }
}