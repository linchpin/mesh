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
	<div class="handlediv" title="Click to toggle">
		<br>
	</div>
	<h3 class="hndle mcs-row">
		<span class="handle-title"><?php esc_html_e( $section->post_title ); ?></span><span class="spinner"></span>

		<div class="mcs-right">
			<div id="section-status-select-<?php esc_attr_e( $section->ID ); ?>-container">
				<label for="section-status-select-<?php esc_attr_e( $section->ID ); ?>"><strong><?php esc_html_e( 'Status:', 'linchpin-mcs' ); ?></strong></label>
				<select class="mcs-block-propagation" id="section-status-select-<?php esc_attr_e( $section->ID ); ?>" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][post_status]">
					<option value="draft" <?php selected( $section->post_status, 'draft' ); ?>><?php esc_html_e( 'Draft', 'linchpin-mcs' ); ?></option>
					<option value="publish" <?php selected( $section->post_status, 'publish' ); ?>><?php esc_html_e( 'Published', 'linchpin-mcs' ); ?></option>
				</select>
			</div>
		</div>
	</h3>
	<div class="inside">
		<div class="mcs-row">
			<div class="mcs-columns-12">
				<input type="text" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][post_title]" class="mcs-section-title widefat" value="<?php esc_attr_e( $section->post_title ); ?>" />
			</div>
		</div>
		<div class="mcs-section-meta mcs-row">
			<div class="mcs-columns-4">
				<?php if ( empty( $featured_image_id ) ) : ?>
					<a href="#" class="mcs-featured-image-choose button dashicons-before dashicons-format-image"><?php esc_html_e( 'Set Background Image', 'linchpin-mcs' ); ?></a>
				<?php else : ?>
					<a href="#" class="mcs-featured-image-choose button dashicons-before dashicons-edit" data-mcs-section-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"><?php echo get_the_title( $featured_image_id ); ?></a>
					<a href="#" class="mcs-featured-image-trash button dashicons-before dashicons-trash" data-mcs-section-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"><?php esc_html_e( 'Remove', 'linchpin-mcs' ); ?></a>
				<?php endif; ?>
			</div>
			<div class="mcs-columns-8 text-right">
				<label for="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][template]"><strong><?php esc_html_e( 'Template:', 'linchpin-mcs' ); ?></strong></label>

				<select class="mcs-choose-layout" id="mcs-sections-template-<?php esc_attr_e( $section->ID ); ?>" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][template]">
					<?php foreach ( array_keys( $templates ) as $template ) : ?>
						<option value="<?php esc_attr_e( $template ); ?>" <?php selected( $selected_template, $template ); ?>><?php esc_html_e( $templates[ $template ]['file'] ); ?></option>
					<?php endforeach; ?>
				</select>

				<label for="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][css-class]">
					<strong><?php esc_html_e( 'CSS Class', 'linchpin-mcs' ); ?></strong> <input type="text" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][css_class]" value="<?php esc_attr_e( $css_class ); ?>" />
				</label>

				<label for="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][title-display]">
					<strong><?php esc_html_e( 'Title Display', 'linchpin-mcs' ); ?></strong>
					<select name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][title_display]" value="<?php esc_attr_e( $title_display ); ?>">
						<option value="" <?php if ( '' == $title_display ) : ?>selected="selected"<?php endif; ?>> - Select - </option>
						<option value="none" <?php if ( 'none' == $title_display ) : ?>selected="selected"<?php endif; ?>>Hide Title</option>
						<?php if ( count( $blocks ) > 1 ) : ?>
						<option value="top" <?php if ( 'top' == $title_display ) : ?>selected="selected"<?php endif; ?>>Top</option>
						<?php foreach( $blocks as $block ) : ?>
						<option value="block-<?php echo $block->menu_order; ?>" <?php if ( 'block-' . $block->menu_order == $title_display ) : ?>selected="selected"<?php endif; ?>>In Block <?php echo $block->menu_order; ?></option>
						<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</label>

				<?php if ( 2 == count( $blocks ) ) : ?>
				<label for="mcs-section[<?php esc_attr_e( $section->ID ); ?>][push-pull]">
					<strong><?php esc_html_e( 'Push/Pull Columns', 'linchpin-mcs' ); ?></strong>
					<input type="checkbox" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][push_pull]" value="1" <?php if ( get_post_meta( $section->ID, '_mcs_push_pull', true ) ): ?>checked<?php endif; ?> />
				</label>
				<?php endif; ?>
			</div>
		</div>

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