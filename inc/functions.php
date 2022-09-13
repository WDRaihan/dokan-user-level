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
    $account_status = get_user_meta($store_id, 'user_status', true);
    
    $user = get_userdata( $store_id );
    $user_roles = $user->roles;
    
    if ( $account_status == 'deactive' ) {
        $banner_url = BANNED_BANNER;
        
    }elseif( in_array( 'administrator', $user_roles ) ){
        $banner_url = ADMIN_BANNER;
    }else{
        $banner_url = $seller_level['banner_url'];
    }
    
    $vendor = dokan()->vendor->get( $store_id );
    
    $ratings = $UL_Levels->get_seller_average_rating($store_id);
    
    if( $ratings['rating'] > 0 ){
        echo '<div class="dokan-seller-rating wd-rating"><a href="'.$vendor->get_shop_url().'reviews/">'.wp_kses_post( dokan_generate_ratings( $ratings['rating'], 5 ) ).' (Rated '.$ratings['rating'].' out of 5)</a></div>';
    }else{
        echo '<div class="dokan-seller-rating wd-rating">'.$ratings['rating'].'</div>';
    }
    
    echo 'Total orders: '.$UL_Levels->get_seller_total_orders();
    
    echo '<div class="user-level"><img src="'.esc_url($banner_url).'" alt="'.esc_html($seller_level['level']).'" /></div>';
    
}

add_action('dokan_seller_listing_after_store_data', 'ul_dokan_seller_listing_after_store_data', 10, 2);
function ul_dokan_seller_listing_after_store_data($seller, $info){
    $UL_Levels = new UL_Seller_Levels( $seller->ID );
    $seller_level = $UL_Levels->get_seller_level();
    $account_status = get_user_meta($seller->ID, 'user_status', true);
    
    $user = get_userdata( $seller->ID );
    $user_roles = $user->roles;
    
    if ( $account_status == 'deactive' ) {
        $banner_url = BANNED_BANNER;
        
    }elseif ( in_array( 'administrator', $user_roles ) ) {
        $banner_url = ADMIN_BANNER;
        
    }else{
        $banner_url = $seller_level['banner_url'];
    }
    

    $vendor = dokan()->vendor->get( $seller->ID );
    
    $ratings = $UL_Levels->get_seller_average_rating($seller->ID);
    if( $ratings['rating'] > 0 ){
        echo '<div class="dokan-seller-rating wd-rating"><a href="'.$vendor->get_shop_url().'reviews/">'.wp_kses_post( dokan_generate_ratings( $ratings['rating'], 5 ) ).' (Rated '.$ratings['rating'].' out of 5)</a></div>';
    }else{
        echo '<div class="dokan-seller-rating wd-rating">'.$ratings['rating'].'</div>';
    }
    
    echo '<div>Total orders: '.$UL_Levels->get_seller_total_orders().'</div>';
    
    echo '<div class="user-level"><img src="'.esc_url($banner_url).'" alt="'.esc_html($seller_level['level']).'" /></div>';
    
}

/*
* Buyer levels
*/
add_action('woocommerce_account_content', 'ul_woocommerce_account_content');
function ul_woocommerce_account_content(){
    $buyer_level_obj = new UL_Buyer_Levels();
    $buyer_level = $buyer_level_obj->get_buyer_level();
    $account_status = get_user_meta($buyer_level_obj->buyer_id, 'user_status', true);
    
    $user = get_userdata( $buyer_level_obj->buyer_id );
    $user_roles = $user->roles;
    
    if ( $account_status == 'deactive' ) {
        $banner_url = BANNED_BANNER;
        
    }elseif ( in_array( 'administrator', $user_roles ) ) {
        $banner_url = ADMIN_BANNER;
    }else{
        $banner_url = $buyer_level['banner_url'];
    }
    
    echo '<div class="user-level"><img src="'.esc_url($banner_url).'" alt="'.esc_html($buyer_level['level']).'" /></div>';
}
