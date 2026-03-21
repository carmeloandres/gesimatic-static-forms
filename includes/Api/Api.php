<?php

namespace GesimaticStaticForms\Api;

// Prevent direct access 
if ( ! defined( 'ABSPATH' ) ) {exit;} ;

use GesimaticStaticForms\Core\Setup;

/**
 * Class Api
 *
 * This class contains the code necessary to manage the gesimatic apis.
 *
 * @package Gsmtc\Api
 */
class Api extends Setup {
	/**
     * Class constructor.
     *
     * Add the rest api initialization hook
     * 
     */
    function __construct(){
        //call to parent constructor
        parent::__construct();

		// error_log ('Api::construct() executed, $params: ');//.var_export($params,true));

	    add_filter( 'gesimatic_admin_api_action_register_user',[$this,'register_user'],10,2);


	}

	/**
	 * To initialize the admin Api hooks	 * 
	 */
	public function register_user($resutl,$params){

		 error_log ('Api->register_user() executed, $params: '.var_export($params,true));

         return $result;
    }
}