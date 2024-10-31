<?php
class Pet_Match_Pro_Public {
	private $pluginName;
	private $version;
    private $PartnerAPIKey;
    private $PMP_API;
    private $integrationPartner;
    private $PMPlicense;
    private $pluginSlug;
    private $generalOptions;
    /* Lost-Found Shortcode Variable Definition */
    private $foundSearch;
    private $lostSearch;
    private $lostfoundSearch;
    private $includePath;
    private $animalID;
    
    /* To Secure Features */
    public $PMPLicenseTypeID;    
    
	public function __construct( $plugin_name, $version, $plugin_slug, $authLicense ) {
		$this->pluginName = $plugin_name;
		$this->version = $version;
        $this->pluginSlug = $plugin_slug;
       	$this->generalOptions = get_option('pet-match-pro-general-options');
       	if (is_array($this->generalOptions)) {
			if (array_key_exists('integration_partner', $this->generalOptions)) {
				$this->integrationPartner = $this->generalOptions['integration_partner'];
			} else {
				$this->integrationPartner = constant('PETPOINT');
			}
			if (array_key_exists('partner_API_key', $this->generalOptions)) {
       			$this->PartnerAPIKey = $this->generalOptions['partner_API_key'];
       		} else {
       			$this->PartnerAPIKey = '';
       		}
		} else {
			$this->integrationPartner = constant('PETPOINT');
			$this->PartnerAPIKey = '';				
		}
						
       	$this->PMPlicense = $authLicense;
       	
       	$this->PMPLicenseTypeID = (int) get_option('PMP_License_Type_ID'); /* To Secure Paid Features */
    	if ( $this->PMPLicenseTypeID == 0 ) {
    		$this->PMPLicenseTypeID = constant('FREE_LEVEL');
    	}

		if ($this->integrationPartner == constant('PETPOINT')) {
			$this->includePath = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/';
			$this->PMP_API = new Pet_Match_Pro_PP_Api($this->PartnerAPIKey, $this->PMPlicense);
			$this->animalID = 'animal' . strtoupper(constant('PETPOINT_ID'));
		} elseif ($this->integrationPartner == constant('RESCUEGROUPS')) {
			$this->includePath = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/';		
			//echo 'Partner API Key ' . $this->PartnerAPIKey . '<br>';
			$this->PMP_API = new Pet_Match_Pro_RG_Api($this->PartnerAPIKey, $this->PMPlicense);
			$this->animalID = str_replace('id', 'ID', constant('RESCUEGROUPS_ID'));				
//			$this->animalID = 'animalID';
		} elseif ($this->integrationPartner == constant('ANIMALSFIRST')) {
			$this->includePath = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/';		
			//echo 'Partner API Key ' . $this->PartnerAPIKey . '<br>';
			$this->PMP_API = new Pet_Match_Pro_AF_Api($this->PartnerAPIKey, $this->PMPlicense);	
			$this->animalID = constant('ANIMALSFIRST_ID');				
		} else {
			echo '<div id="pmp-error-integration-partner"><span class="pmp-error">' . constant('ERROR') . '</span>: No Integration Partner. Select Partner from General Tab.';
        }
	}

