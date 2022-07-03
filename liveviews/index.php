<?php error_reporting(0);
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);

ob_start();

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
$whitelist = array( '127.0.0.1', '::1');

if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL ^ E_WARNING ^ E_ERROR ^ E_PARSE);
}
else
{
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL ^ E_WARNING ^ E_ERROR ^ E_PARSE);
}

if (( !isset($_REQUEST['lookformoves'])) && ( !isset($_REQUEST['livemove'])) && ( !isset($_REQUEST['localmove'])) ) {
    die("This file needs to be included into a viewer.");
} 
$fen ="";
//$defaultfen="13c1c21/1c1S1n1ic1/ca1phs3c/1p71/c2P5c/C3PH3C/11p1ppp21/1C1N1CH1PC/11C5MC/11I4A11 ~w w0 () - 0 54";
//$defaultfen="1c6c1/cmhgsnghmc/1pppppppp1/1a6i1/c3cc3c/C3CC3C/1I6A1/1PPPPPPPP1/CMHGNSGHMC/1C6C1 ~bwc w0 () - 0 1";
$defaultfen="13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1";
$defaultfenSR="13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1";
$defaultfenSY="13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1";

$gamechoice="SR";
$blackplayerassigned=false;
$blackplayer=0;
$playertype=1;
$sharemoves =1;$cookiefen_newfensame=0;
$gametype="";$serverrequesttype="";
$bresult="";
$systemlivegameid="";
$filewhitecookie=""; $fileblackcookie="";
$filewhitegamecookie=""; $fileblackgamecookie="";
$whitegamecookie=""; $blackamecookie="";
$newmove=0;
$currentmover=""; $writefile=0;
$newgame='0';
$movesmade = "";

$systemcookieset="";//'gid='.$systemlivegameid . ";whitemover=".$whitegamer . ";blackmover=".$blackgamer;
$whitegamecookieset=""; //'gid='.$systemlivegameid.";whitemover=".$whitegamer;
$blackgamecookieset="";//'gid='.$systemlivegameid.";blackmover=".$blackgamer;

$decodedc="";$encodedc=""; $leftover=[];				
$controlled_moves=[];
$cookiecategory="";
	$cookiefen="";$gid;

	function hex2str( $hex ) {
		//return pack('H*', $hex);
		return hex2bin($hex);
	  }
	  
	  function str2hex( $str ) {
		//return array_shift( unpack('H*', $str) );
		return bin2hex( $str) ;	}
	  
//SY SR based on the variable receoved
function cf_setcookie(){
		$gchoice="";
		$whitegamer=substr(str_shuffle("aAbBcCdDeEfFgGhHIjJkLnNqQrRtT347"), 0, 5).substr(md5(time()),1);
		$blackgamer=substr(str_shuffle("aAbBcCdDeEfFgGhHIjJkLnNqQrRtT347"), 0, 5).substr(md5(time()),1);

		if((isset($_REQUEST['lg'])) && (strlen($_REQUEST['lg'])>=1)){

			if((strpos($_REQUEST['lg'].substr(0,1),"1")!==false)|| (strpos($_REQUEST['lg'].substr(0,2),"SY" )!==FALSE)){$GLOBALS['gamechoice']="SY";}
			else {$GLOBALS['gamechoice']="SR";}
		}

		if($GLOBALS['gamechoice']=="SY"){$gchoice=$GLOBALS['gamechoice']="SY";}
		else {$gchoice=$GLOBALS['gamechoice']="SR";}
		$systemlivegameid=$gchoice.str_shuffle("aAcdeEfhHjJkmNnpRrTt347");
		if($gchoice=="SR")	{$fen=$GLOBALS['defaultfenSR'];}
		else if($gchoice=="SY")	{$fen=$GLOBALS['defaultfenSY'];}
		else {$fen=$GLOBALS['defaultfen'];}
 
		$systemcookieset='gid='.$systemlivegameid . ";whitemover=".$whitegamer . ";blackmover=".$blackgamer;
		$whitegamecookieset='gid='.$systemlivegameid.";whitemover=".$whitegamer;
		$blackgamecookieset='gid='.$systemlivegameid.";blackmover=".$blackgamer;

		//$encodedc=base64_encode( "cc=". 'white'.";fen=".$fen.";".$systemcookieset);
		$encodedc=str2hex( "cc=". 'white'.";fen=".$fen.";".$systemcookieset);

		//if file does not exist create....else check if file exists but no cookies matched then set it to black.
		$file = fopen($systemlivegameid,"w");
		fwrite($file,$systemcookieset.PHP_EOL);
		$currentmover='w0';

		fwrite($file,'$newfen='.$fen.PHP_EOL);
		fwrite($file,'$blackplayer=0;'.PHP_EOL);
		fclose($file);
		$newmove=1;
		$writefile=1;
		//echo $encodedc;
		$GLOBALS['systemlivegameid']=$systemlivegameid;
		$GLOBALS['systemcookieset']=$systemcookieset;
		$GLOBALS['whitegamecookieset']=$whitegamecookieset;
		$GLOBALS['blackgamecookieset']=$blackgamecookieset;
		$GLOBALS['newgame']='1';

	}

function check_gamestatus($decodedc): int {
	$leftover= explode(';',$decodedc);
	$status =0;$whiteblackcookie="";$blackcookie="";$nonecookie="";
	$cookiefen="";	$cookiecategory=""; $gid="";

	for($i=0;$i<sizeof($leftover);$i++){
		if(  (strpos($leftover[$i], 'cc=') !== false)) { $cookiecategory=explode('cc=',$leftover[$i])[1];	}
		if(  (strpos($leftover[$i], 'fen=') !== false)) { $cookiefen=explode('fen=',$leftover[$i])[1];	}
		if(  (strpos($leftover[$i], 'gid=') !== false)) { $gid=explode('gid=',$leftover[$i])[1];}
}


	if(strlen($decodedc)>1){
	
		if(($cookiecategory=="") ||($cookiecategory==null))
			$cookiecategory="white";

			if(($cookiefen=="") ||($cookiefen==null)){
			//$cookiefen="1c2ai2c1/cmhgsnghmc/cppppppppc/181/c8c/C8C/181/CPPPPPPPPC/CMHGNSGHMC/1C2IA2C1 ~bwc w0 () - 0 1";
			$cookiefen=$GLOBALS['defaultfen'];
			$status =0;
		}

		if(($gid=="") ||($gid==null))
			{$status =0;return $status;}
		else {
		//$fulllivegameid = base64_decode($gid);//explode($gid,$decodedc)[1];
		$fulllivegameid = hex2str($gid);//explode($gid,$decodedc)[1];

		$fulllivegameidArr = explode(";" ,$fulllivegameid);
	
		for($i=0;$i<sizeof($fulllivegameidArr);$i=$i+1){		
			//$onlylivegameid =  explode( 'gid=',$fulllivegameid)[0];
			if(  (strpos($fulllivegameidArr[$i], 'whitemover=') !== false)) { $whiteblackcookie=$fulllivegameidArr[$i];}
			else if(  (strpos($fulllivegameidArr[$i], 'blackmover=') !== false)) { $blackcookie=$fulllivegameidArr[$i];}
			else if(  (strpos($fulllivegameidArr[$i], 'nonemover=') !== false)) { $nonecookie=$fulllivegameidArr[$i];}
		}

		if(($whiteblackcookie!=null)&&($whiteblackcookie!=="")) 	{$status =1;}
		else if(($blackcookie!=null)&&($blackcookie!=="")) {$status =2;}
		else if(($nonecookie!=null)) {$status =3;}
		}
	}
	return $status; //1white 2black 3normal
}
 if (!isset($_REQUEST['livegameid'])) ///check if user already had some pending game // play invitation game if no pending game
	{
		cf_setcookie();
	}
