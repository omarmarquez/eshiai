<div class="clubs index">
<h2><?php echo __('Registered Clubs');?></h2>
<?php 
    $html = $this->Html;
            //debug($_SESSION);
    
    $event_title = $_SESSION['Event']['event_name'];
?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo ('club_name');?></th>
	<th><?php echo ('abbr');?></th>
	<th><?php echo __('(F)')?></th>
	<th><?php echo __('(M)')?></th>
	<th><?php echo __('Juniors')?></th>
	<th><?php echo __('(F)')?></th>
	<th><?php echo __('(M)')?></th>
	<th><?php echo __('Seniors')?></th>
	<th><?php echo __('(F)')?></th>
	<th><?php echo __('(M)')?></th>
	<th><?php echo __('Masters')?></th>
	<th><?php echo __('(F)')?></th>
	<th><?php echo __('(M)')?></th>
	<th><?php echo __('Open')?></th>
	<th><?php echo __('Total')?></th>
</tr>
<?php
$i = 0;
foreach ($clubs as $club):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $html->link(__($club['Club']['club_name'], true), array('action'=>'view', $club['Club']['id'])); ?>
			<br/>
			<?php 
				echo $club['Club']['club_address'] ." ".  $club['Club']['club_city']
				. $club['Club']['club_state']." ".  $club['Club']['club_zip']
				." ".    $club['Club']['club_phone'];
			?>
		</td>
		<td>
			<?php echo $club['Club']['club_abbr']; ?>
		</td>
		<td>
			<?php echo $competitors[ $club['Club']['id'] ]['juniors']['F'];  ?>
		</td>
		<td>
			<?php echo $competitors[ $club['Club']['id'] ]['juniors']['M'];  ?>
		</td>
		<td>
			<b><?php echo $competitors[ $club['Club']['id'] ]['juniors']['F'] + $competitors[ $club['Club']['id'] ]['juniors']['M'];  ?></b>
		</td>
		<td>
			<?php echo $competitors[ $club['Club']['id'] ]['seniors']['F'];  ?>
		</td>
		<td>
			<?php echo $competitors[ $club['Club']['id'] ]['seniors']['M'];  ?>
		</td>
		<td>
			<b><?php echo $competitors[ $club['Club']['id'] ]['seniors']['F'] + $competitors[ $club['Club']['id'] ]['seniors']['M'];  ?></b>
		</td>
		<td>
			<?php echo $competitors[ $club['Club']['id'] ]['masters']['F'];  ?>
		</td>
		<td>
			<?php echo $competitors[ $club['Club']['id'] ]['masters']['M'];  ?>
		</td>
		<td>
			<b><?php echo $competitors[ $club['Club']['id'] ]['masters']['F'] + $competitors[ $club['Club']['id'] ]['masters']['M'];  ?></b>
		</td>
		<td>
			<?php echo $competitors[ $club['Club']['id'] ]['open']['F'];  ?>
		</td>
		<td>
			<?php echo $competitors[ $club['Club']['id'] ]['open']['M'];  ?>
		</td>
		<td>
			<b><?php echo $competitors[ $club['Club']['id'] ]['open']['F'] + $competitors[ $club['Club']['id'] ]['open']['M'];  ?></b>
		</td>
		<td>
			<b><?php echo  $competitors[ $club['Club']['id'] ]['juniors']['F'] + $competitors[ $club['Club']['id'] ]['juniors']['M']
							+ $competitors[ $club['Club']['id'] ]['seniors']['F'] + $competitors[ $club['Club']['id'] ]['seniors']['M'] 
							+ $competitors[ $club['Club']['id'] ]['masters']['F'] + $competitors[ $club['Club']['id'] ]['masters']['M'] 
							+ $competitors[ $club['Club']['id'] ]['open']['F'] + $competitors[ $club['Club']['id'] ]['open']['M'];  ?></b>
		</td>
		
	</tr>

	

<?php endforeach; ?>
</table>
</div>

<?php //debug($clubs);?>
