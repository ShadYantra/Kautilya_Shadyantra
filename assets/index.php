<?php

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

error_reporting(E_ALL);
ini_set('display_errors', 1);
$whitelist = array( '127.0.0.1', '::1');
$systemgameid=null;
			//$filewhitecookie=explode(';', explode( ';whitemover=',$gameid)[1])[0];
			//$fileblackcookie=explode(';',explode(';blackmover=', $gameid)[1])[0];
			//$filewhitegamecookie='$gameid='.$onlygameid.';whitemover='.$filewhitecookie.';';
			//$fileblackgamecookie='$gameid='.$onlygameid.';blackmover='.$fileblackcookie.';';
			
require_once('helpers/helper_functions.php');
require_once('models/ChessRulebook.php');
require_once('models/ChessBoard.php');
require_once('models/ChessPiece.php');
require_once('models/ChessMove.php');
require_once('models/ChessSquare.php');

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

$fen = $board->export_fen();
$side_to_move = $board->get_side_to_move_string();
$who_is_winning = $board->get_who_is_winning_string();
$graphical_board_array = $board->get_graphical_board();
$legal_moves = ChessRulebook::get_legal_moves_list($board->color_to_move, $board);

define('VIEWER', true);
require_once('views/index.php');
