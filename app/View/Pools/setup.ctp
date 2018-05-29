<?php
    echo $this->Html->script( array('jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.position' ),false );
    echo $this->Html->script( array('jquery.ui.tabs','jquery.ui.accordion','jquery.ui.autocomplete' ),false );
    echo $this->Html->script( array('jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable','jquery.ui.resizable' ,'jquery.ui.dialog'),false );
    $html=$this->Html;
    $form=$this->Form;
?>
<div id="pools_setup" class="pools index" >

<h2><?php __('Pools');?></h2>

<div id="tabs">
     <ul>
        <li><a href="#tabs-1">Actions</a></li>
        <li><a href="#tabs-2">Filter</a></li>
        <li><a href="#tabs-3">Search</a></li>
    </ul>
    <div id="tabs-1">
           <div class='row_form' >
                <table><tr>
                    <td>
                        <?php echo $html->link(__('Add USJF Pools', true), array('action'=>'add_std',  $event_id), null, sprintf(__('Are you sure you want to add standard pools?', true)));
                        ?>
                    </td>
                    <td>
                        <?php echo $html->link(__('Add Tohkon Pools', true), array('action'=>'add_std',  $event_id, 2), null, sprintf(__('Are you sure you want to add standard pools?', true)));
                        ?>
                    </td>
                    <td>
                        <?php echo $html->link(__('Remove Empty Pools', true), array('action'=>'del_empty', $event_id), null, sprintf(__('Are you sure you want to delete the empty pools?', true)));
                        ?>
                    </td>
                    <td>
                        <?php echo $html->link(__('Print Roster', true), array('action'=>'print_roster',  $event_id),array('target'=>'pools'));
                        ?>
                    </td>
                    <td> 
                     <?php  echo $this->Js->link( __( "UnAssigned", true )
                                ,array(  'action' => "unasigned/$event_id"  )
                                ,array(  'update' => '#unassigned'
                                        ,'complete' => "$( '#unassigned' ).dialog( 'open' ); return false;"
                                        )
                                );
                            ?>                       
                    </td>
                    <td>
                        <?php echo $html->link(__('Assign ALL Ready', true), array('action'=>'assignReady', $event_id), null, sprintf(__('Are you sure you want to assign to pools all the competitors that are ready?', true)));
                        ?>
                    </td>
                    <td> <a id='NewPoolLnk' href='#'>New Pool</a></td>
                                            
                    
                </tr></table>
                 <?php   
                   $this->Js->buffer("$('#NewPoolLnk').click( function(){ $('#newpool' ).dialog( 'open' ); } );
                           ");
                 ?>
 
            </div>
        </div>
    <div id="tabs-2">
   <div class='row_form' >
        <fieldset>
        <legend><?php __('Filter');?></legend>

<?php
    extract( $_SESSION['Pool']['filter']);
    echo $form->create('Pool', array(  
            'inputDefaults' => array(
                'style' => 'width:60px;',
                'div' => true
            )
    ));
    echo $form->input('Event.id', array('type'=>'hidden','value'=> $event_id ) );
?>
        <?php echo  $form->input('Pool.division', array('options'=>array(''=>'all' , 'juniors'=>'juniors','seniors'=>'seniors', 'masters'=>'masters', 'open'=>'open'), 'default' => $fdiv)) ?>
        <?php echo  $form->input('Pool.sex', array('options'=>array('A'=>'A','F'=>'F','M'=>'M'), 'default' => $fsex)) ?>
        <?php echo  $form->input('Pool.min_age', array( 'default' => $fmina) ) ?>
        <?php echo  $form->input('Pool.max_age' , array( 'default' => $fmaxa)) ?>
        <?php echo  $form->input('Pool.min_weight' , array( 'default' => $fminw)) ?>
        <?php echo  $form->input('Pool.max_weight' , array( 'default' => $fmaxw)) ?>
        <?php echo  $form->input('shime',array('options'=>array(1=>'YES', 0=>'NO'),'default'=>1)) ?>
        <?php echo  $form->input('kansetsu',array('options'=>array(1=>'YES', 0=>'NO' ),'default'=>1)) ?>
        <?php echo  $form->submit('Apply', array( 'div' => false ) ) ?>
        <?php echo  $form->end() ?>
        </fieldset>
    </div>  </div>
    <div id="tabs-3">
 <div class="row_form" >
     <div id='compsearch'></div>
<?php echo $form->create('Pool', array( 'action' => 'competitors'   ));?>
    <fieldset>
        <legend><?php __('Competitor');?></legend>
    <label>Name</label>
    <?php
            echo $this->Form->input('Competitor.name' , array( 'label' => false, 'style' => 'width:160px;float:none;')) ;
            $action = $this->Html->url( array( 'controller'=>'registrations', 'action' => 'autoCompetitor')) ; 
            $this->Js->buffer( "
                       $( '#CompetitorName' ).autocomplete({
                            minLength:3,
                            source: '$action' ,
                            before:  $('#busy-indicator').fadeIn(),
                            complete: $('#busy-indicator').fadeOut()
                       });
             ");

        echo $this->Js->submit( 'Find', array(
                'update'  => '#compsearch'
                ,'url'  => array('controller'=>'pools' ,'action' => 'competitors')
                ,'div' => false
        ));


    ?>
    </fieldset>
<?php echo $form->end();?>
</div>
    </div>
</div>
<?php
          $this->Js->buffer( " $( '#tabs' ).tabs({ collapsible: true});
            " );

?>
<?php
/*
	$ix = 0;
	echo $form->create('Pool', array( 'controller' => 'pools', 'action'=>'add_group'));
	echo $form->input('Pool.event_id', array('type'=>'hidden','value'=> $event_id) );
	echo $form->input('Pool.id', array('type'=>'hidden'));
*/
?>


<div id='pool_lst' class="pool_lst" style="float:left;width:100px;border-right:1px solid;">
<?php include "pool_lst_open.ctp"; ?>
</div>
<div id='pools' style="border-right:1px solid;">
</div>

<div id="unassigned" title="Unassigned Competitors"></div>
<?php 
    $url = $this->Html->url(array(  'action' => "unasigned/$event_id"  ) ) ;

    $this->Js->buffer("
        $('#unassigned').dialog({autoOpen: false});  
        $('#unassigned').bind( 'refresh', function( event ){
            $.ajax( {
                 url: '$url'
                 ,cache: false
                 ,data: {}
                 ,success: function( data){
                     $('#unassigned').html( data )
                 }
             });  
        });

"); ?>


<div id='newpool'  title="Create new pool">
<?php include "form_fields.ctp";?>
</div>
<?php $this->Js->buffer("$( '#newpool' ).dialog({autoOpen: false});  "); ?>

<?php 
    //echo  $form->end();
    echo $this->Js->writeBuffer();
?>
</div>


