=== Hiyield Blocks ===
Contributors: gurn1
Author: Luke Clifton
Author URI: https://www.lscwebdesign.co.uk
Tags: blocks, project block
Requires at least: 6.5
Tested up to: 6.9
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add custom Gutenberg blocks, including a dynamic Project Archive block

== Description ==

**Hiyield Blocks** is built using an MVC architecture with a PSR-4 autoloading through Composer. This approach clearly separates responsibilities and makes it easy to scale, keeping the code organized, maintainable, and structured. All code within the 'app' dir follows this MVC pattern.
Currently, the plugin includes one gutenberg block located in the blocks/src dir, Additional blocks can be added easily as needed. The included block, `projects-grid`, displays custom post type data in a responsive grid format.

== Key Features ==

* Custom Post Type: "Projects"
* Custom Taxonomy: "Service Areas"
* REST Route: "projects-grid" at "hiyield-blocks/v1/"
* Block widget: "Projects Grid"

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.

Alternatively:

1. Upload the plugin zip file through Plugins > Add New > Upload Plugin.
2. Click **Install Now** and then **Activate Plugin**.

*Please ensure the main folder name matches the plugin slug "hiyield-block" before zipping for deployment. GitHub ZIPs often append the branch name, which can prevent proper installation.*

== How to Use ==

From the admin panel:

1. Create taxonomy terms under the Projects tab.
2. Add project posts using the "Add New" link in the Projects tab.
3. Edit the page where you want to display the Projects Grid.
4. In the Gutenberg editor, click the **+** icon to open the block selector.
5. Search for and select the **Projects Grid** block.
6. Hit **Publish/Save** — no additional backend options are required.

== Other Notes ==

This plugin passes WordPress Plugin Check standards and is ready for repository submission. Frontend accessibility has been tested using the Silktide extension.

== Changelog == 

1.0.0 Initial build