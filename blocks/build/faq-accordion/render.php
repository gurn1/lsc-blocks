<?php
if( ! defined('ABSPATH')) {
  exit;
}

$allow_multiple = ! empty($attributes['allowMultipleOpen']);
$categories     = $attributes['categories'] ?? [];
$items          = $block->parsed_block['innerBlocks'] ?? [];
$text_align     = $attributes['textAlign'] ?? 'left';
$item_spacing   = (int) ($attributes['itemSpacing'] ?? 16);
$heading_level  = max(2, min(6, (int) ($attributes['headingLevel'] ?? 3)));
$heading_tag    = 'h' . $heading_level;
$show_category_heading = ! empty($attributes['showCategoryHeading']);
$all_categories_label  = sanitize_text_field($attributes['allCategoriesLabel'] ?? '');
$category_heading_tag  = 'h' . max(2, $heading_level - 1);

if ( empty($items) ) {
  return;
}

$align_to_justify = [
  'left'   => 'flex-start',
  'center' => 'center',
  'right'  => 'flex-end',
];
$justify = $align_to_justify[$text_align] ?? 'flex-start';

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
    'acceptedAnswer' => [ '@type' => 'Answer', 'text' => wp_strip_all_tags($answer) ],
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

$wrapper_attributes = get_block_wrapper_attributes([
  'style' => sprintf(
    '--lsc-faq-text-align: %s; --lsc-faq-item-spacing: %dpx;',
    esc_attr($text_align),
    $item_spacing
  ),
]);
?>

<div
  <?php echo wp_kses_post( $wrapper_attributes ); ?>
  data-wp-interactive="lsc-faq-accordion"
  <?php echo wp_kses_post(wp_interactivity_data_wp_context([
    'allowMultiple'    => $allow_multiple,
    'selectedCategory' => '',
    'openItems'        => [],
    'categories'         => $categories,
    'allCategoriesLabel' => $all_categories_label
  ])); ?>
>

  <?php if ( ! empty($categories) ) : ?>
    <div class="lsc-faq-filter">
      <label for="lsc-faq-category-filter" class="screen-reader-text">
        <?php echo esc_html__('Filter by category', 'lsc-blocks'); ?>
      </label>
      <select id="lsc-faq-category-filter" data-wp-on--change="actions.setCategory">
        <option value=""><?php echo esc_html__('All categories', 'lsc-blocks'); ?></option>
        <?php foreach ( $categories as $cat ) : ?>
          <option value="<?php echo esc_attr($cat['slug']); ?>"><?php echo esc_html($cat['label']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php endif; ?>

  <?php if ( $show_category_heading ) : ?>
    <<?php echo esc_html($category_heading_tag); ?>
      class="lsc-faq-category-heading"
      data-wp-text="state.categoryHeadingText"
      data-wp-bind--hidden="!state.categoryHeadingText"
    ><?php echo esc_html($all_categories_label); ?></<?php echo esc_html($category_heading_tag); ?>>
  <?php endif; ?>

  <?php foreach ( $rendered_items as $item ) : ?>
    <div
      class="lsc-faq-item"
      <?php echo wp_kses_post( wp_interactivity_data_wp_context([ 'itemId' => $item['id'], 'category' => $item['category'] ]) ); ?>
      data-wp-bind--hidden="!state.isItemVisible"
    >
      <<?php echo esc_html($heading_tag); ?>>
        <button
          type="button"
          class="lsc-faq-question"
          data-wp-on--click="actions.toggleItem"
          data-wp-bind--aria-expanded="state.isItemOpen"
        >
          <span class="lsc-faq-question-text"><?php echo esc_html($item['question']); ?></span>
        </button>
      </<?php echo esc_html($heading_tag); ?>>
      <div class="lsc-faq-answer" data-wp-class--is-open="state.isItemOpen" data-wp-bind--inert="!state.isItemOpen">
        <div class="lsc-faq-answer-content"><?php echo wp_kses_post($item['answer']); ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php if ( ! empty($schema_entities) ) : ?>
  <script type="application/ld+json">
    <?php echo wp_json_encode([ '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $schema_entities ]); ?>
  </script>
<?php endif; ?>