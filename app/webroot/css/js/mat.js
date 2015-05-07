
function scoref( player, scoret ){
  var op = 1;
  var remField = null;
  var scoreField = null;
  var scorePassField = null;
  var remStat = null;
 	
  if (player == 1){
	  op = 2;
  }

	//document.getElementById('buttonFocusHold').focus();

	
		remField =  document.board_ctl['data[remove]'];
		scoreField =  eval ( 'document.score[\'data[Score][' + scoret + player +']\'];');
	// 	 eval ( 'document.score[\'data[Score][player]\']='+player );
		scorePassField = document.score['data[Score][score]'];
		 
		scorePassField.value = scoret;
		switch (scoret){
			
			case 'osaekomi':   
					//document.pinform['data[Mat][pin_pos]'].value = player;
					upTrigger( player ) ; 
			break;
			
			default:
				 if(  remField.checked == true ){
					if(  scoreField.value  > 0 ){ 
						scoreField.value-- ;
		     		}
		  		}else{
					scoreField.value++ ;
		  		}
		}
		
		if ( scoret == 'shido'  ) {
			// &&  remField.checked == false
		//	alert( 'document.score' + op + '.yuko.click()' );
			var s = scoreField.value | 0 ;
			if( remField.checked == true ) {
				s++;
			}
			// shidos will moving forward not affect other scores
			s=0;
			switch( s ){
				case 2:
					scoref( op, 'yuko');
					//eval('document.score.yuko' + op + '.click()');
				break;
				case 3:
					remStat = remField.checked; 
					remField.checked = ! remField.checked; 
					scoref( op, 'yuko');
					remField.checked = remStat ; 
					
					scoref(op, 'wazaari');
					//eval('document.score.wazaari' + op + '.click()');
				break;
				case 4:
					scoref(op, 'ippon');
					//eval('document.score.ippon' + op + '.click()');
				break;
			}
 			
 		}
		
		//eval( 'document.board_ctl[\'data[remove]\'].checked= false; ' ) ;
		remField.checked = false;
	}

function chkWinner(){
				
				
				if( document.winner['data[win]'].value == '' || document.winner['data[by]'].value == '' ){
					alert("You must select competitor and score!");
					return false;
				}
				
				if(  confirm("Winner is #" + document.winner['data[win]'].value + " by " 
					+  document.winner['data[by]'].value + " ?")){
						
						
						document.winner.submit();
					}
}

function chkRun(){
	if( document.matDataFrm['match_id'].value !=  document.clockform['data[Mat][current_match_id]'].value){
		window.location.reload();
	}
	
	
var cr = document.matDataFrm['clock_running'].value;
if( cr != document.clockform['data[Mat][clock_running]'].value )
{
 document.clockform['data[Mat][mat_clock]'].value = document.matDataFrm['mat_clock'].value;
 if( cr == 1 ){
  	clock_start();
  }else{
  	clock_stop();
  }
 }
 
var pp = document.matDataFrm['pin_pos'].value;
var pf = document.pinform['data[Mat][pin_pos]'];

if(  pf.value != pp )
{

 pf.value = pp;
 if( pp != 0 ){

	 hajime( pp );
  }else{
	  toketa();
  }
 }

}
