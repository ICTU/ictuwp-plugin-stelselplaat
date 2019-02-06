<?php
/*
// * DO Stelselplaat - do-stelselplaat.php
// *
// * Plugin Name:         ICTU / WP Nieuwe opzet stelselplaat digitaleoverheid.nl (2019)
// * Plugin URI:          https://github.com/ICTU/Digitale-Overheid---WordPress-plugin-Stelselplaat/
// * Description:         Plugin voor digitaleoverheid.nl waarmee extra functionaliteit mogelijk wordt voor het tonen van de stelselplaat voor de samenhang tussen voorzieningen, standaarden en basisregistraties
// * Version:             0.0.1
// * Version description: Eerste opzet.
// * Author:              Paul van Buuren
// * Author URI:          https://wbvb.nl
// * License:             GPL-2.0+
// *

// * DO_Stelselplaat - dosp.acf-definitions-functions.php
// * ----------------------------------------------------------------------------------
// * definitions and aux. functions for Advanced Custom Fields
// * ----------------------------------------------------------------------------------
// * Description:         Plugin voor digitaleoverheid.nl waarmee extra functionaliteit mogelijk wordt voor het tonen van de stelselplaat voor de samenhang tussen voorzieningen, standaarden en basisregistraties
// * @author            Paul van Buuren
// * @license           GPL-2.0+
// * @package           do-stelselplaat
// * version:           0.0.1
// * @version-desc.     Eerste opzet.
// * @link              https://github.com/ICTU/Digitale-Overheid---WordPress-plugin-Stelselplaat/
// * Text Domain:       do-stelselplaat
// * Domain Path:       /languages


 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

add_action( 'plugins_loaded', 'do_sp_init_load_plugin_textdomain' );


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
      public $version = '0.0.1';
  
  
      /**
       * @var DO Stelselplaat
       */
      public $gcmaturity = null;

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
  
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';

        $this->option_name        = 'ictudo_stelselplaat-option';

  
        define( 'DO_SP_VERSION',                 $this->version );
        define( 'DO_SP_FOLDER',                  'do-stelselplaat' );
        define( 'DO_SP_BASE_URL',                trailingslashit( plugins_url( DO_SP_FOLDER ) ) );
        define( 'DO_SP_ASSETS_URL',              trailingslashit( DO_SP_BASE_URL ) );
        define( 'DO_SP_PATH',                    plugin_dir_path( __FILE__ ) );
        define( 'DO_SP_PATH_LANGUAGES',          trailingslashit( DO_SP_PATH . 'languages' ) );;

        define( 'DO_SP_VOORZIENING_CPT',          "voorziening" );
        define( 'DO_SP_BASISREGISTRATIE_CPT',     "basisregistratie" );
        define( 'DO_SP_STANDAARD_CPT',            "standaard" );


        define( 'DO_SP_PLUGIN_DO_DEBUG',         true );
