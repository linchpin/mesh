<?php
/**
 * Default editor template
 *
 * @package MultipleContentSection
 * @subpackage AdminTemplates
 * @since 1.2.0
 */
?>

<?php
wp_editor( $section->post_content, 'mcs-section-editor-' . $section->ID, array(
	'textarea_name' => 'mcs-sections[' . $section->ID . '][post_content]',
) );
?>
