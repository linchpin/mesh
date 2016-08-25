<?php
/**
 * Mesh Template: 2
 * Mesh Template Blocks: 2
 *
 * @since      0.3.6
 *
 * @package    Mesh
 * @subpackage Templates
 */

?>
<section <?php post_class() ?> <?php mesh_section_background(); ?>>
	<?php
		$push_pull       = get_post_meta( get_the_ID(), '_mesh_push_pull', true );
		$collapse_column_spacing = get_post_meta( get_the_ID(), '_mesh_collapse', true );
		$lp_equal = get_post_meta( get_the_ID(), '_mesh_lp_equal', true );

		$title_display = get_post_meta( get_the_ID(), '_mesh_title_display', true );
	?>
	<div class="row <?php if ( ! empty( $collapse_column_spacing ) ) : ?>collapse<?php endif; ?>"<?php if ( ! empty( $lp_equal ) ) : ?> data-equalizer<?php endif; ?>>
		<?php if ( ! empty( $title_display ) && 'no block title' != strtolower( get_the_title() ) ) : ?>
			<div class="small-12 columns">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</div>
		<?php endif; ?>

		<?php $blocks = mesh_get_section_blocks( get_the_ID() ); ?>
		<?php $i = 0; foreach ( $blocks as $block ) : ?>
			<?php
			$column_width = (int) get_post_meta( $block->ID, '_mesh_column_width', true );
			$block_css_class = get_post_meta( $block->ID, '_mesh_css_class',  true );
			$block_offset = get_post_meta( $block->ID, '_mesh_offset',  true );

			if ( isset( $push_pull ) ) {
				if ( 0 == $i ) {
					$push_pull_class = 'medium-push-' . ( 12 - $column_width );
				}

				if ( 1 == $i ) {
					$push_pull_class = 'medium-pull-' . ( 12 - $column_width );
				}
			}

			$offset_class = 'medium-' . $column_width;

			// Change our column width based on our offset.
			if ( ! empty( $block_offset ) ) {
				$offset_class = 'medium-' . ( $column_width - $block_offset ) . ' medium-offset-' . $block_offset;
			} ?>

			<div class="small-12 <?php if ( ! empty( $collapse_column_spacing ) ) : ?>collapse <?php endif; ?><?php esc_attr_e( $offset_class ); ?> columns <?php esc_attr_e( $block_css_class ); ?> <?php if ( $push_pull ) { echo $push_pull_class; } ?>"<?php if ( ! empty( $lp_equal ) ) : ?> data-equalizer-watch<?php endif; ?> <?php mesh_section_background( $block->ID ); ?>>
				<?php if ( ! empty( $block->post_title ) && 'no column title' != strtolower( $block->post_title ) ) : ?>
					<h3 class="entry-subtitle"><?php echo apply_filters( 'the_title', $block->post_title ); ?></h3>
				<?php endif; ?>

				<?php echo apply_filters( 'the_content', $block->post_content ); ?>
			</div>
		<?php $i++;
		endforeach; ?>
	</div>
</section>
