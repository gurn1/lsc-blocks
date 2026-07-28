import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import metadata from './block.json';
import { InnerBlocks } from '@wordpress/block-editor';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( metadata.name, {
	edit: Edit,
	save() {
		return <InnerBlocks.Content />;
	}
} );
