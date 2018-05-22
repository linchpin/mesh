<?php
/**
 * Template used to display warnings when the block count is higher than the available areas within a template
 *
 * @since      0.3.5
 *
 * @package    Mesh
 * @subpackage AdminTemplates
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

$total_blocks = mesh_get_section_blocks( $section->ID, array( 'publish', 'draft' ) );
?>
<?php
if ( ! empty( $selected_template ) && count( $total_blocks ) > $templates[ $selected_template ]['blocks'] ) :
?>
	<?php if ( empty( $mesh_notifications['moreblocks'] ) ) : ?>
		<div id="mesh-warnings-<?php echo esc_attr( $section->ID ); ?>" class="description notice notice-info below-h2 mesh-row collapse" data-type="moreblocks">
			<div class="mesh-columns-8 columns">
				<?php esc_html_e( 'The number of columns selected is causing some content to be hidden.', 'mesh' ); ?>
				<br/>
				<?php esc_html_e( 'Increase the column number to access that content. Or...', 'mesh' ); ?>
			</div>
			<div class="mesh-columns-4 columns">
				<a href="#" class="right button secondary mesh-trash-extra-blocks"><span class="dashicons dashicons-trash"></span><?php esc_html_e( 'Trash Hidden Columns', 'mesh' ); ?></a>
			</div>
		</div>
	<?php endif; ?>
	<?php
endif;
