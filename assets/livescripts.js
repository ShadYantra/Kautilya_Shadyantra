////
var results = [], selectedoption=-1 , length = 0;
var radioState = "";
var idleTime = 0;
var tempinterval;
var wcastleborderopen=0, bcastleborderopen=0, commonborderopen=0;
var showextramoves_opened=0;
var tabLinks = new Array();
var contentDivs = new Array();
var tabListItems =null;var gameid="";
var cookiemover="";var decodedsystemcs="",encodedgameid="",decodedgameid="";
var i = 0; var dt=null;
var systemcs="", cookiefen="";var cookiefenArr=[];
var nameEQ="",decodedc="";var cc="";var leftover="";var gid="";var gidArr="";
var action="";
var svgheight="";
var svgwidth="";
var lg = "",templg="",cog="";
var newgamestatus = "0";
var cancelgame=0;


svgheight="36px";
svgwidth="36px";

var myArray=[];

myArray["i"] = '<img name="i" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/IB.svg">';
myArray["I"] = '<img name="I" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/IW.svg"> ';
myArray["j"] = '<img name="j" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/IB.svg">';
myArray["J"] = '<img name="J" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/IW.svg">';
myArray["a"] = '<img name="a" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/AB.svg">';
myArray["A"] = '<img name="A" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/AW.svg"> ';
myArray["c"] = '<img name="c" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/CB.svg">';
myArray["C"] = '<img name="C" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/CW.svg"> ';
myArray["s"] = '<img name="s" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/SB.svg"> ';
myArray["S"] = '<img name="S" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/SW.svg"> ';
myArray["m"] = '<img name="m" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/MB.svg"> ';
myArray["M"] = '<img name="M" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/MW.svg"> ';
myArray["h"] = '<img name="h" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/HB.svg"> ';
myArray["H"] = '<img name="H" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/HW.svg"> ';
myArray["g"] = '<img name="g" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/GB.svg"> ';
myArray["G"] = '<img name="G" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/GW.svg"> ';
myArray["n"] = '<img name="n" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/NB.svg"> ';
myArray["N"] = '<img name="N" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/NW.svg"> ';
myArray["p"] = '<img name="p" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/PB.svg"> ';
myArray["P"] = '<img name="P" height= "'+svgheight+ '" width= "'+ svgwidth+'" src="../assets/PW.svg"> ';

			//If error or fen mismatch then immediately force refesh

        // Increment the idle time counter every minute.


		function timerIncrement() {
			idleTime = idleTime + 1;
			if (idleTime >= 1) { // 20 minutes
			idleTime=0;
				fetchdata(null);
				}
	
			}

//if thereiks no selection then use White as default
function ord(str){return str.charCodeAt(0);}
var jsondata=null, boardfen="",blackcankill=null,whitecankill=null;
var serverrequesttype="";
var whitecanfullmove=null, whitecanfullmove=null,boardtype =null,gamestatus=null, whitelist=null, BoardMoves= [];
var responsedata=null;
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

var category = null;var decodedc="",encodedc="";
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
							opt.textContent = data_coordinate_notation;

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
	if(showextramoves_opened==1){
		idleTime = 0;return;
	}
		var cookiedtempdata=getfendata().trim();
		var lookformoves="";
		serverrequesttype="";
		templg="";
		if((cog!==null) && (cog!="")) {templg= "&cog=1"}


		if((lg!==null) && (lg!="")) {templg= templg+"&lg="+lg;}
		var gameaction=document.getElementById("gamemode").getAttribute("gameaction");
		if( ((cookiedtempdata==movedfen) && (movedfen!=null) && (movedfen!="")) || ((cookiedtempdata!=movedfen) && (movedfen==null))) {movedfen="";} 
		////console.log (" cookiecategory = "+ cookiecategory);
		if((movedfen==null)){ movedfen=getfendata();}
		//debugger
		if((movedfen==null)){ //refresh the page because no move happend
				lookformoves=commonMove+"="+"&lookformoves=yes&"+nameEQ + encodedc+"&gameaction="+gameaction+templg;

		$.when(
				$.ajax({
						url: gameurl,
						type: 'post',
						data: lookformoves,
						async: true,
						success: function(responsedata){
							//parse the response data as per the json values;
							},
						complete:function(responsedata){
							//check the black vs white turn. If same turn then update the clock with correct value
							//in case of refresh page reload the counter etc
							// Perform operation on return value	
														
							jsondata = JSON.parse(responsedata);
							systemcs = jsondata.systemcs;
							boardfen = jsondata.fen;
							blackcankill= jsondata.blackcankill;
							whitecankill= jsondata.whitecankill;
							blackcanfullmove = jsondata.blackcanfullmove;
							whitecanfullmove = jsondata.whitecanfullmove;
							boardtype = jsondata.boardtype;
							gamestatus= jsondata.gamestatus;
							whitelist = jsondata.whitelist;
							BoardMoves = jsondata.Moves;
							serverrequesttype = jsondata.serverrequesttype;
							newgamestatus = jsondata.ng;
							}
					})
			).done(function () {
				if((boardfen!=null) && (boardfen!="")){
					document.getElementById("hiddenfen").value=boardfen;
					document.getElementById("hiddenfen").textContent=boardfen;
					document.getElementById("fen").value=boardfen;
					//debugger
					if(newgamestatus =="1"){
						//show the button to end game;							
						showcancelgameid();
					}
					else{
						document.removeEventListener('DOMContentLoaded',cancelgameid());
						var elementExists =document.getElementById('cancelgameID');
						if((elementExists!=null)&&(elementExists!=undefined)){
							document.getElementById('cancelgameID').remove();
						}
					}

					updateCookieAction(boardfen);
					updateoptions(1);
					updateCookieAction(boardfen);
					fillmoves(1);
					fentohtml();

					////myAudio.play();
				}

				if(serverrequesttype == "Drawn"){
					document.getElementsByName("board_mover")[0].textContent = "Game ended in a Draw";
				}

				idleTime = 0;
				if((serverrequesttype!=null) &&  ((serverrequesttype== "nrefresh"))){
					///if dom not loaded then load it..

					setTimeout(function() {
						deletemoves();
						fetchdata(null);
					}, 1000);

					//setInterval(10000);
				}
				idleTime = 0;
					});
			}
		else if (movedfen==""){// Already has the Fen Data  or start of the game
				movedfen=getfendata();
				document.getElementById("hiddenfen").value=movedfen;
				document.getElementById("hiddenfen").textContent=movedfen;
				document.getElementById("fen").value=movedfen;
				gameaction="";
				lookformoves=commonMove+"="+movedfen+"&"+nameEQ + encodedc+"&gameaction="+gameaction+templg;
				////debugger
				if((BoardMoves=="")||(BoardMoves==null)||(BoardMoves.length==0)){
				
			$.when(
				
				$.ajax({
					url: gameurl,
					type: 'post',
					data: lookformoves,
					async: true,
					success: function(responsedata){
						},
					complete: function(responsedata){
						
						////console.log (responsedata);
						jsondata = JSON.parse(responsedata.responseText);
						systemcs = jsondata.systemcs;
						boardfen = jsondata.fen;
						blackcankill= jsondata.blackcankill;
						whitecankill= jsondata.whitecankill;
						blackcanfullmove = jsondata.blackcanfullmove;
						whitecanfullmove = jsondata.whitecanfullmove;
						boardtype = jsondata.boardtype;
						gamestatus= jsondata.gamestatus;
						whitelist = jsondata.whitelist;
						BoardMoves = jsondata.Moves;
						serverrequesttype = jsondata.serverrequesttype;
						newgamestatus =jsondata.ng;
						movedfen=jsondata.fen;
					}
				})
				).done(function () {
						
						document.getElementById("hiddenfen").value=movedfen;
						document.getElementById("hiddenfen").textContent=movedfen;
						document.getElementById("fen").value=movedfen;	
						document.getElementById("boardtype").setAttribute("name",boardtype);

						//get systemcs as gameid
						var systemcsArr=systemcs.split(';');
						for(var lai=0;lai < systemcsArr.length;lai++) {

								if((systemcsArr[lai]!=null) && (systemcsArr[lai].includes('gid=')) ) {
									gid=decodedgameid=systemcsArr[lai].split('gid=')[1];
									}
							}			

							//debugger
							if(newgamestatus =="1"){
								//show the button to end game;							
								showcancelgameid();
							}
							else{
								document.removeEventListener('DOMContentLoaded',cancelgameid());
								var elementExists =document.getElementById('cancelgameID');
								if((elementExists!=null)&&(elementExists!=undefined)){
									document.getElementById('cancelgameID').remove();
								}							}
						updateCookieAction(boardfen);
						updateoptions(1);
						updateCookieAction(boardfen);
						fillmoves(1);
						fentohtml();

						if(serverrequesttype == "Drawn"){
							document.getElementsByName("board_mover")[0].textContent = "Game ended in a Draw";
						}
						
						idleTime = 0;

						if((serverrequesttype!=null) &&  ((serverrequesttype== "nrefresh"))){
							///if dom not loaded then load it..
							deletemoves();
							setTimeout(function() {
								fetchdata(null);
							}, 500);
						}
						idleTime = 0;
					});
				}
				else
				{	
					movedfen=getfendata();
					document.getElementById("hiddenfen").value=movedfen;
					document.getElementById("hiddenfen").textContent=movedfen;
					document.getElementById("fen").value=movedfen;	
					document.getElementById("boardtype").setAttribute("name",boardtype);

					//get systemcs as gameid
					var systemcsArr=systemcs.split(';');
					for(var lai=0;lai < systemcsArr.length;lai++) {

						if((systemcsArr[lai]!=null) && (systemcsArr[lai].includes('gid=')) ) {
							gid=decodedgameid=systemcsArr[lai].split('gid=')[1];
							}
						}
						//debugger
						if(newgamestatus =="1"){
							//show the button to end game;							
							showcancelgameid();
						}
						else {
							document.removeEventListener('DOMContentLoaded',cancelgameid());
							var elementExists =document.getElementById('cancelgameID');
							if((elementExists!=null)&&(elementExists!=undefined)){
								document.getElementById('cancelgameID').remove();
							}
						}
					updateCookieAction(boardfen);
					updateoptions(1);
					updateCookieAction(boardfen);

					fillmoves(1);
					fentohtml();
						if(serverrequesttype == "Drawn"){
							document.getElementsByName("board_mover")[0].textContent = "Game ended in a Draw";
						}
					idleTime = 0;			
					//myAudio.play();
				}
			}
		else {
				gameaction="";
				lookformoves=commonMove+"="+movedfen+"&"+nameEQ + encodedc+"&gameaction="+gameaction+templg;
				//boardfen="";
				BoardMoves= null;
				//fentohtml();
				//updateoptions(0);
				//fillmoves( 0);
				$.when(
				$.ajax({
						url: gameurl,
						type: 'post',
						data: lookformoves,
						async: true,
						success: function(responsedata){
								//check the black vs white turn. If same turn then update the clock with correct value
								//in case of refresh page reload the counter etc
								// Perform operation on return value
								//parse the response data as per the json values;
							},
						complete:function(responsedata){
								jsondata = JSON.parse(responsedata.responseText);
								systemcs = jsondata.systemcs;
								boardfen = jsondata.fen;
								blackcankill= jsondata.blackcankill;
								whitecankill= jsondata.whitecankill;
								blackcanfullmove = jsondata.blackcanfullmove;
								whitecanfullmove = jsondata.whitecanfullmove;
								boardtype = jsondata.boardtype;
								gamestatus= jsondata.gamestatus;
								whitelist = jsondata.whitelist;
								BoardMoves = jsondata.Moves;
								serverrequesttype = jsondata.serverrequesttype;
								newgamestatus = jsondata.ng;

								}
					})).done( function(){
						if((boardfen!=null) && (boardfen!="")){
							document.getElementById("hiddenfen").value=boardfen;
							document.getElementById("hiddenfen").textContent=boardfen;
							document.getElementById("fen").value=boardfen;
							document.getElementById("boardtype").setAttribute("name",boardtype);

							document.getElementById("blackcankill").value=blackcankill;
							document.getElementById("whitecankill").value=whitecankill;
							document.getElementById("blackcanfullmove").value=blackcanfullmove;
							document.getElementById("whitecanfullmove").value=whitecanfullmove;
							//debugger
							if(newgamestatus =="1"){
								//show the button to end game;							
								showcancelgameid();
							}
							else{
								document.removeEventListener('DOMContentLoaded',cancelgameid());
								var elementExists =document.getElementById('cancelgameID');
								if((elementExists!=null)&&(elementExists!=undefined)){
									document.getElementById('cancelgameID').remove();
								}
							}

							updateCookieAction(boardfen);	
							updateoptions(1);		
							updateCookieAction(boardfen);

							fillmoves(1);
							fentohtml();

							//myAudio.play();
						}
						if(serverrequesttype == "Drawn"){
							document.getElementsByName("board_mover")[0].textContent = "Game ended in a Draw";
						}

						idleTime = 0;
	
						if((serverrequesttype!=null) &&  ((serverrequesttype== "nrefresh"))){
							///if dom not loaded then load it..
							deletemoves();
							setTimeout(function() {
								fetchdata(null);
							}, 500);
						}
						idleTime = 0;
					});

					var idleInterval = setTimeout(timerIncrement, 500); // 6 seconds

					// Zero the idle timer on mouse movement.
					$(this).mousemove(function (e) {
						idleTime = 0;
					});
					$(this).keypress(function (e) {
						idleTime = 0;
					});

			}

}	
//if thereiks no selection then use White as default

