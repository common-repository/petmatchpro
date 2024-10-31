<?php
if(!class_exists("Pet_Match_Pro_All_API")) {

	class Pet_Match_Pro_All_API {
	
		private $filterOptions;
		private $generalOptions;
		private $integrationPartner;	
		private $partialsDir;
	
	    public $PMPLicenseType;    /* To Secure Features */
	    public $PMPLicenseTypeID;  /* To Secure Features */
	    
		private $FilterAdminOptions;
					
		function __construct() {
			/* Get Option Values */
    	    $this->generalOptions = get_option('pet-match-pro-general-options');	
    	    if (is_array($this->generalOptions)) {
//    	    if ( (!is_array($this->generalOptions['integration_partner'])) && (is_array($this->generalOptions)) ) {
				if (array_key_exists('integration_partner', $this->generalOptions))   {
					$this->integrationPartner = $this->generalOptions['integration_partner'];
					//$this->integrationPartner = strval($this->integrationPartner);
					//$partnerDir = strtoupper($this->integrationPartner) . '_DIR';
				} else {
					$this->integrationPartner = constant('PETPOINT');
			        //$partnerDir = strtoupper(constant('PETPOINT')) . '_DIR';
				}    
			} else {
				$this->integrationPartner = constant('PETPOINT');
		        //$partnerDir = strtoupper(constant('PETPOINT')) . '_DIR';				
			}    	    
			//echo 'Integration Partner is ' . $this->integrationPartner . '.<br>';		
		    			
    	    $this->filterOptions 		= get_option('pet-match-pro-filter-options');
    	    
	        /* Get License Type to Secure Features */
	        $this->PMPLicenseType = get_option('PMP_License_Type');      
	        $this->PMPLicenseTypeID = (int) get_option('PMP_License_Type_ID');  
	        
	        //echo 'Option License Type ID = ' . $this->PMPLicenseTypeID . '<br>';
	        if ( $this->PMPLicenseTypeID == 0 ) {
	            $this->PMPLicenseTypeID = constant('FREE_LEVEL');
	        }
	        //echo 'License Type: ' . $this->PMPLicenseType . '(' . $this->PMPLicenseTypeID . ')<br>';          

	        $partnerDir = strtoupper($this->integrationPartner) . '_DIR';
	        //echo 'Partners Dir Constant ' . $partnerDir . '.<br>';
	       	$this->partialsDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant($partnerDir) . '/' . constant('PARTIALS_DIR') . '/';                      	    

	        $this->FilterAdminOptions = get_option('pet-match-pro-filter-options');	       	
		}

		/* Function to Create Parameters to Pass in Anchor OnClick */    
    	public function onClickValue($gaName, $gaParms) {
    	    /* Get GA Tracking Method */
    	    if ( array_key_exists('ga_tracking_method', $this->generalOptions) ) {
    	        $gaMethod = $this->generalOptions['ga_tracking_method'];
    	    } else {
    	        $gaMethod = 'NA';
    	    }
    	    //echo 'GA Tracking Method: ' . $gaMethod . '<br>';
    	    
    	    /* Configure Search Form Onclick Parameter */    
    	    if ( (isset($gaName)) && (isset($gaParms)) && ($this->PMPLicenseTypeID == constant('PREFERRED_LEVEL')) && ($gaMethod != 'NA') ) {
    	        $onClick = '';
    	        if ($gaMethod == 'GTM') {
    	            $gaCommand = 'gtag(';
    	        } else {
    	            $gaCommand = "ga('send', ";
    	        }
    	        $gaType = "'event'";
    	        if ($gaMethod == 'GTM') {
    	            $onClick .= $gaCommand . $gaType . ", '" . $gaName . "', {";
    	        } else {
    	            $onClick .= $gaCommand . $gaType . ", '" . $gaName . "', ";
    	        }
    	        foreach ($gaParms as $key=>$value) {
    	            if ($gaMethod == 'GTM') {
    	                $onClick .= "'" . $key . "': '" . $value . "', ";
    	            } elseif ( (str_contains($key, 'category')) || (str_contains($key, 'action')) || (str_contains($key, 'id')) )  {
    	                $onClick .= "'" . $value . "', ";
    	            }
    	        }
    	        /* Remove Final Comma */
    	        $onClick = substr_replace($onClick ,"",-2);
    	        if ($gaMethod == 'GTM') {
    	            $onClick .= '});';
    	        } else {
    	            $onClick .= ', 1);';
    	        }
    	        return $onClick;
    	    } else {
    	        return ' ';
    	    }
    	}
    	
    	/* Function to Create Arrays with Keys and Labels for Milti-Dimensional Arrays */
    	public function keyAndLabel($arr) {
    	    $returnArray = array();
    	    foreach ($arr as $key => $item) {
    	        $returnArray[$key] = key($item);
    	    }
    	    return $returnArray;
    	}
	
    	/* Function to Create Arrays with Keys and Labels for Milti-Dimensional Arrays */
    	public function keyAndArray($arr) {
    	    $returnArray = array();
    	    foreach ($arr as $key => $item) {
    	        $returnArray[$key] = key($item);
    	    }
    	    return $returnArray;
    	}

		/* Function to Trim & Convert Arrays Keys to Lower Case */
    	public function keyCleanUp($itemArray) {
    	    //just a little clean up
    	    foreach ($itemArray as $item) {
    	        $cleanup[] = strtolower(trim($item));
    	    }
    	    return $cleanup;
    	}
	
		/* Function to Buiuld the Search Form */
    	public function buildSearchForm($filterOptions, $filterValueArray, $submitClass, $submitOnClick, $fieldLevels) {
    	    //echo '<pre>BUILD SEARCH FORM CALLED WITH FILTER OPTIONS<br>'; print_r($filterOptions); echo '</pre>';
			//echo 'Filter Options Count = ' . count($filterOptions) . '.<br>';
    	    //echo '<pre>BUILD SEARCH FORM CALLED WITH FILTER VALUES<br>'; print_r($filterValueArray); echo '</pre>';
    	    //echo '<pre>BUILD SEARCH FORM CALLED WITH SUBMIT CLASS<br>'; print_r($submitClass); echo '</pre>';
    	    //echo 'BUILD SEARCH FORM CALLED WITH SUBMIT ON CLICK ' . $submitOnClick . '<br>';
    	    //echo '<pre>BUILD SEARCH FORM CALLED WITH FIELD LEVELS<br>'; print_r($fieldLevels); echo '</pre>';

			/* Check for Options by Removing Empty Values */
			$optionKeys = array_keys($filterOptions);
			$optionKeys = array_filter($optionKeys);
    	    //echo '<pre>FILTER OPTIONS KEYS<br>'; print_r($optionKeys); echo '</pre>';
			$optionKeysCount = count($optionKeys);
			//echo 'Filter Options Key Count = ' . $optionKeysCount . '.<br>';

			if ($optionKeysCount > 0) {
				//echo 'Filter Options is NOT Empty.<br>';
	    	    $formEmpty = new PhpFormBuilder();
        	    //echo '<pre>EMPTY FORM<br>'; print_r($formEmpty); echo '</pre>';
	    	    $form = $formEmpty;
	    	    $form->set_att('id', 'pmp-search');
	    	    $form->set_att('action', '');
	    	    $form->set_att('add_nonce', 'pet-match-pro');
	    	    $form->set_att('form_element', true);
	    	    $form->set_att('add_submit', true);
	    	    $form->set_att('submit_class', $submitClass);
	    	    $form->set_att('submit_onclick', $submitOnClick); /* Pass onclick Attribute for Submit Button */
	    	    $form->set_att('license_type', $this->PMPLicenseTypeID); /* License Type ID to Secure Field Values */
	    	    $form->set_att('field_levels', $fieldLevels); /* Search Form Filter License Level Values */
	    	    if (array_key_exists('search_feature_link', $this->generalOptions)) {
	    	    	$featureLink = trim($this->generalOptions['search_feature_link']);
	    	    } else {
	    	    	$featureLink = '';
	    	    }
	    	    if (strlen($featureLink) > 0 ) {
			        //echo 'Search Filter Link = ' . $featureLink . '<br>';        
			        $form->set_att('add_search_feature', $featureLink); /* URL to Display Feature Button Next to Submit */	  
			        $featureTarget = trim($this->generalOptions['search_feature_target']);
					if (strlen($featureTarget) == 0) {
	    	    		$featureTarget = '_blank';
	    	    	}	
	    	    	$featureClass = trim($this->generalOptions['search_feature_class']);
	    	    	if (strlen($featureClass) == 0) {
	    	    		$featureClass = 'pmp-search-feature-button';
	    	    	}	
	    	    	$featureLabel = trim($this->generalOptions['search_feature_label']);
	    	    	if (strlen($featureLabel) == 0) {
	    	    		$featureLabel = 'Not Specified';
	    	    	}	
	    	        $gaName = 'button_pmp_search_select';
	    	        $gaParamArray = array();
	    	        $gaParamArray['event_category'] = 'Button';
	    	        $gaParamArray['event_action'] = 'Select';
	    	        $gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];
	    	        $gaParamArray['click_id'] = 'pmp-search-feature-button' . $submitClass;
	    	        $gaParamArray['click_text'] = $featureLabel;
	    	        $gaParamArray['click_url'] = $featureLink;
	    	        $featureOnClick = $this->onClickValue($gaName, $gaParamArray);
			        $form->set_att('search_feature_onclick', $featureOnClick); /* Pass onclick Attribute for Feature Button */  
			        $form->set_att('search_feature_class', $featureClass); /* Pass Class for Feature Button */  
			        $form->set_att('search_feature_label', $featureLabel); /* Pass Feature Button Label */  
			        $form->set_att('search_feature_target', $featureTarget); /* Pass Target Value for Feature Button Link */  
			    }
	
	    	    //temporarily create the values array
	    	    $filterValues = [];
	    	    $filterName = [];
	    	    $filterKey = [];
	    	    foreach ($filterValueArray as $key => $itemValues) {
	    	        @$filterValues[key($itemValues)] = $itemValues[key($itemValues)];
	    	        $filterKey[$key] = @key($itemValues);
	    	    }
	    	    //echo '<pre>Filter Keys<br>'; print_r($filterKey); echo '</pre>';
	    	    //echo '<pre>Filter Values<br>'; print_r($filterValues); echo '</pre>';
	    	    
	    	    $rows = count($filterOptions)%3; /* Fiverr Fix 4-15-2023 */
	    	    if ($rows <= 1) { 
	    	        //one row
	    	        $rowCounter = 1;
	    	    }
	    	    
	    	    /***Added by Prolific for display label ***/
	    	    foreach($filterKey as $k=>$v) {
	    	        if($k=='location') {
	    	            $filterKey[$k]='Location';
	    	        }
	    	    }
	    	    /***ended by Prolific***/
		
	    	    //echo '<pre>FILTER VALUES<br>'; print_r($filterValues); echo '</pre>';
	    	    //echo '<pre>FILTER KEYS<br>'; print_r($filterKey); echo '</pre>';
	
	    	    $method = trim(substr(array_key_first($fieldLevels), strrpos(array_key_first($fieldLevels), '_'), 99));
	    	    //echo 'Method is ' . $method . '</br>';    	    
	    	    foreach ($filterOptions as $item) {
	    	    	//echo 'Processing Filter Key ' . $key . '.<br>';
	                $levelKey = constant('LEVEL_PREFIX_SEARCH_FILTER') . $item . $method;
		            //echo 'Preparing to Validate Detail with Level Key ' . $levelKey . '.<br>';
					if ( (!empty($fieldLevels)) && (array_key_exists($levelKey, $fieldLevels)) ) {	
						//echo 'Processing Level Key ' . $item . '.<br>';		
		    	        $type = 'text';
		    	        if(@is_array($filterValueArray[$item][$filterKey[$item]])) {
		    	            $type = 'select';
		    	        }
		    	        //print_r($filterValueArray[$item][$filterKey[$item]]);
		    	        $label = $filterKey[$item];
		    	        //echo 'Label Value = ' . $label . ' for Search Form Field ' . $item . '<br>';
		    	        $gaName = 'button_pmp_search_select';
		    	        $gaParamArray = [];
		    	        $gaParamArray['event_category'] = 'Button';
		    	        $gaParamArray['event_action'] = 'Select';
		    	        $gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];
		    	        $gaParamArray['click_id'] = 'pmp-search-submit-button' . $submitClass;
		    	        $gaParamArray['click_text'] = 'Submit';
		    	        $gaParamArray['click_url'] = '';
		    	        $searchOnClick = $this->onClickValue($gaName, $gaParamArray);
		    	        $form->add_input($item, array(
		    	            'type' => $type,
		    	            'options' => $filterValueArray[$item][$filterKey[$item]],
		    	            'wrap_class' => array('pmp-search-form-' . $item),
		    	            'name' => $item,
		    	            'id' => $item,
		    	            'class' => 'pmp-' . $item,
		    	            'label' => $label
		    	            )
		    	        );
		    		}
	    	    }
	    	    //echo '<pre>RETURN FORM<br>'; print_r($form); echo '</pre>';
	    	    $formArray = (array) $form;
	    	    //echo '<pre>FORM ARRAY<br>'; print_r($formArray); echo '</pre>';
	    	    $formInputs = array_values($formArray);
	    	    //echo '<pre>FORM INPUTS PRE<br>'; print_r($formInputs); echo '</pre>';
	    	    if (array_key_exists(0, $formInputs)) {
	    	    	$formInputs = $formInputs[0];
	    	    } else {
	    	    	$formInputs = [];
	    	    }
	    	    //echo '<pre>FORM INPUTS POST<br>'; print_r($formInputs); echo '</pre>';
	    	    //echo 'Form Input Count is ' . count($formInputs) . '.<br>';
	    	    if (count($formInputs) > 0) {    	    
    	    		return $form->build_form(false);
    	    	} else {
   		    	    $form->set_att('add_submit', false);
    	    		return $form->build_form(false);
    	    	}
	    	}
    	}
    	
		/* Function to Confirm if Labels Should be Displayed - Returns 'Enable' or 'false' */
    	public function showLabels($search, $details) {
    		//echo 'showLabels Called with Method ' . $search . '.<br>';
    	    $labels = 'false';   
    	    if ( (is_array($details)) && (array_key_exists('labels', $details)) ) {
    	        if ( (strtolower($details['labels']) == 'yes') or (strtolower($details['labels']) == '1') or (strtolower($details['labels']) == 'on') ) {
    	            $labels = 'Enable';
    	        } 
    	    } else {
    	        //echo '<pre>FILTER OPTIONS '; print_r($this->filterOptions); echo '</pre>';
    	        if ( is_array($this->filterOptions) ) {
    	            if ($search == 'adoptSearch') { 
    	                //echo '<br>' . 'animal_details_adopt_search_labels array key exist: ' . array_key_exists('animal_details_adopt_search_labels', $this->filterOptions) . '<br>';               
    	                if (array_key_exists('animal_details_adopt_search_labels', $this->filterOptions)) {
    	                	if (array_key_exists(0, $this->filterOptions['animal_details_adopt_search_labels'])) {
	    	                    $labels = $this->filterOptions['animal_details_adopt_search_labels'][0];
	    	                }
    	                }
					} elseif ($search == 'lostSearch') { 
    	                //echo '<br>' . 'animal_details_lost_search_labels array key exist: ' . array_key_exists('animal_details_lost_search_labels', $this->filterOptions) . '<br>';               
    	                if (array_key_exists('animal_details_lost_search_labels', $this->filterOptions)) {
    	                	if (array_key_exists(0, $this->filterOptions['animal_details_lost_search_labels'])) {    	                
    	                    	$labels = $this->filterOptions['animal_details_lost_search_labels'][0];
    	                    }
    	                }
    	            } elseif ($search == 'foundSearch') {
    	                //echo '<br>' . 'animal_details_found_search_labels array key exist: ' . array_key_exists('animal_details_found_search_labels', $this->filterOptions) . '<br>';               
    	                if (array_key_exists('animal_details_found_search_labels', $this->filterOptions)) {
    	                	if (array_key_exists(0, $this->filterOptions['animal_details_found_search_labels'])) {    	                
    	                    	$labels = $this->filterOptions['animal_details_found_search_labels'][0];
    	                    }
    	                }
    	            } elseif ($search == 'preferredSearch') {
    	                if (array_key_exists('animal_details_preferred_search_labels', $this->filterOptions)) {
    	                	if (array_key_exists(0, $this->filterOptions['animal_details_preferred_search_labels'])) {    	                
    	                    	$labels = $this->filterOptions['animal_details_preferred_search_labels'][0];
    	                    }
    	                }
    	            }
    	            //echo '<br>' . $search . ' Labels Option = ' . $labels . '<br>'; 
    	        }
    	    }
    	    return $labels;
    	}
    	
		/* Function to Return Details to Display on Search Page */
		/* $callFunc = Search Type, $details = Fields from Shortcode */
    	public function showDetails($callFunc, $details) {
			//echo 'showDetails Call Function is ' . $callFunc . '.<br>';
    		//echo '<pre>showDetails for Method ' . $callFunc . ' Called with<br>'; print_r($details); echo '</pre>';
    		
			$returnThis = [];
			/* Identify Source of Detail Request */		
            if ( is_int(strpos(strtolower($callFunc), 'search')) ) {
				$labelKey = 'search';
				$labelKeyPrefix = constant('LABEL_PREFIX_SEARCH_RESULT');
            } elseif ( is_int(strpos(strtolower($callFunc), 'detail')) ) {
				$labelKey = 'detail';
				$labelKeyPrefix = constant('LABEL_PREFIX_ANIMAL_DETAIL');				
			} else {
				$labelKey = 'search';
				$labelKeyPrefix = constant('LABEL_PREFIX_SEARCH_RESULT');
			}			

            if ( is_int(strpos(strtolower($callFunc), 'adopt')) ) {
            	$method = 'adopt';
			} elseif ( is_int(strpos(strtolower($callFunc), 'found')) ) {
            	$method = 'found';
            } elseif ( is_int(strpos(strtolower($callFunc), 'lost')) ) {
            	$method = 'lost';
            } elseif ( is_int(strpos(strtolower($callFunc), 'featured')) ) {
            	$method = 'adopt';
            } else {
            	$method = 'preferred';
            }
            //echo 'Preparing Details for Method ' . $method . '.<br>';
                		
	        if (is_array($details) && array_key_exists('details', $details)) {
	        	/* Remove White Space from Details Before Exploding */
	        	$cleanDetails = preg_replace('/\s+/','',$details['details']);
	            $ResultDetails = explode(',', $cleanDetails);
	        } else {
	            if ( is_array($this->filterOptions) ) {
	                if ($callFunc == 'lostSearch') {
	                    $ResultDetails = $this->filterOptions['lost_search_details'];
	                } elseif ($callFunc == 'lostDetails') {
	                    $ResultDetails = $this->filterOptions['lost_animal_details'];
	                } elseif ($callFunc == 'foundDetails') {
	                    $ResultDetails = $this->filterOptions['found_animal_details'];
	                } elseif ($callFunc == 'foundSearch') {
	                    $ResultDetails = $this->filterOptions['found_search_details'];
					} elseif ($callFunc == 'adoptSearch') {
	                    $ResultDetails = $this->filterOptions['animal_details_adopt_search'];
	                } elseif ($callFunc == 'adoptDetails') {
	                    $ResultDetails = $this->filterOptions['adopt_animal_details'];
					} elseif ($callFunc == 'featured') {
	                    $ResultDetails = $this->filterOptions['animal_details_adopt_search'];
					} else {
	                    $ResultDetails = $this->filterOptions['preferred_animal_details'];					
					}
	             } else {
	             		$ResultDetails = '';
	             }
	        }
	        //echo '<pre>SEARCH RESULT DETAILS<br>'; print_r($ResultDetails); echo '</pre>';
	        /* Prepare the Return Array */
	        if ( is_array($ResultDetails) ) { 
				/* Include Appropriate Value Array to Confirm Detail Request */
		        $labelsFile = 'pmp-field-values-' . $method . '.php';
		        $requireFile = $this->partialsDir . $labelsFile;
		        //echo 'Preparing to Require Values File ' . $requireFile . '<br>';
        		include $requireFile;

            	$labelValuesArrayName = 'pmpFieldValues' . ucfirst($method);
            	$labelValues = [];
            	$labelValues = $$labelValuesArrayName;
				//echo '<pre>LABEL VALUES<br>'; print_r($labelValues); echo '</pre>';				

       			/* Process Details for Return */
	            foreach ($ResultDetails as $value) {
                   	$labelKey = $labelKeyPrefix . $value . '_' . $method;
	            	//echo 'Preparing to Validate Detail with Label Key ' . $labelKey . ' as Value ' . $value . '<br>';
					if ( (!empty($labelValues)) && (array_key_exists($labelKey, $labelValues)) ) {				
	                	$returnThis[$value] = $labelValues[$labelKey];
//	                	$returnThis[$value] = $value;
	                }
	            }
	            //echo '<pre>Show Details AFTER Location Removal<br>'; print_r($returnThis); echo '</pre>';		    	
	        } else {
	            $errorKey = constant('ERROR');
	            $returnThis[$errorKey] = $errorKey;
	        }
	        //echo '<pre>SEARCH RESULT DETAILS RETURN ARRAY<br>'; echo print_r($returnThis); echo '</pre>';
	        return $returnThis;
	    }

		/* Function to Return Location Exclusion Settings */
		/* $locationsArray = Array of Numbered Location Exclusion Values */
    	public function locationExclusions() {
	        /* Set Location Exclusion Values */
	    	$locationConstantKey = strtoupper($this->integrationPartner) . '_LOCATION';
	    	//echo 'Location Constant Key is ' . $locationConstantKey . '.<br>';	        		        
	        if ( is_array($this->generalOptions) ) {
		        $locationArray = [];
	        	$keyMatch = 'location_exclusion_';
	        	$generalOptionsKeys = array_keys($this->generalOptions);
	        	/* Match Filter Keys Ending with an Integer */
	        	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\d$/';
	        	//echo 'Pattern Match is ' . $matchPattern . '.<br>';
	        	$locationKeys = preg_grep($matchPattern, $generalOptionsKeys);
	        	//echo '<pre>LOCATIONS KEYS<br>'; print_r($locationKeys); echo '</pre>';
	        	if (is_array($locationKeys)) {
	        		foreach ($locationKeys as $locationCounter=>$locationOption) {
	            		if ( array_key_exists($locationOption, $this->generalOptions) ) {
	            			$locationExclusion = $this->generalOptions[$locationOption];
	            			if (strlen($locationExclusion) > 0) {
	            				$locationArray[$locationExclusion] = $locationExclusion;
	            			}
	                		//echo 'Location Exclusion = ' . $locationExclusion . '.<br>';
	            		}
			        }
			        if (empty($locationArray)) {
			        	$locationArray = [constant($locationConstantKey)=>constant($locationConstantKey)];
					}			        
			    } else {
			        $locationArray = [constant($locationConstantKey)=>constant($locationConstantKey)];
				}			    
		    } else {
		        $locationArray = [constant($locationConstantKey)=>constant($locationConstantKey)];
			}			    
	        return $locationArray;          
        }
	    
		/* Function to Return Location Filter Settings */
		/* $locationsArray = Array of Numbered Location Filter Values & Labels */
    	public function locationFilters() {

	        $locationArray = [];
	        
	        /* Set Location Filter Values */
	        if ( is_array($this->generalOptions) ) {
	        	$keyMatch = 'location_filter_';
	        	$generalOptionsKeys = array_keys($this->generalOptions);
	        	/* Match Filter Keys Ending with an Integer */
	        	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\d$/';
	        	//echo 'Pattern Match is ' . $matchPattern . '.<br>';
	        	$locationKeys = preg_grep($matchPattern, $generalOptionsKeys);
	        	//echo '<pre>LOCATIONS KEYS<br>'; print_r($locationKeys); echo '</pre>';
	        	if (is_array($locationKeys)) {
	        		foreach ($locationKeys as $locationCounter=>$locationOption) {
	            		if ( array_key_exists($locationOption, $this->generalOptions) ) {
	            			$locationFilter = $this->generalOptions[$locationOption];
	            			if (strlen($locationFilter) > 0) {
	            				$locationArray[$locationFilter] = $locationFilter;
	            			}
	                		//echo 'Location Filter = ' . $locationFilter . '.<br>';
	            		}
	            		$labelKey = $locationOption . '_label';
			            if ( (strlen($locationFilter) > 0) && (array_key_exists($labelKey, $this->generalOptions)) ) {
			                $locationLabel = $this->generalOptions[$labelKey];
			                if (array_key_exists($locationFilter, $locationArray)) {
			                	$locationArray[$locationFilter] = $locationLabel;
			                }
			                //echo 'Location Label = ' . $locationLabel . '.<br>';
			            }
			        }
			    }
			}
        	//echo '<pre>LOCATIONS ARRAY<br>'; print_r($locationArray); echo '</pre>';
	        return $locationArray;          
        }
	    
		/* Function to Return Location Value */
		/* $locationsArray = Array of Numbered Location filter Values & Labels, $locationOther = String with Other Location Value, $locationValue = String with Location Value to Process */
    	public function locationLabel($locationsArray, $locationOther, $locationValue) {
    		$locationSet = 0;
    		//echo '<pre>LOCATION VALUE<br>'; print_r($locationValue); echo '</pre>';
    		if ( (sizeof($locationsArray) == 0) && (strlen($locationValue) > 0) )  {
				$locationSet = 1;
				return $locationValue;
			} else {
	       		if ( (is_array($locationsArray)) && (!is_array($locationValue)) && (isset($locationValue)) ) {
	   				$locationValue = strval($locationValue);
	       			if (strlen($locationValue) > 0) {      			
	 	      			//echo 'Location Function Called with Value ' . $locationValue . '.<br>';
	 	      			foreach ($locationsArray as $location=>$label) {
							if ( (strpos($locationValue, $location) === 0) ) {
								//echo 'Matched Location ' . $location . ' with strpos Value ' . strpos($locationValue, $location) . '.<br>';
		  					    $locationSet = 1;
	 		           	        if ( strlen($label) > 0 ) {
	 		           	        	return $label;
	 		           	        } else {
	 		           	        	return $location;
	 		           	        }
							}
						}
					} elseif (isset($locationOther)) {
					    $locationSet = 1;
						if (strlen($locationOther) > 0)  {
							return $locationOther;
						} else {
							return constant('ERROR');
						}
		        	} else {
		        		return constant('EMPTY_VALUE');
		        	}
				}
	            if ( ($locationSet == 0) && (isset($locationOther)) ) {
	       			$locationOther = strval($locationOther);            
	            	if (strlen($locationOther) > 0) {
		        		return $locationOther;
		        	} else {
						return constant('ERROR');
					}	        	
		       	} elseif (strlen($locationValue) > 0) {
		       		return $locationValue;
		       	} else {
	       	      	return constant('EMPTY_VALUE');
		        }
			}
       	}
       	
		/* Function to Replace Detail Shortcodes with Values */
		/* $resultDetails = Array of potential shortcode details, $animalDetails = Array of animal result values, $inputString = String containing details shortcodes to replace. */
    	public function replaceDetailShortcodes ($resultDetails, $animalDetails, $inputString) {
        	//echo '<pre>Replace Shortcode Called with RESULT DETAILS<br>'; print_r($resultDetails); echo '</pre>';        
        	//echo '<pre>Replace Shortcode Called with ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';        
        	//echo '<pre>Replace Shortcode Called with INPUT<br>'; echo($inputString); echo '</pre>';        

			/* Populate Arrays Used to Process Inputs */
			$counterKW = 0;
			foreach ($resultDetails as $detailKey =>$detailValue) {
				if (!is_array($detailValue)) {
	            	//echo 'Updating KW Replacement Arrays for Counter ' . $counterKW . ' with Key ' . $detailKey . '<br>';
	                $keyword = 'pmp-detail ' . strtolower($detailKey);
	                $keywordReplace[$counterKW] = $keyword;
					if (array_key_exists($detailKey, $animalDetails)) {
		               	$keywordReplaceValues[$counterKW] = $animalDetails[$detailKey];
	                } else {
	                	$keywordReplaceValues[$counterKW] = constant('ERROR');
	                }
	                $counterKW = $counterKW + 1;
	            }
	        }

			//echo '<pre>KEYWORD REPLACE<br>'; print_r($keywordReplace); echo '</pre>';
			//echo '<pre>KEYWORD REPLACE VALUES<br>'; print_r($keywordReplaceValues); echo '</pre>';
   	        foreach($keywordReplace as $replaceKey=>$replaceValue) {
   	        	//echo 'Processing Key ' . $replaceKey . ' with Value ' . $replaceValue . '<br>';
				if (is_string($replaceValue)) {              	
					if (strlen(trim($replaceValue)) > 0) {
	                	$detailValue = substr($replaceValue, strpos($replaceValue, ' ') + 1, strlen($replaceValue)); 
	                	//echo 'Detail Value is ' . $detailValue . '<br>';
	                	if ($this->integrationPartner == constant('PETPOINT')) {
	                		$description = constant('PETPOINT_DESCRIPTION');
	                	} elseif ($this->integrationPartner == constant('RESCUEGROUPS')) {
	                		$description = constant('RESCUEGROUPS_DESCRIPTION');
	                	} elseif ($this->integrationPartner == constant('ANIMALSFIRST')) {
	                		$description = constant('ANIMALSFIRST_DESCRIPTION');
	                	} else {
	                		$description = constant('ERROR');
	                	}	                	
	                	if ($detailValue != $description) { 
	                		//echo 'Detail Value is NOT a Description.<br>';
	                  		if (is_string($keywordReplaceValues[$replaceKey])) {
	                  			echo 'Output Value ' . $replaceValue . ' Replaced with ' . $keywordReplaceValues[$replaceKey] . '<br>';
	                       		$outputString = @str_replace($replaceValue, $keywordReplaceValues[$replaceKey], $inputString); 
							}
							} else {
	                			//echo 'Detail Value IS a Description.<br>';
		                  		//echo 'Output Set to Input ' . $inputString . '<br>';									
	                      		$outputString = @$inputString; 
	                    }
		           	}
				}
			}
			return $outputString;
		}
		
		/* Function to Sort Input Array */
		/* $array = Array to be sorted, $key = Array key for use in sorting $array, $sortOrder = adc(ending) or desc(ending). */
    	public function sortArrayByKey ($array, $key, $sortOrder) {
        	//echo '<pre>SORT FUNCTION Called with ARRAY<br>'; print_r($array); echo '</pre>';        
        	//echo '<pre>SORT FUNCTION Called with KEY<br>'; print_r($key); echo '</pre>';        
        	//echo '<pre>SORT FUNCTION Called with ORDER<br>'; echo($sortOrder); echo '</pre>';
					
			if(isset($_POST[constant('ANIMALSFIRST_ORDERBY')])){
				$sortField = $_POST[constant('ANIMALSFIRST_ORDERBY')];
			} else {
				$sortField = $key;
			}
			
		    // Extract the values of the specified sort field into a separate array
		    if ($key != 'random') {	    
			    if (strlen($sortField) > 0) {
					$sortArray = array_column($array, $sortField);
				}
			}

			// Determine the Sort Order
		    if ($key != 'random') {	    
				if (is_array($sortArray)) {
					if ($sortOrder == 'asc') {
						// Sort the array in ascending order based on the specified field
						array_multisort($sortArray, SORT_ASC, $array);
					} elseif ($sortOrder == 'desc') {
						// Sort the array in descending order based on the specified field
						array_multisort($sortArray, SORT_DESC, $array);
					}
				} else {
					if ($sortOrder == 'asc') {
						// Sort the array in ascending order 
						array_multisort($array, SORT_ASC);
					} elseif ($sortOrder == 'desc') {
						// Sort the array in descending order 
						array_multisort($array, SORT_DESC);
					}
				}
			} else {
				shuffle($array);
			}
			
			// Return the Sorted Array
			return $array;
		}
		
		/* Function to Assign Method Related Variables */
		/* $callMethod = Search or Detail Method to be Evaluated, $items = Parameters Provided to the Method. */
		/* Returns array with the Value: 
		*/
    	public function callMethod_Parameters ($callMethod, $items) {
        	//echo 'Call Method Parameters Called with Method ' . $callMethod . '.<br>';
        	//echo '<pre>Call Method Called with Items<br>'; print_r($items); echo '</pre>';

			$integrationPartner = $this->generalOptions['integration_partner'];
			//echo 'Integration Partner is ' . $integrationPartner . '.<br>';
			$integrationPartnerUpper = strtoupper($integrationPartner);
			$typeConstant = $integrationPartnerUpper . '_TYPE';
			
			/* Include Default Type Values */
       		$valuesFile = 'pmp-field-values.php';
       		$requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . '/' . constant($integrationPartnerUpper . '_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $valuesFile;
       		include($requireFile);     
       		//echo '<pre>FIELD VALUES '; print_r($pmpFieldValues); echo '</pre>';
       		
       		if (array_key_exists('value_types_other', $pmpFieldValues)) {
       			$otherTypes = $pmpFieldValues['value_types_other'];
       		} else {
       			$otherTypes = [];
       		}

			$methodParms = [];    
			$methodParms['callmethod'] = $callMethod;    	         
            if ( $callMethod == 'adoptSearch' ) {
				$methodParms['method'] = 'adopt';
				$methodParms['type'] = 'adopt';
            } elseif ( $callMethod == 'foundSearch' ) {
				$methodParms['method'] = 'found';
				$methodParms['type'] = 'found';
			} elseif ( $callMethod == 'lostSearch' ) {
				$methodParms['method'] = 'lost';
				$methodParms['type'] = 'lost';
			} elseif ( $callMethod == 'lost_foundSearch' ) {
				if (array_key_exists('lost_found_combination', $this->FilterAdminOptions)) {
					$combination = $this->FilterAdminOptions['lost_found_combination'];
					if ($combination == 'foundSearch') {
						$methodParms['method'] = 'found';
						$methodParms['type'] = 'found';						
					} else {
						$methodParms['method'] = 'lost';
						$methodParms['type'] = 'lost';						
					}
				} else {
					$methodParms['method'] = 'found';
					$methodParms['type'] = 'found';											
				}
			} elseif ( $callMethod == 'petSearch' ) { 
				if ($integrationPartner == constant('ANIMALSFIRST')) {
					if (isset($_POST[constant($typeConstant)])) {
						//echo 'Processing Post Value ' . $_POST[constant($typeConstant)] . '.<br>';
						$typeValue = sanitize_text_field($_POST[constant($typeConstant)]);
						if (array_key_exists($typeValue, $pmpFieldValues['value_types'])) {                    		
							if ($typeValue == 'available') {
								$methodParms['method'] = 'adopt';
							} else {
								$methodParms['method'] = sanitize_text_field($_POST[constant($typeConstant)]);
							}
						} else {
							$methodParms['method'] = 'adopt';
						}
					} else {
						if (array_key_exists('default_method_type', $this->generalOptions)) {
							$defaultType = $this->generalOptions['default_method_type'];
						} else {
							$defaultType = 'available';
						}
						if ($defaultType == 'available') {
							$defaultMethod = 'adopt';
						} else {
							$defaultMethod = strtolower($defaultType);
						}	
						//echo 'Default Type is ' . $defaultType . '.<br>';				
						if ( (is_array($items)) && (!empty($items)) ) {
							if (array_key_exists(constant($typeConstant), $items)) {
								if ( strlen($items[constant($typeConstant)]) > 0 ) {
									$typeValue = $items[constant($typeConstant)];
									//echo 'Type ' . $typeValue . ' Exists in Items.<br>';
									if (array_key_exists($typeValue, $pmpFieldValues['value_types'])) { 
										//echo 'Processing Type Value ' . $typeValue . '.<br>';
										if ($typeValue == 'available') {
											$methodParms['method'] = 'adopt';
											$methodParms['type'] = 'available';										                    			
										} else {
											if ($typeValue == 'lost_found') {
												$methodParms['type'] = 'lost_found';
												if (array_key_exists('lost_found_combination', $this->FilterAdminOptions)) {
													$combination = $this->FilterAdminOptions['lost_found_combination'];
													if ($combination == 'foundSearch') {
														$methodParms['method'] = 'found';
														$methodParms['type'] = 'lost_found';										                    			
													} else {
														$methodParms['method'] = 'lost';
														$methodParms['type'] = 'lost_found';										                    																	
													}
												} else {
													$methodParms['method'] = 'found';
													$methodParms['type'] = 'lost_found';										                    																
												}
											} elseif ($typeValue == 'other') {
													$methodParms['method'] = 'adopt';
													$methodParms['type'] = 'other';	
											} else {							                    																
												$otherTypes = $pmpFieldValues['value_types_preferred'];
												if (array_key_exists($typeValue, $otherTypes)) {
													$methodParms['method'] = 'preferred';	
													$methodParms['type'] = $items[constant('ANIMALSFIRST_TYPE')]; 
												} else {
													$methodParms['method'] = $defaultMethod;
													$methodParms['type'] = $items[constant('ANIMALSFIRST_TYPE')]; 
												}   
											}
										}
									} else {
										$methodParms['method'] = $defaultMethod;
										$methodParms['type'] = $defaultType;                    																									
									}
								} else {
									$methodParms['method'] = $defaultMethod;
									$methodParms['type'] = $defaultType;                    																									
								}
							} else {
								$methodParms['method'] = $defaultMethod;
								$methodParms['type'] = $defaultType;                    																	
							}
						} else {
								$methodParms['method'] = $defaultMethod;
								$methodParms['type'] = $defaultType;                    		
						}
					}
					if (array_key_exists('type', $methodParms)) {
						if ($methodParms['type'] == 'outcome') {
							$methodParms['status'] = '&status=Adopted';
						} else {
							$methodParms['status'] = '';
						}
					} else {
						$methodParms['status'] = '';
					}	   
				}
			} else {
				$methodParms['method'] = 'adopt';
				$methodParms['type'] = 'available';
			}
		//echo '<pre>Method Parameters<br>'; print_r($methodParms); echo '</pre>';		
		return $methodParms;
		}
		
		/* Function to Calculate Age in Years and Months */
		/* $inputDate = Date to Convert to String in MM/DD/YYYY. */
		/* Returns String w/ Age in Years & Months 
		*/
    	public function ageInYears ($birthDate) {
    		if ( (!empty($birthDate)) && ($birthDate != constant('EMPTY_VALUE')) ) {
    			if (!is_integer($birthDate)) {
					$today = date_create("now");
		        	$interval = date_diff($today, $birthDate);
		        	$months = ($interval->y * 12) + $interval->m;
		        } else {
		        	$months = $birthDate;
		        }
		        //echo 'Animal Age in Months = ' . $months . '<br>';                    
		        if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
		          	//echo 'Processing Birth Date<br>';
		            if ($months < 12) {
						//echo 'Less Than 12 Month<br>';
		                return $months . ' Month(s)';
		            } else {
		              	//echo 'Over 12 Months<br>';
		                return round($months / 12) . ' Year(s)';
		            }
		        }
			} else {
				return constant('ERROR');
			}
		}
    }		
}
?>