var mesh = mesh || {};

mesh.admin = function ( $ ) {

	var $body		        = $('body'),
		$reorder_button     = $('.mesh-section-reorder'),
		$add_button         = $('.mesh-section-add'),
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
		section_count;

	return {

		/**
		 * Initialize our script
		 */
		init : function() {

			self     = mesh.admin;
			blocks   = mesh.blocks;
			pointers = mesh.pointers;

			$body
				.on('click', '.mesh-section-add',           self.add_section )
				.on('click', '.mesh-section-remove',        self.remove_section )
				.on('click', '.mesh-section-reorder',       self.reorder_sections )
				.on('click', '.mesh-save-order',            self.save_section_order )
				.on('click', '.mesh-featured-image-trash',  self.remove_background )
				.on('click', '.mesh-section-expand',        self.expand_all_sections )
				.on('click', '.mesh-section-collapse',      self.collapse_all_sections )
				.on('click', '.mesh-featured-image-choose', self.choose_background )
				.on('click.OpenMediaManager', '.mesh-featured-image-choose', self.choose_background )

				.on('click', '.mesh-section-update',        self.section_save )
				.on('click', '.mesh-section-save-draft',    self.section_save_draft )
				.on('click', '.mesh-section-publish',       self.section_publish )

				.on('change', '.mesh-choose-layout',            self.choose_layout )
				.on('keypress', '.msc-clean-edit-element',     self.prevent_submit )
				.on('keyup', '.msc-clean-edit-element',        self.change_input_title )
				.on('change', 'select.msc-clean-edit-element', self.change_select_title );

			$sections = $( '.mesh-section' );

			if ( $sections.length <= 1 ) {
				$reorder_button.addClass( 'disabled' );
			}

			if ( 'undefined' == typeof Foundation ) {
				$equalize.each( self.mesh_equalize );
			}

			// Setup our controls for Blocks
			blocks.init();

			// Seupt our Pointers
			pointers.show_pointer(0);

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
						action                : 'mesh_dismiss_notification',
						mesh_notification_type : $this.attr('data-type'),
						_wpnonce              : mesh_data.dismiss_nonce
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
		 * 1 click to expand sections
		 *
		 * @since 0.3.0
		 *
		 * @param event
		 */
		expand_all_sections : function( event ) {

			event.preventDefault();
			event.stopPropagation();

			var $this = $(this);

			$this.addClass('expanded');

			$('#mesh-container').find('.handlediv').each(function () {
				if ( $this.hasClass('expanded') && 'true' != $(this).attr('aria-expanded') ) {
					$(this).trigger('click');
				}
			} );
		},

		/**
		 * 1 click to collapse sections
		 *
		 * @since 1.0.0
		 *
		 * @param event
		 */
		collapse_all_sections : function( event ) {

			event.preventDefault();
			event.stopPropagation();

			var $this = $(this);

			$this.removeClass('expanded');

			$('#mesh-container').find('.handlediv').each(function () {
				if ( ! $this.hasClass('expanded') && 'true' == $(this).attr('aria-expanded') ) {
					$(this).trigger('click');
				}
			} );
		},

		/**
		 * Choose what layout is used for the section
		 *
		 * @since 0.1.0
		 *
		 * @param event
		 * @returns {boolean}
		 */
		choose_layout : function( event ) {

			event.preventDefault();
			event.stopPropagation();

			var $this      = $(this),
				$spinner   = $this.siblings('.spinner'),
				$section   = $this.parents('.mesh-section'),
				section_id = $section.attr('data-mesh-section-id');

			if ( $this.hasClass('disabled') ) {
				return false;
			}

			$spinner.addClass('is-active');

			$.post( ajaxurl, {
				action                  : 'mesh_choose_layout',
				mesh_post_id             : mesh_data.post_id,
				mesh_section_id          : section_id,
				mesh_section_layout      : $(this).val(),
				mesh_choose_layout_nonce : mesh_data.choose_layout_nonce
			}, function( response ) {
				if ( response ) {

					var $response        = $( response ),
						$tinymce_editors,
						$layout          = $( '#mesh-sections-editor-' + section_id );

					$tinymce_editors = $section.find('.wp-editor-area');

					$tinymce_editors.each( function() {
						if ( parseInt( tinymce.majorVersion ) >= 4 ) {
							tinymce.execCommand( 'mceRemoveEditor', false, $(this).prop('id') );
						}
					});

					$layout.html('').append( $response );
					
					// Loop through all of our edits in the response
					// reset our editors after clearing
					$tinymce_editors = $section.find('.wp-editor-area');

					blocks.setup_resize_slider();
					blocks.setup_sortable();
					blocks.rerender_blocks( $tinymce_editors );

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
		 * @since 0.1.0
		 *
		 * @param event
		 * @returns {boolean}
		 */
		add_section : function(event) {
			event.preventDefault();
			event.stopPropagation();

			section_count = $sections.length;

			var $this = $(this),
				$spinner = $this.siblings('.spinner');

			if ( $this.hasClass('disabled') ) {
				return false;
			}

			$spinner.addClass('is-active');

			$.post( ajaxurl, {
				action: 'mesh_add_section',
				mesh_post_id: mesh_data.post_id,
				mesh_section_count: section_count,
				mesh_add_section_nonce: mesh_data.add_section_nonce
			}, function( response ){
				if ( response ) {
					var $response        = $( response ),
						$tinymce_editors = $response.find('.wp-editor-area' ),
						$empty_msg       = $('.empty-sections-message'),
						$controls        = $('.mesh-main-ua-row');

					$section_container.append( $response );
					$spinner.removeClass('is-active');

					if ( $empty_msg.length ) {
						$empty_msg.fadeOut('fast');
						$controls.fadeIn('fast');
					}

					var $postboxes = $('.mesh-section', $meta_box_container );

					if ( $postboxes.length > 1 ) {
						$reorder_button.removeClass( 'disabled' );
					}

					blocks.rerender_blocks( $tinymce_editors );

					// Repopulate the sections cache so that the new section is included going forward.
					$sections = $('.mesh-section', $section_container);

					setTimeout(function () {
						mesh.pointers.show_pointer();
					}, 250);

				} else {
					$spinner.removeClass('is-active');
				}
			});
		},

		/**
		 * Publish the current section
		 *
		 * @since 1.0.0
		 *
		 * @param event
         */
		section_publish : function(event) {
			event.preventDefault();
			event.stopPropagation();

			var $section = $(this).closest( '.mesh-section' ),
				$post_status_field = $( '.mesh-section-status', $section ),
				$post_status_label = $( '.mesh-section-status-text', $section ),
				$update_button     = $( '.mesh-section-update', $section );

			$post_status_field.val( 'publish' );
			$post_status_label.text( mesh_data.strings.published );
			$update_button.trigger( 'click' );
		},

		/**
		 * Save a draft of the current section
		 *
		 * @since 1.0.0
		 *
		 * @param event
         */
		section_save_draft : function(event) {
			event.preventDefault();
			event.stopPropagation();

			var $section       = $(this).closest( '.mesh-section' ),
				$update_button = $( '.mesh-section-update', $section );

			$update_button.trigger( 'click' );
		},

		/**
		 * Save the current section through an ajax call
		 *
		 * @since 1.0.0
		 *
		 * @param event
         */
		section_save : function(event) {
			event.preventDefault();
			event.stopPropagation();

			var $button = $(this),
				$button_container = $button.parent(),
				$spinner = $( '.spinner', $button.parent() ),
				$current_section = $(this).closest( '.mesh-section' ),
				$post_status_field = $( '.mesh-section-status', $current_section ),
				section_id = $current_section.attr( 'data-mesh-section-id' );

			$current_section.find('.mesh-editor-blocks .wp-editor-area').each( function() {
				var content = tinymce.get( $(this).attr('ID') ).getContent({format : 'raw'});
				$('#' + $(this).attr('ID') ).val( content );
			});

			var	form_data = $current_section.parents( 'form' ).serialize(),
				form_submit_data = [];

			$( '.button', $button_container ).addClass( 'disabled' );
			$spinner.addClass( 'is-active' );

			$.post( ajaxurl, {
				action: 'mesh_save_section',
				mesh_section_id: section_id,
				mesh_section_data: form_data,
				mesh_save_section_nonce: mesh_data.save_section_nonce
			}, function( response ) {
				$button_container.find( '.button' ).removeClass( 'disabled' );
				$spinner.removeClass( 'is-active' );

				if (response) {

					var $publish_draft = $( '.mesh-section-publish,.mesh-section-save-draft' );

					if ( 'publish' == $post_status_field.val() ) {
						$publish_draft.addClass( 'hidden' );
						$button.removeClass( 'hidden' );
					} else {
						$publish_draft.removeClass( 'hidden' );
						$button.addClass( 'hidden' );
					}
				}
			});
		},

		/**
		 * Remove the section
		 *
		 * @since 0.1.0
		 *
		 * @param event
		 */
		remove_section : function(event) {
			event.preventDefault();
			event.stopPropagation();

			var confirm_remove = confirm( mesh_data.strings.confirm_remove );

			if ( ! confirm_remove ) {
				return;
			}

			var $this = $(this),
				$postbox = $this.parents('.mesh-postbox'),
				$spinner = $('.mesh-add-spinner', $postbox),
				section_id = $postbox.attr( 'data-mesh-section-id' );

			$spinner.addClass('is-active');

			$.post( ajaxurl, {
				action: 'mesh_remove_section',
				mesh_post_id: mesh_data.post_id,
				mesh_section_id: section_id,
				mesh_remove_section_nonce: mesh_data.remove_section_nonce
			}, function(response){
				if ( '1' === response ) {
					$postbox.fadeOut( 400, function(){
						$postbox.remove();

						var $postboxes = $meta_box_container.find( '.mesh-section' );

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
		 * @since 0.1.0
		 *
		 * @param event
		 */
		reorder_sections : function( event ) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this),
				$reorder_spinner = $this.siblings('.spinner'),
				$sections = $( '.mesh-postbox', $section_container),
				$block_click_span = $( '<span class="mesh-block-click">' );

			$expand_button.addClass('disabled');
			$add_button.addClass('disabled');
			$meta_box_container.addClass('mesh-is-ordering');

			self.update_notifications( 'reorder', 'warning' );

			$('.hndle', $meta_box_container ).each(function(){
				$(this).prepend( $block_click_span.clone() );
			});

			$('.mesh-block-click').on('click', self.block_click );

			$reorder_button.text( mesh_data.strings.save_order ).addClass('mesh-save-order button-primary').removeClass('mesh-section-reorder');

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
		 * @since 0.3.0
		 *
		 * @param message The message to display
		 * @param type    The type of message to display (warning|info|success)
		 */
		update_notifications : function( message, type ) {

			$description
				.removeClass('notice-info notice-warning notice-success')
				.addClass('notice-' + type )
				.find('p')
				.text( mesh_data.strings[ message ] );

			if( ! $description.is(':visible') ) {
				$description.css({'opacity' : 0 }).show();
			}

			$description.fadeIn('fast');
		},

		/**
		 * Autosave callback
		 *
		 * @since 1.0.0
		 *
		 * @param event
		 * @param ui
		 */
		save_section_order_sortable : function( event, ui ) {
			var $reorder_spinner = $('.mesh-reorder-spinner'),
				section_ids = [];

			$reorder_spinner.addClass( 'is-active' );

			$('.mesh-postbox', $section_container).each(function(){
				section_ids.push( $(this).attr('data-mesh-section-id') );
			});

			response = self.save_section_ajax( section_ids, $reorder_spinner );
		},

		/**
		 * Initiate saving the section order
		 *
		 * @param event
         */
		save_section_order : function(event) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this),
			// @todo confirm this is needed : $sections = $( '.mesh-postbox', $section_container ),
				$reorder_spinner = $('.mesh-reorder-spinner'),
				section_ids = [];

			$reorder_spinner.addClass( 'is-active' );

			$expand_button.removeClass('disabled');
			$add_button.removeClass('disabled');
			$reorder_button.text( mesh_data.strings.reorder ).addClass('mesh-section-reorder').removeClass('mesh-save-order button-primary');

			$('.mesh-postbox', $section_container).each(function(){
				section_ids.push( $(this).attr('data-mesh-section-id') );
			});

			$('.mesh-block-click').remove();

			if( $description.is(':visible') ) {
				$description.removeClass('notice-warning').addClass('notice-info').find('p').text( mesh_data.strings.description );
			}

			self.save_section_ajax( section_ids, $reorder_spinner );

			$section_container.sortable('destroy');
		},

		/**
		 * AJAX call to save section.
		 *
		 * @param section_ids
		 * @param $current_spinner
         */
		save_section_ajax : function( section_ids, $current_spinner ) {
			$.post( ajaxurl, {
                'action': 'mesh_update_order',
                'mesh_post_id'    : parseInt( mesh_data.post_id ),
                'mesh_section_ids' : section_ids,
                'mesh_reorder_section_nonce' : mesh_data.reorder_section_nonce
            }, function( response ) {
				$current_spinner.removeClass( 'is-active' );
            });
		},

		/**
		 * @todo needs description @mmorgan?
		 *
		 * @param event
         */
		change_input_title : function(event) {
			var $this = $(this),
				current_title = $this.val(),
				$handle_title = $this.siblings('.handle-title');

			if ( $this.is('select') ) {
				return;
			}

			if ( current_title === '' || current_title == 'undefined' ) {
				current_title = mesh_data.strings.default_title;
			}

			$handle_title.text( current_title );
		},


		/**
		 * Change the title on our select field
		 *
		 * @param event
         */
		change_select_title : function( event ) {
			var $this = $(this),
				current_title = $this.val(),
				$handle_title = $this.siblings('.handle-title');

			switch ( current_title ) {
				case 'publish':
					current_title = mesh_data.strings.published;
					break;

				case 'draft':
					current_title = mesh_data.strings.draft;
			}

			$handle_title.text( current_title );
		},

		/**
		 * Prevent submitting the post/page when hitting enter
		 * while focused on a section or block form element
		 *
		 * @since 1.0.0
		 *
		 * @param event
		 */
		prevent_submit : function( event ) {
			if ( 13 == event.keyCode ) {
				$(this).siblings('.close-title-edit').trigger('click');

				event.preventDefault();

				return false;
			}
		},

		/**
		 * Block our click event while reordering
		 *
		 * @since 0.1.0
		 *
		 * @param event
		 */
		block_click : function(event){
			event.stopImmediatePropagation();
		},

		/**
		 * Remove our selected background
		 *
		 * @since 0.3.6
		 *
		 * @param event
		 */
		remove_background : function(event) {

			event.preventDefault();
			event.stopPropagation();

			var $button       = $(this),
				$section      = $button.parents('.mesh-postbox'),
				section_id    = parseInt( $section.attr('data-mesh-section-id') );

			$.post( ajaxurl, {
				'action': 'mesh_update_featured_image',
				'mesh_section_id'  : parseInt( section_id ),
				'mesh_featured_image_nonce' : mesh_data.featured_image_nonce
			}, function( response ) {
				if ( response != -1 ) {

					if ( $button.prev().hasClass('right') && ! $button.prev().hasClass('button') ) {
						if ( ! $button.parents('.block-background-container') ) {
							$button.prev().toggleClass( 'button right' );
						} else {
							$button.prev().toggleClass( 'right' ).attr('data-mesh-block-featured-image', '' );
						}
					}

					$button.prev().text( mesh_data.strings.add_image );
					$button.remove();
				}
			});
		},

		/**
		 * Choose the background for our section
		 *
		 * @param event
         */
		choose_background : function(event) {
			event.preventDefault();
			event.stopPropagation();

			var $button       = $(this),
				$section      = $button.parents('.mesh-postbox'),
				section_id    = parseInt( $section.attr('data-mesh-section-id') ),
				frame_id      = 'mesh-background-select-' + section_id,
				current_image = $button.attr('data-mesh-section-featured-image');

	        // If the frame already exists, re-open it.
	        if ( media_frames[ frame_id ] ) {
                media_frames[ frame_id ].uploader.uploader.param( 'mesh_upload', 'true' );
	            media_frames[ frame_id ].open();
	            return;
	        }

	        /**
	         * The media frame doesn't exist let, so let's create it with some options.
	         */
	        media_frames[ frame_id ] = wp.media.frames.media_frames = wp.media({
	            className: 'media-frame mesh-media-frame',
	            frame: 'select',
	            multiple: false,
	            title: mesh_data.strings.select_section_bg,
	            button: {
	                text: mesh_data.strings.select_bg
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
						'data-mesh-section-featured-image': '',
						'href' : '#',
						'class' : 'mesh-featured-image-trash dashicons-before dashicons-dismiss'
					});

				$.post( ajaxurl, {
	                'action': 'mesh_update_featured_image',
	                'mesh_section_id'  : parseInt( section_id ),
	                'mesh_image_id' : parseInt( media_attachment.id ),
	                'mesh_featured_image_nonce' : mesh_data.featured_image_nonce
	            }, function( response ) {
					if ( response != -1 ) {
						current_image = media_attachment.id;

						var $img = $('<img />', {
							src : media_attachment.url
						});

						$button
							.html( $img.html() )
							.attr('data-mesh-section-featured-image', parseInt( media_attachment.id ) )
							.after( $trash );

						if ( $button.hasClass('button') && ! $button.hasClass('right') ) {
							$button.toggleClass( 'button right' );
						}
					}
	            });
	        });

	        // Now that everything has been set, let's open up the frame.
	        media_frames[ frame_id ].open();
		},

		/**
		 * Add ability to equalize blocks
		 *
		 * @since 0.4.0
		 */
		mesh_equalize : function() {

			var $this     = $(this),
				$childs   = $('[data-equalizer-watch]', $this),
				eq_height = 0;

			$childs.each( function() {
				var this_height = $(this).height();

				eq_height = this_height > eq_height ? this_height : eq_height;
			}).height(eq_height);

		}
	};
} ( jQuery );

jQuery(function( $ ) {
    mesh.admin.init();
});