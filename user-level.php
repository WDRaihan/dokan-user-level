<?php
/**
 * Plugin Name: User Level
 * Plugin URI: 
 * Description: 
 * Version: 1.0
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
        
        /*
        * Seller levels
        */
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
        define( 'BUYER_LEVEL_1', USER_LEVEL_BANNERS.'/buyer/LV1.gif' );
        
        //Change Level 2
        define( 'BUYER_LEVEL_2', USER_LEVEL_BANNERS.'/buyer/LV2.gif' );
        
        //Change Level 3
        define( 'BUYER_LEVEL_3', USER_LEVEL_BANNERS.'/buyer/LV3.gif' );

        
        //Require function file
        require_once(USER_LEVEL_PATH.'/inc/functions.php');
    }
}
new User_Level();

$UL_Levels = new UL_Levels(1);
echo $UL_Levels->get_seller_level();

