<?php 
if ( ! function_exists( 'WP_Filesystem' ) ) {
    
    require_once ABSPATH . '/wp-admin/includes/file.php';
} 
class Pet_Match_Pro_AF_Api {
    public $baseDetailURL;
    public $baseSearchURL;  
    public $baseFilterURL;  
    public $apiKey;
    //the URL of the current page where it is called to properly apply links
//    private $selfUrl;
    private $adoptDetailPageURL;
    private $foundDetailPageURL;
    private $lostDetailPageURL;
    private $preferredDetailPageURL;
    public $PMPlicense;
    public $PMPLicenseType;    /* To Secure Features */
    public $PMPLicenseTypeID;  /* To Secure Features */
    private $PMPFilterOptions;
    private $PMPSearchDetails;
    private $PMPSortOptions;
    private $PMPSearchFilter;
    private $PMPSearchLabels;
    private $methodFilterKeys;
    
    private $FilterAdminOptions;
    public $generalOptions;
    public $contactOptions;
    public $labelOptions;
    
    private $partialsDir;
    private $partialsPublicDir;
    private $adminFunction;
    private $animalDetailFunction;
    private $searchDetailSource;
    private $allAPIFunction;	
    private $lostfoundFilterKeys;
    private $resultLimit;
    
	public 	$integrationPartner;
	public  $apiVersion;
    public 	$displayIcons;  
    public 	$displayIconsSearch;  
    public 	$searchOutput;
    public  $pmpLicenseKey;    
    
    private $locationExclusionArray;
    private $locationFilterArray;
    private $locationFilterOther;
    
    public $defaultMethod;
    public $defaultType;
    public $methodValue;
    public $typeValue;
    public $statusValue;
    
