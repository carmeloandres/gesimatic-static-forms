<?php

namespace GesimaticStaticForms\Api;

use Gesimatic\Api\Base\CommonResponse;
use Gesimatic\Api\Controllers\AdminController;
use Gesimatic\Api\Middleware\SinatureValidator;
use Gesimatic\Api\Middleware\RequestValidator;
use Gesimatic\Api\Middleware\ResolveRole;

use GesimaticLoginAttempts\Core\Setup;

/**
 * Class Setup
 *
 * This class contains the code necessary to manage the data from get_login_attempts_pagination api request.
 *
 * @package gesimatic-login-attempts
 */
class UserRegisterAction {

    /** 
     * To validate 
     * 
     * This method perfoms the necesaria actions to validate data.
     * 
     */
    public static function validate($params){

        error_log ('UserRegister validate, $params: '.var_export($params,true));

        // sets the default value
        $sanitized_params = array();

        // check if acction is as expected
        if(isset($params['form']) && ($params['form'] === 'user_register')){

            // Check honeypot
            if ( ! empty($params['gesimatic_website'])) {
                return CommonResponse::error();
            }

            // Validate signature
            if ( ! SignatureValidator::validate( $params['gesimatic_dataload'] ?? '', $params['gesimatic_signature'] ?? '')) {
                return CommonResponse::error();
            }

            // validate json
             $data = RequestValidator::validate_json($params['gesimatic_dataload']);

            if (!$data) {
                return CommonResponse::error();
            }

            // Check trap_time
            if (isset($data['trap_time']) && ! empty($data['trap_time'])) {
                $time_diff = time() - intval($data['trap_time']);
                if ($time_diff < 2) { // Less than 2 seconds
                    return CommonResponse::error();
                }
            }

            // Validate inputs
            $sanitized_params['username'] = RequestValidator::string($params['user_name'] ?? null);
            $sanitized_params['email']    = sanitize_email($params['user_email'] ?? '');

            if (!$sanitized_params['username'] || !is_email($sanitized_params['email'])) {
                return CommonResponse::error();
            }

            // Get user role from block attributes
            $sanitized_params['role'] = ResolveRole::get_role($data);
        }

        return $sanitized_params;
    }

       /**
     * To handle 
     * 
     * This method perfoms the necesaria actions to handle data, to perform the request.
     * 
     */
    public static function handle($validated){

        error_log ('UserRegister handle, $validated: '.var_export($validated,true));

        $results = array(
			'items' => 0,
			'pages' => 0
		);

        return new \WP_REST_Response($results, 200);

     if (is_array($validated)){
        	$filterStatus = '';
			if ($validated['filterStatus'] != '')
				if ($validated['filterStatus'] == 'enabled')
					$filterStatus = " WHERE status = 'enabled' ";
				else $filterStatus = " WHERE status <> 'enabled' ";
        
    $items = $wpdb->get_var( "SELECT COUNT(*) FROM " . self::$table_name_status_ip . " " . $filterStatus );

	        $pages = 0;
			if (($items != NULL)){
				$pages = intval($items) / intval(self::$per_page);
				if ((intval($items) % intval(self::$per_page)) > 0)
					$pages = intval($pages + 1); 
				else $pages = intval($pages);
			}

			$results = array(
				'items' => $items,
				'pages' => $pages
			);		


        } else return CommonResponse::error();

        return new \WP_REST_Response($results, 200);
    }

}
