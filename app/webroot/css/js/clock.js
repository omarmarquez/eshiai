matchTimer = new function(){

	this.cr = null;
	this.output = null;
	this.timer = null;
	this.minute = null;
	this.second = null;
	this.ms = null;
	
	this.setup = function(){
 //			this.output = document.clockform['data[Mat][mat_clock]'];
//			this.cr = document.clockform['data[Mat][clock_running]'];	
		this.output = document.getElementById('MatMatClock');
		this.cr = document.getElementById( 'MatClockRunning');
	};

	this.reset  = function( nc ){
		this.stop();
		this.output.value = nc;
			
	};
	
	this.start = function(){
		
		if( this.cr == null ){
			this.setup();
		}
		this.cr.value=1;
		this.minute = this.output.value.substring(0,1) ;
 		this.second = this.output.value.substring(2,4) ;
  		this.ms = this.output.value.substring(5) ;
		this.timer = setTimeout( "downTimer()" ,100 );
	};
	
	this.stop = function(){
		if( this.cr == null ){
			 this.setup();
		}
		this.cr.value=0;
		clearTimeout( this.timer );
	};
};




pinTimer = new function(){

	this.cr = null;
	this.output = null;
	this.timer = null;
	this.score = null;
	this.minute = null;
	this.second = null;
	this.ms = null;
	this.player = null;
	this.pin_div = null;
	this.pin_score_div = null;
	this.sonomama = null;
	
	this.initialize = function(){
//		this.output = document.clockform['data[Mat][mat_clock]'];
//		this.cr = document.clockform['data[Mat][clock_running]'];	
		this.output = document.getElementById('MatPinClock');
		this.cr = document.getElementById( 'MatPinPos');
		this.score = document.getElementById( 'MatPinScore');
	//	this.pin_div = document.getElementById('pin_clock');
		this.pin_score_div = document.getElementById('pin_score');
		this.sonomama = document.getElementById('sonomama');
	};

	
	this.stop = function(){
		if( this.cr == null ){
			this.initialize() ;
		}
	//	this.pin_div.style.visibility = 'hidden';
		clearTimeout( this.timer );
		// setPinScore();
		this.cr.value=0;
	};
	
	this.reset  = function(){
		 this.stop();
		 this.output.value= '00.0';	
		 this.pin_score_div.innerHTML = '';
	};
	
	this.start = function( up ){
		
		this.cr.value= up;
		//this.minute = this.output.value.substring(0,1) ;
		//this.pin_div.style.visibility = 'visible';
		if( up == 1){
			this.pin_score_div.style.color = '#669';
		}
		if( up == 2){
			this.pin_score_div.style.color = '#FFF';
		}
 		this.second = this.output.value.substring(0,2) ;
  		this.ms = this.output.value.substring(3) ;
		this.timer = setTimeout( "upTimer()" ,100 );
		if( this.second == 0 ){
			this.pin_score_div.innerHTML = '_';
		}
	};
	

	
};



