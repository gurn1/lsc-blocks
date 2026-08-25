<?php
/**
 * Registers the "business info" Block Bindings source, letting authors
 * bind core blocks (Paragraph, Heading, Button, etc.) to centrally
 * managed business data instead of retyping it across the site.
 *
 * @since 1.3.0
 */

namespace lsc\blocks\app\core;

if( ! defined('ABSPATH')) {
  exit;
}

use lsc\blocks\app\admin\LSCSettingsBusinessInfo;

class LSCBlockBindings {

  protected LSCSettings $settings;

  public function __construct(LSCSettings $settings) {
    $this->settings = $settings;

    add_action('init', [$this, 'register']);
    add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_script']);
  }

  public function register(): void {
    register_block_bindings_source('lsc-blocks/business-info', [
      'label'              => __('Business Info', 'lsc-blocks'),
      'get_value_callback' => [$this, 'get_value'],
    ]);
  }

  public function get_value( array $source_args ): string {
    $data = $this->settings->get('business-info');
    $key  = $source_args['key'] ?? '';

    return match (true) {
      $key === 'phone'   => $data['phone'] ?? '',
      $key === 'email'   => $data['email'] ?? '',
      $key === 'address' => $data['address'] ?? '',
      $key === 'hours_today' => $this->hours_today( $data['hours'] ?? [] ),
      str_starts_with($key, 'hours_') => $this->hours_for_day( $data['hours'] ?? [], substr($key, 6) ),
      default => '',
    };
  }

  protected function hours_today( array $hours ): string {
    $today = strtolower( wp_date('l') ); // 'monday', 'tuesday', etc, in the site's own timezone
    return $this->hours_for_day( $hours, $today );
  }

  protected function hours_for_day( array $hours, string $day ): string {
    $day_data = $hours[$day] ?? [];

    if ( empty($day_data) || ! empty($day_data['closed']) ) {
      return __('Closed', 'lsc-blocks');
    }

    if ( empty($day_data['open']) || empty($day_data['close']) ) {
      return '';
    }

    return sprintf('%s - %s', $day_data['open'], $day_data['close']);
  }

  public function enqueue_editor_script(): void {
    wp_enqueue_script(
      'lsc-blocks-block-bindings',
      LSC_URL . 'app/assets/admin/js/lsc-block-admin-bindings.js',
      [ 'wp-blocks', 'wp-i18n' ],
      LSCClass::$version,
      true
    );

    $data = $this->settings->get('business-info');

    wp_add_inline_script(
      'lsc-blocks-block-bindings',
      'window.LSC_BUSINESS_INFO = ' . wp_json_encode([
        'phone'      => $data['phone'] ?? '',
        'email'      => $data['email'] ?? '',
        'address'    => $data['address'] ?? '',
        'hoursToday' => $this->hours_today( $data['hours'] ?? [] ),
      ]) . ';',
      'before'
    );
  }
}