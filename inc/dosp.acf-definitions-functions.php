<?php

// * DO_Stelselplaat - dosp.acf-definitions-functions.php
// * ----------------------------------------------------------------------------------
// * definitions and aux. functions for Advanced Custom Fields
// * ----------------------------------------------------------------------------------
// * @author            Paul van Buuren
// * @license           GPL-2.0+
// * @package           do-stelselplaat
// * version:           1.0.1
// * @version-desc.     Eerste opzet.
// * @link              https://github.com/ICTU/Digitale-Overheid---WordPress-plugin-Stelselplaat/
// * Text Domain:       do-stelselplaat
// * Domain Path:       /languages


if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}


//========================================================================================================

//if( ( function_exists('acf_add_local_field_group') ) && ( 22 == 33 ) ) {
if( function_exists('acf_add_local_field_group') ) {

  //======================================================================================================

  acf_add_local_field_group(array(
  	'key' => 'group_5c5aeb4e30d19',
  	'title' => 'Onderdelen basisinfraplaat',
  	'fields' => array(
  		array(
  			'key' => 'field_5c5afd07125d7',
  			'label' => 'Titeltekst bij standaarden',
  			'name' => 'title_standaarden',
  			'type' => 'text',
  			'instructions' => '',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => array(
  				'width' => '',
  				'class' => '',
  				'id' => '',
  			),
  			'default_value' => 'Standaarden',
  			'placeholder' => '',
  			'prepend' => '',
  			'append' => '',
  			'maxlength' => '',
  		),
  		array(
  			'key' => 'field_5c5aeb9b846e9',
  			'label' => 'Standaarden',
  			'name' => 'link_standaarden',
  			'type' => 'taxonomy',
  			'instructions' => 'kies de dossiers die hierbij horen',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => array(
  				'width' => '',
  				'class' => '',
  				'id' => '',
  			),
  			'taxonomy' => 'dossiers',
  			'field_type' => 'checkbox',
  			'add_term' => 0,
  			'save_terms' => 0,
  			'load_terms' => 0,
  			'return_format' => 'id',
  			'multiple' => 0,
  			'allow_null' => 0,
  		),
  		array(
  			'key' => 'field_5c5afd4384512',
  			'label' => 'Titeltekst bij voorzieningen',
  			'name' => 'title_voorzieningen',
  			'type' => 'text',
  			'instructions' => '',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => array(
  				'width' => '',
  				'class' => '',
  				'id' => '',
  			),
  			'default_value' => 'Voorzieningen en afsprakenstelsels',
  			'placeholder' => '',
  			'prepend' => '',
  			'append' => '',
  			'maxlength' => '',
  		),
  		array(
  			'key' => 'field_5c5aef757d238',
  			'label' => 'Voorzieningen en afsprakenstelsels',
  			'name' => 'link_voorzieningen',
  			'type' => 'taxonomy',
  			'instructions' => 'kies de dossiers die hierbij horen',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => array(
  				'width' => '',
  				'class' => '',
  				'id' => '',
  			),
  			'taxonomy' => 'dossiers',
  			'field_type' => 'checkbox',
  			'add_term' => 0,
  			'save_terms' => 0,
  			'load_terms' => 0,
  			'return_format' => 'id',
  			'multiple' => 0,
  			'allow_null' => 0,
  		),
  		array(
  			'key' => 'field_5c5afd65f8113',
  			'label' => 'Titeltekst bij basisregistraties',
  			'name' => 'title_basisregistraties',
  			'type' => 'text',
  			'instructions' => '',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => array(
  				'width' => '',
  				'class' => '',
  				'id' => '',
  			),
  			'default_value' => 'Basisregistraties',
  			'placeholder' => '',
  			'prepend' => '',
  			'append' => '',
  			'maxlength' => '',
  		),
  		array(
  			'key' => 'field_5c5af063c868f',
  			'label' => 'Basisregistraties',
  			'name' => 'link_basisregistraties',
  			'type' => 'taxonomy',
  			'instructions' => 'kies de dossiers die hierbij horen',
  			'required' => 0,
  			'conditional_logic' => 0,
  			'wrapper' => array(
  				'width' => '',
  				'class' => '',
  				'id' => '',
  			),
  			'taxonomy' => 'dossiers',
  			'field_type' => 'checkbox',
  			'add_term' => 0,
  			'save_terms' => 0,
  			'load_terms' => 0,
  			'return_format' => 'id',
  			'multiple' => 0,
  			'allow_null' => 0,
  		),
  	),
  	'location' => array(
  		array(
  			array(
  				'param' => 'page_template',
  				'operator' => '==',
  				'value' => 'stelselplaat-template.php',
  			),
  		),
  	),
  	'menu_order' => 0,
  	'position' => 'acf_after_title',
  	'style' => 'default',
  	'label_placement' => 'top',
  	'instruction_placement' => 'label',
  	'hide_on_screen' => array(
  		0 => 'featured_image',
  		1 => 'categories',
  		2 => 'tags',
  	),
  	'active' => 1,
  	'description' => '',
  ));


  //======================================================================================================  

}

//========================================================================================================

