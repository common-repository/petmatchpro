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
	/* Secure Paid Features */
	//$this->PMPLicenseTypeID = 3;  
	//echo 'License ID = ' . $this->PMPLicenseTypeID . '.<br>';

	/* Get Field Visibility Levels by License Type */
    $levelsFile = 'pmp-field-levels-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '.php';
   	$requireFile = $this->partialsDir . $levelsFile;
    //echo 'Require File = ' . $requireFile . '<br>';
    require $requireFile;
    //echo '<pre> ADOPTION FIELD LEVEL VALUES '; print_r($pmpFieldLevelsAdopt); echo '</pre>';

	/* Determine How the Page Was Called */
	$homeURL = get_home_url();
	if (isset($_SERVER['HTTP_REFERER'])) {
		$refererURL = $_SERVER['HTTP_REFERER'];
		if ( str_contains($refererURL, $homeURL) ) {
			$referer = constant('REFERER_SEARCH');
		} else {
			$referer = constant('REFERER_DIRECT');
		}
	} else {
		$referer = constant('REFERER_DIRECT');
	}
	
	//echo '<pre>ADOPTABLE DETAILS<br>'; print_r($animalDetails); echo '</pre>';
	$empty = constant('EMPTY_VALUE');
	
	if (array_key_exists(constant('ANIMALSFIRST_SPECIES'), $animalDetails)) {
		$animalSpecies = $animalDetails[constant('ANIMALSFIRST_SPECIES')]['value'];
	} else {
		$animalSpecies = ucwords(constant('ANIMALSFIRST_SPECIES'));
	}
	$animalSpeciesLower = strtolower($animalSpecies);
	
	if (array_key_exists(constant('ANIMALSFIRST_NAME'), $animalDetails)) {
		$animalName = ucwords($animalDetails[constant('ANIMALSFIRST_NAME')]['value']);
	} else {
		$animalName = ucwords(constant('ANIMALSFIRST_NAME'));
	}
	$animalNameLower = strtolower($animalName);
	
  	$output = '';
  	$output .= '<div id="pmp-details-wrapper-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '" class="pmp-details-wrapper">';
  	$output .= '  <div class="pmp-details-media">';
	$output .= '    <div class="pmp-detail-image">'; 
	$photoLevelKey = constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_PHOTOS') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');	
  	if ( ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$photoLevelKey]) ) {
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
    	$output .= '    <div class="pmp-details-thumbs">';

		/* Initialize On Click Parameters */		
 		$gaName = 'image_pmp_detail_select';	
 		$gaParamArray = array();
 		$gaParamArray['event_category'] = 'Image';
 		$gaParamArray['event_action'] = 'Select';
 		$gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];

		$thumb = 0;		
	  	if ( (array_key_exists(constant('ANIMALSFIRST_PHOTOS'), $animalDetails)) && ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$photoLevelKey]) && ($thumb <= $maxThumbs) ) {		
	    	foreach($animalDetails[constant('ANIMALSFIRST_PHOTOS')] as $photoURL){
	    		if ($thumb <= $maxThumbs) {	    		    	
				   	$clickID = 'pmp-detail-thumb-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '-' . $animalSpeciesLower . '-' . str_replace(" ", "-", $animalNameLower) . '-' . $thumb;
				   	$gaParamArray['click_id'] = $clickID;
				   	$clickText = 'View ' . $animalName . ' the ' . $animalSpecies . ' Photo #' . $thumb;
				   	$gaParamArray['click_text'] = $clickText;
				   	$clickURL = $photoURL;
				   	$gaParamArray['click_url'] = $clickURL;
				   	$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  	
					if (!empty($onClick)) {
						$onClick = ' ' . $onClick;
				   	}
				    $output .= '<div class="pmp-imgThumb"><img id="' . $clickID . '" src="' . $clickURL . '"  alt="' . $clickText . '" onclick="galleryThumb(this);' . $onClick . '"></div>';
		    	    $thumb = $thumb + 1;
		    	}
	    	}
	    } else {
	    	$output .= '<div class="pmp-notice-upgrade-license"><a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display thumbnail photos.</div> <!-- .pmp-notice-upgrade-license -->';
	    }
		$videoLevelKey = constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('ANIMALSFIRST_VIDEOS') . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');
		//echo 'Video Level Key = ' . $videoLevelKey . '<br>';
	  	if ( $this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$videoLevelKey] ) {	
	  	//echo 'Processing Video Data<br>';	
	    	if( (array_key_exists(constant('ANIMALSFIRST_VIDEOS'), $animalDetails)) ) {
	    		$videoArray = $animalDetails[constant('ANIMALSFIRST_VIDEOS')];
	    		$videoCount = count($videoArray);
	    		//echo 'There Are ' . $videoCount . ' Videos.<br>';
	    		$videoCount = $videoCount - 1;
	    		$counter = 0;
	    		if ($videoCount > 0) {
	    			$videoSupport = 'video-support.php';
	   				$requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('PUBLIC_DIR') . '/' . constant('VIDEO_DIR') . '/' . $videoSupport;
	    			//echo 'Video Support File = ' . $requireFile . '<br>';
	    			require $requireFile;	    		
	    			while ($counter <= $videoCount) {
	    				if ( (array_key_exists($counter, $videoArray)) && ($videoArray[$counter] != '') ) {
				    		//echo 'Preparing Video Click Details<br>';
						   	$gaParamArray['event_category'] = 'YouTube';
						   	$gaParamArray['event_action'] = 'Play';
						   	$idCounter = $counter + 1;
						   	$clickID = 'pmp-detail-video-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '-' . $animalSpeciesLower . '-' . str_replace(" ", "-", $animalNameLower) . '-' . $idCounter;
						   	$gaParamArray['click_id'] = $clickID;
						   	$clickText = 'View ' . $animalName . ' the ' . $animalSpecies . ' Video #' . $idCounter;
						   	$gaParamArray['click_text'] = $clickText;
						   	$videoURL = $videoArray[$counter];
						   	//echo 'Last Slash Position in URL is ' . strrpos($videoURL, '/') . '.<br>';
						   	$videoID = substr($videoURL, strrpos($videoURL, '/')+1, 99);
						   	//echo 'Video ID = ' . $videoID . '<brb>';
						   	$clickURL = 'https://www.youtube.com/embed/' . $videoID;
						   	$gaParamArray['click_url'] = $clickURL;
						   	$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  
							$output.= '<a href="' . $clickURL . '" class="pmp-detail-video-play-icon" id="" data-channel="custom" title="'. $clickText .'" onclick="' . $onClick . '"></a>';
						}
						$counter = $counter + 1;
					}
				}
	    	}
	    } else {
	    	$output .= '<div class="pmp-notice-upgrade-license"><a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display video.</div> <!-- .pmp-notice-upgrade-license -->';
	    }
    	$output .= '    </div> <!-- .pmp-details-thumbs -->';
	}
	
	$socialShareLevelKey = 'level_social_share_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');	
 	if ( ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$socialShareLevelKey]) && (array_key_exists('social_share', $this->contactOptions)) ) {		
		$output .= '<div id="pmp-details-features-wrapper">';        
	    $output .= '<div class="pmp-details-features">';
	    $output .= '<div id="pmp-social-share-icons">';
		$output .= '<div class="pmp-social-share-title">Share ' . $animalName . ' with Your Network</div> <!-- .pmp-social-share-title-->';	    
	    $output .= do_shortcode('[pmp-social-share]');
	    $output .= '</div> <!-- #pmp-social-share-icons-->';    
	    $output .= '</div> <!-- .pmp-details-features-->';
	    $output .= '</div> <!-- #pmp-details-features-wrapper-->';
	} 
	
    $output .= '  </div> <!-- .pmp-details-media -->';

   	$output .= '  <div id="pmp-details-results-wrapper-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '" class="pmp-details-results-wrapper">'; 

	/* Get Foster & Shelter Locations Names */
	$locationFoster = do_shortcode('[pmp-option type="filter" value="location_foster"]');
	//echo 'Foster Location is ' . $locationFoster . '<br>';
	$locationShelter = do_shortcode('[pmp-option type="filter" value="location_shelter"]');
	//echo 'Shelter Location is ' . $locationShelter . '<br>';	
	
	//echo '<pre>SHOW ITEMS<br>'; print_r($showItems); echo '</pre>';
	//echo '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';
	
	$applicationurl = constant('ANIMALSFIRST_ADOPT_PROFILE_URL');
    $cat = 'Cat';
    $dog = 'Dog';
  	$declawed = constant('ANIMALSFIRST_DECLAWED');
   	$description = constant('ANIMALSFIRST_DESCRIPTION');
   	$foster_home = $locationFoster;
   	$shelter = $locationShelter;
   	$housetrained = '';
   	$location = constant('ANIMALSFIRST_LOCATION');
   	$photo = constant('ANIMALSFIRST_PHOTOS');
   	$videoid = constant('ANIMALSFIRST_VIDEOS');
	
	foreach($showItems as $detailKey){
    	if(array_key_exists($detailKey, $animalDetails)){
    		$levelKey = constant('LEVEL_PREFIX_ANIMAL_DETAIL') . $detailKey . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');
           	if ( ($detailKey <> $description) && ($detailKey <> $videoid) && ($detailKey <> $applicationurl) && ($detailKey <> $photo) && ($detailKey <> strtolower(constant('ERROR'))) ) {
           		if ( ($detailKey <> $declawed) && ($detailKey <> $housetrained) && ($detailKey <> $location) )   {
           			$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div>';
					if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$levelKey]) {
						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';
					} else {
						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display ' . $detailKey . '.</div></div>';
					}
           		} elseif ( ($detailKey == $declawed) && ($animalDetails[constant('ANIMALSFIRST_SPECIES')]['value'] == $cat) ) {
           			$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div>';
					if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$levelKey]) {
						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';
					} else {
						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display ' . $detailKey . '.</div></div>';
					}
           		} elseif ( ($detailKey == $housetrained) && ($animalDetails[constant('ANIMALSFIRST_SPECIES')]['value'] == $dog) ) {
           			$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div>';
					if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$levelKey]) {
						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';
					} else {
						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display ' . $detailKey . '.</div></div>';
					}
				} elseif ($detailKey == $location) {
           				$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div>';
						if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$levelKey]) {
							$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';
						} else {
							$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display ' . $detailKey . '.</div></div>';
						}
           		}
           	}
           	if ( ($detailKey == strtolower(constant('ERROR'))) ) {
           		$detailKey = strtolower($detailKey);
   				$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label pmp-error">'.constant('ERROR').'</div>';
				$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">No Details Configured!</div></div>';
			}
        }            
	}

	if ($this->displayIcons == 1) {
		if (array_key_exists(constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_detail_icons_max', $this->generalOptions)) {
			$maxIcons = $this->generalOptions[constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_animal_detail_icons_max'];
		} else {
			$maxIcons = 5;
		}
    	$iconString = $this->animalDetailFunction->display_pet_icons($animalDetails, $animalName, $maxIcons);
    	$output .= '<div class = "pmp-search-result pmp-animal-icons">' . $iconString . '</div>';
	}		

    //Output Animal Descrciption
    if ( array_key_exists($description, $animalDetails) ) {
    	$dscArray = (array)$animalDetails[$description];
    	$fullDescription = $dscArray['value'];
    	$dscLabel = $dscArray['label'];
    	//echo 'Full Animal Description = ' . $fullDescription . '<br>';
	    if ($fullDescription <> $empty) {
			if (substr($dscLabel, -2) == ': ') {
				$descriptionLabel = rtrim($dscLabel, ': ');
			} else {
				$descriptionLabel = $dscLabel;
			}
		   	$output .= '<div class="row pmp-detail-result pmp-detail-' .$description.'"><div class="col pmp-detail-label pmp-detail-'.$description.'-label">' . $descriptionLabel . ' ' . $animalName . '</div></div>';
			
			if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[constant('LEVEL_PREFIX_ANIMAL_DETAIL') . $description . '_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST')]) {
		   		$output .= '<div class="col pmp-detail-value pmp-detail-'.$description.'-value">'.$fullDescription.'</div>';
			} else {
				$output .= '<div class="col pmp-detail-value pmp-detail-'.$description.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display description.</div>';
			}	   	
	    }
	}

    $conversionButtonLevelKey = 'level_buttons_conversion_' . constant('ADOPT_METHODTYPE_ANIMALSFIRST');

	/* Initialize On Click Parameters */		
	$gaName = 'button_pmp_detail_select';	
	$gaParamArray['event_category'] = 'Button';

    /*Add Additional Buttons */
	$output .= '<div id="pmp-details-other-buttons-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '" class="pmp-details-other-buttons">';
	$hours_link = do_shortcode('[pmp-option type="contact" value="' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '_hours_link"]');
	if ( empty($hours_link) ) {
		$hours_link = '/';
	}
	//echo 'Hours Link = ' . $hours_link . '<br>';
		
    // Add Come Visit Button 
    $visitTitle = 'Come Visit ';
    $visitLabel = 'Come Visit<br>';
    if ( (isset($location_value)) && ($location_value == $shelter) && (!is_null($hours_link)) ) {	
		$visitTitle .= $animalName;
		$visitLabel .= $animalName;
	} else {
		$visitTitle .= 'the Shelter';
		$visitLabel .= 'the Shelter';
	}
	
    if ( (!is_null($visitTitle)) && (!is_null($visitLabel)) ) {	
   		$output .= '<div class="pmp-detail-other-button pmp-detail-come-visit">';    
    	$clickID = 'pmp-detail-come-visit-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '-' . $animalSpeciesLower . '-' . str_replace(" ", "-", $animalNameLower);
		if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$conversionButtonLevelKey]) {
	   		$gaParamArray['click_id'] = $clickID;    			
	   		$clickText = $visitLabel;
	   		$gaParamArray['click_text'] = $clickText;    	    
    		$clickURL = $hours_link;
    		$gaParamArray['click_url'] = $clickURL;
    		$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  	
    		$output .= '<a id="' . $clickID . '" class="pmp-button pmp-detail-come-visit-button" href="' . $clickURL . '" title="' . $visitTitle . '" onclick="' . $onClick . '">' . $clickText . '</a>';
		} else {
	   		$clickText = 'Upgrade to Use<br>Conversions';
    		$clickURL = constant('PMP_LOGIN');
    		$output .= '<a id="' . $clickID . '" class="pmp-button pmp-detail-email-us-button pmp-notice-upgrade-license" href="' . $clickURL . '" title="Login to PetMatchPro.com" target="_blank">' . $clickText .'</a>';
		}    		
       	$output .= '</div> <!-- .pmp-detail-come-visit -->';    	
	}

    //Add Button to Return to Search Results
    if ($referer == constant('REFERER_SEARCH')) {
    	$clickID = 'pmp-detail-search-return-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . '-' . $animalSpeciesLower . '-' . str_replace(" ", "-", $animalNameLower) ;
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

    $output .= '    </div> <!-- #pmp-details-other-buttons-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . ' -->';         
    $output .= '  </div> <!-- #pmp-details-results-wrapper-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . ' -->';
    $output .= '</div> <!-- #pmp-details-wrapper-' . constant('ADOPT_METHODTYPE_ANIMALSFIRST') . ' -->';
?>