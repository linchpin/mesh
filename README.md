#Linchpin Multiple Content Sections

Adds the ability to define multiple content areas within pages, posts and custom post types.

##General Usage

###Adding a function to your page template(s)

`<?php if ( function_exists( 'mcs_display_sections' ) ) : ?>
 	<?php mcs_display_sections(); ?>
 <?php endif; ?>`
 
###Using add_filter to append content to "the_content"

`
<?php
add_action('the_content', 'add_multiple_content_sections');
function add_multiple_content_sections( $the_content ) {
    $the_content .= get_mcs_sections();
    return $the_content;
} ?>
`