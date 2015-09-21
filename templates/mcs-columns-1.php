<?php
/*
 * MCS Template: 1 Column
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
		$title_display = get_post_meta( get_the_ID(), '_mcs_title_display', true );
		$title_displayed = false;

		if ( 'none' == $title_display ) {
			$title_displayed = true;
		}
	?>

	<div class="row">
		<?php if ( ! $title_displayed ) : ?>
			<?php if ( empty( $title_display ) || 'top' == $title_display ) : ?>
			<div class="small-12 columns">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</div>
			<?php $title_displayed = true; endif; ?>
		<?php endif; ?>
		<?php if ( $blocks = mcs_get_section_blocks( get_the_ID() ) ) :
			foreach ( $blocks as $block ) : ?>
				<?php $block_css_class = get_post_meta( $block->ID, '_mcs_css_class', true ); ?>
				<div class="small-12 columns <?php esc_attr_e( $block_css_class ); ?>">
					<?php echo apply_filters( 'the_content', $block->post_content ); ?>
				</div>
			<?php endforeach;
		endif; ?>
	</div>
</section>
