<?php
/**
 * Template to display how a mesh template will be used.
 *
 * @package    Mesh
 * @subpackage AdminTemplates
 * @since      1.1
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}
?>
<div id="mesh-template-usage hide">
	<h2><?php esc_html_e( 'How would you like to use this template?', 'mesh' ); ?></h2>
	<p>
		<a href="#" data-template-type="reference" class="button primary mesh-template-type mesh-reference-template dashicons-before dashicons-plus"><?php esc_html_e( 'Reference Template', 'mesh' ); ?></a> <?php esc_html_e( ' or ', 'mesh' ); ?>
		<a href="#" data-template-type="starter" class="button primary mesh-template-type mesh-starter-template dashicons-before dashicons-plus"><?php esc_html_e( 'Starter Template', 'mesh' ); ?></a>
	</p>
	<input type="hidden" id="mesh_template_usage" name="mesh_template_usage" />
</div>
