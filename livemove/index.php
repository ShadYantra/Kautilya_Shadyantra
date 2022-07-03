<?php error_reporting(0);
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
$gamemode="livemove"; 
$systemlivegameid="";$blackplayer=0;
$boardtype="";$BlackGameID="";
$gameaction="lookoutformoves";
$whitelist = array( '127.0.0.1', '::1');

if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL ^ E_WARNING ^ E_ERROR ^ E_PARSE);
	// Turn off error reporting
error_reporting(0);
}
else
{
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL ^ E_WARNING ^ E_ERROR ^ E_PARSE);
	// Turn off error reporting
error_reporting(0);
}

if ( isset($_REQUEST['BlackGameID']) /*&&(!isset($_COOKIE['livegameid']))*/) {
	$systemlivegameid="../liveviews/".$_REQUEST['BlackGameID']; //if blank
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
						$splitted = explode( '$currentfen=',$line);
						$fen = $splitted[1];
						$matched=$matched+1;
					}
					else if (strpos($line, '$blackplayer=0') !== false) {
							$blackplayer=1;
							}
					else if (strpos($line, '$blackplayer=1') !== false) {
								$blackplayer=-1;
							}

					else if (strpos($line, '$currentfen=') !== false) {
						$splitted = explode( '$currentfen=',$line);
						$fen = $splitted[1];
							$matched=$matched+1; 
							$gametype="old";
							$currentmover=explode(' ', explode( ' ',$line)[1])[0];
					}
					else if (strpos($line, 'gid') !== false) {
						$whiteblackcookie="";
						$blackplayerassigned=true;
						$playertype=2;
							$splitted = explode( 'gid',$line);
							$fulllivegameid = 'gid'.$splitted[1];
							//$onlylivegameid =  explode( 'gid',$fulllivegameid)[0];
							$whiteblackcookie=';whitemover='.explode( ';whitemover=',$fulllivegameid)[1];
							$blackcookie=';blackmover='.explode( ';blackmover=',$fulllivegameid)[1];
							$BlackGameID=$_REQUEST['BlackGameID'].$blackcookie;

					$fileblackgamecookie=$BlackGameID;
					}

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

				$whiteblackcookie="blackmover";
				//extract the $whiteblackcookie="" from cookie;
				$blackplayerassigned=true;
				
				$playertype=2;
				if(($blackplayer==-1)|| ($blackplayer==0)) {
					$playertype=100;
					//$_COOKIE['livegameid']='gid'.$BlackGameID;
					//setcookie('livegameid', 'gid'.$BlackGameID);
					//setcookie('LiveStepType', 'none');
					//$_COOKIE['LiveStepType']='none';					  
					}
				else{
					//$_COOKIE['livegameid']=$BlackGameID;
					//setcookie('livegameid', $BlackGameID);

					//setcookie('LiveStepType', 'black'."_fen_".$fen);
					//$_COOKIE['LiveStepType']='black'."_fen_".$fen;
					
				}

				fclose($file);

				if(strpos($whiteblackcookie,'whitemover')!== false)
				{
				}
				else if(strpos($whiteblackcookie,'blackmover')!== false)
				{
					$playertype=2;
					$boardtype="black";
					
					if((file_exists($systemlivegameid))&&($playertype==2)){
						$data = file($systemlivegameid);// reads an array of lines
					
						$reading = fopen($systemlivegameid, 'r');
						$writing = fopen($systemlivegameid.'.tmp.txt', 'w');
					
						while (!feof($reading)) {
							$line = rtrim(fgets($reading),"\r\n");
							if (stristr($line,'$blackplayer=')==false) {
								$newgame='1';
								fputs($writing, $line.PHP_EOL);
							}
						}
			
						fclose($reading);fclose($writing);
						$currentdata = file_get_contents($systemlivegameid.'.tmp.txt');
						file_put_contents($systemlivegameid, $currentdata, LOCK_EX);
					}
					//assign the game to the userid also;
				}
	}
	else {
		//check local cookies if game already loaded
		//die("No such game exists. Reviewing the game;");
	}
}

?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<title>	ShadYantra </title>
		<link rel="stylesheet" href="../assets/style.css">
		<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script-->
	
		<link rel="stylesheet" href="../assets/modal.css" />

	</head>
	<body gameaction="<?php echo $gameaction; ?>" id="gamemode" gamemode="<?php echo $gamemode; echo '" ';if(($BlackGameID!=null) && ($BlackGameID!="")){ echo 'BlackGameID="'.$BlackGameID.'"';} ?>>
		<div class="two_columns">
			<div id="boardtype" name="<?php echo $boardtype; ?>">
				<div class="status_box" name='board_mover' id='1'> <?php /*echo $side_to_move; */ ?>
				</div>			
				<div id="hiddenfendiv" class="fencenter hideform">
  				<button id="fenclose" style="float: right;">X</button>
				<form> FEN: <p id="hiddenfen" value =""><?php /*echo $fen; */?></p>
				</form>
				</div>
				<table id="graphical_board" width="30%" height="90%" class="chess"></table>
				<!--input type="button" onclick="window.location='../.'" value="Local Game"-->

			</div>
			<div class="center hideform">
			    <button id="close" style="float: right;">X</button>
  				  <form method="post" action="livemove/">
 				       Game ID:<br>
    				    <input type="text" name="BlackGameID" value="">
   					     <br>
    				    <input type="submit" value="Submit">
  				  </form>
			</div>
			<div>
			<input type="hidden" display='none' hidden  id="blackcankill" value="<?php /*echo $board->blackcankill */?>" disabled readonly>
			<input type="hidden" display='none' hidden  id="whitecankill" value="<?php /*echo $board->whitecankill */?>" disabled readonly>

			<input type="hidden" display='none' hidden  id="blackcanfullmove"  value="<?php /*echo $board->blackcanfullmove */?>" disabled readonly>
			<input type="hidden" display='none' hidden  id="whitecanfullmove"  value="<?php /*echo $board->whitecanfullmove */?>" disabled readonly>
			<div style="display:none">
			<div id="textAreas" style="display:block;"> 
				<!--div style="position: relative" class = "container_row" --> 

				<ul id="tabs">
					  <li><a href="#layer1">Self</a></li>
					    <li><a href="#layer2">Opponent</a></li>
					</ul>
					<div id="layer1" class="layer1" class="tabcoachcontent tabContent tab-panel"> 
							<textarea style="top:0; left:0; z-index: 2;;" disabled readonly id="playerta" rows = "8" cols="50"> </textarea> 
					</div>
					<div id="layer2" class="layer2" class="tabcoachcontent tabContent tab-panel hidden">
							<textarea style="top:0; left:0; z-index: 1 ;;" disabled readonly id="opponentta" rows = "8" cols = "50"> </textarea> 
					</div>
				<!--/div-->
			</div>
			<div id="movestypes" name="movestypes" style="display:block;position: relative;top:40px">
			</div>
			</div>
			</div>	
			</div>
			<div >
			<div>

