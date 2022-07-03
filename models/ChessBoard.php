<?php

class ChessBoard {
	const PIECE_LETTERS = array(
		'p' => ChessPiece::PAWN,
		'h' => ChessPiece::KNIGHT,
		'g' => ChessPiece::BISHOP,
		'm' => ChessPiece::ROOK,
		's' => ChessPiece::GENERAL,
		'i' => ChessPiece::KING,
		'r' => ChessPiece::SIMPLEKING,

		'v' => ChessPiece::VIKRAMADITYA	,
		'j' => ChessPiece::INVERTEDKING,
		'ä' => ChessPiece::RAJYAPAALARTHSHASTRI,
		'a' => ChessPiece::ARTHSHASTRI,
		'c' => ChessPiece::SPY,
		'n' => ChessPiece::GODMAN
	);
	const FILE_NUMS_AND_LETTERS = array(
		0 => 'x', 1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f', 7 => 'g', 8 => 'h', 9 => 'y'
	);
	
	/* const FEN_REGEX_FORMAT = '/^([rmneshijvaäçcgpÖRMNESHIJVÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÚÝÄCGP0123456789]{1,10})\/([rmneshijvöaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10}) ([bw0-2]{2,2}) (^$|[\(][\)]|[\(][a-zA-Z0-9]{3,3}[\)]) ([a-hx-y0-9-]{1,2})( (\d{1,2}) (\d{1,4}))?$/u';
	const CLASSIC_FEN_REGEX_FORMAT_LEFT = '/^([cCiIaAvVsSnNjJäÄ1]{1})([rmneshijvaäçcgpÖRMNESHIJVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshijvaäçcgpÖRMNESHIJVÇÄCGP12345678]{1,9}) ([bw0-2]{2,2}) (^$|[\(][\)]|[\(][a-zA-Z0-9]{3,3}[\)]) ([a-hx-y0-9-]{1,2})( (\d{1,2}) (\d{1,4}))?$/u';
	const CLASSIC_FEN_REGEX_FORMAT = '/^(([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})([rmneshijvaAäçcgpÖRMNESHIJVÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaAvrRVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivIaAäçcgpRMNESjUVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivIaAäçcgpRMNESHjVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshijvaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivjaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivjaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([ghmGHMrmneshivjaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivjaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivjaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/((([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshijvjaAäçcgpÖRMNESHIJVÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})) ([bw0-2]{2,2}) (^$|[\(][\)]|[\(][a-zA-Z0-9]{3,3}[\)]) ([a-hx-y0-9-]{1,2})( (\d{1,2}) (\d{1,4}))?$/u';
	*/
	/* */
	const FEN_REGEX_FORMAT = '/^([rmneshijvaäçcgpÖRMNESHIJVÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10})\/([rmneshijvaäçcgpRMNESHIJVAÇÚÝÄCGP0123456789]{1,10})\/([rmneshijvöaäçcgpRMNESHIJVAÇÄCGP0123456789]{1,10}) ([\~bwc]{1,4}) ([bw0-2]{2,2}) (^$|[\(][\)]|[\(][a-zA-Z0-9]{3,3}[\)]) ([a-hx-y0-9-]{1,2})( (\d{1,2}) (\d{1,4}))?$/u';
	const CLASSIC_FEN_REGEX_FORMAT_LEFT = '/^([cCiIaAvVsSnNjJäÄ1]{1})([rmneshijvaäçcgpÖRMNESHIJVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshuvyaäçcgpRMNESHVÇÄCGP12345678]{1,9})*?([cCiIaAvVsSnNjJäÄ1]{1})\/([cCiIaAvVsSnjJNäÄ1]{1})([rmneshijvaäçcgpÖRMNESHIJVÇÄCGP12345678]{1,9}) ([\~bwc]{1,4}) ([bw0-2]{2,2}) (^$|[\(][\)]|[\(][a-zA-Z0-9]{3,3}[\)]) ([a-hx-y0-9-]{1,2})( (\d{1,2}) (\d{1,4}))?$/u';
	const CLASSIC_FEN_REGEX_FORMAT = '/^(([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})([rmneshijvaAäçcgpÖRMNESHIJVÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaAvrRVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivIaAäçcgpRMNESjUVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivIaAäçcgpRMNESHjVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshijvaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivjaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivjaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([ghmGHMrmneshivjaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivjaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/(([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshivjaAäçcgpRMNESHIVJÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})\/((([ghmGHMcCiIaArRvVsSnjJNäÄ1]{1})([rmneshijvjaAäçcgpÖRMNESHIJVÇÄCGP12345678]{1,8}))*?([ghmGHMcCiIaArRvVsSnNjJäÄ1]{1})) ([\~bwc]{1,4}) ([bw0-2]{2,2}) (^$|[\(][\)]|[\(][a-zA-Z0-9]{3,3}[\)]) ([a-hx-y0-9-]{1,2})( (\d{1,2}) (\d{1,4}))?$/u';
	/* */
	const CLASSIC_FEN_REGEX_FORMAT_NOMANS = '/^([cCiIaArRvVsSnNjJäÄ1]{1})?$/u';
	const CLASSIC_FEN_REGEX_FORMAT_BCASTLE = '/^([rmneshijvaäçcgpÖRMNESHVÇÄCGP12345678]{1,8})?$/u';
	const CLASSIC_FEN_REGEX_FORMAT_WAR = '/^([rmneshuvyaäçcgpRMNESHVÇAÄCGP12345678]{1,8})?$/u';
	const CLASSIC_FEN_REGEX_FORMAT_WCASTLE = '/^([rmneshuvyaäçcgpÖRMNESHIJVÇÄCGP12345678]{1,8})?$/u';
	const CLASSIC_FEN_REGEX_FORMAT_TRUCE = '/^([ghmGHMcCiIaAvrRVsSnjJNäÄ1]{1})?$/u';

	const DEFAULT_FEN = '13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1';
	//const DEFAULT_FEN = '13c1c21/1c1S1n1ic1/ca1phs3c/1p71/c2P5c/C3PH3C/11p1ppp21/1C1N1CH1PC/11C5MC/11I4A11 ~w w0 () - 0 54';
	
	public $board = array();// $board[y][x], or in this case, $board[rank][file]
	public $gametype = 1;//1 means classical Agasthya 2: Means Kautilya
	public $commonborderbreached=false;
	public $wbrokencastle=false; 
	public $bbrokencastle=false;

	public $arewcommaderssleeping=false;
	public $arebcommaderssleeping=false;
	public $isCurrentZoneRoyal = false;
	public $iswwZoneRoyal = false;
	public $isbwZoneRoyal = false;

	public $iswltZoneRoyal = false;
	public $iswrtZoneRoyal = false;

	public $isbltZoneRoyal = false;
	public $isbrtZoneRoyal = false;

	public $isbcZoneRoyal = false;
	public $iswcZoneRoyal = false;

	public $color_to_move;
	public $boardtype = 1;//1 means white
	public $moveable = true;
	public $castling = array();// format is array('white_can_castle_kingside' => TRUE, etc.)
	//public $en_passant_target_square = NULL;
	public $halfmove_clock;
	public $controlled_piece=null;
	public $naradcmoves=array();
	public $fullmove_number;
	public $controlled_color=null;
	public $controller_color=null;
	public $refugee=null;
	public $ParityOfficers = array();
	public $PinnedWRefugees = array();
	public $PinnedBRefugees = array();
	public $Winner = '0';
	public $Winners=0;
	public $DefaultParityOfficers = '1S2M2H2G';
	public $DefaultParityRoyals = '1A1I1J1Ä1R1V';
	public $blackcankill = 1; public $whitecankill = 1;
	public $bkingsquare; public $wkingsquare;
	public $bnsquare; public $wnsquare;
	public $elevatedbs=false; public $elevatedws=false;
	public $basquare; public $wasquare;
	public $bssquare; public $wssquare;
	public $blackncanfullmove = 1; public $whitencanfullmove = 1;
	public $blackcanfullmove = 0; public $whitecanfullmove = 0;
	public $blackscanfullmove = 0; public $whitescanfullmove = 0;
	public $blackcanfullmoveinowncastle = 1; public $whitecanfullmoveinowncastle = 1;
	public $blackcanfullmoveinfoecastle = 0; public $whitecanfullmoveinfoecastle = 0;

	function __construct(string $fen = self::DEFAULT_FEN) {
		$this->import_fen($fen);
	}

	function __clone() {
		if ( $this->board ) {
			for ( $rank = 0;$rank <= 9;$rank++ ) {
				for ( $file = 0;$file <= 9;$file++ ) {
					// if there's a piece there
					if ( $this->board[$rank][$file] ) {
						// clone the piece so it's not pointing at an old piece
						$this->board[$rank][$file] = clone $this->board[$rank][$file];
					}
				}
			}
		}
	}

	function setparity(string $fen): void {
		$tchar = 0;
		$cchar ='';
		$chars = $this->DefaultParityOfficers;
		$this->ParityOfficers[0]='';
		$this->ParityOfficers[1]='';

		for($j=0;$j<2;$j++){
			if($j==0){
				$chars=strtolower($chars);
			}//Black = 0
			else if($j==1){$chars=strtoupper($chars);
			}//White = 1
			for ($i=0;$i<strlen($chars);$i=$i+2){
				$tchar = substr_count($fen,substr($chars,$i+1,1));
				$cchar =substr($chars,$i+1,1);
				if($tchar>0){
					$this->ParityOfficers[$j]=$this->ParityOfficers[$j].strval($tchar).$cchar;
				}
				else {
					$this->ParityOfficers[$j]=$this->ParityOfficers[$j]."0".$cchar;
				}
			}
		}
	}

	function checkcontinuity(string $fen): void {
		$tchar = array();
		$chars = 'vijuyr';
		$tcount = 0;
		$fen=explode(" ",$fen)[0];
		//$rv = TRUE;

		for($j=0;$j<2;$j++){
			$tchar[$j] = 0;

			if($j==0){
				$chars=strtolower($chars);
			}//Black = 0
			else if($j==1){
				$chars=strtoupper($chars);
			}//White = 1
			for ($i=0;$i<strlen($chars);$i=$i+1){
				$tcount=substr_count($fen,substr($chars,$i,1));
				$tchar[$j] = $tchar[$j]+$tcount;
			}
		}
	
		if(($tchar[0]==0)&&	($this->Winner!='2')){
			$this->Winner='1';
			//return FALSE;
		 }
	
		if(($tchar[1]==0)&&	($this->Winner!='1')){
			$this->Winner='2';
			//return FALSE;
		}
	
		if(($tchar[0]>0)&& ($tchar[1]>0)){
			$this->Winner='0';
			//return FALSE;
	 	}
	 	if(($tchar[0]==0)&& ($tchar[1]==0)){//No Kings on anyside
			$this->Winner='-1';
			//return FALSE;
	 	}
	}
	
	function checkdemotionparity(//no impact if royal throws
		string $fen,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board): bool {
		$chars = $this->DefaultParityOfficers;
		$tcount = 0;
		$pname=strtolower($piece::FEN_CHESS_PIECES[$color_to_move][$piece->type]);
		if(strtolower($piece::FEN_CHESS_PIECES[$color_to_move][$piece->type])=='g'){
			$possibledemotionpiece=7;//Bishop to Spy'
			$chars='8C';
		}
		else{
			$possibledemotionpiece=($piece->type)+1;
		}

		$pname=$piece::FEN_CHESS_PIECES[$color_to_move][$possibledemotionpiece];
		//$type=$piece::BISHOP;

		if ($piece->group=='OFFICER') {
			if ($color_to_move==2) {//black
				$chars=strtolower($chars);
				$pname=strtolower($pname);
			}//Black = 0
			elseif ($color_to_move==1) {//white
				$chars=strtoupper($chars);
				$pname=strtoupper($pname);
			}//White = 1
			
			$currentpcount=substr_count($fen, $pname);
			if($currentpcount>=0){
				$pcharpos=strpos($chars, $pname);
				if ($pcharpos>=1) {
					$tcount = (int)(substr($chars, $pcharpos-1, 1));
					if ($currentpcount<$tcount) {
						return true;
					}else {//currentpcount is exhausted
						return false;
					}
					return true;
				}
				return false;
			}
			else
			{
				return false;
			}
		}
		return TRUE;
	}

	function checkextractopponentpawnparity(//no impact if royal throws
		string $fen,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board): bool {
		$chars = '8P';
		$tcount = 0;
		if((strtolower($piece::FEN_CHESS_PIECES[$color_to_move][$piece->group])=='ROYAL')||(strtolower($piece::FEN_CHESS_PIECES[$color_to_move][$piece->group])=='NOBLE')){
			return false;
		}

		$pname=$piece::FEN_CHESS_PIECES[$color_to_move][13];

			if ($color_to_move==2) {//black
				$chars=strtolower($chars);
				$pname=strtolower($pname);
			}//Black = 0
			elseif ($color_to_move==1) {//white
				$chars=strtoupper($chars);
				$pname=strtoupper($pname);
			}//White = 1
			
			$currentpcount=substr_count($fen, $pname);
			if($currentpcount>=0){
				$pcharpos=strpos($chars, $pname);
				if ($pcharpos>=1) {
					$tcount = (int)(substr($chars, $pcharpos-1, 1));
					if ($currentpcount<$tcount) {
						return true;
					}else {//currentpcount is exhausted
						return false;
					}
					return true;
				}
				return false;
			}
			else
			{
				return false;
			}
	}

	function checkextractselfpawnparity(//no impact if royal throws
		string $fen,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board): bool {
		$chars = '8P';
		$tcount = 0;
		if((strtolower($piece::FEN_CHESS_PIECES[$color_to_move][$piece->group])=='ROYAL')||(strtolower($piece::FEN_CHESS_PIECES[$color_to_move][$piece->group])=='NOBLE')){
			return false;
		}

		$pname=$piece::FEN_CHESS_PIECES[$color_to_move][13];

			if ($color_to_move==1) {//black
				$chars=strtolower($chars);
				$pname=strtolower($pname);
			}//Black = 0
			elseif ($color_to_move==2) {//white
				$chars=strtoupper($chars);
				$pname=strtoupper($pname);
			}//White = 1
			
			$currentpcount=substr_count($fen, $pname);
			if($currentpcount>=0){
				$pcharpos=strpos($chars, $pname);
				if ($pcharpos>=1) {
					$tcount = (int)(substr($chars, $pcharpos-1, 1));
					if ($currentpcount<$tcount) {
						return true;
					}else {//currentpcount is exhausted
						return false;
					}
					return true;
				}
				return false;
			}
			else
			{
				return false;
			}
	}

