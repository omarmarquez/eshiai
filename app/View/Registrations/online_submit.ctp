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
<div><?php echo $badd;?>
</div>


</div>
<div id='confirm'></div>
<?php  echo $this->Js->writeBuffer(); // Write cached scripts  ?>
