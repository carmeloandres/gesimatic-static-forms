<?php

namespace GesimaticStaticForms\Api;

use Gesimatic\Api\Base\CommonResponse;
//use Gesimatic\Api\Controllers\AdminController;
use Gesimatic\Core\Core;

use GesimaticStaticForms\Api\Middleware\CredentialValidator;
use GesimaticStaticForms\Api\Middleware\SignatureValidator;
use GesimaticStaticForms\Api\Middleware\RequestValidator;
use GesimaticStaticForms\Api\Middleware\ResolveRole;

//use GesimaticLoginAttempts\Core\Setup;

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
        $sanitized_params = [];

        // check if acction is as expected
        if(isset($params['form']) && ($params['form'] === 'user-register')){

            error_log ('UserRegisterAction validate, $params[gesimatic_website]: '.var_export($params['gesimatic_website'],true));
            // Check honeypot
            if ( ! empty($params['gesimatic_website'])) {
                return false; // Honeypot field is filled, likely a bot submission
            }

            error_log ('UserRegisterAction validate, $params[gesimatic_dataload]: '.var_export($params['gesimatic_dataload'],true));
            // Validate signature
            if ( ! SignatureValidator::validate( $params['gesimatic_dataload'] ?? '', $params['gesimatic_signature'] ?? '')) {
                return false;
            }

            // validate json
             $data = RequestValidator::validate_json($params['gesimatic_dataload']);

            error_log ('UserRegisterAction validate, $data: '.var_export($data,true));

             if (!$data) {
                return false;
            }

            // Check trap_time
            if (isset($data['trap_time']) && ! empty($data['trap_time'])) {
                $time_diff = time() - intval($data['trap_time']);
                if ($time_diff < 2) { // Less than 2 seconds
                    return  false;
                }
            }

            // Validate credentials
            $sanitized_params['username'] = sanitize_user($params['user_name'] ?? '');
            $sanitized_params['email'] = sanitize_email($params['user_email'] ?? '');
            if ( ! CredentialValidator::validate($sanitized_params['username'], $sanitized_params['email'])) {
                return false;
            }   

            // Get user role from block attributes
            $role = ResolveRole::get_role($data);
                    
            error_log ('UserRegisterAction validate, $role: '.var_export($role,true));

            if (!$role) {
                return false;
            }

            $sanitized_params['role'] = $role;
        }

        if (! empty($sanitized_params)) {
           return $sanitized_params;
        } else {
            return false;
        }
    }

    /**
     * To handle 
     * 
     * This method perfoms the necesaria actions to handle data, to perform the request.
     * 
     */
    public static function handle($validated_data){

        error_log ('UserRegisterAction handle, $validated_data: '.var_export($validated_data,true));

        if (is_array($validated_data) && isset($validated_data['username'], $validated_data['email'], $validated_data['role'])) {

            $user_id = wp_insert_user([
                'user_login' => $validated_data['username'],
                'user_email' => $validated_data['email'],
                'user_pass'  => wp_generate_password(32, true, true),
                'role'       => $validated_data['role'],
            ]);

            if (is_wp_error($user_id)) return CommonResponse::error();

            update_user_meta($user_id, '_gesimatic_pending_activation', 'pending');

            self::schedule_cleanup($user_id);
            self::send_email($user_id);

            return CommonResponse::success(['message' => 'User registered successfully.']);
        } else {
            return CommonResponse::error();
        }
    }


    /**
     * To schedule cleanup  
     */
    public static function schedule_cleanup($user_id) {

    		// Time límit (ex: 24h)
			$expire = time() + DAY_IN_SECONDS;

			update_user_meta($user_id, '_gesimatic_activation_expire', $expire);
			
			// We scheduled the cleaning with Action Scheduler
			Core::instance()->get_queue()->scheduleSingle(
				$expire,                    // momento de ejecución
				'gesimatic_cleanup_user',      // hook
				[ $user_id ]                   // argumentos
			);
    }   

    /**
     * To send email  
     */
    public static function send_email($user_id) : bool {

        $user = get_userdata($user_id);
        if (!$user) {
            return false;     
        }

        $key = get_password_reset_key( $user );

        if ( is_wp_error( $key ) ) {
            return false;
        }

        $reset_url = network_site_url(
            'wp-login.php?action=rp&key=' . $key . '&login=' . rawurlencode( $user->user_login ),
            'login'
        );

        $subject = __('Set your password', 'gesimatic-static-forms');

        $message = sprintf(
            __('Hello %s,\n\nClick on the following link to set your password:\n\n%s', 'gesimatic-static-forms'),
            $user->display_name,
            $reset_url
        );

        return wp_mail(
            $user->user_email,
            $subject,
            $message
        );

    }

}
