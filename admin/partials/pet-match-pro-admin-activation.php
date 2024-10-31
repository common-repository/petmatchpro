<?php
    $licenseCode=get_option("PMP_lic_Key","");//any option name, by which name have saved the license code into option table
    $licenseEmail=get_option( "PMP_lic_email","");//any option name, by which name have saved the license email into option table

    PetMatchProBase::addOnDelete(function(){
       delete_option("PMP_lic_Key");
    });

	if(PetMatchProBase::CheckWPPlugin($licenseCode,$licenseEmail,$error,$responseObj,__FILE__)){
		/*
		 * This property you will get on success
		 * write your plugin or theme code in this block
		 *
		$responseObj->is_valid; //true
		$responseObj->license_title; // title of the license
		$responseObj->license_key; //license code
		$responseObj->expire_date; // date or 'No expiry' string
		$responseObj->support_end; //date or 'No support' string
		$responseObj->msg; //success message
		*/

		/*
		 * Best solution will be
		 * Write your plugin's "add_action" or "add_filter" code in this block and hide this code in anywhere
		 */
            $licenseKey=get_option("PMP_lic_Key","");
            $liceEmail=get_option( "PMP_lic_email","");
            include_once plugin_dir_path( dirname( __FILE__ ) ) .  constant('PARTIALS_DIR') . '/' . constant('LICENSE_DIR') . '/license-form-active.php';
	} else {
		/*
		 * On error/failed you will get the error message on $error variable
		 * write the logic for license input form
		 */
		if(!empty($licenseKey) && !empty($this->licenseMessage)) {
	        $this->showMessage=true;
        }
        update_option("PMP_lic_Key","") || add_option("PMP_lic_Key","");
        add_action( 'PMP_activate_license', 'action_activate_license'  );
        include_once plugin_dir_path( dirname( __FILE__ ) ) .  constant('PARTIALS_DIR') . '/' . constant('LICENSE_DIR') . '/license-form.php';

		/*
		 * Best solution will be
		 * Write your plugin's menu code with license input form option page
		 * example :
		  add_menu_page (  "PetMatchPro", "PetMatch Pro", 'activate_plugins', "pet-match-pro", "name_of_licesecode_inputform_function", "your plugin or theme icon url");
		 */
	}

  	/**
	 * Initializes the theme's input example by registering the Sections,
	 * Fields, and Settings. This particular group of options is used to demonstration
	 * validation and sanitization.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */

	function initialize_api_license_info() {
		//delete_option('wppb_demo_input_examples');
		if( false == get_option( 'wppb_demo_input_examples' ) ) {
			$default_array = $this->default_input_options();
			update_option( 'wppb_demo_input_examples', $default_array );
		} // end if

		add_settings_section(
			'input_examples_section',
			__( 'Input Examples', 'pet-match-pro-plugin' ),
			array( $this, 'input_examples_callback'),
			'wppb_demo_input_examples'
		);

		add_settings_field(
			'Input Element',
			__( 'Input Element', 'pet-match-pro-plugin' ),
			array( $this, 'input_element_callback'),
			'wppb_demo_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Textarea Element',
			__( 'Textarea Element', 'pet-match-pro-plugin' ),
			array( $this, 'textarea_element_callback'),
			'wppb_demo_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Checkbox Element',
			__( 'Checkbox Element', 'pet-match-pro-plugin' ),
			array( $this, 'checkbox_element_callback'),
			'wppb_demo_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Radio Button Elements',
			__( 'Radio Button Elements', 'pet-match-pro-plugin' ),
			array( $this, 'radio_element_callback'),
			'wppb_demo_input_examples',
			'input_examples_section'
		);

		add_settings_field(
			'Select Element',
			__( 'Select Element', 'pet-match-pro-plugin' ),
			array( $this, 'select_element_callback'),
			'wppb_demo_input_examples',
			'input_examples_section'
		);

		register_setting(
			'wppb_demo_input_examples',
			'wppb_demo_input_examples',
			array( $this, 'validate_input_examples')
		);
	}