=== LSC Blocks ===
Contributors: gurn1
Author: Luke Clifton
Author URI: https://www.lscwebdesign.co.uk
Tags: blocks, project block, faq, accordion
Requires at least: 6.7
Tested up to: 7.0
Requires PHP: 8.0
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add custom Gutenberg blocks, including a dynamic Project Archive block and an FAQ Accordion block.

== Description ==

LSC Blocks is built using an MVC architecture with PSR-4 autoloading through Composer. This structure keeps the codebase clean and well-organised, which is something I personally value when working on scalable WordPress projects. Separating concerns into models, views, and controllers makes the system easier to understand, extend, and maintain over time.

Where appropriate, abstract classes are used to enforce consistency and shared behaviour across components, helping ensure predictable structure as the plugin grows. Not every block needs the full stack, though — blocks are only given a controller and model where server-side data is genuinely required, so simpler blocks stay lightweight rather than carrying unused structure.

The plugin currently includes two Gutenberg blocks, located in `blocks/src`. Additional blocks can be added easily using the same pattern.

= Projects Grid =

Displays custom post type data in a responsive grid, with taxonomy-based filtering handled via a REST endpoint.

* Custom Post Type: "Projects"
* Custom Taxonomy: "Service Areas"
* REST Route: `projects-grid` at `lsc-blocks/v1/`
* Block widget: **Projects Grid**

= FAQ Accordion =

A parent/child block pair (**FAQ Accordion** and **FAQ Item**) for building expandable question-and-answer content directly in the editor.

* Fully authored in the block editor — no custom post type or database table required
* Optional single- or multiple-item-open behaviour, set per block instance
* Author-defined categories (set on the parent block), with an optional client-side filter dropdown on the frontend
* Optional dynamic heading that reflects the currently selected filter category, with a configurable label for the "all categories" state
* Configurable heading level, so the block's markup can slot correctly into a page's existing heading hierarchy
* Text alignment and item spacing controls, exposed in the block's Styles panel
* Three selectable block styles: **Bordered** (default), **Minimal**, and **Card**
* Automatically outputs `FAQPage` JSON-LD structured data alongside the visible markup, so eligible pages can qualify for rich FAQ results in search
* Built with the WordPress Interactivity API — no page reload, and filtering/expanding both work with JavaScript-driven state rather than custom event handling
* Themeable via CSS custom properties (border colour, accent colour, spacing, border radius), so a theme can restyle the block without editing plugin files
* Respects `prefers-reduced-motion`, and collapsed answers are marked `inert` so they're skipped by keyboard and screen-reader navigation

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.

Alternatively:

1. Upload the plugin zip file through Plugins > Add New > Upload Plugin.
2. Click **Install Now** and then **Activate Plugin**.

*Please ensure the main folder name matches the plugin slug "lsc-blocks" before zipping for deployment. GitHub ZIPs often append the branch name, which can prevent proper installation.*

== How to Use ==

= Projects Grid =

1. Create taxonomy terms under the Projects tab.
2. Add project posts using the "Add New" link in the Projects tab.
3. Edit the page where you want to display the Projects Grid.
4. In the Gutenberg editor, click the **+** icon to open the block selector.
5. Search for and select the **Projects Grid** block.
6. Hit **Publish/Save** — no additional backend options are required.

= FAQ Accordion =

1. In the Gutenberg editor, click the **+** icon and select the **FAQ Accordion** block.
2. Add one or more **FAQ Item** blocks inside it, each with a question and answer.
3. Optionally, open the block's settings sidebar to add categories, and assign a category to each FAQ Item from its own settings panel.
4. Configure behaviour (single/multiple open, heading level, category heading) from the **Behavior** panel, and appearance from the **Styles** tab.
5. Hit **Publish/Save**.

== Other Notes ==

* This plugin passes WordPress Plugin Check standards and is ready for repository submission. Frontend accessibility has been tested using the Silktide extension.
* All user-facing strings use WordPress i18n functions, making the plugin suitable for multilingual environments.

== Changelog ==

= 1.1.0 =
* Added the FAQ Accordion block (with FAQ Item child block): author-managed categories, optional filtering, configurable heading levels, three block styles, and FAQPage structured data output.

= 1.0.0 =
* Initial build.