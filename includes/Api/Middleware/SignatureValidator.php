<?php

namespace GesimaticStaticForms\Api;

// Prevent direct access 
if ( ! defined( 'ABSPATH' ) ) {exit;} ;

class SignatureValidator {

    public static function validate($payload, $signature) {

/*        $secret = defined('GESIMATIC_API_SECRET') 
            ? GESIMATIC_API_SECRET 
            : AUTH_KEY;

        $expected = hash_hmac('sha256', $payload, $secret);
*/
        $expected = hash_hmac('sha256', $payload, AUTH_KEY);

        return hash_equals($expected, $signature);
    }
}