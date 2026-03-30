<?php

namespace GesimaticStaticForms\Core;

// Prevent direct access 
if ( ! defined( 'ABSPATH' ) ) {exit;} ; 

/**
 * Class Setup.
 * 
 * @package Gesimatic
*/
class Setup {

    /**
     * Restricted roles
     */
    public static $restricted_roles = ['administrator','author','contributor','editor'];

    /**
    * Empty construct function to enable future use
    */
    function __construct(){
        
    }

    /**
     * Activate plugin function.
     *
     * @return void
     */
    public static function activate(): void {
    
    }

    /**
     * Delete plugin components.
     *
     * @return void
     */
    public static function delete(): void {
//        delete_option('gesimatic_api_token');

//		delete_option('gesimatic_registered_plugins');
    }

    /**
     * To get the available roles.
     *
     * @return array
     */
    public static function get_allowed_roles(): array {
        $wp_roles = wp_roles();
        $all_roles = $wp_roles->get_names();

        return array_diff_key($all_roles,array_flip(self::$restricted_roles));
    }


}