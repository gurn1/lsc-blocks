<?php
/**
 * Business info settings - phone, email, address, opening hours. Used by
 * the business-info Block Bindings source, so authors can bind core
 * blocks (Paragraph, Heading, etc.) to this data anywhere on the site.
 *
 * Registers onto the settings page LSCSettingsGoogleReviews creates
 * ('lsc-blocks-settings') rather than creating its own page - each
 * settings domain gets its own class, but they share one admin screen.
 *
 * @since 1.3.0
 */

namespace lsc\blocks\app\admin;

if( ! defined('ABSPATH')) {
  exit;
}

class LSCSettingsBusinessInfo {

  protected const OPTION_KEY = 'lsc_blocks_business_info';

  protected const DAYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

  public function __construct() {
    add_action('admin_init', [$this, 'register_setting']);
    add_action('lsc_blocks_settings_page', [$this, 'render_fields']);
  }

  public function register_setting(): void {
    register_setting('lsc_blocks_business_info', static::OPTION_KEY, [
      'type'              => 'array',
      'sanitize_callback' => [$this, 'sanitize'],
      'default'           => [],
    ]);
  }

  public function sanitize( $input ): array {
    $hours = [];

    foreach ( static::DAYS as $day ) {
      $hours[$day] = [
        'closed' => ! empty($input['hours'][$day]['closed']),
        'open'   => sanitize_text_field($input['hours'][$day]['open'] ?? ''),
        'close'  => sanitize_text_field($input['hours'][$day]['close'] ?? ''),
      ];
    }

    return [
      'phone'   => sanitize_text_field($input['phone'] ?? ''),
      'email'   => sanitize_email($input['email'] ?? ''),
      'address' => sanitize_textarea_field($input['address'] ?? ''),
      'hours'   => $hours,
    ];
  }

  public function render_fields(): void {
    $data = get_option( static::OPTION_KEY, [] );
    ?>
    <h2><?php echo esc_html__('Business Info', 'lsc-blocks'); ?></h2>
    <p class="description">
      <?php echo esc_html__('Set this once, then bind it into any Paragraph, Heading, or Button block anywhere on the site.', 'lsc-blocks'); ?>
    </p>

    <form method="post" action="options.php">
      <?php settings_fields('lsc_blocks_business_info'); ?>

      <table class="form-table">
        <tr>
          <th scope="row"><label for="lsc-business-phone"><?php echo esc_html__('Phone', 'lsc-blocks'); ?></label></th>
          <td><input type="text" id="lsc-business-phone" name="<?php echo esc_attr(static::OPTION_KEY); ?>[phone]" value="<?php echo esc_attr($data['phone'] ?? ''); ?>" class="regular-text" /></td>
        </tr>
        <tr>
          <th scope="row"><label for="lsc-business-email"><?php echo esc_html__('Email', 'lsc-blocks'); ?></label></th>
          <td><input type="email" id="lsc-business-email" name="<?php echo esc_attr(static::OPTION_KEY); ?>[email]" value="<?php echo esc_attr($data['email'] ?? ''); ?>" class="regular-text" /></td>
        </tr>
        <tr>
          <th scope="row"><label for="lsc-business-address"><?php echo esc_html__('Address', 'lsc-blocks'); ?></label></th>
          <td><textarea id="lsc-business-address" name="<?php echo esc_attr(static::OPTION_KEY); ?>[address]" rows="3" class="large-text"><?php echo esc_textarea($data['address'] ?? ''); ?></textarea></td>
        </tr>
      </table>

      <h3><?php echo esc_html__('Opening Hours', 'lsc-blocks'); ?></h3>
      <table class="form-table">
        <?php foreach ( static::DAYS as $day ) :
          $day_data = $data['hours'][$day] ?? [];
          $closed   = ! empty($day_data['closed']);
        ?>
          <tr>
            <th scope="row"><?php echo esc_html( ucfirst($day) ); ?></th>
            <td>
              <label>
                <input type="checkbox" name="<?php echo esc_attr(static::OPTION_KEY); ?>[hours][<?php echo esc_attr($day); ?>][closed]" value="1" <?php checked($closed); ?> />
                <?php echo esc_html__('Closed', 'lsc-blocks'); ?>
              </label>
              <input type="time" name="<?php echo esc_attr(static::OPTION_KEY); ?>[hours][<?php echo esc_attr($day); ?>][open]" value="<?php echo esc_attr($day_data['open'] ?? ''); ?>" />
              <?php echo esc_html__('to', 'lsc-blocks'); ?>
              <input type="time" name="<?php echo esc_attr(static::OPTION_KEY); ?>[hours][<?php echo esc_attr($day); ?>][close]" value="<?php echo esc_attr($day_data['close'] ?? ''); ?>" />
            </td>
          </tr>
        <?php endforeach; ?>
      </table>

      <?php submit_button( __('Save Business Info', 'lsc-blocks') ); ?>
    </form>
    <?php
  }

  public static function get(): array {
    return get_option( static::OPTION_KEY, [] );
  }
}