else if((isset($_REQUEST['livegameid'])) &&((isset($_REQUEST['lookformoves']))&&(($_REQUEST['lookformoves']!=null) &&($_REQUEST['lookformoves']!="" ))))
	{
		$encodedc=$_REQUEST['livegameid'];
		//check size also
		//$decodedc=base64_decode($encodedc);
		$decodedc=hex2str($encodedc);
		$status=check_gamestatus($decodedc);

		
			$splitted = explode( 'gid=',$unknowngamecookie);
			$livegameid = $splitted[1];
			$onlylivegameid =  explode( ';',$livegameid)[0];

			if((isset($_REQUEST['lg'])) && (strlen($_REQUEST['lg'])>=1)){

				if((strpos($_REQUEST['lg'].substr(0,1),"1")!==false)|| (strpos($_REQUEST['lg'].substr(0,2),"SY" )!==false)){$gamechoice="SY";}
				else {$gamechoice="SR";}

			}
						
			if(strlen($onlylivegameid)>2){
				$gamechoicepos=strpos($onlylivegameid, 'SY');
				if (($gamechoicepos !== false) && ($gamechoicepos==0))
					{$gamechoice="SY";}
				else {$gamechoice="SR";}
			}

			$systemlivegameid= $onlylivegameid.';';	
			$systemcookie=$unknowngamecookie;
			$whiteblackcookie=explode( ';',$livegameid)[1];//'gid='.$onlylivegameid.';blackmover='.$filewhitecookie.';';
		if(file_exists($systemlivegameid)){
			$data = file($systemlivegameid); // reads an array of lines
			$reading = fopen($systemlivegameid, 'r');
			//$writing = fopen($systemlivegameid.'.tmp.txt', 'w');
			$replaced = false;	$importedmatched=false;
			$newmove=0;	$matched=0;
	
			if(strpos($whiteblackcookie,';whitemover=')!== false)
			///check if user already had some pending game // play invitation game if no pending game
			{
				while (!feof($reading)) {
					$line = rtrim(fgets($reading),"\r\n");
					if(($matched==1)	&&(stristr($line,'$currentfen='))){
						$line = '$currentfen='.explode(';', explode( '$currentfen=',$line)[1])[0];
						$movesmade = $line.explode(' ', explode( '-',$line)[1])[1];
						}
					else if ((stristr($line,'$currentfen='))) {
						$replaced = true; $importedmatched=true;
						$fen=explode(';', explode( '$currentfen=',$line)[1])[0];
						$borderstatus=explode(' ', explode( ' ',$line)[1])[0];
						$currentmover=explode(' ', explode( ' ',$line)[2])[0];
						$movesmade = explode(' ', explode( '-',$line)[1])[2];
					}
					//fputs($writing, $line.PHP_EOL);
					else if (strpos($line, 'gid=') !== false) {
						$splitted = explode( 'gid=',$line);
					}
					else if (strpos($line, 'blackplayer=') !== false) {
							$newgame='1';
					};
				}
			}
			else if(strpos($whiteblackcookie,';blackmover=')!== false)
			///check if user already had some pending game // play invitation game if no pending game
			{
					while (!feof($reading)) {
						$line = rtrim(fgets($reading),"\r\n");
						if(($matched==1)	&&(stristr($line,'$currentfen='))){
							$line = '$currentfen='.explode(';', explode( '$currentfen=',$line)[1])[0];
							$movesmade = explode(' ', explode( '-',$line)[1])[2];
						}
						else if ((stristr($line,'$currentfen='))) {
							$fen=explode(';', explode( '$currentfen=',$line)[1])[0];
							$borderstatus=explode(' ', explode( ' ',$line)[1])[0];
							$currentmover=explode(' ', explode( ' ',$line)[2])[0];
							$movesmade = explode(' ', explode( '-',$line)[1])[2];

						}
						 // fputs($writing, $line.PHP_EOL);
	
						else if (strpos($line, 'gid=') !== false) {
						}
						else if (strpos($line, 'blackplayer=') !== false) {
							$newgame='1';
						};					
					}
			}
			fclose($reading);
			}
			$result="";
			if( ((($currentmover=='b0') || ($currentmover=='w1')|| ($currentmover=='b2'))))
			{
				$result= "Black To Move";
				$result= "2";
			}
			else if(
			((($currentmover=='w0') || ($currentmover=='b1')|| ($currentmover=='w2')))){
				$result= "White To Move";
				$result= "1";
			}
			//echo $result;
			//return $result;
	}
else if((isset($_COOKIE['livegameid'])) &&((!isset($_REQUEST['livemove']))||(($_REQUEST['livemove']==null) ||($_REQUEST['livemove']=="" ))))
	{
	$unknowngamecookie=htmlspecialchars($_COOKIE['livegameid']);
	//fetch the latest current move.
	//echo "else if ".$_COOKIE['livegameid'];
		$splitted = explode( 'gid=',$unknowngamecookie);
		$livegameid = $splitted[1];
		$onlylivegameid =  explode( ';',$livegameid)[0];

		if((isset($_REQUEST['lg'])) && (strlen($_REQUEST['lg'])>=1)){

			if((strpos($_REQUEST['lg'].substr(0,1),"1")!==false)|| (strpos($_REQUEST['lg'].substr(0,2),"SY" )!==false)){$gamechoice="SY";}
			else {$gamechoice="SR";}
		}

		if(strlen($onlylivegameid)>2){
			$gamechoicepos=strpos($onlylivegameid, 'SY');
			if (($gamechoicepos !== false) && ($gamechoicepos==0))
				{$gamechoice="SY";}
			else {$gamechoice="SR";}		
		}

		$systemlivegameid= $onlylivegameid.';';
		$systemcookie=$unknowngamecookie;		
		$whiteblackcookie=explode( ';',$livegameid)[1];//'gid='.$onlylivegameid.';blackmover='.$filewhitecookie.';';
		if(file_exists($systemlivegameid)){
		$data = file($systemlivegameid); // reads an array of lines
		$reading = fopen($systemlivegameid, 'r');
		//$writing = fopen($systemlivegameid.'.tmp.txt', 'w');
		$replaced = false;	$importedmatched=false;
		$newmove=0;	$matched=0;

		if(strpos($whiteblackcookie,'whitemover')!== false)
		///check if user already had some pending game // play invitation game if no pending game
		{
			$playertype=1;
			$whitegamecookie=$systemcookie;
	
			//setcookie('livegameid', $whitegamecookie);
  			//$_COOKIE['livegameid']= $whitegamecookie;
			while (!feof($reading)) {
				$line = rtrim(fgets($reading),"\r\n");
				if(($matched==1)	&&(stristr($line,'$currentfen='))){
					$line = '$currentfen='.explode(';', explode( '$currentfen=',$line)[1])[0];
					$movesmade = explode(' ', explode( '-',$line)[1])[2];

					}
				else if ((stristr($line,'$currentfen='))) {
					$replaced = true; $importedmatched=true;
					$fen=explode(';', explode( '$currentfen=',$line)[1])[0];
					$borderstatus=explode(' ', explode( ' ',$line)[1])[0];
					$currentmover=explode(' ', explode( ' ',$line)[2])[0];
					$movesmade = explode(' ', explode( '-',$line)[1])[2];

					}
				//fputs($writing, $line.PHP_EOL);
				else if (strpos($line, 'gid=') !== false) {
					$splitted = explode( 'gid=',$line);
					$fulllivegameid = 'gid='.$splitted[1];
					//$onlylivegameid =  explode( 'gid=',$fulllivegameid)[0];
					$whiteblackcookie=';whitemover='.explode( ';blackmover=',$fulllivegameid)[1];
					$blackcookie=';blackmover='.explode( ';blackmover=',$fulllivegameid)[1];
					$whitecookie=';whitemover='.explode( ';blackmover=',explode( ';blackmover=',$fulllivegameid)[0])[1];

					$whitelivegameid='gid='.$systemlivegameid.$whitecookie;
					//$_COOKIE['livegameid']=$whitelivegameid;
					$whiteplayerassigned=true;
					$playertype=1;
					$fileblackgamecookie=$whitelivegameid;
				}
				else if (strpos($line, 'blackplayer=') !== false) {
					$newgame='1';
				};
			}

			if((isset($_COOKIE['LiveStepType'])==true) &&($_COOKIE['LiveStepType']!="")&&
			(($_COOKIE['LiveStepType']=="white"))) {
			//check with file cookie and if good then show the current fen
			}else {

			}
		}
		else if(strpos($whiteblackcookie,'blackmover')!== false)
		///check if user already had some pending game // play invitation game if no pending game
		{
			$playertype=2;
			$blackgamecookie=$systemcookie;
			//setcookie('livegameid', $blackgamecookie);
			//setcookie('LiveStepType', 'black');
			//$_COOKIE['livegameid']= $blackgamecookie;
			//$_COOKIE['LiveStepType']='black';

				while (!feof($reading)) {
					$line = rtrim(fgets($reading),"\r\n");
					if(($matched==1)	&&(stristr($line,'$currentfen='))){
						$line = '$currentfen='.explode(';', explode( '$currentfen=',$line)[1])[0];
						$movesmade = explode(' ', explode( '-',$line)[1])[2];

						}
					else if ((stristr($line,'$currentfen='))) {
						$fen=explode(';', explode( '$currentfen=',$line)[1])[0];
						$borderstatus=explode(' ', explode( ' ',$line)[1])[0];
						$currentmover=explode(' ', explode( ' ',$line)[2])[0];
						$movesmade = explode(' ', explode( '-',$line)[1])[2];

					}
					 // fputs($writing, $line.PHP_EOL);

					if (strpos($line, 'gid=') !== false) {
						$splitted = explode( 'gid=',$line);
						$fulllivegameid = 'gid='.$splitted[1];
						//$onlylivegameid =  explode( 'gid=',$fulllivegameid)[0];
						$whiteblackcookie=';whitemover='.explode( ';blackmover=',$fulllivegameid)[1];
						$blackcookie=';blackmover='.explode( ';blackmover=',$fulllivegameid)[1];
						$whitecookie=';whitemover='.explode( ';blackmover=',explode( ';blackmover=',$fulllivegameid)[0])[1];
	
						$BlackGameID='gid='.$systemlivegameid.$blackcookie;
						//$_COOKIE['livegameid']=$BlackGameID;
						$blackplayerassigned=true;
						$playertype=2;
						$fileblackgamecookie=$BlackGameID;
					}
					else if (strpos($line, 'blackplayer=') !== false) {
						$newgame='1';
					};
				}

			if((isset($_COOKIE['LiveStepType'])==true) &&($_COOKIE['LiveStepType']!="")&&
			(($_COOKIE['LiveStepType']=="black"))) {
				if(strpos($blackgamecookie,$fileblackgamecookie)!== false)
				{
				}
			}
			else {

			}
		}
		else
		///check if user already had some pending game // play invitation game if no pending game
		{
			$playertype=2;
		}
		fclose($reading);
		}
		else {
			//do not move board;
		}
	}
