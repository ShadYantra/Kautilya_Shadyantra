<?php

class ChessMove {
	const PIECE_LETTERS = array(
		ChessPiece::PAWN => 'p',
		ChessPiece::KNIGHT => 'h',
		ChessPiece::BISHOP => 'g',
		ChessPiece::ROOK => 'm',
		ChessPiece::GENERAL => 's',
		ChessPiece::KING => 'i',
		ChessPiece::SIMPLEKING => 'r',

		ChessPiece::INVERTEDKING => 'j',
		ChessPiece::ARTHSHASTRI => 'a' ,
		ChessPiece::CAPTUREDSCEPTRE =>'ö',
		ChessPiece::RAJYAPAALARTHSHASTRI => 'ä',
		ChessPiece::SPY => 'c' ,
		ChessPiece::GODMAN => 'n'
		//self::RAAJDAND=>'°',
		//self::ARTHDAND=>'´',

	);
	
	public $starting_square;
	public $ending_square;
	public $pushedending_square;
	public $powermovedpiece;
	public $powermovedflag="";

	public $powermovedbeforepromotiontype="";
	public $powermovedpromotiontype="";
	public $powermovedpromotiongroup="";

	public $ending_square_piecetype;
	public $capturedking=FALSE;
	public $color;
	public $piece_type;
	public $capture;
	public $check = FALSE;
	public $checkmate = FALSE;
	public $promotion_piece_type = NULL; // Use the setter to change this. Keeping public so it can be read publicly.
	public $tamedpawn = 0; // Use the setter to change this. Keeping public so it can be read publicly.
	public $demotion_piece_type = NULL; // Use the setter to change this. Keeping public so it can be read publicly.	
	public $en_passant = FALSE;
	public $disambiguation = '';
	public $castling = FALSE;
	public $controlled_moves=null;
	public $controlled_move=False;
	public $board;
	public $CommonBorderOpen_Status=0;
	
	function __construct(		
		ChessSquare $starting_square,
		ChessSquare $ending_square,
		ChessSquare $pushedending_square,
		//$ending_square_type,
		int $ranking, /* 0 Nothing, 1 = Promotion -1 = Demotion*/		
		$color,
		$piece_type,
		bool $capture,
		ChessBoard $old_board,
		bool $store_board = TRUE,
		bool $sameplace,
		bool $controlled_move,
		int $CommonBorderOpen_Status,
		ChessSquare $touchedsquare = null,
		$mode = 'F'
		 //R=Reversed Squares, // N/F= Normal Squares

	) {

		if($mode=='z'){	$this->starting_square = $starting_square; $this->ending_square = $ending_square;
			$this->powermovedflag = 'z';$this->powermovedpiece = $pushedending_square;
			$this->piece_type=$old_board->board[$starting_square->rank][$starting_square->file]->type;

			$this->powermovedbeforepromotiontype=$old_board->board[$pushedending_square->rank][$pushedending_square->file]->type;

		} else
		{ $this->starting_square = $starting_square;
			$this->ending_square = $ending_square;
		}
		/*if($piece_type==9)
			$ttt=1;*/
		//Add the Selfpushed logic here
		//if($this->board)
		$this->color = $color;
		if($mode!='z')
			$this->piece_type = $piece_type;
		$this->capture = $capture;
		
		// Adding $store_board sped up the code by 300ms
		if($controlled_move==true)
			$store_board=false;
		$this->controlled_move = $controlled_move;

	
		if ( $store_board ) {
			$this->board = clone $old_board;

			if($CommonBorderOpen_Status==1)
				$this->board->commonborderbreached = true;
			// Perft uses an empty move to store a board. If not empty move, modify the board.
			if ( $this->starting_square ) {
				$movetype=$this->board->make_move($starting_square, $ending_square,$capture, $sameplace);
				if(($movetype==2) && ($this->board->board[$ending_square->rank][$ending_square->file])!=null){
					if($mode=='R'){	$this->pushedending_square = clone $starting_square; 
						//intermediate square = $pushedending_square;
					}
					else
					$this->pushedending_square=clone $this->board->board[$ending_square->rank][$ending_square->file]->selfpushedpiece->square;
						}
				if($movetype==1){
					$this->pushedending_square=null;//clone $this->board->board[$starting_square][$ending_square]->selfpushedpiece;
				}			
			}


			////Add the logic to add special flag to consider the npiece also;
			if ( $this->touchedsquare!=null ) {
				$movetype=$this->board->make_move($starting_square, $ending_square,$capture, $sameplace);
				if(($movetype==2) && ($this->board->board[$ending_square->rank][$ending_square->file])!=null){
					if($mode=='R'){	$this->pushedending_square = clone $starting_square; 
						//intermediate square = $pushedending_square;
					}
					else
					$this->pushedending_square=clone $this->board->board[$ending_square->rank][$ending_square->file]->selfpushedpiece->square;
						}
				if($movetype==1){
					$this->pushedending_square=null;//clone $this->board->board[$starting_square][$ending_square]->selfpushedpiece;
				}
			}

		}
	}
	
