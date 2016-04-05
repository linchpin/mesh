<?php
/*
 * MCS Template: 1
 *
 * MCS Template Blocks: 1
 *
 * @since 1.3.5
 *
 * @package MultipleContentSections
 * @subpackage Templates
 */

?>

<section <?php post_class(); ?> <?php mcs_section_background(); ?>>

	<?php
		$title_display           = get_post_meta( get_the_ID(), '_mcs_title_display', true );
		$collapse_column_spacing = get_post_meta( get_the_ID(), '_mcs_collapse', true );
		$lp_equal = get_post_meta( get_the_ID(), '_mcs_lp_equal', true );
	?>
	<div class="row <?php if ( ! empty( $collapse_column_spacing ) ) : ?>collapse<?php endif; ?>"<?php if ( ! empty( $lp_equal ) ) : ?> <?php echo $lp_equal; ?><?php endif; ?>>
		<?php if ( ! empty( $title_display ) && 'no block title' != strtolower( get_the_title() ) ) : ?>
			<div class="small-12 columns">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</div>
		<?php endif; ?>

		<?php if ( $blocks = mcs_get_section_blocks( get_the_ID() ) ) :
			foreach ( $blocks as $block ) : ?>
				<?php $block_css_class = get_post_meta( $block->ID, '_mcs_css_class', true ); ?>
				<div class="small-12 columns <?php esc_attr_e( $block_css_class ); ?>">
					<?php if ( ! empty( $block->post_title ) && 'no column title' != strtolower( $block->post_title ) ) : ?>
						<h3 class="entry-subtitle"><?php echo apply_filters( 'the_title', $block->post_title ); ?></h3>
					<?php endif; ?>

					<?php echo apply_filters( 'the_content', $block->post_content ); ?>
				</div>
			<?php endforeach;
		endif; ?>
	</div>
</section>
