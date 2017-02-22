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

// if ( ! has_term( 'reference', 'mesh_template_types', $post ) ) : ?>
<ul class="inline-block-list space-left">
	<li><span class="spinner mesh-reorder-spinner"></span></li>
	<li><a href="#" class="mesh-section-reorder plain-link"><?php esc_html_e( 'Reorder Sections', 'mesh' ); ?></a></li>
	<li><a href="#" class="mesh-section-expand plain-link"><?php esc_html_e( 'Expand All', 'mesh' ); ?></a></li>
	<li><a href="#" class="mesh-section-collapse plain-link"><?php esc_html_e( 'Collapse All', 'mesh' ); ?></a></li>
	<li><a href="#" class="button primary mesh-section-add dashicons-before dashicons-plus"><span class="spinner"></span><?php esc_html_e( 'Add Section', 'mesh' ); ?></a></li>
</ul>
<?php

/*
else : ?>
<div>
    <p>
	    <?php

        $template_terms = get_the_terms( $post, 'mesh_template_usage' );

        if ( ! empty( $template_terms ) ) {
	        $template = new WP_Query( array(
	                'name'           => $template_terms[0]->slug,
                    'post_type'      => 'mesh_template',
                    'no_found_rows'  => true,
                    'posts_per_page' => 1,
                )
	        );

	        if( $template->have_posts() ) {
		        echo sprintf( wp_kses( __( 'This is a reference template. You must edit the the reference template:<a href="%s">%s</a>.', 'mesh' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( get_edit_post_link( $template->posts[0]->ID ) ), esc_html( $template->posts[0]->post_title ) );
	        }
        }
        ?>
    </p>
    <ul class="inline-block-list space-left">
        <li><span class="spinner mesh-reorder-spinner"></span></li>
        <li><a href="#" class="button primary mesh-template-change-type dashicons-before dashicons-plus"><?php esc_html_e( 'Convert to Starter Template', 'mesh' ); ?></a></li>
        <li><a href="#" class="button primary mesh-template-remove dashicons-before dashicons-plus"><?php esc_html_e( 'Remove Template', 'mesh' ); ?></a></li>
    </ul>
</div>
<?php

endif;
*/
