		<table class="match">
		 <tr>
 	<?php 
 		$board=array(
 			1 => array( 'shido'=>0, 'yuko'=>0, 'wazaari'=>0, 'ippon'=>0, 'osaekomi'=>0,  'hantei'=>0 )
			,2 => array( 'shido'=>0, 'yuko'=>0, 'wazaari'=>0, 'ippon'=>0, 'osaekomi'=>0 , 'hantei'=>0)
		 );
 		$cid=array();
 		//debug($match['Player']);
 		if( !empty($mat['Match']['Player'])){
 		foreach( $mat['Match']['Player']  as $k => $v ){
 			$cid[ $v['registration_id'] ] = $v['pos'];
 		}
 		foreach ($mat['Match']['Score'] as $s ): 
  			$board[ $cid[ $s['registration_id' ] ] ][$s['score']] ++;
 		endforeach;
 		}
	?>
 
	<?php  if( !empty($mat['Match']['Player']))
		foreach (array(1,2) as $i):
		$regId = 0; 
		$name ='Bye Bye';
		$style='style="background-color:#669"'; //blue
		if( $i == 2 ){   
			$style='style="background-color:#FFF"';
		 }
		if( isset( $mat['Match']['Player'][$i-1]['Registration']['Competitor']) ) {
			$p=$mat['Match']['Player'][$i-1];
			$regId =  $p['Registration']['id'];
			$pd =  $p['Registration']['Competitor'];
			$name = ucwords($pd['first_name'] . " " .  $pd['last_name'] );
			$name = $pd['Club']['club_abbr'] ."::" . $name;
		}
	?>
	<td colspan="1" <?php echo $style;?>>
		<h2><?php echo  $name ?></h2>
		<?php
			$pinFunction = $ajax->remoteFunction( 
  				array('model'=>'Mat'
						,'name' => 'pinform' 
						,'url'=>array( 
								'controller'=>'mats'
								,'action'=>'set_pin'
								,$mat['Mat']['id']
								,$i
							)
						,'update'=>''
					)
  			); 

		echo $ajax->form('score' . $i ,'post',
				array('model'=>'Score'
						,'name' => 'score' . $i
						,'url'=>array( 
								'controller'=>'scores'
								,'action'=>'add'
							)
						,'update'=>''
						//,'complete' => "document.score" . $i ."['data[remove]'].checked=false;"
					)
				);
		 
				echo $form->hidden('Mat.id' , array('value'=>$mat['Mat']['id']));
				echo $form->hidden('Score.match_id' , array('value'=>$mat['Match']['id']));
				echo $form->hidden('Score.registration_id', array('value'=>$regId));
				echo $form->hidden('Score.score', array('value'=> ''));
				// echo $form->hidden('ippon', array(  'value'=> $board[$i]['ippon'] ));
				echo $form->hidden('osaekomi', array(  'value'=> $board[$i]['osaekomi'] ));
				foreach ($scoring as $s ):
					if( $s == "hantei"){ continue; }
					$onclick = "score('" . $i . "','" . $s ."');";
					if( $s == "osaekomi"){
					 	$onclick = "$pinFunction;$onclick";
					}
				?>
				<div class="">
				<input type="submit" name="<?php echo $s?>" value="<?php echo $s?>" onclick="<?php echo  $onclick?>"  />
				</div>
				<?endforeach;?>
				<input type="checkbox" name="data[remove]"   value="1"/><b>Remove</b>	

		<div class="reduce_30">
		<table class="score">
			<tr>
				<td>I</td>
				<td>W</td>
				<td>Y</td>
				<td>S</td>
			</tr>
			<tr>
				<td><?php echo $form->input('ippon', array( 'label'=>false,'div'=>false,'value'=>$board[$i]['ippon'],'size'=>2));?></td>
				<td><?php echo $form->input('wazaari', array( 'label'=>false,'div'=>false,'value'=>$board[$i]['wazaari'],'size'=>2));?></td>
				<td><?php echo $form->input('yuko', array( 'label'=>false,'div'=>false,'value'=>$board[$i]['yuko'],'size'=>2));?></td>
				<td><?php echo $form->input('shido', array( 'label'=>false,'div'=>false,'value'=>$board[$i]['shido'],'size'=>2));?></td>
			</tr>
		</table>
	</div>

	<?php echo 
		echo $form->end();	
	?>
			
	
	</div>
	</td>

	<?php if( $i == 1 ){ ?>
		<td style="background-color:#99c68e">
			<center>	
			<div class="mt_clock">
			<?php echo include('clock_match.ctp'); ?>
			</div>
			<div class="reduce_30">
			<?php 	include('clock_pin.ctp'); ?> 
			</div>
			</center>			
		</td>	
	 	<?php } // if ?>
	<?php endforeach;	?>

	</tr>

	<tr>
	<td colspan="3">

	</td>
	</tr>
</table>
<div id="matFocusHold"><input type="button" id="buttonFocusHold" /></div>