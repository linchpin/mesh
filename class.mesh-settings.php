<?php
/**
 * Control all of our plugin Settings
 *
 * @since      1.0.0
 * @package    Mesh
 * @subpackage Settings
 */

/**
 * Class Mesh_Settings
 */
class Mesh_Settings {

	/**
	 * Define our settings page
	 *
	 * @var string
	 */
	public static $settings_page = 'mesh';

	/**
	 * Give our plugin a name
	 *
	 * @var string
	 */
	public static $plugin_name = LINCHPIN_MCS_PLUGIN_NAME;

	/**
	 * Initialize our plugin settings.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( 'Mesh_Settings', 'add_admin_menu' ) );
		add_action( 'admin_init', array( 'Mesh_Settings', 'settings_init' ) );
	}

	/**
	 * Add the options page to our settings menu
	 */
	public static function add_admin_menu() {
		add_options_page( LINCHPIN_MCS_PLUGIN_NAME, LINCHPIN_MCS_PLUGIN_NAME, 'manage_options', self::$settings_page, array( 'Mesh_Settings', 'add_options_page' ) );
	}

	/**
	 * Create our settings section
	 *
	 * @since 1.0.0
	 */
	public static function create_section() {
		esc_html_e( 'Below are your settings for Mesh', 'linchpin-mcs' );
	}

	/**
	 * Add all of our settings from the API
	 */
	public static function settings_init() {

		register_setting( self::$settings_page, 'mesh_settings' );

		// Default Settings Section.
		add_settings_section(
			'mesh_sections',
			__( 'Mesh Configurations', 'linchpin-mcs' ),
			array( 'Mesh_Settings', 'create_section' ),
			self::$settings_page
		);

		// Option : CSS Mode.
		$css_mode = array(
			array( 'label' => __( 'Use Mesh CSS', 'linchpin-mcs' ), 'value' => '' ),
			array( 'label' => __( 'Disable Mesh CSS', 'linchpin-mcs' ), 'value' => 0 ),
			array( 'label' => __( 'Use Foundation w/ my theme', 'linchpin-mcs' ), 'value' => 1 ),
			array( 'label' => __( 'Use Bootstrap (coming soon)', 'linchpin-mcs' ), 'value' => 2 ),
		);

		// Allow filtering of available css_mode options.
		$css_mode = apply_filters( 'css_mode', $css_mode );

		add_settings_field(
			'css_mode',
			__( 'CSS Settings', 'linchpin-mcs' ),
			array( 'Mesh_Settings', 'add_select' ),
			self::$settings_page,
			'mesh_sections',
			array(
				'field' => 'css_mode',
				'label' => __( 'CSS', 'linchpin-mcs' ),
				'description' => __( 'Choose whether or not to enqueue CSS with Mesh.', 'linchpin-mcs' ),
				'options' => $css_mode,
			)
		);
	}

	/**
	 * Add our options page wrapper Form
	 */
	public static function add_options_page() {

		$tabs = self::get_tabs();

		$default_tab = self::get_default_tab_slug();

		$active_tab = isset( $_GET[ 'tab' ] ) && array_key_exists( $_GET['tab'], $tabs ) ? $_GET[ 'tab' ] : $default_tab;

		include_once( LINCHPIN_MCS___PLUGIN_DIR . '/admin/settings.php' );
	}

	/**
	 * FIELD CONTROLS
	 *
	 * Below you will find all field control.
	 */

	/**
	 * Build out our settings fields as needed.
	 *
	 * @since 1.0.0
	 * @param object $args An Object of our field customizations.
	 *
	 * @return void Echos our field html.
	 */
	public static function add_textfield( $args ) {

		/**
		 * Define our field defaults
		 */
		$defaults = array(
			'type'        => 'text',
			'class'       => '',
			'description' => '',
			'label'       => '',
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		$options = get_option( 'mesh_settings' ); ?>

		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php esc_html_e( $args['description'] ); ?></p>
		<?php endif; ?>

		<input type="<?php esc_attr_e( $args['type'] ); ?>" class="<?php esc_attr_e( $args['class'] ); ?>" name="clapi_settings[<?php esc_attr_e( $args['field'] ); ?>]" value='<?php esc_attr_e( $options[ $args['field'] ] ); ?>'>

		<?php
	}

	/**
	 * Used any time we need to add in a select field
	 *
	 * @param array $args Args for Customization.
	 */
	public static function add_select( $args ) {

		echo 'this is something htat sadsad adsa';

		/**
		 * Define our field defaults
		 */
		$defaults = array(
			'class'   => '',
			'options' => array(),
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		$options = get_option( 'mesh_settings' );

		$select_options = $args['options'];

		?>

		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php esc_html_e( $args['description'] ); ?></p>
		<?php endif; ?>

		<label for="mesh-<?php esc_attr_e( $args['field'] ); ?>"><?php esc_html_e( $args['label'] ); ?></label>

		<select id="mesh-<?php esc_attr_e( $args['field'] ); ?>" name="mesh_settings[<?php esc_attr_e( $args['field'] ); ?>]">
			<?php foreach ( $select_options as $option ) : ?>
				<option value="<?php esc_attr_e( $option['value'] ); ?>" <?php selected( $options[ $args['field'] ], $option['value'] ); ?>><?php esc_html_e( $option['label'] ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * @param $default_settings
	 *
	 * @return array
	 */
	static private function apply_tab_slug_filters( $default_settings ) {

		$extended_settings[] = array();
		$extended_tabs       = self::get_tabs();

		foreach ( $extended_tabs as $tab_slug => $tab_desc ) {

			$options = isset( $default_settings[ $tab_slug ] ) ? $default_settings[ $tab_slug ] : array();

			$extended_settings[ $tab_slug ] = apply_filters( 'mesh_' . $tab_slug, $options );
		}

		return $extended_settings;
	}

	/**
	 * Get the default tab slug
	 *
	 * @return mixed
	 */
	static public function get_default_tab_slug() {

		return key( self::get_tabs() );
	}

	/**
	 * Retrieve settings tabs
	 *
	 * @since    1.0.0
	 * @return    array    $tabs    Settings tabs
	 */
	static public function get_tabs() {

		$tabs                 = array();
		$tabs['settings']  = __( 'Settings', 'linchpin-mcs' );
		$tabs['faq']       = __( 'About Mesh', 'linchpin-mcs' );
		$tabs['changelog'] = __( 'Change Log', 'linchpin-mcs' );

		return apply_filters( 'mesh_tabs', $tabs );
	}
}
