<?php
/**
 * Template used to display warnings when the block count is higher than the available areas within a template
 *
 * @since 1.3.5
 * @package MultipleContentSections
 * @subpackage AdminTemplates
 */

?>
<?php if ( ! empty( $selected_template ) && count( $blocks ) > $templates[ $selected_template ]['blocks'] ) : ?>

	<?php if ( empty( $mcs_notifications['moreblocks'] ) ) : ?>
		<div id="mcs-warnings-<?php esc_attr_e( $section->ID ); ?>" class="description notice notice-info is-dismissible below-h2" data-type="moreblocks">
			<p>
				<?php esc_html_e( 'The number of columns selected is causing some content to be hidden.', 'linchpin-mcs' ); ?>
				<br/>
				<?php esc_html_e( 'Increase the column number to access that content.', 'linchpin-mcs' ); ?>
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
