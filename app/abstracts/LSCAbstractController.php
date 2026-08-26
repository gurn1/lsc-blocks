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

use lsc\blocks\app\core\LSCSettings;

abstract class LSCAbstractController {

  protected LSCAbstractModel $model;

  final public function __construct( ?LSCAbstractModel $model = null ) {
    $model_class = static::model_class();
    $this->model = $model ?? new $model_class();

    if ( ! $this->is_enabled() ) {
      return;
    }

    $this->boot();
  }

  abstract protected static function model_class(): string;

  protected function boot(): void {
    add_action('init', [$this, 'register']);
  }

  /**
   * REST route config, if this block exposes one. Return null if it doesn't need a REST endpoint at all
   * e.g static block
   */
  public static function route(): ?array {
    return null;
  }

  /**
   * Unique key used in the toggle settings (and to match this block
   * against the enabled-blocks option). e.g. 'projects-grid'.
   */
  abstract public static function identifier(): string; // must match the block id

  /**
   * Whether this block is switched on. Stored as a single option holding
   * an array of enabled block keys, so toggling doesn't need one
   * option row per block.
   */
  public function is_enabled(): bool {
    $disabled = (new LSCSettings())->get_path('general.disabled_blocks', []);

    return ! in_array( static::identifier(), (array) $disabled, true );
  }
  
  abstract public function register(): void;

}