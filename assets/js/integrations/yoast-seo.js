var mesh = mesh || {};
    mesh.integrations = mesh.integrations || {};

mesh.integrations.yoast = function ( $ ) {

    var $body		        = $('body'),
        $document           = $('document'),
        $reorder_button     = $('.mesh-section-reorder'),
        $add_button         = $('.mesh-section-add'),
        $collapse_button    = $('.mesh-section-collapse'),
        $expand_button      = $('.mesh-section-expand'),
        $meta_box_container = $('#mesh-container'),
        $section_container  = $('#mesh-sections-container'),
        $description        = $('#mesh-description'),
        $equalize           = $('.mesh_section [data-equalizer]'),
        $sections,
        media_frames        = [],

        // Container References for Admin(self) / Block
        self,
        blocks,
        pointers,
        templates,
        section_count;

    return {

        /**
         * Initialize our script
         */
        init : function() {

            self = mesh.integrations.yoast;

            YoastSEO.app.registerPlugin( 'MeshAnalysis', {status: 'ready'});
            YoastSEO.app.registerModification(
                'content',
                self.addMeshSections,
                'MeshAnalysis'
            );

            $('#post-body').find(
                'textarea.mesh-wp-editor-area'
            ).on('keyup paste cut click', function () {
                YoastSEO.app.pluginReloaded( 'MeshAnalysis' );
            });
        },

        addMeshSections : function( data ) {
            tinyMCE.triggerSave();

            var mesh_content = '';

            $( '#post-body' ).find( 'textarea.mesh-wp-editor-area' ).each( function () {

                var $this = $(this);

                if ( $this.val() ) {

                    mesh_content += ' ';

                    if ( $this.attr( 'yoast-analysis-before' ) ) {
                        mesh_content += $this.attr( 'yoast-analysis-before' );
                    } else {
                        mesh_content += $this.val();
                    }

                    if ( $this.attr( 'yoast-analysis-after' ) ) {
                        mesh_content += $this.attr( 'yoast-analysis-after' );
                    }
                }
            });
            return data + mesh_content;
        }
    }
} ( jQuery );

jQuery(function( $ ) {
    mesh.integrations.yoast.init();
});