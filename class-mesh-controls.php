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
	public function __construct() {
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
	 * @param \WP_Post|array|int $section Our current section.
	 * @param array        $blocks Blocks within our section.
	 *
	 * @return bool
	 */
	public function show_equalize( $section = array(), $blocks = array() ) {

		$grid = mesh_get_responsive_grid();

		if ( isset( $grid['name'] ) && 'XY Grid' === $grid['name'] ) {
			return false;
		}

		return ( count( $blocks ) > 1 );
	}

	/**
	 * Only show push pull controls when we have more than 1 block
	 *
	 * @param \WP_Post|array|int $section Section.
	 * @param array        $blocks Blocks.
	 *
	 * @since 1.2
	 *
	 * @return bool
	 */
	public function show_push_pull( $section = array(), $blocks = array() ) {

		$grid = mesh_get_responsive_grid();

		if ( isset( $grid['name'] ) && 'XY Grid' === $grid['name'] ) {
			return false;
		}

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
	public function show_offset( $block, $section_blocks ) {

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
	public function get_template_options() {
		$templates = mesh_locate_template_files();
		$options   = array();

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

		// Make sure we have at least one block to make sure we do not
		// have recursion issues.
		if ( empty( $section_blocks ) ) {
			$section_blocks = 1;
		}

		$default_block_columns = intval( $_block_settings['max_columns'] ) / $section_blocks;

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
	public function get_columns() {

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
	public function mesh_section_controls( $section, $blocks, $visible = false ) {

		$controls = array(
			'visible_options' => array(
				'template'      => array(
					'label'         => esc_html__( 'Columns', 'mesh' ),
					'type'          => 'select',
					'css_classes'   => array( 'mesh-choose-layout' ),
					'validation_cb' => false,
					'options_cb'    => array( $this, 'get_template_options' ),
					'id'            => 'mesh-sections-template-' . $section->ID,
				),
				'title-display' => array(
					'label'         => esc_html__( 'Display Title', 'mesh' ),
					'type'          => 'checkbox',
					'css_classes'   => array( 'mesh-section-show-title' ),
					'show_on_cb'    => false,
					'validation_cb' => false,
				),
			),
			'more_options'    => array(
				'section-id' => array(
					'label'         => esc_html__( 'Section ID', 'mesh' ),
					'type'          => 'text',
					'css_classes'   => array( 'mesh-section-id' ),
					'show_on_cb'    => false,
					'validation_cb' => false,
				),
				'css-class'  => array(
					'label'         => esc_html__( 'Section Class', 'mesh' ),
					'type'          => 'text',
					'css_classes'   => array( 'mesh-section-class' ),
					'show_on_cb'    => false,
					'validation_cb' => false,
				),
				'row-class'  => array(
					'label'         => esc_html__( 'Row Class', 'mesh' ),
					'type'          => 'text',
					'css_classes'   => array( 'mesh-row-class' ),
					'show_on_cb'    => false,
					'validation_cb' => false,
				),
				'collapse'   => array(
					'label'         => esc_html__( 'Collapse Padding', 'mesh' ),
					'type'          => 'checkbox',
					'css_classes'   => array( 'mesh-section-collapse-input' ),
					'show_on_cb'    => false,
					'validation_cb' => false,
				),
				'push-pull'  => array(
					'label'         => esc_html__( 'Push Pull', 'mesh' ),
					'type'          => 'checkbox',
					'css_classes'   => array( 'mesh-section-push' ),
					'show_on_cb'    => array( $this, 'show_push_pull' ),
					'validation_cb' => false,
				),
				'lp-equal'   => array(
					'label'       => esc_html__( 'Equalize', 'mesh' ),
					'type'        => 'checkbox',
					'css_classes' => array( 'mesh-section-equalize' ),
					'show_on_cb'  => array( $this, 'show_equalize' ),
					'validate_cb' => false,
				),
			),
		);

		$controls = apply_filters( 'mesh_section_controls', $controls );

		if ( $visible ) {
			$controls        = $controls['visible_options'];
			$container_class = 'inline-block-list mesh-section-meta-visible-list';
		} else {
			$controls        = $controls['more_options'];
			$container_class = 'small-block-grid-1 medium-block-grid-4';
		}
		?>
		<ul class="<?php echo esc_attr( $container_class ); ?>">
		<?php

		foreach ( $controls as $control_key => $control ) :

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

			$underscore_key = str_replace( '-', '_', $control_key );
			$input_value    = get_post_meta( $section->ID, '_mesh_' . esc_attr( $underscore_key ), true );

			$hidden_class = '';

			if ( 'hidden' === $control['type'] ) {
				$hidden_class = 'hidden';
			}
			?>
			<li class="mesh-section-control-<?php echo esc_attr( $control_key ); ?>">
				<label for="mesh-section[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]">
					<span class="<?php echo esc_attr( $hidden_class ); ?>">
					<?php
					if ( isset( $control['label'] ) ) {
						echo esc_html( $control['label'] );
					}
					?>
					</span>
					<?php

					$input_args = array(
						'block_id'          => esc_attr( $section->ID ),
						'post_meta_key'     => esc_attr( $underscore_key ),
						'input_type'        => sanitize_title( $control['type'] ),
						'input_name_format' => 'mesh-sections[%d][%s]',
						'input_css_classes' => $css_classes,
						'options_cb'        => ( isset( $control['options_cb'] ) ) ? $control['options_cb'] : array(),
						'options'           => ( isset( $control['options'] ) ) ? $control['options'] : array(),
					);

					$input_args['input_name'] = sprintf( $input_args['input_name_format'],
						esc_attr( $section->ID ),
						esc_attr( $underscore_key )
					);

					if ( isset( $control['id'] ) ) {
						$input_args['id'] = esc_attr( $control['id'] );
					}

					// Create our inputs.
					Mesh_Input::get_input( $control['type'], $input_args, $input_value, true, $section, $blocks );
					?>
				</label>
			</li>
		<?php endforeach; ?>
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
	public function mesh_block_controls( $block, $section_blocks ) {

		$controls = array(
			'css-class'  => array(
				'label'         => esc_html__( 'CSS Class', 'mesh' ),
				'type'          => 'text',
				'css_classes'   => array( 'mesh-section-class' ),
				'validation_cb' => false,
			),
			'offset'     => array(
				'label'         => esc_html__( 'Offset', 'mesh' ),
				'type'          => 'select',
				'css_classes'   => array( 'mesh-column-offset' ),
				'validation_cb' => false,
				'options_cb'    => array( $this, 'get_offset_options' ),
				'show_on_cb'    => array( $this, 'show_offset' ),
			),
			'centered'   => array(
				'label'         => esc_html__( 'Centered', 'mesh' ),
				'type'          => 'checkbox',
				'css_classes'   => array( 'mesh-section-centered' ),
				'show_on_cb'    => array( $this, 'show_centered' ),
				'validation_cb' => false,
			),
			'columns'    => array(
				'label'         => esc_html__( 'Width (In Columns)', 'mesh' ),
				'type'          => 'dropdown',
				'css_classes'   => array( 'mesh-block-columns', 'column-width' ),
				'validation_cb' => false,
				'options_cb'    => array( $this, 'get_columns' ),
			),
			'menu_order' => array(
				'label'         => esc_html__( 'Menu Order', 'mesh' ),
				'type'          => 'hidden',
				'css_classes'   => array( 'block-menu-order' ),
				'validation_cb' => false,
			),
		);

		$controls = apply_filters( 'mesh_block_controls', $controls );

		$block_grid = ( ( 4 - $section_blocks ) < 1 ) ? 1 : ( 4 - $section_blocks );

		?>
		<ul class="small-block-grid-1 medium-block-grid-<?php echo esc_attr( $block_grid ); ?>">
		<?php
		foreach ( $controls as $controls_key => $control ) :
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

			$underscore_key = str_replace( '-', '_', $controls_key );

			if ( 'columns' === $underscore_key ) {
				$underscore_key = 'column_width';
			}

			$input_value = get_post_meta( $block->ID, '_mesh_' . esc_attr( $underscore_key ), true );

			if ( 'column_width' === $underscore_key && empty( $input_value ) ) {
				if ( 1 === $section_blocks ) {
					$input_value = $this->block_settings['max_columns'];
				} else {
					$input_value = $this->block_settings['max_columns'] / $section_blocks;
				}
			}

			$hidden_class = '';

			if ( 'hidden' === $control['type'] ) {
				$hidden_class = 'hidden';
			}
			?>
			<li class="mesh-section-control-<?php echo esc_attr( $controls_key ); ?> <?php echo esc_attr( $hidden_class ); ?>">
				<label for="mesh-section[<?php echo esc_attr( $block->ID ); ?>][<?php echo esc_attr( $underscore_key ); ?>]">
					<span class="<?php echo esc_attr( $hidden_class ); ?>">
					<?php
					if ( isset( $control['label'] ) ) {
						echo esc_html( $control['label'] );
					}
					?>
					</span>
					<?php
					$input_args = array(
						'post_parent' => esc_attr( $block->post_parent ),
						'block_id' => esc_attr( $block->ID ),
						'post_meta_key' => esc_attr( $underscore_key ),
						'input_type' => sanitize_title( $control['type'] ),
						'input_css_classes' => $css_classes,
						'options_cb' => ( isset( $control['options_cb'] ) ) ? $control['options_cb'] : array(),
					);

					if ( isset( $control['id'] ) ) {
						$input_args['id'] = esc_attr( $control['id'] );
					}

					Mesh_Input::get_input( $control['type'], $input_args, $input_value, true, $block );
					?>
				</label>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
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