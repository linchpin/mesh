<?php
/**
 * Update class for Simple Subtitles
 */
class Simple_Subtitle_Upgrades {

	function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Perform any upgrades needed.
	 */
	function admin_init() {
		if ( 0 > version_compare( $GLOBALS['simple_subtitles_database_version'], '1.0' ) ) {
			$this->version_1_0();
		}

		if ( 0 > version_compare( $GLOBALS['simple_subtitles_database_version'], '2.0' ) ) {
			$this->version_2_0();
		}

		if ( 0 > version_compare( $GLOBALS['simple_subtitles_database_version'], '2.1.1' ) ) {
			$this->version_2_1_1();
		}
	}

	/**
	 * Upgrade to version 1.0 by ensuring the default post types are selected.
	 */
	function version_1_0() {
		if ( $settings = get_option( 'simple_subtitle_settings' ) ) {
			return;
		}

		$post_types = get_post_types();

		if ( empty( $post_types ) ) {
			return;
		}

		$default_post_types = array();

		foreach ( $post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );

			if ( in_array( $post_type, array( 'revision', 'nav_menu_item', 'attachment' ) ) || ! $post_type_object->public ) {
				continue;
			}

			$default_post_types[] = $post_type;
		}

		if ( ! empty( $default_post_types ) ) {
			update_option( 'simple_subtitle_settings', $default_post_types );
		}

		update_option( 'simple_subtitles_version', '1.0' );
		$GLOBALS['simple_subtitles_database_version'] = '1.0';
	}

	/**
	 * Upgrade meta keys to reflect the new namespacing.
	 */
	function version_2_0() {
		$args = array(
			'posts_per_page' => 100,
			'offset' => 0,
			'post_type' => 'any',
			'post_status' => apply_filters( 'simple_subtitle_2_0_upgrade_statuses', array(
				'any',
				'trash',
			) ),
			'meta_query' => array(
				array(
					'key' => '_lp_simple_subtitle',
					'compare' => 'EXISTS',
				),
			),
		);

		$subtitle_posts = new WP_Query( $args );

		while ( $subtitle_posts->have_posts() ) {
			foreach ( $subtitle_posts->posts as $post ) {
				$subtitle = get_post_meta( $post->ID, '_lp_simple_subtitle', true );
				error_log( $subtitle );

				update_post_meta( $post->ID, '_simple_subtitle', $subtitle );
				delete_post_meta( $post->ID, '_lp_simple_subtitle' );
			}

			$args['offset'] = $args['posts_per_page'] + $args['offset'];
			$subtitle_posts = new WP_Query( $args );
		}

		update_option( 'simple_subtitles_version', '2.0' );
		$GLOBALS['simple_subtitles_database_version'] = '2.0';
	}

	/**
	 * Nothing to update here, just the version.
	 */
	function version_2_1_1() {
		update_option( 'simple_subtitles_version', '2.1.1' );
		$GLOBALS['simple_subtitles_database_version'] = '2.1.1';
	}
}
$simple_subtitle_upgrades = new Simple_Subtitle_Upgrades();