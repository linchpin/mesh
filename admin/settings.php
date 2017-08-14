<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the plugin settings and faq pages
 *
 * @since      1.0.0
 *
 * @package    Mesh
 * @subpackage Admin
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Options Page
 *
 * Renders the settings page contents.
 *
 * @since       1.0.0
 */

/*
 * Including Parsedown Library
 *
 * Copyright (c) 2013 Emanuil Rusev, erusev.com
 */
require_once LINCHPIN_MESH___PLUGIN_DIR . '/lib/parsedown/Parsedown.php';

$parsedown = new Parsedown();
?>
<div class="mesh-wrap" id="mesh-settings">
	<div class="table">
		<img class="mesh-logo table-cell" src="<?php echo esc_url( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/mesh-tagline-logo.png' ); ?>" alt="<?php esc_attr_e( 'Mesh', 'mesh' ); ?>" />
		<h3 class="com-button table-cell">
		<?php
			// translators: %1$s: Mesh Website URL %2$s: Visit CTA. WPCS: xss ok.
			printf( wp_kses_post( __( '<a href="%1$s" class="button" target="_blank">%2$s</a>', 'mesh' ) ), esc_url( 'https://meshplugin.com' ), esc_html__( 'Visit MeshPlugin.com', 'mesh' ) ); // WPCS: xss ok.
		?>
		</h3>
		<div class="clearfix"></div>
	</div>
	<?php settings_errors( self::$plugin_name . '-notices' ); ?>
	<h2 class="nav-tab-wrapper negative-bg">
		<?php
		foreach ( $tabs as $tab_slug => $tab_name ) :

			$tab_url = add_query_arg( array(
				'settings-updated' => false,
				'tab'              => $tab_slug,
			) );

			$active = ( $active_tab === $tab_slug ) ? ' nav-tab-active' : '';

			?>
			<a href="<?php echo esc_url( $tab_url ); ?>" title="<?php echo esc_attr( $tab_name ); ?>" class="nav-tab <?php echo esc_attr( $active ); ?>">
				<?php echo esc_html( $tab_name ); ?>
			</a>
		<?php endforeach; ?>
	</h2>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="postbox-container" class="postbox-container">
				<?php if ( 'settings' === $active_tab ) : ?>
					<div id="settings">
						<?php include_once( LINCHPIN_MESH___PLUGIN_DIR . '/admin/settings-meta-box-display.php' ); ?>
					</div>
				<?php elseif ( 'changelog' === $active_tab ) : ?>
					<div class="changelog">
					<?php
					$changelog_path = LINCHPIN_MESH___PLUGIN_DIR . '/CHANGELOG.md';

					if ( file_exists( $changelog_path ) ) {
						$changelog = file_get_contents( $changelog_path, true );

						// WPCS: sanitization ok.
						echo wp_kses_post( $parsedown->text( $changelog ) );
					}
					?>
					</div>
				<?php elseif ( 'about' === $active_tab ) : ?>
					<?php include_once( LINCHPIN_MESH___PLUGIN_DIR . '/admin/settings-about-mesh.php' ); ?>
				<?php elseif ( 'new' === $active_tab ) : ?>
					<?php include_once( LINCHPIN_MESH___PLUGIN_DIR . '/admin/settings-whats-new.php' ); ?>
				<?php else : ?>
					<?php do_action( 'mesh_setting_' . $active_tab ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
