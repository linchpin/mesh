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
<?php $offset = (int) get_post_meta( get_the_ID(), '_mcs_offset', true ); ?>

<section <?php post_class() ?> <?php mcs_section_background(); ?>>
	<div class="row">
		<?php $blocks = mcs_get_section_blocks( get_the_ID() ); ?>
		<?php $i = 1; foreach ( $blocks as $block ) : ?>

			<?php
				$column_width = get_post_meta( $block->ID, '_mcs_column_width', true );
				$block_css_class = get_post_meta( $block->ID, '_mcs_css_class',  true );

				if ( 2 == $i && $offset ) :
			?>
			<div class="small-12 medium-<?php esc_attr_e( $column_width - $offset ); ?> medium-offset-<?php esc_attr_e( $offset ); ?> columns <?php esc_attr_e( $block_css_class ); ?>">
			<?php else: ?>
			<div class="small-12 medium-<?php esc_attr_e( $column_width ); ?> columns <?php esc_attr_e( $block_css_class ); ?>">
			<?php endif; ?>
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<?php echo apply_filters( 'the_content', $block->post_content ); ?>
			</div>
		<?php $i++; endforeach; ?>
	</div>
</section>
