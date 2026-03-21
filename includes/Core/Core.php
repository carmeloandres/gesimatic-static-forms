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

        // To show the gesimatic admin menu
/*        add_action('admin_menu',[$this,'show_admin_menu']);

        // to load the admin assets
        add_action('admin_enqueue_scripts',[$this,'admin_enqueue_assets'], 10, 1);

        // set the react file gesimatic-admin.js as an ES6 module 
        add_filter( 'script_loader_tag', function( $tag, $handle, $src ) {
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
        
}