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
		media_frames        = [],

		// since 1.3.5
		temp_data_storage   = {
			theme: "modern",
			skin: "lightgray",
			language: "en",
			formats: {
				alignleft: [{
					selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
					styles: {textAlign: "left"}
				}, {selector: "img,table,dl.wp-caption", classes: "alignleft"}],
				aligncenter: [{
					selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
					styles: {textAlign: "center"}
				}, {selector: "img,table,dl.wp-caption", classes: "aligncenter"}],
				alignright: [{
					selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
					styles: {textAlign: "right"}
				}, {selector: "img,table,dl.wp-caption", classes: "alignright"}],
				strikethrough: {inline: "del"}
			},
			relative_urls: false,
			remove_script_host: false,
			convert_urls: false,
			browser_spellcheck: true,
			fix_list_elements: true,
			entities: "38,amp,60,lt,62,gt",
			entity_encoding: "raw",
			keep_styles: false,
			cache_suffix: "wp-mce-4203-20150730",
			preview_styles: "font-family font-size font-weight font-style text-decoration text-transform",
			end_container_on_empty_block: true,
			wpeditimage_disable_captions: false,
			wpeditimage_html5_captions: true,
			plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",
			content_css: mcs_data.site_uri + "/wp-includes/css/dashicons.css?ver=4.3," + mcs_data.site_uri + "/wp-includes/js/tinymce/skins/wordpress/wp-content.css?ver=4.3,https://fonts.googleapis.com/css?family=Noto+Sans%3A400italic%2C700italic%2C400%2C700%7CNoto+Serif%3A400italic%2C700italic%2C400%2C700%7CInconsolata%3A400%2C700&subset=latin%2Clatin-ext," + mcs_data.site_uri + "/wp-content/themes/twentyfifteen/css/editor-style.css," + mcs_data.site_uri + "/wp-content/themes/twentyfifteen/genericons/genericons.css",
			resize: false,
			menubar: false,
			wpautop: true,
			indent: false,
			toolbar1: "bold,italic,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,spellchecker",
			toolbar2: "",
			toolbar3: "",
			toolbar4: "",
			tabfocus_elements: "content-html,save-post",
			body_class: "content post-type-page post-status-publish locale-en-us",
			wp_autoresize_on: true,
			add_unload_trigger: false
		};

	return {

		/**
		 * Initialize our script
		 */
		init : function() {
			$body
				.on('click', '.mcs-section-add', multiple_content_sections.admin.add_section )
				.on('click', '.mcs-section-remove', multiple_content_sections.admin.remove_section )
				.on('click', '.mcs-section-reorder', multiple_content_sections.admin.reorder_sections )
				.on('click', '.mcs-save-order', multiple_content_sections.admin.save_section_order )

				.on('change', '.mcs-choose-layout', multiple_content_sections.admin.choose_layout )

				.on('click', '.mcs-featured-image-choose', multiple_content_sections.admin.choose_background )
				.on('click.OpenMediaManager', '.mcs-featured-image-choose', multiple_content_sections.admin.choose_background )

				.on('click', '.mcs-section-expand', multiple_content_sections.admin.expand_all_sections )

				.on('keyup', '.mcs-section-title', multiple_content_sections.admin.change_section_title );

			var $sections = $( '.multiple-content-sections-section' );

			if ( $sections.length <= 1 ) {
				$reorder_button.addClass( 'disabled' );
			}

			multiple_content_sections.admin.setup_slider();
			multiple_content_sections.admin.setup_drag_drop();

		},

		setup_slider : function() {
			$('.column-slider').addClass('ui-slider-horizontal').each(function() {

				var $this = $(this);

				$this.slider({
					value: $this.attr('data-mcs-columns'),
					min:0,
					max:12,
					step:1,
					change : multiple_content_sections.admin.save_column_widths
				});
			});
		},

		setup_drag_drop : function() {

			$( ".mcs-editor-blocks .block" ).draggable({
				'appendTo' : 'body',
			//	containment : '.mcs-editor-blocks',
				helper : 'original',
				revert: true
			});

			$( ".block" )
				.addClass( "ui-widget ui-widget-content ui-helper-clearfix" )
				.find( ".block-header" )
				.addClass( "hndle ui-sortable-handle" )
				.prepend( "<span class='block-toggle'></span>");
/*
			$( ".block-toggle" ).click(function() {
				var icon = $( this );
				icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
				icon.closest( ".block" ).find( ".block-content" ).toggle();
			}); */

			$( ".drop-target" ).droppable({
				accept: ".block:not(.ui-sortable-helper)",
				activeClass: "ui-state-hover",
				hoverClass: "ui-state-active",
				handle: ".block-header",
				revert: true,
				drop: function( event, ui ) {

					var $this = $(this),
						$swap_clone  = ui.draggable,
						$swap_parent = ui.draggable.parent(),
						$tgt         = $( event.target),
						$tgt_clone   = $tgt.find('.block'),
						$section     = $tgt.parents('.multiple-content-sections-section'),
						section_id   = $section.attr('data-mcs-section-id');

					$swap_clone.css( { 'top':'','left':'' } );

					$this.append( $swap_clone );
					$swap_parent.append( $tgt_clone );

					multiple_content_sections.admin.reorder_blocks( $section.find('.wp-editor-area') );
					multiple_content_sections.admin.save_block_order_sortable( section_id, event, ui );

					multiple_content_sections.admin.setup_drag_drop();

					return false;
				}
			});
		},

		/**
		 * Render Block after reorder or change.
		 *
		 * @since 1.3.5
		 *
		 * @param $editors
		 */
		reorder_blocks : function( $editors ) {
			$editors.each(function() {
				var editor_id   = $(this).prop('id'),
					editor_data = temp_data_storage;

				// Reset our editors if we have any
				if( typeof tinymce.editors !== 'undefined' ) {
					if ( tinymce.editors[ editor_id ] ) {
						tinymce.get( editor_id ).remove();
					}
				}

				// Setup our editors
				editor_data.selector = '#' + editor_id;
				tinymce.init( editor_data );

				//tinyMCE.execCommand('mceRepaint', false, editor_id );
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
				$this.text('Expand All');
			} else {
				$this.text('Collapse All');
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

					var $response = $( response ),
						$editors  = $response.find('.wp-editor-area');

					$( '#mcs-sections-editor-' + section_id ).html('').append( $response );

					// Loop through all of our edits in the response

					multiple_content_sections.admin.reorder_blocks( $editors );

					multiple_content_sections.admin.setup_slider();

					$spinner.removeClass('is-active');

				} else {
					$spinner.removeClass('is-active');
				}
			});
		},

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
					var $response = $( response ),
						$editors  = $response.find('.wp-editor-area');

					$section_container.append( $response );
					$spinner.removeClass('is-active');

					var $postboxes = $('.multiple-content-sections-section', $meta_box_container );

					if ( $postboxes.length > 1 ) {
						$reorder_button.removeClass( 'disabled' );
					}

					multiple_content_sections.admin.reorder_blocks( $editors );

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

						$postboxes = $('.multiple-content-sections-section', $meta_box_container);
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
		 * Save when a user adjust column widths still allow 12 columns min max
		 * but cap the limits to 3 and 9 based on common usage.
		 *
		 * @todo: Add filters for column min, max
		 *
		 * @since 1.3.5
		 *
		 * @param event
		 * @param ui
		 */
		save_column_widths : function( event, ui ) {

			var $tgt          = $( event.target ),
				$columns      = $tgt.parent().parent().parent().find('.mcs-editor-blocks').find('.columns'),
				column_total  = 12,
				column_value  = $tgt.slider( "value" ),
				column_start  = column_value,
				post_data     = {
					post_id : parseInt( mcs_data.post_id ),
					section_id : parseInt( $tgt.closest('.multiple-content-sections-section').attr('data-mcs-section-id') ),
					blocks : {}
				};

			// cap max column width
			if( column_value > 9 ){
				$tgt.slider( "value", 9 );
				column_value = 9;
				column_start = 9;
			}

			// cap min column width
			if( column_value < 3 ){
				$tgt.slider( "value", 3 );
				column_value = 3;
				column_start = 3;
			}

			// Custom class removal based on regex pattern
			$columns.removeClass (function (index, css) {
				return (css.match (/\mcs-columns-\d+/g) || []).join(' ');
			});

			$columns.each( function() {
				var $this = $(this),
					block_id = parseInt( $this.find('.block').attr('data-mcs-block-id') ),
					$column_input = $this.find('.column-width');

				$this.addClass( 'mcs-columns-' + column_start );

				if( block_id && column_start ) {
					$column_input.val( column_start );
					post_data.blocks[ block_id.toString() ] = column_start;
				}

				column_start = column_total - column_value;
			} );

			$.post( ajaxurl, {
				'action': 'mcs_update_block_widths',
				'mcs_post_data' : post_data,
				'mcs_reorder_blocks_nonce' : mcs_data.reorder_blocks_nonce
			}, function( response ) {
				// $current_spinner.removeClass( 'is-active' );
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
				$block_click_span = $( '<span />' ).attr({
					'class' : 'mcs-block-click'
				});

			$expand_button.addClass('disabled');
			$add_button.addClass('disabled');
			$meta_box_container.addClass('mcs-is-ordering');

			multiple_content_sections.admin.update_notifications( 'reorder', 'warning' );

			$('.hndle', $meta_box_container ).each(function(){
				$(this).prepend( $block_click_span.clone() );
			});

			$('.mcs-block-click').on('click', multiple_content_sections.admin.block_click );

			$this.text('Save Order').addClass('mcs-save-order button-primary').removeClass('mcs-section-reorder');

			$sections.each(function(){
				$(this).addClass('closed');
			});

			$section_container.sortable({
				update: multiple_content_sections.admin.save_section_order_sortable
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
				.text( mcs_data.labels[ message ] );

			if( ! $description.is(':visible') ) {
				$description.css({'opacity' : 0 }).show();
			}

			$description.fadeIn('fast');
		},

		/**
		 * Save the order of our blocks after drag and drop reorde
		 *
		 * @param int    section_id
		 * @param object event
		 * @param object ui
		 */
		save_block_order_sortable : function( section_id, event, ui ) {

			var $reorder_spinner = $('.mcs-reorder-spinner'),
				block_ids = [];

			$( '#mcs-sections-editor-' + section_id ).find( '.block' ).each( function() {
				block_ids.push( $(this).attr('data-mcs-block-id') );
			});

			response = multiple_content_sections.admin.save_block_ajax( section_id, block_ids, $reorder_spinner );
		},

		/**
		 * Save when we reorder our blocks within a section
		 *
		 * @since 1.3.5
		 *
		 * @param section_id
		 * @param block_ids
		 * @param $reorder_spinner
		 */
		save_block_ajax : function( section_id, block_ids, $reorder_spinner ) {

			$.post( ajaxurl, {
				'action': 'mcs_update_block_order',
				'mcs_section_id'    : section_id,
				'mcs_blocks_ids' : block_ids,
				'mcs_reorder_blocks_nonce' : mcs_data.reorder_blocks_nonce
			}, function( response ) {
				// $current_spinner.removeClass( 'is-active' );
			});
		},

		save_section_order_sortable : function( event, ui ) {
			var $reorder_spinner = $('.mcs-reorder-spinner'),
				section_ids = [];

			$reorder_spinner.addClass( 'is-active' );

			$('.multiple-content-sections-postbox', $section_container).each(function(){
				section_ids.push( $(this).attr('data-mcs-section-id') );
			});

			response = multiple_content_sections.admin.save_section_ajax( section_ids, $reorder_spinner );
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
				$description.removeClass('notice-warning').addClass('notice-info').find('p').text( mcs_data.labels.description );
			}

			multiple_content_sections.admin.save_section_ajax( section_ids, $reorder_spinner );

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

		change_section_title : function(event) {
			var $this = $(this),
				current_title = $this.val(),
				$postbox = $this.parents('.multiple-content-sections-postbox');

			if ( current_title === '' || current_title == 'undefined' ) {
				current_title = 'No Title';
			}

			$('h3.hndle', $postbox).html( current_title );
		},

		block_click : function(event){
			event.stopImmediatePropagation();
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
	            title: 'Select Section Background',
	            button: {
	                text: 'Select Background'
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
	            	$edit_icon = $( '<span />' ).attr({
		            	'class' : 'dashicons dashicons-edit'
		            });

				$.post( ajaxurl, {
	                'action': 'mcs_update_featured_image',
	                'mcs_section_id'  : parseInt( section_id ),
	                'mcs_image_id' : parseInt( media_attachment.id ),
	                'mcs_featured_image_nonce' : mcs_data.featured_image_nonce
	            }, function( response ) {
					if ( response != -1 ) {
						current_image = media_attachment.id;
						$button.text( media_attachment.title ).attr('data-mcs-section-featured-image', parseInt( media_attachment.id ) ).append( $edit_icon );
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