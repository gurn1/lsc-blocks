<?php
/**
 * Field handler
 */

namespace lsc\blocks\app\admin;

if( ! defined('ABSPATH')) {
  exit;
}

class LSCAdminFieldHandler {
  /**
   * Clean the data
   */
  public function sanitize( $section_fields, $input ): array {
    $sanitized = [];

    foreach ( $section_fields as $key => $field ) {
      $sanitized[$key] = $this->sanitize_field( $input[$key] ?? null, $field );
    }

    return $sanitized;
  }

  protected function sanitize_field( $value, array $field ) {
    return match ( $field['type'] ) {
      'email'    => sanitize_email( $value ?? '' ),
      'textarea' => sanitize_textarea_field( $value ?? '' ),
      'checkbox' => ! empty( $value ),
      'hours' => $this->sanitize_hours($value),
      default    => sanitize_text_field( $value ?? '' ),
    };
  }

  /**
   * Clean hours field data.
   */
  protected function sanitize_hours($value): array {
    if (!is_array($value)) {
        return [];
    }

    $sanitized = [];

    foreach ($value as $day => $day_data) {
        if (!is_array($day_data)) {
            continue;
        }

        $sanitized[$day] = [
            'closed' => !empty($day_data['closed']),
            'open'   => sanitize_text_field($day_data['open'] ?? ''),
            'close'  => sanitize_text_field($day_data['close'] ?? ''),
        ];
    }

    return $sanitized;
  }

  /**
   * Get the field output
   */
  public function render_field( string $option_name, string $option_sub_key, string $field_key, array $field, $value ): void {
    $name = sprintf('%s[%s][%s]', $option_name, $option_sub_key, $field_key);
    $id   = sprintf('lsc-field-%s-%s', $option_sub_key, $field_key);
    
    switch ( $field['type'] ) {
      case 'select':
        printf('<select name="%s" id="%s">', esc_attr($name), esc_attr($id));
        foreach ( $field['options'] as $option_value => $option_label ) {
          printf(
            '<option value="%s" %s>%s</option>',
            esc_attr($option_value),
            selected($value, $option_value, false),
            esc_html($option_label)
          );
        }
        echo '</select>';
        break;

      case 'checkbox':
        printf(
          '<input type="checkbox" name="%s" id="%s" value="1" %s />',
          esc_attr($name), esc_attr($id), checked($value, true, false)
        );
        break;

      case 'textarea':
        printf(
          '<textarea name="%s" id="%s" rows="3" class="large-text">%s</textarea>',
          esc_attr($name), esc_attr($id), esc_textarea($value ?? '')
        );
        break;

      case 'hours':
        foreach ( $field['days'] as $day => $day_label ) {
          $day_data = $value[$day] ?? [];
          $closed   = ! empty($day_data['closed']);

          printf(
            '<p><strong>%s</strong><br /><label><input type="checkbox" name="%s[%s][closed]" value="1" %s /> %s</label> <input type="time" name="%s[%s][open]" value="%s" /> %s <input type="time" name="%s[%s][close]" value="%s" /></p>',
            esc_html($day_label),
            esc_attr($name), esc_attr($day), checked($closed, true, false),
            esc_html__('Closed', 'lsc-blocks'),
            esc_attr($name), esc_attr($day), esc_attr($day_data['open'] ?? ''),
            esc_html__('to', 'lsc-blocks'),
            esc_attr($name), esc_attr($day), esc_attr($day_data['close'] ?? '')
          );
        }
        break;

      default:
        printf(
          '<input type="%s" name="%s" id="%s" value="%s" class="regular-text" autocomplete="off" />',
          esc_attr($field['type'] ?? 'text'), esc_attr($name), esc_attr($id), esc_attr($value ?? '')
        );
        break;
    }
  }
}