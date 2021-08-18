var mesh = mesh || {};

mesh.frontend = function ( $ ) {

	var $body     = $('body'),
		$window   = $(window),
		$equalize = $('.mesh-row[data-equalizer]'),
		do_lp_equal = false,

		self;

	$.fn.removeInlineStyle = function( style ) {
		var search = new RegExp( style + '[^;]+;?', 'g' ),
			styles = $(this).attr('style');

        if ( styles !== undefined ) {
	        $(this).attr('style', styles.replace( search, '' ) );
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
			} else {

				var foundation_version = Foundation.version.slice(0,1);

				if ( '6' == foundation_version && 'function' != typeof( Foundation.Equalizer ) ) {
					do_lp_equal = true;
				}

				if ( '5' == foundation_version && 'function' != typeof( Foundation.libs.equalizer.equalize ) ) {
					do_lp_equal = true;
				}
			}

			if ( do_lp_equal ) {
				$window
					.load( self.mesh_equalize_init )
					.resize( self.mesh_equalize_init );
			}
		},

        /**
		 * Fire our initialization
         */
		mesh_equalize_init : function() {
			$equalize.each( self.mesh_equalize );
		},

        /**
		 * Equalize Sections
         */
		mesh_equalize : function() {
			var $this     = $(this),
				$childs   = $this.find('[data-equalizer-watch]'),
				eq_height = 0;

			$childs.removeInlineStyle( 'height' )
				.each( function() {
					var this_height = $(this).height();

					eq_height = this_height > eq_height ? this_height : eq_height;
				}).height(eq_height);

		}
	};
} ( jQuery );

