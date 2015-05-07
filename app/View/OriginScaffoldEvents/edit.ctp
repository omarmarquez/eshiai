<div class="events form">
<?php echo $this->Form->create('Event');?>
	<fieldset>
		<legend><?php echo __('Edit Event'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('active');
		echo $this->Form->input('rw');
		echo $this->Form->input('event_type');
		echo $this->Form->input('event_name');
		echo $this->Form->input('event_date');
		echo $this->Form->input('reg_starts');
		echo $this->Form->input('reg_ends');
		echo $this->Form->input('reg_price');
		echo $this->Form->input('add_reg_price');
		echo $this->Form->input('reg_email');
		echo $this->Form->input('event_location');
		echo $this->Form->input('event_address');
		echo $this->Form->input('event_pass');
		echo $this->Form->input('minutes_between_matches');
		echo $this->Form->input('minutes_between_matches_comp');
		echo $this->Form->input('age_calculation');
		echo $this->Form->input('queue_depth');
		echo $this->Form->input('free_shido');
		echo $this->Form->input('free_medical');
		echo $this->Form->input('default_pool_type');
		echo $this->Form->input('rr_size');
		echo $this->Form->input('match_time_juniors');
		echo $this->Form->input('match_time_seniors');
		echo $this->Form->input('match_time_open');
		echo $this->Form->input('match_time_masters');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Event.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Event.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Events'), array('action' => 'index'));?></li>
	</ul>
</div>