//Move was actually placed. Check if genuine move s per the file
//else if((isset($_REQUEST['livegameid'])) &&(isset($_REQUEST['livemove']))&&(($_REQUEST['livemove']!=null)&&($_REQUEST['livemove']!="" )))
else if((isset($_REQUEST['livegameid'])) &&(isset($_REQUEST['livemove']))&&(($_REQUEST['livemove']!=null)&&($_REQUEST['livemove']!="" )))
	{
		//$decodedc=base64_decode($_REQUEST['livegameid']);
		$decodedc=hex2str($_REQUEST['livegameid']);

		$leftover= explode(';',$decodedc);
		$boardtype= explode('cc=',$leftover[0])[1];

		for($i=0;$i<sizeof($leftover);$i=$i+1){		
			if(  (strpos($leftover[$i], 'cc=') !== false)) {  $boardtype= explode('cc=',$leftover[$i])[1];}
			else if(  (strpos($leftover[$i], 'gid=') !== false)) {  $gid= explode('gid=',$leftover[$i])[1];}
		}

		if((isset($_REQUEST['lg'])) && (strlen($_REQUEST['lg'])>=1)){

			if((strpos($_REQUEST['lg'].substr(0,1),"1")!==false)|| (strpos($_REQUEST['lg'].substr(0,2),"SY" )!==false)){$gamechoice="SY";}
			else {$gamechoice="SR";}
		}

		if ( $boardtype=="black") {
			//$systemlivegameid=base64_decode(explode(';',$gid)[0]); //if blank
			$systemlivegameid=hex2str(explode(';',$gid)[0]); //if blank

			$systemlivegameid=explode(';',$systemlivegameid)[0]; //if blank

			if(strlen($systemlivegameid)>2){
				$gamechoicepos=strpos($systemlivegameid, 'SY');
				if (($gamechoicepos !== false) && ($gamechoicepos==0))
					{$gamechoice="SY";}
				else {$gamechoice="SR";}
			}
			//$blackbrowsercookie=explode( ';blackmover=',base64_decode(explode(';',$gid)[0]))[1];
			$blackbrowsercookie=explode( ';blackmover=',hex2str(explode(';',$gid)[0]))[1];

			$gametype="";
			if(($systemlivegameid!=null) &&($systemlivegameid!="") &&(file_exists($systemlivegameid))){
				//consider the white and black moves
						$file = fopen($systemlivegameid,"r");
						$matched=0;
						while(!feof($file) && $matched<2) {
							$line = rtrim(fgets($file),"\r\n");
		
							if (strpos($line, '$newfen=') !== false) {
								$gametype="new";
								$newmove=1;
								}
		
							else if (strpos($line, '$currentfen=') !== false) {
								$splitted = explode( '$currentfen=',$line);
								$fen = $splitted[1];
									$matched=$matched+1; 
									$gametype="old";
									$borderstatus=explode(' ', explode( ' ',$line)[1])[0];
									$currentmover=explode(' ', explode( ' ',$line)[2])[0];
									$movesmade = explode(' ', explode( '-',$line)[1])[2];

							}
							else if (strpos($line, 'gid=') !== false) {
								$whiteblackcookie="";
								$blackplayerassigned=true;
								$playertype=2;
									$splitted = explode( 'gid=',$line);
									$fulllivegameid = 'gid='.$splitted[1];
									//$onlylivegameid =  explode( 'gid=',$fulllivegameid)[0];
									$whiteblackcookie=';whitemover='.explode( ';blackmover=',$fulllivegameid)[1];
									$blackcookie=';blackmover='.explode( ';blackmover=',$fulllivegameid)[1];
									$nonecookie=';nonemover';
									$BlackGameID='gid='.$systemlivegameid.$blackcookie;
		
									if (($blackcookie=="") ||($blackbrowsercookie=="")) {
										$blackplayer=-1;
										}									
									else if (strpos($blackcookie, $blackbrowsercookie) !== false) {
										$blackplayer=1;
										}
									else 
											$blackplayer=-1;

								$fileblackgamecookie=$BlackGameID;
							}
							else if (strpos($line, 'blackplayer=') !== false) {
								$newgame='1';
							};
		
							if (strpos($line, '_Move=') !== false) {
								$splittedfen_notation = explode( '=',$line);
								$oldfennotation = $splittedfen_notation[1]; 
								$matchedfen =  explode( ';',$oldfennotation)[0];
								//if (strpos($matchedfen.PHP_EOL, $fen) !== false){
									//$fen=$matchedfen;
									$matched=$matched+1;
								//}
							}
						}
		
						$whiteblackcookie="";
						$blackplayerassigned=true;
						$playertype=2;
						if(($blackplayer==-1)|| ($blackplayer==0)) {
							$playertype=100;
							$systemcookieset='gid='.$systemlivegameid.';nonemover';
							$GLOBALS['systemcookieset']=$systemcookieset;
							//$_COOKIE['livegameid']='gid='.$systemlivegameid.$nonecookie;
							//setcookie('livegameid', 'gid='.$systemlivegameid.$nonecookie);			  
							}
						else{
							$GLOBALS['systemcookieset']=$BlackGameID;
							//$_COOKIE['livegameid']=$BlackGameID;
							//setcookie('livegameid', $BlackGameID);
						}
		
						fclose($file);
		
						if(strpos($whiteblackcookie,'whitemover')!== false)
						{
						}
						else if(strpos($whiteblackcookie,'blackmover')!== false)
						{
							$playertype=2;
		
							if( ((($currentmover=='b0') || ($currentmover=='w1')|| ($currentmover=='b2'))))
							{
							$result= "Black To Move"; $result= "2";
							$newmove=1;
							}
							else if(
							((($currentmover=='w0') || ($currentmover=='b1')|| ($currentmover=='w2')))){
							$result= "White To Move"; $result= "1";
							$newmove=0;
							$writefile=0;
							};
							////					
							//set cookies
						}
						else if($playertype!=100)
						{
							$playertype=2;
							if( ((($currentmover=='b0') || ($currentmover=='w1')|| ($currentmover=='b2'))))
							{
							$result= "Black To Move"; $result= "2";
							$newmove=1;
							}
							else if(
							((($currentmover=='w0') || ($currentmover=='b1')|| ($currentmover=='w2')))){
							$result= "White To Move"; $result= "1";
							$newmove=0;
							$writefile=0;
							};
						}
			}
			else {
				die("No such game exists. Reviwing the game;");
			}
		}


		$gamestatus= check_gamestatus($decodedc);

		if($gamestatus==0){
			cf_setcookie();
			//$cookiefen=$fen="1c2ai2c1/cmhgsnghmc/cppppppppc/181/c8c/C8C/181/CPPPPPPPPC/CMHGNSGHMC/1C2IA2C1 ~bwc w0 () - 0 1";
			$cookiefen=$GLOBALS['defaultfen'];
		}
		else if($gamestatus==1){
			$cookiecategory="";$cookiefen=""; $gid="";
			for($i=0;$i<sizeof($leftover);$i++){
				if(  (strpos($leftover[$i], 'cc=') !== false)) { $cookiecategory=explode('cc=',$leftover[$i])[1];	}
				if(  (strpos($leftover[$i], 'fen=') !== false)) { $cookiefen=explode('fen=',$leftover[$i])[1];	}
				if(  (strpos($leftover[$i], 'gid=') !== false)) { $gid=explode('gid=',$leftover[$i])[1];}
		}

			if(($cookiecategory=="") ||($cookiecategory==null))
				$cookiecategory="white";
		
			if(($cookiefen=="") ||($cookiefen==null))
				//$cookiefen="1c2ai2c1/cmhgsnghmc/cppppppppc/181/c8c/C8C/181/CPPPPPPPPC/CMHGNSGHMC/1C2IA2C1 ~bwc w0 () - 0 1";
				$cookiefen=$GLOBALS['defaultfen'];

				if(stristr($GLOBALS['fen'],$cookiefen)!=false )
					$fen=$cookiefen;
		
		}
		else if($gamestatus==2){
			$cookiecategory="";$cookiefen=""; $gid="";
			for($i=0;$i<sizeof($leftover);$i++){
				if(  (strpos($leftover[$i], 'cc=') !== false)) { $cookiecategory=explode('cc=',$leftover[$i])[1];	}
				if(  (strpos($leftover[$i], 'fen=') !== false)) { $cookiefen=explode('fen=',$leftover[$i])[1];	}
				if(  (strpos($leftover[$i], 'gid=') !== false)) { $gid=explode('gid=',$leftover[$i])[1];}
		}
		
			if(($cookiecategory=="") ||($cookiecategory==null))
				$cookiecategory="black";
		
			if(($cookiefen=="") ||($cookiefen==null))
				//$cookiefen="1c2ai2c1/cmhgsnghmc/cppppppppc/181/c8c/C8C/181/CPPPPPPPPC/CMHGNSGHMC/1C2IA2C1 ~bwc w0 () - 0 1";
				$cookiefen=$GLOBALS['defaultfen'];
				if(stristr($GLOBALS['fen'],$cookiefen)!=false )
					$fen=$cookiefen;

		}		
		else{
			cf_setcookie();
		}

		if($gamestatus>0)
		{
	
			$unknowngamecookie=$decodedc;
			//$livegameid = $gid;			
			//$livegameid = base64_decode($gid);//explode($gid,$decodedc)[1];
			$livegameid = hex2str($gid);//explode($gid,$decodedc)[1];

			$onlylivegameid =  explode( ';',$livegameid)[0];
			$systemlivegameid= $onlylivegameid;
			$systemcookie=$decodedc;
			//$encodedc=base64_encode( "cc=". 'white'.";fen=".$fen.";".$systemcookieset);
			$cookiefenArr=explode( ';',$systemcookie);
			$cookiefen="";
			if((strpos($cookiefenArr[1],"fen=")!==false) && (strlen($cookiefenArr[1])<10))
				{ $cookiefen=$fen; $cookiefenArr[1]="fen=".$fen;$systemcookie=$cookiefenArr[0].";".$cookiefenArr[1].";".$cookiefenArr[2];}
			if(($cookiefen=="") ||($cookiefen==null))
				//$cookiefen="1c2ai2c1/cmhgsnghmc/cppppppppc/181/c8c/C8C/181/CPPPPPPPPC/CMHGNSGHMC/1C2IA2C1 ~bwc w0 () - 0 1";
				$cookiefen=$GLOBALS['defaultfen'];
			$currentfenmatched=0;$newfenmatched=0;
		
			$whiteblackcookie=explode($onlylivegameid. ';',$livegameid)[1];//'gid='.$onlylivegameid.';blackmover='.$filewhitecookie.';';
			if(file_exists($systemlivegameid)){
				$data = file($systemlivegameid); // reads an array of lines
				$reading = fopen($systemlivegameid, 'r');
				//$writing = fopen($systemlivegameid.'.tmp.txt', 'w');
				$replaced = false;	$importedmatched=false;


				if((strpos($whiteblackcookie,'blackmover')!== false) || (strpos($whiteblackcookie,'whitemover')!== false))
					{
					if(strpos($whiteblackcookie,'blackmover')!== false) $playertype=2;
					if(strpos($whiteblackcookie,'whitemover')!== false) $playertype=1;
	
					$newmove=0; $matched=0;$cookiefen_newfensame=0;
					$fenstatus=-1;
					//0 means no match.. 
					//1 Means New StepsFen, Hidden GameFEN and FileCurrentFEN matched. Break the IF condition.. (Same status Refresh)
					//2 MEans New StepsFEn and  FileCurrentFEN didnot match. Now, here HiddenGameFEn should match with currentFEN. Keep inside IF
					//3 MEans New StepsFEn and  FileNewFEN match.  break
					
					//0 MEans New StepsFEn and  FileCurrentFEN didnot match. Now, here HiddenGameFEn didnot match with currentFEN. Dont get into IF condition. break
					$currentfenmatched=-1;
					
					$GLOBALS['systemlivegameid']=$systemlivegameid;
					if(strpos($whiteblackcookie,'blackmover')!== false){$blackgamecookie=$systemcookie;$currentfenmatched=-1; $GLOBALS['systemcookieset']=$blackgamecookie;}
					if(strpos($whiteblackcookie,'whitemover')!== false) {$whitegamecookie=$systemcookie;$currentfenmatched=-1; $GLOBALS['systemcookieset']=$whitegamecookie;}
					
					$GLOBALS['whitegamecookieset']=$whitegamecookieset;
					$GLOBALS['blackgamecookieset']=$blackgamecookieset;	
					
					while (!feof($reading)) {
						$line = rtrim(fgets($reading),"\r\n");
						if ((stristr($line,'$currentfen='))) {
							$replaced = true; $importedmatched=true;
							$currentfen=explode(';', explode( '$currentfen=',$line)[1])[0];
							$borderstatus=explode(' ', explode( ' ',$line)[1])[0];
							$currentmover=explode(' ', explode( ' ',$line)[2])[0];
							$movesmade = explode(' ', explode( '-',$line)[1])[2];

							//$extractbrowserfen=
							if(stristr($_REQUEST['livemove'],$cookiefen)){
								$fenstatus=1; $currentfenmatched=1;//same game or refreshed
							}
							else { $fenstatus=2;
							$currentfenmatched=0;}
						}
						if ((stristr($line,'$blackplayer=')) && ($playertype==1) ) {
							$cookiefen_newfensame=1;
							$newfenmatched=1;
							$fenstatus=1;
							$_REQUEST['livemove']=$fen=$cookiefen;
							$newgame='1';
							break;
						}

						if ((stristr($line,'$blackplayer=')) && ($playertype==2) ) {
							$cookiefen_newfensame=1;
							$newfenmatched=1;
							$fenstatus=1;
							$_REQUEST['livemove']=$fen=$cookiefen;
						}
						
						if (strpos($line, 'blackplayer=') !== false) {
							$newgame='1';
						};				
						  //fputs($writing, $line.PHP_EOL);
						if(($fenstatus==2)||($fenstatus==0)){//($importedmatched==true)&&($newfenmatched==0)){
								//$line = rtrim(fgets($reading),"\r\n");
								if((stristr($line,$_REQUEST['livemove']) && (stristr($_REQUEST['livemove'],'$currentfen=')==false))){
										$fenstatus=3;
										$newfenmatched=1;$cookiefen_newfensame=0;//New Step Requested available in file
									}
							}
						else if (strpos($line, 'gid=') !== false) {
							$splitted = explode( 'gid=',$line);
							$fulllivegameid = 'gid='.$splitted[1];	
							$fulllivegameidArr = explode(";" ,$fulllivegameid);
							$whiteblackcookie="";$blackcookie="";$nonecookie="";$whitecookie="";
							for($i=0;$i<sizeof($fulllivegameidArr);$i=$i+1){		
								//$onlylivegameid =  explode( 'gid=',$fulllivegameid)[0];

								if(  (strpos($fulllivegameidArr[$i], 'whitemover=') !== false)) { $whiteblackcookie=$fulllivegameidArr[$i];}
								else if(  (strpos($fulllivegameidArr[$i], 'whitemover=') !== false)) { $whitecookie=$fulllivegameidArr[$i];}
								else if(  (strpos($fulllivegameidArr[$i], 'whitemover=') !== false)) { $nonecookie=$fulllivegameidArr[$i];}
								else if(  (strpos($fulllivegameidArr[$i], 'blackmover=') !== false)) { $whiteblackcookie=$fulllivegameidArr[$i];$blackplayerassigned=true;}
								else if(  (strpos($fulllivegameidArr[$i], 'blackmover=') !== false)) { $blackcookie=$fulllivegameidArr[$i];$blackplayerassigned=true;}
								else if(  (strpos($fulllivegameidArr[$i], 'nonemover=') !== false)) { $nonecookie=$fulllivegameidArr[$i];}
							}
	
							//$blackplayerassigned=true;	$playertype=2;
							if (strpos($line, '_Move=') !== false) {
								$writefile=1;
								}				
							}
							if (strpos($line, '_Move=') !== false) {
								$writefile=1;
							}
						}

					if(($cookiefen!="")&&
						//($cookiefen_newfensame==0)) {
						($fenstatus!=-1)) {
							if($playertype==2){
									if((($currentmover=='b0') || ($currentmover=='w1')|| ($currentmover=='b2')) &&($fenstatus==3)){
										$writefile=1; $fen=$_REQUEST['livemove'];$newmove=1; // send movelist. update file.
									}
									else if((($currentmover=='b0') || ($currentmover=='w1')|| ($currentmover=='b2')) &&($fenstatus==2)){
										$writefile=0; $fen=$currentfen;$newmove=1; // send movelist
										$cookiefen_newfensame=1;
									}
									else if((($currentmover=='b0') || ($currentmover=='w1')|| ($currentmover=='b2')) &&(($fenstatus==1))){
										$writefile=0; $fen=$_REQUEST['livemove'];$newmove=1; // send movelist
										$cookiefen_newfensame=1;							
									}							
									else if((($currentmover=='w0') || ($currentmover=='b1')|| ($currentmover=='w2')) &&($fenstatus==1))	{
										$writefile=0; $fen=$_REQUEST['livemove'];$newmove=0; //nrefresh loop. no moves sent
									}											
									else{ $writefile=0; ;$newmove=0;}
									}
								else if($playertype==1){
									if((($currentmover=='w0') || ($currentmover=='b1')|| ($currentmover=='w2'))&&($fenstatus==3)){
										$writefile=1; $fen=$_REQUEST['livemove'];$newmove=1; // send movelist. update file.
									}
									else if((($currentmover=='w0') || ($currentmover=='b1')|| ($currentmover=='w2')) &&($fenstatus==2)){
										$writefile=0; $fen=$currentfen;$newmove=1; // send movelist
										$cookiefen_newfensame=1;
									}
									else if((($currentmover=='w0') || ($currentmover=='b1')|| ($currentmover=='w2')) &&(($fenstatus==1))){
										$writefile=0; $fen=$_REQUEST['livemove'];$newmove=1; // send movelist
										$cookiefen_newfensame=1;							
									}							
									else if((($currentmover=='w0') || ($currentmover=='w1')|| ($currentmover=='b2'))&&($fenstatus==1)){
										$writefile=0; $fen=$_REQUEST['livemove'];$newmove=0; //nrefresh loop. no moves sent
									}											
									else{ $writefile=0; ;$newmove=0;}
									}
									
								if($newmove==1){$fen=$_REQUEST['livemove'];}
							}
						else {	 $writefile=0;$newmove==0;	}
					}
				else
					///check if user already had some pending game // play invitation game if no pending game
					{
						//$filewhitecookie=explode(';', explode( ';blackmover=',$livegameid)[1])[0];
						//$fileblackcookie=explode(';',explode(';blackmover=', $livegameid)[1])[0];
						//$filewhitegamecookie='gid='.$onlylivegameid.';blackmover='.$filewhitecookie.';';
						//$fileblackgamecookie='gid='.$onlylivegameid.';blackmover='.$fileblackcookie.';';
						$playertype=2;
					}
				fclose($reading);
			}
			else
			{
				//do not move the board
			}
		}
}
require_once('../helpers/helper_functions.php');
require_once('../models/ChessRulebook.php');
require_once('../models/ChessBoard.php');
require_once('../models/ChessPiece.php');
require_once('../models/ChessMove.php');
require_once('../models/ChessSquare.php');