	function checkpromotionparity(//no impact if royal throws
		string $fen,
		ChessPiece $piece,
		$color_to_move,
		ChessBoard $board,
		$possiblepromotionpiece): bool {
		$tchar = 0;
		if($possiblepromotionpiece==6)
			$chars = $this->DefaultParityRoyals;
		else	
			$chars = $this->DefaultParityOfficers;
		$tcount = 0;
		$pname=$piece::FEN_CHESS_PIECES[$color_to_move][$possiblepromotionpiece];
		//$type=$piece::BISHOP;

		if (($piece->group=='OFFICER')||($piece->group=='SEMIROYAL')) {
			if ($color_to_move==2) {//black
				$chars=strtolower($chars);
				$pname=strtolower($pname);
			}//Black = 0
			elseif ($color_to_move==1) {//white
				$chars=strtoupper($chars);
				$pname=strtoupper($pname);
			}//White = 1
			
			$currentpcount=substr_count($fen, $pname);
			if($currentpcount>=0){
				$pcharpos=strpos($chars, $pname);
				if ($pcharpos>=1) {
					$tcount = (int)(substr($chars, $pcharpos-1, 1));
					if ($currentpcount<$tcount) {
						return true;
					}else {//currentpcount is exhausted
						return false;
					}
					return true;
				}
				return false;
			}
			else
			{
				return false;
			}
		}
		return TRUE;
	}

	function checkparity(string $fen): bool {
		$tchar = 0;
		$chars = $this->DefaultParityOfficers;
		$tcount = 0;
		$fen = explode( ' ',$fen)[0];
		//$rv = TRUE;

		for($j=0;$j<2;$j++){
			if($j==0){
				$chars=strtolower($chars);
			}//Black = 0
			else if($j==1){
				$chars=strtoupper($chars);
			}//White = 1
			for ($i=0;$i<strlen($chars);$i=$i+2){
				$tchar = substr_count($fen,substr($chars,$i+1,1));
				$tcount = (int)(substr($chars,$i,1));
				if($tchar>$tcount){
					return FALSE;
 				}
			}
		}

		//parityRoyals
		$tcount = 1;

		if ( ((substr_count($fen,'v')+substr_count($fen,'V')) > $tcount ) ||
			((substr_count($fen,'ä')+substr_count($fen,'Ä')) > $tcount ) ||
			((substr_count($fen,'Ö')+substr_count($fen,'ö')) > $tcount ) ||
			((substr_count($fen,'ö')+substr_count($fen,'V')) > $tcount ) ||
			((substr_count($fen,'Ä')+substr_count($fen,'v')) > $tcount ) ||
			((substr_count($fen,'ä')+substr_count($fen,'Ö')) > $tcount ) ||
			((substr_count($fen,'Ä')+substr_count($fen,'ö')) > $tcount ) ||
			((substr_count($fen,'ä')+substr_count($fen,'V')) > $tcount ) ||
			((substr_count($fen,'Ö')+substr_count($fen,'v')) > $tcount ) ||
			((substr_count($fen,'i')+substr_count($fen,'j')+substr_count($fen,'r')+substr_count($fen,'v')) > $tcount ) ||
			((substr_count($fen,'I')+substr_count($fen,'J')+substr_count($fen,'R')+substr_count($fen,'V')) > $tcount ) ||
			((substr_count($fen,'a')+substr_count($fen,'ä')) > $tcount ) ||
			((substr_count($fen,'A')+substr_count($fen,'Ä')) > $tcount ) ||
			((substr_count($fen,'Ö')) > $tcount ) ||
			((substr_count($fen,'ö')) > $tcount ))
					return FALSE;

		return TRUE;
	}

	function my_mb_substr($string, $offset, $length):string
	{
		$arr = preg_split("//u", $string);
		$slice = array_slice($arr, $offset + 1, $length);
		return implode("", $slice);
	}

	function setboard(string $import_boardtype): void {
		if($import_boardtype=="black"){
			$this->boardtype=2;
		}
		else if(($import_boardtype=="white") || ($import_boardtype=="")){
			$this->boardtype=1;
		}
	}

	function import_fen(string $fen): void {
		$fen = trim($fen);
		$fen = explode( ';',$fen)[0];
		$valid_fen=null;
		$valid_fen = preg_match(self::FEN_REGEX_FORMAT, $fen, $matches);

		if(($this->gametype==1)&&($valid_fen!==null))
		{
			// If the FEN Format is perfect then use this
			$valid_fen = preg_match(self::CLASSIC_FEN_REGEX_FORMAT, $fen , $temp);
		}

		$fen = explode( ' ',$fen)[0];

		if($this->checkparity($fen)==TRUE)
		{
			$this->setparity($fen);
		}
		else if($this->checkparity($fen)==FALSE)
		{
			$valid_fen=NULL;
			$this->ParityOfficers[0]='';
			$this->ParityOfficers[1]='';
		}

		$ttt=$this->ParityOfficers;//DefaultParityOfficers;
		$this->checkcontinuity($fen);
		
		if ( ! $valid_fen ) {
			throw new Exception('Invalid FEN');
		}
		else
		{
			if ($this->Winner=='0') {
			}
		}
		// ******* CREATE PIECES AND ASSIGN THEM TO SQUARES *******
		
		// Set all board squares to NULL. That way we don't have to blank them in the loop below. We can just overwrite the NULL with a piece.
		for ( $i = 0;$i <= 9;$i++ ) {
			for ( $j = 0;$j <= 9;$j++ ) {
				$this->board[$i][$j] = NULL;
			}
		}
		
		$rank_string = array();
		for ( $i = 0;$i <= 9;$i++ ) {
			// Match string = 1, but rank = 8. Fix it here to avoid headaches.
			$rank = $this->invert_rank_or_file_number($i);
			$rank_string[$rank] = $matches[$i+1];
			//echo '<br/> i = '.$i.' Rank = '. $rank.' Rank Rank name = '.$matches[$i];
		}
		
		$nn=0;
		$group='OFFICER';
		$Mortal='1';
		$Striker=0;
		// Process $rank variable strings, convert to pieces and add them to $this->board[][]
		foreach ( $rank_string as $rank => $string ) {
			$file = 0;
			for ( $i = 0;$i <= strlen($rank_string[9-$nn]);$i++ ) {
				$Striker = 1;

				if (!function_exists('mb_substr'))
					{
						$char = $this->my_mb_substr($string, $i, 1);
					}
				else{$char = mb_substr($string, $i, 1,'UTF-8');
					}

				//$char = mb_substr($string, $i, 1,'UTF-8');
				//$char = substr(utf8_decode($string), $i, 1);
				if(preg_match('/[^a-zA-Z0-9ÇÄäç]/i', $char) or strlen($char)==0)
					{
					//echo ' <b><i><u><br/> ***'.$char. " is not valid string </u></i></b>";
					}
				else
				{
					//echo ' <br/> Temp i = '.$i.' String length = '.strlen($string).' Char is = '.$char;
					// Don't use is_int here. $char is a string. Use is_numeric instead.
					if ( is_numeric($char) ) {
						$file = $file + $char;
						//echo ' ** Blank Number on file = '.$file;
					}else {
					$square = $this->number_to_file($file) . $rank;
					if($square=='e0'){
						$square=$square;
					}
					$cc=ctype_upper($char);

					if ($char=='C') {
						$color = ChessPiece::WHITE;
						$Mortal = 1;
						$Striker = 0;
						$group='SEMIROYAL';
						$type = self::PIECE_LETTERS[strtolower($char)];
					}
					elseif ($char=='Ç') {
						$color = ChessPiece::WHITE;
						$Mortal = 1;
						$Striker = 0;
						$group='SEMIROYAL';
						$char='ç';
						$type = self::PIECE_LETTERS[$char];
					}
					elseif (($char=='ç')||($char=='c')) {
						$group='SEMIROYAL';
						$Mortal = 1;
						$color = ChessPiece::BLACK;
						$type = self::PIECE_LETTERS[$char];
					}
					else
					if (($char=='R')||($char=='V')||($char=='I')|| ($char=='J')) {
						$color = ChessPiece::WHITE;
						$Mortal = 1;
						$group='ROYAL';
						$type = self::PIECE_LETTERS[strtolower($char)];
					}
					elseif (($char=='A') ||($char=='Ä')) {
						$color = ChessPiece::WHITE;
						$Striker = 0;
						$char='a';
						$group='ROYAL';
						if($char=='Ä')
							{$char='ä';}

						$type = self::PIECE_LETTERS[$char];
					}
					elseif (($char=='v')||($char=='r')||($char=='i')||($char=='j')) {
						$group='ROYAL';
						$Mortal = 1;
						$color = ChessPiece::BLACK;
						$type = self::PIECE_LETTERS[$char];
					}
					elseif (($char=='a') ||($char=='ä')) {
						$color = ChessPiece::BLACK;
						$Striker = 0;
						$group='ROYAL';
						if($char!='ä')
							{$char='a';}

						$type = self::PIECE_LETTERS[$char];
					}
					elseif(($char=='n')||($char=='N')) 
					{
						$group='NOBLE';
						if($char=='n') $color = ChessPiece::BLACK;
						else  $color = ChessPiece::WHITE;
						$Mortal = 0;
						$Striker = 0;
						$type = self::PIECE_LETTERS[strtolower($char)];
					}
					elseif(($char=='p')||($char=='P'))
					{
						$group='SOLDIER';
						if($char=='p') $color = ChessPiece::BLACK;
						else  $color = ChessPiece::WHITE;
						$Mortal = 1;
						$type = self::PIECE_LETTERS[strtolower($char)];
					}
					elseif ( ctype_upper($char)) {
						$group='OFFICER';
						$Mortal = 1;
						$color = ChessPiece::WHITE;
						$type = self::PIECE_LETTERS[strtolower($char)];
					}
					else
					{
						$group='OFFICER';
						$Mortal = 1;
						$color = ChessPiece::BLACK;
						$type = self::PIECE_LETTERS[strtolower($char)];
					};
					
					$this->board[$rank][$file] = new ChessPiece($color, $square, $type,$group,$Mortal,$Striker);

					if((($rank==9)&&($color==2)&&($file>=4)&&($file<=5)&&(($type==3)||($type==4)))||
					(($rank==0)&&($color==1)&&($file>=4)&&($file<=5)&&(($type==3)||($type==4))))
						$this->board[$rank][$file]->type= self::PIECE_LETTERS['i'];

					if((($rank==9)&&($color==2)&&(($file<4)||($file>5))&&($type==1))||
					 (($rank==0)&&($color==1)&&(($file<4)||($file>5))&&($type==1)))
					  $this->board[$rank][$file]->type= self::PIECE_LETTERS['i'];

					if((($rank==9)&&($color==2)&&(($file<4)||($file>5))&&($type==2))||
					(($rank==0)&&($color==1)&&(($file<4)||($file>5))&&($type==2)))
						$this->board[$rank][$file]->type= self::PIECE_LETTERS['j'];
					$file++;
					}
				}
			}
		$nn++;
		}

			$borderdata=$matches[11] ;						

			if (strpos($borderdata,"b") !== false){
				$this->bbrokencastle=false;
			}
			else {$this->bbrokencastle=true;}

			if (strpos($borderdata,"w") !== false){
				$this->wbrokencastle=false;
			}	
			else {$this->wbrokencastle=true;}

			if (strpos($borderdata,"c") !== false){
				$this->commonborderbreached=false;
			}
			else {$this->commonborderbreached=true;}

		//'1c2ai2c1/cmhgsnghmc/cppppppppc/181/181/181/181/CPPPPPPPPC/CMHGNSGHMC/1C2IA2C1 ~bwc w0 () - 0 1';	
		$this->controlled_piece = $matches[13] ;
		if(($this->controlled_piece!="()")&&($this->controlled_piece!="")&&($this->controlled_piece!==null))
		{
			preg_match('#\((.*?)\)#', $this->controlled_piece, $refugee);
			//echo  "Refugee = ". $refugee[1];
			//echo  "File = ". $refugee[1][1];
			$rank = $refugee[1][2];
			$file=null;
			if( ($refugee[1]!==null) && ($refugee[1]!="") && ($refugee[1][1]!==null) && ($refugee[1][1]!="") &&($refugee[1][2]!==null) && ($refugee[1][2]!="")){
				if(strtolower($refugee[1][1])=='x') $file=0;
				else if(strtolower($refugee[1][1])=='a') $file=1;
				else if(strtolower($refugee[1][1])=='b') $file=2;
				else if(strtolower($refugee[1][1])=='c') $file=3;
				else if(strtolower($refugee[1][1])=='d') $file=4;
				else if(strtolower($refugee[1][1])=='e') $file=5;
				else if(strtolower($refugee[1][1])=='f') $file=6;
				else if(strtolower($refugee[1][1])=='g') $file=7;
				else if(strtolower($refugee[1][1])=='h') $file=8;
				else if(strtolower($refugee[1][1])=='y') $file=9;

				if($file!==null){
					$this->refugee=$this->board[$rank][$file];
				}
			}
		}
		
		// ******* SET COLOR TO MOVE *******
		if ( $matches[12] == 'w0' ) {
			$this->color_to_move = ChessPiece::WHITE;
			$this->controlled_color=null;$this->controller_color=null;
		}elseif ( $matches[12] == 'b0' ) {
			$this->color_to_move = ChessPiece::BLACK;
			$this->controlled_color=null;$this->controller_color=null;
		}elseif ( $matches[12] == 'w1' ) {
			$this->color_to_move = ChessPiece::WHITE;
			$this->controlled_color=2;
			$this->controller_color=1;
		}elseif ( $matches[12] == 'b1' ) {
			$this->color_to_move = ChessPiece::BLACK;
			$this->controlled_color=1;
			$this->controller_color=2;
		}elseif ( $matches[12] == 'w2' ) {
			$this->color_to_move = ChessPiece::BLACK;
			$this->controlled_color=null;$this->controller_color=null;
		}elseif ( $matches[12] == 'b2' ) {
			$this->color_to_move = ChessPiece::WHITE;
			$this->controlled_color=null;$this->controller_color=null;
		}else {
			throw new Exception('Invalid FEN - Invalid Color To Move');
		}
		
		// Set all castling to false. Only set to true if letter is present in FEN. Prevents bugs.
		$this->castling['white_can_castle_kingside'] = FALSE;
		$this->castling['white_can_castle_generalside'] = FALSE;
		$this->castling['black_can_castle_kingside'] = FALSE;
		$this->castling['black_can_castle_generalside'] = FALSE;
		
		if ( isset($matches[15]) ) {
			// ******* SET HALFMOVE CLOCK *******
			$this->halfmove_clock = $matches[16];
			
			// ******* SET FULLMOVE NUMBER *******
			$this->fullmove_number = $matches[17];
		// Short fen. Use default values.
		}else {

			$this->halfmove_clock = 0;
			$this->fullmove_number = 1;
		}
		$this->checkwinner();
 	}