judoTimer = new function(){

	this.init = new function (){
		
		matchTimer.clocktimer = null;
		matchTimer.minute = null;
		matchTimer.second = null;
		matchTimer.ms = null;
		matchTimer.init = 0;
		
		pinTimer.clocktimer = null;
		pinTimer.minute = null;
		pinTimer.second = null;
		pinTimer.ms = null;
		pinTimer.init = 0;
		
		window.onkeyup = this.handleKeyPress; 
		pinTimer.pin_div = null;
		pinTimer.pin_score_div = null;
	
		pinTimer.sonomama = document.getElementById('sonomama');
	};
	
	
	this.cleanup = new function(){
		matchTimer.output =  null; 
		matchTimer.clocktimer = null;
		matchTimer.minute = null;
		matchTimer.second = null;
		matchTimer.ms = null;
		matchTimer.cr = null;
		matchTimer.init = null;
		
		pinTimer.output =  null; 
		pinTimer.clocktimer = null;
		pinTimer.minute = null;
		pinTimer.second = null;
		pinTimer.ms = null;
		pinTimer.cr = null;
		pinTimer.score = null;
		pinTimer.init = null;
		pinTimer.pin_div = null;
		pinTimer.pin_score_div = null;
		pinTimer.sonomama = null;
	};
	
 	this.handleKeyPress = function(e) {
		//alert(e);
		if (!e) e = window.event;
	//	alert('k');
	//alert(e.keyCode);
		//if (e.keyCode != 32 && e.keyCode != 27 && e.keyCode != 38 && e.keyCode != 40) {
			//if (PageData.inputField.value.length < 1) {
			//	PageData.autoTable.style.visibility = 'hidden';
			//}
			//alert('not handled');
			
		//} else {
			// special key was pressed
			switch (e.keyCode)
			{
				//case 119: // F8 
				case 27: // esc
					if( matchTimer.cr.value == 0 ){ // clock not running
						reset_board();
					}
				break;
				case 13: // space
						// matchTrigger();
					eval( 'document.board_ctl.clock_button.click()' ) ; 
				break;
//				case 13: // enter
//						toketa();
//				break;
				case 45: // 0 or .
				case 96: // 0 or .
					toketa();
				break;
				case 110: // Del .
				case 17: //Ctrl
					eval( 'document.board_ctl[\'data[remove]\'].checked= ! document.board_ctl[\'data[remove]\'].checked ' ) ;
				//	eval( 'document.score2[\'data[remove]\'].checked= ! document.score2[\'data[remove]\'].checked' ) ;
				break;
				
				case 106: //* 
					if( pinTimer.cr.value == 0 ){ // clock running
						pinTimer.reset();
					}
				break;
				
				case  8: // <- Reset
					if( matchTimer.cr.value == 0 ){ // clock not running
						reset_board();
					}
				break;
				
				//BLUE
				case 100:
				case 37:  // 4 or left key
					//eval( 'document.score.ippon1.click()' ) ;
					scoref( 1, 'ippon');
				break;
				case 104: //104 ? 
				case 38: // 8 or up key 
					// eval( 'document.score.wazaari1.click()' );
					scoref( 1, 'wazaari');
				break;
				case 101: // 5
					//eval( 'document.score.yuko1.click()' ) ;
					scoref( 1, 'yuko');
				break;
				case 98: // 2
				case 40: // 2 or down key
					//eval( 'document.score.shido1.click()' ) ;
					scoref( 1, 'shido');
				break;
				case 103:
				case 36:  // 7 or Home
					// eval( 'document.score.osaekomi1.click()' ) ;
					scoref( 1, 'osaekomi' );
				break;
				
				//WHITE
				case 107:  // +
					//eval( 'document.score.ippon2.click()' ) ;
					scoref( 2, 'ippon');
				break;
				case 105:
				case 33:  // 9 or pg up
					//eval( 'document.score.wazaari2.click()' ) ;
					scoref( 2, 'wazaari');
				break;
				case 102:
				case 39:   // 6 or left key
					// eval( 'document.score.yuko2.click()' ) ;
					scoref( 2, 'yuko');
				break;
				case 99:
				case 34:   // 3 or pg dn
					// eval( 'document.score.shido2.click()' ) ;
					scoref( 2, 'shido');
				break;
				case 109:
				case 44:   // -
					// eval( 'document.score.osaekomi2.click()' ) ;
					scoref( 2, 'osaekomi');
				break;

				
			} // switch;
			
			
		// }
	};

};


function downTimer(){
	 
 	var stop=false;
 	//alert ( m+':' + s +'.' +ms );
		 if ( matchTimer.ms<=0){ 
		    matchTimer.ms=9 ;
		    matchTimer.second-=1 ;
		 } 
		 
		 if (matchTimer.second<=-1){ 
		    matchTimer.ms=9 ;
		    matchTimer.second=59 ;
		    matchTimer.minute-=1;
		 } 
		
		 if ( matchTimer.minute<=-1){ 
		     matchTimer.ms=0 ;
		     matchTimer.second=0 ;
		     matchTimer.minute=0;
		     stop = true;
		 } else {
		    matchTimer.ms-=1 ;
		 }
		  	var dms=matchTimer.ms |0;
		
		  	var ds=matchTimer.second | 0;
		    if( ds < 10 ){
		    	ds = '0' + ds;
		    }
		    
		    var dm = matchTimer.minute | 0;    
		
		  	matchTimer.output.value=  dm + ":" + ds + "." + dms;
		 
		   if(  stop != true ){ 
		    	matchTimer.timer=setTimeout( "downTimer()",100)
		   }else{
		   		matchTimeOut();
		   }
	};	

