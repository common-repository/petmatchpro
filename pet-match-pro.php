<?php
ob_start();
/**
 *
 *
 * PetMatch Pro plugin developed by Rick Barron and the Team at PetMatchPro
 *
 * @link              https://PetMatchPro.com
 * @since             1.0.1
 * @package           Pet_Match_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       PetMatchPro
 * Description:       Integrates animal search and details from your PetPoint/Petango, AnimalsFirst or RescueGroups account into your website with simple shortcodes.
 * Version:           5.3.5
 * Author:            PetMatchPro
 * Author URI:        https://PetMatchPro.com
 * Text Domain:       pet-match-pro
 * Domain Path:       /languages
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define('PET_MATCH_PRO_VERSION', '5.3.5');

/* Define Global Variables */
define('PET_MATCH_PRO_PATH', __DIR__ );
define('PET_MATCH_PRO_PATH_FILE', __FILE__ );
define('PAGE_BUILDER', 'builder');
define('ERROR', 'ERROR');
define('PMP_CLASS_PREFIX', 'pmp ');
define('PMP_PLUGIN_NAME', 'pet-match-pro');
define('PMP_PLUGIN_SLUG', 'pet_match_pro_');

/* Constants to Manage Premium Content */
define('PREFERRED_LEVEL', 1);  
define('FREE_LEVEL', 3); 
define('FREE_SPECIES', 'cat,dog');
define('PMP_LOGIN', 'https://petmatchpro.com/my-account/');
define('LEVEL_PREFIX_SEARCH', 'level_search_');
define('LEVEL_PREFIX_SEARCH_FILTER', 'level_search_filter_');
define('LEVEL_PREFIX_SEARCH_SORT', 'level_search_sort_');
define('LEVEL_PREFIX_SEARCH_ORDER', 'level_search_order_');
define('LEVEL_PREFIX_SEARCH_RESULT', 'level_search_result_');
define('LEVEL_PREFIX_ANIMAL_DETAIL', 'level_detail_');
define('DETAILS_TEMPLATE_KEY', 'details_template');

/* Constants to Manage Label Processing */
define('LABEL_PREFIX_SEARCH', 'label_search_');
define('LABEL_PREFIX_SEARCH_FILTER', 'label_search_filter_');
define('LABEL_PREFIX_SEARCH_SORT', 'label_search_sort_');
define('LABEL_PREFIX_SEARCH_ORDER', 'label_search_order_');
define('LABEL_PREFIX_SEARCH_RESULT', 'label_search_result_');
define('LABEL_PREFIX_ANIMAL_DETAIL', 'label_detail_');

/*Constants to Manage Methods */
define('ADOPT_METHODTYPE_ANIMALSFIRST', 'adopt');
define('ADOPT_METHODTYPE_PETPOINT', 'adopt');
define('ADOPT_METHODTYPE_RESCUEGROUPS', 'adopt');
define('FOUND_METHODTYPE_ANIMALSFIRST', 'found');
define('FOUND_METHODTYPE_PETPOINT', 'found');
define('LOST_METHODTYPE_ANIMALSFIRST', 'lost');
define('LOST_METHODTYPE_PETPOINT', 'lost');
define('PREFERRED_METHODTYPE_ANIMALSFIRST', 'preferred');

/* Constants to Manage Value Processing */
define('VALUE_PREFIX_SEARCH_FILTER', 'value_search_filter_');

/* Constants to Include Library Files */
define('ADMIN_DIR', 'admin');
define('ASSETS_DIR', 'assets');
define('CSS_DIR', 'css');
define('IMAGES_DIR', 'images');
define('INCLUDE_DIR', 'includes');	
define('LICENSE_DIR', 'license');	
define('PARTIALS_DIR', 'partials');
define('PLUGIN_DIR', 'petmatchpro');
define('PUBLIC_DIR', 'public');
define('SCRIPT_DIR', 'js');
define('TEMPLATES_DIR', 'templates');
define('VIDEO_DIR', 'video');

