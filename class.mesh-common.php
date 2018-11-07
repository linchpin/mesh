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

	public static $section_title_pattern = '/(no (section|column) title)( - ([0-9]*))?/mi'; // Match both old format and new format including - POST_ID also match column or sections

	/**
	 * Update or delete the meta value for a section/column
	 * @since 1.2.5.4
	 *
	 * @param int    $section_id
	 * @param string $meta_key
	 * @param string $value
	 */
	public static function update_delete_section_meta( $section_id, $meta_key, $value ) {
		if ( empty( $value ) ) {
			delete_post_meta( $section_id, $meta_key );
		} else {
			update_post_meta( $section_id, $meta_key, $value );
		}
	}

	/**
	 * Get the title for the passed section
	 * @since 1.2.5.4
	 *
	 * @param $section
	 *
	 * @return mixed
	 */
	public static function get_section_title( $section ) {

		preg_match_all( self::$section_title_pattern, $section->post_title, $section_title_matches, PREG_SET_ORDER, 0 );

		if ( ! empty( $section_title_matches[0][1] ) ) {
			return $section_title_matches[0][1];
		}

		return $section->post_title;
	}

	/**
	 * Return a valid section title
	 *
	 * @since 1.2.5.4
	 *
	 * @param        $section_title
	 * @param        $section_id
	 * @param string $type
	 *
	 * @return bool|string
	 */
	public static function validate_section_title( $section_title, $section_id, $type = 'Section' ) {

		preg_match_all( self::$section_title_pattern, $section_title, $section_title_matches, PREG_SET_ORDER, 0 );

		$title         = '';
		$default_title = sanitize_text_field( 'No ' . $type . ' Title - ' . (int) $section_id );

		if ( empty( $section_title ) || ! empty( $section_title_matches ) ) {
			if ( ! empty( $section_title_matches[0][1] ) && empty( $section_title_matches[0][3] ) ) {
				$title = $default_title;
			}
		} else {
			$title = sanitize_text_field( $section_title );
		}

		// double catch
		if ( empty( $title ) ) {
			$title = $default_title;
		}

		return $title;
	}
}

