<div class="clubs index">
<h2><?php echo __('Registered Clubs');?></h2>
<?php 
    $html = $this->Html;
			//debug($_SESSION);
	
	$event_title = $_SESSION['Event']['event_name'];
			// debug($eid)
?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo ('club_name');?></th>
	<th><?php echo ('abbr');?></th>
	<th><?php echo ('competitors');?></th>
	<th><?php echo ( 'Pool');?></th>
	<th><?php echo ('wins');?></th>
	<th><?php echo ('loses');?></th>
	<th><?php echo ('place');?></th>
</tr>
<?php
$i = 0;
foreach ($clubs as $club):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
    $rc = 0;
    foreach( $club['Competitor'] as $c ){
        $rc += count( $c['Registration']);
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
			<?php echo count( $club['Competitor'] );  ?>
		</td>
		<td>
			<?php echo $rc ?>
		</td>
		<?php 
				$wins = $loses = 0;
				foreach( $club['Competitor'] as $comp ){
					foreach( $comp['Registration'] as $reg ){
						$wins += $reg['match_wins'];
						$loses += $reg['match_loses'];
					}
				}
		
		?>
	<th><?php echo 'wins';?></th>
	<th><?php echo  'loses'; ?></th>
	<th><?php echo  'place';?></th>
		
	</tr>
	<?php  
		if( true  ){?>
	<?php  
		foreach( $club['Competitor'] as $comp ):
			$name = $comp['first_name'] . '&nbsp;' . $comp['last_name'];
	 	   foreach( $comp['Registration'] as $reg ):?>
	<tr <?php echo $class;?>>
		<td colspan="2">&nbsp;</td>
		<td colspan="1"><?php echo $name?> </td>		
		<td colspan="1"><?php echo empty($reg['Pool'])?'':$reg['Pool']['pool_name'] . " " .$reg['Pool']['division']  . " " .$reg['Pool']['category']?> </td>
		<td colspan="1"><?php echo $reg['match_wins'] ?> </td>
		<td colspan="1"><?php echo $reg['match_loses'] ?> </td>
		<td colspan="1"><?php echo $reg['bracket_pos']  ?> </td>
	</tr>
	<?php 
		$name = "&nbsp;";
		endforeach; ?>
		
	<?php   endforeach; ?>
	 <tr <?php echo $class;?>>
		<td colspan="4">&nbsp;</td>
		<td><b><?php echo $wins; ?></b></td>
		<td><b><?php echo $loses; ?></b></td>
		<td><b><?php echo  $club['Club']['points']; ?> pts</b></td>
	</tr>	
	
	<?php } ?>
<?php endforeach; ?>
</table>
</div>

<?php //debug($clubs);?>
