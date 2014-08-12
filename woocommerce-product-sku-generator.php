<?php
/**
 * Plugin Name: WooCommerce Product SKU Generator
 * Plugin URI: http://www.skyverge.com/product/woocommerce-product-sku-generator/
 * Description: Automatically generate SKUs for products using the product slug and (optionally) variation attributes
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com/
 * Version: 1.1
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
 * Generate a SKU for new products that is equal to the product slug (simple products).
 * Optionally append attributes for a product variation to this if the product is variable.
 * Or, set a SKU for a parent product and optionally append attributes to this for variation SKUs.
 *
 * For example, if parent SKUs are generated, a product with the slug 'wp-tee-shirt'
 * will have a SKU of 'wp-tee-shirt'. If variation SKUs are also generated, this SKU will
 * be used, and attributes will be appended. For a white tee shirt in small, the SKU will
 * be 'wp-tee-shirt-small-white'. If only variation SKUs are generated, then the parent SKU
 * will be set and used, and attributes will be appended to it.
 */

/**
 * Update product SKUs using the generated SKU
 *
 * @since 1.0
 */
function wc_sku_generator_update_product( $post_id, $post ) {

	$product = get_product( $post_id );

	wc_sku_generator_update_sku( $product );

}
add_action( 'woocommerce_process_product_meta', 'wc_sku_generator_update_product', 100, 2 );


/**
 * Generate a product SKU from the product slug and/or variation attributes
 *
 * @since 1.0
 */
function wc_sku_generator_update_sku( $product ) {

	$generate_skus = get_option( 'wc_sku_generator_select' );

	// Only generate SKUs for product variations if enabled
	if ( 'all' == $generate_skus ) {
		$sku = $product->get_post_data()->post_name;

		if ( $product->is_type( 'variable' ) ) {

			foreach( $product->get_available_variations() as $variation ) {

				$variation_sku = implode( $variation['attributes'], '-' );
				$variation_sku = str_replace( 'attribute_', '', $variation_sku );

				update_post_meta( $variation['variation_id'], '_sku', $sku . '-' . $variation_sku );

			}

			update_post_meta( $product->id, '_sku', $sku );

		} else {

			update_post_meta( $product->id, '_sku', $sku );
		}

	} elseif ( 'simple' == $generate_skus ) {
		$sku = $product->get_post_data()->post_name;

		update_post_meta( $product->id, '_sku', $sku );

	} else {
		$sku = $product->get_sku();

		if ( $product->is_type( 'variable' ) ) {

			foreach ( $product->get_available_variations() as $variation ) {

				$variation_sku = implode( $variation['attributes'], '-' );
				$variation_sku = str_replace( 'attribute_', '', $variation_sku );

				update_post_meta( $variation['variation_id'], '_sku', $sku . '-' . $variation_sku );

			}

			update_post_meta( $product->id, '_sku', $sku );

		}
	}

}
add_action( 'woocommerce_product_bulk_edit_save', 'wc_sku_generator_update_sku');


/**
 * Only disable SKU field if parent SKUs are being generated
 *
 * @since 1.0.2
 */
$sku_settings = get_option( 'wc_sku_generator_select' );

if ( $sku_settings !== 'variations' ) {

	//Disable SKUs temporarily so we can create our own label
	function wc_sku_generator_disable_sku_input() {
		add_filter( 'wc_product_sku_enabled', '__return_false' );
	}
	add_action( 'woocommerce_product_write_panel_tabs', 'wc_sku_generator_disable_sku_input' );

	//Create a custom SKU label for Product Data
	function wc_sku_generator_create_sku_label() {
		global $thepostid;

		?><p class="form-field"><label><?php esc_html_e( 'SKU', 'woocommerce' ); ?></label><span><?php echo esc_html( get_post_meta( $thepostid, '_sku', true ) ); ?></span></p><?php

		add_filter( 'wc_product_sku_enabled', '__return_true' );
	}
	add_action( 'woocommerce_product_options_sku', 'wc_sku_generator_create_sku_label' );
}


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
				'title'    => __( 'Generate SKUs for:', 'woocommerce' ),
				'desc'     => '<br/>' . __( 'Should SKUs be generated for simple/parent products only, variations only, or both?', 'woocommerce' ),
				'id'       => 'wc_sku_generator_select',
				'type'     => 'select',
				'options'  => array(
					'all'        => __( 'Both parent products and variations', 'woocommerce' ),
					'simple'     => __( 'Only for simple or parent products', 'woocommerce' ),
					'variations' => __( 'Only for variations of the product', 'woocommerce' ),
				),
				'default'  => 'all',
				'class'    => 'chosen_select',
				'css'      => 'min-width:300px;',
				'desc_tip' => __( 'Choosing "Only variations" will allow you to set a parent SKU. The SKU field is disabled otherwise.', 'woocommerce' ),
			);

		}
	}

		return $updated_settings;

}
add_filter( 'woocommerce_product_settings', 'wc_sku_generator_add_settings' );
