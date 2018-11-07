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

$closed_metaboxes = get_user_option( 'closedpostboxes_page' );

if ( empty( $closed_metaboxes ) ) {
	$closed_metaboxes = array();
}

$mesh_notifications = get_user_option( 'linchpin_mesh_notifications' );
$closed             = '';

if ( in_array( 'mesh-section-' . esc_attr( $section->ID ), $closed_metaboxes, true ) ) {
	$closed = ' closed';
}
?>
<div class="mesh-section mesh-postbox postbox <?php echo esc_attr( $closed ); ?>" data-mesh-section-id="<?php echo esc_attr( $section->ID ); ?>" id="mesh-section-<?php echo esc_attr( $section->ID ); ?>">
	<div class="mesh-row mesh-title-row mesh-row-padding">
		<div class="mesh-columns-8">
			<div class="mesh-clean-edit">
				<input type="text" name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][post_title]" class="mesh-clean-edit-element widefat mesh-section-title left" value="<?php echo esc_attr( Mesh_Common::get_section_title( $section ) ); ?>" />
				<span class="close-title-edit left"><?php esc_html_e( 'Done', 'mesh' ); ?></span>
				<span class="handle-title mesh-section-title-text"><?php echo esc_html( Mesh_Common::get_section_title( $section ) ); ?></span>
			</div>
		</div>
		<div class="mesh-columns-4 text-right">
			<div id="section-status-select-<?php echo esc_attr( $section->ID ); ?>-container">
				<div class="mesh-clean-edit handle-right">
					<label for="section-status-select-<?php echo esc_attr( $section->ID ); ?>" class="screen-reader-text"><?php esc_html_e( 'Status:', 'mesh' ); ?></label>
					<select class="mesh-block-propagation mesh-clean-edit-element mesh-section-status" id="section-status-select-<?php echo esc_attr( $section->ID ); ?>" name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][post_status]">
						<option value="draft" <?php selected( $section->post_status, 'draft' ); ?>><?php esc_html_e( 'Draft', 'mesh' ); ?></option>
						<option value="publish" <?php selected( $section->post_status, 'publish' ); ?>><?php esc_html_e( 'Published', 'mesh' ); ?></option>
					</select>
					<span class="close-title-edit right"><?php esc_html_e( 'Done', 'mesh' ); ?></span>
					<span class="handle-title mesh-section-status-text"><?php echo esc_html( 'publish' === $section->post_status ? esc_html__( 'Status: Published', 'mesh' ) : esc_html__( 'Status: Draft', 'mesh' ) ); ?></span>
				</div>
			</div>
		</div>
	</div>
	<span class="handlediv text-center"></span>
	<div class="inside">
		<?php $blocks = mesh_maybe_create_section_blocks( $section, $block_count ); ?>
		<?php require LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-inside.php'; ?>
	</div>
</div>
