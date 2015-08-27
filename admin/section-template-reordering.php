<?php
/**
 * Template used to display helpful block reorder messaging
 *
 * @since 1.3.5
 * @package MultipleContentSections
 * @subpackage AdminTemplates
 */

?>
<?php if ( (int) Multiple_Content_Sections::$template_data[ $selected_template ]['blocks'] > 1 ) : ?>
	<div id="mcs-description" class="description notice notice-warning is-dismissible below-h2">
		<p>
			<?php esc_html_e( 'Reorder your content blocks by dragging and dropping.', 'linchpin-mcs' ); ?>
		</p>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'linchpin-mcs' ); ?></span></button>
	</div>
	<?php
		$block_columns = get_post_meta( $blocks[0]->ID, '_mcs_column_width', true );

		if ( ! $block_columns ) {
			$block_columns = 6;
		}
	?>
	<div class="wp-slider column-slider" data-mcs-columns="<?php esc_attr_e( $block_columns ); ?>"><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span></div>
<?php endif; ?>