/* Integration Partner Constants */
define('INTEGRATION_PARTNERS', array('AnimalsFirst' => 'AnimalsFirst', 'PetPoint' => 'PetPoint', 'RescueGroups' => 'RescueGroups'));
define('ANIMALSFIRST', 'AnimalsFirst');
define('ANIMALSFIRST_DIR', 'af');
define('PETPOINT', 'PetPoint');
define('PETPOINT_DIR', 'pp');
define('RESCUEGROUPS', 'RescueGroups');
define('RESCUEGROUPS_DIR', 'rg');
define('SEARCH_RESULT_LIMIT', 199);

/* Constants to Display Animal Details */
define('ALL', 'All');
define('EMPTY_VALUE', 'Not Defined');
define('REFERER_DIRECT', 'direct');
define('REFERER_SEARCH', 'search');

define('ANIMALSFIRST_ADOPT_PROFILE_URL', 'adopt_profile_url');
define('ANIMALSFIRST_ADOPTION_FEE', 'adoption_fee');
define('ANIMALSFIRST_AGE', 'age');
define('ANIMALSFIRST_AGE_DISPLAY', 'display_age');
define('ANIMALSFIRST_ALTERED', 'altered');
define('ANIMALSFIRST_BREED_PRIMARY', 'breed');
define('ANIMALSFIRST_BREED_SECONDARY', 'secondary_breed');
define('ANIMALSFIRST_COLOR_PRIMARY', 'primary_color');
define('ANIMALSFIRST_COLOR_SECONDARY', 'secondary_color');
define('ANIMALSFIRST_DATE_BIRTH', 'birthday');
define('ANIMALSFIRST_DATE_BIRTH_EST', 'est_birthday');
define('ANIMALSFIRST_DATE_INTAKE', 'intake_date');
define('ANIMALSFIRST_DATE_OUTCOME', 'outcome_date');
define('ANIMALSFIRST_DECLAWED', 'declawed');
define('ANIMALSFIRST_DESCRIPTION', 'description');
define('ANIMALSFIRST_ENERGY_LEVEL', 'energy_level');
define('ANIMALSFIRST_FEATURED', 'featured');
define('ANIMALSFIRST_ID', 'id');
define('ANIMALSFIRST_INTAKE_TYPE', 'intake_type');
define('ANIMALSFIRST_KENNEL', 'kennel');
define('ANIMALSFIRST_LOCATION', 'location');
define('ANIMALSFIRST_LOCATION_ADDRESS', 'address');
define('ANIMALSFIRST_LOCATION_ADDRESS_1', 'address_1');
define('ANIMALSFIRST_LOCATION_ADDRESS_2', 'address_2');
define('ANIMALSFIRST_LOCATION_CITY', 'city');
define('ANIMALSFIRST_LOCATION_COUNTRY', 'country');
define('ANIMALSFIRST_LOCATION_FORMATTED', 'google_formatted_address');
define('ANIMALSFIRST_LOCATION_FULL', 'long_address');
define('ANIMALSFIRST_LOCATION_GEOMETRY', 'google_geometry');
define('ANIMALSFIRST_LOCATION_LATITUDE', 'google_lat');
define('ANIMALSFIRST_LOCATION_LONGITUDE', 'google_lng');
define('ANIMALSFIRST_LOCATION_STATE', 'state');
define('ANIMALSFIRST_LOCATION_URL', 'google_address_url');
define('ANIMALSFIRST_LOCATION_ZIP', 'zip');	
define('ANIMALSFIRST_LENGTH_OF_STAY', 'los');
define('ANIMALSFIRST_MEDIA', 'all_photos, youtube_links');
define('ANIMALSFIRST_MICROCHIP', 'microchip');
define('ANIMALSFIRST_NAME', 'name');
define('ANIMALSFIRST_PHOTOS', 'all_photos');
define('ANIMALSFIRST_PHOTO_URL', 'photo_url');
define('ANIMALSFIRST_PROFILE_URL', 'adopt_profile_url');
define('ANIMALSFIRST_GENDER', 'gender');
define('ANIMALSFIRST_SEQ_ID', 'seq_id');
define('ANIMALSFIRST_SHELTER_ID', 'shelter_id');
define('ANIMALSFIRST_SIZE', 'size');
define('ANIMALSFIRST_SPECIES', 'species');
define('ANIMALSFIRST_STATUS', 'status');
define('ANIMALSFIRST_TYPE', 'type');
//define('ANIMALSFIRST_TYPE_PREFERRED', 'paid');
define('ANIMALSFIRST_VIDEOS', 'youtube_links');		
define('ANIMALSFIRST_ORDERBY', 'sortfield');
define('ANIMALSFIRST_SORTORDER', 'sortorder');
define('ANIMALSFIRST_SORTORDER_ASCENDING', 'asc');
define('ANIMALSFIRST_SORTORDER_DESCENDING', 'desc');
define('ANIMALSFIRST_SORTORDER_RANDOM', 'random');

