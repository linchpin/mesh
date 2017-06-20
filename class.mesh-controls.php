<?php
/**
 * Handle displaying controls within sections and blocks
 *
 * @since 1.2
 */

/**
 * Class Mesh_Controls
 */
class Mesh_Controls {

	/**
	 * Only show equalize when the count of our blocks is greater than 1
	 *
	 * @param $section
	 * @param $blocks
	 *
	 * @return bool
	 */
	function show_equalize( $section, $blocks ) {
		return ( count( $blocks ) > 1 );
	}

	/**
	 * Only show push pull controls when we have more than 1 block
	 *
	 * @param $section
	 * @param $blocks
	 *
	 * @since 1.2
	 *
	 * @return bool
	 */
	function show_push_pull( $section, $blocks ) {
		return ( count( $blocks ) > 1 );
	}

	function show_offset( $block, $section_blocks ) {
		$default_block_columns = 12 / $section_blocks;
		$block_columns = get_post_meta( $block->ID, '_mesh_column_width', true );

		if ( empty( $block_columns ) ) {
			$block_columns = $default_block_columns;
		}

		$offsets_available = $block_columns - 3;

        return $offsets_available > 0;
    }

	function get_template_options() {
		$templates = mesh_locate_template_files();
		$options = array();

		foreach ( $templates as $key => $value ) {
		    $options[$key] = $templates[$key]['file'];
        }

		return $options;
    }

    function get_offset_options( $block, $section_blocks ) {

	    $default_block_columns = 12 / $section_blocks;
	    $block_columns = get_post_meta( $block->ID, '_mesh_column_width', true );

	    if ( empty( $block_columns ) ) {
	        $block_columns = $default_block_columns;
        }

	    $offsets_available = $block_columns - 3;

	    $options = array();

        for ( $i = 0; $i <= $offsets_available; $i++ ) {
	        $options[$i] = $i;
        }

        return $options;
    }

