<?php

    // Pethealth PetPoint Technical Support script for testing webservices

    // Date Developed: 2020-05-08

    // Last Date Updated: 2021-01-31

    // Updated By: Jeffrey Gorton (AWSoftwarePartners@pethealthinc.com)



	// The base Web Services URL for all Petango Web Services calls

	$urlWSBase      = "https://ws.petango.com/webservices/wsadoption.asmx/";

    

    // Reference to the current script to develop a complete url

    $selfURL        = 'wstest.php'; 

    

    // Set a default value for variables used in the script

    $urlWSComplete  = ""; // The URL to call the Webservices methods 

    $daysAgo14      = date('m/d/Y',strtotime("-14 days")); //Defines the date 14 days in the past from today

    $daysAgo13      = date('m/d/Y',strtotime("-13 days")); //Defines the date 13 days in the past from today

    $daysAgo12      = date('m/d/Y',strtotime("-12 days")); //Defines the date 12 days in the past from today

    $daysAgo11      = date('m/d/Y',strtotime("-11 days")); //Defines the date 11 days in the past from today

    $daysAgo10      = date('m/d/Y',strtotime("-10 days")); //Defines the date 10 days in the past from today

    $daysAgo9       = date('m/d/Y',strtotime("-9 days")); //Defines the date 9 days in the past from today

    $daysAgo8       = date('m/d/Y',strtotime("-8 days")); //Defines the date 8 days in the past from today

    $daysAgo7       = date('m/d/Y',strtotime("-7 days")); //Defines the date 7 days in the past from today

    $daysAgo6       = date('m/d/Y',strtotime("-6 days")); //Defines the date 6 days in the past from today

    $daysAgo5       = date('m/d/Y',strtotime("-5 days")); //Defines the date 5 days in the past from today

    $daysAgo4       = date('m/d/Y',strtotime("-4 days")); //Defines the date 4 days in the past from today

    $daysAgo3       = date('m/d/Y',strtotime("-3 days")); //Defines the date 3 days in the past from today

    $daysAgo2       = date('m/d/Y',strtotime("-2 days")); //Defines the date 2 days in the past from today

    $daysAgo1       = date('m/d/Y',strtotime("-1 days")); //Defines the date 1 day in the past from today

    

    // Set the error variable to No by default stating there is no error

    $error = "No";



    // Sets the HTML structure, head, and body 

echo <<<HTML

<html>

<head>

    <title>PetPoint Web Services Sample Script</title>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>

<body style='font-family: Roboto, sans-serif'>

HTML;

        

    /* *********************************************************************** */

    /* *** Script to collect, validate, and store the Web Services AuthKey *** */

    

    // Capture the AuthKey when passed through the form

	if ($_POST['authkey']) {

        

        // Removes any spaces

        $authKeyEntered = trim(sanitize_text_field($_POST['authkey']));

        

        if (strlen($authKeyEntered) == 50) {

        

            // Set the AuthKey variable that will be used throughout the script

            $urlWSAuthKey = sanitize_text_field($_POST['authkey']);

            

        }

        else {

            

            // The length of the AuthKey is wrong

            $error = "Yes";

            

            // Displays the AuthKey form and then terminates script

            displayAuthKeyForm($error);

            

        }

            

    }



    // Capture the AuthKey when passed through the URL

    else if ($_GET['authkey']) {

    

        // Removes any spaces

        $authKeyEntered = trim(sanitize_text_field( $_GET['authkey'] ) );

    

        if (strlen($authKeyEntered) == 50) {

        

            // Set the AuthKey variable that will be used throughout the script

            $urlWSAuthKey = sanitize_text_field( $_GET['authkey'] );

            

        }

        else {

            

            // The length of the AuthKey is wrong

            $error = "Yes";

            

            // Displays the AuthKey form and then terminates script

            displayAuthKeyForm($error);

            

        }

        

    }

    // Display the form to capture the AuthKey



    else {

            

        // Displays the AuthKey form and then terminates script

        displayAuthKeyForm($error);



    }



    /* *********************************************************************** */

    /* *********************************************************************** */



    // Develop the complete URL to call the webservices AdoptableSearch method

	if ($_GET['method'] == 'AdoptableSearch') {

		

        $urlWSComplete  = createAdoptableSearch($urlWSBase,$urlWSAuthKey);



	}



    // Develop the complete URL to call the webservices AdoptableDetails method

	else if ($_GET['method'] == 'AdoptableDetails') {



		$animalID		= sanitize_text_field( $_GET['animalID'] );  // The animal ID to pull details



        $urlWSComplete  = createAdoptableDetails($urlWSBase,$urlWSAuthKey,$animalID);



    }



    // Develop the complete URL to call the webservices AdoptableSearchWithStage method

    // Same as AdoptableSearch, except that Feature is required

    else if ($_GET['method'] == 'AdoptableSearchWithStage') {

        

        $urlWSComplete  = createAdoptableSearchWithStage($urlWSBase,$urlWSAuthKey);



    }



    // Develop the complete URL to call the webservices AdoptionDetails method

    else if ($_GET['method'] == 'AdoptionDetails') {



		$animalID		= sanitize_text_field($_GET['animalID']);  // The animal ID to pull details



        $urlWSComplete  = createAdoptionDetails($urlWSBase,$urlWSAuthKey,$animalID);

        

    }



    // Develop the complete URL to call the webservices AdoptionList method

    else if ($_GET['method'] == 'AdoptionList') {

        

        $adoptionDate   = sanitize_text_field( $_GET['adoptionDate'] );    // Format mm/dd/yyyy

        $siteID			= "";			            //Site ID



        $urlWSComplete  = createAdoptionList($urlWSBase,$urlWSAuthKey,$adoptionDate,$siteID);

        

    }



    // Develop the complete URL to call the webservices foundDetails method

    else if ($_GET['method'] == 'foundDetails') {

        

        $animalID       = sanitize_text_field( $_GET['animalID'] );  // The animal ID to pull details

        

        $urlWSComplete  = createFoundDetails($urlWSBase,$urlWSAuthKey,$animalID);

        

    }



    // Develop the complete URL to call the webservices foundSearch method

    else if ($_GET['method'] == 'foundSearch') {

        

        $urlWSComplete  = createFoundSearch($urlWSBase,$urlWSAuthKey);



    }



    // Develop the complete URL to call the webservices lostDetails method

    else if ($_GET['method'] == 'lostDetails') {

        

        $animalID       = sanitize_text_field( $_GET['animalID'] );  // The animal ID to pull details

        

        $urlWSComplete  = createLostDetails($urlWSBase,$urlWSAuthKey,$animalID);

        

    }



    // Develop the complete URL to call the webservices lostSearch method

    else if ($_GET['method'] == 'lostSearch') {

        

        $urlWSComplete  = createLostSearch($urlWSBase,$urlWSAuthKey);

        

    }



    // This is the main page of wstest.php that provides links for the possible webservices 

    else{

        

echo <<<HTML

        

    <h2 align='center'>PetPoint Web Services Test</h2>

    <p align='center'><strong>Auth Key:</strong> $urlWSAuthKey</p>



    <div align='center'>

        <a href='$selfURL?method=AdoptableSearch&authkey=$urlWSAuthKey' target='_blank'>AdoptableSearch</a><br><br>

        <a href='$selfURL?method=AdoptableSearchWithStage&authkey=$urlWSAuthKey' target='_blank'>AdoptableSearchWithStage</a><br><br>

        <strong>AdoptionList</strong><br>

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo14' target='_blank'>$daysAgo14</a>&ensp;

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo13' target='_blank'>$daysAgo13</a>&ensp;

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo12' target='_blank'>$daysAgo12</a>&ensp;

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo11' target='_blank'>$daysAgo11</a><br>

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo10' target='_blank'>$daysAgo10</a>&ensp;

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo9' target='_blank'>$daysAgo9</a>&ensp;

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo8' target='_blank'>$daysAgo8</a><br><br>

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo7' target='_blank'>$daysAgo7</a>&ensp;

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo6' target='_blank'>$daysAgo6</a>&ensp;

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo5' target='_blank'>$daysAgo5</a>&ensp;

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo4' target='_blank'>$daysAgo4</a><br>

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo3' target='_blank'>$daysAgo3</a>&ensp;

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo2' target='_blank'>$daysAgo2</a>&ensp;

        <a href='$selfURL?method=AdoptionList&authkey=$urlWSAuthKey&adoptionDate=$daysAgo1' target='_blank'>$daysAgo1</a><br><br>

        <a href='$selfURL?method=foundSearch&authkey=$urlWSAuthKey' target='_blank'>foundSearch</a><br><br>

        <a href='$selfURL?method=lostSearch&authkey=$urlWSAuthKey' target='_blank'>lostSearch</a><br><br>

    </div>

        

HTML;



    exit;

        

    }



	//Output URL String to the page to show the call string that was created

	echo "URL Call String is:<br>\n$urlWSComplete<br><br>";



    /* ************************************************************************* */

    /* *** Script to execute and collect the results of the webservices call *** */



	// HTTP GET command to obtain the data

	$outputWS = wp_remote_get($urlWSComplete);

	

	//If outputWS is not a boolean FALSE value

	if ($outputWS !== false){



		// Convert the output to human readable XML

		$xmlWS = simplexml_load_string($outputWS);

        

        // Convert the output to a PHP Array

        $xmlWSArray = json_decode(json_encode((array)simplexml_load_string($outputWS)),1);





		// If the output is not XML, display descriptive error messages

		if ($xmlWS === false) {

			

			echo "Failed loading XML: ";

			

			foreach(libxml_get_errors() as $error) {

			

				echo "<br>", $error->message;

		

			}



		} 



	// If Output WS has produced a boolean FALSE

	}

    else {

	

		echo "The following URL resulted in a FALSE output:<br>$urlWSComplete";

		

	}





    /* Unremark if you want to see XML structure of output

    // Preserve <pre> the output to ensure proper display

    echo "<pre>\n";

    

    // Output the XML

    print_r($xmlWS);

    

    echo "</pre>\n";

    

    var_dump($xmlWSArray);

    */



    /* ************************************************************************* */

    /* ************************************************************************* */





    // Display the results of the webservices call for the AdoptableSearch method

	if ($_GET['method'] == 'AdoptableSearch') {



        outputAdoptableSearch($selfURL,$urlWSAuthKey,$xmlWS);

        

    }



    // Display the results of the webservices call for the AdoptableSearchWithStage method

	else if ($_GET['method'] == 'AdoptableSearchWithStage') {



        outputAdoptableSearchWithStage($selfURL,$urlWSAuthKey,$xmlWS);

        

    }



    // Display the results of the webservices call for the AdoptableDetails method

	else if ($_GET['method'] == 'AdoptableDetails') {



        outputAdoptableDetails($xmlWSArray);



    }



    // Display the results of the webservices call for the AdoptionList method

	else if ($_GET['method'] == 'AdoptionList') {



        outputAdoptionList($selfURL,$urlWSAuthKey,$xmlWS);



    }



    // Display the results of the webservices call for the AdoptionDetails method

	else if ($_GET['method'] == 'AdoptionDetails') {



        outputAdoptionDetails($xmlWSArray);



    }



    // Display the results of the webservices call for the foundSearch method

	else if ($_GET['method'] == 'foundSearch') {



        outputFoundSearch($selfURL,$urlWSAuthKey,$xmlWS);

        

    }

    

    // Display the results of the webservices call for the foundDetails method

	else if ($_GET['method'] == 'foundDetails') {



        outputFoundDetails($xmlWSArray);



    }



    // Display the results of the webservices call for the lostSearch method

	else if ($_GET['method'] == 'lostSearch') {



        outputLostSearch($selfURL,$urlWSAuthKey,$xmlWS);

        

    }



    // Display the results of the webservices call for the lostDetails method

	else if ($_GET['method'] == 'lostDetails') {



        outputLostDetails($xmlWSArray);



    }

    

	// terminate the script

	exit();

		



