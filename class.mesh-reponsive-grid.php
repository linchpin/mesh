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
	private static $grid_systems = array(
		'foundation' => array(
			'columns' => array(
				'small' => 'small',
				'medium' => 'medium',
				'large' => 'large',
				'x-large' => 'x-large',
				'xx-large' => 'xx-large',
			),
			'offset' => 'offset',
			'centered' => 'centered',
			'columns_class' => 'columns',
		),
		'bootstrap' => array(
			'columns' => array(
				'x-small' => 'col-xs',
				'small' => 'col-sm',
				'medium' => 'col-md',
				'large' => 'col-lg',
			),
			'offset' => 'offset',
			'columns_class' => '',
		),
	);

	/**
	 * Get the current responsive grid systems we support
	 *
	 * @param string $grid_system Responsive grid are we using.
	 *
	 * @return mixed
	 */
	public static function get_responsive_grid( $grid_system = 'foundation' ) {
		return self::$grid_systems[ $grid_system ];
	}
}
