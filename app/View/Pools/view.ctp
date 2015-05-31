<?php
    echo $this->Html->script( array('jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.position' ),false );
    echo $this->Html->script( array('jquery.ui.tabs','jquery.ui.accordion','jquery.ui.autocomplete' ),false );
    echo $this->Html->script( array('jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable','jquery.ui.resizable' ,'jquery.ui.dialog'),false );
    $html=$this->Html;
    $form=$this->Form;
    $ajax=$this->Js;
?>
<div class="actions">
    <ul>
       <li><?php echo $html->link(__('[Pool Setup]', true), array('action'=>'setup')); ?> </li>
       <li><?php echo $html->link(__('[New Pool]', true), array('action'=>'add')); ?> </li>
       <li>&nbsp;</li>

        <li><?php echo $html->link(__('[Print Roster]', true), array('action'=>'print_roster',  $pool[ 'Pool']['event_id' ], $pool['Pool']['id']),array('target'=>'pools')); ?></li>
        <li><?php echo $html->link(__('[Edit]', true), array('action'=>'edit',  $pool[ 'Pool']['id' ])); ?></li>
       <li>&nbsp;</li>

        <?php if( $pool['Pool']['status'] == 0  ) { ?>
        <li><?php echo $html->link(__('[Create Matches/Lock Pool]', true), array('action'=>'statusLock', $pool['Pool']['id']), null, sprintf(__('Current matches will be deleted. Are you sure you want to unlock  # %s?', true), $pool['Pool']['pool_name']) ); ?> </li>
        <?php } ?>
        <?php if( $pool['Pool']['status'] == 1 ) { ?>
        <li><?php echo $html->link(__('[UnLock Pool]', true), array('action'=>'statusUnLock', $pool['Pool']['id']), null, sprintf(__('Are you sure you want to unlock  # %s?', true), $pool['Pool']['pool_name']) ); ?> </li>
        <li><?php echo $html->link(__('[Create Matches/Lock Pool]', true), array('action'=>'statusLock', $pool['Pool']['id']), null, sprintf(__('Current matches will be deleted. Are you sure you want to unlock  # %s?', true), $pool['Pool']['pool_name']) ); ?> </li>
        <li><?php echo $html->link(__('[Approve Pool/Send to Head Table]', true), array('action'=>'statusApproved', $pool['Pool']['id']), null, sprintf(__('Are you sure you want to approve  # %s?', true), $pool['Pool']['pool_name']) ); ?> </li>
        <?php } ?>
        <?php if( $pool['Pool']['status'] == 3 )  { ?>
          <li><?php echo $html->link(__('[UnLock Pool]', true), array('action'=>'statusUnLock', $pool['Pool']['id']), null, sprintf(__('Are you sure you want to unlock  # %s?', true), $pool['Pool']['pool_name']) ); ?> </li>
             <li><?php echo $html->link(__('[Release Holded Pool]', true), array('action'=>'statusRelease', $pool['Pool']['id']), array( 'alt' => 'Start Matches for this pool'), sprintf(__('Are you sure you want to release this pool for the mat', true), $pool['Pool']['pool_name']) ); ?> </li>
        <?php } elseif( $pool['Pool']['status'] == 2 || $pool['Pool']['status'] == 4 || $pool['Pool']['status'] == 7 )  { ?>
            <li><?php echo $html->link(__('[Hold Pool]', true), array('action'=>'statusHold', $pool['Pool']['id']), null, sprintf(__('Are you sure you want to hold  # %s?', true), $pool['Pool']['pool_name']) ); ?> </li>
            <li><?php echo $html->link(__('[Force Complete]', true), array('action'=>'statusComplete', $pool['Pool']['id']), null, sprintf(__('Are you sure you wnat to force complete  # %s?', true), $pool['Pool']['pool_name']) ); ?> </li>
         <?php } ?>
        
        <?php if( !empty( $pool['Match'] )) { ?>
        <li><?php echo $html->link( $html->image('pdf_icon.png'), array('controller'=> 'pools', 'action'=>'pdf', $pool['Pool']['id']), array('target'=>'pool','escape' => FALSE)); ?></li>
        <?php } ?>
    </ul>
</div>
<div class="pools view">
<h2><?php  __('Pool');?></h2>
<table>
 <td><div>
	<dl><?php $i = 0; $class = ' class="altrow"';?>

        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Pool Name'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $pool['Pool']['pool_name']; ?>
            &nbsp;
        </dd>

		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Mat'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if($pool['Pool']['mat_id'])  { echo $html->link(__($pool['Mat']['name'], true), array('controller'=>'mats','action'=>'view', $pool['Pool']['mat_id'])); }?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Sex'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $pool['Pool']['sex']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Division'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $pool['Pool']['division']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Category'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $pool['Pool']['category']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $pool['Pool']['type']; ?>
			&nbsp;
		</dd>


		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Age'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $pool['Pool']['min_age'] . " - " . $pool['Pool']['max_age']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Weight'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $pool['Pool']['min_weight'] . " - " . $pool['Pool']['max_weight']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Match Duration'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $pool['Pool']['match_duration']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $pool['PoolStatus']['status']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Registrations'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo count( $pool['Registration']) ; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Matches'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo count($pool['Match'] ); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Complete'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo  $pool['Pool']['completed_count']; ?>
			&nbsp;
		</dd>
	</dl></div>

</td>
<td>

