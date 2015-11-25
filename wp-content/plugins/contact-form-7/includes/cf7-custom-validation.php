<?php
/*
File: Contact Form 7 Custom Validation
Author: Tirumal
Website: http://www.code-tricks.com
Description: This file, when included in functions.php of WordPress theme extends the contact form 7 data validations.
Such as date, email, phone number, zip codes etc. You can also extend the validations just by providing new names and 
respective validaton rules either conditional or reg expressive.
Please note that this is not a plugin. You need to include this file in your theme's functions.php using PHP include()
function.
include("cf7-custom-validation.php");
More information on this post: http://code-tricks.com/contact-form-7-custom-validation-in-wordpress/
*/
//url regex
function get_valid_url( $url ) {
 
    $regex = "((https?|ftp)\:\/\/)?"; // Scheme
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
    $regex .= "(\:[0-9]{2,5})?"; // Port
    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
 
    return preg_match("/^$regex$/", $url);
 
}
// CF7 Custom Validation Rules
function compare_date($date1, $date2) {
  $date1 = explode('/', $date1);
  $date2 = explode('/', $date2);
  
  $output = false;
  
  if($date2[2] > $date1[2]) {
    $output = true;
  } else {
    $output = false;
  }
  
  if($date2[2] == $date1[2]) {
    if($date2[0] > $date1[0]) {
      $output = true;
    } else {
      $output = false;
    }
    
    if($date2[0] == $date1[0]) {
      if($date2[1] >= $date1[1]) {
        $output = true;
      } else {
        $output = false;
      }
    }
  }
  if ($output) {
    return true;
  } else { 
    return false;
  }
  
}



