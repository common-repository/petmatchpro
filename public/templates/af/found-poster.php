<script type="application/javascript">
function galleryThumb(imgs) {
  var expandImg = document.getElementById("expandedImg");
  var imgtext = document.getElementById("imgtext");
  // Use the same src in the expanded image as the image being clicked on from the grid
  expandImg.src = imgs.src;
  // Use the value of the alt attribute of the clickable image as text inside the expanded image
  imgText.innerHTML = imgs.alt;
  // Show the container element (hidden with CSS)
  expandImg.parentElement.style.display = "block";
} 
</script>
<script type="text/javascript">
	let jqueryParams=[],jQuery=function(r){return jqueryParams=[...jqueryParams,r],jQuery},$=function(r){return jqueryParams=[...jqueryParams,r],$};window.jQuery=jQuery,window.$=jQuery;let customHeadScripts=!1;jQuery.fn=jQuery.prototype={},$.fn=jQuery.prototype={},jQuery.noConflict=function(r){if(window.jQuery)return jQuery=window.jQuery,$=window.jQuery,customHeadScripts=!0,jQuery.noConflict},jQuery.ready=function(r){jqueryParams=[...jqueryParams,r]},$.ready=function(r){jqueryParams=[...jqueryParams,r]},jQuery.load=function(r){jqueryParams=[...jqueryParams,r]},$.load=function(r){jqueryParams=[...jqueryParams,r]},jQuery.fn.ready=function(r){jqueryParams=[...jqueryParams,r]},$.fn.ready=function(r){jqueryParams=[...jqueryParams,r]};
