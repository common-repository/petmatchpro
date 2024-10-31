<?php
class Pet_Match_Pro {
	protected $loader;
	protected $pluginName;
//	protected $plugin_name;	
	protected $version;
	protected $pluginSlug;
//	protected $plugin_slug;
	protected $cssClass;
//	protected $css_class;
//	private $partner_api;
	private $integrationPartner;
    private $Auth;
    public $responseObj;
    public $licenseMessage;
    protected $PMPAuth;
    public $generalOptions;
        
	public function __construct() {
		if ( defined( 'PET_MATCH_PRO_VERSION' ) ) {
			$this->version = PET_MATCH_PRO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->pluginName = constant('PMP_PLUGIN_NAME');
        $this->pluginSlug = constant('PMP_PLUGIN_SLUG');
        $this->cssClass = constant('PMP_CLASS_PREFIX');
        //Based on the Partner API load filter options
        $this->generalOptions = get_option('pet-match-pro-general-options');
		if (is_array($this->generalOptions)) {
			if ( (array_key_exists('integration_partner', $this->generalOptions)) ) {
				$this->integrationPartner = $this->generalOptions['integration_partner'];
			} else {
				$this->integrationPartner = constant('PETPOINT');
			}
		} else {
			$this->integrationPartner = constant('PETPOINT');
		}			
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pet_Match_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - Pet_Match_Pro_i18n. Defines internationalization functionality.
	 * - Pet_Match_Pro_Admin. Defines all hooks for the admin area.
	 * - Pet_Match_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . constant('INCLUDE_DIR') . '/class-pet-match-pro-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . constant('INCLUDE_DIR') . '/class-pet-match-pro-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . constant('ADMIN_DIR') . '/class-pet-match-pro-admin.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
    	require_once plugin_dir_path( dirname( __FILE__ ) ) . constant('PUBLIC_DIR') . '/class-pet-match-pro-public.php';
        /**
         * Require the form builder class for easy form creation
         */
    	require_once plugin_dir_path(dirname(__FILE__)) . constant('INCLUDE_DIR') . '/class-pet-match-pro-form-builder.php';

    	//Based on the Partner API load filter options
    	if ($this->integrationPartner == constant('PETPOINT')) {
			require_once plugin_dir_path(dirname(__FILE__)) . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/class-pet-match-pro-pp-options.php';
        	require_once plugin_dir_path(dirname(__FILE__)) . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/class-pet-match-pro-pp-api.php';
    	} elseif ($this->integrationPartner == constant('RESCUEGROUPS')) {
			require_once plugin_dir_path(dirname(__FILE__)) . constant('INCLUDE_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/class-pet-match-pro-rg-options.php';
        	require_once plugin_dir_path(dirname(__FILE__)) . constant('INCLUDE_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/class-pet-match-pro-rg-api.php';
    	} elseif ($this->integrationPartner == constant('ANIMALSFIRST')) {
			require_once plugin_dir_path(dirname(__FILE__)) . constant('INCLUDE_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/class-pet-match-pro-af-options.php';
        	require_once plugin_dir_path(dirname(__FILE__)) . constant('INCLUDE_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/class-pet-match-pro-af-api.php';
     	} else {
            //load a message that will say whatever or do something for defaults
     	}
        $this->loader = new Pet_Match_Pro_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pet_Match_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Pet_Match_Pro_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		//load main plugin settings class
		$plugin_admin = new Pet_Match_Pro_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_slug() );
		//echo 'Preparing to Load PMP Styles.<br>';
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		//echo 'Preparing to Load PMP Scripts.<br>';		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
       
        //load actual settings page and register @ admin/class-pet-match-pro-admin-settings.php

        $plugin_settings = new Pet_Match_Pro_Admin_Settings( $this->get_plugin_name(), $this->get_version(), $this->get_slug() );
        $this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_general_options' );
        $this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_contact_options' ); /* RMB - Removed Commenting */
        $licenseKey=get_option("PMP_lic_Key","");
        $liceEmail=get_option( "PMP_lic_email","");
		if (PetMatchProBase::CheckWPPlugin($licenseKey, $liceEmail, $this->licenseMessage, $this->responseObj, __FILE__)) {
            $this->PMPAuth = true;
            /* Post License Type to Plugin Options */
            $licenseType = $this->responseObj->license_title;
        	$licenseTypeID = $this->responseObj->lic_param;
        	update_option("PMP_License_Type", $licenseType) || add_option("PMP_License_Type", $licenseType);
           	update_option("PMP_License_Type_ID", $licenseTypeID) || add_option("PMP_License_Type_ID", $licenseTypeID);
		} else {
			$this->PMPAuth = false;
        	update_option('PMP_License_Type', "");
           	update_option('PMP_License_Type_ID', "");
		}

        if ($this->integrationPartner == constant('PETPOINT')) {
        	//load the petpoint filter form Pet_Match_Pro_PP_Options
            $plugin_pp_settings = new Pet_Match_Pro_PP_Options($this->PMPAuth);
            $this->loader->add_action( 'admin_init', $plugin_pp_settings, 'initialize_filter_options' );  
            $this->loader->add_action( 'admin_init', $plugin_pp_settings, 'initialize_label_options' );  
        } elseif ($this->integrationPartner == constant('RESCUEGROUPS')) {
        	//load the RescueGroups filter form Pet_Match_Pro_RG_Options
            $plugin_rg_settings = new Pet_Match_Pro_RG_Options($this->PMPAuth);
            $this->loader->add_action( 'admin_init', $plugin_rg_settings, 'initialize_filter_options' );
            $this->loader->add_action( 'admin_init', $plugin_rg_settings, 'initialize_label_options' );
        } elseif ($this->integrationPartner == constant('ANIMALSFIRST')) {
        	//load the AnimalsFirst filter form Pet_Match_Pro_AF_Options
            $plugin_af_settings = new Pet_Match_Pro_AF_Options($this->PMPAuth);
            $this->loader->add_action( 'admin_init', $plugin_af_settings, 'initialize_filter_options' );
            $this->loader->add_action( 'admin_init', $plugin_af_settings, 'initialize_label_options' );
        } else {
        	//load a message that will say whatever or do something for defaults
        }
        $this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_color_options' );        
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	private function define_public_hooks() {
		$pluginPublic = new Pet_Match_Pro_Public( $this->get_plugin_name(), $this->get_version(), $this->get_slug(), $this->get_auth() );
		$this->loader->add_action( 'wp_enqueue_scripts', $pluginPublic, 'pmp_enqueue_styles_scripts' );
//		$this->loader->add_action( 'wp_enqueue_scripts', $pluginPublic, 'enqueue_styles' );
//		$this->loader->add_action( 'wp_enqueue_scripts', $pluginPublic, 'enqueue_scripts' );
		
        $this->loader->add_shortcode( 'pmp-adoptable-details', $pluginPublic, 'petmatch_details' );//
        $this->loader->add_shortcode( 'petmatch_adoptable_details', $pluginPublic, 'petmatch_adoptable_details' );
        
        $this->loader->add_shortcode( 'pmp-adoptable-search', $pluginPublic, 'petmatch_adoptable_search' );//
        $this->loader->add_shortcode( 'pmp-found-details', $pluginPublic, 'petmatch_found_details' );//
        $this->loader->add_shortcode( 'pmp-found-search', $pluginPublic, 'petmatch_found_search' );//
        $this->loader->add_shortcode( 'pmp-lost-search', $pluginPublic, 'petmatch_lost_search' ); /* RMB - Changed Plugin Name */
        $this->loader->add_shortcode( 'pmp-lost-details', $pluginPublic, 'petmatch_lost_details' ); /* RMB - New Shortcode */   
        $this->loader->add_shortcode( 'pmp-lost-found-search', $pluginPublic, 'petmatch_lost_found_search' ); /* RMB - New Shortcode */   
        $this->loader->add_shortcode( 'pmp-adoptable-featured', $pluginPublic, 'petmatch_featured' );//
        $this->loader->add_shortcode( 'pmp-detail', $pluginPublic, 'PMP_detail' );
//        $this->loader->add_shortcode( 'pmp-url', $pluginPublic, 'pmp_url' );
        $this->loader->add_shortcode( 'pmp-option', $pluginPublic, 'petmatch_option_value' ); /* RMB - New Shortcode */           
		$this->loader->add_shortcode('pmp-social-share', $pluginPublic, 'pmp_social_share');    
		/* New Shortcodes for Animals First */
        $this->loader->add_shortcode( 'pmp-search', $pluginPublic, 'petmatch_search' );		
        $this->loader->add_shortcode( 'pmp-details', $pluginPublic, 'petmatch_details' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->pluginName;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pet_Match_Pro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

    /**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_slug() {
		return $this->pluginSlug;
	}

    public function get_auth() {
		return $this->PMPAuth;
	}
}