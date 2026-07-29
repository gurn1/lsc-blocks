import { registerBlockType, registerBlockStyle } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import metadata from './block.json';
import { InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

registerBlockType( metadata.name, {
	edit: Edit,
	save() {
		return <InnerBlocks.Content />;
	}
} );

registerBlockStyle(metadata.name, [
  { name: 'bordered', label: __('Bordered', 'lsc-blocks'), isDefault: true },
  { name: 'minimal', label: __('Minimal', 'lsc-blocks') },
  { name: 'card', label: __('Card', 'lsc-blocks') },
]);
