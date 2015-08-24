<?php
/**
 * Template used to display warnings when the block count is higher than the available areas within a template
 *
 * @since 1.3.5
 * @package MultipleContentSections
 * @subpackage AdminTemplates
 */

?>

<?php if ( count( $blocks ) > Multiple_Content_Sections::$template_data[ $selected_template ]['blocks'] ) : ?>
	<div id="mcs-description" class="description notice notice-info is-dismissible below-h2">
		<p>
			<?php esc_html_e( 'Your sections template selection does not have enough spots to display all of your blocks', 'linchpin-mcs' ); ?>
			<br/>
			<?php esc_html_e( 'Don\'t worry! None of your content is not lost', 'linchpin-mcs' ); ?>
		</p>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
	</div>
	<p>Unused or hidden blocks :
		<?php
		$i = Multiple_Content_Sections::$template_data[ $selected_template ]['blocks'];

		while ( $i <= count( $blocks ) ) {
			esc_html_e( $blocks[ $i ]->ID );
			$i++;
		}
		?>
	</p>
	<?php
endif;
