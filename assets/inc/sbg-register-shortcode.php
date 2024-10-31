<?php
function sbg_generator( $attr, $content = null ) {
    extract(shortcode_atts(array(
        'id' => '0',
        ),$attr)); 

    if(empty($id))
         $id = 0;
     
    if(!is_user_logged_in())
            return 'Login to view this section !';
    
    global $wpdb;
    $user_id = get_current_user_id();
    $permitted_signals = get_user_meta($user_id, 'permitted_signals',true);
    $permitted_signals = explode(',', $permitted_signals);

    if(!in_array($id, $permitted_signals)){
            echo "You don't have permission to access this";
            return;
    }
    wp_enqueue_style('sbg-core-css');
    wp_enqueue_script('sgb-core-js');
    
    $tblname = 'signalbox_signals';
    $table_name = $wpdb->base_prefix . "$tblname";
    $sql = "SELECT * FROM $table_name WHERE `createdby` = '$user_id' ORDER BY `id` DESC";
    $query = $wpdb->get_results($sql,ARRAY_A); ?>

    <?php $html = '<div class="signalbox-generator">';
            $html.='<form id="newsignal"><div class="sbg-field sbg-50">

                <label for="sbg_pair">Pair:</label><input required id="sbg_pair" type="text" name="sbg_pair"/>

            </div>';
            

            $html.='<div class="sbg-field sbg-50">

            <label for="sbg_pair_dir">Pair Direction:</label><select id="sbg_pair_dir" name="sbg_pair_dir">';
                $html.='<option name="pair_up">up</option>';
                $html.='<option name="pair_down">down</option>';
            
            $html.='</select></div>';


            $html.='<div class="sbg-field sbg-100"><label for="sbg_addinfo">Additional Information:</label><textarea id="sbg_addinfo" name="sbg_addinfo"></textarea></div>';

            $html.='<div class="sbg-field sbg-100">

                <label for="sbg_pair">SR:</label><input required id="sbg_rate" type="text" name="sbg_rate"/>

            </div>';    
        /*  $html.='<div class="sbg-field sbg-50">

                <label for="sbg_pair">Trade URL:</label><input id="sbg_tradeurl" type="url" name="sbg_tradeurl"/>

            </div>';*/  
            $html.='<div class="sbg-field sbg-100">

                <button type="button" class="sbg-btn gsbtn" data-id="'.$id.'" >Generate</button>

            </div></form><div class="signalbox-list-container" id="sbglist_result_emp">';
        foreach ($query as $key => $signal) {
                    $html .= '<div class="signalbox-widget sbg-sm-100" id="signalID'.$signal['id'].'"><div>';
                    $html .='<h3><i class="icon-arrow-'.$signal['direction'].'"></i> '.$signal['pair'].'</h3>';
                    $html .='<div><h5>SR</h5>'.$signal['rate'].'</div>';
                    $html .='<p>'.$signal['description'].'</p>';
                    $html .='<button type="button" data-id="'.$signal['id'].'" class="btn-sbg dsbtn">Delete</button>';
                    $html .='</div></div>';
        }


    $html .='</div></div>';
    return $html;
}
add_shortcode('fsp_generator', 'sbg_generator');
add_shortcode('fsp_lists', 'sbg_lists_func');

function sbg_lists_func($atts){
     if(empty($id))
         $id = 0; ?>
     <?php
    wp_enqueue_style('sbg-core-css');
    wp_enqueue_script('sgb-core-js');

    extract(shortcode_atts(array(
        'id' => '0',
        ),$atts));  

    return'<div class="signalbox-list-container" id="sbglist_result" data-id="'.$id.'"></div>';
}