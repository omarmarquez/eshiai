<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="refresh" content="<?php echo isset($rate)?$rate:10?>" >
		<title><?php echo  'BudoComp::' . $title_for_layout?></title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<?php
        echo $this->Html->css('cake');
        echo $this->Html->css('judo');
	 	?>
	</head>
	<body>
		<?php echo $content_for_layout ?>
	</body>
</html>
