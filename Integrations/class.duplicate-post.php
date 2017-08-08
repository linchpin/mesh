<?php
/**
 * Integrate with Duplicate Post plugin in order to copy
 * Mesh content when a post is created.
 *
 * Plugin https://duplicate-post.lopo.it/docs/developers-guide/actions/dp_duplicate_post/ url.
 *
 * This integration does not have any toggles or settings.
 *
 * @package    Mesh
 * @subpackage Integrations
 * @since 1.2
 */

namespace Mesh\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Duplicate_Posts
 *
 * @package Mesh\Integrations
 */
class Duplicate_Post {

	/**
	 * Duplicate_Posts constructor.
	 *
	 * Attach event to both page and post duplication.
	 */
	public function __construct() {
		add_action( 'dp_duplicate_page', array( $this, 'duplicate_mesh_sections' ), 99, 3 );
		add_action( 'dp_duplicate_post', array( $this, 'duplicate_mesh_sections' ), 99, 3 );

		add_action( 'duplicate_post_pre_copy', array( $this, 'skip_mesh_sections' ) );
		add_action( 'duplicate_post_post_copy', array( $this, 'skip_mesh_sections' ) );
	}

	/**
	 * Determine if we should skip the sections or not
	 *
	 * @return bool
	 */
	public function skip_mesh_sections() {
		global $post;

		return false;
	}

	/**
	 * Duplicate the sections of the original Post.
	 *
	 * @param int      $new_post_id New Post ID After duplication.
	 * @param /WP_Post $post        Original Post.
	 * @param string   $status      Post Status of the new post.
	 */
	public function duplicate_mesh_sections( $new_post_id, $post, $status ) {

		if ( ! current_user_can( 'edit_post', $new_post_id ) ) {
			return;
		}

		$mesh_template_duplicate = new Duplicate_Sections();
		$mesh_template_duplicate->duplicate_sections( $new_post_id, $post->ID, false );
	}
}

$mesh_duplicate_post = new Duplicate_Post();
