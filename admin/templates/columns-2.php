<?php
/**
 * 2 Editor template
 *
 * @package MultipleContentSection
 * @subpackage AdminTemplates
 * @since 1.2.0
 */

?>

<div class="mcs-left columns-6">
	<?php
	wp_editor( $section->post_content, 'mcs-section-editor-' . $section_ID, array(
		'textarea_name' => 'mcs-sections[' . $section_ID . '][post_content]',
	) );
	?>
</div>

<div class="mcs-right columns-6">
	<?php
	wp_editor( $section->post_content, 'mcs-section-editor-' . $section_ID . '-support', array(
		'textarea_name' => 'mcs-sections[' . $section_ID . '][post_content_support]',
	) );
	?>
</div>
