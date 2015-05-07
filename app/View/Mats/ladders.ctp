<?php
    $class = null;
    $html= $this->Html;
    $form=$this->Form;
    $ajax=$this->Js;

	if( isset($matids)):

	$nm = count($matids);
	foreach( $matids as $i => $mat ):
	
		$mid = $mat['Mat']['id'];
?>

<div class='deck_queue_multi_<?php echo $nm?>' >
    <div style='font-size: 500%;'><b><?php echo $mat['Mat']['name']?></b></div>
	<div id='deck_queue_<?php echo $mid ?>' class='deck_queue_multi'></div>
</div>
<?php 	
 if ( $nm == 4 && $i == 1 ){
 	echo "<hr style='clear:both;'>";
 }
echo $ajax->remoteTimer(
	array(
	'url' => array( 'controller' => 'mats', 'action' => 'deck' , $mid ,'ajax', 'deck_queue'),
	'update' 	=> '#deck_queue_' . $mid 
	,'frequency' => 6000
//	,'complete' => ''
//	,'condition'=> '(rc++ < 2)'
	)
);
//debug($mat);


	endforeach;

 
  endif; 

  	if( !isset($matids)):

	echo $form->create( 'Mat', array('action'  => 'ladders'));
	foreach( $mats as $mat):
		?>
		<div style='float:left;'>
			<input type='checkbox' name='data[Mat][matids][]' value='<?php echo $mat['Mat']['id']?>'/>
			<b><?php  echo $mat['Mat']['name']?></b>
		</div>
	<?php 
	endforeach;
	echo $form->submit( 'Display' , array());
	echo $form->end();
	
	endif;
    
    echo $this->Js->writeBuffer();
?> 