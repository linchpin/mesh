<?php
/**
 * Mesh Template: 1
 * Mesh Template Blocks: 1
 *
 * @since      0.3.6
 *
 * @package    Mesh
 * @subpackage Templates
 */

?>
<section id="mesh-section-<?php the_ID(); ?>" <?php post_class(); ?> <?php mesh_section_background(); ?> <?php mesh_section_attributes(); ?>>
	<?php
		$title_display           = get_post_meta( get_the_ID(), '_mesh_title_display', true );
		$collapse_column_spacing = get_post_meta( get_the_ID(), '_mesh_collapse', true );
		$lp_equal = get_post_meta( get_the_ID(), '_mesh_lp_equal', true );
	?>
	<div class="row <?php if ( ! empty( $collapse_column_spacing ) ) : ?>collapse<?php endif; ?>"<?php if ( ! empty( $lp_equal ) ) : ?> <?php echo esc_attr( $lp_equal ); ?><?php endif; ?>>
		<?php if ( ! empty( $title_display ) && 'no block title' !== strtolower( get_the_title() ) ) : ?>
			<div class="small-12 columns title-row">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</div>
		<?php endif; ?>

		<?php if ( $blocks = mesh_get_section_blocks( get_the_ID() ) ) :
			foreach ( $blocks as $block ) : ?>
				<div <?php mesh_block_class( $block->ID ); ?> <?php mesh_section_background( $block->ID ); ?>>
					<?php if ( ! empty( $block->post_title ) && 'no column title' !== strtolower( $block->post_title ) ) : ?>
						<h3 class="entry-subtitle"><?php echo esc_html( apply_filters( 'the_title', $block->post_title ) ); ?></h3>
					<?php endif; ?>

					<?php echo apply_filters( 'the_content', $block->post_content ); // @todo what sanitization is best suited here? ?>
				</div>
			<?php endforeach;
		endif; ?>
	</div>
</section>
