<?php
/**
 * Container Controls for editors
 *
 * @since 0.4.1
 *
 * @package    Mesh
 * @subpackage Admin
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}
?>
<?php
global $post;
/**
 * If this is not a reference template. Show our controls.
 *
 * Reference templates can not add/remove or reorder sections
 */
?>
<ul class="inline-block-list space-left">
	<li><span class="spinner mesh-reorder-spinner"></span></li>
    <?php do_action( 'mesh_controls_before' ); ?>
	<li><a href="#" class="mesh-section-reorder plain-link"><?php esc_html_e( 'Reorder Sections', 'mesh' ); ?></a></li>
	<li><a href="#" class="mesh-section-expand plain-link"><?php esc_html_e( 'Expand All', 'mesh' ); ?></a></li>
	<li><a href="#" class="mesh-section-collapse plain-link"><?php esc_html_e( 'Collapse All', 'mesh' ); ?></a></li>
	<li><a href="#" class="button primary mesh-section-add dashicons-before dashicons-plus"><span class="spinner"></span><?php esc_html_e( 'Add Section', 'mesh' ); ?></a></li>
	<?php do_action( 'mesh_controls_after' ); ?>
</ul>
