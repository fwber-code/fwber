<?php
    session_start();
    include("_debug.php");
    include("_names.php");
    include("_init.php");
    include("_secrets.php");

?>
<!doctype html>
<html lang="en">
<head>
    <title><?php echo getSiteName(); ?> - Account Settings<?php echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
<?php

	//first make sure we are a legit user.
	deleteCookiesIfInvalid();
	
	goHomeIfCookieNotSet();

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    $email = mysqli_escape_string($db,$_SESSION["email"]);

	$dbquerystring = sprintf("SELECT id, emailMatches, emailInterested, emailApproved FROM ".$dbname.".users WHERE email='%s'",$email);
	$dbquery = mysqli_query($db,$dbquerystring);
	$dbresults = mysqli_fetch_array($dbquery);
	
	$emailMatches=0;
	$emailInterested=0;
	$emailApproved=0;
	
	if($dbresults['emailMatches']==1)$emailMatches=1;
	if($dbresults['emailInterested']==1)$emailInterested=1;
	if($dbresults['emailApproved']==1)$emailApproved=1;

	mysqli_close($db);

?>
</head>
<body class="d-flex flex-column h-100">
	<?php include("h.php");?>
	<div id="mainbody" align="center" class="matches">
	<table id="body_table">
		<tr>
			<td id="middle_column" align="center" valign="top" style="width:70%">
                <br>
                <br>
                <br>
                <br>
				<div class="innerOutlineContainer whiteToGray"  style="width:50%;font-size:14pt;margin:0px;padding:8px;border-radius:8px;">
					<div class="" style="width:50%;font-size:14pt;">
					Change your email address:<br>
					</div>
					<br>
					<div style="text-align:right;font-size:12pt;margin-right:20%;">
                        <span style="margin:2px;">Current Email Address: <?php echo $email; ?></span><br><br>
					    <form action="_changeEmail" method="POST" enctype="multipart/form-data" id="changeEmailForm" >
							New Email Address:
                            <input type="text" name="newEmail" id="newEmail" style="margin:4px;"></input><br>
							Verify New Address:
                            <input type="text" name="verifyEmail" id="verifyEmail" style="margin:4px;"></input><br>
							<input type="submit" class="btn btn-outline-secondary my-0 px-3 mx-1" name="submit" value="Save" style="margin:4px;"></input>
					    </form>
					</div>
				</div>

				<br>
				<br>

				<div class="innerOutlineContainer whiteToGray" style="width:50%;font-size:14pt;margin:0px;padding:8px;border-radius:8px;">
					<div class="" style="width:50%;font-size:14pt;">
					Change Email Settings:<br>
					</div>
					<br>
					<div id="emailSettingsStatus"></div>
					<div style="text-align:right;font-size:12pt;margin-right:20%;">
					<form onsubmit="changeEmailSettings();return false;">
                        Email me when a new member is a match.
                        <input type="checkbox" id="emailMatches" style="margin:4px;" <?php if($emailMatches==1)echo 'checked="checked";' ?>></input><br>
							<br>
                        Email me when someone is interested in me.
                        <input type="checkbox" id="emailInterested"style="margin:4px;" <?php if($emailInterested==1)echo 'checked="checked";' ?>></input><br>
                        <br>
                        Email me when someone has approved my interest.
                        <input type="checkbox" id="emailApproved"style="margin:4px;" <?php if($emailApproved==1)echo 'checked="checked";' ?>></input><br>
                        <br>
                        <input type="button" class="btn btn-outline-secondary my-0 px-3 mx-1"  name="button" value="Save" style="margin:4px;" onclick="changeEmailSettings();return false;"></input>
					</form>
					</div>
				</div>

				<br>
				<br>

				<div class="innerOutlineContainer whiteToGray" style="width:50%;font-size:14pt;margin:0px;padding:8px;border-radius:8px;">
					<div class="" style="width:50%;font-size:14pt;">
					Change your password:<br>
					</div>
					<br>
					<div style="text-align:right;font-size:12pt;margin-right:20%;">
					<form action="_changePassword" method="POST" enctype="multipart/form-data" id="changePasswordForm" >
                        Old Password:
                        <input type="password" name="oldPass" id="oldPass" style="margin:4px;"></input><br>
                        <br>
                        New Password:
                        <input type="password" name="newPass" id="newPass" style="margin:4px;" ></input><br>
                        Verify New Password:
                        <input type="password" name="verifyPass" id="verifyPass" style="margin:4px;"></input><br><br>
                        <input type="submit" class="btn btn-outline-secondary my-0 px-3 mx-1" name="submit" value="Save" style="margin:4px;"></input>
					</form>
					</div>
				</div>

                <br>
                <br>

				<div class="innerOutlineContainer whiteToDarkGray" style="width:50%; font-size:14pt;margin:0px;padding:8px;border-radius:8px;">
					<div class="" style="width:50%;font-size:14pt;">
					    Delete Account:<br>
					</div>
					<br>
					<div style="width:100%;text-align:right;font-size:12pt;margin-right:20%;color:#000;">
					<form action="_deleteAccount" method="POST" enctype="multipart/form-data" id="deleteAccountForm" >
                        Password:
                        <input type="password" name="myPass" id="myPass" style="margin:4px;" ></input><br>
                        Verify Password:
                        <input type="password" name="myPassAgain" id="myPassAgain" style="margin:4px;"></input><br><br>
                        Type "goodbye" here:
                        <input type="text" name="goodbyeCheck" id="goodbyeCheck" style="margin:4px;"></input><br>
                        <input type="hidden" name="goodbye" id="goodbye" value="goodbye"></input><br>
                        <input type="submit" class="btn btn-outline-secondary my-0 px-3 mx-1" name="submit"  value="Delete"style="margin:4px;"></input>
					</form>
					</div>
				</div>

		</td>
		</tr>
		</table>
	</div>
    <?php include("f.php");?>

    <script src="/js/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="/js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>

    <script type="text/javascript">

        function changeEmailSettings()
        {
            var emailMatches = document.getElementById("emailMatches").checked;
            var emailInterested = document.getElementById("emailInterested").checked;
            var emailApproved = document.getElementById("emailApproved").checked;

            $.ajax(
                {
                    type: "GET",
                    url: "_emailSettings",
                    data: "emailMatches="+emailMatches+"&emailInterested="+emailInterested+"&emailApproved="+emailApproved,
                    dataType: "html",
                    success: function(text)
                    {
                        if(text=="done")
                        {
                            document.getElementById("emailSettingsStatus").innerHTML="Settings Changed!";
                            $(document.getElementById("emailSettingsStatus")).show();
                            setTimeout(function()
                            {
                                $(document.getElementById("emailSettingsStatus")).fadeOut('fast');
                            },10000);
                        }
                        else
                        {
                            document.getElementById("emailSettingsStatus").innerHTML="Something went wrong. Please try again later.<br>Error: " + text;
                            $(document.getElementById("emailSettingsStatus")).show();
                            setTimeout(function()
                            {
                                $(document.getElementById("emailSettingsStatus")).fadeOut('fast');
                            },10000);
                        }
                    }
                });
        }
    </script>

    <script src="/js/jquery-validate/jquery.validate.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $.validator.setDefaults({});
        $().ready
        (
            function()
            {
                $("#changePasswordForm").validate
                ({
                    errorElement:"div",
                    rules:
                        {
                            oldPass:
                                {
                                    required:true
                                },
                            newPass:
                                {
                                    required:true,
                                    minlength:5
                                },
                            verifyPass:
                                {
                                    equalTo: "#newPass"
                                }
                        }
                    ,
                    messages:
                        {
                        }
                });
                // validate the comment form when it is submitted
                $("#changeEmailForm").validate
                ({
                    errorElement:"div",
                    rules:
                        {
                            newEmail:
                                {
                                    email:true,
                                    required:true
                                },
                            verifyEmail:
                                {
                                    equalTo: "#newEmail"
                                }
                        }
                    ,
                    messages:
                        {
                        }
                });

                $("#deleteAccountForm").validate
                ({
                    errorElement:"div",
                    rules:
                        {
                            myPass:
                                {
                                    required:true
                                },
                            myPassAgain:
                                {
                                    equalTo: "#myPassAgain"
                                },
                            goodbyeCheck:
                                {
                                    equalTo: "#goodbye"
                                }
                        }
                    ,
                    messages:
                        {
                        }
                });
            }
        );
    </script>
</body>
</html>