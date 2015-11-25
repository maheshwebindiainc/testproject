<?php
/*
Plugin Name: CF7 Field Validation
Plugin URI: http://resumedirectory.in
Description: This plugin provides custom error messages for each fields in contact form7.
Version: 1.0
Author: Aiyaz, maheshpatel	
Author URI: http://resumedirectory.in
License: GPL2
*/

function action_cf7cv_save_contact_form( $contact_form ) 
{
	
	$tags = $contact_form->form_scan_shortcode();  
	$post_id = $contact_form->id();	

	foreach ($tags as $value) {
		
		if($value['type'] == 'text*' || $value['type'] == 'email*' || $value['type'] == 'textarea*' || $value['type'] == 'tel*'
		|| $value['type'] == 'url*' || $value['type'] == 'checkbox*' || $value['type'] == 'file*'){
			$key = "_cf7cm_".$value['name']."-valid";
			$vals = sanitize_text_field($_POST[$key]);
			$all_meta_keys[] = $key;
			update_post_meta($post_id,$key, $value['name']);  
		}
		
	}
	
}
add_action( 'wpcf7_save_contact_form', 'action_cf7cv_save_contact_form', 9, 1 );

function action_wpcf7_after_create( $instance ) 
{
    $tags = $instance->form_scan_shortcode();  
	$post_id = $instance->id(); 
	 
	foreach ($tags as $value) {
	
		if($value['type'] == 'text*' || $value['type'] == 'email*' || $value['type'] == 'textarea*' || $value['type'] == 'tel*'
		|| $value['type'] == 'url*' || $value['type'] == 'checkbox*' || $value['type'] == 'file*'){
			$key = "_cf7cm_".$value['name']."-valid";
			$vals = sanitize_text_field($_POST[$key]);
			update_post_meta($post_id,$key, $value['name']); 
		}
	}
}
add_action( 'wpcf7_after_create', 'action_wpcf7_after_create', 9, 1 );

function get_meta_values($p_id ='', $key = '') {

    global $wpdb;
    if( empty( $key ) )
        return;
  
    $r = $wpdb->get_results( "SELECT pm.meta_value FROM {$wpdb->postmeta} pm WHERE pm.meta_key LIKE '%$key%' AND pm.post_id = $p_id ");

    return $r;
}


function cf7cv_custom_validation_messages( $messages ) {

	if(isset($_GET['post']) && !empty($_GET['post']) ){
		
		$p_id = $_GET['post']; 
		$p_val = get_meta_values($p_id, '_cf7cm');
	  
		foreach ($p_val as $value) {
			$key = $value->meta_value;
			$newmsg = array(
			 'description' => __( "Error message for $value->meta_value field", 'contact-form-7' ),
			 'default' => __( "Please fill in the required field.", 'contact-form-7' ));
			 
			 $messages[$key] = $newmsg ;
		}
	  
	}
 	return $messages;
}

add_filter( 'wpcf7_messages', 'cf7cv_custom_validation_messages', 10, 1 );

  
function cf7cv_custom_form_validation($result,$tag) { 
 $type = $tag['type'];
 $name = $tag['name'];
 if(empty(wpcf7_get_message( $name ))){
	$name="invalid_required";
 }
 if($type == 'text*' && $_POST[$name] == ''){   
  $result->invalidate( $name, wpcf7_get_message( $name ) );
 }
 if($type == 'email*' && $_POST[$name] == ''){   
  $result->invalidate( $name, wpcf7_get_message( $name ) );  
 }
 if($type == 'email*' && $_POST[$name] != '') {
      if(substr($_POST[$name], 0, 1) == '.' ||
   !preg_match('/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $_POST[$name])) {  
         $result->invalidate( $name, wpcf7_get_message($name) );
  } 
    }
 if($type == 'textarea*' && $_POST[$name] == ''){   
  $result->invalidate( $name, wpcf7_get_message( $name ) );
 }
 if($type == 'tel*' && $_POST[$name] == ''){   
  $result->invalidate( $name, wpcf7_get_message( $name ) );
 }
 if($type == 'url*' && $_POST[$name] == ''){   
  $result->invalidate( $name, wpcf7_get_message( $name ) );
 }
 if($type == 'checkbox*' && $_POST[$name] == ''){   
  $result->invalidate( $name, wpcf7_get_message( $name ) );
 }
 if($type == 'file*' && $_POST[$name] == ''){   
  $result->invalidate( $name, wpcf7_get_message( $name ) );
 }
 
 return $result;
} 


add_filter('wpcf7_validate_text*', 'cf7cv_custom_form_validation', 10, 2); // Req. text field  
add_filter('wpcf7_validate_email*', 'cf7cv_custom_form_validation', 10, 2); // Req. email field  
add_filter('wpcf7_validate_textarea*', 'cf7cv_custom_form_validation', 10, 2); // Req. textarea field  
add_filter('wpcf7_validate_tel*', 'cf7cv_custom_form_validation', 10, 2); // Req. telephone field  
add_filter('wpcf7_validate_url*', 'cf7cv_custom_form_validation', 10, 2); // Req. URL field  
add_filter('wpcf7_validate_checkbox*', 'cf7cv_custom_form_validation', 10, 2); // Req. checkbox field
add_filter('wpcf7_validate_file*', 'cf7cv_custom_form_validation', 10, 2); // Req. File field

?>