$board = new ChessBoard	();
if( ($playertype==100) || ((($currentmover=='b0') || ($currentmover=='w1')|| ($currentmover=='b2')) && ($playertype==1)) ||
((($currentmover=='w0') || ($currentmover=='b1')|| ($currentmover=='w2')) && ($playertype==2))){
	$board->moveable=false;
	$newmove=0;
	if($writefile==1) 	$newmove=1;
}
else if( ((($currentmover=='b1') || ($currentmover=='w0')|| ($currentmover=='w2')) && ($playertype==1)) ||
((($currentmover=='b0') || ($currentmover=='w1')|| ($currentmover=='b2')) && ($playertype==2))){
	$board->moveable=true;
	if($writefile==1) 	$newmove=1;
}

if ( isset($_REQUEST['reset']) ) {
	// Skip this conditional. ChessGame's FEN is the default, new game FEN and doesn't need to be set again.
	rename($systemlivegameid, $systemlivegameid.'_'.date('mdyhisA').'.txt');
} 
elseif ( isset($_REQUEST['livemove']) &&(($writefile==1) )) {
	if($playertype==2)
		$board->setboard('black');
	if(($playertype==1) || ($playertype==100))
		$board->setboard('white');
	if(($gamechoice=="SY") ||($gamechoice=="1"))
		{
			$board->gametype=1;	
		}
	else $board->gametype=2;
	
	$board->import_live_fen($_REQUEST['livemove'],$systemlivegameid,$playertype,$newmove);
	if(isset($_REQUEST['import_boardtype']))
	$board->setboard($_REQUEST['import_boardtype']);

	$legal_moves=null;
	if(($board->moveable==true)){
		$controlled_color=$board->controlled_color;
		$controller_color=$board->controller_color;

		$legal_moves = ChessRulebook::get_legal_moves_list($board->color_to_move, $board);
		//$fen = $board->export_live_fen("1",$legal_moves);
		$board->controlled_color=$controlled_color;
		$board->controller_color=$controller_color;
			if($fen =="")  $fen= $board->export_fen();
		}
		
		if((file_exists($systemlivegameid))&&($legal_moves!=null)){
			{
			$data = file($systemlivegameid);// reads an array of lines
		
			$reading = fopen($systemlivegameid, 'r');
			$writing = fopen($systemlivegameid.'.tmp.txt', 'w');
		
			$replaced = false;
			$importedmatched=false;
			while (!feof($reading)&&($importedmatched==false)) {
				$line = rtrim(fgets($reading),"\r\n");
				if ((stristr($line,'$currentfen='))) {
					$line = '$currentfen='.$fen;
					$movesmade = explode(' ', explode( '-',$line)[1])[2];
					$replaced = true;
					$importedmatched=true;
				}
				if (strlen(rtrim($line))>3)
				fputs($writing, $line.PHP_EOL);
			}

			$movecount=0;
			foreach ( $legal_moves as $key => $move ): 
				$Ntype="";$notationvalue=""; $ending_square=$move->ending_square; $movecount=$movecount+1;					
				if($move->pushedending_square!=null) {
					if($move->tamedpawn <=-1) {
						$ending_square->rank=$ending_square->mediatorrank; $ending_square->file=$ending_square->mediatorfile;
					}
					else
					$ending_square=$move->pushedending_square;
				}

				$move_FEN= $move->board->export_fen_moves($move->starting_square,$move->ending_square,false,$move->tamedpawn);
				$move->ending_square=$ending_square;
				$notationvalue=$move->get_notation();
				if(($newmove==1)&& (strlen(rtrim($line))>3))
					fputs($writing, $movecount.'_Move='.$move_FEN.';'.$movecount.'_Notation='.$notationvalue.PHP_EOL); 
			endforeach;

			fclose($reading);fclose($writing);
			chmod($systemlivegameid.".tmp.txt", 0755);
			$currentdata = file_get_contents($systemlivegameid.'.tmp.txt');
			file_put_contents($systemlivegameid, $currentdata, LOCK_EX);
			// might as well not overwrite the file if we didn't replace anything
			try {
				//sleep(1);
				if (($replaced) && (file_exists($systemlivegameid.'.tmp.txt')))
				{
					rename($systemlivegameid.'.tmp.txt', $systemlivegameid);
				}else {
					unlink($systemlivegameid.'.tmp.txt');
				}
			  }
			  //catch exception
			  catch(Exception $e) {
			  }
			}
			copy($systemlivegameid,'moveshistory/'.$systemlivegameid.'.'.$movesmade.'.txt');

		}
	$legal_moves=null;
	if($newmove!=1)
		$board->moveable=false;

} elseif ( isset($_REQUEST['surrendermove']) ) {
	if(($gamechoice=="SY") ||($gamechoice=="1"))
		{
			$board->gametype=1;	
		}
	else $board->gametype=2;	
	$board->import_fen($_REQUEST['surrendermove']);
	if(isset($_REQUEST['import_boardtype']))
	$board->setboard($_REQUEST['import_boardtype']);
	$board->moveable=false;
} elseif ( isset($_REQUEST['endgamemove']) ) {
	if(($gamechoice=="SY") ||($gamechoice=="1"))
		{
			$board->gametype=1;	
		}
	else $board->gametype=2;	
	$board->import_fen($_REQUEST['endgamemove']);
	if(isset($_REQUEST['import_boardtype']))
	$board->setboard($_REQUEST['import_boardtype']);
} elseif ( isset($_REQUEST['fen']) ) {
	if(($gamechoice=="SY") ||($gamechoice=="1"))
		{
			$board->gametype=1;	
		}
	else $board->gametype=2;
	$board->import_fen($_REQUEST['fen']);
	if(isset($_REQUEST['import_boardtype']))
	$board->setboard($_REQUEST['import_boardtype']);
} elseif  ( isset($_REQUEST['BlackGameID']) && ($systemlivegameid!=null)&&($systemlivegameid!="")) {
	//playertype
	$board->setboard('black');
	if(($gamechoice=="SY") ||($gamechoice=="1"))
	{
		$board->gametype=1;	
	}
	else $board->gametype=2;

	$board->import_live_fen($fen,$systemlivegameid,2,$newmove );
	if(isset($_REQUEST['import_boardtype']))
		$board->setboard($_REQUEST['import_boardtype']);	
}
else {
	if($playertype==2)
		$board->setboard('black');
	if(($playertype==1) || ($playertype==100))
		$board->setboard('white');
	if(($gamechoice=="SY") ||($gamechoice=="1"))
		{
			$board->gametype=1;	
		}
	else $board->gametype=2;
	$board->import_live_fen($fen,$systemlivegameid,$playertype ,$newmove);
	if(isset($_REQUEST['import_boardtype']))
	$board->setboard($_REQUEST['import_boardtype']);	
}

