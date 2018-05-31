<?php
/**
 * Container Inside
 *
 * @since 1.2.0
 *
 * @package    Mesh
 * @subpackage AdminTemplates
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}
?>
<?php require LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-controls.php'; ?>
<div class="mesh-editor-blocks" id="mesh-sections-editor-<?php echo esc_attr( $section->ID ); ?>">
<?php
if ( ! empty( $blocks ) ) {
	include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-blocks.php';
	include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-template-warnings.php';
} else {
	esc_html_e( 'No Blocks Available', 'mesh' );
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
	<input type="hidden" class="section-menu-order" name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][menu_order]" value="<?php echo esc_attr( $section->menu_order ); ?>" />
</div>
