<div class="events index">
	<h2><?php echo __('Events');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('active');?></th>
			<th><?php echo $this->Paginator->sort('rw');?></th>
			<th><?php echo $this->Paginator->sort('event_type');?></th>
			<th><?php echo $this->Paginator->sort('event_name');?></th>
			<th><?php echo $this->Paginator->sort('event_date');?></th>
			<th><?php echo $this->Paginator->sort('reg_starts');?></th>
			<th><?php echo $this->Paginator->sort('reg_ends');?></th>
			<th><?php echo $this->Paginator->sort('reg_price');?></th>
			<th><?php echo $this->Paginator->sort('add_reg_price');?></th>
			<th><?php echo $this->Paginator->sort('reg_email');?></th>
			<th><?php echo $this->Paginator->sort('event_location');?></th>
			<th><?php echo $this->Paginator->sort('event_address');?></th>
			<th><?php echo $this->Paginator->sort('event_pass');?></th>
			<th><?php echo $this->Paginator->sort('minutes_between_matches');?></th>
			<th><?php echo $this->Paginator->sort('minutes_between_matches_comp');?></th>
			<th><?php echo $this->Paginator->sort('age_calculation');?></th>
			<th><?php echo $this->Paginator->sort('queue_depth');?></th>
			<th><?php echo $this->Paginator->sort('free_shido');?></th>
			<th><?php echo $this->Paginator->sort('free_medical');?></th>
			<th><?php echo $this->Paginator->sort('default_pool_type');?></th>
			<th><?php echo $this->Paginator->sort('rr_size');?></th>
			<th><?php echo $this->Paginator->sort('match_time_juniors');?></th>
			<th><?php echo $this->Paginator->sort('match_time_seniors');?></th>
			<th><?php echo $this->Paginator->sort('match_time_open');?></th>
			<th><?php echo $this->Paginator->sort('match_time_masters');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($events as $event): ?>
	<tr>
		<td><?php echo h($event['Event']['id']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['active']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['rw']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['event_type']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['event_name']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['event_date']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['reg_starts']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['reg_ends']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['reg_price']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['add_reg_price']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['reg_email']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['event_location']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['event_address']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['event_pass']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['minutes_between_matches']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['minutes_between_matches_comp']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['age_calculation']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['queue_depth']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['free_shido']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['free_medical']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['default_pool_type']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['rr_size']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['match_time_juniors']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['match_time_seniors']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['match_time_open']); ?>&nbsp;</td>
		<td><?php echo h($event['Event']['match_time_masters']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $event['Event']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $event['Event']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $event['Event']['id']), null, __('Are you sure you want to delete # %s?', $event['Event']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Event'), array('action' => 'add')); ?></li>
	</ul>
</div>
