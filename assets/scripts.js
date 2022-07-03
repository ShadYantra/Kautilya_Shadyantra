////

var tabLinks = new Array();
var contentDivs = new Array();
var tabListItems =null;
var i = 0; var dt=null;
var radioState = "";

var category = null; var cookiecategory = null;
//if thereiks no selection then use White as default
function ord(str){return str.charCodeAt(0);}
var jsondata=null, boardfen="",blackcankill=null,whitecankill=null;
var whitecanfullmove=null, whitecanfullmove=null,boardtype =null,gamestatus=null, whitelist=null, BoardMoves= [];
var responsedata=null;var selectedgamerdoid="";var selectedgamerdoidvalue="";
var color_to_move='',opp_color_to_move='';
var p1name='',p2name='',mname="";
var piecemovetype="";
var kingmove=false,arthshastrimove=false,naaradmove=false,knightmove=false,bishopmove=false,rookmove=false,generalmove=false,officermove=false;

var myAudio = null;

var soldiermove=false;
var spymove=false;

var olelemnt;
var oname;
var optionn;
var lastsquare=null;
var lastcolor;
var history='';
var item = {}; var eventitem= {};
var oldsquare = null, newsquare = null, OriginalCell= null, TargetCell=null;
var coordinate_notation = '';
var option_tag_in_select_tag = null;
var movedfen="";
var lg = "",templg="",cog="";

var category = null;
var cookiecategory = null;
var pieceMoves= [];

function fillmoves(movesflag){
	pieceMoves=[];
	var formm = document.getElementById("all_moves");
	var isVisible = formm.style.display != 'none' && !formm.hasAttribute('hidden');

	if((movesflag==0)){ //set all containers as blank and disable the tag.. hidden.
			if (document.contains(document.getElementById("all_moves")) && (isVisible==true)) {
					document.getElementById("all_moves").style.display="none";;
				}
		}
	else if ((BoardMoves!=null) && (BoardMoves.length!=0)){
		if (document.contains(document.getElementById("all_moves"))) {
			if(isVisible==false)
					document.getElementById("all_moves").style.display="block";;
					$(commonAllMoves).find('option').remove().end();
				}
			var BoardKeyMoves = BoardMoves;
			var option = null;
			var data_coordinate_notation = null;
			var CMove = "No";

			var selectElement = document.getElementById(commonAllMoves);
			var i, L = selectElement.options.length - 1;
			for(i = L; i >= 0; i--) {
				   selectElement.remove(i);
				}
			selectElement.options.length = 0;
			for (var key in BoardKeyMoves)		{
					option = null;
					data_coordinate_notation = null;
					CMove = "No";
	 				if (BoardKeyMoves.hasOwnProperty(key))  {
							// here you have access to
							var option = BoardKeyMoves[key].option
							var data_coordinate_notation = BoardKeyMoves[key].data_coordinate_notation;
							var CMove = BoardKeyMoves[key].CMove;
							var opt = document.createElement('option');

							opt.value = BoardKeyMoves[key].option;
							opt.innerHTML = data_coordinate_notation;

							var att = document.createAttribute("cmove")
							att.value=CMove;       // Create a "class" attribute
		  
							opt.setAttributeNode(att);     
							att = document.createAttribute("pushed_value")
							att.value=data_coordinate_notation;   
							    // Create a "class" attribute
							opt.setAttributeNode(att);							
							att = document.createAttribute("data_coordinate_notation")
							att.value=data_coordinate_notation;       // Create a "class" attribute

							opt.setAttributeNode(att);
							selectElement.appendChild(opt);
						}

				}
		}
	}

function fetchdata(movedfen){
	//
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') 
						c = c.substring(1,c.length);
				if (c.indexOf(nameEQ) == 0){
						cookiecategory=c.substring(nameEQ.length,c.length);
						break;
					}
			}
		//	
		var cookiedtempdata=getfendata().trim();
		var lookformoves="";
		debugger
		if( ((cookiedtempdata==movedfen) && (movedfen!=null) && (movedfen!="")) || ((cookiedtempdata!=movedfen) && (movedfen==null))) {movedfen="";} 
		////console.log (" cookiecategory = "+ cookiecategory);
		if((movedfen==null)){ movedfen=getfendata();}
		
		if((movedfen==null)){
				lookformoves="lookformoves=yes";

				$.ajax({
						url: gameurl,
						type: 'post',
						data: lookformoves,
						async: false,
						success: function(responsedata){

							//parse the response data as per the json values;
							},
						complete:function(responsedata){
							//check the black vs white turn. If same turn then update the clock with correct value
							//in case of refresh page reload the counter etc
							// Perform operation on return value								
							jsondata = JSON.parse(responsedata);
							boardfen = jsondata.fen;
							blackcankill= jsondata.blackcankill;
							whitecankill= jsondata.whitecankill;
							blackcanfullmove = jsondata.blackcanfullmove;
							whitecanfullmove = jsondata.whitecanfullmove;
							boardtype = jsondata.boardtype;
							gamestatus= jsondata.gamestatus;
							whitelist = jsondata.whitelist;
							BoardMoves = jsondata.Moves;

							/*
							if((responsedata=="-1")){
								//load the 	window. enable the moves. delete the lookformoves tag.
								//console.log(responsedata+" "+window.location+" "+cookiecategory);
								//window.location.reload(true);
								//window.location=window.location;
							}					
							if((responsedata=="0")){
								//load the 	window. enable the moves. delete the lookformoves tag.
								//console.log(responsedata+" "+window.location+" "+cookiecategory);
								//window.location.reload(true);
								window.location=window.location;
							}
							else if((responsedata=="1") &&(cookiecategory=="white") ){
								//load the 	window. enable the moves. delete the lookformoves tag.
								//console.log(responsedata+" "+window.location+" "+cookiecategory);
								//window.location.reload(true);
								window.location=window.location;
							}
							else if((responsedata=="2") &&(cookiecategory=="black")){
								//console.log(responsedata+" "+window.location+" "+cookiecategory);		
								//window.location=window.location;
								window.location=window.location;
							}
							else if((responsedata=="100")){
								//console.log(responsedata+" "+window.location+" "+cookiecategory);		
								//window.location=window.location;
								window.location=window.location;
							}
							else
								{ //console.log(responsedata+" "+window.location+" "+cookiecategory);				
								setTimeout(fetchdata,1000);
								}
							*/	
							//alert (boardfen);
							if((boardfen!=null) && (boardfen!="")){
									document.getElementById("hiddenfen").value=boardfen;
									document.getElementById("hiddenfen").innerHTML=boardfen;
									document.getElementById("fen").value=boardfen;
									//alert(boardfen);
									fentohtml();
									updateoptions(1);
									updateCookieAction(boardfen);
									fillmoves(1);
									myAudio.play();
								}
							}
					});
			}
		else if (movedfen==""){
				movedfen=getfendata();
				document.getElementById("hiddenfen").value=movedfen;
				document.getElementById("hiddenfen").innerHTML=movedfen;
				document.getElementById("fen").value=movedfen;
				//alert(boardfen);
				/* fentohtml();
				fillmoves();
				updateoptions();
				//if tehere are no data pfresent in local cache then reload from server
				*/
				
				lookformoves="lookformoves="+movedfen;
				if((BoardMoves=="")||(BoardMoves==null)||(BoardMoves.length==0)){
				$.ajax({
					url: gameurl,
					type: 'post',
					data: lookformoves,
					async: false,
					success: function(responsedata){
						},
					complete: function(responsedata){
						
						////console.log (responsedata);
						jsondata = JSON.parse(responsedata.responseText);
						boardfen = jsondata.fen;
						blackcankill= jsondata.blackcankill;
						whitecankill= jsondata.whitecankill;
						blackcanfullmove = jsondata.blackcanfullmove;
						whitecanfullmove = jsondata.whitecanfullmove;
						boardtype = jsondata.boardtype;
						gamestatus= jsondata.gamestatus;
						whitelist = jsondata.whitelist;
						BoardMoves = jsondata.Moves;

						movedfen=jsondata.fen;
						document.getElementById("hiddenfen").value=movedfen;
						document.getElementById("hiddenfen").innerHTML=movedfen;
						document.getElementById("fen").value=movedfen;							
						updateCookieAction(boardfen);

						fentohtml();
						fillmoves(1);
						updateoptions(1);
						updateCookieAction(boardfen);
						myAudio.play();
					}
				});}
				else
				{	
					movedfen=getfendata();
					document.getElementById("hiddenfen").value=movedfen;
					document.getElementById("hiddenfen").innerHTML=movedfen;
					document.getElementById("fen").value=movedfen;					
					//fentohtml();
					updateoptions(1);
					updateCookieAction(boardfen);
					fillmoves(1);
					myAudio.play();

				}
			}
		else {
				lookformoves="lookformoves="+movedfen;
				//boardfen="";
				BoardMoves= null;
				//fentohtml();
				//updateoptions(0);
				//fillmoves( 0);
				
				$.ajax({
						url: gameurl,
						type: 'post',
						data: lookformoves,
						async: false,
						success: function(responsedata){
								//check the black vs white turn. If same turn then update the clock with correct value
								//in case of refresh page reload the counter etc
								// Perform operation on return value
								//parse the response data as per the json values;
							},
						complete:function(responsedata){
								jsondata = JSON.parse(responsedata.responseText);
								boardfen = jsondata.fen;
								blackcankill= jsondata.blackcankill;
								whitecankill= jsondata.whitecankill;
								blackcanfullmove = jsondata.blackcanfullmove;
								whitecanfullmove = jsondata.whitecanfullmove;
								boardtype = jsondata.boardtype;
								gamestatus= jsondata.gamestatus;
								whitelist = jsondata.whitelist;
								BoardMoves = jsondata.Moves;

								//alert (boardfen);
								if((boardfen!=null) && (boardfen!="")){
										document.getElementById("hiddenfen").value=boardfen;
										document.getElementById("hiddenfen").innerHTML=boardfen;
										document.getElementById("fen").value=boardfen;

										document.getElementById("blackcankill").value=blackcankill;
										document.getElementById("whitecankill").value=whitecankill;
										document.getElementById("blackcanfullmove").value=blackcanfullmove;
										document.getElementById("whitecanfullmove").value=whitecanfullmove;
					
										//alert(boardfen);
										fentohtml();
										updateoptions(1);
										updateCookieAction(boardfen);									
										fillmoves(1);
										myAudio.play();
									}
								}
					});

			}	
	}
//if thereiks no selection then use White as default

