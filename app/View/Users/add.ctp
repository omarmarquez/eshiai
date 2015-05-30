<!-- app/View/Users/add.ctp -->
<div class="users form">
<?php echo $this->Form->create('User');?>
    <fieldset>
        <legend><?php echo __('Add User'); ?></legend>
    <?php
        echo $this->Form->input('username');
        echo $this->Form->input('password');
        echo $this->Form->input('verify_password', array('type'=>'password', 'value'=> $this->Form->value('User.password')));
        echo $this->Form->input('role', array(
            'options' => array('admin' => 'Admin', 'user' => 'User' , 'weights'=>'Weights')
        ));
        echo $this->Form->input('event_id');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
