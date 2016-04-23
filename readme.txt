=== Mesh ===
Contributors: linchpin_agency, desrosj, aware, maxinacube
Tags: linchpin, sections, content, page builder, page builder plugin, design, wysiwyg, home page builder, template builder, layout builder, landing page builder, website builder, site builder, drag and drop builder, editor, page layout, visual editor
Requires at least: 4.0
Tested up to: 4.5
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds the ability to create multiple areas for content within Pages, Posts and Custom Post Yypes.

== Description ==

The UI/UX inspiration of Mesh is to stay as close to the core usability of the WordPress dashboard and only veer from that when absolutely necessary.

Mesh provides a basic responsive grid system build similarly to [Foundation](http://foundation.zurb.com) though you can easily disable styles if you need to.

By default Mesh is only enabled for Pages. Please visit Settings -> Mesh to enable it for more sections.

=== Features ===

*   Simple to use interface no modules or bloat
*   Mobile Friendly, responsive grid system (Foundation, w/ More to come).
*   Easily extensible through actions and filters.
*   Supports Posts, Pages, and Custom Post Types.
*   Customize with your own css and background images.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress

== Frequently Asked Questions ==

= Can I use Mesh on other post types? =

Any post type that is publicly available within your WordPress install can enable Mesh support. Simply visit Settings -> Mesh to enable it.  Only *Pages* have Mesh enabled by default.

= Can I add my own controls to blocks? =

We're working on the ability to tie in extra controls to blocks and sections.

== Changelog ==

= 1.0.0 =
Release date: Apr 21st, 2015

Initial Release:
* To see pre public release change log visit [mesh on github](https://github.com/linchpinagency/mesh/)

* Change the name of Multiple Content Sections to Mesh for public release.
* Finalized Name change for public release.
* Removed unneeded realtime ajax calls that resulted in unwanted publishing of content
* Massive overhaul of admin CSS for public release.
* Completely reworked interface for public release for more WordPress core consistency and ease of use (More to come)
* Allowing the ability to toggle kitchen sink items. @todo Not displaying yet
* Admins can now control some plugin settings. Settings -> Mesh
* Admins can disable or enable CSS as needed
* Admins can now enable Mesh on individual post types.
* Admin area is now much more modular

Bug Fixes:

* TinyMCE now works better. Using a fix seen here [Field Manager](https://github.com/alleyinteractive/wordpress-fieldmanager/blob/master/js/richtext.js#L58-L95)
* TinyMCE options are now consistent when displaying new and existing block editors

Known Issues:

* Some non blocker minor styling issues.
* Minor display issues on smaller screens.
* Ajax `$_POST` is sending more data than needed.