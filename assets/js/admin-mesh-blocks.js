/**
 * Controls Block Administration
 *
 * @since 0.4.1
 */

var mesh = mesh || {};

mesh.blocks = function ($) {

	var $body = $('body'),
		// Instance of our block controller
		self,
		admin,
		block_cache = {};

	return {

		/**
		 * Initialize out Blocks Administration
		 */
		init: function () {

			self = mesh.blocks;
			admin = mesh.admin;

			if ('post' !== mesh_data.screen) {
				return;
			}

			$body
				.on('click', '.mesh-block-featured-image-trash', self.remove_background)
				.on('click', '.mesh-block-featured-image-choose', self.choose_background)
				.on('click.OpenMediaManager', '.mesh-block-featured-image-choose', self.choose_background)
				.on('click', '.mesh-clean-edit:not(.title-input-visible)', self.show_field)
				.on('blur', '.mesh-clean-edit-element:not(select)', self.hide_field)
				.on('click', '.close-title-edit', self.hide_field)
				.on('click', '.slide-toggle-element', self.slide_toggle_element)
				.on('change', '.mesh-column-offset', self.display_offset);

			self.setup_resize_slider();
			self.setup_sortable();
		},

		/**
		 * Setup sorting of blocks in the admin
		 *
		 * @since 1.0.0
		 */
		setup_sortable: function () {
			var column_order = [];

			$('.mesh-editor-blocks .mesh-row').sortable({
				// OPTIONS
				axis: 'x',
				cursor: 'move',
				cursorAt: {left: 0},
				distance: 20,
				handle: '.the-mover',
				items: '.mesh-section-block',
				tolerance: 'pointer',

				// EVENTS
				create: function (event, ui) {
					$('.mesh-editor-blocks .fade-in-on-create').fadeIn('slow');
				},

				start: function (event, ui) {
					var $tgt = $(event.target),
						$column_slider = $tgt.find('.column-slider');

					// Fade out column resizer to avoid odd UI
					$column_slider.fadeOut('fast');

					$('.mesh-section-block:not(.ui-sortable-placeholder)', this).each(function () {
						column_order.push($(this).attr('class'));
					});
				},

				stop: function (event, ui) {
					var $tgt = $(event.target),
						$column_slider = $tgt.find('.column-slider');

					// Fade back in column resizer
					$column_slider.fadeIn('slow');
				},

				update: function (event, ui) {
					var $this = $(this),
						$tgt = $(event.target),
						$section = $tgt.parents('.mesh-section'),
						section_id = $section.attr('data-mesh-section-id'),
						$blocks = $this.find('.mesh-section-block');

					$blocks.each(function (i) {
						var $this = $(this);

						$this.removeAttr('class').addClass(column_order[i]);
						$this.find('.block-menu-order').val(i);
					});

					self.rerender_blocks($section.find('.wp-editor-area'));
					self.save_order(section_id, event, ui);
					self.setup_sortable();
				}
			});
		},

		/**
		 * Change Block Widths based on Column Resizing
		 *
		 * @param event
		 * @param ui
		 * @since 1.0.0
		 */
		change_block_widths: function (event, ui) {
			var $tgt = $(event.target),
				$columns = $tgt.parent().parent().parent().find('.mesh-editor-blocks').find('.mesh-row:first .columns').addClass('dragging'),
				column_length = $columns.length,
				column_total = 12,
				column_values = [],
				slider_values = ui.values,
				post_data = {
					post_id: parseInt(mesh_data.post_id),
					section_id: parseInt($tgt.closest('.mesh-section').attr('data-mesh-section-id')),
					blocks: {}
				};

			// Set array to store columns widths
			// If returned values are [3, 9]
			// -> col 1 = val1 = 3
			// -> col 2 = (val2 - val1) = (9 - 3) = 6
			// -> col 3 = (avail - val2) = (12 - 9) = 3
			if (3 == column_length) {
				for (var i = 0; i <= column_length; i++) {
					switch (i) {
						case 0:
							column_values.push(slider_values[i]);
							break;

						case 1:
							column_values.push(slider_values[i] - slider_values[0]);
							break;

						case 2:
							column_values.push(column_total - slider_values[1]);
							break;
					}
				}
			}

			// Set array to store columns widths
			// If returned value is [4]
			// -> col 1 = val1 = 4
			// -> col 2 = (avail - val1) = (12 - 4) = 8
			if (2 == column_length) {
				column_values.push(slider_values[0]);
				column_values.push(column_total - slider_values[0]);
			}

			// Custom class removal based on regex pattern
			$columns.removeClass(function (index, css) {
				return (css.match(/\mesh-columns-\d+/g) || []).join(' ');
			}).each(function (index) {
				var $this = $(this),
					block_id = parseInt($this.find('.block').attr('data-mesh-block-id')),
					$column_input = $this.find('.column-width'),
					$offset_select = $this.find('.mesh-column-offset'),
					selected_offset = $offset_select.val(),
					column_value = parseInt(column_values[index]),
					max_offset = column_value - 3;

				$offset_select.children('option').remove();

				for (var i = 0; i <= max_offset; i++) {
					$offset_select.append($('<option></option>').attr('value', i).text(i));
				}

				if (selected_offset > max_offset) {
					$offset_select.val(0).trigger('change');
				} else {
					$offset_select.val(selected_offset).trigger('change');
				}

				// Reset column width classes and save post data
				$this.addClass('mesh-columns-' + column_value);

				if (block_id && column_values[index]) {
					$column_input.val(column_value);
					post_data.blocks[block_id.toString()] = column_value;
				}
			});


			self.rerender_blocks($columns.find('.wp-editor-area'));
		},

		/**
		 * Setup Resize Slider
		 */
		setup_resize_slider: function () {

			$('.column-slider').addClass('ui-slider-horizontal').each(function () {
				var $this = $(this),
					blocks = parseInt($this.attr('data-mesh-blocks')),
					is_range = ( blocks > 2 ),
					vals = $.parseJSON($this.attr('data-mesh-columns')),
					data = {
						range: is_range,
						min: 0,
						max: 12,
						step: 1,
						left: 3,
						right: 9,
						gap: 3,
						start: function () {
							$this.css('z-index', 1000);
						},
						stop: function () {
							$this.css('z-index', '').find('.ui-slider-handle').css('z-index', 1000);
						},
						slide: self.change_block_widths
					};

				if (vals) {
					data.value = vals[0];
				}

				if (blocks === 3) {
					vals[1] = vals[0] + vals[1]; // add the first 2 columns together
					vals.pop();
					data.values = vals;
					data.value = null;
				}

				$this.limitslider(data);
			});
		},

		/**
		 * Render Block after reorder or change.
		 *
		 * @since 0.3.5
		 *
		 * @param $tinymce_editors
		 */
		rerender_blocks: function ($tinymce_editors) {

			$tinymce_editors.each(function () {
				var editor_id = $(this).prop('id'),
					proto_id,
					mce_options = [],
					qt_options = [];

				if (typeof tinymce !== 'undefined') {

					// Reset our editors if we have any
					if (parseInt(tinymce.majorVersion) >= 4) {
						tinymce.execCommand('mceRemoveEditor', false, editor_id);
					}

					var $block_content = $(this).closest('.block-content');

					/**
					 * Props to @danielbachuber for a shove in the right direction to have movable editors in the
					 * wp-admin
					 *
					 * https://github.com/alleyinteractive/wordpress-fieldmanager/blob/master/js/richtext.js#L58-L95
					 */
					if (typeof tinyMCEPreInit.mceInit[editor_id] === 'undefined') {
						proto_id = 'content';

						// Clean up the proto id which appears in some of the wp_editor generated HTML

						var block_html = $(this).closest('.block-content').html();

						block_html = block_html.replace(new RegExp('id="' + proto_id + '"', 'g'), 'id="' + editor_id + '"');

						$block_content.html(block_html);


						// This needs to be initialized, so we need to get the options from the proto
						if (proto_id && typeof tinyMCEPreInit.mceInit[proto_id] !== 'undefined') {
							mce_options = $.extend(true, {}, tinyMCEPreInit.mceInit[proto_id]);
							mce_options.body_class = mce_options.body_class.replace(proto_id, editor_id);
							mce_options.selector = mce_options.selector.replace(proto_id, editor_id);
							mce_options.wp_skip_init = false;
							mce_options.plugins = 'lists,media,paste,tabfocus,wordpress,wpautoresize,wpeditimage,wpgallery,wplink,wptextpattern,wpview';
							mce_options.block_formats = 'Paragraph=p; Heading 3=h3; Heading 4=h4';
							mce_options.toolbar1 = 'bold,italic,bullist,numlist,hr,alignleft,aligncenter,alignright,alignjustify,link,wp_adv ';
							mce_options.toolbar2 = 'formatselect,underline,strikethrough,forecolor,pastetext,removeformat ';
							mce_options.toolbar3 = '';
							mce_options.toolbar4 = '';

							tinyMCEPreInit.mceInit[editor_id] = mce_options;
						} else {
							// TODO: No data to work with, this should throw some sort of error
							return;
						}
					}

					try {
						if ('html' !== mesh.blocks.mode_enabled(this)) {

							if (parseInt(tinymce.majorVersion) >= 4) {
								tinymce.execCommand('mceRemoveEditor', false, editor_id);
							}

							$(this).closest('.wp-editor-wrap').on('click.wp-editor', function () {
								if (this.id) {
									window.wpActiveEditor = this.id.slice(3, -5);
								}
							});
						}
					} catch (e) {
						console.log(e);
					}

					try {

						proto_id = 'content';

						if (proto_id && typeof tinyMCEPreInit.qtInit[proto_id] !== 'undefined') {
							qt_options = $.extend(true, {}, tinyMCEPreInit.qtInit[proto_id]);

							qt_options.id = qt_options.id.replace(proto_id, editor_id);

							tinyMCEPreInit.qtInit[editor_id] = qt_options;

							qt_options.buttons = 'strong,em,link,block,img,ul,ol,li';

							if (typeof quicktags !== 'undefined') {
								quicktags(tinyMCEPreInit.qtInit[editor_id]);
							}

							if (typeof QTags !== 'undefined') {
								QTags._buttonsInit();
							}
						}
					} catch (e) {
						console.log(e);
					}

					// @todo This is kinda hacky. See about switching this out @aware
					$block_content.find('.switch-tmce').trigger('click');

					/*
					 * Cache
					 */
					if (typeof tinymce !== 'undefined') {
						var editor = tinymce.get(editor_id),
							cached_block_content = self.get_block_cache(editor_id);

						// Make sure we have an editor and we have cache for it.
						// Once the cache is
						if (editor && !editor.hidden) {
							if (cached_block_content) {
								editor.setContent(cached_block_content);
								self.delete_block_cache(editor_id);
							}
						} else {
							if(cached_block_content) {
								editor.val(cached_block_content);
							}
						}
					}
				}
			});

			if (typeof mesh.integrations.yoast != 'undefined') {
				mesh.integrations.yoast.addMeshSections();
			}
		},

		mode_enabled: function (el) {
			return $(el).closest('.html-active').length ? 'html' : 'tinymce';
		},

		/**
		 * Save the order of our blocks after drag and drop reorder
		 *
		 * @since 0.1.0
		 *
		 * @param section_id
		 * @param event
		 * @param ui
		 */
		save_order: function (section_id, event, ui) {
			var $reorder_spinner = $('.mesh-reorder-spinner'),
				block_ids = [];

			$('#mesh-sections-editor-' + section_id).find('.block').each(function () {
				block_ids.push($(this).attr('data-mesh-block-id'));
			});
		},

		/**
		 * Choose a background for our block
		 *
		 * @param event
		 */
		choose_background: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $button = $(this),
				$section = $button.parents('.block'),
				section_id = parseInt($section.attr('data-mesh-block-id')),
				frame_id = 'mesh-background-select-' + section_id,
				current_image = $button.attr('data-mesh-block-featured-image');

			admin.media_frames = admin.media_frames || [];

			// If the frame already exists, re-open it.
			if (admin.media_frames[frame_id]) {
				admin.media_frames[frame_id].uploader.uploader.param('mesh_upload', 'true');
				admin.media_frames[frame_id].open();
				return;
			}

			/**
			 * The media frame doesn't exist let, so let's create it with some options.
			 */
			admin.media_frames[frame_id] = wp.media.frames.media_frames = wp.media({
				className: 'media-frame mesh-media-frame',
				frame: 'select',
				multiple: false,
				title: mesh_data.strings.select_block_bg,
				button: {
					text: mesh_data.strings.select_bg
				}
			});

			admin.media_frames[frame_id].on('open', function () {
				// Grab our attachment selection and construct a JSON representation of the model.
				var selection = admin.media_frames[frame_id].state().get('selection');

				selection.add(wp.media.attachment(current_image));
			});

			admin.media_frames[frame_id].on('select', function () {
				// Grab our attachment selection and construct a JSON representation of the model.
				var media_attachment = admin.media_frames[frame_id].state().get('selection').first().toJSON(),
					$edit_icon = $('<span />', {
						'class': 'dashicons dashicons-edit'
					}),
					$trash = $('<a/>', {
						'data-mesh-section-featured-image': '',
						'href': '#',
						'class': 'mesh-block-featured-image-trash dashicons-before dashicons-dismiss'
					});

				$.post(ajaxurl, {
					'action': 'mesh_update_featured_image',
					'mesh_section_id': parseInt(section_id),
					'mesh_image_id': parseInt(media_attachment.id),
					'mesh_featured_image_nonce': mesh_data.featured_image_nonce
				}, function (response) {
					if (response != -1) {
						current_image = media_attachment.id;
						$button
							.html('<img src="' + media_attachment.url + '" />')
							.attr('data-mesh-block-featured-image', parseInt(media_attachment.id))
							.after($trash);
					}
				});
			});

			// Now that everything has been set, let's open up the frame.
			admin.media_frames[frame_id].open();
		},

		/**
		 * Remove selected background from our block
		 *
		 * @since 0.3.6
		 *
		 * @param event
		 */
		remove_background: function (event) {

			event.preventDefault();
			event.stopPropagation();

			var $button = $(this),
				$section = $button.parents('.block'),
				section_id = parseInt($section.attr('data-mesh-block-id'));

			$.post(ajaxurl, {
				'action': 'mesh_update_featured_image',
				'mesh_section_id': parseInt(section_id),
				'mesh_featured_image_nonce': mesh_data.featured_image_nonce
			}, function (response) {
				if (response != -1) {
					$button.prev().text(mesh_data.strings.add_image);
					$button.remove();
				}
			});
		},

		show_field: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this);

			if ($this.parents('.mesh-postbox').hasClass('closed')) {
				return;
			}

			$(this).addClass('title-input-visible');
		},

		hide_field: function (event) {
			event.preventDefault();
			event.stopPropagation();

			$(this).parent().removeClass('title-input-visible');
		},

		slide_toggle_element: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this),
				$toggle = $this.data('toggle');

			$($toggle).slideToggle('fast');
			$this.toggleClass('toggled');
		},

		display_offset: function (event) {
			var offset = $(this).val(),
				$block = $(this).parents('.block-header').next('.block-content');

			$block.removeClass('mesh-has-offset mesh-offset-1 mesh-offset-2 mesh-offset-3 mesh-offset-4 mesh-offset-5 mesh-offset-6 mesh-offset-7 mesh-offset-8 mesh-offset-9');

			if (parseInt(offset)) {
				$block.addClass('mesh-has-offset mesh-offset-' + offset);
			}
		},

		/**
		 * Setup Block Drag and Drop
		 *
		 * @since 0.3.0
		 * @deprecated - Keep for fallback if sortable doesn't work out.
		 */
		setup_drag_drop: function () {

			$(".mesh-editor-blocks .block").draggable({
				'appendTo': 'body',
				helper: function (event) {

					var $this = $(this),
						_width = $this.width();
					$clone = $this.clone().width(_width).css('background', '#fff');
					$clone.find('*').removeAttr('id');

					return $clone;
				},
				revert: true,
				zIndex: 1000,
				handle: '.the-mover',
				iframeFix: true,
				start: function (ui, event, helper) {
				}
			});

			$(".block")
				.addClass("ui-widget ui-widget-content ui-helper-clearfix")
				.find(".block-header")
				.addClass("hndle ui-sortable-handle")
				.prepend("<span class='block-toggle' />");

			$(".drop-target").droppable({
				accept: ".block:not(.ui-sortable-helper)",
				activeClass: "ui-state-hover",
				hoverClass: "ui-state-active",
				handle: ".block-header",
				revert: true,
				drop: function (event, ui) {

					var $this = $(this),
						$swap_clone = ui.draggable,
						$swap_parent = ui.draggable.parent(),
						$tgt = $(event.target),
						$tgt_clone = $tgt.find('.block'),
						$section = $tgt.parents('.mesh-section'),
						section_id = $section.attr('data-mesh-section-id');

					$swap_clone.css({'top': '', 'left': ''});

					$this.append($swap_clone);
					$swap_parent.append($tgt_clone);

					self.rerender_blocks($section.find('.wp-editor-area'));
					self.save_order(section_id, event, ui);
					self.setup_drag_drop();

					return false;
				}
			});
		},

		/**
		 * Save block cached content when changes are made within a block.
		 *
		 * @since 1.2
		 * @param block_id
		 * @param cache_content
		 * @return boolean
		 */
		set_block_cache: function (block_id, cache_content) {

			if (!block_id || !cache_content) {
				return false;
			}

			block_cache[block_id] = cache_content;

			return true;
		},

		/**
		 * Get block cached content
		 *
		 * @since 1.2
		 * @param block_id
		 * @return string
		 */
		get_block_cache: function (block_id) {

			if (block_cache[block_id]) {
				return block_cache[block_id]; // get the block ID from the local cache.
			}

			return ''; // cached content for the block.
		},

		/**
		 * Get all the editors within a container/section.
		 *
		 * @since 1.2
		 * @param object $container
		 */
		get_tinymce_editors: function ($container) {
			return $container.find('.wp-editor-area');
		},

		/**
		 * Delete specific block cached content
		 *
		 * @since 1.2
		 * @param block_id
		 * @return string
		 */
		delete_block_cache: function (block_id) {
			if (block_cache[block_id]) {
				delete block_cache[block_id];
			}
		}
	};

}(jQuery);
