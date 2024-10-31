<?php
$this->pmpAdminInfo['integration_partner'] = 'Select the partner from which animals will be sourced for display on your website.';
$this->pmpAdminInfo['partner_API_key'] = 'Enter the key value issued by your integration partner to validate your account.';

$adoptMethodType = 'adopt'; 
$foundMethodType = 'found';
$lostMethodType = 'lost';        		           	

if ( ($this->integrationPartner == constant('PETPOINT')) ) {
	$this->pmpAdminInfo['on_hold_status'] = 'Select the default animal status for use in PetPoint animal searches.';
    $this->adoptMethodType = constant('ADOPT_METHODTYPE_ANIMALSFIRST');
    $adoptMethodType = constant('ADOPT_METHODTYPE_PETPOINT');
    $foundMethodType = constant('FOUND_METHODTYPE_PETPOINT');
    $lostMethodType = constant('LOST_METHODTYPE_PETPOINT');        		
}

if ($this->integrationPartner == constant('RESCUEGROUPS')) {
	$this->pmpAdminInfo['organization_id'] = 'Enter your RescueGroups organization ID.';
	$this->pmpAdminInfo['search_result_limit'] = 'Enter the maximum number of animal records to return from RescueGroups.';
	$this->pmpAdminInfo['sort_order'] = 'Select the default order for sorting search results.';
    $adoptMethodType = constant('ADOPT_METHODTYPE_RESCUEGROUPS'); 
}

if ($this->integrationPartner == constant('ANIMALSFIRST')) {
	$generalOptions = get_option('pet-match-pro-general-options');
	$preferredLabel = $generalOptions[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_methodtype_label'];
    $adoptMethodType = constant('ADOPT_METHODTYPE_ANIMALSFIRST');
    $foundMethodType = constant('FOUND_METHODTYPE_ANIMALSFIRST');
    $lostMethodType = constant('LOST_METHODTYPE_ANIMALSFIRST');
    $preferredMethodType = constant('PREFERRED_METHODTYPE_ANIMALSFIRST');                    	
	if (strlen(trim($preferredLabel)) == 0) {
		$preferredLabel = ucfirst($preferredMethodType);
	}	
	$this->pmpAdminInfo['partner_API_source'] = 'Select the AnimalsFirst API Source.';
	$this->pmpAdminInfo['default_method_type'] = 'Select the default method type for use in AnimalsFirst search and detail shortcodes.';
	$this->pmpAdminInfo['search_result_limit'] = 'Enter the maximum number of animal records to return from AnimalsFirst.';	
	$this->pmpAdminInfo['sort_order'] = 'Select the default order for sorting search results.';	
	$this->pmpAdminInfo['details_template_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')] = 'Select the template for use in displaying ' . $preferredLabel . ' animal pages.';
	$this->pmpAdminInfo['details_page_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')] = 'Select the page to display details for ' . $preferredLabel . '  animal types.';
	$this->pmpAdminInfo['no_search_results_' . constant('PREFERRED_METHODTYPE_ANIMALSFIRST')] = 'Enter message displayed when no ' . $preferredLabel . ' animals meet a search criteria, detail shortcodes NOT supported.';
}

$this->pmpAdminInfo['order_by'] = 'Select the default field for sorting search results.';
$this->pmpAdminInfo['orderby_labels'] = 'Check to use entries in the Labels tab for the sort field names.';
$this->pmpAdminInfo['results_per_row'] = 'Select the number of animals to present per search result row.';
$this->pmpAdminInfo['adopt_animal_search_icons'] = 'Check to show icons in adoptable search results.';
$this->pmpAdminInfo['adopt_animal_search_icons_max'] = 'Select maximum icons to display in adoptable search results.';
$this->pmpAdminInfo['adopt_animal_detail_thumbs_max'] = 'Select maximum thumbnails to display on the animal detail page.';
$this->pmpAdminInfo['adopt_animal_detail_icons'] = 'Check to show icons in the adoptable detail page.';
$this->pmpAdminInfo['adopt_animal_detail_icons_max'] = 'Select maximum icons to display on adoptable detail page.';
$this->pmpAdminInfo['paginate_results'] = 'Check to display search results on multiple pages vs. one.';
$this->pmpAdminInfo['age_in_years'] = 'Check to display animal age in years vs. years and months.';
$this->pmpAdminInfo[constant('PREFERRED_METHODTYPE_ANIMALSFIRST') . '_methodtype_label'] = 'Enter label for the Preferred MethodType.';
$this->pmpAdminInfo['ga_tracking_method'] = 'Select the Google script you installed to track website statistics.';
$this->pmpAdminInfo['details_template_' . $adoptMethodType] = 'Select the template for use in displaying ' . ucfirst($adoptMethodType) . ' animal pages.';
$this->pmpAdminInfo['details_template_' . $foundMethodType] = 'Select the template for use in displaying ' . ucfirst($foundMethodType) . ' animal pages.';
$this->pmpAdminInfo['details_template_' . $lostMethodType] = 'Select the template for use in displaying ' . ucfirst($lostMethodType) . ' animal pages.';
$this->pmpAdminInfo['details_page_' . $adoptMethodType] = 'Select the page to display ' . ucfirst($adoptMethodType) . ' animals.';
$this->pmpAdminInfo['details_page_' . $foundMethodType] = 'Select the page to display ' . ucfirst($foundMethodType) . ' animals.';
$this->pmpAdminInfo['details_page_' . $lostMethodType] = 'Select the page to display ' . ucfirst($lostMethodType) . ' animals.';
$this->pmpAdminInfo['details_page_poster'] = 'Select the page to display animal posters.';
$this->pmpAdminInfo['search_feature_link'] = 'Enter URL for Search Feature button positioned next to Search Submit.';
$this->pmpAdminInfo['search_feature_target'] = 'Select the destination target for Search Feature button.';
$this->pmpAdminInfo['search_feature_class'] = 'Enter css class string for Search Feature button.';
$this->pmpAdminInfo['search_feature_label'] = 'Enter Search Feature button label.';
$this->pmpAdminInfo['no_search_results_' . $adoptMethodType] = 'Enter message displayed when no ' . ucfirst($adoptMethodType) . ' animals meet a search criteria, detail shortcodes NOT supported.';
$this->pmpAdminInfo['no_search_results_' . $foundMethodType] = 'Enter message displayed when no ' . ucfirst($foundMethodType) . ' animals meet a search criteria, detail shortcodes NOT supported.';
$this->pmpAdminInfo['no_search_results_' . $lostMethodType] = 'Enter message displayed when no ' . ucfirst($lostMethodType) . ' animals meet a search criteria, detail shortcodes NOT supported.';
$this->pmpAdminInfo['no_search_results_featured'] = 'Enter  message displayed when no featured animal is configured, detail shortcodes NOT supported.';
$this->pmpAdminInfo['default_description'] = 'Enter description for use when one is not configured in your integration partner, detail shortcodes permitted.';

