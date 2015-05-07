<div class="competitors view">
<h2><?php  __('Search Results');?></h2>
 <?php if( isset($terms)){?>
	<div id='cmp_search'>
	<?php
	 //	echo $form->create('Competitor', array('controller'=>'competitors','action'=>'search','label'=>'search'));
	 //	echo $form->hidden('Competitor.event_id',array('value' =>  0 ));
	 //	echo $form->hidden('Competitor.search',array('value' => $terms ));
	 //	echo $form->end('Search in other events');
	 	?>
	 </div>
<?php }?>
 <?php foreach( $competitors as $competitor): ?>
 <table>
 	<tr class='blue_row'>
 		<th width='30%'><label><?php  echo $this->Html->link( $competitor['Competitor']['first_name'] . " ". $competitor['Competitor']['last_name']
							, array(  'controller' => 'competitors', 'action' => 'view', $competitor['Competitor']['id'])
			); ?></label>
 		</th>
 		<td><label>DOB:</label>
 			<?php echo $competitor['Competitor']['comp_dob']; ?>
 		</td>
 		<td><label>Club:</label>
 		<?php echo $this->Html->link($competitor['Club']['club_name'], array('controller'=> 'clubs', 'action'=>'view', $competitor['Club']['id'])); ?>
 		</td>
 		<td><label>&nbsp;</label>
 		<?php echo $this->Html->link( 'Add Registration', array( 'controller'=>'registrations', 'action' => 'add', $event_id, $competitor['Competitor']['id']), array('class'=>'button'));?>
 		</td>
 	</tr>
 </table>

<div class="related">
	<?php if (!empty($competitor['Registration'])):?>
	<?php
		$i = 0;
		foreach ($competitor['Registration'] as $registration):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
			//debug($registration);
		?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Pool'); ?></th>
		<th><?php __('Mat'); ?></th>
		<th><?php __('Weight'); ?></th>
		<th><?php __('Age'); ?></th>
		<th><?php __('Rank'); ?></th>
		<th><?php __('Division'); ?></th>
		<th><?php __('Approved'); ?></th>
		<th><?php __('UpSkill'); ?></th>
		<th><?php __('UpWeight'); ?></th>
		<th><?php __('UpAge'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
		<tr<?php echo $class; ?>>
			<td><?php echo isset( $registration['Pool']['pool_name'])?
				$this->Html->link(__($registration['Pool']['pool_name'], true), array('controller'=> 'pools', 'action'=>'view', $registration['pool_id'])):'';?></td>
			<td><?php echo isset($registration['Pool']['Mat']['name'])?$registration['Pool']['Mat']['name']:'';?></td>
			<td><?php echo $registration['weight'];?></td>
			<td><?php echo $registration['age'];?></td>
			<td><?php echo $registration['rank'];?></td>
			<td><?php echo $registration['division'];?></td>
			<td><?php echo $registration['approved'];?></td>
					<td><?php echo $registration['upSkill'];?></td>
			<td><?php echo $registration['upWeight'];?></td>
			<td><?php echo $registration['upAge'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller'=> 'registrations', 'action'=>'view', $registration['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller'=> 'registrations', 'action'=>'edit', $registration['id'])); ?>
			</td>
		</tr>
	</table>
	<div class="related">
		<?php //include('view_matches.ctp'); ?>
	</div>
	<?php endforeach; ?>

<?php endif; ?>

</div>
<?php endforeach; ?>
