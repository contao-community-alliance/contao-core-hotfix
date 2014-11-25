Contao Open Source CMS changelog
================================

Version 3.3.6 (2014-10-31)
--------------------------

### Fixed
Fixed a potential directory traversal vulnerability.

### Fixed
Fixed a severe XSS vulnerability. In this context, the insert tag flags
`base64_encode` and `base64_decode` have been removed.

### Fixed
Handle nested insert tags in strip_insert_tags().

### Fixed
Always pass a DC object in the `toggleVisibility` callback (see #7314).

### Fixed
Correctly render the "read more" and article navigation links (see #7300).

### Fixed
Fix the markup of the form submit button (see #7396).

### Fixed
Do not generally remove insert tags from page titles (see #7198).

### Fixed
Consider the `useSSL` flag of the root page when generating URLs (see #7390).

### Fixed
Correctly create the template object in `BaseTemplate::insert()` (see #7366).

### Updated
Updated TinyMCE to version 4.1.6 and added the "lists" plugin (see #7349).

### Fixed
Fixed the FAQ sorting in the back end (see #7362).

### Fixed
Added the `Widget::__isset()` method (see #7290).

### Fixed
Correctly handle dynamic parent tables in the `DC_Table` driver (see #7335).

### Fixed
Correctly shortend HTML strings in `String::substrHtml()` (see #7311).

### Updated
Updated MooTools to version 1.5.1 (see #7267).

### Fixed
Updated swipe.js to version 2.0.1 (see #7307).

### Fixed
Use an `.invisible` class which plays nicely with screen readers (see #7372).

### Fixed
Handle disabled modules in the module loader (see #7380).

### Fixed
Fixed the "link_target" insert tag.

### Fixed
Correctly mark CAPTCHA fields as mandatory (see #7283).

### Updated
Updated the ACE editor to version 1.1.6 (see #7278).

### Fixed
Fix the `Database::list_fields()` method (see #7277).

### Fixed
Correctly assign "col_first" and "col_last" in the image gallery (see #7250).

### Fixed
Set the correct path to TCPDF in `system/config/tcpdf.php` (see #7264).


Version 3.3.5 (2014-08-27)
--------------------------

### Fixed
Do not output an empty `label` tag (see #7249).

### Fixed
Allow floating point numbers in "number" input fields (see #7257).

### Fixed
Do not adjust the start time of past events (see #7121).

### Fixed
Reset the image margins if it exceeds the maximum image size (see #7245).

### Fixed
Reset `$blnPreventSaving` when a model is cloned (see #7243).

### Fixed
Do not reload after storing `CURRENT_ID` in the session (see #7240).

### Fixed
Correctly validate the page number of the versions menu (see #7235).

### Fixed
Handle underscores in the Google+ vanity name (see #7241).

### Fixed
Correctly handle the `rem` unit when importing style sheets (see #7220).

### Fixed
Fix two issues with the extension repository theme.


Version 3.3.4 (2014-07-29)
--------------------------

### Fixed
Restore permission to delete root pages for admin users (see #7135).

### Fixed
Pass the file IDs instead of their UUIDs to the file picker (see #7139).

### Fixed
Correctly handle double quotes in comments (see #7102).

### Fixed
Ignore hidden files when building the internal cache (see #7098).

### Fixed
Correctly pass the insert ID of the undo record (see #6234).

### Fixed
Update the vendor libraries (fixes various issues).


Version 3.3.3 (2014-06-18)
--------------------------

### Fixed
Convert insert tags before assigning the page title to the template (see #7097).

### Fixed
Correctly render images in TinyMCE in the newsletter module (see #7089).


Version 3.3.2 (2014-06-04)
--------------------------

### Fixed
Add the media query to the style sheets in debug mode (see #7070).

### Fixed
Disable the debug mode in the extension creator (see #7068).

### Fixed
Convert image source insert tags in the back end preview (see #7065).

### Fixed
Render all root nodes in the page and file picker (see #6844).

### Fixed
Add the "scssphp-compass" library to support Compass functions.

### Fixed
Support adding multiple TinyMCE instances to the same page (see #7061).


Version 3.3.1 (2014-05-30)
--------------------------

### Fixed
Grant access to static files inside the `vendor` folder.

### Fixed
Do not make the `FormRadioButton` options an array (see #7060).

### Fixed
Support adding ACE and TinyMCE in subpalettes (see #7056).

### Fixed
Only use the DropZone uploader where Ajax uploads can be processed (see #7046).

### Fixed
Make the viewport field 255 characters long (see #7050).

### Fixed
Restore the "submit_container" class in the `FormSubmit` widget (see #7055).

### Fixed
Correctly generate the CSS classes of the `FormSelectMenu` widget (see #7045).

### Fixed
Use a more precise UUID detection in the `FilesModel` class (see #7054).

### Fixed
Use `pack()` instead of `hex2bin()` to be compatible with PHP 5.3 (see #7010).


Version 3.3.0 (2014-05-26)
--------------------------

### Fixed
Correctly show the comments in the "comments" element (see #7040).

### Fixed
Correctly store the file selection in "edit multiple" mode (see #7028).

### Update
Update Compass to version 0.12.6.

### Fixed
Improve the UUID validation to prevent false positives (see #7010).

### Fixed
Correctly sort by date in the listing module (see #5609).

### Fixed
Fix the back link in the "single article" view (see #6955).

### Fixed
Never cache insert tags if the output is not used on the website (see #7018).

### Fixed
Strip forbidden HTML tags in the markdown content element (see #7021).

### Fixed
Prevent parallel execution of the new command line scripts.

### Fixed
Also set the `sql_mode` in the MySQLi driver (see #6996).

### Fixed
Purge the script cache if a style sheet is edited (see #7005).

### Fixed
Disable the maintenance screen if a back end user is logged in (see #7009).

### Fixed
Correctly set the textarea value in the template (see #6995).

### Fixed
Make sure the security questions gets always generated (see #6990).

### Fixed
Do not use `date_default_timezone_get()` in the configuration file (see #6989).

### Fixed
Correctly generate absolute URIs in `Controller::generateFrontendUrl()`.

### Fixed
Fix the link button padding (`a.tl_submit`).


Version 3.3.0-RC2 (2014-05-09)
------------------------------

### Update
Update TinyMCE to version 4.0.26.

### Fixed
Correctly set and explain the page title field (see #6953).

### Fixed
Correctly show the template sources (see #6875).

### Fixed
Support input tags without a "type" attribute in the CSS framwork (see #6902).

### Fixed
Import the `tinymce.css` style sheet in TinyMCE (see #6970).

### Fixed
Catch Swift exceptions when sending form data via e-mail (see #6941).

### Fixed
Try all locale variations when loading TinyMCE (see #6952).

### Fixed
Correctly overwrite the article template (see #6938).

### Fixed
Correctly wrap long labels in the tree view (see #6954).

### Fixed
Correctly add the WAI-ARIA attributes (see #6217).


Version 3.3.0-RC1 (2014-05-02)
------------------------------

### New
Allow to override the default form field template (see #4547).

### Changed
Only pass the current form data to the "processFormData" hook (see #6705).

### New
Add a DropZone-based file uploader (see #6064).

### New
Add permissions to import and export themes (see #5835).

### Improved
Make the fields of the meta wizard configurable in the DCA (see #4327).

### Improved
Also show the preview image when editing multiple files (see #6643).

### Improved
Show the file location below the "name" field in the file manager (see #6503).

### Improved
Add some basic WAI-ARIA attributes to the navigation menu (see #6217).

### Improved
Automatically convert file paths in TinyMCE into insert tags (see #5965).

### Changed
Move the custom layout section markup into template files (see #6531).

### Improved
Move the form field markup into the template files (see #6834).

### New
Add template inheritance and template insertion (see #6508 and #6934).

### New
Add a flexible back end theme.

### Update
Update colorbox to version 1.5.8.

### Update
Update mediaelement.js to version 2.14.2.

### Update
Update jQuery to version 1.11.0 and jQuery UI to version 1.10.4.

### Update
Update the color picker to version 1.4.

### Changed
Use the "bootstrap" theme for the date picker (see #6692).

### Update
Update the back end date picker to version 2.2.0.

### Update
Update ACE to version 1.1.3.

### Improved
Use the widget attributes instead of the DCA in the picker widgets (see #6881).

### Improved
Enable the interlace bit when creating image thumbnails (see #6529).

### Improved
Assign articles to layout sections with an article module only (see #6094).

### New
Add the "parseDate" hook (see #4260).

### New
Make the title tag configurable in the page layout (see #6783).

### New
Add helper methods to generate markup depending on the output type:

 - `Template::generateStyleTag()`
 - `Template::generateInlineStyle()`
 - `Template::generateScriptTag()`
 - `Template::generateInlineScript()`
 - `Template::generateFeedTag()`

### New
Add the "customizeSearch" hook (see #5223).

### New
Add a button to generate article aliases via "edit multiple" (see #6628).

### New
Add a pagination menu at the listing bottom (see #6377).

### Fixed
Only override element and module templates in the front end (see #6878).

### Changed
Use the `html5shiv-printshiv.js` script in the front end (see #6293).

### New
Added the "getLanguages" hook (see #6545).

### Changed
Render the table summary as `<caption>` in HTML5 (see #6295).

### Changed
Also convert paths without delimiter in `Combiner::fixPaths()` (see #6417).

### New
Add the "colorizeLogEntries" hook (see #5803).

### New
Added an "oncut_callback" and "oncopy_callback" to `DC_Folder` (see #6814).

### Improved
Support optional dependencies in the module loader (see #6835).

### New
Mark the beginning and end of each template in debug mode (see #6841).

### New
Added the insert tag flags "urlencode" and "rawurlencode" (see #6859).

### Improved
Add files and folders to the database in details view (see #6880).


Version 3.3.0-beta1 (2014-04-11)
--------------------------------

### New
Add version control for editable files.

### New
Add a configurable "viewport" field to the page layout (see #6251).

### New
Split the layout builder CSS code into a static and a responsive style sheet,
so the responsive behaviour can be disabled (see #6251).

### New
Added more static convenience methods to the `Config` class:

 - `set()`: temporarily set a configuration value
 - `presist()`: permanently store a configuration value
 - `remove()`: permanently remove a configuration value

A static `get()` method has been available already.

### Update
Update TinyMCE to version 4.0.20 (see #1495).

### New
Handle `.scss` and `.less` files in the `Combiner`. This also allows to add SCSS
or LESS files as external style sheets to the page layout.

### New
Allow to override the default module or content element template (see #4547).

### Improved
Create a new version if a member changes their data in the front end.

### Improved
Shorten the file paths in the `FileTree` widget (see #6488).

### Improved
Hide the details page link in the listing module if the details page condition
is not met (see #6332).

### New
Make the file system synchronization available on the command line (see #6815).

### New
Make the `Automator` methods available on the command line (see #6815).

### Changed
Moved the asset version constants to `$GLOBALS['TL_ASSETS']` (see #5759).

### New
Added a "preview front end as member" button (see #6546).

### Changed
Hide forward pages if they point to unpublished target pages (see #6376).

### Changed
Only enable the debug mode in the FE if there is a BE user (see #6450).

### Changed
Do not require MooTools or jQuery for the command scheduler (see #6755).

### Changed
Use the new Google Universal Analytics code snippet (see #6103).

### Improved
Add `$parent` as fourth parameter to the "compileDefinition" hook (see #6697).

### Update
Update TCPDF to version 6.0.062.

### Changed
Enable the maintanance mode by default (see #6758).

### New
Added a markdown content element (see #6052).

### Changed
Merged the "newsarchive" and "newsarchive_empty" templates (see #6647).

### Changed
Make the following functions public static (see #6351):

 - `Controller::getArticle`
 - `Controller::getContentElement`
 - `Controller::getForm`
 - `Controller::getFrontendModule`

### New
Support editing the front end preview page via the "url" parameter (see #6471).

### Improved
Do not combine .js and .css files when running in debug mode (see #6450).

### New
Added a `DcaLoader` class to decouple the DCA loading process (see #5441). DCAs
can now be loaded anywhere using `Controller::loadDataContainer()`.

### Changed
Convert slashes to hyphens in the `standardize()` function (see #6396).

### Improved
Add a `getModel()` method to modules, elements and hybrids (see #6492).

### Improved
Support the "HAVING" command in the `Model\QueryBuilder` class (see #6446).

### Changed
Use class constants for `BackendUser::isAllowed()`.
