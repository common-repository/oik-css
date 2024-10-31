/**
 * Implements CSS block
 *
 * Uses the logic for the [bw_css] shortcode
 *
 * @copyright (C) Copyright Bobbing Wide 2018-2021
 * @author Herb Miller @bobbingwide
 */
import './style.scss';
import './editor.scss';
import Edit from './edit';
import save from './save';

import { __ } from '@wordpress/i18n';

import { registerBlockType, createBlock } from '@wordpress/blocks';

/**
 * Registers the oik-css/css block.
 */
export default registerBlockType( 'oik-css/css',
	{

		example: {
			attributes: {
				css: 'div.wp-block-oik-css-css { color: red;}',
				text: __( 'This sentence will be very red.', 'oik-css' ),
			},
		},

		transforms: {
			from: [
				{
					type: 'block',
					blocks: ['oik-block/css'],
					transform: function( attributes ) {
						return createBlock( 'oik-css/css', {
							css: attributes.css,
							text: attributes.text
						});
					},
				},
				{
					type: 'block',
					blocks: ['core/paragraph', 'core/code', 'core/preformatted'],
					transform: function( attributes ) {
						return createBlock( 'oik-css/css', {
							css: attributes.content
						});
					},
				},
			],

		},



		/**
		 * @see ./edit.js
		 */
		edit: Edit,
		/**
		 * @see ./save.js
		 */
		save
	}
);

