<?php

//if (! defined('VIEWER')) {
   // die("This file needs to be included into a viewer.");
//}
$gamemode="mover_details.php";

    if ( isset($_REQUEST['moverdata_request']) ) {
        //if proper variables are parsed then it is returned. else flush the game.
        echo 'if apple';
        //return json response and catch in jquery
    }
    else 
        echo 'else orange';

				if((file_exists("livegame.txt"))){
							$file = fopen("livegame.txt","r");

							while(!feof($file) && $matched<2) {
								$line = fgets($file);
			
								if (strpos($line, '$newfen=') !== false) {
									$gametype="new";
									}					
							   	else if (strpos($line, '$currentfen=') !== false) {
									$splitted = explode( '$currentfen=',$line);
									$fen = $splitted[1];
									$stringposition=strpos($fen,$exportedfen);
									if (strpos($exportedfen.PHP_EOL, $fen) !== false) {
										$matched=$matched+1; //New request
										$gametype="old";
									}
									else if ((strpos($line, 'Moved_FEN=Good')!==false)) {
										$matched=$matched+1; //New request
										$gametype="old";
									}									
								}
							   	else if (strpos($line, '$gameid=') !== false) {
									$splitted = explode( '$gameid=',$line);
									$gameid = $splitted[1];
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
							fclose($file);

							if(($gametype=="old"))  {
								$data = file('livegame.txt'); // reads an array of lines
			
								$reading = fopen('livegame.txt', 'r');
								$writing = fopen('livegame.tmp.txt', 'w');
			
								$replaced = false;
								$importedmatched=false;
								while (!feof($reading)&&($importedmatched==false)) {
									$line = fgets($reading);
								if(($matched==1)	&&(stristr($line,'$currentfen='))){
									$line = '$currentfen='.explode(';', explode( '$currentfen=',$line)[1])[0].PHP_EOL;
									$replaced=true;$importedmatched=true;}
								else if ((stristr($line,'$currentfen='))) {
									$replaced = true;
									$importedmatched=true;
						 	 	}
						  		fputs($writing, $line);
							}

							$movecount=0;
							foreach ( $legal_moves as $key => $move ): 	?>
								<option value="<?php $notationvalue=""; $ending_square=$move->ending_square; $movecount=$movecount+1;

								if($move->pushedending_square!=null)
									$ending_square=$move->pushedending_square;
								$move_FEN= $move->board->export_fen_moves($move->starting_square,$move->ending_square); 
								echo $move_FEN;
								$move->ending_square=$ending_square;
								$notationvalue=$move->get_notation();							
								fputs($writing, $movecount.'_Move='.$move_FEN.';'.$movecount.'_Notation='.$notationvalue.PHP_EOL); ?>"
								data-coordinate-notation="<?php echo  $notationvalue;?>" >
								<?php echo $notationvalue;							
							endforeach; 

							fclose($reading); fclose($writing);
							chmod("livegame.tmp.txt", 0755);

							// might as well not overwrite the file if we didn't replace anything
						if ($replaced)
							{
							  rename('livegame.tmp.txt', 'livegame.txt');
							} else {
							  unlink('livegame.tmp.txt');
							}
						}
					}
		
			/*	if($gametype=="new") {
					$file = fopen("livegame.txt","w");
					fwrite($file,'$gameid='.'livemove'.str_shuffle("acdefhijkmnprtuvwxyz0123456789").PHP_EOL);
					fwrite($file,'$currentfen='.$exportedfen.PHP_EOL);
					$movecount=0;
					foreach ( $legal_moves as $key => $move ):?> 
						<option value="<?php $notationvalue=""; $ending_square=$move->ending_square; $movecount=$movecount+1;

									if($move->pushedending_square!=null)
										$ending_square=$move->pushedending_square;
									$move_FEN= $move->board->export_fen_moves($move->starting_square,$move->ending_square); 
									echo $move_FEN;
									$move->ending_square=$ending_square;
									$notationvalue=$move->get_notation();							
									fwrite($file, $movecount.'_Move='.$move_FEN.';'); 
									fwrite($file,$movecount.'_Notation='.$notationvalue.PHP_EOL); ?>"
									data-coordinate-notation="<?php echo  $notationvalue;?>" >
									<?php echo $notationvalue;
								echo "</option>"; 
					endforeach;
					fclose($file);
					chmod("livegame.txt", 0755);
				}
                */