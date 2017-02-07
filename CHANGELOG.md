##Change Log For Mesh

## [1.1.5] - 2017-2-6

### Fixed
 - Fixed equalize options should not show if the section is only 1 column wide.
 - Fixed some minor typos.
 - Fixed minor display issue that occurred when removing all Mesh sections on a post.

### Added
 - Ability to filter `mesh_tiny_mce_before_init` to allow even more extended option filtering
 - Default support for interchange using Mesh even if your theme isn't built on Foundation
 - Actions mesh_section_add_before_misc_actions and mesh_section_add_misc_actions for more customization.
 - Ability to preview sections that are not published yet.

### Updated
 - Mesh templates default to "starter" mesh_template_type upon creation

## [1.1.4] - 2016-12-21

### Fixed
 - Selected/uploaded background images were not displaying within admin until refresh.
 - Fixed Mesh Template order consistenty when closing.
 - Fixed block resizing was broken in some instances.
 - Fixed Mesh titles displaying outside of their container if the title is too long 
 
### Added
 - Window will now scroll to the newest block when adding a new section.

## [1.1.3] - 2016-11-17

### Fixed
 - Interchange on section and block background images
 - Fixed minor typo in the previous changelog
 
### Added
 - Exclude Mesh template taxonomies from being added to the WordPress SEO sitemap.xml
 - Equalizer minimum breakpoint support for Foundation 6
 - The ability to select which version of Foundation your theme is using (Defaults to Foundation 5)
 - `mesh-background` custom image size (1920 x 1080) by default.
 - Filters to define what images sizes will be used by interchange.

## [1.1.2] - 2016-11-07

### Fixed
 - Fixed compatibility issue with PHP 5.4 (Thanks @missmuttly anf @tecbrat)

## [1.1.1] - 2016-10-19

### Fixed
 - Some minor copy / typo adjustments

### Added
 - Equalizer minimum breakpoint support for Foundation 6

### Updated
 - Changelog should have proper information now

## [1.1] - 2016-10-17

### Added
 - You can now create reusable templates.
 - Templates are excluded from Yoast SEO admin shenanigans by default.
 - Preliminary remove Mesh Settings on Uninstall.
 - You can now filter how many mesh_templates `mesh_templates_per_page` are queried if you have a lot of templates (More than 50)
 - Better version tracking and upgrade process.
 - You can now filter the output of `mesh_loop_end`. An example would be to stop the default output of mesh_display_sections.
 - Better "in progress and busy" state of page building.
 - New Welcome message on Mesh Templates Post List to help guide users
 - Initial implementation for documentation generation.
 
### Fixed
 - Typo in Mesh settings text field utility method.
 - Offset now displays properly within Post Edit screen on page load.
 - When setting an offset to 7,8 or 9 on single column (12) visual did not match what was being stored in post_meta.
 - Minor security improvements.
 - Now running Mesh admin sections through wp_kses with a custom set of whitelisted elements and attributes.
 - After deleting all sections within a post you had to refresh the pages before you could get your controls back.
 - New sections could not toggle post box collapse with out a page refresh.
 - Ordering of sections was being lost when updating a post.
 - Fixed When going from more to less columns you can now trash unused columns. (Thanks for the find @kelter)
 - Some formatting issues in the readme.
 - If you added a section then immediately tried to resize a JS error would occur to do aria checks.
 
### Updated
 - Some localization strings needed sprucing up (old MCS references).
 - Some style updates (notifications, tighted up soe visuals for consistency).
 
## [1.0.5] - 2016-08-08

### Fixed
- README.md and CHANGELOG.md not pulling into settings page. Thanks @nate-allen [mesh-25]
- Column "More" Options UI is very tight and overlaps. Thanks @nate-allen [mesh-18]

## [1.0.4] - 2016-08-04

### Fixed
- Javascript error was thrown if the user had any columns within text view when saving. [mesh-21]
- Templates were missing the ability for the first column to have an offset. [mesh-5]
- Post Type enabling wasn't working properly. [mesh-19]

## [1.0.3] - 2016-05-15

### Added
- Updated build process for easier deployment to wordpress.org

### Fixed
- Minor code formatting updates
- Fixed publish / update button display issue [mesh-11]

## [1.0.2] - 2016-05-13

