<?php

/**
* WeForms PDF backend
*/
class weforms_pdf {
	
	public function __construct() {

        add_action( 'admin_post_weforms_pdf_view', array( $this, 'download_pdf' ) );

        add_filter( 'weforms_get_entry_columns', array( $this, 'pdf_column' ) );

        add_filter( 'weforms_get_entries', array( $this, 'download_link' ) );
		
	}

	public static function activate() {

        if ( ! function_exists( 'weforms' ) ) {
            
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            
            deactivate_plugins( plugin_basename( __FILE__ ) );

            wp_die( '<div class="error" style="text-align:center"><p>' . sprintf( __( '<b>WeForms PDF</b> requires %sWeForms%s to be installed & activated!', 'weforms-pdf' ), '<a target="_blank" href="https://wordpress.org/plugins/weforms/">', '</a>' ) . '</p><p><small>' . sprintf( __( '%sreturn to plugins dashboard%s', 'weforms-pdf' ), '<a href="'.admin_url('plugins.php').'">', '</a>' ) . '</small></p></div>' );
        }
	
	}

    public function download_link( $entries, $form_id ) {

        foreach ($entries as $key => $entry) {

           $entry->fields['weforms_pdf'] = '<a href="'.admin_url('admin-post.php?action=weforms_pdf_view&entry_id='.$entry->id ).'">Download PDF</a>';
        }

        return $entries;
    }

    public function pdf_column( $column, $form_id ) {

        $column['weforms_pdf'] = 'WeForms PDF';

        return $column;
    }

}