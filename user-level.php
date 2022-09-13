<?php
/**
 * Plugin Name: GAMINGDOME USER LEVEL
 * Plugin URI: 
 * Description: 
 * Version: 1.0
 * Author: WDRaihan
 * Author URI: 
 * Text Domain: user-level
 * Domain Path: /languages
 * License: GPL-2.0+
 */
 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class User_Level {
    public function __construct(){
        define( 'USER_LEVEL_URL', plugin_dir_url( __FILE__ ) );
        define( 'USER_LEVEL_ASSTES', USER_LEVEL_URL.'/assets');
        define( 'USER_LEVEL_PATH', plugin_dir_path( __FILE__ ) );
        define( 'USER_LEVEL_BANNERS', USER_LEVEL_ASSTES.'/banners' );
        define( 'USER_LEVEL_CLASSES__FILE__', __FILE__ );
        add_action( 'wp_enqueue_scripts', array($this, 'wp_scripts') );
        
        /*
        * Seller levels
        */
        //Change Level 0
        define( 'SELLER_LEVEL_0', USER_LEVEL_BANNERS.'/seller/LV0.png' );
        
        //Change Level 1
        define( 'SELLER_LEVEL_1', USER_LEVEL_BANNERS.'/seller/LV1.gif' );
        
        //Change Level 2
        define( 'SELLER_LEVEL_2', USER_LEVEL_BANNERS.'/seller/LV2.gif' );
        
        //Change Level 3
        define( 'SELLER_LEVEL_3', USER_LEVEL_BANNERS.'/seller/LV3.gif' );
        
        //Change Level 4
        define( 'SELLER_LEVEL_4', USER_LEVEL_BANNERS.'/seller/LV4.gif' );
        
        //Change Level 5
        define( 'SELLER_LEVEL_5', USER_LEVEL_BANNERS.'/seller/LV5.gif' );
        
        /*
        * Buyer levels
        */
        //Change Level 1
        define( 'BUYER_LEVEL_0', USER_LEVEL_BANNERS.'/buyer/LV0.png' );
        
        //Change Level 1
        define( 'BUYER_LEVEL_1', USER_LEVEL_BANNERS.'/buyer/LV1.gif' );
        
        //Change Level 2
        define( 'BUYER_LEVEL_2', USER_LEVEL_BANNERS.'/buyer/LV2.gif' );
        
        //Change Level 3
        define( 'BUYER_LEVEL_3', USER_LEVEL_BANNERS.'/buyer/LV3.gif' );

        /*Level requirements*/
        
        //Requirements of level 1
        define( 'SELLER_LEVEL_1_SELES', 10 );
        define( 'SELLER_LEVEL_1_ORDERS', 5 );
        define( 'SELLER_LEVEL_1_RATING', 90 );
        
        //Requirements of level 2
        define( 'SELLER_LEVEL_2_SELES', 100 );
        define( 'SELLER_LEVEL_2_ORDERS', 10 );
        define( 'SELLER_LEVEL_2_RATING', 94 );
        
        //Requirements of level 3
        define( 'SELLER_LEVEL_3_SELES', 500 );
        define( 'SELLER_LEVEL_3_ORDERS', 100 );
        define( 'SELLER_LEVEL_3_RATING', 97 );
        
        //Requirements of level 4
        define( 'SELLER_LEVEL_4_SELES', 900 );
        define( 'SELLER_LEVEL_4_ORDERS', 200 );
        define( 'SELLER_LEVEL_4_RATING', 98 );
        
        //Requirements of level 5
        define( 'SELLER_LEVEL_5_SELES', 1200 );
        define( 'SELLER_LEVEL_5_ORDERS', 300 );
        define( 'SELLER_LEVEL_5_RATING', 99 );
        
        /*Buyer requirements*/
        
        //Seles for buyer level 1
        define( 'BUYER_LEVEL_1_SPENT', 70 );
        
        //Seles for buyer level 2
        define( 'BUYER_LEVEL_2_SPENT', 699 );
        
        //Seles for buyer level 3
        define( 'BUYER_LEVEL_3_SPENT', 4900 );
        
        
        //Require function file
        require_once(USER_LEVEL_PATH.'/inc/functions.php');
    }
    
    //Enqueue scripts
    public function wp_scripts(){
        wp_enqueue_style('ul-styles', USER_LEVEL_ASSTES.'/css/style.css');
    }
}
new User_Level();

add_action('wp_footer', function(){
    /*$UL_Levels = new UL_Seller_Levels(1);
echo $UL_Levels->get_seller_level();*/
});