	function import_live_fen(string $fen,$systemgameid,$playertype,$newmove): void 
	{
		$oldfen="";
		$matched=false;

		if((file_exists($systemgameid))){
			//$this->import_fen($fen);
			chmod($systemgameid, 0755);
			$file = fopen($systemgameid,"r");
	
			while(!feof($file)) {
					$line = rtrim(fgets($file),"\r\n");;
					//echo ("$line");
					if (strpos($line, '$currentfen=') !== false) {
						$splitted = explode( '$currentfen=',$line);
						$oldfen = $splitted[1];
						if($fen == "") $fen = $oldfen;
					}
					else if (strpos($line, '$newfen=') !== false) {
						$splitted = explode( '$newfen=',$line);
						$oldfen = $splitted[1];
					}
					else if (strpos($line, 'gid=') !== false) {
					$splitted = explode( 'gid=',$line);
					$gameid = $splitted[1];
					}
				if (strpos($line, '_Move=') !== false) {
						$splittedfen_notation = explode( '=',$line);
						$oldfennotation = $splittedfen_notation[1];
						$matchedfen =  explode( ';',$oldfennotation)[0];
						if (strpos($matchedfen.PHP_EOL, $fen) !== false){
							$fen=$matchedfen;
							$matched=true;
						}
					}
				}
				fclose($file);
		}
		else {
			$fen=self::DEFAULT_FEN;
			$file = fopen($systemgameid,"w");

			fwrite($file,'gid='.$systemgameid.PHP_EOL);
			fwrite($file,'$newfen='.$fen);
			fclose($file);
			chmod($systemgameid, 0755);
			$matched=true;
			$oldfen=$fen;
		}

		if($matched==false){$fen = $oldfen;}

		$this->import_fen($fen);
		if((($playertype==2) && ($this->boardtype==1)) ||
		(($playertype==1) && ($this->boardtype==2))
		){
			//Dont write anything on file.. Simply import from file to board
			$this->moveable=false;
		}
		else if ($newmove==1)
		{
			$this->moveable=true;
		
			if((strcmp($oldfen,$fen)!=0)&& (file_exists($systemgameid))){
				{
				$data = file($systemgameid);// reads an array of lines
			
				$reading = fopen($systemgameid, 'r');
				$writing = fopen($systemgameid.'.tmp.txt', 'w');
			
				$replaced = false;
				$importedmatched=false;
				while (!feof($reading)&&($importedmatched==false)) {
					$line = rtrim(fgets($reading),"\r\n");
					if ((stristr($line,'$currentfen='))&&(stristr($line,$oldfen))) {
						$line = '$currentfen='.$fen.";"."Moved_FEN=Good";
						$replaced = true;
						$importedmatched=true;
					}
					if(strlen(rtrim($line))>3)
					fputs($writing, $line.PHP_EOL);
				}
				fclose($reading);fclose($writing);
				chmod($systemgameid.".tmp.txt", 0755);

				$currentdata = file_get_contents($systemgameid.'.tmp.txt');
				file_put_contents($systemgameid, $currentdata, LOCK_EX);

				}
				
			}
		}
	}
	
	 function checkwinner():void{
		 $winners=0;
		 $this->bkingsquare=$this->get_king_square(2);//
		 $this->wkingsquare=$this->get_king_square(1);//

		 $this->bssquare=$this->get_general_square(2);//
		 $this->wssquare=$this->get_general_square(1);//

		 $this->basquare=$this->get_arthshastri_square(2);//
		 $this->wasquare=$this->get_arthshastri_square(1);//

		 $this->bnsquare=$this->get_naarad_square(2);//
		 $this->wnsquare=$this->get_naarad_square(1);//

		 $bkingsquare=$this->bkingsquare;//
		 $wkingsquare=$this->wkingsquare;//
		 
		 if((($bkingsquare=="" )||($bkingsquare==null )) && (($wkingsquare!=="" )&&($wkingsquare!==null ))) 
		 	{$this->Winner='1';$winners=1;//white Wins
				return;}

	 	if((($wkingsquare=="" )||($wkingsquare==null )) && (($bkingsquare!=="" )&&($bkingsquare!==null ))) 
		 	{$this->Winner='2';$winners=1;//black Wins
				return;}
		 
		 $bking='';$wking='';

		//We can dynamically update the FENValue
		// $string=self::promoteking(2,$string);//Promote White

		 if ($this->board[9][4]!==null) {
			if (($this->board[9][4]!==null)&&($this->board[9][4]->color==1)&&(($this->board[9][4]->group=='OFFICER')||($this->board[9][4]->group=='SOLDIER'))) {
				///self::promoteking(1,$string);//Promote White
				$this->Winner='1';$winners=$winners+1;//white Wins
			}
		}
		
		if ($this->board[9][5]!==null) {
			if (($this->board[9][5]!==null)&&($this->board[9][5]->color==1)&&(($this->board[9][5]->group=='OFFICER')||($this->board[9][5]->group=='SOLDIER'))) {
				//self::promoteking(1,$string);//Promote White
				$this->Winner='1';$winners=$winners+1;//white Wins
			}
		}
		
		if ($this->board[0][4]!==null) {
			if (($this->board[0][4]!==null)&&($this->board[0][4]->color==2)&&(($this->board[0][4]->group=='OFFICER')||($this->board[0][4]->group=='SOLDIER'))) {
				//self::promoteking(2,$string);//Promote White
				$this->Winner='2';$winners=$winners+1;//Black Wins
			}
		}
	
		if($this->board[0][5]!==null){
			if(($this->board[0][5]!==null)&&($this->board[0][5]->color==2)&&(($this->board[0][5]->group=='OFFICER')||($this->board[0][5]->group=='SOLDIER'))){
				//self::promoteking(2,$string);//Promote White
				$this->Winner='2';$winners=$winners+1;//Black Wins
			}
		}

		//TRUCE or WAR Black KINGs are offering draws
		if(($bkingsquare!==null)&&($bkingsquare->rank!==null))
		{
			if ($this->board[$bkingsquare->rank][$bkingsquare->file]!==null){
				if((($bkingsquare->rank>0) &&($bkingsquare->rank<9)&&(($bkingsquare->file==0) ||($bkingsquare->file==9))) 
				&&($this->board[$bkingsquare->rank][$bkingsquare->file]->type==self::PIECE_LETTERS[strtolower('I')])){
					$bking='D';//D
					}
				else if((($bkingsquare->rank>0) &&($bkingsquare->rank<9)&&(($bkingsquare->file>0) &&($bkingsquare->file<9))) 
				&&($this->board[$bkingsquare->rank][$bkingsquare->file]->type==self::PIECE_LETTERS[strtolower('J')])){
					$bking='D';
					}
				else if((($bkingsquare->file>0) &&($bkingsquare->file<9)&&(($bkingsquare->rank==9))) 
				&&($this->board[$bkingsquare->rank][$bkingsquare->file]->type==self::PIECE_LETTERS[strtolower('J')])){
					 $bking='D';
					}
				else if((($bkingsquare->file==0) ||($bkingsquare->file==9)&&(($bkingsquare->rank==0)||($bkingsquare->rank==9))) 
				&&($this->board[$bkingsquare->rank][$bkingsquare->file]->type==self::PIECE_LETTERS[strtolower('J')])){
					$bking='D';
					}
				else if((($bkingsquare->rank>0) &&($bkingsquare->rank<9)&&(($bkingsquare->file==0) ||($bkingsquare->file==9))) 
				&&($this->board[$bkingsquare->rank][$bkingsquare->file]->type==self::PIECE_LETTERS[strtolower('J')])){
					$wking='W';//D
					}
			}
		}

		//TRUCE or WAR KINGs are offering draws
		if(($wkingsquare!==null) && ($wkingsquare->rank!==null))
		{
			if ($this->board[$wkingsquare->rank][$wkingsquare->file]!==null){
				if((($wkingsquare->rank>0) &&($wkingsquare->rank<9)&&(($wkingsquare->file==0) ||($wkingsquare->file==9))) 
					&&($this->board[$wkingsquare->rank][$wkingsquare->file]->type==self::PIECE_LETTERS[strtolower('I')])){
						$wking='D';
				}
				else if((($wkingsquare->rank>0) &&($wkingsquare->rank<9)&&(($wkingsquare->file>0) &&($wkingsquare->file<9))) 
				&&($this->board[$wkingsquare->rank][$wkingsquare->file]->type==self::PIECE_LETTERS[strtolower('J')])){
					$wking='D';
				}
				else if((($wkingsquare->file>0) &&($wkingsquare->file<9)&&(($wkingsquare->rank==0))) 
				&&($this->board[$wkingsquare->rank][$wkingsquare->file]->type==self::PIECE_LETTERS[strtolower('J')])){
					$wking='D';
				}
				else if((($wkingsquare->file==0) ||($wkingsquare->file==9)&&(($wkingsquare->rank==0)||($wkingsquare->rank==9))) 
				&&($this->board[$wkingsquare->rank][$wkingsquare->file]->type==self::PIECE_LETTERS[strtolower('J')])){
					$wking='D';
				}
				else if((($wkingsquare->rank>0) &&($wkingsquare->rank<9)&&(($wkingsquare->file==0) ||($wkingsquare->file==9))) 
				&&($this->board[$wkingsquare->rank][$wkingsquare->file]->type==self::PIECE_LETTERS[strtolower('J')])){
					$bking='W';//D
				}
			}
		}

		$winners=0;
		//TRUCE vs WAR DRAWs or WINNERs
		if($wking=='W'){
			$this->Winner='1';$winners=1;//Draw Game
		}
		else if($bking=='W'){
			$this->Winner='2';$winners=1;//Draw Game
		}
		//TRUCE vs WAR DRAWs or WINNERs
		else if((($bking=='D')&&($wking=='D'))){
			$this->Winner='3';$winners=0;//Draw Game
			return;
		}
	
		$bwinner=0;$wwinner=0;
		for ($row = 0;$row <= 9;$row++) {
			for ($col = 0;$col <= 9;$col++) {

				if($this->board[$row][$col])
					$pcolor=$this->board[$row][$col]->color;
					
				// Winner with V on any place
				if (($this->board[$row][$col]) &&($this->board[$row][$col]->type==self::PIECE_LETTERS[strtolower('V')])){
						if($this->board[$row][$col]->color==1) {
							$this->Winner='1';$wwinner=$wwinner+1;
						}
						if ($this->board[$row][$col]->color==2) {
								$this->Winner='2';$bwinner=$bwinner+1;
						}
						//continue;
				}
				elseif (($this->board[$row][$col]) && ($row>0) &&($row<9)&&(($col==9)||($col==0))) {
					if ((($this->board[$row][$col]->type==self::PIECE_LETTERS[strtolower('J')]))&&
						($this->board[$row][$col]->color==1)) {
							$this->Winner='2';$wwinner=$wwinner+1;
						}
						if ((($this->board[$row][$col]->type==self::PIECE_LETTERS[strtolower('J')]))&&
						($this->board[$row][$col]->color==2)) {
							$this->Winner='1';$bwinner=$bwinner+1;
						}
					}
				}
		}
		if($bwinner!=0) $bwinner=1;
		if($wwinner!=0) $wwinner=1;

		$this->Winners=$bwinner+$wwinner;
	 }

	 function checkRoyalZone($piece):bool{
	
		//compromised border logic to be added later. Defects found by Basant.

		if(($piece->square->file>0) && ($piece->square->file<9)&&  ($piece->square->rank>0) && ($piece->square->rank<9) && ($this->commonborderbreached==true)) {
			if($this->iswwZoneRoyal==true)
			return true;
			if($this->isbwZoneRoyal==true)
			return true;
		}
		else if(($piece->square->file>0) && ($piece->square->file<9)&&  (($piece->square->rank>=0) && ($piece->square->rank<=4)) && ($this->wbrokencastle==true)) {
			return true;
		}
		else if(($piece->square->file>0) && ($piece->square->file<9)&&  (($piece->square->rank>=5) && ($piece->square->rank<=9)) && ($this->bbrokencastle==true)) {
			return true;
		}
		else if(($piece->square->file>0) && ($piece->square->file<9)&&  (($piece->square->rank>=1) && ($piece->square->rank<=9)) && ($this->commonborderbreached==true)) {
			if(($this->bbrokencastle== true)) {return true;}
			else return false;
		}
		else if(($piece->square->file>0) && ($piece->square->file<9)&&  (($piece->square->rank>=0) && ($piece->square->rank<=8)) && ($this->commonborderbreached==true)) {
			if(($this->wbrokencastle== true)) {return true;}
			else return false;
		}
		else if((($piece->square->file==0) || ($piece->square->file==9))&&  ($piece->square->rank>0) && ($piece->square->rank<9) && ($this->commonborderbreached==true)) {
			if(($this->isbrtZoneRoyal== true)||( $this->iswrtZoneRoyal== true)) {return true;}
			else return false;
		}
		else if(($piece->square->file>0) && ($piece->square->file<9)&&  ($piece->square->rank>0) && ($piece->square->rank<=4)) {
			if($this->iswwZoneRoyal==true)
				return true;	
		}
		else if(($piece->square->file>0) && ($piece->square->file<9)&&  ($piece->square->rank>4) && ($piece->square->rank<9)) {
			if($this->isbwZoneRoyal==true)
				return true;	
		}
		else if(($piece->square->file>0) && ($piece->square->file<9)&&  (($piece->square->rank==0))) {
			if($this->iswcZoneRoyal==true){	return true;}
			else  { return false; }
			}	
		else if(($piece->square->file>0) && ($piece->square->file<9)&&  (($piece->square->rank==9))) {
				if($this->isbcZoneRoyal==true){	return true;}
				else  { return false; }
			}				
		else if((($piece->square->file==0) ) &&  (($piece->square->rank>0) && ($piece->square->rank<9))) {
				if($this->isltZoneRoyal==true){	return true;}
				else { return false; }
				}	
		else if((($piece->square->file==9) ) &&  (($piece->square->rank>0) && ($piece->square->rank<9))) {
				if($this->isrtZoneRoyal==true){	return true;}
				else { return false; }
					}							
		return false;
	}

