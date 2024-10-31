<?php 
/*
Plugin Name: Provide Forex Signals
Plugin URI: https://codeies.com/provide-forex-signals
Description: This plugin is used to send forex signals to your clients/ 
Version: 1.0
Author: Muhammad Junaid
Author URI: https://codeies.com/junaid
Text Domain: codeies_fsp
*/

// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if

// Let's Initialize Everything
if ( file_exists( plugin_dir_path( __FILE__ ) . 'core-init.php' ) ) {
require_once( plugin_dir_path( __FILE__ ) . 'core-init.php' );
}


add_action('show_user_profile', 'sibg_showprofile_meta');
add_action('edit_user_profile', 'sibg_showprofile_meta');
function sibg_showprofile_meta($user) {
$value = (isset($user->permitted_signals)) ? $user->permitted_signals : '';

 echo wp_kses('<h3>Signal IDs ! User can access. </h3>
  <label for="permitted_signals">
    Signal IDs , Seperated by comma (0) For All. 
    <input name="permitted_signals" type="text" id="permitted_signals" value="'.$value.'">
  </label>',array(
    'h3' => array(),
    'label' => array(),
    'input' => array(
        'type'      => array(),
        'name'      => array(),
        'value'     => array(),
       // 'checked'   => array()
    ),
)
);
}
add_action('personal_options_update', 'sibg_update_user_meta');
add_action('edit_user_profile_update', 'sibg_update_user_meta');

function sibg_update_user_meta($user_id) {

  // Sanitizing Comma Seperated Numbers only 
  
  $permitted_signals  = sanitize_text_field($_POST['permitted_signals']);

  if(empty($permitted_signals))
     update_user_meta($user_id, 'permitted_signals', '' ); 

  if (preg_match('/^\d+(?:,\d+)*$/', $permitted_signals) ) // Validate if comma seperated Numbers are being sent 
     update_user_meta($user_id, 'permitted_signals', $permitted_signals ); 

}


function sibg_createtable()
{
    global $wpdb;

    $tblname = 'signalbox_signals';
    $table_name = $wpdb->base_prefix . "$tblname";
  $charset_collate = $wpdb->get_charset_collate();
    #Check to see if the table exists already, if not, then create it

    if($wpdb->get_var( "show tables like '$table_name'" ) != $table_name) 
    {
  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    createdby int(5)  NOT NULL,
    category int(5)  NOT NULL,
    pair varchar(25)  NOT NULL,
    direction varchar(4)  NOT NULL,
    description varchar(250) ,
    rate varchar(10)  NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate;";
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
      dbDelta($sql);
    }
}

 register_activation_hook( __FILE__, 'sibg_createtable' );