<?php
/*
 * MCS Template: 2 Column w/ Gutter
 *
 * MCS Template Blocks: 2
 * MCS Template Gap : 1
 *
 */
?>

<section <?php post_class() ?>>
	<div class="row">
		<div class="small-12 medium-4 columns">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="small-only-text-center">
					<?php the_post_thumbnail(); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="small-12 medium-7 columns">
			<h2 class="entry-title"><?php the_title(); ?></h2>
			<?php the_content(); ?>
		</div>
	</div>
</section>


