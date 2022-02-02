=== Mesh - Page Builder ===
Contributors: linchpin_agency, aware, maxinacube, desrosj, nateallen, ebeltram, lulu5588, fischfood
Tags: page builder, template builder, layout builder, responsive, landing page builder, site builder, foundation, bootstrap, linchpin
Requires at least: 4.0
Tested up to: 5.9
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A page builder, simplified. Get the most flexibility to display content by adding multiple content sections within Pages, Posts, or Custom Post Types.

== Description ==

This plugin is deprecated and no longer actively maintained excluding any security updates. The Block Editor/Gutenberg can handle many aspects of row and column content that this plugin provides.

If you still would like to utilize this plugin you need to additionally install the classic-editor plugin as mesh *is not* compatible with the block editor

Page, Post, or Custom Post Type in a responsive grid system. Adding a Mesh Section creates a new row on your page below the default WordPress content. Each Section can be divided into 1 to 4 Columns, providing Visual/Text editors for each Column. Give your Sections and Columns titles, drag to rearrange, add background images to Columns or an entire Section, then Publish your Section or save it as a Draft until your content is just right.

Efficient and unobtrusive, Mesh was designed to simply extend the functionality of the normal page editor within WordPress to provide the flexibility of building pages in a responsive grid without adding code or editing page templates.

