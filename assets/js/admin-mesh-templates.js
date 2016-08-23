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
                .on('click', '.mesh-template-layout', self.select_layout );
        },

        /**
         * Select the template to use as a base.
         *
         * @since 1.1
         * @param event
         */
        select_layout : function(event) {

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
