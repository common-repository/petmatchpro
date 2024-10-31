<?php
/* Define Levels to Secure Features Based on License Type ID */

const   TemplateFilter = '*default*.php';   

if ( ! function_exists( 'WP_Filesystem' ) ) {
    require_once ABSPATH . '/wp-admin/includes/file.php';
} 

class Pet_Match_Pro_Admin_Settings
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */

    private $plugin_name;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */

    private $version;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */

    private $plugin_slug;
    private $PMPLicenseType;    	/* To Secure Features */
    private $PMPLicenseTypeID;  	/* To Secure Features */  
    public  $integrationPartner; 	/* Integration Partner Support */
    private $partnerDir;         	/* Integration Partner Support */
    private $includeDir;
    private $includePartialsDir;
    private $adminPartialsDir;
    private $templateDir;
    public  $responseObj;
    public  $licenseMessage;
    public  $showMessage = false;
    public  $pmp_activated = false;
    public  $pmpAdminInfo;			/* Admin Hover Help Text */
    public  $pmpLicenseKey;
    public 	$generalOptions;
    public  $colorOptions;
    private $adminFunction;
    
	private $adoptMethodType;
	private $foundMethodType;
	private	$lostMethodType;
	private $preferredMethodType;  
	
    public function instruction_title() {
        return;
    }

    public function __construct($plugin_name, $version, $slug) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_slug = $slug;
        $this->pmpLicenseKey = get_option("PMP_lic_Key", "");
        $liceEmail = get_option("PMP_lic_email", "");

        PetMatchProBase::addOnDelete(function () {
            delete_option('PMP_lic_Key');
            delete_option('PMP_License_Type');
            delete_option('PMP_License_Type_ID');
        });

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
		//echo 'Integration Partner is ' .  $this->integrationPartner . '<br>';  

		$this->colorOptions = get_option('pet-match-pro-color-options');

        if (PetMatchProBase::CheckWPPlugin($this->pmpLicenseKey, $liceEmail, $this->licenseMessage, $this->responseObj, PET_MATCH_PRO_PATH_FILE)) {
            add_action('admin_menu', [$this, 'setup_plugin_options_menu_active']);
            add_action('admin_post_PetMatchPro_el_deactivate_license', [$this, 'action_deactivate_license']);
            $this->pmp_activated = true;
            $this->PMPLicenseType = get_option('PMP_License_Type');      

            //$this->PMPLicenseTypeID = get_option('PMP_License_Type_ID');  
            $this->PMPLicenseTypeID = (int) get_option('PMP_License_Type_ID');  

            //echo 'Option License Type ID = ' . $this->PMPLicenseTypeID . '<br>';

            //$this->PMPLicenseTypeID = 1;
        } else {
            if (!empty($this->pmpLicenseKey) && !empty($this->licenseMessage)) {
                $this->showMessage = true;
            }
            add_action('admin_menu', [$this, 'setup_plugin_options_menu_inactive']);
            update_option("pet-match-pro-license", []) || add_option("pet-match-pro-license", []);
            add_action('PMP_activate_lic', [$this, 'action_activate_license']);
            add_action('admin_post_PMP_activate_license', [$this, 'action_activate_license']);
            $this->pmp_activated = false;
            update_option('PMP_License_Type', "");
            update_option('PMP_License_Type_ID', ""); 
            $this->PMPLicenseTypeID = (int) get_option('PMP_License_Type_ID');                         
        }

        if ( $this->PMPLicenseTypeID == 0 ) {
            $this->PMPLicenseTypeID = constant('FREE_LEVEL');
        }
        //echo 'License Type: ' . $this->PMPLicenseType . '(' . $this->PMPLicenseTypeID . ')<br>';      

        $this->partnerDir = '';
        if ($this->integrationPartner == constant('ANIMALSFIRST')) {
        	$this->partnerDir = constant('ANIMALSFIRST_DIR');
            $this->adoptMethodType = constant('ADOPT_METHODTYPE_ANIMALSFIRST');
            $this->foundMethodType = constant('FOUND_METHODTYPE_ANIMALSFIRST');
            $this->lostMethodType = constant('LOST_METHODTYPE_ANIMALSFIRST');
            $this->preferredMethodType = constant('PREFERRED_METHODTYPE_ANIMALSFIRST');                    	
        } elseif ($this->integrationPartner == constant('RESCUEGROUPS')) {
        	$this->partnerDir = constant('RESCUEGROUPS_DIR');
            $this->adoptMethodType = constant('ADOPT_METHODTYPE_RESCUEGROUPS');        	
        } else {
        	$this->partnerDir = constant('PETPOINT_DIR');
            $this->adoptMethodType = constant('ADOPT_METHODTYPE_PETPOINT');
            $this->foundMethodType = constant('FOUND_METHODTYPE_PETPOINT');
            $this->lostMethodType = constant('LOST_METHODTYPE_PETPOINT');        	
        }
        //echo 'Integration Partner Directory  = ' . $this->partnerDir . '<br>';
        
       	$this->includeDir = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . '/' . $this->partnerDir . '/';
       	//echo 'Include Directory  = ' . $this->includeDir . '<br>';
       	$this->includePartialsDir = $this->includeDir . constant('PARTIALS_DIR') . '/';
       	//echo 'Include Partials Directory  = ' . $this->includePartialsDir . '<br>';
       	$this->adminPartialsDir = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/';

        $this->templateDir = constant('TEMPLATES_DIR') . '/' . $this->partnerDir . '/';
       	//echo 'Template Directory  = ' . $this->templateDir . '<br>';

        /* GetGeneral Admin Hover Help Text */
        $adminInfoFile = 'pmp-admin-info.php';
        $this->pmpAdminInfo = [];
        if ( (is_dir(get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/')) && (is_file(get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $adminInfoFile)) ) {
            $requireFile = get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $adminInfoFile;
        } else {
            $requireFile = $this->adminPartialsDir . $adminInfoFile;
        }
        //echo 'Require File = ' . $requireFile . '<br>';
        require($requireFile);
        //echo '<pre>INFO SETTINGS<br>'; print_r($this->pmpAdminInfo); echo '</pre>';
        
		$this->adminFunction = new PetMatchProFunctions();      
    }

    /**

     * Add the menu if Premium is Activated

     */

    public function setup_plugin_options_menu_active() {

    $logoPath = plugins_url('petmatchpro/' . constant('ASSETS_DIR') . '/' . 'icon-25x25.png');   

        add_menu_page(

            'PetMatchPro Options', //page title

            'PetMatchPro',        //menu title

            'manage_options',    //capabilities (options page)

            'pet-match-pro-options',    //menu slug

            array($this, 'render_options_page_content'),    //function

            $logoPath, //icon using dashicon

            6 // position

        );

        add_submenu_page(

            'pet-match-pro-options', //parent slug

            'PetMatchPro Options', //page title

            'Plugin Options', //menu title

            'manage_options',

            'pet-match-pro-options', //menu slug

            array($this, 'render_options_page_content')    //function

        );

        add_submenu_page(

            'pet-match-pro-options',

            'License Activation',

            'License Information',

            'manage_options',

            'pet-match-pro-license-options',

            array($this, 'initialize_deactivate_form')

        );

    }

    /**

     * Add the menu if Not Premium or deactivated

     */

    public function setup_plugin_options_menu_inactive()

    {

        $logoPath = plugins_url('petmatchpro/' . constant('ASSETS_DIR') . '/' . 'icon-25x25.png');   

        add_menu_page(

            'PetMatchPro Options',

            'PetMatchPro',

            'manage_options',

            'pet-match-pro-options',

            array($this, 'render_options_page_content'),

            $logoPath //icon 

        );

        add_submenu_page('pet-match-pro-options', 'PetMatchPro Options', 'Plugin Options', 'manage_options', 'pet-match-pro-options');

        add_submenu_page('pet-match-pro-options', 'License Activation', 'Activate License', 'manage_options', 'pet-match-pro-license-options', array($this, 'initialize_license_form'));

    }   

    /**

     * Display the initial Options page

     */

    public function render_options_page_content($active_tab = '')

    {

        ?>

        <!-- Create a header in the default WordPress 'wrap' container -->

        <div class="wpwrap pmp-options" style="height:100%;">

            <h1><?php _e('PetMatchPro Options', 'pet-match-pro-plugin'); ?></h1>

            <?php settings_errors(); ?>

            <?php if (isset($_GET['subpage'])) {

                $active_tab = sanitize_text_field( $_GET['subpage'] );

            } else if ($active_tab == 'filter_options') {

                $active_tab = 'filter_options';

            } else if ($active_tab == 'contact_options') {

                $active_tab = 'contact_options';                

            } else if ($active_tab == 'label_options') {

                $active_tab = 'label_options';

            } else if ($active_tab == 'color_options') {

                $active_tab = 'color_options';                                

            } else if ($active_tab == 'instructions') {

                $active_tab = 'instructions';

            } else {

                $active_tab = 'general_options';

            } // end if/else

            ?>

            <h2 class="pmp-nav-tab-wrapper">

                <a href="?page=pet-match-pro-options&subpage=general_options"

                   class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e('General Options', 'pet-match-pro-plugin'); ?></a>

                <a href="?page=pet-match-pro-options&subpage=filter_options"

                   class="nav-tab <?php echo $active_tab == 'filter_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Filter Options', 'pet-match-pro-plugin'); ?></a>

                <a href="?page=pet-match-pro-options&subpage=contact_options"

                   class="nav-tab <?php echo $active_tab == 'contact_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Contact Options', 'pet-match-pro-plugin'); ?></a>

                <a href="?page=pet-match-pro-options&subpage=label_options"

                   class="nav-tab <?php echo $active_tab == 'label_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Labels', 'pet-match-pro-plugin'); ?></a>

                <a href="?page=pet-match-pro-options&subpage=color_options"

                   class="nav-tab <?php echo $active_tab == 'color_options' ? 'nav-tab-active' : ''; ?>"><?php _e('Colors', 'pet-match-pro-plugin'); ?></a>

                <a href="?page=pet-match-pro-options&subpage=instructions"

                   class="nav-tab <?php echo $active_tab == 'instructions' ? 'nav-tab-active' : ''; ?>"><?php _e('Instructions', 'pet-match-pro-plugin'); ?></a>

            </h2>

            <form method="post" action="options.php" id="gk-pets-options">

                <?php

                if ($this->integrationPartner == constant('PETPOINT')) {

                    $ppOptions = new Pet_Match_Pro_PP_Options($this->pmp_activated);

                    $ppOptions->api_activated = $this->pmp_activated;

				} elseif ($this->integrationPartner == constant('RESCUEGROUPS')) {

					$rgOptions = new Pet_Match_Pro_RG_Options($this->pmp_activated);

                    $rgOptions->api_activated = $this->pmp_activated;

				} elseif ($this->integrationPartner == constant('ANIMALSFIRST')) {

					$afOptions = new Pet_Match_Pro_AF_Options($this->pmp_activated);

                    $afOptions->api_activated = $this->pmp_activated;

                }                

                if ($active_tab == 'general_options') {

                    register_setting(

                        'pet-match-pro-general-options',

                        'pet-match-pro-general-options'

                    );

                     $this->initialize_general_options();

                    settings_fields('pet-match-pro-general-options');

                    do_settings_sections('pet-match-pro-general-options');

                    submit_button('Apply Changes');

                } elseif ($active_tab == 'filter_options') {

                    register_setting(

                        'pet-match-pro-filter-options',

                        'pet-match-pro-filter-options'

                    );                                                        

                    //Based on the Partner API load filter options

                    if ($this->integrationPartner == constant('PETPOINT')) {

                        //all petpoint callback here

                        echo $ppOptions->initialize_filter_options();

                        submit_button('Save Filters');

					} elseif ($this->integrationPartner == constant('RESCUEGROUPS')) {

                        //all RescueGroups callback here

                        echo $rgOptions->initialize_filter_options();

                        submit_button('Save Filters');

					} elseif ($this->integrationPartner == constant('ANIMALSFIRST')) {

                        //all AnimalsFirst callback here

                        echo $afOptions->initialize_filter_options();

                        submit_button('Save Filters');

                    } else {

                        //load a message that will say whatever

                        echo 'Select a Partner API from the General Options tab to load available filters.';

                    }

                } elseif ($active_tab == 'contact_options') {

                    register_setting(

                        'pet-match-pro-contact-options',

                        'pet-match-pro-contact-options'

                    );

                    $this->initialize_contact_options();

                    settings_fields('pet-match-pro-contact-options');

                    do_settings_sections('pet-match-pro-contact-options');

                    //$this->initialize_contact_options();

                    submit_button('Save Contact Options');

                } elseif ($active_tab == 'label_options') {

                    register_setting(

                        'pet-match-pro-label-options',

                        'pet-match-pro-label-options'

                    );                

                    if ($this->integrationPartner == constant('PETPOINT')) {

                        echo $ppOptions->initialize_label_options();

                        submit_button('Save Labels');

					} elseif ($this->integrationPartner == constant('RESCUEGROUPS')) {

                        echo $rgOptions->initialize_label_options();

                        submit_button('Save Labels');

					} elseif ($this->integrationPartner == constant('ANIMALSFIRST')) {

                        echo $afOptions->initialize_label_options();

                        submit_button('Save Labels');

                    } else {

                        //load a message that will say whatever

                        echo 'Select a Partner API from the General Options tab to load label options.';

                    }

                } elseif ($active_tab == 'color_options') {

                    register_setting(

                        'pet-match-pro-color-options',

                        'pet-match-pro-color-options'

                    );   

                    $this->initialize_color_options();

                    settings_fields('pet-match-pro-color-options');

                    do_settings_sections('pet-match-pro-color-options');

                    submit_button('Save Colors');                    

                } elseif ($active_tab == 'instructions') {

                    register_setting(

                        'pet-match-pro-instructions',

                        'pet-match-pro-instructions'

                    );                

                    $this->initialize_instructions();

                    settings_fields('pet-match-pro-instructions');

                    do_settings_sections('pet-match-pro-instructions');

                }

                ?>

            </form>

        </div><!-- /.wrap -->

        <?php

    }

    public function general_options_callback() {

        $options = get_option('pet-match-pro-general-options');

        //var_dump($options);

        return '<p>' . __('General PetMatchPro Configuration', 'pet-match-pro-plugin') . '</p>';

    } 

    

    public function contact_options_callback() {

        $options = get_option('pet-match-pro-contact-options');

        //var_dump($options);

        return '<p>' . __('Settings for Contact Options', 'pet-match-pro-plugin') . '</p>';

    } 



    public function color_options_callback() {

        $options = get_option('pet-match-pro-color-options');

        //var_dump($options);

        return '<p>' . __('Settings for Color Options', 'pet-match-pro-plugin') . '</p>';

    } // end general_options_callback

    public function instructions_callback() {
        $options = get_option('pet-match-pro-instructions');
        //var_dump($options);
        return '<p>' . __('View PetMatchPro Instructions', 'pet-match-pro-plugin') . '</p>';
    } // end general_options_callback

    /**

     * Initializes the theme's display options page by registering the Sections,

     * Fields, and Settings.

     *

     * This function is registered with the 'admin_init' hook.

     */

    public function initialize_general_options() {

        // ADDED  BY 15-08-2022 PROLIFIC

       add_settings_section(

            'general_settings_section',                        // ID used to identify this section and with which to register options

            __('', 'pet-match-pro-plugin'),                    // Title to be displayed on the administration page

            array($this, 'general_options_callback'),          // Callback used to render the description of the section

            'pet-match-pro-general-options'                    // Page on which to add this section of options

        );

        // END ADDED  BY 15-08-2022 PROLIFIC 

                        

        /* Get Field Visibility Levels by License Type */
        $levelsFile = 'pmp-option-levels-general.php';
        $requireFile = $this->adminPartialsDir . $levelsFile;
//        $requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $levelsFile;
        //echo 'Require File = ' . $requireFile . '<br>';
        require($requireFile);
        //echo '<pre> ADMIN OPTION LEVEL VALUES '; print_r($pmpOptionLevelsGeneral); echo '</pre>';

        if ( (array_key_exists('level_integration_partner', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_integration_partner']) && (!empty($this->pmpLicenseKey)) ) {

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

        

            add_settings_field(

                'Integration Partner',

                __('Integration Partner', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'select_element_callback'),
//                array($this, 'select_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'integration_partner',

                    constant('INTEGRATION_PARTNERS'),

//                    Pet_Match_Pro_Public::$integrationPartners,

                    $this->pmpAdminInfo['integration_partner'],

                    'class' => $generalOptionsClass

                )

            );



        if ( (array_key_exists('level_partner_API_key', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_partner_API_key']) && (!empty($this->pmpLicenseKey)) ) {

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

        

            add_settings_field(

                'Integration Partner API Key',

                __('Integration Partner API Key', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),
//                array($this, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'partner_API_key',

                    $this->pmpAdminInfo['partner_API_key'],

                    'class' => $generalOptionsClass

                )

            );



		if ($this->integrationPartner == constant('ANIMALSFIRST')) {

	        if ( (array_key_exists('level_partner_API_source', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_partner_API_source']) && (!empty($this->pmpLicenseKey)) ) {

	            $generalOptionsClass = 'pmp-general-option';

	        } else {

	            $generalOptionsClass = 'pmp-option-disable';

	        }

	        $helpText = $this->pmpAdminInfo['partner_API_source'];

	    } else {

	    	$generalOptionsClass = 'pmp-option-exclude';

	    	$helpText = 'ANIMALSFIRST ONLY SETTING';

	    }

        

        add_settings_field(

            'partner_API_source',

            __('Integration Partner API Source', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'select_element_callback'),
//            array($this, 'select_element_callback'),

            'pet-match-pro-general-options',

            'general_settings_section',

            array('pet-match-pro-general-options',

                'partner_API_source',

                array('demo' => 'Demo', 'live' => 'Live'),

                $helpText,

                'class' => $generalOptionsClass

            )

        );


		if ($this->integrationPartner == constant('PETPOINT')) {

	        if ( (array_key_exists('level_on_hold_status', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_on_hold_status']) && (!empty($this->pmpLicenseKey)) ) {

	            $generalOptionsClass = 'pmp-general-option';

	        } else {

	            $generalOptionsClass = 'pmp-option-disable';

	        }

	        $helpText = $this->pmpAdminInfo['on_hold_status'];

	    } else {

	    	$generalOptionsClass = 'pmp-option-exclude';

	    	$helpText = 'PETPOINT ONLY SETTING';

	    }

        
        add_settings_field(

            'Default On Hold Status ',

            __('Default On Hold Status ', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'select_element_callback'),
//            array($this, 'select_element_callback'),

            'pet-match-pro-general-options',

            'general_settings_section',

            array('pet-match-pro-general-options',

                'on_hold_status',

                array('N' => 'No', 'Y' => 'Yes', 'A' => 'Either'),

                $helpText,

                'class' => $generalOptionsClass

            )

        );

        /* Get General Field Levels & Values */
        $levelsFile = 'pmp-field-values.php';
        $integrationDir = strtoupper($this->integrationPartner) . '_DIR';
        //echo 'Integration Director is ' . $integrationDir . '.<br>';
        $requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . '/' . constant($integrationDir) . '/' . constant('PARTIALS_DIR') . '/' . $levelsFile;
        //echo 'Require File = ' . $requireFile . '<br>';
        require($requireFile);
        //echo '<pre>GENERAL LEVEL & VALUES<br>'; print_r($pmpFieldValues); echo '</pre>';

		//$this->PMPLicenseTypeID = 3;
        
        /* Process Type Values */
        $methodTypes = [];
        if ( (array_key_exists('value_types', $pmpFieldValues)) && (array_key_exists('level_types', $pmpFieldValues)) ) {
        	$methodValues = $pmpFieldValues['value_types'];
        	$methodLevels = $pmpFieldValues['level_types'];
        	if (!empty($methodValues)) {
        		foreach ($methodValues as $methodKey => $methodValue) {
        			if (array_key_exists($methodKey, $methodLevels)) {
        				$methodLevel = $methodLevels[$methodKey];
        				//echo 'Method Key ' . $methodKey . ' has Level of ' . $methodLevel . '.<br>';
				        if ( ($this->PMPLicenseTypeID <= $methodLevel) ) {
        					$methodTypes[$methodKey] = $methodValue;
        				}
        			} else {
        				$methodTypes[$methodKey] = constant('ERROR') . ':' . $methodValue;
        			}
        		}
        	} else {
   	        	$methodTypes['available'] = 'Available';
        	}
        
        } else {
        	$methodTypes['available'] = 'Available';
        }
        
		if ($this->integrationPartner == constant('ANIMALSFIRST')) {
	        if ( (array_key_exists('level_default_method_type', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_default_method_type']) && (!empty($this->pmpLicenseKey)) ) {
	            $generalOptionsClass = 'pmp-general-option';
	        } else {
	            $generalOptionsClass = 'pmp-option-disable';
	        }
	        $helpText = $this->pmpAdminInfo['default_method_type'];
	    } else {
	    	$generalOptionsClass = 'pmp-option-exclude';
	    	$helpText = 'ANIMALSFIRST ONLY SETTING';
	    }
        
        add_settings_field(
            'default_method_type',
            __('Default Method Type ', 'pet-match-pro-plugin'),
            array($this->adminFunction , 'select_element_callback'),
//            array($this, 'select_element_callback'),
            'pet-match-pro-general-options',
            'general_settings_section',
            array('pet-match-pro-general-options',
                'default_method_type',
                $methodTypes,
                $helpText,
                'class' => $generalOptionsClass
            )
        );
        
		if ($this->integrationPartner == constant('RESCUEGROUPS')) {

            $generalOptionsClass = 'pmp-general-option';

            $helpText = $this->pmpAdminInfo['organization_id']; 

        } else {

            $generalOptionsClass = 'pmp-option-exclude';

            $helpText = 'RESCUEGROUPS ONLY SETTING'; 

        }

	    add_settings_field(

	      	'organization_id',

	        __('Organization ID', 'pet-match-pro-plugin'),
 
	        array($this->adminFunction, 'input_element_callback'),
//	        array($this, 'input_element_callback'),

	          	'pet-match-pro-general-options',

	            'general_settings_section',

               	array('pet-match-pro-general-options',

       	             'organization_id',

       	             $helpText,

       	             'class' => $generalOptionsClass

               	)

        );

		if ($this->integrationPartner !== constant('PETPOINT')) {

	        if ( (array_key_exists('level_search_result_limit', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_search_result_limit']) && (!empty($this->pmpLicenseKey)) ) {

	            $generalOptionsClass = 'pmp-general-option';

	        } else {

	            $generalOptionsClass = 'pmp-option-disable';

	        }

	        $helpText = $this->pmpAdminInfo['search_result_limit'];

	    } else {

	    	$generalOptionsClass = 'pmp-option-exclude';

	    	$helpText = 'PETPOINT DOES NOT SUPPORT THIS SETTING';

	    }

	        

    	add_settings_field(

        	'search_result_limit',

            __('Search Result Limit', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),
//            array($this, 'input_element_callback'),

            	'pet-match-pro-general-options',

                'general_settings_section',

               	array('pet-match-pro-general-options',

   		             'search_result_limit',

   		             $helpText,

   		             'class' => $generalOptionsClass

               	)

            );



        if ( (array_key_exists('level_order_by', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_order_by']) && (!empty($this->pmpLicenseKey)) ) {   

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

		/* Get Integration Partner Specific Details */
		if ( $this->integrationPartner == constant('PETPOINT') ) {
            $orderbyField = constant('PETPOINT_ORDERBY');
//            $adoptMethodType = constant('ADOPT_METHODTYPE_PETPOINT');
//            $foundMethodType = constant('FOUND_METHODTYPE_PETPOINT');
//            $lostMethodType = constant('LOST_METHODTYPE_PETPOINT');
		} elseif ( $this->integrationPartner == constant('RESCUEGROUPS') ) {
            $orderbyField = constant('RESCUEGROUPS_ORDERBY');
            $sortOrderField = constant('RESCUEGROUPS_SORTORDER');
//            $adoptMethodType = constant('ADOPT_METHODTYPE_RESCUEGROUPS');
		} elseif ( $this->integrationPartner == constant('ANIMALSFIRST') ) {
            $orderbyField = constant('ANIMALSFIRST_ORDERBY');
            $sortOrderField = constant('ANIMALSFIRST_SORTORDER');            
//            $adoptMethodType = constant('ADOPT_METHODTYPE_ANIMALSFIRST');
//            $foundMethodType = constant('FOUND_METHODTYPE_ANIMALSFIRST');
//            $lostMethodType = constant('LOST_METHODTYPE_ANIMALSFIRST');
//            $preferredMethodType = constant('PREFERRED_METHODTYPE_ANIMALSFIRST');            
		}
//        require($requireFile);

       	$orderidValues = $this->adminFunction->Filter_Option_Values($this->adoptMethodType, $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_SORT'), constant('LABEL_PREFIX_SEARCH_SORT'));  
       	//echo '<pre>Order By Values<br>'; print_r($orderidValues); echo '</pre>';

            add_settings_field(
                'order_by',
                __('Default Order By ', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'select_element_callback'),
//                array($this, 'select_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    'order_by',
                    $this->adminFunction->keyAndLabel($orderidValues),
                    $this->pmpAdminInfo['order_by'],
                    'class' => $generalOptionsClass             
                )
            );

		if ($this->integrationPartner != constant('PETPOINT')) {

       		$sortOrderValues = $this->adminFunction->Filter_Option_Values($this->adoptMethodType, $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_ORDER'), constant('LABEL_PREFIX_SEARCH_ORDER'));  
			
	        $helpText = $this->pmpAdminInfo['sort_order'];

	        if ( (array_key_exists('level_sort_order', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_sort_order']) && (!empty($this->pmpLicenseKey)) ) {

            	$generalOptionsClass = 'pmp-general-option';

		    } else {

            	$generalOptionsClass = 'pmp-option-disable';		    

		    }

	    } else {

   			$sortOrderValues['NA'] = array('NA' => 'Not Applicable');

	    	$helpText = 'PETPOINT DOES NOT SUPPORT THIS SETTING';

	    	$generalOptionsClass = 'pmp-option-exclude';

	    }



   	    add_settings_field(
   	        'sort_order',
   	        __('Sort Order', 'pet-match-pro-plugin'),
   	        array($this->adminFunction, 'select_element_callback'),
//   	        array($this, 'select_element_callback'),
   	        'pet-match-pro-general-options',
   	        'general_settings_section',
   	        array('pet-match-pro-general-options',
   	            'sort_order',
   	            $this->adminFunction->keyAndLabel($sortOrderValues),
   	            $helpText,
   	            'class' => $generalOptionsClass             
   	        )
   	    );
            
        /* New Setting to Control Display of Orderby Labels */

        if ( (array_key_exists('level_orderby_labels', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_orderby_labels']) && (!empty($this->pmpLicenseKey)) ) {   

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

        

            add_settings_field(

                'orderby_labels',

                __('Update Order By with Label Settings', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'checkbox_element_callback'),
//                array($this, 'checkbox_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'orderby_labels',

                    array('0' => 'Yes'),

                    $this->pmpAdminInfo['orderby_labels'],

                    'class' => $generalOptionsClass

                )

            );



        if ( (array_key_exists('level_results_per_row', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_results_per_row']) && (!empty($this->pmpLicenseKey)) ) {

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

            

            add_settings_field(

                'results_per_row',

                __('Search Results per Row ', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'select_element_callback'),
//                array($this, 'select_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'results_per_row',

                    array('3' => '3', '4' => '4', '5' => '5'),

                    $this->pmpAdminInfo['results_per_row'],

                    'class' => $generalOptionsClass                           

                )

            );



		//echo 'level_search_icon_adopt = ' . $pmpOptionLevelsGeneral['level_search_icon_adopt'] . '<br>';		

		if ( (array_key_exists('level_search_icons_' . $this->adoptMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_search_icons_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {

			//echo 'Enable level_search_icon_adopt<br>';

			$classSearchIcons = 'pmp-search-icons-' . $this->adoptMethodType;

		} else {

			//echo 'Disable level_search_icon_adopt<br>';		

			$classSearchIcons = 'pmp-option-disable';

		}							

		

		add_settings_field(

			$this->adoptMethodType . '_animal_search_icons',

			__( 'Show Search Icons?', 'pet-match-pro-plugin' ),

			array( $this->adminFunction, 'checkbox_element_callback'),
//			array( $this, 'checkbox_element_callback'),

			'pet-match-pro-general-options',	            

			'general_settings_section',

        	array('pet-match-pro-general-options',

            	$this->adoptMethodType . '_animal_search_icons',

                array('Enable' => 'Show Icons on Search Results'),

                $this->pmpAdminInfo[$this->adoptMethodType . '_animal_search_icons'],

                'class' => $classSearchIcons

			)

		);

		

		$generalOptions = get_option('pet-match-pro-general-options');
		if (is_array($generalOptions)) {
			if (array_key_exists($this->adoptMethodType . '_animal_search_icons', $generalOptions)) {
				$showSearchIcons = $generalOptions[$this->adoptMethodType . '_animal_search_icons'];	
			} else {
				$showSearchIcons = '';
			}	
		} else {
			$showSearchIcons = '';
		}	
		//echo 'Show Search Icon Settings = ' . $showSearchIcons . '<br>';

				

		//echo 'level_search_icons_max_adopt = ' . $pmpOptionLevelsGeneral['level_search_icons_max_adopt'] . '<br>';

		if ( (array_key_exists('level_search_icons_max_' . $this->adoptMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_search_icons_max_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) && ($showSearchIcons != '') ) {

            $classSearchIconsMax = 'pmp-search-icons-max-' . $this->adoptMethodType;

	    } else {

	        $classSearchIconsMax = 'pmp-option-disable';

	    }

		   

	    add_settings_field(

        	$this->adoptMethodType . '_animal_search_icons_max',

            __('Max Search Icons ', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'select_element_callback'),
//            array($this, 'select_element_callback'),

            'pet-match-pro-general-options',

            'general_settings_section',

	            array('pet-match-pro-general-options',

                $this->adoptMethodType . '_animal_search_icons_max',

                array('3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12'),

                $this->pmpAdminInfo[$this->adoptMethodType . '_animal_search_icons_max'],

                'class' => $classSearchIconsMax                           

            )

        );



		//echo 'level_detail_thumbs_max_adopt = ' . $pmpOptionLevelsGeneral['level_detail_thumbs_max_adopt'] . '<br>';

		if ( (array_key_exists('level_detail_thumbs_max_' . $this->adoptMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_detail_thumbs_max_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {

            $classDetailThumbsMax = 'pmp-detail-thumbs-max-' . $this->adoptMethodType;

	    } else {

	        $classDetailThumbsMax = 'pmp-option-disable';

	    }

		   

	    add_settings_field(

        	$this->adoptMethodType . '_animal_detail_thumbs_max',

            __('Max Detail Thumbnails ', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'select_element_callback'),
//            array($this, 'select_element_callback'),

            'pet-match-pro-general-options',

            'general_settings_section',

	            array('pet-match-pro-general-options',

                $this->adoptMethodType . '_animal_detail_thumbs_max',

                array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12'),

                $this->pmpAdminInfo[$this->adoptMethodType . '_animal_detail_thumbs_max'],

                'class' => $classDetailThumbsMax                           

            )

        );



		//echo 'level_detail_icons_adopt = ' . $pmpOptionLevelsGeneral['level_detail_icons_adopt'] . '<br>';

		if ( (array_key_exists('level_detail_icons_' . $this->adoptMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_detail_icons_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {

			//echo 'Enable level_animal_detail_icons_adopt<br>';

			$classDetailIcons = 'pmp-detail-icons-' . $this->adoptMethodType;

		} else {

			//echo 'Disable level_animal_detail_icons_adopt<br>';		

			$classDetailIcons = 'pmp-option-disable';

		}							

		

		add_settings_field(

			$this->adoptMethodType . '_animal_detail_icons',

			__( 'Show Detail Icons?', 'pet-match-pro-plugin' ),

			array( $this->adminFunction, 'checkbox_element_callback'),
//			array( $this, 'checkbox_element_callback'),

			'pet-match-pro-general-options',	            

			'general_settings_section',

        	array('pet-match-pro-general-options',

            	$this->adoptMethodType . '_animal_detail_icons',

                array('Enable' => 'Show Icons on Animal Details'),

                $this->pmpAdminInfo[$this->adoptMethodType . '_animal_detail_icons'],

                'class' => $classDetailIcons

			)

		);

		if (is_array($generalOptions)) {
			if (array_key_exists($this->adoptMethodType . '_animal_detail_icons', $generalOptions)) {
				$showDetailIcons = $generalOptions[$this->adoptMethodType . '_animal_detail_icons'];
			} else {
				$showDetailIcons = '';
			}
		} else {
			$showDetailIcons = '';
		}
		//echo 'Show Detail Icon Settings = ' . $showDetailIcons . '<br>';

			

		//echo 'level_detail_icons_max_adopt = ' . $pmpOptionLevelsGeneral['level_detail_icons_max_adopt'] . '<br>';

		if ( (array_key_exists('level_detail_icons_max_' . $this->adoptMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_detail_icons_max_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) && ($showDetailIcons != '') ) {

            $classDetailIconsMax = 'pmp-detail-icons-max-' . $this->adoptMethodType;

	    } else {

	        $classDetailIconsMax = 'pmp-option-disable';

	    }

		   

	    add_settings_field(

        	$this->adoptMethodType . '_animal_detail_icons_max',

            __('Max Detail Icons ', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'select_element_callback'),
//            array($this, 'select_element_callback'),

            'pet-match-pro-general-options',

            'general_settings_section',

	            array('pet-match-pro-general-options',

                $this->adoptMethodType . '_animal_detail_icons_max',

                array('3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12'),

                $this->pmpAdminInfo[$this->adoptMethodType . '_animal_detail_icons_max'],

                'class' => $classDetailIconsMax                           

            )

        );

		

        if ( (array_key_exists('level_paginate_results', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_paginate_results']) && (!empty($this->pmpLicenseKey)) ) {   

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

                    

            add_settings_field(

                'paginate_results',

                __('Enable Pagination', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'checkbox_element_callback'),
//                array($this, 'checkbox_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'paginate_results',

                    array('0' => 'Yes'),

                    $this->pmpAdminInfo['paginate_results'],

                    'class' => $generalOptionsClass

                )

            );



		$enableKey = 'enable_age_in_years_' . $this->partnerDir;

		$enableValue = 0;

		if ( (array_key_exists($enableKey, $pmpOptionLevelsGeneral)) ) {

			$enableValue = $pmpOptionLevelsGeneral[$enableKey];

		}

		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		

		if ($enableValue == 1) {

	        if ( (array_key_exists('level_age_in_years', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_age_in_years']) && (!empty($this->pmpLicenseKey)) ) {   

	            $generalOptionsClass = 'pmp-general-option';

	        } else {

	            $generalOptionsClass = 'pmp-option-disable';

	        }

	                            

	            add_settings_field(

	                'age_in_years',

	                __( 'Show Age in Years?', 'pet-match-pro-plugin' ),

	                array( $this->adminFunction, 'checkbox_element_callback'),
//	                array( $this, 'checkbox_element_callback'),

	                'pet-match-pro-general-options',

	                'general_settings_section',

	                    array('pet-match-pro-general-options',

	                        'age_in_years',

	                        array('0' => 'Yes'),

	                        $this->pmpAdminInfo['age_in_years'],

	                    'class' => $generalOptionsClass

	

	                    )

	            );

	    }

                

        /* Setting to Track Event Activity */

        if ( (array_key_exists('level_ga_tracking_method', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_ga_tracking_method']) && (!empty($this->pmpLicenseKey)) ) {       

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

                                        

            add_settings_field(

                'ga_tracking_method',

                __('Google Analytics Tracking Method', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'select_element_callback'),
//                array($this, 'select_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'ga_tracking_method',

                    array('NA' => 'Not Installed', 'GA4' => 'Analytics 4', 'GTM' => 'Tag Manager'),

                    $this->pmpAdminInfo['ga_tracking_method'],

                    'class' => $generalOptionsClass                              

                )

            );       

		if ($this->integrationPartner == constant('ANIMALSFIRST')) {
           	$generalOptionsClass = 'pmp-general-option';
           	$helpText = $this->pmpAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_methodtype_label']; 
       	} else {
           	$generalOptionsClass = 'pmp-option-exclude';
           	$helpText = 'ANIMALSFIRST ONLY SETTING'; 
       	}

			/* Preferred Method Type Label */
            add_settings_field(
                constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_methodtype_label',
                __(ucfirst(constant('PREFERRED_METHODTYPE_ANIMALSFIRST')) . ' Method Type Label', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'input_element_callback'),
//                array($this, 'input_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_methodtype_label',
                    $helpText,
                    'class' => $generalOptionsClass
                )
            );

        //scan directory

        /* RMB - Check if Child Directory Exists, Otherwise Set to Plugin Default */     

//        $general_options = get_option('pet-match-pro-general-options');

        if ( (is_dir(get_stylesheet_directory(dirname(__FILE__)) . '/' . constant('PLUGIN_DIR') . '/' . $this->templateDir)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL') ) ) { /* RMB - Paid Feature */
            $finalTemplateDir = get_stylesheet_directory(dirname(__FILE__)) . '/' . constant('PLUGIN_DIR') . '/' . $this->templateDir;
            $templates = array_diff(scandir($finalTemplateDir), array('..', '.')); 
        } elseif ($this->PMPLicenseTypeID != constant('FREE_LEVEL'))  {
            $finalTemplateDir = constant("PET_MATCH_PRO_PATH") . '/' . constant('PUBLIC_DIR') . '/' . $this->templateDir;
            $templates = array_diff(scandir($finalTemplateDir), array('..', '.'));  
        } else {
            $finalTemplateDir = constant("PET_MATCH_PRO_PATH") . '/' . constant('PUBLIC_DIR') . '/' . $this->templateDir;
            $templates = array_diff(glob($finalTemplateDir . TemplateFilter), array('..', '.')); 
        }
        //echo 'Template Director is ' . $finalTemplateDir . '<br>';

        $templatesClean = [];
        $templateVals 	= [];
        foreach ($templates as $template) {
            $cleanUp = str_replace('.php', '', $template);            
            $cleanUp = str_replace($finalTemplateDir, '', $cleanUp); /* Remove Path When Only Displaying Default Templates for Free License */
            $templatesClean[$cleanUp] = $cleanUp;
            /* Filter for adopt Templates */
            //echo 'Processing Template ' . $cleanUp . '.<br>';
            if (str_contains($cleanUp, $this->adoptMethodType)) {
            	//echo 'Adding Template ' . $cleanUp . ' to List.<br>';
            	$templateVals[$cleanUp] = $cleanUp;
            }
        }
        if (count($templateVals) == 0) {
        	$templateVals = $templatesClean;
        }        

        if ( (array_key_exists('level_details_template_' . $this->adoptMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_details_template_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {                                       
            $generalOptionsClass = 'pmp-general-option';
        } else {
            $generalOptionsClass = 'pmp-option-disable';
        }
        
            add_settings_field(
                'details_template_' . $this->adoptMethodType,
                __(ucfirst($this->adoptMethodType) . ' Details Template', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'select_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    'details_template_' . $this->adoptMethodType,
                    $templateVals,
                    $this->pmpAdminInfo['details_template_' . $this->adoptMethodType],
                    'class' => $generalOptionsClass         
                )
            );

		$enableKey = 'enable_details_template_' . $this->foundMethodType . '_' . $this->partnerDir;
		$enableValue = 0;
		if ( (array_key_exists($enableKey, $pmpOptionLevelsGeneral)) ) {
			$enableValue = $pmpOptionLevelsGeneral[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        $templateVals = [];
	        foreach ($templatesClean as $template) {
	            /* Filter for found Templates */
	            if (str_contains($template, $this->foundMethodType)) {
	            	//echo 'Adding Template ' . $cleanUp . ' to List.<br>';
	            	$templateVals[$template] = $template;
	            }
	        }
	        if (count($templateVals) == 0) {
	        	$templateVals = $templatesClean;
	        }	        
		
	        if ( (array_key_exists('level_details_template_' . $this->foundMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_details_template_' . $this->foundMethodType]) && (!empty($this->pmpLicenseKey)) ) {       
	            $generalOptionsClass = 'pmp-general-option';
	        } else {
	            $generalOptionsClass = 'pmp-option-disable';
	        }

            add_settings_field(
                'details_template_' . $this->foundMethodType,
                __(ucfirst($this->foundMethodType) . ' Details Template', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'select_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    'details_template_' . $this->foundMethodType,
                    $templateVals,
                    $this->pmpAdminInfo['details_template_' . $this->foundMethodType],
                    'class' => $generalOptionsClass                            
                )
            );
		}

		$enableKey = 'enable_details_template_' . $this->lostMethodType . '_' . $this->partnerDir;
		$enableValue = 0;
		if ( (array_key_exists($enableKey, $pmpOptionLevelsGeneral)) ) {
			$enableValue = $pmpOptionLevelsGeneral[$enableKey];
		}

		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';
		if ($enableValue == 1) {
	        $templateVals = [];
	        foreach ($templatesClean as $template) {
	            /* Filter for lost Templates */
	            if (str_contains($template, $this->lostMethodType)) {
	            	//echo 'Adding Template ' . $cleanUp . ' to List.<br>';
	            	$templateVals[$template] = $template;
	            }
	        }
	        if (count($templateVals) == 0) {
	        	$templateVals = $templatesClean;
	        }	        
		
	        if ( (array_key_exists('level_details_template_' . $this->lostMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_details_template_' . $this->lostMethodType]) && (!empty($this->pmpLicenseKey)) ) { 
	            $generalOptionsClass = 'pmp-general-option';
	        } else {
	            $generalOptionsClass = 'pmp-option-disable';
	        }

            add_settings_field(
                'details_template_' . $this->lostMethodType,
                __(ucfirst($this->lostMethodType) . ' Details Template', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'select_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    'details_template_' . $this->lostMethodType,
                    $templateVals,
                    $this->pmpAdminInfo['details_template_' . $this->lostMethodType],
                    'class' => $generalOptionsClass                             
                )
            );
        }

		$preferredLabel = ucfirst(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'));
		if (is_array($generalOptions)) {
			if (array_key_exists(constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_methodtype_label', $generalOptions)) {
				$preferredLabel = $generalOptions[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_methodtype_label'];
				if (strlen(trim($preferredLabel)) == 0) {
					$preferredLabel = ucfirst(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'));
				}
			} else {
				$preferredLabel = ucfirst(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'));
			}			
		}
		
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';
		if ($this->integrationPartner == constant('ANIMALSFIRST')) {
	        $templateVals = [];
	        foreach ($templatesClean as $template) {
	            /* Filter for preferred Templates */
	            if ( (str_contains($template, $this->preferredMethodType)) || (str_contains($template, strtolower(constant('PREFERRED_METHODTYPE_ANIMALSFIRST')))) ) {
	            	//echo 'Adding Template ' . $cleanUp . ' to List.<br>';
	            	$templateVals[$template] = $template;
	            }
	        }
	        if (count($templateVals) == 0) {
	        	$templateVals = $templatesClean;
	        }

			$templateKey = substr_replace(constant('LEVEL_PREFIX_ANIMAL_DETAIL'), '', -1) . 's_template_' . $this->preferredMethodType;
			//echo 'Preferred Template Key is ' . $templateKey . '.<br>';	
	        if ( (array_key_exists($templateKey, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral[$templateKey]) && (!empty($this->pmpLicenseKey)) ) { 
	            $generalOptionsClass = 'pmp-general-option';
	        } else {
	            $generalOptionsClass = 'pmp-option-disable';
	        }

			$templateLabel = $preferredLabel . ' Details Template';
            add_settings_field(
                'details_template_preferred',
                __($templateLabel, 'pet-match-pro-plugin'),
                array($this->adminFunction, 'select_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    'details_template_' . $this->preferredMethodType,
                    $templateVals,
                    $this->pmpAdminInfo['details_template_' . $this->preferredMethodType],
                    'class' => $generalOptionsClass                             
                )
            );
        }

        $pages = get_pages();

        $shortCodePage = [];

        //$pages = $pages->

        foreach ($pages as $item) {

            //print_r($item);

            $shortCodePage[$item->ID] = $item->post_title;

        }

        //print_r($shortCodePage);

        //exit;



        if ( (array_key_exists('level_details_page_' . $this->adoptMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_details_page_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

                                                                                

            add_settings_field(

                'details_page_' . $this->adoptMethodType,

                //'Page that has the details shortcode',

                __(ucfirst($this->adoptMethodType) . ' Details Page', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'select_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'details_page_' . $this->adoptMethodType,

                    $shortCodePage,

                    $this->pmpAdminInfo['details_page_' . $this->adoptMethodType],

                    'class' => $generalOptionsClass                                        

                )

            );



		$enableKey = 'enable_details_page_' . $this->foundMethodType . '_' . $this->partnerDir;

		$enableValue = 0;		

		if ( (array_key_exists($enableKey, $pmpOptionLevelsGeneral)) ) {

			$enableValue = $pmpOptionLevelsGeneral[$enableKey];

		}

		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		

		if ($enableValue == 1) {

	        if ( (array_key_exists('level_details_page_' . $this->foundMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_details_page_' . $this->foundMethodType]) && (!empty($this->pmpLicenseKey)) ) {   

	            $generalOptionsClass = 'pmp-general-option';

	        } else {

	            $generalOptionsClass = 'pmp-option-disable';

	        }

                                                                                                 

            add_settings_field(

                'details_page_' . $this->foundMethodType,

                //'Page for the Found Details Page',

                __(ucfirst($this->foundMethodType) . ' Details Page', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'select_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'details_page_' . $this->foundMethodType,

                    $shortCodePage,

                    $this->pmpAdminInfo['details_page_' . $this->foundMethodType],

                    'class' => $generalOptionsClass                                           

                )

            );

		}

		$enableKey = 'enable_details_page_' . $this->lostMethodType . '_' . $this->partnerDir;
		$enableValue = 0;		
		if ( (array_key_exists($enableKey, $pmpOptionLevelsGeneral)) ) {
			$enableValue = $pmpOptionLevelsGeneral[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        if ( (array_key_exists('level_details_page_' . $this->lostMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_details_page_' . $this->lostMethodType]) && (!empty($this->pmpLicenseKey)) ) {
	            $generalOptionsClass = 'pmp-general-option';
	        } else {
	            $generalOptionsClass = 'pmp-option-disable';
	        }

            add_settings_field(
                'details_page_' . $this->lostMethodType,
                //'Page for the Lost Details Page',
                __(ucfirst($this->lostMethodType) . ' Details Page', 'pet-match-pro-plugin'),
                //__('Page that has the details shortcode for Lost', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'select_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    'details_page_' . $this->lostMethodType,
                    $shortCodePage,
                    $this->pmpAdminInfo['details_page_' . $this->lostMethodType],
                    'class' => $generalOptionsClass                                                            
                )
            );  
		}

		if ($this->integrationPartner == constant('ANIMALSFIRST')) {
			$pageKey = substr_replace(constant('LEVEL_PREFIX_ANIMAL_DETAIL'), '', -1) . 's_page_' . $this->preferredMethodType;	
			//echo 'Preferred Page Key is ' . $pageKey . '.<br>';	
	        if ( (array_key_exists($pageKey, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral[$pageKey]) && (!empty($this->pmpLicenseKey)) ) {
	            $generalOptionsClass = 'pmp-general-option';
	        } else {
	            $generalOptionsClass = 'pmp-option-disable';
	        }

			$pageLabel = $preferredLabel . ' Details Page';

            add_settings_field(
                'details_page_' . $this->preferredMethodType,
                __($pageLabel, 'pet-match-pro-plugin'),
                array($this->adminFunction, 'select_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    'details_page_' . $this->preferredMethodType,
                    $shortCodePage,
                    $this->pmpAdminInfo['details_page_' . $this->preferredMethodType],
                    'class' => $generalOptionsClass                                                            
                )
            );  
		}

        /* New Setting for Details Poster */

        if ( (array_key_exists('level_details_page_poster', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_details_page_poster']) && (!empty($this->pmpLicenseKey)) ) {

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

                                                                                                        

            add_settings_field(

                'details_page_poster',

                __('Details Poster Page', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'select_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'details_page_poster',

                    $shortCodePage,

                    $this->pmpAdminInfo['details_page_poster'],

                    'class' => $generalOptionsClass

                )

            );  

            

        /* New Setting for Search Feature Link */

        if ( (array_key_exists('level_search_features', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_search_features']) && (!empty($this->pmpLicenseKey)) ) {

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

                                                                                                        

            add_settings_field(

                'search_feature_link',

                __('Search Feature Button Link', 'pet-match-pro-plugin'),

	            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'search_feature_link',

                    $this->pmpAdminInfo['search_feature_link'],

                    'class' => $generalOptionsClass

                )

            );     



            add_settings_field(

                'search_feature_target',

                __('Search Feature Button Target', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'select_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                	'search_feature_target',

                    array('_blank' => 'New', '_parent' => 'Parent', '_self' => 'Same'),

                    $this->pmpAdminInfo['search_feature_target'],

                    'class' => $generalOptionsClass

                )

            );     

                       

            add_settings_field(

                'search_feature_class',

                __('Search Feature Button Class', 'pet-match-pro-plugin'),

	            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'search_feature_class',

                    $this->pmpAdminInfo['search_feature_class'],

                    'class' => $generalOptionsClass

                )

            );     

            

            add_settings_field(

                'search_feature_label',

                __('Search Feature Button Label', 'pet-match-pro-plugin'),

	            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'search_feature_label',

                    $this->pmpAdminInfo['search_feature_label'],

                    'class' => $generalOptionsClass

                )

            );     

              

        if ( (array_key_exists('level_no_search_results_' . $this->adoptMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_no_search_results_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

                                                                                                                          

            add_settings_field(

                'no_search_results_' . $this->adoptMethodType,

                __(ucfirst($this->adoptMethodType) . ' No Animals Found Message ', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'textarea_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'no_search_results_' . $this->adoptMethodType,

                    $this->pmpAdminInfo['no_search_results_' . $this->adoptMethodType],

                    'class' => $generalOptionsClass

                )

            );

		$enableKey = 'enable_no_search_results_' . $this->foundMethodType . '_' . $this->partnerDir;
		$enableValue = 0;				
		if ( (array_key_exists($enableKey, $pmpOptionLevelsGeneral)) ) {
			$enableValue = $pmpOptionLevelsGeneral[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {       
	        if ( (array_key_exists('level_no_search_results_' . $this->foundMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_no_search_results_' . $this->foundMethodType]) && (!empty($this->pmpLicenseKey)) ) { 
	            $generalOptionsClass = 'pmp-general-option';
	        } else {
	            $generalOptionsClass = 'pmp-option-disable';
	        }
                                                                                                                                     
            add_settings_field(
                'no_search_results_' . $this->foundMethodType,
                __(ucfirst($this->foundMethodType) . ' No Animals Found Message ', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'textarea_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    'no_search_results_' . $this->foundMethodType,
                    $this->pmpAdminInfo['no_search_results_' . $this->foundMethodType],
                    'class' => $generalOptionsClass
                )
            );
		}

		$enableKey = 'enable_no_search_results_' . $this->lostMethodType . '_' . $this->partnerDir;
		$enableValue = 0;				
		if ( (array_key_exists($enableKey, $pmpOptionLevelsGeneral)) ) {
			$enableValue = $pmpOptionLevelsGeneral[$enableKey];
		}

		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';
		if ($enableValue == 1) {       
	        if ( (array_key_exists('level_no_search_results_' . $this->lostMethodType, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_no_search_results_' . $this->lostMethodType]) && (!empty($this->pmpLicenseKey)) ) {
	            $generalOptionsClass = 'pmp-general-option';
	        } else {
	            $generalOptionsClass = 'pmp-option-disable';
	        }

            add_settings_field(
                'no_search_results_' . $this->lostMethodType,
                __('Lost No Animals Found Message ', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'textarea_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    'no_search_results_' . $this->lostMethodType,
                    $this->pmpAdminInfo['no_search_results_' . $this->lostMethodType],
                    'class' => $generalOptionsClass
                )
            );  
		}

		if ($this->integrationPartner == constant('ANIMALSFIRST')) {
			$resultsKey = 'level_no_search_results_' . $this->preferredMethodType;		
	        if ( (array_key_exists($resultsKey, $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral[$resultsKey]) && (!empty($this->pmpLicenseKey)) ) {
	            $generalOptionsClass = 'pmp-general-option';
	        } else {
	            $generalOptionsClass = 'pmp-option-disable';
	        }

			$resultsKey = 'no_search_results_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST'); 
			$resultsLabel = $preferredLabel . ' No Animals Found Message';

            add_settings_field(
                'no_search_results_' . $this->preferredMethodType,
                __($resultsLabel, 'pet-match-pro-plugin'),
                array($this->adminFunction, 'textarea_element_callback'),
                'pet-match-pro-general-options',
                'general_settings_section',
                array('pet-match-pro-general-options',
                    'no_search_results_' . $this->preferredMethodType,
                    $this->pmpAdminInfo['no_search_results_' . $this->preferredMethodType],
                    'class' => $generalOptionsClass
                )
            );  
		}

		$enableKey = 'enable_no_search_results_featured_' . $this->partnerDir;

		$enableValue = 0;				

		if ( (array_key_exists($enableKey, $pmpOptionLevelsGeneral)) ) {

			$enableValue = $pmpOptionLevelsGeneral[$enableKey];

		}

		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		

		if ($enableValue == 1) {       

	        if ( (array_key_exists('level_no_search_results_featured', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_no_search_results_featured']) && (!empty($this->pmpLicenseKey)) ) {   

	            $generalOptionsClass = 'pmp-general-option';

	        } else {

	            $generalOptionsClass = 'pmp-option-disable';

	        }

                                                                                                                                     

            add_settings_field(

                'no_search_results_featured',

                __('Featured No Animal Found Message ', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'textarea_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'no_search_results_featured',

                    $this->pmpAdminInfo['no_search_results_featured'],

                    'class' => $generalOptionsClass

                )

            );

		}



        if ( (array_key_exists('level_default_description', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_default_description']) && (!empty($this->pmpLicenseKey)) ) {

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

        			        

            add_settings_field(

                'default_description',

                __('Default Animal Description ', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'textarea_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'default_description',

                    $this->pmpAdminInfo['default_description'],

                    'class' => $generalOptionsClass

                )

            );



		if ( (array_key_exists('level_location_exclusion', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_location_exclusion']) && (!empty($this->pmpLicenseKey)) ) {

			$classLocationExclusion = 'pmp-location-exclusion';

		} else {

			$classLocationExclusion = 'pmp-option-disable';

		}			

	    

		/* RMB - Add Settings for Exclusion Locations */         

        add_settings_field(

            'location_exclusion_1',

            __('Location Exclusion #1', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_exclusion_1',

                $this->pmpAdminInfo['location_exclusion_1'],

                'class' => $classLocationExclusion

                )

        );



        add_settings_field(

            'location_exclusion_2',

            __('Location Exclusion #2', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_exclusion_2',

                $this->pmpAdminInfo['location_exclusion_2'],

                'class' => $classLocationExclusion

                 )

        );

	    

        add_settings_field(

            'location_exclusion_3',

            __('Location Exclusion #3', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_exclusion_3',

                $this->pmpAdminInfo['location_exclusion_3'],

                'class' => $classLocationExclusion

                 )

        );

        

        add_settings_field(

            'location_exclusion_4',

            __('Location Exclusion #4', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_exclusion_4',

                $this->pmpAdminInfo['location_exclusion_4'],

                'class' => $classLocationExclusion

                 )

        );



        add_settings_field(

            'location_exclusion_5',

            __('Location Exclusion #5', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_exclusion_5',

                $this->pmpAdminInfo['location_exclusion_5'],

                'class' => $classLocationExclusion

                 )

        );

        

		if ( (array_key_exists('level_location_filter', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_location_filter']) && (!empty($this->pmpLicenseKey)) ) {

			$classLocationFilter = 'pmp-location-filter-adopt';

		} else {

			$classLocationFilter = 'pmp-option-disable';

		}			

    

		/* RMB - Add Settings for Adoption Locations */         

        add_settings_field(

            'location_filter_1',

            __('Location Filter #1', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_1',

                $this->pmpAdminInfo['location_filter_1'],

                'class' => $classLocationFilter

            )

        );

        

        add_settings_field(

            'location_filter_1_label',

            __('Location Filter #1 Label', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_1_label',

                $this->pmpAdminInfo['location_filter_1_label'],

                'class' => $classLocationFilter

            )

        );        

        

        add_settings_field(

            'location_filter_2',

            __('Location Filter #2', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_2',

                $this->pmpAdminInfo['location_filter_2'],

                'class' => $classLocationFilter

            )

        );



        add_settings_field(

            'location_filter_2_label',

            __('Location Filter #2 Label', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_2_label',

                $this->pmpAdminInfo['location_filter_2_label'],

                'class' => $classLocationFilter

            )

        );

                

        add_settings_field(

            'location_filter_3',

            __('Location Filter #3', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_3',

                $this->pmpAdminInfo['location_filter_3'],

                'class' => $classLocationFilter

            )

        );



        add_settings_field(

            'location_filter_3_label',

            __('Location Filter #3 Label', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_3_label',

                $this->pmpAdminInfo['location_filter_3_label'],

                'class' => $classLocationFilter

            )

        );





        add_settings_field(

            'location_filter_4',

            __('Location Filter #4', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_4',

                $this->pmpAdminInfo['location_filter_4'],

                'class' => $classLocationFilter

            )

        );



        add_settings_field(

            'location_filter_4_label',

            __('Location Filter #4 Label', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_4_label',

                $this->pmpAdminInfo['location_filter_4_label'],

                'class' => $classLocationFilter

            )

        );



        add_settings_field(

            'location_filter_5',

            __('Location Filter #5', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_5',

                $this->pmpAdminInfo['location_filter_5'],

                'class' => $classLocationFilter

            )

        );



        add_settings_field(

            'location_filter_5_label',

            __('Location Filter #5 Label', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_5_label',

                $this->pmpAdminInfo['location_filter_5_label'],

                'class' => $classLocationFilter

            )

        );

                

        add_settings_field(

            'location_filter_other',

            __('Filter Location All Other', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_filter_other',

                $this->pmpAdminInfo['location_filter_other'],

                'class' => $classLocationFilter

            )

        );

        

		if ( (array_key_exists('level_locations', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_locations']) && (!empty($this->pmpLicenseKey)) ) {

			$classLocations = 'pmp-locations';

		} else {

			$classLocations = 'pmp-option-disable';

		}			

    

		/* RMB - Add Location Settings */         

        add_settings_field(

            'location_foster',

            __('Foster Location', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_foster',

                $this->pmpAdminInfo['location_foster'],

                'class' => $classLocations

            )

        );

        

        add_settings_field(

            'location_shelter',

            __('Shelter Location', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

            array(

                'pet-match-pro-general-options',

                'location_shelter',

                $this->pmpAdminInfo['location_shelter'],

                'class' => $classLocations

            )

        );



        if ( (array_key_exists('level_pmp_custom_css', $pmpOptionLevelsGeneral)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_pmp_custom_css']) && (!empty($this->pmpLicenseKey)) ) {   

            $generalOptionsClass = 'pmp-general-option';

        } else {

            $generalOptionsClass = 'pmp-option-disable';

        }

                                                                                                                                             

            add_settings_field(

                'Custom CSS',

                __('Custom CSS', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'textarea_element_callback'),

                'pet-match-pro-general-options',

                'general_settings_section',

                array('pet-match-pro-general-options',

                    'pmp_custom_css',

                    $this->pmpAdminInfo['pmp_custom_css'],

                    'class' => $generalOptionsClass                 

                )

            );

        

        register_setting(

            'pet-match-pro-general-options',

            'pet-match-pro-general-options'

        );

}



    public function initialize_contact_options() {       

        add_settings_section(

            'contact_settings_section',                        // ID used to identify this section and with which to register options

            __('', 'pet-match-pro-plugin'),                    // Title to be displayed on the administration page

            array($this, 'contact_options_callback'),          // Callback used to render the description of the section

            'pet-match-pro-contact-options'                    // Page on which to add this section of options

        );



        /* Get Field Visibility Levels by License Type */

        $levelsFile = 'pmp-option-levels-contact.php';
        $requireFile = $this->adminPartialsDir . $levelsFile;
//        $requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $levelsFile;

        require($requireFile);

        //echo '<pre> ADMIN OPTION LEVEL VALUES '; print_r($pmpOptionLevelsContact); echo '</pre>';



        //echo 'PMP License Type ID = ' . $PMPLicenseTypeID . '<br>';

        //$PMPLicenseTypeID = 4; 



        /* RMB - Add Setting to Control Use of Social Share Icons on Animal Detail Pages */   

        if ( (array_key_exists('level_social_share', $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_social_share']) && (!empty($this->pmpLicenseKey)) ) {

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

        

            add_settings_field(

                    'social_share',

                    __('Social Share Animal Details?', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'checkbox_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    'social_share',

                    array('0' => 'Yes'),

                    $this->pmpAdminInfo['social_share'],

                    'class' => $contactOptionsClass

                )

            );



        /* RMB - Add Setting for Adoption Phone Number */      

        if ( (array_key_exists('level_phone_' . $this->adoptMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_phone_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) { 

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

                    

            add_settings_field(

                $this->adoptMethodType . '_phone',

                __(ucfirst($this->adoptMethodType) . ' Phone', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->adoptMethodType . '_phone',

                    $this->pmpAdminInfo[$this->adoptMethodType . '_phone'],

                    'class' => $contactOptionsClass

                )

            );

        

        if ( (array_key_exists('level_email_' . $this->adoptMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_email_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {  

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

                                

            add_settings_field(

                $this->adoptMethodType . '_email',

                __(ucfirst($this->adoptMethodType) . ' Email', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->adoptMethodType . '_email',

                    $this->pmpAdminInfo[$this->adoptMethodType . '_email'],

                    'class' => $contactOptionsClass

                )

            );



        if ( (array_key_exists('level_link_' . $this->adoptMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_link_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) { 

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

                                            

            add_settings_field(

                $this->adoptMethodType . '_link',

                __(ucfirst($this->adoptMethodType) . ' Application URL', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->adoptMethodType . '_link',

                    $this->pmpAdminInfo[$this->adoptMethodType . '_link'],

                    'class' => $contactOptionsClass

                )

            );  

                

        /* Vanity Adoption URL */        

        if ( (array_key_exists('level_vanity_link_' . $this->adoptMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_vanity_link_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) { 

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }



            add_settings_field(

                $this->adoptMethodType . '_vanity_link',

                __(ucfirst($this->adoptMethodType) . ' Vanity URL', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->adoptMethodType . '_vanity_link',

                    $this->pmpAdminInfo[$this->adoptMethodType . '_vanity_link'],

                    'class' => $contactOptionsClass

                )

            );          

        

        /* RMB - Add Setting for Foster Phone Number */           

        if ( (array_key_exists('level_foster_phone_' . $this->adoptMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_foster_phone_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

                                                    

            add_settings_field(

                $this->adoptMethodType . '_foster_phone',

                __('Foster Phone', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->adoptMethodType . '_foster_phone',

                    $this->pmpAdminInfo[$this->adoptMethodType . '_foster_phone'],

                    'class' => $contactOptionsClass

                )

            );

           

        /* RMB - Add Setting for Foster Email Address */

        if ( (array_key_exists('level_foster_email_' . $this->adoptMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_foster_email_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

                                                            

            add_settings_field(

                $this->adoptMethodType . '_foster_email',

                __('Foster Email', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->adoptMethodType . '_foster_email',

                    $this->pmpAdminInfo[$this->adoptMethodType . '_foster_email'],

                    'class' => $contactOptionsClass

                )

            );         



        if ( (array_key_exists('level_foster_link_' . $this->adoptMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_foster_link_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

                                                                    

            add_settings_field(

                $this->adoptMethodType . '_foster_link',

                __('Foster Application URL', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->adoptMethodType . '_foster_link',

                    $this->pmpAdminInfo[$this->adoptMethodType . '_foster_link'],

                    'class' => $contactOptionsClass

                )

            );



        /* Vanity Foster URL */

        if ( (array_key_exists('level_foster_vanity_link_' . $this->adoptMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_foster_vanity_link_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {

            $contactOptionsClass = 'pmp-contact-option';

        } else {                    

            $contactOptionsClass = 'pmp-option-disable';

        }

                                                                            

            add_settings_field(

                $this->adoptMethodType . '_foster_vanity_link',

                __('Foster Vanity Link', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->adoptMethodType . '_foster_vanity_link',

                    $this->pmpAdminInfo[$this->adoptMethodType . '_foster_vanity_link'],

                    'class' => $contactOptionsClass

                )

            );     

        

        if ( (array_key_exists('level_donate_link_' . $this->adoptMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_donate_link_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {  

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

                                                                        

            add_settings_field(

                $this->adoptMethodType . '_donation_link',

                __('Donation URL', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->adoptMethodType . '_donation_link',

                    $this->pmpAdminInfo[$this->adoptMethodType . '_donation_link'],

                    'class' => $contactOptionsClass

                )

            );

            

        /* Vanity Donation Link */

        if ( (array_key_exists('level_vanity_link_donate', $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_vanity_link_donate']) && (!empty($this->pmpLicenseKey)) ) { 

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

                                                                            

            add_settings_field(

                'donation_vanity_link',

                __('Donation Vanity Link', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    'donation_vanity_link',

                    $this->pmpAdminInfo['donation_vanity_link'],

                    'class' => $contactOptionsClass

                )

            );

           
        /* Vanity Volunteer Link */
        if ( (array_key_exists('level_vanity_link_volunteer', $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_vanity_link_volunteer']) && (!empty($this->pmpLicenseKey)) ) {  
            $contactOptionsClass = 'pmp-contact-option';
        } else {
            $contactOptionsClass = 'pmp-option-disable';
        }

            add_settings_field(
                'volunteer_vanity_link',
                __('Volunteer Vanity Link', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'input_element_callback'),
                'pet-match-pro-contact-options',
                'contact_settings_section',
                array('pet-match-pro-contact-options',
                    'volunteer_vanity_link',
                    $this->pmpAdminInfo['volunteer_vanity_link'],
                    'class' => $contactOptionsClass
                )
            );     

        /* RMB - Add Setting for Shelter Adoption Hours Link */
        if ( (array_key_exists('level_hours_link_' . $this->adoptMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_hours_link_' . $this->adoptMethodType]) && (!empty($this->pmpLicenseKey)) ) {  
            $contactOptionsClass = 'pmp-contact-option';
        } else {
            $contactOptionsClass = 'pmp-option-disable';
        }

            add_settings_field(
                $this->adoptMethodType . '_hours_link',
                __(ucfirst($this->adoptMethodType) . ' Hours Link', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'input_element_callback'),
                'pet-match-pro-contact-options',
                'contact_settings_section',
                array('pet-match-pro-contact-options',
                    $this->adoptMethodType . '_hours_link',
                    $this->pmpAdminInfo[$this->adoptMethodType . '_hours_link'],
                    'class' => $contactOptionsClass
                )
            );

		$enableKey = 'enable_phone_' . $this->foundMethodType . '_' . $this->partnerDir;
		$enableValue = 0;

		if ( (array_key_exists($enableKey, $pmpOptionLevelsContact)) ) {
			$enableValue = $pmpOptionLevelsContact[$enableKey];
		}

		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        if ( (array_key_exists('level_phone_' . $this->foundMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_phone_' . $this->foundMethodType]) && (!empty($this->pmpLicenseKey)) ) {  
	            $contactOptionsClass = 'pmp-contact-option';
	        } else {
	            $contactOptionsClass = 'pmp-option-disable';
	        }

	        /* RMB - Add Setting for Found Phone Number */                   

            add_settings_field(
                $this->foundMethodType . '_phone',
                __(ucfirst($this->foundMethodType) . ' Phone', 'pet-match-pro-plugin'),
                array($this->adminFunction, 'input_element_callback'),
                'pet-match-pro-contact-options',
                'contact_settings_section',
                    array('pet-match-pro-contact-options',
                    $this->foundMethodType . '_phone',
                    $this->pmpAdminInfo[$this->foundMethodType . '_phone'],
                    'class' => $contactOptionsClass
                )
            );
		}

		$enableKey = 'enable_email_' . $this->foundMethodType . '_' . $this->partnerDir;
		$enableValue = 0;

		if ( (array_key_exists($enableKey, $pmpOptionLevelsContact)) ) {
			$enableValue = $pmpOptionLevelsContact[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        /* RMB - Add Setting for Found Email Address */                   
	        if ( (array_key_exists('level_email_' . $this->foundMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_email_' . $this->foundMethodType]) && (!empty($this->pmpLicenseKey)) ) { 
	            $contactOptionsClass = 'pmp-contact-option';
	        } else {
	            $contactOptionsClass = 'pmp-option-disable';
	        }

            add_settings_field(

                $this->foundMethodType . '_email',

                __(ucfirst($this->foundMethodType) . ' Email', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->foundMethodType . '_email',

                    $this->pmpAdminInfo[$this->foundMethodType . '_email'],

                    'class' => $contactOptionsClass

                )

            );

		}



		$enableKey = 'enable_phone_' . $this->lostMethodType . '_' . $this->partnerDir;

		$enableValue = 0;

		if ( (array_key_exists($enableKey, $pmpOptionLevelsContact)) ) {

			$enableValue = $pmpOptionLevelsContact[$enableKey];

		}

		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		

		if ($enableValue == 1) {

	        /* RMB - Add Setting for Lost Phone Number */   

	        if ( (array_key_exists('level_phone_' . $this->lostMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_phone_' . $this->lostMethodType]) && (!empty($this->pmpLicenseKey)) ) {  

	            $contactOptionsClass = 'pmp-contact-option';

	        } else {

	            $contactOptionsClass = 'pmp-option-disable';

	        }

                                                                                                    

            add_settings_field(

                $this->lostMethodType . '_phone',

                __(ucfirst($this->lostMethodType) . ' Phone', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->lostMethodType . '_phone',

                    $this->pmpAdminInfo[$this->lostMethodType . '_phone'],

                    'class' => $contactOptionsClass

                )

            );

		}



		$enableKey = 'enable_email_' . $this->lostMethodType . '_' . $this->partnerDir;

		$enableValue = 0;

		if ( (array_key_exists($enableKey, $pmpOptionLevelsContact)) ) {

			$enableValue = $pmpOptionLevelsContact[$enableKey];

		}

		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		

		if ($enableValue == 1) {

	        /* RMB - Add Setting for Lost Email Address */    

	        if ( (array_key_exists('level_email_' . $this->lostMethodType, $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_email_' . $this->lostMethodType]) && (!empty($this->pmpLicenseKey)) ) {  

	            $contactOptionsClass = 'pmp-contact-option';

	        } else {

	            $contactOptionsClass = 'pmp-option-disable';

	        }

                                                                                                                       

            add_settings_field(

                $this->lostMethodType . '_email',

                __(ucfirst($this->lostMethodType) . ' Email', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    $this->lostMethodType . '_email',

                    $this->pmpAdminInfo[$this->lostMethodType . '_email'],

                    'class' => $contactOptionsClass

                )

            );

		}



        if ( (array_key_exists('level_sponsor_link', $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_sponsor_link']) && (!empty($this->pmpLicenseKey)) ) {  

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

                                                                        

            add_settings_field(

                'sponsor_link',

                __('Sponsorship URL', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    'sponsor_link',

                    $this->pmpAdminInfo['sponsor_link'],

                    'class' => $contactOptionsClass

                )

            );



        if ( (array_key_exists('level_sponsor_image', $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_sponsor_image']) && (!empty($this->pmpLicenseKey)) ) {  

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }

                                                                        

            add_settings_field(

                'sponsor_image',

                __('Sponsor Image', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    'sponsor_image',

                    $this->pmpAdminInfo['sponsor_image'],

                    'class' => $contactOptionsClass

                )

            );

            

        /* RMB - Add Setting for Website Support Email Address */  

        if ( (array_key_exists('level_email_website', $pmpOptionLevelsContact)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsContact['level_email_website']) && (!empty($this->pmpLicenseKey)) ) {                                                                                                                                          

            $contactOptionsClass = 'pmp-contact-option';

        } else {

            $contactOptionsClass = 'pmp-option-disable';

        }



            add_settings_field(

                'website_support_email',

                __('Website Support Email', 'pet-match-pro-plugin'),

                array($this->adminFunction, 'input_element_callback'),

                'pet-match-pro-contact-options',

                'contact_settings_section',

                array('pet-match-pro-contact-options',

                    'website_support_email',

                    $this->pmpAdminInfo['website_support_email'],

                    'class' => $contactOptionsClass

                )

            );



        register_setting(

            'pet-match-pro-contact-options',

            'pet-match-pro-contact-options'

        );

    }

    

    public function initialize_color_options() {

        add_settings_section(

            'color_settings_section',                        // ID used to identify this section and with which to register options

            __('', 'pet-match-pro-plugin'),                  // Title to be displayed on the administration page

            array($this, 'color_options_callback'),          // Callback used to render the description of the section

            'pet-match-pro-color-options'                    // Page on which to add this section of options

        );



        /* Get Field Visibility Levels by License Type */

        $levelsFile = 'pmp-option-levels-color.php';
        $requireFile = $this->adminPartialsDir . $levelsFile;
//        $requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $levelsFile;

        require($requireFile);

        //echo '<pre> ADMIN OPTION LEVEL VALUES '; print_r($pmpOptionLevelsColor); echo '</pre>';



        //echo 'PMP License Type ID = ' . $PMPLicenseTypeID . '<br>';

        //$PMPLicenseTypeID = 4; 



		/* Determine if Tab is Active */

		$colorsActive = false;		

		if (isset($_GET['subpage'])) {

			$activeTab = sanitize_text_field( $_GET['subpage'] );

			if ($activeTab == 'color_options') {

				$colorsActive = true;

			}

		}



		/* Get the Settings for Use in Formatting the Labels */

		if ( ($colorsActive == true) && (is_array($this->colorOptions)) && (count($this->colorOptions) > 0) ) {

			echo '<style id="pmp-color-options">';		

			foreach ($this->colorOptions as $key=>$value) {

				if (!empty($value)) {

					$classKey = str_replace("_", "-", $key);

					//echo 'Class Key is ' . $classKey . '<br>';

					$classValue = 'table.form-table tr.pmp-color-' . $classKey . ' th {color:' . $value . '; background-color: #dcdcdc;} ';

					//echo 'Class Value is ' $classValue . '<br>';

					echo $classValue;

				}

			}

			echo '</style> <!-- #pmp-color-options -->';			

		}
		
        /* RMB - Add Setting for Anchor Text */      		
        if ( (array_key_exists('level_link_text', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_link_text']) && (!empty($this->pmpLicenseKey)) ) { 
            $colorOptionsClass = 'pmp-color-link-text';
        } else {
            $colorOptionsClass = 'pmp-option-disable';
        }

        add_settings_field(
        	'link_text',
            __('Text Links', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'input_element_callback'),
            	'pet-match-pro-color-options',
                'color_settings_section',
                array('pet-match-pro-color-options',
                    'link_text',
                    $this->pmpAdminInfo['link_text'],
                    'class' => $colorOptionsClass
                )
        );

        if ( (array_key_exists('level_link_text_hover', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_link_text_hover']) && (!empty($this->pmpLicenseKey)) ) { 
            $colorOptionsClass = 'pmp-color-link-text-hover';
        } else {
            $colorOptionsClass = 'pmp-option-disable';
        }

        add_settings_field(
        	'link_text_hover',
            __('Text Links Hover', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'input_element_callback'),
            	'pet-match-pro-color-options',
                'color_settings_section',
                array('pet-match-pro-color-options',
                    'link_text_hover',
                    $this->pmpAdminInfo['link_text_hover'],
                    'class' => $colorOptionsClass
                )
        );






        /* RMB - Add Setting for Search Form Submit Button */      

        if ( (array_key_exists('level_search_submit_text', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_search_submit_text']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-search-submit-text';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }



        add_settings_field(

        	'search_submit_text',

            __('Search Submit Button Text', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_submit_text',

                    $this->pmpAdminInfo['search_submit_text'],

                    'class' => $colorOptionsClass

                )

        );



        if ( (array_key_exists('level_search_submit', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_search_submit']) && (!empty($this->pmpLicenseKey)) ) { 
            $colorOptionsClass1 = 'pmp-color-search-submit-background';
            $colorOptionsClass2 = 'pmp-color-search-submit-border';
        } else {
            $colorOptionsClass1 = 'pmp-option-disable';
            $colorOptionsClass2 = 'pmp-option-disable';
        }        

                    

        add_settings_field(

        	'search_submit_background',

            __('Search Submit Button Background', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_submit_background',

                    $this->pmpAdminInfo['search_submit_background'],

                    'class' => $colorOptionsClass1

                )

        );

        

        add_settings_field(

        	'search_submit_border',

            __('Search Submit Button Border', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_submit_border',

                    $this->pmpAdminInfo['search_submit_border'],

                    'class' => $colorOptionsClass2

                )

        );

        

        if ( (array_key_exists('level_search_submit_hover_text', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_search_submit_hover_text']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-search-submit-hover-text';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }



        add_settings_field(

        	'search_submit_hover_text',

            __('Search Submit Button Hover Text', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_submit_hover_text',

                    $this->pmpAdminInfo['search_submit_hover_text'],

                    'class' => $colorOptionsClass

                )

        );



        if ( (array_key_exists('level_search_submit_hover', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_search_submit_hover']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass1 = 'pmp-color-search-submit-hover-background';

            $colorOptionsClass2 = 'pmp-color-search-submit-hover-border';

        } else {
            $colorOptionsClass1 = 'pmp-option-disable';
            $colorOptionsClass2 = 'pmp-option-disable';
        }

                    

        add_settings_field(

        	'search_submit_hover_background',

            __('Search Submit Button Hover Background', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_submit_hover_background',

                    $this->pmpAdminInfo['search_submit_hover_background'],

                    'class' => $colorOptionsClass1

                )

        );



        add_settings_field(

        	'search_submit_hover_border',

            __('Search Submit Button Hover Border', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_submit_hover_border',

                    $this->pmpAdminInfo['search_submit_hover_border'],

                    'class' => $colorOptionsClass2

                )

        );

        

        /* RMB - Add Setting for Search Title */      

        if ( (array_key_exists('level_search_title', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_search_title']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-search-title';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'search_title',

            __('Search Title', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_title',

                    $this->pmpAdminInfo['search_title'],

                    'class' => $colorOptionsClass

                )

        );

        

        /* RMB - Add Setting for Search Name Result */      

        if ( (array_key_exists('level_search_result_name', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_search_result_name']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-search-result-name';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'search_result_name',

            __('Search Result Name', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_result_name',

                    $this->pmpAdminInfo['search_result_name'],

                    'class' => $colorOptionsClass

                )

        );



        if ( (array_key_exists('level_search_result_name_hover', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_search_result_name_hover']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-search-result-name-hover';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

        

        add_settings_field(

        	'search_result_name_hover',

            __('Search Result Name Hover', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_result_name_hover',

                    $this->pmpAdminInfo['search_result_name_hover'],

                    'class' => $colorOptionsClass

                )

        );

        

        /* RMB - Add Setting for Search Result Label */      

        if ( (array_key_exists('level_search_result_label', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_search_result_label']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-search-result-label';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'search_result_label',

            __('Search Result Label', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_result_label',

                    $this->pmpAdminInfo['search_result_label'],

                    'class' => $colorOptionsClass

                )

        );



        /* RMB - Add Setting for Search Result Value */      

        if ( (array_key_exists('level_search_result', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_search_result']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-search-result';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'search_result',

            __('Search Result Value', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'search_result',

                    $this->pmpAdminInfo['search_result'],

                    'class' => $colorOptionsClass

                )

        );



        /* RMB - Add Setting for Detail Result Buttons */      

        if ( (array_key_exists('level_detail_result_button_text', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_result_button_text']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-detail-result-button-text';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }



        add_settings_field(

        	'detail_result_button_text',

            __('Detail Buttons Text', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_result_button_text',

                    $this->pmpAdminInfo['detail_result_button_text'],

                    'class' => $colorOptionsClass

                )

        );



        if ( (array_key_exists('level_detail_result_button', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_result_button']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass1 = 'pmp-color-detail-result-button-background';

            $colorOptionsClass2 = 'pmp-color-detail-result-button-border';

        } else {
            $colorOptionsClass1 = 'pmp-option-disable';
            $colorOptionsClass2 = 'pmp-option-disable';
        }

                    

        add_settings_field(

        	'detail_result_button_background',

            __('Detail Buttons Background', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_result_button_background',

                    $this->pmpAdminInfo['detail_result_button_background'],

                    'class' => $colorOptionsClass1

                )

        );

        

        add_settings_field(

        	'detail_result_button_border',

            __('Detail Buttons Border', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_result_button_border',

                    $this->pmpAdminInfo['detail_result_button_border'],

                    'class' => $colorOptionsClass2

                )

        );

        

        if ( (array_key_exists('level_detail_result_button_hover_text', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_result_button_hover_text']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-detail-result-button-hover-text';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'detail_result_button_hover_text',

            __('Detail Buttons Hover Text', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_result_button_hover_text',

                    $this->pmpAdminInfo['detail_result_button_hover_text'],

                    'class' => $colorOptionsClass

                )

        );



        if ( (array_key_exists('level_detail_result_button_hover', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_result_button_hover']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass1 = 'pmp-color-detail-result-button-hover-background';

            $colorOptionsClass2 = 'pmp-color-detail-result-button-hover-border';

        } else {
            $colorOptionsClass1 = 'pmp-option-disable';
            $colorOptionsClass2 = 'pmp-option-disable';
        }

                    

        add_settings_field(

        	'detail_result_button_hover_background',

            __('Detail Buttons Hover Background', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_result_button_hover_background',

                    $this->pmpAdminInfo['detail_result_button_hover_background'],

                    'class' => $colorOptionsClass1

                )

        );



        add_settings_field(

        	'detail_result_button_hover_border',

            __('Detail Buttons Hover Border', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_result_button_hover_border',

                    $this->pmpAdminInfo['detail_result_button_hover_border'],

                    'class' => $colorOptionsClass2

                )

        );

        

        if ( (array_key_exists('level_detail_result_title', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_result_title']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-detail-result-title';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'detail_result_title',

            __('Detail Result Title', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_result_title',

                    $this->pmpAdminInfo['detail_result_title'],

                    'class' => $colorOptionsClass

                )

        );

        

        if ( (array_key_exists('level_detail_result_label', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_result_label']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-detail-result-label';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'detail_result_label',

            __('Detail Result Label', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_result_label',

                    $this->pmpAdminInfo['detail_result_label'],

                    'class' => $colorOptionsClass

                )

        );

        

        if ( (array_key_exists('level_detail_result', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_result']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-detail-result';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'detail_result',

            __('Detail Result', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_result',

                    $this->pmpAdminInfo['detail_result'],

                    'class' => $colorOptionsClass

                )

        );

        

        if ( (array_key_exists('level_detail_description_label', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_description_label']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-detail-description-label';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'detail_description_label',

            __('Detail Result Description Label', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_description_label',

                    $this->pmpAdminInfo['detail_description_label'],

                    'class' => $colorOptionsClass

                )

        );

        

        if ( (array_key_exists('level_detail_description', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_description']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-detail-description';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'detail_description',

            __('Detail Result Description', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_description',

                    $this->pmpAdminInfo['detail_description'],

                    'class' => $colorOptionsClass

                )

        );



        if ( (array_key_exists('level_detail_poster_heading', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_poster_heading']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-detail-poster-heading';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'detail_poster_heading',

            __('Detail Poster Heading', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_poster_heading',

                    $this->pmpAdminInfo['detail_poster_heading'],

                    'class' => $colorOptionsClass

                )

        );



        if ( (array_key_exists('level_detail_poster_qr_code', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_poster_qr_code']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-detail-poster-qr-code';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'detail_poster_qr_code',

            __('Detail Poster QR Code', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_poster_qr_code',

                    $this->pmpAdminInfo['detail_poster_qr_code'],

                    'class' => $colorOptionsClass

                )

        );



        if ( (array_key_exists('level_detail_poster_footing', $pmpOptionLevelsColor)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsColor['level_detail_poster_footing']) && (!empty($this->pmpLicenseKey)) ) { 

            $colorOptionsClass = 'pmp-color-detail-poster-footing';

        } else {

            $colorOptionsClass = 'pmp-option-disable';

        }

                    

        add_settings_field(

        	'detail_poster_footing',

            __('Detail Poster Footing', 'pet-match-pro-plugin'),

            array($this->adminFunction, 'input_element_callback'),

            	'pet-match-pro-color-options',

                'color_settings_section',

                array('pet-match-pro-color-options',

                    'detail_poster_footing',

                    $this->pmpAdminInfo['detail_poster_footing'],

                    'class' => $colorOptionsClass

                )

        );



        register_setting(

            'pet-match-pro-color-options',

            'pet-match-pro-color-options'

        );

    }

    

    public function initialize_API_license_form() {

    }



    public function initialize_instructions() {
        add_settings_section(
            'instruction_settings_section',
            __('', 'pet-match-pro-plugin'),
            array($this, 'instructions_callback'),
            'pet-match-pro-instructions'
        );       

        add_settings_field(
            'instruction-installation-title',
            __('Installation Instructions', 'pet-match-pro-plugin'),
            array($this, 'instruction_title'),
            'pet-match-pro-instructions',
            'instruction_settings_section'
            );

        /* Get Installation Instructions */
        $instructionFile = 'pmp-instructions-installation.html';
        $instructionDir = $this->adminPartialsDir;
//        $instructionDir = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/';
        $requireFile =  $instructionDir . $instructionFile;
        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
            $installationSteps = $this->get_file_data($requireFile);
            $installationID = 'pmp_instruction_installation';
        } else {
            $installationSteps = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
            $installationID = 'pmp-error-message';
        }

        $emptyLabel = '';
        add_settings_field(
            'instruction-installation',
            __($emptyLabel, 'pet-match-pro-plugin'),
            array($this->adminFunction, 'html_callback'),
            'pet-match-pro-instructions',
            'instruction_settings_section',
            array(
                'pet-match-pro-instructions',
                $installationID,
                $installationSteps
            )
        );

        /* Get Instruction Visibility Levels */
        $levelsFile = 'pmp-option-levels-instructions.php';
        $requireFile = $this->adminPartialsDir . $levelsFile;
//        $requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $levelsFile;
        require($requireFile);

		$enableKey = 'enable_pmp_instruction_search_' . $this->partnerDir;
		$enableValue = 0;		
		if ( (array_key_exists($enableKey, $pmpOptionLevelsInstructions)) ) {
			$enableValue = $pmpOptionLevelsInstructions[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        add_settings_field(
	            'instruction-search-title',
	            __('Animal Search Shortcode', 'pet-match-pro-plugin'),
	            array($this, 'instruction_title'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section'
	            );

	        /* Get Search Shortcode Instructions */
	        $instructionFile = 'pmp-instructions-search.html';
	        $instructionDir = $this->adminPartialsDir . $this->partnerDir . '/';
//	        $instructionDir = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $this->partnerDir . '/';
	        $requireFile =  $instructionDir . $instructionFile;
	        //echo 'Search File = ' . $requireFile . '<br>';
	        if ( (is_dir($instructionDir)) && (is_file($requireFile)) && (file_exists($requireFile)) ) {
	            $searchAnimal = $this->get_file_data($requireFile);
	            $installationID = 'pmp_instruction_search';
	        } else {
	            $searchAnimal = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
	            $installationID = 'pmp-error-message';
	        }
       
	        add_settings_field(
	            'instruction-search',
	            __($emptyLabel, 'pet-match-pro-plugin'),
	            array($this->adminFunction, 'html_callback'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section',
	            array(
	                'pet-match-pro-instructions',
	                $installationID,
	                $searchAnimal
	            )
	        );
	    }

		$enableKey = 'enable_pmp_instruction_details_' . $this->partnerDir;
		$enableValue = 0;		
		if ( (array_key_exists($enableKey, $pmpOptionLevelsInstructions)) ) {
			$enableValue = $pmpOptionLevelsInstructions[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        add_settings_field(
	            'instruction-details-title',
	            __('Animal Details Shortcode', 'pet-match-pro-plugin'),
	            array($this, 'instruction_title'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section'
	            );
	
	        /* Get Animal Detail Shortcode Instructions */
	        $instructionFile = 'pmp-instructions-details.html';
	        $requireFile =  $instructionDir . $instructionFile;
	        //echo 'Animal Detail File = ' . $requireFile . '<br>';
	        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
	            $detailAnimal = $this->get_file_data($requireFile);
	            $installationID = 'pmp_instruction_detail_adopt';
	        } else {
	            $detailAnimal = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
	            $installationID = 'pmp-error-message';
	        }
	
	        add_settings_field(
	            'instruction-details',
	            __($emptyLabel, 'pet-match-pro-plugin'),
	            array($this->adminFunction, 'html_callback'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section',
	            array(
	                'pet-match-pro-instructions',
	                $installationID,
	                $detailAnimal
	            )
	        );
	    }

		$enableKey = 'enable_pmp_instruction_search_' . $this->adoptMethodType . '_' . $this->partnerDir;
		$enableValue = 0;		
		if ( (array_key_exists($enableKey, $pmpOptionLevelsInstructions)) ) {
			$enableValue = $pmpOptionLevelsInstructions[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        add_settings_field(
	            'instruction-search-' . $this->adoptMethodType . '-title',
	            __(ucfirst($this->adoptMethodType) . ' Search Shortcode', 'pet-match-pro-plugin'),
	            array($this, 'instruction_title'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section'
	            );

	        /* Get Adoptable Search Shortcode Instructions */
	        $instructionFile = 'pmp-instructions-search-' . $this->adoptMethodType . '.html';
	        $instructionDir = $this->adminPartialsDir . $this->partnerDir . '/';
//	        $instructionDir = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $this->partnerDir . '/';
	        $requireFile =  $instructionDir . $instructionFile;
	        //echo 'Adopt Search File = ' . $requireFile . '<br>';
	        if ( (is_dir($instructionDir)) && (is_file($requireFile)) && (file_exists($requireFile)) ) {
	            $searchAdopt = $this->get_file_data($requireFile);
	            $installationID = 'pmp_instruction_search_' . $this->adoptMethodType;
	        } else {
	            $searchAdopt = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
	            $installationID = 'pmp-error-message';
	        }
       
	        add_settings_field(
	            'instruction-search-' . $this->adoptMethodType,
	            __($emptyLabel, 'pet-match-pro-plugin'),
	            array($this->adminFunction, 'html_callback'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section',
	            array(
	                'pet-match-pro-instructions',
	                $installationID,
	                $searchAdopt
	            )
	        );
	    }
        
		$enableKey = 'enable_pmp_instruction_detail_' . $this->adoptMethodType . '_' . $this->partnerDir;
		$enableValue = 0;		
		if ( (array_key_exists($enableKey, $pmpOptionLevelsInstructions)) ) {
			$enableValue = $pmpOptionLevelsInstructions[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        add_settings_field(
	            'instruction-detail-' . $this->adoptMethodType . '-title',
	            __(ucfirst($this->adoptMethodType) . ' Details Shortcode', 'pet-match-pro-plugin'),
	            array($this, 'instruction_title'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section'
	            );
	
	        /* Get Adoptable Detail Shortcode Instructions */
	        $instructionFile = 'pmp-instructions-details-' . $this->adoptMethodType . '.html';
	        $requireFile =  $instructionDir . $instructionFile;
	        //echo 'Adoption Details File = ' . $requireFile . '<br>';
	        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
	            $detailAdopt = $this->get_file_data($requireFile);
	            $installationID = 'pmp_instruction_detail_' . $this->adoptMethodType;
	        } else {
	            $detailAdopt = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
	            $installationID = 'pmp-error-message';
	        }
	
	        add_settings_field(
	            'instruction-detail-' . $this->adoptMethodType,
	            __($emptyLabel, 'pet-match-pro-plugin'),
	            array($this->adminFunction, 'html_callback'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section',
	            array(
	                'pet-match-pro-instructions',
	                $installationID,
	                $detailAdopt
	            )
	        );
	    }

		$enableKey = 'enable_pmp_instruction_featured_' . $this->adoptMethodType . '_' . $this->partnerDir;
		$enableValue = 0;		
		if ( (array_key_exists($enableKey, $pmpOptionLevelsInstructions)) ) {
			$enableValue = $pmpOptionLevelsInstructions[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        add_settings_field(
	            'instruction-featured-' . $this->adoptMethodType . '-title',
	            __('Adoption Featured Shortcode<br><span class="pmp-text-75"><span class="pmp-green">Paid Subscription</span> Required</span>', 'pet-match-pro-plugin'),
	            array($this, 'instruction_title'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section'
	            );
	
	        /* Get Featured Adoptable Shortcode Instructions */
	        $instructionFile = 'pmp-instructions-featured-' . $this->adoptMethodType . '.html';
	        $requireFile =  $instructionDir . $instructionFile;
	        //echo 'Adoption Featured File = ' . $requireFile . '<br>';
	        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
	            $featuredAdopt = $this->get_file_data($requireFile);
	            $installationID = 'pmp_instruction_featured_' . $this->adoptMethodType;
	        } else {
	            $featuredAdopt = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
	            $installationID = 'pmp-error-message';
	        }
	
	        add_settings_field(
	            'instruction-featured-' . $this->adoptMethodType,
	            __($emptyLabel, 'pet-match-pro-plugin'),
	            array($this->adminFunction, 'html_callback'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section',
	            array(
	                'pet-match-pro-instructions',
	                $installationID,
	                $featuredAdopt
	            )
	        );
	    }

		$enableKey = 'enable_pmp_instruction_search_' . $this->foundMethodType . '_' . $this->partnerDir;
		$enableValue = 0;				
		if ( (array_key_exists($enableKey, $pmpOptionLevelsInstructions)) ) {
			$enableValue = $pmpOptionLevelsInstructions[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';
		
		if ($enableValue == 1) {
	        /* Found Shortcodes Instructions */
	        add_settings_field(
	            'instruction-search-' . $this->foundMethodType . '-title',
	            __('Found Search Shortcode<br><span class="pmp-text-75"><span class="pmp-green">Paid Subscription</span> Required</span>', 'pet-match-pro-plugin'),
	            array($this, 'instruction_title'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section'
	            );
	
	        /* Get Found Search Shortcode Instructions */
	        $instructionFile = 'pmp-instructions-search-' . $this->foundMethodType . '.html';
	        $requireFile =  $instructionDir . $instructionFile;
	        //echo 'Found Search File = ' . $requireFile . '<br>';
	        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
	            $searchFound =  $this->get_file_data($requireFile);
	            $installationID = 'pmp_instruction_search_' . $this->foundMethodType;
	        } else {
	            $searchFound = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
	            $installationID = 'pmp-error-message';
	        }
        
	        add_settings_field(
	            'instruction-search-' . $this->foundMethodType,
	            __($emptyLabel, 'pet-match-pro-plugin'),
	            array($this->adminFunction, 'html_callback'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section',
	            array(
	                'pet-match-pro-instructions',
	                $installationID,
	                $searchFound
	            )
	        );
		}

		$enableKey = 'enable_pmp_instruction_detail_' . $this->foundMethodType . '_' . $this->partnerDir;
		$enableValue = 0;				
		if ( (array_key_exists($enableKey, $pmpOptionLevelsInstructions)) ) {
			$enableValue = $pmpOptionLevelsInstructions[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';
		
		if ($enableValue == 1) {
	        add_settings_field(
	            'instruction-detail-' . $this->foundMethodType . '-title',
	            __('Found Details Shortcode<br><span class="pmp-text-75"><span class="pmp-green">Paid Subscription</span> Required</span>', 'pet-match-pro-plugin'),
	            array($this, 'instruction_title'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section'
	            );

	        /* Get Found Detail Shortcode Instructions */
	        $instructionFile = 'pmp-instructions-details-' . $this->foundMethodType . '.html';
	        $requireFile =  $instructionDir . $instructionFile;
	        //echo 'Found Details File = ' . $requireFile . '<br>';
	        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
	            $detailFound =  $this->get_file_data($requireFile);
	            $installationID = 'pmp_instruction_detail_' . $this->foundMethodType;
	        } else {
	            $detailFound = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
	            $installationID = 'pmp-error-message';
	        }

	        add_settings_field(
	            'instruction-detail-' . $this->foundMethodType,
	            __($emptyLabel, 'pet-match-pro-plugin'),
	            array($this->adminFunction, 'html_callback'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section',
	            array(
	                'pet-match-pro-instructions',
	                $installationID,
	                $detailFound
	            )
	        );
	   	}

		$enableKey = 'enable_pmp_instruction_search_' . $this->lostMethodType . '_' . $this->partnerDir;
		$enableValue = 0;				
		if ( (array_key_exists($enableKey, $pmpOptionLevelsInstructions)) ) {
			$enableValue = $pmpOptionLevelsInstructions[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';
		
		if ($enableValue == 1) {
	        /* Lost Shortcodes Instructions */
	        add_settings_field(
	            'instruction-search-' . $this->lostMethodType . '-title',
	            __('Lost Search Shortcode<br><span class="pmp-text-75"><span class="pmp-green">Paid Subscription</span> Required</span>', 'pet-match-pro-plugin'),
	            array($this, 'instruction_title'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section'
	            );
	
	        /* Get Lost Search Shortcode Instructions */
	        $instructionFile = 'pmp-instructions-search-' . $this->lostMethodType . '.html';
	        $requireFile =  $instructionDir . $instructionFile;
	        //echo 'Lost Search File = ' . $requireFile . '<br>';
	        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
	            $searchLost =  $this->get_file_data($requireFile);
	            $installationID = 'pmp_instruction_search_' . $this->lostMethodType;
	        } else {
	            $searchLost = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
	            $installationID = 'pmp-error-message';
	        }
        
	        add_settings_field(
	            'instruction-search-' . $this->lostMethodType,
	            __($emptyLabel, 'pet-match-pro-plugin'),
	            array($this->adminFunction, 'html_callback'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section',
	            array(
	                'pet-match-pro-instructions',
	                $installationID,
	                $searchLost
	            )
	        );
		}

		$enableKey = 'enable_pmp_instruction_detail_' . $this->lostMethodType . '_' . $this->partnerDir;
		$enableValue = 0;				
		if ( (array_key_exists($enableKey, $pmpOptionLevelsInstructions)) ) {
			$enableValue = $pmpOptionLevelsInstructions[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        add_settings_field(
	            'instruction-detail-' . $this->lostMethodType . '-title',
	            __('Lost Details Shortcode<br><span class="pmp-text-75"><span class="pmp-green">Paid Subscription</span> Required</span>', 'pet-match-pro-plugin'),
	            array($this, 'instruction_title'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section'
	            );
	
	        /* Get Lost Detail Shortcode Instructions */
	        $instructionFile = 'pmp-instructions-details-' . $this->lostMethodType . '.html';
	        $requireFile =  $instructionDir . $instructionFile;
	        //echo 'Lost Details File = ' . $requireFile . '<br>';
	        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
	            $detailLost = $this->get_file_data($requireFile);
	            $installationID = 'pmp_instruction_detail_' . $this->lostMethodType;
	        } else {
	            $detailLost = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
	            $installationID = 'pmp-error-message';
	        }
        
	        add_settings_field(
	            'instruction-detail-' . $this->lostMethodType,
	            __($emptyLabel, 'pet-match-pro-plugin'),
	            array($this->adminFunction, 'html_callback'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section',
	            array(
	                'pet-match-pro-instructions',
	                $installationID,
	                $detailLost
	            )
	        );
	 	}

		$enableKey = 'enable_pmp_instruction_search_' . $this->lostMethodType . '_' . $this->foundMethodType . '_' . $this->partnerDir;
		$enableValue = 0;				
		if ( (array_key_exists($enableKey, $pmpOptionLevelsInstructions)) ) {
			$enableValue = $pmpOptionLevelsInstructions[$enableKey];
		}
		//echo 'Enable Key ' . $enableKey . ' = ' . $enableValue . '<br>';

		if ($enableValue == 1) {
	        /* Lost & Found Search Shortcode Instructions */
	        add_settings_field(
	            'instruction-search-' . $this->lostMethodType . '-' . $this->foundMethodType . '-title',
	            __(ucfirst($this->lostMethodType) . ' & ' . $this->foundMethodType . ' Search Shortcode<br><span class="pmp-text-75"><span class="pmp-green">Paid Subscription</span> Required</span>', 'pet-match-pro-plugin'),
	            array($this, 'instruction_title'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section'
	            );

	        /* Get Lost & Found Search Shortcode Instructions */
	        $instructionFile = 'pmp-instructions-search-' . $this->lostMethodType . '-' . $this->foundMethodType . '.html';
	        $requireFile =  $instructionDir . $instructionFile;
	        //echo 'Lost and Found Search File = ' . $requireFile . '<br>';
	        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
	            $searchLostFound = $this->get_file_data($requireFile);
	            $installationID = 'pmp_instruction_search_' . $this->lostMethodType . '_' . $this->foundMethodType;
	        } else {
	            $searchLostFound = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
	            $installationID = 'pmp-error-message';
	        }
        
	        add_settings_field(
	            'instruction-search-' . $this->lostMethodType . '-' . $this->foundMethodType,
	            __($emptyLabel, 'pet-match-pro-plugin'),
	            array($this->adminFunction, 'html_callback'),
	            'pet-match-pro-instructions',
	            'instruction_settings_section',
	            array(
	                'pet-match-pro-instructions',
	                $installationID,
	                $searchLostFound
	            )
	        );
		}
                        
        /* Additional Shortcode Instructions */
        add_settings_field(
            'instruction-detail-title',
            __('Animal Detail Shortcode', 'pet-match-pro-plugin'),
            array($this, 'instruction_title'),
            'pet-match-pro-instructions',
            'instruction_settings_section'
            );

        /* Get Animal Details Shortcode Instructions */
        $instructionFile = 'pmp-instructions-animal-detail.html';
        $requireFile =  $instructionDir . $instructionFile;
        //echo 'Animal Detail File = ' . $requireFile . '<br>';
        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
            $detailAnimal = $this->get_file_data($requireFile);
            $installationID = 'pmp_instruction_animal_details';
        } else {
            $detailAnimal = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
            $installationID = 'pmp-error-message';
        }
        
        add_settings_field(
            'instruction-detail',
            __($emptyLabel, 'pet-match-pro-plugin'),
            array($this->adminFunction, 'html_callback'),
            'pet-match-pro-instructions',
            'instruction_settings_section',
            array(
                'pet-match-pro-instructions',
                $installationID,
                $detailAnimal
            )
        );

        add_settings_field(
            'instruction-admin-title',
            __('Admin Settings Shortcode', 'pet-match-pro-plugin'),
            array($this, 'instruction_title'),
            'pet-match-pro-instructions',
            'instruction_settings_section'
            );

        /* Get Admin Settings Shortcode Instructions */
        $instructionFile = 'pmp-instructions-admin-settings.html';
        $instructionDir = $this->adminPartialsDir;                
//        $instructionDir = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/';                
        $requireFile =  $instructionDir . $instructionFile;
        //echo 'Admin Settings File = ' . $requireFile . '<br>';
        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
            $adminSettings = $this->get_file_data($requireFile);
            $installationID = 'pmp_instruction_admin_settings';
        } else {
            $adminSettings = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
            $installationID = 'pmp-error-message';
        }

        add_settings_field(
            'instruction-admin',
            __($emptyLabel, 'pet-match-pro-plugin'),
            array($this->adminFunction, 'html_callback'),
            'pet-match-pro-instructions',
            'instruction_settings_section',
            array(
                'pet-match-pro-instructions',
                $installationID,
                $adminSettings
            )
        );

        add_settings_field(
            'instruction-social-title',
            __('Social Sharing Shortcode<br><span class="pmp-text-75"><span class="pmp-green">Paid Subscription</span> Required</span>', 'pet-match-pro-plugin'),
            array($this, 'instruction_title'),
            'pet-match-pro-instructions',
            'instruction_settings_section'
            );

        /* Get Social Sharing Shortcode Instructions */
        $instructionFile = 'pmp-instructions-social-sharing.html';
        $requireFile =  $instructionDir . $instructionFile;
        //echo 'Social Sharing Instructions File = ' . $requireFile . '<br>';
        if ( (is_dir($instructionDir)) && (is_file($requireFile)) ) {
            $socialSharing = $this->get_file_data($requireFile);
            $installationID = 'pmp_instruction_admin_settings';
        } else {
            $socialSharing = '<p><span class="pmp-error">ERROR: Unable to find the file ' . $instructionFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
            $installationID = 'pmp-error-message';
        }

        add_settings_field(
            'instruction-social',
            __($emptyLabel, 'pet-match-pro-plugin'),
            array($this->adminFunction, 'html_callback'),
            'pet-match-pro-instructions',
            'instruction_settings_section',
            array(
                'pet-match-pro-instructions',
                $installationID,
                $socialSharing            )
        );

        register_setting(
            'pet-match-pro-instructions',
            'pet-match-pro-instructions'
        );
    }       

    public function initialize_license_form() {
        if (false == get_option('pet-match-pro-license')) {
            //$default_array = $this->default_API_license_options();
            //update_option( 'pet-match-pro-license', $default_array );
        } // end if
        require_once plugin_dir_path(dirname(__FILE__)) . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/activate_license_form.php';
    }

    public function initialize_deactivate_form() {
        $isValid = $this->responseObj->is_valid;
        $license_title = $this->responseObj->license_title;
        $license_key = $this->responseObj->license_key;
        $license_param = $this->responseObj->lic_param;
        require_once plugin_dir_path(dirname(__FILE__)) . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/deactivate_license_form.php';
    }

    private function get_file_data($file_path){
        // Attempt to get access to the filesystem
        if ( WP_Filesystem() ) {
            global $wp_filesystem;
                        
            // Check if the file exists
            if ( $wp_filesystem->exists( $file_path ) ) {
                $file_contents = $wp_filesystem->get_contents( $file_path );
                if ( $file_contents !== false ) {
                   return $file_contents;
                } else {
                    echo 'Failed to read file contents.';
                }
            } else {
                echo 'File does not exist.';
            }
        } 
    }

    public function validate_form_input($input) {
        // Create our array for storing the validated options
        $output = [];
        // Loop through each of the incoming options
        foreach ($input as $key => $value) {
            // Check to see if the current option has a value. If so, process it.
            if (isset($input[$key])) {
                // Strip all HTML and PHP tags and properly handle quoted strings
                $output[$key] = strip_tags(stripslashes($input[$key]));
            } // end if
        } // end foreach
        // Return the array processing any additional functions filtered by this action
        return apply_filters('validate_form_input', $output, $input);
    } // end validate_form_input

    function action_activate_license() {
        check_admin_referer('el-license');
        $licenseKey = !empty(sanitize_text_field($_POST['el_license_key'])) ? sanitize_text_field($_POST['el_license_key'] ) : "";
        $licenseEmail = !empty(sanitize_text_field($_POST['el_license_email']) ) ? sanitize_text_field($_POST['el_license_email'] ): "";
        update_option("PMP_lic_Key", $licenseKey)     || add_option("PMP_lic_Key", $licenseKey);
        update_option("PMP_lic_email", $licenseEmail) || add_option("PMP_lic_email", $licenseEmail);
        update_option('_site_transient_update_plugins', '');
        wp_safe_redirect(admin_url('admin.php?page=pet-match-pro-license-options'));
    }

    function action_deactivate_license() {
        check_admin_referer('el-license');
        $message = "";
        if (PetMatchProBase::RemoveLicenseKey(__FILE__, $message)) {
            update_option("PMP_lic_Key", "") || add_option("PMP_lic_Key", "");
            update_option('_site_transient_update_plugins', '');
        }
        wp_safe_redirect(admin_url('admin.php?page=pet-match-pro-license-options'));
    }
}