$legal_moves=null;
if(($board->moveable==true)){
	$controlled_color=$board->controlled_color;
	$controller_color=$board->controller_color;
	if(($gamechoice=="SY") ||($gamechoice=="1"))
		{
			$board->gametype=1;	
		}
	else $board->gametype=2;
	$legal_moves = ChessRulebook::get_legal_moves_list($board->color_to_move, $board);
	//$fen = $board->export_live_fen("1",$legal_moves);
	$board->controlled_color=$controlled_color;
	$board->controller_color=$controller_color;
		if($fen =="")  $fen= $board->export_fen();
	}

if ( isset($_REQUEST['lookformoves']) && (strtolower($_REQUEST['lookformoves'])!='yes')) {
	if(($gamechoice=="SY") ||($gamechoice=="1"))
		{
			$board->gametype=1;	
		}
	else $board->gametype=2;	
		$board->import_fen($_REQUEST['lookformoves']);
		$fen=$_REQUEST['lookformoves'];
		// Skip this conditional. ChessGame's FEN is the default, new game FEN and doesn't need to be set again.
} else {$fen = $board->export_fen();}

$side_to_move = $board->get_side_to_move_string();
$who_is_winning = $board->get_who_is_winning_string();
$graphical_board_array = $board->get_graphical_board();