<div class="related">
	<h3><?php echo __('Registrations');?></h3>
	<?php if (!empty($pool['Registration'])):?>
	<?php  
		$action='view';
		if(  $pool['Pool']['status'] == 1 ){ $action = 'seed'; } 
		if(  $pool['Pool']['status'] == 5 ||$pool['Pool']['status'] == 9 ){ $action = 'setPlaces'; } 
		echo $form->create( 'Pool', array( 'action' =>  $action. '/' . $pool['Pool']['id']) );
	?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Club'); ?></th>
		<th><?php echo __('Gender'); ?></th>
		<th><?php echo __('Age'); ?></th>
		<th><?php echo __('Weight'); ?></th>
		<th><?php echo __('SWA'); ?></th>
		<th><?php echo __('Seed'); ?></th>
		<?php if(   $pool['Pool']['status'] == 1) { ?>
			<th><?php echo __('Change'); ?></th>
		<?php } ?>
		<th><?php echo __('Wins'); ?></th>
		<th><?php echo __('Loses'); ?></th>
		<?php if(   $pool['Pool']['status'] == 5 ||  $pool['Pool']['status'] == 9) { ?>
			<th><?php echo __('Place'); ?></th>
			<th><?php echo __('Change'); ?></th>
		<?php } ?>
		<th><?php echo __('A'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
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
			<td><div  id ="<?php echo $reg['id']?>" ><?php echo $reg['Competitor']['first_name']. "&nbsp;". $reg['Competitor']['last_name'];?></div></td>
			<td><?php echo $reg['club_abbr'] ;?></td>
			<td><?php echo $reg['Competitor']['comp_sex'];?></td>
			<td><?php echo $reg['age'];?></td>
			<td><?php echo $reg['weight'];?></td>
			<td><?php echo $reg['upSkill']. $reg['upWeight']. $reg['upAge'] ;?></td>
			<td><?php echo $reg['seed'];?></td>
	<?php if(   $pool['Pool']['status'] == 1) { ?>			
			<td width="3"><?php echo  $form->input(  $reg['id'] , array( 'value' => $reg['seed'], 'default' => $i ,'label' => false, 'div'=> false, 'size'=> 1, 'class' => 'pools_view') );?></td>
			<?php } ?>
			<td><?php echo $reg['match_wins'];?></td>
			<td><?php echo $reg['match_loses'];?></td>
	<?php if(   $pool['Pool']['status'] == 5  ||  $pool['Pool']['status'] == 9) { ?>			
			<td><?php echo $reg['bracket_pos'];?></td>
			<td width="3"><?php echo  $form->input(  $reg['id'] , array( 'value' => $reg['bracket_pos'], 'default' => $i ,'label' => false, 'div'=> false, 'size'=> 1, 'class' => 'pools_view') );?></td>
			<?php } ?>
			<td>
			<?php echo $html->image( $reg['approved']?'flag_green.gif':'flag_red.gif'); ?>
			<?php echo $html->image( $reg['card_verified']?'flag_green.gif':'flag_red.gif'); ?>
			</td>
			<td class="actions">
				<?php echo $html->link(__('View', true), array('controller'=> 'registrations', 'action'=>'view', $reg['id'])); ?>
				<?php echo $html->link(__('Edit', true), array('controller'=> 'registrations', 'action'=>'edit', $reg['id'])); ?>
			</td>
		</tr>
	<?php 
		echo $ajax->drag( $reg['id'], array('revert'=> true) );
		endforeach; // debug($reg);
	?>

		<tr><td><div id ="0" >Bye </div></td><td colspan="8">&nbsp; </td></tr>
		<?php echo  $ajax->drag( 0, array('revert'=> true) ) ?>
		
	</table>
	<?php if(   $pool['Pool']['status'] == 1) { ?>
	<div style="float:right">
			<div class="menu_item">	<?php echo $form->submit('Seed Players', array('div'=>false) )?></div>

			<div class="menu_item"><?php echo $html->link(__('[Create Matches]', true), array('action'=>'statusLock', $pool['Pool']['id']), null, sprintf(__('Current matches will be deleted. Are you sure you want to unlock  # %s?', true), $pool['Pool']['pool_name']) ); ?></div>
	</div>
	
	<?php } ?>
	<?php if(   $pool['Pool']['status'] == 5||  $pool['Pool']['status'] == 9) { ?>
	<div style="float:right">
			<div class="menu_item"><?php echo $html->link(__('[ReCalc Places]', true), array('action'=>'calculatePlaces', $pool['Pool']['id']), null, sprintf(__('The system will attempt to calculate places for "%s" based in in wins and loses per competitor. Are you sure?', true), $pool['Pool']['pool_name']) ); ?></div>
			<div class="menu_item">	<?php echo $form->submit('Adjust Places', array('div'=>false) )?></div>
			<div class="menu_item"><?php echo $html->link(__('[Approve Places]', true), array('action'=>'statusReviewed', $pool['Pool']['id']), null, sprintf(__('The pool will be sent to the awards table. Are you sure # %s?', true), $pool['Pool']['pool_name']) ); ?></div>	
	</div>
	
	<?php } ?>
	
	<?php echo $form->end()?>
<?php endif; ?>

</td>
</table>

</div>

<?php $pool = $pool['Pool'] ;?>
<div id="pool_<?php echo $pool['id'];?>"></div>
      <script type="text/javascript">
            <?php echo $ajax->request(
                        
                                array( 'controller'=>'pools','action'=>'view' , $pool['id'] ,'view_matches' )
                                ,array( 'update'=>'#pool_' . $pool['id']
                                //,'complete' =>'document.sync_data()'
                                )
            
            )?>
    </script>
