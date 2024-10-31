<?php
$this->pmpRGAdminInfo[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_criteria'] = 'Check fields for use in filtering ' . ucfirst(constant('ADOPT_METHODTYPE_RESCUEGROUPS')) . '  search results.';
$this->pmpRGAdminInfo[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_details'] = 'Check fields to display in ' . ucfirst(constant('ADOPT_METHODTYPE_RESCUEGROUPS')) . ' search results.';
$this->pmpRGAdminInfo[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_sortfield'] = 'Select field to sort search results.';
$this->pmpRGAdminInfo[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_sortorder'] = 'Select method to order search results.';
$this->pmpRGAdminInfo['animal_details_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_search_labels'] = 'Check to show result labels in ' . ucfirst(constant('ADOPT_METHODTYPE_RESCUEGROUPS')) . ' search results.';
$this->pmpRGAdminInfo[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_details'] = 'Check fields to display in ' . ucfirst(constant('ADOPT_METHODTYPE_RESCUEGROUPS')) . ' detail pages.';

$this->pmpRGAdminInfo['location_foster'] = 'Enter the RescueGroups foster home location name.';
$this->pmpRGAdminInfo['location_shelter'] = 'Enter the RescueGroups main shelter location name.';

/* Get Field Label Values */
$labelsFile = 'pmp-field-values-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '.php';
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
       		$this->pmpRGAdminInfo[$labelKey] = 'Enter a label when filtering ' . ucfirst(constant('ADOPT_METHODTYPE_RESCUEGROUPS')) . ' animals by ' . trim($pmpFieldValuesAdopt[$filterFieldKey]) . '.';       		
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
       		$labelKey = constant('LABEL_PREFIX_SEARCH_RESULT') . $labelOptionKey;
       		//echo 'Processing Label Key ' . $labelKey . '.<br>';	
       		$this->pmpRGAdminInfo[$labelKey] = 'Enter a label for the ' . ucfirst(constant('ADOPT_METHODTYPE_RESCUEGROUPS')) . ' ' . trim($pmpFieldValuesAdopt[$searchFieldKey]) . '.';       		       		        		
        }
	}
}
?>