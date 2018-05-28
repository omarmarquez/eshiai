<?php
    $ix = 0;
    echo $form->create('Pool', array( 'controller' => 'pools', 'action'=>'add'));
     echo $form->input('Pool.id', array('type'=>'hidden'));
?>

    <fieldset>
        <legend><?php __('Pool');?></legend>    
<table>
    <tr>
        <td colspan="2"><?php echo  $form->input('pool_name')?></td>
    </tr>
    <tr>
        <td><?php echo  $form->input('division',array('options'=>array('juniors'=>'juniors','seniors'=>'seniors','masters'=>'masters','open'=>'open'))) ?></td>
        <td><?php echo  $form->input('category',array('options'=>array(''=>'', 'advanced'=>'advanced','novice'=>'novice'))) ?></td>
    </tr>
    <tr>
        <td> <?php echo  $form->input('shime',array('options'=>array(1=>'YES', 0=>'NO'),'default'=>1)) ?></td>
        <td> <?php echo  $form->input('kansetsu',array('options'=>array(1=>'YES', 0=>'NO' ),'default'=>1)) ?></td>
    </tr>
    <tr>
        <td><?php echo $form->input('sex',array('options'=>array('M'=>'M','F'=>'F', 'A'=>'All'))) ?></td>
        <td><?php echo $form->input('type', array('options'=>
                array(
                    'rr'=>'Round Robin'
                    ,'se'=>'Single Elimination'
                    ,'de'=>'Double Elimination'
                    ,'md'=>'Modified Double'
                    ) ,'default' => $dpt  )
                    ) ?></td>
   </tr>
    <tr>
        <td><?php echo $form->input('min_age') ?></td>
        <td><?php echo $form->input('max_age') ?></td>

    </tr>
    <tr>
        <td><?php echo $form->input('min_weight') ?></td>
        <td><?php echo $form->input('max_weight') ?></td>
    </tr>
    <tr>
        <td colspan="2">
        <?php
            echo $form->submit('Save Changes'); 
        ?>
        
    </tr>


</table>
   </fieldset>
</tr>
</table>
<?php   if(isset($this->data['Pool']['id'])): ?>
<button id='btnLock<?php echo $pid;?>'>Create Matches</button>
<button id='btnRelease<?php echo $pid;?>'>Release</button>
<button id='btnHold<?php echo $pid;?>'>Hold</button>
<button id='btnDelete<?php echo $pid;?>'>Delete</button>

</div>
<?php
     $this->Js->click("#btnLock${pid}" 
        ,$this->Html->url(array( 'action'=>'statusLock',  $this->data['Pool']['id']  ))
        ,array( 'update' => '#msgs')
     );
    $this->Js->click("#btnRelease${pid}" 
        ,$this->Html->url(array( 'action'=>'statusRelease',  $this->data['Pool']['id']  ))
        ,array( 'update' => '#msgs')
     );
    $this->Js->click("#btnHold${pid}" 
        ,$this->Html->url(array( 'action'=>'statusHold',  $this->data['Pool']['id']  ))
        ,array( 'update' => '#msgs')
     );
    $this->Js->click("#btnDelete${pid}" 
        ,$this->Html->url(array( 'action'=>'delete',  $this->data['Pool']['id']  ))
        ,array( 'update' => '#msgs')
     );

?>
<?php endif; ?>
<?php echo  $form->end(); ?>