<?php
/*
 * MCS Template: 2 Column Open
 */
?>

<section <?php post_class( 'mcs-section 2col-open' ) ?>>
  <div class="row">
    <div class="small-12 medium-6 columns">
       <?php /* TODO: Set Secondary Content here */ ?>
    </div>
    <div class="small-12 medium-6 columns">
        <h2 class="entry-title"><?php the_title(); ?></h2>
        <?php the_content(); ?>
    </div>
  </div>
</section>


