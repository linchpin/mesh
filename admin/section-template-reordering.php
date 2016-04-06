<?php
/**
 * Template used to display helpful block reorder messaging.
 *
 * Template sizing should only be done if we have more than 1 block but less than 4
 *
 * @since 1.3.5
 * @package MultipleContentSections
 * @subpackage AdminTemplates
 */

// If the template doesn't have any blocks make sure it has 1.
if ( ! $section_blocks = (int) $templates[ $selected_template ]['blocks'] ) {
	$section_blocks = 1;
}

if ( (int) $section_blocks > 1 ) : ?>
	<?php if ( empty( $mcs_notifications['reorder'] ) ) : ?>
		<div class="reordering notice notice-warning is-dismissible below-h2" data-type="reorder">
			<p><?php esc_html_e( 'Reorder your content blocks by dragging and dropping.', 'linchpin-mcs' ); ?></p>
		</div>
	<?php endif; ?>
<?php endif;
