
function rulesdescription(color_to_move, piecemovetype){
        $("textarea#player1ta").val("");$("textarea#player2ta").val("");
        if(color_to_move!=''){
                if(piecemovetype=="naaradmove"){
                        $("div#player1 label").html("Rules for GodMan (#N Means RaajRishi or Naarad)");	
                        //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"~'N' is immortal and has no killing power.");
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'N' is Peace-Maker and higly respected group of Gurus headed by Naarad.");
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Generally, 'N' can move 2 upto step at a time OrthoDiagonally. 'N' CANNOT kill or cannot help their own army to Kill.");
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'N' has to assure that all 8 surrounding areas are made as 'Protection-Asylum' (within same Zone).");
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Opponent pieces under 'N' cannot be killed by N's Army even King. ");
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'N' can be neutralized when inside the opponent CASTLE or by any surrounding opponent Royal and Semi-Royals.");
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Neutral 'N' can move only 1 step at a time. ");				
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Only 'N' cannot push 'Officers' to the Truce-Zone. *N01#");			
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'N' can roam inside any Zone, but cannot move withinTruce Zone.");
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'N' can push only opponent Army Officials. Once the Opponents Army Unit is in safe area, then only N's Army can move. Even in this scenario N's Army cannot kill or threaten the Opponent Army Officials.");
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'N' cannot control opponent Army Officials in Opponents CASTLE of when Netralized.");
                        $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'N' CANNOT promote anyone.");				
                    
                        $("div#player2 label").html("Exceptions in Rules for GodMan");	
                        //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                        $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*N01# 'N' also gets neutralized in Compromized CASTLE of opponent King.");
                    }
                if(piecemovetype=="naaradmove"){
                            $("div#player1 label").html("Rules for RaajRishi or GodMan (#N Means Naarad)");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"* Not Coded Yet. So Rules are complex for Naarad. For now. Made this piece as immovable");
                    
                            $("div#player2 label").html("Exceptions in Rules for RaajRishi (RaajRishi Code is too complex. Will take more than month to code");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"* Not Coded Yet. So Rules are complex for Naarad. For now. Made this piece as immovable");
                        }
                    
                if(piecemovetype=="kingmove"){
                            $("div#player1 label").html("Rules for King (#I Means Indra or King)");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"~'I' is Royal Controller and Protector of Kingdom. Controls the Kingdom and  Army.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Generally, 'I' can move 1 step at a time. 'I' can kill. *I01#");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'I' can move or jump 1-2 steps like 'S' in his own CASTLE with max 2 steps. Castle itself is Royal and 'I' is aware of every area. *I02# *I03#");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'I' cannot Jump over opponent.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'I' can move 'To and From' any Zones with the help of Royal or Semi-Royal members only. *I04# ");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Only 'I' and 'A' both should declare war at their side by leaving the Scepter. Declaration of war helps the Army Officers to Strike the opponent; Otherwise Army cannot strike but only move. *I05");			
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'I' can promote any surrounding Military Officers on the move or even as a simple promotion without moves. *I06# ");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'I' when enters the opponent CASTLE becomes the Emperor or Vikramaditya (IndraJeet). War Ends and opponent loses.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Army when enters the opponent CASTLE makes the 'I' Emperor or Vikramaditya (IndraJeet). War Ends and opponent loses. Here, 'A' also gets promoted.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'A' when enters the opponent CASTLE makes the 'I' Emperor or Vikramaditya (IndraJeet). War Ends and opponent loses. Here, 'A' also gets promoted.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Only 'I' or 'S' can suggest 'Army-Recall' by getting into Truce Zone non Boundary Areas.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Only 'I' can suggest 'Truce and Army-Recall' by getting into Truce Zone's special Boundary Area.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Only 'I' can suggest 'Sandhi' even in the WAR-Zone.");						
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Only 'I' can Accept his Defeat in CASTLE. No Sandhi is allowed in CASTLE.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Only 'I' can Accept his Defeat in TRUCE. No Sandhi is allowed in TRUCE.");						
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Only 'I' can Accept his Defeat in No Mans Land. No Sandhi is allowed in TRUCE.");						
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Only 'I' can Accept his Defeat in No Mans Land. No Sandhi is allowed in TRUCE.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ In Order to make Army to move to Full strength in any Specific Zone, the Royal or Semi-Royal should be present in that Zone.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'I' gets permanantly hidden in Naag-Lok or No-Mans land. War still goes on.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'I' can promote the neighbour Army Ranks in same zone as per parity of Ranks.");				
                    
                            $("div#player2 label").html("Exceptions in Rules for King");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"*I01# If Royal is surrounded with Royal or Semi-Royal in same zone then these Royal or Semi-Royal can move like Knight.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*I02# 'I' require help 'To move-in or move-out' of his own Compromised CASTLE to Truce.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*I03# 'I's own Compromised CASTLE becomes Secondary War-Zone.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*I04# 'I' doesn't need any help 'To move-in or move-out' of Opponent's Compromised CASTLE.");	
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*I05# Army can Strike only if 'I' and 'A' both are not idle. (Still has defects to be fixed)");	
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*I06# Parity of Ranks are maintained. Only 'G=2', 'H=2', 'M=2', 'S=1'  are allowed..");
                        }
                    
                if(piecemovetype=="arthshastrimove"){
                            $("div#player1 label").html("Rules for ArthShastri (#A Means ArthShastri or Prime Minister)");	
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"~'A' is Royal Advisor of King. Manages the Kingdom and Finances.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'A' can move only 1 step at a time. 'A' CANNOT kill. *A01#");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'A' can move or jump 1-2 steps like 'S' in his own CASTLE with max 2 steps. Castle itself is Royal and 'A' is aware of every area. *A02# *A03#");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'A' cannot Jump over opponent.");
                    
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'A' can move n number or Times 'To and From' any Zones with the help of Royal or Semi-Royal members only. *A04# ");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'A' can promote any surrounding Military members on the move or even as a simple promotion without moves. *A05# ");
                    
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ In Order to make Army to move to Full strength in any Specific Zone, the Royal or Semi-Royal should be present in that Zone.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ Only 'A' or 'I' can only suggest 'Truce' (His own Army as Strikeless) by getting into Truce Zone's Boundary Areas. Rest of the Truce Zone has no impact on Army.");
                    
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'A' gets permanantly hidden in Naag-Lok or No-Mans land. War still goes on.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'I' when enters the opponent CASTLE becomes the Emperor or Vikramaditya (IndraJeet). War Ends and opponent loses. Here, 'A' also gets promoted.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~Army or C when enters the opponent CASTLE also makes the 'I' Emperor or Vikramaditya (IndraJeet). War Ends and opponent loses. Here, 'A' also gets promoted.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'A' when enters the opponent CASTLE also makes the 'I' Emperor or Vikramaditya (IndraJeet). War Ends and opponent loses. Here, 'A' also gets promoted.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'A' if is idle then make the Soldiers and Army Strikeless. *A06#");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'A' if dies then make the Soldiers and Army and permanently Strikeless. *A06#");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~Army can Strike only if 'I' and 'A' both are not idle.");	
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'A' can promote the neighbour Army Ranks in same zone as per parity of Ranks.");
                    
                            $("div#player2 label").html("Exceptions in Rules for ArthShastri (ArthShashtri Code has to many bugs");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"*A01# If Royal is surrounded with Royal or Semi-Royal in same zone then these Royal or Semi-Royal can move like 'S' but only with 2 Moves maximum.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*A02# 'A' require help 'To move-in or move-out' of his own Compromised CASTLE to Truce or No-Mans.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*A03# 'A's own Compromised-CASTLE becomes Secondary War-Zone.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*A04# 'A' doesn't need any help 'To move-in or move-out' of Opponent's Compromised CASTLE.");	
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*A05# Parity of Ranks are maintained. Only 'G=2', 'H=2', 'M=2', 'S=1'  are allowed.");	
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*A06# Kautilya Modified this rule and made Army Units as Autonomous and Self-Sustaining.");
                    
                        }
                    
                if(piecemovetype=="spymove"){
                            $("div#player1 label").html("Rules for Chaaran (#C Means Chaaran or Spy)");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"~ 'C' is Semi-Royal and Semi-Millitary Officer and leads the Spies under King.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'C' can move only 1 step at a time. 'C' CANNOT kill. *C01#");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'C' can move or jump 1-2 steps like 'S' in his own CASTLE with max 2 steps. Castle itself is Royal and 'C' is aware of every area. *C02# *C03#");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'C' cannot Jump over opponent.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'C' can move n number or Times 'To and From' any Zones with the help of Royal or Semi-Royal members only. *C04# ");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'C' can promote any surrounding Military members on the move or even as a simple promotion without moves. *C05# ");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'C' can promote himself as optional in Opponent CASTLE without any help. This is exceptional honor");
                    
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ In Order to make Army to move to Full strength in any Specific Zone, the Royal or Semi-Royal should be present in that Zone.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'C' gets permanantly hidden in Naag-Lok or No-Mans land. War still goes on.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'C' can promote the neighbour Army Ranks in same zone as per parity of Ranks.");				
                    
                            $("div#player2 label").html("Exceptions in Rules for 'C'");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"*C01# If Royal is surrounded with Royal or Semi-Royal in same zone then these Royal or Semi-Royal can move like Knight.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*C02# 'C' require help 'To move-in or move-out' of his own Compromised CASTLE to Truce or No-Mans.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*C03# 'C's own Compromised CASTLE becomes Secondary War-Zone.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*C04# 'C' doesn't need any help 'To move-in or move-out' of Opponent's Compromised CASTLE.");	
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*C05# Parity of Ranks are maintained. Only 'G=2', 'H=2', 'M=2', 'S=1'  are allowed.");
                        }
                    
                if(piecemovetype=="generalmove"){
                            $("div#player1 label").html("Rules for Senapati (#S Means General/WaZeer)");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"~'S' are last Rank and Senior to Rook 'M'.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' are funded by 'A'. They are the top commander ot Army Officers.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' moves 1-3 steps in any direction and can also jump.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' moves full move in that zone if I/A/C are present in that zone.");
                    
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' cannot jump over opponent.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' can move and kill either straight or diagonal or like 'H'. *S01#");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' CANNOT kill opponents, if War is not Declared.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' CANNOT kill opponents, if 'A' is Killed.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' CAN change Zones with the help of Royal/Semi-Royals. *S03#.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' can only suggest 'ReCall' by getting into any areas of Truce Zone. *S04#");
                    
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' are directly controlled by Opponent's Naarad. (Not Coded Yet)");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' are indirectly controlled by 'I'.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' are never promoted.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'S' can promote the neighbour Army Ranks in same zone as per parity of Ranks.");				
                    
                            $("div#player2 label").html("Exceptions in Rules for Senapati");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"*S01#. 'S' is not dependent on resources. Hence, even if it is jumping 2 moves like 'H', it does not require resource in 1st step.");
                    
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*S02# When 'I' or 'S' recalls the Army (Is in Truce&Recall Zone), 'S' cannot march forward but can march backward.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*S03# When 'I' signs Truce and Recall Both, then entire Army cannot Kill in any direction.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*S03# When the CASTLEs are compromised then everyone including 'S' can enter or exit provided I has not recalled the army.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*S03# When only 'S' has Recalled the Army, then the maximum Area-Under-Control is counted as per the S's holding. It is maximum to the own boundary or the max row distance 'S' has covered.");
                    
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*S03# When the 'I' and 'S' both have Recalled the Army, then the maximum Area-Under-Control is counted as per the I's holding. It is maximum to the own boundary or the max row distance 'I' has covered.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*S03# Can Enter Truce, BUT can comeout of it with help of I/A/C. Cannot come out of No-Mans Land.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*Sxx# Must be pushed by I/A/C. Kautilya Modified Army Units as Autonomous and Self-Sustaining.");
                        }
                    
                if(piecemovetype=="rookmove"){
                            $("div#player1 label").html("Rules for Mahaarathi (#M Means Rook)");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"~'M' are 3rd Rank and Senior to Knight.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'M' are funded by 'A'. They are under Army 'S'.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'M' moves 1-3 steps in any direction BUT CANNOT jump.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ M' moves full move in that zone if I/A/C/G are present in that zone.");
                    
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'M' can move and kill either straight or diagonal.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'M' CANNOT kill opponents, if War is not Declared.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'M' CANNOT kill opponents, if 'A' is Killed.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'M' CAN only change Zones with the help of General/Royal/Semi-Royals.*M01#.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'M' are directly controlled by Opponent's Naarad. (Not Coded Yet)");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'M' are indirectly controlled by 'I' and can be promoted by any Royal or Semi Royal.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~'M' when are promoted becomes 'S' (Senapati).");
                    
                            $("div#player2 label").html("Exceptions in Rules for Mahaarathi");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"*M01# When 'I' or 'S' recalls the Army (Is in Truce&Recall Zone), 'M' cannot march forward but can march backward.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*M01# When King signs Truce and Recall or Both, then entire Army cannot Kill in any direction.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*M01# When the CASTLEs are compromised then everyone including 'M' can enter or exit provided I/S have not recalled the army.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*M01# Can Enter Truce, BUT can comeout of it with help of I/A/C/G. Cannot come out of No-Mans Land.");			
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*Mxx# Kautilya Modified Army Units as Autonomous and Self-Sustaining.");
                        }
                    
                if(piecemovetype=="knightmove"){
                            $("div#player1 label").html("Rules for Ashwaarohi or Shoorveer (#H Means Knight)");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"~ 'H' are last 2nd Rank and Senior to 'G'.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' are like Modern Guirellas funded by 'A'. They are under Army 'S'.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' moves 1-2 steps in any direction including jumping.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' moves full move in that zone if I/A/C/G are present in that zone.");
                    
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' CANNOT kill anyone if moved only 1 step in any direction. 'H' need resources to kill.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' cannot jump over opponent.");
                    
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' can kill only on 2 steps move. 1 step straight and then 2nd step straight or diagonal. If the 1st step has no team member except Naarad, then 'H' cannot Kill.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' CANNOT kill opponents, if War is not Declared.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' CANNOT kill opponents, if 'A' is Killed.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' CAN only change Zones with the help of General/Royal/Semi-Royals.*H01#.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' are directly controlled by Opponent's Naarad. (Not Coded Yet)");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' are indirectly controlled by 'I' and can be promoted by any Royal or Semi Royal.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'H' when are promoted becomes 'M' (Rook).");				
                    
                            $("div#player2 label").html("Exceptions in Rules for Knight");
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                    
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"* H01# When 'I'  or 'S' recalls the Army (Is in Truce&Recall Zone), 'H' cannot march forward but can march backward.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*H04# Must be pushed by I/A/C. Kautilya Modified Army Units as Autonomous and Self-Sustaining.");

                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*H01# When 'I' signs Truce and Recall or Both, then entire Army cannot Kill in any direction.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*H01# When 'A' signs Truce, then entire Army cannot Kill in any direction.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*M01# Can Enter Truce, BUT can comeout of it with help of I/A/C/G. Cannot come out of No-Mans Land.");

                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*H01# When the CASTLEs are compromised then everyone including 'H' can enter or exit provided King/General have not recalled the army.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*Hxx# Kautilya Modified Army Units as Autonomous and Self-Sustaining.");	
                        }

                if(piecemovetype=="bishopmove"){
                            $("div#player1 label").html("Rules for Gajaarohi or Hastin (#G Means Bishop)");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"~ 'G' are last 1st Rank and Junior to Knight.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'G' are funded by ArthShastri (A). They are under Army's Commander 'S'.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'G' moves 1-2 steps in any direction BUT CANNOT jump.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'G' moves full move in that zone if I/A/C/G are present in that zone.");

                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'G' can move and kill either straight or diagonal.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'G' CANNOT kill opponents, if War is not Declared.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'G' CANNOT kill opponents, if 'A' is Killed.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'G' CAN only change Zones with the help of General/Royal/Semi-Royals.*G01#.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'G' are directly controlled by Opponent's Naarad. (Not Coded Yet)");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'G' are indirectly controlled by King and can be promoted by any Royal or Semi Royal.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'G' when are promoted becomes 'H'(Knights).");

                            $("div#player2 label").html("Exceptions in Rules for Gajaarohi");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"* G01# When King or 'S' (Senapati) recalls the Army (Is in Truce&Recall Zone), 'G' cannot march forward but can march backward.");	
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*G01# When King signs Truce and Recall Both, then entire Army cannot Kill in any direction.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*G02# When the CASTLEs are compromised then everyone including 'G' can enter or exit provided I/S have  not recalled the army.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*G02# Can Enter Truce, BUT can comeout of it with help of I/A/C/G. Cannot come out of No-Mans Land.");

                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*G02# Kautilya Modified Army Units as Autonomous and Self-Sustaining.");
                        }

                if(piecemovetype=="soldiermove"){
                            $("div#player1 label").html("Rules for Padati or Pawns (~P Means Pawns)");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"~ 'P' are funded by 'A'. They are under Army Officers.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'P' moves or kills 1 direction forward (straight or Diagonal). *P01#.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'P' can move or kill only when surrounding squares in same Zone have Army Officers.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'P' CANNOT kill opponents, if War is not Declared.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'P' CANNOT kill opponents, if 'A' is Killed.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'P' CANNOT change Zones. *P02#.");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'P' are indirectly controlled by Opponent's Naarad. (Not Coded Yet)");
                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'P' are not controlled by King. (Not Coded Yet)");

                            $("textarea#player1ta").val($("textarea#player1ta").val()+"\n~ 'P' are never promoted");

                            $("div#player2 label").html("Exceptions in Rules for Pawns");	
                            //if($("input#"+color_to_move+"officerscanmovefull").val()=='0')
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"*P01# When 'I' or 'S' recalls the Army (Is in Truce&Recall Zone), 'P' cannot march forward but can march backward.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*P01# When 'I' signs Truce and Recall Both, then entire Army cannot Kill in any direction.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*P02# When the CASTLEs are compromised then everyone including Pawns can enter provided 'I'/'S' have not recalled the army.");
                            $("textarea#player2ta").val($("textarea#player2ta").val()+"\n*P02# 'P' CANNOT Enter Truce or No Mans Land. Can enter compromised CASTLE because it becomes Royal-War Zone. However, in Kautilya ShadYantra, only Senapati or General can push the Soldiers to change Zone. It is out of scope in the Classic version.");
                        }
            }
    }


