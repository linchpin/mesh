<?php
/**
* Plugin Name: Multiple Content Sections
* Plugin URI: http://linchpin.agency
* Description: Add multiple content sections on a post by post basis.
* Version: 1.4.0
* Author: Linchpin
* Author URI: http://linchpin.agency
* License: GPLv2 or later
 *
 * @package MultipleContentSections
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

define( 'LINCHPIN_MCS_VERSION', '1.4.0' );
define( 'LINCHPIN_MCS_PLUGIN_NAME', 'Multiple Content Sections' );
define( 'LINCHPIN_MCS__MINIMUM_WP_VERSION', '4.0' );
define( 'LINCHPIN_MCS___PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LINCHPIN_MCS___PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Class Multiple_Content_Sections
 */
class Multiple_Content_Sections {

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
	 * @since 1.3.5
	 *
	 * @var array
	 */
	public static $template_data = array(
		'mcs-columns-1.php' => array(
			'label' => '1 Columns',
			'blocks' => 1,
			'widths' => array( 12 ),
		),
		'mcs-columns-2.php' => array(
			'label' => '2 Columns',
			'blocks' => 2,
			'widths' => array( 6, 6 ),
		),
		'mcs-columns-3.php' => array(
			'label' => '3 Columns',
			'blocks' => 3,
			'widths' => array( 4, 4, 4 ),
		),
	) ;

	/**
	 * __construct function.
	 *
	 * @access public
	 */
	function __construct() {

		add_action( 'init', array( $this, 'init' ) );

		add_action( 'edit_page_form', array( $this, 'edit_page_form' ) );

		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );

		add_filter( 'content_edit_pre', array( $this, 'the_content' ) );
		add_filter( 'the_content', array( $this, 'the_content' ), 5 );
		add_filter( 'post_class', array( $this, 'post_class' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			include_once( 'functions-ajax.php' );
		}

		add_action( 'media_buttons', array( $this, 'add_media_buttons' ), 999, 1 );

	}

	/**
	 * Add media buttons to our block editors
     *
	 *
	 * @param $editor_id
	 */
	function add_media_buttons( $editor_id ) {

		$editor_id = (int) str_replace( 'mcs-section-editor-', '', $editor_id );

		if ( 'mcs_section' === get_post_type( $editor_id ) ) :

			$featured_image_id = get_post_thumbnail_id( $editor_id );

			if ( empty( $featured_image_id ) ) : ?>
				<button class="button mcs-block-featured-image-choose dashicons-before dashicons-format-image"><?php esc_attr_e( 'Set Background Image', 'linchpin-mce' ); ?></button>
			<?php else : ?>
				<button class="button mcs-block-featured-image-choose dashicons-before dashicons-edit" data-mcs-section-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"><?php echo get_the_title( $featured_image_id ); ?></button>
				<button class="button mcs-block-featured-image-trash dashicons-before dashicons-trash" data-mcs-section-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"><?php esc_html_e( 'Remove', 'linchpin-mcs' ); ?></button>
			<?php endif; ?>
		<?php endif;
	}

