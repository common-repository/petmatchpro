<?php 

class Pet_Match_Pro_RG_Options {

	public $api_activated;
    public static $dogBreeds = array('' => 'Any', '101'=>'Abruzzese Mastiff','696'=>'Affenpinscher','621'=>'Afghan Hound','95'=>'Aidi','10'=>'Akbash','650'=>'Akita','11'=>'Alano Espanol','13'=>'Alaskan Husky','14'=>'Alaskan Klee Kai','651'=>'Alaskan Malamute','15'=>'Alopekis','16'=>'Alpine Dachsbracke','17'=>'American Bandogge Mastiff','18'=>'American Blue Gascon Hound','791'=>'American Blue Heeler','19'=>'American Bullnese','780'=>'American Eskimo','20'=>'American Indian Dog','21'=>'American Lo-Sze Pugg','22'=>'American White Shepherd','796'=>'Anatolian Shepherd Dog','23'=>'Appenzeller Sennenhunde','24'=>'Argentine Dogo','93'=>'Armant','733'=>'Australian Cattle Dog','754'=>'Australian Kelpie','806'=>'Australian Koolie','734'=>'Australian Shepherd','235394'=>'Australian Shepherd, Miniature','802'=>'Australian Stumpy Tail tle ','817'=>'Austrian Pinscher','25'=>'Azawakh','235395'=>'Azores Cattle Dog','110'=>'Banter Bulldogge','354'=>'Barbet','622'=>'Basenji','623'=>'Basset Hound','624'=>'Beagle','735'=>'Bearded Collie','26'=>'Beauceron','755'=>'Belgian Griffon','736'=>'Belgian Shepherd Dog','737'=>'Belgian Shepherd Dog (Groenendael)','738'=>'Belgian Shepherd Dog (Laekenois)','739'=>'Belgian Shepherd Dog (Malinois)','740'=>'Belgian Shepherd Dog (Tervuren)','27'=>'Bergamasco','652'=>'Bernese Mountain Dog','716'=>'Bichon Frise','4'=>'Bichon Havanais','625'=>'Bloodhound','28'=>'Boerboel','3'=>'Bolognese','676'=>'Border Collie','626'=>'Borzoi','98'=>'Bouvier des Ardennes','742'=>'Bouvier des Flandres','653'=>'Boxer','29'=>'Bracco Italiano','590'=>'Braque Francais Gascogne','591'=>'Braque Francais Pyrenees','743'=>'Briard','609'=>'Brittany','235396'=>'Brittany, French','718'=>'Bulldog','12'=>'Bulldog, Alpaha Blue Blood','85'=>'Bulldog, American','795'=>'Bulldog, English','722'=>'Bulldog, French','235397'=>'Bulldog, Victorian','654'=>'Bullmastiff','97'=>'Cambodian Razorback Dog','655'=>'Canaan Dog','656'=>'Canadian Eskimo','776'=>'Cane Corso','102'=>'Cane Di Oropa','103'=>'Cane Toccatore','83'=>'Carolina Dog','816'=>'Carpathian Shepherd Dog','107'=>'Castro Laboreiro Dog','801'=>'Catahoula Leopard dog','30'=>'Caucasian Mountain Dog','31'=>'Central Asian Outcharka Shepherd','32'=>'Central Asian Outcharka Shepherd','698'=>'Chihuahua, Long Coat','699'=>'Chihuahua, Smooth Coat','700'=>'Chinese Crested','235398'=>'Chinese Foo','719'=>'Chinese Shar-Pei','33'=>'Chinook','720'=>'Chow Chow','818'=>'CimarrÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€šÃ‚Â ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡Ãƒâ€šÃ‚Â¬ÃƒÆ’Ã¢â‚¬Â¦Ãƒâ€šÃ‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â³n Uruguayo','235399'=>'Cirneco dell Etna','744'=>'Collie, Rough','745'=>'Collie, Smooth','627'=>'Coonhound','34'=>'Coonhound, American English','35'=>'Coonhound, Black and Tan','782'=>'Coonhound, Bluetick','36'=>'Coonhound, Redbone','790'=>'Coonhound, Treeing Walker','756'=>'Coton de Tulear','794'=>'Coton de Tulear','815'=>'Croatian Sheep','757'=>'Crossbreed','82'=>'Cur, Black-Mouth','235400'=>'Cur, Black-Mouth','81'=>'Cur, Mountain','37'=>'Czechoslovakian Wolfdog','628'=>'Dachshund, Miniature Long Haired','629'=>'Dachshund, Miniature Smooth Haired','633'=>'Dachshund, Miniature Wire Haired','631'=>'Dachshund, Standard Long Haired','630'=>'Dachshund, Standard Smooth Haired','632'=>'Dachshund, Standard Wire Haired','721'=>'Dalmatian','96'=>'Damchi','235401'=>'Danish Broholmer','634'=>'Deerhound, Scottish','657'=>'Doberman Pinscher','778'=>'Dogue de Bordeaux','355'=>'Dosa Inu','635'=>'Drever','758'=>'Dunker Hound','353'=>'Dutch Sheepdog','235402'=>'Dutch Shepherd Dog','104'=>'Dutch Smoushond','781'=>'English Mastiff','813'=>'English Shepherd','38'=>'Entlebucher Mountain Dog','759'=>'Estrela Mountain Dog','760'=>'Eurasier','814'=>'Feist','761'=>'Fila Brasileiro','39'=>'Finnish Lapphund','636'=>'Finnish Spitz','637'=>'Foxhound, American','638'=>'Foxhound, English','792'=>'German Pinscher','746'=>'German Shepherd Dog','747'=>'German Shepherd, King','762'=>'German Spitz','658'=>'Great Dane','659'=>'Great Pyrenees','763'=>'Greater Swiss Mountain Dog','40'=>'Greenland Dog','639'=>'Greyhound','41'=>'Griffon Vendeen, Grand Basset','640'=>'Griffon Vendeen, Petit Basset','702'=>'Griffon, Brussels','592'=>'Griffon, Wirehaired Pointing','106'=>'Haldenstoever','641'=>'Harrier','787'=>'Havanese','89'=>'Himalayan Sheepdog','235393'=>'Hokkaido (Ainu)','6'=>'Hound','352'=>'Hovawart','811'=>'Hygenhund','642'=>'Ibizan Hound','785'=>'Icelandic Sheepdog','643'=>'Irish Wolfhound','703'=>'Italian Greyhound','704'=>'Japanese Chin','723'=>'Japanese Spitz','42'=>'Kai Ken','99'=>'Karakachan Dog','43'=>'Karelian Bear Dog','724'=>'Keeshond','100'=>'Kerry Beagle','660'=>'Komondor','44'=>'Kooikerhondje','804'=>'Korean Jindo','820'=>'Kromfohrlander, Smooth Coat','821'=>'Kromfohrlander, Wire Haired','661'=>'Kuvasz','235405'=>'Lacy Dog','45'=>'Lagotto Romagnolo','767'=>'Lancashire Heeler','768'=>'Landseer','662'=>'Leonberger','725'=>'Lhasa Apso','46'=>'Lowchen','705'=>'Maltese','706'=>'Manchester Toy Terrier','235406'=>'Maremma Sheepdog','663'=>'Mastiff','235404'=>'Mastiff','84'=>'McNab Herding Dog','356'=>'Mee Kyun Dosa','70'=>'Mexican Hairless','707'=>'Mexican Hairless','710'=>'Miniature Pinscher','797'=>'Miniature Pinscher','111'=>'Mixed Breed, Large over 44 lbs fully grown','112'=>'Mixed Breed, Medium up to 44 lbs fully grown','113'=>'Mixed Breed, Small under 24 lbs fully grown','47'=>'Mudi','769'=>'Munsterlander, Large','784'=>'Munsterlander,Small','770'=>'Neapolitan Mastiff','2'=>'New Guinea Singing Dog','90'=>'New Zealand Heading Dog','664'=>'Newfoundland','48'=>'Norbottenspets','749'=>'Norwegian Buhund','644'=>'Norwegian Elkhound','49'=>'Norwegian Lundehund','750'=>'Old English Sheepdog','235407'=>'Olde English Bulldogge','645'=>'Otterhound','708'=>'Papillon','709'=>'Pekingese','109'=>'Perdiguero Gallego','50'=>'Perro de Presa Canario','51'=>'Peruvian Inca Orchid','646'=>'Pharaoh Hound','789'=>'Picardy Shepherd','52'=>'Plott Hound','94'=>'Podenco Canario','108'=>'Podenco Gallego','53'=>'Podengo Portugeso','593'=>'Pointer','594'=>'Pointer, German Long Haired','595'=>'Pointer, German Short Haired','793'=>'Pointer, German Short Haired','596'=>'Pointer, German Wire Haired','357'=>'Polish Lowland Sheepdog','711'=>'Pomeranian','726'=>'Poodle, Miniature','727'=>'Poodle, Standard','712'=>'Poodle, Toy','351'=>'Poong-San Dog','92'=>'Portugese Sheepdog','665'=>'Portuguese Water Dog','597'=>'Pudelpointer','713'=>'Pug','748'=>'Puli','54'=>'Pumi','55'=>'Pyrenean Shepherd','741'=>'Pyrenean Shepherd','56'=>'Rafeiro do Alentejo','8'=>'Retriever','598'=>'Retriever, Chesapeake Bay','599'=>'Retriever, Curly Coated','600'=>'Retriever, Flat-Coated','601'=>'Retriever, Golden','602'=>'Retriever, Labrador','603'=>'Retriever, Nova Scotia Duck Tolling','647'=>'Rhodesian Ridgeback','666'=>'Rottweiler','105'=>'Saarloos Wolfdog','671'=>'Saint Bernard','648'=>'Saluki','667'=>'Samoyed','728'=>'Schipperke','668'=>'Schnauzer, Giant','688'=>'Schnauzer, Miniature','669'=>'Schnauzer, Standard','812'=>'Schweizer Laufhund Swiss Hound','604'=>'Setter, English','605'=>'Setter, Gordon','606'=>'Setter, Irish','764'=>'Setter, Irish','7'=>'Shepherd','235409'=>'Shepherd, White Swiss','751'=>'Shetland Sheepdog','729'=>'Shiba Inu','730'=>'Shih Tzu','235410'=>'Shiloh Shepherd','670'=>'Siberian Husky','91'=>'Silken Windhound','57'=>'Sloughi','235411'=>'South Russian Ovcharkas','9'=>'Spaniel','607'=>'Spaniel, American Cocker','608'=>'Spaniel, American Water','58'=>'Spaniel, Boykin','697'=>'Spaniel, Cavalier King Charles','610'=>'Spaniel, Clumber','611'=>'Spaniel, English Cocker','612'=>'Spaniel, English Springer','701'=>'Spaniel, English Toy','613'=>'Spaniel, Field','614'=>'Spaniel, French','615'=>'Spaniel, Irish Water','616'=>'Spaniel, Sussex','731'=>'Spaniel, Tibetan','617'=>'Spaniel, Welsh Springer','805'=>'Spanish Water Dog','1'=>'Spinone Italiano','59'=>'Stabyhoun','772'=>'Swedish Elkhound','803'=>'Swedish Vallhund','773'=>'Tahltan Bear Dog','5'=>'Terrier','60'=>'Terrier, Abyssinian Sand','672'=>'Terrier, Airedale','61'=>'Terrier, American Crested Sand','808'=>'Terrier, American Hairless','87'=>'Terrier, American Pit Bull','771'=>'Terrier, American Pit Bull','673'=>'Terrier, American Staffordshire','674'=>'Terrier, Australian','675'=>'Terrier, Bedlington','62'=>'Terrier, Black Russian','777'=>'Terrier, Border','717'=>'Terrier, Boston','819'=>'Terrier, Brazilian','677'=>'Terrier, Bull','678'=>'Terrier, Cairn','63'=>'Terrier, Cesky','679'=>'Terrier, Dandie Dinmont','799'=>'Terrier, English Staffordshire','680'=>'Terrier, Fox, Smooth Haired','80'=>'Terrier, Fox, Toy','681'=>'Terrier, Fox, Wire Haired','64'=>'Terrier, Glen of Imaal','682'=>'Terrier, Irish','765'=>'Terrier, Jack Russell','766'=>'Terrier, Japanese','683'=>'Terrier, Kerry Blue','684'=>'Terrier, Lakeland','685'=>'Terrier, Manchester','65'=>'Terrier, Miniature Bull','686'=>'Terrier, Norfolk','687'=>'Terrier, Norwich','66'=>'Terrier, Parson Russell','86'=>'Terrier, Patterdale','798'=>'Terrier, Rat','809'=>'Terrier, Russell','689'=>'Terrier, Scottish','690'=>'Terrier, Sealyham','714'=>'Terrier, Silky','691'=>'Terrier, Skye','692'=>'Terrier, Soft Coated Wheaten','693'=>'Terrier, Staffordshire Bull','732'=>'Terrier, Tibetan','694'=>'Terrier, Welsh','695'=>'Terrier, West Highland White','715'=>'Terrier, Yorkshire','67'=>'Thai Ridgeback','235408'=>'The Royal Bahamian Potcake (Abaco Potcake)','774'=>'Tibetan Mastiff','68'=>'Tosa','69'=>'Treeing Tennessee Brindle','618'=>'Vizsla, Smooth Haired','619'=>'Vizsla, Wire Haired','235403'=>'Volpino Italiano','620'=>'Weimaraner','752'=>'Welsh Corgi, Cardigan','753'=>'Welsh Corgi, Pembroke','649'=>'Whippet','810'=>'White Swiss Shepherd (Berger Blanc Suisse)');
    public static $catBreeds = array('' => 'Any','783'=>'Abyssinian','71'=>'American Bobtail','358'=>'American Curl','360'=>'American Shorthair','361'=>'American Wirehair','362'=>'Angora','235412'=>'Asian','363'=>'Balinese','364'=>'Bengal','365'=>'Birman','367'=>'Bombay','368'=>'British Shorthair','369'=>'British Shorthair','370'=>'Burmese','235413'=>'Burmilla','371'=>'Calico','235414'=>'Californian Spangled','372'=>'Chartreux','235415'=>'Chausie','374'=>'Colourpoint Shorthair','375'=>'Cornish Rex','350'=>'Crossbreed','376'=>'Cymric','377'=>'Devon Rex','378'=>'Domestic Longhair','775'=>'Domestic Medium Hair','379'=>'Domestic Shorthair','380'=>'Egyptian Mau','235417'=>'European Shorthair','381'=>'Exotic Longhair','382'=>'Exotic Shorthair','383'=>'Feral','359'=>'Havana Brown','384'=>'Himalayan','385'=>'Japanese Bobtail','72'=>'Javanese','405'=>'Khao-Manee','387'=>'Korat','73'=>'LaPerm','235416'=>'Lynx, Desert','388'=>'Maine Coon','389'=>'Manx','366'=>'Munchkin','390'=>'Norwegian Forest Cat','391'=>'Ocicat','235418'=>'Oriental Longhair','373'=>'Oriental Shorthair','392'=>'Oriental Shorthair','386'=>'Persian','393'=>'Persian','74'=>'Pixie-Bob','75'=>'RagaMuffin','394'=>'Ragdoll','395'=>'Russian Blue','235419'=>'Savannah','396'=>'Scottish Fold','76'=>'Selkirk Rex','397'=>'Siamese','800'=>'Siberian','398'=>'Singapura','399'=>'Snowshoe','77'=>'Sokoke','400'=>'Somali','401'=>'Sphynx','235420'=>'Tiffanie','402'=>'Tonkinese','235421'=>'Toyger','78'=>'Turkish Angora','403'=>'Turkish Van','404'=>'Vermont Coon','79'=>'York Chocolate');

