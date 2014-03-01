=== My Custom CSS ===
Contributors: DarkWolf
Donate link: http://www.darkwolf.it/donate-wp
Tags: css, style, custom, theme, plugin, stylesheet, darkwolf
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 1.5

Enable to add Custom CSS Code via admin panel (with syntax and tab support).

== Description ==

Maked by Salvatore Noschese (DarkWolf): http://www.darkwolf.it/

With this plugin you can put custom css code without edit your theme and/or your plugins (really useful in case of any theme/plugin update).

It contain also a Syntax (by <a href="http://codemirror.net/">CodeMirror</a>) color and tab support for write a good css code.

You can see in action (source code) here: http://vegamami.altervista.org/ :)

= Links =

* Author Homepage: [DarkWolf](http://www.darkwolf.it/)
* Plugin maked for (demo link): [VegAmami](http://vegamami.altervista.org/)

= Language =

* English
* Italian
* (If you translate in your language, please, send me) :)

== Screenshots ==

1. Custom Menu in Admin Panel + Box
2. Source code: <a href="http://vegamami.altervista.org/">Vegamami</a> | Stylesheet: <a href="http://vegamami.altervista.org/wp-content/plugins/my-custom-css/my_style.css">./my-custom-css/my_style.css</a>

== Installation ==

1. Upload `my-custom-css` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Upgrade Notice ==

= 1.5 =

* Full compatible with Wordpress multisite (network mode)!

== Changelog ==

= 1.5 =

* New feture: Now is full compatible in network mode (multisite support)!

= 1.4 =

* Fix a small issue in "@import url()" (add ";") with safari browser!

= 1.3 =

* Now code is saved both on database and also in file "my_style.css[+ '?filemtime' to fix browser cache]" when you click on "Save" (made and updated via db+php only if is present css code). Thanks to this I can see custom css code in admin panel via database and put in source via file with '@import url("my_style.css[+ '?filemtime' to fix browser cache]")'. I think (and hope) that this can optimize source code view and time load!
* New "Save" button in plugin page (fixed via css in top right position)!
* New "Top" button in bottom right position (classic "anchor" top button)!
* Many other code clean and optimization!

= 1.2 =

* Removed background in plugin list: <a href="http://wordpress.org/support/topic/plugins-page-colour">support/topic/plugins-page-colour</a>

= 1.1 =

* Updated CodeMirror to release 3.1!

= 1.0 =

* Add CSS Style background and icon in plugins page :)

= 0.9 =

* Very minor change: Plugin priority to 999 (now latest in header)
* Some little fix and clean/indent in php code

= 0.8 =

* Updated CodeMirror to release 3.02!
* Some little change to readme.txt (removed faq and fixed other info).

= 0.7 =

* Changed plugin URI from darkwolf.it to wordpress.org
* Some CSS fix if no JavaScript enabled in browser
* Updated CodeMirror (Syntax) to latest release (atm 3.01)
* New Support and Setting link in plugins list
* Translated Description and Support/Settings links in Ita

= 0.6 =

* Some little fix in CSS!
* Fix incompatibility with WP Editor Plugin: <a href="http://wordpress.org/extend/plugins/wp-editor/">/extend/plugins/wp-editor/</a>

= 0.5 =

* Update Donate link to: <a href="http://www.darkwolf.it/donate-wp">darkwolf.it/donate-wp</a>
* Update CodeMirror (Syntax) to release 3.0: <a href="http://codemirror.net/">codemirror.net</a>
* Add strip tag to prevent bad code: <a href="http://php.net/manual/en/function.strip-tags.php">function.strip-tags.php</a>

= 0.4 =

* Some little fix in css auto height (codemirror.css)

= 0.3 =

* Update Syntax CodeMirror to Version 2.15: <a href="http://codemirror.net/">codemirror.net</a>

= 0.2 =

* Now you can see in source code only if is present custom css
* Blog's homepage redirect for direct access in my-custom-css.php
* Empty "index.html" in all directory to Prevent Directory Listing
* New menu in admin panel (after "Appearance" and before "Plugins") with custom icon

= 0.1 =

* First release