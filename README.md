# Mesh for WordPress

Adds the ability to create multiple areas for content within pages, posts and custom post types.

## Description

Adds the ability to create multiple areas for content within pages, posts and custom post types within WordPress.

## Goals

### Responsive out of the box.

Mesh provides a basic responsive grid system build similarly to [Foundation](http://foundation.zurb.com) though you can easily disable styles if you need to.

Mesh is also compatible with another useful WordPress plugin [Toggle wpautop](https://wordpress.org/plugins/toggle-wpautop/) by [Linchpin](https://linchpin.agency)

## Recognizable and easy to use.

The UI/UX goals of Mesh it to try to stay as close to the core usability of the WordPress dashboard and only veer from that when absolutely necessary.

It should be easy to use and quickly recognizable to the user that this is still the dashboard they are used to.

By default Mesh is only enabled for *Pages*. Please visit Settings -> Mesh to enable it for more sections.

### Extensibility

We have started to add hooks to extend the functionality of Mesh. This list will grow over time.

## Adding a function to your page template(s)

`<?php if ( function_exists( 'mcs_display_sections' ) ) : ?>
 	<?php mcs_display_sections(); ?>
 <?php endif; ?>`
 
## Using add_filter to append content to "the_content"

```<?php
add_action('the_content', 'add_multiple_content_sections');
function add_multiple_content_sections( $the_content ) {
    $the_content .= get_mcs_sections();
    return $the_content;
} ?>```

## FAQs

= Can I use Mesh on other post types? =

Any post type that is publicly available within your WordPress install can enable Mesh support. Simply visit Settings -> Mesh to enable it.  Only *Pages* have Mesh enabled by default.

= Can I add my own controls to blocks? =

We're working on the ability to tie in extra controls to blocks and sections.

### Available Filters

* `apply_filters( 'content_css', get_stylesheet_directory_uri() . '/css/admin-editor.css' , 'editor_path' ),`
* `apply_filters( 'mcs_section_templates', $section_templates );`
* `apply_filters( 'mesh_tabs', $tabs );`
* `apply_filters( 'css_mode', $css_mode );` Allow filtering of available css_mode options
		
```$css_mode = array(
    array( 'label' => __( 'Use Mesh CSS', 'linchpin-mcs' ), 'value' => '' ),
    array( 'label' => __( 'Disable Mesh CSS', 'linchpin-mcs' ), 'value' => 0 ),
    array( 'label' => __( 'Use Foundation w/ my theme', 'linchpin-mcs' ), 'value' => 1 ),
    array( 'label' => __( 'Use Bootstrap (coming soon)', 'linchpin-mcs' ), 'value' => 2 ),
);```
   		`