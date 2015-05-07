<?php

	header('Content-type: application/vnd.pdf');
    header('Content-length: '. strlen($pdfStr));
    header('Content-Disposition: attachment; filename="document.pdf"');
echo $content_for_layout;
die();
?>