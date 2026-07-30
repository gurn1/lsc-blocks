import './editor.scss';

import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';

import {
	PanelBody,
	ToggleControl,
	RangeControl,
	SelectControl,
	TextControl
} from '@wordpress/components';

import { __ } from '@wordpress/i18n';

import CategoryManager from './category-manager';

const ALLOWED_BLOCKS = ['lsc-blocks/faq-item'];
const TEMPLATE = [['lsc-blocks/faq-item']];

export default function Edit({ attributes, setAttributes }) {
	const {
    allowMultipleOpen,
    categories,
    textAlign,
    itemSpacing,
    headingLevel,
		showCategoryHeading,
  	allCategoriesLabel,
  } = attributes;

	
	const blockProps = useBlockProps({
		style: {
      '--lsc-faq-text-align': textAlign,
      '--lsc-faq-item-spacing': `${itemSpacing}px`,
    },
	});

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
						onChange={(value) =>
							setAttributes({ allowMultipleOpen: value })
						}
					/>

					 <ToggleControl
							label={__('Show a heading for the selected category', 'lsc-blocks')}
							checked={showCategoryHeading}
							onChange={(value) => setAttributes({ showCategoryHeading: value })}
						/>

						{showCategoryHeading && (
							<TextControl
								label={__('Heading text when "All categories" is selected', 'lsc-blocks')}
								help={__('Leave blank to show no heading at all when nothing is filtered.', 'lsc-blocks')}
								value={allCategoriesLabel}
								onChange={(value) => setAttributes({ allCategoriesLabel: value })}
							/>
						)}
				</PanelBody>

				<PanelBody
					title={__('Categories', 'lsc-blocks')}
					initialOpen={false}
				>
					<CategoryManager
						categories={categories}
						setAttributes={setAttributes}
					/>
				</PanelBody>
			</InspectorControls>

			<InspectorControls group="styles">
				<PanelBody
					title={__('Layout', 'lsc-blocks')}
					initialOpen={true}
				>
					<SelectControl
						label={__('Text alignment', 'lsc-blocks')}
						value={textAlign}
						options={[
							{
								label: __('Left', 'lsc-blocks'),
								value: 'left',
							},
							{
								label: __('Centre', 'lsc-blocks'),
								value: 'center',
							},
							{
								label: __('Right', 'lsc-blocks'),
								value: 'right',
							},
						]}
						onChange={(value) =>
							setAttributes({ textAlign: value })
						}
					/>

					<RangeControl
						label={__('Item spacing', 'lsc-blocks')}
						value={itemSpacing}
						min={0}
						max={64}
						step={4}
						onChange={(value) =>
							setAttributes({ itemSpacing: value })
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...innerBlocksProps} />
		</>
	);
}