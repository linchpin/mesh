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

		add_filter( 'plugin_action_links', array( 'Mesh_Settings', 'add_settings_link' ), 10, 5 );
	}

	/**
	 * Add the options page to our settings menu
	 */
	public static function add_admin_menu() {
		add_options_page( LINCHPIN_MESH_PLUGIN_NAME, LINCHPIN_MESH_PLUGIN_NAME, 'manage_options', self::$settings_page, array( 'Mesh_Settings', 'add_options_page' ) );
		add_submenu_page( 'edit.php?post_type=mesh_template', esc_html__( 'Settings', 'mesh' ), esc_html__( 'Settings', 'mesh' ), 'manage_options', 'options-general.php?page=mesh' );
	}

	public static function add_settings_link( $actions, $plugin_file ) {
		static $plugin;

		if ( ! isset( $plugin ) ) {
			$plugin = 'mesh/mesh.php';
		}

		if ( $plugin === $plugin_file ) {

			$settings  = array( 'settings' => '<a href="options-general.php?page=mesh">' . esc_html__( 'Settings', 'mesh' ) . '</a>' );
			$site_link = array( 'faq' => '<a href="https://meshplugin.com/knowledgebase/" target="_blank">' . esc_html__( 'FAQ', 'mesh' ) . '</a>');

			$actions = array_merge( $settings, $actions );
			$actions = array_merge( $site_link, $actions );

		}

		return $actions;
	}

	/**
	 * Create our settings section
	 *
	 * @param $args
	 * @since 1.0.0
	 */
	public static function create_section( $args ) {
		?>
		<div class="gray-bg negative-bg">
			<div class="wrapper">
				<h2 class="color-darkpurple light-weight">
					<?php echo esc_html( $args['title'] ); ?>
				</h2>
			</div>
		</div>
		<?php
	}

	/**
	 * Create a post type settings section.
	 */
	public static function create_post_type_section() {
		?>
		<div class="gray-bg negative-bg">
			<div class="wrapper">
				<h2 class="color-darkpurple light-weight">
					<?php esc_html_e( 'Enable Mesh for the following Post Types', 'mesh' ); ?>
				</h2>
			</div>
		</div>
		<div class="wrapper">
			<p><?php esc_html_e( 'Select the post types that allow Mesh functionality.', 'mesh' ); ?></p>
		</div>

		<?php
	}

	/**
	 * Validate that the user has enabled a post type
	 *
	 * @since 1.2.4
	 * @param $input
	 *
	 * @return $input
	 */
	public static function validate_mesh_post_types( $input ) {
		$message = '';
		$type = '';

		if ( null !== $input ) {
			$message = esc_html__( 'Mesh Settings Saved.', 'mesh' );
			$type = 'updated';
		} else {
			$message = esc_html__( 'Mesh Settings Saved. You have disabled all post types. We suggest disabling the plugin if it is no longer needed', 'mesh' );
			$type = 'updated';
		}

		add_settings_error( 'mesh_post_types', 'mesh_post_types_notice', $message, $type );

		$input['mesh_template'] = '1'; // Always make sure we have a mesh_template in our options

		return $input;
	}

	/**
	 * Add all of our settings from the API
	 */
	public static function settings_init() {

		register_setting( self::$settings_page, 'mesh_settings' );
		register_setting( self::$settings_page, 'mesh_post_types', array( 'Mesh_Settings', 'validate_mesh_post_types' ) );

		// Default Settings Section.
		add_settings_section(
			'mesh_sections',
			esc_html__( 'Basic Settings', 'mesh' ),
			array( 'Mesh_Settings', 'create_section' ),
			self::$settings_page
		);

		// Option : CSS Mode.
		$css_mode = array(
			array(
				'label' => esc_html__( 'Use Mesh CSS', 'mesh' ),
				'value' => 0,
			),
			array(
				'label' => esc_html__( 'Disable Mesh CSS', 'mesh' ),
				'value' => -1,
			),
			array(
				'label' => esc_html__( 'Use Foundation built into my theme', 'mesh' ),
				'value' => 1,
			),
			array(
				'label' => esc_html__( 'Use Bootstrap', 'mesh' ),
				'value' => 2,
			),
		);

		// Allow filtering of available css_mode options.
		$css_mode = apply_filters( 'mesh_css_mode', $css_mode );

		add_settings_field(
			'css_mode',
			esc_html__( 'CSS Settings', 'mesh' ),
			array( 'Mesh_Settings', 'add_select' ),
			self::$settings_page,
			'mesh_sections',
			array(
				'field' => 'css_mode',
				'label' => esc_html__( 'CSS', 'mesh' ),
				'description' => esc_html__( 'Choose whether or not to enqueue CSS with Mesh.', 'mesh' ),
				'options' => $css_mode,
			)
		);

		/*
		 * Option: Foundation Version
		 * Add an option for Foundation Version
		 * @since 1.1.3
		 */
		$foundation_version = array(
			array(
				'label' => esc_html__( 'Foundation 5', 'mesh' ),
				'value' => '',
			),
			array(
				'label' => esc_html__( 'Foundation 6', 'mesh' ),
				'value' => 6,
			),
		);

		add_settings_field(
			'foundation_version',
			esc_html__( 'Foundation Version', 'mesh' ),
			array( 'Mesh_Settings', 'add_select' ),
			self::$settings_page,
			'mesh_sections',
			array(
				'field' => 'foundation_version',
				'label' => esc_html__( 'Foundation Version', 'mesh' ),
				'description' => esc_html__( 'Choose which version of Foundation you are using. Foundation 5 and 6 have subtle yet important differences when it comes to markup for components like interchange that Mesh utilizes.', 'mesh' ),
				'options' => $foundation_version,
			)
		);

		// Add an option for each post type.
		$post_types = get_post_types();

		if ( ! empty( $post_types ) ) {
			add_settings_section(
				'mesh_post_type_section',
				esc_html__( 'Post Types', 'mesh' ),
				array( 'Mesh_Settings', 'create_post_type_section' ),
				self::$settings_page
			);

			foreach ( $post_types as $post_type ) {
				$post_type_object = get_post_type_object( $post_type );

				// Skip any of the following post types and post types that ARE NOT public.
				if ( in_array( $post_type, array( 'revision', 'nav_menu_item', 'attachment', 'mesh_template' ), true ) || ! $post_type_object->public ) {
					continue;
				}

				add_settings_field(
					'mesh_post_types_' . $post_type,
					$post_type_object->labels->name,
					array( 'Mesh_Settings', 'add_checkbox' ),
					self::$settings_page,
					'mesh_post_type_section',
					array(
						'field'    => $post_type_object->name,
						'name'    => $post_type_object->labels->name,
						'options' => 'mesh_post_types',
					)
				);
			}
		}

		// Uninstall Option

		// Default Settings Section.
		add_settings_section(
			'mesh_uninstall',
			esc_html__( 'Mesh Uninstall', 'mesh' ),
			array( 'Mesh_Settings', 'create_section' ),
			self::$settings_page
		);

		add_settings_field(
			'mesh_uninstall',
			esc_html__( 'Remove All Data on Uninstall?', 'mesh' ),
			array( 'Mesh_Settings', 'add_checkbox' ),
			self::$settings_page,
			'mesh_uninstall',
			array(
				'options'     => 'mesh_settings',
				'field'       => 'uninstall',
				'label'       => esc_html__( 'Uninstall', 'mesh' ),
				'type'        => $post_type_object->name,
				'name'        => $post_type_object->labels->name,
			)
		);
	}

	/**
	 * Add our options page wrapper Form
	 */
	public static function add_options_page() {

		$tabs = self::get_tabs();

		$default_tab = self::get_default_tab_slug();

		$active_tab = isset( $_GET['tab'] ) && array_key_exists( sanitize_text_field( wp_unslash( $_GET['tab'] ) ), $tabs ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : $default_tab; // WPCS: input var okay, CSRF ok.

		include_once( LINCHPIN_MESH___PLUGIN_DIR . '/admin/settings.php' );
	}

	/**
	 * FIELD CONTROLS
	 *
	 * Below you will find all field control.
	 */

	/**
	 * Build out our settings fields as needed.
	 * Echos our field html.
	 *
	 * @since 1.0.0
	 * @param object $args An Object of our field customizations.
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

		$options = get_option( 'mesh_settings' );
		?>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<input type="<?php echo esc_attr( $args['type'] ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>" name="mesh_settings[<?php echo esc_attr( $args['field'] ); ?>]" value="<?php echo esc_attr( $options[ $args['field'] ] ); ?>">

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
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<label for="mesh-<?php echo esc_attr( $args['field'] ); ?>"><?php echo esc_html( $args['label'] ); ?></label>
		<select id="mesh-<?php echo esc_attr( $args['field'] ); ?>" name="mesh_settings[<?php echo esc_attr( $args['field'] ); ?>]">
			<?php foreach ( $select_options as $option ) : ?>
				<?php

				$selected = '';

				if ( ! empty( $options[ $args['field'] ] ) ) {
					$selected = selected( $options[ $args['field'] ], $option['value'], false );
				}
				?>
				<option value="<?php echo esc_attr( $option['value'] ); ?>" <?php echo esc_html( $selected ); ?>><?php echo esc_html( $option['label'] ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Create a checkbox field.
	 *
	 * @param array $args Customizations.
	 */
	public static function add_checkbox( $args ) {

		/**
		 * Define our field defaults
		 */
		$defaults = array(
			'class'       => '',
			'description' => '',
			'label'       => '',
			'options'     => '', // @since 1.2.4
		);

		// Parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args['options'] ) ) { // If we don't have any option, die early.
			return;
		}

		$options = get_option( $args['options'] );
		$checked = false;

		if ( ! empty( $options[ $args['field'] ] ) ) {
			$checked = true;
		}
		?>

		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>

		<input type="checkbox" class="<?php echo esc_attr( $args['class'] ); ?>" name="<?php echo esc_attr( $args['options'] ); ?>[<?php echo esc_attr( $args['field'] ); ?>]" value="1" <?php checked( $checked ); ?>>

		<?php
	}

	/**
	 * Allow filtering of the settings tabs.
	 *
	 * @param array $default_settings Default Settings Array.
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
		$tabs = array(
			'settings'  => esc_html__( 'Settings',   'mesh' ),
			'about'     => esc_html__( 'About Mesh', 'mesh' ),
			'new'       => esc_html__( "What's New", 'mesh' ),
			'changelog' => esc_html__( 'Change Log', 'mesh' ),
		);

		return apply_filters( 'mesh_tabs', $tabs );
	}
}