define('ANIMALSFIRST_NON_LIVE_SOURCE', 'demo');

define('PETPOINT_ADOPT_APP_URL', 'adoptionapplicationurl');
define('PETPOINT_ADOPTOR_EMAIL', 'email');
define('PETPOINT_ADOPTOR_ID', 'personid');
define('PETPOINT_ADOPTOR_NAME_FIRST', 'firstname');
define('PETPOINT_ADOPTOR_NAME_LAST', 'lastname');
define('PETPOINT_AGE', 'age');
define('PETPOINT_AGE_GROUP', 'agegroup');
define('PETPOINT_ALTERED', 'altered');
define('PETPOINT_ALTERED_SPAY_NEUTERED', 'sn');
define('PETPOINT_ARN', 'arn');
define('PETPOINT_BANNER_URL', 'bannerurl');
define('PETPOINT_BEHAVIOR_RESULT', 'behaviorresult');
define('PETPOINT_BEHAVIOR_TESTS', 'behaviortestlist');
define('PETPOINT_BREED', 'primarybreed');
define('PETPOINT_BREED_PRIMARY', 'primarybreed');
//define('PETPOINT_PRIMARYBREED', 'primarybreed');
define('PETPOINT_BREED_SECONDARY', 'secondarybreed');
//define('PETPOINT_SECONDARYBREED', 'secondarybreed');
define('PETPOINT_COAT', 'coat');
define('PETPOINT_COLLAR_COLOR', 'collarcolor');
define('PETPOINT_COLLAR_2_COLOR', 'collarcolor2');
define('PETPOINT_COLLAR_TYPE', 'collartype');
define('PETPOINT_COLLAR_2_TYPE', 'collartype2');
define('PETPOINT_COLOR_EYE', 'eyes');
define('PETPOINT_COLOR_PRIMARY', 'primarycolor');
//define('PETPOINT_PRIMARYCOLOR', 'primarycolor');
define('PETPOINT_COLOR_SECONDARY', 'secondarycolor');
define('PETPOINT_DATE_ADOPT', 'adoptiondate');
define('PETPOINT_DATE_BIRTH', 'dateofbirth');
//define('PETPOINT_BIRTHDATE', 'dateofbirth');
define('PETPOINT_DATE_FIRST', 'datefirst');
define('PETPOINT_DATE_FOUND', 'founddate');
define('PETPOINT_DATE_INTAKE', 'lastintakedate');
define('PETPOINT_DATE_LAST', 'datelast');
define('PETPOINT_DATE_LOST', 'lostdate');
define('PETPOINT_DATE_RELEASE', 'releasedate');
define('PETPOINT_DECLAWED', 'declawed');
define('PETPOINT_DESCRIPTION', 'dsc');
define('PETPOINT_EAR', 'ears');
define('PETPOINT_FEATURED', 'featured');
define('PETPOINT_HOUSETRAINED', 'housetrained');
define('PETPOINT_ID', 'id');
define('PETPOINT_ID_ANIMAL', 'animalid');
define('PETPOINT_ID_BUDDY', 'buddyid');
define('PETPOINT_ID_COMPANY', 'companyid');
define('PETPOINT_ID_SPECIES', 'speciesid');
define('PETPOINT_ID_STAGE', 'stageid');
//define('PETPOINT_SPECIESID', 'speciesid');
define('PETPOINT_JURISDICTION', 'jurisdiction');
define('PETPOINT_LIVED_ANIMALS', 'livedwithanimals');
define('PETPOINT_LIVED_ANIMALS_TYPES', 'livedwithanimaltypes');
define('PETPOINT_LIVED_KIDS', 'livedwithchildren');
define('PETPOINT_LIVED_PREVIOUS', 'prevenvironment');
define('PETPOINT_LIVED_PREVIOUS_DURATION', 'timeinformerhome');
define('PETPOINT_LOCATION', 'location');
define('PETPOINT_LOCATION_FOUND', 'foundlocation');
define('PETPOINT_LOCATION_FOUND_ADDRESS', 'foundaddress');
define('PETPOINT_LOCATION_FOUND_CITY', 'city');
define('PETPOINT_LOCATION_FOUND_STATE', 'state');
define('PETPOINT_LOCATION_LOST', 'lostlocation');
define('PETPOINT_LOCATION_LOST_ADDRESS', 'lostaddress');
define('PETPOINT_LOCATION_LOST_CITY', 'city');
define('PETPOINT_LOCATION_LOST_STATE', 'state');
define('PETPOINT_LOCATION_SUB', 'sublocation');
define('PETPOINT_MARKS', 'marks');
define('PETPOINT_MARKS_PATTERN', 'colorpattern');
define('PETPOINT_MEDIA', 'photo, videoid');
define('PETPOINT_MEMO', 'memolist');
define('PETPOINT_MICROCHIP', 'chipnumber');
define('PETPOINT_NAME', 'name');
define('PETPOINT_NAME_ANIMAL', 'animalname');
define('PETPOINT_OK_CATS', 'nocats');
//define('PETPOINT_OKCATS', 'nocats');
define('PETPOINT_OK_DOGS', 'nodogs');
//define('PETPOINT_OKDOGS', 'nodogs');
define('PETPOINT_OK_KIDS', 'nokids');
//define('PETPOINT_OKKIDS', 'nokids');
define('PETPOINT_ONHOLD', 'onhold');
define('PETPOINT_ORDERBY', 'orderby');
define('PETPOINT_PHOTO', 'photo');
define('PETPOINT_PRICE', 'price');
define('PETPOINT_SEX', 'sex');
define('PETPOINT_SITE', 'site');
define('PETPOINT_SIZE', 'size');
define('PETPOINT_SPECIAL_NEEDS', 'specialneeds');
//define('PETPOINT_SPECIALNEEDS', 'specialneeds');
define('PETPOINT_SPECIES', 'species');
define('PETPOINT_STAGE', 'stage');
define('PETPOINT_SURRENDER', 'reasonforsurrender');
define('PETPOINT_TAIL', 'tail');
define('PETPOINT_TYPE', 'animaltype');
define('PETPOINT_TYPE_FOUND', 'foundtype');
define('PETPOINT_TYPE_SEARCH', 'searchoption');
define('PETPOINT_VIDEO', 'videoid');
define('PETPOINT_WEIGHT', 'bodyweight');
define('PETPOINT_WEIGHT_UOM', 'bodyweightunit');
define('PETPOINT_WILDLIFE_CAUSE', 'wildlifeintakecause');
define('PETPOINT_WILDLIFE_INJURY', 'wildlifeintakeinjury');

