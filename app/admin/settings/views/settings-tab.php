<?php
if( ! defined('ABSPATH')) :
  exit;
endif;
?>

<form method="post" action="">
  <?php wp_nonce_field( 'lsc_blocks_save_' . $this->id, 'lsc_blocks_nonce' ); ?>
  <input type="hidden" name="lsc_blocks_save" value="<?php echo esc_attr($this->id); ?>" />

  <table class="form-table">
    <?php foreach ( $fields as $key => $field ) : ?>
      <tr>
        <th scope="row">
          <label for="lsc-field-<?php echo esc_attr($this->id . '-' . $key); ?>">
            <?php echo esc_html($field['label']); ?>
          </label>
        </th>
        <td><?php $this->render_field($key, $field); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <?php submit_button(); ?>
</form>