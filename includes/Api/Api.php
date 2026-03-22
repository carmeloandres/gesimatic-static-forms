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

//	    add_filter( 'gesimatic_admin_api_action_register_user',[$this,'register_user'],10,2);

        // Add a hook to register gesimatic_sever API REST endpoints
        add_action('rest_api_init',array($this,'rest_api_init'));


	}

    /**
	 * rest_api_init
	 * 
	 * This method creates the endpoint to the plugin conections
	 *
	 * @return void
	 */
	function rest_api_init(){

	    // endpoint only to user_register 
		register_rest_route('gesimatic-static-forms/v1','user-register',array(
			'methods'  => 'POST',
			'callback' => array($this,'manage_user_register_api_request'),
            'args' => [
                'user_name' => [
                    'required' => true,
                    'type' => 'string'
                ],
                'user_email' => [
                    'required' => true,
                    'type' => 'string'
                ],
            ],
			'permission_callback' => [$this,'user_register_api_permission']							
		));

    }

    /**
	 * To validate the admin api requests
	 * 
	 * This method validate the request of the endpoint from the admin 
	 *
	 * @return bool
	 */
    public function user_register_api_permission(): bool {

        return true;
    }

    /**
	 * user_register_api_controler
	 * 
	 * This method manage de request of the endpoint user_register 
	 *
	 * @return void
	 */
     function user_register_api_controler(\WP_REST_Request $request ){

		 $result = false;
		 
		 if ($request->sanitize_params()){
			 
			 $params = $request->get_params();
			 error_log ('user_register_api_controler, $params: '.var_export($params,true));
		
            if (isset($params['action'])){
				$action = $params['action'];
/*				
				switch ($action){
                    case 'get_access_settings':
                            $result = $this->get_access_settings();
                            break;
					case 'get_smtp_settings':
							$result = $this->get_smtp_settings();
							break;
					case 'set_access_settings':
							$result = $this->set_access_settings($params);
							break;
					case 'set_smtp_settings':
							$result = $this->set_smtp_settings($params);
							break;	
					case 'get_bloqued_ips':
							$result = $this->get_bloqued_ips($params);
							break;
					case 'get_status_ips':
							$result = $this->get_status_ips($params);
							break;
					case 'get_pagination':
							$result = $this->get_pagination($params);
							break;
					case 'do_status_ips_action':
							$result = $this->do_status_ips_action($params);
							break;
					case 'send_test_email':
							$result = $this->send_test_email($params);
							break;
//					case 'register_account':
//							$result = $this->register_account($params);
//							break;
					case 'unregister_account':
							$result = update_option('gsmtc_api_token','');
							break;
					case 'get_backup_download':
							$result = $this->get_backup_download();
							break;
					default:
							$result = apply_filters('gesimatic_admin_api_action_'.$action,$result,$params);
                            // ejemplo de llamada: add_filter(gesimatic_api_action_register_account,[$this,'register_account']);
                            // modificando la función para que reciba dos parametros, $result y $params.
							break;							
						}
*/
				$result = apply_filters('gesimatic_admin_api_action_'.$action,$result,$params);
			} 
		}
		error_log ('admin_api_controler, $result: '.var_export($result,true));

		return new \WP_REST_Response($result, 200);
//        echo json_encode($result);
//		exit();
	}




    /**
	 * To initialize the admin Api hooks	  
	 */
	public function register_user($result,$params){

		 error_log ('Api->register_user() executed, $params: '.var_export($params,true));

         return $result;
    }
}