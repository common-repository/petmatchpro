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

//	let jqueryParams=[],jQuery=function(r){return jqueryParams=[...jqueryParams,r],jQuery},$=function(r){return jqueryParams=[...jqueryParams,r],$};window.jQuery=jQuery,window.$=jQuery;let customHeadScripts=!1;jQuery.fn=jQuery.prototype={},$.fn=jQuery.prototype={},jQuery.noConflict=function(r){if(window.jQuery)return jQuery=window.jQuery,$=window.jQuery,customHeadScripts=!0,jQuery.noConflict},jQuery.ready=function(r){jqueryParams=[...jqueryParams,r]},$.ready=function(r){jqueryParams=[...jqueryParams,r]},jQuery.load=function(r){jqueryParams=[...jqueryParams,r]},$.load=function(r){jqueryParams=[...jqueryParams,r]},jQuery.fn.ready=function(r){jqueryParams=[...jqueryParams,r]},$.fn.ready=function(r){jqueryParams=[...jqueryParams,r]};

</script>

<?php

	/* Secure Paid Features */

	//$this->PMPLicenseTypeID = 3;  

	//echo 'License ID = ' . $this->PMPLicenseTypeID . '<br>';



	/* Get Field Visibility Levels by License Type */

    $levelsFile = 'pmp-field-levels-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '.php';

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



	//echo '<pre>ADOPT DETAILS<br>'; print_r($animalDetails); echo '</pre>';

	$empty = constant('EMPTY_VALUE');

	

	if ( array_key_exists(constant('RESCUEGROUPS_SPECIES'), $animalDetails) ) {

		$animalSpecies = $animalDetails[constant('RESCUEGROUPS_SPECIES')]['value'];

	} else {

	 	$animalSpecies = 'species';

	}

	//echo 'Animal Species is ' . $animalSpecies . '<br>';	

	$animalSpeciesLower = strtolower($animalSpecies);

	

	if ( array_key_exists(constant('RESCUEGROUPS_NAME'), $animalDetails) ) {

		$animalName = ucwords($animalDetails[constant('RESCUEGROUPS_NAME')]['value']);

	} else {

		$animalName = 'Name';

	}

	//echo 'Animal Name is ' . $animalName . '<br>';

	$animalNameLower = strtolower($animalName);



  	$output = '';

  	$output .= '<div id="pmp-details-wrapper-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '" class="pmp-details-wrapper">';

  	

  	$output .= '<div class="row pmp-details-title-wrapper">';

  	$output .= '<div class="pmp-details-right-col">';

    //Add Button to Return to Search Results

    if ($referer == constant('REFERER_SEARCH')) {

		$gaName = 'button_pmp_detail_select';	

		$gaParamArray['event_category'] = 'Button';

    	$clickID = 'pmp-detail-search-return-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '-' . $animalSpeciesLower . '-' . str_replace(" ", "-", $animalNameLower) ;

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

  	$output .= '</div> <!-- .pmp-details-right-col-->';

  	$output .= '<div class="pmp-details-left-col">';

  	$output .= '<h1 class="pmp-adoptable-details-title">' . $animalName . '<h1>';

  	$output .= '</div> <!-- .pmp-details-left-col-->';

  	

  	$output .= '</div> <!-- .pmp-details-title-wrapper-->';

  	

  	$output .= '  <hr style="border-color: #66A436;">';

  	$output .= '  <div class="pmp-details-wrapper">';

  	$output .= '  <div class="pmp-details-media">';

	$output .= '    <div class="pmp-detail-image">';

	$photoLevelKey = constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('RESCUEGROUPS_PHOTOS') . '_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS');	
  	if ( (array_key_exists(constant('RESCUEGROUPS_PHOTOS'), $animalDetails)) && ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$photoLevelKey]) ) {

    	$output .= '      <img id="expandedImg" src="'.$animalDetails[constant('RESCUEGROUPS_PHOTOS')][0].'"><div id="imgtext"></div>';

    } else {

    	$output .= '<div class="pmp-notice-upgrade-license"><a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display photos.</div> <!-- .pmp-notice-upgrade-license -->';

    }

   	$output .= '    </div> <!-- .pmp-detail-image -->';

   	

    if ( !empty($this->PMPLicenseTypeID) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {

    	if (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_detail_thumbs_max', $this->generalOptions)) {

    		$maxThumbs = $this->generalOptions[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_detail_thumbs_max'];

    	} else {

    		$maxThumbs = 6;

    	}

    	//echo 'Max Thumbnails is ' . $maxThumbs . '<br>';    	    	

    	$output .= '    <div class="pmp-details-thumbs">';



		/* Initialize On Click Parameters */		

 		$gaName = 'image_pmp_detail_select';	

 		$gaParamArray = [];

 		$gaParamArray['event_category'] = 'Image';

 		$gaParamArray['event_action'] = 'Select';

 		$gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];



		$thumb = 0;	

		//echo 'Photo Level Key = ' . $photoLevelKey . '<br>';

	  	if ( (array_key_exists(constant('RESCUEGROUPS_PHOTOS'), $animalDetails)) && ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$photoLevelKey]) ) {		

	    	foreach($animalDetails[constant('RESCUEGROUPS_PHOTOS')] as $photoURL){

	    		if ($thumb <= $maxThumbs) {	    	

				   	$clickID = 'pmp-detail-thumb-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '-' . $animalSpeciesLower . '-' . str_replace(" ", "-", $animalNameLower) . '-' . $thumb;

				   	$gaParamArray['click_id'] = $clickID;

				   	$clickText = 'View ' . $animalName . ' the ' . $animalSpecies . ' Photo #' . $thumb;

				   	$gaParamArray['click_text'] = $clickText;

				   	$clickURL = $photoURL;

				   	$gaParamArray['click_url'] = $clickURL;

				   	$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  	

					if (!empty($onClick)) {

						$onClick = ' ' . $onClick;

				   	}

				    $output .= '<div class="pmp-imgThumb"><img id="' . $clickID . '" src="' . $clickURL . '"  alt="' . $clickText . '" title="' . $clickText . '" onclick="galleryThumb(this);' . $onClick . '"></div>';

		    	    $thumb = $thumb + 1;

		    	}

	    	}

	    } else {

	    	$output .= '<div class="pmp-notice-upgrade-license"><a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display thumbnail photos.</div> <!-- .pmp-notice-upgrade-license -->';

	    }

		$videoLevelKey = constant('LEVEL_PREFIX_ANIMAL_DETAIL') . constant('RESCUEGROUPS_VIDEOS') . '_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS');
		//echo 'Video Level Key = ' . $videoLevelKey . '<br>';
	  	if ( $this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$videoLevelKey] ) {	
	  	//echo 'Processing Video Data<br>';	
	    	if( (array_key_exists(constant('RESCUEGROUPS_VIDEOS'), $animalDetails)) ) {
	    		$videoArray = $animalDetails[constant('RESCUEGROUPS_VIDEOS')];
	    		$videoCount = count($videoArray);
	    		//echo 'There Are ' . $videoCount . ' Videos.<br>';
	    		$videoCount = $videoCount - 1;
	    		$counter = 0;
	    		if ($videoCount >= 0) {
	    			//$videoSupport = 'video-support.php';
	   				//$requireFile = constant("PET_MATCH_PRO_PATH") . '/' . constant('PUBLIC_DIR') . '/' . constant('VIDEO_DIR') . '/' . $videoSupport;
	    			//echo 'Video Support File = ' . $requireFile . '<br>';
	    			//require $requireFile;	   
	    			
					$modalVideoCSS = get_home_url() . '/wp-content/plugins/' . constant('PLUGIN_DIR') . '/' . constant('PUBLIC_DIR') . '/' . constant('VIDEO_DIR') . '/' . constant('CSS_DIR') . '/modal-video.min.css';
					//echo 'Modal CSS = ' . $modalVideoCSS . '<br>';
					$modalVideoJS = get_home_url() . '/wp-content/plugins/' . constant('PLUGIN_DIR') . '/' . constant('PUBLIC_DIR') . '/' . constant('VIDEO_DIR') . '/' . constant('SCRIPT_DIR') . '/jquery-modal-video.min.js';
					//echo 'Modal JS = ' . $modalVideoJS . '<br>';
					echo '<link rel="stylesheet" href="' . $modalVideoCSS . '">';
					echo '<script src="' . $modalVideoJS . '"></script>';
 		
	    			while ($counter <= $videoCount) {
	    				if ( (array_key_exists($counter, $videoArray)) && ($videoArray[$counter] != '') ) {
				    		//echo 'Preparing Video Click Details<br>';
						   	$gaParamArray['event_category'] = 'YouTube';
						   	$gaParamArray['event_action'] = 'Play';
						   	$idCounter = $counter + 1;
						   	$clickID = 'pmp-detail-video-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '-' . $animalSpeciesLower . '-' . str_replace(" ", "-", $animalNameLower) . '-' . $idCounter;
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
						   	
    						echo '<script>
								jQuery(document).one("ready",function(){							
								jQuery(".pmp-detail-video-play-icon.pmp-video-' . $idCounter . '").modalVideo({
									youtube:{
									controls:0,
									nocookie: true
									},
     	 						url:"'.$clickURL.'?rel=1&autoplay=1"
		 						});});
								</script>';

							$output.= '<div class="pmp-imgThumb"><a href="#" class="pmp-detail-video-play-icon pmp-video-' . $idCounter . '" id="' . $clickID . '" data-channel="custom" title="'. $clickText .'" onclick="' . $onClick . '"></a></div>';						   	
//							$output.= '<div class="pmp-imgThumb"><a href="' . $clickURL . '" class="pmp-detail-video-play-icon pmp-video-' . $idCounter . '" id="' . $clickID . '" data-channel="custom" title="'. $clickText .'" onclick="' . $onClick . '"></a></div>';						   	
//							$output.= '<a href="' . $clickURL . '" class="pmp-detail-video-play-icon" id="" data-channel="custom" title="'. $clickText .'" onclick="' . $onClick . '"></a>';
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



	$posterPage = $this->generalOptions['details_page_poster'];

	//echo 'Poster Page is ' . $posterPage . '<br>';

	$posterURL = get_permalink($posterPage);

	//echo 'Poster URL = ' . $posterURL . '<br>';

   	

 	if ( ($this->PMPLicenseTypeID <= constant('FREE_LEVEL')) && (!is_null($posterURL)) ) {

	    $output .= '<div class="pmp-details-features-wrapper">'; 		

	    $output .= '<div class="pmp-details-features">'; 		 		

	    $output .= '<div id="pmp-print-poster-button">'; 		

    	/* Configure Onclick Parameter */

 		$gaName = 'button_pmp_detail_select';	    

    	$gaParamArray['event_category'] = 'Button';

    	$gaParamArray['event_action'] = 'Select';

   		$clickID = 'pmp-detail-print-poster-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '-' . $animalSpeciesLower . '-' . str_replace(" ", "-", $animalNameLower);

   		$gaParamArray['click_id'] = $clickID;

   		$clickText = 'Print ' . $animalName . "'s Poster";

   		$gaParamArray['click_text'] = $clickText;

 		$clickURL = $posterURL . '?method=' . $callFunc . '&' . str_replace('id', 'ID', constant('RESCUEGROUPS_ID')) . '='. $_GET[str_replace('id', 'ID', constant('RESCUEGROUPS_ID'))];

		//echo 'Click URL = ' . $clickURL . '<br>';

   		$gaParamArray['click_url'] = $clickURL;

   		$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  

    	$output .= '	<a id="' . $clickID . '" target="_blank" class="pmp-button pmp-detail-print-poster-button" href="' . $clickURL . '" title="Click to Print ' . $animalName . "'s" . ' Poster" onclick="' . $onClick . '">' . $clickText . '</a>';

    	$output .= '</div> <!-- #pmp-print-poster-button-->';

    	$output .= '</div> <!-- .pmp-details-features-->';

    	$output .= '</div> <!-- .pmp-details-feature-wrapper-->';

    }

     		

    $output .= '  </div> <!-- .pmp-details-media -->';



   	$output .= '  <div id="pmp-details-results-wrapper-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '" class="pmp-details-results-wrapper">'; 



	/* Get Foster & Shelter Locations Names */

	$locationFoster = do_shortcode('[pmp-option type="filter" value="location_foster"]');

	//echo 'Foster Location is ' . $locationFoster . '<br>';

	$locationShelter = do_shortcode('[pmp-option type="filter" value="location_shelter"]');

	//echo 'Shelter Location is ' . $locationShelter . '<br>';

	$location_value = '';

		

	if ( array_key_exists(constant('RESCUEGROUPS_SIZE'), $animalDetails) ) {

		$bodyWeight = $animalDetails[constant('RESCUEGROUPS_SIZE')]['value'];

		if ( $bodyWeight == 0 ) {

			$animalDetails[constant('RESCUEGROUPS_SIZE')]['value'] = $empty;

			$bodyWeight = $empty;

		}	

	}



	//echo '<pre>SHOW ITEMS<br>'; print_r($showItems); echo '</pre>';

	//echo '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';

	foreach($showItems as $detailKey){

        $cat = 'Cat';

        $dog = 'Dog';

      	$declawed = constant('RESCUEGROUPS_DECLAWED');

      	$description = constant('RESCUEGROUPS_DESCRIPTION'); 

      	//echo 'Description Field is ' . $description . '<br>';

       	$foster_home = $locationFoster;

       	$shelter = $locationShelter;

       	$housetrained = constant('RESCUEGROUPS_HOUSETRAINED');

       	$location = constant('RESCUEGROUPS_LOCATION');

       	$photo = constant('RESCUEGROUPS_PHOTOS');

       	$video = constant('RESCUEGROUPS_VIDEOS');

    	if(array_key_exists($detailKey, $animalDetails)){

    		$levelKey = constant('LEVEL_PREFIX_ANIMAL_DETAIL') . $detailKey . '_'  . constant('ADOPT_METHODTYPE_RESCUEGROUPS');

    		//echo 'Level Key = ' . $levelKey . '<br>';

    		//echo 'Level for Key ' . $detailKey . ' is ' . $pmpFieldLevelsAdopt[$levelKey] . '<br>';

    		

           	if ( (!is_int(strpos($detailKey, $description))) && ($detailKey <> $video) && ($detailKey <> $photo) && ($detailKey <> strtolower(constant('ERROR'))) ) {

           		if ( ($detailKey <> $declawed) && ($detailKey <> $housetrained) && ($detailKey <> $location) )   {

           			$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div>';

					if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$levelKey]) {

						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';

					} else {

						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display ' . $detailKey . '.</div></div>';

					}

           		} elseif ( ($detailKey == $declawed) && ($animalSpecies == $cat) ) {

           			$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div>';

					if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$levelKey]) {

						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';

					} else {

						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display ' . $detailKey . '.</div></div>';

					}

           		} elseif ( ($detailKey == $housetrained) && ($animalDetails[constant('RESCUEGROUPS_SPECIES')]['value'] == $dog) ) {

           			$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div>';

					if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$levelKey]) {

						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';

					} else {

						$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display ' . $detailKey . '.</div></div>';

					}

				} elseif ($detailKey == $location) {

				  		$location_value = $animalDetails[$detailKey]['value'];

           				$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div>';

						if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$levelKey]) {

							$output .= '<div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">'.$location_value.'</div></div>';

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

		//echo 'Preparing to Display Icons<br>';

		if (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_detail_icons_max', $this->generalOptions)) {

			$maxIcons = $this->generalOptions[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_animal_detail_icons_max'];

		} else {

			$maxIcons = 5;

		}

		//echo 'Max Icons = ' . $maxIcons . '<br>';

		//print_r($resultsArray);

       	//echo '<pre>ICON FUNCTION CALLED WITH<br>'; print_r($resultsArray[$counter]); echo '</pre>';

    	$iconString = $this->animalDetailFunction->display_pet_icons($animalDetails, $animalName, $maxIcons);

    	$output .= '<div class = "pmp-search-result pmp-animal-icons">' . $iconString . '</div>';



	}

	

	$posterPage = $this->generalOptions['details_page_poster'];

	$posterURL = get_permalink($posterPage);

	$socialShareLevelKey = 'level_social_share_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS');

 	if ( ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$socialShareLevelKey]) && (array_key_exists('social_share', $this->contactOptions)) ) {		

		$output .= '<div class="pmp-details-features-wrapper">';        

	    $output .= '<div class="pmp-details-features">';

	    $output .= '<div id="pmp-social-share-icons">';

		$output .= '<div class="pmp-social-share-title">Share ' . $animalName . ' with Your Network</div> <!-- .pmp-social-share-title-->';	    

	    $output .= do_shortcode('[pmp-social-share]');

	    $output .= '</div> <!-- #pmp-social-share-icons-->';  

    	$output .= '</div> <!-- .pmp-details-features-->';

    	$output .= '</div> <!-- .pmp-details-features-wrapper-->';

	} 

		

    $output .= '  </div> <!-- #pmp-details-results-wrapper-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . ' -->';

    

    $output .= '  </div> <!-- .pmp-details-wrapper -->';

   	$output .= '  <hr style="clear:both; border-color: #66A436;">';

        

    //Output Animal Descrciption

    //echo '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';

    $descriptionKey = '';

    $detailKeys = array_keys($animalDetails);  

	//echo '<pre>ANIMAL DETAIL KEYS<br>'; print_r($detailKeys); echo '</pre>';

    foreach($detailKeys as $key => $value) {

    	//echo 'Processing Key ' . $value . ' with string position ' . strpos($value, constant('RESCUEGROUPS_DESCRIPTION')) . '<br>';

    	if (is_int(strpos($value, constant('RESCUEGROUPS_DESCRIPTION')))) {

        	$descriptionKey = $value;

        }

    }   

	//echo 'Description Key is ' . $descriptionKey . '<br>';

    if ( (!is_null($descriptionKey)) && (array_key_exists($descriptionKey, $animalDetails)) ) {

      	//echo 'Description Field is ' . $descriptionKey . '<br>';    

    	$dscArray = (array)$animalDetails[$descriptionKey];

    	$fullDescription = $dscArray['value'];

    	$dscLabel = $dscArray['label'];

    	//echo 'Full Animal Description = ' . $fullDescription . '<br>';

	    if ($fullDescription <> $empty) {

			if (substr($dscLabel, -2) == ': ') {

				$descriptionLabel = rtrim($dscLabel, ': ');

			} else {

				$descriptionLabel = $dscLabel;

			}

			$output .= '<div class="row pmp-detail-description-wrapper">';		

		   	$output .= '<div class="row pmp-detail-result pmp-detail-' .$descriptionKey.'"><div class="col pmp-detail-label pmp-detail-'.$descriptionKey.'-label">' . $descriptionLabel . ' ' . $animalName . '</div></div>';

			

			if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[constant('LEVEL_PREFIX_ANIMAL_DETAIL') . $descriptionKey . '_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS')]) {

		   		$output .= '<div class="col pmp-detail-value pmp-detail-dsc-value">'.$fullDescription.'</div>

				';

			} else {

				$output .= '<div class="col pmp-detail-value pmp-detail-'.$descriptionKey.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display description.</div>';

			}	 

			$output .= '</div> <!-- .pmp-detail-description-wrapper-->';		

	    }

	}

	

	/*Add Conversion Buttons */
    $conversionButtonLevelKey = 'level_buttons_conversion_' . constant('ADOPT_METHODTYPE_RESCUEGROUPS');

	$output .= '<div class="pmp-details-buttons-wrapper">';

	$output .= '<div id="pmp-details-conversion-buttons-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '" class="pmp-details-conversion-buttons" style="width:100%;">';

	$application_link = do_shortcode('[pmp-option type="contact" value="' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_link"]');



    /* Configure Onclick Parameter */

 	$gaName = 'button_pmp_detail_select';	 

	$gaParamArray = [];   

    $gaParamArray['event_category'] = 'Button';

    $gaParamArray['event_action'] = 'Select';

	$gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];    

   	$clickID = 'pmp-detail-application-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '-' . $animalSpeciesLower . '-' . str_replace(" ", "-", $animalNameLower); 

   	$gaParamArray['click_id'] = $clickID;       	   



    //Add Application Button

    //echo 'License Type is ' . $this->PMPLicenseTypeID . '<br>';

    //echo 'Conversion Buttons License Level is ' . $pmpFieldLevelsAdopt['level_buttons_conversion_adopt'] . '<br>';

    if ( (!is_null($application_link)) ) {

		$output .= '<div class="pmp-detail-conversion-button pmp-detail-application" style="float: none; margin-left:auto !important; margin-right:auto !important">';               

		if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$conversionButtonLevelKey]) {

	   		$clickText = 'Complete the ' . ucfirst(constant('ADOPT_METHODTYPE_RESCUEGROUPS')) . ' Application';

	   		$gaParamArray['click_text'] = $clickText;    	    

    		$clickURL = $application_link;

    		$gaParamArray['click_url'] = $clickURL;

    		$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  	

    		$output .= '<a id="' . $clickID . '" class="pmp-button pmp-detail-application-button" href="' . $clickURL . '" title="Adopt ' . $animalName . '" onclick="' . $onClick . '">' . $clickText .'</a>';

    	} else {

	   		$clickText = 'Upgrade to Use<br>Conversions';

    		$clickURL = constant('PMP_LOGIN');

    		$output .= '<a id="' . $clickID . '" class="pmp-button pmp-detail-application-button pmp-notice-upgrade-license" href="' . $clickURL . '" title="Login to PetMatchPro.com" target="_blank">' . $clickText .'</a>';

		}    	

       	$output .= '</div> <!-- .pmp-detail-application -->';    	

	}

    $output .= '</div> <!-- #pmp-details-conversion-buttons-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . ' -->';  

    $output .= '</div> <!-- .pmp-details-buttons-wrapper -->';      

   	$output .= '  <hr style="clear:both; border-color: #66A436;">';



	/*Add Sponsorship Image */

	$sponsorshipLink = do_shortcode('[pmp-option type="contact" value="sponsor_link"]');

	$sponsorshipImage = do_shortcode('[pmp-option type="contact" value="sponsor_image"]');

	

    if ( (!is_null($sponsorshipImage)) ) {

		if ($this->PMPLicenseTypeID <= $pmpFieldLevelsAdopt[$conversionButtonLevelKey]) {

			$output .= '<div class="pmp-sponsorship-wrapper">';

	    	/* Configure Onclick Parameter */

			$gaParamArray = [];   

	 		$gaName = 'image_pmp_detail_select';	

	 		$gaParamArray['event_category'] = 'Image';

	    	$clickID = 'pmp-detail-sponsor-image-' . $animalSpeciesLower . '-' . str_replace(" ", "-", $animalNameLower) ;

	    	$gaParamArray['click_id'] = $clickID;

	 		$gaParamArray['event_action'] = 'Select';

	    	$clickText = 'Click to View Website';

	    	$gaParamArray['click_text'] = $clickText;

	    	$clickURL = $sponsorshipLink;

	    	$gaParamArray['click_url'] = $clickURL;

	    	$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  	

		

			/* Determine Anchor Target */

			if ( str_contains($sponsorshipLink, $homeURL) ) {

				$target = '_self';

			} else {

				$target = '_blank';

			}



    		$output .= '<a id="' . $clickID . '" class="pmp-image pmp-sponsor-image" href="' . $clickURL . '" target = "' . $target . '" title="' . $clickText . '" onclick="' . $onClick . '"><img src="' . $sponsorshipImage .'" alt="Sponsor Image"></a>';

    	} else {

	   		$clickText = 'Upgrade to View<br>Sponsor Image';

    		$clickURL = constant('PMP_LOGIN');

    		$output .= '<a id="' . $clickID . '" class="pmp-button pmp-detail-application-button pmp-notice-upgrade-license" href="' . $clickURL . '" title="Login to PetMatchPro.com" target="_blank">' . $clickText .'</a>';

		}    	

       	$output .= '</div> <!-- .pmp-sponsorship-wrapper -->';    	

	}          

    $output .= '</div> <!-- #pmp-details-wrapper-' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . ' -->';	

?>