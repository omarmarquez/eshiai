
<?php
    $form = $this->Form;
     echo $this->Html->script( array('jquery.ui.core'
            , 'jquery.ui.widget'
            , 'jquery.ui.position' 
            , 'jquery.ui.autocomplete' 
            , 'jquery.validate.min'
            ),false );
  
?>

<div class="registrations form">

<?php
    echo $this->Form->create('Competitor' );  // ,array('action'=>'onsite'));
    echo $this->Form->hidden('id');
 
?>
    <fieldset>
        <legend><?php __('Competitor Data');?></legend>
        <table>
            <tr>
                <td><?php echo $form->input('first_name') ?></td>
                <td><?php echo $form->input('last_name') ?></td>
               <td colspan=2><?php echo $form->input('club_id') ?></td>
             </tr>
             <tr>
                <td><label>DOB</label><?php 
                        echo $form->dateTime('comp_dob','DMY',NULL,array(
                            'label'  => NULL,
                            'minYear' => date('Y') - 80
                            ,'maxYear' => date('Y') - 4
                        ));
                    ?>
                    </td>
                <td><?php echo $form->input('Competitor.comp_sex',array( 'options' => array( 'M'=>'M', 'F'=>'F'), 'label' => 'Sex')) ?></td>
               <td><?php echo $form->input('comp_phone') ?></td>
                <td><?php echo $form->input('email') ?></td>
             </tr>
            <tr>
                <td><?php echo $form->input('comp_address') ?></td>
                <td><?php echo $form->input('comp_city') ?></td>
               <td><?php echo $form->input('comp_state') ?></td>
                <td><?php echo $form->input('comp_zip') ?></td>
             </tr>
             <tr>
            <tr >
                <td><?php echo $this->Form->submit()?></td>
                 <td><?php //echo $this->Form->reset();?></td>
            </tr>

    </table>
  </fieldset>
   
<?php
        echo $this->Form->end();
?>
</div>
