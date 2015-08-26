<?php
/*
 * MCS Template: 2 Column 60-30
 */
?>



<section <?php post_class( 'mcs-section 2col-sixtythirty' ) ?>>
  <div class="row">
    <div class="small-12 medium-8 columns">
        <h2 class="entry-title"><?php the_title(); ?></h2>
        <?php the_content(); ?>
    </div>
    <div class="small-12 medium-4 columns">
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="small-only-text-center">
           <?php the_post_thumbnail(); ?>
        </div>
        <?php endif; ?>
        <div class="image-caption">
           <?php
           /* TODO: Set caption with image's title */
           ?>
        </div>
    </div>
  </div>
</section>