function cf7_custom_form_validation($result,$tag) {
  $type = $tag['type'];
  $name = $tag['name'];
  
  
  if($type == 'text*' && $_POST[$name] == ''){ 
  
	  $result->invalidate( $name, wpcf7_get_message( 'invalid_full_name' ) );
  
  }
//__________________________________________________________________________________________________
  //Comparision date
  $date1 = $_POST['date1'];
  $date2 = $_POST['date2'];
  
  //CheckInDate
  if($name == 'date1'){
    if(!compare_date($date1, $date2)) {
      $result->invalidate( $name, wpcf7_get_message( 'invalid_check_in' ) );
    }
  }
  
//__________________________________________________________________________________________________
  //CheckOutDate
  if($name == 'date2'){
    if(!compare_date($date1, $date2)) {
      $result->invalidate( $name, wpcf7_get_message( 'invalid_check_out' ) );
    }
  }
  
//__________________________________________________________________________________________________
  //url
  if($name == 'url') {
    $url = $_POST['url'];
    
    if($url != '') {
      if(get_valid_url($url)){
        $result['valid'] = true;
      } else {
        $result->invalidate( $name, wpcf7_get_message( 'invalid_url' ) );
      }
    }
  }
  
  
  
//__________________________________________________________________________________________________
  
  //emailAddress
  if($name == 'emailAddress') {
    $emailAddress = $_POST['emailAddress'];
    
    if($emailAddress != '') {
      if(substr($emailAddress, 0, 1) == '.' || !preg_match('/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $emailAddress)) {  
          $result->invalidate( $name, wpcf7_get_message( 'invalid_email' ) );
      }
    }
  }
//__________________________________________________________________________________________________
  
  //datemdy
  if($name == 'datemdy'){
    $datemdy = $_POST['datemdy'];
    
    if($datemdy != '') {
      if(!preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $datemdy)) {
        $result->invalidate( $name, wpcf7_get_message( 'invalid_datemdy' ) );
      }
    }
  }
//__________________________________________________________________________________________________
  //US Zip code validation
  //USZipCode
  if($name == 'USZipCode') {
    $USZipCode = $_POST['USZipCode'];
    
    if($USZipCode != '') {
    //  if(!preg_match('/^([0-9]{5})(-[0-9]{4})?$/i', $USZipCode)) {
        if(!preg_match('/^\d{5}(-\d{4})?$/', $USZipCode)) {
        $result->invalidate( $name, wpcf7_get_message( 'invalid_zipcode' ) );
      }
    }
  }
  
  
//__________________________________________________________________________________________________
  // CANADA Zip code validation
  //CANZipCode
  if($name == 'CANZipCode') {
    $CANZipCode = $_POST['CANZipCode'];
    
    if($CANZipCode != '') {
        if(!preg_match('/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$/', $CANZipCode)) {
        $result->invalidate( $name, wpcf7_get_message( 'invalid_zipcode' ) );
      }
    }
  }
  
//__________________________________________________________________________________________________
  // US and CANADA Zip code validation
  //USCANZipCode
  if($name == 'USCANZipCode') {
    $USCANZipCode = $_POST['USCANZipCode'];
    
    if($USCANZipCode != '') {
        if(!preg_match('/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$/', $USCANZipCode)) {
            if(!preg_match('/^\d{5}(-\d{4})?$/', $USCANZipCode)) {
            $result->invalidate( $name, wpcf7_get_message( 'invalid_zipcode' ) );
          }
        }
    }
  }
  
  
//__________________________________________________________________________________________________
  // Indian Postal code validation
  //inPostalCode
  if($name == 'inPostalCode') {
    $inPostalCode = $_POST['inPostalCode'];
    
    if($inPostalCode != '') {
        if(!preg_match('/^[0-9]{6,6}$/', $inPostalCode)) {
            $result->invalidate( $name, wpcf7_get_message( 'invalid_zipcode_india' ) );
        }
    }
  }
  
//__________________________________________________________________________________________________
//This section updated on 22nd March 2013
// It will accept character, character + numeric value
// It will not accept special characters
  //fullName
  $allNames = array('fullName', 'fullName1');
  foreach($allNames as $uniNames) {
    if($name == $uniNames) {
      $fullName = $_POST[$uniNames];
        if($fullName != '') {
          if(!preg_match('/^[A-Z0-9][a-zA-Z0-9 ]+$/i', $fullName)) {
            $result['valid'] = false;
            $result->invalidate( $name, wpcf7_get_message( 'invalid_full_name' ) );
          }
          
          if(is_numeric($fullName)){
            $result->invalidate( $name, wpcf7_get_message( 'invalid_full_name' ) );
          }
        }
    }
  }
//__________________________________________________________________________________________________    
    
  //acceptNum
  $acceptNumbers = array('acceptNumber', 'acceptNumber1', 'acceptNumber2', 'acceptNumber3', 'acceptNumber4', 'acceptNumber5', 'acceptNumber6');
  
  foreach($acceptNumbers as $acceptNumber){
    if($name == $acceptNumber) {
      $acceptNum = $_POST[$acceptNumber];
      
      if($acceptNum != '') {
        if(ctype_digit($acceptNum)) {
          $result['valid'] = true;
        } else {
          $result->invalidate( $name, wpcf7_get_message( 'invalid_numbers_only' ) );
        }
      }
    }
  }
  
  
  //__________________________________________________________________________________________________
  //faxNumber
  $faxNumbers = array('faxNumber', 'faxNumber1', 'faxNumber2', 'faxNumber3', 'faxNumber4', 'faxNumber5', 'faxNumber6');
  foreach($faxNumbers as $faxNum) {
    if($name == $faxNum) {
      $faxNumber = $_POST[$faxNum];
      $contRegex = '/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/';
      if ($faxNumber != '') {
        if(!preg_match($contRegex, $faxNumber) && strlen($faxNumber) > 10 && strlen($faxNumber) < 18) {
            $result->invalidate( $name, wpcf7_get_message( 'invalid_fax' ) );
        }
      }
    }
  }
//__________________________________________________________________________________________________
  //Agreement fields with the expected values. Ex. write ,,yes" to agree
  $agreement = array('agreement','agreement1','agreement2');
  $expectedValues = array('yes', 'Yes', 'YES');
  foreach ($agreement as $a => $aValue) {
    if($name == $aValue) {
      foreach ($expected_values as $i => $eValue) {
        if($eValue == $_POST[$name]) {
            $result['valid'] = true; break;
        } else {
          $result->invalidate( $name, wpcf7_get_message( 'invalid_agree_terms' ) );
        }
      }
    }
   }
//__________________________________________________________________________________________________
  //Only Characters 
  $OnlyChars = array('onlyChar', 'onlyChar1', 'onlyChar2');
  foreach($OnlyChars as $OnlyChar){
    if($name == $OnlyChar) {
      $onlyChar = $_POST[$OnlyChar];
      if($onlyChar != '') {
        $containsLettersOrNumbers = (preg_match('~[0-9a-z]~i', $onlyChar) > 0);
        if(!$containsLettersOrNumbers
          || is_numeric($onlyChar)
          || strlen($onlyChar > 64) ) {
            $result->invalidate( $name, wpcf7_get_message( 'invalid_chars_only' ) );
        }
  
          if(is_numeric($onlyChar)){
              $result->invalidate( $name, wpcf7_get_message( 'invalid_chars_only' ) );
          }
      }
    }
  }
//__________________________________________________________________________________________________
  //validPhone
  /*
  49-4312 / 777 777
  +1 (305) 613-0958 x101
  (305) 613 09 58 ext 101
  3056130958
  +33 1 47 37 62 24 extension 3
  (016977) 1234
  04312 - 777 777
  91-12345-12345
  +58 295416 7216
  */
  
  $phoneNumbersAll = array('validPhone', 'validPhone1', 'validPhone2', 'validPhone3', 'validPhone4', 'validPhone5', 'validPhone6');
  
  foreach($phoneNumbersAll as $validPhoneVal) {
    if($name == $validPhoneVal) {
      $validPhone = $_POST[$validPhoneVal];
      
      if($validPhone != '') {
        if(preg_match('/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/', $validPhone) 
        
        || preg_match('/^([\+][0-9]{1,3}[\ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9\ \.\-\/]{3,20})((x|ext|extension)[\ ]?[0-9]{1,4})?$/', $validPhone) 
        
        && strlen($validPhone) > 9 
        
        && strlen($validPhone) < 30 
        
        && (int)($validPhone)) {
          //$result['valid'] = true;
        } else {
          $result->invalidate( $name, wpcf7_get_message( 'invalid_tel' ) );
        }
      }
    }
  }
//__________________________________________________________________________________________________
  return $result;
}
//add filter for text field validation
add_filter('wpcf7_validate_text','cf7_custom_form_validation', 10, 2); // text field
add_filter('wpcf7_validate_text*', 'cf7_custom_form_validation', 10, 2); // Req. text field

