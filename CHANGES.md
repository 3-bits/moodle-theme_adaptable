Changes in version 1.2.5 (2016)
---------------------------------------
New features:
- Avatar beside user name in the navbar



Fixed issues:







Changes in version 1.2.4.2 (2016102300)
---------------------------------------
Warning: This is the last minor release compatible with moodle 2.9

Fixed issues:
- #527: Reset course and login blocks width 
- #526: Left padding in main-region 
- #521: Breadcrumbs do not display last item 
- #517: Student pictures narrowed in some listings
- #516: Notice: Undefined property: stdClass: 
- #515: Slider type 1 display caption background when empty 

Also includes some improvements in the mobile header but still working to fix some issues



Changes in version 1.2.4.1 (2016100300)
---------------------------------------
Warning: This is the last minor release compatible with moodle 2.9

Fixed issues:
- #512: Header social bug
- #511: Persisting horizontal scrollbar
- #509: Site title is not displayed without logo image


Changes in version 1.2.4 (2016092200)
-------------------------------------
Warning: This is the last minor release compatible with moodle 2.9

New features:
- Updated Google Fonts list
- Setting: Text selection colours
- Setting: Emoticons size 
- Setting: Piwik Analytics - Thanks to COMETE (Paris Ouest University)
- Setting: Navbar Menu hover colour

Fixed issues:
- #508: Integrate Analytics in one page 
- #507: Emoticon size 
- #504: Remove hardcoded strings in settings 
- #501: Fail to embed a video into marketing blocks 
- #500: Cannot get beyond New settings - Header on fresh install of moodle 3.1 with Adaptable in the theme directory 
- #499: User menu error in user ID in "My Blog" item 
- #497: Categories list on Front Page 
- #494: Definition of from35to100percent broken 
- #493: Inheriting from Adaptable: Hardcoded settings 
- #492: Textbox for email digest type in forums is too narrow 
- #491: Extraneous </div> in frontpage 
- #490: Heading / logo alignment 
- #489: Default titling does nothing without image 
- #487: Full login field label doesn't fit into its box and is cut from the end 
- #486: Logo is not responsive 
- #485: Option to have Search bar and Social Icons? 
- #484: Username placeholder uses not localized string for hint 
- #482: Untidy layout of the login screen 
- #481: Editor can't upload images in some settings sections 
- #480: Disable login button 
- #479: Mess with the front page layout when blocks are on the LEFT side 
- #478: Distorted front page layout before login 
- #476: Block region builder settings doesn't appear 
- #475: Navbar disappears when moving away from first page after login 
- #474: Convert 'course' string to theme string 
- #473: Add logo to default site title 
- #472: Site Title Maximum Width values up to 100% 
- #471: Docking blocks with transparent background 
- #419: Navbar caching "this course" list at site level


Changes in version 1.2.3 (2016052900)
-------------------------------------
- #469: Infobox2 strings missed
- #468: Lower Header blocks text and link Colour: not working
- #467: Override .block settings
- #444: Neither site title nor course title in the header


Changes in version 1.2.2 (2016050900)
-------------------------------------
- #449: 'People' item in 'This course' menu is not displayed in local language 
- #446: Failed to control 'Course Full Name' / 'Course Short Name' setting for the breadcrumb 
- #445: Header height has a 'hidden' limit 
- #444: Neither site title nor course title in the header 
- #441: Multi-lang content not displayed in the marketing blocks 
- #440: Preferences menu item enable by default 
- #429: Teacher Style front Page Tiles 


Changes in version 1.2.1 (2016031800)
-------------------------------------

- Fix #409 Bug rendering sub menus on mobile
- Fix #416 Bug rendering My Courses with cohort option selected
- Fix #411 Multiple tools menus added to menu
- Fix #406 User Preference setting doesn't apply
- Fix #414 Correct URL for My Feedback Report
- Fix #415 Analytics not rendering script tag properly
- Fix #413 Add setting for messages "badge" in header
- Fix #407 Fix time calculation on navbar cache ttl
- Fix #404 Alerts not displaying if set to display to logged in users
- Fix #398 Second infobox has not full screen
- Fix #278 Navbar sticky at the top
- Fix #394 Set Enable Home redirect=0 to On by default
- Fix #397 Replace <b> with <strong>
- Fix #395 Set site title to truncate and add width setting making consistent with course title

Changes in version 1.2.0 (2016030700)
-------------------------------------

