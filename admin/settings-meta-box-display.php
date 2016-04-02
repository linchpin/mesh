<?php
/**
 * Provide a meta box view for the settings page
 *
 * @package    Mesh
 * @subpackage Mesh/admin
 */

/**
 * Meta Box
 *
 * Renders a single meta box.
 *
 * @since       1.0.0
 */
?>
<form action="options.php" method="POST">
	<h2><?php esc_html_e( LINCHPIN_MCS_PLUGIN_NAME, 'linchpin-mcs' ); ?></h2>
	<?php settings_fields( 'mesh_settings' ); ?>
	<?php do_settings_sections( 'mesh_settings' ); ?>
	<?php submit_button(); ?>
</form>
<br class="clear" />
