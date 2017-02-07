=== Mesh - Multiple Content Sections ===
Contributors: linchpin_agency, aware, maxinacube, desrosj, nateallen, ebeltram, lulu5588, fischfood
Tags: linchpin, sections, content, page builder, page builder plugin, design, wysiwyg, home page builder, template builder, layout builder, responsive, landing page builder, website builder, site builder, drag and drop builder, editor, page layout, visual editor, foundation, bootstrap
Requires at least: 4.0
Tested up to: 4.7.0
Stable tag: 1.1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A page builder, simplified. Get the most flexibility to display content by adding multiple content sections within Pages, Posts, or Custom Post Types.

== Description ==
Mesh is the easiest way to add additional content to your Page, Post, or Custom Post Type in a responsive grid system. Adding a Mesh Section creates a new row on your page below the default WordPress content. Each Section can be divided into 1 to 4 Columns, providing Visual/Text editors for each Column. Give your Sections and Columns titles, drag to rearrange, add background images to Columns or an entire Section, then Publish your Section or save it as a Draft until your content is just right.

Efficient and unobtrusive, Mesh was designed to simply extend the functionality of the normal page editor within WordPress to provide the flexibility of building pages in a responsive grid without adding code or editing page templates.

= Responsive Out-of-the-Box =
Mesh is currently built off of [Foundation](http://foundation.zurb.com/)'s grid but will soon have support for Bootstrap and custom frameworks as well. All grid styles can also be easily disabled.

= Familiar and Easy to Use =
Our goal is to stay as close to the core usability of WordPress as possible. Mesh Content editing is recognizable and simple to use. As you create new Sections for your content you will be presented with an interface similar to that utilized on default Pages and Posts.

= Extensible and Plays Well with Others =
We have added a few hooks to extend the functionality of Mesh and will continue to expand these over time. For a full list of currently available hooks and filters check out the FAQ.

= Features =
* Add unlimited Content Sections on a page by page basis
* Choose a 1, 2, 3, or 4 Column layout for each Section
* Use the Visual or Text editor to create your content in each Column
* Add media or forms into any Column
* Section content is searchable
* Sections work with [Jetpack's Related Posts](https://jetpack.com/support/related-posts/) feature!)
* Drag to adjust Column widths
* Drag to reorder Columns within a Section (horizontally) and Sections within your Page or Post (vertically)
* Add an offset before a Column (not available in 4 column layouts)
* Add custom CSS classes to an entire Section or a specific Column (This works great in tandem with [Jetpack's Custom CSS](https://jetpack.com/support/custom-css/) feature!)
* Click to apply built-in styling options like Collapse Padding, Push/Pull, and Equalize
* Set a background image for a Section or a Column
* Save Sections in Draft status or Publish
* Utilizes page editing functionality you are already familiar with in the WordPress admin
* Visual cues in the editors help represent how your content will be displayed on the frontend (ie: column widths, gutters, offsets)
* Enable Mesh Sections on Pages, Posts, and/or any custom post types within the settings (Pages enabled by default)

= Our clients love Mesh, so we thought everyone else might too! =
We first developed Mesh as a tool for our clients to use so they could better self manage their content; in-turn saving them money on support hours. They love it so much we have continued to improve upon it, add new features, and are now proud to present it to the WordPress Community at large!

Let us know how you like Mesh and what we can do to make it even better!
Leave a review here or start a conversation on [GitHub](https://github.com/linchpinagency/mesh).

== Installation ==

1. Click **Add New** under Plugins in your WordPress install.
1. Search for **Mesh** in the directory.
1. Click **Install Now** and *activate* the plugin.

Or

1. Download the latest plugin version above.
1. Click **Add New** under Plugins in your WordPress install.
1. Click **Upload Plugin** and select the zip file containing the plugin


== Frequently Asked Questions ==

= Can I use Mesh on other post types? =
Any post type that is publicly available within your WordPress install can be enabled to support Mesh. Under **Settings** > **Mesh** you can see all post types available to you. Only Pages are Mesh enabled by default.

= Can I add my own controls? =
We're working on the ability to tie in extra controls to Columns and Sections.

= Adding a function to your page template(s) =
`
<?php
if ( function_exists( 'mesh_display_sections' ) ) {
    mesh_display_sections();
}
?>
 `

= Using add_filter to append content to "the_content" =
`
<?php
add_action( 'the_content', 'add_multiple_content_sections' );
function add_multiple_content_sections( $the_content ) {
    $the_content .= mesh_get_sections();
    return $the_content;
} ?>
`

= Using add_filter to set change default size for background images =
`
<?php
add_filter( 'mesh_default_bg_size', 'hatch_mesh_default_bg_size' );

function hatch_mesh_default_bg_size( $size ) {
    // default $size = 'mesh-background' will fall back to 'full'.

	return 'large';
}

?>
`

= Available Filters =
* `add_filter( 'mesh_content_css', get_stylesheet_directory_uri() . '/css/admin-editor.css' , 'editor_path' ),`
* `add_filter( 'mesh_section_templates', $section_templates );`
* `add_filter( 'mesh_tabs', $tabs );`
* `add_filter( 'mesh_css_mode', $css_mode );` Allow filtering of available css_mode options
* `add_filter( 'mesh_allowed_html', array_merge_recursive( $post_allowed, $mesh_allowed );` Filter allowed HTML within MCS
* `add_filter( 'mesh_admin_pointers-' . $screen_id, array() );`

* `add_filter( 'mesh_default_bg_size', $size );`
* `add_filter( 'mesh_large_bg_size', $size );`
* `add_filter( 'mesh_medium_bg_size', $size );`
* `add_filter( 'mesh_xlarge_bg_size', $size );`

* `add_filter( 'mesh_tiny_mce_before_init', $init_options );`
* `add_filter( 'mesh_tiny_mce_options`, $mesh_tiny_mce_options );`

= Available Actions =
* `add_action( 'mesh_section_add_before_misc_actions' )`
* `add_action( 'mesh_section_add_misc_actions_before' )`
* `add_action( 'mesh_section_add_misc_actions_after' )`


== Screenshots ==

1. Mesh editor interface.
2. Mesh front end display.
3. Mesh templates and welcome.

== Changelog ==

= 1.1.5 =
* Fixed equalize options should not show if the section is only 1 column wide.
* Fixed some minor typos.
* Fixed minor display issue that occurred when removing all Mesh sections on a post.
* Added ability to filter `mesh_tiny_mce_before_init` to allow even more extended option filtering
* Added default support for interchange using Mesh even if your theme isn't built on Foundation
* Added actions mesh_section_add_before_misc_actions and mesh_section_add_misc_actions for more customization.
* Added ability to preview sections that are not published yet.
* Updated templates to default to "starter" mesh_template_type taxonomy term

= 1.1.4 =
* Fixed selected/upload background images were not displaying within admin until refresh.
* Fixed Mesh Template order consistently when closing.
* Fixed Block resizing was broken in some instances.
* Fixed Mesh titles displaying outside of their container if the title is too long
* Added Window will now scroll to the newest block when adding a new section.

= 1.1.3 =
* Added exclusion for Mesh Template taxonomies when using WordPress / Yoast SEO
* Added the ability to select which version of Foundation your theme is using (Defaults to Foundation 5)
* Added mesh-background custom image size (1920 x 1080) by default.
* Added filters to define what images sizes will be used by interchange.
* Fixed interchange on section and block background images

= 1.1.2 =
* Fixed compatability issue with PHP 5.4 (Thanks @missmuttly anf @tecbrat)

= 1.1.1 =
* Fixed Some minor copy / typo adjustments
* Fixed Some css / icon changes.
* Fixed Welcome message not displaying properly. [#36]
* Added Equalizer minimum breakpoint support for Foundation 6
* Updated Changelog should have proper information now (Thanks for the find @kelter)
* Updated Ajax to not save column width in realtime. [#17]

= 1.1 =
* Changed You can now create reusable templates.
* Changed Templates are excluded from Yoast SEO admin shenanigans by default.
* Changed Preliminary remove Mesh Settings on Uninstall.
* Changed You can now filter how many mesh_templates `mesh_templates_per_page` are queried if you have a lot of templates (More than 50)
* Changed Better version tracking and upgrade process.
* Changed You can now filter the output of `mesh_loop_end`. An example would be to stop the default output of mesh_display_sections.
* Changed Better "in progress and busy" state of page building.
* Changed New Welcome message on Mesh Templates Post List to help guide users
* Changed Initial implementation for documentation generation.
* Fixed Typo in Mesh settings text field utility method.
* Fixed Offset now displays properly within Post Edit screen on page load.
* Fixed When setting an offset to 7,8 or 9 on single column (12) visual did not match what was being stored in post_meta.
* Fixed Minor security improvements.
* Fixed Now running Mesh admin sections through wp_kses with a custom set of whitelisted elements and attributes.
* Fixed After deleting all sections within a post you had to refresh the pages before you could get your controls back.
* Fixed New sections could not toggle post box collapse with out a page refresh.
* Fixed Ordering of sections was being lost when updating a post.
* Fixed Some formatting issues in the readme.
* Fixed When going from more to less columns you can now trash unused columns. (Thanks for the find @kelter)
* Fixed If you added a section then immediately tried to resize a JS error would occur to do aria checks.
* Updated Some localization strings needed sprucing up (old MCS references).
* Updated Some style updates (notifications, tighted up soe visuals for consistency).

= 1.0.4 =
* Fixed Javascript error was thrown if the user had any columns within text view when saving. [mesh-21]
* Fixed Templates were missing the ability for the first column to have an offset. [mesh-5]
* Fixed Post Type enabling wasn't working properly. [mesh-19]

= 1.0.3 =
* Updated build process for easier deployment to wordpress.org
* Some minor code formatting cleanup
* Fixed publish / update button display issue [mesh-11]

= 1.0.2 =
* Added localization support

= 1.0.1 =
* Added clarity to readme file.
* Better checks for pre-existing Foundation elements.

= 1.0 =
* Hello World!