<?php
$this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_criteria'] = 'Check fields for use in filtering ' . ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_details'] = 'Check fields to display in ' . ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')] = 'Select field to sort ' . ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')] = 'Select method to order ' . ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo['animal_details_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_search_labels'] = 'Check to show result labels in ' . ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_details'] = 'Check fields to display in ' . ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' detail pages.';

$this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_criteria'] = 'Check fields for use in filtering ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_details'] = 'Check fields to display in ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')] = 'Select field to sort ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')] = 'Select method to order ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo['animal_details_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_search_labels'] = 'Check to show result labels in ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_animal_details'] = 'Check fields to display in ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' detail pages.';

$this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_criteria'] = 'Check fields for use in filtering ' . ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_details'] = 'Check fields to display in ' . ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')] = 'Select field to sort ' . ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')] = 'Select method to order ' . ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo['animal_details_' . constant('LOST_METHODTYPE_ANIMALSFIRST') . '_search_labels'] = 'Check to show result labels in ' . ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' search results.';
$this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_animal_details'] = 'Check fields to display in ' . ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' detail pages.';

$generalOptions = get_option('pet-match-pro-general-options');
$preferredLabel = $generalOptions[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_methodtype_label'];
if (strlen(trim($preferredLabel)) == 0) {
	$preferredLabel = ucfirst(constant('PREFERRED_METHODTYPE_ANIMALSFIRST'));
}	

$this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_criteria'] = 'Check fields for use in filtering ' . $preferredLabel . ' search results.';
$this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_details'] = 'Check fields to display in ' . $preferredLabel . ' search results.';
$this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_ORDERBY')] = 'Select field to sort ' . $preferredLabel . ' search results.';
$this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_' . constant('ANIMALSFIRST_SORTORDER')] = 'Select method to order ' . $preferredLabel . ' search results.';
$this->pmpAFAdminInfo['animal_details_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_search_labels'] = 'Check to show result labels in ' . $preferredLabel . ' search results.';
$this->pmpAFAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_animal_details'] = 'Check fields to display in ' . $preferredLabel . ' detail pages.';

$this->pmpAFAdminInfo[constant('LOST_METHODTYPE_ANIMALSFIRST') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_combination'] = 'Select labels for use in searches combining ' . ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' and ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' animals.';

/** ADOPT **/
/* Get Field Label Values */
$labelsFile = 'pmp-field-values-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '.php';
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
$fieldValueKeys = array_keys($pmpFieldValuesAdopt);
//echo '<pre>FILTER VALUE KEYS<br>'; print_r($fieldValueKeys); echo '</pre>';       	
$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
//echo '<pre>FILTER FIELDS<br>'; print_r($filterFieldKeys); echo '</pre>';
		
if (!empty($filterFieldKeys)) {
  	foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
       	$filterLabelValue = trim($pmpFieldValuesAdopt[$filterFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpAFAdminInfo[$labelKey] = 'Enter a label when filtering ' . ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' animals by ' . trim($pmpFieldValuesAdopt[$filterFieldKey]) . '.';       		
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
       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
       	$searchLabelValue = trim($pmpFieldValuesAdopt[$searchFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = 'label_' . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpAFAdminInfo[$labelKey] = 'Enter a label for the ' . ucfirst(constant('ADOPT_METHODTYPE_ANIMALSFIRST')) . ' ' . trim($pmpFieldValuesAdopt[$searchFieldKey]) . '.';       		       		        		
        }
	}
}

/** FOUND **/
/* Get Field Label Values */
$labelsFile = 'pmp-field-values-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '.php';
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
       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
       	$filterLabelValue = trim($pmpFieldValuesFound[$filterFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpAFAdminInfo[$labelKey] = 'Enter a label when filtering ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' animals by ' . trim($pmpFieldValuesFound[$filterFieldKey]) . '.';       		
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
       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
       	$searchLabelValue = trim($pmpFieldValuesFound[$searchFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = 'label_' . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpAFAdminInfo[$labelKey] = 'Enter a label for the ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' ' . trim($pmpFieldValuesFound[$searchFieldKey]) . '.';       		       		        		
        }
	}
}

/** LOST **/
/* Get Field Label Values */
$labelsFile = 'pmp-field-values-' . constant('LOST_METHODTYPE_ANIMALSFIRST') . '.php';
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
       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
       	$filterLabelValue = trim($pmpFieldValuesLost[$filterFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpAFAdminInfo[$labelKey] = 'Enter a label when filtering ' . ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' animals by ' . trim($pmpFieldValuesLost[$filterFieldKey]) . '.';       		
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
       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
       	$searchLabelValue = trim($pmpFieldValuesLost[$searchFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = 'label_' . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpAFAdminInfo[$labelKey] = 'Enter a label for the ' . ucfirst(constant('LOST_METHODTYPE_ANIMALSFIRST')) . ' ' . trim($pmpFieldValuesLost[$searchFieldKey]) . '.';       		       		        		
        }
	}
}

/** PREFERRED **/
/* Get Field Label Values */
$labelsFile = 'pmp-field-values-' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '.php';
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
$fieldValueKeys = array_keys($pmpFieldValuesPreferred);
//echo '<pre>FILTER VALUE KEYS<br>'; print_r($fieldValueKeys); echo '</pre>';       	
$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
//echo '<pre>FILTER FIELDS<br>'; print_r($filterFieldKeys); echo '</pre>';
		
if (!empty($filterFieldKeys)) {
  	foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
       	$filterLabelValue = trim($pmpFieldValuesPreferred[$filterFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpAFAdminInfo[$labelKey] = 'Enter a label when filtering ' . $preferredLabel . ' animals by ' . trim($pmpFieldValuesPreferred[$filterFieldKey]) . '.';       		
        }
	}
}

/* Get Search Result Fields from Values File */
$searchFieldKeys = [];
$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
/* Match Search Label Keys */
$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
$searchValueKeys = array_keys($pmpFieldValuesPreferred);
$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
//echo '<pre>SEARCH FIELDS<br>'; print_r($searchFieldKeys); echo '</pre>';
		
if (!empty($searchFieldKeys)) {
   	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
       	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
       	$searchLabelValue = trim($pmpFieldValuesPreferred[$searchFieldKey]) . ':';
       	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
       	if (strlen($labelOptionKey) > 0) {
       		$labelKey = 'label_' . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpAFAdminInfo[$labelKey] = 'Enter a label for the ' . $preferredLabel . ' ' . trim($pmpFieldValuesPreferred[$searchFieldKey]) . '.';       		       		        		
        }
	}
}
?>