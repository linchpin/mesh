<?php
/**
 * Handle displaying controls within sections and blocks
 *
 * @package     Mesh
 * @subpackage  Controls
 * @since 1.2
 */

/**
 * Class Mesh_Controls
 */
class Mesh_Controls {

	/**
	 * Only show equalize when the count of our blocks is greater than 1
	 *
	 * @param \WP_Post|int $section Our current section.
	 * @param array        $blocks Blocks within our section.
	 *
	 * @return bool
	 */
	function show_equalize( $section, $blocks ) {
		return ( count( $blocks ) > 1 );
	}

	/**
	 * Only show push pull controls when we have more than 1 block
	 *
	 * @param \WP_Post|int $section Section.
	 * @param array        $blocks Blocks.
	 *
	 * @since 1.2
	 *
	 * @return bool
	 */
	function show_push_pull( $section, $blocks ) {
		return ( count( $blocks ) > 1 );
	}

	/**
	 * Show our offset controls if we have blocks within our threshold.
	 *
	 * @param \WP_Post|int $block          Current Block.
	 * @param array        $section_blocks Section Blocks.
	 *
	 * @return bool
	 */
	function show_offset( $block, $section_blocks ) {
		$default_block_columns = 12 / $section_blocks;
		$block_columns = get_post_meta( $block->ID, '_mesh_column_width', true );

		if ( empty( $block_columns ) ) {
			$block_columns = $default_block_columns;
		}

		$offsets_available = $block_columns - 3;

		return $offsets_available > 0;
	}

	/**
	 * Get the options of our template.
	 *
	 * @return array
	 */
	function get_template_options() {
		$templates = mesh_locate_template_files();
		$options = array();

		foreach ( $templates as $key => $value ) {
		    $options[ $key ] = $templates[ $key ]['file'];
		}

		return $options;
	}

	/**
	 * Dipsplay our options.
	 *
	 * @param \WP_Post $block          Block.
	 * @param int      $section_blocks Count of our blocks.
	 *
	 * @return array
	 */
	function get_offset_options( $block, $section_blocks ) {

	    $default_block_columns = 12 / $section_blocks;
	    $block_columns = get_post_meta( $block->ID, '_mesh_column_width', true );

	    if ( empty( $block_columns ) ) {
			$block_columns = $default_block_columns;
		}

	    $offsets_available = $block_columns - 3;

	    $options = array();

		for ( $i = 0; $i <= $offsets_available; $i++ ) {
			$options[ $i ] = $i;
		}

		return $options;
	}