define('RESCUEGROUPS_ACTIVITY_LEVEL', 'animalactivitylevel');
define('RESCUEGROUPS_ADOPTION_FEE', 'animaladoptionfee');
define('RESCUEGROUPS_ADOPTION_PENDING', 'animaladoptionpending');
define('RESCUEGROUPS_AFFECTIONATE', 'animalaffectionate');
define('RESCUEGROUPS_AGE', 'animalgeneralage');
define('RESCUEGROUPS_ALLERGIES', 'animalhasallergies');
define('RESCUEGROUPS_ALTERED', 'animalaltered');
define('RESCUEGROUPS_APARTMENT', 'animalapartment');
define('RESCUEGROUPS_BREED', 'animalbreed');
define('RESCUEGROUPS_BREED_MIXED', 'animalmixedbreed');
define('RESCUEGROUPS_BREED_PRIMARY', 'animalprimarybreed');
define('RESCUEGROUPS_BREED_SECONDARY', 'animalsecondarybreed');
define('RESCUEGROUPS_CAR', 'animalgoodincar');
define('RESCUEGROUPS_COAT_LENGTH', 'animalcoatlength');
define('RESCUEGROUPS_COLOR_DETAILS', 'animalcolordetails');
define('RESCUEGROUPS_COLOR_EYE', 'animaleyecolor');
define('RESCUEGROUPS_COLOR_PRIMARY', 'animalcolor');
define('RESCUEGROUPS_COMPANION_ANIMAL', 'animalcourtesy');
define('RESCUEGROUPS_COURTESY', 'animalneedscompanionanimal');
define('RESCUEGROUPS_CRATE', 'animalcratetrained');
define('RESCUEGROUPS_DATE_AVAILABLE', 'animalavailabledate');
define('RESCUEGROUPS_DATE_BIRTH', 'animalbirthdate');
define('RESCUEGROUPS_DATE_BIRTH_EXACT', 'animalbirthdateexact');
define('RESCUEGROUPS_DATE_FOUND', 'animalfounddate');
define('RESCUEGROUPS_DATE_UPDATED', 'animalupdateddate');
define('RESCUEGROUPS_DECLAWED', 'animaldeclawed');
define('RESCUEGROUPS_DESCRIPTION', 'animaldescriptionplain');
define('RESCUEGROUPS_DESCRIPTION_SUMMARY', 'animalsummary');
define('RESCUEGROUPS_DROOLS', 'animaldrools');
define('RESCUEGROUPS_EAGER', 'animaleagertoplease');
define('RESCUEGROUPS_EAR', 'animaleartype');
define('RESCUEGROUPS_ENERGY', 'animalenergylevel');
define('RESCUEGROUPS_ESCAPES', 'animalescapes');
define('RESCUEGROUPS_EVEN_TEMPERED', 'animaleventempered');
define('RESCUEGROUPS_EXERCISE', 'animalexerciseneeds');
define('RESCUEGROUPS_FENCE', 'animalfence');
define('RESCUEGROUPS_FETCHES', 'animalfetches');
define('RESCUEGROUPS_FOSTER', 'animalneedsfoster');
define('RESCUEGROUPS_FOUND', 'animalFound');
define('RESCUEGROUPS_FOUND_ZIP', 'animalfoundpostalcode');
define('RESCUEGROUPS_GENTLE', 'animalgentle');
define('RESCUEGROUPS_GOOFY', 'animalgoofy');
define('RESCUEGROUPS_GROOMING', 'animalgroomingneeds');
define('RESCUEGROUPS_HEARING', 'animalhearingimpaired');
define('RESCUEGROUPS_HOUSETRAINED', 'animalhousetrained');
define('RESCUEGROUPS_HOUSETRAINED_INFO', 'animalnothousetrainedreason');
define('RESCUEGROUPS_HYPOALLERGENIC', 'animalhypoallergenic');
define('RESCUEGROUPS_ID', 'animalid');
define('RESCUEGROUPS_INDEPENDENT', 'animalindependent');
define('RESCUEGROUPS_INDOOR_OUTDOOR', 'animalindooroutdoor');
define('RESCUEGROUPS_INTELLIGENT', 'animalintelligent');
define('RESCUEGROUPS_LAP', 'animallap');
define('RESCUEGROUPS_LEASH', 'animalleashtrained');
define('RESCUEGROUPS_LOCATION', 'locationname');
define('RESCUEGROUPS_LOCATION_ADDRESS', 'locationaddress');
define('RESCUEGROUPS_LOCATION_CITY', 'locationcity');
define('RESCUEGROUPS_LOCATION_COUNTRY', 'locationcountry');
define('RESCUEGROUPS_LOCATION_PHONE', 'locationphone');
define('RESCUEGROUPS_LOCATION_STATE', 'locationstate');
define('RESCUEGROUPS_LOCATION_URL', 'locationurl');
define('RESCUEGROUPS_LOCATION_ZIP', 'locationpostalcode');
define('RESCUEGROUPS_MARKS_DISTINGUISHING', 'animaldistinguishingmarks');
define('RESCUEGROUPS_MARKS_PATTERN', 'animalpattern');
define('RESCUEGROUPS_MEDIA', 'animalpictures, animalvideos');
define('RESCUEGROUPS_MEDICAL', 'animalongoingmedical');
define('RESCUEGROUPS_MICROCHIPPED', 'animalmicrochipped');
define('RESCUEGROUPS_NAME', 'animalname');
define('RESCUEGROUPS_NEW_PEOPLE', 'animalnewpeople');
define('RESCUEGROUPS_OBEDIENCE_TRAINING', 'animalobediencetraining');
define('RESCUEGROUPS_OBEDIENT', 'animalobedient');
define('RESCUEGROUPS_OK_ADULTS', 'animalokwithadults');
define('RESCUEGROUPS_OK_CATS', 'animalokwithcats');
define('RESCUEGROUPS_OK_COLD', 'animalnocold');
define('RESCUEGROUPS_OK_DOGS', 'animalokwithdogs');
define('RESCUEGROUPS_OK_DOGS_FEMALE', 'animalnofemaledogs');
define('RESCUEGROUPS_OK_DOGS_LARGE', 'animalnolargedogs');
define('RESCUEGROUPS_OK_DOGS_MALE', 'animalnomaledogs');
define('RESCUEGROUPS_OK_DOGS_SMALL', 'animalnosmalldogs');
define('RESCUEGROUPS_OK_FARM', 'animalokwithfarmanimals');
define('RESCUEGROUPS_OK_HEAT', 'animalnoheat');
define('RESCUEGROUPS_OK_KIDS', 'animalokwithkids');
define('RESCUEGROUPS_OK_KIDS_OLDER', 'animalolderkidsonly');
define('RESCUEGROUPS_OK_SENIORS', 'animalokforseniors');
define('RESCUEGROUPS_OWNER_EXPERIENCE', 'animalownerexperience');
define('RESCUEGROUPS_ORDERBY', 'sortfield');
define('RESCUEGROUPS_PHOTOS', 'animalpictures');
define('RESCUEGROUPS_PHOTO_THUMBNAIL_URL', 'urlSecureThumbnail');
//define('RESCUEGROUPS_PHOTO_THUMBNAIL_URL', 'animalThumbnailurl');
define('RESCUEGROUPS_PLAYFUL', 'animalplayful');
define('RESCUEGROUPS_PREDATORY', 'animalpredatory');
define('RESCUEGROUPS_PROFILE_URL', 'animalurl');
define('RESCUEGROUPS_PROTECTIVE', 'animalprotective');
define('RESCUEGROUPS_SEARCH_STRING', 'animalsearchstring');
define('RESCUEGROUPS_SEX', 'animalsex');
define('RESCUEGROUPS_SHEDDING', 'animalshedding');
define('RESCUEGROUPS_SIZE', 'animalsizecurrent');
define('RESCUEGROUPS_SIZE_POTENTIAL', 'animalsizepotential');
define('RESCUEGROUPS_SIZE_POTENTIAL_GENERAL', 'animalgeneralsizepotential');
define('RESCUEGROUPS_SIZE_UOM', 'animalsizeuom');
define('RESCUEGROUPS_SKITTISH', 'animalskittish');
define('RESCUEGROUPS_SORTORDER', 'sortorder');
define('RESCUEGROUPS_SORTORDER_ASCENDING', 'asc');
define('RESCUEGROUPS_SORTORDER_DESCENDING', 'desc');
define('RESCUEGROUPS_SPECIAL_DIET', 'animalspecialdiet');
define('RESCUEGROUPS_SPECIAL_NEEDS', 'animalspecialneeds');
define('RESCUEGROUPS_SPECIAL_NEEDS_INFO', 'animalspecialneedsdescription');
define('RESCUEGROUPS_SPECIES', 'animalspecies');
define('RESCUEGROUPS_SPONSORABLE', 'animalsponsorable');
define('RESCUEGROUPS_SPONSORS', 'animalsponsors');
define('RESCUEGROUPS_SPONSORSHIP_INFO', 'animalsponsorshipdetails');
define('RESCUEGROUPS_SPONSORSHIP_MIN', 'animalsponsorshipminimum');
define('RESCUEGROUPS_SWIMS', 'animalswims');
define('RESCUEGROUPS_STATUS', 'animalstatus');
define('RESCUEGROUPS_TAIL', 'animaltailtype');
define('RESCUEGROUPS_TIMID', 'animaltimid');
define('RESCUEGROUPS_TOYS', 'animalplaystoys');
define('RESCUEGROUPS_UPTODATE', 'animaluptodate');
define('RESCUEGROUPS_VIDEOS', 'animalvideourls');
define('RESCUEGROUPS_VIDEO_URL', 'videoUrl');
define('RESCUEGROUPS_VISION', 'animalsightimpaired');
define('RESCUEGROUPS_VOCAL', 'animalvocal');
define('RESCUEGROUPS_YARD', 'animalyardrequired');
define('RESCUEGROUPS_EMPTY', 'unknown');

