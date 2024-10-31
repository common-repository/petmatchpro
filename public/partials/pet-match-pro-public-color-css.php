<?php

/* Get Color Option Values */
$colorOptions = get_option('pet-match-pro-color-options');

if (!empty($colorOptions)) {
	if ( (array_key_exists('search_submit_text', $colorOptions)) && (strlen(trim($colorOptions['search_submit_text'])) > 0) ) {
		$color = trim($colorOptions['search_submit_text']);
		echo 'div.pmp-search-form input[type=submit] {color:' . $color . '}';
	}

	if ( (array_key_exists('search_submit_background', $colorOptions)) && (strlen(trim($colorOptions['search_submit_background'])) > 0) ) {
		$color = trim($colorOptions['search_submit_background']);
		echo 'div.pmp-search-form input[type=submit] {background-color:' . $color . '}';
	}

	if ( (array_key_exists('search_submit_border', $colorOptions)) && (strlen(trim($colorOptions['search_submit_border'])) > 0) ) {
		$color = trim($colorOptions['search_submit_border']);
		echo 'div.pmp-search-form input[type=submit] {border-color:' . $color . '}';
	}

	if ( (array_key_exists('search_submit_hover_text', $colorOptions)) && (strlen(trim($colorOptions['search_submit_hover_text'])) > 0) ) {
		$color = trim($colorOptions['search_submit_hover_text']);
		echo 'div.pmp-search-form input[type=submit]:hover {color:' . $color . '}';	
	}

	if ( (array_key_exists('search_submit_hover_background', $colorOptions)) && (strlen(trim($colorOptions['search_submit_hover_background'])) > 0) ) {
		$color = trim($colorOptions['search_submit_hover_background']);
		echo 'div.pmp-search-form input[type=submit]:hover {background-color:' . $color . '}';	
	}

	if ( (array_key_exists('search_submit_hover_border', $colorOptions)) && (strlen(trim($colorOptions['search_submit_hover_border'])) > 0) ) {
		$color = trim($colorOptions['search_submit_hover_border']);
		echo 'div.pmp-search-form input[type=submit]:hover {border-color:' . $color . '}';	
	}

	if ( (array_key_exists('search_title', $colorOptions)) && (strlen(trim($colorOptions['search_title'])) > 0) ) {
		$color = trim($colorOptions['search_title']);
		echo 'div.pmp-results-title {color:' . $color . '!important}';
	}

	if ( (array_key_exists('search_result_name', $colorOptions)) && (strlen(trim($colorOptions['search_result_name'])) > 0) ) {
		$color = trim($colorOptions['search_result_name']);
		if ($this->integrationPartner == constant('PETPOINT')) {
			echo 'div.pmp-search-result.pmp-animal-name a {color:' . $color . '!important}';
		} else if ($this->integrationPartner == constant('RESCUEGROUPS')) {
			echo 'div.pmp-search-result.pmp-animal-animalname a {color:' . $color . '}';
		}
	}

	if ( (array_key_exists('search_result_name_hover', $colorOptions)) && (strlen(trim($colorOptions['search_result_name_hover'])) > 0) ) {
		$color = trim($colorOptions['search_result_name_hover']);
		if ($this->integrationPartner == constant('PETPOINT')) {
			echo 'div.pmp-search-result.pmp-animal-name a:hover {color:' . $color . '!important}';
		} else if ($this->integrationPartner == constant('RESCUEGROUPS')) {
			echo 'div.pmp-search-result.pmp-animal-animalname a:hover {color:' . $color . '}';
		}
	}

	if ( (array_key_exists('search_result_label', $colorOptions)) && (strlen(trim($colorOptions['search_result_label'])) > 0) ) {
		$color = trim($colorOptions['search_result_label']);
		echo 'div.pmp-search-result-label {color:' . $color . '}';
	}

	if ( (array_key_exists('search_result', $colorOptions)) && (strlen(trim($colorOptions['search_result'])) > 0) ) {
		$color = trim($colorOptions['search_result']);
		echo 'div.pmp-search-result-detail {color:' . $color . '}';
		echo 'span.pmp-search-result-detail {color:' . $color . '}';
	}

	if ( (array_key_exists('detail_result_button_text', $colorOptions)) && (strlen(trim($colorOptions['detail_result_button_text'])) > 0) ) {
		$color = trim($colorOptions['detail_result_button_text']);
		echo 'div.pmp-detail-search-return button.pmp-button {color:' . $color . '}';
		echo 'div.pmp-details-features a.pmp-button {color:' .$color .'}';
		echo 'div.pmp-details-conversion-buttons div a.pmp-button {color:' .$color .'}';
		echo 'div.pmp-details-other-buttons div a.pmp-button {color:' .$color .'}';
	}

	if ( (array_key_exists('detail_result_button_background', $colorOptions)) && (strlen(trim($colorOptions['detail_result_button_background'])) > 0) ) {
		$color = trim($colorOptions['detail_result_button_background']);
		echo 'div.pmp-detail-search-return button.pmp-button {background-color:' . $color . '!important}';
		echo 'div.pmp-details-features a.pmp-button {background-color:' .$color . '!important}';
		echo 'div.pmp-details-conversion-buttons div a.pmp-button {background-color:' .$color .'!important}';
		echo 'div.pmp-details-other-buttons div a.pmp-button {background-color:' .$color .'!important}';	
	}

	if ( (array_key_exists('detail_result_button_border', $colorOptions)) && (strlen(trim($colorOptions['detail_result_button_border'])) > 0) ) {
		$color = trim($colorOptions['detail_result_button_border']);
		echo 'div.pmp-detail-search-return button.pmp-button {border-color:' . $color . '}';
		echo 'div.pmp-details-features a.pmp-button {border-color:' .$color .'}';
		echo 'div.pmp-details-conversion-buttons div a.pmp-button {border-color:' .$color .'}';
		echo 'div.pmp-details-other-buttons div a.pmp-button {border-color:' .$color .'}';		
	}

	if ( (array_key_exists('detail_result_button_hover_text', $colorOptions)) && (strlen(trim($colorOptions['detail_result_button_hover_text'])) > 0) ) {
		$color = trim($colorOptions['detail_result_button_hover_text']);
		echo 'div.pmp-detail-search-return button.pmp-button:hover {color:' . $color . '}';
		echo 'div.pmp-details-features a.pmp-button:hover {color:' .$color .'}';
		echo 'div.pmp-details-conversion-buttons div a.pmp-button:hover {color:' .$color .'}';
		echo 'div.pmp-details-other-buttons div a.pmp-button:hover {color:' .$color .'}';	
	}

	if ( (array_key_exists('detail_result_button_hover_background', $colorOptions)) && (strlen(trim($colorOptions['detail_result_button_hover_background'])) > 0) ) {
		$color = trim($colorOptions['detail_result_button_hover_background']);
		echo 'div.pmp-detail-search-return button.pmp-button:hover {background-color:' . $color . '!important}';
		echo 'div.pmp-details-features a.pmp-button:hover {background-color:' .$color . '!important}';
		echo 'div.pmp-details-conversion-buttons div a.pmp-button:hover {background-color:' .$color . '!important}';
		echo 'div.pmp-details-other-buttons div a.pmp-button:hover {background-color:' .$color .'!important}';		
	}
	
	if ( (array_key_exists('detail_result_button_hover_border', $colorOptions)) && (strlen(trim($colorOptions['detail_result_button_hover_border'])) > 0) ) {
		$color = trim($colorOptions['detail_result_button_hover_border']);
		echo 'div.pmp-detail-search-return button.pmp-button:hover {border-color:' . $color . '!important}';
		echo 'div.pmp-details-features a.pmp-button:hover {border-color:' .$color . '!important}';
		echo 'div.pmp-details-conversion-buttons div a.pmp-button:hover {border-color:' .$color . '!important}';
		echo 'div.pmp-details-other-buttons div a.pmp-button:hover {border-color:' .$color .'!important}';			
	}

	if ( (array_key_exists('detail_result_title', $colorOptions)) && (strlen(trim($colorOptions['detail_result_title'])) > 0) ) {
		$color = trim($colorOptions['detail_result_title']);
		echo 'h1.pmp-adoptable-details-title {color:' . $color . '}';
		echo 'p.pmp-adoptable-details-title {color:' . $color . '}';	
		echo 'span.pmp-adoptable-details-title {color:' . $color . '}';	
	}

	if ( (array_key_exists('detail_result_label', $colorOptions)) && (strlen(trim($colorOptions['detail_result_label'])) > 0) ) {
		$color = trim($colorOptions['detail_result_label']);
		echo 'div.col.pmp-detail-label {color:' . $color . '}';
	}

	if ( (array_key_exists('detail_result', $colorOptions)) && (strlen(trim($colorOptions['detail_result'])) > 0) ) {
		$color = trim($colorOptions['detail_result']);
		echo 'div.pmp-detail-value {color:' . $color . '}';
	}

	if ( (array_key_exists('detail_description_label', $colorOptions)) && (strlen(trim($colorOptions['detail_description_label'])) > 0) ) {
		$color = trim($colorOptions['detail_description_label']);
		echo '.pmp-detail-description-wrapper > .pmp-detail-result > .pmp-detail-label {color:' . $color . '}';
	}

	if ( (array_key_exists('detail_description', $colorOptions)) && (strlen(trim($colorOptions['detail_description'])) > 0) ) {
		$color = trim($colorOptions['detail_description']);
		echo 'div.pmp-detail-value.pmp-detail-dsc-value {color:' . $color . '}';
	}
	
	if ( (array_key_exists('detail_poster_heading', $colorOptions)) && (strlen(trim($colorOptions['detail_poster_heading'])) > 0) ) {
		$color = trim($colorOptions['detail_poster_heading']);
		echo 'div#pmp-poster-heading-wrapper div.pmp-poster-heading-1 h2 {color:' . $color . '!important}';
		echo 'div#pmp-poster-heading-wrapper div.pmp-poster-heading-2 h3 {color:' . $color . '!important}';		
	}
	
	if ( (array_key_exists('detail_poster_qr_code', $colorOptions)) && (strlen(trim($colorOptions['detail_poster_qr_code'])) > 0) ) {
		$color = trim($colorOptions['detail_poster_qr_code']);
		echo 'div#pmp-poster-qr-code {color:' . $color . '}';
	}
	
	if ( (array_key_exists('detail_poster_footing', $colorOptions)) && (strlen(trim($colorOptions['detail_poster_footing'])) > 0) ) {
		$color = trim($colorOptions['detail_poster_footing']);
		echo 'div#pmp-poster-details-call-to-action-wrapper {color:' . $color . '}';
		echo '.pmp-poster-cta-highlight {color:' . $color . '!important}';
		echo '.pmp-poster-cta-extra-large {color:' . $color . '!important}';
	}
}