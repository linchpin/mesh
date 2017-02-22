<?php
/**
 * Container Template for editors
 *
 * @since 0.2.0
 *
 * @package    Mesh
 * @subpackage AdminTemplates
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

if ( ! $closed_metaboxes = get_user_option( 'closedpostboxes_page' ) ) {
	$closed_metaboxes = array();
}

$mesh_notifications = get_user_option( 'linchpin_mesh_notifications' );

$blocks = mesh_maybe_create_section_blocks( $section, $block_count );

?>
<div class="mesh-section mesh-postbox postbox<?php if ( in_array( 'mesh-section-' . esc_attr( $section->ID ), $closed_metaboxes ) ) : ?> closed<?php endif; ?>" data-mesh-section-id="<?php esc_attr_e( $section->ID ); ?>" id="mesh-section-<?php esc_attr_e( $section->ID ); ?>">
	<div class="mesh-row mesh-title-row mesh-row-padding">
		<div class="mesh-columns-8">
			<div class="mesh-clean-edit">
				<input type="text" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][post_title]" class="mesh-clean-edit-element widefat mesh-section-title left" value="<?php esc_attr_e( $section->post_title ); ?>" />
				<span class="close-title-edit left"><?php esc_html_e( 'Done', 'mesh' ); ?></span>
				<span class="handle-title mesh-section-title-text"><?php esc_html_e( $section->post_title ); ?></span>
			</div>
		</div>
		<div class="mesh-columns-4 text-right">
			<div id="section-status-select-<?php esc_attr_e( $section->ID ); ?>-container">
				<div class="mesh-clean-edit handle-right">
					<label for="section-status-select-<?php esc_attr_e( $section->ID ); ?>" class="screen-reader-text"><?php esc_html_e( 'Status:', 'mesh' ); ?></label>
					<select class="mesh-block-propagation mesh-clean-edit-element mesh-section-status" id="section-status-select-<?php esc_attr_e( $section->ID ); ?>" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][post_status]">
						<option value="draft" <?php selected( $section->post_status, 'draft' ); ?>><?php esc_html_e( 'Draft', 'mesh' ); ?></option>
						<option value="publish" <?php selected( $section->post_status, 'publish' ); ?>><?php esc_html_e( 'Published', 'mesh' ); ?></option>
					</select>
					<span class="close-title-edit right"><?php esc_html_e( 'Done', 'mesh' ); ?></span>
					<span class="handle-title mesh-section-status-text"><?php esc_html_e( 'publish' === $section->post_status ? 'Status: Published' : 'Status: Draft' ); ?></span>
				</div>
			</div>
		</div>
	</div>
	<span class="handlediv text-center"></span>
	<div class="inside">
		<?php include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-controls.php'; ?>
		<div class="mesh-editor-blocks" id="mesh-sections-editor-<?php esc_attr_e( $section->ID ); ?>">
		<?php
		if ( $blocks ) {
			include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-blocks.php';
			include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-template-warnings.php';
		}
		?>
		</div>
		<div class="mesh-row mesh-section-footer">
			<div class="mesh-section-remove-container mesh-columns-4">
				<a href="#" class="mesh-section-remove dashicons-before dashicons-no plain-link grey-link"><?php esc_html_e( 'Remove', 'mesh' ); ?></a>
				<span class="spinner"></span>
			</div>
			<div class="mesh-columns-8 text-right">
				<div class="mesh-update-status-container">
					<span class="spinner" style="float:none;"></span>
					<span class="saved-status-icon dashicons-before dashicons-yes"></span>
				</div>
				<?php if ( 'draft' === get_post_status( $section->ID ) ) : ?>
					<a href="#" class="button mesh-section-save-draft"><?php esc_html_e( 'Save Draft', 'mesh' ); ?></a>
					<a href="#" class="button primary mesh-section-publish"><?php esc_html_e( 'Publish', 'mesh' ); ?></a>
					<a href="#" class="button primary mesh-section-update hidden"><?php esc_html_e( 'Update', 'mesh' ); ?></a>
				<?php else : ?>
					<a href="#" class="button mesh-section-save-draft hidden"><?php esc_html_e( 'Save Draft', 'mesh' ); ?></a>
					<a href="#" class="button primary mesh-section-publish hidden"><?php esc_html_e( 'Publish', 'mesh' ); ?></a>
					<a href="#" class="button primary mesh-section-update "><?php esc_html_e( 'Update', 'mesh' ); ?></a>
				<?php endif; ?>
			</div>
			<input type="hidden" class="section-menu-order" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][menu_order]" value="<?php esc_attr_e( $section->menu_order ); ?>" />
		</div>
	</div>
</div>