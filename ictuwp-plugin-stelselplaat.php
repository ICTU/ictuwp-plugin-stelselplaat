<?php
/*
// * DO Stelselplaat - do-stelselplaat.php
// *
// * Plugin Name:         ICTU / WP Nieuwe opzet stelselplaat digitaleoverheid.nl (2019)
// * Plugin URI:          https://github.com/ICTU/Digitale-Overheid---WordPress-plugin-Stelselplaat/
// * Description:         Plugin voor digitaleoverheid.nl waarmee extra functionaliteit mogelijk wordt voor het tonen van de stelselplaat voor de samenhang tussen voorzieningen, standaarden en basisregistraties
// * Version:             2.0.2
// * Version description: CSS kleur voor iconen verbeterd.
// * Author:              Paul van Buuren
// * Author URI:          https://wbvb.nl
// * License:             GPL-2.0+
// *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

//========================================================================================================

add_action( 'plugins_loaded', 'do_sp_init_load_plugin_textdomain' );

//========================================================================================================

if ( ! class_exists( 'DO_Stelselplaat' ) ) :

  /**
   * Register the plugin.
   *
   * Display the administration panel, add JavaScript etc.
   */
   
	class DO_Stelselplaat {
		
		/**
		* @var string
		*/
		public $version = '2.0.2';
		
		
		/**
		* @var DO Stelselplaat
		*/
		
		public $option_name = null;
		
		
		/**
		* Init
		*/
		public static function init() {
		
		$stelseplaat = new self();
		
		}
		
		//========================================================================================================
		
		/**
		* Constructor
		*/
		public function __construct() {
		
			$this->define_constants();
			$this->includes();
			$this->do_sp_init_setup_actions();
			$this->do_sp_init_setup_filters();
		
		}
		
		//========================================================================================================
		
		/**
		* Define DO Stelselplaat constants
		*/
		private function define_constants() {
		
			$protocol									= strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
			$this->option_name        					= 'ictudo_stelselplaat-option';

			define( 'DO_SP_VERSION',                 	$this->version );
			define( 'DO_SP_FOLDER',                  	'do-stelselplaat' );
			define( 'DO_SP_BASE_URL',                	trailingslashit( plugins_url( DO_SP_FOLDER ) ) );
			define( 'DO_SP_ASSETS_URL',              	trailingslashit( DO_SP_BASE_URL ) );
			define( 'DO_SP_PATH',                    	plugin_dir_path( __FILE__ ) );
			define( 'DO_SP_PATH_LANGUAGES',          	trailingslashit( DO_SP_PATH . 'languages' ) );;
			
			
			define( 'DO_SP_PLUGIN_DO_DEBUG',         	true );
//			define( 'DO_SP_PLUGIN_DO_DEBUG',        	false );
//       	define( 'DO_SP_PLUGIN_OUTPUT_TOSCREEN', 	false );
			define( 'DO_SP_PLUGIN_OUTPUT_TOSCREEN',		true );
			define( 'DO_SP_PLUGIN_GENESIS_ACTIVE',		true ); // todo: inbouwen check op actief zijn van Genesis framework
			
			define( 'DO_SP_PLUGIN_KEY',             	'ictudo_stelselplaat' ); 
			
			define( 'DO_SP_ARCHIVE_CSS',			'dopt-header-css' );  
		
		}
		
		//========================================================================================================
		
		/**
		* All DO Stelselplaat classes
		*/
		private function plugin_classes() {
			
			return array(
				'do_sp_SystemCheck'  => DO_SP_PATH . 'inc/dosp.systemcheck.class.php',
			);
		
		}
		
		//========================================================================================================
		
		/**
		* Load required classes
		*/
		private function includes() {
			
			$autoload_is_disabled = defined( 'DO_SP_AUTOLOAD_CLASSES' ) && DO_SP_AUTOLOAD_CLASSES === false;
			
			if ( function_exists( "spl_autoload_register" ) && ! ( $autoload_is_disabled ) ) {
				
				// >= PHP 5.2 - Use auto loading
				if ( function_exists( "__autoload" ) ) {
					spl_autoload_register( "__autoload" );
				}
				spl_autoload_register( array( $this, 'autoload' ) );
				
			} 
			else {
				// < PHP5.2 - Require all classes
				foreach ( $this->plugin_classes() as $id => $path ) {
					if ( is_readable( $path ) && ! class_exists( $id ) ) {
						require_once( $path );
					}
				}
			}
			
			if ( file_exists( dirname( __FILE__ ) . '/inc/dosp.acf-definitions-functions.php' ) ) {
				require_once dirname( __FILE__ ) . '/inc/dosp.acf-definitions-functions.php';
			}
			
			if ( file_exists( dirname( __FILE__ ) . '/inc/dosp.posttypes-taxonomies.php' ) ) {
				require_once dirname( __FILE__ ) . '/inc/dosp.posttypes-taxonomies.php';
			}
			
		}
		
		//========================================================================================================
		
		/**
		* filter for when the CPT is previewed
		*/
		public function do_sp_frontend_filter_for_preview( $content = '' ) {
		
			global $post;
			
			return $content;
		
		}
		
		//========================================================================================================
		/**
		* for single posts of the correct kind and type: NO post info
		*
		* @param  string  $post_info
		* @return string  $post_info
		*/
		function filter_postinfo( $post_info ) {

			global $wp_query;
			global $post;
			
			return $post_info;
		
		}
		
		//========================================================================================================
		
		/**
		* Autoload DO Stelselplaat classes to reduce memory consumption
		*/
		public function autoload( $class ) {
		
			$classes = $this->plugin_classes();
			
			$class_name = strtolower( $class );
			
			if ( isset( $classes[$class_name] ) && is_readable( $classes[$class_name] ) ) {
				echo 'require: ' . $classes[$class_name]. '<br>';
				die();
				require_once( $classes[$class_name] );
			}
		
		}
		
		//========================================================================================================
		
		/**
		* Hook DO Stelselplaat into WordPress
		*/
		private function do_sp_init_setup_actions() {
		
			// add a page temlate name
			$this->templates                      = array();
			$this->templatefile   		            = 'stelselplaat-template.php';
			
			// add the page template to the templates list
			add_filter( 'theme_page_templates',   array( $this, 'do_sp_init_add_page_templates' ) );
			
			// activate the page filters
			add_action( 'template_redirect',      array( $this, 'do_sp_frontend_use_page_template' )  );
			
			// admin settings
			add_action( 'admin_init',             array( $this, 'do_sp_admin_register_settings' ) );
			
			// add styling and scripts
			add_action( 'wp_enqueue_scripts',     array( $this, 'do_sp_frontend_register_frontend_style_script' ) );
			add_action( 'admin_enqueue_scripts',  array( $this, 'do_sp_admin_register_styles' ) );
		
		
		}
		//========================================================================================================
		
		/**
		* Hook DO Stelselplaat into WordPress
		*/
		private function do_sp_init_setup_filters() {
		
			// content filter
			add_filter( 'the_content', array( $this, 'do_sp_frontend_filter_for_preview' ) );
		
		}
		
		//========================================================================================================
		
		/**
		* Hides the custom post template for pages on WordPress 4.6 and older
		*
		* @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
		* @return array Expanded array of page templates.
		*/
		function do_sp_init_add_page_templates( $post_templates ) {
		
			$post_templates[$this->templatefile]  = _x( 'Stelselplaat DO (2019)', "naam template", "do-stelselplaat" );    
			return $post_templates;
		
		}
		
		//========================================================================================================
		
		/**
		* Register the options page
		*
		* @since    2.0.2
		*/
		public function do_sp_admin_register_settings() {
			
			// Add a General section
			add_settings_section(
				$this->option_name . '_general',
				__( 'General settings', "do-stelselplaat" ),
				array( $this, $this->option_name . '_general_cb' ),
				DO_SP_PLUGIN_KEY
			);
			
		}  
		
		//========================================================================================================
		
		/**
		* Register admin-side styles
		*/
		public function do_sp_admin_register_styles() {
		
			if ( is_admin() ) {
				wp_enqueue_style( 'do-stelselplaat-admin', DO_SP_ASSETS_URL . 'css/do-stelselplaat-admin.css', false, DO_SP_VERSION );
			}
		
		}
		
		//========================================================================================================
		
		/**
		* Add the help tab to the screen.
		*/
		public function do_sp_admin_help_tab() {
			
			$screen = get_current_screen();
			
			// documentation tab
			$screen->add_do_sp_admin_help_tab( array(
				'id'      => 'documentation',
				'title'   => __( 'Documentation', "do-stelselplaat" ),
				'content' => "<p><a href='https://github.com/ICTU/Digitale-Overheid---WordPress-plugin-Stelselplaat/documentation/' target='blank'>" . __( 'Stelselplaat documentatie', "do-stelselplaat" ) . "</a></p>",
				)
			);
		}
		
		//========================================================================================================
		
		/**
		* Register frontend styles
		*/
		public function do_sp_frontend_register_frontend_style_script( ) {
			
			global $post;

			$header_css     = '';
			$acfid          = get_the_id();
			$page_template  = get_post_meta( $acfid, '_wp_page_template', true );
			
			if ( !is_admin() && ( $this->templatefile == $page_template ) ) {

				wp_enqueue_style( DO_SP_ARCHIVE_CSS, DO_SP_ASSETS_URL . 'css/do-stelselplaat-frontend.css', array(), DO_SP_VERSION, 'all' );

if ( 22 == 33 ) {
	
				if ( function_exists( 'get_field' ) ) {
					
					$blokcounter = 0;
	
					$stelselplaatblokken    = get_field( 'stelselplaatblokken', $post->ID );
					$blokid					= sanitize_title( 'blok_0' );

					$header_css .= '#' . $blokid . ' {';
//					$header_css .= ' order: 2;';
//					$header_css .= 'flex-basis: 100%;';
//					$header_css .= 'max-width: 100%;';
//					$header_css .= 'padding-left: 33%;';
//					$header_css .= 'padding-right: 33%;';
//					$header_css .= 'text-align: center;';
					$header_css .= '}';
					
					if( have_rows( 'stelselplaatblokken', $post->ID ) ) {

						
						while( have_rows('stelselplaatblokken', $post->ID ) ): the_row();
						
							$blokcounter++;
	
							$stelselplaatblok_titel		= get_sub_field('stelselplaatblok_titel');
							$stelselplaatblok_icoon		= get_sub_field('stelselplaatblok_icoon');
							$stelselplaatblok_dossiers	= get_sub_field('stelselplaatblok_dossiers');
							$blokid						= sanitize_title( 'blok_' . $blokcounter );
							
							$header_css .= '#' . $blokid . ' {';
							if ( $blokcounter == 1 ) {
//								$header_css .= ' order: ' . $blokcounter . ';';
//								$header_css .= 'flex-basis: 100%;';
//								$header_css .= 'max-width: 100%;';
//								$header_css .= 'border: 10px solid red;';
//								$header_css .= 'padding-left: 33%;';
//								$header_css .= 'padding-right: 33%;';
							}
							else {
//								$header_css .= ' order: ' . ( $blokcounter + 1 ). ';';
							}

//							if ( $blokcounter == 2 ) {
//								$header_css .= 'border: 10px solid green;';
//							}
//							if ( $blokcounter == 3 ) {
//								$header_css .= 'border: 10px solid blue;';
//							}
/*
							foreach ( $stelselplaatblok_dossiers as $dossier ) {
								$terminfo = get_term( $dossier );
								echo '<li class="' . $terminfo->slug . '"><a href="' . get_permalink( $terminfo->term_id ) . '">' . $terminfo->name . '</a></li>';
							}
*/							

							$header_css .= '}';
							
						endwhile;
	
					}
				}
}
				
				
				if ( $header_css ) {
					wp_add_inline_style( DO_SP_ARCHIVE_CSS, $header_css );
				}
			}
		}
		
		//========================================================================================================
		/**
		* Handles the front-end display. 
		*
		* @return void
		*/
		
		public function do_sp_do_frontend_pagetemplate_add_blokken() {
		
			global $post;

			if ( function_exists( 'get_field' ) ) {
				
				$blokcounter = 0;

				$stelselplaatblokken    = get_field( 'stelselplaatblokken', $post->ID );
				$titel_in_het_midden    = get_field( 'titel_in_het_midden', $post->ID );

				if ( ! $titel_in_het_midden ) {
					$titel_in_het_midden  = _x( 'Basisinfrastructuur (GDI)', "naam template", "do-stelselplaat" );    
				}

				if( have_rows( 'stelselplaatblokken', $post->ID ) ) {
					
					echo '<div class="flex-block">';
					

					$blokid						= sanitize_title( 'blok_0' );

				    $needle   = 'asis';
				    $replacer = 'asis&shy;';
				    $titel_in_het_midden = str_replace( $needle, $replacer, $titel_in_het_midden);
					
				    $needle   = 'infrastr';
				    $replacer = 'infra&shy;str';
				    $titel_in_het_midden = str_replace( $needle, $replacer, $titel_in_het_midden);
					
					echo '<section  id="' . $blokid . '" class="infrablock spaak">';
					echo '<h2 id="title-' . sanitize_title( $titel_in_het_midden ) . '"><span>' . $titel_in_het_midden . '</span></h2>';
					echo '</section>';


					
					while( have_rows('stelselplaatblokken', $post->ID ) ): the_row();
					
						$blokcounter++;

						$stelselplaatblok_titel			= get_sub_field('stelselplaatblok_titel');
						$stelselplaatblok_icoon			= get_sub_field('stelselplaatblok_icoon');
						$stelselplaatblok_dossiers		= get_sub_field('stelselplaatblok_dossiers');
//						$stelselplaatblok_toelichting	= get_sub_field('stelselplaatblok_toelichting');
						
						$blokid						= sanitize_title( 'blok_' . $blokcounter );
						
						echo '<section  id="' . $blokid . '" class="infrablock ' . $stelselplaatblok_icoon . '">';
						echo '<div class="kader">';
						echo '<h3 id="title-' . sanitize_title( $stelselplaatblok_titel ) . '">' . $stelselplaatblok_titel . '</h3>';


					    echo '<p class="visuallyhidden">' . sprintf( _x( "Het onderdeel '%s' bestaat uit %s dossiers, namelijk:", 'Toelichting bij stelselonderdeel', 'wp-rijkshuisstijl' ), $stelselplaatblok_titel, count( $stelselplaatblok_dossiers ) ) . '</p>';
						echo '<ul>';

						foreach ( $stelselplaatblok_dossiers as $dossier ) {
							$terminfo = get_term( $dossier );
							echo '<li class="' . $terminfo->slug . '"><a href="' . get_term_link( $terminfo->term_id ) . '">' . $terminfo->name . '</a></li>';
						}
						
						echo '</ul>';
						echo '</div>';
						echo '</section>';
						
					endwhile;

					echo '</div>';
				}
			}    
		}    
		
		//========================================================================================================
		/**
		* Handles the front-end display. 
		*
		* @return void
		*/
		
		public function do_sp_do_frontend_toon_item( $item ) {
			
			global $post;
			
			if ( function_exists( 'get_field' ) ) {
				
				$title    = get_field( 'title_' . $item, $post->ID );
				if ( ! $title ) {
					$title = $item;
				}
				$dossiers    = get_field( 'link_' . $item, $post->ID );
				
				if ( $dossiers ) {
					
					echo '<section  id="' . sanitize_title( $title ) . '" class="infrablock">';
					echo '<h2 id="title-' . sanitize_title( $title ) . '">' . $title . '</h2>';
					
					echo '<ul aria-labelledby="title-' . sanitize_title( $title ) . '">';
					
					foreach ( $dossiers as $dossier ) {
						$terminfo = get_term( $dossier );
						echo '<li class="' . $terminfo->slug . '"><a href="' . get_permalink( $terminfo->term_id ) . '">' . $terminfo->name . '</a></li>';
					}
					
					echo '</ul>';
					echo '</section>';
					
				}
			}
			
		}    
		
		
		//====================================================================================================
		
		/**
		* Modify page content if using a specific page template.
		*/
		public function do_sp_frontend_use_page_template() {
			
			global $post;
			
			$page_template  = get_post_meta( get_the_ID(), '_wp_page_template', true );
			
			if ( $this->templatefile == $page_template ) {
				
				// append actielijnen
				add_action( 'genesis_after_entry_content',   array( $this, 'do_sp_do_frontend_pagetemplate_add_blokken' ), 15 );
				
			}
			
			//=================================================
			
			add_filter( 'genesis_post_info',   array( $this, 'filter_postinfo' ), 10, 2 );
			
		}
		
	}

