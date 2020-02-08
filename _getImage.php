<?php 

	session_start();

	require_once("_init.php");
	require_once("_profileVars.php");
	require_once("_secrets.php");
	//require_once("_globals.php");
	
	//first make sure we are a legit user.
	//full auth for actions
	if(validateSessionOrCookiesReturnLoggedIn()==false){header('Location: '.getSiteURL());return;}
	
	goHomeIfCookieNotSet();
	
	//make sure we've got an image.
	if(!isset($_GET['img']) || !isset($_GET['id']) || empty($_GET['img']) || empty($_GET['id']))exit('no image');

	$db = mysqli_connect($dburl,$dbuser,$dbpass);
	if(!$db)exit(mysqli_connect_error());
	
	$email = mysqli_escape_string($db,$_SESSION["email"]);	

	$dbquerystring = sprintf("SELECT id, notMyType, waitingForThemPrivate, private FROM ".$dbname.".users WHERE email='%s'",$email);
	$dbquery = mysqli_query($db,$dbquerystring);
	$dbresults = mysqli_fetch_array($dbquery);
	
	$myuserid=$dbresults['id'];
	
	$myNotMyType=explode(",",trim(trim($dbresults['notMyType']),","));
	$myWaitingForThemPrivate=explode(",",trim(trim($dbresults['waitingForThemPrivate']),","));
	$myPrivate=explode(",",trim(trim($dbresults['private']),","));
	
	mysqli_free_result($dbquery);

	//get their user id
	$img=mysqli_escape_string($db,$_GET['img']);
	$theiruserid=mysqli_escape_string($db,$_GET['id']);
	
	//make sure this image id is in their photos
	$dbquerystring = sprintf("SELECT privatePics, publicPics FROM ".$dbname.".users WHERE id='%s'",$theiruserid);
	$dbquery = mysqli_query($db,$dbquerystring);
	$dbresults = mysqli_fetch_array($dbquery);
	
	$privatePics=explode(",",trim(trim($dbresults['privatePics']),","));
	$publicPics=explode(",",trim(trim($dbresults['publicPics']),","));
	

		//it's a single image.
	
		//figure out which database list it's in.
		$foundWhere="";
		foreach($publicPics as $s)if($s==$img)$foundWhere="public";
		
		if($foundWhere=="")foreach($privatePics as $s)if($s==$img)$foundWhere="private";
		
		if($foundWhere=="")exit("Image not found.");
	//}

	//see if i have access to that database list
	$haveAccess=false;
		
	if($foundWhere=="public")
	{
		$haveAccess=true;
	

		header("Content-type: image/jpeg");
		readfile("./fwberImageStore/".$img);
		
	}
	
	if($foundWhere=="private")
	{
		//do i have access to private?
		
		//if they are ME
		if($theiruserid==$myuserid)$haveAccess=true;
		
		//if they are in my private
		foreach($myPrivate as $s)if($s==$theiruserid)$haveAccess=true;
		
		if($haveAccess==false)
		{
			header("Content-type: image/jpeg");
			readfile("./fwberImageStore/".$img."_b");
		}
		else
		{
			header("Content-type: image/jpeg");
			readfile("./fwberImageStore/".$img);
		}
	}
	
	if($haveAccess==false)exit("Unauthorized");



	

	
	
