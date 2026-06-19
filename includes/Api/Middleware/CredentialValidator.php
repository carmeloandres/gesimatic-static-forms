<?php

namespace GesimaticStaticForms\Api;

class CredentialValidator {

    public static function validate($username, $email) {

        // Validating username and email
        if (empty($username) || empty($email)) {
            return false;
        }

        if (!is_email($email)) {
            return false;
        }

        if (username_exists($username) || email_exists($email)) {
            return false;
        }

        return true;
    }
}