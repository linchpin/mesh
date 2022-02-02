# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.4.1] - 2022-02-02
### Fixed
- Fixed integrations directory not being called based on capitalization rules

## [1.4.0] - 2021-08-18
### Updated
- Update the build process from grunt to gulp
- Implemented deployments to .org from github

### Fixed
- Fixed an issue with ajax calls to Mesh Templates

## [1.2.5.6]
### Fixed
- Had to temporarily disable WordPress SEO / Yoast Content Analysis from Mesh due to an undocumented change on Yoast's side. We will enable this feature again soon

## [1.2.5.5]
### Fixed
- Die in utilities that shouldn't be there

## [1.2.5.4]
### Fixed
- Fixed over zealous sanitization
- Fixed missing integration folder on wordpress.org
- Fixed an issue with not allowing you to reset section/column titles
- Added better support for WordPress Export / Import tools

## [1.2.5.3]
- Minor fix for column centering using default mesh styles

## [1.2.5.2]
- Fixed an issue with Column Background images not saving properly

## [1.2.5.1]
- Hotfix for title display

## [1.2.5]
### Updated
- Please read about our changes here https://meshplugin.com/release-notes/1-2-5/

### Fixed
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

## [1.2.4]
### Added
- Added more filters and action hooks for developers
- Added field for custom Section ID, Section ID defaults to mesh-section-{post_id}
- Added preliminary support for Gutenberg (AKA No conflicts)
- Added field for custom Section ID
- Added new uninstall process that will clear out Mesh Templates, Sections and Terms
- Fixed a few undefined indexes
- Fixed a few conflicts with gutenberg (This is not full compatiblity with Gutenberg)
- Fixed an issue where Mesh Templates could potentially lost the ability to add Mesh Sections
- Updated Foundation 6.X data attribute tag support
- Updated CSS slightly.

## [1.2.3]
### Fixed
- Fixed a javascript issue with FireFox
- Fixed an issue with Equalizer options being over sanitized.

## [1.2.2]
### Fixed
- Fixed bug causing 'Show Title' checkbox to not work correctly
- Minor Code Climate configuration changes.
- Minor formatting changes for markdown linting.

## [1.2.1]
### Fixed
- Include form elements in Mesh allowed HTML
- Include data-interchange in Mesh allowed HTML on section elements
- Fix for undefined indexes
- Added hooks to Mesh templates

## [1.2]
### Removed
-Remove trailing whitespace from row class
-Remove checks for equalizer in the 1 column template

### Fixed
- Fixed a bug where reordering would stop that section from working properly until refresh.
- Fixed a bug where collapsed sections could not be toggled open after a new section was added
- Fixed a bug when excluding Mesh template related taxonomies from the generated sitemap
- Fixed a bug where section and block background images were displayed before "update" / "publish"
- Controls within Sections and Columns/Blocks are now extendable for developers.
- More security hardening for potential XSS and CSRF.
- Fixed a bug where trashing unused blocks was more aggressive than it should be. Simma down nah.
- Fixed a pesky bug what would delete your content if you changed column count before saving.
- A bunch of little things under the hood you probably wont notice
- First time users will now have an improved onboarding process.
- Existing users will now be presented with a notification to view *"What's new"*
- Added support for Yoast SEO page analysis.
- Added support for scripts within urls within TinyMCE.
- Added support for duplicating sections of a post using "Duplicate Post" Plugin
- Added support for duplicating sections of a post using "Post Duplicator" Plugin
- First implementation of block caching layer.
- WordPress Coding Coding Standards
- Improved build process.
- Improved code analysis process within codeclimate
