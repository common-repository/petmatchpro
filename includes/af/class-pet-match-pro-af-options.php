<?php 

class Pet_Match_Pro_AF_Options {

	public $api_activated;
    private $adminFunction;
    private $animalDetailFunction;    
	public $pmpAFAdminInfo;			/* Admin Hover Help Text */   
	public $PMPLicenseTypeID;	
	public $pmpLicenseKey;
	
    private $partialsDir;
    private $partialsAdminDir;
	
    public function __construct( $api_activated ) {
        global $pmpAdminInfo;			/* Admin Hover Help Text */

        $this->api_activated = $api_activated;	

		/* Include Class Defining Functions for Processing Animal Searches */
		$functionsFile = 'class-pet-match-pro-functions.php';
		require constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/' . $functionsFile;
		$this->adminFunction = new PetMatchProFunctions();  

		/* Include Class Defining Animal Detail Functions */
		$detailsFile = 'class-pet-match-pro-af-detail-functions.php';
		require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/' . $detailsFile;		
		$this->animalDetailFunction = new PetMatchProAnimalDetailFunctions();  

       	$this->partialsDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/' . constant('PARTIALS_DIR') . '/';
       	$this->partialsAdminDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/';		

        /* Get ANIMALSFIRST Info Text for Use as Title Text for Filter and Label Fields in Settings */
        $homeDir = get_home_path();
        $adminInfoFile = 'pmp-admin-info.php';
//        $pmpAdminInfo = [];
        if ( (is_dir(get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/')) && (is_file(get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/' . $adminInfoFile)) ) {
            $requireFile = get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/' . $adminInfoFile;
        } else {
            $requireFile = $this->partialsAdminDir . $adminInfoFile;
        }
        require($requireFile);   

       	$this->PMPLicenseTypeID = (int) get_option('PMP_License_Type_ID'); /* Manage Options */   
		if ( $this->PMPLicenseTypeID == 0 ) {
			$this->PMPLicenseTypeID = constant('FREE_LEVEL');
		} 
		//$this->PMPLicenseTypeID = 3;
		
		$this->pmpLicenseKey = get_option('PMP_lic_Key');
    }

    public function display_form(){

        if($this->api_activated == true){

            settings_fields( 'pet-match-pro-filter-options' );

            //$this->initialize_filter_options();

            do_settings_sections( 'pet-match-pro-filter-options' );

            //submit_button('Save Filters');    

        }  else {

			echo 'not activated';

        }

    }

                

