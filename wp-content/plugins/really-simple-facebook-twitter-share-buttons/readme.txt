=== Really simple Facebook Twitter share buttons ===
Contributors: whiletrue
Donate link: http://www.whiletrue.it/
Tags: facebook, twitter, facebook share, twitter share, facebook share button, twitter share button, linkedin, google +1, +1, google share, google plus share, pinterest, google buzz, buzz, digg, flattr, stumbleupon, hyves, links, post, page, mail, email, reddit, tipy, tumblr, buffer, pinzout, shortcode, youtube, print, rss, bitcoin, litecoin
Requires at least: 2.9+
Tested up to: 3.8.1
Stable tag: 3.0.2

Puts Facebook, Twitter, LinkedIn, Google "+1", Pinterest, Tumblr and other share buttons of your choice above or below your posts.

== Description ==
This plugin shows Facebook, Twitter, LinkedIn, Google "+1", Pinterest and other popular share buttons above or below your posts.
Easy customization of active buttons and position in the Settings menu.

In contrast to several other sharing plugin, this one aims to use only original code provided by any social network.
There is no other service in the middle, you are not required to register or get a key to use it. Enjoy!

Facebook Like, Twitter and Google +1 share buttons are loaded by default. 
Other buttons, including Digg, Facebook Share, Flattr, LinkedIn, Pinterest, Bitcoin, Litecoin, Stumbleupon, Youtube, Hyves, Print, Email, RSS, Reddit, Tipy, Tumblr, Buffer, Pinzout can be added through the `Settings->Really simple share` menu.

Please be careful when selecting the `Show buttons in these pages` options : it can interact badly with other slide/fade/carousel/sidebar active plugins.

= Shortcode =

If you want to place the active buttons only in selected posts, use the [really_simple_share] shortcode.

If you want to place only one share button, add the *button* attribute to the shortcode, e.g.:

* [really_simple_share button="facebook_like"]
* [really_simple_share button="twitter"]
* [really_simple_share button="linkedin"]
* [really_simple_share button="pinterest"]
* [really_simple_share button="google1"]
* [really_simple_share button="digg"]
* [really_simple_share button="stumbleupon"]
* [really_simple_share button="reddit"]
* [really_simple_share button="flattr"]
* [really_simple_share button="tumblr"]
* [really_simple_share button="facebook_share"]
* and so on...

Please note that in order to place single share buttons, they have to be active in the plugin settings page.

= Selective disable =

If you want to hide the share buttons inside selected posts, set a "really_simple_share_disable" custom field with value "yes".

= News =

*New* in version 3.0:

* New Bitcoin and Litecoin buttons
* Code cleaning

= Reference =

