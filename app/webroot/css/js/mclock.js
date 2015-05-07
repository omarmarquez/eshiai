
 var matClock = function(){
  
  	var clock_timer=null;

  	var dateObj=null;

  	var clock_h = null;	// hours	
  	var clock_m = null;	// minutes
  	var clock_s = null;	// seconds
  	var clock_ms = null; 	//milli seconds

  	var clock_running = null; 	 
  	var clock_display=null;
  	var clock_reset=null;
  	
 
 this.init = function(){
 	
 		this.clock_display = document.clockform['data[Mat][mat_clock]'];
		this.clock_running = document.clockform['data[Mat][clock_running]'] ;
		this.clock_reset = document.clockform['init'] ;

 };
 
 this.cleanup = function(){
 	
 		this.clock_display = null;
		this.clock_running = null;
		this.clock_reset = null;
		
 };
 
 this.timer = function (){ 
   
   var stop=false;
   
   if (this.clock_ms <= 0){ 
      this.clock_ms	= 9 ;
      this.clock_s 	-=1 ;
   } 
   
   if ( this.s <= -1){ 
      this.clock_ms=9 ;
      this.clock_s=59 ;
      this.clock_m-=1;
   } 
  
   if ( this.clock_m <= -1){ 
       this.clock_ms=0 ;
       this.clock_s=0 ;
       this.clock_m=0;
      	stop = true;
   } else {
      this.clock_ms-=1 ;
   }
    
    var dms=this.clock_ms;
      if( dms < 10 ){
      	dms = '0' + dms;
      }
    	var ds=this.clock_s;
      if( ds < 10 ){
      	ds = '0' + ds;
      }
      
      var dm = this.clock_m;
      if( dm < 10 ){
      	dm = '0' + dm;
      }
      
      this.clock_display.value = dm + ":" + ds + "." + dms;
      
     if(  stop != true ){ 
      this.clock_timer=setTimeout("this.timer()",100)
     }else{
     	timeOut();
     }
  } ;
  
  
  this.toggle = function() {
  if ( this.clock_running.value ==0 ) {
  	this.clock_m = this.clock_display.value.substring(0,2) ;
  	this.clock_s = this.clock_display.value.substring(3,5) ;
  	this.clock_ms = this.clock_display.value.substring(6) ;

  	this.timer();	
  	this.clock_running.value =1;
  	} else {
  	
  		if(this.clock_running.value == 1 ) {
  			this.clock_running.value == 0;
			clearTimeout( this.clock_timer );
  			clearUp();
  		
  		} else {
  			this.clock_running.value == 1;
			this.clock_timer = setTimeout("this.timer()",100 );
  		}
  	}
  }
  
 
 this.reset = function() {  //reset button
  
  	var answer = confirm("Reset Time?");
  
  	if(  answer) {
  	clearTimeout(downtimer);
  	this.clock_h=0;
  	this.clock_m=0;
  	this.clock_s=0;
  	this.clock_ms=0;
 
 	this.clock_running.value=0;
	this.clock_display.value = this.clock_reset.value;	
  
 	clearUp();
  	}
 }
  
 };


 function timeOut(){
  
  		this.clock_running.value == 0;
		
		ushow=false;
  		
  		clearTimeout( downtimer );
  		clearTimeout( uptimer );
  		
  		setPinScore();
  		
  	   	alert("Time Over!");
  	
  };
  
 
 
 //start Application here
window.onload =  matClock.init;
window.onunload = matClock.cleanup;
 