$this->pmpAdminInfo['location_exclusion_1'] = 'Enter an Integration Partner animal location to exclude from results.' ;
$this->pmpAdminInfo['location_exclusion_2'] = 'Enter an Integration Partner animal location to exclude from results.' ;
$this->pmpAdminInfo['location_exclusion_3'] = 'Enter an Integration Partner animal location to exclude from results.' ;
$this->pmpAdminInfo['location_exclusion_4'] = 'Enter an Integration Partner animal location to exclude from results.' ;
$this->pmpAdminInfo['location_exclusion_5'] = 'Enter an Integration Partner animal location to exclude from results.' ;
$this->pmpAdminInfo['location_filter_1'] = 'Enter the start of an Integration Partner animal location to match for inclusion in results.' ;
$this->pmpAdminInfo['location_filter_1_label'] = 'Enter new label for animal location matching filter pattern.';
$this->pmpAdminInfo['location_filter_2'] = 'Enter the start of an Integration Partner animal location to match for inclusion in results.';
$this->pmpAdminInfo['location_filter_2_label'] = 'Enter new label for animal location matching filter pattern.';
$this->pmpAdminInfo['location_filter_3'] = 'Enter the start of an Integration Partner animal location to match for inclusion in results.';
$this->pmpAdminInfo['location_filter_3_label'] = 'Enter new label for animal location matching filter pattern.';
$this->pmpAdminInfo['location_filter_4'] = 'Enter the start of an Integration Partner animal location to match for inclusion in results.';
$this->pmpAdminInfo['location_filter_4_label'] = 'Enter new label for animal location matching filter pattern.';
$this->pmpAdminInfo['location_filter_5'] = 'Enter the start of an Integration Partner animal location to match for inclusion in results.';
$this->pmpAdminInfo['location_filter_5_label'] = 'Enter new label for animal location matching filter pattern.';
$this->pmpAdminInfo['location_filter_other'] = 'Enter location name for use in consolidating all remaining locations not identified above.';
$this->pmpAdminInfo['location_foster'] = 'Enter the Integration Partner foster home location name.';
$this->pmpAdminInfo['location_shelter'] = 'Enter the Integration Partner main shelter location name.';