/*
TEXTAREA VALIDATIONS
*/
function cf7_custom_textarea_validation($result, $tag) {
  $type = $tag['type'];
  $name = $tag['name'];
  
  //if empty give required field error
  if($type == 'textarea*' && $_POST[$name] == ''){
      $result->invalidate( $name, wpcf7_get_message( 'invalid_required' ) );
  }
  
  //validations begins
//__________________________________________________________________________________________________
// Address text area validation 
  if($name == 'addressTa'){
    $addressTa = $_POST['addressTa'];
    
    if($addressTa != '') {
      if(strlen($addressTa) > 300) {
        $result->invalidate( $name, wpcf7_get_message( 'invalid_too_long' ) );
      }
    }
  }
  
  return $result;
  
}
//add fiter for text area validation
add_filter( 'wpcf7_validate_textarea', 'cf7_custom_textarea_validation', 10, 2 );
add_filter( 'wpcf7_validate_textarea*', 'cf7_custom_textarea_validation', 10, 2 );




function wpcf7_text_validation_filter1( $result, $tag ) {
	$tag = new WPCF7_Shortcode( $tag );

	 $name = $tag->name; 

	$value = isset( $_POST[$name] )
		? trim( wp_unslash( strtr( (string) $_POST[$name], "\n", " " ) ) )
		: '';

	if ( 'text' == $tag->basetype ) {
		if ( $tag->is_required() && '' == $value ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
		}
	}

	/*if ( 'email' == $tag->basetype ) {
		if ( $tag->is_required() && '' == $value ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
		} elseif ( '' != $value && ! wpcf7_is_email( $value ) ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_email' ) );
		}
	}

	if ( 'url' == $tag->basetype ) {
		if ( $tag->is_required() && '' == $value ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
		} elseif ( '' != $value && ! wpcf7_is_url( $value ) ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_url' ) );
		}
	}

	if ( 'tel' == $tag->basetype ) {
		if ( $tag->is_required() && '' == $value ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
		} elseif ( '' != $value && ! wpcf7_is_tel( $value ) ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_tel' ) );
		}
	}

	if ( ! empty( $value ) ) {
		$maxlength = $tag->get_maxlength_option();
		$minlength = $tag->get_minlength_option();

		if ( $maxlength && $minlength && $maxlength < $minlength ) {
			$maxlength = $minlength = null;
		}

		$code_units = wpcf7_count_code_units( $value );

		if ( false !== $code_units ) {
			if ( $maxlength && $maxlength < $code_units ) {
				$result->invalidate( $tag, wpcf7_get_message( 'invalid_too_long' ) );
			} elseif ( $minlength && $code_units < $minlength ) {
				$result->invalidate( $tag, wpcf7_get_message( 'invalid_too_short' ) );
			}
		}
	}*/

	return $result;
}
add_filter( 'wpcf7_validate_text', 'wpcf7_text_validation_filter1', 10, 2 );
add_filter( 'wpcf7_validate_text*', 'wpcf7_text_validation_filter1', 10, 2 );
add_filter( 'wpcf7_validate_email', 'wpcf7_text_validation_filter1', 10, 2 );
add_filter( 'wpcf7_validate_email*', 'wpcf7_text_validation_filter1', 10, 2 );
add_filter( 'wpcf7_validate_url', 'wpcf7_text_validation_filter1', 10, 2 );
add_filter( 'wpcf7_validate_url*', 'wpcf7_text_validation_filter1', 10, 2 );
add_filter( 'wpcf7_validate_tel', 'wpcf7_text_validation_filter1', 10, 2 );
add_filter( 'wpcf7_validate_tel*', 'wpcf7_text_validation_filter1', 10, 2 );


