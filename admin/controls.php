<?php
/**
 * Container Controls for editors
 *
 * @since 1.4.1
 *
 * @package MultipleContentSection
 * @subpackage Admin
 */
?>

<a href="#" class="page-title-action mcs-section-reorder<?php if ( empty( $content_sections ) ) : ?> disabled<?php endif; ?>"><?php esc_html_e( 'Reorder Sections', 'lincpin-mcs' ); ?></a>
<a href="#" class="page-title-action mcs-section-expand<?php if ( empty( $content_sections ) ) : ?> disabled<?php endif; ?>"><?php esc_html_e( 'Expand All', 'lincpin-mcs' ); ?></a>
<a href="#" class="page-title-action mcs-section-add dashicons-before dashicons-plus"><?php esc_html_e( 'Add Section', 'lincpin-mcs' ); ?></a>
