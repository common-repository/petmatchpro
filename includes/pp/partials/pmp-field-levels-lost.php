<?php
$pmpFieldLevelsLost = array ( 
/*License levels for Search Options */
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_AGE_GROUP') . '_' . constant('LOST_METHODTYPE_PETPOINT')		=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ID_SPECIES') . '_' . constant('LOST_METHODTYPE_PETPOINT')		=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_ORDERBY') . '_' . constant('LOST_METHODTYPE_PETPOINT')			=> 2,
constant('LEVEL_PREFIX_SEARCH_FILTER') . constant('PETPOINT_SEX') . '_' . constant('LOST_METHODTYPE_PETPOINT')				=> 2,

/* License Levels for Search Option Values */
constant('LEVEL_PREFIX_SEARCH_SORT') .  constant('PETPOINT_BREED') . '_' . constant('LOST_METHODTYPE_PETPOINT')						=> 2,
constant('LEVEL_PREFIX_SEARCH_SORT') .  constant('PETPOINT_DATE_FIRST') . '_' . constant('LOST_METHODTYPE_PETPOINT')				=> 2,
constant('LEVEL_PREFIX_SEARCH_SORT') .  constant('PETPOINT_DATE_LAST') . '_' . constant('LOST_METHODTYPE_PETPOINT')					=> 3,
constant('LEVEL_PREFIX_SEARCH_SORT') .  constant('PETPOINT_ID') . '_' . constant('LOST_METHODTYPE_PETPOINT')						=> 2,
constant('LEVEL_PREFIX_SEARCH_SORT') .  constant('PETPOINT_NAME') . '_' . constant('LOST_METHODTYPE_PETPOINT')						=> 2,
constant('LEVEL_PREFIX_SEARCH_SORT') .  constant('PETPOINT_SEX') . '_' . constant('LOST_METHODTYPE_PETPOINT')						=> 2,

/* License Levels for Search Results */
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_AGE') . '_' . constant('LOST_METHODTYPE_PETPOINT') 						=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_ALTERED_SPAY_NEUTERED') . '_' . constant('LOST_METHODTYPE_PETPOINT') 	=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_BREED_PRIMARY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2, 
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_BREED_SECONDARY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2, 
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_COLOR_PRIMARY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_COLOR_SECONDARY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_DATE_LOST') . '_' . constant('LOST_METHODTYPE_PETPOINT') 				=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_ID') . '_' . constant('LOST_METHODTYPE_PETPOINT') 						=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_LOCATION_LOST_ADDRESS') . '_' . constant('LOST_METHODTYPE_PETPOINT') 	=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_LOCATION_LOST_CITY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 		=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_LOCATION_LOST_STATE') . '_' . constant('LOST_METHODTYPE_PETPOINT') 		=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_NAME') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_PHOTO') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_SEX') . '_' . constant('LOST_METHODTYPE_PETPOINT') 						=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_SPECIES') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,
constant('LEVEL_PREFIX_SEARCH_RESULT') . constant('PETPOINT_TYPE') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,

/* License Levels for Animal Details */
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_AGE') . '_' . constant('LOST_METHODTYPE_PETPOINT') 						=> 2, 
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BREED_PRIMARY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2, 
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_BREED_SECONDARY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COAT') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLLAR_COLOR') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLLAR_2_COLOR') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLLAR_TYPE') . '_' . constant('LOST_METHODTYPE_PETPOINT') 				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLLAR_2_TYPE'). '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLOR_EYE') . '_' . constant('LOST_METHODTYPE_PETPOINT') 				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLOR_PRIMARY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_COLOR_SECONDARY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DATE_LOST') . '_' . constant('LOST_METHODTYPE_PETPOINT') 				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DECLAWED') . '_' . constant('LOST_METHODTYPE_PETPOINT') 				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_DESCRIPTION') . '_' . constant('LOST_METHODTYPE_PETPOINT') 				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_EAR') . '_' . constant('LOST_METHODTYPE_PETPOINT') 						=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ID') . '_' . constant('LOST_METHODTYPE_PETPOINT') 						=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_ID_COMPANY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_JURISDICTION') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LOCATION_LOST') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LOCATION_LOST_CITY') . '_' . constant('LOST_METHODTYPE_PETPOINT') 		=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_LOCATION_LOST_STATE') . '_' . constant('LOST_METHODTYPE_PETPOINT') 		=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_MARKS') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_MARKS_PATTERN') . '_' . constant('LOST_METHODTYPE_PETPOINT') 			=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_NAME_ANIMAL') . '_' . constant('LOST_METHODTYPE_PETPOINT') 				=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_PHOTO') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SEX') . '_' . constant('LOST_METHODTYPE_PETPOINT') 						=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SIZE') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_SPECIES') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_TAIL') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_WEIGHT') . '_' . constant('LOST_METHODTYPE_PETPOINT') 					=> 2,
constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('PETPOINT_WEIGHT_UOM') . '_' . constant('LOST_METHODTYPE_PETPOINT') 				=> 2,

/* Include Keys for Additional Premium Features */
'level_buttons_conversion_' . constant('LOST_METHODTYPE_PETPOINT') 	=> 2,
'level_buttons_other_' . constant('LOST_METHODTYPE_PETPOINT') 		=> 2,
'level_LEVEL_search_' . constant('LOST_METHODTYPE_PETPOINT')		=> 2,
'level_social_share_' . constant('LOST_METHODTYPE_PETPOINT') 		=> 2
);
?>