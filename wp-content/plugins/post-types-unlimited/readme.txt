=== Post Types Unlimited ===
Contributors: WPExplorer
Tags: custom post types, post types, types, cpt, taxonomies
Requires at least: 4.6
Requires PHP: 5.6.2
Tested up to: 5.7.1
Stable Tag: 1.0.5
License: GNU Version 2 or Any Later Version

== Description ==
Post Types Unlimited is an easy way to add **custom post types** and **custom taxonomies** to your WordPress site (the right way). The plugin works with any theme and is easily translatable. With Post Types Unlimited you can:

* Create custom post types
* Create custom taxonomies

This plugin does not currently allow you to add custom fields (in the works) but it works great with the [ACF Plugin](https://wordpress.org/plugins/advanced-custom-fields/).

Post Types Unlimited makes use of core WordPress functionality for the admin screens and post type, taxonomy registration. This means the plugin is fast, slim and uses the familiar Wordpress UI. Additionally you won't find any upsell or advertisements in the plugin because there isn't a "Pro" version. It's the perfect plugin for adding post types or taxonomies for any site including your client sites.

The design of your post types and taxonomies created with Post Types Unlimited are controlled by your theme. The plugin doesn't do any hacking or advanced modifications to your templates and thus works great with ANY theme. Now, if you are using the [Total Theme](https://total.wpexplorer.com/) you will actually see extra settings that allow you to modify the singular and archive displays for your post types. This is because Total hooks into the plugin to add extra settings. If you are a theme developer you can do the same by hooking into the "ptu_metaboxes" filter to register your own custom meta settings to the add/edit post type and taxonomy screens.

== Installation ==

1. Upload 'post-types-unlimited' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Post Types to add new custom post types
4. Go to Post Types > Taxonomies to add new custom taxonomies

== Frequently Asked Questions ==

= What does the plugin do? =
It adds a new tab in the WordPress admin panel called "Post Types" where you can add new custom post types or custom taxonomies to your site.

= Can I export my custom post types and taxonomies? =
Yes you can! The plugin actually uses a post type to register your custom types and taxonomies thus you can use the core WordPress exporter/import for this.

== Changelog ==

= 1.0.5 =
* Fixed Sanitization when saving post type names to allow underscores and dashes (passes through sanitize_key).

= 1.0.4 =
* Fixed Public and Publicly Queryable arguments not working for custom taxonomies.

= 1.0.3 =

* Fixed potential debug error in Metaboxes.php on line 158.
* Updated admin dashboard columns to display useful details.

= 1.0.2 =

* Improved Menu Icon selector.
* Updated Menu Icon Dashicons list to include new Dashicons added in WP 5.5.
* Improved meta field save function to allow 0 to be saved for text and number fields.
* Added ability to display placeholders for number fields.

= 1.0.1 =

* Fixed issue "With Front" option not working correctly.

= 1.0 =

* First official release