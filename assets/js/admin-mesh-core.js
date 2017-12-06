var mesh = mesh || {};
mesh.integrations = mesh.integrations || {}; // @since 1.2 store integrations.

mesh.admin = function ($) {

	var $body = $('body'),
		$document = $('document'),
		$reorder_button = $('.mesh-section-reorder'),
		$add_button = $('.mesh-section-add'),
		$collapse_button = $('.mesh-section-collapse'),
		$expand_button = $('.mesh-section-expand'),
		$meta_box_container = $('#mesh-container'),
		$section_container = $('#mesh-sections-container'),
		$description = $('#mesh-description'),
		$equalize = $('[data-equalizer]'),
		$sections,
		media_frames = [],

		// Settings

		FADE_SPEED = 100,

		// Container References for Admin(self) / Block
		self,
		blocks,
		pointers,
		templates,
		section_count;

	/*** @return object */
	return {

		/**
		 * Initialize our script
		 */
		init: function () {

			if ('post' !== mesh_data.screen && 'edit' !== mesh_data.screen && 'settings_page_mesh' !== mesh_data.screen) {
				return;
			}

			if ('edit' === mesh_data.screen) {
				templates = mesh.templates;
				// Setup our controls for templates
				templates.init();
			}

			self = mesh.admin;
			blocks = mesh.blocks;
			pointers = mesh.pointers;
			templates = mesh.templates;

			$body
				.on('click', '.mesh-section-add', self.add_section)
				.on('click', '.mesh-section-remove', self.remove_section)
				.on('click', '.mesh-section-reorder', self.reorder_sections)
				.on('click', '.mesh-save-order', self.save_section_order)
				.on('click', '.mesh-featured-image-trash', self.remove_background)
				.on('click', '.mesh-section-expand', self.expand_all_sections)
				.on('click', '.mesh-section-collapse', self.collapse_all_sections)
				.on('click', '.mesh-featured-image-choose', self.choose_background)
				.on('click.OpenMediaManager', '.mesh-featured-image-choose', self.choose_background)

				// @since 1.1
				.on('click', '.mesh-trash-extra-blocks', self.trash_extra_blocks)

				.on('click', '.mesh-section-update', self.section_save)
				.on('click', '.mesh-section-save-draft', self.section_save_draft)
				.on('click', '.mesh-section-publish', self.section_publish)

				.on('change', '.mesh-choose-layout', self.choose_layout)
				.on('keypress', '.mesh-clean-edit-element', self.prevent_submit)
				.on('keyup', '.mesh-clean-edit-element', self.change_input_title)
				.on('change', 'select.mesh-clean-edit-element', self.change_select_title)

				// @since 1.1.3
				.on('change', '#mesh-css_mode', self.display_foundation_options);

			// @since 1.2

			var event = ( typeof( event ) != 'undefined' ) ? event : '';

			$(document)
				.on('postbox-toggled', {event: event}, self.expand_section );

			$sections = $('.mesh-section');

			if ($sections.length <= 1) {
				$reorder_button.addClass('disabled');
			}

			if ($equalize.length) {
				$equalize.each(self.mesh_equalize);
			}

			// Setup our controls for Blocks
			blocks.init();

			// Setup our Pointers
			pointers.show_pointer(0);

			// Setup our controls for templates
			templates.init();

			self.setup_notifications($meta_box_container);

			self.display_foundation_options();

		},

		/**
		 * Add notifications to our section
		 *
		 * @param $layout
		 * @returns void
		 */
		setup_notifications: function ($layout) {
			// Make notices dismissible
			$layout.find('.notice.is-dismissible').each(function () {
				var $this = $(this),
					$button = $('<button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button>'),
					btnText = commonL10n.dismiss || '';

				// Ensure plain text
				$button.find('.screen-reader-text').text(btnText);

				$this.append($button);

				$button.on('click.wp-dismiss-notice', function (event) {
					event.preventDefault();

					$.post(ajaxurl, {
						action: 'mesh_dismiss_notification',
						mesh_notification_type: $this.attr('data-type'),
						_wpnonce: mesh_data.dismiss_nonce
					}, function (response) {
					});

					$this.fadeTo( FADE_SPEED, 0, function () {
						$(this).slideUp( FADE_SPEED, function () {
							$(this).remove();
						});
					});
				});
			});
		},

		/**
		 * Expand targeted section
		 *
		 * @since 1.2
		 *
		 * @param {event}  event  The jQuery Event.
		 * @param {object} element The Object Being Expanded (typically postbox).
		 * @return void
		 */
		expand_section: function ( event, element ) {

			var $section = $(element),
				$tinymce_editor = $section.find('.wp-editor-area');

			if (!$section.hasClass('closed')) {
				blocks.rerender_blocks($tinymce_editor);
			}
		},

		/**
		 * 1 click to expand all sections
		 *
		 * @since 0.3.0
		 *
		 * @param {event} event Click Event.
		 */
		expand_all_sections: function (event) {

			event.preventDefault();
			event.stopPropagation();

			$sections.each(function () {
				var $handle = $(this).find('.handlediv');

				if ('true' != $handle.attr('aria-expanded')) {
					$handle.trigger('click');
					self.expand_section(event, $(this));
				}
			});
		},

		/**
		 * 1 click to collapse sections
		 *
		 * @since 1.0.0
		 *
		 * @param {event} event Click Event.
		 * @return void
		 */
		collapse_all_sections: function (event) {

			if (typeof( event ) != 'undefined') {
				event.preventDefault();
				event.stopPropagation();
			}

			$section_container.find('.handlediv').each(function () {

				var $this = $(this);

				if ('true' == $this.attr('aria-expanded') || $this.hasClass('toggled')) {
					$this.trigger('click');
				}
			});
		},

		/**
		 * This method is only used when a new section is added
		 * to a post. The post toggle action is not bound to the document
		 * or body so we are replicating what is happening from core.
		 *
		 * @since 1.1
		 *
		 * @param event
		 * @return void
		 */
		toggle_collapse: function (event) {

			var $el = $(this),
				p = $el.parent('.postbox'),
				id = p.attr('id'),
				ariaExpandedValue;

			p.toggleClass('closed');

			ariaExpandedValue = !p.hasClass('closed');

			if ($el.hasClass('handlediv')) {
				// The handle button was clicked.
				$el.attr('aria-expanded', ariaExpandedValue);
			} else {
				// The handle heading was clicked.
				$el.closest('.postbox').find('button.handlediv')
					.attr('aria-expanded', ariaExpandedValue);
			}

			if (postboxes.page !== 'press-this') {
				postboxes.save_state(postboxes.page);
			}

			if (id) {
				if (!p.hasClass('closed') && $.isFunction(postboxes.pbshow)) {
					postboxes.pbshow(id);
				} else if (p.hasClass('closed') && $.isFunction(postboxes.pbhide)) {
					postboxes.pbhide(id);
				}
			}

			self.expand_section(event, p.closest('.mesh-section'));
		},

		/**
		 * Choose what layout is used for the section
		 *
		 * @since 0.1.0
		 *
		 * @param {event} event Click Event.
		 * @returns {boolean}
		 */
		choose_layout: function (event) {

			event.preventDefault();
			event.stopPropagation();

			var $this = $(this),
				temp_val = $(this).val(),
				$spinner = $this.siblings('.spinner'),
				$section = $this.parents('.mesh-section'),
				section_id = $section.attr('data-mesh-section-id'),
				$more_options = $section.find('.mesh-section-meta').find('.mesh-more-section-options'),
				tab_open = $more_options.hasClass('toggled');

			if ($this.hasClass('disabled')) {
				return false;
			}

			var $tinymce_editors = blocks.get_tinymce_editors($section);

			$tinymce_editors.each(function () {

				var tinyMCE_content = '',
					editorID = $(this).prop('id'),
					editor = tinymce.get(editorID);

				// Make sure we have an editor and we aren't in text view.
				if (editor && !editor.hidden) {
					tinyMCE_content = editor.getContent();
				}

				blocks.set_block_cache(editorID, tinyMCE_content);
			});

			$spinner.addClass('is-active');

			self.disable_controls($section);

			$.post(ajaxurl, {
				action: 'mesh_choose_layout',
				mesh_post_id: mesh_data.post_id,
				mesh_section_id: section_id,
				mesh_section_layout: temp_val,
				mesh_choose_layout_nonce: mesh_data.choose_layout_nonce
			}, function (response) {
				if (response) {

					var $response = $(response),
						$tinymce_editors,
						$section = $('#mesh-section-' + section_id);

					$tinymce_editors = $section.find('.wp-editor-area');

					// @todo this should be done more efficiently later: Needed for Firefox but will be fixed
					// once consolidated. Can't clear html before removing or tinymce throws an error
					$tinymce_editors.each(function () {

						if (parseInt(tinymce.majorVersion) >= 4) {
							tinymce.execCommand('mceRemoveEditor', false, $(this).prop('id'));
						}
					});

					// Store current display

					$response.find('.mesh-choose-layout').val(temp_val); // Set our newly render html to the properly
																		 // layout.

					// End display reset

					$section.find('.inside').html('').append($response);

					if (tab_open) {
						$section.find('.mesh-more-section-options').addClass('toggled');
						$section.find('.mesh-section-meta-dropdown').removeClass('hide').show();
					}

					// Loop through all of our edits in the response
					// reset our editors after clearing
					$tinymce_editors = $section.find('.wp-editor-area');

					blocks.setup_resize_slider();
					blocks.setup_sortable();
					blocks.rerender_blocks($tinymce_editors);

					// self.setup_notifications( $layout );
				}
				self.enable_controls($section);

				$spinner.removeClass('is-active');
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
		add_section: function (event) {
			event.preventDefault();
			event.stopPropagation();

			section_count = $sections.length;

			var $this = $(this),
				$spinner = $this.find('.spinner'),
				$meshSectionsContainer = $('#mesh-sections-container');

			if ($this.hasClass('disabled')) {
				return false;
			}

			self.disable_controls($meta_box_container);

			$this.addClass('active');

			$spinner.addClass('is-active');

			$.post(ajaxurl, {
				action: 'mesh_add_section',
				mesh_post_id: mesh_data.post_id,
				mesh_section_count: section_count,
				mesh_add_section_nonce: mesh_data.add_section_nonce
			}, function (response) {
				if (response) {
					var $response = $(response),
						$tinymce_editors = $response.find('.wp-editor-area'),
						$empty_msg = $('.empty-sections-message'),
						$controls = $('.mesh-main-ua-row');

					$section_container.append($response);
					$spinner.removeClass('is-active');

					$this.removeClass('active');

					if ($empty_msg.length) {
						$empty_msg.fadeOut('fast').promise(function () {
							$('#description-wrap').remove();
						});
						$controls.fadeIn('fast');
					}

					blocks.rerender_blocks($tinymce_editors);

					// Repopulate the sections cache so that the new section is included going forward.
					$sections = $('.mesh-section', $section_container);

					var $handle = $response.find('.handlediv');

					$handle.attr('aria-expanded', true)
						.on('click', self.toggle_collapse);

					setTimeout(function () {
						mesh.pointers.show_pointer();
					}, 250);

					self.enable_controls($meta_box_container);

					$meta_box_container.trigger("mesh:add_section");

				} else {
					$spinner.removeClass('is-active');
				}

				var windowBottom = $(window).height() + $(window).scrollTop(),
					meshBottom = $meshSectionsContainer.offset().top + $meshSectionsContainer.outerHeight(true),
					scrollTiming = ( ( meshBottom - windowBottom ) * .5 );

				if (1000 > scrollTiming) {
					scrollTiming = 1000;
				}

				if (3000 < scrollTiming) {
					scrollTiming = 3000;
				}


				$('html, body').animate({
					scrollTop: $meshSectionsContainer.offset().top + $meshSectionsContainer.outerHeight(true) - $(window).height()
				}, scrollTiming);
			});
		},

		/**
		 * Publish the current section
		 *
		 * @since 1.0.0
		 *
		 * @param event
		 */
		section_publish: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $section = $(this).closest('.mesh-section'),
				$post_status_field = $('.mesh-section-status', $section),
				$post_status_label = $('.mesh-section-status-text', $section),
				$update_button = $('.mesh-section-update', $section);

			$post_status_field.val('publish');
			$post_status_label.text(mesh_data.strings.published);
			$update_button.trigger('click');
		},

		/**
		 * Save a draft of the current section
		 *
		 * @since 1.0.0
		 *
		 * @param event
		 */
		section_save_draft: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $section = $(this).closest('.mesh-section'),
				$update_button = $('.mesh-section-update', $section);

			$update_button.trigger('click');
		},

		/**
		 * Save the current section through an ajax call
		 *
		 * @since 1.0.0
		 *
		 * @param event
		 */
		section_save: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $button = $(this),
				$button_container = $button.parent(),
				$spinner = $button_container.find('.spinner'),
				$saved_status = $button_container.find('.saved-status-icon'),
				$current_section = $button.closest('.mesh-section'),
				$post_status_field = $current_section.find('.mesh-section-status'),
				section_id = $current_section.attr('data-mesh-section-id');

			$current_section.find('.mesh-editor-blocks .wp-editor-area').each(function () {

				var content = '',
					editorID = $(this).attr('id'),
					editor = tinymce.get(editorID);

				// Make sure we have an editor and we aren't in text view.
				if (editor && !editor.hidden) {

					content = editor.getContent();

					$('#' + editorID).val(content);
				}

			});

			var form_data = $current_section.parents('form').serialize(),
				form_submit_data = [];

			$button_container.find('.button').addClass('disabled');
			$spinner.addClass('is-active');

			$.post(ajaxurl, {
				action: 'mesh_save_section',
				mesh_section_id: section_id,
				mesh_section_data: form_data,
				mesh_post_type: mesh_data.post_type,
				mesh_save_section_nonce: mesh_data.save_section_nonce
			}, function (response) {

				var $button = $button_container.find('.button');

				$button.removeClass('disabled');
				$spinner.removeClass('is-active');
				$saved_status.addClass("is-active").delay(2000).queue(function () {
					$(this).removeClass("is-active").dequeue();
				});

				if (response) {

					var $publish_draft = $current_section.find('.mesh-section-publish, .mesh-section-save-draft');

					if ('publish' == $post_status_field.val()) {
						$button.removeClass('hidden');
						$publish_draft.addClass('hidden');
					} else {
						$button.addClass('hidden');
						$publish_draft.removeClass('hidden');
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
		remove_section: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var confirm_remove = confirm(mesh_data.strings.confirm_remove);

			if (!confirm_remove) {
				return;
			}

			var $this = $(this),
				$postbox = $this.parents('.mesh-postbox'),
				$spinner = $('.mesh-add-spinner', $postbox),
				section_id = $postbox.attr('data-mesh-section-id');

			$spinner.addClass('is-active');

			$.post(ajaxurl, {
				action: 'mesh_remove_section',
				mesh_post_id: mesh_data.post_id,
				mesh_section_id: section_id,
				mesh_remove_section_nonce: mesh_data.remove_section_nonce
			}, function (response) {
				if ('1' === response) {
					$postbox.fadeOut(400, function () {
						$postbox.remove();

						var $postboxes = $meta_box_container.find('.mesh-section');

						if ($postboxes.length <= 1) {
							$reorder_button.addClass('disabled');
						}
					});
				} else if ('-1' === response) {
					console.log('There was an error');
				} else {

					var $response = $(response),
						$controls = $('.mesh-main-ua-row'),
						$description = $('#mesh-description');

					// Add either the empty message or visible sections.
					if (response.indexOf('mesh-empty-actions') === -1) {
						$section_container.append($response);
					}

					$postbox.fadeOut(400, function () {
						$postbox.remove();

						if (response.indexOf('mesh-empty-actions') > 0) {
							$description.html('').append($response).show();
						}
					});

					$controls.fadeOut('fast');

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
		reorder_sections: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this);

			if ($this.hasClass('disabled')) {
				return;
			}

			self.disable_controls($meta_box_container);

			$meta_box_container.addClass('mesh-is-ordering');

			// self.update_notifications( 'reorder', 'warning' );

			$reorder_button
				.text(mesh_data.strings.save_order)
				.addClass('mesh-save-order button-primary')
				.removeClass('mesh-section-reorder');

			self.collapse_all_sections();
			$section_container.sortable();
		},

		/**
		 * Utility method to display notification information
		 *
		 * @since 0.3.0
		 *
		 * @param message The message to display
		 * @param type    The type of message to display (warning|info|success)
		 */
		update_notifications: function (message, type) {

			$description
				.removeClass('notice-info notice-warning notice-success')
				.addClass('notice-' + type)
				.find('p')
				.text(mesh_data.strings[message]);

			if (!$description.is(':visible')) {
				$description.css({'opacity': 0}).show();
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
		save_section_order_sortable: function (event, ui) {
			var $reorder_spinner = $('.mesh-reorder-spinner'),
				section_ids = [];

			$reorder_spinner.addClass('is-active');

			$('.mesh-postbox', $section_container).each(function (index) {

				var $this = $(this);

				section_ids.push($this.attr('data-mesh-section-id'));

				$this.find('.section-menu-order').val(index);
			});
		},

		/**
		 * Initiate saving the section order
		 *
		 * @param event
		 */
		save_section_order: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this),
				$reorder_spinner = $('.mesh-reorder-spinner'),
				section_ids = [];

			$reorder_spinner.addClass('is-active');

			$meta_box_container.removeClass('mesh-is-ordering');

			self.enable_controls($meta_box_container);

			$reorder_button
				.text(mesh_data.strings.reorder)
				.addClass('mesh-section-reorder')
				.removeClass('mesh-save-order button-primary');

			$('.mesh-postbox', $section_container).each(function (index) {
				var $this = $(this);

				section_ids.push($this.attr('data-mesh-section-id'));

				$this.find('.section-menu-order').val(index);
			});

			if ($description.is(':visible')) {
				$description.removeClass('notice-warning').addClass('notice-info').find('p').text(mesh_data.strings.description);
			}

			self.save_section_ajax(section_ids, $reorder_spinner);

			$section_container.sortable('destroy');
		},

		/**
		 * AJAX call to save section.
		 *
		 * @param section_ids
		 * @param $current_spinner
		 */
		save_section_ajax: function (section_ids, $current_spinner) {
			$.post(ajaxurl, {
				'action': 'mesh_update_order',
				'mesh_post_id': parseInt(mesh_data.post_id),
				'mesh_section_ids': section_ids,
				'mesh_reorder_section_nonce': mesh_data.reorder_section_nonce
			}, function (response) {
				$current_spinner.removeClass('is-active');
			});
		},

		/**
		 * Handle the toggle been text and input areas
		 *
		 * @param event
		 */
		change_input_title: function (event) {
			var $this = $(this);

			if ($this.parents('.mesh-postbox').hasClass('closed')) {
				return;
			}

			var current_title = $this.val(),
				$handle_title = $this.siblings('.handle-title');

			if ($this.is('select')) {
				return;
			}

			if (current_title === '' || current_title == 'undefined') {
				current_title = mesh_data.strings.default_title;
			}

			$handle_title.text(current_title);
		},

		/**
		 * Change the title on our select field
		 *
		 * @param event
		 */
		change_select_title: function (event) {
			var $this = $(this),
				current_title = $this.val(),
				$handle_title = $this.siblings('.handle-title');

			switch (current_title) {
				case 'publish':
					current_title = mesh_data.strings.published;
					break;

				case 'draft':
					current_title = mesh_data.strings.draft;
			}

			$handle_title.text(current_title);
		},

		/**
		 * Prevent submitting the post/page when hitting enter
		 * while focused on a section or block form element
		 *
		 * @since 1.0.0
		 *
		 * @param event
		 */
		prevent_submit: function (event) {
			if (13 == event.keyCode) {
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
		block_click: function (event) {
			event.stopImmediatePropagation();
		},

		/**
		 * Remove our selected background
		 *
		 * @since 0.3.6
		 *
		 * @param event
		 */
		remove_background: function (event) {

			event.preventDefault();
			event.stopPropagation();

			var $button = $(this);

			if ($button.prev().hasClass('right') && !$button.prev().hasClass('button')) {
				if (!$button.parents('.block-background-container')) {
					$button.prev().toggleClass('button right');
				} else {
					$button.prev().toggleClass('right').attr('data-mesh-block-featured-image', '');
				}
			}

			$button.siblings('input[type="hidden"]').val('');

			$button.prev().text(mesh_data.strings.add_image);
			$button.remove();
		},

		/**
		 * Choose the background for our section
		 *
		 * @param event
		 */
		choose_background: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $button = $(this),
				$section = $button.parents('.mesh-postbox'),
				section_id = parseInt($section.attr('data-mesh-section-id')),
				frame_id = 'mesh-background-select-' + section_id,
				current_image = $button.attr('data-mesh-section-featured-image');

			// If the frame already exists, re-open it.
			if (media_frames[frame_id]) {
				media_frames[frame_id].uploader.uploader.param('mesh_upload', 'true');
				media_frames[frame_id].open();
				return;
			}

			/**
			 * The media frame doesn't exist let, so let's create it with some options.
			 */
			media_frames[frame_id] = wp.media.frames.media_frames = wp.media({
				className: 'media-frame mesh-media-frame',
				frame: 'select',
				multiple: false,
				title: mesh_data.strings.select_section_bg,
				button: {
					text: mesh_data.strings.select_bg
				}
			});

			media_frames[frame_id].on('open', function () {
				// Grab our attachment selection and construct a JSON representation of the model.
				var selection = media_frames[frame_id].state().get('selection');

				selection.add(wp.media.attachment(current_image));
			});

			media_frames[frame_id].on('select', function () {
				// Grab our attachment selection and construct a JSON representation of the model.
				var media_attachment = media_frames[frame_id].state().get('selection').first().toJSON(),
					$edit_icon = $('<span />', {
						'class': 'dashicons dashicons-edit'
					}),
					$trash = $('<a/>', {
						'data-mesh-section-featured-image': '',
						'href': '#',
						'class': 'mesh-featured-image-trash dashicons-before dashicons-dismiss'
					});

				current_image = media_attachment.id;

				var $img = $('<img />', {
					src: media_attachment.url
				});

				$button
					.html($img)
					.attr('data-mesh-section-featured-image', parseInt(media_attachment.id))
					.after($trash);

				// Add selected attachment id to input
				$button.siblings('input[type="hidden"]').val(media_attachment.id);

				if ($button.hasClass('button') && !$button.hasClass('right')) {
					$button.toggleClass('button right');
				}
			});

			// Now that everything has been set, let's open up the frame.
			media_frames[frame_id].open();
		},

		/**
		 * Add ability to equalize blocks
		 *
		 * @since 0.4.0
		 */
		mesh_equalize: function () {

			var $this = $(this),
				$childs = $('[data-equalizer-watch]', $this),
				eq_height = 0;

			$childs.each(function () {
				var this_height = $(this).height();

				eq_height = this_height > eq_height ? this_height : eq_height;
			}).height(eq_height);

		},

		/**
		 * Remove any extra non visible blocks from our section
		 * through an ajax call.
		 *
		 * @since 1.1
		 *
		 * @param {event} event
		 * @returns void
		 */
		trash_extra_blocks: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $current_section = $(this).closest('.mesh-section'),
				form_data = $current_section.parents('form').serialize();

			var $this = $(this),
				$postbox = $this.parents('.mesh-postbox'),
				section_id = $postbox.attr('data-mesh-section-id');

			self.disable_controls($postbox);

			$.post(ajaxurl, {
				action: 'mesh_trash_hidden_blocks',
				mesh_post_id: mesh_data.post_id,
				mesh_section_id: section_id,
				mesh_section_data: form_data,
				mesh_choose_layout_nonce: mesh_data.choose_layout_nonce,
				mesh_save_section_nonce: mesh_data.save_section_nonce
			}, function (response) {
				if ('1' === response) {

					var $notice = $postbox.find('.description.notice');

					$notice.fadeOut(400, function () {
						$notice.remove();
					});

				} else if ('-1' === response) {
					console.log('There was an error');
				}

				self.enable_controls($postbox);

			});
		},

		/**
		 * Disable all controls.
		 *
		 * This is best used when you are awaiting a
		 * response from an ajax call or if you are in
		 * a multi step option that shouldn't be interrupted
		 * by another action.
		 *
		 * @since 1.1
		 * @param {element} $tgt Selected Element.
		 */
		disable_controls: function ($tgt) {
			$expand_button.addClass('disabled');
			$add_button.addClass('disabled');
			$collapse_button.addClass('disabled');
			$reorder_button.addClass('disabled');

			var $postboxes = $('.mesh-section', $section_container);

			if ($postboxes.length > 1) {
				$reorder_button.removeClass('disabled');
			}

			$('.disabled-overlay').remove(); // Make sure we remove any instance of our overlay.

			$tgt.find('.inside').css('position', 'relative').prepend('<div class="disabled-overlay" />');
		},

		/**
		 * Enable all controls
		 *
		 * @since 1.1
		 * @param {element} $tgt Click Event
		 * @return void
		 */
		enable_controls: function ($tgt) {
			$expand_button.removeClass('disabled');
			$add_button.removeClass('disabled');
			$collapse_button.removeClass('disabled');

			var $postboxes = $('.mesh-section', $meta_box_container);

			if ($postboxes.length > 1) {
				$reorder_button.removeClass('disabled');
			} else {
				$reorder_button.addClass('disabled');
			}

			$tgt.find('.inside').find('.disabled-overlay').remove();
		},

		/**
		 * Allow the usage of Foundation 5 or 6 interchange
		 *
		 * @since 1.1.3
		 * @param {event} event Change Event
		 * @return void
		 */
		display_foundation_options: function (event) {

			var using_foundation = $('#mesh-css_mode').find('option:selected').val(),
				$foundation_version = $('#mesh-foundation_version'),
				$parent_row = $foundation_version.closest('tr');

			if (parseInt(using_foundation) === 1) {
				$parent_row.show();
			} else {
				$parent_row.hide();
				$foundation_version.val('');
			}
		}
	};
}(jQuery);

jQuery(function ($) {
	mesh.admin.init();
});