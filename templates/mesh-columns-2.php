<?php
/**
 * Mesh Template designed to display 2 columns.
 *
 * Mesh Template: 2
 * Mesh Template Blocks: 2
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
		$block_increment         = 0;
		$collapse_column_spacing = get_post_meta( get_the_ID(), '_mesh_collapse', true );
		$collapse_column_spacing = ( ! empty( $collapse_column_spacing ) ) ? 'collapse' : '';
		$push_pull               = get_post_meta( get_the_ID(), '_mesh_push_pull', true );

		if ( ! isset( $push_pull ) ) {
			$push_pull = false;
		}

		?>
		<?php foreach ( $blocks as $block ) : ?>
			<?php
			$block_class_args = array(
				'push_pull'        => $push_pull,
				'total_columns'    => count( $blocks ),
				'column_index'     => $block_increment++,
				'collapse_spacing' => $collapse_column_spacing,
			);
			?>
			<div <?php mesh_block_class( $block->ID, $block_class_args ); ?> <?php mesh_section_background( $block->ID ); ?>  <?php mesh_column_attributes( $block->ID, 'string' ); ?>>
				<?php if ( mesh_maybe_show_block_title( $block->post_title ) ) : ?>
					<h3 class="entry-subtitle"><?php echo esc_html( $block->post_title ); ?></h3>
				<?php endif; ?>
				<?php echo apply_filters( 'the_content', $block->post_content ); // WPCS: XSS ok. ?>
			</div>
		<?php endforeach; ?>

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
