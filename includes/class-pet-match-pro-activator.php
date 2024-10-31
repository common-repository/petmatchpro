<?php
class Pet_Match_Pro_Activator {

	/* Function to Initialize Admin Settings */
    public static function activate() {       
		/* Set General Settings */
		if (false == get_option('pet-match-pro-general-options')) {
			$defaultsGeneral = array(
            	'integration_partner' 													=> constant('PETPOINT'),
            	'partner_API_key' 														=> 'Enter API Key',
            	'partner_API_source' 													=> 'live',
//            	'partner_API_source' 													=> array('live' => 'Live'),
            	'on_hold_status' 														=> substr(constant('ALL'), 0, 1),
//            	'on_hold_status' 														=> array('A' => 'Either'),
            	'default_method_type'													=> 'NA',
//            	'default_method_type'													=> array('NA' => 'Not Applicable'),
            	'order_by' 																=> constant('PETPOINT_NAME'),
//            	'order_by' 																=> array('name' => 'Name'),
            	'orderby_labels' 														=> '',
            	'results_per_row' 														=> '3',
//            	'results_per_row' 														=> array('3' => '3'),
            	constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_search_icons'			=> '',
            	constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_search_icons_max' 		=> '5',
//            	constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_search_icons_max' 		=> array('5' => '5'),
				constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_thumbs_max'		=> '3',
//				constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_thumbs_max'		=> array('3' => '3'),
				constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_icons'			=> '',
            	constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_icons_max' 		=> '5',
//            	constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_icons_max' 		=> array('5' => '5'),
            	'paginate_results'														=> '',
            	'age_in_years'															=> '',
            	'ga_tracking_method' 													=> 'NA',
//            	'ga_tracking_method' 													=> array('NA' => 'Not Installed'),
            	'details_template_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> constant('ADOPT_METHODTYPE_PETPOINT') . '-default',
            	'details_template_' . constant('LOST_METHODTYPE_PETPOINT') 				=> constant('LOST_METHODTYPE_PETPOINT') . '-default',
            	'details_template_' . constant('FOUND_METHODTYPE_PETPOINT') 			=> constant('FOUND_METHODTYPE_PETPOINT') . '-default',
            	'details_page_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> '0',
            	'details_page_' . constant('LOST_METHODTYPE_PETPOINT') 					=> '0',
            	'details_page_' . constant('FOUND_METHODTYPE_PETPOINT') 				=> '0',
            	'details_page_poster' 													=> '0',
            	'search_feature_link' 													=> '',
            	'search_feature_target' 												=> '_blank',
//            	'search_feature_target' 												=> array('_blank' => 'New'),
            	'search_feature_class' 													=> '',
            	'search_feature_label' 													=> '',
            	'no_search_results_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> '<strong>No Animals Meet ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' Search Criteria.</strong>',
            	'no_search_results_' . constant('LOST_METHODTYPE_PETPOINT') 			=> '<strong>No Animals Meet ' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' Search Criteria.</strong>',
            	'no_search_results_' . constant('FOUND_METHODTYPE_PETPOINT') 			=> '<strong>No Animals Meet ' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' Search Criteria.</strong>',
            	'no_search_results_featured' 											=> '<strong>No Featured Animal Configured.</strong>',
            	'default_description' 													=> '<p>We are still learning about this pet.</p>',   
            	'location_exclusion_1'         			=> '',
            	'location_exclusion_2'         			=> '',
            	'location_exclusion_3'         			=> '',           	
            	'location_exclusion_4'         			=> '',
            	'location_exclusion_5'         			=> '',
            	'location_filter_1'						=> '',
            	'location_label_1'						=> '',
            	'location_filter_2'						=> '',
            	'location_label_2'						=> '',
            	'location_filter_3'						=> '',
            	'location_label_3'						=> '',
            	'location_filter_4'						=> '',
            	'location_label_4'						=> '',
            	'location_filter_5'						=> '',
            	'location_label_5'						=> '',
            	'location_filter_other'					=> '',
            	'location_foster'						=> '',
            	'location_shelter'						=> '',
            	'pmp_custom_css' 						=> 'pmp.new-class {text-decoration: none;}',
            	/* RescueGroups Specific Settings */
            	'organization_id'						=> 'XXXX',
            	'search_result_limit'					=> '199',
            	'sort_order'							=> 'asc',
//            	'sort_order'							=> array('asc' => 'Ascending'),
			);
        	add_option('pet-match-pro-general-options', $defaultsGeneral);
        }
        
        if (false == get_option('pet-match-pro-contact-options')) {
			$defaultsContacts = array(
				'social_share'													=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_phone' 				=> '',
            	constant('ADOPT_METHODTYPE_PETPOINT') . '_email' 				=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_link' 				=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_vanity_link' 			=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_foster_phone' 		=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_foster_email'			=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_foster_link' 			=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_foster_vanity_link' 	=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_donation_link'		=> '',
				'donation_vanity_link'											=> '',
				'volunteer_vanity_link'											=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_hours_link' 			=> '',
				constant('FOUND_METHODTYPE_PETPOINT') . '_phone'				=> '',
				constant('FOUND_METHODTYPE_PETPOINT') . '_email'				=> '',
				constant('LOST_METHODTYPE_PETPOINT') . '_phone'					=> '',
				constant('LOST_METHODTYPE_PETPOINT') . '_email'					=> '',
				'sponsor_link'													=> '',
				'sponsor_image'													=> '',
				'website_support_email' 										=> ''
			);
            add_option('pet-match-pro-contact-options', $defaultsContacts);
        }
        
        if (false == get_option('pet-match-pro-filter-options')) {
			$defaultsFilters = array(
				constant('ADOPT_METHODTYPE_PETPOINT') . '_search_criteria' 											=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY') 					=> constant('PETPOINT_NAME'),
				constant('ADOPT_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY') 					=> array('name' => 'Name'),
//            	constant('ADOPT_METHODTYPE_PETPOINT') . '_search_details' 											=> '',
				'animal_details_' . constant('ADOPT_METHODTYPE_PETPOINT') . '_search_labels' 						=> '',
				constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_details'											=> '',
				
				constant('FOUND_METHODTYPE_PETPOINT') . '_search_criteria'											=> '',
				constant('FOUND_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY') 					=> constant('PETPOINT_DATE_LAST'),
//				constant('FOUND_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY') 					=> array('datelast' => 'Date Found, Descending'),
				constant('FOUND_METHODTYPE_PETPOINT') . '_search_details'											=> '',
				'animal_details_' . constant('FOUND_METHODTYPE_PETPOINT') . '_search_labels'						=> '',
				constant('FOUND_METHODTYPE_PETPOINT') . 'animal_details'											=> '',

				constant('LOST_METHODTYPE_PETPOINT') . '_search_criteria'											=> '',
				constant('LOST_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY') 					=> constant('PETPOINT_DATE_LAST'),
//				constant('LOST_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY') 					=> array('datelast' => 'Date Lost, Descending'),
				constant('LOST_METHODTYPE_PETPOINT') . '_search_details'											=> '',
				'animal_details_' . constant('LOST_METHODTYPE_PETPOINT') . '_search_labels' 						=> '',
				constant('LOST_METHODTYPE_PETPOINT') . '_animal_details'											=> '',

				constant('LOST_METHODTYPE_PETPOINT') . '_' . constant('FOUND_METHODTYPE_PETPOINT') . '_combination' => constant('FOUND_METHODTYPE_PETPOINT') . 'Search'
			);
            add_option('pet-match-pro-filter-options', $defaultsFilters);
        }
        
        if (false == get_option('pet-match-pro-label-options')) {
        	$defaultsLabels = [];
        	
        	/****** PETPOINT *******/   
        	/* Get Default Adopt Label Values */
        	$valuesPath = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/' . constant('PARTIALS_DIR') . '/';
			$valuesFile = 'pmp-field-values-' . constant('ADOPT_METHODTYPE_PETPOINT') . '.php';
			$requireFile = $valuesPath . $valuesFile;
			require $requireFile;
//			require_once  $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT'));
			$fieldValueArray = [];
		    $fieldValueArray = $$fieldValueArrayName;
//		    $fieldValueArray = @$$fieldValueArrayName;

			$labelFilter 	= 'label_';
//			if(isset($fieldValueArray)){
			foreach ($fieldValueArray  as $labelKey => $labelValue) {
				//echo 'Processing Adopt Key ' . $labelKey . ' with Value ' . $labelValue . '<br>';
				if (str_contains($labelKey, $labelFilter)) {
					$defaultsLabels[$labelKey] = $labelValue;	
				}
			}
//			}

        	/* Get Default Found Label Values */
			$valuesFile = 'pmp-field-values-' . constant('FOUND_METHODTYPE_PETPOINT') . '.php';
			$requireFile = $valuesPath . $valuesFile;
			//echo 'Found Label Values File is ' . $requireFile . '<br>';
			require $requireFile;
//			require_once  $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT'));
			$fieldValueArray = [];
		    $fieldValueArray = $$fieldValueArrayName;
//		    $fieldValueArray = @$$fieldValueArrayName;
//			if(isset($fieldValueArray)){
			foreach ($fieldValueArray  as $labelKey => $labelValue) {
				//echo 'Processing Found Key ' . $labelKey . ' with Value ' . $labelValue . '<br>';
				if (str_contains($labelKey, $labelFilter)) {
					$defaultsLabels[$labelKey] = $labelValue;	
				}
			}
//			}
						
        	/* Get Default Lost Label Values */
			$valuesFile = 'pmp-field-values-' . constant('LOST_METHODTYPE_PETPOINT') . '.php';
			$requireFile = $valuesPath . $valuesFile;
			//echo 'Lost Label Values File is ' . $requireFile . '<br>';
			require $requireFile;
//			require_once  $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . ucfirst(constant('LOST_METHODTYPE_PETPOINT'));
			$fieldValueArray = [];
		    $fieldValueArray = $$fieldValueArrayName;
//		    $fieldValueArray = @$$fieldValueArrayName;
//			if(isset($fieldValueArray)){
			foreach ($fieldValueArray  as $labelKey => $labelValue) {
				//echo 'Processing Lost Key ' . $labelKey . ' with Value ' . $labelValue . '<br>';
				if (str_contains($labelKey, $labelFilter)) {
					$defaultsLabels[$labelKey] = $labelValue;	
				}
			}
//			}
			
        	/****** RESCUEGROUPS ******/
        	/* Get Default Adopt Label Values */
        	$valuesPath = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/' . constant('PARTIALS_DIR') . '/';
			$valuesFile = 'pmp-field-values-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '.php';
			$requireFile = $valuesPath . $valuesFile;
			//echo 'Adopt Label Values File is ' . $requireFile . '<br>';
			require $requireFile;
//			require_once  $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . ucfirst(constant('ADOPT_METHODTYPE_RESCUEGROUPS'));
		    $fieldValueArray = [];		    
		    $fieldValueArray = $$fieldValueArrayName;
			
			foreach ($fieldValueArray  as $labelKey => $labelValue) {		
				//echo 'Processing Adopt Key ' . $labelKey . ' with Value ' . $labelValue . '<br>';
				if (str_contains($labelKey, $labelFilter)) {
					$defaultsLabels[$labelKey] = $labelValue;					
				}
			}

			$labelFilter 	= 'label_';
			
			foreach ($fieldValueArray  as $labelKey => $labelValue) {
				//echo 'Processing Adopt Key ' . $labelKey . ' with Value ' . $labelValue . '<br>';
				if (str_contains($labelKey, $labelFilter)) {
					$defaultsLabels[$labelKey] = $labelValue;	
				}
			}
			
			
        	/****** ANIMALSFIRST ******/
        	/* Get Default Adopt Label Values */
        	$valuesPath = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/' . constant('PARTIALS_DIR') . '/';
			$valuesFile = 'pmp-field-values-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '.php';
			$requireFile = $valuesPath . $valuesFile;
			//echo 'Adopt Label Values File is ' . $requireFile . '<br>';
			require $requireFile;
//			require_once  $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST'));
		    $fieldValueArray = [];		    
		    $fieldValueArray = $$fieldValueArrayName;
		
//			foreach ($fieldValueArray  as $labelKey => $labelValue) {			
//				//echo 'Processing Adopt Key ' . $labelKey . ' with Value ' . $labelValue . '<br>';
//				if (str_contains($labelKey, $labelFilter)) {
//					$defaultsLabels[$defaultsLabelKey] = $labelValue;
//				}				
//			}
    
			/* Get Filter Fields from Values File */
	        $filterFields = [];
	       	$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
	       	/* Match Filter Label Keys */
	       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
	       	$fieldValueKeys = array_keys($fieldValueArray);
	       	$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
		
		   	if (!empty($filterFieldKeys)) {
	    		foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
		        	$labelOptionKey = trim($filterFieldKey);
		        	if (strlen($labelOptionKey) > 0) {
		        		$labelKey = $labelOptionKey;
		        		$filterLabelValue = trim($fieldValueArray[$filterFieldKey]);		        			        		
						$defaultsLabels[$labelKey] = $filterLabelValue;		        		
		            }
				}
			}
						
			/* Get Search/Detail Result Fields from Values File */
	        $searchFields = [];
	       	$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
	       	/* Match Search Label Keys */
	       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
	       	$searchValueKeys = array_keys($fieldValueArray);
	       	$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
			
		   	if (!empty($searchFieldKeys)) {
		    	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
		        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
		        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
		        	if (strlen($labelOptionKey) > 0) {
		        		$labelKey = 'label_' . $labelOptionKey;
			        	$searchLabelValue = $fieldValueArray[$searchFieldKey];
						$defaultsLabels[$labelKey] = $searchLabelValue;		        					        	
		            }
				}
			}

