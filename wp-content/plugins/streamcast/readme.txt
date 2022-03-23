=== StreamCast - Radio Player for WordPress  ===
Contributors: abuhayat, freemius, shehabulislam
Tags: live stream, icecast, shoutcast, radio player, Audio player
Donate link: https://gum.co/wpdonate
Requires at least: 3.0
Tested up to: 5.8
Stable tag: 2.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Play iceCast, Shoutcast, Radionomy, Radiojar, RadioCo Live stream in Wordpress.

== Description ==

A simple, accessible, user friendly and fully customizable radio player for WordPress. You can  play iceCast, Shoutcast, Radionomy, Radiojar, RadioCo Live stream in WordPress website using shortcode.

= How to use = 


https://www.youtube.com/watch?v=ad_LKLe_YQg



= The stream will not more play in Google Chrome 80+ =

As of Google Chrome 80, mixed content is no longer supported. If the stream is without SSL and the website is with SSL, the stream will no longer be played. Other browsers follow the example of Google. The solution is to set the stream to SSL.

More you can read here: [https://blog.chromium.org/2019/10/no-more-mixed-messages-about-https.html](https://blog.chromium.org/2019/10/no-more-mixed-messages-about-https.html "Visit Link")

How to setup free SSL for your Shoutcast Server Software:
[https://help.shoutcast.com/hc/en-us/articles/360003410613-How-to-setup-free-SSL-for-your-Shoutcast-Server-Software-](https://help.shoutcast.com/hc/en-us/articles/360003410613-How-to-setup-free-SSL-for-your-Shoutcast-Server-Software- "Visit Link")

= Demo  =

LIVE DEMO ->  [Check the Demos ](http://wpradioplayer.com/ "See Demo")  


= ShortCode =

Use shortCode <pre> [stream url="YOUR_STREAM_URL"] </pre> in post / pages/ or any widget area to Embed a player.
 
if you want to use custom background color add background attribute in the shortCode like that <pre>[stream url="http://192.211.51.219:80/live" background="gray"] </pre> 
.You can use color name or color code between double quotations. 

= Feedback = 
Like the plugin? Hate it? Want a new feature?  [Send me some feedback](mailto:abuhayat.du@gmail.com "Send me some feedback")  


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `plugin-directory` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use shortcode in page, post or in widgets.
4. If you want the player in your theme php file, Place `<?php echo do_shortcode('YOUR_SHORTCODE'); ?>` in your template file. 


== Frequently Asked Questions ==

= The player is not playing my Live stream, What can i do now ? =

Please check that the Protocols of Live Stream and Website. If your website running in Https (Secure) and your stream is Http (Unsecure) then modern browsers may not play that stream. Please try to keep both protocol same. 


= The Second Player is not showing as the First one ? =

You cannot embed more than one player in a single page. 





== Screenshots ==

1. Radio Player in Front end.


== Changelog ==

= 2.0.0 =
* Added player theme.
* Added Ultimate radio Player type
* Added custom color in player settings.
* Added modern skins.

= 1.0 =
* Initial Release


