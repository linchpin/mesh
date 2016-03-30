<?php
/**
 * Container Template for editors
 *
 * @since 1.2.0
 *
 * @package MultipleContentSection
 * @subpackage AdminTemplates
 */

if ( ! $closed_metaboxes = get_user_option( 'closedpostboxes_page' ) ) {
	$closed_metaboxes = array();
}

$mcs_notifications = get_user_option( 'linchpin_mcs_notifications' );

$blocks = mcs_maybe_create_section_blocks( $section );

?>
<div class="multiple-content-sections-section multiple-content-sections-postbox postbox<?php if ( in_array( 'mcs-section-' . esc_attr( $section->ID ), $closed_metaboxes ) ) : ?> closed<?php endif; ?>" data-mcs-section-id="<?php esc_attr_e( $section->ID ); ?>" id="mcs-section-<?php esc_attr_e( $section->ID ); ?>">

	<div class="mcs-row mcs-title-row">
		<div class="mcs-columns-4">
			<div class="msc-title-editor left">
				<input type="text" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][post_title]" class="mcs-section-title widefat left" value="<?php esc_attr_e( $section->post_title ); ?>" />
				<span class="close-title-edit left"><?php _e( 'Done', 'linchpin-mcs' ); ?></span>
				<span class="handle-title"><?php esc_html_e( $section->post_title ); ?></span>
			</div>

			<span class="spinner left"></span>
		</div>

		<div class="mcs-columns-6 text-right right">
			<div id="section-status-select-<?php esc_attr_e( $section->ID ); ?>-container">
				<label for="section-status-select-<?php esc_attr_e( $section->ID ); ?>"><strong><?php esc_html_e( 'Status:', 'linchpin-mcs' ); ?></strong></label>
				<select class="mcs-block-propagation" id="section-status-select-<?php esc_attr_e( $section->ID ); ?>" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][post_status]">
					<option value="draft" <?php selected( $section->post_status, 'draft' ); ?>><?php esc_html_e( 'Draft', 'linchpin-mcs' ); ?></option>
					<option value="publish" <?php selected( $section->post_status, 'publish' ); ?>><?php esc_html_e( 'Published', 'linchpin-mcs' ); ?></option>
				</select>
			</div>
		</div>
	</div>

	<span class="handlediv text-center"></span>

	<div class="inside">

		<?php include LINCHPIN_MCS___PLUGIN_DIR . 'admin/section-controls.php'; ?>

		<div class="mcs-editor-blocks" id="mcs-sections-editor-<?php esc_attr_e( $section->ID ); ?>">

		<?php
		if ( $blocks ) {

			include LINCHPIN_MCS___PLUGIN_DIR . 'admin/section-template-reordering.php';

			include LINCHPIN_MCS___PLUGIN_DIR . 'admin/section-blocks.php';

			include LINCHPIN_MCS___PLUGIN_DIR . 'admin/section-template-warnings.php';
		}
		?>
		</div>
		<div class="mcs-row">
			<div class="mcs-section-remove-container mcs-right">
				<span class="spinner"></span>
				<a href="#" class="button mcs-section-remove"><?php esc_html_e( 'Remove Section', 'linchpin-mcs' ); ?></a>
			</div>
		</div>
	</div>
</div>