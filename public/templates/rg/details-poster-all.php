<?php
	echo 'Hello This is the Print All Posters Template';
	echo '<pre>ANIMAL DETAILS<br>'; print_r($this->searchOutput); echo '</pre>';
	$ID = $_GET['animalID'];
	$method = $_GET['method'];	
	$adoptable_phone = do_shortcode('[pmp-option type="contact" value="adoptable_phone"]');
	$adoptablePhone = '(' . substr($adoptable_phone,0,3) . ') ' . substr($adoptable_phone,3,3) . '-' . substr($adoptable_phone,6,4);
    $fosterEmail = do_shortcode('[pmp-option type="contact" value="adoptable_foster_email"]');  
    $foundEmail = do_shortcode('[pmp-option type="contact" value="found_email"]'); 
   	$vanityFoster = do_shortcode('[pmp-option type="contact" value="adoptable_foster_vanity_link"]');	

	$name = do_shortcode('[pmp-detail detail="' . constant('RESCUEGROUPS_NAME') . '" case="mixed"]');

	/* Define Location Options */
	$location_1 = do_shortcode('[pmp-option type="filter" value="adoptable_location_filter_1"]');
	$location_2 = do_shortcode('[pmp-option type="filter" value="adoptable_location_filter_2"]');
	$location_3 = do_shortcode('[pmp-option type="filter" value="adoptable_location_filter_3"]');		
	$location_other = do_shortcode('[pmp-option type="filter" value="adoptable_location_filter_other"]');

	/* Get Foster & Shelter Locations Names */
	$locationFoster = do_shortcode('[pmp-option type="filter" value="location_foster"]');
	//echo 'Foster Location is ' . $locationFoster . '<br>';
	$locationShelter = do_shortcode('[pmp-option type="filter" value="location_shelter"]');
	//echo 'Shelter Location is ' . $locationShelter . '<br>';

	/* Output Media */
	//echo '<pre>ANIMAL DETAILS<br>'; print_r($animalDetails); echo '</pre>';
	$location_value = $animalDetails[constant('RESCUEGROUPS_LOCATION')]['value'];
	if ( !is_null($location_other) ) {
		if ( ($location_value <> $location_1) && ($location_value <> $location_2) && ($location_value <> $location_3) ) {
			$location_value = $location_other;
		}
	}

	$species = do_shortcode('[pmp-detail detail="' . constant('RESCUEGROUPS_SPECIES') . '" case="mixed"]');
	echo 'Species = ' . $species . '<br>';
	$age = $animalDetails[constant('RESCUEGROUPS_BIRTHDATE')]['value'];
	//echo 'Age = ' . $age . '<br>';
	if (substr($age, 0, 1) == 1) {
		$ageFormatted = str_replace('(s)', '', $age);
	} else {
		$ageFormatted = str_replace(array( '(', ')' ), '', $age);
	}
	$sex = do_shortcode('[pmp-detail detail="' . constant('RESCUEGROUPS_SEX') . '" case="mixed"]');
	$breed = do_shortcode('[pmp-detail detail="' . constant('RESCUEGROUPS_BREED') . '" case="mixed"]');

	if (str_contains(strtolower($location_value), 'foster')) {
		$header1 = 'Provide ' . $name; 
		$header2 = 'With a Loving Home';
	} else {
		$header1 = $name;
		$header2 = '(Needs a Foster)';
	}
	
	$output = '';
	$output .= '<div id="pmp-poster-details-wrapper">';
	$output .= '	<div id="pmp-poster-heading-wrapper">';	
	$output .= '		<div class="pmp-poster-heading-1"><h2>' . $header1 . '</h2></div>';
	$output .= '		<div class="pmp-poster-heading-2"><h3>' . $header2 . '</h3></div>';
    $output .= '	</div> <!-- #pmp-poster-heading-wrapper -->';

	$empty = constant('EMPTY_VALUE');

	$bodyWeight = $animalDetails[constant('RESCUEGROUPS_WEIGHT')]['value'];
	if ( $bodyWeight == 0 ) {
		$animalDetails[constant('RESCUEGROUPS_WEIGHT')]['value'] = $empty;
		$bodyWeight = $empty;
	}

	$output .= '	<div id="pmp-poster-main-content-wrapper">';     
	$output .= '		<div class="pmp-poster-media">';     
    $output .= '			<div class="pmp-animal-detail-image">';
    $output .= '				<img id="expandedImg" src="'.$animalDetails[constant('RESCUEGROUPS_PHOTOS')][0].'">';
    $output .= '			</div> <!-- .pmp-animal-detail-image -->';        
    $output .= '		</div> <!-- .pmp-poster-media -->';
	
	$output .= '  		<div class="pmp-poster-details">'; 

    foreach($showItems as $detailKey){
        $cat = 'Cat';
        $dog = 'Dog';
              	
       	$declawed = constant('RESCUEGROUPS_DECLAWED');
       	//echo 'Declawed Constant = ' . $declawed . '<br>';
      	$description = constant('RESCUEGROUPS_DESCRIPTION');
       	$foster_home = $locationFoster;
       	$shelter = $locationShelter;
//      $shelter = 'Shelter';
       	$housetrained = constant('RESCUEGROUPS_HOUSETRAINED');
       	$location = constant('RESCUEGROUPS_LOCATION');
       	$photo = constant('RESCUEGROUPS_PHOTOS');
       	
       	$emptyDate = '01-01-1970';
   		if(array_key_exists($detailKey, $animalDetails)){
   		//echo $detailKey . '<br>';
   			if ( (str_contains(strtolower($detailKey), 'date')) && ($animalDetails[$detailKey]['value'] == $emptyDate) ) {
   				$animalDetails[$detailKey]['value'] = $empty;
   			}	
           	if ( ($detailKey <> $description) && ($detailKey <> $photo) && ($detailKey <> 'animalname') && ($animalDetails[$detailKey]['value'] != $empty) ) {
           		if ( ($detailKey <> $declawed) && ($detailKey <> $housetrained) && ($detailKey <> $location) )   {
           			$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';
           		} elseif ( ($detailKey == $declawed) && ($species == $cat) ) {
						$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';		
           		} elseif ( ($detailKey == $housetrained) && ($species == $dog) ) {
						$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$animalDetails[$detailKey]['value'].'</div></div>';		
				} elseif ($detailKey == $location) {
				  		$location_value = $animalDetails[$detailKey]['value'];
				  		if ( !is_null($location_other) ) {
				  			if ( ($location_value <> $location_1) && ($location_value <> $location_2) && ($location_value <> $location_3) ) {
				  				$location_value = $location_other;
				  			}
				  		}
				  		$output .= '<div class="row pmp-detail-result pmp-animal-detail-'.$detailKey.'"><div class="col pmp-detail-label pmp-animal-detail-'.$detailKey.'-label">'.$animalDetails[$detailKey]['label'].'</div><div class="col pmp-detail-value pmp-animal-detail-'.$detailKey.'-value">'.$location_value.'</div></div>';		
				}
           	} 
        }  
	}          
    $output .= '  		</div> <!-- .pmp-poster-details -->';

    $output .= '	</div> <!-- #pmp-poster-main-content-wrapper -->';

	//Output Animal Descrciption

   	$output .= '  <div id="pmp-poster-details-description-wrapper">'; 
    if ($animalDetails[$description]['value'] <> $empty) {
	  	$output .= '<div class="col pmp-detail-value pmp-animal-detail-'.$description.'-value">'.$animalDetails[$description]['value'].'</div>';
    }
    $output .= '  </div> <!-- #pmp-poster-details-description-wrapper -->';

	/*Output Additional Photos and QR Code */
	$output .= '	<div id="pmp-poster-details-media-footer-wrapper" class="pmp-poster-details-media-footer-wrapper">';
    if ( !is_null($animalDetails[constant('RESCUEGROUPS_PHOTOS')][1]) ) {
   		$output .= '		<div class="pmp-poster-photo">';   
   		$output .= '      		<img class="pmp-poster-photo-1" src="' . $animalDetails[constant('RESCUEGROUPS_PHOTOS')][1] . '">';
       	$output .= '    	</div> <!-- .pmp-poster-photo -->';    	
   	}

   	if ( !is_null($animalDetails[constant('RESCUEGROUPS_PHOTOS')][2]) ) {
   		$output .= '    	<div class="pmp-poster-photo">';   
   		$output .= '      		<img class="pmp-poster-photo-1"src="' . $animalDetails[constant('RESCUEGROUPS_PHOTOS')][2] . '">';
       	$output .= '    	</div> <!-- .pmp-poster-photo -->';    	
   	}

   	/* Determine QR Code URL */
	$detailPageKey = 'details_template';
	//echo 'DETAILS PAGE KEY = ' . $detailPageKey . '<br>';

	$pmpOption = '[pmp-option type="general" value="' . $detailPageKey . '"]';
	$detailPage = do_shortcode($pmpOption);
	$detailPageURL = get_permalink($detailPage);
	//echo 'DETAILS PAGE URL = ' . $detailPageURL . '<br>';

	$qrURL = $detailPageURL . '?method=' . $method . '&animalID=' . $ID;		
	//echo 'QR CODE URL = ' . $qrURL . '<br>';

   	$output .= '		<div id="pmp-poster-qr-code">';
   	$output .= '			<a href="' . $qrURL . '" title="Click Here to Visit ' . $name . ' Online"><img name="QR Code" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&amp;data=https%3A%2F%2Fcincinnatianimalcare.org%2Fadopt%2Fadoptable-details%2F?method=' . $method . '&animalID=' . $ID . '"/></a><br>Scan to View<br>' . $name . ' Online';
    $output .= '		</div> <!-- #pmp-poster-qr-code -->';

   	$output .= '    </div> <!-- #pmp-poster-details-media-footer-wrapper -->';  

    /*Output Call to Action */
    $hours_link = do_shortcode('[pmp-option type="contact" value="adoptable_hours_link"]');	      
    $vanityVolunteer = do_shortcode('[pmp-option type="contact" value="volunteer_vanity_link"]');
    $vanityDonate = do_shortcode('[pmp-option type="contact" value="donation_vanity_link"]');   	    

    $output .= '	<div id="pmp-poster-details-call-to-action-wrapper">';
	    
   	$cta1 = '<div id="pmp-poster-details-call-to-action-1"><span class="pmp-poster-cta-highlight">TO ADOPT ' . ucwords($animalDetails[constant('RESCUEGROUPS_NAME')]['value']) . '</span> Call ' . $adoptablePhone . ' or<br> </div> <!-- #pmp-poster-details-call-to-action-1 -->';
	$cta2 = '<div id="pmp-poster-details-call-to-action-2">Scan the QR Code to Complete an Online Adoption Application.<br></div> <!-- #pmp-poster-details-call-to-action-2 -->';
	if (!$foster) {
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