	/* Register Public Facing Styles & Scripts */
	public function pmp_enqueue_styles_scripts() {
		wp_enqueue_style( $this->pluginName . '-' . constant('PUBLIC_DIR'), plugin_dir_url( __FILE__ ) . constant('CSS_DIR') . '/pet-match-pro-public.css', array(), $this->version, 'all' );
		/* RMB - Check if Child Directory Exists, Otherwise Set to Plugin Default */ 
		$cssDir = '/petmatchpro/' . constant('CSS_DIR') . '/';
        //echo 'CSS Directory is ' . get_stylesheet_directory(dirname(__FILE__)) . $cssDir . '<br>';
        //echo 'Does the CSS Directory Exist? ' . is_dir(get_stylesheet_directory(dirname(__FILE__)) . $cssDir) . '<br>';
		//echo 'Does the Theme CSS File Exist? ' . file_exists(get_stylesheet_directory(dirname(__FILE__)) . $cssDir . 'pet-match-pro-styles.css') . '<br>';
		//echo 'License Type ID = ' . $this->PMPLicenseTypeID . '. Free License Type ID = ' . constant('FREE_LEVEL') . '<br>';
        if ( (is_dir(get_stylesheet_directory(dirname(__FILE__)) . $cssDir)) && (file_exists(get_stylesheet_directory(dirname(__FILE__)) . $cssDir . 'pet-match-pro-styles.css')) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) { /* RMB - Paid Feature */ 
			//echo 'Preparing to Enqueue Theme CSS Sheet<br>';
        	wp_enqueue_style( $this->pluginName . '-style', get_stylesheet_directory_uri() . $cssDir . 'pet-match-pro-styles.css', array(), $this->version, 'all' );
        } else {
            //echo 'Preparing to Enqueue Default CSS Sheet<br>';
        	wp_enqueue_style( $this->pluginName . '-style', plugin_dir_url( __FILE__ ) . constant('CSS_DIR') . '/pet-match-pro-styles.css', array(), $this->version, 'all' );
        }
	
		wp_enqueue_script( $this->pluginName, plugin_dir_url( __FILE__ ) . constant('SCRIPT_DIR') . '/pet-match-pro-public.js', array( 'jquery' ), $this->version, false );	
	}
	

