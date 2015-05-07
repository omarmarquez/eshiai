(function($) {
	//var timeObject.timer = 0;
	$.fn.stopwatch = function( method, cname,  incval , clockTimer ) {
		var clock = this;
	    var timeObject ;
		var end_exp = '0:0';
		/*
		var inc = incval;
		var m = clock.find('.min');
		var s = clock.find('.sec');
		var t = clock.find('.ts');
		var start = clock.find('.start');
		var award = clock.find('.award');
		var stop = clock.find('.stop');
		var reset = clock.find('.reset');
		var active = $('#mat_status').val();
	    var scoreBoard = $('#scoreboard');
		*/
		var inc , m, s, t, start, award, stop, reset, active, scoreBoard, clockName;

		  var methods = {
		    	    init : function( options ) { 
		    	      // THIS 
		    	    		clock.addClass('stopwatch');		
		    	    		//console.log(clock);
			    	    		// This is bit messy, but IE is a crybaby and must be coddled. 
		    	    		//clock.html('<div class="display"><span class="hr">00</span>:<span class="min">00</span>:<span class="sec">00</span></div>');
		    	    		clock.html('<div class="display"><span class="min">00</span>:<span class="sec">00</span>:<span class="ts">0</span></div>');

		    	    		clock.bind( 'start', function( event ){  do_start(); });
		    	    		clock.bind( 'stop', function( event ){ do_stop(); });
		    	    		clock.bind( 'reset', function( event, m1, s1, m2, s2 ){  do_reset(m1, s1, m2, s2); });
		    	    		clock.bind( 'adjust', function( event, m2, s2 ){  do_adjust( m2, s2); });

		    	    		m = clock.find('.min');
		    	    		s = clock.find('.sec');
		    	    		t = clock.find('.ts');
		    	    		start = clock.find('.start');
		    	    		award = clock.find('.award');
		    	    		stop = clock.find('.stop');
		    	    		reset = clock.find('.reset');
		    	    		active = $('#mat_status').val();
		    	    	    scoreBoard = $('#scoreboard');
		    	    	    inc = incval;
		    	    	    clockName = cname;
		    	    	    timeObject = clockTimer;
		    	    	    //alert(clockName);
		    	    		return clock;
		    	    },
		    	    
		    	    getTimer: function( options ){
		    	    	alert( clockName );
		    	    	return timeObject.timer;
		    	    }
		    
		    };    

		    
		    if ( typeof method === 'object' ||  ! method ) {
		        return methods.init.apply( this, arguments );
		    }else{
		    	   return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		    	
		    }	    
		//console.log(clock.html());
		
		// We have to do some searching, so we'll do it here, so we only have to do it once.
		// var h = clock.find('.hr');

		//var alarm = $('#alarm').val();
	    //var audioElement = document.createElement('audio');
	    //audioElement.setAttribute('src', alarm );

	    function do_start(){
			if( active ==0)
				return;
		    timeObject.timer = setInterval(do_time, 100);
		}

		function do_stop(){
			clearInterval(timeObject.timer);
			timeObject.timer = 0;
		}
		
		function do_reset( m1, s1, m2, s2 ) {
			do_stop();
			end_exp = m2 + ':' + s2;
			m.html("0".substring(m1 >= 10) + m1);
			s.html("0".substring(s1 >= 10) + s1);
			t.html( "0" );
		}
		
		function do_adjust( m2, s2 ){
			end_exp = m2 + ':' + s2;
		}

		function end_run() {
			clearInterval(timeObject.timer);
			timeObject.timer = 0;
			//$('#sound_element').html("<embed src='" + alarm +"' hidden=true autostart=true loop=false delay=0 ></embed>");
			//audioElement.play();
			scoreBoard.trigger( 'timeOut' , clockName );
			stop.hide();	
			start.show();
			award.show();  
		}

		function do_time() {
			// parseInt() doesn't work here...
			// hour = parseFloat(h.text());
			minute =  m.text() |0;
			second =  s.text() |0;
			tentse =  t.text() |0;

			tentse = tentse + inc ;

			if(tentse < 0 || tentse > 9 ) {
				tentse = tentse + inc * -1 *  10;
				second = second + inc ;
			}
			
			if(second < 0  || second > 59 ) {
				second = second + inc * -1 *  60;
				minute = minute + inc ;
			}
			
			if( minute < 0 || minute > 59) {
				minute = minute + inc * -1 * 60;
				// hour = hour + inc ;
			}
			
		//	h.html("0".substring(hour >= 10) + hour);
			m.html("0".substring(minute >= 10) + minute);
			s.html("0".substring(second >= 10) + second);
			t.html(tentse);

			if( tentse == 0 && minute + ':' + second == end_exp ){
				end_run();
			}
		}
	};

})(jQuery);
