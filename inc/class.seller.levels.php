<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use WeDevs\Dokan\Cache;

class UL_Seller_Levels {
    public $start_date = '';
    public $end_date = '';
    public $current_user_id = '';
    public $seller_id = '';
    
    public function __construct($seller_id = null){
        
        if( $seller_id == '' ) {
            $seller_id = dokan_get_current_user_id();
        }
        
        $this->seller_id = $seller_id;
        
        //Set current date
        $this->end_date = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
        
        $curent = $this->end_date;

        $dt = new DateTime($curent);
        $dt->sub(new DateInterval('P30D'));
        
        //Set start date
        $this->start_date = $dt->format('Y-m-d');
        
        //Set current user id
        $this->current_user_id = get_current_user_id();
        
    }
    
    //Get seller level
    public function get_seller_level(){
        
        $total_seles = $this->get_seller_seles_by_day();
        $total_order = $this->get_seller_orders_by_day();
        $average_rating = $this->get_seller_average_rating_in_percentage();
        //$the_user = get_user_by( 'id', $this->seller_id );
        
        $seller_level = array();
        
        if( $total_seles >= SELLER_LEVEL_5_SELES && $total_order >= SELLER_LEVEL_5_ORDERS && $average_rating >= SELLER_LEVEL_5_RATING ){
            
            $seller_level = array(
                'level'      => '5',
                'banner_url' => SELLER_LEVEL_5
            );
            
        }elseif( $total_seles >= SELLER_LEVEL_4_SELES && $total_order >= SELLER_LEVEL_4_ORDERS && $average_rating >= SELLER_LEVEL_4_RATING ){
            
            $seller_level = array(
                'level'      => '4',
                'banner_url' => SELLER_LEVEL_4
            );
            
        }elseif( $total_seles >= SELLER_LEVEL_3_SELES && $total_order >= SELLER_LEVEL_3_ORDERS && $average_rating >= SELLER_LEVEL_3_RATING ){
            
            $seller_level = array(
                'level'      => '3',
                'banner_url' => SELLER_LEVEL_3
            );
            
        }elseif( $total_seles >= SELLER_LEVEL_2_SELES && $total_order >= SELLER_LEVEL_2_ORDERS && $average_rating >= SELLER_LEVEL_2_RATING ){
            
            $seller_level = array(
                'level'      => '2',
                'banner_url' => SELLER_LEVEL_2
            );
            
        }elseif( $total_seles >= SELLER_LEVEL_1_SELES && $total_order >= SELLER_LEVEL_1_ORDERS && $average_rating >= SELLER_LEVEL_1_RATING ){
            
            $seller_level = array(
                'level'      => '1',
                'banner_url' => SELLER_LEVEL_1
            );
            
        }else {
            
            $seller_level = array(
                'level'      => '0',
                'banner_url' => SELLER_LEVEL_0
            );
        }
        
        return $seller_level;
        
    }
    
    //Get seles by day
    public function get_seller_seles_by_day(){
        $total_sales = 0;
        
        $total_refunded = $this->get_seller_refunded_amount();

        $order_totals = $this->get_order_report_data( [
            'data' => [
                '_order_total' => [
                    'type'     => 'meta',
                    'function' => 'SUM',
                    'name'     => 'total_sales',
                ],
                'ID' => [
                    'type'     => 'post_data',
                    'function' => 'COUNT',
                    'name'     => 'total_orders',
                ],
            ],
            'filter_range' => true,
        ], $this->start_date, $this->end_date, $this->seller_id );

        $total_sales    = $order_totals->total_sales;
        
        $net_seles = ($total_sales - $total_refunded);
        return $net_seles;
    }
    
