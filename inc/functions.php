<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Include seller level
require_once(USER_LEVEL_PATH.'/inc/class.seller.levels.php');

//Include buyer level
require_once(USER_LEVEL_PATH.'/inc/class.buyer.levels.php');

/*
* Seller Levels
*/
add_action('dokan_store_header_info_fields', 'ul_dokan_store_header_info_fields', 10);
function ul_dokan_store_header_info_fields($store_id){
    $UL_Levels = new UL_Seller_Levels( $store_id );
    $seller_level = $UL_Levels->get_seller_level();
    
    echo '<div class="user-level"><img src="'.esc_url($seller_level['banner_url']).'" alt="'.esc_html($seller_level['level']).'" /></div>';
}

add_action('dokan_seller_listing_after_store_data', 'ul_dokan_seller_listing_after_store_data', 10, 2);
function ul_dokan_seller_listing_after_store_data($seller, $info){
    $UL_Levels = new UL_Seller_Levels( $seller->ID );
    $seller_level = $UL_Levels->get_seller_level();
    
    echo '<div class="user-level"><img src="'.esc_url($seller_level['banner_url']).'" alt="'.esc_html($seller_level['level']).'" /></div>';
}

/*
* Buyer levels
*/
add_action('woocommerce_account_content', 'ul_woocommerce_account_content');
function ul_woocommerce_account_content(){
    $buyer_level_obj = new UL_Buyer_Levels();
    $buyer_level = $buyer_level_obj->get_buyer_level();
    
    echo '<div class="user-level"><img src="'.esc_url($buyer_level['banner_url']).'" alt="'.esc_html($buyer_level['level']).'" /></div>';
}