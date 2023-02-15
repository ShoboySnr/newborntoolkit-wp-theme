=== Plugin Name ===
Contributors: markusfroehlich
Donate link: https://www.paypal.com/donate?business=DUKJP25LKTX62&currency_code=EUR
Tags: sticky, switch, post, cpt, admin
Requires at least: 4.0
Tested up to: 6.0.1
Stable tag: 2.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds a sticky post switch functionality to the admin list post/custom post type pages.

== Description ==

This plugin adds a new column to the post admin columns that allows you to easily mark a post so that it is sticky.
Sticky Posts is a WordPress feature only for posts, with this plugin you can use this feature also with custom post types.

= Features of sticky posts =

* enables you to use the sticky posts feature with every custom post type on front page, archive page or category page
* quick and bulk edit support for custom post types
* selection of the post type (post or custom post type)
* selection of the color of the switch icon
* customized order of the column showing the switch icon
* use only built-in WordPress functions
* the star-icon switch saves the posts in the sticky status immediately with ajax
* optionally, set all translations of a post sticky, supports [Polylang](https://polylang.pro) and [MultilingualPress](https://wordpress.org/plugins/multilingual-press)

== Installation ==

1. Upload 'sticky-posts-switch' to the '/wp-content/plugins/' directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. You will now have the new column "Sticky Post" in the post admin screen.

== Frequently Asked Questions ==

= Where can I config the plugin? =

In the menu, navigate to the item "Settings -> Sticky Posts"

= Why can't I find my own type of content (custom post type) in the list? =

The only types of content displayed are those declared as public.
See [register post type](https://codex.wordpress.org/Function_Reference/register_post_type).

= I found a bug, what shall I do? =

If you have found a bug in my plugin, please send me an email with a short description.
I will fix the bug as soon as possible.

= You like my plugin and you'd like to support me? =

Thank you very much!
In case you want to show how much you appreciate my work, I'd be very grateful if you could give me positive rating with Wordpress-Page and/or donate a small amount to me.

== Screenshots ==

1. The sticky post column at the admin list post page
2. Plugin settings

== Changelog ==
= 2.1.3 =
* Dev - Added the filter "wp_sticky_posts_switch_checkbox_label_text"

= 2.1.2 =
* Dev - Tested up with WordPress 6.0.1
* Dev - Code optimizations

= 2.1.1 =
* Fix - Supported multilingual plugins text in settings page

= 2.1.0 =
* Feature - Add support for Polylang
* Fix - Sticky post removed after save post (Gutenberg)
* Dev - Remove support for WPML (WPML actually has this same functionality)

= 2.0.0 =
* Feature - Add support for the sticky post class
* Dev - Tested up with WordPress 5.2.3
* Dev - Code optimizations
* Dev - Improvement for better main instance call
* Fix - Retrieve the correct queried object for the filtered wp_query

= 1.9.2 =
* Fix - Maximum function nesting level error issue

= 1.9.1 =
* Improvement for better handling in wp_query filters

= 1.9 =
* Add new settings "show on front page" for each post type
* Default posts are now able to activate/deactivate the sticky feature on "front page" or "posts page"

= 1.8 =
* Add new settings table for post types, archive pages and taxonomy pages
* Removed setting "ignore retrieved posts"
* Language pot file update

= 1.7 =
* Code and performance optimizations
* Bugfix in the WPML integration
* Translation fixes

= 1.6 =
* Code and performance optimizations
* Translation fixes
* Throw an JavaScrip alert on ajax sticky / unsticky error

= 1.5 =
* Code and performance optimizations
* Hide the sticky post column at the post trash page

= 1.4 =
* Bugfixes in the "the_posts" filter for custom post types

= 1.3 =
* Add bulk and quick edit support for custom post types
* Add a icon animation during ajax loading
* Add an ajax queue for a better switch experience
* Update the post states immediately after sticky/unsticky a post
* Redesign of the settings page
* Code optimization
* Translation fixes

= 1.2 =
* Readme update
* Translation fixes

= 1.1 =
* Translation fixes
* Add multilingual support for WPML and MultilingualPress

= 1.0 =
* Initial Release