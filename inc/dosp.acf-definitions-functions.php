<?php

// * DO_Stelselplaat - dosp.acf-definitions-functions.php
// * ----------------------------------------------------------------------------------
// * definitions and aux. functions for Advanced Custom Fields
// * ----------------------------------------------------------------------------------
// * @author            Paul van Buuren
// * @license           GPL-2.0+
// * @package           do-stelselplaat
// * version:           2.0.2
// * @version-desc.     CSS kleur voor iconen verbeterd.
// * @link              https://github.com/ICTU/Digitale-Overheid---WordPress-plugin-Stelselplaat/
// * Text Domain:       do-stelselplaat
// * Domain Path:       /languages


if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

/*
paraaf : 2051 electronische paraaf
smartphone : 2054 smartphone
invulformulier : 2069 invulformulier
*/	


//========================================================================================================

if( function_exists('acf_add_local_field_group') ) {

  //======================================================================================================  

	acf_add_local_field_group(array(
		'key' => 'group_5c5aeb4e30d19',
		'title' => 'Stelselplaat-pagina',
		'fields' => array(
			array(
				'key' => 'field_5cee75ec3a6b1',
				'label' => 'Titel in het midden',
				'name' => 'titel_in_het_midden',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'Basisinfrastructuur (GDI)',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5cee79f465902',
				'label' => 'stelselplaatblokken',
				'name' => 'stelselplaatblokken',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_5cee7a1965903',
				'min' => 1,
				'max' => 0,
				'layout' => 'row',
				'button_label' => 'Nieuw blok toevoegen',
				'sub_fields' => array(
					array(
						'key' => 'field_5cee7a1965903',
						'label' => 'Titel',
						'name' => 'stelselplaatblok_titel',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_5cee7a2865904',
						'label' => 'Blok-icoon',
						'name' => 'stelselplaatblok_icoon',
						'type' => 'radio',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'paraaf' => '2051 electronische paraaf',
							'smartphone' => '2054 smartphone',
							'invulformulier' => '2069 invulformulier',
						),
						'allow_null' => 0,
						'other_choice' => 0,
						'default_value' => '',
						'layout' => 'vertical',
						'return_format' => 'value',
						'save_other_choice' => 0,
					),
					array(
						'key' => 'field_5cee7a4a65905',
						'label' => 'Dossiers',
						'name' => 'stelselplaatblok_dossiers',
						'type' => 'taxonomy',
						'instructions' => '',
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
/*					
					array(
						'key' => 'field_5ceeaaeb80b49',
						'label' => 'stelselplaatblok_toelichting',
						'name' => 'stelselplaatblok_toelichting',
						'type' => 'textarea',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'maxlength' => '',
						'rows' => '',
						'new_lines' => '',
					),
*/					
				),
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
		'active' => true,
		'description' => '',
	));

  //======================================================================================================  

}

//========================================================================================================

