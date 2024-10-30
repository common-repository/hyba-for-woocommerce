=== HyBa for Woocommerce ===
Contributors: konektou, ristoniinemets, mstannu, bindcreative
Tags: woocommerce, estonia, banklink, pangalink, payment gateway, hyba
Requires at least: 4.1
Tested up to: 5.8
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extends WooCommerce with HyBa.

== Description ==

This plugin consists of HyBa payment methods:

*   HyBa PAY banklink (iPizza protocol)

Code is not maintained. Original v1.4 source code: [Github](https://github.com/KonektOU/estonian-banklinks-for-woocommerce)


== Installation ==

1. Upload `hyba-for-woocommerce` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the `Plugins` menu in WordPress
3. Go to WooCommerce - Settings
4. Payment gateways will be available to be configured in "Checkout" settings

== Screenshots ==

1. -

== Changelog ==
= 1.5.0 =
* Changing service url

= 1.4.9 =
* Phone validation fix

= 1.4.8 =
* Missing logo fix
* PHP warnings fix

= 1.4.7 =
* Removed unsupported products
* Updated products limit
* Updated products description

= 1.4.6 =
* Added new payment method HyBa Plan
* Added marketing texts for each payment method (displayed on product page)
* Added Demo server checkbox for each payment method (prelive.hyba.ee)
* Plugin will send customer email and phone from order details to HyBa

= 1.4.5 =
* Hardcoded service URL's
* Update payment method logos

= 1.4.4 =
* Added payment method limits (min,max)

= 1.4.3 =
* Added new payment method HyBa Split

= 1.4.2 =
* Added VK_EMAIL and VK_PHONE to IPizza request from merchant to bank
* Added default values for Title and Description
* Removed bank public cert field and hardcoded hyba public key

= 1.4.1 =
* Added HyBa PAY banklink
* Removed all other payment methods