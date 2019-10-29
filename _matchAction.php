<?php 

	session_start(); 

	require_once("_init.php");

	require_once("_profileVars.php");
	require_once("_secrets.php");
	require_once("_globals.php");
	
	//first make sure we are a legit user.
	if(deleteCookiesIfInvalid()==false){header('Location: '.getSiteURL());return;}//full auth for actions
	
	goHomeIfCookieNotSet();
	
	//var statusString = "";
	//if(action=="askfirstbase")statusString = "Email was sent asking for face pics!";
	//if(action=="authorizefirstbase")statusString = "You've taken it to first base! Quick, refresh your match list and see if you're interested!";
	//if(action=="askalltheway")statusString = "Email was sent asking to trade all info!";
	//if(action=="notmytype")statusString = "They weren't what you were looking for. We'll hide you from their matches to soften the blow. Out of sight, out of mind!";
	//if(action=="authorizealltheway")statusString = "You've taken it all the way! Quick, refresh your match list and take a look!";
	//if(action=="undonotmytype")statusString = "You've given them another chance! Refresh your match list to see them in your normal matches.";
	//if(action=="rejection")statusString = "You've rejected them. We'll hide you from their matches to soften the blow. Out of sight, out of mind!";

	//make sure we've got an action.
	if(!isset($_GET['action'])||!isset($_GET['d'])||empty($_GET['action'])||empty($_GET['d']))exit('no action');
	
	//make sure we've got the right action.
	if(
		$_GET['action']!='askfirstbase'&&
		$_GET['action']!='authorizefirstbase'&&
		$_GET['action']!='askalltheway'&&
		$_GET['action']!='notmytype'&&
		$_GET['action']!='authorizealltheway'&&
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
    //waitingForThemFirstBase
    //waitingForThemAllTheWay
    //allTheWay
    //firstBase

    $dbquerystring = sprintf("SELECT id, firstName, notMyType, waitingForThemFirstBase, waitingForThemAllTheWay, allTheWay, firstBase FROM ".$dbname.".users WHERE email='%s'",$email);
    $dbquery = mysqli_query($db,$dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);

    $myuserid=$dbresults['id'];
    $myFirstName = $dbresults['firstName'];

    $myNotMyType=explode(",",trim(trim($dbresults['notMyType']),","));
    $myWaitingForThemFirstBase=explode(",",trim(trim($dbresults['waitingForThemFirstBase']),","));
    $myWaitingForThemAllTheWay=explode(",",trim(trim($dbresults['waitingForThemAllTheWay']),","));
    $myAllTheWay=explode(",",trim(trim($dbresults['allTheWay']),","));
    $myFirstBase=explode(",",trim(trim($dbresults['firstBase']),","));

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
        if($gender=='mf')$dbquerystring.=" AND b_wantGenderCoupleMF='1'";
        if($gender=='mm')$dbquerystring.=" AND b_wantGenderCoupleMM='1'";
        if($gender=='ff')$dbquerystring.=" AND b_wantGenderCoupleFF='1'";
        if($gender=='group')$dbquerystring.=" AND b_wantGenderGroup='1'";

        //then make sure I want THEIR gender
        if($b_wantGenderMan==0)$dbquerystring.=" AND gender!='male'";
        if($b_wantGenderWoman==0)$dbquerystring.=" AND gender!='female'";
        if($b_wantGenderTSWoman==0)$dbquerystring.=" AND gender!='mtf'";
        if($b_wantGenderTSMan==0)$dbquerystring.=" AND gender!='ftm'";
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
        $theirWaitingForThemFirstBase=explode(",",trim(trim($dbresults['waitingForThemFirstBase']),","));
        $theirWaitingForThemAllTheWay=explode(",",trim(trim($dbresults['waitingForThemAllTheWay']),","));
        $theirAllTheWay=explode(",",trim(trim($dbresults['allTheWay']),","));
        $theirFirstBase=explode(",",trim(trim($dbresults['firstBase']),","));

	//-------------------------------------------------	
	//make sure we aren't on their "not my type" list.
	//-------------------------------------------------
        foreach($theirNotMyType as $s)if($s==$myuserid)exit("not their type");

	//-------------------------------------------------	
	//make sure we've got the appropriate status to perform this action.
	//-------------------------------------------------
		//if askfirstbase, they must be public to us, and they must not be in ANY of our db lists.
			//set them in our waitingForThemFirstBase
			//send email conf
		if($action=="askfirstbase")
		{
			foreach($myNotMyType as $s)if($s==$theiruserid)exit("They are in your 'Not My Type' list.");
			foreach($myWaitingForThemFirstBase as $s)if($s==$theiruserid)exit("You are already waiting for them to authorize First Base.");
			foreach($myWaitingForThemAllTheWay as $s)if($s==$theiruserid)exit("You are already waiting or them to authorize All The Way.");
			foreach($myAllTheWay as $s)if($s==$theiruserid)exit("They are already in your All The Way list.");
			foreach($myFirstBase as $s)if($s==$theiruserid)exit("They are already in your First Base list.");
			
			foreach($theirWaitingForThemFirstBase as $s)if($s==$myuserid)exit("They are already waiting for you to authorize First Base.");
			
			$myWaitingForThemFirstBase[] = $theiruserid;
		}
		
		//if authorizefirstbase, we must be listed in their waitingForThemFirstBase, they must NOT be in ours
			//add them to my firstBase
			//remove me from their waitingForThemFirstBase and add me to their firstBase
			//send email conf
		if($action=="authorizefirstbase")
		{

			$found=false;
			foreach($theirWaitingForThemFirstBase as $s)if($s==$myuserid)$found=true;
			if($found==false)exit("You are not in their Waiting For First Base list.");
			
			foreach($myWaitingForThemFirstBase as $s)if($s==$theiruserid)exit("You are already waiting for them to authorize First Base.");
			
			$myFirstBase[] = $theiruserid;

			rem_array($theirWaitingForThemFirstBase,$myuserid);
			
			$theirFirstBase[] = $myuserid;
		}
		
		//if askalltheway, they must be in our firstBase list, we must be in theirs.
			//remove them from my firstbase, add them to my waitingForThemAllTheWay
			//send email conf
		if($action=="askalltheway")
		{
		
			$found=false;
			foreach($myFirstBase as $s)if($s==$theiruserid)$found=true;
			if($found==false)exit("They are not in your First Base list.");
			
			$found=false;
			foreach($theirFirstBase as $s)if($s==$myuserid)$found=true;
			if($found==false)exit("You are not in their First Base list.");
			
			rem_array($myFirstBase,$theiruserid);
			
			$myWaitingForThemAllTheWay[] = $theiruserid;
		
		}
		
		//if authorizealltheway, they must be in our firstBase list
			//we must be listed in their waitingForThemAllTheWay
			//remove them from firstBase, add them to allTheWay
			//remove me from their waitingForThemAllTheWay, add me to their allTheWay
			//send email conf
		if($action=="authorizealltheway")
		{
			$found=false;
			foreach($myFirstBase as $s)if($s==$theiruserid)$found=true;
			if($found==false)exit("They aren't in your 'First Base' list yet. Can't go all the way.");
			
			$found=false;
			foreach($theirWaitingForThemAllTheWay as $s)if($s==$myuserid)$found=true;
			if($found==false)exit("They aren't waiting for us to authorize All The Way.");

			rem_array($myFirstBase,$theiruserid);
			$myAllTheWay[] = $theiruserid;
			
			rem_array($theirWaitingForThemAllTheWay,$myuserid);
			$theirAllTheWay[] = $myuserid;
		
		}
		
		//if notmytype, they must NOT be in our notmytype list.
		
			//remove them from all my lists
			//remove me from all their lists
			//put them in my notmytype list.
		if($action=="notmytype")
		{
		
			foreach($myNotMyType as $s)if($s==$theiruserid)exit("Already not your type.");

			rem_array($myFirstBase,$theiruserid);
			rem_array($myWaitingForThemFirstBase,$theiruserid);
			rem_array($myWaitingForThemAllTheWay,$theiruserid);
			rem_array($myAllTheWay,$theiruserid);
			
			rem_array($theirFirstBase,$myuserid);
			rem_array($theirWaitingForThemFirstBase,$myuserid);
			rem_array($theirWaitingForThemAllTheWay,$myuserid);
			rem_array($theirAllTheWay,$myuserid);
			
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
		
		//if rejection, we must be in their alltheway lists, and they must be in ours.
			//remove me from their alltheway list (all lists)
			//remove them from my alltheway lists (all lists)
			//set them in my notmytype
		if($action=="rejection")
		{
		
			//$found=false;
			//foreach($theirAllTheWay as $s)if($s==$myuserid)$found=true;
			//if($found==false)exit("no");
			
			$found=false;
			foreach($myAllTheWay as $s)if($s==$theiruserid)$found=true;
			if($found==false)exit("They are not in your 'All The Way' list.");

			rem_array($myAllTheWay,$theiruserid);
			rem_array($theirAllTheWay,$myuserid);

			$myNotMyType[] = $theiruserid;
		}

	//update me
	$dbquerystring = 
	sprintf("UPDATE ".$dbname.".users SET notMyType = '%s',waitingForThemFirstBase = '%s',waitingForThemAllTheWay = '%s',allTheWay = '%s',firstBase = '%s' WHERE id='%s'",
	trim(trim(implode(",",$myNotMyType)),","),
	trim(trim(implode(",",$myWaitingForThemFirstBase)),","),
	trim(trim(implode(",",$myWaitingForThemAllTheWay)),","),
	trim(trim(implode(",",$myAllTheWay)),","),
	trim(trim(implode(",",$myFirstBase)),","),
	$myuserid
	);
	if(!mysqli_query($db,$dbquerystring))exit("didn't work");

	//update them
	$dbquerystring = 
	sprintf("UPDATE ".$dbname.".users SET notMyType = '%s',waitingForThemFirstBase = '%s',waitingForThemAllTheWay = '%s',allTheWay = '%s',firstBase = '%s' WHERE id='%s'",
	trim(trim(implode(",",$theirNotMyType)),","),
	trim(trim(implode(",",$theirWaitingForThemFirstBase)),","),
	trim(trim(implode(",",$theirWaitingForThemAllTheWay)),","),
	trim(trim(implode(",",$theirAllTheWay)),","),
	trim(trim(implode(",",$theirFirstBase)),","),
	$theiruserid);
	if(!mysqli_query($db,$dbquerystring))exit("didn't work");

	mysqli_close($db);

	//send back done
	echo "done";

	//send notification email
	
	//only send email if their settings allow for it...
	if(($action=='askfirstbase'&&$theirEmailInterested==1)||
		($action=='authorizefirstbase'&&$theirEmailApproved==1)||
		($action=='askalltheway'&&$theirEmailInterested==1)||
		($action=='authorizealltheway'&&$theirEmailApproved==1)
	)
	{
		sendMatchActionEmail($myFirstName,$theirEmail,$action, $theirVerifyHash);
	}

