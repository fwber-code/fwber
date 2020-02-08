<?php
/*
    Copyright 2020 FWBer.com

    This file is part of FWBer.

    FWBer is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    FWBer is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero Public License for more details.

    You should have received a copy of the GNU Affero Public License
    along with FWBer.  If not, see <https://www.gnu.org/licenses/>.
*/

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
	validateSessionOrCookiesReturnLoggedIn();
	
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
	<div class="col-sm-12 my-auto text-center">

                <br>
                <br>
                <br>
                <br>
				<div class="card mb-5 shadow-sm p-5" style="display:inline-block;">
					    <form action="_changeEmail" method="POST" enctype="multipart/form-data" id="changeEmailForm" >
                            <fieldset style="text-align:center;border:none;">
                                <h1 class="h3 mb-3 font-weight-normal text-center"> Change Email Address</h1>
                                <?php echo $email; ?>
                                <br>
                                <br>

                                <label for="newEmail" class="sr-only">New Email Address:</label>
                                <input type="text" name="newEmail" id="newEmail" style="margin:4px;" placeholder="New Email Address"><br>

                                <label for="verifyEmail" class="sr-only">Verify New Address:</label>
                                <input type="text" name="verifyEmail" id="verifyEmail" style="margin:4px;" placeholder="Verify New Address"><br>

                                <br>
                                <input type="submit" class="btn btn-sm btn-primary my-0 px-3 mx-1" name="submit" value="Save">
                            </fieldset>
					    </form>

				</div>

				<br>


                <div class="card mb-5 shadow-sm p-5" style="display:inline-block;">

					<form onsubmit="changeEmailSettings();return false;">
                        <fieldset style="text-align:center;border:none;">
                            <h1 class="h3 mb-3 font-weight-normal text-center"> Change Email Settings</h1>
                            <table style="width:100%;">
                                <tbody>
                                <tr>
                                    <td style="width:0%;"></td>
                                    <td style="text-align:left;">
                            <label class="checkbox text-left d-sm-inline-block mb-0" for="emailMatches"><input type="checkbox" onclick="toggle(this)" id="emailMatches" style="margin:4px;" <?php if($emailMatches==1)echo 'checked="checked";' ?>>Email me when a new member is a match</label>
                            <br>
                            <label class="checkbox text-left d-sm-inline-block mb-0" for="emailInterested"><input type="checkbox" onclick="toggle(this)" id="emailInterested"style="margin:4px;" <?php if($emailInterested==1)echo 'checked="checked";' ?>>Email me when someone is interested in me</label>
                            <br>
                            <label class="checkbox text-left d-sm-inline-block mb-0" for="emailApproved"><input type="checkbox" onclick="toggle(this)" id="emailApproved"style="margin:4px;" <?php if($emailApproved==1)echo 'checked="checked";' ?>>Email me when someone has approved my interest</label>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <br>
                            <input type="button" class="btn btn-sm btn-primary my-0 px-3 mx-1" name="button" value="Save" onclick="changeEmailSettings();return false;">
                        </fieldset>
					</form>

				</div>

				<br>


                <div class="card mb-5 shadow-sm p-5" style="display:inline-block;">
					<form action="_changePassword" method="POST" enctype="multipart/form-data" id="changePasswordForm" >
                        <fieldset style="text-align:center;border:none;">
                            <h1 class="h3 mb-3 font-weight-normal text-center"> Change Password</h1>

                            <label for="oldPass" class="sr-only">Old Password:</label>
                            <input type="password" name="oldPass" id="oldPass" style="margin:4px;" placeholder="Old Password"><br>

                            <label for="newPass" class="sr-only">New Password:</label>
                            <input type="password" name="newPass" id="newPass" style="margin:4px;" placeholder="New Password"><br>

                            <label for="verifyPass" class="sr-only">Verify New Password:</label>
                            <input type="password" name="verifyPass" id="verifyPass" style="margin:4px;" placeholder="Verify New Password"><br>
                            <br>
                            <input type="submit" class="btn btn-sm btn-primary my-0 px-3 mx-1" name="submit" value="Save">
                        </fieldset>
					</form>

				</div>

                <br>


                <div class="card mb-5 shadow-sm p-5" style="display:inline-block;">

					<form action="_deleteAccount" method="POST" enctype="multipart/form-data" id="deleteAccountForm" >
                        <fieldset style="text-align:center;border:none;">
                            <h1 class="h3 mb-3 font-weight-normal text-center"> Delete Account</h1>

                            <label for="myPass" class="sr-only">Password:</label>
                            <input type="password" name="myPass" id="myPass" style="margin:4px;" placeholder="Password"><br>

                            <label for="myPassAgain" class="sr-only">Verify Password:</label>
                            <input type="password" name="myPassAgain" id="myPassAgain" style="margin:4px;" placeholder="Verify Password"><br>

                            <label for="goodbyeCheck" class="sr-only">Type "goodbye" here:</label>
                            <input type="text" name="goodbyeCheck" id="goodbyeCheck" style="margin:4px;" placeholder="Type 'goodbye' here"><br>
                            <input type="hidden" name="goodbye" id="goodbye" value="goodbye"><br>

                            <input type="submit" class="btn btn-sm btn-danger my-0 px-3 mx-1" name="submit" value="Delete">
                        </fieldset>
					</form>
				</div>

	</div>
    <?php include("f.php");?>

    <script src="/js/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="/js/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>

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

    <script src="/js/jquery-validation-1.19.1/dist/jquery.validate.min.js" type="text/javascript"></script>

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