    public function initialize_filter_options() {

    

    global $pmpAdminInfo;			/* Admin Hover Help Text */

            

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
		//$functionsFile = 'pmp-filter-functions.php';
		//$filePath = '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/';
		//$filePath = '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/';
		$filterLevelFile = 'pmp-option-levels-filter.php';
	   	$requireFile = $this->partialsAdminDir . $filterLevelFile;
       	//echo 'Require File = ' . $requireFile . '<br>';
	    require $requireFile;

		//echo 'PMP License Type ID = ' . $PMPLicenseTypeID . '<br>';

		//$PMPLicenseTypeID = 3; 

		//echo 'Reset PMP License Type ID to ' . $PMPLicenseTypeID . '<br>';

		//echo '<pre>pmpOptionLevelsFilter<br>'; print_r($pmpOptionLevelsFilter); echo '</pre>';

		//echo 'level_search_filters_adopt = ' . $pmpOptionLevelsFilter['level_search_filters_adopt'] . '<br>';

		if ( (array_key_exists('level_search_filters_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_filters_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_filters_adopt<br>';
			$classSearchFilter = 'pmp-search-filters-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');
		} else {
			//echo 'Disable level_search_filters_adopt<br>';		
			$classSearchFilter = 'pmp-option-disable';
		}			

       	$adoptSearchFilter = $this->adminFunction->Filter_Option_Values(constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER'));   

		add_settings_field(
			constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_criteria',
			__( ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' Search Criteria', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_criteria',
                $this->adminFunction->keyAndLabel($adoptSearchFilter),
                $this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_criteria'],
                'class' => $classSearchFilter
            )
		);

		if ( (array_key_exists('level_search_' . constant('ANIMALSFIRST_ORDERBY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_' . constant('ANIMALSFIRST_ORDERBY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_sortfield_adopt<br>';
			$classSearchSortField = 'pmp-search-' . constant('ANIMALSFIRST_ORDERBY') . '-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');
		} else {
			//echo 'Disable level_search_sortfield_adopt<br>';		
			$classSearchSortField = 'pmp-option-disable';
		}							

       	$adoptSearchSort = $this->adminFunction->Filter_Option_Values(constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_SORT'), constant('LABEL_PREFIX_SEARCH_SORT'));  
       	if ( (is_array($adoptSearchSort)) && (empty($adoptSearchSort)) ) {
       		$adoptSearchSort[constant('ANIMALSFIRST_NAME')] = ucfirst(constant('ANIMALSFIRST_NAME'));
       	}
		//echo '<pre>Adopt Sort Fields Returned from Function<br>'; print_r($adoptSearchSort); echo '</pre>';
       	if ( (!array_key_exists(constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'), $this->pmpAFAdminInfo)) ) {
       		$this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')] = '';
       	}

		add_settings_field(
			constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'),
			__( ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' Search Sort Options', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'),
                $this->adminFunction->keyAndLabel($adoptSearchSort),
                $this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')],
                'class' => $classSearchSortField
			)
		);

		if ( (array_key_exists('level_search_' . constant('ANIMALSFIRST_SORTORDER') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_' . constant('ANIMALSFIRST_SORTORDER') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_sortorder_adopt<br>';
			$classSearchSortOrder = 'pmp-search-' . constant('ANIMALSFIRST_SORTORDER') . '-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');
		} else {
			//echo 'Disable level_search_sortorder_adopt<br>';		
			$classSearchSortOrder = 'pmp-option-disable';
		}							

       		$adoptSearchSortOrder = $this->adminFunction->Filter_Option_Values(constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_ORDER'), constant('LABEL_PREFIX_SEARCH_ORDER'));  
       	if ( (!array_key_exists(constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'), $this->pmpAFAdminInfo)) ) {
       		$this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')] = '';
       	}
		add_settings_field(
			constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'),
			__( ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' Search Sort Order', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'),
                $this->adminFunction->keyAndLabel($adoptSearchSortOrder),
                $this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')],
                'class' => $classSearchSortOrder
			)
		);

		//echo 'level_search_details_label_adopt = ' . $pmpOptionLevelsFilter['level_search_details_label_adopt'] . '<br>';

		if ( (array_key_exists('level_search_details_label_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_label_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_details_label_adopt<br>';
			$classSearchLabel = 'pmp-search-labels-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');
		} else {
			//echo 'Disable level_search_details_label_adopt<br>';		
			$classSearchLabel = 'pmp-option-disable';
		}							

		//echo 'level_search_details_adopt = ' . $pmpOptionLevelsFilter['level_search_details_adopt'] . '<br>';
		if ( (array_key_exists('level_search_details_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_details_adopt<br>';
			$classSearchDetails = 'pmp-search-details-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');
		} else {
			//echo 'Disable level_search_details_adopt<br>';		
			$classSearchDetails = 'pmp-option-disable';
		}					
        
       	$adoptSearchDetails = $this->adminFunction->Filter_Option_Values(constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_RESULT'), constant('LABEL_PREFIX_SEARCH_RESULT'));

		add_settings_field(
			constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_details',
			__( ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' Details on Search', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_details',
                $this->adminFunction->keyAndLabel($adoptSearchDetails),
                $this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_details'],
                'class' => $classSearchDetails
			)
		);
		
		add_settings_field(
			'animal_details_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_labels',
			__( 'Show Labels?', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
        	array('pet-match-pro-filter-options',
            	'animal_details_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_labels',
                array('Enable' => 'Show Labels on Search Results'),
                $this->pmpAFAdminInfo['animal_details_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_labels'],
                'class' => $classSearchLabel
			)
		);

		//echo 'level_animal_details_adopt = ' . $pmpOptionLevelsFilter['level_animal_details_adopt'] . '<br>';

		if ( (array_key_exists('level_animal_details_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_animal_details_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classAnimalDetails = 'pmp-animal-details-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');
		} else {
			$classAnimalDetails = 'pmp-option-disable';
		}							

       	$adoptAnimalDetails = $this->adminFunction->Filter_Option_Values(constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_ANIMAL_DETAIL'), constant('LABEL_PREFIX_ANIMAL_DETAIL'));

		add_settings_field(
			constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_details',
			__( ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' Single Details', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
				constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_details',
                $this->adminFunction->keyAndLabel($adoptAnimalDetails),
                $this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_details'],
                'class' => $classAnimalDetails
			)
		);

		//echo 'level_search_filters_found = ' . $pmpOptionLevelsFilter['level_search_filters_found'] . '<br>';

		if ( (array_key_exists('level_search_filters_' . constant('FOUND_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_filters_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchFilter = 'pmp-search-filters-' . constant('FOUND_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchFilter = 'pmp-option-disable';
		}			

        //found

       	$foundSearchFilter = $this->adminFunction->Filter_Option_Values(constant('FOUND_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER'));   

        add_settings_field(
		constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_criteria',
		__( ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' Search Criteria', 'pet-match-pro-plugin' ),
		array( $this->adminFunction, 'checkbox_element_callback'),
		'pet-match-pro-filter-options',	            
		'filter_settings_section',
	         array('pet-match-pro-filter-options',
				constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_criteria',
                $this->adminFunction->keyAndLabel($foundSearchFilter),
                //$this->adminFunction->keyAndLabel(self::$foundSearchFilter),
                $this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_criteria'],
                'class' => $classSearchFilter . ' pmp-top-line' 
			)
		);

		//echo 'level_search_orderby_found = ' . $pmpOptionLevelsFilter['level_search_orderby_found'] . '<br>';

		if ( (array_key_exists('level_search_' . constant('ANIMALSFIRST_ORDERBY') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_' . constant('ANIMALSFIRST_ORDERBY') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchSortField = 'pmp-search-' . constant('ANIMALSFIRST_ORDERBY') . '-' . constant('FOUND_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchSortField = 'pmp-option-disable';
		}							
		
		/* New Section for Found Search Filters */	
       	$foundSearchSort = $this->adminFunction->Filter_Option_Values(constant('FOUND_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_SORT'), constant('LABEL_PREFIX_SEARCH_SORT'));

       	if ( (is_array($foundSearchSort)) && (empty($foundSearchSort)) ) {
       		$foundSearchSort[constant('ANIMALSFIRST_SEQ_ID')] = ucwords(constant('ANIMALSFIRST_SEQ_ID'));
       	}

       	if ( (!array_key_exists(constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'), $this->pmpAFAdminInfo)) ) {
       		$this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')] = '';
       	}

        add_settings_field(
		constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'),
		__( ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' Search Sort Options', 'pet-match-pro-plugin' ),
		array( $this->adminFunction, 'checkbox_element_callback'),
		'pet-match-pro-filter-options',	            
		'filter_settings_section',
        	array('pet-match-pro-filter-options',
                  constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'),
                	$this->adminFunction->keyAndLabel($foundSearchSort),      	      
                  $this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')],
                'class' => $classSearchSortField  
			)
		);

		if ( (array_key_exists('level_search_' . constant('ANIMALSFIRST_SORTORDER') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_' . constant('ANIMALSFIRST_SORTORDER') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchSortOrder = 'pmp-search-' . constant('ANIMALSFIRST_SORTORDER') . '-' . constant('FOUND_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchSortOrder = 'pmp-option-disable';
		}							

       	$foundSearchSortOrder = $this->adminFunction->Filter_Option_Values(constant('FOUND_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_ORDER'), constant('LABEL_PREFIX_SEARCH_ORDER'));  

       	if ( (!array_key_exists(constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'), $this->pmpAFAdminInfo)) ) {
       		$this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')] = '';
       	}

		add_settings_field(
			constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'),
			__( ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' Search Sort Order', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'),
                	$this->adminFunction->keyAndLabel($foundSearchSortOrder),      	      
                $this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')],
                'class' => $classSearchSortOrder
			)
		);

	    //echo '<pre> ADMIN LOST OPTION LEVEL VALUES '; print_r($pmpFieldLevelLost); echo '</pre>';
		//echo 'PMP License Type ID = ' . $PMPLicenseTypeID . '<br>';
		//$PMPLicenseTypeID = 3; 

		//echo 'level_search_details_label_found = ' . $pmpOptionLevelsFilter['level_search_details_label_found'] . '<br>';

		if ( (array_key_exists('level_search_details_label_' . constant('FOUND_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_label_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchLabel = 'pmp-search-labels-' . constant('FOUND_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchLabel = 'pmp-option-disable';
		}							

		//echo 'level_search_details_found = ' . $pmpOptionLevelsFilter['level_search_details_found'] . '<br>';

		if ( (array_key_exists('level_search_details_' . constant('FOUND_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchDetails = 'pmp-search-details-' . constant('FOUND_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchDetails = 'pmp-option-disable';
		}					

       	$foundSearchDetails = $this->adminFunction->Filter_Option_Values(constant('FOUND_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_RESULT'), constant('LABEL_PREFIX_SEARCH_RESULT'));                

        add_settings_field(
		constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_details',
		__( ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' Details on Search', 'pet-match-pro-plugin' ),
		array( $this->adminFunction, 'checkbox_element_callback'),
		'pet-match-pro-filter-options',	            
		'filter_settings_section',
        	array('pet-match-pro-filter-options',
        	      constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_details',
                $this->adminFunction->keyAndLabel($foundSearchDetails),      	      
        	      $this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_details'],
                'class' => $classSearchDetails  
			)
		);
                
        add_settings_field(
		'animal_details_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_labels',
		__( 'Show Labels?', 'pet-match-pro-plugin' ),
		array( $this->adminFunction, 'checkbox_element_callback'),
		'pet-match-pro-filter-options',	            
		'filter_settings_section',
           	array('pet-match-pro-filter-options',
        	      'animal_details_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_labels',
        	      array('Enable' => 'Show Labels on Search Results'),
               	  $this->pmpAFAdminInfo['animal_details_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_labels'],
                  'class' => $classSearchLabel
          		)
		);
		//echo 'level_animal_details_found = ' . $pmpOptionLevelsFilter['level_animal_details_found'] . '<br>';

		if ( (array_key_exists('level_animal_details_' . constant('FOUND_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_animal_details_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classAnimalDetails = 'pmp-animal-details-' . constant('FOUND_METHODTYPE_ANIMALSFIRST');
		} else {
			$classAnimalDetails = 'pmp-option-disable';
		}							

			$foundAnimalDetails = $this->adminFunction->Filter_Option_Values(constant('FOUND_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_ANIMAL_DETAIL'), constant('LABEL_PREFIX_ANIMAL_DETAIL'));

            add_settings_field(
			constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_animal_details',
			__( ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' Single Details', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
                        array(
                                'pet-match-pro-filter-options',
                                constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_animal_details',
			                	$this->adminFunction->keyAndLabel($foundAnimalDetails),      	      
                                $this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_animal_details'],
                  				'class' => $classAnimalDetails 
			)
		);

		//echo 'level_search_filters_lost = ' . $pmpOptionLevelsFilter['level_search_filters_lost'] . '<br>';
		if ( (array_key_exists('level_search_filters_' . constant('LOST_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_filters_' . constant('LOST_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchFilter = 'pmp-search-filters-' . constant('LOST_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchFilter = 'pmp-option-disable';
		}			

       	$lostSearchFilter = $this->adminFunction->Filter_Option_Values(constant('LOST_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER'));    		

		add_settings_field(
			constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_criteria',
			__( ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' Search Criteria', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
        		array('pet-match-pro-filter-options',
                	constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_criteria',
                    $this->adminFunction->keyAndLabel($lostSearchFilter),
                    $this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_criteria'],
                'class' => $classSearchFilter . ' pmp-top-line'
			)
		);
		//echo 'level_search_details_lost = ' . $pmpOptionLevelsFilter['level_search_details_lost'] . '<br>';

		if ( (array_key_exists('level_search_details' . constant('LOST_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_' . constant('LOST_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchDetails = 'pmp-search-details' . constant('LOST_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchDetails = 'pmp-option-disable';
		}					

		//echo 'level_search_orderby_lost= ' . $pmpOptionLevelsFilter['level_search_orderby_lost'] . '<br>';

		if ( (array_key_exists('level_search_' . constant('ANIMALSFIRST_ORDERBY') . '_' . constant('LOST_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_' . constant('ANIMALSFIRST_ORDERBY') . '_' . constant('LOST_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchSortField = 'pmp-search-' . constant('ANIMALSFIRST_ORDERBY') . '-' . constant('LOST_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchSortField = 'pmp-option-disable';
		}							

		/* New Section for Lost Search Filters */	

       	$lostSearchSort = $this->adminFunction->Filter_Option_Values(constant('LOST_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_SORT'), constant('LABEL_PREFIX_SEARCH_SORT'));
       	if ( (is_array($lostSearchSort)) && (empty($lostSearchSort)) ) {
       		$lostSearchSort[constant('ANIMALSFIRST_SEQ_ID')] = ucwords(constant('ANIMALSFIRST_SEQ_ID'));
       	}
       	
       	if ( (!array_key_exists(constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_sort', $this->pmpAFAdminInfo)) ) {
       		$this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_sort'] = '';
       	}

        add_settings_field(
			constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'),
			__( ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' Search Sort Options', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            	array('pet-match-pro-filter-options',
                	constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'),
                    $this->adminFunction->keyAndLabel($lostSearchSort),
                    $this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')],
                'class' => $classSearchSortField
			)
		);

		if ( (array_key_exists('level_search_' . constant('ANIMALSFIRST_SORTORDER') . '_' . constant('LOST_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_' . constant('ANIMALSFIRST_SORTORDER') . '_' . constant('LOST_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchSortOrder = 'pmp-search-' . constant('ANIMALSFIRST_SORTORDER') . '-' . constant('LOST_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchSortOrder = 'pmp-option-disable';
		}							

       	$lostSearchSortOrder = $this->adminFunction->Filter_Option_Values(constant('LOST_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_ORDER'), constant('LABEL_PREFIX_SEARCH_ORDER'));  
       	if ( (!array_key_exists(constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'), $this->pmpAFAdminInfo)) ) {
       		$this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')] = '';
       	}

		add_settings_field(
			constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'),
			__( ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' Search Sort Order', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'),
                  $this->adminFunction->keyAndLabel($lostSearchSortOrder),
                $this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')],
                'class' => $classSearchSortOrder
			)
		);
		
	    //echo '<pre> ADMIN LOST OPTION LEVEL VALUES '; print_r($pmpFieldLevelLost); echo '</pre>';
		//echo 'PMP License Type ID = ' . $PMPLicenseTypeID . '<br>';
		//$PMPLicenseTypeID = 3; 

		//echo 'level_search_details_label_lost = ' . $pmpOptionLevelsFilter['level_search_details_label_lost'] . '<br>';
		if ( (array_key_exists('level_search_details_' . constant('LOST_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_' . constant('LOST_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchDetails = 'pmp-search-details-' . constant('LOST_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchDetails = 'pmp-option-disable';
		}							

      	$lostSearchDetails = $this->adminFunction->Filter_Option_Values(constant('LOST_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_RESULT'), constant('LABEL_PREFIX_SEARCH_RESULT'));                

		add_settings_field(
			constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_details',
			__( ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' Details on Search', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            	array('pet-match-pro-filter-options',
                	constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_details',
                    $this->adminFunction->keyAndLabel($lostSearchDetails),
                    $this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_details'],
                'class' => $classSearchDetails
			)
		);
				                
        add_settings_field(
			'animal_details_' . constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_labels',
			__( 'Show Labels?', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
        	array('pet-match-pro-filter-options',
				'animal_details_' . constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_labels',
                array('Enable' => 'Show Labels on Search Results'),
                $this->pmpAFAdminInfo['animal_details_' . constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_labels'],
                'class' => $classSearchLabel  
			)
		);
		//echo 'level_animal_details_lost = ' . $pmpOptionLevelsFilter['level_animal_details_lost'] . '<br>';

		if ( (array_key_exists('level_animal_details_' . constant('LOST_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_animal_details_' . constant('LOST_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {
			$classAnimalDetails = 'pmp-animal-details-' . constant('LOST_METHODTYPE_ANIMALSFIRST');
		} else {
			$classAnimalDetails = 'pmp-option-disable';
		}							

		$lostAnimalDetails = $this->adminFunction->Filter_Option_Values(constant('LOST_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_ANIMAL_DETAIL'), constant('LABEL_PREFIX_ANIMAL_DETAIL'));

        add_settings_field(
			constant('LOST_METHODTYPE_ANIMALSFIRST') . '_animal_details',
			__( ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' Single Details', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            	array('pet-match-pro-filter-options',
                	constant('LOST_METHODTYPE_ANIMALSFIRST') . '_animal_details',
	                  $this->adminFunction->keyAndLabel($lostAnimalDetails),
                    $this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_animal_details'],
                'class' => $classAnimalDetails  
			)
		);
		
		/* New Section for Preferred Search Filters */	
		$generalOptions = get_option('pet-match-pro-general-options');
		$preferredLabel = $generalOptions[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_methodtype_label'];
		if (strlen(trim($preferredLabel)) == 0) {
			$preferredLabel = ucfirst(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'));
		}	
		
//		$preferredSuffix = strtolower(constant('ANIMALSFIRST_TYPE_PREFERRED'));
		$preferredFiltersKey = substr_replace(constant('LEVEL_PREFIX_SEARCH_FILTER'), '', -1) . 's_preferred';
		//echo 'Preferred Search Filters Level Key is ' . $preferredFiltersKey . '.<br>';
		//echo 'level_search_filters_preferred = ' . $pmpOptionLevelsFilter['level_search_filters_preferred'] . '<br>';
		if ( (array_key_exists($preferredFiltersKey, $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter[$preferredFiltersKey]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchFilter = 'pmp-search-filters-' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchFilter = 'pmp-option-disable';
		}			

       	$preferredSearchFilter = $this->adminFunction->Filter_Option_Values(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER'));    		

		add_settings_field(
			constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_criteria',
			__( $preferredLabel . ' Search Criteria', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
        		array('pet-match-pro-filter-options',
                	'preferred_search_criteria',
                    $this->adminFunction->keyAndLabel($preferredSearchFilter),
                    $this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_criteria'],
                'class' => $classSearchFilter . ' pmp-top-line'
			)
		);
		//echo 'level_search_orderby_preferred= ' . $pmpOptionLevelsFilter['level_search_orderby_preferred'] . '<br>';

		$preferredSortFieldKey = constant('LEVEL_PREFIX_SEARCH') . constant('ANIMALSFIRST_ORDERBY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
		//echo 'Preferred Search SortField Level Key is ' . $preferredSortFieldKey . '.<br>';
		if ( (array_key_exists($preferredSortFieldKey, $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter[$preferredSortFieldKey]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchSortField = 'pmp-search-' . constant('ANIMALSFIRST_ORDERBY') . '-' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchSortField = 'pmp-option-disable';
		}							

       	$preferredSearchSort = $this->adminFunction->Filter_Option_Values(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_SORT'), constant('LABEL_PREFIX_SEARCH_SORT'));
       	if ( (is_array($preferredSearchSort)) && (empty($preferredSearchSort)) ) {
       		$otherSearchSort[constant('ANIMALSFIRST_SEQ_ID')] = ucwords(constant('ANIMALSFIRST_SEQ_ID'));
       	}
       	
       	if ( (!array_key_exists(constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'), $this->pmpAFAdminInfo)) ) {
       		$this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')] = '';
       	}

        add_settings_field(
			constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'),
			__( $preferredLabel . ' Search Sort Options', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            	array('pet-match-pro-filter-options',
                	constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY'),
                    $this->adminFunction->keyAndLabel($preferredSearchSort),
                    $this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')],
                'class' => $classSearchSortField
			)
		);

		$preferredSortOrderKey = constant('LEVEL_PREFIX_SEARCH') . constant('ANIMALSFIRST_SORTORDER') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
		//echo 'Preferred Search SortOrder Level Key is ' . $preferredSortOrderKey . '.<br>';
		if ( (array_key_exists($preferredSortOrderKey, $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter[$preferredSortOrderKey]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchSortOrder = 'pmp-search-' . constant('ANIMALSFIRST_SORTORDER') . '-' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchSortOrder = 'pmp-option-disable';
		}							

       	$preferredSearchSortOrder = $this->adminFunction->Filter_Option_Values(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_ORDER'), constant('LABEL_PREFIX_SEARCH_ORDER'));  
       	if ( (!array_key_exists(constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'), $this->pmpAFAdminInfo)) ) {
       		$this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')] = '';
       	}

		add_settings_field(
			constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'),
			__( $preferredLabel . ' Search Sort Order', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER'),
                $this->adminFunction->keyAndLabel($preferredSearchSortOrder),
                $this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')],
                'class' => $classSearchSortOrder
			)
		);
		
	    //echo '<pre> ADMIN OTHER OPTION LEVEL VALUES '; print_r($pmpFieldLevelOther); echo '</pre>';
		//echo 'PMP License Type ID = ' . $PMPLicenseTypeID . '<br>';
		//$PMPLicenseTypeID = 3; 

		$preferredDetailsKey = constant('LEVEL_PREFIX_SEARCH') . 'details_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
		//echo 'Preferred Search Labels Level Key is ' . $preferredDetailsKey . '.<br>';
		//echo 'level_search_details_label_other = ' . $pmpOptionLevelsFilter['level_search_details_label_other'] . '<br>';
		if ( (array_key_exists($preferredDetailsKey, $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter[$preferredDetailsKey]) && (!empty($this->pmpLicenseKey)) ) {
			$classSearchDetails = 'pmp-search-details-' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
		} else {
			$classSearchDetails = 'pmp-option-disable';
		}							

      	$preferredSearchDetails = $this->adminFunction->Filter_Option_Values(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_RESULT'), constant('LABEL_PREFIX_SEARCH_RESULT'));                

		add_settings_field(
			constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_details',
			__( $preferredLabel . ' Details on Search', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            	array('pet-match-pro-filter-options',
                	constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_details',
                    $this->adminFunction->keyAndLabel($preferredSearchDetails),
                    $this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_details'],
                'class' => $classSearchDetails
			)
		);

        add_settings_field(
			'animal_details_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_labels',
			__( 'Show Labels?', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
        	array('pet-match-pro-filter-options',
				'animal_details_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_labels',
                array('Enable' => 'Show Labels on Search Results'),
                $this->pmpAFAdminInfo['animal_details_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_labels'],
                'class' => $classSearchLabel  
			)
		);
		//echo 'level_animal_details_other = ' . $pmpOptionLevelsFilter['level_animal_details_other'] . '<br>';

		$preferredDetailsKey = 'level_animal_details_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
		//echo 'Preferred Search Details Level Key is ' . $preferredDetailsKey . '.<br>';
		if ( (array_key_exists($preferredDetailsKey, $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter[$preferredDetailsKey]) && (!empty($this->pmpLicenseKey)) ) {
			$classAnimalDetails = 'pmp-animal-details-' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
		} else {
			$classAnimalDetails = 'pmp-option-disable';
		}							

		$preferredAnimalDetails = $this->adminFunction->Filter_Option_Values(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_ANIMAL_DETAIL'), constant('LABEL_PREFIX_ANIMAL_DETAIL'));

        add_settings_field(
			constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_animal_details',
			__( $preferredLabel . ' Single Details', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            	array('pet-match-pro-filter-options',
                	constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_animal_details',
	                  $this->adminFunction->keyAndLabel($preferredAnimalDetails),
                    $this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_animal_details'],
                'class' => $classAnimalDetails  
			)
		);

		if ( (array_key_exists('level_' . constant('LOST_METHODTYPE_ANIMALSFIRST') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_combination', $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_' . constant('LOST_METHODTYPE_ANIMALSFIRST') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_combination']) && (!empty($this->pmpLicenseKey)) ) {
			$classLostFoundCombo = 'pmp-' . constant('LOST_METHODTYPE_ANIMALSFIRST') . '-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '-combination';
		} else {
			$classLostFoundCombo = 'pmp-option-disable';
		}			
        
        /* Configure Option for Lost/Found Combined Search */
        add_settings_field(
            constant('LOST_METHODTYPE_ANIMALSFIRST') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_combination',
            __(ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . '/' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' Combination Selection', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'select_element_callback'),
            'pet-match-pro-filter-options',
            'filter_settings_section',
            array(
                'pet-match-pro-filter-options',
                constant('LOST_METHODTYPE_ANIMALSFIRST') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_combination',
                array('foundSearch' => 'Found', 'lostSearch' => 'Lost'),
                $this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_combination'],
                'class' => $classLostFoundCombo . ' pmp-top-line'
            )
        );

		register_setting(
			'pet-match-pro-filter-options',
			'pet-match-pro-filter-options'
		);
	}

    public function initialize_label_options() {

    global $pmpAdminInfo;			/* Admin Hover Help Text */

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
        if ( (array_key_exists('level_label_fields_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsLabels)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsLabels['level_label_filters_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {  
            $labelFieldsAdoptClass = 'pmp-label-fields-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');
        } else {
            $labelFieldsAdoptClass = 'pmp-option-disable';
        }

        /* Get Field Label Values */
        $labelsFile = 'pmp-field-values-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '.php';
        $requireFile = $this->partialsDir . $labelsFile;
        require($requireFile); 
        //echo '<pre>FIELD VALUES '; print_r($pmpFieldValuesAdopt); echo '</pre>';

		// Filters for Adopt
        add_settings_field(
            constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_label_filter_title',
            __(ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' Search Filter Labels', 'pet-match-pro-plugin'),
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
                    		$this->pmpAFAdminInfo[$labelKey],
                    		'class' => $labelFieldsAdoptClass
                		)
            		);
	            }
			}
		}

    //        ADOPT SEARCH
        add_settings_field(
            constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_label_search_title',
            __(ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' Search/Detail Result Labels', 'pet-match-pro-plugin'),
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
	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
	        	$searchLabelValue = trim($pmpFieldValuesAdopt[$searchFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = 'label_' . $labelOptionKey;
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
                    		$this->pmpAFAdminInfo[$labelKey],
                    		'class' => $labelFieldsAdoptClass
                		)
            		);
	            }
			}
		}
		
		/* FOUND LABELS */        
        if ( (array_key_exists('level_label_fields_' . constant('FOUND_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsLabels)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsLabels['level_label_filters_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {  
            $labelFieldsFoundClass = 'pmp-label-fields-' . constant('FOUND_METHODTYPE_ANIMALSFIRST');
        } else {
            $labelFieldsFoundClass = 'pmp-option-disable';
        }

        /* Get Field Label Values */
        $labelsFile = 'pmp-field-values-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '.php';
        $requireFile = $this->partialsDir . $labelsFile;
        require($requireFile); 
        //echo '<pre>FIELD VALUES '; print_r($pmpFieldValuesFound); echo '</pre>';

		// Filters for Found
        add_settings_field(
            constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_label_filter_title',
            __(ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' Search Filter Labels', 'pet-match-pro-plugin'),
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
	        	$filterLabelValue = trim($pmpFieldValuesFound[$filterFieldKey]) . ':';
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
                    		$this->pmpAFAdminInfo[$labelKey],
                    		'class' => $labelFieldsFoundClass
                		)
            		);
	            }
			}
		}

    //        FOUND SEARCH
        add_settings_field(
            constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_label_search_title',
            __(ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' Search/Detail Result Labels', 'pet-match-pro-plugin'),
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
	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
	        	$searchLabelValue = trim($pmpFieldValuesFound[$searchFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = 'label_' . $labelOptionKey;
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
                    		$this->pmpAFAdminInfo[$labelKey],
                    		'class' => $labelFieldsFoundClass
                		)
            		);
	            }
			}
		}
		
		/* LOST LABELS */        
        if ( (array_key_exists('level_label_fields_' . constant('LOST_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsLabels)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsLabels['level_label_filters_' . constant('LOST_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {  
            $labelFieldsLostClass = 'pmp-label-fields-' . constant('LOST_METHODTYPE_ANIMALSFIRST');
        } else {
            $labelFieldsLostClass = 'pmp-option-disable';
        }

        /* Get Field Label Values */
        $labelsFile = 'pmp-field-values-' . constant('LOST_METHODTYPE_ANIMALSFIRST') . '.php';
        $requireFile = $this->partialsDir . $labelsFile;
        require($requireFile); 
        //echo '<pre>FIELD VALUES '; print_r($pmpFieldValuesLost); echo '</pre>';

		// Filters for Found
        add_settings_field(
            constant('LOST_METHODTYPE_ANIMALSFIRST') . '_label_filter_title',
            __(ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' Search Filter Labels', 'pet-match-pro-plugin'),
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
                    		$this->pmpAFAdminInfo[$labelKey],
                    		'class' => $labelFieldsLostClass
                		)
            		);
	            }
			}
		}

    //        LOST SEARCH
        add_settings_field(
            constant('LOST_METHODTYPE_ANIMALSFIRST') . '_label_search_title',
            __(ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' Search/Detail Result Labels', 'pet-match-pro-plugin'),
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
	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
	        	$searchLabelValue = trim($pmpFieldValuesLost[$searchFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = 'label_' . $labelOptionKey;
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
                    		$this->pmpAFAdminInfo[$labelKey],
                    		'class' => $labelFieldsLostClass
                		)
            		);
	            }
			}
		}
		
		/* PREFERRED LABELS */        
        if ( (array_key_exists('level_label_fields_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST'), $pmpOptionLevelsLabels)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsLabels['level_label_filters_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')]) && (!empty($this->pmpLicenseKey)) ) {  
            $labelFieldsOtherClass = 'pmp-label-fields-' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
        } else {
            $labelFieldsOtherClass = 'pmp-option-disable';
        }

		$generalOptions = get_option('pet-match-pro-general-options');
		$preferredLabel = $generalOptions[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_methodtype_label'];
		if (strlen(trim($preferredLabel)) == 0) {
			$preferredLabel = ucfirst(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'));
		}	
		
        /* Get Field Label Values */
        $labelsFile = 'pmp-field-values-' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '.php';
        $requireFile = $this->partialsDir . $labelsFile;
        require($requireFile); 
        //echo '<pre>FIELD VALUES '; print_r($pmpFieldValuesPreferred); echo '</pre>';

		// Filters for Found
        add_settings_field(
            constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_label_filter_title',
            __($preferredLabel . ' Search Filter Labels', 'pet-match-pro-plugin'),
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
       	$fieldValueKeys = array_keys($pmpFieldValuesPreferred);
		//echo '<pre>FILTER VALUE KEYS<br>'; print_r($fieldValueKeys); echo '</pre>';       	
       	$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
		//echo '<pre>FILTER FIELDS<br>'; print_r($filterFieldKeys); echo '</pre>';
		
	   	if (!empty($filterFieldKeys)) {
	    	foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
	        	$labelOptionKey = trim($filterFieldKey);
//	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
	        	$filterLabelValue = trim($pmpFieldValuesPreferred[$filterFieldKey]) . ':';
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
                    		$this->pmpAFAdminInfo[$labelKey],
                    		'class' => $labelFieldsOtherClass
                		)
            		);
	            }
			}
		}

    //        PREFERRED SEARCH
        add_settings_field(
            constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_label_search_title',
            __($preferredLabel . ' Search/Detail Result Labels', 'pet-match-pro-plugin'),
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
       	$searchValueKeys = array_keys($pmpFieldValuesPreferred);
       	$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
		//echo '<pre>SEARCH FIELDS<br>'; print_r($searchFieldKeys); echo '</pre>';
		
	   	if (!empty($searchFieldKeys)) {
	    	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
	        	$searchLabelValue = trim($pmpFieldValuesPreferred[$searchFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = 'label_' . $labelOptionKey;
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
                    		$this->pmpAFAdminInfo[$labelKey],
                    		'class' => $labelFieldsOtherClass
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