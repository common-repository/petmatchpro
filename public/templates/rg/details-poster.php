<?php

	//echo 'Called Details Poster Template<br>';

	//echo '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';

	$ID = $_GET[str_replace('id', 'ID', constant('RESCUEGROUPS_ID'))];

	$method = $_GET['method'];	
	
	if (strtolower($method) == 'lostdetails') {
		$methodType = 'lost';
	} elseif (strtolower($method) == 'founddetails') {
		$methodType = 'found';
	} else {
		$methodType = constant('ADOPT_METHODTYPE_RESCUEGROUPS');
	}
	
	//echo '<pre>CONTACT OPTIONS<br>'; print_r($this->contactOptions); echo '</pre>';

	$adoptPhone = '';

	if (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_phone', $this->contactOptions)) {

		$adopt_phone = do_shortcode('[pmp-option type="contact" value="' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_phone"]');

		$adoptPhone = '(' . substr($adopt_phone,0,3) . ') ' . substr($adopt_phone,3,3) . '-' . substr($adopt_phone,6,4);

	}

	$fosterEmail = '';

	if (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_foster_email', $this->contactOptions)) {

		$fosterEmail = do_shortcode('[pmp-option type="contact" value="' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_foster_email"]');  

	}

	$vanityFoster = '';

	if (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_foster_vanity_link', $this->contactOptions)) {

   		$vanityFoster = do_shortcode('[pmp-option type="contact" value="' . constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_foster_vanity_link"]');	

   	}

   	

	$foundEmail = '';

	if (array_key_exists('found_email', $this->contactOptions)) {

		$foundEmail = do_shortcode('[pmp-option type="contact" value="found_email"]'); 

	}



	$name = do_shortcode('[pmp-detail detail="' . constant('RESCUEGROUPS_NAME') . '" case="mixed"]');

	

	if (strlen($name) > 0) {

		$animalName = ucwords($name);

	} else {

		$animalName = ucwords(constant('RESCUEGROUPS_NAME'));

	}



	/* Get Foster & Shelter Locations Names */

	$locationFoster = do_shortcode('[pmp-option type="filter" value="location_foster"]');

	//echo 'Foster Location is ' . $locationFoster . '<br>';

	$locationShelter = do_shortcode('[pmp-option type="filter" value="location_shelter"]');

	//echo 'Shelter Location is ' . $locationShelter . '<br>';



	/* Output Media */

	//echo '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';

	if (array_key_exists(constant('RESCUEGROUPS_LOCATION'), $animalDetails)) {

		$location_value = $animalDetails[constant('RESCUEGROUPS_LOCATION')]['value'];

	} else {

		$location_value = '';

	}

	if ( str_contains($location_value, 'foster') ) {

		$inFoster = 'Yes';

	} else {

		$inFoster = 'No';

	}



	$species = do_shortcode('[pmp-detail detail="' . constant('RESCUEGROUPS_SPECIES') . '" case="mixed"]');

	//echo 'Species = ' . $species . '<br>';

	

	if (array_key_exists(constant('RESCUEGROUPS_DATE_BIRTH'), $animalDetails)) {

		$age = $animalDetails[constant('RESCUEGROUPS_DATE_BIRTH')]['value'];

	} else {

		$age = 0;

	}

	//echo 'Age = ' . $age . '<br>';

	if (substr($age, 0, 1) == 1) {

		$ageFormatted = str_replace('(s)', '', $age);

	} else {

		$ageFormatted = str_replace(array( '(', ')' ), '', $age);

	}

	$sex = do_shortcode('[pmp-detail detail="' . constant('RESCUEGROUPS_SEX') . '" case="mixed"]');

	$breed = do_shortcode('[pmp-detail detail="' . constant('RESCUEGROUPS_BREED') . '" case="mixed"]');



	if (str_contains(strtolower($location_value), 'foster')) {

		$header1 = 'Provide ' . $animalName; 

		$header2 = 'With a Loving Home';

	} else {

		$header1 = $animalName;

		$header2 = '(Needs a Foster)';

	}

	

	$output = '';

	$output .= '<div id="pmp-poster-details-wrapper">';

	$output .= '	<div id="pmp-poster-heading-wrapper">';	

	$output .= '		<div class="pmp-poster-heading-1"><h2>' . $header1 . '</h2></div>';

	$output .= '		<div class="pmp-poster-heading-2"><h3>' . $header2 . '</h3></div>';

    $output .= '	</div> <!-- #pmp-poster-heading-wrapper -->';



	$empty = constant('EMPTY_VALUE');



	if (array_key_exists(constant('RESCUEGROUPS_SIZE'), $animalDetails)) {

		$bodyWeight = $animalDetails[constant('RESCUEGROUPS_SIZE')]['value'];

	} else {

		$bodyWeight = 0;

	}

	if ( $bodyWeight == 0 ) {

		$animalDetails[constant('RESCUEGROUPS_SIZE')]['value'] = $empty;

		$bodyWeight = $empty;

	}



	$output .= '	<div id="pmp-poster-main-content-wrapper">';     

	$output .= '		<div class="pmp-poster-media">';     

    $output .= '			<div class="pmp-animal-detail-image">';

    if (array_key_exists(constant('RESCUEGROUPS_PHOTOS'), $animalDetails)) {

    	$output .= '				<img id="expandedImg" src="'.$animalDetails[constant('RESCUEGROUPS_PHOTOS')][0].'">';

    } else {

    	$output .= '				<img id="expandedImg" src="">';    

    }

    $output .= '			</div> <!-- .pmp-animal-detail-image -->';        

    $output .= '		</div> <!-- .pmp-poster-media -->';

	

	$output .= '  		<div class="pmp-poster-details">'; 



	//echo '<pre>SHOW ITEMS<br>'; print_r($showItems); echo '</pre>';

	//echo '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';

    $cat = 'Cat';

    $dog = 'Dog';              	

   	$declawed = constant('RESCUEGROUPS_DECLAWED');

   	//echo 'Declawed Constant = ' . $declawed . '<br>';

   	$description = constant('RESCUEGROUPS_DESCRIPTION');       	

   	$foster_home = $locationFoster;

   	$shelter = $locationShelter;

   	$housetrained = constant('RESCUEGROUPS_HOUSETRAINED');

   	$location = constant('RESCUEGROUPS_LOCATION');

   	$photo = constant('RESCUEGROUPS_PHOTOS');

   	$video = constant('RESCUEGROUPS_VIDEOS');

       	

   	$emptyDate = '01-01-1970';

    foreach($showItems as $detailKey){

   		if(array_key_exists($detailKey, $animalDetails)){

	       	//echo 'START Processing Detail Key ' . $detailKey . '<br>';   		

   			if ( (str_contains(strtolower($detailKey), 'date')) && ($animalDetails[$detailKey]['value'] == $emptyDate) ) {

   				$animalDetails[$detailKey]['value'] = $empty;

   			}	

           	if ( ($detailKey <> $description) && ($detailKey <> $video) && ($detailKey <> $photo) && ($detailKey <> constant('RESCUEGROUPS_NAME')) && ($animalDetails[$detailKey]['value'] != $empty) ) {

           		if ( ($detailKey <> $declawed) && ($detailKey <> $housetrained) && ($detailKey <> $location) )   {

           			$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';

           		} elseif ( ($detailKey == $declawed) && ($species == $cat) ) {

						$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';		

           		} elseif ( ($detailKey == $housetrained) && ($species == $dog) ) {

						$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';		

				} elseif ($detailKey == $location) {

				  		$location_value = $animalDetails[$detailKey]['value'];

				  		$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$location_value.'</div></div>';		

				}

           	} 

	       	//echo 'END Processing Detail Key ' . $detailKey . '<br>';   		

        }  

	}          

    $output .= '  		</div> <!-- .pmp-poster-details -->';



    $output .= '	</div> <!-- #pmp-poster-main-content-wrapper -->';



	//Output Animal Descrciption



   	$output .= '  <div id="pmp-poster-details-description-wrapper">'; 

    if ( (array_key_exists($description, $animalDetails)) ) {

    	if ($animalDetails[$description]['value'] <> $empty) {

	  		$output .= '<div class="col pmp-detail-value pmp-animal-detail-'.$description.'-value">'.$animalDetails[$description]['value'].'</div>';

	  	} else {

	  		$output .= '<div class="col pmp-detail-value pmp-animal-detail-'.$description.'-value">'.constant('EMPTY_VALUE').'</div>';

    	}

	} else {
		if (array_key_exists('default_description', $this->generalOptions)) {
			$output .= '<div class="col pmp-detail-value pmp-animal-detail-'.$description.'-value">'.$this->generalOptions['default_description'].'</div>';
		} else {
	  		$output .= '<div class="col pmp-detail-value pmp-animal-detail-'.$description.'-value">'.constant('EMPTY_VALUE').'</div>';
	  	}
	}

    $output .= '  </div> <!-- #pmp-poster-details-description-wrapper -->';



	/*Output Additional Photos and QR Code */

	$output .= '	<div id="pmp-poster-details-media-footer-wrapper" class="pmp-poster-details-media-footer-wrapper">';



   	if ( array_key_exists(constant('RESCUEGROUPS_PHOTOS'), $animalDetails) ) {

	   	if ( array_key_exists(0, $animalDetails[constant('RESCUEGROUPS_PHOTOS')]) ) {

	   		$output .= '		<div class="pmp-poster-photo">';   

	   		$output .= '      		<img class="pmp-poster-photo-1" src="' . $animalDetails[constant('RESCUEGROUPS_PHOTOS')][0] . '">';

	       	$output .= '    	</div> <!-- .pmp-poster-photo -->';    	

	   	}

	

	   	if ( array_key_exists(1, $animalDetails[constant('RESCUEGROUPS_PHOTOS')]) ) {

	   		$output .= '    	<div class="pmp-poster-photo">';   

	   		$output .= '      		<img class="pmp-poster-photo-1"src="' . $animalDetails[constant('RESCUEGROUPS_PHOTOS')][1] . '">';

	       	$output .= '    	</div> <!-- .pmp-poster-photo -->';    	

	   	}

	}

    	/* Determine QR Code URL */
		//echo 'Method Type is ' . $methodType . '.<br>';
		$optionType = 'general';
		if ($methodType != constant('ADOPT_METHODTYPE_RESCUEGROUPS')) {
			$detailPageKey = 'details_page_' . $methodType;
			$titleText = 'Click Here to Visit ' . $animalName . ' Online';
			$displayText = '<br>Scan to View<br>' . $animalName . ' Online';
			$cta2Text = 'Scan the QR Code to Visit ' . $animalName . ' Online.';			
		} else {
			if ( (array_key_exists(constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_link', $this->contactOptions)) && (strlen(trim($this->contactOptions[constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_link'])) > 0) ) {
				$optionType = 'contact';				
				$detailPageKey = constant('ADOPT_METHODTYPE_RESCUEGROUPS') . '_link';
				$titleText = 'Click Here to Complete an Adoption Application for ' . $animalName;
				$displayText = '<br>Scan to Adopt<br>' . $animalName;
				$cta2Text = 'Scan the QR Code to Complete an Online Adoption Application for ' . $animalName . '.';								
			} else {
				$detailPageKey = 'details_page_' . $methodType;
				$titleText = 'Click Here to Visit ' . $animalName . ' Online';
				$displayText = '<br>Scan to View<br>' . $animalName . ' Online';
				$cta2Text = 'Scan the QR Code to Visit ' . $animalName . ' Online.';			
			}
		}
		//echo 'Detail Page Key is ' . $detailPageKey . '<br>';

		$pmpOption = '[pmp-option type="' . $optionType . '" value="' . $detailPageKey . '"]';
//		$pmpOption = '[pmp-option type="general" value="' . $detailPageKey . '"]';
		//echo 'PMP Option is ' . $pmpOption . '.<br>';

		$detailPage = do_shortcode($pmpOption);
		//echo 'Detail Page is ' . $detailPage . '.<br>';

		if ($optionType == 'general') {
			$detailPageURL = get_permalink($detailPage);
			$qrURL = $detailPageURL . '?method=' . $method . '&animalID=' . $ID;		
		} else {
			$detailPageURL = $detailPage;
			$qrURL = $detailPageURL;
		}
		//echo 'Detail Page URL is ' . $detailPageURL . '.<br>';

//		$qrURL = $detailPageURL . '?method=' . $method . '&animalID=' . $ID;		

		//echo 'QR Code URL is ' . $qrURL . '.<br>';

   	$output .= '		<div id="pmp-poster-qr-code">';

	$referrerURL = $_SERVER['HTTP_REFERER'];
	$urlParamPosition = strpos($referrerURL, '?');
	if (is_int($urlParamPosition)) {
		$baseReferrerURL = substr($referrerURL, 0, $urlParamPosition - 1);
	} else {
		$baseReferrerURL = get_home_url();
	}
	//echo 'Base Referring URL is ' . $baseReferrerURL . '.<br>';
	$encodeURL = urlencode($baseReferrerURL);
	//echo 'Encoded Base Referring URL is ' . $encodeURL . '.<br>';
	$qrSource = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&amp;data=' . $encodeURL . '?method=' . $method . '&' . strtoupper(constant('RESCUEGROUPS_ID')) . '=' . $ID;
	//echo 'QR Source URL is ' . $qrSource . '.<br>';
    $output .= '			<a href="' . $qrURL . '" title="' . $titleText . '"><img name="QR Code" src="' . $qrSource . '"/></a>' . $displayText;

    $output .= '		</div> <!-- #pmp-poster-qr-code -->';



   	$output .= '    </div> <!-- #pmp-poster-details-media-footer-wrapper -->';  



    /*Output Call to Action */

    $hours_link = do_shortcode('[pmp-option type="contact" value="adopt_hours_link"]');	      

    $vanityVolunteer = do_shortcode('[pmp-option type="contact" value="volunteer_vanity_link"]');

    $vanityDonate = do_shortcode('[pmp-option type="contact" value="donation_vanity_link"]');   	    



    $output .= '	<div id="pmp-poster-details-call-to-action-wrapper">';



   	$cta1 = '<div id="pmp-poster-details-call-to-action-1"><span class="pmp-poster-cta-highlight">TO ADOPT ' . $animalName . '</span> Call ' . $adoptPhone . ' or<br> </div> <!-- #pmp-poster-details-call-to-action-1 -->';

	$cta2 = '<div id="pmp-poster-details-call-to-action-2">' . $cta2Text . '<br></div> <!-- #pmp-poster-details-call-to-action-2 -->';

	if ($inFoster == 'No') {

		$cta3 = '<div id="pmp-poster-details-call-to-action-3">Get Details to Foster ' . $name . ' at ' . $vanityFoster . ' or Email Us at ' . $fosterEmail . '.<br> </div><!-- #pmp-poster-details-call-to-action-3 -->';

	} else {

		$cta3 = '<div id="pmp-poster-details-call-to-action-3">Explore Additional ' . $species . "'s at " . 'www.CincyCARE' . $species . 's.org<br> </div><!-- #pmp-poster-details-call-to-action-3 -->';

	}

	$cta4 = '<div id="pmp-poster-details-call-to-action-4"><span class="pmp-poster-cta-highlight">Volunteer</span> - ' . $vanityVolunteer . ' &#x2022;' . ' <span class="pmp-poster-cta-highlight">Donate</span> - ' . $vanityDonate . '<br>  <!-- #pmp-poster-details-call-to-action-4 -->';

	$cta5 = '';



   	$output .= $cta1;

   	$output .= $cta2;

   	$output .= $cta3;

   	$output .= $cta4; 	

  	$output .= $cta5;



   	$output .= '    </div> <!-- #pmp-poster-details-call-to-action-wrapper -->';  



    $output .= '</div> <!-- #pmp-poster-details-wrapper -->';

?>