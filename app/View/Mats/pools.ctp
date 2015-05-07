<div class="menu_item">
<?php 
	  	echo  $ajax->link( 'refresh',
	  			array( 'controller' => 'mats', 'action' => 'pools/'. $mat['Mat']['id']),
	  			array( 'update'  => 'mat_' . $mat['Mat']['id'] )
	  	);
	  	?>
</div>
<div class="menu_item">
	  	<?php
	  	$stat = $mat['Mat']['mat_status'];
	  	if( $stat == 0 ){
	  	  echo $ajax->link( 'Release',
	  			array( 'controller' => 'mats', 'action' => 'statusChange/'. $mat['Mat']['id'] . '/1'),
	  			array( 'update'  => 'mat_' . $mat['Mat']['id'] )
	  	);
	  		
	  	}else{
	  	  echo $ajax->link( 'Hold',
	  			array( 'controller' => 'mats', 'action' => 'statusChange/'. $mat['Mat']['id'] . '/0'),
	  			array( 'update'  => 'mat_' . $mat['Mat']['id'] )
	  	);
	  		
	  	}
	  	?>
	  </div>
	  
	  <table>
	   <tr>
			<th colspan="1">Pool</th>	   
			<th>Age</th>	   
			<th>Competitors</th>	   
		  	<th>Matches</th>
		  	<th>Complete</th>
	  		<th>Remain</th>
			<th colspan="3">Order</th>	   
		</tr>
		
	  <?php 
	  	$mt = 0;
	  	$mc = 0;
	  	$rt = 0;
	  	$j=0;
	  	$m_time = 0;
	  	if( isset($mat['Pool'])):
	  	foreach( $mat['Pool'] as $pool ):
	
		$class = null;
		if ($j++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		//debug( $pool ); exit(0);
 		$pm = $pool['match_count'] ; //count ( $pool['Match'] ) ;
 		$pc = $pool['completed_count'] ; //count ( $pool['MatchComplete'] ) ;
 		$pr=  $pool['registration_count'] ; // count( $pool['Registration'] );
 		$mt += $pm  ;
	  	$mc += $pm - $pc   ;
	  	$rt += $pr;
	  	$m_time +=  ($pm-$pc) * $pool['match_duration'];
	  	
	  	?>
	  <tr <?php echo $class;?>>
	  	<td>
	  	<div id='<?php echo $pool['id'];?>'  class='Draggable' > <?php echo  $html->link( $pool['pool_name'] .'/'. $pool['division'] .'/'. $pool['category'], array('controller'=> 'pools', 'action'=>'view', $pool['id']) ) ?></div></td>
	  	<td><?php echo $pool['min_age'] . ' to ' . $pool['max_age'];?></td>
	  	<td><?php echo $pr ;?></td>
	  	<td><?php echo $pm ;?></td>
	  	<td><?php echo $pc ;?></td>
	  	<td><?php echo $pm - $pc;?></td>
	  	<td><?php echo $pool['qnum'] ;?></td>
	  	<td>
	  	<?php echo  $ajax->link('[+]', array('controller'=> 'mats', 'action'=>'pool_order_down', $pool['id']), array('update' => 'mat_'. $mat['Mat']['id']) ) ?>
	  	</td>
	  	<td>
	  	<?php echo  $ajax->link('[-]', array('controller'=> 'mats', 'action'=>'pool_order_up', $pool['id']) , array('update' => 'mat_'. $mat['Mat']['id'])) ?>
	  	</td>
	  	
	  </tr>
	  	<?php
			echo $ajax->drag( $pool['id'], array('revert'=> false) );
			endforeach; 
			endif;
			
		?>
	  

	  <tr>
	  	<td colspan="2"><b>Mat totals:</b></td>
	  	<td><b><?php echo $rt;?></b></td>
	  	<td><b><?php echo $mt;?></b></td>
	  	<td><b><?php echo $mt -$mc;?></b></td>
	  	<td><b><?php echo $mc;?></b></td>
	  	<td colspan="3">&nbsp;</td>
	  </tr>
	  <tr>
	  	<td colspan="5">&nbsp;</td>
	  	<td colspan='1'><?php  echo round( $m_time / 60, 1) . " hours"?></td>
	  	<td colspan="3">&nbsp;</td>
	  </tr>
	  </table>
