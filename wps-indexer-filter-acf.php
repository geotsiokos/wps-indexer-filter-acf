<?php
/**
 * Plugin Name: WPS Indexer Filter Acf
 * Plugin URI: https://www.itthinx.com
 * Description: Include ACF Field Name synonymous in WPS index.
 * Version: 1.0.0
 * Author: itthinx
 * Author URI: https://www.itthinx.com
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class WPS_Indexer_Filter_Acf {

	public static function boot() {
		add_filter( 'woocommerce_product_search_indexer_filter_content', array( __CLASS__, 'woocommerce_product_search_indexer_filter_content' ), 10, 3 );
		add_action( 'acf/save_post', array( __CLASS__, 'acf_save_post' ), 10, 1 );
		add_filter( 'acf/update_value', array( __CLASS__, 'acf_update_value' ), 10, 3 );
	}

	public static function acf_save_post( $post_id ) {
		$product = wc_get_product( $post_id );
		if ( $product ) {
			$indexer = new WooCommerce_Product_Search_Indexer();
			$indexer->index( $post_id );
		}
	}

	public static function acf_update_value( $value, $post_id, $field ) {
		$product = wc_get_product( $post_id );
		if ( $product ) {
			$indexer = new WooCommerce_Product_Search_Indexer();
			$indexer->index( $post_id );
		}
		return $value;
	}

	public static function woocommerce_admin_process_product_object( $product ) {
		$indexer = new WooCommerce_Product_Search_Indexer();
		$indexer->index( $product->get_id() );
	}

	public static function woocommerce_product_search_indexer_filter_content( $content, $context, $post_id ) {
		if ( $context === 'post_content' ) {
			$fields = array( 'synonymous', 'common_name' );
			$meta_values = array();
			$product = wc_get_product( $post_id );
			if ( $product ) {
				foreach ( $fields as $meta_key ) {
					$meta_value = $product->get_meta( $meta_key );
					if ( !empty( $meta_value ) && is_string( $meta_value ) ) {
						$meta_values[] = $meta_value;
					}
				}
				if ( count( $meta_values ) > 0 ) {
					$content .= ' ' . implode( ' ', $meta_values );
				}
			}
		}
		return $content;
	}
}
WPS_Indexer_Filter_Acf::boot();
