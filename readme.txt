=== WooCommerce Product SKU Generator ===
Contributors: beka.rice, skyverge
Tags: woocommerce, sku
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=paypal@skyverge.com&item_name=Donation+for+WooCommerce+SKU+Generator
Requires at least: 3.5
Tested up to: 3.8.2
Requires WooCommerce at least: 2.0
Tested WooCommerce up to: 2.1.7
Stable Tag: 0.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Automatically generate WooCommerce product SKUs from the product slug and variation attributes.

== Description ==

Generate a descriptive SKU for products (when the product is published or updated) that uses the product slug. If the product is a variable product, this plugin can append attributes for each variation to the SKU to differentiate between them.

**IMPORTANT:** The SKU field will be disabled for simple products and parent products when you install this plugin so that it can't be edited. Your own SKUs previously assigned to products will be overridden if you update a product, and they will change if you change your product slug. **ONLY** use this plugin if you want to automatically create all SKUs in your shop, or do not need to override them with some of your own.

= Features =
 - automatically generate SKUs when a product is published or updated using the product slug
 - optionally generates SKUs for product variations using the parent slug + variation attributes
 - use the bulk product update action to easily generate SKUs for products created before installing this plugin.

= SKUs for all product types =
The WooCommerce Product SKU generator allows you to determine whether all products, including product variations, should have generated SKUs, or if only simple products (and the parent product for a variable product) should have automatically assigned SKUs.

Regardless of which option is selected, product SKUs will be generated any time a product is published or updated. For example, a product with a slug 'awesome-tee-shirt' will have an SKU of 'awesome-tee-shirt' (even if this is a parent product of a variable product). The SKU can change if the product slug changes.

= Variable Products =
You can enable SKU creation for variable products under **WooCommerce &gt; Settings &gt; Products** as well. Should you choose to create unique SKUs for each product variation, the attributes for that variation will be appended to the product slug.

For a variable product whose parent is 'awesome-tee-shirt', the SKU will first use the parent slug, then append the attributes for each variation. For example, if the shirt has a small variation in white, the SKU for that variation will be 'awesome-tee-shirt-small-white'.

If you disable this option, you will be able to manually create your own variation SKUs, as these will not be overridden by saving or updating a product. The parent SKU will be automatically set no matter which option is enabled.

= Bulk Updating =
You can bulk add SKUs to products that you've created prior to installing this plugin. If you select the products you want to update, then bulk edit them, all you have to do is hit "Update". When the products are saved, SKUs will be generated for all products.

This action will also automatically generate the SKUs for product variations if you have them enabled.

= More Details =
 - See the [product page](http://www.skyverge.com/product/woocommerce-product-sku-generator/) for full details and documentation
 - View more of SkyVerge's [free WooCommerce extensions](http://profiles.wordpress.org/skyverge/)
 - View all [SkyVerge WooCommerce extensions](http://www.skyverge.com/shop/)

== Installation ==

1. Upload the entire 'woocommerce-product-sku-generator' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **WooCommerce &gt; Settings &gt; Products** and scroll down to 'Product Data'. Here you have the option to generate SKUs for only simple / parent products, or to enable SKU generation for product variations as well.
4. View [documentation on the product page](http://www.skyverge.com/product/woocommerce-product-sku-generator/) for more help if needed.

**NOTE that** any time a product is updated, its SKU will be generated, so this may override old SKUs if you update products. This plugin is meant for complete SKU automation.

== Frequently Asked Questions ==
= What happens to my old SKUs? =
This plugin generates SKUs any time products are created or updated. Your old SKUs will be overridden if you use it - **only** use the plugin if you don't want to manage SKUs yourself.

= What happens to variation SKUs? =
All variation SKUs can be overridden, regardless of whether you're automatically generating them or not.

= How do I add SKUs to old products? =
Select the products you'd like to generate SKUs for under **Products**. Go to the bulk actions in the top left and click "Edit", then apply. All you need to do is hit "Update" to save these products, and SKUs will automatically be added.

= This is handy! Can I contribute? =
Yes you can! Join in on our [GitHub repository](https://github.com/bekarice/woocommerce-product-sku-generator/) and submit a pull request :)

= Do you plan on adding anything to this plugin? =
Here are a couple of features we plan on adding in the future, but have no ETA for:
 1. Allow manual override for simple / parent product SKUs
 2. Option to automatically create numbered SKU rather than using product slugs
 
We would love some help in adding these features if you're interested in contributing!

== Screenshots ==
1. Plugin Settings
2. Automatic SKU generation upon publish / update
3. Automatic variation SKUs based on attributes (if enabled)

== Changelog ==
= 2014.04.15 - version 0.1 =
 * Initial Release
