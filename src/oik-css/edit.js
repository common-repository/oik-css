import {__} from "@wordpress/i18n";

import clsx from 'clsx';
import ServerSideRender from '@wordpress/server-side-render';
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import {
	Toolbar,
	PanelBody,
	PanelRow,
	FormToggle,
	TextControl,
	TextareaControl,
	SelectControl } from '@wordpress/components';
import { Fragment} from '@wordpress/element';
import { map, partial } from 'lodash';

import { registerBlockType, createBlock } from '@wordpress/blocks';
import {AlignmentControl, BlockControls, InspectorControls, useBlockProps, PlainText} from '@wordpress/block-editor';


/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit  ( props ) {
	const { attributes, setAttributes, instanceId, focus, isSelected } = props;
	const { textAlign, label } = props.attributes;
	const blockProps = useBlockProps( {
		className: clsx( {
			[ `has-text-align-${ textAlign }` ]: textAlign,
		} ),
	} );

	const onChangeText = ( value ) => {
		setAttributes( { text: value } );
	};

	const onChangeCSS = ( value ) => {
		setAttributes( { css: value } );
	};

	const onChangeSrc = ( value ) => {
		setAttributes( { src: value } );
	}

	return (
		<Fragment>

			<InspectorControls>
				<PanelBody>
					<TextareaControl label={ __( "Text", 'oik-css' ) } value={attributes.text} onChange={onChangeText} />
				</PanelBody>
				<PanelBody>
					<PanelRow>
						<TextControl
							label={ __( 'Source file: ID, URL or path', 'oik-css' ) }
							value={  attributes.src }
							onChange={ onChangeSrc }

						/>

					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps}>

					<PlainText
						value={attributes.css}
						placeholder={__('Write CSS or specify a source file.', 'oik-css')}
						onChange={onChangeCSS}
					/>


				{!isSelected &&


					<ServerSideRender
						block="oik-css/css" attributes={attributes}
					/>

				}

			</div>

		</Fragment>
	);
}
