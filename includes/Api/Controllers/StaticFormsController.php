<?php

namespace GesimaticStaticForms\Api\Controllers;

use Gesimatic\Api\Middleware\ApiPermissions;

/**
 * Class Api
 *
 * This class contains the code necessary to manage the gesimatic apis.
 */
class StaticFormsController {

    public function register_routes() {

//        register_rest_route('gesimatic-static-forms/v1', '/user-register', [
        register_rest_route('gesimatic-static-forms/v1', '/(?P<form>[\w-]+)', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle'],
            'permission_callback' => [ApiPermissions::class, 'check'],
        ]);

        error_log ('GesimaticStaticForms->FormsController, register_routes() executed');

    }

        public function handle(\WP_REST_Request $request) {

        // 1. RATE LIMIT
/*        $ip = $_SERVER['REMOTE_ADDR'];
        if (!RateLimiter::check('gsf_' . md5($ip))) {
            return $this->error();
        }
*/
        // 2. GET PARAMS
        $params = $request->get_params();
        error_log ('GesimaticStaticForms->FormsController, handle() $params: '.var_export($params,true));

        // Checks if exists form name
        if ( ! isset($params['form'])) return $this->error();

       // sanitize action 
        $form = sanitize_text_field($params['form']);

        // Gets registered actions
        $static_forms = $this->get_static_forms();

        // checks if it is enabled and validate data
        if ( ! isset($static_forms[$form])) return $this->error();

        // validate request data
        $validated = call_user_func($static_forms[$form]['validate'], $params);
    
        // handle the request
        return call_user_func($static_forms[$form]['handle'], $validated );



        // 3. VALIDATE SIGNATURE
        if (!SignatureValidator::validate(
            $params['gesimatic_dataload'] ?? '',
            $params['gesimatic_signature'] ?? ''
        )) {
            return $this->error();
        }

        // 4. VALIDATE JSON
        $data = RequestValidator::validate_json($params['gesimatic_dataload']);

        if (!$data) {
            return $this->error();
        }

        // 5. HONEYPOT
        if (!empty($params['gesimatic_website'])) {
            return $this->error();
        }

        // 6. VALIDATE INPUTS
        $username = RequestValidator::string($params['user_name'] ?? null);
        $email    = sanitize_email($params['user_email'] ?? '');

        if (!$username || !is_email($email)) {
            return $this->error();
        }

        // 7. SERVICE LAYER
        UserService::register($username, $email, $data);

        return $this->success();
    }

    private function success() {
        return new \WP_REST_Response([
            'success' => true,
            'message' => 'If valid, check your email'
        ], 200);
    }

    private function error() {
        return new \WP_REST_Response([
            'success' => true,
            'message' => 'If valid, check your email'
        ], 200);
    }

    /**
	 * To get all static forms registered at endpoint
	 *
	 * @return bool
	 */
    protected function get_static_forms(): array {
        return apply_filters('gesimatic_static_forms', [
            'user_register' => [
                'validate' => [$this, 'validate_user_register'],
                'handle'   => [$this, 'handle_user_register'],
            ]
        ]);
    }


/*
    public function register_routes() {

        register_rest_route('gesimatic/v1','admin', [
            'methods'  => 'POST',
            'callback' => [$this, 'execute'],
            'args' => [
                'action' => [
                    'required' => true,
                    'type' => 'string'
                ]
            ],
//			'permission_callback' => [$this,'admin_api_permission']
			'permission_callback' => [ApiAdminPermissions::class,'check']

        ]);
    }
    */
}