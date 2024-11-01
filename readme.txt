=== Surveys Extended ===
Contributors: pitschi
Donate link: http://www.a-sd.de/
Tags: survey, question, answer, test, opinion, questionaire
Requires at least: 2.5
Tested up to: 3.1.3
Stable tag: 0.0.6

The Surveys Extended WordPress plugin lets you add surveys to your blog.

== Description == 

The Surveys Extended WordPress plugin lets you add surveys to your blog. It is based on the Surveys plugin by Binny V A. 

You can let the vistors take surveys and see the result from the admin side.

This extended version of the original plugin is advanced in the following ways:

* it offers the possibility to change the order of the questions of a survey
* it provides a back and foreward button to the customer


There are two ways to see the data...

__Aggregate__

This will show the results each question by aggregating all the data so far. This will look like a poll result. This mode is useful to make decisions - you can immediately see the most favored answers in each question.

__Individual Responses__

You can also view all the answers provided by one visitor.

More Details at the original [Surveys WordPress Plugin post](http://www.bin-co.com/blog/2008/11/surveys-wordpress-plugin/) in the blog of Binny V A or on the page of the [Advicio(R) ServDesk GmbH](http://www.advicio-servdesk.de/en "Advicio(R) ServDesk GmbH").

== Installation ==

1. Download the zipped file and extract and upload the contents of the folder to /wp-contents/plugins/ folder
1. Or install it via the plugin manager of WordPress. 
1. Go to the Plugin management page of WordPress admin section and enable the 'Surveys Extended' plugin
1. Go to the Surveys Management page(Tools > Manage Surveys) to create or edit the surveys

= Update from 0.0.1 =
We fixed some severe problems with the database access. If you still have problems try to deactivate and delete the plugin before reinstalling it.   

== Frequently Asked Questions == 

* no questions a.t.m.

== ChangeLog ==

= 0.0.6 = 
* FIX: commented out some debugging code in the javascript file because of an error with the IE.
* thx to maydalenka

= 0.0.5 = 
* FIX: problem with the survey submit form
* FIX: problem with the css -> please press SHIFT + F5 on the survey
* thx to maydalenka

= 0.0.4 =
* FIX: problem with qtranslate integration fixed. please create a survey for each language and append the translated survey to the translated article or page.

= 0.0.3 =
* FIX: an error with the visualization of the shortcode. You have to use [SURVEYS_EXTENDED #] to append the survey.

= 0.0.2 =
* FIX: some severe errors with the database
* thx to artsmo and mayday

= 0.0.1 =
* initial version


== Upgrade Notice ==

= 0.0.6 =
Commented out some code for debugging purposes in the javascript that lead to an error within the IE. We recommend an update.

= 0.0.5 = 
Fixed a problem with the survey submit form and the css. We recommend an update. 

= 0.0.4 =
We had some problems with the editor modified by qtranslate. You should update if the field for the question is not visible. 

= 0.0.3 = 
Fixed an error with the visualization of the shortcode. 

= 0.0.2 =
We fixed some severe errors in the database handling we highly recommend an update. If you have problems with this deactivate and delete the plugin and reinstall it. 
