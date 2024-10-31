<?php

$this->pmpPPAdminInfo[constant('ADOPT_METHODTYPE_PETPOINT') . '_search_criteria'] = 'Check fields for use in filtering ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo[constant('ADOPT_METHODTYPE_PETPOINT') . '_search_details'] = 'Check fields to display in ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo[constant('ADOPT_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')] = 'Check field to sort ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo['animal_details_' . constant('ADOPT_METHODTYPE_PETPOINT') . '_search_labels'] = 'Check to show result labels in ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo[constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_details'] = 'Check fields to display in ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' detail pages.';
$this->pmpPPAdminInfo[constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_icons'] = 'Check to show icons in ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' detail page.';
$this->pmpPPAdminInfo[constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_icons_max'] = 'Select maximum icons to display on ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' detail page.';

$this->pmpPPAdminInfo[constant('FOUND_METHODTYPE_PETPOINT') . '_search_criteria'] = 'Check fields for use in filtering ' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo[constant('FOUND_METHODTYPE_PETPOINT') . '_search_details'] = 'Check fields to display in ' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo[constant('FOUND_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')] = 'Check field to sort ' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo['animal_details_' . constant('FOUND_METHODTYPE_PETPOINT') . '_search_labels'] = 'Check to show result labels in ' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo[constant('FOUND_METHODTYPE_PETPOINT') . '_animal_details'] = 'Check to show result labels in ' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' searches.';

$this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_search_criteria'] = 'Check fields for use in filtering ' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_search_details'] = 'Check fields to display in ' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_search_' . constant('PETPOINT_ORDERBY')] = 'Check field to sort ' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo['animal_details_' . constant('LOST_METHODTYPE_PETPOINT') . '_search_labels'] = 'Check to show result labels in ' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' search results.';
$this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_animal_details'] = 'Check to show result labels in ' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' searches.';

$this->pmpPPAdminInfo[constant('LOST_METHODTYPE_PETPOINT') . '_' . constant('FOUND_METHODTYPE_PETPOINT') . '_combination'] = 'Select labels for use in searches combining ' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' and ' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' animals.';

/** ADOPT **/
/* Get Field Label Values */
$labelsFile = 'pmp-field-values-' . constant('ADOPT_METHODTYPE_PETPOINT') . '.php';
$requireFile = $this->partialsDir . $labelsFile;
require($requireFile); 

$fieldValuesArrayName = 'pmpFieldValues' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT'));
$fieldValues = [];
$fieldValues = $$fieldValuesArrayName;
//echo '<pre>FIELD VALUES<br>'; print_r($fieldValues); echo '</pre>';				

/* Get Filter Fields from Values File */
$filterFieldKeys = [];
$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
/* Match Filter Label Keys */
$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
//echo 'Field Key Match Pattern is ' . $matchPattern . '.<br>';
//echo 'Pattern Match is ' . $matchPattern . '.<br>';
$fieldValueKeys = array_keys($fieldValues);
//$fieldValueKeys = array_keys($pmpFieldValuesAdopt);
//echo '<pre>FILTER VALUE KEYS<br>'; print_r($fieldValueKeys); echo '</pre>';       	
$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
//echo '<pre>FILTER FIELDS<br>'; print_r($filterFieldKeys); echo '</pre>';
		
if (!empty($filterFieldKeys)) {
  	foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
       	$labelOptionKey = trim($filterFieldKey);
//       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
       	$filterLabelValue = trim($fieldValues[$filterFieldKey]) . ':';
//       	$filterLabelValue = trim($pmpFieldValuesAdopt[$filterFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = $labelOptionKey;
//       		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpPPAdminInfo[$labelKey] = 'Enter a label when filtering ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' animals by ' . trim($fieldValues[$filterFieldKey]) . '.';       		
//       		$this->pmpPPAdminInfo[$labelKey] = 'Enter a label when filtering ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' animals by ' . trim($pmpFieldValuesAdopt[$filterFieldKey]) . '.';       		
        }
	}
}

/* Get Search Result Fields from Values File */
$searchFieldKeys = [];
$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
/* Match Search Label Keys */
$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
$searchValueKeys = array_keys($pmpFieldValuesAdopt);
$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
//echo '<pre>SEARCH FIELDS<br>'; print_r($searchFieldKeys); echo '</pre>';
		
