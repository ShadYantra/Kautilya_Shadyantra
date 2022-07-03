<?php

//if (! defined('VIEWER')) {
    //die("This file needs to be included into a viewer.");
//}
$gamemode="move";

?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<title>	ShadYantra </title>
		<link rel="stylesheet" href="assets/style.css">
		<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script-->
		<script src="assets/jquery-3.3.1.min.js"></script>
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
	<body>
		
		<div class="two_columns">
			<div name="<?php $boardtypeflag;$boardtype; ?>">
				<div class="status_box" id='<?php echo $board->	color_to_move; ?>'> <?php echo $side_to_move; ?>
				</div>
				<div class="fencenter hideform">
  				<button id="fenclose" style="float: right;">X</button>
				<form> FEN: <p><?php echo $fen; ?></p>
				</form>
				</div>
				<?php $boardtypeflag=$board->boardtype;
				if ($boardtypeflag==1) {
					define('whiteboard', true);	$boardtypeflag="white";
					require_once('views/whiteboard.php');
				} 
				if ($boardtypeflag==2) { define('blackboard', true);
					$boardtypeflag="black"; 
					require_once('views/blackboard.php');
					}?>
				
				<!-- <input type="submit" name="flip" value="Flip The Board"> -->
				<input type="button" onclick="window.location='.'" value="Reset The Board">
				<input type="button" onclick="window.location='livemove/'" value="Live Game">
				<input type="button" id="livepairing"  name="livepairing" value="Live Paired Game">				
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
			<input type="hidden" display='none' hidden  id="blackofficerscankill" value="<?php echo $board->blackcankill ?>" disabled readonly>
			<input type="hidden" display='none' hidden  id="whiteofficerscankill" value="<?php echo $board->whitecankill ?>" disabled readonly>

			<input type="hidden" display='none' hidden  id="blackofficerscanmovefull"  value="<?php echo $board->blackcanfullmove ?>" disabled readonly>
			<input type="hidden" display='none' hidden  id="whiteofficerscanmovefull"  value="<?php echo $board->whitecanfullmove ?>" disabled readonly>

			<div>
			<div id="textAreas" style="display:block"> 
				<div style="position: relative" class = "container_row"> 
					<div id="layer1" class="layer1" style="position: relative;top:10px"> 
					<label> Self </label>
						<div>
							<textarea style="top:0; left:0; z-index: 2;" disabled readonly id="playerta" rows = "8" cols="60"> </textarea> 
						</div>	
					</div>
					<div id="layer2" class="layer2" style="position: relative;top:30px">
					<label> Opponent </label>
						<div>
							<textarea style="top:0; left:0; z-index: 1;" disabled readonly id="opponentta" rows = "8" cols = "60"> </textarea> 
						</div>
					</div>
				</div>
			</div>
			<div  style="display:block;position: relative;top:40px">
			<div id="KingMoves" class="container KingMoves">
				
			<div style="float:left;width:18%;">
				<form id="make_move" name="make_move" hidden disabled readonly style="display:none;"  method="POST">
				<label >Normal:<br/></label>
					<select id="<?php echo $gamemode; ?>" name="<?php echo $gamemode; ?>" size="10"></select>	
					<!--br><span id="move_count"></span> 	Moves : <br-->
					<input id="submitmove" type="submit" value="Make Move">
				</form>
			</div>

			<div style="float:left;width:18%;">
				<form id="naarad_cmove" name="naarad_cmove" hidden disabled readonly style="display:none;"  method="POST">
				<label >Controlled:<br/></label>
					<select id="cmove" name="<?php echo $gamemode; ?>" size="10"></select>	
					<!--br><span id="move_count"></span> 	Moves : <br-->
					<input id="submitcmove" type="submit" value="Make Move">
				</form>
			</div>

				<div style="float:left;width:19%;">
					<form id="king_endgame" name="king_endgame" hidden disabled readonly style="display:none;">
					<label id="lblViraam">Viraam: <br/></label>
						<select id="endgamemove" name="<?php echo $gamemode; ?>" size="10"></select>
							<!--br><span id="endgamemove_count"></span>Moves : <br-->
						<input id="submitendgamemove" type="submit" value="Make Move">

					</form>
				</div>

				<div style="float:left;width:19%;">
					<form id="recall" name="recall" hidden disabled readonly style="display:none;">
					<label id="lblSandhi">Sandhi<br/></label>
						<select id="recallmove" name="<?php echo $gamemode; ?>" size="5"></select>
							<!--br><span id="ReCallmove_count"></span>Moves Count: <br-->
						<input id="submitrecallmove" type="submit" value="Make Move">

					</form>
				</div>
				<div style="float:left;width:17%;">
					<form id="king_Shanti" name="king_Shanti" hidden disabled readonly style="display:none;">
					<label id="lblShanti">	</label>
						<select id="Shantimove" name="move" size="5"></select>
							<!--br><span id="ReCallmove_count"></span>Moves Count: <br-->
						<input id="submitShantimove" type="submit" value="Make Move">

					</form>
				</div>
				<div style="float:left;width:16%;">
					<form id="winninggame" name="winninggame" hidden disabled readonly>
					<label >Winning: <br/></label>
						<select id="winninggamemove" name="move" size="8"></select>
							<!--br><span id="winninggamemove_count"></span>Moves : <br-->
						<input type="submit" id="	" value="Make Move">

					</form>
				</div>
				
				<div style="float:right;width:18%;;padding-right:10px;">
					<form id="king_surrender" name="king_surrender" hidden disabled readonly style="display:none;">
					<label >Surrender: <br/></label>
						<select id="surrendermove" name="move" size="10"></select>
							<!--br><span id="surrendermove_count"></span>Moves : <br-->	
						<input type="submit" id="submitsurrendermove" value="Make Move">

					</form>
				</div>
			</div>
			</div>
			</div>
			</div>
			<div >
			<div>
			<div id="textAreasRules"> 
				<div style="position: relative" class = "container_row"> 
					<div id="player1" class="layer1" style="position: relative;top:10px;padding:10px;"> 
					<label> Self Rule </label>
						<div>
							<textarea style="top:0; left:0; z-index: 2;" disabled readonly id="player1ta" rows = "12" cols="50	"> </textarea> 
						</div>
					</div>
					<div id="player2" class="layer2" style="position: relative;top:30px;padding:10px;">
					<label> Common Rule </label>
						<div>
							<textarea style="top:0; left:0; z-index: 1;" disabled readonly id="player2ta" rows = "12" cols = "50"> </textarea> 
						</div>
					</div>
				</div>
			</div>


			</div>
			</div>
			<div style="display:none;">
				<div id="history_move" name="history_move" style="display:none;"> 	Historical Moves:<br>
					<input id="history" name="history" size="19">
					<br><span id="steps_count"></span> Moves Count: <br>
				</div>
				<form id="all_moves" name="all_moves" hidden disabled readonly style="display:none;" method="post" > 	All Legal Moves:<br>
					<select id="moves" name="move" size="19">
					<?php $Naraad_Mcount=0;foreach ( $legal_moves as $key => $move ): 
						if (($move->controlled_moves!=null)&&(( $move->starting_square->rank ) &&  ( $move->ending_square->rank )
						&& ( $move->starting_square->file) &&  ( $move->ending_square->file) &&
						( count($move->controlled_moves)>0)))
							{ 
								$Naraad_Mcount=count($move->controlled_moves);
							foreach ( $move->controlled_moves as $key1 => $nmove ):
							?>
							<option
								value="<?php $notationvalue=""; $ending_square=$nmove->ending_square;
									$Ntype="";
								echo $move->board->export_fen_moves($nmove->starting_square,$nmove->ending_square,true); 
								$nmove->ending_square=$ending_square;
								if(3-$nmove->color==$board->color_to_move)
									{ $Ntype=">";}
								$notationvalue=$Ntype.$nmove->get_notation();?>"
								data-coordinate-notation="<?php echo $notationvalue.'"';
								if($Ntype==">")
									{ echo  "CMove='Yes'";}
								else { echo  "CMove='No'";}
								echo  " pushed-value='".$notationvalue."'>";
								echo $notationvalue; ?>
							</option>
							<?php endforeach;
							} else { ?>
							<option
								value="<?php $notationvalue=""; $ending_square=$move->ending_square;
								if(($move->starting_square->rank==5)&&($move->starting_square->file==4))
									$ttt=1;
									$Ntype="";
								if($move->pushedending_square!=null)
									$ending_square=$move->pushedending_square;
							echo $move->board->export_fen_moves($move->starting_square,$move->ending_square,false/* $move->board->[$ending_square->file][$ending_square->rank] */); 
								$move->ending_square=$ending_square;
								//if($move->board->color_to_move==$board->color_to_move)
									//{ $Ntype=">";}
								$notationvalue=$Ntype.$move->get_notation();?>"
								data-coordinate-notation="<?php echo $notationvalue.'"';
								//if($Ntype==">")
									//{ echo  "CMove='Yes'";}
								//else 
								{ echo  "CMove='No'";}
								echo  " pushed-value='".$notationvalue."'>";
								echo $notationvalue; ?>
							</option>
						<?php } endforeach; ?>

					</select><br>

					Move Count: <?php echo count($legal_moves)+$Naraad_Mcount; ?><br>
					<!--input type="submit" display='none' hidden value="Make Move"-->
					<input id="boardtype" name="boardtype" hidden value="">
					<?php
					
					$time = microtime();
					$time = explode(' ', $time);
					$time = $time[1] + $time[0];
					$finish = $time;
					$total_time = round(($finish - $start), 4);	
					$total_time *= 1000;
					$total_time = round($total_time);
					?>
					
					Load Time: <?php echo $total_time; ?> ms<br>
				</form>
			</div>
		</div>
		
		<div>
		<?php if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){?>
			<br/><p> FEN:
			<form id="import_fen" method="post"> 
				<input id="fen" type="text" name="fen" value="<?php echo $fen; ?>"></br>
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
	<script src="assets/scripts.js"></script>
	<script>

$('#livepairing').on('click', function () {
	//alert("hello");
	//this.window.location='livemove\\index.php?paired=yes';
	//$('#show').on('click', function () {
    	$('.center').show();
    	$(this).hide();
	//});

	$('#close').on('click', function () {
    	$('.center').hide();
   	 $('#show').show();
	});
});

$('.status_box').on('click', function () {
    $('.fencenter').toggleClass("hideform");

	$('#fenclose').on('click', function () {
    	$('.fencenter').hide();
   	 $('#fenshow').show();
	});
});
	</script>
</html>