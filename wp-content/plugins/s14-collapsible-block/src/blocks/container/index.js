/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InnerBlocks, RichText, useBlockProps } = wp.blockEditor;

registerBlockType( 'collapsible-block/container', {
	title: __( 'Collapsible Tabs' ),

	description: __( 'Provide Collapsible Block and Custom Container.' ),

	keywords: [
		__( 'container' ),
		__( 'wrapper' ),
		__( 'section' ),
	],

	supports: {
		align: [ 'wide', 'full' ],
		anchor: true,
		html: false,
	},

	category: 'common',

	icon: 'editor-insertmore',

	attributes: {
		id: {
			type: 'number',
			default: Date.now() + Math.floor(Math.random() * 99999999),
		},
		type: {
			type: 'string',
			default: 'h2',
		},
		title: {
			type: 'string',
			selector: 'h2'
		},
		content: {
			type: 'string',
		},
	},

	edit: ( props ) => {
		const { attributes, setAttributes, className, isSelected } = props;
		const blockProps = useBlockProps();

		function updateType( event ) {
			setAttributes( { type: event.target.value } );
		}

		function updateTitle(newdata) {
			setAttributes( { title: newdata } );
			setAttributes( { id: Date.now() + Math.floor(Math.random() * 99999999)} );
		}

		return (
			<div className={ className }>
				<input type={__('hidden')} value={attributes.id} />
				<select value={attributes.type} onChange={updateType} className={__('select-options')} required>
					<option value={''}>{ __('Select one')}</option>
					<option value={'h1'}>{ __('h1')}</option>
					<option value={'h2'}>{ __('h2')}</option>
					<option value={'h3'}>{ __('h3')}</option>
					<option value={'h4'}>{ __('h4')}</option>
					<option value={'h5'}>{ __('h5')}</option>
					<option value={'h6'}>{ __('h6')}</option>
				</select>
				<RichText
					{ ...blockProps }
					tagName="h2"
					value={attributes.title }
					onChange={updateTitle}
					placeholder={ __( 'Enter title' ) }
					keepPlaceholderOnFocus={true}
				/>
				<hr />
				<div>
					<InnerBlocks renderAppender={ InnerBlocks.ButtonBlockAppender } />
				</div>
			</div>
		);
	},

	save: ( props ) => {
		const { className } = props;
		const blockProps = useBlockProps.save();

		return (
			<section itemID={__('s14-collapsible-view-box')} className={ className }>
				<input type={__('checkbox')} className={__('collapsible-checkbox')} id={__('collapsible-checkbox-' + props.attributes.id)} />
				<label htmlFor={__('collapsible-checkbox-' + props.attributes.id)} className={__('s14-collapsible-label')}>
					<RichText.Content className={__('s14-collapsible-label')} { ...blockProps } tagName={props.attributes.type} value={ props.attributes.title } />
				</label>
				<div className={__('collapsible-content')}>
					<InnerBlocks.Content { ...blockProps} />
				</div>
			</section>
		);
	},
} );
