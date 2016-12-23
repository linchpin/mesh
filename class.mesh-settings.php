<?php
/**
 * Control all of our plugin Settings
 *
 * @since      1.0.0
 * @package    Mesh
 * @subpackage Settings
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

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
	public static $plugin_name = LINCHPIN_MESH_PLUGIN_NAME;

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
		add_options_page( LINCHPIN_MESH_PLUGIN_NAME, LINCHPIN_MESH_PLUGIN_NAME, 'manage_options', self::$settings_page, array( 'Mesh_Settings', 'add_options_page' ) );
		add_submenu_page( 'edit.php?post_type=mesh_template', __( 'Settings', 'mesh' ), __( 'Settings', 'mesh' ), 'manage_options', 'options-general.php?page=mesh' );
	}

	/**
	 * Create our settings section
	 *
	 * @since 1.0.0
	 */
	public static function create_section() {
		?>
		<p><?php esc_html_e( 'Below are your settings for Mesh', 'mesh' ); ?></p>
		<?php
	}

	/**
	 * Create a post type settings section.
	 */
	public static function create_post_type_section() {
		?>
		<p><?php esc_html_e( 'Select the post types that allow Mesh.', 'mesh' ); ?></p>
		<?php
	}

	/**
	 * Add all of our settings from the API
	 *
	 *
	 */
	public static function settings_init() {

		register_setting( self::$settings_page, 'mesh_settings' );
		register_setting( self::$settings_page, 'mesh_post_types' );

		// Default Settings Section.
		add_settings_section(
			'mesh_sections',
			__( 'Mesh Configurations', 'mesh' ),
			array( 'Mesh_Settings', 'create_section' ),
			self::$settings_page
		);

		// Option : CSS Mode.
		$css_mode = array(
			array( 'label' => __( 'Use Mesh CSS', 'mesh' ), 'value' => 0 ),
			array( 'label' => __( 'Disable Mesh CSS', 'mesh' ), 'value' => -1 ),
			array( 'label' => __( 'Use Foundation built into my theme', 'mesh' ), 'value' => 1 ),
			array( 'label' => __( 'Use Bootstrap', 'mesh' ), 'value' => 2 ),
		);

		// Allow filtering of available css_mode options.
		$css_mode = apply_filters( 'mesh_css_mode', $css_mode );

		add_settings_field(
			'css_mode',
			__( 'CSS Settings', 'mesh' ),
			array( 'Mesh_Settings', 'add_select' ),
			self::$settings_page,
			'mesh_sections',
			array(
				'field' => 'css_mode',
				'label' => __( 'CSS', 'mesh' ),
				'description' => __( 'Choose whether or not to enqueue CSS with Mesh.', 'mesh' ),
				'options' => $css_mode,
			)
		);

		// Option: Foundation Version
		// Add an option for Foundation Version
		// @since 1.1.3
		$foundation_version = array(
			array( 'label' => __( 'Foundation 5', 'mesh' ), 'value' => '' ),
			array( 'label' => __( 'Foundation 6', 'mesh' ), 'value' => 6 ),
		);

		add_settings_field(
			'foundation_version',
			__( 'Foundation Version', 'mesh' ),
			array( 'Mesh_Settings', 'add_select' ),
			self::$settings_page,
			'mesh_sections',
			array(
				'field' => 'foundation_version',
				'label' => __( 'Foundation Version', 'mesh' ),
				'description' => __( 'Choose which version of Foundation you are using. Foundation 5 and 6 have subtle yet important differences when it comes to markup for components like interchange that Mesh utilizes.', 'mesh' ),
				'options' => $foundation_version,
			)
		);

		// Add an option for each post type.
		if ( $post_types = get_post_types() ) {
			add_settings_section(
				'mesh_post_type_section',
				__( 'Post Types', 'mesh' ),
				array( 'Mesh_Settings', 'create_post_type_section' ),
				self::$settings_page
			);

			foreach ( $post_types as $post_type ) {
				$post_type_object = get_post_type_object( $post_type );

				// Skip any of the following post types and post types that ARE NOT public.
				if ( in_array( $post_type, array( 'revision', 'nav_menu_item', 'attachment', 'mesh_template' ) ) || ! $post_type_object->public ) {
					continue;
				}

				add_settings_field(
					'mesh_post_types_' . $post_type,
					$post_type_object->labels->name,
					array( 'Mesh_Settings', 'add_checkbox' ),
					self::$settings_page,
					'mesh_post_type_section',
					array(
						'post_type' => $post_type_object->name,
						'name' => $post_type_object->labels->name,
					)
				);
			}
		}
	}

	/**
	 * Add our options page wrapper Form
	 */
	public static function add_options_page() {

		$tabs = self::get_tabs();

		$default_tab = self::get_default_tab_slug();

		$active_tab = isset( $_GET[ 'tab' ] ) && array_key_exists( $_GET['tab'], $tabs ) ? $_GET[ 'tab' ] : $default_tab;

		include_once( LINCHPIN_MESH___PLUGIN_DIR . '/admin/settings.php' );
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
		<input type="<?php esc_attr_e( $args['type'] ); ?>" class="<?php esc_attr_e( $args['class'] ); ?>" name="mesh_settings[<?php esc_attr_e( $args['field'] ); ?>]" value="<?php esc_attr_e( $options[ $args['field'] ] ); ?>">

		<?php
	}

	/**
	 * Used any time we need to add in a select field
	 *
	 * @param array $args Args for Customization.
	 */
	public static function add_select( $args ) {

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
	 * Create a checkbox field.
	 *
	 * @param $args
	 */
	public static function add_checkbox( $args ) {

		/**
		 * Define our field defaults
		 */
		$defaults = array(
			'class'       => '',
			'description' => '',
			'label'       => '',
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		$options = get_option( 'mesh_post_types' );

		$checked = false;

		if ( ! empty( $options[ $args['post_type'] ] ) ) {
			$checked = true;
		}
		?>

		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php esc_html_e( $args['description'] ); ?></p>
		<?php endif; ?>

		<input type="checkbox" class="<?php esc_attr_e( $args['class'] ); ?>" name="mesh_post_types[<?php esc_attr_e( $args['post_type'] ); ?>]" value="1" <?php checked( $checked ); ?>>

		<?php
	}

	/**
	 * Allow filtering of the settings tabs
	 *
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
	 * @return   array $tabs Settings tabs
	 */
	static public function get_tabs() {

		$tabs                 = array();
		$tabs['settings']  = __( 'Settings',   'mesh' );
		$tabs['faq']       = __( 'About Mesh', 'mesh' );
		$tabs['changelog'] = __( 'Change Log', 'mesh' );
		$tabs['linchpin']  = __( 'About Linchpin', 'mesh' );

		return apply_filters( 'mesh_tabs', $tabs );
	}
}
