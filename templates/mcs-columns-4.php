<?php
/*
 * MCS Template: 4 Columns
 *
 * MCS Template Blocks: 4
 *
 * @since 1.3.6
 *
 * @package MultipleContentSections
 * @subpackage Templates
 */
?>

<section <?php post_class() ?> <?php mcs_section_background(); ?>>
	<div class="row">
		<?php
			$blocks = mcs_get_section_blocks( get_the_ID() );
		?>
		<?php foreach ( $blocks as $block ) : ?>
			<?php
			$column_width = get_post_meta( $block->ID, '_mcs_column_width', true );
			$block_css_class = get_post_meta( $block->ID, '_mcs_css_class',  true );
			$block_offset = get_post_meta( $block->ID, '_mcs_offset',  true );
			$offset_class = 'medium-' . $column_width;

			// Change our column width based on our offset.
			if ( ! empty( $block_offset ) ) {
				$offset_class = 'medium-' . ( $column_width - $block_offset ) . ' medium-offset-' . $block_offset;
			}
			?>
			<div class="small-12 <?php esc_attr_e( $offset_class ); ?> columns <?php esc_attr_e( $block_css_class ); ?>">
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<?php echo apply_filters( 'the_content', $block->post_content ); ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
