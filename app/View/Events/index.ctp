<div class="events index">
	<h2><?php echo __('Events');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
	    <?php foreach ($fields as $field): ?>
			<th><?php echo $this->Paginator->sort(  $field );?></th>
		<?php endforeach; ?>

	</tr>
	<?php
	foreach ($listing as $event): ?>
	<tr>
       <?php foreach ($fields as $field): ?>

		<td><?php echo preg_match('/name/' , $field)? $this->Html->link(__( $event['Event'][ $field ] ), array('action' => 'view', $event['Event']['id'])):$event['Event'][ $field ]; ?>&nbsp;</td>
       <?php endforeach; ?>


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
<?php // debug($this->Paginator )  ; ?>