<!-- Trigger/Open The Modal -->
<button id="myBtn" hidden>Open Rules in POP-up</button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>Check the Rules.</p>

			<div id="textAreasRules"> 
				<!-- div style="position: relative" class = "container_row" --> 

				<ul id="tabs2">
					  <li><a href="#player1">Self Rule</a></li>
					    <li><a href="#player2">Common Rule</a></li>
					</ul>
					<div id="player1" class="player1" class="tabcoachcontent tabContent tab-panel"> 
							<textarea style="top:0; left:10px; z-index: 2;" disabled readonly id="player1ta" rows = "20" cols="100"> </textarea> 
					</div>
					<div id="player2" class="player2" class="tabcoachcontent tabContent tab-panel hidden">
							<textarea style="top:0; left:10px; z-index: 1;" disabled readonly id="player2ta" rows = "20" cols = "100"> </textarea> 
					</div>
				<!--/div-->
			</div>

			</div>

</div>			

			</div>
			</div>
			<div id="divmoves" name="divmoves" style="display:none;">
				<div id="history_move" name="history_move" style="display:none;"> 	Historical Moves:<br>
					<input id="history" name="history" size="19">
					<br><span id="steps_count"></span> Moves Count: <br>
				</div>
				<form id="all_moves" name="all_moves" hidden disabled readonly style="display:none;" method="post" > 	All Legal Moves:<br>
					<select id="livemoves" name="livemove" size="19">
					<input id="boardtype" name="boardtype" hidden value="">
				</form>				
			</div>
		</div>

		<div id="gameiddiv">
		<br/><p> FEN:</p>
		<form id="import_fen" method="post">
				<textarea id="fen" type="text" name="fen" cols= 55 	style='"<?php if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)) { echo 'none';}?>"' value="<?php /*echo explode( ';',$fen)[0]; */?>"></textarea></br>
				<input type="submit" value="Import FEN">
				<input type="button" value="Invert" name="" id="Invert" onclick="createDefaultCookie();" hidden/>
				<input id="import_boardtype" name="import_boardtype" hidden>
				<!--input type="button" id="perft" value="Perft"-->
		</form>

		<div class="gamemodal micromodal-slide" id="modal-1" aria-hidden="true">
  <div class="modal__overlay" tabindex="-1" data-micromodal-close>
    <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
      <header class="modal__header">
        <h2 class="modal__title">
          Live GameID (Shad Yantra)
        </h2>
        <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
      </header>
      <div class="modal-content-content">
        <div class="modal__content"> <p id="WhiteGameID_Data"></p><br/>
		<p id="WhiteGameFEN_Data"></p>
		</div>
        <footer class="modal__footer">
          <button class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close</button>
        </footer>
      </div>
    </div>
  </div>
</div>

<button name = "gameID" data-micromodal-trigger="modal-1" onclick='showgameid();' > Show Game ID </button>

	</div>
	</body>
	<script src="../assets/jquery-3.3.1.min.js"></script>
		<script src="../assets/jquery-ui.min.js"></script>	
	<script src="../assets/micromodal.min.js"></script>
	<script src="../assets/liverules.js"></script>
	<script src="../assets/livescripts.js"></script>

<div id="placeholder-modal-content"><div name="modalContent" id="modalContent" style="display:none" class="current-modal">
 <h2>Here are the available Options for :</h2>

 <div name="additionalmoves" id="additionalmoves"></div>
 <button type="button" class="" onclick="btn_additionalmoves();"> Submit</button>
 <button type="button" class="" onclick="cancel_additionalmoves();"> Cancel</button>
 <button type="button" id="btnclrmoves" class="" onclick="clear_additionalmoves();"> Clear</button>

</div></div>


	<div class="vanilla-modal">         
       <div class="modal">
       <div class="modal-inner"><div id="modal-content"><div id="modalContent" style="display:none" class="current-modal">
 <h2>Here are the available Options for :</h2>
 <div name="additionalmoves" id="additionalmoves"></div>
 <button type="button" class="" onclick="btn_additionalmoves();"> Submit</button>
 <button type="button" class="" onclick="cancel_additionalmoves();"> Cancel</button>
 <button type="button" class="" onclick="clear_additionalmoves();"> Clear</button>

</div></div></div></div></div-->

<script src="../assets/modal.js"></script>

</html>