//        define( 'DO_SP_PLUGIN_DO_DEBUG',         false );
//        define( 'DO_SP_PLUGIN_OUTPUT_TOSCREEN',  false );
        define( 'DO_SP_PLUGIN_OUTPUT_TOSCREEN',  true );
        define( 'DO_SP_PLUGIN_GENESIS_ACTIVE',   true ); // todo: inbouwen check op actief zijn van Genesis framework

        define( 'DO_SP_PLUGIN_KEY',              'ictudo_stelselplaat' ); 
 
        define( 'DO_SP_NR_QUARTERS',              5 );


        define( 'do_sp_CSS_YEARWIDTH',           13 ); // 12ems per year + 1em margin right
        define( 'do_sp_CSS_QUARTERWIDTH',        3 ); 
        define( 'do_sp_CSS_PADDINGLEFT',         26 ); // basically do_sp_CSS_YEARWIDTH but then twice

        define( 'DO_SP_ARCHIVE_CSS',            'dopt-header-css' );  

        //define( 'DO_SP_CSS_RADIALGRADIENT', true );
        define( 'DO_SP_CSS_RADIALGRADIENT', false );

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

        if( in_the_loop() && is_single() && ( DO_SP_VOORZIENING_CPT == get_post_type() || DO_SP_BASISREGISTRATIE_CPT == get_post_type() ) ) {
//          return $content . do_sp_frontend_display_actielijn_info( $post->ID );
          return $content;
        }
        else {
          return $content;
        }
        
      }
  
    	//========================================================================================================
      /**
      * for single posts of the correct kind and type: NO post info
      *
      * @param  string  $post_info
      * @return string  $post_info
      */
      function filter_postinfo($post_info) {
        global $wp_query;
        global $post;
        

        if ( is_single() && ( DO_SP_VOORZIENING_CPT == get_post_type() || DO_SP_BASISREGISTRATIE_CPT == get_post_type() ) ) {
          return '';
        }
        else {
          return $post_info;
        }

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

        add_action( 'init',                   'do_sp_init_register_post_type' );
        
        // add the page template to the templates list
        add_filter( 'theme_page_templates',   array( $this, 'do_sp_init_add_page_templates' ) );
        
        // activate the page filters
        add_action( 'template_redirect',      array( $this, 'do_sp_frontend_use_page_template' )  );
        
        // admin settings
        add_action( 'admin_init',             array( $this, 'do_sp_admin_register_settings' ) );
        
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
      
        $post_templates[$this->templatefile]  = _x( 'Stelselplaat DO', "naam template", "do-stelselplaat" );    
        return $post_templates;
      
      }
  
      //========================================================================================================
  
    	/**
    	 * Register the options page
    	 *
    	 * @since    0.0.1
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

        $header_css     = '';
        $acfid          = get_the_id();
        $page_template  = get_post_meta( $acfid, '_wp_page_template', true );
  
        if ( !is_admin() && ( $this->templatefile == $page_template ) ) {

          $header_css .= ".actielijnen { ";
          $header_css .= " width: 100em;";   
          $header_css .= "} ";


          wp_enqueue_style( DO_SP_ARCHIVE_CSS, DO_SP_ASSETS_URL . 'css/do-stelselplaat.css', array(), DO_SP_VERSION, 'all' );
        
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
       
      public function do_sp_do_frontend_pagetemplate_add_actielijnen() {

        echo 'actielijnen';

      }    
  
    //====================================================================================================

    public function do_sp_frontend_filter_breadcrumb( $crumb, $args ) {
    
      if ( $crumb ) {
        
        $span_before_start  = '<span class="breadcrumb-link-wrap" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';  
        $span_between_start = '<span itemprop="name">';  
        $span_before_end    = '</span>';  
        $loop               = rhswp_get_context_info();
        $berichtnaam        = get_the_title();

        $planning_page      = get_field( 'planning_page', 'option');
        $planning_page_id   = $planning_page->ID;
        
        if ( !$planning_page_id ) {
          $planning_page_id = get_option( 'page_for_posts' );
        }  
  
        if( ( is_single() && DO_SP_VOORZIENING_CPT == get_post_type() ) || 
            ( is_single() && DO_SP_BASISREGISTRATIE_CPT == get_post_type() ) ) {

        	if ( $planning_page_id ) {
        		return '<a href="' . get_permalink( $planning_page_id ) . '">' . get_the_title( $planning_page_id ) .'</a>' . $args['sep'] . ' ' . $berichtnaam;
        	}
        	else {
        		return $crumb;
        	}
      	}
      	else {
      		return $crumb;
      	}
      }
    }

    //====================================================================================================

    /**
     * Handles the front-end display. 
     *
     * @return void
     */
    public function do_sp_do_frontend_single_actielijn_info() {
    
      global $post;
      
      $echo         = true;
      $showheader   = true;
      $returnstring = '';
      $actielijnentitletext = '';
      
      if ( is_single() && DO_SP_VOORZIENING_CPT == get_post_type() ) {
        
      }

      if ( $echo ) {
        echo $returnstring;
      }
      else {
        return $returnstring;
      }

      
    }

    //====================================================================================================

    /**
     * Handles the front-end display. 
     *
     * @return void
     */
    public function do_sp_do_frontend_single_gebeurtenis_info() {
    
      global $post;
      
      $echo                   = true;
      $showheader             = true;
      $returnstring           = '';
      $actielijnentitletext   = '';
      $acfid                  = $post->ID;

      if ( is_single() && DO_SP_BASISREGISTRATIE_CPT == get_post_type() ) {
        
      }

      if ( $echo ) {
        echo $returnstring;
      }
      else {
        return $returnstring;
      }
      
    }

    //====================================================================================================

    /**
     * Append related actielijnen or gebeurtenissen
     */
 
    public function do_sp_frontend_display_actielijn_info( $postid, $showheader = false, $echo = false, $startyear = '', $endyear = '', $titletext = '', $actielijnentitletext = '' ) {
    
      $returnstring = '';

      if ( DO_SP_BASISREGISTRATIE_CPT == get_post_type( $postid ) ) {
        
      }
      elseif ( DO_SP_VOORZIENING_CPT == get_post_type( $postid ) ) {
        
      } 

      if ( $echo ) {
        echo $returnstring;
      }
      else {
        return $returnstring;
      }

    }


    //====================================================================================================

    /**
    * wrap the content in a wrapper as to limit its max width
    */
    public function do_sp_frontend_append_content_wrapper($content) {
      
      if( is_singular() && is_main_query() ) {
    
        // wrap the content in a wrapper
        $content = '<div class="wrap">' . $content . '</div>';
    		
    	}	
    	return $content;
    	
    }

    //====================================================================================================

    /**
    * Modify page content if using a specific page template.
    */
    public function do_sp_frontend_use_page_template() {

      global $post;
      
      $page_template  = get_post_meta( get_the_ID(), '_wp_page_template', true );

      if ( $this->templatefile == $page_template ) {

        //* Force full-width-content layout
        add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

        // wrap the text in a wrapper to contain its widths
        add_filter('the_content', array( $this, 'do_sp_frontend_append_content_wrapper' ), 15 );

        // append actielijnen
        add_action( 'genesis_after_entry_content',   array( $this, 'do_sp_do_frontend_pagetemplate_add_actielijnen' ), 15 );

      }

    	//=================================================
    	
      if ( is_single() && ( DO_SP_VOORZIENING_CPT == get_post_type() || DO_SP_BASISREGISTRATIE_CPT == get_post_type() ) ) {

        // check the breadcrumb
        add_filter( 'genesis_single_crumb',   array( $this, 'do_sp_frontend_filter_breadcrumb' ), 10, 2 );
        add_filter( 'genesis_page_crumb',     array( $this, 'do_sp_frontend_filter_breadcrumb' ), 10, 2 );
        add_filter( 'genesis_archive_crumb',  array( $this, 'do_sp_frontend_filter_breadcrumb' ), 10, 2 ); 				

        add_filter( 'genesis_post_info',   array( $this, 'filter_postinfo' ), 10, 2 );


        if ( DO_SP_VOORZIENING_CPT == get_post_type() ) {
          add_action( 'genesis_entry_content',  array( $this, 'do_sp_do_frontend_single_actielijn_info' ) );
        }
        
        if ( DO_SP_BASISREGISTRATIE_CPT == get_post_type() ) {
          add_action( 'genesis_entry_content',  array( $this, 'do_sp_do_frontend_single_gebeurtenis_info' ) );
        }

      }

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

add_filter('the_post_navigation', 'do_sp_remove_post_navigation_for_actielijn');

function do_sp_remove_post_navigation_for_actielijn( $args ){

  if ( DO_SP_VOORZIENING_CPT == get_type() ) {
    return '';
  }
  else {
    return '';
  }  
  return $args;

}

//========================================================================================================

/**
 * Append related actielijnen or gebeurtenissen
 */
 
function do_sp_frontend_get_gebeurtenissen_for_actielijn( $args ) {

  $returnstring         = '';

  $defaults = array(
  	'showheader'            => 0,
  	'echo'                  => false,
  	'headertag'             => 'h2',
  	'startyear'             => '',
  	'endyear'               => '',
  	'titletext'             => __( 'Gebeurtenissen', "do-stelselplaat" )
  );
  
  /**
   * Parse incoming $args into an array and merge it with $defaults
   */ 
  $args = wp_parse_args( $args, $defaults );
  
  
  if ( ! isset( $args['id'] ) ) {
    return;
  }


  if ( $args['echo'] ) {
    echo $returnstring;
  }
  else {
    return $returnstring;
  }



}

//========================================================================================================

add_filter('acf/update_value/name=related_gebeurtenissen_actielijnen', 'do_sp_bidirectional_acf_update_value', 10, 3);

add_filter('acf/update_value/name=related_actielijnen', 'do_sp_bidirectional_acf_update_value', 10, 3);

//========================================================================================================


