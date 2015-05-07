<div class="registrations form">
<?php echo $this->Form->create('Registration');?>
	<fieldset>
		<legend><?php echo __('Edit Registration'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('competitor_id');
		echo $this->Form->input('event_id');
		echo $this->Form->input('weight');
		echo $this->Form->input('age');
		echo $this->Form->input('rank');
		echo $this->Form->input('division');
		echo $this->Form->input('club_abbr');
		echo $this->Form->input('club_name');
		echo $this->Form->input('payment');
		echo $this->Form->input('approved');
		echo $this->Form->input('pool_id');
		echo $this->Form->input('upSkill');
		echo $this->Form->input('upWeight');
		echo $this->Form->input('upAge');
		echo $this->Form->input('card_type');
		echo $this->Form->input('card_number');
		echo $this->Form->input('card_verified');
		echo $this->Form->input('comments');
		echo $this->Form->input('match_wins');
		echo $this->Form->input('match_loses');
		echo $this->Form->input('bracket_pos');
		echo $this->Form->input('seed');
		echo $this->Form->input('auto_pool');
		echo $this->Form->input('disqualified');
		echo $this->Form->input('awarded');
		echo $this->Form->input('Match');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Registration.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Registration.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Registrations'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Competitors'), array('controller' => 'competitors', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Competitor'), array('controller' => 'competitors', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Events'), array('controller' => 'events', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Event'), array('controller' => 'events', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Pools'), array('controller' => 'pools', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pool'), array('controller' => 'pools', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Awards'), array('controller' => 'awards', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Award'), array('controller' => 'awards', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Scores'), array('controller' => 'scores', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Score'), array('controller' => 'scores', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Matches'), array('controller' => 'matches', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Match'), array('controller' => 'matches', 'action' => 'add')); ?> </li>
	</ul>
</div>