define('VIEWER', true);

if(($board->Winner==="3") &&($board->Winners===0)){
	$serverrequesttype= "Drawn"; /*refresh once //refresh multiple times //update// noaction*/
	$fendata=array("fen" => $fen,
	"systemcs" => $systemcookieset,
	"serverrequesttype" => $serverrequesttype,
	"blackcankill" => $board->blackcankill,
	"whitecankill" => $board->whitecankill,
	"blackcanfullmove" => $board->blackcanfullmove,
	"whitecanfullmove" => $board->whitecanfullmove,
	"boardtype" => $board->boardtype,
	"gamestatus" => "Game Drawn",
	"whitelist" => array( '127.0.0.1', '::1'),
	"Moves" => 	null,
	"ng" => $newgame);
}
//require_once('../liveviews/index.php');
else if($legal_moves!=null){
		$a = [];
		$matched=0; $exportedfen= $fen; $gametype="old";
		if((file_exists($systemlivegameid))){
				//consider the white and black moves
				$file = fopen($systemlivegameid,"r");
				while(!feof($file) && $matched<2) {
						$line = rtrim(fgets($file),"\r\n");
					
						if (strpos($line, '$newfen=') !== false) {
							$gametype="new";
							}
						else if (strpos($line, '$currentfen=') !== false) {
							$splitted = explode( '$currentfen=',$line);
							$fen = $splitted[1];
							$stringposition=strpos($fen,$exportedfen);
							$movesmade = explode(' ', explode( '-',$line)[1])[2];
				
							if (strpos($fen,$exportedfen) !== false) {
								$matched=$matched+1; //New request
								$gametype="old";
							}
							else if ((strpos($line, 'Moved_FEN=Good')!==false)) {
								$matched=$matched+1; //New request
								$gametype="old";
							}
						}
						else if (strpos($line, 'gid=') !== false) {
							$splitted = explode( 'gid=',$line);
							$livegameid = $splitted[1];
							$onlylivegameid =  explode( ';',$livegameid)[0];
						
							if (isset($_COOKIE['livegameid'])) ///check if user already had some pending game // play invitation game if no pending game
							{
								$filewhitecookie=explode(';', explode( ';blackmover=',$livegameid)[1])[0];
								$fileblackcookie=explode(';',explode(';blackmover=', $livegameid)[1])[0];
								$filewhitegamecookie='gid='.$onlylivegameid.';blackmover='.$filewhitecookie.';';
								$fileblackgamecookie='gid='.$onlylivegameid.';blackmover='.$fileblackcookie.';';
							}
						}
						else if (strpos($line, 'blackplayer=') !== false) {
							$newgame='1';
						};					
					
						if (strpos($line, '_Move=') !== false) {
							$splittedfen_notation = explode( '=',$line);
							$oldfennotation = $splittedfen_notation[1]; 
							$matchedfen =  explode( ';',$oldfennotation)[0];
							$matched=$matched+1;
						}
					}
				fclose($file);
				
				if(($cookiefen_newfensame==1) && ($gametype=="old") && ($legal_moves!=null))  {
					$serverrequesttype='refresh';
						$movecount=0;					
						foreach ( $legal_moves as $key => $move ): 
							{
								$Ntype="";$notationvalue=""; $ending_square=$move->ending_square; $movecount=$movecount+1;				
								if($move->tamedpawn!==0) {
									$ttt=1;
								}

								if($move->powermovedflag!=="") {
									$ttt=1;
									//.Gg2g2=H
									///////////senior came to promote
								}

								if($move->pushedending_square!=null) {
									if($move->tamedpawn <=-1) {
										$ending_square->rank=$ending_square->mediatorrank; $ending_square->file=$ending_square->mediatorfile;
									}
									else
									$ending_square=$move->pushedending_square;
								}
								//pushedendingsquare can be backword also.
								$movevalue=$move->board->export_fen_moves($move->starting_square,$move->ending_square,false,$move->tamedpawn);
								$move->ending_square=$ending_square;
								$notationvalue=$Ntype.$move->get_notation();
							
								$tempa=array("option" => $movevalue, 
										"data_coordinate_notation" => $notationvalue,
										"CMove" => 	"No");
								array_push(	$a,$tempa);

								//Controlled_moves are coming in page-refresh
								if(($move->controlled_moves!==null) &&(sizeof($move->controlled_moves)>0)){
									$controlled_moves=$move->controlled_moves;
									if (($move->controlled_moves!=null)&&(( $move->starting_square->rank ) &&  ( $move->ending_square->rank )
									&& ( $move->starting_square->file) &&  ( $move->ending_square->file) &&
									( count($move->controlled_moves)>0)))
									{ 
										$Naraad_Mcount=count($controlled_moves);
										foreach ($controlled_moves as $key1 => $nmove ):
												$Ntype="";$notationvalue=""; $ending_square=$nmove->ending_square;$movecount=$movecount+1;
												$nmove->ending_square=$ending_square;
												if(3-$nmove->color==$board->color_to_move)
													{ $Ntype=">";}
												$notationvalue=$Ntype.$nmove->get_notation();
												if($Ntype==">") { $CMove='Yes';}
												else { $Move='No';}
												$movevalue= $move->board->export_fen_moves($nmove->starting_square,$nmove->ending_square,true,$move->tamedpawn);
										
													$tempa=array("option" => $movevalue,
													"data_coordinate_notation" => $notationvalue,
													"CMove" => 	$CMove);
													array_push(	$a,$tempa);
											endforeach;
									}
								}

							}
						endforeach;
				}
				else 
				if(($cookiefen_newfensame==0) && ($gametype=="old") && ($legal_moves!=null))  {
					$data = file($systemlivegameid); // reads an array of lines
					$a=null;
						$serverrequesttype= "nrefresh"; /*refresh once //refresh multiple times //update// noaction*/
						$reading = fopen($systemlivegameid, 'r');
						if($newmove==1) { $writing = fopen($systemlivegameid.'.tmp.txt', 'w'); }
					
						$replaced = false;
						$importedmatched=false;
						while (!feof($reading)&&($importedmatched==false)) {
							$line = rtrim(fgets($reading),"\r\n");
							if(($matched==1)	&&(stristr($line,'$currentfen='))){
								$line = '$currentfen='.explode(';', explode( '$currentfen=',$line)[1])[0];
								$movesmade = explode(' ', explode( '-',$line)[1])[2];
								$replaced=true;$importedmatched=true;}
							else if ((stristr($line,'$currentfen='))) {
								$replaced = true;
								$importedmatched=true;
								$movesmade = explode(' ', explode( '-',$line)[1])[2];
							}
							if(($newmove==1) && (strlen(rtrim($line))>3))
								  fputs($writing, $line.PHP_EOL);
						
							  if($importedmatched==true){
								$line = rtrim(fgets($reading),"\r\n");
							
								if(($newmove==1) && (stristr($line,'$blackplayer='))){
												$newgame='1';
												fputs($writing, $line.PHP_EOL);
											}
							}
						}
						$movecount=0;
						foreach ( $legal_moves as $key => $move ): 
								if (($move->controlled_moves!=null)&&(( $move->starting_square->rank ) &&  ( $move->ending_square->rank )
									&& ( $move->starting_square->file) &&  ( $move->ending_square->file) &&
									( count($move->controlled_moves)>0)))
									{ 
										$Naraad_Mcount=count($move->controlled_moves);
										foreach ( $move->controlled_moves as $key1 => $nmove ):
												$Ntype="";$notationvalue=""; $ending_square=$nmove->ending_square;$movecount=$movecount+1;
												$nmove->ending_square=$ending_square;
												if(3-$nmove->color==$board->color_to_move)
													{ $Ntype=">";}
												$notationvalue=$Ntype.$nmove->get_notation();
												if($Ntype==">") { $CMove='Yes';}
												else { $Move='No';}
												$movevalue= $move->board->export_fen_moves($nmove->starting_square,$nmove->ending_square,true,$move->tamedpawn);
										
												if($newmove==1) {
													$tempa=array("option" => $movevalue,
													"data_coordinate_notation" => $notationvalue,
													"CMove" => 	$CMove);	
													fputs($writing, $movecount.'_Move='.$movevalue.';'.$movecount.'_Notation='.$notationvalue.PHP_EOL);	
												}
										endforeach;
									}
								else {
										$Ntype="";$notationvalue=""; $ending_square=$move->ending_square; $movecount=$movecount+1;	

										if($move->pushedending_square!=null) {
											if($move->tamedpawn <=-1) {
												$ending_square->rank=$ending_square->mediatorrank; $ending_square->file=$ending_square->mediatorfile;
											}
											else
											$ending_square=$move->pushedending_square;
										}
												$movevalue=$move->board->export_fen_moves($move->starting_square,$move->ending_square,false,$move->tamedpawn);
												$move->ending_square=$ending_square;
												$notationvalue=$Ntype.$move->get_notation();

												if($newmove==1) {
						
														$tempa=array("option" => $movevalue, 
																"data_coordinate_notation" => $notationvalue,
																"CMove" => 	"No");
														fputs($writing, $movecount.'_Move='.$movevalue.';'.$movecount.'_Notation='.$notationvalue.PHP_EOL);	
													}
									}
						endforeach;	
					
						fclose($reading); 
						if($newmove==1) { fclose($writing);	
								// might as well not overwrite the file if we didn't replace anything
								/*
								try {
										//sleep(1);
										if (($replaced) && (file_exists($systemlivegameid.'.tmp.txt')))
											{
												rename($systemlivegameid.'.tmp.txt', $systemlivegameid);
										}else {
												unlink($systemlivegameid.'.tmp.txt');
											}
					 				}
								catch(Exception $e) {
									}*/
							}
					}				
				else 
				if(($gametype=="old"))  {
						$data = file($systemlivegameid); // reads an array of lines
						$serverrequesttype= "nrefresh"; /*refresh once //refresh multiple times //update// noaction*/
						$reading = fopen($systemlivegameid, 'r');
						if($newmove==1) { $writing = fopen($systemlivegameid.'.tmp.txt', 'w'); }
					
						$replaced = false;
						$importedmatched=false;
						while (!feof($reading)&&($importedmatched==false)) {
							$line = rtrim(fgets($reading),"\r\n");
							if(($matched==1)	&&(stristr($line,'$currentfen='))){
								$line = '$currentfen='.explode(';', explode( '$currentfen=',$line)[1])[0];
								$replaced=true;$importedmatched=true;
								$movesmade = $line.explode(' ', explode( '-',$line)[1])[1];
							}
							else if ((stristr($line,'$currentfen='))) {
								$replaced = true;
								$importedmatched=true;
								$movesmade = $line.explode(' ', explode( '-',$line)[1])[1];

							}
							if(($newmove==1)&& (strlen(rtrim($line))>3))
								  fputs($writing, $line.PHP_EOL);
						
							  if($importedmatched==true){
								$line = rtrim(fgets($reading),"\r\n");
							
								if(($newmove==1)&& (stristr($line,'$blackplayer='))){
									$newgame='1';
									fputs($writing, $line.PHP_EOL);
								}
							}
						}
						$movecount=0;
						foreach ( $legal_moves as $key => $move ): 
								if (($move->controlled_moves!=null)&&(( $move->starting_square->rank ) &&  ( $move->ending_square->rank )
									&& ( $move->starting_square->file) &&  ( $move->ending_square->file) &&
									( count($move->controlled_moves)>0)))
									{ 
										$Naraad_Mcount=count($move->controlled_moves);
										foreach ( $move->controlled_moves as $key1 => $nmove ):
												$Ntype="";$notationvalue=""; $ending_square=$nmove->ending_square;$movecount=$movecount+1;
												$nmove->ending_square=$ending_square;
												if(3-$nmove->color==$board->color_to_move)
													{ $Ntype=">";}
												$notationvalue=$Ntype.$nmove->get_notation();
												if($Ntype==">") { $CMove='Yes';}
												else { $Move='No';}
												$movevalue= $move->board->export_fen_moves($nmove->starting_square,$nmove->ending_square,true,$move->tamedpawn);
										
												if($newmove==1) {
													$tempa=array("option" => $movevalue,
													"data_coordinate_notation" => $notationvalue,
													"CMove" => 	$CMove);	
													array_push(	$a,$tempa);								
													fputs($writing, $movecount.'_Move='.$movevalue.';'.$movecount.'_Notation='.$notationvalue.PHP_EOL);	
												}
												else {array_push(	$a,$tempa);}
										endforeach;
									}
								else {
										$Ntype="";$notationvalue=""; $ending_square=$move->ending_square; $movecount=$movecount+1;						

										if($move->pushedending_square!=null) {
											if($move->tamedpawn <=-1) {
												$ending_square->rank=$ending_square->mediatorrank; $ending_square->file=$ending_square->mediatorfile;
											}
											else
											$ending_square=$move->pushedending_square;
										}
												$movevalue=$move->board->export_fen_moves($move->starting_square,$move->ending_square,false,$move->tamedpawn);
												$move->ending_square=$ending_square;
												$notationvalue=$Ntype.$move->get_notation();

												if($newmove==1) {
						
														$tempa=array("option" => $movevalue, 
																"data_coordinate_notation" => $notationvalue,
																"CMove" => 	"No");
														array_push(	$a,$tempa);		
														fputs($writing, $movecount.'_Move='.$movevalue.';'.$movecount.'_Notation='.$notationvalue.PHP_EOL);	
													}
												else { array_push(	$a,$tempa); }
									}
						endforeach;	
					
						fclose($reading); 
						copy($systemlivegameid,'moveshistory/'.$systemlivegameid.'.'.$movesmade.'.txt');

						if($newmove==1) { fclose($writing);	chmod($systemlivegameid.".tmp.txt", 0755);
								// might as well not overwrite the file if we didn't replace anything
								/*
								try {
										//sleep(1);
										if (($replaced) && (file_exists($systemlivegameid.'.tmp.txt')))
											{
												rename($systemlivegameid.'.tmp.txt', $systemlivegameid);
										}else {
												unlink($systemlivegameid.'.tmp.txt');
											}
					 				}
								catch(Exception $e) {
									}*/
									
							}
					}
				//if(($gametype=="old")) {$legal_moves=null;	$a=null; }

			}
		
		if($gametype=="new") {
				$file = fopen($systemlivegameid,"w");
				$serverrequesttype= "update"; /*refresh once //refresh multiple times //update// noaction*/

				//$systemlivegameid;
				//fwrite($file,'gid='.'livemove'.str_shuffle("acdefhijkmnprtuvwxyz0123456789").PHP_EOL);
				fwrite($file,'gid='.$livegameid.PHP_EOL);
				fwrite($file,'$currentfen='.$exportedfen.PHP_EOL);
				fwrite($file,'$blackplayer=0'.PHP_EOL);
				$newgame='1';

				$movecount=0;
			
				foreach ( $legal_moves as $key => $move ): 
					{
						$Ntype="";$notationvalue=""; $ending_square=$move->ending_square; $movecount=$movecount+1;				
					
						if($move->pushedending_square!=null) {
							if($move->tamedpawn <=-1) {
								$ending_square->rank=$ending_square->mediatorrank; $ending_square->file=$ending_square->mediatorfile;
							}
							else
							$ending_square=$move->pushedending_square;
						}

						$movevalue=$move->board->export_fen_moves($move->starting_square,$move->ending_square,false,$move->tamedpawn);
						$move->ending_square=$ending_square;
						$notationvalue=$Ntype.$move->get_notation();
					
						$tempa=array("option" => $movevalue, 
								"data_coordinate_notation" => $notationvalue,
								"CMove" => 	"No");
						array_push(	$a,$tempa);
						fwrite($file, $movecount.'_Move='.$movevalue.';'); 
						fwrite($file,$movecount.'_Notation='.$notationvalue.PHP_EOL);
					}
				endforeach;
				fclose($file);
				chmod($systemlivegameid, 0755);
			}

	
		$fendata=array("fen" => $fen,
				"systemcs" => $systemcookieset,
				"serverrequesttype" => $serverrequesttype,
				"blackcankill" => $board->blackcankill,
				"whitecankill" => $board->whitecankill,
				"blackcanfullmove" => $board->blackcanfullmove,
				"whitecanfullmove" => $board->whitecanfullmove,
				"boardtype" => $board->boardtype,
				"gamestatus" => "Not Started",
				"whitelist" => array( '127.0.0.1', '::1'),
				"Moves" => 	$a,
				"ng" => $newgame);
		//echo json_encode($fendata);
	}
else 
{
	//if page is refreshed then refresh the page
	$serverrequesttype= "nrefresh"; /*refresh once //refresh multiple times //update// noaction*/

	$fendata=array("fen" => $fen,
	"systemcs" => $systemcookieset,
	"serverrequesttype" => $serverrequesttype,
	"blackcankill" => $board->blackcankill,
	"whitecankill" => $board->whitecankill,
	"blackcanfullmove" => $board->blackcanfullmove,
	"whitecanfullmove" => $board->whitecanfullmove,
	"boardtype" => $board->boardtype,
	"gamestatus" => "Already Started",
	"whitelist" => array( '127.0.0.1', '::1'),
	"Moves" => 	null,
	"ng" => $newgame);
	//ob_end_clean();
}

ob_end_clean();
echo json_encode($fendata);

//define('VIEWER', true);
//require_once('views/index.php');