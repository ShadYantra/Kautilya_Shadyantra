<?php
//If commandars are dead or sleeping then army cannot change Territories. Even with the help of royals
class ChessRulebook {
	const NORTH = 1; const SOUTH = 2; const EAST = 3; const WEST = 4; const NORTHWEST = 5; const NORTHEAST = 6; const SOUTHWEST = 7; const SOUTHEAST = 8;
	const ALL_DIRECTIONS = array( self::NORTH, self::SOUTH, self::EAST, self::WEST, self::NORTHWEST, self::NORTHEAST, self::SOUTHWEST, self::SOUTHEAST );
	
	// Coordinates are in (rank, file) / (y, x) format
	const OCLOCK_OFFSETS = array(
		1 => array(2,1), 2 => array(1,2), 3 => array(2,0), 4 => array(-1,2),
		5 => array(-2,1), 6 => array(-2,0), 7 => array(-2,-1), 8 => array(-1,-2),
		9 => array(0,-2), 10 => array(1,-2), 11 => array(2,-1), 12 => array(0,2),
		13 => array(2,2), 14 => array(2,-2), 15 => array(-2,2), 16 => array(-2,-2)
	);

	const DIRECTION_OFFSETS = array( self::NORTH => array(1,0), self::SOUTH => array(-1,0), self::EAST => array(0,1), self::WEST => array(0,-1), self::NORTHEAST => array(1,1), self::NORTHWEST => array(1,-1), self::SOUTHEAST => array(-1,1), self::SOUTHWEST => array(-1,-1));
	const RBottom_DIRECTIONS = array( self::WEST, self::NORTH, self::NORTHWEST );
	const LBottom_DIRECTIONS = array( self::EAST, self::NORTH, self::NORTHEAST );
	const RTop_DIRECTIONS = array( self::WEST, self::SOUTH, self::SOUTHWEST	);
	const LTop_DIRECTIONS = array( self::EAST, self::SOUTH, self::SOUTHEAST );
	const Bottom_DIRECTIONS = array( self::EAST, self::WEST, self::NORTH, self::NORTHEAST, self::NORTHWEST );
	const Top_DIRECTIONS = array( self::EAST, self::WEST, self::SOUTH, self::SOUTHWEST, self::SOUTHEAST );
	const L_DIRECTIONS = array( self::EAST, self::NORTH, self::SOUTH, self::NORTHEAST, self::SOUTHEAST );
	const R_DIRECTIONS = array( self::WEST, self::NORTH, self::SOUTH, self::NORTHWEST, self::SOUTHWEST );

	const MID_DIRECTIONS = array( self::EAST, self::WEST, self::NORTH, self::SOUTH, self::SOUTHEAST, self::SOUTHWEST, self::NORTHEAST, self::NORTHWEST );

	const BISHOP_DIRECTIONS = array( self::NORTHWEST, self::NORTHEAST, self::SOUTHWEST, self::SOUTHEAST /*self::NORTH,	//self::SOUTH, //self::EAST, //self::WEST*/ );
	const RETREATING_BISHOP_DIRECTIONS_1 = array( self::SOUTHWEST, self::SOUTHEAST /*self::EAST, //self::WEST*/	);
	const RETREATING_BISHOP_DIRECTIONS_2 = array( self::NORTHWEST, self::NORTHEAST /*self::EAST, //self::WEST*/	);
	const ROOK_DIRECTIONS = array( self::NORTH, self::SOUTH, self::EAST, self::WEST );
	const RETREATING_ROOK_DIRECTIONS_1 = array( self::SOUTH, self::EAST, self::WEST );	
	const RETREATING_ROOK_DIRECTIONS_2 = array(	self::NORTH, self::EAST, self::WEST );
	const GENERAL_DIRECTIONS = array( self::NORTH, self::SOUTH, self::EAST, self::WEST, self::NORTHWEST, self::NORTHEAST, self::SOUTHWEST, self::SOUTHEAST );
	const RETREATING_GENERAL_DIRECTIONS_1 = array( self::SOUTH, self::EAST, self::WEST, self::SOUTHWEST, self::SOUTHEAST );
	const RETREATING_GENERAL_DIRECTIONS_2 = array( self::NORTH, self::EAST, self::WEST, self::NORTHWEST, self::NORTHEAST );
	const KING_DIRECTIONS = array( self::NORTH, self::SOUTH, self::EAST, self::WEST, self::NORTHWEST, self::NORTHEAST, self::SOUTHWEST, self::SOUTHEAST );
	const KNIGHT_DIRECTIONS = array(1, 2, 3,4, 5, 6,7, 8, 9,10, 11,12,13,14,15,16);

	const PAWN_DIRECTIONS = array(self::NORTH, self::SOUTH, self::EAST, self::WEST, self::NORTHWEST, self::NORTHEAST, self::SOUTHWEST, self::SOUTHEAST);

	const RETREATING_KNIGHT_DIRECTIONS_1 = array(4, 5, 6,  7,  8,  9, 12,15,16);
	const RETREATING_KNIGHT_DIRECTIONS_2 = array(1, 2, 3, 9, 10, 11, 12,13,14);
	const RETREATING_KNIGHT_DIRECTIONS_11 = array(5, 6,  7,15,16);
	const RETREATING_KNIGHT_DIRECTIONS_22 = array(1, 3, 11,13,14);

	const RETREATING_BLACK_PAWN_DIRECTIONS = array(self::NORTH,self::NORTHEAST, self::NORTHWEST);
	const RETREATING_WHITE_PAWN_DIRECTIONS = array(self::SOUTH,self::SOUTHEAST, self::SOUTHWEST);
		
	const PROMOTION_PIECES = array( ChessPiece::GENERAL, ChessPiece::ROOK, ChessPiece::BISHOP, ChessPiece::KNIGHT,ChessPiece::SPY,ChessPiece::KING,ChessPiece::INVERTEDKING,ChessPiece::ARTHSHASTRI,ChessPiece::GODMAN );
	
	const MAX_SLIDING_DISTANCE = 3;
	const MAX_TOUCH = 1;
	static function get_legal_moves_list(
		$color_to_move, // Color changes when we call recursively. Can't rely on $board for color.
		ChessBoard $board, // ChessBoard, not ChessBoard->board. We need the entire board in a couple of methods.
		bool $need_perfect_move_list = TRUE,
		bool $store_board_in_moves = TRUE,
		bool $need_perfect_notation = TRUE
	): array {
		//**echo '<li> ChessRuleBook.php #1 function get_legal_moves_list called </li>';
		$selfbrokencastle =null;
		$foebrokencastle =null;
		$pieces_to_check = self::get_all_pieces_by_color($color_to_move, $board);
		
		$moves = array();
		$king = null;$naard_can_move=true;
		
		// TODO: Iterate through all squares on chessboard, not all pieces. Then I won't need to
		// store each piece's ChessSquare, and I can get rid of that class completely.

		//null means King is Active now
		$board->set_sleeping_Royals_SemiRoyals_commandars();		
		$board->get_ROYALs_on_Scepters_TruceControl(1);
		$board->get_ROYALs_on_warZone_for_full_move(1);
		$board->get_ROYALs_on_castle_for_full_move(1); //1 means General must be there// 0 means ROYAL or general //3 means supertight
		$board->get_general_on_warZone_for_full_move(1); //1 means General must be there// 0 means ROYAL or general //3 means supertight
		$board->get_general_on_castle_for_full_move(1);
		$board->get_generals_on_truce(1);
		$board->get_compromised_castles();
		$board->setRoyalZone();
		$get_Killing_Allowed=0;	$nonnaarad_can_move=true;

		if(($board->controller_color!=null) &&(($board->controller_color==$color_to_move)&&($board->controlled_color==3-$color_to_move))){
			$get_Killing_Allowed=0;
			//if($board->gametype == 1){
				$naard_can_move=true;
				$nonnaarad_can_move=false;
				self::set_naarad_for_fullmoves($board);
				//}
			/*else if($board->gametype>=2){
					$naard_can_move=false;
					$nonnaarad_can_move=true;
					}*/
			}
		else if(($board->controller_color==null)&&($board->controlled_color==null)){
			$naard_can_move=true;
			$nonnaarad_can_move=true;
			self::set_naarad_for_fullmoves($board);
			$board->controller_color=null;$board->controlled_color=null;
			}

		self::set_general_for_elevatedmoves($board);
		
		$get_FullMover=FALSE;//Check if killing allowed
		$get_CASTLEMover=-1;
		if($color_to_move==1) { $selfbrokencastle=  $board->wbrokencastle;$foebrokencastle= $board->bbrokencastle; }
		if($color_to_move==2) { $selfbrokencastle=  $board->bbrokencastle;$foebrokencastle= $board->wbrokencastle; }

		if(($board->Winner=='-1')||($board->Winner=='0')){
			foreach ($pieces_to_check as $piece) {
				$get_CASTLEMover=-1; $get_FullMover=null;

				if ($piece->type == ChessPiece::SPY){
					$get_FullMover=$get_FullMover;
				}
				if(($piece->group=='ROYAL')||($piece->group=='SEMIROYAL')){
					$get_FullMover=null;
					if(self::get_piece_castle_with_ROYALs($piece,$color_to_move,$board)==1){
						$get_FullMover=TRUE;//peice in its ownself castle
						$get_CASTLEMover=1;
						}
					elseif(self::get_piece_castle_with_ROYALs($piece,$color_to_move,$board)==0){
						$get_CASTLEMover=0;//foe castle has 1 ROYAL
						}
				}
				/*if (($piece->type == ChessPiece::PAWN) &&($color_to_move==2)) {
				$ttt=1;
				}*/

				if(($piece->group=='OFFICER')||($piece->group=='SOLDIER')){
					if($piece->color==1){
						if(($piece->type!=ChessPiece::GENERAL)&&($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9)&&($board->whitecanfullmove==1))
							{
							$get_FullMover=TRUE;
							}
						elseif(($piece->type==ChessPiece::GENERAL)&&($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9)&&($board->whitescanfullmove==1))
							{
							$get_FullMover=TRUE;
							}
						elseif(($piece->square->rank==0)&&($piece->square->file>0)&&($piece->square->file<9))
							{
							$get_FullMover=TRUE;
							}
						elseif(($piece->type!=ChessPiece::GENERAL)&&($piece->square->rank==9)&&($piece->square->file>0)&&($piece->square->file<9)&&(($board->whitecanfullmoveinfoecastle==1)||($board->whitecanfullmove==1)))
							{
							$get_FullMover=TRUE;
							}
						elseif(($piece->type==ChessPiece::GENERAL)&&($piece->square->rank==9)&&($piece->square->file>0)&&($piece->square->file<9)&&(($board->whitecanfullmoveinfoecastle==1)||($board->whitescanfullmove==1)))
							{
							$get_FullMover=TRUE;
							}
						elseif(($piece->square->rank==0)&&($piece->square->file>0)&&($piece->square->file<9)&&($board->whitecanfullmoveinowncastle==1))
							{
							$get_FullMover=TRUE;
							}
						else
							$get_FullMover=FALSE;
						
						if($board->whitecankill==1) 
							$get_Killing_Allowed=1;
						else  $get_Killing_Allowed=0;

						if(self::get_piece_castle_with_ROYALs($piece,$color_to_move,$board)==1){
							$get_FullMover=TRUE;//peice in its ownself castle
							$get_CASTLEMover=1;
							}
						elseif(self::get_piece_castle_with_ROYALs($piece,$color_to_move,$board)==0){
							$get_CASTLEMover=0;//foe castle has 1 ROYAL
							$get_FullMover=TRUE;//peice in its ownself castle
							}
						//else -1 means WAR zone should be checked.	
						}
					elseif($piece->color==2){
						if(($piece->type!=ChessPiece::GENERAL)&&($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9)&&($board->blackcanfullmove==1))
							{
								$get_FullMover=TRUE;
							}
						elseif(($piece->type==ChessPiece::GENERAL)&&($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9)&&($board->blackscanfullmove==1))
							{
								$get_FullMover=TRUE;
							}
						elseif(($piece->square->rank==9)&&($piece->square->file>0)&&($piece->square->file<9))
							{
								$get_FullMover=TRUE;
							}
						elseif(($piece->type!=ChessPiece::GENERAL)&&($piece->square->rank==9)&&($piece->square->file>0)&&($piece->square->file<9)&&(($board->blackcanfullmoveinfoecastle==1)||($board->blackcanfullmove==1)))
							{
								$get_FullMover=TRUE;
							}
						elseif(($piece->type==ChessPiece::GENERAL)&&($piece->square->rank==9)&&($piece->square->file>0)&&($piece->square->file<9)&&(($board->blackcanfullmoveinfoecastle==1)||($board->blackscanfullmove==1)))
							{
								$get_FullMover=TRUE;
							}
						elseif(($piece->square->rank==0)&&($piece->square->file>0)&&($piece->square->file<9)&&($board->blackcanfullmoveinowncastle==1))
							{
								$get_FullMover=TRUE;
							}
						else  $get_FullMover=FALSE;

						if($board->blackcankill==1) 
							$get_Killing_Allowed=1;
						else  $get_Killing_Allowed=0;
						if(self::get_piece_castle_with_ROYALs($piece,$color_to_move,$board)==1){
							$get_FullMover=TRUE;//peice in its ownself castle
							$get_CASTLEMover=1;
							}
						elseif(self::get_piece_castle_with_ROYALs($piece,$color_to_move,$board)==0){
							$get_CASTLEMover=0;//foe castle has 1 ROYAL
							$get_FullMover=TRUE;//peice in its ownself castle
							}
						//else -1 means WAR zone should be checked.	
						}
					}

				if(($board->gametype>=1))
					$jumpstyle='3';//1 = Straight, 2 = diagonal , 3= both

					/*if ($piece->type == ChessPiece::KNIGHT)
						$ttt=1;*/
					if(($board->gametype>=1) &&($get_FullMover!==true) && (($piece->group=="OFFICER")|| ($piece->group=="SEMIROYAL"))){
						$get_FullMover=self::check_general_ROYAL_neighbours_promotion(self::KING_DIRECTIONS, $piece, $color_to_move, $board);
						}
					else if(($board->gametype>=1) && ($get_FullMover==null) && (($piece->group=="ROYAL") ||  ($piece->group=="SEMIROYAL"))){
						//$get_FullMover=self::has_ROYAL_neighbours(  self::KING_DIRECTIONS, $piece->square, $piece->square, $color_to_move, $board );
						$get_FullMover=self::check_general_ROYAL_neighbours_promotion(self::KING_DIRECTIONS, $piece, $color_to_move, $board);
						}

				if($naard_can_move==false){
					$get_Killing_Allowed=0;
				};
				$piece->neighborgeneral=null;

				//Dharm Yudda Showstopper defects caught by Basant Barupal
				if(($piece->square->rank==6) && ($piece->square->file==7))
				{
					$ttt=1;
				}
				$board->isCurrentZoneRoyal=$board->checkRoyalZone($piece);
				if(($board->isCurrentZoneRoyal)) {$get_FullMover=true; $get_Killing_Allowed=1; }

				$senapati_square=$board->get_general_square($color_to_move);

				if((($piece->group=="SOLDIER") || ($piece->group=="OFFICER")) && ($piece->type!=ChessPiece::GENERAL)){
					if(($senapati_square==null)){
						$get_Killing_Allowed=0;
						}
					else {
						$piece->neighborgeneral=self::has_general_neighbour(self::GENERAL_DIRECTIONS,$piece->square,$piece->square,$color_to_move , $board);
					}
				}
				
				//Spies/Royals and General is required for killing allowed
				if((($piece->group=="SOLDIER") || ($piece->group=="OFFICER"))&& ((($piece->color==1)&&($board->arewcommaderssleeping==true)) || (($piece->color==2)&&($board->arebcommaderssleeping==true)))){
					$get_Killing_Allowed=0;
				}

				//Zone with Square Royals. King and Arthshastri are complete set and in Active Squares make the Full Royal Zone. Shostopper defect fixed.
				if (($nonnaarad_can_move)&&($piece->type == ChessPiece::PAWN)) {
					/* */
						$moves = self::add_slide_moves_to_moves_list(self::PAWN_DIRECTIONS, 1, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,$selfbrokencastle,$foebrokencastle);
						if($get_Killing_Allowed==1){
							$moves = self::add_capture_moves_to_moves_list(self::PAWN_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,$selfbrokencastle,$foebrokencastle);
						}
					/* */
				} 
				elseif (($nonnaarad_can_move)&&($piece->type == ChessPiece::KNIGHT)) {
					/* */					
					$moves = self::add_capture_moves_to_moves_list(self::GENERAL_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,$selfbrokencastle,$foebrokencastle);
					 if($get_Killing_Allowed==1) 
						$get_Killing_Allowed=2;
					if(($get_CASTLEMover==1) &&	($get_Killing_Allowed==2)){	$get_Killing_Allowed=1;} //knight does not need to mixup in his own castle.
					if($get_FullMover==true)
						$moves = self::add_jump_and_jumpcapture_moves_to_moves_list(1,$jumpstyle,self::KNIGHT_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::GENERAL_DIRECTIONS, 1, $moves, $piece, $color_to_move, $board, $store_board_in_moves,0,1,FALSE,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					/* */
				}
				else if (($nonnaarad_can_move)&&($piece->type == ChessPiece::BISHOP) ) {
					/* */
					if(($board->gametype>=1) &&($board->gametype<=2)){ //Classical Agastya and //Kautilya
						$moves = self::add_capture_moves_to_moves_list(self::GENERAL_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,1,$selfbrokencastle,$foebrokencastle);
						$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::GENERAL_DIRECTIONS, 2, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					}				
					elseif($board->gametype==3){ 
						$moves = self::add_capture_moves_to_moves_list(self::BISHOP_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,$selfbrokencastle,$foebrokencastle);
						$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::BISHOP_DIRECTIONS, 2, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					}
					/* */
				} 
				elseif (($nonnaarad_can_move)&&($piece->type == ChessPiece::ROOK)) {
					/* */
					$moves = self::add_capture_moves_to_moves_list(self::GENERAL_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,$selfbrokencastle,$foebrokencastle);
					if(($board->gametype>=1)&&($board->gametype<=2)){
						$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::GENERAL_DIRECTIONS,  self::MAX_SLIDING_DISTANCE, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					}
					elseif($board->gametype==3){
						$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::ROOK_DIRECTIONS,  self::MAX_SLIDING_DISTANCE, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					}
					/* */
				} 
				elseif (($nonnaarad_can_move)&& ($piece->type == ChessPiece::GENERAL)) {
					//only in case of Shat Ranjan Senapati can be promoted..
					/* */
					$moves = self::add_capture_moves_to_moves_list(self::GENERAL_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,$selfbrokencastle,$foebrokencastle);
					$get_Killing_Allowed=1;
					if($get_FullMover==TRUE)
						$moves= self::add_jump_and_jumpcapture_moves_to_moves_list(1,$jumpstyle,self::KNIGHT_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,$get_FullMover,1,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::GENERAL_DIRECTIONS, self::MAX_SLIDING_DISTANCE, $moves, $piece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					/* */
				} 
				elseif (($nonnaarad_can_move)&&(($piece->type == ChessPiece::KING)||($piece->type == ChessPiece::INVERTEDKING))) {
					/* */
					$moves = self::add_capture_moves_to_moves_list(self::GENERAL_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,1,$selfbrokencastle,$foebrokencastle);
					$moves = self::add_jump_and_jumpcapture_moves_to_moves_list(2,$jumpstyle,self::KNIGHT_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,1,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::KING_DIRECTIONS, 4, $moves, $piece, $color_to_move, $board, $store_board_in_moves,1,1,TRUE,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);				
					/* */
					$king = $piece;
				} 
				elseif (($nonnaarad_can_move)&&(($piece->type == ChessPiece::ARTHSHASTRI))) {
					/* */
					if($get_FullMover==true)
						{
							//$moves = self::add_jump_and_jumpcapture_moves_to_moves_list(2,$jumpstyle,self::KNIGHT_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,0,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
							$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::KING_DIRECTIONS, 4, $moves, $piece, $color_to_move, $board, $store_board_in_moves,0,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
						}
					else if($get_FullMover==false)	
						$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::KING_DIRECTIONS, 1, $moves, $piece, $color_to_move, $board, $store_board_in_moves,0,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					$ARTHSHASTRI = $piece;
					/* */
				}
				elseif (($nonnaarad_can_move)&&($piece->type == ChessPiece::SPY)) {
					//Spy moves all pieces steps.. is lowest pawns till highest Royal Rank.. Like Lowest Officer with Royal power of King...
					/* */
					if($get_FullMover==true)
						{
						$moves = self::add_jump_and_jumpcapture_moves_to_moves_list(2,$jumpstyle,self::KNIGHT_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,0,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
						$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::KING_DIRECTIONS, 4, $moves, $piece, $color_to_move, $board, $store_board_in_moves,0,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
						}
					else if($get_FullMover==false)
						$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::KING_DIRECTIONS, 1, $moves, $piece, $color_to_move, $board, $store_board_in_moves,0,1,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
						
					$SPY = $piece;
					/* */
				}
				elseif (($piece->type == ChessPiece::GODMAN) &&($naard_can_move ==true)){ //Half Mover of ROYAL
					/* ArthShastri and Naarad have levels as per Kautilya.  */
					/* */
						$moves = self::add_slide_and_slidecontroller_moves_to_moves_list(self::GENERAL_DIRECTIONS, 4 , $moves, $piece, $color_to_move, $board, $store_board_in_moves,true,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
						//$moves = self::add_jump_and_jumpcapture_moves_to_moves_list(2,$jumpstyle,self::KNIGHT_DIRECTIONS, $moves, $piece, $color_to_move, $board, $store_board_in_moves,0,1,true,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,FALSE);
					$GODMAN = $piece;
					/* */
				}
			}
		}
		
		if ( $need_perfect_move_list ) {
			if ($king!=null) {
				$moves = self::eliminate_king_in_check_moves($king, $moves, $color_to_move);
			}
			else if(($king==null) && ($board->Winner!='-1')){
				$moves = null;	$moves = array();
				return $moves;
			}
		}
		
		if ( $need_perfect_notation ) {
			self::clarify_ambiguous_pieces($moves, $color_to_move, $board);			
			//self::mark_checks_and_checkmates($moves, $color_to_move);			
			$moves = self::sort_moves_alphabetically($moves);
		}
		
		return $moves;
	}
	
	static function sort_moves_alphabetically(array $moves): array {
		if ( ! $moves ) {
			return $moves;
		}
		foreach ( $moves as $move ) {
			$temp_array[$move->get_notation()] = $move;
		}
		
		ksort($temp_array);
		return $temp_array;
	}
	
	// Return format is the FIRST DUPLICATE. The second duplicate is deleted.
	// It keeps the original key intact.
	static function get_duplicates(array $array): array {
		return array_unique(array_diff_assoc($array, array_unique($array)));
	}
	
	// Returns void. Just modifies the ChessMoves in the $moves array by reference.
	static function clarify_ambiguous_pieces(array $moves, $color_to_move, ChessBoard $board): void {
		// For GENERALs, rooks, bishops, and knights
		foreach ( self::PROMOTION_PIECES as $type ) {
			// Create list of ending squares that this type of piece can move to
			$ending_squares = array();
			foreach ( $moves as $move ) {//if(ending_squares[])==b5
				$pushed="";
				if ( $move->tamedpawn !== 0 ) {
					$move->tamedpawn;
				}

				if ( $move->piece_type == $type ) {
					$pushed="";
					
					if(($move->board->board[$move->ending_square->rank][$move->ending_square->file]!=null) &&(($move->board->board[$move->ending_square->rank][$move->ending_square->file]->selfpushedpiece!=null)  && 
					($move->board->board[$move->ending_square->rank][$move->ending_square->file]->selfpushed==true)))
						$pushed="p";

						if(($move->tamedpawn==-1))	$pushed="-p"; 
						else if(($move->tamedpawn==1))	$pushed="p";
						else if(($move->tamedpawn==-2))	$pushed="-P";
						else if(($move->tamedpawn==2))	$pushed="P";
						else $pushed="";

						if( $move->piece_type == 7 ){
								$ttt=1;
						}

						if(( $move->piece_type == 7 ) &&	($move->board->commonborderbreached==true))
							$pushed="bb";
							if(( $move->piece_type == 7 ) &&	($move->board->commonborderbreached==false))
							$pushed="bnb";
					$ending_squares[] = $move->ending_square->get_alphanumeric().$move->piece_type.$pushed;
				}
			}
			
	
			/*foreach ( $moves as $move ) {	
				$pushed="";
					if(( $move->piece_type == 7 ) &&	($move->board->commonborderbreached==true))
						$pushed="bb";
					if(( $move->piece_type == 7 ) &&	($move->board->commonborderbreached==false))
						$pushed="bnb";

					$ending_squares[] = $move->ending_square->get_alphanumeric().$move->piece_type.$pushed;
					if( $move->piece_type == 7 ){
						$ttt=1;
					}
				}
			*/	
			// Isolate the duplicate squares
			$duplicates = self::get_duplicates($ending_squares);
			
			foreach ( $moves as $move ) {
				if ( $move->piece_type != $type ) {
					continue;
				}
				/* if(($move->starting_square->rank==5) && ($move->starting_square->file==1))
				$ttt=1;*/
				if ( ! in_array($move->ending_square->get_alphanumeric(), $duplicates) ) {
					continue;
				}
				
				$pieces_on_same_rank = $board->count_pieces_on_rank($move->piece_type, $move->starting_square->rank, $color_to_move);
				$pieces_on_same_file = $board->count_pieces_on_file($move->piece_type, $move->starting_square->file, $color_to_move);
				
				if ( $pieces_on_same_rank > 1 && $pieces_on_same_file > 1 ) {
					// TODO: This isn't perfect. If GENERALs on a8, c8, a6, the move Q8a7 will display as
					// Qa8a7, even though the GENERAL on c8 can't move there. To fix, we probably have to
					// generate a legal move list for each piece.
					$move->disambiguation = $move->starting_square->get_alphanumeric();
				} elseif ( $pieces_on_same_rank > 1 ) {
					$move->disambiguation = $move->starting_square->get_file_letter();
				} elseif ( $pieces_on_same_file > 1 ) {
					$move->disambiguation = $move->starting_square->rank;
				}
			}
		}
	}

	static function square_surrounded_by_anyone(
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board
	): ?ChessSquare {
		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;
		$minrank=1; $maxrank=8;

		//All ROYALs are exposed in CASTLE.	
		//Proxy Neighbours as CASTLE and WAR are mixed.
		if((($color_to_move==1)&&($board->whitecanfullmoveinfoecastle==1)) || (($color_to_move==2)&&($board->blackcanfullmoveinowncastle==1))){
			$maxrank=9;
		}

		if((($color_to_move==1)&&($board->whitecanfullmoveinowncastle==1)) || (($color_to_move==2)&&($board->blackcanfullmoveinfoecastle==1))){
			$minrank=0;
		}

		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);

		if ($ending_square) {
			if(($starting_square->file==0)||($starting_square->file==9)){
				if(($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank)&&($ending_square->file!=$starting_square->file))
				{//Non-truce zone endpoint but ROYAL is in Truce-Zone. return
				return null;
				}
			}
		}

		if (!$ending_square) {
			return null;
		} else {
			//Defective.... Check if the WAr-Zone and CASTLE are Mixed.  Incomplete ....
			if ((($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->rank>=$minrank)&&($ending_square->rank<=$maxrank) && ($ending_square->file>=1) && ($ending_square->file<=8))) //Check within War Zone
			{
				if ($board->board[$rank][$file]) {
					if ($board->board[$rank][$file]->color == $color_to_move)
					{
						 //*echo ' Ending square contains a friendly General ';*/
						return $ending_square;
					}
					else
						$ending_square=null;
				}
				else
				  $ending_square=null;
			}
			else
				$ending_square=null;

		}
		return $ending_square;
	}

	static function square_surrounded_by_officers(
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board
	): ?ChessSquare {

		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;
		$minrank=1; $maxrank=8;

		//All ROYALs are exposed in CASTLE.	
		//Proxy Neighbours as CASTLE and WAR are mixed.
		if((($color_to_move==1)&&($board->whitecanfullmoveinfoecastle==1)) || (($color_to_move==2)&&($board->blackcanfullmoveinowncastle==1))){
			$maxrank=9;
		}

		if((($color_to_move==1)&&($board->whitecanfullmoveinowncastle==1)) || (($color_to_move==2)&&($board->blackcanfullmoveinfoecastle==1))){
			$minrank=0;
		}

		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);

		if ($ending_square) {
			if(($starting_square->file==0)||($starting_square->file==9)){
				if(($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank)&&($ending_square->file!=$starting_square->file))
				{//Non-truce zone endpoint but ROYAL is in Truce-Zone. return
				return null;
				}
			}
		}

		if (!$ending_square) {
			return null;
		} else {
			//Defective.... Check if the WAr-Zone and CASTLE are Mixed.  Incomplete ....
			if ((($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->rank>=$minrank)&&($ending_square->rank<=$maxrank) && ($ending_square->file>=1) && ($ending_square->file<=8))) //Check within War Zone
			{
				if ($board->board[$rank][$file]) {
					//Code was missing for Semi-Royal
					if ( (($board->board[$rank][$file]->color == $color_to_move) && ($board->board[$rank][$file]->group=='OFFICER'))||
					(($board->board[$rank][$file]->color == $color_to_move) && ($board->board[$rank][$file]->group=='SEMIROYAL')))
					{
						 //*echo ' Ending square contains a friendly General ';*/
						return $ending_square;
					}
					else
						return null;
				}
				else
					return null;
			}
			else 
				return null;
		}
		return $ending_square;
	}

	static function square_surrounded_by_army(
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board
	): ?ChessSquare {

		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;
		$minrank=1; $maxrank=8;

		//All ROYALs are exposed in CASTLE.
	
		if((($color_to_move==1)&&($board->whitecanfullmoveinfoecastle==1)) || (($color_to_move==2)&&($board->blackcanfullmoveinowncastle==1))){
			$maxrank=9;
		}

		if((($color_to_move==1)&&($board->whitecanfullmoveinowncastle==1)) || (($color_to_move==2)&&($board->blackcanfullmoveinfoecastle==1))){
			$minrank=0;
		}

		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);

		if ($ending_square) {
			if(($starting_square->file==0)||($starting_square->file==9)){
				if(($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank)&&($ending_square->file!=$starting_square->file))
				{//Non-truce zone endpoint but ROYAL is in Truce-Zone. return
				return null;
				}
			}
		}

		if (!$ending_square) {
			return null;
		} else {
			//Defective.... Check if the WAr-Zone and CASTLE are Mixed.  Incomplete ....
			if ((($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->rank>=$minrank)&&($ending_square->rank<=$maxrank) && ($ending_square->file>=1) && ($ending_square->file<=8))) //Check within War Zone
			{
				if ($board->board[$rank][$file]) {
					if ( (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='OFFICER')||($board->board[$rank][$file]->group=='SOLDIER'))))
					{
						 //*echo ' Ending square contains a friendly General ';*/
						return $ending_square;
					}
					else
						return null;
				}
				else
					return null;
			}
			else 
				return null;
		}
		return $ending_square;
	}

	static function square_surrounded_by_nonROYALs_and_naarad(
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board
	): ?ChessSquare {
	
		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;
		$minrank=1; $maxrank=8;

		//All ROYALs are exposed in CASTLE.
		//Proxy Neighbours as CASTLE and WAR are mixed.
		if((($color_to_move==1)&&($board->bbrokencastle==true)) || (($color_to_move==2)&&($board->bbrokencastle==true))){
			$maxrank=9;
		}

		if((($color_to_move==1)&&($board->wbrokencastle==true)) || (($color_to_move==2)&&($board->wbrokencastle==true))){
			$minrank=0;
		}

		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);

		if ($ending_square) {
			if(($starting_square->file==0)||($starting_square->file==9)){
				if(($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank)&&($ending_square->file!=$starting_square->file))
				{//Non-truce zone endpoint but ROYAL is in Truce-Zone. return
				return null;
				}
			}

			//No-one Cannot enter uncompromised own CASTLE with lone effort.
			if((($starting_square->rank==8)&&($ending_square->rank==9)&&(($color_to_move==2)&&($board->bbrokencastle==false)))||
			(($starting_square->rank==1)&&($ending_square->rank==0)&&(($color_to_move==1)&&($board->wbrokencastle==false)))){
				return null;
			}
		}

		if (!$ending_square) {
			return null;
		} else {
			//Defective.... Check if the WAr-Zone and CASTLE are Mixed.  Incomplete ....
			if ((($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->rank>=$minrank)&&($ending_square->rank<=$maxrank) && ($ending_square->file>=1) && ($ending_square->file<=8))) //Check within War Zone
			{
				if ($board->board[$rank][$file]) {
					if ( (($board->board[$rank][$file]->color == $color_to_move) && ($board->board[$rank][$file]->type==ChessPiece::GENERAL)))
					{
						 //*echo ' Ending square contains a friendly General ';*/
						//Check if general is elevated
						if($board->gametype==1) 
							return $ending_square;
						else if((($board->elevatedbs==true) && ($color_to_move==2)) || (($board->elevatedws==true) && ($color_to_move==1))) 
							return $ending_square;
						elseif( (($board->elevatedbs==false) && ($color_to_move==2)) || (($board->elevatedws==false) && ($color_to_move==1))) 
							return null;
					}
					elseif (($board->board[$rank][$file]->color == $color_to_move) && ( (($starting_square->rank==0)&&($color_to_move==1)&&($board->whitecanfullmoveinowncastle==1)) || 
					(($starting_square->rank==9)&&($color_to_move==2)&&($board->blackcanfullmoveinowncastle==1))  ))
						return $ending_square;
					//Ending Block is neighhbour to its own compromised castle
					elseif (($board->board[$rank][$file]->color == $color_to_move) && ( (($ending_square->rank==0)&&($ending_square->file>0)&&($ending_square->file<9)&&($starting_square->rank==1)&&($color_to_move==1)&&($board->blackcanfullmoveinfoecastle==1)) || 
						(($ending_square->rank==9)&&($starting_square->rank==8)&&($ending_square->file>0)&&($ending_square->file<9)&&($color_to_move==2)&&($board->whitecanfullmoveinfoecastle==1))  ))
							return $ending_square;
					elseif( (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='SOLDIER')||($board->board[$rank][$file]->group=='OFFICER'))))
					{
						 //*echo ' Ending square contains a friendly ROYAL or SemiROYAL ';*/
						return $ending_square;
					}
					else
						return null;
				}
				else
					return null;
			}
			else if ((($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==0) && 	($ending_square->file==0))||
			(($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==0) && ($ending_square->file==0))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==0) && ($ending_square->file==0)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==0) && ($ending_square->file==0)&&($ending_square->rank>=2))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank>=2))
			) /* Truce Zone neighbors*/
			{
				if ($board->board[$rank][$file]) {
					if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='SOLDIER')||($board->board[$rank][$file]->group=='OFFICER')))					
					{
						 //*echo ' Ending square contains a friendly General ';*/
						return $ending_square;
					}
					else 
						return null;
				}
				else
					return null;
			}
			else
				return null;
		}
		return $ending_square;
	}

	static function officer_square_surrounded_by_general_ROYALs(
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board
	): ?ChessSquare {

		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;
		$minrank=1; $maxrank=8;

		//All ROYALs are exposed in CASTLE.
		//Proxy Neighbours as CASTLE and WAR are mixed.
		if((($color_to_move==1)&&($board->bbrokencastle==true)) || (($color_to_move==2)&&($board->bbrokencastle==true))){
			$maxrank=9;
		}

		if((($color_to_move==1)&&($board->wbrokencastle==true)) || (($color_to_move==2)&&($board->wbrokencastle==true))){
			$minrank=0;
		}

		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);

		if ($ending_square) {
			if(($starting_square->file==0)||($starting_square->file==9)){
				if(($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank)&&($ending_square->file!=$starting_square->file))
				{//Non-truce zone endpoint but ROYAL is in Truce-Zone. return
				return null;
				}
			}

			//No-one Cannot enter uncompromised own CASTLE with lone effort.
			if((($starting_square->rank==8)&&($ending_square->rank==9)&&(($color_to_move==2)&&($board->bbrokencastle==false)))||
			(($starting_square->rank==1)&&($ending_square->rank==0)&&(($color_to_move==1)&&($board->wbrokencastle==false)))){		
				return null;
			}
		}

		if (!$ending_square) {
			return null;
		} else {
			//Defective.... Check if the WAr-Zone and CASTLE are Mixed.  Incomplete ....
			if ((($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->rank>=$minrank)&&($ending_square->rank<=$maxrank) && ($ending_square->file>=1) && ($ending_square->file<=8))) //Check within War Zone
			{
				if ($board->board[$rank][$file]) {
					if ( (($board->board[$rank][$file]->color == $color_to_move) && ($board->board[$rank][$file]->type==ChessPiece::GENERAL)))
					{
						 //*echo ' Ending square contains a friendly General ';*/
						//Check if general is elevated
						if($board->gametype>=1) 
							return $ending_square;
					}
					elseif (($board->board[$rank][$file]->color == $color_to_move) && ( (($starting_square->rank==0)&&($color_to_move==1)&&($board->whitecanfullmoveinowncastle==1)) || 
					(($starting_square->rank==9)&&($color_to_move==2)&&($board->blackcanfullmoveinowncastle==1))  ))
						return $ending_square;
					//Ending Block is neighhbour to its own compromised castle
					elseif (($board->board[$rank][$file]->color == $color_to_move) && ( (($ending_square->rank==0)&&($ending_square->file>0)&&($ending_square->file<9)&&($starting_square->rank==1)&&($color_to_move==1)&&($board->blackcanfullmoveinfoecastle==1)) || 
						(($ending_square->rank==9)&&($starting_square->rank==8)&&($ending_square->file>0)&&($ending_square->file<9)&&($color_to_move==2)&&($board->whitecanfullmoveinfoecastle==1))  ))
							return $ending_square;
					elseif( (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL'))))
					{
						 //*echo ' Ending square contains a friendly ROYAL or SemiROYAL ';*/
						return $ending_square;
					}
					else
						return null;
				}
				else
					return null;
			}
			else if ((($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==0) && 	($ending_square->file==0))||
			(($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==0) && ($ending_square->file==0))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==0) && ($ending_square->file==0)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==0) && ($ending_square->file==0)&&($ending_square->rank>=2))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank>=2))
			) /* Truce Zone neighbors*/
			{
				if ($board->board[$rank][$file]) {
					if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')|| ($board->board[$rank][$file]->type==ChessPiece::GENERAL)))
					{
						 //*echo ' Ending square contains a friendly General ';*/
						return $ending_square;
					}
					else 
						return null;
				}
				else
					return null;
			}
			
			else
				return null;
		}
		return $ending_square;
	}


//Check the neighbour Officers when Spies are moved near to them
	static function check_neighbours_promotion_candidates( /**/
				array $directions_list,
				ChessPiece $piece,
				$color_to_move,
				ChessBoard $board,
				ChessPiece $Skippiece=null
			): array {
				$candidates=[];
				$ending_square=null;
				foreach ( $directions_list as $direction ) {
						$current_xy = self::DIRECTION_OFFSETS[$direction];
						$current_xy[0] *= 1;
						$current_xy[1] *= 1;
						$type=0;
		
						
						//$ending_square = self::officer_square_surrounded_by_general_ROYALs(
						$ending_square = self::square_surrounded_by_officers_spies(
	
							$piece->square,
							$current_xy[0],
							$current_xy[1],
							$color_to_move,
							$board
						);
						if(!$ending_square)
						{ continue;
						}
						
						if(($Skippiece!=null)&&($ending_square->file==$Skippiece->square->file)&& ($ending_square->rank==$Skippiece->square->rank)) { continue; }
		
						if(($ending_square!=null))
						{
							$candidates[]=$ending_square;
						}
					}
				return	$candidates;
			}
			

	static function square_surrounded_by_officers_spies(
				ChessSquare $starting_square,
				int $y_delta,int $x_delta,
				$color_to_move,
				ChessBoard $board
			): ?ChessSquare {
		
				$rank = $starting_square->rank + $y_delta;
				$file = $starting_square->file + $x_delta;
				$minrank=1; $maxrank=8;
		
				//All ROYALs are exposed in CASTLE.
				//Proxy Neighbours as CASTLE and WAR are mixed.
				if((($color_to_move==1)&&($board->bbrokencastle==true)) || (($color_to_move==2)&&($board->bbrokencastle==true))){
					$maxrank=9;
				}
		
				if((($color_to_move==1)&&($board->wbrokencastle==true)) || (($color_to_move==2)&&($board->wbrokencastle==true))){
					$minrank=0;
				}
		
				$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);
		
				if ($ending_square) {
					if(($starting_square->file==0)||($starting_square->file==9)){
						if(($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank)&&($ending_square->file!=$starting_square->file))
						{//Non-truce zone endpoint but ROYAL is in Truce-Zone. return
						return null;
						}
					}
		
					//No-one Cannot enter uncompromised own CASTLE with lone effort.
					if((($starting_square->rank==8)&&($ending_square->rank==9)&&(($color_to_move==2)&&($board->bbrokencastle==false)))||
					(($starting_square->rank==1)&&($ending_square->rank==0)&&(($color_to_move==1)&&($board->wbrokencastle==false)))){		
						return null;
					}
				}
		
				if (!$ending_square) {
					return null;
				} else {
					//Defective.... Check if the WAr-Zone and CASTLE are Mixed.  Incomplete ....
					if ((($starting_square->rank>=$minrank)&&($starting_square->rank<=$maxrank) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
					($ending_square->rank>=$minrank)&&($ending_square->rank<=$maxrank) && ($ending_square->file>=1) && ($ending_square->file<=8))) //Check within War Zone
					{
						if ($board->board[$rank][$file]) {
							if ( (($board->board[$rank][$file]->color == $color_to_move) && ($board->board[$rank][$file]->type==ChessPiece::GENERAL)))
							{
								 //*echo ' Ending square contains a friendly General ';*/
								//Check if general is elevated
								if($board->gametype>=1) 
									return $ending_square;
							}
							elseif (($board->board[$rank][$file]->color == $color_to_move) && ( (($starting_square->rank==0)&&($color_to_move==1)&&($board->whitecanfullmoveinowncastle==1)) || 
							(($starting_square->rank==9)&&($color_to_move==2)&&($board->blackcanfullmoveinowncastle==1))  ))
								return $ending_square;
							//Ending Block is neighhbour to its own compromised castle
							elseif (($board->board[$rank][$file]->color == $color_to_move) && ( (($ending_square->rank==0)&&($ending_square->file>0)&&($ending_square->file<9)&&($starting_square->rank==1)&&($color_to_move==1)&&($board->blackcanfullmoveinfoecastle==1)) || 
								(($ending_square->rank==9)&&($starting_square->rank==8)&&($ending_square->file>0)&&($ending_square->file<9)&&($color_to_move==2)&&($board->whitecanfullmoveinfoecastle==1))  ))
									return $ending_square;
							elseif( (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='OFFICER')||($board->board[$rank][$file]->group=='SEMIROYAL'))))
							{
								 //*echo ' Ending square contains a friendly Officer or SemiROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
						else
							return null;
					}
					else if ((($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==0) && 	($ending_square->file==0))||
					(($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==9) && ($ending_square->file==9))||
					(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==0) && ($ending_square->file==0))||
					(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==9) && ($ending_square->file==9))||
					(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==0) && ($ending_square->file==0)&&($ending_square->rank<=7))||
					(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==0) && ($ending_square->file==0)&&($ending_square->rank>=2))||
					(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank<=7))||
					(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank>=2))
					) /* Truce Zone neighbors*/
					{
						if ($board->board[$rank][$file]) {
							if( (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='OFFICER')||($board->board[$rank][$file]->group=='SEMIROYAL'))))
							{
								 //*echo ' Ending square contains a friendly General ';*/
								return $ending_square;
							}
							else 
								return null;
						}
						else
							return null;
					}
					else
						return null;
				}
				return $ending_square;
			}
		


	static function check_general_ROYAL_neighbours_promotion( /**/
		array $directions_list,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		ChessPiece $Skippiece=null
	): bool {
		$ending_square=null;
		foreach ( $directions_list as $direction ) {
				$current_xy = self::DIRECTION_OFFSETS[$direction];
				$current_xy[0] *= 1;
				$current_xy[1] *= 1;
				$type=0;

				
				$ending_square = self::officer_square_surrounded_by_general_ROYALs(
					$piece->square,
					$current_xy[0],
					$current_xy[1],
					$color_to_move,
					$board
				);
				if(!$ending_square)
				{ continue;
				}
				
				if(($Skippiece!=null)&&($ending_square->file==$Skippiece->square->file)&& ($ending_square->rank==$Skippiece->square->rank)) { continue; }

				if(($ending_square!=null))
				{
					return TRUE;
				}
			}
		if(!$ending_square)
		{ return FALSE;
		}
		else
			return TRUE;
	}

	static function check_general_push_demotion( /**/
		array $directions_list,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		ChessPiece $Skippiece=null
	): bool {
		$ending_square=null;
		foreach ( $directions_list as $direction ) {
				$current_xy = self::DIRECTION_OFFSETS[$direction];
				$current_xy[0] *= 1;
				$current_xy[1] *= 1;
				$type=0;

				$rank = $piece->square->rank +$current_xy[0];
				$file = $piece->square->file + $current_xy[1];

				$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);

				if(!$ending_square)
				{ continue;
				}
				
				if(($Skippiece!=null)&&($ending_square->file==$Skippiece->square->file)&& ($ending_square->rank==$Skippiece->square->rank)) { continue; }

				if(($ending_square!=null))
				{
					return TRUE;
				}
			}
		if(!$ending_square)
		{ return FALSE;
		}
		else
			return TRUE;
	}


	static function has_opponent_neighbours( /**/
		array $directions_list,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board
	): bool {
		$ending_square=null;
		foreach ( $directions_list as $direction ) {
				$current_xy = self::DIRECTION_OFFSETS[$direction];
				$current_xy[0] *= 1;
				$current_xy[1] *= 1;
				$type=0;
				$ending_square = self::square_surrounded_by_army(
					$piece->square,
					$current_xy[0],
					$current_xy[1],
					$color_to_move,
					$board
				);
				if(!$ending_square)
				{ continue;
				}
				if($ending_square!=null)
				{
					return TRUE;
				}
			}
		if(!$ending_square)
		{ return FALSE;
		}
		else
			return TRUE;
	}

	static function check_trapped_piece( /**/
		$piece,
		$color_to_move,
		ChessBoard $board,
		$condition
	): void {
	$ending_square=null;
	$pushed_square=null;
	$self_directions=null;
	$newpiece=null;
	if(($piece->color==$color_to_move) && ($piece->striker==1)){
			if(($piece->square->rank==1) && ($piece->square->file==8)){
				$self_directions=self::RBottom_DIRECTIONS;
				}
			else if(($piece->square->rank==1) && ($piece->square->file==1)){
				$self_directions=self::LBottom_DIRECTIONS;
				}
			else if(($piece->square->rank==8) && ($piece->square->file==8)){
				$self_directions=self::RTop_DIRECTIONS;
				}
			else if(($piece->square->rank==8) && ($piece->square->file==1)){
				$self_directions=self::LTop_DIRECTIONS;
				}
			else if(($piece->square->rank==1) && ($piece->square->file>1)&&($piece->square->file<8)){
				$self_directions=self::Bottom_DIRECTIONS;
				}
			else if(($piece->square->rank==8) && ($piece->square->file>1)&&($piece->square->file<8)){
				$self_directions=self::Top_DIRECTIONS;
				}
			else if(($piece->square->file==1) && ($piece->square->rank>1)&&($piece->square->rank<8)){
				$self_directions=self::L_DIRECTIONS;
				}
			else if(($piece->square->file==8) && ($piece->square->rank>1)&&($piece->square->rank<8)){
				$self_directions=self::R_DIRECTIONS;
				}
			else if(($piece->square->file>1) && ($piece->square->file<8) && ($piece->square->rank>1)&&($piece->square->rank<8)){
				$self_directions=self::MID_DIRECTIONS;
				}

		if($self_directions!=null){
			$pushed_square=null;
			$pushed_y=-1;
			$pushed_x=-1;

			foreach ( $self_directions	as $direction ) {
				$ending_square=null;
				$pushed_x=-1;
				$pushed_square=null;
				$pushed_y=-1;
				$current_xy = self::DIRECTION_OFFSETS[$direction];
				$ending_square = self::try_to_make_square_using_rank_and_file_num($piece->square->rank+$current_xy[0], $piece->square->file+$current_xy[1]);
				
				if(!$ending_square)
				{ 
					continue;
				}
				$newpiece=$board->board[$ending_square->rank][$ending_square->file];
				$ending_square=null;
				if($newpiece==null) //only opponent
					continue;
				if($newpiece->color==$piece->color)
					continue;

				/* Left to Right move */
				if( ($newpiece->square->rank==$piece->square->rank) && ($newpiece->square->file==$piece->square->file+1)) 
					{
						$pushed_x=$newpiece->square->file+1;
						$pushed_y=$newpiece->square->rank;
					}
				/* Right to Left move */
				else if( ($newpiece->square->rank==$piece->square->rank) && ($newpiece->square->file==$piece->square->file-1)) 
					{
						$pushed_x=$newpiece->square->file-1;
						$pushed_y=$newpiece->square->rank;
					}

				/* Bottom to Top move */
				else if( ($newpiece->square->rank==$piece->square->rank+1) && ($newpiece->square->file==$piece->square->file)) 
					{
						$pushed_x=$newpiece->square->file;
						$pushed_y=$newpiece->square->rank+1;
					}

				/* Top to Bottom move */
				else if( ($newpiece->square->rank==$piece->square->rank-1) && ($newpiece->square->file==$piece->square->file)) 
					{
						$pushed_x=$newpiece->square->file;
						$pushed_y=$newpiece->square->rank-1;
					}

				/* Bottom diagonal to Top diagonal Left to Right move */
				else if( ($newpiece->square->rank==$piece->square->rank+1) && ($newpiece->square->file==$piece->square->file+1)) 
					{
						$pushed_x=$newpiece->square->file+1;
						$pushed_y=$newpiece->square->rank+1;
					}

				/* Bottom diagonal to Top diagonal Right to Left move */
				else if( ($newpiece->square->rank==$piece->square->rank+1) && ($newpiece->square->file==$piece->square->file-1)) 
					{
						$pushed_x=$newpiece->square->file-1;
						$pushed_y=$newpiece->square->rank+1;
					}
										
				/* Top to Bottom move Left to Right move */
				else if( ($newpiece->square->rank==$piece->square->rank-1) && ($newpiece->square->file==$piece->square->file+1)) 
					{
						$pushed_x=$newpiece->square->file+1;
						$pushed_y=$newpiece->square->rank-1;
					}

				/* Top to Bottom move Right to Left move */
				else if( ($newpiece->square->rank==$piece->square->rank-1) && ($newpiece->square->file==$piece->square->file-1)) 
					{
						$pushed_x=$newpiece->square->file-1;
						$pushed_y=$newpiece->square->rank-1;
					}

				if(($pushed_y<=-1)||($pushed_x<=-1)||($pushed_y>=10)||($pushed_x>=10)){
					$pushed_square=null;
				}
				else
					$pushed_square = self::try_to_make_square_using_rank_and_file_num($pushed_y, $pushed_x);
				
				if(!$pushed_square)
					{
						continue;
					}
				
				/*if($board->board[ $newpiece->square->rank][ $newpiece->square->file]==null)
					$ttt=1;
				if($board->board[ $piece->square->rank][ $piece->square->file]==null)
					$ttt=1;
				*/

					$officerp=self::check_officers_neighbours( /**/
						self::KING_DIRECTIONS,
						$board->board[ $newpiece->square->rank][ $newpiece->square->file],
						abs(3-$color_to_move),
						$board,
						'exclude'
					);

				$checkpinnedrefugees=self::checkpinnedrefugees($color_to_move,$board, $newpiece->square,$newpiece->square)==false;

				if( ($officerp==false) && ($board->board[ $newpiece->square->rank][ $newpiece->square->file]->type=="13") &&($checkpinnedrefugees==false)){
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushed=true;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedsquare=array ("rank"=>$pushed_square->rank, "file"=>$pushed_square->file);
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedpiece=clone $newpiece;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusher=true;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushersquare=array ("rank"=>$piece->square->rank, "file"=>$piece->square->file);
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusherpiece=clone $piece;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=true;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushersquare=array ("rank"=>$piece->square->rank, "file"=>$piece->square->file);
						continue;
				}

				if(($piece!=null)&&($board->board[ $pushed_square->rank][ $pushed_square->file]!=null)&&
				(($pushed_y<=0)||($pushed_x<=0)||($pushed_y>=9)||($pushed_x>=9))){
						$ROYALp=false;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=true;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushed=false;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedsquare=null;//
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedpiece=null;//;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusher=true;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushersquare=array ("rank"=>$piece->square->rank, "file"=>$piece->square->file);
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusherpiece=clone $piece;
					}
				else if(($piece!=null)&&($board->board[ $pushed_square->rank][ $pushed_square->file]!=null)){
					$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushed=false;
					$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedsquare=null;
					$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedpiece=null;
					$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusher=null;
					$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushersquare=null;
					$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusherpiece=null;

					 if(( $board->board[ $newpiece->square->rank][ $newpiece->square->file]->type<$board->board[ $piece->square->rank][ $piece->square->file]->type)){
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=true;
						}
					else if( ( $board->board[ $newpiece->square->rank][ $newpiece->square->file]->type>=$board->board[ $pushed_square->rank][ $pushed_square->file]->type)){
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=true;
					}
					else if(( $board->board[ $newpiece->square->rank][ $newpiece->square->file]->type>=$board->board[ $piece->square->rank][ $piece->square->file]->type)){
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=true;
					}
				}	
				else if($board->board[ $pushed_square->rank][ $pushed_square->file]==null){
					if(($pushed_y<=0)||($pushed_x<=0)||($pushed_y>=9)||($pushed_x>=9)){
						$ROYALp=false;
						//if($board->board[ $newpiece->square->rank][ $newpiece->square->file]->type==ChessPiece::ROOK)
						if(($board->board[ $newpiece->square->rank][ $newpiece->square->file]->group=="OFFICER")
						||(strpos($board->board[ $newpiece->square->rank][ $newpiece->square->file]->group,"ROYAL")!==FALSE))//Can be ROYAL also
							{
							//Opponent piece has no ROYAL//
							/*$ROYALp=self::check_general_ROYAL_neighbours_promotion(self::KING_DIRECTIONS, $board->board[ $newpiece->square->rank][ $newpiece->square->file],
								abs(3-$color_to_move), $board );
							*/
							$ROYALp=self::has_ROYAL_neighbours( self::KING_DIRECTIONS,$newpiece->square, $newpiece->square, abs(3-$color_to_move), $board );	
							}

						if($ROYALp==true) {
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=false;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushed=true;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedsquare=array ("rank"=>$pushed_square->rank, "file"=>$pushed_square->file);
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedpiece=clone $newpiece;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusher=true;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushersquare=array ("rank"=>$piece->square->rank, "file"=>$piece->square->file);
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusherpiece=clone $piece;
						}
						else{
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=true;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushed=false;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedsquare=null;//
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedpiece=null;//;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusher=true;
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushersquare=array ("rank"=>$piece->square->rank, "file"=>$piece->square->file);
							$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusherpiece=clone $piece;
						}
					}
					else if($board->board[$pushed_square->rank][$pushed_square->file]==null){
						/*if(($newpiece->square->rank==5) &&( $newpiece->square->file==1)
						&& ($piece->square->rank==4) &&( $piece->square->file==1))
							$test=1;*/
						
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=false;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushed=true;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedsquare=array ("rank"=>$pushed_square->rank, "file"=>$pushed_square->file);
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedpiece=clone $newpiece;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusher=true;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushersquare=array ("rank"=>$piece->square->rank, "file"=>$piece->square->file);
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusherpiece=clone $piece;
					}
				}
			}
		}
	}
	}

	static function check_virtual_trapped_piece( /**/
		$piece,
		$endingpiece,
		$color_to_move,
		ChessBoard $board,
		$condition
	): void {
		$pushed_square=null;

				$pushed_x=-1; $pushed_y=-1;
				$ending_square=$endingpiece->square;
				$newpiece=$board->board[$ending_square->rank][$ending_square->file];
				if($newpiece==null) //only opponent
					return;
				if($newpiece->color==$piece->color)
					return;

				/* Left to Right move */
				if( ($newpiece->square->rank==$piece->square->rank) && ($newpiece->square->file==$piece->square->file+1)) 
					{
						$pushed_x=$newpiece->square->file+1;
						$pushed_y=$newpiece->square->rank;
					}
				/* Right to Left move */
				else if( ($newpiece->square->rank==$piece->square->rank) && ($newpiece->square->file==$piece->square->file-1)) 
					{
						$pushed_x=$newpiece->square->file-1;
						$pushed_y=$newpiece->square->rank;
					}

				/* Bottom to Top move */
				else if( ($newpiece->square->rank==$piece->square->rank+1) && ($newpiece->square->file==$piece->square->file)) 
					{
						$pushed_x=$newpiece->square->file;
						$pushed_y=$newpiece->square->rank+1;
					}

				/* Top to Bottom move */
				else if( ($newpiece->square->rank==$piece->square->rank-1) && ($newpiece->square->file==$piece->square->file)) 
					{
						$pushed_x=$newpiece->square->file;
						$pushed_y=$newpiece->square->rank-1;
					}

				/* Bottom diagonal to Top diagonal Left to Right move */
				else if( ($newpiece->square->rank==$piece->square->rank+1) && ($newpiece->square->file==$piece->square->file+1)) 
					{
						$pushed_x=$newpiece->square->file+1;
						$pushed_y=$newpiece->square->rank+1;
					}
				/* Bottom diagonal to Top diagonal Right to Left move */
				else if( ($newpiece->square->rank==$piece->square->rank+1) && ($newpiece->square->file==$piece->square->file-1)) 
					{
						$pushed_x=$newpiece->square->file-1;
						$pushed_y=$newpiece->square->rank+1;
					}
				/* Top to Bottom move Left to Right move */
				else if( ($newpiece->square->rank==$piece->square->rank-1) && ($newpiece->square->file==$piece->square->file+1)) 
					{
						$pushed_x=$newpiece->square->file+1;
						$pushed_y=$newpiece->square->rank-1;
					}
				/* Top to Bottom move Right to Left move */
				else if( ($newpiece->square->rank==$piece->square->rank-1) && ($newpiece->square->file==$piece->square->file-1)) 
					{
						$pushed_x=$newpiece->square->file-1;
						$pushed_y=$newpiece->square->rank-1;
					}

				if(($pushed_y<=-1)||($pushed_x<=-1)||($pushed_y>=10)||($pushed_x>=10)){
					$pushed_square=null;
				}
				else
					$pushed_square = self::try_to_make_square_using_rank_and_file_num($pushed_y, $pushed_x);
				
				if(!$pushed_square)
					{ 
						return;
					}
				
				//if Junior is striking Senior
				if( ($board->board[ $pushed_square->rank][ $pushed_square->file]!=null)&&
					( $board->board[ $newpiece->square->rank][ $newpiece->square->file]->type>=$board->board[ $pushed_square->rank][ $pushed_square->file]->type)){
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=true;
					}
				else if($board->board[ $pushed_square->rank][ $pushed_square->file]==null){
					if(($pushed_y<=0)||($pushed_x<=0)||($pushed_y>=9)||($pushed_x>=9)){
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=true;
					}
					else if($board->board[$pushed_square->rank][$pushed_square->file]==null){//if senior is striking then selftrapped=true;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selftrapped=false;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushed=true;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedsquare=array ("rank"=>$pushed_square->rank, "file"=>$pushed_square->file);
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushedpiece=clone $newpiece;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusher=true;
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpushersquare=array ("rank"=>$piece->square->rank, "file"=>$piece->square->file);
						$board->board[ $newpiece->square->rank][ $newpiece->square->file]->selfpusherpiece=clone $piece;
					}
				}
	}

	static function check_uncontrolled_officers_neighbours( /**/
		array $directions_list,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		$condition,
		$controlledpiece
		): bool {
		$ending_square=null;
		$uncontrolledofficers=false;
		foreach ( $directions_list as $direction ) {
				$current_xy = self::DIRECTION_OFFSETS[$direction];
				$current_xy[0] *= 1;
				$current_xy[1] *= 1;
				$type=0;
				$ending_square = self::square_surrounded_by_officers(
					$piece->square,
					$current_xy[0],
					$current_xy[1],
					$color_to_move,
					$board
				);
				if(!$ending_square)
				{ continue;
				}
				if($ending_square!=null)
				{
					///General is stuck so pawns it is Not free to instruct the stucked army. Army can listen to other officers
					if(($piece->controlledpiece==true)&&($controlledpiece==true)&&  ($board->board[$ending_square->rank][$ending_square->file]->group=="OFFICER")
					&&($board->board[$ending_square->rank][$ending_square->file]->controlledpiece==true)&& ($uncontrolledofficers==false))
						{ $uncontrolledofficers=false; continue; }
					else if(($piece->controlledpiece==true)&&($controlledpiece==true)&&  ($board->board[$ending_square->rank][$ending_square->file]->group=="OFFICER")
						&&($board->board[$ending_square->rank][$ending_square->file]->controlledpiece==false))
							{ $uncontrolledofficers=true; return TRUE; }
					else if(($condition=='exclude')&&(self::checkpinnedrefugees($color_to_move,$board, $ending_square,$ending_square)==true))
						continue;
					return TRUE;
				}
			}
		return $uncontrolledofficers;
	}

	static function check_officers_neighbours( /**/
		array $directions_list,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		$condition	): bool {
		$ending_square=null;
		$pawncanmove=true;
		foreach ( $directions_list as $direction ) {
				$current_xy = self::DIRECTION_OFFSETS[$direction];
				$current_xy[0] *= 1;
				$current_xy[1] *= 1;
				$type=0;
				$ending_square = self::square_surrounded_by_officers(
					$piece->square,
					$current_xy[0],
					$current_xy[1],
					$color_to_move,
					$board
				);
				if(!$ending_square)
				{ continue;
				}
				if($ending_square!=null)
				{
					if(($condition=='exclude')&&(self::checkpinnedrefugees($color_to_move,$board, $ending_square,$ending_square)==true))
						continue;
					return TRUE;
				}
			}
		if(!$ending_square)
		{		return FALSE;
		}
		else
		return TRUE;
	}

	static function ROYAL_square_surrounded_by_ROYALs(
		ChessSquare $actual_square,
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board,
		bool $sameplace,
		string $check_neighbour_type,
		$royaltype="ALL"
	): ?ChessSquare
	{
		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;
		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);
		if (($sameplace==TRUE)&&($ending_square)){
				if(($actual_square->rank==$ending_square->rank)&& ($actual_square->file==$ending_square->file)){
					return null;
			}
		}

		if ($ending_square) {

			/*if(($ending_square->file==0) &&($ending_square->rank==7))
			{ $ttt=1; }*/

			if (($starting_square->rank==0) &&($starting_square->file==5) && ($ending_square->rank==0)&&($ending_square->file==4)) {
				$starting_square=$starting_square;
			}

			if(($starting_square->file==0)||($starting_square->file==9)){
				if(($starting_square->rank>=1)&&($starting_square->rank<=8)&&($ending_square->file!=$starting_square->file))
				{//Non-truce zone endpoint but ROYAL is in Truce-Zone. return
				return null;
				}
				else
					$rank;
			}
		}

		if (!$ending_square) {
			return null;
		} else {

			if(($board->board[$rank][$file]!=null) && ($board->board[$rank][$file]->color == $color_to_move)) {
					//Check if Ending square is active royal in castle to Awaken the trapped Palace// Compromised logic to be added later
					if (( ((($ending_square->rank==0)&&($starting_square->rank==0)) || (($ending_square->rank==9)&&($starting_square->rank==9))) && ((($starting_square->file==4) && ($ending_square->file==3))||
					(($starting_square->file==5) && ($ending_square->file==6)))))
					{

					}
					else if (( ((($ending_square->file==0)&&($starting_square->file==0)) || (($ending_square->file==9)&&($starting_square->file==9))) && ((($starting_square->rank==4) && ($ending_square->rank==3))||
					(($starting_square->rank==5) && ($ending_square->rank==6)))))
					{

					}
					else if (( ((($ending_square->file==0)&&($starting_square->file==0)) || (($ending_square->file==9)&&($starting_square->file==9))) && ((($starting_square->rank==4) && ($ending_square->rank==5))||
					(($starting_square->rank==5) && ($ending_square->rank==4)))))
					{
						return null; // Neighbors Sleepers are not touching
					}
					else if (( ((($ending_square->rank==0)&&($starting_square->rank==0)) || (($ending_square->rank==9)&&($starting_square->rank==9))) && ((($starting_square->file==4) && ($ending_square->file==5))||
					(($starting_square->file==5) && ($ending_square->file==4)))))
					{
						return null; // Neighbors Sleepers are not touching
					}
					else if (( ((($ending_square->file==4)&&($starting_square->file==4)) || (($ending_square->file==5)&&($starting_square->file==5))) && ((($starting_square->rank==4) && ($ending_square->rank==5))||
					(($starting_square->rank==5) && ($ending_square->rank==4)))))
					{
						return null; // Neighbors Sleepers are not touching
					}
					else if (( ((($ending_square->rank==4)&&($starting_square->rank==4)) || (($ending_square->rank==5)&&($starting_square->rank==5))) && ((($starting_square->file==4) && ($ending_square->file==5))||
					(($starting_square->file==5) && ($ending_square->file==4)))))
					{
						return null; // Neighbors Sleepers are not touching
					}

					///compromised castle ($board->wbrokencastle==true)&&($board->bbrokencastle==true)&&
					//Check if Ending square is active royal in castle to Awaken the trapped Palace// Compromised logic to be added later
					if (($board->isCurrentZoneRoyal==true) &&((( $board->wbrokencastle==true)&&(($ending_square->rank==1)&&($starting_square->rank==0)) || (($board->bbrokencastle==true)&&($ending_square->rank==8)&&($starting_square->rank==9))) && 
					((($starting_square->file==$ending_square->file+1))||
					(($starting_square->file==$ending_square->file-1))))&&($starting_square->file>0)&&($starting_square->file<9)  &&($ending_square->file>0)&&($ending_square->file<9))
					{

							if($royaltype=="ALL") { 
									if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
									{
										 //*echo ' Ending square contains a friendly ROYAL ';*/
										return $ending_square;
									}
									else
										return null;
								}
							else if($royaltype=="ROYAL") { 
									if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')))
									{
										 //*echo ' Ending square contains a friendly ROYAL ';*/
										return $ending_square;
									}
									else
										return null;
								}
							else if($royaltype=="SEMIROYAL") { 
									if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='SEMIROYAL')))
									{
										 //*echo ' Ending square contains a friendly SEMIROYAL ';*/
										return $ending_square;
									}
									else
										return null;
								}
					}
					else if (($board->isCurrentZoneRoyal==true) &&( (($board->wbrokencastle==true)&&(($ending_square->rank==0)&&($starting_square->rank==1)) || (($board->bbrokencastle==true)&&($ending_square->rank==9)&&($starting_square->rank==8))) && 
					((($starting_square->file==$ending_square->file+1))||
					(($starting_square->file==$ending_square->file-1))))&&($starting_square->file>0)&&($starting_square->file<9)  &&($ending_square->file>0)&&($ending_square->file<9))
					{
						if($royaltype=="ALL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
							{
								 //*echo ' Ending square contains a friendly ROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
						else if($royaltype=="ROYAL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')))
							{
								 //*echo ' Ending square contains a friendly ROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
						else if($royaltype=="SEMIROYAL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='SEMIROYAL')))
							{
								 //*echo ' Ending square contains a friendly SEMIROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
					}
					else if (((( $board->wbrokencastle==false)&&(($ending_square->rank==1)&&($starting_square->rank==0)) || (($board->bbrokencastle==false)&&($ending_square->rank==8)&&($starting_square->rank==9))) && 
					((($starting_square->file==$ending_square->file+1))||
					(($starting_square->file==$ending_square->file-1))))&&($starting_square->file>0)&&($starting_square->file<9)  &&($ending_square->file>0)&&($ending_square->file<9))
					{
						return null; 

					}
					else if (( (($board->wbrokencastle==false)&&(($ending_square->rank==0)&&($starting_square->rank==1)) || (($board->bbrokencastle==false)&&($ending_square->rank==9)&&($starting_square->rank==8))) && 
					((($starting_square->file==$ending_square->file+1))||
					(($starting_square->file==$ending_square->file-1))))&&($starting_square->file>0)&&($starting_square->file<9)  &&($ending_square->file>0)&&($ending_square->file<9))
					{
						return null; 
					}
					else if (($board->isCurrentZoneRoyal==false) &&((( $board->wbrokencastle==true)&&(($ending_square->rank==1)&&($starting_square->rank==0)) || (($board->bbrokencastle==true)&&($ending_square->rank==8)&&($starting_square->rank==9))) && 
					((($starting_square->file==$ending_square->file+1))||
					(($starting_square->file==$ending_square->file-1))))&&($starting_square->file>0)&&($starting_square->file<9)  &&($ending_square->file>0)&&($ending_square->file<9))
					{
						if($royaltype=="ALL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
							{
								 //*echo ' Ending square contains a friendly ROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
						else if($royaltype=="ROYAL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')))
							{
								 //*echo ' Ending square contains a friendly ROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
						else if($royaltype=="SEMIROYAL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='SEMIROYAL')))
							{
								 //*echo ' Ending square contains a friendly SEMIROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
					}
					else if (($board->isCurrentZoneRoyal==false) &&( (($board->wbrokencastle==true)&&(($ending_square->rank==0)&&($starting_square->rank==1)) || (($board->bbrokencastle==true)&&($ending_square->rank==9)&&($starting_square->rank==8))) && 
					((($starting_square->file==$ending_square->file+1))||
					(($starting_square->file==$ending_square->file-1))))&&($starting_square->file>0)&&($starting_square->file<9)  &&($ending_square->file>0)&&($ending_square->file<9))
					{
						//ending_square should be royal or semiroyal
						if($royaltype=="ALL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
							{
								 //*echo ' Ending square contains a friendly ROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
						else if($royaltype=="ROYAL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')))
							{
								 //*echo ' Ending square contains a friendly ROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
						else if($royaltype=="SEMIROYAL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='SEMIROYAL')))
							{
								 //*echo ' Ending square contains a friendly SEMIROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
					}

			}	
			else if(($board->board[$rank][$file]==null)) {
				//Piece is trapped inside the palace and Sleeping for ever
				if (( ((($ending_square->rank==0)&&($starting_square->rank==0)) || (($ending_square->rank==9)&&($starting_square->rank==9))) && ((($starting_square->file==4) && ($ending_square->file==5))||
				(($starting_square->file==5) && ($ending_square->file==4)))))
				{
					return null;
				}
				else if (( ((($ending_square->file==0)&&($starting_square->file==0)) || (($ending_square->file==9)&&($starting_square->file==9))) && ((($starting_square->rank==4) && ($ending_square->rank==5))||
				(($starting_square->rank==5) && ($ending_square->rank==4)))))
				{
					return null;
				}
				
			}

			if ((($ending_square->rank==0)&&($starting_square->rank==0) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->file>=1) && ($ending_square->file<=8))||(($ending_square->rank==9)&&($starting_square->rank==9) && 
			($starting_square->file>=1) && ($starting_square->file<=8)&&($ending_square->file>=1) && ($ending_square->file<=8))||
			(($starting_square->rank>=1)&&($starting_square->rank<=8) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->rank>=1)&&($ending_square->rank<=8) && ($ending_square->file>=1) && ($ending_square->file<=8))) 
			{
				if ($board->board[$rank][$file]) {
					if($royaltype=="ALL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
							{
								 //*echo ' Ending square contains a friendly ROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
					else if($royaltype=="ROYAL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')))
							{
								 //*echo ' Ending square contains a friendly ROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
					else if($royaltype=="SEMIROYAL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='SEMIROYAL')))
							{
								 //*echo ' Ending square contains a friendly SEMIROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
					}
				else
				  return null;
			}
			else if ((($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==0) && 	($ending_square->file==0))||
			(($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==0) && ($ending_square->file==0))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==0) &&	($ending_square->file==0)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==0) && ($ending_square->file==0)&&($ending_square->rank>=2))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank>=2))
			) 
			{
				if ($board->board[$rank][$file]) {
					if($royaltype=="ALL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
							{
								 //*echo ' Ending square contains a friendly ROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
					else if($royaltype=="ROYAL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')))
							{
								 //*echo ' Ending square contains a friendly ROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
					else if($royaltype=="SEMIROYAL") { 
							if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='SEMIROYAL')))
							{
								 //*echo ' Ending square contains a friendly SEMIROYAL ';*/
								return $ending_square;
							}
							else
								return null;
						}
					}
				else
				  return null;
				}
			else
				return null;
			}		
		return $ending_square;
	}

	static function ROYAL_square_surrounded_by_ROYALs_____Original(
		ChessSquare $actual_square,
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board,
		bool $sameplace,
		string $check_neighbour_type
	): ?ChessSquare
	{
		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;
		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);
		if (($sameplace==TRUE)&&($ending_square)){
				if(($actual_square->rank==$ending_square->rank)&& ($actual_square->file==$ending_square->file)){
					return null;
			}
		}

		if ($ending_square) {

			/*if(($ending_square->file==0) &&($ending_square->rank==7))
			{ $ttt=1; }*/

			if (($starting_square->rank==0) &&($starting_square->file==5) && ($ending_square->rank==0)&&($ending_square->file==4)) {
				$starting_square=$starting_square;
			}

			if(($starting_square->file==0)||($starting_square->file==9)){
				if(($starting_square->rank>=1)&&($starting_square->rank<=8)&&($ending_square->file!=$starting_square->file))
				{//Non-truce zone endpoint but ROYAL is in Truce-Zone. return
				return null;
				}
				else
					$rank;
			}
		}

		if (!$ending_square) {
			return null;
		} else {
			if ((($ending_square->rank==0)&&($starting_square->rank==0) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->file>=1) && ($ending_square->file<=8))||(($ending_square->rank==9)&&($starting_square->rank==9) && 
			($starting_square->file>=1) && ($starting_square->file<=8)&&($ending_square->file>=1) && ($ending_square->file<=8))||
			(($starting_square->rank>=1)&&($starting_square->rank<=8) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->rank>=1)&&($ending_square->rank<=8) && ($ending_square->file>=1) && ($ending_square->file<=8))) 
			{
				if ($board->board[$rank][$file]) {
					if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
					{
						 //*echo ' Ending square contains a friendly ROYAL ';*/
						return $ending_square;
					}
					else
					return null;
				}
				else
				  return null;
			}
			else if ((($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==0) && 	($ending_square->file==0))||
			(($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==0) && ($ending_square->file==0))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==0) &&	($ending_square->file==0)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==0) && ($ending_square->file==0)&&($ending_square->rank>=2))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank>=2))
			) 
			{
				if ($board->board[$rank][$file]) {
					if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
					{
						 //*echo ' Ending square contains a friendly ROYAL ';*/
						return $ending_square;
					}
					else 
						return null;
				}
				else
					return null;
				}
			else
				return null;
			}		
		return $ending_square;
	}


	static function square_surrounded_by_general(
		ChessSquare $actual_square,
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board,
		bool $sameplace
	): ?ChessSquare 
	{
		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;

		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);

		if (($sameplace==TRUE)&&($ending_square)){
				if(($actual_square->rank==$ending_square->rank)&& ($actual_square->file==$ending_square->file)){
					return null;
			}
		}

		if ($ending_square) {
			/*if(($ending_square->file==0) &&($ending_square->rank==7))
			{ $tttt=1; }*/

			if (($starting_square->rank==0) &&($starting_square->file==5) && ($ending_square->rank==0)&&($ending_square->file==4)) {
				$starting_square=$starting_square;
			}

			if(($starting_square->file==0)||($starting_square->file==9)){
				if(($starting_square->rank>=1)&&($starting_square->rank<=8)&&($ending_square->file!=$starting_square->file))
				{//Non-truce zone endpoint but ROYAL is in Truce-Zone. return
					return null;
				}
				else
					$rank;
			}
		}

		if (!$ending_square) {
			return null;
		} else {
			if ((($ending_square->rank==0)&&($starting_square->rank==0) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->file>=1) && ($ending_square->file<=8))||(($ending_square->rank==9)&&($starting_square->rank==9) && 
			($starting_square->file>=1) && ($starting_square->file<=8)&&($ending_square->file>=1) && ($ending_square->file<=8))||
			(($starting_square->rank>=1)&&($starting_square->rank<=8) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->rank>=1)&&($ending_square->rank<=8) && ($ending_square->file>=1) && ($ending_square->file<=8))) 
			{
				if ($board->board[$rank][$file]) {
					if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='OFFICER')
					&& ($board->board[$rank][$file]->type== ChessPiece::GENERAL)))
					{
						 //*echo ' Ending square contains a friendly ROYAL ';*/
						return $ending_square;
					}
					else
						return null;
				}
				else
					return null;
			}
			else if ((($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==0) && 	($ending_square->file==0))||
			(($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==0) && ($ending_square->file==0))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==0) &&	($ending_square->file==0)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==0) && ($ending_square->file==0)&&($ending_square->rank>=2))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank>=2))
			) 
			{
				if ($board->board[$rank][$file]) {
					if (($board->board[$rank][$file]->color == $color_to_move) && (($board->board[$rank][$file]->group=='OFFICER')
					&& ($board->board[$rank][$file]->type== ChessPiece::GENERAL)))
					{
						 //*echo ' Ending square contains a friendly ROYAL ';*/
						return $ending_square;
					}
					else 
						return null;
				}
				else
					return null;
			}
			else
				return null;
		}
		return $ending_square;
	}

	static function set_naarad_for_fullmoves($board){
			self::populate_opponent_neighbours($board); /**/
	}

	static function set_general_for_elevatedmoves($board):void{
		$ending_square=null;
		$generalsquare=null;
		$board->elevatedws=false;
		$board->elevatedbs=false;
		for($color=1;$color<=2;$color++){
			if($color==1)
				$generalsquare=$board->wssquare;
			elseif($color==2)
				$generalsquare=$board->bssquare;
			
			if($generalsquare==null)
				{
					if($color==1)
						$board->elevatedws=false;
					elseif($color==2)
						$board->elevatedbs=false;
					continue; // Goto next color now
				}
			foreach ( self::KING_DIRECTIONS as $direction ) {
					$current_xy = self::DIRECTION_OFFSETS[$direction];
					$current_xy[0] *= 1;
					$current_xy[1] *= 1;
					$ending_square = self::officer_square_surrounded_by_general_ROYALs	(
						$generalsquare,
						$current_xy[0],
						$current_xy[1],
						$color,
						$board
					);
					if(!$ending_square)
					{ continue;
					}
					if($ending_square!=null)
					{
						if($color==1)
							$board->elevatedws=true;
						elseif($color==2)
							$board->elevatedbs=true;
						break; // Goto external loop now
					}
				}
			}
		$color;
	}

	static function has_opponent_ROYAL_neighbours( /**/
		array $directions_list,
		ChessSquare $actual_square,
		ChessSquare $starting_square,
		$color_to_move,
		ChessBoard $board
	): bool {
		$ending_square=null;
		foreach ( $directions_list as $direction ) {
				$current_xy = self::DIRECTION_OFFSETS[$direction];
				/*Nov 26 Useless expression 
				$current_xy[0] *= 1;
				$current_xy[1] *= 1;*/

				$ending_square = self::square_surrounded_by_opponent_ROYALs(
					$actual_square,
					$starting_square,
					$current_xy[0],
					$current_xy[1],
					$color_to_move,
					$board,
					TRUE
				);
				if(!$ending_square)
				{ continue;
				}
				if($ending_square!=null)
				{
					return TRUE; //Atleast one ROYAL/Semi-ROYAL present
					//return FALSE; //Atleast one opponent ROYAL/Semi-ROYAL present
				}
			}
		if(!$ending_square)
		{ 	//return TRUE; //No Opponent ROYAL/Semi-ROYAL present
			return FALSE; //No ROYAL/Semi-ROYAL present
		}
		else
		{ 	//return TRUE; //No Opponent ROYAL/Semi-ROYAL present
			return FALSE; //No ROYAL/Semi-ROYAL present
		}
	}

static function check_opponent_neighbours(&$board,int $opponent_colors,bool $naarad_Opponent_ROYALp)
{
	$ending_square=null;
	$starting_square=null;
	$allpieces = null;

	if(($board->controller_color==null)||($board->controller_color!=$board->controlled_color)){
		$board->controller_color=3-$opponent_colors;
		$board->controlled_color=$opponent_colors;
	}

	if($opponent_colors==1)
		$starting_square=$board->bnsquare;

	if($opponent_colors==2)
		$starting_square=$board->wnsquare;

		if($naarad_Opponent_ROYALp==true) { $board->board[$starting_square->rank][$starting_square->file]->controlledpiece=false ;$starting_square=null;}

	if($starting_square!=null){	
		foreach ( self::KING_DIRECTIONS as $direction ) {
			$current_xy = self::DIRECTION_OFFSETS[$direction];
				/*Nov 26 Useless expression 
				$current_xy[0] *= 1;
				$current_xy[1] *= 1;*/
			
			$ending_square = self::square_surrounded_by_army(
				$starting_square,
				$current_xy[0],
				$current_xy[1],
				$opponent_colors,
				$board
			);
			if(!$ending_square)
				{ continue;
				}
			if($ending_square!=null)
				{
					if($board->board[$ending_square->rank][$ending_square->file]->group=='OFFICER')
					$board->board[$ending_square->rank][$ending_square->file]->controlledpiece=true;
					$allpieces[] = $board->board[$ending_square->rank][$ending_square->file];
					continue;
				}
			}
		}
	if(!$allpieces)
		{
		if($opponent_colors==2) $board->PinnedBRefugees= [];
		if($opponent_colors==1) $board->PinnedWRefugees= [];
		}
	else
		{
		if(($opponent_colors==2) && ($board->whitencanfullmove==1)) $board->PinnedBRefugees= $allpieces; 
		else $board->PinnedBRefugees= [];
		if(($opponent_colors==1)  && ($board->blackncanfullmove==1)) $board->PinnedWRefugees= $allpieces;
		else $board->PinnedWRefugees= [];
		}
}

	static function populate_opponent_neighbours( $board){
		//check opponent ROYALs
		$wnpiece=null;$bnpiece=null;
		$naarad_Opponent_ROYALp=false;

		if(($board->bnsquare!=null))
		{	$bnpiece=$board->board[$board->bnsquare->rank][$board->bnsquare->file];
			$naarad_Opponent_ROYALp=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $board->bnsquare, $board->bnsquare, 1, $board );	
			self::check_opponent_neighbours($board,1,$naarad_Opponent_ROYALp,$naarad_Opponent_ROYALp);
		}
		
		if(($board->wnsquare!=null))
		{
			$wnpiece=$board->board[$board->wnsquare->rank][$board->wnsquare->file];
			$naarad_Opponent_ROYALp=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $board->wnsquare, $board->wnsquare, 2, $board );	
			self::check_opponent_neighbours($board,2,$naarad_Opponent_ROYALp,$naarad_Opponent_ROYALp);
		}
	}

	//in future, merge this function with has_opponent_ROYAL_neighbours

	static function square_surrounded_by_opponent_ROYALs(
		ChessSquare $actual_square,
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board,
		bool $sameplace
	): ?ChessSquare 
	{
		$sameplace;
		$ROYALcolor=3-$color_to_move; //Revert the Color

		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;

		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);

		if (($sameplace==TRUE)&&($ending_square)){
				if(($actual_square->rank==$ending_square->rank)&& ($actual_square->file==$ending_square->file)){
					return null;
			}
		}

		if ($ending_square) {

			/*if(($ending_square->file==0) &&($ending_square->rank==7))
			{ $tttt=1; 	} */

			if (($starting_square->rank==0) &&($starting_square->file==5) && ($ending_square->rank==0)&&($ending_square->file==4)) {
				$starting_square=$starting_square;
			}

			if(($starting_square->file==0)||($starting_square->file==9)){
				if(($starting_square->rank>=1)&&($starting_square->rank<=8)&&($ending_square->file!=$starting_square->file))
				{//Non-truce zone endpoint but ROYAL is in Truce-Zone. return
					return null;
				}
				else
					$rank;
			}
		}

		if (!$ending_square) {
			return null;
		} else {
			if ((($ending_square->rank==0)&&($starting_square->rank==0) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->file>=1) && ($ending_square->file<=8))||(($ending_square->rank==9)&&($starting_square->rank==9) && 
			($starting_square->file>=1) && ($starting_square->file<=8)&&($ending_square->file>=1) && ($ending_square->file<=8))||
			(($starting_square->rank>=1)&&($starting_square->rank<=8) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->rank>=1)&&($ending_square->rank<=8) && ($ending_square->file>=1) && ($ending_square->file<=8))) 
			{
				if ($board->board[$rank][$file]) {
					if (($board->board[$rank][$file]->color == $ROYALcolor) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
					{
						 //*echo ' Ending square contains a friendly ROYAL ';*/
						return $ending_square;
					}
					else
						$ending_square=null;

				}
				else
				  $ending_square=null;
			}
			else if ((($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==0) && 	($ending_square->file==0))||
			(($ending_square->rank==7)&&($starting_square->rank==8) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==0) && ($ending_square->file==0))||
			(($ending_square->rank==2)&&($starting_square->rank==1) && ($starting_square->file==9) && ($ending_square->file==9))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==0) &&	($ending_square->file==0)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==0) && ($ending_square->file==0)&&($ending_square->rank>=2))||
			(($ending_square->rank>=1)&&($starting_square->rank==($ending_square->rank)+1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank<=7))||
			(($ending_square->rank<=8)&&($starting_square->rank==($ending_square->rank)-1) && ($starting_square->file==9) && ($ending_square->file==9)&&($ending_square->rank>=2))
			) 
			{
				if ($board->board[$rank][$file]) {
					if (($board->board[$rank][$file]->color == $ROYALcolor) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
					{
						 //*echo ' Ending square contains a friendly ROYAL ';*/
						return $ending_square;
					}
					else 
						$ending_square=null;
				}
				else
				  $ending_square=null;
			}
			elseif ((($ending_square->rank==0)&&($starting_square->rank<=1) && ($starting_square->file>=1) && ($starting_square->file<=8)&&
			($ending_square->file>=1) && ($ending_square->file<=8) &&  ($board->wbrokencastle==true ))||(($ending_square->rank>=8)&&($starting_square->rank==9) && 
			($starting_square->file>=1) && ($starting_square->file<=8)&&($ending_square->file>=1) && ($ending_square->file<=8) && ($board->bbrokencastle==true ))) 
			{
				if ($board->board[$rank][$file]) {
					if (($board->board[$rank][$file]->color == $ROYALcolor) && (($board->board[$rank][$file]->group=='ROYAL')||($board->board[$rank][$file]->group=='SEMIROYAL')))
					{
						 //*echo ' Ending square contains a friendly ROYAL ';*/
						return $ending_square;
					}
					else
						$ending_square=null;

				}
				else
				  $ending_square=null;
			}
			else
				$ending_square=null;
		}
		return $ending_square;
	}

	static function has_ROYAL_neighbours( /**/
			array $directions_list,
			ChessSquare $actual_square,
			ChessSquare $starting_square,
			$color_to_move,
			ChessBoard $board,
			$royaltype="ALL"
		): bool {
			$ending_square=null;
			foreach ( $directions_list as $direction ) {
					$current_xy = self::DIRECTION_OFFSETS[$direction];
					$current_xy[0] *= 1;
					$current_xy[1] *= 1;

					$ending_square = self::ROYAL_square_surrounded_by_ROYALs(
						$actual_square,
						$starting_square,
						$current_xy[0],
						$current_xy[1],
						$color_to_move,
						$board,
						TRUE,
						"Tight",$royaltype
					);
					if(!$ending_square)
					{ continue;
					}
					if($ending_square!=null)
					{
						return TRUE;
					}
				}
			if(!$ending_square)
			{ return FALSE;
			}
			else
				return TRUE;
		}


		static function is_trapped_in_palace( /**/
			array $directions_list,
			ChessSquare $actual_square,
			ChessSquare $starting_square,
			$color_to_move,
			ChessBoard $board
		): bool {
			$ending_square=null;
			$royaltype="ALL";
			foreach ( $directions_list as $direction ) {
					$current_xy = self::DIRECTION_OFFSETS[$direction];
					$current_xy[0] *= 1;
					$current_xy[1] *= 1;

					$ending_square = self::ROYAL_square_surrounded_by_ROYALs(
						$actual_square,
						$starting_square,
						$current_xy[0],
						$current_xy[1],
						$color_to_move,
						$board,
						TRUE,
						"Tight",$royaltype
					);
					if(!$ending_square)
					{ continue;
					}
					if($ending_square!=null)
					{
						return TRUE;
					}
				}
			if(!$ending_square)
			{ return FALSE;
			}
			else
				return TRUE;
		}


	static function has_general_neighbour( /**/
			array $directions_list,
			ChessSquare $actual_square,
			ChessSquare $starting_square,
			$color_to_move,
			ChessBoard $board
		): bool {
			$ending_square=null;
			foreach ( $directions_list as $direction ) {
					$current_xy = self::DIRECTION_OFFSETS[$direction];
				/*Nov 26 Useless expression 
				$current_xy[0] *= 1;
				$current_xy[1] *= 1;*/

					$ending_square = self::square_surrounded_by_general(
						$actual_square,
						$starting_square,
						$current_xy[0],
						$current_xy[1],
						$color_to_move,
						$board,
						TRUE
					);
					if(!$ending_square)
					{ continue;
					}
					if($ending_square!=null)
					{
						return TRUE;
					}
				}
			if(!$ending_square)
			{ return FALSE;
			}
			else
				return TRUE;
		}

		static function get_piece_castle_with_ROYALs( /**/
			ChessPiece $piece,
			$color_to_move,
			ChessBoard $board
		): int {
			$j=0; $ctype=1;
			//self CASTLE itself is full ROYAL.. Any Officer present in it does not require ROYALs to be present
			if((($piece->square->rank==0)&&($piece->square->file>0)&&($piece->square->file<9)&&($color_to_move==1)) || (($piece->square->rank==9)&&($piece->square->file>0)&&($piece->square->file<9)&&($color_to_move==2))){
				
				if (( $board->iswcZoneRoyal==true) && ($piece->square->rank==0) )  {
					return 1;

					}
				else if (( $board->isbcZoneRoyal==true) && ($piece->square->rank==9) )  {
						return 1;	
						}		
					return 0;
			
			}
			//inside Foe CASTLE
			if((($piece->square->rank==9)&&($piece->square->file>0)&&($piece->square->file<9)&&($color_to_move==1))) {
				$ctype=2;$j=9;
				}
			//inside Foe castle	
			elseif((($piece->square->rank==0)&&($piece->square->file>0)&&($piece->square->file<9)&&($color_to_move==2))){ 
				$ctype=1;$j=0;
				}
			else{
				return -1; //piece is not in castle
			}

			for ($i = 1; $i <= 8; $i++) { /**Loop through foe castle. Any side can enter castle*/
					if (!$board->board[$j][$i]) {
						continue;
					}

					//Its own ROYAL member is present in foe
					if(($piece->color==$board->board[$j][$i]->color)&&(($board->board[$j][$i]->group=='ROYAL') || ($board->board[$j][$i]->group=='SEMIROYAL'))){
						return 0; //inside foe castle
					}
					else
						continue;
				}
			return -1; //piece is not in castle
		}

		static function get_compromised_castle( /**/
			ChessPiece $piece,
			$color_to_move,
			$castletocheck,
			ChessBoard $board
		): bool {
			$j=0;
			$ctype=1;
			if(abs($color_to_move-$castletocheck)==1){ //foe
				$ctype=$castletocheck;
			}
			else
			if(abs($color_to_move-$castletocheck)!=1){ //self
				$ctype=$color_to_move;
			}

			for ($i = 1; $i <= 8; $i++) { /**Loop through castle . Any side can enter castle*/
				if ($castletocheck==1) {
					$j=0;
					}

				if ($castletocheck==2) {
						$j=9;
					}

					if (!$board->board[$j][$i]) {
						continue;
					}

					if((($board->board[$j][$i]->group=='ROYAL') || ($board->board[$j][$i]->group=='SEMIROYAL') ||
					($board->board[$j][$i]->group=='NOBLE')) &&($board->board[$j][$i]->color!=$ctype)){//Compromised
						return true;
					}
					if(($board->board[$j][$i]->group!='ROYAL')&&($board->board[$j][$i]->group!='SEMIROYAL')&&
					($board->board[$j][$i]->group!='NOBLE')&&($board->board[$j][$i]->color!=$ctype)){//Compromised
						return true;
					}
					else
						continue;
				}			
			return false;
		}

	static function check_ROYAL_neighbours( /* check_neighbour_type = Zonal, Tight*/
		array $directions_list,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		string $check_neighbour_type
	): bool {
		$ending_square=null;
		$starting_square=$piece->square;
		$ROYALs=0;

		if(($check_neighbour_type=="Zone") && (($piece->group=="ROYAL") || ($piece->group=="SEMIROYAL")))
		{
			if((($piece->color==	$color_to_move)	&& ($piece->square->file>0) && ($piece->square->file<9)&& ($piece->square->rank==9) && ($piece->color==2))||
			(($piece->color==	$color_to_move)	&& ($piece->square->file>0) && ($piece->square->file<9)&& ($piece->square->rank==0) && ($piece->color==1)))
				{
					return true;
				}
			if((($piece->color!=$color_to_move)	&& ($piece->square->file>0) && ($piece->square->file<9)&& ($piece->square->rank==9) && ($piece->color==2)))
				{
				$maxrow=9;$maxcol=8;$minrow=9;$mincol=1;
				}
			if((($piece->color!= $color_to_move)	&& ($piece->square->file>0) && ($piece->square->file<9)&& ($piece->square->rank==0) && ($piece->color==1)))
				$maxrow=0;$maxcol=8;$minrow=0;$mincol=1;
			//no Mans Logic not added yet Oct 2021
			$maxrow=0;$maxcol=0;$minrow=0;$mincol=0;

			if(($starting_square->rank>=1)&&($starting_square->rank<=8)&&($starting_square->file>0)&&($starting_square->file<9)){
			$maxrow=8;$maxcol=8;$minrow=1;$mincol=1;}

			//opponent CASTLE
			if(($starting_square->rank==0)&&($starting_square->file>0)&&($starting_square->file<9)){
				$maxrow=0;$maxcol=8;$minrow=0;$mincol=1;}

			//opponent CASTLE	
			if(($starting_square->rank==9)&&($starting_square->file>0)&&($starting_square->file<9)){
				$maxrow=9;$maxcol=8;$minrow=9;$mincol=1;}

			if(($starting_square->file==0)&&($starting_square->rank>0)&&($starting_square->rank<9)){
				$maxrow=8;$maxcol=0;$minrow=1;$mincol=0;}

			if(($starting_square->file==9)&&($starting_square->rank>0)&&($starting_square->rank<9)){
				$maxrow=8;$maxcol=9;$minrow=1;$mincol=9;}

			for ($rank = $maxrow; $rank >= $minrow; $rank--) {
				if($ROYALs>=2) break;
				for ($file = $mincol; $file <= $maxcol; $file++) {
					if($ROYALs>=2) break;
					//war Zone
					if (($board->board[$rank][$file]!=null) && (($board->board[$rank][$file]->color== $color_to_move) ))
						{
							if(($board->board[$rank][$file]->group=="ROYAL") ||($board->board[$rank][$file]->group=="SEMIROYAL"))
							{
								$ROYALs=$ROYALs+1;
							}
						}
				}
			}
		}
		if($ROYALs>=2) return true;
		else return false;
	}



	//Modified by Ashok
	static function get_LastGeneralRow(ChessPiece $piece,	$color_to_move,	ChessBoard $board,	int $mtype	): int{

		$ksquare=$board->get_king_square(abs($color_to_move));
		$asquare=$board->get_arthshastri_square(abs($color_to_move));
		$gsquare=$board->get_general_square($color_to_move);

		if($ksquare==null) return -1;
		elseif($gsquare==null)  return -1;		

		if((($piece->group=="OFFICER") ||($piece->group=="SOLDIER"))&&($piece->color==1)){
			if((($ksquare->file==0)||($ksquare->file==9))&&(($ksquare->rank>=1)&&($ksquare->rank<=4)&&($piece->square->rank>=1)&&($piece->square->rank<=4))){
				return 4;
			}
			if((($ksquare->file==0)||($ksquare->file==9))&&(($ksquare->rank>=1)&&($ksquare->rank<=4)&&($piece->square->rank>=5)&&($piece->square->rank<=8))){
				return $piece->square->rank-1;
			}
			if((($ksquare->file==0)||($ksquare->file==9))&&($ksquare->rank>=5)&&($ksquare->rank<=8)){
				if(($piece->square->rank>=1)&&($piece->square->rank<=8)&&($piece->square->rank<=$ksquare->rank)){
					return $ksquare->rank;
				}
				else if(($piece->square->rank>=1)&&($piece->square->rank<=8)&&($piece->square->rank>$ksquare->rank)){
					return $piece->square->rank-1; //Can retreat from lower to very lower
				}
			}
		}
		else if((($piece->group=="OFFICER") ||($piece->group=="SOLDIER"))&&($piece->color==2)){
			if((($ksquare->file==0)||($ksquare->file==9))&&(($ksquare->rank>=5)&&($ksquare->rank<=8)&&($piece->square->rank>=5)&&($piece->square->rank<=8))){
				return 5;
				}
			if((($ksquare->file==0)||($ksquare->file==9))&&(($ksquare->rank>=5)&&($ksquare->rank<=8)&&($piece->square->rank>=1)&&($piece->square->rank<=4))){
				return $piece->square->rank+1;
				}
			if((($ksquare->file==0)||($ksquare->file==9))&&($ksquare->rank>=1)&&($ksquare->rank<=4)){
				if(($piece->square->rank>=1)&&($piece->square->rank<=8)&&($piece->square->rank>=$ksquare->rank)){
					return $ksquare->rank;
					}
				else if(($piece->square->rank>=1)&&($piece->square->rank<=8)&&($piece->square->rank<$ksquare->rank)){
					return $piece->square->rank+1; //Can retreat from lower to upper
					}
				}
			}
		return -1;
	}

	//Modified by Ashok
	static function get_LastKing_ArthShashtri_Row(ChessPiece $piece,	$color_to_move,	ChessBoard $board,	int $mtype	): int 
	{

			
		$ksquare=$board->get_king_square(abs($color_to_move));
		$asquare=$board->get_arthshastri_square(abs($color_to_move));
		$gsquare=$board->get_general_square($color_to_move);

		if($ksquare==null) {
			if($gsquare==null) {
				return -1;
			}
		}


		//Remaining part is for SAmraat Ashok.... So skipping for now

		if((($piece->group=="OFFICER") ||($piece->group=="SOLDIER"))&&($piece->color==1)){

			//KING has primary TRUCE Obligations
			if(($board->gametype==5) &&( ($board->commonborderbreached==false)&& ($ksquare!=null)&&((($ksquare->file==0)||($ksquare->file==9))&&(($ksquare->rank>=1)&&($ksquare->rank<=4)&&($piece->square->rank>=1)&&($piece->square->rank<=4))))){
				return 4;
			}
	
			if(($ksquare!=null)&&((($ksquare->file==0)||($ksquare->file==9))&&(($ksquare->rank>=1)&&($ksquare->rank<=8)&&($piece->square->rank>=1)&&($piece->square->rank<=9)))){
				if($piece->group=="SOLDIER")
				return $ksquare->rank;
			}			
			//Ashok Logic	
			/*	
			else if(($ksquare!=null)&&((($ksquare->file==0)||($ksquare->file==9))&&(($ksquare->rank>=1)&&($ksquare->rank<=4)&&($piece->square->rank>=5)&&($piece->square->rank<=9)))){
				return $piece->square->rank-1;
			}
			else if(($ksquare!=null)&&((($ksquare->file==0)||($ksquare->file==9))&&($ksquare->rank>=5)&&($ksquare->rank<=8))){
				if(($piece->square->rank>=1)&&($piece->square->rank<=9)&&($piece->square->rank<=$ksquare->rank)){
					return $ksquare->rank;
				}
				else if(($piece->square->rank>=1)&&($piece->square->rank<=9)&&($piece->square->rank>$ksquare->rank)){
					return $piece->square->rank-1; //Can retreat from lower to very lower
				}
			}
			*/
			//ArthShastri has secondary TRUCE Obligations

			if(($board->gametype==5)&& ($asquare!=null)&&((($asquare->file==0)||($asquare->file==9))&&(($asquare->rank>=1)&&($asquare->rank<=4)&&($piece->square->rank>=1)&&($piece->square->rank<=4)))){
				return 4;
			}
			if(($asquare!=null)&&((($asquare->file==0)||($asquare->file==9))&&(($asquare->rank>=1)&&($asquare->rank<=8)&&($piece->square->rank>=1)&&($piece->square->rank<=9)))){
				return $asquare->rank;
			}

			//Ashok Logic
			/*
			else if(($asquare!=null)&&((($asquare->file==0)||($asquare->file==9))&&(($asquare->rank>=1)&&($asquare->rank<=4)&&($piece->square->rank>=5)&&($piece->square->rank<=9)))){
				return $piece->square->rank-1;
			}
			else if(($asquare!=null)&&((($asquare->file==0)||($asquare->file==9))&&($asquare->rank>=5)&&($asquare->rank<=8))){
				if(($piece->square->rank>=1)&&($piece->square->rank<=9)&&($piece->square->rank<=$asquare->rank)){
					return $asquare->rank;
				}
				else if(($piece->square->rank>=1)&&($piece->square->rank<=9)&&($piece->square->rank>$asquare->rank)){
					return $piece->square->rank-1; //Can retreat from lower to very lower
				}
			}
			*/

			//General has tertiary TRUCE Obligations
			if(($board->gametype==5)&&($gsquare!=null)&&((($gsquare->file==0)||($gsquare->file==9))&&(($gsquare->rank>=1)&&($gsquare->rank<=4)&&($piece->square->rank>=1)&&($piece->square->rank<=4)))){
				return 4;
			}
			if(($gsquare!=null)&&((($gsquare->file==0)||($gsquare->file==9))&&(($gsquare->rank>=1)&&($gsquare->rank<=8)&&($piece->square->rank>=1)&&($piece->square->rank<=9)))){
				return $asquare->rank;
			}
			//Ashok Logic
			/*
			else if(($gsquare!=null)&&((($gsquare->file==0)||($gsquare->file==9))&&(($gsquare->rank>=1)&&($gsquare->rank<=4)&&($piece->square->rank>=5)&&($piece->square->rank<=9)))){
				return $piece->square->rank-1;
			}

			else if(($gsquare!=null)&&((($gsquare->file==0)||($gsquare->file==9))&&($gsquare->rank>=5)&&($gsquare->rank<=8))){
				if(($piece->square->rank>=1)&&($piece->square->rank<=9)&&($piece->square->rank<=$gsquare->rank)){
					return $gsquare->rank;
				}
				else if(($piece->square->rank>=1)&&($piece->square->rank<=9)&&($piece->square->rank>$gsquare->rank)){
					return $piece->square->rank-1; //Can retreat from lower to very lower
				}
			}
			*/
		return 9;
		}
		else if((($piece->group=="OFFICER") ||($piece->group=="SOLDIER"))&&($piece->color==2)){
			if(($board->gametype==5) && (($board->commonborderbreached==false)&&($ksquare!=null)&&((($ksquare->file==0)||($ksquare->file==9))&&(($ksquare->rank>=5)&&($ksquare->rank<=8)&&($piece->square->rank>=5)&&($piece->square->rank<=8))))){
				return 5;
			}
			if(($ksquare!=null)&&((($ksquare->file==0)||($ksquare->file==9))&&(($ksquare->rank>=1)&&($ksquare->rank<=8)&&($piece->square->rank>=0)&&($piece->square->rank<=8)))){
				return $ksquare->rank;
			}
			//Ashok Logic
			/*
			else if(($ksquare!=null)&&((($ksquare->file==0)||($ksquare->file==9))&&(($ksquare->rank>=5)&&($ksquare->rank<=8)&&($piece->square->rank>=0)&&($piece->square->rank<=4)))){
				return $piece->square->rank+1;
			}
			else if(($ksquare!=null)&&((($ksquare->file==0)||($ksquare->file==9))&&($ksquare->rank>=1)&&($ksquare->rank<=4))){
				if(($piece->square->rank>=0)&&($piece->square->rank<=8)&&($piece->square->rank>=$ksquare->rank)){
					return $ksquare->rank;
				}
				else if(($piece->square->rank>=0)&&($piece->square->rank<=8)&&($piece->square->rank<$ksquare->rank)){
					return $piece->square->rank+1; //Can retreat from lower to upper
				}
			}
			*/
		
			if(($board->gametype==5)&&($asquare!=null)&&((($asquare->file==0)||($asquare->file==9))&&(($asquare->rank>=5)&&($asquare->rank<=8)&&($piece->square->rank>=5)&&($piece->square->rank<=8)))){
				return 5;
			}
			if(($asquare!=null)&&((($asquare->file==0)||($asquare->file==9))&&(($asquare->rank>=1)&&($asquare->rank<=8)&&($piece->square->rank>=0)&&($piece->square->rank<=8)))){
				return $asquare->rank;
			}			
			//Ashok Logic
			/*
			else if(($asquare!=null)&&((($asquare->file==0)||($asquare->file==9))&&(($asquare->rank>=5)&&($asquare->rank<=8)&&($piece->square->rank>=0)&&($piece->square->rank<=4)))){
				return $piece->square->rank+1;
			}
			else if(($asquare!=null)&&((($asquare->file==0)||($asquare->file==9))&&($asquare->rank>=1)&&($asquare->rank<=4))){
				if(($piece->square->rank>=0)&&($piece->square->rank<=8)&&($piece->square->rank>=$asquare->rank)){
					return $asquare->rank;
				}
				else if(($piece->square->rank>=0)&&($piece->square->rank<=8)&&($piece->square->rank<$asquare->rank)){
					return $piece->square->rank+1; //Can retreat from lower to upper
				}
			}
			*/

			if(($board->gametype==5)&&($gsquare!=null)&&((($gsquare->file==0)||($gsquare->file==9))&&(($gsquare->rank>=5)&&($gsquare->rank<=8)&&($piece->square->rank>=5)&&($piece->square->rank<=8)))){
				return 5;
			}
			if(($gsquare!=null)&&((($gsquare->file==0)||($gsquare->file==9))&&(($gsquare->rank>=1)&&($gsquare->rank<=8)&&($piece->square->rank>=0)&&($piece->square->rank<=8)))){
				return $gsquare->rank;
			}			
			//Ashok Logic
			/*
			else if(($gsquare!=null)&&((($gsquare->file==0)||($gsquare->file==9))&&(($gsquare->rank>=5)&&($gsquare->rank<=8)&&($piece->square->rank>=1)&&($piece->square->rank<=4)))){
				return $piece->square->rank+1;
			}
			else if(($gsquare!=null)&&((($gsquare->file==0)||($gsquare->file==9))&&($gsquare->rank>=1)&&($gsquare->rank<=4))){
				if(($piece->square->rank>=0)&&($piece->square->rank<=8)&&($piece->square->rank>=$gsquare->rank)){
					return $gsquare->rank;
				}
				else if(($piece->square->rank>=0)&&($piece->square->rank<=8)&&($piece->square->rank<$gsquare->rank)){
					return $piece->square->rank+1; //Can retreat from lower to upper
				}
			}
			*/
			return 0;
		}
		return -1;
	}

	static function get_corrected_Retreating_Knight_General_directions(
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		int $mtype,
		int $lastaccessiblerow,
		$tempDirection
	): array {

		$directions_list=[];
			
				if(($piece->color==1)&&($lastaccessiblerow<$piece->square->rank))
					{
						if(($piece->type == ChessPiece::KNIGHT)||($piece->type == ChessPiece::GENERAL)){
							if($mtype==2){
								$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_11;
								}
							return $directions_list;
							}
					}
				elseif(($piece->color==2)&&($lastaccessiblerow>$piece->square->rank))
					{				
						if(($piece->type == ChessPiece::KNIGHT)||($piece->type == ChessPiece::GENERAL)){
							if($mtype==2){
									$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_22;
								}
							return $directions_list;
							}
					}
				
		return $tempDirection;
	}

	//Army can retreat as per King or Generals Order
	static function get_CommonBorderOpen_Status(
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		ChessSquare $ending_square
	):int {

		//True = Open Border // False = May or May not have Border
		if(  (($piece->color==1) && ($ending_square->rank>4) &&($piece->square->rank<=4)) && ($board->commonborderbreached==true))
		{
			return 1;
		}
		else if(  (($piece->color==2) && ($ending_square->rank<5) &&($piece->square->rank>=5))&&($board->commonborderbreached==true))
		{
			return 1;
		}
		else if(  (($piece->color==1) && ($ending_square->rank<=4) &&($piece->square->rank<=4)))
		{
			return -1;
		}
		else if(  (($piece->color==2) && ($ending_square->rank>=5) &&($piece->square->rank>=5)))
		{
			return -1;
		}	
		else if(  (($piece->color==1) && ($ending_square->rank>4) &&($piece->square->rank<=4)) && ($board->commonborderbreached==false))
		{
			return 0;
		}
		else if(  (($piece->color==2) && ($ending_square->rank<5) &&($piece->square->rank>=5))&&($board->commonborderbreached==false))
		{
			return 0;
		}			
		else 
			return -1;
	}

	//Army can retreat as per King or Generals Order
	static function get_CastleBorderOpen_Status(
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		ChessSquare $ending_square
	):int {

		//True = Open Border // False = May or May not have Border
		if(  (($piece->color==1) && ($ending_square->rank>4) &&($piece->square->rank<=4)) && ($board->commonborderbreached==true))
		{
			return 1;
		}
		else if(  (($piece->color==2) && ($ending_square->rank<5) &&($piece->square->rank>=5))&&($board->commonborderbreached==true))
		{
			return 1;
		}
		else if(  (($piece->color==1) && ($ending_square->rank<=4) &&($piece->square->rank<=4)))
		{
			return -1;
		}
		else if(  (($piece->color==2) && ($ending_square->rank>=5) &&($piece->square->rank>=5)))
		{
			return -1;
		}	
		else if(  (($piece->color==1) && ($ending_square->rank>4) &&($piece->square->rank<=4)) && ($board->commonborderbreached==false))
		{
			return 0;
		}
		else if(  (($piece->color==2) && ($ending_square->rank<5) &&($piece->square->rank>=5))&&($board->commonborderbreached==false))
		{
			return 0;
		}			
		else 
			return -1;
	}

	//Army can retreat as per King / Arthshastri or Generals Order
	static function get_Retreating_ARMY_directions(
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		int $mtype

	): array {

			$directions_list=[];
			$ksquare=array ("rank"=>0, "file"=>0);
			$ksquare=$board->get_king_square(abs($color_to_move));//

			$asquare=$board->get_arthshastri_square(abs($color_to_move));//
			$gsquare=$board->get_general_square($color_to_move);//

			if($ksquare==null) {
				//Check if General is available or not
					//Check if General is available or not
				if($asquare==null) {
						return [];
					}			
				if($gsquare==null) {
					return [];
				}
			}

			if($ksquare!=null) {	
				if((($piece->group=="OFFICER") ||($piece->group=="SOLDIER"))&&(($ksquare->file==0)||($ksquare->file==9))&&($ksquare->rank>=1)&&($ksquare->rank<=8))
				{
					if($piece->color==1)
						{
							//ashok logic
							/*if((($ksquare->rank>=1)&&($ksquare->rank<=4)&&($piece->square->rank>=1)&&($piece->square->rank<=4))||
							(($ksquare->rank>=5)&&($piece->square->rank<$ksquare->rank))){
								return [];
							}*/
							if(($piece->square->rank<=$ksquare->rank)){
								return [];
							}							
							else
								{
								if($piece->type == ChessPiece::PAWN){
									$directions_list=self::RETREATING_WHITE_PAWN_DIRECTIONS;
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::BISHOP){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_BISHOP_DIRECTIONS_1;
										}
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::KNIGHT){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_1;
										}
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::ROOK){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_ROOK_DIRECTIONS_1;
										}
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::GENERAL){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_1;
										}
									return $directions_list;
									}
								}
						}
					elseif($piece->color==2)
						{
							/*
							if((($ksquare->rank>=5)&&($ksquare->rank<=8)&&($piece->square->rank>=5)&&($piece->square->rank<=8))||
							(($ksquare->rank<=4)&&($piece->square->rank>=$ksquare->rank))){
								return [];
							}*/
							if(($piece->square->rank>=$ksquare->rank)){
								return [];
							}							
							else
								{
								if($piece->type == ChessPiece::PAWN){
									$directions_list=self::RETREATING_BLACK_PAWN_DIRECTIONS;
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::BISHOP){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_BISHOP_DIRECTIONS_2;
										}
									return $directions_list;
								}
								elseif($piece->type == ChessPiece::KNIGHT){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
									}
									elseif($mtype==2){
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_2;
									}
								}
								elseif($piece->type == ChessPiece::ROOK){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_ROOK_DIRECTIONS_2;
										}
									return $directions_list;
								}
								elseif ($piece->type == ChessPiece::GENERAL) {
									if ($mtype==1) {
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
									} elseif ($mtype==2) {
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_2;
									}
									return $directions_list;
									}
								}
						}
				}
			}

			if($asquare!=null) {	
				if((($piece->group=="OFFICER") ||($piece->group=="SOLDIER"))&&(($asquare->file==0)||($asquare->file==9))&&($asquare->rank>=1)&&($asquare->rank<=8))
				{
					if($piece->color==1)
						{
							/*
							if((($asquare->rank>=1)&&($asquare->rank<=4)&&($piece->square->rank>=1)&&($piece->square->rank<=4))||
							(($asquare->rank>=5)&&($piece->square->rank<$asquare->rank))){
								return [];
							}
							*/
							if(($piece->square->rank<=$asquare->rank)){
								return [];
							}							
							else
								{
								if($piece->type == ChessPiece::PAWN){
									$directions_list=self::RETREATING_WHITE_PAWN_DIRECTIONS;
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::BISHOP){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_BISHOP_DIRECTIONS_1;
										}
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::KNIGHT){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_1;
										}
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::ROOK){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_ROOK_DIRECTIONS_1;
										}
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::GENERAL){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_1;
										}
									return $directions_list;
									}
								}
						}
					elseif($piece->color==2)
						{
							/*
							if((($asquare->rank>=5)&&($asquare->rank<=8)&&($piece->square->rank>=5)&&($piece->square->rank<=8))||
							(($asquare->rank<=4)&&($piece->square->rank>=$asquare->rank))){
								return [];
							}*/
							if(($piece->square->rank>=$asquare->rank)){
								return [];
							}							
							else
								{
								if($piece->type == ChessPiece::PAWN){
									$directions_list=self::RETREATING_BLACK_PAWN_DIRECTIONS;
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::BISHOP){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_BISHOP_DIRECTIONS_2;
										}
									return $directions_list;
								}
								elseif($piece->type == ChessPiece::KNIGHT){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
									}
									elseif($mtype==2){
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_2;
									}
								}
								elseif($piece->type == ChessPiece::ROOK){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_ROOK_DIRECTIONS_2;
										}
									return $directions_list;
								}
								elseif ($piece->type == ChessPiece::GENERAL) {
									if ($mtype==1) {
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
									} elseif ($mtype==2) {
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_2;
									}
									return $directions_list;
									}
								}
						}
				}
			}

			if($gsquare!=null) {
				if((($piece->group=="OFFICER") ||($piece->group=="SOLDIER"))&&(($gsquare->file==0)||($gsquare->file==9))&&($gsquare->rank>=1)&&($gsquare->rank<=8))
				{
					if($piece->color==1)
						{
							/*if((($gsquare->rank>=1)&&($gsquare->rank<=4)&&($piece->square->rank>=1)&&($piece->square->rank<=4))||
							(($gsquare->rank>=5)&&($piece->square->rank<$gsquare->rank))){
								return [];
							}*/
							if(($piece->square->rank<=$gsquare->rank)){
								return [];
							}							
							else
								{
								if($piece->type == ChessPiece::PAWN){
									$directions_list=self::RETREATING_WHITE_PAWN_DIRECTIONS;
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::BISHOP){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_BISHOP_DIRECTIONS_1;
										}
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::KNIGHT){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_1;
										}
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::ROOK){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_ROOK_DIRECTIONS_1;
										}
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::GENERAL){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_1;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_1;
										}
									return $directions_list;
									}
								}
						}
					elseif($piece->color==2)
						{
							/*
							if((($gsquare->rank>=5)&&($gsquare->rank<=8)&&($piece->square->rank>=5)&&($piece->square->rank<=8))||
							(($gsquare->rank<=4)&&($piece->square->rank>=$gsquare->rank))){
								return [];
							}*/
							if(($piece->square->rank>=$gsquare->rank)){
								return [];
							}
							else
								{
								if($piece->type == ChessPiece::PAWN){
									$directions_list=self::RETREATING_BLACK_PAWN_DIRECTIONS;
									return $directions_list;
									}
								elseif($piece->type == ChessPiece::BISHOP){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_BISHOP_DIRECTIONS_2;
										}
									return $directions_list;
								}
								elseif($piece->type == ChessPiece::KNIGHT){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
									}
									elseif($mtype==2){
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_2;
									}
									return $directions_list;
								}
								elseif($piece->type == ChessPiece::ROOK){
									if($mtype==1){
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
										}
									elseif($mtype==2){
										$directions_list=self::RETREATING_BISHOP_DIRECTIONS_2;
										}
									return $directions_list;
								}
								elseif ($piece->type == ChessPiece::GENERAL) {
									if ($mtype==1) {
										$directions_list=self::RETREATING_GENERAL_DIRECTIONS_2;
									} elseif ($mtype==2) {
										$directions_list=self::RETREATING_KNIGHT_DIRECTIONS_2;
									}
									return $directions_list;
									}
								}
						}
				}
			}
			return [];
		}

	static function castle_became_warzone_moves_to_moves_list(
			array $directions_list,
			int $spaces,
			array $moves,
			ChessPiece $piece,
			$color_to_move,
			ChessBoard $board,
			bool $store_board_in_moves,
			int $cankill,
			bool $get_FullMover,
			bool $selfbrokencastle,
			bool $foebrokencastle
		): array {
			return [];
		}

		static function add_slide_and_slidecontroller_moves_to_moves_list(
			array $directions_list,
			int $spaces,
			array $moves,
			ChessPiece $piece,
			$color_to_move,
			&$board,
			bool $store_board_in_moves,
			bool $get_FullMover,
			bool $selfbrokencastle,
			bool $foebrokencastle,
			int $get_CASTLEMover,
			bool $controlled_move
		): array {
	
			/* $boolslide=TRUE; $ROYALp=FALSE; $candemote=FALSE; $dem=0; $tempDirection=null; $mtype=1;
			$generalaccessiblerow=-1;$movesA=[]; */
			$lastaccessiblerow=-1;$cankill=0;$get_FullMover=true;$new_move=null;$opponent_refuged=false;
			$CommonBorderOpen_Status=-1;
			$capture = FALSE;;$unsecured=false;
			$moves1=[];
			$controlledpiece=null;$naaradblocks=0;
			$naarad_neutralized=false; 
	
			/* Start new code added for sleepin p[ieces */

			//Pieces inside CASTLE become Semi-Royal for the time-being.
			if(((($piece->square->file>=1) &&($piece->square->file<=3)) || (($piece->square->file>=6) &&($piece->square->file<=8)))  && 
			((($piece->square->rank==0) && ($color_to_move==1)) || ($piece->square->rank==9)  && ($color_to_move==2)))
			{ 
				if($board->isCurrentZoneRoyal==true){
					$naarad_neutralized=true; // naarad gets impacted by the royal king, spy or minister
				}
			}
		
			/*piece is trapped in palace and has no royal help.
			if(  ((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
			||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9))  ))
			{
				if($naarad_neutralized==false)
					return $moves;//
			}
			*/

			if($naarad_neutralized==false)		
				$naarad_neutralized=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $piece->square, $piece->square, 3-$color_to_move, $board );

			if($naarad_neutralized==false)
				$naarad_neutralized=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $piece->square, $piece->square, $color_to_move, $board );

			/* End new code added for sleepin p[ieces */
			if($board->isCurrentZoneRoyal==true) {$naarad_neutralized=true;$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;}
			else { $opponent_refuged=self::has_opponent_neighbours( self::KING_DIRECTIONS, $piece, 3-$color_to_move, $board );
			self::checkpinnedrefugees($color_to_move,$board, $piece->square,$piece->square);
			//self::checkpinnedrefugees(3-$color_to_move,$board, $piece->square,$piece->square);
			}
			
			//Narad is powerful and can be moved.		//Moving the Narad 

			if(($board->controller_color!=null) &(($controlled_move==false)&&($board->controller_color==$color_to_move)&&($board->controller_color==3-$color_to_move))){
				return $moves;
			}
			if(($naarad_neutralized==false)){
				//$spaces=2;
				//$board->color=3-$color_to_move;
				foreach ( $directions_list as $direction ) {
					for ( $dli = 1; $dli <= $spaces; $dli++ ) {
	
							$current_xy = self::DIRECTION_OFFSETS[$direction];
							$current_xy[0] *= $dli;
							$current_xy[1] *= $dli;
							$type=0;
	
							$ending_square = self::square_exists_and_not_occupied_by_friendly_piece(
								$type, '0', $piece->square, $current_xy[0], $current_xy[1], $color_to_move, $board, $cankill, true, $selfbrokencastle, $foebrokencastle					
							);
			
							if ( ! $ending_square ) {
								// square does not exist, or square occupied by friendly piece
								// stop sliding
								break;
								}
	
						if((($lastaccessiblerow!=-1)&&($color_to_move==2)&&($ending_square->rank<$lastaccessiblerow))||
						(($lastaccessiblerow!=-1)&&($color_to_move==1)&&($ending_square->rank>$lastaccessiblerow))){
							continue;
						}
							$new_move=null;
							$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 1, $piece->color, $piece->type, $capture, $board, $store_board_in_moves, false ,$controlled_move,$CommonBorderOpen_Status);

							$move2 = clone $new_move;
							$move2->PinnedBRefugees=[]; $move2->PinnedWRefugees=[];
							$move2->board->PinnedBRefugees=[]; $move2->board->PinnedWRefugees=[];
							$unsecured=false;
							$naaradblocks=0; $calculatednaaradblocks=0;
							if($color_to_move==1) {
								$naaradblocks =sizeof($board->PinnedBRefugees);
								$move2->board->wnsquare=$ending_square;
								self::check_opponent_neighbours($move2->board,3-$color_to_move,false);
								$calculatednaaradblocks=sizeof($move2->board->PinnedBRefugees);
							}
							else if($color_to_move==2) {
								$naaradblocks =sizeof($board->PinnedWRefugees);
								$move2->board->bnsquare=$ending_square;
								self::check_opponent_neighbours($move2->board,3-$color_to_move,false);
								$calculatednaaradblocks=sizeof($move2->board->PinnedWRefugees);
							}

							if($color_to_move==1) {
								$naaradblocks =sizeof($board->PinnedBRefugees);
								$move2->board->wnsquare=$ending_square;
								$calculatednaaradblocks=sizeof($move2->board->PinnedBRefugees);
							}
							else if($color_to_move==2) {
								$naaradblocks =sizeof($board->PinnedWRefugees);
								$move2->board->bnsquare=$ending_square;
								$calculatednaaradblocks=sizeof($move2->board->PinnedWRefugees);
							}
							if(($calculatednaaradblocks>=0)&& ($naaradblocks>0)&& ($calculatednaaradblocks<$naaradblocks))
								$unsecured=true;
							else if(($calculatednaaradblocks>=0)&& ($naaradblocks==0))
								$unsecured=false;
							//match if count is same then probably squares are not same
							else {
								//$maxcount=$naaradblocks;
								$securedcount=0;
								if($calculatednaaradblocks>=$naaradblocks){
									//$maxcount=$calculatednaaradblocks;
									for ( $i = 0; $i < $calculatednaaradblocks; $i++ ) {
										for ( $j = 0; $j < $naaradblocks; $j++ ) {
											if($color_to_move==1) {
												if(($move2->board->PinnedBRefugees[$i]->square->rank ==	$board->PinnedBRefugees[$j]->square->rank)&&
												($move2->board->PinnedBRefugees[$i]->square->file ==	$board->PinnedBRefugees[$j]->square->file))
												$securedcount=$securedcount+1;
												$move2->board->PinnedBRefugees[$i]->controlledpiece=true;
												//$move2->board->PinnedBRefugees[$i]->controlledpiece=true;
											}
											else if($color_to_move==2) {
												if(($move2->board->PinnedWRefugees[$i]->square->rank ==	$board->PinnedWRefugees[$j]->square->rank)&&
												($move2->board->PinnedWRefugees[$i]->square->file ==	$board->PinnedWRefugees[$j]->square->file))
												$move2->board->PinnedWRefugees[$i]->controlledpiece=true;

												$securedcount=$securedcount+1;
											}
										}
									}
									if($securedcount>=$naaradblocks)
										$unsecured=false;
									else $unsecured =true;
								}
								else if($calculatednaaradblocks<$naaradblocks){
									$unsecured=true;
								}

								if(($unsecured==false) && ($new_move!=null))
								{	$move2 = clone $new_move;
									$moves[] = $move2;
								}
							}
							//break;
						}
					/*if(($unsecured==false) && ($new_move!=null))
						{	$move2 = clone $new_move;
							$moves[] = $move2;
						}*/
				}
			}

			//Narad has no refugee hence can free run
			if($opponent_refuged==false){
				$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::BISHOP_DIRECTIONS, $spaces, $moves, $piece, $color_to_move, $board, $store_board_in_moves,0,0,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,False);
				$moves = self::add_slide_and_slidecapture_moves_to_moves_list(self::ROOK_DIRECTIONS,$spaces, $moves, $piece, $color_to_move, $board, $store_board_in_moves,0,0,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,False);
			}
			//Moving the pinned pieces and then its own pieces
			//$board->color_to_move=$color_to_move;

			$jumpstyle='3';$get_Killing_Allowed=0;

				if($color_to_move==1) {
					$naaradblocks =sizeof($board->PinnedBRefugees);
				}
				else if($color_to_move==2) {
					$naaradblocks =sizeof($board->PinnedWRefugees);
				}
				
				$naaradcolor= $color_to_move;
				$rank=null; $file=null; 

				$new_move = new ChessMove(
					$piece->square, $piece->square,$piece->square,
					0,
					$piece->color, $piece->type, $capture,
					$board,	$store_board_in_moves, TRUE,$controlled_move,$CommonBorderOpen_Status);

				for ( $k = 0; $k < $naaradblocks; $k++ ) {
					if(($naaradcolor==1) &&($board->PinnedBRefugees!=null)&&($board->PinnedBRefugees[$k]!=null)) {
						$rank=$board->PinnedBRefugees[$k]->square->rank;
						$file=$board->PinnedBRefugees[$k]->square->file;
						}
					else if(($naaradcolor==2) &&($board->PinnedWRefugees!=null)&&($board->PinnedWRefugees[$k]!=null)) {
						$rank=$board->PinnedWRefugees[$k]->square->rank;
						$file=$board->PinnedWRefugees[$k]->square->file;
						}
					
					if(($rank!=null) && ($file!=null) &&  (($board->board[$rank][$file]!=null) && ($board->board[$rank][$file]->group=="OFFICER") && ($board->board[$rank][$file]))) {
							$controlledpiece = clone $board->board[$rank][$file];
							if($controlledpiece!=null){
								$controlledpiece->striker=0;
								$color_to_move=3-$naaradcolor;
								$controlledpiece->controlledpiece=true;
								$board->board[$rank][$file]->controlledpiece=true;
								$board->color_to_move=$color_to_move;

								if(($board->gametype>=1) && ($controlledpiece->group=="OFFICER")){
									$get_FullMover=self::check_general_ROYAL_neighbours_promotion(self::KING_DIRECTIONS, $controlledpiece, $color_to_move, $board);
									}

								if ($controlledpiece->type == ChessPiece::GENERAL) {
									$moves1 = self::add_jump_and_jumpcapture_moves_to_moves_list(1,$jumpstyle,self::KNIGHT_DIRECTIONS, $moves1, $controlledpiece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,0,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,TRUE);
									$moves1 = self::add_slide_and_slidecapture_moves_to_moves_list(self::GENERAL_DIRECTIONS, 3, $moves1, $controlledpiece, $color_to_move, $board, $store_board_in_moves,0,0,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,TRUE);
								}
								else if ($controlledpiece->type == ChessPiece::KNIGHT) {
									//$moves1 = self::add_capture_moves_to_moves_list(self::GENERAL_DIRECTIONS, $moves1, $controlledpiece, $color_to_move, $board, $store_board_in_moves,0,$selfbrokencastle,$foebrokencastle);
									$moves1 = self::add_jump_and_jumpcapture_moves_to_moves_list(1,$jumpstyle,self::KNIGHT_DIRECTIONS, $moves1, $controlledpiece, $color_to_move, $board, $store_board_in_moves,$get_Killing_Allowed,0,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,TRUE);
									$moves1 = self::add_slide_and_slidecapture_moves_to_moves_list(self::GENERAL_DIRECTIONS, 2, $moves1, $controlledpiece, $color_to_move, $board, $store_board_in_moves,0,0,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,TRUE);

								}
								else if (($controlledpiece->type == ChessPiece::BISHOP) &&($color_to_move==$controlledpiece->color)) {
									if(($board->gametype==3))
									$moves1 = self::add_slide_and_slidecapture_moves_to_moves_list(self::BISHOP_DIRECTIONS, 2, $moves1, $controlledpiece, $color_to_move, $board, $store_board_in_moves,0,0,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,TRUE);
									else 
									$moves1 = self::add_slide_and_slidecapture_moves_to_moves_list(self::GENERAL_DIRECTIONS, 2, $moves1, $controlledpiece, $color_to_move, $board, $store_board_in_moves,0,0,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,TRUE);

								}
								else if (($controlledpiece->type == ChessPiece::ROOK) &&($color_to_move==$controlledpiece->color)) {
									$moves1 = self::add_slide_and_slidecapture_moves_to_moves_list(self::GENERAL_DIRECTIONS, 3, $moves1, $controlledpiece, $color_to_move, $board, $store_board_in_moves,0,0,$get_FullMover,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,TRUE);
						
								}
								else if ($controlledpiece->type == ChessPiece::PAWN) {
									$moves1 = self::add_slide_and_slidecapture_moves_to_moves_list(self::KING_DIRECTIONS, 1, $moves1, $piece, $color_to_move, $board, $store_board_in_moves,0,0,false,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,TRUE);
								}
							}
						}
				}

				$board->color_to_move=$naaradcolor;

				for ( $k = 0; $k < $naaradblocks; $k++ ) {
					if(($naaradcolor==1) &&($board->PinnedBRefugees!=null)&&($board->PinnedBRefugees[$k]!=null)) {
						$rank=$board->PinnedBRefugees[$k]->square->rank;
						$file=$board->PinnedBRefugees[$k]->square->file;
						}
					else if(($naaradcolor==2) &&($board->PinnedWRefugees!=null)&&($board->PinnedWRefugees[$k]!=null)) {
						$rank=$board->PinnedWRefugees[$k]->square->rank;
						$file=$board->PinnedWRefugees[$k]->square->file;
						}
					
					if(($rank!=null) && ($file!=null) &&  (($board->board[$rank][$file]!=null) && ($board->board[$rank][$file]->group=="SOLDIER") && ($board->board[$rank][$file]))) {
							$controlledpiece = clone $board->board[$rank][$file];
							if($controlledpiece!=null){
								$controlledpiece->striker=0;
								$color_to_move=3-$naaradcolor;
								$controlledpiece->controlledpiece=true;
								$board->color=$color_to_move;
								if ($controlledpiece->type == ChessPiece::PAWN) {
									$uncontrolled_officer=self::check_uncontrolled_officers_neighbours( self::KING_DIRECTIONS, $controlledpiece, $color_to_move, $board, 'exclude' ,$controlledpiece->controlledpiece);
									if($uncontrolled_officer==false)
									$moves1 = self::add_slide_and_slidecapture_moves_to_moves_list(self::KING_DIRECTIONS, 1, $moves1, $piece, $color_to_move, $board, $store_board_in_moves,0,0,false,$selfbrokencastle,$foebrokencastle,$get_CASTLEMover,true);
									}
								$board->color_to_move=$naaradcolor;
							}
					}
				}
				$board->color_to_move=$naaradcolor;
				$store_board_in_moves=True;

			if(count($moves1)>0){
					$new_move = new ChessMove(
						$piece->square,	$piece->square,$piece->square,0,$piece->color,$piece->type,$capture,$board,
						$store_board_in_moves, TRUE,$controlled_move,$CommonBorderOpen_Status);
			
					$new_move->controlled_moves=$moves1;
					$new_move->controlled_move=true;
					$moves[]=$new_move;
					$board->naradcmoves[]=$new_move;
				}
			return $moves;//$moves[]=$new_move;
		}

	static function add_slide_and_slidecapture_moves_to_moves_list(
		array $directions_list,
		int $spaces,
		array $moves,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		bool $store_board_in_moves,
		int $cankill,
		int $canbepromoted,
		bool $get_FullMover,
		bool $selfbrokencastle,
		bool $foebrokencastle,
		int $get_CASTLEMover,
		bool $controlled_move
	): array {

		$ROYALp=FALSE; $candemote=FALSE; $capture = FALSE; $dem=0; $tempDirection=null; 
		$CommonBorderOpen_Status=-1;
		if($board->gametype<=2)
			$mtype=1;//slide //2 jump 
		else 
			$mtype=2;
		$lastaccessiblerow=-1;
		/* $generalaccessiblerow=-1; $enemytrapped=false; $boolslide=TRUE; */
		//Create the Array of Move Types.. This will help in deciding the two types of moves in retrating.. Moving back and to the top border

		/*if($controlled_move==true)
		$ttt=1;

		if(($piece->type==ChessPiece::KING) || ($piece->type==ChessPiece::INVERTEDKING) )
			$debug=1;

		if($piece->type==ChessPiece::GENERAL)
			$debug=1;
	
		if($piece->type==ChessPiece::ROOK)
			$debug=1;
		
		if($piece->type==ChessPiece::KNIGHT)
			$debug=1;

		if($piece->type==ChessPiece::BISHOP)
			$debug=1;
		*/	
		if($piece->type!=ChessPiece::GODMAN)
			{
				$tempDirection=self::get_Retreating_ARMY_directions( $piece, $color_to_move, $board, $mtype	);

				//Retreat or Truce Zone has Either King or General. Check this possibility.
				if (isset($tempDirection) && is_array($tempDirection)){
					$abcd=1;
					if(!empty($tempDirection)) //King is sitting on RestZone within Truce
						{
						$directions_list=$tempDirection;
						}
						$lastaccessiblerow=self::get_LastKing_ArthShashtri_Row( $piece, $color_to_move, $board, $mtype );
				}

				$tempDirection=null;

				if(($piece->square->rank==8)&&($piece->square->file==0)){
					$piece->square->rank;
				}
				//$ROYALp=self::check_ROYAL_neighbours( self::KING_DIRECTIONS, $piece, $color_to_move, $board, "Zone" );


				$ROYALp=$ROYAL_ROYALp=$StartPiece_RoyalTouch=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $piece->square, $piece->square, $color_to_move, $board );

			/*piece is trapped in palace and has no royal help.
			if(  ((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
			||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9))  ))
			{
				if( (($piece->square->rank==0)&&($piece->square->file==4) && ($board->board[0][3]->type==ChessPiece::GENERAL)) ||
				(($piece->square->rank==0)&&($piece->square->file==5) && ($board->board[0][6]->type==ChessPiece::GENERAL))||
				(($piece->square->rank==9)&&($piece->square->file==4) && ($board->board[9][3]->type==ChessPiece::GENERAL))||
				(($piece->square->rank==9)&&($piece->square->file==5) && ($board->board[9][6]->type==ChessPiece::GENERAL))
				){

				}
				else if(($ROYAL_ROYALp==false))
				{
					return $moves;//

				}
			}

			if(  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==4) || ($piece->square->file==5)) )
			||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==4) || ($piece->square->file==5))  ))
			{
				if( (($piece->square->rank==4)&&($piece->square->file==0) && ($board->board[3][0]->type==ChessPiece::GENERAL)) ||
				(($piece->square->rank==5)&&($piece->square->file==0) && ($board->board[6][0]->type==ChessPiece::GENERAL))||
				(($piece->square->rank==4)&&($piece->square->file==9) && ($board->board[3][9]->type==ChessPiece::GENERAL))||
				(($piece->square->rank==5)&&($piece->square->file==9) && ($board->board[6][9]->type==ChessPiece::GENERAL))
				){

				}
				else if(($ROYAL_ROYALp==false))
				{
					return $moves;//

				}
			}
			*/


					//piece is trapped in palace and has no royal help.
					if(  ($piece->type!=ChessPiece::PAWN) && (((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
					||   ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9)))  ))
					{
						$board->board[$piece->square->rank] [$piece->square->file]->awake=false;
		
						if( (($piece->square->rank==0)&&($piece->square->file==4) && ($board->board[0][3]!=null) && (($board->board[0][3]->type==ChessPiece::GENERAL)  || ($board->board[0][3]->group=='ROYAL') ||($board->board[0][3]->group=='SEMIROYAL')) ) ||
						(($piece->square->rank==0)&&($piece->square->file==5) && ($board->board[0][6]!=null) && (($board->board[0][6]->type==ChessPiece::GENERAL) || ($board->board[0][6]->group=='ROYAL') ||($board->board[0][6]->group=='SEMIROYAL')) )||
						(($piece->square->rank==9)&&($piece->square->file==4) && ($board->board[9][3]!=null) && (($board->board[9][3]->type==ChessPiece::GENERAL) || ($board->board[9][3]->group=='ROYAL') ||($board->board[9][3]->group=='SEMIROYAL')) )||
						(($piece->square->rank==9)&&($piece->square->file==5) && ($board->board[9][6]!=null) && (($board->board[9][6]->type==ChessPiece::GENERAL) || ($board->board[9][6]->group=='ROYAL') ||($board->board[9][6]->group=='SEMIROYAL')) ) ||
	
						(($piece->square->file==0)&&($piece->square->rank==4) && ($board->board[3][0]!=null) && (($board->board[3][0]->type==ChessPiece::GENERAL) || ($board->board[3][0]->group=='ROYAL') ||($board->board[3][0]->group=='SEMIROYAL')) ) ||
						(($piece->square->file==0)&&($piece->square->rank==5) && ($board->board[6][0]!=null) && (($board->board[6][0]->type==ChessPiece::GENERAL) || ($board->board[6][0]->group=='ROYAL') ||($board->board[6][0]->group=='SEMIROYAL')) )||
						(($piece->square->file==9)&&($piece->square->rank==4) && ($board->board[3][9]!=null) && (($board->board[3][9]->type==ChessPiece::GENERAL) || ($board->board[3][9]->group=='ROYAL') ||($board->board[3][9]->group=='SEMIROYAL')) )||
						(($piece->square->file==9)&&($piece->square->rank==5) && ($board->board[6][9]!=null) && (($board->board[6][9]->type==ChessPiece::GENERAL) || ($board->board[6][9]->group=='ROYAL') ||($board->board[6][9]->group=='SEMIROYAL')) )
							

						){
							$board->board[$piece->square->rank] [$piece->square->file]->awake=true;
							$ROYAL_ROYALp==true;
						}
						else if(($ROYAL_ROYALp==false))
						{
							return $moves;//
		
						}
					}
		
					else if(  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==4) || ($piece->square->file==5)) )
					||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==4) || ($piece->square->file==5))  ))
					{
		
						//add the logic to check if the warzone already broken palaces. commonborder
						$board->board[$piece->square->rank] [$piece->square->file]->awake=false;
		
						if( ($piece->type!=ChessPiece::PAWN)&&(($piece->square->rank==4)&&($piece->square->file==4) && ( (($board->board[4][3]!==null) && ( ($board->board[4][3]->type==ChessPiece::GENERAL) ||  ($board->board[4][3]->group=='ROYAL') ||($board->board[4][3]->group=='SEMIROYAL') ))||
						(($board->board[3][5]!==null) && ( ($board->board[3][5]->type==ChessPiece::GENERAL) ||  ($board->board[3][5]->group=='ROYAL') ||($board->board[3][5]->group=='SEMIROYAL') ))||
						(($board->board[3][3]!==null) && ( ($board->board[3][3]->type==ChessPiece::GENERAL) ||  ($board->board[3][3]->group=='ROYAL') ||($board->board[3][3]->group=='SEMIROYAL') )) ||
						(($board->board[3][4]!==null) && ( ($board->board[3][4]->type==ChessPiece::GENERAL) ||  ($board->board[3][4]->group=='ROYAL') ||($board->board[3][4]->group=='SEMIROYAL') ))

						)) ||
		
						(($piece->square->rank==4)&&($piece->square->file==5) && ( (($board->board[4][6]!==null) && ( ($board->board[4][6]->type==ChessPiece::GENERAL) ||  ($board->board[4][6]->group=='ROYAL') ||($board->board[4][6]->group=='SEMIROYAL') ))||
						(($board->board[3][6]!==null) && ( ($board->board[3][6]->type==ChessPiece::GENERAL) ||  ($board->board[3][6]->group=='ROYAL') ||($board->board[3][6]->group=='SEMIROYAL') )) ||
						(($board->board[3][5]!==null) && ( ($board->board[3][5]->type==ChessPiece::GENERAL) ||  ($board->board[3][5]->group=='ROYAL') ||($board->board[3][5]->group=='SEMIROYAL') )) ||
						(($board->board[3][4]!==null) && ( ($board->board[3][4]->type==ChessPiece::GENERAL) ||  ($board->board[3][4]->group=='ROYAL') ||($board->board[3][4]->group=='SEMIROYAL') ))
		
						)) ||
		
						(($piece->square->rank==5)&&($piece->square->file==4) && ( (($board->board[5][3]!==null) && ( ($board->board[5][3]->type==ChessPiece::GENERAL) ||  ($board->board[5][3]->group=='ROYAL') ||($board->board[5][3]->group=='SEMIROYAL') ))||
						(($board->board[6][3]!==null) && ( ($board->board[6][3]->type==ChessPiece::GENERAL) ||  ($board->board[6][3]->group=='ROYAL') ||($board->board[6][3]->group=='SEMIROYAL') ))||
						(($board->board[6][4]!==null) && ( ($board->board[6][4]->type==ChessPiece::GENERAL) ||  ($board->board[6][4]->group=='ROYAL') ||($board->board[6][4]->group=='SEMIROYAL') ))||
						(($board->board[6][4]!==null) && ( ($board->board[6][5]->type==ChessPiece::GENERAL) ||  ($board->board[6][5]->group=='ROYAL') ||($board->board[6][5]->group=='SEMIROYAL') ))
		
						)) ||
		
						(($piece->square->rank==5)&&($piece->square->file==5) && ( (($board->board[6][4]!==null) && ( ($board->board[6][4]->type==ChessPiece::GENERAL) ||  ($board->board[6][4]->group=='ROYAL') ||($board->board[6][4]->group=='SEMIROYAL') ))||
						(($board->board[6][5]!==null) && ( ($board->board[6][5]->type==ChessPiece::GENERAL) ||  ($board->board[6][5]->group=='ROYAL') ||($board->board[6][5]->group=='SEMIROYAL') ))||
						(($board->board[6][6]!==null) && ( ($board->board[6][6]->type==ChessPiece::GENERAL) ||  ($board->board[6][6]->group=='ROYAL') ||($board->board[6][6]->group=='SEMIROYAL') ))||
						(($board->board[5][6]!==null) && ( ($board->board[5][6]->type==ChessPiece::GENERAL) ||  ($board->board[5][6]->group=='ROYAL') ||($board->board[5][6]->group=='SEMIROYAL') ))
						))){
							$board->board[$piece->square->rank] [$piece->square->file]->awake=true;
								$ROYAL_ROYALp=true;
						}
						
						if ((( $board->commonborderbreached == true ) && ( $board->CommonBorderOpen_Status==1)) &&
						( ($piece->type!=ChessPiece::PAWN)&&(($piece->square->rank==4)&&($piece->square->file==4) && 
						((($board->board[5][3]!==null) && ( ($board->board[5][3]->type==ChessPiece::GENERAL) ||  ($board->board[5][3]->group=='ROYAL') ||($board->board[5][3]->group=='SEMIROYAL') ))
						)) ||
		
						(($piece->square->rank==4)&&($piece->square->file==5) && 
						( (($board->board[5][6]!==null) && ( ($board->board[4][6]->type==ChessPiece::GENERAL) ||  ($board->board[5][6]->group=='ROYAL') ||($board->board[5][6]->group=='SEMIROYAL') ))		
						)) ||
		
						(($piece->square->rank==5)&&($piece->square->file==4) && 
						( (($board->board[4][3]!==null) && ( ($board->board[4][3]->type==ChessPiece::GENERAL) ||  ($board->board[4][3]->group=='ROYAL') ||($board->board[5][3]->group=='SEMIROYAL') ))		
						)) ||
		
						(($piece->square->rank==5)&&($piece->square->file==5) && 
						( (($board->board[4][6]!==null) && ( ($board->board[4][6]->type==ChessPiece::GENERAL) ||  ($board->board[4][6]->group=='ROYAL') ||($board->board[6][4]->group=='SEMIROYAL') ))
						)))){
							$board->board[$piece->square->rank] [$piece->square->file]->awake=true;
								$ROYAL_ROYALp=true;
						}	
						else if(($ROYAL_ROYALp==false))
						{
							return $moves;//
						}
					}
		

			/* Start new code added for sleepin p[ieces */

			//Pieces inside CASTLE become Semi-Royal for the time-being.
			if(((($piece->square->file>=1) &&($piece->square->file<=3)) || (($piece->square->file>=6) &&($piece->square->file<=8)))  && 
			((($piece->square->rank==0) && ($color_to_move==1)) || ($piece->square->rank==9)  && ($color_to_move==2)))
			{ 
				if($board->isCurrentZoneRoyal==true){
					$ROYALp=$ROYAL_ROYALp=true;
				}
				else 
				$ROYAL_ROYALp=false;
			}
			
			/* End new code added for sleepin p[ieces */


				if(($get_CASTLEMover==1)&&($selfbrokencastle==FALSE))//&&(($board->$blackcanfullmoveinowncastle == 1)||($board->$whitecanfullmoveinowncastle == 1)))
				{
					$ROYALp=true;
					//$booljump=true;
				}
				
				if(($board->gametype>=2)&& (($piece->group=='SEMIROYAL')||($piece->group=='SOLDIER')||(($piece->group=='OFFICER')&&($piece->type!==ChessPiece::GENERAL)))){
	
					$GENERALZONEPUSHER=self::check_general_push_demotion( /**/
						self::KING_DIRECTIONS,
						$piece,
						$color_to_move,
						$board
						);
				}

				if($board->isCurrentZoneRoyal==true){$ROYAL_ROYALp=$ROYALp=true;}

				if(($ROYALp==false)&&( ($piece->group=='OFFICER') ||($piece->group=='SEMIROYAL') ))
					{
						$StartPiece_PromotionalTouch=self::check_general_ROYAL_neighbours_promotion( /**/
							self::KING_DIRECTIONS,
							$piece,
							$color_to_move,
							$board
					);	
				}

			}
			//if Palace is not opponent Castle then good.. otherwise not good
			if((($piece->square->rank>=3)&&($piece->square->rank<=6)) &&(($piece->square->file==0)||($piece->square->file==9)))
			{
				//Truce Palace captured by Opponent
				if(($piece->square->rank==3) && ($piece->color==1) && ( 
					(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==2))
				    ||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==2))
				)){

				}
				else if(($piece->square->rank==6) && ($piece->color==1) && (
					(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==2))
				||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==2))
				)){

				}
				else if(($piece->square->rank==3) && ($piece->color==2) && ( 
					(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==1))
				||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==1))
				)){

				}
				else if(($piece->square->rank==6) && ($piece->color==2) && ( 
					(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==1))
				||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==1))
				)){

				}
				else if(($piece->square->rank==3) && ( 
					(($piece->square->file==0) && ($board->board[4][0]==null))
				||(($piece->square->file==9) && ($board->board[4][9]==null))
				)){

				}
				else if(($piece->square->rank==6) && ( 
					(($piece->square->file==0) && ($board->board[5][0]=null))
				||(($piece->square->file==9) && ($board->board[5][9]!=null))
				)){

				}
				else if(($piece->square->rank==3) && ( 
					(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group=='NOBLE'))
				||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group=='NOBLE'))
				)){

				}
				else if(($piece->square->rank==6) && ( 
					(($piece->square->file==0) &&  ($board->board[5][0]!=null)&&($board->board[5][0]->group=='NOBLE'))
				||(($piece->square->file==9) &&  ($board->board[5][9]!=null)&&($board->board[5][9]->group=='NOBLE'))
				)){

				}				
				else
					{$ROYALp=true;$ROYAL_ROYALp=true;}

			}

			if((($piece->square->rank==0)||($piece->square->rank==9)) &&(($piece->square->file>0)&& ($piece->square->file<9)))
			{
				$ROYAL_ROYALp=true;$ROYALp=true;
			}

		/*else
			{
			$tttt=1;
			}*/

			//Single ROYAL cannot move out of any zone.
			//if(($ROYAL_ROYALp==false)&&(strpos($piece->group,"ROYAL")!==FALSE)&&($piece->square->rank<=9)&&($piece->square->rank>=0)&&(($piece->square->file==0)&&($piece->square->file==9))){
				//return $moves;
			//}

			//self-promotion to be added later for semi-ROYALs
			if(($canbepromoted==1)&&($piece->group=="OFFICER")&&($ROYALp==true) /*&&($piece->square->file>0)&&($piece->square->file<9)*/){ // Check of self promotion can happen but not in TZ without ROYAL
				//if($ROYALp==TRUE) 
					{$dem=-1;}
				//else {$dem=0;}
				if (($board->gametype>=2) && ($piece->type==ChessPiece::GENERAL))
				{
					$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,6);
					$dem=-3;
				}
				else if ($piece->type!==ChessPiece::GENERAL)
					$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,$piece->type-1);
		
				if($canpromote==TRUE){// then update the parity with new demoted values
				//$piece->type=$piece->type+1;
					//Force Promotion to add in movelist	
					$new_move1 = new ChessMove(
						$piece->square, $piece->square,$piece->square,
						0,
						$piece->color, $piece->type, $capture, $board, $store_board_in_moves,
						TRUE,$controlled_move,$CommonBorderOpen_Status
						);
		
					$move2 = clone $new_move1;
					$move2-> set_promotion_piece($piece->type+$dem);					
					$moves[] = $move2;
					}
			}

			 /* Defective Last Row promotion Agastya*/
			if(($piece->group=="SEMIROYAL") &&($ROYALp==true)&&
				(($piece->square->rank==9)&&($piece->square->file>0)&&($piece->square->file<9)&&($piece->color==1)||
				($piece->square->rank==0)&&($piece->square->file>0)&&($piece->square->file<9)&&($piece->color==2))
				){

				$new_move = new ChessMove(
					$piece->square, $piece->square,$piece->square,
					0,
					$piece->color, $piece->type,
					$capture, $board, $store_board_in_moves,
					TRUE,$controlled_move,$CommonBorderOpen_Status
					);

				$canpromote=false;
				$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);

				if(($canpromote==TRUE)&&(($color_to_move==1)&&($piece->square->rank==9)||($color_to_move==2)&&($piece->square->rank==0))){// then update the parity with new demoted values
					$move2 = clone $new_move;
					$move2-> set_promotion_piece(12);
					$moves[] = $move2;
					}
				}
			else /* Semi ROYAL Promotion by King or ROYAL as Apaad Dharm. Kautilya */
			if(($piece->group=="SEMIROYAL") && (($ROYALp==true)||(($piece->square->rank==0) && ($piece->square->file>0) &&($piece->square->file<9) && ($piece->color==1))
			|| (($piece->square->rank==9) && ($piece->square->file>0) &&($piece->square->file<9) && ($piece->color==2)))){
					$new_move = new ChessMove(
						$piece->square, $piece->square,$piece->square,
						0,
						$piece->color, $piece->type,
						$capture, $board, $store_board_in_moves,
						TRUE,$controlled_move,$CommonBorderOpen_Status
						);
	
					$canpromote=false;
					$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
	
					if(($canpromote==TRUE)){// then update the parity with new demoted values
						$move2 = clone $new_move;
						$move2-> set_promotion_piece(12);
						$moves[] = $move2;
						}
				}
			else
			if(($piece->group=="ROYAL") &&( ($piece->type == ChessPiece::KING) || ($piece->type == ChessPiece::INVERTEDKING) ) &&
				(($piece->square->file<4)||($piece->square->file>5))&&($piece->square->rank>=0)&&($piece->square->rank<=9)
				){ 
					$new_move = new ChessMove(
						$piece->square, $piece->square,$piece->square,
						0,
						$piece->color, $piece->type, 
						$capture,$board, $store_board_in_moves,
						TRUE,$controlled_move,$CommonBorderOpen_Status
						);

					$move2 = clone $new_move;
					if(( $piece->type != ChessPiece::INVERTEDKING)){
						$move2-> set_promotion_piece(2);
					}
					else if(( $piece->type != ChessPiece::KING)){
						$move2-> set_promotion_piece(1);
					}
					$moves[] = $move2;
					//return $moves; Dont Return but add more moves
				}
			else
			if(($piece->group=="ROYAL") &&(($piece->type == ChessPiece::KING) || ($piece->type == ChessPiece::INVERTEDKING))&&
				(($piece->square->file==0)||($piece->square->file==9))&&($piece->square->rank>=0)&&($piece->square->rank<=9)
				){ 
					$new_move = new ChessMove(
							$piece->square, $piece->square,$piece->square,
							0,
							$piece->color, $piece->type,
							$capture, $board, $store_board_in_moves,
							TRUE,$controlled_move,$CommonBorderOpen_Status
						);
	
					$move2 = clone $new_move;
					if(( $piece->type != ChessPiece::INVERTEDKING)){
							$move2-> set_promotion_piece(2);
						}
					else if(( $piece->type != ChessPiece::KING)){
							$move2-> set_promotion_piece(1);
						}
					$moves[] = $move2;
						//return $moves; Dont Return but add more moves
				}
			else
			if(($piece->group=="ROYAL") &&(($piece->type == ChessPiece::KING)||($piece->type == ChessPiece::INVERTEDKING)) &&
				(($piece->square->rank==0)&&($piece->square->file==4)&&($piece->color==1)||($piece->square->rank==9)&&($piece->square->file==5)&&($piece->color==2))
				){
					$new_move = new ChessMove(
							$piece->square, $piece->square,$piece->square,
							0,
							$piece->color, $piece->type,
							$capture, $board, $store_board_in_moves,
							TRUE,$controlled_move,$CommonBorderOpen_Status
						);
	
					$move2 = clone $new_move;
					if(( $piece->type != ChessPiece::INVERTEDKING)){
							$move2-> set_promotion_piece(2);
						}
					else if(( $piece->type != ChessPiece::KING)){
							$move2-> set_promotion_piece(1);
						}
						$moves[] = $move2;
				}
			else
			if(($piece->group=="ROYAL") &&(($piece->type == ChessPiece::INVERTEDKING)) &&
				(($piece->square->rank==0)&&($piece->square->file==4)&&($piece->color==1)||($piece->square->rank==9)&&($piece->square->file==5)&&($piece->color==2))
				){ 
					$new_move = new ChessMove(
						$piece->square, $piece->square,$piece->square,
						0,
						$piece->color, $piece->type,
						$capture, $board, $store_board_in_moves,
						TRUE,$controlled_move,$CommonBorderOpen_Status
						);
	
						$move2 = clone $new_move;
						$move2-> set_promotion_piece(1);
						$moves[] = $move2;
				}
			else
			if(($piece->group=="ROYAL") &&( $piece->type == ChessPiece::INVERTEDKING) &&
				(($piece->square->rank==0)&&($piece->square->file!=4)&&($piece->color==1)||($piece->square->rank==9)&&($piece->square->file!=5)&&($piece->color==2))
				){ 
					$new_move = new ChessMove(
						$piece->square, $piece->square,$piece->square,
						0,
						$piece->color, $piece->type,
						$capture, $board, $store_board_in_moves,
						TRUE,$controlled_move,$CommonBorderOpen_Status
						);
	
						$move2 = clone $new_move;
						$move2-> set_promotion_piece(1);
						$moves[] = $move2;
				}
			else
			if(($piece->group=="ROYAL") &&( $piece->type == ChessPiece::KING)&&
			((($piece->square->rank==0)&&($piece->square->file!=4)&&($piece->color==1))||(($piece->square->rank==9)&&($piece->square->file!=5)&&($piece->color==2)))
				){
						$new_move = new ChessMove(
						$piece->square, $piece->square,$piece->square,
						0,
						$piece->color, $piece->type, 
						$capture, $board, $store_board_in_moves,
						TRUE,$controlled_move,$CommonBorderOpen_Status
					);
	
						$move2 = clone $new_move;
						if(( $piece->type != ChessPiece::INVERTEDKING)){
							$move2-> set_promotion_piece(2);
						}
						$moves[] = $move2;
				}
			else			
			if(($piece->group=="ROYAL") &&( $piece->type == ChessPiece::KING)&&
			(($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9))
			){ //add the war zone inversion mode
			
					$new_move = new ChessMove(
						$piece->square, $piece->square,$piece->square,
						0,
						$piece->color, $piece->type,
						$capture, $board, $store_board_in_moves,
						TRUE,$controlled_move,$CommonBorderOpen_Status
					);
	
						$move2 = clone $new_move;
						if(( $piece->type != ChessPiece::INVERTEDKING)){
							$move2-> set_promotion_piece(2);
						}
						$moves[] = $move2;
				}
			else
			if(($piece->group=="ROYAL") &&( $piece->type == ChessPiece::INVERTEDKING)&&
				(($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9))
				){ //add the war zone normal mode option
				
						$new_move = new ChessMove(
							$piece->square, $piece->square,$piece->square,
							0,
							$piece->color, $piece->type,
							$capture, $board, $store_board_in_moves,
							TRUE,$controlled_move,$CommonBorderOpen_Status
						);
		
							$move2 = clone $new_move;
							$move2-> set_promotion_piece(1);
							$moves[] = $move2;
					}
	
			if(((strpos($piece->group,"ROYAL")!==FALSE))&&( //cannot get out of no mans
			((($piece->square->file==0)||($piece->square->file==9))&&(($piece->square->rank==0)||($piece->square->rank==9)))
			)){
				$piece->group;//Stop counting moves as ROYAL is stuck
				
				$new_move = new ChessMove(
					$piece->square, $piece->square,$piece->square,
					0,
					$piece->color, $piece->type,
					$capture, $board, $store_board_in_moves,
					TRUE,$controlled_move,$CommonBorderOpen_Status
				);
				$move2 = clone $new_move;

				if(($piece->group=="ROYAL") &&( $piece->type == ChessPiece::KING)){ //add the war zone inversion mode
							$move2-> set_promotion_piece(2);
							$moves[] = $move2;
							//return $moves; Dont Return but add more moves
				}
				else
				if(($piece->group=="ROYAL") &&( $piece->type == ChessPiece::INVERTEDKING) ){ //add the war zone normal mode option
								$move2-> set_promotion_piece(1);
								$moves[] = $move2;
								//return $moves; Dont Return but add more moves
				}
				///continue;
			}
			else
			{
				if($get_FullMover==FALSE) 
					$spaces=1;

				//Function to check if anyone can getout of the PALACE. Create a proper Function.
				/*if(  ((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
					||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9))  ))
					{
						$allowed = self::get_CommonBorderOpen_Status(	$piece, $color_to_move, $board );			
						if($allowed==false){
							$spaces=0;	
								}
					}
				*/
				$ending_square=null;
				if($spaces>1)
					$ROYALp=false;
				foreach ( $directions_list as $direction ) {
					if($CommonBorderOpen_Status==1){
						$CommonBorderOpen_Status=-1;
					}	
						for ( $i = 1; $i <= $spaces; $i++ ) {
	
							
								$current_xy = self::DIRECTION_OFFSETS[$direction];
								$current_xy[0] *= $i;
								$current_xy[1] *= $i;
								$type=0;
							
								$ending_square = self::square_exists_and_not_occupied_by_friendly_piece(
									$type,
									'0',
									$piece->square, $current_xy[0], $current_xy[1], $color_to_move,
									$board, $cankill, $get_FullMover, $selfbrokencastle, $foebrokencastle
								);
							
								$capture = FALSE;
							
								if ( ! $ending_square ) {
									// square does not exist, or square occupied by friendly piece
									// stop sliding
									break;
									}

									if(($CommonBorderOpen_Status==1) && ($ending_square!=null) &&  ($board->board[$ending_square->rank][$ending_square->file])){
										$CommonBorderOpen_Status=-1;
										break;
									}
		
									if (($CommonBorderOpen_Status==1)&&  ($ending_square!=null) && ($board->board[$ending_square->rank][$ending_square->file]==null) &&($ending_square->rank-$piece->square->rank>=2))  {
										$CommonBorderOpen_Status=1;
										break;
									}	

									
								/*if( (($selfbrokencastle==true)&&( $piece->square->rank==9)&&($ending_square->rank<8)&&($color_to_move==2)||
								($foebrokencastle==true)&&($ending_square->rank>1)&&($piece->square->rank==0)&&($color_to_move==2))||  
								(($selfbrokencastle==true)&&( $piece->square->rank==0)&&($ending_square->rank>1)&&($color_to_move==1)||
								($foebrokencastle==true)&&($ending_square->rank<8)&&($piece->square->rank==9)&&($color_to_move==1)))
								{ 
									break;
								}
								*/
							
								//Spies/Royals and General is required for killing allowed.. Knight logic and General Logic needs to be corrected
								//Spies/Royals and General is required for killing allowed.. Knight logic and General Logic needs to be corrected
								if((($piece->group=="SOLDIER") || ($piece->group=="OFFICER")) && ($piece->color==1) && ($board->arewcommaderssleeping==true)){
										$cankill=0;
									}
								if((($piece->group=="SOLDIER") || ($piece->group=="OFFICER")) && ($piece->color==2) && ($board->arebcommaderssleeping==true)){
										$cankill=0;
									}

							if(($board->refugee!=null) && 
							(($board->refugee->square!=null)&& ($board->refugee->square->rank==$ending_square->rank) && ($board->refugee->square->file==$ending_square->file)))
							{
								continue;
							}
						
							if(($lastaccessiblerow!=-1)&&($color_to_move==2)&&($ending_square->rank<$lastaccessiblerow)){
								continue;
							}
						
							if(($lastaccessiblerow!=-1)&&($color_to_move==1)&&($ending_square->rank>$lastaccessiblerow)){
								continue;
							}
						
							$endpiece=null;
					
							//check if ending piece is crossing border without Royal.. Border shoud be broken
							//PENDING in Jan 2022 Code
							/*

							if(  ((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
							||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9))  ))
							{
								$allowed = self::get_CommonBorderOpen_Status(	$piece, $color_to_move, $board );			
								if($allowed==false){
									$spaces=0;	
										}
							}
							*/

							/* Check if border breached */
							$CommonBorderOpen_Status = self::get_CommonBorderOpen_Status(	$piece, $color_to_move, $board,$ending_square );
							if(($CommonBorderOpen_Status==0)){
									if (  ($StartPiece_PromotionalTouch==false)&& ($ROYAL_ROYALp==false) && ($piece->type!=ChessPiece::GODMAN) ){
											break;
									}
									else if ((($ROYAL_ROYALp==true) ||  ($StartPiece_PromotionalTouch==true)) && ($piece->type!=ChessPiece::GODMAN) ){
										if ($board->board[$ending_square->rank][$ending_square->file]) {
											$CommonBorderOpen_Status=1;
											break;
										}

										if (($board->board[$ending_square->rank][$ending_square->file]==null) &&(abs($ending_square->rank-$piece->square->rank)>=1) 
										&&($ending_square->file!=$piece->square->file)&& ($piece->square->file>0)&&($piece->square->file<9))  {
											$CommonBorderOpen_Status=1;
											if( (($ending_square->rank==5)&&($piece->square->rank<5)) || (($ending_square->rank==4)&&($piece->square->rank>4)))
											{}
											else
												break;
										}

										$CommonBorderOpen_Status=1;
									}
									else if (($piece->type==ChessPiece::GODMAN)){
											$CommonBorderOpen_Status=0;					
										}
								}

							/* Check if border breached */

							if ($board->board[$ending_square->rank][$ending_square->file]) {
								if ($board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move) {
									if((($piece->group=='OFFICER')) && ($piece->type<=$board->board[$ending_square->rank][$ending_square->file]->type) && 
									(($board->board[$ending_square->rank][$ending_square->file]->group=='OFFICER') ||($board->board[$ending_square->rank][$ending_square->file]->group=='SEMIROYAL')|| ($board->board[$ending_square->rank][$ending_square->file]->group=='SOLDIER')))
										$capture = true;
									else if((($piece->group=='ROYAL')) && ($piece->type<=$board->board[$ending_square->rank][$ending_square->file]->type) && 
										(($board->board[$ending_square->rank][$ending_square->file]->group=='ROYAL') ||($board->board[$ending_square->rank][$ending_square->file]->group=='SEMIROYAL') 
										||($board->board[$ending_square->rank][$ending_square->file]->group=='OFFICER') || ($board->board[$ending_square->rank][$ending_square->file]->group=='SOLDIER')))
											$capture = true;
									else if((($piece->group=='OFFICER')) && ($piece->type>$board->board[$ending_square->rank][$ending_square->file]->type) && 
										(($board->board[$ending_square->rank][$ending_square->file]->group=='OFFICER') || ($board->board[$ending_square->rank][$ending_square->file]->group=='SEMIROYAL')||($board->board[$ending_square->rank][$ending_square->file]->group=='ROYAL')))
											{//$capture = false; continue;
											
											if( ($ending_square->mediatorrank!=null)&&($ending_square->mediatorfile!=null)){
												$mediatorpiece = clone $piece;
												$endpiece = clone $board->board[$ending_square->rank][$ending_square->file];
											
												if(($piece->square->mediatorrank!=$ending_square->mediatorrank)&&($piece->square->mediatorfile!=$ending_square->mediatorfile)){
													$mediatorpiece->square->mediatorrank=$ending_square->mediatorrank;
													$mediatorpiece->square->mediatorfile=$ending_square->mediatorfile;
													$mediatorpiece->state="V";
													}
												//$sittingpiece=$board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file];
												$board1 = clone $board;
												$board1->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]=$mediatorpiece;
												if($ROYAL_ROYALp==true)
													$mediatorpiece->elevatedofficer=true;
												else $mediatorpiece->elevatedofficer=false;
												
												if($i>=2){
													$moves = self::add_running_capture_moves_to_moves_list($moves, $mediatorpiece, $endpiece, $color_to_move, $board1, $store_board_in_moves,1,$selfbrokencastle,$foebrokencastle,$CommonBorderOpen_Status);
													break;
													}
												}/*
										else {
											$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, -1, $piece->color, $piece->type, $capture, $board, $store_board_in_moves, false);
											//$move2 = clone $new_move1;
											//$moves[] = 	$move2;
											$move2 = clone $new_move;
									$move2->set_demotion_piece($piece->type+$dem);
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
										$move2->set_killed_king(TRUE);
									$moves[] = $move2;
											}
											*/
											break;
											}
										
									//if((($piece->group=='OFFICER')) && (($enemytrapped==true)||($capture==true)))
									//	{ $capture = true;$enemytrapped=false; }
									//else if(($piece->group=='OFFICER') && ($enemytrapped==false))
									//	{ continue; }
									}
							}
						
							//movement within the opponent castle
							if(($piece->group=="SEMIROYAL") &&($ROYALp==true) && (($piece->square->rank==$ending_square->rank))&&((($ending_square->rank==0) &&($color_to_move==2))||(($ending_square->rank==9)&&($color_to_move==1)))&&(
								(($ending_square->file>0)&&($ending_square->file<9))
								)&& ($board->board[$ending_square->rank][$ending_square->file]==null)  ){ // Check of promotion can happen
								
									if($piece->group=="SEMIROYAL"){
										$new_move = new ChessMove(
											$piece->square, $ending_square,$ending_square,
											0,
											$piece->color, $piece->type, $capture,
											$board, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status
											);
										
										$moves[] = $new_move;
										$canpromote=false;
										$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
										
										if($canpromote==TRUE){
											$move2 = clone $new_move;
											$move2-> set_promotion_piece(12);
											//check if the king is killed
											if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
													$move2->set_killed_king(TRUE);
											$moves[] = $move2;
											}
										
										if((($foebrokencastle==true)&&((($ending_square->rank==9)&&($color_to_move==1))||(($ending_square->rank==0)&&($color_to_move==2)))) &&(($ending_square->file==4)||($ending_square->file==5)))
											{ 
											continue;
											}
										}
							}
						
									//***  Self Compromised CASTLE movement in and out without ROYAL. 2 steps are not allowed */
									if((($piece->group=="SEMIROYAL")||($piece->group=="ROYAL"))&&($ROYAL_ROYALp==false)&&($selfbrokencastle==TRUE)&&
									(((abs($ending_square->file-$piece->square->file)>1)&&(($ending_square->file>=0)&&($ending_square->file<=9))) ||
									((abs($ending_square->rank-$piece->square->rank)>1)&&(($ending_square->file>=0)&&($ending_square->file<=9)))))
										{
										continue;
										}									
							
									//classical cannot allow Soldiers to move from Normal CAStle to Truce. Only General is allowed. But General can push Officrs from compromised CasTLE.
									if((($piece->group=="SOLDIER"))&&
									(  (($ending_square->file==0)||($ending_square->file==9)) &&(($piece->square->rank==0)||($piece->square->rank==9))&&
									 (($ending_square->rank==1)||($ending_square->rank==8)) &&(($piece->square->file>0)&&($piece->square->file<9)) ))
									{
										break;
									}
								
									if( $board->board[$ending_square->rank][$ending_square->file]!=null ){
										if ( $board->board[$ending_square->rank][$ending_square->file] ) {
											if (( $board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move)) {
												if((($ending_square->rank==0)&& ($ending_square->file==0))||(($ending_square->rank==0)&& ($ending_square->file==9))||
												(($ending_square->rank==9)&& ($ending_square->file==0))||(($ending_square->rank==9)&& ($ending_square->file==9))){
													$capture = FALSE;
													break; /** ????? */
													}
												
												//Classical Game. Officers cannot kill in compromised zone or Truce when ArthShashtri is also in idle condition
												if(((strpos($piece->group,"ROYAL")===FALSE))&&(
								 				(($color_to_move==2)&& (($board->basquare==null) ||  ((($board->basquare->file==5)||($board->basquare->file==4)) &&(($board->basquare->rank==9))))||
								 				((($board->basquare->file==5)||($board->basquare->file==4)) &&(($board->basquare->rank==9)))||
								 				((($board->basquare->file==0)||($board->basquare->file==9)) &&(($board->basquare->rank==4)||($board->basquare->rank==5))) ||

								 				((($board->basquare->file==5)||($board->basquare->file==4)) &&(($board->basquare->rank==4)))||
								 				((($board->basquare->file==5)||($board->basquare->file==4)) &&(($board->basquare->rank==5)))||
								 				((($board->basquare->file==4)||($board->basquare->file==5)) &&(($board->basquare->rank==4)||($board->basquare->rank==5)))												 
												 ) ||
												
												 ( ($color_to_move==1) && (($board->wasquare==null) || ((($board->wasquare->file==5)||($board->wasquare->file==4)) &&(($board->wasquare->rank==0))))||
								 				((($board->wasquare->file==5)||($board->wasquare->file==4)) &&(($board->wasquare->rank==0)))||
								 				((($board->wasquare->file==0)||($board->wasquare->file==9)) &&(($board->wasquare->rank==4)||($board->wasquare->rank==5))) ||
								 				
												 ((($board->wasquare->file==5)||($board->wasquare->file==4)) &&(($board->wasquare->rank==4)))||
												 ((($board->wasquare->file==5)||($board->wasquare->file==4)) &&(($board->wasquare->rank==5)))||
								 				((($board->wasquare->file==4)||($board->wasquare->file==5)) &&(($board->wasquare->rank==4)||($board->wasquare->rank==5))) 

								 				)))
													{
														break; /*continue*/
													}
												
													
												//Truce Zone guys cannot be killed
												if(($ending_square->rank>=0)&& ($ending_square->rank<=9)&&(($ending_square->file==0)||
												($ending_square->file==9))){
													break; /** Cannot Kill anyone in TruceZone */
												}
											}
										
										if(($capture == TRUE)&&($board->board[$ending_square->rank][$ending_square->file]->mortal!=1)){
											continue; /** Only Mortals can be killed */
										}
									}
									$capture = true;
								}
								else 
									$capture = false;
							
									//ROYALs moving from War to CASTLE with ROYAL touch
									if((strpos($piece->group,"ROYAL")!==FALSE) && ($ROYALp)&&($piece->square->file>1)&&($piece->square->file<9)&&($piece->square->rank>1)&&($piece->square->rank<9)&&(($ending_square->file>0)&&($ending_square->file<9)&&(($ending_square->rank==0)||($ending_square->rank==9)))) {
											if ( $board->board[$ending_square->rank][$ending_square->file] ==null) {
												if(($piece->type == ChessPiece::SPY)||($piece->type == ChessPiece::ARTHSHASTRI)||($piece->type == ChessPiece::KING)||( $piece->type == ChessPiece::INVERTEDKING)){
													if(($ending_square->rank==0)||($ending_square->rank==9)){
													
														$new_move = new ChessMove( $piece->square, $ending_square, $ending_square, 0, $piece->color, $piece->type, $capture, $board, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status );
														if($piece->group=="SEMIROYAL"){
															//Trying to enter the Opponent CASTLE
															$move1 = clone $new_move;
															if((($ending_square->rank==9)&&($color_to_move==1))||(($ending_square->rank==0)&&($color_to_move==2))){
																$canpromote=false;
																$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
															
																if($canpromote==TRUE){
																	$move2 = clone $new_move;
																	$move2-> set_promotion_piece(12);
																	$moves[] = $move2;
																	}
																
																//if((($foebrokencastle==true)&&((($ending_square->rank==9)&&($color_to_move==1))||(($ending_square->rank==0)&&($color_to_move==2)))) &&(($ending_square->file==4)||($ending_square->file==5)))
																	//{ 
																	//continue;
																	//}
																}
																$moves[] = $move1;
																continue;
															}
														//If ROYALs are entering	
														else if(($piece->group=="ROYAL")&&((($ending_square->rank==9)&&($color_to_move==1))||(($ending_square->rank==0)&&($color_to_move==2)))){
															$move2 = clone $new_move;
															if(($piece->type == ChessPiece::ARTHSHASTRI))
																	$move2-> set_promotion_piece(50);
															else
																$move2-> set_promotion_piece(100);

															$moves[] = $move2;
															//return $moves; Dont Return but add more moves
															continue;
															}
														//If ROYALs are entering in its own castle
														else if(($piece->group=="ROYAL")&&((($ending_square->rank==9)&&($color_to_move==2))||(($ending_square->rank==0)&&($color_to_move==1)))){
															$move2 = clone $new_move;
															//$moves[] = $new_move;
														//check if the king is killed
														if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
															$move2->set_killed_king(TRUE);
														if(( $piece->type == ChessPiece::ARTHSHASTRI)&&($ending_square->file!=4)&&($ending_square->file!=5)){
															$moves[] = $move2;
														}
														elseif((( $piece->type == ChessPiece::KING)||( $piece->type == ChessPiece::INVERTEDKING))&&($ending_square->file!=4)&&($ending_square->file!=5)){ 
															$moves[] = $move2;
															$move3 = clone $new_move;
															$move3-> set_promotion_piece(2);
															//check if the king is killed
															if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																$move3->set_killed_king(TRUE);
															$moves[] = $move3;
															}
														elseif(($ending_square->file==4)||($ending_square->file==5)){
															if(($piece->type == ChessPiece::KING)){
																$moves[] = $move2;
																$move3 = clone $new_move;
																$move3-> set_promotion_piece(2);
																$moves[] = $move3;
																}
															else if(($piece->type == ChessPiece::INVERTEDKING)){
																	$moves[] = $move2;
																	$move3 = clone $new_move;
																	$move3-> set_promotion_piece(1);
																	$moves[] = $move3;
																	}
															else if(($piece->type == ChessPiece::ARTHSHASTRI))
																$moves[] = $move2;
																
															/*if(($piece->type == ChessPiece::ARTHSHASTRI)){
																$move2-> set_promotion_piece(5);
																$moves[] = $move2;
																}
															*/
															/*
															if(($piece->type == ChessPiece::KING)){
																	//$move2-> set_promotion_piece(1);
																	$moves[] = $move2;
																	$move3 = clone $new_move;
																	$move3-> set_promotion_piece(2);
																	$moves[] = $move3;
																	//check if the king is killed
																	if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																		$move3->set_killed_king(TRUE);
																}*/
															}
														elseif(($ending_square->file<4)||($ending_square->file>5)){ 
															if(($piece->type == ChessPiece::KING)){
																//$move2-> set_promotion_piece(1);
																$moves[] = $move2;
																$move3 = clone $new_move;
																$move3-> set_promotion_piece(2);
																$moves[] = $move3;
																}
															
															else if(($piece->type == ChessPiece::ARTHSHASTRI))
																$moves[] = $move2;

															/*if(($piece->type == ChessPiece::ARTHSHASTRI)){
																$move2-> set_promotion_piece(6);
																$moves[] = $move2;
																}
																*/
															
															else if(($piece->type == ChessPiece::INVERTEDKING)){
																$moves[] = $move2;
																$move3 = clone $new_move;
																$move3-> set_promotion_piece(1);
																$moves[] = $move3;
																}
															}
														continue;
															}
														else 
															continue; /** Cannot get inside CASTLE piece */
													}
												}											
											}
										//Ending CASTLE has value so WAR ppl cannt enter
										elseif (($piece->square->rank>0)&&($piece->square->rank<9))
											continue;
										}
									
									//single ROYAL in CASTLE trying to move to No mans
									elseif(((strpos($piece->group,"ROYAL")!==FALSE)&&($ROYALp==false))&&
										((($piece->square->file>0)&&($piece->square->file<9)&&($piece->square->rank==0) &&
											($ending_square->rank==0)&&(($ending_square->file==0)||($ending_square->file==9)))||
										(($piece->square->file>0)&&($piece->square->file<9)&&($piece->square->rank==9) &&
										($ending_square->rank==9)&&(($ending_square->file==0)||($ending_square->file==9))) 
										)){
											$piece->group;//Stop counting moves as single ROYAL cannot move from castle to no mans
											continue;
										}
										//single ROYAL in Normal TRUCE trying to enter to WAR
										else if(((strpos($piece->group,"ROYAL")!==FALSE)&&($ROYAL_ROYALp==false))&&
											((($piece->square->rank<3)&&($piece->square->rank>6)&&($piece->square->file==0))||
											(($piece->square->rank<3)&&($piece->square->rank>6)&&($piece->square->file==9)))){
											$piece->group;//Stop counting moves as ROYAL is alone in truce and cannot move to war
											continue;
										}
										//single ROYAL in WAR trying to enter to CASTLE or no mans
										else if((strpos($piece->group,"ROYAL")!==FALSE)&&($ROYAL_ROYALp==false)&&
											($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9)&&
											((($ending_square->rank==0) &&($selfbrokencastle==false)&&($color_to_move==1))||(($ending_square->rank==9) &&($foebrokencastle==false)&&($color_to_move==1))||
											(($ending_square->rank==9) &&($selfbrokencastle==false)&&($color_to_move==2))||(($ending_square->rank==0) &&($foebrokencastle==false)&&($color_to_move==2))
											||($ending_square->file==0)||($ending_square->file==9)))
											{
											continue;
										}
									
										if($ROYALp==TRUE){ 
											/*We can also add the promotion logic*/
											if($board->board[$piece->square->rank][$piece->square->file]==null)
												{/*$ttt=1;*/}
											else if((($board->board[$piece->square->rank][$piece->square->file]->group=='ROYAL')||($board->board[$piece->square->rank][$piece->square->file]->group=='SEMIROYAL'))&&
											((($ending_square->file==0)&&($piece->square->file==0)&&($ending_square->rank==0)&&($piece->square->rank>0)&&($piece->square->rank<9))||
											(($ending_square->file==9)&&($piece->square->file==9)&&($ending_square->rank==0)&&($piece->square->rank>0)&&($piece->square->rank<9))||
											(($ending_square->file==0)&&($piece->square->file==0)&&($ending_square->rank==9)&&($piece->square->rank>0)&&($piece->square->rank<9))||
											(($ending_square->file==9)&&($piece->square->file==9)&&($ending_square->rank==9)&&($piece->square->rank>0)&&($piece->square->rank<9))
											)){
												$ROYALp=$ROYALp;
											}
										}
										else
										{
											//only RajRrishi has the right to enter these places
										
											/*// More than 2 rank jump not allwed rom compromised castle
											if(($selfbrokencastle==true)&&($piece->square->rank==0)&&($ending_square->rank>1)&&($color_to_move==1)||
											($foebrokencastle==true)&&($piece->square->rank==9)&&($ending_square->rank<8)&&($color_to_move==1))
											{ 
											continue;
											}
											else*/if(($selfbrokencastle==true)&&($piece->square->rank==0)&&($ending_square->rank>4)&&($color_to_move==2)||
											($foebrokencastle==true)&&($piece->square->rank==9)&&($ending_square->rank<5)&&($color_to_move==2))
											{ /* More than 4 rank jump not allwed from compromised castle*/
											continue;
											}
											elseif(($selfbrokencastle==true)&&($ending_square->rank==0)&&($color_to_move==1)||
											($foebrokencastle==true)&&($ending_square->rank==9)&&($color_to_move==1))
											{ /*
											* CASTLE has become warzone
											*/
											}
											elseif(($selfbrokencastle==true)&&($ending_square->rank==9)&&($color_to_move==2)||
											($foebrokencastle==true)&&($ending_square->rank==0)&&($color_to_move==2))
											{ /*
											* CASTLE has become warzone
											*/
											
											}
											elseif((($ending_square->rank==0) &&($ending_square->file>0)&&($ending_square->file<9))||(($ending_square->rank==9) &&($ending_square->file>0)&&($ending_square->file<9))){

												
											if(($piece->group=="OFFICER") &&($board->board[$ending_square->rank][$ending_square->file]==null)&&
												((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->file>=0)&&($ending_square->file<=9))&&
												((($ending_square->rank==0)||($ending_square->rank==9))) && (($StartPiece_RoyalTouch==true)&&($board->gametype>=1)) ) ){
													///	* Classical General can penetrate the CASTLE. //
													$board1= clone $board;
	
													if(($CommonBorderOpen_Status == 1)){
														$board1->commonborderbreached=true;
													}
													
													if(($ending_square->rank==0) && ($piece->color==2)){
														$board1->wbrokencastle=true;
													}
													else if(($ending_square->rank==9) && ($piece->color==1)){
														$board1->bbrokencastle=true;
													}
	
													$new_move = new ChessMove($piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status);
													$moves[] = $new_move;
													break;
													}
												
												//if there is no surrounding ROYALs then break. Create function here.
												//if(($piece->group!=='ROYAL')&&($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9))			
											}
										}
									
										if ( $board->board[$ending_square->rank][$ending_square->file] ) {
											if (( $board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move)) {
												$capture = TRUE;
												if((($ending_square->rank==0)&& ($ending_square->file==0)||($ending_square->rank==0)&& ($ending_square->file==9))||
												(($ending_square->rank==9)&& ($ending_square->file==0))||(($ending_square->rank==9)&& ($ending_square->file==9))){
													continue; /** no mans already has a piece */
												}
											
												/** Cannot kill out of the normal castle */
												if( (($selfbrokencastle==false)&&( $piece->square->rank==9)&&($ending_square->rank<=8)&&($color_to_move==2)||
												($foebrokencastle==false)&&($ending_square->rank>=1)&&($piece->square->rank==0)&&($color_to_move==2))||  
												(($selfbrokencastle==false)&&( $piece->square->rank==0)&&($ending_square->rank>=1)&&($color_to_move==1)||
												($foebrokencastle==false)&&($ending_square->rank<=8)&&($piece->square->rank==9)&&($color_to_move==1)))
												{ 
												break;
												}
											
						 						/** Cannot kill out of Truce zone */
												if((($ending_square->rank>=1)&& ($ending_square->file>=1)&&($ending_square->rank<=8)&& ($ending_square->file<=8))&&
												(($piece->square->rank>0)&&($piece->square->rank<9))&&(($piece->square->file==9)|| ($piece->square->file==0))){
													break;
												}
											
						 						/** Cannot kill out of No Mans */
												 if((($ending_square->rank>=0)&& ($ending_square->file>=1)&&($ending_square->rank<=9)&& ($ending_square->file<=8))&&
												 (($piece->square->rank==0)||($piece->square->rank==9))&&(($piece->square->file==9)|| ($piece->square->file==0))){
													 break;
												 }
						 						/** Cannot kill inside No Mans */
												 if((($ending_square->rank==0)|| ($ending_square->rank==9)) && (($ending_square->file==0)||($ending_square->file==9))){
													 break;
												 }
											 
												if(($ending_square->rank>=0)&& ($ending_square->rank<=9)&&(($ending_square->file==0)||
												($ending_square->file==9))){
													continue; /** Cannot Kill anyone in TruceZone */
												}
												//else
												//$capture = FALSE;
											}
										
											if(($capture == TRUE)&&($board->board[$ending_square->rank][$ending_square->file]->mortal!=1)){
												continue; /** Only Mortals can be killed */
											}
										
											//Check for intermediate square and put it as it is from TRuce to WAR
											if((($piece->square->file==0)&&($piece->square->rank>0)&&($piece->square->rank<9))||
											(($piece->square->file==9)&&($piece->square->rank>0)&&($piece->square->rank<9))){
													if($piece->square->file==0){
														$ending_square->file=1;
														$cancapture=FALSE;
													}
													elseif($piece->square->file==8){
														$ending_square->file=7;
														$cancapture=FALSE;
													}
													else{//invalid slides..
														continue;
													}
											}
										}
										else
										{
											if((($piece->square->file==0)&&($piece->square->rank>0)&&($piece->square->rank<9)&&($ROYALp==False))||
											(($piece->square->file==9)&&($piece->square->rank>0)&&($piece->square->rank<9)&&($ROYALp==False))||
											(($piece->square->rank==0)&&($piece->square->file>0)&&($piece->square->file<9)&&($ROYALp==False))||
											(($piece->square->rank==9)&&($piece->square->file>0)&&($piece->square->file<9)&&($ROYALp==False))											
											){
													$ROYALp=self::check_general_ROYAL_neighbours_promotion( /**/
														self::KING_DIRECTIONS,
														$piece,
														$color_to_move,
														$board
													);
												if(($ROYALp==false) && (($piece->group=='SOLDIER')||($piece->group=='OFFICER')))
													continue;
												else if(($ROYALp==false) && (($piece->group=='ROYAL')||($piece->group=='SEMIROYAL')))
													{														
														if( (($piece->square->file==0) && ($ending_square->file>=1)) ||
														(($piece->square->file==9) && ($ending_square->file<=8)) ||
														(($piece->square->rank==0) && ($ending_square->rank>=1)) ||
														(($piece->square->rank==9) && ($ending_square->rank<=8)) ||

														(($ending_square->rank==0) && ($ending_square->file==0)) ||
														(($ending_square->rank==0) && ($ending_square->file==9)) ||
														(($ending_square->rank==9) && ($ending_square->file==0)) ||
														(($ending_square->rank==9) && ($ending_square->file==9))
														
														){
															break;
														}	
													};
											}
										}
									
										//ArthShashtri and spy cannot kill.. Also if King is idle then also cannot kill
										if(($cankill==0)&&($capture))
											break; 
										$ksqr=$board->get_king_square(abs($color_to_move-3));
									
										if($ksqr==null) continue;
										if(($capture==TRUE)&&
										(($board->get_king_square(abs($color_to_move-3))->rank==0)&&(($board->get_king_square(abs($color_to_move-3))->file==4)||($board->get_king_square(abs($color_to_move-3))->file==5))&&($color_to_move==1)
										||($board->get_king_square(abs($color_to_move-3))->rank==9)&&(($board->get_king_square(abs($color_to_move-3))->file==4)||($board->get_king_square(abs($color_to_move-3))->file==5))&&($color_to_move==2)))
										{
											if(($piece->type==1)&&($piece->square->rank>0)&&($piece->square->rank<9))
												break; 	//If King is holding scepter then no Capture Allowed
										}
									
										//Kautilya Demotion of Officers
										if(($piece->group=="OFFICER") &&(($ending_square->file==0)||($ending_square->file==9))&&(
											(($ending_square->rank>=0)&&($ending_square->rank<=9))
											)&&(($ROYALp==FALSE)&&($board->gametype>=2))&&($GENERALZONEPUSHER==true)){ // Check of demotion can happen in Truce or No Mans as per Parity
											
												//if(($piece->group=='SEMIROYAL')||($piece->group=='SOLDIER')||($piece->group=='OFFICER')&&($piece->type!==ChessPiece::GENERAL))

												if($piece->type==12)  $dem=-5; 
												else $dem=1;
												$candemote=$board->checkdemotionparity( $board->export_fen(), $piece,$color_to_move,$board);
												$CommonBorderOpen_Status = self::get_CommonBorderOpen_Status(	$piece, $color_to_move, $board,$ending_square );

												if($candemote==TRUE){// then update the parity with new demoted values
												//$piece->type=$piece->type+1;
												
													$new_move = new ChessMove(
														$piece->square,	$ending_square,$ending_square,
														0,
														$piece->color, $piece->type, 
														$capture, $board, $store_board_in_moves,
														FALSE,$controlled_move,$CommonBorderOpen_Status
														);
													
														if(($capture==true) && ($ending_square->mediatorrank!=null)&&($ending_square->mediatorfile!=null)){
															$mediatorpiece = clone $piece;
															$endpiece = clone $board->board[$ending_square->rank][$ending_square->file];
														
															if(($piece->square->mediatorrank!=$ending_square->mediatorrank)&&($piece->square->mediatorfile!=$ending_square->mediatorfile)){
																$mediatorpiece->square->mediatorrank=$ending_square->mediatorrank;
																$mediatorpiece->square->mediatorfile=$ending_square->mediatorfile;
																$mediatorpiece->state="V";
																}
															$sittingpiece=$board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file];
															$board1 = clone $board;
															$board1->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]=$mediatorpiece;
															if($i>=2){
																$moves = self::add_running_capture_moves_to_moves_list($moves, $mediatorpiece, $endpiece, $color_to_move, $board1, $store_board_in_moves,1,$selfbrokencastle,$foebrokencastle,$CommonBorderOpen_Status);
																break;
																}
															}
														else {
															$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, -1, $piece->color, $piece->type, $capture, $board, $store_board_in_moves, false,$controlled_move,$CommonBorderOpen_Status);
															//$move2 = clone $new_move1;
															//$moves[] = 	$move2;
															$move2 = clone $new_move;
															$move2->set_demotion_piece($piece->type+$dem);
															//check if the king is killed
															if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																$move2->set_killed_king(TRUE);
															$moves[] = $move2;
															}
													continue;
													}
											}

										//Kautilya wintin Truce movement
										if(($piece->group=="SEMIROYAL") &&((($ending_square->file==0)&&($piece->square->file==0))||(($piece->square->file==9)&&($ending_square->file==9)))&&(
											(($ending_square->rank>=0)&&($ending_square->rank<=9) && ($piece->square->rank>=0)&&($piece->square->rank<=9) )
											)&&(($board->gametype>=2))&&($GENERALZONEPUSHER==true)){ // Check of demotion can happen in Truce or No Mans as per Parity
													$new_move = new ChessMove(
															$piece->square,	$ending_square,$ending_square, 0,
															$piece->color, $piece->type, $capture, $board, $store_board_in_moves,
															FALSE,$controlled_move,$CommonBorderOpen_Status );
													$moves[] = $new_move;		

													$skipxy=$piece->square;
													$dROYALp=self::has_ROYAL_neighbours(  self::KING_DIRECTIONS, $skipxy, $ending_square, $color_to_move, $board );
	
													$targetpiece=clone $piece;
													$targetpiece->square->file=	$ending_square->file;
													$targetpiece->square->rank=	$ending_square->rank;
													//endpiece in truce is Palace or surrounding palace the also promotion
	
													$dgeneralp=self::check_general_ROYAL_neighbours_promotion( self::KING_DIRECTIONS, $targetpiece, $color_to_move, $board );
													// Check of destination promotion can happen
													if(($canbepromoted==1)&&(($dROYALp==TRUE)||($dgeneralp==TRUE)))
														{ 
															$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
															if(($canpromote==TRUE)&& ($canbepromoted==1)){// then update the parity with new demoted values
																	$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status );
																	$move3 = clone $new_move1;
																	//check if the king is killed
																	if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																		$move3->set_killed_king(TRUE);
																	$move3-> set_promotion_piece($piece->type+$dem);
	
																	//check if the king is killed
																	if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																			$move3->set_killed_king(TRUE);
																	$moves[] = $move3;
															}
														}
	






													if($ROYALp==FALSE){
															break;
														}
													else if($ROYALp==true){
														$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
														if(($canpromote==TRUE)){// then update the parity with new ptomoted values
															//Force Promotion to add in movelist
															$new_move1 = new ChessMove(
																$piece->square, $ending_square,$ending_square, 0, 	$piece->color, $piece->type,
																$capture, $board, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status );
												
																$move3 = clone $new_move1;
																$move3-> set_promotion_piece(12);
																$moves[] = $move3;
															}
														continue;
													}
										}

										//Kautilya No Impact in SemiRoyal when pushed by General in LEft or Right Columns
										if(($piece->group=="SEMIROYAL") &&(($ending_square->file==0)||($ending_square->file==9))&&(
											(($ending_square->rank>=0)&&($ending_square->rank<=9))
											)&&(($board->gametype>=2))&&($GENERALZONEPUSHER==true)){ // Check of demotion can happen in Truce or No Mans as per Parity
												
												if($ROYALp==FALSE){
													$new_move = new ChessMove(
														$piece->square,	$ending_square,$ending_square,
														0,
														$piece->color, $piece->type, 
														$capture, $board, $store_board_in_moves,
														FALSE,$controlled_move,$CommonBorderOpen_Status
														);
														$moves[] = $new_move;	
														

															$skipxy=$piece->square;
															$dROYALp=self::has_ROYAL_neighbours(  self::KING_DIRECTIONS, $skipxy, $ending_square, $color_to_move, $board );
			
															$targetpiece=clone $piece;
															$targetpiece->square->file=	$ending_square->file;
															$targetpiece->square->rank=	$ending_square->rank;
															//endpiece in truce is Palace or surrounding palace the also promotion
			
															$dgeneralp=self::check_general_ROYAL_neighbours_promotion( self::KING_DIRECTIONS, $targetpiece, $color_to_move, $board );
															// Check of destination promotion can happen
															if(($canbepromoted==1)&&(($dROYALp==TRUE)||($dgeneralp==TRUE)))
																{ 
																	$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,$piece->type-1);
			
																	if(($canpromote==TRUE)&& ($canbepromoted==1)){// then update the parity with new demoted values
																		//$piece->type=$piece->type+1;
																		//Force Promotion to add in movelist	
																		$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status );
			
																		$move3 = clone $new_move1;
																		//check if the king is killed
																		if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																			$move3->set_killed_king(TRUE);
																		$move3-> set_promotion_piece($piece->type+$dem);
			
																		//check if the king is killed
																		if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																				$move3->set_killed_king(TRUE);
																		$moves[] = $move3;
																	}
																}
			
																$new_move = new ChessMove(
																	$piece->square, $ending_square,$ending_square,
																	0,
																	$piece->color, $piece->type,
																	$capture, $board, $store_board_in_moves,
																	FALSE,$controlled_move,$CommonBorderOpen_Status
																	);
			
															$move2 = clone $new_move;
															$moves[] = $move2;
													}
												if($ROYALp==true){
														$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
														if(($canpromote==TRUE)){// then update the parity with new ptomoted values
															//Force Promotion to add in movelist
															$new_move1 = new ChessMove(
																$piece->square, $ending_square,$ending_square,
																0,
																$piece->color, $piece->type,
																$capture, $board, $store_board_in_moves,
																FALSE,$controlled_move,$CommonBorderOpen_Status
																);
												
																$move3 = clone $new_move1;
																$move3-> set_promotion_piece(12);
																$moves[] = $move3;
															}
													continue;
												}
													$targetpiece=clone $piece;
													$targetpiece->square->file=	$ending_square->file;
													$targetpiece->square->rank=	$ending_square->rank;

													$skipxy=$piece->square;
			
													$dROYALp=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $skipxy, $ending_square, $color_to_move, $board );
													if($dROYALp==true){
															$new_move = new ChessMove(
																	$piece->square,	$ending_square,$ending_square,
																	0,
																	$piece->color, $piece->type, 
																	$capture, $board, $store_board_in_moves,
																	FALSE,$controlled_move,$CommonBorderOpen_Status
																	);
																	
															$moves[] = $new_move;
															$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
															if(($canpromote==TRUE)){// then update the parity with new ptomoted values
																	//Force Promotion to add in movelist				
																	$new_move-> set_promotion_piece(12);
																	$moves[] = $new_move;
																}
																continue;
														}
													else
															break;
										}
									//***  Compromised CASTLE Penetration or Normal CASTLE movement in and out without ROYAL */	No-mans is neither promotion nor demotion zone.
									if(($piece->group=="SEMIROYAL")&&($foebrokencastle==TRUE)&&((($ending_square->rank>=8)&&(($ending_square->file>0)&&($ending_square->file<9))&&($color_to_move==1))||
									(($ending_square->rank<=1)&&(($ending_square->file>0)&&($ending_square->file<9))&&($color_to_move==2))
									))
									{
										$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
										if((($ending_square->rank==1)&&($color_to_move==2))||(($ending_square->rank==8)&&($color_to_move==1))||(($ending_square->rank==9)&&($color_to_move==1))||(($ending_square->rank==0)&&($color_to_move==2))){
											if(($canpromote==TRUE)){// then update the parity with new ptomoted values
												//Force Promotion to add in movelist
												$new_move1 = new ChessMove(
													$piece->square, $ending_square,$ending_square,
													0,
													$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
										);
									
								$move3 = clone $new_move1;
								$move3-> set_promotion_piece(12);
								$moves[] = $move3;
								}
							}
							continue;
						}
					
						//***  Foe Compromised CASTLE movement witin itself or out of  it without ROYAL. */
						if((($piece->group=="ROYAL"))&&($ROYALp==false)&&($foebrokencastle==TRUE)&&
						((($piece->square->rank==0)&&($ending_square->rank==1)&&(($ending_square->file>0)&&($ending_square->file<9))&&($color_to_move==2))||
						(($ending_square->rank==8)&&($piece->square->rank==9)&&(($ending_square->file>0)&&($ending_square->file<9))&&($color_to_move==1))||
						(($piece->square->rank==0)&&($ending_square->rank==0)&&(($ending_square->file>=0)&&($ending_square->file<=9))&&($color_to_move==2))||
						(($ending_square->rank==9)&&($piece->square->rank==9)&&(($ending_square->file>=0)&&($ending_square->file<=9))&&($color_to_move==1))			
						))
						{
									$new_move1 = new ChessMove(
										$piece->square,	$ending_square,$ending_square,
										0,
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
										);
									
								$move3 = clone $new_move1;
								$moves[] = $move3;
							continue;
						}
					
						//***  Foe Compromised CASTLE movement witin itself or out of  it without ROYAL. */	
						if((($piece->group=="SEMIROYAL"))&&($ROYALp==false)&&($foebrokencastle==TRUE)&&
						((($piece->square->rank==0)&&($ending_square->rank==1)&&(($ending_square->file>0)&&($ending_square->file<9))&&($color_to_move==2))||
						(($ending_square->rank==8)&&($piece->square->rank==9)&&(($ending_square->file>0)&&($ending_square->file<9))&&($color_to_move==1))	
						))
						{
									$new_move1 = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										0,
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
										);
									
								$move3 = clone $new_move1;
							continue;
						}
							//CASTLE to TRUCE  Defective ???
							if((($piece->group=="ROYAL"))&&
								(((($ending_square->rank>0)&&($ending_square->rank<9))&&(($ending_square->file==0)||($ending_square->file==9)))&&		
								((($piece->square->rank==0)||($piece->square->rank==9))&&(($piece->square->file>0)&&($piece->square->file<9)))	
								))
								{
									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										1,
										$piece->color,$piece->type,
										$capture, $board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
									);
								
									$move2 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);

									if(( $piece->type == ChessPiece::INVERTEDKING)){
												//$move2-> set_promotion_piece(1);
												//check if the king is killed
												if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
													$move2->set_killed_king(TRUE);
												$moves[] = $move2;
												$move3 = clone $new_move;
												$move3-> set_promotion_piece(1);
												//check if the king is killed
												if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
													$move3->set_killed_king(TRUE);
												$moves[] = $move3;
											}
									else if(( $piece->type == ChessPiece::KING)){
										//$move2-> set_promotion_piece(1);
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
										$moves[] = $move2;
										$move3 = clone $new_move;
										$move3-> set_promotion_piece(2);
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$moves[] = $move3;
									}
									elseif(( $piece->type == ChessPiece::ARTHSHASTRI)){
										$moves[] = $move2;
										//$move2-> set_promotion_piece(6);
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
									}
									/*elseif( $piece->type == ChessPiece::ARTHSHASTRI){
										$moves[] = $move2;
									}
									elseif(( $piece->type == ChessPiece::KING)){ 
										$moves[] = $move2;
										$move3 = clone $new_move;
										$move3-> set_promotion_piece(2);
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$moves[] = $move3;
										}*/
										continue;
								}
							//ROYAL Guys from CASTLE to WAR  Defective ???
							else if((($piece->group=="ROYAL"))&&
								(((($ending_square->rank>0)&&($ending_square->rank<9))&&(($ending_square->file>=0)&&($ending_square->file<=9)))&&		
								((($piece->square->rank==0)||($piece->square->rank==9))&&(($piece->square->file>0)&&($piece->square->file<9)))	
								))
								{
									$new_move = new ChessMove(
										$piece->square,	$ending_square,$ending_square,
										1,					
										$piece->color,	$piece->type,
										$capture, $board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
									);
								
									$move2 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
								
									if(( $piece->type == ChessPiece::KING) || ( $piece->type == ChessPiece::INVERTEDKING) ){
										//$move2-> set_promotion_piece(1);
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
										$moves[] = $move2;
										$move3 = clone $new_move;
										$move3-> set_promotion_piece(2);
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$moves[] = $move3;
									}
									elseif(( $piece->type == ChessPiece::ARTHSHASTRI)){
										$moves[] = $move2;
										//$move2-> set_promotion_piece(6);
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
									}
									/*elseif( $piece->type == ChessPiece::ARTHSHASTRI){
										$moves[] = $move2;
									}
									elseif(( $piece->type == ChessPiece::KING)){ 
										$moves[] = $move2;
										$move3 = clone $new_move;
										$move3-> set_promotion_piece(2);
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$moves[] = $move3;
										}*/
									if(((($board->wbrokencastle==true)) /*||($whitecanfullmoveinowncastle == 1)*/)   || ((($board->bbrokencastle==true)) /*||($blackcanfullmoveinowncastle == 1)*/) ){
										continue;}
									else break;
								}
							//ROYAL Guys from CASTLE to castle or castle to No Mans
							elseif((($piece->group=="ROYAL"))&&
								(((($ending_square->rank==0)||($ending_square->rank==9))&&(($ending_square->file>=0)&&($ending_square->file<=9)))&&		
								((($piece->square->rank==0)||($piece->square->rank==9))&&(($piece->square->file>0)&&($piece->square->file<9)))	
								))
								{
									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										1,					
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
									);
								
									$move2 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
									if(( $piece->type == ChessPiece::ARTHSHASTRI)&&($ending_square->file!=4)&&($ending_square->file!=5)){
										$moves[] = $move2;
									}
									elseif((( $piece->type == ChessPiece::KING)||( $piece->type == ChessPiece::INVERTEDKING))&&($ending_square->file!=4)&&($ending_square->file!=5)){ 
										$moves[] = $move2;
										$move3 = clone $new_move;
										$move3-> set_promotion_piece(2);
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$moves[] = $move3;
										}
									elseif(($ending_square->file==4)||($ending_square->file==5)){
										if(($piece->type == ChessPiece::KING)){
											$moves[] = $move2;
											$move3 = clone $new_move;
											$move3-> set_promotion_piece(2);
											$moves[] = $move3;
											}
										else if(($piece->type == ChessPiece::INVERTEDKING)){
												$moves[] = $move2;
												$move3 = clone $new_move;
												$move3-> set_promotion_piece(1);
												$moves[] = $move3;
												}
										else if(($piece->type == ChessPiece::ARTHSHASTRI))
											$moves[] = $move2;
											
										/*if(($piece->type == ChessPiece::ARTHSHASTRI)){
											$move2-> set_promotion_piece(5);
											$moves[] = $move2;
											}
										*/
										/*
										if(($piece->type == ChessPiece::KING)){
												//$move2-> set_promotion_piece(1);
												$moves[] = $move2;
												$move3 = clone $new_move;
												$move3-> set_promotion_piece(2);
												$moves[] = $move3;
												//check if the king is killed
												if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
													$move3->set_killed_king(TRUE);
											}*/
										}
									elseif(($ending_square->file<4)||($ending_square->file>5)){ 
										if(($piece->type == ChessPiece::KING)){
											//$move2-> set_promotion_piece(1);
											$moves[] = $move2;
											$move3 = clone $new_move;
											$move3-> set_promotion_piece(2);
											$moves[] = $move3;
											}
										
										else if(($piece->type == ChessPiece::ARTHSHASTRI))
											$moves[] = $move2;
										
										/*if(($piece->type == ChessPiece::ARTHSHASTRI)){
											$move2-> set_promotion_piece(6);
											$moves[] = $move2;
											}
											*/
										
										else if(($piece->type == ChessPiece::INVERTEDKING)){
											$moves[] = $move2;
											$move3 = clone $new_move;
											$move3-> set_promotion_piece(1);
											$moves[] = $move3;
											}
										}
									continue;
								}
							//ROYAL Guys from WAR to CASTLE (non-Scepter) or to No mans defective
							elseif(($piece->group=="ROYAL")&&
								((($piece->square->file>0)&&($piece->square->file<9))&&(($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->rank==0)||($ending_square->rank==9))&&(($ending_square->file<4)||($ending_square->file>5))))
								{
									//$moves-> set_promotion_piece(2);
									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										1,
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
									);
								
									$move2 = clone $new_move;
								
									if(( $piece->type == ChessPiece::INVERTEDKING)){
										//$move2-> set_demotion_piece($piece->type+$dem);
										$move2-> set_promotion_piece(1);
									}
									/*elseif(( $piece->type != ChessPiece::KING)&&( $piece->type != ChessPiece::INVERTEDKING)&&( $piece->type == ChessPiece::ARTHSHASTRI)&&( $piece->type != ChessPiece::SPY)){
										//$move2-> set_demotion_piece($piece->type+$dem);
										$move2-> set_promotion_piece(6);
									}
									*/
									$moves[] = $move2;
									$move3 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
									else if(( $piece->type == ChessPiece::KING)){
											$move3-> set_promotion_piece(2);
										}
									
									$moves[] = $move3;
								}
							/* ROYAL Guys  back from any location to CASTLE to own Scepters*/	
							elseif(($piece->group=="ROYAL")&&((((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4) ||($ending_square->file==5))&&($color_to_move==1)
								)||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4)||($ending_square->file==5))&&($color_to_move==2)
								)))|| /*CASTLE KING becoming full king*/
								((($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9))&&(($ending_square->file==4) ||($ending_square->file==5))&&(($ending_square->rank==0)||($ending_square->rank==9)))
								)){
									//$moves-> set_promotion_piece(2);
									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										1,
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
									);
								
									$move2 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
								
										if(( $piece->type == ChessPiece::KING)&&
										(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4) ||($ending_square->file==5))&&
										($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4)||($ending_square->file==5))&&($color_to_move==2)	)))
										){
											$moves[] = $move2;
											$move3 = clone $new_move;
											$move3-> set_promotion_piece(2);
											$moves[] = $move3;
										}
										elseif((( $piece->type == ChessPiece::INVERTEDKING))&&
										((($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9))&&
										(($ending_square->file==4) ||($ending_square->file==5))&&((($ending_square->rank==0)&&($color_to_move==1))||(($ending_square->rank==9)&&($color_to_move==2))))
										){
											//$move2-> set_promotion_piece(1);
											$moves[] = $move2;
											$move3 = clone $new_move;
											$move3-> set_promotion_piece(1);
											$moves[] = $move3;
										}
										elseif((( $piece->type == ChessPiece::KING))&&
										((($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9))&&
										(($ending_square->file==4) ||($ending_square->file==5))&&((($ending_square->rank==0)&&($color_to_move==1))||(($ending_square->rank==9)&&($color_to_move==2))))
										){
											//$move2-> set_promotion_piece(1);
											$moves[] = $move2;
											$move3 = clone $new_move;
											$move3-> set_promotion_piece(2);
											$moves[] = $move3;
										}
										elseif(( $piece->type == ChessPiece::ARTHSHASTRI)&&
										(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4) ||($ending_square->file==5))&&
										($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4)||($ending_square->file==5))&&($color_to_move==2)	)))
										){
											$moves[] = $move2;
										}
										elseif(( $piece->type == ChessPiece::ARTHSHASTRI)&&
										((($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9))&&
										(($ending_square->file==4) ||($ending_square->file==5))&&((($ending_square->rank==0)&&($color_to_move==1))||(($ending_square->rank==9)&&($color_to_move==2))))
										){
											//$move2-> set_promotion_piece(5);
											$moves[] = $move2;
										}
								}
						  	/* from CASTLE to non Scepters but within own CASTLE */		
							elseif(($piece->group=="ROYAL")&&((((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4) && ($ending_square->file!=5))&&($color_to_move==1)
								)||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4)&&($ending_square->file!=5))&&($color_to_move==2)
								)))
								)){
									//$moves-> set_promotion_piece(2);
									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										1,
										$piece->color,$piece->type,
										$capture,$board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
									);

									$move2 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);

									if(( $piece->type == ChessPiece::KING)&&
										(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4) &&($ending_square->file!=5))&&
										($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4)&&($ending_square->file!=5))&&($color_to_move==2)	)))
										){
											//$move2-> set_promotion_piece(1);
											$moves[] = $move2;
											$move3 = clone $new_move;
											$move3-> set_promotion_piece(2);
											$moves[] = $move3;
										}
									elseif(( $piece->type == ChessPiece::INVERTEDKING)&&
										(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4) &&($ending_square->file!=5))&&
										($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4)&&($ending_square->file!=5))&&($color_to_move==2)	)))
										){
											$moves[] = $move2;
											$move3 = clone $new_move;
											$move3-> set_promotion_piece(1);
											$moves[] = $move3;
										}
									elseif((($piece->type == ChessPiece::ARTHSHASTRI))&&
										(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4) &&($ending_square->file!=5))&&
										($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4)&&($ending_square->file!=5))&&($color_to_move==2)	)))
										){
											//if($piece->type != ChessPiece::ARTHSHASTRI)
												//$move2-> set_promotion_piece(6);
											$moves[] = $move2;
										}
								}
							/* From Truce to CASTLE or WAR*/
							elseif(($piece->group=="ROYAL")&&(($piece->type == ChessPiece::KING)||  ( $piece->type == ChessPiece::ARTHSHASTRI) )&&
								((($piece->square->rank>0)&&($piece->square->rank<9)&&(($piece->square->file==0) || ($piece->square->file==9)))||
									(($piece->square->file>=0)&&($piece->square->file<=9)&&(($piece->square->rank==0) || ($piece->square->rank==9))&&($ending_square->rank!=$piece->square->rank))||
									(($piece->square->file>=0)&&($piece->square->file<=9)&&(($piece->square->rank==0) || ($piece->square->rank==9))&&($ending_square->rank==$piece->square->rank)&&
									(($ending_square->file<3)||($ending_square->file>6)))
									))
								{
									$new_move = new ChessMove(
										$piece->square,	$ending_square,$ending_square,
										1,
										$piece->color,$piece->type,
										$capture,$board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
									);	

									$move3 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);

									if(( $piece->type == ChessPiece::KING)&&( $piece->type != ChessPiece::ARTHSHASTRI)){												
										$moves[] = $move3;
										$move3 = clone $new_move;
										$move3-> set_promotion_piece(2);
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
											$moves[] = $move3;
										}

									/*if(( $piece->type != ChessPiece::KING)&&( $piece->type == ChessPiece::ARTHSHASTRI)){												
											//$move3-> set_promotion_piece(6);
										}
									*/	

									$moves[] = $move3;
									continue;
								}
							/***** WAR to Truce/or CASTLE to Truce  */	
							elseif(($piece->group=="ROYAL")&&(($ending_square->file>0)&&($ending_square->file<9)||((($ending_square->rank==0)||($ending_square->rank==9)) &&($ending_square->file!=4) && ($ending_square->file!=5)))&&
								(((( $piece->type == ChessPiece::KING)||( $piece->type == ChessPiece::INVERTEDKING)||( $piece->type == ChessPiece::ARTHSHASTRI))&&
								((($piece->square->rank==0)&&(($piece->square->file==4) ||($piece->square->file==5))&&($color_to_move==1)
								)||(($piece->square->rank==9)&&(($piece->square->file==4)||($piece->square->file==5))&&($color_to_move==2))))|| 
								((( $piece->type == ChessPiece::KING) ||( $piece->type == ChessPiece::INVERTEDKING)||( $piece->type == ChessPiece::ARTHSHASTRI))&& (($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9)))
								)){
									//$moves-> set_promotion_piece(2);
									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										1,
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
									);

									$move2 = clone $new_move;
									$moves[] = $move2;

									if((( $piece->type == ChessPiece::INVERTEDKING)) /*&&((($ending_square->rank==0)&&($color_to_move==2))||(($ending_square->rank==9)&&(($color_to_move==1))))
									&&(($ending_square->file==5)||($ending_square->file==4))*/){	
										$move2 = clone $new_move;
										$move2-> set_promotion_piece(1);
										$moves[] = $move2;
									}
									/*else
									if(( $piece->type == ChessPiece::ARTHSHASTRI)&&((($ending_square->rank==0)&&($color_to_move==2))||(($ending_square->rank==9)&&(($color_to_move==1))))
									&&(($ending_square->file==5)||($ending_square->file==4))){	
										$move2-> set_promotion_piece(5);
									}
									*/
									else if( $piece->type == ChessPiece::KING){
										$move2 = clone $new_move;
										$move2-> set_promotion_piece(2);
										$moves[] = $move2;
										}
									/*else
									if(( $piece->type == ChessPiece::ARTHSHASTRI)&&((($ending_square->rank==0)&&($color_to_move==2))||(($ending_square->rank==9)&&(($color_to_move==1))))
									&&(($ending_square->file!=5)&&($ending_square->file!=4))){
											$move3-> set_promotion_piece(6);
										}
									*/	
								}
							/***** No Mans */	
							elseif(($piece->group=="ROYAL")&&(($piece->type == ChessPiece::INVERTEDKING)||($piece->type == ChessPiece::KING)||($piece->type == ChessPiece::ARTHSHASTRI))&&
								(($piece->square->rank>0)&&($piece->square->rank<9)&&(($piece->square->file==0) || ($piece->square->file==9))))
								{				
									$moves[] = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										0,
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										false,$controlled_move,$CommonBorderOpen_Status);

									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										1,					
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										FALSE,$controlled_move,$CommonBorderOpen_Status
									);

									$move3 = clone $new_move;

									if( $piece->type == ChessPiece::INVERTEDKING){
											$move3->set_promotion_piece(1);
										}
									else if( $piece->type == ChessPiece::KING){
											$move3->set_promotion_piece(2);
										};
									$moves[] = $move3;
								}
							/***** Add for ArthShahstri logoc also */	
							elseif(($piece->group=="ROYAL")&&(( $piece->type == ChessPiece::KING)||( $piece->type == ChessPiece::ARTHSHASTRI))&&
								((($piece->square->rank==0)&&(($piece->square->file==4) ||($piece->square->file==5))&&($color_to_move==1)
								)||(($piece->square->rank==9)&&(($piece->square->file==4)||($piece->square->file==5))&&($color_to_move==2)
								))){
									//$moves-> set_promotion_piece(2);
									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										1,
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										false,$controlled_move,$CommonBorderOpen_Status
									);

									$move2 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);

									//$move2-> set_demotion_piece($piece->type+$dem);
									if(( $piece->type == ChessPiece::KING)){
										//$move2-> set_promotion_piece(1);
										$moves[] = $move2;
										$move3 = clone $new_move;
										$move3-> set_promotion_piece(2);
										$moves[] = $move3;
									}

									if(( $piece->type == ChessPiece::ARTHSHASTRI)){		
										//$move2-> set_promotion_piece(6);
										$moves[] = $move2;
										}
								}
							 /***** Add for ArthShahstri logoc also */	
							elseif(($piece->group=="ROYAL")&&(( $piece->type == ChessPiece::KING)||( $piece->type == ChessPiece::ARTHSHASTRI))&&
								((($piece->square->rank==0)&&(($ending_square->file==4) ||($ending_square->file==5))&&($color_to_move==1)
								)||(($piece->square->rank==9)&&(($ending_square->file==4)||($ending_square->file==5))&&($color_to_move==2)
								))){
									//$moves-> set_promotion_piece(2);
									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										1,
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										false,$controlled_move,$CommonBorderOpen_Status
									);

									$move2 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);

									//$move2-> set_demotion_piece($piece->type+$dem);
									if( $piece->type == ChessPiece::KING){
										//$move2-> set_promotion_piece(1);
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);

										$move3-> set_promotion_piece(2);
										$moves[] = $move3;
									}

									if( $piece->type == ChessPiece::ARTHSHASTRI){
										//$move2-> set_promotion_piece(5);
										$moves[] = $move2;
										}
								}
							else{
								/* Classical. Officers can penetrate the compromised CASTLE with ROYALs or with General*/
								if(($piece->group=="OFFICER") &&($board->board[$ending_square->rank][$ending_square->file]==null)&&((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->file>=0)||($ending_square->file<=9))&&(
									(($ending_square->rank==0)||($ending_square->rank==9))))){
										if(($ROYAL_ROYALp==true)||(($selfbrokencastle==true)&&($ending_square->rank==0)&&($color_to_move==1)) || (($selfbrokencastle==true)&&($ending_square->rank==9)&&($color_to_move==2))||
										(($foebrokencastle==true)&&($ending_square->rank==0)&&($color_to_move==2))||(($foebrokencastle==true)&&($ending_square->rank==9)&&($color_to_move==1))){
											$new_move = new ChessMove(
												$piece->square, $ending_square,$ending_square,
												0,
												$piece->color, $piece->type,
												$capture, $board, $store_board_in_moves,
												FALSE,$controlled_move,$CommonBorderOpen_Status
												);

											$move2 = clone $new_move;
											$moves[] = $move2;
										}
										continue;
									}
								//Officers can jump from CASTLE to WAR only 1 step. Uncomproimized CASTLE Logic missing
								elseif(($piece->group=="OFFICER") &&($board->board[$ending_square->rank][$ending_square->file]==null)&&
								((($piece->square->file>=1)&&($piece->square->file<=8))&&(($piece->square->rank==0)||($piece->square->rank==9))&&(($ending_square->file>0)||($ending_square->file<9))&&(
										(($ending_square->rank>0)&&($ending_square->rank<9))))){	

											if($ROYAL_ROYALp==true){

												$skipxy=$piece->square;
												$dROYALp=self::has_ROYAL_neighbours(  self::KING_DIRECTIONS, $skipxy, $ending_square, $color_to_move, $board );

												$targetpiece=clone $piece;
												$targetpiece->square->file=	$ending_square->file;
												$targetpiece->square->rank=	$ending_square->rank;
												//endpiece in truce is Palace or surrounding palace the also promotion

												$dgeneralp=self::check_general_ROYAL_neighbours_promotion( self::KING_DIRECTIONS, $targetpiece, $color_to_move, $board );
												// Check of destination promotion can happen
												if(($canbepromoted==1)&&(($dROYALp==TRUE)||($dgeneralp==TRUE)))
													{ 
														$dem=-1;
														if(($board->gametype>=2)&&($piece->type==ChessPiece::GENERAL))
														{
															$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,6);
															$dem=-3;
														}
														else if($piece->type!==ChessPiece::GENERAL)
														$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,$piece->type-1);

														if(($canpromote==TRUE)&& ($canbepromoted==1)){// then update the parity with new demoted values
															//$piece->type=$piece->type+1;
															//Force Promotion to add in movelist	
															$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status );

															$move3 = clone $new_move1;
															//check if the king is killed
															if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																$move3->set_killed_king(TRUE);
															$move3-> set_promotion_piece($piece->type+$dem);

															//check if the king is killed
															if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																	$move3->set_killed_king(TRUE);
															$moves[] = $move3;
														}
													}

													$new_move = new ChessMove(
														$piece->square, $ending_square,$ending_square,
														0,
														$piece->color, $piece->type,
														$capture, $board, $store_board_in_moves,
														FALSE,$controlled_move,$CommonBorderOpen_Status
														);

												$move2 = clone $new_move;
												$moves[] = $move2;
											}

											//compromised logic to be added for unlimited moves
											break;
										}
								/*Officer Cannot kill anyone from war to  CASTLE (uncompromized)*/		
								elseif(($piece->group=="OFFICER") &&($board->board[$ending_square->rank][$ending_square->file]!=null)&&((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->file>=0)||($ending_square->file<=9))&&(
									(($ending_square->rank==0)||($ending_square->rank==9))))){
										//continue;
									}

								/* Classical General or Officer can penetrate the CASTLE with the help of ROYAL. But cannot Kill Inside*/
								if(($piece->group=="OFFICER") &&($board->board[$ending_square->rank][$ending_square->file]!=null)&&((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->file>=0)&&($ending_square->file<=9))&&(
									((($ending_square->rank==0)&&($selfbrokencastle==false)&&($color_to_move==1))||(($ending_square->rank==9)&&($selfbrokencastle==false)&&($color_to_move==2))||
									(($ending_square->rank==0)&&($foebrokencastle==false)&&($color_to_move==2))||(($ending_square->rank==9)&&($foebrokencastle==false)&&($color_to_move==1)))) && (($ROYALp==true)) /*&& ($piece->type==ChessPiece::GENERAL) */)){
										if ($capture==false) {
											$new_move = new ChessMove(
												$piece->square, $ending_square,$ending_square,
												0,
												$piece->color, $piece->type,
												$capture, $board, $store_board_in_moves,
												false,$controlled_move,$CommonBorderOpen_Status
											);

											$move2 = clone $new_move;
											$moves[] = $move2;
										}
										continue;
									}

								/* Classical General or Officer can kill inside the compromised castle.*/
								if(($piece->group=="OFFICER") &&($board->board[$ending_square->rank][$ending_square->file]!=null)&&((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->file>0)&&($ending_square->file<9))&&(
									((($ending_square->rank==0)&&($selfbrokencastle==true)&&($color_to_move==1))||(($ending_square->rank==9)&&($selfbrokencastle==true)&&($color_to_move==2))||
									(($ending_square->rank==0)&&($foebrokencastle==true)&&($color_to_move==2))||(($ending_square->rank==9)&&($foebrokencastle==true)&&($color_to_move==1)))) && (($ROYALp==true)) /*&& ($piece->type==ChessPiece::GENERAL) */)){
										if ($capture==true) {
											$new_move = new ChessMove(
												$piece->square, $ending_square,$ending_square,
												0,
												$piece->color, $piece->type,
												$capture, $board, $store_board_in_moves,
												false,$controlled_move,$CommonBorderOpen_Status
											);

											$move2 = clone $new_move;
											$moves[] = $move2;
										}
										continue;
									}
								/** Can kill out of the Compromised castle.. Promotion logic not added here */
								if( (($selfbrokencastle==true)&&( $piece->square->rank==9)&&($ending_square->rank<=5)&&($color_to_move==2)||
									($foebrokencastle==true)&&($ending_square->rank>=4)&&($piece->square->rank==0)&&($color_to_move==2))||  
									(($selfbrokencastle==true)&&( $piece->square->rank==0)&&($ending_square->rank>=4)&&($color_to_move==1)||
									($foebrokencastle==true)&&($ending_square->rank<=5)&&($piece->square->rank==9)&&($color_to_move==1)))
									{ 
										$new_move = new ChessMove(
											$piece->square, $ending_square,$ending_square,
											0,
											$piece->color, $piece->type,
											$capture, $board, $store_board_in_moves,
											FALSE,$controlled_move,$CommonBorderOpen_Status
											);

										$move2 = clone $new_move;
										$moves[] = $move2;
										//break; //Only if captured 
									}
								elseif(($piece->group=="OFFICER") &&($board->board[$ending_square->rank][$ending_square->file]!=null)&&((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->file>=0)||($ending_square->file<=9))&&(
								(($ending_square->rank==0)||($ending_square->rank==9))))){ /*Cannot kill anyone from war to  CASTLE*/
									}
								//movement withing truce line not allowed	
								elseif(($piece->group=="OFFICER") &&(($ending_square->file==0)||($ending_square->file==9))&&(
										(($ending_square->rank>=0)&&($ending_square->rank<=9))
										)&&(($ROYALp==FALSE)&&($board->gametype>=1))){
											continue; 
									}
								//Kautilya Allowed the to change zone with the help of General.		
								elseif(($piece->group=="OFFICER") &&(($ending_square->file==0)||($ending_square->file==9))&&(
										(($ending_square->rank>=0)&&($ending_square->rank<=9))
										)&&(($ROYALp==FALSE)/* &&(($GeneralROYALp==FALSE)  General as pusher*/&&($board->gametype>=2))){
											continue; // Check of demotion can happen in Truce or No Mans as per Parity
									}									
								/* Classical General can penetrate the TRUCE with the help of ROYAL*/	
								elseif(($piece->type==ChessPiece::GENERAL)&&($piece->group=="OFFICER") &&(($ending_square->file==0)||($ending_square->file==9))&&(
										(($ending_square->rank>=0)&&($ending_square->rank<=9))
										)&&(($ROYAL_ROYALp==true)&&($board->gametype>=1))){

												$new_move = new ChessMove(
													$piece->square, $ending_square,$ending_square,
													0,
													$piece->color, $piece->type,
													$capture, $board, $store_board_in_moves,
													FALSE,$controlled_move,$CommonBorderOpen_Status
													);

												$move2 = clone $new_move;
												$moves[] = $move2;
												continue;
										}
								/* Classical Officers can penetrate the TRUCE with the help of ROYAL*/	
								elseif(($piece->type!=ChessPiece::GENERAL) &&($piece->group=="OFFICER") &&(($ending_square->file==0)||($ending_square->file==9))&&(
									(($ending_square->rank>=0)&&($ending_square->rank<=9))
									)&&(($ROYAL_ROYALp==true)&&($board->gametype>=1))){

											$new_move = new ChessMove(
												$piece->square, $ending_square,$ending_square,
												0,
												$piece->color, $piece->type,
												$capture, $board, $store_board_in_moves,
												FALSE,$controlled_move,$CommonBorderOpen_Status
												);

											$move2 = clone $new_move;
											$moves[] = $move2;

											if((($piece->group=="OFFICER")&&($piece->square->file>=0)&&($piece->square->file<=9)))
											{ // Check of promotion can happen within warzone or even in compromised
												$skipxy=$piece->square;
												$dROYALp=self::has_ROYAL_neighbours( 
													self::KING_DIRECTIONS,
													$skipxy,
													$ending_square,
													$color_to_move,
													$board
													);

												//endpiece in truce is Palace or surrounding palace the also promotion
												/*if((($ending_square->rank>=3) && ($ending_square->rank<=6)) && (($ending_square->file==0) ||($ending_square->file==9) ) ){
													$dROYALp=true;
												}
												*/
												if((($piece->square->rank>=3)&&($piece->square->rank<=6)) &&(($piece->square->file==0)||($piece->square->file==9)))
												{
													//Truce Palace captured by Opponent
													if(($piece->square->rank==3) && ($piece->color==1) && ( 
														(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==2))
														||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==2))
													)){

													}
													else if(($piece->square->rank==6) && ($piece->color==1) && (
														(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==2))
													||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==2))
													)){

													}
													else if(($piece->square->rank==3) && ($piece->color==2) && ( 
														(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==1))
													||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==1))
													)){

													}
													else if(($piece->square->rank==6) && ($piece->color==2) && ( 
														(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==1))
													||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==1))
													)){

													}
													else if(($piece->square->rank==3) && ( 
														(($piece->square->file==0) && ($board->board[4][0]==null))
													||(($piece->square->file==9) && ($board->board[4][9]==null))
													)){
													
													}
													else if(($piece->square->rank==6) && ( 
														(($piece->square->file==0) && ($board->board[5][0]=null))
													||(($piece->square->file==9) && ($board->board[5][9]!=null))
													)){
													
													}
													else if(($piece->square->rank==3) && ( 
														(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group=='NOBLE'))
													||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group=='NOBLE'))
													)){
													
													}
													else if(($piece->square->rank==6) && ( 
														(($piece->square->file==0) &&  ($board->board[5][0]!=null)&&($board->board[5][0]->group=='NOBLE'))
													||(($piece->square->file==9) &&  ($board->board[5][9]!=null)&&($board->board[5][9]->group=='NOBLE'))
													)){
													
													}
													else
													{	$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;$dROYALp=true;$dgeneralp=true;
													}
												}
												$targetpiece=clone $piece;
												$targetpiece->square->file=	$ending_square->file;
												$targetpiece->square->rank=	$ending_square->rank;
							
												$dgeneralp=self::check_general_ROYAL_neighbours_promotion( 
													self::KING_DIRECTIONS,
													$targetpiece,
													$color_to_move,
													$board
													);
												
													$dem=-1;
													if(($board->gametype>=2)&&($piece->type==ChessPiece::GENERAL))
													{
														$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,6);
														$dem=-3;
													}
													else if($piece->type!==ChessPiece::GENERAL)
													$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,$piece->type-1);

												if(($canbepromoted==1)&&($canpromote==true) && (($dROYALp==TRUE) ||($dgeneralp==true)))
												{ // Check of demotion can happen
													$dem=-1;
												
													$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,$piece->type-1);
												
													if($canpromote==TRUE){// then update the parity with new demoted values
														//$piece->type=$piece->type+1;
														//Force Promotion to add in movelist	
														$new_move1 = new ChessMove(
															$piece->square,	$ending_square,$ending_square,
															0,
															$piece->color, $piece->type,
															$capture, $board, $store_board_in_moves,
															FALSE,$controlled_move,$CommonBorderOpen_Status
															);
														
														$move3 = clone $new_move1;
														//check if the king is killed
														if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
															$move3->set_killed_king(TRUE);
														$move3-> set_promotion_piece($piece->type+$dem);
														$moves[] = $move3;
													}
												}
											}

											continue;
									}
								/* */ 
								elseif(($piece->group=="OFFICER") &&(($ending_square->file==0)||($ending_square->file==9))&&(
										(($ending_square->rank>=0)&&($ending_square->rank<=9)) && (($piece->square->rank>1)&&($piece->square->rank<9))
										)&&(($ROYAL_ROYALp==false)&&($board->gametype>=1))){
												break; //end of columns reached
										}
								/* */		
								else if((($board->controlled_color==$piece->color)&&($piece->color==$board->color_to_move)&&($controlled_move==true)))
								{
									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										0,
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										false,$controlled_move,$CommonBorderOpen_Status
									);

										//y$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, -1, $piece->color, $piece->type, $capture, $board, $store_board_in_moves, false);
										//$move2 = clone $new_move1;
										//$moves[] = 	$move2;
										$move2 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
										$moves[] = $move2;
										continue;
								}
								// Naarad is forcing the Controlled Piece
								else if((($piece->color==$board->color_to_move)&&($controlled_move==true)))
								{
									$new_move = new ChessMove(
										$piece->square, $ending_square,$ending_square,
										0,
										$piece->color, $piece->type,
										$capture, $board, $store_board_in_moves,
										false,$controlled_move,$CommonBorderOpen_Status
									);

										//y$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, -1, $piece->color, $piece->type, $capture, $board, $store_board_in_moves, false);
										//$move2 = clone $new_move1;
										//$moves[] = 	$move2;
										$move2 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
										$moves[] = $move2;
										continue;
								}					
								else if(($controlled_move==false))
								{

									$CommonBorderOpen_Status = self::get_CommonBorderOpen_Status(	$piece, $color_to_move, $board,$ending_square );

									if(($CommonBorderOpen_Status==0)){
										if ((($ROYAL_ROYALp==false) && ($StartPiece_PromotionalTouch==false)) && ($piece->type!=ChessPiece::GODMAN) ){
												break;
										}
										else if ((($ROYAL_ROYALp==true) || ($StartPiece_PromotionalTouch)) && ($piece->type!=ChessPiece::GODMAN) ){
											if ($board->board[$ending_square->rank][$ending_square->file]) {
												$CommonBorderOpen_Status=1;
												break;
											}
											$CommonBorderOpen_Status=1;
										}
										else if (($piece->type==ChessPiece::GODMAN)){
												$CommonBorderOpen_Status=0;					
											}
									}

									if(($capture==true) && ($ending_square->mediatorrank!=null)&&($ending_square->mediatorfile!=null)){
										$mediatorpiece = clone $piece;
										$endpiece = clone $board->board[$ending_square->rank][$ending_square->file];

										if(($piece->square->mediatorrank!=$ending_square->mediatorrank)&&($piece->square->mediatorfile!=$ending_square->mediatorfile)){
											$mediatorpiece->square->mediatorrank=$ending_square->mediatorrank;
											$mediatorpiece->square->mediatorfile=$ending_square->mediatorfile;
											$mediatorpiece->state="V";
											}
										$sittingpiece=$board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file];
										$board1 = clone $board;

										/* Check if border breached */
										if(($CommonBorderOpen_Status==0)){
											if (($ROYAL_ROYALp==false) && ($piece->type!=ChessPiece::GODMAN) ){
													continue;
											}
											else if (($ROYAL_ROYALp==true) && ($piece->type!=ChessPiece::GODMAN) ){
												if ($board->board[$ending_square->rank][$ending_square->file]) {
													$CommonBorderOpen_Status=1;
													break;
												}											
												$CommonBorderOpen_Status=1;
											}
											else if (($piece->type==ChessPiece::GODMAN)){
													$CommonBorderOpen_Status=0;					
												}
										}
		
										/* Check if border breached */

										if(($CommonBorderOpen_Status == 1)){
											$board1->commonborderbreached=true;
										}
										

										$board1->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]=$mediatorpiece;
										if($i>=2){
											$moves = self::add_running_capture_moves_to_moves_list($moves, $mediatorpiece, $endpiece, $color_to_move, $board1, $store_board_in_moves,1,$selfbrokencastle,$foebrokencastle,$CommonBorderOpen_Status);
											break;
										}
										//else 
											//continue;
									}
									else {
										//y$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, -1, $piece->color, $piece->type, $capture, $board, $store_board_in_moves, false);
										//$move2 = clone $new_move1;
										//$moves[] = 	$move2;
										/* Check if border breached */
										$board1 = clone $board;

										if(($CommonBorderOpen_Status==0)){
											if (($ROYAL_ROYALp==false) && ($piece->type!=ChessPiece::GODMAN) ){
													continue;
											}
											else if (($ROYAL_ROYALp==true) && ($piece->type!=ChessPiece::GODMAN) ){
												if ($board->board[$ending_square->rank][$ending_square->file]) {
													$CommonBorderOpen_Status=1;
													break;
												}											
												$CommonBorderOpen_Status=1;
											}
											else if (($piece->type==ChessPiece::GODMAN)){
													$CommonBorderOpen_Status=0;					
												}
										}
		
										/* Check if border breached */

										if(($CommonBorderOpen_Status == 1)){
											$board1->commonborderbreached=true;
											$board1->CommonBorderOpen_Status=1;
										}


										$targetpiece=clone $piece;
										$targetpiece->square->file=	$ending_square->file;
										$targetpiece->square->rank=	$ending_square->rank;
					
										$dgeneralp=self::check_general_ROYAL_neighbours_promotion( 
											self::KING_DIRECTIONS,
											$targetpiece,
											$color_to_move,
											$board1
											);
										
											$dem=-1;
											if(($board->gametype>=2)&&($piece->type==ChessPiece::GENERAL))
											{
												$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,6);
												$dem=-3;
											}
											else if($piece->type!==ChessPiece::GENERAL)
											$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,$piece->type-1);

										//If there are no Royal_touch etc.
										if ( (($ROYAL_ROYALp==false) ||( $dgeneralp==false)) &&($board->iswcZoneRoyal==false) &&  ($ending_square!=null)  &&
										( (abs($ending_square->rank-$piece->square->rank)>=1)||(abs($ending_square->file-$piece->square->file)>=1))&& ($piece->square->rank==0) )  {
											break;
										}


										if ( (($ROYAL_ROYALp==false) ||( $dgeneralp==false)) &&($board->isbcZoneRoyal==false) &&  ($ending_square!=null)  &&
										( (abs($ending_square->rank-$piece->square->rank)>=1)||(abs($ending_square->file-$piece->square->file)>=1))&& ($piece->square->rank==9) )  {
											break;
										}	
																

										if(($canbepromoted==1)&&($canpromote==true) && (/*($dROYALp==TRUE) ||*/($dgeneralp==true)))
										{ // Check of demotion can happen
											$dem=-1;
										
											$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,$piece->type-1);
										
											if($canpromote==TRUE){// then update the parity with new demoted values
												//$piece->type=$piece->type+1;
												//Force Promotion to add in movelist	
												$new_move1 = new ChessMove(
													$piece->square,	$ending_square,$ending_square,
													0,
													$piece->color, $piece->type,
													$capture, $board1, $store_board_in_moves,
													FALSE,$controlled_move,$CommonBorderOpen_Status
													);
												
												//check if the king is killed
												if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
													$new_move1->set_killed_king(TRUE);
												$new_move1-> set_promotion_piece($piece->type+$dem);
												$moves[] = $new_move1;
											}
										}
										
										$new_move = new ChessMove(
											$piece->square, $ending_square,$ending_square,
											0,
											$piece->color, $piece->type,
											$capture, $board1, $store_board_in_moves,
											false,$controlled_move,$CommonBorderOpen_Status
										);


										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$new_move->set_killed_king(TRUE);
										$moves[] = $new_move;

										if(($CommonBorderOpen_Status==1) && ($piece->type==ChessPiece::SPY) ){
												//spy is jumping the border
												$board1->commonborderbreached=false;
												$new_move = new ChessMove(
													$piece->square, $ending_square,$ending_square,
													0,
													$piece->color, $piece->type,
													$capture, $board1, $store_board_in_moves,
													false,$controlled_move,-1
												);										

												//check if the king is killed
												if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
													$new_move->set_killed_king(TRUE);
												$moves[] = $new_move;
											}							

											else if(($CommonBorderOpen_Status==1) && ( ($piece->group=="SOLDIER") ||($piece->group=="OFFICER") )){
												break;
											}

										}
								}
							}
						
							if(($piece->group=="SEMIROYAL")) //&&($ROYALp==true))
							{ // ROYALs can be promoted in Apaad Dharma
								$skipxy=$piece->square;
							
								$targetpiece=clone $piece;
								$targetpiece->square->file=	$ending_square->file;
								$targetpiece->square->rank=	$ending_square->rank;

								/*
								$dgeneralp=self::check_general_ROYAL_neighbours_promotion( 
									self::KING_DIRECTIONS,
									$targetpiece,
									$color_to_move,
									$board,
									$piece
									);
								
								*/

								//calculate special promotion on jump//
								
								$spymoves=[];
								$spymoves= self::check_neighbours_promotion_candidates(
									self::KING_DIRECTIONS,
									$targetpiece,
									$color_to_move,
									$board,
									$piece
								);						
																
		
								for($iiii=0;((count($spymoves)>=1) && ($iiii<count($spymoves)));$iiii=$iiii+1){
									$board1=clone $board;
									$board1->board[$piece->square->rank][$piece->square->file]=null;
									$board1->board[$targetpiece->square->rank][$targetpiece->square->file]=$piece;
									$ttt=1;
									$temppiece=$board1->board[$spymoves[$iiii]->rank][$spymoves[$iiii]->file];
									

									if(($board->gametype>=2)&&($temppiece->type==ChessPiece::GENERAL))
									{
										$canpromote=$board->checkpromotionparity( $board->export_fen(), $temppiece,$color_to_move,$board,6);
										$dem=-3;
									}
									else if(($temppiece->group=="OFFICER"))
									{
										$canpromote=$board->checkpromotionparity( $board->export_fen(), $temppiece,$color_to_move,$board,$temppiece->type-1);
										$dem=-1;
									}
									else if(($temppiece->group=="SEMIROYAL"))
									{
										$canpromote=$board->checkpromotionparity( $board->export_fen(), $temppiece,$color_to_move,$board,12);
										$dem=5;
									}

									if($canpromote==TRUE){// then update the parity with new demoted values
										//'z'' means promote same square by moving seniors.. $piece->square,	$ending_square would not be used.
										// for z $temppiece->square, witll be updated in same square.
										$new_move1 = new ChessMove(
											$piece->square,	$ending_square,$temppiece->square,
											0,
											$piece->color, $temppiece->type,
											$capture, $board1, $store_board_in_moves,
											FALSE,$controlled_move,$CommonBorderOpen_Status,null,'z'
											);
									
										$move3 = clone $new_move1;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$move3->set_touched_promotion_piece($temppiece->type+$dem);
										$moves[] = $move3;
									}
									/* */
								}
							
								$dROYALp=self::has_ROYAL_neighbours( 
									self::KING_DIRECTIONS,
									$skipxy,
									$ending_square,
									$color_to_move,
									$board
									);

									if((($piece->square->rank>=3)&&($piece->square->rank<=6)) &&(($piece->square->file==0)||($piece->square->file==9)))
									{
										//Truce Palace captured by Opponent
										if(($piece->square->rank==3) && ($piece->color==1) && ( 
											(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==2))
											||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==2))
										)){
										
										}
										else if(($piece->square->rank==6) && ($piece->color==1) && (
											(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==2))
										||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==2))
										)){
										
										}
										else if(($piece->square->rank==3) && ($piece->color==2) && ( 
											(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==1))
										||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==1))
										)){
										
										}
										else if(($piece->square->rank==6) && ($piece->color==2) && ( 
											(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==1))
										||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==1))
										)){
										
										}
										else if(($piece->square->rank==3) && ( 
											(($piece->square->file==0) && ($board->board[4][0]==null))
										||(($piece->square->file==9) && ($board->board[4][9]==null))
										)){
										
										}
										else if(($piece->square->rank==6) && ( 
											(($piece->square->file==0) && ($board->board[5][0]=null))
										||(($piece->square->file==9) && ($board->board[5][9]!=null))
										)){
										
										}
										else if(($piece->square->rank==3) && ( 
											(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group=='NOBLE'))
										||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group=='NOBLE'))
										)){
										
										}
										else if(($piece->square->rank==6) && ( 
											(($piece->square->file==0) &&  ($board->board[5][0]!=null)&&($board->board[5][0]->group=='NOBLE'))
										||(($piece->square->file==9) &&  ($board->board[5][9]!=null)&&($board->board[5][9]->group=='NOBLE'))
										)){
										
										}				
										else
											{$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;$dROYALp=true;$dgeneralp=true;
											}		
									}
								//Condition to check if it is of Kautilya if(($canbepromoted==1))
								if($dROYALp==true)
								{ // Check of demotion can happen
									$dem=-1;
									$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
									if($canpromote==TRUE){// then update the parity with new demoted values
										$new_move1 = new ChessMove(
											$piece->square,	$ending_square,$ending_square,
											0,
											$piece->color, $piece->type,
											$capture, $board, $store_board_in_moves,
											FALSE,$controlled_move,$CommonBorderOpen_Status
											);
										
										$move3 = clone $new_move1;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$move3-> set_promotion_piece(12);
										$moves[] = $move3;
									}
								}
							}
						else if((($piece->group=="OFFICER")&&($piece->square->file>=0)&&($piece->square->file<=9)))
							{ // Check of promotion can happen within warzone or even in compromised
								$skipxy=$piece->square;
								$dROYALp=self::has_ROYAL_neighbours( 
									self::KING_DIRECTIONS,
									$skipxy,
									$ending_square,
									$color_to_move,
									$board
									);
								
								$targetpiece=clone $piece;
								$targetpiece->square->file=	$ending_square->file;
								$targetpiece->square->rank=	$ending_square->rank;
								
								$dgeneralp=self::check_general_ROYAL_neighbours_promotion( 
									self::KING_DIRECTIONS,
									$targetpiece,
									$color_to_move,
									$board,
									$piece
									);
								//endpiece in truce is Palace or surrounding palace the also promotion
								/*if((($ending_square->rank>=3) && ($ending_square->rank<=6)) && (($ending_square->file==0) ||($ending_square->file==9) ) ){
									$dROYALp=true;$dgeneralp=true;
								}*/
							
								if((($piece->square->rank>=3)&&($piece->square->rank<=6)) &&(($piece->square->file==0)||($piece->square->file==9)))
								{
									//Truce Palace captured by Opponent
									if(($piece->square->rank==3) && ($piece->color==1) && ( 
										(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==2))
										||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==2))
									)){
									
									}
									else if(($piece->square->rank==6) && ($piece->color==1) && (
										(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==2))
									||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==2))
									)){

									}
									else if(($piece->square->rank==3) && ($piece->color==2) && ( 
										(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==1))
									||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==1))
									)){

									}
									else if(($piece->square->rank==6) && ($piece->color==2) && ( 
										(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==1))
									||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==1))
									)){

									}
									else if(($piece->square->rank==3) && ( 
										(($piece->square->file==0) && ($board->board[4][0]==null))
									||(($piece->square->file==9) && ($board->board[4][9]==null))
									)){

									}
									else if(($piece->square->rank==6) && ( 
										(($piece->square->file==0) && ($board->board[5][0]=null))
									||(($piece->square->file==9) && ($board->board[5][9]!=null))
									)){

									}
									else if(($piece->square->rank==3) && ( 
										(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group=='NOBLE'))
									||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group=='NOBLE'))
									)){

									}
									else if(($piece->square->rank==6) && ( 
										(($piece->square->file==0) &&  ($board->board[5][0]!=null)&&($board->board[5][0]->group=='NOBLE'))
									||(($piece->square->file==9) &&  ($board->board[5][9]!=null)&&($board->board[5][9]->group=='NOBLE'))
									)){

									}				
									else
										{$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;$dROYALp=true;$dgeneralp=true;
										}		
								}


								if(($canbepromoted==1)&&(($dROYALp==TRUE) ||($dgeneralp==true)))
								{ // Check of demotion can happen
									$dem=-1;
									if(($board->gametype>=2)&&($piece->type==ChessPiece::GENERAL))
										{
											$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,6);
											$dem=-3;
										}
									else if(($board->gametype>=2)&&($piece->type!==ChessPiece::GENERAL)&&($piece->group!=="ROYAL")&&($piece->group!=="NOBLE")){
										if($piece->type!==ChessPiece::SPY) {
										$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,$piece->type-1);
										}
										else { $canpromote=false;}
									}
									if($canpromote==TRUE){// then update the parity with new demoted values
										//$piece->type=$piece->type+1;
										//Force Promotion to add in movelist	
										$new_move1 = new ChessMove(
											$piece->square,	$ending_square,$ending_square,
											0,
											$piece->color, $piece->type,
											$capture, $board, $store_board_in_moves,
											FALSE,$controlled_move,$CommonBorderOpen_Status
											);

										$move3 = clone $new_move1;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$move3-> set_promotion_piece($piece->type+$dem);
										$moves[] = $move3;
									}
								}
							}

						if ( $capture ) { /* stop sliding*/	break; 	}

							// empty square
							// continue sliding
							// continue;
						}
					}
				
			}

		return $moves;
	}
	
	static function add_capture_moves_to_moves_list(
		array $directions_list,
		array $moves,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		bool $store_board_in_moves,
		int $cankill,
		bool $selfbrokencastle,
		bool $foebrokencastle
	): array {
		$controlled_move=false;
		/*if($piece->type==ChessPiece::KNIGHT)
			$ttt=1;
		if($piece->type==ChessPiece::GENERAL)
			$ttt=1;*/
			$officer_ROYALp=false;
			if($piece->type==13) {$tempstr="SEMIROYAL";} else {$tempstr="ALL";} 
			$officer_ROYALp=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $piece->square, $piece->square, $color_to_move, $board, $tempstr );

				//piece is trapped in palace and has no royal help.
				if((($piece->type==13))&&  ((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
				||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9))  ))
				{
					if($officer_ROYALp==false)
						return $moves;//
				}

			$CommonBorderOpen_Status=-1;
			$tempDirection=null;
			$officerp=TRUE; 
			$mtype=1;//slide //2 jump
			$lastaccessiblerow=-1;
			$tempDirection=self::get_Retreating_ARMY_directions( $piece, $color_to_move, $board, $mtype );

			if (isset($tempDirection) && is_array($tempDirection)){
				if(!empty($tempDirection))
				$directions_list=$tempDirection;
				$lastaccessiblerow=self::get_LastKing_ArthShashtri_Row( $piece, $color_to_move, $board, $mtype );
			}
	
			$tempDirection=null;

			if(($piece->type==ChessPiece::PAWN)){
				$piece->square->rank;
			}

			$officerp=self::check_officers_neighbours( /**/ self::KING_DIRECTIONS, $piece, $color_to_move, $board, 'exclude' );
			$enemytrapped=false;

			/* Start new code added for sleepin p[ieces */

			//Pieces inside CASTLE become Semi-Royal for the time-being.
			if(((($piece->square->file>=1) &&($piece->square->file<=3)) || (($piece->square->file>=6) &&($piece->square->file<=8)))  && 
			((($piece->square->rank==0) && ($color_to_move==1)) || ($piece->square->rank==9)  && ($color_to_move==2)))
			{ 
				if(($board->isCurrentZoneRoyal==true)&& ($piece->type!=13)) {
					//$ROYALp=$ROYAL_ROYALp=true;
					$officer_ROYALp=true;
				}
				else if(($board->isCurrentZoneRoyal==false)&& ($piece->type==13)) {
					$officer_ROYALp=false;
				}				
				else if(($board->isCurrentZoneRoyal==true)&& ($piece->type==13)&&($officer_ROYALp=false)) {
					$officer_ROYALp=false;
				}
				else if(($piece->type==13)&&($officer_ROYALp=true)) {
					$officer_ROYALp=true;
				}			
				else
					$officer_ROYALp=false;

			}
		
			/* End new code added for sleepin p[ieces */
			if($piece->type==13) {$tempstr="SEMIROYAL";} else {$tempstr="ALL";} 
			$officer_ROYALp=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $piece->square, $piece->square, $color_to_move, $board, $tempstr );

				//piece is trapped in palace and has no royal help.
				if( (($piece->type!=13))&& ((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
				||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9))  ))
				{
					if($officer_ROYALp==false)
						return $moves;//
				}

			if($board->isCurrentZoneRoyal==true) {$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;}


			if($piece->controlledpiece==true) $officerp=self::check_uncontrolled_officers_neighbours(self::KING_DIRECTIONS, $piece, $color_to_move, $board, 'exclude',$piece->controlledpiece);
			
		//pawn has no surrounding officer then also it is considered as trapped
		self::check_trapped_piece($piece,$color_to_move, $board,'exclude');
		if(($officerp==false)&&($piece->type==13)){
			//$board->board[ $piece->square->rank][ $piece->square->file]->selftrapped=true;
		}
		
		if((($officerp==TRUE)||($piece->type<13))){

			foreach ( $directions_list as $direction ) {
				$current_xy = self::DIRECTION_OFFSETS[$direction];
				$type=0;

				$ending_square = self::square_exists_and_not_occupied_by_friendly_piece(
					$type,
					'0',
					$piece->square,
					$current_xy[0],
					$current_xy[1],
					$color_to_move,
					$board,
					$cankill,
					FALSE,
					$selfbrokencastle,
					$foebrokencastle
				);
			
				if ( $ending_square ) {
					$capture = FALSE;

					//Spies/Royals and General is required for killing allowed.. Knight logic and General Logic needs to be corrected
					if((($piece->group=="SOLDIER") || ($piece->group=="OFFICER")) && ($piece->color==1) && ($board->arewcommaderssleeping==true)){
								$cankill=0;
							}
					
					if((($piece->group=="SOLDIER") || ($piece->group=="OFFICER")) && ($piece->color==2) && ($board->arebcommaderssleeping==true)){
								$cankill=0;
							}
					/* Check if border breached */
					$CommonBorderOpen_Status = self::get_CommonBorderOpen_Status(	$piece, $color_to_move, $board,$ending_square );
					if(($CommonBorderOpen_Status==0)){
										if (($officer_ROYALp==false) && ($piece->type!=ChessPiece::GODMAN) ){
												continue;
										}
										else if (($officer_ROYALp==true) && ($piece->type!=ChessPiece::GODMAN) ){
											if ($board->board[$ending_square->rank][$ending_square->file]) {
												$CommonBorderOpen_Status=1;
												break;
											}						
											$CommonBorderOpen_Status=1;}
										else if (($piece->type==ChessPiece::GODMAN)){
												$CommonBorderOpen_Status=0;					
											}
									}
	
								/* Check if border breached */

					if ( $board->board[$ending_square->rank][$ending_square->file] ) {
						if ( $board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move ) {
							$capture = TRUE;
						}

						if(($capture == TRUE)&&($board->board[$ending_square->rank][$ending_square->file]->mortal!=1)){
						continue; /** Only Mortals can be killed */
						}
					}
					if($board->gametype>=1){
						if(($ending_square->rank>=0)&&($ending_square->rank<=9)&&(($ending_square->file==0)||($ending_square->file==9))){
							continue;
						}
					}
					if(($board->refugee!=null) && 
					(($board->refugee->square!=null)&& ($board->refugee->square->rank==$ending_square->rank) && ($board->refugee->square->file==$ending_square->file)))
					{
						continue;
					}				

					$enemypushed=false;
					$enemytrapped=false;
					if ($board->board[$ending_square->rank][$ending_square->file]) {
						$enemytrapped=$board->board[$ending_square->rank][$ending_square->file]->selftrapped;
						$enemypushed = $board->board[$ending_square->rank][$ending_square->file]->selfpushed;

						if ($board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move) {
							$capture = false;
							//else if(($piece->group=='SOLDIER') && ($board->board[$ending_square->rank][$ending_square->file]->group=='SOLDIER'))
							//$capture = true;
							if(($piece->type<=$board->board[$ending_square->rank][$ending_square->file]->type)&&($cankill==1))
								{
								$capture = true;
								}
							else if(($officer_ROYALp==true)&&($piece->type>$board->board[$ending_square->rank][$ending_square->file]->type)&&($cankill==1))
								{
								$capture = true;$enemytrapped=true;
								}
							else if(($piece->type>$board->board[$ending_square->rank][$ending_square->file]->type)&&($cankill==1) &&($enemytrapped==true))
								{$capture = true; $enemypushed=false;}//enemytrapped is main condition for junior Strikers
								
							if((($piece->group=='SOLDIER') ||($piece->group=='OFFICER')) && ($piece->neighborgeneral!=null))
								{$capture = true;$enemytrapped=true;}
							else if((($piece->group=='SOLDIER') ||($piece->group=='OFFICER')||(($piece->group=='ROYAL'))) && (($enemytrapped==true)&&($capture==true)))
								{ $capture = true;$enemytrapped=true;	}
							else if((($piece->group=='SOLDIER')  ||($piece->group=='OFFICER'))  && ($enemypushed==false))
								{ continue; }
							}
					}
				
					if ((( $capture )&&($cankill==1))||($enemypushed==true)) {

						if(($enemypushed==true) && ($piece->striker==1)){

								$originalpiece = clone $piece;
								$originalpiece->square->file =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["file"];
								$originalpiece->square->rank =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["rank"];
								$originalpiece->type =  $board->board[$ending_square->rank][$ending_square->file]->type;

								$originalpiece->square->group =  $board->board[$ending_square->rank][$ending_square->file]->group;

								$board->board[$ending_square->rank][$ending_square->file]->state='endcolor:'.$originalpiece->color.',endtype:'.$originalpiece->type.',endmortal:'.$originalpiece->mortal.',endstriker:'.$originalpiece->striker.',endgroup:'.$originalpiece->group.',endnotation:'.$originalpiece->get_fen_symbol().',endrank:'.$originalpiece->square->rank.',endfile:'.$originalpiece->square->file.";";

								$move = new ChessMove(
									$piece->square,	$ending_square,$ending_square,
									0,
									$piece->color,$piece->type,
									false,
									$board,
									$store_board_in_moves,
									FALSE,$controlled_move,$CommonBorderOpen_Status,null
									);
								$moves[] = clone $move;			
							}		
						//If King is holding scepter then no Capture Allowed by non-ROYALs
						if(($capture==TRUE)&&
							(($board->get_king_square($piece->color)->rank==0)&&(($board->get_king_square($piece->color)->file==5) ||  ($board->get_king_square($piece->color)->file==4)) &&($piece->color==1)
							||($board->get_king_square($piece->color)->rank==9)&&(($board->get_king_square($piece->color)->file==5) || ($board->get_king_square($piece->color)->file==4))&&($piece->color==2)))
							{
								continue;
							}
						//If King is holding scepter then no Capture Allowed by non-ROYALs
						if(($capture==TRUE)&& ($piece->type==13) && ($board->get_arthshastri_square($piece->color)==null)){
								continue;
							}
							
						if(($capture==TRUE)&& ($piece->type==13) && ($board->get_arthshastri_square($piece->color)!=null) &&
							(($board->get_arthshastri_square($piece->color)->rank==0)&&(($board->get_arthshastri_square($piece->color)->file==5) || ($board->get_king_square($piece->color)->file==4))&&($piece->color==1)
							||($board->get_arthshastri_square($piece->color)->rank==9)&&(($board->get_arthshastri_square($piece->color)->file==5) || ($board->get_king_square($piece->color)->file==4)) &&($piece->color==2)))
							{
								continue;
							}
							
						if(($capture==TRUE)&&(($ending_square->rank==0)||($ending_square->rank==9)||($ending_square->file==0)||($ending_square->file==9))){
								//Pieces cannot capture the CASTLE or No-Mans location
								continue;
							}
							
						if(($lastaccessiblerow!=-1)&&($color_to_move==2)&&($ending_square->rank<$lastaccessiblerow)){
								continue;
							}
							
						if(($lastaccessiblerow!=-1)&&($color_to_move==1)&&($ending_square->rank>$lastaccessiblerow)){
								continue;
							}
							
						if (( $capture )&&($cankill==1)) {
										$move = new ChessMove(
											$piece->square,	$ending_square,$ending_square,
											0,
											$piece->color, $piece->type,
											$capture,
											$board,
											$store_board_in_moves,
											FALSE,$controlled_move,$CommonBorderOpen_Status,null
										);
								
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
										$move->set_killed_king(TRUE);
								
									$moves[] = clone $move;
								
									if($board->gametype>=2){
										$extractedpawntype="";
										//Added the logic to demote to pawn
										if(($board->board[$ending_square->rank][$ending_square->file]->group=='SEMIROYAL') ||($board->board[$ending_square->rank][$ending_square->file]->group=='OFFICER')){
											$cantamepawn=$board->checkextractselfpawnparity( $board->export_fen(), $piece,$color_to_move,$board);
											if($cantamepawn==true)
												$extractedpawntype="O";
											}
										else if($board->board[$ending_square->rank][$ending_square->file]->group=='SOLDIER'){
												$cantamepawn=$board->checkextractopponentpawnparity( $board->export_fen(), $piece,$color_to_move,$board);
												if($cantamepawn==true)
													$extractedpawntype="S";
											}
										
										//tamedpawn or reuse pawn

										if($cantamepawn==true){
											if(($enemypushed==true) && ($piece->striker==1)&& (($board->board[$ending_square->rank][$ending_square->file]!==null) && 
											($board->board[$ending_square->rank][$ending_square->file]->color!==$piece->color) )){
													$originalpiece = clone $piece;
													$originalpiece->square->file =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["file"];
													$originalpiece->square->rank =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["rank"];
													//$originalpiece->type =  13;	
													//$originalpiece->color =  (abs(3-$piece->color));
													//$originalpiece->group =  'SOLDIER';
													if($piece->color==1) { $originalpiece->color=2; } 
													else if($piece->color==2) { $originalpiece->color=1; }

													$board->board[$ending_square->rank][$ending_square->file]->state='endcolor:'.$originalpiece->color.',endtype:13'.',endmortal:'.$originalpiece->mortal.',endstriker:'.$originalpiece->striker.',endgroup:'.'SOLDIER'.',endnotation:'.$originalpiece->get_fen_symbol().',endrank:'.$originalpiece->square->rank.',endfile:'.$originalpiece->square->file.";";

													$move = new ChessMove(
														$piece->square,
														$ending_square,$ending_square,
														0,
														$piece->color,
														$piece->type,
														false,
														$board,
														$store_board_in_moves,
														FALSE,$controlled_move,$CommonBorderOpen_Status,null
													);
													if(($piece->color==1))  { $move->tamedpawn=1; } //{ if($extractedpawntype=="O") { $move->tamedpawn=1; } else {$move->tamedpawn=2; }}
													if(($piece->color==2))  { $move->tamedpawn=2; } //{ if($extractedpawntype=="O") { $move->tamedpawn=2; } else {$move->tamedpawn=1; }}
													$moves[] = clone $move;
												}
										
												if(($extractedpawntype=="S") && ($piece->striker==1)&& (($board->board[$ending_square->rank][$ending_square->file]!==null) && 
												($board->board[$ending_square->rank][$ending_square->file]->color!==$piece->color) )){
														$board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["file"]=$piece->square->file;
														$board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["rank"]=$piece->square->rank;
												
														$originalpiece->square->file =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["file"];
														$originalpiece->square->rank =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["rank"];

														if(($piece->color==1)) { $originalpiece->color=1; } 
														if(($piece->color==2)) { $originalpiece->color=2; }
														//$originalpiece->square->file =  $board->board[$piece->square->rank][$piece->square->file]->selfpushedsquare["file"];
														//$originalpiece->square->rank =  $board->board[$piece->square->rank][$piece->square->file]->selfpushedsquare["rank"];
														//$board->board[$piece->square->rank][$piece->square->file]->state='endcolor:'.$originalpiece->color.',endtype:13'.',endmortal:'.$originalpiece->mortal.',endstriker:'.$originalpiece->striker.',endgroup:'.'SOLDIER'.',endnotation:'.$originalpiece->get_fen_symbol().',endrank:'.$originalpiece->square->rank.',endfile:'.$originalpiece->square->file.";";
														$board->board[$ending_square->rank][$ending_square->file]->state='endcolor:'.$originalpiece->color.',endtype:13'.',endmortal:'.$originalpiece->mortal.',endstriker:'.$originalpiece->striker.',endgroup:'.'SOLDIER'.',endnotation:'.$originalpiece->get_fen_symbol().',endrank:'.$originalpiece->square->rank.',endfile:'.$originalpiece->square->file.";";
												
														$move = new ChessMove(
															$piece->square,
															$ending_square,$ending_square,
															0,
															$piece->color,
															$piece->type,
															false,
															$board,
															$store_board_in_moves,
															FALSE,$controlled_move,$CommonBorderOpen_Status,null,'R'
														);
													
														if($piece->color==1) { $move->tamedpawn=-1; } 
														if($piece->color==2) { $move->tamedpawn=-2; }
														$moves[] = clone $move;
													}
										}
									}
							}
						}
					}
				}
			}
			return $moves;
		}

	static function add_running_capture_moves_to_moves_list(
		array $moves,
		ChessPiece $piece,
		ChessPiece $endpiece,
		$color_to_move,
		ChessBoard $board,
		bool $store_board_in_moves,
		int $cankill,
		bool $selfbrokencastle,
		bool $foebrokencastle,
		int $CommonBorderOpen_Status=-1
		): array {
			$tempDirection=null;$controlled_move=false;
			$mediatorpiece=null;$originalpiece=null;
			$officerp=TRUE; 
			$mtype=1;//slide //2 jump
			$lastaccessiblerow=-1;
			$tempDirection=self::get_Retreating_ARMY_directions(
				$piece,
				$color_to_move,
				$board,
				$mtype
			);

			if (isset($tempDirection) && is_array($tempDirection)){
				if(!empty($tempDirection))
				$directions_list=$tempDirection;

				$lastaccessiblerow=self::get_LastKing_ArthShashtri_Row(
					$piece,
					$color_to_move,
					$board,
					$mtype
					);
			}
	
			$tempDirection=null;

			if(($piece->square->rank==4)&&($piece->square->file==2)){
				$piece->square->rank;
			}

				$officerp=self::check_officers_neighbours( /**/
					self::KING_DIRECTIONS,
					$piece,
					$color_to_move,
					$board,
					'exclude'
				);
				
			$officerp;
			$enemytrapped=false;
			$originalpiece=null;
			/*if($piece->type==9){
				$ttt=1;	}*/

			//Mediator block exists of same side
			if($piece->state=='V'){
				$board->board[$piece->square->rank][$piece->square->file]=null;

				if($piece->square->mediatorfile<=-1){
					//Depends on Knight Trapped Piece
					$piece->square->mediatorfile=$piece->square->mediatorfile*-1;
				$cankill=3;}
				if ($piece->square->mediatorrank<=-1){
					$piece->square->mediatorrank=$piece->square->mediatorrank*-1;
				$cankill=3;}


				$mediatorpiece=clone $piece;
				$mediatorpiece->square->file=$piece->square->mediatorfile;
				$mediatorpiece->square->rank=$piece->square->mediatorrank;
				$mediatorpiece->square->mediatorfile=null;
				$mediatorpiece->square->mediatorrank=null;

				if($board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]!=null){
					$originalpiece=clone $board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file];
					$originalpiece->state=$originalpiece->state.';V=color:'.$piece->color.',type:'.$piece->type.',mortal:'.$piece->mortal.',striker:'.$piece->striker.',group:'.$piece->group.',notation:'.$piece->get_fen_symbol().',rank:'.$piece->square->mediatorrank.',file:'.$piece->square->mediatorfile.";";
				}
				else if($board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]==null){
					$originalpiece = clone $piece;
					//$originalpiece= clone $board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file];
					$originalpiece->state=$originalpiece->state.'Fake=color:'.$piece->color.',type:'.$piece->type.',mortal:'.$piece->mortal.',striker:'.$piece->striker.',group:'.$piece->group.',notation:'.$piece->get_fen_symbol().',rank:'.$piece->square->mediatorrank.',file:'.$piece->square->mediatorfile.";";
					//$originalpiece->state=$originalpiece->state.'EndPiece=color:'.$endpiece->color.',type:'.$endpiece->type.',mortal:'.$endpiece->mortal.',striker:'.$endpiece->striker.',group:'.$endpiece->group.',notation:'.$endpiece->get_fen_symbol().',rank:'.$endpiece->square->mediatorrank.',file:'.$piece->square->mediatorfile.";";
				}
				else 
					$originalpiece = clone $piece;
				$board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]=$originalpiece;
			}

		//pawn has no surrounding officer then also it is considered as trapped	
		/*if(($piece->square->mediatorfile!=null)  && (($piece->square->mediatorrank!=null) )){
			$piece->square->rank=$piece->square->mediatorrank;
			$piece->square->file=$piece->square->mediatorfile;
		}*/

		self::check_trapped_piece($mediatorpiece,$color_to_move, $board,'exclude');

		if((strpos($piece->state,"V")!==FALSE)&&($endpiece!=null)){

			if(($board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]!=null) &&
			((strpos($board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]->state,"VFake")!==FALSE))
			&&($board->board[$endpiece->square->rank][$endpiece->square->file]!=null) && (
				($board->board[$endpiece->square->rank][$endpiece->square->file]->selfpushed==true ) ))
			{
				$originalpiece = clone $endpiece;
				$originalpiece->square->file =  $board->board[$endpiece->square->rank][$endpiece->square->file]->selfpushedsquare["file"];
				$originalpiece->square->rank =  $board->board[$endpiece->square->rank][$endpiece->square->file]->selfpushedsquare["rank"];
				$originalpiece->state=$board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]->state.'endcolor:'.$originalpiece->color.',endtype:'.$originalpiece->type.',endmortal:'.$originalpiece->mortal.',endstriker:'.$originalpiece->striker.',endgroup:'.$originalpiece->group.',endnotation:'.$originalpiece->get_fen_symbol().',endrank:'.$originalpiece->square->rank.',endfile:'.$originalpiece->square->file.";";

				$board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]=$originalpiece;
			}
		}

		if(($officerp==false)&&($piece->type==13)){
			//$board->board[ $piece->square->rank][ $piece->square->file]->selftrapped=true;
		}
	
		if(($officerp==TRUE)||($piece->type<13)){
			$ending_square= $endpiece->square;

				if ( $ending_square ) {
					$capture = FALSE;
				
					if ( $board->board[$ending_square->rank][$ending_square->file] ) {
						if ( $board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move ) {
							$capture = TRUE;
						}

						if(($capture == TRUE)&&($board->board[$ending_square->rank][$ending_square->file]->mortal!=1)){
							return $moves;
						}
					}
					if($board->gametype>=1){
						if(($ending_square->rank>=0)&&($ending_square->rank<=9)&&(($ending_square->file==0)||($ending_square->file==9))){
							return $moves;
						}
					}

					$enemypushed=false;
					$enemytrapped=false;
					if ($board->board[$ending_square->rank][$ending_square->file]) {
						$enemytrapped=$board->board[$ending_square->rank][$ending_square->file]->selftrapped;
						$enemypushed = $board->board[$ending_square->rank][$ending_square->file]->selfpushed;

						if(($enemytrapped==true) && ($cankill==3)) $cankill=1;
						if ($board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move) {				
							$capture = false;
							//else if(($piece->group=='SOLDIER') && ($board->board[$ending_square->rank][$ending_square->file]->group=='SOLDIER'))
							//$capture = true;
							if(($piece->type<=$board->board[$ending_square->rank][$ending_square->file]->type)&&($cankill==1))
								{
								$capture = true;
								}
							//Royal/Semi-Royal/General touch elevates	
							else if((($piece->group=='SOLDIER') ||($piece->group=='OFFICER')) && (($officerp==TRUE))){
								$capture = true;
							}
							else if(($piece->elevatedofficer==true)&&($piece->type>$board->board[$ending_square->rank][$ending_square->file]->type)&&($cankill==1))
								{
									$capture = true;$enemypushed=true;$enemytrapped=true;
								}
							else if(($piece->type>$board->board[$ending_square->rank][$ending_square->file]->type)&&($cankill==1) &&($enemytrapped==true))
								{$capture = true; $enemypushed=false;}//enemytrapped is main condition for junior Strikers
						
							if((($piece->group=='SOLDIER') ||($piece->group=='OFFICER')) && ($piece->neighborgeneral!=null))
								{$capture = true;$enemytrapped=true;}
							else if((($piece->group=='SOLDIER') ||($piece->group=='OFFICER')||(($piece->group=='ROYAL'))) && (($enemytrapped==true)&&($capture==true)))
								{ $capture = true;$enemytrapped=true;  }
							else if((($piece->group=='SOLDIER')  ||($piece->group=='OFFICER'))  && ($enemypushed==false))
								{ return $moves; }
							}
					}
				
					if ((( $capture )&&($cankill==1))||($enemypushed==true)) {

						if(($enemypushed==true) && ($piece->striker==1)){
							$move = new ChessMove(
								$piece->square,
								$ending_square,$ending_square,
								0,
								$piece->color,
								$piece->type,
								false,
								$board,
								$store_board_in_moves,
								FALSE,$controlled_move,$CommonBorderOpen_Status,null
							);
							$moves[] = clone $move;
							$move=null;
						}

						//If King is holding scepter then no Capture Allowed by non-ROYALs
						if(($capture==TRUE)&& ($board->get_king_square($piece->color)!=null) &&
						(($board->get_king_square($piece->color)->rank==0)&&($board->get_king_square($piece->color)->file==4)&&($piece->color==1)
						||($board->get_king_square($piece->color)->rank==9)&&($board->get_king_square($piece->color)->file==5)&&($piece->color==2)))
						{
							return $moves;;
						}
				
						if(($capture==TRUE)&&(($ending_square->rank==0)||($ending_square->rank==9)||($ending_square->file==0)||($ending_square->file==9))){
							//Pieces cannot capture the CASTLE or No-Mans location
							return $moves;;
						}

						if(($lastaccessiblerow!=-1)&&($color_to_move==2)&&($ending_square->rank<$lastaccessiblerow)){
							return $moves;;
						}

						if(($lastaccessiblerow!=-1)&&($color_to_move==1)&&($ending_square->rank>$lastaccessiblerow)){
							return $moves;;
						}

						if (( $capture )&&($cankill==1))
							{

							$move = new ChessMove(
								$piece->square,
								$ending_square,$ending_square,
								0,
								$piece->color,
								$piece->type,
								$capture,
								$board,
								$store_board_in_moves,
								FALSE,$controlled_move,$CommonBorderOpen_Status,null
							);

							//check if the king is killed
							if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
								$move->set_killed_king(TRUE);

							$moves[] = clone $move;
							if($board->gametype>=2){
									$extractedpawntype="";
									//Added the logic to demote to pawn
									if(($board->board[$ending_square->rank][$ending_square->file]->group=='SEMIROYAL') ||($board->board[$ending_square->rank][$ending_square->file]->group=='OFFICER')){
										$cantamepawn=$board->checkextractselfpawnparity( $board->export_fen(), $piece,$color_to_move,$board);
										if($cantamepawn==true)
											$extractedpawntype="O";
										}
									else if($board->board[$ending_square->rank][$ending_square->file]->group=='SOLDIER'){
											$cantamepawn=$board->checkextractopponentpawnparity( $board->export_fen(), $piece,$color_to_move,$board);
											if($cantamepawn==true)
												$extractedpawntype="S";
										}

									if($cantamepawn==true){
										if(($enemypushed==true) && ($piece->striker==1)&& (($board->board[$ending_square->rank][$ending_square->file]!==null) && 
											($board->board[$ending_square->rank][$ending_square->file]->color!==$piece->color) )){
											
												$originalpiece = clone $piece;
												$originalpiece->square->file =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["file"];
												$originalpiece->square->rank =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["rank"];
												$originalpiece->type =  13;
												$originalpiece->group =  'SOLDIER';

												$board->board[$ending_square->rank][$ending_square->file]->state='endcolor:'.$originalpiece->color.',endtype:13'.',endmortal:'.$originalpiece->mortal.',endstriker:'.$originalpiece->striker.',endgroup:'.$originalpiece->group.',endnotation:'.$originalpiece->get_fen_symbol().',endrank:'.$originalpiece->square->rank.',endfile:'.$originalpiece->square->file.";";

												$move = new ChessMove(
													$piece->square,
													$ending_square,$ending_square,
													0,
													$piece->color,
													$piece->type,
													false,
													$board,
													$store_board_in_moves,
													FALSE,$controlled_move,$CommonBorderOpen_Status,null
												);
												if(($piece->color==1))  { $move->tamedpawn=1; } //{ if($extractedpawntype=="O")  { $move->tamedpawn=1; }{$move->tamedpawn=2;} else {$move->tamedpawn=1; }}
												else if(($piece->color==2))  { $move->tamedpawn=2; } //{ if($extractedpawntype=="O") {$move->tamedpawn=1;}  else {$move->tamedpawn=2;} }

											$moves[] = clone $move;
											}
										if(($extractedpawntype=="S")&&($piece->striker==1)&& ($board->board[$ending_square->rank][$ending_square->file]===null )){
												//Sample Code to pull the opponent//middlemen
												//intermediate has issues when single jump 
												$originalpiece = clone $piece;
												$board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["file"]=$piece->square->mediatorfile;
												$board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["rank"]=$piece->square->mediatorrank;
												$ending_square->mediatorfile=$piece->square->mediatorfile;
												$ending_square->mediatorrank=$piece->square->mediatorrank;

												$originalpiece->square->file =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["file"];
												$originalpiece->square->rank =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["rank"];
												//$originalpiece->square->file =  $board->board[$piece->square->rank][$piece->square->file]->selfpushedsquare["file"];
												//$originalpiece->square->rank =  $board->board[$piece->square->rank][$piece->square->file]->selfpushedsquare["rank"];
												//$board->board[$piece->square->rank][$piece->square->file]->state='endcolor:'.$originalpiece->color.',endtype:13'.',endmortal:'.$originalpiece->mortal.',endstriker:'.$originalpiece->striker.',endgroup:'.'SOLDIER'.',endnotation:'.$originalpiece->get_fen_symbol().',endrank:'.$originalpiece->square->rank.',endfile:'.$originalpiece->square->file.";";
												$board->board[$ending_square->rank][$ending_square->file]->state='endcolor:'.$originalpiece->color.',endtype:13'.',endmortal:'.$originalpiece->mortal.',endstriker:'.$originalpiece->striker.',endgroup:'.'SOLDIER'.',endnotation:'.$originalpiece->get_fen_symbol().',endrank:'.$originalpiece->square->rank.',endfile:'.$originalpiece->square->file.";";

												$move = new ChessMove(
													$piece->square,
													$ending_square,$ending_square,
													0,
													$piece->color,
													$piece->type,
													false,
													$board,
													$store_board_in_moves,
													FALSE,$controlled_move,$CommonBorderOpen_Status,null,'R'
												);											
											
												if($piece->color==1) { $move->tamedpawn=-1; } 
												if($piece->color==2) { $move->tamedpawn=-2; } 
												$moves[] = clone $move;
											}
									}
							}	
						}
					}
				}
		}
		
		return $moves;
	}


	static function add_reuse_pawns_slide_moves_to_moves_list(
		array $directions_list,
		int $spaces,
		array $moves,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		bool $store_board_in_moves,
		int $cankill,
		bool $selfbrokencastle,
		bool $foebrokencastle
	): array {
			//**echo '<li> ChessRuleBook.php #6 function add_slide_moves_to_moves_list called </li>';
			$lastaccessiblerow=-1;
			$cankill=0; $capture = false;
			$controlled_move=false;
			$CommonBorderOpen_Status=-1;
			$officer_ROYALp=false;
			if($piece->type!=13) {return null;}

			if($board->isCurrentZoneRoyal==true) {$officer_ROYALp=true;}
		
				foreach ($directions_list as $direction) {
					for ($i = 1; $i <= $spaces; $i++) {
						$current_xy = self::DIRECTION_OFFSETS[$direction];
						$current_xy[0] *= $i;
						$current_xy[1] *= $i;
						$type=0;
						$ending_square = self::square_exists_and_not_occupied_by_friendly_piece(
							$type, '0', $piece->square, $current_xy[0], $current_xy[1], $color_to_move,
							$board, $cankill, FALSE, $selfbrokencastle, $foebrokencastle );
				
						if (! $ending_square) {
							// square does not exist, or square occupied by friendly piece
							// stop sliding
							break;
						}				

						/* Check if border breached */
						$CommonBorderOpen_Status = self::get_CommonBorderOpen_Status(	$piece, $color_to_move, $board,$ending_square );
								if(($CommonBorderOpen_Status==0)){
										if (($officer_ROYALp==false) && ($piece->type!=ChessPiece::GODMAN) ){
												continue;
										}
										else if (($officer_ROYALp==true) && ($piece->type!=ChessPiece::GODMAN) ){
											if ($board->board[$ending_square->rank][$ending_square->file]) {
												$CommonBorderOpen_Status=1;
												break;
											}
											$CommonBorderOpen_Status=1;}
										else if (($piece->type==ChessPiece::GODMAN)){
												$CommonBorderOpen_Status=0;					
											}
									}
	
								/* Check if border breached */	
			
						if($board->gametype>=1){
							if(($ending_square->rank>=0)&&($ending_square->rank<=9)&&(($ending_square->file==0)||($ending_square->file==9))){
								continue;
							}
						}

						if(($lastaccessiblerow!=-1)&&($color_to_move==2)&&($ending_square->rank<$lastaccessiblerow)){
							continue;
						}

						if(($lastaccessiblerow!=-1)&&($color_to_move==1)&&($ending_square->rank>$lastaccessiblerow)){
							continue;
						}

						if ((($ending_square->rank==0)||($ending_square->rank==9))&&(($ending_square->file>0)&&($ending_square->file<9))) {
							continue;
						}

						$new_move = new ChessMove(
							$piece->square, $ending_square,$ending_square, 	0,
							$piece->color, $piece->type,
							$capture, $board, $store_board_in_moves,
							FALSE,$controlled_move,$CommonBorderOpen_Status,null
						);

						$moves[] = $new_move;
					}
				}
			
		return $moves;
		}


	static function add_slide_moves_to_moves_list(
		array $directions_list,
		int $spaces,
		array $moves,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		bool $store_board_in_moves,
		int $cankill,
		bool $selfbrokencastle,
		bool $foebrokencastle
	): array {
			//**echo '<li> ChessRuleBook.php #6 function add_slide_moves_to_moves_list called </li>';
			$tempDirection=null;
			$lastaccessiblerow=-1;
			$controlled_move=false;
			$CommonBorderOpen_Status=-1;
			$mtype=1;//slide //2 jump
			$officer_ROYALp=false;
			$tempstr="";

			$officer_ROYALp=false;
			if($piece->type==13) {$tempstr="SEMIROYAL";} else {$tempstr="ALL";} 
			$officer_ROYALp=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $piece->square, $piece->square, $color_to_move, $board, $tempstr );

				//piece is trapped in palace and has no royal help.
				if((($piece->type==13))&&  ((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
				||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9))  ))
				{
					if($officer_ROYALp==false)
						return $moves;//
				}

			//Create the Array of Move Types.. This will help in deciding the two types of moves in retrating.. Moving back and to the top border
			$tempDirection=self::get_Retreating_ARMY_directions(
				$piece,
				$color_to_move,
				$board,
				$mtype
			);

			if (isset($tempDirection) && is_array($tempDirection)){
				if(!empty($tempDirection))
				$directions_list=$tempDirection;

				$lastaccessiblerow=self::get_LastKing_ArthShashtri_Row(
					$piece,
					$color_to_move,
					$board,
					$mtype
					);
			}
			
			if(($piece->group=='SOLDIER')){
	
				$GENERALZONEPUSHER=self::check_general_push_demotion( /**/
					self::KING_DIRECTIONS,
					$piece,
					$color_to_move,
					$board
					);
			}	
			$tempDirection=null; $officerp=TRUE; 

			if(($piece->square->rank==8)&&($piece->square->file==0)){
				$piece->square->rank;
			}

			$officerp=self::check_officers_neighbours( self::KING_DIRECTIONS, $piece, $color_to_move, $board, 'include');

			self::check_trapped_piece($piece,$color_to_move, $board,'exclude');

			/* Start new code added for sleepin p[ieces */

			//Pieces inside CASTLE become Semi-Royal for the time-being.
			if(((($piece->square->file>=1) &&($piece->square->file<=3)) || (($piece->square->file>=6) &&($piece->square->file<=8)))  && 
			((($piece->square->rank==0) && ($color_to_move==1)) || ($piece->square->rank==9)  && ($color_to_move==2)))
			{ 
				if(($board->isCurrentZoneRoyal==true)&& ($piece->type!=13)) {
					//$ROYALp=$ROYAL_ROYALp=true;
					$officer_ROYALp=true;
				}
				else if(($board->isCurrentZoneRoyal==false)&& ($piece->type==13)) {
					$officer_ROYALp=false;
				}				
				else if(($board->isCurrentZoneRoyal==true)&& ($piece->type==13)&&($officer_ROYALp=false)) {
					$officer_ROYALp=false;
				}
				else if(($piece->type==13)&&($officer_ROYALp=true)) {
					$officer_ROYALp=true;
				}			
				else
					$officer_ROYALp=false;

			}
		
			//piece is trapped in palace and has no royal help.
			if(  ((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
			||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9))  ))
			{
				if($officer_ROYALp==false)
					return $moves;//
			}

			/* End new code added for sleepin p[ieces */

			if($piece->type==13) {$tempstr="SEMIROYAL";} else {$tempstr="ALL";} 
			$officer_ROYALp=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $piece->square, $piece->square, $color_to_move, $board, $tempstr );

				//piece is trapped in palace and has no royal help.
				if( (($piece->type!=13))&& ((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
				||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9))  ))
				{
					if($officer_ROYALp==false)
						return $moves;//
				}

			if($board->isCurrentZoneRoyal==true) {$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;}
		
			if (($officerp==true) ) {
				foreach ($directions_list as $direction) {
					for ($i = 1; $i <= $spaces; $i++) {
						$current_xy = self::DIRECTION_OFFSETS[$direction];
						$current_xy[0] *= $i;
						$current_xy[1] *= $i;
						$type=0;
						$ending_square = self::square_exists_and_not_occupied_by_friendly_piece(
							$type,
							'0',
							$piece->square,
							$current_xy[0],
							$current_xy[1],
							$color_to_move,
							$board,
							$cankill,
							FALSE,
							$selfbrokencastle,
							$foebrokencastle
						);
				
						if (! $ending_square) {
							// square does not exist, or square occupied by friendly piece
							// stop sliding
							break;
						}
				
								//Spies/Royals and General is required for killing allowed.. Knight logic and General Logic needs to be corrected
								if((($piece->group=="SOLDIER") || ($piece->group=="OFFICER")) && ($piece->color==1) && ($board->arewcommaderssleeping==true)){
										$cankill=0;
									}
					
								if((($piece->group=="SOLDIER") || ($piece->group=="OFFICER")) && ($piece->color==2) && ($board->arebcommaderssleeping==true)){
										$cankill=0;
									}

								/* Check if border breached */
								$CommonBorderOpen_Status = self::get_CommonBorderOpen_Status(	$piece, $color_to_move, $board,$ending_square );
								if(($CommonBorderOpen_Status==0)){
										if (($officer_ROYALp==false) && ($piece->type!=ChessPiece::GODMAN) ){
												continue;
										}
										else if (($officer_ROYALp==true) && ($piece->type!=ChessPiece::GODMAN) ){
											if ($board->board[$ending_square->rank][$ending_square->file]) {
												$CommonBorderOpen_Status=1;
												break;
											}
											$CommonBorderOpen_Status=1;}
										else if (($piece->type==ChessPiece::GODMAN)){
												$CommonBorderOpen_Status=0;					
											}
									}
	
								/* Check if border breached */	

						$capture = false; $enemypushed=false; $enemytrapped=false;
						if ($board->board[$ending_square->rank][$ending_square->file]) {
							$enemytrapped=$board->board[$ending_square->rank][$ending_square->file]->selftrapped;
							$enemypushed = $board->board[$ending_square->rank][$ending_square->file]->selfpushed;
	
							if ($board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move) {				
								$capture = false;
								//else if(($piece->group=='SOLDIER') && ($board->board[$ending_square->rank][$ending_square->file]->group=='SOLDIER'))
								//$capture = true;
								if(($piece->type<=$board->board[$ending_square->rank][$ending_square->file]->type)&&($cankill==1))
									{
									$capture = true;
									break;
									}
								else if(($piece->type>$board->board[$ending_square->rank][$ending_square->file]->type)&&($cankill==1) &&($enemytrapped==true))
									{$capture = true; break;
									}//enemytrapped is main condition for junior Strikers
									
								if(($piece->group=='SOLDIER') && ($piece->neighborgeneral!=null))
									{$capture = true;$enemypushed=true;$enemytrapped=true;}
								else if(($piece->group=='SOLDIER') && (($enemytrapped==true)&&($capture==true)))
									{ $capture = true;$enemytrapped=true;$enemypushed=true;}
								else if(($piece->group=='SOLDIER') && (($enemytrapped==false)&&($enemypushed==true)))
									{ $capture = true;$enemytrapped=false;$enemypushed=true;}								
								else if((($piece->group=='SOLDIER'))  && ($enemypushed==false))
									{ continue; }
								}
						}

						if ($board->board[$ending_square->rank][$ending_square->file]) {
							if ($board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move) {

								if(($piece->group=='SOLDIER') && ($board->board[$ending_square->rank][$ending_square->file]->group=='SOLDIER'))
									$capture = true;

								if(($piece->group=='SOLDIER') && (($enemytrapped==true)||($capture==true)))
									{ $capture = true;$enemytrapped=false; }
								else if(($piece->group=='SOLDIER') && ($enemytrapped==false))
									{ continue; }
							}
						}
				
						if($board->gametype>=1){
							if(($ending_square->rank>=0)&&($ending_square->rank<=9)&&(($ending_square->file==0)||($ending_square->file==9))){
								continue;
							}
						}

						if($enemypushed==true)
						{
								$originalpiece = clone $piece;
								$originalpiece->square->file =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["file"];
								$originalpiece->square->rank =  $board->board[$ending_square->rank][$ending_square->file]->selfpushedsquare["rank"];
								$originalpiece->type =  $board->board[$ending_square->rank][$ending_square->file]->type;

								$originalpiece->square->group =  $board->board[$ending_square->rank][$ending_square->file]->group;

								$board->board[$ending_square->rank][$ending_square->file]->state='endcolor:'.$originalpiece->color.',endtype:'.$originalpiece->type.',endmortal:'.$originalpiece->mortal.',endstriker:'.$originalpiece->striker.',endgroup:'.$originalpiece->group.',endnotation:'.$originalpiece->get_fen_symbol().',endrank:'.$originalpiece->square->rank.',endfile:'.$originalpiece->square->file.";";

								$move = new ChessMove(
									$piece->square,
									$ending_square,$ending_square,
									0,
									$piece->color,
									$piece->type,
									false,
									$board,
									$store_board_in_moves,
									FALSE,$controlled_move,$CommonBorderOpen_Status,null
								);
								$moves[] = clone $move;
						}

						if ($capture) {
							// enemy piece in square
							// stop sliding
							break;
						}

						if(($lastaccessiblerow!=-1)&&($color_to_move==2)&&($ending_square->rank<$lastaccessiblerow)){
							continue;
						}

						if(($lastaccessiblerow!=-1)&&($color_to_move==1)&&($ending_square->rank>$lastaccessiblerow)){
							continue;
						}

						if ((($ending_square->rank==0)||($ending_square->rank==9))&&(($ending_square->file>0)&&($ending_square->file<9))) {
							continue;
						}

						$new_move = new ChessMove(
							$piece->square,
							$ending_square,$ending_square,
							0,
							$piece->color,
							$piece->type,
							$capture,
							$board,
							$store_board_in_moves,
							FALSE,$controlled_move,$CommonBorderOpen_Status,null
						);
		
						//check if the king is killed
						if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
								$new_move->set_killed_king(TRUE);
						$moves[] = $new_move;
					}
				}
			}
		return $moves;
		}

	static function add_jump_and_jumpcapture_moves_to_moves_list(
		int $type, 
		$jumpstyle,
		array $oclock_list,
		array $moves,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		bool $store_board_in_moves,
		int $cankill,
		int $canbepromoted,
		bool $get_FullMover,
		bool $selfbrokencastle,
		bool $foebrokencastle,
		int $get_CASTLEMover,
		bool $controlled_move
	): array 
	{
		//SEMIROYAL  SELF PROMOTION, TOUCH PROMOTION Not done yet
		//breakpointer
		$tempDirection=null;
		$mtype=2;//slide //2 jump
		$lastaccessiblerow=-1;
		$canpromote=false;
		$CommonBorderOpen_Status=-1;

		$ROYAL_ROYALp=self::has_ROYAL_neighbours( self::KING_DIRECTIONS, $piece->square, $piece->square, $color_to_move, $board );



			//piece is trapped in palace and has no royal help.
			if(  ((($piece->square->rank==0) || ($piece->square->rank==9)) && (($piece->square->file==4) || ($piece->square->file==5)) )
			||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==0) || ($piece->square->file==9))  ))
			{
				$board->board[$piece->square->rank] [$piece->square->file]->awake=false;

				if( (($piece->square->rank==0)&&($piece->square->file==4) && ($board->board[0][3]!=null) && (($board->board[0][3]->type==ChessPiece::GENERAL)  || ($board->board[0][3]->group=='ROYAL') ||($board->board[0][3]->group=='SEMIROYAL')) ) ||
				(($piece->square->rank==0)&&($piece->square->file==5) && ($board->board[0][6]!=null) && (($board->board[0][6]->type==ChessPiece::GENERAL)  || ($board->board[0][6]->group=='ROYAL') ||($board->board[0][6]->group=='SEMIROYAL')) )||
				(($piece->square->rank==9)&&($piece->square->file==4) && ($board->board[9][3]!=null) && (($board->board[9][3]->type==ChessPiece::GENERAL) || ($board->board[9][3]->group=='ROYAL') ||($board->board[9][3]->group=='SEMIROYAL')) )||
				(($piece->square->rank==9)&&($piece->square->file==5) && ($board->board[9][6]!=null) && (($board->board[9][6]->type==ChessPiece::GENERAL) || ($board->board[9][6]->group=='ROYAL') ||($board->board[9][6]->group=='SEMIROYAL')) )||

				(($piece->square->file==0)&&($piece->square->rank==4) && ($board->board[3][0]!=null) && (($board->board[3][0]->type==ChessPiece::GENERAL) || ($board->board[3][0]->group=='ROYAL') ||($board->board[3][0]->group=='SEMIROYAL')) ) ||
				(($piece->square->file==0)&&($piece->square->rank==5) && ($board->board[6][0]!=null) && (($board->board[6][0]->type==ChessPiece::GENERAL) || ($board->board[6][0]->group=='ROYAL') ||($board->board[6][0]->group=='SEMIROYAL')) )||
				(($piece->square->file==9)&&($piece->square->rank==4) && ($board->board[3][9]!=null) && (($board->board[3][9]->type==ChessPiece::GENERAL) || ($board->board[3][9]->group=='ROYAL') ||($board->board[3][9]->group=='SEMIROYAL')) )||
				(($piece->square->file==9)&&($piece->square->rank==5) && ($board->board[6][9]!=null) && (($board->board[6][9]->type==ChessPiece::GENERAL) || ($board->board[6][9]->group=='ROYAL') ||($board->board[6][9]->group=='SEMIROYAL')) )
									
				){
					$board->board[$piece->square->rank] [$piece->square->file]->awake=true;
				}
				else if(($ROYAL_ROYALp==false))
				{
					return $moves;//

				}
			}

			else if(  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==4) || ($piece->square->file==5)) )
			||  ((($piece->square->rank==4) || ($piece->square->rank==5)) && (($piece->square->file==4) || ($piece->square->file==5))  ))
			{

				//add the logic to check if the warzone already broken palaces. commonborder
				$board->board[$piece->square->rank] [$piece->square->file]->awake=false;

				if( (($piece->square->rank==4)&&($piece->square->file==4) && ( (($board->board[4][3]!==null) && ( ($board->board[4][3]->type==ChessPiece::GENERAL) ||  ($board->board[4][3]->group=='ROYAL') ||($board->board[4][3]->group=='SEMIROYAL') ))||
				(($board->board[3][5]!==null) && ( ($board->board[3][5]->type==ChessPiece::GENERAL) ||  ($board->board[3][5]->group=='ROYAL') ||($board->board[3][5]->group=='SEMIROYAL') ))||
				(($board->board[3][3]!==null) && ( ($board->board[3][3]->type==ChessPiece::GENERAL) ||  ($board->board[3][3]->group=='ROYAL') ||($board->board[3][3]->group=='SEMIROYAL') ))||
				(($board->board[3][4]!==null) && ( ($board->board[3][4]->type==ChessPiece::GENERAL) ||  ($board->board[3][4]->group=='ROYAL') ||($board->board[3][4]->group=='SEMIROYAL') ))
				)) ||

				(($piece->square->rank==4)&&($piece->square->file==5) && ( (($board->board[4][6]!==null) && ( ($board->board[4][6]->type==ChessPiece::GENERAL) ||  ($board->board[4][6]->group=='ROYAL') ||($board->board[4][6]->group=='SEMIROYAL') ))||
				(($board->board[3][6]!==null) && ( ($board->board[3][6]->type==ChessPiece::GENERAL) ||  ($board->board[3][6]->group=='ROYAL') ||($board->board[3][6]->group=='SEMIROYAL') )) ||
				(($board->board[3][5]!==null) && ( ($board->board[3][5]->type==ChessPiece::GENERAL) ||  ($board->board[3][5]->group=='ROYAL') ||($board->board[3][5]->group=='SEMIROYAL') )) ||
				(($board->board[3][4]!==null) && ( ($board->board[3][4]->type==ChessPiece::GENERAL) ||  ($board->board[3][4]->group=='ROYAL') ||($board->board[3][4]->group=='SEMIROYAL') ))

				)) ||

				(($piece->square->rank==5)&&($piece->square->file==4) && ( (($board->board[5][3]!==null) && ( ($board->board[5][3]->type==ChessPiece::GENERAL) ||  ($board->board[5][3]->group=='ROYAL') ||($board->board[5][3]->group=='SEMIROYAL') ))||
				(($board->board[6][3]!==null) && ( ($board->board[6][3]->type==ChessPiece::GENERAL) ||  ($board->board[6][3]->group=='ROYAL') ||($board->board[6][3]->group=='SEMIROYAL') ))||
				(($board->board[6][4]!==null) && ( ($board->board[6][4]->type==ChessPiece::GENERAL) ||  ($board->board[6][4]->group=='ROYAL') ||($board->board[6][4]->group=='SEMIROYAL') ))||
				(($board->board[6][4]!==null) && ( ($board->board[6][5]->type==ChessPiece::GENERAL) ||  ($board->board[6][5]->group=='ROYAL') ||($board->board[6][5]->group=='SEMIROYAL') ))

				)) ||

				(($piece->square->rank==5)&&($piece->square->file==5) && ( (($board->board[6][4]!==null) && ( ($board->board[6][4]->type==ChessPiece::GENERAL) ||  ($board->board[6][4]->group=='ROYAL') ||($board->board[6][4]->group=='SEMIROYAL') ))||
				(($board->board[6][5]!==null) && ( ($board->board[6][5]->type==ChessPiece::GENERAL) ||  ($board->board[6][5]->group=='ROYAL') ||($board->board[6][5]->group=='SEMIROYAL') ))||
				(($board->board[6][6]!==null) && ( ($board->board[6][6]->type==ChessPiece::GENERAL) ||  ($board->board[6][6]->group=='ROYAL') ||($board->board[6][6]->group=='SEMIROYAL') ))||
				(($board->board[5][6]!==null) && ( ($board->board[5][6]->type==ChessPiece::GENERAL) ||  ($board->board[5][6]->group=='ROYAL') ||($board->board[5][6]->group=='SEMIROYAL') ))
				))){
					$board->board[$piece->square->rank] [$piece->square->file]->awake=true;
					$ROYAL_ROYALp=true;
				}

				if ((( $board->commonborderbreached == true ) && ( $board->CommonBorderOpen_Status==1)) &&
				( ($piece->type!=ChessPiece::PAWN)&&(($piece->square->rank==4)&&($piece->square->file==4) && 
				((($board->board[5][3]!==null) && ( ($board->board[5][3]->type==ChessPiece::GENERAL) ||  ($board->board[5][3]->group=='ROYAL') ||($board->board[5][3]->group=='SEMIROYAL') ))
				)) ||

				(($piece->square->rank==4)&&($piece->square->file==5) && 
				( (($board->board[5][6]!==null) && ( ($board->board[4][6]->type==ChessPiece::GENERAL) ||  ($board->board[5][6]->group=='ROYAL') ||($board->board[5][6]->group=='SEMIROYAL') ))		
				)) ||

				(($piece->square->rank==5)&&($piece->square->file==4) && 
				( (($board->board[4][3]!==null) && ( ($board->board[4][3]->type==ChessPiece::GENERAL) ||  ($board->board[4][3]->group=='ROYAL') ||($board->board[5][3]->group=='SEMIROYAL') ))		
				)) ||

				(($piece->square->rank==5)&&($piece->square->file==5) && 
				( (($board->board[4][6]!==null) && ( ($board->board[4][6]->type==ChessPiece::GENERAL) ||  ($board->board[4][6]->group=='ROYAL') ||($board->board[6][4]->group=='SEMIROYAL') ))
				)))){
					$board->board[$piece->square->rank] [$piece->square->file]->awake=true;
						$ROYAL_ROYALp=true;
				}	
				else if(($ROYAL_ROYALp==false))
				{
					return $moves;//
				}
			}

		//Create the Array of Move Types.. This will help in deciding the two types of moves in retrating.. Moving back and to the top border
		$tempDirection=self::get_Retreating_ARMY_directions($piece,$color_to_move,$board,	$mtype);

		if (isset($tempDirection) && is_array($tempDirection)){
			if(!empty($tempDirection)){
				$oclock_list=$tempDirection;
			}
			
			$lastaccessiblerow=self::get_LastKing_ArthShashtri_Row($piece,$color_to_move,$board,$mtype);
	
			if(($piece->type==ChessPiece::KNIGHT)||($piece->type==ChessPiece::GENERAL)){
				$tempDirection=self::get_corrected_Retreating_Knight_General_directions($piece,$color_to_move,$board,$mtype,$lastaccessiblerow,$tempDirection);
					if (isset($tempDirection) && is_array($tempDirection)){
							if(!empty($tempDirection)){
									$oclock_list=$tempDirection;
								}
						}
				}
		}

		$tempDirection=null; $booljump=TRUE; $ROYALp=FALSE; $cancapture=True; $candemote=FALSE; $capture = FALSE;
		$dem=0; $officer_ROYALp=FALSE;

		if($get_FullMover==false)
			$booljump=false;
		else
			$booljump=true;
		if($board->gametype==4)
			$booljump=self::check_ROYAL_neighbours(self::KING_DIRECTIONS, $piece, $color_to_move, $board, "Zone");

		if($board->isCurrentZoneRoyal==true) {
			$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;}

			
		/* Start new code added for sleepin p[ieces */

		//Pieces inside CASTLE become Semi-Royal for the time-being.
		if(((($piece->square->file>=1) &&($piece->square->file<=3)) || (($piece->square->file>=6) &&($piece->square->file<=8)))  && 
		((($piece->square->rank==0) && ($color_to_move==1)) || ($piece->square->rank==9)  && ($color_to_move==2))){ 
				if($board->isCurrentZoneRoyal==true){
					$ROYALp=$ROYAL_ROYALp=true;
				}
				else 
				$ROYAL_ROYALp=false;

			}

			/* End new code added for sleepin p[ieces */

		if ($type==2) {// Check if ROYAL has ROYALs. if not then cannot jump;

			//can jump within own castle

			///Add the logic to Check the Merged Zones or Merged Castle or Singe CASTLE

			//If there are no Royal_touch etc.
			if (( $board->iswcZoneRoyal==true) && ($piece->square->rank==0) )  {
				$ROYALp=true;
				$booljump=true;
				}	

			else if (( $board->isbcZoneRoyal==true) && ($piece->square->rank==9) )  {
					$ROYALp=true;
					$booljump=true;
				}
			else
			$ROYALp=$booljump;
			//if Palace is not opponent Castle then good.. otherwise not good
			if((($piece->square->rank>=3)&&($piece->square->rank<=6)) &&(($piece->square->file==0)||($piece->square->file==9)))
			{
				//Truce Palace captured by Opponent
				if(($piece->square->rank==3) && ($piece->color==1) && ( 
					(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==2))
				    ||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==2))
				)){

				}
				else if(($piece->square->rank==6) && ($piece->color==1) && (
					(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==2))
				||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==2))
				)){

				}
				else if(($piece->square->rank==3) && ($piece->color==2) && ( 
					(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==1))
				||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==1))
				)){

				}
				else if(($piece->square->rank==6) && ($piece->color==2) && ( 
					(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==1))
				||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==1))
				)){

				}
				else if(($piece->square->rank==3) && ( 
					(($piece->square->file==0) && ($board->board[4][0]==null))
				||(($piece->square->file==9) && ($board->board[4][9]==null))
				)){

				}
				else if(($piece->square->rank==6) && ( 
					(($piece->square->file==0) && ($board->board[5][0]=null))
				||(($piece->square->file==9) && ($board->board[5][9]!=null))
				)){

				}
				else if(($piece->square->rank==3) && ( 
					(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group=='NOBLE'))
				||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group=='NOBLE'))
				)){

				}
				else if(($piece->square->rank==6) && ( 
					(($piece->square->file==0) &&  ($board->board[5][0]!=null)&&($board->board[5][0]->group=='NOBLE'))
				||(($piece->square->file==9) &&  ($board->board[5][9]!=null)&&($board->board[5][9]->group=='NOBLE'))
				)){

				}				
				else
					{$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;}

			}			

			if(($ROYALp==false)&&($piece->group=='SEMIROYAL'))
				{
					$booljump=self::check_general_ROYAL_neighbours_promotion( /**/
						self::KING_DIRECTIONS,
						$piece,
						$color_to_move,
						$board
					);
				$officer_ROYALp=$booljump;
				$booljump=true;
				}
			else if(($ROYALp==false)&&(strpos($piece->group,"ROYAL")!==FALSE)){
					return $moves;
				}

			if(($get_CASTLEMover==1))//&&(($board->$blackcanfullmoveinowncastle == 1)||($board->$whitecanfullmoveinowncastle == 1)))
			{
				$officer_ROYALp=true;
				$booljump=true;
			}
			else
			//$officer_ROYALp=$booljump;
			$booljump=true;
	
		}
		elseif ($type==1) 
			{// Check if Officer has ROYALs;
			if(($ROYALp==false)&&($piece->group=='OFFICER'))
				{
					$booljump=self::check_general_ROYAL_neighbours_promotion( /**/
						self::KING_DIRECTIONS,
						$piece,
						$color_to_move,
						$board
					);
				$officer_ROYALp=$booljump;
				$booljump=true;
				}

			if(($get_CASTLEMover==1))//&&(($board->$blackcanfullmoveinowncastle == 1)||($board->$whitecanfullmoveinowncastle == 1)))
			{
				$officer_ROYALp=true;
				$booljump=true;
			}
			else
			//$officer_ROYALp=$booljump;
			$booljump=true;
		}

		if((($piece->square->rank>=3)&&($piece->square->rank<=6)) &&(($piece->square->file==0)||($piece->square->file==9)))
		{
			//Truce Palace captured by Opponent
			if(($piece->square->rank==3) && ($piece->color==1) && ( 
				(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==2))
				||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==2))
			)){

			}
			else if(($piece->square->rank==6) && ($piece->color==1) && (
				(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==2))
			||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==2))
			)){

			}
			else if(($piece->square->rank==3) && ($piece->color==2) && ( 
				(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==1))
			||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==1))
			)){

			}
			else if(($piece->square->rank==6) && ($piece->color==2) && ( 
				(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==1))
			||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==1))
			)){

			}
			else if(($piece->square->rank==3) && ( 
				(($piece->square->file==0) && ($board->board[4][0]==null))
			||(($piece->square->file==9) && ($board->board[4][9]==null))
			)){

			}
			else if(($piece->square->rank==6) && ( 
				(($piece->square->file==0) && ($board->board[5][0]=null))
			||(($piece->square->file==9) && ($board->board[5][9]!=null))
			)){

			}
			else if(($piece->square->rank==3) && ( 
				(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group=='NOBLE'))
			||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group=='NOBLE'))
			)){

			}
			else if(($piece->square->rank==6) && ( 
				(($piece->square->file==0) &&  ($board->board[5][0]!=null)&&($board->board[5][0]->group=='NOBLE'))
			||(($piece->square->file==9) &&  ($board->board[5][9]!=null)&&($board->board[5][9]->group=='NOBLE'))
			)){

			}				
			else
				{$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;}

		}

		if((($piece->square->rank==0)||($piece->square->rank==9)) &&(($piece->square->file>0)&& ($piece->square->file<9)))
			{
				$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;
			}

		if(($piece->group=="OFFICER")&&($officer_ROYALp==TRUE)&&($piece->square->file>0)&&($piece->square->file<9)){ // Check of promotion can happen except NMZ

			if(($piece->type!=9) && ($canbepromoted==1))
				$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,10);
			else
				$canpromote=false;
				
			if(($canpromote==TRUE) && ($canbepromoted==1)){// then update the parity with new demoted values
				$new_move1 = new ChessMove(
					$piece->square,
					$piece->square,$piece->square,
					0,
					$piece->color,
					$piece->type,
					$capture,
					$board,
					$store_board_in_moves,
					TRUE,$controlled_move,$CommonBorderOpen_Status,null
					);
	
				$move3 = clone $new_move1;
				$move3-> set_promotion_piece(10);//Knight can become Rook only
				$moves[] = $move3;
				}
		}

		if(($piece->group=="SEMIROYAL")&&($ROYALp==TRUE)&&($piece->square->file>0)&&($piece->square->file<9)){ // Check of promotion can happen except NMZ
				$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
				
			if(($canpromote==TRUE) && ($canbepromoted==1)){// then update the parity with new demoted values
				$new_move1 = new ChessMove(
					$piece->square,
					$piece->square,$piece->square,
					0,
					$piece->color,
					$piece->type,
					$capture,
					$board,
					$store_board_in_moves,
					TRUE,$controlled_move,$CommonBorderOpen_Status,null
					);
	
				$move3 = clone $new_move1;
				$move3-> set_promotion_piece(12);//Spy can become Gajarohi only
				$moves[] = $move3;
				}
		}

		if(($piece->group=="ROYAL")&&( $piece->type == ChessPiece::KING) &&
			(($piece->square->rank==0) && ($piece->square->file==4)&&($piece->color==1)||($piece->square->rank==9)&&($piece->square->file==5)&&($piece->color==2))
			){ 		
					$new_move = new ChessMove(
						$piece->square,
						$piece->square,$piece->square,
						0,
						$piece->color,
						$piece->type,
						$capture,
						$board,
						$store_board_in_moves,
						TRUE,$controlled_move,$CommonBorderOpen_Status,null
					);
	
					$move2 = clone $new_move;
					$move2-> set_promotion_piece(2);
					$moves[] = $move2;
				}
		else if(($piece->group=="ROYAL")&&( $piece->type == ChessPiece::KING) &&
			(($piece->square->rank==0)&&($piece->square->file!=4)&&($piece->color==1)||($piece->square->rank==9)&&($piece->square->file!=5)&&($piece->color==2))
			){ // give the option to become normal in castle
			
					$new_move = new ChessMove(
						$piece->square,
						$piece->square,$piece->square,
						0,
						$piece->color,
						$piece->type,
						$capture,
						$board,
						$store_board_in_moves,
						TRUE,$controlled_move,$CommonBorderOpen_Status,null
					);
	
						$move2 = clone $new_move;
						if(( $piece->type != ChessPiece::INVERTEDKING)){												
							$move2-> set_promotion_piece(2);
						}
						$moves[] = $move2;
						//return $moves; Dont Return but add more moves
				}
		else if(($piece->group=="ROYAL") &&($ROYALp==true)&&( $piece->type == ChessPiece::KING)&&
			(($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9))
			){ //add the war zone inversion mode
			
					$new_move = new ChessMove(
						$piece->square,
						$piece->square,$piece->square,
						0,
						$piece->color,
						$piece->type,
						$capture,
						$board,
						$store_board_in_moves,
						TRUE,$controlled_move,$CommonBorderOpen_Status,null
					);
	
						$move2 = clone $new_move;
						if(( $piece->type != ChessPiece::INVERTEDKING)){
							$move2-> set_promotion_piece(2);
						}
						$moves[] = $move2;
						//return $moves; Dont Return but add more moves
				}
		else if(($piece->group=="ROYAL") &&($ROYALp==true)&&( $piece->type == ChessPiece::INVERTEDKING)&&
				(($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9))
				){ //add the war zone inversion mode
				
						$new_move = new ChessMove(
							$piece->square,
							$piece->square,$piece->square,
							0,
							$piece->color,
							$piece->type,
							$capture,
							$board,
							$store_board_in_moves,
							TRUE,$controlled_move,$CommonBorderOpen_Status,null
						);
		
							$move2 = clone $new_move;
							$move2-> set_promotion_piece(1);
							$moves[] = $move2;
							//return $moves; Dont Return but add more moves
					}
		else if(($piece->group=="ROYAL") &&($ROYALp==true)&&( $piece->type == ChessPiece::INVERTEDKING)&&
				(($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9))
				){ //add the war zone normal mode option
				
						$new_move = new ChessMove(
							$piece->square,
							$piece->square,$piece->square,
							0,
							$piece->color,
							$piece->type,
							$capture,
							$board,
							$store_board_in_moves,
							TRUE,$controlled_move,$CommonBorderOpen_Status,null
						);
		
							$move2 = clone $new_move;
							$move2-> set_promotion_piece(1);
							$moves[] = $move2;
							//return $moves; Dont Return but add more moves
				}

			//booljump true means can change the zone..
			$tcount=0;
			if($get_FullMover==FALSE){ return $moves ;} //It is useless to loop through all possible moves
			if(($jumpstyle=='1')||($jumpstyle=='2'))
				$tcount=1;
			elseif($jumpstyle=='3')
				$tcount=2;
			else
				$tcount=1;


			if (($booljump==TRUE)) { 
				foreach ($oclock_list as $oclock) {
					$tempc=1;
					$board1= null;
					for (; $tempc <= $tcount; $tempc++) {
						$xdelta=self::OCLOCK_OFFSETS[$oclock][1];
						$ydelta=self::OCLOCK_OFFSETS[$oclock][0];
						if(abs($xdelta)==abs($ydelta)){
							$tempc=100;$type=='3'; $jflag='3';//diagonal jump intermediate
						}
					
						if((($jumpstyle=='3')||($jumpstyle=='1'))&&($tempc==1))
							$jflag='1';
					
						if(((($jumpstyle=='3')&&($tempc==2))||(($jumpstyle=='2')&&($tempc==1))))
							$jflag='2';

						$ending_square = self::square_exists_and_not_occupied_by_friendly_piece(
								$type, $jflag, $piece->square, $ydelta, $xdelta, $color_to_move, $board, $cankill, $get_FullMover, $selfbrokencastle, $foebrokencastle );
						/*		*/
						if ($ending_square) {
							$capture = false;


								//2 steps jump for ROYALs from normal castle not allowed
								// Check if border breached 
								$CommonBorderOpen_Status = self::get_CommonBorderOpen_Status(	$piece, $color_to_move, $board,$ending_square );
								if(($CommonBorderOpen_Status==0)){
										if (($ROYAL_ROYALp==false) && (strpos($piece->group,"ROYAL"))){
												continue;
										}
										else if (($officer_ROYALp==false) && (strpos($piece->group,"ROYAL")===FALSE)){
											continue;
										}
										else if (($officer_ROYALp==true) || ($ROYAL_ROYALp==true) ){
											if ($board->board[$ending_square->rank][$ending_square->file]) {
												$CommonBorderOpen_Status=1;
												continue;
											}
											
											if (($board->board[$ending_square->rank][$ending_square->file]==null) &&(abs($ending_square->rank-$piece->square->rank)>=1) 
											&&($ending_square->file!=$piece->square->file)&& ($piece->square->file>0)&&($piece->square->file<9))  {
												$CommonBorderOpen_Status=1;
												if( (($ending_square->rank==5)&&($piece->square->rank<5)) || (($ending_square->rank==4)&&($piece->square->rank>4)))
												{}
												else
													continue;
											}
											$CommonBorderOpen_Status=1;}
									}


								// Check if border breached								
								// IF King ArthaShastri Or Commandar are sleeping then opponent Border cannot be crossed //
								//Spies/Royals and General is required for killing allowed.. Knight logic and General Logic needs to be corrected
								if((($piece->group=="SOLDIER") || ($piece->group=="OFFICER")) && ($piece->color==1) && ($board->arewcommaderssleeping==true)){
											$cankill=0;
									}
					
								if((($piece->group=="SOLDIER") || ($piece->group=="OFFICER")) && ($piece->color==2) && ($board->arebcommaderssleeping==true)){
										$cankill=0;
									}

							if (($ROYAL_ROYALp==true)&&(strpos($piece->group,"ROYAL")!==FALSE) && 
							(((($selfbrokencastle==false)&& ($piece->square->rank==0)&&($color_to_move==1) && ($ending_square->rank>1))||
							(($foebrokencastle==false)&&($piece->square->rank==9)&&($color_to_move==1) && ($ending_square->rank<8))) || 
							((($selfbrokencastle==false)&&($piece->square->rank==9)&&($color_to_move==2)&& ($ending_square->rank<8))||
							(($foebrokencastle==false)&&($piece->square->rank==0)&&($color_to_move==2)&& ($ending_square->rank>1))))){
								continue;
							}
							//2 steps jump for Naarad/Officers/Soldiers from normal castle not allowed
							else
							if (($officer_ROYALp==true)&&(strpos($piece->group,"ROYAL")===FALSE) && 
							(((($selfbrokencastle==false)&&($piece->square->rank==0)&&($color_to_move==1) && ($ending_square->rank>1))||
							(($foebrokencastle==false)&&($piece->square->rank==9)&&($color_to_move==1) && ($ending_square->rank<8))) || 
							((($selfbrokencastle==false)&&($piece->square->rank==9)&&($color_to_move==2)&& ($ending_square->rank<8))||
							(($foebrokencastle==false)&&($piece->square->rank==0)&&($color_to_move==2)&& ($ending_square->rank>1))))){
								continue;
							}

							if(($ROYALp==true)&&(strpos($piece->group,"ROYAL")!==FALSE)&&((($ending_square->rank==2)&&($piece->square->rank==0))||(($ending_square->rank==7)&&($piece->square->rank==9)))){
							}

							if(($ROYAL_ROYALp==true)&&(strpos($piece->group,"ROYAL")!==FALSE)&&((($ending_square->file==1)&&($piece->square->file==0))||(($ending_square->file==8)&&($piece->square->file==9))||(($ending_square->file==2)&&($piece->square->file==0))||(($ending_square->file==7)&&($piece->square->file==9)))){
								continue;//2 steps jump from truce not allowed
							}
					
							if(($lastaccessiblerow!=-1)&&($color_to_move==2)&&($ending_square->rank<$lastaccessiblerow)){
								$booljump==FALSE; // Officers / Soldiers cannot go beyond  Retreat.
								continue;
							}

							if(($lastaccessiblerow!=-1)&&($color_to_move==1)&&($ending_square->rank>$lastaccessiblerow)){
								$booljump==FALSE; // Officers / Soldiers cannot go beyond  Retreat.
								continue;
							}

							if ($board->board[$ending_square->rank][$ending_square->file]) {
								// enemy piece
								if ($board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move) {
									$capture = true;
								}
							}
							//  Defective.... I will correct this caode later. Foe Compromised CASTLE movement in and out without ROYAL. 2 steps are not allowed 
							if( $board->board[$ending_square->rank][$ending_square->file]!=null ){
								if ( $board->board[$ending_square->rank][$ending_square->file] ) {
									if (( $board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move)) {
										if((($ending_square->rank==0)&& ($ending_square->file==0))||(($ending_square->rank==0)&& ($ending_square->file==9))||
										(($ending_square->rank==9)&& ($ending_square->file==0))||(($ending_square->rank==9)&& ($ending_square->file==9))){
											$capture = FALSE;
											continue; 
										}

										//Classical Game. Officers cannot kill in compromised zone or Truce when ArthShashtri is also in idle condition
										if(((strpos($piece->group,"ROYAL")===FALSE))&&($board->gametype>=1)&&(
							 			($color_to_move==2)&& (($board->basquare==null) || ( ((($board->basquare->file==5)||($board->basquare->file==4)) &&(($board->basquare->rank==9))))||
							 			((($board->basquare->file==5)||($board->basquare->file==4)) &&(($board->basquare->rank==9)))||
							 			((($board->basquare->file==0)||($board->basquare->file==9)) &&(($board->basquare->rank==4)||($board->basquare->rank==5))) ) ||

										 ( ($color_to_move==1) && (($board->wasquare==null) ||((($board->wasquare->file==5)||($board->wasquare->file==4)) &&(($board->wasquare->rank==0)))||
							 			((($board->wasquare->file==5)||($board->wasquare->file==4)) &&(($board->wasquare->rank==0)))||
							 			((($board->wasquare->file==0)||($board->wasquare->file==9)) &&(($board->wasquare->rank==4)||($board->wasquare->rank==5))) ))
			 							))
											{
											continue;
											}

										//Truce Zone guys cannot be killed
										if(($ending_square->rank>=0)&& ($ending_square->rank<=9)&&(($ending_square->file==0)||
										($ending_square->file==9))){
											continue; // Cannot Kill anyone in TruceZone * /
										}
										//else
										//$capture = FALSE;
										if($cancapture==FALSE){
											$capture = FALSE;
											$cancapture=TRUE;
											continue;
										}//cannot capture
									}

									if(($capture == TRUE)&&($board->board[$ending_square->rank][$ending_square->file]->mortal!=1)){
										continue; // Only Mortals can be killed * /
									}
									$booljump=TRUE;
								}
							}

							//ROYAL CASTLE to WAR  Defective. Compromised CASTLE does not require ROYAL touch ???
							if(($piece->group=="ROYAL")&&
							((($ending_square->file>0)&&($ending_square->file<9))&&		
							(((($piece->square->rank==0)&&($ending_square->rank<=1))||(($piece->square->rank==9) &&(($ending_square->rank>=8)))
							&&($piece->square->file>0)&&($piece->square->file<9)))	
							))
							{
								$board1= clone $board;

								if(($CommonBorderOpen_Status == 1)){
									$board1->commonborderbreached=true;
								}
								$new_move = new ChessMove( $piece->square, $ending_square,$ending_square,1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status,null);
								$move2 = clone $new_move;
								//check if the king is killed
								if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
									$move2->set_killed_king(TRUE);

								if(( $piece->type == ChessPiece::KING)){
									//$move2-> set_promotion_piece(1);
									$moves[] = $move2;
									$move3 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
										$move3->set_killed_king(TRUE);
									$move3-> set_promotion_piece(2);
									$moves[] = $move3;
								}
								elseif( $piece->type == ChessPiece::ARTHSHASTRI){
									$moves[] = $move2;
								}
								elseif(( $piece->type == ChessPiece::INVERTEDKING)){ 
									$moves[] = $move2;
									$move3 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
										$move3->set_killed_king(TRUE);
										//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
										$move3->set_killed_king(TRUE);
									$move3-> set_promotion_piece(1);
									$moves[] = $move3;
								}
								continue;
							}
							//CASTLE to CASTLE or CASTLE To No-Mans
							elseif((($piece->group=="ROYAL"))&&
							(((($ending_square->rank==0)||($ending_square->rank==9))&&(($ending_square->file>=0)&&($ending_square->file<=9)))&&		
							((($piece->square->rank==0)||($piece->square->rank==9))&&(($piece->square->file>0)&&($piece->square->file<9)))	
							))
							{
								$board1= clone $board;

								if(($CommonBorderOpen_Status == 1)){
									$board1->commonborderbreached=true;
								}

								$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status,null );
								$move2 = clone $new_move;
								//check if the king is killed
								if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
									$move2->set_killed_king(TRUE);
								if(($ending_square->file<4)||($ending_square->file>5)){
									if($piece->type == ChessPiece::KING){
										//$move2-> set_promotion_piece(1);
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$move3-> set_promotion_piece(2);
										$moves[] = $move3;
									}
									elseif(( $piece->type == ChessPiece::INVERTEDKING)){ 
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$move3-> set_promotion_piece(1);
										$moves[] = $move3;
									}
									$moves[] = $move2;
								}
								if(($ending_square->file==4)||($ending_square->file==5)){
										if(( $piece->type == ChessPiece::KING)){ 
											//$move2-> set_promotion_piece(1);
											$moves[] = $move2;
											$move3 = clone $new_move;
											//check if the king is killed
											if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
												$move3->set_killed_king(TRUE);
											$move3-> set_promotion_piece(2);
											$moves[] = $move3;
										}
										elseif(( $piece->type == ChessPiece::ARTHSHASTRI)){ 
												//$move2-> set_promotion_piece(5);
												$moves[] = $move2;
											}
									}
								continue;
							}
							//WAR to CASTLE (non-Scepter) or to No mans... check ROYALp
							elseif((($piece->group=="ROYAL"))&&
							(((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->rank==0)||($ending_square->rank==9))&&(($ending_square->file<4)||($ending_square->file>5)))
							))
							{
								//$moves-> set_promotion_piece(2);
								$board1= clone $board;

								if(($CommonBorderOpen_Status == 1)){
									$board1->commonborderbreached=true;
								}

								$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves,FALSE,$controlled_move,$CommonBorderOpen_Status,null	);
	
								$move2 = clone $new_move;
								//check if the king is killed
								if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
									$move2->set_killed_king(TRUE);
								if(( $piece->type == ChessPiece::INVERTEDKING)&&( $piece->type != ChessPiece::ARTHSHASTRI)&&( $piece->type != ChessPiece::SPY)){
									//$move2-> set_demotion_piece($piece->type+$dem);
									$move2-> set_promotion_piece(1);
								}
								$moves[] = $move2;
								$move3 = clone $new_move;
								//check if the king is killed
								if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
										$move3->set_killed_king(TRUE);
								if(( $piece->type == ChessPiece::KING)&&( $piece->type != ChessPiece::ARTHSHASTRI)&&( $piece->type != ChessPiece::SPY)){
										$move3-> set_promotion_piece(2);
									}

								$moves[] = $move3;
							}
		  					// back from any location to CASTLE to own Scepters					
							elseif(($piece->group=="ROYAL")&&((((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4) ||($ending_square->file==5))&&($color_to_move==1)
							)||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4)||($ending_square->file==5))&&($color_to_move==2)
							)))|| // CASTLE KING becoming full king * /
							((($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9))&&(($ending_square->file==4) ||($ending_square->file==5))&&(($ending_square->rank==0)||($ending_square->rank==9)))
							)){
								$board1= clone $board;

								if(($CommonBorderOpen_Status == 1)){
									$board1->commonborderbreached=true;
								}
									$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move ,$CommonBorderOpen_Status,null);
	
								$move2 = clone $new_move;
								//check if the king is killed
								if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
										$move2->set_killed_king(TRUE);

								if((( $piece->type == ChessPiece::KING))&&
									(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4) ||($ending_square->file==5))&&
									($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4)||($ending_square->file==5))&&($color_to_move==2)	)))
									){
										//$move2-> set_promotion_piece(1);
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$move3-> set_promotion_piece(2);
										$moves[] = $move3;
									}
								else	
								if((( $piece->type == ChessPiece::INVERTEDKING))&&
									(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4) ||($ending_square->file==5))&&
									($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4)||($ending_square->file==5))&&($color_to_move==2)	)))
									){
										//$move2-> set_promotion_piece(1);
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$move3-> set_promotion_piece(1);
										$moves[] = $move3;
									}
								else
								if((( $piece->type == ChessPiece::KING))&&
									((($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9))&&
									(($ending_square->file==4) ||($ending_square->file==5))&&((($ending_square->rank==0)&&($color_to_move==1))||(($ending_square->rank==9)&&($color_to_move==2))))
									){
										//$move2-> set_promotion_piece(1);
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$move3-> set_promotion_piece(2);
										$moves[] = $move3;
									}
								else
								if((( $piece->type == ChessPiece::INVERTEDKING))&&
									((($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9))&&
									(($ending_square->file==4) ||($ending_square->file==5))&&((($ending_square->rank==0)&&($color_to_move==1))||(($ending_square->rank==9)&&($color_to_move==2))))
									){
										//$move2-> set_promotion_piece(1);
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$move3-> set_promotion_piece(1);
										$moves[] = $move3;
									}
								else
								if(( $piece->type == ChessPiece::ARTHSHASTRI)&&
									(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4) ||($ending_square->file==5))&&
									($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4)||($ending_square->file==5))&&($color_to_move==2)	)))
									){
										//$move2-> set_promotion_piece(5);
										$moves[] = $move2;
									}
								else
								if(( $piece->type == ChessPiece::ARTHSHASTRI)&&
									((($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9))&&
									(($ending_square->file==4) ||($ending_square->file==5))&&((($ending_square->rank==0)&&($color_to_move==1))||(($ending_square->rank==9)&&($color_to_move==2))))
									){
										//$move2-> set_promotion_piece(5);
										$moves[] = $move2;
									}
								continue;
							}
							// from CASTLE to non Scepters but within won CASTLE //					
							elseif(($piece->group=="ROYAL")&&((((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4) && ($ending_square->file!=5))&&($color_to_move==1))
								||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4)&&($ending_square->file!=5))&&($color_to_move==2)
								))))){
									//$moves-> set_promotion_piece(2);
									$board1= clone $board;

									if(($CommonBorderOpen_Status == 1)){
										$board1->commonborderbreached=true;
									}
									$new_move = new ChessMove( $piece->square, $ending_square,$ending_square,1, $piece->color,$piece->type,	$capture,$board1,$store_board_in_moves,FALSE,$controlled_move,$CommonBorderOpen_Status,null );
									$move2 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
										$move2->set_killed_king(TRUE);
									if(( $piece->type == ChessPiece::KING)&&
										(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4) &&($ending_square->file!=5))&&
										($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4)&&($ending_square->file!=5))&&($color_to_move==2)	)))
										){
											//$move2-> set_promotion_piece(1);
											$moves[] = $move2;
											$move3 = clone $new_move;
											//check if the king is killed
											if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
												$move3->set_killed_king(TRUE);
											$move3-> set_promotion_piece(2);
											$moves[] = $move3;
										}
									else
									if(( $piece->type == ChessPiece::KING)&&
										(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4) &&($ending_square->file!=5))&&
										($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4)&&($ending_square->file!=5))&&($color_to_move==2)	)))
										){
											$moves[] = $move2;
											$move3 = clone $new_move;
											//check if the king is killed
											if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
												$move3->set_killed_king(TRUE);
											$move3-> set_promotion_piece(2);
											$moves[] = $move3;
										}
									else
									if((( $piece->type == ChessPiece::ARTHSHASTRI))&&
										(((($piece->square->rank==0)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4) &&($ending_square->file!=5))&&
										($color_to_move==1))||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file!=4)&&($ending_square->file!=5))&&($color_to_move==2)	)))
										){
											//$move2-> set_promotion_piece(6);
											$moves[] = $move2;
										}
									continue;
								}
							// From Truce //
							elseif(($piece->group=="ROYAL")&&(($piece->type == ChessPiece::KING)||  ( $piece->type == ChessPiece::ARTHSHASTRI) )&&
								((($piece->square->rank>0)&&($piece->square->rank<9)&&(($piece->square->file==0) || ($piece->square->file==9)))||
								(($piece->square->file>=0)&&($piece->square->file<=9)&&(($piece->square->rank==0) || ($piece->square->rank==9))&&($ending_square->rank!=$piece->square->rank))||
								(($piece->square->file>=0)&&($piece->square->file<=9)&&(($piece->square->rank==0) || ($piece->square->rank==9))&&($ending_square->rank==$piece->square->rank)&&
								(($ending_square->file<4)||($ending_square->file>5)))
								))
								{
									$board1= clone $board;

									if(($CommonBorderOpen_Status == 1)){
										$board1->commonborderbreached=true;
									}

									$new_move = new ChessMove($piece->square, $ending_square,$ending_square,1,$piece->color,$piece->type,$capture,$board1,$store_board_in_moves,	FALSE,$controlled_move,$CommonBorderOpen_Status,null);
									$move3 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
										$move3->set_killed_king(TRUE);
									if(( $piece->type == ChessPiece::KING)&&( $piece->type != ChessPiece::ARTHSHASTRI)){												
										$move3-> set_promotion_piece(2);
									}
									$moves[] = $move3;
								}
								//Spy Truce movement
								elseif(($piece->group=="SEMIROYAL")&&
								((($piece->square->rank>0)&&($piece->square->rank<9)&&(($piece->square->file==0) || ($piece->square->file==9)))||
								(($piece->square->file>=0)&&($piece->square->file<=9)&&(($piece->square->rank==0) || ($piece->square->rank==9))&&($ending_square->rank!=$piece->square->rank))||
								(($piece->square->file>=0)&&($piece->square->file<=9)&&(($piece->square->rank==0) || ($piece->square->rank==9))&&($ending_square->rank==$piece->square->rank)&&
								(($ending_square->file<4)||($ending_square->file>5)))
								))
								{
									$board1= clone $board;

									if(($CommonBorderOpen_Status == 1)){
										$board1->commonborderbreached=true;
									}

									$new_move = new ChessMove($piece->square, $ending_square,$ending_square,1,$piece->color,$piece->type,$capture,$board1,$store_board_in_moves,	FALSE,$controlled_move,$CommonBorderOpen_Status,null);
									$move3 = clone $new_move;
									//check if the king is killed
									if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
										$move3->set_killed_king(TRUE);
									$moves[] = $move3;

											//endblock has no data or blank
											if ($board->board[$ending_square->rank][$ending_square->file] ==null) {
												$board1= clone $board;
	
														$move2 = clone $new_move;
														//check if the king is killed
														if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
															$move2->set_killed_king(TRUE);

															$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
															if($canpromote==TRUE){
																$move2 = clone $new_move;
																//check if the king is killed
																if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																	$move2->set_killed_king(TRUE);
																$move2-> set_promotion_piece(12);
																$moves[] = $move2;
															}
													}

													continue;
		
								}								
							//WAR To CASTLE movement
							elseif((strpos($piece->group,"ROYAL")!==FALSE) && ($ROYALp)&&($ending_square->file>0)&&($ending_square->file<9)&&(($ending_square->rank==0)||($ending_square->rank==9))){
								if ( $board->board[$ending_square->rank][$ending_square->file] ==null) {
									if(($piece->type == ChessPiece::SPY)||($piece->type == ChessPiece::KING)||( $piece->type == ChessPiece::INVERTEDKING)||( $piece->type == ChessPiece::ARTHSHASTRI)){
										if(($ending_square->rank==0)||($ending_square->rank==9)){
											if(($piece->square->rank<=9)&&($color_to_move==1))
											{
												$board1= clone $board;

												if(($CommonBorderOpen_Status == 1)){
													$board1->commonborderbreached=true;
												}
												$new_move = new ChessMove($piece->square, $ending_square, $ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves,FALSE,$controlled_move,$CommonBorderOpen_Status ,null);
												$move1 = clone $new_move;
												//check if the king is killed
												if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
													$move1->set_killed_king(TRUE);
												//doublepromotion of spy logic
												if(($color_to_move==1)&&($ending_square->rank==9)&&($piece->group=="SEMIROYAL")){
													$moves[] = $move1;
													$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
													if($canpromote==TRUE){
														$move2 = clone $new_move;
														//check if the king is killed
														if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
															$move2->set_killed_king(TRUE);
														$move2-> set_promotion_piece(12);
														$moves[] = $move2;
													}
											
													if((($foebrokencastle==true)&&((($ending_square->rank==9)&&($color_to_move==1))||(($ending_square->rank==0)&&($color_to_move==2)))) &&(($ending_square->file==4)||($ending_square->file==5)))
														{ 
														continue;
														}
													//$move1 = clone $new_move;
													//check if the king is killed
													if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
														$move1->set_killed_king(TRUE);
													$moves[] = $move1;
													continue;
												}
												elseif(($color_to_move==1)&&($ending_square->rank==9)){
													if(($piece->type == ChessPiece::ARTHSHASTRI))
														$move1-> set_promotion_piece(50);
													else
														$move1-> set_promotion_piece(100);
												}
												$moves[] = $move1;
												continue;
											}
											else
											if(($piece->square->rank>=0)&&($color_to_move==2))
												{
													$board1= clone $board;

													if(($CommonBorderOpen_Status == 1)){
														$board1->commonborderbreached=true;
													}
													$new_move = new ChessMove( $piece->square, $ending_square, $ending_square, 0, $piece->color, $piece->type, $capture,$board1, $store_board_in_moves,FALSE,$controlled_move,$CommonBorderOpen_Status,null );
													//doublepromotion of spy logic
													$move1 = clone $new_move;
													//check if the king is killed
													if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
														$move1->set_killed_king(TRUE);
													if(($piece->group=="SEMIROYAL")&&($color_to_move==2)&&($ending_square->rank==0)){
														$canpromote=false;
														$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
			
														if($canpromote==TRUE){
															$move2 = clone $new_move;
															//check if the king is killed
															if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																$move2->set_killed_king(TRUE);
															$move2-> set_promotion_piece(12);
															$moves[] = $move2;
														}
			
														if((($foebrokencastle==true)&&((($ending_square->rank==9)&&($color_to_move==1))||(($ending_square->rank==0)&&($color_to_move==2)))) &&(($ending_square->file==4)||($ending_square->file==5)))
															{ 
															continue;
															}
														//$move1 = clone $new_move;
														//check if the king is killed
														if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
															$move1->set_killed_king(TRUE);
														$moves[] = $move1;
														continue;
													}
													else if(($color_to_move==2)&&($ending_square->rank==0)){
														if(( $piece->type == ChessPiece::ARTHSHASTRI))
															$move2-> set_promotion_piece(50);
														else
															$move2-> set_promotion_piece(100);
													}
											
													$moves[] = $move1;
													continue;
												}
											else 
												continue; // ** Cannot get inside CASTLE piece //
										}
									}
								}
								else { 	continue;}
							}
							//WAR To WAR movement
							elseif(($piece->group=="ROYAL") && ($ROYALp)&&(($piece->square->file>0)&&($piece->square->file<9)&&(($piece->square->rank>=1)&&($piece->square->rank<=8))
								&&(($ending_square->file>0)&&($ending_square->file<9)&&($ending_square->rank>=1)&&($ending_square->rank<=8)))){
										//endblock has no data or blank
										if ($board->board[$ending_square->rank][$ending_square->file] ==null) {
											$board1= clone $board;

											if(($CommonBorderOpen_Status == 1)){
												$board1->commonborderbreached=true;
											}
												$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move ,$CommonBorderOpen_Status,null);

												$move2 = clone $new_move;
												//check if the king is killed
												if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
													$move2->set_killed_king(TRUE);

												if(($piece->type == ChessPiece::ARTHSHASTRI))
													$moves[] = $move2;
												elseif(($piece->type == ChessPiece::KING))
												{
													$moves[] = $move2;
													$move2 = clone $new_move;
													//check if the king is killed
													if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
														$move2->set_killed_king(TRUE);
													$move2-> set_promotion_piece(2);
													$moves[] = $move2;
												}
												elseif(($piece->type == ChessPiece::INVERTEDKING))
												{
													$moves[] = $move2;
													$move2 = clone $new_move;
													//check if the king is killed
													if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
														$move2->set_killed_king(TRUE);
													$move2-> set_promotion_piece(1);
													$moves[] = $move2;
												}
												continue;

										}
										else
										{
											if(($capture==true) && ($ending_square->mediatorrank!=null)&&($ending_square->mediatorfile!=null)){
												$mediatorpiece = clone $piece;
												$endpiece = clone $board->board[$ending_square->rank][$ending_square->file];
		
												if(($piece->square->mediatorrank!=$ending_square->mediatorrank)&&($piece->square->mediatorfile!=$ending_square->mediatorfile)){
													$mediatorpiece->square->mediatorrank=$ending_square->mediatorrank;
													$mediatorpiece->square->mediatorfile=$ending_square->mediatorfile;
													$mediatorpiece->state="V";
													}
												$sittingpiece=$board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file];
												$board1 = clone $board;
												$board1->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]=$mediatorpiece;
												if($tempc>=1){
													$moves = self::add_running_capture_moves_to_moves_list($moves, $mediatorpiece, $endpiece, $color_to_move, $board1, $store_board_in_moves,1,$selfbrokencastle,$foebrokencastle,$CommonBorderOpen_Status);
													continue;
												}
											}
											else {
												$board1= clone $board;

												if(($CommonBorderOpen_Status == 1)){
													$board1->commonborderbreached=true;
												}
												$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, -1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, false,$controlled_move,$CommonBorderOpen_Status);
												$move2 = clone $new_move1;
												$moves[] = 	$move2;
												}
											//continue;
										 }

									}
							elseif(((strpos($piece->group,"ROYAL")!==FALSE))&&(
								(($piece->square->file==0)&&($piece->square->rank==0))||(($piece->square->file==0)&&($piece->square->rank==9))||
								(($piece->square->file==9)&&($piece->square->rank==0))||(($piece->square->file==9)&&($piece->square->rank==9))
								)){
									$booljump==FALSE; // ROYAL cannot exit no-mans.
									continue;
								}
							elseif((((strpos($piece->group,"ROYAL")!==FALSE)) &&($ROYALp==false)&&($ending_square->file==$piece->square->file)&&($ending_square->rank<=8)
								&&($piece->square->rank>=1)&&($piece->square->rank<=8)&&($ending_square->rank>=1))&&(($ending_square->file==0)||
								($ending_square->file==9))){ //no jumping within truce
									$booljump=FALSE;
									continue;
								}
							elseif((((strpos($piece->group,"ROYAL")!==FALSE) &&($ROYALp))&&($ending_square->rank==$piece->square->rank)&&($ending_square->rank<=1)&&($piece->square->file>0)&&($piece->square->file<9))||
								((strpos($piece->group,"ROYAL")!==FALSE) &&($ROYALp)&&($ending_square->rank==$piece->square->rank)&&($ending_square->rank>=8)&&($piece->square->file>0)&&($piece->square->file<9))||
								((strpos($piece->group,"ROYAL")!==FALSE) &&($ROYALp)&&($ending_square->rank>0)&&($ending_square->rank<9)&&($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9)&&($ending_square->file<=1)&&($piece->square->rank>0)&&($piece->square->rank<9))||
								((strpos($piece->group,"ROYAL")!==FALSE) &&($ROYALp)&&($ending_square->rank>0)&&($ending_square->rank<9)&&($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9)&&($piece->square->rank>0)&&($piece->square->rank<9))||
								((strpos($piece->group,"ROYAL")!==FALSE) &&($ROYALp)&&($ending_square->rank==0)&&($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9))||
								((strpos($piece->group,"ROYAL")!==FALSE) &&($ROYALp)&&($ending_square->rank==9)&&($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0)&&($piece->square->file<9))){
										$booljump==TRUE; //war zone4
								}
							elseif(((strpos($piece->group,"ROYAL")!==FALSE) &&($ROYAL_ROYALp))&&( //Castle to WAR ZONE
								(($ending_square->rank<$piece->square->rank)&&($ending_square->rank==8)&&($piece->square->file>0)&&($piece->square->file<9)&&($ending_square->file>0)&&($ending_square->file<9))||
								(($ending_square->rank>$piece->square->rank)&&($ending_square->rank==1)&&($piece->square->file>0)&&($piece->square->file<9)&&($ending_square->file>0)&&($ending_square->file<9))||
								(($ending_square->rank==$piece->square->rank)&&($ending_square->rank>=8)&&($piece->square->file>0)&&($piece->square->file<9)&&($ending_square->file>0)&&($ending_square->file<9))
								)){
										$booljump=TRUE;
								}
							elseif(((strpos($piece->group,"ROYAL")!==FALSE) &&($ROYAL_ROYALp))&&( //Castle to Truce
								(($ending_square->rank>$piece->square->rank)&&($ending_square->rank==1)&&($piece->square->file>1)&&($piece->square->file<8)&&($ending_square->file==0)&&($ending_square->file<9))||
								(($ending_square->rank==$piece->square->rank)&&($ending_square->rank>=8)&&($piece->square->file>1)&&($piece->square->file<8)&&($ending_square->file==0)&&($ending_square->file<9))||
								(($ending_square->rank>$piece->square->rank)&&($ending_square->rank==1)&&($piece->square->file>1)&&($piece->square->file<8)&&($ending_square->file==9)&&($ending_square->file>0))||
								(($ending_square->rank==$piece->square->rank)&&($ending_square->rank>=8)&&($piece->square->file>1)&&($piece->square->file<8)&&($ending_square->file==9)&&($ending_square->file>0))
								)){
										$booljump=TRUE;
								}
							elseif(strpos($piece->group,"ROYAL")===FALSE){
								if($piece->group=="OFFICER"){
									if($officer_ROYALp==true)
										$piece->elevatedofficer=true;
									else $piece->elevatedofficer=false;

									if(($ending_square->file>=1)&&($ending_square->file<=8)&&(($ending_square->rank==0)||($ending_square->rank==9))){
										//if castle compromised then can jump else not. Compromised castle does need ROYAL push
										if(((($selfbrokencastle==true)&&($piece->square->rank>=0)&&($color_to_move==1) && ($ending_square->rank==0))||
											(($foebrokencastle==true)&&($piece->square->rank<=9)&&($color_to_move==1) && ($ending_square->rank==9))) || 
											((($selfbrokencastle==true)&&($piece->square->rank<=9)&&($color_to_move==2)&& ($ending_square->rank==9))||
											(($foebrokencastle==true)&&($piece->square->rank>=0)&&($color_to_move==2)&& ($ending_square->rank==0))))
											{ 
												$board1= clone $board;

												if(($CommonBorderOpen_Status == 1)){
													$board1->commonborderbreached=true;
												}
												$new_move = new ChessMove($piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status);
												$move2 = clone $new_move;

												if(($officer_ROYALp==TRUE)&& ($canbepromoted==1))
													{ // Check of promotion can happen
														$dem=-1;
														$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,$piece->type-1);
														if(($canpromote==TRUE)){
															//check if the king is killed
															if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																$move2->set_killed_king(TRUE);
															$move2-> set_promotion_piece($piece->type+$dem);
				
															//check if the king is killed
															if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
																$move2->set_killed_king(TRUE);
														}
													}

												$moves[] = $move2;
												continue;
											}
										//1= Self CASTLE.. 0 = Foe CASTLE and can be jumped by Officers without ROYALs or General.
										elseif((($get_CASTLEMover==1)&&($piece->square->rank==0)&&($color_to_move==1))||
											(($get_CASTLEMover==1)&&($piece->square->rank==9)&&($color_to_move==2)))
											{ 
												$board1= clone $board;

												if(($CommonBorderOpen_Status == 1)){
													$board1->commonborderbreached=true;
												}
												$new_move = new ChessMove($piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status);
												$move2 = clone $new_move;
												$moves[] = $move2;
												continue;
											}
										//if the ending square has blank value in castle
										elseif(($piece->group=="OFFICER") &&($board->board[$ending_square->rank][$ending_square->file]==null)&&
											((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->file>=0)&&($ending_square->file<=9))&&
											((($ending_square->rank==0)||($ending_square->rank==9))) && (($officer_ROYALp==true)&&($board->gametype>=1)) ) ){
												///	* Classical General can penetrate the CASTLE. //
												$board1= clone $board;

												if(($CommonBorderOpen_Status == 1)){
													$board1->commonborderbreached=true;
												}
												
												if(($ending_square->rank==0) && ($piece->color==2)){
													$board1->wbrokencastle=true;
												}
												else if(($ending_square->rank==9) && ($piece->color==1)){
													$board1->bbrokencastle=true;
												}

												$new_move = new ChessMove($piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status);
				
												$move2 = clone $new_move;
												$moves[] = $move2;
												continue;
											}
										else
											continue; // Cannot get inside CASTLE piece //
									}
								}

								//No Mans Area. Check if the Pieces not allowed can kill or not.
								if ( $board->board[$ending_square->rank][$ending_square->file] ) {
									if (( $board->board[$ending_square->rank][$ending_square->file]->color != $color_to_move)) {
										if((($ending_square->rank==0)&& ($ending_square->file==0))||(($ending_square->rank==0)&& ($ending_square->file==9))||
										(($ending_square->rank==9)&& ($ending_square->file==0))||(($ending_square->rank==9)&& ($ending_square->file==9))){
											$capture = FALSE;
											continue; 
										}

										//Truce Zone guys cannot be killed
										if(($ending_square->rank>=0)&& ($ending_square->rank<=9)&&(($ending_square->file==0)||
											($ending_square->file==9))){
												continue; ///** Cannot Kill anyone in TruceZone //
											}
										//else
										//$capture = FALSE;
										if($cancapture==FALSE){
											$capture = FALSE;
											$cancapture=TRUE;
											continue;
										}//cannot capture
									}

									if(($capture == TRUE)&&($board->board[$ending_square->rank][$ending_square->file]->mortal!=1)){
											continue; //** Only Mortals can be killed //
										}

									$booljump=TRUE;
								}
							}
							else{
								$ttt=strpos($piece->group,"ROYAL");
								continue;
							}
		
							if ( $board->board[$ending_square->rank][$ending_square->file] ==null) {
								$capture = FALSE;
							}

							//Movement within  CASTLE with promotion
							if(($piece->group=="OFFICER") &&($officer_ROYALp==true)&&(($piece->square->rank==$ending_square->rank))&&(($ending_square->rank==0)||($ending_square->rank==9))&&(
								(($ending_square->file>0)&&($ending_square->file<9)))){ // Check of promotion can happen

									//officers holding opponent Scepter	wins the game
									if((($foebrokencastle==true)&&((($ending_square->rank==9)&&($color_to_move==1))||(($ending_square->rank==0)&&($color_to_move==2)))) &&(($ending_square->file==4)||($ending_square->file==5)))
										{ 
											$board1= clone $board;

											if(($CommonBorderOpen_Status == 1)){
												$board1->commonborderbreached=true;
											}
											$new_move = new ChessMove($piece->square, $ending_square, $ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status );

											$move2 = clone $new_move;
											//check if the king is killed
											if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
												$move2->set_killed_king(TRUE);
											//$move2-> set_promotion_piece(25);
											$moves[] = $move2;
											continue;
										}

									if(($piece->type!=9) && ($canbepromoted==1))//Piece is Knight can be promoted as Rook
										$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,10);
									else
										$canpromote=false;

									$ksqr=$board->get_king_square(abs($color_to_move-3));
									if($ksqr==null) continue;
									//If King is holding scepter then no Capture Allowed
									if(($capture==TRUE)&&($cankill!=0)&&
										(($board->get_king_square(abs($color_to_move-3))->rank==0)&&($board->get_king_square(abs($color_to_move-3))->file==4)&&($color_to_move==1)
										||($board->get_king_square(abs($color_to_move-3))->rank==9)&&($board->get_king_square(abs($color_to_move-3))->file==5)&&($color_to_move==2)))
										{
											continue;
										}

									//non general can be promoted.
									if(($canpromote==TRUE)&& ($canbepromoted==1)){// then update the parity with new demoted values
										//$piece->type=$piece->type+1;
										$board1= clone $board;

										if(($CommonBorderOpen_Status == 1)){
											$board1->commonborderbreached=true;
										}
										$new_move = new ChessMove($piece->square, $ending_square, $ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE ,$controlled_move,$CommonBorderOpen_Status);

										$move2 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
										if(($officer_ROYALp==TRUE))
											$move2-> set_promotion_piece(10);

										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs(3-$color_to_move))->rank==$ending_square->rank) &&($board->get_king_square(3-$color_to_move)->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);

										$moves[] = $move2;
										//return $moves; Dont Return but add more moves									
									}
								}
					
							//classical does not allow the movement of non-officers or no-elevated Officers or non-elevated-ROYALs to truce or no mans
							if(((($piece->group=="OFFICER") &&($piece->type!=="GENERAL")&&($ROYAL_ROYALp==false)) ||($piece->group=="SOLDIER"))&& (($ending_square->file==0)||($ending_square->file==9))&&(
								$board->gametype==1))
								{
									continue;
								}
							//add the logic to gametype>=2	

							//movement to TRUCE with demotion as per kautilya or no demotion as per classical (check if this is correct logic)
							if(($piece->group=="OFFICER") &&(($ending_square->file==0)||($ending_square->file==9))&&(
								(($ending_square->rank>0)&&($ending_square->rank<9))&&($get_CASTLEMover==1)
								))
								{ // Check if demotion can happen as per Kautilya
										if(($officer_ROYALp==TRUE)&&($board->gametype>=1)) {$dem=0;}// Kautilya allows the demotion but Classical had no demotion
										elseif($board->gametype>=2) {$dem=1;}

										if(abs($ending_square->rank-$piece->square->rank)>=2)//only one step allowed
											continue;

										$cankill=0; //Cannot kill from CASTLE to external place
										$candemote=$board->checkdemotionparity( $board->export_fen(), $piece,$color_to_move,$board);
	
										if(($candemote==TRUE)&& ($canbepromoted==1)){// then update the parity with new demoted values
											//$piece->type=$piece->type+1;
											$board1= clone $board;

											if(($CommonBorderOpen_Status == 1)){
												$board1->commonborderbreached=true;
											}
											$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE ,$controlled_move,$CommonBorderOpen_Status);

											$move2 = clone $new_move;
											//check if the king is killed
											if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
												$move2->set_killed_king(TRUE);
											if($officer_ROYALp==FALSE)
												$move2-> set_demotion_piece($piece->type+$dem);
											$moves[] = $move2;
											continue; //Demotion moves only...Dont Return but add more moves
										}
								}
							elseif(($piece->group=="OFFICER") &&(($ending_square->file==0)||($ending_square->file==9))&&(
								(($ending_square->rank>=0)&&($ending_square->rank<=9))&&($get_CASTLEMover!=1)
								)&&($board->gametype>=2))
									{ // Check of demotion can happen as per Kautilya
										if($officer_ROYALp==TRUE) {$dem=0;}
										else {$dem=1;}
		
										if(($officer_ROYALp==TRUE)&&($board->gametype>=1)) {$dem=0;}// Kautilya allows the demotion but Classical had no demotion
										elseif($board->gametype>=2) {$dem=1;}

										$candemote=$board->checkdemotionparity( $board->export_fen(), $piece,$color_to_move,$board);
		
										if($candemote==TRUE){// then update the parity with new demoted values
											//$piece->type=$piece->type+1;
											$board1= clone $board;

											if(($CommonBorderOpen_Status == 1)){
												$board1->commonborderbreached=true;
											}
											$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move ,$CommonBorderOpen_Status);

											$move2 = clone $new_move;
											//check if the king is killed
											if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
												$move2->set_killed_king(TRUE);
											if($officer_ROYALp==FALSE)
												$move2-> set_demotion_piece($piece->type+$dem);
											$moves[] = $move2;
											continue; //Demotion moves only...Dont Return but add more moves
										}
									}

							$ksqr=$board->get_king_square(abs($color_to_move-3));
							if($ksqr==null) continue;
							//If King is holding scepter then no Capture Allowed
							if(($capture==TRUE)&&($cankill==1)&&
								(($board->get_king_square(abs($color_to_move-3))->rank==0)&&($board->get_king_square(abs($color_to_move-3))->file==4)&&($color_to_move==1)
								||($board->get_king_square(abs($color_to_move-3))->rank==9)&&($board->get_king_square(abs($color_to_move-3))->file==5)&&($color_to_move==2)))
								{
										continue;
								}
				
							if(($cankill==0) &&($capture)){ // Knight logic is required to check the surrounding resource from P to S
									continue;
								}

							//Unelevated ROYALs or SemiROYALs from WAR to TRUCE
							if(($ROYAL_ROYALp==false)&&(($piece->group=="ROYAL") || ($piece->group=="SEMIROYAL")) &&((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->file==0)||($ending_square->file==9)))&&(($ending_square->rank<=9)||($ending_square->rank>=0)))
									{
									continue;
									}

							//Unelevated KING from WAR to CASTLE ZONE But not to Sceptres
							if(($ROYAL_ROYALp==false)&&($piece->group=="ROYAL")&&(( ($piece->type == ChessPiece::KING)||( $piece->type == ChessPiece::INVERTEDKING))&&
									(((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->rank==0)||($ending_square->rank==9)))&&(($ending_square->file<4)||($ending_square->file>5))||		
									((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->rank>0)&&($ending_square->rank<9)))&&(($ending_square->file>=0)&&($ending_square->file<=9))
									)))
									{
										continue;
									}
							//WAR to CASTLE ZONE But not to Sceptres
							if(($ROYAL_ROYALp==true)&&($piece->group=="ROYAL")&&(( ($piece->type == ChessPiece::KING)||( $piece->type == ChessPiece::INVERTEDKING))&&
								(((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->rank==0)||($ending_square->rank==9)))&&(($ending_square->file<4)||($ending_square->file>5))||		
								((($piece->square->rank>0)&&($piece->square->rank<9))&&(($ending_square->rank>0)&&($ending_square->rank<9)))&&(($ending_square->file>=0)&&($ending_square->file<=9))
								)))
								{
										//$moves-> set_promotion_piece(2);
										$board1= clone $board;

										if(($CommonBorderOpen_Status == 1)){
											$board1->commonborderbreached=true;
										}
										$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status );
										$move2 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
										if(( $piece->type != ChessPiece::KING)){
											//$move2-> set_demotion_piece($piece->type+$dem);
											$move2-> set_promotion_piece(1);
										}
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										if(( $piece->type != ChessPiece::INVERTEDKING)){
												$move3-> set_promotion_piece(2);
											}
										$moves[] = $move3;
								}
							//CASTLE too CASTLE or CASTLE to WAR
							elseif(($piece->group=="ROYAL")&&((( $piece->type == ChessPiece::KING) || ($piece->type == ChessPiece::INVERTEDKING) )&&
								(((($piece->square->rank==0)||($piece->square->rank==9))&&($ending_square->rank!=$piece->square->rank))||
								((($piece->square->rank==0)||($piece->square->rank==9))&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==0)||($ending_square->file==9)))
								)))
								{
										//$moves-> set_promotion_piece(2);
										$board1= clone $board;

										if(($CommonBorderOpen_Status == 1)){
											$board1->commonborderbreached=true;
										}
										$new_move = new ChessMove($piece->square, $ending_square,$ending_square, 1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move ,$CommonBorderOpen_Status);

										$move2 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move2->set_killed_king(TRUE);
										if(( $piece->type != ChessPiece::KING)){
											//$move2-> set_demotion_piece($piece->type+$dem);
											$move2-> set_promotion_piece(1);
											}
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
												$move3->set_killed_king(TRUE);
										if(( $piece->type != ChessPiece::INVERTEDKING)){
												$move3-> set_promotion_piece(2);
											}
										$moves[] = $move3;
								}
							
							//CASTLE to No Mans = TRuce ... KING movement not covered....
							elseif(($piece->group=="ROYAL")&&(( $piece->type == ChessPiece::KING) || ($piece->type == ChessPiece::INVERTEDKING)) &&((
								((($piece->square->rank==0)&&(($ending_square->file==4) ||($ending_square->file==5))&&($color_to_move==1)
								)||(($piece->square->rank==9)&&($ending_square->rank==$piece->square->rank)&&(($ending_square->file==4)||($ending_square->file==5))&&($color_to_move==2)
								))) ||
								(
								(($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9))&&
								(($ending_square->file==4) ||($ending_square->file==5))&&(($ending_square->rank==0)||($ending_square->rank==9)))					
								)){
										//$moves-> set_promotion_piece(2);
										$board1= clone $board;

										if(($CommonBorderOpen_Status == 1)){
											$board1->commonborderbreached=true;
										}
										$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status );

										$move2 = clone $new_move;
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										$move3-> set_promotion_piece(2);
										$moves[] = $move3;
								}
							elseif(($piece->group=="ROYAL")&&($piece->type == ChessPiece::KING) &&
								(($piece->square->rank>0)&&($piece->square->rank<9)&&(($piece->square->file==0) || ($piece->square->file==9))))
								{
									$board1= clone $board;

									if(($CommonBorderOpen_Status == 1)){
										$board1->commonborderbreached=true;
									}
										$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status );
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										if(( $piece->type != ChessPiece::INVERTEDKING)){												
												$move3-> set_promotion_piece(2);
											}
										$moves[] = $move3;
								}
							elseif(($piece->group=="ROYAL")&&((( $piece->type == ChessPiece::KING) &&
								((($piece->square->rank==0)&&(($piece->square->file==4) ||($piece->square->file==5))&&($color_to_move==1)
								)||(($piece->square->rank==9)&&(($piece->square->file==4)||($piece->square->file==5))&&($color_to_move==2)
								)))|| (( $piece->type == ChessPiece::KING) &&
								(($piece->square->rank>0)&&($piece->square->rank<9)&&($piece->square->file>0) && ($piece->square->file<9)))	
								)){
										//$moves-> set_promotion_piece(2);
										$board1= clone $board;

										if(($CommonBorderOpen_Status == 1)){
											$board1->commonborderbreached=true;
										}
										$new_move = new ChessMove( $piece->square, $ending_square,$ending_square, 1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE ,$controlled_move,$CommonBorderOpen_Status);
										if(( $piece->type == ChessPiece::KING)){

											$move2 = clone $new_move;
										}
										$moves[] = $move2;
										$move3 = clone $new_move;
										//check if the king is killed
										if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
											$move3->set_killed_king(TRUE);
										if(( $piece->type != ChessPiece::INVERTEDKING)){
											$move3-> set_promotion_piece(2);
										}
										$moves[] = $move3;
								}
							else {
									if(($capture==true) && ($ending_square->mediatorrank!=null)&&($ending_square->mediatorfile!=null)){
										$mediatorpiece = clone $piece;
										$endpiece = clone $board->board[$ending_square->rank][$ending_square->file];

										if(($piece->square->mediatorrank!=$ending_square->mediatorrank)&&($piece->square->mediatorfile!=$ending_square->mediatorfile)){
											$mediatorpiece->square->mediatorrank=$ending_square->mediatorrank;
											$mediatorpiece->square->mediatorfile=$ending_square->mediatorfile;
											$mediatorpiece->state="V";
											}
										$sittingpiece=$board->board[$mediatorpiece->square->rank][$mediatorpiece->square->file];
										$board1 = clone $board;
										$board1->board[$mediatorpiece->square->rank][$mediatorpiece->square->file]=$mediatorpiece;
										if($tempc>=1){
											$moves = self::add_running_capture_moves_to_moves_list($moves, $mediatorpiece, $endpiece, $color_to_move, $board1, $store_board_in_moves,1,$selfbrokencastle,$foebrokencastle,$CommonBorderOpen_Status);
											continue;
										}
									}
									else {
										$board1= clone $board;

										if(($CommonBorderOpen_Status == 1)){
											$board1->commonborderbreached=true;

										}


										if (($board->board[$ending_square->rank][$ending_square->file]==null) &&(abs($ending_square->rank-$piece->square->rank)>=1) 
										&&($ending_square->file!=$piece->square->file)&& ($piece->square->file>0)&&($piece->square->file<9))  {
											$CommonBorderOpen_Status=1;
											$board1->CommonBorderOpen_Status=1;

											if( (($ending_square->rank==5)&&($piece->square->rank<5)) || (($ending_square->rank==4)&&($piece->square->rank>4)))
											{}
											else
												continue;
										}

										$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, -1, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, false,$controlled_move,$CommonBorderOpen_Status);
										$move2 = clone $new_move1;
										$moves[] = 	$move2;

										if(($board1->commonborderbreached=true) && ($CommonBorderOpen_Status==1) && (($piece->group=="SOLDIER") ||($piece->group=="OFFICER") || ($piece->group=="NOBLE")))
										    continue;

										}
									//continue;
				 				}
							
							if((($piece->group=="OFFICER")|| ($piece->group=="SEMIROYAL"))&&($piece->square->file>0)&&($piece->square->file<9)){ // Check of promotion can happen except TZ
								$skipxy=$piece->square;

								$dROYALp=self::has_ROYAL_neighbours(  self::KING_DIRECTIONS, $skipxy, $ending_square, $color_to_move, $board );
					
								$targetpiece=clone $piece;
								$targetpiece->square->file=	$ending_square->file;
								$targetpiece->square->rank=	$ending_square->rank;
								//endpiece in truce is Palace or surrounding palace the also promotion

								if((($piece->square->rank>=3)&&($piece->square->rank<=6)) &&(($piece->square->file==0)||($piece->square->file==9)))
								{
									//Truce Palace captured by Opponent
									if(($piece->square->rank==3) && ($piece->color==1) && ( 
										(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==2))
										||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==2))
									)){
					
									}
									else if(($piece->square->rank==6) && ($piece->color==1) && (
										(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==2))
									||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==2))
									)){
					
									}
									else if(($piece->square->rank==3) && ($piece->color==2) && ( 
										(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group!='NOBLE')&&($board->board[4][0]->color==1))
									||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group!='NOBLE')&&($board->board[4][9]->color==1))
									)){
					
									}
									else if(($piece->square->rank==6) && ($piece->color==2) && ( 
										(($piece->square->file==0) && ($board->board[5][0]!=null)&&($board->board[5][0]->group!='NOBLE')&&($board->board[5][0]->color==1))
									||(($piece->square->file==9) && ($board->board[5][9]!=null)&&($board->board[5][9]->group!='NOBLE')&&($board->board[5][9]->color==1))
									)){
					
									}
									else if(($piece->square->rank==3) && ( 
										(($piece->square->file==0) && ($board->board[4][0]==null))
									||(($piece->square->file==9) && ($board->board[4][9]==null))
									)){
					
									}
									else if(($piece->square->rank==6) && ( 
										(($piece->square->file==0) && ($board->board[5][0]=null))
									||(($piece->square->file==9) && ($board->board[5][9]!=null))
									)){
					
									}
									else if(($piece->square->rank==3) && ( 
										(($piece->square->file==0) && ($board->board[4][0]!=null)&&($board->board[4][0]->group=='NOBLE'))
									||(($piece->square->file==9) && ($board->board[4][9]!=null)&&($board->board[4][9]->group=='NOBLE'))
									)){
					
									}
									else if(($piece->square->rank==6) && ( 
										(($piece->square->file==0) &&  ($board->board[5][0]!=null)&&($board->board[5][0]->group=='NOBLE'))
									||(($piece->square->file==9) &&  ($board->board[5][9]!=null)&&($board->board[5][9]->group=='NOBLE'))
									)){
					
									}				
									else
										{$ROYAL_ROYALp=true;$officer_ROYALp=true;$ROYALp=true;$dROYALp=true;$dgeneralp=true;
										}		
								}

								$dgeneralp=self::check_general_ROYAL_neighbours_promotion( self::KING_DIRECTIONS, $targetpiece, $color_to_move, $board,$piece );
								// Check of destination promotion can happen
								if(($canbepromoted==1)&&(($dROYALp==TRUE)||($dgeneralp==TRUE)))
									{ 
										$dem=-1;
										if(( $board->gametype>=2)&&($piece->type==ChessPiece::GENERAL))
										{
											$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,6);
											$dem=-3;
										}
										else if(($piece->type!==ChessPiece::GENERAL) && ($piece->group!="SEMIROYAL"))
											$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,$piece->type-1);
										else if(($piece->group=="SEMIROYAL")&&($dROYALp==TRUE)){
											$canpromote=$board->checkpromotionparity( $board->export_fen(), $piece,$color_to_move,$board,12);
											$dem=5;
										}
										else if(($piece->group=="SEMIROYAL")&&($dROYALp==false)){
											$canpromote=false;
										}
												
										if(($canpromote==TRUE)&& ($canbepromoted==1)){// then update the parity with new demoted values
											//$piece->type=$piece->type+1;
											//Force Promotion to add in movelist	
											$board1= clone $board;

											if(($CommonBorderOpen_Status == 1)){
												$board1->commonborderbreached=true;
											}
											$new_move1 = new ChessMove( $piece->square, $ending_square,$ending_square, 0, $piece->color, $piece->type, $capture, $board1, $store_board_in_moves, FALSE,$controlled_move,$CommonBorderOpen_Status );

											$move3 = clone $new_move1;
											//check if the king is killed
											if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
												$move3->set_killed_king(TRUE);
											$move3-> set_promotion_piece($piece->type+$dem);

											//check if the king is killed
											if(($capture==TRUE)&&( ($board->get_king_square(abs($color_to_move-3))->rank==$ending_square->rank) &&($board->get_king_square(abs($color_to_move-3))->file==$ending_square->file)))
													$move3->set_killed_king(TRUE);
											$moves[] = $move3;
										}
									}
								else
									{

									}
							}
						 }
						/* */
					}
					
				}
			}
		return $moves;
	}

	static function mark_checks_and_checkmates(array $moves, $color_to_move): void {
		$enemy_color = self::invert_color($color_to_move);		
		foreach ( $moves as $move ) {
			$enemy_king_square = $move->board->get_king_square($enemy_color);
			//if(($move->ending_square->file==3)&&($move->ending_square->rank==3)){
				if(($enemy_king_square==null)&&($move->ending_square->file>=0)&&($move->ending_square->rank>=0)){
					$enemy_king_square=$move->ending_square; // Moving in check condition
				}
			//}

			if ( self::square_is_attacked($color_to_move, $move->board, $enemy_king_square) ) {
				$move->check = TRUE;
				$legal_moves_for_enemy = self::get_legal_moves_list($enemy_color, $move->board, TRUE, TRUE, FALSE);
				
				if ( ! $legal_moves_for_enemy ) {
					$move->checkmate = TRUE;
				}
			}
		}
	}
	
	static function eliminate_king_in_check_moves(ChessPiece $king, array $moves, $color_to_move): array {
		if ( ! $king ) {
			throw new Exception('Invalid FEN - One of the kings is missing');
		}
		
		$enemy_color = self::invert_color($color_to_move);
		$new_moves = array();
		
		foreach ( $moves as $move ) {
			$friendly_king_square = $move->board->get_king_square($color_to_move);
			//self::square_is_attacked($enemy_color, $move->board, $friendly_king_square);
			if (!self::square_is_attacked($enemy_color, $move->board, $friendly_king_square) ) { //Mover's Kings is not under threat
				$new_moves[] = $move;
			}
			else
			if (self::square_is_attacked($enemy_color, $move->board, $friendly_king_square) ) {
				$new_moves[] = $move;
			}
		}
		
		return $new_moves;
	}
		
	static function get_all_pieces_by_color($color_to_move, ChessBoard $board): array {
		$list_of_pieces = array();
		for ( $i = 0; $i <= 9; $i++ ) {
			for ( $j = 0; $j <=9; $j++ ) {
				$piece = $board->board[$i][$j];
				
				if ( $piece ) {
					if ( $piece->color == $color_to_move ) {
						$list_of_pieces[] = $piece;
					}
				}
			}
		}
		return $list_of_pieces;
	}

	static function checkpinnedrefugees($color_to_move,$board,ChessSquare $starting_square,ChessSquare $ending_square):?bool
	{
	
		if(($color_to_move==2)&&($board->bnsquare==null)){
				return false;
		}
		elseif(($color_to_move==1)&&($board->wnsquare==null)){
			return false;
		}

		if($board->bnsquare=null)
			$board->PinnedWRefugees=[];
		if($board->wnsquare=null)
			$board->PinnedBRefugees=[];

			for($i=0;$i<8;$i++){
				if($i<count($board->PinnedWRefugees)){
						if (($color_to_move==2)&&  (($ending_square->rank==$board->PinnedWRefugees[$i]->square->rank) &&($ending_square->file==$board->PinnedWRefugees[$i]->square->file))){
							//Cannt kill as it is pinned
							return true;
							}

						if (($color_to_move==1)&&(count($board->PinnedWRefugees)>$i)&&(($board->board[$starting_square->rank][$starting_square->file]->group=='OFFICER')
							&&(($starting_square->rank==$board->PinnedWRefugees[$i]->square->rank) &&($starting_square->file==$board->PinnedWRefugees[$i]->square->file)))){
								//Cannt kill as it is pinned
								return true;
							}
					}
				elseif($i<count($board->PinnedBRefugees)){
						if ( ($color_to_move==1)&&(($ending_square->rank==$board->PinnedBRefugees[$i]->square->rank) &&($ending_square->file==$board->PinnedBRefugees[$i]->square->file)) ){
							//Cannt kill as it is pinned
							return true;
						}

						if (($color_to_move==2)&&(count($board->PinnedBRefugees)>$i)&&(($board->board[$starting_square->rank][$starting_square->file]->group=='OFFICER')
						&&(($starting_square->rank==$board->PinnedBRefugees[$i]->square->rank) &&($starting_square->file==$board->PinnedBRefugees[$i]->square->file)))){
							//Cannt kill as it is pinned
							return true;
						}
					}
				}
			return false;
	}				

// positive X = east, negative X = west, positive Y = north, negative Y = south
//Knight can always strike in 1st move. Fix this issue..

	static function square_exists_and_not_occupied_by_friendly_piece(		
		int $type,
		$jumpflag,
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board,
		int $cankill,
		$get_FullMover,
		$selfbrokencastle,
		$foebrokencastle
	): ?ChessSquare {
		//type = 0 means slide
		$xx=0;$yy=0;
		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;
		$wallcrossallowed=false;
		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank,$file);
		$intermediate_square = null;

		if(($type!=0)&&(($get_FullMover==false)&&($file==-1)||($file==10))){
			if($file==-1){ //War to Truce
				$file=0;}
			else if($file==10){ 	
				$file=9;}

			$intermediate_square=self::try_to_make_square_using_rank_and_file_num ($starting_square->rank,$file);
			if(!$board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell is blank data
					return $intermediate_square;
			}
			return null;
		}
		
		// Ending square is off the board
		if ( ! $ending_square ) {	
			return null;
		}

		//Cannot Kill or Push Controlled Guy
		if (($board->board[$ending_square->rank][$ending_square->file]!=null)&&( $board->board[$ending_square->rank][$ending_square->file]->controlledpiece==true)){
			$starting_piece=$board->board[$starting_square->rank][$starting_square->file];
				return null;
		}
		
		//Controlled Piece Cannot Kill or Push Normal Guy
		if (( $board->board[$starting_square->rank][$starting_square->file]->controlledpiece==true)&&
		( $board->board[$ending_square->rank][$ending_square->file]!=null)){
				return null;
		}

		/*if(($starting_square->rank==8)&&($starting_square->file==6)&&($ending_square->rank==9)&&($ending_square->file==7))
		{$ttt=1;}
		if(($ending_square->rank==9)&&($ending_square->file==1)&&($starting_square->rank==7)&&($starting_square->file==2))
		{$ttt=1;}*/
		if( (($selfbrokencastle==true)&&( $starting_square->rank==9)&&($ending_square->rank<8)&&($color_to_move==2)||
		($foebrokencastle==true)&&($ending_square->rank>1)&&($starting_square->rank==0)&&($color_to_move==2))||  
		(($selfbrokencastle==true)&&( $starting_square->rank==0)&&($ending_square->rank>1)&&($color_to_move==1)||
		($foebrokencastle==true)&&($ending_square->rank<8)&&($starting_square->rank==9)&&($color_to_move==1)))
		{
			$intermediate_square = null;
		}
		if( ((($selfbrokencastle==true)&&( $starting_square->rank==9)&&($ending_square->rank>=6)&&($color_to_move==2)||
		($foebrokencastle==true)&&($ending_square->rank<=3)&&($starting_square->rank==0)&&($color_to_move==2))||  
		(($selfbrokencastle==true)&&( $starting_square->rank==0)&&($ending_square->rank<=3)&&($color_to_move==1)||
		($foebrokencastle==true)&&($ending_square->rank>=6)&&($starting_square->rank==9)&&($color_to_move==1)))  )
		{ /*
			* Enter into WAR Zone from Compromized CASTLE or move within CASTLE
			*/
			if($type==0) {
				$intermediate_square=$ending_square;
				$intermediate_square=null;
			}
			else
			if($type!=0){
				if(($type==2)&&(abs($x_delta)<2) &&(abs($y_delta)<2))
				{
				}
				else
				if ($type>=1){ 
					if ($jumpflag=='1') {
						//$straight jump
		
						// if Intermediate square contains a enemy piece
						if (($starting_square->rank)-($ending_square->rank)==2) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if (($ending_square->rank)-($starting_square->rank)==2) {
							$yy=1;//**echo ' xx_1 ';
						}
		
						if (($starting_square->file)-($ending_square->file)==2) {
							$xx=-1;//**echo ' yy_-1 ';
						}
						
						if (($ending_square->file)-($starting_square->file)==2) {
							$xx=1;//**echo ' yy_1 ';
						}
					
						if (abs($starting_square->rank-$ending_square->rank)==2) {
							$xx=0;//**echo ' yy ';
						} elseif (abs($starting_square->file-$ending_square->file)==2) {
							$yy=0; 	//**echo ' xx ';
						}
					}
					elseif ($jumpflag>='2') {
						//$diagonal jump
						if ((($starting_square->rank)-($ending_square->rank)==2)||(($starting_square->rank)-($ending_square->rank)==1)) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if ((($ending_square->rank)-($starting_square->rank)==2)||(($ending_square->rank)-($starting_square->rank)==1)) {
							$yy=1;//**echo ' xx_1 ';
						}
	
						if ((($starting_square->file)-($ending_square->file)==2)||(($starting_square->file)-($ending_square->file)==1)) {
							$xx=-1;//**echo ' yy_-1 ';
						}
					
						if ((($ending_square->file)-($starting_square->file)==2)||(($ending_square->file)-($starting_square->file)==1)) {
							$xx=1;//**echo ' yy_1 ';
						}
					}
					$intermediate_square = self::try_to_make_square_using_rank_and_file_num($starting_square->rank+$yy, $starting_square->file+$xx);
					if ( ! $intermediate_square ) {
						return null;
					}
					
					/* This is doubtfull
					if(  $intermediate_square->rank!=$starting_square->rank ){
						return null;
					}
					*/
		
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
					(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"OFFICER")!==FALSE)) {//if intermediate cell has Full ROYAL or Noble then jumping not allowed
						if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
								return null;//
							}
						}

					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
					($board->board[$starting_square->rank][$starting_square->file]->group=="ROYAL")){//if intermediate cell has Soldier or Officer  or Noble then jumping not allowed
							if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER') || ($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
									return null;//
								}
							}
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
							(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"SEMIROYAL")!==FALSE)) {//if intermediate cell has Noble then jumping not allowed
								if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
										return null;//
								}
							}

					if($board->board[$intermediate_square->rank][$intermediate_square->file]){
						if (($cankill==2) &&($type==1)&&(abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==0)) {//Same team-member
							if(($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SEMIROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER')){
							}
						else
							{
								return null;//
							}
						}
					}
		
					if($board->board[$ending_square->rank][$ending_square->file]){//Ending has enemy but intermediate is blank
						if(($jumpflag==1)&&($ending_square->rank!=$intermediate_square->rank)){
							return null;//cannot kill outside of the compromised castle.
						}
						if(!$board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell is blank data
							if (($cankill==2) &&(abs($board->board[$ending_square->rank][$ending_square->file]->color - $color_to_move) ==1)) {//Enemy team-member
								return null;//
								}
							}
						}
			
					if ( $board->board[$intermediate_square->rank][$intermediate_square->file] ) { //Check Intermediate Block
						if ( $board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move==0 ) {
							//**echo ' samecolor';							
						}
						else
						if ( abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==1) {
						//**echo ' diffcolor';
						//**echo ' diffcolor';
						if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
						(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

						}
						else 
							return null;
						}
						else
						{
							//**echo ' blankcolor';
						}
					}
					else
					if ( $board->board[$rank][$file] ) {//Check Ending block
						if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
						//**echo ' samecolor';
						return null; 
						}
						else
						if ( $board->board[$rank][$file]->color == $color_to_move ) {
							//**echo ' Ending square contains a friendly piece ';
							return null;
						}
						else
						if ( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
							//**echo ' diffcolor'; abs($board->board[$rank][$file]->color - $color_to_move)
							//check if no naarad pinned blocks present
							if(self::checkpinnedrefugees($color_to_move,$board, $starting_square,$ending_square)==true)
								return null;
						}
						else
						{
							//**echo ' blankcolor';
						}
					}
				}
			}
		}
		//
		/*
		else
		//No movedown in truce for non-elevated army
		if(   (($board->board[$starting_square->rank][$starting_square->file]->group=='OFFICER')||($board->board[$starting_square->rank][$starting_square->file]->group=='SOLDIER'))   
		&& (($ending_square->rank>=1)&&($ending_square->rank<=8)&&($starting_square->rank>=1)&&($starting_square->rank<=8))&&
		(($ending_square->file==0)||($ending_square->file==9)||($starting_square->file==0)||($starting_square->file==9))&&(($ending_square->file==$starting_square->file))){
			return null;
		}
		//
		*/
		 //should Fix the issue for General also
		elseif((($selfbrokencastle==true)&&($ending_square->rank==0)&&($color_to_move==1)||
		($foebrokencastle==true)&&($ending_square->rank==9)&&($color_to_move==1)) ||(($selfbrokencastle==true)&&($ending_square->rank==9)&&($color_to_move==2)||
		($foebrokencastle==true)&&($ending_square->rank==0)&&($color_to_move==2))  
		||(($ending_square->file>0)&&($ending_square->file<9)&&(($ending_square->rank==9)||($ending_square->rank==0))&&($starting_square->rank>1)&&($starting_square->rank<9)&&($color_to_move==1)&&($board->gametype>=1)))

		{ /*
			* Enter into CASTLE as it has become warzone
			*/
			if($type==0) {
				$intermediate_square=$ending_square;
						//$diagonal move
						if ((($starting_square->rank)-($ending_square->rank)>=2)&&(($starting_square->file)-($ending_square->file)>=2)) {
							$yy=-1; $xx=-1 ;
						}
						if ((($starting_square->rank)-($ending_square->rank)>=2)&&(($ending_square->file)-($starting_square->file)>=2)) {
							$yy=-1; $xx=1 ;
						}
						if ((($ending_square->rank)-($starting_square->rank)>=2)&&(($ending_square->file)-($starting_square->file)>=2)) {
							$yy=1; $xx=1 ;
						}
						if ((($ending_square->rank)-($starting_square->rank)>=2)&&(($starting_square->file)-($ending_square->file)>=2)) {
							$yy=1; $xx=-1 ;
						}
					$intermediate_square = self::try_to_make_square_using_rank_and_file_num($starting_square->rank+$yy, $starting_square->file+$xx);
					if ( ! $intermediate_square ) {
						return null;
					}
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
					(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"OFFICER")!==FALSE)) {//if intermediate cell has King then jumping now allowed
						if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
								return null;//
							}
						}
	
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
						(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"ROYAL")!==FALSE)) {//if intermediate cell has Full ROYAL or Noble then jumping not allowed
							if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER') || ($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
									return null;//
								}
							}
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
							(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"SEMIROYAL")!==FALSE)) {//if intermediate cell has Full ROYAL or Noble then jumping not allowed
								if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
										return null;//
								}
							}
				}
			else
			if($type!=0){
				if(($type==2)&&(abs($x_delta)<2) &&(abs($y_delta)<2))
				{
				}
				else
				if ($type>=1){ 
					// if Intermediate square contains a enemy piece 
					if ($jumpflag=='1') {
						//$straight jump
		
						// if Intermediate square contains a enemy piece
						if (($starting_square->rank)-($ending_square->rank)==2) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if (($ending_square->rank)-($starting_square->rank)==2) {
							$yy=1;//**echo ' xx_1 ';
						}
		
						if (($starting_square->file)-($ending_square->file)==2) {
							$xx=-1;//**echo ' yy_-1 ';
						}
						
						if (($ending_square->file)-($starting_square->file)==2) {
							$xx=1;//**echo ' yy_1 ';
						}
					
						if (abs($starting_square->rank-$ending_square->rank)==2) {
							$xx=0;//**echo ' yy ';
						} elseif (abs($starting_square->file-$ending_square->file)==2) {
							$yy=0; 	//**echo ' xx ';
						}
					}
					elseif ($jumpflag>='2') {
						//$diagonal jump
						if ((($starting_square->rank)-($ending_square->rank)==2)||(($starting_square->rank)-($ending_square->rank)==1)) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if ((($ending_square->rank)-($starting_square->rank)==2)||(($ending_square->rank)-($starting_square->rank)==1)) {
							$yy=1;//**echo ' xx_1 ';
						}
	
						if ((($starting_square->file)-($ending_square->file)==2)||(($starting_square->file)-($ending_square->file)==1)) {
							$xx=-1;//**echo ' yy_-1 ';
						}
					
						if ((($ending_square->file)-($starting_square->file)==2)||(($ending_square->file)-($starting_square->file)==1)) {
							$xx=1;//**echo ' yy_1 ';
						}
						//$intermediate_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);
					}
					$intermediate_square = self::try_to_make_square_using_rank_and_file_num($starting_square->rank+$yy, $starting_square->file+$xx);
					if ( ! $intermediate_square ) {
						return null;
					}
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
					(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"OFFICER")!==FALSE)) {//if intermediate cell has King then jumping now allowed
						if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
								return null;//
							}
						}
					//No jumping of TRUCE
					if(($intermediate_square->rank!=$starting_square->rank) && (($intermediate_square->file==0)||($intermediate_square->file==9))){
						return null;
					}
		
					if($board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell has data. Logic is incorrect here for King vs General
						if (($cankill==2) &&($type==1)&&(abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==0)) {//Same team-member
							if(($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SEMIROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER')){
							}
						else
							{
								return null;//
							}
						}
					}
		
					if($board->board[$ending_square->rank][$ending_square->file]){//Ending has enemy but intermediate is blank
						if(!$board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell is blank data
							if (($cankill==2) &&(abs($board->board[$ending_square->rank][$ending_square->file]->color - $color_to_move) ==1)) {//Enemy team-member
								return null;//
								}
							}
						}
		
					if ((($intermediate_square->file==0 ) ||($intermediate_square->file==9 )) &&($intermediate_square->rank>=0 )&&($intermediate_square->rank<=9 )) {
						return null;
					}
		
					if ( $board->board[$intermediate_square->rank][$intermediate_square->file] ) { //Check Intermediate Block
						if ( $board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move==0 ) {
						//**echo ' samecolor';
							
						}
						else
						if ( abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==1) {
						//**echo ' diffcolor';
							//if the starting piece is spy then it can jump
							if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
							(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

							}
							else 
							return null;

						}
						else
						{
							//**echo ' blankcolor';
						}
					}
					else
					if ( $board->board[$rank][$file] ) {//Check Ending block
						if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
						//**echo ' samecolor';
						return null; 
						}
						else
						if ( $board->board[$rank][$file]->color == $color_to_move ) {
							//**echo ' Ending square contains a friendly piece ';
							return null;
						}
						else
						if ( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
							//**echo ' diffcolor'; abs($board->board[$rank][$file]->color - $color_to_move)
							//check if no naarad pinned block
							if(self::checkpinnedrefugees($color_to_move,$board,$starting_square,$ending_square)==true)
								return null;
						}
						else
						{
							//**echo ' blankcolor';
						}
					}
				}
			}
		}
		else
		if(($selfbrokencastle==true)&&($ending_square->rank==9)&&($color_to_move==2)||
		($foebrokencastle==true)&&($ending_square->rank==0)&&($color_to_move==2))
		{ /*
			* Enter into CASTLE as it has become warzone
			*/
			if($type==0) {
				//$intermediate_square=$ending_square;
				$intermediate_square=null;
			}
			else
			if($type!=0){
				if(($type==2)&&(abs($x_delta)<2) &&(abs($y_delta)<2))
				{
				}
				else
				if ($type>=1){ //Horse =1 King or General = 2 
					// if Intermediate square contains a enemy piece 
					if ($jumpflag=='1') {
						//$straight jump
		
						// if Intermediate square contains a enemy piece
						if (($starting_square->rank)-($ending_square->rank)==2) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if (($ending_square->rank)-($starting_square->rank)==2) {
							$yy=1;//**echo ' xx_1 ';
						}
		
						if (($starting_square->file)-($ending_square->file)==2) {
							$xx=-1;//**echo ' yy_-1 ';
						}
						
						if (($ending_square->file)-($starting_square->file)==2) {
							$xx=1;//**echo ' yy_1 ';
						}
					
						if (abs($starting_square->rank-$ending_square->rank)==2) {
							$xx=0;//**echo ' yy ';
						} elseif (abs($starting_square->file-$ending_square->file)==2) {
							$yy=0; 	//**echo ' xx ';
						}
					}
					elseif ($jumpflag>='2') {
						//$diagonal jump
						if ((($starting_square->rank)-($ending_square->rank)==2)||(($starting_square->rank)-($ending_square->rank)==1)) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if ((($ending_square->rank)-($starting_square->rank)==2)||(($ending_square->rank)-($starting_square->rank)==1)) {
							$yy=1;//**echo ' xx_1 ';
						}
	
						if ((($starting_square->file)-($ending_square->file)==2)||(($starting_square->file)-($ending_square->file)==1)) {
							$xx=-1;//**echo ' yy_-1 ';
						}
					
						if ((($ending_square->file)-($starting_square->file)==2)||(($ending_square->file)-($starting_square->file)==1)) {
							$xx=1;//**echo ' yy_1 ';
						}
					}
					$intermediate_square = self::try_to_make_square_using_rank_and_file_num($starting_square->rank+$yy, $starting_square->file+$xx);

					//**echo ' <br/> Night Move ';
					if ( ! $intermediate_square ) {
						return null;
					}
	
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
					(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"OFFICER")!==FALSE)) {//if intermediate cell has King or Noble then jumping now allowed
						if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
								return null;//
							}
						}

					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
					($board->board[$starting_square->rank][$starting_square->file]->group=="ROYAL")){//if intermediate cell has Soldier or Officer or Noble then jumping not allowed
							if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER') || ($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
									return null;//
								}
							}
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
							(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"SEMIROYAL")!==FALSE)) {//if intermediate cell has ROYAL then jumping not allowed
								if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
										return null;//
								}
							}
						
					if(  $intermediate_square->rank!=$starting_square->rank ){
						return null;
					}
		
					if($board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell has data
						if ((($cankill==2) && ($type==1))&&(abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==0)) {//Same team-member
							if(($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SEMIROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER')){
							}
						else
							{
								return null;//knight Cannot kill without intermidetiate.. King or General can still move even if these
							}
						}
						else
						if ((abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) !=0)) {
								return null;
						}
					}
		
					if($board->board[$ending_square->rank][$ending_square->file]){//Ending has enemy but intermediate is blank
						if(!$board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell is blank data
							if (($cankill==2) &&(abs($board->board[$ending_square->rank][$ending_square->file]->color - $color_to_move) ==1)) {//Enemy team-member
								return null;//
								}
							}
						}
		
					if ((($intermediate_square->file==0 ) ||($intermediate_square->file==9 )) &&($intermediate_square->rank>=0 )&&($intermediate_square->rank<=9 )) {
						return null;
					}
		
					if ( $board->board[$intermediate_square->rank][$intermediate_square->file] ) { //Check Intermediate Block
						if ( $board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move==0 ) {
						//**echo ' samecolor';
							
						}
						else
						if ( abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==1) {
						//**echo ' diffcolor';
						if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
						(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

						}
						else 
							return null;
						}
						else
						{
							//**echo ' blankcolor';
						}
					}
					else
					if ( $board->board[$rank][$file] ) {//Check Ending block
						if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
						//**echo ' samecolor';
						return null; 
						}
						else
						if ( $board->board[$rank][$file]->color == $color_to_move ) {
							//**echo ' Ending square contains a friendly piece ';
							return null;
						}
						else
						if ( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
							//**echo ' diffcolor'; abs($board->board[$rank][$file]->color - $color_to_move)
							//check if no naarad pinned blocks present
							if(self::checkpinnedrefugees($color_to_move,$board,$starting_square,$ending_square)==true)
								return null;
						}
						else
						{
							//**echo ' blankcolor';
						}
					}
				}
			}

		}
		/*elseif(((($ending_square->rank==0)||($ending_square->rank==9))&&($starting_square->rank>=1)&&($starting_square->rank<=8))&&
		(($ending_square->file>0)&&($ending_square->file<9))&&($board->board[$starting_square->rank][$starting_square->file]->group=='OFFICER')){
			return null;// No Officers are allowed to penetrate the CASTLE if not compromised or not pushed by ROYAL or General
		}*/
		elseif(($board->board[$starting_square->rank][$starting_square->file]!=null)  && (((($ending_square->rank==0)||($ending_square->rank==9))&&($starting_square->rank>=1)&&($starting_square->rank<=8))&&
		(($ending_square->file>0)&&($ending_square->file<9))&&($board->board[$starting_square->rank][$starting_square->file]->group=='SOLDIER'))){
			return null;// No Soldiers are allowed to penetrate the CASTLE  if not compromised
		}
		elseif(((($starting_square->file==0)||($starting_square->file==9))&&(($starting_square->rank==0)||($starting_square->rank==9))
		&&($board->board[$starting_square->rank][$starting_square->file]->group!='NOBLE'))){
			return null;// No-one can escape NoMans except RajRishi and Emperor
		}
		
		$xx=0; $yy=0;
		
		if(($type>=1)&&(abs($x_delta)<2) &&(abs($y_delta)<2))
		{
		}
		elseif ($type>=1){ //Horse
			if ($jumpflag=='1') {
				//$straight jump

				// if Intermediate square contains a enemy piece
				if (($starting_square->rank)-($ending_square->rank)==2) {
					$yy=-1; //**echo ' xx_-1 ';
				}
				if (($ending_square->rank)-($starting_square->rank)==2) {
					$yy=1;//**echo ' xx_1 ';
				}

				if (($starting_square->file)-($ending_square->file)==2) {
					$xx=-1;//**echo ' yy_-1 ';
				}
				
				if (($ending_square->file)-($starting_square->file)==2) {
					$xx=1;//**echo ' yy_1 ';
				}
			
				if (abs($starting_square->rank-$ending_square->rank)==2) {
					$xx=0;//**echo ' yy ';
				} elseif (abs($starting_square->file-$ending_square->file)==2) {
					$yy=0; 	//**echo ' xx ';
				}
			}
			elseif ($jumpflag>='2') {
						//$diagonal jump
						if ((($starting_square->rank)-($ending_square->rank)==2)||(($starting_square->rank)-($ending_square->rank)==1)) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if ((($ending_square->rank)-($starting_square->rank)==2)||(($ending_square->rank)-($starting_square->rank)==1)) {
							$yy=1;//**echo ' xx_1 ';
						}
	
						if ((($starting_square->file)-($ending_square->file)==2)||(($starting_square->file)-($ending_square->file)==1)) {
							$xx=-1;//**echo ' yy_-1 ';
						}
					
						if ((($ending_square->file)-($starting_square->file)==2)||(($ending_square->file)-($starting_square->file)==1)) {
							$xx=1;//**echo ' yy_1 ';
						}
			}
			//**echo ' <br/> Night Move ';
			$intermediate_square = self::try_to_make_square_using_rank_and_file_num($starting_square->rank+$yy, $starting_square->file+$xx);

			if ( ! $intermediate_square ) {
				return null;
			}

			if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
			(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"OFFICER")!==FALSE)) {//if intermediate cell has King or Royal then jumping now allowed
				if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
						return null;//
					}
				}
/*
			if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
				($board->board[$starting_square->rank][$starting_square->file]->group=="ROYAL")) {//if intermediate cell has Full Solidier, Officer or Noble then jumping not allowed
					if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER') || ($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
							return null;//
						}
					}
			if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
					(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"SEMIROYAL")!==FALSE)) {//if intermediate cell has Full ROYAL or Noble then jumping not allowed
						if (($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')){
								return null;//
						}
					}
*/					

			if($board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell has data
				if (/*((($cankill==2) &&($type ==1) ) || ($type==2)) &&*/(abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==0)) {//Same team-member
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||
					($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SEMIROYAL')||
					($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')||
					($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||
					($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER')){
					}
				else
					{
						return null;//
					}
				}
			}

			if($board->board[$ending_square->rank][$ending_square->file]){//Ending has enemy but intermediate is blank
				if(!$board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell is blank data
					if ((($cankill==2)||($cankill==0)) &&(abs($board->board[$ending_square->rank][$ending_square->file]->color - $color_to_move) ==1)) {//Enemy team-member
						$ending_square->mediatorrank=$intermediate_square->rank*-1;
						$ending_square->mediatorfile=$intermediate_square->file*-1;
						//return null;//Horse cannot kill without mixing.... Horse can repel even without mixing
						return $ending_square;//Horse cannot kill without mixing.... Horse can repel even without mixing
						}
					}
				if(($board->board[$intermediate_square->rank][$intermediate_square->file]) &&($cankill==0)){//if intermediate cell also has some data but
					if ((abs($board->board[$ending_square->rank][$ending_square->file]->color - $color_to_move) ==1)) {//Enemy team-member SPY ARTHSHASTRI
						if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
						(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

						}
						else 
						return null;
						}
					}
				}

			if ((($intermediate_square->file==0 ) ||($intermediate_square->file==0 )) &&($intermediate_square->rank>=0 )&&($intermediate_square->rank<=9 )) {
				return null;
			}

			if ( $board->board[$intermediate_square->rank][$intermediate_square->file] ) { //Check Intermediate Block which data
				if ( $board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move==0 ) {
				//**echo ' samecolor';
				}
				else
				if ( abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==1) {
					//**echo ' diffcolor';
					if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
						(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

						}
						else 
							return null;
				}
				else
				{
					//**echo ' blankcolor';
				}
			}
			else
			if ( $board->board[$rank][$file] ) {//Check Ending block which has intermediate block but no data
				if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
				//**echo ' samecolor';
				return null; 
				}
				else
				if ( $board->board[$rank][$file]->color == $color_to_move ) {
					//**echo ' Ending square contains a friendly piece ';
					return null;
				}
				else
				if ( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
					//**echo ' diffcolor'; abs($board->board[$rank][$file]->color - $color_to_move)
					//check if no naarad pinned blocks present
					if(self::checkpinnedrefugees($color_to_move,$board,$starting_square,$ending_square)==true)
					return null;
				}
				else
				{
					//**echo ' blankcolor';
				}
			}
		}

		if(($type>=1)&&($intermediate_square==null))
			return null;

		if(($type==2)&&($intermediate_square!=null)&&($board->board[$intermediate_square->rank][$intermediate_square->file]!=null)){ //ROYALs
			if ( $board->board[$intermediate_square->rank][$intermediate_square->file] ) { //Check Intermediate Block
				if ( $board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move==0 ) {
				//echo ' samecolor';
					
				}
				else
				if ( abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==1) {
				//echo ' diffcolor';				
				//**echo ' diffcolor';
					if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
					(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

					}
					else 
						return null;
				}
				else
				{
				}
			}
			else
			if ( $board->board[$rank][$file] ) {//Check Ending block

				if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
				//echo ' samecolor';
				return null; 
				}
				elseif ( $board->board[$rank][$file]->color == $color_to_move ) {
					return null;
				}
				elseif( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
					//check if no naarad pinned blocks present
					if(self::checkpinnedrefugees($color_to_move,$board,$starting_square,$ending_square)==true)
					return null;
				}
				else
				{
				}
			}
		}
		
		if(($type==1)&&($intermediate_square!=null)&&($board->board[$intermediate_square->rank][$intermediate_square->file]!=null)){
				if ((abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) !=0)) {
					return null;
				}
		}

		// Ending square contains a friendly piece
		if ( $board->board[$rank][$file] ) {
			if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
				//echo ' samecolor';
				return null; 
				}
				elseif ( $board->board[$rank][$file]->color == $color_to_move ) {
					return null;
				}
				elseif( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
					//check if no naarad pinned blocks present and Narad is not Null..
					if(self::checkpinnedrefugees($color_to_move,$board,$starting_square,$ending_square)==true)
					return null;
				}
		}

		//Check the last piece action on Trapped piece or Pushed Piece
		/*if(($intermediate_square!=null)&&($ending_square!=null) &&(($board->board[$intermediate_square->rank][$intermediate_square->file]==null)
		&& (($board->board[$ending_square->rank][$ending_square->file]!=null))	)){
				$settledpiece = clone $board->board[$starting_square->rank][$starting_square->file];
				$endingpiece =clone $board->board[$ending_square->rank][$ending_square->file];
				$settledpiece->square = $intermediate_square;
				self::check_virtual_trapped_piece($settledpiece,$endingpiece,$color_to_move, $board,'exclude');
			}
		*/	

		if($jumpflag==0) {
			$yy=0;
			$xx=0;
			
			if($starting_square->rank >= $ending_square->rank+2){
				$yy=1;
			}
			if($starting_square->rank <= $ending_square->rank -2 ){
				$yy=-1;
			}
			if($starting_square->file >= $ending_square->file +2 ){
				$xx=1;
			}

			if($starting_square->file <= $ending_square->file-2){
				$xx=-1;
			}

			$intermediate_square = self::try_to_make_square_using_rank_and_file_num($ending_square->rank+$yy, $ending_square->file+$xx);
		}

		if($intermediate_square!=null){ 
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]) && 
					(strpos($board->board[$starting_square->rank][$starting_square->file]->group,"OFFICER")!==FALSE)) {//if intermediate cell has Noble or Royals then jumping not allowed
						if ((($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='NOBLE')||($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL'))&&
							(($board->board[$starting_square->rank][$starting_square->file]->color==$board->board[$intermediate_square->rank][$intermediate_square->file]->color))){
								return null;//
							}
						}

			if((($starting_square->rank==1) && ($ending_square->rank==0) && ($intermediate_square->rank==0) && 
			(abs($starting_square->file-$ending_square->file)==2) &&(abs($starting_square->file-$intermediate_square->file)==1) && ($board->bbrokencastle==false)) || 
			(($starting_square->rank==8) && ($ending_square->rank==9) && ($intermediate_square->rank==9) && 
			(abs($starting_square->file-$ending_square->file)==2) &&(abs($starting_square->file-$intermediate_square->file)==1) && ($board->wbrokencastle==false)))
						return null;

			$ending_square->mediatorfile=$intermediate_square->file;
			$ending_square->mediatorrank=$intermediate_square->rank;
		}
		return $ending_square;
	}
	
	// positive X = east, negative X = west, positive Y = north, negative Y = south
	static function piece_exists_and_not_occupied_by_friendly_piece(		
		int $type,
		$jumpflag,
		ChessSquare $starting_square,
		int $y_delta,int $x_delta,
		$color_to_move,
		ChessBoard $board,
		int $cankill,
		$get_FullMover,
		$selfbrokencastle,
		$foebrokencastle
	): ?ChessSquare {
		$xx=0;$yy=0;
		$rank = $starting_square->rank + $y_delta;
		$file = $starting_square->file + $x_delta;

		$ending_square = self::try_to_make_square_using_rank_and_file_num($rank,$file);
		$intermediate_square = null;

		//if(self::checkpinnedrefugees($color_to_move,$board,$rank,$file)==true){
			//$tttt=1;
		//}

		if(($type!=0)&&(($get_FullMover==false)&&($file==-1)||($file==10))){
			if($file==-1){ //War to Truce
				$file=0;}
			else if($file==10){ 	
				$file=9;}

			$intermediate_square=self::try_to_make_square_using_rank_and_file_num ($starting_square->rank,$file);
			if(!$board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell is blank data
					return $intermediate_square;
			}
			return null;
		}
		
		// Ending square is off the board
		if ( ! $ending_square ) {	
			return null;
		}

		/*if(($starting_square->rank==8)&&($starting_square->file==6)&&($ending_square->rank==9)&&($ending_square->file==7))
		{$ttt=1;}
		if(($ending_square->rank==9)&&($ending_square->file==1)&&($starting_square->rank==7)&&($starting_square->file==2))
		{$ttt=1;}*/
		if( (($selfbrokencastle==true)&&( $starting_square->rank==9)&&($ending_square->rank<8)&&($color_to_move==2)||
		($foebrokencastle==true)&&($ending_square->rank>1)&&($starting_square->rank==0)&&($color_to_move==2))||  
		(($selfbrokencastle==true)&&( $starting_square->rank==0)&&($ending_square->rank>1)&&($color_to_move==1)||
		($foebrokencastle==true)&&($ending_square->rank<8)&&($starting_square->rank==9)&&($color_to_move==1)))
		{
			$intermediate_square = null;
		}
		if( ((($selfbrokencastle==true)&&( $starting_square->rank==9)&&($ending_square->rank>=6)&&($color_to_move==2)||
		($foebrokencastle==true)&&($ending_square->rank<=3)&&($starting_square->rank==0)&&($color_to_move==2))||  
		(($selfbrokencastle==true)&&( $starting_square->rank==0)&&($ending_square->rank<=3)&&($color_to_move==1)||
		($foebrokencastle==true)&&($ending_square->rank>=6)&&($starting_square->rank==9)&&($color_to_move==1)))  )
		{ /*
			* Enter into WAR Zone from Compromized CASTLE or move within CASTLE
			*/
			if($type==0) {
				$intermediate_square=$ending_square;
				$intermediate_square=null;
			}
			else
			if($type!=0){
				if(($type==2)&&(abs($x_delta)<2) &&(abs($y_delta)<2))
				{
				}
				else
				if ($type>=1){ 
					if ($jumpflag=='1') {
						// $straight jump
						// if Intermediate square contains a enemy piece
						if (($starting_square->rank)-($ending_square->rank)==2) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if (($ending_square->rank)-($starting_square->rank)==2) {
							$yy=1;//**echo ' xx_1 ';
						}
		
						if (($starting_square->file)-($ending_square->file)==2) {
							$xx=-1;//**echo ' yy_-1 ';
						}
						
						if (($ending_square->file)-($starting_square->file)==2) {
							$xx=1;//**echo ' yy_1 ';
						}
					
						if (abs($starting_square->rank-$ending_square->rank)==2) {
							$xx=0;//**echo ' yy ';
						} elseif (abs($starting_square->file-$ending_square->file)==2) {
							$yy=0; 	//**echo ' xx ';
						}
					}
					elseif ($jumpflag>='2') {
						//$diagonal jump
						if ((($starting_square->rank)-($ending_square->rank)==2)||(($starting_square->rank)-($ending_square->rank)==1)) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if ((($ending_square->rank)-($starting_square->rank)==2)||(($ending_square->rank)-($starting_square->rank)==1)) {
							$yy=1;//**echo ' xx_1 ';
						}
	
						if ((($starting_square->file)-($ending_square->file)==2)||(($starting_square->file)-($ending_square->file)==1)) {
							$xx=-1;//**echo ' yy_-1 ';
						}
					
						if ((($ending_square->file)-($starting_square->file)==2)||(($ending_square->file)-($starting_square->file)==1)) {
							$xx=1;//**echo ' yy_1 ';
						}
					}
					$intermediate_square = self::try_to_make_square_using_rank_and_file_num($starting_square->rank+$yy, $starting_square->file+$xx);
					if ( ! $intermediate_square ) {
						return null;
					}
					
					/* This is doubtfull
					if(  $intermediate_square->rank!=$starting_square->rank ){
						return null;
					}
					*/
		
					if($board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell has data
						if (($cankill==2) &&($type==1)&&(abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==0)) {//Same team-member
							if(($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SEMIROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER')){
							}
						else
							{
								return null;//
							}
						}
					}
		
					if($board->board[$ending_square->rank][$ending_square->file]){//Ending has enemy but intermediate is blank
						if(($jumpflag==1)&&($ending_square->rank!=$intermediate_square->rank)){
							return null;//cannot kill outside of the compromised castle.
						}
						if(!$board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell is blank data
							if (($cankill==2) &&(abs($board->board[$ending_square->rank][$ending_square->file]->color - $color_to_move) ==1)) {//Enemy team-member
								return null;//
								}
							}
						}
			
					if ( $board->board[$intermediate_square->rank][$intermediate_square->file] ) { //Check Intermediate Block
						if ( $board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move==0 ) {
						//**echo ' samecolor';
							
						}
						else
						if ( abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==1) {
							//**echo ' diffcolor';
							//**echo ' diffcolor';
							if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
							(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

							}
							else 
								return null;
						}
						else
						{
							//**echo ' blankcolor';
						}
					}
					else
					if ( $board->board[$rank][$file] ) {//Check Ending block
						if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
						//**echo ' samecolor';
						return null; 
						}
						else
						if ( $board->board[$rank][$file]->color == $color_to_move ) {
							//**echo ' Ending square contains a friendly piece ';
							return null;
						}
						else
						if ( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
							//**echo ' diffcolor'; abs($board->board[$rank][$file]->color - $color_to_move)
							//check if no naarad pinned blocks present
							if(self::checkpinnedrefugees($color_to_move,$board, $starting_square,$ending_square)==true)
								return null;
						}
						else
						{
							//**echo ' blankcolor';
						}
					}
				}
			}
		}
		else
		//No movedown in truce
		if((($ending_square->rank>=1)&&($ending_square->rank<=8)&&($starting_square->rank>=1)&&($starting_square->rank<=8))&&
		(($ending_square->file==0)||($ending_square->file==9)||($starting_square->file==0)||($starting_square->file==9))&&(($ending_square->file==$starting_square->file))){
			return null;
		}
		 //should Fix the issue for General also
		elseif((($selfbrokencastle==true)&&($ending_square->rank==0)&&($color_to_move==1)||
		($foebrokencastle==true)&&($ending_square->rank==9)&&($color_to_move==1)) ||(($selfbrokencastle==true)&&($ending_square->rank==9)&&($color_to_move==2)||
		($foebrokencastle==true)&&($ending_square->rank==0)&&($color_to_move==2))  
		||   (($ending_square->file>0)&&($ending_square->file<9)&&(($ending_square->rank==9)||($ending_square->rank==0))&&($starting_square->rank>1)&&($starting_square->rank<9)&&($color_to_move==1)&&($board->gametype>=1)))

		{ /*
			* Enter into CASTLE as it has become warzone
			*/
			if($type==0) {
				$intermediate_square=$ending_square;
						//$diagonal move
						if ((($starting_square->rank)-($ending_square->rank)>=2)&&(($starting_square->file)-($ending_square->file)>=2)) {
							$yy=-1; $xx=-1 ;
						}
						if ((($starting_square->rank)-($ending_square->rank)>=2)&&(($ending_square->file)-($starting_square->file)>=2)) {
							$yy=-1; $xx=1 ;
						}
						if ((($ending_square->rank)-($starting_square->rank)>=2)&&(($ending_square->file)-($starting_square->file)>=2)) {
							$yy=1; $xx=1 ;
						}
						if ((($ending_square->rank)-($starting_square->rank)>=2)&&(($starting_square->file)-($ending_square->file)>=2)) {
							$yy=1; $xx=-1 ;
						}
					$intermediate_square = self::try_to_make_square_using_rank_and_file_num($starting_square->rank+$yy, $starting_square->file+$xx);
					if ( ! $intermediate_square ) {
						return null;
					}
				}
			else
			if($type!=0){
				if(($type==2)&&(abs($x_delta)<2) &&(abs($y_delta)<2))
				{
				}
				else
				if ($type>=1){ 
					// if Intermediate square contains a enemy piece 
					if ($jumpflag=='1') {
						// $straight jump 		
						// if Intermediate square contains a enemy piece
						if (($starting_square->rank)-($ending_square->rank)==2) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if (($ending_square->rank)-($starting_square->rank)==2) {
							$yy=1;//**echo ' xx_1 ';
						}
		
						if (($starting_square->file)-($ending_square->file)==2) {
							$xx=-1;//**echo ' yy_-1 ';
						}
						
						if (($ending_square->file)-($starting_square->file)==2) {
							$xx=1;//**echo ' yy_1 ';
						}
					
						if (abs($starting_square->rank-$ending_square->rank)==2) {
							$xx=0;//**echo ' yy ';
						} elseif (abs($starting_square->file-$ending_square->file)==2) {
							$yy=0; 	//**echo ' xx ';
						}
					}
					elseif ($jumpflag>='2') {
						//$diagonal jump
						if ((($starting_square->rank)-($ending_square->rank)==2)||(($starting_square->rank)-($ending_square->rank)==1)) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if ((($ending_square->rank)-($starting_square->rank)==2)||(($ending_square->rank)-($starting_square->rank)==1)) {
							$yy=1;//**echo ' xx_1 ';
						}
	
						if ((($starting_square->file)-($ending_square->file)==2)||(($starting_square->file)-($ending_square->file)==1)) {
							$xx=-1;//**echo ' yy_-1 ';
						}
					
						if ((($ending_square->file)-($starting_square->file)==2)||(($ending_square->file)-($starting_square->file)==1)) {
							$xx=1;//**echo ' yy_1 ';
						}
						//$intermediate_square = self::try_to_make_square_using_rank_and_file_num($rank, $file);
					}
					$intermediate_square = self::try_to_make_square_using_rank_and_file_num($starting_square->rank+$yy, $starting_square->file+$xx);
					if ( ! $intermediate_square ) {
						return null;
					}
					
					//No jumping of TRUCE
					if(($intermediate_square->rank!=$starting_square->rank) && (($intermediate_square->file==0)||($intermediate_square->file==9))){
						return null;
					}
		
					if($board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell has data
						if (($cankill==2) &&($type==1)&&(abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==0)) {//Same team-member
							if(($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SEMIROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER')){
							}
						else
							{
								return null;//
							}
						}
					}
		
					if($board->board[$ending_square->rank][$ending_square->file]){//Ending has enemy but intermediate is blank
						if(!$board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell is blank data
							if (($cankill==2) &&(abs($board->board[$ending_square->rank][$ending_square->file]->color - $color_to_move) ==1)) {//Enemy team-member
								return null;//
								}
							}
						}
		
					if ((($intermediate_square->file==0 ) ||($intermediate_square->file==9 )) &&($intermediate_square->rank>=0 )&&($intermediate_square->rank<=9 )) {
						return null;
					}
		
					if ( $board->board[$intermediate_square->rank][$intermediate_square->file] ) { //Check Intermediate Block
						if ( $board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move==0 ) {
							//**echo ' samecolor';							
						}
						else
						if ( abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==1) {
							//**echo ' diffcolor';
							//**echo ' diffcolor';
							if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
							(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

							}
							else 
								return null;
						}
						else { /*echo ' blankcolor';*/ }
					}
					else
					if ( $board->board[$rank][$file] ) {//Check Ending block
						if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
						//**echo ' samecolor';
						return null; 
						}
						else
						if ( $board->board[$rank][$file]->color == $color_to_move ) {
							//**echo ' Ending square contains a friendly piece ';
							return null;
						}
						else
						if ( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
							//**echo ' diffcolor'; abs($board->board[$rank][$file]->color - $color_to_move)
							//check if no naarad pinned block
							if(self::checkpinnedrefugees($color_to_move,$board,$starting_square,$ending_square)==true)
								return null;
						}
						else { /*echo ' blankcolor';*/ }
					}
				}
			}
		}
		else
		if(($selfbrokencastle==true)&&($ending_square->rank==9)&&($color_to_move==2)||
		($foebrokencastle==true)&&($ending_square->rank==0)&&($color_to_move==2))
		{ /*
			* Enter into CASTLE as it has become warzone
			*/
			if($type==0) {
				//$intermediate_square=$ending_square;
				$intermediate_square=null;
			}
			else
			if($type!=0){
				if(($type==2)&&(abs($x_delta)<2) &&(abs($y_delta)<2))
				{
				}
				else
				if ($type>=1){ //Horse =1 King or General = 2 
					// if Intermediate square contains a enemy piece 
					if ($jumpflag=='1') {
						// $straight jump
						// if Intermediate square contains a enemy piece
						if (($starting_square->rank)-($ending_square->rank)==2) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if (($ending_square->rank)-($starting_square->rank)==2) {
							$yy=1;//**echo ' xx_1 ';
						}
		
						if (($starting_square->file)-($ending_square->file)==2) {
							$xx=-1;//**echo ' yy_-1 ';
						}
						
						if (($ending_square->file)-($starting_square->file)==2) {
							$xx=1;//**echo ' yy_1 ';
						}
					
						if (abs($starting_square->rank-$ending_square->rank)==2) {
							$xx=0;//**echo ' yy ';
						} elseif (abs($starting_square->file-$ending_square->file)==2) {
							$yy=0; 	//**echo ' xx ';
						}
					}
					elseif ($jumpflag>='2') {
						//$diagonal jump
						if ((($starting_square->rank)-($ending_square->rank)==2)||(($starting_square->rank)-($ending_square->rank)==1)) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if ((($ending_square->rank)-($starting_square->rank)==2)||(($ending_square->rank)-($starting_square->rank)==1)) {
							$yy=1;//**echo ' xx_1 ';
						}
	
						if ((($starting_square->file)-($ending_square->file)==2)||(($starting_square->file)-($ending_square->file)==1)) {
							$xx=-1;//**echo ' yy_-1 ';
						}
					
						if ((($ending_square->file)-($starting_square->file)==2)||(($ending_square->file)-($starting_square->file)==1)) {
							$xx=1;//**echo ' yy_1 ';
						}
					}
					$intermediate_square = self::try_to_make_square_using_rank_and_file_num($starting_square->rank+$yy, $starting_square->file+$xx);

					//**echo ' <br/> Night Move ';
					if ( ! $intermediate_square ) {
						return null;
					}
					if(  $intermediate_square->rank!=$starting_square->rank ){
						return null;
					}
		
					if($board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell has data
						if ((($cankill==2) && ($type==1))&&(abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==0)) {//Same team-member
							if(($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SEMIROYAL')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||
							($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER')){
							}
						else
							{
								return null;//knight Cannot kill without intermidetiate.. King or General can still move even if these
							}
						}
						else
						if ((abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) !=0)) {
								return null;
						}
					}
		
					if($board->board[$ending_square->rank][$ending_square->file]){//Ending has enemy but intermediate is blank
						if(!$board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell is blank data
							if (($cankill==2) &&(abs($board->board[$ending_square->rank][$ending_square->file]->color - $color_to_move) ==1)) {//Enemy team-member
								return null;//
								}
							}
						}
		
					if ((($intermediate_square->file==0 ) ||($intermediate_square->file==9 )) &&($intermediate_square->rank>=0 )&&($intermediate_square->rank<=9 )) {
						return null;
					}
		
					if ( $board->board[$intermediate_square->rank][$intermediate_square->file] ) { //Check Intermediate Block
						if ( $board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move==0 ) {
						//**echo ' samecolor';							
						}
						else
						if ( abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==1) {
						//**echo ' diffcolor';
						//**echo ' diffcolor';
						if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
						(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

						}
						else 
							return null;
						}
						else { /*echo ' blankcolor';*/ }
					}
					else
					if ( $board->board[$rank][$file] ) {//Check Ending block
						if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
						//**echo ' samecolor';
						return null; 
						}
						else
						if ( $board->board[$rank][$file]->color == $color_to_move ) {
							//**echo ' Ending square contains a friendly piece ';
							return null;
						}
						else
						if ( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
							//**echo ' diffcolor'; abs($board->board[$rank][$file]->color - $color_to_move)
							//check if no naarad pinned blocks present
							if(self::checkpinnedrefugees($color_to_move,$board,$starting_square,$ending_square)==true)
								return null;
						}
						else { /*echo ' blankcolor';*/ }
					}
				}
			}

		}
		/*elseif(((($ending_square->rank==0)||($ending_square->rank==9))&&($starting_square->rank>=1)&&($starting_square->rank<=8))&&
		(($ending_square->file>0)&&($ending_square->file<9))&&($board->board[$starting_square->rank][$starting_square->file]->group=='OFFICER')){
			return null;// No Officers are allowed to penetrate the CASTLE if not compromised or not pushed by ROYAL or General
		}*/
		elseif(((($ending_square->rank==0)||($ending_square->rank==9))&&($starting_square->rank>=1)&&($starting_square->rank<=8))&&
		(($ending_square->file>0)&&($ending_square->file<9))&&($board->board[$starting_square->rank][$starting_square->file]->group=='SOLDIER')){
			return null;// No Soldiers are allowed to penetrate the CASTLE  if not compromised
		}
		elseif(((($starting_square->file==0)||($starting_square->file==9))&&(($starting_square->rank==0)||($starting_square->rank==9))
		&&($board->board[$starting_square->rank][$starting_square->file]->group!='NOBLE'))){
			return null;// No-one can escape NoMans except RajRishi and Emperor
		}
		
		$xx=0; $yy=0;
		
		if(($type>=1)&&(abs($x_delta)<2) &&(abs($y_delta)<2))
		{
		}
		elseif ($type>=1){ //Horse
			if ($jumpflag=='1') {
				// straight jump
				// if Intermediate square contains a enemy piece
				if (($starting_square->rank)-($ending_square->rank)==2) {
					$yy=-1; //**echo ' xx_-1 ';
				}
				if (($ending_square->rank)-($starting_square->rank)==2) {
					$yy=1;//**echo ' xx_1 ';
				}

				if (($starting_square->file)-($ending_square->file)==2) {
					$xx=-1;//**echo ' yy_-1 ';
				}
				
				if (($ending_square->file)-($starting_square->file)==2) {
					$xx=1;//**echo ' yy_1 ';
				}
			
				if (abs($starting_square->rank-$ending_square->rank)==2) {
					$xx=0;//**echo ' yy ';
				} elseif (abs($starting_square->file-$ending_square->file)==2) {
					$yy=0; 	//**echo ' xx ';
				}
			}
			elseif ($jumpflag>='2') {
						//$diagonal jump
						if ((($starting_square->rank)-($ending_square->rank)==2)||(($starting_square->rank)-($ending_square->rank)==1)) {
							$yy=-1; //**echo ' xx_-1 ';
						}
						if ((($ending_square->rank)-($starting_square->rank)==2)||(($ending_square->rank)-($starting_square->rank)==1)) {
							$yy=1;//**echo ' xx_1 ';
						}
	
						if ((($starting_square->file)-($ending_square->file)==2)||(($starting_square->file)-($ending_square->file)==1)) {
							$xx=-1;//**echo ' yy_-1 ';
						}
					
						if ((($ending_square->file)-($starting_square->file)==2)||(($ending_square->file)-($starting_square->file)==1)) {
							$xx=1;//**echo ' yy_1 ';
						}
			}
			//**echo ' <br/> Night Move ';
			$intermediate_square = self::try_to_make_square_using_rank_and_file_num($starting_square->rank+$yy, $starting_square->file+$xx);

			if ( ! $intermediate_square ) {
				return null;
			}

			if($board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell has data
				if (/*((($cankill==2) &&($type ==1) ) || ($type==2)) &&*/(abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==0)) {//Same team-member
					if(($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='ROYAL')||
					($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SEMIROYAL')||
					($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='OFFICER')||
					($board->board[$intermediate_square->rank][$intermediate_square->file]->group=='SOLDIER')){
					}
				else
					{
						return null;//
					}
				}
			}

			if($board->board[$ending_square->rank][$ending_square->file]){//Ending has enemy but intermediate is blank
				if(!$board->board[$intermediate_square->rank][$intermediate_square->file]){//if intermediate cell is blank data
					if ((($cankill==2)||($cankill==0)) &&(abs($board->board[$ending_square->rank][$ending_square->file]->color - $color_to_move) ==1)) {//Enemy team-member
						return null;//Horse cannot kill without mixing
						}
					}
				if(($board->board[$intermediate_square->rank][$intermediate_square->file]) &&($cankill==0)){//if intermediate cell also has some data but
					if ((abs($board->board[$ending_square->rank][$ending_square->file]->color - $color_to_move) ==1)) {//Enemy team-member SPY ARTHSHASTRI
							return null;//SPY ARTHSHASTRI cant kill
						}
					}
				}

			if ((($intermediate_square->file==0 ) ||($intermediate_square->file==0 )) &&($intermediate_square->rank>=0 )&&($intermediate_square->rank<=9 )) {
				return null;
			}

			if ( $board->board[$intermediate_square->rank][$intermediate_square->file] ) { //Check Intermediate Block which data
				if ( $board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move==0 ) {
					//**echo ' samecolor';
				}
				else
				if ( abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==1) {
					//**echo ' diffcolor';
					//**echo ' diffcolor';
					if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
						(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

					}
					else 
						return null;
				}
				else { /*echo ' blankcolor';*/ }
			}
			else
			if ( $board->board[$rank][$file] ) {//Check Ending block which has intermediate block but no data
				if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
				//**echo ' samecolor';
				return null; 
				}
				else
				if ( $board->board[$rank][$file]->color == $color_to_move ) {
					//**echo ' Ending square contains a friendly piece ';
					return null;
				}
				else
				if ( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
					//**echo ' diffcolor'; abs($board->board[$rank][$file]->color - $color_to_move)
					//check if no naarad pinned blocks present
					if(self::checkpinnedrefugees($color_to_move,$board,$starting_square,$ending_square)==true)
					return null;
				}
				else { /*echo ' blankcolor';*/ }
			}
		}

		if(($type>=1)&&($intermediate_square==null))
			return null;

		if(($type==2)&&($intermediate_square!=null)&&($board->board[$intermediate_square->rank][$intermediate_square->file]!=null)){ //ROYALs
			if ( $board->board[$intermediate_square->rank][$intermediate_square->file] ) { //Check Intermediate Block
				if ( $board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move==0 ) {
					//echo ' samecolor';
				}
				else
				if ( abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) ==1) {
					//echo ' diffcolor';
					//**echo ' diffcolor';
					if(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::SPY) ||
					(($board->board[$starting_square->rank][$starting_square->file]->type==ChessPiece::KING) && ($board->board[$intermediate_square->rank][$intermediate_square->file]->type==ChessPiece::GODMAN))){

					}
					else 
						return null;
				}
				else { }
			}
			else
			if ( $board->board[$rank][$file] ) {//Check Ending block

				if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
				//echo ' samecolor';
				return null; 
				}
				elseif ( $board->board[$rank][$file]->color == $color_to_move ) {
					return null;
				}
				elseif( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
					//check if no naarad pinned blocks present
					if(self::checkpinnedrefugees($color_to_move,$board,$starting_square,$ending_square)==true)
					return null;
				}
				else { }
			}
		}
		
		if(($type==1)&&($intermediate_square!=null)&&($board->board[$intermediate_square->rank][$intermediate_square->file]!=null)){
				if ((abs($board->board[$intermediate_square->rank][$intermediate_square->file]->color - $color_to_move) !=0)) {
					return null;
				}
		}

		// Ending square contains a friendly piece
		if ( $board->board[$rank][$file] ) {
			if ( $board->board[$rank][$file]->color - $color_to_move==0 ) {
				//echo ' samecolor';
				return null; 
				}
				elseif ( $board->board[$rank][$file]->color == $color_to_move ) {
					return null;
				}
				elseif( abs($board->board[$rank][$file]->color - $color_to_move) ==1) {
					//check if no naarad pinned blocks present
					if(self::checkpinnedrefugees($color_to_move,$board,$starting_square,$ending_square)==true)
					return null;
				}
		}

		//Check the last piece action on Trapped piece or Pushed Piece
		/*if(($intermediate_square!=null)&&($ending_square!=null) &&(($board->board[$intermediate_square->rank][$intermediate_square->file]==null)
		&& (($board->board[$ending_square->rank][$ending_square->file]!=null))	)){
				$settledpiece = clone $board->board[$starting_square->rank][$starting_square->file];
				$endingpiece =clone $board->board[$ending_square->rank][$ending_square->file];
				$settledpiece->square = $intermediate_square;
				self::check_virtual_trapped_piece($settledpiece,$endingpiece,$color_to_move, $board,'exclude');
			}
		*/	
		return $ending_square;
	}
		
	static function try_to_make_square_using_rank_and_file_num(int $rank, int $file): ?ChessSquare {
		if ( $rank >= 0 && $rank <=9  && $file >= 0 && $file <= 9 ) {
			return new ChessSquare($rank, $file);
		} else {
			return null;
		}
	}
	
	static function invert_color($color) {
		if ( $color == ChessPiece::WHITE ) {
			return ChessPiece::BLACK;
		} else {
			return ChessPiece::WHITE;
		}
	}
	
	static function get_squares_in_these_directions(
		ChessSquare $starting_square,
		array $directions_list,
		int $spaces
	): array {
		$list_of_squares = array();
		foreach ( $directions_list as $direction ) {
			// $spaces should be 1 for king, 1 or 2 for pawns, 7 for all other sliding pieces
			// 7 is the max # of squares you can slide on a chessboard
			
			$current_xy = self::DIRECTION_OFFSETS[$direction];
			$current_xy[0] =  $current_xy[0] * $spaces + $starting_square->rank;
			$current_xy[1] =  $current_xy[1] * $spaces + $starting_square->file;
			
			$square = self::try_to_make_square_using_rank_and_file_num($current_xy[0], $current_xy[1]);
			
			if ( $square ) {
				$list_of_squares[] = $square;
			}
		}
		
		return $list_of_squares;
	}
	
	static function square_is_attacked(
		$enemy_color,
		ChessBoard $board,
		ChessSquare $square_to_check
	): bool {
		$friendly_color = self::invert_color($enemy_color);
		
		if ( self::square_threatened_by_sliding_pieces($board, $square_to_check, $friendly_color) ) {
			return TRUE;
		}
		
		if ( self::square_threatened_by_jumping_pieces($board, $square_to_check, $friendly_color) ) {
			return TRUE;
		}
				
		return FALSE;
	}

	static function square_threatened_by_sliding_pieces(
		ChessBoard $board,
		ChessSquare $square_to_check,
		$friendly_color
	): bool {
		foreach ( self::ALL_DIRECTIONS as $direction ) {
			for ( $i = 1; $i <= self::MAX_SLIDING_DISTANCE; $i++ ) {
				$current_xy = self::DIRECTION_OFFSETS[$direction];
				$rank = $square_to_check->rank + $current_xy[0] * $i;
				$file = $square_to_check->file + $current_xy[1] * $i;
				
				if ( ! self::square_is_on_board($rank, $file) ) {
					// Square is off the board. Stop sliding in this direction.
					break;
				}
				
				$piece = self::get_piece($rank, $file, $board);
				
				if ( ! $piece ) {
					// Square is empty. Continue sliding in this direction.
					continue;
				}
				
				if ( $piece->color == $friendly_color ) {
					// Sliding is blocked by a friendly piece. Stop sliding in this direction.
					break;
				}
				
				// If this code is reached, piece must be an enemy. No need to double check.				
				// I could probably structure this to be faster, but I did it this way for readability.
				if (( $piece->type == ChessPiece::KING )||( $piece->type == ChessPiece::INVERTEDKING )) {
					if ( $i == 1 ) {
						return TRUE;
					}
				} elseif ( $piece->type == ChessPiece::GENERAL ) {
					if ( $direction == self::NORTH || $direction == self::SOUTH || $direction == self::EAST || $direction == self::WEST || $direction == self::NORTHEAST || $direction == self::NORTHWEST || $direction == self::SOUTHEAST || $direction == self::SOUTHWEST ) {
						return TRUE;
					}
				} elseif ( $piece->type == ChessPiece::ROOK ) {
					if ( $direction == self::NORTH || $direction == self::SOUTH || $direction == self::EAST || $direction == self::WEST ) {
						return TRUE;
					}
				} elseif (( $piece->type == ChessPiece::BISHOP )&&($file==0)) {
					if ( $direction == self::NORTHEAST || $direction == self::NORTHWEST || $direction == self::SOUTHEAST || $direction == self::SOUTHWEST ) {
						return TRUE;
					}
				} elseif ( $piece->type == ChessPiece::PAWN ) {
					if ( $i == 1 ) {
						if ( $piece->color == ChessPiece::BLACK ) {
						if ( $direction == self::NORTH|| $direction == self::NORTHEAST || $direction == self::NORTHWEST ) {
								return TRUE;
							}
							if ( $direction == self::NORTH) {
								return TRUE;
							}
						} elseif ( $piece->color == ChessPiece::WHITE ) {
							if ($direction == self::SOUTH|| $direction == self::SOUTHEAST || $direction == self::SOUTHWEST ) {
								return TRUE;
							}
							if ( $direction == self::SOUTH) {
								return TRUE;
							}
						}
					}
				}
				
				// If this code has been reached, then there is an enemy piece on this square
				// but it is not threatening the test square. Stop sliding in this direction.
				break;
			}
		}
		
		return FALSE;
	}
	
	//Pending http://localhost/pc/?move=1c2ia2c1%2Fcr1esge1rc%2Fcpppppp2c%2F11n1%2F181%2F11N4P1%2F13S1%2FCPPPPPP1P1%2FCR1E1GE1RC%2F1C2AI2C1+b+-+-+1+10
	//Black Queen is erasing data
	static function square_threatened_by_jumping_pieces(
		ChessBoard $board,
		ChessSquare $square_to_check,
		$friendly_color
	): bool {
		foreach ( self::KNIGHT_DIRECTIONS as $oclock ) {
			$current_xy = self::OCLOCK_OFFSETS[$oclock];
			$rank = $square_to_check->rank + $current_xy[0];//Row 3+
			$file = $square_to_check->file + $current_xy[1];
			////**echo  '<br> Night = X '.$current_xy[0].'  Y = '.$current_xy[1].'  Calculated Rank '.$rank.'  Calculated File '.$file ;

			if ( ! self::square_is_on_board($rank, $file) ) {
				// Square is off the board. On to the next test square.
				continue; // Check Next Square
			}
			
			$piece = self::get_piece($rank, $file, $board);
			////**echo ' $piece->type = '.$piece->type;
			if ( ! $piece ) {
				// Square is empty. On to the next test square.
				$mixingsquare='';
				continue;
			}
			
			if ( $piece->color == $friendly_color ) {
				// Square is occupied by a friendly piece. On to the next test square.
				continue;
			}
			
			if (( $piece->type == ChessPiece::KNIGHT ) ||( $piece->type == ChessPiece::GENERAL )) {  //If target piece is enemy and not threatening the opponent under R. Create a function.				
				return TRUE;
			}
			
			// If this code has been reached, then there is an enemy piece on this square
			// but it is not threatening the test square. On to the next square.
			// continue;
			//**echo ' Rank '.$square_to_check->rank.' File '.$square_to_check->file.'  Color '.$friendly_color.' Final Bad ='.$square_to_check->rank.' '.$square_to_check->file.'  '.$friendly_color.'<br/> *** Next Square to be checked <br/>';
		}
		//**echo '<br/>  Rank '.$square_to_check->rank.' File '.$square_to_check->file.'  Color '.$friendly_color.' Final Bad ='.$square_to_check->rank.' '.$square_to_check->file.'  '.$friendly_color;
		return FALSE;
	}	

	static function square_is_on_board(int $rank, int $file): bool {
		if ( $rank >= 0 && $rank <= 9 && $file >= 0 && $file <= 9 ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	static function get_piece(int $rank, int $file, ChessBoard $board): ?ChessPiece {
		if ( $board->board[$rank][$file] ) {
			return $board->board[$rank][$file];
		} else {
			return NULL;
		}
	}
}
