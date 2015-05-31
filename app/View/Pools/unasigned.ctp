<?php
	$class = null;
    $html = $this->Html; $form=$this->Form;
    $this->Js->buffer("
       var draggableArguments={
         revert: 'invalid',
         helper:'clone',
         append: '#page',
         containment: 'DOM',
         zIndex: 1500,
         addClasses: false
        }
    ");
?>
<div id='pool_0' class='pooling'>
<h4>Non pooled competitors</h4>

<table id="npc_lst">
   <thead>
	<tr>
		<th colspan='2' data-sort="string">competitor</th>
		<th data-sort="string">club</th>
		<th data-sort="string">sex</th>
		<th data-sort="int">age</th>
		<th data-sort="float">weight</th>
		<th data-sort="string">rank</th>
		<th data-sort="string">SWA</th>
		<th data-sort="string">seed</th>
		<th data-sort="string">A</th>
		<th data-sort="string">action</th>
	</tr>
   </thead>
   <tbody>
	<?php
	$j = 0;
	foreach ($registrations as $r):
	$reg = $r['Registration'];
	if( empty( $r['Competitor'])){
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
	    <td><?php echo  $form->checkbox( 'Regs.' .$reg['id'] , array('value' => $reg['id'] , 'label'=>false, 'div' => false))  ?></td>
		<td>
		<div id="<?php echo $reg['id']?>" pool_id="0" ><?php echo  $r['Competitor']['last_name'] .", ". $r['Competitor']['first_name']?>
		</div>
		</td>
		<td><?php echo  $reg['club_abbr']?></td>
		<td><?php echo  $r['Competitor']['comp_sex']?></td>
		<td><?php echo  $reg['age']?></td>
		<td><?php echo  $reg['weight']?></td>
		<td><?php echo  $reg['rank']?></td>
		<td><?php echo  $reg['upSkill']. $reg['upWeight'].$reg['upAge']?></td>
		<td><?php echo  $reg['seed']?></td>
		<td>
		<?php if(!$reg['approved'] ) { echo $html->image( $reg['approved']?'flag_green.gif':'flag_red.gif'); }?>
		<?php if(!$reg['card_verified'] ) { echo $html->image( $reg['card_verified']?'flag_green.gif':'flag_red.gif'); }?>
		</td>
		<td class="actions"><?php echo  $html->link('[+]', array('controller'=> 'competitors', 'action'=>'edit', $r['Competitor']['id']) ) ?>
		<?php echo  $html->link('[e]', array('controller'=> 'registrations', 'action'=>'edit', $reg['id']) ) ?>	</td>
	</tr>

	<?php
				 $this->Js->drag( $reg['id']);
	endforeach;
	?>
    </tbody>
</table>
</div>
	<?php
/*
        $this->Js->get( '#pool_' . $pool['Pool']['id']);
        $this->Js->drop( 	array(
        
            'hoverclass' => 'Droppable',
			'url' => array('controller'=>'pools', 'action'=>'pool/0' )
		     ,'with'=>'{draggedid:element.id}'
		      ,'update' => '#pool_unasigned' 
		));
  */      
	   echo $this->Js->writeBuffer();
	?>
<script type="text/javascript">
 $(function(){

    $("#npc_lst").stupidtable();
 });
</script>
