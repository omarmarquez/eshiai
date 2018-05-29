	<table  cellpadding = "0" cellspacing = "0" align="right">
	 <tr>
		<th colspan="1"><?php echo __('Pool'); ?></th>
		<th colspan="1"><?php echo __('Match #'); ?></th>
		<th colspan='1'><?php echo __('Action'); ?></th>
		<th><?php echo __('White'); ?></th>		
		<th><?php echo __('Blue'); ?></th>
		<th><?php echo __('Duration'); ?></th>	
	  </tr>	
		<?php
		$i = 0; 
		//debug($mat['Deck']);
		 if( isset($mat['Deck']))
			foreach ($mat['Deck'] as  $m ):
		// debug($m);
			$class = null;
		
			if( !isset($m['Match']['qorder']) || !$m['Match']['qorder']){
				//continue;
			}
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
						
			$action1 = $this->Html->link( 'rush'
				,array( 'controller' => 'mats', 'action' => 'put_at_top' , $m['mat_id'] ,  $m['id']  )
				, null
				,'Send to top of deck?' 
				);
			$action2 = '';
			if( false && $m['Pool']['type'] == 'rr'){
				
				$action2 = $this->Html->link( 'skip'
				,array( 'controller' => 'mats', 'action' => 'put_at_skip' , $m['mat_id'] ,  $m['id']  )
				, null
				,'Skip this match?' 
				);
			}
				
			if( $i==  1 && false){
				$action1 = $html->link(__('award',true), array(),array( 'onclick' =>
								"javascript:
										document.getElementById('popup_award').style.display='inline';
										document.getElementById('MatAwardForm')['data[Match][id]'].value=" . $m['id'] . ";
										return false;"
					));
				$action2 = $html->link( 'deck'
				,array( 'controller' => 'mats', 'action' => 'put_at_bottom' ,$m['mat_id'],$m['id']  )
				, null
				,'Send to bootom of deck?' 
				);
					
			}
			// $action = $html->link('select', array( 'action'=>'select', $m['mat_id'], $m['id'] ), null, sprintf(__('Load this match?', true)));
			//if($m['Match']['id']==$mat['Mat']['current_match_id'] ){
			//	$action2 ='<b>current</b>';
			//}
			if($m['hold']==1){
					$action2 ='<b>On Hold</b>';
				
			}
			//debug($m);
			$cn = array( 1 => '', 2 => '' );
			foreach( $m['Player'] as $p  ){
				if( isset( $p['Registration']['Competitor'] )):
				$c = $p['Registration']['Competitor'];
				$cn[ $p['pos'] ] = $p['Registration']['club_abbr'] . ':&nbsp;'. $c['first_name'] . ' ' . $c['last_name'];
				endif;
			}
		?>
		<tr <?php echo  $class;?>>
			<td>
				<?php echo  $html->link(__( $m['Pool']['division'] .' ' . $m['Pool']['pool_name']  .' ' . $m['Pool']['category'], true), array('controller'=> 'pools', 'action'=>'view', $m['Pool']['id'])); ?>
			</td>
			<td>#<?php echo $m['match_num'];?></td>		
			<td>&nbsp;<?php echo  $action1;?> &nbsp;<?php echo  $action2?></td>		
										
			<td class="white_row"><?php 
					echo $cn[1]. "&nbsp;";
				?>
			</td>		
			<td class="blue_row" ><?php 
					echo $cn[2]. "&nbsp;";
				?>
			</td>		
			<td><?php echo $m['Pool']['match_duration'] .":00";?></td>		
	
		
		</tr>
		<?php endforeach; ?>
		<?php //endforeach; ?>
		</table>