	function setRoyalZone__Original():void{
		$spy_squares=[];
		$spy_limits=0;

		/* */

		//compromised border logic to be added later. Defects found by Basant.

			$this->wkingsquare=$this->get_king_square(1);//
			$this->wasquare=$this->get_arthshastri_square(1);//

			$this->bkingsquare=$this->get_king_square(2);//
			$this->basquare=$this->get_arthshastri_square(2);//

			$spy_squares=$this->get_spy_squares(1);

			//War became Fullmover inActive squares.
			if(($this->wkingsquare->file>0) && ($this->wkingsquare->file<9)&& ($this->wasquare->file>0) && ($this->wasquare->file<9)&&
			(  ($this->wkingsquare->rank>0) && ($this->wkingsquare->rank<=4))&& (($this->wasquare->rank>0) && ($this->wasquare->rank<=4)))
			{

				if(($spy_squares!=null) && (count($spy_squares)>0)){
					for ($t=0;(($t<=(count($spy_squares)-1)) &&($spy_limits<=1)) ;$t=$t+1 ){
						if(($spy_squares[$t]->rank>0) && ($spy_squares[$t]->rank<=4)){
							$spy_limits=$spy_limits+1;
						}
					}
				}	
				if($spy_limits>=1) 
					{$this->iswwZoneRoyal= true; }
			}
			//War became Fullmover inActive squares.
			else if(($this->wkingsquare->file>0) && ($this->wkingsquare->file<9)&& ($this->wasquare->file>0) && ($this->wasquare->file<9)&&
			(  ($this->wkingsquare->rank>5) && ($this->wkingsquare->rank<9))&& (($this->wasquare->rank>5) && ($this->wasquare->rank<9)))
			{

				if(($spy_squares!=null) && (count($spy_squares)>0)){
					for ($t=0;(($t<=(count($spy_squares)-1)) &&($spy_limits<=1)) ;$t=$t+1 ){
						if(($spy_squares[$t]->rank>5) && ($spy_squares[$t]->rank<9)){
							$spy_limits=$spy_limits+1;
						}
					}
				}	
				if($spy_limits>=1) 
					{$this->isbwZoneRoyal= true; }
			}

				$spy_squares=$this->get_spy_squares(2);

			//War became Fullmover inActive squares.
			if(($this->bkingsquare->file>0) && ($this->bkingsquare->file<9)&& ($this->basquare->file>0) && ($this->basquare->file<9)&&
			(  ($this->bkingsquare->rank>0) && ($this->bkingsquare->rank<=4))&& (($this->basquare->rank>0) && ($this->basquare->rank<=4)))
			{

				if(($spy_squares!=null) && (count($spy_squares)>0)){
					for ($t=0;(($t<=(count($spy_squares)-1)) &&($spy_limits<=1)) ;$t=$t+1 ){
						if(($spy_squares[$t]->rank>0) && ($spy_squares[$t]->rank<=4)){
							$spy_limits=$spy_limits+1;
						}
					}
				}	
				if($spy_limits>=1) 
					{$this->iswwZoneRoyal= true; }
			}
			//War became Fullmover inActive squares.
			if(($this->bkingsquare->file>0) && ($this->bkingsquare->file<9)&& ($this->basquare->file>0) && ($this->basquare->file<9)&&
			(  ($this->bkingsquare->rank>5) && ($this->bkingsquare->rank<9))&& (($this->basquare->rank>0) && ($this->basquare->rank<9)))
			{

				if(($spy_squares!=null) && (count($spy_squares)>0)){
					for ($t=0;(($t<=(count($spy_squares)-1)) &&($spy_limits<=1)) ;$t=$t+1 ){
						if(($spy_squares[$t]->rank>5) && ($spy_squares[$t]->rank<9)){
							$spy_limits=$spy_limits+1;
						}
					}
				}	
				if($spy_limits>=1)
					{$this->isbwZoneRoyal= true; }
			}
	}	 
	
	function setRoyalZone():void{
		$spy_squares=[];
		$spy_limits=0;

		/* */

		//compromised border logic to be added later. Defects found by Basant.

			$this->wkingsquare=$this->get_king_square(1);//
			$this->wasquare=$this->get_arthshastri_square(1);//

			$this->bkingsquare=$this->get_king_square(2);//
			$this->basquare=$this->get_arthshastri_square(2);//

			//War became Fullmover inActive squares.
			if(($this->wkingsquare->file>0) && ($this->wkingsquare->file<9)&& ($this->wasquare->file>0) && ($this->wasquare->file<9)&&
			(  ($this->wkingsquare->rank>0) && ($this->wkingsquare->rank<=4))&& (($this->wasquare->rank>0) && ($this->wasquare->rank<=4)))
			{
				$this->iswwZoneRoyal= true;
			}
			//War became Fullmover inActive squares.
			else if(($this->wkingsquare->file>0) && ($this->wkingsquare->file<9)&& ($this->wasquare->file>0) && ($this->wasquare->file<9)&&
			(  ($this->wkingsquare->rank>5) && ($this->wkingsquare->rank<9))&& (($this->wasquare->rank>5) && ($this->wasquare->rank<9)))
			{
					$this->isbwZoneRoyal= true; 
			}

			//War became Fullmover inActive squares.
			if(($this->bkingsquare->file>0) && ($this->bkingsquare->file<9)&& ($this->basquare->file>0) && ($this->basquare->file<9)&&
			(  ($this->bkingsquare->rank>0) && ($this->bkingsquare->rank<=4))&& (($this->basquare->rank>0) && ($this->basquare->rank<=4)))
			{
					$this->iswwZoneRoyal= true;
			}
			//War became Fullmover inActive squares.
			if(($this->bkingsquare->file>0) && ($this->bkingsquare->file<9)&& ($this->basquare->file>0) && ($this->basquare->file<9)&&
			(  ($this->bkingsquare->rank>5) && ($this->bkingsquare->rank<9))&& (($this->basquare->rank>0) && ($this->basquare->rank<9)))
			{
					$this->isbwZoneRoyal= true;
			}

			//Check the CASTLE Zone became inactive royal
			if(($this->wkingsquare->file>0) && ($this->wkingsquare->file<9)&& ($this->wasquare->file>0) && ($this->wasquare->file<9)&&
			(  ($this->wkingsquare->rank==0))&& (($this->wasquare->rank==0)))
			{
				$this->iswcZoneRoyal= true;
			}
			//Check the CASTLE Zone became inactive royal
			else if(($this->bkingsquare->file>0) && ($this->bkingsquare->file<9)&& ($this->basquare->file>0) && ($this->basquare->file<9)&&
			(  ($this->bkingsquare->rank==9))&& (($this->basquare->rank==9)))
			{
					$this->isbcZoneRoyal= true; 
			}




			//Check the Left Truce Zone became inactive royal
			if(($this->wkingsquare->file==0) && ($this->wasquare->file==0) &&
			(  ($this->wkingsquare->rank>0) && ($this->wkingsquare->rank<=4))&& (($this->wasquare->rank>0) && ($this->wasquare->rank<=4)))
			{
				$this->iswltZoneRoyal= true;
			}
			//Check the Right Truce Zone became inactive royal
			else if(($this->wkingsquare->file==9) && ($this->wasquare->file==9) &&
			(  ($this->wkingsquare->rank>0) && ($this->wkingsquare->rank<=4))&& (($this->wasquare->rank>0) && ($this->wasquare->rank<=4)))
			{
				$this->iswrtZoneRoyal= true;
			}

			//Check the Left Truce Zone became inactive royal
			if(($this->bkingsquare->file==0) && ($this->bkingsquare->file==0) &&
			(  ($this->bkingsquare->rank>4) && ($this->bkingsquare->rank<=8))&& (($this->basquare->rank>4) && ($this->basquare->rank<=8)))
			{
				$this->isbltZoneRoyal= true;
			}
			//Check the Right Truce Zone became inactive royal
			else if(($this->bkingsquare->file==9) && ($this->basquare->file==9) &&
			(  ($this->bkingsquare->rank>4) && ($this->bkingsquare->rank<=8))&& (($this->basquare->rank>4) && ($this->basquare->rank<=8)))
			{
				$this->isbrtZoneRoyal= true;
			}

			//Check the Left Truce Zone became inactive royal
			if(($this->wkingsquare->file==0) && ($this->wasquare->file==0) &&
			(  ($this->wkingsquare->rank>4) && ($this->wkingsquare->rank<=8))&& (($this->wasquare->rank>4) && ($this->wasquare->rank<=8)))
			{
				$this->iswltZoneRoyal= true;
				$this->isbltZoneRoyal= true;
			}
			//Check the Right Truce Zone became inactive royal
			else if(($this->wkingsquare->file==9) && ($this->wasquare->file==9) &&
			(  ($this->wkingsquare->rank>4) && ($this->wkingsquare->rank<=8))&& (($this->wasquare->rank>4) && ($this->wasquare->rank<=8)))
			{
				$this->iswrtZoneRoyal= true;
				$this->isbrtZoneRoyal= true;
			}

			//Check the Left Truce Zone became inactive royal
			if(($this->bkingsquare->file==0) && ($this->basquare->file==0) &&
			(  ($this->bkingsquare->rank>4) && ($this->bkingsquare->rank<=8))&& (($this->basquare->rank>4) && ($this->basquare->rank<=8)))
			{
				$this->isbltZoneRoyal= true;
				$this->iswltZoneRoyal= true;

			}
			//Check the Right Truce Zone became inactive royal
			else if(($this->bkingsquare->file==9) && ($this->basquare->file==9) &&
			(  ($this->bkingsquare->rank>4) && ($this->bkingsquare->rank<=8))&& (($this->basquare->rank>4) && ($this->basquare->rank<=8)))
			{
				$this->isbrtZoneRoyal= true;
				$this->iswrtZoneRoyal= true;
			}

			if($this->commonborderbreached==true){ 	
				//isbcZoneRoyal
				if( ($this->iswwZoneRoyal==true)|| ($this->isbwZoneRoyal==true)){
					 $this->iswwZoneRoyal=true;
					 $this->isbwZoneRoyal=true; 
					}
				}
			}	 

