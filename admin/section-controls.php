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
				<?php mesh_section_controls( $section, $blocks, true ); ?>
			<?php endif; ?>
		</ul>
	</div>

	<a href="#" class="slide-toggle-element slide-toggle-meta-dropdown mesh-more-section-options" data-toggle=".mesh-section-meta-dropdown-<?php echo esc_attr( $section->ID ); ?>"><?php esc_html_e( 'More Options', 'mesh' ); ?></a>
</div>

<div class="mesh-section-meta-dropdown mesh-section-meta-dropdown-<?php echo esc_attr( $section->ID ); ?> mesh-row hide">
	<div class="mesh-columns-9 mesh-table">
		<div class="mesh-row mesh-table-footer">
			<?php

			/**
			 * If we are utilizing a reference template our controls should not be output?
			 * @todo Maybe we show the information but do not output as fields.
			 */

			if ( ! has_term( 'reference', 'mesh_template_types', $post ) ) : ?>
			<ul class="inline-block-list space-left">
				<?php mesh_section_controls( $section, $blocks, false ); ?>
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
				<a href="#" class="mesh-featured-image-choose right" data-mesh-section-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"><img src="<?php echo esc_url( $featured_image[0] ); ?>" /></a>
				<a href="#" class="mesh-featured-image-trash dashicons-before dashicons-dismiss" data-mesh-section-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"></a>
			<?php endif; ?>
		</div>
	</div>
	<?php

	/**
	 * Add the ability to add controls after
	 */
	do_action( 'mesh_section_add_controls_after' ); ?>
</div>
