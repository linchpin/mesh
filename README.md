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

##Change Log

###0.4.0
* Updates to frontend templates for offset and custom css classes
* Added abillty to disable notification messages

###0.3.9
* Added some style cleanup of the column resizer
* Fixed a zindex issue when resizing columns

###0.3.8
* Cleaned up Post Type labels used when exporting content using the WordPress export tools (now shows "Section")

###0.3.7
* Cleaned up notifications
* Cleaned up reordering
* Added smoother/real-time column resizing

###0.3.6
* Added CSS classes to Blocks
* Added Background Image to blocks
* Fixed up resizing of 3 column layouts
* Added utility to build out section background images

###0.3.5

#### Significant Release. Probably breaks backwards compatibility with internal releases.
* Added the ability to reorder blocks using drag and drop
* Better redrawing of editable areas after reordering (of drawers and sections) is complete
* Added ajax order saving on reordering blocks
* Added the ability to define custom css classes per section
* Additional Security hardening prior to public release
* Added better localization support on **MOST** strings
* Added a bunch of default templates to choose from

###0.3
* Added notifications on saving, reordering
* Added css of admin elements
* Added better structure for notifications

###0.2
* Added README.md
* Added ability to have multiple editors within a *section* based on template
* Added GruntFile
* Minor code cleanup to adhere to WordPress coding standards
* Refactor code for template selection

###0.1.1
* Added feature to store data from multiple content sections within "the_content" of the parent post. This allows content within Multiple Content Sections to show up within the WordPress search results.
* Added ability to select templates
* Added ability to upload media to sections

###0.1.0
* Initial Release