<?php
/**
 * Plugin Name: WooCommerce Product SKU Generator
 * Plugin URI: http://www.skyverge.com/product/woocommerce-product-sku-generator/
 * Description: Automatically generate SKUs for products using the product slug and (optionally) variation attributes
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com/
 * Version: 1.0.2
 * Text Domain: woocommerce-product-sku-generator
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2012-2014 SkyVerge, Inc. (info@skyverge.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Product-SKU-Generator
 * @author    SkyVerge
 * @category  Admin
 * @copyright Copyright (c) 2012-2014, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Plugin Description
 *
 * Generate a SKU for new products that is equal to the product slug (simple products)
 * Optionally append attributes for a product variation to this if the product is variable
 * For a variable product whose parent is 'wordpress-tee-shirt', the SKU will use the parent slug, then append the attributes for each variation. If the shirt has a small variation in white, the SKU will be 'wordpress-tee-shirt-small-white'.
 * SKUs should be created when a product is saved or updated.
 *
 */

/**
 * Generate a product SKU from the product slug
 *
 * @since 1.0
 */
function wc_sku_generator_update_product( $post_id, $post ) {

	$product = get_product( $post_id );

	wc_sku_generator_update_sku( $product );

}
add_action( 'woocommerce_process_product_meta', 'wc_sku_generator_update_product', 100, 2 );


/**
 * Update product SKUs for parent products or all products (inc variations) depending on settings
 *
 * @since 1.0
 */
function wc_sku_generator_update_sku( $product ) {

	$generate_variation_skus = get_option( 'wc_sku_generator_select' );

	$sku = $product->get_post_data()->post_name;

	// Only generate SKUs for product variations if enabled
	if( 'all' == $generate_variation_skus ) {

		if( $product->product_type == 'variable' ) {

			foreach( $product->get_available_variations() as $variation ) {

				$variation_sku = implode( $variation['attributes'], '-' );
				$variation_sku = str_replace( 'attribute_', '', $variation_sku );

				update_post_meta( $variation['variation_id'], '_sku', $sku . '-' . $variation_sku );

			}

			update_post_meta( $product->id, '_sku', $sku );

		} else {

			update_post_meta( $product->id, '_sku', $sku );
		}

	} else {

		update_post_meta( $product->id, '_sku', $sku );
	}

}
add_action( 'woocommerce_product_bulk_edit_save', 'wc_sku_generator_update_sku');


/**
 * Disable SKUs temporarily so we can create our own label
 *
 * @since 1.0.2
 */
function wc_sku_generator_disable_sku_input() {
	add_filter( 'wc_product_sku_enabled', '__return_false' );
}
add_action( 'woocommerce_product_write_panel_tabs', 'wc_sku_generator_disable_sku_input' );


/**
 * Create a custom SKU label for Product Data
 *
 * @since 1.0.2
 */
function wc_sku_generator_create_sku_label() {
	global $thepostid;

	?><p class="form-field"><label><?php esc_html_e( 'SKU', 'woocommerce' ); ?></label><span><?php echo esc_html( get_post_meta( $thepostid, '_sku', true ) ); ?></span></p><?php

	add_filter( 'wc_product_sku_enabled', '__return_true' );
}
add_action( 'woocommerce_product_options_sku', 'wc_sku_generator_create_sku_label' );


/**
 *Add SKU generator to Settings > Products page at the end of the 'Product Data' section
 *
 * @since 1.0
 */
function wc_sku_generator_add_settings( $settings ) {

	$updated_settings = array();

	foreach ( $settings as $setting ) {

		$updated_settings[] = $setting;

		if ( isset( $setting['id'] ) && 'woocommerce_review_rating_verification_required' === $setting['id'] ) {

			$updated_settings[] = array(
					'title' 	=> __( 'Generate SKUs for:', 'woocommerce' ),
					'desc' 		=> '<br/>' . __( 'Should product variations get unique SKUs, or only simple and parent products?', 'woocommerce' ),
					'id' 		=> 'wc_sku_generator_select',
					'type' 		=> 'select',
					'options' => array(
						'all'  			=> __( 'All Products including Variations', 'woocommerce' ),
						'simple' => __( 'Only for Simple or Parent Products', 'woocommerce' ),
						),
					'default'	=> 'all',
					'css' 		=> 'min-width:300px;',
					'desc_tip'	=>  __('Parent product SKUs for variable products will always be assigned, but you have the option to create your own variation SKUs.', 'woocommerce' ),

			);

		}
	}

		return $updated_settings;

}
add_filter( 'woocommerce_product_settings', 'wc_sku_generator_add_settings' );
