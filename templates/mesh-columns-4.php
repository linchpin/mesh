<?php
/**
 * Mesh Template designed to display 4 columns.
 *
 * Mesh Template: 4
 * Mesh Template Blocks: 4
 *
 * @since      0.3.6
 *
 * @package    Mesh
 * @subpackage Templates
 */

?>
<section <?php post_class() ?> <?php mesh_section_background(); ?>>
	<?php
		$title_display = get_post_meta( get_the_ID(), '_mesh_title_display', true );
		$collapse_column_spacing = get_post_meta( get_the_ID(), '_mesh_collapse', true );
		$lp_equal = get_post_meta( get_the_ID(), '_mesh_lp_equal', true );
	?>
	<div class="row <?php if ( ! empty( $collapse_column_spacing ) ) : ?>collapse <?php endif; ?>"<?php if ( ! empty( $lp_equal ) ) : ?> data-equalizer data-equalize-on="medium"<?php endif; ?>>
		<?php if ( ! empty( $title_display ) && 'no block title' !== strtolower( get_the_title() ) ) : ?>
			<div class="small-12 columns title-row">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</div>
		<?php endif; ?>

		<?php $blocks = mesh_get_section_blocks( get_the_ID() ); ?>
		<?php $i = 0; foreach ( $blocks as $block ) : ?>
			<?php
			$block_class_args = array(
				'total_columns'    => count( $blocks ),
				'column_width'     => (int) get_post_meta( $block->ID, '_mesh_column_width', true ),
				'column_index'     => $i,
				'collapse_spacing' => ( ! empty( $collapse_column_spacing ) ) ? 'collapse' : '',
			);
			?>
			<div <?php mesh_block_class( $block->ID, $block_class_args ); ?><?php if ( ! empty( $lp_equal ) ) : ?> data-equalizer-watch<?php endif; ?> <?php mesh_section_background( $block->ID ); ?>>
				<?php if ( ! empty( $block->post_title ) && 'no column title' !== strtolower( $block->post_title ) ) : ?>
					<h3 class="entry-subtitle"><?php echo apply_filters( 'the_title', $block->post_title ); ?></h3>
				<?php $title_displayed = true;
				endif; ?>
				<?php echo apply_filters( 'the_content', $block->post_content ); ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
