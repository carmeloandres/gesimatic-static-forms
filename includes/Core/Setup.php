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
}