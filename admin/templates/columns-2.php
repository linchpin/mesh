<?php
/**
 * Dual Editor template
 *
 * @package MultipleContentSection
 * @subpackage AdminTemplates
 * @since 1.2.0
 */
?>

<div class="mcs-left columns-6">
	<?php
	wp_editor( $section->post_content, 'mcs-section-editor-' . $section->ID, array(
		'textarea_name' => 'mcs-sections[' . $section->ID . '][post_content]',
		'teeny' => true,
	) );
	?>
</div>

<div class="mcs-right columns-6">
	<?php
	wp_editor( $section->post_content, 'mcs-section-editor-' . $section->ID . '-support', array(
		'textarea_name' => 'mcs-sections[' . $section->ID . '][post_content_support]',
		'teeny' => true,
	) );
	?>
</div>