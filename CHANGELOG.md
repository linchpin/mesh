##Change Log For Multiple Content Sections

## [0.4.1] - 2015-09-12

### Added
- Controls are displayed in the footer of the sections
- Separate CHANGELOG.md file
- Doc Blocks within JavaScript for some methods were add
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
- Initial Release