	/* Define Shortcode Functions */      
	public function petmatch_adoptable_search($vars) {
		//echo '<pre>Adoptable Search Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Adoptable Search Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( !empty($firstURLParm) ) {
			if ( !str_contains($firstURLParm, constant('PAGE_BUILDER')) ) {
				$this->pmp_custom_css();  
				//echo '<pre>ADOPTABLE SEARCH SHORTCODE VARS<br>'; print_r($vars); echo '</pre>';
	            return $this->PMP_API->createSearch($vars, 'adoptSearch');
	        } else {
		        return;
		    }
		} else {
			$this->pmp_custom_css();  
			//echo '<pre>ADOPTABLE SEARCH SHORTCODE VARS<br>'; print_r($vars); echo '</pre>';
            return $this->PMP_API->createSearch($vars, 'adoptSearch');
		}			
    }
    
	public function petmatch_found_search($vars) {
		//echo '<pre>Found Search Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Found Search Shortcode Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {		
//		if ( (!empty($firstURLParm)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {		
			if ( ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//			if ( (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
				$this->pmp_custom_css();
				return $this->PMP_API->createSearch($vars, 'foundSearch');
			} else {
				return '<div id="pmp-notice-upgrade-license"><span class="pmp-error">NOTICE:</span> <a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Login</a> and upgrade your license to use this feature.</div> <!-- #pmp-notice-upgrade-license -->';
			}
		} else {
//			$this->pmp_custom_css();
			return null;
//			return $this->PMP_API->createSearch($vars, 'foundSearch');
		}
	}

    public function petmatch_lost_search($vars) {
		//echo '<pre>Lost Search Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Lost Search Shortcode Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {				
//		if ( (!empty($firstURLParm)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {				
			if (  ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//			if ( (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
				$this->pmp_custom_css();
				return $this->PMP_API->createSearch($vars, 'lostSearch');
			} else {
				return '<div id="pmp-notice-upgrade-license"><span class="pmp-error">NOTICE:</span> <a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Login</a> and upgrade your license to use this feature.</div> <!-- #pmp-notice-upgrade-license -->';
			}
		} else {
//			$this->pmp_custom_css();
			return null;
//			return $this->PMP_API->createSearch($vars, 'lostSearch');
		}
	}

	/* New Shortcode to Combine Lost & Found Search Results */
	public function petmatch_lost_found_search($vars) {
		//echo '<pre>Lost & Found Search Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Lost & Found Shortcode Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {						
//		if ( (!empty($firstURLParm)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {						
			if ( ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//			if ( (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
				$this->pmp_custom_css();
				return $this->PMP_API->createLostFoundSearch($vars);             
			} else {
				return '<div id="pmp-notice-upgrade-license"><span class="pmp-error">NOTICE:</span> <a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Login</a> and upgrade your license to use this feature.</div> <!-- #pmp-notice-upgrade-license -->';
			}
		} else {
//			$this->pmp_custom_css();
			return null;             
//			return $this->PMP_API->createLostFoundSearch($vars);             
		}
	}

	/* New Pet Search Shorcode Added with AnimalsFirst */
    public function petmatch_search($vars) {  	
		//echo '<pre>Pet Search Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Pet Search Shortcode Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {				
//		if ( (!empty($firstURLParm)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {				
			if ( ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//			if ( (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
				$this->pmp_custom_css();
				return $this->PMP_API->createSearch($vars, 'petSearch');
			} else {
				return '<div id="pmp-notice-upgrade-license"><span class="pmp-error">NOTICE:</span> <a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Login</a> and upgrade your license to use this feature.</div> <!-- #pmp-notice-upgrade-license -->';
			}
		} else {
//			$this->pmp_custom_css();
			return null;
//			return $this->PMP_API->createSearch($vars, 'petSearch');
		}
	}
    
    public function petmatch_adoptable_details($items = '') {
		$callFunc = '';
		//echo '<pre>Adoptable Details Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Adoptable Details Shortcode Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {
			$callFunc = $_GET['method'];
			if(isset($_GET[$this->animalID])) {
				$this->pmp_custom_css();
				return $this->PMP_API->createDetails($_GET[$this->animalID], $items, $callFunc);
			}
		} else {
			return null;
        }
	}

	public function petmatch_found_details($items= '') {
		//echo '<pre>Found Details Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Found Details Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {		
//		if ( (!empty($firstURLParm)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {		
			if ( ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//			if ( (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
				$this->pmp_custom_css();
				if(isset($_GET[$this->animalID])) {
					return $this->PMP_API->createDetails($_GET[$this->animalID], $items, 'foundDetails');
				}
			} else {
				return '<div id="pmp-notice-upgrade-license"><span class="pmp-error">NOTICE:</span> <a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Login</a> and upgrade your license to use this feature.</div> <!-- #pmp-notice-upgrade-license -->';
			}
		} else {
//			$this->pmp_custom_css();
//			if(isset($_GET['animalID'])) {
//				return $this->PMP_API->createDetails($_GET[$this->animalID], $items, 'foundDetails');
//			}
			return null;
		}
	}

	/* RMB - New Shortcode for Lost Details */
	public function petmatch_lost_details($items= '') {
		//echo '<pre>Lost Details Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Lost Details Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {		
//		if ( (!empty($firstURLParm)) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {		
			if ( ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
//			if ( (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
				$this->pmp_custom_css();
				if(isset($_GET[$this->animalID])) {
					return $this->PMP_API->createDetails($_GET[$this->animalID], $items, 'lostDetails');
				}
			} else {
				return '<div id="pmp-notice-upgrade-license"><span class="pmp-error">NOTICE:</span> <a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Login</a> and upgrade your license to use this feature.</div> <!-- #pmp-notice-upgrade-license -->';
			}
		} else {
//			$this->pmp_custom_css();
//			if(isset($_GET['animalID'])) {
//				return $this->PMP_API->createDetails($_GET[$this->animalID], $items, 'lostDetails');
//			}
			return null;
		}
	}

    public function petmatch_featured($vars) {
		//echo '<pre>Featured Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Featured Shortcode Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {
//		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
			if ( ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
				$this->pmp_custom_css();
				return $this->PMP_API->createSearch($vars, 'featured');
			} else {
				return '<div id="pmp-notice-upgrade-license"><span class="pmp-error">NOTICE:</span> <a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Login</a> and upgrade your license to use this feature.</div> <!-- #pmp-notice-upgrade-license -->';
			}
		} else {
			return null;
		}
	}

	public function petmatch_details($items) {
		$callFunc = '';
		//echo '<pre>Details Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Details Shortcode Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {
			$callFunc = @$_GET['method']; //  Modificed BY 15-08-2022 PROLIFIC
			//echo 'Pet Details Called with Method ' . $callFunc . '<br>';
			if(isset($_GET[$this->animalID])) {
				//$this->pmp_custom_css();
				return $this->PMP_API->createDetails($_GET[$this->animalID], $items, $callFunc);
			}
		} else {
			return null;
		}
	}

	public function PMP_detail($vars) {
		//print_r($vars);
		$callFunc = '';
		$detailResult = '';
		$detailSearch = '';
		//echo '<pre>Details Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Details Shortcode Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {
			if ( ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
				$callFunc = @$_GET['method'];   
			
				if(is_array($vars)) {
					//add shortcode defaults attr
					$vars = shortcode_atts(array(
						'case' => 'lower',
						'detail' => '',
						'replace' => ''
					), $vars);
					//echo '<pre>Revised Parms<br>'; print_r($vars); echo '</pre>';    					
					$detailSearch = $vars['detail'];
					//echo 'Detail Item is ' . $detailSearch . '.<br>';
					if (array_key_exists($this->animalID, $_GET)) {
//				if (!empty($_GET[$this->animalID])) {
						$animalID = $_GET[$this->animalID];
					} else {
						$animalID = '';
					}
					$detailResult = $this->PMP_API->animalDetail($animalID, $detailSearch, $callFunc);
//				$detailResult = $this->PMP_API->animalDetail($_GET[$this->animalID], $detailSearch, $callFunc);
					if (!is_array($detailResult)) {
						$descConstant = constant(strtoupper($this->integrationPartner) . '_DESCRIPTION');
						//echo 'Description Constant = ' . $descConstant . '.<br>';
						if ( ($detailSearch != $descConstant) ) {
							//echo 'Detail Result is ' . $detailResult . '.<br>';
							if (array_key_exists('case', $vars)) {
								if(strtolower($vars['case']) == 'upper') {
									$detailResult = @strtoupper($detailResult);
								} elseif(strtolower($vars['case']) == 'mixed') {
									$detailResult = @ucwords($detailResult);
								} else {
									$detailResult = @strtolower($detailResult);
								}
							} else {
								$detailResult = @strtolower($detailResult);
							}
							if (array_key_exists('replace', $vars)) {
								//echo 'Preparing to Replace ' . $detailResult . ' with ' . $vars['replace'] . '<br>';
								$detailResult = preg_replace('/\s+/', $vars['replace'], $detailResult);
								//echo 'New Detail Result ' . $detailResult . '<br>';
							}            
						}
					}
				} else {
					//plain shortcode
					$detailSearch = $vars;
					$detailResult = $this->PMP_API->animalDetail($_GET[$this->animalID], $detailSearch, $callFunc);
					if (!is_array($detailResult)) {
						$detailResult = strtolower($detailResult);
					}
				}
				return $detailResult;
			} else {		
				return '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">' . ucfirst($vars['detail']) . '</a> <!-- .pmp-notice-upgrade-license -->';
//				return '<div id="pmp-notice-upgrade-license"><span class="pmp-error">NOTICE:</span> <a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Login</a> and upgrade your license to use this feature.</div> <!-- #pmp-notice-upgrade-license -->';
	        }
	    } else {
	    	return null;
	    }
	}

	/* New Shortcode to Display Option Values */	        
	public function petmatch_option_value($vars){
    	$optionType = '';
        $optionArray = '';
        $optionKey = '';
        $optionValue = '';
		//echo '<pre>Options Shortcode Called with Parms<br>'; print_r($vars); echo '</pre>';    
		$urlParms = $_GET;
		//echo '<pre>Options Shortcode Called with urlParms<br>'; print_r($urlParms); echo '</pre>';
		if ( (is_array($urlParms)) && (count($urlParms) > 0) ) {
			$firstURLParm = array_key_first($urlParms);
		} else {
			$firstURLParm = constant('EMPTY_VALUE');
		}
		if ( (!empty($firstURLParm)) && (!str_contains($firstURLParm, constant('PAGE_BUILDER'))) ) {
			if(is_array($vars)) {
				//add shortcode defaults attr
				$vars = shortcode_atts(array(
					'type' => '',
					'value' => ''
					), $vars);
					//echo '<pre>'; print_r($vars); '<br></pre>';
				if ( ($vars['type']) && ($vars['value']) && ($vars['value'] != constant('DETAILS_TEMPLATE_KEY')) ) {
					/* Get Admin Tab Security Levels */
       				$levelsFile = 'pmp-option-levels.php';
       				$levelsDir = constant("PET_MATCH_PRO_PATH") . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/';        
       				$requireFile =  $levelsDir . $levelsFile;
       				//echo 'Option Levels File = ' . $requireFile . '<br>';
       				if ( (is_dir($levelsDir)) && (is_file($requireFile)) ) {
       					require($requireFile);
       					$levelKey = 'level_' . $vars['type'] . '_options';
      				  		if ( (array_key_exists($levelKey, $pmpOptionLevels)) && ($this->PMPLicenseTypeID <= $pmpOptionLevels[$levelKey]) ) {  
							$optionType = 'pet-match-pro-' . $vars['type'] . '-options';
							$optionArray = get_option($optionType);
							//print_r($optionArray);
    	           			$optionKey = $vars['value'];
    	           			//echo '<br> Option Key = ' . $optionKey . '<br>';
    	           			if (array_key_exists($optionKey, $optionArray)) {
    	           				$optionValue = $optionArray[$optionKey];
    	           			} else {
    	           				$optionValue = '';
    	           			}
    	           			//echo 'Option Value = ' . $optionValue . '<br>';
    	           			return $optionValue;
    	           		} else {
							return '<div class ="pmp-error-message"><a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Display ' . ucwords($vars['type']) . ' Settings</div>';					
    	           		}
    	           	} else {
    	           		return '<p><span class="pmp-error">ERROR: Unable to find the file ' . $levelsFile . '.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
    	           	}
    	        } else {
    	        	return '<p><span class="pmp-error">ERROR: This shortcode must be called with parameters, see the <i>Instructions</i> tab for details.</span> If the problem persists, <a class="pmp-text-link" href="' . constant('PMP_LOGIN') .'" target="_blank">login</a> to your account to request assistance.</p>';
    	        }		
			}
		}
	}

	function pmp_custom_css() {
		if (is_array($this->generalOptions)) {
			if (array_key_exists('pmp_custom_css', $this->generalOptions)) {
	        	echo '<style id="pmp-custom-css">';
	          	echo $this->generalOptions['pmp_custom_css'];
	    	    echo '</style> <!-- #pmp_custom_css -->';
			}
		}
      	return;
	}

	function PMP_modify_post($content) {
	    //$post->post_content = str_replace('Some', 'WHATEVER', $post->post_content);
        var_dump($content);
        exit;
    }
    
	public function pmp_social_share()	{
    	/* Get Field Visibility Levels */
	    $levelsFile = 'pmp-field-levels-adopt.php';
	   	$requireFile = $this->includePath . constant('PARTIALS_DIR') . '/' . $levelsFile;
	    //echo 'Require File = ' . $requireFile . '<br>';
	    require $requireFile;

		/* Determine if Social Share is Configured */
		$contactOptions = get_option('pet-match-pro-contact-options');
	    
     	if ( ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt['level_social_share_adopt']) && (array_key_exists('social_share', $contactOptions)) ) {
     		include_once ABSPATH . 'wp-admin/includes/plugin.php';
     		$socialPlugin = 'monarch/monarch.php';
			if ( is_plugin_active($socialPlugin) ) {
				$monarch = $GLOBALS['et_monarch'];
				//echo '<pre> MONARCH<br>'; print_r($monarch); echo '</pre>';
				$monarchOptions = $monarch->monarch_options;
				//echo '<pre> MONARCH OPTIONS<br>'; print_r($monarchOptions); echo '</pre>';
				return $monarch->generate_inline_icons('et_social_inline_custom');
			} else {
				$supportMsg = '<a class="pmp-text-link pmp-notice-contact-support" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Contact</a> Support to Install Feature</span>';
				return $supportMsg;
			}
		} else {
			$upgradeMsg = '<a class="pmp-text-link pmp-notice-upgrade-license" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> Required</span>';
			return $upgradeMsg;
		}
	}        
}