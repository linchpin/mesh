<?php
/**
 * Container Template for editors
 *
 * @package MultipleContentSection
 * @subpackage AdminTemplates
 * @since 1.2.0
 */
?>

<div class="multiple-content-sections-section multiple-content-sections-postbox postbox <?php echo $closed ? 'closed' : '' ; ?>" data-mcs-section-id="<?php esc_attr_e( $section->ID ); ?>">
	<div class="handlediv" title="Click to toggle"><br></div>
	<h3 class="hndle ui-sortable-handle"><span><?php echo $section->post_title; ?></span><span class="spinner"></span></h3>
	<div class="inside">
		<p>
			<input type="text" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][post_title]" class="mcs-section-title widefat" value="<?php esc_attr_e( $section->post_title ); ?>" />
		</p>
		<p class="mcs-section-meta">
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
					<option value=""><?php esc_html_e( 'Default', 'linchpin-mcs' ); ?></option>
					<option value="columns-2"><?php esc_html_e( '2 Content Columns', 'linchpin-mcs' ); ?></option>
					<?php foreach ( array_keys( $templates ) as $template ) : ?>
						<option value="<?php esc_attr_e( $template ); ?>" <?php selected( $selected, $template ); ?>><?php esc_html_e( $templates[ $template ] ); ?></option>
					<?php endforeach; ?>
				</select>
			</span>
		</p>

		<div class="mcs-editor-area">
			<?php // @todo: Load in the editor sections here using ajax or otherwise. ?>

			<?php include LINCHPIN_MCS___PLUGIN_DIR . 'admin/templates/default.php'; ?>
		</div>
		<p class="mcs-section-remove-container mcs-right">
			<span class="spinner"></span>
			<a href="#" class="button mcs-section-remove"><?php esc_html_e( 'Remove Section', 'linchpin-mcs' ); ?></a>
		</p>
	</div>
</div>