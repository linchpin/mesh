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

mesh.blocks = function ( $ ) {

    var $body = $('body'),
        // Instance of our block controller
        self,
        admin;

    return {

        /**
         * Initialize out Blocks Administration
         */
        init : function() {

            self = mesh.blocks;
            admin = mesh.admin;

            $body
                .on('click', '.mesh-block-featured-image-trash', self.remove_background )
                .on('click', '.mesh-block-featured-image-choose', self.choose_background )
                .on('click.OpenMediaManager', '.mesh-block-featured-image-choose', self.choose_background )
                .on('click', '.msc-clean-edit:not(.title-input-visible)', self.show_field )
                .on('blur', '.msc-clean-edit-element:not(select)', self.hide_field )
                .on('click', '.close-title-edit', self.hide_field )
                .on('click', '.slide-toggle-element', self.slide_toggle_element )
                .on('change', '.mesh-column-offset', self.display_offset );

            self.setup_resize_slider();
            self.setup_sortable();
        },

        /**
	     * Setup sorting of blocks in the admin
	     *
	     * @since 1.0.0
	     */
        setup_sortable : function () {
	        var column_order = [];

			$('.mesh-editor-blocks .mesh-row').sortable({
				// OPTIONS
				axis      : 'x',
				cursor    : 'move',
				cursorAt  : { left: 0 },
				distance  : 20,
				handle    : '.the-mover',
				items     : '.mesh-section-block',
				tolerance : 'pointer',

				// EVENTS
				create : function ( event, ui ) {
					$('.mesh-editor-blocks .fade-in-on-create').fadeIn('slow');
				},

				start : function ( event, ui ) {
					var $tgt           = $( event.target ),
						$column_slider = $tgt.find( '.column-slider' );

					// Fade out column resizer to avoid odd UI
					$column_slider.fadeOut('fast');

					$('.mesh-section-block:not(.ui-sortable-placeholder)', this).each(function () {
						column_order.push( $(this).attr('class') );
					} );
				},

				stop : function ( event, ui ) {
					var $tgt           = $( event.target ),
						$column_slider = $tgt.find( '.column-slider' );

					// Fade back in column resizer
					$column_slider.fadeIn('slow');
				},

				update : function ( event, ui ) {
					var $this      = $(this),
                        $tgt       = $( event.target),
                        $section   = $tgt.parents('.mesh-section'),
                        section_id = $section.attr('data-mesh-section-id'),
                        $blocks    = $this.find('.mesh-section-block');

					$blocks.each(function ( i ) {
						var $this = $(this);

						$this.removeAttr('class').addClass(column_order[i]);
						$this.find('.block-menu-order').val(i);
					} );

					self.rerender_blocks( $section.find('.wp-editor-area') );
					self.save_order( section_id, event, ui );
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
        change_block_widths : function( event, ui ) {
            var $tgt          = $( event.target ),
                $columns      = $tgt.parent().parent().parent().find('.mesh-editor-blocks').find('.columns').addClass('dragging'),
                column_length = $columns.length,
                column_total  = 12,
                column_values = [],
                slider_values = ui.values,
                post_data     = {
                    post_id : parseInt( mesh_data.post_id ),
                    section_id : parseInt( $tgt.closest('.mesh-section').attr('data-mesh-section-id') ),
                    blocks : {}
                };

			// Set array to store columns widths
			// If returned values are [3, 9]
			// -> col 1 = val1 = 3
			// -> col 2 = (val2 - val1) = (9 - 3) = 6
			// -> col 3 = (avail - val2) = (12 - 9) = 3
			if ( 3 == column_length ) {
				for ( var i = 0; i <= column_length; i++ ) {
					switch ( i ) {
						case 0:
							column_values.push( slider_values[i] );
							break;

						case 1:
							column_values.push(slider_values[i] - slider_values[0]);
							break;

						case 2:
							column_values.push( column_total - slider_values[1] );
							break;
					}
				}
			}

			// Set array to store columns widths
			// If returned value is [4]
			// -> col 1 = val1 = 4
			// -> col 2 = (avail - val1) = (12 - 4) = 8
			if ( 2 == column_length ) {
				column_values.push( slider_values[0] );
				column_values.push( column_total - slider_values[0] );
			}

            // Custom class removal based on regex pattern
            $columns.removeClass (function (index, css) {
                return (css.match (/\mesh-columns-\d+/g) || []).join(' ');
            }).each( function( index ) {
                var $this = $(this),
                    block_id = parseInt( $this.find('.block').attr('data-mesh-block-id') ),
                    $column_input = $this.find('.column-width'),
                    $offset_select = $this.find('.mesh-column-offset'),
                    selected_offset = $offset_select.val(),
                    column_value = parseInt( column_values[ index ] ),
                    max_offset   = column_value - 3;

                $offset_select.children('option').remove();

                for ( var i = 0; i <= max_offset; i++ ) {
	                $offset_select.append( $('<option></option>').attr( 'value', i ).text( i ) );
                }

                if ( selected_offset > max_offset ) {
	                $offset_select.val( 0 ).trigger('change');
                } else {
	                $offset_select.val( selected_offset ).trigger('change');
                }

				// Reset column width classes and save post data
                $this.addClass( 'mesh-columns-' + column_value );

                if( block_id && column_values[ index ] ) {
                    $column_input.val( column_value );
                    post_data.blocks[ block_id.toString() ] = column_value;
                }
            } );
        },

        /**
         * Setup Resize Slider
         */
        setup_resize_slider : function() {
            $('.column-slider').addClass('ui-slider-horizontal').each(function() {
                var $this    = $(this),
                    blocks   = parseInt( $this.attr('data-mesh-blocks') ),
                    is_range = ( blocks > 2 ),
                    vals     = $.parseJSON( $this.attr('data-mesh-columns') ),
                    data     = {
                        range: is_range,
                        min:0,
                        max:12,
                        step:1,
                        left: 3,
                        right: 9,
                        gap: 3,
                        start : function() {
                            $this.css('z-index', 1000);
                        },
                        stop : function() {
                            $this.css('z-index', '').find('.ui-slider-handle').css('z-index', 1000);
                        },
                        slide : self.change_block_widths
                    };

                if ( vals ) {
                    data.value = vals[0];
                }

                if( blocks === 3 ) {
                    vals[1] = vals[0] + vals[1]; // add the first 2 columns together
                    vals.pop();
                    data.values = vals;
                    data.value = null;
                }

                $this.limitslider( data );
            });
        },

        /**
         * Render Block after reorder or change.
         *
         * @since 0.3.5
         *
         * @param $tinymce_editors
         */
        rerender_blocks : function( $tinymce_editors ) {

            $tinymce_editors.each(function() {
                var editor_id   = $(this).prop('id'),
                    proto_id,
                    mce_options = [],
                    qt_options  = [];

                if ( typeof tinymce !== 'undefined' ) {

                    // Reset our editors if we have any
                    if ( parseInt( tinymce.majorVersion ) >= 4 ) {
                        tinymce.execCommand( 'mceRemoveEditor', false, editor_id );
                    }

                    var $block_content = $(this).closest('.block-content');

                    /**
                     * Props to @danielbachuber for a shove in the right direction to have movable editors in the wp-admin
                     *
                     * https://github.com/alleyinteractive/wordpress-fieldmanager/blob/master/js/richtext.js#L58-L95
                     */
                    if ( typeof tinyMCEPreInit.mceInit[ editor_id ] === 'undefined' ) {
                        proto_id = 'content';

                        // Clean up the proto id which appears in some of the wp_editor generated HTML

                        var block_html = $(this).closest('.block-content').html(),
                            pattern = /\[post_mesh\-section\-editor\-[0-9]+\]/;
                        block_html = block_html.replace(new RegExp(proto_id, 'g'), editor_id);
                        block_html = block_html.replace(new RegExp(pattern, 'g'), '[post_content]');

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
                        if ( 'html' !== mesh.blocks.mode_enabled( this ) ) {
                            if ( parseInt( tinymce.majorVersion ) >= 4 ) {
                                tinymce.execCommand( 'mceRemoveEditor', false, editor_id );
                            }
                            
                            // tinymce.init( tinyMCEPreInit.mceInit[ editor_id ] );

                            $( this ).closest( '.wp-editor-wrap' ).on( 'click.wp-editor', function() {
                                if ( this.id ) {
                                    window.wpActiveEditor = this.id.slice( 3, -5 );
                                }
                            } );
                        }
                    } catch(e){
                        console.log( e );
                    }

                    try {

                        proto_id = 'content';

                        if ( proto_id && typeof tinyMCEPreInit.qtInit[proto_id] !== 'undefined') {
                            qt_options = $.extend(true, {}, tinyMCEPreInit.qtInit[proto_id]);

                            qt_options.id = qt_options.id.replace(proto_id, editor_id);

                            tinyMCEPreInit.qtInit[editor_id] = qt_options;

                            qt_options.buttons = 'strong,em,link,block,img,ul,ol,li';

                            if ( typeof quicktags !== 'undefined' ) {
                                quicktags(tinyMCEPreInit.qtInit[editor_id]);
                            }

                            if ( typeof QTags !== 'undefined' ) {
                                QTags._buttonsInit();
                            }
                        }
                    } catch(e) {
                        console.log( e );
                    }

                    // @todo This is kinda hacky. See about switching this out @aware
                    $block_content.find('.switch-tmce').trigger('click');
                }
            });
        },

        mode_enabled: function( el ) {
            return $( el ).closest( '.html-active' ).length ? 'html' : 'tinymce';
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
        save_order : function( section_id, event, ui ) {
            var $reorder_spinner = $('.mesh-reorder-spinner'),
                block_ids = [];

            $( '#mesh-sections-editor-' + section_id ).find( '.block' ).each( function() {
                block_ids.push( $(this).attr('data-mesh-block-id') );
            });
        },

        /**
         * Choose a background for our block
         *
         * @param event
         */
        choose_background : function(event) {
            event.preventDefault();
            event.stopPropagation();

            var $button       = $(this),
                $section      = $button.parents('.block'),
                section_id    = parseInt( $section.attr('data-mesh-block-id') ),
                frame_id      = 'mesh-background-select-' + section_id,
                current_image = $button.attr('data-mesh-block-featured-image');

            admin.media_frames = admin.media_frames || [];

            // If the frame already exists, re-open it.
            if ( admin.media_frames[ frame_id ] ) {
                admin.media_frames[ frame_id ].uploader.uploader.param( 'mesh_upload', 'true' );
                admin.media_frames[ frame_id ].open();
                return;
            }

            /**
             * The media frame doesn't exist let, so let's create it with some options.
             */
            admin.media_frames[ frame_id ] = wp.media.frames.media_frames = wp.media({
                className: 'media-frame mesh-media-frame',
                frame: 'select',
                multiple: false,
                title: mesh_data.strings.select_block_bg,
                button: {
                    text: mesh_data.strings.select_bg
                }
            });

            admin.media_frames[ frame_id ].on('open', function(){
                // Grab our attachment selection and construct a JSON representation of the model.
                var selection = admin.media_frames[ frame_id ].state().get('selection');

                selection.add( wp.media.attachment( current_image ) );
            });

            admin.media_frames[ frame_id ].on('select', function(){
                // Grab our attachment selection and construct a JSON representation of the model.
                var media_attachment = admin.media_frames[ frame_id ].state().get('selection').first().toJSON(),
                    $edit_icon = $( '<span />', {
                        'class' : 'dashicons dashicons-edit'
                    }),
                    $trash = $('<a/>', {
                        'data-mesh-section-featured-image': '',
                        'href' : '#',
                        'class' : 'mesh-block-featured-image-trash dashicons-before dashicons-dismiss'
                    });

                $.post( ajaxurl, {
                    'action': 'mesh_update_featured_image',
                    'mesh_section_id'  : parseInt( section_id ),
                    'mesh_image_id' : parseInt( media_attachment.id ),
                    'mesh_featured_image_nonce' : mesh_data.featured_image_nonce
                }, function( response ) {
                    if ( response != -1 ) {
                        current_image = media_attachment.id;
                        $button
                            .html( '<img src="' + media_attachment.url + '" />' )
                            .attr('data-mesh-block-featured-image', parseInt( media_attachment.id ) )
                            .after( $trash );
                    }
                });
            });

            // Now that everything has been set, let's open up the frame.
            admin.media_frames[ frame_id ].open();
        },

        /**
         * Remove selected background from our block
         *
         * @since 0.3.6
         *
         * @param event
         */
        remove_background : function( event ) {

            event.preventDefault();
            event.stopPropagation();

            var $button       = $(this),
                $section      = $button.parents('.block'),
                section_id    = parseInt( $section.attr('data-mesh-block-id') );

            $.post( ajaxurl, {
                'action': 'mesh_update_featured_image',
                'mesh_section_id'  : parseInt( section_id ),
                'mesh_featured_image_nonce' : mesh_data.featured_image_nonce
            }, function( response ) {
                if ( response != -1 ) {
                    $button.prev().text( mesh_data.strings.add_image );
                    $button.remove();
                }
            });
        },

        show_field : function ( event ) {
	        event.preventDefault();
	        event.stopPropagation();

	        $(this).addClass('title-input-visible');
		},

		hide_field : function ( event ) {
	        event.preventDefault();
	        event.stopPropagation();

	        $(this).parent().removeClass('title-input-visible');
		},

		slide_toggle_element : function ( event ) {
			event.preventDefault();
			event.stopPropagation();

			var $this   = $(this),
				$toggle = $this.data('toggle');

			$($toggle).slideToggle('fast');
			$this.toggleClass('toggled');
		},

		display_offset : function ( event ) {
			var offset = $(this).val(),
				$block = $(this).parents('.block-header').next('.block-content');

			$block.removeClass('mesh-has-offset mesh-offset-1 mesh-offset-2 mesh-offset-3 mesh-offset-4 mesh-offset-5 mesh-offset-6 mesh-offset-7 mesh-offset-8 mesh-offset-9');

			if ( parseInt( offset ) ) {
				$block.addClass('mesh-has-offset mesh-offset-' + offset );
			}
		},

		/**
         * Setup Block Drag and Drop
         *
         * @since 0.3.0
         * @deprecated - Keep for fallback if sortable doesn't work out.
         */
        setup_drag_drop : function() {

            $( ".mesh-editor-blocks .block" ).draggable({
                'appendTo' : 'body',
                helper : function( event ) {

                    var $this = $(this),
                        _width = $this.width();
                        $clone = $this.clone().width(_width).css('background','#fff');
                        $clone.find('*').removeAttr('id');

                    return $clone;
                },
                revert: true,
                zIndex: 1000,
                handle: '.the-mover',
                iframeFix:true,
                start:function( ui, event, helper ){}
            });

            $( ".block" )
                .addClass( "ui-widget ui-widget-content ui-helper-clearfix" )
                .find( ".block-header" )
                .addClass( "hndle ui-sortable-handle" )
                .prepend( "<span class='block-toggle' />");

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
                        $section     = $tgt.parents('.mesh-section'),
                        section_id   = $section.attr('data-mesh-section-id');

                    $swap_clone.css( { 'top':'','left':'' } );

                    $this.append( $swap_clone );
                    $swap_parent.append( $tgt_clone );

                    self.rerender_blocks( $section.find('.wp-editor-area') );
                    self.save_order( section_id, event, ui );
                    self.setup_drag_drop();

                    return false;
                }
            });
        }
    };

} ( jQuery );
;
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

				.on('change', '.mesh-choose-layout',           self.choose_layout )
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

                    // @todo this should be done more efficiently later: Needed to Firefox but will be fixed
                    // once consolidated. Can't clear html before removing or tinymce throws an error
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

					// self.setup_notifications( $layout );

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

			var $button            = $(this),
				$button_container  = $button.parent(),
				$spinner           = $button_container.find( '.spinner' ),
				$current_section   = $button.closest( '.mesh-section' ),
				$post_status_field = $current_section.find( '.mesh-section-status' ),
				section_id         = $current_section.attr( 'data-mesh-section-id' );

			$current_section.find('.mesh-editor-blocks .wp-editor-area').each( function() {

				var content = '',
					editorID = $(this).attr('id'),
					editor = tinymce.get( editorID );

					// Make sure we have an editor and we aren't in text view.
					if( editor ) {
						content = editor.getContent({format: 'raw'});
						$('#' + editorID).val(content);
					}
			});

			var	form_data = $current_section.parents( 'form' ).serialize(),
				form_submit_data = [];

			$button_container.find( '.button' ).addClass( 'disabled' );
			$spinner.addClass( 'is-active' );

			$.post( ajaxurl, {
				action: 'mesh_save_section',
				mesh_section_id: section_id,
				mesh_section_data: form_data,
				mesh_save_section_nonce: mesh_data.save_section_nonce
			}, function( response ) {

				var $button = $button_container.find( '.button' );

				$button.removeClass( 'disabled' );
				$spinner.removeClass( 'is-active' );

				if ( response ) {

					var $publish_draft = $current_section.find( '.mesh-section-publish, .mesh-section-save-draft' );

					if ( 'publish' == $post_status_field.val() ) {
						$button.removeClass( 'hidden' );
						$publish_draft.addClass( 'hidden' );
					} else {
						$button.addClass( 'hidden' );
						$publish_draft.removeClass( 'hidden' );
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
//# sourceMappingURL=admin-mesh.js.map