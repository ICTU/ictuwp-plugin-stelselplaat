<?php

// * DO_Stelselplaat - dosp.acf-definitions-functions.php
// * ----------------------------------------------------------------------------------
// * definitions and aux. functions for Advanced Custom Fields
// * ----------------------------------------------------------------------------------
// * @author            Paul van Buuren
// * @license           GPL-2.0+
// * @package           do-stelselplaat
// * version:           0.0.1
// * @version-desc.     Eerste opzet.
// * @link              https://github.com/ICTU/Digitale-Overheid---WordPress-plugin-Stelselplaat/
// * Text Domain:       do-stelselplaat
// * Domain Path:       /languages




if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

//========================================================================================================

function do_sp_bidirectional_acf_update_value( $value, $post_id, $field  ) {

	// vars
	$field_name   = $field['name'];
	$field_key    = $field['key'];
	$global_name  = 'is_updating_' . $field_name;

  $debugstring = 'do_sp_bidirectional_acf_update_value';
  
  $debugstring .= "value='" . implode( ", ", $value ) . "'";
  $debugstring .= ", post_id='" . $post_id . "'";
  $debugstring .= " (type=" . get_post_type( $post_id ) . ")";
  $debugstring .= ", field_key='" . $field_key . "'";
  $debugstring .= ", field_name='" . $field_name . "'";
  
  // dodebug( $debugstring );

	// bail early if this filter was triggered from the update_field() function called within the loop below
	// - this prevents an inifinte loop
	if( !empty($GLOBALS[ $global_name ]) ) return $value;
	
	
	// set global variable to avoid inifite loop
	// - could also remove_filter() then add_filter() again, but this is simpler
	$GLOBALS[ $global_name ] = 1;
	
	
	// loop over selected posts and add this $post_id
	if( is_array($value) ) {

    // dodebug( 'do_sp_bidirectional_acf_update_value: is array' );
	
		foreach( $value as $post_id2 ) {

      $debugstring = "post_id2='" . $post_id2 . "'";
      $debugstring .= " (type=" . get_post_type( $post_id2 ) . ")";


			// load existing related posts
			$value2 = get_field($field_name, $post_id2, false);
			
			
			// allow for selected posts to not contain a value
			if( empty($value2) ) {
				
				$value2 = array();
				
			}

			// bail early if the current $post_id is already found in selected post's $value2
			if( in_array($post_id, $value2) ) continue;
			
			
			// append the current $post_id to the selected post's 'related_posts' value
			$value2[] = $post_id;
			
			
			// update the selected post's value (use field's key for performance)
			update_field($field_key, $value2, $post_id2);
			
		}
	
	}
	
	
	// find posts which have been removed
	$old_value = get_field($field_name, $post_id, false);
	
	if( is_array($old_value) ) {
		
		foreach( $old_value as $post_id2 ) {
			
			// bail early if this value has not been removed
			if( is_array($value) && in_array($post_id2, $value) ) continue;
			
			
			// load existing related posts
			$value2 = get_field($field_name, $post_id2, false);
			
			
			// bail early if no value
			if( empty($value2) ) continue;
			
			
			// find the position of $post_id within $value2 so we can remove it
			$pos = array_search($post_id, $value2);
			
			
			// remove
			unset( $value2[ $pos] );
			
			
			// update the un-selected post's value (use field's key for performance)
			update_field($field_key, $value2, $post_id2);
			
		}
		
	}

  // reset global varibale to allow this filter to function as per normal
  $GLOBALS[ $global_name ] = 0;

  // return
  return $value;
    
}

//========================================================================================================

if( function_exists('acf_add_local_field_group') ) {

  //======================================================================================================


  //======================================================================================================


  //======================================================================================================


  //======================================================================================================
  

  //======================================================================================================
  

}

//========================================================================================================