jQuery(function( $ ) {
    mesh.frontend.init();
});
//# sourceMappingURL=mesh.js.map
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiIiwic291cmNlcyI6WyJtZXNoLmpzIl0sInNvdXJjZXNDb250ZW50IjpbInZhciBtZXNoID0gbWVzaCB8fCB7fTtcblxubWVzaC5mcm9udGVuZCA9IGZ1bmN0aW9uICggJCApIHtcblxuXHR2YXIgJGJvZHkgICAgID0gJCgnYm9keScpLFxuXHRcdCR3aW5kb3cgICA9ICQod2luZG93KSxcblx0XHQkZXF1YWxpemUgPSAkKCcubWVzaC1yb3dbZGF0YS1lcXVhbGl6ZXJdJyksXG5cdFx0ZG9fbHBfZXF1YWwgPSBmYWxzZSxcblxuXHRcdHNlbGY7XG5cblx0JC5mbi5yZW1vdmVJbmxpbmVTdHlsZSA9IGZ1bmN0aW9uKCBzdHlsZSApIHtcblx0XHR2YXIgc2VhcmNoID0gbmV3IFJlZ0V4cCggc3R5bGUgKyAnW147XSs7PycsICdnJyApLFxuXHRcdFx0c3R5bGVzID0gJCh0aGlzKS5hdHRyKCdzdHlsZScpO1xuXG4gICAgICAgIGlmICggc3R5bGVzICE9PSB1bmRlZmluZWQgKSB7XG5cdCAgICAgICAgJCh0aGlzKS5hdHRyKCdzdHlsZScsIHN0eWxlcy5yZXBsYWNlKCBzZWFyY2gsICcnICkgKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybiB0aGlzO1xuXHR9O1xuXG5cdHJldHVybiB7XG5cblx0XHQvKipcblx0XHQgKiBJbml0aWFsaXplIG91ciBzY3JpcHRcblx0XHQgKi9cblx0XHRpbml0IDogZnVuY3Rpb24oKSB7XG5cdFx0XHRzZWxmID0gbWVzaC5mcm9udGVuZDtcblxuXHRcdFx0Ly8gRm91bmRhdGlvbiBkb2VzIG5vdCBleGlzdFxuXHRcdFx0aWYgKCAnb2JqZWN0JyAhPSB0eXBlb2YoIEZvdW5kYXRpb24gKSApIHtcblx0XHRcdFx0ZG9fbHBfZXF1YWwgPSB0cnVlO1xuXG5cdFx0XHQvLyBGb3VuZGF0aW9uIGV4aXN0c1xuXHRcdFx0fSBlbHNlIHtcblxuXHRcdFx0XHR2YXIgZm91bmRhdGlvbl92ZXJzaW9uID0gRm91bmRhdGlvbi52ZXJzaW9uLnNsaWNlKDAsMSk7XG5cblx0XHRcdFx0aWYgKCAnNicgPT0gZm91bmRhdGlvbl92ZXJzaW9uICYmICdmdW5jdGlvbicgIT0gdHlwZW9mKCBGb3VuZGF0aW9uLkVxdWFsaXplciApICkge1xuXHRcdFx0XHRcdGRvX2xwX2VxdWFsID0gdHJ1ZTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGlmICggJzUnID09IGZvdW5kYXRpb25fdmVyc2lvbiAmJiAnZnVuY3Rpb24nICE9IHR5cGVvZiggRm91bmRhdGlvbi5saWJzLmVxdWFsaXplci5lcXVhbGl6ZSApICkge1xuXHRcdFx0XHRcdGRvX2xwX2VxdWFsID0gdHJ1ZTtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXG5cdFx0XHRpZiAoIGRvX2xwX2VxdWFsICkge1xuXHRcdFx0XHQkd2luZG93XG5cdFx0XHRcdFx0LmxvYWQoIHNlbGYubWVzaF9lcXVhbGl6ZV9pbml0IClcblx0XHRcdFx0XHQucmVzaXplKCBzZWxmLm1lc2hfZXF1YWxpemVfaW5pdCApO1xuXHRcdFx0fVxuXHRcdH0sXG5cbiAgICAgICAgLyoqXG5cdFx0ICogRmlyZSBvdXIgaW5pdGlhbGl6YXRpb25cbiAgICAgICAgICovXG5cdFx0bWVzaF9lcXVhbGl6ZV9pbml0IDogZnVuY3Rpb24oKSB7XG5cdFx0XHQkZXF1YWxpemUuZWFjaCggc2VsZi5tZXNoX2VxdWFsaXplICk7XG5cdFx0fSxcblxuICAgICAgICAvKipcblx0XHQgKiBFcXVhbGl6ZSBTZWN0aW9uc1xuICAgICAgICAgKi9cblx0XHRtZXNoX2VxdWFsaXplIDogZnVuY3Rpb24oKSB7XG5cdFx0XHR2YXIgJHRoaXMgICAgID0gJCh0aGlzKSxcblx0XHRcdFx0JGNoaWxkcyAgID0gJHRoaXMuZmluZCgnW2RhdGEtZXF1YWxpemVyLXdhdGNoXScpLFxuXHRcdFx0XHRlcV9oZWlnaHQgPSAwO1xuXG5cdFx0XHQkY2hpbGRzLnJlbW92ZUlubGluZVN0eWxlKCAnaGVpZ2h0JyApXG5cdFx0XHRcdC5lYWNoKCBmdW5jdGlvbigpIHtcblx0XHRcdFx0XHR2YXIgdGhpc19oZWlnaHQgPSAkKHRoaXMpLmhlaWdodCgpO1xuXG5cdFx0XHRcdFx0ZXFfaGVpZ2h0ID0gdGhpc19oZWlnaHQgPiBlcV9oZWlnaHQgPyB0aGlzX2hlaWdodCA6IGVxX2hlaWdodDtcblx0XHRcdFx0fSkuaGVpZ2h0KGVxX2hlaWdodCk7XG5cblx0XHR9XG5cdH07XG59ICggalF1ZXJ5ICk7XG5cbmpRdWVyeShmdW5jdGlvbiggJCApIHtcbiAgICBtZXNoLmZyb250ZW5kLmluaXQoKTtcbn0pO1xuLy8jIHNvdXJjZU1hcHBpbmdVUkw9bWVzaC5qcy5tYXAiXSwiZmlsZSI6Im1lc2guanMifQ==
