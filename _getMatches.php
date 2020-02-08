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
	function getMatches($email)
	{
		include("_debug.php");
		include("_secrets.php");
        include("_globals.php");

		$db = mysqli_connect($dburl,$dbuser,$dbpass);
		if(!$db)exit(mysqli_connect_error());

		$dbquerystring = sprintf("SELECT * FROM ".$dbname.".users WHERE email='%s'",$email);
		$dbquery = mysqli_query($db,$dbquerystring);
		
		$me = mysqli_fetch_assoc($dbquery);
		
		mysqli_free_result($dbquery);

		
		//first sort users by latitude
		//then sort by longitude
		//then remove those who aren't looking for my gender.
		//then remove gender that we aren't looking for.
		
		$distMiles=0;
		
		if($me['distance']=="dist0m"	)$distMiles = 2.0;
		if($me['distance']=="dist5m"	)$distMiles = 5.0;
		if($me['distance']=="dist10m"	)$distMiles = 10.0;
		if($me['distance']=="dist20m"	)$distMiles = 20.0;
		if($me['distance']=="dist50m"	)$distMiles = 50.0;
		
		$latDist = (1.1 * $distMiles)/49.1;
		$lonDist = (1.1 * $distMiles)/69.1;
		
		$minLat = $me['lat'] - $latDist;
		$maxLat = $me['lat'] + $latDist;
		$minLon = $me['lon'] - $lonDist;
		$maxLon = $me['lon'] + $lonDist;
		
		$dbquerystring =
		"SELECT * FROM ".$dbname.".users WHERE lat >= '{$minLat}' AND lat <= '{$maxLat}' AND lon >= '{$minLon}' AND lon <= '{$maxLon}'";
		
		//first make sure they want MY gender
		if($me['gender']=='male')$dbquerystring.=" AND b_wantGenderMan='1'";
		if($me['gender']=='female')$dbquerystring.=" AND b_wantGenderWoman='1'";
		if($me['gender']=='mtf')$dbquerystring.=" AND b_wantGenderTSWoman='1'";
		if($me['gender']=='ftm')$dbquerystring.=" AND b_wantGenderTSMan='1'";
		if($me['gender']=='cdmtf')$dbquerystring.=" AND b_wantGenderCDWoman='1'";
		if($me['gender']=='cdftm')$dbquerystring.=" AND b_wantGenderCDMan='1'";
		if($me['gender']=='mf')$dbquerystring.=" AND b_wantGenderCoupleMF='1'";
		if($me['gender']=='mm')$dbquerystring.=" AND b_wantGenderCoupleMM='1'";
		if($me['gender']=='ff')$dbquerystring.=" AND b_wantGenderCoupleFF='1'";
		if($me['gender']=='group')$dbquerystring.=" AND b_wantGenderGroup='1'";
		
		//then make sure I want THEIR gender
		if($me['b_wantGenderMan']==0)$dbquerystring.=" AND gender!='male'";
		if($me['b_wantGenderWoman']==0)$dbquerystring.=" AND gender!='female'";
		if($me['b_wantGenderTSWoman']==0)$dbquerystring.=" AND gender!='mtf'";
		if($me['b_wantGenderTSMan']==0)$dbquerystring.=" AND gender!='ftm'";
		if($me['b_wantGenderCDWoman']==0)$dbquerystring.=" AND gender!='cdmtf'";
		if($me['b_wantGenderCDMan']==0)$dbquerystring.=" AND gender!='cdftm'";
		if($me['b_wantGenderCoupleMF']==0)$dbquerystring.=" AND gender!='mf'";
		if($me['b_wantGenderCoupleMM']==0)$dbquerystring.=" AND gender!='mm'";
		if($me['b_wantGenderCoupleFF']==0)$dbquerystring.=" AND gender!='ff'";
		if($me['b_wantGenderGroup']==0)$dbquerystring.=" AND gender!='group'";
		

		//calculate age
		$age = getAge($me['birthdayMonth'],$me['birthdayDay'],$me['birthdayYear']);

		//do they want MY age?
		$dbquerystring.=" AND wantAgeFrom <= '".$age."'";
		$dbquerystring.=" AND wantAgeTo >= '".$age."'";
		
		//do they want MY body type?
		if($me['body']=='tiny')$dbquerystring.=" AND b_wantBodyTiny='1'";
		if($me['body']=='slim')$dbquerystring.=" AND b_wantBodySlim='1'";
		if($me['body']=='average')$dbquerystring.=" AND b_wantBodyAverage='1'";
		if($me['body']=='muscular')$dbquerystring.=" AND b_wantBodyMuscular='1'";
		if($me['body']=='curvy')$dbquerystring.=" AND b_wantBodyCurvy='1'";
		if($me['body']=='thick')$dbquerystring.=" AND b_wantBodyThick='1'";
		if($me['body']=='bbw')$dbquerystring.=" AND b_wantBodyBBW='1'";
		
		//do i want THEIR body type?
		if($me['b_wantBodyTiny']==0)$dbquerystring.=" AND body!='tiny'";
		if($me['b_wantBodySlim']==0)$dbquerystring.=" AND body!='slim'";
		if($me['b_wantBodyAverage']==0)$dbquerystring.=" AND body!='average'";
		if($me['b_wantBodyMuscular']==0)$dbquerystring.=" AND body!='muscular'";
		if($me['b_wantBodyCurvy']==0)$dbquerystring.=" AND body!='curvy'";
		if($me['b_wantBodyThick']==0)$dbquerystring.=" AND body!='thick'";
		if($me['b_wantBodyBBW']==0)$dbquerystring.=" AND body!='bbw'";

		
		//do they want MY ethnicity?
		if($me['ethnicity']=='white')$dbquerystring.=" AND b_wantEthnicityWhite='1'";
		if($me['ethnicity']=='asian')$dbquerystring.=" AND b_wantEthnicityAsian='1'";
		if($me['ethnicity']=='latino')$dbquerystring.=" AND b_wantEthnicityLatino='1'";
		if($me['ethnicity']=='indian')$dbquerystring.=" AND b_wantEthnicityIndian='1'";
		if($me['ethnicity']=='black')$dbquerystring.=" AND b_wantEthnicityBlack='1'";
		if($me['ethnicity']=='other')$dbquerystring.=" AND b_wantEthnicityOther='1'";

		//do i want THEIR ethnicity
		if($me['b_wantEthnicityWhite']==0)$dbquerystring.=" AND ethnicity!='white'";
		if($me['b_wantEthnicityAsian']==0)$dbquerystring.=" AND ethnicity!='asian'";
		if($me['b_wantEthnicityLatino']==0)$dbquerystring.=" AND ethnicity!='latino'";
		if($me['b_wantEthnicityIndian']==0)$dbquerystring.=" AND ethnicity!='indian'";
		if($me['b_wantEthnicityBlack']==0)$dbquerystring.=" AND ethnicity!='black'";
		if($me['b_wantEthnicityOther']==0)$dbquerystring.=" AND ethnicity!='other'";

	
				
		//do they want MY hairColor?
		if($me['hairColor']=='light')$dbquerystring.=" AND b_wantHairColorLight='1'";
		if($me['hairColor']=='medium')$dbquerystring.=" AND b_wantHairColorMedium='1'";
		if($me['hairColor']=='dark')$dbquerystring.=" AND b_wantHairColorDark='1'";
		if($me['hairColor']=='red')$dbquerystring.=" AND b_wantHairColorRed='1'";
		if($me['hairColor']=='gray')$dbquerystring.=" AND b_wantHairColorGray='1'";
		if($me['hairColor']=='other')$dbquerystring.=" AND b_wantHairColorOther='1'";

		//do i want THEIR hairColor
		if($me['b_wantHairColorLight']==0)$dbquerystring.=" AND hairColor!='light'";
		if($me['b_wantHairColorMedium']==0)$dbquerystring.=" AND hairColor!='medium'";
		if($me['b_wantHairColorDark']==0)$dbquerystring.=" AND hairColor!='dark'";
		if($me['b_wantHairColorRed']==0)$dbquerystring.=" AND hairColor!='red'";
		if($me['b_wantHairColorGray']==0)$dbquerystring.=" AND hairColor!='gray'";
		if($me['b_wantHairColorOther']==0)$dbquerystring.=" AND hairColor!='other'";

		
		//do they want MY hairLength?
		if($me['hairLength']=='bald')$dbquerystring.=" AND b_wantHairLengthBald='1'";
		if($me['hairLength']=='short')$dbquerystring.=" AND b_wantHairLengthShort='1'";
		if($me['hairLength']=='medium')$dbquerystring.=" AND b_wantHairLengthMedium='1'";
		if($me['hairLength']=='long')$dbquerystring.=" AND b_wantHairLengthLong='1'";

		//do i want THEIR hairLength
		if($me['b_wantHairLengthBald']==0)$dbquerystring.=" AND hairLength!='bald'";
		if($me['b_wantHairLengthShort']==0)$dbquerystring.=" AND hairLength!='short'";
		if($me['b_wantHairLengthMedium']==0)$dbquerystring.=" AND hairLength!='medium'";
		if($me['b_wantHairLengthLong']==0)$dbquerystring.=" AND hairLength!='long'";

		
		//do they want MY tattoos?
		if($me['tattoos']=='none')$dbquerystring.=" AND b_wantTattoosNone='1'";
		if($me['tattoos']=='some')$dbquerystring.=" AND b_wantTattoosSome='1'";
		if($me['tattoos']=='allOver')$dbquerystring.=" AND b_wantTattoosAllOver='1'";

		//do i want THEIR tattoos
		if($me['b_wantTattoosNone']==0)$dbquerystring.=" AND tattoos!='none'";
		if($me['b_wantTattoosSome']==0)$dbquerystring.=" AND tattoos!='some'";
		if($me['b_wantTattoosAllOver']==0)$dbquerystring.=" AND tattoos!='allOver'";

		
		//do they want MY overallLooks?
		if($me['overallLooks']=='ugly')$dbquerystring.=" AND b_wantLooksUgly='1'";
		if($me['overallLooks']=='plain')$dbquerystring.=" AND b_wantLooksPlain='1'";
		if($me['overallLooks']=='quirky')$dbquerystring.=" AND b_wantLooksQuirks='1'";
		if($me['overallLooks']=='average')$dbquerystring.=" AND b_wantLooksAverage='1'";
		if($me['overallLooks']=='attractive')$dbquerystring.=" AND b_wantLooksAttractive='1'";
		if($me['overallLooks']=='hottie')$dbquerystring.=" AND b_wantLooksHottie='1'";
		if($me['overallLooks']=='superModel')$dbquerystring.=" AND b_wantLooksSuperModel='1'";

		//do i want THEIR overallLooks
		if($me['b_wantLooksUgly']==0)$dbquerystring.=" AND overallLooks!='ugly'";
		if($me['b_wantLooksPlain']==0)$dbquerystring.=" AND overallLooks!='plain'";
		if($me['b_wantLooksQuirks']==0)$dbquerystring.=" AND overallLooks!='quirky'";
		if($me['b_wantLooksAverage']==0)$dbquerystring.=" AND overallLooks!='average'";
		if($me['b_wantLooksAttractive']==0)$dbquerystring.=" AND overallLooks!='attractive'";
		if($me['b_wantLooksHottie']==0)$dbquerystring.=" AND overallLooks!='hottie'";
		if($me['b_wantLooksSuperModel']==0)$dbquerystring.=" AND overallLooks!='superModel'";
		
		//do they want MY intelligence?
		if($me['intelligence']=='goodHands')$dbquerystring.=" AND b_wantIntelligenceSlow='1'";
		if($me['intelligence']=='bitSlow')$dbquerystring.=" AND b_wantIntelligenceBitSlow='1'";
		if($me['intelligence']=='average')$dbquerystring.=" AND b_wantIntelligenceAverage='1'";
		if($me['intelligence']=='faster')$dbquerystring.=" AND b_wantIntelligenceFaster='1'";
		if($me['intelligence']=='genius')$dbquerystring.=" AND b_wantIntelligenceGenius='1'";

		//do i want THEIR intelligence
		if($me['b_wantIntelligenceSlow']==0)$dbquerystring.=" AND intelligence!='goodHands'";
		if($me['b_wantIntelligenceBitSlow']==0)$dbquerystring.=" AND intelligence!='bitSlow'";
		if($me['b_wantIntelligenceAverage']==0)$dbquerystring.=" AND intelligence!='average'";
		if($me['b_wantIntelligenceFaster']==0)$dbquerystring.=" AND intelligence!='faster'";
		if($me['b_wantIntelligenceGenius']==0)$dbquerystring.=" AND intelligence!='genius'";

		
		

		
		//do they want MY bedroomPersonality?
		if($me['bedroomPersonality']=='passive')$dbquerystring.=" AND b_wantBedroomPersonalityPassive='1'";
		if($me['bedroomPersonality']=='shy')$dbquerystring.=" AND b_wantBedroomPersonalityShy='1'";
		if($me['bedroomPersonality']=='confident')$dbquerystring.=" AND b_wantBedroomPersonalityConfident='1'";
		if($me['bedroomPersonality']=='aggressive')$dbquerystring.=" AND b_wantBedroomPersonalityAggressive='1'";

		//do i want THEIR bedroomPersonality
		if($me['b_wantBedroomPersonalityPassive']==0)$dbquerystring.=" AND bedroomPersonality!='passive'";
		if($me['b_wantBedroomPersonalityShy']==0)$dbquerystring.=" AND bedroomPersonality!='shy'";
		if($me['b_wantBedroomPersonalityConfident']==0)$dbquerystring.=" AND bedroomPersonality!='confident'";
		if($me['b_wantBedroomPersonalityAggressive']==0)$dbquerystring.=" AND bedroomPersonality!='aggressive'";
		
		

		
		//do they want MY pubicHair?
		if($me['pubicHair']=='shaved')$dbquerystring.=" AND b_wantPubicHairShaved='1'";
		if($me['pubicHair']=='trimmed')$dbquerystring.=" AND b_wantPubicHairTrimmed='1'";
		if($me['pubicHair']=='cropped')$dbquerystring.=" AND b_wantPubicHairAverage='1'";
		if($me['pubicHair']=='natural')$dbquerystring.=" AND b_wantPubicHairNatural='1'";
		if($me['pubicHair']=='hairy')$dbquerystring.=" AND b_wantPubicHairHairy='1'";

		//do i want THEIR pubicHair
		if($me['b_wantPubicHairShaved']==0)$dbquerystring.=" AND pubicHair!='shaved'";
		if($me['b_wantPubicHairTrimmed']==0)$dbquerystring.=" AND pubicHair!='trimmed'";
		if($me['b_wantPubicHairAverage']==0)$dbquerystring.=" AND pubicHair!='cropped'";
		if($me['b_wantPubicHairNatural']==0)$dbquerystring.=" AND pubicHair!='natural'";
		if($me['b_wantPubicHairHairy']==0)$dbquerystring.=" AND pubicHair!='hairy'";
		
		

		//if i'm a man, if we got this far they still want me, and therefore my penis
		if($me['gender']=='male'||$me['gender']=='mtf'||$me['gender']=='cdmtf'||$me['gender']=='mf'||$me['gender']=='mm')
		{	
			//do they want MY penisSize?
			if($me['penisSize']=='tiny')$dbquerystring.=" AND b_wantPenisSizeTiny='1'";
			if($me['penisSize']=='skinny')$dbquerystring.=" AND b_wantPenisSizeSkinny='1'";
			if($me['penisSize']=='average')$dbquerystring.=" AND b_wantPenisSizeAverage='1'";
			if($me['penisSize']=='thick')$dbquerystring.=" AND b_wantPenisSizeThick='1'";
			if($me['penisSize']=='huge')$dbquerystring.=" AND b_wantPenisSizeHuge='1'";
		}
		
		//if i'm a man, if we got this far they still want me, and therefore my body hair
		if($me['gender']=='male'||$me['gender']=='ftm'||$me['gender']=='ftm'||$me['gender']=='mf'||$me['gender']=='mm')
		{	
			//do they want MY bodyHair?
			if($me['bodyHair']=='smooth')$dbquerystring.=" AND b_wantBodyHairSmooth='1'";
			if($me['bodyHair']=='average')$dbquerystring.=" AND b_wantBodyHairAverage='1'";
			if($me['bodyHair']=='hairy')$dbquerystring.=" AND b_wantBodyHairHairy='1'";
		}

		//if i'm a woman, if we got this far they still want me, and therefore my breasts
		if($me['gender']=='female'||$me['gender']=='mtf'||$me['gender']=='cdmtf'||$me['gender']=='cdftm'||$me['gender']=='ftm'||$me['gender']=='mf'||$me['gender']=='ff')
		{	
			//do they want MY breastSize?
			if($me['breastSize']=='tiny')$dbquerystring.=" AND b_wantBreastSizeTiny='1'";
			if($me['breastSize']=='small')$dbquerystring.=" AND b_wantBreastSizeSmall='1'";
			if($me['breastSize']=='average')$dbquerystring.=" AND b_wantBreastSizeAverage='1'";
			if($me['breastSize']=='large')$dbquerystring.=" AND b_wantBreastSizeLarge='1'";
			if($me['breastSize']=='huge')$dbquerystring.=" AND b_wantBreastSizeHuge='1'";
		}
		
		/*
		//these must be equal
		if($me['b_mutualLongTerm']==1)$dbquerystring.=" AND b_mutualLongTerm='1'";
		if($me['b_mutualLongTerm']==0)$dbquerystring.=" AND b_mutualLongTerm='0'";
		
		if($me['b_mutualNoStrings']==1)$dbquerystring.=" AND b_mutualNoStrings='1'";
		if($me['b_mutualNoStrings']==0)$dbquerystring.=" AND b_mutualNoStrings='0'";
		
		if($me['b_mutualWearMask']==1)$dbquerystring.=" AND b_mutualWearMask='1'";
		if($me['b_mutualWearMask']==0)$dbquerystring.=" AND b_mutualWearMask='0'";
		
		if($me['b_mutualOnlyPlaySafe']==1)$dbquerystring.=" AND b_mutualOnlyPlaySafe='1'";
		if($me['b_mutualOnlyPlaySafe']==0)$dbquerystring.=" AND b_mutualOnlyPlaySafe='0'";
		
		if($me['b_mutualProofOfTesting']==1)$dbquerystring.=" AND b_mutualProofOfTesting='1'";
		if($me['b_mutualProofOfTesting']==0)$dbquerystring.=" AND b_mutualProofOfTesting='0'";
		*/
		
		if($me['b_smokeCigarettes']==1)$dbquerystring.=" AND b_noCigs='0'";
		if($me['b_noCigs']==1)$dbquerystring.=" AND b_smokeCigarettes='0'";
		
		if($me['b_lightDrinker']==1)$dbquerystring.=" AND b_noLightDrink='0'";
		if($me['b_noLightDrink']==1)$dbquerystring.=" AND b_lightDrinker='0'";
		
		if($me['b_heavyDrinker']==1)$dbquerystring.=" AND b_noHeavyDrink='0'";
		if($me['b_noHeavyDrink']==1)$dbquerystring.=" AND b_heavyDrinker='0'";
		
		if($me['b_smokeMarijuana']==1)$dbquerystring.=" AND b_noMarijuana='0'";
		if($me['b_noMarijuana']==1)$dbquerystring.=" AND b_smokeMarijuana='0'";

		if($me['b_psychedelics']==1)$dbquerystring.=" AND b_noPsychedelics='0'";
		if($me['b_noPsychedelics']==1)$dbquerystring.=" AND b_psychedelics='0'";
		
		if($me['b_otherDrugs']==1)$dbquerystring.=" AND b_noDrugs='0'";
		if($me['b_noDrugs']==1)$dbquerystring.=" AND b_otherDrugs='0'";
		
		if($me['b_haveWarts']==1)$dbquerystring.=" AND b_noWarts='0'";
		if($me['b_noWarts']==1)$dbquerystring.=" AND b_haveWarts='0'";
		
		if($me['b_haveHerpes']==1)$dbquerystring.=" AND b_noHerpes='0'";
		if($me['b_noHerpes']==1)$dbquerystring.=" AND b_haveHerpes='0'";
		
		if($me['b_haveHepatitis']==1)$dbquerystring.=" AND b_noHepatitis='0'";
		if($me['b_noHepatitis']==1)$dbquerystring.=" AND b_haveHepatitis='0'";		
		
		if($me['b_haveOtherSTI']==1)$dbquerystring.=" AND b_noOtherSTIs='0'";
		if($me['b_noOtherSTIs']==1)$dbquerystring.=" AND b_haveOtherSTI='0'";

		if($me['b_haveHIV']==1)$dbquerystring.=" AND b_noHIV='0'";
		if($me['b_noHIV']==1)$dbquerystring.=" AND b_haveHIV='0'";	

		
		if($me['b_marriedTheyKnow']==1)$dbquerystring.=" AND b_noMarriedTheyKnow='0'";
		if($me['b_noMarriedTheyKnow']==1)$dbquerystring.=" AND b_marriedTheyKnow='0'";	

		if($me['b_marriedSecret']==1)$dbquerystring.=" AND b_noMarriedSecret='0'";
		if($me['b_noMarriedSecret']==1)$dbquerystring.=" AND b_marriedSecret='0'";
		
		if($me['b_poly']==1)$dbquerystring.=" AND b_noPoly='0'";
		if($me['b_noPoly']==1)$dbquerystring.=" AND b_poly='0'";
		
		if($me['b_disability']==1)$dbquerystring.=" AND b_noDisabled='0'";
		if($me['b_noDisabled']==1)$dbquerystring.=" AND b_disability='0'";

		
		//if any of these match mine, this section is a big OR
		$dbquerystring.=" AND (";
		
		if($me['b_wantSafeSex']==1)$dbquerystring.=" b_wantSafeSex='1' OR";
		if($me['b_wantBarebackSex']==1)$dbquerystring.=" b_wantBarebackSex='1' OR";
		
		if($me['b_wantOralGive']==1)$dbquerystring.=" b_wantOralReceive='1' OR";
		if($me['b_wantOralReceive']==1)$dbquerystring.=" b_wantOralGive='1' OR";
		
		if($me['b_wantAnalTop']==1)$dbquerystring.=" b_wantAnalBottom='1' OR";
		if($me['b_wantAnalBottom']==1)$dbquerystring.=" b_wantAnalTop='1' OR";
		
		if($me['b_wantFilming']==1)$dbquerystring.=" b_wantFilming='1' OR";
		
		if($me['b_wantVoyeur']==1)$dbquerystring.=" b_wantExhibitionist='1' OR";
		if($me['b_wantExhibitionist']==1)$dbquerystring.=" b_wantVoyeur='1' OR";
		
		if($me['b_wantRoleplay']==1)$dbquerystring.=" b_wantRoleplay='1' OR";
		if($me['b_wantSpanking']==1)$dbquerystring.=" b_wantSpanking='1' OR";
		
		if($me['b_wantDom']==1)$dbquerystring.=" b_wantSub='1' OR";
		if($me['b_wantSub']==1)$dbquerystring.=" b_wantDom='1' OR";
		
		if($me['b_wantStrapon']==1)$dbquerystring.=" b_wantStrapon='1' OR";
		if($me['b_wantCuckold']==1)$dbquerystring.=" b_wantCuckold='1' OR";
		if($me['b_wantFurry']==1)$dbquerystring.=" b_wantFurry='1' OR";
		
		//close OR
		$dbquerystring.=" '0'='1' )";

		
		//this is a big OR
		$dbquerystring.=" AND (";
		if($me['b_whereMyPlace']==1)$dbquerystring.=" b_whereYouHost='1' OR";
		if($me['b_whereYouHost']==1)$dbquerystring.=" b_whereMyPlace='1' OR";

		if($me['b_whereCarDate']==1)$dbquerystring.=" b_whereCarDate='1' OR";	
			
		if($me['b_whereHotelIPay']==1)$dbquerystring.=" b_whereHotelYouPay='1' OR";
		if($me['b_whereHotelYouPay']==1)$dbquerystring.=" b_whereHotelIPay='1' OR";
		
		if($me['b_whereHotelSplit']==1)$dbquerystring.=" b_whereHotelSplit='1' OR";
		
		if($me['b_whereBarClub']==1)$dbquerystring.=" b_whereBarClub='1' OR";
		if($me['b_whereGymSauna']==1)$dbquerystring.=" b_whereGymSauna='1' OR";
		if($me['b_whereNudeBeach']==1)$dbquerystring.=" b_whereNudeBeach='1' OR";
		if($me['b_whereOther']==1)$dbquerystring.=" b_whereOther='1' OR";
		
		//close OR
		$dbquerystring.=" '0'='1' )";
		
		

		//not myself
		$dbquerystring.=" AND email != '".$me['email']."'";
		
		//and profile done
		$dbquerystring.=" AND profileDone='1'";
		$dbquerystring.=" AND verified='1'";
		//$dbquerystring.=" AND disabled='0'";
		
		//echo $dbquerystring;
	
		$dbquery = mysqli_query($db,$dbquerystring);
		
		$numMatches = mysqli_num_rows($dbquery);
		

		if($dbquery)
		{
			$result_array=array();
			$x=0;
			while ($x<$numMatches)
			{
			    $x++;
                $row = mysqli_fetch_assoc($dbquery);
				$result_array[] = $row;
			}
			mysqli_free_result($dbquery);
		}
		mysqli_close($db);	

		$myNotMyType=explode(",",trim(trim($me['notMyType']),","));
		$myWaitingForThemPrivate=explode(",",trim(trim($me['waitingForThemPrivate']),","));
		$myPrivate=explode(",",trim(trim($me['private']),","));

		

		if(count($result_array)>0)
		{
			foreach($result_array as &$g)
			{
				$status = "";

				//check their status list for "not my type". if i am in their list, don't show. 
				$theirNotMyType = explode(",",$g['notMyType']);
				foreach($theirNotMyType as $tnmtID)
				{
					if($tnmtID==$me['id'])$status="hidden";
				}

				//if user is in "not my type" list, don't show. 
				if($status=="")
				{
					foreach($myNotMyType as $nmtID)
					{
						if($nmtID==$g['id'])$status="notmytype";
					}
				}
				

				//if i am in their "waiting for them for private" list, show "private, authorize private"" 
				if($status=="")
				{
					$theirWaitingForThemPrivate = explode(",",$g['waitingForThemPrivate']);
					foreach($theirWaitingForThemPrivate as $twftpID)
					{
						if($twftpID==$me['id'])$status="authforprivate";
					}
				}
				
				//if they are in my "waiting for them for private" list, show "waiting for them for private" 
				if($status=="")
				{
					foreach($myWaitingForThemPrivate as $wftpID)
					{
						if($wftpID==$g['id'])$status="waitingforprivate";
					}
				}

				//if user is in my "private" list, show private. 
				if($status=="")
				{
					foreach($myPrivate as $pID)
					{
						if($pID==$g['id'])$status="private";
					}
				}

				
				//if there is no status set, show default profile. STATUS = "0 DEFAULT PUBLIC"
				if($status=="")
				{
					$status="public";
				}

				$g['status']=$status;

				$g['remove']=false;

				//do they HAVE a penis?
				if(hasPenis($g)==true)
				{
					//do i CARE about their penis size
					//if they are male, and we got this far, i must be interested in males, so i care about their penis, and they have the size set.
					
					//do i want THEIR penisSize
					if($me['b_wantPenisSizeTiny']==0&&$g['penisSize']=='tiny')$g['remove']=true;
					if($me['b_wantPenisSizeSkinny']==0&&$g['penisSize']=='skinny')$g['remove']=true;
					if($me['b_wantPenisSizeAverage']==0&&$g['penisSize']=='average')$g['remove']=true;
					if($me['b_wantPenisSizeThick']==0&&$g['penisSize']=='thick')$g['remove']=true;
					if($me['b_wantPenisSizeHuge']==0&&$g['penisSize']=='huge')$g['remove']=true;
				}

				//do they HAVE bodyHair?
				if(hasBodyHair($g)==true)
				{
					//do i CARE about their bodyHair
					//do i want THEIR bodyHair
					if($me['b_wantBodyHairSmooth']==0&&$g['bodyHair']=='smooth')$g['remove']=true;
					if($me['b_wantBodyHairAverage']==0&&$g['bodyHair']=='average')$g['remove']=true;
					if($me['b_wantBodyHairHairy']==0&&$g['bodyHair']=='hairy')$g['remove']=true;
				}	
				
				//do they HAVE breastSize?
				if(hasBreasts($g)==true)
				{
					//do i CARE about their breastSize
					//do i want THEIR breastSize
					if($me['b_wantBreastSizeTiny']==0&&$g['breastSize']=='tiny')$g['remove']=true;
					if($me['b_wantBreastSizeSmall']==0&&$g['breastSize']=='small')$g['remove']=true;
					if($me['b_wantBreastSizeAverage']==0&&$g['breastSize']=='average')$g['remove']=true;
					if($me['b_wantBreastSizeLarge']==0&&$g['breastSize']=='large')$g['remove']=true;
					if($me['b_wantBreastSizeHuge']==0&&$g['breastSize']=='huge')$g['remove']=true;
				}
				

				//calculate the AGE of this user
				//have to calculate their age afterwards and then filter them out of they don't fit within my wantAge parameters
				//then remove those who aren't in my age range.
				
				$g['age']=getAge($g['birthdayMonth'],$g['birthdayDay'],$g['birthdayYear']);
				if($g['age']<$me['wantAgeFrom']||$g['age']>$me['wantAgeTo'])$g['remove']=true;
				

				//calculate COMMON DESIRES
				$commonDesires=0;
				
				if($me['b_wantSafeSex']==1&&$g['b_wantSafeSex']==1)$commonDesires++;
				if($me['b_wantBarebackSex']==1&&$g['b_wantBarebackSex']==1)$commonDesires++;
				
				if($me['b_wantOralGive']==1&&$g['b_wantOralReceive']==1)$commonDesires++;
				if($me['b_wantOralReceive']==1&&$g['b_wantOralGive']==1)$commonDesires++;
				
				if($me['b_wantAnalTop']==1&&$g['b_wantAnalBottom']==1)$commonDesires++;
				if($me['b_wantAnalBottom']==1&&$g['b_wantAnalTop']==1)$commonDesires++;
				
				if($me['b_wantFilming']==1&&$g['b_wantFilming']==1)$commonDesires++;
				
				if($me['b_wantVoyeur']==1&&$g['b_wantExhibitionist']==1)$commonDesires++;
				if($me['b_wantExhibitionist']==1&&$g['b_wantVoyeur']==1)$commonDesires++;
				
				if($me['b_wantRoleplay']==1&&$g['b_wantRoleplay']==1)$commonDesires++;
				if($me['b_wantSpanking']==1&&$g['b_wantSpanking']==1)$commonDesires++;
				
				if($me['b_wantDom']==1&&$g['b_wantSub']==1)$commonDesires++;
				if($me['b_wantSub']==1&&$g['b_wantDom']==1)$commonDesires++;
				
				if($me['b_wantStrapon']==1&&$g['b_wantStrapon']==1)$commonDesires++;
				if($me['b_wantCuckold']==1&&$g['b_wantCuckold']==1)$commonDesires++;
				if($me['b_wantFurry']==1&&$g['b_wantFurry']==1)$commonDesires++;
				
				$g['commonDesires']=$commonDesires;
				
				//calculate DISTANCE
				$g['calculatedDistance']= getDistanceBetweenPoints($me['lat'],$me['lon'],$g['lat'],$g['lon']);
				
			}
			unset($g);
			
			$new_result_array = array();
			
			//remove all "remove"
			foreach($result_array as &$g)
			{
				if($g['remove']==false)$new_result_array[]=$g;
			}
			unset($g);

			unset($result_array);
			
			return $new_result_array;
		}
	}
