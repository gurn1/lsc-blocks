<?php
/**
 * Abstract for registering Settings page/tab
 * 
 * @version 1.0.0
 */

namespace lsc\blocks\app\abstracts;

if( ! defined('ABSPATH')) {
  exit;
}

use lsc\blocks\app\admin\LSCAdminFieldHandler;
use \lsc\blocks\app\core\LSCSettings;

abstract class LSCAbstractAdminSettings extends LSCAbstractAdminPageContent {

  /**
   * Option key
   */
  protected string $option_name = '';

  /**
   * Settings page id
   */
  protected string $id = '';

  /**
   * Settings page label
   */
  protected string $label = '';

  /**
   * Settings capability
   */
  protected string $capability = 'manage_options';

  /**
   * settings option key
   */
  protected string $option_key = '';

  protected LSCAdminFieldHandler $field_handler;

  protected LSCSettings $settings;

  /**
   * Settings
   */

  /**
   * constructor
   */
  public function __construct() {
    $this->settings = new LSCSettings();
    $this->field_handler = new LSCAdminFieldHandler();
    $this->option_name = $this->settings::OPTION_NAME;

    add_action('admin_init', [$this, 'maybe_save']);
  }

  public function get_id(): string {
    return $this->id;
  }

  public function get_label(): string {
    return $this->label;
  }

  public function get_capability(): string {
    return $this->capability;
  }

  public function get(): array {
    $options = $this->settings->all();

    if ( ! is_array($options) ) {
      return [];
    }

    return $options[ $this->option_key ] ?? [];
  }

  abstract public function fields(): array;

  public function render_field( string $key, array $field, $value = '' ) {
    if( empty($value ) ) {
      $data = $this->get();
      $value = $data[$key] ?? null;
    }

    return $this->field_handler->render_field( $this->option_name , $this->option_key, $key, $field, $value );
  }

  public function maybe_save(): void {
    if ( ! isset($_POST['lsc_blocks_save']) || $_POST['lsc_blocks_save'] !== $this->id ) {
      return;
    }

    check_admin_referer( 'lsc_blocks_save_' . $this->id, 'lsc_blocks_nonce' );

    if ( ! current_user_can($this->capability) ) {
      wp_die( esc_html__('You do not have permission to do this.', 'lsc-blocks') );
    }

    $submitted = wp_unslash( $_POST[ $this->option_name  ][ $this->option_key ] ?? [] );

    $all = $this->settings->all();
    $all[ $this->option_key ] = $this->field_handler->sanitize( $this->fields(), $submitted );
    update_option( $this->option_name , $all );

    wp_safe_redirect( add_query_arg('updated', 'true', wp_get_referer()) );
    exit;
  }
  
  public function render(): void {
    if ( ! current_user_can( $this->capability ) ) {
      wp_die( esc_html__('You do not have permission to access this page.', 'lsc-blocks') );
    }

    $fields = $this->fields();

    require LSC_SETTINGS_VIEWS . 'settings-tab.php';
  }

}