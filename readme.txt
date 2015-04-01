=== Steel ===
Contributors: starverte, mbeall
Tags: bootstrap, carousel, quotes, shortcodes, slides, teams, widgets
Requires at least: 3.9
Tested up to: 4.1
Stable tag: 1.2.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

Core plugin of the Sparks Framework. Works for any theme; but when paired with Flint your WordPress site will be on fire.

== Description ==

Steel adds certain capabilities through several optional modules. We are continuing to add modules, so stay tuned.

= Bootstrap =
Bootstrap 3 is included with Steel, and comes alive through newly added shortcodes!

= Quotes =
Display a random quote (or testimonial) in the sidebar with our random quote widget. We are looking at embedding random quotes within a post or page in our next update.

= Slides =
Create and display media slideshows using Bootstrap’s Carousel plugin.

= Teams =
Create profiles for board of directors, staff members, etc. and display them on a nice summary page.

= Future Modules =
* Aliases - Have multiple "pretty permalinks" point to the same post or page, or create a "pretty permalink" for an offsite link.
* Events - Add an events calendar to your WordPress site
* Podcast - Add a podcast with different channels and different feeds, available to listen online or via iTunes
* Styles - This module will replace the need for shortcodes by adding buttons and styles to TinyMCE.

Suggestions for other modules? Let us know via the Support tab.

Like our plugin? Write us a review!

== Installation ==

1. Upload `steel.zip` to the `/wp-content/plugins/` directory
1. Unzip `steel.zip` to a sub-directory named `steel`
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Check out the options menu for more options

== Frequently Asked Questions ==

Submit your questions to http://sparks.starverte.com/steel and we will try to answer them.

== Changelog ==
= 1.2.3 =
* Fixed fatal error in slides.php

= 1.2.2 =
* Added target attribute to btn shortcode
* Various UI improvements
* Added Google Analytics module
* Upgrade to Bootstrap 3.3.4 and point to MaxCDN's bootstrap files

= 1.2.0 =
* Options UI improvements
* Added grid.css
* Further development of Podcast module (still inactive)
* Upgrade to Bootstrap 3.3.2

= 1.1.7 =
* Clean up code
* Upgrade to Bootstrap 3.2

For more details, check out https://github.com/starverte/steel/pull/126

= 1.1.6 =
* Fixed `run.js` to avoid conflict with new Audio List feature in WordPress 3.9
* Fixed columns shortcode to reflect Bootstrap integration
* Added transitions to Slides module
* New Navigation Menu widget

For more details, check out https://github.com/starverte/steel/pull/125

= 1.1.4 =
* Simplified editor in Slides
* Removed templates and integrated new Flint action hooks
* Upgraded to Bootstrap 3.1.1

= 1.1.3 =
* Caption in "Bar" skin now overlays image instead of appearing beneath the image
* jQuery conflicts fixed

= 1.1.2 =
Extracted carousel and glyphicon styles from bootstrap.css so that carousel works even if Bootstrap module isn't active

= 1.1.1 =
* Added skins for Slides module
* Added ability to turn off Bootstrap module

= 1.1.0 =
* Added numerous shortcodes to implement Bootstrap like [btn] and [glyph] (for complete list and use, check out http://sparks.starverte.com/steel/bootstrap/)
* Added new Slides module (documentation will soon be available via http://sparks.starverte.com/steel/slides/)

For more details, check out https://github.com/starverte/steel/pull/102

= 1.0.1 =
Removed redundant `steel_version` and admin scripts functions

= 1.0.0 =
Updated branding and descriptions.

= 0.8.2 =
Initial release