    //Get orders by day
    public function get_seller_orders_by_day(){
        $total_orders = 0;
        
        $order_totals = $this->get_order_report_data( [
            'data' => [
                '_order_total' => [
                    'type'     => 'meta',
                    'function' => 'SUM',
                    'name'     => 'total_sales',
                ],
                'ID' => [
                    'type'     => 'post_data',
                    'function' => 'COUNT',
                    'name'     => 'total_orders',
                ],
            ],
            'filter_range' => true,
        ], $this->start_date, $this->end_date, $this->seller_id );

        $total_orders   = absint( $order_totals->total_orders );
        
        return $total_orders;
    }
    
    //Get total orders
    public function get_seller_total_orders(){
        $total_orders = 0;
        
        $order_totals = $this->get_order_report_data( [
            'data' => [
                '_order_total' => [
                    'type'     => 'meta',
                    'function' => 'SUM',
                    'name'     => 'total_sales',
                ],
                'ID' => [
                    'type'     => 'post_data',
                    'function' => 'COUNT',
                    'name'     => 'total_orders',
                ],
            ],
            'filter_range' => true,
        ], '2020-01-01', '6020-01-01', $this->seller_id );

        $total_orders   = absint( $order_totals->total_orders );
        
        return $total_orders;
    }
    
    //Get average rating
    public function get_seller_average_rating($seller_id){
        
        global $wpdb;

        $result = $wpdb->get_row( $wpdb->prepare(
            "SELECT AVG(cm.meta_value) as average, COUNT(wc.comment_ID) as count FROM $wpdb->posts p
            INNER JOIN $wpdb->comments wc ON p.ID = wc.comment_post_ID
            LEFT JOIN $wpdb->commentmeta cm ON cm.comment_id = wc.comment_ID
            WHERE p.post_author = %d AND p.post_type = 'product' AND p.post_status = 'publish'
            AND ( cm.meta_key = 'rating' OR cm.meta_key IS NULL) AND wc.comment_approved = 1
            ORDER BY wc.comment_post_ID", $seller_id ) );

        $rating_value = apply_filters( 'dokan_seller_rating_value', array(
            'rating' => number_format( $result->average, 2 ),
            'count'  => (int) $result->count
        ), $seller_id );

        return $rating_value;
        
    }
    
    public function get_seller_average_rating_in_percentage(){
        
        $average_rating = $this->get_seller_average_rating($this->seller_id);
        
        $x = (int) $average_rating['rating'];
        $total = 5;
        $percentage = ($x*100)/$total;
        return $percentage;
    }
    
    public function get_seller_refunded_amount(){
        global $wpdb;
        
        $seller_id = $this->seller_id;
        if( $seller_id == '' ) {
            $seller_id = dokan_get_current_user_id();
        }

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT SUM(dr.refund_amount) FROM {$wpdb->posts} AS posts
                    INNER JOIN $wpdb->dokan_refund AS dr ON posts.ID = dr.order_id
                    WHERE posts.post_type = %s AND posts.post_status != %s
                        AND dr.status = %d AND seller_id = %d AND DATE(post_date) >= %s AND DATE(post_date) <= %s",
                'shop_order', 'trash', 1, $seller_id, $this->start_date, $this->end_date
            )
        );
    }
    
