<?php
/**
 * Upgrade Notification Template
 * Display useful upgrade information within an admin notification
 *
 * @package Mesh
 * @since 1.2.5
 */

$mesh_version = get_option( 'mesh_version' );

?>
<div class="mesh-notice mesh-update-notice notice notice-info is-dismissible" data-type="update-notice">
	<div class="table">
		<div class="table-cell">
			<img src="<?php echo esc_attr( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/mesh-full-logo-full-color@2x.png' ); ?>" >
		</div>
		<div class="table-cell">
			<p class="no-margin">
				<?php
				// translators: %1$s: Version Number %2$s: Link to what's new tab.
				printf( wp_kses_post( __( 'Thanks for updating Mesh to v. (%1$s). <strong>Minor release</strong>.  <a href="%2$s">what\'s new</a>', 'mesh' ) ),
					esc_html( $mesh_version ),
					esc_url( admin_url( 'options-general.php?page=mesh&tab=new' ) )
				);
				?>
			</p>
			<p class="no-margin">
				<?php echo wp_kses_post( __( 'Fixes integrations and over zealous escaping', 'mesh' ) ); ?>
			</p>
		</div>
	</div>
</div>
