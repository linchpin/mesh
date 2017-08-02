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

	<a href="#" class="slide-toggle-element slide-toggle-meta-dropdown mesh-more-section-options" data-toggle=".mesh-section-meta-dropdown-<?php echo esc_attr( $section->ID ); ?>"><?php esc_html_e( 'More Options', 'mesh' ); ?></a>
</div>

<div class="mesh-section-meta-dropdown mesh-section-meta-dropdown-<?php echo esc_attr( $section->ID ); ?> mesh-row hide">
	<div class="mesh-columns-12 mesh-table">
		<div class="mesh-row mesh-table-footer">
			<?php

			/**
			 * If we are utilizing a reference template our controls should not be output?
			 * @todo Maybe we show the information but do not output as fields.
			 */

			if ( ! has_term( 'reference', 'mesh_template_types', $post ) ) : ?>
			<?php mesh_section_controls( $section, $blocks, false ); ?>
			<?php endif; ?>
		</div>
	</div>
	<?php

	/**
	 * Add the ability to add controls after
	 */
	do_action( 'mesh_section_add_controls_after' ); ?>
</div>