function deletemoves(){
	BoardMoves=null;
	if (document.contains(document.getElementById("all_moves"))) {
		$(commonAllMoves).find('option').remove().end();
	}
}
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
			//deletemoves();
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
					all_movesoption.setAttribute("cmove",BoardMoves[optionmoves].CMove);
					all_movesoption.setAttribute("data_coordinate_notation",BoardMoves[optionmoves].data_coordinate_notation);
					all_movesoption.textContent=BoardMoves[optionmoves].data_coordinate_notation;

					if($("#"+ commonAllMoves+ " option[data_coordinate_notation='"+ BoardMoves[optionmoves].data_coordinate_notation+"']").length == 0){ 
						////debugger
						all_moves_select.appendChild(all_movesoption); }
					///update the piecemoves here

					//if(data_coordinate_notation.substr(0,1)=='^') {	piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
					//else
					if(data_coordinate_notation.substr(0,1)=='*') { piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
					else if(data_coordinate_notation.substr(0,1)=='>') { piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
					else if(data_coordinate_notation.substr(0,1)=='-') { piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
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

			if(document.getElementById("hiddenfen").textContent.trim()!="") {	fentohtml(); }
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

	/*var list, index;
	list = document.getElementsByClassName("pbsvg");
	for (index = 0; index < list.length; ++index) {
		list[index].setAttribute('style','height:'+svgheight+', width:'+svgwidth);
	}
	*/
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
		var dataa = txt;$(this).data('coordinate_notation');
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

	String.prototype.hex2bin = function ()
	{
	
	  var i = 0, l = this.length - 1, bytes = []
	
	  for (i; i < l; i += 2)
	  {
		bytes.push(parseInt(this.substr(i, 2), 16))
	  }
	
	  return String.fromCharCode.apply(String, bytes)   
	
	}
	
	String.prototype.bin2hex = function ()
	{
	
	  var i = 0, l = this.length, chr, hex = ''
	
	  for (i; i < l; ++i)
	  {
	
		chr = this.charCodeAt(i).toString(16)	
		hex += chr.length < 2 ? '0' + chr : chr
	  }
	
	  return hex
	
	}

	String.prototype._hexEncode = function(){
		var hex, i;
	
		var result = "";
		for (i=0; i<this.length; i++) {
			hex = this.charCodeAt(i).toString(16);
			result += ("000"+hex).slice(-4);
		}
	
		return result
	}
		
	String.prototype._hexDecode = function(){
		var j;
		var hexes = this.match(/.{1,4}/g) || [];
		var back = "";
		for(j = 0; j<hexes.length; j++) {
			back += String.fromCharCode(parseInt(hexes[j], 16));
		}
	
		return back;
	}
	
	function getfendata(){

		var ca = document.cookie.split(';');
		var splitagain=null;
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') 
				c = c.substring(1,c.length);
			
			if (c.indexOf(nameEQ) == 0){
				encodedc=c.substring(nameEQ.length,c.length)
				//leftover=atob(encoded_data);
				leftover=(encodedc).hex2bin();

				if(leftover!=null) {
					leftoverArr=leftover.split(';');
					cookiecategory ="";
					cookiefen="";
					gid="";
					for(var lai=0;lai < leftoverArr.length;lai++) {
						if((leftoverArr[lai]!=null) && (leftoverArr[lai].includes('cc=')) ) {
							cookiecategory=leftoverArr[lai].split('cc=')[1];
						}
						else if((leftoverArr[lai]!=null) && (leftoverArr[lai].includes('fen=')) ) {
							cookiefen=leftoverArr[lai].split('fen=')[1];
						}
						else if((leftoverArr[lai]!=null) && (leftoverArr[lai].includes('gid=')) ) {
							b64gid=leftoverArr[lai].split('gid=')[1];
							//gid=atob(b64gid);
							gid=(b64gid).hex2bin();
							if(gid.length>2){
									if(gid.substring(0,2)=="SY") {lg="SY";}
									else if((gid.substring(0,2)=="LM")||(gid.substring(0,2)=="SR")) {lg="SR";}
								}						
							}
						}
				}
				break;
			}
				////console.log (c.substring(nameEQ.length,c.length));
		}

		//
		//console.log (cookiecategory);
		var tempcookiefen="13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1";
		//cookiefen = document.cookie.split(';fen=');
	
	if((cookiefen!="") && (cookiefen!=null)) { movedfen=cookiefen;/*cookiefen[1].split(';fen=');*/ }
	
		//if(splitagain!=null) {cookiefen = ";fen="+splitagain[0]; tempcookiefen=splitagain[0];}
		else if(cookiefen==null){movedfen=tempcookiefen;}
	
		if((document.getElementById("hiddenfen").value!="") || (tempcookiefen!=""))
			movedfen=tempcookiefen;
		return movedfen;
	}

$('#perft').click(function(){
	window.location.href = 'perft.php?fen='  + $('#fen').val();
});


	function createCookieAction() {
		var createcookie=1;
		var boardtype=document.getElementById("boardtype").getAttribute("name");

		if ((boardtype=="black") && (document.getElementById("gamemode").getAttribute("gamemode")=="livemove")) { category= cookiecategory="black";
				//blackgameid="LMT4Jd3kjcaRt7NhHnempEfAr;blackmover=RtI34beb6f92f6f98cd4b004030bb35ca034"
				gid=document.getElementById("gamemode").getAttribute("blackgameid");
				//Also add the latest fen
			}
	
		//cc=white;fen=fen;gid=;
		var ca = document.cookie.split(';');
		var splitagain=null;
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];var b64gid="";
			while (c.charAt(0)==' ') {
				c = c.substring(1,c.length);
			}
			
			if (c.indexOf(nameEQ) == 0){
				encodedc=c.substring(nameEQ.length,c.length)
				//leftover=atob(encodedc);
				leftover=(encodedc).hex2bin();
				//leftover=leftover.substring(nameEQ.length,leftover.length);
				if(leftover!=null) {
					leftoverArr=leftover.split(';');
					cookiecategory ="";
					cookiefen="";
					gid="";
					for(var lai=0;lai < leftoverArr.length;lai++) {
						if((leftoverArr[lai]!=null) && (leftoverArr[lai].includes('cc=')) ) {
							cookiecategory=leftoverArr[lai].split('cc=')[1];
						}
						else if((leftoverArr[lai]!=null) && (leftoverArr[lai].includes('fen=')) ) {
							cookiefen=leftoverArr[lai].split('fen=')[1];
						}
						else if((leftoverArr[lai]!=null) && (leftoverArr[lai].includes('gid=')) ) {
							b64gid=leftoverArr[lai].split('gid=')[1];
							//gid=atob(b64gid);
							gid=(b64gid).hex2bin();
							}
						}
						//debugger
					 decodedsystemcs="";decodedgameid="";
					if(((systemcs!=null) && (systemcs!=""))|| ((gid!=null) && (gid!=""))) {
						var tempgid=(gid);
						if((tempgid!=null) && (tempgid!="")){
								createcookie=0;
								return;
							}
						decodedsystemcs=gid;
						if((decodedsystemcs!=null) && (decodedsystemcs!="")) {							
							decodedgameid=decodedsystemcs.split(';')[0];	
							if((decodedgameid!=null) && (decodedgameid!="")){
								decodedgameid=decodedgameid.split('gid=')[1];
							}
							else {decodedgameid="";}
								
						}
						if (boardtype=="black") { cookiecategory="black";} 

						if(cookiecategory=="white"){
							cookiemover=decodedsystemcs.split(';')[1];
							cookiemover=cookiemover.split('whitemover=')[1];
							if((cookiemover!=null) && (cookiemover!="")){
								encodedgameid=decodedgameid+";"+"whitemover="+cookiemover;
							}
						}
						else if(cookiecategory=="black"){
							decodedsystemcs=gid;
							cookiemover=decodedsystemcs.split(';')[1];
							cookiemover=cookiemover.split('blackmover=')[1];
							if((cookiemover!=null) && (cookiemover!="")){
								encodedgameid=decodedgameid+";"+"blackmover="+cookiemover;
							}					
						}
	
						var tempgid =encodedgameid;
						createcookie=0;

						if(decodedgameid.length>2){
							if(decodedgameid.substring(0,2)=="SY") {lg="SY";}
							else if((decodedgameid.substring(0,2)=="LM")||(decodedgameid.substring(0,2)=="SR")) {lg="SR";}
						}
						if((tempgid!=="") &&(tempgid!=null))
							gid = (tempgid);
						else if((gid=="") || (gid==null))
							gid = "";						
					}
				}
				break;
			}
				////console.log (c.substring(nameEQ.length,c.length));
		}

		//console.log (cookiecategory);
		//cookiefen = document.cookie.split(';fen=');

		if	(createcookie==1){
				var tempcookiefen="13ia31/cmhgsnghmc/1pppppppp1/181/c3cc3c/C3CC3C/181/1PPPPPPPP1/CMHGNSGHMC/13AI31 ~bwc w0 () - 0 1";
				if((cookiefen==null)||(cookiefen=="")) {cookiefen=tempcookiefen;}
		
				document.getElementById("hiddenfen").value=	cookiefen;
				document.getElementById("hiddenfen").textContent=cookiefen;
				document.getElementById("fen").value=cookiefen;
		
				//
				if ((boardtype=="black") && (document.getElementById("gamemode").getAttribute("gamemode")=="livemove")) { category= cookiecategory="black";
						//blackgameid="LMT4Jd3kjcaRt7NhHnempEfAr;blackmover=RtI34beb6f92f6f98cd4b004030bb35ca034"
						gid=document.getElementById("gamemode").getAttribute("blackgameid");
						//Also add the latest fen
					} 
				else{
						var buttonvalue = document.getElementById("Invert").name;
						if((buttonvalue==null) ||(buttonvalue=="")){
								if((cookiecategory==null) ||(cookiecategory=="")){
										document.getElementById("Invert").name="white";	category="white"; buttonvalue="white";
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
										document.getElementById("Invert").name="white";	category="white"; buttonvalue="white";
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
										document.getElementById("Invert").name="white";	category="white"; buttonvalue="white";
									}
								else if((buttonvalue==null) ||(buttonvalue=="")){
										document.getElementById("Invert").name=category;
									}
								else {
										document.getElementById("Invert").name=buttonvalue;		
										category=buttonvalue;
									}
							}
					}
			
				//console.log (buttonvalue);
	
				var date = new Date();
				date.setTime(date.getTime()+(1*24*60*60*1000));
				var expires = "; expires="+date.toUTCString();
	
				date = new Date();
				date.setTime(date.getTime()+(1*24*60*60*1000));
				expires = "; expires="+date.toUTCString();
				$("input[name='whiterdo']").attr("checked",false);
				$("input[name='blackrdo']").attr("checked",true);
				//console.log ("   -----------------------  ");
				//encodedc=btoa("cc="+category+";fen="+cookiefen+";gid="+btoa(gid));

				if(decodedgameid.length>2){
					if(decodedgameid.substring(0,2)=="SY") {lg="SY";}
					else if((decodedgameid.substring(0,2)=="LM")||(decodedgameid.substring(0,2)=="SR")) {lg="SR";}
				}
				//console.log(" gid for lg = "+gid);
				encodedc=("cc="+category+";fen="+cookiefen+";gid="+(gid).bin2hex()).bin2hex();

				document.cookie = nameEQ + encodedc+" ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
				document.cookie = nameEQ + encodedc +expires+"; path=/"+";";
			}
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
	////debugger
	var htmldata=(document.getElementById("fen").value.trim());
	//1rnbqkbnr1/1pppppppp1/181/181/181/181/1PPPPPPPP1/1RNBQKBNR1 ~wcb w KQkq - 0 1
	var htmlgrid=htmldata.split("/",100);
	var tdata="";

	var bordersbreached=htmlgrid[htmlgrid.length-1].split(" ")[1];
	bordersbreached= bordersbreached.split(" ")[0];

	if((bordersbreached!=null) && (bordersbreached!="") ) {
		if(bordersbreached.includes("c"))
			commonborderopen=0;
		else 
			commonborderopen=1;	
		
		if(bordersbreached.includes("b"))
			bcastleborderopen=0;
		else 
			bcastleborderopen=1;

		if(bordersbreached.includes("w"))
			wcastleborderopen=0;
		else 
			wcastleborderopen=1;		

	}

	var mover=htmlgrid[htmlgrid.length-1].split(" ")[2];
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
	
	document.getElementsByName("board_mover")[0].textContent = moverstatus;
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
	var boardtype=document.getElementById("boardtype").getAttribute("name");  
	//var invertedboardtype=document.getElementById("Invert").name;  
	var rclass="",cclass="";		
	var svgimag=""; var psvgname=""; var lrowxtop = "";var rowdata="";
	var frowxbotldata="";

	if((boardtype=="")||(boardtype=="white")||(boardtype=="1")){ 
		  var lrow="<tr>"+ '<td name="'+position+'l" id ="xtopl" style="background-color:white;height:10px;width:10px;"></td>';
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
			var col=9, lrowxmiddle="",rowdata="",column_id="",square_color="";var middlerows="";middlerowdata=""; var row=0;var mod=0;
			
			for (var i=0;i<=htmlgrid.length-1;i++){
				  position='l';	  rowdata="";middlerowdata=""; row=0; tdata=htmlgrid[i];
				  if((col%2)==1) { mod=1;} else{mod=0;}	  
  
				  for (;row<=tdata.length-1;row++){	rclass="";	cclass="";
						  if ((row==0) && (((col==0) || (col==9)))) {
								x=ord('x');  square_color="naaglok";
							  if((col==0)) { square_color=square_color+'  naaglokbottomrowborder'};
							  if((col==9)) { square_color=square_color+'  naagloktoprowborder'};
							  }
						  else if((row==9) && ((col==0) || (col==9))) { 
							  x=ord('y'); square_color="naaglok";
							  if((col==0)) { square_color=square_color+' rightcolborder naaglokbottomrowborder'};
							  if((col==9)) { square_color=square_color+' rightcolborder naagloktoprowborder'};
							 }
						  else if ((row==0) || (row==9) && (((col>=1) && (col<=9))))  {
							  x=row+ord('a')-1;  square_color="whitetruce";
								if (row==0) {square_color=square_color+' leftcolborder';}
							  	if (row==9) {square_color=square_color+' rightcolborder';}
							  }
						  else if ((row>=1) && (row<9) && (((col==0) || (col==9))))  {
							  x=row+ord('a')-1;
							  if(col==0) {  if(wcastleborderopen!=1) {square_color="whitecastle bottomrowborder";} else {square_color="whitecastle";}	}	 
							  if(col==9) { if(bcastleborderopen!=1){square_color="blackcastle toprowborder";} else {square_color="blackcastle";}}
							  }			 					  
						  else if ((col>0) && (col<5) && (((row>0) && (row<9))))  {
								square_color="white";
							  
								x=row+ord('a')-1;  /*mod=Math.abs(1-mod);
								if (mod==1){square_color="black";} else {square_color="white";} */
							  }
						else if ((col>4) && (col<9) && (((row>0) && (row<9))))  {
								square_color="black";

								  x=row+ord('a')-1; /* mod=Math.abs(1-mod);
								  if (mod==1){square_color="black";} else {square_color="white";} */
								}							  
  
						  if (((row==0) || (row==9)) && (((col>=0) && (col<=9))))  {  
							  	if (row==0) { x=ord('x') ;square_color=square_color+' leftcolborder'} 
								else if (row==9){ x=ord('y');square_color=square_color+' rightcolborder'};}
  
						  chr_x=String.fromCharCode(x); var colid=chr_x+''+col; var octagonWrap="",octagonWrapInner="";  psvgname=myArray[tdata[row]]; 

						  //debugger
						     if((i==4)){ 
								 //debugger
								 if((colid=='d4')||(colid=='d5')||(colid=='e4')||(colid=='e5')) { octagonWrap= ' octagonWrap';
								 square_color='whitefort';
								} 
								 if((commonborderopen==0) && ((colid!='x5')&&(colid!='y5')   )) octagonWrap= octagonWrap+' bottomborder'; 
								}

								if((i==5)){ 
									if((colid=='d4')||(colid=='d5')||(colid=='e4')||(colid=='e5')) { octagonWrap= ' octagonWrap';
									square_color='whitefort';
								   }
								   if((commonborderopen==0) && ((colid!='x4') && (colid!='y4')   )) octagonWrap= octagonWrap+' topborder'; 
 								}

							if(commonborderopen==0) {
							if((colid=='x5')&&(lg=="SR")){ octagonWrap= ' topLTrucePalace';  } 	
							else  if((colid=='y5')&&(lg=="SR")){ octagonWrap= ' topRTrucePalace';  }
							else  if((colid=='x4')&& (lg=="SR")){ octagonWrap= ' bottomLTrucePalace';  }
							else  if((colid=='y4')&&(lg=="SR")){ octagonWrap= ' bottomRTrucePalace';  } }

							else if(commonborderopen==1) {
								if((colid=='x5')&&(lg=="SR")){ octagonWrap= ' topLTrucePalace';  } 	else  if((colid=='y5')&&(lg=="SR")){ octagonWrap= ' topRTrucePalace';  }
								else  if((colid=='x4')&&(lg=="SR")){ octagonWrap= ' bottomLTrucePalace';  } else  if((colid=='y4')&&(lg=="SR")){ octagonWrap= ' bottomRTrucePalace';  } }
							
						   if((colid=='d0')||(colid=='e0')||(colid=='d9')||(colid=='e9')) { octagonWrap= ' octagonWrap';}
						  if((colid=='x4')||(colid=='x5')||(colid=='y4')||(colid=='y5')) { octagonWrapInner= ' octagonT';} 
						  if((colid=='d0')||(colid=='e0')||(colid=='d9')||(colid=='e9')){ octagonWrapInner= ' octagonC';}				
						  if((colid=='e5')||(colid=='e4')||(colid=='d5')||(colid=='d4')){ octagonWrap= ' octagonWrap'; octagonWrapInner= ' octagonC';}				
  
						  if((colid=='d0')||(colid=='d9')){octagonWrap=octagonWrap+" RPalace";}
						  if((colid=='e0')||(colid=='e9')){octagonWrap=octagonWrap+" LPalace";}

						  if((colid=='d0')||(colid=='e0')){octagonWrap=octagonWrap+" BPalace";}
						  if((colid=='d9')||(colid=='e9')){octagonWrap=octagonWrap+" TPalace";}
						  
						  if((colid=='d4')||(colid=='d5')){octagonWrap=octagonWrap+" RPalace";}
						  if((colid=='e4')||(colid=='e5')){octagonWrap=octagonWrap+" LPalace";}

						  if((colid=='d4')||(colid=='e4')){octagonWrap=octagonWrap+" BPalace";}
						  if((colid=='d5')||(colid=='e5')){octagonWrap=octagonWrap+" TPalace";}
		
						  
						  if(commonborderopen==0) {
						     if((i==4)){ 
								 	if((colid=='d5')||(colid=='e5')) {  octagonWrap= octagonWrap+' bottomborder'; 
									}
								}

								if((i==5)){ 
									if((colid=='d4')||(colid=='e4')) { octagonWrap= octagonWrap+' topborder'; 
									}
 								}
							}


							middlerowdata=middlerowdata+'<td name="warz" id ="'+colid+'" class="'+square_color+octagonWrap+ rclass+cclass+'" style ="height:40px;width:40px;padding-top:2px; padding-bottom:2px;padding-left:2px;padding-right:2px;">'+ '<span class="draggable_piece'+ octagonWrapInner +'" draggable="true" style ="display:inline-block;padding-top:2px;padding-left:2px;" name="'+(tdata[row] || "").trim()+'">'+ (psvgname || "")+' </span></td>';
						}
					rowdata = middlerowdata+ '<td name="r'+col+'" id =r'+col+' class="truce" style="background-color:gray;height:40px;width:40px;font-size:10px"><span data-micromodal-trigger= "modal-2" class="nondraggable" draggable="false">'+col+'</span></td>';
					middlerows=middlerows+"<tr>"+'<td name="'+position+col+'" id ='+position+col+' class="truce" style="height:40px;width:40px;background-color:gray;font-size:10px"><span class="nondraggable" draggable="false">'+col+'</span></td>'+rowdata;
					col=col-1;
				}
  
			position="bot"; var frowxbot = '<td name="'+position+'l" id ="xbotl" style="background-color:white;height:10px;width:10px;"></td>';
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
	else if((boardtype=="black")||(boardtype=="2")){ 
		debugger
			  var lrow="<tr>"+ '<td name="'+position+'l" id ="ytopl" style="background-color:white;height:10px;width:10px;"></td>';
			  for ( var i = 9; i >=0; i-- ) {
					  if (i==9) {
						  x=ord('y'); style='gray;height:10px;width:40px;font-size:10px';
					  }
						tdata=htmlgrid[i];
						////console.log(tdata.length);
						if ((i>0) && (i<9)){
						  x=ord('a')+i-1; style='gray;height:10px;width:40px;font-size:10px';
					  }
						if(i==tdata.length+1){
						  x=ord('x'); style='gray;height:10px;width:40px;font-size:10px';
					  }

					  if (i==0) {
						  x=ord('x'); style='gray;height:10px;width:40px;font-size:10px';
					  }								 
						chr_x=String.fromCharCode(x); 
						rowdata=rowdata+ '<td name="castle" id ="'+chr_x+position+'" style="background-color:'+style+'"> <span class="nondraggable_piece" draggable="false" >'+chr_x+'</span></td>';
					}
				lrowxtop= '<td name="'+position+'r" id ="y'+position+'r" style="font-size:10px;background-color:white;height:10px;width:10px"></td>';
				var lrow=lrow+rowdata+ lrowxtop+"</tr>";	
				var col=0, lrowxmiddle="",rowdata="",column_id="",square_color=""; var middlerows="";middlerowdata=""; var row=9;var mod=0;
				for (var i=9;i>=0;i--){
					  position='l';	 rowdata="";middlerowdata="";  tdata=htmlgrid[i];row=9;
					  if((col%2)==1) { mod=1;} else{mod=0;}	  
	  
					  for (;row>=0;row--){ rclass="";	cclass="";
	  						  if ((row==9) && (((col==0) || (col==9)))) {
									x=ord('y'); square_color="naaglok";
									if((col==0)) { square_color=square_color+'  naagloktoprowborder'};
									if((col==9)) { square_color=square_color+'  naaglokbottomrowborder'};
								  }
							  else if((row==0) && ((col==0) || (col==9))) { 
								  x=ord('x'); square_color="naaglok";
								  if((col==0)) { square_color=square_color+' leftcolborder naagloktoprowborder'};
								  if((col==9)) { square_color=square_color+' leftcolborder naaglokbottomrowborder'};								  
								  }
							  else if ((row==0) || (row==9) && (((col>=1) && (col<=9))))  {
								  x=row+ord('a')-1; square_color="whitetruce";
								  	if (row==0) {square_color=square_color+' rightcolborder';}
							  		if (row==9) {square_color=square_color+' leftcolborder';}								  
								  }
							  else if ((row>=1) && (row<9) && (((col==0) || (col==9))))  {
								  x=row+ord('a')-1; 
								 
								 		if(col==0) {  if(bcastleborderopen!=1) {square_color="blackcastle toprowborder";} else {square_color="blackcastle";}	}	 
								  		if(col==9) { if(wcastleborderopen!=1){square_color="whitecastle bottomrowborder";} else {square_color="whitecastle";}}
								  }
							  /*else if ((row>0) && (row<9) && (((col>0) && (col<9))))  {
									x=row+ord('a')-1;  mod=Math.abs(1-mod); 
									if (mod==1){square_color="black";} else {square_color="white";} 
								  }*/
								else if ((col>0) && (col<5) && (((row>0) && (row<9))))  {
									square_color="black";
								  
									x=row+ord('a')-1;
								  }
							else if ((col>4) && (col<9) && (((row>0) && (row<9))))  {
									square_color="white";
	
									  x=row+ord('a')-1; 
									}
							if ((row==0) || (row==9) && (((col>=0) && (col<=9))))  {  
								if (row==0) { x=ord('y') ;square_color=square_color+' rightcolborder'} 
								else if (row==9){ x=ord('x');square_color=square_color+' leftcolborder'};}

								chr_x=String.fromCharCode(x); var colid=chr_x+''+col; var octagonWrap="",octagonWrapInner="";  psvgname=myArray[tdata[row]]; 

								//debugger
							   //if((i==5)){if(commonborderopen==0) octagonWrap= octagonWrap+' bottomborder'; }
							   if((i==5)){ 
								if((colid=='d4')||(colid=='d5')||(colid=='e4')||(colid=='e5')) { octagonWrap= ' octagonWrap'; square_color='blackfort';
							} 
								 if((commonborderopen==0) && ((colid!='x5')&&(colid!='y5')   )) octagonWrap= octagonWrap+' bottomborder'; 
							   }

							   if((i==4)){ 
								if((colid=='d4')||(colid=='d5')||(colid=='e4')||(colid=='e5')) { octagonWrap= ' octagonWrap';
								square_color='blackfort';
							   }
							   if((commonborderopen==0) && ((colid!='x5') && (colid!='y5')   )) octagonWrap= octagonWrap+' topborder'; 
							}			   
					   		
							   if(commonborderopen==0){
								if((colid=='x5')&&(lg=="SR")){ octagonWrap= ' topLTrucePalace'; }
								else if((colid=='y5')&&(lg=="SR")){ octagonWrap= ' topRTrucePalace'; }

								else  if((colid=='x4')&& (lg=="SR")){ octagonWrap= ' bottomLTrucePalace';  } 
								else  if((colid=='y4')&&(lg=="SR")){ octagonWrap= ' bottomRTrucePalace';  } }

							if(commonborderopen==1){
								if((colid=='x5')&&(lg=="SR")){ octagonWrap= ' bottomLTrucePalace'; }
								else  if((colid=='y5')&&(lg=="SR")){octagonWrap= ' bottomRTrucePalace';  }
								   else  if((colid=='x4')&&(lg=="SR")){ octagonWrap= ' topLTrucePalace';  }
								else  if((colid=='y4')&&(lg=="SR")){ octagonWrap= ' topRTrucePalace';  }
							 }

							  if((colid=='d0')||(colid=='e0')||(colid=='d9')||(colid=='e9')) { octagonWrap= ' octagonWrap';}
							 if((colid=='x4')||(colid=='x5')||(colid=='y4')||(colid=='y5')) { octagonWrapInner= ' octagonT';} 
							 if((colid=='d0')||(colid=='e0')||(colid=='d9')||(colid=='e9')){ octagonWrapInner= ' octagonC';}
							 if((colid=='d5')||(colid=='e4')||(colid=='d5')||(colid=='d9')){ octagonWrapInner= ' octagonWrap';octagonWrapInner= ' octagonC';}

							 if((colid=='d0')||(colid=='d9')){octagonWrap=octagonWrap+" RPalace";}
							 if((colid=='e0')||(colid=='e9')){octagonWrap=octagonWrap+" LPalace";}
   
							 if((colid=='d0')||(colid=='e0')){octagonWrap=octagonWrap+" TPalace";}
							 if((colid=='d9')||(colid=='e9')){octagonWrap=octagonWrap+" BPalace";}
							 
							 if((colid=='d4')||(colid=='e4')){octagonWrap=octagonWrap+" RPalace";}
							 if((colid=='e5')||(colid=='d5')){octagonWrap=octagonWrap+" LPalace";}
						
							if((colid=='d4')||(colid=='e4')){octagonWrap=octagonWrap+" TPalace";}
							if((colid=='d5')||(colid=='e5')){octagonWrap=octagonWrap+" BPalace";}
							
							if(commonborderopen==0) {
						     if((i==4)){ 
								 	if((colid=='d5')||(colid=='e5')) {  octagonWrap= octagonWrap+' topborder'; 
									}
								}

								if((i==5)){ 
									if((colid=='d4')||(colid=='e4')) { octagonWrap= octagonWrap+' bottomborder'; 
									}
 								}
							}
					
								middlerowdata=middlerowdata+'<td name="warz" id ="'+colid+'" class="'+square_color+octagonWrap+ rclass+cclass+ '" style ="height:40px;width:40px;padding-top:2px; padding-bottom:2px;padding-left:2px;padding-right:2px;">'+ '<span data-micromodal-trigger= "modal-2" class="draggable_piece'+ octagonWrapInner +'" draggable="true" style ="display:inline-block;padding-top:2px;padding-left:2px; " name="'+(tdata[row] || "").trim()+'">'+ (psvgname || "")+' </span></td>';
							}
						rowdata = middlerowdata+ '<td name="r'+col+'" id =r'+col+' class="truce" style="background-color:gray;height:40px;width:40px;font-size:10px"><span class="nondraggable" draggable="false">'+col+'</span></td>';
						middlerows=middlerows+"<tr>"+'<td name="'+position+col+'" id ='+position+col+' class="truce" style="height:40px;width:40px;background-color:gray;font-size:10px"><span class="nondraggable" draggable="false">'+col+'</span></td>'+rowdata;
						col=col+1;
					}
	  
				position="bot";
				var frowxbot = '<td name="'+position+'l" id ="xbotl" style="background-color:white;height:10px;width:10px;"></td>';
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
	systemcs;encodedgameid="";
	if(fendata.includes(';'))
		febdata=fendata.split(';')[0];
	//"gid=LMHRn3AmtjeT7pNEJhdrkacf4;whitemover=JFfRDc0c9f671dad01e6f3a93d131ca3eff0;blackmover=r4IC7c0c9f671dad01e6f3a93d131ca3eff0"
	var ca = document.cookie.split(';');
	var splitagain=null;
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') {
			c = c.substring(1,c.length);
		}
		
		if (c.indexOf(nameEQ) == 0){
			encodedc=c.substring(nameEQ.length,c.length)
			//leftover=atob(encodedc);
			leftover=(encodedc).hex2bin();

			//leftover=leftover.substring(nameEQ.length,leftover.length);
			if(leftover!=null) {
				leftoverArr=leftover.split(';');
				cookiecategory ="";
				cookiefen="";
				gid="";
				for(var lai=0;lai < leftoverArr.length;lai++) {
					if((leftoverArr[lai]!=null) && (leftoverArr[lai].includes('cc=')) ) {
						cookiecategory=leftoverArr[lai].split('cc=')[1];
					}
					else if((leftoverArr[lai]!=null) && (leftoverArr[lai].includes('fen=')) ) {
						cookiefen=leftoverArr[lai].split('fen=')[1];
					}
					else if((leftoverArr[lai]!=null) && (leftoverArr[lai].includes('gid=')) ) {
						b64gid=leftoverArr[lai].split('gid=')[1];
						//gid=atob(b64gid);
						gid=(b64gid).hex2bin();
						if((gid!="") && (gid!=null)){
							decodedgameid=gid.split(';')[0];
							}
						else if((systemcs!="") &&(systemcs!=null)){
							var systemcsArr=systemcs.split(';');
							for(var si=0;si < systemcsArr.length;si++) {
								if((systemcsArr[si]!=null) && (systemcsArr[si].includes('gid=')) ) {
									decodedgameid=b64gid=gid=systemcsArr[si].split('gid=')[1];
								}
								else if((systemcsArr[si]!=null) && (systemcsArr[si].includes('whitemover=')) ) {
									cookiemover=systemcsArr[si].split('whitemover=')[1];
								}
								else if((systemcsArr[si]!=null) && (systemcsArr[si].includes('blackmover=')) ) {
									cookiemover=systemcsArr[si].split('blackmover=')[1];
								}
							}	
						}
					}
				}	

				decodedsystemcs="";
				if((systemcs!=null) && (systemcs!="") && (b64gid!="")) {
					decodedsystemcs=systemcs;
					//decodedsystemcs=gid;
					//debugger
					if((decodedsystemcs!=null) && (decodedsystemcs!="")) {

						if((decodedgameid==null) || (decodedgameid=="")){
							decodedgameid="";
						}							
					}
					if(cookiecategory=="white"){
						cookiemover=decodedsystemcs.split(';')[1];
						cookiemover=cookiemover.split('whitemover=')[1];
						if((cookiemover!=null) && (cookiemover!="")){
							encodedgameid=decodedgameid+";"+"whitemover="+cookiemover;
						}
					}
					else if(cookiecategory=="black"){
						cookiemover=decodedsystemcs.split(';')[1];
						cookiemover=cookiemover.split('blackmover=')[1];
						if((cookiemover!=null) && (cookiemover!="")){
							encodedgameid=decodedgameid+";"+"blackmover="+cookiemover;
						}					
					}

					var tempgid =encodedgameid;
					if((tempgid!=="") &&(tempgid!=null))
						gid = (tempgid);
					else if((gid=="") || (gid==null))
						gid = "";						
				}
			}

			if(decodedgameid.length>2){
				if(decodedgameid.substring(0,2)=="SY") {lg="SY";}
				else if((decodedgameid.substring(0,2)=="LM")||(decodedgameid.substring(0,2)=="SR")) {lg="SR";}
			}

			break;
		}
			////console.log (c.substring(nameEQ.length,c.length));
	}
	//console.log (cookiecategory);

	if((fendata!="") &&(fendata!=null)) {
		cookiefen=fendata;
	}
	
	document.getElementById("hiddenfen").value=	fendata;
	document.getElementById("hiddenfen").textContent=fendata;
	document.getElementById("fen").value=fendata;
	document.getElementById('WhiteGameID_Data').textContent= "Game ID = "+ decodedgameid;
	document.getElementById('WhiteGameFEN_Data').textContent="Current FEN = "+fendata;

	cookiefen=fendata;
	//
	var buttonvalue = document.getElementById("Invert").name;
	if((buttonvalue==null) ||(buttonvalue=="")){
		if((cookiecategory==null) ||(cookiecategory=="")){
			document.getElementById("Invert").name="white";	category="white"; buttonvalue="white";
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
			document.getElementById("Invert").name="white"; category="white"; buttonvalue="white";
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

	var date = new Date();
	date.setTime(date.getTime()+(1*24*60*60*1000));
	var expires = "; expires="+date.toUTCString();
	date = new Date();
	date.setTime(date.getTime()+(1*24*60*60*1000));
	expires = "; expires="+date.toUTCString();
	$("input[name='whiterdo']").attr("checked",false);
	$("input[name='blackrdo']").attr("checked",true);

	//encodedc=btoa("cc="+category+";fen="+cookiefen+";gid="+btoa(gid));
	
	//calculate lg
	console.log(" Update Cookie Action = gid for lg = "+decodedgameid);
				if(decodedgameid.length>2){
					if(decodedgameid.substring(0,2)=="SY") {lg="SY";}
					else if((decodedgameid.substring(0,2)=="LM")||(decodedgameid.substring(0,2)=="SR")) {lg="SR";}
				}
	encodedc=("cc="+category+";fen="+cookiefen+";gid="+(gid).bin2hex()).bin2hex();

	document.cookie = nameEQ + encodedc+" ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	if(cancelgame!=1)
		document.cookie = nameEQ + encodedc +expires+"; path=/"+";";
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

		if(document.getElementById("hiddenfen").textContent.trim!="") 
		fentohtml();
	}

$(document).ready(function(){
	//alert(window.location);
	var url = new URL(window.location);
	var lgurl=url.searchParams.get("lg");
	var cogurl=url.searchParams.get("cog");
	if((cogurl==undefined)||(cogurl==null)) {cogurl="";}

	/* send the function to php server to check if game is idle */
	//* if yes then result will call the js function to update the cache and revert tothe main page
	if(cogurl=="1"){

	}

	if((lgurl==undefined)||(lgurl==null)) {lgurl=1;}
	
	if(lg==""){ lg = lgurl;
			if((lgurl==1)||(lgurl.toLowerCase()=="1")) {lg="SY";}
			else if (lgurl==2) { lg="SR" }
			else lg="SR";
		console.log("************************ LG ="+lg);	
		}

	category=null;
		
		myAudio=document.createElement('audio');

		if(document.getElementById("gamemode").getAttribute("gamemode")=="livemove") {myAudio.src = '../assets/move.mp3'; gamemode="livemode"; gameurl="../liveviews/"; commonMove= "livemove"; commonAllMoves = "livemoves" ; nameEQ = "livegameid" + "="; }
		else { gamemode="localmode"; nameEQ = "LocalStepType" + "="; gameurl="../views/"; commonMove= "move";  commonAllMoves = "localmoves" ;
		myAudio.src = './assets/move.mp3';

		myAudio.controls = true;
		myAudio.muted=true;
		document.body.appendChild(myAudio);

		$('#livepairing').on('click', function () {
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
							//debugger
					}
					else if(($("div.status_box").attr('id')=='2')){
							color_to_move='black';opp_color_to_move='white';
							//debugger
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
							var dataa = txt;// $(this).data('coordinate_notation');
							txt=txt.trim();
							//if(txt.substr(1,1)=='^') {tname=txt.substr(2,2); mname=txt.substr(4,2); dname=txt.substr(6,2)}
							//else 
							//debugger
							if(txt.substr(1,1)=='*') { tname=txt.substr(2,2); dname=txt.substr(4,2)}
							else if(txt.substr(1,1)=='>') { tname=txt.substr(2,2); dname=txt.substr(4,2)}
							else if(txt.substr(1,1)=='-') { tname=txt.substr(2,2); dname=txt.substr(4,2)}
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
											document.getElementById('lblSandhi').textContent="Sandhi";
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
													document.getElementById('lblSandhi').textContent="Viraam Sandhi";
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
												document.getElementById('lblShanti').textContent="Shanti";
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
													document.getElementById('lblShanti').textContent="Shanti";
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
													document.getElementById('lblShanti').textContent="Viraam Shanti Sandhi";
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
														document.getElementById('lblViraam').textContent="Viraam";
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
													document.getElementById('lblViraam').textContent="Viraam";
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
			else {	/*

					document.querySelectorAll('select#'+commonMove).forEach(eventitem => {
						eventitem.addEventListener('dblclick', event => {
									//
									//$('#make_move').submit();
									//
									////myAudio.play();
									movedfen=$('select[name='+ commonMove+'] option').filter(':selected').val();
									var elements = document.getElementById(commonMove).options;
									for(var i = 0; i < elements.length; i++){
											  elements[i].selected = false;
										}
									////myAudio.play();
									fetchdata(movedfen);
								});
						});

					document.querySelectorAll('select#cmove').forEach(eventitem => {
						eventitem.addEventListener('dblclick', event => {	
									////myAudio.play(); 
									movedfen=$('select[name=cmove] option').filter(':selected').val();
									var elements = document.getElementById("cmove").options;
									for(var i = 0; i < elements.length; i++){
											  elements[i].selected = false;
										}						
									//$('#naarad_cmove').submit(); 
									////myAudio.play();
									fetchdata(movedfen);

								});
						});

					document.querySelectorAll('select#surrendermove').forEach(eventitem => {
						eventitem.addEventListener('dblclick', event => {	
									////myAudio.play(); 
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
									////myAudio.play(); 
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
					*/}

				if(serverrequesttype== "nrefresh"){
					deletemoves();	
					event.preventDefault();
				}		
		});	

		$('table').on("dragstart", function (event) {
			dt = event.originalEvent.dataTransfer;
					dt.setData('Text', $(this).attr('id'));

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
							////debugger
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
											////debugger
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
		
											if ((($("div.status_box").attr('id')=='1')&& (p1name.match(/^[A-ZÁ]*$/))) || (($("div.status_box").attr('id')=='2')&& (p1name.match(/^[a-zá]*$/)))) {
													$("div#textAreasRules").show();
													//console.log(pieceMoves[ppiecesquare.id]);
													OriginalCell= eventitem;
													$("#"+commonAllMoves+" option").each(function() {
															var val = $(this).val();
															var txt = $(this).html();
															var dataa = txt;// $(this).data('coordinate_notation');
															txt=txt.trim();
															//if(txt.substr(1,1)=='^') {tname=txt.substr(2,2); mname=txt.substr(4,2); dname=txt.substr(6,2)}
															//else 
															if(txt.substr(1,1)=='*') { tname=txt.substr(2,2); dname=txt.substr(4,2)}
															else if(txt.substr(1,1)=='>') { tname=txt.substr(2,2); dname=txt.substr(4,2)}
															else if(txt.substr(1,1)=='-') { tname=txt.substr(2,2); dname=txt.substr(4,2)}
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
																			document.getElementById('lblSandhi').textContent="Sandhi";
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
																					document.getElementById('lblSandhi').textContent="Viraam Sandhi";
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
																					document.getElementById('lblSandhi').textContent="Viraam Sandhi";
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
																				document.getElementById('lblShanti').textContent="Shanti";
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
																					document.getElementById('lblShanti').textContent="Shanti";
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
										
									if(serverrequesttype== "nrefresh"){
										deletemoves();
										event.preventDefault();
									}
				//event.preventDefault();
				
			});	

		$('table').bind('selectstart', function(event) {
					//event.preventDefault();
					if((serverrequesttype== "nrefresh")||(serverrequesttype== "refresh")) {
						event.preventDefault();
											}
			});

		//$('table').on("dragenter dragover drop", function (event,ui ) {	
		$('table').on("dragover drop", function (event,ui ) {	

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
				////debugger	
				if(event.target.nodeName=="TD"){
						 sample=event.target.querySelector('.draggable_piece'); if (sample==null) {item=null;} else if (sample.nodeName=="SPAN"){ item=sample; } else {item=null;}
					}
				else if((event.target.nodeName=="SPAN")  && (event.target.className=="draggable_piece")){ item=event.target; } 
				else if((event.target.nodeName.toLowerCase()=="img")  && (event.target.parentNode.className=="draggable_piece")){ item=event.target.parentNode; } else {item=null;}
	
				if(item==null) {$("#"+ commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();	}
				else if(item.getAttribute("name")==""){$("#"+ commonMove).empty(); $("#winninggamemove").empty(); $("#endgamemove").empty(); $("#surrendermove").empty();$("#Shantimove").empty();$("#recallmove").empty();	}
				else if(item!=null){
						item.classList.add("currentpiece");
						}

				//$(eventitem).closest('td').attr('id');
				oldsquare = dt;
				oldsquare = $(OriginalCell).closest('td').attr('id');;
				newsquare = $(item).closest('td').attr('id');
				option_tag_in_select_tag = [];
				optioncount=-1;
				debugger

				if(oldsquare!== newsquare){
						
					coordinate_notation = p1name+oldsquare + newsquare;
					//console.log(coordinate_notation);
					tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
				
					if(tempvalue.length > 0){ //check the demotion piece
						option_tag_in_select_tag.push(tempvalue);
			
						if(tempvalue.length > 1){ //check the demotion piece

								coordinate_notation = p1name+oldsquare + newsquare+'=';
								//console.log(coordinate_notation);
								tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
			
								if(tempvalue.length > 0){ //check the demotion piece
									option_tag_in_select_tag.push(tempvalue);;
								}
						
								coordinate_notation = p1name+oldsquare + newsquare+'≡';
								//console.log(coordinate_notation);
								tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
			
								if(tempvalue.length > 0){ //check the demotion piece
									option_tag_in_select_tag.push(tempvalue);;
								}
		
								coordinate_notation = p1name+oldsquare + newsquare+'+';
								//console.log(coordinate_notation);
								tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
						
								if(tempvalue.length > 0){ //check the demotion piece
									option_tag_in_select_tag.push(tempvalue);;
								}
						}		

					}
				}
				//optioncount=optioncount+tempvalue.length;
				//option_tag_in_select_tag[0] = tempvalue;
				coordinate_notation = p1name+'*'+oldsquare + newsquare;
				//console.log("** "+coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
				
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag.push(tempvalue);
				}

				//optioncount=optioncount+tempvalue.length;
				//option_tag_in_select_tag[0] = tempvalue;
				coordinate_notation = p1name+'-'+oldsquare + newsquare+oldsquare+"<P";
				//console.log("** "+coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
				
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag.push(tempvalue);
				}


				coordinate_notation = p1name+'-'+oldsquare + newsquare+ oldsquare+"<p";;
				//console.log("** "+coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
				
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag.push(tempvalue);
				}
				
				coordinate_notation = p1name+'-'+oldsquare + newsquare;
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation$='>P']");
				
				if(tempvalue.length > 0){ //check the demotion piece
					if((tempvalue[0].getAttribute("data_coordinate_notation")!=null) && (tempvalue[0].getAttribute("data_coordinate_notation")!=undefined) &&  (tempvalue[0].getAttribute("data_coordinate_notation").includes(coordinate_notation)) ){
						option_tag_in_select_tag.push(tempvalue);
					}
				}

				coordinate_notation = p1name+'-'+oldsquare + newsquare;

				//console.log("** "+coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation$='>p']");
				//debugger
				if(tempvalue.length > 0){ //check the demotion piece
					if((tempvalue[0].getAttribute("data_coordinate_notation")!=null) && (tempvalue[0].getAttribute("data_coordinate_notation")!=undefined) &&  (tempvalue[0].getAttribute("data_coordinate_notation").includes(coordinate_notation)) ){
						option_tag_in_select_tag.push(tempvalue);
					}
				}
			
				coordinate_notation = p1name+'*'+oldsquare + newsquare+'+';
				//console.log(coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
	
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag.push(tempvalue);;
				}
	
				//example m^e5e4e3
				coordinate_notation = p1name+'>'+oldsquare + newsquare;
				//console.log(coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
	
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag.push(tempvalue);
				}

				coordinate_notation = p1name+'>'+oldsquare + newsquare+'+';
				//console.log(coordinate_notation);
				tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation^='" + coordinate_notation + "']");
	
				if(tempvalue.length > 0){ //check the demotion piece
					option_tag_in_select_tag.push(tempvalue);
				}
	
				//document.addEventListener("DOMContentLoaded", showextramoves(option_tag_in_select_tag));
				showextramoves_opened=1;
				var ele = document.getElementsByClassName("radio_moves");

				if(ele !==null){
					while(ele.length > 0){
						ele[0].parentNode.removeChild(ele[0]);	
					}
				}
		
				//coordinate_notation = p1name+'*'+oldsquare + newsquare+'+';
				////console.log(coordinate_notation);
				//tempvalue=$("form[name='all_moves'] select[name='"+commonMove+"'] option[data_coordinate_notation='" + coordinate_notation + "']");
				if((ele==null) || (ele.length==0)){
					optioncount=option_tag_in_select_tag.length;
					if (( optioncount != 0 )) {				
						selectedoption=0;
						if(optioncount>0){
							//document.querySelector('item.currentpiece').addEventListener('drop', function() {
  							  //});							  
							  showextramoves_opened=1;
							  showextramoves(option_tag_in_select_tag);
							}

							if((optioncount==0)){
								//debugger
									tempvalue=option_tag_in_select_tag[0];
									movedfen=tempvalue[0].value;
			
										//movedfen=$('select[id=all_moves] option').filter(':selected').val();
										var elements = document.getElementById(commonAllMoves).options;
										for(var i = 0; i < elements.length; i++){
												  elements[i].selected = false;
											}
										showextramoves_opened=0;
										deletemoves();
										fetchdata(movedfen);
										option_tag_in_select_tag=[]; 
										optioncount=-1;
										selectedoption=-1;
										OriginalCell=null;			
										dt = null;
										if(serverrequesttype== "nrefresh"){
											deletemoves();
											event.preventDefault();
										}
									}
								else{								
									tempinterval= setInterval(function (){ 
									//debugger
									optioncount=option_tag_in_select_tag.length;
									if((optioncount>0) &&(showextramoves_opened==0)){
										//debugger
										if(selectedoption>=0){
											tempvalue=option_tag_in_select_tag[selectedoption];
											movedfen=tempvalue[0].value;
										}
										//movedfen=$('select[id=all_moves] option').filter(':selected').val();
										var elements = document.getElementById(commonAllMoves).options;
										for(var i = 0; i < elements.length; i++){
												  elements[i].selected = false;
											}
										//$('#king_surrender').submit();
										deletemoves();
										fetchdata(movedfen);
										option_tag_in_select_tag=[]; 
										optioncount=-1;
										selectedoption=-1;
										OriginalCell=null;			
										dt = null;
									}
								if(serverrequesttype== "nrefresh"){
									deletemoves();
									event.preventDefault();
								}

								if(showextramoves_opened==0)
									clearInterval(tempinterval);
	
							},1000);
						}
						//option_tag_in_select_tag=[]; 

					if(serverrequesttype== "nrefresh"){
						deletemoves();
						event.preventDefault();
						}
					}
					}
				}	
				rulesdescription(color_to_move, piecemovetype);
								
	});	

		movedfen=null;
		fetchdata(movedfen);	

		
				var all_moves = document.getElementById('all_moves');
				all_moves.disabled = false;all_moves.readOnly = false;all_moves.setAttribute("style","display:none;");
				all_moves.setAttribute("name","all_moves");all_moves.setAttribute("id","all_moves");all_moves.setAttribute("type","hidden");all_moves.method = 'post';
				document.getElementById("divmoves").appendChild(all_moves);
		
				var all_moves_select = document.createElement('select');
				all_moves_select.setAttribute("name",commonMove);all_moves_select.setAttribute("id",commonAllMoves)
				all_moves_select.setAttribute("style","display:none;");all_moves_select.size="19";
				var blength=0;
				if(BoardMoves==null) {blength=0;}
				
				document.getElementById("all_moves").innerHTML='All Legal Moves:<br> <select id="'+commonAllMoves+'" name="'+ commonMove+'" size="19"></select><br>	Move Count:'+ blength+'><br> <input id="boardtype" name="boardtype" hidden value="">';
	
				var all_movesoption = null;
				all_moves_select=document.getElementById(commonAllMoves);
				var piecename="",piecemove="",squarename="",	fen="",data_coordinate_notation="", pieceset=[];			
	
				if(BoardMoves!=null){
					for (var optionmoves=0;optionmoves<BoardMoves.length;optionmoves++){
						//console.log("***********************")
						data_coordinate_notation=BoardMoves[optionmoves].data_coordinate_notation;
						
						all_movesoption = document.createElement('option');
						all_movesoption.value = BoardMoves[optionmoves].option;all_movesoption.readOnly = false;
						all_movesoption.setAttribute("data_coordinate_notation",BoardMoves[optionmoves].data_coordinate_notation);

						all_movesoption.textContent=BoardMoves[optionmoves].data_coordinate_notation;
						all_moves_select.appendChild(all_movesoption);
						///update the piecemoves here
	
						//if(data_coordinate_notation.substr(0,1)=='^') {	piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
						//else 
						if(data_coordinate_notation.substr(0,1)=='*') { piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
						else if(data_coordinate_notation.substr(0,1)=='>') { piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
						else if(data_coordinate_notation.substr(0,1)=='-') { piecename=data_coordinate_notation.substr(1,1);squarename=data_coordinate_notation.substr(2,2); }
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
				if(decodedgameid.length != 0) { $('#WhiteGameID_Data').css('visibility', 'visible'); }
			});

		$('#uploadimages').on('click', function() {
				$("#dialog").dialog({
						modal: true
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

   			  //});
			  init();
			  document.addEventListener("DOMContentLoaded", showgameid());
			  //jQuery(document).ready(function(e) {
				//jQuery('#mymodal').trigger('click');
			//});
			
			//document.addEventListener("DOMContentLoaded", btn_additionalmoves());
			//document.addEventListener("DOMContentLoaded", cancel_additionalmoves());
			//document.addEventListener("DOMContentLoaded", clear_additionalmoves());


		});


		function btn_additionalmoves () {        
			var childdiv = null; var ele=null;
			showextramoves_opened=0;
			//debugger

			var div = $("div#modal-content");
			var movesele = div.find("div[id='additionalmoves']");

			for(var ii=0;ii<(movesele.length);ii=ii+1){
				div.attr("id", "additionalmoves"+ii);
			 }

			childdiv =div.find("div[name='additionalmoves']");
			if(childdiv!=null)
				{ ele = div.find("input[type='radio']");		
					for(var ii=0;ii<(ele.length);ii=ii+1){
						   if(ele[ii].checked == true) {selectedoption=ii;}
				 		  else ele[ii].checked = false;
						}
					$('div[name="additionalmoves"]').empty();
					$('#additionalmoves').empty();
				}
			modal.close('modalContent');
			fetchdata(null);		
		 };

		 function cancel_additionalmoves () { 

			$('div[name="additionalmoves"]').empty();
			$('#additionalmoves').empty();
			showextramoves_opened=0;
			selectedoption=-1;
			modal.close('modalContent');
			fetchdata(null);
		};

		 function clear_additionalmoves () {   
			 //debugger     

			 var ele = document.getElementsByClassName("radio_moves");
			 for(var ii=0;ii<ele.length;ii++)
				ele[ii].checked = false;	
			showextramoves_opened=1;
			selectedoption=-1;
			fetchdata(null);
		 };



		function showgameid(){
				try {
					document.getElementById('WhiteGameID_Data').textContent= "Game ID = "+ decodedgameid;
					document.getElementById('WhiteGameFEN_Data').textContent="Current FEN = "+document.getElementById("fen").value
				  MicroModal.init({
					awaitCloseAnimation: true,// set to false, to remove close animation
					onShow: function(gamemodal) {										
					  console.log("micromodal open = decodedgameid "+decodedgameid);
					},
					onClose: function(gamemodal) {
					  console.log("micromodal close");
					}
				  });
				  
				} catch (e) {
				  console.log("micromodal error: ", e);
				}
	};

	function clearCookie(name, domain, path){
		var domain = domain || document.domain;
		var path = path || "/";
		document.cookie = name + "=; expires=" + +new Date + "; domain=" + domain + "; path=" + path;
	};

	function eraseCookie(name) {
		createCookie(name,"",-1);
	}

	function createCookie(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	}

	function cancelgameid(){

		var parentDiv= document.getElementById('gameiddiv');
		var bt2 = document.getElementById('cancelgameID');
		if((parentDiv!=null)&&(parentDiv!=undefined)&& (bt2!=null)&&(bt2!=undefined)){
				if((newgamestatus=="1")){
					cancelgame=1;

					if((boardfen==null) || (boardfen=="")|| (boardfen==undefined)){
							boardfen=document.getElementById("hiddenfen").value;
							boardfen=document.getElementById("fen").value;
						}
					//debugger
					document.cookie = "livegameid=; expires=Thu, 01 Jan 1970 00:00:00 GMT";

					updateCookieAction(boardfen);
					newgamestatus="0";cancelgame=0;
					document.removeEventListener('DOMContentLoaded',cancelgameid());
					bt2.setAttribute('onclick',"");
					bt2.setAttribute('innerText',"");
					bt2.setAttribute('innerHTML',"");

					var elementExists =document.getElementById('cancelgameID');
					if((elementExists!=null)&&(elementExists!=undefined)){
						document.getElementById('cancelgameID').remove();
					}

					var cookies = document.cookie.split(";");
					for (var i = 0; i < cookies.length; i++)
  							eraseCookie(cookies[i].split("=")[0]);

					document.cookie = "livegameid=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
					window.location = '../';
					//goback to the lastpage
				}
		}
	}

	function showcancelgameid(){
		var parentDiv= document.getElementById('gameiddiv');

		try {
			if((parentDiv.querySelector("#cancelgameID") == null)||(parentDiv.querySelector("#cancelgameID") == undefined)){
				document.addEventListener("DOMContentLoaded", cancelgameid());
				var bt2 = document.createElement('button');
				bt2.setAttribute("name","cancelgameID");
				bt2.setAttribute("id","cancelgameID");
				bt2.setAttribute("data-micromodal-trigger","modal-3");
				bt2.setAttribute("onclick","cancelgameid();");

				bt2.innerText="Cancel the Game";
				bt2.innerHTML="Cancel the Game";

				parentDiv.appendChild(bt2);
				}
		  
		} catch (e) {
		  console.log("showcancelgameid error: ", e);
		}
};

	function showgamechoices(){
		try {
			document.getElementById('WhiteGameID_Data').textContent= "Game ID = "+ decodedgameid;
			document.getElementById('WhiteGameFEN_Data').textContent="Current FEN = "+document.getElementById("fen").value
		  MicroModal.init({
			awaitCloseAnimation: true,// set to false, to remove close animation
			onShow: function(gamechoicemodal) {										
			  console.log("micromodal open = decodedgameid "+decodedgameid);
			},
			onClose: function(gamechoicemodal) {
			  console.log("micromodal close");
			}
		  });
		  
		} catch (e) {
		  console.log("micromodal error: ", e);
		}
};

	var returnoption=-1;

	function createRadioElement( id,value, checked) {

		var lnbrk = document.createElement('br');

		var input = document.createElement('input');
			input.classList.add("radio_moves");
			input.name  = "radio_option"+id;
			input.id  = "radio_option"+id;
			input.type = 'radio';
			input.value = id;

			//debugger

		var inputlabel = document.createElement('Label');
		inputlabel.setAttribute("for",input.id);
		value=value.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') ;
		//alert(value);
		inputlabel.innerHTML = value;

		//castle
		var dr ="<span style='background:orange;'>"+ value + " <span style='background:yellow;'> [Offer Draw and Retreat] </span> <span>";
		var sr ="<span style='background:white;'>"+ value + " <span style='background:red;'> [Surrender] </span> <span>"; 
		var dsr ="<span style='background:yellow;'>"+ value + " <span style='background:red;'> [From Draw to Surrender] </span> <span>"; 
		txt=value.trim();

		var text=inputlabel.childNodes[0].nodeValue;

		var OfficerLetter=text.substr(0,1);
		let UCresult =	OfficerLetter.includes("P") || OfficerLetter.includes("C") || OfficerLetter.includes("G") || OfficerLetter.includes("H") || OfficerLetter.includes("M") || OfficerLetter.includes("S"); 
		let lcresult = 	OfficerLetter.includes("p") || OfficerLetter.includes("c") || OfficerLetter.includes("g") || OfficerLetter.includes("h") || OfficerLetter.includes("m") || OfficerLetter.includes("s"); 
		let lastletter = text.substr(text.length-1,1) ;

		let Officer="Officer";
		let last2ndletter =text.substr(text.length-2,1); 

		comment="";
		OfficerLetter=text.substr(0,1);
		var lastOfficer="";
		if((OfficerLetter.includes("C")) || (OfficerLetter.includes("c"))){Officer="Charak (Spy)";}
		else if((OfficerLetter.includes("G")) || (OfficerLetter.includes("g"))){Officer="Gajarohi (Bishop)";}
		else if((OfficerLetter.includes("H")) || (OfficerLetter.includes("h"))){Officer="ShoorVeer (Knight)";}
		else if((OfficerLetter.includes("M")) || (OfficerLetter.includes("m"))){Officer="Maharathi (Rook)";}
		else if((OfficerLetter.includes("S")) || (OfficerLetter.includes("s"))){Officer="Senapti (General)";}

		//if(text.substr(1,1)=='^') { comment="Naraad (Peace-Ambassador) to Control the opponent"; } 
		//else 
		if(text.substr(1,1)=='*') { comment="Capture the opponent"; if (lastletter=="#") comment="Capture the opponent King"} 
		else if(text.substr(1,1)=='>') { comment="Push the opponent";}
		else if(text.substr(1,1)=='-') {
			//alert(text);
				if((UCresult)&&(UCresult!="")&&(UCresult!=null)){
					if (last2ndletter=='>'){
							if (lastletter=='P') {comment="Extract the White Padati(Pawn) from White "+ Officer+" Unit and Push";}
							else 
							if (lastletter=='p') {comment="Extract and Reuse the White Padati(Pawn) as Black pawn unit and Push";}
						}
					else if (last2ndletter=='<'){
							if (lastletter=='P') {comment="Extract and Reuse the White Padati(Pawn) as Black pawn unit and Pull back";}
						}					
					}				
				else if((lcresult)&&(lcresult!="")&&(lcresult!=null)){
					if (last2ndletter=='>'){
							if (lastletter=='p') {comment="Extract the Black Padati(Pawn) from Black "+ Officer+" Unit and Push";}
							else 
							if (lastletter=='P') {comment="Extract and Reuse the Black Padati(Pawn) as White unit and Push";}
						}
					else if (last2ndletter=='<'){
							if (lastletter=='p') {comment="Extract and Reuse the Black Padati(Pawn) as White pawn unit and Pull back";}
						}
					}
	}
	else if (last2ndletter=='='){

		/*if((lastletter.includes("C")) || (lastletter.includes("c"))){lastOfficer="Charak (Spy)";}
		else if((lastletter.includes("G")) || (lastletter.includes("g"))){lastOfficer="Gajarohi (Bishop)";}
		else if((lastletter.includes("H")) || (lastletter.includes("h"))){lastOfficer="ShoorVeer (Knight)";}
		else if((lastletter.includes("M")) || (lastletter.includes("m"))){lastOfficer="Maharathi (Rook)";}
		else if((lastletter.includes("S")) || (lastletter.includes("s"))){lastOfficer="Senapti (General)";}
	*/
		if( ((OfficerLetter.includes("C")) || (OfficerLetter.includes("c"))) && ((lastletter.includes("G")) || (lastletter.includes("g")) )){ comment="Charak (Spy) Promoted as Gajarohi (Bishop)";}
		else if(((OfficerLetter.includes("G")) || (OfficerLetter.includes("g")))  && ((lastletter.includes("C")) || (lastletter.includes("c"))) ){ comment="Gajarohi (Bishop) Demoted as Charak (Spy)";}
		else if( ((OfficerLetter.includes("G")) || (OfficerLetter.includes("g"))) && ((lastletter.includes("H")) || (lastletter.includes("h")) )){ comment="Gajarohi (Bishop) Promoted as Hrashawrohi ShoorVeer (Knight)";}
		else if( ((OfficerLetter.includes("H")) || (OfficerLetter.includes("h"))) && ((lastletter.includes("G")) || (lastletter.includes("g")) )){ comment="Hrashawrohi ShoorVeer (Knight) Demoted Gajarohi (Bishop)";}
		else if( ((OfficerLetter.includes("H")) || (OfficerLetter.includes("H"))) && ((lastletter.includes("M")) || (lastletter.includes("m")) )){ comment="Hrashawrohi ShoorVeer (Knight) Promoted as Maharathi (Rook)";}
		else if( ((OfficerLetter.includes("M")) || (OfficerLetter.includes("m"))) && ((lastletter.includes("H")) || (lastletter.includes("h")) )){ comment="Maharathi (Rook) Demoted as Hrashawrohi ShoorVeer (Knight)";}
		else if( ((OfficerLetter.includes("M")) || (OfficerLetter.includes("M"))) && ((lastletter.includes("S")) || (lastletter.includes("s")) )){ comment="Maharathi (Rook) Promoted as Senapati (General)";}
		else if( ((OfficerLetter.includes("M")) || (OfficerLetter.includes("m"))) && ((lastletter.includes("H")) || (lastletter.includes("h")) )){ comment="Senapati (General) Demoted as Maharathi (Rook)";}
	}

	inputlabel.innerHTML = "<span style='background:white;'>"+ value + " <span style='background:lightgreen;'> "+ comment+" </span> <span>";

		//if( ((text.substr(0,1)=='j')|| (text.substr(0,1)=='J'))&& ((value.includes("0=J")) || (value.includes("9=j"))||(value.includes("9=J"))||(value.includes("0=j")))){ inputlabel.innerHTML = "<span style='background:white;'>"+ value + " <span style='background:yellow;'> [Offer Draw] </span> <span>"; }
		//else 
		if((value.includes("0=J")) || (value.includes("9=j"))||(value.includes("9=J"))||(value.includes("0=j"))){ inputlabel.innerHTML = "<span style='background:white;'>"+ value + " <span style='background:yellow;'> [Offer Draw] </span> <span>"; }
		else if((value.includes("0=I")) || (value.includes("9=i"))||(value.includes("9=I"))||(value.includes("0=i"))){ inputlabel.innerHTML = "<span style='background:yellow;'>"+ value + " <span style='background:green;'> [Draw Offer taken back] </span> <span>"; }

		//truce
		else if((value.includes("x1=I")) || (value.includes("x1=i"))||(value.includes("x2=I"))||(value.includes("x2=i"))){ inputlabel.innerHTML = dr ;}
		else if((value.includes("x3=I")) || (value.includes("x3=i"))||(value.includes("x4=I"))||(value.includes("x4=i"))){ inputlabel.innerHTML = dr ;}
		else if((value.includes("x5=I")) || (value.includes("x5=i"))||(value.includes("x6=I"))||(value.includes("x6=i"))){ inputlabel.innerHTML =  dr ;}
		else if((value.includes("x7=I")) || (value.includes("x7=i"))||(value.includes("x8=I"))||(value.includes("x8=i"))){ inputlabel.innerHTML =  dr }

		else if((value.includes("y1=I")) || (value.includes("y1=i"))||(value.includes("y2=I"))||(value.includes("y2=i"))){ inputlabel.innerHTML = dr ;} 
		else if((value.includes("y3=I")) || (value.includes("y3=i"))||(value.includes("y4=I"))||(value.includes("y4=i"))){ inputlabel.innerHTML = dr ;} 

		else if((value.includes("y5=I")) || (value.includes("y5=i"))||(value.includes("y6=I"))||(value.includes("y6=i"))){ inputlabel.innerHTML = dr ;}
		else if((value.includes("y7=I")) || (value.includes("y7=i"))||(value.includes("y8=I"))||(value.includes("y8=i"))){ inputlabel.innerHTML = dr ;}

		else if((value.includes("x1=J")) || (value.includes("x1=j"))||(value.includes("x2=J"))||(value.includes("x2=j"))){ inputlabel.innerHTML = sr ;} 
		else if((value.includes("x3=J")) || (value.includes("x3=j"))||(value.includes("x4=J"))||(value.includes("x4=j"))){ inputlabel.innerHTML = sr ;} 
		else if((value.includes("x5=J")) || (value.includes("x5=j"))||(value.includes("x6=J"))||(value.includes("x6=j"))){ inputlabel.innerHTML = sr ;} 
		else if((value.includes("x7=J")) || (value.includes("x7=j"))||(value.includes("x8=J"))||(value.includes("x8=j"))){ inputlabel.innerHTML = sr ;}

		else if((value.includes("y1=J")) || (value.includes("y1=j"))||(value.includes("y2=J"))||(value.includes("y2=j"))){ inputlabel.innerHTML = sr ;} 
		else if((value.includes("y3=J")) || (value.includes("y3=j"))||(value.includes("y4=J"))||(value.includes("y4=j"))){ inputlabel.innerHTML = sr ;} 
		else if((value.includes("y5=J")) || (value.includes("y5=j"))||(value.includes("y6=J"))||(value.includes("y6=j"))){ inputlabel.innerHTML = sr ;} 
		else if((value.includes("y7=J")) || (value.includes("y7=j"))||(value.includes("y8=J"))||(value.includes("y8=j"))){ inputlabel.innerHTML = sr ;}
		/* buggy
		//i moving to Truce
		else if((value.substring(0,1=='I')) &&  ((value.includes("x1")) || (value.includes("x2"))||(value.includes("x3"))||(value.includes("x4")) )){ inputlabel.innerHTML = value + "Offer Draw and Retreat"; }
		else if((value.substring(0,1=='I')) && ((value.includes("x5")) || (value.includes("x6"))||(value.includes("x7"))||(value.includes("x8")))){ inputlabel.innerHTML = value + "Offer Draw and Retreat"; }
		else if((value.substring(0,1=='I')) && ((value.includes("y1")) || (value.includes("y2"))||(value.includes("y3"))||(value.includes("y4")))){ inputlabel.innerHTML = value + "Offer Draw and Retreat"; }
		else if((value.substring(0,1=='I')) && ((value.includes("y5")) || (value.includes("y6"))||(value.includes("y7"))||(value.includes("y8")))){ inputlabel.innerHTML = value + "Offer Draw and Retreat"; }

		else if((value.substring(0,1=='i')) &&  ((value.includes("x1")) || (value.includes("x2"))||(value.includes("x3"))||(value.includes("x4")) )){ inputlabel.innerHTML = value + "Offer Draw and Retreat"; }
		else if((value.substring(0,1=='i')) && ((value.includes("x5")) || (value.includes("x6"))||(value.includes("x7"))||(value.includes("x8")))){ inputlabel.innerHTML = value + "Offer Draw and Retreat"; }
		else if((value.substring(0,1=='i')) && ((value.includes("y1")) || (value.includes("y2"))||(value.includes("y3"))||(value.includes("y4")))){ inputlabel.innerHTML = value + "Offer Draw and Retreat"; }
		else if((value.substring(0,1=='i')) && ((value.includes("y5")) || (value.includes("y6"))||(value.includes("y7"))||(value.includes("y8")))){ inputlabel.innerHTML = value + "Offer Draw and Retreat"; }

		//i moving to Truce
		else if((value.substring(0,1=='J')) &&  ((value.includes("x1")) || (value.includes("x2"))||(value.includes("x3"))||(value.includes("x4")) )){ inputlabel.innerHTML = value + "Surrender"; }
		else if((value.substring(0,1=='J')) && ((value.includes("x5")) || (value.includes("x6"))||(value.includes("x7"))||(value.includes("x8")))){ inputlabel.innerHTML = value + "Surrender"; }
		else if((value.substring(0,1=='J')) && ((value.includes("y1")) || (value.includes("y2"))||(value.includes("y3"))||(value.includes("y4")))){ inputlabel.innerHTML = value + "Surrender"; }
		else if((value.substring(0,1=='J')) && ((value.includes("y5")) || (value.includes("y6"))||(value.includes("y7"))||(value.includes("y8")))){ inputlabel.innerHTML = value + "Surrender"; }

		else if((value.substring(0,1=='j')) &&  ((value.includes("x1")) || (value.includes("x2"))||(value.includes("x3"))||(value.includes("x4")) )){ inputlabel.innerHTML = value + "Surrender"; }
		else if((value.substring(0,1=='j')) && ((value.includes("x5")) || (value.includes("x6"))||(value.includes("x7"))||(value.includes("x8")))){ inputlabel.innerHTML = value + "Surrender"; }
		else if((value.substring(0,1=='j')) && ((value.includes("y1")) || (value.includes("y2"))||(value.includes("y3"))||(value.includes("y4")))){ inputlabel.innerHTML = value + "Surrender"; }
		else if((value.substring(0,1=='j')) && ((value.includes("y5")) || (value.includes("y6"))||(value.includes("y7"))||(value.includes("y8")))){ inputlabel.innerHTML = value + "Surrender"; }
		*/

		var elements = document.getElementsByClassName("radio_moves");

		input.setAttribute('onclick',"$('.radio_moves').prop('checked', false);$(this).prop('checked', true);"); // for FF
		input.onclick = function() {
			$('.radio_moves').prop('checked', false);
			$(this).prop('checked', true);
		/*	$('input[class=radio_moves]').attr('checked',false);
			$('input[class=radio_moves]').attr('checked',false);
			$('input[class=radio_moves]').attr('checked',false);
			$('input[class=radio_moves]').attr('checked',false);
		*/
		}
		
		
		for (var i = 0; i < elements.length; i++) {
			elements[i].addEventListener('click', input.onclick(), false);
		}
		
			//if (checked) {
				$('.radio_moves').prop('checked', false);
				showextramoves_opened=1;
			

			//}
			//debugger
			document.getElementById('additionalmoves').appendChild(lnbrk);
			document.getElementById('additionalmoves').appendChild(input);
			document.getElementById('additionalmoves').appendChild(inputlabel);
			
	}

	 function CloseTimeoutModal(){
		$('div[name="additionalmoves"]').empty(); 
		$('#additionalmoves').empty();
		selectedoption=-1;
		showextramoves_opened=0;
		modal.close('modalContent');
	}

	function showextramoves(option_tag_in_select_tag){
		
		var tempvalue="",tempmovedfen="";
		var radioopt;
		if((option_tag_in_select_tag!=null)&&(typeof option_tag_in_select_tag != 'undefined')) {
		try {
			//debugger
			$('#additionalmoves').empty();
			$('div[name="additionalmoves"]').empty();
				for (var ol=0;ol<option_tag_in_select_tag.length;ol++){
					tempvalue=option_tag_in_select_tag[ol];
					//debugger
					tempmovedfen=tempvalue[0].textContent;
					
					createRadioElement(ol,tempmovedfen);
				}

				if(option_tag_in_select_tag.length==1)
				{
					document.getElementById('btnclrmoves').style.display= "none";
					document.getElementById('radio_option0').checked = true;
				}
				else{ document.getElementById('btnclrmoves').style.display= "";}

				showextramoves_opened=1;
					modal.open('modalContent');
					setTimeout(CloseTimeoutModal,20000);
				
		} catch (e) {
		  console.log("micromodal error: ", e);
		  selectedoption=-1;
		}
	}
}

