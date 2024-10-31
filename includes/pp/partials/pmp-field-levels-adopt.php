<?php
$pmpFieldLevelsAdopt = array ( 
/*License Levels for Search Options */
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_AGE_GROUP') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')		=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_BREED_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ID_SPECIES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')		=> 3,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_LOCATION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')		=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_OK_CATS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_OK_DOGS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_OK_KIDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ONHOLD') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ORDERBY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_SEX') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_SITE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_SPECIAL_NEEDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 2,

/*License Levels for Search Sort Fields */
constant('LEVEL_PREFIX_SEARCH_SORT') .  constant('PETPOINT_BREED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_SEARCH_SORT') .  constant('PETPOINT_ID') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_SEARCH_SORT') .  constant('PETPOINT_NAME') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 3,
constant('LEVEL_PREFIX_SEARCH_SORT') .  constant('PETPOINT_SEX') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,

/* License Levels for Search Results */
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_AGE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')						=> 3,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_ALTERED_SPAY_NEUTERED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_ARN') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')						=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_BEHAVIOR_RESULT') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 3,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_BREED_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_ID') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')						=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_LOCATION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_MEMO') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_NAME') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 3, 
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_OK_CATS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_OK_DOGS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_OK_KIDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_ONHOLD') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_PHOTO') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 3,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_SEX') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')						=> 3,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_SPECIAL_NEEDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_SPECIES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 3,

/* License Levels for Animal Details */
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ADOPT_APP_URL') . '_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_AGE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')						=> 3,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_AGE_GROUP') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 3,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ALTERED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
//constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ALTERED_SPAY_NEUTERED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ARN') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')						=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BANNER_URL') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BEHAVIOR_RESULT') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BEHAVIOR_TESTS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BREED_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 3,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BREED_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLOR_PRIMARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLOR_SECONDARY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DATE_BIRTH') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DATE_INTAKE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DECLAWED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DESCRIPTION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 3,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_FEATURED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_HOUSETRAINED') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ID') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')						=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ID_BUDDY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ID_COMPANY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LIVED_ANIMALS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LIVED_ANIMALS_TYPES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')		=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LIVED_KIDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LIVED_PREVIOUS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LIVED_PREVIOUS_DURATION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')	=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LOCATION') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LOCATION_SUB') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_MARKS_PATTERN') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_MEMO') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_MICROCHIP') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_NAME_ANIMAL') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 3,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_OK_CATS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_OK_DOGS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_OK_KIDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ONHOLD') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_PHOTO') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 3,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_PRICE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SEX') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')						=> 3,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SITE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SIZE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SPECIAL_NEEDS') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SPECIES') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 3,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_STAGE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SURRENDER') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_VIDEO') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_TYPE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_WEIGHT') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_WILDLIFE_CAUSE') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_WILDLIFE_INJURY') . '_' . constant('ADOPT_METHODTYPE_PETPOINT')			=> 2,

/* Include Keys for Additional Premium Features */
'level_LEVEL_search_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 3,
'level_video_' . constant('ADOPT_METHODTYPE_PETPOINT')					=> 2,
'level_buttons_conversion_' . constant('ADOPT_METHODTYPE_PETPOINT') 	=> 2,
'level_buttons_other_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 3,
'level_buttons_additional_' . constant('ADOPT_METHODTYPE_PETPOINT') 	=> 2,
'level_social_share_' . constant('ADOPT_METHODTYPE_PETPOINT') 			=> 2
);
?>