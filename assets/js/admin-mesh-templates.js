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
        blocks;

    return {

        /**
         * Initialize our Template Administration
         */
        init : function() {

            self   = mesh.templates;
            blocks = mesh.blocks;

            $body
                .on('click', '.mesh-select-template', self.select_template )
                .on('click', '.mesh-template-layout', self.select_layout )
                .on('click', '.mesh-template-start',  self.display_template_types )
                .on('click', '.mesh-template-type',   self.select_template_type );
        },

        display_template_types : function( event ) {
            event.preventDefault();
            event.stopPropagation();

            $('#mesh-template-usage').show();
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

                    $section_container = $('#mesh-sections-container')
                    $section_container.append($response);
                   // $spinner.removeClass('is-active');

                    if ($empty_msg.length) {
                        $empty_msg.fadeOut('fast');
                        $controls.fadeIn('fast');
                    }

                    var $postboxes = $('.mesh-section', $('#mesh-container'));

                    if ($postboxes.length > 1) {
                        $reorder_button.removeClass('disabled');
                    }

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
