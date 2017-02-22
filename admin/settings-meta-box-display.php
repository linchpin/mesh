<?php
/**
 * Provide a meta box view for the settings page
 * Renders a single meta box.
 *
 * @package    Mesh
 * @subpackage MeshAdmin
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}
?>
<form action="options.php" method="POST">
	<h2><?php esc_html_e( LINCHPIN_MESH_PLUGIN_NAME, 'mesh' ); ?></h2>
	<?php settings_fields( 'mesh' ); ?>
	<?php do_settings_sections( 'mesh' ); ?>
	<?php submit_button(); ?>
</form>
<br class="clear" />
