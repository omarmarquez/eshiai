(function($) {
	$.fn.scoreboard = function( mTimer, pTimer, wn, bn, pn  ) {
		var board = this;
		var osai_time = 20;
		var osai_play = false;
		var mainTimer = mTimer;
		var pinTimer = pTimer;
		board.addClass('scoreboard');
		/*
		//console.log(board);
		
	
		//console.log(board.html());
		
		*/
		var main_clock = $('#clock1');
		var pin_clock = $('#clock2');
		
		var controls = board.find('.controls');
		
		controls.append('<input type="button" class="start" value="Hajime" />');
		controls.append('<input type="button" class="stop" value="Mate" />');
		controls.append('<p/>');
	    controls.append('<input type="text" class="min1" value="3" size="2" style="display:inline;" />:');
	    controls.append('<input type="text" class="sec1" value="0" size="2"  style="display:inline;"/>');
		controls.append('<input type="button" class="reset" value="Reset"  style="display:inline;"/>');
		controls.append('<p/>');
		controls.append('<input type="button" class="osai" value="Osaekomi" />');
		controls.append('<input type="button" class="toketa" value="Toketa" />');
		controls.append('<p/>');
	    controls.append('<input type="text" class="sec2" value="0" size="3"  style="display:inline;"/>');
		controls.append('<input type="button" class="reset2" value="Reset"  style="display:inline;"/>');
		controls.append('<p/>');
		controls.append('<input type="checkbox" class="remove" value=""  style="display:inline;"/>Remove');
		controls.append('<p/>');
		controls.append('<input type="button" class="breset" value="Reset Board"  style="display:inline;"/>');
		controls.append('<input type="button" class="award" value="Award Match"  style="display:inline;"/>');
		controls.append('<p/>');
		//controls.append('<br/> <span class="debug">_</span>');


		var start = board.find('.start');
		var award = board.find('.award');
		var stop = board.find('.stop');
		var deb = board.find('.debug');
		stop.hide();
		
		var min1 = board.find('.min1');
		var sec1 = board.find('.sec1');
		var reset = board.find('.reset');
		var rem = board.find('.remove');
		var board_reset = board.find('.breset');

		start.bind('click', function() {
		    main_clock.trigger('start');
		    start.hide();
		    award.hide();
		    stop.show();
		});
		stop.bind('click', function() {
		    main_clock.trigger('stop');
		    stop.hide();
		    start.show();
		    award.show();
		});
		
		function reset_clock1(){
		    main_clock.trigger('reset', [min1.val(),sec1.val(), '0','0']);
		    stop.hide();
		    start.show();		
		    award.show();		
		}
		reset.bind('click', function() {
		    if( ! confirm('Reset') ) return false;
		    reset_clock1();
		});

		var osai = board.find('.osai');
		var toketa = board.find('.toketa');
		toketa.hide();
		
		var sec2 = board.find('.sec2');
		var reset2 = board.find('.reset2');


		osai.bind('click', function() {
		    pin_clock.trigger('reset', ['0',sec2.val(), '0', osai_time ]);
		    pin_clock.trigger('start');
		    osai.hide();
		    toketa.show();
		});
		toketa.bind('click', function() {
		    pin_clock.trigger('stop');
		    toketa.hide();
		    osai.show();
		    osai_play = false;
		});

		function reset_clock2(){
		    pin_clock.trigger('reset', ['0',sec2.val(), '0', osai_time]);
		    toketa.hide();
		    osai.show();		
		}
				
		reset2.bind('click', function() {
		 //   if( ! confirm('Reset') ) return false;
		    reset_clock2();
		});

		
		/////////////////
		var white_i = board.find('.white_i');
		var white_w = board.find('.white_w');
		var white_y = board.find('.white_y');
		var white_s = board.find('.white_s');
		var blue_i = board.find('.blue_i');
		var blue_w = board.find('.blue_w');
		var blue_y = board.find('.blue_y');
		var blue_s = board.find('.blue_s');

		
		white_i.bind( 'click', function(){
			v = white_i.text()|0;
			inc = rem.is(':checked')?-1:1;
			v = v+ inc ;
			if( v > 1 ) v=1;
			if( v< 0 ) v = 0;
			white_i.html( v );
		});
		blue_i.bind( 'click', function(){
			v = blue_i.text()|0;
			inc = rem.is(':checked')?-1:1;
			v = v+ inc ;
			if( v > 1 ) v=1;
			if( v< 0 ) v = 0;
			blue_i.html( v );
		});
		white_w.bind( 'click', function(){
			v = white_w.text()|0;
			inc = rem.is(':checked')?-1:1;
			v = v+ inc ;
			if( v >1 ){ white_i.click(); v=2;}
			if( v< 0 ) v = 0;
			white_w.html( v );
			if( osai_play == 'white' ){
				pin_clock.trigger('adjust', [ '0', v?15:20] );
			}
			
		});
		blue_w.bind( 'click', function(){
			v = blue_w.text()|0;
			inc = rem.is(':checked')?-1:1;
			v = v+ inc ;
			if( v >1 ){  blue_i.click(); v=2;}
			if( v< 0 ) v = 0;
			blue_w.html( v );
			if( osai_play == 'blue' ){
				pin_clock.trigger('adjust', [ '0', v?15:20] );
			}
				
		
		});
		white_y.bind( 'click', function(){
			v = white_y.text()|0;
			inc = rem.is(':checked')?-1:1;
			v = v+ inc ;
			//if( v >1 ){  white_i.click(); v=2;}
			if( v< 0 ) v = 0;
			white_y.html( v );
		});
		blue_y.bind( 'click', function(){
			v = blue_y.text()|0;
			inc = rem.is(':checked')?-1:1;
			v = v+ inc ;
			//if( v >1 ){  blue_i.click(); v=2;}
			if( v< 0 ) v = 0;
			blue_y.html( v );
		});
		white_s.bind( 'click', function(){
			v = white_s.text()|0;
			inc = rem.is(':checked')?-1:1;
			incad = inc == 1?0:1;
			v = v+ inc ;
			if( v< 0 ) v = 0;
			vs = v;
			/*
			if( v + incad ==2  ){  blue_y.click(); v=vs;}
			if( v + incad ==3  ){  rem.click(); blue_y.click();rem.click();blue_w.click(); v=vs;}
			if( v + incad ==4  ){  rem.click(); blue_w.click();rem.click();blue_i.click(); v=vs;}
			*/
			white_s.html( v );
		});
		blue_s.bind( 'click', function(){
			v = blue_s.text()|0;
			inc = rem.is(':checked')?-1:1;
			incad = inc == 1?0:1;
			v = v+ inc ;
			if( v< 0 ) v = 0;
			vs = v;
			/*
			if( v + incad ==2  ){  white_y.click(); v=vs;}
			if( v + incad ==3  ){  rem.click(); white_y.click();rem.click(); white_w.click(); v=vs;}
			if( v + incad ==4  ){  rem.click(); white_w.click();rem.click(); white_i.click(); v=vs;}
			*/
			blue_s.html( v );
		});
		////////////////
		
		function reset_board(){
			
			reset_clock1();
			reset_clock2();
			white_i.html(0);
			white_w.html(0);
			white_y.html(0);
			white_s.html(0);
			blue_i.html(0);
			blue_w.html(0);
			blue_y.html(0);
			blue_s.html(0);
		}
		reset_board();
		board_reset.bind('click', function() {
		   if( ! confirm('Reset') ) return false;
			if( $('#match_id').val() == 0 ){
				reset_board();
			}else{
				location.reload(); 
			}
		});
		award.bind('click', function() {
			   if( ! confirm('Award Match') ) return false;
				if( $('#match_id').val() != 0 )
					$('#award_dialog').dialog( 'open' ); 
			   return false;
				    
			});

		$(document).keydown(function(event) {
			
			 rf = false;
			 prc = false;
			if (event.which == 13) {
				     event.preventDefault();
			}
			switch( event.which ){
			//case 119: // F8 
			case 27: // esc
					if( start.is(':visible') ){ // clock not running
						if( confirm("Reset"))
							reset_board();
					}
					prc = true;
					break;
			case 13: // enter
			case 1444: // keypad enter
					// matchTrigger();
					//eval( 'document.board_ctl.clock_button.click()' ) ; 
					if( start.is(':visible') ){
							start.click();
					}else{
							stop.click();
					}
					prc = true;
					break;

			case 36:  // blue kp 7
			case 103:  // blue kp 7
					osai_play = "white";
					osai_time = (white_w.text()|0)?15:20;		
					osai.click();
					prc = true;
					break;
			case 109:   // white -
					osai_play = "blue";
					osai_time = (blue_w.text()|0)?15:20;	
					osai.click();
					prc = true;
					break;
				
			case 45: // 0 or .
			case 96: // 0 or .
					toketa.click();
					prc = true;
					break;
			case 110: // Del .
			case 17: //Ctrl
			case 46:
					rem.click();
					break;
			
			case 106: //* 
				if( pinTimer.cr.value == 0 ){ // clock running
					pinTimer.reset();
				}
				prc = true;
				break;
			
			case  8: // <- Reset
				if( matchTimer.cr.value == 0 ){ // clock not running
					reset_board();
				}
				prc = true;
				break;			

			//WHITE
			case 100:
			case 37:  // 4 or left key
					//blue_i.click();
					white_i.click();
					prc = true;
					break;
			case 104: //104 ? 
			case 38: // 8 or up key 
					//blue_w.click();
					white_w.click();
					prc = true;
					break;

			case 101: // 5
					//blue_y.click();
					white_y.click();
					prc = true;
					break;
			case 98: // 2
			case 40: // 2 or down key
					//blue_s.click();
					white_s.click();
					prc = true;
					break;

			//BLUE
			case 107:  // +
						//white_i.click();
						blue_i.click();
						prc = true;
						break;
			case 105:
			case 33:  // 9 or pg up
						//white_w.click();
						blue_w.click();
						prc = true;
						break;
			case 102:
			case 39:   // 6 or left key
						//white_y.click();
						blue_y.click();
						prc = true;
						break;
			case 99:
			case 34:   // 3 or pg dn
						//white_s.click();
						blue_s.click();
						prc = true;
						break;
			default:
				; //deb.html( event.which );
			} // switch
			
			
			if( prc && rem.is(':checked')  ) rem.click();  // remove removal
			
		});
		
		var alarm = $('#alarm').val();	
	    var audioElement = document.createElement('audio');
	    audioElement.setAttribute('src', alarm );
		
		function clockTimeOut( origin ) {
			
			if( origin == 'main' && pinTimer.timer != 0 )
				  return;
			
			//$('#sound_element').html("<embed src='" + alarm +"' hidden=true autostart=true loop=false delay=0 ></embed>");
			audioElement.play();
		}
		board.bind( 'timeOut', function( event, origin ){
	        clockTimeOut( origin );
		});
		
	}; // class
})(jQuery);
