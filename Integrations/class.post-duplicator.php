<?php
/**
 * Integrate with Duplicate Post plugin in order to copy
 * Mesh content when a post is created.
 * https://duplicate-post.lopo.it/docs/developers-guide/actions/dp_duplicate_post/
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
 * Class Post_Duplicator
 *
 * @package Mesh\Integrations
 */
class Post_Duplicator {

	/**
	 * Duplicate_Posts constructor.
	 *
	 * Attach post duplication.
	 */
	public function __construct() {
		add_action( 'mtphr_post_duplicator_created', array( $this, 'duplicate_mesh_sections' ), 99, 3 );
	}

	/**
	 * Duplicate mesh sections.
	 *
	 * @param int   $original_id Original ID.
	 * @param int   $duplicate_id Duplicated ID.
	 * @param array $settings Any additional settings.
	 * @since 1.2
	 */
	public function duplicate_mesh_sections( $original_id, $duplicate_id, $settings = array() ) {

		if ( ! current_user_can( 'edit_post', $duplicate_id ) ) {
			return;
		}

		$mesh_template_duplicate = new Duplicate_Sections();
		$mesh_template_duplicate->duplicate_sections( (int) $duplicate_id, (int) $original_id, false );
	}
}

$mesh_post_duplicator = new Post_Duplicator();
