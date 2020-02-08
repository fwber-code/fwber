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
?>
<!doctype html>
<html lang="en">
<head>
	<title><?php require_once("_names.php"); echo getSiteName(); ?> - Forgot Password<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
	<?php include("h.php");?>
	<div id="mainbody" align="center">
		<br>
        <br>
        <br>
        <div class="outerOutlineContainer" style="margin:0px;padding:0px;width:500px;">
            <div class="normalContainer" style="margin:0px;padding:0px;">
                <div class="whiteToGray" style="font-size:14pt;margin:0px;padding:8px;border-radius:8px;">
                    <div class="outerOutlineContainer grayToWhite" style="width:50%;font-size:14pt;color:#555;border-color:#999; padding:4px;">
                        Request Password Reset:<br>
                    </div>
                    <br>
                    <div id="requestStatus"></div>
                    <div style="text-align:right;font-size:12pt;margin-right:20%;">
                        <form onsubmit="requestPasswordReset();return false;">
                            Email Address: <input type="text" name="myEmail" id="myEmail" style="margin:4px;"></input><br>
                            <br>
                            <input type="button" class="buttonGray"  name="button" value="Request" style="margin:4px;" onclick="requestPasswordReset();return false;"></input>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>

<?php include("f.php");?>

    <script src="/js/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="/js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        function requestPasswordReset()
        {
            var emailAddress = document.getElementById("myEmail").value;
            $.ajax(
                {
                    type: "GET",
                    url: "_requestPasswordReset",
                    data: "emailAddress="+emailAddress,
                    dataType: "html",
                    success: function(text)
                    {
                        if(text=="done")
                        {
                            $(document.getElementById("requestStatus")).hide();
                            $(document.getElementById("requestStatus")).fadeIn('slow');
                            document.getElementById("requestStatus").innerHTML="If your email is signed up with <?php echo getSiteName();?>, we've sent you a password request link. Please check your email.";

                        }
                        else
                        {
                            $(document.getElementById("requestStatus")).hide();
                            document.getElementById("requestStatus").innerHTML="Something went wrong. Please try again later.<br>Error: " + text;
                            $(document.getElementById("requestStatus")).fadeIn('fast');
                            setTimeout(function()
                            {
                                $(document.getElementById("requestStatus")).fadeOut('slow');
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