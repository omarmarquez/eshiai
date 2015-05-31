<?php 	
    $form = $this->Form;
    $html = $this->Html;
    $ajax=  $this->Js;
    
  if( false): //commenting out
	echo $form->create('Pool', array( 'action'=>'print_roster'));
	echo $form->input('Event.id', array('type'=>'hidden','value'=>  $event ) );
?>
<div class="actions">
<table>
 <tr>
 	<td> 
 		<?php echo  $form->input('Pool.division', array('options'=>array(''=>'all' , 'juniors'=>'juniors','seniors'=>'seniors', 'masters'=>'masters'))) ?>
 	</td>
   	<td> 
 		<?php echo  $form->submit('Filter' ) ?>
 	</td>
 	
 </tr>
</table>
</div>
<?php
	echo $form->end() ;
  endif;
?>
<div class="scale_print_200">
<?php foreach( $pools as $pool): ?>
<div id='event_logo'>
	<?php
	foreach ($pool['Event']['EventFile'] as $eventFile)
		if( $eventFile['isLogo'] == 'Y')
		 {	 	
		 	 echo $html->image(array('controller'=>'event_files','action'=>'show',$eventFile['id']),array('title'=>'Logo'));
		 }  
		 $i=0;
		 $class='';
	?>	
</div>
<h2><?php echo $pool['Event']['event_name'] . "::&nbsp;" .  $pool['Pool']['pool_name'] .' '.   $pool['Pool']['category']; ?></h2>
<table>
<tr>
 <td><?php __('Division '.  $pool['Pool']['division'] ); ?> </td>
 <td><?php __( $pool['Pool']['match_duration']. ' minutes matches '.   $pool['Pool']['type'] ); ?> </td>
 <td><?php __('Age ' .  $pool['Pool']['min_age'] .'-'. $pool['Pool']['max_age'] ); ?> </td>
<td><?php __('Weight ' .  $pool['Pool']['min_weight'] .'-'. $pool['Pool']['max_weight'] ); ?> </td>
</tr>
</table>
<div class="related">
	<h3><?php __('Registrations');?></h3>
	<?php if (!empty($pool['Registration'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th>&nbsp;</th>
		<th><?php __('Name'); ?></th>
		<th><?php __('Club'); ?></th>
		<th><?php __('Gender'); ?></th>
		<th><?php __('Age'); ?></th>
		<th><?php __('Weight'); ?></th>
		<th><?php __('SWA'); ?></th>
		<th><?php __('Seed'); ?></th>
		<th><?php __('A'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($pool['Registration'] as $reg):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
			if( preg_match( '/Y/',$reg['upSkill'] .$reg['upWeight'] .$reg['upAge']  )){
				$class = ' class="blue_row"';
			}
		
	?>
		<tr<?php echo $class;?>>
			<td><?php echo $i?></td>
			<td  id ="<?php echo $reg['id']?>" ><?php echo $reg['Competitor']['first_name']. "&nbsp;". $reg['Competitor']['last_name'];?></td>
			<td><?php echo $reg['Competitor']['Club']['club_abbr'] ;?></td>
			<td><?php echo $reg['Competitor']['comp_sex'];?></td>
			<td><?php echo $reg['age'];?></td>
			<td><?php echo $reg['weight'];?></td>
			<td><?php echo $reg['upSkill']. $reg['upWeight']. $reg['upAge'] ;?></td>
			<td><?php echo $reg['seed'];?></td>
			<td><?php
                                echo $html->image( $reg['approved']?'flag_green.gif':'flag_red.gif', ['alt' => 'Approved']);
                                echo $html->image( $reg['card_verified']?'flag_green.gif':'flag_red.gif', ['alt' => 'Check-In']);
			?></td>
		</tr>
	<?php 
				echo $ajax->drag( $reg['id'], array('revert'=> true) );
	
		endforeach; ?>

			<?php echo  $ajax->drag( 0, array('revert'=> true) ) ?>
		
	</table>
<?php endif; ?>
<P CLASS="pagebreakhere">
 <?php endforeach;?>

