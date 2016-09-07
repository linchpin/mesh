<?php
/**
 * Displayed when you do not have any sections.
 * Shown when initialized or if all the sections have been removed,
 *
 * @since      1.1
 *
 * @package    Mesh
 * @subpackage Admin
 */

$mesh_templates = mesh_get_templates();
?>
<p><?php esc_html_e( 'You do not have any Content Sections.', 'mesh' ); ?></p>
<p><?php esc_html_e( 'Get started using Mesh by adding a Content Section now.', 'mesh' ); ?></p>
<p>
	<?php if ( ! empty( $mesh_templates ) ) : ?>
		<a href="#" class="button primary mesh-select-template dashicons-before dashicons-plus"><?php esc_html_e( 'Begin with a Template', 'mesh' ); ?></a> <?php esc_html_e( ' or ', 'mesh' ); ?>
	<?php endif; ?>
	<a href="#" class="button primary mesh-section-add dashicons-before dashicons-plus"><?php esc_html_e( 'Add Section', 'mesh' ); ?></a>
</p>