//add movestpe div	
function updateoptions(movesflag){
	var tempi=0;category=null;
	var formm = document.getElementById("all_moves");
	var isVisible = formm.style.display != 'none' && !formm.hasAttribute('hidden');

	if((movesflag==0)){ //set all containers as blank and disable the tag.. hidden.
			if (document.contains(document.getElementById("all_moves")) && (isVisible==true)) {
					document.getElementById("all_moves").style.display="none";;
				}
		}
	else if ((BoardMoves!=null) && (BoardMoves.length!=0)){
				if (document.contains(document.getElementById("all_moves"))) {
					if(isVisible==false)
							document.getElementById("all_moves").style.display="block";;
					$(commonAllMoves).find('option').remove().end();

				}
			/*if (document.contains(document.getElementById("movestypes"))) {
					var parent=document.getElementById("movestypes")
					while (parent.firstChild) {
							parent.removeChild(parent.firstChild);
						}		
				}
			*/	

			var all_moves_select = null;
			var all_movesoption = null;
			all_moves_select=document.getElementById(commonAllMoves);
			var piecename="",piecemove="",squarename="",	fen="",data_coordinate_notation="", pieceset=[];			

			for (var optionmoves=0;optionmoves<BoardMoves.length;optionmoves++){
					//console.log("***********************")
					data_coordinate_notation=BoardMoves[optionmoves].data_coordinate_notation;
					all_movesoption = document.createElement('option');
					all_movesoption.value = BoardMoves[optionmoves].option;all_movesoption.readOnly = false;
					all_movesoption.setAttribute("data_coordinate_notation",BoardMoves[optionmoves].data_coordinate_notation);
					all_movesoption.innerHTML=BoardMoves[optionmoves].data_coordinate_notation;
					all_moves_select.appendChild(all_movesoption);
					///update the piecemoves here

					if(data_coordinate_notation.substr(0,1)=='^') {
						piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
					else if(data_coordinate_notation.substr(0,1)=='*') { piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
					else if(data_coordinate_notation.substr(0,1)=='>') { piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
					else  { piecename=data_coordinate_notation.substr(0,1);squarename=data_coordinate_notation.substr(1,2); }
		 
					piecemove=data_coordinate_notation;
					fen=all_movesoption;
					pieceset["piecename"] = piecename;
					pieceset["squarename"] = squarename;
					pieceset["piecemove"] = piecemove;
					pieceset["fen"] = all_movesoption.value;;
		
					pieceMoves[squarename]=pieceset;
					pieceset=[];
				}

			//console.log (pieceMoves);	

			if(document.getElementById("hiddenfen").innerHTML.trim()!="") {	fentohtml(); }
			if( $('#Invert').length ) createCookieAction();
			//

				//add the event handler to add steps /
	}
	else {	$('form#winninggame').hide();$('form#King_endgame').hide();$('form#King_surrender').hide(); $('form#make_move').hide();	$('form#naarad_cmove').hide(); }
				
	if ($('select#winninggamemove option').length > 0) { $('form#winninggame').show(); 	}
	else { $('form#winninggame').hide(); }
	if ($('select#endgamemove option').length > 0) { $('form#King_endgame').show(); }	else { $('form#King_endgame').hide(); }
	if ($('select#surrendermove option').length > 0) { $('form#King_surrender').show();	}	else { $('form#King_surrender').hide(); }
	if ($('select#'+commonMove+' option').length > 0) { $('form#make_move').show(); }	else { $('form#make_move').hide(); }
	if ($('select#cmove option').length > 0) { $('form#naarad_cmove').show();	}	else { $('form#naarad_cmove').hide(); }	
	$("#move_count").html(tempi);
}
	
function simple(event){
	var i =0;var oop;	var tempi=0;
	p1name = $(this).attr('name');
	
	if(lastsquare!=null){
		lastsquare.css('background-color',lastcolor);
		lastsquare	=null; 	lastcolor =null;
	}	

	if(lastsquare==null){
		lastcolor=$(this).closest('td').css('background-color');
		$(this).closest('td').css('background-color', 'blue');
		lastsquare=$(this).closest('td');
	}

	$("#"+commonMove).empty();
	$("#"+commonAllMoves+" option").each(function() {
		////
		var val = $(this).val();
			var txt = $(this).html();
		var dataa = $(this).data('coordinate-notation');
		txt=txt.trim();
		if(txt.substr(0,1)==p1name){
			if ($( "#"+commonMove ).length) {
					$("#"+commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
			}
			tempi=tempi+1;
			}
		i=i+1;
		});
	$("#move_count").html(tempi);			
	}

function init() {

 for (var j = 0; j<=2;j++){
  // Grab the tab links and content divs from the page
  if (j == 0) tabListItems = document.getElementById('tabs').childNodes;
  if (j == 1) tabListItems = document.getElementById('tabs2').childNodes;
  if (j == 2) tabListItems = document.getElementById('Movestabs2').childNodes;
  for (i = 0; i < tabListItems.length; i++) {
    if (tabListItems[i].nodeName == "LI") {
      var tabLink = getFirstChildWithTagName(tabListItems[i], 'A');
      var id = getHash(tabLink.getAttribute('href'));
      tabLinks[id] = tabLink;
      contentDivs[id] = document.getElementById(id);
    }
  }
  
  i = 0;

  // Assign onclick events to the tab links, and
  // highlight the first tab

  for (var id in tabLinks) {
    tabLinks[id].onclick = showTab;
    tabLinks[id].onfocus = function() {
      this.blur()
    };
    if (i == 0) tabLinks[id].className = 'selected';
    i++;
  }

  // Hide all content divs except the first
  i = 0;

  for (var id in contentDivs) {
    if (i != 0) contentDivs[id].className = 'tabContent hide';
    i++;
  }
}

}

function showTab() {
  var selectedId = getHash(this.getAttribute('href'));

  // Highlight the selected tab, and dim all others.
  // Also show the selected content div, and hide all others.
  for (var id in contentDivs) {
    if (id == selectedId) {
      tabLinks[id].className = 'selected';
      contentDivs[id].className = 'tabContent';
    } else {
      tabLinks[id].className = '';
      contentDivs[id].className = 'tabContent hide';
    }
  }

  // Stop the browser following the link
  return false;
}

function getFirstChildWithTagName(element, tagName) {
  for (var i = 0; i < element.childNodes.length; i++) {
    if (element.childNodes[i].nodeName == tagName) return element.childNodes[i];
  }
}

function getHash(url) {
  var hashPos = url.lastIndexOf('#');
  return url.substring(hashPos + 1);
}

$('#show').on('click', function () {
    $('.center').show();
    $(this).hide();
});

$('#close').on('click', function () {
    $('.center').hide();
    $('#show').show();
});


//$('select#move').change(function(){
		var category = null;
	var cookiecategory = null;


	function getfendata(){

		var cookiefen="";
		var ca = document.cookie.split(';');
		var splitagain=null;
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') 
				c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0){
				//
				cookiecategory=c.substring(nameEQ.length,c.length);
				if(cookiecategory!=null) {cookiecategory=cookiecategory.split('_fen_')[0];}
				break;
			}
				////console.log (c.substring(nameEQ.length,c.length));
		}
		//
		//console.log (cookiecategory);
		var tempcookiefen="13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1";
		cookiefen = document.cookie.split('_fen_');
	
		if((cookiefen!="") && (cookiefen!=null)) { splitagain=cookiefen[1].split('_fen_');}
	
		if(splitagain!=null) {cookiefen = "_fen_"+splitagain[0];tempcookiefen=splitagain[0];}
		else if(cookiefen==null){cookiefen="_fen_"+"13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1";}
		else if ( (cookiefen[1]==null)|| (cookiefen[1]=="") ) {cookiefen="_fen_"+"13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1";}
		else {tempcookiefen = cookiefen[1];cookiefen=null;cookiefen="_fen_"+tempcookiefen;tempcookiefen="";}
		
		//console.log (tempcookiefen);
	
		if((document.getElementById("hiddenfen").value!="") || (tempcookiefen!=""))
			movedfen=tempcookiefen;
			return movedfen;
	}

/*	
	$('select#move').change(function(){
		//
		var $empty=$('select#move').filter(function() { return this.value == ""; });
		if ( $('select#move').filter(function() { return this.value == ""; }).length == $('select#move').length ){
				$('#make_move #submitmove').attr('disabled','disabled');
				$('select#move').css("background-color", "");
		 
			} 
		else {
				$('#submitmove').removeAttr('disabled');
				$('#submitmove').removeAttr('hidden');
	
				$('select#move').css("background-color", "yellow");
				$('select#endgamemove').css("background-color", "");
				$('select#surrendermove').css("background-color", "");
				$('select#winninggamemove').css("background-color", "");
				$('select#cmove').css("background-color", "");

				$('#submitendgamemove').attr('disabled','disabled');
				$('#submitendgamemove').attr('hidden','hidden');
				$('#submitsurrendermove').attr('disabled','disabled');
				$('#submitsurrendermove').attr('hidden','hidden'); 
				$('#submitwinninggamemove').attr('disabled','disabled');
				$('#submitwinninggamemove').attr('hidden','hidden'); 
				$('#submitcmove').attr('disabled','disabled');
				$('#submitcmove').attr('hidden','hidden');			
			}
	});

$('select#winninggamemove').change(function(){
		var $empty=$('select#winninggamemove').filter(function() { return this.value == ""; });
		if ( $('select#winninggamemove').filter(function() { return this.value == ""; }).length == $('select#winninggamemove').length ){
				$('#submitendgamemove').attr('disabled','disabled');
			} 
		else
			{
				$('#submitwinninggamemove').removeAttr('disabled');
				$('#submitwinninggamemove').removeAttr('hidden');
		 
				$('#submitmove').attr('disabled','disabled'); 
				$('#submitmove').attr('hidden','hidden');
				$('#submitsurrendermove').attr('disabled','disabled');
				$('#submitsurrendermove').attr('hidden','hidden'); 
				$('#submitendgamemove').attr('disabled','disabled');
				$('#submitendgamemove').attr('hidden','hidden');
				$('#submitcmove').attr('disabled','disabled');
				$('#submitcmove').attr('hidden','hidden');

				$('select#move').css("background-color", "");
				$('select#endgamemove').css("background-color", "");
				$('select#surrendermove').css("background-color", "");
				$('select#winninggamemove').css("background-color", "yellow");
				$('select#cmove').css("background-color", "");
			}
	});

$('select#endgamemove').change(function(){
  var $empty=$('select#endgamemove').filter(function() { return this.value == ""; });
if ( $('select#endgamemove').filter(function() { return this.value == ""; }).length == $('select#endgamemove').length ){
		$('#submitendgamemove').attr('disabled','disabled');
		$('select#endgamemove').css("background-color", "");

} else
{
	$('#submitendgamemove').removeAttr('disabled');
	$('#submitendgamemove').removeAttr('hidden');
	$('#submitmove').attr('disabled','disabled'); 
	$('#submitmove').attr('hidden','hidden');
	$('#submitsurrendermove').attr('disabled','disabled');
	$('#submitsurrendermove').attr('hidden','hidden'); 
	$('#submitwinninggamemove').attr('disabled','disabled');
	$('#submitwinninggamemove').attr('hidden','hidden'); 
	$('#submitcmove').attr('disabled','disabled');
	$('#submitcmove').attr('hidden','hidden');

	$('select#move').css("background-color", "");
	$('select#endgamemove').css("background-color", "yellow");
	$('select#surrendermove').css("background-color", "");
	$('select#winninggamemove').css("background-color", "");
	$('select#cmove').css("background-color", "");
}
});

$('select#surrendermove').change(function(){
var $empty=$('select#surrendermove').filter(function() { return this.value == ""; });
if ( $('select#surrendermove').filter(function() { return this.value == ""; }).length == $('select#surrendermove').length ){
		$('#submitsurrendermove').attr('disabled','disabled');
		 $('select#surrendermove').css("background-color", "");

} 
else
{
	$('#submitsurrendermove').removeAttr('disabled');
	$('#submitsurrendermove').removeAttr('hidden');
		 
	$('#submitmove').attr('disabled','disabled'); 
	$('#submitmove').attr('hidden','hidden');
	$('#submitendgamemove').attr('disabled','disabled');
	$('#submitendgamemove').attr('hidden','hidden'); 
	$('#submitwinninggamemove').attr('disabled','disabled');
	$('#submitwinninggamemove').attr('hidden','hidden');
	$('#submitcmove').attr('disabled','disabled');
	$('#submitcmove').attr('hidden','hidden');		

	$('select#move').css("background-color", "");
	$('select#endgamemove').css("background-color", "");
	$('select#surrendermove').css("background-color", "yellow");
	$('select#winninggamemove').css("background-color", "");
	$('select#cmove').css("background-color", "");
}
});

$('select#winninggamemove').change(function(){
		var $empty=$('select#winninggamemove').filter(function() { return this.value == ""; });
		if ( $('select#winninggamemove').filter(function() { return this.value == ""; }).length == $('select#winninggamemove').length ){
				$('#submitwinninggamemove').attr('disabled','disabled');
			} 
		else
			{
				$('#submitwinninggamemove').removeAttr('disabled');
				$('#submitwinninggamemove').removeAttr('hidden');
				$('#submitmove').attr('disabled','disabled');
				$('#submitmove').attr('hidden','hidden');
				$('#submitendgamemove').attr('disabled','disabled');
				$('#submitendgamemove').attr('hidden','hidden');
				$('#submitwinninggamemove').attr('disabled','disabled');
				$('#submitwinninggamemove').attr('hidden','hidden');
				$('#submitcmove').attr('disabled','disabled');
				$('#submitcmove').attr('hidden','hidden');
			}
	});
*/
$('#perft').click(function(){
	window.location.href = 'perft.php?fen='  + $('#fen').val();
});


	function createCookieAction() {
		var cookiefen="";
		var ca = document.cookie.split(';');
		var splitagain=null;
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') 
				c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0){
				//
				cookiecategory=c.substring(nameEQ.length,c.length);
				if(cookiecategory!=null) {cookiecategory=cookiecategory.split('_fen_')[0];}
				break;
			}
				////console.log (c.substring(nameEQ.length,c.length));
		}

		//
		//console.log (cookiecategory);
	
		cookiefen = document.cookie.split('_fen_');
	
		if((cookiefen!="") && (cookiefen!=null)) { splitagain=cookiefen[1].split('_fen_');}
	
		if(splitagain!=null) {cookiefen = "_fen_"+splitagain[0];}
		else if(cookiefen==null){cookiefen="_fen_"+"13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1";}
		else if ( (cookiefen[1]==null)|| (cookiefen[1]=="") ) {cookiefen="_fen_"+"13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1";}
		else {var tempcookiefen = cookiefen[1];cookiefen=null;cookiefen="_fen_"+tempcookiefen;tempcookiefen="";}
		cookiecategory
		//console.log (cookiefen);
	
		document.getElementById("hiddenfen").value=	cookiefen.split('_fen_')[1];
		document.getElementById("hiddenfen").innerHTML=cookiefen.split('_fen_')[1];
		document.getElementById("fen").value=cookiefen.split('_fen_')[1];
		//
		var buttonvalue = document.getElementById("Invert").name;
		if((buttonvalue==null) ||(buttonvalue=="")){
			if((cookiecategory==null) ||(cookiecategory=="")){
				document.getElementById("Invert").name="white"
				category="white";
				buttonvalue="white";
			}
			else if((category==null) ||(category=="")){
				document.getElementById("Invert").name=cookiecategory;
				category=cookiecategory;
			}
			else {
				document.getElementById("Invert").name=cookiecategory;
				category=cookiecategory;
			}
		}
		else if((category==null) ||(category=="")){
			if((cookiecategory==null) ||(cookiecategory=="")){
				document.getElementById("Invert").name="white"
				category="white";
				buttonvalue="white";
			}
			else if((buttonvalue==null) ||(buttonvalue=="")){
				document.getElementById("Invert").name=cookiecategory;
				category=cookiecategory;
			}
			else {
				document.getElementById("Invert").name=cookiecategory;
				category=cookiecategory;
			}
		}
		else if((cookiecategory==null) ||(cookiecategory=="")){
			if((category==null) ||(category=="")){
				document.getElementById("Invert").name="white"
				category="white";
				buttonvalue="white";
			}
			else if((buttonvalue==null) ||(buttonvalue=="")){
				document.getElementById("Invert").name=category;
			}
			else {
				document.getElementById("Invert").name=buttonvalue;		
				category=buttonvalue;
			}
		}
	
		//console.log (buttonvalue);
	
		var date = new Date();
		date.setTime(date.getTime()+(1*24*60*60*1000));
		var expires = "; expires="+date.toUTCString();
		//console.log ("   ^^^^^^^^^^^^^^^^^^  ");
	
		var date = new Date();
		date.setTime(date.getTime()+(1*24*60*60*1000));
		var expires = "; expires="+date.toUTCString();
		$("input[name='whiterdo']").attr("checked",false);
		$("input[name='blackrdo']").attr("checked",true);
		//console.log ("   -----------------------  ");
		
		document.cookie = nameEQ + " ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
		document.cookie = nameEQ + category+cookiefen+expires+"; path=/"+";";
	}
	
