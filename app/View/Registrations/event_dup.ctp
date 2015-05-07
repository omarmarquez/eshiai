<div class="registrations form">

<?php
    echo $this->Form->create('Registration' ); 
?>
    <fieldset>
        <legend><?php __('Registration');?></legend>
        <table>
            <tr>
                <td><?php
                    echo $this->Form->input('Registration.event' , array( ) );

                ?></td>             
            </tr>
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