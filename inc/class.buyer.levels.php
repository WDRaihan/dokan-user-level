<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UL_Buyer_Levels {
    public $current_user_id = '';
    public $buyer_id = '';
    
    public function __construct($buyer_id = null){
        
        if( $buyer_id == '' ) {
            $buyer_id = get_current_user_id();
        }
        
        $this->buyer_id = $buyer_id;
        
        //Set current date
        $this->end_date = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
        
        //Set current user id
        $this->current_user_id = get_current_user_id();
        
    }
    
    //Get seller level
    public function get_buyer_level(){
        
        $total_spent = $this->get_buyer_total_spent();
        
        $buyer_level = array();
        
        if( $total_spent >= BUYER_LEVEL_3_SPENT ){
            
            $buyer_level = array(
                'level'      => '3',
                'banner_url' => BUYER_LEVEL_3
            );
            
        }elseif( $total_spent >= BUYER_LEVEL_2_SPENT ){
            
            $buyer_level = array(
                'level'      => '2',
                'banner_url' => BUYER_LEVEL_2
            );
            
        }elseif( $total_spent >= BUYER_LEVEL_1_SPENT ){
            
            $buyer_level = array(
                'level'      => '1',
                'banner_url' => BUYER_LEVEL_1
            );
            
        }else {
            
            $buyer_level = array(
                'level'      => '0',
                'banner_url' => BUYER_LEVEL_0
            );
        }
        
        return $buyer_level;
        
    }
    
    //Get seles by day
    public function get_buyer_total_spent(){
        
        $total_spent = wc_get_customer_total_spent( $this->buyer_id );
        return $total_spent;
    }

}
//new UL_Seller_Levels();
