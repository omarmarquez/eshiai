<span id="dummyspan"></span>
<script language="javascript" type="text/javascript">
function DHTMLSound( snd ) {
  document.getElementById("dummyspan").innerHTML=
    "<embed src='/judocomp/files/wav/"+snd+".wav' hidden=true autostart=true loop=false volume=60>";
}
</script>
<div class="mats form">
<?php 
$form = $this->Form;
$opts = array();

if( isset( $this->data['Mat']['id'])){
  
    $opts = array('controller' => 'mats',  'action' => 'edit'  );
}

echo $form->create('Mat',  $opts );?>
	<fieldset>
 		<legend><?php __('Add/Edit Mat');?></legend>
	<?php
        echo $form->hidden('id');
        echo $form->input('name');
		echo $form->input('event_id', array('type'=>'hidden', 'value'=>$_SESSION['Event']['id']));
		echo $form->input('location');
		?>
		<table>
		<?php 
		$sound = array( 1 => 'alarm01', 2 => 'alarm02' ,3 => 'alarm03',4 => 'alarm04', 5 => 'alarm05');
		foreach( $sound as $n =>  $s): ?>
		<tr>
		<td>
		    <audio id="audio_<?php echo $n?>" name="audio_<?php echo $n?>"  preload=1 ><source src="<?php echo $this->Html->url( "/files/wav/${s}.wav" ,true)?>"/></audio>
		    
		    <input type='radio' name='data[Mat][sound]' value='<?php echo $n?>' id='<?php echo $s?>' <?php if(isset($this->data['Mat']['sound']) && $n == $this->data['Mat']['sound']) echo "checked"?>/> 
		<a  onClick="document.getElementById('audio_<?php echo $n?>').play()"> Sound<?php echo $n?></a></td>

		</tr>
	<?php endforeach ; ?>
	</table>		
		
	</fieldset>
	<?php
	if( $this->request->is('ajax')){
	    echo $this->Js->submit( 'Save', array( 'update' =>'#mat_' . $this->data['Mat']['id'], 'url' => array('controller' => 'mats' ,'action' =>'edit/' . $this->data['Mat']['id'])));
	}else{

	   echo $form->end('Submit');
    }
    
      echo $this->Js->writeBuffer();
 
	?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $this->Html->link(__('Delete', true), array('action'=>'delete', $form->value('Mat.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Mat.id'))); ?></li>
    </ul>
</div>