/* **************** */

/* Script Functions */



function displayAuthKeyForm($error) {



    $errorText = "";



    if ($error == "Yes") {

        

        $errorText = "<font color='red'>Not a Valid AuthKey, please double check and resubmit</font><br>";

        

    }



echo <<<HTML

<h2 align='center'>Please enter your shelters Web Services Authorization Key</h2>

<form name='wstest' action="$selfURL" method='post' enctype='multipart/form-data'>

    <div align='center'>

        $errorText

        <input type='text' name='authkey' size='70' max='50'><br><br>

        <input type="image" name="Submit" src="../SupportFiles/Submit.png" alt="Submit">

    <div>

</form>

HTML;



    exit();

    

    

}

        

function createAdoptableSearch($urlWSBaseIN,$urlWSAuthKeyIN) {

    

    $urlWSCompleteOUT = "";



    // Input details can be found at

    // https://pethealth.force.com/community/s/article/Webservices-information-guide#adoptable-search

    

    $speciesID 		    = "0"; 			//All Species

    $sex 			    = "A"; 			//All animal genders

    $ageGroup 		    = "All";		//All age groups

    $location		    = "";			//Location string

    $site			    = "";			//Site string

    $stageID		    = "";			//Stage ID string

    $onHold			    = "A";			//Animals on HOLD or Not on HOLD

    $orderBy		    = "Name";		//Sort by Animal Name

    $primaryBreed	    = "All";		//All Breeds (Primary)

    $secondaryBreed     = "All";		//All Breeds (Secondary)

    $specialNeeds	    = "A";			//Special Needs

    $noDogs			    = "A";			//Can live with dogs

    $noCats			    = "A";			//Can live with cats

    $noKids			    = "A";			//Can live with kids

    

    $urlWSCompleteOUT   = $urlWSBaseIN . "AdoptableSearch?authKey=$urlWSAuthKeyIN"; 	//Initial URL build

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&speciesID=$speciesID"; 			        //Add Species ID to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&sex=$sex"; 						        //Add Gender to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&ageGroup=$ageGroup";				        //Add Age Group to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&location=$location";				        //Add Location to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&site=$site";						        //Add Site to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&stageID=$stageID";					        //Add Stage ID to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&onHold=$onHold";					        //Add On Hold Status to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&orderBy=$orderBy";					        //Add Output Order to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&primaryBreed=$primaryBreed";		        //Add Primary Breed to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&secondaryBreed=$secondaryBreed";	        //Add Secondary Breed to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&specialNeeds=$specialNeeds";		        //Add Special Needs to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&noDogs=$noDogs";					        //Add No Dogs Value to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&noCats=$noCats";					        //Add No Cats Value to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&noKids=$noKids";					        //Add No Kids Value to URL



    return $urlWSCompleteOUT;



}



function createAdoptableDetails($urlWSBaseIN,$urlWSAuthKeyIN,$animalIDIN) {



	$urlWSCompleteOUT = "";

    

    $urlWSCompleteOUT = $urlWSBaseIN . "AdoptableDetails?authKey=$urlWSAuthKeyIN"; 	    //Initial URL build

	$urlWSCompleteOUT = "$urlWSCompleteOUT&animalID=$animalIDIN"; 			            //AnimalID to display



    return $urlWSCompleteOUT;



}



function createAdoptableSearchWithStage($urlWSBaseIN,$urlWSAuthKeyIN) {



    $urlWSCompleteOUT   = "";

    

    $speciesID 		    = "0"; 			//All Species

    $sex 			    = "A"; 			//All animal genders

    $ageGroup 		    = "All";		//All age groups

    $location		    = "";			//Location string

    $site			    = "";			//Site string

    $stageID		    = "";			//Stage ID string

    $onHold			    = "A";			//Animals on HOLD or Not on HOLD

    $orderBy		    = "Name";		//Sort by Animal Name

    $primaryBreed	    = "All";		//All Breeds (Primary)

    $secondaryBreed     = "All";		//All Breeds (Secondary)

    $specialNeeds	    = "A";			//Special Needs

    $noDogs			    = "A";			//Can live with dogs

    $noCats			    = "A";			//Can live with cats

    $noKids			    = "A";			//Can live with kids

    $featured           = "";           //Featured Animal        

    

    $urlWSCompleteOUT   = $urlWSBaseIN . "AdoptableSearchWithStage?authKey=$urlWSAuthKeyIN"; //Initial URL build

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&speciesID=$speciesID"; 			                //Add Species ID to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&sex=$sex"; 						                //Add Gender to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&ageGroup=$ageGroup";				                //Add Age Group to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&location=$location";				                //Add Location to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&site=$site";						                //Add Site to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&stageID=$stageID";					            //Add Stage ID to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&onHold=$onHold";					                //Add On Hold Status to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&orderBy=$orderBy";					            //Add Output Order to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&primaryBreed=$primaryBreed";		                //Add Primary Breed to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&secondaryBreed=$secondaryBreed";	                //Add Secondary Breed to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&specialNeeds=$specialNeeds";		                //Add Special Needs to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&noDogs=$noDogs";					                //Add No Dogs Value to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&noCats=$noCats";					                //Add No Cats Value to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&noKids=$noKids";					                //Add No Kids Value to URL

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&featured=$featured";				                //Add Feature Value to URL

    

    return $urlWSCompleteOUT;



}



function createAdoptionDetails($urlWSBaseIN,$urlWSAuthKeyIN,$animalIDIN) {



	$urlWSCompleteOUT = "";

    

    $urlWSCompleteOUT = $urlWSBaseIN . "AdoptionDetails?authKey=$urlWSAuthKeyIN"; 	    //Initial URL build

	$urlWSCompleteOUT = "$urlWSCompleteOUT&animalID=$animalIDIN"; 			            //AnimalID to display



    return $urlWSCompleteOUT;

        

}



function createAdoptionList($urlWSBaseIN,$urlWSAuthKeyIN,$adoptionDateIN,$siteIDIN) {

        

	$urlWSCompleteOUT   = "";

    

    $urlWSCompleteOUT   = $urlWSBaseIN . "AdoptionList?authKey=$urlWSAuthKeyIN"; //Initial URL build

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&adoptionDate=$adoptionDateIN" ;     //Add Adoption Date

    $urlWSCompleteOUT   = "$urlWSCompleteOUT&siteid=$siteIDIN" ;                   //Add Blank Site ID (all sites)



    return $urlWSCompleteOUT;

        

}



