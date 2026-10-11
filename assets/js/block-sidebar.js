( function ( wp ) {
    'use strict';

    var __ = wp.i18n.__;
    var registerPlugin   = wp.plugins.registerPlugin;
    var PluginSidebarMoreMenuItem = wp.editPost.PluginSidebarMoreMenuItem;
    var PluginSidebar    = wp.editPost.PluginSidebar;
    var PanelBody        = wp.components.PanelBody;
    var CheckboxControl  = wp.components.CheckboxControl;
    var Notice           = wp.components.Notice;
    var useSelect        = wp.data.useSelect;
    var useDispatch      = wp.data.useDispatch;
    var createElement    = wp.element.createElement;

    var META_KEY = '_nim_noindex';

    registerPlugin( 'noindex-manager', {
        render: function () {
            var meta      = useSelect( function ( select ) {
                return select( 'core/editor' ).getEditedPostAttribute( 'meta' );
            } );
            var editPost  = useDispatch( 'core/editor' ).editPost;
            var isNoindex = meta && meta[ META_KEY ] === '1';

            function handleChange( checked ) {
                var newMeta = {};
                newMeta[ META_KEY ] = checked ? '1' : '';
                editPost( { meta: newMeta } );
            }

            return createElement(
                wp.element.Fragment,
                null,
                createElement(
                    PluginSidebarMoreMenuItem,
                    { target: 'noindex-manager-sidebar' },
                    __( 'Search Engine Visibility', 'noindex-manager' )
                ),
                createElement(
                    PluginSidebar,
                    {
                        name:  'noindex-manager-sidebar',
                        title: __( 'Search Engine Visibility', 'noindex-manager' ),
                        icon:  'hidden',
                    },
                    createElement(
                        PanelBody,
                        { initialOpen: true },
                        createElement( CheckboxControl, {
                            label:    __( 'Hide from search engines (noindex)', 'noindex-manager' ),
                            help:     __( 'Adds a noindex meta tag to this page so search engines exclude it from results.', 'noindex-manager' ),
                            checked:  isNoindex,
                            onChange: handleChange,
                        } ),
                        isNoindex && createElement(
                            Notice,
                            {
                                status:      'warning',
                                isDismissible: false,
                                className:   'nim-block-notice',
                            },
                            __( 'This page is set to noindex and will not appear in search results.', 'noindex-manager' )
                        )
                    )
                )
            );
        },
    } );
} )( window.wp );
