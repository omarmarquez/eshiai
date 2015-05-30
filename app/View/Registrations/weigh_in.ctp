<?php
    echo $this->Html->script( array('jquery.ui.core'
        , 'jquery.ui.widget'
        , 'jquery.ui.position' 
        , 'jquery.ui.autocomplete' ),false );

?>
 <?php if( empty($competitors)): ?>
<div id='weightIn1'>
<h2>Weight In</h2>
<div id='form' style="width:200px;">
<?php echo $this->Form->create('Registration', array( 'action' => 'weightIn/' . $event_id ));?>
    <fieldset>
        <legend><?php __('Competitor');?></legend>
    <?php
            echo $this->Form->input('Competitor.name' , array( 'value' => $comp_name )) ;
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
                'update'  => '#weighIn'
                ,'url'  => array( 'action' => 'weighIn/'. $event_id)
        ));
    ?>
    </fieldset>
<?php echo $this->Form->end();?>
</div>
<?php endif; ?>
<div class="registrations form" id='weighIn'>
    <?php if( !empty($competitors)): ?>
	
    <?php foreach( $competitors as $c ): ?>

    <table>
	<tr>
		<th colspan="2">Name</th>
        <th>DOB</th>
        <th>Sex</th>
        <th>City</th>
        <th>State</th>
        <th>Club</th>
        <!--
	<th>Card Type</th>
	<th>Card Number</th>
	<th>Card Verified</th>
	<th>Release Forms</th>
	-->
        <th>Weight</th>
	</tr>


	
    <tr>
        <td><?php echo $c['Competitor']['first_name'];?></td>
        <td><?php echo $c['Competitor']['last_name'];?></td>
        <td><?php echo $c['Competitor']['comp_dob'];?></td>
        <td><?php echo $c['Competitor']['comp_sex'];?></td>
        <td><?php echo $c['Competitor']['comp_city'];?></td>
        <td><?php echo $c['Competitor']['comp_state'];?></td>
        <td><?php echo $c['Club']['club_name'];?></td>
		<!--
		<td><?php echo $c['Registration']['card_type'];?></td>
		<td><?php echo $c['Registration']['card_number'];?></td>
		<td><?php echo $this->Form->checkbox( 'card_verified' , array( $c['Registration']['card_verified']==1?'checked':'none'));?></td>
		<td><?php echo $this->Form->checkbox( 'approved' , array( $c['Registration']['approved']==1?'checked':'none'));?></td>
		-->
		<td><?php echo $this->Form->input( 'competitor_id', array( 'type' => 'hidden', 'value' => $c['Competitor']['id']));?>
            <?php echo $this->Form->input( 'name', array( 'type' => 'hidden', 'value' => $comp_name));?>
            <?php echo $this->Form->input( 'weight', array( 'style' => 'width:60px;', 'label'=> false, 'div'=>false));?>
			<?php echo $this->Form->submit( 'Submit');
             $this->Js->submit( 'Submit', array(
                'update'  => 'weightIn1'
                ,'url'  => array( 'action' => 'weighIn/'. $event_id)
                ,'div' => false
				));
			 echo $this->Form->end();
				?>
			</td>
    </tr>
<?php endforeach ; ?>
</table>
    <?php 
        echo $this->Js->writeBuffer(); // Write cached scripts
        endif;
    ?>
</div>
</div>