</script>
<?php
    //echo '<pre>FOUND POSTER Template Called with Details<br>'; print_r($animalDetails); echo '</pre>';
	/* Secure Paid Features */
	//$this->PMPLicenseTypeID = 3;  
	//echo 'License ID = ' . $this->PMPLicenseTypeID . '<br>';

	/* Get Field Visibility Levels by License Type */
    $levelsFile = 'pmp-field-levels-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '.php';
   	$requireFile = $this->partialsDir . $levelsFile;
    //echo 'Require File = ' . $requireFile . '<br>';
    require $requireFile;
    //echo '<pre> FOUND FIELD LEVEL VALUES '; print_r($pmpFieldLevelsFound); echo '</pre>';

	/* Determine How the Page Was Called */
	if (isset($_SERVER['HTTP_REFERER'])) {
		$refererURL = $_SERVER['HTTP_REFERER'];
		$homeURL = get_home_url();
		if ( str_contains($refererURL, $homeURL) ) {
			$referer = constant('REFERER_SEARCH');
		} else {
			$referer = constant('REFERER_DIRECT');
		}
	} else {
		$referer = constant('REFERER_DIRECT');
	}
	$empty = constant('EMPTY_VALUE');
  	
	if (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $animalDetails)) {
		$animalSpecies = $animalDetails[constant('ANIMALSFIRST_SPECIES')]['value'];
	} else {
		$animalSpecies = ucwords(constant('ANIMALSFIRST_SPECIES'));
	}
	$animalSpeciesLower = strtolower($animalSpecies);
	
	if (array_key_exists(constant('ANIMALSFIRST_SEQ_ID'), $animalDetails)) {
		$animalID = $animalDetails[constant('ANIMALSFIRST_SEQ_ID')]['value'];	
	} else {
		$animalID = 0;
	}

  	$output = ''; 
  	if (array_key_exists(constant('ANIMALSFIRST_LOCATION_CITY'), $animalDetails)) {
  		$foundCity = $address[constant('ANIMALSFIRST_LOCATION_CITY')];
  	} else {
  		$foundCity = constant('EMPTY_VALUE');
  	}
	$output .= '<h2 id="pmp-details-title-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '" class="pmp-details-title">' .$animalDetails[constant('ANIMALSFIRST_GENDER')]['value'] . ' ' . $animalSpecies . ' ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' in ' . $foundCity . '</h2>';  	
  	$output .= '<div id="pmp-details-wrapper-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '" class="pmp-details-wrapper">';
  	$output .= '  <div class="pmp-details-media">';
	$output .= '    <div class="pmp-detail-image">'; 
	$photoLevelKey = constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_PHOTOS') . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST');
  	if ( ($this->PMPLicenseTypeID <= $pmpFieldLevelsFound[$photoLevelKey]) ) {
  		$photoArray = $animalDetails[constant('ANIMALSFIRST_PHOTOS')];
		if (array_key_exists(0, $photoArray)) {
	    	$output .= '      <img id="expandedImg" src="'.$photoArray[0].'"><div id="imgtext"></div>';
	    } elseif (!empty($animalDetails[constant('ANIMALSFIRST_PHOTO_URL')])) {
    		$output .= '      <img id="expandedImg" src="'.$animalDetails[constant('ANIMALSFIRST_PHOTO_URL')].'"><div id="imgtext"></div>';
    	} else {
    		$output .= '<div id="pmp-error-message"><p class="pmp-error">' . constant('ERROR') . ': No Photo.</p></div> <!-- #pmp-error-message -->';
    	}
    } else {
	   	$output .= '<div class="pmp-notice-upgrade-license"><a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display photos.</div> <!-- .pmp-notice-upgrade-license -->';
    }
   	$output .= '    </div> <!-- .pmp-detail-image -->';

    if ( !empty($this->PMPLicenseTypeID) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
    	if (array_key_exists(constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_detail_thumbs_max', $this->generalOptions)) {
    		$maxThumbs = $this->generalOptions[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_detail_thumbs_max'];
    	} else {
    		$maxThumbs = 6;
    	}
    }
    
    if ( !empty($this->PMPLicenseTypeID) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {
    	$output .= '    <div class="pmp-details-thumbs">';

		/* Initialize On Click Parameters */		
 		$gaName = 'image_pmp_detail_select';	
 		$gaParamArray = [];
 		$gaParamArray['event_category'] = 'Image';
 		$gaParamArray['event_action'] = 'Select';
 		$gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];

		$thumb = 0;		
	  	if ( (array_key_exists(constant('ANIMALSFIRST_PHOTOS'), $animalDetails)) && ($this->PMPLicenseTypeID <= $pmpFieldLevelsFound[$photoLevelKey]) && ($thumb <= $maxThumbs) ) {	
	    	foreach($animalDetails[constant('ANIMALSFIRST_PHOTOS')] as $photoURL){
			   	$clickID = 'pmp-detail-thumb-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '-' . $animalSpeciesLower . '-' . $animalID . '-' . $thumb;
			   	$gaParamArray['click_id'] = $clickID;
			   	$clickText = 'View ' . ucfirst(constant('FOUND_METHODTYPE_ANIMALSFIRST')) . ' ' . $animalSpecies . ' Photo #' . $thumb;
			   	$gaParamArray['click_text'] = $clickText;
			   	$clickURL = $photoURL;
			   	$gaParamArray['click_url'] = $clickURL;
			   	$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  	
				if (!empty($onClick)) {
					$onClick = ' ' . $onClick;
			   	}
			    $output .= '<div class="pmp-imgThumb"><img id="' . $clickID . '" src="' . $clickURL . '"  alt="' . $clickText . '" onclick="galleryThumb(this);' . $onClick . '"></div>';
	    	    $thumb = $thumb +1;
	    	}
	    } else {
	    	$output .= '<div class="pmp-notice-upgrade-license"><a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display thumbnail photos.</div> <!-- .pmp-notice-upgrade-license -->';
	    }
    	$output .= '    </div> <!-- .pmp-details-thumbs -->';
	}
	
	$displaySocial = 0;	
	$socialShareLevelKey = 'level_social_share_' . constant('FOUND_METHODTYPE_ANIMALSFIRST');
 	if ( ($this->PMPLicenseTypeID <= $pmpFieldLevelsFound[$socialShareLevelKey]) && (array_key_exists('social_share', $this->contactOptions)) ) {		
		$output .= '<div id="pmp-details-features-wrapper">';        
	    $output .= '<div class="pmp-details-features">';
	    $output .= '<div id="pmp-social-share-icons">';
		$output .= '<div class="pmp-social-share-title">Share this ' . $animalSpecies . ' with Your Network</div> <!-- .pmp-social-share-title-->';	    
	    $output .= do_shortcode('[pmp-social-share]');
	    $output .= '</div> <!-- #pmp-social-share-icons-->';    
	    $output .= '</div> <!-- .pmp-details-features-->';
	    $displaySocial = 1;	    
	} 
	
	$posterPage = $this->generalOptions['details_page_poster'];
	$posterURL = get_permalink($posterPage);
	$displayPoster = 0;
 	if ( ($this->PMPLicenseTypeID <= constant('FREE_LEVEL')) && (!is_null($posterURL)) ) {
 		if ($displaySocial == 0) {
			$output .= '<div id="pmp-details-features-wrapper">'; 
		}       
    	/* Configure Onclick Parameter */
 		$gaName = 'button_pmp_detail_select';	    
    	$gaParamArray['event_category'] = 'Button';
    	$gaParamArray['event_action'] = 'Select';
   		$clickID = 'pmp-detail-print-poster-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '-' . $animalSpeciesLower . '-' . $animalID;
   		$gaParamArray['click_id'] = $clickID;
   		$clickText = 'Print Poster';
   		$gaParamArray['click_text'] = $clickText;
   		$clickURL = $posterURL;
   		$gaParamArray['click_url'] = $clickURL;
   		$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  
    	$output .= '	<div class="pmp-details-features"><a id="' . $clickID . '" target="_blank" class="pmp-button pmp-detail-print-poster-button" href="' . $clickURL . '?method=' . $callFunc . '&' . constant('ANIMALSFIRST_ID') . '='. $_GET[constant('ANIMALSFIRST_ID')] . '" title="Click to Print Poster" onclick="' . $onClick . '">' . $clickText . '</a>';
    	$output .= '</div> <!-- .pmp-details-features-->';
    	$displayPoster = 1;
	}
	if ( ($displaySocial == 1) || ($displayPoster == 1) ) {
	   	$output .= '</div> <!-- #pmp-details-features-wrapper-->';
	}
	
    $output .= '  </div> <!-- .pmp-details-media -->';

   	$output .= '  <div id="pmp-details-results-wrapper-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '" class="pmp-details-results-wrapper">'; 

	//echo 'DETAILS ARRAY <pre>'; print_r($animalDetails); echo '</pre>';
	
    $cat = 'Cat';
  	$declawed = constant('ANIMALSFIRST_DECLAWED');
   	$photo = constant('ANIMALSFIRST_PHOTOS');	
    foreach($showItems as $detailKey){
   		if(array_key_exists($detailKey, $animalDetails)) {
    		$levelKey = constant('LEVEL_PREFIX_ANIMAL_DETAIL') . $detailKey . '_' . constant('FOUND_METHODTYPE_ANIMALSFIRST');   
    		if ( is_numeric($animalDetails[$detailKey]['value']) && ($animalDetails[$detailKey]['value'] == 0) ) {
    			$animalDetails[$detailKey]['value'] = $empty;
    		}
   			if ( ($detailKey == $declawed) && ($animalSpecies != $cat) ) {
   				$output = $output;
           	} elseif ( ($animalDetails[$detailKey]['value'] != $empty) && ($animalDetails[$detailKey] != $photo) ) {           	
				if ($this->PMPLicenseTypeID <= $pmpFieldLevelsFound[$levelKey]) {
					$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';
				} else {
					$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display ' . $detailKey . '.</div></div>';
				}
           	}
        }            
	}	

    $conversionButtonLevelKey = 'level_buttons_conversion_' . constant('FOUND_METHODTYPE_ANIMALSFIRST');

	/* Initialize On Click Parameters */		
	$gaName = 'button_pmp_detail_select';	
	$gaParamArray['event_category'] = 'Button';

    /*Add Additional Buttons */
	$output .= '<div id="pmp-details-other-buttons-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '" class="pmp-details-other-buttons">';
	
   	//Add Button to Call Shelter
   	$contact_options = get_option('pet-match-pro-contact-options');
	if (array_key_exists(constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_phone', $this->contactOptions)) {
		$foundPhone = $contact_options[constant('FOUND_METHODTYPE_ANIMALSFIRST') . '_phone'];
	} else {
		$foundPhone = constant('EMPTY_VALUE');	
	}   	

   	if ( !is_null($foundPhone) ) {
   		$output .= '    <div class="pmp-detail-other-button pmp-detail-call-us">';       	
    	$clickID = 'pmp-detail-call-us-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '-' . $animalSpeciesLower . '-' . $animalID;
		if ($this->PMPLicenseTypeID <= $pmpFieldLevelsFound[$conversionButtonLevelKey]) {  
		   	$gaParamArray['click_id'] = $clickID;    	
		   	$clickText = 'Call About This ' . $animalSpecies;
		   	$gaParamArray['click_text'] = $clickText;    	    
		   	$clickURL = 'tel:' . $foundPhone;
		   	$gaParamArray['click_url'] = $clickURL;
		   	$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  
   			$output .= '      <a id="' . $clickID . '" class="pmp-button pmp-detail-call-us-button" href="' . $clickURL . '" title="Call Us About This ' . $animalSpecies . '" onclick="' . $onClick . '">' . $clickText . '</a>';
	    } else {
		   	$clickText = 'Upgrade to Use<br>Conversions';
	    	$clickURL = constant('PMP_LOGIN');
	    	$output .= '      <a id="' . $clickID . '" target="_blank" class="pmp-button pmp-detail-application-foster-button pmp-notice-upgrade-license" href="' . $clickURL . '" title="Login to PetMatchPro.com">' . $clickText . '</a>';
	    }
   			
        $output .= '    </div> <!-- .pmp-detail-call-us -->';    
   	}
    
    //Add Button to Return to Search Results
    if ($referer == constant('REFERER_SEARCH')) {
    	$clickID = 'pmp-detail-search-return-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . '-' . $animalSpeciesLower . '-' . $animalID;
    	$gaParamArray['click_id'] = $clickID;
    	$clickText = 'Back to Search';
    	$gaParamArray['click_text'] = $clickText;
    	$clickURL = 'window.history.back();';
    	$gaParamArray['click_url'] = $clickURL;
    	$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  	
		if (!empty($onClick)) {
			$onClick = ' ' . $onClick;
    	}
    	$output .= '    <div class="pmp-detail-other-button pmp-detail-search-return">'; 
    	$output .= '      <button id="' . $clickID . '" class="pmp-button pmp-detail-search-return-button" onclick="' . $clickURL . $onClick . '" title="' . $clickText . '">' . $clickText . '</button>';
       	$output .= '    </div> <!-- .pmp-detail-search-return -->';    
    }

    $output .= '    </div> <!-- #pmp-details-other-buttons-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . ' -->';         
    $output .= '  </div> <!-- #pmp-details-results-wrapper-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . ' -->';
    $output .= '</div> <!-- #pmp-details-wrapper-' . constant('FOUND_METHODTYPE_ANIMALSFIRST') . ' -->';
?>