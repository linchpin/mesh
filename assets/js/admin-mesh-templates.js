/**
 * Controls template Administration and selection
 *
 * @package    Mesh
 * @subpackage Templates
 * @since      1.1
 */

var mesh = mesh || {};

mesh.templates = function ( $ ) {

    var $body = $('body'),
        // Instance of our template controller
        self,
        blocks,
        $welcomePanel = $( '#mesh-template-welcome-panel' );

    return {

        /**
         * Initialize our Template Administration
         */
        init : function() {

            self   = mesh.templates;

            $welcomePanel.find( '.mesh-template-welcome-panel-close' ).on( 'click', function( event ) {
                event.preventDefault();

                $welcomePanel.addClass('hidden');

                self.updateWelcomePanel( 0 );
            });

            if ( 'post' !== mesh_data.screen ) {
                return;
            }

            blocks = mesh.blocks;

            $body
                .on('click', '.mesh-select-template',      self.select_template )
                .on('click', '.mesh-template-layout',      self.select_layout )
                .on('click', '.mesh-template-start',       self.display_template_types )
               //  .on('click', '.mesh-template-type',        self.select_template_type )
                .on('click', '.mesh-template-change-type', self.change_template_type )
                .on('click', '.mesh-template-remove',      self.remove_template );

                // .on('click', '.mesh_template .mesh-section-update, .mesh_template .mesh-section-publish', self.warn_on_save );
        },

        /**
         * Warn the user that they will their changes will
         * be applied to other templates on update/publish
         *
         * @todo 1.2
         *
         * @param event
         */
        warn_on_save : function( event ) {
            event.preventDefault();
            event.stopPropagation();

            var confirmation = confirm( mesh_data.strings.confirm_template_section_update );

            if ( true !== confirmation ) {
                self.applyTemplateChanges();
            }
        },

        /**
         * When we update a template's section(s) update all sections
         * of each posts that use this templates sections.
         *
         * @todo 1.2
         */
        applyTemplateChanges : function() {
            $.post( ajaxurl, {
                action: 'mesh_apply_template_changes',
                mesh_post_id: mesh_data.post_id,
                mesh_template_id: template,
                mesh_template_type: template_type,
                mesh_choose_template_nonce: mesh_data.choose_template_nonce
            }, function( response ) {
                if ( response ) {

                }
            });
        },

        /**
         * Show or Hide our Mesh Welcome Panel
         * Based on the Welcome Panel in WP Core
         *
         * @param visible
         */
        updateWelcomePanel : function( visible ) {
            $.post( ajaxurl, {
                action: 'mesh_template_update_welcome_panel',
                visible: visible,
                meshtemplatepanelnonce: $( '#mesh-templates-welcome-panel-nonce' ).val()
            });
        },

        remove_template : function( event ) {

        },

        /**
         * Change the type of template that is being used (Reference vs Starter)
         *
         * Our response should include a refreshed set of sections
         * with all of our proper controls needed now that this
         * template is no longer being used as a "reference"
         *
         * @since 1.1
         * @param event
         */
        change_template_type : function( event ) {
            event.preventDefault();
            event.stopPropagation();

            $.post( ajaxurl, {
                action: 'mesh_change_template_type',
                mesh_post_id: mesh_data.post_id,
                mesh_template_type: 'starter',
                mesh_choose_template_nonce: mesh_data.choose_template_nonce
            }, function( response ) {

                if ( response ) {
                    var $response = $(response),
                        $tinymce_editors = $response.find('.wp-editor-area'),
                        $empty_msg = $('.empty-sections-message'),
                        $controls = $('.mesh-main-ua-row');

                    var $mesh_container = $('#mesh-container');
                    $mesh_container.html('').append( $response.children() );
                    // $spinner.removeClass('is-active');

                    if ($empty_msg.length) {
                        $empty_msg.fadeOut('fast');
                        $controls.fadeIn('fast');
                    }

                    var $postboxes = $('.mesh-section', $mesh_container );

                    if ($postboxes.length > 1) {
                        $('.mesh-section-reorder').removeClass('disabled');
                    }

                    blocks.setup_resize_slider();
                    blocks.setup_sortable();
                    blocks.rerender_blocks($tinymce_editors);

                    // Repopulate the sections cache so that the new section is included going forward.
                    blocks.$sections = $('.mesh-section', $('#mesh-sections-container') );
                }
            });
        },

        /**
         * Display our available template usage, Reference or Starting Point
         * @param event
         */
        display_template_types : function( event ) {
            event.preventDefault();
            event.stopPropagation();

            // @todo 1.2
            // If we have a mesh template. Always use it as a starting point.
            // if( 'mesh_template' !== mesh_data.post_type ) {
            //    $('#mesh-template-usage').show();
            // } else {
            //    $('.mesh-starter-template').trigger('click');
            // }

            self.select_template_type( event );
        },

        /**
         * Select the type of template we are using
         * This can be either a reference template or
         * a starter template.
         *
         * @since 1.1
         * @param event
         */
        select_template_type : function( event ) {
            event.preventDefault();
            event.stopPropagation();

            var $this         = $(this),
                template      = $('.mesh-template:checked').val(),
                template_type = $this.attr( 'data-template-type' );

            $.post( ajaxurl, {
                action: 'mesh_choose_template',
                mesh_post_id: mesh_data.post_id,
                mesh_template_id: template,
                mesh_template_type: template_type,
                mesh_choose_template_nonce: mesh_data.choose_template_nonce
            }, function( response ) {
                if (response) {
                    var $response = $(response),
                        $tinymce_editors = $response.find('.wp-editor-area'),
                        $empty_msg = $('.empty-sections-message'),
                        $controls = $('.mesh-main-ua-row');

                   var $section_container = $('#mesh-sections-container');
                       $section_container.append($response);

                    if ($empty_msg.length) {
                        $empty_msg.fadeOut('fast');
                        $controls.fadeIn('fast');
                    }

                    var $postboxes = $('.mesh-section', $('#mesh-container'));

                    if ($postboxes.length > 1) {
                        $('.mesh-section-reorder').removeClass('disabled');
                    }

                    blocks.setup_resize_slider();
                    blocks.setup_sortable();
                    blocks.rerender_blocks($tinymce_editors);

                    // Repopulate the sections cache so that the new section is included going forward.
                    blocks.$sections = $('.mesh-section', $section_container);
                }
            });
        },

        /**
         * Select the template to use as a base.
         *
         * @todo security harden possibly, is it beneficial to output available templates for additional validation
         *
         * @since 1.1
         * @param event
         */
        select_layout : function( event ) {

            event.preventDefault();
            event.stopPropagation();

            var $this = $(this),
                $template_layouts = $('.mesh-template-layout');

            $template_layouts.removeClass('active').removeProp('checked');

            $this.addClass('active').find('.mesh-template').prop('checked', 'checked');
        },

        /**
         * Add new section(s) to our content based on a Mesh Template
         *
         * @since 1.1
         *
         * @param event
         * @returns {boolean}
         */
        select_template : function(event) {

            event.preventDefault();
            event.stopPropagation();

            var $this = $(this),
                $spinner = $this.siblings('.spinner');

            if ( $this.hasClass('disabled') ) {
                return false;
            }

            $spinner.addClass('is-active');

            $.post( ajaxurl, {
                action: 'mesh_list_templates',
                mesh_post_id: mesh_data.post_id,
                mesh_choose_template_nonce: mesh_data.choose_template_nonce
            }, function( response ){
                if ( response ) {
                    var $response = $( response );

                    $('#mesh-description').html('').append( $response );
                    $spinner.removeClass('is-active');

                } else {
                    $spinner.removeClass('is-active');
                }
            });
        }
    };

} ( jQuery );