	/**
	 * Display all section controls
	 *
	 * @param object $section Current section.
	 * @param array  $blocks  Our sections current block.
	 * @param bool   $visible Show visible options.
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
				'featured_image' => array(
					'type' => 'media',
					'label' => __( 'Featured Image', 'mesh' ),
					'css_classes' => '',
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
		?>
		<ul class="small-block-grid-1 medium-block-grid-4">
		<?php

		foreach ( $controls as $key => $control ) {

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
            <li class="mesh-section-control-<?php echo esc_attr( $key ); ?>">
                <label for="mesh-section[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]">
                    <?php esc_html_e( $control['label'] ); ?>
					<?php
					switch ( $control['type'] ) {
						case 'checkbox' : ?>
                            <input type="checkbox" name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>" value="1" <?php checked( $current ); ?> />
                        <?php break;
						case 'select' :
						case 'dropdown' :
							// @todo inline control structure that needs updating.
							?>
							<select name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>"<?php echo isset( $control['id'] ) ? 'id="' . esc_attr( $control['id'] ) .'"' : ''; ?><?php if ( isset( $control['multiple'] ) && $control['multiple'] ) echo ' multiple'; ?>>
                                <?php

								// @todo this needs to be cleaned up to meet wpcs
								$options = ( ! empty( $control['options_cb'] ) && is_callable( $control['options_cb'] ) )
								? call_user_func_array( $control['options_cb'], array( $section, $blocks ) )
								: $control['options'];

								foreach ( $options as $option_key => $value ) {
									printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $option_key ), selected( esc_attr( $current ), esc_attr( $key ), false ), esc_attr( $value ) );
								}
								?>
                            </select>
                        <?php
						break;
	                    case 'media' : ?>
                            <div class="mesh-section-background">
                                <div class="choose-image">
				                    <?php $featured_image_id = get_post_thumbnail_id( $section->ID );

				                    if ( empty( $featured_image_id ) ) : ?>
                                        <a class="mesh-featured-image-choose"><?php esc_attr_e( 'Set Background Image', 'mesh' ); ?></a>
				                    <?php else : ?>
					                    <?php $featured_image = wp_get_attachment_image_src( $featured_image_id, array( 160, 60 ) ); ?>
                                        <a class="mesh-featured-image-choose right" data-mesh-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"><img src="<?php echo esc_attr( $featured_image[0] ); ?>" /></a>
                                        <a class="mesh-featured-image-trash dashicons-before dashicons-dismiss" data-mesh-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"></a>
				                    <?php endif; ?>

				                    <input type="hidden" name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" value="<?php echo esc_attr( $featured_image_id ); ?>" />
                                </div>
                            </div>
		                    <?php
		                    break;
						case 'input' :
						case 'text' :
						default : ?>
                            <input type="text" name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>" value="<?php echo esc_attr( get_post_meta( $section->ID, '_mesh_' . esc_attr( $underscore_key ), true ) ); ?>" />
                            <?php
						break;
					} ?>
                </label>
            </li>
            <?php
		} ?>
		</ul>
		<?php
	}

	/**
	 * Display all block controls
	 *
	 * @param \WP_Post|array $block Target Section.
	 * @param array          $section_blocks Blocks within the section.
	 *
	 * @since 1.2
	 */
	function mesh_block_controls( $block, $section_blocks ) {

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
			'featured_image' => array(
				'type' => 'media',
				'label' => '',
				'css_classes'    => array( 'mesh-section-class' ),
			),
		);

		$controls = apply_filters( 'mesh_block_controls', $controls );

		$block_grid = ( ( 4 - $section_blocks ) < 1 ) ? 1 : ( 4 - $section_blocks );
		printf( '<ul class="small-block-grid-1 medium-block-grid-%s">', esc_attr( $block_grid ) );

		foreach ( $controls as $key => $control ) {
			$display_control = true;

			if ( ! empty( $control['show_on_cb'] ) && is_callable( $control['show_on_cb'] ) ) {
				$display_control = call_user_func_array( $control['show_on_cb'], array( $block, $section_blocks ) );
			}

			if ( ! $display_control ) {
				continue;
			}

			if ( isset( $control['css_classes'] ) ) {
				if ( ! empty( $control['css_classes'] ) && is_array( $control['css_classes'] ) ) {
					$css_classes = array_map( 'sanitize_html_class', $control['css_classes'] );
				} else {
					$css_classes = array( sanitize_html_class( $control['css_classes'] ) );
				}
			}

			$css_classes = implode( ' ', $css_classes );
			$underscore_key = str_replace( '-', '_', $key );
			$current        = get_post_meta( $block->ID, '_mesh_' . esc_attr( $underscore_key ), true );
			?>
            <li class="mesh-section-control-<?php echo esc_attr( $key ); ?>">
                <label for="mesh-section[<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]">
					<?php
					if ( isset( $control['label'] ) ) {
						echo esc_html( $control['label'] );
					}
					?>
					<?php
					switch ( $control['type'] ) {
						case 'checkbox' : ?>
                            <input type="checkbox" name="mesh-sections[<?php echo esc_attr( $block->post_parent ); ?>][blocks][<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>" value="1" <?php checked( $current ); ?> />
							<?php
							break;
						case 'select' :
						case 'dropdown' :
							// @todo select needs inline control structures removed.
						?>
                            <select name="mesh-sections[<?php echo esc_attr( $block->post_parent ); ?>][blocks][<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>"<?php echo isset( $control['id'] ) ? 'id="' . esc_attr( $control['id'] ) .'"' : ''; ?><?php if ( isset( $control['multiple'] ) && $control['multiple'] ) echo ' multiple'; ?>>
								<?php
								$options = ( ! empty( $control['options_cb'] ) && is_callable( $control['options_cb'] ) )
									? call_user_func_array( $control['options_cb'], array( $block, $section_blocks ) )
									: $control['options'];

								foreach ( $options as $optionkey => $value ) {
									printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $optionkey ), selected( esc_attr( $current ), esc_attr( $optionkey ), false ), esc_attr( $value ) );
								}
								?>
                            </select>
							<?php
							break;
						case 'media' : ?>
                            <div class="mesh-section-background">
                                <div class="choose-image">
		                            <?php $featured_image_id = get_post_thumbnail_id( $block->ID );

