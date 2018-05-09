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
	 * @var array
	 */
	private $block_settings = array();

	/**
	 * Mesh_Controls constructor.
	 */
	function __construct() {
		$this->block_settings = array(
			'push_pull'        => false,
			'collapse_spacing' => false,
			'total_columns'    => 1,
			'max_columns'      => apply_filters( 'mesh_max_columns', 12 ),
			'column_index'     => -1,
			'column_width'     => apply_filters( 'mesh_column_width', 12 ),
		);
	}

	/**
	 * @return array
	 */
	public function get_block_settings() {
		return $this->block_settings;
	}

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

		$_block_settings = $this->get_block_settings();

		$default_block_columns = $_block_settings['max_columns'] / $section_blocks;
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

		foreach ( $templates as $template_key => $value ) {
			$options[ $template_key ] = $templates[ $template_key ]['file'];
		}

		return $options;
	}

	/**
	 * Display our offset options.
	 *
	 * @param \WP_Post $block          Block.
	 * @param int      $section_blocks Count of our blocks.
	 *
	 * @return array
	 */
	function get_offset_options( $block, $section_blocks ) {

		$_block_settings = $this->get_block_settings();

		$default_block_columns = $_block_settings['max_columns'] / $section_blocks;

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
	 * Build out a dropdown for our available columns.
	 *
	 * @param $block
	 * @param $section_blocks
	 *
	 * @return array
	 */
	function get_columns( $block, $section_blocks ) {

		$_block_settings = $this->get_block_settings();

		$block_columns = $_block_settings['max_columns'];

		$options = array();

		for ( $i = 3; $i <= $block_columns; $i++ ) {
			$options[ $i ] = $i;
		}

		return $options;
	}

	/**
	 * Display our centered options.
	 *
	 * @param \WP_Post $block          Block.
	 * @param int      $section_blocks Count of our blocks.
	 *
	 * @return boolean
	 */
	function show_centered( $block, $section_blocks ) {
		return  ( 1 === $section_blocks );
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
					'label'          => esc_html__( 'Columns', 'mesh' ),
					'type'           => 'select',
					'css_classes'    => array( 'mesh-choose-layout' ),
					'validation_cb'  => false,
					'options_cb'     => array( $this, 'get_template_options' ),
					'id'             => 'mesh-sections-template-' . $section->ID,
				),
				'title-display' => array(
					'label'          => esc_html__( 'Display Title', 'mesh' ),
					'type'           => 'checkbox',
					'css_classes'    => array( 'mesh-section-show-title' ),
					'show_on_cb'     => false,
					'validation_cb'  => false,
				),
				'size' => array(
					'label'          => esc_html__( 'Size', 'mesh' ),
					'type'           => 'dropdown',
					'css_classes'    => array( 'mesh-section-display-size' ),
					'options'     => array(
						esc_html__( 'Small', 'mesh' ),
						esc_html__( 'Medium', 'mesh' ),
						esc_html__( 'Large', 'mesh' ),
						esc_html__( 'X-Large', 'mesh' ),
					),
				),
				'small-full-width' => array(
					'label'          => esc_html__( 'Full Width?', 'mesh' ),
					'type'           => 'checkbox',
					'css_classes'    => array( 'mesh-section-show-title mesh-hide' ),
					'show_on_cb'     => false,
					'validation_cb'  => false,
				),
			),
			'more_options' => array(
				'section-id' => array(
					'label'         => esc_html__( 'Section ID', 'mesh' ),
					'type'          => 'text',
					'css_classes'   => array( 'mesh-section-id' ),
					'show_on_cb'    => false,
					'validation_cb' => false,
				),
				'css-class' => array(
					'label'          => esc_html__( 'Section Class', 'mesh' ),
					'type'           => 'text',
					'css_classes'    => array( 'mesh-section-class' ),
					'show_on_cb'     => false,
					'validation_cb'  => false,
				),
				'row-class' => array(
					'label'          => esc_html__( 'Row Class', 'mesh' ),
					'type'           => 'text',
					'css_classes'    => array( 'mesh-row-class' ),
					'show_on_cb'     => false,
					'validation_cb'  => false,
				),
				'collapse' => array(
					'label'          => esc_html__( 'Collapse Padding', 'mesh' ),
					'type'           => 'checkbox',
					'css_classes'    => array( 'mesh-section-collapse-input' ),
					'show_on_cb'     => false,
					'validation_cb'  => false,
				),
				'push-pull' => array(
					'label'          => esc_html__( 'Push Pull', 'mesh' ),
					'type'           => 'checkbox',
					'css_classes'    => array( 'mesh-section-push' ),
					'show_on_cb'     => array( $this, 'show_push_pull' ),
					'validation_cb'  => false,
				),
				'lp-equal' => array(
					'label'          => esc_html__( 'Equalize', 'mesh' ),
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
			$container_class = 'inline-block-list mesh-section-meta-visible-list';
		} else {
			$controls = $controls['more_options'];
			$container_class = 'small-block-grid-1 medium-block-grid-4';
		}
		?>
		<ul class="<?php echo esc_attr( $container_class ); ?>">
		<?php

		foreach ( $controls as $control_key => $control ) {

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

			$underscore_key = str_replace( '-', '_', $control_key );
			$current        = get_post_meta( $section->ID, '_mesh_' . esc_attr( $underscore_key ), true );
			?>
			<li class="mesh-section-control-<?php echo esc_attr( $control_key ); ?>">
				<label for="mesh-section[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]">
					<?php echo esc_html( $control['label'] ); ?>
					<?php
					switch ( $control['type'] ) {
						case 'checkbox':
					?>
							<input type="checkbox" name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>" value="1" <?php checked( $current ); ?> />
						<?php
							break;
						case 'select':
						case 'dropdown':
							// @todo inline control structure that needs updating.
							$multiple = '';
							$control_id = '';
							if ( isset( $control['id'] ) ) {
								$control_id = sprintf( 'id="%s"', esc_attr( $control['id'] ) );
							}

							if ( isset( $control['multiple'] ) && $control['multiple'] ) {
								$multiple = 'multiple';
							}
						?>
						<select <?php echo $control_id; // WPCS xss okay. ?> name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>" <?php echo $multiple; // WPCS xss okay. ?>>
						<?php

						// @todo this needs to be cleaned up to meet wpcs
						$options = ( ! empty( $control['options_cb'] ) && is_callable( $control['options_cb'] ) )
						? call_user_func_array( $control['options_cb'], array( $section, $blocks ) )
						: $control['options'];

						foreach ( $options as $option_key => $value ) {
							printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $option_key ), selected( esc_attr( $current ), esc_attr( $option_key ), false ), esc_attr( $value ) );
						}
						?>
						</select>
						<?php
							break;
						case 'input':
						case 'text':
						default:
						?>
							<input type="text" name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>" value="<?php echo esc_attr( get_post_meta( $section->ID, '_mesh_' . esc_attr( $underscore_key ), true ) ); ?>" />
						<?php
							break;
					}
				?>
				</label>
			</li>
		<?php
		}
		?>
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
				'label'          => esc_html__( 'Offset', 'mesh' ),
				'type'           => 'select',
				'css_classes'    => array( 'mesh-column-offset' ),
				'validation_cb'  => false,
				'options_cb'     => array( $this, 'get_offset_options' ),
				'show_on_cb'     => array( $this, 'show_offset' ),
			),
			'css-class' => array(
				'label'          => esc_html__( 'CSS Class', 'mesh' ),
				'type'           => 'text',
				'css_classes'    => array( 'mesh-section-class' ),
				'validation_cb'  => false,
			),
			'centered' => array(
				'label'          => esc_html__( 'Centered', 'mesh' ),
				'type'           => 'checkbox',
				'css_classes'    => array( 'mesh-section-centered' ),
				'show_on_cb'     => array( $this, 'show_centered' ),
				'validation_cb'  => false,
			),
			'featured_image' => array(
				'type' => 'media',
				'label' => '',
				'css_classes'    => array( 'mesh-section-class' ),
			),
			'columns' => array(
				'label'          => esc_html__( 'Columns', 'mesh' ),
				'type'           => 'dropdown',
				'css_classes'    => array( 'mesh-block-columns', 'column-width' ),
				//'show_on_cb'     => array( $this, 'show_centered' ),
				'validation_cb'  => false,
				'options_cb'     => array( $this, 'get_columns' ),
			),
			'menu_order' => array(
				'label'          => esc_html__( 'Menu Order', 'mesh' ),
				'type'           => 'hidden',
				'css_classes'    => array( 'block-menu-order' ),
				'validation_cb'  => false,
			),
		);

		$controls = apply_filters( 'mesh_block_controls', $controls );

		$block_grid = ( ( 4 - $section_blocks ) < 1 ) ? 1 : ( 4 - $section_blocks );
		printf( '<ul class="small-block-grid-1 medium-block-grid-%s">', esc_attr( $block_grid ) );

		foreach ( $controls as $controls_key => $control ) {
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

			$css_classes    = implode( ' ', $css_classes );
			$underscore_key = str_replace( '-', '_', $controls_key );
			$current        = get_post_meta( $block->ID, '_mesh_' . esc_attr( $underscore_key ), true );

			if ( 'columns' === $underscore_key && empty( $current ) ) {
				$current = $this->block_settings['max_columns'];
			}

			?>
			<li class="mesh-section-control-<?php echo esc_attr( $controls_key ); ?>">
				<label for="mesh-section[<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]">
					<?php
					if ( isset( $control['label'] ) ) {
						echo esc_html( $control['label'] );
					}
					?>
					<?php
					switch ( $control['type'] ) {
						case 'checkbox':
							?>
							<input type="checkbox" name="mesh-sections[<?php echo esc_attr( $block->post_parent ); ?>][blocks][<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>" value="1" <?php checked( $current ); ?> />
							<?php
							break;
						case 'select':
						case 'dropdown':
							$multiple = '';
							$control_id = '';
							if ( isset( $control['id'] ) ) {
								$control_id = sprintf( 'id="%s"', esc_attr( $control['id'] ) );
							}

							if ( isset( $control['multiple'] ) && $control['multiple'] ) {
								$multiple = 'multiple';
							}
						?>
							<select <?php echo $control_id; // WPCS: XSS ok, sanitization ok. ?> name="mesh-sections[<?php echo esc_attr( $block->post_parent ); ?>][blocks][<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>"<?php echo esc_attr( $multiple ); ?>>
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
						case 'media':
						?>

                            <?php
                            $featured_image_id = get_post_thumbnail_id( $block->ID );
                            $section_background_class = 'mesh-section-background';
                            $section_background_class = ( ! empty( $featured_image_id ) ) ? $section_background_class . ' has-background-set' : $section_background_class;
                            ?>

							<div class="<?php esc_attr_e( $section_background_class ); ?>">
								<div class="choose-image">
									<?php if ( empty( $featured_image_id ) ) : ?>
										<a class="mesh-block-featured-image-choose"><?php esc_attr_e( 'Set Background Image', 'mesh' ); ?></a>
									<?php else : ?>
										<?php $featured_image = wp_get_attachment_image_src( $featured_image_id, array( 160, 60 ) ); ?>
										<a class="mesh-block-featured-image-choose right" data-mesh-block-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"><img src="<?php echo esc_attr( $featured_image[0] ); ?>" /></a>
										<a class="mesh-block-featured-image-trash dashicons-before dashicons-dismiss" data-mesh-block-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"></a>
									<?php endif; ?>
                                    <input type="hidden" name="mesh-sections[<?php echo esc_attr( $block->post_parent ); ?>][blocks][<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( 'featured_image' ); ?>]" value="<?php echo esc_attr( $featured_image_id ); ?>" />
								</div>
							</div>
							<?php
							break;
						case 'hidden': ?>
							<input type="text" name="mesh-sections[<?php echo esc_attr( $block->post_parent ); ?>][blocks][<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>" value="<?php echo esc_attr( get_post_meta( $block->ID, '_mesh_' . esc_attr( $underscore_key ), true ) ); ?>" />
							<?php
							break;
						case 'input':
						case 'text':
						default :
						?>
						<input type="text" name="mesh-sections[<?php echo esc_attr( $block->post_parent ); ?>][blocks][<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]" class="<?php echo esc_attr( $css_classes ); ?>" value="<?php echo esc_attr( get_post_meta( $block->ID, '_mesh_' . esc_attr( $underscore_key ), true ) ); ?>" />
						<?php
							break;
					}
					?>
				</label>
			</li>
			<?php
		}
		?>
		</ul>
		<?php
	}
}

/**
 * Build out the attributes passed to each section
 *
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
	 *
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

/**
 * Build out extra element attributes
 *
 * @param int  $post_id Current Post ID.
 * @since 1.2.3
 *
 * @return string
 */
function get_mesh_element_attributes( $post_id = 0 ) {
	global $post;

	if ( empty( $post_id ) ) {
		$post_id  = $post->ID;
	}

	$post_parent_id   = wp_get_post_parent_id( $post_id );
	$parent_post_type = get_post_type( $post_parent_id );

	$element_attributes = array();

	if ( 'mesh_section' != $parent_post_type ) {
	    $section_id = get_post_meta( $post_id, '_mesh_section_id', true );
	    $section_id = ( ! empty( $section_id ) ) ? $section_id : 'mesh-section-' . $post_id;
	    $section_id = 'id="' . $section_id . '"';

	    $element_attributes[] = $section_id;
    }

    return $element_attributes;
}