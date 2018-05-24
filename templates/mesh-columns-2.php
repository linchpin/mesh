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

<?php
/**
 * Add the ability to add markup before Mesh section
 */
do_action( 'mesh_section_before' );
?>

<section <?php post_class('mesh_section'); ?> <?php mesh_section_background(); ?> <?php mesh_section_attributes(); ?>>
	<?php
	/**
	 * Add the ability to add markup before Mesh row
	 */
	do_action( 'mesh_row_before' );
	?>

	<?php $title_display = get_post_meta( get_the_ID(), '_mesh_title_display', true ); ?>
	<div class="<?php echo esc_attr( mesh_get_row_class() ); ?>" <?php mesh_row_attributes(); ?>>
		<?php if ( ! empty( $title_display ) && 'no block title' !== strtolower( get_the_title() ) ) : ?>
			<div class="<?php echo esc_attr( mesh_get_title_class() ); ?>">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</div>
		<?php endif; ?>

		<?php do_action( 'mesh_columns_before' ); ?>

		<?php $blocks = mesh_get_section_blocks( get_the_ID() ); ?>

		<?php $i = 0; foreach ( $blocks as $block ) : ?>
			<?php
			$column_width = (int) get_post_meta( $block->ID, '_mesh_column_width', true );

			if ( ! isset( $push_pull ) ) {
				$push_pull = false;
			}

			$block_class_args = array(
				'push_pull'        => $push_pull,
				'total_columns'    => count( $blocks ),
				'column_width'     => $column_width,
				'column_index'     => $i,
				'collapse_spacing' => ( ! empty( $collapse_column_spacing ) ) ? 'collapse' : '',
			);
			?>

			<div <?php mesh_block_class( $block->ID, $block_class_args ); ?> <?php mesh_section_background( $block->ID ); ?> <?php echo esc_html( mesh_get_column_attributes( $block->ID ) ); ?>>
				<?php if ( ! empty( $block->post_title ) && 'no column title' !== strtolower( $block->post_title ) ) : ?>
					<h3 class="entry-subtitle"><?php echo esc_html( $block->post_title ); ?></h3>
				<?php endif; ?>
				<?php echo apply_filters( 'the_content', $block->post_content ); ?>
			</div>
		<?php
			$i++;
		endforeach;
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
?>
