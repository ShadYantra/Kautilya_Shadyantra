<?php

if (! defined('blackboard')) {
    die("This file needs to be included into a viewer.");
}
$gamemode="move";

?>
				<table id="graphical_board" width="30%" height="90%">
					<tbody>
							<tr>
								<?php $style='gray'; $position="top";
									 echo '<td name="'.$position.'l" id ="xtopl" style="background-color:white;height:10px;width:10px;"></td>';
								for ( $i = 9; $i >= 0; $i-- ) {
								 if ($i==9) {
									$x=ord('y');
									$style='gray;height:10px;width:40px;font-size:10px';
								 }
								 if (($i>0) && ($i<9))
								 {
									$x=ord('a')+$i-1;
									$style='gray;height:10px;width:40px;font-size:10px';
								 }
								 if($i==0){
								  $x=ord('x');
									$style='gray;height:10px;width:40px;font-size:10px';
								 } 
									 echo '<td name="castle" id ="'.chr($x).$position.'" style="background-color:'.$style.'"> <span class="nondraggable_piece" draggable="false" >'.chr($x).'</span></td>';
								}
									 echo '<td name="'.$position.'r" id ="x'.$position.'r" style="font-size:10px;background-color:white;height:10px;width:10px"></td>';
								?>
							</tr>
							
						<?php $rownum=0; for ( $i = sizeof($graphical_board_array)-1; $i >= 0; $i-- ) {
 							 $row =  $graphical_board_array[$rownum] ?>
	
							<tr>
								 <?php  $position='l';
										echo '<td 	name="'.$position.$rownum.'" id ='.$position.$rownum.' class="truce" style="height:40px;width:40px;background-color:gray;font-size:10px">
										<span class="nondraggable" draggable="false">'.$rownum.'</span></td>';
										$colnum=0; for ( $j = sizeof($row)-1; $j >= 0; $j-- ){
										 	$column =  $row[$j] ;?>

										<td	name="warz" id ="<?php echo $column['id']; ?>"
											class="<?php echo $column['square_color']; if(($column['id']=='x4')||($column['id']=='x5')||($column['id']=='y4')||($column['id']=='y5')||($column['id']=='d0')||($column['id']=='e0')||($column['id']=='d9')||($column['id']=='e9')) {
                                                echo ' octagonWrap';
                                            }?>" style ="height:40px;width:40px" 
										>
										<span class="draggable_piece<?php if(($column['id']=='x4')||($column['id']=='x5')||($column['id']=='y4')||($column['id']=='y5')) { echo ' octagonT';} if(($column['id']=='d0')||($column['id']=='e0')||($column['id']=='d9')||($column['id']=='e9')){ echo ' octagonC';}?>" draggable="true" style ="display:inline-block;" name="<?php echo $column['name']; ?>"> <?php echo $column['piece']; ?>
										</span>
									</td>
								<?php 
									$colnum=0;};
									echo '<td 	name="r'.$rownum.'" id =r'.$rownum.' class="truce" style="background-color:gray;height:40px;width:40px;font-size:10px">
										<span class="nondraggable" draggable="false">'.$rownum.'</span></td>';
									?>
							</tr>
							
						<?php $rownum++; } ?>
						<tr>
								<?php $position="bot";
									 echo '<td name="x'.$position.'y" id ="y'.$position.'r" style="background-color:white;height:10px;width:40px;"></td>';
									for ( $i = 9; $i > 0; $i-- ) {
									$style='gray';
								 if ($i==9) {
									$x=ord('y');
									$style='gray;height:10px;width:40px;font-size:10px';
								 }
								 if (($i>0) && ($i<9))
								 {
									$x=ord('a')+$i-1;
									$style='gray;height:10px;width:40px;font-size:10px';
								 }
								 if($i==0){
								  $x=ord('x');
									$style='gray;height:10px;width:40px;font-size:10px';
								 }
									 echo '<td id ="'.chr($x).$position.'" style="background-color:'.$style.'"> <span class="nondraggable_piece" draggable="false" >'.chr($x).'</span></td>';
								}
								 echo '<td name="x'.$position.'r"  id ="x'.$position.'r" style="background-color:white;height:10px;width:40px;"></td>';
								
								?>
							</tr>
					</tbody>
				</table>