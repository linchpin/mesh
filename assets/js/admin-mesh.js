/*!
 * LimitSlider
 * https://github.com/vanderlee/limitslider
 *
 * Copyright (c) 2011-2015 Martijn W. van der Lee
 * Licensed under the MIT.
 */
/* Slider extension with forced limits and gaps.
 * Optional ranges, titles and labels.
 */

;(function ($, undefined) {
	"use strict";

	$.widget('vanderlee.limitslider', $.ui.slider, {
		options: $.extend({
			'classEven':	'ui-slider-handle-even',
			'classOdd':		'ui-slider-handle-odd',
			'gap':			undefined,
			'left':			undefined,
			'right':		undefined,
			'limit':		undefined,
			'limits':		undefined,
			'ranges':		[],
			'title':		false,
			'label':		false
		}, $.ui.slider.prototype.options),

		_create: function() {
			if (!this.options.values) {
				this.options.values = [this.options.value];
			}

			$.ui.slider.prototype._create.call(this);

			$(this.element).addClass('ui-limitslider');

			this._renderRanges();
			this._renderLabels();
			this._renderTitles();
		},

		_renderTitle: function(index) {
			if (this.options.title) {
				var value = this.options.values[index];
				$(this.handles[index])
						.attr('title', $.isFunction(this.options.title) ? this.options.title(value, index) : value)
						.addClass(this.options[index % 2 ? 'classEven' : 'classOdd']);
			}
		},

		_renderTitles: function(index) {
			if (this.options.title) {
				var that = this;
				$.each(this.options.values, function(v) {
					that._renderTitle(v);
				});
			}
		},

		_renderLabel: function(index) {
			if (this.options.label) {
				var value = this.options.values[index],
					html = $('<div>').css({
					'text-align':		'center'
				,	'font-size':		'75%'
				,	'display':			'table-cell'
				,	'vertical-align':	'middle'
				}).html($.isFunction(this.options.label) ? this.options.label(value, index) : value);

				$(this.handles[index]).html(html).css({
					'text-decoration':	'none'
				,	'display':			'table'
				});
			}
		},

		_renderLabels: function() {
			if (this.options.label) {
				var that = this;
				$.each(this.options.values, function(v) {
					that._renderLabel(v);
				});
			}
		},

		_renderRanges: function() {
			var options	= this.options,
				values  = options.values,
				scale   = function(value) {
							return (value - options.min) * 100 / (options.max - options.min);
						},
				index,
				left,
				right,
				range;

			$('.ui-slider-range', this.element).remove();

			for (index = 0; index <= values.length; ++index) {
				var range = options.ranges[index],
					sliderRange;

				if (range) {
					left = scale(index == 0? options.min : values[index - 1]);
					right = scale(index < values.length? values[index] : options.max);

					sliderRange = $('<div/>')
						.addClass('ui-slider-range ui-widget-header')
						.css('width', (right - left) + '%');

					if (range.styleClass) {
						sliderRange.addClass(range.styleClass);
					}

					if (left == 0) {
						sliderRange.addClass('ui-slider-range-min');
					} else if (right == 100) {
						sliderRange.addClass('ui-slider-range-max');
					} else {
						sliderRange.css('left', left+'%');
					}

					$(this.element).prepend(sliderRange);
//					sliderRange.prependTo(this.element);
				}
			}
		},

		_slide: function(event, index, newVal) {
			// Left limit
			if (this.options.left) {
				newVal = Math.max(newVal, this.options.left);
			}

			// Right limit
			if (this.options.right) {
				newVal = Math.min(newVal, this.options.right);
			}

			// Limit
			if (this.options.limit) {
				newVal = Math.max(newVal, this.options.limit[0]);
				newVal = Math.min(newVal, this.options.limit[1]);
			}

			// Per-slider limit
			if (this.options.limits && this.options.limits[index]) {
				newVal = Math.max(newVal, this.options.limits[index][0]);
				newVal = Math.min(newVal, this.options.limits[index][1]);
			}

			if (this.options.gap || this.options.gap === 0) {
				// Gap to previous
				if (index > 0) {
					 newVal = Math.max(newVal, this.options.values[index - 1] + this.options.gap);
				}

				// Gap to next
				if (index < this.options.values.length - 1) {
					 newVal = Math.min(newVal, this.options.values[index + 1] - this.options.gap);
				}
			}

			// Call parent
			$.ui.slider.prototype._slide.call(this, event, index, newVal);
		},

		_change: function(event, index) {
			// Call parent
			$.ui.slider.prototype._change.call(this, event, index);

			// Apply visuals
			this._renderRanges();
			this._renderLabel(index);
			this._renderTitle(index);
		},

		insert: function(index, value, range, limit) {
			var max = this.options.values.length,
				prev,
				next;

			index = (index === null || typeof index === 'undefined')
					? max
					: Math.max(0, Math.min(index, max));

			if (typeof value === 'undefined') {
				prev = index <= 0 ? this.options.min : this.options.values[index - 1],
				next = index >= max ? this.options.max : this.options.values[index];
				value = Math.round((prev + next) * .5);
			}

			this.options.values.splice(index, 0, value);
			if (this.options.ranges) {
				this.options.ranges.splice(index, 0, range || false);
			}
			if (this.options.limits) {
				this.options.limits.splice(index, 0, range || undefined);
			}

			this._create();
			this.element.trigger('slide', [index, value]);

			return this;
		},

		remove: function(index, length) {
			var max = this.options.values.length - 1;
			length = Math.max(1, length || 1);

			if (max > length - 1) {
				index = (index === null || typeof index === 'undefined')
						? max + 1 - length
						: Math.max(0, Math.min(index, max));

				this.options.values.splice(index, length);
				if (this.options.ranges) {
					this.options.ranges.splice(index, length);
				}
				if (this.options.limits) {
					this.options.limits.splice(index, length);
				}

				this._create();
			}

			return this;
		}
	});
}(jQuery));;
var mesh = mesh || {};

