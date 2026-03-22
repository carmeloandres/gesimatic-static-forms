<?php

namespace GesimaticStaticForms\Blocks;

// Prevent direct access 
if ( ! defined( 'ABSPATH' ) ) {die;} ; 


/**
 * Class Gesimatic_Server_Blocks
 *
 * This class contains the code necessary to create the server side gesimatic server blocks
 *
 * @package Gesimatic-server
 */
class Blocks {

  /**
     * Class constructor.
     *
     * Add the necessary hooks
     * 
     */
    function __construct(){

 		// Register GesimaticSever API REST endpoints
        add_action('init',array($this,'register_blocks'));
       
    }

    /**
	 * registers the blocks and block assets 
	 * 
	 * This method registers the Gesimatic Static Forms Blocks and the necesary assets to work.
	 *
	 * @return void
	 */
    function register_blocks(){

        wp_register_script(
            'gesimatic-user-register-editor',
            GESIMATIC_STATIC_FORMS_URL.'blocks/user-register/build/index.js',
            [ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ],
            GESIMATIC_STATIC_FORMS_VERSION
        );

        $wp_roles = wp_roles();
        $all_roles = $wp_roles->get_names();
        
        wp_localize_script(
            'gesimatic-user-register-editor',
            'gesimaticRoles',
            $all_roles,
        );

        wp_register_style(
            'gesimatic-user-register-style',
            GESIMATIC_STATIC_FORMS_URL.'blocks/user-register/build/index.css',
            [],
            GESIMATIC_STATIC_FORMS_VERSION
        );

        register_block_type(GESIMATIC_STATIC_FORMS_PATH. '/blocks/user-register',['render_callback' => [$this,'user_register_render_cb']]);
    }

    /**
	 * renders the user-register block 
	 * 
	 * This method gets the user-register attributes an render to frontend the block.
	 *
	 * @return void
	 */
    function user_register_render_cb($atts){
        error_log ('user_register_render_cb - $atts : '.var_export($atts,true));

        $output = '';

        ob_start();
        ?>
        <form id="<?php echo $atts['formId']; ?>" class="wp-block-gesimatic-static-forms-user-register">
            <?php if($atts['showTitle'] == true) { ?>
                <h2 class='gesimatic-form__title'><?php echo $atts['title']; ?></h2>
            <?php } ?> 
            <label class='gesimatic-form__label'><?php echo $atts['nameLabel']; ?></label>
            <input type="text" class='gesimatic-form__input' style="border-color:<?php echo $atts['elementsColor']; ?>"/>
            <label class='gesimatic-form__label'><?php echo $atts['emailLabel']; ?></label>
            <input type="email" class='gesimatic-form__input' style="border-color:<?php echo $atts['elementsColor']; ?>"/>
            <button type="button" class='gesimatic-form__button' style="background-color:<?php echo $atts['elementsColor']; ?>"><?php echo $atts['buttonLabel']; ?></button>
        </form>


        <?php
        $output = ob_get_contents();
        ob_end_clean();

         return $output;
    }

}
