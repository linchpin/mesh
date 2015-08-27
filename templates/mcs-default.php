<?php
/*
 * MCS Template: Default
 *
 * @since 1.3.5
 *
 * @package MultipleContentSections
 * @subpackage Templates
 */
?>
<section <?php post_class( 'mcs-section mcs-col-1' ) ?>>
	<div class="row">
		<div class="small-12 columns">
			<h2 class="entry-title"><?php the_title(); ?></h2>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="small-only-text-center">
					<?php the_post_thumbnail(); ?>
				</div>
			<?php endif; ?>
			<div>
				<?php
					$blocks = mcs_get_section_blocks( get_the_ID() );

					foreach ( $blocks as $block ) {
						apply_filters( 'the_content', $block->post_content );
					}
				?>
			</div>
		</div>
	</div>
</section>
