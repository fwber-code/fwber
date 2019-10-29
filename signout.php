<?php
	session_start();
	
	require_once("_names.php");
	
	//delete cookies
	setcookie("email","",time()-1000,'/',".".getSiteDomain());
	setcookie("token","",time()-1000,'/',".".getSiteDomain());
	
	session_destroy();

	header('Location: '.getSiteURL());
    exit();
?>