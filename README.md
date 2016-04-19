# Mesh for WordPress

Adds the ability to create multiple areas for content within pages, posts and custom post types.

## Description

Adds the ability to create multiple areas for content within pages, posts and custom post types within WordPress.

## Goals

### Responsive out of the box.

Mesh provides a basic responsive grid system build similarly to [Foundation](http://foundation.zurb.com) though you can easily disable styles if you need to.

Mesh is also compatible with another useful WordPress plugin [Toggle wpautop](https://wordpress.org/plugins/toggle-wpautop/) by [Linchpin](https://linchpin.agency)

## Recognizable and easy to use.

The UI/UX goals of Mesh is to stay as close to the core usability of the WordPress dashboard and only veer from that when absolutely necessary.

It should be easy to use and quickly recognizable to the user that this is still the dashboard they are used to.

By default Mesh is only enabled for *Pages*. Please visit Settings -> Mesh to enable it for more sections.

### Extensibility

We have started to add hooks to extend the functionality of Mesh. This list will grow over time.

## Adding a function to your page template(s)

```php
<?php
if ( function_exists( 'mesh_display_sections' ) ) {
    mesh_display_sections();
}
?>
 ```
 
## Using add_filter to append content to "the_content"

```php
<?php
add_action( 'the_content', 'add_multiple_content_sections' );
function add_multiple_content_sections( $the_content ) {
    $the_content .= get_mesh_sections();
    return $the_content;
} ?>
```

## FAQs

#### Can I use Mesh on other post types?

Any post type that is publicly available within your WordPress install can enable Mesh support. Simply visit Settings -> Mesh to enable it.  Only *Pages* have Mesh enabled by default.

#### Can I add my own controls to columns/blocks?

We're working on the ability to tie in extra controls to blocks and sections through filters

### Available Filters

* `apply_filters( 'mesh_content_css', get_stylesheet_directory_uri() . '/css/admin-editor.css' , 'editor_path' ),`
* `apply_filters( 'mesh_section_templates', $section_templates );`
* `apply_filters( 'mesh_tabs', $tabs );`
* `apply_filters( 'mesh_css_mode', $css_mode );` Allow filtering of available css_mode options
* `apply_filters( 'mesh_allowed_html', array_merge_recursive( $post_allowed, $mesh_allowed );` Filter allowed HTML within MCS
* `apply_filters( 'mesh_admin_pointers-' . $screen_id, array() );`

### Recognition

* We're utilizing the [PHP Markdown Lib](https://github.com/michelf/php-markdown) by Michel Fortin @michelf
* Found some help along the way from regarding [draggable editors](https://github.com/alleyinteractive/wordpress-fieldmanager/blob/master/js/richtext.js#L58-L95) by @danielbachhuber
