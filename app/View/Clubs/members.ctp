<?php 
	$class = null; 
    $html=$this->Html;
    $ajax=$this->Js;
?>
<div id='club_<?php echo  $club['Club']['id']; ?>' class='clubing'>
<h4><?php echo  $html->link( $club['Club']['club_name'] .' (' . $club['Club']['club_abbr'] .') ' . $club['Club']['club_address'] .' '. $club['Club']['club_state'] .' '
, array('controller'=> 'clubs', 'action'=>'view', $club['Club']['id']) ) ?>

</h4>

<input type="submit" value="Move Here"
	onclick="this.form['data[club][id]'].value=<?php echo $club['Club']['id']?>" />

<table>
	<tr>
		<th>competitor</th>
		<th>sex</th>
		<th>dob</th>
		<th>action</th>
	</tr>
	<?php
	$j = 0;
	foreach ($club['Competitor'] as $reg):

	//debug($reg);
	$class = null;
	if ($j++ % 2 == 0) {
		$class = ' class="altrow"';
	}

	?>
	<tr <?php echo $class?>>
		<td>
		<div id="<?php echo $reg['id']?>"><?php echo  $reg['last_name'] .", ". $reg['first_name']?>
		</div>
		</td>
		<td><?php echo  $reg['comp_sex']?></td>
		<td><?php echo  $reg['comp_dob']?></td>
		</td>
		<td class="actions"><?php echo  $html->link('[+]', array('controller'=> 'competitors', 'action'=>'edit', $reg['id']) ) ?>
		<?php echo  $this->Form->checkbox( 'Regs.' .$reg['id'] , array('value' => $reg['id'] , 'label'=>false, 'div' => false))  ?>
		</td>
	</tr>

	<?php
		echo $ajax->drag( $reg['id'], array('revert'=> false) );
	
	endforeach;
	?>
</table>
</div>
	<?php


		echo $ajax->dropRemote( 'club_' . $club['Club']['id'],
		array('hoverclass' => 'Droppable'),
		array(
					'url' => array('controller'=>'clubs', 'action'=>'club/' . $club['Club']['id'] )
		,'with'=>'{draggedid:element.id}'
		,'update' => 'club_' . $club['Club']['id']
		)
		);
	
	?>