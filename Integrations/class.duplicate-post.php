<?php
/**
 * Integrate with Duplicate Post plugin in order to copy
 * Mesh content when a post is created.
 *
 * https://duplicate-post.lopo.it/docs/developers-guide/actions/dp_duplicate_post/
 *
 * This integration does not have any toggles or settings.
 *
 * @since 1.2
 *
 */
namespace Mesh\Integrations;

if ( ! defined('ABSPATH' ) ) {
	exit;
}

/**
 * Class Duplicate_Posts
 * @package Mesh\Integrations
 */
class Duplicate_Post {

	/**
	 * Duplicate_Posts constructor.
	 *
	 * Attach event to both page and post duplication.
	 *
	 */
	public function __construct() {
		add_action( 'dp_duplicate_page', array( $this, 'duplicate_mesh_sections' ), 99, 3 );
		add_action( 'dp_duplicate_post', array( $this, 'duplicate_mesh_sections' ), 99, 3 );

		add_action ( 'duplicate_post_pre_copy', array( $this, 'skip_mesh_sections' ) );
		add_action ( 'duplicate_post_post_copy', array( $this, 'skip_mesh_sections' ) );
	}

	/**
	 * @return bool
	 */
	public function skip_mesh_sections() {
		global $post;

		// if ( $post->post_type == 'mesh_section' ) {

		// }

		return false;
	}

	/**
	 * @param int      $new_post_id
	 * @param /WP_Post $post
	 * @param string   $status
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