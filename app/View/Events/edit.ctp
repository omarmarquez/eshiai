<div class="events form">
<?php echo $this->Form->create('Event');?>
	<fieldset>
		<legend><?php echo __('Edit Event'); ?></legend>
	<?php
		echo $this->Form->input('id', array( 'type' => 'hidden'));
        echo $this->Form->input('event_name');
		echo $this->Form->input('event_type', array('options'=>array('judo','wrestling')));
		echo $this->Form->input('event_date');
		echo $this->Form->input('reg_starts');
        echo $this->Form->input('reg_email');
		echo $this->Form->input('reg_ends');
		echo $this->Form->input('reg_price');
		echo $this->Form->input('add_reg_price');
		echo $this->Form->input('event_location');
		echo $this->Form->input('event_address');
		echo $this->Form->input('event_pass');
		echo $this->Form->input('minutes_between_matches');
		echo $this->Form->input('minutes_between_matches_comp');
		echo $this->Form->input('age_calculation', array('options'=>array('DOB' =>'DOB','YOB' => 'YOB')));
		echo $this->Form->input('queue_depth');
		echo $this->Form->input('free_shido');
		echo $this->Form->input('free_medical');
		echo $this->Form->input('default_pool_type', array('options'=>array('rr' => 'Round Robin','de' =>'Double Elimination','se'=>'Single Elimination', 'co' => 'co')));
		echo $this->Form->input('rr_size');
		echo $this->Form->input('match_time_juniors');
		echo $this->Form->input('match_time_seniors');
		echo $this->Form->input('match_time_open');
        echo $this->Form->input('match_time_masters');
        echo "<div style='clear:both;'>&nbsp;</div>";
        echo $this->Form->input('paypal_int',array( 'before' => ''));
        echo "<div style='clear:both;'>&nbsp;</div>";
        echo $this->Form->input('paypal_button_add', array('type'=>'textarea', 'cols'=>40,'rows'=>5 ));
        echo $this->Form->input('paypal_button_cart', array('type'=>'textarea', 'cols'=>40,'rows'=>5 ));
        echo "<div style='clear:both;'>&nbsp;</div>";
        echo $this->Form->input('paypal_username');
        echo $this->Form->input('paypal_password');
        echo $this->Form->input('paypal_signature');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Event.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Event.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Events'), array('action' => 'index'));?></li>
        <li><?php echo $this->Html->link(__('Clone Registrations'), array('controller' => 'registrations', 'action' => 'event_dup'));?></li>
        <li><?php echo $this->Html->link(__('Import WP Registrations'), array('controller' => 'registrations', 'action' => 'import_wp_regs', $this->Form->value('Event.id') ));?></li>
        <li><?php echo $this->Html->link(__('Import WP Payments'), array('controller' => 'registrations', 'action' => 'import_wp_pmts', $this->Form->value('Event.id') ));?></li>
        <li><?php echo $this->Html->link(__('Import WP Clubs'), array('controller' => 'registrations', 'action' => 'import_wp_clubs', $this->Form->value('Event.id') ));?></li>
	</ul>
</div>
<style>
  .floatleft{float: left;}
 </style>

<?php 
    $this->Js->buffer( "$('.input').width('30%').addClass('floatleft');");
?>