	function promoteking($color,$string):string{

		if($color==1){
			$string = str_replace('U', 'V', $string);
			$string = str_replace('Y', 'V', $string);
			$string = str_replace('I', 'V', $string);
			$string = str_replace('A', 'Ä', $string);

			$string = str_replace('u', 'r', $string);
			$string = str_replace('j', 'r', $string);
			$string = str_replace('y', 'r', $string);
			$string = str_replace('i', 'r', $string);
		}
		elseif($color==2){
			$string = str_replace('u', 'v', $string);
			$string = str_replace('y', 'v', $string);
			$string = str_replace('i', 'v', $string);
			$string = str_replace('a', 'ä', $string);

			$string = str_replace('U', 'R', $string);
			$string = str_replace('J', 'R', $string);
			$string = str_replace('Y', 'R', $string);
			$string = str_replace('I', 'R', $string);
		}
		return $string;
	}

	
	//function export_fen_moves($move->starting_square,$move->ending_square)
	function export_fen_moves(ChessSquare $starting_square,ChessSquare $ending_square, bool $controlled_move=false, int $tamedpawn=0): string {
		//Echo '<li> ChessBoard.php 2# function export_fen called </li>';
		$string = '';
		$piece=null;
		$startingpiece=null;
		$middleman= null;$originator=null;$pushedpiece=null;$pusherpiece=null;$finalpushedpiece=null;
		$ending_square->mediatorrank=null;$ending_square->mediatorfile=null;
		$cn="";$rankdiff=-1;$filediff=-1;
		$startingpiece= $this->board[$starting_square->rank][$starting_square->file];

		if(($tamedpawn !==0))//&& ($ending_square->rank==2) &&($ending_square->file ==1))
		$test=1;

		if(($ending_square->rank==3) &&($ending_square->file ==6) )//&& ($ending_square->rank==2) &&($ending_square->file ==1))
			$test=1;
		if(($starting_square!==null) &&($ending_square!==null)){
			$rankdiff=($starting_square->rank - $ending_square->rank);
			$filediff=($starting_square->file - $ending_square->file);
		}

		//comparison  == failed for starting_square and ending_square...So used this trick
		if(($rankdiff==0) && ($filediff==0) && ($tamedpawn >= 0 )){//&& ($ending_square->rank==2) &&($ending_square->file ==1))
			$ttt=1;
			//Exception for Swapping of the values
		}
		else if ($controlled_move==false)
		{
			for ($rank = 9;$rank >= 0;$rank--) {
				if($pusherpiece!==null) 
					break;
				for ($file = 0;$file <= 9;$file++) {

					if(($rank==2) &&($file ==7))
						$test=1;

					if (($this->board[$rank][$file]!==null) && 
					($rank==$ending_square->rank) &&
					($file==$ending_square->file)&& ($this->board[$starting_square->rank][$starting_square->file]==null)) {
						$state=$this->board[$rank][$file]->state;
						if( (strpos($state,"V=")!==FALSE) && ($this->board[$rank][$file]->type!==null)) {
							$originator=clone $this->board[$rank][$file];
							$originator->color=(int) explode(",", explode("color:",$state)[1])[0];
							$originator->state="V";
							$originator->type=(int) explode(",", explode("type:",$state)[1])[0];
							$originator->mortal=(int) explode(",", explode("mortal:",$state)[1])[0];
							$originator->striker=(int) explode(",", explode("striker:",$state)[1])[0];
							$originator->group=explode(",", explode("group:",$state)[1])[0];
							$originator->square->rank=$rank;
							$originator->square->file=$file;

							$middleman=clone $this->board[$rank][$file];
							$middleman->rank=$this->board[$rank][$file]->square->rank;
							$middleman->file=$this->board[$rank][$file]->square->file;

							$middleman->state="R";
							$orank=(int) explode(",", explode("rank:",$state)[1])[0];
							$ofile=(int) explode(";", explode("file:",$state)[1])[0];

							$this->board[$middleman->square->rank][$middleman->square->file]=$middleman;
							$this->board[$rank][$file]=$originator;
						}
						else if((strpos($state,"Fake=")!==FALSE) && ($this->board[$rank][$file]->type!==null)) {
								$originator=clone $this->board[$rank][$file];
	
								$middleman=clone $this->board[$rank][$file];
								$middleman->square->rank=$rank;
								$middleman->square->file=$file;
								$middleman->type=(int) explode(",", explode("type:",$state)[1])[0];
								$middleman->group=(int) explode(",", explode("group:",$state)[1])[0];
								$middleman->color=(int) explode(",", explode("color:",$state)[1])[0];
								$middleman->state="R";
								$orank=(int) explode(",", explode("rank:",$state)[1])[0];
								$ofile=(int) explode(";", explode("file:",$state)[1])[0];
	
								$this->board[$middleman->square->rank][$middleman->square->file]=$middleman;
								$originator=$middleman;
								$this->board[$rank][$file]=$originator;
							}
						else if((strpos($state,"R")!==FALSE) && ($this->board[$rank][$file]->type!==null) && ($tamedpawn <= -1 )) {
								$finalenemyposition=clone $this->board[$rank][$file];
								$finalenemyposition->square->rank=$starting_square->rank;
								$finalenemyposition->square->file=$starting_square->file;
							
							if($tamedpawn !== 0) { 
								$finalenemyposition->type=13; $finalenemyposition->group='SOLDIER'; 
								if($tamedpawn!=0) $ttamedpawn=abs($tamedpawn);
								$finalenemyposition->color=$ttamedpawn;//abs(3-$finalenemyposition->color);
							}
							$this->board[$finalenemyposition->square->rank][$finalenemyposition->square->file]=$finalenemyposition;
						}							
					}
					else if (($this->board[$rank][$file]!==null) && 
					($this->board[$rank][$file]->selfpushed==true)&&
					($this->board[$rank][$file]->selfpushedsquare!==null)) 
					{
						$state=$this->board[$rank][$file]->state;
						if(strpos($state,"V=")!==FALSE){
							$originator=clone $this->board[$rank][$file];
							$originator->color=(int) explode(",", explode("color:",$state)[1])[0];
							$originator->state="V";
							$originator->type=(int) explode(",", explode("type:",$state)[1])[0];
							$originator->mortal=(int) explode(",", explode("mortal:",$state)[1])[0];
							$originator->striker=(int) explode(",", explode("striker:",$state)[1])[0];
							$originator->group=explode(",", explode("group:",$state)[1])[0];
							$originator->square->rank=$rank;
							$originator->square->file=$file;

							$middleman=clone $this->board[$rank][$file];
							$middleman->rank=$this->board[$rank][$file]->square->rank;
							$middleman->file=$this->board[$rank][$file]->square->file;

							$middleman->state="R";
							$orank=(int) explode(",", explode("rank:",$state)[1])[0];
							$ofile=(int) explode(";", explode("file:",$state)[1])[0];

							///this middlemen doubtful for starting position???							
							$this->board[$middleman->square->rank][$middleman->square->file]=$middleman;
							$this->board[$rank][$file]=$originator;

							$finalenemyposition=clone $this->board[$rank][$file]->selfpushedpiece;

								$finalenemyposition->square->rank=$this->board[$rank][$file]->selfpushedsquare["rank"];
								$finalenemyposition->square->file=$this->board[$rank][$file]->selfpushedsquare["file"];
							
							 if($tamedpawn <= -1 ) { //if middlemen?? 
								$finalenemyposition->square->rank=$starting_square->rank;
								$finalenemyposition->square->file=$starting_square->file;
							}
							
							if($tamedpawn !== 0) { $finalenemyposition->type=13; $finalenemyposition->group='SOLDIER';
								if($tamedpawn!=0) $ttamedpawn=abs($tamedpawn);
								$finalenemyposition->color=$ttamedpawn;//abs(3-$finalenemyposition->color);	
								}
							$this->board[$finalenemyposition->square->rank][$finalenemyposition->square->file]=$finalenemyposition;
						}
						if(strpos($state,"Fake=")!==FALSE){
							$originator=clone $this->board[$rank][$file];
							$originator->color=(int) explode(",", explode("color:",$state)[1])[0];
							$originator->state="V";
							$originator->type=(int) explode(",", explode("type:",$state)[1])[0];
							$originator->mortal=(int) explode(",", explode("mortal:",$state)[1])[0];
							$originator->striker=(int) explode(",", explode("striker:",$state)[1])[0];
							$originator->group=explode(",", explode("group:",$state)[1])[0];
							$originator->square->rank=$rank;
							$originator->square->file=$file;

							$middleman=clone $this->board[$rank][$file];
							$middleman->rank=$this->board[$rank][$file]->square->rank;
							$middleman->file=$this->board[$rank][$file]->square->file;

							$middleman->state="R";
							$orank=(int) explode(",", explode("rank:",$state)[1])[0];
							$ofile=(int) explode(";", explode("file:",$state)[1])[0];

							$this->board[$middleman->square->rank][$middleman->square->file]=$middleman;
							$this->board[$rank][$file]=$originator;

							$finalenemyposition=clone $this->board[$rank][$file];
							$finalenemyposition->color=(int) explode(",", explode("endcolor:",$state)[1])[0];
							$finalenemyposition->state="V";

							if($tamedpawn !== 0) { $finalenemyposition->type=13;
								if($tamedpawn!=0) $ttamedpawn=abs($tamedpawn);
								$finalenemyposition->color=$ttamedpawn;//abs(3-$finalenemyposition->color);
							}
							else 	$finalenemyposition->type=(int) explode(",", explode("endtype:",$state)[1])[0];
							$finalenemyposition->mortal=(int) explode(",", explode("endmortal:",$state)[1])[0];
							$finalenemyposition->striker=(int) explode(",", explode("endstriker:",$state)[1])[0];

							if($tamedpawn !== 0) { $finalenemyposition->group='SOLDIER';}
							else $finalenemyposition->group=explode(",", explode("endgroup:",$state)[1])[0];
							$finalenemyposition->square->rank=explode(",", explode("endrank:",$state)[1])[0];
							$finalenemyposition->square->file=explode(";", explode("endfile:",$state)[1])[0];
							
							 if($tamedpawn <= -1 ) { //check middlemen if possible
								$finalenemyposition->square->rank=$starting_square->rank;
								$finalenemyposition->square->file=$starting_square->file;
							}
							
							if($tamedpawn !== 0) { $finalenemyposition->type=13; $finalenemyposition->group='SOLDIER'; 
								if($tamedpawn!=0) $ttamedpawn=abs($tamedpawn);
								$finalenemyposition->color=$ttamedpawn;//abs(3-$finalenemyposition->color);	
								}
							$this->board[$finalenemyposition->square->rank][$finalenemyposition->square->file]=$finalenemyposition;
						}
						else if (($this->board[$starting_square->rank][$starting_square->file]==null) && 
						($this->board[$ending_square->rank][$ending_square->file]==null) &&
						($this->board[$rank][$file]->selfpushed==true))
							if(($this->board[$rank][$file]->selfpushedsquare!==null) && ($this->board[$rank][$file]->selfpushedsquare["rank"]==$ending_square->rank)&&
							($this->board[$rank][$file]->selfpushedsquare["file"]==$ending_square->file)) {
								$this->board[$ending_square->rank][$ending_square->file]=$this->board[$rank][$file]->selfpushedpiece;
								$this->board[$ending_square->rank][$ending_square->file]->state='R';
								$this->board[$ending_square->rank][$ending_square->file]->square=$ending_square;
							}
						}
				}
			}
			if($tamedpawn>=1)
			if($this->board[$starting_square->rank][$starting_square->file]!==null){
				$this->board[$starting_square->rank][$starting_square->file]=null;
			}

			if($tamedpawn<=-1){
				if($this->board[$starting_square->rank][$starting_square->file]==null){
					$finalpushedpiece= clone $this->board[$ending_square->rank][$ending_square->file];
				}
				else $finalpushedpiece = clone $this->board[$ending_square->rank][$ending_square->file];
			}

		}

		$controlled_notation="";
		$finalpushedpiece;
			for ($rank = 9;$rank >= 0;$rank--) {
				$empty_squares = 0;
				$empty_l =0;
				$empty_r =0;
				$instance =null;
				//echo '<br/><br/>';
				for ($file = 0;$file <= 9;$file++) {
					$piece=null;
					if($this->board[$rank][$file]!==null)
						$piece = clone $this->board[$rank][$file];
					$instance ='1';

					if(($rank==4) &&($file ==5))
						$test=1;
					if(($starting_square->rank==$rank) &&($starting_square->file==$file)&& 
					(($controlled_move==true)&&($this->board[$starting_square->rank][$starting_square->file]!==null))){
							//$this->board[$starting_square->rank][$starting_square->file]=null;
							$piece=null;
						}

					//Pushed tamed piece is matched with victim. Add
					if(($controlled_move==false)&&($finalpushedpiece!==null) && ($piece==null) && ($ending_square->file==$file) && ($ending_square->rank==$rank) 
					&& (($finalpushedpiece->square->rank ==$rank)&&
					($finalpushedpiece->square->file==$file)&&($finalpushedpiece->square->rank!==null))&& ( $tamedpawn !== 0 )) {
						$piece=clone $finalpushedpiece;
						$piece->type=13;
						$piece->group='SOLDIER';
						//if($this->board[$rank][$file]->selfpushedpiece->color!=null)
						if($tamedpawn!=0) $ttamedpawn=abs($tamedpawn);
							$piece->color=$this->board[$ending_square->rank][$ending_square->file]->color=$ttamedpawn;//abs(3-$this->board[$rank][$file]->selfpushedpiece->color);
						//$piece->color=abs($piece->color);
					}

					if(($controlled_move==false)&&($finalpushedpiece==null) && ($piece==null) && ($ending_square->file==$file) && ($ending_square->rank==$rank) 
					&& (($starting_square->rank ==$rank)&&
					($starting_square->file==$file))&& ( $tamedpawn <= -1 )) {
						$piece=clone $finalenemyposition;
						$piece->type=13;
						$piece->group='SOLDIER';
						//if($this->board[$rank][$file]->selfpushedpiece->color!=null)
						if($tamedpawn!=0) $ttamedpawn=abs($tamedpawn);
							$piece->color=$this->board[$ending_square->rank][$ending_square->file]->color=$ttamedpawn;//abs(3-$this->board[$rank][$file]->selfpushedpiece->color);
						//$piece->color=abs($piece->color);
					}

					//Pushed tamed piece is matched with victim. Add
					else if(($controlled_move==false)&&($finalpushedpiece!=null) && ($ending_square->file==$file) && ($ending_square->rank==$rank) && ( $tamedpawn <=-1 )){
						$piece= clone $finalpushedpiece;
					}

					//Pushed piece gets the starting piece. Use the Starting Piece
					else if(($controlled_move==false)&&($pushedpiece!==null) && ($pusherpiece!==null)&&(($pushedpiece->square->rank ==$piece->square->rank)&&
					($pushedpiece->square->file==$piece->square->file)&&($pushedpiece->square->rank!==null))&& ( $tamedpawn !== 0 )) {
						$piece=clone $pusherpiece;$piece->square->file=$file;$piece->square->rank=$rank;
					}
					 else if(($controlled_move==false)&& ($ending_square->file==$file) && ($ending_square->rank==$rank) 
					&& ( $tamedpawn !==0 ) &&($piece!=null)) {
						$piece->type=13;
						$piece->group='SOLDIER';
						//if($this->board[$rank][$file]->selfpushedpiece->color!=null)
						if($tamedpawn!=0) $ttamedpawn=abs($tamedpawn);
							$piece->color=$this->board[$ending_square->rank][$ending_square->file]->color=$ttamedpawn;//abs(3-$this->board[$rank][$file]->selfpushedpiece->color);

					}
					//Pusher is matched with victim's piece. Skip
					else if(($controlled_move==false)&&($pusherpiece!==null) && ($piece==null) && ($starting_square->file==$file) && ($starting_square->rank==$rank) 
					&& (($pusherpiece->square->rank ==$rank)&&
					($pusherpiece->square->file==$file)&&($pusherpiece->square->rank!==null))) {
						$piece=null;
					}
					//Pushed piece is matched with victim. Add
					else if(($controlled_move==false)&&($finalpushedpiece!==null) && ($piece==null) && ($ending_square->file==$file) && ($ending_square->rank==$rank) 
					&& (($finalpushedpiece->square->rank ==$rank)&&
					($finalpushedpiece->square->file==$file)&&($finalpushedpiece->square->rank!==null))) {
						$piece=clone $finalpushedpiece;
					}
					//Pushed piece gets the starting piece. Use the Starting Piece
					else if(($controlled_move==false)&&($pushedpiece!==null) && ($pusherpiece!==null)&&(($pushedpiece->square->rank ==$piece->square->rank)&&
					($pushedpiece->square->file==$piece->square->file)&&($pushedpiece->square->rank!==null))) {
						$piece=clone $pusherpiece;$piece->square->file=$file;$piece->square->rank=$rank;
					}
					
					// Controlled piece is moved to ending Piece //
					else if(($controlled_move==true)&&(($controlled_notation=="")||($controlled_notation==null)) && ($this->controlled_color==2)&& ($this->controller_color==1) && ($ending_square->file==$file) && ($ending_square->rank==$rank) ) {
						$piece= null;
						for ($bl = 0;$bl < count($this->PinnedBRefugees);$bl++) {
							if(($this->PinnedBRefugees[$bl]->square->file==$starting_square->file)&&($this->PinnedBRefugees[$bl]->square->rank==$starting_square->rank)){
								$piece=clone $this->PinnedBRefugees[$bl];$piece->square->file=$ending_square->file;$piece->square->rank=$ending_square->rank;
								$controlled_notation=$piece->get_fen_symbol().$piece->square->get_alphanumeric();
								$cn=$controlled_notation;
								break;
							}
						}
					}
					else if(($controlled_move==true)&&(($controlled_notation=="")||($controlled_notation==null))&&($this->controlled_color==1)&& ($this->controller_color==2) && ($ending_square->file==$file) && ($ending_square->rank==$rank) ) {
						$piece= null;
						for ($wl = 0;$wl < count($this->PinnedWRefugees);$wl++) {
							if(($this->PinnedWRefugees[$wl]->square->file==$starting_square->file)&&($this->PinnedWRefugees[$wl]->square->rank==$starting_square->rank)){
								$piece=clone $this->PinnedWRefugees[$wl];$piece->square->file=$ending_square->file;$piece->square->rank=$ending_square->rank;
								$controlled_notation=$piece->get_fen_symbol().$piece->square->get_alphanumeric();
								$cn=$controlled_notation;
								break;
							}
						}
					}
					////////////////////////
					if ($piece->color==3) {
					$ttt=1;
					}

					if (($piece)) {
						$instance=$piece->get_fen_symbol();
					}//else {$instance=null};

					if ((! $piece)||($instance=='') ||(($instance==null))) {
						//echo ' (Empty) ';
						if ($file ==0) {
							$empty_l =1;
							$string .=$empty_l;
						}elseif ($file ==9) {
								if (($empty_squares<=8)&&($empty_squares>=1)) {
								$string .=$empty_squares;
								}

								$empty_r =1;
								$string .=$empty_r;
						}else {
							$empty_squares++;
							}
					}else {//Square as some piece
								//echo ' #Piece# = '.$piece->type;
								//echo ' Blank = '.$empty_squares;
								if ($empty_squares) {//if empty square is non-zero and reached the file
									$string .= $empty_squares;
									$empty_squares = 0;
								}
								$string .= $piece->get_fen_symbol();
							}
				}

				if ($rank != 0) {
					$string .= "/";
					//$controlled_notation="";
				}
		}

			$string .= " ~";	

			if (($this->bbrokencastle==false)){
				$string .= "b";
			}
			if (($this->wbrokencastle==false)){
				$string .= "w";
			}	
			if (($this->commonborderbreached==false)){
				$string .= "c";
			}

								
		if ((strtolower($instance)=='n')&&($this->controlled_color!==null) && ($this->controller_color!==null) && ($this->controlled_color == ChessPiece::WHITE)){
				if (($this->board[$starting_square->rank][$starting_square->file]==null)&&($controlled_move==true)
					&&($this->controller_color==1)) {
					$string .= " w2 ";
				}
				else if (($this->board[$starting_square->rank][$starting_square->file]==null)&&($controlled_move==true)
				&&($this->controller_color==1)) {
					$string .= " b1 ";
				}
				else if(($controlled_move==false) && ($this->controller_color==1) ){
					$this->controlled_color=null;
					$this->controller_color=null;
					$string .= " b0 ";
				}
				else if(($controlled_move==false) && ($this->controller_color==2) &&  ($this->color_to_move==2)){
					$this->controlled_color=null;
					$this->controller_color=null;
					$string .= " w0 ";
				}
				else if(($controlled_move==false) && ($this->controller_color==2) &&  ($this->color_to_move==1)){
					$this->controlled_color=null;
					$this->controller_color=null;
					$string .= " b0 ";
				}
				else if($controlled_move==false){
					$this->controlled_color=null;
					$this->controller_color=null;
					$string .= " w0 ";
				}
				else if($controlled_move==true){
					$string .= " b1 ";
				}
			}
		elseif ((strtolower($instance)=='n')&&($this->controlled_color!==null) && ($this->controller_color!==null) && ($this->controlled_color == ChessPiece::BLACK)) {
				if (($this->board[$starting_square->rank][$starting_square->file]==null)&&($controlled_move==true)
					&&($this->controller_color==2)) {
					$string .= " b2 ";
					}
				else if (($this->board[$starting_square->rank][$starting_square->file]==null)&&($controlled_move==true)
					&&($this->controller_color==2)) {
					$string .= " w1 ";
					}
				else if(($controlled_move==false) && ($this->controller_color==2) ){
						$this->controlled_color=null;
						$this->controller_color=null;
						$string .= " w0 ";
				}
				else if(($controlled_move==false) && ($this->controller_color==1) &&  ($this->color_to_move==1)){
					$this->controlled_color=null;
					$this->controller_color=null;
					$string .= " b0 ";
				}
				else if(($controlled_move==false) && ($this->controller_color==1) &&  ($this->color_to_move==2)){
					$this->controlled_color=null;
					$this->controller_color=null;
					$string .= " w0 ";
				}
				else if ($controlled_move==false){
					$this->controlled_color=null;
					$this->controller_color=null;
					$string .= " b0 ";
				}
				else if($controlled_move==true){
					$string .= " w1 ";
				}
			}
		else if ($this->color_to_move == ChessPiece::WHITE) {
			if (($this->board[$starting_square->rank][$starting_square->file]==null))
				$string .= " w0 "; //it was w0
			else if (($this->board[$starting_square->rank][$starting_square->file]!==null)&&
				($ending_square!==null)&&(($starting_square->rank==$ending_square->rank)&&($starting_square->file==$ending_square->file)))
				$string .= " w0 "; //it was w0
			else if (($this->board[$starting_square->rank][$starting_square->file]!==null)&&
				($ending_square!==null)&&(  ($tamedpawn !== 0 ) ))
				$string .= " w0 "; //it was w0			
			}
		elseif ($this->color_to_move == ChessPiece::BLACK) {
			if (($this->board[$starting_square->rank][$starting_square->file]==null))
				$string .= " b0 "; //it was b0
			else if (($this->board[$starting_square->rank][$starting_square->file]!==null)&&
			($ending_square!==null)&&(($starting_square->rank==$ending_square->rank)&&($starting_square->file==$ending_square->file)))
				$string .= " b0 "; //it was b0
			else if (($this->board[$starting_square->rank][$starting_square->file]!==null)&&
				($ending_square!==null)&&(  ($tamedpawn !== 0 ) ))
				$string .= " b0 "; //it was b0
		}

		$string .= "(".$cn .")";
		$string .= " -";
		$string .= " " . $this->halfmove_clock . ' ' . $this->fullmove_number;

		//Change positions of winning empereror and arthshastri if present

		if ($this->board[9][4]!==null) {
			if (($this->board[9][4]!==null)&&($this->board[9][4]->color==1)&&(($this->board[9][4]->group=='OFFICER')||($this->board[9][4]->group=='SOLDIER'))) {
				$string = self::promoteking(1,$string);//Promote White
				return $string;
			}
		}
		
		if ($this->board[9][5]!==null) {
			if (($this->board[9][5]!==null)&&($this->board[9][5]->color==1)&&(($this->board[9][5]->group=='OFFICER')||($this->board[9][5]->group=='SOLDIER'))) {
				$string = self::promoteking(1,$string);//Promote White
				return $string;
			}
		}
		
		if ($this->board[0][4]!==null) {
			if (($this->board[0][4]!==null)&&($this->board[0][4]->color==2)&&(($this->board[0][4]->group=='OFFICER')||($this->board[0][4]->group=='SOLDIER'))) {
				$string = self::promoteking(2,$string);//Promote Black
				return $string;
			}
		}
	
		if($this->board[0][5]!==null){
			if(($this->board[0][5]!==null)&&($this->board[0][5]->color==2)&&(($this->board[0][5]->group=='OFFICER')||($this->board[0][5]->group=='SOLDIER'))){
				$string = self::promoteking(2,$string);//Promote Black
				return $string;
			}
		}
	
		if (strpos($string,"Ä")!==FALSE) {
			$string = self::promoteking(1,$string);//Promote White
			return $string;
		}
		
		if (strpos($string,"ä")!==FALSE) {
			$string = self::promoteking(2,$string);//Promote Black
			return $string;
		}

		if ((strpos($string,"V")!==FALSE)) {
			$string = self::promoteking(1,$string);//Promote White
			return $string;
		}
		else
		if ((strpos($string,"v")!==FALSE)) {
			$string = self::promoteking(2,$string);//Promote Black
			return $string;
		}
		
		return $string;
	}

