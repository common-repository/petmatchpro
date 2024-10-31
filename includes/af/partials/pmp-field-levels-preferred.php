<?php
$pmpFieldLevelsPreferred = array ( 
/*License Levels for Search Filters */
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_AGE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_ALTERED') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_BREED_PRIMARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_COLOR_PRIMARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_DECLAWED') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_GENDER') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_MICROCHIP') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SEQ_ID') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SIZE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SPECIES') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_ORDERBY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('ANIMALSFIRST_SORTORDER') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,

/*License Levels for Search Sort Fields */
constant('LEVEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_AGE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_BREED_PRIMARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_GENDER') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_NAME') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_SEQ_ID') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_SIZE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_SORT') . constant('ANIMALSFIRST_SPECIES') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,


/*License Levels for Search Sort Order Values */
constant('LEVEL_PREFIX_SEARCH_ORDER') . constant('ANIMALSFIRST_SORTORDER_ASCENDING') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_ORDER') . constant('ANIMALSFIRST_SORTORDER_DESCENDING') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_ORDER') . constant('ANIMALSFIRST_SORTORDER_RANDOM') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,

/* License Levels for Search Results */
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_ADOPT_PROFILE_URL') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_ADOPTION_FEE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_AGE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')					=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_AGE_DISPLAY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_ALTERED') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DATE_BIRTH') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DATE_BIRTH_EST') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DATE_INTAKE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DATE_OUTCOME') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_BREED_PRIMARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_BREED_SECONDARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_COLOR_PRIMARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_COLOR_SECONDARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DECLAWED') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_DESCRIPTION') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_FEATURED') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_GENDER') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_ID') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')					=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_KENNEL') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_INTAKE_TYPE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_ADDRESS') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_ADDRESS_1') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_ADDRESS_2') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_CITY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_COUNTRY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_FORMATTED') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_FULL') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_GEOMETRY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_LATITUDE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_LONGITUDE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_STATE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_URL') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LOCATION_ZIP') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_LENGTH_OF_STAY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_MICROCHIP') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_NAME') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_PHOTO_URL') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_PHOTOS') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_SEQ_ID') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_SHELTER_ID') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_SIZE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_SPECIES') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_STATUS') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('ANIMALSFIRST_VIDEOS') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,

/* License Levels for Animal Details */
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_ADOPT_PROFILE_URL') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_ADOPTION_FEE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_AGE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')					=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_AGE_DISPLAY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_ALTERED') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DATE_BIRTH') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DATE_BIRTH_EST') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DATE_INTAKE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DATE_OUTCOME') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_BREED_PRIMARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_BREED_SECONDARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_COLOR_PRIMARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_COLOR_SECONDARY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DECLAWED') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_DESCRIPTION') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_FEATURED') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_GENDER') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_ID') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')					=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_KENNEL') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_INTAKE_TYPE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_ADDRESS') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_ADDRESS_1') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_ADDRESS_2') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_CITY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_COUNTRY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_FORMATTED') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_FULL') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_GEOMETRY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_LATITUDE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_LONGITUDE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')		=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_STATE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_URL') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LOCATION_ZIP') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_LENGTH_OF_STAY') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_MICROCHIP') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_NAME') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_PHOTO_URL') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_PHOTOS') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_SEQ_ID') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_SHELTER_ID') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')			=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_SIZE') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_SPECIES') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_STATUS') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_VIDEOS') . '_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')				=> 1,

/* Include Keys for Additional Premium Features */
'level_label_search_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') 			=> 1,
'level_video_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')					=> 1,
'level_buttons_conversion_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') 	=> 1,
'level_buttons_other_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') 			=> 1,
'level_buttons_additional_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') 	=> 1,
'level_social_share_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') 			=> 1
);
?>