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
<div id="mesh-template-usage" style="display:none">
	<h2><?php esc_html_e( 'How would you like to use this template?', 'mesh' ); ?></h2>
	<div class="mesh-row">
		<div class="mesh-section-block mesh-columns-5 columns">
			<a href="#" data-template-type="reference" class="button primary mesh-template-type mesh-reference-template dashicons-before dashicons-plus"><?php esc_html_e( 'Reference Template', 'mesh' ); ?></a>
			<p class="description">
				<?php esc_html_e( 'I want my structure to always match this template layout. If the selected template is updated in the future, the changes will be reflected in this $s.', 'mesh' ); ?>
			</p>
		</div>

		<div class="mesh-section-block mesh-columns-2 columns">
			<strong><?php esc_html_e( ' or ', 'mesh' ); ?></strong>
		</div>

		<div class="mesh-section-block mesh-columns-5 columns">
			<a href="#" data-template-type="starter" class="button primary mesh-template-type mesh-starter-template dashicons-before dashicons-plus"><?php esc_html_e( 'Starter Template', 'mesh' ); ?></a>
			<p class="description">
				<?php esc_html_e( 'I want to use this template as a base. I\'ll customize it from there.', 'mesh' ); ?>
			</p>
		</div>
	</div>
	<input type="hidden" name="mesh_template_usage" />
</div>