	function export_fen(): string {
		$string = '';
		$piece = null;
		
		for ($rank = 9;$rank >= 0;$rank--) {
			$empty_squares = 0;
			$empty_l =0;
			$empty_r =0;
			$instance =null;
			//echo '<br/><br/>';
			for ($file = 0;$file <= 9;$file++) {
				$piece=null;
				if($this->board[$rank][$file]!==null)
					$piece = clone $this->board[$rank][$file];
				$instance ='1';
				//Pusher is matched with victim's piece. Skip

				if (($piece)) {
					$instance=$piece->get_fen_symbol();
				}//else {$instance=null};

				if ((! $piece)||($instance=='') ||(($instance==null))) {
					//echo ' (Empty) ';
					if ($file ==0) {
						$empty_l =1;
						$string .=$empty_l;
					}elseif ($file ==9) {
							if (($empty_squares<=8)&&($empty_squares>=1)) {
								$string .=$empty_squares;
							}

						$empty_r =1;
						$string .=$empty_r;
						}else {
							$empty_squares++;
					}
				}else {//Square as some piece
						//echo ' #Piece# = '.$piece->type;
						//echo ' Blank = '.$empty_squares;
						if ($empty_squares) {//if empty square is non-zero and reached the file
							$string .= $empty_squares;
							$empty_squares = 0;
						}
					$string .= $piece->get_fen_symbol();
				}
			}

			if ($rank != 0) {
				$string .= "/";
			}
		}
	
		$string .= " ~";

		if (($this->bbrokencastle==false)){
			$string .= "b";
		}	
		if (($this->wbrokencastle==false)){
			$string .= "w";
		}
		if (($this->commonborderbreached==false)){
			$string .= "c";
		}
			
		if (($this->controller_color== ChessPiece::WHITE)&&($this->controlled_color==ChessPiece::BLACK)) {
			$string .= " w1 ";
		}
		else if (($this->controller_color== ChessPiece::WHITE)&&($this->controlled_color==ChessPiece::WHITE)) {
			$string .= " w2 ";
		}
		else if (($this->controller_color== ChessPiece::BLACK)&&($this->controlled_color==ChessPiece::WHITE)) {
			$string .= " b1 ";
		}
		else if (($this->controller_color== ChessPiece::BLACK)&&($this->controlled_color==ChessPiece::BLACK)) {
			$string .= " b2 ";
		}
		else if ($this->color_to_move == ChessPiece::WHITE) {
			$string .= " w0 ";
		}
		else if ($this->color_to_move == ChessPiece::BLACK) {
			$string .= " b0 ";
		}
		if($this->controlled_piece ==null)
		$string .= "(".")";
		else 
		$string .= $this->controlled_piece;

		$string .= " -";
		$string .= " " . $this->halfmove_clock . ' ' . $this->fullmove_number;

		//Change positions of winning empereror and arthshastri if present

		if ($this->board[9][4]!==null) {
			if (($this->board[9][4]!==null)&&($this->board[9][4]->color==1)&&(($this->board[9][4]->group=='OFFICER')||($this->board[9][4]->group=='SEMIROYAL')||($this->board[9][4]->group=='SOLDIER'))) {
				$string = self::promoteking(1,$string);//Promote White
				return $string;
			}
		}
		
		if ($this->board[9][5]!==null) {
			if (($this->board[9][5]!==null)&&($this->board[9][5]->color==1)&&(($this->board[9][5]->group=='OFFICER')||($this->board[9][5]->group=='SEMIROYAL')||($this->board[9][5]->group=='SOLDIER'))) {
				$string = self::promoteking(1,$string);//Promote White
				return $string;
			}
		}
		
		if ($this->board[0][4]!==null) {
			if (($this->board[0][4]!==null)&&($this->board[0][4]->color==2)&&(($this->board[0][4]->group=='OFFICER')||($this->board[0][4]->group=='SEMIROYAL')||($this->board[0][4]->group=='SOLDIER'))) {
				$string = self::promoteking(2,$string);//Promote Black
				return $string;
			}
		}
	
		if($this->board[0][5]!==null){
			if(($this->board[0][5]!==null)&&($this->board[0][5]->color==2)&&(($this->board[0][5]->group=='OFFICER')||($this->board[0][5]->group=='SEMIROYAL')||($this->board[0][5]->group=='SOLDIER'))){
				$string = self::promoteking(2,$string);//Promote Black
				return $string;
			}
		}
	
		if (strpos($string,"Ä")!==FALSE) {
			$string = self::promoteking(1,$string);//Promote White
			return $string;
		}
		
		if (strpos($string,"ä")!==FALSE) {
			$string = self::promoteking(2,$string);//Promote Black
			return $string;
		}

		if ((strpos($string,"V")!==FALSE)) {
			$string = self::promoteking(1,$string);//Promote White
			return $string;
		}
		else
		if ((strpos($string,"v")!==FALSE)) {
			$string = self::promoteking(2,$string);//Promote Black
			return $string;
		}
		
		return $string;
	}

	function export_live_fen(string $fcondition,array $legal_moves,$systemgameid): string
	{
		$exportedfen="";
		$matched=0;
		//add the returned data in file
		$exportedfen= $this->export_fen();
		$gametype="old";

		if(($fcondition=="1")&&(file_exists($systemgameid))){
			$file = fopen($systemgameid,"r");
	
			while(!feof($file)) {
					$line = fgets($file);

					if (strpos($line, '$newfen=') !== false) {
						//$splitted = explode( '$currentfen=',$line);
						//$fen = $splitted[1];
						//if (strpos($exportedfen.PHP_EOL, $fen) !== false) {
							//$matched=2;//New request
						//}
						$gametype="new";
					}
					else if (strpos($line, '$currentfen=') !== false) {
						$splitted = explode( '$currentfen=',$line);
						$fen = $splitted[1];
						if (strpos($exportedfen.PHP_EOL, $fen) !== false) {
							$matched=2;//New request
							$gametype="old";
						}
					}
					else if (strpos($line, 'gid=') !== false) {
					$splitted = explode( 'gid=',$line);
					$gameid = $splitted[1];
					}

					if (strpos($line, '_Move=') !== false) {
						$splittedfen_notation = explode( '=',$line);
						$oldfennotation = $splittedfen_notation[1];
						$matchedfen =explode( ';',$oldfennotation)[0];
						//if (strpos($matchedfen.PHP_EOL, $fen) !== false){
							//$fen=$matchedfen;
							$matched=1;
						//}
					}
				}
				fclose($file);

				if($matched==2){
					$file = fopen($systemgameid,"a");
					$movecount=0;
					foreach ( $legal_moves as $key => $move ): 
						$movecount=$movecount+1;

						$notationvalue="";$ending_square=$move->ending_square;
						if($move->pushedending_square!==null)
							$ending_square=$move->pushedending_square;
						$move_FEN= $move->board->export_fen_moves($move->starting_square,$move->ending_square);
						$move->ending_square=$ending_square;
						$notationvalue=$move->get_notation();
						fwrite($file, $movecount.'_Move='.$move_FEN.';');
						fwrite($file,$movecount.'_Notation='.$notationvalue.PHP_EOL);
					endforeach;
	
					fclose($file);
					}
			}

		if(($gametype=="new") && ($systemgameid!==null)) {
			$file = fopen($systemgameid,"w");
			fwrite($file,'gid='.$systemgameid.PHP_EOL);
			fwrite($file,'$currentfen='.$exportedfen.PHP_EOL);
			$movecount=0;
			foreach ( $legal_moves as $key => $move ): 
				$movecount=$movecount+1;
				$notationvalue="";$ending_square=$move->ending_square;
				if($move->pushedending_square!==null)
					$ending_square=$move->pushedending_square;
					$move_FEN = $move->board->export_fen_moves($move->starting_square,$move->ending_square);
					$move->ending_square=$ending_square;
					$notationvalue=$move->get_notation();
					fwrite($file, $movecount.'_Move='.$move_FEN.';');
					fwrite($file,$movecount.'_Notation='.$notationvalue.PHP_EOL);
			endforeach;

			fclose($file);
			chmod($systemgameid, 0755);
		}
		return $exportedfen;
	}
	
	// Keeping this for debug reasons.
	function get_ascii_board(): string {
		//Echo '<li> ChessBoard.php 3# function get_ascii_board called </li>';
		$string = '';

		if ($this->Winners>=2) {
			$string .= "INVALID FEN. There cannot be two Winners";
			//return $string;
		}

		$string = '';

		if($this->Winner=='-1')
			$string .= "No Kings. Rajtantra Game";
		else
		if ($this->Winner=='0') {
			if ($this->color_to_move == ChessPiece::WHITE) {
				$string .= "White To Move";
			}elseif ($this->color_to_move == ChessPiece::BLACK) {
				$string .= "Black To Move";
			}
		}
		else
		if ($this->Winner=='1') {
			$string .= "White Won the Game";
		}
		else if ($this->Winner=='2') {
			$string .= "Black Won the Game";
		}
		else if ($this->Winner=='3') {
				$string .= "Both Agreed to Stop the Game. Draw";
		}

		for ( $rank = 9;$rank >= 0;$rank-- ) {
			$string .= "<br>";

			for ( $file = 0;$file <= 9;$file++ ) {
				$square = $this->board[$rank][$file];

				if ( ! $square ) {
					$string .= "*";
				}else {
					$string .= $this->board[$rank][$file]->get_unicode_symbol();
				}
			}
		}
		$string .= "<br><br>";
		return $string;
	}
	
