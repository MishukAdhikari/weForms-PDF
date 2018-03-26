<?php

/**
* WeForms PDF backend
*/
class weforms_pdf {
	
	public function __construct() {
		
	}

	public static function activate() {

        if ( ! function_exists( 'weforms' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            deactivate_plugins( plugin_basename( __FILE__ ) );

            wp_die( '<div class="error"><p>' . sprintf( __( '<b>WeForms PDF</b> requires %sWeForms%s to be installed & activated!', 'weforms-pdf' ), '<a target="_blank" href="https://wordpress.org/plugins/weforms/">', '</a>' ) . '</p></div>' );
        }
	
	}
}