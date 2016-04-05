if( typeof(multiple_content_sections) == 'undefined' ) {
	multiple_content_sections = {};
}

multiple_content_sections.frontend = function ( $ ) {

	var $body     = $('body'),
		$window   = $(window),
		$equalize = $('.mcs_section [data-equalizer]'),

		self;

	$.fn.removeInlineStyle = function( style ) {
		var search = new RegExp( style + '[^;]+;?', 'g' ),
			styles = this.attr('style');

        if ( styles !== undefined ) {
	        this.attr('style', styles.replace( search, '' ) );
        }

        return this;
	};

	return {

		/**
		 * Initialize our script
		 */
		init : function() {
			self = multiple_content_sections.frontend;

			if ( 'function' != typeof Foundation.libs.equalizer.equalize && 'function' != typeof Foundation.Equalizer ) {
				$window
					.load( self.mesh_equalize_init )
					.resize( self.mesh_equalize_init );
			}
		},

		mesh_equalize_init : function() {
			$equalize.each( self.mesh_equalize );
		},

		mesh_equalize : function() {

			var $this     = $(this),
				$childs   = $('[data-equalizer-watch]', $this),
				eq_height = 0;

			$childs.each(function() {
				$(this).removeInlineStyle( 'height' );
			});

			$childs.each( function() {
				var this_height = $(this).height();

				eq_height = this_height > eq_height ? this_height : eq_height;
			}).height(eq_height);

		}
	};
} ( jQuery );

jQuery(function( $ ) {
    multiple_content_sections.frontend.init();
});
//# sourceMappingURL=mesh.js.map