    //Order report data
    public function get_order_report_data( $args, $start_date, $end_date, $current_user = false ) {
        global $wpdb;

        if ( ! $current_user ) {
            $current_user = dokan_get_current_user_id();
        }

        $defaults = [
            'data'         => [],
            'where'        => [],
            'where_meta'   => [],
            'query_type'   => 'get_row',
            'group_by'     => '',
            'order_by'     => '',
            'limit'        => '',
            'filter_range' => false,
            'nocache'      => false,
            'debug'        => false,
        ];

        $args = wp_parse_args( $args, $defaults );

        extract( $args );

        if ( empty( $data ) ) {
            return false;
        }

        $select = [];

        foreach ( $data as $key => $value ) {
            $distinct = '';

            if ( isset( $value['distinct'] ) ) {
                $distinct = 'DISTINCT';
            }

            if ( $value['type'] == 'meta' ) {
                $get_key = "meta_{$key}.meta_value";
            } elseif ( $value['type'] == 'post_data' ) {
                $get_key = "posts.{$key}";
            } elseif ( $value['type'] == 'order_item_meta' ) {
                $get_key = "order_item_meta_{$key}.meta_value";
            } elseif ( $value['type'] == 'order_item' ) {
                $get_key = "order_items.{$key}";
            }

            if ( $value['function'] ) {
                $get = "{$value['function']}({$distinct} {$get_key})";
            } else {
                $get = "{$distinct} {$get_key}";
            }

            $select[] = "{$get} as {$value['name']}";
        }

        $query['select'] = 'SELECT ' . implode( ',', $select );
        $query['from']   = "FROM {$wpdb->posts} AS posts";

        // Joins
        $joins       = [];
        $joins['do'] = "LEFT JOIN {$wpdb->prefix}dokan_orders AS do ON posts.ID = do.order_id";

        foreach ( $data as $key => $value ) {
            if ( $value['type'] == 'meta' ) {
                $joins["meta_{$key}"] = "LEFT JOIN {$wpdb->postmeta} AS meta_{$key} ON posts.ID = meta_{$key}.post_id";
            } elseif ( $value['type'] == 'order_item_meta' ) {
                $joins['order_items']            = "LEFT JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON posts.ID = order_items.order_id";
                $joins["order_item_meta_{$key}"] = "LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta_{$key} ON order_items.order_item_id = order_item_meta_{$key}.order_item_id";
            } elseif ( $value['type'] == 'order_item' ) {
                $joins['order_items'] = "LEFT JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON posts.ID = order_id";
            }
        }

        if ( ! empty( $where_meta ) ) {
            foreach ( $where_meta as $value ) {
                if ( ! is_array( $value ) ) {
                    continue;
                }

                $key = is_array( $value['meta_key'] ) ? $value['meta_key'][0] : $value['meta_key'];

                if ( isset( $value['type'] ) && $value['type'] == 'order_item_meta' ) {
                    $joins['order_items']            = "LEFT JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON posts.ID = order_id";
                    $joins["order_item_meta_{$key}"] = "LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta_{$key} ON order_items.order_item_id = order_item_meta_{$key}.order_item_id";
                } else {
                    // If we have a where clause for meta, join the postmeta table
                    $joins["meta_{$key}"] = "LEFT JOIN {$wpdb->postmeta} AS meta_{$key} ON posts.ID = meta_{$key}.post_id";
                }
            }
        }

        $query['join'] = implode( ' ', $joins );

        $query['where'] = "
            WHERE   posts.post_type     = 'shop_order'
            AND     posts.post_status   != 'trash'
            AND     do.seller_id = {$current_user}
            AND     do.order_status IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', [ 'wc-completed', 'wc-processing', 'wc-on-hold' ] ) ) . "')
            AND     do.order_status NOT IN ('wc-cancelled','wc-refunded','wc-failed')
            ";

        if ( $filter_range ) {
            $query['where'] .= "
                AND     DATE(post_date) >= '" . $start_date . "'
                AND     DATE(post_date) <= '" . $end_date . "'
            ";
        }

        foreach ( $data as $key => $value ) {
            if ( $value['type'] == 'meta' ) {
                $query['where'] .= " AND meta_{$key}.meta_key = '{$key}'";
            } elseif ( $value['type'] == 'order_item_meta' ) {
                $query['where'] .= " AND order_items.order_item_type = '{$value['order_item_type']}'";
                $query['where'] .= " AND order_item_meta_{$key}.meta_key = '{$key}'";
            }
        }

