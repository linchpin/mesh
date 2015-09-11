<?php
/*
 * MCS Template: 1 Column
 *
 * MCS Template Blocks: 1
 *
 * @since 1.3.5
 *
 * @package MultipleContentSections
 * @subpackage Templates
 */

?>

<section <?php post_class(); ?> <?php mcs_section_background(); ?>>


	<div class="row">
		<div class="small-12 columns <?php esc_attr_e( $block_css_class ); ?>">
			<h2 class="entry-title"><?php the_title(); ?></h2>
			<?php
			if ( $blocks = mcs_get_section_blocks( get_the_ID() ) ) {
				foreach ( $blocks as $block ) {
					echo apply_filters( 'the_content', $block->post_content );
				}
			}
			?>
		</div>
	</div>
</section>
