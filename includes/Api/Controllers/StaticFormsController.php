<?php

namespace GesimaticStaticForms\Api\Controllers;

use Gesimatic\Api\Middleware\ApiPermissions;
use Gesimatic\Api\Base\CommonResponse;

/**
 * Class Api
 *
 * This class contains the code necessary to manage the gesimatic apis.
 */
class StaticFormsController {

    public function register_routes() {

        register_rest_route('gesimatic-static-forms/v1', '/(?P<form>[\w-]+)', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle'],
            'permission_callback' => [ApiPermissions::class, 'check'],
        ]);

    }

    public function handle(\WP_REST_Request $request) {

        // Get params
        $params = $request->get_params();
        error_log ('GesimaticStaticForms->FormsController, handle() $params: '.var_export($params,true));

        // Checks if exists form name
        if ( ! isset($params['form'])) return  CommonResponse::error();

       // sanitize action 
        $form = sanitize_text_field($params['form']);

        // Gets registered actions
        $static_forms = $this->get_static_forms();

        // checks if it is enabled and validate data
        if ( ! isset($static_forms[$form])) return CommonResponse::error();

        // validate request data
        $validated = call_user_func($static_forms[$form]['validate'], $params);
    
        // handle the request
        return call_user_func($static_forms[$form]['handle'], $validated );
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
}