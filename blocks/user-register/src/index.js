import { __ }from '@wordpress/i18n'
import { registerBlockType } from '@wordpress/blocks';
import block from '../block.json'

/**
 * Internal dependencies
 */
import Edit from './edit';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(block.name, {
	/**
	 * @see ./edit.js
	 */
	edit: Edit,

});