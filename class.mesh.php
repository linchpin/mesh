<?php
/**
 * Class Mesh
 */
class Mesh {

	/**
	 * Store available templates.
	 *
	 * @var array
	 */
	public $templates = array();

	/**
	 * Store all our TinyMCE Editors
	 * @var array
	 */
	public $tinymce_editors = array();

	/**
	 * Store the available blocks per template.
	 *
	 * @since 0.3.5
	 *
	 * @var array
	 */
	public $template_data = array();

	/**
	 * __construct function.
	 *
	 * @access public
	 */
	function __construct() {

		$this->template_data = array(
			'mesh-columns-1.php' => array(
				'label' => __( '1 Columns', 'linchpin-mesh' ),
				'blocks' => 1,
				'widths' => array( 12 ),
			),
			'mesh-columns-2.php' => array(
				'label' => __( '2 Columns', 'linchpin-mesh' ),
				'blocks' => 2,
				'widths' => array( 6, 6 ),
			),
			'mesh-columns-3.php' => array(
				'label' => __( '3 Columns', 'linchpin-mesh' ),
				'blocks' => 3,
				'widths' => array( 4, 4, 4 ),
			),
		);

		add_action( 'init',                  array( $this, 'init' ) );

		add_action( 'edit_page_form',        array( $this, 'edit_page_form' ) ); // Pages.
		add_action( 'edit_form_advanced',    array( $this, 'edit_page_form' ) ); // Other Post Types.

		add_action( 'save_post',             array( $this, 'save_post' ), 10, 2 );

		add_action( 'loop_end',              array( $this, 'loop_end' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );

		add_action( 'wp_enqueue_scripts',    array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts',    array( $this, 'wp_enqueue_styles' ) );

		add_filter( 'content_edit_pre',      array( $this, 'the_content' ) );
		add_filter( 'the_content',           array( $this, 'the_content' ), 5 );
		add_filter( 'post_class',            array( $this, 'post_class' ) );

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			include_once( 'class.mesh-ajax.php' );
		}

		// Adjust TinyMCE and Media buttons.
		add_filter( 'tiny_mce_before_init', array( $this, 'tiny_mce_before_init' ) );

		// Add Screen Options.
		add_action( 'admin_menu',           array( $this, 'admin_menu' ) );
	}

	/**
	 * Add Screen Options to the Plugin
	 */
	function admin_menu() {
		add_action( 'load-post.php', array( $this, 'add_screen_options' ) );
		add_filter( 'set-screen-option', array( $this, 'set_screen_option' ), 10, 3 );
	}

	/**
	 * Save our custom display options
	 *
	 * @since 0.4.4
	 *
	 * @param string  $status Save status
	 * @param array   $option Option we're saving
	 * @param mixed   $value  Value to save
	 *
	 * @return void|mixed
	 */
	function set_screen_option( $status, $option, $value ) {
		if ( 'linchpin_mesh_section_kitchensink' === $option ) {
			return $value;
		}

		return;
	}

	/**
	 * Add Toggleable Options to show or hide controls
	 *
	 * @since 0.4.4
	 */
	function add_screen_options() {
		$screen = get_current_screen();

		if ( ! is_object( $screen ) || 'page' !== $screen->id ) {
			return;
		}

		$args = array(
			'label'   => __( 'Show Extra MCS Section Controls?', 'linchpin-mesh' ),
			'default' => 0,
			'option'  => 'linchpin_mesh_section_kitchensink',
		);

		add_screen_option( 'linchpin_mesh_section_kitchensink', $args );
	}

	/**
	 * Update our tiny MCE w/ our own settings.
	 *
	 * @param array $in
	 *
	 * @return array $in Input Object
	 */
	function tiny_mce_before_init( $in ) {

		global $post;

		// Exclude the default editor from our customizations.
		if ( '#content' === $in['selector'] ) {
			return $in;
		}

		$in['remove_linebreaks']   = false;
		$in['gecko_spellcheck']    = false;
		$in['keep_styles']         = true;
		$in['accessibility_focus'] = true;
		$in['tabfocus_elements']   = 'major-publishing-actions';
		$in['media_strict']        = false;
		$in['paste_remove_styles'] = false;
		$in['paste_remove_spans']  = false;
		$in['paste_strip_class_attributes'] = 'none';
		$in['paste_text_use_dialog'] = true;
		$in['wpeditimage_disable_captions'] = true;
		$in['plugins'] = 'tabfocus,paste,media,wordpress,wpgallery,wplink';
		$in['content_css'] = get_template_directory_uri() . '/editor-style.css';
		$in['wpautop'] = true;
		$in['apply_source_formatting'] = false;
		$in['block_formats'] = 'Paragraph=p; Heading 3=h3; Heading 4=h4';
		$in['toolbar1'] = 'bold,italic,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,alignjustify,link,wp_adv ';
		$in['toolbar2'] = 'formatselect,underline,strikethrough,forecolor,pastetext,removeformat ';
		$in['toolbar3'] = '';
		$in['toolbar4'] = '';

		return $in;
	}

	/**
	 * Init function.
	 *
	 * @access public
	 * @return void
	 */
	function init() {

		$labels = array(
			'name'                => _x( 'Content Section', 'Content Section', 'linchpin-mesh' ),
			'singular_name'       => _x( 'Content Section', 'Content Section', 'linchpin-mesh' ),
			'menu_name'           => __( 'Content Section', 'linchpin-mesh' ),
			'name_admin_bar'      => __( 'Content Section', 'linchpin-mesh' ),
			'parent_item_colon'   => __( 'Parent Content Section:', 'linchpin-mesh' ),
			'all_items'           => __( 'All Content Sections', 'linchpin-mesh' ),
			'add_new_item'        => __( 'Add New Content Section', 'linchpin-mesh' ),
			'add_new'             => __( 'Add New', 'linchpin-mesh' ),
			'new_item'            => __( 'New Content Section', 'linchpin-mesh' ),
			'edit_item'           => __( 'Edit Content Section', 'linchpin-mesh' ),
			'update_item'         => __( 'Update Content Section', 'linchpin-mesh' ),
			'view_item'           => __( 'View Content Section', 'linchpin-mesh' ),
			'search_items'        => __( 'Search Content Sections', 'linchpin-mesh' ),
			'not_found'           => __( 'Not found', 'linchpin-mesh' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'linchpin-mesh' ),
		);

		register_post_type( 'mesh_section', array(
			'label'               => __( 'Content Section', 'linchpin-mesh' ),
			'description'         => __( 'Content Section', 'linchpin-mesh' ),
			'labels'              => $labels,
			'public' => false,
			'hierarchical' => true,
			'supports' => array( 'title','editor','author','thumbnail','excerpt' ),
			'capability_type' => 'post',
			'has_archive' => false,
			'show_in_menus' => false,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'show_ui' => false,
			'rewrite' => null,
		) );
	}

	/**
	 * edit_form_advanced function.
	 *
	 * @access public
	 *
	 * @todo Add the ability to select which post can have Multple Content Sections
	 *
	 * @param object $post WordPress Post Object.
	 *
	 * @return void
	 */
	function edit_page_form( $post ) {
		$content_sections = mesh_get_sections( $post->ID, 'array', true );
		$mesh_notifications = get_user_option( 'linchpin_mesh_notifications', get_current_user_id() );
		?>
		<div id="mesh-container">
			<?php wp_nonce_field( 'mesh_content_sections_nonce', 'mesh_content_sections_nonce' ); ?>


			<div class="notice below-h2 mesh-row mesh-main-ua-row<?php if ( empty( $content_sections ) ) { echo ' hide'; } ?>">
				<div class="mesh-columns-3 columns">
					<p class="title"><?php esc_html_e( 'Multiple Content Sections', 'linchpin-mesh' ); ?></p>
				</div>

				<div class="mesh-columns-9 columns text-right">
					<?php include LINCHPIN_MESH___PLUGIN_DIR .'admin/controls.php'; ?>
				</div>
			</div>

			<?php if ( empty( $content_sections ) ) : ?>
				<div id="mesh-description" class="description notice below-h2 text-center lead empty-sections-message">
					<p><?php esc_html_e( 'You do not have any Content Sections.', 'linchpin-mesh' ); ?></p>
					<p><?php esc_html_e( 'Get started using Mesh by adding a Content Section now.', 'linchpin-mesh' ); ?></p>
					<p><a href="#" class="button primary mesh-section-add dashicons-before dashicons-plus"><?php esc_html_e( 'Add Section', 'linchpin-mesh' ); ?></a></p>
				</div>
			<?php else : ?>
				<?php if ( empty( $mesh_notifications['intro'] ) ) : ?>
					<div id="mesh-description" class="description notice is-dismissible notice-info below-h2" data-type="intro">
						<p><?php esc_html_e( 'Multiple content sections allow you to easily segment your page\'s contents into different blocks of markup.', 'linchpin-mesh' ); ?></p>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<div id="mesh-container">
				<?php foreach ( $content_sections as $key => $section ) : ?>
					<?php mesh_add_section_admin_markup( $section, true ); ?>
				<?php endforeach; ?>
			</div>

			<div class="notice below-h2 mesh-row mesh-main-ua-row<?php if ( empty( $content_sections ) ) { echo ' hide'; } ?>">
				<div class="mesh-columns-12 columns text-right">
					<?php include LINCHPIN_MESH___PLUGIN_DIR .'admin/controls.php'; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * save_post function.
	 *
	 * @access public
	 *
	 * @param mixed  $post_id
	 * @param object $post
	 *
	 * @return void
	 */
	function save_post( $post_id, $post ) {
		// Skip revisions and autosaves.
		if ( wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}

		// Users should have the ability to edit listings.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST['mesh_content_sections_nonce'] ) || ! wp_verify_nonce( $_POST['mesh_content_sections_nonce'], 'mesh_content_sections_nonce' )  ) {
			return;
		}

		if ( empty( $_POST['mesh-sections'] ) ) {
			return;
		}

		remove_action( 'save_post', array( $this, 'save_post' ), 10 );

		$count = 0;

		// Check if we are doing a section update via AJAX
		$saving_section_via_ajax = false;
		$ajax_section_id = 0;

		if ( ! empty( $_POST['action'] ) && 'mesh_save_section' == $_POST['action'] ) {
			$saving_section_via_ajax = true;
			$ajax_section_id = (int) $_POST['mesh_section_id'];
		}

		foreach ( $_POST['mesh-sections'] as $section_id => $section_data ) {

			// Sections that we don't want to update
			if ( $saving_section_via_ajax && $ajax_section_id !== $section_id ) {
				continue;
			}

			$section = get_post( (int) $section_id );

			if ( 'mesh_section' !== $section->post_type ) {
				continue;
			}

			if ( ! $saving_section_via_ajax ) {
				$status = sanitize_post_field( 'post_status', $section_data['post_status'], $post_id, 'attribute' );

				if ( ! in_array( $status, array( 'publish', 'draft' ), true ) ) {
					$status = 'draft';
				}

				$updates = array(
					'ID'           => (int) $section_id,
					'post_title'   => sanitize_text_field( $section_data['post_title'] ),
					'post_content' => '', // Sections don't have content
					'post_status'  => $status,
					'menu_order'   => $count,
				);

				wp_update_post( $updates );

				$count ++;
			}

			// Save Template.
			$template = sanitize_text_field( $section_data['template'] );

			if ( empty( $template ) ) {
				delete_post_meta( $section->ID, '_mesh_template' );
			} else {
				update_post_meta( $section->ID, '_mesh_template', $template );
			}

			// Save CSS Classes.
			$css_classes = explode( ' ', $section_data['css_class'] );
			$sanitized_css_classes = array();

			foreach ( $css_classes as $css ) {
				$sanitized_css_classes[] = sanitize_html_class( $css );
			}

			$sanitized_css_classes = implode( ' ', $sanitized_css_classes );

			if ( empty( $sanitized_css_classes ) ) {
				delete_post_meta( $section->ID, '_mesh_css_class' );
			} else {
				update_post_meta( $section->ID, '_mesh_css_class', $sanitized_css_classes );
			}

			// Save LP Equal.
			$lp_equal = '';
			if ( ! empty( $section_data['lp_equal'] ) ) {
				$lp_equal = sanitize_text_field( $section_data['lp_equal'] );
			}

			if ( empty( $lp_equal ) ) {
				delete_post_meta( $section->ID, '_mesh_lp_equal' );
			} else {
				update_post_meta( $section->ID, '_mesh_lp_equal', $lp_equal );
			}

			// Save Title Display
			if ( empty( $section_data['title_display'] ) ) {
				delete_post_meta( $section->ID, '_mesh_title_display' );
			} else {
				update_post_meta( $section->ID, '_mesh_title_display', $section_data['title_display'] );
			}

			// Save Push / Pull.
			if ( empty( $section_data['push_pull'] ) ) {
				delete_post_meta( $section->ID, '_mesh_push_pull' );
			} else {
				update_post_meta( $section->ID, '_mesh_push_pull', $section_data['push_pull'] );
			}

			// Save Collapse.
			if ( empty( $section_data['collapse'] ) ) {
				delete_post_meta( $section->ID, '_mesh_collapse' );
			} else {
				update_post_meta( $section->ID, '_mesh_collapse', $section_data['collapse'] );
			}

			// Process the section's blocks.
			$blocks = array();

			if ( ! empty( $section_data['blocks'] ) ) {
				$blocks = $section_data['blocks'];
			}

			foreach ( $blocks as $block_id => $block_data ) {
				$block = get_post( (int) $block_id );

				if ( empty( $block ) || 'mesh_section' !== $block->post_type || $section->ID !== $block->post_parent ) {
					continue;
				}

				if ( empty( $status ) ) {
					$status = 'draft';
				}

				$updates = array(
					'ID'           => (int) $block_id,
					'post_content' => wp_kses( $block_data['post_content'], mesh_get_allowed_html() ),
					'post_status'  => $status,
					'post_title'   => sanitize_text_field( $block_data['post_title'] ),
				);

				wp_update_post( $updates );

				$block_column_width = (int) $section_data['blocks'][ $block_id ]['columns'];

				// If we don't have a column width defined or we are using a 1 column layout clear our saved widths.
				if ( empty( $block_column_width ) || 'mesh-columns-1.php' === $template ) {
					delete_post_meta( $block_id, '_mesh_column_width' );
				} else {
					update_post_meta( $block_id, '_mesh_column_width', $block_column_width );
				}

				// Save Column Offset.
				$offset = (int) $section_data['blocks'][ $block_id ]['offset'];

				if ( empty( $offset ) ) {
					delete_post_meta( $block_id, '_mesh_offset' );
				} else {
					update_post_meta( $block_id, '_mesh_offset', $offset );
				}

				/**
				 * @todo: optimize this loop into a utility method
				 */

				$block_css_class = $section_data['blocks'][ $block_id ]['css_class'];

				// Save CSS Classes.
				$css_classes = explode( ' ', $block_css_class );
				$sanitized_css_classes = array();

				foreach ( $css_classes as $css ) {
					$sanitized_css_classes[] = sanitize_html_class( $css );
				}

				$sanitized_css_classes = implode( ' ', $sanitized_css_classes );

				if ( empty( $sanitized_css_classes ) ) {
					delete_post_meta( $block_id, '_mesh_css_class' );
				} else {
					update_post_meta( $block_id, '_mesh_css_class', $sanitized_css_classes );
				}
			}
		}

		// Save a block's content into its section, and then into it's page.
		$section_posts = mesh_get_sections( $post_id );

		if ( ! empty( $section_posts ) ) {
			foreach ( $section_posts as $p ) {
				if ( empty( $current_section_page ) ) {
					if ( $saving_section_via_ajax ) {
						$current_section_page = $p->post_parent;
					} else {
						$current_section_page = $post_id;
					}
				}

				$section_content = array();

				$blocks = mesh_get_section_blocks( $p->ID );

				foreach ( $blocks as $b ) {
					$section_content[] = strip_tags( $b->post_content );
				}

				wp_update_post( array(
					'ID'           => $p->ID,
					'post_content' => implode( ' ', $section_content ),
				) );
			}

			// Get the sections again.
			$section_posts = mesh_get_sections( $post_id );
			$page_content_sections = array();
			$page_content_sections[] = '<div id="mesh-section-content">';

			foreach ( $section_posts as $p ) {
				if ( 'publish' !== $p->post_status ) {
					continue;
				}

				$page_content_sections[] = strip_tags( $p->post_title );
				$page_content_sections[] = strip_tags( $p->post_content );
			}

			$page_content_sections[] = '</div>';

			wp_update_post( array(
				'ID' => $current_section_page,
				'post_content' => $post->post_content . implode( ' ' , $page_content_sections ),
			) );
		}

		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
	}

	/**
	 * Simple loop to get our sections
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	function the_content( $content ) {
		$pos = strpos( $content, '<div id="mesh-section-content">' );

		if ( false !== $pos ) {
			$content = substr( $content, 0, ( strlen( $content ) - $pos ) * -1 );
		}

		return $content;
	}

	/**
	 * post_class function.
	 *
	 * Filter custom classes to section container
	 *
	 * @param array $classes
	 *
	 * @return array
	 */
	function post_class( $classes ) {
		if ( $custom_class = get_post_meta( get_the_ID(), '_mesh_css_class', true ) ) {
			$classes[] = esc_attr( $custom_class );
		}

		return $classes;
	}

	/**
	 * At the end of a page, let's show sections.
	 *
	 * @todo: When we add support for other post types, this will need to change.
	 *
	 * @param $wp_query
	 */
	function loop_end( $wp_query ) {
		if ( ! $wp_query->is_main_query() ) {
			return;
		}

		if ( ! is_page() ) {
			return;
		}

		mesh_display_sections( $wp_query->post->ID );
	}

	/**
	 * admin_enqueue_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_enqueue_scripts() {
		global $current_screen, $post;

		if ( 'post' !== $current_screen->base ) {
			return;
		}

		wp_enqueue_script( 'admin-mesh', plugins_url( 'assets/js/admin-mesh.js', __FILE__ ), array( 'jquery', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-slider', 'wp-pointer' ), '1.0', true );

		$strings = array(
			'reorder' =>           __( 'Be sure to save the order of your sections once your changes are complete.', 'linchpin-mesh' ),
			'description' =>       __( 'Multiple content sections allows you to easily segment your page\'s contents into different blocks of markup.', 'linchpin-mesh' ),
			'add_image' =>         __( 'Set Background Image', 'linchpin-mesh' ),
			'remove_image' =>      __( 'Remove Background', 'linchpin-mesh' ),
			'expand_all' =>        __( 'Expand All', 'linchpin-mesh' ),
			'collapse_all' =>      __( 'Collapse All', 'linchpin-mesh' ),
			'default_title' =>     __( 'No Section Title', 'linchpin-mesh' ),
			'select_section_bg' => __( 'Select Section Background', 'linchpin-mesh' ),
			'select_bg' =>         __( 'Select Background' , 'linchpin-mesh' ),
			'select_block_bg' =>   __( 'Select Column Background', 'linchpin-mesh' ),
			'published' =>         __( 'Published', 'linchpin-mesh' ),
			'draft' =>             __( 'Draft', 'linchpin-mesh' ),
			'confirm_remove' =>    __( 'Are you sure you want to remove this section?', 'linchpin-mesh' ),
		);

		$strings = apply_filters( 'mesh_strings', $strings ); // Allow filtering of localization strings.

		$localized_data = array(
			'post_id' => $post->ID,
			'site_uri' => site_url(),
			'choose_layout_nonce'   => wp_create_nonce( 'mesh_choose_layout_nonce' ),
			'remove_section_nonce'  => wp_create_nonce( 'mesh_remove_section_nonce' ),
			'add_section_nonce'     => wp_create_nonce( 'mesh_add_section_nonce' ),
			'save_section_nonce'    => wp_create_nonce( 'mesh_save_section_nonce' ),
			'reorder_section_nonce' => wp_create_nonce( 'mesh_reorder_section_nonce' ),
			'featured_image_nonce'  => wp_create_nonce( 'mesh_featured_image_nonce' ),
			'reorder_blocks_nonce'  => wp_create_nonce( 'mesh_reorder_blocks_nonce' ),
			'dismiss_nonce'         => wp_create_nonce( 'mesh_dismiss_notification_nonce' ),
			'content_css'           => apply_filters( 'mesh_content_css', get_stylesheet_directory_uri() . '/css/admin-editor.css' , 'editor_path' ),
			'strings'               => $strings,
		);

		$localized_data = apply_filters( 'mesh_data', $localized_data ); // Allow filtering of the entire localized dataset.

		wp_localize_script( 'admin-mesh', 'mesh_data', $localized_data );
	}

	/**
	 * Enqueue admin styles
	 *
	 * @access public
	 * @return void
	 */
	function admin_enqueue_styles() {
		wp_enqueue_style( 'admin-mesh', plugins_url( 'assets/css/admin-mesh.css', __FILE__ ), array(), LINCHPIN_MESH_VERSION );
	}

	/**
	 * Enqueue frontend scripts
	 *
	 * @access public
	 * @return void
	 */
	function wp_enqueue_scripts() {
		wp_enqueue_script( 'mesh-frontend', plugins_url( 'assets/js/mesh.js', __FILE__ ), array( 'jquery' ), LINCHPIN_MESH_VERSION, true );
	}

	/**
	 * Enqueue frontend styles
	 *
	 * @access public
	 * @return void
	 */
	function wp_enqueue_styles() {
		wp_enqueue_style( 'mesh-grid-foundation', plugins_url( 'assets/css/mesh-grid-foundation.css', __FILE__ ), array(), LINCHPIN_MESH_VERSION );
	}

	/**
	 * Scan directory for files.
	 *
	 * @param string $path
	 * @param null   $extensions
	 * @param int    $depth
	 * @param string $relative_path
	 *
	 * @return array|bool
	 */
	public static function scandir( $path, $extensions = null, $depth = 0, $relative_path = '' ) {
		if ( ! is_dir( $path ) ) {
			return false;
		}

		if ( $extensions ) {
			$extensions = (array) $extensions;
			$_extensions = implode( '|', $extensions );
		}

		$relative_path = trailingslashit( $relative_path );

		if ( '/' === $relative_path ) {
			$relative_path = '';
		}

		$results = scandir( $path );
		$files = array();

		foreach ( $results as $result ) {
			if ( '.' === $result[0] ) {
				continue;
			}
			if ( is_dir( $path . '/' . $result ) ) {
				if ( ! $depth || 'CVS' === $result ) {
					continue;
				}
				$found = self::scandir( $path . '/' . $result, $extensions, $depth - 1 , $relative_path . $result );
				$files = array_merge_recursive( $files, $found );
			} elseif ( ! $extensions || preg_match( '~\.(' . $_extensions . ')$~', $result ) ) {
				$files[ $relative_path . $result ] = $path . '/' . $result;
			}
		}

		return $files;
	}
}