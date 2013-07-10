<?php
global $wpdb;

$metadatas = "SELECT DISTINCT user_id, meta_key, meta_value
            FROM wp_usermeta
            WHERE meta_key = '_IDGL_elem_ECP_user_order'
            ";

$metadata = $wpdb -> get_results($wpdb -> prepare($metadatas));

//ALL USERS WITH ORDERS (active or inactive orders)
foreach($metadata as $current){
    $meta_id = $current->umeta_id;
    $user_id = $current->user_id;
    $order = unserialize($current->meta_value);
    $details = "SELECT DISTINCT user_id, meta_key, meta_value
            FROM wp_usermeta
            WHERE meta_key = '_IDGL_elem_ECP_user_orders_details'
            AND user_id = $user_id";
            
    $detail = $wpdb -> get_results($wpdb -> prepare($details));
    
    //USERS WHICH HAVE ORDER DETAILS (BTW, ALL USERS HAVE, BUT JUST IN CASE)
    if(isset($detail[0])){ 
        $cur_detail = $detail[0];
        $detail_value = unserialize($cur_detail->meta_value);
        
        //USERS WHICH HAVE EMPTY DETAILS
        if(empty($detail_value)){ 
            $cur_user = get_userdata($user_id);
            $date = new DateTime($cur_user->user_registered);
            $date = date_format($date, 'U');
            
            //handle every different possibility
            if(is_array($order)){
                ;
            }else{//lest's try unserializing again
                $order = unserialize($order);
            }
                
            if(is_array($order)){
                $orders_details = array();
                foreach($order as $index => $current){
                    $post_sql = "SELECT ID, post_name, meta_value
                        FROM wp_3_posts post
                        JOIN wp_3_postmeta meta ON post.ID = meta.post_id 
                        WHERE post.ID = $current
                        AND meta_key = 'universal_price'";

                    $product = $wpdb -> get_results($wpdb -> prepare($post_sql));
                    if( ! empty($product)){
                        $product_as_array = array();
                        foreach($product as $product){
                            $product_as_array[$index]["id"] = $product->ID;
                            $product_as_array[$index]["type"] = $product->post_name;
                            $product_as_array[$index]["price"] = $product->meta_value;
                            break;
                        }
                        $orders_details[$date]=$product_as_array;
                    }
                }
                update_user_meta($user_id, "_IDGL_elem_ECP_user_orders_details", $orders_details);
            }
        }
    }
}

die('Database fixed');