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
	 * This method registers the Gesimatic server Blocks and the necesary assets to work.
	 *
	 * @return void
	 */
    function register_blocks(){
         register_block_type(GESIMATIC_STATIC_FORMS_PATH. '/Blocks/member-register');
    }
}
