<?php

namespace GesimaticStaticForms\Blocks;

// Prevent direct access 
if ( ! defined( 'ABSPATH' ) ) {die;} ; 


/**
 * Class Gesimatic_Server_Blocks
 *
 * This class contains the code necessary to create the server side gesimatic server blocks
 *
 * @package Gesimatic-server
 */
class Blocks {

  /**
     * Class constructor.
     *
     * Add the necessary hooks
     * 
     */
    function __construct(){

 		// Register GesimaticSever API REST endpoints
        add_action('init',array($this,'register_blocks'));
       
    }

    /**
	 * registers the blocks and block assets 
	 * 
	 * This method registers the Gesimatic Static Forms Blocks and the necesary assets to work.
	 *
	 * @return void
	 */
    function register_blocks(){

    error_log ('Blocks::register_blocks() executed, $params: ');//.var_export($params,true));
        wp_register_script(
            'gesimatic-user-register-editor',
            GESIMATIC_STATIC_FORMS_PATH.'blocks/user-register/build/index.js',
            [],
            GESIMATIC_STATIC_FORMS_VERSION
        );

        register_block_type(GESIMATIC_STATIC_FORMS_PATH. '/blocks/user-register');
    }
}
