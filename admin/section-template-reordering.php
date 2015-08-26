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
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
</div>
<?php endif; ?>