function createFoundDetails($urlWSBaseIN,$urlWSAuthKeyIN,$animalIDIN) {



	$urlWSCompleteOUT = "";

    

    $urlWSCompleteOUT = $urlWSBaseIN . "foundDetails?authKey=$urlWSAuthKeyIN"; 	//Initial URL build

	$urlWSCompleteOUT = "$urlWSCompleteOUT&animalID=$animalIDIN"; 			    //AnimalID to display



    return $urlWSCompleteOUT;

        

}



function createFoundSearch($urlWSBaseIN,$urlWSAuthKeyIN) {



	$urlWSCompleteOUT   = "";

    

    $speciesID          = "0";  // The animal ID to pull details

    $sex                = "A";  // Gender

    $ageGroup           = "All";// Age Group

    $orderBy            = "ID"; // Order By

    $searchOption       = "0";  // Search Option



        

    $urlWSCompleteOUT  = $urlWSBaseIN . "foundSearch?AuthKey=$urlWSAuthKeyIN";  //Initial URL build

    $urlWSCompleteOUT  = "$urlWSCompleteOUT&speciesID=$speciesID" ;             //Add Species ID

    $urlWSCompleteOUT  = "$urlWSCompleteOUT&sex=$sex" ;                         //Add Gender

    $urlWSCompleteOUT  = "$urlWSCompleteOUT&ageGroup=$ageGroup" ;               //Add Age Group

    $urlWSCompleteOUT  = "$urlWSCompleteOUT&orderBy=$orderBy" ;                 //Add Order By

    $urlWSCompleteOUT  = "$urlWSCompleteOUT&searchOption=$searchOption" ;       //Add Search Option        

    

    return $urlWSCompleteOUT;



        

}



function createLostDetails($urlWSBaseIN,$urlWSAuthKeyIN,$animalIDIN) {



	$urlWSCompleteOUT = "";

    

    $urlWSCompleteOUT = $urlWSBaseIN . "lostDetails?authKey=$urlWSAuthKeyIN"; 	//Initial URL build

	$urlWSCompleteOUT = "$urlWSCompleteOUT&animalID=$animalIDIN"; 			    //AnimalID to display



    return $urlWSCompleteOUT;

        

}



function createLostSearch($urlWSBaseIN,$urlWSAuthKeyIN) {



	$urlWSCompleteOUT   = "";

    

    $speciesID          = "0";  // The animal ID to pull details

    $sex                = "A";  // Gender

    $ageGroup           = "All";// Age Group

    $orderBy            = "ID"; // Order By

    $searchOption       = "0";  // Search Option

    $speciesID          = "0";  // The animal ID to pull details

    $sex                = "A";  // Gender

    $ageGroup           = "All";// Age Group

    $orderBy            = "ID"; // Order By

    $searchOption       = "0";  // Search Option

        

    $urlWSCompleteOUT  = $urlWSBaseIN . "lostSearch?AuthKey=$urlWSAuthKeyIN";   //Initial URL build

    $urlWSCompleteOUT  = "$urlWSCompleteOUT&speciesID=$speciesID" ;             //Add Species ID

    $urlWSCompleteOUT  = "$urlWSCompleteOUT&sex=$sex" ;                         //Add Gender

    $urlWSCompleteOUT  = "$urlWSCompleteOUT&ageGroup=$ageGroup" ;               //Add Age Group

    $urlWSCompleteOUT  = "$urlWSCompleteOUT&orderBy=$orderBy" ;                 //Add Order By

    $urlWSCompleteOUT  = "$urlWSCompleteOUT&searchOption=$searchOption" ;       //Add Search Option        



    return $urlWSCompleteOUT;



}



function outputAdoptableSearch($selfURLIN,$urlWSAuthKeyIN,$xmlWSIN) {



    // Count the results of the XML array

    $xmlArrayCount = count($xmlWSIN);

    

    // Sets the counter to zero to use to loop through array count

    $counter = 0;



    echo "<h2 align='center'>AdoptableSearch Output</h2>\n\n";



    echo "\n\n<table align='center'><tr>\n";

    

    // If the counter value is less than the xml Array Count

    while ($counter < $xmlArrayCount-1) {

        

        foreach ($xmlWSIN->XmlNode->$counter->adoptableSearch as $output) {



            // Set default value of non-mandatory fields equal to "Not Defined"

            $xmlSecondaryBreed = $xmlSpecialNeeds = $xmlNoDogs = $xmlNoCats = $xmlNoKids = "Not Defined";

            

            // Mandatory Fields that will always have a value

            $xmlAnimalID        = $output->ID;

            $xmlName            = $output->Name;

            $xmlPhoto           = $output->Photo;

            $xmlSpecies         = $output->Species;

            $xmlSex             = $output->Sex;

            $xmlPrimaryBreed    = $output->PrimaryBreed;

            $xmlAgeMonths       = $output->Age;

            $xmlLocation        = $output->Location;

            $xmlOnHold          = $output->OnHold;

            $xmlStage           = $output->Stage;

            $xmlAnimalType      = $output->AnimalType;

            $xmlAgeGroup        = $output->AgeGroup;

            $xmlAltered         = $output->SN;

                

            // Check to see if there is a value in the non-mandatory fields

            // If there is a value, replace the variable default value "Not Defined" with the actual value of the output

            if (is_string($output->SecondaryBreed))     { 

                $xmlSecondaryBreed = $output->SecondaryBreed; 

            }



            if (is_string($output->SpecialNeeds))       { 

                $xmlSpecialNeeds = $output->SpecialNeeds; 

            }

            

            if (is_string($output->NoDogs))             { 

                $xmlNoDogs = $output->NoDogs; 

            }

            

            if (is_string($output->NoCats))             {

                $xmlNoCats = $output->NoCats; 

            }

            

            if (is_string($output->NoKids))             { 

                $xmlNoKids = $output->NoKids; 

            }



            $xmlAnimalDetailsLink = $selfURLIN . '?method=AdoptableDetails&animalID='. $xmlAnimalID . '&authkey=' . $urlWSAuthKeyIN;

                            

echo <<<HTML

<td width='25%'>

    <div align='center'>

        <a href='$xmlAnimalDetailsLink'>

            <img src='$xmlPhoto'>

        </a>

        <br>

        <br>

        <strong>

            $xmlName

        </strong>

        <br>

        <br>

    </div>



<!--

All Output Values

xmlAnimalID         = $xmlAnimalID<br>\n

xmlName             = $xmlName<br>\n

xmlSpecies          = $xmlSpecies<br>\n

xmlSex              = $xmlSex<br>\n

xmlPrimaryBreed     = $xmlPrimaryBreed<br>\n

xmlSecondaryBreed   = $xmlSecondaryBreed<br>\n

xmlAgeMonths        = $xmlAgeMonths<br>\n

xmlPhoto            = $xmlPhoto<br>\n

xmlLocation         = $xmlLocation<br>\n

xmlOnHold           = $xmlOnHold<br>\n

xmlStage            = $xmlStage<br>\n

xmlAnimalType       = $xmlAnimalType<br>\n

xmlAgeGroup         = $xmlAgeGroup<br>\n

xmlAltered          = $xmlAltered<br>\n

xmlSpecialNeeds     = $xmlSpecialNeeds<br>\n

xmlNoDogs           = $xmlNoDogs<br>\n

xmlNoCats           = $xmlNoCats<br>\n

xmlNoKids           = $xmlNoKids<br>\n

-->

</td>

HTML;



            // End the HTML row at every fourth animal

            if (($counter+1) % 4 == 0) {

                echo "</tr><tr>";

            }

            

        }



        // Increment Counter

        $counter++;

        

    }

    

    echo "</tr></table>";

    

}



