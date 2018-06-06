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
		<?php if ( ! has_term( 'reference', 'mesh_template_types', $post ) ) : ?>
			<?php mesh_section_controls( $section, $blocks, true ); ?>
		<?php endif; ?>
	</div>

	<a href="#" class="slide-toggle-element slide-toggle-meta-dropdown mesh-more-section-options" data-toggle=".mesh-section-meta-dropdown-<?php echo esc_attr( $section->ID ); ?>">
		<span class="screen-reader-text"><?php esc_html_e( 'More Options', 'mesh' ); ?></span>
	</a>
</div>

<div class="mesh-section-meta-dropdown mesh-section-meta-dropdown-<?php echo esc_attr( $section->ID ); ?> mesh-row hide">

	<div class="mesh-columns-9 mesh-table">
		<div class="mesh-row mesh-table-footer">
			<?php

			/*
			 * If we are utilizing a reference template our controls should not be output?
			 * @todo Maybe we show the information but do not output as fields.
			 */

			if ( ! has_term( 'reference', 'mesh_template_types', $post ) ) :
				?>
				<?php mesh_section_controls( $section, $blocks, false ); ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="mesh-columns-3 mesh-table">
		<div class="mesh-row mesh-table-footer">
			<?php
			if ( ! has_term( 'reference', 'mesh_template_types', $post ) ) :

				$featured_image_id        = get_post_thumbnail_id( $section->ID );
				$section_background_class = 'mesh-section-background';
				$section_background_class = ( ! empty( $featured_image_id ) ) ? $section_background_class . ' has-background-set' : $section_background_class;
				?>

				<div class="<?php echo esc_attr( $section_background_class ); ?>">
					<div class="choose-image">
						<?php if ( empty( $featured_image_id ) ) : ?>
							<a class="mesh-featured-image-choose"><?php esc_attr_e( 'Set Background Image', 'mesh' ); ?></a>
						<?php else : ?>
							<?php
							$featured_image = wp_get_attachment_image_src( $featured_image_id, array( 160, 60 ) );
							?>
							<a class="mesh-featured-image-choose right"
							   data-mesh-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"><img
										src="<?php echo esc_attr( $featured_image[ 0 ] ); ?>"/></a>
							<a class="mesh-featured-image-trash dashicons-before dashicons-dismiss"
							   data-mesh-featured-image="<?php echo esc_attr( $featured_image_id ); ?>"></a>
						<?php endif; ?>
						<input type="hidden"
							   class="mesh-section-background-input"
							   name="mesh-sections[<?php echo esc_attr( $section->ID ); ?>][<?php echo esc_attr( 'featured_image' ); ?>]"
							   value="<?php echo esc_attr( $featured_image_id ); ?>"/>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php

	/**
	 * Add the ability to add controls after
	 */
	do_action( 'mesh_section_add_controls_after' );
	?>
</div>