        	/* Get Default Found Label Values */
			$valuesFile = 'pmp-field-values-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '.php';
			$requireFile = $valuesPath . $valuesFile;
			//echo 'Found Label Values File is ' . $requireFile . '<br>';
			require $requireFile;
//			require_once  $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST'));
		    $fieldValueArray = [];		    
		    $fieldValueArray = $$fieldValueArrayName;
			
//			foreach ($fieldValueArray  as $labelKey => $labelValue) {			
//				//echo 'Processing Found Key ' . $labelKey . ' with Value ' . $labelValue . '<br>';
//				if (str_contains($labelKey, $labelFilter)) {
//					$defaultsLabels[$labelKey] = $labelValue;					
//				}
//			}

			/* Get Filter Fields from Values File */
	        $filterFields = [];
	       	$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
	       	/* Match Filter Label Keys */
	       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
	       	$fieldValueKeys = array_keys($fieldValueArray);
	       	$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
		
		   	if (!empty($filterFieldKeys)) {
	    		foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
		        	$labelOptionKey = trim($filterFieldKey);
		        	if (strlen($labelOptionKey) > 0) {
		        		$labelKey = $labelOptionKey;
		        		$filterLabelValue = trim($fieldValueArray[$filterFieldKey]);		        			        		
						$defaultsLabels[$labelKey] = $filterLabelValue;		        		
		            }
				}
			}
						
			/* Get Search/Detail Result Fields from Values File */
	        $searchFields = [];
	       	$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
	       	/* Match Search Label Keys */
	       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
	       	$searchValueKeys = array_keys($fieldValueArray);
	       	$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
			
		   	if (!empty($searchFieldKeys)) {
		    	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
		        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
		        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
		        	if (strlen($labelOptionKey) > 0) {
		        		$labelKey = 'label_' . $labelOptionKey;
			        	$searchLabelValue = $fieldValueArray[$searchFieldKey];
						$defaultsLabels[$labelKey] = $searchLabelValue;		        					        	
		            }
				}
			}

			
        	/* Get Default Lost Label Values */
			$valuesFile = 'pmp-field-values-' . constant('LOST_METHODTYPE_ANIMALSFIRST') . '.php';
			$requireFile = $valuesPath . $valuesFile;
			//echo 'Lost Label Values File is ' . $requireFile . '<br>';
			require $requireFile;
