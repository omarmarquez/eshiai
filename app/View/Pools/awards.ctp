<?php
    echo $this->Html->script( array('jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.position' ),false );
    echo $this->Html->script( array('jquery.ui.tabs','jquery.ui.accordion','jquery.ui.autocomplete' ),false );
    echo $this->Html->script( array('jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable','jquery.ui.resizable' ,'jquery.ui.dialog'),false );
    $html=$this->Html;
    $form=$this->Form;
    $ajax=$this->Js;
?>
<div id="pools" class="pools index" >

<h2><?php __('Event Awards');?></h2>
<div class="actions">
	<ul>
		<li><?php // echo $html->link(__('Update Awards', true), array('action'=>'awards_set_pos_event', 'id'=>$event[ 'Event']['id' ])); ?></li>
	</ul>
</div>
<?php 	
	echo $form->create('Pool', array( 'action'=>'awards'));
	echo $form->input('Event.id', array('type'=>'hidden','value'=> $event[ 'Event']['id' ]) );
?>

<table>
 <tr>
  	<td> 
 		<?php echo  $form->input('Pool.division', array('options'=>array(''=>'all' , 'juniors'=>'juniors','seniors'=>'seniors', 'masters'=>'masters'))) ?>
 	</td>
  	<td> 
 		<?php echo  $form->input('Pool.category', array('options'=>array(''=>'all' , 'novice'=>'novice','advanced'=>'advanced' ))) ?>
 	</td>
    <td> 
 		<?php echo  $form->submit('Filter' ) ?>
 	</td>
 	
 </tr>
</table>
<?php echo  $form->end() ?>
</div>
<div id='awards_lst'></div>
<?php 
	echo  $ajax->remoteTimer(
	array(
	'url' => $html->url(array('controller'=>'pools', 'action'=>'awards_lst/'. $event[ 'Event']['id' ] )),
	'update' => '#awards_lst',
	 'frequency' => 10000
	)
);
	
?>
