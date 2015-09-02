<?php
/*
 * MCS Template: 2 Columns
 *
 * MCS Template Blocks: 2
 *
 * @since 1.3.5
 *
 * @package MultipleContentSections
 * @subpackage Templates
 */
?>

<section <?php post_class() ?> <?php mcs_section_background(); ?>>
	<div class="row">
		<?php $blocks = mcs_get_section_blocks( get_the_ID() ); ?>
		<?php foreach ( $blocks as $block ) : ?>
			<div class="small-12 medium-<?php esc_attr_e( get_post_meta( $block->ID, '_mcs_column_width', true ) ); ?> columns">
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<?php echo apply_filters( 'the_content', $block->post_content ); ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
