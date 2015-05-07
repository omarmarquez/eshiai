<?php
echo $javascript->link('prototype');
echo $javascript->link('scriptaculous'); 
echo $javascript->link('effects'); 
echo $javascript->link('dragdrop'); 
?>
<div class="actions">
<?php 	
	echo $form->create('Pool', array( 'action'=>'print_brackets'));
	echo $form->input('Event.id', array('type'=>'hidden','value'=>  $event ) );
?>
<table>
 <tr>
 	<td> 
 		<?php echo  $form->input('Pool.division', array('options'=>array(''=>'all' , 'juniors'=>'juniors','seniors'=>'seniors', 'masters'=>'masters'))) ?>
 	</td>
   	<td> 
 		<?php echo  $form->submit('Filter' ) ?>
 	</td>
 	
 </tr>
</table>
<?php echo  $form->end() ?>
</div>

<div class="scale_print_150">
<h2><?php  __('Pools');?></h2>

<?php foreach( $pools as $pool): ?>
<P  style="page-break-before: always;"/></P>
<?php  include('display.ctp'); ?>
 <?php endforeach;?>
</div>
