<?php
add_action( 'rest_api_init', 'sibg_custom_users_api');
 
function sibg_custom_users_api(){
    register_rest_route( 'sbg/v1', '/signals/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'sibg_get_custom_users_data',
        'permission_callback' => '__return_true'
    ));
}

function sibg_get_custom_users_data($data){
    global $wpdb;
    $tblname = 'signalbox_signals';
    $table_name = $wpdb->base_prefix . "$tblname";
    if($data['id'] ==0){
        $sql = "SELECT * FROM $table_name ORDER BY `id` DESC";
    }else{
        $sql = "SELECT * FROM $table_name WHERE `category`= ".$data['id']." ORDER BY `id` DESC";
    }
    
    $query = $wpdb->get_results($sql,ARRAY_A);
    $count = count($query);
    return array('count'=>$count,'data'=>$query);
}