    private $adminFunction;
    private $animalDetailFunction;    

	public $pmpRGAdminInfo;			/* Admin Hover Help Text */   
	public $PMPLicenseTypeID;	
	public $pmpLicenseKey;
	
    private $partialsDir;
    private $partialsAdminDir;

    public function __construct( $api_activated ) {

        global $pmpAdminInfo;			/* Admin Hover Help Text */

        $this->api_activated = $api_activated;	

		/* Include Class Defining Functions for Processing Animal Searches */
		$functionsFile = 'class-pet-match-pro-functions.php';
		require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/' . $functionsFile;
		$this->adminFunction = new PetMatchProFunctions();  

		/* Include Class Defining Animal Detail Functions */
		$detailsFile = 'class-pet-match-pro-rg-detail-functions.php';
		require_once constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/' . $detailsFile;		
		$this->animalDetailFunction = new PetMatchProAnimalDetailFunctions();  

       	$this->partialsDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('INCLUDE_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/' . constant('PARTIALS_DIR') . '/';
       	$this->partialsAdminDir = constant('PET_MATCH_PRO_PATH') . '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/';

        /* Get RescueGroups Info Text for Use as Title Text for Filter and Label Fields in Settings */
        $homeDir = get_home_path();
        $adminInfoFile = 'pmp-admin-info.php';
        $pmpAdminInfo = array();
        if ( (is_dir(get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/')) && (is_file(get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/' . $adminInfoFile)) ) {
            $requireFile = get_stylesheet_directory(dirname(__FILE__)) . '/petmatchpro/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/' . $adminInfoFile;
        } else {
            $requireFile = $this->partialsAdminDir . $adminInfoFile;
        }
        require($requireFile);   

       	$this->PMPLicenseTypeID = (int) get_option('PMP_License_Type_ID'); /* Manage Options */   
		if ( $this->PMPLicenseTypeID == 0 ) {
			$this->PMPLicenseTypeID = constant('FREE_LEVEL');
		} 
		$this->pmpLicenseKey = get_option('PMP_lic_Key');
    }



    public function display_form(){

        if($this->api_activated == true){

            settings_fields( 'pet-match-pro-filter-options' );

            //$this->initialize_filter_options();

            do_settings_sections( 'pet-match-pro-filter-options' );

            //submit_button('Save Filters');    

        }  else {

			echo 'not activated';

        }

    }

                

    public function initialize_filter_options() {

    

    global $pmpAdminInfo;			/* Admin Hover Help Text */

            

		if(isset($_REQUEST['page']) && $_REQUEST['page']=='pet-match-pro-options') {

			 settings_fields('pet-match-pro-filter-options'); //prolific  14-09-2022

		}

		do_settings_sections( 'pet-match-pro-filter-options' );

        

        add_settings_section(

			'filter_settings_section',			            

			__( '', 'pet-match-pro-plugin' ),		        

			array( $this->adminFunction, 'filter_options_callback'),	    

			'pet-match-pro-filter-options'		                

		);

       	//echo "License Type ID = " . $PMPLicenseTypeID . '<br>'; 

       	//$PMPLicenseTypeID = 3;	       

		/* Include Required Function Fiels */

		//$functionsFile = 'pmp-filter-functions.php';

		//$filePath = '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/' . constant('RESCUEGROUPS_DIR') . '/';

		//$filePath = '/' . constant('ADMIN_DIR') . '/' . constant('PARTIALS_DIR') . '/';

		$filterLevelFile = 'pmp-option-levels-filter.php';

	   	$requireFile = $this->partialsAdminDir . $filterLevelFile;

       	//echo 'Require File = ' . $requireFile . '<br>';

	    require $requireFile;

	    

		//echo 'PMP License Type ID = ' . $PMPLicenseTypeID . '<br>';

		//$PMPLicenseTypeID = 3; 

		//echo 'Reset PMP License Type ID to ' . $PMPLicenseTypeID . '<br>';

		

		//echo 'level_search_filters_adopt = ' . $pmpOptionLevelsFilter['level_search_filters_adopt'] . '<br>';

		if ( (array_key_exists('level_search_filters_adopt', $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_filters_adopt']) && (!empty($this->pmpLicenseKey)) ) {

			//echo 'Enable level_search_filters_adopt<br>';

			$classSearchFilter = 'pmp-search-filters-adopt';

		} else {

			//echo 'Disable level_search_filters_adopt<br>';		

			$classSearchFilter = 'pmp-option-disable';

		}			

		

       	$adoptSearchFilter = $this->adminFunction->Filter_Option_Values('adopt', $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_FILTER'), constant('LABEL_PREFIX_SEARCH_FILTER'));   



		add_settings_field(

			'adopt_search_criteria',

			__( 'Default Adopt Search Criteria', 'pet-match-pro-plugin' ),

			array( $this->adminFunction, 'checkbox_element_callback'),

			'pet-match-pro-filter-options',	            

			'filter_settings_section',

            array('pet-match-pro-filter-options',

            	'adopt_search_criteria',

                $this->adminFunction->keyAndLabel($adoptSearchFilter),

                $this->pmpRGAdminInfo['adopt_search_criteria'],

                'class' => $classSearchFilter

            )

		);
		
		if ( (array_key_exists('level_search_sortfield_adopt', $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_sortfield_adopt']) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_sortfield_adopt<br>';
			$classSearchSortField = 'pmp-search-sortfield-adopt';
		} else {
			//echo 'Disable level_search_sortfield_adopt<br>';		
			$classSearchSortField = 'pmp-option-disable';
		}							

       	$adoptSearchSort = $this->adminFunction->Filter_Option_Values('adopt', $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_SORT'), constant('LABEL_PREFIX_SEARCH_SORT'));  
       	if ( (is_array($adoptSearchSort)) && (empty($adoptSearchSort)) ) {
       		$adoptSearchSort[constant('RESCUEGROUPS_NAME')] = ucfirst(constant('RESCUEGROUPS_NAME'));
       	}
		//echo '<pre>Adopt Sort Fields Returned from Function<br>'; print_r($adoptSearchSort); echo '</pre>';
       	if ( (!array_key_exists('adopt_search_sortfield', $this->pmpRGAdminInfo)) ) {
       		$this->pmpRGAdminInfo['adopt_search_sortfield'] = '';
       	}

		add_settings_field(
			'adopt_search_sortfield',
			__( 'Adopt Animal Search Sort Field', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	'adopt_search_sortfield',
                $this->adminFunction->keyAndLabel($adoptSearchSort),
                $this->pmpRGAdminInfo['adopt_search_sortfield'],
                'class' => $classSearchSortField
			)
		);
	
		if ( (array_key_exists('level_search_sortorder_adopt', $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_sortorder_adopt']) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_sortorder_adopt<br>';
			$classSearchSortOrder = 'pmp-search-sortorder-adopt';
		} else {
			//echo 'Disable level_search_sortorder_adopt<br>';		
			$classSearchSortOrder = 'pmp-option-disable';
		}							

   		$adoptSearchSortOrder = $this->adminFunction->Filter_Option_Values('adopt', $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_ORDER'), constant('LABEL_PREFIX_SEARCH_ORDER'));  
       	if ( (!array_key_exists('adopt_search_sortorder', $this->pmpRGAdminInfo)) ) {
       		$this->pmpRGAdminInfo['adopt_search_sortorder'] = '';
       	}

		add_settings_field(
			'adopt_search_sortorder',
			__( 'Adopt Animal Search Sort Order', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	'adopt_search_sortorder',
                $this->adminFunction->keyAndLabel($adoptSearchSortOrder),
                $this->pmpRGAdminInfo['adopt_search_sortorder'],
                'class' => $classSearchSortOrder
			)
		);
		
		//echo 'level_search_details_adopt = ' . $pmpOptionLevelsFilter['level_search_details_adopt'] . '<br>';
		if ( (array_key_exists('level_search_details_adopt', $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_adopt']) && (!empty($this->pmpLicenseKey)) ) {
			//echo 'Enable level_search_details_adopt<br>';
			$classSearchDetails = 'pmp-search-details-adopt';
		} else {
			//echo 'Disable level_search_details_adopt<br>';		
			$classSearchDetails = 'pmp-option-disable';
		}					
     
       	$adoptSearchDetails = $this->adminFunction->Filter_Option_Values('adopt', $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_SEARCH_RESULT'), constant('LABEL_PREFIX_SEARCH_RESULT'));

		add_settings_field(
			'adopt_search_details',
			__( 'Adopt Animal Details on Search', 'pet-match-pro-plugin' ),
			array( $this->adminFunction, 'checkbox_element_callback'),
			'pet-match-pro-filter-options',	            
			'filter_settings_section',
            array('pet-match-pro-filter-options',
            	'adopt_search_details',
                $this->adminFunction->keyAndLabel($adoptSearchDetails),
                $this->pmpRGAdminInfo['adopt_search_details'],
                'class' => $classSearchDetails
			)
		);
		
		//echo 'level_search_details_label_adopt = ' . $pmpOptionLevelsFilter['level_search_details_label_adopt'] . '<br>';

		if ( (array_key_exists('level_search_details_label_adopt', $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_search_details_label_adopt']) && (!empty($this->pmpLicenseKey)) ) {

			//echo 'Enable level_search_details_label_adopt<br>';

			$classSearchLabel = 'pmp-search-labels-adopt';

		} else {

			//echo 'Disable level_search_details_label_adopt<br>';		

			$classSearchLabel = 'pmp-option-disable';

		}							

		

		add_settings_field(

			'animal_details_adopt_search_labels',

			__( 'Show Labels?', 'pet-match-pro-plugin' ),

			array( $this->adminFunction, 'checkbox_element_callback'),

			'pet-match-pro-filter-options',	            

			'filter_settings_section',

        	array('pet-match-pro-filter-options',

            	'animal_details_adopt_search_labels',

                array('Enable' => 'Show Labels on Search Results'),

                $this->pmpRGAdminInfo['animal_details_adopt_search_labels'],

                'class' => $classSearchLabel

			)

		);



		//echo 'level_animal_details_adopt = ' . $pmpOptionLevelsFilter['level_animal_details_adopt'] . '<br>';

		if ( (array_key_exists('level_animal_details_adopt', $pmpOptionLevelsFilter)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsFilter['level_animal_details_adopt']) && (!empty($this->pmpLicenseKey)) ) {

			//echo 'Enable level_animal_details_adopt<br>';

			$classAnimalDetails = 'pmp-animal-details-adopt';

		} else {

			//echo 'Disable level_animal_details_adopt<br>';		

			$classAnimalDetails = 'pmp-option-disable';

		}							

		
       	$adoptAnimalDetails = $this->adminFunction->Filter_Option_Values('adopt', $this->PMPLicenseTypeID, constant('LEVEL_PREFIX_ANIMAL_DETAIL'), constant('LABEL_PREFIX_ANIMAL_DETAIL'));

		add_settings_field(

			'adopt_animal_details',

			__( 'Default Animal Details (Adopt Single)', 'pet-match-pro-plugin' ),

			array( $this->adminFunction, 'checkbox_element_callback'),

			'pet-match-pro-filter-options',	            

			'filter_settings_section',

            array('pet-match-pro-filter-options',

				'adopt_animal_details',

                $this->adminFunction->keyAndLabel($adoptAnimalDetails),

                $this->pmpRGAdminInfo['adopt_animal_details'],

                'class' => $classAnimalDetails

			)

		);

        //submit_button('Save Filters');

		register_setting(

			'pet-match-pro-filter-options',

			'pet-match-pro-filter-options'

		);



		//settings_fields( 'pet-match-pro-filter-options' );

        //do_settings_sections( 'pet-match-pro-filter-options' );

        //submit_button('Save Filters');        

	}



    public function initialize_label_options() {
 
    global $pmpAdminInfo;			/* Admin Hover Help Text */
  
		if(isset($_REQUEST['page']) && $_REQUEST['page']=='pet-match-pro-options') {
			 settings_fields('pet-match-pro-label-options'); 
		}
		do_settings_sections( 'pet-match-pro-label-options' );

        add_settings_section(
            'label_settings_section',
            __('', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'label_options_callback'),
            'pet-match-pro-label-options'
        );       

        /* Get Field Visibility Levels by License Type */
        $levelsFile = 'pmp-option-levels-labels.php';
        $requireFile = $this->partialsAdminDir . $levelsFile;
        require($requireFile); 
        //echo '<pre> ADMIN OPTION LEVEL VALUES '; print_r($pmpOptionLevelsLabels); echo '</pre>';

        //echo 'PMP License Type ID = ' . $this->PMPLicenseTypeID . '<br>';
        //$PMPLicenseTypeID = 2; 

        if ( (array_key_exists('level_label_fields_adopt', $pmpOptionLevelsLabels)) && ($this->PMPLicenseTypeID <= $pmpOptionLevelsLabels['level_label_filters_adopt']) && (!empty($this->pmpLicenseKey)) ) {  
            $labelFieldsAdoptClass = 'pmp-label-fields-adopt';
        } else {
            $labelFieldsAdoptClass = 'pmp-option-disable';
        }

        /* Get Field Label Values */
        $labelsFile = 'pmp-field-values-adopt.php';
        $requireFile = $this->partialsDir . $labelsFile;
        require($requireFile); 
        //echo '<pre>FIELD VALUES '; print_r($pmpFieldValuesAdopt); echo '</pre>';

		// Filters for Adopt
        add_settings_field(
            'label_filter_adopt_title',
            __('Adoption Search Filter Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section'
            );

		/* Get Filter Fields from Values File */
        $filterFieldKeys = [];
       	$keyMatch = constant('LABEL_PREFIX_SEARCH_FILTER');
       	/* Match Filter Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	//echo 'Field Key Match Pattern is ' . $matchPattern . '.<br>';
       	//echo 'Pattern Match is ' . $matchPattern . '.<br>';
       	$fieldValueKeys = array_keys($pmpFieldValuesAdopt);
		//echo '<pre>FILTER VALUE KEYS<br>'; print_r($fieldValueKeys); echo '</pre>';       	
       	$filterFieldKeys = preg_grep($matchPattern, $fieldValueKeys);
		//echo '<pre>FILTER FIELDS<br>'; print_r($filterFieldKeys); echo '</pre>';
		
	   	if (!empty($filterFieldKeys)) {
	    	foreach ($filterFieldKeys as $filterCounter=>$filterFieldKey) {
	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_FILTER'), '', trim($filterFieldKey));
	        	$filterLabelValue = trim($pmpFieldValuesAdopt[$filterFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $filterLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = constant('LABEL_PREFIX_SEARCH_FILTER') . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($filterLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpRGAdminInfo[$labelKey],
                    		'class' => $labelFieldsAdoptClass
                		)
            		);
	            }
			}
		}
				
    //        ADOPT SEARCH
        add_settings_field(
            'label_search_adopt_title',
            __('Adopt Search/Detail Result Labels', 'pet-match-pro-plugin'),
            array($this->adminFunction, 'title_element_callback'),
            'pet-match-pro-label-options',
            'label_settings_section',
            array(
                'class' => 'pmp-top-line'
            )
        );
        
		/* Get Search Result Fields from Values File */
        $searchFieldKeys = [];
       	$keyMatch = constant('LABEL_PREFIX_SEARCH_RESULT');
       	/* Match Search Label Keys */
       	$matchPattern = '/' . preg_quote($keyMatch, '/') . '\w/';
       	$searchValueKeys = array_keys($pmpFieldValuesAdopt);
       	$searchFieldKeys = preg_grep($matchPattern, $searchValueKeys);
		//echo '<pre>SEARCH FIELDS<br>'; print_r($searchFieldKeys); echo '</pre>';
		
	   	if (!empty($searchFieldKeys)) {
	    	foreach ($searchFieldKeys as $searchCounter=>$searchFieldKey) {
	        	$labelOptionKey = str_replace(constant('LABEL_PREFIX_SEARCH_RESULT'), '', trim($searchFieldKey));
	        	$searchLabelValue = trim($pmpFieldValuesAdopt[$searchFieldKey]) . ':';
	        	//echo 'Processing Label Option Key ' . $labelOptionKey . ' with Value ' . $searchLabelValue . '.<br>'; 
	        	if (strlen($labelOptionKey) > 0) {
	        		$labelKey = constant('LABEL_PREFIX_SEARCH_RESULT') . $labelOptionKey;
	        		//echo 'Processing Label Key ' . $labelKey . '.<br>';	        		
        			add_settings_field(
                		$labelKey,
                		__($searchLabelValue, 'pet-match-pro-plugin'),
                		array($this->adminFunction, 'input_element_callback'),
                		'pet-match-pro-label-options',
                		'label_settings_section',
                		array(
                    		'pet-match-pro-label-options',
                    		$labelKey,
                    		$this->pmpRGAdminInfo[$labelKey],
                    		'class' => $labelFieldsAdoptClass
                		)
            		);
	            }
			}
		}
        
        register_setting(
            'pet-match-pro-label-options',
            'pet-match-pro-label-options'
        );
    }
}