	/**
	 * Display all section controls
	 *
	 * @param object  $section Current Section
	 * @param array   $blocks  Our Sections Current Block
	 * @param bool    $visible Show visible options?
	 *
	 * @since 1.2
	 */
	function mesh_section_controls( $section, $blocks, $visible = false ) {

		$controls = array(
			'visible_options' => array(
				'template' => array(
					'label'          => __( 'Columns', 'mesh' ),
					'type'           => 'select',
					'css_classes'    => array( 'mesh-choose-layout' ),
					'validation_cb'  => false,
                    'options_cb'     => array( $this, 'get_template_options' ),
                    'id'             => 'mesh-sections-template-' . $section->ID,
				),
				'display-title' => array(
					'label'          => __( 'Display Title', 'mesh' ),
					'type'           => 'checkbox',
					'css_classes'    => array( 'mesh-section-show-title' ),
					'show_on_cb'     => false,
					'validation_cb'  => false,
				),
			),
			'more_options' => array(
				'css-class' => array(
					'label'	         => __( 'CSS Class', 'mesh' ),
					'type'           => 'text',
					'css_classes'    => array( 'mesh-section-class' ),
					'show_on_cb'     => false,
					'validation_cb'  => false,
				),
				'collapse' => array(
					'label'          => __( 'Collapse Padding', 'mesh' ),
					'type'           => 'checkbox',
					'css_classes'    => array( 'mesh-section-collapse-input' ),
					'show_on_cb'     => false,
					'validation_cb'  => false,
				),
				'push-pull' => array(
					'label'          => __( 'Push Pull', 'mesh' ),
					'type'           => 'checkbox',
					'css_classes'    => array( 'mesh-section-push' ),
					'show_on_cb'     => array( $this, 'show_push_pull' ),
					'validation_cb'  => false,
				),
				'lp-equal' => array(
					'label'          => __( 'Equalize', 'mesh' ),
					'type'           => 'checkbox',
					'css_classes'    => 'mesh-section-equalize',
					'show_on_cb'     => array( $this, 'show_equalize' ),
					'validate_cb'    => false,
				),
			),
		);

		$controls = apply_filters( 'mesh_section_controls', $controls );

		if ( $visible ) {
			$controls = $controls['visible_options'];
		} else {
			$controls = $controls['more_options'];
		}

		echo '<ul class="small-block-grid-1 medium-block-grid-2">';

		foreach( $controls as $key => $control ) {

			$display_control = true;

			if ( ! empty( $control['show_on_cb'] ) && is_callable( $control['show_on_cb'] ) ) {
				$display_control = call_user_func_array( $control['show_on_cb'], array( $section, $blocks ) );
			}

			if ( ! $display_control ) {
			    continue;
            }

            if ( ! empty( $control['css_classes'] ) && is_array( $control['css_classes'] ) ) {
                $css_classes = array_map( 'sanitize_html_class', $control['css_classes'] );
            } else {
                $css_classes = array( sanitize_html_class( $control['css_classes'] ) );
            }

            $css_classes = implode( ' ', $css_classes );

            $underscore_key = str_replace( '-', '_', $key );
            $current        = get_post_meta( $section->ID, '_mesh_' . esc_attr( $underscore_key ), true );
            ?>
            <li class="mesh-section-control-<?php esc_attr_e( $key ); ?>">
                <label for="mesh-section[<?php esc_attr_e( $section->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]">
                    <?php esc_html_e( $control['label'] ); ?>
                    <?php
                    switch( $control['type'] ) {
                        case 'checkbox' : ?>
                            <input type="checkbox" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]" class="<?php esc_attr_e( $css_classes ); ?>" value="1" <?php checked( $current ); ?> />
                            <?php
                            break;
                        case 'select' :
                        case 'dropdown' :
                        ?>
                            <select name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]" class="<?php esc_attr_e( $css_classes ); ?>"<?php echo isset( $control['id'] ) ? 'id="' . esc_attr( $control['id'] ) .'"' : ''; ?><?php if ( isset( $control['multiple'] ) && $control['multiple'] ) echo ' multiple'; ?>>
                                <?php
                                $options = ( ! empty( $control['options_cb'] ) && is_callable( $control['options_cb'] ) )
                                    ? call_user_func_array( $control['options_cb'], array( $section, $blocks ) )
                                    : $control['options'];

                                foreach( $options as $key => $value ) {
                                    printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr($key), selected( esc_attr($current), esc_attr($key), false), esc_attr($value) );
                                }
                                ?>
                            </select>
                        <?php
                            break;
                        case 'media' :
                            /**
                             * @todo add the ability to select media/image.
                             */
                            break;
                        case 'input' :
                        case 'text' :
                        default : ?>
                            <input type="text" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]" class="<?php esc_attr_e( $css_classes ); ?>" value="<?php esc_attr_e( get_post_meta( $section->ID, '_mesh_' . esc_attr( $underscore_key ), true ) ); ?>" />
                            <?php
                            break;
                    }
                    ?>
                </label>
            </li>
            <?php
		}

		echo '</ul>';
	}

	/**
	 * Display all block controls
	 *
	 * @param $block
	 * @param $section_blocks
	 * @since 1.2
	 *
	 * @return bool
	 */
	function mesh_block_controls( $block, $section_blocks ) {

	    error_log(print_r( $block,1));
		$controls = array(
            'offset' => array(
                'label'          => __( 'Offset', 'mesh' ),
                'type'           => 'select',
                'css_classes'    => array( 'mesh-column-offset' ),
                'validation_cb'  => false,
                'options_cb'     => array( $this, 'get_offset_options' ),
                'show_on_cb'     => array( $this, 'show_offset' ),
            ),
            'css-class' => array(
                'label'	         => __( 'CSS Class', 'mesh' ),
                'type'           => 'text',
                'css_classes'    => array( 'mesh-section-class' ),
                'validation_cb'  => false,
            ),
		);

		$controls = apply_filters( 'mesh_block_controls', $controls );

		echo '<ul class="small-block-grid-1 medium-block-grid-2">';

		foreach( $controls as $key => $control ) {
			$display_control = true;

			if ( ! empty( $control['show_on_cb'] ) && is_callable( $control['show_on_cb'] ) ) {
				$display_control = call_user_func_array( $control['show_on_cb'], array( $block, $section_blocks ) );
			}

			if ( ! $display_control ) {
				continue;
			}

			if ( ! empty( $control['css_classes'] ) && is_array( $control['css_classes'] ) ) {
				$css_classes = array_map( 'sanitize_html_class', $control['css_classes'] );
			} else {
				$css_classes = array( sanitize_html_class( $control['css_classes'] ) );
			}

			$css_classes = implode( ' ', $css_classes );

            $underscore_key = str_replace( '-', '_', $key );
			$current        = get_post_meta( $block->ID, '_mesh_' . esc_attr( $underscore_key ), true );
			?>
            <li class="mesh-section-control-<?php esc_attr_e( $key ); ?>">
                <label for="mesh-section[<?php esc_attr_e( $block->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]">
					<?php esc_html_e( $control['label'] ); ?>
					<?php
					switch( $control['type'] ) {
						case 'checkbox' : ?>
                            <input type="checkbox" name="mesh-sections[<?php esc_attr_e( $block->post_parent ); ?>][blocks][<?php esc_attr_e( $block->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]" class="<?php esc_attr_e( $css_classes ); ?>" value="1" <?php checked( $current ); ?> />
							<?php
							break;
						case 'select' :
						case 'dropdown' :
							?>
                            <select name="mesh-sections[<?php esc_attr_e( $block->post_parent ); ?>][blocks][<?php esc_attr_e( $block->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]" class="<?php esc_attr_e( $css_classes ); ?>"<?php echo isset( $control['id'] ) ? 'id="' . esc_attr( $control['id'] ) .'"' : ''; ?><?php if ( isset( $control['multiple'] ) && $control['multiple'] ) echo ' multiple'; ?>>
								<?php
								$options = ( ! empty( $control['options_cb'] ) && is_callable( $control['options_cb'] ) )
									? call_user_func_array( $control['options_cb'], array( $block, $section_blocks ) )
									: $control['options'];

								foreach( $options as $key => $value ) {
									printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr($key), selected( esc_attr($current), esc_attr($key), false), esc_attr($value) );
								}
								?>
                            </select>
							<?php
							break;
						case 'media' :
							/**
							 * @todo add the ability to select media/image.
							 */
							break;
						case 'input' :
						case 'text' :
						default : ?>
                            <input type="text" name="mesh-sections[<?php esc_attr_e( $block->post_parent ); ?>][blocks][<?php esc_attr_e( $block->ID ); ?>][<?php esc_attr_e( $underscore_key ); ?>]" class="<?php esc_attr_e( $css_classes ); ?>" value="<?php esc_attr_e( get_post_meta( $block->ID, '_mesh_' . esc_attr( $underscore_key ), true ) ); ?>" />
							<?php
							break;
					}
					?>
                </label>
            </li>
			<?php
        }

        echo '</ul>';
	}
}

