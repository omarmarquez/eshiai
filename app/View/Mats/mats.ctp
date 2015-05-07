	<h3><?php echo $ajax->link( __('Ready pools ', true)
					, array('controller'=>'mats', 'action'=>'pools_ready/' . $event_id   )
					, array( 'update' => 'pools_ready')
		);?></h3>
    <div id='pools_ready'></div>
 	 <?php 
 	 echo  $ajax->remoteTimer(
	array(
		'url' => array('controller'=>'mats', 'action'=>'pools_ready/' . $event_id   ),
		'update' => 'pools_ready',
	 	'frequency' => 30
			)
		);	
    ?>

<table>

<?php

$i = 0;
$j =0;
foreach ($mats as $mat):
	$class = null;
	if ($i++ % 2 == 0) {
		if( $i > 1 ) echo "</tr>";
		echo "<tr>";
	}
?>
<td style="width:40%;">
<div id="mata_<?php echo $mat['Mat']['id'];?>" ><p/>
&nbsp;
<table cellpadding="0" cellspacing="0">
	<tr<?php echo $class;?> >
		<td>
			<h3><?php echo $html->link( $mat['Mat']['name'], array('action'=>'view',  $mat['Mat']['id']  ) ); ?></h3>
		</td>
			<td>
			<h3><?php echo $mat['Mat']['location']; ?></h3>
		</td>

	</tr>
	<tr<?php echo $class;?>>
	  <td colspan="4">
	  <div id='<?php echo 'mat_' . $mat['Mat']['id'] ;?>'>
	  <?php   $pools = array();
	  		include('pools.ctp');?>
	  </div>
	  </td>
	</tr>
	<?php 
		
		echo $ajax->dropRemote( 'mat_' . $mat['Mat']['id'],
			array('hoverclass' => 'Droppable'),
			array( 
					'url' => array('controller'=>'mats', 'action'=>'loadPool',  $mat['Mat']['id'] )
					,'with'=>'{draggedid:element.id}'
					,'update' => 'mat_' . $mat['Mat']['id']
					)
			);
	  	echo
	  $ajax->remoteTimer(
	array(
		'url' => array('controller'=>'mats', 'action'=>'pools/' . $mat['Mat']['id']),
		'update' => 'mat_'. $mat['Mat']['id'],
	 	'frequency' => 30
			)
		);	
			
	?>
	</table>
</div>
</td>
<?php endforeach;  ?>

</table>
</td>
</table>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Mat', true), array('action'=>'add')); ?></li>
	</ul>
</div>


	<h3><?php echo $ajax->link( __('Contested pools ', true)
					, array('controller'=>'mats', 'action'=>'pools_status/' . $event_id . '/' . 9 )
					, array( 'update' => 'pools_contested')
		);?></h3>
    <div id='pools_contested'></div>
 	 <?php 
 	 echo  $ajax->remoteTimer(
	array(
		'url' => array('controller'=>'mats', 'action'=>'pools_status/' . $event_id . '/' . 9 ),
		'update' => 'pools_contested',
	 	'frequency' => 30
			)
		);	
    ?>

	<h3><?php echo $ajax->link( __('Completed pools ', true)
					, array('controller'=>'mats', 'action'=>'pools_status/' . $event_id . '/' . 5 )
					, array( 'update' => 'pools_completed')
		);?></h3>
   <div id='pools_completed'></div>
 	 <?php 
 	 echo  $ajax->remoteTimer(
	array(
		'url' => array('controller'=>'mats', 'action'=>'pools_status/' . $event_id . '/' . 5 ),
		'update' => 'pools_completed',
	 	'frequency' => 30
			)
		);	
    ?>


<?php //debug($mats);?>