	// Do a deep clone. Needed for pawn promotion.
	function __clone() {
		$this->starting_square = clone $this->starting_square;
		$this->ending_square = clone $this->ending_square;
		if ( $this->board ) {
			$this->board = clone $this->board;
		}
	}

function set_killed_king($killedKing):void{
		$this->capturedking = $killedKing;   

}

	function set_demotion_piece($piece_type): void {
		$rank = $this->ending_square->rank;
		$file = $this->ending_square->file;
		if ( $this->board ) {
			$this->board->board[$rank][$file]->type = $piece_type;
		}
		
		// update the notation
		$this->demotion_piece_type = $piece_type;
	}

	function set_promotion_piece($piece_type): void {
	
		// update the piece
		$rank = $this->ending_square->rank;
		$file = $this->ending_square->file;
		if ( $this->board ) {
			$this->board->board[$rank][$file]->type = $piece_type;
		}
		
		// update the notation
		$this->promotion_piece_type = $piece_type;
	}

	function set_touched_promotion_piece($piece_type): void {
	
		if ( $this->board ) {
		$this->powermovedpromotiontype = $piece_type;
		}
	}	

	function get_notation(): string {
		$pcolor = NULL;
		$string = '';
		$char = self::PIECE_LETTERS[$this->piece_type];
		$kingsquare = NULL;
		if ($this->color == ChessPiece::WHITE)
		{
			if (($char=='a')|| ($char=='i')||($char=='c')|| ($char=='u')|| ($char=='y')||($char=='j')) {
				$pcolor = strtoupper($char);
			}
			else
				$pcolor =	strtoupper(self::PIECE_LETTERS[$this->piece_type]);
		}
		elseif($this->color == ChessPiece::BLACK){
			$pcolor = strtolower($char)	;
		}


		if ( $this->tamedpawn !== 0 ) {
		$ttt=1;
		}
			// type of piece
			if ( $this->piece_type == ChessPiece::PAWN){// && $this->capture ) {
				$string .= $pcolor;
			} elseif ( $this->piece_type != ChessPiece::PAWN ) {
				$string .= $pcolor;
			}
				
			if ( $this->piece_type == ChessPiece::GENERAL ) {
				$string = $string;
			}	
			// capture?
			if ( $this->capture ) {
				$string .= '*';
			}
			else if (($this->capture ==false ) && ($this->board==null)&& ($this->controlled_move==true)  ) {
				$string .= $this->starting_square->get_alphanumeric();
				$string .= $this->ending_square->get_alphanumeric();
				return $string;
			}			
			else if (($this->capture ==false ) && ($this->board->board[$this->ending_square->rank][$this->ending_square->file]!=null) && 
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushed==true)  &&
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushedsquare!=null)&& ( $this->tamedpawn == 0 )) {
				$string .= '>';
			}
			else if (($this->board->board[$this->ending_square->rank][$this->ending_square->file]!=null) && 
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushed==true)  &&
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushedsquare!=null) && ( $this->tamedpawn !== 0 ) ) {
				$string .= '-';
				$this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushedpiece->type=13;
			}
			// destination square
			$string .= $this->starting_square->get_alphanumeric();
			$string .= $this->ending_square->get_alphanumeric();

			if (($this->capture ==false ) && ($this->board->board[$this->ending_square->rank][$this->ending_square->file]!=null) && 
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushed==true)  &&
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushedsquare!=null)) {
				//$string .= '>';
				$getsquare=$this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushedsquare;
				$this->ending_square->set_square($getsquare["file"] ,$getsquare["rank"]);
				//$this->ending_square->set_square(8 ,8);
				$string .= $this->ending_square->get_alphanumeric();
			}
			
			if($this->color==1)
			$kingsquare=$this->board->bkingsquare;//opponent square
			if($this->color==2)
			$kingsquare=$this->board->wkingsquare;//			
			
			if($kingsquare!=null){
				if ($this->board->board[$kingsquare->rank][$kingsquare->file]!=null) {
					if (($this->ending_square->rank == $kingsquare->rank )&&($this->ending_square->file == $kingsquare->file )) {
						$string .= '#';
					}
				}
			}

		if($this->color == ChessPiece::BLACK){
			/*if ( $this->tamedpawn == true ) {
				$string .= '=P';
			} 
			else*/ {
				if ( $this->promotion_piece_type == ChessPiece::GENERAL ) {
				$string .= '=s';
			} elseif ( $this->promotion_piece_type == ChessPiece::ROOK ) {
				$string .= '=m';
			} elseif ( $this->promotion_piece_type == ChessPiece::BISHOP ) {
				$string .= '=g';
			} elseif ( $this->promotion_piece_type == ChessPiece::KNIGHT ) {
				$string .= '=h';
			}

			//later will change the royal_piece_type
			else if ( $this->promotion_piece_type == ChessPiece::KING ) {
				$string .= '=i';
			} elseif ( $this->promotion_piece_type == ChessPiece::INVERTEDKING ) {
				$string .= '=j';
		 	} elseif ( $this->promotion_piece_type == ChessPiece::VIKRAMADITYA) {
				$string .= '=v';
			} elseif ( $this->promotion_piece_type == ChessPiece::RAJYAPAALARTHSHASTRI) {
				$string .= '=Ä';
			}elseif ( $this->promotion_piece_type == ChessPiece::ARTHSHASTRI) {
				$string .= '=a';
			}

			if ( $this->demotion_piece_type == ChessPiece::KING ) {
				$string .= '=i';
			} elseif ( $this->demotion_piece_type == ChessPiece::INVERTEDKING ) {
				$string .= '=i';
			}elseif ( $this->demotion_piece_type == ChessPiece::ARTHSHASTRI) {
				$string .= '=a';
			}
			elseif ( $this->demotion_piece_type == ChessPiece::CAPTUREDSCEPTRE) {
				$string .= '=o';
			}

			if (( $this->demotion_piece_type!= $this->piece_type) &&( $this->promotion_piece_type!= $this->piece_type)&& (( $this->piece_type == ChessPiece::ROOK)||( $this->piece_type == ChessPiece::BISHOP)||( $this->piece_type == ChessPiece::KNIGHT)||( $this->piece_type == ChessPiece::GENERAL) ||( $this->piece_type == ChessPiece::PAWN))&&((( $this->color=='1')&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')))||
			(( $this->color=='2')&&(($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))))){
				$string .= 'o';
			}
			elseif (( $this->demotion_piece_type!= $this->piece_type) &&( $this->promotion_piece_type!= $this->piece_type)&& (( $this->piece_type == ChessPiece::ROOK)||( $this->piece_type == ChessPiece::BISHOP)||( $this->piece_type == ChessPiece::KNIGHT)||( $this->piece_type == ChessPiece::GENERAL) ||( $this->piece_type == ChessPiece::PAWN))&&((( $this->color=='1')&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')))||
			(( $this->color=='2')&&(($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))))){
				$string .= 'o';
			}
			elseif ((($this->promotion_piece_type == ChessPiece::BISHOP)||( $this->promotion_piece_type == ChessPiece::KNIGHT))&&($this->piece_type==ChessPiece::SPY)&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')||($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))) {
				$string .= 'o';
			}
			elseif ((($this->promotion_piece_type == ChessPiece::BISHOP)||( $this->promotion_piece_type == ChessPiece::KNIGHT)||($this->promotion_piece_type == ChessPiece::GENERAL)||( $this->promotion_piece_type == ChessPiece::ROOK))&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')||($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))) {
				$string .= 'o';
			}
		}
		}
		else
		{

			/*if ( $this->tamedpawn == true ) {
				$string .= '=p';
			} 			
			else*/{
				if ( $this->promotion_piece_type == ChessPiece::GENERAL ) {
					$string .= '=S';
				} elseif ( $this->promotion_piece_type == ChessPiece::ROOK ) {
					$string .= '=M';
				} elseif ( $this->promotion_piece_type == ChessPiece::BISHOP ) {
					$string .= '=G';
				} elseif ( $this->promotion_piece_type == ChessPiece::KNIGHT ) {
					$string .= '=H';
				}
	
				//later will change the royal_piece_type
				if ( $this->promotion_piece_type == ChessPiece::KING ) {
					$string .= '=I';
				} elseif ( $this->promotion_piece_type == ChessPiece::INVERTEDKING ) {
					$string .= '=J';
				 } elseif ( $this->promotion_piece_type == ChessPiece::VIKRAMADITYA) {
					$string .= '=V';
				} elseif ( $this->promotion_piece_type == ChessPiece::RAJYAPAALARTHSHASTRI) {
					$string .= '=Ä';
				}elseif ( $this->promotion_piece_type == ChessPiece::ARTHSHASTRI) {
					$string .= '=A';
				}
	
				if ( $this->demotion_piece_type == ChessPiece::KING ) {
					$string .= '=I';
				} elseif ( $this->demotion_piece_type == ChessPiece::INVERTEDKING ) {
					$string .= '=J';
				}elseif ( $this->demotion_piece_type == ChessPiece::ARTHSHASTRI) {
					$string .= '=A';
				}
				elseif ( $this->demotion_piece_type == ChessPiece::CAPTUREDSCEPTRE) {
					$string .= '=Ö';
				}
	
				if (( $this->demotion_piece_type!= $this->piece_type) &&( $this->promotion_piece_type!= $this->piece_type)&& (( $this->piece_type == ChessPiece::ROOK)||( $this->piece_type == ChessPiece::BISHOP)||( $this->piece_type == ChessPiece::KNIGHT)||( $this->piece_type == ChessPiece::GENERAL) ||( $this->piece_type == ChessPiece::PAWN))&&((( $this->color=='1')&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')))||
				(( $this->color=='2')&&(($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))))){
					$string .= 'Ö';
				}
				elseif (( $this->demotion_piece_type!= $this->piece_type) &&( $this->promotion_piece_type!= $this->piece_type)&& (( $this->piece_type == ChessPiece::ROOK)||( $this->piece_type == ChessPiece::BISHOP)||( $this->piece_type == ChessPiece::KNIGHT)||( $this->piece_type == ChessPiece::GENERAL) ||( $this->piece_type == ChessPiece::PAWN))&&((( $this->color=='1')&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')))||
				(( $this->color=='2')&&(($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))))){
					$string .= 'Ö';
				}
				elseif ((($this->promotion_piece_type == ChessPiece::BISHOP)||( $this->promotion_piece_type == ChessPiece::KNIGHT))&&($this->piece_type==ChessPiece::SPY)&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')||($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))) {
					$string .= 'Ö';
				}
				elseif ((($this->promotion_piece_type == ChessPiece::BISHOP)||( $this->promotion_piece_type == ChessPiece::KNIGHT)||($this->promotion_piece_type == ChessPiece::GENERAL)||( $this->promotion_piece_type == ChessPiece::ROOK))&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')||($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))) {
					$string .= 'Ö';
				}
			}
			
		}	//if(( $this->piece_type == ChessPiece::KNIGHT)&&($this->ending_square->rank==5)&&($this->ending_square->file==4)){
                //except Inveted King ever king type can be kicked
				//if ((( $this->board->commonborderbreached == true ) && ( $this->board->CommonBorderOpen_Status==0))

				if ((( $this->board->commonborderbreached == true ) && ( $this->board->CommonBorderOpen_Status==1))
				||
				( $this->board->wbrokencastle == true ) ||
				( $this->board->bbrokencastle == true )) {				 
					$string .= '≡';
				}

			if ( $this->tamedpawn >= 1 ) {
				$string .= '>';}
			if ( $this->tamedpawn <= -1 ) {
				$string .= '<';}

				if ( $this->tamedpawn != 0 ){		
					if (( $this->tamedpawn == -1 )) 	$string= $string.'P';
					if (( $this->tamedpawn == -2 )) 	$string= $string.'p';
					if (( $this->tamedpawn == 1 )) 	$string= $string.'P';
					if (( $this->tamedpawn == 2 )) 	$string= $string.'p';
				}

           // }
						
			//if ( $this->demotion_piece_type == ChessPiece::GENERAL ) {
				//$string .= '=S';
			//} else
			if ( $this->demotion_piece_type == ChessPiece::ROOK ) {
				$string .= '=M';
			} elseif ( $this->demotion_piece_type == ChessPiece::BISHOP ) {
				$string .= '=G';
			} elseif ( $this->demotion_piece_type == ChessPiece::KNIGHT ) {
				$string .= '=H';
			} elseif ( $this->demotion_piece_type == ChessPiece::SPY ) {
			$string .= '=C';			
			}
		
		// check or checkmate
		if ( $this->checkmate ) {
			$string .= '#';
		} elseif ( $this->check ) {
			$string .= '+';
		}
		
		return $string;
	}
	

	function get_touched_notation(): string {
		$pcolor = NULL;
		$string = '';
		$char = self::PIECE_LETTERS[$this->powermovedbeforepromotiontype];
		//powermovedpiece
		$kingsquare = NULL;
		if ($this->color == ChessPiece::WHITE)
		{
			if (($char=='a')|| ($char=='i')||($char=='c')|| ($char=='u')|| ($char=='y')||($char=='j')) {
				$pcolor = strtoupper($char);
			}
			else
				$pcolor =	strtoupper(self::PIECE_LETTERS[$this->powermovedbeforepromotiontype]);
		}
		elseif($this->color == ChessPiece::BLACK){
			$pcolor = strtolower($char)	;
		}

			if (($this->capture ==false ) && ($this->board==null)&& ($this->controlled_move==true)  ) {
				$string .= $this->starting_square->get_alphanumeric();
				$string .= $this->ending_square->get_alphanumeric();
				return $string;
			}			
			else if (($this->capture ==false ) && ($this->board->board[$this->ending_square->rank][$this->ending_square->file]!=null) && 
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushed==true)  &&
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushedsquare!=null)&& ( $this->tamedpawn == 0 )) {
				$string .= '>';
			}
			else if (($this->board->board[$this->ending_square->rank][$this->ending_square->file]!=null) && 
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushed==true)  &&
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushedsquare!=null) && ( $this->tamedpawn !== 0 ) ) {
				$string .= '-';
				$this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushedpiece->type=13;
			}
			// destination square
			$string .= $this->starting_square->get_alphanumeric();
			$string .= $this->ending_square->get_alphanumeric();

			if (($this->capture ==false ) && ($this->board->board[$this->ending_square->rank][$this->ending_square->file]!=null) && 
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushed==true)  &&
			($this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushedsquare!=null)) {
				//$string .= '>';
				$getsquare=$this->board->board[$this->ending_square->rank][$this->ending_square->file]->selfpushedsquare;
				$this->ending_square->set_square($getsquare["file"] ,$getsquare["rank"]);
				//$this->ending_square->set_square(8 ,8);
				$string .= $this->ending_square->get_alphanumeric();
			}
			
			if($this->color==1)
			$kingsquare=$this->board->bkingsquare;//opponent square
			if($this->color==2)
			$kingsquare=$this->board->wkingsquare;//			
			
			if($kingsquare!=null){
				if ($this->board->board[$kingsquare->rank][$kingsquare->file]!=null) {
					if (($this->ending_square->rank == $kingsquare->rank )&&($this->ending_square->file == $kingsquare->file )) {
						$string .= '#';
					}
				}
			}

		if($this->color == ChessPiece::BLACK){
			/*if ( $this->tamedpawn == true ) {
				$string .= '=P';
			} 
			else*/ {
				if ( $this->promotion_piece_type == ChessPiece::GENERAL ) {
				$string .= '=s';
			} elseif ( $this->promotion_piece_type == ChessPiece::ROOK ) {
				$string .= '=m';
			} elseif ( $this->promotion_piece_type == ChessPiece::BISHOP ) {
				$string .= '=g';
			} elseif ( $this->promotion_piece_type == ChessPiece::KNIGHT ) {
				$string .= '=h';
			}

			//later will change the royal_piece_type
			else if ( $this->promotion_piece_type == ChessPiece::KING ) {
				$string .= '=i';
			} elseif ( $this->promotion_piece_type == ChessPiece::INVERTEDKING ) {
				$string .= '=j';
		 	} elseif ( $this->promotion_piece_type == ChessPiece::VIKRAMADITYA) {
				$string .= '=v';
			} elseif ( $this->promotion_piece_type == ChessPiece::RAJYAPAALARTHSHASTRI) {
				$string .= '=Ä';
			}elseif ( $this->promotion_piece_type == ChessPiece::ARTHSHASTRI) {
				$string .= '=a';
			}

			if ( $this->demotion_piece_type == ChessPiece::KING ) {
				$string .= '=i';
			} elseif ( $this->demotion_piece_type == ChessPiece::INVERTEDKING ) {
				$string .= '=i';
			}elseif ( $this->demotion_piece_type == ChessPiece::ARTHSHASTRI) {
				$string .= '=a';
			}
			elseif ( $this->demotion_piece_type == ChessPiece::CAPTUREDSCEPTRE) {
				$string .= '=o';
			}

			if (( $this->demotion_piece_type!= $this->piece_type) &&( $this->promotion_piece_type!= $this->piece_type)&& (( $this->piece_type == ChessPiece::ROOK)||( $this->piece_type == ChessPiece::BISHOP)||( $this->piece_type == ChessPiece::KNIGHT)||( $this->piece_type == ChessPiece::GENERAL) ||( $this->piece_type == ChessPiece::PAWN))&&((( $this->color=='1')&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')))||
			(( $this->color=='2')&&(($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))))){
				$string .= 'o';
			}
			elseif (( $this->demotion_piece_type!= $this->piece_type) &&( $this->promotion_piece_type!= $this->piece_type)&& (( $this->piece_type == ChessPiece::ROOK)||( $this->piece_type == ChessPiece::BISHOP)||( $this->piece_type == ChessPiece::KNIGHT)||( $this->piece_type == ChessPiece::GENERAL) ||( $this->piece_type == ChessPiece::PAWN))&&((( $this->color=='1')&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')))||
			(( $this->color=='2')&&(($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))))){
				$string .= 'o';
			}
			elseif ((($this->promotion_piece_type == ChessPiece::BISHOP)||( $this->promotion_piece_type == ChessPiece::KNIGHT))&&($this->piece_type==ChessPiece::SPY)&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')||($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))) {
				$string .= 'o';
			}
			elseif ((($this->promotion_piece_type == ChessPiece::BISHOP)||( $this->promotion_piece_type == ChessPiece::KNIGHT)||($this->promotion_piece_type == ChessPiece::GENERAL)||( $this->promotion_piece_type == ChessPiece::ROOK))&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')||($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))) {
				$string .= 'o';
			}
		}
		}
		else
		{

			/*if ( $this->tamedpawn == true ) {
				$string .= '=p';
			} 			
			else*/{
				if ( $this->promotion_piece_type == ChessPiece::GENERAL ) {
					$string .= '=S';
				} elseif ( $this->promotion_piece_type == ChessPiece::ROOK ) {
					$string .= '=M';
				} elseif ( $this->promotion_piece_type == ChessPiece::BISHOP ) {
					$string .= '=G';
				} elseif ( $this->promotion_piece_type == ChessPiece::KNIGHT ) {
					$string .= '=H';
				}
	
				//later will change the royal_piece_type
				if ( $this->promotion_piece_type == ChessPiece::KING ) {
					$string .= '=I';
				} elseif ( $this->promotion_piece_type == ChessPiece::INVERTEDKING ) {
					$string .= '=J';
				 } elseif ( $this->promotion_piece_type == ChessPiece::VIKRAMADITYA) {
					$string .= '=V';
				} elseif ( $this->promotion_piece_type == ChessPiece::RAJYAPAALARTHSHASTRI) {
					$string .= '=Ä';
				}elseif ( $this->promotion_piece_type == ChessPiece::ARTHSHASTRI) {
					$string .= '=A';
				}
	
				if ( $this->demotion_piece_type == ChessPiece::KING ) {
					$string .= '=I';
				} elseif ( $this->demotion_piece_type == ChessPiece::INVERTEDKING ) {
					$string .= '=J';
				}elseif ( $this->demotion_piece_type == ChessPiece::ARTHSHASTRI) {
					$string .= '=A';
				}
				elseif ( $this->demotion_piece_type == ChessPiece::CAPTUREDSCEPTRE) {
					$string .= '=Ö';
				}
	
				if (( $this->demotion_piece_type!= $this->piece_type) &&( $this->promotion_piece_type!= $this->piece_type)&& (( $this->piece_type == ChessPiece::ROOK)||( $this->piece_type == ChessPiece::BISHOP)||( $this->piece_type == ChessPiece::KNIGHT)||( $this->piece_type == ChessPiece::GENERAL) ||( $this->piece_type == ChessPiece::PAWN))&&((( $this->color=='1')&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')))||
				(( $this->color=='2')&&(($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))))){
					$string .= 'Ö';
				}
				elseif (( $this->demotion_piece_type!= $this->piece_type) &&( $this->promotion_piece_type!= $this->piece_type)&& (( $this->piece_type == ChessPiece::ROOK)||( $this->piece_type == ChessPiece::BISHOP)||( $this->piece_type == ChessPiece::KNIGHT)||( $this->piece_type == ChessPiece::GENERAL) ||( $this->piece_type == ChessPiece::PAWN))&&((( $this->color=='1')&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')))||
				(( $this->color=='2')&&(($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))))){
					$string .= 'Ö';
				}
				elseif ((($this->promotion_piece_type == ChessPiece::BISHOP)||( $this->promotion_piece_type == ChessPiece::KNIGHT))&&($this->piece_type==ChessPiece::SPY)&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')||($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))) {
					$string .= 'Ö';
				}
				elseif ((($this->promotion_piece_type == ChessPiece::BISHOP)||( $this->promotion_piece_type == ChessPiece::KNIGHT)||($this->promotion_piece_type == ChessPiece::GENERAL)||( $this->promotion_piece_type == ChessPiece::ROOK))&&(($this->ending_square->get_alphanumeric()=='d9')||($this->ending_square->get_alphanumeric()=='e9')||($this->ending_square->get_alphanumeric()=='d0')||($this->ending_square->get_alphanumeric()=='e0'))) {
					$string .= 'Ö';
				}
			}
			
		}	//if(( $this->piece_type == ChessPiece::KNIGHT)&&($this->ending_square->rank==5)&&($this->ending_square->file==4)){
                //except Inveted King ever king type can be kicked
                if ($this->capturedking == true){
                    $string .= '#';
                }

			if ( $this->tamedpawn >= 1 ) {
				$string .= '>';}
			if ( $this->tamedpawn <= -1 ) {
				$string .= '<';}

				if ( $this->tamedpawn != 0 ){		
					if (( $this->tamedpawn == -1 )) 	$string= $string.'P';
					if (( $this->tamedpawn == -2 )) 	$string= $string.'p';
					if (( $this->tamedpawn == 1 )) 	$string= $string.'P';
					if (( $this->tamedpawn == 2 )) 	$string= $string.'p';
				}

           // }
						
			//if ( $this->demotion_piece_type == ChessPiece::GENERAL ) {
				//$string .= '=S';
			//} else
			if ( $this->demotion_piece_type == ChessPiece::ROOK ) {
				$string .= '=M';
			} elseif ( $this->demotion_piece_type == ChessPiece::BISHOP ) {
				$string .= '=G';
			} elseif ( $this->demotion_piece_type == ChessPiece::KNIGHT ) {
				$string .= '=H';
			} elseif ( $this->demotion_piece_type == ChessPiece::SPY ) {
			$string .= '=C';			
			}
		
		// check or checkmate
		if ( $this->checkmate ) {
			$string .= '#';
		} elseif ( $this->check ) {
			$string .= '+';
		}
		
		return $string;
	}
	


	function get_coordinate_notation(): string {
		// Automatically pick GENERAL when drag and dropping.
		if (
			$this->demotion_piece_type == ChessPiece::ROOK ||
			$this->demotion_piece_type == ChessPiece::BISHOP ||
			$this->demotion_piece_type == ChessPiece::KNIGHT
		) {
			return "";
		}
		if (
			$this->promotion_piece_type == ChessPiece::ROOK ||
			$this->promotion_piece_type == ChessPiece::BISHOP ||
			$this->promotion_piece_type == ChessPiece::KNIGHT
		) {
			return "";
		} else {		
			return $this->starting_square->get_alphanumeric() . $this->ending_square->get_alphanumeric();
		}
	}
	
	function get_piece_letter(): string {
		$pcolor='';
		if ($this->color == ChessPiece::WHITE) {
            $pcolor =	strtoupper(self::PIECE_LETTERS[$this->piece_type]);
        }
		else{
		$pcolor =	strtolower(self::PIECE_LETTERS[$this->piece_type]);
		};
		//Echo '<li> ChessMove.php #4 function get_piece_letter called </li>';	
		return $pcolor;
	}
}
