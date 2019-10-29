<?php
session_start();

include("_names.php");
include("_debug.php");

?>
<!doctype html>
<html lang="en">
<head>
    <title><?php echo getSiteName(); ?> - Upload Pictures<?php echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
	<?php include("h.php");?>
    <div class="col-sm-12 my-auto text-center">
        <br>
        <br>
        <br>
        <br>

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
            .jumbotron {
                padding-top: 3rem;
                padding-bottom: 3rem;
                margin-bottom: 0;
                background-color: #fff;
            }
            @media (min-width: 768px) {
                .jumbotron {
                    padding-top: 6rem;
                    padding-bottom: 6rem;
                }
            }

            .jumbotron p:last-child {
                margin-bottom: 0;
            }

            .jumbotron-heading {
                font-weight: 300;
            }

            .jumbotron .container {
                max-width: 40rem;
            }

            footer {
                padding-top: 3rem;
                padding-bottom: 3rem;
            }

            footer p {
                margin-bottom: .25rem;
            }

        </style>


        <h1 class="h3 mb-3 font-weight-normal text-center"> Manage Pictures</h1>


        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading">Manage Pictures</h1>
                <p class="lead text-muted">
                    Other sites are overridden by bots and fake profiles with photoshopped pictures.<br>
                    <?php echo getSiteName(); ?> gives everyone an equal chance: By using avatars, it evens the playing field for everyone.<br>
                </p>
            </div>
        </section>

        <br>


            <div class="album py-5 bg-light">
                <div class="container">

                    <div class="row">

                        <div style="border:#000 1px solid; border-radius:8px;margin:0px;">
                            Your Public Pictures
                            <div class="smallText" style="color:#222;font-size:11pt">
                                These are public and shown to anyone who matches with you.<br>
                                We recommend uploading face pictures here.<br>
                                That way if you run into someone you know they will only know you signed up, not what you are into.<br>
                            </div>
                            <br>
                            <div id="picsFirstBase">
                            </div>
<?php

    include("_debug.php");
    include("_secrets.php");

		//connect to database, find any firstbase pictures for me.
		$db = mysqli_connect($dburl,$dbuser,$dbpass);
		if(!$db)exit(mysqli_connect_error());

		$email = mysqli_escape_string($db,$_SESSION["email"]);	
	
		//get my userid
		$dbquerystring = sprintf("SELECT id, firstBasePics, allTheWayPics FROM ".$dbname.".users WHERE email='%s'",$email);
		$dbquery = mysqli_query($db,$dbquerystring);
		$dbresults = mysqli_fetch_array($dbquery);
		
		$userid=$dbresults['id'];
		
		$firstBasePics=explode(",",trim(trim($dbresults['firstBasePics']),","));
		$allTheWayPics=explode(",",trim(trim($dbresults['allTheWayPics']),","));
		
		mysqli_free_result($dbquery);
		
		//output them here with a delete button, with a php get image script.
		foreach($firstBasePics as $s)
		{
			if($s=="")continue;
?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                       <div class="imageUploadList" align="center" width="100%" height="225" id="imgdiv<?php echo $s; ?>">
                        <a href="/_getImage?img=<?php echo $s; ?>&id=<?php echo $userid; ?>" target="_blank"><img src="/_getImage?img=<?php echo $s; ?>&id=<?php echo $userid; ?>" id="pic<?php echo $s; ?>" class="imageUploadImage"></a>
                        </div>
                        <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-secondary buttonDarkRed" name="delete" id="deleteButton<?php echo $s; ?>" onclick="ajaxDelete('<?php echo $s; ?>');">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



<?php 
		}
?>

            <table>
            <tr>
            <td>
                <div style="font-size:14pt;">
                    Upload File
                </div>
            </td>
            <td>
            <div>




                <form action="_imageUpload" method="post" name="firstBaseForm" id="firstBaseForm" enctype="multipart/form-data">

                    <input type="file" name="filedata" id="filedata" onchange="ajaxUpload(this.form,'firstBase');return false;"/>
                    <?php
                    //<!--[if IE]>
                    //<button onclick="ajaxUpload(this.form,'firstBase');return false;">
                    //Upload Image
                    //</button>
                    //<![endif]-->
                    //<noscript>
                    //<input type="submit" name="submit" value="Upload Image" />
                    //</noscript>
                    ?>
                </form>

                <div style="font-size:8pt;font-style:italic;">
                File Types: .jpg, .png, 50MB maximum.
                </div>
            </div>
            </td>
            </tr>
            </table>
            <br>
        </div>


            <br>
            <br>
			<div style="border:#000 1px solid; border-radius:8px;margin:0px;">
				    Your Private Pictures
					<div class="smallText" style="color:#222;font-size:11pt">
                        These are private pictures. Only members you have authorized will see them.<br>
                        We recommend uploading nude pictures here.<br>
                        Nobody will be able to see your interests or private pictures until you let them.<br>
					</div>
            </div>

            <table>
                <tr>
                    <td>
                        <div id="picsAllTheWay">
                        </div>
                    </td>
<?php
		//output them here with a delete button, with a php get image script.
		foreach($allTheWayPics as $s)
		{
			if($s=="")continue;
?>
			<td>
			<div class="imageUploadList" align="center" id="imgdiv<?php echo $s; ?>">
			<a href="/_getImage?img=<?php echo $s; ?>&id=<?php echo $userid; ?>" target="_blank"><img src="/_getImage?img=<?php echo $s; ?>&id=<?php echo $userid; ?>" id="pic<?php echo $s; ?>" class="imageUploadImage"></a>
			<br>
			<input type="button" class="buttonDarkRed" name="delete" id="deleteButton<?php echo $s; ?>" value="Delete" style="font-size:8pt;" onclick="ajaxDelete('<?php echo $s; ?>');"/>
			</div>
			</td>
<?php
		}
		//handle delete button.
?>
                </tr>
            </table>
            <table>
            <tr>
            <td>
                <div style="font-size:14pt;font-weight:bold;">
                    Select A File To Upload:
                </div>
            </td>
            <td>
            <div>
                <form action="_imageUpload" method="post" name="allTheWayForm" id="allTheWayForm" enctype="multipart/form-data">
                    <input type="hidden" name="filename" value="filename" />
                    <input type="file" name="filename" id="allTheWayFileName" value="filename" onchange="ajaxUpload(this.form,'allTheWay');return false;"/>
                </form>
                <div style="font-size:8pt;font-style:italic;">
                    File Types: .jpg, .png, 50MB maximum.
                </div>
            </div>
            </td>
            </tr>
            </table>
            <br>

    <?php include("f.php");?>

    <script type="text/javascript" src="js/ajaxupload.js"></script>

    <style type="text/css">
        iframe #ajax-temp
        {
            display:none;
        }

        img
        {
            border: 0px;
        }
        #upload_area
        {
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
    </style>

    <script src="/js/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="/js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>

    <script type="text/javascript">

        function ajaxDelete(imgID)
        {
            var e = document.getElementById("deleteButton"+imgID);

            $(e).hide("explode","",200);

            $.ajax(
                {
                    type: "GET",
                    url: "_deleteImage",
                    data: "img=" + escape(imgID),
                    dataType: "html",
                    success: function(text)
                    {
                        if(text=="done")
                        {
                            $(document.getElementById("imgdiv"+imgID)).hide("explode","",1000);
                        }
                        else
                        {
                            $(document.getElementById("img"+imgID)).hide("puff","",1000);
                            document.getElementById("imgdiv"+imgID).innerHTML="Something went wrong. Please try again later.<br>Error: " + text;
                        }
                    }
                });
        }
    </script>
</body>
</html>