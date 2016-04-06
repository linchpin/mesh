var multiple_content_sections = multiple_content_sections || {};

multiple_content_sections.pointers = function ( $ ) {

	return {

		/**
		 * Initialize our script
		 *
		 * @param int index
		 */
		show_pointer: function ( index ) {
			var pointer = mcs_data.wp_pointers.pointers[index],
				options = $.extend( pointer.options, {
					close: function() {
						$.post( ajaxurl, {
							pointer: pointer.pointer_id,
							action: 'dismiss-wp-pointer'
						});
					}
				});
			
			$(pointer.target).pointer( options ).pointer('open');
		}
	};

} ( jQuery );