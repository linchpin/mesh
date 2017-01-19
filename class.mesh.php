<?php
/**
 * Meat of most of Mesh Functionality
 *
 * @since   1.0
 * @package Mesh
 */

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
				'label'  => __( '1 Columns', 'mesh' ),
				'blocks' => 1,
				'widths' => array( 12 ),
			),
			'mesh-columns-2.php' => array(
				'label'  => __( '2 Columns', 'mesh' ),
				'blocks' => 2,
				'widths' => array( 6, 6 ),
			),
			'mesh-columns-3.php' => array(
				'label'  => __( '3 Columns', 'mesh' ),
				'blocks' => 3,
				'widths' => array( 4, 4, 4 ),
			),
		);

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Edit form is static so it can be called elsewhere.
		add_action( 'edit_form_after_editor', array( 'Mesh', 'edit_page_form' ) );

		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		add_action( 'wp_trash_post', array( $this, 'wp_trash_post' ) );
		add_action( 'before_delete_post', array( $this, 'before_delete_post' ) );
		add_action( 'untrash_post', array( $this, 'untrash_post' ) );

		add_action( 'loop_end', array( $this, 'loop_end' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_styles' ) );

		add_filter( 'content_edit_pre', array( $this, 'the_content' ) );
		add_filter( 'the_content', array( $this, 'the_content' ), 5 );
		add_filter( 'post_class', array( $this, 'post_class' ) );

		add_filter( 'edit_form_after_title', array( $this, 'output_debug_post_info' ) );

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			include_once( 'class.mesh-ajax.php' );
			include_once( 'class.mesh-templates-ajax.php' );
		}

		// Adjust TinyMCE and Media buttons.
		add_filter( 'tiny_mce_before_init', array( $this, 'tiny_mce_before_init' ) );

		// Add Screen Options.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_filter( 'get_edit_post_link', array( $this, 'get_edit_post_link' ), 10, 3 );

		// Exclude certain taxonomies for WordPress SEO / Yoast.
		add_filter( 'wpseo_sitemap_exclude_taxonomy', array( $this, 'wpseo_sitemap_exclude_taxonomy' ) );

		// @since 1.1.3
		add_action( 'after_setup_theme', array( $this, 'after_theme_setup' ) );
	}

	/**
	 * @param string $link
	 * @param int    $post_id
	 * @param string $context
	 *
	 * @return mixed
	 */
	function get_edit_post_link( $link, $post_id, $context ) {
		global $post;

		if ( empty( $post->post_parent ) ) {
			return $link;
		}

		if ( 'mesh_section' !== get_post_type( $post->ID ) ) {
			return $link;
		}

		if ( 'mesh_section' !== get_post_type( $post->post_parent ) ) {
			return $link;
		}

		$parents = get_post_ancestors( $post->ID );

		$id = ( ! empty( $parents ) ) ? $parents[ count( $parents ) - 1 ] : $post->post_parent;

		if ( 'mesh_section' !== get_post_type( $id ) ) {
			return '#'; // get_edit_post_link( $id );
		}

		return '#'; // get_edit_post_link();
	}

	/**
	 * Output some useful information about posts.
	 */
	function output_debug_post_info() {
		global $post;

		if ( 'mesh_section' === $post->post_type && true === LINCHPIN_MESH_DEBUG_MODE ) {
		}
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
	 * @param string $status Save status.
	 * @param array  $option Option we're saving.
	 * @param mixed  $value  Value to save.
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
			'label'   => __( 'Show Extra Mesh Section Controls?', 'mesh' ),
			'default' => 0,
			'option'  => 'linchpin_mesh_section_kitchensink',
		);

		add_screen_option( 'linchpin_mesh_section_kitchensink', $args );
	}

	/**
	 * Update our tiny MCE w/ our own settings.
	 *
	 * @param array $in Input Object used by TinyMCE.
	 *
	 * @return array $in Input Object
	 */
	function tiny_mce_before_init( $in ) {

		global $post;

		// Exclude the default editor from our customizations.
		if ( '#content' === $in['selector'] ) {
			return $in;
		}

		$in['remove_linebreaks']            = false;
		$in['gecko_spellcheck']             = false;
		$in['keep_styles']                  = true;
		$in['accessibility_focus']          = true;
		$in['tabfocus_elements']            = 'major-publishing-actions';
		$in['media_strict']                 = false;
		$in['paste_remove_styles']          = false;
		$in['paste_remove_spans']           = false;
		$in['paste_strip_class_attributes'] = 'none';
		$in['paste_text_use_dialog']        = true;
		$in['wpeditimage_disable_captions'] = true;
		$in['plugins']                      = 'tabfocus,paste,media,wordpress,wpgallery,wplink';

		// Only add in our editor styles if we have the file.
		if ( file_exists( get_template_directory_uri() . '/editor-style.css' ) ) {
			$in['content_css'] = get_template_directory_uri() . '/editor-style.css';
		}

		$in['wpautop']                 = true;
		$in['apply_source_formatting'] = false;
		$in['block_formats']           = 'Paragraph=p; Heading 3=h3; Heading 4=h4';
		$in['toolbar1']                = 'bold,italic,bullist,numlist,hr,alignleft,aligncenter,alignright,alignjustify,link,wp_adv ';
		$in['toolbar2']                = 'formatselect,underline,strikethrough,forecolor,pastetext,removeformat ';
		$in['toolbar3']                = '';
		$in['toolbar4']                = '';

		return apply_filters( 'mesh_tiny_mce_before_init', $in );
	}

	/**
	 * Init function.
	 *
	 * @access public
	 * @return void
	 */
	function init() {

		load_plugin_textdomain( 'mesh', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		$labels = array(
			'name'               => _x( 'Content Section', 'Content Section', 'mesh' ),
			'singular_name'      => _x( 'Content Section', 'Content Section', 'mesh' ),
			'menu_name'          => __( 'Content Section', 'mesh' ),
			'name_admin_bar'     => __( 'Content Section', 'mesh' ),
			'parent_item_colon'  => __( 'Parent Content Section:', 'mesh' ),
			'all_items'          => __( 'All Content Sections', 'mesh' ),
			'add_new_item'       => __( 'Add New Content Section', 'mesh' ),
			'add_new'            => __( 'Add New', 'mesh' ),
			'new_item'           => __( 'New Content Section', 'mesh' ),
			'edit_item'          => __( 'Edit Content Section', 'mesh' ),
			'update_item'        => __( 'Update Content Section', 'mesh' ),
			'view_item'          => __( 'View Content Section', 'mesh' ),
			'search_items'       => __( 'Search Content Sections', 'mesh' ),
			'not_found'          => __( 'Not found', 'mesh' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'mesh' ),
		);

		register_post_type( 'mesh_section', array(
			'label'               => __( 'Content Section', 'mesh' ),
			'description'         => __( 'Content Section', 'mesh' ),
			'labels'              => $labels,
			'public'              => false,
			'hierarchical'        => true,
			'supports'            => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'page-attributes',
				'revisions',
			),
			'capability_type'     => 'post',
			'has_archive'         => false,
			'show_in_menus'       => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'menu_icon'           => 'dashicons-feedback',
			'show_ui'             => LINCHPIN_MESH_DEBUG_MODE,
			'rewrite'             => false,
		) );
	}

	/**
	 * The first time the plugin is activated, we need to add some default options.
	 */
	function admin_init() {
		if ( false === get_option( 'mesh_version' ) ) {
			update_option( 'mesh_post_types', array( 'page' => 1, 'mesh_template' => 1 ) );
			update_option( 'mesh_version', LINCHPIN_MESH_VERSION );
		}
	}

	/**
	 * edit_form_advanced function.
	 *
	 * @access public
	 *
	 * @todo   Add the ability to select which post can have Multple Content Sections
	 *
	 * @param object $post WordPress Post Object.
	 *
	 * @return void
	 */
	public static function edit_page_form( $post ) {
		$allowed_post_types = get_option( 'mesh_post_types' );

		if ( ! array_key_exists( $post->post_type, $allowed_post_types ) ) {
			return;
		}

		$content_sections   = mesh_get_sections( $post->ID, 'array', array( 'publish', 'draft' ) );
		$mesh_notifications = get_user_option( 'linchpin_mesh_notifications', get_current_user_id() );
		$mesh_templates     = mesh_get_templates();
		?>
		<div id="mesh-container" class="<?php esc_attr_e( $post->post_type ); ?>">
			<?php wp_nonce_field( 'mesh_content_sections_nonce', 'mesh_content_sections_nonce' ); ?>
			<div class="notice below-h2 mesh-row mesh-main-ua-row<?php if ( empty( $content_sections ) ) : ?> hide<?php endif; ?>">
				<div class="mesh-columns-3 columns">
					<p class="title mesh-admin-title"><?php esc_html_e( 'Mesh', 'mesh' ); ?></p>
				</div>

				<div class="mesh-columns-9 columns text-right">
					<?php include LINCHPIN_MESH___PLUGIN_DIR . 'admin/controls.php'; ?>
				</div>
			</div>

			<?php if ( empty( $content_sections ) ) : ?>
				<div id="mesh-description" class="description notice below-h2 text-center lead empty-sections-message">
					<?php include LINCHPIN_MESH___PLUGIN_DIR . 'admin/sections-empty.php'; ?>
				</div>
			<?php else : ?>
				<?php if ( empty( $mesh_notifications['intro'] ) ) : ?>
					<div id="mesh-description" class="description collapse notice is-dismissible notice-info below-h2" data-type="intro">
						<p><?php esc_html_e( 'Mesh allow you to easily break up your page into different blocks of content/markup.', 'mesh' ); ?></p>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<div id="mesh-sections-container">
				<?php foreach ( $content_sections as $key => $section ) : ?>
					<?php mesh_add_section_admin_markup( $section, true ); ?>
				<?php endforeach; ?>
			</div>

			<div class="notice below-h2 mesh-row mesh-main-ua-row bottom<?php if ( empty( $content_sections ) ) : ?> hide<?php endif; ?>">
				<div class="mesh-columns-12 columns text-right">
					<?php include LINCHPIN_MESH___PLUGIN_DIR . 'admin/controls.php'; ?>
				</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Save post.
	 *
	 * @access public
	 *
	 * @param mixed  $post_id Post ID.
	 * @param object $post    Post Object.
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

		if ( ! isset( $_POST['mesh_content_sections_nonce'] ) || ! wp_verify_nonce( $_POST['mesh_content_sections_nonce'], 'mesh_content_sections_nonce' ) ) {
			return;
		}

		if ( empty( $_POST['mesh-sections'] ) ) {
			return;
		}

		remove_action( 'save_post', array( $this, 'save_post' ), 10 );

		$count = 0;

		// Check if we are doing a section update via AJAX.
		$saving_section_via_ajax = false;
		$ajax_section_id         = 0;

		if ( ! empty( $_POST['action'] ) && 'mesh_save_section' == $_POST['action'] ) {
			$saving_section_via_ajax = true;
			$ajax_section_id         = (int) $_POST['mesh_section_id'];
		}

		foreach ( $_POST['mesh-sections'] as $section_id => $section_data ) {

			// If using AJAX, make sure we only update the section we want to save.
			if ( $saving_section_via_ajax && $ajax_section_id !== $section_id ) {
				continue;
			}

			$section = get_post( (int) $section_id );

			if ( 'mesh_section' !== $section->post_type ) {
				continue;
			}

			$status = sanitize_post_field( 'post_status', $section_data['post_status'], $post_id, 'attribute' );

			if ( ! in_array( $status, array( 'publish', 'draft' ) ) ) {
				$status = 'draft';
			}

			$updates = array(
				'ID'           => (int) $section_id,
				'post_title'   => sanitize_text_field( $section_data['post_title'] ),
				'post_content' => '', // Sections don't have content.
				'post_status'  => $status,
				'menu_order'   => ( empty( $section_data['menu_order'] ) ) ? $count : $section_data['menu_order'],
			);

			wp_update_post( $updates );

			$count ++;

			// Save Template.
			$template = sanitize_text_field( $section_data['template'] );

			if ( empty( $template ) ) {
				delete_post_meta( $section->ID, '_mesh_template' );
			} else {
				update_post_meta( $section->ID, '_mesh_template', $template );
			}

			// Save CSS Classes.
			$css_classes           = explode( ' ', $section_data['css_class'] );
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

			// Save Title Display.
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
					'menu_order'   => (int) $block_data['menu_order'],
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
				$css_classes           = explode( ' ', $block_css_class );
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

		// If this is an AJAX request, and we are saving a section,
		// we need to make sure we process the sections based on the parent.
		if ( 'mesh_section' === $post->post_type ) {
			$page_id = $post->post_parent;
		} else {
			$page_id = $post_id;
		}

		// Save a block's content into its section, and then into it's page.
		$section_posts = mesh_get_sections( $page_id );

		if ( ! empty( $section_posts ) ) {
			foreach ( $section_posts as $p ) {
				$section_content = array();

				$blocks = mesh_get_section_blocks( $p->ID );

				foreach ( $blocks as $block ) {
					if ( ! empty( $block->post_title ) && __( 'No Column Title', 'mesh' ) !== $block->post_title ) {
						$section_content[] = strip_tags( $block->post_title );
					}

					if ( ! empty( $block->post_content ) ) {
						$section_content[] = $block->post_content;
					}
				}

				// Update section to include its block's content.
				wp_update_post( array(
					'ID'           => $p->ID,
					'post_content' => implode( ' ', $section_content ),
				) );
			}

			// Get the sections again.
			$section_posts           = mesh_get_sections( $page_id );
			$page_content_sections   = array();
			$page_content_sections[] = '<div id="mesh-section-content">';

			foreach ( $section_posts as $p ) {
				if ( 'publish' !== $p->post_status ) {
					continue;
				}

				if ( ! empty( $p->post_title ) && __( 'No Section Title', 'mesh' ) !== $p->post_title ) {
					$page_content_sections[] = strip_tags( $p->post_title );
				}

				if ( ! empty( $p->post_content ) ) {
					$page_content_sections[] = $p->post_content;
				}
			}

			$page_content_sections[] = '</div>';

			$current_page = get_post( $page_id );
			$content      = $current_page->post_content;
			$pos          = strpos( $content, '<div id="mesh-section-content">' );

			if ( false !== $pos ) {
				$content = substr( $content, 0, ( strlen( $content ) - $pos ) * - 1 );
			}

			wp_update_post( array(
				'ID'           => $page_id,
				'post_content' => $content . implode( ' ', $page_content_sections ),
			) );
		}

		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
	}

	/**
	 * When supported post type is trashed, trash its sections and blocks.
	 *
	 * @param int $post_id ID of the Post to trash.
	 */
	function wp_trash_post( $post_id ) {
		$post_to_trash = get_post( $post_id );

		$supported_post_types = get_option( 'mesh_post_types', array() );

		if ( empty( $supported_post_types[ $post_to_trash->post_type ] ) ) {
			return;
		}

		$sections = mesh_get_sections( $post_id, 'array', array( 'any' ) );

		if ( empty( $sections ) ) {
			return;
		}

		foreach ( $sections as $section ) {
			if ( $blocks = mesh_get_sections( $section->ID, 'array', array( 'any' ) ) ) {
				foreach ( $blocks as $block ) {
					wp_trash_post( $block->ID );
				}
			}
			wp_trash_post( $section->ID );
		}
	}

	/**
	 * When a supported post type is deleted, delete its sections and blocks.
	 *
	 * @param int $post_id ID of the post to be deleted.
	 */
	function before_delete_post( $post_id ) {
		$post_to_delete = get_post( $post_id );

		$supported_post_types = get_option( 'mesh_post_types', array() );

		if ( empty( $supported_post_types[ $post_to_delete->post_type ] ) ) {
			return;
		}

		$sections = mesh_get_sections( $post_id, 'array', array( 'publish', 'draft', 'trash' ) );

		if ( empty( $sections ) ) {
			return;
		}

		foreach ( $sections as $section ) {
			if ( $blocks = mesh_get_sections( $section->ID, 'array', array( 'publish', 'draft', 'trash' ) ) ) {
				foreach ( $blocks as $block ) {
					wp_delete_post( $block->ID );
				}
			}
			wp_delete_post( $section->ID );
		}
	}

	/**
	 * When a post is untrashed, untrash any sections or blocks that belong to it.
	 *
	 * @param int $post_id The trashed Post's ID.
	 */
	function untrash_post( $post_id ) {
		$trashed_post = get_post( $post_id );

		$supported_post_types = get_option( 'mesh_post_types', array() );

		if ( empty( $supported_post_types[ $trashed_post->post_type ] ) ) {
			return;
		}

		$sections = mesh_get_sections( $post_id, 'array', array( 'trash' ) );

		if ( empty( $sections ) ) {
			return;
		}

		foreach ( $sections as $section ) {
			if ( $blocks = mesh_get_sections( $section->ID, 'array', array( 'trash' ) ) ) {
				foreach ( $blocks as $block ) {
					wp_untrash_post( $block->ID );
				}
			}
			wp_untrash_post( $section->ID );
		}
	}

	/**
	 * Simple loop to get our sections
	 *
	 * @param string $content Post content.
	 *
	 * @return string Return the content that has been filtered.
	 */
	function the_content( $content ) {
		$pos = strpos( $content, '<div id="mesh-section-content">' );

		if ( false !== $pos ) {
			$content = substr( $content, 0, ( strlen( $content ) - $pos ) * - 1 );
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
	 * @since   1.0
	 *
	 * @example "add_filter( 'mesh_loop_end', '__return_empty_string' );" return nothing and add my sections manually
	 *
	 * @param object $wp_query WordPress Query Object.
	 */
	function loop_end( $wp_query ) {
		if ( ! $wp_query->is_main_query() ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$sections = mesh_display_sections( $wp_query->post->ID, false );

		echo apply_filters( 'mesh_loop_end', $sections, $wp_query->post->ID ); // WPCS: ok.
	}

	/**
	 * admin_enqueue_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_enqueue_scripts() {
		global $current_screen, $post;

		$mesh_screens = array( 'post', 'edit', 'settings_page_mesh' );

		if ( ! in_array( $current_screen->base, $mesh_screens ) ) {
			return;
		}

		if ( 'edit' === $current_screen->base && 'edit-mesh_template' !== $current_screen->id ) {
			return;
		}

		wp_enqueue_script( 'admin-mesh', plugins_url( 'assets/js/admin-mesh.js', __FILE__ ), array(
			'jquery',
			'jquery-ui-draggable',
			'jquery-ui-droppable',
			'jquery-ui-slider',
			'wp-pointer',
		), '1.0', true );

		$strings = array(
			'reorder_warn'                    => __( 'Be sure to save the order of your sections once your changes are complete.', 'mesh' ),
			'description'                     => __( 'Mesh allow you to easily break up your page into different blocks of content/markup.', 'mesh' ),
			'add_image'                       => __( 'Set Background Image', 'mesh' ),
			'remove_image'                    => __( 'Remove Background', 'mesh' ),
			'expand_all'                      => __( 'Expand All', 'mesh' ),
			'collapse_all'                    => __( 'Collapse All', 'mesh' ),
			'default_title'                   => __( 'No Section Title', 'mesh' ),
			'select_section_bg'               => __( 'Select Section Background', 'mesh' ),
			'select_bg'                       => __( 'Select Background', 'mesh' ),
			'select_block_bg'                 => __( 'Select Column Background', 'mesh' ),
			'published'                       => __( 'Status: Published', 'mesh' ),
			'draft'                           => __( 'Status: Draft', 'mesh' ),
			'confirm_remove'                  => __( 'Are you sure you want to remove this section?', 'mesh' ),
			'save_order'                      => __( 'Save Order', 'mesh' ),
			'reorder'                         => __( 'Reorder Sections', 'mesh' ),
			'confirm_template_section_update' => __( 'Apply template changes to posts/pages?', 'mesh' ),
		);

		$strings = apply_filters( 'mesh_strings', $strings ); // Allow filtering of localization strings.

		$localized_data = array(
			'post_id'               => ! is_null( $post ) ? $post->ID : 0,
			'post_type'             => ! is_null( $post ) ? $post->post_type : $current_screen->post_type,
			'site_uri'              => site_url(),
			'screen'                => $current_screen->base,
			'choose_layout_nonce'   => wp_create_nonce( 'mesh_choose_layout_nonce' ),
			'remove_section_nonce'  => wp_create_nonce( 'mesh_remove_section_nonce' ),
			'add_section_nonce'     => wp_create_nonce( 'mesh_add_section_nonce' ),
			'save_section_nonce'    => wp_create_nonce( 'mesh_save_section_nonce' ),
			'reorder_section_nonce' => wp_create_nonce( 'mesh_reorder_section_nonce' ),
			'featured_image_nonce'  => wp_create_nonce( 'mesh_featured_image_nonce' ),
			'reorder_blocks_nonce'  => wp_create_nonce( 'mesh_reorder_blocks_nonce' ),
			'dismiss_nonce'         => wp_create_nonce( 'mesh_dismiss_notification_nonce' ),
			'choose_template_nonce' => wp_create_nonce( 'mesh_choose_template_nonce' ),
			'content_css'           => apply_filters( 'mesh_content_css', get_stylesheet_directory_uri() . '/css/admin-editor.css', 'editor_path' ),
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
		$mesh_options = get_option( 'mesh_settings' );
		$css_mode     = (int) $mesh_options['css_mode'];

		if ( -1 === $css_mode ) {
			return;
		} else {
			if ( 0 === $css_mode ) {
				wp_enqueue_style( 'mesh-grid-foundation', plugins_url( 'assets/css/mesh-grid-foundation.css', __FILE__ ), array(), LINCHPIN_MESH_VERSION );
			}
		}
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

		$_extensions = '';

		if ( $extensions ) {
			$extensions  = (array) $extensions;
			$_extensions = implode( '|', $extensions );
		}

		$relative_path = trailingslashit( $relative_path );

		if ( '/' === $relative_path ) {
			$relative_path = '';
		}

		$results = scandir( $path );
		$files   = array();

		foreach ( $results as $result ) {
			if ( '.' === $result[0] ) {
				continue;
			}
			if ( is_dir( $path . '/' . $result ) ) {
				if ( ! $depth || 'CVS' === $result ) {
					continue;
				}
				$found = self::scandir( $path . '/' . $result, $extensions, $depth - 1, $relative_path . $result );
				$files = array_merge_recursive( $files, $found );
			} elseif ( ! $extensions || preg_match( '~\.(' . $_extensions . ')$~', $result ) ) {
				$files[ $relative_path . $result ] = $path . '/' . $result;
			}
		}

		return $files;
	}

	/**
	 * Return a list of valid admin markup kses passing elements.
	 *
	 * @since 1.1
	 * @return array
	 */
	public static function get_admin_template_kses() {
		return array(
			'div' => array(
				'class'     => array(),
				'id'        => array(),
				'data-type' => array(),
				'style'     => array(),
			),
			'a' => array(
				'href'  => array(),
				'title' => array(),
				'class' => array(),
			),
			'input'  => array(
				'type'  => array(),
				'name'  => array(),
				'id'    => array(),
				'class' => array(),
				'value' => array(),
			),
			'label'  => array(
				'for'   => array(),
				'class' => array(),
			),
			'select' => array(
				'name'  => array(),
				'id'    => array(),
				'class' => array(),
				'value' => array(),
			),
			'option' => array(
				'value'    => array(),
				'selected' => array(),
			),
			'span'   => array(
				'class' => array(),
				'style' => array(),
			),
			'ul'     => array(
				'class' => array(),
			),
			'li'     => array(
				'class' => array(),
			),
			'p'      => array(),
			'br'     => array(),
		);
	}

	/**
	 * Filter to exclude the taxonomy from the XML sitemap.
	 *
	 * @since 1.1.3
	 *
	 * @param boolean $exclude       Defaults to false.
	 * @param string  $taxonomy_name Name of the taxonomy to exclude.
	 *
	 * @return boolean
	 */
	public function wpseo_sitemap_exclude_taxonomy( $exclude = false, $taxonomy_name ) {

		$excluded_taxonomies = array(
			'mesh_template_usage',
			'mesh_template_types',
		);

		if ( in_array( $taxonomy_name, $excluded_taxonomies ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Add a custom background image size for Mesh
	 *
	 * @since 1.1.3
	 */
	public function after_theme_setup() {
		add_image_size( 'mesh-background', 1920, 1080 );
	}
}