add_filter( 'plugin_row_meta', 'wk_plugin_row_meta', 10, 2 );

function wk_plugin_row_meta( $links, $file ) {
    if ( plugin_basename( __FILE__ ) == $file ) {
        $row_meta = array(
            'Support'    => '<a href="' . esc_url( constant('PMP_LOGIN') ) . '" target="_blank" aria-label="' . esc_attr__( 'Plugin Additional Links', 'domain' ) . '" style="color:green;">' . esc_html__( 'Support', 'domain' ) . '</a>'
        );
        return array_merge( $links, $row_meta );
    }
    return (array) $links;
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pet-match-pro-activator.php
 */
function activate_pet_match_pro() {
	ob_start();
    require_once plugin_dir_path( __FILE__ ) . constant('INCLUDE_DIR') . '/class-pet-match-pro-activator.php';    
    Pet_Match_Pro_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pet-match-pro-deactivator.php
 */
function deactivate_pet_match_pro() {
	ob_start();
    require_once plugin_dir_path( __FILE__ ) . constant('INCLUDE_DIR') . '/class-pet-match-pro-deactivator.php';
    Pet_Match_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pet_match_pro' );
register_deactivation_hook( __FILE__, 'deactivate_pet_match_pro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . constant('INCLUDE_DIR') . '/class-pet-match-pro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function run_pet_match_pro() {
    $plugin = new Pet_Match_Pro();
    $plugin->run();
}

run_pet_match_pro();

/* Function to Prevent Warning Messages in Admin */
function app_output_buffer() {
    ob_start();
} 

add_action('init', 'app_output_buffer');