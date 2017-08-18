<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Legacy product contains all deprecated methods for this class and can be
 * removed in the future.
 */
include_once( 'class-mesh-data.php' );

/**
 * Abstract Section Class
 *
 * The Mesh section class handles individual section data.
 *
 * @version  1.2.0
 * @package  Mesh/Abstracts
 * @category Abstract Class
 * @author   Linchpin
 */
class Mesh_Section extends Mesh_Data {

	/**
	 * Post type.
	 * @var string
	 */
	protected $post_type = 'mesh_section';

	/**
	 * Stores product data.
	 *
	 * @var array
	 */
	protected $data = array(
		'name'               => '',
		'slug'               => '',
		'status'             => false,
		'description'        => '',
		'short_description'  => '',
		'parent_id'          => 0,
		'menu_order'         => 0,
	);

	/**
	 * Get the product if ID is passed, otherwise the product is new and empty.
	 * This class should NOT be instantiated, but the wc_get_product() function
	 * should be used. It is possible, but the wc_get_product() is preferred.
	 *
	 * @param int|Mesh_Section|object $product Product to init.
	 */
	public function __construct( $product = 0 ) {
		parent::__construct( $product );
		if ( is_numeric( $product ) && $product > 0 ) {
			$this->set_id( $product );
		} elseif ( $product instanceof self ) {
			$this->set_id( absint( $product->get_id() ) );
		} elseif ( ! empty( $product->ID ) ) {
			$this->set_id( absint( $product->ID ) );
		} else {
			$this->set_object_read( true );
		}

		$this->data_store = Mesh_Data_Store::load( 'section-' . $this->get_type() );
		if ( $this->get_id() > 0 ) {
			$this->data_store->read( $this );
		}
	}

	/**
	 * Prefix for action and filter hooks on data.
	 *
	 * @since  1.2.0
	 * @return string
	 */
	protected function get_hook_prefix() {
		return 'mesh_section_get_';
	}