/*function message1($result=null,$tag=null){   
//print_r($tag);
   $type = $tag['type'];
   $name = $tag['name'];
  
  //echo $_POST[$name];
  
   $messages = array(
		'mail_sent_ok' => array(
			'description'
				=> __( "Sender's message was sent successfully", 'contact-form-7' ),
			'default'
				=> __( 'Your message was sent successfully. Thanks.', 'contact-form-7' )
		),

		'mail_sent_ng' => array(
			'description'
				=> __( "Sender's message was failed to send", 'contact-form-7' ),
			'default'
				=> __( 'Failed to send your message. Please try later or contact the administrator by another method.', 'contact-form-7' )
		),

		'validation_error' => array(
			'description'
				=> __( "Validation errors occurred", 'contact-form-7' ),
			'default'
				=> __( 'Validation errors occurred. Please confirm the fields and submit it again.', 'contact-form-7' )
		),

		'spam' => array(
			'description'
				=> __( "Submission was referred to as spam", 'contact-form-7' ),
			'default'
				=> __( 'Failed to send your message. Please try later or contact the administrator by another method.', 'contact-form-7' )
		),

		'accept_terms' => array(
			'description'
				=> __( "There are terms that the sender must accept", 'contact-form-7' ),
			'default'
				=> __( 'Please accept the terms to proceed.', 'contact-form-7' )
		),

		'invalid_required' => array(
			'description'
				=> __( "There is a field that the sender must fill in", 'contact-form-7' ),
			'default'
				=> __( 'Please fill in the required field.', 'contact-form-7' )
		),

		'invalid_too_long' => array(
			'description'
				=> __( "There is a field that the user input is longer than the maximum allowed length", 'contact-form-7' ),
			'default'
				=> __( 'This input is too long.', 'contact-form-7' )
		),

		'invalid_too_short' => array(
			'description'
				=> __( "There is a field that the user input is shorter than the minimum allowed length", 'contact-form-7' ),
			'default'
				=> __( 'This input is too short.', 'contact-form-7' )
		)
	);  

return array_merge( $messages, array(
      'invalid_full_name' => array(
        'description' => __( "User enters a name that doesn't appear to be two names (a full name)", 'contact-form-7' ),
        'default' => __( 'Please include your full name.', 'contact-form-7' )
      ),
      'invalid_chars_only' => array(
        'description' => __( "Test User enters a number in a field that should be characters only", 'contact-form-7' ),
        'default' => __( 'Please include characters only.', 'contact-form-7' )
      ),
      'invalid_numbers_only' => array(
        'description' => __( "User enters a character in a field that should be numbers only", 'contact-form-7' ),
        'default' => __( 'Please include numbers only.', 'contact-form-7' )
      ),
      'invalid_zipcode' => array(
        'description' => __( "User enters a zipcode that is invalid in format", 'contact-form-7' ),
        'default' => __( 'Zipcode seems invalid.', 'contact-form-7' )
      ),
      'invalid_zipcode_india' => array(
        'description' => __( "User enters an invalid Indian zipcode", 'contact-form-7' ),
        'default' => __( 'Entered Pin code for India is Invalid.', 'contact-form-7' )
      ),
      'invalid_fax' => array(
        'description' => __( "Fax number that the sender entered is invalid", 'contact-form-7' ),
        'default' => __( 'Fax number seems invalid.', 'contact-form-7' )
      ),
      'invalid_check_in' => array(
        'description' => __( "Check In date is before Check Out date", 'contact-form-7' ),
        'default' => __( 'Logical Error: Check in date should be before check out date.', 'contact-form-7' )
      ),
      'invalid_check_out' => array(
        'description' => __( "Check Out date is before Check In date", 'contact-form-7' ),
        'default' => __( 'Logical Error: Check out date should be After check in date.', 'contact-form-7' )
      ),
      'invalid_date_mdy' => array(
        'description' => __( "Entered date is not in format MM-DD-YYYY", 'contact-form-7' ),
        'default' => __( 'Please enter date in MM-DD-YYYY format.', 'contact-form-7' )
      )
    ));
}
add_filter( 'wpcf7_messages', 'message1', 10, 2 );
add_filter('wpcf7_validate_text','message1', 10, 2); // text field
add_filter('wpcf7_validate_text*', 'message1', 10, 2); // Req. text field

*/



