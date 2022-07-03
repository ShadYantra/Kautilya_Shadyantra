<?php

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

error_reporting(E_ALL);
ini_set('display_errors', 1);
if (( !isset($_REQUEST['lookformoves']))) {
    die("This file needs to be included into a viewer.");
} 
$whitelist = array( '127.0.0.1', '::1');
$systemgameid=null;
			//$filewhitecookie=explode(';', explode( ';whitemover=',$gameid)[1])[0];
			//$fileblackcookie=explode(';',explode(';blackmover=', $gameid)[1])[0];
			//$filewhitegamecookie='$gameid='.$onlygameid.';whitemover='.$filewhitecookie.';';
			//$fileblackgamecookie='$gameid='.$onlygameid.';blackmover='.$fileblackcookie.';';
			
require_once('../helpers/helper_functions.php');
require_once('../models/ChessRulebook.php');
require_once('../models/ChessBoard.php');
require_once('../models/ChessPiece.php');
require_once('../models/ChessMove.php');
require_once('../models/ChessSquare.php');

$board = new ChessBoard();

if ( isset($_REQUEST['reset']) ) {
	// Skip this conditional. ChessGame's FEN is the default, new game FEN and doesn't need to be set again.
} elseif ( isset($_REQUEST['livemove']) ) {
	$board->import_fen($_REQUEST['livemove']);
	if(isset($_REQUEST['import_boardtype']))
	$board->setboard($_REQUEST['import_boardtype']);
} elseif ( isset($_REQUEST['move']) ) {
	$board->import_fen($_REQUEST['move']);
	if(isset($_REQUEST['import_boardtype']))
	$board->setboard($_REQUEST['import_boardtype']);
} elseif ( isset($_REQUEST['surrendermove']) ) {
	$board->import_fen($_REQUEST['surrendermove']);
} elseif ( isset($_REQUEST['endgamemove']) ) {
	$board->import_fen($_REQUEST['endgamemove']);
} elseif ( isset($_REQUEST['fen']) && (in_array($_SERVER['REMOTE_ADDR'], $whitelist))) {
	$board->import_fen($_REQUEST['fen']);
	if(isset($_REQUEST['import_boardtype']))
	$board->setboard($_REQUEST['import_boardtype']);
}

if ( isset($_REQUEST['lookformoves']) && (strtolower($_REQUEST['lookformoves'])!='yes')) {
	$board->import_fen($_REQUEST['lookformoves']);
	$fen=$_REQUEST['lookformoves'];
	// Skip this conditional. ChessGame's FEN is the default, new game FEN and doesn't need to be set again.
} else {$fen = $board->export_fen();}

$side_to_move = $board->get_side_to_move_string();
$who_is_winning = $board->get_who_is_winning_string();
$graphical_board_array = $board->get_graphical_board();
$legal_moves = ChessRulebook::get_legal_moves_list($board->color_to_move, $board);

if($legal_moves!=null){
		$a = [];

		/*<input type="hidden" display='none' hidden  id="blackofficerscankill" value="<?php echo $board->blackcankill ?>" disabled readonly>
		<input type="hidden" display='none' hidden  id="whiteofficerscankill" value="<?php echo $board->whitecankill ?>" disabled readonly>

		<input type="hidden" display='none' hidden  id="blackofficerscanmovefull"  value="<?php echo $board->blackcanfullmove ?>" disabled readonly>
		<input type="hidden" display='none' hidden  id="whiteofficerscanmovefull"  value="<?php echo $board->whitecanfullmove ?>" disabled readonly>
		*/							

		foreach ( $legal_moves as $key => $move ): 
				if (($move->controlled_moves!=null)&&(( $move->starting_square->rank ) &&  ( $move->ending_square->rank )
				&& ( $move->starting_square->file) &&  ( $move->ending_square->file) &&
				( count($move->controlled_moves)>0)))
					{ 
						$Naraad_Mcount=count($move->controlled_moves);
						foreach ( $move->controlled_moves as $key1 => $nmove ):
								$notationvalue=""; $ending_square=$nmove->ending_square;
								$Ntype="";
								$nmove->ending_square=$ending_square;
								if(3-$nmove->color==$board->color_to_move)
									{ $Ntype=">";}
								$notationvalue=$Ntype.$nmove->get_notation();
								if($Ntype==">")
									{ $CMove='Yes';}
								else { $Move='No';}
								$movevalue= $move->board->export_fen_moves($nmove->starting_square,$nmove->ending_square,true);
			
								$tempa=array("option" => $movevalue,
									"data-coordinate-notation" => $notationvalue,
									"CMove" => 	$CMove);
								array_push(	$a,$tempa);
						endforeach;
					}
				else {
						$notationvalue=""; $ending_square=$move->ending_square;

						$Ntype="";
						if($move->pushedending_square!=null)
								$ending_square=$move->pushedending_square;
								$movevalue=$move->board->export_fen_moves($move->starting_square,$move->ending_square,false);
								$move->ending_square=$ending_square;
								$notationvalue=$Ntype.$move->get_notation();

								$tempa=array("option" => $movevalue, 
									"data_coordinate_notation" => $notationvalue,
									"CMove" => 	"No");
								array_push(	$a,$tempa);
					}
		endforeach;	

		$fendata=array("fen" => $fen,
			"blackcankill" => $board->blackcankill,
			"whitecankill" => $board->whitecankill,
			"blackcanfullmove" => $board->blackcanfullmove,
			"whitecanfullmove" => $board->whitecanfullmove,
			"boardtype" => $board->boardtype,
			"gamestatus" => "Not Started",
			"whitelist" => array( '127.0.0.1', '::1'),
			"Moves" => 	$a);
		echo json_encode($fendata);
		//echo json_encode($a);
		//return json_encode($fendata);
	}

//define('VIEWER', true);
//require_once('views/index.php');
