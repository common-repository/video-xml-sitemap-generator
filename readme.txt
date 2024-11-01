=== Plugin Name ===
Contributors: tradebooster
Tags: Video XML Sitemap Generator, dailymotion videos, You Tube videos, Vimeo videos, xml sitemaps, google, google sitemaps, google video sitemap, google xml sitemap, videos, seo, sitemaps, video sitemap
Requires at least: 2.9.2
Tested up to: 3.5.2
Stable tag: 1.0.0

== Description ==

The Video XML Sitemap Generator plugin generates an XML file containing information about the videos embedded in your WP Pages and Posts as per Google XML Schema for Video Sitemaps. You can upload your videos to either Youtube, Vimeo or Dailymotion. The plugin will read all your pages and posts and generate a sitemap containing links and information about all the videos that you have embedded.

If you have questions regarding the plugin or its usage or to report a bug, drop us an email at <a href="mailto:support@tradebooster.com"> support@tradebooster.com</a>

== Installation ==

How you can install the plugin:

1. Upload the plugin's folder (video-sitemap-generator) to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress

== FAQ ==

= Can this plugin be used to generate a sitemap for my DailyMotion videos? =

Yes

= Can this plugin be used to generate a sitemap for my Vimeo videos? =

Yes

= Can this plugin be used to generate a sitemap for my YouTube videos? =

Yes

= How do I configure which video services to use for generating the sitemap? =

1. To configure the video services to use to generate the sitemap expand the Settings menu from WordPress dashboard sidebar and select "Video Sitemap".
2. On the plugin settings page select the Checkbox for the services you would like to use on your site.
3. Save these settings. And you are done.

= How do I generate my sitemap for the first time =

If you are generating the sitemap for the first time, we recommend that you follow the steps below:

1. create an empty file with the name video-sitemap.xml in the root folder of your Wordpress Installation.
2. Using your FTP client or web hosting control panel (cPanel or Plesk) change the permissions of this file to 777.
3. Go to Settings -> Video Sitemap in the Wordpress Admin
4. Click on the "Generate Video Sitemap" button to create your XML Sitemap.

= I am getting Permission Denied errors =

It implies that you don't have write permissions on the video-sitemap.xml file. Please use chmod or your FTP manager to set the necessary permissions to 777. Follow the steps mentioned in the above FAQ.

= How can I submit my video sitemap to Google? =

Once you have created your Sitemap, you can submit it to Google using Webmaster Tools. Google doesn't guarantee that all videos included in a Sitemap will appear in their search results.

= How do I customize the description of my video in the sitemap? =

The Video XML Sitemap Generator plugin will pull the description for your video from your post excerpt.

= Where's the sitemap file stored? =

You can find the video-sitemap.xml file in your blog's root folder.

== Screenshots ==

1. Configuration