/**
 * @param int  $post_id
 * @param bool $echo
 * @since 1.2
 *
 * @return string
 */
function mesh_section_attributes( $post_id = 0, $echo = true ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id  = $post->ID;
	}

	/**
	 * Process Section Meta
	 */

	$default_section_meta = array(
		'_mesh_css_class',
		'_mesh_lp_equal',
		'_mesh_title_display',
		'_mesh_push_pull',
		'_mesh_collapse',
		'_mesh_blocks',
		'post_title',
		'post_status',
		'_mesh_template',
		'template_original',
		'menu_order'
	);

	/**
	 * This filter is used to remove or add elements to the default section meta
	 * @todo "meta" related to a section
	 */
	$default_section_meta = apply_filters( 'mesh_default_section_meta_fields', $default_section_meta );

	$section_data = get_post_meta( $post_id, '' );

	$attributes = array();

	// Process our custom meta
	foreach( $section_data as $data_key => $data_field ) {
		// Do not process default keys
		if( in_array( $data_key, $default_section_meta ) ) {
			continue;
		}

		$lowercase_data_key = str_replace( '_mesh_', '', $data_key );

		if( ! empty( $data_field ) ) {
			$attributes[ 'data-' . $lowercase_data_key ] = $data_field[0];
		}
	}

	if ( empty( $attributes ) ) {
		return '';
	} else {
		if ( false === $echo ) {
			return $attributes;
		} else {

			$attributes = join(' ', array_map( function( $data_key ) use ( $attributes )
				{
					if( is_bool( $attributes[ $data_key ] ) ) {
						return $attributes[ $data_key ] ? $data_key : '';
					}
					return $data_key . '="' . $attributes[ $data_key ] . '"';
				}, array_keys( $attributes ) ) );

			echo $attributes; // WPCS: XSS ok.
		}
	}

	return $attributes;
}

/**
 * Public functions to call classes
 *
 * @param object  $section Current Section
 * @param array   $blocks  Our Sections Current Block
 * @param bool    $visible Show visible options?
 *
 * @since 1.2
 */
function mesh_section_controls( $section, $blocks, $visible ) {
	$mesh_controls = new Mesh_Controls();
	$mesh_controls->mesh_section_controls( $section, $blocks, $visible );
}

/**
 * Public functions to call classes
 *
 * @param array   $block  Our Current Block
 *
 * @since 1.2
 */
function mesh_block_controls( $block, $section_blocks ) {
	$mesh_controls = new Mesh_Controls();
	$mesh_controls->mesh_block_controls( $block, $section_blocks );
}