<?php

class Pet_Match_Pro_PP_Options {

	public $api_activated;
    private $adminFunction;
    private $animalDetailFunction; 
	public $pmpPPAdminInfo;			/* Admin Hover Help Text */
	public $PMPLicenseTypeID;	 	
	public $pmpLicenseKey;
	
    private $partialsDir;
    private $partialsAdminDir;

    public function __construct( $api_activated ) {
        global $pmpAdminInfo;			/* Admin Hover Help Text */
    
        $this->api_activated = $api_activated;	

		/* Include Class Defining Functions for Processing Animal Searches */
		$functionsFile = 'class-pet-match-pro-functions.php';
		require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/' . $functionsFile;
		$this->adminFunction = new PetMatchProFunctions();  

		/* Include Class Defining Animal Detail Functions */
		$detailsFile = 'class-pet-match-pro-pp-detail-functions.php';
		require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/' . $detailsFile;		
		$this->animalDetailFunction = new PetMatchProAnimalDetailFunctions();     

       	$this->partialsDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/' . constant('PARTIALS_DIR') . '/';
       	$this->partialsAdminDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('PETPOINT_DIR') . '/';		

        /* Get PetPoint Info Text for Use as Title Text for Filter and Label Fields in Settings */
        $homeDir = get_home_path();
        $adminInfoFile = 'pmp-admin-info.php';
        if ( (is_dir(get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('PETPOINT_DIR') . '/')) && (is_file(get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('PETPOINT_DIR') . '/' . $adminInfoFile)) ) {
            $requireFile = get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('PETPOINT_DIR') . '/' . $adminInfoFile;
        } else {
            $requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('PETPOINT_DIR') . '/' . $adminInfoFile;
        }
        require($requireFile);

       	$this->PMPLicenseTypeID = (int) get_option('PMP_License_Type_ID'); /* Manage Options */   
		if ( $this->PMPLicenseTypeID == 0 ) {
			$this->PMPLicenseTypeID = constant('FREE_LEVEL');
		}         

  		$this->pmpLicenseKey = get_option('PMP_lic_Key');		
    }