- Fix #393 Fix HTML Validation errors on closing menu div
- Fix #392 Remove unwanted </i> tag from top menus
- Fix #389 Coursebox non closed <a> tag breaks HTML validation
- Fix #390 Header menu font-size increment is too rough
- Fix #375 Prevent Frontpage Block Regions appearing in block settings sitewide
- Fix #354 Grader Report: Icons in webkit overlapping.
- Fix #381 Prevent slider being wrapped in <a tag where no slider URL is given
- Fix #384 Add settings page for course layout
- Fix #184 Topic title header width changes when editing On
- Fix #383 Correct display of section headings in courses
- Fix #382 Improve support for onetopic course format
- Fix #281 Correct width of forum display dropdown select
- Fix #287 Remove duplicate check on logo
- Fix #376 Multiple changes to improve site titles and header settings.
- Fix #377 Add options for display of cohort enrolments in list of my courses
- Fix #375 Prevent Frontpage Block Regions appearing in block settings sitewide
- Fix #373 Help link target not being applied
- Fix #340 Correct / improve block style settings
- Fix #372 Improve styling of saved comments in marking guide
- Fix #367 Change logout icon to fa-sign-out making consistent with core Moodle.
- Fix #298 Use larger profile image on front page to improve view of forum posts.
- Fix #368 Adding class for frontpage blocks which can bow be targeted independently of the side blocks
- Fix #366 Missed region-frnt-market-a string when editing an HTML block
- Fix #365 Add dropdown to Font Weight
- Fix #343 Link hover colour in blocks
- Fix #358 Set enable custom block region on front page to off by default

Changes in version 1.1.3 (2016022601)
-------------------------------------

- Fix #60 Add support for Social Wall courese format
- Fix #363 Add padding setting for main navigation items
- Fix #362 Add caching for main navigation bar
- Fix #355 Add link to font awesome icon list in social settings
- Fix #315 Resolve bugs on theme select

Changes in version 1.1.2 (2016022502)
-------------------------------------

- Fix #345 One full width marketing block not displayed
- Fix #360 Remove es language files
- Fix #361 Make My Course list settings more generic
- Fix #353 Add feature for custom sorting of My Courses
- Fix #349 End slash in the social icon url error
- Fix #348 Remove default facebook social icon
- Fix #353 Add feature for custom sorting of My Courses
- Fix #352 Move hidden courses into sub menu
- Fix #273 PHP errors after install
- Fix #334 Include Font Awesome in the theme
- Fix #336 Remove Global Font Size from settings
- Fix #268 Quiz settings spill out of the main region
- Fix #247 Mail settings spill on blocks
- Fix #331 Remove lecacy contact details style
- Fix #327 Fix show / hide front page marketing blocks

Changes in version 1.1.1 (2016020400)
-------------------------------------

NOTE: After upgrading go to the "Header Navbar" settings and save to update the "enablemysites" field with new values

- Fix #304 Improve css and add setting to control length of text displayed in my courses dropdown
- Fix #326 Title using double line overlap text
- Fix #313 Delete Block Button Color Incorrect
- Fix #317 Wrong ul in flexslider
- Fix #280 Partial fix removing some title attributes from navigation and improving alignment of navbar dropdown menus
- Fix #249 Increaes messages dropdown size to avoid spill, corrected issue with left align and improved spacing
- Fix #320 Remove notifications from messages, this needs to be put back as an option in admin settings
- Fix #318 Catch notices from renderer caused by undefined variables
- Fix #316 Increase font size range for Top Menu & Navigation bar
- Fix #184 Topic title header width changes when editing On
- Fix #285 Slideshow is not working in RTL sites
- Fix #311 Undefined variable: /theme/adaptable/renderers.php on line 747
- Fix #277 There are 2 marketing blocks settings separated from the sub-section
- Fix #304 Reduce length of course titles in my course dropdown list to avoid spill
- Fix #309 Add option to show hidden courses with icons in my course dropdown list
- Fix #308 Fix styling issue in gapselect question type
- Fix #305 Correct question text layout in lesson module
- Fix #306 Correct links to readme file in admin pages
- Fix #268 Fix quiz settings spilling out of container
- Fix #307 Fix feature to use course shortcode in header
- Fix #298 Show larger / sharper profile image in forum posts
- Fix #301 Show larger profile image when clicking to view profile in forum
- Fix #299 Correct default settings for marketing and footer layout builders

Changes in version 1.1.0 (2016012700)
-------------------------------------

- Fix #296 - if not already set define looped settings with a default value
- Fix #295 adding color pickers to sliders
- Fix #291 add renderer, finish layout builder in settings, fix incorrect lang strings, make show / hide use PHP to ...
- Fix #291 Rewrite footer settings adding layout builder
- Fix #284 adding block region builder
