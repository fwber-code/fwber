<?php 

	session_start(); 

	include("_init.php");
	include("_names.php");
	include("_debug.php");

    include("_profileVars.php");
    include("_secrets.php");
    include("_globals.php");
    include("_emailFunctions.php");

	//first make sure we are a legit user.
	if(deleteCookiesIfInvalid()==false){header('Location: '.getSiteURL());return;}//full auth for actions
	
	goHomeIfCookieNotSet();
	

	//make sure we've got an action.
	if(!isset($_GET['action'])||!isset($_GET['d'])||empty($_GET['action'])||empty($_GET['d']))exit('no action');
	
	//make sure we've got the right action.
	if(
		$_GET['action']!='askprivate'&&
		$_GET['action']!='notmytype'&&
		$_GET['action']!='authorizeprivate'&&
		$_GET['action']!='undonotmytype'&&
		$_GET['action']!='rejection'
		
	)exit('bad action');
	
	$action=$_GET['action'];

	
	initAllVariablesFromDB();
	
	$db = mysqli_connect($dburl,$dbuser,$dbpass);
	if(!$db)exit(mysqli_connect_error());
	
	$email = mysqli_escape_string($db,$_SESSION["email"]);	
	
	$theiruserid = mysqli_escape_string($db,$_GET['d']);

    //-------------------------------------------------
    //get my status csv lists
    //-------------------------------------------------

    //db entries:
    //notMyType
    //waitingForThemPrivate
    //private

    $dbquerystring = sprintf("SELECT id, firstName, notMyType, waitingForThemPrivate, private FROM ".$dbname.".users WHERE email='%s'",$email);
    $dbquery = mysqli_query($db,$dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);

    $myuserid=$dbresults['id'];
    $myFirstName = $dbresults['firstName'];

    $myNotMyType=explode(",",trim(trim($dbresults['notMyType']),","));
    $myWaitingForThemPrivate=explode(",",trim(trim($dbresults['waitingForThemPrivate']),","));
    $myPrivate=explode(",",trim(trim($dbresults['private']),","));

    mysqli_free_result($dbquery);

	//-------------------------------------------------	
	//make sure this user is accessible to us
		//run a simple match check, distance, gender
	//-------------------------------------------------	

        //first sort users by latitude
        //then sort by longitude
        //then remove those who aren't looking for my gender.
        //then remove gender that we aren't looking for.

        if($distance=="dist0m"	)$distMiles = 2.0;
        if($distance=="dist5m"	)$distMiles = 5.0;
        if($distance=="dist10m"	)$distMiles = 10.0;
        if($distance=="dist20m"	)$distMiles = 20.0;
        if($distance=="dist50m"	)$distMiles = 50.0;

        $latDist = (1.1 * $distMiles)/49.1;
        $lonDist = (1.1 * $distMiles)/69.1;

        $minLat = $lat - $latDist;
        $maxLat = $lat + $latDist;
        $minLon = $lon - $lonDist;
        $maxLon = $lon + $lonDist;

        $dbquerystring =
        "SELECT * FROM ".$dbname.".users WHERE id = '{$theiruserid}' AND lat >= '{$minLat}' AND lat <= '{$maxLat}' AND lon >= '{$minLon}' AND lon <= '{$maxLon}'";

        //first make sure they want MY gender
        if($gender=='male')$dbquerystring.=" AND b_wantGenderMan='1'";
        if($gender=='female')$dbquerystring.=" AND b_wantGenderWoman='1'";
        if($gender=='mtf')$dbquerystring.=" AND b_wantGenderTSWoman='1'";
        if($gender=='ftm')$dbquerystring.=" AND b_wantGenderTSMan='1'";
        if($gender=='cdmtf')$dbquerystring.=" AND b_wantGenderCDWoman='1'";
        if($gender=='cdftm')$dbquerystring.=" AND b_wantGenderCDMan='1'";
        if($gender=='mf')$dbquerystring.=" AND b_wantGenderCoupleMF='1'";
        if($gender=='mm')$dbquerystring.=" AND b_wantGenderCoupleMM='1'";
        if($gender=='ff')$dbquerystring.=" AND b_wantGenderCoupleFF='1'";
        if($gender=='group')$dbquerystring.=" AND b_wantGenderGroup='1'";

        //then make sure I want THEIR gender
        if($b_wantGenderMan==0)$dbquerystring.=" AND gender!='male'";
        if($b_wantGenderWoman==0)$dbquerystring.=" AND gender!='female'";
        if($b_wantGenderTSWoman==0)$dbquerystring.=" AND gender!='mtf'";
        if($b_wantGenderTSMan==0)$dbquerystring.=" AND gender!='ftm'";
        if($b_wantGenderCDWoman==0)$dbquerystring.=" AND gender!='cdmtf'";
        if($b_wantGenderCDMan==0)$dbquerystring.=" AND gender!='cdftm'";
        if($b_wantGenderCoupleMF==0)$dbquerystring.=" AND gender!='mf'";
        if($b_wantGenderCoupleMM==0)$dbquerystring.=" AND gender!='mm'";
        if($b_wantGenderCoupleFF==0)$dbquerystring.=" AND gender!='ff'";
        if($b_wantGenderGroup==0)$dbquerystring.=" AND gender!='group'";


        //calculate age
        $age = getAge($birthdayMonth,$birthdayDay,$birthdayYear);

        //do they want MY age?
        $dbquerystring.=" AND wantAgeFrom <= '{$age}'";
        $dbquerystring.=" AND wantAgeTo >= '{$age}'";


        //not myself, duh
        $dbquerystring.=" AND email != '{$email}'";

        //and profile done
        $dbquerystring.=" AND profileDone='1'";
        $dbquerystring.=" AND verified='1'";

        //echo $dbquerystring;

        $dbquery = mysqli_query($db,$dbquerystring);

        $dbresults = mysqli_fetch_array($dbquery);

        if(!$dbresults)exit('no');

        $theirFirstName = $dbresults['firstName'];
        $theirEmail = $dbresults['email'];
        $theirEmailMatches = $dbresults['emailMatches'];
        $theirEmailInterested = $dbresults['emailInterested'];
        $theirEmailApproved = $dbresults['emailApproved'];
        $theirVerifyHash = $dbresults['verifyHash'];

        $theirNotMyType=explode(",",trim(trim($dbresults['notMyType']),","));
        $theirWaitingForThemPrivate=explode(",",trim(trim($dbresults['waitingForThemPrivate']),","));
        $theirPrivate=explode(",",trim(trim($dbresults['private']),","));


	//-------------------------------------------------	
	//make sure we aren't on their "not my type" list.
	//-------------------------------------------------
        foreach($theirNotMyType as $s)if($s==$myuserid)exit("not their type");

	//-------------------------------------------------	
	//make sure we've got the appropriate status to perform this action.
	//-------------------------------------------------

		
		//if askprivate
			//add them to my private
			//send email conf
		if($action=="askprivate")
		{
			$myWaitingForThemPrivate[] = $theiruserid;
		}
		
		//if authorizeprivate
			//we must be listed in their waitingForThemPrivate
			//add them to my private
			//remove me from their waitingForThemPrivate, add me to their private
			//send email conf
		if($action=="authorizeprivate")
		{

			$found=false;
			foreach($theirWaitingForThemPrivate as $s)if($s==$myuserid)$found=true;
			if($found==false)exit("They aren't waiting for us to authorize private.");

			$myPrivate[] = $theiruserid;
			
			rem_array($theirWaitingForThemPrivate,$myuserid);
			$theirPrivate[] = $myuserid;
		
		}
		
		//if private, they must NOT be in our private list.
		
			//remove them from all my lists
			//remove me from all their lists
			//put them in my notmytype list.
		if($action=="notmytype")
		{
		
			foreach($myNotMyType as $s)if($s==$theiruserid)exit("Already not your type.");


			rem_array($myWaitingForThemPrivate,$theiruserid);
			rem_array($myPrivate,$theiruserid);
			

			rem_array($theirWaitingForThemPrivate,$myuserid);
			rem_array($theirPrivate,$myuserid);
			
			$myNotMyType[] = $theiruserid;
		
		}
		
		//if undonotmytype, we must NOT be in their notmytype list, they MUST be in ours.
			//remove me from all their lists.
			//remove them from my notmytype list. (all lists)
		if($action=="undonotmytype")
		{
			//foreach($theirNotMyType as $s)if($s==$myuserid)exit("no");
			
			$found=false;
			foreach($myNotMyType as $s)if($s==$theiruserid)$found=true;
			if($found==false)exit("They are not in your 'not my type' list.");
			
			rem_array($myNotMyType,$theiruserid);
		}
		
		//if rejection, we must be in their private lists, and they must be in ours.
			//remove me from their private list (all lists)
			//remove them from my private lists (all lists)
			//set them in my notmytype
		if($action=="rejection")
		{
	
			
			$found=false;
			foreach($myPrivate as $s)if($s==$theiruserid)$found=true;
			if($found==false)exit("They are not in your private list.");

			rem_array($myPrivate,$theiruserid);
			rem_array($theirPrivate,$myuserid);

			$myNotMyType[] = $theiruserid;
		}

	//update me
	$dbquerystring = 
	sprintf("UPDATE ".$dbname.".users SET notMyType = '%s',waitingForThemPrivate = '%s',private = '%s' WHERE id='%s'",
	trim(trim(implode(",",$myNotMyType)),","),
	trim(trim(implode(",",$myWaitingForThemPrivate)),","),
	trim(trim(implode(",",$myPrivate)),","),
	$myuserid
	);
	if(!mysqli_query($db,$dbquerystring))exit("didn't work");

	//update them
	$dbquerystring = 
	sprintf("UPDATE ".$dbname.".users SET notMyType = '%s',waitingForThemPrivate = '%s',private = '%s' WHERE id='%s'",
	trim(trim(implode(",",$theirNotMyType)),","),
	trim(trim(implode(",",$theirWaitingForThemPrivate)),","),
	trim(trim(implode(",",$theirPrivate)),","),
	$theiruserid);
	if(!mysqli_query($db,$dbquerystring))exit("didn't work");

	mysqli_close($db);

	//send back done
	echo "done";

	//send notification email
	
	//only send email if their settings allow for it...
	if(

		($action=='askprivate'&&$theirEmailInterested==1)||
		($action=='authorizeprivate'&&$theirEmailApproved==1)
	)
	{
		sendMatchActionEmail($myFirstName,$theirEmail,$action, $theirVerifyHash);
	}

