<?php
/*
 * MCS Template: 1 Column
 */
?>



<section <?php post_class( 'mcs-section 1col' ) ?>>
  <div class="row">
    <div class="small-12 columns">
      <h2 class="entry-title"><?php the_title(); ?></h2>      
         <?php if ( has_post_thumbnail() ) : ?>
        <div class="small-only-text-center">
           <?php the_post_thumbnail(); ?>
        </div>
        <?php endif; ?>
        <div>
          <?php the_content(); ?>
        </div>
    </div>
  </div>
</section>


