<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo  'BudoComp::' . $title_for_layout?></title>
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
		<?php 	echo $html->css('cake.css'); ?>
		<?php 	echo $html->css('judo.css'); ?>
	</head>
	<body>
	
		<!-- If you'd like some sort of menu to show up on all of your views, include it here -->
<div id="header">
		

	<div class="float_left">
	<h1><?php echo  $session->read('event_name');?></h1>
	</div>

	<div id="menu" class="float_right">
			<div class="menu_item"><?php 	echo $html->link('Events','/events/index', array('class'=>'button')); ?></div>
			<div class="menu_item"><?php 	echo $html->link('Score Board','/mats/score_board', array('class'=>'button')); ?></div>
	</div>
</div>
	
	<?php 	if( $session->check( "event_id") ){  
			//debug($session);
			$eid = $session->read('event_id');
			// debug($eid);
		?>
	
	<div id='event_menu'>			
	<div class="actions">
			<div class="menu_item"><?php echo  $html->link(__('Registrations', true), array('controller'=> 'registrations', 'action'=>'index',  $eid  ));?> </div>
			<div class="menu_item"><?php echo  $html->link(__('Pooling', true), array('controller'=> 'pools', 'action'=>'admin',  $eid ));?> </div>
			<div class="menu_item"><?php echo  $html->link(__('Mats', true), array('controller'=> 'mats', 'action'=>'index',  $eid));?> </div>
			<div class="menu_item"><?php echo  $html->link(__('Awards', true), array('controller'=> 'pools', 'action'=>'awards',  $eid ));?> </div>
			<div class="menu_item"><?php echo  $html->link(__('Clubs Report', true), array('controller'=> 'clubs', 'action'=>'clubs',  $eid ));?> </div>
			<div class="menu_item"><?php echo $html->link( __('Setup', true), '/events/view/' . $eid  , array('class'=>'button')); ?></div>

	</div>
	</div>
	<div id='cmp_search'> 
	<?php echo 
	 	echo $form->create('Competitor', array('controller'=>'competitors','action'=>'search','label'=>'search'));
	 	echo $form->hidden('Competitor.event_id',array('value' =>  $eid ));
	 	echo $form->input('Competitor.search',array('type'=>'text','s'=> '32', 'maxLength'=>'32','label'=>false,'div'=>false));
	 	echo $form->submit('>',array( 'label'=>false,'div'=>false));
	 	echo $form->end();
	 	
	 	?>
	 <script language="javascript">
	 	document.getElementById('CompetitorSearch').focus();
	 </script>
	 	
	 </div> 
	  
	
	<?php } ?>	
	  <table align="center">
	   <td>

	<?php 
	 if ($session->check('Message.flash')): $session->flash(); endif; // this line displays our flash messages
	?>
		<!-- Here's where I want my views to be displayed -->
		<?php echo $content_for_layout ?>
	 </td>
	 </table>
		<!-- Add a footer to each displayed page -->
		<div id="footer">...</div>
	</body>
</html>
