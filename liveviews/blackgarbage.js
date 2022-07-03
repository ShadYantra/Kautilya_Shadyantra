    <script> 
      const pieces = {
        K:"♔", Q:"♕", R:"♖", B:"♗", N:"♘", P:"♙", 
        k:"♚", q:"♛", r:"♜", b:"♝", n:"♞", p:"♟",
      }
	 function ord(str){return str.charCodeAt(0);}

	  function fentohtml() {
		htmldata=document.getElementById("hiddenfen").value.trim();
		//1rnbqkbnr1/1pppppppp1/181/181/181/181/1PPPPPPPP1/1RNBQKBNR1 w KQkq - 0 1
		
		var htmlgrid=htmldata.split("/",100);
		var tdata="";
		htmlgrid[htmlgrid.length-1]=htmlgrid[htmlgrid.length-1].split(" ")[0];
		console.log (htmlgrid);
		
		for (var i=0;i<=htmlgrid.length-1;i++){
			tdata=htmlgrid[i];
			//console.log (tdata);
			tdata1=tdata;
					for (var ij=0;ij<=tdata.length;ij++){
						n=tdata.charAt(ij);
						if(isNaN(n)==false)
							tdata1=tdata1.replace(n, " ".repeat(n));
					}
			htmlgrid[i]=tdata1;
		}
		
		var style='gray' , position="top";
	 
		var lrowxtop = '<td name="'+position+'l" id ="xtopl" style="background-color:white;height:10px;width:10px;"></td>';
		var lrowxtopldata="";
		for ( var i = 0; i <=htmlgrid[0].length-1; i++ ) {
				if (i==0) {
						x=ord('x');
						style='gray;height:10px;width:40px;font-size:10px';
						}
				tdata=htmlgrid[i];
				console.log(tdata.length);
				if ((i>0) && (i<tdata.length)){
						x=i+ord('a')-1;
						style='gray;height:10px;width:40px;font-size:10px';
					 }
				if(i==tdata.length){
						x=ord('y');
						style='gray;height:10px;width:40px;font-size:10px';
					}
								 
				chr_x=String.fromCharCode(x); 
				lrowxtopldata=lrowxtopldata+ '<td name="castle" id ="'+chr_x+position+'" style="background-color:'+style+'"> <span class="nondraggable_piece" draggable="false" >'+chr_x+'</span></td>';
			}
		lrowxtop= '<td name="'+position+'r" id ="y'+position+'r" style="font-size:10px;background-color:white;height:10px;width:10px"></td>';

		var lrow="<tr>"+lrowxtop+lrowxtopldata+ "</tr>";	
		var col=9, lrowxmiddle="",rowxmiddledata="",column_id="",square_color="";;
		var middlerows="",lrowxmiddledata="";

		for (var i=0;i<=htmlgrid.length-1;i++){
				position='l';	//console.log (tdata);
				lrowxmiddle= '<td name="'+position+col+'" id ='+position+col+' class="truce" style="height:40px;width:40px;background-color:gray;font-size:10px"><span class="nondraggable" draggable="false">'+col+'</span></td>';
				rowxmiddledata="";lrowxmiddledata="";
				tdata=htmlgrid[i];

				for (var ij=0;ij<=tdata.length;ij++){
						if (ij==0) {
									x=ord('x');
									if((col==0) || (col==9)) square_color="naaglok"
									else if(col%2==1) square_color="whitetruce"
									else if(col%2==2) square_color="blacktruce";
								 }

						if ((ij>0) && (ij<tdata.length))
								 {
									x=ij+ord('a')-1;
									if(col==0) {square_color="whitecastle";}
									else if (col==9) { square_color="blackcastle";}
									else if (col%2==1) { if((col%2)*(ij%2)==1) {square_color="black";} else {square_color="white";} } 
									else if (col%2==2) { if((col%2)*(ij%2)==1) {square_color="black";} else {square_color="white";} }						
								 }
						if(ij==tdata.length){ x=ord('y');}
								 
						chr_x=String.fromCharCode(x); 
						var colid=chr_x+''+col;
						var octagonWrap="",octagonWrapInner="";
						if((colid=='x4')||(colid=='x5')||(colid=='y4')||(colid=='y5')||(colid=='d0')||(colid=='e0')||(colid=='d9')||(colid=='e9')) { octagonWrap= ' octagonWrap';}
						if((colid=='x4')||(colid=='x5')||(colid=='y4')||(colid=='y5')) { octagonWrapInner= ' octagonT';} 
						if((colid=='d0')||(colid=='e0')||(colid=='d9')||(colid=='e9')){ octagonWrapInner= ' octagonC';}					

						lrowxmiddledata=lrowxmiddledata+'<td name="warz" id ="'+col+'" class="'+square_color+octagonWrap+ '" style ="height:40px;width:40px">'+ '<span class="draggable_piece'+ octagonWrapInner +'" draggable="true" style ="display:inline-block;" name="'+(tdata[ij] || "").trim()+'">'+ (tdata[ij] || "")+' </span></td>';
					}
				rowxmiddledata = lrowxmiddledata+ '<td name="r'+col+'" id =r'+col+' class="truce" style="background-color:gray;height:40px;width:40px;font-size:10px"><span class="nondraggable" draggable="false">'+col+'</span></td>';
				middlerows=middlerows+"<tr>"+lrowxmiddle+rowxmiddledata+ "</tr>";
				col=col-1;
			}
					
		position="bot";					
		var frowxbot = '<td name="'+position+'l" id ="xbotl" style="background-color:white;height:10px;width:10px;"></td>';
		var frowxbotldata="";
		for ( var i = 0;  i <=htmlgrid[9].length-1; i++ ) {
				if (i==0) {
						x=ord('x');
						style='gray;height:10px;width:40px;font-size:10px';
						}
				tdata=htmlgrid[i];
				console.log(tdata.length);

				if ((i>0) && (i<tdata.length)) {
						x=i+ord('a')-1;
						style='gray;height:10px;width:40px;font-size:10px';
					 }
				if(i==tdata.length){
						x=ord('y');
						style='gray;height:10px;width:40px;font-size:10px';
					}
								 
				chr_x=String.fromCharCode(x); 
				frowxbotldata=frowxbotldata+ '<td name="castle" id ="'+chr_x+position+'" style="background-color:'+style+'"> <span class="nondraggable_piece" draggable="false" >'+chr_x+'</span></td>';
			}
		frowxbot= '<td name="'+position+'r" id ="y'+position+'r" style="font-size:10px;background-color:white;height:10px;width:10px"></td>';
		var frow="<tr>"+frowxbot+frowxbotldata+ "</tr>";	
				
		var html = '"<table id="graphical_board" width="30%" height="90%" class=\"chess\"> <tbody>"' + lrow+middlerows+frow + "\n</tbody></table>";
        document.getElementById("out").innerHTML = html;			
      }
      fentohtml();
    </script>