/*
function swap( cells, x, y ){
	//if( x != y ){     
  
	var $cell1 = cells[x][y];
	var $cell2 = cells[11-x][11-y];
	$cell1.replaceWith( $cell2.clone() );
	$cell2.replaceWith( $cell1.clone() );
	 //}
 }
*/
/*
function reversegrid(){
	 var cells = [];
	 $('table').find('tr').each(function(){
		 var row = [];
		 $(this).find('td').each(function(){
			row.push( $(this) );    
		 });
		 cells.push( row );
	 });
	 
	 for( var y = 0; y <= cells.length/2; y++ ){
		 for( var x = 0; x < cells[y].length; x++ ){
			 swap( cells, x, y );
		 }   
	 }

}
*/

function fentohtml() {
	var htmldata=document.getElementById("fen").value.trim();
	//1rnbqkbnr1/1pppppppp1/181/181/181/181/1PPPPPPPP1/1RNBQKBNR1 w KQkq - 0 1
	
	var htmlgrid=htmldata.split("/",100);
	var tdata="";
	var mover=htmlgrid[htmlgrid.length-1].split(" ")[1];
	mover=mover.split(" ")[0];
	htmlgrid[htmlgrid.length-1]=htmlgrid[htmlgrid.length-1].split(" ")[0];
	var boardtype=document.getElementById("boardtype");
	id="";
	////console.log (htmlgrid);
	var movername="white";
	var moverid="1";
	var moverstatus="white";
  
	if(mover=="w0"){
		movername="white";
		moverid="1";
		moverstatus="White To Move";
	}
	else if(mover=="b0"){
		movername="black";
		moverid="2";
		moverstatus="Black To Move";
	}
	
	document.getElementsByName("board_mover")[0].innerHTML = moverstatus;
	document.getElementsByName("board_mover")[0].id = moverid; 
	for (var i=0;i<=htmlgrid.length-1;i++){
		tdata=htmlgrid[i];
		////console.log (tdata);
		tdata1=tdata;
				for (var row=0;row<=tdata.length;row++){
					n=tdata.charAt(row);
					if(isNaN(n)==false)
						tdata1=tdata1.replace(n, " ".repeat(n));
				}
		htmlgrid[i]=tdata1;
	}
	
	var style='gray' , position="top";
	var boardtype=document.getElementById("Invert").name;  

	if((boardtype=="")||(boardtype=="white")){ 
		var lrowxtop = "";
		  var rowdata="";var lrow="<tr>"+ '<td name="'+position+'l" id ="xtopl" style="background-color:white;height:10px;width:10px;"></td>';
		  for ( var i = 0; i <=htmlgrid[0].length-1; i++ ) {
				  if (i==0) {
					  x=ord('x'); style='gray;height:10px;width:40px;font-size:10px';
				  }
					tdata=htmlgrid[i];
					////console.log(tdata.length);
					if ((i>0) && (i<tdata.length-1)){
					  x=i+ord('a')-1; style='gray;height:10px;width:40px;font-size:10px';
				  }
					if(i==tdata.length-1){
					  x=ord('y'); style='gray;height:10px;width:40px;font-size:10px';
				  }
							 
					chr_x=String.fromCharCode(x); 
					rowdata=rowdata+ '<td name="castle" id ="'+chr_x+position+'" style="background-color:'+style+'"> <span class="nondraggable_piece" draggable="false" >'+chr_x+'</span></td>';
				}
			lrowxtop= '<td name="'+position+'r" id ="y'+position+'r" style="font-size:10px;background-color:white;height:10px;width:10px"></td>';
  
			var lrow=lrow+rowdata+ lrowxtop+"</tr>";	
			var col=9, lrowxmiddle="",rowdata="",column_id="",square_color="";;
			var middlerows="";middlerowdata="";
			var row=0;var mod=0;
			for (var i=0;i<=htmlgrid.length-1;i++){
				  position='l';	////console.log (tdata);
				  rowdata="";middlerowdata="";
				  tdata=htmlgrid[i];row=0;
				  if((col%2)==1) { mod=1;} else{mod=0;}	  
  
				  for (;row<=tdata.length-1;row++){
  
						  if ((row==0) && (((col==0) || (col==9)))) {
								x=ord('x');
							  square_color="naaglok";
							  }
						  else if((row==9) && ((col==0) || (col==9))) { 
							  x=ord('y');
							  square_color="naaglok";
							  }
						  else if ((row==0) || (row==9) && (((col>1) && (col<9))))  {
							  x=row+ord('a')-1;
							  square_color="whitetruce";
							  }
						  else if ((row>=1) && (row<9) && (((col==0) || (col==9))))  {
							  x=row+ord('a')-1;
							  square_color="blackcastle";
							  }			 					  
						  else if ((row>=0) && (row<=9) && (((col>=0) && (col<=9))))  {
								x=row+ord('a')-1;
								  mod=Math.abs(1-mod);
								if (mod==1){square_color="black";} else {square_color="white";} 
							  }
  
						  if ((row==0) || (row==9) && (((col>=1) && (col<=8))))  {
							  if (row==0) { x=ord('x') } else if (row==9){ x=ord('y')};
								}
  
						  chr_x=String.fromCharCode(x); 
							var colid=chr_x+''+col;
						   var octagonWrap="",octagonWrapInner="";
						   if((colid=='x4')||(colid=='x5')||(colid=='y4')||(colid=='y5')||(colid=='d0')||(colid=='e0')||(colid=='d9')||(colid=='e9')) { octagonWrap= ' octagonWrap';}
						  if((colid=='x4')||(colid=='x5')||(colid=='y4')||(colid=='y5')) { octagonWrapInner= ' octagonT';} 
						  if((colid=='d0')||(colid=='e0')||(colid=='d9')||(colid=='e9')){ octagonWrapInner= ' octagonC';}					
  
							middlerowdata=middlerowdata+'<td name="warz" id ="'+colid+'" class="'+square_color+octagonWrap+ '" style ="height:40px;width:40px">'+ '<span class="draggable_piece'+ octagonWrapInner +'" draggable="true" style ="display:inline-block;" name="'+(tdata[row] || "").trim()+'">'+ (tdata[row] || "")+' </span></td>';
						}
					rowdata = middlerowdata+ '<td name="r'+col+'" id =r'+col+' class="truce" style="background-color:gray;height:40px;width:40px;font-size:10px"><span class="nondraggable" draggable="false">'+col+'</span></td>';
					middlerows=middlerows+"<tr>"+'<td name="'+position+col+'" id ='+position+col+' class="truce" style="height:40px;width:40px;background-color:gray;font-size:10px"><span class="nondraggable" draggable="false">'+col+'</span></td>'+rowdata;
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
				  tdata=htmlgrid[i]; ////console.log(tdata.length);
  
				  if ((i>0) && (i<tdata.length-1)) {
						  x=i+ord('a')-1;
						  style='gray;height:10px;width:40px;font-size:10px';
						 }
					else if(i==tdata.length-1){
						  x=ord('y');
						  style='gray;height:10px;width:40px;font-size:10px';
						}						   
				  chr_x=String.fromCharCode(x); 
					frowxbotldata=frowxbotldata+ '<td name="castle" id ="'+chr_x+position+'" style="background-color:'+style+'"> <span class="nondraggable_piece" draggable="false" >'+chr_x+'</span></td>';
				}
			frowxbot= '<td name="'+position+'r" id ="y'+position+'r" style="font-size:10px;background-color:white;height:10px;width:10px"></td>';
			var frow="<tr>"+frowxbot+frowxbotldata+ "</tr>";
		  }
	else if((boardtype=="black")){ 
			var lrowxtop = "";
			  var rowdata="";var lrow="<tr>"+ '<td name="'+position+'l" id ="xtopl" style="background-color:white;height:10px;width:10px;"></td>';
			  for ( var i = 9; i >=0; i-- ) {
					  if (i==0) {
						  x=ord('x'); style='gray;height:10px;width:40px;font-size:10px';
					  }
						tdata=htmlgrid[i];
						////console.log(tdata.length);
						if ((i>0) && (i<9)){
						  x=ord('a')+i-1; style='gray;height:10px;width:40px;font-size:10px';
					  }
						if(i==9){
						  x=ord('y'); style='gray;height:10px;width:40px;font-size:10px';
					  }
								 
						chr_x=String.fromCharCode(x); 
						rowdata=rowdata+ '<td name="castle" id ="'+chr_x+position+'" style="background-color:'+style+'"> <span class="nondraggable_piece" draggable="false" >'+chr_x+'</span></td>';
					}
				lrowxtop= '<td name="'+position+'r" id ="y'+position+'r" style="font-size:10px;background-color:white;height:10px;width:10px"></td>';
	  
				var lrow=lrow+rowdata+ lrowxtop+"</tr>";	
				var col=0, lrowxmiddle="",rowdata="",column_id="",square_color="";;
				var middlerows="";middlerowdata="";
				var row=9;var mod=0;
				for (var i=9;i>=0;i--){
					  position='l';	////console.log (tdata);
					  rowdata="";middlerowdata="";
					  tdata=htmlgrid[i];row=9;
					  if((col%2)==1) { mod=1;} else{mod=0;}	  
	  
					  for (;row>=0;row--){
	  
							  if ((row==0) && (((col==0) || (col==9)))) {
									x=ord('x');
								  square_color="naaglok";
								  }
							  else if((row==9) && ((col==0) || (col==9))) { 
								  x=ord('y');
								  square_color="naaglok";
								  }
							  else if ((row==0) || (row==9) && (((col>1) && (col<9))))  {
								  x=row+ord('a')-1;
								  square_color="whitetruce";
								  }
							  else if ((row>=1) && (row<9) && (((col==0) || (col==9))))  {
								  x=row+ord('a')-1;
								  square_color="blackcastle";
								  }			 					  
							  else if ((row>=0) && (row<=9) && (((col>=0) && (col<=9))))  {
									x=row+ord('a')-1;
									  mod=Math.abs(1-mod);
									if (mod==1){square_color="black";} else {square_color="white";} 
								  }
	  
							  if ((row==0) || (row==9) && (((col>=1) && (col<=8))))  {
								  if (row==0) { x=ord('x') } else if (row==9){ x=ord('y')};
									}
	  
							  chr_x=String.fromCharCode(x); 
								var colid=chr_x+''+col;
							   var octagonWrap="",octagonWrapInner="";
							   if((colid=='x4')||(colid=='x5')||(colid=='y4')||(colid=='y5')||(colid=='d0')||(colid=='e0')||(colid=='d9')||(colid=='e9')) { octagonWrap= ' octagonWrap';}
							  if((colid=='x4')||(colid=='x5')||(colid=='y4')||(colid=='y5')) { octagonWrapInner= ' octagonT';} 
							  if((colid=='d0')||(colid=='e0')||(colid=='d9')||(colid=='e9')){ octagonWrapInner= ' octagonC';}					
	  
								middlerowdata=middlerowdata+'<td name="warz" id ="'+colid+'" class="'+square_color+octagonWrap+ '" style ="height:40px;width:40px">'+ '<span class="draggable_piece'+ octagonWrapInner +'" draggable="true" style ="display:inline-block;" name="'+(tdata[row] || "").trim()+'">'+ (tdata[row] || "")+' </span></td>';
							}
						rowdata = middlerowdata+ '<td name="r'+col+'" id =r'+col+' class="truce" style="background-color:gray;height:40px;width:40px;font-size:10px"><span class="nondraggable" draggable="false">'+col+'</span></td>';
						middlerows=middlerows+"<tr>"+'<td name="'+position+col+'" id ='+position+col+' class="truce" style="height:40px;width:40px;background-color:gray;font-size:10px"><span class="nondraggable" draggable="false">'+col+'</span></td>'+rowdata;
						col=col+1;
					}
	  
				position="bot";
				var frowxbot = '<td name="'+position+'l" id ="xbotl" style="background-color:white;height:10px;width:10px;"></td>';
				var frowxbotldata="";
				for ( var i = 9; i >=0; i-- ) {
					if (i==0) {
						  x=ord('x');
						  style='gray;height:10px;width:40px;font-size:10px';
						  }
					  tdata=htmlgrid[i]; ////console.log(tdata.length);
	  
					  if ((i>0) && (i<9)) {
							  x=ord('a')+i-1;
							  style='gray;height:10px;width:40px;font-size:10px';
							 }
						else if(i==9){
							  x=ord('y');
							  style='gray;height:10px;width:40px;font-size:10px';
							}						   
					  chr_x=String.fromCharCode(x); 
						frowxbotldata=frowxbotldata+ '<td name="castle" id ="'+chr_x+position+'" style="background-color:'+style+'"> <span class="nondraggable_piece" draggable="false" >'+chr_x+'</span></td>';
					}
				frowxbot= '<td name="'+position+'r" id ="y'+position+'r" style="font-size:10px;background-color:white;height:10px;width:10px"></td>';
				var frow="<tr>"+frowxbot+frowxbotldata+ "</tr>";
		}				
	var html = '<tbody>' + lrow+middlerows+frow + "\n</tbody>";
	var hiddenmover= document.getElementById("graphical_board");
	hiddenmover.innerHTML= html;
  }
 