function managemoves(piecemovetype,p1name,p2name,tname,dname,val,txt,dataa){
        if((txt.substr(0,1)==p1name)&&(p2name==tname)){
                    //ArthShastri is in CASTLE or opponent CASTLE. If General is in Truce then it means Army will have to retreat. If King or Arsthshastri is in War then retreat will not happen.
                    if((officermove==true)&& (/[a-h09]{2,2}/.test(dname))){
                                if ((txt.indexOf("Ö") >= 0)){
                                        $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#winninggame').show();
                                    }
                                else{
                                        if ($("#livemove").length) {
                                                $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                            }
                                    }
                            }
                    //General move from WAR to Truce
                    else if((piecemovetype=="generalmove")&& ((/[xy1-8]{2,2}/.test(dname)) &&(/[a-h1-8]{2,2}/.test(p2name)))){
                                $("#recallmove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                document.getElementById('lblSandhi').innerHTML="Sandhi";
                                $('form#recall').show();
                            }
                        //King move from WAR to Truce (non-Borders)
                        else if((piecemovetype=="kingmove")&& ((/[xy123678]{2,2}/.test(dname)) &&(/[a-h1-8]{2,2}/.test(p2name)))){
                                //debugger
                                if ((txt.indexOf("=Y") >= 0)){
                                        $("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_surrender').show();
                                    }
                                else {
                                        $("#recallmove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        document.getElementById('lblSandhi').innerHTML="Viraam Sandhi";
                                        $('form#recall').show();
                                    }
                            }
                        //ArthShashtri moving to Scepter
                        else if((piecemovetype=="arthshastrimove")&& (/[a-h45]{2,2}/.test(dname))){
                                if ((txt.indexOf("=Ä") >= 0)){
                                    $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    $('form#winninggame').show();
                                    }
                                else if ((txt.indexOf("=Á") >= 0)){
                                    $("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    document.getElementById('lblShanti').innerHTML="Shanti";
                                    $('form#king_Shanti').show();
                                    }
                                else{										
                                        if ($("#livemove").length) {
                                             $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));							
                                        }
                                    }
                            }
                        //ArthShashtri moving to Truce Borders
                        else if((piecemovetype=="arthshastrimove")&& (/[xy45]{2,2}/.test(dname))){
                                if ((txt.indexOf("=Ä") >= 0)){
                                        $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#winninggame').show();
                                    }
                                else if ((txt.indexOf("=A") >= 0)||(txt.indexOf("=") >= -1)){
                                        $("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        document.getElementById('lblShanti').innerHTML="Shanti";
                                        $('form#king_Shanti').show();
                                    }
                            }
                        //Officers	winning the scepters
                        else if(((piecemovetype=="spymove")||(officermove==true)||(soldiermove==true))&& (/[a-h09]{2,2}/.test(dname))){
                                if ((txt.indexOf("Ö")>=0)||(txt.indexOf("#") >= 0)){
                                        $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#winninggame').show();
                                    }
                                else{
                                        if ($("#livemove").length) {
                                             $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        }
                                    }
                            }
                        //TRUCE to No Mans
                        else if((piecemovetype=="kingmove")&& ((((/[x0]{2,2}/.test(p2name))||(/[y0]{2,2}/.test(p2name)))&&(/[xy09]{2,2}/.test(dname)))|| (((/[x9]{2,2}/.test(p2name))||(/[y9]{2,2}/.test(p2name)))&&(/[xy09]{2,2}/.test(dname))))){
                                //No Inversion in TRUCE
                                if ((txt.indexOf("=Y") >= 0)||(txt.indexOf("=J") >= 0)){
                                        $("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_surrender').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=I") >= 0)||(txt.indexOf("=") >= -1)){
                                        $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    }
                                else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
                                        $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#winninggame').show();
                                    }
                            }
                        //Truce to Truce	
                        else if((piecemovetype=="kingmove")&& ((/[xy0-8]{2,2}/.test(p2name)) &&(/[xy0-8]{2,2}/.test(dname)))){
                                if ((txt.indexOf("=Y") >= 0)){
                                        $("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_surrender').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
                                    if ($("#livemove").length) {
                                         $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        }
                                    }
                            }
                        //TRUCE to WAR
                        else if((piecemovetype=="kingmove")&& ((/[xy0-8]{2,2}/.test(p2name)) &&(/[a-h1-8]{2,2}/.test(dname)))){
                                if ((txt.indexOf("=Y") >= 0)){
                                        $("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_endgame').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){

                                        if ($("#livemove").length) {
                                            $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));

                                        }
                                    }
                            }
                        //TRUCE to CASTLE
                        else if((piecemovetype=="kingmove")&& ((((/[x0]{2,2}/.test(p2name))||(/[y0]{2,2}/.test(p2name)))&&(/[ah0]{2,2}/.test(dname)))|| (((/[x9]{2,2}/.test(p2name))||(/[y9]{2,2}/.test(p2name)))&&(/[ah0]{2,2}/.test(dname))))){
                                //No Inversion in TRUCE
                                if ((txt.indexOf("=Y") >= 0)){
                                        $("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_surrender').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
                                        if ($("#livemove").length) {
                                                $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                            }
                                    }
                                else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
                                        $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#winninggame').show();
                                    }
                            }
                        //kingmove moving to Truce Borders
                        else if((piecemovetype=="kingmove")&& (/[a-h1-8]{2,2}/.test(p2name))&&(/[xy45]{2,2}/.test(dname))){
                                if ((txt.indexOf("=V") >= 0)){
                                        $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#winninggame').show();
                                    }
                                else if ((txt.indexOf("=Y") >= 0)){
                                        $("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_surrender').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
                                        $("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        document.getElementById('lblShanti').innerHTML="Viraam Shanti Sandhi";
                                        //debugger;
                                        $('form#king_Shanti').show();
                                    }
                            }
                        //kingmove War to Non-Border Truce	
                        else if((piecemovetype=="kingmove")&& ((/[xy1-8]{2,2}/.test(dname)) &&(/[a-h1-8]{2,2}/.test(p2name)))){
                                if ((txt.indexOf("=Y") >= 0)){
                                    $("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    $('form#king_surrender').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
                                    $("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    $('form#king_endgame').show();
                                    }
                            }
                        // Within CASTLE Scepter
                        else if((piecemovetype=="kingmove")&& (((/[a-h9]{2,2}/.test(p2name))&&(/[d-e9]{2,2}/.test(dname)))|| ((/[a-h0]{2,2}/.test(p2name)&&(/[d-e0]{2,2}/.test(dname)))))){
                                //No Draw in CASTLE
                                if ((txt.indexOf("=J") >= 0)){
                                    $("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    $('form#king_Shanti').show();
                                    }
                                else if ((txt.indexOf("=I") >= 0) || (txt.indexOf("=") >= -1) ){
                                    $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    }
                                else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#")>=0)){
                                    $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    $('form#winninggame').show();
                                    }
                            }
                        //Within CASTLE
                        else if((piecemovetype=="kingmove")&& (((/[a-h9]{2,2}/.test(p2name))&&(/[a-h9]{2,2}/.test(dname)))|| ((/[a-h0]{2,2}/.test(p2name))&&(/[a-h0]{2,2}/.test(dname))))){
                                if ((txt.indexOf("=Y") >= 0)||(txt.indexOf("=J") >= 0)){
                                    $("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    $('form#king_Shanti').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=I") >= 0)||(txt.indexOf("=V")>=0)||(txt.indexOf("#")>=0)||(txt.indexOf("=") >= -1)){
                                    $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    }
                            }
                        //CASTLE to WAR
                        else if((piecemovetype=="kingmove")&& ((/[a-h1-8]{2,2}/.test(dname))&&(/[a-h09]{2,2}/.test(p2name)))){
                                if ((txt.indexOf("=Y") >= 0)){
                                        $("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_endgame').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
                                        if ($("#livemove").length) {
                                                $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                            }
                                    }
                            }
                        //WAR to CASTLE
                        else if((piecemovetype=="kingmove")&& ((/[a-h09]{2,2}/.test(dname)&&(/[a-h1-8]{2,2}/.test(p2name))))){
                                if ((txt.indexOf("=Y") >= 0)){
                                        $("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_surrender').show();
                                    }
                                else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
                                        $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#winninggame').show();
                                    }
                                else if ((txt.indexOf("=I") >= 0)){
                                        $("#Shantimove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_Shanti').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
                                        if ($("#livemove").length) {
                                                $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        }
                                    }
                            }
                        //WAR to WAR
                        else if((piecemovetype=="kingmove")&& ((/[a-h1-8]{2,2}/.test(dname)&&(/[a-h1-8]{2,2}/.test(p2name))))){
                                    if ((txt.indexOf("=Y") >= 0)){
                                            $("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                            document.getElementById('lblViraam').innerHTML="Viraam";
                                            $('form#king_endgame').show();
                                        }
                                    else if ((txt.indexOf("=V") >= 0)||(txt.indexOf("#") >= 0)){
                                            $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                            $('form#winninggame').show();
                                        }							
                                    else if ((txt.indexOf("=U") >= 0)||(txt.indexOf("=") >= -1)){
                                            if ($("#livemove").length) {
                                                    $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));

                                        }
                                }
                            }
                        //CASTLE to No Mans
                        else if((piecemovetype=="kingmove")&& ((((/[x]{1}/.test(dname)))&&(((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname)))|| ((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname))))) ||(((/[y]{1}/.test(dname)))&&(((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname)))|| ((/[09]{2,2}/.test(p2name))&&(/[089]{2,2}/.test(dname))))))){
                                //debugger
                                if ((txt.indexOf("=Y") >= 0)){
                                        $("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        document.getElementById('lblViraam').innerHTML="Viraam";
                                        $('form#king_endgame').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)){
                                        $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                    }
                                else{
                                    if ($("#livemove").length) {
                                            $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        }
                                    }
                            }
                        //CASTLE to TRUCE
                        else if((piecemovetype=="kingmove")&& ((((/[x]{1}/.test(dname)))&&(((/[9]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname)))|| ((/[0]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname)))))||(((/[y]{1}/.test(dname)))&&(((/[9]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname)))|| ((/[0]{1}/.test(p2name))&&(/[1-8]{1}/.test(dname))))))){

                                if ((txt.indexOf("=Y") >= 0)){
                                        $("#surrendermove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_surrender').show();
                                    }
                                else if ((txt.indexOf("=U") >= 0)){
                                        $("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_endgame').show();
                                    }
                            }
                        else if((piecemovetype=="kingmove")&& (/[1-8]{1}/.test(p2name))){
                                //(p2name.indexOf('1')||(p2name.indexOf('2')>=0)||(p2name.indexOf('3')>=0)||(p2name.indexOf('4')>=0)||(p2name.indexOf('5')>=0)||(p2name.indexOf('6')>=0)||(p2name.indexOf('7')>=0)||(p2name.indexOf('8')>=0))){
                                if ((txt.indexOf("=Y") >= 0)){
                                        $("#endgamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#king_endgame').show();
                                    }
                                else{
                                    if ($("#livemove").length) {
                                            $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));

                                        }
                                    }
                            }
                        else if((/[a-h0-9]{2,2}/.test(dname))){
                                if ((txt.indexOf("#") >= 0)){
                                        $("#winninggamemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));
                                        $('form#winninggame').show();
                                    }
                                else{
                                        if ($("#livemove").length) {
                                                $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));

                                            }
                                    }
                                }
                        else{
                                debugger
                                if ($("#livemove").length) {
                                        $("#livemove").append($('<option></option>').val(val).html(txt).attr('data-coordinate-notation',dataa));

                                    }
                               // tempi=tempi+1;
                            }
                    }

                }