<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

//========================================================================================================

/**
 * Register post type
 */
function do_sp_init_register_post_type() {

  $typeUC_single = _x( "Voorziening", "labels", "do-stelselplaat" );
  $typeUC_plural = _x( "Voorzieningen", "labels", "do-stelselplaat" );
  
  $typeLC_single = _x( "voorziening", "labels", "do-stelselplaat" );
  $typeLC_plural = _x( "voorzieningen", "labels", "do-stelselplaat" );

	$labels = array(
		"name"                  => sprintf( '%s', $typeUC_single ),
		"singular_name"         => sprintf( '%s', $typeUC_single ),
		"menu_name"             => sprintf( '%s', $typeUC_single ),
		"all_items"             => sprintf( _x( 'All %s', 'labels', "do-stelselplaat" ), $typeLC_plural ),
		"add_new"               => sprintf( _x( 'Add %s', 'labels', "do-stelselplaat" ), $typeLC_plural ),
		"add_new_item"          => sprintf( _x( 'Add new %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"edit"                  => _x( "Edit?", "labels", "do-stelselplaat" ),
		"edit_item"             => sprintf( _x( 'Edit %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"new_item"              => sprintf( _x( 'Add %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"view"                  => _x( "Show", "labels", "do-stelselplaat" ),
		"view_item"             => sprintf( _x( 'View %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"search_items"          => sprintf( _x( 'Search %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"not_found"             => sprintf( _x( 'No %s available', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"not_found_in_trash"    => sprintf( _x( 'No %s in trash', 'labels', "do-stelselplaat" ), $typeLC_plural ),
		"parent"                => _x( "Parent", "labels", "do-stelselplaat" ),
	);

	$args = array(
    "label"                 => $typeUC_plural,
    "labels"                => $labels,
    "description"           => "",
    "public"                => true,
    "publicly_queryable"    => true,
    "show_ui"               => true,
    "show_in_rest"          => false,
    "rest_base"             => "",
    "has_archive"           => false,
    "menu_icon"             => "dashicons-media-document",          
    "show_in_menu"          => true,
    "exclude_from_search"   => false,
    "capability_type"       => "post",
    "map_meta_cap"          => true,
    "hierarchical"          => false,
    "rewrite"               => array( "slug" => DO_SP_VOORZIENING_CPT, "with_front" => true ),
    "query_var"             => true,
		"supports"              => array( "title", "editor" ),					
	);
		
	register_post_type( DO_SP_VOORZIENING_CPT, $args );
	
  //------------------------------------------------------------------------------------------------------
  
  $typeUC_single = _x( "Basisregistratie", "labels", "do-stelselplaat" );
  $typeUC_plural = _x( "Basisregistraties", "labels", "do-stelselplaat" );
  
  $typeLC_single = _x( "basisregistratie", "labels", "do-stelselplaat" );
  $typeLC_plural = _x( "basisregistraties", "labels", "do-stelselplaat" );

	$labels = array(
		"name"                  => sprintf( '%s', $typeUC_single ),
		"singular_name"         => sprintf( '%s', $typeUC_single ),
		"menu_name"             => sprintf( '%s', $typeUC_single ),
		"all_items"             => sprintf( _x( 'All %s', 'labels', "do-stelselplaat" ), $typeLC_plural ),
		"add_new"               => sprintf( _x( 'Add %s', 'labels', "do-stelselplaat" ), $typeLC_plural ),
		"add_new_item"          => sprintf( _x( 'Add new %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"edit"                  => _x( "Edit?", "labels", "do-stelselplaat" ),
		"edit_item"             => sprintf( _x( 'Edit %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"new_item"              => sprintf( _x( 'Add %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"view"                  => _x( "Show", "labels", "do-stelselplaat" ),
		"view_item"             => sprintf( _x( 'View %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"search_items"          => sprintf( _x( 'Search %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"not_found"             => sprintf( _x( 'No %s available', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"not_found_in_trash"    => sprintf( _x( 'No %s in trash', 'labels', "do-stelselplaat" ), $typeLC_plural ),
		"parent"                => _x( "Parent", "labels", "do-stelselplaat" ),
	);

	$args = array(
    "label"                 => $typeUC_plural,
    "labels"                => $labels,
    "description"           => "",
    "public"                => true,
    "publicly_queryable"    => true,
    "show_ui"               => true,
    "show_in_rest"          => false,
    "rest_base"             => "",
    "has_archive"           => false,
    "menu_icon"             => "dashicons-media-document",          
    "show_in_menu"          => true,
    "exclude_from_search"   => false,
    "capability_type"       => "post",
    "map_meta_cap"          => true,
    "hierarchical"          => false,
    "rewrite"               => array( "slug" => DO_SP_BASISREGISTRATIE_CPT, "with_front" => true ),
    "query_var"             => true,
		"supports"              => array( "title", "editor" ),					
	);
		
	register_post_type( DO_SP_BASISREGISTRATIE_CPT, $args );
	
  //------------------------------------------------------------------------------------------------------
  
  $typeUC_single = _x( "Standaard", "labels", "do-stelselplaat" );
  $typeUC_plural = _x( "Standaarden", "labels", "do-stelselplaat" );
  
  $typeLC_single = _x( "standaard", "labels", "do-stelselplaat" );
  $typeLC_plural = _x( "Standaarden", "labels", "do-stelselplaat" );

	$labels = array(
		"name"                  => sprintf( '%s', $typeUC_single ),
		"singular_name"         => sprintf( '%s', $typeUC_single ),
		"menu_name"             => sprintf( '%s', $typeUC_single ),
		"all_items"             => sprintf( _x( 'All %s', 'labels', "do-stelselplaat" ), $typeLC_plural ),
		"add_new"               => sprintf( _x( 'Add %s', 'labels', "do-stelselplaat" ), $typeLC_plural ),
		"add_new_item"          => sprintf( _x( 'Add new %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"edit"                  => _x( "Edit?", "labels", "do-stelselplaat" ),
		"edit_item"             => sprintf( _x( 'Edit %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"new_item"              => sprintf( _x( 'Add %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"view"                  => _x( "Show", "labels", "do-stelselplaat" ),
		"view_item"             => sprintf( _x( 'View %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"search_items"          => sprintf( _x( 'Search %s', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"not_found"             => sprintf( _x( 'No %s available', 'labels', "do-stelselplaat" ), $typeLC_single ),
		"not_found_in_trash"    => sprintf( _x( 'No %s in trash', 'labels', "do-stelselplaat" ), $typeLC_plural ),
		"parent"                => _x( "Parent", "labels", "do-stelselplaat" ),
	);

	$args = array(
    "label"                 => $typeUC_plural,
    "labels"                => $labels,
    "description"           => "",
    "public"                => true,
    "publicly_queryable"    => true,
    "show_ui"               => true,
    "show_in_rest"          => false,
    "rest_base"             => "",
    "has_archive"           => false,
    "menu_icon"             => "dashicons-media-document",          
    "show_in_menu"          => true,
    "exclude_from_search"   => false,
    "capability_type"       => "post",
    "map_meta_cap"          => true,
    "hierarchical"          => false,
    "rewrite"               => array( "slug" => DO_SP_STANDAARD_CPT, "with_front" => true ),
    "query_var"             => true,
		"supports"              => array( "title", "editor" ),					
	);
		
	register_post_type( DO_SP_STANDAARD_CPT, $args );
	
  //------------------------------------------------------------------------------------------------------
  
	//      	flush_rewrite_rules();

}
  
//========================================================================================================

