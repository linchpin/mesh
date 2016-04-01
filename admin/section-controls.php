<?php
/**
 * Contains all section controls
 *
 * @since 1.4.4
 *
 * @package MultipleContentSection
 * @subpackage AdminTemplates
 */
?>

<div class="mcs-section-meta mcs-row mcs-row-padding">
	<div class="mcs-columns-12">
		<div class="left">
			<label for="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][template]"><strong><?php esc_html_e( 'Columns:', 'linchpin-mcs' ); ?></strong></label>

			<select class="mcs-choose-layout" id="mcs-sections-template-<?php esc_attr_e( $section->ID ); ?>" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][template]">
				<?php foreach ( array_keys( $templates ) as $template ) : ?>
					<option value="<?php esc_attr_e( $template ); ?>" <?php selected( $selected_template, $template ); ?>><?php esc_html_e( $templates[ $template ]['file'] ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="left">
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
		</div>
	</div>

	<a href="#" class="slide-toggle-element slide-toggle-meta-dropdown" data-toggle=".mcs-section-meta-dropdown"><?php _e( 'More Options' ); ?></a>
</div>

<div class="mcs-section-meta-dropdown mcs-row hide">
	<div class="mcs-columns-9 mcs-table">
		<div class="mcs-row">
			<label for="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][css-class]">
				<?php esc_html_e( 'CSS Class', 'linchpin-mcs' ); ?> <input type="text" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][css_class]" value="<?php esc_attr_e( $css_class ); ?>" />
			</label>
		</div>

		<div class="mcs-row mcs-table-footer">
			<label for="mcs-section[<?php esc_attr_e( $section->ID ); ?>][collapse]">
				<?php esc_html_e( 'Collapse Padding', 'linchpin-mcs' ); ?> <input type="checkbox" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][collapse]" value="1" <?php if ( get_post_meta( $section->ID, '_mcs_collapse', true ) ): ?>checked<?php endif; ?> />
			</label>

			<?php if ( 2 == count( $blocks ) ) : ?>
				<label for="mcs-section[<?php esc_attr_e( $section->ID ); ?>][push-pull]">
					<?php esc_html_e( 'Push/Pull', 'linchpin-mcs' ); ?> <input type="checkbox" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][push_pull]" value="1" <?php if ( get_post_meta( $section->ID, '_mcs_push_pull', true ) ): ?>checked<?php endif; ?> />
				</label>
			<?php endif; ?>

			<label for="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][lp-equal]">
				<?php esc_html_e( 'Equalize', 'linchpin-mcs' ); ?> <input type="text" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][lp_equal]" value="<?php esc_attr_e( $lp_equal ); ?>" />
			</label>
		</div>
	</div>

	<div class="mcs-columns-3 text-right mcs-section-background">
		<div class="choose-image">
			<?php if ( empty( $featured_image_id ) ) : ?>
				<a href="#" class="mcs-featured-image-choose button"><?php esc_html_e( 'Set Background Image', 'linchpin-mcs' ); ?></a>
			<?php else : ?>

			<?php $featured_image = wp_get_attachment_image_src( $featured_image_id, array( 160, 60 ) ); ?>

				<a href="#" class="mcs-featured-image-choose right" data-mcs-section-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"><img src="<?php echo $featured_image[0]; ?>" /></a>

				<a href="#" class="mcs-featured-image-trash dashicons-before dashicons-dismiss" data-mcs-section-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"></a>
			<?php endif; ?>
		</div>
	</div>
</div>