//========================================================================================================

endif;

//========================================================================================================

add_action( 'plugins_loaded', array( 'DO_Stelselplaat', 'init' ), 10 );

//========================================================================================================

add_action( 'wp_enqueue_scripts', 'do_sp_aux_remove_cruft', 100 ); // high prio, to ensure all junk is discarded

/**
 * Unhook DO Stelselplaat styles from WordPress
 */
function do_sp_aux_remove_cruft() {

//  wp_dequeue_style('cmb2-styles');

}

//========================================================================================================

if (! function_exists( 'do_sp_aux_write_to_log' ) ) {

	function do_sp_aux_write_to_log( $log ) {
		
    $subject = 'log';
    $subject .= ' (ID = ' . getmypid() . ')';

    $subjects = array();
    $subjects[] = $log;

		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( $subject . ' - ' .  print_r( $log, true ) );
			}
			else {
				error_log( $subject . ' - ' .  $log );
			}
		}
	}

}

//========================================================================================================

if (! function_exists( 'dovardump' ) ) {
  
  function dovardump($data, $context = '', $echo = true ) {

    if ( WP_DEBUG && DO_SP_PLUGIN_DO_DEBUG ) {
      $contextstring  = '';
      $startstring    = '<div class="debug-context-info">';
      $endtring       = '</div>';
      
      if ( $context ) {
        $contextstring = '<p>Vardump ' . $context . '</p>';        
      }

      do_sp_aux_write_to_log( print_r($data), true );      

      
      if ( $echo && DO_SP_PLUGIN_OUTPUT_TOSCREEN ) {
        
        echo $startstring . '<hr>';
        echo $contextstring;        
        echo '<pre>';
        print_r($data);
        echo '</pre><hr>' . $endtring;
      }
      else {
        return '<hr>' . $contextstring . '<pre>' . print_r($data, true) . '</pre><hr>';
      }
    }        
  }
}

//========================================================================================================

if (! function_exists( 'dodebug' ) ) {
  
  function dodebug( $string, $tag = 'span' ) {
    
    if ( WP_DEBUG && DO_SP_PLUGIN_DO_DEBUG ) {

      do_sp_aux_write_to_log( $string );      
      if ( DO_SP_PLUGIN_OUTPUT_TOSCREEN ) {
        echo '<' . $tag . ' class="debugstring" style="border: 1px solid red; background: yellow; display: block; "> ' . $string . '</' . $tag . '>';
      }
    }
  }

}

//========================================================================================================

/**
 * Initialise translations
 */
function do_sp_init_load_plugin_textdomain() {

  load_plugin_textdomain( "do-stelselplaat", false, basename( dirname( __FILE__ ) ) . '/languages' );

}

//========================================================================================================