	function get_graphical_board(): array {
		$graphical_board_array = array();
		for ( $rank = 9;$rank >= 0;$rank-- ) {
			for ( $file = 0;$file <= 9;$file++ ) {
				$piece = $this->board[$rank][$file];

				// NaagLok COLOR
				if ( (($rank==0) && ($file==0)) || (($rank==0) && ($file==9)) || (($rank==9) && ($file==0))||(($rank==9) && ($file==9)) ) {
					$graphical_board_array[$rank][$file]['square_color'] = 'naaglok';
				}
				else if ( (($rank==0) && ($file<9)) || (($rank==9) && ($file<9))) {
					// CASTLE COLOR
					if ( ($rank + $file) % 2 == 1 ) {
						$graphical_board_array[$rank][$file]['square_color'] = 'blackcastle';
					}else {
						$graphical_board_array[$rank][$file]['square_color'] = 'whitecastle';
					}
				}
				else if ( (($file==0) && ($rank<9)) || (($file==9) && ($rank<9))) {
					// Truce COLOR
					if ( ($rank + $file) % 2 == 1 ) {
						$graphical_board_array[$rank][$file]['square_color'] = 'blacktruce';
					}else {
						$graphical_board_array[$rank][$file]['square_color'] = 'whitetruce';
					}
				}
				else{
					// SQUARE COLOR
					if ( ($rank + $file) % 2 == 1 ) {
						$graphical_board_array[$rank][$file]['square_color'] = 'black';
					}else {
						$graphical_board_array[$rank][$file]['square_color'] = 'white';
					}
				}
				// ID
				$graphical_board_array[$rank][$file]['id'] = self::FILE_NUMS_AND_LETTERS[$file] . $rank;
				
				// PIECE
				if ( ! $piece ) {
					$graphical_board_array[$rank][$file]['piece'] = '';
					$graphical_board_array[$rank][$file]['name'] = '';

				}else {
					$graphical_board_array[$rank][$file]['piece'] = $this->board[$rank][$file]->get_unicode_symbol();
					$graphical_board_array[$rank][$file]['name'] = $this->board[$rank][$file]->get_fen_symbol();
				}
			}
		}
		return $graphical_board_array;
	}
	
	function get_side_to_move_string(): string {
		$string = '';
		if ($this->Winners>=2) {
			$string .= "INVALID FEN. There cannot be two Winners";
			return $string;
		}
	
		if($this->Winner=='-1')
			$string .= "No Kings. Rajtantra Game.";

		if (($this->Winner=='0') || ($this->Winner=='-1')) {
			if (($this->color_to_move == ChessPiece::WHITE) &&( ($this->controlled_piece=="") || ($this->controlled_piece==null) ||($this->controlled_piece=="()"))) {
				$string .= "White To Move";
			}
			else if (($this->color_to_move == ChessPiece::BLACK) && (($this->controlled_piece=="") || ($this->controlled_piece==null) ||($this->controlled_piece=="()"))) {
				$string .= "Black To Move";
			}
			else if (($this->controller_color == ChessPiece::WHITE) && ($this->controlled_color == ChessPiece::WHITE)) {
				$string .= "White Naarad is Controller. Move for Black";
			}
			else if (($this->controller_color == ChessPiece::BLACK) && ($this->controlled_color == ChessPiece::BLACK)) {
				$string .= "Black Naarad is Controller. Move for WHITE";
			}
			else if (($this->controller_color == ChessPiece::WHITE) && ($this->controlled_color == ChessPiece::BLACK)) {
				$string .= "White Naarad is Controller. Move for WHITE";
			}
			else if (($this->controller_color == ChessPiece::BLACK) && ($this->controlled_color == ChessPiece::WHITE)) {
				$string .= "Black Naarad is Controller. Move for Black";
			}
		}
		else
		if ($this->Winner=='1') {
				$string .= "White Won the Game";
		}
		else if ($this->Winner=='2') {
				$string .= "Black Won the Game";
		}
		else if ($this->Winner=='3') {
				$string .= "Both Agreed to Stop the Game. Draw";
		}
		
		return $string;
	}
	
	function get_who_is_winning_string(): string {
		$points = 0;		
		foreach ( $this->board as $value1 ) {
			foreach ( $value1 as $piece ) {
				if ( $piece ) {
					$points += $piece->get_value();
				}
			}
		}
		
		if ( $points > 0 ) {
			return "Material: White Ahead By $points";
		}elseif ( $points < 0 ) {
			$points *= -1;
			return "Material: Black Ahead By $points";
		}else {
			return "Material: Equal";
		}
	}
	
	function invert_rank_or_file_number(int $number): int {
		// 1 => 8 , // 2 => 7 // etc. 	
		return 9 - $number;
	}
	
	function number_to_file(int $number) {
		return self::FILE_NUMS_AND_LETTERS[$number];
	}
	
	// Note: This does not check for and reject illegal moves. It is up to code in the ChessGame class to generate a list of legal moves, then only make_move those moves.
	// In fact, sometimes make_move will be used on illegal moves (king in check moves), then the illegal moves will be deleted from the list of legal moves in a later step.
	function make_move(ChessSquare $old_square1, ChessSquare $new_square,bool $capture, bool $sameplace): int {
		$movetype=1;
		$sitting_piece = null;$is_capture=null;$pushedsquare=null;
		$moving_piece = null;
		$old_square = clone $old_square1;
		if( $this->board[$old_square->rank][$old_square->file])
			$moving_piece = clone $this->board[$old_square->rank][$old_square->file];

		if(($moving_piece==null) &&($old_square->mediatorrank!==null) &&($old_square->mediatorfile!==null) )
			{
				$moving_piece = clone $this->board[$old_square->mediatorrank][$old_square->mediatorfile];
				$old_square->rank =$old_square->mediatorrank;
				$old_square->file =$old_square->mediatorfile;
			}

		if($moving_piece!==null){
			/*if(($moving_piece->type==9))
				$ttt=1;*/
			if($this->board[$new_square->rank][$new_square->file]!==null) 
				$sitting_piece = clone $this->board[$new_square->rank][$new_square->file];
		
			$is_capture = $this->board[$new_square->rank][$new_square->file];
		
			/*if ( $moving_piece->type == ChessPiece::KNIGHT) {
			$ttt=1;
			}*/
			if ( $moving_piece->type == ChessPiece::PAWN || $is_capture ) {
				$this->halfmove_clock = 0;
			}else {
				$this->halfmove_clock++;
			}

			//if ($this->board[$new_square->rank][$new_square->file]!==null)
			if(($this->board[$new_square->rank][$new_square->file]!==null)&&(($this->board[$new_square->rank][$new_square->file]->selfpushedsquare!==null)))	
				$pushedsquare = $this->board[$new_square->rank][$new_square->file]->selfpushedsquare;

			if($moving_piece!==null)	
				$this->board[$new_square->rank][$new_square->file] =clone $moving_piece;
			else 
				$this->board[$new_square->rank][$new_square->file]= null;

			//if(($this->board[$new_square->rank][$new_square->file]->selfpushed == true) && ($pushedsquare )){
			if(($sitting_piece!==null) &&($capture==false) && ($sitting_piece->selfpushed != null) && ($sitting_piece->selfpushed == true) && ($pushedsquare )){
				$this->board[$new_square->rank][$new_square->file]->selfpushedsquare= $pushedsquare;
				$this->board[ $new_square->rank][ $new_square->file]->selfpushed=true;
				$this->board[ $new_square->rank][ $new_square->file]->selfpushersquare=array ("rank"=>$old_square->rank, "file"=>$old_square->file);
				$this->board[ $new_square->rank][ $new_square->file]->selfpusherpiece=clone $this->board[$old_square->rank][$old_square->file];
				if($sitting_piece!==null)
					$this->board[ $new_square->rank][ $new_square->file]->selfpushedpiece=clone $sitting_piece;
				$movetype=2;
			}
			else if(($sitting_piece!==null) &&($capture==true) && ($sitting_piece->selfpushed != null) && ($sitting_piece->selfpushed == true) && ($pushedsquare )){
				$this->board[$new_square->rank][$new_square->file]->selfpushedsquare= $pushedsquare;
				$this->board[ $new_square->rank][ $new_square->file]->selfpushed=false;
				$this->board[ $new_square->rank][ $new_square->file]->selfpushersquare=null;
				$this->board[ $new_square->rank][ $new_square->file]->selfpusherpiece=null;
				$this->board[ $new_square->rank][ $new_square->file]->selfpushedpiece=null;
				$movetype=1;
			}
			// Update $moving_piece->square too to avoid errors.

			$moving_piece->square = $new_square;
		}
		else 
			{
			}
			if($sameplace==FALSE)
				$this->board[$old_square->rank][$old_square->file] = NULL;
		
			if ( $this->color_to_move == ChessPiece::BLACK ) {
				$this->fullmove_number++;
			}
		
			$this->flip_color_to_move();
			return 	$movetype;
		}
	
	// Used to move the rook during castling.
	// Can't use make_move because it messes up color_to_move, halfmove, and fullmove.
	function make_additional_move_on_same_turn(ChessSquare $old_square, ChessSquare $new_square): void {
		$moving_piece = clone $this->board[$old_square->rank][$old_square->file];
		$this->board[$new_square->rank][$new_square->file] = $moving_piece;
		// Update $moving_piece->square too to avoid errors.
		$moving_piece->square = $new_square;
		$this->board[$old_square->rank][$old_square->file] = NULL;
	}
	
	function flip_color_to_move(): void {
		if ( $this->color_to_move == ChessPiece::WHITE ) {
			$this->color_to_move = ChessPiece::BLACK;
		}elseif ( $this->color_to_move == ChessPiece::BLACK ) {
			$this->color_to_move = ChessPiece::WHITE;
		}
	}
	
	function square_is_occupied(ChessSquare $square): bool {
		$rank = $square->rank;
		$file = $square->file;
		
		if ( $this->board[$rank][$file] ) {
			return TRUE;
		}else {
			return FALSE;
		}
	}
	
	function get_king_square($color): ?ChessSquare {
		foreach ( $this->board as $rowblock ) {
			foreach ( $rowblock as $piece ) {
				if ( $piece ) {
					if($piece->group=='ROYAL'){
						if ((($piece->type == ChessPiece::SIMPLEKING)||($piece->type == ChessPiece::VIKRAMADITYA)||($piece->type == ChessPiece::KING)||($piece->type == ChessPiece::INVERTEDKING)||($piece->type == ChessPiece::KING)||($piece->type == ChessPiece::INVERTEDKING)) && ($piece->color == $color )) {
							return $piece->square;
						}
					}
				}
			}
		}
		return NULL;
	}

	function get_spy_squares($color): ?array {
		$spysquares= [];
		
		foreach ( $this->board as $rowblock ) {
			foreach ( $rowblock as $piece ) {
				if ( $piece ) {
					if($piece->group=='SEMIROYAL'){
						if ($piece->color == $color ) {
							$spysquares []= $piece->square;
						}
					}
				}
			}
		}
		return $spysquares;
	}

	function get_free_spy_squares($color): ?bool {
			foreach ( $this->board as $rowblock ) {
				foreach ( $rowblock as $piece ) {
					if ( $piece ) {
						if($piece->group=='SEMIROYAL'){
							if ($piece->color == $color ) {
								if(((($piece->square->file==0)||($piece->square->file==9)) && (($piece->square->rank==4)||($piece->square->rank==5))) ||
								((($piece->square->rank==0)||($piece->square->rank==9)) && (($piece->square->file==4)||($piece->square->file==5))))
								{
									
								}
								else {return true;}
							}
						}
					}
				}
			}
			return false;
	}

	function get_general_square($color): ?ChessSquare {
		foreach ( $this->board as $rank ) {
			foreach ( $rank as $piece ) {
				if ( $piece ) {
					if (($piece->type == ChessPiece::GENERAL) && ($piece->color == $color )) {
						return $piece->square;
					}
				}
			}
		}
		return NULL;
	}

	function get_arthshastri_square($color): ?ChessSquare {
		foreach ( $this->board as $rowblock ) {
			foreach ( $rowblock as $piece ) {
				if ( $piece ) {
					if ((($piece->type == ChessPiece::ARTHSHASTRI)||($piece->type == ChessPiece::RAJYAPAALARTHSHASTRI)) && ($piece->color == $color )) {
						return $piece->square;
					}
				}
			}
		}
		return NULL;
	}

	//Border is not similar to Wall..
	function set_sleeping_Royals_SemiRoyals_commandars(){
			 $wksquare=$this->get_king_square(1);
			 $bksquare=$this->get_king_square(2);







			 
			 if( ($wksquare==null) || ((($wksquare->file==0) ||($wksquare->file==9)) && (($wksquare->rank==4) ||($wksquare->rank==5)))||
			 ((($wksquare->rank==0) ||($wksquare->rank==9)) && (($wksquare->file==4) ||($wksquare->file==5)))){
				$this->arewcommaderssleeping=true;
			 }

			 if( ($bksquare==null) || ((($bksquare->file==0) ||($bksquare->file==9)) && (($bksquare->rank==4) ||($bksquare->rank==5)))||
			 ((($bksquare->rank==0) ||($bksquare->rank==9)) && (($bksquare->file==4) ||($bksquare->file==5)))){
				$this->arebcommaderssleeping=true;
			 }

			 if(($this->arebcommaderssleeping==true) &&	($this->arebcommaderssleeping==true)) {return;}

			 $wasquare=$this->get_arthshastri_square(1);
			 $basquare=$this->get_arthshastri_square(2);

			 if( ($wasquare==null) || ((($wasquare->file==0) ||($wasquare->file==9)) && (($wasquare->rank==4) ||($wasquare->rank==5)))
			 ||
			 ((($wasquare->rank==0) ||($wasquare->rank==9)) && (($wasquare->file==4) ||($wasquare->file==5)))){
				$this->arewcommaderssleeping=true;
			 }
			 if( ($basquare==null) || ((($basquare->file==0) ||($basquare->file==9)) && (($basquare->rank==4) ||($basquare->rank==5)))||
			 ((($basquare->rank==0) ||($basquare->rank==9)) && (($basquare->file==4) ||($basquare->file==5)))){
				$this->arebcommaderssleeping=true;
			 }

			 if(($this->arebcommaderssleeping==true) &&	($this->arebcommaderssleeping==true)) {return;}

			
			$free_wspysquares= $this->get_free_spy_squares(1);
			$wspysquares= $this->get_free_spy_squares(1);
			$free_bspysquares= $this->get_free_spy_squares(2);
			$bspysquares= $this->get_free_spy_squares(2);

			if(($free_wspysquares==null) || ($free_wspysquares==false)||($wspysquares==null) || ($wspysquares==false)){
				$this->arewcommaderssleeping=true;
			}

			if(($free_bspysquares==null) || ($free_bspysquares==false)||($bspysquares==null) || ($bspysquares==false)){
				$this->arebcommaderssleeping=true;
			}

			$wgeneral_square= $this->get_general_square(1);
			$bgeneral_square= $this->get_general_square(2);


			if(($wgeneral_square==null) || ((($wgeneral_square->file==0) ||($wgeneral_square->file==9)) && (($wgeneral_square->rank==4) ||($wgeneral_square->rank==5))) ||
			((($wgeneral_square->rank==0) ||($wgeneral_square->rank==9)) && (($wgeneral_square->file==4) ||($wgeneral_square->file==5)))){
				$this->arewcommaderssleeping=true;
			}

			if(($bgeneral_square==null) || ((($bgeneral_square->file==0) ||($bgeneral_square->file==9)) && (($bgeneral_square->rank==4) ||($bgeneral_square->rank==5))) ||
			((($bgeneral_square->rank==0) ||($bgeneral_square->rank==9)) && (($bgeneral_square->file==4) ||($bgeneral_square->file==5)))){
				$this->arebcommaderssleeping=true;
			}
	}
	
