<?php
	$class = null;
    $html= $this->Html;
    $form=$this->Form;
    $pid = $pool['Pool']['id'];
       
?>
<h4><?php echo  $html->link( $pool['Pool']['pool_name'] .' (' . $pool['Pool']['sex'] .') ' . $pool['Pool']['category'] .' '. $pool['Pool']['division'] .' '
, array('controller'=> 'pools', 'action'=>'view', $pool['Pool']['id']) ) ?>
<?php echo  ' ' .$pool['Pool']['min_age'] ." to ".  $pool['Pool']['max_age'] ?>
<?php echo  ' ,' . $pool['Pool']['min_weight'] ." - " . $pool['Pool']['max_weight'] .' lbs/kgs'?>
<?php echo   $pool['Pool']['type'] . ' ' . $pool['Pool']['match_duration'] . ' mins.' ?>
<?php echo   'status:'  . $pool['PoolStatus']['status']  ?> <?php echo   $pool['Mat']['name']? ', '. $pool['Mat']['name'] :' ' ?>
</h4>
<div class="float_right">
<?php echo $html->image('24x24/Close.gif',
            array(
                'alt'   => 'close',
                'align' => 'right',
                'id'    => 'imgClose'.$pid
            ))?>
<?php echo $html->image('iconEdit.png',
            array(
                'alt'   => 'edit',
                'align' => 'right',
                'id'    =>  'imgEdit'.$pid
            ))?>
<?php echo $html->image('iconRun.png',
            array(
                'alt'   => 'release',
                'align' => 'right',
                'id'    => 'imgRelease'.$pid
            ))?>
<?php echo $html->image('iconReload.png',
            array(
                'alt'   => 'reload',
                'align' => 'right',
                'id'    => 'imgReload'.$pid
            ))?>
</div>
<div id='pool_cont<?php echo $pid;?>'>
<!--  input type="submit" value="Move Here"	onclick="this.form['data[Pool][id]'].value=<?php echo $pool['Pool']['id']?>" /-->
<?php if( count($pool['Registration'] ) <= 1 ){ ?>
<div class="warning">Not Enough Competitors!!!</div>
<?php } ?>
<table id="cmp_lst">
    <thead>
	<tr>
		<th data-sort="string" colspan='2'>competitor</th>
		<th data-sort="string">club</th>
		<th data-sort="string">sex</th>
		<th data-sort="int">age</th>
		<th data-sort="float">weight</th>
		<th data-sort="string">rank</th>
		<th data-sort="string">SWA</th>
		<th data-sort="string">seed</th>
		<th data-sort="string">A</th>
		<th>action</th>
	</tr>
    </thead>
    <tbody>
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
	    <td><?php echo  $form->checkbox( 'Regs.' .$reg['id'] , array('value' => $reg['id'] , 'label'=>false, 'div' => false))  ?></td>
		<td>
		<div id="<?php echo $reg['id']?>" pool_id="<?php echo $reg['pool_id']; ?>"><?php echo  $reg['Competitor']['last_name'] .", ". $reg['Competitor']['first_name']?>
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
		<?php echo  $this->Js->link('[r]', array('controller'=> 'pools', 'action'=>'rem_reg', $reg['id']) , array( 'update'=>'#pool_' . $reg['pool_id'],'confirm'=>'Remove?')) ?>
		
		<?php }?></td>
	</tr>

	<?php
	if( $pool['Pool']['status'] == 0){

            $this->Js->drag( $reg['id']);
	}
	endforeach;
	?>
   </tbody>
</table>

</div>
<?php

	if( $pool['Pool']['status'] == 0){
         $this->Js->dropReg(  'pool_'. $pool['Pool']['id'], $this->Html->url( array( 'action' => 'dropReg/' .  $pool['Pool']['id'])));
	}
    $url = $this->Html->url( array( 'action' => 'edit' , $pid ));
    $this->Js->buffer("
        $('#imgClose${pid}').click( function(){ $('#pool_$pid' ).remove();});
        $('#imgReload${pid}').click( function(){ $('#pool_$pid' ).trigger('refresh');});
        $('#imgEdit${pid}').click( function(){
             $.ajax({
                    url: '$url',
                    cache: false,
                    method: 'GET',
                    data: { },
                    success: function( data ){
                        $('#pool_cont$pid' ).html( data );
                        }
                })
        });
    ");
    echo $this->Js->writeBuffer();
?>
<script type="text/javascript">
 $(function(){

    $("#cmp_lst").stupidtable();
 });
</script>