	/**
	 * Init function.
	 *
	 * @access public
	 * @return void
	 */
	function init() {

		$labels = array(
			'name'                => _x( 'Content Section', 'Content Section', 'linchpin-mcs' ),
			'singular_name'       => _x( 'Content Section', 'Content Section', 'linchpin-mcs' ),
			'menu_name'           => __( 'Content Section', 'linchpin-mcs' ),
			'name_admin_bar'      => __( 'Content Section', 'linchpin-mcs' ),
			'parent_item_colon'   => __( 'Parent Content Section:', 'linchpin-mcs' ),
			'all_items'           => __( 'All Content Sections', 'linchpin-mcs' ),
			'add_new_item'        => __( 'Add New Content Section', 'linchpin-mcs' ),
			'add_new'             => __( 'Add New', 'linchpin-mcs' ),
			'new_item'            => __( 'New Content Section', 'linchpin-mcs' ),
			'edit_item'           => __( 'Edit Content Section', 'linchpin-mcs' ),
			'update_item'         => __( 'Update Content Section', 'linchpin-mcs' ),
			'view_item'           => __( 'View Content Section', 'linchpin-mcs' ),
			'search_items'        => __( 'Search Content Sections', 'linchpin-mcs' ),
			'not_found'           => __( 'Not found', 'linchpin-mcs' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'linchpin-mcs' ),
		);

		register_post_type( 'mcs_section', array(
			'label'               => __( 'Content Section', 'linchpin-mcs' ),
			'description'         => __( 'Content Section', 'linchpin-mcs' ),
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
	 * @param object $post WordPress Post Object.
	 *
	 * @return void
	 */
	function edit_page_form( $post ) {
		$content_sections = mcs_get_sections( $post->ID );
		?>
		<div id="mcs-container">
			<?php wp_nonce_field( 'mcs_content_sections_nonce', 'mcs_content_sections_nonce' ); ?>

			<h2 class="mcs-section-controls-container">
				<?php esc_html_e( 'Multiple Content Sections', 'linchpin-mcs' ); ?>
				<a href="#" class="page-title-action mcs-section-reorder<?php if ( empty( $content_sections ) ) : ?> disabled<?php endif; ?>"><?php esc_html_e( 'Reorder Sections', 'lincpin-mcs' ); ?></a>
				<a href="#" class="page-title-action mcs-section-expand<?php if ( empty( $content_sections ) ) : ?> disabled<?php endif; ?>"><?php esc_html_e( 'Expand All', 'lincpin-mcs' ); ?></a>
				<a href="#" class="page-title-action mcs-section-add dashicons-before dashicons-plus"><?php esc_html_e( 'Add Section', 'lincpin-mcs' ); ?></a>
				<span class="spinner mcs-reorder-spinner"></span>
			</h2>


			<?php if ( empty( $content_sections ) ) : ?>
				<div id="mcs-description" class="description notice notice-info below-h2">
					<p>
						<?php esc_html_e( 'You haven\'t added any content sections yet! It\'s easy click ', 'linchpin-mcs' ); ?>
						<a href="#" class="button mcs-section-add dashicons-before dashicons-plus"><?php esc_html_e( 'Add Section', 'lincpin-mcs' ); ?></a>
						<?php esc_html_e( ' to get started', 'linchpin-mcs' ); ?>
					</p>
				</div>
			<?php else : ?>
				<?php if ( empty( $mcs_notifications['intro'] ) ) : ?>
					<div id="mcs-description" class="description notice is-dismissible notice-info below-h2" data-type="intro">
						<p><?php esc_html_e( 'Multiple content sections allow you to easily segment your page\'s contents into different blocks of markup.', 'linchpin-mcs' ); ?></p>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<div id="multiple-content-sections-container">
				<?php foreach ( $content_sections as $key => $section ) : ?>
					<?php mcs_add_section_admin_markup( $section, true ); ?>
				<?php endforeach; ?>
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

		if ( ! isset( $_POST['mcs_content_sections_nonce'] ) || ! wp_verify_nonce( $_POST['mcs_content_sections_nonce'], 'mcs_content_sections_nonce' )  ) {
			return;
		}

		if ( empty( $_POST['mcs-sections'] ) ) {
			return;
		}

		remove_action( 'save_post', array( $this, 'save_post' ), 10 );

		foreach ( $_POST['mcs-sections'] as $section_id => $section_data ) {
			$section = get_post( (int) $section_id );

			if ( 'mcs_section' != $section->post_type ) {
				continue;
			}

			if ( $post_id != $section->post_parent ) {
				continue;
			}

			$status = sanitize_post_field( 'post_status', $section_data['post_status'], $post_id, 'attribute' );

			if ( ! in_array( $status, array( 'publish', 'draft' ) ) ) {
				$status = 'draft';
			}

			if ( empty( $section_data['post_content'] ) ) {
				$section_data['post_content'] = '';
			}

			$updates = array(
				'ID' => (int) $section_id,
				'post_title' => sanitize_text_field( $section_data['post_title'] ),
				'post_content' => wp_kses( $section_data['post_content'], array_merge(
					array(
						'iframe' => array(
							'src' => true,
							'style' => true,
							'id' => true,
							'class' => true,
						),
					),
					wp_kses_allowed_html( 'post' )
				) ),
				'post_status' => $status,
			);

			wp_update_post( $updates );

			// Save Template.
			$template = sanitize_text_field( $section_data['template'] );

			if ( empty( $template ) ) {
				delete_post_meta( $section->ID, '_mcs_template' );
			} else {
				update_post_meta( $section->ID, '_mcs_template', $template );
			}

			// Save CSS Classes.
			$css_classes = explode( ' ', $section_data['css_class'] );
			$sanitized_css_classes = array();

			foreach( $css_classes as $css ) {
				$sanitized_css_classes[] = sanitize_html_class( $css );
			}

			$sanitized_css_classes = implode( ' ', $sanitized_css_classes );

			if ( empty( $sanitized_css_classes ) ) {
				delete_post_meta( $section->ID, '_mcs_css_class' );
			} else {
				update_post_meta( $section->ID, '_mcs_css_class', $sanitized_css_classes );
			}

			// Save Column Offset

			$offset = (int) $section_data['offset'];

			if ( empty( $offset ) ) {
				delete_post_meta( $section->ID, '_mcs_offset' );
			} else {
				update_post_meta( $section->ID, '_mcs_offset', $offset );
			}

			// Process the section's blocks.
			$blocks = array();

			if ( ! empty( $section_data['blocks'] ) ) {
				$blocks = $section_data['blocks'];
			}

			foreach ( $blocks as $block_id => $block_data ) {
				$block = get_post( (int) $block_id );

				if ( empty( $block ) || 'mcs_section' != $block->post_type || $section->ID != $block->post_parent ) {
					continue;
				}

				$updates = array(
					'ID' => (int) $block_id,
					'post_content' => wp_kses( $block_data['post_content'], mcs_get_allowed_html() ),
					'post_status' => $status,
				);

				wp_update_post( $updates );

				$block_column_width = (int) $section_data['blocks'][ $block_id ]['columns'];

				// If we don't have a column width defined or we are using a 1 column layout clear our saved widths.
				if ( empty( $block_column_width ) || 'mcs-columns-1.php' == $template ) {
					delete_post_meta( $block_id, '_mcs_column_width' );
				} else {
					update_post_meta( $block_id, '_mcs_column_width', $block_column_width );
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
					delete_post_meta( $block_id, '_mcs_css_class' );
				} else {
					update_post_meta( $block_id, '_mcs_css_class', $sanitized_css_classes );
				}
			}
		}

		// Save a block's content into its section, and then into it's page.
		$section_posts = mcs_get_sections( $post_id );

		if ( ! empty( $section_posts ) ) {
			foreach ( $section_posts as $p ) {
				$section_content = array();

				$blocks = mcs_get_section_blocks( $p->ID );

				foreach ( $blocks as $b ) {
					$section_content[] = strip_tags( $b->post_content );
				}

				wp_update_post( array(
					'ID' => $p->ID,
					'post_content' => implode( ' ', $section_content ),
				) );
			}

			// Get the sections again.
			$section_posts = mcs_get_sections( $post_id );
			$page_content_sections = array();
			$page_content_sections[] = '<div id="mcs-section-content">';

			foreach ( $section_posts as $p ) {
				if ( 'publish' !== $p->post_status ) {
					continue;
				}

				$page_content_sections[] = strip_tags( $p->post_title );
				$page_content_sections[] = strip_tags( $p->post_content );
			}

			$page_content_sections[] = '</div>';

			wp_update_post( array(
				'ID' => $post_id,
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
		$pos = strpos( $content, '<div id="mcs-section-content">' );

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
		$classes[] = 'mcs-section';

		if ( $custom_class = get_post_meta( get_the_ID(), '_mcs_css_class', true ) ) {
			$classes[] = esc_attr( $custom_class );
		}

		return $classes;
	}

	/**
	 * admin_enqueue_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_enqueue_scripts() {
		global $current_screen, $post;

		if ( 'post' != $current_screen->base ) {
			return;
		}

		wp_enqueue_script( 'admin-mcs', plugins_url( 'assets/js/admin-mcs.js', __FILE__ ), array( 'jquery', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-slider' ), '1.0', true );

		// get_stylesheet_directory_uri() . '/css/editor-style.css'

		$localized_data = array(
			'post_id' => $post->ID,
			'site_uri' => site_url(),
			'choose_layout_nonce'   => wp_create_nonce( 'mcs_choose_layout_nonce' ),
			'remove_section_nonce'  => wp_create_nonce( 'mcs_remove_section_nonce' ),
			'add_section_nonce'     => wp_create_nonce( 'mcs_add_section_nonce' ),
			'reorder_section_nonce' => wp_create_nonce( 'mcs_reorder_section_nonce' ),
			'featured_image_nonce'  => wp_create_nonce( 'mcs_featured_image_nonce' ),
			'reorder_blocks_nonce'  => wp_create_nonce( 'mcs_reorder_blocks_nonce' ),
			'dismiss_nonce'         => wp_create_nonce( 'mcs_dismiss_notification_nonce' ),
			'content_css'           => apply_filters( 'content_css', get_stylesheet_directory_uri() . '/css/editor-style.css' , 'editor_path' ),
			'labels' => array(
				'reorder' => __( 'Be sure to save order of your sections once your changes are complete.', 'linchpin-mcs' ),
				'description' => __( 'Multiple content sections allows you to easily segment your page\'s contents into different blocks of markup.', 'linchpin-mcs' ),
				'add_image' => __( 'Set Background Image', 'linchpin-mcs' ),
				'remove_image' => __( 'Remove Background', 'linchpin-mcs' ),
			),
		);



		wp_localize_script( 'admin-mcs', 'mcs_data', $localized_data );
	}

	/**
	 * admin_enqueue_styles function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_enqueue_styles() {
		wp_enqueue_style( 'admin-mcs', plugins_url( 'assets/css/admin-mcs.css', __FILE__ ), array(), '1.0' );
	}

	/**
	 * @param $path
	 * @param null $extensions
	 * @param int $depth
	 * @param string $relative_path
	 *
	 * @return array|bool
	 */
	public static function scandir( $path, $extensions = null, $depth = 0, $relative_path = '' ) {
	    if ( ! is_dir( $path ) )
	        {return false;}

	    if ( $extensions ) {
	        $extensions = (array) $extensions;
	        $_extensions = implode( '|', $extensions );
	    }

	    $relative_path = trailingslashit( $relative_path );
	    if ( '/' == $relative_path )
	        {$relative_path = '';}

	    $results = scandir( $path );
	    $files = array();

	    foreach ( $results as $result ) {
	        if ( '.' == $result[0] )
	            {continue;}
	        if ( is_dir( $path . '/' . $result ) ) {
	            if ( ! $depth || 'CVS' == $result )
	                {continue;}
	            $found = self::scandir( $path . '/' . $result, $extensions, $depth - 1 , $relative_path . $result );
	            $files = array_merge_recursive( $files, $found );
	        } elseif ( ! $extensions || preg_match( '~\.(' . $_extensions . ')$~', $result ) ) {
	            $files[ $relative_path . $result ] = $path . '/' . $result;
	        }
	    }

    return $files;
}

}

$multiple_content_sections = new Multiple_Content_Sections();

/**
 * @param null $type
 * @param int $depth
 * @param bool|false $search_parent
 *
 * @return array
 */
function mcs_get_files( $type = null, $depth = 0, $search_parent = false, $directory = '' ) {
    $files = (array) Multiple_Content_Sections::scandir( $directory, $type, $depth );

    if ( $search_parent && $this->parent() )
        {$files += (array) Multiple_Content_Sections::scandir( $directory, $type, $depth );}

    return $files;
}

/**
 * Load a list of template files.
 *
 * @access public
 *
 * @param string $section_templates (default: '') Our list of available templates.
 *
 * @return mixed
 */
function mcs_locate_template_files( $section_templates = '' ) {
	$current_theme = wp_get_theme();

	$section_templates = array();

	$plugin_template_files = (array) mcs_get_files( 'php', 1, false, LINCHPIN_MCS___PLUGIN_DIR . 'templates/' );

	// Loop through our local plugin templates

	foreach( $plugin_template_files as $plugin_file => $plugin_file_full_path ) {
		if ( ! preg_match( '|MCS Template:(.*)$|mi', file_get_contents( $plugin_file_full_path ), $header ) ) {
			continue;
		}

		$section_templates[ $plugin_file ]['file'] = _cleanup_header_comment( $header[1] );

		if ( preg_match( '/MCS Template Blocks: ?([0-9]{1,2})$/mi', file_get_contents( $plugin_file_full_path ), $block_header ) ) {
			$section_templates[ $plugin_file ]['blocks'] = $block_header[1];
		}
	}

	$files = (array) $current_theme->get_files( 'php', 1 );

	// Loop through our theme templates. This should be made into utility method.

	foreach ( $files as $file => $full_path ) {

		if ( ! preg_match( '|MCS Template:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
			continue;
		}

		$section_templates[ $file ]['file'] = _cleanup_header_comment( $header[1] );

		if ( preg_match( '/MCS Template Blocks: ?([0-9]{1,2})$/mi', file_get_contents( $full_path ), $block_header ) ) {
			$section_templates[ $file ]['blocks'] = (int) $block_header[0];
		}
	}

	/**
	 * Filter list of page templates for a theme.
	 *
	 * This filter does not currently allow for page templates to be added.
	 *
	 * @since 1.3.5
	 *
	 * @param array        $page_templates Array of page templates. Keys are filenames,
	 *                                     values are translated names.
	 * @param WP_Theme     $this           The theme object.
	 * @param WP_Post|null $post           The post being edited, provided for context, or null.
	 */
	$return = apply_filters( 'mcs_section_templates', $section_templates );

	return $section_templates;
}

/**
 * Return admin facing markup for a section.
 *
 * @access public
 *
*@param  object $section Current section being manipulated.
 *
*@return void Prints the markup of the admin panel
 */
function mcs_add_section_admin_markup( $section, $closed = false ) {
	if ( ! is_admin() ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $section->ID ) ) {
		return;
	}

	$templates = mcs_locate_template_files();

	// Make sure we always have a template.
	if ( ! $selected_template = get_post_meta( $section->ID, '_mcs_template', true ) ) {
		$selected_template = 'mcs-columns-1.php';
	}

	$css_class = get_post_meta( $section->ID, '_mcs_css_class', true );
	$offset = get_post_meta( $section->ID, '_mcs_offset', true );

	$featured_image_id = get_post_thumbnail_id( $section->ID );

	include LINCHPIN_MCS___PLUGIN_DIR . 'admin/section-container.php';
}

/**
 * @param $post_id
 * @param string $return_type
 *
 * @return array|WP_Query
 */
function mcs_get_sections( $post_id, $return_type = 'array' ) {
	$content_sections = new WP_Query( array(
		'post_type' => 'mcs_section',
		'posts_per_page' => 50,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_parent' => (int) $post_id,
	) );

	switch ( $return_type ) {
		case 'query' :
			return $content_sections;
			break;

		case 'array' :
		default      :
			return $content_sections->posts;
			break;
	}
}

/**
 * Load a specified template file for a section
 *
 * @access public
 *
*@param string $post_id (default: '')
 *
*@return void
 */
function the_mcs_content( $post_id = '' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	if ( 'mcs_section' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! $template = get_post_meta( $post_id, '_mcs_template', true ) ) {
		$template = 'mcs-columns-1.php';
	}

	$located = locate_template( sanitize_text_field( $template ), true, false );

	if ( $located ) {
		return;
	} else {

		$file = LINCHPIN_MCS___PLUGIN_DIR . '/templates/' . $template;

		if ( file_exists( $file ) ) {
			include $file;
		} else {
			?>
			<div <?php post_class(); ?>
				<h3 title="<?php the_title_attribute(); ?>"><?php the_title(); ?></h3>
				<div class="entry">
					<?php the_content(); ?>
				</div>
			</div>
			<?php
		}
	}
}

/**
 * mcs_display_sections function.
 *
 * @access public
 *
*@param string $post_id (default: '')
 *
*@return void
 */
function mcs_display_sections( $post_id = '' ) {
	global $post, $mcs_section_query;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	if ( ! $mcs_section_query = mcs_get_sections( $post_id, 'query' ) ) {
		return;
	}

	if ( ! empty( $mcs_section_query ) ) {
		if ( $mcs_section_query->have_posts() ) : while ( $mcs_section_query->have_posts() ) : $mcs_section_query->the_post();
			the_mcs_content();
		endwhile; endif; wp_reset_postdata();
	}
}

/**
 * Get a section's blocks.
 *
 * @access public
 *
 * @param  int    $section_id
 * @param  string $post_status
 *
 * @return array
 */
function mcs_get_section_blocks( $section_id, $post_status = 'publish' ) {
	$content_blocks = new WP_Query( array(
		'post_type' => 'mcs_section',
		'post_status' => $post_status,
		'posts_per_page' => 50,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_parent' => (int) $section_id,
	) );

	if ( $content_blocks->have_posts() ) {
		return $content_blocks->posts;
	} else {
		return array();
	}
}

/**
 * Make sure a section has a certain number of blocks
 * @todo: Should always be at least 1 section?
 *
 * @access public
 *
 * @param  mixed $section
 * @param  int   $number_needed
 *
 * @return array
 */
function mcs_maybe_create_section_blocks( $section, $number_needed = 0 ) {

	$blocks = mcs_get_section_blocks( $section->ID, $section->post_status );
	$count = count( $blocks );

	// Create enough blocks to fill the section.
	while ( $count < $number_needed ) {
		wp_insert_post( array(
			'post_type'   => 'mcs_section',
			'post_title'  => 'Block ' . $count,
			'post_parent' => $section->ID,
			'menu_order'  => $count,
			'post_name'   => 'section-' . $section->ID . '-block',
		) );

		++$count;
	}

	return mcs_get_section_blocks( $section->ID, $section->post_status );
}

function mcs_section_background( $post_id = 0, $echo = true ) {

	global $post;

	if( empty( $post_id ) ) {
		$post_id  = $post->ID;
	}

	if ( has_post_thumbnail() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
		$style = 'data-interchange="[' . esc_url( $image[0] ) . ', (default)], [' . esc_url( $image[0] ) . ', (large)]" style="background-image: url(' . esc_url( $image[0] ) . ');"';
	}

	if ( empty( $style ) ) {
		return;
	} else {
		if ( false === $echo ) {
			return style;
		} else {
			echo $style;
		}
	}
}

// Return an array of allowed html for wp_kses functions
function mcs_get_allowed_html() {
	$mcs_allowed = apply_filters( 'mcs_default_allowed_html', array(
		'iframe' => array(
			'src' => true,
			'style' => true,
			'id' => true,
			'class' => true,
			'name' => true,
			'allowfullscreen' => true,
			'msallowfullscreen' => true,
			'mozallowfullscreen' => true,
			'webkitallowfullscreen' => true,
			'oallowfullscreen' => true,
			'allowtransparency' => true,
			'frameborder' => true,
			'scrolling' => true,
			'width' => true,
			'height' => true,
		),
		'script' => array(
			'src' => true,
		),
		'div' => array(
			'data-equalizer' => true,
			'data-equalizer-watch' => true,
		)
	) );

	$post_allowed = wp_kses_allowed_html( 'post' );

	return apply_filters( 'mcs_allowed_html', array_merge_recursive( $post_allowed, $mcs_allowed ) );
}