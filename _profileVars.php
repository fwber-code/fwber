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
    include("_secrets.php");

    $dateJoined = 0;
    $dateLastSignedIn = 0;
    $dateLastSeen = 0;

    $postalCode = "00000";
    $country = "United States";
    $isoCountryCode = "US";
    $city= "Unknown Postal Code. Please Put City Name In Postal Code Instead.";
    $state="XX";

    $lat=0.0;
    $lon=0.0;

    $distance = "";

    $birthdayMonth = 1;
    $birthdayDay = 1;
    $birthdayYear = 1900;

    $gender = "";
    $body = "";
    $ethnicity = "";
    $hairColor = "";
    $hairLength = "";
    $tattoos = "";
    $overallLooks = "";
    $intelligence = "";
    $bedroomPersonality = "";
    $pubicHair = "";
    $penisSize = "";
    $bodyHair = "";
    $breastSize = "";

    $b_smokeCigarettes = 0;
    $b_lightDrinker = 0;
    $b_heavyDrinker = 0;
    $b_smokeMarijuana = 0;
    $b_psychedelics = 0;
    $b_otherDrugs = 0;

    $b_haveWarts = 0;
    $b_haveHerpes = 0;
    $b_haveHepatitis = 0;
    $b_haveOtherSTI = 0;
    $b_haveHIV = 0;

    $b_marriedTheyKnow = 0;
    $b_marriedSecret = 0;
    $b_poly = 0;
    $b_disability = 0;

    $b_wantGenderMan = 0;
    $b_wantGenderWoman = 0;
    $b_wantGenderTSWoman = 0;
    $b_wantGenderTSMan = 0;
    $b_wantGenderCDWoman = 0;
    $b_wantGenderCDMan = 0;
    $b_wantGenderCoupleMF = 0;
    $b_wantGenderCoupleMM = 0;
    $b_wantGenderCoupleFF = 0;
    $b_wantGenderGroup = 0;

    $wantAgeFrom = 18;
    $wantAgeTo = 99;

    $b_wantBodyTiny = 0;
    $b_wantBodySlim = 0;
    $b_wantBodyAverage = 0;
    $b_wantBodyMuscular = 0;
    $b_wantBodyCurvy = 0;
    $b_wantBodyThick = 0;
    $b_wantBodyBBW = 0;
    $b_wantEthnicityWhite = 0;
    $b_wantEthnicityAsian = 0;
    $b_wantEthnicityLatino = 0;
    $b_wantEthnicityIndian = 0;
    $b_wantEthnicityBlack = 0;
    $b_wantEthnicityOther = 0;
    $b_wantHairColorLight = 0;
    $b_wantHairColorMedium = 0;
    $b_wantHairColorDark = 0;
    $b_wantHairColorRed = 0;
    $b_wantHairColorGray = 0;
    $b_wantHairColorOther = 0;
    $b_wantHairLengthBald = 0;
    $b_wantHairLengthShort = 0;
    $b_wantHairLengthMedium = 0;
    $b_wantHairLengthLong = 0;
    $b_wantTattoosNone = 0;
    $b_wantTattoosSome = 0;
    $b_wantTattoosAllOver = 0;
    $b_wantLooksUgly = 0;
    $b_wantLooksPlain = 0;
    $b_wantLooksQuirks = 0;
    $b_wantLooksAverage = 0;
    $b_wantLooksAttractive = 0;
    $b_wantLooksHottie = 0;
    $b_wantLooksSuperModel = 0;
    $b_wantIntelligenceSlow = 0;
    $b_wantIntelligenceBitSlow = 0;
    $b_wantIntelligenceAverage = 0;
    $b_wantIntelligenceFaster = 0;
    $b_wantIntelligenceGenius = 0;
    $b_wantBedroomPersonalityPassive = 0;
    $b_wantBedroomPersonalityShy = 0;
    $b_wantBedroomPersonalityConfident = 0;
    $b_wantBedroomPersonalityAggressive = 0;
    $b_wantPubicHairShaved = 0;
    $b_wantPubicHairTrimmed = 0;
    $b_wantPubicHairAverage = 0;
    $b_wantPubicHairNatural = 0;
    $b_wantPubicHairHairy = 0;
    $b_wantPenisSizeTiny = 0;
    $b_wantPenisSizeSkinny = 0;
    $b_wantPenisSizeAverage = 0;
    $b_wantPenisSizeThick = 0;
    $b_wantPenisSizeHuge = 0;
    $b_wantBodyHairSmooth = 0;
    $b_wantBodyHairAverage = 0;
    $b_wantBodyHairHairy = 0;
    $b_wantBreastSizeTiny = 0;
    $b_wantBreastSizeSmall = 0;
    $b_wantBreastSizeAverage = 0;
    $b_wantBreastSizeLarge = 0;
    $b_wantBreastSizeHuge = 0;

    $b_wantSafeSex = 0;
    $b_wantBarebackSex = 0;
    $b_wantOralGive = 0;
    $b_wantOralReceive = 0;
    $b_wantAnalTop = 0;
    $b_wantAnalBottom = 0;
    $b_wantFilming = 0;
    $b_wantVoyeur = 0;
    $b_wantExhibitionist = 0;
    $b_wantRoleplay = 0;
    $b_wantSpanking = 0;
    $b_wantDom = 0;
    $b_wantSub = 0;
    $b_wantStrapon = 0;
    $b_wantCuckold = 0;
    $b_wantFurry = 0;

    $b_whereMyPlace = 0;
    $b_whereYouHost = 0;
    $b_whereCarDate = 0;
    $b_whereHotelIPay = 0;
    $b_whereHotelYouPay = 0;
    $b_whereHotelSplit = 0;
    $b_whereBarClub = 0;
    $b_whereGymSauna = 0;
    $b_whereNudeBeach = 0;
    $b_whereOther = 0;

    $b_mutualLongTerm = 0;
    $b_mutualNoStrings = 0;
    $b_mutualWearMask = 0;
    $b_mutualOnlyPlaySafe = 0;
    $b_mutualProofOfTesting = 0;
    $b_noCigs = 0;
    $b_noLightDrink = 0;
    $b_noHeavyDrink = 0;
    $b_noMarijuana = 0;
    $b_noPsychedelics = 0;
    $b_noDrugs = 0;
    $b_noHerpes = 0;
    $b_noWarts = 0;
    $b_noHepatitis = 0;
    $b_noOtherSTIs = 0;
    $b_noHIV = 0;
    $b_noMarriedTheyKnow = 0;
    $b_noMarriedSecret = 0;
    $b_noPoly = 0;
    $b_noDisabled = 0;

    $publicText = "";
    $privateText = "";
    $firstName = "";

    //=========================================================================================
    function initAllVariablesFromDB()
    {//=========================================================================================

        include("_debug.php");
        include("_secrets.php");
        include("_globals.php");

		//need to load existing values and fill them in the forms.

		$db = mysqli_connect($dburl,$dbuser,$dbpass);
		if(!$db)exit(mysqli_connect_error());
		
		$email = mysqli_escape_string($db,$_SESSION["email"]);

		$dbquerystring = "SELECT ".
		"email".
		", "."dateJoined".
		", "."dateLastSignedIn".
		", "."dateLastSeen".
		", "."profileDone".
		", "."postalCode".
		", "."country".
		", "."isoCountryCode".
		", "."city".
		", "."state".
		", "."lat".
		", "."lon".
		", "."distance".
		", "."birthdayMonth".
		", "."birthdayDay".
		", "."birthdayYear".
		", "."gender".
		", "."body".
		", "."ethnicity".
		", "."hairColor".
		", "."hairLength".
		", "."tattoos".
		", "."overallLooks".
		", "."intelligence".
		", "."bedroomPersonality".
		", "."pubicHair".
		", "."penisSize".
		", "."bodyHair".
		", "."breastSize".

		", "."b_smokeCigarettes".
		", "."b_lightDrinker".
		", "."b_heavyDrinker".
		", "."b_smokeMarijuana".
		", "."b_psychedelics".
		", "."b_otherDrugs".

		", "."b_haveWarts".
		", "."b_haveHerpes".
		", "."b_haveHepatitis".
		", "."b_haveOtherSTI".
		", "."b_haveHIV".

		", "."b_marriedTheyKnow".
		", "."b_marriedSecret".
		", "."b_poly".
		", "."b_disability".

		", "."b_wantGenderMan".
		", "."b_wantGenderWoman".
		", "."b_wantGenderTSWoman".
		", "."b_wantGenderTSMan".
		", "."b_wantGenderCDWoman".
		", "."b_wantGenderCDMan".
		", "."b_wantGenderCoupleMF".
		", "."b_wantGenderCoupleMM".
		", "."b_wantGenderCoupleFF".
		", "."b_wantGenderGroup".

		", "."wantAgeFrom".
		", "."wantAgeTo".

		", "."b_wantBodyTiny".
		", "."b_wantBodySlim".
		", "."b_wantBodyAverage".
		", "."b_wantBodyMuscular".
		", "."b_wantBodyCurvy".
		", "."b_wantBodyThick".
		", "."b_wantBodyBBW".

		", "."b_wantEthnicityWhite".
		", "."b_wantEthnicityAsian".
		", "."b_wantEthnicityLatino".
		", "."b_wantEthnicityIndian".		
		", "."b_wantEthnicityBlack".
		", "."b_wantEthnicityOther".

		", "."b_wantHairColorLight".
		", "."b_wantHairColorMedium".
		", "."b_wantHairColorDark".
		", "."b_wantHairColorRed".
		", "."b_wantHairColorGray".
		", "."b_wantHairColorOther".

		", "."b_wantHairLengthBald".
		", "."b_wantHairLengthShort".
		", "."b_wantHairLengthMedium".
		", "."b_wantHairLengthLong".

		", "."b_wantTattoosNone".
		", "."b_wantTattoosSome".
		", "."b_wantTattoosAllOver".

		", "."b_wantLooksUgly".
		", "."b_wantLooksPlain".
		", "."b_wantLooksQuirks".
		", "."b_wantLooksAverage".
		", "."b_wantLooksAttractive".
		", "."b_wantLooksHottie".
		", "."b_wantLooksSuperModel".

		", "."b_wantIntelligenceSlow".
		", "."b_wantIntelligenceBitSlow".
		", "."b_wantIntelligenceAverage".
		", "."b_wantIntelligenceFaster".
		", "."b_wantIntelligenceGenius".

		", "."b_wantBedroomPersonalityPassive".
		", "."b_wantBedroomPersonalityShy".
		", "."b_wantBedroomPersonalityConfident".
		", "."b_wantBedroomPersonalityAggressive".

		", "."b_wantPubicHairShaved".
		", "."b_wantPubicHairTrimmed".
		", "."b_wantPubicHairAverage".
		", "."b_wantPubicHairNatural".
		", "."b_wantPubicHairHairy".

		", "."b_wantPenisSizeTiny".
		", "."b_wantPenisSizeSkinny".
		", "."b_wantPenisSizeAverage".
		", "."b_wantPenisSizeThick".
		", "."b_wantPenisSizeHuge".

		", "."b_wantBodyHairSmooth".
		", "."b_wantBodyHairAverage".
		", "."b_wantBodyHairHairy".

		", "."b_wantBreastSizeTiny".
		", "."b_wantBreastSizeSmall".
		", "."b_wantBreastSizeAverage".
		", "."b_wantBreastSizeLarge".
		", "."b_wantBreastSizeHuge".

		", "."b_wantSafeSex".
		", "."b_wantBarebackSex".

		", "."b_wantOralGive".
		", "."b_wantOralReceive".

		", "."b_wantAnalTop".
		", "."b_wantAnalBottom".

		", "."b_wantFilming".
		", "."b_wantVoyeur".
		", "."b_wantExhibitionist".

		", "."b_wantRoleplay".
		", "."b_wantSpanking".
		", "."b_wantDom".
		", "."b_wantSub".

		", "."b_wantStrapon".
		", "."b_wantCuckold".
		", "."b_wantFurry".
		

		", "."b_whereMyPlace".
		", "."b_whereYouHost".

		", "."b_whereCarDate".
		", "."b_whereHotelIPay".
		", "."b_whereHotelYouPay".
		", "."b_whereHotelSplit".
		", "."b_whereBarClub".
		", "."b_whereGymSauna".
		", "."b_whereNudeBeach".
		", "."b_whereOther".

		", "."b_mutualLongTerm".
		", "."b_mutualNoStrings".
		", "."b_mutualWearMask".

		", "."b_mutualOnlyPlaySafe".
		", "."b_mutualProofOfTesting".

		", "."b_noCigs".
		", "."b_noLightDrink".
		", "."b_noHeavyDrink".
		", "."b_noMarijuana".

		", "."b_noPsychedelics".
		", "."b_noDrugs".

		", "."b_noHerpes".
		", "."b_noWarts".
		", "."b_noHepatitis".
		", "."b_noOtherSTIs".
		", "."b_noHIV".

		", "."b_noMarriedTheyKnow".
		", "."b_noMarriedSecret".
		", "."b_noPoly".
		", "."b_noDisabled".

		", "."publicText".
		", "."privateText".
		", "."firstName".
		sprintf(" FROM ".$dbname.".users WHERE email='%s'",$email);
		
		$dbquery = mysqli_query($db,$dbquerystring);
		$dbresults = mysqli_fetch_array($dbquery);
		mysqli_free_result($dbquery);
				
		//done with the db
		mysqli_close($db);
		
		if(
			$dbresults!=null 
		)
		{
		
			$dateJoined									 = $dbresults['dateJoined'					];
			$dateLastSignedIn								 = $dbresults['dateLastSignedIn'					];
			$dateLastSeen								 = $dbresults['dateLastSeen'					];
			$profileDone								 = $dbresults['profileDone'					];
			$postalCode									 = $dbresults['postalCode'						];
			$country								 = $dbresults['country'						];
			$isoCountryCode								 = $dbresults['isoCountryCode'						];
            $city									     = $dbresults['city'						];
			$state									 = $dbresults['state'						];
			$lat										 = $dbresults['lat'						];
			$lon									 	 = $dbresults['lon'						];
			$distance									 = $dbresults['distance'					];
			$birthdayMonth								 = $dbresults['birthdayMonth'				];
			$birthdayDay								 = $dbresults['birthdayDay'					];
			$birthdayYear								 = $dbresults['birthdayYear'				];
			$gender										 = $dbresults['gender'						];
			$body										 = $dbresults['body'						];
			$ethnicity									 = $dbresults['ethnicity'					];
			$hairColor									 = $dbresults['hairColor'					];
			$hairLength									 = $dbresults['hairLength'					];
			$tattoos									 = $dbresults['tattoos'						];
			$overallLooks								 = $dbresults['overallLooks'				];
			$intelligence								 = $dbresults['intelligence'				];
			$bedroomPersonality							 = $dbresults['bedroomPersonality'			];
			$pubicHair									 = $dbresults['pubicHair'					];
			$penisSize									 = $dbresults['penisSize'					];
			$bodyHair									 = $dbresults['bodyHair'					];
			$breastSize									 = $dbresults['breastSize'					];

			$b_smokeCigarettes							 = $dbresults['b_smokeCigarettes'				];
			$b_lightDrinker								 = $dbresults['b_lightDrinker'				];
			$b_heavyDrinker								 = $dbresults['b_heavyDrinker'				];
			$b_smokeMarijuana								 = $dbresults['b_smokeMarijuana'				];
			$b_psychedelics									 = $dbresults['b_psychedelics'					];
			$b_otherDrugs									 = $dbresults['b_otherDrugs'					];

			$b_haveWarts									 = $dbresults['b_haveWarts'					];
			$b_haveHerpes									 = $dbresults['b_haveHerpes'					];
			$b_haveHepatitis								 = $dbresults['b_haveHepatitis'				];
			$b_haveOtherSTI								 = $dbresults['b_haveOtherSTI'				];
			$b_haveHIV									 = $dbresults['b_haveHIV'						];

			$b_marriedTheyKnow							 = $dbresults['b_marriedTheyKnow'				];
			$b_marriedSecret								 = $dbresults['b_marriedSecret'				];
			$b_poly										 = $dbresults['b_poly'						];
			$b_disability									 = $dbresults['b_disability'					];

			$b_wantGenderMan								 = $dbresults['b_wantGenderMan'				];
			$b_wantGenderWoman							 = $dbresults['b_wantGenderWoman'				];
			$b_wantGenderTSWoman							 = $dbresults['b_wantGenderTSWoman'			];
			$b_wantGenderTSMan							 = $dbresults['b_wantGenderTSMan'				];
			$b_wantGenderCDWoman							 = $dbresults['b_wantGenderCDWoman'			];
			$b_wantGenderCDMan							 = $dbresults['b_wantGenderCDMan'				];
			$b_wantGenderCoupleMF							 = $dbresults['b_wantGenderCoupleMF'			];
			$b_wantGenderCoupleMM							 = $dbresults['b_wantGenderCoupleMM'			];
			$b_wantGenderCoupleFF							 = $dbresults['b_wantGenderCoupleFF'			];
			$b_wantGenderGroup							 = $dbresults['b_wantGenderGroup'				];

			$wantAgeFrom								 = $dbresults['wantAgeFrom'					];
			$wantAgeTo									 = $dbresults['wantAgeTo'					];

			$b_wantBodyTiny								 = $dbresults['b_wantBodyTiny'				];
			$b_wantBodySlim								 = $dbresults['b_wantBodySlim'				];
			$b_wantBodyAverage							 = $dbresults['b_wantBodyAverage'				];
			$b_wantBodyMuscular							 = $dbresults['b_wantBodyMuscular'			];
			$b_wantBodyCurvy								 = $dbresults['b_wantBodyCurvy'				];
			$b_wantBodyThick								 = $dbresults['b_wantBodyThick'				];
			$b_wantBodyBBW								 = $dbresults['b_wantBodyBBW'					];

			$b_wantEthnicityWhite							 = $dbresults['b_wantEthnicityWhite'			];
			$b_wantEthnicityAsian							 = $dbresults['b_wantEthnicityAsian'			];
			$b_wantEthnicityLatino						 = $dbresults['b_wantEthnicityLatino'			];
			$b_wantEthnicityIndian						 = $dbresults['b_wantEthnicityIndian'			];
			$b_wantEthnicityBlack							 = $dbresults['b_wantEthnicityBlack'			];
			$b_wantEthnicityOther							 = $dbresults['b_wantEthnicityOther'			];

			$b_wantHairColorLight							 = $dbresults['b_wantHairColorLight'			];
			$b_wantHairColorMedium						 = $dbresults['b_wantHairColorMedium'			];
			$b_wantHairColorDark							 = $dbresults['b_wantHairColorDark'			];
			$b_wantHairColorRed							 = $dbresults['b_wantHairColorRed'			];
			$b_wantHairColorGray							 = $dbresults['b_wantHairColorGray'			];
			$b_wantHairColorOther							 = $dbresults['b_wantHairColorOther'			];

			$b_wantHairLengthBald							 = $dbresults['b_wantHairLengthBald'			];
			$b_wantHairLengthShort						 = $dbresults['b_wantHairLengthShort'			];
			$b_wantHairLengthMedium						 = $dbresults['b_wantHairLengthMedium'		];
			$b_wantHairLengthLong							 = $dbresults['b_wantHairLengthLong'			];

			$b_wantTattoosNone							 = $dbresults['b_wantTattoosNone'				];
			$b_wantTattoosSome							 = $dbresults['b_wantTattoosSome'				];
			$b_wantTattoosAllOver							 = $dbresults['b_wantTattoosAllOver'			];

			$b_wantLooksUgly								 = $dbresults['b_wantLooksUgly'				];
			$b_wantLooksPlain								 = $dbresults['b_wantLooksPlain'				];
			$b_wantLooksQuirks							 = $dbresults['b_wantLooksQuirks'				];
			$b_wantLooksAverage							 = $dbresults['b_wantLooksAverage'			];
			$b_wantLooksAttractive						 = $dbresults['b_wantLooksAttractive'			];
			$b_wantLooksHottie							 = $dbresults['b_wantLooksHottie'				];
			$b_wantLooksSuperModel						 = $dbresults['b_wantLooksSuperModel'			];

			$b_wantIntelligenceSlow						 = $dbresults['b_wantIntelligenceSlow'					];
			$b_wantIntelligenceBitSlow					 = $dbresults['b_wantIntelligenceBitSlow'					];
			$b_wantIntelligenceAverage					 = $dbresults['b_wantIntelligenceAverage'					];
			$b_wantIntelligenceFaster						 = $dbresults['b_wantIntelligenceFaster'					];
			$b_wantIntelligenceGenius						 = $dbresults['b_wantIntelligenceGenius'					];

			$b_wantBedroomPersonalityPassive				 = $dbresults['b_wantBedroomPersonalityPassive'			];
			$b_wantBedroomPersonalityShy					 = $dbresults['b_wantBedroomPersonalityShy'				];
			$b_wantBedroomPersonalityConfident			 = $dbresults['b_wantBedroomPersonalityConfident'			];
			$b_wantBedroomPersonalityAggressive			 = $dbresults['b_wantBedroomPersonalityAggressive'		];

			$b_wantPubicHairShaved						 = $dbresults['b_wantPubicHairShaved'						];
			$b_wantPubicHairTrimmed						 = $dbresults['b_wantPubicHairTrimmed'					];
			$b_wantPubicHairAverage						 = $dbresults['b_wantPubicHairAverage'					];
			$b_wantPubicHairNatural						 = $dbresults['b_wantPubicHairNatural'					];
			$b_wantPubicHairHairy							 = $dbresults['b_wantPubicHairHairy'					];

			$b_wantPenisSizeTiny							 = $dbresults['b_wantPenisSizeTiny'					];
			$b_wantPenisSizeSkinny						 = $dbresults['b_wantPenisSizeSkinny'					];
			$b_wantPenisSizeAverage						 = $dbresults['b_wantPenisSizeAverage'				];
			$b_wantPenisSizeThick							 = $dbresults['b_wantPenisSizeThick'					];
			$b_wantPenisSizeHuge							 = $dbresults['b_wantPenisSizeHuge'					];

			$b_wantBodyHairSmooth							 = $dbresults['b_wantBodyHairSmooth'					];
			$b_wantBodyHairAverage						 = $dbresults['b_wantBodyHairAverage'					];
			$b_wantBodyHairHairy							 = $dbresults['b_wantBodyHairHairy'					];

			$b_wantBreastSizeTiny							 = $dbresults['b_wantBreastSizeTiny'					];
			$b_wantBreastSizeSmall						 = $dbresults['b_wantBreastSizeSmall'					];
			$b_wantBreastSizeAverage						 = $dbresults['b_wantBreastSizeAverage'				];
			$b_wantBreastSizeLarge						 = $dbresults['b_wantBreastSizeLarge'					];
			$b_wantBreastSizeHuge							 = $dbresults['b_wantBreastSizeHuge'					];

			$b_wantSafeSex								 = $dbresults['b_wantSafeSex'						];
			$b_wantBarebackSex							 = $dbresults['b_wantBarebackSex'					];

			$b_wantOralGive								 = $dbresults['b_wantOralGive'					];
			$b_wantOralReceive							 = $dbresults['b_wantOralReceive'					];

			$b_wantAnalTop								 = $dbresults['b_wantAnalTop'						];
			$b_wantAnalBottom								 = $dbresults['b_wantAnalBottom'					];

			$b_wantFilming								 = $dbresults['b_wantFilming'						];
			$b_wantVoyeur									 = $dbresults['b_wantVoyeur'						];
			$b_wantExhibitionist							 = $dbresults['b_wantExhibitionist'				];

			$b_wantRoleplay								 = $dbresults['b_wantRoleplay'					];
			$b_wantSpanking								 = $dbresults['b_wantSpanking'					];
			$b_wantDom									 = $dbresults['b_wantDom'							];
			$b_wantSub									 = $dbresults['b_wantSub'							];

			$b_wantStrapon								 = $dbresults['b_wantStrapon'						];
			$b_wantCuckold								 = $dbresults['b_wantCuckold'						];
			$b_wantFurry									 = $dbresults['b_wantFurry'						];
			

			$b_whereMyPlace								 = $dbresults['b_whereMyPlace'					];
			$b_whereYouHost								 = $dbresults['b_whereYouHost'					];

			$b_whereCarDate								 = $dbresults['b_whereCarDate'					];
			$b_whereHotelIPay								 = $dbresults['b_whereHotelIPay'					];
			$b_whereHotelYouPay							 = $dbresults['b_whereHotelYouPay'				];
			$b_whereHotelSplit							 = $dbresults['b_whereHotelSplit'					];
			$b_whereBarClub								 = $dbresults['b_whereBarClub'					];
			$b_whereGymSauna								 = $dbresults['b_whereGymSauna'					];
			$b_whereNudeBeach								 = $dbresults['b_whereNudeBeach'					];
			$b_whereOther									 = $dbresults['b_whereOther'						];

			$b_mutualLongTerm								 = $dbresults['b_mutualLongTerm'					];
			$b_mutualNoStrings							 = $dbresults['b_mutualNoStrings'					];
			$b_mutualWearMask								 = $dbresults['b_mutualWearMask'					];

			$b_mutualOnlyPlaySafe							 = $dbresults['b_mutualOnlyPlaySafe'				];
			$b_mutualProofOfTesting						 = $dbresults['b_mutualProofOfTesting'			];

			$b_noCigs										 = $dbresults['b_noCigs'							];
			$b_noLightDrink								 = $dbresults['b_noLightDrink'					];
			$b_noHeavyDrink								 = $dbresults['b_noHeavyDrink'					];
			$b_noMarijuana								 = $dbresults['b_noMarijuana'						];
			$b_noPsychedelics									 = $dbresults['b_noPsychedelics'							];
			$b_noDrugs									 = $dbresults['b_noDrugs'							];

			$b_noHerpes									 = $dbresults['b_noHerpes'						];
			$b_noWarts									 = $dbresults['b_noWarts'						];
			$b_noHepatitis								 = $dbresults['b_noHepatitis'						];
			$b_noOtherSTIs								 = $dbresults['b_noOtherSTIs'						];
			$b_noHIV										 = $dbresults['b_noHIV'							];

			$b_noMarriedTheyKnow									 = $dbresults['b_noMarriedTheyKnow'						];
			$b_noMarriedSecret									 = $dbresults['b_noMarriedSecret'						];
			$b_noPoly										 = $dbresults['b_noPoly'							];
			$b_noDisabled									 = $dbresults['b_noDisabled'						];

			$publicText									 = $dbresults['publicText'						];
			$privateText									 = $dbresults['privateText'						];
			$firstName									 = $dbresults['firstName'						];
		}
    }

    //=========================================================================================
    function updateDBWithAllVariables()
    {//=========================================================================================

        include("_debug.php");
        include("_secrets.php");
        include("_globals.php");
		
		$db = mysqli_connect($dburl,$dbuser,$dbpass);
		if(!$db)exit(mysqli_connect_error());

		$email = mysqli_escape_string($db,$_SESSION["email"]);

		$dbquerystring = sprintf("SELECT id, email FROM ".$dbname.".users WHERE email='%s'",$email);
		$dbquery = mysqli_query($db,$dbquerystring);
		$dbresults = mysqli_fetch_array($dbquery);
		mysqli_free_result($dbquery);
		
		//-------------------------------------------------	
		//when submit changes, set profileDone to true.
		//-------------------------------------------------	
		
		$dbquerystring =
		"UPDATE ".$dbname.".users SET".
		" `profileDone` = '1'".
		
		", `postalCode` = '".$postalCode."'".
		", `country` = '".$country."'".
		", `isoCountryCode` = '".$isoCountryCode."'".
		", `city` = '".$city."'".
		", `state` = '".$state."'".
		", `lat` = '".$lat."'".
		", `lon` = '".$lon."'".
		
		", `distance` = '".$distance."'".
		
		", `birthdayMonth` = '".$birthdayMonth."'".
		", `birthdayDay` = '".$birthdayDay."'".
		", `birthdayYear` = '".$birthdayYear."'".
		
		", `gender` = '".$gender."'".
		", `body` = '".$body."'".
		", `ethnicity` = '".$ethnicity."'".
		", `hairColor` = '".$hairColor."'".
		", `hairLength` = '".$hairLength."'".
		", `tattoos` = '".$tattoos."'".
		", `overallLooks` = '".$overallLooks."'".
		", `intelligence` = '".$intelligence."'".
		", `bedroomPersonality` = '".$bedroomPersonality."'".
		", `pubicHair` = '".$pubicHair."'".
		", `penisSize` = '".$penisSize."'".
		", `breastSize` = '".$breastSize."'".
		", `bodyHair` = '".$bodyHair."'".
		", `wantAgeFrom` = '".$wantAgeFrom."'".
		", `wantAgeTo` = '".$wantAgeTo."'".
		", `b_smokeCigarettes` = '".$b_smokeCigarettes."'".
		", `b_lightDrinker` = '".$b_lightDrinker."'".
		", `b_heavyDrinker` = '".$b_heavyDrinker."'".
		", `b_smokeMarijuana` = '".$b_smokeMarijuana."'".
		", `b_psychedelics` = '".$b_psychedelics."'".
		", `b_otherDrugs` = '".$b_otherDrugs."'".
		", `b_haveWarts` = '".$b_haveWarts."'".
		", `b_haveHerpes` = '".$b_haveHerpes."'".
		", `b_haveHepatitis` = '".$b_haveHepatitis."'".
		", `b_haveOtherSTI` = '".$b_haveOtherSTI."'".
		", `b_haveHIV` = '".$b_haveHIV."'".
		", `b_marriedTheyKnow` = '".$b_marriedTheyKnow."'".
		", `b_marriedSecret` = '".$b_marriedSecret."'".
		", `b_poly` = '".$b_poly."'".
		", `b_disability` = '".$b_disability."'".
		", `b_wantGenderMan` = '".$b_wantGenderMan."'".
		", `b_wantGenderWoman` = '".$b_wantGenderWoman."'".
		", `b_wantGenderTSWoman` = '".$b_wantGenderTSWoman."'".
		", `b_wantGenderTSMan` = '".$b_wantGenderTSMan."'".
		", `b_wantGenderCDWoman` = '".$b_wantGenderCDWoman."'".
		", `b_wantGenderCDMan` = '".$b_wantGenderCDMan."'".
		", `b_wantGenderCoupleMF` = '".$b_wantGenderCoupleMF."'".
		", `b_wantGenderCoupleMM` = '".$b_wantGenderCoupleMM."'".
		", `b_wantGenderCoupleFF` = '".$b_wantGenderCoupleFF."'".
		", `b_wantGenderGroup` = '".$b_wantGenderGroup."'".
		", `b_wantBodyTiny` = '".$b_wantBodyTiny."'".
		", `b_wantBodySlim` = '".$b_wantBodySlim."'".
		", `b_wantBodyAverage` = '".$b_wantBodyAverage."'".
		", `b_wantBodyMuscular` = '".$b_wantBodyMuscular."'".
		", `b_wantBodyCurvy` = '".$b_wantBodyCurvy."'".
		", `b_wantBodyThick` = '".$b_wantBodyThick."'".
		", `b_wantBodyBBW` = '".$b_wantBodyBBW."'".
		", `b_wantEthnicityWhite` = '".$b_wantEthnicityWhite."'".
		", `b_wantEthnicityAsian` = '".$b_wantEthnicityAsian."'".
		", `b_wantEthnicityLatino` = '".$b_wantEthnicityLatino."'".
		", `b_wantEthnicityIndian` = '".$b_wantEthnicityIndian."'".
		", `b_wantEthnicityBlack` = '".$b_wantEthnicityBlack."'".
		", `b_wantEthnicityOther` = '".$b_wantEthnicityOther."'".
		", `b_wantHairColorLight` = '".$b_wantHairColorLight."'".
		", `b_wantHairColorMedium` = '".$b_wantHairColorMedium."'".
		", `b_wantHairColorDark` = '".$b_wantHairColorDark."'".
		", `b_wantHairColorRed` = '".$b_wantHairColorRed."'".
		", `b_wantHairColorGray` = '".$b_wantHairColorGray."'".
		", `b_wantHairColorOther` = '".$b_wantHairColorOther."'".
		", `b_wantHairLengthBald` = '".$b_wantHairLengthBald."'".
		", `b_wantHairLengthShort` = '".$b_wantHairLengthShort."'".
		", `b_wantHairLengthMedium` = '".$b_wantHairLengthMedium."'".
		", `b_wantHairLengthLong` = '".$b_wantHairLengthLong."'".
		", `b_wantTattoosNone` = '".$b_wantTattoosNone."'".
		", `b_wantTattoosSome` = '".$b_wantTattoosSome."'".
		", `b_wantTattoosAllOver` = '".$b_wantTattoosAllOver."'".
		", `b_wantLooksUgly` = '".$b_wantLooksUgly."'".
		", `b_wantLooksPlain` = '".$b_wantLooksPlain."'".
		", `b_wantLooksQuirks` = '".$b_wantLooksQuirks."'".
		", `b_wantLooksAverage` = '".$b_wantLooksAverage."'".
		", `b_wantLooksAttractive` = '".$b_wantLooksAttractive."'".
		", `b_wantLooksHottie` = '".$b_wantLooksHottie."'".
		", `b_wantLooksSuperModel` = '".$b_wantLooksSuperModel."'".
		", `b_wantIntelligenceSlow` = '".$b_wantIntelligenceSlow."'".
		", `b_wantIntelligenceBitSlow` = '".$b_wantIntelligenceBitSlow."'".
		", `b_wantIntelligenceAverage` = '".$b_wantIntelligenceAverage."'".
		", `b_wantIntelligenceFaster` = '".$b_wantIntelligenceFaster."'".
		", `b_wantIntelligenceGenius` = '".$b_wantIntelligenceGenius."'".
		", `b_wantBedroomPersonalityPassive` = '".$b_wantBedroomPersonalityPassive."'".
		", `b_wantBedroomPersonalityShy` = '".$b_wantBedroomPersonalityShy."'".
		", `b_wantBedroomPersonalityConfident` = '".$b_wantBedroomPersonalityConfident."'".
		", `b_wantBedroomPersonalityAggressive` = '".$b_wantBedroomPersonalityAggressive."'".
		", `b_wantPubicHairShaved` = '".$b_wantPubicHairShaved."'".
		", `b_wantPubicHairTrimmed` = '".$b_wantPubicHairTrimmed."'".
		", `b_wantPubicHairAverage` = '".$b_wantPubicHairAverage."'".
		", `b_wantPubicHairNatural` = '".$b_wantPubicHairNatural."'".
		", `b_wantPubicHairHairy` = '".$b_wantPubicHairHairy."'".
		", `b_wantPenisSizeTiny` = '".$b_wantPenisSizeTiny."'".
		", `b_wantPenisSizeSkinny` = '".$b_wantPenisSizeSkinny."'".
		", `b_wantPenisSizeAverage` = '".$b_wantPenisSizeAverage."'".
		", `b_wantPenisSizeThick` = '".$b_wantPenisSizeThick."'".
		", `b_wantPenisSizeHuge` = '".$b_wantPenisSizeHuge."'".
		", `b_wantBodyHairSmooth` = '".$b_wantBodyHairSmooth."'".
		", `b_wantBodyHairAverage` = '".$b_wantBodyHairAverage."'".
		", `b_wantBodyHairHairy` = '".$b_wantBodyHairHairy."'".
		", `b_wantBreastSizeTiny` = '".$b_wantBreastSizeTiny."'".
		", `b_wantBreastSizeSmall` = '".$b_wantBreastSizeSmall."'".
		", `b_wantBreastSizeAverage` = '".$b_wantBreastSizeAverage."'".
		", `b_wantBreastSizeLarge` = '".$b_wantBreastSizeLarge."'".
		", `b_wantBreastSizeHuge` = '".$b_wantBreastSizeHuge."'".
		", `b_wantSafeSex` = '".$b_wantSafeSex."'".
		", `b_wantBarebackSex` = '".$b_wantBarebackSex."'".
		", `b_wantOralGive` = '".$b_wantOralGive."'".
		", `b_wantOralReceive` = '".$b_wantOralReceive."'".
		", `b_wantAnalTop` = '".$b_wantAnalTop."'".
		", `b_wantAnalBottom` = '".$b_wantAnalBottom."'".
		", `b_wantFilming` = '".$b_wantFilming."'".
		", `b_wantVoyeur` = '".$b_wantVoyeur."'".
		", `b_wantExhibitionist` = '".$b_wantExhibitionist."'".
		", `b_wantRoleplay` = '".$b_wantRoleplay."'".
		", `b_wantSpanking` = '".$b_wantSpanking."'".
		", `b_wantDom` = '".$b_wantDom."'".
		", `b_wantSub` = '".$b_wantSub."'".
		", `b_wantStrapon` = '".$b_wantStrapon."'".
		", `b_wantCuckold` = '".$b_wantCuckold."'".
		", `b_wantFurry` = '".$b_wantFurry."'".
		
		", `b_whereMyPlace` = '".$b_whereMyPlace."'".
		", `b_whereYouHost` = '".$b_whereYouHost."'".
		", `b_whereCarDate` = '".$b_whereCarDate."'".
		", `b_whereHotelIPay` = '".$b_whereHotelIPay."'".
		", `b_whereHotelYouPay` = '".$b_whereHotelYouPay."'".
		", `b_whereHotelSplit` = '".$b_whereHotelSplit."'".
		", `b_whereBarClub` = '".$b_whereBarClub."'".
		", `b_whereGymSauna` = '".$b_whereGymSauna."'".
		", `b_whereNudeBeach` = '".$b_whereNudeBeach."'".
		", `b_whereOther` = '".$b_whereOther."'".
		", `b_mutualLongTerm` = '".$b_mutualLongTerm."'".
		", `b_mutualNoStrings` = '".$b_mutualNoStrings."'".
		", `b_mutualWearMask` = '".$b_mutualWearMask."'".
		", `b_mutualOnlyPlaySafe` = '".$b_mutualOnlyPlaySafe."'".
		", `b_mutualProofOfTesting` = '".$b_mutualProofOfTesting."'".
		", `b_noCigs` = '".$b_noCigs."'".
		", `b_noLightDrink` = '".$b_noLightDrink."'".
		", `b_noHeavyDrink` = '".$b_noHeavyDrink."'".
		", `b_noMarijuana` = '".$b_noMarijuana."'".
		", `b_noPsychedelics` = '".$b_noPsychedelics."'".
		", `b_noDrugs` = '".$b_noDrugs."'".
		", `b_noHerpes` = '".$b_noHerpes."'".
		", `b_noWarts` = '".$b_noWarts."'".
		", `b_noHepatitis` = '".$b_noHepatitis."'".
		", `b_noOtherSTIs` = '".$b_noOtherSTIs."'".
		", `b_noHIV` = '".$b_noHIV."'".
		", `b_noMarriedTheyKnow` = '".$b_noMarriedTheyKnow."'".
		", `b_noMarriedSecret` = '".$b_noMarriedSecret."'".
		", `b_noPoly` = '".$b_noPoly."'".
		", `b_noDisabled` = '".$b_noDisabled."'".
		", `publicText` = '".$publicText."'".
		", `privateText` = '".$privateText."'".
		", `firstName` = '".$firstName."'".
		" WHERE ".$dbname.".users.id = ".$dbresults['id'].";";

		//-------------------------------------------------
		// CLANG!!!
		//-------------------------------------------------
		if(!mysqli_query($db,$dbquerystring))exit(mysqli_error($db));

		//done with the db
		mysqli_close($db);

    }

    //=========================================================================================
    function updateAllVariablesFromPOSTData()
    {//=========================================================================================

		include("_debug.php");
		include("_globals.php");
        include("_secrets.php");
		
		$db = mysqli_connect($dburl,$dbuser,$dbpass);
		if(!$db)exit(mysqli_connect_error());

		//-------------------------------------------------
		// die if not filled in, validator should prevent this.
		//-------------------------------------------------
		if(!isset($_POST['postalCode'])||empty($_POST['postalCode']))exit('postalCode'); else $postalCode= mysqli_escape_string($db,strip_tags($_POST['postalCode']));
		if(!isset($_POST['country'])||empty($_POST['country']))exit('country'); else $country= mysqli_escape_string($db,strip_tags($_POST['country']));

		//-------------------------------------------------
		//-------------------------------------------------
		//-------------------------------------------------
		//-------------------------------------------------
		
		// lat and lon get set here.
		
		//-------------------------------------------------
		//-------------------------------------------------
		//-------------------------------------------------
		//-------------------------------------------------
		
		//set isoCountryCode from country POST data
		include("_countryCodes.php");
		$isoCountryCode = getCountryCodeFromCountryString($country);
		
		//connect to db to fill in lat, lon, city, state
		$dbquerystring = "SELECT lat, lon, city, state FROM ".$dbname.".zipgeoworld WHERE isoCountryCode='{$isoCountryCode}' AND postalCode='{$postalCode}'";
			
		$dbquery = mysqli_query($db,$dbquerystring);
		$results = mysqli_fetch_assoc($dbquery);
		
		$lat=0;
		
		$city="";
		
		if($dbquery==null || $results==null)
		{
		
			//fall back to google api.

			$url= file_get_contents('http://maps.googleapis.com/maps/api/geocode/xml?address='.rawurlencode(utf8_encode($country.', '.$postalCode)).'&sensor=false'); 
			
			//$url=null;
			
			if($url!=null)
			{
				//echo "GeoCoded via Google API";
				
				$xml = simplexml_load_string($url); 
				$tracks = $xml->result; 
				$long_name = "";
				$short_name = "";
			
				foreach($tracks as $key) 
				{ 
					foreach($key->address_component as $val)
					{ 
						$long_name= $val->long_name; 
						$short_name= $val->short_name; 
						if($val->type=="locality") 
						{ 
							if($city=="")$city=$long_name;
							if($city==null)$city=$short_name; 
						}
						else 
						if($val->type=="administrative_area_level_1") 
						{ 
							$state=$short_name; 
							if($state==null)$state=$long_name; 
						}
						else if($val->type=="administrative_area_level_2") 
						{ 
							if($city=="")$city=$long_name; 
							if($city==null)$city=$short_name; 
						} 
					}

					foreach($key->geometry as $geo)
					{ 
						foreach($geo->location as $loc)
						{
							if($lat==0)
							{
								$lat = $loc->lat;
								$lon = $loc->lng;
							}
						}
					}
				} 
			}
			else
			{
				//yahoo geocode
				$url= file_get_contents('http://where.yahooapis.com/geocode?appid=GV3rhxTV34EzXckaBhdvvXTbModL4Sumbd5BJMUxl8_kdFwkxLDr9MH.VFg-&location='.rawurlencode(utf8_encode($country.', '.$postalCode)).''); 
			
				if($url!=null)
				{
					//echo "GeoCoded via Yahoo API";
				
					$xml = simplexml_load_string($url); 
					$tracks = $xml->Result; 
			
					foreach($tracks as $key) 
					{ 

						if($lat==0)
						{
							$lat = $key->latitude;
							$lon = $key->longitude;
							$city = $key->city;
							$state = $key->statecode;
						}

					} 
				}
			}
		}
		else
		{
			$lat = $results['lat'];
			$lon =  $results['lon'];
			$city =  $results['city'];
			$state =  $results['state'];
		}
		
		if($state=="XX")$state=$isoCountryCode;
		
		if($city=="")$city= $postalCode;
		
		//if(!isset($_POST['isoCountryCode'            ])||empty($_POST['isoCountryCode'            ]))exit('isoCountryCode'                         ); else $isoCountryCode                         = mysqli_escape_string($db,$_POST['isoCountryCode'                         ]);
		//if(!isset($_POST['city'            ])||empty($_POST['city'            ]))exit('city'                         ); else $city                         = mysqli_escape_string($db,$_POST['city'                         ]);
		//if(!isset($_POST['state'            ])||empty($_POST['state'            ]))exit('state'                         ); else $state                         = mysqli_escape_string($db,$_POST['state'                         ]);
		//if(!isset($_POST['lat'            ])||empty($_POST['lat'            ]))exit('lat'                         ); else $lat                         = mysqli_escape_string($db,$_POST['lat'                         ]);
		//if(!isset($_POST['lon'            ])||empty($_POST['lon'            ]))exit('lon'                         ); else $lon                         = mysqli_escape_string($db,$_POST['lon'                         ]);
		if(!isset($_POST['distance'           ])||empty($_POST['distance'           ]))exit('distance'                        ); else $distance                        = mysqli_escape_string($db,$_POST['distance'                        ]);
		if(!isset($_POST['birthdayMonth'      ])||empty($_POST['birthdayMonth'      ]))exit('birthdayMonth'                   ); else $birthdayMonth                   = mysqli_escape_string($db,$_POST['birthdayMonth'                   ]);
		if(!isset($_POST['birthdayDay'        ])||empty($_POST['birthdayDay'        ]))exit('birthdayDay'                     ); else $birthdayDay                     = mysqli_escape_string($db,$_POST['birthdayDay'                     ]);
		if(!isset($_POST['birthdayYear'       ])||empty($_POST['birthdayYear'       ]))exit('birthdayYear'                    ); else $birthdayYear                    = mysqli_escape_string($db,$_POST['birthdayYear'                    ]);
		
		if($birthdayMonth<1 || $birthdayMonth>12)$birthdayMonth=1;
		if($birthdayDay<1 || $birthdayDay>31)$birthdayDay=1;
		if($birthdayYear<1900 || $birthdayYear>2012 )$birthdayYear=1900;
		
		if(!isset($_POST['gender'             ])||empty($_POST['gender'             ]))exit('gender'                          ); else $gender                          = mysqli_escape_string($db,$_POST['gender'                          ]);
		if(!isset($_POST['body'               ])||empty($_POST['body'               ]))exit('body'                            ); else $body                            = mysqli_escape_string($db,$_POST['body'                            ]);
		if(!isset($_POST['ethnicity'          ])||empty($_POST['ethnicity'          ]))exit('ethnicity'                       ); else $ethnicity                       = mysqli_escape_string($db,$_POST['ethnicity'                       ]);
		if(!isset($_POST['hairColor'          ])||empty($_POST['hairColor'          ]))exit('hairColor'                       ); else $hairColor                       = mysqli_escape_string($db,$_POST['hairColor'                       ]);
		if(!isset($_POST['hairLength'         ])||empty($_POST['hairLength'         ]))exit('hairLength'                      ); else $hairLength                      = mysqli_escape_string($db,$_POST['hairLength'                      ]);
		if(!isset($_POST['tattoos'            ])||empty($_POST['tattoos'            ]))exit('tattoos'                         ); else $tattoos                         = mysqli_escape_string($db,$_POST['tattoos'                         ]);
		if(!isset($_POST['overallLooks'       ])||empty($_POST['overallLooks'       ]))exit('overallLooks'                    ); else $overallLooks                    = mysqli_escape_string($db,$_POST['overallLooks'                    ]);
		if(!isset($_POST['intelligence'       ])||empty($_POST['intelligence'       ]))exit('intelligence'                    ); else $intelligence                    = mysqli_escape_string($db,$_POST['intelligence'                    ]);
		if(!isset($_POST['bedroomPersonality' ])||empty($_POST['bedroomPersonality' ]))exit('bedroomPersonality'              ); else $bedroomPersonality              = mysqli_escape_string($db,$_POST['bedroomPersonality'              ]);
		if(!isset($_POST['pubicHair'          ])||empty($_POST['pubicHair'          ]))exit('pubicHair'                       ); else $pubicHair                       = mysqli_escape_string($db,$_POST['pubicHair'                       ]);
		
		if(isset($_POST['penisSize'                         ]))$penisSize                         = mysqli_escape_string($db,$_POST['penisSize'                       ]);
		if(isset($_POST['breastSize'                        ]))$breastSize                        = mysqli_escape_string($db,$_POST['breastSize'                      ]);
		if(isset($_POST['bodyHair'                          ]))$bodyHair                          = mysqli_escape_string($db,$_POST['bodyHair'                        ]);
		
		if(!isset($_POST['wantAgeFrom'         ])||empty($_POST['wantAgeFrom'         ]))exit('wantAgeFrom'                      ); else $wantAgeFrom                      = mysqli_escape_string($db,strip_tags($_POST['wantAgeFrom'                         ]));
		if(!isset($_POST['wantAgeTo'           ])||empty($_POST['wantAgeTo'           ]))exit('wantAgeTo'                        ); else $wantAgeTo                        = mysqli_escape_string($db,strip_tags($_POST['wantAgeTo'                        ]));
		

		//force age >= 18
		if($wantAgeFrom<18)$wantAgeFrom = 18;
		if($wantAgeTo<18)$wantAgeTo = 18;
		if($wantAgeFrom>120)$wantAgeFrom = 120;
		if($wantAgeTo>120)$wantAgeTo = 120;
				
		if(getAge($birthdayMonth,$birthdayDay,$birthdayYear)<18)$birthdayYear=1900;
		
		//-------------------------------------------------
		// all bools are init to 0 above. set them to 1 if the post is set
		//-------------------------------------------------
		
		if(isset($_POST['b_smokeCigarettes'                 ]))$b_smokeCigarettes                 = 1;
		if(isset($_POST['b_lightDrinker'                    ]))$b_lightDrinker                    = 1;
		if(isset($_POST['b_heavyDrinker'                    ]))$b_heavyDrinker                    = 1;
		if(isset($_POST['b_smokeMarijuana'                  ]))$b_smokeMarijuana                  = 1;
		if(isset($_POST['b_psychedelics'                      ]))$b_psychedelics                      = 1;
		if(isset($_POST['b_otherDrugs'                      ]))$b_otherDrugs                      = 1;

		if(isset($_POST['b_haveWarts'                       ]))$b_haveWarts                       = 1;
		if(isset($_POST['b_haveHerpes'                      ]))$b_haveHerpes                      = 1;
		if(isset($_POST['b_haveHepatitis'                   ]))$b_haveHepatitis                   = 1;
		if(isset($_POST['b_haveOtherSTI'                    ]))$b_haveOtherSTI                    = 1;
		if(isset($_POST['b_haveHIV'                         ]))$b_haveHIV                         = 1;

		if(isset($_POST['b_marriedTheyKnow'                 ]))$b_marriedTheyKnow                 = 1;
		if(isset($_POST['b_marriedSecret'                   ]))$b_marriedSecret                   = 1;
		if(isset($_POST['b_poly'                            ]))$b_poly                            = 1;
		if(isset($_POST['b_disability'                      ]))$b_disability                      = 1;

		if(isset($_POST['b_wantGenderMan'                   ]))$b_wantGenderMan                   = 1;
		if(isset($_POST['b_wantGenderWoman'                 ]))$b_wantGenderWoman                 = 1;
		if(isset($_POST['b_wantGenderTSWoman'               ]))$b_wantGenderTSWoman               = 1;
		if(isset($_POST['b_wantGenderTSMan'                 ]))$b_wantGenderTSMan                 = 1;
		if(isset($_POST['b_wantGenderCDWoman'               ]))$b_wantGenderCDWoman               = 1;
		if(isset($_POST['b_wantGenderCDMan'                 ]))$b_wantGenderCDMan                 = 1;
		if(isset($_POST['b_wantGenderCoupleMF'              ]))$b_wantGenderCoupleMF              = 1;
		if(isset($_POST['b_wantGenderCoupleMM'              ]))$b_wantGenderCoupleMM              = 1;
		if(isset($_POST['b_wantGenderCoupleFF'              ]))$b_wantGenderCoupleFF              = 1;
		if(isset($_POST['b_wantGenderGroup'                 ]))$b_wantGenderGroup                 = 1;

		if(isset($_POST['b_wantBodyTiny'                    ]))$b_wantBodyTiny                    = 1;
		if(isset($_POST['b_wantBodySlim'                    ]))$b_wantBodySlim                    = 1;
		if(isset($_POST['b_wantBodyAverage'                 ]))$b_wantBodyAverage                 = 1;
		if(isset($_POST['b_wantBodyMuscular'                ]))$b_wantBodyMuscular                = 1;
		if(isset($_POST['b_wantBodyCurvy'                   ]))$b_wantBodyCurvy                   = 1;
		if(isset($_POST['b_wantBodyThick'                   ]))$b_wantBodyThick                   = 1;
		if(isset($_POST['b_wantBodyBBW'                     ]))$b_wantBodyBBW                     = 1;

		if(isset($_POST['b_wantEthnicityWhite'              ]))$b_wantEthnicityWhite              = 1;
		if(isset($_POST['b_wantEthnicityAsian'              ]))$b_wantEthnicityAsian              = 1;
		if(isset($_POST['b_wantEthnicityLatino'             ]))$b_wantEthnicityLatino             = 1;
		if(isset($_POST['b_wantEthnicityIndian'             ]))$b_wantEthnicityIndian             = 1;
		if(isset($_POST['b_wantEthnicityBlack'              ]))$b_wantEthnicityBlack              = 1;
		if(isset($_POST['b_wantEthnicityOther'              ]))$b_wantEthnicityOther              = 1;

		if(isset($_POST['b_wantHairColorLight'              ]))$b_wantHairColorLight              = 1;
		if(isset($_POST['b_wantHairColorMedium'             ]))$b_wantHairColorMedium             = 1;
		if(isset($_POST['b_wantHairColorDark'               ]))$b_wantHairColorDark               = 1;
		if(isset($_POST['b_wantHairColorRed'                ]))$b_wantHairColorRed                = 1;
		if(isset($_POST['b_wantHairColorGray'               ]))$b_wantHairColorGray               = 1;
		if(isset($_POST['b_wantHairColorOther'              ]))$b_wantHairColorOther              = 1;

		if(isset($_POST['b_wantHairLengthBald'              ]))$b_wantHairLengthBald              = 1;
		if(isset($_POST['b_wantHairLengthShort'             ]))$b_wantHairLengthShort             = 1;
		if(isset($_POST['b_wantHairLengthMedium'            ]))$b_wantHairLengthMedium            = 1;
		if(isset($_POST['b_wantHairLengthLong'              ]))$b_wantHairLengthLong              = 1;

		if(isset($_POST['b_wantTattoosNone'                 ]))$b_wantTattoosNone                 = 1;
		if(isset($_POST['b_wantTattoosSome'                 ]))$b_wantTattoosSome                 = 1;
		if(isset($_POST['b_wantTattoosAllOver'              ]))$b_wantTattoosAllOver              = 1;

		if(isset($_POST['b_wantLooksUgly'                   ]))$b_wantLooksUgly                   = 1;
		if(isset($_POST['b_wantLooksPlain'                  ]))$b_wantLooksPlain                  = 1;
		if(isset($_POST['b_wantLooksQuirks'                 ]))$b_wantLooksQuirks                 = 1;
		if(isset($_POST['b_wantLooksAverage'                ]))$b_wantLooksAverage                = 1;
		if(isset($_POST['b_wantLooksAttractive'             ]))$b_wantLooksAttractive             = 1;
		if(isset($_POST['b_wantLooksHottie'                 ]))$b_wantLooksHottie                 = 1;
		if(isset($_POST['b_wantLooksSuperModel'             ]))$b_wantLooksSuperModel             = 1;

		if(isset($_POST['b_wantIntelligenceSlow'            ]))$b_wantIntelligenceSlow            = 1;
		if(isset($_POST['b_wantIntelligenceBitSlow'         ]))$b_wantIntelligenceBitSlow         = 1;
		if(isset($_POST['b_wantIntelligenceAverage'         ]))$b_wantIntelligenceAverage         = 1;
		if(isset($_POST['b_wantIntelligenceFaster'          ]))$b_wantIntelligenceFaster          = 1;
		if(isset($_POST['b_wantIntelligenceGenius'          ]))$b_wantIntelligenceGenius          = 1;

		if(isset($_POST['b_wantBedroomPersonalityPassive'   ]))$b_wantBedroomPersonalityPassive   = 1;
		if(isset($_POST['b_wantBedroomPersonalityShy'       ]))$b_wantBedroomPersonalityShy       = 1;
		if(isset($_POST['b_wantBedroomPersonalityConfident' ]))$b_wantBedroomPersonalityConfident = 1;
		if(isset($_POST['b_wantBedroomPersonalityAggressive']))$b_wantBedroomPersonalityAggressive= 1;

		if(isset($_POST['b_wantPubicHairShaved'             ]))$b_wantPubicHairShaved             = 1;
		if(isset($_POST['b_wantPubicHairTrimmed'            ]))$b_wantPubicHairTrimmed            = 1;
		if(isset($_POST['b_wantPubicHairAverage'            ]))$b_wantPubicHairAverage            = 1;
		if(isset($_POST['b_wantPubicHairNatural'            ]))$b_wantPubicHairNatural            = 1;
		if(isset($_POST['b_wantPubicHairHairy'              ]))$b_wantPubicHairHairy              = 1;

		if(isset($_POST['b_wantPenisSizeTiny'               ]))$b_wantPenisSizeTiny               = 1;
		if(isset($_POST['b_wantPenisSizeSkinny'             ]))$b_wantPenisSizeSkinny             = 1;
		if(isset($_POST['b_wantPenisSizeAverage'            ]))$b_wantPenisSizeAverage            = 1;
		if(isset($_POST['b_wantPenisSizeThick'              ]))$b_wantPenisSizeThick              = 1;
		if(isset($_POST['b_wantPenisSizeHuge'               ]))$b_wantPenisSizeHuge               = 1;

		if(isset($_POST['b_wantBodyHairSmooth'              ]))$b_wantBodyHairSmooth              = 1;
		if(isset($_POST['b_wantBodyHairAverage'             ]))$b_wantBodyHairAverage             = 1;
		if(isset($_POST['b_wantBodyHairHairy'               ]))$b_wantBodyHairHairy               = 1;

		if(isset($_POST['b_wantBreastSizeTiny'              ]))$b_wantBreastSizeTiny              = 1;
		if(isset($_POST['b_wantBreastSizeSmall'             ]))$b_wantBreastSizeSmall             = 1;
		if(isset($_POST['b_wantBreastSizeAverage'           ]))$b_wantBreastSizeAverage           = 1;
		if(isset($_POST['b_wantBreastSizeLarge'             ]))$b_wantBreastSizeLarge             = 1;
		if(isset($_POST['b_wantBreastSizeHuge'              ]))$b_wantBreastSizeHuge              = 1;

		if(isset($_POST['b_wantSafeSex'                     ]))$b_wantSafeSex                     = 1;
		if(isset($_POST['b_wantBarebackSex'                 ]))$b_wantBarebackSex                 = 1;

		if(isset($_POST['b_wantOralGive'                    ]))$b_wantOralGive                    = 1;
		if(isset($_POST['b_wantOralReceive'                 ]))$b_wantOralReceive                 = 1;

		if(isset($_POST['b_wantAnalTop'                     ]))$b_wantAnalTop                     = 1;
		if(isset($_POST['b_wantAnalBottom'                  ]))$b_wantAnalBottom                  = 1;

		if(isset($_POST['b_wantFilming'                     ]))$b_wantFilming                     = 1;
		if(isset($_POST['b_wantVoyeur'                      ]))$b_wantVoyeur                      = 1;
		if(isset($_POST['b_wantExhibitionist'               ]))$b_wantExhibitionist               = 1;

		if(isset($_POST['b_wantRoleplay'                    ]))$b_wantRoleplay                    = 1;
		if(isset($_POST['b_wantSpanking'                    ]))$b_wantSpanking                    = 1;
		if(isset($_POST['b_wantDom'                         ]))$b_wantDom                         = 1;
		if(isset($_POST['b_wantSub'                         ]))$b_wantSub                         = 1;

		if(isset($_POST['b_wantStrapon'                     ]))$b_wantStrapon                     = 1;
		if(isset($_POST['b_wantCuckold'                     ]))$b_wantCuckold                     = 1;
		if(isset($_POST['b_wantFurry'                       ]))$b_wantFurry                       = 1;
		

		if(isset($_POST['b_whereMyPlace'                    ]))$b_whereMyPlace                    = 1;
		if(isset($_POST['b_whereYouHost'                    ]))$b_whereYouHost                    = 1;

		if(isset($_POST['b_whereCarDate'                    ]))$b_whereCarDate                    = 1;
		if(isset($_POST['b_whereHotelIPay'                  ]))$b_whereHotelIPay                  = 1;
		if(isset($_POST['b_whereHotelYouPay'                ]))$b_whereHotelYouPay                = 1;
		if(isset($_POST['b_whereHotelSplit'                 ]))$b_whereHotelSplit                 = 1;
		if(isset($_POST['b_whereBarClub'                    ]))$b_whereBarClub                    = 1;
		if(isset($_POST['b_whereGymSauna'                   ]))$b_whereGymSauna                   = 1;
		if(isset($_POST['b_whereNudeBeach'                  ]))$b_whereNudeBeach                  = 1;
		if(isset($_POST['b_whereOther'                      ]))$b_whereOther                      = 1;

		if(isset($_POST['b_mutualLongTerm'                  ]))$b_mutualLongTerm                  = 1;
		if(isset($_POST['b_mutualNoStrings'                 ]))$b_mutualNoStrings                 = 1;
		if(isset($_POST['b_mutualWearMask'                  ]))$b_mutualWearMask                  = 1;

		if(isset($_POST['b_mutualOnlyPlaySafe'              ]))$b_mutualOnlyPlaySafe              = 1;
		if(isset($_POST['b_mutualProofOfTesting'            ]))$b_mutualProofOfTesting            = 1;

		if(isset($_POST['b_noCigs'                          ]))$b_noCigs                          = 1;
		if(isset($_POST['b_noLightDrink'                    ]))$b_noLightDrink                    = 1;
		if(isset($_POST['b_noHeavyDrink'                    ]))$b_noHeavyDrink                    = 1;
		if(isset($_POST['b_noMarijuana'                     ]))$b_noMarijuana                     = 1;

		if(isset($_POST['b_noPsychedelics'                         ]))$b_noPsychedelics                         = 1;
		if(isset($_POST['b_noDrugs'                         ]))$b_noDrugs                         = 1;

		if(isset($_POST['b_noHerpes'                        ]))$b_noHerpes                        = 1;
		if(isset($_POST['b_noWarts'                         ]))$b_noWarts                        = 1;
		if(isset($_POST['b_noHepatitis'                     ]))$b_noHepatitis                     = 1;
		if(isset($_POST['b_noOtherSTIs'                     ]))$b_noOtherSTIs                     = 1;
		if(isset($_POST['b_noHIV'                           ]))$b_noHIV                           = 1;

		if(isset($_POST['b_noMarriedTheyKnow'               ]))$b_noMarriedTheyKnow       = 1;
		if(isset($_POST['b_noMarriedSecret'                 ]))$b_noMarriedSecret           = 1;
		if(isset($_POST['b_noPoly'                          ]))$b_noPoly                          = 1;
		if(isset($_POST['b_noDisabled'                      ]))$b_noDisabled                      = 1;

		$publicText = mysqli_escape_string($db,strip_tags($_POST['publicText'                      ]));
		$privateText = mysqli_escape_string($db,strip_tags($_POST['privateText'                      ]));
		$firstName = mysqli_escape_string($db,strip_tags($_POST['firstName'                      ]));

		if(strlen($publicText)>2000)$publicText = substr($publicText,0,2000);
		if(strlen($privateText)>2000)$privateText = substr($privateText,0,2000);

}
