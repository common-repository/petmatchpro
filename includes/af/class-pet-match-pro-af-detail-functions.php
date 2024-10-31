<?php
if(!class_exists("PetMatchProAnimalDetailFunctions")) {

	class PetMatchProAnimalDetailFunctions {
	
		private $partialsDir;
		private $imagesPath;
			
		function __construct() {
			$this->partialsDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('ANIMALSFIRST_DIR') . '/' . constant('PARTIALS_DIR') . '/';
	        $this->imagesPath = plugin_dir_url(__DIR__) . constant('IMAGES_DIR') . '/';
	        //echo 'Images Path = ' . $this->imagesPath . '<br>';			
		}

		/* Function to Return the Fields Displayed in Animal Detail Pages */
		function Animal_Details($method, $PMPLicenseTypeID, $useScope) {
	       	//echo "License Type ID = " . $PMPLicenseTypeID . '<br>';
	       	if ( $PMPLicenseTypeID == 0 ) {
	       		$PMPLicenseTypeID = constant('FREE_LEVEL');
	       	}
	       	//echo "License Type ID = " . $PMPLicenseTypeID . '<br>';

	       	$methodLower = strtolower($method);
	       	$methodUpper = strtoupper($method);
	       	$methodMixed = ucwords($method);
	
			/* Get Field Values */
		    $valuesFile = 'pmp-field-values-' . $methodLower . '.php';
		   	$requireFile = $this->partialsDir . $valuesFile;
		   	//echo 'Require File = ' . $requireFile . '<br>';
		    require $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . $methodMixed;
		    $fieldValueArray = $$fieldValueArrayName;
		    //echo '<pre> ADMIN ' . $methodUpper . ' VALUES '; print_r($fieldValueArray); echo '</pre>';
		
			//$PMPLicenseTypeID = 3; 
				
	        /* Obtain Custom Search Labels */
	        $labelOptions = get_option('pet-match-pro-label-options');
	       	//echo '<pre>ADMIN LABEL VALUES - PRE PROCESSING<br>'; print_r($labelOptions); echo '</pre>';
	
			$AnimalDetails = [];
			$methodSuffix = '_' . $methodLower;
			$filterPrefix = constant('LABEL_PREFIX_ANIMAL_DETAIL');
			foreach ($fieldValueArray as $key => $value) {
				if ( (is_numeric(strpos($key, $filterPrefix))) ) {
	           		$arrayKey = str_replace($filterPrefix, '', $key);
	           		$arrayKey = str_replace($methodSuffix, '', $arrayKey);
	           		//echo 'Processing Animal Detail Label ' . $arrayKey . '.<br>';
	           		$labelKey = 'label_' . $arrayKey . $methodSuffix;
	           		if ( is_array($labelOptions) ) {          		
	           			if ( (array_key_exists($labelKey, $labelOptions)) ) {
	           				if (strlen(trim($labelOptions[$labelKey])) > 0) {
	           					$fieldValue = $labelOptions[$labelKey];
	           				} else {
		           				$fieldValue = $value;	           				
	           				}
	           			} else {
	           				$fieldValue = $value;
	           			} 
	           		} else {
	           			$fieldValue = $value;
	           		} 
	           		//echo 'Setting Value Key ' . $fieldValue . '.<br>';
	           		$AnimalDetails[$arrayKey] = $fieldValue;
				}
			}
	
	       	//echo '<pre>' . $methodUpper . ' ANIMAL DETAIL BEFORE LICENSE PROCESSING<br>'; print_r($AnimalDetails); echo '</pre>';
	
			/* Get Field Visibility Levels by License Type */
		    $levelsFile = 'pmp-field-levels-' . $methodLower . '.php';
		   	$requireFile = $this->partialsDir . $levelsFile;
		    require $requireFile;
		    $fieldLevelArrayName = 'pmpFieldLevels' . $methodMixed;
		    $fieldLevelArray = $$fieldLevelArrayName;
	
		    //echo '<pre>' . $methodUpper . ' LEVEL VALUES '; print_r($fieldLevelArray); echo '</pre>';
				
	        //echo '<pre>' . $methodUpper . ' ANIMAL DETAILS<br>'; print_r($AnimalDetails); echo '</pre>';
			/* Remove Animal Details Based on License Type */
			foreach ($fieldLevelArray as $levelKey => $levelValue) {
			//echo 'Search Detail Key = ' . $levelKey . '<br>';
				if ( str_contains($levelKey, constant('LEVEL_PREFIX_ANIMAL_DETAIL')) ) {
					//echo 'Processing Animal Detail Key = ' . $levelKey . ' with Value = ' . $levelValue . '<br>';
					$fieldName = str_replace(constant('LEVEL_PREFIX_ANIMAL_DETAIL'), '', $levelKey);
					$fieldName = str_replace($methodSuffix, '', $fieldName);
					if ( (array_key_exists($fieldName, $AnimalDetails)) && ($PMPLicenseTypeID > $levelValue) ) {	
						//echo 'Preparing to Remove Animal Detail Key = ' . $fieldName . '<br>';
						unset($AnimalDetails[$fieldName]);
					}
				}
			}
			//echo '<pre>Animal Details AFTER MEDIA Processing<br>'; print_r($AnimalDetails); echo '</pre>';		
			return $AnimalDetails;
		}     
		
		/* Function to Return the Field Labels for Animal Details */
		function Animal_Labels($detailFields, $method, $useScope) {
		    //echo '<pre> LABEL FUNCTION CALLED WITH DETAIL FIELDS <br>'; print_r($detailFields); echo '</pre>';
	       	//echo "Method = " . $method . '<br>';
	       	//echo "Scope = " . $useScope . '<br>';

	       	$methodLower = strtolower($method);
	       	$methodUpper = strtoupper($method);
	       	$methodMixed = ucwords($method);
	
			/* Get Field Values */
		    $valuesFile = 'pmp-field-values-' . $methodLower . '.php';
		   	$requireFile = $this->partialsDir . $valuesFile;
		    require $requireFile;
		    $fieldValueArrayName = 'pmpFieldValues' . $methodMixed;
		    $fieldValueArray = $$fieldValueArrayName;
		    //echo '<pre> ADMIN ' . $methodUpper . ' VALUES '; print_r($fieldValueArray); echo '</pre>';
		
	        /* Obtain Custom Search Labels */
	        $labelOptions = get_option('pet-match-pro-label-options');
	       	//echo '<pre>ADMIN LABEL VALUES - PRE PROCESSING<br>'; print_r($labelOptions); echo '</pre>';
	
			$animalLabels = array();
			$methodSuffix = '_' . $methodLower;
			if ( strtoupper($useScope) == 'search' ) {
				$labelPrefix = constant('LABEL_PREFIX_SEARCH_RESULT');
			} else {
				$labelPrefix = constant('LABEL_PREFIX_ANIMAL_DETAIL');
			}
			foreach ($detailFields as $key => $value) {
           		$labelKey = 'label_' . $key . $methodSuffix;
           		//echo 'Processing Label Key ' . $labelKey . '<br>';
           		
          		if ( is_array($labelOptions) ) {          		           		
	           		if ( (array_key_exists($labelKey, $labelOptions)) ) {
	           			if (strlen(trim($labelOptions[$labelKey])) > 0) {
	           				$labelValue = $labelOptions[$labelKey];
	           			} else {
			           		$labelKey = $labelPrefix . $key . $methodSuffix;           		
		           			$labelValue = $fieldValueArray[$labelKey];	           			
	           			}
	           		} else {
		           		$labelKey = $labelPrefix . $key . $methodSuffix;           		
	           			$labelValue = $fieldValueArray[$labelKey];
	           		} 
	           	} else {
	           		$labelKey = $labelPrefix . $ky . $methodSuffix;           		
        			$labelValue = $fieldValueArray[$labelKey];
           		} 
           		//echo 'Setting Value Key ' . $fieldValue . '<br>';
           		$animalLabels[$key] = $labelValue;
			}
			//echo '<pre>Animal Labels<br>'; print_r($AnimalLabels); echo '</pre>';		
			return $animalLabels;
		}    
		
		/* Function to Display Pet Icons */		
		function display_pet_icons($resultArray, $animalName, $maxIcons) {
			//echo '<pre>ICON FUNCTION CALLED WITH<br>'; print_r($resultArray); echo '</pre>';
			//echo 'Icon Function Called with Max Icons = ' . $maxIcons . '<br>';
			$iconString 	= '';
			//$uploads 		= wp_get_upload_dir();
			//$this->imagesPath 	= $uploads["baseurl"];
			
			$iconCount 	= 0;
			if ( (array_key_exists(constant('ANIMALSFIRST_GENDER'), $resultArray)) ) {
				if ( (is_array($resultArray[constant('ANIMALSFIRST_GENDER')])) && (array_key_exists('value', $resultArray[constant('ANIMALSFIRST_GENDER')])) ) {
					if ( ($resultArray[constant('ANIMALSFIRST_GENDER')]['value'] == "Female") ) {
						$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_female.png' . '" alt="Female Icon" title="' . $animalName . ' is a Girl" />';
					} elseif ( ($resultArray[constant('ANIMALSFIRST_GENDER')]['value'] == "Male") ){
						$iconString = $iconString . '<img src="' . $this->imagesPath . 'icon_male.png' . '" alt="Male Icon" title="' . $animalName . ' is a Boy" />';
					}
					$iconCount = $iconCount + 1;
					//echo 'Incremented Icon Count for Gender.<br>';				
				} else {
					if (!is_array($resultArray[constant('ANIMALSFIRST_GENDER')])) {
						if ( ($resultArray[constant('ANIMALSFIRST_GENDER')] == "Female") ) {
							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_female.png' . '" alt="Female Icon" title="' . $animalName . ' is a Girl" />';
						} elseif ( ($resultArray[constant('ANIMALSFIRST_GENDER')] == "Male") ){
							$iconString = $iconString . '<img src="' . $this->imagesPath . 'icon_male.png' . '" alt="Male Icon" title="' . $animalName . ' is a Boy" />';
						}
						$iconCount = $iconCount + 1;
						//echo 'Incremented Icon Count for Gender.<br>';				
					}
				}
			}
			if ( (array_key_exists(constant('ANIMALSFIRST_AGE'), $resultArray)) ) {
				if ( (is_array($resultArray[constant('ANIMALSFIRST_AGE')])) && (array_key_exists('value', $resultArray[constant('ANIMALSFIRST_AGE')])) ) {
					if ($resultArray[constant('ANIMALSFIRST_AGE')]['value'] != constant('EMPTY_VALUE')) {		
						$ageIcon = strtolower($resultArray[constant('ANIMALSFIRST_AGE')]['value']);
						$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_' . $ageIcon . '.jpg' . '" alt="General Age Icon" title="' . $animalName . ' is ' . ucfirst($ageIcon) . '" />';
						$iconCount = $iconCount + 1;	
						//echo 'Incremented Icon Count for Age.<br>';						
					}
				} else {
					if ( (array_key_exists(constant('ANIMALSFIRST_AGE'), $resultArray)) && (!is_array($resultArray[constant('ANIMALSFIRST_AGE')])) ) {
						if ($resultArray[constant('ANIMALSFIRST_AGE')] != constant('EMPTY_VALUE')) {
							$ageIcon = strtolower($resultArray[constant('ANIMALSFIRST_AGE')]);
							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_' . $ageIcon . '.jpg' . '" alt="General Age Icon" title="' . $animalName . ' is ' . ucfirst($ageIcon) . '" />';
							$iconCount = $iconCount + 1;	
							//echo 'Incremented Icon Count for Age.<br>';						
						}
					}
				}
			}
			if ( (array_key_exists(constant('ANIMALSFIRST_DECLAWED'), $resultArray)) ) {	
				if ($resultArray[constant('ANIMALSFIRST_DECLAWED')] != constant('EMPTY_VALUE')) {
					if ( ($resultArray[constant('ANIMALSFIRST_DECLAWED')] == "Yes") && ($resultArray[constant('ANIMALSFIRST_SPECIES')] == 'Cat') && ($iconCount < $maxIcons) ) {
						//echo 'Processing Declawed Icon with Previous Count ' . $iconCount . '<br>';
						$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_declawed.png' . '" alt="Animal is Declawed Icon" title="' . $animalName . ' is Declawed" />';
						$iconCount = $iconCount + 1;
						//echo 'Incremented Icon Count for Declaw.<br>';						
					}								
				}
				if ( (is_array($resultArray[constant('ANIMALSFIRST_DECLAWED')])) && (array_key_exists('value', $resultArray[constant('ANIMALSFIRST_DECLAWED')])) ) {								
					if ($resultArray[constant('ANIMALSFIRST_DECLAWED')]['value'] != constant('EMPTY_VALUE')) {						
						if ( ($resultArray[constant('ANIMALSFIRST_DECLAWED')]['value'] == "Yes") && ($resultArray[constant('ANIMALSFIRST_SPECIES')]['value'] == 'Cat') && ($iconCount < $maxIcons) ) {
							//echo 'Processing Declawed Icon with Previous Count ' . $iconCount . '<br>';
							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_declawed.png' . '" alt="Animal is Declawed Icon" title="' . $animalName . ' is Declawed" />';
							$iconCount = $iconCount + 1;
							//echo 'Incremented Icon Count for Declaw.<br>';													
						}								
					}
				}        
			}
			
			if ( (array_key_exists(constant('ANIMALSFIRST_VIDEOS'), $resultArray)) ) {
				$videoArray = $resultArray[constant('ANIMALSFIRST_VIDEOS')];
			} elseif (array_key_exists(constant('ANIMALSFIRST_VIDEOS'), $resultArray)) {
				$videoArray = $resultArray[constant('ANIMALSFIRST_VIDEOS')];
			}
			
			//echo 'Icon Count Before Video = ' . $iconCount . '<br>';
			//echo '<pre>VIDEO ARRAY<br>'; print_r($videoArray); echo '</pre>';
			//echo 'Is Video Array [0] Set: '; echo isset($videoArray[0]); echo '<br>';
			if (( (!empty($videoArray[0])) || (isset($videoArray)) ) && ($iconCount < $maxIcons) ) {
				//echo '<pre>Processing Video 0<br>'; print_r($videoArray[0]); echo '</pre>';										           				
				$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_video.jpg' . '" alt="Animal Has Video Icon" title="' . $animalName . ' Has a Video" />';
				$iconCount = $iconCount + 1;		
			} 	

			//echo 'Icon String Before Return' . $iconString . '<br>';
			if (!is_null($iconString)) {
				return $iconString;
			} else {
				return false;
			}
		}
	}
}
?>