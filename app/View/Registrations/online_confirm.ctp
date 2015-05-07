<?php 
    $session = $this->Session;
    $form = $this->Form;
    $html = $this->Html;
    $ajax = $this->Js;

    echo $session->flash(); 

 ?>
<h3><?php echo __( $event_info['event_name']); ?></h3>
<hr>
     <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Date:' ); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo trim( $event_info['event_date'] );?>
            &nbsp;
        </dd>
    </dl>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Address:' ); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo trim( $event_info['event_address'] );?>
            &nbsp;
        </dd>
    </dl>

<h3><?php echo __( 'Competitor: '. $comp_info['first_name'] . " " . $comp_info['last_name']); ?></h3>
<hr>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Address:' ); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo trim( $comp_info['comp_address'] . " " . $comp_info['comp_city'] . " " . $comp_info['comp_state'] . " " . $comp_info['comp_zip']);?>
            &nbsp;
        </dd>
    </dl>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Phone:' ); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo trim( $comp_info['comp_phone'] );?>
            &nbsp;
        </dd>
    </dl>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('EMail:' ); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo trim( $comp_info['email']  );?>
            &nbsp;
        </dd>
    </dl>
   <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Club:' ); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo trim( $club_info['Club']['club_name'] . ' ' .  $club_info['Club']['club_city'] . ' ' .  $club_info['Club']['club_state']);?>
            &nbsp;
        </dd>
    </dl>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Card Type:' ); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo trim( $reg_info['card_type']  );?>
            &nbsp;
        </dd>
    </dl>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Card Number:' ); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo trim( $reg_info['card_number']  );?>
            &nbsp;
        </dd>
    </dl>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Rank:' ); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo trim( $reg_info['rank']  );?>
            &nbsp;
        </dd>
    </dl>


<?php

setlocale(LC_MONETARY, 'en_US');

?>
<h3><?php echo  __('Registration Information'); ?></h3>
<hr>

 <?php
    $prc = $event_info['reg_price'];
    $tprc = 0;
    foreach( $reg_info['cats'] as $reg ):
    $ar = 0;

?>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>>
        <?php echo trim( $reg['division']  ) . ' '. __('division' ); ?>
        <?php
            if( $reg['upAge'] =='Y' ) echo __( ', Up in Age');
            if( $reg['upSkill'] =='Y' ) echo __( ', Up in Skill');
            if( $reg['upWeight'] =='Y' ) echo __( ', Up in Weight');
         ?>
        </dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo money_format('%i', $prc );?>
            &nbsp;
        </dd>
    </dl>

<?php
    $tprc += $prc;
    $prc = $event_info['add_reg_price'];
      endforeach;
  ?>

      <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?><hr><?php echo __('Total:' ); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
           <b> <?php echo money_format('%i', $tprc);?></b>
            &nbsp;

        </dd>
    </dl>

 
<div id='billing'>
 <?php

            echo $ajax->submit( 'go back',
                 array(
                     'url' => array( 'action' => 'online2/'.$event_id )
                     ,'update'  => '#online2'
                    )
                );
                
      if( $event_info['paypal_int'] == 1 ):
 ?>
 <div>
     <table style='width:620px'>
             <tr>
                <td colspan=2><h3><?php echo  __('Billing'); ?></h3></td>
            </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="checkbox" id="bill_same">Same as above.</td>
                </tr>
          <tr>
                <td><?php echo $form->input('User.first_name', array('class'  => 'required')) ?></td>
                <td><?php echo $form->input('User.last_name', array('class'  => 'required')) ?></td>
             </tr>
           <tr>
                <td><?php echo $form->input('User.billing_address', array('class'  => 'required')) ?></td>
                <td><?php echo $form->input('User.billing_address2', array()) ?></td>
             </tr>
          <tr>
                <td colspan=2><?php echo $form->input('User.email_address', array('class'  => 'email')) ?></td>
             </tr>
           <tr>
                <td><?php echo $form->input('User.billing_city', array('class'  => 'required')) ?></td>
                <td><?php echo $form->input('User.billing_state', array('class'  => 'required')) ?></td>
             </tr>
           <tr>
                <td><?php echo $form->input('User.billing_zip', array('class'  => 'required')) ?></td>
                <td><?php echo $form->input('User.billing_country', array('class'  => 'required')) ?></td>
             </tr>
            <tr>
                <td colspan=2><hr /><h3><?php echo  __('Credit Card'); ?></h3>
</td>
            </tr>
           <tr>
                <td><?php echo $form->input('User.credit_type', array('default'=>'', 'options' => array( 
                            ''=>''
                            , 'MasterCard'=>'MasterCard'
                            , 'Visa' => 'Visa'
                            , 'Discover' => 'Discover'
                            ,'American Express' => 'Amex'
                            ))) ?></td>
                <td><?php echo $form->input('User.card_number', array('class'  => 'creditcard')) ?></td>
             </tr>
           <tr>
                <td><?php echo $this->Form->input('User.exp_date', array('type' => 'date',
                                'label' => 'Expiration Date',
                                   'dateFormat' => 'MY',
                                    'minYear' => date('Y') ,
                                    'maxYear' => date('Y') + 15,
                            ));?>
                </td>
                <td><?php echo $form->input('User.cv_code', array()) ?></td>
             </tr>
        </table>

 </div>
 <?php endif; ?>
 <div style='float:left;'>
 <?php
       echo $ajax->submit( 'Register',
                 array(                     
                     'url' => array( 'action' => 'online_confirm2/'. $event_id )
                     ,'update'  => '#confirm',
                     'evalScripts' => true,
                     'before' => $this->Js->get('#pay-indicator')->effect('fadeIn', array('buffer' => false)),
                     'complete' => $this->Js->get('#pay-indicator')->effect('fadeOut', array('buffer' => false)),
                     )
                );
 ?>
 </div>
 <div  id="paying-indicator" >
              <?php echo $this->Html->image('ajax-loader.gif', array('id' => 'pay-indicator')); ?>
 </div>
<?php
        $this->Js->buffer("
        $('#pay-indicator').fadeOut();
             
        $('#RegForm').validate();
        
        $('#bill_same').click(function(){
           $('#UserFirstName').val( $('#CompetitorFirstName').val() ); 
           $('#UserLastName').val( $('#CompetitorLastName').val() ); 
           $('#UserBillingAddress').val('" .$comp_info['comp_address'] . "'); 
           $('#UserEmailAddress').val('" .$comp_info['email'] . "'); 
           $('#UserBillingCity').val('" .$comp_info['comp_city'] . "'); 
           $('#UserBillingState').val('" .$comp_info['comp_state'] . "'); 
           $('#UserBillingZip').val('" .$comp_info['comp_zip'] . "'); 
         });");
 ?>

</div>
<div id='confirm'></div>
<?php  echo $this->Js->writeBuffer(); // Write cached scripts  ?>
