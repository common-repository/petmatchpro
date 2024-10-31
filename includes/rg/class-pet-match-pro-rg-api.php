<?php 
if ( ! function_exists( 'WP_Filesystem' ) ) {
    
    require_once ABSPATH . '/wp-admin/includes/file.php';
} 
class Pet_Match_Pro_RG_Api {
    public $BaseURL;
    public $apiKey;
    //the URL of the current page where it is called to properly apply links
    private $selfUrl;
    private $adoptDetailPageURL;
    private $foundDetailPageURL;
    private $lostDetailPageURL;
    public $PMPlicense;
    public $PMPLicenseType;    /* To Secure Features */
    public $PMPLicenseTypeID;  /* To Secure Features */
    private $PMPAdoptFilterOptions;
    private $PMPAdoptSearchDetails;
    private $PMPAdoptSortOptions;
    private $PMPSearchFilter;
    private $PMPSearchLabels;
    private $adoptFilterKeys;
    private $adoptFilterLabels;
    private $FilterAdminOptions;
    public $generalOptions;
    public $contactOptions;
    private $partialsDir;
    private $partialsPublicDir;
    private $adminFunction;
    private $animalDetailFunction;
    private $searchDetailSource;
    private $allAPIFunction;

	public 	$integrationPartner;
    public 	$displayIcons;  
    public 	$displayIconsSearch;  
    public 	$searchOutput;
    
    private $locationExclusionArray;
    private $locationFilterArray;
    private $locationFilterOther;
    
    public $defaultMethod;
    public $defaultType;
    public $methodValue;    

