<?php 
	$class = null; 
?>
<div id='pool_<?php echo  $pool['Pool']['id']; ?>' class='pooling'>
<h4><?php echo  $html->link( $pool['Pool']['pool_name'] .' (' . $pool['Pool']['sex'] .') ' . $pool['Pool']['category'] .' '. $pool['Pool']['division'] .' '
, array('controller'=> 'pools', 'action'=>'view', $pool['Pool']['id']) ) ?>
<?php echo  ' ' .$pool['Pool']['min_age'] ." to ".  $pool['Pool']['max_age'] ?>
<?php echo  ' ,' . $pool['Pool']['min_weight'] ." - " . $pool['Pool']['max_weight'] .' lbs/kgs'?>
<?php echo   $pool['Pool']['type'] . ' ' . $pool['Pool']['match_duration'] . ' mins.' ?>
<?php echo   'status:'  . $pool['PoolStatus']['status']  ?> <?php echo   $pool['Mat']['name']? ', '. $pool['Mat']['name'] :' ' ?>
</h4>

<input type="submit" value="Move Here"
	onclick="this.form['data[Pool][id]'].value=<?php echo $pool['Pool']['id']?>" />
<?php if( count($pool['Registration'] ) <= 1 ){ ?>
<div class="warning">Not Enough Competitors!!!</div>
<?php } ?>
<table>
	<tr>
		<th>competitor</th>
		<th>club</th>
		<th>sex</th>
		<th>age</th>
		<th>weight</th>
		<th>rank</th>
		<th>SWA</th>
		<th>seed</th>
		<th>A</th>
		<th>action</th>
	</tr>
	<?php
	$j = 0;
	foreach ($pool['Registration'] as $reg):
	if( empty( $reg['Competitor'])){
		continue;
	}
	//debug($reg);
	$class = null;
	if ($j++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	if( preg_match( '/Y/',$reg['upSkill'] .$reg['upWeight'] .$reg['upAge']  )){
		$class = ' class="blue_row"';
	}
	?>
	<tr <?php echo $class?>>
		<td>
		<div id="<?php echo $reg['id']?>"><?php echo  $reg['Competitor']['last_name'] .", ". $reg['Competitor']['first_name']?>
		</div>
		</td>
		<td><?php echo  $reg['club_abbr']?></td>
		<td><?php echo  $reg['Competitor']['comp_sex']?></td>
		<td><?php echo  $reg['age']?></td>
		<td><?php echo  $reg['weight']?></td>
		<td><?php echo  $reg['rank']?></td>
		<td><?php echo  $reg['upSkill']. $reg['upWeight'].$reg['upAge']?></td>
		<td><?php echo  $reg['seed']?></td>
		<td><?php if(!$reg['approved'] ) { echo $html->image( $reg['approved']?'flag_green.gif':'flag_red.gif'); }?>
		</td>
		<td class="actions"><?php echo  $html->link('[+]', array('controller'=> 'competitors', 'action'=>'edit', $reg['Competitor']['id']) ) ?>
		<?php if( $pool['Pool']['status'] == 0){ ?> <?php echo  $html->link('[e]', array('controller'=> 'registrations', 'action'=>'edit', $reg['id']) ) ?>
		<?php echo  $ajax->link('[r]', array('controller'=> 'pools', 'action'=>'rem_reg', $reg['id']),array( 'update'=>'#pool_' . $reg['pool_id'] ,'confirm'=> 'Remove?')) ?>
		<?php echo  $form->checkbox( 'Regs.' .$reg['id'] , array('value' => $reg['id'] , 'label'=>false, 'div' => false))  ?>
		<?php }?></td>
	</tr>

	<?php
	if( $pool['Pool']['status'] == 0){
		echo $ajax->drag( $reg['id'], array('revert'=> false) );
	}
	endforeach;
	?>
</table>
</div>
	<?php

	if( $pool['Pool']['status'] == 0){

		echo $ajax->dropRemote( 'pool_' . $pool['Pool']['id'],
		array('hoverclass' => 'Droppable'),
		array(
					'url' => array('controller'=>'pools', 'action'=>'pool/' . $pool['Pool']['id'] )
		,'with'=>'{draggedid:element.id}'
		,'update' => 'pool_' . $pool['Pool']['id']
		)
		);
	}
	?>