function outputAdoptableSearchWithStage($selfURLIN,$urlWSAuthKeyIN,$xmlWSIN) {



    // Count the results of the XML array

    $xmlArrayCount = count($xmlWSIN);

    

    // Sets the counter to zero to use to loop through array count

    $counter = 0;



    echo "<h2 align='center'>AdoptableSearchWithStage Output</h2>\n\n";



    echo "\n\n<table align='center'><tr>\n";

        

    // If the counter value is less than the xml Array Count

    while ($counter < $xmlArrayCount-1) {

            

        foreach ($xmlWSIN->XmlNode->$counter->adoptableSearch as $output) {



            // Set default value of non-mandatory fields equal to "Not Defined"

            $xmlSecondaryBreed = $xmlSpecialNeeds = $xmlNoDogs = $xmlNoCats = $xmlNoKids = "Not Defined";

            

            // Mandatory Fields that will always have a value

            $xmlAnimalID        = $output->ID;

            $xmlName            = $output->Name;

            $xmlPhoto           = $output->Photo;

            $xmlSpecies         = $output->Species;

            $xmlSex             = $output->Sex;

            $xmlPrimaryBreed    = $output->PrimaryBreed;

            $xmlAgeMonths       = $output->Age;

            $xmlLocation        = $output->Location;

            $xmlOnHold          = $output->OnHold;

            $xmlStage           = $output->Stage;

            $xmlAnimalType      = $output->AnimalType;

            $xmlAgeGroup        = $output->AgeGroup;

            $xmlAltered         = $output->SN;

                

            // Check to see if there is a value in the non-mandatory fields

            // If there is a value, replace the variable default value "Not Defined" with the actual value of the output

            if (is_string($output->SecondaryBreed))     { 

                $xmlSecondaryBreed = $output->SecondaryBreed; 

            }



            if (is_string($output->SpecialNeeds))       { 

                $xmlSpecialNeeds = $output->SpecialNeeds; 

            }

            

            if (is_string($output->NoDogs))             { 

                $xmlNoDogs = $output->NoDogs; 

            }

            

            if (is_string($output->NoCats))             {

                $xmlNoCats = $output->NoCats; 

            }

            

            if (is_string($output->NoKids))             { 

                $xmlNoKids = $output->NoKids; 

            }



            $xmlAnimalDetailsLink = $selfURLIN . '?method=AdoptableDetails&animalID='. $xmlAnimalID . '&authkey=' . $urlWSAuthKeyIN;

                            

echo <<<HTML

<td width='25%'>

    <div align='center'>

        <a href='$xmlAnimalDetailsLink'>

            <img src='$xmlPhoto'>

        </a>

        <br>

        <br>

        <strong>

            $xmlName

        </strong>

        <br>

        <br>

    </div>



<!--

All Output Values

xmlAnimalID         = $xmlAnimalID<br>\n

xmlName             = $xmlName<br>\n

xmlSpecies          = $xmlSpecies<br>\n

xmlSex              = $xmlSex<br>\n

xmlPrimaryBreed     = $xmlPrimaryBreed<br>\n

xmlSecondaryBreed   = $xmlSecondaryBreed<br>\n

xmlAgeMonths        = $xmlAgeMonths<br>\n

xmlPhoto            = $xmlPhoto<br>\n

xmlLocation         = $xmlLocation<br>\n

xmlOnHold           = $xmlOnHold<br>\n

xmlStage            = $xmlStage<br>\n

xmlAnimalType       = $xmlAnimalType<br>\n

xmlAgeGroup         = $xmlAgeGroup<br>\n

xmlAltered          = $xmlAltered<br>\n

xmlSpecialNeeds     = $xmlSpecialNeeds<br>\n

xmlNoDogs           = $xmlNoDogs<br>\n

xmlNoCats           = $xmlNoCats<br>\n

xmlNoKids           = $xmlNoKids<br>\n

-->

</td>

HTML;



            // End the HTML row at every fourth animal

            if (($counter+1) % 4 == 0) {

                echo "</tr><tr>";

            }

            

        }



        // Increment Counter

        $counter++;

        

    }

    

    echo "</tr></table>";

    

}



function outputAdoptableDetails($xmlWSArrayIN) {

        

    echo "<h2 align='center'>AdoptableDetails Output</h2>\n\n";



    echo "\n\n<table align='center'><tr>\n";



    // Set default value of non-mandatory fields equal to "Not Defined"

    $xmlSecondaryBreed = $xmlSecondaryColor = $xmlDescription = $xmlPhoto2 = $xmlPhoto3 = $xmlSpecialNeeds = "Not Defined";

    $xmlNoDogs = $xmlNoCats = $xmlNoKids = $xmlBehaviorResult = $xmlMemoList = $xmlTimeInFormerHome = "Not Defined";

    $xmlReasonForSurrender = $xmlPrevEnvironment = $xmlLivedWithChildren = $xmlLivedWithAnimals = "Not Defined";

    $xmlLivedWithAnimalTypes = $xmlBodyWeight = $xmlAnimalRefNumber = $xmlVideoID = $xmlBehaviorTestList = "Not Defined";

    $xmlWildlifeIntakeInjury = $xmlWildlifeIntakeCause = $xmlBuddyID = $xmlChipNumber = $xmlColorPattern = "Not Defined";

        

    // Mandatory Fields that will always have a value

    $xmlCompanyID               = $xmlWSArrayIN['CompanyID'];

    $xmlAnimalID                = $xmlWSArrayIN['ID'];

    $xmlAnimalName              = $xmlWSArrayIN['AnimalName'];

    $xmlSpecies                 = $xmlWSArrayIN['Species'];

    $xmlSex                     = $xmlWSArrayIN['Sex'];

    $xmlAltered                 = $xmlWSArrayIN['Altered'];

    $xmlPrimaryBreed            = $xmlWSArrayIN['PrimaryBreed'];

    $xmlPrimaryColor            = $xmlWSArrayIN['PrimaryColor'];

    $xmlAge                     = $xmlWSArrayIN['Age'];

    $xmlSize                    = $xmlWSArrayIN['Size'];

    $xmlHousetrained            = $xmlWSArrayIN['Housetrained']; 

    $xmlDeclawed                = $xmlWSArrayIN['Declawed'];

    $xmlPrice                   = $xmlWSArrayIN['Price'];

    $xmlLastIntakeDate          = $xmlWSArrayIN['LastIntakeDate'];

    $xmlLocation                = $xmlWSArrayIN['Location'];

    $xmlPhoto1                  = $xmlWSArrayIN['Photo1'];

    $xmlOnHold                  = $xmlWSArrayIN['OnHold'];

    $xmlSite                    = $xmlWSArrayIN['Site'];

    $xmlDateOfBirth             = $xmlWSArrayIN['DateOfBirth'];

    $xmlStage                   = $xmlWSArrayIN['Stage'];

    $xmlAnimalType              = $xmlWSArrayIN['AnimalType'];

    $xmlAgeGroup                = $xmlWSArrayIN['AgeGroup'];

    $xmlFeatured                = $xmlWSArrayIN['Featured'];

    $xmlSublocation             = $xmlWSArrayIN['Sublocation'];

    $xmlAdoptionApplicationUrl  = $xmlWSArrayIN['AdoptionApplicationUrl'];

    $xmlBannerURL               = $xmlWSArrayIN['BannerURL'];



    // Check to see if there is a value in the non-mandatory fields

    // If there is a value, replace the variable default value "Not Defined" with the actual value of the output

    if (is_string($xmlWSArrayIN['SecondaryBreed']))           { 

        $xmlSecondaryBreed = $xmlWSArrayIN['SecondaryBreed']; 

    }



    if (is_string($xmlWSArrayIN['Dsc']))                      { 

        $xmlDescription = $xmlWSArrayIN['Dsc']; 

    }

    

    if (is_string($xmlWSArrayIN['Photo2']))                   { 

        $xmlPhoto2 = $xmlWSArrayIN['Photo2']; 

    }



    if (is_string($xmlWSArrayIN['Photo3']))                   { 

        $xmlPhoto3 = $xmlWSArrayIN['Photo3']; 

    }



    if (is_string($xmlWSArrayIN['SpecialNeeds']))             { 

        $xmlSpecialNeeds = $xmlWSArrayIN['SpecialNeeds']; 

    }



    if (is_string($xmlWSArrayIN['NoDogs']))                   { 

        $xmlNoDogs = $xmlWSArrayIN['NoDogs']; 

    }



    if (is_string($xmlWSArrayIN['NoCats']))                   { 

        $xmlNoCats = $xmlWSArrayIN['NoCats']; 

    }



    if (is_string($xmlWSArrayIN['NoKids']))                   { 

        $xmlNoKids = $xmlWSArrayIN['NoKids']; 

    }



    if (is_string($xmlWSArrayIN['BehaviorResult']))           { 

        $xmlBehaviorResult = $xmlWSArrayIN['BehaviorResult']; 

    } 



    if (is_string($xmlWSArrayIN['MemoList']))                 { 

        $xmlMemoList = $xmlWSArrayIN['MemoList']; 

    }

    

    if (is_string($xmlWSArrayIN['TimeInFormerHome']))         { 

        $xmlTimeInFormerHome = $xmlWSArrayIN['TimeInFormerHome']; 

    }

    

    if (is_string($xmlWSArrayIN['ReasonForSurrender']))       { 

        $xmlReasonForSurrender = $xmlWSArrayIN['ReasonForSurrender']; 

    }

    

    if (is_string($xmlWSArrayIN['PrevEnvironment']))          { 

        $xmlPrevEnvironment = $xmlWSArrayIN['PrevEnvironment']; 

    }

    

    if (is_string($xmlWSArrayIN['LivedWithChildren']))        { 

        $xmlLivedWithChildren = $xmlWSArrayIN['LivedWithChildren']; 

    }

    

    if (is_string($xmlWSArrayIN['LivedWithAnimals']))         { 

        $xmlLivedWithAnimals = $xmlWSArrayIN['LivedWithAnimals']; 

    }

    

    if (is_string($xmlWSArrayIN['LivedWithAnimalTypes']))     { 

        $xmlLivedWithAnimalTypes = $xmlWSArrayIN['LivedWithAnimalTypes']; 

    }

    

    if (is_string($xmlWSArrayIN['BodyWeight']))               { 

        $xmlBodyWeight = $xmlWSArrayIN['BodyWeight']; 

    }

    

    if (is_string($xmlWSArrayIN['ARN']))                      { 

        $xmlAnimalRefNumber = $xmlWSArrayIN['ARN']; 

    }

    

    if (is_string($xmlWSArrayIN['VideoID']))                  { 

        $xmlVideoID = $xmlWSArrayIN['VideoID']; 

    }

    

    if (is_string($xmlWSArrayIN['BehaviorTestList']))         { 

        $xmlBehaviorTestList = $xmlWSArrayIN['BehaviorTestList']; 

    }

    

    if (is_string($xmlWSArrayIN['WildlifeIntakeInjury']))     { 

        $xmlWildlifeIntakeInjury = $xmlWSArrayIN['WildlifeIntakeInjury']; 

    }

    

    if (is_string($xmlWSArrayIN['WildlifeIntakeCause']))      { 

        $xmlWildlifeIntakeCause = $xmlWSArrayIN['WildlifeIntakeCause']; 

    }

    

    if (is_string($xmlWSArrayIN['BuddyID']))                  { 

        $xmlBuddyID = $xmlWSArrayIN['BuddyID']; 

    }

    

    if (is_string($xmlWSArrayIN['ChipNumber']))               { 

        $xmlChipNumber = $xmlWSArrayIN['ChipNumber']; 

    }

    

    if (is_string($xmlWSArrayIN['ColorPattern']))             { 

        $xmlColorPattern = $xmlWSArrayIN['ColorPattern']; 

    }



    // Create Single Breed Variable for display

    $breed = "$xmlPrimaryBreed/$xmlSecondaryBreed";

    if ($xmlSecondaryBreed == "Not Defined") {

        

        $breed = $xmlPrimaryBreed;

        

    }

    

    // Create link to adoption application

    // Final URL call structure for application is https://www.petango.com/Adoption-Application?shelterId=[CompanyID]&animalId=[AnimalID]

    $applicationLink = "https://www.petango.com/Adoption-Application?shelterId=$xmlCompanyID&animalId=$xmlAnimalID";





echo <<<HTML

<td>

    <div align='center'>

        <img src='$xmlPhoto1'>

        <br>

        <br>

        <strong>

            $xmlAnimalName

        </strong>

        <br>

        $xmlAnimalID

        <br>

        $xmlSpecies

        <br>

        $xmlSex

        <br>

        $breed

        <br>

        <br>

        <a href='$applicationLink' target='_blank'>Complete Interest Form for $xmlAnimalName</a>

        <br>

    </div>







<!--

xmlCompanyID                = $xmlCompanyID<br>\n

xmlAnimalID                 = $xmlAnimalID<br>\n

xmlAnimalName               = $xmlAnimalName<br>\n

xmlSpecies                  = $xmlSpecies<br>\n

xmlSex                      = $xmlSex<br>\n

xmlAltered                  = $xmlAltered<br>\n

xmlPrimaryBreed             = $xmlPrimaryBreed<br>\n

xmlSecondaryBreed           = $xmlSecondaryBreed<br>\n

xmlPrimaryColor             = $xmlPrimaryColor<br>\n

xmlSecondaryColor           = $xmlSecondaryColor<br>\n

xmlAge                      = $xmlAge<br>\n

xmlSize                     = $xmlSize<br>\n

xmlHousetrained             = $xmlHousetrained<br>\n

xmlDeclawed                 = $xmlDeclawed<br>\n

xmlPrice                    = $xmlPrice<br>\n

xmlLastIntakeDate           = $xmlLastIntakeDate<br>\n

xmlLocation                 = $xmlLocation<br>\n

xmlDescription              = $xmlDescription<br>\n

xmlPhoto1                   = $xmlPhoto1<br>\n

xmlPhoto2                   = $xmlPhoto2<br>\n

xmlPhoto3                   = $xmlPhoto3<br>\n

xmlOnHold                   = $xmlOnHold<br>\n

xmlSpecialNeeds             = $xmlSpecialNeeds<br>\n

xmlNoDogs                   = $xmlNoDogs<br>\n

xmlNoCats                   = $xmlNoCats<br>\n

xmlNoKids                   = $xmlNoKids<br>\n

xmlBehaviorResult           = $xmlBehaviorResult<br>\n

xmlMemoList                 = $xmlMemoList<br>\n

xmlSite                     = $xmlSite<br>\n

xmlTimeInFormerHome         = $xmlTimeInFormerHome<br>\n

xmlReasonForSurrender       = $xmlReasonForSurrender<br>\n

xmlPrevEnvironment          = $xmlPrevEnvironment<br>\n

xmlLivedWithChildren        = $xmlLivedWithChildren<br>\n

xmlLivedWithAnimals         = $xmlLivedWithAnimals<br>\n

xmlLivedWithAnimalTypes     = $xmlLivedWithAnimalTypes<br>\n

xmlBodyWeight               = $xmlBodyWeight<br>\n

xmlDateOfBirth              = $xmlDateOfBirth<br>\n

xmlAnimalRefNumber          = $xmlAnimalRefNumber<br>\n

xmlVideoID                  = $xmlVideoID<br>\n

xmlBehaviorTestList         = $xmlBehaviorTestList<br>\n

xmlStage                    = $xmlStage<br>\n

xmlAnimalType               = $xmlAnimalType<br>\n

xmlAgeGroup                 = $xmlAgeGroup<br>\n

xmlWildlifeIntakeInjury     = $xmlWildlifeIntakeInjury<br>\n

xmlWildlifeIntakeCause      = $xmlWildlifeIntakeCause<br>\n

xmlBuddyID                  = $xmlBuddyID<br>\n

xmlFeatured                 = $xmlFeatured<br>\n

xmlSublocation              = $xmlSublocation<br>\n

xmlChipNumber               = $xmlChipNumber<br>\n

xmlColorPattern             = $xmlColorPattern<br>\n

xmlAdoptionApplicationUrl   = $xmlAdoptionApplicationUrl<br>\n

xmlBannerURL                = $xmlBannerURL<br>\n

-->

</td>

HTML;



    echo "</tr></table>";



}



