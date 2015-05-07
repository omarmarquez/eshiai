<span id="dummyspan"></span>
<script language="javascript" type="text/javascript">
function DHTMLSound( snd ) {
  document.getElementById("dummyspan").innerHTML=
    "<embed src='/judocomp/files/wav/"+snd+".wav' hidden=true autostart=true loop=false >";
}
</script>
<div class="mats form">
<?php 
$form = $this->Form;
echo $form->create('Mat');?>
	<fieldset>
 		<legend><?php __('Edit Mat');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
		echo $form->input('event_id');
		echo $form->input('location');
		
		$snd = $this->data['Mat']['sound'];
	?>
	<table>
		<?php   
		$sound = array( 1 => 'alarm01', 2 => 'alarm02' ,3 => 'alarm03',4 => 'alarm04', 5 => 'alarm05');
		foreach( $sound as $n =>  $s): ?>
		<tr>
		<td><input type='radio' name='data[Mat][sound]' value='<?php echo $n?>' id='<?php echo $s?>' <?php if($snd == $n ){ echo " checked";}?> /> 
		<a  onClick="getElementById( '<?php echo $s?>' ).click(); DHTMLSound( '<?php echo $s?>' );"> Sound<?php echo $n?></a>
		&nbsp; <?php if($snd == $n ){ echo " current";}?>
		</td>
		
		</tr>
	<?php endforeach ; ?>
	</table>		
	
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Mat.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Mat.id'))); ?></li>
	</ul>
</div>
