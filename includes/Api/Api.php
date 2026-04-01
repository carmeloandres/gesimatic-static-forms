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
     function manage_user_register_api_request(\WP_REST_Request $request ){

		 $result = [
			'success' => false,
        	'message' => __('Process not ended.','gesimatic-static-forms'),
    	];
		 
		if ($request->sanitize_params()){
			 
			$params = $request->get_params();
			error_log ('manage_user_register_api_request, $params: '.var_export($params,true));
		
			// Validating the honeypot
			if ($params['gesimatic_website'] !== ''){
				$result = [
					'success' => false,
        			'message' => __('Bot detected.','gesimatic-static-forms'),
    			];
				return new \WP_REST_Response($result, 200);
			}

			$username = sanitize_user($params['user_name']);
			$email = sanitize_email($params['user_email']);

			// 🔐 1. Validaciones básicas
			if (empty($username) || empty($email)) {
				$result = [
					'success' => false,
        			'message' => __('Missing required fields.','gesimatic-static-forms'),
    			];
				return new \WP_REST_Response($result, 200);
			}

		    if (!is_email($email)) {
				$result = [
					'success' => false,
        			'message' => __('Invalid email.','gesimatic-static-forms'),
    			];
				return new \WP_REST_Response($result, 200);
		    }

	    	if (username_exists($username) || email_exists($email)) {
				$result = [
					'success' => false,
        			'message' => __('User already exists.','gesimatic-static-forms'),
    			];
				return new \WP_REST_Response($result, 200);
		    }

			// validating payload data
			$dataload = $params['gesimatic_dataload'];
			$signature = $params['gesimatic_signature'];
			if( ! $this->data_validation($dataload, $signature)){
				$result = [
					'success' => false,
        			'message' => __('Manipulated data','gesimatic-static-forms'),
				];
				return new \WP_REST_Response($result, 200);
			}
					
			$data = json_decode($dataload);
			error_log ('Api->manage_user_register_api_request, $data: '.var_export($data,true));
			
			if ((time() - $data['trap_time']) < 3) {
				$result = [
					'success' => false,
        			'message' => __('Bot detected','gesimatic-static-forms'),
				];
				return new \WP_REST_Response($result, 200);
			}



			// 🔐 2. Obtener rol (desde tu sistema seguro)
			$role = 'subscriber'; // 👈 aquí debes integrar tu lógica segura

//			$allowed_roles = ['subscriber', 'customer'];
/*			$allowed_roles = self::get_allowed_roles();

			if (!in_array($role, $allowed_roles, true)) {
				return new WP_Error('invalid_role', 'Invalid role');
			}

			// 🔐 3. Crear usuario SIN contraseña usable
			$random_password = wp_generate_password(20, true, true);

			$user_id = wp_create_user($username, $random_password, $email);

			if (is_wp_error($user_id)) {
				return $user_id;
			}

			// 🔐 4. Asignar rol
			wp_update_user([
				'ID'   => $user_id,
				'role' => $role
			]);

			// 🔐 5. Generar clave de reset
			$user = get_user_by('id', $user_id);

			$reset_key = get_password_reset_key($user);

			if (is_wp_error($reset_key)) {
				return $reset_key;
			}

			// 🔗 6. Crear URL de reset
			$reset_url = network_site_url(
				"wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($user->user_login),
				'login'
			);

			// 📧 7. Enviar email
			$subject = __('Set your password'.'gesimatic-static-forms');

			$message = "Hello {$user->user_login},\n\n";
			$message .= "Click the following link to set your password:\n\n";
			$message .= $reset_url . "\n\n";
			$message .= "If you did not request this, ignore this email.";

			wp_mail($user->user_email, $subject, $message);

			$result = [
				'success' => true,
				'message' => 'User registered. Check your email.'
			];
*/
		}

		error_log ('admin_api_controler, $result: '.var_export($result,true));

		return new \WP_REST_Response($result, 200);
	}

    /**
	 * To validate form data	  
	 */
	private function data_validation($dataload, $signature){

		$expected_signature = hash_hmac('sha256', $dataload, AUTH_SALT);

		if (!hash_equals($expected_signature, $signature)) {
			return false;
		} else {return true;}
	}

    /**
	 * To initialize the admin Api hooks	  
	 */
	public function register_user($result,$params){

		 error_log ('Api->register_user() executed, $params: '.var_export($params,true));

         return $result;
    }
}