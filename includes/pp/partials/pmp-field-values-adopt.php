<?php
	$ValuesPMPLicenseTypeID = (int) get_option('PMP_License_Type_ID');  
	//echo 'Adopt Field Values License Type Before Checking = ' . $ValuesPMPLicenseTypeID . '<br>';
	
	if ( $ValuesPMPLicenseTypeID == 0 ) {
		$ValuesPMPLicenseTypeID = constant('FREE_LEVEL');
	}
	
	//echo 'Adopt Field Values License Type = ' . $ValuesPMPLicenseTypeID . '<br>';

$pmpFieldValuesAdopt = array ( 
/*Label & Values for Search Filters */
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_AGE_GROUP') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')		=> 'Age Group',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_AGE_GROUP') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')		=> array('All' => 'All', 'OverYear' => 'Over 1 Year Old', 'UnderYear' => 'Under 1 Year Old'),
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 'Primary Breed',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> '',
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_BREED_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 'Secondary Breed',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_BREED_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> '',
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ID_SPECIES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')		=> 'Species',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ID_SPECIES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')		=> array('0' => 'All', '1' => 'Dog', '2' => 'Cat', '3' => 'Rabbit', '4' => 'Horse', '5' => 'Small and Furry', '6' => 'Pig', '7' => 'Reptile', '8' => 'Bird', '9' => 'Barnyard', '1003' => 'Other than Dog and Cat'),
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_LOCATION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')		=> 'Location',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_LOCATION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')		=> '',
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_OK_CATS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 'Not Ok With Cats',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_OK_CATS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> array('A' => 'Either', 'Y' => 'Yes', 'N' => 'No'),
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_OK_DOGS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 'Not Ok With Dogs',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_OK_DOGS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> array('A' => 'Either', 'Y' => 'Yes', 'N' => 'No'),
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_OK_KIDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 'Not Ok With Kids',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_OK_KIDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> array('A' => 'Either', 'Y' => 'Yes', 'N' => 'No'),
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ONHOLD') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 'On-Hold',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ONHOLD') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> array('A' => 'Either', 'Y' => 'Yes', 'N' => 'No'),
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ORDERBY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 'Order by',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ORDERBY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> array( 'Breed' => 'Breed', 'ID' => 'ID', 'Name' => 'Name', 'Sex' => 'Sex'),
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_SEX') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 'Sex',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_SEX') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> array('A' => 'All', 'M' => 'Male', 'F' => 'Female'),
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_SITE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 'Site',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_SITE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> '',
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_SPECIAL_NEEDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 'Special Needs',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_SPECIAL_NEEDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> array('A' => 'Either', 'Y' => 'Yes', 'N' => 'No'),

/*Labels for Search Sort Fields */
constant('LABEL_PREFIX_SEARCH_SORT') . constant('PETPOINT_BREED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 'Breed',
constant('LABEL_PREFIX_SEARCH_SORT') . constant('PETPOINT_ID') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 'ID',
constant('LABEL_PREFIX_SEARCH_SORT') . constant('PETPOINT_NAME') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 'Name',
constant('LABEL_PREFIX_SEARCH_SORT') . constant('PETPOINT_SEX') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 'Sex',

/* Labels for Search Results */
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_AGE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Age',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_ALTERED_SPAY_NEUTERED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 'Spay/Neutered',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_ARN') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Animal Reference Number',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_BEHAVIOR_RESULT') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 		=> 'Behaviour Result',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Primary Breed', 
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_BREED_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 		=> 'Secondary Breed', 
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_ID') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 						=> 'Animal Number',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_LOCATION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Shelter Location',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_MEMO') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Memo List',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_NAME') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Name',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_OK_CATS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Not OK with Cats',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_OK_DOGS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Not OK with Dogs',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_OK_KIDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Not OK with Kids',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_ONHOLD') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'On-Hold', 
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_PHOTO') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Photo',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_SEX') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Sex',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_SPECIAL_NEEDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Special Needs',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_SPECIES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Species',

/*Labels for Animal Details */
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ADOPT_APP_URL') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Adoption URL',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_AGE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Age', 
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_AGE_GROUP') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Age Group',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ALTERED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> "Altered",
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ARN') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Animal Reference Number',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BANNER_URL') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Banner URL',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BEHAVIOR_RESULT') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 		=> 'Behavior Result',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BEHAVIOR_TESTS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Behavior Test List',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Primary Breed', 
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BREED_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 		=> 'Secondary Breed',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLOR_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Primary Color',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLOR_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 		=> 'Secondary Color',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DATE_BIRTH') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Date of Birth',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DATE_INTAKE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> "Intake Date",
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DECLAWED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Declawed',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DESCRIPTION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Description',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_FEATURED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Featured',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_HOUSETRAINED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'House Trained',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ID') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 						=> 'Animal Number',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ID_BUDDY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Buddy ID',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ID_COMPANY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Company ID',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LIVED_ANIMALS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Lived with Animals',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LIVED_ANIMALS_TYPES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 	=> 'Lived with Animal Types',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LIVED_KIDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Lived with Children',            
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LIVED_PREVIOUS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Previous Environment',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LIVED_PREVIOUS_DURATION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 'Time in Former Home',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LOCATION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Shelter Location',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LOCATION_SUB') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Sub-Location',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_MARKS_PATTERN') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Color Pattern',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_MEMO') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Memo List',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_MICROCHIP') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Chip Number',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_NAME_ANIMAL') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Animal Name',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_OK_CATS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Not OK with Cats',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_OK_DOGS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Not OK with Dogs',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_OK_KIDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Not OK with Kids',            
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ONHOLD') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'On-Hold', 
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_PHOTO') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Photo', 
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_PRICE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Price',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SEX') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Sex',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SITE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Site',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SIZE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Size',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SPECIAL_NEEDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Special Needs',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SPECIES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Species',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_STAGE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Stage',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SURRENDER') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 				=> 'Intake Details',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_VIDEO') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Video',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_TYPE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Animal Type',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_WEIGHT') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 					=> 'Body Weight',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_WILDLIFE_CAUSE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 'Wildlife Intake Cause',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_WILDLIFE_INJURY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 		=> 'WildLife Intake Injury'
);

	/* Remove Premium Species Filter as Required */
	if ( $ValuesPMPLicenseTypeID == constant('FREE_LEVEL') ) {
		/* Include Default Field Values */
		//$valuesFile = 'pmp-field-values.php';
		//$requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('INCLUDE_DIR') . '/' . constant('PETPOINT_DIR') . '/' . constant('PARTIALS_DIR') . '/' . $valuesFile;
		//require($requireFile);           
		/* Get Free Species Values */
		//$speciesFree = $pmpFieldValues['value_free_species'];
		//$speciesFreeList = constant('FREE_SPECIES');
		//echo 'Free Species List = ' . $speciesFreeList . '<br>';
		$speciesFree = [];
		$speciesFree = explode(",", constant('FREE_SPECIES'));
		//echo '<pre>FREE SPECIES<br>'; print_r($speciesFree); echo '</pre>';	
//		$speciesFilter = $pmpFieldValuesAdopt[constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ID_SPECIES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')];
//		foreach ( $speciesFilter as $key => $value ) {
//			if (in_array(strtolower($value), $speciesFree)) {
//				$freeFilter[$key] = $value;
//			}
//		}
		if ( is_array($speciesFree) ) {
//		if ( is_array($freeFilter) ) {
			$pmpFieldValuesAdopt[constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ID_SPECIES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')] = $speciesFree;
//			$pmpFieldValuesAdopt[constant('VALUE_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ID_SPECIES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')] = $freeFilter;
		}
	}
?>