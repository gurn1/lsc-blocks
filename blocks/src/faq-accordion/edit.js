import {
  InspectorControls,
  useBlockProps,
  useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import CategoryManager from './category-manager';

const ALLOWED_BLOCKS = ['lsc-blocks/faq-item'];
const TEMPLATE = [['lsc-blocks/faq-item']];

export default function Edit({ attributes, setAttributes }) {
  const { allowMultipleOpen, categories } = attributes;
  const blockProps = useBlockProps();
  const innerBlocksProps = useInnerBlocksProps(blockProps, {
    allowedBlocks: ALLOWED_BLOCKS,
    template: TEMPLATE,
    templateLock: false,
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Behavior', 'lsc-blocks')}>
          <ToggleControl
            label={__('Allow multiple items open at once', 'lsc-blocks')}
            checked={allowMultipleOpen}
            onChange={(value) => setAttributes({ allowMultipleOpen: value })}
          />
        </PanelBody>

        <PanelBody title={__('Categories', 'lsc-blocks')} initialOpen={false}>
          <CategoryManager
            categories={categories}
            setAttributes={setAttributes}
          />
        </PanelBody>
      </InspectorControls>

      <div {...innerBlocksProps} />
    </>
  );
}