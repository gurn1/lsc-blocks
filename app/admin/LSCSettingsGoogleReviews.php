<?php
/**
 * Temporary settings screen for the Google API key, ahead of the full
 * block-options system planned for later. Centralizing the option name
 * and the getter here means only this file needs to change when that lands.
 *
 * @since 1.2.0
 */

namespace lsc\blocks\app\admin;

if( ! defined('ABSPATH')) {
  exit;
}

class LSCSettingsGoogleReviews {

  protected const OPTION_KEY = 'lsc_blocks_google_api_key';

  public function __construct() {
    add_action('admin_menu', [$this, 'register_page']);
    add_action('admin_init', [$this, 'register_setting']);
  }

  public function register_page(): void {
    add_options_page(
      __('LSC Blocks - Google Reviews', 'lsc-blocks'),
      __('LSC Blocks', 'lsc-blocks'),
      'manage_options',
      'lsc-blocks-settings',
      [$this, 'render_page']
    );
  }

  public function register_setting(): void {
    register_setting('lsc_blocks_google_reviews', static::OPTION_KEY, [
      'type'              => 'string',
      'sanitize_callback' => 'sanitize_text_field',
      'default'           => '',
    ]);
  }

  public function render_page(): void {
    if ( ! current_user_can('manage_options') ) {
      return;
    }
    ?>
    <div class="wrap">
      <h1><?php echo esc_html__('LSC Blocks - Google Reviews', 'lsc-blocks'); ?></h1>

      <?php if ( empty( get_option(static::OPTION_KEY, '') ) ) : ?>
        <div class="notice notice-info inline">
          <p><?php echo esc_html__('No API key configured - Google Reviews blocks are currently showing demo data.', 'lsc-blocks'); ?></p>
        </div>
      <?php endif; ?>

      <form method="post" action="options.php">
        <?php settings_fields('lsc_blocks_google_reviews'); ?>
        <table class="form-table">
          <tr>
            <th scope="row">
              <label for="<?php echo esc_attr(static::OPTION_KEY); ?>">
                <?php echo esc_html__('Google Places API Key', 'lsc-blocks'); ?>
              </label>
            </th>
            <td>
              <input
                type="password"
                id="<?php echo esc_attr(static::OPTION_KEY); ?>"
                name="<?php echo esc_attr(static::OPTION_KEY); ?>"
                value="<?php echo esc_attr( get_option(static::OPTION_KEY, '') ); ?>"
                class="regular-text"
                autocomplete="off"
              />
              <p class="description">
                <?php echo esc_html__('Used server-side only. Never exposed to visitors or stored in block content.', 'lsc-blocks'); ?>
              </p>
            </td>
          </tr>
        </table>
        <?php submit_button(); ?>
      </form>
    </div>
    <?php
  }

  /**
   * Centralized getter, so nothing else needs to know the option name.
   */
  public static function api_key(): string {
    return (string) get_option( static::OPTION_KEY, '' );
  }
}