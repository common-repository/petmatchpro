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

	//echo 'License ID = ' . $this->PMPLicenseTypeID . '<br>';



	/* Get Field Visibility Levels by License Type */

    $levelsFile = 'pmp-field-levels-' . constant('LOST_METHODTYPE_PETPOINT') . '.php';

   	$requireFile = $this->partialsDir . $levelsFile;

    //echo 'Require File = ' . $requireFile . '<br>';

    require $requireFile;

    //echo '<pre> LOST FIELD LEVEL VALUES '; print_r($pmpFieldLevelsLost); echo '</pre>';



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

	$empty = constant('EMPTY_VALUE');

  	$output = '';



	if (array_key_exists(constant('PETPOINT_SPECIES'), $animalDetails)) {

		$animalSpecies = $animalDetails[constant('PETPOINT_SPECIES')]['value'];

	} else {

		$animalSpecies = ucwords(constant('PETPOINT_SPECIES'));

	}

	$animalSpeciesLower = strtolower($animalSpecies);

	

	if (array_key_exists(constant('PETPOINT_ID'), $animalDetails)) {

		$animalID = $animalDetails[constant('PETPOINT_ID')]['value'];	

	} else {

		$animalID = 0;

	}



	$output .= '<h2 id="pmp-details-title-' . constant('LOST_METHODTYPE_PETPOINT') . '" class="pmp-details-title">' .$animalDetails[constant('PETPOINT_SEX')]['value'] . ' ' . $animalSpecies . ' Lost in ' . $animalDetails[constant('PETPOINT_JURISDICTION')]['value'] . '</h2>';  	

  	$output .= '<div id="pmp-details-wrapper-' . constant('LOST_METHODTYPE_PETPOINT') . '" class="pmp-details-wrapper">';

  	$output .= '  <div class="pmp-details-media">';

	$output .= '    <div class="pmp-detail-image">'; 

  	if ( (array_key_exists(constant('PETPOINT_PHOTO'), $animalDetails)) && ($this->PMPLicenseTypeID <= $pmpFieldLevelsLost['level_detail_' . constant('PETPOINT_PHOTO') . '_' . constant('LOST_METHODTYPE_PETPOINT')]) ) {

    	$output .= '      <img id="expandedImg" src="'.$animalDetails[constant('PETPOINT_PHOTO')][0].'"><div id="imgtext"></div>';

    } else {

    	$output .= '<div class="pmp-notice-upgrade-license"><a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display photos.</div> <!-- .pmp-notice-upgrade-license -->';

    }

   	$output .= '    </div> <!-- .pmp-detail-image -->';

      

    if ( !empty($this->PMPLicenseTypeID) && ($this->PMPLicenseTypeID != constant('FREE_LEVEL')) ) {

    	if (array_key_exists(constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_thumbs_max', $this->generalOptions)) {

    		$maxThumbs = $this->generalOptions[constant('ADOPT_METHODTYPE_PETPOINT') . '_animal_detail_thumbs_max'];

    	} else {

    		$maxThumbs = 6;

    	}

    

    	$output .= '    <div class="pmp-details-thumbs">';



		/* Initialize On Click Parameters */		

 		$gaName = 'image_pmp_detail_select';	

 		$gaParamArray = [];

 		$gaParamArray['event_category'] = 'Image';

 		$gaParamArray['event_action'] = 'Select';

 		$gaParamArray['page_url'] = $_SERVER['REQUEST_URI'];

 		

		$thumb = 0;		

	  	if ( (array_key_exists(constant('PETPOINT_PHOTO'), $animalDetails)) && ($this->PMPLicenseTypeID <= $pmpFieldLevelsLost['level_detail_' . constant('PETPOINT_PHOTO') . '_' . constant('LOST_METHODTYPE_PETPOINT')]) && ($thumb <= $maxThumbs) ) {

	    	foreach($animalDetails[constant('PETPOINT_PHOTO')] as $photoURL){

	    		if ($thumb <= $maxThumbs) {	    		    		    	

				   	$clickID = 'pmp-detail-thumb-' . constant('LOST_METHODTYPE_PETPOINT') . '-' . $animalSpeciesLower . '-' . $animalID . '-' . $thumb;

				   	$gaParamArray['click_id'] = $clickID;

				   	$clickText = 'View Lost ' . $animalSpecies . ' Photo #' . $thumb;

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

    	$output .= '    </div> <!-- .pmp-details-thumbs -->';

	}



 	if ( ($this->PMPLicenseTypeID <= $pmpFieldLevelsLost['level_social_share_' . constant('LOST_METHODTYPE_PETPOINT')]) && (array_key_exists('social_share', $this->contactOptions)) ) {		

		$output .= '<div id="pmp-details-features-wrapper">';        

	    $output .= '<div class="pmp-details-features">';

	    $output .= '<div id="pmp-social-share-icons">';

		$output .= '<div class="pmp-social-share-title">Share this ' . $animalSpecies . ' with Your Network</div> <!-- .pmp-social-share-title-->';	    

	    $output .= do_shortcode('[pmp-social-share]');

	    $output .= '</div> <!-- #pmp-social-share-icons-->';    

	    $output .= '</div> <!-- .pmp-details-features-->';

	    $output .= '</div> <!-- #pmp-details-features-wrapper-->';

	} 



    $output .= '  </div> <!-- .pmp-details-media -->';



   	$output .= '  <div id="pmp-details-results-wrapper-' . constant('LOST_METHODTYPE_PETPOINT') . '" class="pmp-details-results-wrapper">'; 



	//echo 'DETAILS ARRAY <pre>'; print_r($animalDetails); echo '</pre>';

	

    $cat = 'Cat';

  	$declawed = constant('PETPOINT_DECLAWED');

   	$photo = constant('PETPOINT_PHOTO');

    foreach($showItems as $detailKey){

   		if(array_key_exists($detailKey, $animalDetails)) {

    		$levelKey = constant('LEVEL_PREFIX_ANIMAL_DETAIL') . $detailKey . '_' . constant('LOST_METHODTYPE_PETPOINT');   

    		if ( is_numeric($animalDetails[$detailKey]['value']) && ($animalDetails[$detailKey]['value'] == 0) ) {

    			$animalDetails[$detailKey]['value'] = $empty;

    		}

   			if ( ($detailKey == $declawed) && ($animalDetails[constant('PETPOINT_SPECIES')]['value'] != $cat) ) {

   				$output = $output;

           	} elseif ( ($animalDetails[$detailKey]['value'] != $empty) && ($animalDetails[$detailKey] != $photo) ) {           	

				if ($this->PMPLicenseTypeID <= $pmpFieldLevelsLost[$levelKey]) {

					$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';

				} else {

					$output .= '<div class="row pmp-detail-result pmp-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-detail-'.$detailKey.'-value">' . '<a class="pmp-text-link" href="' . constant('PMP_LOGIN') . '" target="_blank" title="Login to PetMatchPro.com">Upgrade</a> to display ' . $detailKey . '.</div></div>';

				}

           	}

        }            

	}	



	/* Initialize On Click Parameters */		

	$gaName = 'button_pmp_detail_select';	

	$gaParamArray['event_category'] = 'Button';



    /*Add Additional Buttons */

	$output .= '<div id="pmp-details-other-buttons-' . constant('LOST_METHODTYPE_PETPOINT') . '" class="pmp-details-other-buttons">';



   	//Add Button to Call Shelter

	$lost_phone = $this->contactOptions[constant('LOST_METHODTYPE_PETPOINT') . '_phone'];  

	$lostEmail = $this->contactOptions[constant('LOST_METHODTYPE_PETPOINT') . '_email'];



   	if ( !is_null($lost_phone) ) {

   		$output .= '    <div class="pmp-detail-other-button pmp-detail-call-us">';       	

    	$clickID = 'pmp-detail-call-us-' . constant('LOST_METHODTYPE_PETPOINT') . '-' . $animalSpeciesLower . '-' . $animalID;

		if ($this->PMPLicenseTypeID <= $pmpFieldLevelsLost['level_buttons_conversion_' . constant('LOST_METHODTYPE_PETPOINT')]) {  

		   	$gaParamArray['click_id'] = $clickID;    	

		   	$clickText = 'Call About This ' . $animalSpecies;

		   	$gaParamArray['click_text'] = $clickText;    	    

		   	$clickURL = 'tel:' . $lost_phone;

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



   	//Add Button to Send Email

    if ( (!is_null($lostEmail)) ) {

		$output .= '<div class="pmp-detail-other-button pmp-detail-email-us">';               

		if ($this->PMPLicenseTypeID <= $pmpFieldLevelsLost['level_buttons_conversion_' . constant('LOST_METHODTYPE_PETPOINT')]) {

	   		$clickText = 'Email Us About<br>This ' . $animalSpecies;

	   		$gaParamArray['click_text'] = $clickText;    	    

    		$clickURL = 'mailto:' . $lostEmail . '?subject=IMPORTANT: Lost Inquiry for ' . $animalID . ' the ' . $animalSpecies;

    		$gaParamArray['click_url'] = $clickURL;

    		$onClick = $this->allAPIFunction->onClickValue($gaName, $gaParamArray);  	

    		$output .= '<a id="' . $clickID . '" class="pmp-button pmp-detail-email-us-button" href="' . $clickURL . '" title="Email Us About ' . $animalID . '" onclick="' . $onClick . '">' . $clickText .'</a>';

    	} else {

	   		$clickText = 'Upgrade to Use<br>Conversions';

    		$clickURL = constant('PMP_LOGIN');

    		$output .= '<a id="' . $clickID . '" class="pmp-button pmp-detail-email-us-button pmp-notice-upgrade-license" href="' . $clickURL . '" title="Login to PetMatchPro.com" target="_blank">' . $clickText .'</a>';

		}    	

       	$output .= '</div> <!-- .pmp-detail-email-us -->';    	

    } 



    //Add Button to Return to Search Results

    if ($referer == constant('REFERER_SEARCH')) {

    	$clickID = 'pmp-detail-search-return-' . constant('LOST_METHODTYPE_PETPOINT') . '-' . $animalSpeciesLower . '-' . $animalID;

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



    $output .= '    </div> <!-- #pmp-details-other-buttons-' . constant('LOST_METHODTYPE_PETPOINT') . ' -->';         

    $output .= '  </div> <!-- #pmp-details-results-wrapper-' . constant('LOST_METHODTYPE_PETPOINT') . ' -->';

    $output .= '</div> <!-- #pmp-details-wrapper-' . constant('LOST_METHODTYPE_PETPOINT') . ' -->';

?>