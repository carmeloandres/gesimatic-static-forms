<?php

namespace GesimaticStaticForms\Api\Middleware;

class RequestValidator {

    public static function validate_json($json) {

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        return $data;
    }

    public static function string($value) {
        return is_string($value) ? trim($value) : false;
    }
}