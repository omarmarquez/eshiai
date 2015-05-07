<?php
if(!isset($inpage)) {
	header('Content-type: ' . $eventFile['EventFile']['type']);
    header('Content-length: '.$eventFile['EventFile']['size']);
    header('Content-Disposition: attachment; filename="'.$eventFile['EventFile']['name'].'"');
   }
echo $content_for_layout;
die();
?>