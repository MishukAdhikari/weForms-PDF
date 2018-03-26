<?php

/**
 * Plugin Name:       weForms PDF
 * Plugin URI:        http://wordpress.org/plugins/weforms-pdf
 * Description:       A simple plugin which allow to download weforms data as pdf
 * Version:           1.0.0
 * Author:            Mishuk Adhikari
 * Author URI:        about.me/MishukAdhikari
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       weforms-pdf
 * Domain Path:       /languages
 *
 * 
 * Author Details
 * @link              about.me/MishukAdhikari
 * @since             1.0.0
 * @package           Weforms_Pdf
 *
 * @wordpress-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-weforms-pdf-activator.php
 */
function activate_weforms_pdf() {
	weforms_pdf::activate();
}

register_activation_hook( __FILE__, 'activate_weforms_pdf' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class.weforms_pdf.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function weforms_pdf() {

	$plugin = new weforms_pdf();

}
weforms_pdf();