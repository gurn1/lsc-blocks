<?php
/**
 * Plugin Name: Hiyield Blocks
 * Description: Add custom Gutenberg blocks, including a dynamic Project Archive block.
 * Version: 1.0.0
 * Author: Luke Clifton
 * Author URI: https://www.lscwebdesign.co.uk
 * Text Domain: hiyield-blocks
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.5
 * Requires PHP: 8.0
 *
 * @package hiyield-blocks
 */

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! defined( 'HYB_FILE' ) ) {
  define( 'HYB_FILE', __FILE__ );
}

/**
 * Add PSR-4 Autoloader from composer
 * 
 * @since 1.0.0
 */
require __DIR__ . '/vendor/autoload.php';


/**
 * Instantiate main plugin class
 * 
 * @since 1.0.0
 */
\hy\blocks\app\core\HYBClass::instance();

/**
 * Plugin Activation 
 * 
 * @since 1.0.0
 */
function HYB_plugin_activation() {
  update_option('hyb_plugin_activation', true);
}
register_activation_hook(__FILE__, 'HYB_plugin_activation');

/**
 * Plugin Deactivation
 * 
 * @since 1.0.0
 */
function HYB_plugin_deactivation() {
  flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'HYB_plugin_deactivation');