function playAlarm(  start ) {

	var ma = document.getElementById("mat_alarm");
	   eval(' ma.play();');
}

function matchTimeOut(){

		show=false;
		ushow=false;
		
		clearTimeout( matchTimer.timer );
		if( pinTimer.cr.value != 0 ){ 		// there is an active pin ?
			return;
		}	

		playAlarm(  'true' );
		
		
}


function clock_stop(){
			matchTimer.stop();
			toketa();
}

function clock_start(){
		matchTimer.start();
		 if(  pinTimer.sonomama.checked == true ){
			 pinTimer.sonomama.checked = false; 
			 pinTimer.start( pinTimer.cr.value );
		 }
}

function matchTrigger() {

	document.getElementById('buttonFocusHold').focus();
		
	if( matchTimer.cr == null ){
		matchTimer.setup();
		pinTimer.initialize();
	}	
	if( matchTimer.cr.value == 1) {
			clock_stop();
		} else {
			clock_start();
		}
}

function clearDown() {

		matchTimer.reset( '0:00.0');
		pinTimer.reset();
	
 }

 /* Uptimer for pins */


 function upTimer(){ 
 
 var stop=false;
 var changed = false;
 
 pinTimer.ms++ ;
 if (pinTimer.ms>9){ 
    pinTimer.ms=0 ;
    changed = true;
    pinTimer.second ++ ;
 }
	
  var dms=pinTimer.ms | 0;
  // if( dms < 10 ){     	dms = '0' + dms;     }
  var ds=pinTimer.second | 0;
  if( ds < 10 ){
	    	ds = '0' + ds;
   }
	    
   pinTimer.output.value =   ds + "." + dms;
 
    if( changed && pinTimer.second == 15 )
    	 pinTimer.pin_score_div.innerHTML ='Y';
 
    if( changed && pinTimer.second == 20) {
    	pinTimer.pin_score_div.innerHTML = 'W';
		// check for short pin here	
		if(  document.getElementById( 'ScoreWazaari' + pinTimer.cr.value ) .value >= 1 ){
			stop = true;
			pinTimer.pin_score_div.innerHTML ='I'; 
		}
	}
    if( changed && pinTimer.second == 25 ){
        stop = true;
      	 pinTimer.pin_score_div.innerHTML ='I'; 
     }

       
   if(  !stop  ){ 
	   pinTimer.timer=setTimeout("upTimer()",100)
   }else{
	    pinTimer.cr.value = 0;
   		matchTimeOut();
   	// alert("Time Over!");
   }
} 
 
function upTrigger( p ) {
 pinTimer.reset();
 pinTimer.start( p );
}

function setPinScore(){
 if (pinTimer.cr.value != 0  ) {
 	
 	if (pinTimer.pin_score_div.innerHTML == 'W') {
 		eval('document.score.wazaari' + pinTimer.cr.value + '.click()');
 	}
 	if (pinTimer.pin_score_div.innerHTML == 'Y') {
 		eval('document.score.yuko' + pinTimer.cr.value + '.click()');
 	}
 	if (pinTimer.pin_score_div.innerHTML == 'I') {
 		eval('document.score.ippon' + pinTimer.cr.value + '.click()');
 	}
 	pinTimer.pin_score_div.innerHTML = '';
 }
}

function toketa() {

	pinTimer.stop(); 
 
 }
 
window.onload = judoTimer.init;
window.onunload = judoTimer.cleanup;
//window.onkeyup =  judoTimer.handleKeyPress;

if (document.addEventListener){ // FF

document.addEventListener('keyup', judoTimer.handleKeyPress, false); 
 
} else if (document.attachEvent){ // IE
// document.attachEvent('keyup', judoTimer.handleKeyPress);
document.onkeyup =  judoTimer.handleKeyPress;

} 