### Fixed
- Missing Domain Path: /languages

## [1.0.1] - 2016-05-13

### Updated
- Readme has more information
- Setting up localization

### Fixed
- Checks for Foundation

## [1.0.0] - 2016-04-21

### Updated
- Change the name of Multiple Content Sections to Mesh for public release.
- Finalized Name change for public release.
- Removed unneeded realtime ajax calls that resulted in unwanted publishing of content
- Massive overhaul of admin CSS for public release.
- Completely reworked interface for public release for more WordPress core consistency and ease of use (More to come)

### Fixed
- TinyMCE now works better. Using a fix seen here https://github.com/alleyinteractive/wordpress-fieldmanager/blob/master/js/richtext.js#L58-L95
- TinyMCE options are now consistent when displaying new and existing block editors

### Added
- Allowing the ability to toggle kitchen sink items. @todo Not displaying yet
- Admins can now control some plugin settings. Settings -> Mesh
- Admins can disable or enable CSS as needed
- Admins can now enable Mesh on individual post types.
- Admin area is now much more modular

### Known Issues
- Some non blocker minor styling issues.
- Minor display issues on smaller screens.
- Ajax `$_POST` is sending more data than needed.

## [0.4.5] - 2015-10-27

### Fixed
- Blocks had issues selecting a background once the blocks code was separated out.

## [0.4.4] - 2015-10-3

### Fixed
- Temporary fix for toggling styles between html/text view of blocks

### Updated
- Separated section controls into a different template for more flexible expansion later on.

## [0.4.3.1] - 2015-09-21

### Fixed
- Made sure that a block always matches the parent section when being created (publish|draft) etc

## [0.4.3] - 2015-09-21

### Added
- Can now collapse column spacing using an option

## [0.4.2.1] - 2015-09-21

### Fixed
- Made sure that blocks can not have the same `post_name`

## [0.4.2] - 2015-09-21

### Added
- Checking if a page is private before displaying it to the end user.
- Created logic to display the title of a section either above the blocks or within a specific block

### Fixed
- JavaScript references we're broken after JavaScript was separated.

## [0.4.1] - 2015-09-12

### Added
- Controls are displayed in the footer of the sections
- Separate CHANGELOG.md file
- Doc Blocks within JavaScript for some methods were added
- Offset can now be defined per block/column not just per section

### Updated
- Separated Controls into it's own reusable template
- Separated MultipleContentSections into it's own class
- Gruntfile.js now joins JavaScript files and minifies
- Separated block related Javascript into it's own file

### Fixed
- Default Template `<div>` was not being closed correctly

## [0.4.0] - 2015-09-10

### Added
- Users can now disable notification messages

### Updated
- Frontend templates support column offset and custom css classes

## [0.3.9]

### Updated
- Style cleanup of the column resizer

### Fixed
- zindex issue when resizing columns

## [0.3.8]

### Fixed
- Cleaned up Post Type labels used when exporting content using the WordPress export tools (now shows "Section")

## [0.3.7]

### Updated
- Cleaned up notifications
- Cleaned up reordering
- Added smoother/real-time column resizing

## [0.3.6]

### Added
- CSS classes to Blocks
- Background Image to blocks
- Utility method to build out section background images

### Fixed
- 3 column layouts can now be resized

## [0.3.5]

#### Significant Release. Probably breaks backwards compatibility with internal releases.

### Added
- Ability to reorder blocks using drag and drop
- Block order saves using Ajax
- Ability to define custom css classes per section
- Better localization support on **MOST** strings
- Users now have a bunch of default templates to choose from

### Updated
- Better redrawing of editable areas after reordering (of drawers and sections) is complete
- Additional Security hardening prior to public release

## [0.3.0]

### Added
- Notifications display on saving, reordering
- Added css of admin elements
- Added better structure for notifications

## [0.2.0]

### Added
- Added README.md
- Added ability to have multiple editors within a *section* based on template
- Added GruntFile

### Fixed
- Minor code cleanup to adhere to WordPress coding standards
- Refactor code for template selection

## [0.1.1]

### Added
- Added feature to store data from multiple content sections within "the_content" of the parent post. This allows content within Multiple Content Sections to show up within the WordPress search results.
- Added ability to select templates
- Added ability to upload media to sections

## [0.1.0]
- Initial Internal Release