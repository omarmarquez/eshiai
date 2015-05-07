<div class="registrations form">

<?php
    echo $this->Form->create('Registration',  array('type' => 'file') ); 
    echo $this->Form->input('event_id', array('type' => 'hidden'));
?>
    <fieldset>
        <legend><?php __('Registration');?></legend>
        <table>
            <tr>
                <td><label>CSV file with WP Payments</label></td>
             </tr>
            <tr>
                <td><?php
                    echo $this->Form->input('cfile', array('type' => 'file'));

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