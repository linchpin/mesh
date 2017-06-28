var mesh = mesh || {};
    mesh.integrations = mesh.integrations || {};

mesh.integrations.yoast = function ( $ ) {

    var $postBody,

        // Container References for Admin(self) / Block
        self;

    return {

        /**
         * Initialize our script
         */
        init : function() {

            self = mesh.integrations.yoast;

            $postBody = $('#post-body');

            YoastSEO.app.registerPlugin( 'MeshAnalysis', {status: 'ready'});
            YoastSEO.app.registerModification(
                'content',
                self.addMeshSections,
                'MeshAnalysis'
            );

            $postBody.find(
                'textarea.mesh-wp-editor-area'
            ).on('keyup paste cut click', function () {
                YoastSEO.app.pluginReloaded( 'MeshAnalysis' );
            });
        },

        /**
         * This portion is inspired by
         * https://github.com/alexis-magina/yoast-cmb2-field-analysis
         *
         * @param data
         * @returns {string}
         */
        addMeshSections : function( data ) {
            tinyMCE.triggerSave();

            var mesh_content = '';

            $postBody.find( 'textarea.mesh-wp-editor-area' ).each( function () {

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
            }).on('keyup paste cut click', function () {
                YoastSEO.app.pluginReloaded( 'MeshAnalysis' );
            });
            return data + mesh_content;
        }
    }
} ( jQuery );

jQuery(function( $ ) {
    mesh.integrations.yoast.init();
});