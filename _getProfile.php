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
	function getProfile($g, $whichBase)
	{
		include("_globals.php");
		
		$genderString = getGenderString($g['gender']);
		
		$distanceAmt = "";
		$distanceUnit = "";
		if($isoCountryCode=="US")
		{
			$distanceAmt = $g['calculatedDistance'];
			$distanceUnit="Miles";
		}
		else 
		{
			$distanceAmt = round($g['calculatedDistance']*0.62, 1);
			$distanceUnit="Kilometers";
		}

		
?>

		<?php if($whichBase=="public"){ ?>
			<div id="<?php echo $whichBase.$g['id']; ?>" class="innerOutlineContainer" width="90%" style="width:90%">
		<?php }else if($whichBase=="waitingforprivate"){ ?>
			<div id="<?php echo $whichBase.$g['id']; ?>" class="innerOutlineContainer" width="90%" style="width:90%">
		<?php }else if($whichBase=="private"){ ?>
			<div id="<?php echo $whichBase.$g['id']; ?>" class="innerOutlineContainer" width="90%" style="width:90%">
		<?php }else if($whichBase=="authforprivate"){ ?>
			<div id="<?php echo $whichBase.$g['id']; ?>" class="innerOutlineContainer" width="90%" style="width:90%">
		<?php }else if($whichBase=="notmytype"){ ?>
			<div id="<?php echo $whichBase.$g['id']; ?>" class="innerOutlineContainer" width="90%" style="width:90%">
		<?php } ?>
        
				
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td style="border-right:#aaa 1px dotted; ">
							<table width="100%" >
							<tr>
								<td >
<?php

        //ftm.body_curvy.skin_whiteTan.breastSize_none.bodyHair_none.facialHair_none.hairLength_buzzed.hairColor_lightBlonde.png

        $avatarName = "";

        //if($g['gender']=="male")$avatarName.="male.";
        //else if($g['gender']=="female")$avatarName.="female.";
        //else if($g['gender']=="mtf")$avatarName.="mtf.";
        //else if($g['gender']=="ftm")$avatarName.="ftm.";

        $bodyFolder = "";

        if($g['body']=="tiny"){$avatarName.="body_small.";$bodyFolder="body0";}
        else if($g['body']=="slim"){$avatarName.="body_skinny.";$bodyFolder="body1";}
        else if($g['body']=="average"){$avatarName.="body_average.";$bodyFolder="body2";}
        else if($g['body']=="muscular"){$avatarName.="body_athletic.";$bodyFolder="body3";}
        else if($g['body']=="curvy"){$avatarName.="body_curvy.";$bodyFolder="body4";}
        else if($g['body']=="thick"){$avatarName.="body_thick.";$bodyFolder="body5";}
        else if($g['body']=="bbw"){$avatarName.="body_bbw.";$bodyFolder="body6";}

        //other light-skinned
        //other medium-skinned
        //other dark-skinned

        //if($g['ethnicity']=="white")$avatarName.="skin_whitePale.";
        //if($g['ethnicity']=="white")$avatarName.="skin_whitePink.";
        if($g['ethnicity']=="white")$avatarName.="skin_whiteTan.";
        //if($g['ethnicity']=="asian")$avatarName.="skin_asianLight.";
        else if($g['ethnicity']=="asian")$avatarName.="skin_asianDark.";
        else if($g['ethnicity']=="latino")$avatarName.="skin_latino.";
        else if($g['ethnicity']=="indian")$avatarName.="skin_indian.";
        else if($g['ethnicity']=="black")$avatarName.="skin_blackLight.";
        //if($g['ethnicity']=="black")$avatarName.="skin_blackDark.";
        else if($g['ethnicity']=="other")$avatarName.="skin_asianDark.";

        if($g['gender']=="male")$avatarName.="breastSize_none.";
        else if($g['gender']=="cdmtf")$avatarName.="breastSize_none.";
        else if($g['gender']=="mm")$avatarName.="breastSize_none.";
        else if($g['breastSize']=="tiny")$avatarName.="breastSize_none.";
        else if($g['breastSize']=="small")$avatarName.="breastSize_small.";
        else if($g['breastSize']=="average")$avatarName.="breastSize_medium.";
        else if($g['breastSize']=="large")$avatarName.="breastSize_large.";
        else if($g['breastSize']=="huge")$avatarName.="breastSize_huge.";


        if($g['gender']=="female")$avatarName.="penisSize_none.";
        //else if($g['gender']=="mtf")$avatarName.="penisSize_none.";
        //else if($g['gender']=="ftm")$avatarName.="penisSize_none.";
        else if($g['gender']=="ff")$avatarName.="penisSize_none.";
        else if($g['penisSize']=="tiny")$avatarName.="penisSize_small.";
        else if($g['penisSize']=="skinny")$avatarName.="penisSize_skinny.";
        else if($g['penisSize']=="average")$avatarName.="penisSize_average.";
        else if($g['penisSize']=="thick")$avatarName.="penisSize_large.";
        else if($g['penisSize']=="huge")$avatarName.="penisSize_huge.";

        if($g['gender']=="female")$avatarName.="bodyHair_none.";
        else if($g['gender']=="mtf")$avatarName.="bodyHair_none.";
        //else if($g['gender']=="ftm")$avatarName.="bodyHair_none.";
        else if($g['gender']=="ff")$avatarName.="bodyHair_none.";
        //if($g['bodyHair']=="smooth")$avatarName.="bodyHair_none.";
        else if($g['bodyHair']=="smooth")$avatarName.="bodyHair_little.";
        else if($g['bodyHair']=="average")$avatarName.="bodyHair_some.";
        else if($g['bodyHair']=="hairy")$avatarName.="bodyHair_lots.";

        //if($g['']=="")
        $avatarName.="facialHair_none.";
        //if($g['']=="")$avatarName.="facialHair_some.";

        if($g['hairLength']=="bald")$avatarName.="hairLength_none.";
        //if($g['hairLength']=="")$avatarName.="hairLength_balding.";
        //if($g['hairLength']=="")$avatarName.="hairLength_buzzed.";
        else if($g['hairLength']=="short")$avatarName.="hairLength_buzzed.";
        else if($g['hairLength']=="medium")$avatarName.="hairLength_medium.";
        else if($g['hairLength']=="long")$avatarName.="hairLength_long.";

        if($g['hairColor']=="light")$avatarName.="hairColor_lightBlonde.";
        //if($g['hairColor']=="light")$avatarName.="hairColor_darkBlonde.";
        //if($g['hairColor']=="light")$avatarName.="hairColor_lightBrown.";
        else if($g['hairColor']=="medium")$avatarName.="hairColor_mediumBrown.";
        //else if($g['hairColor']=="dark")$avatarName.="hairColor_darkBrown.";
        else if($g['hairColor']=="dark")$avatarName.="hairColor_black.";
        else if($g['hairColor']=="red")$avatarName.="hairColor_red.";
        else if($g['hairColor']=="gray")$avatarName.="hairColor_gray.";
        else if($g['hairColor']=="other")$avatarName.="hairColor_black.";

        $avatarName.="png";

        //echo $avatarName;

        $femaleName = $avatarName;
        $femaleName = str_replace("penisSize_small","penisSize_none",$femaleName);
        $femaleName = str_replace("penisSize_skinny","penisSize_none",$femaleName);
        $femaleName = str_replace("penisSize_average","penisSize_none",$femaleName);
        $femaleName = str_replace("penisSize_large","penisSize_none",$femaleName);
        $femaleName = str_replace("penisSize_huge","penisSize_none",$femaleName);

        $femaleName = str_replace("bodyHair_little","bodyHair_none",$femaleName);
        $femaleName = str_replace("bodyHair_some","bodyHair_none",$femaleName);
        $femaleName = str_replace("bodyHair_lots","bodyHair_none",$femaleName);

        $maleName = $avatarName;
        $maleName = str_replace("breastSize_small","breastSize_none",$maleName);
        $maleName = str_replace("breastSize_medium","breastSize_none",$maleName);
        $maleName = str_replace("breastSize_large","breastSize_none",$maleName);
        $maleName = str_replace("breastSize_huge","breastSize_none",$maleName);

        if($g['gender']=="male")
        {
            echo '<div align="center" class="blueToWhite" style="padding:0px;margin:6px;">';
            echo '<img src="/avatars/male/'.$bodyFolder.'/male.'.$avatarName.'"  border="0" class="avatar" />';
            echo '</div>';
        }
        else if($g['gender']=="female")
        {
            echo '<div align="center" class="pinkToWhite" style="padding:0px;margin:6px;">';
            echo '<img src="/avatars/female/'.$bodyFolder.'/female.'.$avatarName.'"  border="0" class="avatar" />';
            echo '</div>';
        }
        else if($g['gender']=="mtf" || $g['gender']=="cdmtf")
        {
            echo '<div align="center" class="purpleToWhite" style="padding:0px;margin:6px;">';
            echo '<img src="/avatars/mtf/'.$bodyFolder.'/mtf.'.$avatarName.'"  border="0" class="avatar" />';
            echo '</div>';

        }
        else if($g['gender']=="ftm" || $g['gender']=="cdftm")
        {
            echo '<div align="center" class="purpleToWhite" style="padding:0px;margin:6px;">';
            echo '<img src="/avatars/male/'.$bodyFolder.'/male.'.$avatarName.'"  border="0" class="avatar" />';
            echo '</div>';
        }
        else if($g['gender']=="mf")
        {


            echo '<div align="center" class="purpleToWhite" style="padding:0px;margin:6px;">';
            echo '<table><tr><td>';
            echo '<img src="/avatars/male/'.$bodyFolder.'/male.'.$maleName.'"  border="0" class="avatar" style="margin-right:-20px;"/>';
            echo '</td><td>';
            echo '<img src="/avatars/female/'.$bodyFolder.'/female.'.$femaleName.'"  border="0" class="avatar" style="margin-left:-20px;"/>';
            echo '</td></tr></table>';
            echo '</div>';
        }
        else if($g['gender']=="mm")
        {
            echo '<div align="center" class="blueToWhite" style="padding:0px;margin:6px;">';
            echo '<table><tr><td>';
            echo '<img src="/avatars/male/'.$bodyFolder.'/male.'.$avatarName.'"  border="0" class="avatar" style="margin-right:-20px;"/>';
            echo '</td><td>';
            echo '<img src="/avatars/male/'.$bodyFolder.'/male.'.$avatarName.'"  border="0" class="avatar" style="margin-left:-20px;"/>';
            echo '</td></tr></table>';
            echo '</div>';
        }
        else if($g['gender']=="ff")
        {
            echo '<div align="center" class="pinkToWhite" style="padding:0px;margin:6px;">';
            echo '<table><tr><td>';
            echo '<img src="/avatars/female/'.$bodyFolder.'/female.'.$avatarName.'"  border="0" class="avatar" style="margin-right:-20px;"/>';
            echo '</td><td>';
            echo '<img src="/avatars/female/'.$bodyFolder.'/female.'.$avatarName.'"  border="0" class="avatar" style="margin-left:-20px;"/>';
            echo '</td></tr></table>';
            echo '</div>';
        }
        else if($g['gender']=="group")
        {

            echo '<div align="center" class="purpleToWhite" style="padding:0px;margin:6px;">';
            echo '<table><tr><td>';
            echo '<img src="/avatars/male/'.$bodyFolder.'/male.'.$maleName.'"  border="0" class="avatar" style="margin-right:-20px;"/>';
            echo '</td><td>';
            echo '<img src="/avatars/female/'.$bodyFolder.'/female.'.$femaleName.'"  border="0" class="avatar" style="margin-right:-20px;margin-left:-20px;"/>';
            echo '</td><td>';
            echo '<img src="/avatars/male/'.$bodyFolder.'/male.'.$maleName.'"  border="0" class="avatar" style="margin-right:-20px;margin-left:-20px;"/>';
            echo '</td><td>';
            echo '<img src="/avatars/female/'.$bodyFolder.'/female.'.$femaleName.'"  border="0" class="avatar" style="margin-left:-20px;"/>';
            echo '</td></tr></table>';
            echo '</div>';
        }
        else
        {


        }
?>

								</td>
								
								<td align="center">
								
										<div style="font-size:18pt;">
											<?php echo $g['firstName']; ?>
										</div>

										<div>
											<table cellpadding="0" cellspacing="0">
												<tr>
													<td>
														<div style="font-size:32pt; font-weight:bold; font-family:Arial;">
															<?php echo printGenderChar($g); ?>
														</div>
													</td>
													<td>
														<div style="font-size:18pt;">
															<?php echo printGenderString($g); ?>
														</div>
														<div style="font-size:8pt;">
														in <?php echo $g['city'].", ".$g['state']; ?>
														</div>
													</td>

												</tr>
											</table>
										</div>

										<br>
										<div style="font-size:11pt;">
											Compatible with you!
										</div>
										
										<br>
										<?php if(strlen($g['publicText'])>0){ ?>
										
												<div align="left">
													<textarea id="publicText" name="publicText" style="height:4em; width:90%; font-size:8pt; color:#777; font-weight:normal; border-radius:8px; overflow-y:scroll; white-space:nowrap;" readonly><?php echo preg_replace("/\s\s+/", " ", $g['publicText']); ?></textarea>
												</div>

										<?php } ?>

								</td>
							
							</tr>

							</table>
						</td>

						<td align="center" style="background:#eee; border-right:#aaa 1px dotted; padding-left:8px;padding-right:8px;">
						
							<div style="font-size:11pt;">
								Age
							</div>
							<br>
							<div style="font-size:14pt;">
								<?php echo $g['age']; ?>
							</div>
							
							<div style="font-size:11pt;">
								Years
							</div>
						</td>

						<td align="center" style="background:#fff; border-right:#aaa 1px dotted; padding-left:8px;padding-right:8px;">
						
							<div style="font-size:11pt;">
								Distance
							</div>
							<br>
							<div style="font-size:14pt;">
								<?php echo $distanceAmt; ?>
							</div>
							
							<div style="font-size:11pt;">
								<?php echo $distanceUnit; ?>
							</div>
						</td>
						
						<td align="center" style="background:#eee; border-right:#aaa 1px dotted; padding-left:8px;padding-right:8px;">
						
							<div style="font-size:11pt;">
								Desires
							</div>
							<br>
							<div style="font-size:14pt;">
								<?php echo $g['commonDesires']; ?>
							</div>
							
							<div style="font-size:11pt;">
								In Common
							</div>

						</td>
						


						<td align="center"
						<?php if($whichBase=="public"){ ?>
						class="whiteToBlue"
						<?php }else if($whichBase=="waitingforprivate"){ ?>
						class="whiteToGray"
						<?php }else if($whichBase=="authforprivate"){ ?>
						class="whiteToPink"
						<?php }else if($whichBase=="notmytype"){ ?>
						class="whiteToGray"
						<?php }else if($whichBase=="private"){ ?>
						class="whiteToPink"
						<?php } ?>
						style="border:0px;border-radius:0px;border-top-right-radius:8px;border-bottom-right-radius:0px;"
						>

										<?php if($whichBase=="public"){ ?>
											<div class="">
											New Match!
											</div>
										<?php }else if($whichBase=="waitingforprivate"){ ?>
											<div class="">
												Waiting For Them To Approve Trading Private Profile
											</div>
										<?php }else if($whichBase=="authforprivate"){ ?>
											<div class="">
												Waiting For You To Approve Trading Private Profile
											</div>
										<?php }else if($whichBase=="notmytype"){ ?>
											<div class="">
												Not My Type
											</div>
										<?php }else if($whichBase=="private"){ ?>
											<div class="">
												Friends With Benefits!
											</div>
										<?php } ?>

								

										<?php if($whichBase=="public"){ ?>
										<div id="div<?php echo $g['id']; ?>">

											<input type="button" class="btn btn-primary my-0 px-3 mx-1" name="askprivate" value='Ask For Private Profile' onclick="talkToServer(this,'<?php echo $g['id']; ?>');">

											<br>
											<input type="button" class="btn btn-outline-secondary my-0 px-3 mx-1" name="notmytype" value='Not My Type' onclick="talkToServer(this,'<?php echo $g['id']; ?>');">
										</div>
										<span id="status<?php echo $g['id']; ?>" style="font-size:11pt;color:#555;display:none;"></span>


										<?php }else if($whichBase=="waitingforprivate"){ ?>
											<span style="font-size:8pt;color:#555;">Waiting for an answer...</span><br>


										<?php }else if($whichBase=="authforprivate"){ ?>
										<div id="div<?php echo $g['id']; ?>">
											<span style="font-size:8pt;color:#555; font-weight:bold;"><?php echo $g['firstName']; ?> wants to trade private profiles!</span><br>
											<input type="button" class="btn btn-primary my-0 px-3 mx-1" name="authorizeprivate" value='Authorize Private Profile' onclick="talkToServer(this,'<?php echo $g['id']; ?>');">
											<br>
											<input type="button" class="btn btn-outline-secondary my-0 px-3 mx-1" name="notmytype" value='Not My Type...' onclick="talkToServer(this,'<?php echo $g['id']; ?>');">
										</div>
										<span id="status<?php echo $g['id']; ?>" style="font-size:11pt;color:#555;display:none;"></span>	


										<?php }else if($whichBase=="notmytype"){ ?>
										<div id="div<?php echo $g['id']; ?>">
											<span style="font-size:8pt;color:#555;">
											You've marked this person as "not your type."<br>
											You will not show up in their search results.
											</span>
											<br>
											<input type="button" class="btn btn-outline-secondary my-0 px-3 mx-1" name="undonotmytype" value='Undo Not My Type' onclick="talkToServer(this,'<?php echo $g['id']; ?>');">
										</div>	
										<span id="status<?php echo $g['id']; ?>" style="font-size:11pt;color:#555;display:none;"></span>	
											
											
										<?php }else if($whichBase=="private"){ ?>
												
													<div id="div<?php echo $g['id']; ?>">
														<input type="button" class="btn btn-outline-secondary my-0 px-3 mx-1" name="rejection" value='Not My Type' onclick="talkToServer(this,'<?php echo $g['id']; ?>');">
													</div>
													<span id="status<?php echo $g['id']; ?>" style="font-size:11pt;color:#555;display:none;"></span>
												
												
										<?php } ?>
										
										<br>

						</td>
					</tr>
					</table>

				<div id="<?php echo $whichBase.$g['id']; ?>details" style="">
				
<?php
						if($whichBase=="public"||$whichBase=="private"||$whichBase=="waitingforprivate"||$whichBase=="authforprivate")
						{
?>
								<div id="<?php echo $whichBase.$g['id']; ?>pics" style="font-size:8pt; border-top:#aaa 1px dotted;">	
									<table width="100%" align="center">
										<tr>
											<td width="50%">
								
												<table width="100%" align="center">
													<tr>
														<td>
															<div class="tableHeader tableOutlinedColorBlue">
															Public Pics
															</div>
															
														</td>
														
													</tr>
													<tr>
														<td>
															<div>
														
<?php

										$publicPics=explode(",",trim(trim($g['publicPics']),","));
										$privatePics=explode(",",trim(trim($g['privatePics']),","));

										
										foreach($publicPics as $s)
										{
											if($s=="")continue;
?>
											
											<a href="/_getImage?img=<?php echo $s; ?>&id=<?php echo $g['id']; ?>" target="_blank"><img src="/_getImage?img=<?php echo $s; ?>&id=<?php echo $g['id']; ?>" id="pic<?php echo $s; ?>" class="imageUploadImage" /></a>

<?php
										}
?>

															</div>
														</td>
													</tr>
												</table>
											</td>

<?php
								if($whichBase=="public"||$whichBase=="private"||$whichBase=="waitingforprivate"||$whichBase=="authforprivate")
								if(count($privatePics)!=1||$privatePics[0]!="") //skip if no pics
								if($g['email']!=$_SESSION["email"] || $whichBase=="private") //skip if your own profile
								{ 
									
								
?>
											
												<td width="50%">
													<table width="100%" align="center">
														<tr>
															<td>
																<div class="tableHeader tableOutlinedColorPink">
																Private Pics
																</div>
																
															</td>
															
														</tr>
														<tr>
															<td>
																<div>
																	
															
<?php
											//output them here
											foreach($privatePics as $s)
											{
												if($s=="")continue;
?>
												
												<a href="/_getImage?img=<?php echo $s; ?>&id=<?php echo $g['id']; ?>" target="_blank"><img src="/_getImage?img=<?php echo $s; ?>&id=<?php echo $g['id']; ?>" id="pic<?php echo $s; ?>" class="imageUploadImage" /></a>

<?php
											}
?>

																</div>
															</td>
														</tr>
													</table>
												</td>
											
											<?php }else{ ?>
											
												<td width="50%" class="questionMarkBlock">
													<div align="center" style="font-size:48pt;">
														?
													</div>
												</td>

											<?php }?>
											
										</tr>
									</table>
								</div>
						<?php } ?>

						<div id="<?php echo $whichBase.$g['id']; ?>info" style="font-size:8pt; border-top:#aaa 1px dotted;" align="left" width="100%">	
							<table border="0" cellpadding="0" cellspacing="1" width="100%">
								<tr >
									<td valign="top" >
									
										<table >
											<tr >
												<td valign="top">
										
													<tr>
													
														<div class="tableHeader tableOutlinedColorBlue">
														About
														</div>
													</tr>
													<tr>
														<td valign="top">
												
															<table >
												
												
																<tr>
																	<td align="right" valign="top" >
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Last Seen:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php 
																				if(date("F j, Y",$g['dateLastSeen'])==date("F j, Y"))echo "Today!";
																				else echo date("F j, Y",$g['dateLastSeen']); 
																			?>
																		</div>
																	</td>
																</tr>												
												
												
																<tr>
																	<td align="right" valign="top" >
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Join Date:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php 
																				echo date("F j, Y",$g['dateJoined']); 
																			?>
																		</div>
																	</td>
																</tr>	
												
												
																<tr>
																	<td align="right" valign="top" >
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Gender:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php if($g['gender']=="male"		)echo "Man"; ?>
																			<?php if($g['gender']=="female"		)echo "Woman"; ?>

																			<?php if($g['gender']=="mf"			)echo "Couple Man + Woman (MF)"; ?>
																			<?php if($g['gender']=="mm"			)echo "Couple Man + Man (MM)"; ?>
																			<?php if($g['gender']=="ff"			)echo "Couple Woman + Woman (FF)"; ?>
																			<?php if($g['gender']=="group"		)echo "Group"; ?>
																			<?php if($g['gender']=="mtf"		)echo "TS Woman (MTF)"; ?>
																			<?php if($g['gender']=="ftm"		)echo "TS Man (FTM)"; ?>
																			<?php if($g['gender']=="cdmtf"		)echo "CD Woman (MTF)"; ?>
																			<?php if($g['gender']=="cdftm"		)echo "CD Man (FTM)"; ?>
																		</div>
																	</td>
																</tr>
																
																<tr>
																	<td align="right" valign="top">
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Age:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php echo $g['age']; ?>

																		</div>
																	</td>
																</tr>
																
																<tr>
																	<td align="right" valign="top">
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Body:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php if($g['body']=="tiny"			)echo "Small"; ?>
																			<?php if($g['body']=="slim"			)echo "Slim"; ?>
																			<?php if($g['body']=="average"		)echo "Average"; ?>
																			<?php if($g['body']=="muscular"		)echo "Muscular"; ?>
																			<?php if($g['body']=="curvy"		)echo "Curvy"; ?>
																			<?php if($g['body']=="thick"		)echo "Thick"; ?>
																			<?php if($g['body']=="bbw"			)echo "BBW/BBM"; ?>
																		</div>
																	</td>
																</tr>
																
																<tr>
																	<td align="right" valign="top">
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Ethnicity:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php if($g['ethnicity']=="white"	)echo "White"; ?>
																			<?php if($g['ethnicity']=="asian"	)echo "Asian"; ?>
																			<?php if($g['ethnicity']=="latino"	)echo "Latino"; ?>
																			<?php if($g['ethnicity']=="indian"	)echo "Indian"; ?>
																			<?php if($g['ethnicity']=="black"	)echo "Black"; ?>
																			<?php if($g['ethnicity']=="other"	)echo "Other"; ?>
																		</div>
																	</td>
																</tr>
																
																<tr>
																	<td align="right" valign="top">
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Hair Color:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php if($g['hairColor']=="light"	)echo "Light"; ?>
																			<?php if($g['hairColor']=="medium"	)echo "Medium"; ?>
																			<?php if($g['hairColor']=="dark"		)echo "Dark"; ?>
																			<?php if($g['hairColor']=="red"		)echo "Red"; ?>
																			<?php if($g['hairColor']=="gray"		)echo "Gray"; ?>
																			<?php if($g['hairColor']=="other"	)echo "Other"; ?>
																		</div>
																	</td>
																</tr>
																
																<tr>
																	<td align="right" valign="top">
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Hair Length:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php if($g['hairLength']=="bald"	)echo "Bald"; ?>
																			<?php if($g['hairLength']=="short"	)echo "Short"; ?>
																			<?php if($g['hairLength']=="medium"	)echo "Medium"; ?>
																			<?php if($g['hairLength']=="long"	)echo "Long"; ?>
																		</div>
																	</td>
																</tr>
																
																<tr>
																	<td align="right" valign="top">
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Tattoos:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php if($g['tattoos']=="none"		)echo "None"; ?>
																			<?php if($g['tattoos']=="some"		)echo "Some"; ?>
																			<?php if($g['tattoos']=="allOver"	)echo "All Over"; ?>
																		</div>
																	</td>
																</tr>					
															</table>
														</td>
														<td valign="top">
															<table>

																<tr>
																	<td align="right" valign="top">
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Looks:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php if($g['overallLooks']=="ugly"			)echo "Downright Ugly"; ?>
																			<?php if($g['overallLooks']=="plain"			)echo "Plain"; ?>
																			<?php if($g['overallLooks']=="quirky"		)echo "Some Quirks"; ?>
																			<?php if($g['overallLooks']=="average"		)echo "Average"; ?>
																			<?php if($g['overallLooks']=="attractive"	)echo "Attractive"; ?>
																			<?php if($g['overallLooks']=="hottie"		)echo "Hottie"; ?>
																			<?php if($g['overallLooks']=="superModel"	)echo "Super Model"; ?>
																		</div>
																	</td>
																</tr>
																
																<tr>
																	<td align="right" valign="top">
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Intelligence:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php if($g['intelligence']=="goodHands"		)echo "Slower Than Most"; ?>
																			<?php if($g['intelligence']=="bitSlow"		)echo "A Bit Slow"; ?>
																			<?php if($g['intelligence']=="average"		)echo "Average"; ?>
																			<?php if($g['intelligence']=="faster"		)echo "A Bit Clever"; ?>
																			<?php if($g['intelligence']=="genius"		)echo "Faster Than Most"; ?>
																		</div>
																	</td>
																</tr>
																
																
																
																
																<?php if(hasBodyHair($g)==true){ ?>
																<tr>
																	<td align="right" valign="top">
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Body Hair:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																			<?php if($g['bodyHair']=="smooth"	)echo "Smooth"; ?>
																			<?php if($g['bodyHair']=="average"	)echo "Average"; ?>
																			<?php if($g['bodyHair']=="hairy"		)echo "Hairy"; ?>
																		</div>
																	</td>
																</tr>
																<?php } ?>
																

																<?php if($whichBase=="private"){ ?>
																
																		<tr>
																			<td align="right" valign="top">
																				<div class="profile_container_info_label tableOutlinedColorPink" style="padding:0; margin:0;">
																					Bedroom Type:&nbsp;
																				</div>
																			</td>
																			
																			<td align="left">
																				<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																					<?php if($g['bedroomPersonality']=="passive"			)echo "Very Passive"; ?>
																					<?php if($g['bedroomPersonality']=="shy"				)echo "Introverted/Shy"; ?>
																					<?php if($g['bedroomPersonality']=="confident"		)echo "Extroverted/Confident"; ?>
																					<?php if($g['bedroomPersonality']=="aggressive"		)echo "Very Aggressive"; ?>
																				</div>
																			</td>
																		</tr>
																		

																		<tr>
																			<td align="right" valign="top">
																				<div class="profile_container_info_label tableOutlinedColorPink" style="padding:0; margin:0;">
																					Pubic Hair:&nbsp;
																				</div>
																			</td>
																			
																			<td align="left">
																				<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																					<?php if($g['pubicHair']=="shaved"	)echo "Shaved"; ?>
																					<?php if($g['pubicHair']=="trimmed"	)echo "Short Trimmed"; ?>
																					<?php if($g['pubicHair']=="cropped"	)echo "Average Cropped"; ?>
																					<?php if($g['pubicHair']=="natural"	)echo "Natural"; ?>
																					<?php if($g['pubicHair']=="hairy"	)echo "Very Hairy"; ?>
																				</div>
																			</td>
																		</tr>
																		
																		<?php if(hasPenis($g)==true){ ?>
																		<tr>
																			<td align="right" valign="top">
																				<div class="profile_container_info_label tableOutlinedColorPink" style="padding:0; margin:0;">
																					Penis Size:&nbsp;
																				</div>
																			</td>
																			
																			<td align="left">
																				<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																					<?php if($g['penisSize']=="tiny"		)echo "Small"; ?>
																					<?php if($g['penisSize']=="skinny"	)echo "Skinny"; ?>
																					<?php if($g['penisSize']=="average"	)echo "Average"; ?>
																					<?php if($g['penisSize']=="thick"	)echo "Thick"; ?>
																					<?php if($g['penisSize']=="huge"		)echo "Huge"; ?>
																				</div>
																			</td>
																		</tr>
																		<?php } ?>
																		

																		<?php if(hasBreasts($g)==true){ ?>
																		<tr>
																			<td align="right" valign="top">
																				<div class="profile_container_info_label tableOutlinedColorPink" style="padding:0; margin:0;">
																					Breast Size:&nbsp;
																				</div>
																			</td>
																			
																			<td align="left">
																				<div class="profile_container_info_info" style="padding-left:4px;padding:0;">
																					<?php if($g['breastSize']=="tiny"		)echo "None"; ?>
																					<?php if($g['breastSize']=="small"		)echo "Small"; ?>
																					<?php if($g['breastSize']=="average"		)echo "Average"; ?>
																					<?php if($g['breastSize']=="large"		)echo "Large"; ?>
																					<?php if($g['breastSize']=="huge"		)echo "Huge"; ?>
																				</div>
																			</td>
																		</tr>
																		<?php } ?>
																
																<?php } ?>
																
																<tr>
																	<td align="right" valign="top">
																		<div class="profile_container_info_label tableOutlinedColorBlue" style="padding:0; margin:0;">
																			Other:&nbsp;
																		</div>
																	</td>
																	
																	<td align="left">
																		<div class="profile_container_info_info" style="padding-left:4px;padding:0;font-weight:bold;">
																		
																			
																			
																			<?php if($g['b_smokeCigarettes']==1		)echo "I Smoke Cigarettes<br>"; ?>
																			<?php if($g['b_lightDrinker']==1			)echo "I'm A Light Drinker<br>"; ?>
																			<?php if($g['b_heavyDrinker']==1			)echo "I'm A Heavy Drinker<br>"; ?>
																			<?php if($g['b_smokeMarijuana']==1		)echo "I Smoke Marijuana<br>"; ?>
																			<?php if($g['b_psychedelics']==1			)echo "I Use Psychedelics<br>"; ?>
																			<?php if($g['b_otherDrugs']==1			)echo "I Use Other Drugs<br>"; ?>


																			<?php if($g['b_marriedTheyKnow']==1		)echo "I Am Married And They Know<br>"; ?>
																			<?php if($g['b_marriedSecret']==1		)echo "I Am Married And They Don't Know<br>"; ?>
																			<?php if($g['b_poly']==1					)echo "I Am In A Poly Relationship<br>"; ?>
																			<?php if($g['b_disability']==1			)echo "I Have A Disability<br>"; ?>

																			<?php if($g['b_haveWarts']==1			)echo "I Have Genital Warts<br>"; ?>
																			<?php if($g['b_haveHerpes']==1			)echo "I Have Genital Herpes<br>"; ?>
																			<?php if($g['b_haveHepatitis']==1		)echo "I Have Hepatitis<br>"; ?>
																			<?php if($g['b_haveOtherSTI']==1			)echo "I Have An Other STI<br>"; ?>
																			<?php if($g['b_haveHIV']==1				)echo "I Have HIV<br>"; ?>
																			
																			
																		</div>
																	</td>
																</tr>

															</table>
														</td>
													</tr>
												</td>
											</tr>
										</table>
									</td>

									<?php if($whichBase=="private"){ ?>

											<td valign="top">
												
												<div class="tableHeader tableOutlinedColorPink">
												Shared Attributes
												</div>
												
												<table>
												
													<tr>
														<td align="right" valign="top" valign="top">
															<div class="profile_container_info_label tableOutlinedColorPink" style="padding:0; margin:0;">
																Shared Sexual Interests:&nbsp;
															</div>
														</td>
														
														<td align="left">
															<div class="profile_container_info_info profile_wants" style="padding-left:4px;padding:0; font-weight:bold; color:#ff66cb;">
																
																<?php if($b_wantSafeSex==1				&&$g['b_wantSafeSex']==1)echo "Safe Sex<br>"; ?>
																<?php if($b_wantBarebackSex==1			&&$g['b_wantBarebackSex']==1)echo "Bareback Sex<br>"; ?>
																
																<?php if($b_wantOralGive==1				&&$g['b_wantOralReceive']==1)echo "Oral (I Like Giving)<br>"; ?>
																<?php if($b_wantOralReceive==1			&&$g['b_wantOralGive']==1)echo "Oral (I Like Receiving)<br>"; ?>
																
																<?php if($b_wantAnalTop==1				&&$g['b_wantAnalBottom']==1)echo "Anal (I Like Giving)<br>"; ?>
																<?php if($b_wantAnalBottom==1			&&$g['b_wantAnalTop']==1)echo "Anal (I Like Receiving)<br>"; ?>
																
																<?php if($b_wantFilming==1				&&$g['b_wantFilming']==1)echo "Filming/Being Filmed<br>"; ?>
																
																<?php if($b_wantVoyeur==1				&&$g['b_wantExhibitionist']==1)echo "Watching (I Am A Voyeur)<br>"; ?>
																<?php if($b_wantExhibitionist==1		&&$g['b_wantVoyeur']==1)echo "Showing Off (I Am An Exhibitionist)<br>"; ?>
																
																<?php if($b_wantRoleplay==1				&&$g['b_wantRoleplay']==1)echo "Roleplay<br>"; ?>
																<?php if($b_wantSpanking==1				&&$g['b_wantSpanking']==1)echo "Spanking/Light Bondage<br>"; ?>
																
																<?php if($b_wantDom==1					&&$g['b_wantSub']==1)echo "Serious BDSM (I Am Dom)<br>"; ?>
																<?php if($b_wantSub==1					&&$g['b_wantDom']==1)echo "Serious BDSM (I Am Sub)<br>"; ?>
																
																<?php if($b_wantStrapon==1				&&$g['b_wantStrapon']==1)echo "Strapons or Pegging<br>"; ?>
																<?php if($b_wantCuckold==1				&&$g['b_wantCuckold']==1)echo "Cuckold/HotWife<br>"; ?>
																<?php if($b_wantFurry==1				&&$g['b_wantFurry']==1)echo "Furdom/Furry<br>"; ?>
																
															</div>
														</td>
													</tr>

													<tr>
														<td align="right" valign="top">
															<div class="profile_container_info_label tableOutlinedColorPink" style="padding:0; margin:0;">
																Where:&nbsp;
															</div>
														</td>
														
														<td align="left">
															<div class="profile_container_info_info profile_wants" style="padding-left:4px;padding:0; font-weight:bold; color:#ff66cb;">
															
																<?php if($b_whereMyPlace==1				&&$g['b_whereYouHost']==1)echo "My Place (I Can Host)<br>"; ?>
																<?php if($b_whereYouHost==1				&&$g['b_whereMyPlace']==1)echo "Your Place (You Host)<br>"; ?>
																
																<?php if($b_whereCarDate==1				&&$g['b_whereCarDate']==1)echo "Car Date<br>"; ?>
																
																<?php if($b_whereHotelIPay==1			&&$g['b_whereHotelYouPay']==1)echo "Hotel (I Pay)<br>"; ?>
																<?php if($b_whereHotelYouPay==1			&&$g['b_whereHotelIPay']==1)echo "Hotel (You Pay)<br>"; ?>
																
																<?php if($b_whereHotelSplit==1			&&$g['b_whereHotelSplit']==1)echo "Hotel (We Split)<br>"; ?>
																
																<?php if($b_whereBarClub==1				&&$g['b_whereBarClub']==1)echo "Bar/Club<br>"; ?>
																<?php if($b_whereGymSauna==1			&&$g['b_whereGymSauna']==1)echo "Gym/Sauna/Hot Tubs<br>"; ?>
																<?php if($b_whereNudeBeach==1			&&$g['b_whereNudeBeach']==1)echo "Nude Beach<br>"; ?>
																<?php if($b_whereOther==1				&&$g['b_whereOther']==1)echo "Other<br>"; ?>
																
															</div>
														</td>
													</tr>
													

													<?php
													/*
													<tr>
														<td align="right" valign="top">
															<div class="profile_container_info_label tableOutlinedColorPink" style="padding:0; margin:0;">
																Mutual Rules:&nbsp;
															</div>
														</td>
														
														<td align="left">
															<div class="profile_container_info_info profile_wants" style="padding-left:4px;padding:0; font-weight:bold; color:#00a;">
																
																<?php if($b_mutualLongTerm==1			&&$g['b_mutualLongTerm']==1)echo "I Want An Exclusive FWB (Monogamous And Long Term)<br>"; ?>
																<?php if($b_mutualNoStrings==1			&&$g['b_mutualNoStrings']==1)echo "Absolutely No Strings (One Time Thing)<br>"; ?>
																<?php if($b_mutualWearMask==1			&&$g['b_mutualWearMask']==1)echo "I Must Be Extremely Discreet/Anonymous/Wear A Mask<br>"; ?>
																										 
																<?php if($b_mutualOnlyPlaySafe==1		&&$g['b_mutualOnlyPlaySafe']==1)echo "Only Play Safe (No Exceptions For Any Play, Ever)<br>"; ?>
																<?php if($b_mutualProofOfTesting==1		&&$g['b_mutualProofOfTesting']==1)echo "Match Must Have Proof Of Recent Testing<br>"; ?>
																											
															</div>
														</td>
													</tr>
													*/
													?>
													

													<tr>
														<td align="right" valign="top">
															<div class="profile_container_info_label tableOutlinedColorPink" style="padding:0; margin:0;">
																Mandatory Rules:&nbsp;
															</div>
														</td>
														
														<td align="left">
															<div class="profile_container_info_info profile_wants" style="padding-left:4px;padding:0; ">
																
																<?php if($g['b_noCigs']==1)echo "No Cigarette Smokers<br>"; ?>
																<?php if($g['b_noLightDrink']==1)echo "No Light Drinking<br>"; ?>
																<?php if($g['b_noHeavyDrink']==1)echo "No Heavy Drinking<br>"; ?>
																<?php if($g['b_noMarijuana']==1)echo "No Marijuana<br>"; ?>
																<?php if($g['b_noPsychedelics']==1)echo "No Psychedelics<br>"; ?>
																<?php if($g['b_noDrugs']==1)echo "No Other Drugs<br>"; ?>
																		   
																<?php if($g['b_noHerpes']==1)echo "Match Must Not Have Genital Herpes (HSV)<br>"; ?>
																<?php if($g['b_noWarts']==1)echo "Match Must Not Have Genital Warts (HPV)<br>"; ?>
																<?php if($g['b_noHepatitis']==1)echo "Match Must Not Hepatitis<br>"; ?>
																<?php if($g['b_noOtherSTIs']==1)echo "Match Must Not Any Other STIs<br>"; ?>
																<?php if($g['b_noHIV']==1)echo "Match Must Not Have HIV<br>"; ?>
																	   
																<?php if($g['b_noMarriedSecret']==1)echo "Must Not Be Married If It Is Secret From Their Spouse<br>"; ?>
																<?php if($g['b_noMarriedTheyKnow']==1)echo "Must Not Be Married Even If Their Spouse Knows<br>"; ?>
																<?php if($g['b_noPoly']==1)echo "Match Must Not Be In Poly Relationship<br>"; ?>
																<?php if($g['b_noDisabled']==1)echo "Match Must Not Have Disabilities<br>"; ?>
																												
															</div>
														</td>
													</tr>
												</table>
											</td>


											<td valign="top">
												
												<div class="tableHeader tableOutlinedColorPink">
												Contact Info
												</div>
												<table>

													<tr>
														<td align="right" valign="top">
															<div class="profile_container_info_label tableOutlinedColorPink" style="padding:0; margin:0;">
																Email Address:&nbsp;
															</div>
														</td>
														
														<td align="left">
															<div class="profile_container_info_info " style="padding-left:4px;padding:0; font-weight:bold; color:#000;">
															
																<?php echo $g['email']; ?>

															</div>
														</td>
													</tr>
													
													<tr>
														<td align="center" colspan="2">
															<br>
															<input type="button" class="btn btn-lg btn-primary" style="font-size:18px;" name="email<?php echo $g['id']; ?>" value='Email Them Now!' onclick="parent.location='mailto:<?php echo $g['email']; ?>?subject=Hey! It\'s <?php echo $firstName; ?> from <?php echo getSiteName();?>! Wanna hang out? :-) Yeahhhhh!!! '">
														</td>
													</tr>


										<?php if(strlen($g['privateText'])>0){ ?>
										
												<tr>
													<td colspan="2">
														<div align="left">

															<textarea id="privateText" name="privateText" style="width:100%; height:30em; font-size:8pt; color:#222; font-weight:normal; border-radius:8px;" readonly><?php echo $g['privateText']; ?></textarea>

														</div>
													</td>
												</tr>

										<?php } ?>

												</table>

												<br>
                                                <br>

											</td>

									<?php } else {  ?>

									<td class="questionMarkBlock" width="30%">
										<div align="center" style="font-size:48pt;">
											?
										</div>
										
									</td>
									
									<td class="questionMarkBlock" width="30%">
										<div align="center" style="font-size:48pt;">
											?
										</div>
										
									</td>
									
									<?php }  ?>

								</tr>
							</table>
						</div>
					</div>
        </div>

		<br>
		<br>

<?php
	} //end getProfile()

