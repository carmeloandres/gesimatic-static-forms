<?php

namespace GesimaticStaticForms\Api;

// Prevent direct access 
if ( ! defined( 'ABSPATH' ) ) {exit;} ;

class UserService {

    public static function register($username, $email, $data) {

        if (username_exists($username) || email_exists($email)) {
            return;
        }

        $role = self::resolve_role($data);

        if (!$role) return;

        $user_id = wp_insert_user([
            'user_login' => $username,
            'user_email' => $email,
            'user_pass'  => wp_generate_password(32, true, true),
            'role'       => $role,
        ]);

        if (is_wp_error($user_id)) return;

        update_user_meta($user_id, '_gsf_status', 'pending');

        self::schedule_cleanup($user_id);
        self::send_email($user_id);
    }
}