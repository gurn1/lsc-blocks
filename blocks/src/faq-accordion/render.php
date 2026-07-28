<?php
if( ! defined('ABSPATH')) {
  exit;
}

$allow_multiple = ! empty($attributes['allowMultipleOpen']);
$items = $block->parsed_block['innerBlocks'] ?? [];

if ( empty($items) ) {
  return;
}

$schema_entities = [];
?>

<div
  <?php echo get_block_wrapper_attributes(); ?>
  data-wp-interactive="lsc-faq-accordion"
  <?php echo wp_interactivity_data_wp_context([ 'allowMultiple' => $allow_multiple ]); ?>
>
  <?php foreach ( $items as $index => $item ) :
    $item_attrs = $item['attrs'] ?? [];
    $question   = wp_kses_post( $item_attrs['question'] ?? '' );
    $answer     = wp_kses_post( $item_attrs['answer'] ?? '' );
    $category   = sanitize_text_field( $item_attrs['category'] ?? '' );

    if ( empty($question) || empty($answer) ) {
      continue;
    }

    $schema_entities[] = [
      '@type'          => 'Question',
      'name'           => wp_strip_all_tags($question),
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text'  => wp_strip_all_tags($answer),
      ],
    ];
    ?>
    <div class="lsc-faq-item" data-category="<?php echo esc_attr($category); ?>">
      <h3>
        <button
          type="button"
          class="lsc-faq-question"
          aria-expanded="false"
          data-wp-on--click="actions.toggleItem"
          data-wp-bind--aria-expanded="context.isOpen"
        >
          <?php echo $question; ?>
        </button>
      </h3>
      <div class="lsc-faq-answer" data-wp-bind--hidden="!context.isOpen">
        <?php echo $answer; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php if ( ! empty($schema_entities) ) : ?>
  <script type="application/ld+json">
    <?php echo wp_json_encode([
      '@context'   => 'https://schema.org',
      '@type'      => 'FAQPage',
      'mainEntity' => $schema_entities,
    ]); ?>
  </script>
<?php endif; ?>