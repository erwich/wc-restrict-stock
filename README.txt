=== Restrict Stock for WooCommerce ===
Contributors: ewich
Donate link: https://wich.tech/donate
Tags: woocommerce, products, restrict, quantity
Requires at least: 5.0
Tested up to: 5.6.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A basic plugin that can enforce quantity limits for products in WooCommerce Orders.

== Description ==

This plugin came out of a use-case of a shop owner running a hybrid online and brick-and-mortar store, needing greater 
control of restrictions on selling products online. Imagine a local customer calls in and asks you to set aside a copy of a 
new board game, and you want to make sure that they don't all sell online. Or, you're pushing a hot new release and want to deter 
scalping bots and/or wholesale buyers from buying more than a handful of a certain product per transaction.

Enter Restrict Stock for WooCommerce - a very small, simple, and lightweight plugin that gives greater control over how the stock of your 
products are displayed and interacted with in the checkout process. Upon installation, a new group of new fields 
will be added to the _Product > Inventory_ interface for all products called "Quantity to restrict," "Restriction Notes," and "Quantity to Hide." 
Please see below for more information on these fields, and how they influence product stock and customers' user experience. 

    - **Quantity to Restrict:** If you wish to only allow a certain number of this product to be purchased per transaction, enter it here
    - **Restriction Notes:** If you are restricting amounts of this product per transaction, you can enter a custom error message here for when customers attempt to purchase more
    - **Quantity to Hide:** If you are reserving, or hiding, a certain number of product from being displayed in stock counts and being sold, enter that number here

== Changelog ==

= 1.0.0 =
* Initial release to the world!