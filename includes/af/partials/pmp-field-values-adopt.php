<?php
	$ValuesPMPLicenseTypeID = (int) get_option('PMP_License_Type_ID');  
	//echo 'Adopt Field Values License Type Before Checking = ' . $ValuesPMPLicenseTypeID . '<br>';

	if ( $ValuesPMPLicenseTypeID == 0 ) {
		$ValuesPMPLicenseTypeID = constant('FREE_LEVEL');
	}

	//echo 'Adopt Field Values License Type = ' . $ValuesPMPLicenseTypeID . '<br>';

$pmpFieldValuesAdopt = array ( 
/*Label & Values for Search Filters */
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_AGE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Age',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_AGE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> [],
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_ALTERED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Altered',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_ALTERED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> [],
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')	=> 'Breed',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')	=> [],
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_DECLAWED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Declawed',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_DECLAWED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> [],
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_GENDER') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Gender',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_GENDER') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> [],
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_MICROCHIP') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Microchip',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_MICROCHIP') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> '',
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_COLOR_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')	=> 'Primary Color',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_COLOR_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')	=> [],
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SEQ_ID') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'ID',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SEQ_ID') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> '',
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SIZE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Size',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SIZE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> [],
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SPECIES') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Species',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SPECIES') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> [],
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_ORDERBY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Sort by',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_ORDERBY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> array('age' => 'Age', 'breed' => 'Breed', 'gender' => 'Gender', 'name' => 'Name', 'seq_id' => 'ID', 'size' => 'Size', 'species' => 'Species'),
constant('LABEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SORTORDER') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Sort Order',
constant('VALUE_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SORTORDER') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> array('asc' => 'Ascending', 'desc' => 'Descending', 'random' => 'Random'),

/*Labels for Search Sort Fields */
constant('LABEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_AGE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Age',
constant('LABEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Breed',
constant('LABEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_GENDER') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Gender',
constant('LABEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_NAME') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Name',
constant('LABEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_SEQ_ID') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'ID',
constant('LABEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_SIZE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Size',
constant('LABEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_SPECIES') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Species',

/*Labels for Search Sort Order Values */
constant('LABEL_PREFIX_SEARCH_ORDER') . constant('ANIMALSFIRST_SORTORDER_ASCENDING') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Ascending',
constant('LABEL_PREFIX_SEARCH_ORDER') . constant('ANIMALSFIRST_SORTORDER_DESCENDING') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')	=> 'Descending',
constant('LABEL_PREFIX_SEARCH_ORDER') . constant('ANIMALSFIRST_SORTORDER_RANDOM') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Random',

/* Labels for Search Results */
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_ADOPT_PROFILE_URL') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Adoption Profile Link',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_ADOPTION_FEE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Adoption Fee',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_AGE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')					=> 'Age',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_AGE_DISPLAY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Display Age',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_ALTERED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Altered',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DATE_BIRTH') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Birthdate',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DATE_BIRTH_EST') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Estimated Birthdate',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DATE_INTAKE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Intake Date',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DATE_OUTCOME') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Outcome Date',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Primary Breed',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_BREED_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Secondary Breed',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_COLOR_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Primary Color',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_COLOR_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Secondary Color',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DECLAWED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Declawed',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DESCRIPTION') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Description',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_FEATURED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Featured',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_GENDER') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Gender',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_ID') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')					=> 'ID',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_KENNEL') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Kennel',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_INTAKE_TYPE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Intake Type',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Location',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_ADDRESS') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Address',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_ADDRESS_1') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Address #1',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_ADDRESS_2') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Address #2',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_CITY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'City',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_COUNTRY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Country',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_FORMATTED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Formatted Adress',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_FULL') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Full Address',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_GEOMETRY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Address Geometry',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_LATITUDE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Latitude',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_LONGITUDE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Longitude',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_STATE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'State',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_URL') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Address Link',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_ZIP') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Zip Code',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LENGTH_OF_STAY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'LOS',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_MICROCHIP') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Microchip',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_NAME') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Name',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_PHOTO_URL') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Photo Link',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_PHOTOS') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Photos',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_SEQ_ID') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Sequence ID',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_SHELTER_ID') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Shelter ID',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_SIZE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Size',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_SPECIES') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Species',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_STATUS') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Status',
constant('LABEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_VIDEOS') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Videos',

/*Labels for Animal Details */
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_ADOPT_PROFILE_URL') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Adoption Profile Link',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_ADOPTION_FEE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Adoption Fee',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_AGE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')					=> 'Age',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_AGE_DISPLAY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Display Age',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_ALTERED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Altered',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DATE_BIRTH') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Birthdate',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DATE_BIRTH_EST') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Estimated Birthdate',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DATE_INTAKE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Intake Date',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DATE_OUTCOME') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Outcome Date',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Primary Breed',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_BREED_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Secondary Breed',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_COLOR_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Primary Color',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_COLOR_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Secondary Color',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DECLAWED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Declawed',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DESCRIPTION') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Description',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_FEATURED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Featured',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_GENDER') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Gender',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_ID') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')					=> 'ID',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_KENNEL') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Kennel',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_INTAKE_TYPE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Intake Type',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Location',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_ADDRESS') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Address',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_ADDRESS_1') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Address #1',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_ADDRESS_2') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Address #2',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_CITY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'City',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_COUNTRY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Country',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_FORMATTED') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Formatted Adress',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_FULL') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Full Address',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_GEOMETRY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Address Geometry',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_LATITUDE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Latitude',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_LONGITUDE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Longitude',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_STATE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'State',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_URL') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Address Link',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_ZIP') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'Zip Code',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LENGTH_OF_STAY') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')		=> 'LOS',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_MICROCHIP') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Microchip',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_NAME') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Name',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_PHOTO_URL') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Photo Link',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_PHOTOS') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Photos',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_SEQ_ID') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Sequence ID',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_SHELTER_ID') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')			=> 'Shelter ID',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_SIZE') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Size',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_SPECIES') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Species',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_STATUS') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Status',
constant('LABEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_VIDEOS') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')				=> 'Videos'
);
	/* Remove Premium Species Filter as Required */
	if ( $ValuesPMPLicenseTypeID == constant('FREE_LEVEL') ) {
		/* Get Free Species Values */
		$speciesFree = [];
		$speciesFree = explode(",", constant('FREE_SPECIES'));
		//echo '<pre>FREE SPECIES<br>'; print_r($speciesFree); echo '</pre>';

//		$speciesFilter = $pmpFieldValuesAdopt['value_filter_animalspecies_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')];
//		foreach ( $speciesFilter as $key => $value ) {
//			if (in_array(strtolower($value), $speciesFree)) {
//				$freeFilter[$key] = $value;
//			}
//		}

		if ( is_array($speciesFree) ) {
//		if ( is_array($freeFilter) ) {
			$pmpFieldValuesAdopt[constant('VALUE_PREFIX_SEARCH_FILTER')  . constant('ANIMALSFIRST_SPECIES') . constant('ADOPT_METHODTYPE_ANIMALSFIRST')] = $speciesFree;
//			$pmpFieldValuesAdopt[constant('VALUE_PREFIX_SEARCH_FILTER')  . constant('ANIMALSFIRST_SPECIES') . constant('ADOPT_METHODTYPE_ANIMALSFIRST')] = $freeFilter;
		}
	}
?>