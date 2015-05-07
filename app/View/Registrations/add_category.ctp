<div id="cat_<?php echo ++$cat_id;?>">
<div style='float:left;margin-left:30px;'>
<?php echo $this->Form->input("Registration.cats.${cat_id}.division" ,array('value'  => $reg['division'], 'options'=>array( 'juniors'=>'juniors','seniors'=>'seniors','masters'=>'masters','open'=>'open')));?>
</div>
<div style='float:left;margin-left:30px;'>
<?php echo $this->Form->input("Registration.cats.${cat_id}.upSkill" ,array( 'options'=> array('N'=>'N','Y'=>'Y'), 'div' => false)) ?>
</div>
<div style='float:left;margin-left:30px;'>
<?php echo $this->Form->input("Registration.cats.${cat_id}.upWeight" ,array( 'options'=> array('N'=>'N','Y'=>'Y'), 'div' => false)) ?>
</div>
<div style='float:left;margin-left:30px;'>
 <?php echo $this->Form->input("Registration.cats.${cat_id}.upAge" ,array( 'options'=> array('N'=>'N','Y'=>'Y'), 'div' => false)) ?>
</div>
<div style='float:left;margin-left:30px;'>
 <?php echo $this->Form->input("Remove" ,array( 'type' => 'button' , "onclick" => "$('#cat_${cat_id}').remove(); return false;", 'div' => false, 'label' =>'')) ?>
</div>
<hr style='clear:both;'>
</div>

