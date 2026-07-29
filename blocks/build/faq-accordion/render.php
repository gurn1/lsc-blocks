<?php
if( ! defined('ABSPATH')) {
  exit;
}

$allow_multiple = ! empty($attributes['allowMultipleOpen']);
$categories     = $attributes['categories'] ?? [];
$items          = $block->parsed_block['innerBlocks'] ?? [];

if ( empty($items) ) {
  return;
}

$schema_entities = [];
$rendered_items  = [];

foreach ( $items as $index => $item ) {
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

  $rendered_items[] = [
    'id'       => 'faq-item-' . $index,
    'question' => $question,
    'answer'   => $answer,
    'category' => $category,
  ];
}

if ( empty($rendered_items) ) {
  return;
}
?>

<div
  <?php echo get_block_wrapper_attributes(); ?>
  data-wp-interactive="lsc-faq-accordion"
  <?php echo wp_interactivity_data_wp_context([
    'allowMultiple'    => $allow_multiple,
    'selectedCategory' => '',
    'openItems'        => [],
  ]); ?>
>

  <?php if ( ! empty($categories) ) : ?>
    <div class="lsc-faq-filter">
      <label for="lsc-faq-category-filter" class="screen-reader-text">
        <?php echo esc_html__('Filter by category', 'lsc-blocks'); ?>
      </label>
      <select id="lsc-faq-category-filter" data-wp-on--change="actions.setCategory">
        <option value=""><?php echo esc_html__('All categories', 'lsc-blocks'); ?></option>
        <?php foreach ( $categories as $cat ) : ?>
          <option value="<?php echo esc_attr($cat['slug']); ?>">
            <?php echo esc_html($cat['label']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php endif; ?>

  <?php foreach ( $rendered_items as $item ) : ?>
    <div
      class="lsc-faq-item"
      <?php echo wp_interactivity_data_wp_context([
        'itemId'   => $item['id'],
        'category' => $item['category'],
      ]); ?>
      data-wp-bind--hidden="!state.isItemVisible"
    >
      <h3>
        <button
          type="button"
          class="lsc-faq-question"
          data-wp-on--click="actions.toggleItem"
          data-wp-bind--aria-expanded="state.isItemOpen"
        >
          <?php echo $item['question']; ?>
        </button>
      </h3>
      <div class="lsc-faq-answer" data-wp-bind--hidden="!state.isItemOpen">
        <?php echo $item['answer']; ?>
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