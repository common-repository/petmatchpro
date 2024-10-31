<?php

	$ID = $_GET[str_replace(constant('PETPOINT_ID'), strtoupper(constant('PETPOINT_ID')), constant('PETPOINT_ID_ANIMAL'))];

	$method = $_GET['method'];


	//echo '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';

	if (strtolower($method) == constant('LOST_METHODTYPE_PETPOINT') . 'details') {

		$methodType = constant('LOST_METHODTYPE_PETPOINT');

		$dateLost = do_shortcode('[pmp-detail detail="' . constant('PETPOINT_DATE_LOST') . '"]');

		$dateLost = date("m/d/Y", strtotime($dateLost));

		$lost_phone = do_shortcode('[pmp-option type="contact" value="' . constant('LOST_METHODTYPE_PETPOINT') . '_phone"]');

		$lostPhone = '(' . substr($lost_phone,0,3) . ') ' . substr($lost_phone,3,3) . '-' . substr($lost_phone,6,4);	

	    $lostEmail = do_shortcode('[pmp-option type="contact" value="' . constant('LOST_METHODTYPE_PETPOINT') . '_email"]'); 				

	} elseif (strtolower($method) == constant('FOUND_METHODTYPE_PETPOINT') . 'details') {

		$methodType = constant('FOUND_METHODTYPE_PETPOINT');

		$dateFound = do_shortcode('[pmp-detail detail="' . constant('PETPOINT_DATE_FOUND') . '"]');

		$dateFound = date("m/d/Y", strtotime($dateFound));	

		$found_phone = do_shortcode('[pmp-option type="contact" value="' . constant('FOUND_METHODTYPE_PETPOINT') . '_phone"]');

		$foundPhone = '(' . substr($found_phone,0,3) . ') ' . substr($found_phone,3,3) . '-' . substr($found_phone,6,4);	

    	$foundEmail = do_shortcode('[pmp-option type="contact" value="' . constant('FOUND_METHODTYPE_PETPOINT') . '_email"]'); 									

	} else {

		$methodType = constant('ADOPT_METHODTYPE_PETPOINT');

		$adopt_phone = do_shortcode('[pmp-option type="contact" value="' . constant('ADOPT_METHODTYPE_PETPOINT') . '_phone"]');

		$adoptPhone = '(' . substr($adopt_phone,0,3) . ') ' . substr($adopt_phone,3,3) . '-' . substr($adopt_phone,6,4);

    	$fosterEmail = do_shortcode('[pmp-option type="contact" value="' . constant('ADOPT_METHODTYPE_PETPOINT') . '_foster_email"]');  

    	$foundEmail = do_shortcode('[pmp-option type="contact" value="' . constant('FOUND_METHODTYPE_PETPOINT') . '_email"]'); 

   		$vanityFoster = do_shortcode('[pmp-option type="contact" value="' . constant('ADOPT_METHODTYPE_PETPOINT') . '_foster_vanity_link"]');	

	}

	$animalName = do_shortcode('[pmp-detail detail="' . constant('PETPOINT_NAME') . '" case="mixed"]');
	if ($animalName == constant('ERROR')) {
		$animalName = do_shortcode('[pmp-detail detail="' . constant('PETPOINT_NAME_ANIMAL') . '" case="mixed"]');
		if ($animalName == constant('ERROR')) {
			$animalName = ucwords(constant('PETPOINT_NAME'));
		}
	}
	//echo 'Animal Name is ' . $animalName . '.<br>';

	/* Get Foster & Shelter Locations Names */

	$locationFoster = do_shortcode('[pmp-option type="filter" value="location_foster"]');

	//echo 'Foster Location is ' . $locationFoster . '<br>';

	$locationShelter = do_shortcode('[pmp-option type="filter" value="location_shelter"]');

	//echo 'Shelter Location is ' . $locationShelter . '<br>';	



	/* Output Media */

	//echo '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';

	if (array_key_exists(constant('PETPOINT_LOCATION'), $animalDetails)) {

		$location_value = $animalDetails[constant('PETPOINT_LOCATION')]['value'];

	} else {

		$location_value = constant('EMPTY_VALUE');

	}

	if ( str_contains($location_value, 'foster') ) {

		$inFoster = 'Yes';

	} else {

		$inFoster = 'No';

	}


	$species = do_shortcode('[pmp-detail detail="' . constant('PETPOINT_SPECIES') . '" case="mixed"]');

	if (strlen($species) == 0) {

		$species = ucwords(constant('PETPOINT_SPECIES'));

	}
	//echo 'Species = ' . $species . '<br>';

	

	$jurisdiction = do_shortcode('[pmp-detail detail="' . constant('PETPOINT_JURISDICTION') . '" case="mixed"]');

	if (strlen($jurisdiction) == 0) {

		$jurisdiction = ucwords(constant('PETPOINT_JURISDICTION'));

	}
	//echo 'Jurisdiction = ' . $jurisdiction . '<br>';



	$primaryColor = do_shortcode('[pmp-detail detail="' . constant('PETPOINT_COLOR_PRIMARY') . '" case="mixed"]');

	if (strlen($primaryColor) == 0) {

		$primaryColor = ucwords(constant('PETPOINT_COLOR_PRIMARY'));

	}
	//echo 'Primary Color = ' . $primaryColor . '<br>';



	

	if (array_key_exists(constant('PETPOINT_AGE'), $animalDetails)) {

		$age = $animalDetails[constant('PETPOINT_AGE')]['value'];

	} else {

		$age = 0;

	}

	

	if (substr($age, 0, 1) == 1) {

		$ageFormatted = str_replace('(s)', '', $age);

	} else {

		$ageFormatted = str_replace(array( '(', ')' ), '', $age);

	}

	

	$sex = do_shortcode('[pmp-detail detail="' . constant('PETPOINT_SEX') . '" case="mixed"]');

	$breed = do_shortcode('[pmp-detail detail="' . constant('PETPOINT_BREED_PRIMARY') . '" case="mixed"]');



	if ($methodType == constant('ADOPT_METHODTYPE_PETPOINT')) {

		if (str_contains(strtolower($location_value), 'foster')) {

			$header1 = 'Provide ' . $animalName; 

			$header2 = 'With a Loving Home';

		} else {

			$header1 = $animalName;

			$header2 = '(Needs a Foster)';

		}

	} elseif ($methodType == constant('LOST_METHODTYPE_PETPOINT')) {

		$header1 = strtoupper(constant('LOST_METHODTYPE_PETPOINT')) . ' ' . strtoupper($species) ;

		$header2 = '"' . $animalName . '" (' . $ageFormatted . ')<br>' . ucfirst(constant('LOST_METHODTYPE_PETPOINT')) . ' ' . $dateLost;

	} else {

		$header1 = strtoupper(constant('FOUND_METHODTYPE_PETPOINT')) . ' ' . strtoupper($species) ;

		$header2 = $breed . ' (' . $ageFormatted . ')<br>' . ucfirst(constant('FOUND_METHODTYPE_PETPOINT')) . ' ' . $dateFound;		

	}

	

	$output = '';

	$output .= '<div id="pmp-poster-details-wrapper">';

	$output .= '	<div id="pmp-poster-heading-wrapper">';	

	$output .= '		<div class="pmp-poster-heading-1"><h2>' . $header1 . '</h2></div>';

	$output .= '		<div class="pmp-poster-heading-2"><h3>' . $header2 . '</h3></div>';

    $output .= '	</div> <!-- #pmp-poster-heading-wrapper -->';



	$empty = constant('EMPTY_VALUE');



	if ( array_key_exists(constant('PETPOINT_WEIGHT'), $animalDetails) ) {

		$bodyWeight = $animalDetails[constant('PETPOINT_WEIGHT')]['value'];

		if ( $bodyWeight == 0 ) {

			$animalDetails[constant('PETPOINT_WEIGHT')]['value'] = $empty;

			$bodyWeight = $empty;

		}	

	}



	if ($methodType == constant('ADOPT_METHODTYPE_PETPOINT')) {

  		$output .= '	<div id="pmp-poster-main-content-wrapper">';     

  		$output .= '		<div class="pmp-poster-media">';     

        $output .= '			<div class="pmp-animal-detail-image">';

    	if (array_key_exists(constant('PETPOINT_PHOTO'), $animalDetails)) {

        	$output .= '				<img id="expandedImg" src="'.$animalDetails[constant('PETPOINT_PHOTO')][0].'">';

        } else {

        	$output .= '				<img id="expandedImg" src="">';	        

        }

        $output .= '			</div> <!-- .pmp-animal-detail-image -->';        

        $output .= '		</div> <!-- .pmp-poster-media -->';

	

   		$output .= '  		<div class="pmp-poster-details">'; 



        $cat = 'Cat';

        $dog = 'Dog';

    	$applicationurl = constant('PETPOINT_ADOPT_APP_URL');

   		$declawed = constant('PETPOINT_DECLAWED');

   		$description = constant('PETPOINT_DESCRIPTION');

   		$foster_home = $locationFoster;

   		$shelter = $locationShelter;

   		$housetrained = constant('PETPOINT_HOUSETRAINED');

   		$location = constant('PETPOINT_LOCATION');

   		$photo = constant('PETPOINT_PHOTO');

   		$videoid = constant('PETPOINT_VIDEO');

            

      	$emptyDate = '01-01-1970';

        foreach($showItems as $detailKey){

       		if(array_key_exists($detailKey, $animalDetails)){

       		//echo $detailKey . '<br>';

       			if ( (str_contains(strtolower($detailKey), 'date')) && ($animalDetails[$detailKey]['value'] == $emptyDate) ) {

       				$animalDetails[$detailKey]['value'] = $empty;

       			}	

            	if ( ($detailKey <> $description) && ($detailKey <> $videoid) && ($detailKey <> $applicationurl) && ($detailKey <> $photo) && ($detailKey <> constant('PETPOINT_NAME')) && ($animalDetails[$detailKey]['value'] != $empty) ) {

            		if ( ($detailKey <> $declawed) && ($detailKey <> $housetrained) && ($detailKey <> $location) )   {

            			$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';

            		} elseif ( ($detailKey == $declawed) && ($species == $cat) ) {

							$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';		

            		} elseif ( ($detailKey == $housetrained) && ($species == $dog) ) {

							$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';		

					} elseif ($detailKey == $location) {

					  		$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$location_value.'</div></div>';		

					}

            	} 

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

	    }

	    $output .= '  </div> <!-- #pmp-poster-details-description-wrapper -->';



	    /*Output Additional Photos and QR Code */

	    $output .= '	<div id="pmp-poster-details-media-footer-wrapper" class="pmp-poster-details-media-footer-wrapper">';

	    

	   	if ( array_key_exists(constant('PETPOINT_PHOTO'), $animalDetails) ) {	    

	    	if ( array_key_exists(0, $animalDetails[constant('PETPOINT_PHOTO')]) ) {

	    		$output .= '		<div class="pmp-poster-photo">';   

	    		$output .= '      		<img class="pmp-poster-photo-1" src="' . $animalDetails[constant('PETPOINT_PHOTO')][0] . '">';

	        	$output .= '    	</div> <!-- .pmp-poster-photo -->';    	

	    	}



	    	if ( array_key_exists(1, $animalDetails[constant('PETPOINT_PHOTO')]) ) {

	    		$output .= '    	<div class="pmp-poster-photo">';   

	    		$output .= '      		<img class="pmp-poster-photo-1"src="' . $animalDetails[constant('PETPOINT_PHOTO')][1] . '">';

	        	$output .= '    	</div> <!-- .pmp-poster-photo -->';    	

	    	}

	   	}

    	/* Determine QR Code URL */
		//echo 'Method Type is ' . $methodType . '.<br>';
		$optionType = 'general';
		if ($methodType != constant('ADOPT_METHODTYPE_PETPOINT')) {
			$detailPageKey = 'details_page_' . $methodType;
			$titleText = 'Click Here to Visit ' . $animalName . ' Online';
			$displayText = '<br>Scan to View<br>' . $animalName . ' Online';
			$cta2Text = 'Scan the QR Code to Visit ' . $animalName . ' Online.';						
		} else {
			if ( (array_key_exists(constant('ADOPT_METHODTYPE_PETPOINT') . '_link', $this->contactOptions)) && (strlen(trim($this->contactOptions[constant('ADOPT_METHODTYPE_PETPOINT') . '_link'])) > 0) ) {
				$optionType = 'contact';				
				$detailPageKey = constant('ADOPT_METHODTYPE_PETPOINT') . '_link';
				$titleText = 'Click Here to Complete an Adoption Application for ' . $animalName;
				$displayText = '<br>Scan to ' . ucfirst(constant('ADOPT_METHODTYPE_PETPOINT')) . '<br>' . $animalName;
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
		//echo 'PMP Option is ' . $pmpOption . '.<br>';

		$detailPage = do_shortcode($pmpOption);
		//echo 'Detail Page is ' . $detailPage . '.<br>';

		if ($optionType == 'general') {
			$detailPageURL = get_permalink($detailPage);
			$qrURL = $detailPageURL . '?method=' . $method . '&' . str_replace('id', 'ID', constant('PETPOINT_ID_ANIMAL')) . '=' . $ID;		
		} else {
			$detailPageURL = $detailPage;
			$qrURL = $detailPageURL;
		}
		//echo 'Detail Page URL is ' . $detailPageURL . '.<br>';

		//echo 'QR Code URL is ' . $qrURL . '.<br>';

    	$output .= '		<div id="pmp-poster-qr-code">';

    	$output .= '			<a href="' . $qrURL . '" title="' . $titleText . '"><img name="QR Code" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&amp;data=https%3A%2F%2Fcincinnatianimalcare.org%2Fadopt%2Fadopt-details%2F?method=' . $method . '&animalID=' . $ID . '"/></a>' . $displayText;

        $output .= '		</div> <!-- #pmp-poster-qr-code -->';



	   	$output .= '    </div> <!-- #pmp-poster-details-media-footer-wrapper -->';  



	} else {		

  		$output .= '	<div id="pmp-poster-main-content-wrapper">';     

  		$output .= '		<div class="pmp-poster-media-' . constant('LOST_METHODTYPE_PETPOINT') . '">';     

        $output .= '			<div class="pmp-animal-detail-image-' . constant('LOST_METHODTYPE_PETPOINT') . '">';

    	if (array_key_exists(constant('PETPOINT_PHOTO'), $animalDetails)) {       

        	$output .= '				<img src="'.$animalDetails[constant('PETPOINT_PHOTO')][0].'">';

        } else {

        	$output .= '				<img src="">';

        }

        $output .= '			</div> <!-- .pmp-animal-detail-image-' . constant('LOST_METHODTYPE_PETPOINT') . ' -->';        

        $output .= '		</div> <!-- .pmp-poster-media-' . constant('LOST_METHODTYPE_PETPOINT') . ' -->';

        $output .= '	</div> <!-- #pmp-poster-main-content-wrapper -->';

    }       



    /*Output Call to Action */

    $hours_link = do_shortcode('[pmp-option type="contact" value="' . constant('ADOPT_METHODTYPE_PETPOINT') . ' _hours_link"]');	      

    $vanityVolunteer = do_shortcode('[pmp-option type="contact" value="volunteer_vanity_link"]');

    $vanityDonate = do_shortcode('[pmp-option type="contact" value="donation_vanity_link"]');   	    



    $output .= '	<div id="pmp-poster-details-call-to-action-wrapper">';

	    

    if ($methodType == constant('ADOPT_METHODTYPE_PETPOINT')) {

    	$cta1 = '<div id="pmp-poster-details-call-to-action-1"><span class="pmp-poster-cta-highlight">TO ADOPT ' . $animalName . '</span> Call ' . $adoptPhone . ' or<br> </div> <!-- #pmp-poster-details-call-to-action-1 -->';

   		$cta2 = '<div id="pmp-poster-details-call-to-action-2">' . $cta2Text . '<br></div> <!-- #pmp-poster-details-call-to-action-2 -->';

		if ($inFoster == 'No') {

   			$cta3 = '<div id="pmp-poster-details-call-to-action-3">Get Details to Foster ' . $animalName . ' at ' . $vanityFoster . ' or Email Us at ' . $fosterEmail . '.<br> </div><!-- #pmp-poster-details-call-to-action-3 -->';

   		} else {

   			$cta3 = '<div id="pmp-poster-details-call-to-action-3">Explore Additional ' . $species . "'s at " . 'www.CincyCARE' . $species . 's.org<br> </div><!-- #pmp-poster-details-call-to-action-3 -->';

		}

		$cta4 = '<div id="pmp-poster-details-call-to-action-4"><span class="pmp-poster-cta-highlight">Volunteer</span> - ' . $vanityVolunteer . ' &#x2022;' . ' <span class="pmp-poster-cta-highlight">Donate</span> - ' . $vanityDonate . '<br>  <!-- #pmp-poster-details-call-to-action-4 -->';

		$cta5 = '';

	} elseif ($methodType == constant('FOUND_METHODTYPE_PETPOINT')) {

    	$cta1 = '<div id="pmp-poster-details-call-to-action-1"><div class="pmp-col-left"><span class="pmp-poster-cta-highlight">Breed:</span> ' . $breed . '</div><div class="pmp-col-right"><span class="pmp-poster-cta-highlight">Sex: </span>' . $sex . '<br></div> </div> <!-- #pmp-poster-details-call-to-action-1 -->';

   		$cta2 = '<div id="pmp-poster-details-call-to-action-2"><div class="pmp-col-left"><span class="pmp-poster-cta-highlight">Color:</span> ' . $primaryColor . '</div><div class="pmp-col-right"><span class="pmp-poster-cta-highlight">Wt: </span>' . $bodyWeight . '<br></div> </div> <!-- #pmp-poster-details-call-to-action-2 -->';

		$cta3 = '<div id="pmp-poster-details-call-to-action-3"><span class="pmp-poster-cta-extra-large">Found in: ' . strtoupper($jurisdiction) . '<br> </div><!-- #pmp-poster-details-call-to-action-3 -->';

		$cta4 = '<div id="pmp-poster-details-call-to-action-4">If You Have Any Information Please Contact Cincinnati Animal CARE<br></div> <!-- #pmp-poster-details-call-to-action-4 -->';

		$cta5 = '<div id="pmp-poster-details-call-to-action-5"><span class="pmp-poster-cta-extra-large">' . $foundPhone . '</span><br>' . $foundEmail . '</div> <!-- #pmp-poster-details-call-to-action-5 -->';

	} else {

    	$cta1 = '<div id="pmp-poster-details-call-to-action-1"><div class="pmp-col-left"><span class="pmp-poster-cta-highlight">Breed:</span> ' . $breed . '</div><div class="pmp-col-right"><span class="pmp-poster-cta-highlight">Sex: </span>' . $sex . '<br></div> </div> <!-- #pmp-poster-details-call-to-action-1 -->';

   		$cta2 = '<div id="pmp-poster-details-call-to-action-2"><div class="pmp-col-left"><span class="pmp-poster-cta-highlight">Color:</span> ' . $primaryColor . '</div><div class="pmp-col-right"><span class="pmp-poster-cta-highlight">Wt: </span>' . $bodyWeight . '<br></div> </div> <!-- #pmp-poster-details-call-to-action-2 -->';

		$cta3 = '<div id="pmp-poster-details-call-to-action-3"><span class="pmp-poster-cta-extra-large">Last Seen: ' . strtoupper($jurisdiction) . '<br> </div><!-- #pmp-poster-details-call-to-action-3 -->';

		$cta4 = '<div id="pmp-poster-details-call-to-action-4">If You Have Any Information Please Contact Cincinnati Animal CARE<br></div> <!-- #pmp-poster-details-call-to-action-4 -->';

		$cta5 = '<div id="pmp-poster-details-call-to-action-5"><span class="pmp-poster-cta-extra-large">' . $lostPhone . '</span><br>' . $lostEmail . '</div> <!-- #pmp-poster-details-call-to-action-5 -->';

	}



   	$output .= $cta1;

   	$output .= $cta2;

   	$output .= $cta3;

   	$output .= $cta4; 	

  	$output .= $cta5;



   	$output .= '    </div> <!-- #pmp-poster-details-call-to-action-wrapper -->';  



    $output .= '</div> <!-- #pmp-poster-details-wrapper -->';

?>