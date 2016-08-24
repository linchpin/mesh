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

?>
<?php if ( ! empty( $selected_template ) && count( $blocks ) > $templates[ $selected_template ]['blocks'] ) : ?>

	<?php if ( empty( $mesh_notifications['moreblocks'] ) ) : ?>
		<div id="mesh-warnings-<?php esc_attr_e( $section->ID ); ?>" class="description notice notice-info is-dismissible below-h2" data-type="moreblocks">
			<p>
				<?php esc_html_e( 'The number of columns selected is causing some content to be hidden.', 'mesh' ); ?>
				<br/>
				<?php esc_html_e( 'Increase the column number to access that content.', 'mesh' ); ?>
			</p>
		</div>
	<?php endif; ?>
	<?php /*
	<p>Unused or hidden blocks :
		<?php
		$i = $templates[ $selected_template ]['blocks'];

		while ( $i <= count( $blocks ) ) {
			if ( ! empty( $blocks[ $i ] ) ) {
				esc_html_e( $blocks[ $i ]->ID . ',' );
			}

			$i++;
		}
		?>
	</p>
	*/ ?>
	<?php
endif;
