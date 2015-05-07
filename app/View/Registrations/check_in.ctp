<?php
     echo $this->Html->script( array('jquery.ui.core'
     , 'jquery.ui.widget'
     , 'jquery.ui.position' 
     , 'jquery.ui.autocomplete' ),false );

?>
 <?php if( empty($competitors)): ?>
<div id='weightIn1'>
<h2>Check In</h2>
<div id='form' style="width:200px;">
<?php echo $this->Form->create('Registration', array( 'action' => 'checkIn/' . $event_id ));?>
    <fieldset>
        <legend><?php __('Competitor');?></legend>
        <?php
        
            echo $this->Form->input('Competitor.name' ) ;
            $action = $this->Html->url( array( 'action' => 'autoCompetitor')) ; 
            $this->Js->buffer( "
                       $( '#CompetitorName' ).autocomplete({
                            minLength:3,
                            source: '$action' ,
                            before:  $('#busy-indicator').fadeIn(),
                            complete: $('#busy-indicator').fadeOut()
                       });
             ");
        

        echo $this->Js->submit( 'Find', array(
                'update'  => '#checkIn'
                ,'url'  => array( 'action' => 'checkIn/'. $event_id)
        ));
    ?>
    </fieldset>
<?php echo $this->Form->end();?>
</div>
<?php endif; ?>
<div class="registrations form" id='checkIn' style="width:80%;">
    <?php if( !empty($competitors)): ?>
 
    <?php foreach( $competitors as $c ): ?>
         <?php 
            echo $this->Form->create('Registration', array( 'action' => 'checkIn/' . $event_id ));
           echo $this->Form->input( 'id', array( 'type' => 'hidden', 'value' => $c['Registration']['id']));
            echo $this->Form->input( 'competitor_id', array( 'type' => 'hidden', 'value' => $c['Competitor']['id']));
           
            echo $this->Form->input( 'name', array( 'type' => 'hidden', 'value' => $comp_name));
   	?>
   	<div id='reg_<?php echo $c['Registration']['id'];?>'>
   	<hr>
     <table>
	<tr>
	    <th>Registration</th>
		<th colspan="2">Name</th>
        <th>DOB</th>
        <th>Age</th>
        <th>Sex</th>
        <th>City</th>
        <th>State</th>
        <th>Rank</th>
        <th>Club</th>
 	</tr>
    <tr>
        <td><?php echo $c['Registration']['id'];?></td>
       <td><?php echo $this->Html->link(  $c['Competitor']['first_name'] . ' '. $c['Competitor']['last_name'], array( 'controller' => 'competitors', 'action'=> 'edit',$c['Competitor']['id'] ));?></td>
        <td></td>
        <td><?php echo $c['Competitor']['comp_dob'];?></td>
       <td><?php echo $c['Registration']['age'];?></td>
        <td><?php echo $c['Competitor']['comp_sex'];?></td>
        <td><?php echo $c['Competitor']['comp_city'];?></td>
        <td><?php echo $c['Competitor']['comp_state'];?></td>
         <td><?php echo $c['Registration']['rank'];?></td>
        <td><?php echo $c['Club']['club_name'];?></td>
     </tr>
    <tr>
    <td colspan ='10'>
    	<b>Comments</b> 
      	<?php echo $this->Form->textarea( 'comments' , array( 'rows' => 2, 'value' => $c['Registration']['comments'])); ?>
     </td>
     </tr>
     <tr>
     <th>Division</th>
   	 <th>Up&nbsp;Age/Skill/Weight</th>
     <th>Weight</th>
     <th>Card Type</th>
     <th>Card Number</th>
     <th>Card Verified</th>
     <th>Payment</th>
     <th>Approved</th>
      <th>Action</th>
 	</tr>
     <tr>
       <td><?php echo $c['Registration']['division'];?></td>
      <td><?php echo $c['Registration']['upAge'] .' ' .  $c['Registration']['upSkill'] .' ' .$c['Registration']['upWeight'];?> </td>
      <td><?php echo $c['Registration']['weight'];?></td>
      <td><?php echo $c['Registration']['card_type'];?></td>
       <td><?php echo $c['Registration']['card_number'];?></td>
   <td colspan ='1'>
   
     	<?php echo $this->Form->checkbox( 'card_verified' , array( $c['Registration']['card_verified']==1?'checked':'none')); ?>
     </td>
      <td><?php echo $c['Registration']['paid'];?></td>
    <td colspan ='1'>
  
     	<?php echo $this->Form->checkbox( 'approved' , array( $c['Registration']['approved']==1?'checked':'none')); ?>
     </td>
     <td>
      	   <?php 
          echo  
             $this->Js->submit( 'Update', array(
                'update'  => '#reg_' . $c['Registration']['id']
                ,'url'  => array( 'action' => 'checkIn2/'. $event_id)
                ,'div' => false
        ));
	
 //echo $this->Html->url( array('action' => 'edit/' . $c['Registration']['id'])) ; 

   ?>
     
     </td>
    </tr>
 
    </table>
   </div>
 <?php 
    echo $this->Form->end();
 	endforeach ; 
    endif;
    echo $this->Js->writeBuffer(); // Write cached scripts  
 ?>
</div>
</div>
