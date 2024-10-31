<?php 
if ( ! function_exists( 'WP_Filesystem' ) ) {
   
    require_once ABSPATH . '/wp-admin/includes/file.php';
} 
class Pet_Match_Pro_PP_Api {
    private $baseURL;
    private $apiKey;
    //the URL of the current page where it is called to properly apply links
    private $selfUrl;
    private $adoptDetailPageURL;
    private $foundDetailPageURL;
    private $lostDetailPageURL;
    private $PMPlicense;
    private $PMPLicenseType;    /* To Secure Features */
    private $PMPLicenseTypeID;  /* To Secure Features */
    private $PMPAdoptFilterOptions;
    private $PMPFoundFilterOptions;
    private $PMPLostFilterOptions;
    private $PMPSortOptions;   
    private $PMPAdoptSearchDetails;
    private $imagesPath;
//    private $PMPSearchFilter;
    private $PMPSearchLabels;
    private $methodFilterKeys;
    private $lostFilterKeys;
    private $foundFilterKeys;
    private $lostfoundFilterKeys; /* Added for Lost/Found Combined Search */
    private $adoptFilterLabels;
    private $lostFilterLabels;
    private $foundFilterLabels;
    
    private $FilterAdminOptions;
    public 	$generalOptions;
    public	$contactOptions;
    public  $labelOptions;
    
    private $partialsDir;
	private $partialsPublicDir;
    private $adminFunction;
    private $animalDetailFunction;
    private $searchDetailSource;
    private $allAPIFunction;
    
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
//    public $typeValue;
//    public $statusValue;
    