    public function __construct($authKey, $activated) {
        $this->BaseURL = 'https://api.rescuegroups.org/http/json';      
        //echo 'Auth Key = ' . $authKey . '<br>';
        $this->apiKey = $authKey;
        $this->selfUrl = '';
        $this->PMPlicense = $activated;
        
        /* Get License Type to Secure Features */
        $this->PMPLicenseType = get_option('PMP_License_Type');      
        $this->PMPLicenseTypeID = (int) get_option('PMP_License_Type_ID');  
        
        //echo 'Option License Type ID = ' . $this->PMPLicenseTypeID . '<br>';
        if ( $this->PMPLicenseTypeID == 0 ) {
            $this->PMPLicenseTypeID = constant('FREE_LEVEL');
        }
        //echo 'License Type: ' . $this->PMPLicenseType . '(' . $this->PMPLicenseTypeID . ')<br>';                            
        
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
	        if ( (array_key_exists('details_page_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS'), $this->generalOptions)) && (is_numeric($this->generalOptions['details_page_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS')])) && (intval($this->generalOptions['details_page_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS')]) > 0) ) {
	            $adoptDetailPageURL = $this->generalOptions['details_page_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS')];
	            $detailsPage = get_post($adoptDetailPageURL);
	            $this->adoptDetailPageURL = get_home_url() . '/' . $detailsPage->post_name;
	         } else {
	           	$this->adoptDetailPageURL = get_home_url();
	         }
		} else {
           	$this->adoptDetailPageURL = get_home_url();		
		}
         //echo 'Adopt Details Page URL is ' . $this->adoptDetailPageURL . '<br>';
                
        $this->PMPSearchFilter = [];
        //all parameters saved on the PP Filter Options Admin Settings
        $this->FilterAdminOptions = get_option('pet-match-pro-filter-options');
        //echo '<pre>FILTER ADMIN OPTIONS<br>'; print_r($this->FilterAdminOptions); echo '</pre><br>';
        if ( is_array($this->FilterAdminOptions) ) { 
            if (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_criteria', $this->FilterAdminOptions)) { /*RMB Check If Adopt Search Criteria Exist */
                $this->PMPAdoptFilterOptions = $this->FilterAdminOptions[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_criteria'];
                //echo '<pre>ADOPT SEARCH FILTERS '; print_r($this->PMPAdoptFilterOptions); echo '</pre>';

	        	$sortField = constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_' . constant('RESCUEGROUPS_ORDERBY');      
                if (array_key_exists($sortField, $this->FilterAdminOptions)) {
                    $this->PMPAdoptSortOptions = $this->FilterAdminOptions[$sortField];  
                    //echo '<pre>ADOPT SEARCH ORDERBY OPTIONS '; print_r($this->PMPAdoptSortOptions); echo '</pre>';        
                } else {              
                    $this->PMPAdoptSortOptions = array(constant('RESCUEGROUPS_NAME') => 'Name');
                }
            }
            if (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS')   . '_search_details', $this->FilterAdminOptions)) { /*RMB Check If Adopt Search Detail Criteria Exist */     
                $this->PMPAdoptSearchDetails = $this->FilterAdminOptions[constant('ADOPT_METHODTYPE_RESCUEGROUPS')   . '_search_details'];
            }
        }

       	$this->partialsDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/' . constant('PARTIALS_DIR') . '/';
       	$this->partialsPublicDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('PUBLIC_DIR') . '/' . constant('PARTIALS_DIR') . '/';

        /* Include Class Defining Functions for Processing Animal Searches */
        $functionsFile = 'class-pet-match-pro-functions.php';
        require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/' . $functionsFile;
        $this->adminFunction = new PetMatchProFunctions();       
        
        /* Include Class Defining Animal Detail Functions */
        $detailsFile = 'class-pet-match-pro-rg-detail-functions.php';
        require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/' . $detailsFile;      
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
	        if ( ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_search_icons_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS')]) && (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_search_icons', $this->generalOptions)) ) {
				$this->displayIconsSearch = 1;
			} 
		}
		//echo 'Display Search Icons = ' . $this->displayIconsSearch . '<br>';
		
        //echo 'level_detail_icons_adopt = ' . $pmpOptionLevelsGeneral['level_detail_icons_adopt'] . '<br>';	
        //echo 'License ID = ' . $this->PMPLicenseTypeID . '<br>';
        //echo '<pre>adopt_animal_detail_icons<br>'; print_r($this->generalOptions['adopt_animal_detail_icons']); echo '</pre>';
		$this->displayIcons = 0;
		if (is_array($this->generalOptions)) {
	        if ( ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_detail_icons_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS')]) && (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_detail_icons', $this->generalOptions)) ) {
				$this->displayIcons = 1;
			}
		}
		//echo 'Display Detail Icons = ' . $this->displayIcons . '<br>';
		
        $this->contactOptions = get_option('pet-match-pro-contact-options');	
        
        /* Set Location Options */
        $this->locationExclusionArray = $this->allAPIFunction->locationExclusions();
        //echo '<pre>LOCATION EXCLUSIONS<br>'; print_r($this->locationExclusionArray); echo '</pre>';
        $this->locationFilterArray = $this->allAPIFunction->locationFilters();
        //echo '<pre>LOCATION FILTERS<br>'; print_r($this->locationFilterArray); echo '</pre>';
        if (is_array($this->generalOptions)) {
	        if ( array_key_exists('location_filter_other', $this->generalOptions) ) {
	            $this->locationFilterOther = $this->generalOptions['location_filter_other'];
	            //echo 'Location Other = ' . $this->locationFilterOther . '.<br>';
	        }        
	    } else {
	    	$this->locationFilterOther = '';	
	    }
	    
		$this->defaultMethod = constant('ADOPT_METHODTYPE_RESCUEGROUPS');
	    
    }
       
	/* Add Rescue Groups API Call Functions */
	private function postJson($url, $json) {
    	// create a new cURL resource
    	$ch = curl_init();
    	// set options, url, etc.
    	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	//curl_setopt($ch, CURLOPT_VERBOSE, true);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	// grab URL and pass it to the browser
    	$result = curl_exec($ch);
    	if (curl_errno($ch)) {
    	    // TODO: Handle errors here
    	    return array(
    	            "result" => "",
    	            "status" => "error",
    	            "error" => curl_error($ch)
    	    );
    	} else {
    	    // close cURL resource, and free up system resources
    	    curl_close($ch);
    	}
    	return array(
    	        "status" => "ok",
    	        "error" => "",
    	        "result" => $result,
    	);
	}

	private function postToApi($data) {
	    $resultJson = postJson($GLOBALS["httpApiUrl"], json_encode($data));
	    if ($resultJson["status"] == "ok") {
	        $result = json_decode($resultJson["result"], true);
	        $jsonError = getJsonError();
	        if (!$jsonError && $resultJson["status"] == "ok") {
	            return $result;
	        } else {
	            return array (
	                    "status" => "error",
	                    "text" => $result["error"] . $jsonError,
	                    "errors" => []
	            );
	        }
	    } else return false;
	}

	private function getJsonError() {
	    switch (json_last_error()) {
	        case JSON_ERROR_NONE:
	            return false;
	            break;
	        case JSON_ERROR_DEPTH:
			            return "Maximum stack depth exceeded";
	            break;
	        case JSON_ERROR_STATE_MISMATCH:
	            return "Underflow or the modes mismatch";
	            break;
	        case JSON_ERROR_CTRL_CHAR:
	            return "Unexpected control character found";
	            break;
	        case JSON_ERROR_SYNTAX:
	            return "Syntax error, malformed JSON";
	            break;
	        case JSON_ERROR_UTF8:
	            return "Malformed UTF-8 characters, possibly incorrectly encoded";
	            break;
	        default:
	            return "Unknown error";
	            break;
	    }
	}     
       
    public function fetch_rg_data($urlData, $method, $items = []) {
        /* ************************************************************************* */
        /* *** Script to execute and collect the results of the webservices call *** */
        // HTTP GET command to obtain the data
        //echo '<pre>FETCH DATA called with Items<br>'; print_r($items); echo '</pre>';
        //echo '<pre>FETCH METHOD ' . $method . ' CALLED WITH<br>'; print_r($urlData); echo '</pre>';
        if ( array_key_exists('website_support_email', $this->contactOptions) ) {
	        $supportEmail = $this->contactOptions['website_support_email'];             
 	    } else {
			$supportEmail = 'No Support Email Address Provided';
		}
		//echo 'Support Email = ' . $supportEmail . '<br>'; 
		//echo 'Fetching Data with URL ' . $this->BaseURL . '<br>'; 
        if ( !is_null($urlData) ) {
	    	$resultsJson = $this->postJson($this->BaseURL, json_encode($urlData));
	    	//echo '<pre>JSON RESULTS<br>'; print_r($resultsJson); echo '</pre>';;
	    	if ($resultsJson["status"] == "ok") {
	        	$results = json_decode($resultsJson["result"], true);
				//echo '<pre>SEARCH RESULTS<br>'; print_r($results); echo '</pre>';
				if (array_key_exists('foundRows', $results)) {
					if ( (($results['foundRows'] == 1) && (array_key_exists(0, $results['data'])) && (!empty($results['data'][0]))) || ($results['foundRows'] > 1) ) {
			        	$jsonError = $this->getJsonError();
			        	if (!$jsonError && $resultsJson["status"] != "ok") {
							$errorArray = array (
			                    			"status" => "error",
			                    			"text" => $results["error"] . $jsonError,
			                    			"errors" => []
			            					);
				            return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we are unable to reach our animal database. Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=RescueGroups Integration Error">email</a> to report the problem.</p>';
						}
					} else {
				    	return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately no records were returned from our animal database. Please confirm your API key and try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=RescueGroups API Key Error">email</a> to report the problem.</p>';
					}	
				} else {
			    	return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately no records were returned from our animal database. Please confirm your API key and try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=RescueGroups API Key Error">email</a> to report the problem.</p>';
				}			           								           
	        } elseif ($resultsJson["status"] == "error") {
		      	return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we are unable to reach our animal database. Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=RescueGroups Integration Error">email</a> to report the problem.</p>';
			}	   
		} else {
				return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Check PetMatchPro configuration, no details provided for search to our animal database. Please check the instructions or request support.</p>';
		}
        // Display the results of the webservices call for the AdoptSearch method
        //should define which method is called to properly call function
        $callFunc = $method;
        //echo 'Call Function = ' . $callFunc . '<br>';
        if ($callFunc == constant('ADOPT_METHODTYPE_RESCUEGROUPS') . 'Search') {
            //echo '<pre>Preparing to Call Search Output with Parameters '; print_r($items); echo '</pre>';
            //echo 'Details Page URL = ' . $this->adoptDetailPageURL . '<br>';
            return $this->outputSearch($this->adoptDetailPageURL, $results, $items, constant('ADOPT_METHODTYPE_RESCUEGROUPS') . 'Search');
            //echo 'Output Search Complete!<br>';
        } else if ($callFunc == constant('ADOPT_METHODTYPE_RESCUEGROUPS') . 'Details') {
            //echo '<pre>Preparing to Call Detail Output with Parameters '; print_r($items); echo '</pre>';
            return $this->outputDetails($results, $items, constant('ADOPT_METHODTYPE_RESCUEGROUPS') . 'Details');
            //echo 'Output Detail Complete!<br>';
        } elseif ($callFunc == 'singleDetail') {
            return $results;
        }
        return;
    }

    public function createSearch($items, $callFunc) {
        /* Check for API KEY Before Creating Search */
        //echo 'API Key = ' . $this->apiKey . '.<br>';
        if ( isset($this->apiKey) && !empty($this->apiKey) ) {
            //echo '<pre>Search Called with Values<br>'; print_r($items); echo '</pre>';
            
            //$this->PMPLicenseTypeID = 3;
        
            /* Include Default Field Values */
            $valuesFile = 'pmp-field-values.php';  
            $requireFile = $this->partialsDir . $valuesFile;
            require($requireFile);     
            //echo '<pre>FIELD VALUES '; print_r($pmpFieldValues); echo '</pre>';
        
            //echo 'Species Key in Items Array ' . array_key_exists('animalspecies', $items) . '<br>';
            //echo 'License Type ID = ' . $this->PMPLicenseTypeID . ' with free value of ' . constant('FREE_LEVEL') . '<br>';
        
            /* Get Free Species Values */
            $speciesFree = explode(",", constant('FREE_SPECIES'));          
            //echo 'Species in Free List ' . in_array(strtolower($items['species']), $speciesFree) . '<br>';   
                 
            if ( (array_key_exists(constant('RESCUEGROUPS_SPECIES'), $items)) && ($this->PMPLicenseTypeID == constant('FREE_LEVEL')) && (!in_array(strtolower($items[constant('RESCUEGROUPS_SPECIES')]), $speciesFree)) ) {
                $itemsSpecies = ucwords($items['animalspecies']);
                return '<div class ="pmp-error-message"><a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required to Search ' . ucwords($itemsSpecies) . 's</div></div>';                  
            } else {        
                $urlWSCompleteOUT = "";
                $defaultSort = 'animalName';
                $orderBy = $this->generalOptions['order_by'];
                if ( !isset($orderBy) ) {
                    $orderBy = $defaultSort;
                }
                //echo 'Order by = ' . $orderBy . '<br>';
                /* Select Default Orderby Value in Search Form */
                if ( !isset($_REQUEST[constant('RESCUEGROUPS_ORDERBY')]) ) {
                    $_REQUEST[constant('RESCUEGROUPS_ORDERBY')] = $orderBy;
                }   
                
                $defaultOrder = 'asc';
                $sortOrder = $this->generalOptions['sort_order'];
                if ( !isset($sortOrder) ) {
                    $sortOrder = $defaultOrder;
                }
                /* Select Default Search Order Value in Search Form */
                if ( !isset($_REQUEST[constant('RESCUEGROUPS_SORTORDER')]) ) {
                    $_REQUEST[constant('RESCUEGROUPS_SORTORDER')] = $sortOrder;
                }                   
                //echo 'Sort Order = ' . $sortOrder . '<br>';

                $defaults = array(constant('RESCUEGROUPS_DECLAWED') => constant('ALL'), constant('RESCUEGROUPS_AGE') => constant('ALL'), constant('RESCUEGROUPS_FOSTER') => constant('ALL'), constant('RESCUEGROUPS_OK_CATS') => constant('ALL'), constant('RESCUEGROUPS_OK_DOGS') => constant('ALL'), constant('RESCUEGROUPS_OK_KIDS') => constant('ALL'), constant('RESCUEGROUPS_SPECIAL_NEEDS') => constant('ALL'), constant('RESCUEGROUPS_SPECIAL_DIET') => constant('ALL'), constant('RESCUEGROUPS_SPECIES') => constant('ALL'), constant('RESCUEGROUPS_YARD') => constant('ALL'), constant('RESCUEGROUPS_ORDERBY') => $orderBy, constant('RESCUEGROUPS_SORTORDER') => $sortOrder);
                //echo '<pre>Defauts BEFORE Processing<br>'; print_r($defaults); echo '</pre>';
                
                $species = $pmpFieldValues['value_filter_animalspecies_reverse_all'];
                //echo '<pre>SPECIES VALUES<br>'; print_r($species); echo '</pre>';
                
                $animalSpecies = '';
                
                //echo '<pre>CREATE SEARCH ITEMS<br>'; print_r($items); echo '</pre>';

                $queryString = [];
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
		            //echo '<pre>Search Items AFTER Processing<br>'; print_r($items); echo '</pre>';
                	                                  
                    $this->adoptFilterKeys = array_keys($this->adminFunction->Filter_Option_Values(constant('ADOPT_METHODTYPE_RESCUEGROUPS'), $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER')));
                    //echo '<pre>ADOPT FILTER KEYS<br>'; print_r($this->adoptFilterKeys); echo '</pre>';
                    
                    foreach ($items as $key => $onlyFilter) {
                       // echo 'Processing Item Key ' . $key . ' with Value ' . $onlyFilter . '<br>';
                        if (in_array(strtolower($key), array_map('strtolower', $this->adoptFilterKeys))) {
                            //$queryString
                            if ($key == constant('RESCUEGROUPS_SPECIES')) {
                                //echo 'Key = ' . $key . ' Value = ' . $onlyFilter . ' with Species Value of ' .  $species[$onlyFilter] . '<br>';
                                $defaults[$key] = strtolower($onlyFilter);
                                //echo 'Set Defaults Species to ' . $defaults[$key] . '<br>';
                            } else {
                                $defaults[$key] = strtolower($onlyFilter);
                            }
                        }
                        //echo $onlyFilter;
                    }
                    //echo '<pre>Defauts AFTER Processing<br>'; print_r($defaults); echo '</pre>';
                    
                    //echo '<pre>POST VARIABLES '; print_r($_POST); echo '</pre>';
					//echo 'items[animalspecies] exists as '. $items['animalspecies'] . '<br>';    
		        	//echo 'post[animalspecies] exists as '. $_POST['animalspecies'] . '<br>';    
                   
                    if (array_key_exists(constant('RESCUEGROUPS_SPECIES'), $items)) {
                    	if ( isset($_POST[constant('RESCUEGROUPS_SPECIES')]) ) {
 							$animalSpecies = sanitize_text_field($_POST[constant('RESCUEGROUPS_SPECIES')]);
 						} else {
 							$animalSpecies = $defaults[constant('RESCUEGROUPS_SPECIES')];
 						}
 					}
                    
                    if (array_key_exists(constant('RESCUEGROUPS_ORDERBY'), $items)) {
                    	if ( isset($_POST[constant('RESCUEGROUPS_ORDERBY')]) ) {
                        	$orderBy = sanitize_text_field($_POST[constant('RESCUEGROUPS_ORDERBY')] ); 
						} else {
 							$orderby = $defaults[constant('RESCUEGROUPS_ORDERBY')];
						}
                    }
                    //var_dump($defaults);
                } else {
                    //just plane shortcode
                    $animalSpecies = (isset($_POST[constant('RESCUEGROUPS_SPECIES')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_SPECIES')] ) : constant('ALL');
                    }
        
        		//echo 'Animal Species = ' . $animalSpecies . '<br>';
                $declawed = (isset($_POST[constant('RESCUEGROUPS_DECLAWED')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_DECLAWED')] ) : constant('ALL');
                $generalAge = (isset($_POST[constant('RESCUEGROUPS_AGE')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_AGE')] ) : constant('ALL');
                $needsFoster = (isset($_POST[constant('RESCUEGROUPS_FOSTER')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_FOSTER')] ) : constant('ALL');
                $okWithCats = (isset($_POST[constant('RESCUEGROUPS_OK_CATS')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_OK_CATS')] ) : constant('ALL');
                $okWithDogs = (isset($_POST[constant('RESCUEGROUPS_OK_DOGS')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_OK_DOGS')] ) : constant('ALL');
                $okWithKids = (isset($_POST[constant('RESCUEGROUPS_OK_KIDS')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_OK_KIDS')] ) : constant('ALL');
                $searchString = (isset($_POST[constant('RESCUEGROUPS_SEARCH_STRING')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_SEARCH_STRING')] ) : null;               
                $sex = (isset($_POST[constant('RESCUEGROUPS_SEX')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_SEX')] ) : constant('ALL');
                $specialDiet = (isset($_POST[constant('RESCUEGROUPS_SPECIAL_DIET')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_SPECIAL_DIET')] ) : constant('ALL');
                $specialNeeds = (isset($_POST[constant('RESCUEGROUPS_SPECIAL_NEEDS')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_SPECIAL_NEEDS')] ) : constant('ALL');
                $yardRequired = (isset($_POST[constant('RESCUEGROUPS_YARD')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_YARD')] ) : constant('ALL');

                //echo 'Orderby = ' . $orderBy . '<br>';
                $sortOrder = (isset($_POST[constant('RESCUEGROUPS_SORTORDER')])) ? sanitize_text_field($_POST[constant('RESCUEGROUPS_SORTORDER')] ) : $sortOrder;
                //echo 'Sort Order = ' . $sortOrder . '<br>';
                
				//Configure Filter Processing Value
				$filterProcessing = "(1 and 2 and 3";
				if ($sex != constant('ALL')) {
					$filterProcessing = $filterProcessing . ' and 4';
				}
				if ($generalAge != constant('ALL')) {
					$filterProcessing = $filterProcessing . ' and 5';
				}
				if ($needsFoster != constant('ALL')) {
					$filterProcessing = $filterProcessing . ' and 6';
				}
				if ($specialNeeds != constant('ALL')) {
					$filterProcessing = $filterProcessing . ' and 7';
				}
				if ($specialDiet != constant('ALL')) {
					$filterProcessing = $filterProcessing . ' and 8';
				}			
				if ($okWithCats != constant('ALL')) {
					$filterProcessing = $filterProcessing . ' and 9';
				}
				if ($okWithDogs != constant('ALL')) {
					$filterProcessing = $filterProcessing . ' and 10';
				}
				if ($okWithKids != constant('ALL')) {
					$filterProcessing = $filterProcessing . ' and 11';
				}
				if ($declawed != constant('ALL')) {
					$filterProcessing = $filterProcessing . ' and 12';
				}			
				if ($yardRequired!= constant('ALL')) {
					$filterProcessing = $filterProcessing . ' and 13';
				}
				if (!(is_null($searchString))) {
					$filterProcessing = $filterProcessing . ' and 14';
				}	
				$filterProcessing = $filterProcessing . ')';
				//echo '<pre>FILTER PROCESSING ARRAY'; print_r($filterProcessing); echo '</pre>';

				//Create Array for Call to RescueGroups.org API	
		        $resultLimit = $this->generalOptions['search_result_limit'];
		        if ( (is_numeric($resultLimit)) && ($resultLimit > 0) ) {
		        	$resultLimit = $resultLimit;
		        } else {
		        	$resultLimit = constant('SEARCH_RESULT_LIMIT');
		        }
		        
		        $orgID = $this->generalOptions['organization_id'];
		        
				$apiData = array(
					"apikey" => $this->apiKey,
 					"objectType" => "animals",
 					"objectAction" => "publicSearch",
  					"search" => array ("resultStart" => 0,
    									"resultLimit" => $resultLimit,
    									"resultSort" => $orderBy,
    									"resultOrder" => $sortOrder,
    									"calcFoundRows" => "Yes",
    									"filters" => array(
    		  								array("fieldName" => "animalOrgID",
    		    								"operation" => "equals",
    		    								"criteria" => $orgID,
    		  								),
    		  								array("fieldName" => "animalStatus",
    		    								"operation" => "equals",
    		    								"criteria" => "Available",
    		  								),    			
    		  								array("fieldName" => "animalSpecies",
    		    								"operation" => "equals",
    		    								"criteria" => $animalSpecies,
    		  								),
    		  								array("fieldName" => "animalSex",
    		    								"operation" => "equals",
    		    								"criteria" => $sex,
    		  								),    		  			
    		  								array("fieldName" => "animalGeneralAge",
    		    								"operation" => "equals",
    		    								"criteria" => $generalAge,
    		  								),    		  			
    		  								array("fieldName" => "animalNeedsFoster",
    		    								"operation" => "equals",
    		    								"criteria" => $needsFoster,
    		  								),    		  			
    		  								array("fieldName" => "animalSpecialneeds",
    		    								"operation" => "equals",
    		    								"criteria" => $specialNeeds,
    		  								),    		  			
    		  								array("fieldName" => "animalSpecialDiet",
    		    								"operation" => "equals",
    		    								"criteria" => $specialDiet,
    		  								),      		  			
    		  								array("fieldName" => "animalOKWithCats",
    		    								"operation" => "equals",
    		    								"criteria" => $okWithCats,
    		  								),    		  			
    		  								array("fieldName" => "animalOKWithDogs",
    		    								"operation" => "equals",
    		    								"criteria" => $okWithDogs,
    		  								),    		  			
    		  								array("fieldName" => "animalOKWithKids",
    		    								"operation" => "equals",
    		    								"criteria" => $okWithKids,
    		  								),    	
    		  								array("fieldName" => "animalDeclawed",
    		    								"operation" => "equals",
    		    								"criteria" => $declawed,
    		  								),    		    		  				  			
    		  								array("fieldName" => "animalYardRequired",
    		    								"operation" => "equals",
    		    								"criteria" => $yardRequired,
    		  								),    		    		  				  			
    		  								array("fieldName" => "animalSearchString",
    		    								"operation" => "contains",
    		    								"criteria" => trim($searchString),
    		  								),    		    		  				  			
    									),
    									"filterProcessing" => $filterProcessing,
    									"fields" => array("animalID",
    											"animalActivityLevel",
    											"animalAdoptionFee",
    											"animalAdoptionPending",
    											"animalAffectionate",
    											"animalGeneralAge",
    											"animalHasAllergies",
  												"animalAltered",
  												"animalApartment",
  												"animalBreed",
  												"animalMixedBreed",
  												"animalPrimaryBreed",
  												"animalSecondaryBreed",
  												"animalGoodInCar",
  												"animalCoatLength",
  												"animalColorDetails",
  												"animalEyeColor",
  												"animalColor",
  												"animalNeedsCompanionAnimal",
  												"animalCourtesy",
  												"animalCratetrained",
  												"animalAvailableDate",
  												"animalBirthdate",
  												"animalBirthdateExact",
  												"animalFound",
  												"animalFoundDate",
  												"animalUpdatedDate",											
  												"animalDeclawed",
  												"animalDescriptionPlain",
  												"animalSummary",
  												"animalDrools",
  												"animalEagerToPlease",
  												"animalEarType",
  												"animalEnergyLevel",
  												"animalEscapes",
  												"animalEventempered",
  												"animalExerciseNeeds",
  												"animalFence",
  												"animalFetches",
  												"animalNeedsFoster",
  												"animalFound",
  												"animalFoundPostalcode",
  												"animalGentle",
  												"animalGoofy",
  												"animalGroomingNeeds",
  												"animalHearingImpaired",
  												"animalHousetrained",
  												"animalNotHousetrainedReason",
  												"animalHypoallergenic",
  												"animalIndependent",
  												"animalIndoorOutdoor",
  												"animalIntelligent",
  												"animalLap",
  												"animalLeashtrained",
  												"locationName",
  												"locationAddress",
  												"locationCity",
  												"locationCountry",
  												"locationPhone",
  												"locationState",
  												"locationUrl",
  												"locationPostalcode",
  												"animalDistinguishingMarks",
  												"animalPattern",
  												"animalOngoingMedical",
  												"animalMicrochipped",
  												"animalName",
  												"animalNewPeople",
  												"animalObedienceTraining",
  												"animalObedient",
  												"animalOKWithAdults",
  												"animalOKWithCats",
  												"animalNoCold",
  												"animalOKWithDogs",
  												"animalNoFemaleDogs",
  												"animalNoLargeDogs",
  												"animalNoMaleDogs",
  												"animalNoSmallDogs",
  												"animalOKWithFarmAnimals",
  												"animalNoHeat",
  												"animalOKWithKids",
  												"animalOlderKidsOnly",
  												"animalOKForSeniors",
  												"animalOwnerExperience",
  												"animalPictures",
  												"animalThumbnailUrl",
  												"animalPlayful",
  												"animalPredatory",
  												"animalUrl",
  												"animalProtective",
  												"animalSex",
  												"animalShedding",
  												"animalSizeCurrent",
  												"animalSizePotential",
  												"animalGeneralSizePotential",
  												"animalSizeUOM",
  												"animalSkittish",
  												"animalSpecialDiet",
  												"animalSpecialneeds",
  												"animalSpecialneedsDescription",
  												"animalSpecies",
  												"animalSponsorable",
  												"animalSponsors",
  												"animalSponsorshipDetails",
  												"animalSponsorshipMinimum",
  												"animalSwims",
  												"animalStatus",
  												"animalTailType",
  												"animalTimid",
  												"animalPlaysToys",
  												"animalUptodate",
  												"animalVideoUrls",
  												"animalSightImpaired",
  												"animalVocal"
										)
  								),
				);
				//echo '<pre>CALL TO API'; print_r($apiData); echo '</pre>';
            }
                //echo 'Fetching URL = ' . $urlWSCompleteOUT . '<br>';
                //echo '<pre>Fetching Data with Parameters<br>'; print_r($items); echo '</pre>';
                
                /* Assign Details from Admin if Not Provided im Shortcode */
                if ( array_key_exists('details', $items) ) {
                    $this->searchDetailSource = 'shortcode';
                    return $this->fetch_rg_data($apiData, $callFunc, $items);
                } else {
                    $this->searchDetailSource = 'admin';
                    //echo 'Called with Method ' . $callFunc . '<br>';
                    if ( $callFunc == 'foundSearch' ) {
                        $detailPrefix = 'found';
                    } elseif ( $callFunc == 'lostSearch' ) {
                        $detailPrefix = 'lost';
                    } else {
                        $detailPrefix = constant('ADOPT_METHODTYPE_RESCUEGROUPS');
                    }                    
                    $detailKey = $detailPrefix . '_search_details';
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
                    //echo '<pre>CREATE SEARCH ITEMS<br>'; print_r($items); echo '</pre>';
                    return $this->fetch_rg_data($apiData, $callFunc, $items);
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
        
		if ( $items ) { /* RMB */
			$apiData = array("apikey" => $this->apiKey,
							"objectType" => "animals",
							"objectAction" => "publicView",
							"values" => array (
								array("animalID" => $animalIDIN),
   							),
   							"fields" => array(
   								"animalID",
    							"animalActivityLevel",
    							"animalAdoptionFee",
    							"animalAdoptionPending",
    							"animalAffectionate",
    							"animalGeneralAge",
    							"animalHasAllergies",
  								"animalAltered",
  								"animalApartment",
  								"animalBreed",
  								"animalMixedBreed",
  								"animalPrimaryBreed",
  								"animalSecondaryBreed",
  								"animalGoodInCar",
  								"animalCoatLength",
  								"animalColorDetails",
  								"animalEyeColor",
  								"animalColor",
  								"animalNeedsCompanionAnimal",
  								"animalCourtesy",
  								"animalCratetrained",
  								"animalAvailableDate",
  								"animalBirthdate",
  								"animalBirthdateExact",
  								"animalFound",
  								"animalFoundDate",
  								"animalUpdatedDate",											
  								"animalDeclawed",
  								"animalDescriptionPlain",
  								"animalSummary",
  								"animalDrools",
  								"animalEagerToPlease",
  								"animalEarType",
  								"animalEnergyLevel",
  								"animalEscapes",
  								"animalEventempered",
  								"animalExerciseNeeds",
  								"animalFence",
  								"animalFetches",
  								"animalNeedsFoster",
  								"animalFound",
  								"animalFoundPostalcode",
  								"animalGentle",
  								"animalGoofy",
  								"animalGroomingNeeds",
  								"animalHearingImpaired",
  								"animalHousetrained",
  								"animalNotHousetrainedReason",
  								"animalHypoallergenic",
  								"animalIndependent",
  								"animalIndoorOutdoor",
  								"animalIntelligent",
  								"animalLap",
  								"animalLeashtrained",
  								"locationName",
  								"locationAddress",
  								"locationCity",
  								"locationCountry",
  								"locationPhone",
  								"locationState",
  								"locationUrl",
  								"locationPostalcode",
  								"animalDistinguishingMarks",
  								"animalPattern",
  								"animalOngoingMedical",
  								"animalMicrochipped",
  								"animalName",
  								"animalNewPeople",
  								"animalObedienceTraining",
  								"animalObedient",
  								"animalOKWithAdults",
  								"animalOKWithCats",
  								"animalNoCold",
  								"animalOKWithDogs",
  								"animalNoFemaleDogs",
  								"animalNoLargeDogs",
  								"animalNoMaleDogs",
  								"animalNoSmallDogs",
  								"animalOKWithFarmAnimals",
  								"animalNoHeat",
  								"animalOKWithKids",
  								"animalOlderKidsOnly",
  								"animalOKForSeniors",
  								"animalOwnerExperience",
  								"animalPictures",
  								"animalThumbnailUrl",
  								"animalPlayful",
  								"animalPredatory",
  								"animalUrl",
  								"animalProtective",
  								"animalSex",
  								"animalShedding",
  								"animalSizeCurrent",
  								"animalSizePotential",
  								"animalGeneralSizePotential",
  								"animalSizeUOM",
  								"animalSkittish",
  								"animalSpecialDiet",
  								"animalSpecialneeds",
  								"animalSpecialneedsDescription",
  								"animalSpecies",
  								"animalSponsorable",
  								"animalSponsors",
  								"animalSponsorshipDetails",
  								"animalSponsorshipMinimum",
  								"animalSwims",
  								"animalStatus",
  								"animalTailType",
  								"animalTimid",
  								"animalPlaysToys",
  								"animalUptodate",
  								"animalVideoUrls",
  								"animalSightImpaired",
  								"animalVocal"
							)
						);
			}
        return $this->fetch_rg_data($apiData, $callFunc, $items);
    }
    
    public function outputSearch($selfURLIN, $results, $details, $callFunc) {
       	//echo '<pre>Preparing to Output ' . $callFunc . ' Search with Details '; print_r($details); echo '</pre>';
        //echo '<pre>OUTPUT SEARCH CALLED WITH XML<br>'; print_r($results); echo '</pre>';
        if (is_array($results)) {
        	if (array_key_exists('foundRows', $results)) {
        		$resultCount = intval($results['foundRows']);
        	} else {
        		$resultCount = 0;
        	}
        } else {
	        $resultCount = 0;
	    }
        	
        //echo 'Array Count = ' . $resultCount . '<br>';

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
        //admin filter options
        //$callFunc = type of search, $details = ovveride in shortcode
        $searchResultDetails = [];
        $searchResultDetails = $this->allAPIFunction->showDetails($callFunc, $details);
        //echo '<pre>SEARCH RESULTS<br>'; print_r($searchResultDetails); echo '</pre>';
		$detailCount = array_count_values($searchResultDetails);
        //echo 'Number of Details = ' . $detailCount . '<br>';

        $searchResultLabels = [];  
        $searchResultLabels = $this->animalDetailFunction->Animal_Labels($searchResultDetails, constant('ADOPT_METHODTYPE_RESCUEGROUPS'), 'search');   
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
            if (array_key_exists(constant('RESCUEGROUPS_SPECIES'), $details)) {
                $addtoSearchClass = '-' . $details[constant('RESCUEGROUPS_SPECIES')];
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
        $levelsSuffix = constant('ADOPT_METHODTYPE_RESCUEGROUPS'); 
                                
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
        	$outputMax = 99999;
        }
        
        $TDresult .= "<div class='pmp-search-results-container'><div class='pmp-items grid-container" . $resultPerRow . "'>";

        //start processing returned results
        $resultItems = [];
        //echo "<pre>";
        //$kc =$xmlWSIN->XmlNode;
        //print_r($kc);
        
		$resultItems = (array)$results['data'];
		$resultsArray = array_values($resultItems); 
		//$resultsArray = array_change_key_case($resultsArray, CASE_LOWER);
        //echo '<pre>Results to Display '; print_r($resultsArray); echo "</pre>"; 
		$detailsFunc = constant('ADOPT_METHODTYPE_RESCUEGROUPS') . 'Details';		  
		    
		/* Initialize Icon Variables */
		if ($this->displayIconsSearch == 1) {
			$iconPath = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('IMAGES_DIR') . '/';
			if (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_search_icons_max', $this->generalOptions)) {
				$maxIcons = $this->generalOptions[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_search_icons_max'];
			} else {
				$maxIcons = 5;
			}
		}
		
        // Sets the counter to zero to use to loop through array count
        $counter = 0;        
        while ( ($counter < $resultCount) && ($counter < $outputMax) ) {
        	$resultsArray[$counter] = array_change_key_case($resultsArray[$counter], CASE_LOWER);
			//echo '<pre>Results to Display '; print_r($resultsArray[$counter]); echo "</pre>"; 
			$detailsPage = $selfURLIN . '?method=' . $detailsFunc . '&' . str_replace('id', 'ID', constant('RESCUEGROUPS_ID')) . '=' . $resultsArray[$counter][constant('RESCUEGROUPS_ID')];
//			$detailsPage = $selfURLIN . '?method=' . $detailsFunc . '&animalID=' . $resultsArray[$counter][constant('RESCUEGROUPS_ID')];
            //use override details to show $searchResultDetails if defined in admin and or shortcode
			//echo 'Details Page URL = ' . $detailsPage . '<br>';		            

            if (array_key_exists(constant('RESCUEGROUPS_SPECIES'), $resultsArray[$counter])) {
            	$species = $resultsArray[$counter][constant('RESCUEGROUPS_SPECIES')];
            } else {
            	$species = ucfirst(constant('RESCUEGROUPS_SPECIES'));
            }
            //echo 'Animal Species = ' . $species . '<br>';
            $speciesLower = strtolower($species);

            //if name is forced to be excluded get the name but dont include in array
            if (array_key_exists(constant('RESCUEGROUPS_NAME'), $resultsArray[$counter])) {
            	$animalName = $resultsArray[$counter][constant('RESCUEGROUPS_NAME')]; 
			} else {
				$animalName = constant('EMPTY_VALUE');
			}
			//echo 'Animal Name = ' . $animalName . '<br>';         
            if (!array_key_exists(constant('RESCUEGROUPS_NAME'), $searchResultDetails)) {
                $hover = "this " . $species;	
            } else {
                $hover = $animalName;
            }

			/* Process Search Results */
			$DetailsOutput = [];
			//echo 'Processing Results for Animal ' . $resultsArray[$counter][constant('RESCUEGROUPS_ID')] . ' with Name ' . $animalName . ' at Location ' . $resultsArray[$counter][constant('RESCUEGROUPS_LOCATION')] . '.<br>';
			if ( (!array_key_exists(constant('ERROR'), $searchResultDetails)) && (!array_key_exists($resultsArray[$counter][constant('RESCUEGROUPS_LOCATION')],$this->locationExclusionArray)) ) {  
				foreach ($resultsArray[$counter] as $animalKey => $animalKeyValue) {  
					//$animalKey = strtolower($animalKey);
					//echo 'Processing Animal Key ' . $animalKey . ' with Value ' . $animalKeyValue . '<br>';
					
					if ( array_key_exists($animalKey, $searchResultDetails) ) {
		                $DetailsOutput[$animalKey]['label'] = $searchResultLabels[$animalKey];
		                if ( (strlen(preg_replace('/\s+/', '', isset($animalKeyValue))) == 0) || (strtolower(preg_replace('/\s+/', '', isset($animalKeyValue))) == constant('RESCUEGROUPS_EMPTY')) || (strlen(trim($animalKeyValue)) == 0) ) {
		                    $DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
		                } else {
		                    $DetailsOutput[$animalKey]['value'] = $animalKeyValue;
		                }
	                	/* RMB - Remove Time from Date Fields */
		                if (str_contains($animalKey, 'date')) {
		                    $DetailsOutput[$animalKey]['value'] = date("m-d-Y",strtotime($animalKeyValue));
		                }	               
	                	//for age compute in years and months
		                if ( ($animalKey == constant('RESCUEGROUPS_DATE_BIRTH')) ) {
 	               			if ( ($animalKeyValue != constant('EMPTY_VALUE')) && (!empty($animalKeyValue)) ) {
								$birthDate = date_create($animalKeyValue);
								$birthDate = date_create(str_replace('-', '/', $animalKeyValue));
		                    	if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
			            			$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->ageInYears($birthDate);
				                } elseif ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
				                	$DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
				                } else {
				                	$DetailsOutput[$animalKey]['value'] = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required';
				                }	
		                    }
		                }

		                if ($animalKey == constant('RESCUEGROUPS_LOCATION')) {
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
				           	$iconString[$counter] = $this->animalDetailFunction->display_pet_icons($resultsArray[$counter], $animalName, $maxIcons);
				        }
		            }
				}	           
			}
            //echo '<pre>DETAILS<br>'; print_r($details); echo '</pre>';
            //echo '<pre>DETAILS POST LABEL PROCESSING<br>'; print_r($DetailsOutput); echo '</pre>';
            
			$locationValue = $resultsArray[$counter][constant('RESCUEGROUPS_LOCATION')];
			//echo 'Location Value is ' . $locationValue . '.<br>';
			if (strlen(trim($locationValue)) == 0) {
				$locationValue = constant('EMPTY_VALUE');
			}
			//echo 'Processing ' . $animalName . ' in Location ' . $locationValue . '<br>';
			if (!array_key_exists($locationValue, $this->locationExclusionArray)) {
				//echo 'Location ' . $locationValue . 'Was NOT Excluded!<br>';
	            /* Re-Order Output Details Based on Short Code Selection */
	            $detailKeys = explode(',', $details['details']);
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
	            if ( isset($resultsArray[$counter][constant('RESCUEGROUPS_PHOTOS')]) ) {
	                $imgSrc = $resultsArray[$counter][constant('RESCUEGROUPS_PHOTOS')][0]['urlSecureThumbnail'];
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
	                    if ($key == constant('RESCUEGROUPS_NAME')) {
	                    	//echo 'Processing Animal Name<br>';
	                        $clickID = 'pmp-animal-name-' . strtolower($species) . '-' . str_replace(" ", "-", strtolower($animalName)); 
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
        	return $TDresult .= "<div class='pmp-no-results'>" . $this->generalOptions['no_search_results_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS')] . "</div>";
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

    public function outputDetails($results, $items, $callFunc) {
        if ( WP_Filesystem() ) {
            global $wp_filesystem;
        }
        //echo '<pre> OUTPUT DETAILS CALLED WITH PARAMETERS '; print_r($items); echo '</pre>';
        //echo '<pre> OUTPUT DETAILS CALLED DETAILS<br>'; print_r($results); echo '</pre>';
        if ( (!empty($results['data'][0])) ) {
        	$resultCount = intval($results['foundRows']);
        } else {
        	$resultCount = 0;
        }
        //echo 'Result Count is ' . $resultCount . '<br>';

		/* Include Style Updates from Colors Tab */
		$colorsFile = 'pet-match-pro-public-color-css.php';
        echo '<style id="pmp-color-options-css">';
		include $this->partialsPublicDir . $colorsFile;
        echo '</style> <!-- #pmp-color-options-css -->';

        //echo 'Call Function = ' . $callFunc . '<br>';

        $keySuffix = '_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS'); 

        $DetailsOutput = $this->animalDetailFunction->Animal_Details(constant('ADOPT_METHODTYPE_RESCUEGROUPS'), $this->PMPLicenseTypeID, 'front-end');
        $detailTemplate = 'details_template' . $keySuffix;                   
        //echo "<pre>ADOPTION DETAILS OUTPUT<br>"; print_r($DetailsOutput);echo "</pre>";                
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
        	$resultItems = (array)$results['data'][0];
        	//echo "<pre>RESULTS ITEMS<br>"; print_r($resultItems);echo "</pre>";
			//$resultsArray = array_values($resultItems); 
        	$detailsItem = array_change_key_case($resultItems, CASE_LOWER);
        } else {
        	$detailsItem = [];
        }
       	//echo "<pre>DETAILS ITEMS<br>"; print_r($detailsItem);echo "</pre>";     	        
        
        $labelOptions = get_option('pet-match-pro-label-options');

        //echo "<pre>BEFORE LABEL KEY OPERATION<br>"; print_r($label_options);echo "</pre>";           

        /* Remove 'label_' prefix from label keys & filter keys */
        $resultLabels = [];
        if ( is_array($this->labelOptions) ) {
			$labelKeys = array_keys($this->labelOptions);			
			//echo '<pre>Label Keys<br>'; print_r($labelKeys); echo '</pre>';
			$selectedLabelKeys = preg_grep("/$keySuffix/i", $labelKeys);
			//echo '<pre>Method Selected Label Keys<br>'; print_r($selectedLabelKeys); echo '</pre>';
			$detailFilter = constant('LABEL_PREFIX_ANIMAL_DETAIL');
			$selectedLabelKeys = preg_grep("/$detailFilter/i", $selectedLabelKeys);
			//echo '<pre>Detail Selected Label Keys<br>'; print_r($selectedLabelKeys); echo '</pre>';

			foreach ($selectedLabelKeys as $seqNum => $labelKey) {
				//echo 'Processing Label Key ' . $labelKey . '.<br>';
                if ( (is_int(strpos($labelKey, $keySuffix))) && (!is_int(strpos($labelKey, constant('LABEL_PREFIX_SEARCH_FILTER')))) ) {
                    $resultLabelKey = str_replace('label_', '', $labelKey);				
					//$resultLabelKey = str_replace($detailFilter, '', $labelKey);
					$resultLabelKey = str_replace($keySuffix, '', $resultLabelKey);
					//echo 'Result Label Key is ' . $resultLabelKey . '.<br>';
	                if ( (strlen(trim($resultLabelKey)) > 0) ) { 
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
        
//        $result_labels = [];
//        if ( is_array($labelOptions) ) {
//            foreach ($labelOptions as $key => $value) {
//                $label_key = strtolower(substr($key, 6)); /* Convert Keys to Lower Case */
//                if ( (strpos($key, $keySuffix)) && (!strpos($key, 'filter_')) ) {
//                    $label_key = str_replace($keySuffix, '', $label_key);           
//                    if (strlen(trim($value)) > 0) {     
//                    	$result_labels[$label_key] = $value;
//                    } elseif (array_key_exists($label_key, $DetailsOutput))  {
//                    	$result_labels[$label_key] = $DetailsOutput[$label_key];
//                    } else {
//                    	$result_labels[$label_key] = constant('EMPTY_VALUE');
//                    }
//                    $result_labels[$label_key] = $value;
//                } 
//            }
//        } else {
//            $result_labels = $DetailsOutput;         
//        }

        //echo "<pre>RESULT LABELS<br>"; print_r($resultLabels);echo "</pre>";
        //echo "<pre>RESULT DETAILS<br>"; print_r($ResultDetails);echo "</pre>";
        //only include those that are set to be shown by admin settings or by shortcode override
        //get all keys of returned result and put in array
//        $keyword_replace = [];
//        $keyword_replace_values = [];
		$animalDetails = [];
		if ($resultCount == 1) {
	        foreach ($ResultDetails as $key => $item) {
		        $animalDetails[$key]['label'] = $resultLabels[$key] . ': ';
	            $animalDetails[$key]['value'] = $detailsItem[$key];
	            if (!is_array($detailsItem[$key])) {
	                if ( (strlen(preg_replace('/\s+/', '', $detailsItem[$key])) == 0) || (strtolower(preg_replace('/\s+/', '', $detailsItem[$key])) == constant('RESCUEGROUPS_EMPTY')) ) {
	                    $animalDetails[$key]['value'] = constant('EMPTY_VALUE');
	                } else {
	                    $animalDetails[$key]['value'] = $detailsItem[$key];
	                }
	            } else {
	                if (empty($detailsItem[$key])) {
	                    $animalDetails[$key]['value'] = constant('EMPTY_VALUE');
	                } else {
	                    $animalDetails[$key]['value'] = $detailsItem[$key];
	                }
	            }
	            /* RMB - Remove Time from Date Fields */
	            if (str_contains($key, 'date')) {
	                $animalDetails[$key]['value'] = date("m-d-Y",strtotime($detailsItem[$key]));
	            }            
	            //$animalDetails[$key]['value'] = $this->convertAge($detailsItem[$key]);
	            //for age compute in years and months
//	            if ($key == constant('RESCUEGROUPS_DATE_BIRTH')) {
//		            $today = date_create("now");
//		            //echo 'Birth Date is ' . $animalDetails[$key]['value'] . '<br>';
//					$birthDate = date_create($animalDetails[$key]['value']);
//					if (!$birthDate) {
//						$birthDate = date_create(str_replace('-', '/', $animalDetails[$key]['value']));
//					}
//		            $interval = date_diff($today, $birthDate);
//		            $months = ($interval->y * 12) + $interval->m;
//		            //echo 'Animal Age in Months = ' . $months . '<br>';                    
//		            if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//		              	//echo 'Processing Birth Date<br>';
//		                if ($months < 12) {
//							//echo 'Less Than 12 Month<br>';
//		                    $animalDetails[$key]['value'] = $months . ' Month(s)';
//		                } else {
//		                 	//echo 'Over 12 Months<br>';
//		                    $animalDetails[$key]['value'] = round($months / 12) . ' Year(s)';
//		                }
//		            }
//	            }
	            
                if ( ($key == constant('RESCUEGROUPS_DATE_BIRTH')) ) {
           			if ( ($animalDetails[$key]['value'] != constant('EMPTY_VALUE')) && (!empty($animalDetails[$key]['value'])) ) {
						$birthDate = date_create($animalDetails[$key]['value']);
						$birthDate = date_create(str_replace('-', '/', $animalDetails[$key]['value']));
                    	if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
	            			$animalDetails[$key]['value'] = $this->allAPIFunction->ageInYears($birthDate);
				        } elseif ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
				           	$animalDetails[$key]['value'] = constant('EMPTY_VALUE');
				        } else {
				           	$animalDetails[$key]['value'] = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required';
				        }	
	                }
				}	            
                if ($key == constant('RESCUEGROUPS_LOCATION')) {
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
		$dscKeys = array_keys($animalDetails);
		//echo '<pre>DETAIL ARRAY KEYS<br>'; print_r($dscKeys); echo '</pre>';
  		$dscMatchKey = -1;
  		foreach ($dscKeys as $dscKey => $dscValue) {
  			if (strpos(strtolower($dscValue), 'description')) {
				//echo 'Description Match of ' . $dscValue . '<br>';
		  		$dscMatchKey = $dscValue;
		  	}
		 }
		//echo 'Description Match Key is ' . $dscMatchKey . '<br>';
		if ($dscMatchKey >=0) {      
            //echo '<br>' . 'Description Value'. '<br>';
            //print_r($animalDetails['dsc']['value']);
            //echo '<br>';
            if ( ($animalDetails[$dscMatchKey]['value'] == constant('EMPTY_VALUE')) || ($animalDetails[$dscMatchKey]['value'] == constant('RESCUEGROUPS_EMPTY')) ){
            	$animalDescription = $this->allAPIFunction->replaceDetailShortcodes($ResultDetails, $animalDetails, $this->generalOptions['default_description']);            
            	//echo '<br>' . 'Animal Description'. '<br>';
            	//echo $animalDescription . '<br>';
                $animalDetails[$dscMatchKey]['value'] = do_shortcode($animalDescription);
            	//echo 'Completed Value Reassign';
            }
        }
        
        $showItems = array_keys($animalDetails);

        //include the photos
        if (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_detail_thumbs_max', $this->generalOptions)) {
        	$maxThumbs = intval($this->generalOptions[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_detail_thumbs_max']);
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
	        	if (!empty($detailsItem[constant('RESCUEGROUPS_PHOTOS')][$photoKey])) {
	   	        	$animalDetails[constant('RESCUEGROUPS_PHOTOS')][] = $detailsItem[constant('RESCUEGROUPS_PHOTOS')][$photoKey][constant('RESCUEGROUPS_PHOTO_THUMBNAIL_URL')];
	   	        	//echo 'Added Animal Photo<br>';
	  	      	}
	  	      	$counter = $counter + 1;
			}
		}

		$numPhotos = count($animalDetails[constant('RESCUEGROUPS_PHOTOS')]);
		if ($numPhotos == 0) {
			$numPhotos = 1;
		}
		//echo 'There are ' . $numPhotos . ' Photos.<br>';
		$numThumbs = $numPhotos;
			
		if ( ($numThumbs <= $maxThumbs) && (array_key_exists(0, $detailsItem[constant('RESCUEGROUPS_VIDEOS')])) ) {
			$videoCount = count($detailsItem[constant('RESCUEGROUPS_VIDEOS')]);
			//echo 'There is/are ' . $videoCount . ' Video(s).<br>';
			$videoCount = $videoCount - 1;
			$counter = 0;
			while ( ($numThumbs <= $maxThumbs) && ($counter <= $videoCount) ){
				//echo 'Processing Video Counter ' . $counter . ' with Thumb ' . $numThumbs . '<br>';	
	       		if (!empty($detailsItem[constant('RESCUEGROUPS_VIDEOS')][$counter][constant('RESCUEGROUPS_VIDEO_URL')])) {
	   	   	    	$animalDetails[constant('RESCUEGROUPS_VIDEOS')][] = $detailsItem[constant('RESCUEGROUPS_VIDEOS')][$counter][constant('RESCUEGROUPS_VIDEO_URL')];
	   	   	    	//echo 'Added Animal Video.<br>';
	   	   	    	$numThumbs = $numThumbs + 1;
	   	   	    }
	  	   	  	$counter = $counter + 1;
			}
		}

//        if (!empty($detailsItem[constant('RESCUEGROUPS_VIDEOS')][0])) {
//            $animalDetails[constant('RESCUEGROUPS_VIDEOS')] = $detailsItem[constant('RESCUEGROUPS_VIDEOS')][0]['videoUrl'];
//        } else {
//            $animalDetails[constant('RESCUEGROUPS_VIDEOS')] = '';
//        }
  	      	
        //get template options if defined in shortcode
        if (is_array($items)) {
            if (array_key_exists('template', $items)) {
                $template = $items['template'];
            }
        }
        //echo 'Template to be Used is ' . $template . '<br>';        

        //echo '<pre>ANIMAL DETAILS FOR DISPLAY - POST PROCESSING <br>'; print_r($animalDetails); echo '</pre>';

        /* RMB - Check if Child Directory Exists, Otherwise Set to Plugin Default */
        $templateDir = constant('TEMPLATES_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/';
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
        //echo 'Animal Detail Called with Item = ' . $item . '<br>';
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
            $animalDetails = []; /* RMB */       
            if (!empty(trim($item))) {
				if ( (strlen($item) > 0) && (strlen($animalIDIN) > 0) ) { 
			        $levelsSuffix = '';
					//echo 'Call Method Being Called from animalDetail.<br>'; 				
					$methodParms = $this->allAPIFunction->callMethod_Parameters($callFunc, $item);
					//echo '<pre>Method Parameters<br>'; print_r($methodParms); echo '</pre>';	
					$this->methodValue = $this->defaultMethod;
					if (array_key_exists('method', $methodParms)) {
						$this->methodValue = $methodParms['method'];
					}
					//echo 'Method Value is ' . $this->methodValue . '.<br>';
   			        $levelsSuffix = $this->methodValue;	
//			        if ($callFunc == 'foundSearch') {
//			            $levelsSuffix = 'found';
//			        } elseif ($callFunc == 'lostSearch') {
//		                    $levelsSuffix = 'lost';
//	                } else {
//	                    $levelsSuffix = constant('ADOPT_METHODTYPE_RESCUEGROUPS'); 
//	                }

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
							$apiData = array("apikey" => $this->apiKey,
											"objectType" => "animals",
			 								"objectAction" => "publicView",
			  								"values" => array (
												array("animalID" => $animalIDIN),
			    							),
			    							"fields" => array(
   															"animalID",
    														"animalActivityLevel",
    														"animalAdoptionFee",
    														"animalAdoptionPending",
    														"animalAffectionate",
    														"animalGeneralAge",
    														"animalHasAllergies",
  															"animalAltered",
  															"animalApartment",
  															"animalBreed",
  															"animalMixedBreed",
  															"animalPrimaryBreed",
  															"animalSecondaryBreed",
  															"animalGoodInCar",
  															"animalCoatLength",
  															"animalColorDetails",
  															"animalEyeColor",
  															"animalColor",
  															"animalNeedsCompanionAnimal",
  															"animalCourtesy",
  															"animalCratetrained",
  															"animalAvailableDate",
  															"animalBirthdate",
  															"animalBirthdateExact",
  															"animalFound",
  															"animalFoundDate",
  															"animalUpdatedDate",											
  															"animalDeclawed",
  															"animalDescriptionPlain",
  															"animalSummary",
  															"animalDrools",
  															"animalEagerToPlease",
  															"animalEarType",
  															"animalEnergyLevel",
  															"animalEscapes",
  															"animalEventempered",
  															"animalExerciseNeeds",
  															"animalFence",
  															"animalFetches",
  															"animalNeedsFoster",
  															"animalFound",
  															"animalFoundPostalcode",
  															"animalGentle",
  															"animalGoofy",
  															"animalGroomingNeeds",
  															"animalHearingImpaired",
  															"animalHousetrained",
  															"animalNotHousetrainedReason",
  															"animalHypoallergenic",
  															"animalIndependent",
  															"animalIndoorOutdoor",
  															"animalIntelligent",
  															"animalLap",
  															"animalLeashtrained",
  															"locationName",
  															"locationAddress",
  															"locationCity",
  															"locationCountry",
  															"locationPhone",
  															"locationState",
  															"locationUrl",
  															"locationPostalcode",
  															"animalDistinguishingMarks",
  															"animalPattern",
  															"animalOngoingMedical",
  															"animalMicrochipped",
  															"animalName",
  															"animalNewPeople",
  															"animalObedienceTraining",
  															"animalObedient",
  															"animalOKWithAdults",
  															"animalOKWithCats",
  															"animalNoCold",
  															"animalOKWithDogs",
  															"animalNoFemaleDogs",
  															"animalNoLargeDogs",
  															"animalNoMaleDogs",
  															"animalNoSmallDogs",
  															"animalOKWithFarmAnimals",
  															"animalNoHeat",
  															"animalOKWithKids",
  															"animalOlderKidsOnly",
  															"animalOKForSeniors",
  															"animalOwnerExperience",
  															"animalPictures",
  															"animalThumbnailUrl",
  															"animalPlayful",
  															"animalPredatory",
  															"animalUrl",
  															"animalProtective",
  															"animalSex",
  															"animalShedding",
  															"animalSizeCurrent",
  															"animalSizePotential",
  															"animalGeneralSizePotential",
  															"animalSizeUOM",
  															"animalSkittish",
  															"animalSpecialDiet",
  															"animalSpecialneeds",
  															"animalSpecialneedsDescription",
  															"animalSpecies",
  															"animalSponsorable",
  															"animalSponsors",
  															"animalSponsorshipDetails",
  															"animalSponsorshipMinimum",
  															"animalSwims",
  															"animalStatus",
  															"animalTailType",
  															"animalTimid",
  															"animalPlaysToys",
  															"animalUptodate",
  															"animalVideoUrls",
  															"animalSightImpaired",
  															"animalVocal"
											)
										);
			                $animalDetails = $this->fetch_rg_data($apiData, 'singleDetail', $item);
			                if (is_array($animalDetails)) {
			                	if (array_key_exists('data', $animalDetails)) {
			                		$animalDetails = $animalDetails['data'][0];
			                	} else {
			                		return constant('ERROR');
			                	}
		               		} else {
		               			return constant('ERROR');
		               		}
                			//echo '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';
                			// $details = []; - RMB
                			foreach ($animalDetails as $key => $animalItem) {
                			    $details[strtolower($key)] = $animalItem;
                			}
                			//echo '<pre>DETAILS VALUE<br>'; print_r($details[$item]); echo '</pre>';
                
                			if ($item == constant('RESCUEGROUPS_LOCATION')) {
          						//echo 'Result Location Value is ' . $details[$item] . '.<br>';
                				if (array_key_exists($item, $details)) {               	
                			    	$locationValue = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $details[$item]);
									//echo 'Location Value of ' . $locationValue . '.<br>';	               	                     	
                			    	return $locationValue;
               					}
               				} 
                			return $details[$item]; /* RMB */
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
        //$override_search = [];
        if (is_array($details)) {
            $details = array_change_key_case($details, CASE_LOWER);
        }
        /*
         * $filterOptions = Selected Adopt filters to use on admin
         * $filterValues = values of all available filters as array;
        */
        //echo '<pre>FILTER ADMIN OPTIONS<br>'; print_r($this->FilterAdminOptions); echo '</pre>';
        /* START FlyWheel Hosting Changes */
        $sortKey = 'adopt_search_' . constant('RESCUEGROUPS_ORDERBY');
        if ( (is_array($this->FilterAdminOptions)) && (array_key_exists($sortKey, $this->FilterAdminOptions)) ) {
            $this->PMPAdoptSortOptions = $this->FilterAdminOptions[$sortKey];  
        } else {
            $this->PMPAdoptSortOptions = array('animalName' => 'Name');
        }
        /* END FlyWheel Hosting Changes */
        //$PMPLicenseTypeID = get_option('PMP_License_Type_ID'); /* Manage Options */ 
        $filterReturn = [];  
        if ($callFunc == constant('ADOPT_METHODTYPE_RESCUEGROUPS') . 'Search') {
            $callMethod = constant('ADOPT_METHODTYPE_RESCUEGROUPS');
            //echo '<pre>'; print_r(Pet_Match_Pro_PP_Options::$adoptSearchFilter); echo '</pre>';
        }
        //echo 'Before Call within searchFilters<br>';
        $filterOptionsFE = $this->adminFunction->Search_Filter_Values($callMethod, $this->PMPLicenseTypeID);
        if ( is_array($filterOptionsFE) ) {
            $filterOptions  = array_keys($filterOptionsFE);
        } else {
            $filterOptions  = null;
        }
        //$filterOptions    = array_keys($this->adminFunction->Search_Filter($callMethod, $this->PMPLicenseTypeID, 'front-end'));
        $filterValues = $this->adminFunction->Search_Filter_Values($callMethod, $this->PMPLicenseTypeID);
        //echo '<pre>FILTER OPTIONS BEFORE PROCESSING '; print_r($filterOptions); echo '</pre>';  
        //echo '<pre>FILTER VALUES BEFORE PROCESSING '; print_r($filterValues); echo '</pre>';  
        //echo '<pre>SORT OPTIONS BEFORE PROCESSING '; print_r($sortOptions); echo '</pre>';    
              
        //remove form filters if defined in shortcode
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
        		if ( (is_array($this->FilterAdminOptions)) && (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_criteria', $this->FilterAdminOptions)) ) {
        			$searchFilters = $this->FilterAdminOptions[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_criteria'];
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
        	if ( (is_array($this->FilterAdminOptions)) && (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_criteria', $this->FilterAdminOptions)) ) {
        		$searchFilters = $this->FilterAdminOptions[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_criteria'];
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