//			require_once  $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST'));
		    $fieldValueArray = [];		    
		    $fieldValueArray = $$fieldValueArrayName;
			
//			foreach ($fieldValueArray  as $labelKey => $labelValue) {							
//				//echo 'Processing Lost Key ' . $labelKey . ' with Value ' . $labelValue . '<br>';
//				if (str_contains($labelKey, $labelFilter)) {
//					$defaultsLabels[$labelKey] = $labelValue;					
//				}
//			}

			/* Get Filter Fields from Values File */
	        $filterFields = [];
	       	$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
	       	/* Match Filter Label Keys */
	       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
	       	$fieldValueKeys = array_keys($fieldValueArray);
	       	$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
		
		   	if (!empty($filterFieldKeys)) {
	    		foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
		        	$labelOptionKey = trim($filterFieldKey);
		        	if (strlen($labelOptionKey) > 0) {
		        		$labelKey = $labelOptionKey;
		        		$filterLabelValue = trim($fieldValueArray[$filterFieldKey]);		        			        		
						$defaultsLabels[$labelKey] = $filterLabelValue;		        		
		            }
				}
			}
						
			/* Get Search/Detail Result Fields from Values File */
	        $searchFields = [];
	       	$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
	       	/* Match Search Label Keys */
	       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
	       	$searchValueKeys = array_keys($fieldValueArray);
	       	$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
			
		   	if (!empty($searchFieldKeys)) {
		    	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
		        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
		        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
		        	if (strlen($labelOptionKey) > 0) {
		        		$labelKey = 'label_' . $labelOptionKey;
			        	$searchLabelValue = $fieldValueArray[$searchFieldKey];
						$defaultsLabels[$labelKey] = $searchLabelValue;		        					        	
		            }
				}
			}
			
        	/* Get Default Preferred Label Values */
			$valuesFile = 'pmp-field-values-' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '.php';
			$requireFile = $valuesPath . $valuesFile;
			//echo 'Preferred Label Values File is ' . $requireFile . '<br>'; 
			require $requireFile;
