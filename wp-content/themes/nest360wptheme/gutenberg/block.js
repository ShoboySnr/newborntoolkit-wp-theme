/* block.js */
var el = wp.element.createElement;

wp.blocks.registerBlockType('studio14-gutenberg/collapsible-tabs', {

    title: 'Collapsible Tabs',

    description: 'Create a expandibles and collapsible tabs.',

    icon: 'editor-insertmore',

    category: 'common',

    attributes: {
        title: { type: 'string', selector: '.s14-collapsible-tabs-title', },
        content: { type: 'array', source: 'children', selector: 'section' }
    },
    example: {
        attributes: {
            title: 'An Example Collapsible Title',
            content: 'Enter the content here...',
        },
    },


    edit: function(props) {
        function updateTitle( event ) {
            props.setAttributes( { title: event.target.value } );
        }

        function updateContent( newdata ) {
            props.setAttributes( { content: newdata } );
        }

        return el( 'div',
            {
                className: 's14-collapsible-box collapsible-' + props.attributes.label
            },
            el (
                'h4',
                {
                    class: 'collapsible-label-title',
                },
                'Collapsible Tab',
            ),
            el (
                'input',
                {
                    type: 'checkbox',
                    class: 'collapsible-checkbox',
                }
            ),
            el(
                'input',
                {
                    type: 'text',
                    placeholder: 'Title',
                    value: props.attributes.title,
                    class: 'collapsible-title',
                    onChange: updateTitle,
                    style: { width: '100%' }
                }
            ),
            el(
                wp.blockEditor.RichText,
                {
                    tagName: 'section',
                    onChange: updateContent,
                    value: props.attributes.content,
                    placeholder: 'Details',
                    class: 'rich-text collapsible-content'
                }
            )
        ); // End return

    },

    save: function(props) {
        const random_numbers = Date.now() + Math.floor(Math.random() * 99999999)
        
        return el( 'div',
            {
                className: 's14-collapsible-view-box collapsible-' + props.attributes.label
            },
            el(
                'input',
                {
                    type: 'checkbox',
                    class: 'collapsible-checkbox',
                    id: 'collapsible-checkbox-' + random_numbers
                }
            ),
            el(
                'label',
                {
                    for: 'collapsible-checkbox-' + + random_numbers,
                    tabindex: '0'
                },
                props.attributes.title
            ),
            el( wp.blockEditor.RichText.Content, {
                tagName: 'section',
                class: 'collapsible-content',
                value: props.attributes.content
            })

        ); // End return

    } // End save()
});