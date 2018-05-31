<?php
/**
 * Mesh Template designed to display 1 column.
 *
 * Mesh Template: 1
 * Mesh Template Blocks: 1
 *
 * @since      0.3.6
 *
 * @package    Mesh
 * @subpackage Templates
 */

?>

<?php
/**
 * Add the ability to add markup before Mesh section
 */
do_action( 'mesh_section_before' );
?>

<section <?php post_class( 'mesh_section' ); ?> <?php mesh_section_background(); ?> <?php mesh_section_attributes(); ?>>
	<?php
	/**
	 * Add the ability to add markup before Mesh row
	 */
	do_action( 'mesh_row_before' );
	?>

	<div <?php mesh_row_class(); ?> <?php mesh_row_attributes(); ?>>
		<?php if ( mesh_maybe_show_section_title() ) : ?>
			<div class="<?php echo esc_attr( mesh_get_title_class() ); ?>">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</div>
		<?php endif; ?>

		<?php do_action( 'mesh_columns_before' ); ?>

		<?php
		$blocks                  = mesh_get_section_blocks( get_the_ID() );
		$collapse_column_spacing = get_post_meta( get_the_ID(), '_mesh_collapse', true );
		$collapse_column_spacing = ( ! empty( $collapse_column_spacing ) ) ? 'collapse' : '';

		if ( ! empty( $blocks ) ) :
			foreach ( $blocks as $block ) :
				?>
				<div <?php mesh_block_class( $block->ID ); ?> <?php mesh_section_background( $block->ID ); ?> <?php mesh_column_attributes( $block->ID, 'string' ); ?>>
					<?php if ( mesh_maybe_show_block_title( $block->post_title ) ) : ?>
						<h3 class="entry-subtitle"><?php echo esc_html( apply_filters( 'the_title', $block->post_title ) ); ?></h3>
					<?php endif; ?>
					<?php
						echo apply_filters( 'the_content', $block->post_content ); // WPCS: XSS ok.
					?>
				</div>
			<?php
			endforeach;
		endif;
		?>

		<?php do_action( 'mesh_columns_after' ); ?>
	</div>

	<?php
	/**
	 * Add the ability to add markup after Mesh row
	 */
	do_action( 'mesh_row_after' );
	?>
</section>

<?php
/**
 * Add the ability to add markup after Mesh section
 */
do_action( 'mesh_section_after' );