		                            if ( empty( $featured_image_id ) ) : ?>
                                        <a class="mesh-block-featured-image-choose"><?php esc_attr_e( 'Set Background Image', 'mesh' ); ?></a>
		                            <?php else : ?>
			                            <?php $featured_image = wp_get_attachment_image_src( $featured_image_id, array( 160, 60 ) ); ?>
                                        <a class="mesh-block-featured-image-choose right" data-mesh-block-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"><img src="<?php echo esc_attr( $featured_image[0] ); ?>" /></a>
                                        <a class="mesh-block-featured-image-trash dashicons-before dashicons-dismiss" data-mesh-block-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"></a>
		                            <?php endif; ?>
                                </div>
                            </div>
                            <?php
							break;
						case 'input' :
						case 'text' :
						default : ?>
                            <input type="text" name="mesh-sections[<?php echo esc_attr( $block->post_parent ); ?>][blocks][<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>" value="<?php echo esc_attr( get_post_meta( $block->ID, '_mesh_' . esc_attr( $underscore_key ), true ) ); ?>" />
							<?php
							break;
					}
					?>
                </label>
            </li>
			<?php
		} ?>
        </ul>
		<?php
	}
}

/**
 * Build out the attributes passed to each section
 * @param int  $post_id Current Post ID.
 * @param bool $echo    Echo the post or not.
 * @since 1.2
 *
 * @return string
 */
function mesh_section_attributes( $post_id = 0, $echo = true ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id  = $post->ID;
	}

	/*
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
		'menu_order',
	);

	/**
	 * This filter is used to remove or add elements to the default section meta
	 * @todo "meta" related to a section
	 */
	$default_section_meta = apply_filters( 'mesh_default_section_meta_fields', $default_section_meta );

	$section_data = get_post_meta( $post_id, '' );

	$attributes = array();

	// Process our custom meta.
	foreach ( $section_data as $data_key => $data_field ) {
		// Do not process default keys.
		if ( in_array( $data_key, $default_section_meta, true ) ) {
			continue;
		}

		$lowercase_data_key = str_replace( '_mesh_', '', $data_key );

		if ( ! empty( $data_field ) ) {
			$attributes[ 'data-' . $lowercase_data_key ] = $data_field[0];
		}
	}

	if ( empty( $attributes ) ) {
		return '';
	} else {
		if ( false === $echo ) {
			return $attributes;
		} else {

			$attributes = join( ' ', array_map( function( $data_key ) use ( $attributes ) {
				if ( is_bool( $attributes[ $data_key ] ) ) {
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
 * @param object $section Current Section.
 * @param array  $blocks  Our Sections Current Block.
 * @param bool   $visible Show visible options.
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
 * @param array $block          Our Current Block.
 * @param array $section_blocks Blocks within the current section.
 *
 * @since 1.2
 */
function mesh_block_controls( $block, $section_blocks ) {
	$mesh_controls = new Mesh_Controls();
	$mesh_controls->mesh_block_controls( $block, $section_blocks );
}
