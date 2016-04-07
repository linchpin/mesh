var multiple_content_sections = multiple_content_sections || {};

multiple_content_sections.pointers = function ( $ ) {

    var current_index = 0;

    return {

        /**
         * Show our current pointer based on index.
         */
        show_pointer: function() {

            // Make sure we have pointers available.
            if( typeof( mcs_data.wp_pointers ) === 'undefined') {
                return;
            }

            var pointer = mcs_data.wp_pointers.pointers[current_index],
                options = $.extend( pointer.options, {
                    close: function() {
                        $.post( ajaxurl, {
                            pointer: pointer.pointer_id,
                            action: 'dismiss-wp-pointer'
                        });

                        current_index++;

						if ( current_index < mcs_data.wp_pointers.pointers.length ) {
	                        multiple_content_sections.pointers.show_pointer();
						}
                    }
                });

            $(pointer.target).pointer( options ).pointer('open');
        }
    };

} ( jQuery );