/*
Add Custom Error Messages
*/
/*
if ( !function_exists( 'cf7_custom_validation_messages' ) ) {
  function cf7_custom_validation_messages( $messages ) {
    
   
    return array_merge( $messages, array(
      'invalid_full_name' => array(
        'description' => __( "User enters a name that doesn't appear to be two names (a full name)", 'contact-form-7' ),
        'default' => __( 'Please include your full name.', 'contact-form-7' )
      ),
      'invalid_chars_only' => array(
        'description' => __( "User enters a number in a field that should be characters only", 'contact-form-7' ),
        'default' => __( 'Please include characters only.', 'contact-form-7' )
      ),
      'invalid_numbers_only' => array(
        'description' => __( "User enters a character in a field that should be numbers only", 'contact-form-7' ),
        'default' => __( 'Please include numbers only.', 'contact-form-7' )
      ),
      'invalid_zipcode' => array(
        'description' => __( "User enters a zipcode that is invalid in format", 'contact-form-7' ),
        'default' => __( 'Zipcode seems invalid.', 'contact-form-7' )
      ),
      'invalid_zipcode_india' => array(
        'description' => __( "User enters an invalid Indian zipcode", 'contact-form-7' ),
        'default' => __( 'Entered Pin code for India is Invalid.', 'contact-form-7' )
      ),
      'invalid_fax' => array(
        'description' => __( "Fax number that the sender entered is invalid", 'contact-form-7' ),
        'default' => __( 'Fax number seems invalid.', 'contact-form-7' )
      ),
      'invalid_check_in' => array(
        'description' => __( "Check In date is before Check Out date", 'contact-form-7' ),
        'default' => __( 'Logical Error: Check in date should be before check out date.', 'contact-form-7' )
      ),
      'invalid_check_out' => array(
        'description' => __( "Check Out date is before Check In date", 'contact-form-7' ),
        'default' => __( 'Logical Error: Check out date should be After check in date.', 'contact-form-7' )
      ),
      'invalid_date_mdy' => array(
        'description' => __( "Entered date is not in format MM-DD-YYYY", 'contact-form-7' ),
        'default' => __( 'Please enter date in MM-DD-YYYY format.', 'contact-form-7' )
      )
    ));
  }
  add_filter( 'wpcf7_messages', 'cf7_custom_validation_messages' );
}*/
?>