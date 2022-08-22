<?php
/**
 * Plugin Name: WPS Indexer Filter Acf
 * Plugin URI: https://www.itthinx.com
 * Description: Test plugin for the woocommerce_product_search_indexer_filter_content filter.
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
	}

	public static function woocommerce_product_search_indexer_filter_content( $content, $context, $post_id ) {
		if ( $context === 'post_content' ) {
			$fields = array( 'composer', 'editor_arranger', 'series', 'format', 'genre', 'language', 'duration', 'grade', 'instrumentation' );
			$product = wc_get_product( $post_id );
			$meta_values = array();
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
		return $content;
	}
}
WPS_Indexer_Filter_Acf::boot();
