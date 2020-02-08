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
	<title><?php require_once("_names.php"); echo getSiteName(); ?> - Contact Us<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
	<?php include("h.php");?>
        <div class="col-sm-12 my-auto text-center">
            <form class="form-signin" action="_contact" method="POST" enctype="multipart/form-data"
                  name="contactFormName" id="contactFormID">
                <fieldset style="text-align:left;">

                    <h1 class="h3 mb-3 font-weight-normal text-center"> Contact Us</h1>
                    <div style="font-size:12pt;">
                        If something is not working please let us know.<br>
                        We welcome hearing suggestions or new categories we can add.<br>
                    </div>
                    <br>
                    <br>
                    <select name="subject" id="subject" class="mb-2">
                        <option value="help">Help</option>
                        <option value="suggestion">Suggestion</option>
                        <option value="bugReport">Bug Report</option>
                        <option value="other">Other</option>
                    </select>
                    <br>
                    <label for="emailAddress" class="sr-only">Your email address (optional)</label>
                    <input type="email" id="emailAddress" class="form-control" placeholder="Your email address (optional)">
                    <label for="message" class="sr-only">Your message</label>
                    <textarea name="message" id="message" class="required" cols="60" rows="10" style="" placeholder="Your message"></textarea><br>
                    <br>
                    <div class="text-center">
                        <button class="btn btn-lg btn-primary" type="submit" name="contactButtonName"
                                id="contactButtonID">Send
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>

<?php include("f.php");?>

<script src="/js/jquery-3.4.1.min.js" type="text/javascript"></script>
<script src="/js/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>

<script src="/js/jquery-validation-1.19.1/dist/jquery.validate.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $.validator.setDefaults({});
    $().ready
    (
        function()
        {
            // validate the comment form when it is submitted
            $("#contactFormID").validate
            ({
                errorElement:"div",

                rules:
                    {
                        message:
                            {
                                minlength:2,
                                required:true
                            }
                    }
                ,
                messages:
                {
                    message:"You must write a message."
                }
            });
        }
    );
</script>
</body>
</html>