function updateCookieAction(fendata) {
	var cookiefen="";
	var ca = document.cookie.split(';');
	var splitagain=null;
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') 
			c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0){
			//
			cookiecategory=c.substring(nameEQ.length,c.length);
			if(cookiecategory!=null) {cookiecategory=cookiecategory.split('_fen_')[0];}
			break;
		}
			////console.log (c.substring(nameEQ.length,c.length));
	}
	//
	//console.log (cookiecategory);

	document.getElementById("hiddenfen").value=	fendata;
	document.getElementById("hiddenfen").innerHTML=fendata;
	document.getElementById("fen").value=fendata;
	//
	var buttonvalue = document.getElementById("Invert").name;
	if((buttonvalue==null) ||(buttonvalue=="")){
		if((cookiecategory==null) ||(cookiecategory=="")){
			document.getElementById("Invert").name="white"
			category="white";
			buttonvalue="white";
		}
		else if((category==null) ||(category=="")){
			document.getElementById("Invert").name=cookiecategory;
			category=cookiecategory;
		}
		else {
			document.getElementById("Invert").name=cookiecategory;
			category=cookiecategory;
		}
	}
	else if((category==null) ||(category=="")){
		if((cookiecategory==null) ||(cookiecategory=="")){
			document.getElementById("Invert").name="white"
			category="white";
			buttonvalue="white";
		}
		else if((buttonvalue==null) ||(buttonvalue=="")){
			document.getElementById("Invert").name=cookiecategory;
			category=cookiecategory;
		}
		else {
			document.getElementById("Invert").name=cookiecategory;
			category=cookiecategory;
		}
	}
	else if((cookiecategory==null) ||(cookiecategory=="")){
		if((category==null) ||(category=="")){
			document.getElementById("Invert").name="white"
			category="white";
			buttonvalue="white";
		}
		else if((buttonvalue==null) ||(buttonvalue=="")){
			document.getElementById("Invert").name=category;
		}
		else {
			document.getElementById("Invert").name=buttonvalue;		
			category=buttonvalue;
		}
	}

	//console.log (buttonvalue);

	var date = new Date();
	date.setTime(date.getTime()+(1*24*60*60*1000));
	var expires = "; expires="+date.toUTCString();
	//console.log ("   ^^^^^^^^^^^^^^^^^^  ");

	var date = new Date();
	date.setTime(date.getTime()+(1*24*60*60*1000));
	var expires = "; expires="+date.toUTCString();
	$("input[name='whiterdo']").attr("checked",false);
	$("input[name='blackrdo']").attr("checked",true);
	//console.log ("   -----------------------  ");
	
	document.cookie = nameEQ+"; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	document.cookie = nameEQ+category+"_fen_"+ fendata+expires+"; path=/"+";";
}

	function createDefaultCookie() {
		category=null;
		createCookieAction();
		////
		if (category=="white") { category = "black"; document.getElementById("boardtype").value=category;
		document.getElementById("import_boardtype").value=category;document.getElementById("Invert").name="black";  //reversegrid(); 
		$('#import_fen').submit(); 

		}
		else if (category=="black") {  category = "white"; document.getElementById("boardtype").value=category;
		document.getElementById("import_boardtype").value=category; document.getElementById("Invert").name="white";  //reversegrid(); 
		$('#import_fen').submit();
	
		document.getElementById("boardtype").value=category;
		document.getElementById("import_boardtype").value=category;}
		
		createCookieAction();

		if(document.getElementById("hiddenfen").innerHTML.trim!="") 
		fentohtml();
	}

