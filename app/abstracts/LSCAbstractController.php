<?php
/**
 * Abstract class for registering controllers
 * 
 * @version 1.0.0
 */

namespace lsc\blocks\app\abstracts;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

abstract class LSCAbstractController {

  protected LSCAbstractModel $model;

  final public function __construct( LSCAbstractModel $model = null ) {

    if( ! $this->is_enabled() ) {
      return; // disable the block
    }

    $model_class = static::model_class();
    $this->model = $model ?? new $model_class();

    $this->boot();
  }

  abstract protected static function model_class(): string;

  protected function boot(): void {
    add_action('init', [$this, 'register']);
  }

  /**
   * Whether this block is switched on. Stored as a single option holding
   * an array of enabled block keys, so toggling doesn't need one
   * option row per block.
   */
  public function is_enabled(): bool {
    $enabled = get_option(LSC_OPTIONS, null);

    if( is_null($enabled) ) {
      return true; // default if no option saved
    }

    return in_array( static::identifier(), (array) $enabled, true );
  }

  /**
   * Unique key used in the toggle settings (and to match this block
   * against the enabled-blocks option). e.g. 'projects-grid'.
   */
  abstract public static function identifier(): string;
  
  abstract public function register(): void;
  abstract protected function request( \WP_Rest_Request $request ): array;

  public function response( \WP_Rest_Request $request ) {

  }

}