For more info on this plugin: [www.whiletrue.it](http://www.whiletrue.it/really-simple-facebook-twitter-share-buttons-for-wordpress/ "www.whiletrue.it")

Do you like this plugin? Give a chance to our other works:

* [Most and Least Read Posts](http://www.whiletrue.it/most-and-least-read-posts-widget-for-wordpress/ "Most and Least Read Posts")
* [Tilted Tag Cloud Widget](http://www.whiletrue.it/tilted-tag-cloud-widget-per-wordpress/ "Tilted Tag Cloud Widget")
* [Reading Time](http://www.whiletrue.it/reading-time-for-wordpress/ "Reading Time")

== Installation ==
Best is to install directly from WordPress. If manual installation is required, please make sure to put all of the plugin files in a folder named `really-simple-facebook-twitter-share-buttons` (not two nested folders) in the plugin directory, then activate the plugin through the `Plugins` menu in WordPress.

== Frequently Asked Questions ==

= The settings page seems corrupted, some buttons labels are missing. How to solve it? =

Sometimes the options get corrupted after several plugin updates. 
Click the Reset button (on the lowest right of the plugin settings page) to give the plugin a fresh start. 
Please take a note of your plugin configuration before resetting the options, or remember the data you want to put back in.

= What's the difference between Facebook Like and Share buttons? =
Facebook Like is the official Button actively supported by Facebook.
On 18th July 2012 Facebook dropped support for the (old and long time deprecated) Share button, so this button has been removed from the plugin and replaced with an externali link with no counter.

= Why users can't choose which image to share when using Facebook Like button ? =
This is an automated Facebook behaviour: clicking Facebook Like the user can't choose each time which image to share, 
but you can set the right image inside the code using the 
<a href="http://developers.facebook.com/docs/reference/plugins/like/">Open Graph Tag</a> og:image.

= When I activate the plugin it messes up with other plugins showing post excerpts in different ways (fade, carousel, sidebar). What can I do? =
Uncheck all the "Show buttons in these pages" options in the `Settings->Really simple share` menu, except for "Single posts".
This way all the share buttons should disappear, except the one displayed beside the post in every Single post page.

= Is it possible to modify the style/css of the buttons? =
Yes, every button has its own div class (e.g. "really_simple_share_twitter") for easy customization inside the theme css files.
Plus, the div surrounding all buttons has its own class "really_simple_share". 
If you want to override default styling of the buttons, check the `disable default styles` option add your style rules inside your css theme file.

= Is it possible to show the buttons anywhere inside my theme, using a PHP function? =
Yes, you can call this PHP function:
`<?php echo really_simple_share_publish($link='', $title=''); ?>` 
You can pass the share link and the title as parameters.
You shouldn't leave the parameters blank, unless the code is put inside the WP loop.
For example, use this code to create buttons linking to the website home page:
`<?php echo really_simple_share_publish(get_bloginfo('url'), get_bloginfo('name'));  ?>`

= Sometimes the Pinterest PinIt button doesn't appear in some posts. =
The PinIt button requires some media to share, so it only shows up when the post contains at least one image.

= I've cheched the "show counter" option in the Settings page, but sometimes the Pinterest PinIt button's counter doesn't appear. =
The PinIt counter only appear if the post has been shared at least once. Also, the refresh time for the PinIt counter could be long. 

== Screenshots ==
1. Sample content, activating the Facebook Share and Twitter buttons  
2. Options available in the Settings menu 


== Changelog ==

= 3.0.2 =
* Fixed: Facebook Like iFrame fix
* Fixed: Buffer button default text cleaning

= 3.0.1 =
* Changed: Try to use image title, if alt text is void, as Pinterest description

= 3.0 =
* Added: Bitcoin and Litecoin buttons
* Changed: Code cleaning

= 2.17.3 =
* Added: Use image alt text, if present, as Pinterest description 

= 2.17.2 =
* Fixed: Facebook Share (new) css cleaning

= 2.17.1 =
* Fixed: Facebook Share (new) button in count page

= 2.17 =
* Added: Facebook Share (new) button
* Changed: Performance improvements
* Fixed: Hardcoded CSS cleaning
* Fixed: Facebook Like code cleaning

= 2.16.4 =
* Changed: Facebook Like button code update

= 2.16.3 =
* Changed: Twitter button async code

= 2.16.2 =
* Changed: New button to show/hide advanced options
* Fixed: Facebook Like Send button enable bug

= 2.16.1 =
* Changed: Old tags cleaning

= 2.16 =
* Added: New admin page showing share button counts for recent posts and pages
* Fixed: Minimum box height for large buttons

= 2.15 =
* Added: Fixed share url option for the Facebook Like button
* Fixed: Disabled buttons messed up on older (<3.0) WordPress releases
* Fixed: Options messed up on install

= 2.14.4 =
* Changed: Updated FAQ
* Changed: Plugin tested up WordPress 3.6
* Fixed: Smartphone usability improvements on settings screen
* Fixed: XHTML validation fix

= 2.14 =
* Added: Large button option, available for some social networks (e.g. google+ and twitter)
* Changed: Code cleaning

= 2.13.2 =
* Changed: Settings page style cleaning for the new MP6 Admin Theme
* Changed: Code cleaning

= 2.13 =
* Added: Reset to Default values button
* Changed: Code cleaning

= 2.12 =
* Added: Google share button
* Fixed: Pinterest image default alt text (thanks Laserjob)

= 2.11.2 =
* Added: Email custom subject
* Fixed: Plugin updates bug
* Fixed: Facebook like status bug (thanks laserjobs)
* Fixed: Pinterest title bug (thanks laserjobs)

= 2.11 =
* Added: Youtube channel button

= 2.10.5 =
* Fixed: CSRF vulnerability on settings page
* Fixed: Pinterest image fixed protocol, avoids mess on some sites
* Fixed: Pinterest multiple calls (from different plugins) could stop counters
* Fixed: Facebook iframe style cleaning

= 2.10 =
* Added: Different calls to action, above and below the post
* Added: Print button, with optional label
* Changed: code cleaning

= 2.9.9 =
* Added: Pinterest image hover button
* Added: WhileTrue RSS Feed
* Added: CSS layout classes "really_simple_share_box" and "really_simple_share_button" for easier styling
* Added: New `really_simple_share_box` CSS class for box layout
* Added: New `really_simple_share_button` CSS class for button layout
* Changed: Facebook Html5 popup style cleaning
* Changed: Pinterest async code and cleanup
* Changed: Display, default options and translations cleaning
* Fixed: better support of home page buttons selection (thanks Amaury Balmer)
* Fixed: Twitter little bug, showed weird text on some websites
* Fixed: Pinterest box layout vertical offset
* Fixed: Pinterest post image search only when in loop
* Fixed: Email button image theme-aware CSS style

= 2.9 =
* Changed: Pinterest button complete rewrite
* Changed: Pinterest button fallback: when no media is found, shows the multiple image selector
* Changed: Code cleaning

= 2.8.2 =
* Fixed: Flattr button height issue while showing in box size
* Fixed: Facebook Like button Html5 code locale and action (like/recommend)

= 2.8 =
* Added: Facebook app ID setting for the Facebook Like button (recommended)
* Added: Facebook Like button Html5 code (recommended if your theme supports it)

= 2.7 =
* Added: support for translations
* Added: Italian translation

= 2.6.3 =
* Added: Multi language support: if the WPML plugin is active, language is set automatically for each button
* Changed: CSS improvement on the block surrounding the buttons
* Changed: Pinterest button update (please clear cache on update)

= 2.6 =
* Added: Pinzout button
* Added: Comments RSS Feed button
* Changed: improved CSS file with iframe border cleaning
* Changed: new default options (Google +1 enabled by default)
* Fixed: code cleaning and better assets inclusion
* Happy new year 2013!

= 2.5.11 =
* Added: Tumblr button (basic support: no display option)
* Added: Facebook Share button back again in a new limited version (no counter, no style), facing its dropped support by Facebook
* Added: shortcode single button options 
* Added: Pinterest multiple image selector (thanks Stephen Baugh)
* Added: Optional related Twitter usernames (comma separated) added to the follow list
* Changed: screenshots moved outside, reducing the size of the plugin and allowing for faster updates
* Changed: separate images folder, for plugin structure cleaning
* Changed: different file inclusion code
* Changed: Pinterest multiple image selector made optional (doesn't work in some environments)
* Changed: Google Buzz button removed (Google dropped support to it)
* Changed: Facebook Share button removed (Facebook dropped support to it)
* Changed: Possibility to set a custom title in the publish function, leaving the default link (thanks Arvid Janson)
* Changed: Facebook Send button code update and style cleaning
* Fixed: php code notices
* Fixed: Facebook Like button width fix
* Fixed: "Missing Title Tags" error solved for the PinIt image selection iframe
* Fixed: apply shortcode to content before adding the buttons (thanks Stephen Baugh)
* Fixed: Force https protocol whenever possible
* Fixed: better style file inclusion and small code cleaning
* Fixed: Email button url cleaning
* Fixed: Better special characters in url handling
* Fixed: Facebook Like foreign languages support
* Fixed: Facebook Send locale, broken in the 2.5.2 update
* Fixed: Little php code cleaning

= 2.5.0 =
* Added: Buffer button
* Fixed: Style cleaning for the prepend_above box
* Changed: Pinterest button is shown if some image is found in the post content, even if it's not a thumbnail or an attachment

= 2.4.4 =
* Changed: Little code cleaning
* Fixed: Google+ and Pinterest buttons broken in previous updates
* Fixed: Google+ and Pinterest issue on header javascript loading
* Fixed: The option to disable buttons on excerpts now correctly disables only the plugin  
* Fixed: Facebook Like box height
* Fixed: Pinterest button broken in 2.4 and 2.4.1 while recognizing images in posts 

= 2.4 =
* Added: Facebook share button counter customization
* Added: Option to disable buttons on excerpts
* Changed: CSS Style improvements (button vertical alignment, removed redundant code)
* Changed: Removed redundant spaces (sometimes breaking the button alignment) 
* Fixed: If button width is not set, use the default value
* Fixed: For Pinterest, now check the existence of the function has_post_thumbnail
* Fixed: On some templates the Google+ button was disappearing (javascript code not loaded)

= 2.3 =
* Added: Pinterest button (basic support: only shows if there is some media, links to the thumbnail or to the first media attachment)
* Added: Language basic support for some buttons
* Changed: More compact and effective Settings page
* Changed: Update on Google +1 button code

= 2.2 =
* Added: Option to put a line of text above the buttons, e.g. 'If you liked this post, say thanks sharing it:'
* Added: Option to put an inline short text just before the buttons, e.g. 'Share this!'

= 2.1 =
* Added: Option to put scripts at the bottom of the body, to increase page loading speed
* Added: Option to enable/disable adding the author of the post to the Twitter follow list
* Added: Little performance improvements

= 2.0 =
* Added: Button arbitrary positioning via drag&drop
* Added: Arbitrary spacing for every button
* Added: Twitter post author customization (thanks Vincent Oord - Springest.com)
* Added: Wordpress link customization (default permalink and shortlink available)
* Added: Email button label
* Added: Class "robots-nocontent" and "snap_nopreview" given to the element surrounding the buttons
* Added: Some code cleaning

= 1.8.4 =
* Added: Tipy button
* Added: Linkedin button counter customization
* Changed: better email icon (thanks Jml from Argentina)
* Fixed: Twitter share button title cleaning (thanks Harald)
* Fixed: Removed the standard "Tweet" text from the link inside the Twitter button, to avoid its occasional presence in the summaries (thanks David)

= 1.8.0 =
* Added: Separate stylesheet added, with an option to disable it

= 1.7.3 =
* Fixed: Flattr share button title cleaning (thanks Harald)
* Fixed: Flattr share button js api loading, tags loading and text linking 
* Fixed: Flattr share button warning for posts without tags

= 1.7.0 =
* Added: Flattr share button

= 1.6.3 =
* Added: Box layout available for compatible buttons
* Added: Google +1 button width and counter customization
* Fixed: Facebook Like button url encoded (thanks Radek Maciaszek)

= 1.6.0 =
* Added: Google +1 share button
* Added: possibility to hide the Twitter button counter 
* Changed: admin page restyle

= 1.5.0 =
* Added: possibility to use the "really_simple_share_publish" PHP function to publish the buttons inside the PHP code, for themes and other plugins
* Changed: single permalink and title loading, for better performance

= 1.4.16 =
* Added: Reddit share button
* Added: Email share button
* Added: "really_simple_share_disable" custom field, if set to "yes" hides share buttons inside post content
* Added: [really_simple_share] shortcode, shows active share buttons inside post content
* Added: Hyves (the leading Duch social network) button
* Added: Facebook Like text customization (like/recommend)
* Added: Facebook Like button new "Send" option (currently via FBML)
* Added: Facebook Like and Twitter button width customization via the options menu 
* Added: Possibility to position the buttons above and below the post content
* Changed: admin css improvements
* Changed: removed redundant <br /> element
* Changed: [really_simple_share] shortcode works even when "really_simple_share_disable" is used (thanks to Chestel!)
* Fixed: more vertical space (for the current Facebook Like button)
* Fixed: Digg button JS removed from the <head> section
* Fixed: Email share button image absolute path
* Fixed: PHP Notices
* Fixed: css improvements
* Fixed: Twitter button fixed-width style for WPtouch compatibility
* Fixed: Excerpt/Content and JavaScript loading
* Fixed: Show in Search results

= 1.4.0 =
* Added: "Show in Search results" option
* Added: Twitter additional text option, e.g. ' (via @authorofblogentry)'
* Changed: Avoid multiple external JavaScript files loading when possible, for better performance
* Changed: Settings display improvement
* Fixed: Twitter title button

= 1.3.0 =
* Added: Digg and Stumbleupon share buttons
* Added: CSS classes for easy styling

= 1.2.3 =
* Added: Facebook like button (Facebook share is still present but deprecated)
* Added: Google Buzz share button
* Changed: Save/retrieve options standardization
* Fixed: Facebook share button
* Fixed: Button positions and links

= 1.2.0 =
* Added: Active buttons option
* Added: Active locations (home page, single posts, pages, tags, categories, date based archives, author archives) option

= 1.1.0 =
* Added: LinkedIn share button

= 1.0.1 =
* Fixed: Uninstall

= 1.0.0 =
Initial release


== Upgrade Notice ==

= 2.10.3 =
Users having versions from 2.10.1 and 2.10.2 should upgrade due to a bugfix on the Pinterest button

= 2.4.4 =
Users having versions from 2.4 to 2.4.3 should upgrade due to a bugfix on the Google+ and Pinterest buttons 

= 1.7.3 =
Users having version from 1.6.3 to 1.7.2 should upgrade due to a bugfix on the Flattr button 

= 1.7.2 =
Users having version from 1.6.3 to 1.7.1 should upgrade due to a bugfix on the Flattr button 

= 1.7.1 =
Users having version from 1.6.3 to 1.7.0 should upgrade due to bugfixes on general loading and on the Flattr button 

= 1.4.2 =
Users having version 1.4.0 and 1.4.1 are advised to upgrade due to an Excerpt/Content and JavaScript loading bugfix

= 1.2.2 =
Facebook Share button is deprecated in favor of Facebook Like button

= 1.0.0 =
Initial release

