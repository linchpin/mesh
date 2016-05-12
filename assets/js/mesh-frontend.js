/**
 * Build out all of our frontend business
 * @since 1.0.0
 */

var mesh = mesh || {};

mesh.frontend = function ( $ ) {

	var $body     = $('body'),
		$window   = $(window),
		$equalize = $('.mesh_section [data-equalizer]'),
		do_lp_equal = false,

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
			self = mesh.frontend;

			// Foundation does not exist
			if ( 'object' != typeof( Foundation ) ) {
				do_lp_equal = true;

			// Foundation exists
			} else if ( 'object' == typeof( Foundation ) ) {

				// Foundation's equalize is not turned on
				if ( 'function' != typeof( Foundation.Equalizer ) && undefined == typeof( Foundation.libs ) ) {
					do_lp_equal = true;
				} else {
					// Not Foundation 6 and has Foundation 5 equalizer object
					if ( 'function' != typeof ( Foundation.Equalizer ) && 'object' == typeof( Foundation.libs.equalizer ) ) {

						// Foundation 5 equalize function is not available
						if ( 'function' != typeof( Foundation.libs.equalizer.equalize ) ) {
							do_lp_equal = true;
						}
					}
				}
			}

			if ( do_lp_equal ) {
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
    mesh.frontend.init();
});