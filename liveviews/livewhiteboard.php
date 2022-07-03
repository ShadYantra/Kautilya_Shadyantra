<?php

if (! defined('livewhiteboard')) {
    die("This file needs to be included into a viewer.");
}
$gamemode="livemove";

?>
				<table id="graphical_board" width="30%" height="90%">
					<tbody>
							<tr>
								<?php $style='gray'; $position="top";
									 echo '<td name="'.$position.'l" id ="xtopl" style="background-color:white;height:10px;width:10px;"></td>';
								for ( $i = 0; $i <=9; $i++ ) {
								 if ($i==0) {
									$x=ord('x');
									$style='gray;height:10px;width:40px;font-size:10px';
								 }
								 if (($i>0) && ($i<9))
								 {
									$x=$i+ord('a')-1;
									$style='gray;height:10px;width:40px;font-size:10px';
								 }
								 if($i==9){
								  $x=ord('y');
									$style='gray;height:10px;width:40px;font-size:10px';
								 } 
									 echo '<td name="castle" id ="'.chr($x).$position.'" style="background-color:'.$style.'"> <span class="nondraggable_piece" draggable="false" >'.chr($x).'</span></td>';
								}
									 echo '<td name="'.$position.'r" id ="y'.$position.'r" style="font-size:10px;background-color:white;height:10px;width:10px"></td>';
								?>
							</tr>
	
						<?php $col=9;foreach ( $graphical_board_array as $rank => $row ): ?>
	
							<tr>
								 <?php  $position='l';
										echo '<td 	name="'.$position.$col.'" id ='.$position.$col.' class="truce" style="height:40px;width:40px;background-color:gray;font-size:10px">
										<span class="nondraggable" draggable="false">'.$col.'</span></td>';
										foreach ( $row as $file => $column ): ?>

										<td	name="warz" id ="<?php echo $column['id']; ?>"
											class="<?php echo $column['square_color']; if(($column['id']=='x4')||($column['id']=='x5')||($column['id']=='y4')||($column['id']=='y5')||($column['id']=='d0')||($column['id']=='e0')||($column['id']=='d9')||($column['id']=='e9')) {
                                                echo ' octagonWrap';
                                            }?>" style ="height:40px;width:40px" 
										>
										<span class="draggable_piece<?php if(($column['id']=='x4')||($column['id']=='x5')||($column['id']=='y4')||($column['id']=='y5')) { echo ' octagonT';} if(($column['id']=='d0')||($column['id']=='e0')||($column['id']=='d9')||($column['id']=='e9')){ echo ' octagonC';}?>" draggable="true" style ="display:inline-block;" name="<?php echo $column['name']; ?>"> <?php echo $column['piece']; ?>
										</span>
									</td>
								<?php endforeach;
									echo '<td 	name="r'.$col.'" id =r'.$col.' class="truce" style="background-color:gray;height:40px;width:40px;font-size:10px">
										<span class="nondraggable" draggable="false">'.$col.'</span></td>';
									?>
							</tr>
							
						<?php $col--; endforeach; ?>
						<tr>
								<?php $position="bot";
									 echo '<td name="x'.$position.'x" id ="x'.$position.'r" style="background-color:white;height:10px;width:40px;"></td>';
									for ( $i = 0; $i <=9; $i++ ) {
									$style='gray';
								 if ($i==0) {
									$x=ord('x');
									$style='gray;height:10px;width:40px;font-size:10px';
								 }
								 if (($i>0) && ($i<9))
								 {
									$x=$i+ord('a')-1;
									$style='gray;height:10px;width:40px;font-size:10px';
								 }
								 if($i==9){
								  $x=ord('y');
									$style='gray;height:10px;width:40px;font-size:10px';
								 }
									 echo '<td id ="'.chr($x).$position.'" style="background-color:'.$style.'"> <span class="nondraggable_piece" draggable="false" >'.chr($x).'</span></td>';
								}
								 echo '<td name="y'.$position.'r"  id ="y'.$position.'r" style="background-color:white;height:10px;width:40px;"></td>';
								
								?>
							</tr>
					</tbody>
				</table>