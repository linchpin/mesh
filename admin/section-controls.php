<?php
/**
 * Contains all section controls
 *
 * @since      0.4.4
 *
 * @package    Mesh
 * @subpackage AdminTemplates
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

global $post;
?>

<div class="mesh-section-meta mesh-row mesh-row-padding">
	<div class="mesh-columns-12">
		<ul class="inline-block-list space-left">
			<?php if ( ! has_term( 'reference', 'mesh_template_types', $post ) ) : ?>
			<li>
				<label for="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][template]"><?php esc_html_e( 'Columns:', 'mesh' ); ?></label>

				<select class="mesh-choose-layout" id="mesh-sections-template-<?php esc_attr_e( $section->ID ); ?>" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][template]">
					<?php foreach ( array_keys( $templates ) as $template ) : ?>
						<option value="<?php esc_attr_e( $template ); ?>" <?php selected( $selected_template, $template ); ?>><?php esc_html_e( $templates[ $template ]['file'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</li>
			<?php endif; ?>
			<li>
				<label for="mesh-section[<?php esc_attr_e( $section->ID ); ?>][title_display]">
					<?php esc_html_e( 'Display Title', 'mesh' ); ?> <input type="checkbox" name="mesh-sections[<?php esc_attr_e( $section->ID ); ?>][title_display]" value="1" <?php if ( get_post_meta( $section->ID, '_mesh_title_display', true ) ) : ?>checked<?php endif; ?> />
				</label>
			</li>
		</ul>
	</div>

	<a href="#" class="slide-toggle-element slide-toggle-meta-dropdown mesh-more-section-options" data-toggle=".mesh-section-meta-dropdown-<?php esc_attr_e( $section->ID ); ?>"><?php _e( 'More Options' ); ?></a>
</div>

<div class="mesh-section-meta-dropdown mesh-section-meta-dropdown-<?php esc_attr_e( $section->ID ); ?> mesh-row hide">
	<div class="mesh-columns-9 mesh-table">
		<div class="mesh-row mesh-table-footer">
			<?php if ( ! has_term( 'reference', 'mesh_template_types', $post ) ) : ?>
			<ul class="inline-block-list space-left">
				<?php mesh_section_controls(  $section, $blocks ); ?>
			</ul>
			<?php endif; ?>
		</div>
	</div>

	<div class="mesh-columns-3 text-right mesh-section-background">
		<div class="choose-image">
			<?php if ( empty( $featured_image_id ) ) : ?>
				<a href="#" class="mesh-featured-image-choose button"><?php esc_html_e( 'Set Background Image', 'mesh' ); ?></a>
			<?php else : ?>
			<?php $featured_image = wp_get_attachment_image_src( $featured_image_id, array( 160, 60 ) ); ?>
				<a href="#" class="mesh-featured-image-choose right" data-mesh-section-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"><img src="<?php echo esc_url( $featured_image[0] ); ?>" /></a>
				<a href="#" class="mesh-featured-image-trash dashicons-before dashicons-dismiss" data-mesh-section-featured-image="<?php esc_attr_e( $featured_image_id ); ?>"></a>
			<?php endif; ?>
		</div>
	</div>
	<?php

	/**
	 * Add the ability to add controls after
	 */
	do_action( 'mesh_section_add_controls_after' ); ?>
</div>