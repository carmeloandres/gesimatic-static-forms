<?php

namespace GesimaticStaticForms\Core;

// Prevent direct access 
if ( ! defined( 'ABSPATH' ) ) {exit;} ; 

use GesimaticStaticForms\Core\Setup;
use GesimaticStaticForms\Blocks\Blocks;
use GesimaticStaticForms\Api\Api;
/**
 * Class Core
 *
 * This class contains the code necessary to manage the necesary function hooks.
 *
 * @package Gesimatic
 */
class Core extends Setup {

    /**
     * Array to store dinamicaly the instances of each class when they are required.
     *
     * @var array
     */
    protected array $instances = [];

    /**
     * Class constructor.
     *
     * Sets the value of the properties, adds the actions necessary for the operation of
     * class.
     */    
    function __construct()
    {
        //call to parent constructor
        parent::__construct();

        // Load the Api class if not is loaded
        if (! isset($this->instances['api']))
            $this->instances['api'] = new Api();

        // Load the Blocks class if not is loaded
        if (! isset($this->instances['blocks']))
            $this->instances['blocks'] = new Blocks();

        // To set the user role at reset password if proceed
        add_action('after_pasword_reset',[$this,'delete_user_activation_expires']);

        // To clean the registered but inactive users
        add_action( 'gesimatic_cleanup_user', [ $this, 'cleanup_inactive_user' ] );

        // to load the text domain
        add_action('plugins_loaded',[$this,'load_text_domain']);

        // set the react file gesimatic-admin.js as an ES6 module 
/*        add_filter( 'script_loader_tag', function( $tag, $handle, $src ) {
            // If the handle is not from our React script, we return the tag unchanged.
            if ( 'gesimatic-admin-js' !== $handle ) {
                return $tag;
            }

            // We changed the tag to include type="module"
            $tag = '<script type="module" src="' . esc_url( $src ) . '" id="' . esc_attr( $handle ) . '-js"></script>';
            
            return $tag;
        }, 10, 3 );       

        add_action( 'gesimatic_admin_header', [ \Gesimatic\Admin\InterfaceManager::class, 'render_common_header' ] );

 //       \Gesimatic\Api\Api::init();
        error_log ('Core::construct() executed, $params: ');//.var_export($params,true));
*/
    }

    /**
     * Function to load the text domain
     * @param void
     */
    public static function load_text_domain() {
        load_plugin_textdomain(
            'gesimatic-static-forms',
            false,
            '/gesimatic-static-forms/languages'//Relative path to WP_PLUGIN_DIR where the .mo file resides. 
        );
    }

    /**
     * Función que se ejecutará en la cola de Action Scheduler
     * @param int $user_id
     */
    public static function cleanup_inactive_user( $user_id ) {
        // Comprobar que el usuario existe
        $user = get_userdata( $user_id );
        if ( ! $user ) return;

        // Obtener la meta de expiración
        $expire = get_user_meta( $user_id, '_gesimatic_activation_expire', true );

        // Si ha expirado, eliminar usuario
        if ( $expire && time() > intval($expire) ) {
            wp_delete_user( $user_id );
        }
    }

     /**
     * To set the user role.
     *
     * Sets the user role at reset password if proceed
     */    
    function delete_user_activation_expires($user){
        // $user → objeto WP_User
        
        delete_user_meta($user->ID, 'gesimatic_pending_activation');
        delete_user_meta($user->ID, 'gesimatic_activation_expires');

        error_log("User {$user->user_login} has reset their password");
    }

}