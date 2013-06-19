<?php
global $wpdb;

$metadatas = "SELECT umeta_id, user_id, meta_value
            FROM wp_usermeta
            WHERE meta_key = '_IDGL_elem_ECP_user_order'";

$metadata = $wpdb -> get_results($wpdb -> prepare($metadatas));

foreach($metadata as $current){
    $meta_id = $current->umeta_id;
    $user_id = $current->user_id;
    $order = unserialize(unserialize($current->meta_value));
    
    echo '<pre>';
    print_r($order);
    echo '</pre>';
    echo '<hr>';
}




die('dbfix');