<?php
/**
 * Handle various common internal methods
 *
 * @package     Mesh
 * @subpackage  Input
 * @since       1.2.5.4
 */

/**
 * Class Mesh_Inputs
 */
class Mesh_Common {

	/**
	 * Update or delete the meta value for a section/column
	 *
	 * @param int $section_id
	 * @param string $meta_key
	 * @param string $value
	 */
	public static function update_delete_section_meta( $section_id, $meta_key, $value ) {
		if ( empty( $value ) ) {
			delete_post_meta( $section_id, '_mesh_lp_equal' );
		} else {
			update_post_meta( $section_id, '_mesh_lp_equal', $value );
		}
	}
}

