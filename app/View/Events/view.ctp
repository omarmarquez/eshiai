<div class="event" style="float:left;width:40%;">
<h2><?php  echo __( "Event Parameters");?></h2>
	<dl>
		<dt><?php echo __('Number'); ?></dt>
		<dd>
			<?php echo h($record['Event']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Event Type'); ?></dt>
		<dd>
			<?php echo h($record['Event']['event_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Event Date'); ?></dt>
		<dd>
			<?php echo h($record['Event']['event_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reg Starts'); ?></dt>
		<dd>
			<?php echo h($record['Event']['reg_starts']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reg Ends'); ?></dt>
		<dd>
			<?php echo h($record['Event']['reg_ends']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reg Price'); ?></dt>
		<dd>
			<?php echo h($record['Event']['reg_price']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Add Reg Price'); ?></dt>
		<dd>
			<?php echo h($record['Event']['add_reg_price']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reg Email'); ?></dt>
		<dd>
			<?php echo h($record['Event']['reg_email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Event Location'); ?></dt>
		<dd>
			<?php echo h($record['Event']['event_location']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Event Address'); ?></dt>
		<dd>
			<?php echo h($record['Event']['event_address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Event Pass'); ?></dt>
		<dd>
			<?php echo h($record['Event']['event_pass']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Minutes Between Matches'); ?></dt>
		<dd>
			<?php echo h($record['Event']['minutes_between_matches']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Minutes Between Matches Comp'); ?></dt>
		<dd>
			<?php echo h($record['Event']['minutes_between_matches_comp']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Age Calculation'); ?></dt>
		<dd>
			<?php echo h($record['Event']['age_calculation']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Queue Depth'); ?></dt>
		<dd>
			<?php echo h($record['Event']['queue_depth']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Free Shido'); ?></dt>
		<dd>
			<?php echo h($record['Event']['free_shido']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Free Medical'); ?></dt>
		<dd>
			<?php echo h($record['Event']['free_medical']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Default Pool Type'); ?></dt>
		<dd>
			<?php echo h($record['Event']['default_pool_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rr Size'); ?></dt>
		<dd>
			<?php echo h($record['Event']['rr_size']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Match Time Juniors'); ?></dt>
		<dd>
			<?php echo h($record['Event']['match_time_juniors']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Match Time Seniors'); ?></dt>
		<dd>
			<?php echo h($record['Event']['match_time_seniors']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Match Time Open'); ?></dt>
		<dd>
			<?php echo h($record['Event']['match_time_open']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Match Time Masters'); ?></dt>
		<dd>
			<?php echo h($record['Event']['match_time_masters']); ?>
			&nbsp;
		</dd>
	</dl>
	<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Event'), array('action' => 'edit', $record['Event']['id'])); ?> </li>
        <li><?php echo $this->Form->postLink(__('Delete Event'), array('action' => 'delete', $record['Event']['id']), null, __('Are you sure you want to delete # %s?', $record['Event']['id'])); ?> </li>
    </ul>
	</div>
	<div id="mats"></div>
</div>
<div style="float:left;width:56%;">

<div id="pools"></div>
</div>
<script language="JavaScript">

       $.ajax({
             url: '<?php echo $this->Html->url( array('controller' => 'mats', 'action' => 'index')) ?>',
                    cache: false,
                    method: 'GET',
                    data: { },
                    success: function( data ){
                        $('#mats').html(data);
                        }
                });
        $.ajax({
             url: '<?php echo $this->Html->url( array('controller' => 'pools', 'action' => 'index')) ?>',
                    cache: false,
                    method: 'GET',
                    data: { },
                    success: function( data ){
                        $('#pools').html(data);
                        }
                });
  
</script>