mesh.pointers = function ( $ ) {

    var current_index = 0;

    return {

        /**
         * Show our current pointer based on index.
         */
        show_pointer: function() {

            // Make sure we have pointers available.
            if( typeof( mesh_data.wp_pointers ) === 'undefined') {
                return;
            }

            var pointer = mesh_data.wp_pointers.pointers[current_index],
                options = $.extend( pointer.options, {
                    close: function() {
                        $.post( ajaxurl, {
                            pointer: pointer.pointer_id,
                            action: 'dismiss-wp-pointer'
                        });

                        current_index++;

						if ( current_index < mesh_data.wp_pointers.pointers.length ) {
	                        mesh.pointers.show_pointer();
						}
                    }
                });

            $(pointer.target).pointer( options ).pointer('open');
        }
    };

} ( jQuery );;
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
;
var mesh = mesh || {};

mesh.templates = function ( $ ) {

    var $body = $('body'),
        // Instance of our template controller
        self,
        blocks,
        $welcomePanel = $( '#mesh-template-welcome-panel' );

    return {

        /**
         * Initialize our Template Administration
         */
        init : function() {

            self   = mesh.templates;

            $welcomePanel.find( '.mesh-template-welcome-panel-close' ).on( 'click', function( event ) {
                event.preventDefault();

                $welcomePanel.addClass('hidden');

                self.updateWelcomePanel( 0 );
            });

            if ( 'post' !== mesh_data.screen ) {
                return;
            }

            blocks = mesh.blocks;

            $body
                .on('click', '.mesh-select-template',      self.select_template )
                .on('click', '.mesh-template-layout',      self.select_layout )
                .on('click', '.mesh-template-start',       self.display_template_types )
               //  .on('click', '.mesh-template-type',        self.select_template_type )
                .on('click', '.mesh-template-change-type', self.change_template_type )
                .on('click', '.mesh-template-remove',      self.remove_template );

                // .on('click', '.mesh_template .mesh-section-update, .mesh_template .mesh-section-publish', self.warn_on_save );
        },

        /**
         * Warn the user that they will their changes will
         * be applied to other templates on update/publish
         *
         * @todo 1.2
         *
         * @param event
         */
        warn_on_save : function( event ) {
            event.preventDefault();
            event.stopPropagation();

            var confirmation = confirm( mesh_data.strings.confirm_template_section_update );

            if ( true !== confirmation ) {
                self.applyTemplateChanges();
            }
        },

        /**
         * When we update a template's section(s) update all sections
         * of each posts that use this templates sections.
         *
         * @todo 1.2
         */
        applyTemplateChanges : function() {
            $.post( ajaxurl, {
                action: 'mesh_apply_template_changes',
                mesh_post_id: mesh_data.post_id,
                mesh_template_id: template,
                mesh_template_type: template_type,
                mesh_choose_template_nonce: mesh_data.choose_template_nonce
            }, function( response ) {
                if ( response ) {

                }
            });
        },

        /**
         * Show or Hide our Mesh Welcome Panel
         * Based on the Welcome Panel in WP Core
         *
         * @param visible
         */
        updateWelcomePanel : function( visible ) {
            $.post( ajaxurl, {
                action: 'mesh_template_update_welcome_panel',
                visible: visible,
                meshtemplatepanelnonce: $( '#mesh-templates-welcome-panel-nonce' ).val()
            });
        },

        remove_template : function( event ) {

        },

        /**
         * Change the type of template that is being used (Reference vs Starter)
         *
         * Our response should include a refreshed set of sections
         * with all of our proper controls needed now that this
         * template is no longer being used as a "reference"
         *
         * @since 1.1
         * @param event
         */
        change_template_type : function( event ) {
            event.preventDefault();
            event.stopPropagation();

            $.post( ajaxurl, {
                action: 'mesh_change_template_type',
                mesh_post_id: mesh_data.post_id,
                mesh_template_type: 'starter',
                mesh_choose_template_nonce: mesh_data.choose_template_nonce
            }, function( response ) {

                if ( response ) {
                    var $response = $(response),
                        $tinymce_editors = $response.find('.wp-editor-area'),
                        $empty_msg = $('.empty-sections-message'),
                        $controls = $('.mesh-main-ua-row');

                    var $mesh_container = $('#mesh-container');
                    $mesh_container.html('').append( $response.children() );
                    // $spinner.removeClass('is-active');

                    if ($empty_msg.length) {
                        $empty_msg.fadeOut('fast');
                        $controls.fadeIn('fast');
                    }

                    var $postboxes = $('.mesh-section', $mesh_container );

                    if ($postboxes.length > 1) {
                        $('.mesh-section-reorder').removeClass('disabled');
                    }

                    blocks.setup_resize_slider();
                    blocks.setup_sortable();
                    blocks.rerender_blocks($tinymce_editors);

                    // Repopulate the sections cache so that the new section is included going forward.
                    blocks.$sections = $('.mesh-section', $('#mesh-sections-container') );
                }
            });
        },

        /**
         * Display our available template usage, Reference or Starting Point
         * @param event
         */
        display_template_types : function( event ) {
            event.preventDefault();
            event.stopPropagation();

            // @todo 1.2
            // If we have a mesh template. Always use it as a starting point.
            // if( 'mesh_template' !== mesh_data.post_type ) {
            //    $('#mesh-template-usage').show();
            // } else {
            //    $('.mesh-starter-template').trigger('click');
            // }

            self.select_template_type( event );
        },

        /**
         * Select the type of template we are using
         * This can be either a reference template or
         * a starter template.
         *
         * @since 1.1
         * @param event
         */
        select_template_type : function( event ) {
            event.preventDefault();
            event.stopPropagation();

            var $this         = $(this),
                template      = $('.mesh-template:checked').val(),
                template_type = $this.attr( 'data-template-type' );

            $.post( ajaxurl, {
                action: 'mesh_choose_template',
                mesh_post_id: mesh_data.post_id,
                mesh_template_id: template,
                mesh_template_type: template_type,
                mesh_choose_template_nonce: mesh_data.choose_template_nonce
            }, function( response ) {
                if (response) {
                    var $response = $(response),
                        $tinymce_editors = $response.find('.wp-editor-area'),
                        $empty_msg = $('.empty-sections-message'),
                        $controls = $('.mesh-main-ua-row');

                   var $section_container = $('#mesh-sections-container');
                       $section_container.append($response);

                    if ($empty_msg.length) {
                        $empty_msg.fadeOut('fast');
                        $controls.fadeIn('fast');
                    }

                    var $postboxes = $('.mesh-section', $('#mesh-container'));

                    if ($postboxes.length > 1) {
                        $('.mesh-section-reorder').removeClass('disabled');
                    }

                    blocks.setup_resize_slider();
                    blocks.setup_sortable();
                    blocks.rerender_blocks($tinymce_editors);

                    // Repopulate the sections cache so that the new section is included going forward.
                    blocks.$sections = $('.mesh-section', $section_container);
                }
            });
        },

        /**
         * Select the template to use as a base.
         *
         * @todo security harden possibly, is it beneficial to output available templates for additional validation
         *
         * @since 1.1
         * @param event
         */
        select_layout : function( event ) {

            event.preventDefault();
            event.stopPropagation();

            var $this = $(this),
                $template_layouts = $('.mesh-template-layout');

            $template_layouts.removeClass('active').removeProp('checked');

            $this.addClass('active').find('.mesh-template').prop('checked', 'checked');
        },

        /**
         * Add new section(s) to our content based on a Mesh Template
         *
         * @since 1.1
         *
         * @param event
         * @returns {boolean}
         */
        select_template : function(event) {

            event.preventDefault();
            event.stopPropagation();

            var $this = $(this),
                $spinner = $this.siblings('.spinner');

            if ( $this.hasClass('disabled') ) {
                return false;
            }

            $spinner.addClass('is-active');

            $.post( ajaxurl, {
                action: 'mesh_list_templates',
                mesh_post_id: mesh_data.post_id,
                mesh_choose_template_nonce: mesh_data.choose_template_nonce
            }, function( response ){
                if ( response ) {
                    var $response = $( response );

                    $('#mesh-description').html('').append( $response );
                    $spinner.removeClass('is-active');

                } else {
                    $spinner.removeClass('is-active');
                }
            });
        }
    };

} ( jQuery );
;
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
//# sourceMappingURL=admin-mesh.js.map