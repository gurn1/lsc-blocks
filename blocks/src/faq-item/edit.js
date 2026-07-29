import {
  RichText,
  useBlockProps,
  InspectorControls,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes, context }) {
  const { question, answer, category } = attributes;
  const categories = context['lsc-blocks/faqCategories'] || [];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Category', 'lsc-blocks')}>
          <SelectControl
            label={__('FAQ Category', 'lsc-blocks')}
            value={category}
            options={[
              { value: '', label: __('— Select —', 'lsc-blocks') },
              ...categories.map((cat) => ({ value: cat.slug, label: cat.label })),
            ]}
            onChange={(value) => setAttributes({ category: value })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps({ className: 'lsc-faq-item' })}>
        <RichText
          tagName="h3"
          className="lsc-faq-question"
          value={question}
          onChange={(value) => setAttributes({ question: value })}
          placeholder={__('Add question…', 'lsc-blocks')}
          allowedFormats={['core/bold', 'core/italic']}
        />

        <RichText
          tagName="div"
          className="lsc-faq-answer"
          value={answer}
          onChange={(value) => setAttributes({ answer: value })}
          placeholder={__('Add answer…', 'lsc-blocks')}
          allowedFormats={['core/bold', 'core/italic', 'core/link']}
        />
      </div>
    </>
  );
}