    public function __construct($authKey, $activated) {
        $this->baseURL = "https://ws.petango.com/webservices/wsadoption.asmx/";
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
        
        $this->generalOptions = get_option('pet-match-pro-general-options');
       	if (is_array($this->generalOptions)) {
			if (array_key_exists('integration_partner', $this->generalOptions)) {
				$this->integrationPartner = $this->generalOptions['integration_partner'];
			} else {
				$this->integrationPartner = constant('PETPOINT');
			}
		} else {
			$this->integrationPartner = constant('PETPOINT');
		}   
        //echo '<pre>GENERAL OPTIONS<br>'; print_r($this->generalOptions); echo '<pre>';

		if (is_array($this->generalOptions)) {
	        if ( (array_key_exists('details_page_' . constant('ADOPT_METHODTYPE_PETPOINT'), $this->generalOptions)) && (is_numeric($this->generalOptions['details_page_' . constant('ADOPT_METHODTYPE_PETPOINT')])) && (intval($this->generalOptions['details_page_' . constant('ADOPT_METHODTYPE_PETPOINT')]) > 0) ) {
	       		$adoptDetailPageURL = $this->generalOptions['details_page_' . constant('ADOPT_METHODTYPE_PETPOINT')];
	            $detailsPage = get_post($adoptDetailPageURL);       		
	           	$this->adoptDetailPageURL = get_home_url() . '/' . $detailsPage->post_name;
	        } else {
	           	$this->adoptDetailPageURL = get_home_url();
	        }
	        if( (array_key_exists('details_page_' . constant('FOUND_METHODTYPE_PETPOINT'), $this->generalOptions)) && (is_numeric($this->generalOptions['details_page_' . constant('FOUND_METHODTYPE_PETPOINT')])) && (intval($this->generalOptions['details_page_' . constant('FOUND_METHODTYPE_PETPOINT')]) > 0) ) {
	           	$foundDetailPageURL = get_post($this->generalOptions['details_page_' . constant('FOUND_METHODTYPE_PETPOINT')]);
	            $detailsPage = get_post($foundDetailPageURL);           	
	           	$this->foundDetailPageURL = get_home_url() . '/' . $detailsPage->post_name;
	        } else {
	         	$this->foundDetailPageURL = get_home_url();            
	        }
	        if( (array_key_exists('details_page_' . constant('LOST_METHODTYPE_PETPOINT'), $this->generalOptions)) && (is_numeric($this->generalOptions['details_page_' . constant('LOST_METHODTYPE_PETPOINT')])) && (intval($this->generalOptions['details_page_' . constant('LOST_METHODTYPE_PETPOINT')]) > 0) ) {
				$lostDetailPageURL = get_post($this->generalOptions['details_page_' . constant('LOST_METHODTYPE_PETPOINT')]);
	            $detailsPage = get_post($lostDetailPageURL);			
	           	$this->lostDetailPageURL = get_home_url() . '/' . $detailsPage->post_name;
	        } else {
	           	$this->lostDetailPageURL = get_home_url();
	        }
		} else {
	    	$this->adoptDetailPageURL = get_home_url();
	        $this->foundDetailPageURL = get_home_url();            
	        $this->lostDetailPageURL = get_home_url();
		}
        $this->imagesPath = plugin_dir_url(__DIR__) . constant('IMAGES_DIR') . '/';
//        $this->PMPSearchFilter = [];
        
        //all parameters saved on the PP Filter Options Admin Settings
        $this->FilterAdminOptions = get_option('pet-match-pro-filter-options');
        //echo '<pre>FILTER ADMIN OPTIONS<br>'; print_r($this->FilterAdminOptions); echo '</pre><br>';
        
//        if ( is_array($this->FilterAdminOptions) ) { 
//            if (array_key_exists('adopt_search_criteria', $this->FilterAdminOptions)) { /*RMB Check If Adopt Search Criteria Exist */
//                $this->PMPAdoptFilterOptions = $this->FilterAdminOptions['adopt_search_criteria'];
//                //echo '<pre>ADOPTABLE SEARCH FILTERS '; print_r($this->PMPAdoptFilterOptions); echo '</pre>';
//                if (array_key_exists('adopt_search_sort', $this->FilterAdminOptions)) {
//                    $this->PMPAdoptSortOptions = $this->FilterAdminOptions['adopt_search_sort'];  
//                    //echo '<pre>ADOPTABLE SEARCH ORDERBY OPTIONS '; print_r($this->PMPAdoptSortOptions); echo '</pre>';        
//                } else {
//                    $this->PMPAdoptSortOptions = array('Name' => 'Name');
//                }
//            }
//            if (array_key_exists('found_search_criteria', $this->FilterAdminOptions)) { /*RMB Check If Found Search Criteria Exist */
//                $this->PMPFoundFilterOptions = $this->FilterAdminOptions[constant('FOUND_METHODTYPE_PETPOINT') . '_search_criteria'];
//                $this->PMPFoundSortOptions = $this->FilterAdminOptions[constant('FOUND_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')];
//            }
//            if (array_key_exists('lost_search_criteria', $this->FilterAdminOptions)) { /*RMB Check If Lost Search Criteria Exist */
//                $this->PMPLostFilterOptions = $this->FilterAdminOptions[constant('LOST_METHODTYPE_PETPOINT') . '_search_criteria'];
//                $this->PMPLostSortOptions = $this->FilterAdminOptions[constant('LOST_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')];
//            }
//            if (array_key_exists('adopt_search_details', $this->FilterAdminOptions)) { /*RMB Check If Adopt Search Detail Criteria Exist */     
//                $this->PMPAdoptSearchDetails = $this->FilterAdminOptions[constant('ADOPT_METHODTYPE_PETPOINT') . '_search_details'];
//            }
//        }

        $this->partialsDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/' . constant('PARTIALS_DIR') . '/';
       	$this->partialsPublicDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('PUBLIC_DIR') . '/' . constant('PARTIALS_DIR') . '/';        
        
        /* Include Class Defining Functions for Processing Animal Searches */
        $functionsFile = 'class-pet-match-pro-functions.php';
        require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/' . $functionsFile;
        $this->adminFunction = new PetMatchProFunctions();       
        
        /* Include Class Defining Animal Detail Functions */
        $detailsFile = 'class-pet-match-pro-pp-detail-functions.php';
        require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/' . $detailsFile;      
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
	        if ( ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_search_icons_' . constant('ADOPT_METHODTYPE_PETPOINT')]) && (array_key_exists(constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_search_icons', $this->generalOptions)) ) {
				$this->displayIconsSearch = 1;
			}
		}
		//echo 'Display Search Icons = ' . $this->displayIconsSearch . '<br>';
		
        //echo 'level_detail_icons_adopt = ' . $pmpOptionLevelsGeneral['level_detail_icons_adopt'] . '<br>';	
        //echo 'License ID = ' . $this->PMPLicenseTypeID . '<br>';
        //echo '<pre>adopt_animal_detail_icons<br>'; print_r($this->generalOptions['adopt_animal_detail_icons']); echo '</pre>';
		$this->displayIcons = 0;
        if (is_array($this->generalOptions)) {		
	        if ( ($this->PMPLicenseTypeID <= $pmpOptionLevelsGeneral['level_detail_icons_' . constant('ADOPT_METHODTYPE_PETPOINT')]) && (array_key_exists(constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_icons', $this->generalOptions)) ) {
				$this->displayIcons = 1;
			}
		}
		//echo 'Display Detail Icons = ' . $this->displayIcons . '<br>';
		
        $this->contactOptions = get_option('pet-match-pro-contact-options');
        $this->labelOptions = get_option('pet-match-pro-label-options');  

        /* Set Location Options */
        $this->locationExclusionArray = $this->allAPIFunction->locationExclusions();
        //echo '<pre>LOCATION EXCLUSIONS<br>'; print_r($this->locationExclusionArray); echo '</pre>';
        $this->locationFilterArray = $this->allAPIFunction->locationFilters();
        //echo '<pre>LOCATION FILTERS<br>'; print_r($this->locationFilterArray); echo '</pre>';
        if (is_array($this->generalOptions)) {
	        if ( array_key_exists('location_filter_other', $this->generalOptions) ) {
	            $this->locationFilterOther = $this->generalOptions['location_filter_other'];
	        } else {
	        	$this->locationFilterOther = '';
	        }
		} else {
	    	$this->locationFilterOther = '';		
		}
        //echo 'Location Other = ' . $this->locationFilterOther . '.<br>';

		$this->defaultMethod = constant('ADOPT_METHODTYPE_PETPOINT');
		$this->methodValue = $this->defaultMethod;
    }
       
    public function fetch_pp_data($urlQuery, $method, $items) {
//    public function fetch_pp_data($urlQuery, $method, $items = []) {
        /* ************************************************************************* */
        /* *** Script to execute and collect the results of the webservices call *** */
        // HTTP GET command to obtain the data
        //echo $urlQuery;
        //$urlQuery = 'https://ws.petango.com/webservices/wsadoption.asmx/AdoptableSearch?authKey=ho44bl6bmphbs21iior7k671v2gh2cf8r70636k2i7rcvs1br0&speciesid=2&sex=A&agegroup=All&location=Foster%20Home&site=&onhold=A&orderby=Name&primarybreed=All&secondarybreed=All&specialneeds=A&nodogs=A&nocats=A&nokids=A&stageID=&searchOption=0'; 
        //$urlQuery = 'https://ws.petango.com/webservices/wsadoption.asmx/AdoptableSearch?authKey=ho44bl6bmphbs21iior7k671v2gh2cf8r70636k2i7rcvs1br0&speciesid=2&sex=A&agegroup=All&site=&onhold=A&orderby=Name&primarybreed=All&secondarybreed=All&specialneeds=A&nodogs=A&nocats=A&nokids=A&stageID=&searchOption=0';
        //echo 'Fetching PetPoint Data with Method ' . $method . ' and Query URL<br>' . $urlQuery . '<br><br>';
        //echo '<pre>Fetching PetPoint Data with Items<br>'; print_r($items); echo '</pre>';
        $finalURL = str_replace(' ', '%20',$urlQuery); /****Prolific 20-08-2022**/ 
        //echo 'Calling Petango with URL: ' . $finalURL . '<br>';
        
        if ( !is_null($finalURL) ) {        
	        if ( array_key_exists('website_support_email', $this->contactOptions) ) {
	            $supportEmail = $this->contactOptions['website_support_email'];             
	        } else {
	            $supportEmail = '><p class="pmp-error">No Support Email Address Provided.</p>';
	        }
	                
	        /* Use WP Functions to Obtain the Data */
	        $response = wp_remote_get($finalURL);
	        $outputWS = wp_remote_retrieve_body( $response );
	        //echo '<pre>PETPOINT FILE CONTENTS<br>'; echo $outputWS; echo '</pre>';
	        
	        //If outputWS is not a boolean FALSE value
	        if ( (!empty(trim($outputWS))) && (!str_contains($outputWS, 'not found')) ) {
	            // Convert the output to human readable XML
	            $xmlWS = @simplexml_load_string($outputWS);
	            //echo '<pre>PETPOINT XML CONTENTS<br>'; print_r($xmlWS); echo '</pre>';
	            // Convert the output to a PHP Array
	            $xmlWSArray = json_decode(json_encode((array)$xmlWS), 1);
	           
	            //echo '<pre>PETPOINT XML ARRAY CONTENTS<br>'; print_r($xmlWSArray); echo '</pre>';
	            // If the output is not XML, display descriptive error messages
	            if ($xmlWS === false) {	            
	            	$errMsg = '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': We are unable to reach our animal database, please check the API Key in the General Options.</p><p>Additional error messages may be presented below:</p>'	            
;
//	            	$errMsg = '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ' Failed Loading XML: ';
	                foreach (libxml_get_errors() as $error) {
	                    $errMsg .= '<br>' . $error->message;
	                }
	                $errMsg .= '</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=PetPoint Integration Error">email</a> to report the problem.</p></div>';
	                return $errMsg;
	            }
	            // If Output WS has produced a boolean FALSE
	        } else {
	            //return "The following URL resulted in a FALSE output:<br>$urlWSComplete";
	            //return '<div id="pmp-error-message"><p class="pmp-error">ERROR: The following URL resulted in a FALSE output:</p>' . '<p>' . $urlQuery . '</p>'; /* RMB - Correct Error Display */    
	            //echo 'Support Email = ' . $supportEmail . '<br>';          
	            return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately we are unable to reach our animal database or no animals meet your search criteria.<br>Please try again later.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=PetPoint Integration Error">email</a> to report the problem.</p>';
	        }
		} else {
			return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Check PetMatchPro configuration, no details provided for search to our animal database. Please check the instructions or request support.</p>';
		}
        //echo "<pre>xmlWS<br>"; print_r($xmlWS); echo "</pre>";
        //echo "<pre>xmlWSArray<br>"; var_dump($xmlWSArray); echo "</pre>";
        //
        /* ************************************************************************* */
        /* ************************************************************************* */
        
		if ( (str_contains($method, 'Search')) && (is_countable($xmlWSArray)) && (array_key_exists('XmlNode',$xmlWSArray))  ) {
			$fetchCount = count($xmlWSArray["XmlNode"])-1;
		} elseif (is_countable($xmlWSArray)) {
			$fetchCount = count($xmlWSArray);
		} else {
			$fetchCount = 0;
		}
        //echo 'Fetch Count = ' . $fetchCount . '<br>';
        
        if ($method == constant('ADOPT_METHODTYPE_PETPOINT') . 'Search') {
            $details = [];
            if (is_array($items)) {
                if (array_key_exists('details', $items)) {
                    $details = explode(",", $items['details']);
                }
            }
            //echo '<pre>Preparing to Call Search Output with Parameters '; print_r($items); echo '</pre>';
            return $this->outputSearch($this->adoptDetailPageURL, $xmlWS, $items, constant('ADOPT_METHODTYPE_PETPOINT') . 'Search');
            //echo 'Output Search Complete!<br>';
        } else if ( ($method == 'AdoptableDetails') && ($fetchCount > 0) )  {
            //echo '<pre>Preparing to Call Detail Output with Parameters '; print_r($items); echo '</pre>';
            return $this->outputDetails($xmlWSArray, $items, constant('ADOPT_METHODTYPE_PETPOINT') . 'Details');
            //echo 'Output Detail Complete!<br>';
        } else if ($method == 'featured') {
            $details = [];
            if (is_array($items)) {
                if (array_key_exists('details', $items)) {
                    $details = explode(",", $items['details']);
                }
            }
            return $this->outputFeatured($this->adoptDetailPageURL, $xmlWS, $items, 'featured');
        } else if ($method == constant('FOUND_METHODTYPE_PETPOINT') . 'Search') {
            //$this->outputFoundSearch($this->adoptDetailPageURL,$xmlWS, $items);
            $details = [];
            if (is_array($items)) {
                if (array_key_exists('details', $items)) {
                    $details = explode(",", $items['details']);
                }
            }
            return $this->outputSearch($this->foundDetailPageURL, $xmlWS, $items, constant('FOUND_METHODTYPE_PETPOINT') . 'Search');
        } else if ( ($method == constant('FOUND_METHODTYPE_PETPOINT') . 'Details') && ($fetchCount > 0) ) {
            //outputFoundDetails($xmlWSArray);
            return $this->outputDetails($xmlWSArray, $items, constant('FOUND_METHODTYPE_PETPOINT') . 'Details');
        } // Display the results of the webservices call for the lostSearch method
        else if ($method == constant('LOST_METHODTYPE_PETPOINT') . 'Search') {
            //outputLostSearch($xmlWS);
            $details = [];
            if (is_array($items)) {
                if (array_key_exists('details', $items)) {
                    $details = explode(",", $items['details']);
                }
            }
            return $this->outputSearch($this->lostDetailPageURL, $xmlWS, $items, constant('LOST_METHODTYPE_PETPOINT') . 'Search');
        } // Display the results of the webservices call for the lostDetails method
        else if ( ($method == constant('LOST_METHODTYPE_PETPOINT') . 'Details') && ($fetchCount > 0) ) {
            return $this->outputDetails($xmlWSArray, $items, constant('LOST_METHODTYPE_PETPOINT') . 'Details');
        //    return outputLostDetails($xmlWSArray);
        } elseif ( ($method == 'singleDetail')  && ($fetchCount > 0) ) {
            return $xmlWSArray;
        } else {
            return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Unfortunately no results we returned from our animal database. Please try again.</p><p>If the problem persists, send us an <a class="pmp-text-link" href="mailto:' . $supportEmail . '?subject=PetPoint Fetch Returned No Results">email</a> to report the problem.</p>';
        }
        return;
    }

    public function createSearch($items, $callFunc) {
        /* Check for API KEY Before Creating Search */
        //echo 'API Key = ' . $this->apiKey . '.<br>';
        if ( (isset($this->apiKey)) && (!empty($this->apiKey)) ) {
            //$items['species'] = 'horse';
            //echo '<pre>Search Called with Method ' . $callFunc . ' and Values<br>'; print_r($items); echo '</pre>';
            
			$methodParms = $this->allAPIFunction->callMethod_Parameters($callFunc, $items);
			//echo '<pre>Method Parameters<br>'; print_r($methodParms); echo '</pre>';	

			$this->methodValue = $this->defaultMethod;
			if (array_key_exists('method', $methodParms)) {
				$this->methodValue = $methodParms['method'];
			}
//			$this->typeValue = $this->defaultType;		
//			if (array_key_exists('type', $methodParms)) {
//				$this->typeValue = $methodParms['type'];
//			}
//			$this->statusValue = '';		
//			if (array_key_exists('status', $methodParms)) {
//				$this->statusValue = $methodParms['status'];
//			}
			//echo 'Method Value of ' . $this->methodValue . '.<br>';
            
            //echo 'Species Key in Items Array ' . array_key_exists('species', $items) . '<br>';
            //echo 'License Type ID = ' . $this->PMPLicenseTypeID . ' with free value of ' . constant('FREE_LEVEL') . '<br>';
        
            /* Get Free Species Values */
            //$speciesFree = $pmpFieldValues['value_free_species'];
            $speciesFree = explode(",", constant('FREE_SPECIES'));          
            //echo 'Species in Free List ' . in_array(strtolower($items['species']), $speciesFree) . '<br>';  
                  
            if ( (array_key_exists(constant('PETPOINT_SPECIES'), $items)) && ($this->PMPLicenseTypeID == constant('FREE_LEVEL')) && (!in_array(strtolower($items[constant('PETPOINT_SPECIES')]), $speciesFree)) ) {
                $itemsSpecies = ucwords($items[constant('PETPOINT_SPECIES')]);
                return '<div class ="pmp-error-message"><a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required to Search ' . ucwords($itemsSpecies) . 's</div></div>';                  
            } else {        
                $defaultSort = constant('PETPOINT_NAME');
//                $defaultSort = 'Name';
                if (array_key_exists('order_by', $this->generalOptions)) {                
                	$orderBy = $this->generalOptions['order_by'];
//                	$orderBy = ucfirst($this->generalOptions['order_by']);
                }
                if ( !isset($orderBy) ) {
                    $orderBy = $defaultSort;
                }
                //echo 'Order by is ' . $orderBy . '.<br>';
                
                /* Select Default Orderby Value in Search Form */
                //echo '<pre>$_POST INITIAL<br>'; print_r($_POST); echo '</pre>';
                //echo '<pre>$_GET INITIAL<br>'; print_r($_GET); echo '</pre>';
                //echo '<pre>$_REQUEST INITIAL<br>'; print_r($_REQUEST); echo '</pre>';
                if ( !isset($_REQUEST[constant('PETPOINT_ORDERBY')]) ) {
                	//echo 'Set $_REQUEST for ' . constant('PETPOINT_ORDERBY') . ' to ' . $orderBy . '.<br>';
                    $_REQUEST[constant('PETPOINT_ORDERBY')] = $orderBy;
                } 
                //echo '<pre>$_REQUEST #2<br>'; print_r($_REQUEST); echo '</pre>';
                  
                $defaults = array(constant('PETPOINT_ID_SPECIES') => '0', constant('PETPOINT_SEX') => substr(constant('ALL'), 0, 1), constant('PETPOINT_AGE_GROUP') => constant('ALL'), constant('PETPOINT_LOCATION') => '', constant('PETPOINT_SITE') => '', constant('PETPOINT_ONHOLD') => substr(constant('ALL'), 0, 1), constant('PETPOINT_ORDERBY') => $orderBy, constant('PETPOINT_BREED_PRIMARY') => constant('ALL'), constant('PETPOINT_BREED_SECONDARY') => constant('ALL'), constant('PETPOINT_SPECIAL_NEEDS') => substr(constant('ALL'), 0, 1), constant('PETPOINT_OK_DOGS') => substr(constant('ALL'), 0, 1), constant('PETPOINT_OK_CATS') => substr(constant('ALL'), 0, 1), constant('PETPOINT_OK_KIDS') => substr(constant('ALL'), 0, 1));
                //echo '<pre>Defauts BEFORE Processing<br>'; print_r($defaults); echo '</pre>';
                
                //for url variables override items with GET data
                if (!empty($_GET)) {
                    $items = $_GET;
                }
                //echo '<pre>$_GET #2<br>'; print_r($_GET); echo '</pre>';                
                    
                //echo '<pre>CREATE SEARCH ITEMS<br>'; print_r($items); echo '</pre>';
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

                    $this->methodFilterKeys = array_keys($this->adminFunction->Filter_Option_Values($this->methodValue, $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER')));
//                    $this->adoptFilterKeys = array_keys($this->adminFunction->Search_Filter_Values('adopt', $this->PMPLicenseTypeID));
                    //echo '<pre>FILTER KEYS<br>'; print_r($this->methodFilterKeys); echo '</pre>';

		            /* Include Default Field Values */
		            $valuesFile = 'pmp-field-values.php';
		            $requireFile = $this->partialsDir . $valuesFile;
		            require($requireFile);           
        
	                $species = $pmpFieldValues['value_filter_speciesid_reverse_all'];
	                //echo '<pre>SPECIES VALUES<br>'; print_r($species); echo '</pre>';
                
                    foreach ($items as $key => $onlyFilter) {
                        //echo 'Processing Item Key ' . $key . ' with Value ' . $onlyFilter . '<br>';
                        if (in_array(strtolower($key), array_map('strtolower', $this->methodFilterKeys))) {
                            //$queryString
                            if ($key == constant('PETPOINT_ID_SPECIES')) {
                                //use ID rather than name
                                //echo 'Key = ' . $key . ' Value = ' . $onlyFilter . ' with Species Value of ' .  $species[$onlyFilter] . '<br>';
                                $defaults[$key] = $species[strtolower($onlyFilter)];
                                //echo 'Set Defaults Species ID to ' . $defaults[$key] . '<br>';
                            } else {
                                $defaults[$key] = strtolower($onlyFilter);
                            }
                        } elseif ($key == constant('PETPOINT_SPECIES')) {
							$defaults[constant('PETPOINT_ID_SPECIES')] = $species[strtolower($onlyFilter)];
//							unset($items[constant('PETPOINT_SPECIES')]);
						}
                    }
                    //echo '<pre>Defauts AFTER Processing<br>'; print_r($defaults); echo '</pre>';
                
//                    if (array_key_exists(constant('PETPOINT_SPECIES'), $items)) {
//                        //vd($items);
//                        $items[constant('PETPOINT_ID_SPECIES')] = $items[constant('PETPOINT_SPECIES')];
//                        unset($items[constant('PETPOINT_SPECIES')]); /* RMB New Entry to Removed Duplicate for Species */
//                    }

	                //echo '<pre>URL PARAMETERS '; print_r($items); echo '</pre>';                    
		            //echo '<pre>Search Items AFTER Processing<br>'; print_r($items); echo '</pre>';
                    
	                $speciesID = '0';
                    if (array_key_exists(constant('PETPOINT_ID_SPECIES'), $items)) {
                    	//echo 'Species Exists in Items.<br>';
                    	if ( isset($_POST[constant('PETPOINT_ID_SPECIES')]) ) {
                    		//echo 'Species Set as $_POST.<br>';
 							$speciesID = sanitize_text_field($_POST[constant('PETPOINT_ID_SPECIES')]);
 						} else {
 							//echo 'Species Set to Default Value of ' . $defaults[constant('ANIMALSFIRST_SPECIES')] . '.<br>';
 							$speciesID = $defaults[constant('PETPOINT_ID_SPECIES')];
 						}
 					}
//                        $speciesID = (isset($_POST[constant('PETPOINT_ID_SPECIES')])) ? sanitize_text_field($_POST[constant('PETPOINT_ID_SPECIES')]) : '0';//"1";    //All Species
//                    }

                    if (array_key_exists(constant('PETPOINT_ORDERBY'), $items)) {
                    	if ( isset($_POST[constant('PETPOINT_ORDERBY')]) ) {
                        	$orderBy = sanitize_text_field($_POST[constant('PETPOINT_ORDERBY')] ); 
						} else {
 							$orderby = $defaults[constant('PETPOINT_ORDERBY')];
						}
                    }
//                        $orderBy = $items[constant('PETPOINT_ORDERBY')];
//                    } else {
//                        $orderBy = (isset($_POST[constant('PETPOINT_ORDERBY')])) ? sanitize_text_field($_POST[constant('PETPOINT_ORDERBY')] ) : $defaultSort;//"1";    //All Species
//                    }
                } else {
                    //just plane shortcode
                    $speciesID = (isset($_POST[constant('PETPOINT_ID_SPECIES')])) ? sanitize_text_field($_POST[constant('PETPOINT_ID_SPECIES')] ) : '0';//"1";    //All Species
                }
        
                $sex = (isset($_POST[constant('PETPOINT_SEX')])) ? sanitize_text_field($_POST[constant('PETPOINT_SEX')] ) : substr(constant('ALL'), 0, 1);    //All animal genders
				$defaults[constant('PETPOINT_SEX')] = $sex;              
                $ageGroup = (isset($_POST[constant('PETPOINT_AGE_GROUP')])) ? sanitize_text_field($_POST[constant('PETPOINT_AGE_GROUP')] ) : constant('ALL');  //All age groups
                $defaults[constant('PETPOINT_AGE_GROUP')] = $ageGroup;
                $location = (isset($_POST[constant('PETPOINT_LOCATION')])) ? sanitize_text_field($_POST[constant('PETPOINT_LOCATION')] ) : "";   //Location string
                $defaults[constant('PETPOINT_LOCATION')] = $location;
                $site = (isset($_POST[constant('PETPOINT_SITE')])) ? sanitize_text_field($_POST[constant('PETPOINT_SITE')] ) : "";   //Site string
                $defaults[constant('PETPOINT_SITE')] = $site;
                $stageID = "";   //Stage ID string
                $defaults[constant('PETPOINT_ID_STAGE')] = $stageID;
                $specialNeeds = (isset($_POST[constant('PETPOINT_SPECIAL_NEEDS')])) ? sanitize_text_field($_POST[constant('PETPOINT_SPECIAL_NEEDS')] ) : substr(constant('ALL'), 0, 1);
                $defaults[constant('PETPOINT_SPECIAL_NEEDS')] = $specialNeeds;
                $on_hold_status = $this->generalOptions['on_hold_status'];
                $onHold = (isset($_POST[constant('PETPOINT_ONHOLD')])) ? sanitize_text_field($_POST[constant('PETPOINT_ONHOLD')] ) : $on_hold_status;   //Animals on HOLD or Not on HOLD
                $defaults[constant('PETPOINT_ONHOLD')] = $onHold;
                
                //$sortField = (isset($_POST[constant('PETPOINT_ORDERBY')])) ? sanitize_text_field($_POST[constant('PETPOINT_ORDERBY')] ) : $orderBy;
//                if (!array_key_exists(constant('PETPOINT_ORDERBY'), $_POST)) {
//                    if (empty($_POST[constant('PETPOINT_ORDERBY')])) {
//                        $orderBy = $defaultSort;
//                    }
//                } else {
                    $orderBy = (isset($_POST[constant('PETPOINT_ORDERBY')])) ? sanitize_text_field($_POST[constant('PETPOINT_ORDERBY')] ) : $defaultSort;  //Sort by Animal Name
//                }
//                echo '<pre>$_POST #3<br>'; print_r($_POST); echo '</pre>';                
                $defaults[constant('PETPOINT_ORDERBY')] = $orderBy;
                //echo 'Sort Field is ' . $orderBy . '.<br>';
                
                $primaryBreed = (isset($_POST[constant('PETPOINT_BREED_PRIMARY')])) ? sanitize_text_field($_POST[constant('PETPOINT_BREED_PRIMARY')] ) : constant('ALL');  //All Breeds (Primary)
                $defaults[constant('PETPOINT_BREED_PRIMARY')] = $primaryBreed;
                $secondaryBreed = (isset($_POST[constant('PETPOINT_BREED_SECONDARY')])) ? sanitize_text_field($_POST[constant('PETPOINT_BREED_SECONDARY')] ) : constant('ALL');  //All Breeds (Secondary)
                $defaults[constant('PETPOINT_BREED_SECONDARY')] = $secondaryBreed;
                $noCats = (isset($_POST[constant('PETPOINT_OK_CATS')])) ? sanitize_text_field($_POST[constant('PETPOINT_OK_CATS')] ) : substr(constant('ALL'), 0, 1);   //Can live with cats
                $defaults[constant('PETPOINT_OK_CATS')] = $noCats;
                $noDogs = (isset($_POST[constant('PETPOINT_OK_DOGS')])) ? sanitize_text_field($_POST[constant('PETPOINT_OK_DOGS')] ) : substr(constant('ALL'), 0, 1);   //Can live with dogs
                $defaults[constant('PETPOINT_OK_DOGS')] = $noDogs;
                $noKids = (isset($_POST[constant('PETPOINT_OK_KIDS')])) ? sanitize_text_field($_POST[constant('PETPOINT_OK_KIDS')] ) : substr(constant('ALL'), 0, 1);   //Can live with kids
                $defaults[constant('PETPOINT_OK_KIDS')] = $noKids;
                if (($callFunc != constant('ADOPT_METHODTYPE_PETPOINT') . 'Search') || ($callFunc != 'featured')) {
                    $searchOption = 0;
                } else {
                	$searchOption = '';
                }
                $defaults[constant('PETPOINT_TYPE_SEARCH')] = $searchOption;
                //override featured for URL function for featured pets
                if ( ($callFunc == 'featured') || ($callFunc == constant('ADOPT_METHODTYPE_PETPOINT') . 'Search') ) {
                    $apiMethod = 'AdoptableSearch';
                } else {
                    $apiMethod = $callFunc;
                }
                //echo 'API Method = ' . $apiMethod . '.<br>';
                
                $queryString = $this->baseURL . $apiMethod . '?authKey=' . $this->apiKey;  //Initial URL build
//                $queryString = $this->baseURL . "$apiMethod?authKey=$this->apiKey";  //Initial URL build
				//echo 'Query String - Initial<br>' . $queryString . '<br>';

                /* Assign Details from Admin if Not Provided in Shortcode */
                if ( array_key_exists('details', $items) ) {
                    $this->searchDetailSource = 'shortcode';
//                    return $this->fetch_pp_data($queryString, $callFunc, $items);
                } else {
                    $this->searchDetailSource = 'admin';
                    
//                    /* Get Filter Options from Admin */
//                    //echo 'Called with Method ' . $callFunc . '<br>';
//                    if ( $callFunc == 'FoundSearch' ) {
//                        $detailPrefix = 'found';
//                    } elseif ( $callFunc == 'LostSearch' ) {
//                        $detailPrefix = 'lost';
//                    } else {
//                        $detailPrefix = 'adopt';
//                    }

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
//                    //echo '<pre>CREATE SEARCH ITEMS<br>'; print_r($items); echo '</pre>';
//					echo 'Query String - Final<br>' . $queryString . '<br>';                    
//                    return $this->fetch_pp_data($queryString, $callFunc, $items);
                }

				$equals = '=';				
				$urlConnector = '&';
                //echo '<pre>Defauts Used for URL Processing<br>'; print_r($defaults); echo '</pre>';
                foreach ($defaults as $key => $value) {
                    if (isset($_POST[$key])) {
                        $sanKey = sanitize_text_field( $_POST[$key] );
                    //if (isset($_POST[$item])) {
                        $queryString = $queryString . $urlConnector . $key . $equals . $sanKey;
//                        $queryString = "$queryString&$key=$sanKey";
                        //$queryString = "$queryString&$item=$_POST[$item]";
                    } else {
                        //echo 'Adding filter ' . $key . ' to URL with value ' . $value . '<br>';
                        $queryString = $queryString . $urlConnector . $key . $equals . $value;
//                        $queryString = "$queryString&$key=$value";
                    }
                }
				//echo 'Query String - Post Defaults<br>' . $queryString . '<br>';
                
//                $queryString = "$queryString&stageID=$stageID";
//                if (($callFunc != 'adoptSearch') || ($callFunc != 'featured')) {
//                    $queryString = "$queryString&searchOption=0";       //Add Search Option
//                }

             	//echo '<pre>CREATE SEARCH ITEMS<br>'; print_r($items); echo '</pre>';
                return $this->fetch_pp_data($queryString, $callFunc, $items);
            }
        } else {
            //echo 'Support Email = ' . $supportEmail . '<br>';          
            return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Integration partner API key is missing in the General Settings.</p></div>';
        }
    }

    /*Create Search for Lost and Found Pets */    
    public function createLostFoundSearch($items) {
        //echo '<pre>CALLED WITH'; print_r($items); echo '</pre>';
        if ( isset($this->apiKey) && !empty($this->apiKey) ) {
	        $firstqueryString = '';
	        $defaultSort = 'DateLast';
//	        $defaultSort = constant('PETPOINT_DATE_LAST');
	        /* Select Default Orderby Value in Search Form */
	        if ( !isset($_REQUEST[constant('PETPOINT_ORDERBY')]) ) {
	            $_REQUEST[constant('PETPOINT_ORDERBY')] = $defaultSort;
	        }           
	        //specify species ID here if in shortcode
	        $defaults = array(constant('PETPOINT_ID_SPECIES') => '0', constant('PETPOINT_SEX') => substr(constant('ALL'), 0, 1), constant('PETPOINT_AGE_GROUP') => constant('ALL'), constant('PETPOINT_ORDERBY') => $defaultSort, constant('PETPOINT_TYPE_SEARCH') => '0');
	
	        /* Include Default Field Values */
	        $valuesFile = 'pmp-field-values.php';
	        $requireFile = $this->partialsDir . $valuesFile;
	        require($requireFile);           
	        
	        $species = $pmpFieldValues['value_filter_speciesid_reverse_reduced'];
	
	        //for url variables override items with GET data
	        if (!empty($_GET)) {
	            $items = sanitize_text_field( $_GET );
	        }
	
			if ( (is_array($this->FilterAdminOptions)) && (array_key_exists(constant('LOST_METHODTYPE_PETPOINT') . '_' . constant('FOUND_METHODTYPE_PETPOINT') . '_combination', $this->FilterAdminOptions)) ) {
	        	$firstMethod = $this->FilterAdminOptions[constant('LOST_METHODTYPE_PETPOINT') . '_' . constant('FOUND_METHODTYPE_PETPOINT') . '_combination'];     
	       	}
	        //echo 'First Method = ' . $firstMethod . '<br>';
	        if ($firstMethod) {
	            $firstSuffix = strtolower(str_replace('Search', '', $firstMethod));     
	        } else {
	            $firstMethod = constant('LOST_METHODTYPE_PETPOINT') . 'Search';
	            $firstSuffix = constant('LOST_METHODTYPE_PETPOINT');      
	        }
	        //echo 'First Suffix = ' . $firstSuffix . '<br>';         
	        
	        $this->lostfoundFilterKeys = array_keys($this->adminFunction->Filter_Option_Values($firstSuffix, $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER')));     
	//        $this->lostfoundFilterKeys = array_keys($this->adminFunction->Search_Filter_Values($firstSuffix, $this->PMPLicenseTypeID));      
	        //echo '<pre>' . strtoupper($firstSuffix) . ' FILTER KEYS<br>'; print_r($this->lostfoundFilterKeys); echo '</pre>';
	        
	        $speciesID = '0';        
	        if (!empty($items)) {
	            if (array_key_exists(constant('PETPOINT_ID_SPECIES'), $items)) {
	//            if (array_key_exists(constant('PETPOINT_SPECIES'), $items)) {
	                //vd($items);
	                $items[constant('PETPOINT_ID_SPECIES')] = $items[constant('PETPOINT_ID_SPECIES')];
	//                $items[constant('PETPOINT_ID_SPECIES')] = $items[constant('PETPOINT_SPECIES')];
	                unset($items[constant('PETPOINT_ID_SPECIES')]); /* RMB New Entry to Removed Duplicate for Species */                
	//                unset($items[constant('PETPOINT_SPECIES')]); /* RMB New Entry to Removed Duplicate for Species */                
	            }
	            foreach ($items as $key => $onlyFilter) {
	            //echo 'Processing Item ' . $key . ' with Value ' . $onlyFilter . '<br>';
	                if (in_array(strtolower($key), array_map('strtolower', $this->lostfoundFilterKeys))) {
	                    //$queryString
	                    if ($key == constant('PETPOINT_ID_SPECIES')) {
	                        //use ID rather than name
	                        $defaults[constant('PETPOINT_ID_SPECIES')] = $species[strtolower($onlyFilter)];
	                    } else {
	                        $defaults[$key] = strtolower($onlyFilter);
	                    }
                    } elseif ($key == constant('PETPOINT_SPECIES')) {
						$defaults[constant('PETPOINT_ID_SPECIES')] = $species[strtolower($onlyFilter)];
						unset($items[constant('PETPOINT_SPECIES')]);
					}
	            }
//	            if (array_key_exists(constant('PETPOINT_ID_SPECIES'), $items)) {
//	                $speciesID = (isset($_POST[constant('PETPOINT_ID_SPECIES')])) ? sanitize_text_field($_POST[constant('PETPOINT_ID_SPECIES')] ) : '0'; //"1";    //All Species
//	            }

                if (array_key_exists(constant('PETPOINT_ID_SPECIES'), $items)) {
                   	//echo 'Species Exists in Items.<br>';
                   	if ( isset($_POST[constant('PETPOINT_ID_SPECIES')]) ) {
                   		//echo 'Species Set as $_POST.<br>';
						$speciesID = sanitize_text_field($_POST[constant('PETPOINT_ID_SPECIES')]);
					} else {
						//echo 'Species Set to Default Value of ' . $defaults[constant('ANIMALSFIRST_SPECIES')] . '.<br>';
						$speciesID = $defaults[constant('PETPOINT_ID_SPECIES')];
					}
				}
	        } else {
	            //just plain shortcode
	            $speciesID = (isset($_POST[constant('PETPOINT_ID_SPECIES')])) ? sanitize_text_field($_POST[constant('PETPOINT_ID_SPECIES')] ) : '0'; //"1";    //All Species
	        }
	        //echo '<pre>UPDATED DEFAULTS<br>'; print_r($defaults); echo '</pre>';
	
	        $sex = (isset($_POST[constant('PETPOINT_SEX')])) ? sanitize_text_field($_POST[constant('PETPOINT_SEX')] ) : substr(constant('ALL'), 0, 1);    //All Animal Genders
	        $ageGroup = (isset($_POST[constant('PETPOINT_AGE_GROUP')])) ? sanitize_text_field($_POST[constant('PETPOINT_AGE_GROUP')] ) : constant('ALL');  //All Age Groups
	        $orderBy = (isset($_POST[constant('PETPOINT_ORDERBY')])) ? sanitize_text_field($_POST[constant('PETPOINT_ORDERBY')] ) : $defaultSort;    
	
	        if ($firstMethod) {
	            if ($firstMethod == constant('LOST_METHODTYPE_PETPOINT') . 'Search') {
	                $nextMethod = constant('FOUND_METHODTYPE_PETPOINT') . 'Search';
	                $nextSuffix = constant('FOUND_METHODTYPE_PETPOINT');  
	            } else {
	                $firstMethod = constant('FOUND_METHODTYPE_PETPOINT') . 'Search';
	                $nextMethod = constant('LOST_METHODTYPE_PETPOINT') . 'Search';
	                $nextSuffix = constant('LOST_METHODTYPE_PETPOINT');
	            }
	        } else {
	            $firstMethod = constant('LOST_METHODTYPE_PETPOINT') . 'Search';
	            $nextMethod = constant('FOUND_METHODTYPE_PETPOINT') . 'Search';
	            $nextSuffix = constant('FOUND_METHODTYPE_PETPOINT');     
	        }
	
	        $searchOption = (isset($_POST['searchoption'])) ? sanitize_text_field($_POST['searchoption']) : '0';  //All Search Options
	
	        /* Get Field Visibility Levels by License Type */
	        $levelsFile = 'pmp-field-levels-' . $firstSuffix . '.php';
	        $requireFile = $this->partialsDir . $levelsFile;
	        require($requireFile);
	        
	        $fieldLevelsArrayName = 'pmpFieldLevels' . ucwords($firstSuffix);
	        $fieldLevels = [];
	        $fieldLevels = $$fieldLevelsArrayName;
	        
	        $firstqueryString = $this->baseURL . $firstMethod . '?authKey=' . $this->apiKey;  //Initial URL build
	        
			$equals = '=';				
			$urlConnector = '&';
	        //echo '<pre>DEFAULTS AFTER PROCESSING<br>'; print_r($defaults); echo '</pre>';
	        foreach ($defaults as $key => $value) {
	        //foreach ($this->lostfoundFilterKeys as $item) {
	            if (isset($_POST[$key])) {
	            //if (isset($_POST[$item])) {
	                
	                $sanKey = sanitize_text_field( $_POST[$key] );
	                
	                $firstqueryString = $firstqueryString . $urlConnector . $key . $equals . $sanKey;
	                //echo 'URL Parameter ' . $item . ' with Post Value ' . $_POST[$item] . '<br>';                
	            } else {
	                $firstqueryString = $firstqueryString . $urlConnector . $key . $equals . $value;
	                //echo 'URL Parameter ' . $item . ' with Default Value ' . $defaults[$item] . '<br>';                
	            }
	        }
	        
	        //echo 'First Suffix is ' . $firstSuffix . '. Next suffix is ' . $nextSuffix . '.<br>';
	        $firstMethodURL = str_replace(' ','%20', $firstqueryString); 
	        //echo $firstMethod . ' url = ' .  $firstMethod_url . '<br>';
	        
	        /* Use WP Functions to Obtain the Data */
	        $response = wp_remote_get($firstMethodURL);
	        $firstoutputWS = wp_remote_retrieve_body( $response );
	
	        //If outputWS is not a boolean FALSE value
	        if (!empty(trim($firstoutputWS))) {
	            /* Remove Date and Address Field Prefixes */
	            $firstoutputWS = str_replace(ucwords($firstSuffix) . 'Date', 'Date', $firstoutputWS);
	            $firstoutputWS = str_replace(ucwords($firstSuffix) . 'Address', 'Address', $firstoutputWS);
	            $firstWS = @simplexml_load_string($firstoutputWS);
	            // Convert the output to a PHP Array
	            $firstxmlWSArray = json_decode(json_encode((array)$firstWS), 1);
	            //echo '<pre>Results from ' . $firstMethod . ' SEARCH '; print_r($firstxmlWSArray); echo '</pre><br>';
	        } else {
	        	$firstoutputWS = [];
	        	$firstxmlWSArray = [];
	        }
	        
	        //echo '<pre>Results from ' . $firstMethod . ' SEARCH '; print_r($firstxmlWSArray); echo '</pre><br>';
	
	        $nextqueryString = '';
	        $nextqueryString = str_replace($firstMethod, $nextMethod, $firstqueryString);
	        //echo 'Next Method URL is ' .  $nextqueryString . '<br>';
	        
	        /* Use WP Functions to Obtain the Data */
	        $response = wp_remote_get($nextqueryString);
	        $nextoutputWS = wp_remote_retrieve_body( $response );
	        
	        //$nextxmlWS = [];
	        //If outputWS is not a boolean FALSE value
	        if (!empty(trim($nextoutputWS))) {
	            /* Remove Date and Address Field Prefixes */
	            $nextoutputWS = str_replace(ucwords($nextSuffix) . 'Date', 'Date', $nextoutputWS);
	            $nextoutputWS = str_replace(ucwords($nextSuffix) . 'Address', 'Address', $nextoutputWS);
	            $nextWS = @simplexml_load_string($nextoutputWS);
	            // Convert the output to human readable XML
	            $nextxmlWSArray = json_decode(json_encode((array)$nextWS), 1);            
	            //echo '<pre>Results from ' . $nextMethod . ' SEARCH '; print_r($nextxmlWSArray); echo '</pre><br>';
	        } else {
	        	$nextxmlWSArray = [];
	        }
	
	        /* Remove Last Blank Entry from Arrays */
	        if (array_key_exists('XmlNode', $firstxmlWSArray)) {
	        	if (is_countable($firstxmlWSArray['XmlNode'])) {
					$firstArrayCount = count($firstxmlWSArray['XmlNode'])-1;
				} else {
					$firstArrayCount = 0;
				}
			} else {
					$firstArrayCount = 0;
			}
	        //echo 'First Array Count = ' . $firstArrayCount . '<br>';
	        $firstUnset = $firstArrayCount;
	        if ($firstUnset >= 0) {
	        	unset($firstxmlWSArray['XmlNode'][$firstUnset]);
	        }
	        if (array_key_exists('XmlNode', $nextxmlWSArray)) {
		       	if (is_countable($nextxmlWSArray['XmlNode'])) {
	    	    	$nextArrayCount = count($nextxmlWSArray['XmlNode'])-1;
	    	    } else {
	    	    	$nextArrayCount = 0;
	    	    }
			} else {
				$nextArrayCount = 0;
			}
	        //echo 'Next Array Count = ' . $nextArrayCount . '<br>';
	        $nextUnset = $nextArrayCount;
	        if ($nextUnset >= 0) {
				unset($nextxmlWSArray['XmlNode'][$nextUnset]);
			}

            //echo '<pre>PRE SORT Results from ' . $firstMethod . ' SEARCH '; print_r($firstxmlWSArray); echo '</pre><br>';
            //echo '<pre>PRE SORT Results from ' . $nextMethod . ' SEARCH '; print_r($nextxmlWSArray); echo '</pre><br>';
			
	        $xmlWSArray=[];  
			if ( ($firstArrayCount > 0) && ($nextArrayCount > 0) ) {
				$xmlWSArray = array_merge_recursive($firstxmlWSArray, $nextxmlWSArray); 
		        /* Resequence Key Values */
		        //echo '<pre>Results from Combining Arrays '; print_r($xmlWSArray); echo '</pre><br>';
		    } elseif ($nextArrayCount > 0) {
		    	//echo 'First Method Empty<br>';
		    	$xmlWSArray = $nextxmlWSArray;
		    } elseif ($firstArrayCount > 0) {
		    	//echo 'Second Method Empty<br>';
		    	$xmlWSArray = $firstxmlWSArray;
		    }
	
	        //echo '<pre>Results from Combining Arrays<br>'; print_r($xmlWSArray); echo '</pre><br>';
	        	    
			if ( (is_countable($xmlWSArray)) && (array_key_exists('XmlNode',$xmlWSArray)) ) {
		        $xmlWSArray = array_values($xmlWSArray['XmlNode']);		
				$mergeCount = count($xmlWSArray);
			} else {
				$mergeCount = 0;
			}
			//echo 'Merge Count = ' . $mergeCount . '<br>';
	
	        /* Format Date Field */
	        if ($mergeCount > 0) {
		        foreach ($xmlWSArray as $ID => $value) {
		            //echo '<pre>Procecssing Merged Array ID ' . $ID . ' with Value<br>'; print_r($value); echo '</pre><br>';
		            $dateValue = date("Y-m-d",strtotime($value['an']['Date']));
		            //echo 'Date Field is ' . $value['an']['Date'] . ', convert to date as ' . $dateValue . '<br>';
		            //echo 'Date Field is ' . $ID['an']['Date'] . ', convert to date as ' . $dateValue . '<br>';
		            $xmlWSArray[$ID]['an']['Date'] = $dateValue;
		        }
		        //echo '<pre>Results from Formatting Date in Combined Array<br>'; print_r($xmlWSArray); echo '</pre><br>';
	
		        /* Sort Values Based on Field Specified */
		        if (!array_key_exists(constant('PETPOINT_ORDERBY'), $_POST)) {
		            if (empty($_POST[constant('PETPOINT_ORDERBY')])) {
		                $orderBy = $defaultSort;
		            }
		        } else {
		            $orderBy = (isset($_POST[constant('PETPOINT_ORDERBY')])) ? sanitize_text_field($_POST[constant('PETPOINT_ORDERBY')]) : $defaultSort;  //Sort by Date Last
		        }
		        //echo 'Order by for Combined Search Results = ' . $orderBy . '<br>';
		        if ( str_contains($orderBy, 'Date') ) {
		            $orderField = 'Date';
		        } else {
		            $orderField = $orderBy;
		        }
		        //echo 'Order Field = ' . $orderField . '<br>';
		        if ($orderBy == $defaultSort) {
		            array_multisort(array_column(array_column($xmlWSArray, 'an'), $orderField), SORT_DESC, $xmlWSArray );
		        } else {
		            array_multisort(array_column(array_column($xmlWSArray, 'an'), $orderField), SORT_ASC, $xmlWSArray );
		        }
	
			    //echo '<pre>Results after Sorting Combined Array '; print_r($xmlWSArray); echo '</pre><br>';
	        	//echo 'Result Count is ' . count($xmlWSArray, COUNT_RECURSIVE) . '<br>';
		        //echo '<pre>Called with Filters'; print_r($Filters); echo '</pre>';
		        if (is_countable($xmlWSArray)) {
		        	$xmlArrayCount = count($xmlWSArray);
		        } else {
		        	$xmlArrayCount = 0;
		        }
		        //echo 'Sort Count = ' . $xmlArrayCount . '<br>';
			} else {
	        	$xmlArrayCount = 0;
	        }
	                
			/* Include Style Updates from Colors Tab */
			$colorsFile = 'pet-match-pro-public-color-css.php';
	        echo '<style id="pmp-color-options-css">';
			include $this->partialsPublicDir . $colorsFile;
	        echo '</style> <!-- #pmp-color-options-css -->';
	        
	//        /*Include Custom CSS from Admin for Paid Licenses */
	//        if ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
	//            echo '<style id="pmp-custom-css">';
	//            echo $this->generalOptions['pmp_custom_css'];
	//            echo '</style> <!-- #pmp-custom-css -->';
	//        }
	        
	        //print_r($this->generalOptions);
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
	        $labels = $this->allAPIFunction->showLabels($firstMethod, $items);
	        // Sets the counter to zero to use to loop through array count
	        $counter = 0;
	        //Fields to be Disoplayed in Search Results
	        //admin filter options
	        $searchResultDetails = $this->allAPIFunction->showDetails($firstMethod, $items);
	        //echo '<pre>SEARCH DETAILS'; print_r($searchResultDetails); echo '</pre>';
	
	        /*
	         * Search Form Variables
	         * $this->FilterAdminOptions = Options to show on search form - Form Fields
	         * $filterValueArray = Values of the dropdown if needed
	         */
	        //should return search filters in array format array($filetrOptions, $filterValues)
	        $searchFilters = $this->SearchFilters($firstMethod, $items);
	        //echo 'SEARCH FILTERS<br>'; print_r($searchFilters); echo '<br>';
	        //check if species is dog or cat and use default species
	        /* Identify the Detail Fields to be Displayed in the Results */
	        //echo '<pre>Called with Details'; print_r($Details); echo '</pre>';
	
			/* Get Field Values */
			$valuesFile = 'pmp-field-values-' . $firstSuffix . '.php';
			$requireFile = $this->partialsDir . $valuesFile;
			//echo 'Including ' . $firstSuffix . ' Result Values File ' . $requireFile . '<br>';
			require $requireFile;
			$fieldValueArrayName = 'pmpFieldValues' . ucfirst($firstSuffix);
			$fieldValueArray = $$fieldValueArrayName;
			//echo '<pre>' . strtoupper($firstSuffix) . ' VALUES<br>'; print_r($fieldValueArray); echo '</pre>';
	        $valuesFile = 'pmp-field-values.php';
	        $requireFile = $this->partialsDir . $valuesFile;
	//        $requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $valuesFile;
	        require($requireFile);     
	
//		   	/* Obtain Custom Result Labels */
//		   	$labelOptions = get_option('pet-match-pro-label-options');
//		   	//echo '<pre>LABEL OPTIONS<br>'; print_r($labelOptions); echo '</pre>';
//	
			$labelArray = [];
			$methodSuffix = '_' . $firstSuffix;			
				
			$filterPrefix = constant('LABEL_PREFIX_ANIMAL_DETAIL');
			//echo 'Seach Filter Prefix = ' . $filterPrefix . '<br>';
			foreach ($fieldValueArray as $key => $value) {
				if ( (is_numeric(strpos($key, $filterPrefix))) ) {
		       		//echo 'Key ' . $key . ' with Value ' . $value . '<br>';
		       		$arrayKey = str_replace($filterPrefix, '', $key);
		       		$arrayKey = str_replace($methodSuffix, '', $arrayKey);
		       		//echo 'Processing Label ' . $arrayKey . '<br>';
		       		$labelKey = 'label_' . $arrayKey . $methodSuffix;
		       		//echo 'Label Key ' . $labelKey . '<br>';
		       		if ( (is_array($this->labelOptions)) && (array_key_exists($labelKey, $this->labelOptions)) ) { 
		       			$labelArray[$labelKey] = $this->labelOptions[$labelKey];
		       		} else {
		       			$labelArray[$labelKey] = $value;
		       		} 
		       		//echo 'Setting Value Key ' . $fieldKey . '<br>';
				}
			}
		    //echo '<pre>' . strtoupper($firstSuffix) . ' RESULT LABELS<br>'; print_r($labelArray); echo '</pre>';
	        
	        $addtoSearchClass = '';
			$addtoSearchClass = '-' . $firstSuffix . '-' . $nextSuffix;
	              
	        /* Configure Search Form Onclick Parameter */
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
	        
	        //echo '<pre>SEARCH FILTER VALUES - POST PROCESSING <br>'; print_r($searchFilters); 
	        
	        /* Remove Breed from Order by Values (results contain multiple species) */
	        $orderbyArray = $searchFilters['filterValues'][constant('PETPOINT_ORDERBY')];
	        $orderbyLabel = array_key_first($orderbyArray);
	        //echo 'Order by Label = ' . $orderbyLabel . '<br>';
	        $orderbyOptions = $orderbyArray[$orderbyLabel];
	        $orderbyValues = [];
	        //echo '<pre>Order By Options BEFORE Processing<br>'; print_r($orderbyOptions); echo '</pre>';
	        foreach ($orderbyOptions as $orderbyKey => $orderbyValue) {
	            if ( strtolower($orderbyKey) != 'breed' ) {
	                $orderbyValues[$orderbyKey] = $orderbyValue;
	            }
	        }
	        //echo '<pre>Order By Options AFTER Processing<br>'; print_r($orderbyValues); echo '</pre>';
	        $searchFilters['filterValues'][constant('PETPOINT_ORDERBY')][$orderbyLabel] = $orderbyValues;
	        
	        /*Check if Filter Options Are Required */
	        //echo '<pre>SEARCH FILTERS<br>'; print_r($searchFilters); echo '</pre>';
	        if ($searchFilters['filterOptions']) {
	            $TDresult .= $this->allAPIFunction->buildSearchForm($searchFilters['filterOptions'], $searchFilters['filterValues'], $addtoSearchClass, $searchOnClick, $fieldLevels);
	        }
	        $TDresult .= '</div>';
	
	        if (is_array($items) && array_key_exists('title', $items)) {
	            $TDresult .= '<div class="pmp-results-title">';
	            $TDresult .= $items['title'];
	            $TDresult .= '</div>';
	        }
	        //end of form logic
	
	        $TDresult .= "<div class='pmp-search-results-container'><div class='pmp-items grid-container" . $resultPerRow . "'>";
	        
	        while ($counter < $xmlArrayCount) {
	            //echo 'Counter = ' . $counter  . '<br>';
	            //identify results to use based on function
	            $keysArray = [];
	            $valuesArray = [];
	            $empty = constant('EMPTY_VALUE');
	            foreach ($xmlWSArray[$counter]['an'] as $key=>$value) {
	                //echo 'key is ' . $key . '<br>';
	                $keysArray[] = $key;
	                if ( !$value ) {
	                    $value = $empty;
	                }
	                $valuesArray[] = $value;
	                //echo 'value is ' .$value . '<br>';                
	            }
	            $results = array_combine(array_map('strtolower', $keysArray), $valuesArray);
	            $DetailsOutput = [];
	            //echo '<pre>RESULTS<br>'; print_r($results); echo '</pre>';
	            //to lower case just to match keys
	            if (!array_key_exists(constant('ERROR'), $searchResultDetails)) {
		            foreach ($searchResultDetails as $key => $output) {
		                //use labels from static variable
		                //echo 'Key of ' . $key . ' with Value of ' . $output . '<br>';
		                $detailLabelKey = 'label_' . $key . $methodSuffix;
		                //echo 'Label Key is ' . $detailLabelKey . '<br>';
		                
		                if ( array_key_exists($detailLabelKey, $labelArray) ) {
		                	$DetailsOutput[$key]['label'] = $labelArray[$detailLabelKey];
		                } elseif ( (is_numeric(strpos($key, 'date'))) ) {
		                	$dateKey = 'label' . $methodSuffix . 'date' . $methodSuffix;
		                	//echo 'Date Key is ' . $dateKey . '<br>';
		                	$DetailsOutput[$key]['label'] = $labelArray[$dateKey];
		                } else {
		                	$DetailsOutput[$key]['label'] = ucfirst($output);
		                	//echo 'Set Label Value to ' . $DetailsOutput[$key]['label'] . '<br>';
		                }
						//echo 'Preparing to Process Results Key ' . $key . '<br>';
						if (array_key_exists($key, $results)) {
							$DetailsOutput[$key]['value'] = $results[$key];
						} else {
							$DetailsOutput[$key]['value'] = constant('EMPTY_VALUE');
						}
		                //for sex in whole world used on found and lost
		                if ($key == constant('PETPOINT_SEX')) {
		                	$resultsKey = $results[$key];
		                	if (array_key_exists($resultsKey, $pmpFieldValues['value_' . constant('PETPOINT_SEX')])) {
		                		$DetailsOutput[$key]['value'] = $pmpFieldValues['value_' . constant('PETPOINT_SEX')][$resultsKey];
		                	} else {
		                		$DetailsOutput[$key]['value'] = $pmpFieldValues['value_' . constant('PETPOINT_SEX')]['U'];
		                	}	
		                }
		
		                //for age compute in years and months
		                if ( ($key == constant('PETPOINT_AGE')) && (array_key_exists($key, $results)) ){
		                	$ageMonths = (int)$results[$key];
		                	//echo 'Processing Age with Value ' . $results[$key] . ' for Animal ' . $results['id'] . '<br>';
	               			if ( ($ageMonths > 0) && (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//		                	if (is_int((int)$results[$key])) {
//		                		$birthDate = date_create(date('m/d/Y')); 
//		                		$birthDate = date_sub($birthDate, date_interval_create_from_date_string($results[$key] . ' months'));
			                	$DetailsOutput[$key]['value'] = $this->allAPIFunction->ageInYears($ageMonths);
//			                	$DetailsOutput[$key]['value'] = $this->convertAge($results[$key], array_key_exists('age_in_years', $this->generalOptions));
				            } elseif ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
				               	$DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
				            } else {
				               	$DetailsOutput[$animalKey]['value'] = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required';
				            }	
		                }
		                
//		                if ( ($key == constant('PETPOINT_DATE_BIRTH')) && (array_key_exists($key, $results)) ) {
// 	               			if ( ($results[$key] != constant('EMPTY_VALUE')) && (!empty($results[$key] )) ) {
//								$birthDate = date_create($results[$key]);
//								$birthDate = date_create(str_replace('-', '/', $results[$key]));
//			                    if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//			            			$DetailsOutput[$key]['value'] = $this->allAPIFunction->ageInYears($birthDate);
//			                    }
//			                } else {
//			                	$DetailsOutput[$key]['value'] = constant('EMPTY_VALUE');
//			                }	
//			            }
		                
		                /* RMB - Process Location Value */
		                if ($key == constant('PETPOINT_LOCATION')) {
	            			//echo 'Result Location Value is ' . $results[$key] . '.<br>';
		                	if (array_key_exists($key, $DetailsOutput)) {               	
		                    	$locationValue = $DetailsOutput[$key]['value'];
		                    	$DetailsOutput[$key]['value'] = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $locationValue);
								//echo 'Location Value of ' . $DetailsOutput[$key]['value'] . '.<br>';	               	                     	
		               		}
		               	} 
	
		                /* RMB - Remove Time from Date Fields */
		                if ( (array_key_exists($key, $results)) && (str_contains($key, 'date')) ) {
		                    $DetailsOutput[$key]['value'] = date("m-d-Y",strtotime($results[$key]));
		                }
	
		                if ($key == 'type') {
		                    //echo $DetailsOutput[$key]['value'] . '<br>';
		                    if (str_contains(strtolower($DetailsOutput[$key]['value']), constant('LOST_METHODTYPE_PETPOINT'))) {
		                        $DetailPageURL = $this->lostDetailPageURL;
		                        $searchMethod = constant('LOST_METHODTYPE_PETPOINT') . 'Details';
		                    } else {
		                        $DetailPageURL = $this->foundDetailPageURL;
		                        $searchMethod = constant('FOUND_METHODTYPE_PETPOINT') . 'Details';
		                    }
		                }                           
		            }
	            }
	            
	            if (!isset($DetailPageURL)) {
	            	if ($firstSuffix == constant('LOST_METHODTYPE_PETPOINT')) {
		            	$DetailPageURL = $this->lostDetailPageURL;
		                $searchMethod = constant('LOST_METHODTYPE_PETPOINT') . 'Details';
		            } else {
	                    $DetailPageURL = $this->foundDetailPageURL;
	                    $searchMethod = constant('FOUND_METHODTYPE_PETPOINT') . 'Details';
	                }
	            }
	
	            if(!is_admin()) {            
	                $detailsPage = $DetailPageURL . '?method=' . $searchMethod . '&' . str_replace(constant('PETPOINT_ID'), strtoupper(constant('PETPOINT_ID')), constant('PETPOINT_ID_ANIMAL')) . '=' . $results[constant('PETPOINT_ID')];            
	            } else {
	            	$detailsPage = '';
	            }
	
	            //if name is forced to be excluded get the name but dont include in array
	            if (!array_key_exists(constant('PETPOINT_NAME'), $searchResultDetails)) {
	                $animalName = $results[constant('PETPOINT_NAME')];
	                $hover = "this " . ucwords($results[constant('PETPOINT_SPECIES')]);
	            } else {
	                //use value from returned results
	                $animalName = $DetailsOutput[constant('PETPOINT_NAME')]['value'];
	                $hover = ucwords(strtolower($DetailsOutput[constant('PETPOINT_NAME')]['value']));
	            }
	
	            if (($counter + 1) % $resultPerRow == 1) {
	                //$TDresult .=  "<div class='adopt-search-row'>";
	            }
	
	            $species = strtolower($results[constant('PETPOINT_SPECIES')]);
	            //echo '<pre>DETAILS POST LABEL PROCESSING '; print_r($DetailsOutput); echo '</pre>';           
	            $TDresult .= '<div class="pmp-search-result-container">';
	
	            /* Configure Image Onclick Parameter */
	            $clickID = 'pmp-animal-image-' . strtolower($species) . '-' . str_replace(" ", "-", strtolower($animalName)); 
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
	            $TDresult .= '<div class="pmp-search-result pmp-animal-image"><a href="' . $detailsPage . '" class="pmp-animal-image-link" id="' . $clickID . '" onclick="' . $imageOnClick . '" ><img src="' . $results['photo'] . '" title="' . $clickText . '"></a></div>';
	
	            foreach ($DetailsOutput as $key => $result) {
	                if ($key == constant('PETPOINT_NAME')) {
	                    $clickID = 'pmp-animal-' . constant('PETPOINT_NAME'). '-' . strtolower($species) . '-' . str_replace(" ", "-", strtolower($animalName)); 
	                    $clickText = $DetailsOutput[$key]['value'];
	                    $gaParamArray['event_category'] = 'Text';
	                    $gaParamArray['click_id'] = $clickID;
	                    $gaParamArray['click_text'] = $clickText;
	                    $nameOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
	                    $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '"><a href="' . $detailsPage . '" class="pmp-animal-name-link" id="' . $clickID . '" onclick="' . $nameOnClick . '" >' . $clickText . '</a></div>';
	                    //$TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '"><a href="' . $detailsPage . '" class="pmp-animal-name-link" id="pmp-animal-name-' . $species . '-' . $animalName . '" >' . $DetailsOutput[$key]['value'] . '</a></div>';
	                }
	                elseif ($labels != 'Enable') { /* RMB */
	                    $TDresult .= '<span class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $DetailsOutput[$key]['value'] . '</span> |'; /* RMB */
	                } else { /* RMB */
	                    $DetailLabel = '<div class ="pmp-search-result-label pmp-animal-' . strtolower($key) . '-label">' . $DetailsOutput[$key]['label'] . ': </div>'; /* RMB */
	                    $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $DetailsOutput[$key]['value'] . '</div></div>'; /* RMB */
	                } /* RMB */
	            }
	            /* RMB - Remove Final Separator */
	            if ($labels != 'Enable') { 
	                $TDresult = substr($TDresult, 0, -2);
	            }
	
	            //append icons at the bottom div
	
	            //$TDresult .= '<div class = "pmp-search-result pmp-animal-icons">' . $icons . '</div>';
	            $TDresult .= '</div>';
	            // End the HTML row at every fourth animal
	            if (($counter + 1) % $resultPerRow == 0) {
	                $TDresult .= "";
	            }
	            // Increment Counter
	            $counter++;
	        }
	        $TDresult .= "</div>";
	        //closing DIV on included file
	
	        if ($xmlArrayCount <= 1) {
	            if ($firstMethod == constant('FOUND_METHODTYPE_PETPOINT') . 'Search') {
	                return $TDresult .= "<div class='pmp-no-results'>" . $this->generalOptions['no_search_results_' . constant('FOUND_METHODTYPE_PETPOINT')] . "</div>";
	            } elseif ($firstMethod == constant('LOST_METHODTYPE_PETPOINT') . 'Search') {
	                return $TDresult .= "<div class='pmp-no-results'>" . $this->generalOptions['no_search_results_' . constant('LOST_METHODTYPE_PETPOINT')] . "</div>";
	            } else {
	                return $TDresult .= "<div class='pmp-no-results'>" . $this->generalOptions['no_search_results_' . constant('ADOPT_METHODTYPE_PETPOINT')] . "</div>";
	            }
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
	                //$TDresult .= '<div class="pmp-firstPage"><a href="#" class="pmp-page-first-link' . $addtoSearchClass . '" id="pmp-page-first">&laquo;</a></div><div class="pmp-previousPage"><a href="#" class="pmp-page-previous-link' . $addtoSearchClass . '" id="pmp-page-previous">&lsaquo;</a></div><div class="pmp-pageNumbers"></div><div class="pmp-nextPage"><a href="#" class="pmp-page-next-link' . $addtoSearchClass . '" id="pmp-page-next">&rsaquo;</a></div><div class="pmp-lastPage"><a href="#" class="pmp-page-last-link' . $addtoSearchClass . '" id="pmp-page-last">&raquo;</a></div>';
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
	            } else {
	                $TDresult .= '</div>';
	            }
	        }
	        return $TDresult;
        } else {
            //echo 'Support Email = ' . $supportEmail . '<br>';          
            return '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': Integration partner API key is missing in the General Settings.</p></div>';
        }
    }

    public function createDetails($animalIDIN, $items, $callFunc) {
        $queryString = '';
        
		if ( ($callFunc == constant('ADOPT_METHODTYPE_PETPOINT') . 'Details') ) {
			$apiMethod = 'AdoptableDetails';
		} else {
			$apiMethod = $callFunc;
		}
        
        $queryString = $this->baseURL . $apiMethod . '?authKey=' . $this->apiKey;      //Initial URL build
        $queryString = $queryString . '&' . str_replace(constant('PETPOINT_ID'), strtoupper(constant('PETPOINT_ID')), constant('PETPOINT_ID_ANIMAL')) . '=' . $animalIDIN;                //AnimalID to display
        return $this->fetch_pp_data($queryString, $callFunc, $items);
    }
    
    public function outputSearch($selfURLIN, $xmlWSIN, $details, $callFunc) {
    	//echo 'Outout Search Called w/ Input URL<br>' . $selfURLIN . '<br>';
        //echo '<pre>Preparing to Output ' . $callFunc . ' Search with Details '; print_r($details); echo '</pre>';
        //echo '<pre>OUTPUT SEARCH CALLED WITH XML<br>'; print_r($xmlWSIN); echo '</pre>';
       	$xmlArrayCount = count($xmlWSIN);
        //echo 'Array Count = ' . $xmlArrayCount . '<br>';
                
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

        $valuesFile = 'pmp-field-values.php';
        $valuesFile = $this->partialsDir . $valuesFile;
        require($valuesFile);

        //Fields to be Displayed in Search Results
        $searchResultDetails = [];        
        $searchResultDetails = $this->allAPIFunction->showDetails($callFunc, $details);
        //echo '<pre>SEARCH RESULTS<br>'; print_r($searchResultDetails); echo '</pre>';
        $detailCountArray = array_count_values($searchResultDetails);
        //echo '<pre>Search Detail Count<br>'; print_r($detailCount); echo '</pre>'; 
        $detailCount = count($detailCountArray);
        //echo 'Number of Details = ' . $detailCount . '<br>';

        $searchResultLabels = [];  
        if ($callFunc == constant('ADOPT_METHODTYPE_PETPOINT') . 'Search') {
            $searchResultLabels = $this->animalDetailFunction->Animal_Labels($searchResultDetails, constant('ADOPT_METHODTYPE_PETPOINT'), 'search');   
        } elseif ($callFunc == constant('FOUND_METHODTYPE_PETPOINT') . 'Search') {
            $searchResultLabels = $this->animalDetailFunction->Animal_Labels($searchResultDetails, constant('FOUND_METHODTYPE_PETPOINT'), 'search');
        } elseif ($callFunc == constant('LOST_METHODTYPE_PETPOINT') . 'Search') {
            $searchResultLabels = $this->animalDetailFunction->Animal_Labels($searchResultDetails, constant('LOST_METHODTYPE_PETPOINT'), 'search');
        }       
        //echo '<pre>SEARCH RESULT LABELS<br>'; print_r($searchResultLabels); echo '</pre>';
        
        /*
         * Search Form Variables
         * $this->FilterAdminOptions = Options to show on search form - Form Fields
         * $filterValueArray = Values of the dropdown if needed
         */
        //should return search filters in array format array($this->FilterAdminOptions, $filterValues)
       	$searchFilters = []; 
        $searchFilters = $this->SearchFilters($callFunc, $details);
        //echo '<pre>SEARCH FILTERS<br>'; print_r($searchFilters); echo '</pre>';
        //check if species is dog or cat and use default species
        
        $addtoSearchClass = '';
        if (is_array($details)) {
            if (array_key_exists(constant('PETPOINT_SPECIES'), $details)) {
                $addtoSearchClass = '-' . $details[constant('PETPOINT_SPECIES')];
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
		//echo 'Levels Suffix is ' . $levelsSuffix . '.<br>';
//        $levelsSuffix = '';
//        //echo 'Called with Method ' . $callFunc . '<br>';
//        if ($callFunc == 'foundSearch') {
//            $levelsSuffix = 'found';
//        } elseif ($callFunc == 'lostSearch') {
//                    $levelsSuffix = 'lost';
//                } else {
//                    $levelsSuffix = 'adopt'; 
//                }
                                
        /* Get Field Visibility Levels by License Type */
        $levelsFile = 'pmp-field-levels-' . $levelsSuffix . '.php';
        $requireFile = $this->partialsDir . $levelsFile;
        //echo 'Preparing to Include ' . $requireFile . '.<br>';
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
        //echo "<pre>";
        //$kc =$xmlWSIN->XmlNode;
        //print_r($kc);
        
		/* Initialize Icon Variables */
		if ($this->displayIconsSearch == 1) {
			$iconPath = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('IMAGES_DIR') . '/';
			if (array_key_exists(constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_search_icons_max', $this->generalOptions)) {
				$maxIcons = $this->generalOptions[constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_search_icons_max'];
			} else {
				$maxIcons = 5;
			}
		}
        // Sets the counter to zero to use to loop through array count
        $counter = 0;		
        $xmlItems = [];        
        while ( ($counter < $xmlArrayCount - 1) && ($counter < $outputMax) ) {
            //identify results to use based on function
            if ($callFunc == constant('FOUND_METHODTYPE_PETPOINT') . 'Search') {
                $detailsFunc = constant('FOUND_METHODTYPE_PETPOINT') . 'Details';
                if (isset($xmlWSIN->XmlNode[$counter]->an)) {                
                	$xmlItems = (array)$xmlWSIN->XmlNode[$counter]->an;
                } else {
                	$xmlItems = [];
                }
            } elseif ($callFunc == constant('LOST_METHODTYPE_PETPOINT') . 'Search') {
                $detailsFunc = constant('LOST_METHODTYPE_PETPOINT') . 'Details';
                if (isset($xmlWSIN->XmlNode[$counter]->an)) {                
                	$xmlItems = (array)$xmlWSIN->XmlNode[$counter]->an;
                } else {
                	$xmlItems = [];
                }                	
            } else {
                $detailsFunc = 'AdoptableDetails';
                if (isset($xmlWSIN->XmlNode[$counter]->adoptableSearch)) {                
                	$xmlItems = (array)$xmlWSIN->XmlNode[$counter]->adoptableSearch; /* Fiverr Change 04/19/2023 */
                } else {
                	$xmlItems = [];
                }                	                	
            }
           // echo '<PRE>XML ITEMS<br>'; print_r($xmlItems);'</pre>';
            //to lower case just to match keys
           // echo "<pre>";
            $resultArray = array_combine(array_map('strtolower', array_keys($xmlItems)), $xmlItems);
            
            //echo '<pre>Results to Display '; print_r($resultArray); echo "</pre>";
            //echo "<pre>my "; print_r($this->labelOptions); echo "</pre>";
            //echo "<pre>co "; print_r(Pet_Match_Pro_PP_Options::$foundSearchDetails); echo "</pre>";
            //place each value from above in the main array of the called function with labels
            //echo "<pre>"; print_r($searchDetailsOutput); echo "</pre>";
            //default to "Not Defined" for empty
            //add the link to the details page first
            //create search details based on details set by admin or shortcode
            
            //echo 'Defining Details Page for Method ' . $detailsFunc . '.<br>';
          	if (array_key_exists(constant('PETPOINT_ID'), $resultArray)) {
          		$detailsPage = $selfURLIN . '?method=' . $detailsFunc . '&' . str_replace(constant('PETPOINT_ID'), strtoupper(constant('PETPOINT_ID')), constant('PETPOINT_ID_ANIMAL')) . '=' . $resultArray[constant('PETPOINT_ID')];
          	} else {
          		$detailsPage = $selfURLIN . '?method=' . $detailsFunc . '&' . str_replace(constant('PETPOINT_ID'), strtoupper(constant('PETPOINT_ID')), constant('PETPOINT_ID_ANIMAL')) . '=' . constant('ERROR');          	
          	}
            //use override details to show $searchResultDetails if defined in admin and or shortcode

            if (array_key_exists(constant('PETPOINT_SPECIES'), $resultArray)) {
            	$species = $resultArray[constant('PETPOINT_SPECIES')];
            } else {
            	$species = 	ucfirst(constant('PETPOINT_SPECIES'));
            }
            //echo 'Animal Species = ' . $species . '<br>';
            $speciesLower = strtolower($species);

            //if name is forced to be excluded get the name but dont include in array
            if (!array_key_exists(constant('PETPOINT_NAME'), $resultArray)) {
				$resultArray[constant('PETPOINT_NAME')] = constant('EMPTY_VALUE');
            } 
            $animalName = $resultArray[constant('PETPOINT_NAME')];
            //echo 'Processing Data for Animal ' . $animalName . '.<br>';
            
            if (!array_key_exists(constant('PETPOINT_NAME'), $searchResultDetails)) {
                $hover = "this " . $species;
            } else {
                //use value from returned results
                $hover = ucwords(strtolower($resultArray[constant('PETPOINT_NAME')]));
            }   

			if (array_key_exists(constant('PETPOINT_LOCATION'), $resultArray)) {			
	            if (strlen(trim($resultArray[constant('PETPOINT_LOCATION')])) == 0 ) {
	            	$resultArray[constant('PETPOINT_LOCATION')] = constant('EMPTY_VALUE');   
	            }      
	        } else {
	        	$resultArray[constant('PETPOINT_LOCATION')] = constant('EMPTY_VALUE');
	        }

			$DetailsOutput = [];
			//echo 'Processing Results for Animal ' . $resultArray['id'] . ' with Name ' . $resultArray['name'] . ' at Location ' . $resultArray[constant('PETPOINT_LOCATION')] . '.<br>';
			if ( (is_array($resultArray)) && (is_array($this->locationExclusionArray)) ) {			
	            if ( (!array_key_exists(constant('ERROR'), $searchResultDetails)) && (!array_key_exists($resultArray[constant('PETPOINT_LOCATION')],$this->locationExclusionArray)) ) { 
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
	
//		            foreach ($searchResultDetails as $key => $output) {
//		                //echo 'Processing Search Result Detail ' . $key . ' with Value ' . $output . '<br>';
//		                //use labels from static variable
//		                $DetailsOutput[$key]['label'] = $searchResultLabels[$key];
//		                //$DetailsOutput[$key]['label'] = $result_labels[$key];
//		                //$DetailsOutput[$key]['label'] = $searchDetailsOutput[$key . $keySuffix];
//		                if (strlen(preg_replace('/\s+/', '', isset($resultArray[$key]))) == 0) {
//		                    $DetailsOutput[$key]['value'] = constant('EMPTY_VALUE');
//		                } else {
//		                    $DetailsOutput[$key]['value'] = $resultArray[$key];
//		                }
		                
			                if ($animalKey == constant('PETPOINT_SEX')) {
			                	if ( (array_key_exists($animalKey, $resultArray)) ) {
			                		//echo 'Processing Sex Key ' . $animalKey . ' with Value ' . $resultArray[$animalKey] . '.<br>';
			                		$sexKey = $resultArray[$animalKey];
			                		//echo 'Processing Result Sex Value ' . $sexKey . '.<br>';
			                		if (array_key_exists($sexKey, $pmpFieldValues['value_sex'])) {
			                			$DetailsOutput[$animalKey]['value'] = $pmpFieldValues['value_sex'][$sexKey];
			                		} else {
			                			$DetailsOutput[$animalKey]['value'] = $resultArray[$animalKey];
			                		}
			                	}
	   			            }
   			            		                
			                //for age compute in years and months
			                if ( ($animalKey == constant('PETPOINT_AGE')) && (array_key_exists($animalKey, $resultArray)) ) {
			                	$ageMonths = (int)$resultArray[$animalKey];
			                	//echo 'Processing Age with Value ' . $resultArray[$animalKey] . ' for Animal ' . $resultArray['id'] . '<br>';
	               				if ( ($ageMonths > 0) && (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//			                		$birthDate = date_create(date('m/d/Y')); 
//			                		$birthDate = date_sub($birthDate, date_interval_create_from_date_string($resultArray[$animalKey] . ' months'));
									//$birthDate = date_create(str_replace('-', '/', $resultArray[$key]));
				                	$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->ageInYears($ageMonths);
				                } elseif ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
				                	$DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
				                } else {
				                	$DetailsOutput[$animalKey]['value'] = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required';
				                }	
			                }
			                
//			                if ( ($animalKey == constant('PETPOINT_DATE_BIRTH')) && (array_key_exists($animalKey, $resultArray)) ) {
//			                	//echo 'Processing Birth Date with Value ' . $resultArray[$animalKey] . ' for Animal ' . $resultArray['id'] . '<br>';
//	 	               			if ( ($resultArray[$animalKey] != constant('EMPTY_VALUE')) && (!empty($resultArray[$animalKey] )) ) {
//									$birthDate = date_create($resultArray[$animalKey]);
//									$birthDate = date_create(str_replace('-', '/', $resultArray[$animalKey]));
//				                    if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//				            			$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->ageInYears($birthDate);
//				                    }
//				                } else {
//				                	$DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
//				                }	
//				            }
			                		                
			                /* RMB - Process Location Value */
			                if ($animalKey == constant('PETPOINT_LOCATION')) {
		            			//echo 'Result Location Key is ' . $animalKey . ' with Value ' . $resultArray[$key] . '.<br>';
			                	if (array_key_exists($animalKey, $DetailsOutput)) {               	
			                    	$locationValue = $DetailsOutput[$animalKey]['value'];
			                    	$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $locationValue);
									//echo 'Location Value of ' . $DetailsOutput[$animalKey]['value'] . '.<br>';	               	                     	
			               		}
			               	} 
//		                /* RMB - Remove Time from Date Fields */
//		                if (str_contains($key, 'date')) {
//		                    $DetailsOutput[$key]['value'] = date("m-d-Y",strtotime($resultArray[$key]));
//		                }
	                
							$iconString = [];	                
							if ($this->displayIconsSearch == 1) {
								//echo 'Preparing to Process Icons<br>';
								//print_r($resultArray);
					    	   	//echo '<pre>ICON FUNCTION CALLED WITH<br>'; print_r($resultArray[$key]); echo '</pre>';
					    	   	$iconString[$counter] = $this->animalDetailFunction->display_pet_icons($resultArray, $animalName, $maxIcons);
					    	   	//echo 'Icon String<br>' . $iconString[$counter] . '<br>';
					    	}
			            }
			        	//echo '<pre>DETAILS OUTPUT<br>'; print_r($DetailsOutput); echo '</pre>';		            
					}
				}
			}            
            //vd($DetailsOutput);
//            if (($counter + 1) % $resultPerRow == 1) {
//                //$TDresult .=  "<div class='adoptable-search-row'>";
//            }

			$locationValue = $resultArray[constant('PETPOINT_LOCATION')];
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
	            if ( isset($resultArray[constant('PETPOINT_PHOTO')]) ) {
	                $imgSrc = $resultArray[constant('PETPOINT_PHOTO')];
	            } else {
	                $imgSrc = '';
	            }

	            $TDresult .= '<div class="pmp-search-result-container">'; 	            
	            $TDresult .= '<div class="pmp-search-result pmp-animal-image"><a href="' . $detailsPage . '" class="pmp-animal-image-link" id="' . $clickID . '" onclick="' . $imageOnClick . '" ><img src="' . $imgSrc . '" title="' . $clickText . '"></a></div> <!-- .pmp-animal-image -->';
	            //echo '<pre>PROCESSING RESULTS<br>'; print_r($this->searchOutput); echo '</pre>';
	            foreach ($this->searchOutput as $key => $result) {
	                //echo '<pre>PROCESSING RESULT KEY<br>'; print_r($key); echo '</pre>';
	                //echo 'Location Value is ' . $locationValue . '.<br>';
	                if ( ($key != constant('ERROR')) ) {
	                    $levelKey = constant('LEVEL_PREFIX_SEARCH_RESULT') . $key . '_' . $levelsSuffix;
	                    //echo 'Level Key = ' . $levelKey . '<br>';         
	                    if ($key == constant('PETPOINT_NAME')) {
	                        $clickID = 'pmp-animal-' . constant('PETPOINT_NAME') . '-' . $speciesLower . '-' . str_replace(" ", "-", strtolower($animalName)); 
	                        $clickText = $this->searchOutput[$key]['value'];
	                        $gaName = 'text_pmp_search_select';                                     
	                        $gaParamArray['event_category'] = 'Text';
	                        $gaParamArray['click_id'] = $clickID;
	                        $gaParamArray['click_text'] = $clickText;
	                        $nameOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
	                        if ( ($this->searchDetailSource == 'admin') && ($labels == 'Enable') ) {
	                            $DetailLabel = '<div class ="pmp-search-result-label pmp-animal-' . strtolower($key) . '-label">' . $this->searchOutput[$key]['label'] . ': </div>'; 
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . '<a href="' . $detailsPage . '" class="pmp-animal-name-link" id="' . $clickID . '" onclick="' . $nameOnClick . '" >' . $clickText . '</a></div></div>';                         
	                        } else {
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '"><a href="' . $detailsPage . '" class="pmp-animal-name-link" id="' . $clickID . '" onclick="' . $nameOnClick . '" >' . $clickText . '</a></div><!-- .pmp-search-result -->';
	                        }                  
	                    } elseif ($labels != 'Enable') { /* RMB */
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
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $this->searchOutput[$key]['value'] . '</div></div>'; /* RMB */
	                        } else {
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required</div></div>'; /* RMB */                 
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
        }

        $TDresult .= '</div> <!-- .pmp-search-results-container -->';
        //closing DIV on included file
        if ($xmlArrayCount <= 1) {
            if ($callFunc == constant('FOUND_METHODTYPE_PETPOINT') . 'Search') {
                return $TDresult .= "<div class='pmp-no-results'>" . $this->generalOptions['no_search_results_' . constant('FOUND_METHODTYPE_PETPOINT')] . "</div>";
            } elseif ($callFunc == constant('LOST_METHODTYPE_PETPOINT') . 'Search') {
                return $TDresult .= "<div class='pmp-no-results'>" . $this->generalOptions['no_search_results_' . constant('LOST_METHODTYPE_PETPOINT')] . "</div>";
            } else {
                return $TDresult .= "<div class='pmp-no-results'>" . $this->generalOptions['no_search_results_' . constant('ADOPT_METHODTYPE_PETPOINT')] . "</div>";
            }
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
            } else {
                $TDresult .= '</div>';
            }
        }
        return $TDresult;
    }

    public function outputFeatured($selfURLIN, $xmlWSIN, $details, $callFunc) {
        //echo '<pre>FEATURED SHORTCODE DATA<br>'; print_r($xmlWSIN); echo '</pre>';
        $xmlArrayCount = count($xmlWSIN);
        
        $labels = $this->allAPIFunction->showLabels($callFunc, $details);
        
        //details to be shown on result page
        //admin filter options
        //$callFunc = type of search, $details = ovveride in shortcode
        $searchResultDetails = $this->allAPIFunction->showDetails(constant('PETPOINT_FEATURED'), $details);
        //echo '<pre>SEARCH RESULTS<br>'; print_r($searchResultDetails); echo '</pre>';

        $detailCountArray = array_count_values($searchResultDetails);
        //echo '<pre>Search Detail Count<br>'; print_r($detailCount); echo '</pre>'; 
        $detailCount = count($detailCountArray);
        //echo 'Detail Count = ' . $detailCount . '<br>'; 
              
        $nameCount = 0;
        if ( array_key_exists(constant('PETPOINT_NAME'), $detailCountArray) ) {
            $nameCount = 1;
        }
        //echo 'Name Count = ' . $nameCount . '<br>';
        
        $detailCount = $detailCount - $nameCount;
        //echo 'Detail Count After Name Adjustment = ' . $detailCount . '<br>';
        
        $searchResultLabels = $this->animalDetailFunction->Animal_Labels($searchResultDetails, constant('ADOPT_METHODTYPE_PETPOINT'), 'search');  
         
        $TDresult = '';
        if (is_array($details) && array_key_exists('title', $details)) {
            $TDresult .= '<div class="pmp-results-title featured">';
            $TDresult .= $details['title'];
            $TDresult .= '</div>';
        }
        
        /* Get Field Visibility Levels by License Type */
        $levelsFile = 'pmp-field-levels-' . constant('ADOPT_METHODTYPE_PETPOINT') . '.php';
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
        while ($counter < $xmlArrayCount - 1) {
        	//echo 'Processing Counter ' . $counter . '<br>';
        	
            //$xmlItems = (array)$xmlWSIN->XmlNode[$counter]->an;
            //$results = array_combine(array_map('strtolower', array_keys($xmlItems)), $xmlItems);
        	
            //var_dump($xmlWSIN->XmlNode->$counter->adoptableSearch);
            if (!empty($xmlWSIN->XmlNode[$counter]->adoptableSearch->Featured)) {  
            	//echo 'XML Not Empty<br>';  
            	//echo 'Featured Value for Counter ' . $counter . ' is ' . $xmlWSIN->XmlNode[$counter]->adoptableSearch->Featured . '<br>';        
	            if ($xmlWSIN->XmlNode[$counter]->adoptableSearch->Featured == "Yes") {
	            	//echo 'Featured Pet Found<br>';
	                //featured is found
	                $featuredCounter = 1;
	                //echo 'Featured Counter = 1<br>';
	                //vd($xmlWSIN->XmlNode->$counter->adoptableSearch);
	                //identify results to use based on function
	                $xmlItems = (array)$xmlWSIN->XmlNode[$counter]->adoptableSearch;
	                $detailsFunc = 'AdoptableDetails';
	                //to lower case just to match keys
	                $resultArray = array_combine(array_map('strtolower', array_keys($xmlItems)), $xmlItems);
	                //place each value from above in the main array of the called function with labels
	                $searchDetailsOutput = $this->allAPIFunction->showDetails($callFunc, $details);
	                $detailsPage = $selfURLIN . '?method=' . $detailsFunc . '&' . str_replace(constant('PETPOINT_ID'), strtoupper(constant('PETPOINT_ID')), constant('PETPOINT_ID_ANIMAL')) . '=' . $resultArray[constant('PETPOINT_ID')];
	                //use override details to show $searchResultDetails if defined in admin and or shortcode
	                
		            /* Include Default Field Values */
		            $valuesFile = 'pmp-field-values.php';
		            $requireFile = $this->partialsDir . $valuesFile;
		            require $requireFile;
	                	
	                foreach ($searchResultDetails as $animalKey => $output) {
	                    //use labels from static variable
	                    $DetailsOutput[$animalKey]['label'] = $searchResultLabels[$animalKey];
	                    if (strlen(preg_replace('/\s+/', '', $resultArray[$animalKey])) == 0) {
	                        $DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
	                    } else {
	                        $DetailsOutput[$animalKey]['value'] = $resultArray[$animalKey];
	                    }
	
//	                    //for sex in whole world used on found and lost
//	                    if ($key == constant('PETPOINT_SEX')) {
//		                	$resultsKey = $results[$key];
//		                	echo 'Sex Result Key is ' . $resultsKey . '.<br>';
//		                	if (array_key_exists($resultsKey, $pmpFieldValues['value_sex'])) {
//		                		$DetailsOutput[$key]['value'] = $pmpFieldValues['value_sex'][$resultsKey];
//		                	} else {
//		                		$DetailsOutput[$key]['value'] = $pmpFieldValues['value_sex']['U'];
//		                	}	
//	                        if ($results[$key] == 'M') {
//	                            $DetailsOutput[$key]['value'] = 'Male';
//	                        } elseif ($results[$key] == 'F') {
//	                            $DetailsOutput[$key]['value'] = 'Female';
//	                        } elseif ($results[$key] == 'U') {
//	                            $DetailsOutput[$key]['value'] = 'Unknown';
//	                        }
//	                    }

//	                    //for age compute in years and months
//	                    if ($key == constant('PETPOINT_AGE')) {
//	                		$DetailsOutput[$key]['value'] = $this->convertAge($results[$key], array_key_exists('age_in_years', $this->generalOptions));
//	                    }
	                    
//	   	                if ($key == constant('PETPOINT_LOCATION')) {
//	            			//echo 'Result Location Value is ' . $results[$key] . '.<br>';
//		                	if (array_key_exists($key, $results)) {               	
//		                    	$locationValue = $results[$key];
//		                    	$DetailsOutput[$key]['value'] = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $locationValue);
//								//echo 'Location Value of ' . $DetailsOutput[$key]['value'] . '.<br>';	               	                     	
//		               		}
//		               	} 	
		               	
			            if ($animalKey == constant('PETPOINT_SEX')) {
			               	if ( (array_key_exists($animalKey, $resultArray)) ) {
			               		//echo 'Processing Sex Key ' . $animalKey . ' with Value ' . $resultArray[$animalKey] . '.<br>';
			               		$sexKey = $resultArray[$animalKey];
			               		//echo 'Processing Result Sex Value ' . $sexKey . '.<br>';
			               		if (array_key_exists($sexKey, $pmpFieldValues['value_sex'])) {
			               			$DetailsOutput[$animalKey]['value'] = $pmpFieldValues['value_sex'][$sexKey];
			               		} else {
			               			$DetailsOutput[$animalKey]['value'] = $resultArray[$animalKey];
			               		}
			               	}
	   			        }
   			            		                
			            //for age compute in years and months
			            if ( ($animalKey == constant('PETPOINT_AGE')) && (array_key_exists($animalKey, $resultArray)) ) {
			               	//echo 'Processing Age with Value ' . $resultArray[$animalKey] . ' for Animal ' . $resultArray['id'] . '<br>';
			               	$ageMonths = (int)$resultArray[$animalKey];
	               			if ( ($ageMonths > 0) && (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//			               		$birthDate = date_create(date('m/d/Y')); 
//			               		$birthDate = date_sub($birthDate, date_interval_create_from_date_string($resultArray[$animalKey] . ' months'));
								//$birthDate = date_create(str_replace('-', '/', $resultArray[$animalKey]));
				               	$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->ageInYears($ageMonths);
				            } elseif ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
				               	$DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
				            } else {
				               	$DetailsOutput[$animalKey]['value'] = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required';
				            }	
			            }
			               
//			            if ( ($animalKey == constant('PETPOINT_DATE_BIRTH')) && (array_key_exists($animalKey, $resultArray)) ) {
//			               	//echo 'Processing Birth Date with Value ' . $resultArray[$animalKey] . ' for Animal ' . $resultArray['id'] . '<br>';
//	 	               		if ( ($resultArray[$animalKey] != constant('EMPTY_VALUE')) && (!empty($resultArray[$animalKey] )) ) {
//								$birthDate = date_create($resultArray[$animalKey]);
//								$birthDate = date_create(str_replace('-', '/', $resultArray[$animalKey]));
//				                if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//				           			$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->ageInYears($birthDate);
//				                }
//				           } else {
//				               	$DetailsOutput[$animalKey]['value'] = constant('EMPTY_VALUE');
//				           }	
//				        }
			                		                
			            /* RMB - Process Location Value */
			            if ($animalKey == constant('PETPOINT_LOCATION')) {
		            		//echo 'Result Location Key is ' . $animalKey . ' with Value ' . $resultArray[$animalKey] . '.<br>';
			               	if (array_key_exists($animalKey, $DetailsOutput)) {               	
			                   	$locationValue = $DetailsOutput[$animalKey]['value'];
			                   	$DetailsOutput[$animalKey]['value'] = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $locationValue);
								//echo 'Location Value of ' . $DetailsOutput[$animalKey]['value'] . '.<br>';	               	                     	
			            	}
			            } 
	                }
	
	                $TDresult .= '<div class="pmp-search-result-container featured">';
                
	                /* Set Image OnClick Parameter */
					if (array_key_exists(constant('PETPOINT_ID_SPECIES'), $details)) {
						$species =  $details[constant('PETPOINT_ID_SPECIES')];
					} else {
						if (array_key_exists(constant('PETPOINT_ID_SPECIES'), $details)) {
							$species =  $details[constant('PETPOINT_ID_SPECIES')];
						} else {
							$species = ucfirst(constant('PETPOINT_ID_SPECIES'));
						}
					}
					
					if (array_key_exists(constant('PETPOINT_NAME'), $DetailsOutput)) {
						if (array_key_exists('value', $DetailsOutput[constant('PETPOINT_NAME')])) {
							if (strlen(trim($DetailsOutput[constant('PETPOINT_NAME')]['value'])) > 0 ) {
								$animalName = $DetailsOutput[constant('PETPOINT_NAME')]['value'];
							} else {
								$animalName = constant('PETPOINT_NAME');
							}
						} else {
							$animalName = constant('PETPOINT_NAME');
						}
					} else {
						$animalName = constant('PETPOINT_NAME');
					}

	                $clickID = 'pmp-animal-image-' . strtolower($species) . '-' . str_replace(" ", "-", strtolower($animalName)); 
	                $clickText = 'Learn More About ' . ucfirst($animalName);
	                $gaName = 'image_pmp_search_select';                
	                $gaParamArray['event_category'] = 'Image';
	                $gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];        
	                $gaParamArray['click_id'] = $clickID;
	                $gaParamArray['click_text'] = $clickText;               
	                $gaParamArray['click_url'] = $detailsPage;
	                $imageOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
	                //echo 'Image OnClick Parameter <br>';
	                //echo  $imageOnClick . '<br>';            

	                $TDresult .= '<div class="pmp-search-result pmp-animal-image featured"><a href="' . $detailsPage . '" class="pmp-animal-image-link" id="' . $clickID . '" onclick="' . $imageOnClick . '"><img src="' . $resultArray['photo'] . '" title="' . $clickText . '" class="pmp-search-result-img pmp-animal-image featured-img"></a></div> <!-- .pmp-animal-image .featured-->';

	                foreach ($DetailsOutput as $key => $result) {
	                    $levelKey = constant('LEVEL_PREFIX_SEARCH_RESULT') . $key . '_' . constant('ADOPT_METHODTYPE_PETPOINT');
	                    //echo 'Level Key = ' . $levelKey . '<br>';         
	                    if ($key == constant('PETPOINT_NAME')) {
	                        $clickID = 'pmp-animal-' . constant('PETPOINT_NAME') . '-' . strtolower($species) . '-' . str_replace(" ", "-", strtolower($animalName)); 
	                        $clickText = ucfirst($DetailsOutput[$key]['value']);
	                        $gaName = 'text_pmp_search_select';                                     
	                        $gaParamArray['event_category'] = 'Text';
	                        $gaParamArray['click_id'] = $clickID;
	                        $gaParamArray['click_text'] = $clickText;
	                        $nameOnClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);
	                    
	                        $TDresult .= '<div class ="pmp-search-result pmp-animal-' . $key . ' featured"><a href="' . $detailsPage . '" id="' . $clickID . '" onclick="' . $nameOnClick . '">' . $clickText . '</a></div>';
//	                    }
//	                    elseif ($labels != 'Enable') {
//	                        //echo 'Processing Key ' . $levelKey . ' with Level Value ' . $fieldLevels[$levelKey] . '<br>';
//	                        if ($this->PMPLicenseTypeID <= $fieldLevels[$levelKey]) {                   
//	                            $TDresult .= '<span class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $DetailsOutput[$key]['value'] . '</span> |'; 
//	                        } else {
//	                            $TDresult .= '<span class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail"><a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required</span> |';                  
//	                        }
//	                    } else { 
//	                        $DetailLabel = '<div class ="pmp-search-result-label pmp-animal-' . strtolower($key) . '-label">' . $DetailsOutput[$key]['label'] . ': </div>'; /* RMB */
//	                        //echo 'Processing Key ' . $levelKey . ' with Level Value ' . $fieldLevels[$levelKey] . '<br>';
//	                        if ($this->PMPLicenseTypeID <= $fieldLevels[$levelKey]) {                                       
//	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $DetailsOutput[$key]['value'] . '</div></div>'; 
//	                        } else {
//	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required</div></div>'; /* RMB */                 
//	                        }                            
//                    	} 
	                    } elseif ($labels != 'Enable') { /* RMB */
							if ( (strlen(trim($DetailsOutput[$key]['value'])) > 0) && ($key != constant('EMPTY_VALUE')) ) {   
		                        $TDresult .= '<div class ="pmp-search-result pmp-animal-details-no-labels">';                                       							                     
		                        if ($this->PMPLicenseTypeID <= $fieldLevels[$levelKey]) {
			                        //echo 'Processing Key ' . $levelKey . ' with Level Value ' . $fieldLevels[$levelKey] . ' and Value ' . $DetailsOutput[$key]['value'] . '<br>';	                        
		                            $TDresult .= '<span class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $DetailsOutput[$key]['value'] . '</span> |'; /* RMB */
		                        } else {
		                            $TDresult .= '<span class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail"><a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required</span> |';                  
		                        }
		                        $TDresult .= '</div><!-- .pmp-animal-details-no-labels -->';		                        
		                    }
	                    } else { /* RMB */
	                        $DetailLabel = '<div class ="pmp-search-result-label pmp-animal-' . strtolower($key) . '-label">' . $DetailsOutput[$key]['label'] . ': </div>'; /* RMB */                
	                        //echo 'Processing Key ' . $levelKey . ' with Level Value ' . $fieldLevels[$levelKey] . '<br>';
	                        if ($this->PMPLicenseTypeID <= $fieldLevels[$levelKey]) {                
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . $DetailsOutput[$key]['value'] . '</div></div>'; /* RMB */
	                        } else {
	                            $TDresult .= '<div class ="pmp-search-result pmp-animal-' . strtolower($key) . '">' . $DetailLabel . '<div class ="pmp-search-result-detail pmp-animal-' . strtolower($key) . '-detail">' . '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required</div></div>'; /* RMB */                 
	                        }
	                    } /* RMB */
                	}
//	                if ( ($labels != 'Enable') && ($detailCount > 0) ) { 
//	                //if ($labels != 'Enable') { 
//	                    $TDresult = substr($TDresult, 0, -2);
//	                }

		            if ( ($labels != 'Enable') && ($detailCount != 1) ) { 
		            	//echo 'Removing Final Separator from Result with Length ' . strlen($TDresult) . '.<br>';
		            	$lastSeparator = strrpos($TDresult, ' |');
		            	//echo 'Last Separator Located at ' . $lastSeparator . '.<br>';
		            	$TDresult = substr_replace($TDresult, '', $lastSeparator-1, -1); 
		                //$TDresult = substr($TDresult, 0, -2);
		                //echo 'Result now Has Length of ' . strlen($TDresult) . '.<br>';
						$TDresult .= '</div><!-- .pmp-animal-details-no-labels -->';                  
		            }
	                              
	                $TDresult .= '</div>';
	                $counter = $xmlArrayCount;                	
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

    public function outputDetails($xmlWSArrayIN, $items, $callFunc) {
        if ( WP_Filesystem() ) {
            global $wp_filesystem;
        }
        //echo 'Output Details Called with Function ' . $callFunc . '.<br>';
        //echo '<pre> OUTPUT DETAILS CALLED WITH PARAMETERS '; print_r($items); echo '</pre>';
        //echo '<pre> OUTPUT DETAILS CALLED DETAILS<br>'; print_r($xmlWSArrayIN); echo '</pre>';
        //details to be shown on result page
        
		if (is_countable($xmlWSArrayIN)) {
			$resultCount = count($xmlWSArrayIN);
		} else {
			$resultCount = 0;
        }  
        //echo 'Result Count is ' . $resultCount . '<br>';              

        /* Obtain License Type to Secure Features */    
//        $licenseTypeID = $this->PMPLicenseTypeID; 

		/* Include Style Updates from Colors Tab */
		$colorsFile = 'pet-match-pro-public-color-css.php';
        echo '<style id="pmp-color-options-css">';
		include $this->partialsPublicDir . $colorsFile;
        echo '</style> <!-- #pmp-color-options-css -->';
        
        /* Include Default Field Values */
        $valuesFile = 'pmp-field-values.php';
        $requireFile = $this->partialsDir . $valuesFile;
        require $requireFile;
        
//        /*Include Custom CSS from Admin for Paid Licenses */
//        if ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
//            echo '<style id="pmp-custom-css">';
//            echo $this->generalOptions['pmp_custom_css'];
//            echo '</style> <!-- #pmp-custom-css -->';
//        }

//        $keySuffix = '';
//        if ($callFunc == 'foundDetails') {
//            $keySuffix = '_found';
//        } elseif ($callFunc == 'lostDetails') {
//                    $keySuffix = '_lost';
//                } else {
//                    $keySuffix = '_adopt'; 
//                }      

        /* Determine Key Suffix */
        $detailPosition = strpos($callFunc, 'Details');
        $callMethod = substr($callFunc, 0, $detailPosition);
        //echo 'Call Method is ' . $callMethod . '.<br>';        
        if (strtolower($callMethod) == 'adoptable') {
        	$callMethod = constant('ADOPT_METHODTYPE_PETPOINT');
        }
        //echo 'Call Method is ' . $callMethod . '.<br>';        
        $keySuffix = '_' . $callMethod;
        //$keySuffix = '_adopt'; 
        //echo 'Key Suffix is ' . $keySuffix . '.<br>';

//        if ($callFunc == 'foundDetails') {
//            $DetailsOutput = $this->animalDetailFunction->Animal_Details(constant('FOUND_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, 'front-end');
//            $template = $this->generalOptions['details_template_' . constant('FOUND_METHODTYPE_PETPOINT')];
//           //echo "<pre>LABEL OPTIONS AFTER FOUND PROCESSING<br>"; print_r($this->labelOptions);echo "</pre>";                
//        } elseif ($callFunc == 'lostDetails') {
//            $DetailsOutput = $this->animalDetailFunction->Animal_Details(constant('LOST_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, 'front-end');
//            $template = $this->generalOptions['details_template_' . constant('LOST_METHODTYPE_PETPOINT')];
//        } else {
//            $DetailsOutput = $this->animalDetailFunction->Animal_Details(constant('ADOPT_METHODTYPE_PETPOINT'), $this->PMPLicenseTypeID, 'front-end');
//            $template = $this->generalOptions['details_template_' . constant('ADOPT_METHODTYPE_PETPOINT')];
//        }
//        $DetailsOutput = $this->animalDetailFunction->Animal_Details($callMethod, $this->PMPLicenseTypeID, 'front-end');
        //echo "<pre>DETAILS OUTPUT<br>"; print_r($DetailsOutput);echo "</pre>"; 
        $detailTemplate = 'details_template' . $keySuffix;
        if ( (is_array($this->generalOptions)) && (array_key_exists($detailTemplate, $this->generalOptions)) ) {
        	$template = $this->generalOptions[$detailTemplate];
        } else {
        	$template = '';
        }
        //echo 'Detail Template File = ' . $template . '<br>';

//        $DetailsOutput = array_change_key_case($DetailsOutput, CASE_LOWER);
        //echo "<pre>DETAILS OUTPUT<br>"; print_r($DetailsOutput);echo "</pre>";

        $ResultDetails = array_change_key_case($this->allAPIFunction->showDetails($callFunc, $items));
        //echo "<pre>RESULT DETAILS<br>"; print_r($ResultDetails);echo "</pre>";
                
        if ($resultCount > 0) {
	        $resultArray = array_change_key_case($xmlWSArrayIN, CASE_LOWER);
	    } else {
	    	$resultArray = [];
	    }
        //echo "<pre>RESULTS<br>"; print_r($resultArray);echo "</pre>";
	    
//	    if (is_array($DetailsOutput)) {
//	    	$DetailsOutput = array_change_key_case($DetailsOutput, CASE_LOWER);
//	    }
        //echo "<pre>DETAILS OUTPUT<br>"; print_r($DetailsOutput);echo "</pre>";

        //echo "<pre>BEFORE LABEL KEY OPERATION<br>"; print_r($this->labelOptions);echo "</pre>";           
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
				$resultLabelKey = str_replace($detailFilter, '', $labelKey);
				$resultLabelKey = str_replace($keySuffix, '', $resultLabelKey);
				//echo 'Result Label Key is ' . $resultLabelKey . '.<br>';
                if ( (strlen(trim($resultLabelKey)) > 0) ) {    
                	//echo 'Label Option Value for Key ' . $labelKey . ' is ' . $this->labelOptions[$labelKey]) . '.<br>';  
                	$labelOptionsValue = $this->labelOptions[$labelKey];
                	//echo 'Label Options Value is ' . $labelOptionsValue . '.<br>'; 
	               	if ( (array_key_exists($labelKey, $this->labelOptions)) && (strlen($labelOptionsValue) > 0) ) {
	               		$resultLabels[$resultLabelKey] = $this->labelOptions[$labelKey];
	               	} elseif ( (array_key_exists($resultLabelKey, $ResultDetails)) && (strlen($ResultDetails[$resultLabelKey]) > 0) )  {
//	               	} elseif ( (array_key_exists($resultLabelKey, $DetailsOutput)) && (strlen($this->$DetailsOutput[$resultLabelKey]) > 0) )  {
	               		$resultLabels[$resultLabelKey] = $ResultDetails[$resultLabelKey];
//	               		$resultLabels[$resultLabelKey] = $DetailsOutput[$resultLabelKey];
	               	} else {
	               		$resultLabels[$resultLabelKey] = constant('EMPTY_VALUE');
	               	}		
				}
			}
        } else {
            $resultLabels = $ResultDetails;         
//            $resultLabels = $DetailsOutput;         
        }			
        	
//            foreach ($this->labelOptions as $key => $value) {
//                $label_key = strtolower(substr($key, 6)); /* Convery Keys to Lower Case */
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
//    	                	$resultLabels[$labelKey] = $value;
//    	                } elseif (array_key_exists($labelKey, $DetailsOutput))  {
//    	                	$resultLabels[$labelKey] = $DetailsOutput[$labelKey];
//    	                } else {
//    	                	$resultLabels[$labelKey] = constant('EMPTY_VALUE');
//    	                }
//    	            }
//                } 
                
                
//                if ( (strpos($key, $keySuffix)) && (!strpos($key, 'filter_')) ) {
//                    $label_key = str_replace($keySuffix, '', $label_key);  
//                    echo 'Processing Label Option ' . $key . ' with Value ' . $value . ' as Label Key ' . $label_key . '.<br>';         
//                    if (strlen(trim($value)) > 0) {     
//                    	$result_labels[$label_key] = $value;
//                    } elseif (array_key_exists($label_key, $DetailsOutput))  {
//                    	$result_labels[$label_key] = $DetailsOutput[$label_key];
//                    } else {
//                    	$result_labels[$label_key] = constant('EMPTY_VALUE');
//                    }
//                } 
//            }

        //echo "<pre>RESULT LABELS<br>"; print_r($resultLabels);echo "</pre>";
        //echo "<pre>RESULT DETAILS<br>"; print_r($ResultDetails);echo "</pre>";
        //only include those that are set to be shown by admin settings or by shortcode override
        //get all keys of returned result and put in array
//        $keyword_replace = [];
//        $keyword_replace_values = [];
        $name = constant('PETPOINT_NAME');
        $animalname = constant('PETPOINT_NAME_ANIMAL');
//        $counterKW = 0;
		$animalDetails = [];
		if ($resultCount > 0) {
	        foreach ($ResultDetails as $key => $item) {
	        	//echo 'Processing Key ' . $key . ' with Value ' . $item . '.<br>';
	        	if ( (array_key_exists($key, $resultLabels)) && (array_key_exists($key, $resultArray)) ) {
	        		//echo 'Result Label and Details Item Exist.<br>'; 
	        		if (empty($resultArray[$key])) {
	        			$resultArray[$key] = constant('EMPTY_VALUE');
	        		}
		        	$animalDetails[$key]['label'] = $resultLabels[$key] . ': ';
		        	if (is_array($resultArray[$key])) {
	            		$animalDetails[$key]['value'] = $resultArray[$key];
	            	} else {
	            		$animalDetails[$key]['value'] = ucwords($resultArray[$key]);
	            	}
	            	if (!is_array($resultArray[$key])) {
	            	    if ( (strlen(preg_replace('/\s+/', '', $resultArray[$key])) == 0) ) {
	            	        $animalDetails[$key]['value'] = constant('EMPTY_VALUE');
	            	    } else {
	            	        $animalDetails[$key]['value'] = ucwords($resultArray[$key]);
	            	    }
	            	} else {
	            	    if (empty($resultArray[$key])) {
	            	        $animalDetails[$key]['value'] = constant('EMPTY_VALUE');
	            	    } else {
		        			if (is_array($resultArray[$key])) {
	            	        	$animalDetails[$key]['value'] = $resultArray[$key];
	            	        } else {
	            	        	$animalDetails[$key]['value'] = ucwords($resultArray[$key]);
	            	        }
	            	    }
	            	}
	            	/* RMB - Remove Time from Date Fields */
	            	if (str_contains($key, 'date')) {
	            	    $animalDetails[$key]['value'] = date("m-d-Y",strtotime($resultArray[$key]));
	            	}
	            }    
	            //echo 'Animal Detail Key is ' . $key . ' with Label ' . $animalDetails[$key]['label'] . ' and Value ' . $animalDetails[$key]['value'] . '.<br>';
	            //$animalDetails[$key]['value'] = $this->convertAge($resultArray[$key]);
	            //for age compute in years and months
	            if ( ($key == constant('PETPOINT_AGE')) && (array_key_exists($key, $resultArray)) ) {
	            	$ageMonths = (int)$resultArray[$key];
	               	//echo 'Processing Age with Value ' . $resultArray[$key] . '.<br>';
	               	if ( ($ageMonths > 0) && (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//	               		$birthDate = date_create(date('m/d/Y')); 
//	               		$birthDate = date_sub($birthDate, date_interval_create_from_date_string($resultArray[$key] . ' months'));
//	               		echo 'Calculated Birth Date is ' . $birthDate->format('m/d/Y') . '.<br>';
//						//$birthDate = date_create(str_replace('-', '/', $resultArray[$animalKey]));
		               	$animalDetails[$key]['value'] = $this->allAPIFunction->ageInYears($ageMonths);
//		               	$DetailsOutput[$key]['value'] = $this->allAPIFunction->ageInYears($birthDate);
				     } elseif ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) {
				       	$animalDetails[$key]['value'] = constant('EMPTY_VALUE');
				     } else {
				       	$animalDetails[$key]['value'] = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">' . constant('PETPOINT_AGE') . '</a>';
//				       	$animalDetails[$key]['value'] = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required';
				     }	
	            }
	            
//	            if ($key == constant('ANIMALSFIRST_DATE_BIRTH')) {
//	            	//echo constant('ANIMALSFIRST_DATE_BIRTH') . ' Value is ' . $resultArray[$key] . '.<br>';
//                	if ( ($animalDetails[$key]['value'] != constant('EMPTY_VALUE')) && (strlen(trim($resultArray[$key])) > 0) ) {
//                	if ( ($animalDetails[$key]['value'] != constant('EMPTY_VALUE')) (!empty($animalDetails[$key]['value'])) ) {
//                		$birthDate = date_create($resultArray[$key]);
//			            $today = date_create("now");
//						$birthDate = date_create($animalDetails[$key]['value']);
//						$birthDate = date_create(str_replace('-', '/', $resultArray[$key]));
//			            $interval = date_diff($today, $birthDate);
//			            $months = ($interval->y * 12) + $interval->m;
//			            //echo 'Animal Age in Months = ' . $months . '<br>';                    
//			            if ( (array_key_exists('age_in_years', $this->generalOptions)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//			            	$animalDetails[$key]['value'] = $this->allAPIFunction->ageInYears($birthDate);
//			              	//echo 'Processing Birth Date<br>';
//			                if ($months < 12) {
//								//echo 'Less Than 12 Month<br>';
//			                    $animalDetails[$key]['value'] = $months . ' Month(s)';
//			                } else {
//			                 	//echo 'Over 12 Months<br>';
//			                    $animalDetails[$key]['value'] = round($months / 12) . ' Year(s)';
//			                }
//			            }
//		            }
//				}

                if ($key == constant('ANIMALSFIRST_LOCATION')) {
          			//echo 'Result Location Value is ' . $resultArray[$key] . '.<br>';
                	if (array_key_exists($key, $resultArray)) {               	
                    	$locationValue = $resultArray[$key];
                    	$animalDetails[$key]['value'] = $this->allAPIFunction->locationLabel($this->locationFilterArray, $this->locationFilterOther, $locationValue);
						//echo 'Location Value of ' . $animalDetails[$key]['value'] . '.<br>';	               	                     	
               		}
               	} 
	        }
		}

		//echo '<pre>KEYWORD REPLACE<br>'; print_r($keyword_replace); echo '</pre>';
		//echo '<pre>KEYWORD REPLACE VALUES<br>'; print_r($keyword_replace_values); echo '</pre>';
        //echo "<pre>API ANIMAL DETAILS<br>"; print_r($animalDetails);echo "</pre>";        
		/* Find Description Array Key */
        if ( (array_key_exists(constant('ANIMALSFIRST_DESCRIPTION'), $animalDetails)) && ($animalDetails[constant('ANIMALSFIRST_DESCRIPTION')]['value'] == constant('EMPTY_VALUE')) ){
          	$animalDescription = $this->allAPIFunction->replaceDetailShortcodes($ResultDetails, $animalDetails, $this->generalOptions['default_description']);            
            //echo '<br>' . 'Animal Description'. '<br>';
            //echo $animalDescription . '<br>';
            $animalDetails[constant('ANIMALSFIRST_DESCRIPTION')]['value'] = $animalDescription;
            //echo 'Completed Value Reassign';
        }
		//echo '<pre>Animal Description from Shortcode Function<br>'; echo($shortcodeDescription); echo '</pre>';
        
        $showItems = array_keys($animalDetails);

        //include the photos
        if (array_key_exists(constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_thumbs_max', $this->generalOptions)) {
        	$maxThumbs = intval($this->generalOptions[constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_thumbs_max']);
        } else {
        	$maxThumbs = 3;
        }
		//echo 'Max Thumbs is ' . $maxThumbs . '.<br>';        
        
        //echo '<pre>RESULT ARRAY<br>'; print_r($resultArray); echo '</pre>';
		if ( is_int($maxThumbs) ) {        
	        $thumb = 1;        
	        if ($callFunc == constant('ADOPT_METHODTYPE_PETPOINT') . 'Details') {
	        	while ($thumb <= $maxThumbs) {
	        		$thumbKey = constant('PETPOINT_PHOTO') . $thumb;
	        		//echo 'Processing Thumb Key ' . $thumbKey . '<br>';
			        if (!empty($resultArray[$thumbKey])) {
		                $animalDetails[constant('PETPOINT_PHOTO')][] = $resultArray[$thumbKey];
	        			//echo 'Added Thumb Key ' . $thumbKey . ' to AnimalDetails Array.<br>';
		            }
	       			$thumb = $thumb + 1;	            
		        }
	            if (!empty($resultArray[constant('PETPOINT_VIDEO')])) {
	                $animalDetails[constant('PETPOINT_VIDEO')] = $resultArray[constant('PETPOINT_VIDEO')];
	            } else {
	                $animalDetails[constant('PETPOINT_VIDEO')] = '';
	            }
	        } else {
	            if (!empty($resultArray[constant('PETPOINT_PHOTO')])) {
	            	//echo 'Added 1st Thumb<br>';
	                $animalDetails[constant('PETPOINT_PHOTO')][] = $resultArray[constant('PETPOINT_PHOTO')];
	                $maxThumbs = $maxThumbs - 1;
	                //echo 'Decremented Max Thumbs to ' . $maxThumbs . '<br>';
	            }
	        	while ($thumb <= $maxThumbs) {
	        		$thumbKey = constant('PETPOINT_PHOTO') . $thumb;
	        		//echo 'Processing Thumb Key ' . $thumbKey . '<br>';
			        if (!empty($resultArray[$thumbKey])) {
		                $animalDetails[constant('PETPOINT_PHOTO')][] = $resultArray[$thumbKey];
	        			//echo 'Added Thumb Key ' . $thumbKey . ' to AnimalDetails Array.<br>';
		            }
	       			$thumb = $thumb + 1;
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
        $templateDir = constant('TEMPLATES_DIR') . '/' . constant('PETPOINT_DIR') . '/';
//        if($this->integrationPartner == constant('PETPOINT')){
//            $templateDir = $templateDir . constant('PETPOINT_DIR') . '/';
//        }
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
	                if ($levelsSuffix == constant('ADOPT_METHODTYPE_PETPOINT')) { 
	                    $apiMethod = 'AdoptableDetails';
	                } else {
       			        $apiMethod = $callFunc;				
       			    }
					//echo 'Levels Suffix is ' . $levelsSuffix . '.<br>';

//			        if (strpos($callFunc, constant('FOUND_METHODTYPE_PETPOINT')) == 0) {
//			        if ($callFunc == constant('FOUND_METHODTYPE_PETPOINT') . 'Search') {
//			            $levelsSuffix = constant('FOUND_METHODTYPE_PETPOINT');
//			        } elseif (strpos($callFunc, constant('LOST_METHODTYPE_PETPOINT')) == 0) {
//			        } elseif ($callFunc == constant('LOST_METHODTYPE_PETPOINT') . 'Search') {
//		                    $levelsSuffix = constant('LOST_METHODTYPE_PETPOINT');
//	                } else {
//	                    $levelsSuffix = constant('ADOPT_METHODTYPE_PETPOINT'); 
//	                    $apiMethod = 'AdoptableDetails';
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
//				            $details = []; /* RMB */                   
//			                $queryString = "";
			                $queryString = $this->baseURL . $apiMethod . '?authKey=' . $this->apiKey;      //Initial URL build
			                $queryString = $queryString . '&' . str_replace(constant('PETPOINT_ID'), strtoupper(constant('PETPOINT_ID')), constant('PETPOINT_ID_ANIMAL')) . '=' . $animalIDIN;                //AnimalID to display
//			                $queryString = "$queryString&animalID=$animalIDIN";                //AnimalID to display
							//echo 'Fetching Data with URL<br>' . $queryString . '<br>';
			                $animalDetails = $this->fetch_pp_data($queryString, 'singleDetail', $item);
			                //echo '<pre>ANIMALS DETAILS<br>'; print_r($animalDetails); echo '</pre>';
			                // $details = []; - RMB
			                if (is_array($animalDetails)) {
//		                		$animalDetails = $animalDetails;
						        foreach ($animalDetails as $key => $animalitem) {
						            $details[strtolower($key)] = $animalitem;
								}		                		
			               	} else {
			               		return constant('ERROR');
			               	}		                
                
			                if ($item == constant('PETPOINT_LOCATION')) {
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
        	return null;
//        	return constant('ERROR');
        }
    }

    private function searchFilters($callFunc, $details) {
        //$override_search = [];
        if (is_array($details)) {
            $details = array_change_key_case($details, CASE_LOWER);
        }
        /*
         * $this->FilterAdminOptions = Selected Adopt filters to use on admin
         * $filterValues = values of all available filters as array;
        */

        $filterReturn = [];  
        
//        if ($callFunc == 'adoptSearch') {
//            $callMethod = 'adopt';
//            //echo '<pre>'; print_r(Pet_Match_Pro_PP_Options::$adoptSearchFilter); echo '</pre>';
//        } elseif ($callFunc == 'lostSearch') {
//            $callMethod = 'lost';
//        } else { //found search
//            $callMethod = 'found';            
//        }       
		$methodParms = $this->allAPIFunction->callMethod_Parameters($callFunc, $details);
		//echo '<pre>Method Parameters<br>'; print_r($methodParms); echo '</pre>';	
		if (array_key_exists('method', $methodParms)) {
			$this->methodValue = $methodParms['method'];
		}
      	$callMethod = $this->methodValue;	
        //echo 'SearchFilters Call Method is ' . $callMethod . '.<br>';		

        //echo '<pre>FILTER ADMIN OPTIONS<br>'; print_r($this->FilterAdminOptions); echo '</pre>';
        $sortKey = $callMethod . '_search_' . constant('PETPOINT_ORDERBY');        
        //echo 'Sort Key is ' . $sortKey . '.<br>';
        
        if ( (is_array($this->FilterAdminOptions)) && (array_key_exists($sortKey, $this->FilterAdminOptions)) ) {
            $this->PMPSortOptions = $this->FilterAdminOptions[$sortKey];  
        } else {
            $this->PMPSortOptions = array(constant('ANIMALSFIRST_NAME') => ucfirst(constant('ANIMALSFIRST_NAME')));
        }

//        if (array_key_exists('adopt_search_sort', $this->FilterAdminOptions)) {
//            $this->PMPAdoptSortOptions = $this->FilterAdminOptions['adopt_search_sort'];  
//        } else {
//            $this->PMPAdoptSortOptions = array('Name' => 'Name');
//        }
//        if ($this->PMPLicenseTypeID > constant('FREE_LEVEL')) {
//            $this->PMPLostSortOptions = $this->FilterAdminOptions['lost_search_sort'];    
//            $this->PMPFoundSortOptions = $this->FilterAdminOptions['found_search_sort'];  
//        }
        
        $filterOptionsFE = $this->adminFunction->Search_Filter_Values($callMethod, $this->PMPLicenseTypeID);        
        if ( is_array($filterOptionsFE) ) {
            $filterOptions  = array_keys($filterOptionsFE);
        } else {
            $filterOptions  = null;
        }
        
        $filterValues = $this->adminFunction->Search_Filter_Values($callMethod, $this->PMPLicenseTypeID);        
        //echo '<pre>FILTER OPTIONS BEFORE PROCESSING '; print_r($filterOptions); echo '</pre>';  
        //echo '<pre>FILTER VALUES BEFORE PROCESSING '; print_r($filterValues); echo '</pre>';  
        //echo '<pre>SORT OPTIONS BEFORE PROCESSING '; print_r($sortOptions); echo '</pre>';    
        
        /* Obtain Custom Search Labels for Breeds */
//        $labelOptions = get_option('pet-match-pro-label-options');
        $valuesFile = 'pmp-field-values-' . $callMethod . '.php';
        //$partialsDir = '/' . constant('PETPOINT_DIR') . '/' . constant('PETPOINT_PARTIALS_DIR') . '/';
        $valuesFile = $this->partialsDir . $valuesFile;
        //$valuesFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . $partialsDir . $valuesFile;
        require($valuesFile);
        $fieldValueArrayName = 'pmpFieldValues' . ucwords($callMethod);
        $fieldValueArray = $$fieldValueArrayName;
        //echo '<pre>FIELD VALUES<br>'; print_r($fieldValueArray); echo '</pre>';
        
        $primaryBreedKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_BREED_PRIMARY') . '_' . $callMethod; 
        //echo 'Primary Breed Key = ' . $primaryBreedKey . '<br>';
        $secondaryBreedKey = constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_BREED_SECONDARY') . '_' . $callMethod;
        //echo 'Secondary Breed Key = ' . $secondaryBreedKey . '<br>';

		if ($callMethod == constant('ADOPT_METHODTYPE_PETPOINT')) {
	        if ( is_array($this->labelOptions) ) {                            
	            if (array_key_exists($primaryBreedKey, $this->labelOptions)) {
	            	if (strlen(trim($this->labelOptions[$primaryBreedKey])) > 0) {
	                	$primaryBreedLabel = $this->labelOptions[$primaryBreedKey];
	                } else {
	                	$primaryBreedLabel = $fieldValueArray[$primaryBreedKey];	                
	                }
	            } else {
	                $primaryBreedLabel = $fieldValueArray[$primaryBreedKey];
	            }
	            if (array_key_exists($secondaryBreedKey, $this->labelOptions)) {
	            	if (strlen(trim($this->labelOptions[$secondaryBreedKey])) > 0) {
	                	$secondaryBreedLabel = $this->labelOptions[$secondaryBreedKey];
	                } else {
	                	$secondaryBreedLabel = $fieldValueArray[$secondaryBreedKey];
	                }
	            } else {
	                $secondaryBreedLabel = $fieldValueArray[$secondaryBreedKey];
	            }
	        } else {
	            $primaryBreedLabel = $fieldValueArray[$primaryBreedKey];   
	            $secondaryBreedLabel = $fieldValueArray[$secondaryBreedKey];	                 
	        }	        
	    }

        $valuesFile = 'pmp-field-values.php';
        $valuesFile = $this->partialsDir . $valuesFile;
        require($valuesFile);

		//echo '<pre>DETAILS<br>'; print_r($details); echo '</pre>';
        if ( (is_array($details)) && ($callMethod == constant('ADOPT_METHODTYPE_PETPOINT')) ) {
            //use cat or dog species if defined
            if ( (array_key_exists('species', $details)) ) {
                if ( (strtolower($details['species']) == 'dog') ) {
                    $filterValues[constant('PETPOINT_BREED_PRIMARY')][$primaryBreedLabel] = $pmpFieldValues['value_breed_dogs'];
                    $filterValues[constant('PETPOINT_BREED_SECONDARY')][$secondaryBreedLabel] = $pmpFieldValues['value_breed_dogs'];
                } elseif ( (strtolower($details['species']) == 'cat') )  {
                    $filterValues[constant('PETPOINT_BREED_PRIMARY')][$primaryBreedLabel] = $pmpFieldValues['value_breed_cats'];
                    $filterValues[constant('PETPOINT_BREED_SECONDARY')][$secondaryBreedLabel] = $pmpFieldValues['value_breed_cats'];
                }
            }
            
            if ( (array_key_exists('speciesid', $details)) ) {
                if ( (strtolower($details['speciesid']) == '1') ) {
                    $filterValues[constant('PETPOINT_BREED_PRIMARY')][$primaryBreedLabel] = $pmpFieldValues['value_breed_dogs'];
                    $filterValues[constant('PETPOINT_BREED_SECONDARY')][$secondaryBreedLabel] = $pmpFieldValues['value_breed_dogs'];
                } elseif ( (strtolower($details['speciesid']) == '2') )  {
                    $filterValues[constant('PETPOINT_BREED_PRIMARY')][$primaryBreedLabel] = $pmpFieldValues['value_breed_cats'];
                    $filterValues[constant('PETPOINT_BREED_SECONDARY')][$secondaryBreedLabel] = $pmpFieldValues['value_breed_cats'];
                }
            }
        }

        //remove form filters if defined in shortcode
       	$filterAdminOptionKey = $this->methodValue . '_search_criteria';                    
        if (is_array($details)) {
//            //use cat or dog species if defined
//            if ( (array_key_exists('speciesid', $details)) && ($callMethod == 'adopt') ) {
//                if (strtolower($details['speciesid']) == 'dog') {
//                    $filterValues[constant('PETPOINT_BREED_PRIMARY')][$primaryBreedLabel] = Pet_Match_Pro_PP_Options::$dogBreeds;
//                    $filterValues[constant('PETPOINT_BREED_SECONDARY')][$secondaryBreedLabel] = Pet_Match_Pro_PP_Options::$dogBreeds; /* RMB - to Default Label Value */
//                } elseif (strtolower($details['speciesid']) == 'cat') {
//                    $filterValues[constant('PETPOINT_BREED_PRIMARY')][$primaryBreedLabel] = Pet_Match_Pro_PP_Options::$catBreeds; /* RMB - to Default Label Value */
//                    $filterValues[constant('PETPOINT_BREED_SECONDARY')][$secondaryBreedLabel] = Pet_Match_Pro_PP_Options::$catBreeds; /* RMB - to Default Label Value */
//                }
//            }
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
        //echo '<pre>Filter Options After Override '; print_r($filterOptions); echo '</pre>'; 
        return (array('filterOptions' => $filterOptions, 'filterValues' => $filterValues));
    }
}