= Responsive Out-of-the-Box =
Mesh is currently built off of [Foundation](http://foundation.zurb.com/)'s grid but will soon have support for Bootstrap and custom frameworks as well. All grid styles can also be easily disabled.

= Familiar and Easy to Use =
Our goal is to stay as close to the core usability of WordPress as possible. Mesh Content editing is recognizable and simple to use. As you create new Sections for your content you will be presented with an interface similar to that utilized on default Pages and Posts.

= Extensible and Plays Well with Others =
We have added a few hooks to extend the functionality of Mesh and will continue to expand these over time. For a full list of currently available hooks and filters check out the FAQ.

= Features =
* Add unlimited Content Sections on a page by page basis
* Easy to Choose and manage Column layout for each Section as you build your pages
* Use the Visual or Text editor to create your content in each Column
* Add media, shortcodes into any Column just like "The Editor"
* All content is searchable in SEO and within your website.
* Work with [Jetpack's Related Posts](https://jetpack.com/support/related-posts/) feature!)
* Drag to adjust Column widths
* Drag and drop reorder Columns within a Section (horizontally) and Sections within your Page or Post (vertically)
* Add an offset before a Column (not available in 4 column layouts)
* Add custom CSS classes to an entire Section or a specific Column (This works great in tandem with [Jetpack's Custom CSS](https://jetpack.com/support/custom-css/) feature!)
* Click to apply built-in styling options like Collapse Padding, Push/Pull, and Equalize
* Set a background image for a Section or a Column
* Save Sections in Draft status or Publish
* Utilizes page editing functionality you are already familiar with in the WordPress admin
* Visual cues in the editors help represent how your content will be displayed on the frontend (ie: column widths, gutters, offsets)
* Enable Mesh Sections on Pages, Posts, and/or any custom post types within the settings (Pages enabled by default)
* Mesh section content will be analyized by Yoast Page scoring
* Works with popular page/post duplication plugins

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

= Available Hooks =

Learn more about available hooks (filters and actions) by visiting the mesh our [knowledgebase](https://meshplugin.com/knowledgebase_category/hooks-filters/).

== Screenshots ==

1. Mesh editor interface.
2. Mesh front end display.
3. Mesh templates and welcome.

== Changelog ==

= 1.4.1 =

### Fixes

- Fixed integrations directory not being called based on capitalization rules

= 1.4.0 =

### Updated

- Update the build process from grunt to gulp
- Implemented deployments to .org from github

### Fixes

- Fixed an issue with ajax calls to Mesh Templates

= 1.2.5.6 =

### Fixes

- Had to temporarily disable WordPress SEO / Yoast Content Analysis from Mesh due to an undocumented change on Yoast's side. We will enable this feature again soon

= 1.2.5.5 =

### Fixes

- Die in utilities that shouldn't be there

= 1.2.5.4 =

### Fixes

- Fixed over zealous sanitization
- Fixed missing integration folder on wordpress.org
- Fixed an issue with not allowing you to reset section/column titles
- Added better support for WordPress Export / Import tools

= 1.2.5.3 =

- Minor fix for column centering using default mesh styles

= 1.2.5.2 =

- Fixed an issue with Column Background images not saving properly

= 1.2.5.1 =

- Hotfix for title display

= 1.2.5 =

This is a pretty big release
Please read about our changes here https://meshplugin.com/release-notes/1-2-5/

### Fixes

- Quality of life fixes for UI.
- Background images can now be removed on blocks!
- Tons of refactoring for section and block option customization
- Fixed offset selector being shown when it shouldn't be

### Added
- You can now center blocks within single column sections ( any width smaller than 12 columns )
- You can now set the width of a single column section to any width you want (higher than 3 columns by default)
- Mesh is even more compatible with Foundation Flex Grid, XY Grid, CSS Grid and even more custom grid systems.
- Added new "Foundation 6.4.X" option within Foundation Menu of Mesh Settings
- Added new setting to select Float, Flex and XY grids in Foundation Menu of Mesh Settings
- Developers will find section and block controls have even more flexibility (documentation to come soon)
- Better css when hovering a draggable columns.
- Moved and Refactored a ton of methods to be more organized (utilities.php)
- Added utility methods for outputting CSS Classes and other attributes for sections, rows and columns
- Added more descriptions to options within Mesh Settings
- Added Mesh_Input class to handle building all input markup (dramatically cutting down code duplications between blocks and sections)
- Added a nag message for reviews. Please help!
- Added a ton of new filters
    - You can now define your own max columns using the `mesh_max_columns` filter
    - Filter html attributes for sections, rows and columns, `mesh_element_attributes`
    - Filter html attributes for sections `mesh_section_attributes`
    - Filter html attributes for rows `mesh_row_attributes`
    - Filter html attributes for columns `mesh_column_attributes`
    - Filter css classes for rows `mesh_row_classes`
    - Filter css classes for title `mesh_section_title_css_classes`
    - Filter css classes for columns `mesh_column_classes`
    - Filter available responsive grid systems `mesh_responsive_grid_systems`
    - Filter available to override available foundation versions `mesh_foundation_version`
    - Filter to define the max columns of a given row `mesh_max_columns` Default remains 12

### Updated
* Tons of CSS refactoring to make the UI cleaner
* Media and TinyMCE menus of each section now collapse when viewing smaller blocks for a better overall experience.
* TinyMCE default settings are now shared between blocks built with php and javascript.
* Dramatically cut down the code needed within mesh php templates (Developers may want to review any custom templates on a staging environment)
* Admin area now utilizes Mesh_Responsive grid class more extensively
* Minor code formatting for code climate / WPCS
* Minor security enhancements to escape admin related content on output
* Minor JavaScript optimizations
* Column resizing is now more responsive. Sections no longer redraw on every "slide", instead only redraw after a change occurs.

= 1.2.4 =
* Added more filters and action hooks for developers
* Added field for custom Section ID, Section ID defaults to mesh-section-{post_id}
* Added preliminary support for Gutenberg (AKA No conflicts)
* Added field for custom Section ID
* Added new uninstall process that will clear out Mesh Templates, Sections and Terms
* Fixed a few undefined indexes
* Fixed a few conflicts with gutenberg (This is not full compatiblity with Gutenberg)
* Fixed an issue where Mesh Templates could potentially lost the ability to add Mesh Sections
* Updated Foundation 6.X data attribute tag support
* Updated CSS slightly.

= 1.2.3 =
* Fixed a javascript issue with FireFox
* Fixed an issue with Equalizer options being over sanitized.

= 1.2.2 =
* Fixed bug causing 'Show Title' checkbox to not work correctly
* Minor Code Climate configuration changes.
* Minor formatting changes for markdown linting.

= 1.2.1 =
* Include form elements in Mesh allowed HTML
* Include data-interchange in Mesh allowed HTML on section elements
* Fix for undefined indexes
* Added hooks to Mesh templates

= 1.2 =
* Remove trailing whitespace from row class
* Remove checks for equalizer in the 1 column template
* Fixed a bug where reordering would stop that section from working properly until refresh.
* Fixed a bug where collapsed sections could not be toggled open after a new section was added
* Fixed a bug when excluding Mesh template related taxonomies from the generated sitemap
* Fixed a bug where section and block background images were displayed before "update" / "publish"
* Controls within Sections and Columns/Blocks are now extendable for developers.
* More security hardening for potential XSS and CSRF.
* Fixed a bug where trashing unused blocks was more aggressive than it should be. Simma down nah.
* Fixed a pesky bug what would delete your content if you changed column count before saving.
* A bunch of little things under the hood you probably wont notice
* First time users will now have an improved onboarding process.
* Existing users will now be presented with a notification to view *"What's new"*
* Added support for Yoast SEO page analysis.
* Added support for scripts within urls within TinyMCE.
* Added support for duplicating sections of a post using "Duplicate Post" Plugin
* Added support for duplicating sections of a post using "Post Duplicator" Plugin
* First implementation of block caching layer.
* WordPress Coding Coding Standards
* Improved build process.
* Improved code analysis process within codeclimate

= 1.1.7 =
* Confirmed 4.8 compatibility
* Fix for issue within "content" being replaced when it shouldn't be
* Fix for duplicated sections not applying the proper date.

= 1.1.6 =
* Fixed undefined index `foundation_version`.
* Fix bug for `.row` max-width being set to `rem-calc(1200)`
* Fix issue within visual editors within blocks. The html was being saved instead of the raw data. wpautop filter should still be applied if available.
* Hot fix bug with Foundation interchange conflict
* Setup Code Climate and Code Climate test coverage reporting.
* Fixed PHP warnings when retrieving `mesh_post_types` when it is not yet set.
* Include mesh.js.map in Grunt build
* Introduce a `CONTRIBUTING.md` file.
* Added a `.travis.yml` file to automate our unit tests.
* Added `addtextdomain` task to the Grunt configuration.
* Added `JSON` files to Code Climate grading.
* Added `node_modules` folder to the `.gitignore`.
* Update unit test install scripts.
* Changed `esc_attr_e()` and `esc_html_()` calls to `echo esc_attr()` and
* `echo esc_html()` when containing a variable.
* Remove `makepot` task from the Grunt configuration.
* Exclude the `Michelf` library from Code Climate scanning.
* Remove `languages` folder.
* Replace Michelf library with Parsedown

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