//			require_once  $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . ucfirst(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'));
		    $fieldValueArray = [];		    
		    $fieldValueArray = $$fieldValueArrayName;
			
//			foreach ($fieldValueArray  as $labelKey => $labelValue) {			
//				//echo 'Processing Preferred Key ' . $labelKey . ' with Value ' . $labelValue . '<br>';
//				if (str_contains($labelKey, $labelFilter)) {
//					$defaultsLabels[$labelKey] = $labelValue;					
//				}
//			}
			/* Get Filter Fields from Values File */
	        $filterFields = [];
	       	$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
	       	/* Match Filter Label Keys */
	       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
	       	$fieldValueKeys = array_keys($fieldValueArray);
	       	$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
		
		   	if (!empty($filterFieldKeys)) {
	    		foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
		        	$labelOptionKey = trim($filterFieldKey);
		        	if (strlen($labelOptionKey) > 0) {
		        		$labelKey = $labelOptionKey;
		        		$filterLabelValue = trim($fieldValueArray[$filterFieldKey]);		        			        		
						$defaultsLabels[$labelKey] = $filterLabelValue;		        		
		            }
				}
			}
						
			/* Get Search/Detail Result Fields from Values File */
	        $searchFields = [];
	       	$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
	       	/* Match Search Label Keys */
	       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
	       	$searchValueKeys = array_keys($fieldValueArray);
	       	$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
			
		   	if (!empty($searchFieldKeys)) {
		    	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
		        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
		        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
		        	if (strlen($labelOptionKey) > 0) {
		        		$labelKey = 'label_' . $labelOptionKey;
			        	$searchLabelValue = $fieldValueArray[$searchFieldKey];
						$defaultsLabels[$labelKey] = $searchLabelValue;		        					        	
		            }
				}
			}

			//echo '<pre>LABEL VALUES<br>'; print_r($defaultsLabels); echo '</pre>';
			
            add_option('pet-match-pro-label-options', $defaultsLabels);
        }
        
        if (false == get_option('pet-match-pro-color-options')) {
			$defaultsColors = array(
				'link_text'													=> '#0000FF',
				'link_text_hover'											=> '#0000FF'
			);
            add_option('pet-match-pro-color-options', $defaultsColors);
        }        
    }
}