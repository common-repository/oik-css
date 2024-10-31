=== oik-css ===
Contributors: bobbingwide, vsgloik
Donate link: https://www.oik-plugins.com/oik/oik-donate/
Tags: CSS, GeSHi, blocks, shortcodes
Requires at least: 5.0
Tested up to: 6.6.2
Gutenberg compatible: Yes
Stable tag: 2.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows internal CSS styling to be included in the content of the page.

== Description ==
- Use the CSS block to add custom CSS.
- Use the GeSHi block to syntax highlight: CSS, HTML, JavaScript, jQuery, PHP, MySQL or None.

For backward compatibility:

- Use the [bw_css] shortcode to add custom CSS as and when you need it.

For designers, developers and documenters [bw_css] supports echoing of the custom CSS, allowing you to document the CSS you are using.
For readability, the CSS is processed using the Generic Syntax Highlighter (GeSHi) processing.

- Use the [bw_geshi] shortcode for syntax highlighting of: CSS, HTML(5), JavaScript and jQuery, PHP and MySQL.
Also supports language: none.

If the oik base plugin is activated

- Use the [bw_autop] shortcode to disable or re-enable WordPress's wpautop() logic.

- Use the experimental [bw_background] shortcode to display an attached image in the background.

== Installation ==
1. Upload the contents of the oik-css plugin to the `/wp-content/plugins/oik-css' directory
1. Activate the oik-css plugin through the 'Plugins' menu in WordPress
1. Use the CSS and GeSHi blocks within your content.

== Frequently Asked Questions ==

= What are the dependencies? =

This code is no longer dependent upon the oik base plugin; it uses shared libraries.
If you want to use the shortcodes then using oik v3.3.7 or higher is still recommended.

= What is the syntax? =
`
[bw_css] your CSS goes here [/bw_css]
`

Note: The ending shortcode tag [/bw_css] is required

= How do I get the GeSHi output? =
Either
`
[bw_css .] your CSS goes here[/bw_css]
`

or
`
[bw_css text="Annotation to the CSS that will follow"] your CSS goes here[/bw_css]
`

= How do I get GeSHi output for other languages? =

Use the [bw_geshi] shortcode.
e.g.
[bw_geshi html]&lt;h3&gt;[bw_css], [bw_geshi] &amp; [bw_background]&lt;/h3&gt;&lt;p&gt;Cool, lazy smart shortcodes from oik-plugins.&lt;/p&gt;
[/bw_geshi]

Supported languages are:

* CSS
* HTML(5)
* JavaScript and jQuery
* PHP
* MySQL
* none

If you want to display syntax highlighted CSS without affecting the current display use [bw_geshi css].

= What version of GeSHi does oik-css use? =
oik-css delivers a subset of GeSHi version 1.0.9.0, which was released in May 2017, with modifications to support PHP 7.2

Only a small selection of the languages are supported by oik-css. These are the languages primarily used by WordPress.

Note: oik-css will only load the GeSHi code if it is not already loaded.

== Screenshots ==
1. CSS block example
2. GeSHi block example
3. [bw_css] - syntax and examples
4. [bw_geshi] - examples
5. oik-CSS options - available when oik is active

== Upgrade Notice ==
= 2.3.0 =
Update to remove the dependency on the ep() shared library function which is being renamed in oik-bwtrace

== Changelog ==
= 2.3.0 =
* Changed: Remove oik_css_load_script_textdomain_relative_path #26
* Changed: Update language files #26
* Changed: Update wp-scripts to v30.0.2 and package version to 2.3.0 #26
* Changed: Remove dependency on bobbfunc's ep() #33
* Changed: Reconcile bobbfunc updates #33
* Tested: With WordPress 6.6.2 and WordPress Multisite
* Tested: With PHP 8.3
* Tested: With PHPUnit 9.6

== Further reading ==
If you want to read more about the oik plugins then please visit the
[oik plugin](https://www.oik-plugins.com/oik)
**"OIK - OIK Information Kit"**
