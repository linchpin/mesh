![Mesh Banner Image](https://linchpin.agency/wp-content/uploads/2016/05/mesh-banner-github.jpg)
# Mesh - Multiple Content Sections

A WordPress page builder, simplified. Get the most flexibility to display content by adding multiple content sections within Pages, Posts, or Custom Post Types.

## What is Mesh - Multiple Content Sections?

Mesh is the easiest way to add additional content to your Page, Post, or Custom Post Type in a responsive grid system. Adding a Mesh Section creates a new row on your page below the default WordPress content. Each Section can be divided into 1 to 4 Columns, providing Visual/Text editors for each Column. Give your Sections and Columns titles, drag to rearrange, add background images to Columns or an entire Section, then Publish your Section or save it as a Draft until your content is just right.

Efficient and unobtrusive, Mesh was designed to simply extend the functionality of the normal page editor within WordPress to provide the flexibility of building pages in a responsive grid without adding code or editing page templates.

#### Responsive Out-of-the-Box

Mesh is currently built off of [Foundation](http://foundation.zurb.com)'s grid but will soon have support for Bootstrap and custom frameworks as well. All grid styles can also be easily disabled.

#### Familiar and Easy to Use

Our goal is to stay as close to the core usability of WordPress as possible. Mesh content editing is recognizable and simple to use. As you create new Sections for your content you will be presented with an interface similar to that utilized on default Pages and Posts.

#### Extensible and Plays Well with Others

We have added a few hooks to extend the functionality of Mesh and will continue to expand these over time. For a full list of currently available hooks and filters check out the FAQ.


## FAQs

#### Can I use Mesh on other post types?

Any post type that is publicly available within your WordPress install can be enabled to support Mesh. Under _**Settings**_ > _**Mesh**_ you can see all post types available to you. Only Pages are Mesh enabled by default.

#### Can I add my own controls?

We’re working on the ability to tie in extra controls to Columns and Sections.

#### Adding a function to your page template(s)

```php
<?php
if ( function_exists( 'mesh_display_sections' ) ) {
    mesh_display_sections();
}
?>
 ```
 
#### Using add_filter to append content to "the_content"

```php
<?php
add_action( 'the_content', 'add_multiple_content_sections' );
function add_multiple_content_sections( $the_content ) {
    $the_content .= mesh_get_sections();
    return $the_content;
} ?>
```

#### Available Filters

* `apply_filters( 'mesh_content_css', get_stylesheet_directory_uri() . '/css/admin-editor.css' , 'editor_path' ),`
* `apply_filters( 'mesh_section_templates', $section_templates );`
* `apply_filters( 'mesh_tabs', $tabs );`
* `apply_filters( 'mesh_css_mode', $css_mode );` Allow filtering of available css_mode options
* `apply_filters( 'mesh_allowed_html', array_merge_recursive( $post_allowed, $mesh_allowed );` Filter allowed HTML within MCS
* `apply_filters( 'mesh_admin_pointers-' . $screen_id, array() );`

##### Filters added 1.1
* `apply_filters( 'mesh_templates_per_page', 50 );`
* `apply_filters( 'mesh_loop_end', $section_html_content );` 

### Recognition

* We're utilizing the [PHP Markdown Lib](https://github.com/michelf/php-markdown) by Michel Fortin @michelf
* Found some help along the way from regarding [draggable editors](https://github.com/alleyinteractive/wordpress-fieldmanager/blob/master/js/richtext.js#L58-L95) by @danielbachhuber