if (!empty($searchFieldKeys)) {
   	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
       	$labelOptionKey = trim($searchFieldKey);
//       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
       	$searchLabelValue = trim($pmpFieldValuesAdopt[$searchFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = $labelOptionKey;
//       		$labelKey = 'label_' . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpPPAdminInfo[$labelKey] = 'Enter a label for the ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' ' . trim($pmpFieldValuesAdopt[$searchFieldKey]) . '.';       		       		        		
        }
	}
}

/* Get Detail Result Fields from Values File */
$detailFieldKeys = [];
$keyMatch = constant('LABEL_PREFIX_ANIMAL_DETAIL');
/* Match Detail Label Keys */
$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
$detailValueKeys = array_keys($pmpFieldValuesAdopt);
$detailFieldKeys = preg_grep($matchPattern, $detailValueKeys);
//echo '<pre>DETAIL FIELDS<br>'; print_r($detailFieldKeys); echo '</pre>';
		
if (!empty($detailFieldKeys)) {
   	foreach ($detailFieldKeys as $detailCounter=>$detailFieldKey) {
       	$labelOptionKey = trim($detailFieldKey);
//       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_ANIMAL_DETAIL'), '', trim($detailFieldKey));
       	$detailLabelValue = trim($pmpFieldValuesAdopt[$detailFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $detailLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = $labelOptionKey;
//       		$labelKey = 'label_' . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpPPAdminInfo[$labelKey] = 'Enter a label for the ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . ' ' . trim($pmpFieldValuesAdopt[$detailFieldKey]) . '.';       		       		        		
        }
	}
}

/** FOUND **/
/* Get Field Label Values */
$labelsFile = 'pmp-field-values-' . constant('FOUND_METHODTYPE_PETPOINT') . '.php';
$requireFile = $this->partialsDir . $labelsFile;
require($requireFile); 
//echo '<pre>FIELD VALUES '; print_r($pmpFieldValuesAdopt); echo '</pre>';

/* Get Filter Fields from Values File */
$filterFieldKeys = [];
$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
/* Match Filter Label Keys */
$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
//echo 'Field Key Match Pattern is ' . $matchPattern . '.<br>';
//echo 'Pattern Match is ' . $matchPattern . '.<br>';
$fieldValueKeys = array_keys($pmpFieldValuesFound);
//echo '<pre>FILTER VALUE KEYS<br>'; print_r($fieldValueKeys); echo '</pre>';       	
$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
//echo '<pre>FILTER FIELDS<br>'; print_r($filterFieldKeys); echo '</pre>';
		
if (!empty($filterFieldKeys)) {
  	foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
       	$labelOptionKey = trim($filterFieldKey);
//       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
       	$filterLabelValue = trim($pmpFieldValuesFound[$filterFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = $labelOptionKey;
//       		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpPPAdminInfo[$labelKey] = 'Enter a label when filtering ' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' animals by ' . trim($pmpFieldValuesFound[$filterFieldKey]) . '.';       		
        }
	}
}

/* Get Search Result Fields from Values File */
$searchFieldKeys = [];
$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
/* Match Search Label Keys */
$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
$searchValueKeys = array_keys($pmpFieldValuesFound);
$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
//echo '<pre>SEARCH FIELDS<br>'; print_r($searchFieldKeys); echo '</pre>';
		
if (!empty($searchFieldKeys)) {
   	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
       	$labelOptionKey = trim($searchFieldKey);
//       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
       	$searchLabelValue = trim($pmpFieldValuesFound[$searchFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = $labelOptionKey;
//       		$labelKey = 'label_' . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpPPAdminInfo[$labelKey] = 'Enter a label for the ' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' ' . trim($pmpFieldValuesFound[$searchFieldKey]) . '.';       		       		        		
        }
	}
}

/* Get Detail Result Fields from Values File */
$detailFieldKeys = [];
$keyMatch = constant('LABEL_PREFIX_ANIMAL_DETAIL');
/* Match Detail Label Keys */
$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
$detailValueKeys = array_keys($pmpFieldValuesFound);
$detailFieldKeys = preg_grep($matchPattern, $detailValueKeys);
//echo '<pre>DETAIL FIELDS<br>'; print_r($detailFieldKeys); echo '</pre>';
		
if (!empty($detailFieldKeys)) {
   	foreach ($detailFieldKeys as $detailCounter=>$detailFieldKey) {
       	$labelOptionKey = trim($detailFieldKey);
//       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_ANIMAL_DETAIL'), '', trim($detailFieldKey));
       	$detailLabelValue = trim($pmpFieldValuesFound[$detailFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $detailLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = $labelOptionKey;
//       		$labelKey = 'label_' . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpPPAdminInfo[$labelKey] = 'Enter a label for the ' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' ' . trim($pmpFieldValuesFound[$detailFieldKey]) . '.';       		       		        		
        }
	}
}

/** LOST **/
/* Get Field Label Values */
$labelsFile = 'pmp-field-values-' . constant('LOST_METHODTYPE_PETPOINT') . '.php';
$requireFile = $this->partialsDir . $labelsFile;
require($requireFile); 
//echo '<pre>FIELD VALUES '; print_r($pmpFieldValuesAdopt); echo '</pre>';

/* Get Filter Fields from Values File */
$filterFieldKeys = [];
$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
/* Match Filter Label Keys */
$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
//echo 'Field Key Match Pattern is ' . $matchPattern . '.<br>';
//echo 'Pattern Match is ' . $matchPattern . '.<br>';
$fieldValueKeys = array_keys($pmpFieldValuesLost);
//echo '<pre>FILTER VALUE KEYS<br>'; print_r($fieldValueKeys); echo '</pre>';       	
$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
//echo '<pre>FILTER FIELDS<br>'; print_r($filterFieldKeys); echo '</pre>';
		
if (!empty($filterFieldKeys)) {
  	foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
       	$labelOptionKey = trim($filterFieldKey);
//       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
       	$filterLabelValue = trim($pmpFieldValuesLost[$filterFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = $labelOptionKey;
//       		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpPPAdminInfo[$labelKey] = 'Enter a label when filtering ' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' animals by ' . trim($pmpFieldValuesLost[$filterFieldKey]) . '.';       		
        }
	}
}

/* Get Search Result Fields from Values File */
$searchFieldKeys = [];
$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
/* Match Search Label Keys */
$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
$searchValueKeys = array_keys($pmpFieldValuesLost);
$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
//echo '<pre>SEARCH FIELDS<br>'; print_r($searchFieldKeys); echo '</pre>';
		
if (!empty($searchFieldKeys)) {
   	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
       	$labelOptionKey = trim($searchFieldKey);
//       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
       	$searchLabelValue = trim($pmpFieldValuesLost[$searchFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = $labelOptionKey;
//       		$labelKey = 'label_' . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpPPAdminInfo[$labelKey] = 'Enter a label for the ' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' ' . trim($pmpFieldValuesLost[$searchFieldKey]) . '.';       		       		        		
        }
	}
}

/* Get Detail Result Fields from Values File */
$detailFieldKeys = [];
$keyMatch = constant('LABEL_PREFIX_ANIMAL_DETAIL');
/* Match Detail Label Keys */
$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
$detailValueKeys = array_keys($pmpFieldValuesLost);
$detailFieldKeys = preg_grep($matchPattern, $detailValueKeys);
//echo '<pre>DETAIL FIELDS<br>'; print_r($detailFieldKeys); echo '</pre>';
		
if (!empty($detailFieldKeys)) {
   	foreach ($detailFieldKeys as $detailCounter=>$detailFieldKey) {
       	$labelOptionKey = trim($detailFieldKey);
//       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_ANIMAL_DETAIL'), '', trim($detailFieldKey));
       	$detailLabelValue = trim($pmpFieldValuesLost[$detailFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $detailLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = $labelOptionKey;
//       		$labelKey = 'label_' . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpPPAdminInfo[$labelKey] = 'Enter a label for the ' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' ' . trim($pmpFieldValuesLost[$detailFieldKey]) . '.';       		       		        		
        }
	}
}
//echo '<pre>PETPOINT ADMIN INFO<br>'; print_r($this->pmpPPAdminInfo); echo '</pre>';
?>