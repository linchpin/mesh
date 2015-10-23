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
	</div>
	<div class="mcs-row">
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

		<label for="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][lp-equal]">
			<strong><?php esc_html_e( 'LP Equal', 'linchpin-mcs' ); ?></strong> <input type="text" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][lp_equal]" value="<?php esc_attr_e( $lp_equal ); ?>" />
		</label>

		<label for="mcs-section[<?php esc_attr_e( $section->ID ); ?>][collapse]">
			<strong><?php esc_html_e( 'Collapse Column Padding', 'linchpin-mcs' ); ?></strong>
			<input type="checkbox" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][collapse]" value="1" <?php if ( get_post_meta( $section->ID, '_mcs_collapse', true ) ): ?>checked<?php endif; ?> />
		</label>

		<?php if ( 2 == count( $blocks ) ) : ?>
			<label for="mcs-section[<?php esc_attr_e( $section->ID ); ?>][push-pull]">
				<strong><?php esc_html_e( 'Push/Pull Columns', 'linchpin-mcs' ); ?></strong>
				<input type="checkbox" name="mcs-sections[<?php esc_attr_e( $section->ID ); ?>][push_pull]" value="1" <?php if ( get_post_meta( $section->ID, '_mcs_push_pull', true ) ): ?>checked<?php endif; ?> />
			</label>
		<?php endif; ?>
	</div>
</div>