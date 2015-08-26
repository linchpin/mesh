<?php
/**
 * Container Template for editors
 *
 * @package MultipleContentSection
 * @subpackage AdminTemplates
 * @since 1.2.0
 */

if ( ! $closed_metaboxes = get_user_option( 'closedpostboxes_page' ) ) {
	$closed_metaboxes = array();
}
?>
<div class="multiple-content-sections-section multiple-content-sections-postbox postbox<?php if ( in_array( 'mcs-section-' . esc_attr( $section->ID ), $closed_metaboxes ) ) : ?> closed<?php endif; ?>" data-mcs-section-id="<?php esc_attr_e( $section->ID ); ?>" id="mcs-section-<?php esc_attr_e( $section->ID ); ?>">
	<div class="handlediv" title="Click to toggle">
		<br>
	</div>
	<h3 class="hndle"><span><?php esc_html_e( $section->post_title ); ?></span><span class="spinner"></span></h3>
	<div class="inside">
		<p>
			<input type="text" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][post_title]" class="mcs-section-title widefat" value="<?php esc_attr_e( $section->post_title ); ?>" />
		</p>
		<div class="mcs-section-meta">
			<span class="mcs-right">
				<?php if ( empty( $featured_image_id ) ) : ?>
					<a href="#" class="mcs-featured-image-choose"><?php esc_html_e( 'Choose background image', 'linchpin-mcs' ); ?></a>
				<?php else : ?>
					<a href="#" class="mcs-featured-image-choose" data-mcs-section-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"><?php echo get_the_title( $featured_image_id ); ?> <span class="dashicons dashicons-edit"></span></a>
				<?php endif; ?>
			</span>
			<span class="mcs-left">
				<label for="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][post_status]"><strong><?php esc_html_e( 'Status:', 'linchpin-mcs' ); ?></strong></label>
				<select name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][post_status]">
					<option value="draft" <?php selected( $section->post_status, 'draft' ); ?>><?php esc_html_e( 'Draft', 'linchpin-mcs' ); ?></option>
					<option value="publish" <?php selected( $section->post_status, 'publish' ); ?>><?php esc_html_e( 'Published', 'linchpin-mcs' ); ?></option>
				</select>

				<label for="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][template]"><strong><?php esc_html_e( 'Template:', 'linchpin-mcs' ); ?></strong></label>

				<select class="mcs-choose-layout" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][template]">

					<?php $mcs_templates = Multiple_Content_Sections::$template_data; ?>

					<?php foreach ( $mcs_templates as $key => $mcs_template ) : ?>
						<option value="<?php esc_attr_e( $key ); ?>" <?php selected( $selected_template, $key ); ?>><?php esc_html_e( $mcs_template['label'], 'linchpin-mcs' ); ?></option>
					<?php endforeach; ?>

					<?php foreach ( array_keys( $templates ) as $template ) : ?>
						<option value="<?php esc_attr_e( $template ); ?>" <?php selected( $selected_template, $template ); ?>><?php esc_html_e( $templates[ $template ] ); ?></option>
					<?php endforeach; ?>
				</select>
			</span>
		</div>

		<div class="mcs-editor-blocks" id="mcs-sections-editor-<?php esc_attr_e( $section->ID ); ?>">

		<?php
		if ( $blocks = mcs_maybe_create_section_blocks( $section ) ) {

			include LINCHPIN_MCS___PLUGIN_DIR . '/admin/section-template-reordering.php';

			if ( (int) Multiple_Content_Sections::$template_data[ $selected_template ]['blocks'] > 1 ) : ?>
				<div class="wp-slider column-slider" data-mcs-columns="<?php esc_attr_e( get_post_meta( $blocks[0]->ID, '_mcs_column_width', true ) ); ?>"><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span></div>
			<?php endif;

			switch ( $selected_template ) {
				case 'columns-3.php' :
					include LINCHPIN_MCS___PLUGIN_DIR . '/admin/templates/columns-3.php';
					break;
				case 'columns-2.php' :
					include LINCHPIN_MCS___PLUGIN_DIR . '/admin/templates/columns-2.php';
					break;
				default :
					include LINCHPIN_MCS___PLUGIN_DIR . '/admin/templates/default.php';
			}

			include LINCHPIN_MCS___PLUGIN_DIR . '/admin/section-template-warnings.php';
		}

		?>
		</div>
		<p class="mcs-section-remove-container mcs-right">
			<span class="spinner"></span>
			<a href="#" class="button mcs-section-remove"><?php esc_html_e( 'Remove Section', 'linchpin-mcs' ); ?></a>
		</p>
	</div>
</div>