        if ( ! empty( $where_meta ) ) {
            $relation = isset( $where_meta['relation'] ) ? $where_meta['relation'] : 'AND';

            $query['where'] .= ' AND (';

            foreach ( $where_meta as $index => $value ) {
                if ( ! is_array( $value ) ) {
                    continue;
                }

                $key = is_array( $value['meta_key'] ) ? $value['meta_key'][0] : $value['meta_key'];

                if ( strtolower( $value['operator'] ) == 'in' ) {
                    if ( is_array( $value['meta_value'] ) ) {
                        $value['meta_value'] = implode( "','", $value['meta_value'] );
                    }

                    if ( ! empty( $value['meta_value'] ) ) {
                        $where_value = "IN ('{$value['meta_value']}')";
                    }
                } else {
                    $where_value = "{$value['operator']} '{$value['meta_value']}'";
                }

                if ( ! empty( $where_value ) ) {
                    if ( $index > 0 ) {
                        $query['where'] .= ' ' . $relation;
                    }

                    if ( isset( $value['type'] ) && $value['type'] == 'order_item_meta' ) {
                        if ( is_array( $value['meta_key'] ) ) {
                            $query['where'] .= " ( order_item_meta_{$key}.meta_key   IN ('" . implode( "','", $value['meta_key'] ) . "')";
                        } else {
                            $query['where'] .= " ( order_item_meta_{$key}.meta_key   = '{$value['meta_key']}'";
                        }

                        $query['where'] .= " AND order_item_meta_{$key}.meta_value {$where_value} )";
                    } else {
                        if ( is_array( $value['meta_key'] ) ) {
                            $query['where'] .= " ( meta_{$key}.meta_key   IN ('" . implode( "','", $value['meta_key'] ) . "')";
                        } else {
                            $query['where'] .= " ( meta_{$key}.meta_key   = '{$value['meta_key']}'";
                        }

                        $query['where'] .= " AND meta_{$key}.meta_value {$where_value} )";
                    }
                }
            }

            $query['where'] .= ')';
        }

        if ( ! empty( $where ) ) {
            foreach ( $where as $value ) {
                if ( strtolower( $value['operator'] ) == 'in' ) {
                    if ( is_array( $value['value'] ) ) {
                        $value['value'] = implode( "','", $value['value'] );
                    }

                    if ( ! empty( $value['value'] ) ) {
                        $where_value = "IN ('{$value['value']}')";
                    }
                } else {
                    $where_value = "{$value['operator']} '{$value['value']}'";
                }

                if ( ! empty( $where_value ) ) {
                    $query['where'] .= " AND {$value['key']} {$where_value}";
                }
            }
        }

        if ( $group_by ) {
            $query['group_by'] = "GROUP BY {$group_by}";
        }

        if ( $order_by ) {
            $query['order_by'] = "ORDER BY {$order_by}";
        }

        if ( $limit ) {
            $query['limit'] = "LIMIT {$limit}";
        }

        $query      = apply_filters( 'dokan_reports_get_order_report_query', $query );
        $query      = implode( ' ', $query );
        $query_hash = md5( $query_type . $query );

        if ( $debug ) {
            printf( '<pre>%s</pre>', print_r( $query, true ) );
        }

        $cache_group = "report_data_seller_{$current_user}";
        $cache_key   = 'wc_report_' . $query_hash;

        $result = Cache::get_transient( $cache_key, $cache_group );
        if ( $debug || $nocache || ( false === $result ) ) {
            $result = apply_filters( 'dokan_reports_get_order_report_data', $wpdb->$query_type( $query ), $data );

            if ( $filter_range ) {
                
                $curent = date( 'Y-m-d', current_time( 'timestamp', 0 ) );

                $dt = new DateTime($curent);
                
                if ( $end_date === $dt->format('Y-m-d') ) {
                    $expiration = 60 * 60 * 1; // 1 hour
                } else {
                    $expiration = 60 * 60 * 24; // 24 hour
                }
            } else {
                $expiration = 60 * 60 * 24; // 24 hour
            }

            Cache::set_transient( $cache_key, $result, $cache_group, $expiration );
        }

        return $result;
    }
    
}
//new UL_Seller_Levels();