function outputAdoptionList($selfURLIN,$urlWSAuthKeyIN,$xmlWSIN) {



    // Count the results of the XML array

    $xmlArrayCount = count($xmlWSIN);

    

    // Sets the counter to zero to use to loop through array count

    $counter = 0;



    echo "<h2 align='center'>AdoptionList Output</h2>\n\n";



    echo "\n\n<table align='center'><tr>\n";



    // If the counter value is less than the xml Array Count

    while ($counter < $xmlArrayCount-1) {

        

        foreach ($xmlWSIN->XmlNode->$counter->adoption as $output) {

            

            // Set default value of non-mandatory fields equal to "Not Defined"

            $xmlAnimalName = $xmlSalutation = $xmlEmail = "Not Defined";

            

            // Mandatory Fields that will always have a value

            $xmlAnimalID        = $output->AnimalID;

            $xmlSpecies         = $output->Species;

            $xmlSex             = $output->Sex;

            $xmlAdoptionDate    = $output->AdoptionDate;

            $xmlReleaseDate     = $output->ReleaseDate;

            $xmlPersonID        = $output->PersonID;

            $xmlFirstName       = $output->FirstName;

            $xmlLastName        = $output->LastName;



            // Check to see if there is a value in the non-mandatory fields

            // If there is a value, replace the variable default value "Not Defined" with the actual value of the output

            if (strlen(preg_replace('/\s+/','',$output->AnimalName)) != 0)     { 

                $xmlAnimalName = $output->AnimalName; 

            }



            if (strlen(preg_replace('/\s+/','',$output->Salutation)) != 0)     { 

                $xmlSalutation = $output->Salutation; 

            }



            if (strlen(preg_replace('/\s+/','',$output->Email)) != 0)     { 

                $xmlEmail = $output->Email; 

            }



            $AdoptionDateOnly = substr($xmlAdoptionDate,0,10);



            $xmlAdoptionDetailsLink = $selfURLIN . '?method=AdoptionDetails&animalID='. $xmlAnimalID . '&authkey=' . $urlWSAuthKeyIN;

                            

echo <<<HTML

<td width='25%'>

    <div align='center'>

        <a href='$xmlAdoptionDetailsLink'>

            <strong>

                $xmlAnimalName

            </strong>

        </a>

        <br>

        $xmlSpecies

        <br>

        $AdoptionDateOnly

        <br>

        $xmlLastName, $xmlFirstName

        <br>

        <br>

        <br>

    </div>



<!--

All Output Values

xmlAnimalID     = $xmlAnimalID<br>\n;

xmlAnimalName   = $xmlAnimalName<br>\n;

xmlSpecies      = $xmlSpecies <br>\n;

xmlSex          = $xmlSex <br>\n;

xmlAdoptionDate = $xmlAdoptionDate<br>\n;

xmlReleaseDate  = $xmlReleaseDate <br>\n;

xmlPersonID     = $xmlPersonID<br>\n;

xmlFirstName    = $xmlFirstName <br>\n;

xmlLastName     = $xmlLastName<br>\n;

xmlSalutation   = $xmlSalutation <br>\n;

xmlEmail        = $xmlEmail <br>\n;

-->

</td>

HTML;





            if (($counter+1) % 4 == 0) {

                echo "</tr><tr>";

            }

            

        }



        // Increment Counter

        $counter++;

        

    }



    echo "</tr></table>";



}



