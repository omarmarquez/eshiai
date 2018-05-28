<html>
<head>
<title>ScoreBoard</title>
<?php
      echo $this->Html->css('score-board');
      echo $this->Html->script('jquery');
      echo $this->Html->css('jquery-ui');
 
      echo $this->Html->script( array('jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.position' ),true );
      echo $this->Html->script( array('jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable','jquery.ui.resizable' ,'jquery.ui.dialog'),true );
 

        echo $this->Html->script('jquery.stopwatch');
        echo $this->Html->script('jquery.scoreboard');

	$match_time = "'5'";
    $mat_id = 0;
    $match_id =0;
    $mat_status = 1;
	$sound_profile = "00";
	$pool_name  =   "&nbsp;";
    $clubw = $clubb = '&nbsp;';
    $cbname = "white";
    $cwname = "blue";
    $aurl ="";  // award url

	if(  isset( $mat['Deck'][0] ) ){
	    
        $mat_id = $mat['Mat']['id'];
        $mat_status = $mat['Mat']['mat_status'];
        $match_id = $mat['Deck'][0]['id'];
        
        $sound_profile = str_pad( $mat['Mat']['sound'],2,'0',STR_PAD_LEFT);
        $match_time = $mat['Deck'][0]['Pool']['match_duration'];
        $pool_name = $mat['Deck'][0]['Pool']['pool_name'] ;
        
        if( isset($mat['Deck'][0]['Player'][1] )){
        $p1 = $mat['Deck'][0]['Player'][1]['Registration']['Competitor'];
        $cwname =  $p1['first_name'] . ' ' . $p1['last_name'] ;
        $clubw = $mat['Deck'][0]['Player'][1]['Registration']['club_abbr'];
        }
        if( isset($mat['Deck'][0]['Player'][0] )){
        $p0 = $mat['Deck'][0]['Player'][0]['Registration']['Competitor'];
        $cbname = $p0['first_name'] . ' ' . $p0['last_name'] ;
        $clubb = $mat['Deck'][0]['Player'][0]['Registration']['club_abbr'];
        
        $aurl = $this->Html->url( array( 'controller' => 'matches', 'action' => 'award', $mat['Deck'][0]['id']));
         // debug($mat);
         }
    }
?>
</head>
<body>
    <input type="hidden" id='match_id' value='<?php echo $match_id?>'/>
  <div id="container">
    <div id="scoreboard">
	<table>
	   <tr>
	        <td colspan=2><div class="match"><div id="clock1"></div></div><td>
	        <td colspan=4><span class="controls"></span></td>
	   </tr>
        <tr class="board_row">
            <td colspan=2>
                <div style="text-align:left;overflow:hidden;white-space:nowrap;width:500;clear:none;float:left;" ><?php echo $pool_name ?></div>
                <div style="float:right;clear:none;"><div id="clock2"></div></div>
            </td>
            <td>I</td>
            <td>W</td>
            <td>S</td>
        </tr>
	    <tr class="board_row_white" style="text-align:center;">
	        <td colspan=2 style="text-align:left;"><span class="white_name1"><?php echo $clubb ?></span></td>
            <td rowspan=2><span class="white_i">0</span></td>
            <td rowspan=2><span class="white_w">0</span></td>
            <td rowspan=2><span class="white_s">0</span></td>
 	    </tr>
        <tr class="board_row_white" style="text-align:center;" >
            <td colspan=2 ><div style="text-align:left;overflow:hidden;white-space:nowrap;width:800;" ><?php echo $cbname ?></div></td>
        </tr>
 	    <tr class="board_row_blue" style="text-align:center;">
	        <td colspan=2 style="text-align:left;"><span class="blue_name1"><?php echo $clubw ?></span></td>
            <td rowspan=2><span class="blue_i">0</span></td>
            <td rowspan=2><span class="blue_w">0</span></td>
            <td rowspan=2><span class="blue_s">0</span></td>
 	    </tr>
       <tr class="board_row_blue" style="text-align:center;">
            <td colspan=2 ><div style="text-align:left;overflow:hidden;white-space:nowrap;width:800;" ><?php echo $cwname ?></divn></td>
        </tr>
</table>
</div>	

  </div>
     <input type="hidden" name="mat_status" id="mat_status" value="<?php echo $mat_status?>"/>
     <input type="hidden" name="alarm" id="alarm" value="<?php echo $this->Html->url( "/files/wav/alarm${sound_profile}.wav" ,true)?>"/>
    <span id="sound_element"><embed id="alarm_embed" src='<?php echo $this->Html->url( "/files/wav/alarm${sound_profile}.wav" ,true)?>' hidden=true autostart=false loop=false ></embed></span>
	<audio id="audio_embed" preload="True">
	     <source src='<?php echo $this->Html->url( "/files/wav/alarm${sound_profile}.wav" ,true)?>' type="audio/wav" />
	</audio>
	<div id="award_dialog" title="Award Match">
	    <?php include APP. DS ."View/Matches/award.ctp";?>	    
	</div>
	
	<script type="text/javascript">
		$(function() {
		    Timer = function(){ this.timer = 0 ;};
		    var mainTimer = new Timer();
		    var pinTimer = new Timer();
		     
		    $('#award_dialog').dialog({
			autoOpen: false, 
			resizable: false,
      			height: "auto",
      			width: 620,
      			modal: true
			});

			$('#clock1').stopwatch('init', 'main', -1, mainTimer );
			$('#clock2').stopwatch('init', 'pin', 1, pinTimer );
			$('#scoreboard').scoreboard( mainTimer, pinTimer, '<?php echo addslashes( $pool_name) ?>','<?php addslashes(  $cwname) ?>', '<?php addslashes( $cbname) ?>', '<?php echo $aurl ?>' );
			
			$('#clock1').trigger('reset', [ <?php echo $match_time ?>, '0', '0','0']);
			$('#scoreboard').focus();
		});
		
	    /*
 
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
        */
	</script>
	
</body>
</html>
<?php  echo $this->Js->writeBuffer(); // Write cached scripts ?>
