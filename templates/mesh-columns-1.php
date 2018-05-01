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

<?php
/**
 * Add the ability to add markup before Mesh section
 */
do_action( 'mesh_section_before' );

$mesh_element_attributes = get_mesh_element_attributes();
$mesh_element_attributes = apply_filters( 'mesh_row_attributes', $mesh_element_attributes );
$mesh_element_attributes = implode( ' ', $mesh_element_attributes );
?>

<section <?php post_class(); ?> <?php mesh_section_background(); ?> <?php echo $mesh_element_attributes; ?>>
	<?php
	/**
	 * Add the ability to add markup before Mesh row
	 */
	do_action( 'mesh_row_before' );
	?>

	<?php
		$title_display = get_post_meta( get_the_ID(), '_mesh_title_display', true );
		$collapse_column_spacing = get_post_meta( get_the_ID(), '_mesh_collapse', true );
	  $custom_row_class = get_post_meta( get_the_ID(), '_mesh_row_class', true );

		$row_class = ( ! empty( $collapse_column_spacing ) ) ? 'row collapse' : 'row';
	  $row_class = ( ! empty( $custom_row_class ) ) ? $row_class . ' ' . $custom_row_class : $row_class;
		
    $title_class = array( 'small-12', 'columns', 'title-row' );
	  $title_class = apply_filters( 'mesh_section_title_css_classes', $title_class );
	  $title_class = implode( ' ', $title_class );
	?>

	<div class="<?php echo esc_attr( $row_class ); ?>">
		<?php if ( ! empty( $title_display ) && 'no block title' !== strtolower( get_the_title() ) ) : ?>
			<div class="<?php echo esc_attr( $title_class ); ?>">
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</div>
		<?php endif; ?>

		<?php do_action( 'mesh_columns_before' ); ?>

		<?php

		$blocks = mesh_get_section_blocks( get_the_ID() );

		if ( ! empty( $blocks ) ) :
			foreach ( $blocks as $block ) :

            $mesh_element_attributes = get_mesh_element_attributes( $block->ID );
            $mesh_element_attributes = apply_filters( 'mesh_column_attributes', $mesh_element_attributes );
            $mesh_element_attributes = implode( ' ', $mesh_element_attributes );
			?>
				<div <?php mesh_block_class( $block->ID ); ?> <?php mesh_section_background( $block->ID ); ?> <?php echo $mesh_element_attributes; ?>>
					<?php if ( ! empty( $block->post_title ) && 'no column title' !== strtolower( $block->post_title ) ) : ?>
						<h3 class="entry-subtitle"><?php echo esc_html( apply_filters( 'the_title', $block->post_title ) ); ?></h3>
					<?php endif; ?>
					<?php
						echo apply_filters( 'the_content', $block->post_content );
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
?>
