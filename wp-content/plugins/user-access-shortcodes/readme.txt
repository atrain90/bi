=== User Access Shortcodes ===
Contributors: spwebguy
Tags: access, users, user, logged, logged in, registered, logged in, shortcodes, shortcode, content, restrict, control, posts, pages, block, restriction, button, editor 
Requires at least: 3.6
Tested up to: 5.1
Stable tag: trunk
License: GPL2
License URI: http://www.gnu.org/licenses/gpl.html

The most simple way of controlling who sees what in your posts/pages. Restrict content to logged in users only (or guests) with a simple shortcode.

== Description ==
This is the most simple way of controlling who sees what in your posts/pages. This plugin allows you to restrict content to logged in users only (or guests) with a simple shortcode. What you see is what you get, and it’s totally free.

= Usage =
##### Show content only for Guests
~~~~
[UAS_guest hint="" in="" admin="1"]
This content can only be seen by guests.
[/UAS_guest]
~~~~
##### Show content only for Registered/Logged in users
~~~~
[UAS_loggedin hint="" ex=""]
This content can only be seen by logged in users.
[/UAS_loggedin]
~~~~
##### Show content ony for specific users
~~~~
[UAS_specific hint="" ids="" admin="1"]
This content can only be seen by some selected users.
[/UAS_specific]
~~~~

Go to [the plugin's documentation](https://wpdarko.zendesk.com/hc/en-us/articles/206303637-Get-started-with-the-User-Access-Shortcodes-plugin) if you need more information on how to use this plugin.

= Support =
Find help in [our forums](https://wpdarko.com/ask-for-support/) for this plugin (we’ll answer you fast, promise).

== Installation ==

= Installation =
1. In your WordPress admin panel, go to Plugins > New Plugin
2. Find our Responsive Tabs plugin by WP Darko and click Install now
3. Alternatively, download the plugin and upload the contents of user-access-shortcodes.zip to your plugins directory, which usually is /wp-content/plugins/
4. Activate the plugin

= Usage =
##### Show content only for Guests
~~~~
[UAS_guest hint="" in="" admin="1"]
This content can only be seen by guests.
[/UAS_guest]
~~~~
##### Show content only for Registered/Logged in users
~~~~
[UAS_loggedin hint="" ex=""]
This content can only be seen by logged in users.
[/UAS_loggedin]
~~~~
##### Show content ony for specific users
~~~~
[UAS_specific hint="" ids="" admin="1"]
This content can only be seen by some selected users.
[/UAS_specific]
~~~~

Go to [the plugin's documentation](https://wpdarko.zendesk.com/hc/en-us/articles/206303637-Get-started-with-the-User-Access-Shortcodes-plugin) for information on how to use it.

== Frequently Asked Questions ==
= Usage =
1. Add/Edit a post/page
2. Click the User Access Shortcodes button from the editor’s menu
3. Choose between “Guests only”, “Logged in users only” and “Specific users” (by ID)
4. Add your content between the tags

Go to [the plugin's documentation](https://wpdarko.zendesk.com/hc/en-us/articles/206303637-Get-started-with-the-User-Access-Shortcodes-plugin) if you need more information on how to use this plugin.

= Support =
Find help in [our forums](https://wpdarko.com/ask-for-support/) for this plugin (we’ll answer you fast, promise).

== Screenshots ==
1. Simple, hassle-free content restrictions

== Changelog ==
= 2.1.1 =
* Fixed nested shortcode issue

= 2.1 =
* Better shortcode support (nested)

= 2.0 =
* Added new minor features
* Can show/hide content for users by ID

= 1.3 =
* Nested shortcodes support

= 1.2 =
* Minor fixes

= 1.1 =
* Minor bug fix

= 1.0 =
* Initial release (yay!)