function outputAdoptionDetails($xmlWSArrayIN) {



    echo "<h2 align='center'>AdoptionDetails Output</h2>\n\n";



    echo "\n\n<table align='center'><tr>\n";



    // Set default value of non-mandatory fields equal to "Not Defined"

    $xmlAnimalName = $xmlSalutation = $xmlEmail = "Not Defined";

    

    // Mandatory Fields that will always have a value

    $xmlAnimalID        = $xmlWSArrayIN['AnimalID'];

    $xmlSpecies         = $xmlWSArrayIN['Species'];

    $xmlSex             = $xmlWSArrayIN['Sex'];

    $xmlAdoptionDate    = $xmlWSArrayIN['AdoptionDate'];

    $xmlReleaseDate     = $xmlWSArrayIN['ReleaseDate'];

    $xmlPersonID        = $xmlWSArrayIN['PersonID'];

    $xmlFirstName       = $xmlWSArrayIN['FirstName']; 

    $xmlLastName        = $xmlWSArrayIN['LastName'];





    // Check to see if there is a value in the non-mandatory fields

    // If there is a value, replace the variable default value "Not Defined" with the actual value of the output

    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['AnimalName'])) != 0)     { 

        $xmlAnimalName = $xmlWSArrayIN['AnimalName'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Salutation'])) != 0)     {

        $xmlSalutation = $xmlWSArrayIN['Salutation']; 

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Email'])) != 0)          { 

        $xmlEmail = $xmlWSArrayIN['Email'];

    }



    $AdoptionDateOnly = substr($xmlAdoptionDate,0,10);



echo <<<HTML

<td>

    <div align='center'>

        <strong>

            $xmlAnimalName

        </strong>

        <br>

        $xmlSpecies

        <br>

        $xmlSex

        <br>

        $xmlLastName, $xmlFirstName

        <br>

        Adoption Date: $AdoptionDateOnly

        <br>

        Email: $xmlEmail

        <br><br>

    </div>



<!--

All Output Values

xmlAnimalID     = $xmlAnimalID<br>\n

xmlAnimalName   = $xmlAnimalName<br>\n

xmlSpecies      = $xmlSpecies <br>\n

xmlSex          = $xmlSex <br>\n

xmlAdoptionDate = $xmlAdoptionDate<br>\n

xmlReleaseDate  = $xmlReleaseDate <br>\n

xmlPersonID     = $xmlPersonID<br>\n

xmlFirstName    = $xmlFirstName <br>\n

xmlLastName     = $xmlLastName<br>\n

xmlSalutation   = $xmlSalutation <br>\n

xmlEmail        = $xmlEmail <br>\n

-->

</td>

HTML;



    echo "</tr></table>";



}



function outputFoundSearch($selfURLIN,$urlWSAuthKeyIN,$xmlWSIN) {



    // Count the results of the XML array

    $xmlArrayCount = count($xmlWSIN);

    

    // Sets the counter to zero to use to loop through array count

    $counter = 0;



    echo "<h2 align='center'>foundSearch Output</h2>\n\n";



    echo "\n\n<table align='center'><tr>\n";



    // If the counter value is less than the xml Array Count

    while ($counter < $xmlArrayCount-1) {

        

        foreach ($xmlWSIN->XmlNode->$counter->an as $output) {

            

            // Set default value of non-mandatory fields equal to "Not Defined"

            $xmlAnimalName = $xmlStage = $xmlSecondaryBreed = $xmlSecondaryColor = $xmlAnimalRefNumber = "Not Defined";

            

            // Mandatory Fields that will always have a value

            $xmlAnimalID        = $output->ID;

            $xmlSpecies         = $output->Species;

            $xmlPrimaryBreed    = $output->PrimaryBreed;

            $xmlAgeMonths       = $output->Age;

            $xmlSex             = $output->Sex;

            $xmlFoundDate       = $output->FoundDate;

            $xmlFoundAddress    = $output->FoundAddress;

            $xmlType            = $output->Type;

            $xmlLocation        = $output->Location;

            $xmlPhoto           = $output->Photo;

            $xmlPrimaryColor    = $output->PrimaryColor;

            $xmlCity            = $output->City;

            $xmlState           = $output->State;

            $xmlSpayedNeutered  = $output->SpayedNeutered;

            $xmlJurisdiction    = $output->Jurisdiction;



            // Check to see if there is a value in the non-mandatory fields

            // If there is a value, replace the variable default value "Not Defined" with the actual value of the output

            if (strlen(preg_replace('/\s+/','',$output->Name)) != 0)     { 

                $xmlAnimalName = $output->Name; 

            }



            if (strlen(preg_replace('/\s+/','',$output->Stage)) != 0)     { 

                $xmlStage = $output->Stage; 

            }



            if (strlen(preg_replace('/\s+/','',$output->SecondaryBreed)) != 0)     { 

                $xmlSecondaryBreed = $output->SecondaryBreed; 

            }



            if (strlen(preg_replace('/\s+/','',$output->SecondaryColor)) != 0)     { 

                $xmlSecondaryColor = $output->SecondaryColor; 

            }



            if (strlen(preg_replace('/\s+/','',$output->ARN)) != 0)     { 

                $xmlAnimalRefNumber = $output->ARN; 

            }

            

            $FoundDateOnly = substr($xmlFoundDate,0,10);

            

            // Create Single Breed Variable for display

            $breed = "$xmlPrimaryBreed/$xmlSecondaryBreed";

            if ($xmlSecondaryBreed == "Not Defined") {

                

                $breed = $xmlPrimaryBreed;

                

            }



            // Create Single Breed Variable for display

            $color = "$xmlPrimaryColor/$xmlSecondaryColor";

            if ($xmlSecondaryColor == "Not Defined") {

                

                $color = $xmlPrimaryColor;

                

            }



            $xmlFosterDetailsLink = $selfURLIN . '?method=foundDetails&animalID='. $xmlAnimalID . '&authkey=' . $urlWSAuthKeyIN;

                            

echo <<<HTML

<td width='25%'>

    <div align='center'>

        <a href='$xmlFosterDetailsLink'>

            <strong>

                <img src='$xmlPhoto'>

                <br>

                <br>

                $xmlAnimalName

            </strong>

        </a>

        <br>

        $breed

        <br>

        $color

        <br>

        $xmlSpecies

        <br>

        Date Found: $FoundDateOnly

        <br>

        <br>

        <br>

    </div>



<!--

All Output Values

xmlAnimalID         = $xmlAnimalID<br>\n

xmlSpecies          = $xmlSpecies <br>\n

xmlPrimaryBreed     = $xmlPrimaryBreed<br>\n

xmlAgeMonths        = $xmlAgeMonths <br>\n

xmlSex              = $xmlSex <br>\n

xmlFoundDate        = $xmlFoundDate <br>\n

xmlFoundAddress     = $xmlFoundAddress<br>\n

xmlType             = $xmlType<br>\n

xmlLocation         = $xmlLocation<br>\n

xmlPhoto            = $xmlPhoto <br>\n

xmlPrimaryColor     = $xmlPrimaryColor<br>\n

xmlCity             = $xmlCity<br>\n

xmlState            = $xmlState <br>\n

xmlSpayedNeutered   = $xmlSpayedNeutered<br>\n

xmlJurisdiction     = $xmlJurisdiction<br>\n

xmlAnimalName       = $xmlAnimalName <br>\n

xmlStage            = $xmlStage <br>\n

xmlSecondaryBreed   = $xmlSecondaryBreed <br>\n

xmlSecondaryColor   = $xmlSecondaryColor <br>\n

xmlAnimalRefNumber  = $xmlAnimalRefNumber <br>\n

-->

</td>

HTML;





            if (($counter+1) % 4 == 0) {

                echo "</tr><tr>";

            }

            

        }



        // Increment Counter

        $counter++;

        

    }



    echo "</tr></table>";

    

}



