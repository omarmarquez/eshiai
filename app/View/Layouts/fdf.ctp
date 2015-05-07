<?php

	header('Content-type: application/vnd.fdf');
    header('Content-length: '. strlen($fdfStr));
    header('Content-Disposition: attachment; filename="document.fdf"');
echo $content_for_layout;
die();
?>