	function get_naarad_square($color): ?ChessSquare {
		foreach ( $this->board as $rank ) {
			foreach ( $rank as $piece ) {
				if ( $piece ) {
					if (($piece->type == ChessPiece::GODMAN) && ($piece->color == $color )) {
						return $piece->square;
					}
				}
			}
		}
		return NULL;
	}

	function get_commonborderbreached($color): bool {
		$count=0;
		foreach ( $this->board as $RankData ) {
			if($count>=1) break;
			foreach ( $RankData as $piece ) {
				if ( $piece ) {
					//Generals are sitting on TRUCE Borders
					if (($piece->type != ChessPiece::GODMAN)&&(($piece->square->rank>=5)&&($piece->square->rank<=9))  && (($piece->color==1)) )  {
						$this->commonborderbreached=true;
						$count=$count+1; // or return
						break;
					}
	
					if (($piece->type != ChessPiece::GODMAN)&&(($piece->square->rank>=0)&&($piece->square->rank<=4))  && (($piece->color==2)) )  {
						$this->commonborderbreached=true;
						$count=$count+1;// or return
						break;
					}					
				}
			}
		}
		if($count>=1)
			return TRUE;
		return FALSE;
	}

	function get_generals_on_truce($color): bool {
		$count=0;
		foreach ( $this->board as $RankData ) {
			if($count==2) break;
			foreach ( $RankData as $piece ) {
				if ( $piece ) {
					//Generals are sitting on TRUCE Borders
					if (($piece->type == ChessPiece::GENERAL)&&(($piece->square->file==0)||($piece->square->file==9))){
							if(($piece->color==1)){
								$this->whitecanfullmoveinfoecastle = 0;//Can fullmove
								$this->whitecanfullmove = 0;//Can fullmove
								$count=$count+1;
								}
							if(($piece->color==2)){
								$this->blackcanfullmoveinfoecastle = 0;//Can fullmove
								$this->blackcanfullmove = 0;//Can fullmove
								$count=$count+1;
								}
							if($count==2) break;
					}
				}
			}
		}
		if($count>=1)
			return TRUE;
		return FALSE;
	}
	
	function get_royals_on_Scepters_TruceControl($color): bool {
		$count=0;//Classical Game does not allow killing if  Arthshastri or King are in Scepters.
		foreach ( $this->board as $RankData ) {
			////echo '<br/>';
			if($count==2) break;
			foreach ( $RankData as $piece ) {
				if ( $piece ) {
					/*Kings are sitting on Scepters or TRUCE Borders
					if (((($piece->type == ChessPiece::KING)||($piece->type == ChessPiece::KING))&&((($piece->square->file==4)||($piece->square->file==5))&&(($piece->square->rank==0)||($piece->square->rank==9))))||
					(($piece->type == ChessPiece::KING)&&((($piece->square->rank==4)||($piece->square->rank==5))&&(($piece->square->file==0)||($piece->square->file==9))))){
					*/if (((($piece->group == "ROYAL"))&&((($piece->square->file==4)||($piece->square->file==5))&&(($piece->square->rank==0)||($piece->square->rank==9))))||
						(($piece->group == "ROYAL")&&((($piece->square->rank==4)||($piece->square->rank==5))&&(($piece->square->file==0)||($piece->square->file==9))))){
								if(($piece->color==1)&& ($this->whitecankill == 1)){
								$this->whitecankill = 0;//Cannot kill
								$count=$count+1;
								}
							if(($piece->color==2)&& ($this->blackcankill == 1)){
									$this->blackcankill = 0;//Cannot kill
									$count=$count+1;
								}
							if($count==2) break;
					}
				}
			}
		}
		if($count>=1)
			return TRUE;
		return FALSE;
	}
	
	function get_royals_on_castle_for_full_move($color): bool { 
		$count=0;
		foreach ( $this->board as $RankData ) {
			foreach ( $RankData as $piece ) {
				if ( $piece ) {
					if (($piece->square->file>0)&&($piece->square->file<9)&&(($piece->square->rank==0)||($piece->square->rank<9))) {
						if ((($piece->group == 'ROYAL') || ($piece->group== 'SEMIROYAL'))) {
							/*
							if(($piece->square->rank==0)&&($piece->color==1)&& ($this->whitecanfullmoveinowncastle == 0)){
								$this->whitecanfullmoveinowncastle = 1;//White Can full move
								$count=$count+1;
								}
							if(($piece->square->rank==9)&&($piece->color==2)&& ($this->blackcanfullmoveinowncastle == 0)){
									$this->blackcanfullmoveinowncastle = 1;//Black Can full move
									$count=$count+1;
									}
							*/

							if(($piece->square->rank==9)&&($piece->color==1)&& ($this->whitecanfullmoveinfoecastle == 0)){
									$this->whitecanfullmoveinfoecastle = 1;//Can fullmove
									$count=$count+1;
								}
							if(($piece->square->rank==0)&&($piece->color==2)&& ($this->blackcanfullmoveinfoecastle == 0)){
									$this->blackcanfullmoveinfoecastle = 1;//Can fullmove
									$count=$count+1;
								}
							if($count==4) break;
						}
						elseif ((($piece->group == 'OFFICER') || ($piece->group== 'SOLDIER'))) {
							/*
							if(($piece->square->rank==0)&&($piece->color==1)&& ($this->whitecanfullmoveinowncastle == 0)){
								$this->whitecanfullmoveinowncastle = 1;//White Can full move
								$count=$count+1;
								}
							if(($piece->square->rank==9)&&($piece->color==2)&& ($this->blackcanfullmoveinowncastle == 0)){
									$this->blackcanfullmoveinowncastle = 1;//Black Can full move
									$count=$count+1;
								}
							*/

							if(($piece->square->rank==9)&&($piece->color==1)&& ($this->whitecanfullmoveinfoecastle == 0)){
									$this->whitecanfullmoveinfoecastle = 1;//Can fullmove
									$count=$count+1;
								}
							if(($piece->square->rank==0)&&($piece->color==2)&& ($this->blackcanfullmoveinfoecastle == 0)){
									$this->blackcanfullmoveinfoecastle = 1;//Can fullmove
									$count=$count+1;
								}
							if($count==4) break;
						}
					}
				}
			}
		}
		if($count>=1)
			return TRUE;
		if($count==0)
		return FALSE;
	}

	function get_general_on_castle_for_full_move($color): bool {
		$count=0;$color=1;
		foreach ( $this->board as $RankData ) {
			foreach ( $RankData as $piece ) {
				if ( $piece ) {
					if (($piece->square->file>0)&&($piece->square->file<9)&&(($piece->square->rank==0)||($piece->square->rank<9))) {
						if ((($piece->group == 'OFFICER') && ($piece->type== ChessPiece::GENERAL))) {
							/*
							if(($piece->square->rank==0)&&($piece->color==1)&& ($this->whitecanfullmoveinowncastle == 0)){
								$this->whitecanfullmoveinowncastle = 1;//White Can full move
								$count=$count+1;
								}
							if(($piece->square->rank==9)&&($piece->color==2)&& ($this->blackcanfullmoveinowncastle == 0)){
									$this->blackcanfullmoveinowncastle = 1;//Black Can full move
									$count=$count+1;
									}
							*/

							if(($piece->square->rank==9)&&($piece->color==1)&& ($this->whitecanfullmoveinfoecastle == 1)){
									$this->whitecanfullmoveinfoecastle = 1;//Can fullmove
									$count=$count+1;
								}
							elseif(($piece->square->rank==9)&&($piece->color==1)&& ($this->whitecanfullmoveinfoecastle == 0)){
									//cannot full move
									$count=$count+1;
								}
							if(($piece->square->rank==0)&&($piece->color==2)&& ($this->blackcanfullmoveinfoecastle == 1)){
									$this->blackcanfullmoveinfoecastle = 1;//Can fullmove
									$count=$count+1;
								}
							if(($piece->square->rank==0)&&($piece->color==2)&& ($this->blackcanfullmoveinfoecastle == 0)){
									//cannot full move
									$count=$count+1;
								}
							if($count==4) break;
						}
					}
				}
			}
		}
		if($count>=1)
			return TRUE;
		if($count==0)
		return FALSE;
	}

	function get_royals_on_warZone_for_full_move($color): bool {
		//$blackcanfullmove = 1; //$whitecanfullmove = 1;
		$count=0;

		foreach ( $this->board as $RankData ) {
			foreach ( $RankData as $piece ) {
				if ( $piece ) {
					if (($piece->square->file>0)&&($piece->square->file<9)&&($piece->square->rank>0)&&($piece->square->rank<9)) {
						if ((($piece->group == 'ROYAL') /*|| ($piece->group== 'SEMIROYAL')*/)) {
							if(($piece->color==1)&& ($this->whitecanfullmove == -1)){
								$this->whitecanfullmove = 1;//Can move fully
								$this->whitescanfullmove = 1;//S Can move fully
								$count=$count+1;
								}	
							else if(($piece->color==1)&& ($this->whitecanfullmove == 0)){
								$this->whitecanfullmove = -1;//Can move fully
								$this->whitescanfullmove = -1;//S Can move fully
								$count=$count+1;
								}
							else if(($piece->color==2)&& ($this->blackcanfullmove == -1)){
									$this->blackcanfullmove = 1;//Can move fully
									$this->blackcanfullmove = 1;//S Can move fully
									$count=$count+1;
									}						
							if(($piece->color==2)&& ($this->blackcanfullmove == 0)){
									$this->blackcanfullmove = -1;//Can move fully
									$this->blackscanfullmove = -1;//S Can move fully
									$count=$count+1;
								}
							if($count==2) break;
						}
					}
				}
			}
		}
		if($count>=1)
			return TRUE;
		if($count==0)
		return FALSE;
	}

	function get_general_on_warZone_for_full_move($movetype): bool {
		//$blackcanfullmove = 1; //$whitecanfullmove = 1;
		$count=0;
		$generalsquare=$this->wssquare;
		if(($generalsquare==null) &&($this->whitecanfullmove != 1) && ($movetype==1)) {$this->whitecanfullmove = 0;}

		$generalsquare=$this->bssquare;
		if(($generalsquare==null) && ($this->blackcanfullmove != 1 ) && ($movetype==1)){$this->blackcanfullmove = 0;}

		foreach ( $this->board as $RankData ) {
			foreach ( $RankData as $piece ) {
				if ( $piece ) {
					if (($piece->square->file>0)&&($piece->square->file<9)&&($piece->square->rank>0)&&($piece->square->rank<9)) {
						if ((($piece->group == 'OFFICER') && ($piece->type== ChessPiece::GENERAL))) {
							if(($piece->color==1) && ( $this->whitecanfullmove == 1) && ($movetype==1)){
								$this->whitecanfullmove = 1;//Can move fully
								$count=$count+1;
								}
							elseif(($piece->color==1) && ($movetype==0)){
									$this->whitecanfullmove = 1;//Can move fully
									$count=$count+1;
									}
							if(($piece->color==2)&& ($this->blackcanfullmove == 1)&& ($movetype==1) ){
									$this->blackcanfullmove = 1;//Can move fully
									$count=$count+1;
								}
							elseif(($piece->color==2)&& ($movetype==0)){
									$count=$count+1;
									$this->blackcanfullmove = 1;//Can move fully
								}
							if($count==2) break;
						}
					}
				}
			}
		}
		if($count>=1)
			return TRUE;
		if($count==0)
			return FALSE;
	}

	function get_royals_on_warZone($color): ?ChessSquare {
		foreach ( $this->board as $RankData ) {
			////echo '<br/>';
			foreach ( $RankData as $piece ) {
				if ( $piece ) {
					if (($piece->square->file>0)&&($piece->square->file<9)&&($piece->square->rank>0)&&($piece->square->rank<9)) {
						////echo ' Actual = '.$piece->type.' Expected = '.ChessPiece::KING;
						if ((($piece->type == ChessPiece::KING) || ($piece->type == ChessPiece::INVERTEDKING) ||($piece->type == ChessPiece::KING)||
						($piece->type == ChessPiece::INVERTEDKING)||($piece->type == ChessPiece::SPY)||($piece->type == ChessPiece::ARTHSHASTRI)||($piece->type == ChessPiece::ARTHSHASTRI)/*||
						($piece->type == ChessPiece::SPY)||($piece->type == ChessPiece::SPYWRAAJDAND)||($piece->type == ChessPiece::SPYWARTHDAND)||
						($piece->type == ChessPiece::KINGWARTHDAND)||($piece->type == ChessPiece::ARTHSHASTRIWRAAJDAND)||($piece->type == ChessPiece::INVERTEDKINGWARTHDAND)*/)
						&& ($piece->color == $color)) {
							return $piece->square;// Royal is in War Zone.. So, we can return
						}
					}
				}
			}
		}
		return NULL;
	}

	function get_compromised_castles() {
		$j=0;
		$castletocheck=1;
		
		for (;$castletocheck <= 2;$castletocheck++) {/**Loop through castle . Any side can enter castle*/
			if ($castletocheck==1) {
				$j=0;
				}
			elseif ($castletocheck==2) {
				$j=9;
				}
			for ($i = 1;$i <= 8;$i++) {/**Loop through castle . Any side can enter castle*/				

				if (!$this->board[$j][$i]) {
					continue;
				}

				if((($this->board[$j][$i]->group=='ROYAL') || ($this->board[$j][$i]->group=='SEMIROYAL') ||
				($this->board[$j][$i]->group=='NOBLE')) &&($this->board[$j][$i]->color!=$castletocheck)){//Compromised
					if($j==0)$this->wbrokencastle=true;
					if($j==9)$this->bbrokencastle=true;
				}

				//Opponent Army
				if(($this->board[$j][$i]->group!='ROYAL')&&($this->board[$j][$i]->group!='SEMIROYAL')&&
				($this->board[$j][$i]->group!='NOBLE')&&($this->board[$j][$i]->color!=$castletocheck)){//Compromised
					if($j==0)$this->wbrokencastle=true;
					if($j==9)$this->bbrokencastle=true;
				}
				else
					continue;
			}
		}
	}

	function remove_piece_from_square(ChessSquare $square): void {
		$rank = $square->rank;
		$file = $square->file;	
		$this->board[$rank][$file] = NULL;
	}
	
	function count_pieces_on_rank($type, int $rank, $color): int {
		$count = 0;
		for ( $i = 0;$i <= 9;$i++ ) {
			$piece = $this->board[$rank][$i];
			if ( $piece ) {
				if ( $piece->type == $type && $piece->color == $color ) {
					$count++;
				}
			}
		}		
		return $count;
	}
	
	function count_pieces_on_file($type, int $file, $color): int {
		$count = 0;
		for ( $i = 0;$i <= 9;$i++ ) {
			$piece = $this->board[$i][$file];
			
			if ( $piece ) {
				if ( $piece->type == $type && $piece->color == $color ) {
					$count++;
				}
			}
		}
		return $count;
	}
}