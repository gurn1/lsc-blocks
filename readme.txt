=== LSC Blocks ===
Contributors: gurn1
Author: Luke Clifton
Author URI: https://www.lscwebdesign.co.uk
Tags: blocks, gutenberg, faq, reviews, feedback
Requires at least: 6.7
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A collection of Gutenberg blocks: a filterable project grid, an FAQ accordion, a "was this helpful?" widget, and cached Google reviews.

== Description ==

LSC Blocks adds four independent Gutenberg blocks, each covering a common site-building need:

* **Projects Grid** — a responsive grid pulled from a custom Projects post type, with taxonomy-based filtering.
* **FAQ Accordion** — expandable question-and-answer content, authored directly in the block editor, with structured data for search engines.
* **Was This Helpful?** — a thumbs up/down feedback widget for any post or page.
* **Google Reviews** — displays cached reviews for a configured business location, refreshed on a schedule you control.

Each block only registers the backend functionality it actually needs — a purely presentational block adds no database tables or REST routes, while a data-driven block only queries what it displays. Individual blocks can be switched off from **Settings → LSC Blocks → General** if you don't need all of them on a given site.

= Projects Grid =

* Registers a "Projects" custom post type and a "Service Areas" taxonomy.
* Displays projects in a three-column grid, filterable by taxonomy term.
* Filtering happens client-side where possible, falling back to a REST request for anything not already on the page.

= FAQ Accordion =

* Two blocks working together: **FAQ Accordion** (the container) and **FAQ Item** (each question/answer pair).
* No custom post type — content is authored directly in the editor and stored with the page.
* Author-defined categories, with an optional frontend filter dropdown.
* Choice of single-item-open or multiple-items-open behaviour.
* Configurable heading level, so the block's markup fits correctly into a page's existing heading structure.
* Three selectable block styles (Bordered, Minimal, Card), plus text alignment and spacing controls.
* Outputs `FAQPage` structured data (JSON-LD) alongside the visible content, so eligible pages can qualify for rich results in search.
* Built on the WordPress Interactivity API — expanding and filtering both happen without a page reload.

= Was This Helpful? =

* A simple thumbs up/down widget for gathering feedback on a post.
* Vote counts are stored in post meta — no custom database table required.
* One vote per visitor per post, remembered locally in the browser.

= Google Reviews =

* Displays your business's Google reviews, pulled via the Google Places API.
* Reviews are fetched on a schedule (daily, weekly, or monthly — configurable per block) and cached, rather than queried live on every page view.
* Search for your business by name directly from the block's settings — no need to look up a Place ID manually.
* List, grid, and slider display modes, with configurable cards-per-slide.
* If no API key is configured yet, the block displays sample data so you can build and style your page before going live.

= Business Info =

A **Business Info** settings page (under **Tools**) stores your phone number, email, address, and opening hours in one place. Once saved, this data can be bound directly into core WordPress blocks — a Paragraph, Heading, or Button — anywhere on the site, using the Block Bindings API. Update it once in Settings, and every bound block updates automatically, including a live "today's hours" value.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin through the **Plugins** menu in WordPress.

Alternatively:

1. Upload the plugin's zip file via **Plugins → Add New → Upload Plugin**.
2. Click **Install Now**, then **Activate Plugin**.

== Frequently Asked Questions ==

= Do I need a Google API key to use the Google Reviews block? =

No. Without a key configured, the block displays sample review data so you can lay out and style the block. Add a key under **Settings → LSC Blocks → Google Reviews** at any point to switch to live data.

= How often does the Google Reviews block check for new reviews? =

However often you choose per block — daily, weekly, or monthly. Reviews are cached and refreshed on that schedule via WP-Cron, rather than fetched on every page load. Note that WP-Cron relies on site traffic to trigger scheduled tasks; on low-traffic sites, a refresh may be slightly delayed until the next visit after it's due.

= Can I turn off blocks I'm not using? =

Yes. **Settings → LSC Blocks → General** lists every block registered by the plugin, each with its own toggle.

= Does the FAQ Accordion block need a database table? =

No — questions and answers are authored directly in the block editor and stored as part of the page content, the same as any other block.

== Changelog ==

= 1.2.0 =
* Added the Google Reviews block, with scheduled fetching, caching, and a place-search tool in the block settings.
* Added the "Was This Helpful?" feedback block.
* Added a Business Info settings page and a matching Block Bindings source, so core blocks can be bound to centrally managed contact details.
* Added a General settings tab for enabling/disabling individual blocks.
* Consolidated plugin settings under Settings → LSC Blocks.

= 1.1.0 =
* Added the FAQ Accordion block (with FAQ Item child block): author-managed categories, optional filtering, configurable heading levels, three block styles, and FAQPage structured data output.

= 1.0.0 =
* Initial release, with the Projects Grid block.