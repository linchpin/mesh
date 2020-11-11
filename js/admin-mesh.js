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
				.on('change', '.mesh-column-offset', self.display_offset)
				.on('change', 'input.mesh-section-centered', self.display_centered)
				.on('mouseenter', '.the-mover', function() {
					$(this).closest('.block').addClass('ui-hover-state');
				}).on('mouseleave', '.the-mover', function() {
					$(this).closest('.block').removeClass('ui-hover-state');
				})
				.on('change', '.mesh-block-columns.column-width', function( event ) {
					self.change_block_widths( event );
				});

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

			$('.mesh-editor-blocks > .mesh-row[data-section-blocks]').sortable({
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
						column_order.push( $(this).attr('class') );
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
		change_block_widths: function ( event, ui ) {

            var $tgt = $(event.target);

			if ( typeof( ui ) === 'undefined' ) {
				ui = {};
				ui.values = [ parseInt( $tgt.val() ) ];
			}

			var $columns = $tgt.parents('.mesh-section').find('.mesh-editor-blocks').find('.mesh-row:first .columns').addClass('dragging'),
				column_length = $columns.length,
				column_total = parseInt( mesh_data.max_columns ),
				column_values = [],
				slider_values = ui.values,
				post_data = {
					post_id: parseInt( mesh_data.post_id ),
					section_id: parseInt($tgt.closest('.mesh-section').attr('data-mesh-section-id')),
					blocks: {}
				};

			// Set array to store columns widths
			// If returned values are [3, 9]
			// -> col 1 = val1 = 3
			// -> col 2 = (val2 - val1) = (9 - 3) = 6
			// -> col 3 = (avail - val2) = (12 - 9) = 3
			if ( 3 === column_length ) {
				for ( var i = 0; i <= column_length; i++ ) {
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
			if ( 2 === column_length ) {
				column_values.push( slider_values[0] );
				column_values.push( column_total - slider_values[0] );
			}

			if ( 1 === column_length ) {
				column_values.push( $tgt.val() );
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
					$offset_select.append( $('<option></option>').attr('value', i).text(i) );
				}

				if (selected_offset > max_offset) {
					$offset_select.val(0).trigger('change');
				} else {
					$offset_select.val(selected_offset).trigger('change');
				}

				// Reset column width classes and save post data
				$this.addClass('mesh-columns-' + column_value);

				if ( column_value <= 4 ) {
                    $this.addClass('mesh-small-block');
				} else {
					$this.removeClass('mesh-small-block');
				}

				if ( block_id && column_values[index] ) {
					$column_input.val(column_value);
					post_data.blocks[ block_id.toString() ] = column_value;
				}
			});
		},

		/**
		 * Setup Resize Slider
		 */
		setup_resize_slider: function () {

			var column_spacing = [];

			$('.column-slider').addClass('ui-slider-horizontal').each(function () {
				var $this = $(this),
					blocks = parseInt($this.attr('data-mesh-blocks')),
					is_range = ( blocks > 2 ),
					vals = $.parseJSON($this.attr('data-mesh-columns')),
					data = {
						range: is_range,
						min: 0,
						max: parseInt( mesh_data.max_columns ),
						step: 1,
						left: 3,
						right: 9,
						gap: 3,
						create : function() {
							var $handle = $('.ui-slider-handle');

							$handle.find('.inner-border').remove();
                            $handle.append( $('<span class="inner-border" />' ) );
						},
						start: function ( event, ui ) {
							$this.css('z-index', 1000);
							var $tgt     = $(event.target),
                                $columns = $tgt.parents('.mesh-section').find('.mesh-editor-blocks').find('.mesh-row:first .columns');

                            $columns.each( function() {
                                var $btns = $(this).find('.mce-first.mce-btn-group .mce-btn[role="button"]');
                              		$btns.hide();

                              		$($btns[0]).css('visibility', 'hidden').show();
							});
						},
						stop: function (event, ui) {
							$this.css('z-index', '').find('.ui-slider-handle').css('z-index', 1000);
							self.notify_user(event, ui);
						},
						slide: self.change_block_widths,
						change: function( event, ui ) {
							var $tgt     = $(event.target),
								$columns = $tgt.parents('.mesh-section').find('.mesh-editor-blocks').find('.mesh-row:first .columns');
							self.rerender_blocks($columns.find('.wp-editor-area'));
                        }
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
		 * Notify the user on some ui changes
		 *
         * @param event
         * @param ui
         */
		notify_user : function( event, ui ) {
            var $tgt = $(event.target),
            	$columns = $tgt.parents('.mesh-section').find('.mesh-editor-blocks').find('.mesh-row:first .columns').removeClass('dragging');
        },

		/**
		 * Render Block after reorder or change.
		 *
		 * @since 0.3.5
		 *
		 * @param $tinymce_editors
		 */
		rerender_blocks: function ( $tinymce_editors ) {

			$tinymce_editors.each(function () {
				var editor_id = $(this).prop('id'),
                    proto_id = 'content',
					$block = $(this).closest('.mesh-section-block'),
					mce_options = $.extend( true, {}, mesh_data.tinymce_options ), // get our localized options
					column_width,
					qt_options = [];

				column_width = $block.find('.mesh-block-columns').val();

				if ( column_width <= 4 ) {
					$block.addClass('mesh-small-block');
                    mce_options.toolbar1 = mesh_data.tinymce_options.small_toolbar1;
                    mce_options.toolbar2 = mesh_data.tinymce_options.small_toolbar2;
				} else {
                    $block.removeClass('mesh-small-block');
				}

                if ( typeof tinymce !== 'undefined' ) {

					// Reset our editors if we have any
					if (parseInt(tinymce.majorVersion) >= 4) {
						tinymce.execCommand( 'mceRemoveEditor', false, editor_id );
					}

					var $block_content = $(this).closest('.block-content');

					self.create_editor( editor_id, mce_options, $block_content );

					try {
						if ('html' !== mesh.blocks.mode_enabled(this) ) {
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

						if ( proto_id && typeof tinyMCEPreInit.qtInit[proto_id] !== 'undefined' ) {

							qt_options = tinyMCEPreInit.qtInit[proto_id];
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
						if ( editor && ! editor.hidden ) {

							if ( cached_block_content ) {
								editor.setContent(cached_block_content);
								self.delete_block_cache(editor_id);
							}
						} else {
							if( cached_block_content ) {
								editor.val(cached_block_content);
							}
						}
					}
				}
			});

			if (typeof mesh.integrations.yoast !== 'undefined') {
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
				current_image = parseInt( $button.parent().find('.mesh-block-background-input').val() ),
                $parent_container = $button.parents('.mesh-section-background');

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


                current_image = media_attachment.id;

                var $img = $('<img />', {
                    src: media_attachment.url
                });

                $button
                    .html($img)
                    .attr('data-mesh-section-featured-image', parseInt(media_attachment.id))
                    .after($trash);

                $parent_container.addClass('has-background-set');
                // Add selected attachment id to input
                $button.siblings('input[type="hidden"]').val(media_attachment.id);
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
		remove_background : function ( event ) {

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
         * Show input field
         *
         * @param event
         */
		show_field: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this);

			if ($this.parents('.mesh-postbox').hasClass('closed')) {
				return;
			}

			$(this).addClass('title-input-visible');
		},

        /**
         * Hide input field
         *
         * @param event
         */
		hide_field: function (event) {
			event.preventDefault();
			event.stopPropagation();

			$(this).parent().removeClass('title-input-visible');
		},

        /**
         * Toggle Slide click
         *
         * @param event
         */
		slide_toggle_element: function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $this = $(this),
				$toggle = $this.data('toggle');

			$($toggle).slideToggle('fast');
			$this.toggleClass('toggled');
		},

        /**
         *
         * @param event
         */
		display_offset: function (event) {
			var $this  = $(this),
                offset = parseInt( $this.val() ),
				$block = $this.parents('.block-header').next('.block-content');

			$block.removeClass('mesh-has-offset mesh-offset-1 mesh-offset-2 mesh-offset-3 mesh-offset-4 mesh-offset-5 mesh-offset-6 mesh-offset-7 mesh-offset-8 mesh-offset-9');

			if ( offset ) {
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

					$this.append( $swap_clone );
					$swap_parent.append( $tgt_clone );

					self.rerender_blocks( $section.find('.wp-editor-area') );
					self.save_order( section_id, event, ui );
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
		},

        /**
         * Get all the editors within a container/section.
         *
         * @since 1.2
         *
         * @param $container
         * @return {*}
         */
        get_tinymce_editors: function ($container) {
            return $container.find('.wp-editor-area');
        },

        /**
		 * Create an editor within our block.
         *
         * Props to @danielbachuber for a shove in the right direction to have movable editors in the
         * wp-admin
         *
         * https://github.com/alleyinteractive/wordpress-fieldmanager/blob/master/js/richtext.js#L58-L95
         *
		 * @since 1.2.5
         * @param editor_id
         * @param mce_options
         * @param $block_content
         */
		create_editor : function( editor_id, mce_options, $block_content ) {

           	if ( typeof tinyMCEPreInit.mceInit[editor_id] === 'undefined' ) {

                var proto_id = 'content',
                    block_html = $block_content.html();

                // Clean up the proto id which appears in some of the wp_editor generated HTML
                block_html = block_html.replace(new RegExp('id="' + proto_id + '"', 'g'), 'id="' + editor_id + '"');

                $block_content.html(block_html);

                // This needs to be initialized, so we need to get the options from the proto
                if ( proto_id && typeof tinyMCEPreInit.mceInit[proto_id] !== 'undefined') {

                    mce_options = $.extend( true, {}, tinyMCEPreInit.mceInit[proto_id], mce_options );
                    mce_options.body_class = mce_options.body_class.replace(proto_id, editor_id);
                    mce_options.selector = mce_options.selector.replace(proto_id, editor_id);
                }
            } else {
                mce_options = $.extend( true, {}, tinyMCEPreInit.mceInit[editor_id], mce_options );
			}

            tinyMCEPreInit.mceInit[editor_id] = mce_options;
		},

        /**
         * Toggling block centering
         *
         * @since 1.2.5
         * @param event
         */
        display_centered: function ( event ) {

            var $tgt = $(this),
                $section = $tgt.parents('.mesh-section-block'),
                $center_class = 'mesh-block-centered';

            if ( $tgt.is(':checked') ) {
                $section.addClass( $center_class );
            } else {
                $section.removeClass( $center_class );
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
				.on('change', '#mesh-css_mode', self.display_foundation_options)

				// @since 1.2.5
        		.on('change', '#mesh-foundation_version', self.display_foundation_grid_options);

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
            self.display_foundation_grid_options();
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

					blocks.setup_sortable();

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

					if ( 'publish' === $post_status_field.val() ) {
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

			var $button = $(this),
				$parent_container = $button.parents('.mesh-section-background');

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
            $parent_container.removeClass('has-background-set');
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
				current_image = parseInt( $button.parent().find('.mesh-section-background-input').val() ),
				$parent_container = $button.parents('.mesh-section-background');

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

                $parent_container.addClass('has-background-set');

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
				$parent_row = $foundation_version.closest('tr'),
                $foundation_grid_system = $('#mesh-grid_system'),
                $foundation_grid_system_row = $foundation_grid_system.closest('tr');

			if (parseInt(using_foundation) === 1) {
				$parent_row.show();
			} else {
				$parent_row.hide();
                $foundation_grid_system_row.hide();
                $foundation_grid_system.val('');
				$foundation_version.val('');
			}
		},

        /**
		 * Display our grid system options if we are using Foundation 6.4
		 *
		 * @since 1.2.5
		 *
         * @param event
         */
		display_foundation_grid_options: function(event) {
            var using_foundation = $('#mesh-css_mode').find('option:selected').val(),
                $foundation_version = $('#mesh-foundation_version'),
				$foundation_grid_system = $('#mesh-grid_system'),
            	$foundation_grid_system_row = $foundation_grid_system.closest('tr');

            if ( parseInt( using_foundation ) === 1 && 6.4 === parseFloat( $foundation_version.val() ) ) {
                $foundation_grid_system_row.show();
            } else {
                $foundation_grid_system_row.hide();
                $foundation_grid_system.val('');
            }
		}
	};
}(jQuery);

jQuery(function ($) {
	mesh.admin.init();
});
//# sourceMappingURL=admin-mesh.js.map
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiIiwic291cmNlcyI6WyJhZG1pbi1tZXNoLmpzIl0sInNvdXJjZXNDb250ZW50IjpbIi8qIVxuICogTGltaXRTbGlkZXJcbiAqIGh0dHBzOi8vZ2l0aHViLmNvbS92YW5kZXJsZWUvbGltaXRzbGlkZXJcbiAqXG4gKiBDb3B5cmlnaHQgKGMpIDIwMTEtMjAxNSBNYXJ0aWpuIFcuIHZhbiBkZXIgTGVlXG4gKiBMaWNlbnNlZCB1bmRlciB0aGUgTUlULlxuICovXG4vKiBTbGlkZXIgZXh0ZW5zaW9uIHdpdGggZm9yY2VkIGxpbWl0cyBhbmQgZ2Fwcy5cbiAqIE9wdGlvbmFsIHJhbmdlcywgdGl0bGVzIGFuZCBsYWJlbHMuXG4gKi9cblxuOyhmdW5jdGlvbiAoJCwgdW5kZWZpbmVkKSB7XG5cdFwidXNlIHN0cmljdFwiO1xuXG5cdCQud2lkZ2V0KCd2YW5kZXJsZWUubGltaXRzbGlkZXInLCAkLnVpLnNsaWRlciwge1xuXHRcdG9wdGlvbnM6ICQuZXh0ZW5kKHtcblx0XHRcdCdjbGFzc0V2ZW4nOlx0J3VpLXNsaWRlci1oYW5kbGUtZXZlbicsXG5cdFx0XHQnY2xhc3NPZGQnOlx0XHQndWktc2xpZGVyLWhhbmRsZS1vZGQnLFxuXHRcdFx0J2dhcCc6XHRcdFx0dW5kZWZpbmVkLFxuXHRcdFx0J2xlZnQnOlx0XHRcdHVuZGVmaW5lZCxcblx0XHRcdCdyaWdodCc6XHRcdHVuZGVmaW5lZCxcblx0XHRcdCdsaW1pdCc6XHRcdHVuZGVmaW5lZCxcblx0XHRcdCdsaW1pdHMnOlx0XHR1bmRlZmluZWQsXG5cdFx0XHQncmFuZ2VzJzpcdFx0W10sXG5cdFx0XHQndGl0bGUnOlx0XHRmYWxzZSxcblx0XHRcdCdsYWJlbCc6XHRcdGZhbHNlXG5cdFx0fSwgJC51aS5zbGlkZXIucHJvdG90eXBlLm9wdGlvbnMpLFxuXG5cdFx0X2NyZWF0ZTogZnVuY3Rpb24oKSB7XG5cdFx0XHRpZiAoIXRoaXMub3B0aW9ucy52YWx1ZXMpIHtcblx0XHRcdFx0dGhpcy5vcHRpb25zLnZhbHVlcyA9IFt0aGlzLm9wdGlvbnMudmFsdWVdO1xuXHRcdFx0fVxuXG5cdFx0XHQkLnVpLnNsaWRlci5wcm90b3R5cGUuX2NyZWF0ZS5jYWxsKHRoaXMpO1xuXG5cdFx0XHQkKHRoaXMuZWxlbWVudCkuYWRkQ2xhc3MoJ3VpLWxpbWl0c2xpZGVyJyk7XG5cblx0XHRcdHRoaXMuX3JlbmRlclJhbmdlcygpO1xuXHRcdFx0dGhpcy5fcmVuZGVyTGFiZWxzKCk7XG5cdFx0XHR0aGlzLl9yZW5kZXJUaXRsZXMoKTtcblx0XHR9LFxuXG5cdFx0X3JlbmRlclRpdGxlOiBmdW5jdGlvbihpbmRleCkge1xuXHRcdFx0aWYgKHRoaXMub3B0aW9ucy50aXRsZSkge1xuXHRcdFx0XHR2YXIgdmFsdWUgPSB0aGlzLm9wdGlvbnMudmFsdWVzW2luZGV4XTtcblx0XHRcdFx0JCh0aGlzLmhhbmRsZXNbaW5kZXhdKVxuXHRcdFx0XHRcdFx0LmF0dHIoJ3RpdGxlJywgJC5pc0Z1bmN0aW9uKHRoaXMub3B0aW9ucy50aXRsZSkgPyB0aGlzLm9wdGlvbnMudGl0bGUodmFsdWUsIGluZGV4KSA6IHZhbHVlKVxuXHRcdFx0XHRcdFx0LmFkZENsYXNzKHRoaXMub3B0aW9uc1tpbmRleCAlIDIgPyAnY2xhc3NFdmVuJyA6ICdjbGFzc09kZCddKTtcblx0XHRcdH1cblx0XHR9LFxuXG5cdFx0X3JlbmRlclRpdGxlczogZnVuY3Rpb24oaW5kZXgpIHtcblx0XHRcdGlmICh0aGlzLm9wdGlvbnMudGl0bGUpIHtcblx0XHRcdFx0dmFyIHRoYXQgPSB0aGlzO1xuXHRcdFx0XHQkLmVhY2godGhpcy5vcHRpb25zLnZhbHVlcywgZnVuY3Rpb24odikge1xuXHRcdFx0XHRcdHRoYXQuX3JlbmRlclRpdGxlKHYpO1xuXHRcdFx0XHR9KTtcblx0XHRcdH1cblx0XHR9LFxuXG5cdFx0X3JlbmRlckxhYmVsOiBmdW5jdGlvbihpbmRleCkge1xuXHRcdFx0aWYgKHRoaXMub3B0aW9ucy5sYWJlbCkge1xuXHRcdFx0XHR2YXIgdmFsdWUgPSB0aGlzLm9wdGlvbnMudmFsdWVzW2luZGV4XSxcblx0XHRcdFx0XHRodG1sID0gJCgnPGRpdj4nKS5jc3Moe1xuXHRcdFx0XHRcdCd0ZXh0LWFsaWduJzpcdFx0J2NlbnRlcidcblx0XHRcdFx0LFx0J2ZvbnQtc2l6ZSc6XHRcdCc3NSUnXG5cdFx0XHRcdCxcdCdkaXNwbGF5JzpcdFx0XHQndGFibGUtY2VsbCdcblx0XHRcdFx0LFx0J3ZlcnRpY2FsLWFsaWduJzpcdCdtaWRkbGUnXG5cdFx0XHRcdH0pLmh0bWwoJC5pc0Z1bmN0aW9uKHRoaXMub3B0aW9ucy5sYWJlbCkgPyB0aGlzLm9wdGlvbnMubGFiZWwodmFsdWUsIGluZGV4KSA6IHZhbHVlKTtcblxuXHRcdFx0XHQkKHRoaXMuaGFuZGxlc1tpbmRleF0pLmh0bWwoaHRtbCkuY3NzKHtcblx0XHRcdFx0XHQndGV4dC1kZWNvcmF0aW9uJzpcdCdub25lJ1xuXHRcdFx0XHQsXHQnZGlzcGxheSc6XHRcdFx0J3RhYmxlJ1xuXHRcdFx0XHR9KTtcblx0XHRcdH1cblx0XHR9LFxuXG5cdFx0X3JlbmRlckxhYmVsczogZnVuY3Rpb24oKSB7XG5cdFx0XHRpZiAodGhpcy5vcHRpb25zLmxhYmVsKSB7XG5cdFx0XHRcdHZhciB0aGF0ID0gdGhpcztcblx0XHRcdFx0JC5lYWNoKHRoaXMub3B0aW9ucy52YWx1ZXMsIGZ1bmN0aW9uKHYpIHtcblx0XHRcdFx0XHR0aGF0Ll9yZW5kZXJMYWJlbCh2KTtcblx0XHRcdFx0fSk7XG5cdFx0XHR9XG5cdFx0fSxcblxuXHRcdF9yZW5kZXJSYW5nZXM6IGZ1bmN0aW9uKCkge1xuXHRcdFx0dmFyIG9wdGlvbnNcdD0gdGhpcy5vcHRpb25zLFxuXHRcdFx0XHR2YWx1ZXMgID0gb3B0aW9ucy52YWx1ZXMsXG5cdFx0XHRcdHNjYWxlICAgPSBmdW5jdGlvbih2YWx1ZSkge1xuXHRcdFx0XHRcdFx0XHRyZXR1cm4gKHZhbHVlIC0gb3B0aW9ucy5taW4pICogMTAwIC8gKG9wdGlvbnMubWF4IC0gb3B0aW9ucy5taW4pO1xuXHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0aW5kZXgsXG5cdFx0XHRcdGxlZnQsXG5cdFx0XHRcdHJpZ2h0LFxuXHRcdFx0XHRyYW5nZTtcblxuXHRcdFx0JCgnLnVpLXNsaWRlci1yYW5nZScsIHRoaXMuZWxlbWVudCkucmVtb3ZlKCk7XG5cblx0XHRcdGZvciAoaW5kZXggPSAwOyBpbmRleCA8PSB2YWx1ZXMubGVuZ3RoOyArK2luZGV4KSB7XG5cdFx0XHRcdHZhciByYW5nZSA9IG9wdGlvbnMucmFuZ2VzW2luZGV4XSxcblx0XHRcdFx0XHRzbGlkZXJSYW5nZTtcblxuXHRcdFx0XHRpZiAocmFuZ2UpIHtcblx0XHRcdFx0XHRsZWZ0ID0gc2NhbGUoaW5kZXggPT0gMD8gb3B0aW9ucy5taW4gOiB2YWx1ZXNbaW5kZXggLSAxXSk7XG5cdFx0XHRcdFx0cmlnaHQgPSBzY2FsZShpbmRleCA8IHZhbHVlcy5sZW5ndGg/IHZhbHVlc1tpbmRleF0gOiBvcHRpb25zLm1heCk7XG5cblx0XHRcdFx0XHRzbGlkZXJSYW5nZSA9ICQoJzxkaXYvPicpXG5cdFx0XHRcdFx0XHQuYWRkQ2xhc3MoJ3VpLXNsaWRlci1yYW5nZSB1aS13aWRnZXQtaGVhZGVyJylcblx0XHRcdFx0XHRcdC5jc3MoJ3dpZHRoJywgKHJpZ2h0IC0gbGVmdCkgKyAnJScpO1xuXG5cdFx0XHRcdFx0aWYgKHJhbmdlLnN0eWxlQ2xhc3MpIHtcblx0XHRcdFx0XHRcdHNsaWRlclJhbmdlLmFkZENsYXNzKHJhbmdlLnN0eWxlQ2xhc3MpO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdGlmIChsZWZ0ID09IDApIHtcblx0XHRcdFx0XHRcdHNsaWRlclJhbmdlLmFkZENsYXNzKCd1aS1zbGlkZXItcmFuZ2UtbWluJyk7XG5cdFx0XHRcdFx0fSBlbHNlIGlmIChyaWdodCA9PSAxMDApIHtcblx0XHRcdFx0XHRcdHNsaWRlclJhbmdlLmFkZENsYXNzKCd1aS1zbGlkZXItcmFuZ2UtbWF4Jyk7XG5cdFx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHRcdHNsaWRlclJhbmdlLmNzcygnbGVmdCcsIGxlZnQrJyUnKTtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHQkKHRoaXMuZWxlbWVudCkucHJlcGVuZChzbGlkZXJSYW5nZSk7XG4vL1x0XHRcdFx0XHRzbGlkZXJSYW5nZS5wcmVwZW5kVG8odGhpcy5lbGVtZW50KTtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH0sXG5cblx0XHRfc2xpZGU6IGZ1bmN0aW9uKGV2ZW50LCBpbmRleCwgbmV3VmFsKSB7XG5cdFx0XHQvLyBMZWZ0IGxpbWl0XG5cdFx0XHRpZiAodGhpcy5vcHRpb25zLmxlZnQpIHtcblx0XHRcdFx0bmV3VmFsID0gTWF0aC5tYXgobmV3VmFsLCB0aGlzLm9wdGlvbnMubGVmdCk7XG5cdFx0XHR9XG5cblx0XHRcdC8vIFJpZ2h0IGxpbWl0XG5cdFx0XHRpZiAodGhpcy5vcHRpb25zLnJpZ2h0KSB7XG5cdFx0XHRcdG5ld1ZhbCA9IE1hdGgubWluKG5ld1ZhbCwgdGhpcy5vcHRpb25zLnJpZ2h0KTtcblx0XHRcdH1cblxuXHRcdFx0Ly8gTGltaXRcblx0XHRcdGlmICh0aGlzLm9wdGlvbnMubGltaXQpIHtcblx0XHRcdFx0bmV3VmFsID0gTWF0aC5tYXgobmV3VmFsLCB0aGlzLm9wdGlvbnMubGltaXRbMF0pO1xuXHRcdFx0XHRuZXdWYWwgPSBNYXRoLm1pbihuZXdWYWwsIHRoaXMub3B0aW9ucy5saW1pdFsxXSk7XG5cdFx0XHR9XG5cblx0XHRcdC8vIFBlci1zbGlkZXIgbGltaXRcblx0XHRcdGlmICh0aGlzLm9wdGlvbnMubGltaXRzICYmIHRoaXMub3B0aW9ucy5saW1pdHNbaW5kZXhdKSB7XG5cdFx0XHRcdG5ld1ZhbCA9IE1hdGgubWF4KG5ld1ZhbCwgdGhpcy5vcHRpb25zLmxpbWl0c1tpbmRleF1bMF0pO1xuXHRcdFx0XHRuZXdWYWwgPSBNYXRoLm1pbihuZXdWYWwsIHRoaXMub3B0aW9ucy5saW1pdHNbaW5kZXhdWzFdKTtcblx0XHRcdH1cblxuXHRcdFx0aWYgKHRoaXMub3B0aW9ucy5nYXAgfHwgdGhpcy5vcHRpb25zLmdhcCA9PT0gMCkge1xuXHRcdFx0XHQvLyBHYXAgdG8gcHJldmlvdXNcblx0XHRcdFx0aWYgKGluZGV4ID4gMCkge1xuXHRcdFx0XHRcdCBuZXdWYWwgPSBNYXRoLm1heChuZXdWYWwsIHRoaXMub3B0aW9ucy52YWx1ZXNbaW5kZXggLSAxXSArIHRoaXMub3B0aW9ucy5nYXApO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0Ly8gR2FwIHRvIG5leHRcblx0XHRcdFx0aWYgKGluZGV4IDwgdGhpcy5vcHRpb25zLnZhbHVlcy5sZW5ndGggLSAxKSB7XG5cdFx0XHRcdFx0IG5ld1ZhbCA9IE1hdGgubWluKG5ld1ZhbCwgdGhpcy5vcHRpb25zLnZhbHVlc1tpbmRleCArIDFdIC0gdGhpcy5vcHRpb25zLmdhcCk7XG5cdFx0XHRcdH1cblx0XHRcdH1cblxuXHRcdFx0Ly8gQ2FsbCBwYXJlbnRcblx0XHRcdCQudWkuc2xpZGVyLnByb3RvdHlwZS5fc2xpZGUuY2FsbCh0aGlzLCBldmVudCwgaW5kZXgsIG5ld1ZhbCk7XG5cdFx0fSxcblxuXHRcdF9jaGFuZ2U6IGZ1bmN0aW9uKGV2ZW50LCBpbmRleCkge1xuXHRcdFx0Ly8gQ2FsbCBwYXJlbnRcblx0XHRcdCQudWkuc2xpZGVyLnByb3RvdHlwZS5fY2hhbmdlLmNhbGwodGhpcywgZXZlbnQsIGluZGV4KTtcblxuXHRcdFx0Ly8gQXBwbHkgdmlzdWFsc1xuXHRcdFx0dGhpcy5fcmVuZGVyUmFuZ2VzKCk7XG5cdFx0XHR0aGlzLl9yZW5kZXJMYWJlbChpbmRleCk7XG5cdFx0XHR0aGlzLl9yZW5kZXJUaXRsZShpbmRleCk7XG5cdFx0fSxcblxuXHRcdGluc2VydDogZnVuY3Rpb24oaW5kZXgsIHZhbHVlLCByYW5nZSwgbGltaXQpIHtcblx0XHRcdHZhciBtYXggPSB0aGlzLm9wdGlvbnMudmFsdWVzLmxlbmd0aCxcblx0XHRcdFx0cHJldixcblx0XHRcdFx0bmV4dDtcblxuXHRcdFx0aW5kZXggPSAoaW5kZXggPT09IG51bGwgfHwgdHlwZW9mIGluZGV4ID09PSAndW5kZWZpbmVkJylcblx0XHRcdFx0XHQ/IG1heFxuXHRcdFx0XHRcdDogTWF0aC5tYXgoMCwgTWF0aC5taW4oaW5kZXgsIG1heCkpO1xuXG5cdFx0XHRpZiAodHlwZW9mIHZhbHVlID09PSAndW5kZWZpbmVkJykge1xuXHRcdFx0XHRwcmV2ID0gaW5kZXggPD0gMCA/IHRoaXMub3B0aW9ucy5taW4gOiB0aGlzLm9wdGlvbnMudmFsdWVzW2luZGV4IC0gMV0sXG5cdFx0XHRcdG5leHQgPSBpbmRleCA+PSBtYXggPyB0aGlzLm9wdGlvbnMubWF4IDogdGhpcy5vcHRpb25zLnZhbHVlc1tpbmRleF07XG5cdFx0XHRcdHZhbHVlID0gTWF0aC5yb3VuZCgocHJldiArIG5leHQpICogLjUpO1xuXHRcdFx0fVxuXG5cdFx0XHR0aGlzLm9wdGlvbnMudmFsdWVzLnNwbGljZShpbmRleCwgMCwgdmFsdWUpO1xuXHRcdFx0aWYgKHRoaXMub3B0aW9ucy5yYW5nZXMpIHtcblx0XHRcdFx0dGhpcy5vcHRpb25zLnJhbmdlcy5zcGxpY2UoaW5kZXgsIDAsIHJhbmdlIHx8IGZhbHNlKTtcblx0XHRcdH1cblx0XHRcdGlmICh0aGlzLm9wdGlvbnMubGltaXRzKSB7XG5cdFx0XHRcdHRoaXMub3B0aW9ucy5saW1pdHMuc3BsaWNlKGluZGV4LCAwLCByYW5nZSB8fCB1bmRlZmluZWQpO1xuXHRcdFx0fVxuXG5cdFx0XHR0aGlzLl9jcmVhdGUoKTtcblx0XHRcdHRoaXMuZWxlbWVudC50cmlnZ2VyKCdzbGlkZScsIFtpbmRleCwgdmFsdWVdKTtcblxuXHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0fSxcblxuXHRcdHJlbW92ZTogZnVuY3Rpb24oaW5kZXgsIGxlbmd0aCkge1xuXHRcdFx0dmFyIG1heCA9IHRoaXMub3B0aW9ucy52YWx1ZXMubGVuZ3RoIC0gMTtcblx0XHRcdGxlbmd0aCA9IE1hdGgubWF4KDEsIGxlbmd0aCB8fCAxKTtcblxuXHRcdFx0aWYgKG1heCA+IGxlbmd0aCAtIDEpIHtcblx0XHRcdFx0aW5kZXggPSAoaW5kZXggPT09IG51bGwgfHwgdHlwZW9mIGluZGV4ID09PSAndW5kZWZpbmVkJylcblx0XHRcdFx0XHRcdD8gbWF4ICsgMSAtIGxlbmd0aFxuXHRcdFx0XHRcdFx0OiBNYXRoLm1heCgwLCBNYXRoLm1pbihpbmRleCwgbWF4KSk7XG5cblx0XHRcdFx0dGhpcy5vcHRpb25zLnZhbHVlcy5zcGxpY2UoaW5kZXgsIGxlbmd0aCk7XG5cdFx0XHRcdGlmICh0aGlzLm9wdGlvbnMucmFuZ2VzKSB7XG5cdFx0XHRcdFx0dGhpcy5vcHRpb25zLnJhbmdlcy5zcGxpY2UoaW5kZXgsIGxlbmd0aCk7XG5cdFx0XHRcdH1cblx0XHRcdFx0aWYgKHRoaXMub3B0aW9ucy5saW1pdHMpIHtcblx0XHRcdFx0XHR0aGlzLm9wdGlvbnMubGltaXRzLnNwbGljZShpbmRleCwgbGVuZ3RoKTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdHRoaXMuX2NyZWF0ZSgpO1xuXHRcdFx0fVxuXG5cdFx0XHRyZXR1cm4gdGhpcztcblx0XHR9XG5cdH0pO1xufShqUXVlcnkpKTs7XG52YXIgbWVzaCA9IG1lc2ggfHwge307XG5cbm1lc2gucG9pbnRlcnMgPSBmdW5jdGlvbiAoICQgKSB7XG5cbiAgICB2YXIgY3VycmVudF9pbmRleCA9IDA7XG5cbiAgICByZXR1cm4ge1xuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBTaG93IG91ciBjdXJyZW50IHBvaW50ZXIgYmFzZWQgb24gaW5kZXguXG4gICAgICAgICAqL1xuICAgICAgICBzaG93X3BvaW50ZXI6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgICAgICAvLyBNYWtlIHN1cmUgd2UgaGF2ZSBwb2ludGVycyBhdmFpbGFibGUuXG4gICAgICAgICAgICBpZiggdHlwZW9mKCBtZXNoX2RhdGEud3BfcG9pbnRlcnMgKSA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciBwb2ludGVyID0gbWVzaF9kYXRhLndwX3BvaW50ZXJzLnBvaW50ZXJzW2N1cnJlbnRfaW5kZXhdLFxuICAgICAgICAgICAgICAgIG9wdGlvbnMgPSAkLmV4dGVuZCggcG9pbnRlci5vcHRpb25zLCB7XG4gICAgICAgICAgICAgICAgICAgIGNsb3NlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICQucG9zdCggYWpheHVybCwge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBvaW50ZXI6IHBvaW50ZXIucG9pbnRlcl9pZCxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBhY3Rpb246ICdkaXNtaXNzLXdwLXBvaW50ZXInXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgY3VycmVudF9pbmRleCsrO1xuXG5cdFx0XHRcdFx0XHRpZiAoIGN1cnJlbnRfaW5kZXggPCBtZXNoX2RhdGEud3BfcG9pbnRlcnMucG9pbnRlcnMubGVuZ3RoICkge1xuXHQgICAgICAgICAgICAgICAgICAgICAgICBtZXNoLnBvaW50ZXJzLnNob3dfcG9pbnRlcigpO1xuXHRcdFx0XHRcdFx0fVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICQocG9pbnRlci50YXJnZXQpLnBvaW50ZXIoIG9wdGlvbnMgKS5wb2ludGVyKCdvcGVuJyk7XG4gICAgICAgIH1cbiAgICB9O1xuXG59ICggalF1ZXJ5ICk7O1xudmFyIG1lc2ggPSBtZXNoIHx8IHt9O1xuXG5tZXNoLmJsb2NrcyA9IGZ1bmN0aW9uICgkKSB7XG5cblx0dmFyICRib2R5ID0gJCgnYm9keScpLFxuXHRcdC8vIEluc3RhbmNlIG9mIG91ciBibG9jayBjb250cm9sbGVyXG5cdFx0c2VsZixcblx0XHRhZG1pbixcblx0XHRibG9ja19jYWNoZSA9IHt9O1xuXG5cdHJldHVybiB7XG5cblx0XHQvKipcblx0XHQgKiBJbml0aWFsaXplIG91dCBCbG9ja3MgQWRtaW5pc3RyYXRpb25cblx0XHQgKi9cblx0XHRpbml0OiBmdW5jdGlvbiAoKSB7XG5cblx0XHRcdHNlbGYgPSBtZXNoLmJsb2Nrcztcblx0XHRcdGFkbWluID0gbWVzaC5hZG1pbjtcblxuXHRcdFx0aWYgKCdwb3N0JyAhPT0gbWVzaF9kYXRhLnNjcmVlbikge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdCRib2R5XG5cdFx0XHRcdC5vbignY2xpY2snLCAnLm1lc2gtYmxvY2stZmVhdHVyZWQtaW1hZ2UtdHJhc2gnLCBzZWxmLnJlbW92ZV9iYWNrZ3JvdW5kKVxuXHRcdFx0XHQub24oJ2NsaWNrJywgJy5tZXNoLWJsb2NrLWZlYXR1cmVkLWltYWdlLWNob29zZScsIHNlbGYuY2hvb3NlX2JhY2tncm91bmQpXG5cdFx0XHRcdC5vbignY2xpY2suT3Blbk1lZGlhTWFuYWdlcicsICcubWVzaC1ibG9jay1mZWF0dXJlZC1pbWFnZS1jaG9vc2UnLCBzZWxmLmNob29zZV9iYWNrZ3JvdW5kKVxuXHRcdFx0XHQub24oJ2NsaWNrJywgJy5tZXNoLWNsZWFuLWVkaXQ6bm90KC50aXRsZS1pbnB1dC12aXNpYmxlKScsIHNlbGYuc2hvd19maWVsZClcblx0XHRcdFx0Lm9uKCdibHVyJywgJy5tZXNoLWNsZWFuLWVkaXQtZWxlbWVudDpub3Qoc2VsZWN0KScsIHNlbGYuaGlkZV9maWVsZClcblx0XHRcdFx0Lm9uKCdjbGljaycsICcuY2xvc2UtdGl0bGUtZWRpdCcsIHNlbGYuaGlkZV9maWVsZClcblx0XHRcdFx0Lm9uKCdjbGljaycsICcuc2xpZGUtdG9nZ2xlLWVsZW1lbnQnLCBzZWxmLnNsaWRlX3RvZ2dsZV9lbGVtZW50KVxuXHRcdFx0XHQub24oJ2NoYW5nZScsICcubWVzaC1jb2x1bW4tb2Zmc2V0Jywgc2VsZi5kaXNwbGF5X29mZnNldClcblx0XHRcdFx0Lm9uKCdjaGFuZ2UnLCAnaW5wdXQubWVzaC1zZWN0aW9uLWNlbnRlcmVkJywgc2VsZi5kaXNwbGF5X2NlbnRlcmVkKVxuXHRcdFx0XHQub24oJ21vdXNlZW50ZXInLCAnLnRoZS1tb3ZlcicsIGZ1bmN0aW9uKCkge1xuXHRcdFx0XHRcdCQodGhpcykuY2xvc2VzdCgnLmJsb2NrJykuYWRkQ2xhc3MoJ3VpLWhvdmVyLXN0YXRlJyk7XG5cdFx0XHRcdH0pLm9uKCdtb3VzZWxlYXZlJywgJy50aGUtbW92ZXInLCBmdW5jdGlvbigpIHtcblx0XHRcdFx0XHQkKHRoaXMpLmNsb3Nlc3QoJy5ibG9jaycpLnJlbW92ZUNsYXNzKCd1aS1ob3Zlci1zdGF0ZScpO1xuXHRcdFx0XHR9KVxuXHRcdFx0XHQub24oJ2NoYW5nZScsICcubWVzaC1ibG9jay1jb2x1bW5zLmNvbHVtbi13aWR0aCcsIGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHRcdFx0XHRzZWxmLmNoYW5nZV9ibG9ja193aWR0aHMoIGV2ZW50ICk7XG5cdFx0XHRcdH0pO1xuXG5cdFx0XHRzZWxmLnNldHVwX3Jlc2l6ZV9zbGlkZXIoKTtcblx0XHRcdHNlbGYuc2V0dXBfc29ydGFibGUoKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogU2V0dXAgc29ydGluZyBvZiBibG9ja3MgaW4gdGhlIGFkbWluXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS4wLjBcblx0XHQgKi9cblx0XHRzZXR1cF9zb3J0YWJsZTogZnVuY3Rpb24gKCkge1xuXHRcdFx0dmFyIGNvbHVtbl9vcmRlciA9IFtdO1xuXG5cdFx0XHQkKCcubWVzaC1lZGl0b3ItYmxvY2tzID4gLm1lc2gtcm93W2RhdGEtc2VjdGlvbi1ibG9ja3NdJykuc29ydGFibGUoe1xuXHRcdFx0XHQvLyBPUFRJT05TXG5cdFx0XHRcdGF4aXM6ICd4Jyxcblx0XHRcdFx0Y3Vyc29yOiAnbW92ZScsXG5cdFx0XHRcdGN1cnNvckF0OiB7bGVmdDogMH0sXG5cdFx0XHRcdGRpc3RhbmNlOiAyMCxcblx0XHRcdFx0aGFuZGxlOiAnLnRoZS1tb3ZlcicsXG5cdFx0XHRcdGl0ZW1zOiAnLm1lc2gtc2VjdGlvbi1ibG9jaycsXG5cdFx0XHRcdHRvbGVyYW5jZTogJ3BvaW50ZXInLFxuXG5cdFx0XHRcdC8vIEVWRU5UU1xuXHRcdFx0XHRjcmVhdGU6IGZ1bmN0aW9uIChldmVudCwgdWkpIHtcblx0XHRcdFx0XHQkKCcubWVzaC1lZGl0b3ItYmxvY2tzIC5mYWRlLWluLW9uLWNyZWF0ZScpLmZhZGVJbignc2xvdycpO1xuXHRcdFx0XHR9LFxuXG5cdFx0XHRcdHN0YXJ0OiBmdW5jdGlvbiAoZXZlbnQsIHVpKSB7XG5cdFx0XHRcdFx0dmFyICR0Z3QgPSAkKGV2ZW50LnRhcmdldCksXG5cdFx0XHRcdFx0XHQkY29sdW1uX3NsaWRlciA9ICR0Z3QuZmluZCgnLmNvbHVtbi1zbGlkZXInKTtcblxuXHRcdFx0XHRcdC8vIEZhZGUgb3V0IGNvbHVtbiByZXNpemVyIHRvIGF2b2lkIG9kZCBVSVxuXHRcdFx0XHRcdCRjb2x1bW5fc2xpZGVyLmZhZGVPdXQoJ2Zhc3QnKTtcblxuXHRcdFx0XHRcdCQoJy5tZXNoLXNlY3Rpb24tYmxvY2s6bm90KC51aS1zb3J0YWJsZS1wbGFjZWhvbGRlciknLCB0aGlzKS5lYWNoKGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRcdGNvbHVtbl9vcmRlci5wdXNoKCAkKHRoaXMpLmF0dHIoJ2NsYXNzJykgKTtcblx0XHRcdFx0XHR9KTtcblx0XHRcdFx0fSxcblxuXHRcdFx0XHRzdG9wOiBmdW5jdGlvbiAoZXZlbnQsIHVpKSB7XG5cdFx0XHRcdFx0dmFyICR0Z3QgPSAkKGV2ZW50LnRhcmdldCksXG5cdFx0XHRcdFx0XHQkY29sdW1uX3NsaWRlciA9ICR0Z3QuZmluZCgnLmNvbHVtbi1zbGlkZXInKTtcblxuXHRcdFx0XHRcdC8vIEZhZGUgYmFjayBpbiBjb2x1bW4gcmVzaXplclxuXHRcdFx0XHRcdCRjb2x1bW5fc2xpZGVyLmZhZGVJbignc2xvdycpO1xuXHRcdFx0XHR9LFxuXG5cdFx0XHRcdHVwZGF0ZTogZnVuY3Rpb24gKGV2ZW50LCB1aSkge1xuXHRcdFx0XHRcdHZhciAkdGhpcyA9ICQodGhpcyksXG5cdFx0XHRcdFx0XHQkdGd0ID0gJChldmVudC50YXJnZXQpLFxuXHRcdFx0XHRcdFx0JHNlY3Rpb24gPSAkdGd0LnBhcmVudHMoJy5tZXNoLXNlY3Rpb24nKSxcblx0XHRcdFx0XHRcdHNlY3Rpb25faWQgPSAkc2VjdGlvbi5hdHRyKCdkYXRhLW1lc2gtc2VjdGlvbi1pZCcpLFxuXHRcdFx0XHRcdFx0JGJsb2NrcyA9ICR0aGlzLmZpbmQoJy5tZXNoLXNlY3Rpb24tYmxvY2snKTtcblxuXHRcdFx0XHRcdCRibG9ja3MuZWFjaChmdW5jdGlvbiAoaSkge1xuXHRcdFx0XHRcdFx0dmFyICR0aGlzID0gJCh0aGlzKTtcblxuXHRcdFx0XHRcdFx0JHRoaXMucmVtb3ZlQXR0cignY2xhc3MnKS5hZGRDbGFzcyhjb2x1bW5fb3JkZXJbaV0pO1xuXHRcdFx0XHRcdFx0JHRoaXMuZmluZCgnLmJsb2NrLW1lbnUtb3JkZXInKS52YWwoaSk7XG5cdFx0XHRcdFx0fSk7XG5cblx0XHRcdFx0XHRzZWxmLnJlcmVuZGVyX2Jsb2Nrcygkc2VjdGlvbi5maW5kKCcud3AtZWRpdG9yLWFyZWEnKSk7XG5cdFx0XHRcdFx0c2VsZi5zYXZlX29yZGVyKHNlY3Rpb25faWQsIGV2ZW50LCB1aSk7XG5cdFx0XHRcdFx0c2VsZi5zZXR1cF9zb3J0YWJsZSgpO1xuXHRcdFx0XHR9XG5cdFx0XHR9KTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQ2hhbmdlIEJsb2NrIFdpZHRocyBiYXNlZCBvbiBDb2x1bW4gUmVzaXppbmdcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSBldmVudFxuXHRcdCAqIEBwYXJhbSB1aVxuXHRcdCAqIEBzaW5jZSAxLjAuMFxuXHRcdCAqL1xuXHRcdGNoYW5nZV9ibG9ja193aWR0aHM6IGZ1bmN0aW9uICggZXZlbnQsIHVpICkge1xuXG4gICAgICAgICAgICB2YXIgJHRndCA9ICQoZXZlbnQudGFyZ2V0KTtcblxuXHRcdFx0aWYgKCB0eXBlb2YoIHVpICkgPT09ICd1bmRlZmluZWQnICkge1xuXHRcdFx0XHR1aSA9IHt9O1xuXHRcdFx0XHR1aS52YWx1ZXMgPSBbIHBhcnNlSW50KCAkdGd0LnZhbCgpICkgXTtcblx0XHRcdH1cblxuXHRcdFx0dmFyICRjb2x1bW5zID0gJHRndC5wYXJlbnRzKCcubWVzaC1zZWN0aW9uJykuZmluZCgnLm1lc2gtZWRpdG9yLWJsb2NrcycpLmZpbmQoJy5tZXNoLXJvdzpmaXJzdCAuY29sdW1ucycpLmFkZENsYXNzKCdkcmFnZ2luZycpLFxuXHRcdFx0XHRjb2x1bW5fbGVuZ3RoID0gJGNvbHVtbnMubGVuZ3RoLFxuXHRcdFx0XHRjb2x1bW5fdG90YWwgPSBwYXJzZUludCggbWVzaF9kYXRhLm1heF9jb2x1bW5zICksXG5cdFx0XHRcdGNvbHVtbl92YWx1ZXMgPSBbXSxcblx0XHRcdFx0c2xpZGVyX3ZhbHVlcyA9IHVpLnZhbHVlcyxcblx0XHRcdFx0cG9zdF9kYXRhID0ge1xuXHRcdFx0XHRcdHBvc3RfaWQ6IHBhcnNlSW50KCBtZXNoX2RhdGEucG9zdF9pZCApLFxuXHRcdFx0XHRcdHNlY3Rpb25faWQ6IHBhcnNlSW50KCR0Z3QuY2xvc2VzdCgnLm1lc2gtc2VjdGlvbicpLmF0dHIoJ2RhdGEtbWVzaC1zZWN0aW9uLWlkJykpLFxuXHRcdFx0XHRcdGJsb2Nrczoge31cblx0XHRcdFx0fTtcblxuXHRcdFx0Ly8gU2V0IGFycmF5IHRvIHN0b3JlIGNvbHVtbnMgd2lkdGhzXG5cdFx0XHQvLyBJZiByZXR1cm5lZCB2YWx1ZXMgYXJlIFszLCA5XVxuXHRcdFx0Ly8gLT4gY29sIDEgPSB2YWwxID0gM1xuXHRcdFx0Ly8gLT4gY29sIDIgPSAodmFsMiAtIHZhbDEpID0gKDkgLSAzKSA9IDZcblx0XHRcdC8vIC0+IGNvbCAzID0gKGF2YWlsIC0gdmFsMikgPSAoMTIgLSA5KSA9IDNcblx0XHRcdGlmICggMyA9PT0gY29sdW1uX2xlbmd0aCApIHtcblx0XHRcdFx0Zm9yICggdmFyIGkgPSAwOyBpIDw9IGNvbHVtbl9sZW5ndGg7IGkrKyApIHtcblx0XHRcdFx0XHRzd2l0Y2ggKGkpIHtcblx0XHRcdFx0XHRcdGNhc2UgMDpcblx0XHRcdFx0XHRcdFx0Y29sdW1uX3ZhbHVlcy5wdXNoKHNsaWRlcl92YWx1ZXNbaV0pO1xuXHRcdFx0XHRcdFx0XHRicmVhaztcblxuXHRcdFx0XHRcdFx0Y2FzZSAxOlxuXHRcdFx0XHRcdFx0XHRjb2x1bW5fdmFsdWVzLnB1c2goc2xpZGVyX3ZhbHVlc1tpXSAtIHNsaWRlcl92YWx1ZXNbMF0pO1xuXHRcdFx0XHRcdFx0XHRicmVhaztcblxuXHRcdFx0XHRcdFx0Y2FzZSAyOlxuXHRcdFx0XHRcdFx0XHRjb2x1bW5fdmFsdWVzLnB1c2goY29sdW1uX3RvdGFsIC0gc2xpZGVyX3ZhbHVlc1sxXSk7XG5cdFx0XHRcdFx0XHRcdGJyZWFrO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fVxuXHRcdFx0fVxuXG5cdFx0XHQvLyBTZXQgYXJyYXkgdG8gc3RvcmUgY29sdW1ucyB3aWR0aHNcblx0XHRcdC8vIElmIHJldHVybmVkIHZhbHVlIGlzIFs0XVxuXHRcdFx0Ly8gLT4gY29sIDEgPSB2YWwxID0gNFxuXHRcdFx0Ly8gLT4gY29sIDIgPSAoYXZhaWwgLSB2YWwxKSA9ICgxMiAtIDQpID0gOFxuXHRcdFx0aWYgKCAyID09PSBjb2x1bW5fbGVuZ3RoICkge1xuXHRcdFx0XHRjb2x1bW5fdmFsdWVzLnB1c2goIHNsaWRlcl92YWx1ZXNbMF0gKTtcblx0XHRcdFx0Y29sdW1uX3ZhbHVlcy5wdXNoKCBjb2x1bW5fdG90YWwgLSBzbGlkZXJfdmFsdWVzWzBdICk7XG5cdFx0XHR9XG5cblx0XHRcdGlmICggMSA9PT0gY29sdW1uX2xlbmd0aCApIHtcblx0XHRcdFx0Y29sdW1uX3ZhbHVlcy5wdXNoKCAkdGd0LnZhbCgpICk7XG5cdFx0XHR9XG5cblx0XHRcdC8vIEN1c3RvbSBjbGFzcyByZW1vdmFsIGJhc2VkIG9uIHJlZ2V4IHBhdHRlcm5cblx0XHRcdCRjb2x1bW5zLnJlbW92ZUNsYXNzKGZ1bmN0aW9uIChpbmRleCwgY3NzKSB7XG5cdFx0XHRcdHJldHVybiAoY3NzLm1hdGNoKC9cXG1lc2gtY29sdW1ucy1cXGQrL2cpIHx8IFtdKS5qb2luKCcgJyk7XG5cdFx0XHR9KS5lYWNoKGZ1bmN0aW9uIChpbmRleCkge1xuXHRcdFx0XHR2YXIgJHRoaXMgPSAkKHRoaXMpLFxuXHRcdFx0XHRcdGJsb2NrX2lkID0gcGFyc2VJbnQoJHRoaXMuZmluZCgnLmJsb2NrJykuYXR0cignZGF0YS1tZXNoLWJsb2NrLWlkJykpLFxuXHRcdFx0XHRcdCRjb2x1bW5faW5wdXQgPSAkdGhpcy5maW5kKCcuY29sdW1uLXdpZHRoJyksXG5cdFx0XHRcdFx0JG9mZnNldF9zZWxlY3QgPSAkdGhpcy5maW5kKCcubWVzaC1jb2x1bW4tb2Zmc2V0JyksXG5cdFx0XHRcdFx0c2VsZWN0ZWRfb2Zmc2V0ID0gJG9mZnNldF9zZWxlY3QudmFsKCksXG5cdFx0XHRcdFx0Y29sdW1uX3ZhbHVlID0gcGFyc2VJbnQoY29sdW1uX3ZhbHVlc1tpbmRleF0pLFxuXHRcdFx0XHRcdG1heF9vZmZzZXQgPSBjb2x1bW5fdmFsdWUgLSAzO1xuXG5cdFx0XHRcdCRvZmZzZXRfc2VsZWN0LmNoaWxkcmVuKCdvcHRpb24nKS5yZW1vdmUoKTtcblxuXHRcdFx0XHRmb3IgKHZhciBpID0gMDsgaSA8PSBtYXhfb2Zmc2V0OyBpKyspIHtcblx0XHRcdFx0XHQkb2Zmc2V0X3NlbGVjdC5hcHBlbmQoICQoJzxvcHRpb24+PC9vcHRpb24+JykuYXR0cigndmFsdWUnLCBpKS50ZXh0KGkpICk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRpZiAoc2VsZWN0ZWRfb2Zmc2V0ID4gbWF4X29mZnNldCkge1xuXHRcdFx0XHRcdCRvZmZzZXRfc2VsZWN0LnZhbCgwKS50cmlnZ2VyKCdjaGFuZ2UnKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHQkb2Zmc2V0X3NlbGVjdC52YWwoc2VsZWN0ZWRfb2Zmc2V0KS50cmlnZ2VyKCdjaGFuZ2UnKTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdC8vIFJlc2V0IGNvbHVtbiB3aWR0aCBjbGFzc2VzIGFuZCBzYXZlIHBvc3QgZGF0YVxuXHRcdFx0XHQkdGhpcy5hZGRDbGFzcygnbWVzaC1jb2x1bW5zLScgKyBjb2x1bW5fdmFsdWUpO1xuXG5cdFx0XHRcdGlmICggY29sdW1uX3ZhbHVlIDw9IDQgKSB7XG4gICAgICAgICAgICAgICAgICAgICR0aGlzLmFkZENsYXNzKCdtZXNoLXNtYWxsLWJsb2NrJyk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0JHRoaXMucmVtb3ZlQ2xhc3MoJ21lc2gtc21hbGwtYmxvY2snKTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGlmICggYmxvY2tfaWQgJiYgY29sdW1uX3ZhbHVlc1tpbmRleF0gKSB7XG5cdFx0XHRcdFx0JGNvbHVtbl9pbnB1dC52YWwoY29sdW1uX3ZhbHVlKTtcblx0XHRcdFx0XHRwb3N0X2RhdGEuYmxvY2tzWyBibG9ja19pZC50b1N0cmluZygpIF0gPSBjb2x1bW5fdmFsdWU7XG5cdFx0XHRcdH1cblx0XHRcdH0pO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBTZXR1cCBSZXNpemUgU2xpZGVyXG5cdFx0ICovXG5cdFx0c2V0dXBfcmVzaXplX3NsaWRlcjogZnVuY3Rpb24gKCkge1xuXG5cdFx0XHR2YXIgY29sdW1uX3NwYWNpbmcgPSBbXTtcblxuXHRcdFx0JCgnLmNvbHVtbi1zbGlkZXInKS5hZGRDbGFzcygndWktc2xpZGVyLWhvcml6b250YWwnKS5lYWNoKGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0dmFyICR0aGlzID0gJCh0aGlzKSxcblx0XHRcdFx0XHRibG9ja3MgPSBwYXJzZUludCgkdGhpcy5hdHRyKCdkYXRhLW1lc2gtYmxvY2tzJykpLFxuXHRcdFx0XHRcdGlzX3JhbmdlID0gKCBibG9ja3MgPiAyICksXG5cdFx0XHRcdFx0dmFscyA9ICQucGFyc2VKU09OKCR0aGlzLmF0dHIoJ2RhdGEtbWVzaC1jb2x1bW5zJykpLFxuXHRcdFx0XHRcdGRhdGEgPSB7XG5cdFx0XHRcdFx0XHRyYW5nZTogaXNfcmFuZ2UsXG5cdFx0XHRcdFx0XHRtaW46IDAsXG5cdFx0XHRcdFx0XHRtYXg6IHBhcnNlSW50KCBtZXNoX2RhdGEubWF4X2NvbHVtbnMgKSxcblx0XHRcdFx0XHRcdHN0ZXA6IDEsXG5cdFx0XHRcdFx0XHRsZWZ0OiAzLFxuXHRcdFx0XHRcdFx0cmlnaHQ6IDksXG5cdFx0XHRcdFx0XHRnYXA6IDMsXG5cdFx0XHRcdFx0XHRjcmVhdGUgOiBmdW5jdGlvbigpIHtcblx0XHRcdFx0XHRcdFx0dmFyICRoYW5kbGUgPSAkKCcudWktc2xpZGVyLWhhbmRsZScpO1xuXG5cdFx0XHRcdFx0XHRcdCRoYW5kbGUuZmluZCgnLmlubmVyLWJvcmRlcicpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICRoYW5kbGUuYXBwZW5kKCAkKCc8c3BhbiBjbGFzcz1cImlubmVyLWJvcmRlclwiIC8+JyApICk7XG5cdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0c3RhcnQ6IGZ1bmN0aW9uICggZXZlbnQsIHVpICkge1xuXHRcdFx0XHRcdFx0XHQkdGhpcy5jc3MoJ3otaW5kZXgnLCAxMDAwKTtcblx0XHRcdFx0XHRcdFx0dmFyICR0Z3QgICAgID0gJChldmVudC50YXJnZXQpLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAkY29sdW1ucyA9ICR0Z3QucGFyZW50cygnLm1lc2gtc2VjdGlvbicpLmZpbmQoJy5tZXNoLWVkaXRvci1ibG9ja3MnKS5maW5kKCcubWVzaC1yb3c6Zmlyc3QgLmNvbHVtbnMnKTtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICRjb2x1bW5zLmVhY2goIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgJGJ0bnMgPSAkKHRoaXMpLmZpbmQoJy5tY2UtZmlyc3QubWNlLWJ0bi1ncm91cCAubWNlLWJ0bltyb2xlPVwiYnV0dG9uXCJdJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcdFx0JGJ0bnMuaGlkZSgpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICBcdFx0JCgkYnRuc1swXSkuY3NzKCd2aXNpYmlsaXR5JywgJ2hpZGRlbicpLnNob3coKTtcblx0XHRcdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0XHR9LFxuXHRcdFx0XHRcdFx0c3RvcDogZnVuY3Rpb24gKGV2ZW50LCB1aSkge1xuXHRcdFx0XHRcdFx0XHQkdGhpcy5jc3MoJ3otaW5kZXgnLCAnJykuZmluZCgnLnVpLXNsaWRlci1oYW5kbGUnKS5jc3MoJ3otaW5kZXgnLCAxMDAwKTtcblx0XHRcdFx0XHRcdFx0c2VsZi5ub3RpZnlfdXNlcihldmVudCwgdWkpO1xuXHRcdFx0XHRcdFx0fSxcblx0XHRcdFx0XHRcdHNsaWRlOiBzZWxmLmNoYW5nZV9ibG9ja193aWR0aHMsXG5cdFx0XHRcdFx0XHRjaGFuZ2U6IGZ1bmN0aW9uKCBldmVudCwgdWkgKSB7XG5cdFx0XHRcdFx0XHRcdHZhciAkdGd0ICAgICA9ICQoZXZlbnQudGFyZ2V0KSxcblx0XHRcdFx0XHRcdFx0XHQkY29sdW1ucyA9ICR0Z3QucGFyZW50cygnLm1lc2gtc2VjdGlvbicpLmZpbmQoJy5tZXNoLWVkaXRvci1ibG9ja3MnKS5maW5kKCcubWVzaC1yb3c6Zmlyc3QgLmNvbHVtbnMnKTtcblx0XHRcdFx0XHRcdFx0c2VsZi5yZXJlbmRlcl9ibG9ja3MoJGNvbHVtbnMuZmluZCgnLndwLWVkaXRvci1hcmVhJykpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuXHRcdFx0XHRcdH07XG5cblx0XHRcdFx0aWYgKHZhbHMpIHtcblx0XHRcdFx0XHRkYXRhLnZhbHVlID0gdmFsc1swXTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGlmIChibG9ja3MgPT09IDMpIHtcblx0XHRcdFx0XHR2YWxzWzFdID0gdmFsc1swXSArIHZhbHNbMV07IC8vIGFkZCB0aGUgZmlyc3QgMiBjb2x1bW5zIHRvZ2V0aGVyXG5cdFx0XHRcdFx0dmFscy5wb3AoKTtcblx0XHRcdFx0XHRkYXRhLnZhbHVlcyA9IHZhbHM7XG5cdFx0XHRcdFx0ZGF0YS52YWx1ZSA9IG51bGw7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHQkdGhpcy5saW1pdHNsaWRlcihkYXRhKTtcblx0XHRcdH0pO1xuXHRcdH0sXG5cbiAgICAgICAgLyoqXG5cdFx0ICogTm90aWZ5IHRoZSB1c2VyIG9uIHNvbWUgdWkgY2hhbmdlc1xuXHRcdCAqXG4gICAgICAgICAqIEBwYXJhbSBldmVudFxuICAgICAgICAgKiBAcGFyYW0gdWlcbiAgICAgICAgICovXG5cdFx0bm90aWZ5X3VzZXIgOiBmdW5jdGlvbiggZXZlbnQsIHVpICkge1xuICAgICAgICAgICAgdmFyICR0Z3QgPSAkKGV2ZW50LnRhcmdldCksXG4gICAgICAgICAgICBcdCRjb2x1bW5zID0gJHRndC5wYXJlbnRzKCcubWVzaC1zZWN0aW9uJykuZmluZCgnLm1lc2gtZWRpdG9yLWJsb2NrcycpLmZpbmQoJy5tZXNoLXJvdzpmaXJzdCAuY29sdW1ucycpLnJlbW92ZUNsYXNzKCdkcmFnZ2luZycpO1xuICAgICAgICB9LFxuXG5cdFx0LyoqXG5cdFx0ICogUmVuZGVyIEJsb2NrIGFmdGVyIHJlb3JkZXIgb3IgY2hhbmdlLlxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDAuMy41XG5cdFx0ICpcblx0XHQgKiBAcGFyYW0gJHRpbnltY2VfZWRpdG9yc1xuXHRcdCAqL1xuXHRcdHJlcmVuZGVyX2Jsb2NrczogZnVuY3Rpb24gKCAkdGlueW1jZV9lZGl0b3JzICkge1xuXG5cdFx0XHQkdGlueW1jZV9lZGl0b3JzLmVhY2goZnVuY3Rpb24gKCkge1xuXHRcdFx0XHR2YXIgZWRpdG9yX2lkID0gJCh0aGlzKS5wcm9wKCdpZCcpLFxuICAgICAgICAgICAgICAgICAgICBwcm90b19pZCA9ICdjb250ZW50Jyxcblx0XHRcdFx0XHQkYmxvY2sgPSAkKHRoaXMpLmNsb3Nlc3QoJy5tZXNoLXNlY3Rpb24tYmxvY2snKSxcblx0XHRcdFx0XHRtY2Vfb3B0aW9ucyA9ICQuZXh0ZW5kKCB0cnVlLCB7fSwgbWVzaF9kYXRhLnRpbnltY2Vfb3B0aW9ucyApLCAvLyBnZXQgb3VyIGxvY2FsaXplZCBvcHRpb25zXG5cdFx0XHRcdFx0Y29sdW1uX3dpZHRoLFxuXHRcdFx0XHRcdHF0X29wdGlvbnMgPSBbXTtcblxuXHRcdFx0XHRjb2x1bW5fd2lkdGggPSAkYmxvY2suZmluZCgnLm1lc2gtYmxvY2stY29sdW1ucycpLnZhbCgpO1xuXG5cdFx0XHRcdGlmICggY29sdW1uX3dpZHRoIDw9IDQgKSB7XG5cdFx0XHRcdFx0JGJsb2NrLmFkZENsYXNzKCdtZXNoLXNtYWxsLWJsb2NrJyk7XG4gICAgICAgICAgICAgICAgICAgIG1jZV9vcHRpb25zLnRvb2xiYXIxID0gbWVzaF9kYXRhLnRpbnltY2Vfb3B0aW9ucy5zbWFsbF90b29sYmFyMTtcbiAgICAgICAgICAgICAgICAgICAgbWNlX29wdGlvbnMudG9vbGJhcjIgPSBtZXNoX2RhdGEudGlueW1jZV9vcHRpb25zLnNtYWxsX3Rvb2xiYXIyO1xuXHRcdFx0XHR9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAkYmxvY2sucmVtb3ZlQ2xhc3MoJ21lc2gtc21hbGwtYmxvY2snKTtcblx0XHRcdFx0fVxuXG4gICAgICAgICAgICAgICAgaWYgKCB0eXBlb2YgdGlueW1jZSAhPT0gJ3VuZGVmaW5lZCcgKSB7XG5cblx0XHRcdFx0XHQvLyBSZXNldCBvdXIgZWRpdG9ycyBpZiB3ZSBoYXZlIGFueVxuXHRcdFx0XHRcdGlmIChwYXJzZUludCh0aW55bWNlLm1ham9yVmVyc2lvbikgPj0gNCkge1xuXHRcdFx0XHRcdFx0dGlueW1jZS5leGVjQ29tbWFuZCggJ21jZVJlbW92ZUVkaXRvcicsIGZhbHNlLCBlZGl0b3JfaWQgKTtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHR2YXIgJGJsb2NrX2NvbnRlbnQgPSAkKHRoaXMpLmNsb3Nlc3QoJy5ibG9jay1jb250ZW50Jyk7XG5cblx0XHRcdFx0XHRzZWxmLmNyZWF0ZV9lZGl0b3IoIGVkaXRvcl9pZCwgbWNlX29wdGlvbnMsICRibG9ja19jb250ZW50ICk7XG5cblx0XHRcdFx0XHR0cnkge1xuXHRcdFx0XHRcdFx0aWYgKCdodG1sJyAhPT0gbWVzaC5ibG9ja3MubW9kZV9lbmFibGVkKHRoaXMpICkge1xuXHRcdFx0XHRcdFx0XHQkKHRoaXMpLmNsb3Nlc3QoJy53cC1lZGl0b3Itd3JhcCcpLm9uKCdjbGljay53cC1lZGl0b3InLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHRcdFx0aWYgKHRoaXMuaWQpIHtcblx0XHRcdFx0XHRcdFx0XHRcdHdpbmRvdy53cEFjdGl2ZUVkaXRvciA9IHRoaXMuaWQuc2xpY2UoMywgLTUpO1xuXHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0fSBjYXRjaCAoZSkge1xuXHRcdFx0XHRcdFx0Y29uc29sZS5sb2coZSk7XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0dHJ5IHtcblxuXHRcdFx0XHRcdFx0aWYgKCBwcm90b19pZCAmJiB0eXBlb2YgdGlueU1DRVByZUluaXQucXRJbml0W3Byb3RvX2lkXSAhPT0gJ3VuZGVmaW5lZCcgKSB7XG5cblx0XHRcdFx0XHRcdFx0cXRfb3B0aW9ucyA9IHRpbnlNQ0VQcmVJbml0LnF0SW5pdFtwcm90b19pZF07XG5cdFx0XHRcdFx0XHRcdHF0X29wdGlvbnMuaWQgPSBxdF9vcHRpb25zLmlkLnJlcGxhY2UocHJvdG9faWQsIGVkaXRvcl9pZCk7XG5cblx0XHRcdFx0XHRcdFx0dGlueU1DRVByZUluaXQucXRJbml0W2VkaXRvcl9pZF0gPSBxdF9vcHRpb25zO1xuXG5cdFx0XHRcdFx0XHRcdHF0X29wdGlvbnMuYnV0dG9ucyA9ICdzdHJvbmcsZW0sbGluayxibG9jayxpbWcsdWwsb2wsbGknO1xuXG5cdFx0XHRcdFx0XHRcdGlmICh0eXBlb2YgcXVpY2t0YWdzICE9PSAndW5kZWZpbmVkJykge1xuXHRcdFx0XHRcdFx0XHRcdHF1aWNrdGFncyh0aW55TUNFUHJlSW5pdC5xdEluaXRbZWRpdG9yX2lkXSk7XG5cdFx0XHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdFx0XHRpZiAodHlwZW9mIFFUYWdzICE9PSAndW5kZWZpbmVkJykge1xuXHRcdFx0XHRcdFx0XHRcdFFUYWdzLl9idXR0b25zSW5pdCgpO1xuXHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0fSBjYXRjaCAoZSkge1xuXHRcdFx0XHRcdFx0Y29uc29sZS5sb2coZSk7XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0Ly8gQHRvZG8gVGhpcyBpcyBraW5kYSBoYWNreS4gU2VlIGFib3V0IHN3aXRjaGluZyB0aGlzIG91dCBAYXdhcmVcblx0XHRcdFx0XHQkYmxvY2tfY29udGVudC5maW5kKCcuc3dpdGNoLXRtY2UnKS50cmlnZ2VyKCdjbGljaycpO1xuXG5cdFx0XHRcdFx0Lypcblx0XHRcdFx0XHQgKiBDYWNoZVxuXHRcdFx0XHRcdCAqL1xuXHRcdFx0XHRcdGlmICh0eXBlb2YgdGlueW1jZSAhPT0gJ3VuZGVmaW5lZCcpIHtcblxuXHRcdFx0XHRcdFx0dmFyIGVkaXRvciA9IHRpbnltY2UuZ2V0KGVkaXRvcl9pZCksXG5cdFx0XHRcdFx0XHRcdGNhY2hlZF9ibG9ja19jb250ZW50ID0gc2VsZi5nZXRfYmxvY2tfY2FjaGUoZWRpdG9yX2lkKTtcblxuXHRcdFx0XHRcdFx0Ly8gTWFrZSBzdXJlIHdlIGhhdmUgYW4gZWRpdG9yIGFuZCB3ZSBoYXZlIGNhY2hlIGZvciBpdC5cblx0XHRcdFx0XHRcdC8vIE9uY2UgdGhlIGNhY2hlIGlzXG5cdFx0XHRcdFx0XHRpZiAoIGVkaXRvciAmJiAhIGVkaXRvci5oaWRkZW4gKSB7XG5cblx0XHRcdFx0XHRcdFx0aWYgKCBjYWNoZWRfYmxvY2tfY29udGVudCApIHtcblx0XHRcdFx0XHRcdFx0XHRlZGl0b3Iuc2V0Q29udGVudChjYWNoZWRfYmxvY2tfY29udGVudCk7XG5cdFx0XHRcdFx0XHRcdFx0c2VsZi5kZWxldGVfYmxvY2tfY2FjaGUoZWRpdG9yX2lkKTtcblx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHRcdFx0aWYoIGNhY2hlZF9ibG9ja19jb250ZW50ICkge1xuXHRcdFx0XHRcdFx0XHRcdGVkaXRvci52YWwoY2FjaGVkX2Jsb2NrX2NvbnRlbnQpO1xuXHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cdFx0XHR9KTtcblxuXHRcdFx0aWYgKHR5cGVvZiBtZXNoLmludGVncmF0aW9ucy55b2FzdCAhPT0gJ3VuZGVmaW5lZCcpIHtcblx0XHRcdFx0bWVzaC5pbnRlZ3JhdGlvbnMueW9hc3QuYWRkTWVzaFNlY3Rpb25zKCk7XG5cdFx0XHR9XG5cdFx0fSxcblxuXHRcdG1vZGVfZW5hYmxlZDogZnVuY3Rpb24gKGVsKSB7XG5cdFx0XHRyZXR1cm4gJChlbCkuY2xvc2VzdCgnLmh0bWwtYWN0aXZlJykubGVuZ3RoID8gJ2h0bWwnIDogJ3RpbnltY2UnO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBTYXZlIHRoZSBvcmRlciBvZiBvdXIgYmxvY2tzIGFmdGVyIGRyYWcgYW5kIGRyb3AgcmVvcmRlclxuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDAuMS4wXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0gc2VjdGlvbl9pZFxuXHRcdCAqIEBwYXJhbSBldmVudFxuXHRcdCAqIEBwYXJhbSB1aVxuXHRcdCAqL1xuXHRcdHNhdmVfb3JkZXI6IGZ1bmN0aW9uIChzZWN0aW9uX2lkLCBldmVudCwgdWkpIHtcblx0XHRcdHZhciAkcmVvcmRlcl9zcGlubmVyID0gJCgnLm1lc2gtcmVvcmRlci1zcGlubmVyJyksXG5cdFx0XHRcdGJsb2NrX2lkcyA9IFtdO1xuXG5cdFx0XHQkKCcjbWVzaC1zZWN0aW9ucy1lZGl0b3ItJyArIHNlY3Rpb25faWQpLmZpbmQoJy5ibG9jaycpLmVhY2goZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRibG9ja19pZHMucHVzaCgkKHRoaXMpLmF0dHIoJ2RhdGEtbWVzaC1ibG9jay1pZCcpKTtcblx0XHRcdH0pO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBDaG9vc2UgYSBiYWNrZ3JvdW5kIGZvciBvdXIgYmxvY2tcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSBldmVudFxuXHRcdCAqL1xuXHRcdGNob29zZV9iYWNrZ3JvdW5kOiBmdW5jdGlvbiAoZXZlbnQpIHtcblx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuXHRcdFx0dmFyICRidXR0b24gPSAkKHRoaXMpLFxuXHRcdFx0XHQkc2VjdGlvbiA9ICRidXR0b24ucGFyZW50cygnLmJsb2NrJyksXG5cdFx0XHRcdHNlY3Rpb25faWQgPSBwYXJzZUludCgkc2VjdGlvbi5hdHRyKCdkYXRhLW1lc2gtYmxvY2staWQnKSksXG5cdFx0XHRcdGZyYW1lX2lkID0gJ21lc2gtYmFja2dyb3VuZC1zZWxlY3QtJyArIHNlY3Rpb25faWQsXG5cdFx0XHRcdGN1cnJlbnRfaW1hZ2UgPSBwYXJzZUludCggJGJ1dHRvbi5wYXJlbnQoKS5maW5kKCcubWVzaC1ibG9jay1iYWNrZ3JvdW5kLWlucHV0JykudmFsKCkgKSxcbiAgICAgICAgICAgICAgICAkcGFyZW50X2NvbnRhaW5lciA9ICRidXR0b24ucGFyZW50cygnLm1lc2gtc2VjdGlvbi1iYWNrZ3JvdW5kJyk7XG5cblx0XHRcdGFkbWluLm1lZGlhX2ZyYW1lcyA9IGFkbWluLm1lZGlhX2ZyYW1lcyB8fCBbXTtcblxuXHRcdFx0Ly8gSWYgdGhlIGZyYW1lIGFscmVhZHkgZXhpc3RzLCByZS1vcGVuIGl0LlxuXHRcdFx0aWYgKGFkbWluLm1lZGlhX2ZyYW1lc1tmcmFtZV9pZF0pIHtcblx0XHRcdFx0YWRtaW4ubWVkaWFfZnJhbWVzW2ZyYW1lX2lkXS51cGxvYWRlci51cGxvYWRlci5wYXJhbSgnbWVzaF91cGxvYWQnLCAndHJ1ZScpO1xuXHRcdFx0XHRhZG1pbi5tZWRpYV9mcmFtZXNbZnJhbWVfaWRdLm9wZW4oKTtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHQvKipcblx0XHRcdCAqIFRoZSBtZWRpYSBmcmFtZSBkb2Vzbid0IGV4aXN0IGxldCwgc28gbGV0J3MgY3JlYXRlIGl0IHdpdGggc29tZSBvcHRpb25zLlxuXHRcdFx0ICovXG5cdFx0XHRhZG1pbi5tZWRpYV9mcmFtZXNbZnJhbWVfaWRdID0gd3AubWVkaWEuZnJhbWVzLm1lZGlhX2ZyYW1lcyA9IHdwLm1lZGlhKHtcblx0XHRcdFx0Y2xhc3NOYW1lOiAnbWVkaWEtZnJhbWUgbWVzaC1tZWRpYS1mcmFtZScsXG5cdFx0XHRcdGZyYW1lOiAnc2VsZWN0Jyxcblx0XHRcdFx0bXVsdGlwbGU6IGZhbHNlLFxuXHRcdFx0XHR0aXRsZTogbWVzaF9kYXRhLnN0cmluZ3Muc2VsZWN0X2Jsb2NrX2JnLFxuXHRcdFx0XHRidXR0b246IHtcblx0XHRcdFx0XHR0ZXh0OiBtZXNoX2RhdGEuc3RyaW5ncy5zZWxlY3RfYmdcblx0XHRcdFx0fVxuXHRcdFx0fSk7XG5cblx0XHRcdGFkbWluLm1lZGlhX2ZyYW1lc1tmcmFtZV9pZF0ub24oJ29wZW4nLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdC8vIEdyYWIgb3VyIGF0dGFjaG1lbnQgc2VsZWN0aW9uIGFuZCBjb25zdHJ1Y3QgYSBKU09OIHJlcHJlc2VudGF0aW9uIG9mIHRoZSBtb2RlbC5cblx0XHRcdFx0dmFyIHNlbGVjdGlvbiA9IGFkbWluLm1lZGlhX2ZyYW1lc1tmcmFtZV9pZF0uc3RhdGUoKS5nZXQoJ3NlbGVjdGlvbicpO1xuXG5cdFx0XHRcdHNlbGVjdGlvbi5hZGQod3AubWVkaWEuYXR0YWNobWVudChjdXJyZW50X2ltYWdlKSk7XG5cdFx0XHR9KTtcblxuXHRcdFx0YWRtaW4ubWVkaWFfZnJhbWVzW2ZyYW1lX2lkXS5vbignc2VsZWN0JywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHQvLyBHcmFiIG91ciBhdHRhY2htZW50IHNlbGVjdGlvbiBhbmQgY29uc3RydWN0IGEgSlNPTiByZXByZXNlbnRhdGlvbiBvZiB0aGUgbW9kZWwuXG5cdFx0XHRcdHZhciBtZWRpYV9hdHRhY2htZW50ID0gYWRtaW4ubWVkaWFfZnJhbWVzW2ZyYW1lX2lkXS5zdGF0ZSgpLmdldCgnc2VsZWN0aW9uJykuZmlyc3QoKS50b0pTT04oKSxcblx0XHRcdFx0XHQkZWRpdF9pY29uID0gJCgnPHNwYW4gLz4nLCB7XG5cdFx0XHRcdFx0XHQnY2xhc3MnOiAnZGFzaGljb25zIGRhc2hpY29ucy1lZGl0J1xuXHRcdFx0XHRcdH0pLFxuXHRcdFx0XHRcdCR0cmFzaCA9ICQoJzxhLz4nLCB7XG5cdFx0XHRcdFx0XHQnZGF0YS1tZXNoLXNlY3Rpb24tZmVhdHVyZWQtaW1hZ2UnOiAnJyxcblx0XHRcdFx0XHRcdCdocmVmJzogJyMnLFxuXHRcdFx0XHRcdFx0J2NsYXNzJzogJ21lc2gtYmxvY2stZmVhdHVyZWQtaW1hZ2UtdHJhc2ggZGFzaGljb25zLWJlZm9yZSBkYXNoaWNvbnMtZGlzbWlzcydcblx0XHRcdFx0XHR9KTtcblxuXG4gICAgICAgICAgICAgICAgY3VycmVudF9pbWFnZSA9IG1lZGlhX2F0dGFjaG1lbnQuaWQ7XG5cbiAgICAgICAgICAgICAgICB2YXIgJGltZyA9ICQoJzxpbWcgLz4nLCB7XG4gICAgICAgICAgICAgICAgICAgIHNyYzogbWVkaWFfYXR0YWNobWVudC51cmxcbiAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgICRidXR0b25cbiAgICAgICAgICAgICAgICAgICAgLmh0bWwoJGltZylcbiAgICAgICAgICAgICAgICAgICAgLmF0dHIoJ2RhdGEtbWVzaC1zZWN0aW9uLWZlYXR1cmVkLWltYWdlJywgcGFyc2VJbnQobWVkaWFfYXR0YWNobWVudC5pZCkpXG4gICAgICAgICAgICAgICAgICAgIC5hZnRlcigkdHJhc2gpO1xuXG4gICAgICAgICAgICAgICAgJHBhcmVudF9jb250YWluZXIuYWRkQ2xhc3MoJ2hhcy1iYWNrZ3JvdW5kLXNldCcpO1xuICAgICAgICAgICAgICAgIC8vIEFkZCBzZWxlY3RlZCBhdHRhY2htZW50IGlkIHRvIGlucHV0XG4gICAgICAgICAgICAgICAgJGJ1dHRvbi5zaWJsaW5ncygnaW5wdXRbdHlwZT1cImhpZGRlblwiXScpLnZhbChtZWRpYV9hdHRhY2htZW50LmlkKTtcblx0XHRcdH0pO1xuXG5cdFx0XHQvLyBOb3cgdGhhdCBldmVyeXRoaW5nIGhhcyBiZWVuIHNldCwgbGV0J3Mgb3BlbiB1cCB0aGUgZnJhbWUuXG5cdFx0XHRhZG1pbi5tZWRpYV9mcmFtZXNbZnJhbWVfaWRdLm9wZW4oKTtcblx0XHR9LFxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBSZW1vdmUgc2VsZWN0ZWQgYmFja2dyb3VuZCBmcm9tIG91ciBibG9ja1xuICAgICAgICAgKlxuICAgICAgICAgKiBAc2luY2UgMC4zLjZcbiAgICAgICAgICpcbiAgICAgICAgICogQHBhcmFtIGV2ZW50XG5cdFx0ICovXG5cdFx0cmVtb3ZlX2JhY2tncm91bmQgOiBmdW5jdGlvbiAoIGV2ZW50ICkge1xuXG4gICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgZXZlbnQuc3RvcFByb3BhZ2F0aW9uKCk7XG5cbiAgICAgICAgICAgIHZhciAkYnV0dG9uID0gJCh0aGlzKTtcblxuICAgICAgICAgICAgaWYgKCRidXR0b24ucHJldigpLmhhc0NsYXNzKCdyaWdodCcpICYmICEkYnV0dG9uLnByZXYoKS5oYXNDbGFzcygnYnV0dG9uJykpIHtcbiAgICAgICAgICAgICAgICBpZiAoISRidXR0b24ucGFyZW50cygnLmJsb2NrLWJhY2tncm91bmQtY29udGFpbmVyJykpIHtcbiAgICAgICAgICAgICAgICAgICAgJGJ1dHRvbi5wcmV2KCkudG9nZ2xlQ2xhc3MoJ2J1dHRvbiByaWdodCcpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICRidXR0b24ucHJldigpLnRvZ2dsZUNsYXNzKCdyaWdodCcpLmF0dHIoJ2RhdGEtbWVzaC1ibG9jay1mZWF0dXJlZC1pbWFnZScsICcnKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICRidXR0b24uc2libGluZ3MoJ2lucHV0W3R5cGU9XCJoaWRkZW5cIl0nKS52YWwoJycpO1xuXG4gICAgICAgICAgICAkYnV0dG9uLnByZXYoKS50ZXh0KG1lc2hfZGF0YS5zdHJpbmdzLmFkZF9pbWFnZSk7XG4gICAgICAgICAgICAkYnV0dG9uLnJlbW92ZSgpO1xuXHRcdH0sXG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIFNob3cgaW5wdXQgZmllbGRcbiAgICAgICAgICpcbiAgICAgICAgICogQHBhcmFtIGV2ZW50XG4gICAgICAgICAqL1xuXHRcdHNob3dfZmllbGQ6IGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG5cdFx0XHR2YXIgJHRoaXMgPSAkKHRoaXMpO1xuXG5cdFx0XHRpZiAoJHRoaXMucGFyZW50cygnLm1lc2gtcG9zdGJveCcpLmhhc0NsYXNzKCdjbG9zZWQnKSkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdCQodGhpcykuYWRkQ2xhc3MoJ3RpdGxlLWlucHV0LXZpc2libGUnKTtcblx0XHR9LFxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBIaWRlIGlucHV0IGZpZWxkXG4gICAgICAgICAqXG4gICAgICAgICAqIEBwYXJhbSBldmVudFxuICAgICAgICAgKi9cblx0XHRoaWRlX2ZpZWxkOiBmdW5jdGlvbiAoZXZlbnQpIHtcblx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuXHRcdFx0JCh0aGlzKS5wYXJlbnQoKS5yZW1vdmVDbGFzcygndGl0bGUtaW5wdXQtdmlzaWJsZScpO1xuXHRcdH0sXG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIFRvZ2dsZSBTbGlkZSBjbGlja1xuICAgICAgICAgKlxuICAgICAgICAgKiBAcGFyYW0gZXZlbnRcbiAgICAgICAgICovXG5cdFx0c2xpZGVfdG9nZ2xlX2VsZW1lbnQ6IGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG5cdFx0XHR2YXIgJHRoaXMgPSAkKHRoaXMpLFxuXHRcdFx0XHQkdG9nZ2xlID0gJHRoaXMuZGF0YSgndG9nZ2xlJyk7XG5cblx0XHRcdCQoJHRvZ2dsZSkuc2xpZGVUb2dnbGUoJ2Zhc3QnKTtcblx0XHRcdCR0aGlzLnRvZ2dsZUNsYXNzKCd0b2dnbGVkJyk7XG5cdFx0fSxcblxuICAgICAgICAvKipcbiAgICAgICAgICpcbiAgICAgICAgICogQHBhcmFtIGV2ZW50XG4gICAgICAgICAqL1xuXHRcdGRpc3BsYXlfb2Zmc2V0OiBmdW5jdGlvbiAoZXZlbnQpIHtcblx0XHRcdHZhciAkdGhpcyAgPSAkKHRoaXMpLFxuICAgICAgICAgICAgICAgIG9mZnNldCA9IHBhcnNlSW50KCAkdGhpcy52YWwoKSApLFxuXHRcdFx0XHQkYmxvY2sgPSAkdGhpcy5wYXJlbnRzKCcuYmxvY2staGVhZGVyJykubmV4dCgnLmJsb2NrLWNvbnRlbnQnKTtcblxuXHRcdFx0JGJsb2NrLnJlbW92ZUNsYXNzKCdtZXNoLWhhcy1vZmZzZXQgbWVzaC1vZmZzZXQtMSBtZXNoLW9mZnNldC0yIG1lc2gtb2Zmc2V0LTMgbWVzaC1vZmZzZXQtNCBtZXNoLW9mZnNldC01IG1lc2gtb2Zmc2V0LTYgbWVzaC1vZmZzZXQtNyBtZXNoLW9mZnNldC04IG1lc2gtb2Zmc2V0LTknKTtcblxuXHRcdFx0aWYgKCBvZmZzZXQgKSB7XG5cdFx0XHRcdCRibG9jay5hZGRDbGFzcygnbWVzaC1oYXMtb2Zmc2V0IG1lc2gtb2Zmc2V0LScgKyBvZmZzZXQpO1xuXHRcdFx0fVxuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBTZXR1cCBCbG9jayBEcmFnIGFuZCBEcm9wXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMC4zLjBcblx0XHQgKiBAZGVwcmVjYXRlZCAtIEtlZXAgZm9yIGZhbGxiYWNrIGlmIHNvcnRhYmxlIGRvZXNuJ3Qgd29yayBvdXQuXG5cdFx0ICovXG5cdFx0c2V0dXBfZHJhZ19kcm9wOiBmdW5jdGlvbiAoKSB7XG5cblx0XHRcdCQoXCIubWVzaC1lZGl0b3ItYmxvY2tzIC5ibG9ja1wiKS5kcmFnZ2FibGUoe1xuXHRcdFx0XHQnYXBwZW5kVG8nOiAnYm9keScsXG5cdFx0XHRcdGhlbHBlcjogZnVuY3Rpb24gKGV2ZW50KSB7XG5cblx0XHRcdFx0XHR2YXIgJHRoaXMgPSAkKHRoaXMpLFxuXHRcdFx0XHRcdFx0X3dpZHRoID0gJHRoaXMud2lkdGgoKTtcblx0XHRcdFx0XHQkY2xvbmUgPSAkdGhpcy5jbG9uZSgpLndpZHRoKF93aWR0aCkuY3NzKCdiYWNrZ3JvdW5kJywgJyNmZmYnKTtcblx0XHRcdFx0XHQkY2xvbmUuZmluZCgnKicpLnJlbW92ZUF0dHIoJ2lkJyk7XG5cblx0XHRcdFx0XHRyZXR1cm4gJGNsb25lO1xuXHRcdFx0XHR9LFxuXHRcdFx0XHRyZXZlcnQ6IHRydWUsXG5cdFx0XHRcdHpJbmRleDogMTAwMCxcblx0XHRcdFx0aGFuZGxlOiAnLnRoZS1tb3ZlcicsXG5cdFx0XHRcdGlmcmFtZUZpeDogdHJ1ZSxcblx0XHRcdFx0c3RhcnQ6IGZ1bmN0aW9uICh1aSwgZXZlbnQsIGhlbHBlcikge1xuXHRcdFx0XHR9XG5cdFx0XHR9KTtcblxuXHRcdFx0JChcIi5ibG9ja1wiKVxuXHRcdFx0XHQuYWRkQ2xhc3MoXCJ1aS13aWRnZXQgdWktd2lkZ2V0LWNvbnRlbnQgdWktaGVscGVyLWNsZWFyZml4XCIpXG5cdFx0XHRcdC5maW5kKFwiLmJsb2NrLWhlYWRlclwiKVxuXHRcdFx0XHQuYWRkQ2xhc3MoXCJobmRsZSB1aS1zb3J0YWJsZS1oYW5kbGVcIilcblx0XHRcdFx0LnByZXBlbmQoXCI8c3BhbiBjbGFzcz0nYmxvY2stdG9nZ2xlJyAvPlwiKTtcblxuXHRcdFx0JChcIi5kcm9wLXRhcmdldFwiKS5kcm9wcGFibGUoe1xuXHRcdFx0XHRhY2NlcHQ6IFwiLmJsb2NrOm5vdCgudWktc29ydGFibGUtaGVscGVyKVwiLFxuXHRcdFx0XHRhY3RpdmVDbGFzczogXCJ1aS1zdGF0ZS1ob3ZlclwiLFxuXHRcdFx0XHRob3ZlckNsYXNzOiBcInVpLXN0YXRlLWFjdGl2ZVwiLFxuXHRcdFx0XHRoYW5kbGU6IFwiLmJsb2NrLWhlYWRlclwiLFxuXHRcdFx0XHRyZXZlcnQ6IHRydWUsXG5cdFx0XHRcdGRyb3A6IGZ1bmN0aW9uIChldmVudCwgdWkpIHtcblxuXHRcdFx0XHRcdHZhciAkdGhpcyA9ICQodGhpcyksXG5cdFx0XHRcdFx0XHQkc3dhcF9jbG9uZSA9IHVpLmRyYWdnYWJsZSxcblx0XHRcdFx0XHRcdCRzd2FwX3BhcmVudCA9IHVpLmRyYWdnYWJsZS5wYXJlbnQoKSxcblx0XHRcdFx0XHRcdCR0Z3QgPSAkKGV2ZW50LnRhcmdldCksXG5cdFx0XHRcdFx0XHQkdGd0X2Nsb25lID0gJHRndC5maW5kKCcuYmxvY2snKSxcblx0XHRcdFx0XHRcdCRzZWN0aW9uID0gJHRndC5wYXJlbnRzKCcubWVzaC1zZWN0aW9uJyksXG5cdFx0XHRcdFx0XHRzZWN0aW9uX2lkID0gJHNlY3Rpb24uYXR0cignZGF0YS1tZXNoLXNlY3Rpb24taWQnKTtcblxuXHRcdFx0XHRcdCRzd2FwX2Nsb25lLmNzcyh7J3RvcCc6ICcnLCAnbGVmdCc6ICcnfSk7XG5cblx0XHRcdFx0XHQkdGhpcy5hcHBlbmQoICRzd2FwX2Nsb25lICk7XG5cdFx0XHRcdFx0JHN3YXBfcGFyZW50LmFwcGVuZCggJHRndF9jbG9uZSApO1xuXG5cdFx0XHRcdFx0c2VsZi5yZXJlbmRlcl9ibG9ja3MoICRzZWN0aW9uLmZpbmQoJy53cC1lZGl0b3ItYXJlYScpICk7XG5cdFx0XHRcdFx0c2VsZi5zYXZlX29yZGVyKCBzZWN0aW9uX2lkLCBldmVudCwgdWkgKTtcblx0XHRcdFx0XHRzZWxmLnNldHVwX2RyYWdfZHJvcCgpO1xuXG5cdFx0XHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdFx0XHR9XG5cdFx0XHR9KTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogU2F2ZSBibG9jayBjYWNoZWQgY29udGVudCB3aGVuIGNoYW5nZXMgYXJlIG1hZGUgd2l0aGluIGEgYmxvY2suXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS4yXG5cdFx0ICogQHBhcmFtIGJsb2NrX2lkXG5cdFx0ICogQHBhcmFtIGNhY2hlX2NvbnRlbnRcblx0XHQgKiBAcmV0dXJuIGJvb2xlYW5cblx0XHQgKi9cblx0XHRzZXRfYmxvY2tfY2FjaGU6IGZ1bmN0aW9uIChibG9ja19pZCwgY2FjaGVfY29udGVudCkge1xuXG5cdFx0XHRpZiAoIWJsb2NrX2lkIHx8ICFjYWNoZV9jb250ZW50KSB7XG5cdFx0XHRcdHJldHVybiBmYWxzZTtcblx0XHRcdH1cblxuXHRcdFx0YmxvY2tfY2FjaGVbYmxvY2tfaWRdID0gY2FjaGVfY29udGVudDtcblxuXHRcdFx0cmV0dXJuIHRydWU7XG5cdFx0fSxcblxuICAgICAgICAvKipcbiAgICAgICAgICogR2V0IGJsb2NrIGNhY2hlZCBjb250ZW50XG4gICAgICAgICAqXG4gICAgICAgICAqIEBzaW5jZSAxLjJcbiAgICAgICAgICogQHBhcmFtIGJsb2NrX2lkXG4gICAgICAgICAqIEByZXR1cm4gc3RyaW5nXG4gICAgICAgICAqL1xuICAgICAgICBnZXRfYmxvY2tfY2FjaGU6IGZ1bmN0aW9uIChibG9ja19pZCkge1xuXG4gICAgICAgICAgICBpZiAoYmxvY2tfY2FjaGVbYmxvY2tfaWRdKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGJsb2NrX2NhY2hlW2Jsb2NrX2lkXTsgLy8gZ2V0IHRoZSBibG9jayBJRCBmcm9tIHRoZSBsb2NhbCBjYWNoZS5cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgcmV0dXJuICcnOyAvLyBjYWNoZWQgY29udGVudCBmb3IgdGhlIGJsb2NrLlxuICAgICAgICB9LFxuXG5cdFx0LyoqXG5cdFx0ICogRGVsZXRlIHNwZWNpZmljIGJsb2NrIGNhY2hlZCBjb250ZW50XG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS4yXG5cdFx0ICogQHBhcmFtIGJsb2NrX2lkXG5cdFx0ICogQHJldHVybiBzdHJpbmdcblx0XHQgKi9cblx0XHRkZWxldGVfYmxvY2tfY2FjaGU6IGZ1bmN0aW9uIChibG9ja19pZCkge1xuXHRcdFx0aWYgKGJsb2NrX2NhY2hlW2Jsb2NrX2lkXSkge1xuXHRcdFx0XHRkZWxldGUgYmxvY2tfY2FjaGVbYmxvY2tfaWRdO1xuXHRcdFx0fVxuXHRcdH0sXG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIEdldCBhbGwgdGhlIGVkaXRvcnMgd2l0aGluIGEgY29udGFpbmVyL3NlY3Rpb24uXG4gICAgICAgICAqXG4gICAgICAgICAqIEBzaW5jZSAxLjJcbiAgICAgICAgICpcbiAgICAgICAgICogQHBhcmFtICRjb250YWluZXJcbiAgICAgICAgICogQHJldHVybiB7Kn1cbiAgICAgICAgICovXG4gICAgICAgIGdldF90aW55bWNlX2VkaXRvcnM6IGZ1bmN0aW9uICgkY29udGFpbmVyKSB7XG4gICAgICAgICAgICByZXR1cm4gJGNvbnRhaW5lci5maW5kKCcud3AtZWRpdG9yLWFyZWEnKTtcbiAgICAgICAgfSxcblxuICAgICAgICAvKipcblx0XHQgKiBDcmVhdGUgYW4gZWRpdG9yIHdpdGhpbiBvdXIgYmxvY2suXG4gICAgICAgICAqXG4gICAgICAgICAqIFByb3BzIHRvIEBkYW5pZWxiYWNodWJlciBmb3IgYSBzaG92ZSBpbiB0aGUgcmlnaHQgZGlyZWN0aW9uIHRvIGhhdmUgbW92YWJsZSBlZGl0b3JzIGluIHRoZVxuICAgICAgICAgKiB3cC1hZG1pblxuICAgICAgICAgKlxuICAgICAgICAgKiBodHRwczovL2dpdGh1Yi5jb20vYWxsZXlpbnRlcmFjdGl2ZS93b3JkcHJlc3MtZmllbGRtYW5hZ2VyL2Jsb2IvbWFzdGVyL2pzL3JpY2h0ZXh0LmpzI0w1OC1MOTVcbiAgICAgICAgICpcblx0XHQgKiBAc2luY2UgMS4yLjVcbiAgICAgICAgICogQHBhcmFtIGVkaXRvcl9pZFxuICAgICAgICAgKiBAcGFyYW0gbWNlX29wdGlvbnNcbiAgICAgICAgICogQHBhcmFtICRibG9ja19jb250ZW50XG4gICAgICAgICAqL1xuXHRcdGNyZWF0ZV9lZGl0b3IgOiBmdW5jdGlvbiggZWRpdG9yX2lkLCBtY2Vfb3B0aW9ucywgJGJsb2NrX2NvbnRlbnQgKSB7XG5cbiAgICAgICAgICAgXHRpZiAoIHR5cGVvZiB0aW55TUNFUHJlSW5pdC5tY2VJbml0W2VkaXRvcl9pZF0gPT09ICd1bmRlZmluZWQnICkge1xuXG4gICAgICAgICAgICAgICAgdmFyIHByb3RvX2lkID0gJ2NvbnRlbnQnLFxuICAgICAgICAgICAgICAgICAgICBibG9ja19odG1sID0gJGJsb2NrX2NvbnRlbnQuaHRtbCgpO1xuXG4gICAgICAgICAgICAgICAgLy8gQ2xlYW4gdXAgdGhlIHByb3RvIGlkIHdoaWNoIGFwcGVhcnMgaW4gc29tZSBvZiB0aGUgd3BfZWRpdG9yIGdlbmVyYXRlZCBIVE1MXG4gICAgICAgICAgICAgICAgYmxvY2tfaHRtbCA9IGJsb2NrX2h0bWwucmVwbGFjZShuZXcgUmVnRXhwKCdpZD1cIicgKyBwcm90b19pZCArICdcIicsICdnJyksICdpZD1cIicgKyBlZGl0b3JfaWQgKyAnXCInKTtcblxuICAgICAgICAgICAgICAgICRibG9ja19jb250ZW50Lmh0bWwoYmxvY2tfaHRtbCk7XG5cbiAgICAgICAgICAgICAgICAvLyBUaGlzIG5lZWRzIHRvIGJlIGluaXRpYWxpemVkLCBzbyB3ZSBuZWVkIHRvIGdldCB0aGUgb3B0aW9ucyBmcm9tIHRoZSBwcm90b1xuICAgICAgICAgICAgICAgIGlmICggcHJvdG9faWQgJiYgdHlwZW9mIHRpbnlNQ0VQcmVJbml0Lm1jZUluaXRbcHJvdG9faWRdICE9PSAndW5kZWZpbmVkJykge1xuXG4gICAgICAgICAgICAgICAgICAgIG1jZV9vcHRpb25zID0gJC5leHRlbmQoIHRydWUsIHt9LCB0aW55TUNFUHJlSW5pdC5tY2VJbml0W3Byb3RvX2lkXSwgbWNlX29wdGlvbnMgKTtcbiAgICAgICAgICAgICAgICAgICAgbWNlX29wdGlvbnMuYm9keV9jbGFzcyA9IG1jZV9vcHRpb25zLmJvZHlfY2xhc3MucmVwbGFjZShwcm90b19pZCwgZWRpdG9yX2lkKTtcbiAgICAgICAgICAgICAgICAgICAgbWNlX29wdGlvbnMuc2VsZWN0b3IgPSBtY2Vfb3B0aW9ucy5zZWxlY3Rvci5yZXBsYWNlKHByb3RvX2lkLCBlZGl0b3JfaWQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgbWNlX29wdGlvbnMgPSAkLmV4dGVuZCggdHJ1ZSwge30sIHRpbnlNQ0VQcmVJbml0Lm1jZUluaXRbZWRpdG9yX2lkXSwgbWNlX29wdGlvbnMgKTtcblx0XHRcdH1cblxuICAgICAgICAgICAgdGlueU1DRVByZUluaXQubWNlSW5pdFtlZGl0b3JfaWRdID0gbWNlX29wdGlvbnM7XG5cdFx0fSxcblxuICAgICAgICAvKipcbiAgICAgICAgICogVG9nZ2xpbmcgYmxvY2sgY2VudGVyaW5nXG4gICAgICAgICAqXG4gICAgICAgICAqIEBzaW5jZSAxLjIuNVxuICAgICAgICAgKiBAcGFyYW0gZXZlbnRcbiAgICAgICAgICovXG4gICAgICAgIGRpc3BsYXlfY2VudGVyZWQ6IGZ1bmN0aW9uICggZXZlbnQgKSB7XG5cbiAgICAgICAgICAgIHZhciAkdGd0ID0gJCh0aGlzKSxcbiAgICAgICAgICAgICAgICAkc2VjdGlvbiA9ICR0Z3QucGFyZW50cygnLm1lc2gtc2VjdGlvbi1ibG9jaycpLFxuICAgICAgICAgICAgICAgICRjZW50ZXJfY2xhc3MgPSAnbWVzaC1ibG9jay1jZW50ZXJlZCc7XG5cbiAgICAgICAgICAgIGlmICggJHRndC5pcygnOmNoZWNrZWQnKSApIHtcbiAgICAgICAgICAgICAgICAkc2VjdGlvbi5hZGRDbGFzcyggJGNlbnRlcl9jbGFzcyApO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAkc2VjdGlvbi5yZW1vdmVDbGFzcyggJGNlbnRlcl9jbGFzcyApO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cdH07XG5cbn0oalF1ZXJ5KTtcbjtcbnZhciBtZXNoID0gbWVzaCB8fCB7fTtcblxubWVzaC50ZW1wbGF0ZXMgPSBmdW5jdGlvbiAoICQgKSB7XG5cbiAgICB2YXIgJGJvZHkgPSAkKCdib2R5JyksXG4gICAgICAgIC8vIEluc3RhbmNlIG9mIG91ciB0ZW1wbGF0ZSBjb250cm9sbGVyXG4gICAgICAgIHNlbGYsXG4gICAgICAgIGJsb2NrcyxcbiAgICAgICAgJHdlbGNvbWVQYW5lbCA9ICQoICcjbWVzaC10ZW1wbGF0ZS13ZWxjb21lLXBhbmVsJyApO1xuXG4gICAgcmV0dXJuIHtcblxuICAgICAgICAvKipcbiAgICAgICAgICogSW5pdGlhbGl6ZSBvdXIgVGVtcGxhdGUgQWRtaW5pc3RyYXRpb25cbiAgICAgICAgICovXG4gICAgICAgIGluaXQgOiBmdW5jdGlvbigpIHtcblxuICAgICAgICAgICAgc2VsZiAgID0gbWVzaC50ZW1wbGF0ZXM7XG5cbiAgICAgICAgICAgICR3ZWxjb21lUGFuZWwuZmluZCggJy5tZXNoLXRlbXBsYXRlLXdlbGNvbWUtcGFuZWwtY2xvc2UnICkub24oICdjbGljaycsIGZ1bmN0aW9uKCBldmVudCApIHtcbiAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgICAgICAgICAgICAgJHdlbGNvbWVQYW5lbC5hZGRDbGFzcygnaGlkZGVuJyk7XG5cbiAgICAgICAgICAgICAgICBzZWxmLnVwZGF0ZVdlbGNvbWVQYW5lbCggMCApO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIGlmICggJ3Bvc3QnICE9PSBtZXNoX2RhdGEuc2NyZWVuICkge1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgYmxvY2tzID0gbWVzaC5ibG9ja3M7XG5cbiAgICAgICAgICAgICRib2R5XG4gICAgICAgICAgICAgICAgLm9uKCdjbGljaycsICcubWVzaC1zZWxlY3QtdGVtcGxhdGUnLCAgICAgIHNlbGYuc2VsZWN0X3RlbXBsYXRlIClcbiAgICAgICAgICAgICAgICAub24oJ2NsaWNrJywgJy5tZXNoLXRlbXBsYXRlLWxheW91dCcsICAgICAgc2VsZi5zZWxlY3RfbGF5b3V0IClcbiAgICAgICAgICAgICAgICAub24oJ2NsaWNrJywgJy5tZXNoLXRlbXBsYXRlLXN0YXJ0JywgICAgICAgc2VsZi5kaXNwbGF5X3RlbXBsYXRlX3R5cGVzIClcbiAgICAgICAgICAgICAgIC8vICAub24oJ2NsaWNrJywgJy5tZXNoLXRlbXBsYXRlLXR5cGUnLCAgICAgICAgc2VsZi5zZWxlY3RfdGVtcGxhdGVfdHlwZSApXG4gICAgICAgICAgICAgICAgLm9uKCdjbGljaycsICcubWVzaC10ZW1wbGF0ZS1jaGFuZ2UtdHlwZScsIHNlbGYuY2hhbmdlX3RlbXBsYXRlX3R5cGUgKVxuICAgICAgICAgICAgICAgIC5vbignY2xpY2snLCAnLm1lc2gtdGVtcGxhdGUtcmVtb3ZlJywgICAgICBzZWxmLnJlbW92ZV90ZW1wbGF0ZSApO1xuXG4gICAgICAgICAgICAgICAgLy8gLm9uKCdjbGljaycsICcubWVzaF90ZW1wbGF0ZSAubWVzaC1zZWN0aW9uLXVwZGF0ZSwgLm1lc2hfdGVtcGxhdGUgLm1lc2gtc2VjdGlvbi1wdWJsaXNoJywgc2VsZi53YXJuX29uX3NhdmUgKTtcbiAgICAgICAgfSxcblxuICAgICAgICAvKipcbiAgICAgICAgICogV2FybiB0aGUgdXNlciB0aGF0IHRoZXkgd2lsbCB0aGVpciBjaGFuZ2VzIHdpbGxcbiAgICAgICAgICogYmUgYXBwbGllZCB0byBvdGhlciB0ZW1wbGF0ZXMgb24gdXBkYXRlL3B1Ymxpc2hcbiAgICAgICAgICpcbiAgICAgICAgICogQHRvZG8gMS4yXG4gICAgICAgICAqXG4gICAgICAgICAqIEBwYXJhbSBldmVudFxuICAgICAgICAgKi9cbiAgICAgICAgd2Fybl9vbl9zYXZlIDogZnVuY3Rpb24oIGV2ZW50ICkge1xuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG4gICAgICAgICAgICB2YXIgY29uZmlybWF0aW9uID0gY29uZmlybSggbWVzaF9kYXRhLnN0cmluZ3MuY29uZmlybV90ZW1wbGF0ZV9zZWN0aW9uX3VwZGF0ZSApO1xuXG4gICAgICAgICAgICBpZiAoIHRydWUgIT09IGNvbmZpcm1hdGlvbiApIHtcbiAgICAgICAgICAgICAgICBzZWxmLmFwcGx5VGVtcGxhdGVDaGFuZ2VzKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0sXG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIFdoZW4gd2UgdXBkYXRlIGEgdGVtcGxhdGUncyBzZWN0aW9uKHMpIHVwZGF0ZSBhbGwgc2VjdGlvbnNcbiAgICAgICAgICogb2YgZWFjaCBwb3N0cyB0aGF0IHVzZSB0aGlzIHRlbXBsYXRlcyBzZWN0aW9ucy5cbiAgICAgICAgICpcbiAgICAgICAgICogQHRvZG8gMS4yXG4gICAgICAgICAqL1xuICAgICAgICBhcHBseVRlbXBsYXRlQ2hhbmdlcyA6IGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgJC5wb3N0KCBhamF4dXJsLCB7XG4gICAgICAgICAgICAgICAgYWN0aW9uOiAnbWVzaF9hcHBseV90ZW1wbGF0ZV9jaGFuZ2VzJyxcbiAgICAgICAgICAgICAgICBtZXNoX3Bvc3RfaWQ6IG1lc2hfZGF0YS5wb3N0X2lkLFxuICAgICAgICAgICAgICAgIG1lc2hfdGVtcGxhdGVfaWQ6IHRlbXBsYXRlLFxuICAgICAgICAgICAgICAgIG1lc2hfdGVtcGxhdGVfdHlwZTogdGVtcGxhdGVfdHlwZSxcbiAgICAgICAgICAgICAgICBtZXNoX2Nob29zZV90ZW1wbGF0ZV9ub25jZTogbWVzaF9kYXRhLmNob29zZV90ZW1wbGF0ZV9ub25jZVxuICAgICAgICAgICAgfSwgZnVuY3Rpb24oIHJlc3BvbnNlICkge1xuICAgICAgICAgICAgICAgIGlmICggcmVzcG9uc2UgKSB7XG5cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSxcblxuICAgICAgICAvKipcbiAgICAgICAgICogU2hvdyBvciBIaWRlIG91ciBNZXNoIFdlbGNvbWUgUGFuZWxcbiAgICAgICAgICogQmFzZWQgb24gdGhlIFdlbGNvbWUgUGFuZWwgaW4gV1AgQ29yZVxuICAgICAgICAgKlxuICAgICAgICAgKiBAcGFyYW0gdmlzaWJsZVxuICAgICAgICAgKi9cbiAgICAgICAgdXBkYXRlV2VsY29tZVBhbmVsIDogZnVuY3Rpb24oIHZpc2libGUgKSB7XG4gICAgICAgICAgICAkLnBvc3QoIGFqYXh1cmwsIHtcbiAgICAgICAgICAgICAgICBhY3Rpb246ICdtZXNoX3RlbXBsYXRlX3VwZGF0ZV93ZWxjb21lX3BhbmVsJyxcbiAgICAgICAgICAgICAgICB2aXNpYmxlOiB2aXNpYmxlLFxuICAgICAgICAgICAgICAgIG1lc2h0ZW1wbGF0ZXBhbmVsbm9uY2U6ICQoICcjbWVzaC10ZW1wbGF0ZXMtd2VsY29tZS1wYW5lbC1ub25jZScgKS52YWwoKVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0sXG5cbiAgICAgICAgcmVtb3ZlX3RlbXBsYXRlIDogZnVuY3Rpb24oIGV2ZW50ICkge1xuXG4gICAgICAgIH0sXG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIENoYW5nZSB0aGUgdHlwZSBvZiB0ZW1wbGF0ZSB0aGF0IGlzIGJlaW5nIHVzZWQgKFJlZmVyZW5jZSB2cyBTdGFydGVyKVxuICAgICAgICAgKlxuICAgICAgICAgKiBPdXIgcmVzcG9uc2Ugc2hvdWxkIGluY2x1ZGUgYSByZWZyZXNoZWQgc2V0IG9mIHNlY3Rpb25zXG4gICAgICAgICAqIHdpdGggYWxsIG9mIG91ciBwcm9wZXIgY29udHJvbHMgbmVlZGVkIG5vdyB0aGF0IHRoaXNcbiAgICAgICAgICogdGVtcGxhdGUgaXMgbm8gbG9uZ2VyIGJlaW5nIHVzZWQgYXMgYSBcInJlZmVyZW5jZVwiXG4gICAgICAgICAqXG4gICAgICAgICAqIEBzaW5jZSAxLjFcbiAgICAgICAgICogQHBhcmFtIGV2ZW50XG4gICAgICAgICAqL1xuICAgICAgICBjaGFuZ2VfdGVtcGxhdGVfdHlwZSA6IGZ1bmN0aW9uKCBldmVudCApIHtcbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICAgICAgJC5wb3N0KCBhamF4dXJsLCB7XG4gICAgICAgICAgICAgICAgYWN0aW9uOiAnbWVzaF9jaGFuZ2VfdGVtcGxhdGVfdHlwZScsXG4gICAgICAgICAgICAgICAgbWVzaF9wb3N0X2lkOiBtZXNoX2RhdGEucG9zdF9pZCxcbiAgICAgICAgICAgICAgICBtZXNoX3RlbXBsYXRlX3R5cGU6ICdzdGFydGVyJyxcbiAgICAgICAgICAgICAgICBtZXNoX2Nob29zZV90ZW1wbGF0ZV9ub25jZTogbWVzaF9kYXRhLmNob29zZV90ZW1wbGF0ZV9ub25jZVxuICAgICAgICAgICAgfSwgZnVuY3Rpb24oIHJlc3BvbnNlICkge1xuXG4gICAgICAgICAgICAgICAgaWYgKCByZXNwb25zZSApIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyICRyZXNwb25zZSA9ICQocmVzcG9uc2UpLFxuICAgICAgICAgICAgICAgICAgICAgICAgJHRpbnltY2VfZWRpdG9ycyA9ICRyZXNwb25zZS5maW5kKCcud3AtZWRpdG9yLWFyZWEnKSxcbiAgICAgICAgICAgICAgICAgICAgICAgICRlbXB0eV9tc2cgPSAkKCcuZW1wdHktc2VjdGlvbnMtbWVzc2FnZScpLFxuICAgICAgICAgICAgICAgICAgICAgICAgJGNvbnRyb2xzID0gJCgnLm1lc2gtbWFpbi11YS1yb3cnKTtcblxuICAgICAgICAgICAgICAgICAgICB2YXIgJG1lc2hfY29udGFpbmVyID0gJCgnI21lc2gtY29udGFpbmVyJyk7XG4gICAgICAgICAgICAgICAgICAgICRtZXNoX2NvbnRhaW5lci5odG1sKCcnKS5hcHBlbmQoICRyZXNwb25zZS5jaGlsZHJlbigpICk7XG4gICAgICAgICAgICAgICAgICAgIC8vICRzcGlubmVyLnJlbW92ZUNsYXNzKCdpcy1hY3RpdmUnKTtcblxuICAgICAgICAgICAgICAgICAgICBpZiAoJGVtcHR5X21zZy5sZW5ndGgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICRlbXB0eV9tc2cuZmFkZU91dCgnZmFzdCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgJGNvbnRyb2xzLmZhZGVJbignZmFzdCcpO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgdmFyICRwb3N0Ym94ZXMgPSAkKCcubWVzaC1zZWN0aW9uJywgJG1lc2hfY29udGFpbmVyICk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKCRwb3N0Ym94ZXMubGVuZ3RoID4gMSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgJCgnLm1lc2gtc2VjdGlvbi1yZW9yZGVyJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICBibG9ja3Muc2V0dXBfcmVzaXplX3NsaWRlcigpO1xuICAgICAgICAgICAgICAgICAgICBibG9ja3Muc2V0dXBfc29ydGFibGUoKTtcbiAgICAgICAgICAgICAgICAgICAgYmxvY2tzLnJlcmVuZGVyX2Jsb2NrcygkdGlueW1jZV9lZGl0b3JzKTtcblxuICAgICAgICAgICAgICAgICAgICAvLyBSZXBvcHVsYXRlIHRoZSBzZWN0aW9ucyBjYWNoZSBzbyB0aGF0IHRoZSBuZXcgc2VjdGlvbiBpcyBpbmNsdWRlZCBnb2luZyBmb3J3YXJkLlxuICAgICAgICAgICAgICAgICAgICBibG9ja3MuJHNlY3Rpb25zID0gJCgnLm1lc2gtc2VjdGlvbicsICQoJyNtZXNoLXNlY3Rpb25zLWNvbnRhaW5lcicpICk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0sXG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIERpc3BsYXkgb3VyIGF2YWlsYWJsZSB0ZW1wbGF0ZSB1c2FnZSwgUmVmZXJlbmNlIG9yIFN0YXJ0aW5nIFBvaW50XG4gICAgICAgICAqIEBwYXJhbSBldmVudFxuICAgICAgICAgKi9cbiAgICAgICAgZGlzcGxheV90ZW1wbGF0ZV90eXBlcyA6IGZ1bmN0aW9uKCBldmVudCApIHtcbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICAgICAgLy8gQHRvZG8gMS4yXG4gICAgICAgICAgICAvLyBJZiB3ZSBoYXZlIGEgbWVzaCB0ZW1wbGF0ZS4gQWx3YXlzIHVzZSBpdCBhcyBhIHN0YXJ0aW5nIHBvaW50LlxuICAgICAgICAgICAgLy8gaWYoICdtZXNoX3RlbXBsYXRlJyAhPT0gbWVzaF9kYXRhLnBvc3RfdHlwZSApIHtcbiAgICAgICAgICAgIC8vICAgICQoJyNtZXNoLXRlbXBsYXRlLXVzYWdlJykuc2hvdygpO1xuICAgICAgICAgICAgLy8gfSBlbHNlIHtcbiAgICAgICAgICAgIC8vICAgICQoJy5tZXNoLXN0YXJ0ZXItdGVtcGxhdGUnKS50cmlnZ2VyKCdjbGljaycpO1xuICAgICAgICAgICAgLy8gfVxuXG4gICAgICAgICAgICBzZWxmLnNlbGVjdF90ZW1wbGF0ZV90eXBlKCBldmVudCApO1xuICAgICAgICB9LFxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBTZWxlY3QgdGhlIHR5cGUgb2YgdGVtcGxhdGUgd2UgYXJlIHVzaW5nXG4gICAgICAgICAqIFRoaXMgY2FuIGJlIGVpdGhlciBhIHJlZmVyZW5jZSB0ZW1wbGF0ZSBvclxuICAgICAgICAgKiBhIHN0YXJ0ZXIgdGVtcGxhdGUuXG4gICAgICAgICAqXG4gICAgICAgICAqIEBzaW5jZSAxLjFcbiAgICAgICAgICogQHBhcmFtIGV2ZW50XG4gICAgICAgICAqL1xuICAgICAgICBzZWxlY3RfdGVtcGxhdGVfdHlwZSA6IGZ1bmN0aW9uKCBldmVudCApIHtcbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICAgICAgdmFyICR0aGlzICAgICAgICAgPSAkKHRoaXMpLFxuICAgICAgICAgICAgICAgIHRlbXBsYXRlICAgICAgPSAkKCcubWVzaC10ZW1wbGF0ZTpjaGVja2VkJykudmFsKCksXG4gICAgICAgICAgICAgICAgdGVtcGxhdGVfdHlwZSA9ICR0aGlzLmF0dHIoICdkYXRhLXRlbXBsYXRlLXR5cGUnICk7XG5cbiAgICAgICAgICAgICQucG9zdCggYWpheHVybCwge1xuICAgICAgICAgICAgICAgIGFjdGlvbjogJ21lc2hfY2hvb3NlX3RlbXBsYXRlJyxcbiAgICAgICAgICAgICAgICBtZXNoX3Bvc3RfaWQ6IG1lc2hfZGF0YS5wb3N0X2lkLFxuICAgICAgICAgICAgICAgIG1lc2hfdGVtcGxhdGVfaWQ6IHRlbXBsYXRlLFxuICAgICAgICAgICAgICAgIG1lc2hfdGVtcGxhdGVfdHlwZTogdGVtcGxhdGVfdHlwZSxcbiAgICAgICAgICAgICAgICBtZXNoX2Nob29zZV90ZW1wbGF0ZV9ub25jZTogbWVzaF9kYXRhLmNob29zZV90ZW1wbGF0ZV9ub25jZVxuICAgICAgICAgICAgfSwgZnVuY3Rpb24oIHJlc3BvbnNlICkge1xuICAgICAgICAgICAgICAgIGlmIChyZXNwb25zZSkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgJHJlc3BvbnNlID0gJChyZXNwb25zZSksXG4gICAgICAgICAgICAgICAgICAgICAgICAkdGlueW1jZV9lZGl0b3JzID0gJHJlc3BvbnNlLmZpbmQoJy53cC1lZGl0b3ItYXJlYScpLFxuICAgICAgICAgICAgICAgICAgICAgICAgJGVtcHR5X21zZyA9ICQoJy5lbXB0eS1zZWN0aW9ucy1tZXNzYWdlJyksXG4gICAgICAgICAgICAgICAgICAgICAgICAkY29udHJvbHMgPSAkKCcubWVzaC1tYWluLXVhLXJvdycpO1xuXG4gICAgICAgICAgICAgICAgICAgdmFyICRzZWN0aW9uX2NvbnRhaW5lciA9ICQoJyNtZXNoLXNlY3Rpb25zLWNvbnRhaW5lcicpO1xuICAgICAgICAgICAgICAgICAgICAgICAkc2VjdGlvbl9jb250YWluZXIuYXBwZW5kKCRyZXNwb25zZSk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKCRlbXB0eV9tc2cubGVuZ3RoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAkZW1wdHlfbXNnLmZhZGVPdXQoJ2Zhc3QnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICRjb250cm9scy5mYWRlSW4oJ2Zhc3QnKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIHZhciAkcG9zdGJveGVzID0gJCgnLm1lc2gtc2VjdGlvbicsICQoJyNtZXNoLWNvbnRhaW5lcicpKTtcblxuICAgICAgICAgICAgICAgICAgICBpZiAoJHBvc3Rib3hlcy5sZW5ndGggPiAxKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKCcubWVzaC1zZWN0aW9uLXJlb3JkZXInKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIGJsb2Nrcy5zZXR1cF9yZXNpemVfc2xpZGVyKCk7XG4gICAgICAgICAgICAgICAgICAgIGJsb2Nrcy5zZXR1cF9zb3J0YWJsZSgpO1xuICAgICAgICAgICAgICAgICAgICBibG9ja3MucmVyZW5kZXJfYmxvY2tzKCR0aW55bWNlX2VkaXRvcnMpO1xuXG4gICAgICAgICAgICAgICAgICAgIC8vIFJlcG9wdWxhdGUgdGhlIHNlY3Rpb25zIGNhY2hlIHNvIHRoYXQgdGhlIG5ldyBzZWN0aW9uIGlzIGluY2x1ZGVkIGdvaW5nIGZvcndhcmQuXG4gICAgICAgICAgICAgICAgICAgIGJsb2Nrcy4kc2VjdGlvbnMgPSAkKCcubWVzaC1zZWN0aW9uJywgJHNlY3Rpb25fY29udGFpbmVyKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSxcblxuICAgICAgICAvKipcbiAgICAgICAgICogU2VsZWN0IHRoZSB0ZW1wbGF0ZSB0byB1c2UgYXMgYSBiYXNlLlxuICAgICAgICAgKlxuICAgICAgICAgKiBAdG9kbyBzZWN1cml0eSBoYXJkZW4gcG9zc2libHksIGlzIGl0IGJlbmVmaWNpYWwgdG8gb3V0cHV0IGF2YWlsYWJsZSB0ZW1wbGF0ZXMgZm9yIGFkZGl0aW9uYWwgdmFsaWRhdGlvblxuICAgICAgICAgKlxuICAgICAgICAgKiBAc2luY2UgMS4xXG4gICAgICAgICAqIEBwYXJhbSBldmVudFxuICAgICAgICAgKi9cbiAgICAgICAgc2VsZWN0X2xheW91dCA6IGZ1bmN0aW9uKCBldmVudCApIHtcblxuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG4gICAgICAgICAgICB2YXIgJHRoaXMgPSAkKHRoaXMpLFxuICAgICAgICAgICAgICAgICR0ZW1wbGF0ZV9sYXlvdXRzID0gJCgnLm1lc2gtdGVtcGxhdGUtbGF5b3V0Jyk7XG5cbiAgICAgICAgICAgICR0ZW1wbGF0ZV9sYXlvdXRzLnJlbW92ZUNsYXNzKCdhY3RpdmUnKS5yZW1vdmVQcm9wKCdjaGVja2VkJyk7XG5cbiAgICAgICAgICAgICR0aGlzLmFkZENsYXNzKCdhY3RpdmUnKS5maW5kKCcubWVzaC10ZW1wbGF0ZScpLnByb3AoJ2NoZWNrZWQnLCAnY2hlY2tlZCcpO1xuICAgICAgICB9LFxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBBZGQgbmV3IHNlY3Rpb24ocykgdG8gb3VyIGNvbnRlbnQgYmFzZWQgb24gYSBNZXNoIFRlbXBsYXRlXG4gICAgICAgICAqXG4gICAgICAgICAqIEBzaW5jZSAxLjFcbiAgICAgICAgICpcbiAgICAgICAgICogQHBhcmFtIGV2ZW50XG4gICAgICAgICAqIEByZXR1cm5zIHtib29sZWFufVxuICAgICAgICAgKi9cbiAgICAgICAgc2VsZWN0X3RlbXBsYXRlIDogZnVuY3Rpb24oZXZlbnQpIHtcblxuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG4gICAgICAgICAgICB2YXIgJHRoaXMgPSAkKHRoaXMpLFxuICAgICAgICAgICAgICAgICRzcGlubmVyID0gJHRoaXMuc2libGluZ3MoJy5zcGlubmVyJyk7XG5cbiAgICAgICAgICAgIGlmICggJHRoaXMuaGFzQ2xhc3MoJ2Rpc2FibGVkJykgKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAkc3Bpbm5lci5hZGRDbGFzcygnaXMtYWN0aXZlJyk7XG5cbiAgICAgICAgICAgICQucG9zdCggYWpheHVybCwge1xuICAgICAgICAgICAgICAgIGFjdGlvbjogJ21lc2hfbGlzdF90ZW1wbGF0ZXMnLFxuICAgICAgICAgICAgICAgIG1lc2hfcG9zdF9pZDogbWVzaF9kYXRhLnBvc3RfaWQsXG4gICAgICAgICAgICAgICAgbWVzaF9jaG9vc2VfdGVtcGxhdGVfbm9uY2U6IG1lc2hfZGF0YS5jaG9vc2VfdGVtcGxhdGVfbm9uY2VcbiAgICAgICAgICAgIH0sIGZ1bmN0aW9uKCByZXNwb25zZSApe1xuICAgICAgICAgICAgICAgIGlmICggcmVzcG9uc2UgKSB7XG4gICAgICAgICAgICAgICAgICAgIHZhciAkcmVzcG9uc2UgPSAkKCByZXNwb25zZSApO1xuXG4gICAgICAgICAgICAgICAgICAgICQoJyNtZXNoLWRlc2NyaXB0aW9uJykuaHRtbCgnJykuYXBwZW5kKCAkcmVzcG9uc2UgKTtcbiAgICAgICAgICAgICAgICAgICAgJHNwaW5uZXIucmVtb3ZlQ2xhc3MoJ2lzLWFjdGl2ZScpO1xuXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgJHNwaW5uZXIucmVtb3ZlQ2xhc3MoJ2lzLWFjdGl2ZScpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfTtcblxufSAoIGpRdWVyeSApO1xuO1xudmFyIG1lc2ggPSBtZXNoIHx8IHt9O1xubWVzaC5pbnRlZ3JhdGlvbnMgPSBtZXNoLmludGVncmF0aW9ucyB8fCB7fTsgLy8gQHNpbmNlIDEuMiBzdG9yZSBpbnRlZ3JhdGlvbnMuXG5cbm1lc2guYWRtaW4gPSBmdW5jdGlvbiAoJCkge1xuXG5cdHZhciAkYm9keSA9ICQoJ2JvZHknKSxcblx0XHQkZG9jdW1lbnQgPSAkKCdkb2N1bWVudCcpLFxuXHRcdCRyZW9yZGVyX2J1dHRvbiA9ICQoJy5tZXNoLXNlY3Rpb24tcmVvcmRlcicpLFxuXHRcdCRhZGRfYnV0dG9uID0gJCgnLm1lc2gtc2VjdGlvbi1hZGQnKSxcblx0XHQkY29sbGFwc2VfYnV0dG9uID0gJCgnLm1lc2gtc2VjdGlvbi1jb2xsYXBzZScpLFxuXHRcdCRleHBhbmRfYnV0dG9uID0gJCgnLm1lc2gtc2VjdGlvbi1leHBhbmQnKSxcblx0XHQkbWV0YV9ib3hfY29udGFpbmVyID0gJCgnI21lc2gtY29udGFpbmVyJyksXG5cdFx0JHNlY3Rpb25fY29udGFpbmVyID0gJCgnI21lc2gtc2VjdGlvbnMtY29udGFpbmVyJyksXG5cdFx0JGRlc2NyaXB0aW9uID0gJCgnI21lc2gtZGVzY3JpcHRpb24nKSxcblx0XHQkZXF1YWxpemUgPSAkKCdbZGF0YS1lcXVhbGl6ZXJdJyksXG5cdFx0JHNlY3Rpb25zLFxuXHRcdG1lZGlhX2ZyYW1lcyA9IFtdLFxuXG5cdFx0Ly8gU2V0dGluZ3NcblxuXHRcdEZBREVfU1BFRUQgPSAxMDAsXG5cblx0XHQvLyBDb250YWluZXIgUmVmZXJlbmNlcyBmb3IgQWRtaW4oc2VsZikgLyBCbG9ja1xuXHRcdHNlbGYsXG5cdFx0YmxvY2tzLFxuXHRcdHBvaW50ZXJzLFxuXHRcdHRlbXBsYXRlcyxcblx0XHRzZWN0aW9uX2NvdW50O1xuXG5cdC8qKiogQHJldHVybiBvYmplY3QgKi9cblx0cmV0dXJuIHtcblxuXHRcdC8qKlxuXHRcdCAqIEluaXRpYWxpemUgb3VyIHNjcmlwdFxuXHRcdCAqL1xuXHRcdGluaXQ6IGZ1bmN0aW9uICgpIHtcblxuXHRcdFx0aWYgKCdwb3N0JyAhPT0gbWVzaF9kYXRhLnNjcmVlbiAmJiAnZWRpdCcgIT09IG1lc2hfZGF0YS5zY3JlZW4gJiYgJ3NldHRpbmdzX3BhZ2VfbWVzaCcgIT09IG1lc2hfZGF0YS5zY3JlZW4pIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRpZiAoJ2VkaXQnID09PSBtZXNoX2RhdGEuc2NyZWVuKSB7XG5cdFx0XHRcdHRlbXBsYXRlcyA9IG1lc2gudGVtcGxhdGVzO1xuXHRcdFx0XHQvLyBTZXR1cCBvdXIgY29udHJvbHMgZm9yIHRlbXBsYXRlc1xuXHRcdFx0XHR0ZW1wbGF0ZXMuaW5pdCgpO1xuXHRcdFx0fVxuXG5cdFx0XHRzZWxmID0gbWVzaC5hZG1pbjtcblx0XHRcdGJsb2NrcyA9IG1lc2guYmxvY2tzO1xuXHRcdFx0cG9pbnRlcnMgPSBtZXNoLnBvaW50ZXJzO1xuXHRcdFx0dGVtcGxhdGVzID0gbWVzaC50ZW1wbGF0ZXM7XG5cblx0XHRcdCRib2R5XG5cdFx0XHRcdC5vbignY2xpY2snLCAnLm1lc2gtc2VjdGlvbi1hZGQnLCBzZWxmLmFkZF9zZWN0aW9uKVxuXHRcdFx0XHQub24oJ2NsaWNrJywgJy5tZXNoLXNlY3Rpb24tcmVtb3ZlJywgc2VsZi5yZW1vdmVfc2VjdGlvbilcblx0XHRcdFx0Lm9uKCdjbGljaycsICcubWVzaC1zZWN0aW9uLXJlb3JkZXInLCBzZWxmLnJlb3JkZXJfc2VjdGlvbnMpXG5cdFx0XHRcdC5vbignY2xpY2snLCAnLm1lc2gtc2F2ZS1vcmRlcicsIHNlbGYuc2F2ZV9zZWN0aW9uX29yZGVyKVxuXHRcdFx0XHQub24oJ2NsaWNrJywgJy5tZXNoLWZlYXR1cmVkLWltYWdlLXRyYXNoJywgc2VsZi5yZW1vdmVfYmFja2dyb3VuZClcblx0XHRcdFx0Lm9uKCdjbGljaycsICcubWVzaC1zZWN0aW9uLWV4cGFuZCcsIHNlbGYuZXhwYW5kX2FsbF9zZWN0aW9ucylcblx0XHRcdFx0Lm9uKCdjbGljaycsICcubWVzaC1zZWN0aW9uLWNvbGxhcHNlJywgc2VsZi5jb2xsYXBzZV9hbGxfc2VjdGlvbnMpXG5cdFx0XHRcdC5vbignY2xpY2snLCAnLm1lc2gtZmVhdHVyZWQtaW1hZ2UtY2hvb3NlJywgc2VsZi5jaG9vc2VfYmFja2dyb3VuZClcblx0XHRcdFx0Lm9uKCdjbGljay5PcGVuTWVkaWFNYW5hZ2VyJywgJy5tZXNoLWZlYXR1cmVkLWltYWdlLWNob29zZScsIHNlbGYuY2hvb3NlX2JhY2tncm91bmQpXG5cblx0XHRcdFx0Ly8gQHNpbmNlIDEuMVxuXHRcdFx0XHQub24oJ2NsaWNrJywgJy5tZXNoLXRyYXNoLWV4dHJhLWJsb2NrcycsIHNlbGYudHJhc2hfZXh0cmFfYmxvY2tzKVxuXG5cdFx0XHRcdC5vbignY2xpY2snLCAnLm1lc2gtc2VjdGlvbi11cGRhdGUnLCBzZWxmLnNlY3Rpb25fc2F2ZSlcblx0XHRcdFx0Lm9uKCdjbGljaycsICcubWVzaC1zZWN0aW9uLXNhdmUtZHJhZnQnLCBzZWxmLnNlY3Rpb25fc2F2ZV9kcmFmdClcblx0XHRcdFx0Lm9uKCdjbGljaycsICcubWVzaC1zZWN0aW9uLXB1Ymxpc2gnLCBzZWxmLnNlY3Rpb25fcHVibGlzaClcblxuXHRcdFx0XHQub24oJ2NoYW5nZScsICcubWVzaC1jaG9vc2UtbGF5b3V0Jywgc2VsZi5jaG9vc2VfbGF5b3V0KVxuXHRcdFx0XHQub24oJ2tleXByZXNzJywgJy5tZXNoLWNsZWFuLWVkaXQtZWxlbWVudCcsIHNlbGYucHJldmVudF9zdWJtaXQpXG5cdFx0XHRcdC5vbigna2V5dXAnLCAnLm1lc2gtY2xlYW4tZWRpdC1lbGVtZW50Jywgc2VsZi5jaGFuZ2VfaW5wdXRfdGl0bGUpXG5cdFx0XHRcdC5vbignY2hhbmdlJywgJ3NlbGVjdC5tZXNoLWNsZWFuLWVkaXQtZWxlbWVudCcsIHNlbGYuY2hhbmdlX3NlbGVjdF90aXRsZSlcblxuXHRcdFx0XHQvLyBAc2luY2UgMS4xLjNcblx0XHRcdFx0Lm9uKCdjaGFuZ2UnLCAnI21lc2gtY3NzX21vZGUnLCBzZWxmLmRpc3BsYXlfZm91bmRhdGlvbl9vcHRpb25zKVxuXG5cdFx0XHRcdC8vIEBzaW5jZSAxLjIuNVxuICAgICAgICBcdFx0Lm9uKCdjaGFuZ2UnLCAnI21lc2gtZm91bmRhdGlvbl92ZXJzaW9uJywgc2VsZi5kaXNwbGF5X2ZvdW5kYXRpb25fZ3JpZF9vcHRpb25zKTtcblxuXHRcdFx0Ly8gQHNpbmNlIDEuMlxuXG5cdFx0XHR2YXIgZXZlbnQgPSAoIHR5cGVvZiggZXZlbnQgKSAhPSAndW5kZWZpbmVkJyApID8gZXZlbnQgOiAnJztcblxuXHRcdFx0JChkb2N1bWVudClcblx0XHRcdFx0Lm9uKCdwb3N0Ym94LXRvZ2dsZWQnLCB7ZXZlbnQ6IGV2ZW50fSwgc2VsZi5leHBhbmRfc2VjdGlvbiApO1xuXG5cdFx0XHQkc2VjdGlvbnMgPSAkKCcubWVzaC1zZWN0aW9uJyk7XG5cblx0XHRcdGlmICgkc2VjdGlvbnMubGVuZ3RoIDw9IDEpIHtcblx0XHRcdFx0JHJlb3JkZXJfYnV0dG9uLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0fVxuXG5cdFx0XHRpZiAoJGVxdWFsaXplLmxlbmd0aCkge1xuXHRcdFx0XHQkZXF1YWxpemUuZWFjaChzZWxmLm1lc2hfZXF1YWxpemUpO1xuXHRcdFx0fVxuXG5cdFx0XHQvLyBTZXR1cCBvdXIgY29udHJvbHMgZm9yIEJsb2Nrc1xuXHRcdFx0YmxvY2tzLmluaXQoKTtcblxuXHRcdFx0Ly8gU2V0dXAgb3VyIFBvaW50ZXJzXG5cdFx0XHRwb2ludGVycy5zaG93X3BvaW50ZXIoMCk7XG5cblx0XHRcdC8vIFNldHVwIG91ciBjb250cm9scyBmb3IgdGVtcGxhdGVzXG5cdFx0XHR0ZW1wbGF0ZXMuaW5pdCgpO1xuXG5cdFx0XHRzZWxmLnNldHVwX25vdGlmaWNhdGlvbnMoJG1ldGFfYm94X2NvbnRhaW5lcik7XG5cblx0XHRcdHNlbGYuZGlzcGxheV9mb3VuZGF0aW9uX29wdGlvbnMoKTtcbiAgICAgICAgICAgIHNlbGYuZGlzcGxheV9mb3VuZGF0aW9uX2dyaWRfb3B0aW9ucygpO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBBZGQgbm90aWZpY2F0aW9ucyB0byBvdXIgc2VjdGlvblxuXHRcdCAqXG5cdFx0ICogQHBhcmFtICRsYXlvdXRcblx0XHQgKiBAcmV0dXJucyB2b2lkXG5cdFx0ICovXG5cdFx0c2V0dXBfbm90aWZpY2F0aW9uczogZnVuY3Rpb24gKCRsYXlvdXQpIHtcblx0XHRcdC8vIE1ha2Ugbm90aWNlcyBkaXNtaXNzaWJsZVxuXHRcdFx0JGxheW91dC5maW5kKCcubm90aWNlLmlzLWRpc21pc3NpYmxlJykuZWFjaChmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdHZhciAkdGhpcyA9ICQodGhpcyksXG5cdFx0XHRcdFx0JGJ1dHRvbiA9ICQoJzxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwibm90aWNlLWRpc21pc3NcIj48c3BhbiBjbGFzcz1cInNjcmVlbi1yZWFkZXItdGV4dFwiPjwvc3Bhbj48L2J1dHRvbj4nKSxcblx0XHRcdFx0XHRidG5UZXh0ID0gY29tbW9uTDEwbi5kaXNtaXNzIHx8ICcnO1xuXG5cdFx0XHRcdC8vIEVuc3VyZSBwbGFpbiB0ZXh0XG5cdFx0XHRcdCRidXR0b24uZmluZCgnLnNjcmVlbi1yZWFkZXItdGV4dCcpLnRleHQoYnRuVGV4dCk7XG5cblx0XHRcdFx0JHRoaXMuYXBwZW5kKCRidXR0b24pO1xuXG5cdFx0XHRcdCRidXR0b24ub24oJ2NsaWNrLndwLWRpc21pc3Mtbm90aWNlJywgZnVuY3Rpb24gKGV2ZW50KSB7XG5cdFx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblxuXHRcdFx0XHRcdCQucG9zdChhamF4dXJsLCB7XG5cdFx0XHRcdFx0XHRhY3Rpb246ICdtZXNoX2Rpc21pc3Nfbm90aWZpY2F0aW9uJyxcblx0XHRcdFx0XHRcdG1lc2hfbm90aWZpY2F0aW9uX3R5cGU6ICR0aGlzLmF0dHIoJ2RhdGEtdHlwZScpLFxuXHRcdFx0XHRcdFx0X3dwbm9uY2U6IG1lc2hfZGF0YS5kaXNtaXNzX25vbmNlXG5cdFx0XHRcdFx0fSwgZnVuY3Rpb24gKHJlc3BvbnNlKSB7XG5cdFx0XHRcdFx0fSk7XG5cblx0XHRcdFx0XHQkdGhpcy5mYWRlVG8oIEZBREVfU1BFRUQsIDAsIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRcdCQodGhpcykuc2xpZGVVcCggRkFERV9TUEVFRCwgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRcdFx0XHQkKHRoaXMpLnJlbW92ZSgpO1xuXHRcdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0fSk7XG5cdFx0XHRcdH0pO1xuXHRcdFx0fSk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEV4cGFuZCB0YXJnZXRlZCBzZWN0aW9uXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS4yXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge2V2ZW50fSAgZXZlbnQgIFRoZSBqUXVlcnkgRXZlbnQuXG5cdFx0ICogQHBhcmFtIHtvYmplY3R9IGVsZW1lbnQgVGhlIE9iamVjdCBCZWluZyBFeHBhbmRlZCAodHlwaWNhbGx5IHBvc3Rib3gpLlxuXHRcdCAqIEByZXR1cm4gdm9pZFxuXHRcdCAqL1xuXHRcdGV4cGFuZF9zZWN0aW9uOiBmdW5jdGlvbiAoIGV2ZW50LCBlbGVtZW50ICkge1xuXG5cdFx0XHR2YXIgJHNlY3Rpb24gPSAkKGVsZW1lbnQpLFxuXHRcdFx0XHQkdGlueW1jZV9lZGl0b3IgPSAkc2VjdGlvbi5maW5kKCcud3AtZWRpdG9yLWFyZWEnKTtcblxuXHRcdFx0aWYgKCEkc2VjdGlvbi5oYXNDbGFzcygnY2xvc2VkJykpIHtcblx0XHRcdFx0YmxvY2tzLnJlcmVuZGVyX2Jsb2NrcygkdGlueW1jZV9lZGl0b3IpO1xuXHRcdFx0fVxuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiAxIGNsaWNrIHRvIGV4cGFuZCBhbGwgc2VjdGlvbnNcblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAwLjMuMFxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtldmVudH0gZXZlbnQgQ2xpY2sgRXZlbnQuXG5cdFx0ICovXG5cdFx0ZXhwYW5kX2FsbF9zZWN0aW9uczogZnVuY3Rpb24gKGV2ZW50KSB7XG5cblx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuXHRcdFx0JHNlY3Rpb25zLmVhY2goZnVuY3Rpb24gKCkge1xuXHRcdFx0XHR2YXIgJGhhbmRsZSA9ICQodGhpcykuZmluZCgnLmhhbmRsZWRpdicpO1xuXG5cdFx0XHRcdGlmICgndHJ1ZScgIT0gJGhhbmRsZS5hdHRyKCdhcmlhLWV4cGFuZGVkJykpIHtcblx0XHRcdFx0XHQkaGFuZGxlLnRyaWdnZXIoJ2NsaWNrJyk7XG5cdFx0XHRcdFx0c2VsZi5leHBhbmRfc2VjdGlvbihldmVudCwgJCh0aGlzKSk7XG5cdFx0XHRcdH1cblx0XHRcdH0pO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiAxIGNsaWNrIHRvIGNvbGxhcHNlIHNlY3Rpb25zXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS4wLjBcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7ZXZlbnR9IGV2ZW50IENsaWNrIEV2ZW50LlxuXHRcdCAqIEByZXR1cm4gdm9pZFxuXHRcdCAqL1xuXHRcdGNvbGxhcHNlX2FsbF9zZWN0aW9uczogZnVuY3Rpb24gKGV2ZW50KSB7XG5cblx0XHRcdGlmICh0eXBlb2YoIGV2ZW50ICkgIT0gJ3VuZGVmaW5lZCcpIHtcblx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0ZXZlbnQuc3RvcFByb3BhZ2F0aW9uKCk7XG5cdFx0XHR9XG5cblx0XHRcdCRzZWN0aW9uX2NvbnRhaW5lci5maW5kKCcuaGFuZGxlZGl2JykuZWFjaChmdW5jdGlvbiAoKSB7XG5cblx0XHRcdFx0dmFyICR0aGlzID0gJCh0aGlzKTtcblxuXHRcdFx0XHRpZiAoJ3RydWUnID09ICR0aGlzLmF0dHIoJ2FyaWEtZXhwYW5kZWQnKSB8fCAkdGhpcy5oYXNDbGFzcygndG9nZ2xlZCcpKSB7XG5cdFx0XHRcdFx0JHRoaXMudHJpZ2dlcignY2xpY2snKTtcblx0XHRcdFx0fVxuXHRcdFx0fSk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFRoaXMgbWV0aG9kIGlzIG9ubHkgdXNlZCB3aGVuIGEgbmV3IHNlY3Rpb24gaXMgYWRkZWRcblx0XHQgKiB0byBhIHBvc3QuIFRoZSBwb3N0IHRvZ2dsZSBhY3Rpb24gaXMgbm90IGJvdW5kIHRvIHRoZSBkb2N1bWVudFxuXHRcdCAqIG9yIGJvZHkgc28gd2UgYXJlIHJlcGxpY2F0aW5nIHdoYXQgaXMgaGFwcGVuaW5nIGZyb20gY29yZS5cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjFcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSBldmVudFxuXHRcdCAqIEByZXR1cm4gdm9pZFxuXHRcdCAqL1xuXHRcdHRvZ2dsZV9jb2xsYXBzZTogZnVuY3Rpb24gKGV2ZW50KSB7XG5cblx0XHRcdHZhciAkZWwgPSAkKHRoaXMpLFxuXHRcdFx0XHRwID0gJGVsLnBhcmVudCgnLnBvc3Rib3gnKSxcblx0XHRcdFx0aWQgPSBwLmF0dHIoJ2lkJyksXG5cdFx0XHRcdGFyaWFFeHBhbmRlZFZhbHVlO1xuXG5cdFx0XHRwLnRvZ2dsZUNsYXNzKCdjbG9zZWQnKTtcblxuXHRcdFx0YXJpYUV4cGFuZGVkVmFsdWUgPSAhcC5oYXNDbGFzcygnY2xvc2VkJyk7XG5cblx0XHRcdGlmICgkZWwuaGFzQ2xhc3MoJ2hhbmRsZWRpdicpKSB7XG5cdFx0XHRcdC8vIFRoZSBoYW5kbGUgYnV0dG9uIHdhcyBjbGlja2VkLlxuXHRcdFx0XHQkZWwuYXR0cignYXJpYS1leHBhbmRlZCcsIGFyaWFFeHBhbmRlZFZhbHVlKTtcblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdC8vIFRoZSBoYW5kbGUgaGVhZGluZyB3YXMgY2xpY2tlZC5cblx0XHRcdFx0JGVsLmNsb3Nlc3QoJy5wb3N0Ym94JykuZmluZCgnYnV0dG9uLmhhbmRsZWRpdicpXG5cdFx0XHRcdFx0LmF0dHIoJ2FyaWEtZXhwYW5kZWQnLCBhcmlhRXhwYW5kZWRWYWx1ZSk7XG5cdFx0XHR9XG5cblx0XHRcdGlmIChwb3N0Ym94ZXMucGFnZSAhPT0gJ3ByZXNzLXRoaXMnKSB7XG5cdFx0XHRcdHBvc3Rib3hlcy5zYXZlX3N0YXRlKHBvc3Rib3hlcy5wYWdlKTtcblx0XHRcdH1cblxuXHRcdFx0aWYgKGlkKSB7XG5cdFx0XHRcdGlmICghcC5oYXNDbGFzcygnY2xvc2VkJykgJiYgJC5pc0Z1bmN0aW9uKHBvc3Rib3hlcy5wYnNob3cpKSB7XG5cdFx0XHRcdFx0cG9zdGJveGVzLnBic2hvdyhpZCk7XG5cdFx0XHRcdH0gZWxzZSBpZiAocC5oYXNDbGFzcygnY2xvc2VkJykgJiYgJC5pc0Z1bmN0aW9uKHBvc3Rib3hlcy5wYmhpZGUpKSB7XG5cdFx0XHRcdFx0cG9zdGJveGVzLnBiaGlkZShpZCk7XG5cdFx0XHRcdH1cblx0XHRcdH1cblxuXHRcdFx0c2VsZi5leHBhbmRfc2VjdGlvbihldmVudCwgcC5jbG9zZXN0KCcubWVzaC1zZWN0aW9uJykpO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBDaG9vc2Ugd2hhdCBsYXlvdXQgaXMgdXNlZCBmb3IgdGhlIHNlY3Rpb25cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAwLjEuMFxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtldmVudH0gZXZlbnQgQ2xpY2sgRXZlbnQuXG5cdFx0ICogQHJldHVybnMge2Jvb2xlYW59XG5cdFx0ICovXG5cdFx0Y2hvb3NlX2xheW91dDogZnVuY3Rpb24gKGV2ZW50KSB7XG5cblx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuXHRcdFx0dmFyICR0aGlzID0gJCh0aGlzKSxcblx0XHRcdFx0dGVtcF92YWwgPSAkKHRoaXMpLnZhbCgpLFxuXHRcdFx0XHQkc3Bpbm5lciA9ICR0aGlzLnNpYmxpbmdzKCcuc3Bpbm5lcicpLFxuXHRcdFx0XHQkc2VjdGlvbiA9ICR0aGlzLnBhcmVudHMoJy5tZXNoLXNlY3Rpb24nKSxcblx0XHRcdFx0c2VjdGlvbl9pZCA9ICRzZWN0aW9uLmF0dHIoJ2RhdGEtbWVzaC1zZWN0aW9uLWlkJyksXG5cdFx0XHRcdCRtb3JlX29wdGlvbnMgPSAkc2VjdGlvbi5maW5kKCcubWVzaC1zZWN0aW9uLW1ldGEnKS5maW5kKCcubWVzaC1tb3JlLXNlY3Rpb24tb3B0aW9ucycpLFxuXHRcdFx0XHR0YWJfb3BlbiA9ICRtb3JlX29wdGlvbnMuaGFzQ2xhc3MoJ3RvZ2dsZWQnKTtcblxuXHRcdFx0aWYgKCR0aGlzLmhhc0NsYXNzKCdkaXNhYmxlZCcpKSB7XG5cdFx0XHRcdHJldHVybiBmYWxzZTtcblx0XHRcdH1cblxuXHRcdFx0dmFyICR0aW55bWNlX2VkaXRvcnMgPSBibG9ja3MuZ2V0X3RpbnltY2VfZWRpdG9ycygkc2VjdGlvbik7XG5cblx0XHRcdCR0aW55bWNlX2VkaXRvcnMuZWFjaChmdW5jdGlvbiAoKSB7XG5cblx0XHRcdFx0dmFyIHRpbnlNQ0VfY29udGVudCA9ICcnLFxuXHRcdFx0XHRcdGVkaXRvcklEID0gJCh0aGlzKS5wcm9wKCdpZCcpLFxuXHRcdFx0XHRcdGVkaXRvciA9IHRpbnltY2UuZ2V0KGVkaXRvcklEKTtcblxuXHRcdFx0XHQvLyBNYWtlIHN1cmUgd2UgaGF2ZSBhbiBlZGl0b3IgYW5kIHdlIGFyZW4ndCBpbiB0ZXh0IHZpZXcuXG5cdFx0XHRcdGlmIChlZGl0b3IgJiYgIWVkaXRvci5oaWRkZW4pIHtcblx0XHRcdFx0XHR0aW55TUNFX2NvbnRlbnQgPSBlZGl0b3IuZ2V0Q29udGVudCgpO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0YmxvY2tzLnNldF9ibG9ja19jYWNoZShlZGl0b3JJRCwgdGlueU1DRV9jb250ZW50KTtcblx0XHRcdH0pO1xuXG5cdFx0XHQkc3Bpbm5lci5hZGRDbGFzcygnaXMtYWN0aXZlJyk7XG5cblx0XHRcdHNlbGYuZGlzYWJsZV9jb250cm9scygkc2VjdGlvbik7XG5cblx0XHRcdCQucG9zdChhamF4dXJsLCB7XG5cdFx0XHRcdGFjdGlvbjogJ21lc2hfY2hvb3NlX2xheW91dCcsXG5cdFx0XHRcdG1lc2hfcG9zdF9pZDogbWVzaF9kYXRhLnBvc3RfaWQsXG5cdFx0XHRcdG1lc2hfc2VjdGlvbl9pZDogc2VjdGlvbl9pZCxcblx0XHRcdFx0bWVzaF9zZWN0aW9uX2xheW91dDogdGVtcF92YWwsXG5cdFx0XHRcdG1lc2hfY2hvb3NlX2xheW91dF9ub25jZTogbWVzaF9kYXRhLmNob29zZV9sYXlvdXRfbm9uY2Vcblx0XHRcdH0sIGZ1bmN0aW9uIChyZXNwb25zZSkge1xuXHRcdFx0XHRpZiAocmVzcG9uc2UpIHtcblxuXHRcdFx0XHRcdHZhciAkcmVzcG9uc2UgPSAkKHJlc3BvbnNlKSxcblx0XHRcdFx0XHRcdCR0aW55bWNlX2VkaXRvcnMsXG5cdFx0XHRcdFx0XHQkc2VjdGlvbiA9ICQoJyNtZXNoLXNlY3Rpb24tJyArIHNlY3Rpb25faWQpO1xuXG5cdFx0XHRcdFx0JHRpbnltY2VfZWRpdG9ycyA9ICRzZWN0aW9uLmZpbmQoJy53cC1lZGl0b3ItYXJlYScpO1xuXG5cdFx0XHRcdFx0Ly8gQHRvZG8gdGhpcyBzaG91bGQgYmUgZG9uZSBtb3JlIGVmZmljaWVudGx5IGxhdGVyOiBOZWVkZWQgZm9yIEZpcmVmb3ggYnV0IHdpbGwgYmUgZml4ZWRcblx0XHRcdFx0XHQvLyBvbmNlIGNvbnNvbGlkYXRlZC4gQ2FuJ3QgY2xlYXIgaHRtbCBiZWZvcmUgcmVtb3Zpbmcgb3IgdGlueW1jZSB0aHJvd3MgYW4gZXJyb3Jcblx0XHRcdFx0XHQkdGlueW1jZV9lZGl0b3JzLmVhY2goZnVuY3Rpb24gKCkge1xuXG5cdFx0XHRcdFx0XHRpZiAocGFyc2VJbnQodGlueW1jZS5tYWpvclZlcnNpb24pID49IDQpIHtcblx0XHRcdFx0XHRcdFx0dGlueW1jZS5leGVjQ29tbWFuZCgnbWNlUmVtb3ZlRWRpdG9yJywgZmFsc2UsICQodGhpcykucHJvcCgnaWQnKSk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0fSk7XG5cblx0XHRcdFx0XHQvLyBTdG9yZSBjdXJyZW50IGRpc3BsYXlcblxuXHRcdFx0XHRcdCRyZXNwb25zZS5maW5kKCcubWVzaC1jaG9vc2UtbGF5b3V0JykudmFsKHRlbXBfdmFsKTsgLy8gU2V0IG91ciBuZXdseSByZW5kZXIgaHRtbCB0byB0aGUgcHJvcGVybHlcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAvLyBsYXlvdXQuXG5cblx0XHRcdFx0XHQvLyBFbmQgZGlzcGxheSByZXNldFxuXG5cdFx0XHRcdFx0JHNlY3Rpb24uZmluZCgnLmluc2lkZScpLmh0bWwoJycpLmFwcGVuZCgkcmVzcG9uc2UpO1xuXG5cdFx0XHRcdFx0aWYgKHRhYl9vcGVuKSB7XG5cdFx0XHRcdFx0XHQkc2VjdGlvbi5maW5kKCcubWVzaC1tb3JlLXNlY3Rpb24tb3B0aW9ucycpLmFkZENsYXNzKCd0b2dnbGVkJyk7XG5cdFx0XHRcdFx0XHQkc2VjdGlvbi5maW5kKCcubWVzaC1zZWN0aW9uLW1ldGEtZHJvcGRvd24nKS5yZW1vdmVDbGFzcygnaGlkZScpLnNob3coKTtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHQvLyBMb29wIHRocm91Z2ggYWxsIG9mIG91ciBlZGl0cyBpbiB0aGUgcmVzcG9uc2Vcblx0XHRcdFx0XHQvLyByZXNldCBvdXIgZWRpdG9ycyBhZnRlciBjbGVhcmluZ1xuXHRcdFx0XHRcdCR0aW55bWNlX2VkaXRvcnMgPSAkc2VjdGlvbi5maW5kKCcud3AtZWRpdG9yLWFyZWEnKTtcblxuXHRcdFx0XHRcdGJsb2Nrcy5zZXR1cF9yZXNpemVfc2xpZGVyKCk7XG5cdFx0XHRcdFx0YmxvY2tzLnNldHVwX3NvcnRhYmxlKCk7XG5cdFx0XHRcdFx0YmxvY2tzLnJlcmVuZGVyX2Jsb2NrcygkdGlueW1jZV9lZGl0b3JzKTtcblxuXHRcdFx0XHRcdC8vIHNlbGYuc2V0dXBfbm90aWZpY2F0aW9ucyggJGxheW91dCApO1xuXHRcdFx0XHR9XG5cdFx0XHRcdHNlbGYuZW5hYmxlX2NvbnRyb2xzKCRzZWN0aW9uKTtcblxuXHRcdFx0XHQkc3Bpbm5lci5yZW1vdmVDbGFzcygnaXMtYWN0aXZlJyk7XG5cdFx0XHR9KTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQWRkIGEgbmV3IHNlY3Rpb24gdG8gb3VyIGNvbnRlbnRcblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAwLjEuMFxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIGV2ZW50XG5cdFx0ICogQHJldHVybnMge2Jvb2xlYW59XG5cdFx0ICovXG5cdFx0YWRkX3NlY3Rpb246IGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG5cdFx0XHRzZWN0aW9uX2NvdW50ID0gJHNlY3Rpb25zLmxlbmd0aDtcblxuXHRcdFx0dmFyICR0aGlzID0gJCh0aGlzKSxcblx0XHRcdFx0JHNwaW5uZXIgPSAkdGhpcy5maW5kKCcuc3Bpbm5lcicpLFxuXHRcdFx0XHQkbWVzaFNlY3Rpb25zQ29udGFpbmVyID0gJCgnI21lc2gtc2VjdGlvbnMtY29udGFpbmVyJyk7XG5cblx0XHRcdGlmICgkdGhpcy5oYXNDbGFzcygnZGlzYWJsZWQnKSkge1xuXHRcdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0XHR9XG5cblx0XHRcdHNlbGYuZGlzYWJsZV9jb250cm9scygkbWV0YV9ib3hfY29udGFpbmVyKTtcblxuXHRcdFx0JHRoaXMuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xuXG5cdFx0XHQkc3Bpbm5lci5hZGRDbGFzcygnaXMtYWN0aXZlJyk7XG5cblx0XHRcdCQucG9zdChhamF4dXJsLCB7XG5cdFx0XHRcdGFjdGlvbjogJ21lc2hfYWRkX3NlY3Rpb24nLFxuXHRcdFx0XHRtZXNoX3Bvc3RfaWQ6IG1lc2hfZGF0YS5wb3N0X2lkLFxuXHRcdFx0XHRtZXNoX3NlY3Rpb25fY291bnQ6IHNlY3Rpb25fY291bnQsXG5cdFx0XHRcdG1lc2hfYWRkX3NlY3Rpb25fbm9uY2U6IG1lc2hfZGF0YS5hZGRfc2VjdGlvbl9ub25jZVxuXHRcdFx0fSwgZnVuY3Rpb24gKHJlc3BvbnNlKSB7XG5cdFx0XHRcdGlmIChyZXNwb25zZSkge1xuXHRcdFx0XHRcdHZhciAkcmVzcG9uc2UgPSAkKHJlc3BvbnNlKSxcblx0XHRcdFx0XHRcdCR0aW55bWNlX2VkaXRvcnMgPSAkcmVzcG9uc2UuZmluZCgnLndwLWVkaXRvci1hcmVhJyksXG5cdFx0XHRcdFx0XHQkZW1wdHlfbXNnID0gJCgnLmVtcHR5LXNlY3Rpb25zLW1lc3NhZ2UnKSxcblx0XHRcdFx0XHRcdCRjb250cm9scyA9ICQoJy5tZXNoLW1haW4tdWEtcm93Jyk7XG5cblx0XHRcdFx0XHQkc2VjdGlvbl9jb250YWluZXIuYXBwZW5kKCRyZXNwb25zZSk7XG5cdFx0XHRcdFx0JHNwaW5uZXIucmVtb3ZlQ2xhc3MoJ2lzLWFjdGl2ZScpO1xuXG5cdFx0XHRcdFx0JHRoaXMucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuXG5cdFx0XHRcdFx0aWYgKCRlbXB0eV9tc2cubGVuZ3RoKSB7XG5cdFx0XHRcdFx0XHQkZW1wdHlfbXNnLmZhZGVPdXQoJ2Zhc3QnKS5wcm9taXNlKGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRcdFx0JCgnI2Rlc2NyaXB0aW9uLXdyYXAnKS5yZW1vdmUoKTtcblx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0JGNvbnRyb2xzLmZhZGVJbignZmFzdCcpO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdGJsb2Nrcy5yZXJlbmRlcl9ibG9ja3MoJHRpbnltY2VfZWRpdG9ycyk7XG5cblx0XHRcdFx0XHQvLyBSZXBvcHVsYXRlIHRoZSBzZWN0aW9ucyBjYWNoZSBzbyB0aGF0IHRoZSBuZXcgc2VjdGlvbiBpcyBpbmNsdWRlZCBnb2luZyBmb3J3YXJkLlxuXHRcdFx0XHRcdCRzZWN0aW9ucyA9ICQoJy5tZXNoLXNlY3Rpb24nLCAkc2VjdGlvbl9jb250YWluZXIpO1xuXG5cdFx0XHRcdFx0dmFyICRoYW5kbGUgPSAkcmVzcG9uc2UuZmluZCgnLmhhbmRsZWRpdicpO1xuXG5cdFx0XHRcdFx0JGhhbmRsZS5hdHRyKCdhcmlhLWV4cGFuZGVkJywgdHJ1ZSlcblx0XHRcdFx0XHRcdC5vbignY2xpY2snLCBzZWxmLnRvZ2dsZV9jb2xsYXBzZSk7XG5cblx0XHRcdFx0XHRzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRcdG1lc2gucG9pbnRlcnMuc2hvd19wb2ludGVyKCk7XG5cdFx0XHRcdFx0fSwgMjUwKTtcblxuXHRcdFx0XHRcdHNlbGYuZW5hYmxlX2NvbnRyb2xzKCRtZXRhX2JveF9jb250YWluZXIpO1xuXG5cdFx0XHRcdFx0JG1ldGFfYm94X2NvbnRhaW5lci50cmlnZ2VyKFwibWVzaDphZGRfc2VjdGlvblwiKTtcblxuXHRcdFx0XHRcdGJsb2Nrcy5zZXR1cF9zb3J0YWJsZSgpO1xuXG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0JHNwaW5uZXIucmVtb3ZlQ2xhc3MoJ2lzLWFjdGl2ZScpO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0dmFyIHdpbmRvd0JvdHRvbSA9ICQod2luZG93KS5oZWlnaHQoKSArICQod2luZG93KS5zY3JvbGxUb3AoKSxcblx0XHRcdFx0XHRtZXNoQm90dG9tID0gJG1lc2hTZWN0aW9uc0NvbnRhaW5lci5vZmZzZXQoKS50b3AgKyAkbWVzaFNlY3Rpb25zQ29udGFpbmVyLm91dGVySGVpZ2h0KHRydWUpLFxuXHRcdFx0XHRcdHNjcm9sbFRpbWluZyA9ICggKCBtZXNoQm90dG9tIC0gd2luZG93Qm90dG9tICkgKiAuNSApO1xuXG5cdFx0XHRcdGlmICgxMDAwID4gc2Nyb2xsVGltaW5nKSB7XG5cdFx0XHRcdFx0c2Nyb2xsVGltaW5nID0gMTAwMDtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGlmICgzMDAwIDwgc2Nyb2xsVGltaW5nKSB7XG5cdFx0XHRcdFx0c2Nyb2xsVGltaW5nID0gMzAwMDtcblx0XHRcdFx0fVxuXG5cblx0XHRcdFx0JCgnaHRtbCwgYm9keScpLmFuaW1hdGUoe1xuXHRcdFx0XHRcdHNjcm9sbFRvcDogJG1lc2hTZWN0aW9uc0NvbnRhaW5lci5vZmZzZXQoKS50b3AgKyAkbWVzaFNlY3Rpb25zQ29udGFpbmVyLm91dGVySGVpZ2h0KHRydWUpIC0gJCh3aW5kb3cpLmhlaWdodCgpXG5cdFx0XHRcdH0sIHNjcm9sbFRpbWluZyk7XG5cdFx0XHR9KTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogUHVibGlzaCB0aGUgY3VycmVudCBzZWN0aW9uXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS4wLjBcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSBldmVudFxuXHRcdCAqL1xuXHRcdHNlY3Rpb25fcHVibGlzaDogZnVuY3Rpb24gKGV2ZW50KSB7XG5cdFx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdFx0ZXZlbnQuc3RvcFByb3BhZ2F0aW9uKCk7XG5cblx0XHRcdHZhciAkc2VjdGlvbiA9ICQodGhpcykuY2xvc2VzdCgnLm1lc2gtc2VjdGlvbicpLFxuXHRcdFx0XHQkcG9zdF9zdGF0dXNfZmllbGQgPSAkKCcubWVzaC1zZWN0aW9uLXN0YXR1cycsICRzZWN0aW9uKSxcblx0XHRcdFx0JHBvc3Rfc3RhdHVzX2xhYmVsID0gJCgnLm1lc2gtc2VjdGlvbi1zdGF0dXMtdGV4dCcsICRzZWN0aW9uKSxcblx0XHRcdFx0JHVwZGF0ZV9idXR0b24gPSAkKCcubWVzaC1zZWN0aW9uLXVwZGF0ZScsICRzZWN0aW9uKTtcblxuXHRcdFx0JHBvc3Rfc3RhdHVzX2ZpZWxkLnZhbCgncHVibGlzaCcpO1xuXHRcdFx0JHBvc3Rfc3RhdHVzX2xhYmVsLnRleHQobWVzaF9kYXRhLnN0cmluZ3MucHVibGlzaGVkKTtcblx0XHRcdCR1cGRhdGVfYnV0dG9uLnRyaWdnZXIoJ2NsaWNrJyk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFNhdmUgYSBkcmFmdCBvZiB0aGUgY3VycmVudCBzZWN0aW9uXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS4wLjBcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSBldmVudFxuXHRcdCAqL1xuXHRcdHNlY3Rpb25fc2F2ZV9kcmFmdDogZnVuY3Rpb24gKGV2ZW50KSB7XG5cdFx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdFx0ZXZlbnQuc3RvcFByb3BhZ2F0aW9uKCk7XG5cblx0XHRcdHZhciAkc2VjdGlvbiA9ICQodGhpcykuY2xvc2VzdCgnLm1lc2gtc2VjdGlvbicpLFxuXHRcdFx0XHQkdXBkYXRlX2J1dHRvbiA9ICQoJy5tZXNoLXNlY3Rpb24tdXBkYXRlJywgJHNlY3Rpb24pO1xuXG5cdFx0XHQkdXBkYXRlX2J1dHRvbi50cmlnZ2VyKCdjbGljaycpO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBTYXZlIHRoZSBjdXJyZW50IHNlY3Rpb24gdGhyb3VnaCBhbiBhamF4IGNhbGxcblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjAuMFxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIGV2ZW50XG5cdFx0ICovXG5cdFx0c2VjdGlvbl9zYXZlOiBmdW5jdGlvbiAoZXZlbnQpIHtcblx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuXHRcdFx0dmFyICRidXR0b24gPSAkKHRoaXMpLFxuXHRcdFx0XHQkYnV0dG9uX2NvbnRhaW5lciA9ICRidXR0b24ucGFyZW50KCksXG5cdFx0XHRcdCRzcGlubmVyID0gJGJ1dHRvbl9jb250YWluZXIuZmluZCgnLnNwaW5uZXInKSxcblx0XHRcdFx0JHNhdmVkX3N0YXR1cyA9ICRidXR0b25fY29udGFpbmVyLmZpbmQoJy5zYXZlZC1zdGF0dXMtaWNvbicpLFxuXHRcdFx0XHQkY3VycmVudF9zZWN0aW9uID0gJGJ1dHRvbi5jbG9zZXN0KCcubWVzaC1zZWN0aW9uJyksXG5cdFx0XHRcdCRwb3N0X3N0YXR1c19maWVsZCA9ICRjdXJyZW50X3NlY3Rpb24uZmluZCgnLm1lc2gtc2VjdGlvbi1zdGF0dXMnKSxcblx0XHRcdFx0c2VjdGlvbl9pZCA9ICRjdXJyZW50X3NlY3Rpb24uYXR0cignZGF0YS1tZXNoLXNlY3Rpb24taWQnKTtcblxuXHRcdFx0JGN1cnJlbnRfc2VjdGlvbi5maW5kKCcubWVzaC1lZGl0b3ItYmxvY2tzIC53cC1lZGl0b3ItYXJlYScpLmVhY2goZnVuY3Rpb24gKCkge1xuXG5cdFx0XHRcdHZhciBjb250ZW50ID0gJycsXG5cdFx0XHRcdFx0ZWRpdG9ySUQgPSAkKHRoaXMpLmF0dHIoJ2lkJyksXG5cdFx0XHRcdFx0ZWRpdG9yID0gdGlueW1jZS5nZXQoZWRpdG9ySUQpO1xuXG5cdFx0XHRcdC8vIE1ha2Ugc3VyZSB3ZSBoYXZlIGFuIGVkaXRvciBhbmQgd2UgYXJlbid0IGluIHRleHQgdmlldy5cblx0XHRcdFx0aWYgKGVkaXRvciAmJiAhZWRpdG9yLmhpZGRlbikge1xuXG5cdFx0XHRcdFx0Y29udGVudCA9IGVkaXRvci5nZXRDb250ZW50KCk7XG5cblx0XHRcdFx0XHQkKCcjJyArIGVkaXRvcklEKS52YWwoY29udGVudCk7XG5cdFx0XHRcdH1cblxuXHRcdFx0fSk7XG5cblx0XHRcdHZhciBmb3JtX2RhdGEgPSAkY3VycmVudF9zZWN0aW9uLnBhcmVudHMoJ2Zvcm0nKS5zZXJpYWxpemUoKSxcblx0XHRcdFx0Zm9ybV9zdWJtaXRfZGF0YSA9IFtdO1xuXG5cdFx0XHQkYnV0dG9uX2NvbnRhaW5lci5maW5kKCcuYnV0dG9uJykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHQkc3Bpbm5lci5hZGRDbGFzcygnaXMtYWN0aXZlJyk7XG5cblx0XHRcdCQucG9zdChhamF4dXJsLCB7XG5cdFx0XHRcdGFjdGlvbjogJ21lc2hfc2F2ZV9zZWN0aW9uJyxcblx0XHRcdFx0bWVzaF9zZWN0aW9uX2lkOiBzZWN0aW9uX2lkLFxuXHRcdFx0XHRtZXNoX3NlY3Rpb25fZGF0YTogZm9ybV9kYXRhLFxuXHRcdFx0XHRtZXNoX3Bvc3RfdHlwZTogbWVzaF9kYXRhLnBvc3RfdHlwZSxcblx0XHRcdFx0bWVzaF9zYXZlX3NlY3Rpb25fbm9uY2U6IG1lc2hfZGF0YS5zYXZlX3NlY3Rpb25fbm9uY2Vcblx0XHRcdH0sIGZ1bmN0aW9uIChyZXNwb25zZSkge1xuXG5cdFx0XHRcdHZhciAkYnV0dG9uID0gJGJ1dHRvbl9jb250YWluZXIuZmluZCgnLmJ1dHRvbicpO1xuXG5cdFx0XHRcdCRidXR0b24ucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHRcdCRzcGlubmVyLnJlbW92ZUNsYXNzKCdpcy1hY3RpdmUnKTtcblx0XHRcdFx0JHNhdmVkX3N0YXR1cy5hZGRDbGFzcyhcImlzLWFjdGl2ZVwiKS5kZWxheSgyMDAwKS5xdWV1ZShmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0JCh0aGlzKS5yZW1vdmVDbGFzcyhcImlzLWFjdGl2ZVwiKS5kZXF1ZXVlKCk7XG5cdFx0XHRcdH0pO1xuXG5cdFx0XHRcdGlmIChyZXNwb25zZSkge1xuXG5cdFx0XHRcdFx0dmFyICRwdWJsaXNoX2RyYWZ0ID0gJGN1cnJlbnRfc2VjdGlvbi5maW5kKCcubWVzaC1zZWN0aW9uLXB1Ymxpc2gsIC5tZXNoLXNlY3Rpb24tc2F2ZS1kcmFmdCcpO1xuXG5cdFx0XHRcdFx0aWYgKCAncHVibGlzaCcgPT09ICRwb3N0X3N0YXR1c19maWVsZC52YWwoKSApIHtcblx0XHRcdFx0XHRcdCRidXR0b24ucmVtb3ZlQ2xhc3MoJ2hpZGRlbicpO1xuXHRcdFx0XHRcdFx0JHB1Ymxpc2hfZHJhZnQuYWRkQ2xhc3MoJ2hpZGRlbicpO1xuXHRcdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0XHQkYnV0dG9uLmFkZENsYXNzKCdoaWRkZW4nKTtcblx0XHRcdFx0XHRcdCRwdWJsaXNoX2RyYWZ0LnJlbW92ZUNsYXNzKCdoaWRkZW4nKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH0pO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBSZW1vdmUgdGhlIHNlY3Rpb25cblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAwLjEuMFxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIGV2ZW50XG5cdFx0ICovXG5cdFx0cmVtb3ZlX3NlY3Rpb246IGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG5cdFx0XHR2YXIgY29uZmlybV9yZW1vdmUgPSBjb25maXJtKG1lc2hfZGF0YS5zdHJpbmdzLmNvbmZpcm1fcmVtb3ZlKTtcblxuXHRcdFx0aWYgKCFjb25maXJtX3JlbW92ZSkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdHZhciAkdGhpcyA9ICQodGhpcyksXG5cdFx0XHRcdCRwb3N0Ym94ID0gJHRoaXMucGFyZW50cygnLm1lc2gtcG9zdGJveCcpLFxuXHRcdFx0XHQkc3Bpbm5lciA9ICQoJy5tZXNoLWFkZC1zcGlubmVyJywgJHBvc3Rib3gpLFxuXHRcdFx0XHRzZWN0aW9uX2lkID0gJHBvc3Rib3guYXR0cignZGF0YS1tZXNoLXNlY3Rpb24taWQnKTtcblxuXHRcdFx0JHNwaW5uZXIuYWRkQ2xhc3MoJ2lzLWFjdGl2ZScpO1xuXG5cdFx0XHQkLnBvc3QoYWpheHVybCwge1xuXHRcdFx0XHRhY3Rpb246ICdtZXNoX3JlbW92ZV9zZWN0aW9uJyxcblx0XHRcdFx0bWVzaF9wb3N0X2lkOiBtZXNoX2RhdGEucG9zdF9pZCxcblx0XHRcdFx0bWVzaF9zZWN0aW9uX2lkOiBzZWN0aW9uX2lkLFxuXHRcdFx0XHRtZXNoX3JlbW92ZV9zZWN0aW9uX25vbmNlOiBtZXNoX2RhdGEucmVtb3ZlX3NlY3Rpb25fbm9uY2Vcblx0XHRcdH0sIGZ1bmN0aW9uIChyZXNwb25zZSkge1xuXHRcdFx0XHRpZiAoJzEnID09PSByZXNwb25zZSkge1xuXHRcdFx0XHRcdCRwb3N0Ym94LmZhZGVPdXQoNDAwLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHQkcG9zdGJveC5yZW1vdmUoKTtcblxuXHRcdFx0XHRcdFx0dmFyICRwb3N0Ym94ZXMgPSAkbWV0YV9ib3hfY29udGFpbmVyLmZpbmQoJy5tZXNoLXNlY3Rpb24nKTtcblxuXHRcdFx0XHRcdFx0aWYgKCRwb3N0Ym94ZXMubGVuZ3RoIDw9IDEpIHtcblx0XHRcdFx0XHRcdFx0JHJlb3JkZXJfYnV0dG9uLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdH0pO1xuXHRcdFx0XHR9IGVsc2UgaWYgKCctMScgPT09IHJlc3BvbnNlKSB7XG5cdFx0XHRcdFx0Y29uc29sZS5sb2coJ1RoZXJlIHdhcyBhbiBlcnJvcicpO1xuXHRcdFx0XHR9IGVsc2Uge1xuXG5cdFx0XHRcdFx0dmFyICRyZXNwb25zZSA9ICQocmVzcG9uc2UpLFxuXHRcdFx0XHRcdFx0JGNvbnRyb2xzID0gJCgnLm1lc2gtbWFpbi11YS1yb3cnKSxcblx0XHRcdFx0XHRcdCRkZXNjcmlwdGlvbiA9ICQoJyNtZXNoLWRlc2NyaXB0aW9uJyk7XG5cblx0XHRcdFx0XHQvLyBBZGQgZWl0aGVyIHRoZSBlbXB0eSBtZXNzYWdlIG9yIHZpc2libGUgc2VjdGlvbnMuXG5cdFx0XHRcdFx0aWYgKHJlc3BvbnNlLmluZGV4T2YoJ21lc2gtZW1wdHktYWN0aW9ucycpID09PSAtMSkge1xuXHRcdFx0XHRcdFx0JHNlY3Rpb25fY29udGFpbmVyLmFwcGVuZCgkcmVzcG9uc2UpO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdCRwb3N0Ym94LmZhZGVPdXQoNDAwLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHQkcG9zdGJveC5yZW1vdmUoKTtcblxuXHRcdFx0XHRcdFx0aWYgKHJlc3BvbnNlLmluZGV4T2YoJ21lc2gtZW1wdHktYWN0aW9ucycpID4gMCkge1xuXHRcdFx0XHRcdFx0XHQkZGVzY3JpcHRpb24uaHRtbCgnJykuYXBwZW5kKCRyZXNwb25zZSkuc2hvdygpO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdH0pO1xuXG5cdFx0XHRcdFx0JGNvbnRyb2xzLmZhZGVPdXQoJ2Zhc3QnKTtcblxuXHRcdFx0XHRcdCRzcGlubmVyLnJlbW92ZUNsYXNzKCdpcy1hY3RpdmUnKTtcblx0XHRcdFx0fVxuXHRcdFx0fSk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFNhdmUgd2hlbiBzZWN0aW9ucyBhcmUgcmVvcmRlcmVkXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMC4xLjBcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSBldmVudFxuXHRcdCAqL1xuXHRcdHJlb3JkZXJfc2VjdGlvbnM6IGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG5cdFx0XHR2YXIgJHRoaXMgPSAkKHRoaXMpO1xuXG5cdFx0XHRpZiAoJHRoaXMuaGFzQ2xhc3MoJ2Rpc2FibGVkJykpIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRzZWxmLmRpc2FibGVfY29udHJvbHMoJG1ldGFfYm94X2NvbnRhaW5lcik7XG5cblx0XHRcdCRtZXRhX2JveF9jb250YWluZXIuYWRkQ2xhc3MoJ21lc2gtaXMtb3JkZXJpbmcnKTtcblxuXHRcdFx0Ly8gc2VsZi51cGRhdGVfbm90aWZpY2F0aW9ucyggJ3Jlb3JkZXInLCAnd2FybmluZycgKTtcblxuXHRcdFx0JHJlb3JkZXJfYnV0dG9uXG5cdFx0XHRcdC50ZXh0KG1lc2hfZGF0YS5zdHJpbmdzLnNhdmVfb3JkZXIpXG5cdFx0XHRcdC5hZGRDbGFzcygnbWVzaC1zYXZlLW9yZGVyIGJ1dHRvbi1wcmltYXJ5Jylcblx0XHRcdFx0LnJlbW92ZUNsYXNzKCdtZXNoLXNlY3Rpb24tcmVvcmRlcicpO1xuXG5cdFx0XHRzZWxmLmNvbGxhcHNlX2FsbF9zZWN0aW9ucygpO1xuXHRcdFx0JHNlY3Rpb25fY29udGFpbmVyLnNvcnRhYmxlKCk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIFV0aWxpdHkgbWV0aG9kIHRvIGRpc3BsYXkgbm90aWZpY2F0aW9uIGluZm9ybWF0aW9uXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMC4zLjBcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSBtZXNzYWdlIFRoZSBtZXNzYWdlIHRvIGRpc3BsYXlcblx0XHQgKiBAcGFyYW0gdHlwZSAgICBUaGUgdHlwZSBvZiBtZXNzYWdlIHRvIGRpc3BsYXkgKHdhcm5pbmd8aW5mb3xzdWNjZXNzKVxuXHRcdCAqL1xuXHRcdHVwZGF0ZV9ub3RpZmljYXRpb25zOiBmdW5jdGlvbiAobWVzc2FnZSwgdHlwZSkge1xuXG5cdFx0XHQkZGVzY3JpcHRpb25cblx0XHRcdFx0LnJlbW92ZUNsYXNzKCdub3RpY2UtaW5mbyBub3RpY2Utd2FybmluZyBub3RpY2Utc3VjY2VzcycpXG5cdFx0XHRcdC5hZGRDbGFzcygnbm90aWNlLScgKyB0eXBlKVxuXHRcdFx0XHQuZmluZCgncCcpXG5cdFx0XHRcdC50ZXh0KG1lc2hfZGF0YS5zdHJpbmdzW21lc3NhZ2VdKTtcblxuXHRcdFx0aWYgKCEkZGVzY3JpcHRpb24uaXMoJzp2aXNpYmxlJykpIHtcblx0XHRcdFx0JGRlc2NyaXB0aW9uLmNzcyh7J29wYWNpdHknOiAwfSkuc2hvdygpO1xuXHRcdFx0fVxuXG5cdFx0XHQkZGVzY3JpcHRpb24uZmFkZUluKCdmYXN0Jyk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEF1dG9zYXZlIGNhbGxiYWNrXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS4wLjBcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSBldmVudFxuXHRcdCAqIEBwYXJhbSB1aVxuXHRcdCAqL1xuXHRcdHNhdmVfc2VjdGlvbl9vcmRlcl9zb3J0YWJsZTogZnVuY3Rpb24gKGV2ZW50LCB1aSkge1xuXHRcdFx0dmFyICRyZW9yZGVyX3NwaW5uZXIgPSAkKCcubWVzaC1yZW9yZGVyLXNwaW5uZXInKSxcblx0XHRcdFx0c2VjdGlvbl9pZHMgPSBbXTtcblxuXHRcdFx0JHJlb3JkZXJfc3Bpbm5lci5hZGRDbGFzcygnaXMtYWN0aXZlJyk7XG5cblx0XHRcdCQoJy5tZXNoLXBvc3Rib3gnLCAkc2VjdGlvbl9jb250YWluZXIpLmVhY2goZnVuY3Rpb24gKGluZGV4KSB7XG5cblx0XHRcdFx0dmFyICR0aGlzID0gJCh0aGlzKTtcblxuXHRcdFx0XHRzZWN0aW9uX2lkcy5wdXNoKCR0aGlzLmF0dHIoJ2RhdGEtbWVzaC1zZWN0aW9uLWlkJykpO1xuXG5cdFx0XHRcdCR0aGlzLmZpbmQoJy5zZWN0aW9uLW1lbnUtb3JkZXInKS52YWwoaW5kZXgpO1xuXHRcdFx0fSk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEluaXRpYXRlIHNhdmluZyB0aGUgc2VjdGlvbiBvcmRlclxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIGV2ZW50XG5cdFx0ICovXG5cdFx0c2F2ZV9zZWN0aW9uX29yZGVyOiBmdW5jdGlvbiAoZXZlbnQpIHtcblx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuXHRcdFx0dmFyICR0aGlzID0gJCh0aGlzKSxcblx0XHRcdFx0JHJlb3JkZXJfc3Bpbm5lciA9ICQoJy5tZXNoLXJlb3JkZXItc3Bpbm5lcicpLFxuXHRcdFx0XHRzZWN0aW9uX2lkcyA9IFtdO1xuXG5cdFx0XHQkcmVvcmRlcl9zcGlubmVyLmFkZENsYXNzKCdpcy1hY3RpdmUnKTtcblxuXHRcdFx0JG1ldGFfYm94X2NvbnRhaW5lci5yZW1vdmVDbGFzcygnbWVzaC1pcy1vcmRlcmluZycpO1xuXG5cdFx0XHRzZWxmLmVuYWJsZV9jb250cm9scygkbWV0YV9ib3hfY29udGFpbmVyKTtcblxuXHRcdFx0JHJlb3JkZXJfYnV0dG9uXG5cdFx0XHRcdC50ZXh0KG1lc2hfZGF0YS5zdHJpbmdzLnJlb3JkZXIpXG5cdFx0XHRcdC5hZGRDbGFzcygnbWVzaC1zZWN0aW9uLXJlb3JkZXInKVxuXHRcdFx0XHQucmVtb3ZlQ2xhc3MoJ21lc2gtc2F2ZS1vcmRlciBidXR0b24tcHJpbWFyeScpO1xuXG5cdFx0XHQkKCcubWVzaC1wb3N0Ym94JywgJHNlY3Rpb25fY29udGFpbmVyKS5lYWNoKGZ1bmN0aW9uIChpbmRleCkge1xuXHRcdFx0XHR2YXIgJHRoaXMgPSAkKHRoaXMpO1xuXG5cdFx0XHRcdHNlY3Rpb25faWRzLnB1c2goJHRoaXMuYXR0cignZGF0YS1tZXNoLXNlY3Rpb24taWQnKSk7XG5cblx0XHRcdFx0JHRoaXMuZmluZCgnLnNlY3Rpb24tbWVudS1vcmRlcicpLnZhbChpbmRleCk7XG5cdFx0XHR9KTtcblxuXHRcdFx0aWYgKCRkZXNjcmlwdGlvbi5pcygnOnZpc2libGUnKSkge1xuXHRcdFx0XHQkZGVzY3JpcHRpb24ucmVtb3ZlQ2xhc3MoJ25vdGljZS13YXJuaW5nJykuYWRkQ2xhc3MoJ25vdGljZS1pbmZvJykuZmluZCgncCcpLnRleHQobWVzaF9kYXRhLnN0cmluZ3MuZGVzY3JpcHRpb24pO1xuXHRcdFx0fVxuXG5cdFx0XHRzZWxmLnNhdmVfc2VjdGlvbl9hamF4KHNlY3Rpb25faWRzLCAkcmVvcmRlcl9zcGlubmVyKTtcblxuXHRcdFx0JHNlY3Rpb25fY29udGFpbmVyLnNvcnRhYmxlKCdkZXN0cm95Jyk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEFKQVggY2FsbCB0byBzYXZlIHNlY3Rpb24uXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0gc2VjdGlvbl9pZHNcblx0XHQgKiBAcGFyYW0gJGN1cnJlbnRfc3Bpbm5lclxuXHRcdCAqL1xuXHRcdHNhdmVfc2VjdGlvbl9hamF4OiBmdW5jdGlvbiAoc2VjdGlvbl9pZHMsICRjdXJyZW50X3NwaW5uZXIpIHtcblx0XHRcdCQucG9zdChhamF4dXJsLCB7XG5cdFx0XHRcdCdhY3Rpb24nOiAnbWVzaF91cGRhdGVfb3JkZXInLFxuXHRcdFx0XHQnbWVzaF9wb3N0X2lkJzogcGFyc2VJbnQobWVzaF9kYXRhLnBvc3RfaWQpLFxuXHRcdFx0XHQnbWVzaF9zZWN0aW9uX2lkcyc6IHNlY3Rpb25faWRzLFxuXHRcdFx0XHQnbWVzaF9yZW9yZGVyX3NlY3Rpb25fbm9uY2UnOiBtZXNoX2RhdGEucmVvcmRlcl9zZWN0aW9uX25vbmNlXG5cdFx0XHR9LCBmdW5jdGlvbiAocmVzcG9uc2UpIHtcblx0XHRcdFx0JGN1cnJlbnRfc3Bpbm5lci5yZW1vdmVDbGFzcygnaXMtYWN0aXZlJyk7XG5cdFx0XHR9KTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogSGFuZGxlIHRoZSB0b2dnbGUgYmVlbiB0ZXh0IGFuZCBpbnB1dCBhcmVhc1xuXHRcdCAqXG5cdFx0ICogQHBhcmFtIGV2ZW50XG5cdFx0ICovXG5cdFx0Y2hhbmdlX2lucHV0X3RpdGxlOiBmdW5jdGlvbiAoZXZlbnQpIHtcblx0XHRcdHZhciAkdGhpcyA9ICQodGhpcyk7XG5cblx0XHRcdGlmICgkdGhpcy5wYXJlbnRzKCcubWVzaC1wb3N0Ym94JykuaGFzQ2xhc3MoJ2Nsb3NlZCcpKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0dmFyIGN1cnJlbnRfdGl0bGUgPSAkdGhpcy52YWwoKSxcblx0XHRcdFx0JGhhbmRsZV90aXRsZSA9ICR0aGlzLnNpYmxpbmdzKCcuaGFuZGxlLXRpdGxlJyk7XG5cblx0XHRcdGlmICgkdGhpcy5pcygnc2VsZWN0JykpIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRpZiAoY3VycmVudF90aXRsZSA9PT0gJycgfHwgY3VycmVudF90aXRsZSA9PSAndW5kZWZpbmVkJykge1xuXHRcdFx0XHRjdXJyZW50X3RpdGxlID0gbWVzaF9kYXRhLnN0cmluZ3MuZGVmYXVsdF90aXRsZTtcblx0XHRcdH1cblxuXHRcdFx0JGhhbmRsZV90aXRsZS50ZXh0KGN1cnJlbnRfdGl0bGUpO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBDaGFuZ2UgdGhlIHRpdGxlIG9uIG91ciBzZWxlY3QgZmllbGRcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSBldmVudFxuXHRcdCAqL1xuXHRcdGNoYW5nZV9zZWxlY3RfdGl0bGU6IGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0dmFyICR0aGlzID0gJCh0aGlzKSxcblx0XHRcdFx0Y3VycmVudF90aXRsZSA9ICR0aGlzLnZhbCgpLFxuXHRcdFx0XHQkaGFuZGxlX3RpdGxlID0gJHRoaXMuc2libGluZ3MoJy5oYW5kbGUtdGl0bGUnKTtcblxuXHRcdFx0c3dpdGNoIChjdXJyZW50X3RpdGxlKSB7XG5cdFx0XHRcdGNhc2UgJ3B1Ymxpc2gnOlxuXHRcdFx0XHRcdGN1cnJlbnRfdGl0bGUgPSBtZXNoX2RhdGEuc3RyaW5ncy5wdWJsaXNoZWQ7XG5cdFx0XHRcdFx0YnJlYWs7XG5cblx0XHRcdFx0Y2FzZSAnZHJhZnQnOlxuXHRcdFx0XHRcdGN1cnJlbnRfdGl0bGUgPSBtZXNoX2RhdGEuc3RyaW5ncy5kcmFmdDtcblx0XHRcdH1cblxuXHRcdFx0JGhhbmRsZV90aXRsZS50ZXh0KGN1cnJlbnRfdGl0bGUpO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBQcmV2ZW50IHN1Ym1pdHRpbmcgdGhlIHBvc3QvcGFnZSB3aGVuIGhpdHRpbmcgZW50ZXJcblx0XHQgKiB3aGlsZSBmb2N1c2VkIG9uIGEgc2VjdGlvbiBvciBibG9jayBmb3JtIGVsZW1lbnRcblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjAuMFxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIGV2ZW50XG5cdFx0ICovXG5cdFx0cHJldmVudF9zdWJtaXQ6IGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0aWYgKDEzID09IGV2ZW50LmtleUNvZGUpIHtcblx0XHRcdFx0JCh0aGlzKS5zaWJsaW5ncygnLmNsb3NlLXRpdGxlLWVkaXQnKS50cmlnZ2VyKCdjbGljaycpO1xuXG5cdFx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cblx0XHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdFx0fVxuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBCbG9jayBvdXIgY2xpY2sgZXZlbnQgd2hpbGUgcmVvcmRlcmluZ1xuXHRcdCAqXG5cdFx0ICogQHNpbmNlIDAuMS4wXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0gZXZlbnRcblx0XHQgKi9cblx0XHRibG9ja19jbGljazogZnVuY3Rpb24gKGV2ZW50KSB7XG5cdFx0XHRldmVudC5zdG9wSW1tZWRpYXRlUHJvcGFnYXRpb24oKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogUmVtb3ZlIG91ciBzZWxlY3RlZCBiYWNrZ3JvdW5kXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMC4zLjZcblx0XHQgKlxuXHRcdCAqIEBwYXJhbSBldmVudFxuXHRcdCAqL1xuXHRcdHJlbW92ZV9iYWNrZ3JvdW5kOiBmdW5jdGlvbiAoZXZlbnQpIHtcblxuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG5cdFx0XHR2YXIgJGJ1dHRvbiA9ICQodGhpcyksXG5cdFx0XHRcdCRwYXJlbnRfY29udGFpbmVyID0gJGJ1dHRvbi5wYXJlbnRzKCcubWVzaC1zZWN0aW9uLWJhY2tncm91bmQnKTtcblxuXHRcdFx0aWYgKCRidXR0b24ucHJldigpLmhhc0NsYXNzKCdyaWdodCcpICYmICEkYnV0dG9uLnByZXYoKS5oYXNDbGFzcygnYnV0dG9uJykpIHtcblx0XHRcdFx0aWYgKCEkYnV0dG9uLnBhcmVudHMoJy5ibG9jay1iYWNrZ3JvdW5kLWNvbnRhaW5lcicpKSB7XG5cdFx0XHRcdFx0JGJ1dHRvbi5wcmV2KCkudG9nZ2xlQ2xhc3MoJ2J1dHRvbiByaWdodCcpO1xuXHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdCRidXR0b24ucHJldigpLnRvZ2dsZUNsYXNzKCdyaWdodCcpLmF0dHIoJ2RhdGEtbWVzaC1ibG9jay1mZWF0dXJlZC1pbWFnZScsICcnKTtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXG5cdFx0XHQkYnV0dG9uLnNpYmxpbmdzKCdpbnB1dFt0eXBlPVwiaGlkZGVuXCJdJykudmFsKCcnKTtcblxuXHRcdFx0JGJ1dHRvbi5wcmV2KCkudGV4dChtZXNoX2RhdGEuc3RyaW5ncy5hZGRfaW1hZ2UpO1xuXHRcdFx0JGJ1dHRvbi5yZW1vdmUoKTtcbiAgICAgICAgICAgICRwYXJlbnRfY29udGFpbmVyLnJlbW92ZUNsYXNzKCdoYXMtYmFja2dyb3VuZC1zZXQnKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQ2hvb3NlIHRoZSBiYWNrZ3JvdW5kIGZvciBvdXIgc2VjdGlvblxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIGV2ZW50XG5cdFx0ICovXG5cdFx0Y2hvb3NlX2JhY2tncm91bmQ6IGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG5cdFx0XHR2YXIgJGJ1dHRvbiA9ICQodGhpcyksXG5cdFx0XHRcdCRzZWN0aW9uID0gJGJ1dHRvbi5wYXJlbnRzKCcubWVzaC1wb3N0Ym94JyksXG5cdFx0XHRcdHNlY3Rpb25faWQgPSBwYXJzZUludCgkc2VjdGlvbi5hdHRyKCdkYXRhLW1lc2gtc2VjdGlvbi1pZCcpKSxcblx0XHRcdFx0ZnJhbWVfaWQgPSAnbWVzaC1iYWNrZ3JvdW5kLXNlbGVjdC0nICsgc2VjdGlvbl9pZCxcblx0XHRcdFx0Y3VycmVudF9pbWFnZSA9IHBhcnNlSW50KCAkYnV0dG9uLnBhcmVudCgpLmZpbmQoJy5tZXNoLXNlY3Rpb24tYmFja2dyb3VuZC1pbnB1dCcpLnZhbCgpICksXG5cdFx0XHRcdCRwYXJlbnRfY29udGFpbmVyID0gJGJ1dHRvbi5wYXJlbnRzKCcubWVzaC1zZWN0aW9uLWJhY2tncm91bmQnKTtcblxuXHRcdFx0Ly8gSWYgdGhlIGZyYW1lIGFscmVhZHkgZXhpc3RzLCByZS1vcGVuIGl0LlxuXHRcdFx0aWYgKG1lZGlhX2ZyYW1lc1tmcmFtZV9pZF0pIHtcblx0XHRcdFx0bWVkaWFfZnJhbWVzW2ZyYW1lX2lkXS51cGxvYWRlci51cGxvYWRlci5wYXJhbSgnbWVzaF91cGxvYWQnLCAndHJ1ZScpO1xuXHRcdFx0XHRtZWRpYV9mcmFtZXNbZnJhbWVfaWRdLm9wZW4oKTtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHQvKipcblx0XHRcdCAqIFRoZSBtZWRpYSBmcmFtZSBkb2Vzbid0IGV4aXN0IGxldCwgc28gbGV0J3MgY3JlYXRlIGl0IHdpdGggc29tZSBvcHRpb25zLlxuXHRcdFx0ICovXG5cdFx0XHRtZWRpYV9mcmFtZXNbZnJhbWVfaWRdID0gd3AubWVkaWEuZnJhbWVzLm1lZGlhX2ZyYW1lcyA9IHdwLm1lZGlhKHtcblx0XHRcdFx0Y2xhc3NOYW1lOiAnbWVkaWEtZnJhbWUgbWVzaC1tZWRpYS1mcmFtZScsXG5cdFx0XHRcdGZyYW1lOiAnc2VsZWN0Jyxcblx0XHRcdFx0bXVsdGlwbGU6IGZhbHNlLFxuXHRcdFx0XHR0aXRsZTogbWVzaF9kYXRhLnN0cmluZ3Muc2VsZWN0X3NlY3Rpb25fYmcsXG5cdFx0XHRcdGJ1dHRvbjoge1xuXHRcdFx0XHRcdHRleHQ6IG1lc2hfZGF0YS5zdHJpbmdzLnNlbGVjdF9iZ1xuXHRcdFx0XHR9XG5cdFx0XHR9KTtcblxuXHRcdFx0bWVkaWFfZnJhbWVzW2ZyYW1lX2lkXS5vbignb3BlbicsIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0Ly8gR3JhYiBvdXIgYXR0YWNobWVudCBzZWxlY3Rpb24gYW5kIGNvbnN0cnVjdCBhIEpTT04gcmVwcmVzZW50YXRpb24gb2YgdGhlIG1vZGVsLlxuXHRcdFx0XHR2YXIgc2VsZWN0aW9uID0gbWVkaWFfZnJhbWVzW2ZyYW1lX2lkXS5zdGF0ZSgpLmdldCgnc2VsZWN0aW9uJyk7XG5cblx0XHRcdFx0c2VsZWN0aW9uLmFkZCh3cC5tZWRpYS5hdHRhY2htZW50KGN1cnJlbnRfaW1hZ2UpKTtcblx0XHRcdH0pO1xuXG5cdFx0XHRtZWRpYV9mcmFtZXNbZnJhbWVfaWRdLm9uKCdzZWxlY3QnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdC8vIEdyYWIgb3VyIGF0dGFjaG1lbnQgc2VsZWN0aW9uIGFuZCBjb25zdHJ1Y3QgYSBKU09OIHJlcHJlc2VudGF0aW9uIG9mIHRoZSBtb2RlbC5cblx0XHRcdFx0dmFyIG1lZGlhX2F0dGFjaG1lbnQgPSBtZWRpYV9mcmFtZXNbZnJhbWVfaWRdLnN0YXRlKCkuZ2V0KCdzZWxlY3Rpb24nKS5maXJzdCgpLnRvSlNPTigpLFxuXHRcdFx0XHRcdCRlZGl0X2ljb24gPSAkKCc8c3BhbiAvPicsIHtcblx0XHRcdFx0XHRcdCdjbGFzcyc6ICdkYXNoaWNvbnMgZGFzaGljb25zLWVkaXQnXG5cdFx0XHRcdFx0fSksXG5cdFx0XHRcdFx0JHRyYXNoID0gJCgnPGEvPicsIHtcblx0XHRcdFx0XHRcdCdkYXRhLW1lc2gtc2VjdGlvbi1mZWF0dXJlZC1pbWFnZSc6ICcnLFxuXHRcdFx0XHRcdFx0J2hyZWYnOiAnIycsXG5cdFx0XHRcdFx0XHQnY2xhc3MnOiAnbWVzaC1mZWF0dXJlZC1pbWFnZS10cmFzaCBkYXNoaWNvbnMtYmVmb3JlIGRhc2hpY29ucy1kaXNtaXNzJ1xuXHRcdFx0XHRcdH0pO1xuXG5cdFx0XHRcdGN1cnJlbnRfaW1hZ2UgPSBtZWRpYV9hdHRhY2htZW50LmlkO1xuXG5cdFx0XHRcdHZhciAkaW1nID0gJCgnPGltZyAvPicsIHtcblx0XHRcdFx0XHRzcmM6IG1lZGlhX2F0dGFjaG1lbnQudXJsXG5cdFx0XHRcdH0pO1xuXG5cdFx0XHRcdCRidXR0b25cblx0XHRcdFx0XHQuaHRtbCgkaW1nKVxuXHRcdFx0XHRcdC5hdHRyKCdkYXRhLW1lc2gtc2VjdGlvbi1mZWF0dXJlZC1pbWFnZScsIHBhcnNlSW50KG1lZGlhX2F0dGFjaG1lbnQuaWQpKVxuXHRcdFx0XHRcdC5hZnRlcigkdHJhc2gpO1xuXG4gICAgICAgICAgICAgICAgJHBhcmVudF9jb250YWluZXIuYWRkQ2xhc3MoJ2hhcy1iYWNrZ3JvdW5kLXNldCcpO1xuXG5cdFx0XHRcdC8vIEFkZCBzZWxlY3RlZCBhdHRhY2htZW50IGlkIHRvIGlucHV0XG5cdFx0XHRcdCRidXR0b24uc2libGluZ3MoJ2lucHV0W3R5cGU9XCJoaWRkZW5cIl0nKS52YWwobWVkaWFfYXR0YWNobWVudC5pZCk7XG5cblx0XHRcdFx0aWYgKCRidXR0b24uaGFzQ2xhc3MoJ2J1dHRvbicpICYmICEkYnV0dG9uLmhhc0NsYXNzKCdyaWdodCcpKSB7XG5cdFx0XHRcdFx0JGJ1dHRvbi50b2dnbGVDbGFzcygnYnV0dG9uIHJpZ2h0Jyk7XG5cdFx0XHRcdH1cblx0XHRcdH0pO1xuXG5cdFx0XHQvLyBOb3cgdGhhdCBldmVyeXRoaW5nIGhhcyBiZWVuIHNldCwgbGV0J3Mgb3BlbiB1cCB0aGUgZnJhbWUuXG5cdFx0XHRtZWRpYV9mcmFtZXNbZnJhbWVfaWRdLm9wZW4oKTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogQWRkIGFiaWxpdHkgdG8gZXF1YWxpemUgYmxvY2tzXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMC40LjBcblx0XHQgKi9cblx0XHRtZXNoX2VxdWFsaXplOiBmdW5jdGlvbiAoKSB7XG5cblx0XHRcdHZhciAkdGhpcyA9ICQodGhpcyksXG5cdFx0XHRcdCRjaGlsZHMgPSAkKCdbZGF0YS1lcXVhbGl6ZXItd2F0Y2hdJywgJHRoaXMpLFxuXHRcdFx0XHRlcV9oZWlnaHQgPSAwO1xuXG5cdFx0XHQkY2hpbGRzLmVhY2goZnVuY3Rpb24gKCkge1xuXHRcdFx0XHR2YXIgdGhpc19oZWlnaHQgPSAkKHRoaXMpLmhlaWdodCgpO1xuXG5cdFx0XHRcdGVxX2hlaWdodCA9IHRoaXNfaGVpZ2h0ID4gZXFfaGVpZ2h0ID8gdGhpc19oZWlnaHQgOiBlcV9oZWlnaHQ7XG5cdFx0XHR9KS5oZWlnaHQoZXFfaGVpZ2h0KTtcblxuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBSZW1vdmUgYW55IGV4dHJhIG5vbiB2aXNpYmxlIGJsb2NrcyBmcm9tIG91ciBzZWN0aW9uXG5cdFx0ICogdGhyb3VnaCBhbiBhamF4IGNhbGwuXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS4xXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge2V2ZW50fSBldmVudFxuXHRcdCAqIEByZXR1cm5zIHZvaWRcblx0XHQgKi9cblx0XHR0cmFzaF9leHRyYV9ibG9ja3M6IGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG5cdFx0XHR2YXIgJGN1cnJlbnRfc2VjdGlvbiA9ICQodGhpcykuY2xvc2VzdCgnLm1lc2gtc2VjdGlvbicpLFxuXHRcdFx0XHRmb3JtX2RhdGEgPSAkY3VycmVudF9zZWN0aW9uLnBhcmVudHMoJ2Zvcm0nKS5zZXJpYWxpemUoKTtcblxuXHRcdFx0dmFyICR0aGlzID0gJCh0aGlzKSxcblx0XHRcdFx0JHBvc3Rib3ggPSAkdGhpcy5wYXJlbnRzKCcubWVzaC1wb3N0Ym94JyksXG5cdFx0XHRcdHNlY3Rpb25faWQgPSAkcG9zdGJveC5hdHRyKCdkYXRhLW1lc2gtc2VjdGlvbi1pZCcpO1xuXG5cdFx0XHRzZWxmLmRpc2FibGVfY29udHJvbHMoJHBvc3Rib3gpO1xuXG5cdFx0XHQkLnBvc3QoYWpheHVybCwge1xuXHRcdFx0XHRhY3Rpb246ICdtZXNoX3RyYXNoX2hpZGRlbl9ibG9ja3MnLFxuXHRcdFx0XHRtZXNoX3Bvc3RfaWQ6IG1lc2hfZGF0YS5wb3N0X2lkLFxuXHRcdFx0XHRtZXNoX3NlY3Rpb25faWQ6IHNlY3Rpb25faWQsXG5cdFx0XHRcdG1lc2hfc2VjdGlvbl9kYXRhOiBmb3JtX2RhdGEsXG5cdFx0XHRcdG1lc2hfY2hvb3NlX2xheW91dF9ub25jZTogbWVzaF9kYXRhLmNob29zZV9sYXlvdXRfbm9uY2UsXG5cdFx0XHRcdG1lc2hfc2F2ZV9zZWN0aW9uX25vbmNlOiBtZXNoX2RhdGEuc2F2ZV9zZWN0aW9uX25vbmNlXG5cdFx0XHR9LCBmdW5jdGlvbiAocmVzcG9uc2UpIHtcblx0XHRcdFx0aWYgKCcxJyA9PT0gcmVzcG9uc2UpIHtcblxuXHRcdFx0XHRcdHZhciAkbm90aWNlID0gJHBvc3Rib3guZmluZCgnLmRlc2NyaXB0aW9uLm5vdGljZScpO1xuXG5cdFx0XHRcdFx0JG5vdGljZS5mYWRlT3V0KDQwMCwgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRcdFx0JG5vdGljZS5yZW1vdmUoKTtcblx0XHRcdFx0XHR9KTtcblxuXHRcdFx0XHR9IGVsc2UgaWYgKCctMScgPT09IHJlc3BvbnNlKSB7XG5cdFx0XHRcdFx0Y29uc29sZS5sb2coJ1RoZXJlIHdhcyBhbiBlcnJvcicpO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0c2VsZi5lbmFibGVfY29udHJvbHMoJHBvc3Rib3gpO1xuXG5cdFx0XHR9KTtcblx0XHR9LFxuXG5cdFx0LyoqXG5cdFx0ICogRGlzYWJsZSBhbGwgY29udHJvbHMuXG5cdFx0ICpcblx0XHQgKiBUaGlzIGlzIGJlc3QgdXNlZCB3aGVuIHlvdSBhcmUgYXdhaXRpbmcgYVxuXHRcdCAqIHJlc3BvbnNlIGZyb20gYW4gYWpheCBjYWxsIG9yIGlmIHlvdSBhcmUgaW5cblx0XHQgKiBhIG11bHRpIHN0ZXAgb3B0aW9uIHRoYXQgc2hvdWxkbid0IGJlIGludGVycnVwdGVkXG5cdFx0ICogYnkgYW5vdGhlciBhY3Rpb24uXG5cdFx0ICpcblx0XHQgKiBAc2luY2UgMS4xXG5cdFx0ICogQHBhcmFtIHtlbGVtZW50fSAkdGd0IFNlbGVjdGVkIEVsZW1lbnQuXG5cdFx0ICovXG5cdFx0ZGlzYWJsZV9jb250cm9sczogZnVuY3Rpb24gKCR0Z3QpIHtcblx0XHRcdCRleHBhbmRfYnV0dG9uLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0JGFkZF9idXR0b24uYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHQkY29sbGFwc2VfYnV0dG9uLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0JHJlb3JkZXJfYnV0dG9uLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXG5cdFx0XHR2YXIgJHBvc3Rib3hlcyA9ICQoJy5tZXNoLXNlY3Rpb24nLCAkc2VjdGlvbl9jb250YWluZXIpO1xuXG5cdFx0XHRpZiAoJHBvc3Rib3hlcy5sZW5ndGggPiAxKSB7XG5cdFx0XHRcdCRyZW9yZGVyX2J1dHRvbi5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdH1cblxuXHRcdFx0JCgnLmRpc2FibGVkLW92ZXJsYXknKS5yZW1vdmUoKTsgLy8gTWFrZSBzdXJlIHdlIHJlbW92ZSBhbnkgaW5zdGFuY2Ugb2Ygb3VyIG92ZXJsYXkuXG5cblx0XHRcdCR0Z3QuZmluZCgnLmluc2lkZScpLmNzcygncG9zaXRpb24nLCAncmVsYXRpdmUnKS5wcmVwZW5kKCc8ZGl2IGNsYXNzPVwiZGlzYWJsZWQtb3ZlcmxheVwiIC8+Jyk7XG5cdFx0fSxcblxuXHRcdC8qKlxuXHRcdCAqIEVuYWJsZSBhbGwgY29udHJvbHNcblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjFcblx0XHQgKiBAcGFyYW0ge2VsZW1lbnR9ICR0Z3QgQ2xpY2sgRXZlbnRcblx0XHQgKiBAcmV0dXJuIHZvaWRcblx0XHQgKi9cblx0XHRlbmFibGVfY29udHJvbHM6IGZ1bmN0aW9uICgkdGd0KSB7XG5cdFx0XHQkZXhwYW5kX2J1dHRvbi5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdCRhZGRfYnV0dG9uLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0JGNvbGxhcHNlX2J1dHRvbi5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblxuXHRcdFx0dmFyICRwb3N0Ym94ZXMgPSAkKCcubWVzaC1zZWN0aW9uJywgJG1ldGFfYm94X2NvbnRhaW5lcik7XG5cblx0XHRcdGlmICgkcG9zdGJveGVzLmxlbmd0aCA+IDEpIHtcblx0XHRcdFx0JHJlb3JkZXJfYnV0dG9uLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0JHJlb3JkZXJfYnV0dG9uLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0fVxuXG5cdFx0XHQkdGd0LmZpbmQoJy5pbnNpZGUnKS5maW5kKCcuZGlzYWJsZWQtb3ZlcmxheScpLnJlbW92ZSgpO1xuXHRcdH0sXG5cblx0XHQvKipcblx0XHQgKiBBbGxvdyB0aGUgdXNhZ2Ugb2YgRm91bmRhdGlvbiA1IG9yIDYgaW50ZXJjaGFuZ2Vcblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjEuM1xuXHRcdCAqIEBwYXJhbSB7ZXZlbnR9IGV2ZW50IENoYW5nZSBFdmVudFxuXHRcdCAqIEByZXR1cm4gdm9pZFxuXHRcdCAqL1xuXHRcdGRpc3BsYXlfZm91bmRhdGlvbl9vcHRpb25zOiBmdW5jdGlvbiAoZXZlbnQpIHtcblxuXHRcdFx0dmFyIHVzaW5nX2ZvdW5kYXRpb24gPSAkKCcjbWVzaC1jc3NfbW9kZScpLmZpbmQoJ29wdGlvbjpzZWxlY3RlZCcpLnZhbCgpLFxuXHRcdFx0XHQkZm91bmRhdGlvbl92ZXJzaW9uID0gJCgnI21lc2gtZm91bmRhdGlvbl92ZXJzaW9uJyksXG5cdFx0XHRcdCRwYXJlbnRfcm93ID0gJGZvdW5kYXRpb25fdmVyc2lvbi5jbG9zZXN0KCd0cicpLFxuICAgICAgICAgICAgICAgICRmb3VuZGF0aW9uX2dyaWRfc3lzdGVtID0gJCgnI21lc2gtZ3JpZF9zeXN0ZW0nKSxcbiAgICAgICAgICAgICAgICAkZm91bmRhdGlvbl9ncmlkX3N5c3RlbV9yb3cgPSAkZm91bmRhdGlvbl9ncmlkX3N5c3RlbS5jbG9zZXN0KCd0cicpO1xuXG5cdFx0XHRpZiAocGFyc2VJbnQodXNpbmdfZm91bmRhdGlvbikgPT09IDEpIHtcblx0XHRcdFx0JHBhcmVudF9yb3cuc2hvdygpO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0JHBhcmVudF9yb3cuaGlkZSgpO1xuICAgICAgICAgICAgICAgICRmb3VuZGF0aW9uX2dyaWRfc3lzdGVtX3Jvdy5oaWRlKCk7XG4gICAgICAgICAgICAgICAgJGZvdW5kYXRpb25fZ3JpZF9zeXN0ZW0udmFsKCcnKTtcblx0XHRcdFx0JGZvdW5kYXRpb25fdmVyc2lvbi52YWwoJycpO1xuXHRcdFx0fVxuXHRcdH0sXG5cbiAgICAgICAgLyoqXG5cdFx0ICogRGlzcGxheSBvdXIgZ3JpZCBzeXN0ZW0gb3B0aW9ucyBpZiB3ZSBhcmUgdXNpbmcgRm91bmRhdGlvbiA2LjRcblx0XHQgKlxuXHRcdCAqIEBzaW5jZSAxLjIuNVxuXHRcdCAqXG4gICAgICAgICAqIEBwYXJhbSBldmVudFxuICAgICAgICAgKi9cblx0XHRkaXNwbGF5X2ZvdW5kYXRpb25fZ3JpZF9vcHRpb25zOiBmdW5jdGlvbihldmVudCkge1xuICAgICAgICAgICAgdmFyIHVzaW5nX2ZvdW5kYXRpb24gPSAkKCcjbWVzaC1jc3NfbW9kZScpLmZpbmQoJ29wdGlvbjpzZWxlY3RlZCcpLnZhbCgpLFxuICAgICAgICAgICAgICAgICRmb3VuZGF0aW9uX3ZlcnNpb24gPSAkKCcjbWVzaC1mb3VuZGF0aW9uX3ZlcnNpb24nKSxcblx0XHRcdFx0JGZvdW5kYXRpb25fZ3JpZF9zeXN0ZW0gPSAkKCcjbWVzaC1ncmlkX3N5c3RlbScpLFxuICAgICAgICAgICAgXHQkZm91bmRhdGlvbl9ncmlkX3N5c3RlbV9yb3cgPSAkZm91bmRhdGlvbl9ncmlkX3N5c3RlbS5jbG9zZXN0KCd0cicpO1xuXG4gICAgICAgICAgICBpZiAoIHBhcnNlSW50KCB1c2luZ19mb3VuZGF0aW9uICkgPT09IDEgJiYgNi40ID09PSBwYXJzZUZsb2F0KCAkZm91bmRhdGlvbl92ZXJzaW9uLnZhbCgpICkgKSB7XG4gICAgICAgICAgICAgICAgJGZvdW5kYXRpb25fZ3JpZF9zeXN0ZW1fcm93LnNob3coKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgJGZvdW5kYXRpb25fZ3JpZF9zeXN0ZW1fcm93LmhpZGUoKTtcbiAgICAgICAgICAgICAgICAkZm91bmRhdGlvbl9ncmlkX3N5c3RlbS52YWwoJycpO1xuICAgICAgICAgICAgfVxuXHRcdH1cblx0fTtcbn0oalF1ZXJ5KTtcblxualF1ZXJ5KGZ1bmN0aW9uICgkKSB7XG5cdG1lc2guYWRtaW4uaW5pdCgpO1xufSk7XG4vLyMgc291cmNlTWFwcGluZ1VSTD1hZG1pbi1tZXNoLmpzLm1hcCJdLCJmaWxlIjoiYWRtaW4tbWVzaC5qcyJ9
