<?php 

	session_start();

	require_once("_init.php");
	require_once("_profileVars.php");
	require_once("_secrets.php");
	//require_once("_globals.php");
	
	//first make sure we are a legit user.
	//full auth for actions
	if(deleteCookiesIfInvalid()==false){header('Location: '.getSiteURL());return;}
	
	goHomeIfCookieNotSet();
	
	//make sure we've got an image.
	if(!isset($_GET['img']) || !isset($_GET['id']) || empty($_GET['img']) || empty($_GET['id']))exit('no image');

	$db = mysqli_connect($dburl,$dbuser,$dbpass);
	if(!$db)exit(mysqli_connect_error());
	
	$email = mysqli_escape_string($db,$_SESSION["email"]);	

	$dbquerystring = sprintf("SELECT id, notMyType, waitingForThemFirstBase, waitingForThemAllTheWay, allTheWay, firstBase FROM ".$dbname.".users WHERE email='%s'",$email);
	$dbquery = mysqli_query($db,$dbquerystring);
	$dbresults = mysqli_fetch_array($dbquery);
	
	$myuserid=$dbresults['id'];
	
	$myNotMyType=explode(",",trim(trim($dbresults['notMyType']),","));
	$myWaitingForThemFirstBase=explode(",",trim(trim($dbresults['waitingForThemFirstBase']),","));
	$myWaitingForThemAllTheWay=explode(",",trim(trim($dbresults['waitingForThemAllTheWay']),","));
	$myAllTheWay=explode(",",trim(trim($dbresults['allTheWay']),","));
	$myFirstBase=explode(",",trim(trim($dbresults['firstBase']),","));
	
	mysqli_free_result($dbquery);

	//get their user id
	$img=mysqli_escape_string($db,$_GET['img']);
	$theiruserid=mysqli_escape_string($db,$_GET['id']);
	
	//make sure this image id is in their photos
	$dbquerystring = sprintf("SELECT allTheWayPics, firstBasePics FROM ".$dbname.".users WHERE id='%s'",$theiruserid);
	$dbquery = mysqli_query($db,$dbquerystring);
	$dbresults = mysqli_fetch_array($dbquery);
	
	$allTheWayPics=explode(",",trim(trim($dbresults['allTheWayPics']),","));
	$firstBasePics=explode(",",trim(trim($dbresults['firstBasePics']),","));
	
	//if($img=="firstbase")$foundWhere=="firstbase";
	//else
	//if($img=="alltheway")$foundWhere=="alltheway";
	//else
	//{
		//it's a single image.
	
		//figure out which database list it's in.
		$foundWhere="";
		foreach($firstBasePics as $s)if($s==$img)$foundWhere="firstbase";
		
		if($foundWhere=="")foreach($allTheWayPics as $s)if($s==$img)$foundWhere="alltheway";
		
		if($foundWhere=="")exit("Image not found.");
	//}

	//see if i have access to that database list
	$haveAccess=false;
		
	if($foundWhere=="firstbase")
	{
		//do i have access to their first base?
		
		//if they are ME
		if($theiruserid==$myuserid)$haveAccess=true;
	
		//if they are in my alltheway
		foreach($myAllTheWay as $s)if($s==$theiruserid)$haveAccess=true;
		
		//if they are in my firstbase
		foreach($myFirstBase as $s)if($s==$theiruserid)$haveAccess=true;
		
		//if they are in my waitingforthemalltheway
		foreach($myWaitingForThemAllTheWay as $s)if($s==$theiruserid)$haveAccess=true;

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
	
	if($foundWhere=="alltheway")
	{
		//do i have access to all the way?
		
		//if they are ME
		if($theiruserid==$myuserid)$haveAccess=true;
		
		//if they are in my alltheway
		foreach($myAllTheWay as $s)if($s==$theiruserid)$haveAccess=true;
		
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

	//if($img=="firstbase")
	//{
		//export all firstbase pics
	
	//}
	//else
	//if($img=="alltheway")
	//{
		//export all alltheway pics
	
	//}
	//else
	//{
	
		//header("Content-type: image/jpeg");
		//readfile("./fwberImageStore/".$img);
	
		/*
		//return header.
		$fh = fopen("./fwberImageStore/".$img,"rb");
		if($fh==NULL)exit("Could not open image.");
		rewind($fh);
		header("Content-Type: image/jpeg");
		header("Content-Length: ".filesize($fh));
		fpassthru($fh);
		fclose($fh);
		exit();*/
	//}
	

	

	
	
