    <div id='content_form'>
<?php 
    $form = $this->Form;
    $html = $this->Html;
    $pid = isset($this->data['Pool']['id'] )?$this->data['Pool']['id']:0;
    
?>
<div class="pools form">
<div id='msgs'></div>
<table>
<tr>
<td> 	
<?php echo $form->create('Pool', array( )); ?>

<?php  //echo $form->hidden('ref.url',array('value'=>$ref_url))?>
<?php echo $form->hidden('id' ) ?>
<?php echo $form->hidden('event_id' ) ?>
	<fieldset>
 		<legend><?php __('Division');?></legend>	
<table>
	<tr>
		<td colspan="2"><?php echo  $form->input('pool_name')?></td>
    </tr>
	<tr>
		<td colspan="2"><?php echo  $form->input('match_duration')?></td>
    </tr>
    <tr>
		<td><?php echo  $form->input('division',array('options'=>array('juniors'=>'juniors','seniors'=>'seniors','masters'=>'masters','open'=>'open'))) ?></td>
		<td><?php echo  $form->input('category',array('options'=>array(''=>'', 'advanced'=>'advanced','novice'=>'novice'))) ?></td>
	</tr>
	<tr>
		<td><?php echo $form->input('sex',array('options'=>array('M'=>'M','F'=>'F', 'A'=>'All'))) ?></td>
        <td><?php echo $form->input('type', array('options'=>
                array(
                    'rr'=>'Round Robin'
                    ,'se'=>'Single Elimination'
                    ,'de'=>'Double Elimination'
                    ,'md'=>'Modified Double'
                    ) ,'default' => $dpt  )
                    ) ?></td>
   </tr>
    <tr>
		<td><?php echo $form->input('min_age') ?></td>
		<td><?php echo $form->input('max_age') ?></td>

	</tr>
	<tr>
		<td><?php echo $form->input('min_weight') ?></td>
		<td><?php echo $form->input('max_weight') ?></td>
	</tr>
	<tr>
		<td colspan="2">
		<?php
			echo $form->submit('Save Changes'); 
            //echo $this->Js->submit('Save', array('update' => '#pool_'.$pid, 'url' => array( 'action' => 'edit' )));
		?>
		
	</tr>


</table>
	</fieldset>
<?php echo $form->end();?>
</td>

</tr>
</table>
<?php   if(isset($this->data['Pool']['id'])): ?>

   <div class="menu_item"><?php echo $html->link(__('[Delete]', true), array('action'=>'delete', $this->data['Pool']['id']), null, sprintf(__('Delete pool %s. Are you sure?',  $this->data['Pool']['pool_name']) )); ?></div>    

</div>
<?php

     echo $this->Js->writeBuffer();
   
?>
<?php endif; ?>
</div>
