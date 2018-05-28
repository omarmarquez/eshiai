<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo __('Tournament Management System'); ?>
		<?php echo __( isset($event_name)?':' . $event_name :''); ?>
	</title>
	<?php

	 //  echo $this->Html->meta('icon');
	//	 echo $this->Html->meta('icon',$this->Html->url('favicon.ico')	);
	//	 echo $this->Html->css('cake');
         echo $this->Html->css('cake.generic');
         echo $this->Html->css('judo');
          
         echo $this->Html->script('jquery');
         echo $this->Html->css('jquery-ui');
         
         echo $this->Html->script('stupidtable.min');

		echo $scripts_for_layout;
		?>
	

</head>
<body>
	<div id="container" style="width:100%;float:left;">
	<div id="header">

	<div id='cmp_search'>
	<?php
	 	echo $this->Form->create('Competitor', array('controller'=>'competitors','action'=>'search','label'=>'search'));
	 	echo $this->Form->hidden('Competitor.event_id',array('value' =>  $event_id ));
	 	echo $this->Form->input('Competitor.search',array(
			'type'=>'text'
			,'value'=>'search'
			,'label' => ''
			,'size'=> '20'
			,'maxLength'=>'24'
			,'div'=>false
			,'onfocus' => 'this.value="";'
			));
	 	echo $this->Form->end();
	?>
	
	 </div>
	 
			<?php if(isset($event_id)): ?>
          <div class="menu_item"><?php echo  $this->Html->link(__('Event', true), array('controller'=> 'events', 'action'=>'view',  $event_id ));?> </div>
             <div class="menu_item"><?php echo  $this->Html->link(__('Registrations', true), array('controller'=> 'registrations', 'action'=>'index',  $event_id  ));?> </div>
            <div class="menu_item"><?php echo  $this->Html->link(__('Check-In', true), array('controller'=> 'registrations', 'action'=>'checkIn',  $event_id  ));?> </div>
            <div class="menu_item"><?php echo  $this->Html->link(__('Weigh-In', true), array('controller'=> 'registrations', 'action'=>'weighIn',  $event_id  ));?> </div>
             <div class="menu_item"><?php echo  $this->Html->link(__('Pools', true), array('controller'=> 'pools', 'action'=>'index',  $event_id ));?> </div>
			<div class="menu_item"><?php echo  $this->Html->link(__('Head Table', true), array('controller'=> 'mats', 'action'=>'headTable',  $event_id));?> </div>
            <div class="menu_item"><?php echo  $this->Html->link(__('Awards', true), array('controller'=> 'pools', 'action'=>'awards',  $event_id ));?> </div>
            <div class="menu_item"><?php echo  $this->Html->link(__('Reports', true), array('controller'=> 'reports', 'action'=>'index',  $event_id ));?> </div>
            <div class="menu_item"><?php echo  $this->Html->link(__('Logout', true), array('controller'=> 'users', 'action'=>'logout',  $event_id ));?> </div>
            <?php else: ?>
            <div class="menu_item"><?php echo  $this->Html->link(__('Home', true), array('controller'=> 'events', 'action'=>'index'  ));?> </div>
             <div class="menu_item"><?php echo  $this->Html->link(__('Score Board', true), array('controller'=> 'mats', 'action'=>'board',  $event_id));?> </div>
            <div class="menu_item"><?php echo  $this->Html->link(__('Weigh In', true), array('controller'=> 'registrations', 'action'=>'weighIn'));?>
            <?php endif; ?>
 		</div>
		<div  id="indicator" >
		      <?php echo $this->Html->image('ajax-loader.gif', array('id' => 'busy-indicator')); ?>
		</div>
		<div id="content" >
			<div style="width:130px;height:16px;"><div id="ajax_ind" style="display:none"><?php echo $this->Html->image('ajax-loader.gif');?></div></div>
                <?php echo $this->Session->flash();  ?>
                
				<div id="event_name" style="float:right;z-index:-1;font-size:130%;width:500px;">
				<h2><?php echo $event_name;?></h2></div>
    			
    			<?php echo $content_for_layout; ?>
		</div>

	<div id="footer">
			&nbsp;<div class="menu_item"><?php echo  $this->Html->link(__('Clubs', true), array('controller'=> 'clubs', 'action'=>'index',  $event_id  ));?> </div>
			&nbsp;<div class="menu_item"><?php echo  $this->Html->link(__('Competitors', true), array('controller'=> 'competitors', 'action'=>'index',  $event_id ));?> </div>
            &nbsp;<div class="menu_item"><?php echo  $this->Html->link(__('Users', true), array('controller'=> 'users', 'action'=>'index',  $event_id ));?> </div>
	</div>
	
	<div style="float:right;font-size:80%;">
	<div style="height:20px;">&nbsp;</div>
	<!-- google_ad_section_start -->
	<p>Martial arts competition site featuring Judo tournament setup, online registration, pooling, match scheduling, awards and reports.</p>
	<!-- google_ad_section_end -->
	</div>	 
		<div style="clear:both;"></div>
	</div>
    <?php  echo $this->Js->writeBuffer(); // Write cached scripts ?>
	<?php // include_once 'add_sense.ctp';?>
	<?php echo  $this->element('sql_dump'); ?>
</body>
</html>
