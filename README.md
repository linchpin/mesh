# Mesh for WordPress

Adds the ability to create multiple areas for content within pages, posts and custom post types.

## Description

Adds the ability to create multiple areas for content within pages, posts and custom post types within WordPress.

By default the plugin is only enabled for Pages but you can visit Settings -> Mesh to enable it for more sections.

Mesh is also compatible with another useful WordPress plugin [Toggle wpautop](https://wordpress.org/plugins/toggle-wpautop/) by [Linchpin](https://linchpin.agency)

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

## Available Filters

* `apply_filters( 'content_css', get_stylesheet_directory_uri() . '/css/admin-editor.css' , 'editor_path' ),`
* `apply_filters( 'mcs_section_templates', $section_templates );`