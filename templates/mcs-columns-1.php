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

if ( has_post_thumbnail() ) {
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ) );
	$style = ' style="background-image: url(' . $image[0] . ');"';
}
?>

<section <?php post_class(); if ( ! empty( $style ) ) echo $style; ?>>
	<div class="row">
		<div class="small-12 columns">
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
