import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const { question, thankYouMessage } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Text', 'lsc-blocks')}>
          <TextControl
            label={__('Question', 'lsc-blocks')}
            value={question}
            onChange={(value) => setAttributes({ question: value })}
          />
          <TextControl
            label={__('Thank you message', 'lsc-blocks')}
            value={thankYouMessage}
            onChange={(value) => setAttributes({ thankYouMessage: value })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps({ className: 'lsc-feedback-widget-preview' })}>
        <p>{question}</p>
        <div className="lsc-feedback-buttons">
          <button type="button" disabled>{__('Yes', 'lsc-blocks')} <span>0</span></button>
          <button type="button" disabled>{__('No', 'lsc-blocks')} <span>0</span></button>
        </div>
      </div>
    </>
  );
}