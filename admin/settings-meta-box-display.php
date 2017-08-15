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
<form action="options.php" class="settings-form" method="POST">
	<div class="about hero negative-bg">
		<div class="hero-text">
			<h1><?php esc_html_e( 'Mesh Settings', 'mesh' ); ?></h1>
		</div>
	</div>
	<?php settings_fields( 'mesh' ); ?>
	<?php do_settings_sections( 'mesh' ); ?>
	<?php submit_button(); ?>
</form>
<br class="clear" />
