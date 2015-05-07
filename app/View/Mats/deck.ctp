<?php
    $class = null;
    $html= $this->Html;
    $form=$this->Form;
    $ajax=$this->Js;

?>
<div id="deck_queue" ></div>
<?php 	//debug($mat);
echo $ajax->remoteTimer(
	array(
	'url' => $html->url(array( 'controller' => 'mats', 'action' => 'deck', $mat['Mat']['id'] ,'ajax', 'deck_queue')),
	'update' 	=> '#deck_queue'
	,'frequency' => 6000
//	,'count' => 2
//	,'complete' => ''
//	,'condition'=> '(rc++ < 2)'
	)
);
//debug($mat);
echo $this->Js->writeBuffer();
?>			