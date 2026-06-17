<?php

namespace GesimaticStaticForms\Api;

// Prevent direct access 
if ( ! defined( 'ABSPATH' ) ) {exit;} ;


class RateLimiter {

    public static function check($key, $limit = 10, $window = 60) {

        $count = (int) get_transient($key);

        if ($count >= $limit) {
            return false;
        }

        set_transient($key, $count + 1, $window);

        return true;
    }
}