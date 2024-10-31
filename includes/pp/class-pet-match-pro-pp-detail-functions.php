<?php

if(!class_exists("PetMatchProAnimalDetailFunctions")) {



	class PetMatchProAnimalDetailFunctions {

	

		private $partialsDir;

		private $imagesPath;		

			

		function __construct() {

			$this->partialsDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/' . constant('PARTIALS_DIR') . '/';

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
	           		//echo 'Processing Animal Detail Label ' . $arrayKey . '<br>';
	           		$labelKey = 'label_' . $arrayKey . $methodSuffix;
	           		if (is_array($labelOptions) ) {          		
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
	           		//echo 'Setting Value Key ' . $fieldValue . '<br>';
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
			//echo '<pre>Animal Details AFTER Processing<br>'; print_r($AnimalDetails); echo '</pre>';					

			/* Remove Media Details - Displayed Automatically if They Exist */
			$generalOptions = get_option('pet-match-pro-general-options');
			if ( (is_array($generalOptions)) && (array_key_exists('integration_partner', $generalOptions)) ) {
				$integrationPartner = $generalOptions['integration_partner'];
			}
			if ( $integrationPartner == constant('PETPOINT') ) {
				$pointMedia = constant('PETPOINT_MEDIA');
				$ppMedia = explode(',', $pointMedia);
				//echo '<pre>PETPOINT MEDIA<br>'; print_r($ppMedia); echo '</pre>';
				foreach ( $ppMedia as $key => $value ) {
					//echo 'Processing Media Key ' . $key . ' with Value ' . $value . '<br>';
					if ( array_key_exists($value, $AnimalDetails) ) {
						unset($AnimalDetails[$value]);
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

			$animalLabels = [];
			$methodSuffix = '_' . $methodLower;
			if ( strtoupper($useScope) == 'search' ) {
				$labelPrefix = constant('LABEL_PREFIX_SEARCH_RESULT');
			} else {
				$labelPrefix = constant('LABEL_PREFIX_ANIMAL_DETAIL');
			}

			foreach ($detailFields as $key => $value) {
				if ( strtolower($key) == 'name' ) {
					$alteredKey = 'animalname';
				} else {
					$alteredKey = $key;
				}
           		$labelKey = 'label_' . $alteredKey . $methodSuffix;
           		//echo 'Processing Label Key ' . $labelKey . '<br>';
           		
          		if ( is_array($labelOptions) ) {          		           		
	           		if ( (array_key_exists($labelKey, $labelOptions)) ) {
	           			if (strlen(trim($labelOptions[$labelKey])) > 0) {
	           				$labelValue = $labelOptions[$labelKey];
	           			} else {
		           			$labelKey = $labelPrefix . $alteredKey . $methodSuffix;   
		           			$labelValue = $fieldValueArray[$labelKey];
	           			}
	           		} else {
		           		$labelKey = $labelPrefix . $alteredKey . $methodSuffix;   
		           		if (array_key_exists($labelKey, $fieldValueArray)) {
		           			$labelValue = $fieldValueArray[$labelKey];
		           		} else {
		           			$labelValue = constant('ERROR');
		           		}
	           		} 
	           	} else {
	           		$labelKey = $labelPrefix . $alteredKey . $methodSuffix; 
		           	if (array_key_exists($labelKey, $fieldValueArray)) {
		           		$labelValue = $fieldValueArray[$labelKey];
	           		} else {
	           			$labelValue = constant('ERROR');
	           		}
           		} 
           		//echo 'Setting Value Key ' . $labelValue . '<br>';
           		$animalLabels[$key] = $labelValue;
			}
			//echo '<pre>Animal Labels<br>'; print_r($animalLabels); echo '</pre>';		

			return $animalLabels;
		}     

		

		/* Function to Display Pet Icons */		

		function display_pet_icons($resultArray, $animalName, $maxIcons) {

			//echo '<pre>ICON FUNCTION CALLED WITH<br>'; print_r($resultArray); echo '</pre>';

			//echo 'Icon Function Called with Max Icons = ' . $maxIcons . '<br>';

			$iconString 	= '';

			

			$iconCount 	= 0;

			if ( (array_key_exists(constant('PETPOINT_SEX'), $resultArray)) ) {

				if ( ($resultArray[constant('PETPOINT_SEX')] == "Female") ) {

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_female.png' . '" alt="Female Icon" title="' . $animalName . ' is a Girl" />';

				} elseif ( ($resultArray[constant('PETPOINT_SEX')] == "Male") ){

					$iconString = $iconString . '<img src="' . $this->imagesPath . 'icon_male.png' . '" alt="Male Icon" title="' . $animalName . ' is a Boy" />';

				}

				$iconCount = $iconCount + 1;

			}

			if ( (is_array($resultArray[constant('PETPOINT_SEX')])) && (array_key_exists('value', $resultArray['sex'])) ) {

				if ( ($resultArray[constant('PETPOINT_SEX')]['value'] == "Female") ) {

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_female.png' . '" alt="Female Icon" title="' . $animalName . ' is a Girl" />';

				} elseif ( ($resultArray[constant('PETPOINT_SEX')]['value'] == "Male") ){

					$iconString = $iconString . '<img src="' . $this->imagesPath . 'icon_male.png' . '" alt="Male Icon" title="' . $animalName . ' is a Boy" />';

				}

				$iconCount = $iconCount + 1;

			}

			if ( (array_key_exists(constant('PETPOINT_AGE_GROUP'), $resultArray)) ) {

				if (array_key_exists('value', $resultArray[constant('PETPOINT_AGE_GROUP')])) {

					$ageIcon = strtolower($resultArray[constant('PETPOINT_AGE_GROUP')]['value']);

				} else {

					$ageIcon = strtolower($resultArray[constant('PETPOINT_AGE_GROUP')]);

				}

				$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_' . $ageIcon . '.jpg' . '" alt="General Age Icon" title="' . $animalName . ' is ' . ucfirst($ageIcon) . '" />';

				$iconCount = $iconCount + 1;		

			}

			

			if ( (array_key_exists(constant('PETPOINT_DECLAWED'), $resultArray)) ) {

				if ( ($resultArray[constant('PETPOINT_DECLAWED')] == "Yes") && ($iconCount < $maxIcons) ) {

					//echo 'Processing Declawed Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_declaw.jpg' . '" alt="Declawed Cat Icon" title="' . $animalName . ' is Declawed" />';

					$iconCount = $iconCount + 1;		

				}

			}

			if ( (is_array($resultArray[constant('PETPOINT_DECLAWED')])) && (array_key_exists('value', $resultArray['declawed'])) ) {

				if ( ($resultArray[constant('PETPOINT_DECLAWED')]['value'] == "Yes") && ($iconCount < $maxIcons) ) {

					//echo 'Processing Declawed Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_declaw.jpg' . '" alt="Declawed Cat Icon" title="' . $animalName . ' is Declawed" />';

					$iconCount = $iconCount + 1;		

				}

			}

			if ( (array_key_exists(constant('PETPOINT_SPECIAL_NEEDS'), $resultArray)) ) {		

				if ( ($resultArray[constant('PETPOINT_SPECIAL_NEEDS')]['value'] == "Yes") && ($iconCount < $maxIcons) ) {

					//echo 'Processing Special Needs Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_special_needs.jpg' . '" alt="Animal with Special Needs Icon" title="' . $animalName . ' Has Special Needs" />';

					$iconCount = $iconCount + 1;				

				}

			}	

			if ( (array_key_exists(constant('PETPOINT_SPECIAL_NEEDS'), $resultArray)) ) {						

				if ( (is_array($resultArray[constant('PETPOINT_SPECIAL_NEEDS')])) ) {

					if ( (array_key_exists('value', $resultArray[constant('PETPOINT_SPECIAL_NEEDS')])) ) {

						if ( ($resultArray[constant('PETPOINT_SPECIAL_NEEDS')]['value'] == "Yes") && ($iconCount < $maxIcons) ) {

							//echo 'Processing Special Needs Icon with Previous Count ' . $iconCount . '<br>';

							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_special_needs.jpg' . '" alt="Animal with Special Needs Icon" title="' . $animalName . ' Has Special Needs" />';

							$iconCount = $iconCount + 1;				

						}

					}

				}

			}

			if ( (array_key_exists(constant('PETPOINT_OK_CATS'), $resultArray)) ) {					

				if ( ($resultArray[constant('PETPOINT_OK_CATS')] == "Yes") && ($iconCount < $maxIcons) ) {

					//echo 'Processing OK with Cats Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_cats.png' . '" alt="OK with Cats Icon" title="' . $animalName . ' is OK with Cats" />';

					$iconCount = $iconCount + 1;								

				} elseif ( ($resultArray[constant('PETPOINT_OK_CATS')] == "No") && ($iconCount < $maxIcons) ) {

					//echo 'Processing NOT OK with Cats Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_cats_not.png' . '" alt="NOT OK with Cats Icon" title="' . $animalName . ' is NOT OK with Cats" />';

					$iconCount = $iconCount + 1;								

				}

			}

			if ( (array_key_exists(constant('PETPOINT_OK_CATS'), $resultArray)) ) {								

				if ( (is_array($resultArray[constant('PETPOINT_OK_CATS')])) ) {

					if ( (array_key_exists('value', $resultArray['nocats'])) ) {

						if ( ($resultArray[constant('PETPOINT_OK_CATS')]['value'] == "Yes") && ($iconCount < $maxIcons) ) {

							//echo 'Processing OK with Cats Icon with Previous Count ' . $iconCount . '<br>';

							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_cats.png' . '" alt="OK with Cats Icon" title="' . $animalName . ' is OK with Cats" />';

							$iconCount = $iconCount + 1;								

						} elseif ( ($resultArray[constant('PETPOINT_OK_CATS')]['value'] == "No") && ($iconCount < $maxIcons) ) {

							//echo 'Processing NOT OK with Cats Icon with Previous Count ' . $iconCount . '<br>';

							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_cats_not.png' . '" alt="NOT OK with Cats Icon" title="' . $animalName . ' is NOT OK with Cats" />';

							$iconCount = $iconCount + 1;								

						}

					}

				}

			}

			if ( (array_key_exists(constant('PETPOINT_OK_DOGS'), $resultArray)) ){					

				if ( ($resultArray[constant('PETPOINT_OK_DOGS')] == "Yes")  && ($iconCount < $maxIcons) ) {

					//echo 'Processing OK with Dogs Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_dogs.png' . '" alt="OK with Dogs Icon" title="' . $animalName . ' is OK with Dogs" />';

					$iconCount = $iconCount + 1;								

				} elseif ( ($resultArray[constant('PETPOINT_OK_DOGS')] == "No") && ($iconCount < $maxIcons) ) {

					//echo 'Processing NOT OK with Dogs Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_dogs_not.png' . '" alt="NOT OK with Dogs Icon" title="' . $animalName . ' is NOT OK with Dogs" />';

					$iconCount = $iconCount + 1;								

				}

			}

			if ( (array_key_exists(constant('PETPOINT_OK_DOGS'), $resultArray)) ){							

				if ( (is_array($resultArray[constant('PETPOINT_OK_DOGS')])) ) {

					if ( (array_key_exists('value', $resultArray[constant('PETPOINT_OK_DOGS')])) ) {

						if ( ($resultArray[constant('PETPOINT_OK_DOGS')]['value'] == "Yes") && ($iconCount < $maxIcons) ) {

							//echo 'Processing OK with Dogs Icon with Previous Count ' . $iconCount . '<br>';

							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_dogs.png' . '" alt="OK with Dogs Icon" title="' . $animalName . ' is OK with Dogs" />';

							$iconCount = $iconCount + 1;								

						} elseif ( ($resultArray[constant('PETPOINT_OK_DOGS')]['value'] == "No") && ($iconCount < $maxIcons) ) {

							//echo 'Processing NOT OK with Dogs Icon with Previous Count ' . $iconCount . '<br>';

							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_dogs_not.png' . '" alt="NOT OK with Dogs Icon" title="' . $animalName . ' is NOT OK with Dogs" />';

							$iconCount = $iconCount + 1;								

						}

					}

				}

			}

			if ( (array_key_exists(constant('PETPOINT_OK_KIDS'), $resultArray)) ){								

				if ( ($resultArray[constant('PETPOINT_OK_KIDS')] == "Yes") && ($iconCount < $maxIcons) ) {

					//echo 'Processing OK with Kids Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_kids.jpg' . '" alt="OK with Kids Icon" title="' . $animalName . ' is OK with Kids" />';

					$iconCount = $iconCount + 1;								

				} elseif (  ($resultArray[constant('PETPOINT_OK_KIDS')] == "No") && ($iconCount < $maxIcons) ) {

					//echo 'Processing NOT OK with Kids Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_kids_not.jpg' . '" alt="NOT OK with Kids Icon" title="' . $animalName . ' is NOT OK with Kids" />';

					$iconCount = $iconCount + 1;								

				}

			}

			if ( (array_key_exists(constant('PETPOINT_OK_KIDS'), $resultArray)) ){								

				if ( (is_array($resultArray[constant('PETPOINT_OK_KIDS')])) ) {

					if ( (array_key_exists('value', $resultArray['nokids'])) ) {

						if ( ($resultArray[constant('PETPOINT_OK_KIDS')]['value'] == "Yes") && ($iconCount < $maxIcons) ) {

							//echo 'Processing OK with Kids Icon with Previous Count ' . $iconCount . '<br>';

							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_kids.jpg' . '" alt="OK with Kids Icon" title="' . $animalName . ' is OK with Kids" />';

							$iconCount = $iconCount + 1;								

						} elseif ( ($resultArray[constant('PETPOINT_OK_KIDS')]['value'] == "No") && ($iconCount < $maxIcons) ) {

							//echo 'Processing NOT OK with Kids Icon with Previous Count ' . $iconCount . '<br>';

							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_ok_kids_not.jpg' . '" alt="NOT OK with Kids Icon" title="' . $animalName . ' is NOT OK with Kids" />';

							$iconCount = $iconCount + 1;								

						}

					}

				}

			}

			if ( (array_key_exists(constant('PETPOINT_HOUSETRAINED'), $resultArray)) ) {								

				if ( ($resultArray[constant('PETPOINT_HOUSETRAINED')] == "Yes") && ($resultArray[constant('PETPOINT_SPECIES')] == 'Dog') && ($iconCount < $maxIcons) ) {

					//echo 'Processing Housetrained Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_house_trained.png' . '" alt="Animal is House Trained Icon" title="' . $animalName . ' is House Trained" />';

					$iconCount = $iconCount + 1;								

				} elseif ( ($resultArray[constant('PETPOINT_HOUSETRAINED')] == "No") && ($resultArray[tant('PETPOINT_SPECIES')] == 'Dog') && ($iconCount < $maxIcons) ) {

					//echo 'Processing NOT Housetrained Icon with Previous Count ' . $iconCount . '<br>';

					$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_house_trained_not.png' . '" alt="Animal is NOT House Trained Icon" title="' . $animalName . ' is NOT House Trained" />';

					$iconCount = $iconCount + 1;								

				}        

			}			

			if ( (array_key_exists(constant('PETPOINT_HOUSETRAINED'), $resultArray)) ) {								

				if ( (is_array($resultArray[constant('PETPOINT_HOUSETRAINED')])) ) {

					if ( (array_key_exists('value', $resultArray[constant('PETPOINT_HOUSETRAINED')])) ) {

						if ( ($resultArray[constant('PETPOINT_HOUSETRAINED')]['value'] == "Yes") && ($resultArray[tant('PETPOINT_SPECIES')]['value'] == 'Dog') && ($iconCount < $maxIcons) ) {

							//echo 'Processing Housetrained Icon with Previous Count ' . $iconCount . '<br>';

							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_house_trained.png' . '" alt="Animal is House Trained Icon" title="' . $animalName . ' is House Trained" />';

							$iconCount = $iconCount + 1;								

						} elseif ( ($resultArray[constant('PETPOINT_HOUSETRAINED')]['value'] == "No") && ($resultArray[tant('PETPOINT_SPECIES')]['value'] == 'Dog') && ($iconCount < $maxIcons) ) {

							//echo 'Processing NOT Housetrained Icon with Previous Count ' . $iconCount . '<br>';

							$iconString = $iconString . '<img class="pmp-detail-icon" src="' . $this->imagesPath . 'icon_house_trained_not.png' . '" alt="Animal is NOT House Trained Icon" title="' . $animalName . ' is NOT House Trained" />';

							$iconCount = $iconCount + 1;								

						}        

					}

				}

			}

			if ( (array_key_exists('animalVideos', $resultArray)) ) {

				$videoArray = $resultArray['animalVideos'];

			} elseif (array_key_exists(constant('PETPOINT_VIDEOS'), $resultArray)) {

				$videoArray = $resultArray[constant('PETPOINT_VIDEOS')];

			}

			

			//echo 'Icon Count Before Video = ' . $iconCount . '<br>';

			//echo '<pre>VIDEO ARRAY<br>'; print_r($videoArray); echo '</pre>';

			if (( (!empty($videoArray[0])) || (!empty($videoArray)) ) && ($iconCount < $maxIcons) ) {

			//if (array_key_exists('animalVideo'['0'], $resultArray)) {	

				//echo '<pre>Processing Video 0<br>'; print_r($videoArray[0]); echo '</pre>';										           				

				//if (!empty($videoArray[0]['mediaID']) 

				//if (!empty($resultArray['animalVideoUrls']['0']['mediaID']) && ($iconCount < $maxIcons)) {

				//echo 'Processing Video Icon with Previous Count ' . $iconCount . '<br>';

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