$this->pmpAdminInfo['pmp_custom_css'] = 'Enter custom CSS to alter PetMatchPro front-end (user experience).';
$this->pmpAdminInfo['social_share'] = 'Check to enable social sharing of animal detail pages.';
$this->pmpAdminInfo[$adoptMethodType . '_phone'] = 'Enter phone number (numbers only) regarding animal ' . ucfirst($adoptMethodType) . '.';
$this->pmpAdminInfo[$adoptMethodType . '_email'] = 'Enter email address for animal ' . ucfirst($adoptMethodType) . '.';
$this->pmpAdminInfo[$adoptMethodType . '_link'] = 'Enter URL to complete an ' . ucfirst($adoptMethodType) . ' application.';
$this->pmpAdminInfo[$adoptMethodType . '_vanity_link'] = 'Enter URL for ' . ucfirst($adoptMethodType) . ' information.';
$this->pmpAdminInfo[$adoptMethodType . '_foster_phone'] = 'Enter phone number (numbers only) to foster an animal.';
$this->pmpAdminInfo[$adoptMethodType . '_foster_email'] = 'Enter email address to foster an animal.';
$this->pmpAdminInfo[$adoptMethodType . '_foster_link'] = 'Enter URL to complete a foster application.';
$this->pmpAdminInfo[$adoptMethodType . '_foster_vanity_link'] = 'Enter URL for foster information.';
$this->pmpAdminInfo[$adoptMethodType . '_donation_link'] = 'Enter website or external URL to make a donation.';
$this->pmpAdminInfo['donation_vanity_link'] = 'Enter URL for donation information.';
$this->pmpAdminInfo['volunteer_vanity_link'] = 'Enter URL for volunteer information.';
$this->pmpAdminInfo[$adoptMethodType . '_hours_link'] = 'Enter a website URL to display hours of operation.';
$this->pmpAdminInfo[$foundMethodType . '_phone'] = 'Enter phone number (numbers only) to inquire about a ' . ucfirst($foundMethodType) . ' animal.';
$this->pmpAdminInfo[$foundMethodType . '_email'] = 'Enter email address to inquire about a ' . ucfirst($foundMethodType) . ' animal.';
$this->pmpAdminInfo[$lostMethodType . '_phone'] = 'Enter phone number (numbers only) to inquire about a ' . ucfirst($lostMethodType) . ' animal.';
$this->pmpAdminInfo[$lostMethodType . '_email'] = 'Enter email address to inquire about a ' . ucfirst($lostMethodType) . ' animal.';
$this->pmpAdminInfo['sponsor_link'] = 'Enter external URL to visit sponsor website.';
$this->pmpAdminInfo['sponsor_image'] = 'Enter website or external URL to sponsor image or banner.';
$this->pmpAdminInfo['website_support_email'] = 'Enter email address to request website support.';

$this->pmpAdminInfo['link_text'] = 'Enter hex or RGB color value for Text Links.';
$this->pmpAdminInfo['link_text_hover'] = 'Enter hex or RGB color value for Text Link Hover State.';
$this->pmpAdminInfo['search_submit_text'] = 'Enter hex or RGB color value for Search Submit Button Text.';
$this->pmpAdminInfo['search_submit_background'] = 'Enter hex or RGB color value for Search Submit Button Background.';
$this->pmpAdminInfo['search_submit_border'] = 'Enter hex or RGB color value for Search Submit Button Border.';
$this->pmpAdminInfo['search_submit_hover_text'] = 'Enter hex or RGB color value for Search Submit Button Hover State Text.';
$this->pmpAdminInfo['search_submit_hover_background'] = 'Enter hex or RGB color value for Search Submit Button Hover State Background.';
$this->pmpAdminInfo['search_submit_hover_border'] = 'Enter hex or RGB color value for Search Submit Button Hover State Border.';
$this->pmpAdminInfo['search_title'] = 'Enter hex or RGB color value for Search Results Title.';
$this->pmpAdminInfo['search_result_name'] = 'Enter hex or RGB color value for Search Result Name.';
$this->pmpAdminInfo['search_result_name_hover'] = 'Enter hex or RGB color value for Search Result Name Hover State.';
$this->pmpAdminInfo['search_result_label'] = 'Enter hex or RGB color value for Search Result Label.';
$this->pmpAdminInfo['search_result'] = 'Enter hex or RGB color value for Search Result Value.';
$this->pmpAdminInfo['detail_result_button_text'] = 'Enter hex or RGB color value for Details Button Text.';
$this->pmpAdminInfo['detail_result_button_background'] = 'Enter hex or RGB color value for Details Button Background.';
$this->pmpAdminInfo['detail_result_button_border'] = 'Enter hex or RGB color value for Details Button Border.';
$this->pmpAdminInfo['detail_result_button_hover_text'] = 'Enter hex or RGB color value for Details Button Hover State Text.';
$this->pmpAdminInfo['detail_result_button_hover_background'] = 'Enter hex or RGB color value for Details Button Hover State Background.';
$this->pmpAdminInfo['detail_result_button_hover_border'] = 'Enter hex or RGB color value for Details Button Hover State Border.';
$this->pmpAdminInfo['detail_result_title'] = 'Enter hex or RGB color value for Detail Result Title.';
$this->pmpAdminInfo['detail_result_label'] = 'Enter hex or RGB color value for Detail Result Label.';
$this->pmpAdminInfo['detail_result'] = 'Enter hex or RGB color value for Detail Result Value.';
$this->pmpAdminInfo['detail_description_label'] = 'Enter hex or RGB color value for Detail Description Label.';
$this->pmpAdminInfo['detail_description'] = 'Enter hex or RGB color value for Detail Description Copy.';
$this->pmpAdminInfo['detail_poster_heading'] = 'Enter hex or RGB color value for Detail Poster Heading Copy.';
$this->pmpAdminInfo['detail_poster_qr_code'] = 'Enter hex or RGB color value for Detail Poster QR Code.';
$this->pmpAdminInfo['detail_poster_footing'] = 'Enter hex or RGB color value for Detail Poster Footing Copy.';
?>