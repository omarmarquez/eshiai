<table cellpadding="0" cellspacing="0">

<?php
    $html=$this->Html;
    $form=$this->Form;
    $ajax=$this->Js;
$i = 0;
foreach ($pools as $pool):
	$class = null;
	if( empty($pool['Registration'])){
		continue;
	}
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	//	echo '<tr>';
	}
?>
	<tr <?php echo $class;?>>
		<td id='pool_<?php echo  $pool['Pool']['id']; ?>'>	
			<h4>
			<?php echo  $html->link( $pool['Pool']['pool_name'] .' (' . $pool['Pool']['sex'] .') ' . $pool['Pool']['division'] .' '. $pool['Pool']['category']
				, array('controller'=> 'pools', 'action'=>'view', $pool['Pool']['id']) ) ?>
			<?php echo  ' ' .$pool['Pool']['min_age'] ." to ".  $pool['Pool']['max_age'] ?>
			<?php echo  ' ,' . $pool['Pool']['min_weight'] ." - " . $pool['Pool']['max_weight'] .' lbs/kgs'?>
			<?php echo   $pool['Pool']['type'] . ' ' . $pool['Pool']['match_duration'] . ' mins. '.  $pool['PoolStatus']['status']?>
		</h4>
	<div style="height:20px;">&nbsp;</div>
	<?php if($pool['Pool']['status'] == 8){?>
		<div class='float_right'>
			<div class="menu_item">
				<?php echo $html->link('Pool Awarded', array( 'action'=>'statusAwarded',  $pool['Pool']['id'])
					, null, 'Mark this pool as awarded?' );			
				 ?>
			</div>
			<div class="menu_item">
				<?php echo $html->link('Pool Contested', array( 'action'=>'statusContested',   $pool['Pool']['id'])
					, null,  'Mark this pool as contestd (Send back to the Head Table) ?'  );			
				 ?>
			</div>
		</div>
	<?php } ?>
	<div style='width:50%'>
		<table>
		 <tr><th colspan="3">&nbsp;</th><th>Ippons</th><th>received</th></tr>
		<?php $j = 0; 	foreach ($pool['Registration'] as $reg): ?>
		<tr>
			<td> <?php echo $reg['bracket_pos'] ; ?></td>
			<td> <?php echo $reg['Competitor']['last_name'] .", ". $reg['Competitor']['first_name']; ?></td>
			<td> <?php echo $reg['Competitor']['Club']['club_name'] ; ?></td>
			<td> <?php echo isset( $ippons[ $reg['id'] ] )? $ippons[ $reg['id'] ]:0; ?></td>
			<td><div id='reg_<?php echo $reg['id'];?>'>
				<?php if( ! $reg['awarded']):  
 		       	   echo $ajax->link( $html->image('check_box_empty.gif') ,
	  				array( 'controller' => 'registrations', 'action' => 'award/'. $reg['id'] ),
	  				array(   'update'  => '#reg_' . $reg['id'] , 'escape' => false 
	  				,'confirm'=> "Award " . $reg['Competitor']['last_name'] .", ". $reg['Competitor']['first_name'] . "?"
	  				));
				
				 else: 
					echo $html->image('check_box_black.gif');
				 endif; 
 				 
				 ?></div>
			</td>
			
		</tr>
		<?php  endforeach;  ?>

	 </table>
	 </div>
	<div style="height:10px;">&nbsp;</div>
		</td>
	</tr>

 <?php endforeach; ?>

</table>	
<?php  echo $this->Js->writeBuffer(); // Write cached scripts ?>
