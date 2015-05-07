<div class="registrations view">
<h2><?php  echo __('Registration');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Competitor'); ?></dt>
		<dd>
			<?php echo $this->Html->link($registration['Competitor']['id'], array('controller' => 'competitors', 'action' => 'view', $registration['Competitor']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Event'); ?></dt>
		<dd>
			<?php echo $this->Html->link($registration['Event']['event_date'], array('controller' => 'events', 'action' => 'view', $registration['Event']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Weight'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['weight']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Age'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['age']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rank'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['rank']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Division'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['division']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Club Abbr'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['club_abbr']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Club Name'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['club_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Payment'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['payment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Approved'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['approved']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pool'); ?></dt>
		<dd>
			<?php echo $this->Html->link($registration['Pool']['pool_name'], array('controller' => 'pools', 'action' => 'view', $registration['Pool']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('UpSkill'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['upSkill']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('UpWeight'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['upWeight']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('UpAge'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['upAge']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Card Type'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['card_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Card Number'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['card_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Card Verified'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['card_verified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comments'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['comments']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Match Wins'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['match_wins']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Match Loses'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['match_loses']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Bracket Pos'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['bracket_pos']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Seed'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['seed']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Auto Pool'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['auto_pool']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Disqualified'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['disqualified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Awarded'); ?></dt>
		<dd>
			<?php echo h($registration['Registration']['awarded']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Registration'), array('action' => 'edit', $registration['Registration']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Registration'), array('action' => 'delete', $registration['Registration']['id']), null, __('Are you sure you want to delete # %s?', $registration['Registration']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Registrations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Registration'), array('action' => 'add')); ?> </li>
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
	<div class="related">
		<h3><?php echo __('Related Awards');?></h3>
	<?php if (!empty($registration['Award'])):?>
		<dl>
			<dt><?php echo __('Id');?></dt>
		<dd>
	<?php echo $registration['Award']['id'];?>
&nbsp;</dd>
		<dt><?php echo __('Pool Id');?></dt>
		<dd>
	<?php echo $registration['Award']['pool_id'];?>
&nbsp;</dd>
		<dt><?php echo __('Registration Id');?></dt>
		<dd>
	<?php echo $registration['Award']['registration_id'];?>
&nbsp;</dd>
		<dt><?php echo __('Place');?></dt>
		<dd>
	<?php echo $registration['Award']['place'];?>
&nbsp;</dd>
		</dl>
	<?php endif; ?>
		<div class="actions">
			<ul>
				<li><?php echo $this->Html->link(__('Edit Award'), array('controller' => 'awards', 'action' => 'edit', $registration['Award']['id'])); ?></li>
			</ul>
		</div>
	</div>
	<div class="related">
	<h3><?php echo __('Related Scores');?></h3>
	<?php if (!empty($registration['Score'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Match Id'); ?></th>
		<th><?php echo __('Registration Id'); ?></th>
		<th><?php echo __('Score'); ?></th>
		<th><?php echo __('Mat Clock'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($registration['Score'] as $score): ?>
		<tr>
			<td><?php echo $score['id'];?></td>
			<td><?php echo $score['created'];?></td>
			<td><?php echo $score['match_id'];?></td>
			<td><?php echo $score['registration_id'];?></td>
			<td><?php echo $score['score'];?></td>
			<td><?php echo $score['mat_clock'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'scores', 'action' => 'view', $score['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'scores', 'action' => 'edit', $score['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'scores', 'action' => 'delete', $score['id']), null, __('Are you sure you want to delete # %s?', $score['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Score'), array('controller' => 'scores', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Matches');?></h3>
	<?php if (!empty($registration['Match'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Pool Id'); ?></th>
		<th><?php echo __('Mat Id'); ?></th>
		<th><?php echo __('Match Num'); ?></th>
		<th><?php echo __('Round'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Qorder'); ?></th>
		<th><?php echo __('Hold'); ?></th>
		<th><?php echo __('Winner'); ?></th>
		<th><?php echo __('By'); ?></th>
		<th><?php echo __('Referee Id'); ?></th>
		<th><?php echo __('Ref Score'); ?></th>
		<th><?php echo __('Started'); ?></th>
		<th><?php echo __('Completed'); ?></th>
		<th><?php echo __('Skip'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($registration['Match'] as $match): ?>
		<tr>
			<td><?php echo $match['id'];?></td>
			<td><?php echo $match['pool_id'];?></td>
			<td><?php echo $match['mat_id'];?></td>
			<td><?php echo $match['match_num'];?></td>
			<td><?php echo $match['round'];?></td>
			<td><?php echo $match['status'];?></td>
			<td><?php echo $match['qorder'];?></td>
			<td><?php echo $match['hold'];?></td>
			<td><?php echo $match['winner'];?></td>
			<td><?php echo $match['by'];?></td>
			<td><?php echo $match['referee_id'];?></td>
			<td><?php echo $match['ref_score'];?></td>
			<td><?php echo $match['started'];?></td>
			<td><?php echo $match['completed'];?></td>
			<td><?php echo $match['skip'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'matches', 'action' => 'view', $match['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'matches', 'action' => 'edit', $match['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'matches', 'action' => 'delete', $match['id']), null, __('Are you sure you want to delete # %s?', $match['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Match'), array('controller' => 'matches', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