    public function __construct($authKey, $activated) {
        //echo 'Auth Key = ' . $authKey . '<br>';
        $this->apiKey = $authKey;
//        $this->selfUrl = '';
        $this->PMPlicense = $activated;
        
        /* Get License Type to Secure Features */
        $this->PMPLicenseType = get_option('PMP_License_Type');      
        $this->PMPLicenseTypeID = (int) get_option('PMP_License_Type_ID');  
        
        //echo 'Option License Type ID = ' . $this->PMPLicenseTypeID . '<br>';
        if ( $this->PMPLicenseTypeID == 0 ) {
            $this->PMPLicenseTypeID = constant('FREE_LEVEL');
        }
        //echo 'License Type: ' . $this->PMPLicenseType . '(' . $this->PMPLicenseTypeID . ')<br>';                            
        
        $this->pmpLicenseKey = get_option("PMP_lic_Key", "");
        
        $this->generalOptions = get_option('pet-match-pro-general-options');
        //echo '<pre>GENERAL OPTIONS<br>'; print_r($this->generalOptions); echo '<pre>';
        
       	if (is_array($this->generalOptions)) {
			if (array_key_exists('integration_partner', $this->generalOptions)) {
				$this->integrationPartner = $this->generalOptions['integration_partner'];
			} else {
				$this->integrationPartner = constant('PETPOINT');
			}
		} else {
			$this->integrationPartner = constant('PETPOINT');
		}   
		//echo 'Integration Partner is ' .  $this->integrationPartner . '<br>';   
		
   		if (is_array($this->generalOptions)) {
	        if ( (array_key_exists('details_page_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST'), $this->generalOptions)) && (is_numeric($this->generalOptions['details_page_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')])) && (intval($this->generalOptions['details_page_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) > 0) ) {
	            $adoptDetailPageURL = $this->generalOptions['details_page_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')];
	            $detailsPage = get_post($adoptDetailPageURL);
	            $this->adoptDetailPageURL = get_home_url() . '/' . $detailsPage->post_name;
	         } else {
	           	$this->adoptDetailPageURL = get_home_url();
	         }
	         //echo 'Adoptable Details Page URL is ' . $this->adoptDetailPageURL . '<br>';

	        if( (array_key_exists('details_page_' . constant('FOUND_METHODTYPE_ANIMALSFIRST'), $this->generalOptions)) && (is_numeric($this->generalOptions['details_page_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')])) && (intval($this->generalOptions['details_page_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')]) > 0) ) {
	           	$foundDetailPageURL = get_post($this->generalOptions['details_page_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')]);
	            $detailsPage = get_post($foundDetailPageURL);           	
	           	$this->foundDetailPageURL = get_home_url() . '/' . $detailsPage->post_name;
	        } else {
	         	$this->foundDetailPageURL = get_home_url();            
	        }
			//echo 'Found Details Page URL is ' . $this->foundDetailPageURL . '<br>';        
        
	        if( (array_key_exists('details_page_' . constant('LOST_METHODTYPE_ANIMALSFIRST'), $this->generalOptions)) && (is_numeric($this->generalOptions['details_page_' . constant('LOST_METHODTYPE_ANIMALSFIRST')])) && (intval($this->generalOptions['details_page_' . constant('LOST_METHODTYPE_ANIMALSFIRST')]) > 0) ) {
				$lostDetailPageURL = get_post($this->generalOptions['details_page_' . constant('LOST_METHODTYPE_ANIMALSFIRST')]);
	            $detailsPage = get_post($lostDetailPageURL);			
	           	$this->lostDetailPageURL = get_home_url() . '/' . $detailsPage->post_name;
	        } else {
	           	$this->lostDetailPageURL = get_home_url();
	        }
			//echo 'Lost Details Page URL is ' . $this->lostDetailPageURL . '<br>';  
			
	        if( (array_key_exists('details_page_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST'), $this->generalOptions)) && (is_numeric($this->generalOptions['details_page_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')])) && (intval($this->generalOptions['details_page_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')]) > 0) ) {
				$preferredDetailPageURL = get_post($this->generalOptions['details_page_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')]);
	            $detailsPage = get_post($preferredDetailPageURL);			
	           	$this->preferredDetailPageURL = get_home_url() . '/' . $detailsPage->post_name;
	        } else {
	           	$this->preferredDetailPageURL = get_home_url();
	        }
			//echo 'Other Details Page URL is ' . $this->preferredDetailPageURL . '<br>';        
		} else {
           	$this->adoptDetailPageURL = get_home_url();
         	$this->foundDetailPageURL = get_home_url();   
           	$this->lostDetailPageURL = get_home_url();	
           	$this->preferredDetailPageURL = get_home_url();           	         	         
		}
		
		if (is_array($this->generalOptions)) {
			if (array_key_exists('search_result_limit', $this->generalOptions)) { 	
        		$this->resultLimit = $this->generalOptions['search_result_limit'];
				if ( (is_numeric($this->resultLimit)) && ($this->resultLimit > 0) ) {
					$this->resultLimit = $this->resultLimit;
				} else {
					$this->resultLimit = constant('SEARCH_RESULT_LIMIT');
	    		}
	    	} else {
				$this->resultLimit = constant('SEARCH_RESULT_LIMIT');
			}
		} else {
			$this->resultLimit = constant('SEARCH_RESULT_LIMIT');
		}		
	    	
		$urlPrefix = '';
		if (is_array($this->generalOptions)) {		
			if (array_key_exists('partner_API_source', $this->generalOptions)) {
				$apiSource =  $this->generalOptions['partner_API_source'];
				if ($apiSource == constant('ANIMALSFIRST_NON_LIVE_SOURCE')) {
					$urlPrefix = constant('ANIMALSFIRST_NON_LIVE_SOURCE') . '.';
				}
	    	    $this->baseDetailURL = 'https://' . $urlPrefix . 'animalsfirst.com/api/v2/get-animal' . '?api_token=' . $this->apiKey . '&limit=1';
	    	    $this->baseSearchURL = 'https://' . $urlPrefix . 'animalsfirst.com/api/v2/get-animals/' . '?api_token=' . $this->apiKey . '&limit=' . $this->resultLimit;
	    	    $this->baseFilterURL = 'https://' . $urlPrefix . 'animalsfirst.com/api/v2/get-filters/' . '?api_token=' . $this->apiKey;
	    	} else {
				$this->baseDetailURL = 'https://' . $urlPrefix . 'animalsfirst.com/api/getPetForSnippet/' . $this->apiKey;
	      		$this->baseSearchURL = 'https://' . $urlPrefix . 'animalsfirst.com/api/getPetsForSnippet/' . $this->apiKey . '/1/' . $this->resultLimit;   
	       		$this->baseFilterURL = 'https://' . $urlPrefix . 'animalsfirst.com/api/getAllFiltersForSnippet/' . $this->apiKey;
	    	}
	    } else {
			$this->baseDetailURL = 'https://' . $urlPrefix . 'animalsfirst.com/api/getPetForSnippet/' . $this->apiKey;
      		$this->baseSearchURL = 'https://' . $urlPrefix . 'animalsfirst.com/api/getPetsForSnippet/' . $this->apiKey . '/1/' . $this->resultLimit;   
       		$this->baseFilterURL = 'https://' . $urlPrefix . 'animalsfirst.com/api/getAllFiltersForSnippet/' . $this->apiKey;	    
	    }

        //all parameters saved on the PP Filter Options Admin Settings
        $this->FilterAdminOptions = get_option('pet-match-pro-filter-options');
        //echo '<pre>FILTER ADMIN OPTIONS<br>'; print_r($this->FilterAdminOptions); echo '</pre><br>';
        
       	$this->partialsDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/' . constant('PARTIALS_DIR') . '/';
       	$this->partialsPublicDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('PUBLIC_DIR') . '/' . constant('PARTIALS_DIR') . '/';

        /* Include Class Defining Functions for Processing Animal Searches */
        $functionsFile = 'class-pet-match-pro-functions.php';
        require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/' . $functionsFile;
        $this->adminFunction = new PetMatchProFunctions();       
        
        /* Include Class Defining Animal Detail Functions */
        $detailsFile = 'class-pet-match-pro-af-detail-functions.php';
        require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/' . $detailsFile;      
        $this->animalDetailFunction = new PetMatchProAnimalDetailFunctions();
        
        /* Include Class Defining Functions for All APIs */
        $functionsFile = 'class-pet-match-pro-all-api.php';
        require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . $functionsFile;      
        $this->allAPIFunction = new Pet_Match_Pro_All_API();
               
        /* Include General Options Levels File */
        $levelsFile = 'pmp-option-levels-general.php';
        $levelsPath = constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/';
        //echo 'General Levels Path = ' . $levelsPath . '<br>';
        require_once  $levelsPath . $levelsFile;

        //echo 'level_search_icons_adopt = ' . $pmpOptionLevelsGeneral['level_search_icons_adopt'] . '<br>';	
        //echo 'License ID = ' . $this->PMPLicenseTypeID . '<br>';
        //echo '<pre>adopt_animal_search_icons<br>'; print_r($this->generalOptions['adopt_animal_search_icons']); echo '</pre>';
		$this->displayIconsSearch = 0;        
        if (is_array($this->generalOptions)) {
        	if ( ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_search_icons_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) && (array_key_exists(constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_search_icons', $this->generalOptions)) ) {
				$this->displayIconsSearch = 1;
			}
		}
		//echo 'Display Search Icons = ' . $this->displayIconsSearch . '<br>';
		
        //echo 'level_detail_icons_adopt = ' . $pmpOptionLevelsGeneral['level_detail_icons_adopt'] . '<br>';	
        //echo 'License ID = ' . $this->PMPLicenseTypeID . '<br>';
        //echo '<pre>adopt_animal_detail_icons<br>'; print_r($this->generalOptions['adopt_animal_detail_icons']); echo '</pre>';
		$this->displayIcons = 0;
        if (is_array($this->generalOptions)) {
        	if ( ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_detail_icons_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) && (array_key_exists(constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_detail_icons', $this->generalOptions)) ) {
				$this->displayIcons = 1;
			}
		}
		//echo 'Display Detail Icons = ' . $this->displayIcons . '<br>';
		
        $this->contactOptions = get_option('pet-match-pro-contact-options');	
        $this->labelOptions = get_option('pet-match-pro-label-options');
        //echo '<pre>LABELS OPTIONS<br>'; print_r($this->labelOptions); echo '</pre>';
        
        /* Set Location Options */
        $this->locationExclusionArray = $this->allAPIFunction->locationExclusions();
        //echo '<pre>LOCATION EXCLUSIONS<br>'; print_r($this->locationExclusionArray); echo '</pre>';
        $this->locationFilterArray = $this->allAPIFunction->locationFilters();
        //echo '<pre>LOCATION FILTERS<br>'; print_r($this->locationFilterArray); echo '</pre>';
     	if (is_array($this->FilterAdminOptions)) {
	        if ( array_key_exists('location_filter_other', $this->FilterAdminOptions) ) {
	            $this->locationFilterOther = $this->FilterAdminOptions['location_filter_other'];
	            //echo 'Location Other = ' . $this->locationFilterOther . '.<br>';
	        }       
	    } else {
	    	$this->locationFilterOther = '';
	    }

		if (is_array($this->generalOptions)) {        
			if (array_key_exists('default_method_type', $this->generalOptions)) {
				$this->defaultType = $this->generalOptions['default_method_type'];
			} else {
				$this->defaultType = 'available';
			}
		} else {
			$this->defaultType = 'available';
		}
		if ($this->defaultType == 'available') {
			$this->defaultMethod = constant('ADOPT_METHODTYPE_ANIMALSFIRST');
		} else {
			$this->defaultMethod = strtolower($defaultType);
		}	
		$this->methodValue = $this->defaultMethod;
	}
              
    public function fetch_af_data($finalURL, $method, $items, $sortField, $sortOrder) {
        //echo '<pre>FETCH DATA called with Items<br>'; print_r($items); echo '</pre>';
        //echo '<pre>FETCH METHOD ' . $method . ' CALLED WITH<br>'; print_r($finalURL); echo '</pre>';
        $finalURL = str_replace(' ', '%20' , $finalURL); 
        if ( !is_null($finalURL) ) {
			if ( array_key_exists('website_support_email', $this->contactOptions) ) {
        	   $supportEmail = $this->contactOptions['website_support_email'];             
        	} else {
        	    $supportEmail = '><p class="pmp-error">No Support Email Address Provided.</p>';
        	}

        	if ( ($method == 'petSearch') && (array_key_exists(constant('ANIMALSFIRST_TYPE'), $items)) ) {
        		$typeValue = strtolower($items[constant('ANIMALSFIRST_TYPE')]);
           		/* Include Default Field Values */
           		$valuesFile = 'pmp-field-values.php';
           		$requireFile = $this->partialsDir . $valuesFile;
           		include($requireFile);     
           		//echo '<pre>FIELD VALUES '; print_r($pmpFieldValues); echo '</pre>';
            	if (array_key_exists($typeValue, $pmpFieldValues['value_types'])) {
            		$typeLevelArray = $pmpFieldValues['level_types'];
            		$typeLevel = $typeLevelArray[$typeValue];
            		//echo 'Level for Type ' . $typeValue . ' is ' . $typeLevel . '.<br>';
            		//$this->PMPLicenseTypeID = 3;
   			        if ( ($this->PMPLicenseTypeID <= $typeLevel) && (!empty($this->pmpLicenseKey)) ) {   
			        	$response = wp_remote_get($finalURL);
			            //echo '<pre>ANIMALSFIRST RESPONSE<br>'; print_r($response); echo '</pre>';        	
						if (is_wp_error($response)) {
							return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we are unable to reach our animal database. Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=RescueGroups Integration Error">email</a> to report the problem.</p>';
						} else {   
			        		//echo '<pre>ANIMALSFIRST FILE CONTENTS<br>'; print_r($response); echo '</pre>';
			        		if (array_key_exists('response', $response)) {
    			    				/* Array Has Sequential Numeric Keys Starting at Zero(0) */
    			    		    	$jsonArray = json_decode($response['body'], 1); 
    			    		    	if ( (is_array($jsonArray)) && (array_key_exists(strtolower(constant('ERROR')), $jsonArray)) ) {
					            		return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we encountered a technical issue preparing your animal search.<br>Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=AnimalsFirst Integration Error">email</a> to report the problem.</p>';
    			    		    	}      
    			        			//echo '<pre>ANIMALSFIRST CONTENT<br>'; print_r($jsonArray); echo '</pre>';
    			    		} else {
					            return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we encountered a technical issue preparing your animal search.<br>Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=AnimalsFirst Integration Error">email</a> to report the problem.</p>';
					        }
					    }
					} else {
                		return '<div class ="pmp-error-message text-150"><a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required to Search for Type Value ' . $typeValue . '.</div></div>';                  
                	}
                }
			}
        	$response = wp_remote_get($finalURL);			
			if (is_wp_error($response)) {
				return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we are unable to reach our animal database. Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=RescueGroups Integration Error">email</a> to report the problem.</p>';
			} else {   
        		//echo '<pre>ANIMALSFIRST FILE CONTENTS<br>'; print_r($response); echo '</pre>';
        		if (array_key_exists('response', $response)) {
  	    				/* Array Has Sequential Numeric Keys Starting at Zero(0) */
  	    		    	$jsonArray = json_decode($response['body'], 1); 
  	    		    	if ( (is_array($jsonArray)) && (array_key_exists(strtolower(constant('ERROR')), $jsonArray)) ) {
		            		return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we encountered a technical issue preparing your animal search.<br>Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=AnimalsFirst Integration Error">email</a> to report the problem.</p>';
	    		    	}      
	        			//echo '<pre>ANIMALSFIRST CONTENT<br>'; print_r($jsonArray); echo '</pre>';
        		} else {
		            return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we encountered a technical issue preparing your animal search.<br>Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=AnimalsFirst Integration Error">email</a> to report the problem.</p>';
		        }
		    }
		} else {
			return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Check PetMatchPro configuration, no details provided for search to our animal database. Please check the instructions or request support.</p>';
		}
		
		if ( (is_countable($jsonArray)) ) {
			$fetchCount = count($jsonArray);
		} else {
			$fetchCount = 0;
		}
		//echo 'Fetch Count is ' . $fetchCount . '.<br>';
		
		//echo '<pre>PRE SORT DATA<br>'; print_r($jsonArray); echo '</pre>';
		if ( (!empty($sortField)) && (empty($sortOrder)) ) {
			$sortOrder = $this->generalOptions[constant('ANIMALSFIRST_SORTORDER')];
		}					

		/* Sort Results by Key Value */
		//echo 'Preparing to Sort Results by Field ' . $sortField . ' in Order ' . $sortOrder . '<br>';
		if ( (strlen($sortField) > 0) && (strlen($sortOrder) > 0) ) {
			//echo '<pre>SORT ARRAY COLUMN<br>'; print_r($sortKey); echo '</pre>';
			$sortedData = $this->allAPIFunction->sortArrayByKey($jsonArray, $sortField, $sortOrder);
		} else {
			$sortedData = $jsonArray;
		} 
		//echo '<pre>SORTED DATA BY FIELD ' . $sortField . ' ' . $sortOrder . '<br>'; print_r($sortedData); echo '</pre>';
                
        //echo 'Fetch Count = ' . $fetchCount . '<br>';
        if ($method == constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Search') {
            $details = [];
            if (is_array($items)) {
                if (array_key_exists('details', $items)) {
                    $details = explode(",", $items['details']);
                }
            }
            //echo '<pre>Preparing to Call Search Output with Parameters '; print_r($items); echo '</pre>';
            return $this->outputSearch($this->adoptDetailPageURL, $sortedData, $items, constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Search');
            //echo 'Output Search Complete!<br>';
        } else if ( ($method == constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Details') && ($fetchCount > 0) )  {
            //echo '<pre>Preparing to Call Detail Output with Parameters '; print_r($items); echo '</pre>';
            return $this->outputDetails($sortedData, $items, constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Details');
            //echo 'Output Detail Complete!<br>';
        } else if ($method == 'featured') {
            $details = [];
            if (is_array($items)) {
                if (array_key_exists('details', $items)) {
                    $details = explode(",", $items['details']);
                }
            }
            return $this->outputFeatured($this->adoptDetailPageURL, $sortedData, $items, 'featured');
        } else if ($method == constant('FOUND_METHODTYPE_ANIMALSFIRST') . 'Search') {
            //$this->outputFoundSearch($this->adoptDetailPageURL,$xmlWS, $items);
            $details = [];
            if (is_array($items)) {
                if (array_key_exists('details', $items)) {
                    $details = explode(",", $items['details']);
                }
            }
            return $this->outputSearch($this->foundDetailPageURL, $sortedData, $items, constant('FOUND_METHODTYPE_ANIMALSFIRST') . 'Search');
        } else if ( ($method == constant('FOUND_METHODTYPE_ANIMALSFIRST') . 'Details') && ($fetchCount > 0) ) {
            //outputFoundDetails($xmlWSArray);
            return $this->outputDetails($sortedData, $items, constant('FOUND_METHODTYPE_ANIMALSFIRST') . 'Details');
        } // Display the results of the webservices call for the lostSearch method
        else if ($method == constant('LOST_METHODTYPE_ANIMALSFIRST') . 'Search') {
            //outputLostSearch($xmlWS);
            $details = [];
            if (is_array($items)) {
                if (array_key_exists('details', $items)) {
                    $details = explode(",", $items['details']);
                }
            }
            return $this->outputSearch($this->lostDetailPageURL, $sortedData, $items, constant('LOST_METHODTYPE_ANIMALSFIRST') . 'Search');
        } // Display the results of the webservices call for the lostDetails method
        else if ( ($method == constant('LOST_METHODTYPE_ANIMALSFIRST') . 'Details') && ($fetchCount > 0) ) {
            return $this->outputDetails($sortedData, $items, 'lostDetails');
        } elseif ( ($method == 'singleDetail')  && ($fetchCount > 0) ) {
            return $sortedData;
        } elseif ( ($method == 'petSearch')  && ($fetchCount > 0) ) {
            $details = [];
            if (is_array($items)) {
                if (array_key_exists('details', $items)) {
                    $details = explode(",", $items['details']);
                }
            }
       		if (array_key_exists(constant('ANIMALSFIRST_TYPE'), $items)) {
      			if ( strlen($items[constant('ANIMALSFIRST_TYPE')]) > 0 ) {
       				$typeValue = $items[constant('ANIMALSFIRST_TYPE')];
       				//echo 'Processing Type Value ' . $typeValue . '.<br>';
       				if ($typeValue == 'available') {
       					$callFunc = constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Search';
       					$detailPage = $this->adoptDetailPageURL;
       				} else {
       					if ($typeValue == constant('LOST_METHODTYPE_ANIMALSFIRST') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST')) {
							if (array_key_exists(constant('LOST_METHODTYPE_ANIMALSFIRST') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_combination', $this->FilterAdminOptions)) {
								$combination = $this->FilterAdminOptions[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_combination'];
								$searchPosition = strpos($combination, 'Search');
								$callPrefix = substr($combination, 0, $searchPosition);
							} else {
								$callPrefix = constant('LOST_METHODTYPE_ANIMALSFIRST');
							}
						} else {
							$callPrefix = constant('PREFERRED_METHODTYPE_ANIMALSFIRST');
						}
						//echo 'Call Prefix is ' . $callPrefix . '.<br>';
       					$callFunc = $callPrefix . 'Search';
       					//echo 'Detail Page URL is ' . $tempPage . '.<br>';

						$detailsPageKey = 'details_page_' . $callPrefix;
						//echo 'Details Page Key is ' . $detailsPageKey . '<br>';    					
        				if( (array_key_exists($detailsPageKey, $this->generalOptions)) && (is_numeric($this->generalOptions[$detailsPageKey])) && (intval($this->generalOptions[$detailsPageKey]) > 0) ) {
           					$detailPageURL = get_post($this->generalOptions[$detailsPageKey]);
            				$detailPage = get_home_url() . '/' . get_post($detailPageURL)->post_name;           	
        				} else {
         					$detailPage = get_home_url();            
        				}
       					//echo 'Detail Page URL is ' . $detailPage . '.<br>';       					
       				}
       			} else {
       				$callFunc = constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Search';
   					$detailPage = $this->adoptDetailPageURL;       				
       			}
       		} else {
   				$callFunc = constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Search';
				$detailPage = $this->adoptDetailPageURL;   				
       		}
       		//echo 'Calling outputSearch with Detail Page URL ' . $detailPage . '<br>';
            return $this->outputSearch($detailPage, $sortedData, $items, $callFunc);
        } else {
            return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately no results we returned from our animal database. Please try again.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=AnimalsFirst Fetch Returned No Results">email</a> to report the problem.</p>';
        }
        return;
    }

    public function createSearch($items, $callFunc) {
    	//echo '<pre>CREATE ' . $callFunc . ' SEARCH CALLED WITH ITEMS<br>'; print_r($items); echo '</pre>';
        /* Check for API KEY Before Creating Search */
        //echo 'API Key = ' . $this->apiKey . '.<br>';
        if ( isset($this->apiKey) && !empty($this->apiKey) ) {
            //echo '<pre>Search Called with Values<br>'; print_r($items); echo '</pre>';
            //$this->PMPLicenseTypeID = 3;
             
            //echo 'Species Key in Items Array ' . array_key_exists('animalspecies', $items) . '<br>';
            //echo 'License Type ID = ' . $this->PMPLicenseTypeID . ' with free value of ' . constant('FREE_LEVEL') . '<br>';

			//echo 'Call Method Being Called from createSearch.<br>';            
			$methodParms = $this->allAPIFunction->callMethod_Parameters($callFunc, $items);
			//echo '<pre>Method Parameters<br>'; print_r($methodParms); echo '</pre>';	

			$this->methodValue = $this->defaultMethod;
			if (array_key_exists('method', $methodParms)) {
				$this->methodValue = $methodParms['method'];
			}
			$this->typeValue = $this->defaultType;		
			if (array_key_exists('type', $methodParms)) {
				$this->typeValue = $methodParms['type'];
			}
			$this->statusValue = '';		
			if (array_key_exists('status', $methodParms)) {
				$this->statusValue = $methodParms['status'];
			}
			//echo 'Method Value of ' . $this->methodValue . ' with Type Value of ' . $this->typeValue . ' and Status of ' . $this->statusValue . '.<br>';
                    
            /* Get Free Species Values */
            $speciesFree = explode(",", constant('FREE_SPECIES'));          
            //echo 'Species in Free List ' . in_array(strtolower($items['species']), $speciesFree) . '<br>';   
                 
            if ( (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $items)) && ($this->PMPLicenseTypeID == constant('FREE_LEVEL')) && (!in_array(strtolower($items[constant('ANIMALSFIRST_SPECIES')]), $speciesFree)) ) {
                $itemsSpecies = ucwords($items[constant('ANIMALSFIRST_SPECIES')]);
                return '<div class ="pmp-error-message"><a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required to Search ' . ucwords($itemsSpecies) . 's</div></div>';                  
            } else {   
            	//echo 'Processing Search Parameters.<br>';     
                $defaultSort = constant('ANIMALSFIRST_NAME');
                if (array_key_exists('order_by', $this->generalOptions)) {
                	$orderBy = $this->generalOptions['order_by'];
                }
                if ( !isset($orderBy) ) {
                    $orderBy = $defaultSort;
                }
                //echo 'Order by = ' . $orderBy . '<br>';
                /* Select Default Orderby Value in Search Form */
                if ( !isset($_REQUEST[constant('ANIMALSFIRST_ORDERBY')]) ) {
                    $_REQUEST[constant('ANIMALSFIRST_ORDERBY')] = $orderBy;
                }   
                
                $defaultOrder = 'asc';
                if (array_key_exists('sort_order', $this->generalOptions)) {
                	$sortOrder = $this->generalOptions['sort_order'];
                }
                if ( !isset($sortOrder) ) {
                    $sortOrder = $defaultOrder;
                }
                /* Select Default Search Order Value in Search Form */
                if ( !isset($_REQUEST[constant('ANIMALSFIRST_SORTORDER')]) ) {
                    $_REQUEST[constant('ANIMALSFIRST_SORTORDER')] = $sortOrder;
                }                   
                //echo 'Sort Order = ' . $sortOrder . '<br>';
                
                $defaults = array(constant('ANIMALSFIRST_AGE') => constant('ALL'), constant('ANIMALSFIRST_BREED_PRIMARY') => constant('ALL'), constant('ANIMALSFIRST_GENDER') => constant('ALL'), constant('ANIMALSFIRST_COLOR_PRIMARY') => constant('ALL'), constant('ANIMALSFIRST_SIZE') => constant('ALL'), constant('ANIMALSFIRST_SPECIES') => constant('ALL'), constant('ANIMALSFIRST_STATUS') => constant('ALL'), constant('ANIMALSFIRST_TYPE') => constant('ALL'), constant('ANIMALSFIRST_ORDERBY') => $orderBy, constant('ANIMALSFIRST_SORTORDER') => $sortOrder);
                //echo '<pre>Defauts BEFORE Processing<br>'; print_r($defaults); echo '</pre>';
                
                //$species = $pmpFieldValues['value_filter_animalspecies_reverse_all'];
                //echo '<pre>SPECIES VALUES<br>'; print_r($species); echo '</pre>';
                                
                //for url variables override items with GET data
                if (!empty($_GET)) {
                    $items = $_GET;
                }
                //echo '<pre>URL PARAMETERS '; print_r($items); echo '</pre>';

                if (!empty($items)) {
                	/* Remove Spaces from Filter Values */
                	if (array_key_exists('filter', $items)) {
                		//echo '<pre>Item Filters BEFORE Processing<br>'; print_r($items['filter']); echo '</pre>';
                		$items['filter'] = preg_replace('/\s+/', '', $items['filter']);
                		//echo '<pre>Item Filters AFTER Processing<br>'; print_r($items['filter']); echo '</pre>';
                	}
                	/* Remove Spaces from Details Values */
                	if (array_key_exists('details', $items)) {
                		//echo '<pre>Item Details BEFORE Processing<br>'; print_r($items['details']); echo '</pre>';
                		$items['details'] = preg_replace('/\s+/', '', $items['details']);
                		//echo '<pre>Item Details AFTER Processing<br>'; print_r($items['details']); echo '</pre>';
                	}
                	/* Remove Spaces from Search Type Value */
                	if (array_key_exists(constant('ANIMALSFIRST_TYPE'), $items)) {
                		//echo '<pre>Item Type BEFORE Processing<br>'; print_r($items[constant('ANIMALSFIRST_TYPE')]); echo '</pre>';
                		$items[constant('ANIMALSFIRST_TYPE')] = preg_replace('/\s+/', '', $items[constant('ANIMALSFIRST_TYPE')]);
                		//echo '<pre>Item Type AFTER Processing<br>'; print_r($items[constant('ANIMALSFIRST_TYPE')]); echo '</pre>';
                	}                	
		            //echo '<pre>Search Items AFTER Processing<br>'; print_r($items); echo '</pre>';
                	                                  
                    $this->methodFilterKeys = array_keys($this->adminFunction->Filter_Option_Values($this->methodValue, $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER')));
                    //echo '<pre>ADOPTABLE FILTER KEYS<br>'; print_r($this->methodFilterKeys); echo '</pre>';
                    
                    foreach ($items as $key => $onlyFilter) {
                       //echo 'Processing Item Key ' . $key . ' with Value ' . $onlyFilter . '.<br>';
                        if (in_array(strtolower($key), array_map('strtolower', $this->methodFilterKeys))) {
                            if ($key == constant('ANIMALSFIRST_SPECIES')) {
                                //echo 'Key = ' . $key . ' Value = ' . $onlyFilter . ' with Species Value of ' .  $species[$onlyFilter] . '<br>';
                                $defaults[$key] = strtolower($onlyFilter);
                                //echo 'Set Defaults Species to ' . $defaults[$key] . '<br>';
                            } else {
                                $defaults[$key] = strtolower($onlyFilter);
                            }
                        }
                        //echo $onlyFilter . '<br>';
                    }                    
                    //echo '<pre>POST VARIABLES '; print_r($_POST); echo '</pre>';
					//echo 'items[animalspecies] exists as '. $items['animalspecies'] . '<br>';    
		        	//echo 'post[animalspecies] exists as '. $_POST['animalspecies'] . '<br>';    

	                $animalSpecies = constant('ALL');                   
                    if (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $items)) {
                    	//echo 'Species Exists in Items.<br>';
                    	if ( isset($_POST[constant('ANIMALSFIRST_SPECIES')]) ) {
                    		//echo 'Species Set as $_POST.<br>';
 							$animalSpecies = sanitize_text_field($_POST[constant('ANIMALSFIRST_SPECIES')]);
 						} else {
 							//echo 'Species Set to Default Value of ' . $defaults[constant('ANIMALSFIRST_SPECIES')] . '.<br>';
 							$animalSpecies = $defaults[constant('ANIMALSFIRST_SPECIES')];
 						}
 					}
                    
                    if (array_key_exists(constant('ANIMALSFIRST_ORDERBY'), $items)) {
                    	if ( isset($_POST[constant('ANIMALSFIRST_ORDERBY')]) ) {
                        	$orderBy = sanitize_text_field($_POST[constant('ANIMALSFIRST_ORDERBY')] ); 
						} else {
 							$orderby = $defaults[constant('ANIMALSFIRST_ORDERBY')];
						}
                    }
                } else {
                    //just plane shortcode
                    $animalSpecies = (isset($_POST[constant('ANIMALSFIRST_SPECIES')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_SPECIES')] ) : constant('ALL');
                }
        		//echo 'Animal Species = ' . $animalSpecies . '<br>';
        		
                $filterAge = (isset($_POST[constant('ANIMALSFIRST_AGE')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_AGE')] ) : constant('ALL');
                $defaults[constant('ANIMALSFIRST_AGE')] = $filterAge;
                //echo 'Filter Age = ' . $filterAge . '<br>';
                $filterAltered = (isset($_POST[constant('ANIMALSFIRST_ALTERED')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_ALTERED')] ) : constant('ALL');
                $defaults[constant('ANIMALSFIRST_ALTERED')] = $filterAltered;
                //echo 'Filter Altered = ' . $filterAltered . '<br>';
                $filterBreed = (isset($_POST[constant('ANIMALSFIRST_BREED_PRIMARY')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_BREED_PRIMARY')] ) : constant('ALL');
                $defaults[constant('ANIMALSFIRST_BREED_PRIMARY')] = $filterBreed;
                //echo 'Filter Breed = ' . $filterBreed . '<br>';
                $filterPrimary_color = (isset($_POST[constant('ANIMALSFIRST_COLOR_PRIMARY')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_COLOR_PRIMARY')] ) : constant('ALL');  
                $defaults[constant('ANIMALSFIRST_COLOR_PRIMARY')] =  $filterPrimary_color; 
                $filterDeclawed = (isset($_POST[constant('ANIMALSFIRST_DECLAWED')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_DECLAWED')] ) : constant('ALL');
                $defaults[constant('ANIMALSFIRST_DECLAWED')] = $filterDeclawed;
                //echo 'Filter Declawed = ' . $filterDeclawed . '<br>';
                $filterGender = (isset($_POST[constant('ANIMALSFIRST_GENDER')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_GENDER')] ) : constant('ALL');
                $defaults[constant('ANIMALSFIRST_GENDER')] = $filterGender;
                //echo 'Filter Gender = ' . $filterGender . '<br>';
                $filterSize = (isset($_POST[constant('ANIMALSFIRST_SIZE')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_SIZE')] ) : constant('ALL');
                $defaults[constant('ANIMALSFIRST_SIZE')] = $filterSize;
                //echo 'Filter Size = ' . $filterSize . '<br>';

                $filterMicrochip = (isset($_POST[constant('ANIMALSFIRST_MICROCHIP')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_MICROCHIP')] ) : constant('ALL');
                $defaults[constant('ANIMALSFIRST_MICROCHIP')] = $filterMicrochip;  
                
                $filterSpecies = (isset($_POST[constant('ANIMALSFIRST_SPECIES')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_SPECIES')] ) : $animalSpecies;
                $defaults[constant('ANIMALSFIRST_SPECIES')] = $filterSpecies;
                //$filterSpecies = $animalSpecies;
                $filterStatus = (isset($_POST[constant('ANIMALSFIRST_STATUS')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_STATUS')] ) : constant('ALL');
                $defaults[constant('ANIMALSFIRST_STATUS')] = $filterStatus;
                //echo 'Filter Status = ' . $filterStatus . '<br>';                
                $filterSeq_id = (isset($_POST[constant('ANIMALSFIRST_SEQ_ID')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_SEQ_ID')] ) : constant('ALL');
                $defaults[constant('ANIMALSFIRST_SEQ_ID')] = $filterSeq_id; 
                                           
                $sortField = (isset($_POST[constant('ANIMALSFIRST_ORDERBY')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_ORDERBY')] ) : $orderBy;
                $defaults[constant('ANIMALSFIRST_ORDERBY')] = $sortField;
                //echo 'Orderby = ' . $sortField . '<br>';
                $sortOrder = (isset($_POST[constant('ANIMALSFIRST_SORTORDER')])) ? sanitize_text_field($_POST[constant('ANIMALSFIRST_SORTORDER')] ) : $sortOrder;
                $defaults[constant('ANIMALSFIRST_SORTORDER')] = $sortOrder;
                //echo 'Sort Order = ' . $sortOrder . '<br>';

               	//echo '<pre>Defauts AFTER Processing<br>'; print_r($defaults); echo '</pre>';                
               	
		    	$queryString = $this->baseSearchURL;
				//echo 'Query String BEFORE Processing<br>' . $queryString . '<br>';
                
                /* Assign Details from Admin if Not Provided in Shortcode */
                if ( array_key_exists('details', $items) ) {
                    $this->searchDetailSource = 'shortcode';
                    $defaults[constant('ANIMALSFIRST_TYPE')] = $this->typeValue;                        
                    $queryString = $queryString . '&type=' . $this->typeValue . $this->statusValue;                        					                  
					//echo 'Query String AFTER Processing<br>' . $queryString . '<br>';    
                } else {
                    $this->searchDetailSource = 'admin';
                    $defaults[constant('ANIMALSFIRST_TYPE')] = $this->typeValue;  
                    
                    $queryString = $queryString . '&type=' . $this->typeValue . $this->statusValue;   
                                         					                  
                    $detailKey = $this->methodValue . '_search_details';
                    //echo 'Search Detail Key = ' . $detailKey . '<br>';
                    
                    if ( is_array($this->FilterAdminOptions) ) {
                        $searchDetails = $this->FilterAdminOptions[$detailKey];
                        if ( is_array($searchDetails) ) {
                            //echo '<pre>SEARCH DETAILS<br>'; print_r($searchDetails); echo '</pre>';
                            if ( is_array($searchDetails) ) {
                                $detailList = '';
                                foreach ($searchDetails as $key => $value) {
                                    $detailList = $detailList . ',' . $value;   
                                }
                                $detailList = substr($detailList,1);
                                //echo 'Detail List = ' . $detailList . '<br>';
                            }
                        } else {
                            $detailList = constant('ERROR');
                        }
                    } else {
                        $detailList = constant('ERROR');
                    }
                    $items['details'] = $detailList;
            	}
                //echo '<pre>Create Search Items AFTER Processing<br>'; print_r($items); echo '</pre>';

                //echo '<pre>DEFAULTS BEFORE QUERY STRING FINALIZATION<br>'; print_r($defaults); echo '</pre>';
                if ($this->methodValue != constant('ADOPT_METHODTYPE_ANIMALSFIRST')) {
                	$urlCounter = 2; /* Status Already Added to URL */
                } else {
                   	$urlCounter = 1;
				}
				
				/* Include Value & Levels File */
				$valuesFile = 'pmp-field-values.php';
		        $valuesFile = $this->partialsDir . $valuesFile;
		        require($valuesFile);
				//echo '<pre>Field Values<br>'; print_r($pmpFieldValues); echo '</pre>';
				
				$urlConnector = '&';				
				foreach ($defaults as $filterKey=>$filterValue) {
					$filterVar = 'filter' . ucfirst($filterKey);
					//echo 'Processing Filter Variable ' . $filterVar . ' with Value ' . $filterValue . '<br>'; 
					if ( (isset($$filterVar)) && ($$filterVar != constant('ALL')) ) {
						if ($filterKey == constant('ANIMALSFIRST_SPECIES')) {
							$queryKey = $filterKey;
	                       	$equals = '=';
						} else {
							//echo 'Setting Query Key for Filter Key ' . $filterKey . ' with Value ' . $$filterVar . '.<br>';
							$queryKey = $filterKey;
		                	if ( array_key_exists($filterKey, $pmpFieldValues['filter_fields_arrays']) ) {
		                		$equals = '[]=';
		                    } else {
		                       	$equals = '=';
		                    }							
						}
						if (strlen(trim($$filterVar)) > 0) {
							//echo 'Processing Query String for Query Key ' . $queryKey . ' with Value ' . $$filterVar . ' using Connector ' . $urlConnector . '.<br>';
							$urlCounter = $urlCounter + 1;	
							//echo 'Query String BEFORE Update<br>' . $queryString . '<br>';	
							if ($queryKey == constant('ANIMALSFIRST_MICROCHIP')) {
								$queryString = $queryString . $urlConnector . $queryKey . $equals . $$filterVar;
							} else {
								$queryString = $queryString . $urlConnector . $queryKey . $equals . urlencode(ucfirst($$filterVar));
							}
							//echo 'Query String AFTER Update<br>' . $queryString . '<br>';					
						}
					}
				}
				//echo 'Query String AFTER Processing<br>' . $queryString . '<br>';  					 
                return $this->fetch_af_data($queryString, $callFunc, $items, $sortField, $sortOrder);            	
            }
        } else {                
            $supportEmail = $this->contactOptions['website_support_email'];             
            //echo 'Support Email = ' . $supportEmail . '<br>';          
            return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Integration partner API key is missing in the General Settings.</p></div>';
        }
    }

    public function createDetails($animalIDIN, $items, $callFunc) {
    	//echo 'Creating Animal ' . $callFunc . ' Details with ID ' . $animalIDIN . '<br>';
    	//echo '<pre>CALLED WITH ITEMS<br>'; print_r($items) . '</pre>';
        
		//Create URL for Call to AnimalsFirst API		        
        if ( (is_array($items)) && (strlen($animalIDIN) > 0) ) { 
				$queryString = $this->baseDetailURL . '&animal_' . constant('ANIMALSFIRST_ID') . '=' . $animalIDIN;
				https://animalsfirst.com/api/v2/get-animal&api_key your_api_key&id=123123123 
		} else {
			$queryString = constant('ERROR');		
		}
		//echo 'Create Details Called with Query String<br>'. $queryString . '<br>';
        return $this->fetch_af_data($queryString, $callFunc, $items, '', '');
    }
    
    public function outputSearch($selfURLIN, $results, $details, $callFunc) {
       	//echo '<pre>Preparing to Output ' . $callFunc . ' Search with Details '; print_r($details); echo '</pre>';
        //echo '<pre>OUTPUT SEARCH CALLED WITH XML<br>'; print_r($results); echo '</pre>';
        if ( (is_array($results)) ) {
	        $resultCount = intval(count($results));
	    } else {
	    	$resultCount = 0 ;
	    }
        //echo 'Result Count = ' . $resultCount . '<br>';

		/* Include Style Updates from Colors Tab */
		$colorsFile = 'pet-match-pro-public-color-css.php';
        echo '<style id="pmp-color-options-css">';
		include $this->partialsPublicDir . $colorsFile;
        echo '</style> <!-- #pmp-color-options-css -->';

		if (is_array($this->generalOptions)) {
			if (array_key_exists('results_per_row', $this->generalOptions)) {
				$resultPerRow = $this->generalOptions['results_per_row'];
			} else {
				$resultPerRow = 3;
			}
		} else {
			$resultPerRow = 3;
		}
        $itemsPerPage = $resultPerRow * 3;
        //$labels - check if labels on results should be shown
        $labels = $this->allAPIFunction->showLabels($callFunc, $details);
        //echo 'Display Labels = ' . $labels . '<br>';

        //Fields to be Displayed in Search Results
        //$callFunc = type of search, $details = ovveride in shortcode
        $searchResultDetails = [];
        $searchResultDetails = $this->allAPIFunction->showDetails($callFunc, $details);
        //echo '<pre>SEARCH RESULTS<br>'; print_r($searchResultDetails); echo '</pre>';
		$detailCount = array_count_values($searchResultDetails);
        //echo 'Number of Details = ' . $detailCount . '<br>';

        $searchResultLabels = [];  
        if ($callFunc == constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Search') {
            $searchResultLabels = $this->animalDetailFunction->Animal_Labels($searchResultDetails, constant('ADOPT_METHODTYPE_ANIMALSFIRST'), 'search');   
        } elseif ($callFunc == constant('FOUND_METHODTYPE_ANIMALSFIRST') . 'Search') {
            $searchResultLabels = $this->animalDetailFunction->Animal_Labels($searchResultDetails, constant('FOUND_METHODTYPE_ANIMALSFIRST'), 'search');
        } elseif ($callFunc == constant('LOST_METHODTYPE_ANIMALSFIRST') . 'Search') {
            $searchResultLabels = $this->animalDetailFunction->Animal_Labels($searchResultDetails, constant('LOST_METHODTYPE_ANIMALSFIRST'), 'search');
        } else {
            $searchResultLabels = $this->animalDetailFunction->Animal_Labels($searchResultDetails, constant('PREFERRED_METHODTYPE_ANIMALSFIRST'), 'search');        
        }
            
        //echo '<pre>SEARCH RESULT LABELS<br>'; print_r($searchResultLabels); echo '</pre>';
        
        /*
         * Search Form Variables
         * $filterOptions = Options to show on search form - Form Fields
         * $filterValueArray = Values of the dropdown if needed
         */
        //should return search filters in array format array($filterOptions, $filterValues)
       	$searchFilters = []; 
        $searchFilters = $this->SearchFilters($callFunc, $details);
        //echo '<pre>SEARCH FILTERS<br>'; print_r($searchFilters); echo '</pre>';
        //check if species is dog or cat and use default species
        
        $addtoSearchClass = '';
        if (is_array($details)) {
            if (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $details)) {
                $addtoSearchClass = '-' . $details[constant('ANIMALSFIRST_SPECIES')];
            }
        }

        /* Configure Search Form Select Onclick Parameter */
        $gaName = 'button_pmp_search_select';
        $gaParamArray = [];
        $gaParamArray['event_category'] = 'Button';
        $gaParamArray['event_action'] = 'Select';
        $gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];
        $gaParamArray['click_id'] = 'pmp-search-submit-button' . $addtoSearchClass;
        $gaParamArray['click_text'] = 'Submit';
        $gaParamArray['click_url'] = '';
        $searchOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
        //echo 'Search OnClick Parameter <br>';
        //echo  $searchOnClick . '<br>';
        
        $TDresult = '<div class="pmp-container pmp-search-form">';

		$levelsSuffix = $this->methodValue;
        /* Get Field Visibility Levels by License Type */
        //$levelsSuffix = strtolower(str_replace('_', '',  $keySuffix));  
        $levelsFile = 'pmp-field-levels-' . $levelsSuffix . '.php';
        $requireFile = $this->partialsDir . $levelsFile;
        require($requireFile);
        
        $fieldLevelsArrayName = 'pmpFieldLevels' . ucwords($levelsSuffix);
        $fieldLevels = [];
        $fieldLevels = $$fieldLevelsArrayName;
        //echo '<pre>' . ucwords($levelsSuffix) . ' Field Levels<br>'; print_r($fieldLevels); echo '</pre>';
                
        //print_r($searchFilters);

        /* Get Field Visibility Levels by License Type */
        //echo '<pre> ' . strtoupper($levelSuffix) . ' FIELD LEVEL VALUES '; print_r($fieldLevels); echo '</pre>';
        /*Check if Filter Options Are Requiured */
        if ($searchFilters['filterOptions']) {
            $TDresult .= $this->allAPIFunction->buildSearchForm($searchFilters['filterOptions'], $searchFilters['filterValues'], $addtoSearchClass, $searchOnClick, $fieldLevels);
        }

        $TDresult .= '</div>';

        if (is_array($details) && array_key_exists('title', $details)) {
            $TDresult .= '<div class="pmp-results-title">';
            $TDresult .= $details['title'];
            $TDresult .= '</div>';
        }
        //end of form logic

		if (is_array($details) && array_key_exists('count', $details)) {
            $outputMax = $details['count'];
        } else {
        	$outputMax = 999;
        }
        
        $TDresult .= "<div class='pmp-search-results-container'><div class='pmp-items grid-container" . $resultPerRow . "'>";

        //start processing returned results
        //$resultItems = [];    
        //if ( (is_array($results)) && (array_key_exists('data', $results)) ) {
        //	$resultItems = (array)$results['data'];
        //}
		$resultsArray = $results; 
		//$resultsArray = array_change_key_case($resultsArray, CASE_LOWER);
        //echo '<pre>Results to Display '; print_r($resultsArray); echo "</pre>"; 
		//$detailsFunc = 'AdoptableDetails';	
		
		/* Initialize Icon Variables */
		if ($this->displayIconsSearch == 1) {
			$iconPath = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('IMAGES_DIR') . '/';
			if (array_key_exists(constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_search_icons_max', $this->generalOptions)) {
				$maxIcons = $this->generalOptions[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_search_icons_max'];
			} else {
				$maxIcons = 5;
			}
		}
		
        // Sets the counter to zero to use to loop through array count
        $counter = 0;        
        while ( ($counter < $resultCount) && ($counter < $outputMax) ) {
            if (isset($resultsArray[$counter])) {                
             	$resultArray = $resultsArray[$counter];
            } else {
              	$resultArray = [];
            }
            if ($callFunc == constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Search') {
                $detailsFunc = constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Details';
            } elseif ($callFunc == constant('FOUND_METHODTYPE_ANIMALSFIRST') . 'Search') {
                $detailsFunc = constant('FOUND_METHODTYPE_ANIMALSFIRST') . 'Details';
            } elseif ($callFunc == constant('LOST_METHODTYPE_ANIMALSFIRST') . 'Search') {
                $detailsFunc = constant('LOST_METHODTYPE_ANIMALSFIRST') . 'Details';
            } elseif ($callFunc == constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . 'Search') {
            	$detailsFunc = constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . 'Details';
            }
        
        	$resultArray = array_change_key_case($resultArray, CASE_LOWER);
			//echo '<pre>Results to Display<br>'; print_r($resultArray); echo "</pre>";
			
	        /* Decompose Address Array Fields */
	        if (array_key_exists(constant('ANIMALSFIRST_LOCATION_ADDRESS'), $resultArray)) {
	        	if (is_array($resultArray[constant('ANIMALSFIRST_LOCATION_ADDRESS')])) {
		        	if (count($resultArray[constant('ANIMALSFIRST_LOCATION_ADDRESS')]) > 0) {
		        		$address = $resultArray[constant('ANIMALSFIRST_LOCATION_ADDRESS')];
		        		foreach ($address as $addressKey => $addressValue) {
		        			$resultArray[$addressKey] = $addressValue;
		        		}
		        	}
		        }
	        }         
			//echo '<pre>Results to Display AFTER Addresss Processing<br>'; print_r($resultArray); echo "</pre>";
 
			$detailsPage = $selfURLIN . '?method=' . $detailsFunc . '&' . constant('ANIMALSFIRST_ID') . '=' . $resultArray[constant('ANIMALSFIRST_ID')];
            //use override details to show $searchResultDetails if defined in admin and or shortcode
			//echo 'Details Page URL = ' . $detailsPage . '<br>';		            

            if (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $resultArray)) {
            	$species = $resultArray[constant('ANIMALSFIRST_SPECIES')];
            } else {
            	$species = 	ucfirst(constant('ANIMALSFIRST_SPECIES'));
            }
            //echo 'Animal Species = ' . $species . '<br>';
            $speciesLower = strtolower($species);

            //if name is forced to be excluded get the name but dont include in array
            if (array_key_exists(constant('ANIMALSFIRST_NAME'), $resultArray)) {
            	if (!empty($resultArray[constant('ANIMALSFIRST_NAME')])) {
	            	$animalName = $resultArray[constant('ANIMALSFIRST_NAME')]; 
	            } else {
					$animalName = constant('EMPTY_VALUE');
				}	            
			} else {
				$animalName = constant('EMPTY_VALUE');
			}
			//echo 'Animal Name = ' . $animalName . '<br>';         
            if (!array_key_exists(constant('ANIMALSFIRST_NAME'), $searchResultDetails)) {
                $hover = "this " . $species;	
            } else {
                $hover = $animalName;
            }

			if (array_key_exists(constant('ANIMALSFIRST_LOCATION'), $resultArray)) {			
	            if (strlen(trim($resultArray[constant('ANIMALSFIRST_LOCATION')])) == 0 ) {
	            	$resultArray[constant('ANIMALSFIRST_LOCATION')] = constant('EMPTY_VALUE');   
	            }      
	        } else {
	        	$resultArray[constant('ANIMALSFIRST_LOCATION')] = constant('EMPTY_VALUE');
	        }

			/* Process Search Results */
			$DetailsOutput = [];
			//echo 'Processing Results for Animal ' . $resultArray[constant('ANIMALSFIRST_ID')] . ' with Name ' . $animalName . ' at Location ' . $resultArray[constant('ANIMALSFIRST_LOCATION')] . '.<br>';
			//echo '<pre>LOCATION EXCLUSIONS<br>'; print_r($this->locationExclusionArray); echo '</pre>';
			if ( (!array_key_exists(constant('ERROR'), $searchResultDetails)) && (!array_key_exists($resultArray[constant('ANIMALSFIRST_LOCATION')],$this->locationExclusionArray)) ) {  
				foreach ($resultArray as $animalKey => $animalKeyValue) {  
					//$animalKey = strtolower($animalKey);
					//echo 'Processing Animal Key ' . $animalKey . ' with Value ' . $animalKeyValue . '<br>';
					
					if ( array_key_exists($animalKey, $searchResultDetails) ) {
		                $DetailsOutput[$animalKey]['label'] = $searchResultLabels[$animalKey];
		                if ( (strlen(preg_replace('/\s+/', '', isset($animalKeyValue))) == 0) || (strlen(trim($animalKeyValue)) == 0) ) {
		                    $DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
		                } else {
		                    $DetailsOutput[$animalKey]['value'] = $animalKeyValue;
		                }
	                	/* RMB - Remove Time from Date Fields */
		                if (str_contains($animalKey, 'date')) {
		                    $DetailsOutput[$animalKey]['value'] = date("m-d-Y",strtotime($animalKeyValue));
		                }	                
		                
	                	//for age compute in years and months
		                if ($animalKey == constant('ANIMALSFIRST_DATE_BIRTH')) {
 	               			if ( ($animalKeyValue != constant('EMPTY_VALUE')) && (!empty($animalKeyValue)) ) {
 	               				//echo 'Processing Birthdate Value ' . $animalKeyValue . '.<br>';
								$birthDate = date_create($animalKeyValue);
								$birthDate = date_create(str_replace('-', '/', $animalKeyValue));
//			                    $interval = date_diff($today, $birthDate);
//			                    $months = ($interval->y * 12) + $interval->m;
			                    //echo 'Animal Age in Months = ' . $months . '<br>';                    
			                    if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
			            			$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->ageInYears($birthDate);
			                    	//echo 'Processing Birth Date<br>';
//			                        if ($months < 12) {
//										//echo 'Less Than 12 Month<br>';
//			                            $DetailsOutput[$animalKey]['value'] = $months . ' Month(s)';
//			                        } else {
//			                        	//echo 'Over 12 Montfhs<br>';
//			                            $DetailsOutput[$animalKey]['value'] = round($months / 12) . ' Year(s)';
//			                        }
				                } elseif ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
				                	$DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
				                } else {
				                	$DetailsOutput[$animalKey]['value'] = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required';
				                }	
			                }
							//echo $animalKey . 'Value is ' . $DetailsOutput[$animalKey]['value'] . '<br>';
			            }
			            
		                if ($animalKey == constant('ANIMALSFIRST_LOCATION')) {
	            			//echo 'Result Location Key is ' . $animalKey . ' with Value ' . $animalKeyValue . '.<br>';
		                	if (array_key_exists($animalKey, $DetailsOutput)) {               	
		                    	$locationValue = $animalKeyValue;
		                    	$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $locationValue);
								//echo 'Location Value of ' . $DetailsOutput[$animalKey]['value'] . '.<br>';	               	                     	
		               		}
		               	} 
			            
						$iconString = [];	                
						if ($this->displayIconsSearch == 1) {
							//echo 'Preparing to Process Icons<br>';
							//print_r($resultsArray);
				           	//echo '<pre>ICON FUNCTION CALLED WITH<br>'; print_r($resultsArray[$counter]); echo '</pre>';
				           	$iconString[$counter] = $this->animalDetailFunction->display_pet_icons($resultArray, $animalName, $maxIcons);
				        }
		            }
				}	           
			}
            //echo '<pre>DETAILS<br>'; print_r($details); echo '</pre>';
            //echo '<pre>DETAILS POST LABEL PROCESSING<br>'; print_r($DetailsOutput); echo '</pre>';
                        
			$locationValue = $resultArray[constant('ANIMALSFIRST_LOCATION')];
			if (strlen(trim($locationValue)) == 0) {
				$locationValue = constant('EMPTY_VALUE');
			}
			//echo 'Processing ' . $animalName . ' in Location ' . $locationValue . '<br>';
			if ( (is_array($this->locationExclusionArray)) && (!array_key_exists($locationValue, $this->locationExclusionArray)) ) {
				//echo 'Location ' . $locationValue . ' Was NOT Excluded!<br>';
				
	            /* Re-Order Output Details Based on Short Code Selection */
	            $detailKeys = explode(',', $details['details']);
	            //echo '<pre>DETAIL KEYS<br>'; print_r($detailKeys); echo '</pre>';
				$flipDetailKeys = array_flip($detailKeys);            
	            $this->searchOutput = array_merge(array_intersect_key($flipDetailKeys, $DetailsOutput), array_intersect_key($DetailsOutput, $flipDetailKeys));
	            //echo '<pre>DETAILS POST ORDER PROCESSING<br>'; print_r($this->searchOutput); echo '</pre>';
	            
	            /* Configure Image Onclick Parameter */
	            $clickID = 'pmp-animal-image-' . $speciesLower . '-' . str_replace(" ", "-", strtolower($animalName)); 
	            $clickText = 'Learn More About ' . $hover;
	            $gaName = 'image_pmp_search_select';                
	            $gaParamArray['event_category'] = 'Image';
	            $gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];        
	            $gaParamArray['click_id'] = $clickID;
	            $gaParamArray['click_text'] = $clickText; 
	            $gaParamArray['click_url'] = $detailsPage;
	            $imageOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
	            //echo 'Image OnClick Parameter <br>';
	            //echo  $imageOnClick . '<br>';            
	            if ( (isset($resultArray[constant('ANIMALSFIRST_PHOTOS')])) ) {
					$photoArray = $resultArray[constant('ANIMALSFIRST_PHOTOS')];
					//$photoKey = rtrim(substr(constant('ANIMALSFIRST_PHOTOS'), strpos(constant('ANIMALSFIRST_PHOTOS'), '_') + 1), 's') . '_url';
					//echo 'Photo Key = ' . $photoKey . '.<br>';
					if (array_key_exists(0, $photoArray)) {
		                $imgSrc = $photoArray[0];
		                if ( strlen($imgSrc) == 0 ) {
		                	$imgSrc = $resultArray[constant('ANIMALSFIRST_PHOTO_URL')];
		                }
		            } else {
	                	$imgSrc = $resultArray[constant('ANIMALSFIRST_PHOTO_URL')];		            
		            }
	            } else {
	                $imgSrc = '';
	            }
				//echo 'Picture Source = ' . $imgSrc . '<br>';
	
	            $TDresult .= '<div class="pmp-search-result-container">';
	            $TDresult .= '<div class="pmp-search-result pmp-animal-image"><a href="' . $detailsPage . '" class="pmp-animal-image-link" id="' . $clickID . '" onclick="' . $imageOnClick . '" ><img src="' . $imgSrc . '" title="' . $clickText . '"></a></div> <!-- .pmp-animal-image -->';
	            
	            //echo '<pre>PROCESSING RESULTS<br>'; print_r($DetailsOutput); echo '</pre>';
	            //$iconCounter = 0;
	            foreach ($this->searchOutput as $key => $result) {
	                //echo '<pre>PROCESSING RESULT KEY<br>'; print_r($key); echo '</pre>';
	                if ( ($key != constant('ERROR')) ) {
	                    $levelKey = constant('LEVEL_PREFIX_SEARCH_RESULT') . $key . '_' . $levelsSuffix;
	                    //echo 'Level Key = ' . $levelKey . '<br>';         
	                    if ($key == constant('ANIMALSFIRST_NAME')) {
	                    	//echo 'Processing Animal Name<br>';
	                        $clickID = 'pmp-animal-name-' . $speciesLower . '-' . str_replace(" ", "-", strtolower($animalName)); 
	                        $clickText = $this->searchOutput[$key]['value'];
	                        $gaName = 'text_pmp_search_select';                                     
	                        $gaParamArray['event_category'] = 'Text';
	                        $gaParamArray['click_id'] = $clickID;
	                        $gaParamArray['click_text'] = $clickText;
	                        $nameOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
	                        if ( ($this->searchDetailSource == 'admin') && ($labels == 'Enable') ) {
	                            $DetailLabel = '<div class ="pmp-search-result-label pmp-animal-' . strtolower($key) . '-label">' . $this->searchOutput[$key]['label'] . ': </div>'; 
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . '<a href="' . $detailsPage . '" class="pmp-animal-name-link" id="' . $clickID . '" onclick="' . $nameOnClick . '" >' . $clickText . '</a></div></div><!-- .pmp-search-result -->';                         
	                        } else {
	                            //$DetailLabel = '';
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '"><a href="' . $detailsPage . '" class="pmp-animal-name-link" id="' . $clickID . '" onclick="' . $nameOnClick . '" >' . $clickText . '</a></div><!-- .pmp-search-result -->';
	                        }   
	                    }
	                    elseif ($labels != 'Enable') { /* RMB */
							if ( (strlen(trim($this->searchOutput[$key]['value'])) > 0) && ($key != constant('EMPTY_VALUE')) ) {                        
		                        $TDresult .= '<div class ="pmp-search-result pmp-animal-details-no-labels">';                                       
		                        if ($this->PMPLicenseTypeID <= $fieldLevels[$levelKey]) {
			                        //echo 'Processing Key ' . $levelKey . ' with Level Value ' . $fieldLevels[$levelKey] . ' and Value ' . $this->searchOutput[$key]['value'] . '<br>';	                        	                        
		                            $TDresult .= '<span class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $this->searchOutput[$key]['value'] . '</span> |'; /* RMB */
		                        } else {
		                            $TDresult .= '<span class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail"><a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required</span> |';                  
		                        }
		                        $TDresult .= '</div><!-- .pmp-animal-details-no-labels -->';
		                    }
	                    } else { /* RMB */
	                        $DetailLabel = '<div class ="pmp-search-result-label pmp-animal-' . strtolower($key) . '-label">' . $this->searchOutput[$key]['label'] . ': </div>'; /* RMB */                
	                        //echo 'Processing Key ' . $levelKey . ' with Level Value ' . $fieldLevels[$levelKey] . '<br>';
	                        if ($this->PMPLicenseTypeID <= $fieldLevels[$levelKey]) {                
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $this->searchOutput[$key]['value'] . '</div></div><!-- .pmp-search-result -->'; 
	                        } else {
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required</div></div><!-- .pmp-search-result -->';                
	                        }
	                    } /* RMB */
	                } else {
	                    $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">';
	                    $TDresult .= '  <div class ="pmp-search-result-label pmp-animal-' . strtolower($key) . '-label pmp-red">' . constant('ERROR') . ': </div> <!-- .pmp-search-result-label -->';                
	                    $TDresult .= '  <div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">Configure Details!</div> <!-- .pmp-search-result-detail -->';
	                    $TDresult .= '</div> <!-- .pmp-search-result -->';                  
	                }                                
	            }
	            
	            /* RMB - Remove Final Separator */
	            if ( ($labels != 'Enable') && ($detailCount != 1) ) { 
	            	//echo 'Removing Final Separator from Result with Length ' . strlen($TDresult) . '.<br>';
	            	$lastSeparator = strrpos($TDresult, ' |');
	            	//echo 'Last Separator Located at ' . $lastSeparator . '.<br>';
	            	$TDresult = substr_replace($TDresult, '', $lastSeparator-1, -1); 
	                //$TDresult = substr($TDresult, 0, -2);
	                //echo 'Result now Has Length of ' . strlen($TDresult) . '.<br>';
					$TDresult .= '</div><!-- .pmp-animal-details-no-labels -->';                  
	            }
	            
				if ($this->displayIconsSearch == 1) {
		            $TDresult .= '<div class = "pmp-search-result pmp-animal-icons">' . $iconString[$counter] . '</div>';
		        }
		        $TDresult .= '</div><!-- .pmp-search-result-container -->';
	            // End the HTML row at every fourth animal
	            if (($counter + 1) % $resultPerRow == 0) {
	                $TDresult .= "";
	            }
			}
            // Increment Counter
            $counter++;
        } /* End WHILE */

        $TDresult .= '</div> <!-- .pmp-search-results-container -->';
        //closing DIV on included file
        if ($resultCount < 1) {
        	return $TDresult .= "<div class='pmp-no-results'>" . $this->generalOptions['no_search_results_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')] . "</div>";
        } else {
            //pagination enabled?
            if (array_key_exists('paginate_results', $this->generalOptions)) {               
                $gaParamArray['event_category'] = 'Icon';
                $gaParamArray['click_id'] = 'pmp-page-first';
                $gaParamArray['click_text'] = '&laquo;';
                $gaParamArray['click_url'] = '#';
                $firstOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
                $gaParamArray['click_id'] = 'pmp-page-previous';
                $gaParamArray['click_text'] = '&lsaquo;';
                $prevOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
                $gaParamArray['click_id'] = 'pmp-page-next';
                $gaParamArray['click_text'] = '&rsaquo;';
                $nextOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
                $gaParamArray['click_id'] = 'pmp-page-last';
                $gaParamArray['click_text'] = '&raquo;';
                $lastOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
                $TDresult .= '<div class="pmp-pager">';
                $TDresult .= '<div class="pmp-firstPage"><a href="#" class="pmp-page-first-link' . $addtoSearchClass . '" id="pmp-page-first" onclick="' . $firstOnClick . '" >&laquo;</a></div><div class="pmp-previousPage"><a href="#" class="pmp-page-previous-link' . $addtoSearchClass . '" id="pmp-page-previous" onclick="' . $prevOnClick . '">&lsaquo;</a></div><div class="pmp-pageNumbers"></div><div class="pmp-nextPage"><a href="#" class="pmp-page-next-link' . $addtoSearchClass . '" id="pmp-page-next" onclick="' . $nextOnClick . '" >&rsaquo;</a></div><div class="pmp-lastPage"><a href="#" class="pmp-page-last-link' . $addtoSearchClass . '" id="pmp-page-last" onclick="' . $lastOnClick . '">&raquo;</a></div>';
                $TDresult .= '</div>';
                $TDresult .= '</div>';
                //$addtoSearchClass
                //vd($addtoSearchClass);
                $TDresult .= '<script type="text/javascript">';
                $TDresult .= 'jQuery(function($) {$(".pmp-search-results-container").PMPpaginate({';
                $TDresult .= 'itemsPerPage: ' . $itemsPerPage . ',';
                $TDresult .= 'species: "' . $addtoSearchClass . '",';
                $TDresult .= 'pageNumbers: ".pmp-pageNumbers",';
                $TDresult .= 'itemsContainer: ".pmp-items"';
                $TDresult .= '});});';
                $TDresult .= '</script>';
            }
        }
        return $TDresult;
    }

    public function outputFeatured($selfURLIN, $jsonArray, $details, $callFunc) {
    	//echo '<pre>OUTPUT FEATURED WITH DETAILS<br>'; print_r($details); echo '</pre>';
        //echo '<pre>FEATURED SHORTCODE DATA<br>'; print_r($jsonArray); echo '</pre>';

        $resultCount = count($jsonArray);
        //echo 'Featured Result Count = ' . $resultCount . '.<br>';
        $labels = $this->allAPIFunction->showLabels($callFunc, $details);

        //details to be shown on result page
        //admin filter options
        //$callFunc = type of search, $details = ovveride in shortcode
        $searchResultDetails = $this->allAPIFunction->showDetails(constant('ANIMALSFIRST_FEATURED'), $details);
        //echo '<pre>FEATURED RESULTS<br>'; print_r($searchResultDetails); echo '</pre>';

        $detailCountArray = array_count_values($searchResultDetails);
        //echo '<pre>Featured Search Detail Count<br>'; print_r($detailCountArray); echo '</pre>'; 
        $detailCount = count($detailCountArray);
        //echo 'Featured Detail Count = ' . $detailCount . '<br>';
               
        $nameCount = 0;
        if ( array_key_exists(constant('ANIMALSFIRST_NAME'), $detailCountArray) ) {
            $nameCount = 1;
        }
        //echo 'Name Count = ' . $nameCount . '<br>';
        $detailCount = $detailCount - $nameCount;
        //echo 'Detail Count After Name Adjustment = ' . $detailCount . '<br>';
        
        $searchResultLabels = $this->animalDetailFunction->Animal_Labels($searchResultDetails, constant('ADOPT_METHODTYPE_ANIMALSFIRST'), 'search');  
         
        $TDresult = '';
        if (is_array($details) && array_key_exists('title', $details)) {
            $TDresult .= '<div class="pmp-results-title featured">';
            $TDresult .= $details['title'];
            $TDresult .= '</div>';
        }
        
        /* Get Field Visibility Levels by License Type */
        $levelsFile = 'pmp-field-levels-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '.php';
        $requireFile = $this->partialsDir . $levelsFile;
        require($requireFile);
        
        $fieldLevelsArrayName = 'pmpFieldLevelsAdopt';
        $fieldLevels = [];
        $fieldLevels = $$fieldLevelsArrayName;
        //echo '<pre>' . ucwords($levelsSuffix) . ' Field Levels<br>'; print_r($fieldLevels); echo '</pre>';
        
        $TDresult .= "<div class='pmp-search-results-container featured'>";
        
        //start processing returned results
        //set counter
        $featuredCounter = 0;
        //echo 'Array Count is ' . $resultCount . '<br>';

        $counter = 0;
        while ($counter < $resultCount) {
        	//echo 'Processing Counter ' . $counter . '<br>';  
        	if ( (array_key_exists(constant('ANIMALSFIRST_FEATURED'), $jsonArray[$counter])) && ($jsonArray[$counter][constant('ANIMALSFIRST_FEATURED')] != '') ) {  
	            if ($jsonArray[$counter][constant('ANIMALSFIRST_FEATURED')] == 'Yes') {
	            	//echo 'Featured Pet Found<br>';
	                //featured is found
	                $featuredCounter = 1;
	                //echo 'Featured Counter = 1<br>';
	                //vd($xmlWSIN->XmlNode->$counter->adoptSearch);
	                //identify results to use based on function
	                $resultItems = (array)$jsonArray[$counter];
	                $detailsFunc = constant('ADOPT_METHODTYPE_ANIMALSFIRST') . 'Details';
//	                $detailsFunc = 'AdoptableDetails';
	                //to lower case just to match keys
	                $results = array_combine(array_map('strtolower', array_keys($resultItems)), $resultItems);
	                //place each value from above in the main array of the called function with labels
	                $searchDetailsOutput = $this->allAPIFunction->showDetails($callFunc, $details);
	                $detailsPage = $selfURLIN . '?method=' . $detailsFunc . '&' . constant('ANIMALSFIRST_ID') . '=' . $results[constant('ANIMALSFIRST_ID')];
	                //use override details to show $searchResultDetails if defined in admin and or shortcode
	
	                foreach ($searchResultDetails as $animalKey => $animalKeyValue) {
	                    //use labels from static variable
	                    $DetailsOutput[$animalKey]['label'] = $searchResultLabels[$animalKey];
	                    if (strlen(preg_replace('/\s+/', '', $results[$animalKey])) == 0) {
	                        $DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
	                    } else {
	                        $DetailsOutput[$animalKey]['value'] = $results[$animalKey];
	                    }
	
//	                    //for age compute in years and months
//	                    if ($key == constant('ANIMALSFIRST_DATE_BIRTH')) {
//	                		$DetailsOutput[$key]['value'] = $this->convertAge($results[$key], array_key_exists('age_in_years', $this->generalOptions));
//	                    }
	                    
//	   	                if ($key == constant('ANIMALSFIRST_LOCATION')) {
//	            			//echo 'Result Location Value is ' . $results[$key] . '.<br>';
//		                	if (array_key_exists($key, $results)) {               	
//		                    	$locationValue = $results[$key];
//		                    	$DetailsOutput[$key]['value'] = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $locationValue);
//								//echo 'Location Value of ' . $DetailsOutput[$key]['value'] . '.<br>';	               	                     	
//		               		}
//		               	} 
		               	
	                	//for age compute in years and months
		                if ($animalKey == constant('ANIMALSFIRST_DATE_BIRTH')) {
 	               			if ( ($animalKeyValue != constant('EMPTY_VALUE')) && (!empty($animalKeyValue)) ) {
 	               				//echo 'Processing Birthdate Value ' . $animalKeyValue . '.<br>';
								$birthDate = date_create($animalKeyValue);
								$birthDate = date_create(str_replace('-', '/', $animalKeyValue));
			                    //echo 'Animal Age in Months = ' . $months . '<br>';                    
			                    if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
			            			$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->ageInYears($birthDate);
				                } elseif ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
				                	$DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
				                } else {
				                	$DetailsOutput[$animalKey]['value'] = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required';
				                }	
			                }
			            }
			            
		                if ($animalKey == constant('ANIMALSFIRST_LOCATION')) {
	            			//echo 'Result Location Key is ' . $animalKey . ' with Value ' . $animalKeyValue . '.<br>';
		                	if (array_key_exists($animalKey, $results)) {               	
		                    	$locationValue = $results[$animalkey];
		                    	$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $locationValue);
								//echo 'Location Value of ' . $DetailsOutput[$animalKey]['value'] . '.<br>';	               	                     	
		               		}
		               	} 
	                }
	
	                $TDresult .= '<div class="pmp-search-result-container featured">';
                
	                /* Set Image OnClick Parameter */
					if (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $details)) {
						$species =  $details[constant('ANIMALSFIRST_SPECIES')];
					} else {
						if (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $details)) {
							$species =  $details[constant('ANIMALSFIRST_SPECIES')];
						} else {
							$species = constant('ANIMALSFIRST_SPECIES');
						}
					}
	                $clickID = 'pmp-animal-image-' . strtolower($species) . '-' . str_replace(" ", "-", strtolower($DetailsOutput[constant('ANIMALSFIRST_NAME')]['value'])); 
	                $clickText = 'Learn More About ' . ucfirst($DetailsOutput[constant('ANIMALSFIRST_NAME')]['value']);
	                $gaName = 'image_pmp_search_select';                
	                $gaParamArray['event_category'] = 'Image';
	                $gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];        
	                $gaParamArray['click_id'] = $clickID;
	                $gaParamArray['click_text'] = $clickText;               
	                $gaParamArray['click_url'] = $detailsPage;
	                $imageOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
	                //echo 'Image OnClick Parameter <br>';
	                //echo  $imageOnClick . '<br>';            

	                $TDresult .= '<div class="pmp-search-result pmp-animal-image featured"><a href="' . $detailsPage . '" class="pmp-animal-image-link" id="' . $clickID . '" onclick="' . $imageOnClick . '"><img src="' . $results['photo'] . '" title="' . $clickText . '" class="pmp-search-result-img pmp-animal-image featured-img"></a></div> <!-- .pmp-animal-image .featured-->';

	                foreach ($DetailsOutput as $key => $result) {
	                    $levelKey = constant('LEVEL_PREFIX_SEARCH_RESULT') . $key . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');
	                    //echo 'Level Key = ' . $levelKey . '<br>';         
	                    if ($key == constant('ANIMALSFIRST_NAME')) {
	                        $clickID = 'pmp-animal-name-' . strtolower($species) . '-' . str_replace(" ", "-", strtolower($DetailsOutput[constant('ANIMALSFIRST_NAME')]['value'])); 
	                        $clickText = ucfirst($DetailsOutput[$key]['value']);
	                        $gaName = 'text_pmp_search_select';                                     
	                        $gaParamArray['event_category'] = 'Text';
	                        $gaParamArray['click_id'] = $clickID;
	                        $gaParamArray['click_text'] = $clickText;
	                        $nameOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
	                    
	                        $TDresult .= '<div class ="pmp-search-result pmp-animal-' . $key . ' featured"><a href="' . $detailsPage . '" id="' . $clickID . '" onclick="' . $nameOnClick . '">' . $clickText . '</a></div>';
	                    }
	                    elseif ($labels != 'Enable') {
	                        //echo 'Processing Key ' . $levelKey . ' with Level Value ' . $fieldLevels[$levelKey] . '<br>';
	                        if ($this->PMPLicenseTypeID <= $fieldLevels[$levelKey]) {                   
	                            $TDresult .= '<span class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $DetailsOutput[$key]['value'] . '</span> |'; 
	                        } else {
	                            $TDresult .= '<span class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail"><a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required</span> |';                  
	                        }
	                    } else { 
	                        $DetailLabel = '<div class ="pmp-search-result-label pmp-animal-' . strtolower($key) . '-label">' . $DetailsOutput[$key]['label'] . ': </div>'; /* RMB */
	                        //echo 'Processing Key ' . $levelKey . ' with Level Value ' . $fieldLevels[$levelKey] . '<br>';
	                        if ($this->PMPLicenseTypeID <= $fieldLevels[$levelKey]) {                                       
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $DetailsOutput[$key]['value'] . '</div></div>'; 
	                        } else {
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required</div></div>'; /* RMB */                 
	                        }                            
                    	} 
                	}
	                if ( ($labels != 'Enable') && ($detailCount > 0) ) { 
	                //if ($labels != 'Enable') { 
	                    $TDresult = substr($TDresult, 0, -2);
	                }
                
	                $TDresult .= '</div>';
	                $counter = $resultCount;                	
                }
            }
            //echo 'Incrementing Counter<br>';
            $counter++;
        }

		//echo 'Featured Counter = ' . $featuredCounter . ' for Species ' . $details[constant('ANIMALSFIRST_SPECIES')] . '.<br>';
        //closing DIV on included file
        if ($featuredCounter == 0) {
			if ( (array_key_exists('no_search_results_featured', 	$this->generalOptions)) && (strlen($this->generalOptions['no_search_results_featured']) > 0) ) {
				$noFeatured = $this->generalOptions['no_search_results_featured'];
			} else {
				//echo 'No Featured Search Result Msg in Admin.<br>';
		        if (is_array($details) && array_key_exists(constant('ANIMALSFIRST_SPECIES'), $details)) {
		            $species = strtolower($details[constant('ANIMALSFIRST_SPECIES')]);
		        } else {
		        	$species = 'animal';
		        }
				$noFeatured = '<p class="pmp-error">Come back soon, our featured ' . $species . ' has been adopted.</p>';
//				$noFeatured = '<div class="pmp-no-results featured">' . '<p class="pmp-error">Come back soon, our featured ' . $species . ' has been adopted.</p></div> <!-- .pmp-no-results -->';
			}
            //echo 'No Featured<br>' . $noFeatured . '<br>';
            $TDresult .= '<div class="pmp-no-results featured">' . $noFeatured . '</div> <!-- .pmp-no-results -->';
        } 
        $TDresult .= '</div> <!-- .pmp-search-results-container -->';
        
        return $TDresult;
    }

    public function outputDetails($results, $items, $callFunc) {
        if ( WP_Filesystem() ) {
            global $wp_filesystem;
        }
        
        //echo '<pre> OUTPUT DETAILS CALLED WITH PARAMETERS '; print_r($items); echo '</pre>';
        //echo '<pre> OUTPUT DETAILS CALLED WITH RESULTS<br>'; print_r($results); echo '</pre>';
        //echo 'Call Function = ' . $callFunc . '<br>';
                
        if ( (!empty($results)) ) {
       		$resultCount = 1;
        } else {
        	$resultCount = 0;
        }
        //echo 'Result Count is ' . $resultCount . '<br>';

		/* Include Style Updates from Colors Tab */
		$colorsFile = 'pet-match-pro-public-color-css.php';
        echo '<style id="pmp-color-options-css">';
		include $this->partialsPublicDir . $colorsFile;
        echo '</style> <!-- #pmp-color-options-css -->';

        /* Determine Key Suffix */
        $detailPosition = strpos($callFunc, 'Details');
        $callMethod = substr($callFunc, 0, $detailPosition);
        //echo 'Call Method is ' . $callMethod . '.<br>';        
        if (strtolower($callMethod) == 'adoptable') {
        	$callMethod = constant('ADOPT_METHODTYPE_ANIMALSFIRST');
        }
        //echo 'Call Method is ' . $callMethod . '.<br>';        
        $keySuffix = '_' . $callMethod;
        //$keySuffix = '_adopt'; 
        //echo 'Key Suffix is ' . $keySuffix . '.<br>';
        
        $DetailsOutput = $this->animalDetailFunction->Animal_Details($callMethod, $this->PMPLicenseTypeID, 'front-end');
        //echo "<pre>DETAILS OUTPUT<br>"; print_r($DetailsOutput);echo "</pre>"; 
        $detailTemplate = 'details_template' . $keySuffix;           
        if ( (is_array($this->generalOptions)) && (array_key_exists($detailTemplate, $this->generalOptions)) ) {
        	$template = $this->generalOptions[$detailTemplate];
        } else {
        	$template = '';
        }
        //echo 'Detail Template File = ' . $template . '<br>';

        $DetailsOutput = array_change_key_case($DetailsOutput, CASE_LOWER);
        //echo "<pre>DETAILS OUTPUT<br>"; print_r($DetailsOutput);echo "</pre>";

        //$callFunc = type of search, $details = ovveride in shortcode
        $ResultDetails = array_change_key_case($this->allAPIFunction->showDetails($callFunc, $items));
        //echo "<pre>RESULT OUTPUT<br>"; print_r($ResultDetails);echo "</pre>";

        if ($resultCount == 1) {
			$resultItems = (array)$results;
        	//echo "<pre>RESULTS ITEMS<br>"; print_r($resultItems);echo "</pre>";
        	$detailsItem = array_change_key_case($resultItems, CASE_LOWER);
        } else {
        	$detailsItem = [];
        }
       	//echo "<pre>DETAILS ITEM<br>"; print_r($detailsItem);echo "</pre>";     	

        /* Decompose Address Array Fields */
        if (array_key_exists(constant('ANIMALSFIRST_LOCATION_ADDRESS'), $detailsItem)) {
        	if (is_array($detailsItem[constant('ANIMALSFIRST_LOCATION_ADDRESS')])) {
	        	if (count($detailsItem[constant('ANIMALSFIRST_LOCATION_ADDRESS')]) > 0) {
	        		$address = $detailsItem[constant('ANIMALSFIRST_LOCATION_ADDRESS')];
	        		foreach ($address as $addressKey => $addressValue) {
	        			$detailsItem[$addressKey] = $addressValue;
	        		}
	        	}
	        }
        }
        		
        //echo "<pre>BEFORE LABEL KEY OPERATION<br>"; print_r($this->labelOptions);echo "</pre>";           
        /* Remove 'label_' prefix from label keys & filter keys */
        $resultLabels = [];
        if ( is_array($this->labelOptions) ) {
			$labelKeys = array_keys($this->labelOptions);			
			//echo '<pre>Label Keys<br>'; print_r($labelKeys); echo '</pre>';
			$selectedLabelKeys = preg_grep("/$keySuffix/i", $labelKeys);
			//echo '<pre>Method Selected Label Keys<br>'; print_r($selectedLabelKeys); echo '</pre>';
//			$detailFilter = constant('LABEL_PREFIX_ANIMAL_DETAIL');
//			$selectedLabelKeys = preg_grep("/$detailFilter/i", $selectedLabelKeys);
//			echo '<pre>Detail Selected Label Keys<br>'; print_r($selectedLabelKeys); echo '</pre>';

			foreach ($selectedLabelKeys as $seqNum => $labelKey) {
				//echo 'Processing Label Key ' . $labelKey . '.<br>';
                if ( (is_int(strpos($labelKey, $keySuffix))) && (!is_int(strpos($labelKey, constant('LABEL_PREFIX_SEARCH_FILTER')))) ) {
                    $resultLabelKey = str_replace('label_', '', $labelKey);				
					//$resultLabelKey = str_replace($detailFilter, '', $labelKey);
					$resultLabelKey = str_replace($keySuffix, '', $resultLabelKey);
					//echo 'Result Label Key is ' . $resultLabelKey . '.<br>';
	                if ( (strlen(trim($resultLabelKey)) > 0) ) { 
	                	//echo 'Label Option Value for Key ' . $labelKey . ' is ' . $this->labelOptions[$labelKey]) . '.<br>';  
	                	$labelOptionsValue = $this->labelOptions[$labelKey];
	                	//echo 'Label Options Value is ' . $labelOptionsValue . '.<br>'; 
	                	if ( (array_key_exists($labelKey, $this->labelOptions)) && (strlen($labelOptionsValue ) > 0) ) {
	                		$resultLabels[$resultLabelKey] = $this->labelOptions[$labelKey];
	                	} elseif ( (array_key_exists($resultLabelKey, $DetailsOutput)) && (strlen($DetailsOutput[$resultLabelKey]) > 0) )  {
	                		$resultLabels[$resultLabelKey] = $DetailsOutput[$resultLabelKey];
	                	} else {
	                		$resultLabels[$resultLabelKey] = constant('EMPTY_VALUE');
	                	}		
					}
				}
			}
        } else {
            $resultLabels = $DetailsOutput;         
        }			
        //echo "<pre>RESULT LABELS<br>"; print_r($resultLabels);echo "</pre>";

//        $resultLabels = [];
//        if ( is_array($this->labelOptions) ) {
//            foreach ($this->labelOptions as $key => $value) {
//            	//echo 'Processing Label Key ' . $key . ' with Value ' . $value . '.<br>';
//                $label_key = strtolower(substr($key, 6)); /* Convert Keys to Lower Case */
//                if ( (strpos($key, $keySuffix)) && (!strpos($key, 'filter_')) ) {
//                    $labelKey = str_replace('label_', '', $key);
//                    $keySuffixPosition = strrpos($labelKey, $keySuffix);
//                    //echo 'Suffix Position is ' .  $keySuffixPosition . '.<br>';                    
//                    if ( is_int($keySuffixPosition) ) {
//	                    $labelKey = substr_replace($labelKey, '', $keySuffixPosition);  
//                    $labelKey = str_replace($keySuffix, '', $labelKey);  
//                    $label_key = str_replace($keySuffix, '', $label_key);  
//	                    //echo 'Label Key is ' . $labelKey . '.<br>'; 
//    	                if ( (strlen(trim($value)) > 0) && ($labelKey != $keySuffix) ) {     
//    	                	$result_labels[$labelKey] = $value;
//    	                } elseif (array_key_exists($labelKey, $DetailsOutput))  {
//    	                	$result_labels[$labelKey] = $DetailsOutput[$labelKey];
//    	                } else {
//    	                	$result_labels[$labelKey] = constant('EMPTY_VALUE');
//    	                }
//    	            }
//                } 
//            }
//        } else {
//            $result_labels = $DetailsOutput;         
//        }

        //echo "<pre>Result Details Processing<br>"; print_r($ResultDetails);echo "</pre>";
        //echo "<pre>DETAILS ITEM<br>"; print_r($detailsItem);echo "</pre>";
        //only include those that are set to be shown by admin settings or by shortcode override
        //get all keys of returned result and put in array
//        $keyword_replace = [];
//        $keyword_replace_values = [];
		$animalDetails = [];
		if ($resultCount == 1) {
	        foreach ($ResultDetails as $key => $item) {
	        	//echo 'Processing Key ' . $key . ' with Value ' . $item . '.<br>';
	        	if ( (array_key_exists($key, $resultLabels)) && (array_key_exists($key, $detailsItem)) ) {
	        		//echo 'Result Label and Details Item Exist.<br>'; 
	        		if (empty($detailsItem[$key])) {
	        			$detailsItem[$key] = constant('EMPTY_VALUE');
	        		}
		        	$animalDetails[$key]['label'] = $resultLabels[$key] . ': ';
		        	if (is_array($detailsItem[$key])) {
	            		$animalDetails[$key]['value'] = $detailsItem[$key];
	            	} else {
	            		$animalDetails[$key]['value'] = ucwords($detailsItem[$key]);
	            	}
	            	if (!is_array($detailsItem[$key])) {
	            	    if ( (strlen(preg_replace('/\s+/', '', $detailsItem[$key])) == 0) ) {
	            	        $animalDetails[$key]['value'] = constant('EMPTY_VALUE');
	            	    } else {
	            	        $animalDetails[$key]['value'] = ucwords($detailsItem[$key]);
	            	    }
	            	} else {
	            	    if (empty($detailsItem[$key])) {
	            	        $animalDetails[$key]['value'] = constant('EMPTY_VALUE');
	            	    } else {
		        			if (is_array($detailsItem[$key])) {
	            	        	$animalDetails[$key]['value'] = $detailsItem[$key];
	            	        } else {
	            	        	$animalDetails[$key]['value'] = ucwords($detailsItem[$key]);
	            	        }
	            	    }
	            	}
	            	/* RMB - Remove Time from Date Fields */
	            	if (str_contains($key, 'date')) {
	            	    $animalDetails[$key]['value'] = date("m-d-Y",strtotime($detailsItem[$key]));
	            	}
	            }    
	            //echo 'Animal Detail Key is ' . $key . ' with Label ' . $animalDetails[$key]['label'] . ' and Value ' . $animalDetails[$key]['value'] . '.<br>';
	            //$animalDetails[$key]['value'] = $this->convertAge($detailsItem[$key]);
	            //for age compute in years and months
	            if ($key == constant('ANIMALSFIRST_DATE_BIRTH')) {
	            	//echo constant('ANIMALSFIRST_DATE_BIRTH') . ' Value is ' . $detailsItem[$key] . '.<br>';
                	if ( ($animalDetails[$key]['value'] != constant('EMPTY_VALUE')) && (strlen(trim($detailsItem[$key])) > 0) ) {
//                	if ( ($animalDetails[$key]['value'] != constant('EMPTY_VALUE')) (!empty($animalDetails[$key]['value'])) ) {
                		$birthDate = date_create($detailsItem[$key]);
//			            $today = date_create("now");
//						$birthDate = date_create($animalDetails[$key]['value']);
						$birthDate = date_create(str_replace('-', '/', $detailsItem[$key]));
//			            $interval = date_diff($today, $birthDate);
//			            $months = ($interval->y * 12) + $interval->m;
			            //echo 'Animal Age in Months = ' . $months . '<br>';                    
			            if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
			            	$animalDetails[$key]['value'] = $this->allAPIFunction->ageInYears($birthDate);
			              	//echo 'Processing Birth Date<br>';
//			                if ($months < 12) {
								//echo 'Less Than 12 Month<br>';
//			                    $animalDetails[$key]['value'] = $months . ' Month(s)';
//			                } else {
			                 	//echo 'Over 12 Months<br>';
//			                    $animalDetails[$key]['value'] = round($months / 12) . ' Year(s)';
//			                }
				        } elseif ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
				           	$animalDetails[$key]['value'] = constant('EMPTY_VALUE');
				        } else {
				           	$animalDetails[$key]['value'] = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required';
				        }	
		            }
				}

                if ($key == constant('ANIMALSFIRST_LOCATION')) {
          			//echo 'Result Location Value is ' . $detailsItem[$key] . '.<br>';
                	if (array_key_exists($key, $detailsItem)) {               	
                    	$locationValue = $detailsItem[$key];
                    	$animalDetails[$key]['value'] = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $locationValue);
						//echo 'Location Value of ' . $animalDetails[$key]['value'] . '.<br>';	               	                     	
               		}
               	} 
	        }
		}
        //echo "<pre>ANIMAL DETAILS<br>"; print_r($animalDetails);echo "</pre>"; 
	        
		/* Find Description Array Key */
        if ( (array_key_exists(constant('ANIMALSFIRST_DESCRIPTION'), $animalDetails)) && ($animalDetails[constant('ANIMALSFIRST_DESCRIPTION')]['value'] == constant('EMPTY_VALUE')) ){
          	$animalDescription = $this->allAPIFunction->replaceDetailShortcodes($ResultDetails, $animalDetails, $this->generalOptions['default_description']);            
            //echo '<br>' . 'Animal Description'. '<br>';
            //echo $animalDescription . '<br>';
            $animalDetails[constant('ANIMALSFIRST_DESCRIPTION')]['value'] = $animalDescription;
            //echo 'Completed Value Reassign';
        }
        
        $showItems = array_keys($animalDetails);

        //include the photos
        if (array_key_exists(constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_detail_thumbs_max', $this->generalOptions)) {
        	$maxThumbs = intval($this->generalOptions[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_detail_thumbs_max']);
        } else {
        	$maxThumbs = 6;
        }
		//echo 'Max Thumbs is ' . $maxThumbs . '<br>';        
        //echo '<pre>DETAIL ITEMS<br>'; print_r($detailsItem); echo '</pre>';
		if ( is_int($maxThumbs) ) {
			//echo 'Max thumbs is an Integer.<br>';
			$counter = 1;
			while ($counter <= $maxThumbs) {
				$photoKey = $counter - 1;		
				//echo 'Processing Counter ' . $counter . ' with Key ' . $photoKey . '<br>';	
	        	if (!empty($detailsItem[constant('ANIMALSFIRST_PHOTOS')][$photoKey])) {
	        		$photoFile = $detailsItem[constant('ANIMALSFIRST_PHOTOS')][$photoKey];
	        		//echo 'Photo File Name is ' . $photoFile . '.<br>';
	        		//echo 'Photo URL is<br>' . $photoURL . '.<br>';
	   	        	$animalDetails[constant('ANIMALSFIRST_PHOTOS')][] = $photoFile;
	   	        	//echo 'Added Animal Photo<br>';
	   	        }
	  	      	$counter = $counter + 1;
			}
			if (!array_key_exists(constant('ANIMALSFIRST_PHOTOS'), $animalDetails)) {
				$animalDetails[constant('ANIMALSFIRST_PHOTOS')] = [];
				$animalDetails[constant('ANIMALSFIRST_PHOTO_URL')] = $detailsItem[constant('ANIMALSFIRST_PHOTO_URL')];
			}
			
			$numPhotos = count($animalDetails[constant('ANIMALSFIRST_PHOTOS')]);
			if ($numPhotos == 0) {
				$numPhotos = 1;
			}
			//echo 'There are ' . $numPhotos . ' Photos.<br>';
			$numThumbs = $numPhotos;
			
			if ( ($numThumbs <= $maxThumbs) && (array_key_exists(0, $detailsItem[constant('ANIMALSFIRST_VIDEOS')])) ) {
				$videoCount = count($detailsItem[constant('ANIMALSFIRST_VIDEOS')]);
				//echo 'There is/are ' . $videoCount . ' Video(s).<br>';
				$videoCount = $videoCount - 1;
				$counter = 0;
				while ( ($numThumbs <= $maxThumbs) && ($counter <= $videoCount) ){
					//echo 'Processing Video Counter ' . $counter . ' with Thumb ' . $numThumbs . '<br>';	
	        		if (!empty($detailsItem[constant('ANIMALSFIRST_VIDEOS')][$counter])) {
	   	    	    	$animalDetails[constant('ANIMALSFIRST_VIDEOS')][] = $detailsItem[constant('ANIMALSFIRST_VIDEOS')][$counter];
	   	    	    	//echo 'Added Animal Video.<br>';
	   	    	    	$numThumbs = $numThumbs + 1;
	   	    	    }
	  	    	  	$counter = $counter + 1;
				}
			}
		}

        //get template options if defined in shortcode
        if (is_array($items)) {
            if (array_key_exists('template', $items)) {
                $template = $items['template'];
            }
        }
        //echo 'Template to be Used is ' . $template . '<br>';        

        //echo '<pre>ANIMAL DETAILS FOR DISPLAY - POST PROCESSING <br>'; print_r($animalDetails); echo '</pre>';

        /* RMB - Check if Child Directory Exists, Otherwise Set to Plugin Default */
        $templateDir = constant('TEMPLATES_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/';
        if ($template) {
            if ( ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) && (is_dir(get_stylesheet_directory(dirname(__FILE__)) . '/' . constant('PLUGIN_DIR') . '/' . $templateDir)) ) {
                $templateDir = get_stylesheet_directory(dirname(__FILE__)) . '/' . constant('PLUGIN_DIR') . '/' . $templateDir;
                //echo 'Template Director is ' . $templateDir . '<br>';
            } else {
                $templateDir = constant("PET_MATCH_PRO_PATH") . '/' . constant('PUBLIC_DIR') . '/' . $templateDir;
                //echo 'Template Director is ' . $templateDir . '<br>';        
            }
            //echo 'Preparing to Include Detail Template<br>';

			if (str_contains($template, '.php')) {
				include($templateDir . $template);
			} else {
				include($templateDir . $template . '.php');
			}
            //echo 'Template File Included<br>';
        } else {
            include constant("PET_MATCH_PRO_PATH") . '/' . constant('PUBLIC_DIR') . '/' . $templateDir . 'default.php';   /* RMB - Use Plugin Path Constant */
        }
    
        //echo 'Included Template ' . $templateDir . $template . '.php' . '<br>';
        return  $output;
    }

    public function animalDetail($animalIDIN, $item, $callFunc) {
        //echo '<pre>Animal Detail Called with<br>'; print_r($item); echo '<br>';
        //echo '<pre>Animal Details Called by Class<br>'; print_r(get_called_class()); echo '</pre>';
        //echo '<pre>Animal Details Backtrace<br>'; print_r(debug_backtrace()[1]); echo '</pre>';
        //echo 'Animal Detail Called with ID = ' . $animalIDIN . '<br>';
        //echo 'Animal Detail Called with Function = ' . $callFunc . '<br>';
   		$urlParms = $_GET;
   		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		//echo 'First URL Parameter is ' . $firstURLParm . '<br>';
        
		if ( ($firstURLParm != constant('EMPTY_VALUE')) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {
			//echo 'Processing Animal Detail Shortcode.<br>';
            $animalDetails = [];       
            if (!empty(trim($item))) {
            	//echo 'Processing Item ' . $item . '.<br>';
				if ( (strlen($item) > 0) && (strlen($animalIDIN) > 0) ) { 
					//echo 'Call Method Being Called from animalDetail.<br>'; 				
					$methodParms = $this->allAPIFunction->callMethod_Parameters($callFunc, $item);
					//echo '<pre>Method Parameters<br>'; print_r($methodParms); echo '</pre>';	

					$this->methodValue = $this->defaultMethod;
					if (array_key_exists('method', $methodParms)) {
						$this->methodValue = $methodParms['method'];
					}
					//echo 'Method Value is ' . $this->methodValue . '.<br>';
   			        $levelsSuffix = $this->methodValue;			

			        /* Get Field Visibility Levels by License Type */
			        $levelsFile = 'pmp-field-levels-' . $levelsSuffix . '.php';
			        $requireFile = $this->partialsDir . $levelsFile;
			        include($requireFile);
				    $fieldLevelArrayName = 'pmpFieldLevels' . ucwords($levelsSuffix);
					//echo 'Levels Array is ' . $fieldLevelArrayName . '.<br>';			
				    $fieldLevelArray = $$fieldLevelArrayName;

					$filterPrefix = constant('LEVEL_PREFIX_ANIMAL_DETAIL');
					$detailItem = $filterPrefix . $item . '_' . $levelsSuffix;					
				    if (array_key_exists($detailItem, $fieldLevelArray)) {
				    	$detailItemLevel = $fieldLevelArray[$detailItem];
						//echo 'Level Value of ' . $detailItem . ' is ' . $detailItemLevel . '.<br>';
						if ($this->PMPLicenseTypeID <= $detailItemLevel) {
							//Create URL for Call to AnimalsFirst API	
							$queryString = $this->baseDetailURL . '&animal_' . constant('ANIMALSFIRST_ID') . '=' . $animalIDIN;
							//echo 'Preparing to Fetch Single Detail with Query<br>' . $queryString . '<br>';
			                $animalDetails = $this->fetch_af_data($queryString, 'singleDetail', $item, '', '');
			               	//echoho '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';                
			                if (is_array($animalDetails)) {   
//		                		$animalDetails = $animalDetails;
						        foreach ($animalDetails as $key => $animalitem) {
						            $details[strtolower($key)] = $animalitem;
								}		                				                		
			               	} else {
			               		return constant('ERROR');
			               	}
		              
							//echo 'Animal Detail Called with Item = ' . $item . '<br>';
		              
		 	               	if ($item == constant('ANIMALSFIRST_LOCATION')) {
		     	     			//echo 'Result Location Value is ' . $details[$item] . '.<br>';
		     		           	if (array_key_exists($item, $details)) {               	
		     		               	$locationValue = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $details[$item]);
									//echo 'Location Value of ' . $locationValue . '.<br>';	               	                     	
		     		               	return $locationValue;
		     	          		}
		     	          	} 
		     	           return $details[$item];						
						} else {
                			return '<div class ="pmp-error-message text-150"><a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required to Display ' . ucwords($item) . '.</div></div>';                  
						}						
					} else {
	               		return constant('ERROR');					
					}
	            } else { 
	                return constant('ERROR');
	            } 	                  
            } else { 
                return constant('ERROR');
            } 	  
        } else {
        	return constant('ERROR');
        }
    }

    private function searchFilters($callFunc, $details) {
    	//echo 'SearchFilters Called with Function ' . $callFunc . ' and Details<br>';
    	//echo '<pre><br>'; print_r($details); echo '</pre>';
        //$override_search = [];
        if (is_array($details)) {
            $details = array_change_key_case($details, CASE_LOWER);
        }
        /*
         * $filterOptions = Selected Adoptable filters to use on admin
         * $filterValues = values of all available filters as array;
        */
        //$PMPLicenseTypeID = get_option('PMP_License_Type_ID'); /* Manage Options */ 
        
        $filterReturn = [];  

		//echo 'Call Method Being Called from searchFilters.<br>'; 
		$methodParms = $this->allAPIFunction->callMethod_Parameters($callFunc, $details);
		//echo '<pre>Method Parameters<br>'; print_r($methodParms); echo '</pre>';	
		$this->methodValue = $this->defaultMethod;
		if (array_key_exists('method', $methodParms)) {
			$this->methodValue = $methodParms['method'];
		}
      	$callMethod = $this->methodValue;	
        //echo 'SearchFilters Call Method is ' . $callMethod . '.<br>';		
       
        //echo '<pre>FILTER ADMIN OPTIONS<br>'; print_r($this->FilterAdminOptions); echo '</pre>';
        $sortKey = $callMethod . '_search_' . constant('ANIMALSFIRST_ORDERBY');        
        //echo 'Sort Key is ' . $sortKey . '.<br>';
        
        if ( (is_array($this->FilterAdminOptions)) && (array_key_exists($sortKey, $this->FilterAdminOptions)) ) {
            $this->PMPSortOptions = $this->FilterAdminOptions[$sortKey];  
        } else {
            $this->PMPSortOptions = array(constant('ANIMALSFIRST_NAME') => ucfirst(constant('ANIMALSFIRST_NAME')));
        }
        //echo '<pre>SORT OPTIONS<br>'; print_r($this->PMPSortOptions); echo '</pre>';

		/* Search Criteria Configured in the Admin */     
		
        $filterOptionsFE = $this->adminFunction->Search_Filter_Values($callMethod, $this->PMPLicenseTypeID);
        if ( is_array($filterOptionsFE) ) {
            $filterOptions  = array_keys($filterOptionsFE);
        } else {
            $filterOptions  = null;
        }
        //echo '<pre>Filter Options Front-End<br>'; print_r($filterOptionsFE); echo '</pre>';
        
		/* Get Label Values for Filter Options with Full Sort Field & Sort Order Values */
        $filterValues = $this->adminFunction->Search_Filter_Values($callMethod, $this->PMPLicenseTypeID);
        
        //echo '<pre>FILTER OPTIONS BEFORE PROCESSING '; print_r($filterOptions); echo '</pre>';  
        //echo '<pre>FILTER VALUES BEFORE PROCESSING '; print_r($filterValues); echo '</pre>';  
        //echo '<pre>SORT OPTIONS BEFORE PROCESSING<br>'; print_r($sortOptions); echo '</pre>';    

        /* Obtain Custom Search Labels */
        $valuesFile = 'pmp-field-values-' . $callMethod . '.php';
        //$partialsDir = '/' . constant('ANIMALSFIRST_DIR') . '/' . constant('ANIMALSFIRST_PARTIALS_DIR') . '/';
        $valuesFile = $this->partialsDir . $valuesFile;
        //$valuesFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . $partialsDir . $valuesFile;
        require($valuesFile);
        $fieldValueArrayName = 'pmpFieldValues' . ucwords($callMethod);
        $fieldValueArray = $$fieldValueArrayName;
        //echo '<pre>FIELD VALUES<br>'; print_r($fieldValueArray); echo '</pre>';
        
        $ageKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_AGE') . '_' . $callMethod; 
        $alteredKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_ALTERED') . '_' . $callMethod; 
        $breedKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_BREED_PRIMARY') . '_' . $callMethod; 
        //echo 'Breed Key is ' . $breedKey . '.<br>';
        $colorKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_COLOR_PRIMARY') . '_' . $callMethod; 
        $declawedKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_DECLAWED') . '_' . $callMethod; 
        $genderKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_GENDER') . '_' . $callMethod;         
        $sizeKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SIZE') . '_' . $callMethod; 

        $microchipKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_MICROCHIP') . '_' . $callMethod; 
        $seqidKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SEQ_ID') . '_' . $callMethod; 
        $speciesKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SPECIES') . '_' . $callMethod; 
        
        if ( is_array($this->labelOptions) ) {                            
            if (array_key_exists($ageKey, $this->labelOptions)) {
            	if (strlen(trim($this->labelOptions[$ageKey])) > 0) {
                	$ageLabel = $this->labelOptions[$ageKey];
                } else {
	                $ageLabel = $fieldValueArray[$ageKey];                
                }
            } else {
                $ageLabel = $fieldValueArray[$ageKey];
            }

            if (array_key_exists($alteredKey, $this->labelOptions)) {
            	if (strlen(trim($this->labelOptions[$alteredKey])) > 0) {
                	$alteredLabel = $this->labelOptions[$alteredKey];
                } else {
	                $alteredLabel = $fieldValueArray[$alteredKey];
                }
            } else {
                $alteredLabel = $fieldValueArray[$alteredKey];
            }

            if (array_key_exists($breedKey, $this->labelOptions)) {
            	if (strlen(trim($this->labelOptions[$breedKey])) > 0) {
                	$breedLabel = $this->labelOptions[$breedKey];
                } else {
	                $breedLabel = $fieldValueArray[$breedKey];
                }
            } else {
                $breedLabel = $fieldValueArray[$breedKey];
            }
            
            if (array_key_exists($colorKey, $this->labelOptions)) {
            	if (strlen(trim($this->labelOptions[$colorKey])) > 0) {
	                $colorLabel = $this->labelOptions[$colorKey];
	            } else {
	                $colorLabel = $fieldValueArray[$colorKey];	            
	            }
            } else {
                $colorLabel = $fieldValueArray[$colorKey];
            }

            if (array_key_exists($declawedKey, $this->labelOptions)) {
            	if (strlen(trim($this->labelOptions[$declawedKey])) > 0) {
	                $declawedLabel = $this->labelOptions[$declawedKey];
	            } else {
	                $declawedLabel = $fieldValueArray[$declawedKey];
	            }
            } else {
                $declawedLabel = $fieldValueArray[$declawedKey];
            }

            if (array_key_exists($genderKey, $this->labelOptions)) {
            	if (strlen(trim($this->labelOptions[$genderKey])) > 0) {            
	                $genderLabel = $this->labelOptions[$genderKey];
	            } else {
	                $genderLabel = $fieldValueArray[$genderKey];
	            }
            } else {
                $genderLabel = $fieldValueArray[$genderKey];
            }

            if (array_key_exists($sizeKey, $this->labelOptions)) {
            	if (strlen(trim($this->labelOptions[$sizeKey])) > 0) {            
	                $sizeLabel = $this->labelOptions[$sizeKey];
				} else {
	                $sizeLabel = $fieldValueArray[$sizeKey];				
				}
            } else {
                $sizeLabel = $fieldValueArray[$sizeKey];
            }

            if (array_key_exists($microchipKey, $this->labelOptions)) {
            	if (strlen(trim($this->labelOptions[$microchipKey])) > 0) {                        
                	$microchipLabel = $this->labelOptions[$microchipKey];
                } else {
                	$microchipLabel = $fieldValueArray[$microchipKey];
                }
            } else {
                $microchipLabel = $fieldValueArray[$microchipKey];
            }

            if (array_key_exists($seqidKey, $this->labelOptions)) {
            	if (strlen(trim($this->labelOptions[$seqidKey])) > 0) {                                    
                	$seqidLabel = $this->labelOptions[$seqidKey];
                } else {
	                $seqidLabel = $fieldValueArray[$seqidKey];                
                }
            } else {
                $seqidLabel = $fieldValueArray[$seqidKey];
            }

            if (array_key_exists($speciesKey, $this->labelOptions)) {
            	if (strlen(trim($this->labelOptions[$speciesKey])) > 0) {                                                
                	$speciesLabel = $this->labelOptions[$speciesKey];
                } else {
    	            $speciesLabel = $fieldValueArray[$speciesKey];                
                }
            } else {
                $speciesLabel = $fieldValueArray[$speciesKey];
            }
        } else {
            $ageLabel = $fieldValueArray[$ageKey];      
            $alteredLabel = $fieldValueArray[$alteredKey];        
            $breedLabel = $fieldValueArray[$breedKey];        
            $colorLabel = $fieldValueArray[$colorKey];        
            $declawedLabel = $fieldValueArray[$declawedKey];        
            $genderLabel = $fieldValueArray[$genderKey];        
            $sizeLabel = $fieldValueArray[$sizeKey];     
            $microchipLabel = $fieldValueArray[$microchipKey];  
            $seqidLabel = $fieldValueArray[$seqidKey];
            $speciesLabel = $fieldValueArray[$speciesKey];  
        }        

		if ( array_key_exists('website_support_email', $this->contactOptions) ) {
           $supportEmail = $this->contactOptions['website_support_email'];             
        } else {
            $supportEmail = 'No Support Email Address Provided';
        }
        
        if ( strlen($this->baseFilterURL) == 0 ) {
        	$errorMsg = '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Check PetMatchPro configuration, no details provided to search our animal database. Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=AnimalsFirst Integration Error">email</a> to report the problem.</p>';
        	return array(constant('ERROR'), $errorMsg);
        }
		//echo 'Filter Value URL is<br>' . $queryString . '<br>';  

		if ( strlen($this->baseFilterURL) > 0 ) {
			//echo 'Filters URL is ' . $this->baseFilterURL . '<br>';
           	$response = wp_remote_get($this->baseFilterURL);
			if (is_wp_error($response)) {
        		$errorMsg = '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we are unable to reach our animal database. Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=AnimalsFirst Integration Error">email</a> to report the problem.</p>';
        		return array(constant('ERROR'), $errorMsg);
			} else {   
        		//echo '<pre>ANIMALSFIRST FILE CONTENTS<br>'; print_r($response); echo '</pre>';
        		if (array_key_exists('response', $response)) {
        			$queryStatus = $response['response']['code'];
        			if ( ($queryStatus == 200) ) {
        				//$outputWS = wp_remote_retrieve_body($response);
        		    	$filtersArray = json_decode($response['body'], 1);       
            			//echo '<pre>ANIMALSFIRST FILTER VALUES CONTENTS<br>'; print_r($filtersArray); echo '</pre>';
        			} else {
		            	//echo 'Support Email = ' . $supportEmail . '<br>';          
		            	return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we encountered a technical issue preparing your animal search.<br>Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=AnimalsFirst Integration Error">email</a> to report the problem.</p>';
		        	}
        		} else {
		            //echo 'Support Email = ' . $supportEmail . '<br>';          
		            return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we encountered a technical issue preparing your animal search.<br>Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=AnimalsFirst Integration Error">email</a> to report the problem.</p>';
		        }
		    }
		} else {
        	$errorMsg = '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Check PetMatchPro configuration, no details provided to search our animal database. Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=AnimalsFirst Integration Error">email</a> to report the problem.</p>';
        	return array(constant('ERROR'), $errorMsg);
		}

		/* Create Individual Filter Arrays */
		//echo '<pre>Processing Filter Values for DETAILS<br>'; print_r($details); echo '</pre>';
		$all = array(constant('ALL')=>constant('ALL'));
		if ( (is_array($filtersArray)) && (array_key_exists(constant('ANIMALSFIRST_AGE'), $filtersArray)) ) {
			$ages = $filtersArray[constant('ANIMALSFIRST_AGE')]['values'];
			$speciesAges = [];
            if ( (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $details)) ) {
            	if (strtolower($details[constant('ANIMALSFIRST_SPECIES')]) == 'dog') {
	            	$counter = 0;
	            	$counterMax = 3;
	            	while ($counter <= $counterMax) {
	            		$speciesAges[$counter] = $ages[$counter];
	            		$counter = $counter + 1;
	            	}
	            } elseif (strtolower($details[constant('ANIMALSFIRST_SPECIES')]) == 'cat') {
	            	$counter = 4;
	            	$counterMax = 7;
	            	while ($counter <= $counterMax) {
	            		$speciesAges[$counter] = $ages[$counter];
	            		$counter = $counter + 1;
	            	}
	 			} else {
	 				$speciesAges = $ages;
	 			}
			} else {
 				$speciesAges = $ages;
 			}		 			
			$ages = array_combine($speciesAges, $speciesAges);
			$ages = array_combine($ages, $ages);
			$ages = $all + $ages;
			//echo '<pre>AGE FILTER VALUES<br>'; print_r($ages); echo '</pre>';
            $filterValues[constant('ANIMALSFIRST_AGE')][$ageLabel] = $ages;																		
		}
		
		if ( (is_array($filtersArray)) && (array_key_exists(constant('ANIMALSFIRST_ALTERED'), $filtersArray)) ) {
			$alteredValues = $filtersArray[constant('ANIMALSFIRST_ALTERED')]['values'];
			$altereds = array_combine($alteredValues, $alteredValues);
			$altereds = $all + $altereds;
			//echo '<pre>ALTERED FILTER VALUES<br>'; print_r($altereds); echo '</pre>';
            $filterValues[constant('ANIMALSFIRST_ALTERED')][$alteredLabel] = $altereds;																					
		}

		$colorMapKey = 	constant('ANIMALSFIRST_COLOR_PRIMARY') . '_map';
		//echo 'Color Map Key is '. $colorMapKey . '.<br>';	
		if ( (is_array($filtersArray)) && (array_key_exists($colorMapKey, $filtersArray)) ) {
			$colorValuesArray = $filtersArray[$colorMapKey]['values'];
			//echo '<pre>Color Values Array<br>'; print_r($colorValuesArray); echo '</pre>';
			$colorValues = [];
			foreach ($colorValuesArray as $colorKey => $colorValueArray) {
				//echo 'Processing Color Key ' . $colorKey . '.<br>';
				$colorValues[$colorKey] = $colorValueArray['value'];
			}
			//echo '<pre>Color Values<br>'; print_r($colorValues); echo '</pre>';
			$colors = array_combine($colorValues, $colorValues);
			$colors = $all + $colors;
			//echo '<pre>PRIMARY COLOR FILTER VALUES<br>'; print_r($colors); echo '</pre>';
            $filterValues[constant('ANIMALSFIRST_COLOR_PRIMARY')][$colorLabel] = $colors;																					
		}		
			
		if ( (is_array($filtersArray)) && (array_key_exists(constant('ANIMALSFIRST_DECLAWED'), $filtersArray)) ) {
			$declawedValues = $filtersArray[constant('ANIMALSFIRST_DECLAWED')]['values'];
			$declaweds = array_combine($declawedValues, $declawedValues);
			$declaweds = $all + $declaweds;
			//echo '<pre>DECLAWED FILTER VALUES<br>'; print_r($declaweds); echo '</pre>';
            $filterValues[constant('ANIMALSFIRST_DECLAWED')][$declawedLabel] = $declaweds;																					
		}
					
		if ( (is_array($filtersArray)) && (array_key_exists(constant('ANIMALSFIRST_GENDER'), $filtersArray)) ) {
			$gendersValues = $filtersArray[constant('ANIMALSFIRST_GENDER')]['values'];
			//echo '<pre>GENDER VALUES<br>'; print_r($gendersValues); echo '</pre>';
			$genders = array_combine($gendersValues, $gendersValues);
			//echo '<pre>GENDER COMBINED<br>'; print_r($genders); echo '</pre>';		
			$genders = $all + $genders;
			//echo '<pre>GENDER FILTER VALUES<br>'; print_r($genders); echo '</pre>';
            $filterValues[constant('ANIMALSFIRST_GENDER')][$genderLabel] = $genders;																					
		}
		
		if ( (is_array($filtersArray)) && (array_key_exists(constant('ANIMALSFIRST_SIZE'), $filtersArray)) ) {
			$sizes = $filtersArray[constant('ANIMALSFIRST_SIZE')]['values'];
			$sizes = array_combine($sizes, $sizes);
			$sizes = $all + $sizes;
			//echo '<pre>SIZE FILTER VALUES<br>'; print_r($sizes); echo '</pre>';
            $filterValues[constant('ANIMALSFIRST_SIZE')][$sizeLabel] = $sizes;																					
		}

          if ( (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $details)) ) {        
            if (strtolower($details[constant('ANIMALSFIRST_SPECIES')]) == 'dog') {
				if ( (is_array($filtersArray)) && (array_key_exists(constant('ANIMALSFIRST_BREED_PRIMARY'), $filtersArray)) ) {
					$breedsDog = $filtersArray[constant('ANIMALSFIRST_BREED_PRIMARY')][strtolower($details[constant('ANIMALSFIRST_SPECIES')])][0];
					$breedsDog = array_combine($breedsDog, $breedsDog);
					$breedsDog = $all + $breedsDog;
					//echo '<pre>DOG BREEDS FILTER VALUES<br>'; print_r($breedsDog); echo '</pre>';
                    $filterValues[constant('ANIMALSFIRST_BREED_PRIMARY')][$breedLabel] = $breedsDog;					
				}
			} elseif (strtolower($details[constant('ANIMALSFIRST_SPECIES')]) == 'cat') {
				if ( (is_array($filtersArray)) && (array_key_exists(constant('ANIMALSFIRST_BREED_PRIMARY'), $filtersArray)) ) {
					$breedsCat = $filtersArray[constant('ANIMALSFIRST_BREED_PRIMARY')][strtolower($details[constant('ANIMALSFIRST_SPECIES')])][0];
					$breedsCat = array_combine($breedsCat, $breedsCat);
					$breedsCat = $all + $breedsCat;
					//echo '<pre>CAT BREEDS FILTER VALUES<br>'; print_r($breedsCat); echo '</pre>';
                    $filterValues[constant('ANIMALSFIRST_BREED_PRIMARY')][$breedLabel] = $breedsCat;										
				}
			} elseif (strtolower($details[constant('ANIMALSFIRST_SPECIES')]) == 'rabbit') {
				if ( (is_array($filtersArray)) && (array_key_exists(constant('ANIMALSFIRST_BREED_PRIMARY'), $filtersArray)) ) {
					$breedsRabbit = $filtersArray[constant('ANIMALSFIRST_BREED_PRIMARY')][strtolower($details[constant('ANIMALSFIRST_SPECIES')])][0];
					$breedsRabbit = array_combine($breedsRabbit, $breedsRabbit);
					$breedsRabbit = $all + $breedsRabbit;
					//echo '<pre>RABBIT BREEDS FILTER VALUES<br>'; print_r($breedsRabbit); echo '</pre>';
                    $filterValues[constant('ANIMALSFIRST_BREED_PRIMARY')][$breedLabel] = $breedsRabbit;										
				}
			} elseif (strtolower($details[constant('ANIMALSFIRST_SPECIES')]) == 'horse') {
				if ( (is_array($filtersArray)) && (array_key_exists(constant('ANIMALSFIRST_BREED_PRIMARY'), $filtersArray)) ) {
					$breedsHorse = $filtersArray[constant('ANIMALSFIRST_BREED_PRIMARY')][strtolower($details[constant('ANIMALSFIRST_SPECIES')])][0];
					$breedsHorse = array_combine($breedsHorse, $breedsHorse);
					$breedsHorse = $all + $breedsHorse;
					//echo '<pre>HORSE BREEDS FILTER VALUES<br>'; print_r($breedsHorse); echo '</pre>';
                    $filterValues[constant('ANIMALSFIRST_BREED_PRIMARY')][$breedLabel] = $breedsHorse;															
				}
			}
		} else {
			$filterValues[constant('ANIMALSFIRST_BREED_PRIMARY')][$breedLabel] = $all;					
		}			
		
		if ( (is_array($filtersArray)) && (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $filtersArray)) ) {
			$species = $filtersArray[constant('ANIMALSFIRST_SPECIES')]['values'];
			$species = array_combine($species, $species);
			$species = $all + $species;
			//echo '<pre>SPECIES FILTER VALUES<br>'; print_r($species); echo '</pre>';
            $filterValues[constant('ANIMALSFIRST_SPECIES')][$speciesLabel] = $species;																		
		}

		if ( (is_array($details)) && (array_key_exists('filter', $details)) ) {
			$detailsFilters = $details['filter'];
			//echo 'Details Filter Values<br>' . $detailsFilters . '<br>';
			if (str_contains($detailsFilters, constant('ANIMALSFIRST_TYPE'))) {
	            /* Include Default Field Values */
	            $valuesFile = 'pmp-field-values.php';
	            $requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $valuesFile;
	            require($requireFile);     
	            //echo '<pre>FIELD VALUES '; print_r($pmpFieldValues); echo '</pre>';
	            if ($callMethod == constant('ADOPT_METHODTYPE_ANIMALSFIRST')) {
					$filterValues[constant('ANIMALSFIRST_TYPE')][$typeLabel] = $pmpFieldValues['value_types'];
//					$filterValues[constant('ANIMALSFIRST_TYPE')][$typeLabel] = $pmpFieldValues['value_filter_type_adopt'];
				}
			}
		}
		//echo '<pre>FILTER VALUES<br>'; print_r($filterValues); echo '</pre>';	
			
        //remove form filters if defined in shortcode
       	$filterAdminOptionKey = $this->methodValue . '_search_criteria';            
        if (is_array($details)) {
        	//echo '<pre>Details Exist<br>'; print_r($details); echo '</pre>';
            if (array_key_exists('filter', $details)) {
            	//echo 'Processing Filter Options within Details.<br>';
                $unsetOverride = explode(',', $details['filter']);
                //clear filter options default
                $filterOptions = [];
                if (!empty($unsetOverride)) {
	                foreach ($unsetOverride as $item) {
	                    $filterOptions[$item] = $item;
	                }
	            }
            } else { /* Filter by Admin Settings */
            	//echo 'Filter Option Does NOT Exist.<br>';  
        		if ( (is_array($this->FilterAdminOptions)) && (array_key_exists($filterAdminOptionKey, $this->FilterAdminOptions)) ) {
//        		if ( (is_array($this->FilterAdminOptions)) && (array_key_exists('adopt_search_criteria', $this->FilterAdminOptions)) ) {
        			$searchFilters = $this->FilterAdminOptions[$filterAdminOptionKey];
//        			$searchFilters = $this->FilterAdminOptions['adopt_search_criteria'];
        			if (count($searchFilters) > 0) {
        				//echo 'Processing Admin filter Options.<br>';
	        	        $filterOptions = [];
	        	        if (!empty($searchFilters)) {
		    	            foreach ($searchFilters as $item) {
		    	                $filterOptions[$item] = $item;
		    	            }
		    	        }        		
        			}
        		}
        	} 
        } else { /* Filter by Admin Settings */
        	//echo 'Details Do NOT Exist.<br>';        
        	if ( (is_array($this->FilterAdminOptions)) && (array_key_exists($filterAdminOptionKey, $this->FilterAdminOptions)) ) {
//        	if ( (is_array($this->FilterAdminOptions)) && (array_key_exists('adopt_search_criteria', $this->FilterAdminOptions)) ) {
        		$searchFilters = $this->FilterAdminOptions[$filterAdminOptionKey];
//        		$searchFilters = $this->FilterAdminOptions['adopt_search_criteria'];
        		if (count($searchFilters) > 0) {
        			//echo 'Processing Admin Filter Options.<br>';
	                $filterOptions = [];
	                if (!empty($searchFilters)) {
		                foreach ($searchFilters as $item) {
		                    $filterOptions[$item] = $item;
		                }
		            }        		
        		}
        	}
        }
		
        //echo '<pre>Filter Options After Override<br>'; print_r($filterOptions); echo '</pre>'; 
        //echo '<pre>Filter Values After Override<br>'; print_r($filterValues); echo '</pre>'; 
        return (array('filterOptions' => $filterOptions, 'filterValues' => $filterValues));
    }
}