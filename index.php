<?php $gamemode="localmove";
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
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
 ?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<title>	ShadYantra </title>
		<link rel="stylesheet" href="./assets/style.css">
		<link rel="stylesheet" href="../assets/modal.css" />

		<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script-->
		<script src="./assets/jquery-3.3.1.min.js"></script>
		<style> 
			span[name="J"]{ -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg); } 
	
	span[name="j"]{ -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg); } 

	</style>
	</head>
	<body id="gamemode" gamemode="<?php echo $gamemode; ?>">
	
	
		<div class="two_columns">
		<?php if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){?>
	
			<div id="boardtype" name="<?php $boardtypeflag;$boardtype; ?>">
				<div class="status_box" name='board_mover' id='1'> <?php /*echo $side_to_move; */ ?>
				</div>
				<div id="hiddenfendiv" class="fencenter hideform">
  				<button id="fenclose" style="float: right;">X</button>
				<form> FEN: <p id="hiddenfen" value =""><?php /*echo $fen; */?></p>
				</form>
				</div>
				<table id="graphical_board" width="30%" height="90%" class="chess"></table>
				<!-- <input type="submit" name="flip" value="Flip The Board"> -->
				<input type="button" onclick="window.location='.'" value="Reset The Board">			
			</div>
			<?php }?>
			<div>

			<div class="gamechoicemodal micromodal-slide" id="modal-1" aria-hidden="true">
  <div class="modal__overlay" tabindex="-1" data-micromodal-close>
    <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
      <header class="modal__header">
        <h2 class="modal__title">
          Live GameID (Shad Yantra)
        </h2>
        <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
      </header>
      <div class="modal-content-content">
        <div class="modal__content">
		<input type="radio" name="livegame_Whitechoice_1" id="livegame_Whitechoice_1" readonly disabled  onclick="livegame(1);">
        <label for="livegame_Whitechoice_1" style="background-color : #d1d1d1;">Play ShadYantra LiveGame as White (Stable)</label> <br/>
		<input type="radio" name="livegame_Whitechoice_2" id="livegame_Whitechoice_2" selected onclick="livegame(2);">
        <label for="livegame_Whitechoice_2">Play Kautilya ShadYantra LiveGame as White (99% coded) </label>	<br/>	
		<input type="radio" name="livegame_PairedBlackchoice" id="livegame_PairedBlackchoice" onclick="pairedpopup()">
		<!--input type="radio" name="cancel_unstartedgame" id="cancel_unstartedgame" onclick="cancelorphanedgame()"-->

        <label for="livegame_PairedBlackchoice">Use the GameID to play a LiveGame as Black </label>

		<!--div id="center hideform"-->
  				  <!--form method="post" action="./livemove/"-->
						<br/><label name="BlackGameID_" for="BlackGameID_">Game ID </label>
    				    <input type="text" name="BlackGameID_" maxlength="30" size="35" value="">
    				    <!--input type="submit" value="Submit"-->
  				  <!--/form-->
			<!--/div-->
		</div>

        <footer class="modal__footer">
          <button class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close</button>
		  <button class="modal__btn" aria-label="Submit the option" id="modalsubmit" type="submit" value="Submit" onclick="smartgamechoice()">Submit</button>
        </footer>
      </div>
    </div>
  </div>
</div>			
			
			<!--input type="button" onclick="window.location='livemove/'" value="Live Game"-->
			<input type="button" data-micromodal-trigger="modal-1" onclick='showgamechoices();' value="Live Game">
			<!--input type="button" id="livepairing"  name="livepairing" value="Live Paired Game"-->			

			<input type="hidden" display='none' hidden  id="blackcankill" value="<?php /*echo $board->blackcankill */?>" disabled readonly>
			<input type="hidden" display='none' hidden  id="whitecankill" value="<?php /*echo $board->whitecankill */?>" disabled readonly>

			<input type="hidden" display='none' hidden  id="blackcanfullmove"  value="<?php /*echo $board->blackcanfullmove */?>" disabled readonly>
			<input type="hidden" display='none' hidden  id="whitecanfullmove"  value="<?php /*echo $board->whitecanfullmove */?>" disabled readonly>
			<div>
			<div id="textAreas" style="display:none"> 
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
<button id="myBtn">Open Rules in POP-up</button>

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
					<select id="moves" name="move" size="19"></select><br>
					<input id="boardtype" name="boardtype" hidden value="">
				</form>
			</div>
		</div>
		
		<div>
		<?php if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){?>
			<br/><p> FEN:
			<form id="import_fen" method="post"> 
				<input id="fen" type="text" name="fen" value="<?php /*echo $fen; */?>"></br>
				<input type="submit" value="Import FEN"> 
				<input type="button" value="Invert" name="" id="Invert" onclick="createDefaultCookie();"/> </br>
				<input id="import_boardtype" name="import_boardtype" hidden>
				<!--input type="button" id="perft" value="Perft"-->
			</p>
		</form>	
		<?php }?>
		</div>
		</div>
	</body>
		<script src="./assets/jquery-ui.min.js"></script>	
	<script src="./assets/micromodal.min.js"></script>
	<script src="./assets/rules.js"></script>
	<script src="./assets/scripts.js"></script>
	<script src="./assets/modal.js"></script>

</html>