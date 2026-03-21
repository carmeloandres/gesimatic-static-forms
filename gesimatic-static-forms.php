<?php
/** 
 * Gesimatic Static Forms
 * 
 * @package     gesimatic-static-forms
 * @since       1.0.0
 * @author      Carmelo Andrés
 * @license     Saas license
 * @copyright   2025 Carmelo Andrés
 * 
 * @wordpress-plugin
 * Plugin Name:         Gesimatic static forms
 * Description:         This plugin provide some static forms to use as a block or a shortcodes and their ows functionality.
 * Version:             1.0
 * Requires at least:   6.2
 * Requires PHP:      	7.0
 * Author:              Carmelo Andrés
 * Author URI:          https://carmeloandres.com
 * License:             saas license
 * License URI:       	https://www.gesimatic/saas-terms
 * Text Domain:         gesimatic-static-forms
 * Domain Path:         /languages
 */ 

defined( 'ABSPATH' ) || exit; // To prevent direct access

/**
 * Configuration constants.
 *
 * @since 1.0.0
 *
 * @const string GESIMATIC_STATIC_FORMS_PATH      Absolute path to the plugin directory.
 * @const string GESIMATIC_STATIC_FORMS_URL       Absolute URL to the plugin directory.
 * @const int    GESIMATIC_STATIC_FORMS_VERSION   Plugin version.
 */
define ('GESIMATIC_STATIC_FORMS_PATH',plugin_dir_path(__FILE__));
if (function_exists('is_multisite') && is_multisite()) {
    define ('GESIMATIC_STATIC_FORMS_URL',esc_url( network_site_url()).'wp-content/plugins/gesimatic-static-forms/');
} else { define ('GESIMATIC_STATIC_FORMS_URL',home_url('/wp-content/plugins/gesimatic-static-forms/')); }
define ('GESIMATIC_STATIC_FORMS_VERSION',425);

/**
 * Loads the Composer autoloader for the plugin.
 *
 * This enables automatic loading of classes defined in the `includes/` directory
 * according to the PSR-4 configuration in `composer.json`.
 *
 * @since 1.0.0
 * @uses GSMTC_FLAGS_PATH . 'vendor/autoload.php'
 */
/**
 * Autoload dependencies either via Composer or manually.
*/

if (file_exists(GESIMATIC_STATIC_FORMS_PATH . 'vendor/autoload.php')) {
    require_once GESIMATIC_STATIC_FORMS_PATH . 'vendor/autoload.php';
} else {
    /**
     * Basic PSR-4 compliant autoloader.
     *
     * @param string $class Fully qualified class name.
     * @return void
    */
    spl_autoload_register(function ($class) {
        $prefix = 'GesimaticStaticForms\\';
        $base_dir = GESIMATIC_STATIC_FORMS_PATH . 'includes/';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    });
}

/**
 * Registers the plugin activation hook for the plugin.
 *
 * When the plugin is activated, WordPress will automatically execute
 * the {@see gesimatic_translations_activate()} function to perform the initial
 * setup tasks required by the plugin.
 *
 * @since 1.0.0
 *
 * @param string $file     The path to the main plugin file (`__FILE__`).
 * @param string $callback The name of the function to execute on activation.
 *
 * @see https://developer.wordpress.org/reference/functions/register_activation_hook/
 */
register_activation_hook(__FILE__,'gesimatic_static_forms_activate');

/**
 * Plugin activation handler
 *
 * @param bool $network_wide Whether the plugin is being activated network-wide.
 */

function gesimatic_static_forms_activate($network_wide){
    // Check if gesimatic is active
    if (!is_plugin_active('gesimatic/gesimatic.php')) {
        // If gesimatic is not active, show to the user a message.
        deactivate_plugins('gesimatic-server/index.php');
        wp_die(__('This plugin requires the Gesimatic plugin to be activated !!!  Please install and activate Gesimatic to use this plugin.','gsmtc-server'));
    }
    if (is_multisite() && $network_wide) {
        $sites = get_sites();
        foreach ($sites as $site) {
            switch_to_blog($site->blog_id);
            gesimatic_static_forms_single_site_setup();
            restore_current_blog();
        }
    } else {
        gesimatic_static_forms_single_site_setup();
    }
}

/**
 * Function for single site activation
 */
function gesimatic_static_forms_single_site_setup(){
    if (class_exists('\GesimaticStaticForms\Core\Setup')){
        \GesimaticStaticForms\Core\Setup::activate();
    }
}

// Creation of a new site after plugin activation
add_action('wp_initialize_site', 'gesimatic_static_forms_new_site_setup', 10, 1);

/**
 * Function for new single site activation in a multisite
 */

function gesimatic_static_forms_new_site_setup($new_site){
	switch_to_blog($new_site->blog_id);

	gesimatic_static_forms_single_site_setup();

	restore_current_blog();

}

$Gsmtc_static_forms = new GesimaticStaticForms\Core\Core();




