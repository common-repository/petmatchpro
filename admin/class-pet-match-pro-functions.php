<?php
if(!class_exists("PetMatchProFunctions")) {

	class PetMatchProFunctions {
	
		private $partialsDir;
		private $integrationPartner;
		private $filterAdminOptions;
		private $PMPLicenseTypeID;
		private $generalOptions;
		private $orderbyLabels;
		
	    private $allAPIFunction;			
        private $methodValue;
	    private $typeValue;
	    private $statusValue;
			
		function __construct() {
        	$this->PMPLicenseTypeID = (int) get_option('PMP_License_Type_ID');  
        
	        //echo 'Option License Type ID = ' . $this->PMPLicenseTypeID . '<br>';
	        if ( $this->PMPLicenseTypeID == 0 ) {
	            $this->PMPLicenseTypeID = constant('FREE_LEVEL');
	        }

			/* Identify Integration Partner */			
        	$generalOptions = get_option('pet-match-pro-general-options');
			if (is_array($generalOptions)) {
				if ( (array_key_exists('integration_partner', $generalOptions)) ) {
					$this->integrationPartner = $generalOptions['integration_partner'];
				} else {
					$this->integrationPartner = constant('PETPOINT');
				}
			} else {
				$this->integrationPartner = constant('PETPOINT');
			}			
        	//echo 'Integration Partner = ' . $integrationPartner . '<br>';
        	
        	$partnerDir = '';
        	if ($this->integrationPartner == constant('PETPOINT')) {
        		$partnerDir = constant('PETPOINT_DIR');
        	} elseif ($this->integrationPartner == constant('RESCUEGROUPS')) {
        		$partnerDir = constant('RESCUEGROUPS_DIR');
        	} elseif ($this->integrationPartner == constant('ANIMALSFIRST')) {
        		$partnerDir = constant('ANIMALSFIRST_DIR');
        	}
			$this->partialsDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . $partnerDir . '/' . constant('PARTIALS_DIR') . '/';
			
			$this->filterAdminOptions = get_option('pet-match-pro-filter-options');
			
	       	$this->generalOptions = get_option('pet-match-pro-general-options');
	       	
			/* Initialize Switch Controlling Order by Value Labels */	
			if (is_array($this->generalOptions)) {      	
		       	if ( array_key_exists('orderby_labels', $this->generalOptions) ) {
			       	$this->orderbyLabels = $this->generalOptions['orderby_labels'];
			    } else {
					$this->orderbyLabels = '';		    
			    }
		    } else {
				$this->orderbyLabels = '';		    
		    }
			    		    
	        /* Include Class Defining Functions for All APIs */
    	    $functionsFile = 'class-pet-match-pro-all-api.php';
    	    require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . $functionsFile;      
    	    $this->allAPIFunction = new Pet_Match_Pro_All_API();
		}
		
		public function Filter_Option_Values($method, $PMPLicenseTypeID, $levelPrefix, $labelPrefix) {
			//echo ucwords($method) . ' Filter Option Values Called with<br>'; echo 'Level Prefix: ' . $levelPrefix . '<br>'; echo 'Label Prefix: ' . $labelPrefix . '<br><br>';
	       	//echo $method . " License Type ID = " . $PMPLicenseTypeID . '<br>';
	       	if ( $PMPLicenseTypeID == 0 ) {
	       		$PMPLicenseTypeID = constant('FREE_LEVEL');
	       	}

	       	$methodLower = strtolower($method);
	       	$methodUpper = strtoupper($method);
	       	$methodMixed = ucwords($method);
	       	//echo 'Search Filter ' . $methodMixed . " License Type ID = " . $PMPLicenseTypeID . '<br>';
	
			/* Get Field Values */
		    $valuesFile = 'pmp-field-values-' . $methodLower . '.php';
		   	$requireFile = $this->partialsDir . $valuesFile;
		    require $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . $methodMixed;
		    $fieldValueArray = $$fieldValueArrayName;
		    //echo '<pre> ADMIN ' . strtoupper($method) . ' VALUES '; print_r($fieldValueArray); echo '</pre>';
		    
			//$PMPLicenseTypeID = 3; 
			
			/* Get Field Visibility Levels by License Type */
		    $levelsFile = 'pmp-field-levels-' . $methodLower . '.php';
		   	$requireFile = $this->partialsDir . $levelsFile;
		    require $requireFile;
		    $fieldLevelArrayName = 'pmpFieldLevels' . $methodMixed;
		    $fieldLevelArray = $$fieldLevelArrayName;
		    //echo '<pre> ADMIN ' . $methodUpper . ' LEVEL VALUES '; print_r($fieldLevelArray); echo '</pre>';

	        /* Obtain Custom Search Labels */
	        $labelOptions = get_option('pet-match-pro-label-options');
	       	//echo '<pre>FILTER OPTION LABELS<br>'; print_r($labelOptions); echo '</pre>';

			$workingArray = [];
			$fieldArray = [];
			$methodSuffix = '_' . $methodLower;			
			
			//$PMPLicenseTypeID = 3;

			/* Get Appropriate Label Keys as Array */
			$labelKeys = array_keys($fieldValueArray);			
			//echo '<pre>Label Keys<br>'; print_r($labelKeys); echo '</pre>';
			$selectedLabelKeys = preg_grep("/$labelPrefix/i", $labelKeys);
			//echo '<pre>Selected Label Keys<br>'; print_r($selectedLabelKeys); echo '</pre>';

			foreach ($selectedLabelKeys as $index => $labelKey) {
	           		$arrayKey = str_replace($labelPrefix, '', $labelKey);
	           		$arrayKey = str_replace($methodSuffix, '', $arrayKey);
	           		//echo 'Processing Label ' . $arrayKey . '.<br>';
	           		if (is_int(strpos($labelPrefix, 'filter'))) {
	           			$labelOptionKey = $labelKey;
	           		} else {
	           			$labelOptionKey = 'label_' . $arrayKey . $methodSuffix;
	           		}
	           		//echo 'Label Option Key is ' . $labelOptionKey . '.<br>';
	           		$levelKey = $levelPrefix . $arrayKey . $methodSuffix;
	           		if ( (array_key_exists($levelKey, $fieldLevelArray)) && (array_key_exists($labelKey, $fieldValueArray)) ) {
	           			$levelValue = $fieldLevelArray[$levelKey];
	           			if ( is_array($labelOptions) ) { 
	           				if ( (array_key_exists($labelOptionKey, $labelOptions)) && (strlen($labelOptions[$labelOptionKey]) > 0) && ($PMPLicenseTypeID <= $levelValue) ) {
	           					if (strlen(trim($labelOptions[$labelOptionKey])) > 0) {
	           						$fieldKey = $labelOptions[$labelOptionKey];
	           					} else {
	           						$fieldKey = $fieldValueArray[$labelKey];
	           					}
	           				} else {
	           					$fieldKey = $fieldValueArray[$labelKey];
	           				} 
	           			} else {
	           				$fieldKey = $fieldValueArray[$labelKey];
	           			} 
	           		} else {
	           			$fieldKey = $fieldValueArray[$labelKey];
	           		}
	           		//echo 'Setting Value Key ' . $fieldKey . ' with Label Key ' . $labelKey . '.<br>';
	           		$fieldArray[$fieldKey] = $fieldValueArray[$labelKey];
	           		$workingArray[$arrayKey] = $fieldArray;
	           		$fieldArray = [];
			}
	       	//echo '<pre>' . $methodUpper . ' Filter Option Values<br>'; print_r($workingArray); echo '</pre>';

			return $workingArray;
		}     
		
		public function Search_Filter_Values($method, $PMPLicenseTypeID) {
	       	//echo "Search Filter Values Called with Method " . $method . '.<br>';
	       	//echo "License Type ID = " . $PMPLicenseTypeID . '.<br>';
	       	if ( $PMPLicenseTypeID == 0 ) {
	       		$PMPLicenseTypeID = constant('FREE_LEVEL');
	       	}
	       	//echo "License Type ID = " . $PMPLicenseTypeID . '<br>';

	       	$methodLower = strtolower($method);
       		$adminMethodPrefix = $methodLower . '_';
	       	$methodUpper = strtoupper($method);
	       	$methodMixed = ucwords($method);

			//$PMPLicenseTypeID = 3; 
	
			/* Get Filter Fields */
			$filterFields = $this->Filter_Option_Values($method, $PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER'));
			//echo '<pre>' . $methodUpper . ' Filter Values<br>'; print_r($filterFields); echo '</pre>';
			
			/* Get Method Field Values */
		    $valuesFile = 'pmp-field-values-' . $methodLower . '.php';
		   	$valuesFile = $this->partialsDir . $valuesFile;
		    require $valuesFile;
	   	    $fieldValueArrayName = 'pmpFieldValues' . $methodMixed;
		    $fieldValueArray = $$fieldValueArrayName;    
		    //echo '<pre> ' . $methodUpper . ' FIELD VALUES '; print_r($fieldValueArray); echo '</pre>';

			$filterValues = [];
			foreach ($filterFields as $filterKey => $filterLabel) {
				$labelKey = (string)array_key_first($filterLabel);
				//echo 'Processing Filter Key ' . $filterKey . ' with Label ' . $labelKey . '.<br>';
				$valueKey = constant('VALUE_PREFIX_SEARCH_FILTER') . $filterKey . '_' . $methodLower;
				//echo 'Value Key is ' . $valueKey . '.<br>';
				if ( (array_key_exists($valueKey, $fieldValueArray)) && (!empty($fieldValueArray[$valueKey])) ) {
					//echo 'Preparing to Update Key ' . $labelKey . ' with Label ' . $labelString . ' with Value<br>';
					//echo '<pre><br>'; print_r($fieldValueArray[$valueKey]); echo '</pre>';
					$filterValues[$filterKey][$labelKey] = $fieldValueArray[$valueKey];
				} else {
					$filterValues[$filterKey][$labelKey] = $filterFields[$filterKey][$labelKey];
				}
			}
			//echo '<pre>' . $methodUpper . ' Filter Values AFTER Value Processing<br>'; print_r($filterValues); echo '</pre>';

	        /* Get Labels Values Configured in Admin */
	        $labelOptions = get_option('pet-match-pro-label-options');
		    //echo '<pre> LABEL OPTIONS<br>'; print_r($labelOptions); echo '</pre>';
		    
			/* Initialize Filter Keys & Labels */
			$methodSuffix = '_' . $methodLower;
			$filterPrefix = constant('LABEL_PREFIX_SEARCH_FILTER');
			$filterKeys = [];
			$filterValues = [];
			foreach ($fieldValueArray as $key => $value) {
				if ( (is_numeric(strpos($key, $filterPrefix))) ) {
					//echo 'Processing Filter Key ' . $key . ' with Value ' . $value . '<br>';
	           		$arrayKey = str_replace($filterPrefix, '', $key);
	           		$arrayKey = str_replace($methodSuffix, '', $arrayKey);
	           		//echo 'Array Key is ' . $arrayKey . '.<br>';
					//$filterKeys[] = $arrayKey;
					
	           		if ( is_array($labelOptions) ) { 					
						if ( (array_key_exists($key, $labelOptions)) ) {
							//echo 'Label Option Exists <br>';
							if (strlen(trim($labelOptions[$key])) > 0) {
								$keyLabel = $labelOptions[$key];
							} else {
								$keyLabel = $value;							
							}
						} else {
							//echo 'Label Option Does NOT Exist <br>';
							$keyLabel = $value;
						}
					} else {
						//echo 'Label Option Does NOT Exist <br>';
						$keyLabel = $value;
					}
					//echo 'Key Label = ' . $keyLabel . '<br>';
					$valueKey = constant('VALUE_PREFIX_SEARCH_FILTER') . $arrayKey . '_' . $methodLower;
					//echo 'Value Key is ' . $valueKey . '.<br>';
					if ( array_key_exists($valueKey, $fieldValueArray) ) {
					//if ( array_key_exists($valueKey, $pmpFieldValues) ) {
						//echo '<pre>Processing ' . $key . ' as ' . $arrayKey . ' with Label ' . $keyLabel . ' as General Value<br>'; print_r($fieldValueArray[$valueKey]); echo '</pre>';
						$filterValues[$arrayKey][$keyLabel] = $fieldValueArray[$valueKey];	
					} else {
						$valueKey = $valueKey . $methodSuffix;
						//echo 'Value Key is ' . $valueKey . '.<br>';
						//echo '<pre>Processing ' . $key . ' as ' . $arrayKey . ' with Label ' . $keyLabel . ' as Method Value<br>'; print_r($fieldValueArray[$valueKey]); echo '</pre>';					
						if (array_key_exists($valueKey, $fieldValueArray)) { 						
							$filterValues[$arrayKey][$keyLabel] = $fieldValueArray[$valueKey];
						}
					}
				}
			}		
			//echo '<pre>Filter Values AFTER Label Processing<br>'; print_r($filterValues); echo '</pre>';

			return $filterValues;
		}
		
		/** Callback Functions **/
	    public function checkbox_element_callback($args) {
	        /*
	         * 0 - element
	         * 1 - element key
	         * 2 - value array
	         * 3 - title
	         * class - table row class
	         */
	        //echo '<pre>Checkbox Callback Argumants<br>'; print_r($args); echo '</pre>';
	        $options = get_option($args[0], []);
	        $to_be_checked = isset($options[$args[1]]) ? (array)$options[$args[1]] : [];
	        $html = '';
	        foreach ($args[2] as $key => $value) {
	            $toCheck = $key;
	            if (is_numeric($key)) {
	                $toCheck = $value;
	            }
	            if (in_array($toCheck, $to_be_checked)) {
	                $check = 'checked';
	            } else {
	                $check = '';
	            }
	            //if non-multi dimensional array
	            if (is_numeric($key)) {
	                $html .= '<div class="gk-pets-checkbox-wrapper"><input type="checkbox" id="' . $args[0] . '[' . $args[1] . '][]" title="' . $args[3] . '" name="' .  $args[0] . '[' . $args[1] . '][]" value="' . $value . '" ' . $check . ' />';
	                $html .= '&nbsp;<label for="' . $args[0] . '[' . $args[1] . ']">' . $value . '   </label></div>';
	            } else {
	                $html .= '<div class="gk-pets-checkbox-wrapper"><input type="checkbox" id="' . $args[0] . '[' . $args[1] . '][]" title="' . $args[3] . '" name="' . $args[0] . '[' . $args[1] . '][]" value="' . $key . '" ' . $check . ' />';
	                $html .= '&nbsp;<label for="' . $args[0] . '[' . $args[1] . ']">' . $value . '   </label></div>';
	            }
	        }
	        echo $html;
	    }

	    public function html_callback($args) {
	        //echo '<pre>HTML Callback Argumants<br>'; print_r($args); echo '</pre>';
	        if ( ($args[2]) && (!empty($args[2]))  ) {
	            echo '<div id="' . $args[1] . '">';
	            if ( is_wp_error( $args[2] ) ) {
	                $error_string = $args[2]->get_error_message();
	                echo $error_string ;
	            } else {
	                echo $args[2];
	            }
	            //echo '<div id="' . $args[1] . '" title="' . $args[2] . '">';
	        } else {
	            echo '<div id="' . $args[1] . '">';
	            echo $args[1];          
	        }   
	        //echo $options[$args[1]];
	        echo '</div> <!-- ' . $args[1] . ' -->';
	    } 

	    public function input_element_callback($args) {
	        //echo '<pre>Input Callback Argumants<br>'; print_r($args); echo '</pre>';
	        $options = get_option($args[0]);
	        //echo "<pre>INPUT CALLBACK CALLED WITH ARGUMENT ZERO OPTIONS<br>";print_r($options); echo "</pre>";
	        if (is_array($options)) {
	        	if (array_key_exists($args[1], $options)) {
		            $inputValue = $options[$args[1]];
		        } else {
		        	$inputValue = '';
		        }	
	        } else {
	            $inputValue = '';
	        }
	        if ( ($args[2]) ) {
	            echo '<input type="text" id="' . $args[1] . '" title="' . $args[2] . '" name="' . $args[0] . '[' . $args[1] . ']" value="' . $inputValue . '" />';
	        } else {
	            echo '<input type="text" id="' . $args[1] . '" name="' . $args[0] . '[' . $args[1] . ']" value="' . $inputValue . '" />';
	        }   
	    } // end input_element_callback
    
	    public function radio_element_callback($args) {
	        //echo '<pre>Radio Callback Argumants<br>'; print_r($args); echo '</pre>';
	        $options = get_option($args[0]);
	        $html = '<input type="radio" id="radio_example_one" name="' . $args[0] . '[' . $args[1] . ']" value="1"' . checked(1, $options['radio_example'], false) . '/>';
	        $html .= '&nbsp;';
	        $html .= '<label for="radio_example_one">Option One</label>';
	        $html .= '&nbsp;';
	        $html .= '<input type="radio" id="radio_example_two" name="pet-match-pro-license[radio_example]" value="2"' . checked(2, $options['radio_example'], false) . '/>';
	        $html .= '&nbsp;';
	        $html .= '<label for="radio_example_two">Option Two</label>';
	        echo $html;
	    } // end radio_element_callback

	    public function select_element_callback($args) {
	    	//echo '<pre>Select Callback Arguments<br>'; print_r($args); echo '</pre>';
	        $options = get_option($args[0]);
	        if ( ($args[3]) ) {
	            $html = '<select id="' . $args[1] . '" title="' . $args[3] .  '" name="' . $args[0] . '[' . $args[1] . ']">';
	        } else {
	            $html = '<select id="' . $args[1] . '" name="' . $args[0] . '[' . $args[1] . ']">';
	        }
	        //print_r($args[2]);
	        foreach ($args[2] as $key => $value) {
	        	if (isset($options[$args[1]])) {
	        		$selectedValue = $options[$args[1]];
	        	} else {
	        		$selectedValue = '';
	        	}
	            $html .= '<option value="' . $key . '"' . selected($selectedValue, $key, false) . '>' . __($value, 'pet-match-pro-plugin') . '</option>';
	        }
	        $html .= '</select>';
	        echo $html;
	    } 
	    
	    public function textarea_element_callback($args) {
	    	//echo '<pre>Text Area Callback Arguments<br>'; print_r($args); echo '</pre>';
	        $options = get_option($args[0]);
	        $html = '';
	        if (isset($args[2])) {
	            $html .= '<label for="' . $args[1] . '">' . $args[2] . '</label>';
	            $html .= '&nbsp;';
	        }
	
			if (isset($options[$args[1]])) {
				$textAreaValue = $options[$args[1]];
			} else {
				$textAreaValue = '';
			}
	        $html .= '<textarea id="' . $args[1] . '" name="' . $args[0] . '[' . $args[1] . ']" rows="5" cols="50">' . $textAreaValue . '</textarea>';
	        echo $html;
	    } 
	    
	    public function title_element_callback() {
        	return;
		}
		
		public function filter_options_callback() {
			//$options = get_option('pet-match-pro-filter-options');
			return '<p>' . __( 'Integration Partner Filter Options', 'pet-match-pro-plugin' ) . '</p>';
		} 
	
		public function label_options_callback() {
			//$options = get_option('pet-match-pro-label-options');
			return '<p>' . __( 'Integration Partner Custom Label Values', 'pet-match-pro-plugin' ) . '</p>';
		}	

		/**
		 * Sanitization callback for the social options. Since each of the social options are text inputs,
		 * this function loops through the incoming option and strips all tags and slashes from the value
		 * before serializing it.
		 *
		 * @params	$input	The unsanitized collection of options.
		 *
		 * @returns			The collection of sanitized values.
		 */
		public function sanitize_filter_options( $input ) {
			// Define the array for the updated options
			$output = [];
			// Loop through each of the options sanitizing the data
			foreach( $input as $key => $val ) {
				if( isset ( $input[$key] ) ) {
					$output[$key] = esc_url_raw( strip_tags( stripslashes( $input[$key] ) ) );
				} // end if
			} // end foreach
			// Return the new collection
			return apply_filters( 'sanitize_filter_options', $output, $input );
		}

		/**
		 * Sanitization callback for the label options. Since each of the label options are text inputs,
		 * this function loops through the incoming option and strips all tags and slashes from the value
		 * before serializing it.
		 *
		 * @params	$input	The unsanitized collection of options.
		 *
		 * @returns			The collection of sanitized values.
		 */
		public function sanitize_label_options( $input ) {
			// Define the array for the updated options
			$output = [];
			// Loop through each of the options sanitizing the data
			foreach( $input as $key => $val ) {
				if( isset ( $input[$key] ) ) {
					$output[$key] = esc_url_raw( strip_tags( stripslashes( $input[$key] ) ) );
				} // end if
			} // end foreach
			// Return the new collection
			return apply_filters( 'sanitize_label_options', $output, $input );
		}
	
		/* Array Display Function */
	    public function keyAndLabel($arr){
			$returnArray = [];
			foreach($arr as $key => $item) {
				$returnArray[$key] = key($item);
	        }
			return $returnArray;
	   	}   
	   	
		/* Form Input Validation Function */
		public function validate_form_input( $input ) {
			// Create our array for storing the validated options
			$output = [];
			// Loop through each of the incoming options
			foreach( $input as $key => $value ) {
				// Check to see if the current option has a value. If so, process it.
				if( isset( $input[$key] ) ) {
					// Strip all HTML and PHP tags and properly handle quoted strings
					$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
				} // end if
			} // end foreach
			// Return the array processing any additional functions filtered by this action
			return apply_filters( 'validate_form_input', $output, $input );
		}	   	
	}
}