<?php

/**
 * Weforms PDF
 *
 * Main Class to generate
 * and download weforms
 * data as pdf
 */
class weforms_pdf {
	
	public function __construct() {

        add_action( 'admin_post_weforms_pdf_view', array( $this, 'download_pdf' ) );

        add_filter( 'weforms_get_entry_columns', array( $this, 'pdf_column' ) );

        add_filter( 'weforms_get_entries', array( $this, 'download_link' ) );
		
	}

	/**
	 * Callback while activation
	 * Check if weforms activate
	 * 
	 * @return void
	 */
	public static function activate() {

        if ( ! function_exists( 'weforms' ) ) {
            
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            
            deactivate_plugins( plugin_basename( __FILE__ ) );

            wp_die( '<div class="error" style="text-align:center"><p>' . sprintf( __( '<b>WeForms PDF</b> requires %sWeForms%s to be installed & activated!', 'weforms-pdf' ), '<a target="_blank" href="https://wordpress.org/plugins/weforms/">', '</a>' ) . '</p><p><small>' . sprintf( __( '%sreturn to plugins dashboard%s', 'weforms-pdf' ), '<a href="'.admin_url('plugins.php').'">', '</a>' ) . '</small></p></div>' );
        }
	
	}

	/**
	 * Create pdf download link to backend
	 * 
	 * @param  int $entries
	 * @param  int $form_id
	 * @return string
	 */
    public function download_link( $entries, $form_id ) {

        foreach ($entries as $key => $entry) {

           $entry->fields['weforms_pdf'] = '<a href="'.admin_url('admin-post.php?action=weforms_pdf_view&entry_id='.$entry->id ).'">Download PDF</a>';
        }

        return $entries;
    }

    /**
     * Add column to entries field
     * 
     * @param  string $column 
     * @param  int $form_id
     * @return array         
     */
    public function pdf_column( $column, $form_id ) {

        $column['weforms_pdf'] = 'WeForms PDF';

        return $column;
    }

    /**
     * Prepare pdf for download
     * 
     * @return void
     */
    public function download_pdf() {

    	require_once plugin_dir_path( __DIR__ ). 'fpdf/fpdf.php';

    	if (!empty($_REQUEST['entry_id'])) {

    		$form_entry_id = $_REQUEST['entry_id'];
    	
    	} else {
    		
    		$form_entry_id = '';
    	
    	}

        global $wpdb;

        $query = 'SELECT form_id FROM ' . $wpdb->weforms_entries . ' WHERE id = %d';

        $form_id = $wpdb->get_var( $wpdb->prepare( $query, $form_entry_id ) );

        $form       = weforms()->form->get($form_id);
        
        $entry      = $form->entries()->get($form_entry_id);
        
        $fields     = $entry->get_fields();
        
        $submission_status = weforms_get_entry( $form_entry_id );

        $user_info = get_userdata($submission_status->user_id);

        $form_name   = get_the_title( $form_id );
        
        $header_type = weforms_get_settings( 'pdf_header_type' , 'title' );
        
        $logo = weforms_get_settings('pdf_logo');
        
        $fpdf = new FPDF();
        
        $fpdf->SetMargins(20,20,20);
        
        $fpdf->AddPage();

        $fpdf->SetFont('Arial','B',20);
        $fpdf->SetY(25);
        $fpdf->Cell(62,10, get_bloginfo( 'name' ), null, null, 'L');

        $fpdf->SetFont('Arial','B',10);
        $fpdf->SetY(25);
        $fpdf->SetX(130);
        $fpdf->Cell(62,10, 'Generated From', null, null, 'R');
        $fpdf->SetY(29);
        $fpdf->SetX(130);
        $fpdf->SetFont('Arial','',8);
        $fpdf->Cell(62,10, get_bloginfo( 'url' ), null, null, 'R');
        $fpdf->Ln();
        $fpdf->Ln();
        $fpdf->SetFont('Arial','B', 12);
        $fpdf->Cell(85,10, 'Form Field', 'B');
        $fpdf->Cell(86,10, 'Field Value', 'B');
        $fpdf->SetFont('Arial','',14);
        $fpdf->Ln();


        foreach( $fields as $field ) {

            $field_value = $field['value'];

            if (is_array($field_value)) {

                $field_value = implode( ", ", $field_value );

            } elseif ( is_serialized( $field_value ) ) {

                $field_value = implode(',', unserialize( $field_value ));

            }

            $fpdf->Cell(85,10, $field['label']);

            if ( $field['type'] == 'image_upload' ){

                $fpdf->Image($field_value, null, null, 85, 50);
            
            } else {

                $fpdf->Write(10, strip_tags($field_value));
            
            }

            $fpdf->Ln();
        }

        $fpdf->Ln();
        
        $fpdf->Ln();

        $fpdf->Cell(null,null, null, 'B');

        $fpdf->Ln();
        
        $fpdf->Ln();

        $fpdf->SetFont('Arial','B', 10);
        
        $fpdf->Cell(85,10, 'Form Name: '.$form_name);

        $fpdf->SetFont('Arial','', 10);
        
        $fpdf->Cell(85,10, 'Submitted on: '.date_i18n( 'F j Y, g: i a', strtotime($submission_status->created_at)), null, null, 'R');
        
        $fpdf->Ln(5);

        $fpdf->Cell(85,10, 'Submitted by: '.$user_info->user_login);

        $fpdf->output('I', get_the_title( $form_id ).'-'.$form_id.'.pdf');

    }

}