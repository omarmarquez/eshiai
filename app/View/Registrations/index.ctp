<?php $html=$this->Html;?>
<div class="registrations index">

<h2><?php echo __('Registrations');?></h2>
<div class="menu">
      <div class="menu_item"><?php echo  $this->Html->link(__('New Registration', true), array('controller'=> 'registrations', 'action'=>'add',  $event_id ));?> </div>

      <div class="menu_item"><?php echo  $this->Html->link(__('OnLine Registration', true), array('controller'=> 'registrations', 'action'=>'online',  $event_id ));?> </div>
    
</div>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort(  'Competitor.first_name','Competitor');?></th>
            <th class="actions"><?php echo __('Actions');?></th>
			<th><?php echo $this->Paginator->sort( 'Competitor.club_id','Club');?></th>
			<th><?php echo $this->Paginator->sort('rtype');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th><?php echo $this->Paginator->sort('weight');?></th>
			<th><?php echo $this->Paginator->sort('age');?></th>
			<th><?php echo $this->Paginator->sort('rank');?></th>
			<th><?php echo $this->Paginator->sort('division');?></th>
			<th colspan=3><?php echo $this->Paginator->sort('extra');?></th>
			<th><?php echo $this->Paginator->sort('approved');?></th>
			<th><?php echo $this->Paginator->sort('card_verified');?></th>
			<th><?php echo $this->Paginator->sort('pool');?></th>
		</tr>
	<?php
	foreach ($registrations as $registration): ?>
	<tr>

        <td>
            <?php 
            $comp_name = $registration['Competitor']['first_name']  . " " .$registration['Competitor']['last_name'];
            echo $html->link($registration['Competitor']['first_name']  . " " .$registration['Competitor']['last_name'] 
             , array('controller'=> 'competitors', 'action'=>'edit', $registration['Competitor']['id'])); ?>
        </td>
        <td class="actions">
            <?php echo $html->link(__('Edit', true), array('action'=>'edit', $registration['Registration']['id'])); ?>
               <?php echo $html->link(__('Weigh In', true), array('action'=>'weighIn', $event_id, $comp_name )); ?>
                <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $registration['Registration']['id'] ), null, __('Are you sure you want to delete # %s?', $registration['Registration']['id'])); ?>
 </td>
        <td>
            <?php
                if( isset( $registration['Competitor']['Club'])){

                    echo $html->link($registration['Competitor']['Club']['club_name']
                        , array('controller'=> 'clubs', 'action'=>'view', $registration['Competitor']['club_id']));

                }
                ?>
        </td>
        <td>
            <?php echo $registration['Registration']['rtype']; ?>
        </td>
                <td>
            <?php echo $registration['Registration']['modified']; ?>
        </td>
                <td>
            <?php echo $registration['Registration']['weight']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['age']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['rank']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['division']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['upSkill']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['upWeight']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['upAge']; ?>
        </td>
        <td>
            <?php echo $html->image( $registration['Registration']['approved']?'flag_green.gif':'flag_red.gif'); ?>
        </td>
        <td>
            <?php echo $html->image( $registration['Registration']['card_verified']?'flag_green.gif':'flag_red.gif'); ?>
        </td>
        <td>
            <?php echo $html->link($registration['Pool']['pool_name'], array('controller'=> 'pools', 'action'=>'view', $registration['Pool']['id'])); ?>
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

