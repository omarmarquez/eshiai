<?php
    $form = $this->Form;
     echo $this->Html->script( array('jquery.ui.core'
            , 'jquery.ui.widget'
            , 'jquery.ui.position' 
            , 'jquery.ui.autocomplete' 
            , 'jquery.validate.min'
            ),false );
  
?>

<div id="reg_form" class="registrations form" style='width:80%'>
<h2>Registration</h2>
<?php
	echo $form->create('Registration',array('action'=>'online_submit/' .$event_id,'id' =>'RegForm'));
	echo $form->hidden('Registration.event_id', array('value' => $event_id ));
		if( isset($return['ref'])){
		echo $form->hidden('return', array('value' => $return['ref']));
	}

?>
	<fieldset>
 		<legend><?php __('Competitor Data');?></legend>
 		<table>
 			<tr>
			    <td><?php echo $form->input('Competitor.first_name') ?></td>
			    <td><?php echo $form->input('Competitor.last_name') ?></td>
			 </tr>
			 <tr>
 				<td><label>DOB</label><?php 
                        echo $form->dateTime('Competitor.comp_dob','DMY',NULL,array(
                            'label'  => NULL,
                            'minYear' => date('Y') - 80
                            ,'maxYear' => date('Y') - 4
                        ));
 					?>
 					</td>
 				<td><?php echo $form->input('Competitor.comp_sex',array( 'options' => array( 'M'=>'M', 'F'=>'F'), 'label' => 'Sex')) ?></td>
  			</tr>

 	</table>
  </fieldset>
	<div id='online2'>
 				<?php
                echo $this->Js->submit('next', array(
                    'url' => array(  'action' => 'online2', $event_id ),
                    'update' => '#online2',
                    'evalScripts' => true,
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
                    
                    ));			
 				?>
 	</div>


 <?php echo $form->end();
 
   
    $this->Js->buffer( "

        $('#CompetitorFirstName').focus();
    ");
 ?>
</div>

