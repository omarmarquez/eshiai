<div class="registrations form">

<?php
    echo $this->Form->create('Registration' );  // ,array('action'=>'onsite'));
    echo $this->Form->hidden('Registration.event_id', array('value' => $event_id ));
    echo $this->Form->hidden('id');
    echo $this->Form->hidden('weight');
    echo $this->Form->hidden('competitor_id');
    echo $this->Form->hidden('Competitor.club_id', array('default' => 0 ));
        if( isset($return['ref'])){
        echo $this->Form->hidden('return', array('value' => $return['ref']));
    }

?>
    <fieldset>
        <legend><?php __('Registration');?></legend>
        <table>
            <tr>
                <td>Club:<?php
                    echo $this->Form->input('Club.club_name' , array( ) );
                    echo "<br>";
                    echo $this->Form->input('Club.club_abbr' , array(  ) );
                ?></td>             
                <td>Competitor:<?php
                    echo $this->Form->input('Competitor.name' , array() ) ;
                    ?></td>
                <td><?php echo $this->Form->input('Competitor.comp_dob', array('type'=>'text' , 'between' => 'YYYY-MM-DD')) ?></td>
                <td><?php echo $this->Form->input('Competitor.comp_sex',array( 'options' => array('','F'=>'F','M'=>'M'),'default'=>'')) ?></td>
            </tr>
            <tr>
                <td><?php echo $this->Form->input('rank') ?></td>
                <td><?php echo $this->Form->input('division',array('options'=>array('','juniors'=>'juniors','seniors'=>'seniors','masters'=>'masters','open'=>'open')));?></td>
                <td><?php echo $this->Form->input('card_type' ,array( 'options' => array('','USJF'=>'USJF','USJA'=>'USJA','USA Judo'=>'USA Judo','OTHER'=>'OTHER') ) )?></td>
                <td><?php echo $this->Form->input('card_number') ?></td>
            </tr>
            <tr>
                <td><?php echo $this->Form->input('rtype',array('options'=>array('','shiai'=>'shiai','kata'=>'kata'),'default'=>'shiai'));?></td>
                <td>
                <div style='float:left;'>
                <?php echo $this->Form->input('upSkill',array( 'options'=> array('N'=>'N','Y'=>'Y'), 'div' => false)) ?>
                </div>
                <div style='float:left;'>
                <?php echo $this->Form->input('upWeight',array( 'options'=> array('N'=>'N','Y'=>'Y'), 'div' => false)) ?>
                </div>
                <div style='float:left;'>
                <?php echo $this->Form->input('upAge',array( 'options'=> array('N'=>'N','Y'=>'Y'), 'div' => false)) ?>
                </div>
                </td>
                <td colspan='1'>
                    <?php echo $this->Form->input('card_verified')?>
                    <?php echo  $this->Form->input('approved')?>
                    <?php echo  $this->Form->input('paid')?>
                 </td>
              <td colspan="2">
               <?php echo $this->Form->input('comments',array( 'type' => 'textarea', 'rows' =>2 )) ?>
              </td>
            </tr>
             <tr>
                <td>Kata:<?php
                    echo $this->Form->input('kata_name' , array( ) );
                ?></td>             
                <td colspan="3"> <?php
                    echo $this->Form->input('kata_partner' , array() ) ;
                 ?></td>
              </tr>           
            <tr colspan="4">
                <td><?php echo $this->Form->submit()?></td>
                 <td><p>&nbsp;</p><?php echo $this->Form->button('Reset', array('type' => 'reset'));?></td>
            </tr>
        </table>
    </fieldset>

<?php
        echo $this->Form->end();
?>
</div>
