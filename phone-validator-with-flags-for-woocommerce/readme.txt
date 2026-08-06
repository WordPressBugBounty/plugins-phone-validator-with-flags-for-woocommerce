=== Phone Validator with Flags for WooCommerce ===
Contributors: mokhtarbsaid
Tags: woocommerce, checkout, phone validation, phone field, country flags
Requires at least: 6.8
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a country flag and phone validation to the WooCommerce checkout phone field. Supports Classic and Block Checkout.

== Description ==

**Phone Validator with Flags for WooCommerce** enhances the billing phone field on the checkout page by:

- Displaying a dropdown with country flags and dial codes.
- Auto-detecting the default country based on your WooCommerce store location.
- Validating the phone number format before order submission.
- Respecting WooCommerce's "Selling Locations" settings to limit the list of countries.
- Supporting both Classic Checkout and Block Checkout (introduced in WooCommerce 8+).
- Allowing full customization via a dedicated settings page under WooCommerce → Settings → Phone Validator.
- Supporting the shipping phone field independently from the billing phone field.

Ideal for WooCommerce stores targeting international customers who want to ensure clean, valid phone numbers during checkout.

Have a question or need help? [Contact the developer](https://mokhtarbensaid.com/contact/).

== Features ==

* Add country flags and dial codes to the checkout phone field.
* Validate phone numbers in real-time before submission.
* Full support for WooCommerce Block Checkout (Gutenberg).
* Supports billing phone, shipping phone, and My Account phone fields independently.
* Set preferred countries to appear at the top of the dropdown with a visual divider.
* Choose between all countries or only WooCommerce Selling Locations.
* Shipping phone field uses WooCommerce Shipping Locations automatically.
* Inline error messages styled to match WooCommerce notices.
* Respects WooCommerce field validation rules automatically.
* Compatible with WordPress 7 and WooCommerce 10+.
* GDPR-safe - no external APIs, fully self-hosted.
* Compatible with High-Performance Order Storage (HPOS).
* Lightweight and translation-ready.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Ensure that WooCommerce is active and "Selling Locations" are configured.
4. Go to WooCommerce → Settings → Phone Validator to configure the plugin.
5. The billing phone field on the checkout page will now display with country flag and validation.

== Libraries Used ==

This plugin includes the following third-party open source library:

* [intl-tel-input](https://github.com/jackocnr/intl-tel-input) v29.0.1 - JavaScript international phone input utility.
  Source: https://github.com/jackocnr/intl-tel-input
  License: MIT

== Frequently Asked Questions ==

= Does this plugin send data to third-party services? =
No. All assets and logic are fully self-hosted. No external scripts, APIs, or CDNs are used.

= Does this plugin work with WooCommerce Block Checkout? =
Yes. Since version 2.0.0 the plugin fully supports Block Checkout introduced in WooCommerce 8+.

= Does this plugin work with custom phone fields? =
Currently it targets the default WooCommerce billing_phone, shipping_phone, and My Account phone fields.

= What if the user enters an invalid phone number? =
An inline error message will appear styled to match WooCommerce notices, and the order will not be submitted until a valid number is entered.

= Does it support HPOS? =
Yes. This plugin officially declares compatibility with WooCommerce High-Performance Order Storage.

= Can I set a default country? =
Yes. You can set a default country from WooCommerce → Settings → Phone Validator. The default behavior uses your WooCommerce store country automatically.

= Can I show preferred countries at the top of the list? =
Yes. You can select preferred countries from the settings page and they will appear at the top of the dropdown, separated from the rest by a visual divider.

= Does it support the shipping phone field? =
Yes. You can enable the shipping phone field independently from WooCommerce → Settings → Phone Validator. The shipping phone dropdown will only show countries from your WooCommerce Shipping Locations.

= Is it compatible with WordPress 7 and the latest WooCommerce? =
Yes. The plugin is fully tested and compatible with WordPress 7 and WooCommerce 10+.

= I need help or have a suggestion. How can I contact the developer? =
You can reach the developer through the [contact page](https://mokhtarbensaid.com/contact/).

== Screenshots ==

1. Enhanced checkout phone field with country flag and dial code.
1. Enhanced Account addresses phone field with country flag and dial code.
2. Plugin settings page part 1 under WooCommerce → Settings → Phone Validator .
3. Plugin settings page part 2 under WooCommerce → Settings → Phone Validator.

== Changelog ==

= 1.0.0 =
* Initial release with flag selector, validation, and WooCommerce integration.

= 1.1.0 =
* Fix some technical issues about the error alert.

= 1.2.0 =
* Fix some technical issues.

= 1.3.0 =
* Fix compatibility issues.

= 2.0.0 =
* Full support for WooCommerce Block Checkout (Gutenberg).
* Added dedicated settings page under WooCommerce → Settings → Phone Validator.
* Added support for shipping phone field with Shipping Locations filtering.
* Added support for My Account phone field.
* Added preferred countries option with visual divider.
* Added inline error messages styled to match WooCommerce notices.
* Updated intl-tel-input library from v18.1.1 to v29.0.1.
* Default country now uses WooCommerce store location automatically.
* Fixed double initialization bug.
* Compatible with WordPress 7 and WooCommerce 10+.
* Code refactored and split into dedicated classes.

== Upgrade Notice ==

= 1.0.0 =
This is the initial stable release.

= 2.0.0 =
Major update. Adds Block Checkout support, settings page, shipping phone field, and updated intl-tel-input library. Compatible with WordPress 7 and WooCommerce 10+. Recommended for all users.