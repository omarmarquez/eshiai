<?php 
    $session = $this->Session;
    $form = $this->Form;
    $html = $this->Html;

    echo $session->flash(); 

 	echo $this->Js->submit( 'search again',
  				 array(
       				 'url' => array( 'action' => 'online2' )
       				 ,'update'	=> '#online2'
    				)
				);
 ?>
<?php echo $form->input('Competitor.id' , array('type'=>'hidden', 'value' => $competitor['Competitor']['id'])) ?>
	<fieldset>
 		<legend><?php __('Additional Information');?></legend>

 		<table>
 			<tr>
			    <td colspan=2><?php echo $form->input('Competitor.comp_address' , array('label' => 'Address', 'value' => $competitor['Competitor']['comp_address'])) ?></td>
			    <td><?php echo $form->input('Competitor.comp_city' , array('label'=>'City', 'value' => $competitor['Competitor']['comp_city'])) ?>
			    </td>
			    <td>
			    <div style='float:left; width:40%;'>
			     <?php echo $form->input('Competitor.comp_state' , array('label'=>'State', 'value' => $competitor['Competitor']['comp_state'], 'div' => false)) ?>
			     </div>
			     <div style='float:left; width:40%;'>
			     <?php echo $form->input('Competitor.comp_zip' , array('label'=>'Zip', 'value' => $competitor['Competitor']['comp_zip'], 'div' => false)) ?>
			     </div>
			     </td>

			</tr>
			<tr>
			    <td><?php echo $form->input('Competitor.comp_phone' , array('label'=>'Phone', 'value' => $competitor['Competitor']['comp_phone'])) ?></td>
			    <td><?php echo $form->input('Competitor.email' , array( 'value' => $competitor['Competitor']['email'])) ?></td>

			    <td><?php echo $form->input('Competitor.password', array('value' => $competitor['Competitor']['password'], 'type' => 'password')) ?>
			    </td><td> <?php echo $form->input('Competitor.confirm_password', array('value'=> $competitor['Competitor']['password'], 'type' => 'password')) ?></td>

			 </tr>
			 <tr>
		    	<td><?php echo $form->input('Competitor.rank' , array( 'value' => $competitor['Competitor']['rank'])) ?></td>
			 	<td><?php echo $form->input('Competitor.membership_type' , array( 'value' => $competitor['Competitor']['membership_type'])) ?></td>
		    	<td><?php echo $form->input('Competitor.membership_number' , array( 'value' => $competitor['Competitor']['membership_number'])) ?></td>
		    	<td><?php echo $this->Form->label('Competitor.membership_expiration'); echo $form->datetime('Competitor.membership_expiration' , 'DMY', NULL, array( 'value' => $competitor['Competitor']['membership_expiration'])) ?></td>
		    	<td>&nbsp;</td>		 			 
			 </tr>
 			</table>
 	</fieldset>
	<fieldset>
 		<legend><?php __('Club Information');?></legend>
 		<div id='set_club'>
 		<?php echo $form->input('Club.id' , array('type'=>'hidden', 'value' => $competitor['Club']['id'])) ?>
 		</div>
 		<table>
 		<tr>
 				<td colspan=2><?php echo $form->input('Club.club_name' , array( 'value' => $competitor['Club']['club_name'])) ?>
 				</td>
 	 	 		
				<td>
 				<?php //echo $form->input('Club.abbr' , array( 'value' => $competitor['Club']['club_abbr'])) ?>

				</td>
 			</tr>
	 			</table>
 	</fieldset>
    <script language="JavaScript">
                    $(function() {
                         $( "#ClubClubName" ).autocomplete({
                            minLength:3,
                            source:'<?php echo $this->Html->url( array( 'controller' => 'clubs' ,'action' => 'autoComplete2')) ; ?>',
                            before:  $("#busy-indicator").fadeIn(),
                            complete: $("#busy-indicator").fadeOut()
                            });
                         });
   </script>

<?php
 
	echo $this->Form->submit( 'next', array( 'url' => array( 'action' => 'online_submit', $event_id  )));

 	//echo
 	 $this->Js->submit( 'next',
  				 array(
  				     'id' => 'confirmbtn',
       				 'url' => array( 'action' => 'online_submit' , $event_id ),
       				 'update'	=> '#online2',
       				 'evalScripts' => true,
                     'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                     'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
 
    				)
				);
   echo $this->Js->writeBuffer(); // Write cached scripts  
 ?>