function outputFoundDetails($xmlWSArrayIN) {



    echo "<h2 align='center'>foundDetails Output</h2>\n\n";



    echo "\n\n<table align='center'><tr>\n";



    // Set default value of non-mandatory fields equal to "Not Defined"

    $xmlAnimalName = $xmlSecondaryBreed = $xmlPhoto2 = $xmlPhoto3 = $xmlSecondaryColor = "Not Defined";

    $xmlColorPattern = $xmlTail = $xmlCoat = $xmlEyes = $xmlEars = $xmlMarks = $xmlCollarType = "Not Defined";

    $xmlCollarColor = $xmlCollarType2 = $xmlCollarColor2 = $xmlDsc = $xmlAnimalRefNumber = $xmlStage = "Not Defined";

    

    // Mandatory Fields that will always have a value

    $xmlAnimalID        = $xmlWSArrayIN['ID'];

    $xmlCompanyID       = $xmlWSArrayIN['CompanyID'];

    $xmlSpecies         = $xmlWSArrayIN['Species'];

    $xmlPrimaryBreed    = $xmlWSArrayIN['PrimaryBreed'];

    $xmlSex             = $xmlWSArrayIN['Sex'];

    $xmlAgeMonths       = $xmlWSArrayIN['Age'];

    $xmlFoundDate       = $xmlWSArrayIN['FoundDate'];

    $xmlFoundLocation   = $xmlWSArrayIN['FoundLocation'];

    $xmlPhoto           = $xmlWSArrayIN['Photo']; 

    $xmlPrimaryColor    = $xmlWSArrayIN['PrimaryColor'];

    $xmlLocation        = $xmlWSArrayIN['Location'];

    $xmlSite            = $xmlWSArrayIN['Site']; 

    $xmlSize            = $xmlWSArrayIN['Size'];

    $xmlBodyWeight      = $xmlWSArrayIN['BodyWeight']; 

    $xmlBodyWeightUnit  = $xmlWSArrayIN['BodyWeightUnit'];

    $xmlDeclawed        = $xmlWSArrayIN['Declawed'];

    $xmlFoundType       = $xmlWSArrayIN['FoundType']; 

    $xmlCity            = $xmlWSArrayIN['City'];

    $xmlState           = $xmlWSArrayIN['State'];



    // Check to see if there is a value in the non-mandatory fields

    // If there is a value, replace the variable default value "Not Defined" with the actual value of the output

    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['AnimalName'])) != 0)     { 

        $xmlAnimalName = $xmlWSArrayIN['AnimalName'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['SecondaryBreed'])) != 0)     { 

        $xmlSecondaryBreed = $xmlWSArrayIN['SecondaryBreed'];

    }

    

    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Photo2'])) != 0)     { 

        $xmlPhoto2 = $xmlWSArrayIN['Photo2'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Photo3'])) != 0)     { 

        $xmlPhoto3 = $xmlWSArrayIN['Photo3'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['SecondaryColor'])) != 0)     { 

        $xmlSecondaryColor = $xmlWSArrayIN['SecondaryColor'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['ColorPattern'])) != 0)     { 

        $xmlColorPattern = $xmlWSArrayIN['ColorPattern'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Tail'])) != 0)     { 

        $xmlTail = $xmlWSArrayIN['Tail'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Coat'])) != 0)     { 

        $xmlCoat = $xmlWSArrayIN['Coat'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Eyes'])) != 0)     { 

        $xmlEyes = $xmlWSArrayIN['Eyes'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Ears'])) != 0)     { 

        $xmlEars = $xmlWSArrayIN['Ears'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Marks'])) != 0)     { 

        $xmlMarks = $xmlWSArrayIN['Marks'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['CollarType'])) != 0)     { 

        $xmlCollarType = $xmlWSArrayIN['CollarType'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['CollarColor'])) != 0)     { 

        $xmlCollarColor = $xmlWSArrayIN['CollarColor'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['CollarType2'])) != 0)     { 

        $xmlCollarType2 = $xmlWSArrayIN['CollarType2'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['CollarColor2'])) != 0)     { 

        $xmlCollarColor2 = $xmlWSArrayIN['CollarColor2'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Dsc'])) != 0)     { 

        $xmlDsc = $xmlWSArrayIN['Dsc'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['ARN'])) != 0)     { 

        $xmlAnimalRefNumber = $xmlWSArrayIN['ARN'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Stage'])) != 0)     { 

        $xmlStage = $xmlWSArrayIN['Stage'];

    }



    $FoundDateOnly = substr($xmlFoundDate,0,10);

    

    // Create Single Breed Variable for display

    $breed = "$xmlPrimaryBreed/$xmlSecondaryBreed";

    if ($xmlSecondaryBreed == "Not Defined") {

        

        $breed = $xmlPrimaryBreed;

        

    }



    // Create Single Breed Variable for display

    $color = "$xmlPrimaryColor/$xmlSecondaryColor";

    if ($xmlSecondaryColor == "Not Defined") {

        

        $color = $xmlPrimaryColor;

        

    }





echo <<<HTML

<td width='25%'>

    <div align='center'>

        <strong>

            <img src='$xmlPhoto'>

            <br>

            <br>

            $xmlAnimalName

        </strong>

        <br>

        $breed

        <br>

        $color

        <br>

        $xmlSpecies

        <br>

        Date Found: $FoundDateOnly

        <br>

        Found Location:

        <br>

        $xmlFoundLocation

        <br>

        <br>

    </div>



<!--

All Output Values

xmlAnimalID = $xmlAnimalID<br>\n

xmlCompanyID = $xmlCompanyID<br>\n

xmlSpecies = $xmlSpecies<br>\n

xmlPrimaryBreed = $xmlPrimaryBreed<br>\n

xmlSex = $xmlSex<br>\n

xmlAgeMonths = $xmlAgeMonths<br>\n

xmlFoundDate = $xmlFoundDate<br>\n

xmlFoundLocation = $xmlFoundLocation<br>\n

xmlPhoto = $xmlPhoto<br>\n

xmlPrimaryColor = $xmlPrimaryColor<br>\n

xmlLocation = $xmlLocation<br>\n

xmlSite = $xmlSite<br>\n

xmlSize = $xmlSize<br>\n

xmlBodyWeight = $xmlBodyWeight<br>\n

xmlBodyWeightUnit = $xmlBodyWeightUnit<br>\n

xmlDeclawed = $xmlDeclawed<br>\n

xmlFoundType = $xmlFoundType<br>\n

xmlCity = $xmlCity<br>\n

xmlState = $xmlState<br>\n

xmlAnimalName = $xmlAnimalName<br>\n

xmlSecondaryBreed = $xmlSecondaryBreed<br>\n

xmlPhoto2 = $xmlPhoto2<br>\n

xmlPhoto3 = $xmlPhoto3<br>\n

xmlSecondaryColor = $xmlSecondaryColor<br>\n

xmlColorPattern = $xmlColorPattern<br>\n

xmlTail = $xmlTail<br>\n

xmlCoat = $xmlCoat<br>\n

xmlEyes = $xmlEyes<br>\n

xmlEars = $xmlEars<br>\n

xmlMarks = $xmlMarks<br>\n

xmlCollarType = $xmlCollarType<br>\n

xmlCollarColor = $xmlCollarColor<br>\n

xmlCollarType2 = $xmlCollarType2<br>\n

xmlCollarColor2 = $xmlCollarColor2<br>\n

xmlDsc = $xmlDsc<br>\n

xmlAnimalRefNumber = $xmlAnimalRefNumber<br>\n

xmlStage = $xmlStage<br>\n

-->

HTML;



    echo "</tr></table>";



}



function outputLostSearch($selfURLIN,$urlWSAuthKeyIN,$xmlWSIN) {



    // Count the results of the XML array

    $xmlArrayCount = count($xmlWSIN);

    

    // Sets the counter to zero to use to loop through array count

    $counter = 0;



    echo "<h2 align='center'>lostSearch Output</h2>\n\n";



    echo "\n\n<table align='center'><tr>\n";



    // If the counter value is less than the xml Array Count

    while ($counter < $xmlArrayCount-1) {

        

        foreach ($xmlWSIN->XmlNode->$counter->an as $output) {

            

            // Set default value of non-mandatory fields equal to "Not Defined"

            $xmlAnimalName = $xmlSecondaryBreed = $xmlSecondaryColor = $xmlAnimalRefNumber = "Not Defined";

            

            // Mandatory Fields that will always have a value

            $xmlAnimalID        = $output->ID;

            $xmlSpecies         = $output->Species;

            $xmlPrimaryBreed    = $output->PrimaryBreed;

            $xmlAgeMonths       = $output->Age;

            $xmlSex             = $output->Sex;

            $xmlLostDate        = $output->LostDate;

            $xmlLostAddress     = $output->LostAddress;

            $xmlType            = $output->Type;

            $xmlPhoto           = $output->Photo;

            $xmlPrimaryColor    = $output->PrimaryColor;

            $xmlCity            = $output->City;

            $xmlState           = $output->State;

            $xmlSpayedNeutered  = $output->SpayedNeutered;



            // Check to see if there is a value in the non-mandatory fields

            // If there is a value, replace the variable default value "Not Defined" with the actual value of the output

            if (strlen(preg_replace('/\s+/','',$output->Name)) != 0)     { 

                $xmlAnimalName = $output->Name; 

            }



            if (strlen(preg_replace('/\s+/','',$output->SecondaryBreed)) != 0)     { 

                $xmlSecondaryBreed = $output->SecondaryBreed; 

            }



            if (strlen(preg_replace('/\s+/','',$output->SecondaryColor)) != 0)     { 

                $xmlSecondaryColor = $output->SecondaryColor; 

            }



            if (strlen(preg_replace('/\s+/','',$output->ARN)) != 0)     { 

                $xmlAnimalRefNumber = $output->ARN; 

            }

            

            $LostDateOnly = substr($xmlLostDate,0,10);

            

            // Create Single Breed Variable for display

            $breed = "$xmlPrimaryBreed/$xmlSecondaryBreed";

            if ($xmlSecondaryBreed == "Not Defined") {

                

                $breed = $xmlPrimaryBreed;

                

            }



            // Create Single Breed Variable for display

            $color = "$xmlPrimaryColor/$xmlSecondaryColor";

            if ($xmlSecondaryColor == "Not Defined") {

                

                $color = $xmlPrimaryColor;

                

            }



            $xmlLostDetailsLink = $selfURLIN . '?method=lostDetails&animalID='. $xmlAnimalID . '&authkey=' . $urlWSAuthKeyIN;

                            

echo <<<HTML

<td width='25%'>

    <div align='center'>

        <a href='$xmlLostDetailsLink'>

            <strong>

                <img src='$xmlPhoto'>

                <br>

                <br>

                $xmlAnimalName

            </strong>

        </a>

        <br>

        $breed

        <br>

        $color

        <br>

        $xmlSpecies

        <br>

        Date Lost: $LostDateOnly

        <br>

        <br>

        <br>

    </div>



<!--

All Output Values

xmlAnimalID         = $xmlAnimalID<br>\n

xmlSpecies          = $xmlSpecies <br>\n

xmlPrimaryBreed     = $xmlPrimaryBreed<br>\n

xmlAgeMonths        = $xmlAgeMonths <br>\n

xmlSex              = $xmlSex <br>\n

xmlLostDate         = $xmlLostDate <br>\n

xmlLostAddress      = $xmlLostAddress<br>\n

xmlType             = $xmlType<br>\n

xmlLocation         = $xmlLocation<br>\n

xmlPhoto            = $xmlPhoto <br>\n

xmlPrimaryColor     = $xmlPrimaryColor<br>\n

xmlCity             = $xmlCity<br>\n

xmlState            = $xmlState <br>\n

xmlSpayedNeutered   = $xmlSpayedNeutered<br>\n

xmlJurisdiction     = $xmlJurisdiction<br>\n

xmlAnimalName       = $xmlAnimalName <br>\n

xmlStage            = $xmlStage <br>\n

xmlSecondaryBreed   = $xmlSecondaryBreed <br>\n

xmlSecondaryColor   = $xmlSecondaryColor <br>\n

xmlAnimalRefNumber  = $xmlAnimalRefNumber <br>\n

-->

</td>

HTML;





            if (($counter+1) % 4 == 0) {

                echo "</tr><tr>";

            }

            

        }



        // Increment Counter

        $counter++;

        

    }



    echo "</tr></table>";



}        



function outputLostDetails($xmlWSArrayIN) {



    echo "<h2 align='center'>foundDetails Output</h2>\n\n";



    echo "\n\n<table align='center'><tr>\n";



    // Set default value of non-mandatory fields equal to "Not Defined"

    $xmlAnimalName = $xmlSecondaryBreed = $xmlPhoto2 = $xmlPhoto3 = $xmlSecondaryColor = "Not Defined";

    $xmlColorPattern = $xmlTail = $xmlCoat = $xmlEyes = $xmlEars = $xmlMarks = $xmlCollarType = "Not Defined";

    $xmlCollarColor = $xmlCollarType2 = $xmlCollarColor2 = $xmlDsc = $xmlAnimalRefNumber = $xmlStage = "Not Defined";

    

    // Mandatory Fields that will always have a value

    $xmlAnimalID        = $xmlWSArrayIN['ID'];

    $xmlCompanyID       = $xmlWSArrayIN['CompanyID'];

    $xmlSpecies         = $xmlWSArrayIN['Species'];

    $xmlPrimaryBreed    = $xmlWSArrayIN['PrimaryBreed'];

    $xmlSex             = $xmlWSArrayIN['Sex'];

    $xmlAgeMonths       = $xmlWSArrayIN['Age'];

    $xmlLostDate        = $xmlWSArrayIN['LostDate'];

    $xmlLostLocation    = $xmlWSArrayIN['LostLocation'];

    $xmlPhoto           = $xmlWSArrayIN['Photo']; 

    $xmlPrimaryColor    = $xmlWSArrayIN['PrimaryColor'];

    $xmlLocation        = $xmlWSArrayIN['Location'];

    $xmlSite            = $xmlWSArrayIN['Site']; 

    $xmlSize            = $xmlWSArrayIN['Size'];

    $xmlBodyWeight      = $xmlWSArrayIN['BodyWeight']; 

    $xmlBodyWeightUnit  = $xmlWSArrayIN['BodyWeightUnit'];

    $xmlDeclawed        = $xmlWSArrayIN['Declawed'];

    $xmlFoundType       = $xmlWSArrayIN['FoundType']; 

    $xmlCity            = $xmlWSArrayIN['City'];

    $xmlState           = $xmlWSArrayIN['State'];



    // Check to see if there is a value in the non-mandatory fields

    // If there is a value, replace the variable default value "Not Defined" with the actual value of the output

    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['AnimalName'])) != 0)     { 

        $xmlAnimalName = $xmlWSArrayIN['AnimalName'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['SecondaryBreed'])) != 0)     { 

        $xmlSecondaryBreed = $xmlWSArrayIN['SecondaryBreed'];

    }

    

    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Photo2'])) != 0)     { 

        $xmlPhoto2 = $xmlWSArrayIN['Photo2'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Photo3'])) != 0)     { 

        $xmlPhoto3 = $xmlWSArrayIN['Photo3'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['SecondaryColor'])) != 0)     { 

        $xmlSecondaryColor = $xmlWSArrayIN['SecondaryColor'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['ColorPattern'])) != 0)     { 

        $xmlColorPattern = $xmlWSArrayIN['ColorPattern'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Tail'])) != 0)     { 

        $xmlTail = $xmlWSArrayIN['Tail'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Coat'])) != 0)     { 

        $xmlCoat = $xmlWSArrayIN['Coat'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Eyes'])) != 0)     { 

        $xmlEyes = $xmlWSArrayIN['Eyes'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Ears'])) != 0)     { 

        $xmlEars = $xmlWSArrayIN['Ears'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Marks'])) != 0)     { 

        $xmlMarks = $xmlWSArrayIN['Marks'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['CollarType'])) != 0)     { 

        $xmlCollarType = $xmlWSArrayIN['CollarType'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['CollarColor'])) != 0)     { 

        $xmlCollarColor = $xmlWSArrayIN['CollarColor'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['CollarType2'])) != 0)     { 

        $xmlCollarType2 = $xmlWSArrayIN['CollarType2'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['CollarColor2'])) != 0)     { 

        $xmlCollarColor2 = $xmlWSArrayIN['CollarColor2'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Dsc'])) != 0)     { 

        $xmlDsc = $xmlWSArrayIN['Dsc'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['ARN'])) != 0)     { 

        $xmlAnimalRefNumber = $xmlWSArrayIN['ARN'];

    }



    if (strlen(preg_replace('/\s+/','',$xmlWSArrayIN['Stage'])) != 0)     { 

        $xmlStage = $xmlWSArrayIN['Stage'];

    }



    $LostDateOnly = substr($xmlLostDate,0,10);

    

    // Create Single Breed Variable for display

    $breed = "$xmlPrimaryBreed/$xmlSecondaryBreed";

    if ($xmlSecondaryBreed == "Not Defined") {

        

        $breed = $xmlPrimaryBreed;

        

    }



    // Create Single Breed Variable for display

    $color = "$xmlPrimaryColor/$xmlSecondaryColor";

    if ($xmlSecondaryColor == "Not Defined") {

        

        $color = $xmlPrimaryColor;

        

    }





echo <<<HTML

<td width='25%'>

    <div align='center'>

        <strong>

            <img src='$xmlPhoto'>

            <br>

            <br>

            $xmlAnimalName

        </strong>

        <br>

        $breed

        <br>

        $color

        <br>

        $xmlSpecies

        <br>

        Date Lost: $LostDateOnly

        <br>

        Lost Location:

        <br>

        $xmlLostLocation

        <br>

        <br>

    </div>



<!--

All Output Values

xmlAnimalID         = $xmlAnimalID<br>\n

xmlCompanyID        = $xmlCompanyID<br>\n

xmlSpecies          = $xmlSpecies<br>\n

xmlPrimaryBreed     = $xmlPrimaryBreed<br>\n

xmlSex              = $xmlSex<br>\n

xmlAgeMonths        = $xmlAgeMonths<br>\n

xmlLostDate         = $xmllLostDate<br>\n

xmllLostLocation    = $xmlLostLocation<br>\n

xmlPhoto            = $xmlPhoto<br>\n

xmlPrimaryColor     = $xmlPrimaryColor<br>\n

xmlLocation         = $xmlLocation<br>\n

xmlSite             = $xmlSite<br>\n

xmlSize             = $xmlSize<br>\n

xmlBodyWeight       = $xmlBodyWeight<br>\n

xmlBodyWeightUnit   = $xmlBodyWeightUnit<br>\n

xmlDeclawed         = $xmlDeclawed<br>\n

xmlFoundType        = $xmlFoundType<br>\n

xmlCity             = $xmlCity<br>\n

xmlState            = $xmlState<br>\n

xmlAnimalName       = $xmlAnimalName<br>\n

xmlSecondaryBreed   = $xmlSecondaryBreed<br>\n

xmlPhoto2           = $xmlPhoto2<br>\n

xmlPhoto3           = $xmlPhoto3<br>\n

xmlSecondaryColor   = $xmlSecondaryColor<br>\n

xmlColorPattern     = $xmlColorPattern<br>\n

xmlTail             = $xmlTail<br>\n

xmlCoat             = $xmlCoat<br>\n

xmlEyes             = $xmlEyes<br>\n

xmlEars             = $xmlEars<br>\n

xmlMarks            = $xmlMarks<br>\n

xmlCollarType       = $xmlCollarType<br>\n

xmlCollarColor      = $xmlCollarColor<br>\n

xmlCollarType2      = $xmlCollarType2<br>\n

xmlCollarColor2     = $xmlCollarColor2<br>\n

xmlDsc              = $xmlDsc<br>\n

xmlAnimalRefNumber  = $xmlAnimalRefNumber<br>\n

-->

HTML;



    echo "</tr></table>";



}



?>