$(document).ready(function(){
		category=null;
		
		myAudio=document.createElement('audio');

		if(document.getElementById("gamemode").getAttribute("gamemode")=="livemove") {myAudio.src = '../assets/move.mp3'; gamemode="livemode"; gameurl="../liveviews/"; commonMove= "livemove"; commonAllMoves = "livemoves" ; nameEQ = "LiveStepType" + "="; }
		else { gamemode="localmode"; nameEQ = "LocalStepType" + "="; gameurl="../views/"; commonMove= "move";  commonAllMoves = "localmoves" ;
		myAudio.src = './assets/move.mp3';

		myAudio.controls = true;
		myAudio.muted=true;
		document.body.appendChild(myAudio);

		$('#livepairing').on('click', function () {
			//alert("hello");
			//this.window.location='livemove\\index.php?paired=yes';
			//$('#show').on('click', function () {
			$('.center').show();
				//$(this).hide();
			//});
		});	
		} 
			
		function piecefunction(eventitem){
			$('form#winninggame').hide();$('form#king_endgame').hide(); $('form#king_surrender').hide(); kingmove=false;
			$('form#make_move').show(); $("#winninggamemove").empty();	$("#"+commonMove).empty();
			$('form#naarad_cmove').hide();	$('cmove').empty();
			$("#"+commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();										
			//$('form#make_move').empty();

			$("#"+commonAllMoves+" option").each(function() {
					$('form#winninggame').hide(); $('form#king_endgame').hide(); $('form#king_surrender').hide();
					if ($('select#'+commonAllMoves+' option').length == 0) {
							$('form#all_moves').hide(); $('form#winninggame').hide(); $('form#history_move').hide(); $('form#make_move').hide(); $('form#naarad_cmove').hide(); 
						}
					//
					$("textarea#playerta").val("");	$("textarea#opponentta").val("");
					$("textarea#player1ta").val(""); $("textarea#player2ta").val("");
					$("div#textAreasRules").hide();
					
					if(($("div.status_box").attr('id')=='1')){
							color_to_move='white';opp_color_to_move='black';
					}
					else if(($("div.status_box").attr('id')=='2')){
							color_to_move='black';opp_color_to_move='white';
					}
				
					if(color_to_move!=''){
							if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
									$("textarea#playerta").val($("textarea#playerta").val()+"* Your ("+color_to_move+ ") Military Officials can move only 1 step in WARZONE. Reason: There is NO Coordinator.\n");
							if($("input#"+color_to_move+"officerscanmovefull").val()=='1')
									$("textarea#playerta").val($("textarea#playerta").val()+"* All Military Officials can move full steps in WARZone.\n");
							if($("input#"+color_to_move+"officerscankill").val()=='0')
									$("textarea#playerta").val($("textarea#playerta").val()+"* Your ("+color_to_move+ ") Military Officials CANNOT STRIKE the Opponent. Reason: King or ArthShashtri or both interested in Domestic Affairs.\n");
							if($("input#"+color_to_move+"officerscankill").val()=='1')
									$("textarea#playerta").val($("textarea#playerta").val()+"* All Military Officials can kill. Reason: King and ArthShashtri both are not idle involved in domestic affairs\n");
					
							//$("textarea#opponentta").val($("textarea#opponentta").val()+"\:: Your Opponent Details:: \n");	
							if($("input#"+opp_color_to_move+"officerscanmovefull").val()=='0')
									$("textarea#opponentta").val($("textarea#opponentta").val()+"** "+opp_color_to_move+" Millitary Officials can move only 1 step in WARZONE. \n");
							if($("input#"+opp_color_to_move+"officerscanmovefull").val()=='1')
									$("textarea#opponentta").val($("textarea#opponentta").val()+"** "+opp_color_to_move+" Millitary Officials can move full steps in WARZONE. So be Cautious.\n");
							if($("input#"+opp_color_to_move+"officerscankill").val()=='0')
									$("textarea#opponentta").val($("textarea#opponentta").val()+"** "+opp_color_to_move+" Military Officials cannot kill anyone in war. Their King has not declared the war or is involved in domestic affairs.\n");
							if($("input#"+opp_color_to_move+"officerscankill").val()=='1')
									$("textarea#opponentta").val($("textarea#opponentta").val()+"** "+opp_color_to_move+" Military Officials Military Officials has rights to STRIKE. So be Cautious\n");
						}
				});
			

			var i =0;var oop;tempi=0;var tname='';var mname='';var dname='';
			p1name = eventitem.getAttribute('name');
			var p2name=null; var ppiecesquare=eventitem.closest("td");
			piecesquare = $(ppiecesquare);
			if(lastsquare!=null){ lastsquare.css('background-color',lastcolor);	lastsquare	=null; 	lastcolor =null; }
			
			naaradmove=false;
			piecemovetype="";
			kingmove=false; arthshastrimove=false;spymove=false;
			soldiermove=false;bishopmove=false;knightmove=false;rookmove=false;generalmove=false;officermove=false;
			
			if((p1name.toLowerCase()=='n')) { naaradmove=true;piecemovetype="naaradmove";	}			
			else if((p1name.toLowerCase()=='p')) { soldiermove=true; piecemovetype="soldiermove";}
			else if((p1name.toLowerCase()=='h')) { knightmove=true;piecemovetype="knightmove"; }
			else if((p1name.toLowerCase()=='g')) { bishopmove=true; piecemovetype="bishopmove";}
			else if((p1name.toLowerCase()=='m')) { rookmove=true; piecemovetype="rookmove";}
			else if((p1name.toLowerCase()=='s')) { generalmove=true; piecemovetype="generalmove";}	
			else if((p1name.toLowerCase()=='i')||(p1name.toLowerCase()=='j')||(p1name.toLowerCase()=='y')) {kingmove=true;piecemovetype="kingmove";}				
			else if((p1name.toLowerCase()=='a')||(p1name=='á')||(p1name=='Á')){	arthshastrimove=true;piecemovetype="arthshastrimove"; }
			else if(p1name.toLowerCase()=='c'){	spymove=true;piecemovetype="spymove";}
			if((p1name.toLowerCase()=='g')||(p1name.toLowerCase()=='h')||(p1name.toLowerCase()=='m')||(p1name.toLowerCase()=='s')){ officermove=true; }
			
			if ($('select#recallmove option').length == 0) { $('form#recall').hide(); $("#recallmove").empty(); }
			if ($('select#Shantimove option').length == 0) { $('form#king_Shanti').hide(); $("#Shantimove").empty(); }
			if ($('select#winninggamemove option').length == 0) { $('form#winninggame').hide();$("#winninggamemove").empty(); }
			if ($('select#endgamemove option').length == 0) { $('form#king_endgame').hide(); }
			if ($('select#surrendermove option').length == 0) { $('form#king_surrender').hide(); }
			if ($('select#cmove option').length == 0) { $('form#naarad_cmove').hide(); }

			if(lastsquare==null){ lastcolor=piecesquare.css('background-color'); piecesquare.css('background-color', 'blue'); lastsquare=piecesquare; }
			lastsquare=piecesquare;
			p2name=	piecesquare.attr("id").substr(0,2);
			
			$('select#cmove option').empty();
			$("textarea#player1ta").val(""); $("textarea#player2ta").val("");
			$("div#textAreasRules").hide();
			
			if ((($("div.status_box").attr('id')=='1')&& (p1name.match(/^[A-ZÁ]*$/))) || (($("div.status_box").attr('id')=='2')&& (p1name.match(/^[a-zá]*$/)))) {
					$("div#textAreasRules").show();
								
					//console.log(piecesquare);
					//console.log(pieceMoves[ppiecesquare.id]);
					$("#"+commonAllMoves+" option").each(function() {
							var val = $(this).val();
							var txt = $(this).html();
							var dataa = $(this).data('coordinate-notation');
							txt=txt.trim();
							if(txt.substr(1,1)=='^') {tname=txt.substr(2,2); mname=txt.substr(4,2); dname=txt.substr(6,2)}
							else if(txt.substr(1,1)=='*') { tname=txt.substr(2,2); dname=txt.substr(4,2)}
							else if(txt.substr(1,1)!='*') {	tname=txt.substr(1,2); dname=txt.substr(3,2)}
							
							if((txt.substr(0,1)==p1name)&&(p2name==tname)){
									//ArthShastri is in CASTLE or opponent CASTLE. If General is in Truce then it means Army will have to retreat. If King or Arsthshastri is in War then retreat will not happen.
									if((officermove==true)&& (/[a-h09]{2,2}/.test(dname))){
											if ((txt.indexOf("Ö") >= 0)){
													$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#winninggame').show();
												}
											else{
													if ($("#"+commonMove).length) {
															$("#"+commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
														}
												}
										}
									//General move from WAR to Truce
									else if((piecemovetype=="generalmove")&& ((/[xy1-8]{2,2}/.test(dname)) &&(/[a-h1-8]{2,2}/.test(p2name)))){
											$("#recallmove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
											document.getElementById('lblSandhi').innerHTML="Sandhi";
											$('form#recall').show();
										}
									//King move from WAR to Truce (non-Borders)
									else if((piecemovetype=="kingmove")&& ((/[xy123678]{2,2}/.test(dname)) &&(/[a-h1-8]{2,2}/.test(p2name)))){
											//
											if ((txt.indexOf("=Y") >= 0)){
													$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_surrender').show();
												}
											else {
													$("#recallmove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													document.getElementById('lblSandhi').innerHTML="Viraam Sandhi";
													$('form#recall').show();
												}
										}
									//ArthShashtri moving to Scepter
									else if((piecemovetype=="arthshastrimove")&& (/[a-h45]{2,2}/.test(dname))){
											if ((txt.indexOf("=Ä") >= 0)){
												$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												$('form#winninggame').show();
												}
											else if ((txt.indexOf("=Á") >= 0)){
												$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												document.getElementById('lblShanti').innerHTML="Shanti";
												$('form#king_Shanti').show();
												}
											else{										
													if ($("#"+commonMove).length) {
														 $("#"+commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));							
													}
												}
										}
									//ArthShashtri moving to Truce Borders
									else if((piecemovetype=="arthshastrimove")&& (/[xy45]{2,2}/.test(dname))){
											if ((txt.indexOf("=Ä") >= 0)){
													$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#winninggame').show();
												}
											else if ((txt.indexOf("=A") >= 0)||(txt.indexOf("=") >= -1)){
													$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													document.getElementById('lblShanti').innerHTML="Shanti";
													$('form#king_Shanti').show();
												}
										}
									//Officers	winning the scepters
									else if(((piecemovetype=="spymove")||(officermove==true)||(piecemovetype=="soldiermove"))&& (/[a-h09]{2,2}/.test(dname))){
											if ((txt.indexOf("Ö")>=0)||(txt.indexOf("#") >= 0)){
													$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#winninggame').show();
												}
											else{
													if ($("#"+commonMove).length) {
														 $("#"+commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													}
												}
										}
									//TRUCE to No Mans
									else if((piecemovetype=="kingmove")&& ((((/[x0]{2,2}/.test(p2name))||(/[y0]{2,2}/.test(p2name)))&&(/[xy09]{2,2}/.test(dname)))|| (((/[x9]{2,2}/.test(p2name))||(/[y9]{2,2}/.test(p2name)))&&(/[xy09]{2,2}/.test(dname))))){
											//No Inversion in TRUCE
											if ((txt.indexOf("=Y") >= 0)||(txt.indexOf("=J") >= 0)){
													$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_surrender').show();
												}
											else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
													$("#"+commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												}
											else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
													$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#winninggame').show();
												}
										}
									//Truce to Truce	
									else if((piecemovetype=="kingmove")&& ((/[xy0-8]{2,2}/.test(p2name)) &&(/[xy0-8]{2,2}/.test(dname)))){
											if ((txt.indexOf("=Y") >= 0)){
													$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_surrender').show();
												}
											else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
												if ($("#"+commonMove).length) {
													 $("#"+commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													}
												}
										}
									//TRUCE to WAR
									else if((piecemovetype=="kingmove")&& ((/[xy0-8]{2,2}/.test(p2name)) &&(/[a-h1-8]{2,2}/.test(dname)))){
											if ((txt.indexOf("=Y") >= 0)){
													$("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_endgame').show();
												}
											else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){

													if ($("#"+ commonMove).length) {
														$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));

													}
												}
										}
									//TRUCE to CASTLE
									else if((piecemovetype=="kingmove")&& ((((/[x0]{2,2}/.test(p2name))||(/[y0]{2,2}/.test(p2name)))&&(/[ah0]{2,2}/.test(dname)))|| (((/[x9]{2,2}/.test(p2name))||(/[y9]{2,2}/.test(p2name)))&&(/[ah0]{2,2}/.test(dname))))){
											//No Inversion in TRUCE
											if ((txt.indexOf("=Y") >= 0)){
													$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_surrender').show();
												}
											else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
													if ($("#"+ commonMove).length) {
															$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
														}
												}
											else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
													$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#winninggame').show();
												}
										}
									//kingmove moving to Truce Borders
									else if((piecemovetype=="kingmove")&& (/[a-h1-8]{2,2}/.test(p2name))&&(/[xy45]{2,2}/.test(dname))){
											if ((txt.indexOf("=V") >= 0)){
													$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#winninggame').show();
												}
											else if ((txt.indexOf("=Y") >= 0)){
													$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_surrender').show();
												}
											else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
													$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													document.getElementById('lblShanti').innerHTML="Viraam Shanti Sandhi";
													//;
													$('form#king_Shanti').show();
												}
										}
									//kingmove War to Non-Border Truce	
									else if((piecemovetype=="kingmove")&& ((/[xy1-8]{2,2}/.test(dname)) &&(/[a-h1-8]{2,2}/.test(p2name)))){
											if ((txt.indexOf("=Y") >= 0)){
												$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												$('form#king_surrender').show();
												}
											else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
												$("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												$('form#king_endgame').show();
												}
										}
									// Within CASTLE Scepter
									else if((piecemovetype=="kingmove")&& (((/[a-h9]{2,2}/.test(p2name))&&(/[d-e9]{2,2}/.test(dname)))|| ((/[a-h0]{2,2}/.test(p2name)&&(/[d-e0]{2,2}/.test(dname)))))){
											//No Draw in CASTLE
											if ((txt.indexOf("=J") >= 0)){
												$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												$('form#king_Shanti').show();
												}
											else if ((txt.indexOf("=I") >= 0) || (txt.indexOf("=") >= -1) ){
												$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												}
											else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#")>=0)){
												$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												$('form#winninggame').show();
												}
										}
									//Within CASTLE
									else if((piecemovetype=="kingmove")&& (((/[a-h9]{2,2}/.test(p2name))&&(/[a-h9]{2,2}/.test(dname)))|| ((/[a-h0]{2,2}/.test(p2name))&&(/[a-h0]{2,2}/.test(dname))))){
											if ((txt.indexOf("=Y") >= 0)||(txt.indexOf("=J") >= 0)){
												$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												$('form#king_Shanti').show();
												}
											else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=I") >= 0)||(txt.indexOf("=V")>=0)||(txt.indexOf("#")>=0)||(txt.indexOf("=") >= -1)){
												$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												}
										}
									//CASTLE to WAR
									else if((piecemovetype=="kingmove")&& ((/[a-h1-8]{2,2}/.test(dname))&&(/[a-h09]{2,2}/.test(p2name)))){
											if ((txt.indexOf("=Y") >= 0)){
													$("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_endgame').show();
												}
											else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
													if ($("#"+ commonMove).length) {
															$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
														}
												}
										}
									//WAR to CASTLE
									else if((piecemovetype=="kingmove")&& ((/[a-h09]{2,2}/.test(dname)&&(/[a-h1-8]{2,2}/.test(p2name))))){
											if ((txt.indexOf("=Y") >= 0)){
													$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_surrender').show();
												}
											else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
													$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#winninggame').show();
												}
											else if ((txt.indexOf("=I") >= 0)){
													$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_Shanti').show();
												}
											else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
													if ($("#"+ commonMove).length) {
															$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													}
												}
										}
									//WAR to WAR
									else if((piecemovetype=="kingmove")&& ((/[a-h1-8]{2,2}/.test(dname)&&(/[a-h1-8]{2,2}/.test(p2name))))){
												if ((txt.indexOf("=Y") >= 0)){
														$("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
														document.getElementById('lblViraam').innerHTML="Viraam";
														$('form#king_endgame').show();
													}
												else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
														$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
														$('form#winninggame').show();
													}							
												else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
														if ($("#"+ commonMove).length) {
																$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));

													}
											}
										}
									//CASTLE to No Mans
									else if((piecemovetype=="kingmove")&& ((((/[x]{1}/.test(dname)))&&(((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname)))|| ((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname))))) ||(((/[y]{1}/.test(dname)))&&(((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname)))|| ((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname))))))){
											//
											if ((txt.indexOf("=Y") >= 0)){
													$("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													document.getElementById('lblViraam').innerHTML="Viraam";
													$('form#king_endgame').show();
												}
											else if ((txt.indexOf("=U") >= 0)){
													$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
												}
											else{
												if ($("#"+ commonMove).length) {
														$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													}
												}
										}
									//CASTLE to TRUCE
									else if((piecemovetype=="kingmove")&& ((((/[x]{1}/.test(dname)))&&(((/[9]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname)))|| ((/[0]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname)))))||(((/[y]{1}/.test(dname)))&&(((/[9]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname)))|| ((/[0]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname))))))){

											if ((txt.indexOf("=Y") >= 0)){
													$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_surrender').show();
												}
											else if ((txt.indexOf("=U") >= 0)){
													$("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_endgame').show();
												}
										}
									else if((piecemovetype=="kingmove")&& (/[1-8]{1}/.test(p2name))){
											//(p2name.indexOf('1')||(p2name.indexOf('2')>=0)||(p2name.indexOf('3')>=0)||(p2name.indexOf('4')>=0)||(p2name.indexOf('5')>=0)||(p2name.indexOf('6')>=0)||(p2name.indexOf('7')>=0)||(p2name.indexOf('8')>=0))){
											if ((txt.indexOf("=Y") >= 0)){
													$("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#king_endgame').show();
												}
											else{
												if ($("#"+ commonMove).length) {
														$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));

													}
												}
										}
									else if((/[a-h0-9]{2,2}/.test(dname))){
											if ((txt.indexOf("#") >= 0)){
													$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
													$('form#winninggame').show();
												}
											else{
													if ($("#"+ commonMove).length) {
															$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));

														}
												}
											}
									else{
											
											if ($("#"+ commonMove).length) {
													$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));

												}
											tempi=tempi+1;
										}
								}
							i=i+1;
				});
				}
			if(p1name=="") { $("#"+ commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();}	
			rulesdescription(color_to_move, piecemovetype);
		}

		if( $('#Invert').length ){
			createCookieAction();
		}

		$(document).mouseover(function(event) {
			event.preventDefault();

			//better add tempselected draggable and remove once done////
			var hidden = Array.prototype.slice.call(document.querySelectorAll(".currentpiece"));
			hidden.forEach(function(eventitem){
				eventitem.classList.remove("currentpiece");
				});
			eventitem=event.target;
			var sample=null;

			if(event.target.nodeName=="TD"){
					 sample=event.target.querySelector('.draggable_piece'); if (sample==null) {eventitem=null;} else if (sample.nodeName=="SPAN"){ eventitem=sample; } else {eventitem=null;}
				}
			else if((event.target.nodeName=="SPAN")  && (event.target.className=="draggable_piece")){ eventitem=event.target; } else {eventitem=null;}

			//if(eventitem==null) { $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();	}
			//else if(eventitem.getAttribute("name")==""){$("#"+ commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();	}
			//else 
			if(eventitem!=null){
				eventitem.classList.add("currentpiece");

					//if($this).classList.contains(draggable_piece);
					var i =0;var oop; tempi=0;

					//$('form#make_move').empty();
					//document.querySelectorAll('.currentpiece').forEach(item => {
						eventitem.removeEventListener('mouseover',piecefunction(eventitem),true);

						eventitem.addEventListener('mouseover',piecefunction(eventitem),true);
						
							if ($('select#winninggamemove option').length > 0) { $('form#winninggame').show(); 	}
							else { $('form#winninggame').hide(); }
									
							if ($('select#endgamemove option').length > 0) { $('form#King_endgame').show(); }	else { $('form#King_endgame').hide(); }
									
							if ($('select#surrendermove option').length > 0) { $('form#King_surrender').show();	}	else { $('form#King_surrender').hide(); }
									
							if ($('select#'+commonMove+' option').length > 0) { $('form#make_move').show(); }	else { $('form#make_move').hide(); }
									
							if ($('select#cmove option').length > 0) { $('form#naarad_cmove').show();	}	else { $('form#naarad_cmove').hide(); }
							
				}
			else {	

					document.querySelectorAll('select#'+commonMove).forEach(eventitem => {
						eventitem.addEventListener('dblclick', event => {
									//
									//$('#make_move').submit();
									//
									myAudio.play();
									movedfen=$('select[name='+ commonMove+'] option').filter(':selected').val();
									var elements = document.getElementById(commonMove).options;
									for(var i = 0; i < elements.length; i++){
											  elements[i].selected = false;
										}
									myAudio.play();
									fetchdata(movedfen);
								});
						});

					document.querySelectorAll('select#cmove').forEach(eventitem => {
						eventitem.addEventListener('dblclick', event => {	
									myAudio.play(); 
									movedfen=$('select[name=cmove] option').filter(':selected').val();
									var elements = document.getElementById("cmove").options;
									for(var i = 0; i < elements.length; i++){
											  elements[i].selected = false;
										}						
									//$('#naarad_cmove').submit(); 
									myAudio.play();fetchdata(movedfen);

								});
						});

					document.querySelectorAll('select#surrendermove').forEach(eventitem => {
						eventitem.addEventListener('dblclick', event => {	
									myAudio.play(); 
									movedfen=$('select[id=surrendermove] option').filter(':selected').val();
									var elements = document.getElementById("surrendermove").options;
									for(var i = 0; i < elements.length; i++){
											  elements[i].selected = false;
										}
									//$('#king_surrender').submit(); 
									fetchdata(movedfen);
								});
						});

					document.querySelectorAll('select#endgamemove').forEach(eventitem => {
						eventitem.addEventListener('dblclick', event => {	
									myAudio.play(); 
									movedfen=$('select[id=endgamemove] option').filter(':selected').val();
									var elements = document.getElementById("endgamemove").options;
									for(var i = 0; i < elements.length; i++){
											  elements[i].selected = false;
										}
									//$('#king_surrender').submit(); 
									fetchdata(movedfen);
								});
						});
				//	});
				}
		});	

		$('table').on("dragstart", function (event) {
					//var dt = event.originalEvent.dataTransfer;
					//dt.setData('Text', $(this).attr('id'));

						//better add tempselected draggable and remove once done////
						var hidden = Array.prototype.slice.call(document.querySelectorAll(".currentpiece"));
						hidden.forEach(function(eventitem){
							eventitem.classList.remove("currentpiece");
							});
						eventitem=event.target;
						var sample=null;
			
						if(event.target.nodeName=="TD"){
								 sample=event.target.querySelector('.draggable_piece'); if (sample==null) {eventitem=null;} else if (sample.nodeName=="SPAN"){ eventitem=sample; } else {eventitem=null;}
							}
						else if((event.target.nodeName=="SPAN")  && (event.target.className=="draggable_piece")){ item=event.target; } else {item=null;}
			
						if(eventitem==null) {$("#"+ commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();	}
						else if(eventitem.getAttribute("name")==""){$("#"+ commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();	}
						else if(eventitem!=null){
							eventitem.classList.add("currentpiece");
										var i =0;var oop; tempi=0;
											kingmove=false;
											$("#winninggamemove").empty();	$("#"+ commonMove).empty();
											$('cmove').empty();
											$("#"+ commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();										
											//$('form#make_move').empty();		
											if ($('select#'+ commonAllMoves+' option').length == 0) {
												$('#textAreasMoves').hide(); 
												}
											else { $('#textAreasMoves').show(); }
											
											$("#"+commonAllMoves+" option").each(function() {
													//
													$("textarea#playerta").val("");	$("textarea#opponentta").val("");
													$("textarea#player1ta").val(""); $("textarea#player2ta").val("");
													$("div#textAreasRules").hide();
													
													if(($("div.status_box").attr('id')=='1')){
															color_to_move='white';opp_color_to_move='black';
													}
													else if(($("div.status_box").attr('id')=='2')){
															color_to_move='black';opp_color_to_move='white';
													}
												
													if(color_to_move!=''){
															if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
																	$("textarea#playerta").val($("textarea#playerta").val()+"* Your ("+color_to_move+ ") Military Officials can move only 1 step in WARZONE. Reason: There is NO Coordinator.\n");
															if($("input#"+color_to_move+"officerscanmovefull").val()=='1')
																	$("textarea#playerta").val($("textarea#playerta").val()+"* All Military Officials can move full steps in WARZone.\n");
															if($("input#"+color_to_move+"officerscankill").val()=='0')
																	$("textarea#playerta").val($("textarea#playerta").val()+"* Your ("+color_to_move+ ") Military Officials CANNOT STRIKE the Opponent. Reason: King or ArthShashtri or both interested in Domestic Affairs.\n");
															if($("input#"+color_to_move+"officerscankill").val()=='1')
																	$("textarea#playerta").val($("textarea#playerta").val()+"* All Military Officials can kill. Reason: King and ArthShashtri both are not idle involved in domestic affairs\n");
													
															//$("textarea#opponentta").val($("textarea#opponentta").val()+"\:: Your Opponent Details:: \n");	
															if($("input#"+opp_color_to_move+"officerscanmovefull").val()=='0')
																	$("textarea#opponentta").val($("textarea#opponentta").val()+"** "+opp_color_to_move+" Millitary Officials can move only 1 step in WARZONE. \n");
															if($("input#"+opp_color_to_move+"officerscanmovefull").val()=='1')
																	$("textarea#opponentta").val($("textarea#opponentta").val()+"** "+opp_color_to_move+" Millitary Officials can move full steps in WARZONE. So be Cautious.\n");
															if($("input#"+opp_color_to_move+"officerscankill").val()=='0')
																	$("textarea#opponentta").val($("textarea#opponentta").val()+"** "+opp_color_to_move+" Military Officials cannot kill anyone in war. Their King has not declared the war or is involved in domestic affairs.\n");
															if($("input#"+opp_color_to_move+"officerscankill").val()=='1')
																	$("textarea#opponentta").val($("textarea#opponentta").val()+"** "+opp_color_to_move+" Military Officials Military Officials has rights to STRIKE. So be Cautious\n");
														}
												});
											
			
											var i =0;var oop;tempi=0;var tname='';var mname='';var dname='';
											p1name = eventitem.getAttribute('name');
											var p2name=null; var ppiecesquare=eventitem.closest("td");
											piecesquare = $(ppiecesquare);
											if(lastsquare!=null){ lastsquare.css('background-color',lastcolor);	lastsquare	=null; 	lastcolor =null; }
											
											naaradmove=false;
											piecemovetype="";
											kingmove=false; arthshastrimove=false;spymove=false;
											soldiermove=false;bishopmove=false;knightmove=false;rookmove=false;generalmove=false;officermove=false;
											
											if((p1name.toLowerCase()=='n')) { naaradmove=true;piecemovetype="naaradmove";	}			
											else if((p1name.toLowerCase()=='p')) { soldiermove=true; piecemovetype="soldiermove";}
											else if((p1name.toLowerCase()=='h')) { knightmove=true;piecemovetype="knightmove"; }
											else if((p1name.toLowerCase()=='g')) { bishopmove=true; piecemovetype="bishopmove";}
											else if((p1name.toLowerCase()=='m')) { rookmove=true; piecemovetype="rookmove";}
											else if((p1name.toLowerCase()=='s')) { generalmove=true; piecemovetype="generalmove";}	
											else if((p1name.toLowerCase()=='i')||(p1name.toLowerCase()=='j')||(p1name.toLowerCase()=='y')) {kingmove=true;piecemovetype="kingmove";}				
											else if((p1name.toLowerCase()=='a')||(p1name=='á')||(p1name=='Á')){	arthshastrimove=true;piecemovetype="arthshastrimove"; }
											else if(p1name.toLowerCase()=='c'){	spymove=true;piecemovetype="spymove";}
											if((p1name.toLowerCase()=='g')||(p1name.toLowerCase()=='h')||(p1name.toLowerCase()=='m')||(p1name.toLowerCase()=='s')){ officermove=true; }
											
											if ($('select#recallmove option').length == 0) { $("#recallmove").empty(); }
											if ($('select#Shantimove option').length == 0) { $("#Shantimove").empty(); }
											if ($('select#winninggamemove option').length == 0) {$("#winninggamemove").empty(); }
											if ($('select#endgamemove option').length == 0) {  }
											if ($('select#surrendermove option').length == 0) {}
											if ($('select#cmove option').length == 0) {  }
			
											if(lastsquare==null){ lastcolor=piecesquare.css('background-color'); piecesquare.css('background-color', 'blue'); lastsquare=piecesquare; }
											lastsquare=piecesquare;
											p2name=	piecesquare.attr("id").substr(0,2);
											
											$('select#cmove option').empty();
											$("textarea#player1ta").val(""); $("textarea#player2ta").val("");
											$("div#textAreasRules").hide();
											if ($('select#'+ commonAllMoves+' option').length == 0) {
												$('#textAreasMoves').hide(); 
												}
											else $('#textAreasMoves').show(); 											
											//
											//dt = event.originalEvent.dataTransfer; dt.setData('Text', $(event.srcElement).closest('td').attr('id'));
											dt = $(eventitem).closest('td').attr('id');
											var p2nameelement=$(eventitem).closest('td').attr('id');
											p2name=p2nameelement.substr(0,2);
											//alert (p2name);
		
											if ((($("div.status_box").attr('id')=='1')&& (p1name.match(/^[A-ZÁ]*$/))) || (($("div.status_box").attr('id')=='2')&& (p1name.match(/^[a-zá]*$/)))) {
													$("div#textAreasRules").show();
													//console.log(pieceMoves[ppiecesquare.id]);
													OriginalCell= eventitem;
													$("#"+commonAllMoves+" option").each(function() {
															var val = $(eventitem).val();
															var txt = $(eventitem).html();
															var dataa = $(eventitem).data('coordinate-notation');
															txt=txt.trim();
															if(txt.substr(1,1)=='^') {tname=txt.substr(2,2); mname=txt.substr(4,2); dname=txt.substr(6,2)}
															else if(txt.substr(1,1)=='*') { tname=txt.substr(2,2); dname=txt.substr(4,2)}
															else if(txt.substr(1,1)!='*') {	tname=txt.substr(1,2); dname=txt.substr(3,2)}
															
															if((txt.substr(0,1)==p1name)&&(p2name==tname)){
																	//ArthShastri is in CASTLE or opponent CASTLE. If General is in Truce then it means Army will have to retreat. If King or Arsthshastri is in War then retreat will not happen.
																	if((officermove==true)&& (/[a-h09]{2,2}/.test(dname))){
																			if ((txt.indexOf("Ö") >= 0)){
																					$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#winninggame').show();
																				}
																			else{
																					if ($("#"+ commonMove).length) {
																							$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																						}
																				}
																		}
																	//General move from WAR to Truce
																	else if((piecemovetype=="generalmove")&& ((/[xy1-8]{2,2}/.test(dname)) &&(/[a-h1-8]{2,2}/.test(p2name)))){
																			$("#recallmove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																			document.getElementById('lblSandhi').innerHTML="Sandhi";
																			$('form#recall').show();
																		}
																	//King move from WAR to Truce (non-Borders)
																	else if((piecemovetype=="kingmove")&& ((/[xy123678]{2,2}/.test(dname)) &&(/[a-h1-8]{2,2}/.test(p2name)))){
																			//
																			if ((txt.indexOf("=J") >= 0)){
																					$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#king_surrender').show();
																				}
																			else {
																					$("#recallmove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					document.getElementById('lblSandhi').innerHTML="Viraam Sandhi";
																					$('form#recall').show();
																				}
																		}
																	//arthshastrimove move from WAR to Truce (non-Borders)
																	else if((piecemovetype=="arthshastrimove")&& ((/[xy123678]{2,2}/.test(dname)) &&(/[a-h1-8]{2,2}/.test(p2name)))){
																			//
																			if ((txt.indexOf("=Y") >= 0)){
																					$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#king_surrender').show();
																				}
																			else {
																					$("#recallmove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					document.getElementById('lblSandhi').innerHTML="Viraam Sandhi";
																					$('form#recall').show();
																				}
																		}																	
																	//ArthShashtri moving to Scepter
																	else if((piecemovetype=="arthshastrimove")&& (/[a-h45]{2,2}/.test(dname))){
																			if ((txt.indexOf("=Ä") >= 0)){
																				$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#winninggame').show();
																				}
																			else if ((txt.indexOf("=Á") >= 0)){
																				$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				document.getElementById('lblShanti').innerHTML="Shanti";
																				$('form#king_Shanti').show();
																				}
																			else{										
																					if ($("#"+ commonMove).length) {
																						 $("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));							
																					}
																				}
																		}
																	//ArthShashtri moving to Truce Borders
																	else if((piecemovetype=="arthshastrimove")&& (/[xy45]{2,2}/.test(dname))){
																			if ((txt.indexOf("=Ä") >= 0)){
																					$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#winninggame').show();
																				}
																			else if ((txt.indexOf("=A") >= 0)||(txt.indexOf("=") >= -1)){
																					$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					document.getElementById('lblShanti').innerHTML="Shanti";
																					$('form#king_Shanti').show();
																				}
																		}
																	//Officers	winning the scepters
																	else if(((piecemovetype=="spymove")||(officermove==true)||(piecemovetype=="soldiermove"))&& (/[a-h09]{2,2}/.test(dname))){
																			if ((txt.indexOf("Ö")>=0)||(txt.indexOf("#") >= 0)){
																					$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#winninggame').show();
																				}
																			else{
																					if ($("#"+ commonMove).length) {
																						 $("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					}
																				}
																		}
																	//TRUCE to No Mans
																	else if((piecemovetype=="kingmove")&& ((((/[x0]{2,2}/.test(p2name))||(/[y0]{2,2}/.test(p2name)))&&(/[xy09]{2,2}/.test(dname)))|| (((/[x9]{2,2}/.test(p2name))||(/[y9]{2,2}/.test(p2name)))&&(/[xy09]{2,2}/.test(dname))))){
																			//No Inversion in TRUCE
																			if ((txt.indexOf("=J") >= 0)){
																					$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#king_surrender').show();
																				}
																			else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
																					$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				}
																			else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
																					$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#winninggame').show();
																				}
																		}
																	//Truce to Truce	
																	else if((piecemovetype=="kingmove")&& ((/[xy0-8]{2,2}/.test(p2name)) &&(/[xy0-8]{2,2}/.test(dname)))){
																			if ((txt.indexOf("=J") >= 0)){
																					$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#king_surrender').show();
																				}
																			else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
																				if ($("#"+ commonMove).length) {
																					 $("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					}
																				}
																		}
																	//TRUCE to WAR
																	else if((piecemovetype=="kingmove")&& ((/[xy0-8]{2,2}/.test(p2name)) &&(/[a-h1-8]{2,2}/.test(dname)))){
																			if ((txt.indexOf("=J") >= 0)){
																				$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#king_surrender').show();
																				}
																			else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
			
																					if ($("#"+ commonMove).length) {
																						$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
			
																					}
																				}
																		}
																	//TRUCE to CASTLE
																	else if((piecemovetype=="kingmove")&& ((((/[x0]{2,2}/.test(p2name))||(/[y0]{2,2}/.test(p2name)))&&(/[ah0]{2,2}/.test(dname)))|| (((/[x9]{2,2}/.test(p2name))||(/[y9]{2,2}/.test(p2name)))&&(/[ah0]{2,2}/.test(dname))))){
																			//No Inversion in TRUCE
																			if ((txt.indexOf("=J") >= 0)){
																					$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#king_surrender').show();
																				}
																			else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
																					if ($("#"+ commonMove).length) {
																							$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																						}
																				}
																			else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
																					$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#winninggame').show();
																				}
																		}
																	//kingmove moving to Truce Borders
																	else if((piecemovetype=="kingmove")&& (/[a-h1-8]{2,2}/.test(p2name))&&(/[xy45]{2,2}/.test(dname))){
																			if ((txt.indexOf("=V") >= 0)){
																					$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#winninggame').show();
																				}
																			else if ((txt.indexOf("=J") >= 0)){
																					$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#king_surrender').show();
																				}
																			else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
																				if ($("#"+ commonMove).length) {
																					$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				}
																				}
																		}
																	//kingmove War to Non-Border Truce	// surrender and recall both
																	else if((piecemovetype=="kingmove")&& ((/[xy1-8]{2,2}/.test(dname)) &&(/[a-h1-8]{2,2}/.test(p2name)))){
																			if ((txt.indexOf("=I") >= 0)){
																				$("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#king_surrender').show();
																				}
																			else if ((txt.indexOf("=J") >= 0)||(txt.indexOf("=") >= -1)){
																				$("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#king_endgame').show();
																				}
																		}
																	// Within CASTLE Scepter
																	else if((piecemovetype=="kingmove")&& (((/[a-h9]{2,2}/.test(p2name))&&(/[d-e9]{2,2}/.test(dname)))|| ((/[a-h0]{2,2}/.test(p2name)&&(/[d-e0]{2,2}/.test(dname)))))){
																			//No Draw in CASTLE
																			if ((txt.indexOf("=J") >= 0)){
																				$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#king_Shanti').show();
																				}
																			else if ((txt.indexOf("=I") >= 0) || (txt.indexOf("=") >= -1) ){
																				$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				}
																			else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#")>=0)){
																				$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#winninggame').show();
																				}
																		}
																	//Within CASTLE
																	else if((piecemovetype=="kingmove")&& (((/[a-h9]{2,2}/.test(p2name))&&(/[a-h9]{2,2}/.test(dname)))|| ((/[a-h0]{2,2}/.test(p2name))&&(/[a-h0]{2,2}/.test(dname))))){
																			if ((txt.indexOf("=J") >= 0)){
																				$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#king_Shanti').show();
																				}
																			else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=V")>=0)||(txt.indexOf("#")>=0)||(txt.indexOf("=") >= -1)){
																				$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				}
																		}
																	//CASTLE to WAR
																	else if((piecemovetype=="kingmove")&& ((/[a-h1-8]{2,2}/.test(dname))&&(/[a-h09]{2,2}/.test(p2name)))){
																			if ((txt.indexOf("=J") >= 0)){
																				$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#king_Shanti').show();
																				}
																			else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
																					if ($("#"+ commonMove).length) {
																							$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																						}
																				}
																		}
																	//WAR to CASTLE
																	else if((piecemovetype=="kingmove")&& ((/[a-h09]{2,2}/.test(dname)&&(/[a-h1-8]{2,2}/.test(p2name))))){
																			if ((txt.indexOf("=J") >= 0)){
																				$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#king_Shanti').show();
																				}
																			else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
																					$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#winninggame').show();
																				}
																			else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
																					if ($("#"+ commonMove).length) {
																							$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					}
																				}
																		}
																	//WAR to WAR
																	else if((piecemovetype=="kingmove")&& ((/[a-h1-8]{2,2}/.test(dname)&&(/[a-h1-8]{2,2}/.test(p2name))))){
																		if ((txt.indexOf("=J") >= 0)){
																			$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																			$('form#king_Shanti').show();
																			}
																		else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
																				$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#winninggame').show();
																			}
																		else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
																				if ($("#"+ commonMove).length) {
																						$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				}
																			}
																		}
																	//CASTLE to No Mans
																	else if((piecemovetype=="kingmove")&& ((((/[x]{1}/.test(dname)))&&(((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname)))|| ((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname))))) ||(((/[y]{1}/.test(dname)))&&(((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname)))|| ((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname))))))){
																		if ((txt.indexOf("=J") >= 0)){
																			$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																			$('form#king_Shanti').show();
																			}
																		else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
																				$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#winninggame').show();
																			}
																		else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
																				if ($("#"+ commonMove).length) {
																						$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				}
																			}
																		}
																	//CASTLE to TRUCE
																	else if((piecemovetype=="kingmove")&& ((((/[x]{1}/.test(dname)))&&(((/[9]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname)))|| ((/[0]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname)))))||(((/[y]{1}/.test(dname)))&&(((/[9]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname)))|| ((/[0]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname))))))){
																		if ((txt.indexOf("=J") >= 0)){
																			$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																			$('form#king_Shanti').show();
																			}
																		else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
																				$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#winninggame').show();
																			}
																		else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
																				if ($("#"+ commonMove).length) {
																						$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				}
																			}
																		}
																	else if((piecemovetype=="kingmove")&& (/[1-8]{1}/.test(p2name))){
																		if ((txt.indexOf("=J") >= 0)){
																			$("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																			$('form#king_Shanti').show();
																			}
																		else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
																				$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				$('form#winninggame').show();
																			}
																		else if ((txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
																				if ($("#"+ commonMove).length) {
																						$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																				}
																			}
																		}
																	else if((/[a-h0-9]{2,2}/.test(dname))){
																			if ((txt.indexOf("#") >= 0)){
																					$("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
																					$('form#winninggame').show();
																				}
																			else{
																					if ($("#"+ commonMove).length) {
																							$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
			
																						}
																				}
																			}
																	else{
																			
																			if ($("#"+ commonMove).length) {
																					$("#"+ commonMove).append($('<option></option>').val(val).html(txt).attr('data_coordinate_notation',dataa));
			
																				}
																			tempi=tempi+1;
																		}
																}
															i=i+1;
												});
												}
											if(p1name=="") { $("#"+ commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();}	
											rulesdescription(color_to_move, piecemovetype);
										if ($('select#winninggamemove option').length > 0) { $('form#winninggame').show(); 	}
										else {  }
												
										if ($('select#endgamemove option').length > 0) { $('form#King_endgame').show(); }	else {  }
												
										if ($('select#surrendermove option').length > 0) { $('form#King_surrender').show();	}	else { }
												
										if ($('select#'+commonMove+' option').length > 0) { $('form#make_move').show(); }	else { }
												
										if ($('select#'+commonMove+' option').length > 0) { $('form#naarad_cmove').show();	}	else { }
							}
						else {	
		
						//	});
						}{
							rulesdescription(color_to_move, piecemovetype);
									}
							});	

		$('table').bind('selectstart', function(event) {
					event.preventDefault();
			});

		$('table').on("dragenter dragover drop", function (event,ui ) {	
			event.preventDefault();

			var optioncount=0; var tempvalue=null;;

			if (event.type === 'drop') {
				//console.log(OriginalCell);				
				//console.log(dt);

				var hidden = Array.prototype.slice.call(document.querySelectorAll(".currentpiece"));
				hidden.forEach(function(item){
						item.classList.remove("currentpiece");
					});
				var item=event.target;
				var sample=null;
	
				if(event.target.nodeName=="TD"){
						 sample=event.target.querySelector('.draggable_piece'); if (sample==null) {item=null;} else if (sample.nodeName=="SPAN"){ item=sample; } else {item=null;}
					}
				else if((event.target.nodeName=="SPAN")  && (event.target.className=="draggable_piece")){ item=event.target; } else {item=null;}
	
				if(item==null) {$("#"+ commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();	}
				else if(item.getAttribute("name")==""){$("#"+ commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();	}
				else if(item!=null){
						item.classList.add("currentpiece");
						}

				oldsquare = dt;
				newsquare = $(item).closest('td').attr('id');
				option_tag_in_select_tag = [];
				optioncount=-1;
				coordinate_notation = p1name+oldsquare + newsquare;
				//console.log(coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
				
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag[0] = tempvalue;
					optioncount=optioncount+tempvalue.length;
				}

				//optioncount=optioncount+tempvalue.length;
				//option_tag_in_select_tag[0] = tempvalue;
				coordinate_notation = p1name+'*'+oldsquare + newsquare;
				//console.log("** "+coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
				
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag[optioncount] = tempvalue;
					optioncount=optioncount+tempvalue.length;
				}

				coordinate_notation = p1name+oldsquare + newsquare+'=';
				//console.log(coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
	
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag[optioncount] = tempvalue;
					optioncount=optioncount+tempvalue.length;
				}
				
				coordinate_notation = p1name+oldsquare + newsquare+'+';
				//console.log(coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
				
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag[optioncount] = tempvalue;
					optioncount=optioncount+tempvalue.length;
				}

				coordinate_notation = p1name+'*'+oldsquare + newsquare+'+';
				//console.log(coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
	
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag[optioncount] = tempvalue;
					optioncount=optioncount+tempvalue.length;
				}
	
				coordinate_notation = p1name+'^'+oldsquare + newsquare+'+';
				//console.log(coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
	
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag[optioncount] = tempvalue;
					optioncount=optioncount+tempvalue.length;
				}
	
				//coordinate_notation = p1name+'*'+oldsquare + newsquare+'+';
				////console.log(coordinate_notation);
				//tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation='" + coordinate_notation + "']");
				
				if ( option_tag_in_select_tag.length != 0 ) {				
	
					//option_tag_in_select_tag.attr('selected','selected');
					//$(this).find('span').attr('name',p1name);
	
					/*				
					if (typeof (Storage) != "undefined"){
						if (!localStorage.getItem("serverData")){
							// Ajax JSON to get server information
							getServerData();
							}
						else
							{
							retrieveAndSetData();
							}
						}
					else
						{
						alert("localStorage unavailable!");
						}
					*/	
					//$('#make_move').submit();
					
					if(optioncount==0){
						tempvalue=option_tag_in_select_tag[optioncount];
						//console.log(tempvalue);
						//console.log(tempvalue[0].value);

						movedfen=tempvalue[0].value;

							//movedfen=$('select[id=all_moves] option').filter(':selected').val();
							var elements = document.getElementById(commonAllMoves).options;
							for(var i = 0; i < elements.length; i++){
									  elements[i].selected = false;
								}
							//$('#king_surrender').submit();
							fetchdata(movedfen);
							option_tag_in_select_tag=[]; 
							optioncount=-1;
							OriginalCell=null;			
							dt = null;
						}				
					
					};
			}	
								rulesdescription(color_to_move, piecemovetype);
		});	

		movedfen=null;
		fetchdata(movedfen);	

		
				var all_moves = document.createElement('form');
				all_moves.disabled = false;all_moves.readOnly = false;all_moves.setAttribute("style","display:none;");
				all_moves.setAttribute("name","all_moves");all_moves.setAttribute("id","all_moves");all_moves.setAttribute("type","hidden");all_moves.method = 'post';
				document.getElementById("divmoves").appendChild(all_moves);
		
				var all_moves_select = document.createElement('select');
				all_moves_select.setAttribute("name",commonMove);all_moves_select.setAttribute("id",commonAllMoves)
				all_moves_select.setAttribute("style","display:none;");all_moves_select.size="19";
				document.getElementById("all_moves").innerHTML='All Legal Moves:<br> <select id="'+commonAllMoves+'" name="'+ commonMove+'" size="19"></select><br>	Move Count:'+ BoardMoves.length+'><br> <input id="boardtype" name="boardtype" hidden value="">';
	
				var all_movesoption = null;
				all_moves_select=document.getElementById(commonAllMoves);
				var piecename="",piecemove="",squarename="",	fen="",data_coordinate_notation="", pieceset=[];			
	
				for (var optionmoves=0;optionmoves<BoardMoves.length;optionmoves++){
						//console.log("***********************")
						data_coordinate_notation=BoardMoves[optionmoves].data_coordinate_notation;
						
						all_movesoption = document.createElement('option');
						all_movesoption.value = BoardMoves[optionmoves].option;all_movesoption.readOnly = false;
						all_movesoption.setAttribute("data_coordinate_notation",BoardMoves[optionmoves].data_coordinate_notation);
						all_movesoption.innerHTML=BoardMoves[optionmoves].data_coordinate_notation;
						all_moves_select.appendChild(all_movesoption);
						///update the piecemoves here
	
						if(data_coordinate_notation.substr(0,1)=='^') {
							piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
						else if(data_coordinate_notation.substr(0,1)=='*') { piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
						else if(data_coordinate_notation.substr(0,1)=='>') { piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
						else  { piecename=data_coordinate_notation.substr(0,1);squarename=data_coordinate_notation.substr(1,2); }
			 
						piecemove=data_coordinate_notation;
						fen=all_movesoption;
						pieceset["piecename"] = piecename;
						pieceset["squarename"] = squarename;
						pieceset["piecemove"] = piecemove;
						pieceset["fen"] = all_movesoption.value;;
			
						pieceMoves[squarename]=pieceset;
						pieceset=[];
					}
	
				//console.log (pieceMoves);	
	
				if(document.getElementById("hiddenfen").innerHTML.trim()!="") {	fentohtml(); }
				if( $('#Invert').length ) createCookieAction();
				//
	
				if (document.contains(document.getElementById("movestypes"))) {
						var parent=document.getElementById("movestypes");
						var Div_KingMoves = document.createElement('div');
						Div_KingMoves.setAttribute("class","container KingMoves");
						var movesdiv='<div id="textAreasMoves">	<ul id="Movestabs2">  <li><a href="#Normal">Normal</a></li> <li><a href="#Shanti">Shanti</a></li> <li><a href="#Surrender">Surrender/Shanti/Retreat</a></li> <li><a href="#End">End Game</a></li><li><a href="#Winning">Win Game</a></li></ul>';
	
						var make_move='<div id="Normal" class="Normal" class="tabmovescontent tabContent tab-panel"> <div style="float:left;width:18%;"> <form id="make_move" name="make_move" hidden disabled readonly style="display:none;"  method="POST"><label >Normal:<br/></label> <select id="'+commonMove+'" name="'+commonMove+'" size="10"></select> <input id="submitmove" type="submit" value="Make Move"> </form> </div></div>';
						var naarad_cmove='<div id="NaaradMove" class="NaaradMove" class="tabmovescontent tabContent tab-panel"> <div style="float:left;width:18%;"> <form id="naarad_cmove" name="naarad_cmove" hidden disabled readonly style="display:none;"  method="POST"><label >Controlled: <br/></label> <select id="cmove" name="'+ document.getElementById("gamemode").getAttribute("gamemode")+'" size="10"></select>  <input id="submitcmove" type="submit" value="Make Move"> </form> </div></div>';
						var king_endgame='<div id="End" class="EndGame" class="tabmovescontent tabContent tab-panel"> <div style="float:left;width:18%;"> <form id="king_endgame" name="king_endgame" hidden disabled readonly style="display:none;"  method="POST"><label id="lblViraam">Viraam: <br/></label> <select id="endgamemove" name="'+ document.getElementById("gamemode").getAttribute("gamemode")+'" size="10"></select> <input id="submitendgamemove" type="submit" value="Make Move"> </form> </div></div>';
						var recall='<div id="recall" class="Normal" class="tabmovescontent tabContent tab-panel"> <div style="float:left;width:18%;"> <form id="recall" name="recall" hidden disabled readonly style="display:none;"  method="POST"><label id="lblSandhi">Sandhi: <br/></label> <select id="recallmove" name="'+ document.getElementById("gamemode").getAttribute("gamemode")+'" size="10"></select> <input id="submitrecallmove" type="submit" value="Make Move"> </form> </div></div>';
						var king_Shanti='<div id="Shanti" class="Normal" class="tabmovescontent tabContent tab-panel"> <div style="float:left;width:18%;"> <form id="king_Shanti" name="king_Shanti" hidden disabled readonly style="display:none;"  method="POST"><label id="lblShanti">Shanti: <br/></label> <select id="Shantimove" name="'+commonMove+'" size="10"></select> <input id="submitShantimove" type="submit" value="Make Move"> </form> </div></div>';
						var winninggame='<div id="Winning" class="Normal" class="tabmovescontent tabContent tab-panel"> <div style="float:left;width:18%;"> <form id="winninggame" name="winninggame" hidden disabled readonly style="display:none;"  method="POST"><label>Winning: <br/></label> <select id="winninggamemove" name="'+commonMove+'" size="10"></select> <input id="submitwinninggamemove" type="submit" value="Make Move"> </form> </div></div>';
						var king_surrender='<div id="Surrender" class="Normal" class="tabmovescontent tabContent tab-panel"> <div style="float:left;width:18%;"> <form id="king_surrender" name="king_surrender" hidden disabled readonly style="display:none;"  method="POST"><label>Surrender: <br/></label> <select id="surrendermove" name="'+commonMove+'" size="10"></select> <input id="submitsurrendermove" type="submit" value="Make Move"> </form> </div></div>';
	
						Div_KingMoves.innerHTML=movesdiv+make_move+naarad_cmove+king_endgame+recall+king_Shanti+winninggame+king_surrender+"</div>";
						//console.log(Div_KingMoves.innerHTML);
						document.getElementById("movestypes").appendChild(Div_KingMoves);
					}
	
				var piece=document.getElementsByClassName('draggable_piece');
				var bt1 = document.createElement('input'), bt2 = document.createElement('input'), bt3 = document.createElement('input');
				var bt4 = document.createElement('input'), bt5 = document.createElement('input'), bt6 = document.createElement('input');
				var bt7 = document.createElement('input'), bt8 = document.createElement('input');
	
				bt1.setAttribute("name","import_boardtype");bt2.setAttribute("name","import_boardtype");bt3.setAttribute("name","import_boardtype");
				bt4.setAttribute("name","import_boardtype");bt5.setAttribute("name","import_boardtype");bt6.setAttribute("name","import_boardtype");
				bt7.setAttribute("name","import_boardtype");bt8.setAttribute("name","import_boardtype");
	
				bt1.setAttribute("type","hidden"), bt1.setAttribute("type","hidden"), bt2.setAttribute("type","hidden");
				bt3.setAttribute("type","hidden"), bt4.setAttribute("type","hidden"), bt5.setAttribute("type","hidden");
				bt6.setAttribute("type","hidden"), bt7.setAttribute("type","hidden"), bt8.setAttribute("type","hidden");
	
				bt1.setAttribute("value",category), bt1.setAttribute("value",category), bt2.setAttribute("value",category), bt3.setAttribute("value",category);
				bt4.setAttribute("value",category), bt5.setAttribute("value",category), bt6.setAttribute("value",category);
				bt7.setAttribute("value",category), bt8.setAttribute("value",category);
					
				document.getElementById('king_surrender').appendChild(bt1), document.getElementById('all_moves').appendChild(bt2);
				document.getElementById('winninggame').appendChild(bt3), document.getElementById('recall').appendChild(bt4);
				document.getElementById('king_Shanti').appendChild(bt5), document.getElementById('king_endgame').appendChild(bt6);
				document.getElementById('make_move').appendChild(bt7), document.getElementById('naarad_cmove').appendChild(bt8);
					//add the event handler to add steps /


		$('#perft').click(function(){
				window.location.href = 'perft.php?fen='  + $('#fen').val();
			});

		$('#WhiteGameID').click(function(){
				if($('#WhiteGameID').length != 0) { $('#WhiteGameID_Data').css('visibility', 'visible'); }
			});

		$('#uploadimages').on('click', function() {
				$("#dialog").dialog({
						modal: true
					});
			});

		$('.status_box').on('click', function () {
				$('.fencenter').toggleClass("hideform");

				$('#fenclose').on('click', function () {
						$('.fencenter').hide();
						$('#fenshow').show();
					});
			});


			var modal = document.getElementById("myModal");
			// Get the button that opens the modal
			var btn = document.getElementById("myBtn");
			// Get the <span> element that closes the modal
			var span = document.getElementsByClassName("close")[0];
			// When the user clicks the button, open the modal 
			btn.onclick = function() {
			  modal.style.display = "block";
			}
			
			// When the user clicks on <span> (x), close the modal
			span.onclick = function() {
			  modal.style.display = "none";
			}
			
			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
			  if (event.target == modal) {
				modal.style.display = "none";
			  }
			}
			init();
	});


	function deselectchoices(){
		var radio = document.querySelector('input[type=radio][name=livegame_Whitechoice_1]');
		radio.checked = false;
		radio = document.querySelector('input[type=radio][name=livegame_Whitechoice_2]');
		radio.checked = false;	
		radio = document.querySelector('input[type=radio][name=livegame_PairedBlackchoice]');
		radio.checked = false;

		selectedgamerdoid="";
		selectedgamerdoidvalue="";		
		document.querySelector('input[name=BlackGameID_]').value="";
		document.querySelector('input[name=BlackGameID_]').style.display = 'none';
		document.querySelector('label[name=BlackGameID_]').style.display = 'none';			 

	}

	function smartgamechoice(){

		element=document.querySelector('button[id=modalsubmit]');
		if(element.hasAttribute('data-micromodal-close'))
			element.removeAttribute('data-micromodal-close');

			selectedgamerdoidvalue=document.querySelector('input[name=BlackGameID_]').value;
		
		if((selectedgamerdoid=="livegame_PairedBlackchoice") && (selectedgamerdoidvalue.trim().length>0)){
			document.querySelector('input[name=BlackGameID_]').style.display = 'none';			 
			document.querySelector('label[name=BlackGameID_]').style.display = 'none';

			element.setAttribute('data-micromodal-close',null);

			var form = document.createElement("form");
			var element1 = document.createElement("input"); 

			form.method = "POST";
			form.action = "./livemove/";   
		
			element1.value=selectedgamerdoidvalue.trim();
			element1.name="BlackGameID";
			element1.id="BlackGameID";
			form.appendChild(element1);		
			document.body.appendChild(form);		
			form.submit();
		}
		else if((selectedgamerdoid=="livegame_Whitechoice_1")||(selectedgamerdoid=="livegame_Whitechoice_2")){
			selectedgamerdoidvalue="";
			selectedgamerdoid=="";
			document.querySelector('input[name=BlackGameID_]').style.display = 'none';			 
			document.querySelector('label[name=BlackGameID_]').style.display = 'none';

			element.setAttribute('data-micromodal-close',null);
			//window.location='livemove/';
			var form = document.createElement("form");
			var element1 = document.createElement("input");

			form.method = "get";
			form.action = "livemove/";   
			element1.value="1";
			if(selectedgamerdoid=="livegame_Whitechoice_2"){element1.value="2";}
			element1.name="lg";
			element1.id="lg";
			form.appendChild(element1);		
			document.body.appendChild(form);
			form.submit();

		}		
	}

