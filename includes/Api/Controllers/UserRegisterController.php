<?php

namespace GesimaticStaticForms\Api;

use Gesimatic\Api\Middleware\ApiPermissions;

class UserRegisterController {

    public function register_routes() {

//        register_rest_route('gesimatic-static-forms/v1', '/user-register', [
        register_rest_route('gesimatic-static-forms/v1', '/(?P<form>[\w-]+)', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle'],
            'permission_callback' => [ApiPermissions::class, 'check'],
        ]);
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
        error_log ('GesimaticStaticForms->FormsController, $params: '.var_export($params,true));

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
}