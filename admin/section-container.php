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

	<div class="mcs-row mcs-title-row mcs-row-padding">
		<div class="mcs-columns-8">
			<div class="msc-clean-edit">
				<input type="text" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][post_title]" class="msc-clean-edit-element widefat mcs-section-title left" value="<?php esc_attr_e( $section->post_title ); ?>" />
				<span class="close-title-edit left"><?php _e( 'Done', 'linchpin-mcs' ); ?></span>
				<span class="handle-title"><?php esc_html_e( $section->post_title ); ?></span>
			</div>
		</div>

		<div class="mcs-columns-4 text-right">
			<div id="section-status-select-<?php esc_attr_e( $section->ID ); ?>-container">
				<div class="msc-clean-edit handle-right">
					<label for="section-status-select-<?php esc_attr_e( $section->ID ); ?>" class="screen-reader-text"><strong><?php esc_html_e( 'Status:', 'linchpin-mcs' ); ?></strong></label>
					<select class="mcs-block-propagation msc-clean-edit-element mcs-section-status" id="section-status-select-<?php esc_attr_e( $section->ID ); ?>" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][post_status]">
						<option value="draft" <?php selected( $section->post_status, 'draft' ); ?>><?php esc_html_e( 'Draft', 'linchpin-mcs' ); ?></option>
						<option value="publish" <?php selected( $section->post_status, 'publish' ); ?>><?php esc_html_e( 'Published', 'linchpin-mcs' ); ?></option>
					</select>
					<span class="close-title-edit right"><?php _e( 'Done', 'linchpin-mcs' ); ?></span>
					<span class="handle-title"><?php esc_html_e( $section->post_status == 'publish' ? 'Published' : 'Draft' ); ?></span>
				</div>
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

		<div class="mcs-row mcs-section-footer">
			<div class="mcs-section-remove-container mcs-columns-4">
				<span class="spinner"></span>
				<a href="#" class="mcs-section-remove dashicons-before dashicons-no plain-link grey-link"><?php esc_html_e( 'Move to Trash', 'linchpin-mcs' ); ?></a>
			</div>

			<div class="mcs-columns-8 text-right">
				<?php if ( 'draft' == get_post_status( $section->ID ) ) : ?>
					<a href="#" class="button mcs-section-save-draft"><?php esc_html_e( 'Save Draft', 'linchpin-mcs' ); ?></a>
					<a href="#" class="button primary mcs-section-publish"><?php esc_html_e( 'Publish', 'linchpin-mcs' ); ?></a>
				<?php else : ?>
					<a href="#" class="button primary mcs-section-update"><?php esc_html_e( 'Update', 'linchpin-mcs' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>