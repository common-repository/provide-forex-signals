<?php 
/*
*
*	***** Signal Box Generator *****
*
*	This file initializes all SBG Core components
*	
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if
// Define Our Constants
define('SBG_CORE_INC',dirname( __FILE__ ).'/assets/inc/');
define('SBG_CORE_IMG',plugins_url( 'assets/img/', __FILE__ ));
define('SBG_CORE_CSS',plugins_url( 'assets/css/', __FILE__ ));
define('SBG_CORE_JS',plugins_url( 'assets/js/', __FILE__ ));
/*
*
*  Register CSS
*
*/
function sbg_register_core_css(){
wp_register_style('sbg-core-css', SBG_CORE_CSS . 'sbg-core.css',null,time(),'all');

};
add_action( 'wp_enqueue_scripts', 'sbg_register_core_css' );    
/*
*
*  Register JS/Jquery Ready
*
*/
function sbg_register_core_js(){
// Register Core Plugin JS	
wp_enqueue_script( 'jquery' );
wp_register_script('sgb-core-js', SBG_CORE_JS . 'sgb-core.js','jquery',time(),true);

wp_localize_script( 'sgb-core-js', 'sbg_obj',
            array( 
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'rest_url' => get_rest_url()
            ) 
        );

/*wp_localize_script( 'sbg-core-js', 'sbg_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );*/
};
add_action( 'wp_enqueue_scripts', 'sbg_register_core_js' );    
/*
*
*  Includes
*
*/ 

require_once SBG_CORE_INC . 'sbg-register-shortcode.php';
require_once SBG_CORE_INC . 'sbg-ajax-request.php';
require_once SBG_CORE_INC . 'sbg-apis-register.php';
//require_once SBG_CORE_INC . 'sbg-register-widget.php';
//require_once SBG_CORE_INC . 'sbg-core-functions.php';
