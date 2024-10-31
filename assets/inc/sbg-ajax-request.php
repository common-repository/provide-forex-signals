<?php
add_action('wp_ajax_sbg_update_signals', 'sibg_update_signals');
add_action('wp_ajax_nopriv_sbg_update_signals', 'sibg_update_signals');

function sibg_update_signals()
{
    if (!isset($_POST['data']))
    {
        _e("Invalid Data", 'sibg');
        wp_die();
    }

    parse_str($_POST['data'], $data);
    $error = '';

    $pair = sanitize_text_field($data['sbg_pair']);
    $direction = sanitize_text_field($data['sbg_pair_dir']);
    $sbg_addinfo = sanitize_text_field($data['sbg_addinfo']);
    $rate = (float)$data['sbg_rate'];

    $id = (int) $_POST['id']; // Sanitized
    $method = sanitize_title($_POST['method']); // ENUM

    $user_id = get_current_user_id();

    if ($user_id == 0)
    {
        $error = _e("Login to generate ! ",'sibg');
        //$permitted = false;
        wp_die();
    }
    $permitted_signals = get_user_meta($user_id, 'permitted_signals', true);

    $permitted_signals = explode(',', $permitted_signals);

    if (in_array($id, $permitted_signals))
    {
        $permitted = true;
    }

    global $wpdb;
    $tblname = 'signalbox_signals';
    $table_name = $wpdb->base_prefix . "$tblname";
    //print_r($data);
    if ($method == 'add' && $permitted)
    {
        $query_data = array(
            'createdby' => $user_id,
            'category' => $id,
            'pair' => $pair,
            'direction' => $direction,
            'rate' => $rate,
            'description' => $sbg_addinfo
        );
        $format = array(
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%s'
        );
        $query = $wpdb->insert($table_name, $query_data, $format);
        // $query = $wpdb->query($SQL );
        //var_dump($query);
        if ($query)
        {
            echo 'success';
        }
        else
        {
            //$lastError = $wpdb->last_query();
            _e('Error while adding the signal! Query Failed Check your errorlog','sibg');
        }
        wp_die();
    }
    else if ($method == 'delete')
    {
        $SQL = "DELETE FROM `$table_name` WHERE `id` = '$id' AND `createdby` = '$user_id'";
        $query = $wpdb->query($SQL);
        if ($query)
        {
            echo 'success';
        }
        else
        {
            //$lastError = $wpdb->last_query();
            _e('Error while deleting the signal! Query Failed Check your errorlog','sibg');
        }
        wp_die();
    }
    wp_die();
}

