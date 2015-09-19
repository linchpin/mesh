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
	<?php
		$push_pull       = get_post_meta( get_the_ID(), '_mcs_push_pull', true );

		$title_display   = get_post_meta( get_the_ID(), '_mcs_title_display', true );
		$title_displayed = false;

		if ( 'none' == $title_display ) {
			$title_displayed = true;
		}
	?>
	<div class="row">
		<?php if ( ! $title_displayed ) : ?>
			<?php if ( empty( $title_display ) || 'top' === $title_display ) : ?>
			<div class="small-12 columns">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</div>
			<?php $title_displayed = true;
			endif; ?>
		<?php endif; ?>

		<?php $blocks = mcs_get_section_blocks( get_the_ID() ); ?>
		<?php $i = 0; foreach ( $blocks as $block ) : ?>
			<?php
			$column_width = (int) get_post_meta( $block->ID, '_mcs_column_width', true );
			$block_css_class = get_post_meta( $block->ID, '_mcs_css_class',  true );
			$block_offset = get_post_meta( $block->ID, '_mcs_offset',  true );

			if ( isset( $push_pull ) ) {
				if ( 0 == $i ) {
					$push_pull_class = 'push-' . ( 12 - $column_width );
				}

				if ( 1 == $i ) {
					$push_pull_class = 'pull-' . ( 12 - $column_width );
				}
			}

			$offset_class = 'medium-' . $column_width;

			// Change our column width based on our offset.
			if ( ! empty( $block_offset ) ) {
				$offset_class = 'medium-' . ( $column_width - $block_offset ) . ' medium-offset-' . $block_offset;
			} ?>

			<div class="small-12 <?php esc_attr_e( $offset_class ); ?> columns <?php esc_attr_e( $block_css_class ); ?> <?php if ( $push_pull ) { echo $push_pull_class; } ?>">
				<?php if ( ! $title_displayed && 'block-' . $i == $title_display ) : ?>
					<h2 class="entry-title"><?php the_title(); ?></h2>
				<?php $title_displayed = true; endif; ?>

				<?php echo apply_filters( 'the_content', $block->post_content ); ?>
			</div>
		<?php $i++;
		endforeach; ?>
	</div>
</section>
