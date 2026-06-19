<?php

namespace GesimaticStaticForms\Api\Middleware;


class SignatureValidator {

    public static function validate($payload, $signature) {

        $expected = hash_hmac('sha256', $payload, AUTH_KEY);

        return hash_equals($expected, $signature);
    }
}