	/**
	 * Get internal type. Should return string and *should be overridden* by child classes.
	 * @since 1.2.0
	 * @return string
	 */
	public function get_type() {
		// product_type is @deprecated but here for BW compat with child classes.
		return $this->product_type;
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	|
	| Methods for getting data from the product object.
	*/

	/**
	 * Get all class data in array format.
	 * @since 1.2.0
	 * @return array
	 */
	public function get_data() {
		return array_merge(
			array(
				'id' => $this->get_id(),
			),
			$this->data,
			array(
				'meta_data' => $this->get_meta_data(),
			)
		);
	}

	/**
	 * Get section name.
	 *
	 * @since 1.2.0
	 * @param  string $context
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get section slug.
	 *
	 * @since 1.2.0
	 * @param  string $context
	 * @return string
	 */
	public function get_slug( $context = 'view' ) {
		return $this->get_prop( 'slug', $context );
	}

	/**
	 * Get section status.
	 *
	 * @since 1.2.0
	 * @param  string $context
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get product description.
	 *
	 * @since 1.2.0
	 * @param  string $context
	 * @return string
	 */
	public function get_description( $context = 'view' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Get parent ID.
	 *
	 * @since 1.2.0
	 * @param  string $context
	 * @return int
	 */
	public function get_parent_id( $context = 'view' ) {
		return $this->get_prop( 'parent_id', $context );
	}

	/**
	 * Get menu order.
	 *
	 * @since 1.2.0
	 * @param  string $context
	 * @return int
	 */
	public function get_menu_order( $context = 'view' ) {
		return $this->get_prop( 'menu_order', $context );
	}

	/**
	 * Get category ids.
	 *
	 * @since 1.2.0
	 * @param  string $context
	 * @return array
	 */
	public function get_category_ids( $context = 'view' ) {
		return $this->get_prop( 'category_ids', $context );
	}

	/**
	 * Get tag ids.
	 *
	 * @since 1.2.0
	 * @param  string $context
	 * @return array
	 */
	public function get_tag_ids( $context = 'view' ) {
		return $this->get_prop( 'tag_ids', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	|
	| Functions for setting section data. These should not update anything in the
	| database itself and should only change what is stored in the class
	| object.
	*/

	/**
	 * Set section name.
	 *
	 * @since 2.7.0
	 * @param string $name Section name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set section slug.
	 *
	 * @since 1.2.0
	 * @param string $slug Product slug.
	 */
	public function set_slug( $slug ) {
		$this->set_prop( 'slug', $slug );
	}

	/**
	 * Set section created date.
	 *
	 * @since 1.2.0
	 * @param string $timestamp Timestamp.
	 */
	public function set_date_created( $timestamp ) {
		$this->set_prop( 'date_created', is_numeric( $timestamp ) ? $timestamp : strtotime( $timestamp ) );
	}

	/**
	 * Set section modified date.
	 *
	 * @since 1.2.0
	 * @param string $timestamp Timestamp.
	 */
	public function set_date_modified( $timestamp ) {
		$this->set_prop( 'date_modified', is_numeric( $timestamp ) ? $timestamp : strtotime( $timestamp ) );
	}

	/**
	 * Set section status.
	 *
	 * @since 1.2.0
	 * @param string $status Product status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set product description.
	 *
	 * @since 1.2.0
	 * @param string $description Section description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set section short description.
	 *
	 * @since 1.2.0
	 * @param string $short_description Section short description.
	 */
	public function set_short_description( $short_description ) {
		$this->set_prop( 'short_description', $short_description );
	}

	/**
	 * Set parent ID.
	 *
	 * @since 2.7.0
	 * @param int $parent_id Product parent ID.
	 */
	public function set_parent_id( $parent_id ) {
		$this->set_prop( 'parent_id', absint( $parent_id ) );
	}

	/**
	 * Set menu order.
	 *
	 * @since 1.2.0
	 * @param int $menu_order Menu order.
	 */
	public function set_menu_order( $menu_order ) {
		$this->set_prop( 'menu_order', intval( $menu_order ) );
	}

	/**
	 * Set the section categories.
	 *
	 * @since 1.2.0
	 * @param array $term_ids List of terms IDs.
	 */
	public function set_category_ids( $term_ids ) {
		$this->set_prop( 'category_ids', $this->sanitize_term_ids( $term_ids, 'product_cat' ) );
	}

	/**
	 * Set the section tags.
	 *
	 * @since 1.2.0
	 * @param array $term_ids List of terms IDs.
	 */
	public function set_tag_ids( $term_ids ) {
		$this->set_prop( 'tag_ids', $this->sanitize_term_ids( $term_ids, 'product_tag' ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Other Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get term ids from either a list of names, ids, or terms.
	 *
	 * @since 1.2.0
	 * @param array $terms
	 * @param string $taxonomy
	 */
	protected function sanitize_term_ids( $terms, $taxonomy ) {
		$term_ids = array();
		foreach ( $terms as $term ) {
			if ( is_object( $term ) ) {
				$term_ids[] = $term->term_id;
			} elseif ( is_integer( $term ) ) {
				$term_ids[] = absint( $term );
			} else {
				$term_object = get_term_by( 'name', $term, $taxonomy );

				if ( $term_object && ! is_wp_error( $term_object ) ) {
					$term_ids[] = $term_object->term_id;
				}
			}
		}
		return $term_ids;
	}

	/**
	 * Save data (either create or update depending on if we are working on an existing product).
	 *
	 * @since 1.2.0
	 */
	public function save() {

		if ( $this->data_store ) {
			if ( $this->get_id() ) {
				$this->data_store->update( $this );
			} else {
				$this->data_store->create( $this );
			}

			return $this->get_id();
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Conditionals
	|--------------------------------------------------------------------------
	*/

	/**
	 * Check if a section supports a given feature.
	 *
	 * Section classes should override this to declare support (or lack of support) for a feature.
	 *
	 * @param string $feature string The name of a feature to test support for.
	 * @return bool True if the section supports the feature, false otherwise.
	 * @since 1.2.0
	 */
	public function supports( $feature ) {
		return apply_filters( 'mesh_section_supports', in_array( $feature, $this->supports ) ? true : false, $feature, $this );
	}

	/**
	 * Returns whether or not the product post exists.
	 *
	 * @return bool
	 */
	public function exists() {
		return false !== $this->get_status();
	}

	/**
	 * Returns whether or not the section has any child blocks.
	 *
	 * @return bool
	 */
	public function has_child() {
		return 0 < count( $this->get_children() );
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the product's title. For products this is the product name.
	 *
	 * @return string
	 */
	public function get_title() {
		return apply_filters( 'mesh_section_title', $this->get_name(), $this );
	}

	/**
	 * Product permalink.
	 * @return string
	 */
	public function get_permalink() {
		return get_permalink( $this->get_id() );
	}

	/**
	 * Returns the children IDs if applicable. Overridden by child classes.
	 *
	 * @return array of IDs
	 */
	public function get_children() {
		return array();
	}

	/**
	 * Returns the section background image.
	 *
	 * @param string $size (default: 'shop_thumbnail')
	 * @param array $attr
	 * @param bool True to return $placeholder if no image is found, or false to return an empty string.
	 * @return string
	 */
	public function get_image( $size = 'mesh_background_image', $attr = array() ) {
		if ( has_post_thumbnail( $this->get_id() ) ) {
			$image = get_the_post_thumbnail( $this->get_id(), $size, $attr );
		} elseif ( ( $parent_id = wp_get_post_parent_id( $this->get_id() ) ) && has_post_thumbnail( $parent_id ) ) {
			$image = get_the_post_thumbnail( $parent_id, $size, $attr );
		} else {
			$image = '';
		}
		return str_replace( array( 'https://', 'http://' ), '//', $image );
	}
}