if( typeof(multiple_content_sections) == 'undefined' ) {
	multiple_content_sections = {};
}

multiple_content_sections.admin = function ( $ ) {

	var $body		        = $('body'),
		$reorder_button     = $('.mcs-section-reorder'),
		$add_button         = $('.mcs-section-add'),
		$expand_button      = $('.mcs-section-expand'),
		$meta_box_container = $('#mcs-container'),
		$section_container  = $('#multiple-content-sections-container'),
		$description        = $('#mcs-description'),
		$empty_message      = $('.empty-sections-message'),
		media_frames        = [],

		// Container References for Admin(self) / Block
		self,
		blocks;

	return {

		/**
		 * Initialize our script
		 */
		init : function() {

			self = multiple_content_sections.admin;
			blocks = multiple_content_sections.blocks;

			$body
				.on('click', '.mcs-section-add', self.add_section )
				.on('click', '.mcs-section-remove', self.remove_section )
				.on('click', '.mcs-section-reorder', self.reorder_sections )
				.on('click', '.mcs-save-order', self.save_section_order )

				.on('change', '.mcs-choose-layout', self.choose_layout )

				.on('click', '.mcs-featured-image-choose', self.choose_background )
				.on('click.OpenMediaManager', '.mcs-featured-image-choose', self.choose_background )

				.on('click', '.mcs-featured-image-trash', self.remove_background )

				.on('click', '.mcs-section-expand', self.expand_all_sections )

				.on('keydown', '.msc-clean-edit-element', self.change_input_title )

				.on('change', 'select.msc-clean-edit-element', self.change_select_title );

			var $sections = $( '.multiple-content-sections-section' );

			if ( $sections.length <= 1 ) {
				$reorder_button.addClass( 'disabled' );
			}

			// Setup our controls for Blocks
			blocks.init();

			self.setup_notifications( $meta_box_container );

		},

		/**
		 * Add notifications to our section
		 *
		 * @param $layout
		 */
		setup_notifications : function( $layout ) {
			// Make notices dismissible
			$layout.find( '.notice.is-dismissible' ).each( function() {
				var $this = $( this ),
					$button = $( '<button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button>' ),
					btnText = commonL10n.dismiss || '';

				// Ensure plain text
				$button.find( '.screen-reader-text' ).text( btnText );

				$this.append( $button );

				$button.on( 'click.wp-dismiss-notice', function( event ) {
					event.preventDefault();

					$.post( ajaxurl, {
						action                : 'mcs_dismiss_notification',
						mcs_notification_type : $this.attr('data-type'),
						_wpnonce     : mcs_data.dismiss_nonce
					}, function( response ) {});

					$this.fadeTo( 100 , 0, function() {
						$(this).slideUp( 100, function() {
							$(this).remove();
						});
					});
				});
			});
		},

		/**
		 * 1 click to expand or collapse sections
		 *
		 * @since 1.3.0
		 *
		 * @param event
		 */
		expand_all_sections : function( event ) {

			event.preventDefault();
			event.stopPropagation();

			var $this = $(this);

			$this.toggleClass('expanded');

			if( ! $this.hasClass('expanded') ) {
				$this.text( mcs_data.strings.expand_all );
			} else {
				$this.text( mcs_data.strings.collapse_all );
			}

			$('#multiple-content-sections-container').find('.hndle').trigger('click');
		},

		/**
		 * Choose what layout is used for the section
		 *
		 * @since 1.1.0
		 *
		 * @param event
		 * @returns {boolean}
		 */
		choose_layout : function( event ) {

			event.preventDefault();
			event.stopPropagation();

			var $this      = $(this),
				$spinner   = $this.siblings('.spinner'),
				$section   = $this.parents('.multiple-content-sections-section'),
				section_id = $section.attr('data-mcs-section-id');

			if ( $this.hasClass('disabled') ) {
				return false;
			}

			$spinner.addClass('is-active');

			$.post( ajaxurl, {
				action                  : 'mcs_choose_layout',
				mcs_post_id             : mcs_data.post_id,
				mcs_section_id          : section_id,
				mcs_section_layout      : $(this).val(),
				mcs_choose_layout_nonce : mcs_data.choose_layout_nonce
			}, function( response ) {
				if ( response ) {

					var $response        = $( response ),
						$tinymce_editors = $response.find('.wp-editor-area'),
						$layout          = $( '#mcs-sections-editor-' + section_id );

					$layout.html('').append( $response );

					// Loop through all of our edits in the response

					blocks.reorder_blocks( $tinymce_editors );
					blocks.setup_resize_slider();
					blocks.setup_drag_drop();

					self.setup_notifications( $layout );

					$spinner.removeClass('is-active');

				} else {
					$spinner.removeClass('is-active');
				}
			});
		},

		/**
		 * Add a new section to our content
		 *
		 * @since 1.0.0
		 *
		 * @param event
		 * @returns {boolean}
		 */
		add_section : function(event) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this),
				$spinner = $this.siblings('.spinner');

			if ( $this.hasClass('disabled') ) {
				return false;
			}

			$spinner.addClass('is-active');

			$.post( ajaxurl, {
				action: 'mcs_add_section',
				mcs_post_id: mcs_data.post_id,
				mcs_add_section_nonce: mcs_data.add_section_nonce
			}, function( response ){
				if ( response ) {
					var $response        = $( response ),
						$tinymce_editors = $response.find('.wp-editor-area' );

					$section_container.append( $response );
					$spinner.removeClass('is-active');

					if ( $('.empty-sections-message').length ) {
						$('.empty-sections-message').fadeOut('fast');
					}

					var $postboxes = $('.multiple-content-sections-section', $meta_box_container );

					if ( $postboxes.length > 1 ) {
						$reorder_button.removeClass( 'disabled' );
					}

					blocks.reorder_blocks( $tinymce_editors );

				} else {
					$spinner.removeClass('is-active');
				}
			});
		},

		remove_section : function(event) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this),
				$postbox = $this.parents('.multiple-content-sections-postbox'),
				$spinner = $('.mcs-add-spinner', $postbox),
				section_id = $postbox.attr( 'data-mcs-section-id' );

			$spinner.addClass('is-active');

			$.post( ajaxurl, {
				action: 'mcs_remove_section',
				mcs_post_id: mcs_data.post_id,
				mcs_section_id: section_id,
				mcs_remove_section_nonce: mcs_data.remove_section_nonce
			}, function(response){
				if ( '1' === response ) {
					$postbox.fadeOut( 400, function(){
						$postbox.remove();

						var $postboxes = $('.multiple-content-sections-section', $meta_box_container);

						if ( $postboxes.length <= 1 ) {
							$reorder_button.addClass( 'disabled' );
						}
					});
				} else {
					$spinner.removeClass('is-active');
				}
			});
		},

		/**
		 * Save when sections are reordered
		 *
		 * @since 1.0
		 *
		 * @param event
		 */
		reorder_sections : function( event ) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this),
				$reorder_spinner = $this.siblings('.spinner'),
				$sections = $( '.multiple-content-sections-postbox', $section_container),
				$block_click_span = $( '<span class="mcs-block-click">' );

			$expand_button.addClass('disabled');
			$add_button.addClass('disabled');
			$meta_box_container.addClass('mcs-is-ordering');

			self.update_notifications( 'reorder', 'warning' );

			$('.hndle', $meta_box_container ).each(function(){
				$(this).prepend( $block_click_span.clone() );
			});

			$('.mcs-block-click').on('click', self.block_click );

			$this.text('Save Order').addClass('mcs-save-order button-primary').removeClass('mcs-section-reorder');

			$sections.each(function(){
				$(this).addClass('closed');
			});

			$section_container.sortable({
				update : self.save_section_order_sortable
			});
		},

		/**
		 * Utility method to display notification information
		 *
		 * @since 1.3.0
		 *
		 * @param string message The message to display
		 * @param string type The type of message to display (warning|info|success)
		 */
		update_notifications : function( message, type ) {

			$description
				.removeClass('notice-info notice-warning notice-success')
				.addClass('notice-' + type )
				.find('p')
				.text( mcs_data.strings[ message ] );

			if( ! $description.is(':visible') ) {
				$description.css({'opacity' : 0 }).show();
			}

			$description.fadeIn('fast');
		},

		save_section_order_sortable : function( event, ui ) {
			var $reorder_spinner = $('.mcs-reorder-spinner'),
				section_ids = [];

			$reorder_spinner.addClass( 'is-active' );

			$('.multiple-content-sections-postbox', $section_container).each(function(){
				section_ids.push( $(this).attr('data-mcs-section-id') );
			});

			response = self.save_section_ajax( section_ids, $reorder_spinner );
		},

		save_section_order : function(event) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this),
				$sections = $( '.multiple-content-sections-postbox', $section_container ),
				$reorder_spinner = $('.mcs-reorder-spinner'),
				section_ids = [];

			$reorder_spinner.addClass( 'is-active' );

			$expand_button.removeClass('disabled');
			$add_button.removeClass('disabled');
			$this.text('Reorder').addClass('mcs-section-reorder').removeClass('mcs-save-order button-primary');

			$('.multiple-content-sections-postbox', $section_container).each(function(){
				section_ids.push( $(this).attr('data-mcs-section-id') );
			});

			$('.mcs-block-click').remove();

			if( $description.is(':visible') ) {
				$description.removeClass('notice-warning').addClass('notice-info').find('p').text( mcs_data.strings.description );
			}

			self.save_section_ajax( section_ids, $reorder_spinner );

			$section_container.sortable('destroy');
		},

		save_section_ajax : function( section_ids, $current_spinner ) {
			$.post( ajaxurl, {
                'action': 'mcs_update_order',
                'mcs_post_id'    : parseInt( mcs_data.post_id ),
                'mcs_section_ids' : section_ids,
                'mcs_reorder_section_nonce' : mcs_data.reorder_section_nonce
            }, function( response ) {
				$current_spinner.removeClass( 'is-active' );
            });
		},

		change_input_title : function(event) {
			var $this = $(this),
				current_title = $this.val(),
				$handle_title = $this.siblings('.handle-title');

			self.prevent_submit( event, $this );

			if ( $this.is('select') ) {
				return;
			}

			if ( current_title === '' || current_title == 'undefined' ) {
				current_title = mcs_data.strings.default_title;
			}

			$handle_title.text( current_title );
		},

		change_select_title : function( event ) {
			var $this = $(this),
				current_title = $this.val(),
				$handle_title = $this.sibling('.handle-title');

			switch ( current_title ) {
				case 'publish':
					current_title = mcs_data.strings.published;
					break;

				case 'draft':
					current_title = mcs.strings.draft;
			}

			$handle_title.text( current_title );
		}

		/**
		 * Prevent submitting the post/page when hitting enter
		 * while focused on a section or block form element
		 *
		 * @since 1.0.0
		 *
		 * @param event
		 */
		prevent_submit : function( event, $this ) {
			if ( 13 == event.keyCode ) {
				$this.siblings('.close-title-edit').trigger('click');

				event.preventDefault();

				return false;
			}
		},

		/**
		 * Block our click event while reordering
		 *
		 * @since 1.0.0
		 *
		 * @param event
		 */
		block_click : function(event){
			event.stopImmediatePropagation();
		},

		/**
		 * Remove our selected background
		 *
		 * @since 1.3.6
		 *
		 * @param event
		 */
		remove_background : function(event) {

			event.preventDefault();
			event.stopPropagation();

			var $button       = $(this),
				$section      = $button.parents('.multiple-content-sections-postbox'),
				section_id    = parseInt( $section.attr('data-mcs-section-id') ),
				current_image = $button.attr('data-mcs-section-featured-image'),
				$edit_icon = $( '<span class="dashicons dashicons-format-image" />');

			$.post( ajaxurl, {
				'action': 'mcs_update_featured_image',
				'mcs_section_id'  : parseInt( section_id ),
				'mcs_featured_image_nonce' : mcs_data.featured_image_nonce
			}, function( response ) {
				if ( response != -1 ) {
					if ( $button.prev().hasClass('right') && ! $button.prev().hasClass('button') ) {
						$button.prev().toggleClass( 'button right' );
					}

					$button.prev().text( mcs_data.strings.add_image );

					$button.remove();
				}
			});
		},

		choose_background : function(event) {
			event.preventDefault();
			event.stopPropagation();

			var $button       = $(this),
				$section      = $button.parents('.multiple-content-sections-postbox'),
				section_id    = parseInt( $section.attr('data-mcs-section-id') ),
				frame_id      = 'mcs-background-select-' + section_id,
				current_image = $button.attr('data-mcs-section-featured-image');

	        // If the frame already exists, re-open it.
	        if ( media_frames[ frame_id ] ) {
                media_frames[ frame_id ].uploader.uploader.param( 'mcs_upload', 'true' );
	            media_frames[ frame_id ].open();
	            return;
	        }

	        /**
	         * The media frame doesn't exist let, so let's create it with some options.
	         */
	        media_frames[ frame_id ] = wp.media.frames.media_frames = wp.media({
	            className: 'media-frame mcs-media-frame',
	            frame: 'select',
	            multiple: false,
	            title: mcs_data.strings.select_section_bg,
	            button: {
	                text: mcs_data.strings.select_bg
	            }
	        });

            media_frames[ frame_id ].on('open', function(){
	            // Grab our attachment selection and construct a JSON representation of the model.
	            var selection = media_frames[ frame_id ].state().get('selection');

                selection.add( wp.media.attachment( current_image ) );
	        });

            media_frames[ frame_id ].on('select', function(){
	            // Grab our attachment selection and construct a JSON representation of the model.
	            var media_attachment = media_frames[ frame_id ].state().get('selection').first().toJSON(),
	            	$edit_icon = $( '<span />', {
						'class' : 'dashicons dashicons-edit'
					}),
					$trash = $('<a/>', {
						'data-mcs-section-featured-image': '',
						'href' : '#',
						'class' : 'mcs-featured-image-trash dashicons-before dashicons-dismiss'
					});

				$.post( ajaxurl, {
	                'action': 'mcs_update_featured_image',
	                'mcs_section_id'  : parseInt( section_id ),
	                'mcs_image_id' : parseInt( media_attachment.id ),
	                'mcs_featured_image_nonce' : mcs_data.featured_image_nonce
	            }, function( response ) {
					if ( response != -1 ) {
						current_image = media_attachment.id;
						$button
							.html( '<img src="' + media_attachment.url + '" />' )
							.attr('data-mcs-section-featured-image', parseInt( media_attachment.id ) )
							.after( $trash );

						if ( $button.hasClass('button') && ! $button.hasClass('right') ) {
							$button.toggleClass( 'button right' );
						}
					}
	            });
	        });

	        // Now that everything has been set, let's open up the frame.
	        media_frames[ frame_id ].open();
		}
	};
} ( jQuery );

jQuery(function( $ ) {
    multiple_content_sections.admin.init();
});