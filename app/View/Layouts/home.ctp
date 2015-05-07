<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo  'JudoShiai::' . $title_for_layout?></title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<?php	echo $html->css('cake.css'); ?>
		<?php	echo $html->css('judo.css'); ?>
	</head>
	<body>

		<!-- If you'd like some sort of menu to show up on all of your views, include it here -->
		<div id="header">
	<div id="menu" class="float_right">
			<div class="menu_item"><?php	echo $html->link('Score Board','/mats/score_board', array('class'=>'button')); ?></div>
			<div class="menu_item"><?php	echo $html->link('About','https://omarquez.no-ip.org/wiki/index.php/BudoComp', array('class'=>'button')); ?></div>
	</div>
		</div>
	  <table align="center">
	   <td>
         <?php
		$i = rand(1,26);
		$img = str_pad($i, 2 , "0", STR_PAD_LEFT);
    		 echo $html->image('layout/budo' . $img .'.jpg',array('alt' => 'temporal image','height' => '300', 'width' => '300'));
        ?>
        </td>
        <td>

		<!-- Here's where I want my views to be displayed -->
		<?php echo $content_for_layout ?>
	 </td>
	 </table>
		<!-- Add a footer to each displayed page -->
		<div id="footer" style="align:center;">
		<center>
		<div style="height:100px;">&nbsp;</div>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-1742662962338298";
/* ad_home_bottom */
google_ad_slot = "7279906425";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</center>
	</div>
	</body>
</html>
