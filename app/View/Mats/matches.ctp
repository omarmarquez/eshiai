<table  cellpadding = "0" cellspacing = "0" align="right">
	 <tr>
		<th><?php __('Action'); ?></th>
		<th><?php __('Match'); ?></th>
		<th><?php __('Blue'); ?></th>
		<th><?php __('White'); ?></th>		
		<th><?php __('Length'); ?></th>	
		<th><?php __('Pool'); ?></th>
		<th><?php __('Pool'); ?></th>
	  </tr>	
		<?php
		$i = 0;
		//debug($mat['Matches']);
	//	foreach ($mat['Pool'] as $pool):
			foreach ($mat['Matches'] as  $m ):
		//debug($m);
			$class = null;
		
			if( !isset($m['qorder']) || !$m['qorder']){
				//continue;
			}
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
			
			$action = $ajax->link( 'select'
				,array( 'controller' => 'mats', 'action' => 'select' ,$m['mat_id'], $m['id']  )
				,array( 'update' => 'mat_match' ) 
				,'Load this match?' 
				); 
			
			// $action = $html->link('select', array( 'action'=>'select', $m['mat_id'], $m['id'] ), null, sprintf(__('Load this match?', true)));
			if($m['id']==$mat['Mat']['current_match_id'] ){
				$action ='<b>current</b>';
			}
			if($m['hold']==1){
					$action ='<b>On Hold</b>';
				
			}
			$cn = array( 1 => '', 2 => '' );
			foreach( $m['Player'] as $p  ){
				$c = $p['Registration']['Competitor'];
				$cn[ $p['pos'] ] = $c['first_name'] . ' ' . $c['last_name'];
			}
		?>
		<tr <?php echo  $class;?>>
			<td><?php echo  $action?></td>		
			<td><?php echo $m['match_num'];?></td>		
			<td><?php echo $cn[1];?></td>		
			<td><?php echo $cn[2];?></td>		
			<td><?php echo $m['Pool']['match_duration'] .":00";?></td>		
			<td>
				<?php echo  $html->link(__( $m['Pool']['pool_name'] , true), array('controller'=> 'pools', 'action'=>'view', $m['Pool']['id'])); ?>
			</td>
			<td>
				<?php echo  $html->link(__( 'display' , true), array('controller'=> 'pools', 'action'=>'display', $m['Pool']['id'])); ?>
			
			</td>		
	
		</tr>
		<?php endforeach; ?>
		<?php //endforeach; ?>
		</table>