    public function initialize_filter_options() {

		if(isset($_REQUEST['page']) && $_REQUEST['page']=='pet-match-pro-options') {
			 settings_fields('pet-match-pro-filter-options'); //prolific  14-09-2022
		}
		do_settings_sections( 'pet-match-pro-filter-options' );

        add_settings_section(
			'filter_settings_section',			            
			__( '', 'pet-match-pro-plugin' ),		        
			array( $this->adminFunction , 'filter_options_callback'),	    
			'pet-match-pro-filter-options'		                
		);

		/* Include Required Function Fields */
		$filterLevelFile = 'pmp-option-levels-filter.php';
	   	$requireFile = $this->partialsAdminDir . $filterLevelFile;
       	//echo 'Require File = ' . $requireFile . '<br>';
	    require $requireFile;

		//echo 'PMP License Type ID = ' . $PMPLicenseTypeID . '<br>';
		//$PMPLicenseTypeID = 3; 
		//echo 'Reset PMP License Type ID to ' . $PMPLicenseTypeID . '<br>';

		//echo 'level_search_filters_adopt = ' . $pmpOptionLevelsFilter['level_search_filters_adopt'] . '<br>';
		if ( (array_key_exists('level_search_filters_' . constant('ADOPT_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_filters_' . constant('ADOPT_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_filters_adopt<br>';
			$classSearchFilter = 'pmp-search-filters-' . constant('ADOPT_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_filters_adopt<br>';		
			$classSearchFilter = 'pmp-option-disable';
		}			
		
       	$adoptSearchFilter = $this->adminFunction->Filter_Option_Values(constant('ADOPT_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER'));   

		add_settings_field(
			constant('ADOPT_METHODTYPE_PETPOINT') . '_search_criteria',
			__( ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' Search Criteria', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	'adopt_search_criteria',
                $this->adminFunction->keyAndLabel($adoptSearchFilter),
                $this->pmpPPAdminInfo['adopt_search_criteria'],
                'class' => $classSearchFilter
            )
		);

		//echo 'level_search_orderby_adopt= ' . $pmpOptionLevelsFilter['level_search_orderby_adopt'] . '<br>';
		if ( (array_key_exists('level_search_' . constant('PETPOINT_ORDERBY') . '_' .  constant('ADOPT_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_' . constant('PETPOINT_ORDERBY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_orderby_adopt<br>';
			$classSearchOrderby = 'pmp-search-' . constant('PETPOINT_ORDERBY') . '-' .  constant('ADOPT_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_orderby_adopt<br>';		
			$classSearchOrderby = 'pmp-option-disable';
		}							

       	$adoptSearchSort = $this->adminFunction->Filter_Option_Values(constant('ADOPT_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_SORT'), constant('LABEL_PREFIX_SEARCH_SORT'));  
       	if ( (!array_key_exists(constant('ADOPT_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY'), $this->pmpPPAdminInfo)) ) {
       		$this->pmpPPAdminInfo[constant('ADOPT_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')] = '';
       	}

		add_settings_field(
			constant('ADOPT_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY'),
			__( ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' Search Sort Options', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	constant('ADOPT_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY'),
                $this->adminFunction->keyAndLabel($adoptSearchSort),
                $this->pmpPPAdminInfo[constant('ADOPT_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')],
                'class' => $classSearchOrderby
			)
		);

		//echo 'level_search_details_adopt = ' . $pmpOptionLevelsFilter['level_search_details_adopt'] . '<br>';
		if ( (array_key_exists('level_search_details_' . constant('ADOPT_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_' . constant('ADOPT_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_details_adopt<br>';
			$classSearchDetails = 'pmp-search-details-' . constant('ADOPT_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_details_adopt<br>';		
			$classSearchDetails = 'pmp-option-disable';
		}					

       	$adoptSearchDetails = $this->adminFunction->Filter_Option_Values(constant('ADOPT_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_RESULT'), constant('LABEL_PREFIX_SEARCH_RESULT'));        
       	   
		add_settings_field(
			constant('ADOPT_METHODTYPE_PETPOINT') . '_search_details',
			__( ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' Details on Search', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	'adopt_search_details',
                $this->adminFunction->keyAndLabel($adoptSearchDetails),
                $this->pmpPPAdminInfo[constant('ADOPT_METHODTYPE_PETPOINT') . '_search_details'],
                'class' => $classSearchDetails
			)
		);
		
		//echo 'level_search_details_label_adopt = ' . $pmpOptionLevelsFilter['level_search_details_label_adopt'] . '<br>';
		if ( (array_key_exists('level_search_details_label_' . constant('ADOPT_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_label_' . constant('ADOPT_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_details_label_adopt<br>';
			$classSearchLabel = 'pmp-search-labels-' . constant('ADOPT_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_details_label_adopt<br>';		
			$classSearchLabel = 'pmp-option-disable';
		}							

		add_settings_field(
			'animal_details_' . constant('ADOPT_METHODTYPE_PETPOINT') . '_search_labels',
			__( 'Show Labels?', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
        	array('pet-match-pro-filter-options',
            	'animal_details_' . constant('ADOPT_METHODTYPE_PETPOINT') . '_search_labels',
                array('Enable' => 'Show Labels on Search Results'),
                $this->pmpPPAdminInfo['animal_details_' . constant('ADOPT_METHODTYPE_PETPOINT') . '_search_labels'],
                'class' => $classSearchLabel
			)
		);
		
		//echo 'level_animal_details_adopt = ' . $pmpOptionLevelsFilter['level_animal_details_adopt'] . '<br>';
		if ( (array_key_exists('level_animal_details_' . constant('ADOPT_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_animal_details_' . constant('ADOPT_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_animal_details_adopt<br>';
			$classAnimalDetails = 'pmp-animal-details-' . constant('ADOPT_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_animal_details_adopt<br>';		
			$classAnimalDetails = 'pmp-option-disable';
		}							

       	$adoptAnimalDetails = $this->adminFunction->Filter_Option_Values(constant('ADOPT_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_ANIMAL_DETAIL'), constant('LABEL_PREFIX_ANIMAL_DETAIL'));

		add_settings_field(
			constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_details',
			__( ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' Single Details', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_details',
                $this->adminFunction->keyAndLabel($adoptAnimalDetails),
                $this->pmpPPAdminInfo[constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_details'],
                'class' => $classAnimalDetails
			)
		);

		//echo 'level_search_filters_found = ' . $pmpOptionLevelsFilter['level_search_filters_found'] . '<br>';
		if ( (array_key_exists('level_search_filters_' . constant('FOUND_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_filters_' . constant('FOUND_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_filters_found<br>';
			$classSearchFilter = 'pmp-search-filters-' . constant('FOUND_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_filters_found<br>';		
			$classSearchFilter = 'pmp-option-disable';
		}			

        //found

       	$foundSearchFilter = $this->adminFunction->Filter_Option_Values(constant('FOUND_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER'));   

        add_settings_field(
		constant('FOUND_METHODTYPE_PETPOINT') . '_search_criteria',
		__( ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' Search Criteria', 'pet-match-pro-plugin' ),
		array( $this->adminFunction, 'checkbox_element_callback'),
		'pet-match-pro-filter-options',	            
		'filter_settings_section',
	         array('pet-match-pro-filter-options',
				constant('FOUND_METHODTYPE_PETPOINT') . '_search_criteria',
                $this->adminFunction->keyAndLabel($foundSearchFilter),
                //$this->keyAndLabel(self::$foundSearchFilter),
                $this->pmpPPAdminInfo[constant('FOUND_METHODTYPE_PETPOINT') . '_search_criteria'],
                'class' => $classSearchFilter . ' pmp-top-line' 
			)
		);

		//echo 'level_search_orderby_found = ' . $pmpOptionLevelsFilter['level_search_orderby_found'] . '<br>';
		if ( (array_key_exists('level_search_' . constant('PETPOINT_ORDERBY') . '_' . constant('FOUND_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_orderby_' . constant('FOUND_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_orderby_found<br>';
			$classSearchOrderby = 'pmp-search-' . constant('PETPOINT_ORDERBY') . '-' . constant('FOUND_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_orderby_found<br>';		
			$classSearchOrderby = 'pmp-option-disable';
		}							

		/* New Section for Found Search Filters */	
       	$foundSearchSort = $this->adminFunction->Filter_Option_Values(constant('FOUND_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_SORT'), constant('LABEL_PREFIX_SEARCH_SORT'));

       	if ( (!array_key_exists(constant('FOUND_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY'), $this->pmpPPAdminInfo)) ) {
       		$this->pmpPPAdminInfo[constant('FOUND_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')] = '';
       	}

        add_settings_field(
		constant('FOUND_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY'),
		__( ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' Search Sort Options', 'pet-match-pro-plugin' ),
		array( $this->adminFunction, 'checkbox_element_callback'),
		'pet-match-pro-filter-options',	            
		'filter_settings_section',
        	array('pet-match-pro-filter-options',
                  constant('FOUND_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY'),
                  $this->adminFunction->keyAndLabel($foundSearchSort),
                  $this->pmpPPAdminInfo[constant('FOUND_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')],
                'class' => $classSearchOrderby  
			)
		);
		
		//echo 'level_search_details_label_found = ' . $pmpOptionLevelsFilter['level_search_details_label_found'] . '<br>';
		if ( (array_key_exists('level_search_details_label_' . constant('FOUND_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_label_' . constant('FOUND_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_details_label_found<br>';
			$classSearchLabel = 'pmp-search-labels-' . constant('FOUND_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_details_label_found<br>';		
			$classSearchLabel = 'pmp-option-disable';
		}							

		//echo 'level_search_details_found = ' . $pmpOptionLevelsFilter['level_search_details_found'] . '<br>';
		if ( (array_key_exists('level_search_details_' . constant('FOUND_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_' . constant('FOUND_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_details_found<br>';
			$classSearchDetails = 'pmp-search-details-' . constant('FOUND_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_details_found<br>';		
			$classSearchDetails = 'pmp-option-disable';
		}					

       	$foundSearchDetails = $this->adminFunction->Filter_Option_Values(constant('FOUND_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_RESULT'), constant('LABEL_PREFIX_SEARCH_RESULT'));                        

        add_settings_field(
		constant('FOUND_METHODTYPE_PETPOINT') . '_search_details',
		__( ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' Details on Search', 'pet-match-pro-plugin' ),
		array( $this->adminFunction, 'checkbox_element_callback'),
		'pet-match-pro-filter-options',	            
		'filter_settings_section',
        	array('pet-match-pro-filter-options',
        	      constant('FOUND_METHODTYPE_PETPOINT') . '_search_details',
        	      $this->adminFunction->keyAndLabel($foundSearchDetails),
        	      //self::$foundSearchDetails,
        	      $this->pmpPPAdminInfo[constant('FOUND_METHODTYPE_PETPOINT') . '_search_details'],
                'class' => $classSearchDetails  
			)
		);

        add_settings_field(
		'animal_details_' . constant('FOUND_METHODTYPE_PETPOINT') . '_search_labels',
		__( 'Show Labels?', 'pet-match-pro-plugin' ),
		array( $this->adminFunction, 'checkbox_element_callback'),
		'pet-match-pro-filter-options',	            
		'filter_settings_section',
           	array('pet-match-pro-filter-options',
        	      'animal_details_' . constant('FOUND_METHODTYPE_PETPOINT') . '_search_labels',
        	      array('Enable' => 'Show Labels on Search Results'),
               	  $this->pmpPPAdminInfo['animal_details_' . constant('FOUND_METHODTYPE_PETPOINT') . '_search_labels'],
                  'class' => $classSearchLabel
          		)
		);

		//echo 'level_animal_details_found = ' . $pmpOptionLevelsFilter['level_animal_details_found'] . '<br>';
		if ( (array_key_exists('level_animal_details_' . constant('FOUND_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_animal_details_' . constant('FOUND_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_animal_details_found<br>';
			$classAnimalDetails = 'pmp-animal-details-' . constant('FOUND_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_animal_details_found<br>';		
			$classAnimalDetails = 'pmp-option-disable';
		}							
			$foundAnimalDetails = $this->adminFunction->Filter_Option_Values(constant('FOUND_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_ANIMAL_DETAIL'), constant('LABEL_PREFIX_ANIMAL_DETAIL'));
            add_settings_field(
			constant('FOUND_METHODTYPE_PETPOINT') . '_animal_details',
			__( ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' Single Details', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
                        array(
                                'pet-match-pro-filter-options',
                                constant('FOUND_METHODTYPE_PETPOINT') . '_animal_details',
                                $this->adminFunction->keyAndLabel($foundAnimalDetails),
                                //self::$foundAnimalDetails,
                                $this->pmpPPAdminInfo[constant('FOUND_METHODTYPE_PETPOINT') . '_animal_details'],
                  'class' => $classAnimalDetails 
			)
		);

		//echo 'level_search_filters_lost = ' . $pmpOptionLevelsFilter['level_search_filters_lost'] . '<br>';
		if ( (array_key_exists('level_search_filters_' . constant('LOST_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_filters_' . constant('LOST_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_filters_lost<br>';
			$classSearchFilter = 'pmp-search-filters-' . constant('LOST_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_filters_lost<br>';		
			$classSearchFilter = 'pmp-option-disable';
		}			

       	$lostSearchFilter = $this->adminFunction->Filter_Option_Values(constant('LOST_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER'));    		

		add_settings_field(
			constant('LOST_METHODTYPE_PETPOINT') . '_search_criteria',
			__( ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' Search Criteria', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
        		array('pet-match-pro-filter-options',
                	constant('LOST_METHODTYPE_PETPOINT') . '_search_criteria',
                    $this->adminFunction->keyAndLabel($lostSearchFilter),
                    $this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_search_criteria'],
                'class' => $classSearchFilter . ' pmp-top-line' 
			)
		);

		//echo 'level_search_orderby_lost= ' . $pmpOptionLevelsFilter['level_search_orderby_lost'] . '<br>';
		if ( (array_key_exists('level_search_orderby_' . constant('LOST_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_orderby_' . constant('LOST_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_orderby_lost<br>';
			$classSearchOrderby = 'pmp-search-orderby-' . constant('LOST_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_orderby_lost<br>';		
			$classSearchOrderby = 'pmp-option-disable';
		}							

		/* New Section for Lost Search Filters */	
       	$lostSearchSort = $this->adminFunction->Filter_Option_Values(constant('LOST_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_SORT'), constant('LABEL_PREFIX_SEARCH_SORT'));

       	if ( (!array_key_exists(constant('LOST_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY'), $this->pmpPPAdminInfo)) ) {
       		$this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')] = '';
       	}

        add_settings_field(
			constant('LOST_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY'),
			__( ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' Search Sort Options', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            	array('pet-match-pro-filter-options',
                	constant('LOST_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY'),
                    $this->adminFunction->keyAndLabel($lostSearchSort),
                    //self::$lostSearchSort,
                    $this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')],
                'class' => $classSearchOrderby
			)
		);

		//echo 'level_search_details_lost = ' . $pmpOptionLevelsFilter['level_search_details_lost'] . '<br>';
		if ( (array_key_exists('level_search_details_' . constant('LOST_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_' . constant('LOST_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_details_lost<br>';
			$classSearchDetails = 'pmp-search-details-' . constant('LOST_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_details_lost<br>';		
			$classSearchDetails = 'pmp-option-disable';
		}					

      	$lostSearchDetails = $this->adminFunction->Filter_Option_Values(constant('LOST_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_RESULT'), constant('LABEL_PREFIX_SEARCH_RESULT'));                        

		add_settings_field(
			constant('LOST_METHODTYPE_PETPOINT') . '_earch_details',
			__( ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' Details on Search', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            	array('pet-match-pro-filter-options',
                	constant('LOST_METHODTYPE_PETPOINT') . '_search_details',
                    $this->adminFunction->keyAndLabel($lostSearchDetails),
                    //self::$lostSearchDetails,
                    $this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_search_details'],
                'class' => $classSearchDetails
			)
		);

		//echo 'level_search_details_label_lost = ' . $pmpOptionLevelsFilter['level_search_details_label_lost'] . '<br>';
		if ( (array_key_exists('level_search_details_label_' . constant('LOST_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_label_' . constant('LOST_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_details_label_lost<br>';
			$classSearchLabel = 'pmp-search-labels-' . constant('LOST_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_search_details_label_lost<br>';		
			$classSearchLabel = 'pmp-option-disable';
		}							
		                
        add_settings_field(
			'animal_details_' . constant('LOST_METHODTYPE_PETPOINT') . '_search_labels',
			__( 'Show Labels?', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
        	array('pet-match-pro-filter-options',
				'animal_details_' . constant('LOST_METHODTYPE_PETPOINT') . '_search_labels',
                array('Enable' => 'Show Labels on Search Results'),
                $this->pmpPPAdminInfo['animal_details_' . constant('LOST_METHODTYPE_PETPOINT') . '_search_labels'],
                'class' => $classSearchLabel  
			)
		);

		//echo 'level_animal_details_lost = ' . $pmpOptionLevelsFilter['level_animal_details_lost'] . '<br>';
		if ( (array_key_exists('level_animal_details_' . constant('LOST_METHODTYPE_PETPOINT'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_animal_details_' . constant('LOST_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_animal_details_lost<br>';
			$classAnimalDetails = 'pmp-animal-details-' . constant('LOST_METHODTYPE_PETPOINT');
		} else {
			//echo 'Disable level_animal_details_lost<br>';		
			$classAnimalDetails = 'pmp-option-disable';
		}							
		
		$lostAnimalDetails = $this->adminFunction->Filter_Option_Values(constant('LOST_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_ANIMAL_DETAIL'), constant('LABEL_PREFIX_ANIMAL_DETAIL'));

        add_settings_field(
			constant('LOST_METHODTYPE_PETPOINT') . '_animal_details',
			__( ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' Single Details', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            	array('pet-match-pro-filter-options',
                	constant('LOST_METHODTYPE_PETPOINT') . '_animal_details',
                    $this->adminFunction->keyAndLabel($lostAnimalDetails),
                    $this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_animal_details'],
                'class' => $classAnimalDetails  
			)
		);

		if ( (array_key_exists('level_' . constant('LOST_METHODTYPE_PETPOINT') . '_' . constant('FOUND_METHODTYPE_PETPOINT') . '_combination', $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_' . constant('LOST_METHODTYPE_PETPOINT') . '_' . constant('FOUND_METHODTYPE_PETPOINT') . '_combination']) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_lost_found_combination<br>';
			$classLostFoundCombo = 'pmp-' . constant('LOST_METHODTYPE_PETPOINT') . '-' . constant('FOUND_METHODTYPE_PETPOINT') . '-combination';
		} else {
			//echo 'Disable level_lost_found_combination<br>';		
			$classLostFoundCombo = 'pmp-option-disable';
		}			

        /* Configure Option for Lost/Found Combined Search */
        add_settings_field(
            constant('LOST_METHODTYPE_PETPOINT') . '_' . constant('FOUND_METHODTYPE_PETPOINT') . '_combination',
            __(ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . '/' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' Combination Selection', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'select_element_callback'),
            'pet-match-pro-filter-options',
            'filter_settings_section',
            array(
                'pet-match-pro-filter-options',
                constant('LOST_METHODTYPE_PETPOINT') . '_' . constant('FOUND_METHODTYPE_PETPOINT') . '_combination',
                array('foundSearch' => 'Found', 'lostSearch' => 'Lost'),
                $this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_' . constant('FOUND_METHODTYPE_PETPOINT') . '_combination'],
                'class' => $classLostFoundCombo . ' pmp-top-line'
            )
        );
        
        //submit_button('Save Filters');
		register_setting(
			'pet-match-pro-filter-options',
			'pet-match-pro-filter-options'
		);

	}

	

    public function initialize_label_options() {

    
    global $pmpAdminInfo;			/* Admin Hover Help Text */
    //echo '<pre>ADMIN INFO<br>'; print_r($this->pmpPPAdminInfo); echo '</pre>';

		if(isset($_REQUEST['page']) && $_REQUEST['page']=='pet-match-pro-options') {
			 settings_fields('pet-match-pro-label-options'); 
		}
		do_settings_sections( 'pet-match-pro-label-options' );

        add_settings_section(
            'label_settings_section',
            __('', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'label_options_callback'),
            'pet-match-pro-label-options'
        );       

        /* Get Field Visibility Levels by License Type */
        $levelsFile = 'pmp-option-levels-labels.php';
        $requireFile = $this->partialsAdminDir . $levelsFile;
        require($requireFile); 
        //echo '<pre> ADMIN OPTION LEVEL VALUES '; print_r($pmpOptionLevelsLabels); echo '</pre>';
        //echo 'PMP License Type ID = ' . $this->PMPLicenseTypeID . '<br>';

        //$PMPLicenseTypeID = 2; 
        if ( (array_key_exists('level_label_fields_' . constant('ADOPT_METHODTYPE_PETPOINT'), $pmpOptionLevelsLabels)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsLabels['level_label_filters_' . constant('ADOPT_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {  
            $labelFieldsAdoptClass = 'pmp-label-fields-' . constant('ADOPT_METHODTYPE_PETPOINT');
        } else {
            $labelFieldsAdoptClass = 'pmp-option-disable';
        }

        /* Get Field Label Values */
        $labelsFile = 'pmp-field-values-' . constant('ADOPT_METHODTYPE_PETPOINT') . '.php';
        $requireFile = $this->partialsDir . $labelsFile;
        require($requireFile); 
        //echo '<pre>FIELD VALUES '; print_r($pmpFieldValuesAdopt); echo '</pre>';

		// Filters for Adopt
        add_settings_field(
            constant('ADOPT_METHODTYPE_PETPOINT') . '_label_filter_title',
            __(ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' Search Filter Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section'
            );

		/* Get Filter Fields from Values File */
        $filterFields = [];
       	$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
       	/* Match Filter Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	//echo 'Field Key Match Pattern is ' . $matchPattern . '.<br>';
       	//echo 'Pattern Match is ' . $matchPattern . '.<br>';
       	$fieldValueKeys = array_keys($pmpFieldValuesAdopt);
		//echo '<pre>FILTER VALUE KEYS<br>'; print_r($fieldValueKeys); echo '</pre>';       	
       	$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
		//echo '<pre>FILTER FIELDS<br>'; print_r($filterFieldKeys); echo '</pre>';
		
	   	if (!empty($filterFieldKeys)) {
	    	foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
	        	$labelOptionKey = trim($filterFieldKey);
//	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
	        	$filterLabelValue = trim($pmpFieldValuesAdopt[$filterFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = $labelOptionKey;
//	        		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($filterLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpPPAdminInfo[$labelKey],
                    		'class' => $labelFieldsAdoptClass
                		)
            		);
	            }
			}
		}

    //        ADOPT SEARCH
        add_settings_field(
            constant('ADOPT_METHODTYPE_PETPOINT') . '_label_search_title',
            __(ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' Search Result Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section',
            array(
                'class' => 'pmp-top-line'
            )
        );
        
		/* Get Search Result Fields from Values File */
        $searchFields = [];
       	$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
       	/* Match Search Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	$searchValueKeys = array_keys($pmpFieldValuesAdopt);
       	$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
		//echo '<pre>SEARCH FIELDS<br>'; print_r($searchFieldKeys); echo '</pre>';
		
	   	if (!empty($searchFieldKeys)) {
	    	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
	        	$labelOptionKey = trim($searchFieldKey);
//	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
	        	$searchLabelValue = trim($pmpFieldValuesAdopt[$searchFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = $labelOptionKey;
//	        		$labelKey = 'label_' . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($searchLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpPPAdminInfo[$labelKey],
                    		'class' => $labelFieldsAdoptClass
                		)
            		);
	            }
			}
		}
		
    //        ADOPT DETAIL
        add_settings_field(
            constant('ADOPT_METHODTYPE_PETPOINT') . '_label_detail_title',
            __(ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' Detail Result Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section',
            array(
                'class' => 'pmp-top-line'
            )
        );
        
		/* Get Detail Result Fields from Values File */
        $searchFields = [];
       	$keyMatch = constant('LABEL_PREFIX_ANIMAL_DETAIL');
       	/* Match Search Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	$detailValueKeys = array_keys($pmpFieldValuesAdopt);
       	$detailFieldKeys = preg_grep($matchPattern, $detailValueKeys);
		//echo '<pre>DETAIL FIELDS<br>'; print_r($detailFieldKeys); echo '</pre>';
		
	   	if (!empty($detailFieldKeys)) {
	    	foreach ($detailFieldKeys as $detailCounter=>$detailFieldKey) {
	        	$labelOptionKey = trim($detailFieldKey);
//	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_ANIMAL_DETAIL'), '', trim($detailFieldKey));
	        	$detailLabelValue = trim($pmpFieldValuesAdopt[$detailFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $detailLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = $labelOptionKey;
//	        		$labelKey = 'label_' . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($detailLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpPPAdminInfo[$labelKey],
                    		'class' => $labelFieldsAdoptClass
                		)
            		);
	            }
			}
		}
				
		/* FOUND LABELS */        
        if ( (array_key_exists('level_label_fields_' . constant('FOUND_METHODTYPE_PETPOINT'), $pmpOptionLevelsLabels)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsLabels['level_label_filters_' . constant('FOUND_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {  
            $labelFieldsFoundClass = 'pmp-label-fields-' . constant('FOUND_METHODTYPE_PETPOINT');
        } else {
            $labelFieldsFoundClass = 'pmp-option-disable';
        }

        /* Get Field Label Values */
        $labelsFile = 'pmp-field-values-' . constant('FOUND_METHODTYPE_PETPOINT') . '.php';
        $requireFile = $this->partialsDir . $labelsFile;
        require($requireFile); 
        //echo '<pre>FIELD VALUES '; print_r($pmpFieldValuesFound); echo '</pre>';

		// Filters for Found
        add_settings_field(
            constant('FOUND_METHODTYPE_PETPOINT') . '_label_filter_title',
            __(ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' Search Filter Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section',
            array(
                'class' => 'pmp-top-line'
            )
        );

		/* Get Filter Fields from Values File */
        $filterFields = [];
       	$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
       	/* Match Filter Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	//echo 'Field Key Match Pattern is ' . $matchPattern . '.<br>';
       	//echo 'Pattern Match is ' . $matchPattern . '.<br>';
       	$fieldValueKeys = array_keys($pmpFieldValuesFound);
		//echo '<pre>FILTER VALUE KEYS<br>'; print_r($fieldValueKeys); echo '</pre>';       	
       	$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
		//echo '<pre>FILTER FIELDS<br>'; print_r($filterFieldKeys); echo '</pre>';
		
	   	if (!empty($filterFieldKeys)) {
	    	foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
	        	$labelOptionKey = trim($filterFieldKey);
//	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
//	        	$filterLabelValue = trim($pmpFieldValuesFound[$filterFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = $labelOptionKey;
//	        		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($filterLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpPPAdminInfo[$labelKey],
                    		'class' => $labelFieldsFoundClass
                		)
            		);
	            }
			}
		}

    //        FOUND SEARCH
        add_settings_field(
            constant('FOUND_METHODTYPE_PETPOINT') . '_label_search_title',
            __(ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' Search Result Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section',
            array(
                'class' => 'pmp-top-line'
            )
        );
        
		/* Get Search Result Fields from Values File */
        $searchFields = [];
       	$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
       	/* Match Search Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	$searchValueKeys = array_keys($pmpFieldValuesFound);
       	$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
		//echo '<pre>SEARCH FIELDS<br>'; print_r($searchFieldKeys); echo '</pre>';
		
	   	if (!empty($searchFieldKeys)) {
	    	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
	        	$labelOptionKey = trim($searchFieldKey);
//	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
	        	$searchLabelValue = trim($pmpFieldValuesFound[$searchFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = $labelOptionKey;
//	        		$labelKey = 'label_' . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($searchLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpPPAdminInfo[$labelKey],
                    		'class' => $labelFieldsFoundClass
                		)
            		);
	            }
			}
		}

    //        FOUND DETAIL
        add_settings_field(
            constant('FOUND_METHODTYPE_PETPOINT') . '_label_detail_title',
            __(ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' Detail Result Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section',
            array(
                'class' => 'pmp-top-line'
            )
        );
        
		/* Get Detail Result Fields from Values File */
        $searchFields = [];
       	$keyMatch = constant('LABEL_PREFIX_ANIMAL_DETAIL');
       	/* Match Search Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	$detailValueKeys = array_keys($pmpFieldValuesFound);
       	$detailFieldKeys = preg_grep($matchPattern, $detailValueKeys);
		//echo '<pre>DETAIL FIELDS<br>'; print_r($detailFieldKeys); echo '</pre>';
		
	   	if (!empty($detailFieldKeys)) {
	    	foreach ($detailFieldKeys as $detailCounter=>$detailFieldKey) {
	        	$labelOptionKey = trim($detailFieldKey);
//	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_ANIMAL_DETAIL'), '', trim($detailFieldKey));
	        	$detailLabelValue = trim($pmpFieldValuesFound[$detailFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $detailLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = $labelOptionKey;
//	        		$labelKey = 'label_' . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($detailLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpPPAdminInfo[$labelKey],
                    		'class' => $labelFieldsFoundClass
                		)
            		);
	            }
			}
		}
				
		/* LOST LABELS */        
        if ( (array_key_exists('level_label_fields_' . constant('LOST_METHODTYPE_PETPOINT'), $pmpOptionLevelsLabels)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsLabels['level_label_filters_' . constant('LOST_METHODTYPE_PETPOINT')]) && (!empty($this->pmpLicenseKey)) ) {  
            $labelFieldsLostClass = 'pmp-label-fields-' . constant('LOST_METHODTYPE_PETPOINT');
        } else {
            $labelFieldsLostClass = 'pmp-option-disable';
        }

        /* Get Field Label Values */
        $labelsFile = 'pmp-field-values-' . constant('LOST_METHODTYPE_PETPOINT') . '.php';
        $requireFile = $this->partialsDir . $labelsFile;
        require($requireFile); 
        //echo '<pre>FIELD VALUES '; print_r($pmpFieldValuesLost); echo '</pre>';

		// Filters for Found
        add_settings_field(
            constant('LOST_METHODTYPE_PETPOINT') . '_label_filter_title',
            __(ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' Search Filter Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section',
            array(
                'class' => 'pmp-top-line'
            )
        );

		/* Get Filter Fields from Values File */
        $filterFields = [];
       	$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
       	/* Match Filter Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	//echo 'Field Key Match Pattern is ' . $matchPattern . '.<br>';
       	//echo 'Pattern Match is ' . $matchPattern . '.<br>';
       	$fieldValueKeys = array_keys($pmpFieldValuesLost);
		//echo '<pre>FILTER VALUE KEYS<br>'; print_r($fieldValueKeys); echo '</pre>';       	
       	$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
		//echo '<pre>FILTER FIELDS<br>'; print_r($filterFieldKeys); echo '</pre>';
		
	   	if (!empty($filterFieldKeys)) {
	    	foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
	        	$labelOptionKey = trim($filterFieldKey);
//	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
	        	$filterLabelValue = trim($pmpFieldValuesLost[$filterFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = $labelOptionKey;
//	        		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($filterLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpPPAdminInfo[$labelKey],
                    		'class' => $labelFieldsLostClass
                		)
            		);
	            }
			}
		}

    //        LOST SEARCH
        add_settings_field(
            constant('LOST_METHODTYPE_PETPOINT') . '_label_search_title',
            __(ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' Search Result Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section',
            array(
                'class' => 'pmp-top-line'
            )
        );
        
		/* Get Search Result Fields from Values File */
        $searchFields = [];
       	$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
       	/* Match Search Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	$searchValueKeys = array_keys($pmpFieldValuesLost);
       	$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
		//echo '<pre>SEARCH FIELDS<br>'; print_r($searchFieldKeys); echo '</pre>';
		
	   	if (!empty($searchFieldKeys)) {
	    	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
	        	$labelOptionKey = $searchFieldKey;
//	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
	        	$searchLabelValue = trim($pmpFieldValuesLost[$searchFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = $labelOptionKey;
//	        		$labelKey = 'label_' . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($searchLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpPPAdminInfo[$labelKey],
                    		'class' => $labelFieldsLostClass
                		)
            		);
	            }
			}
		}

    //        LOST DETAIL
        add_settings_field(
            constant('LOST_METHODTYPE_PETPOINT') . '_label_detail_title',
            __(ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' Detail Result Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section',
            array(
                'class' => 'pmp-top-line'
            )
        );
        
		/* Get Detail Result Fields from Values File */
        $searchFields = [];
       	$keyMatch = constant('LABEL_PREFIX_ANIMAL_DETAIL');
       	/* Match Search Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	$detailValueKeys = array_keys($pmpFieldValuesLost);
       	$detailFieldKeys = preg_grep($matchPattern, $detailValueKeys);
		//echo '<pre>DETAIL FIELDS<br>'; print_r($detailFieldKeys); echo '</pre>';
		
	   	if (!empty($detailFieldKeys)) {
	    	foreach ($detailFieldKeys as $detailCounter=>$detailFieldKey) {
	        	$labelOptionKey = trim($detailFieldKey);
//	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_ANIMAL_DETAIL'), '', trim($detailFieldKey));
	        	$detailLabelValue = trim($pmpFieldValuesLost[$detailFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $detailLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = $labelOptionKey;
//	        		$labelKey = 'label_' . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($detailLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpPPAdminInfo[$labelKey],
                    		'class' => $labelFieldsLostClass
                		)
            		);
	            }
			}
		}
						
        register_setting(
            'pet-match-pro-label-options',
            'pet-match-pro-label-options'
        );        
    }
}