<?php
/**
 * Responsive Grid/Framework Class for Mesh
 *
 * @since      1.1.0
 * @package    Mesh
 * @subpackage Responsive_Grid
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Class Mesh_Responsive_Grid
 */
class Mesh_Responsive_Grid {

	/**
	 * Available Grid Systems,
	 * Foundation (Default)
	 * Bootstrap
	 *
	 * @since 1.1
	 *
	 * @var array
	 */
	private static $grid_systems = array();

	/**
	 * Get the current responsive grid systems we support
	 *
	 * @param string $grid_system Responsive grid are we using.
	 *
	 * @return mixed
	 */
	public static function get_responsive_grid( $grid_system = '' ) {

		$grid_systems = self::get_grid_systems();

		$mesh_options = get_option( 'mesh_settings' );

		// If we do not explicitly pass a grid_system fall back to our option.
		if ( empty( $grid_system ) ) {
			if ( isset( $mesh_options['grid_system'] ) && '' !== $mesh_options['grid_system'] ) {
				$grid_system = $mesh_options['grid_system'];
			}
		}

		// css_mode to grid conversion
		// @todo this should be converted (I think we should update css mode to be text instead of int -@aware)
		switch ( intval( $mesh_options['css_mode'] ) ) {
			case 0:
				$css_mode = 'css';
				break;
			case 2:
				$css_mode = 'bootstrap';
				break;
			case 1:
			default:
				$css_mode = 'foundation';

				if ( empty( $grid_system ) ) {
					$grid_system = 'float';
				}
		}

		// Foundation has more options for grid systems
		if ( 'foundation' === $css_mode ) {
			return $grid_systems[ $css_mode ][ $grid_system ];
		}

		return $grid_systems[ $css_mode ];
	}

	/**
	 * Get our list of grid systems available.
	 *
	 * @since 1.2.5
	 *
	 * @return array
	 */
	public static function get_grid_systems() {

		self::$grid_systems = array(
			'css'        => array( // @since 1.2.5
				'columns'       => array(
					'small'    => 'small',
					'medium'   => 'medium',
					'large'    => 'large',
					'x-large'  => 'x-large',
					'xx-large' => 'xx-large',
				),
				'offset'        => 'offset',
				'centered'      => 'centered',
				'columns_class' => 'columns',
				'row_class'     => 'row',
			),
			'foundation' => array(
				'float' => array(
					'name'          => esc_html__( 'Float Grid (legacy)', 'mesh' ),
					'columns'       => array(
						'small'    => 'small',
						'medium'   => 'medium',
						'large'    => 'large',
						'x-large'  => 'x-large',
						'xx-large' => 'xx-large',
					),
					'offset'        => 'offset',
					'centered'      => 'centered',
					'columns_class' => 'columns',
					'row_class'     => 'row', // @since 1.2.5
				),
				'flex'  => array(
					'name'          => esc_html__( 'Flex Grid (Legacy)', 'mesh' ),
					'columns'       => array(
						'small'    => 'small',
						'medium'   => 'medium',
						'large'    => 'large',
						'x-large'  => 'x-large',
						'xx-large' => 'xx-large',
					),
					'offset'        => 'offset',
					'centered'      => 'centered',
					'columns_class' => 'columns',
					'row_class'     => 'row',
				),
				'xy'    => array(
					'name'          => esc_html__( 'XY Grid', 'mesh' ),
					'columns'       => array(
						'small'    => 'small',
						'medium'   => 'medium',
						'large'    => 'large',
						'x-large'  => 'x-large',
						'xx-large' => 'xx-large',
					),
					'offset'        => 'offset',
					'centered'      => 'centered',
					'columns_class' => 'cell',
					'row_class'     => 'grid-x',
				),
			),
			'bootstrap'  => array(
				'columns'       => array(
					'x-small' => 'col-xs',
					'small'   => 'col-sm',
					'medium'  => 'col-md',
					'large'   => 'col-lg',
				),
				'offset'        => 'offset',
				'columns_class' => '',
			),
		);

		return apply_filters( 'mesh_responsive_grid_systems', self::$grid_systems );
	}
}

/**
 * Return a specific grid systems for usage in our templates.
 *
 * @since 1.2.5
 *
 * @return mixed
 */
function mesh_get_responsive_grid() {
	return Mesh_Responsive_Grid::get_responsive_grid();
}

/**
 * Get our list of filtered grid systems.
 *
 * @since 1.2.5
 *
 * @return array
 */
function mesh_get_responsive_grid_systems() {
	return Mesh_Responsive_Grid::get_grid_systems();
}