function check(rdoname) {
	document.getElementById(rdoname).checked = true;
}
function uncheck(rdoname) {
	document.getElementById(rdoname).checked = false;
}

	function livegame(rdonum){
		deselectchoices();
		var rdochoice="livegame_Whitechoice"+"_"+rdonum;
		check(rdochoice);
		 selectedgamerdoid=rdochoice;
		 selectedgamerdoidvalue="";
		 document.querySelector('input[name=BlackGameID_]').style.display = 'none';			 
		 document.querySelector('label[name=BlackGameID_]').style.display = 'none';			 

		}

	function pairedpopup(){
		deselectchoices();
		selectedgamerdoid="livegame_PairedBlackchoice";
		selectedgamerdoidvalue="";
			check("livegame_PairedBlackchoice");		
			//enable the field to add the blackgameid
			document.querySelector('input[name=BlackGameID_]').value="";
			document.querySelector('input[name=BlackGameID_]').style.display = 'block';
			document.querySelector('label[name=BlackGameID_]').style.display = 'block';

	}	

	function showgamechoices(){
		try {
			deselectchoices();
			//document.getElementById('WhiteGameID_Data').innerHTML= "Game ID = "+ decodedgameid;
			//document.getElementById('WhiteGameFEN_Data').innerHTML="Current FEN = "+document.getElementById("fen").value
		  MicroModal.init({
			awaitCloseAnimation: true,// set to false, to remove close animation
			onShow: function(gamechoicemodal) {										
			 console.log("micromodal open = showgamechoices ");
			},
			onClose: function(gamechoicemodal) {
			  console.log("micromodal close");
			}
		  });
		  
		} catch (e) {
		  console.log("micromodal error: ", e);
		}
	};	

	function cancelorphanedgame(){
		try {
			deselectchoices();
			//document.getElementById('WhiteGameID_Data').innerHTML= "Game ID = "+ decodedgameid;
			//document.getElementById('WhiteGameFEN_Data').innerHTML="Current FEN = "+document.getElementById("fen").value
		  MicroModal.init({
			awaitCloseAnimation: true,// set to false, to remove close animation
			onShow: function(gamechoicemodal) {										
			 console.log("micromodal open = showgamechoices ");
			},
			onClose: function(gamechoicemodal) {
			  console.log("micromodal close");
			}
		  });
		  
		} catch (e) {
		  console.log("micromodal error: ", e);
		}
}