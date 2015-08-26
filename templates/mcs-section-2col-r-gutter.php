<?php
/*
 * MCS Template: 2 Column Right w/ Gutter
 */
?>



<section <?php post_class( 'mcs-section 2col-r-wgutter' ) ?>>
  <div class="row">
    <div class="small-12 medium-5 end medium-push-7 columns">  
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
   <div class="small-12 medium-6 medium-pull-6 columns">
        <h2 class="entry-title"><?php the_title(); ?></h2>
        <?php the_content(); ?>
    </div>
  </div>
</section>



