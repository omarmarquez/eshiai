<?php
    echo $this->Html->script( array('jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.position' ),false );
    echo $this->Html->script( array('jquery.ui.tabs','jquery.ui.accordion','jquery.ui.autocomplete' ),false );
    echo $this->Html->script( array('jquery.ui.mouse','jquery.ui.draggable','jquery.ui.droppable','jquery.ui.resizable' ,'jquery.ui.dialog'),false );
    $html=$this->Html;
    $form=$this->Form;
    $ajax=$this->Js;
?>
<div>
   <a href='#' id='newMatLnk'><?php echo __("New Mat");?></a>
</div>
<div id='newMat'  title="Create new Mat">
<?php 
    echo $this->Form->create('Mat', array( 'action' => 'add'));
    echo $this->Form->input('name', array('size'=>30 )); 
    echo $this->Form->end(__('Submit'));
?>
</div>
<?php
    $this->Js->buffer("$( '#newMat' ).dialog({autoOpen: false});
     ");
    $this->Js->buffer("$('#newMatLnk').click( function(){ $( '#newMat' ).dialog( 'open' ); });
    ");
?>
<div style="clear:both;">&nbsp;</div>

<table>
    <tr><td width="20%">
 
    <h3><?php echo $ajax->link( __('Ready pools ', true)
                    , array('controller'=>'pools', 'action'=>'poolLst' ,7 )
                    , array( 'update' => '#pools')
        );?></h3>
<div id="pools" class="pools index" style="float:left;width:140px;" ></div>
<?php 
       echo $ajax->bind( 'pools'  , 'refresh'
            ,  array( 'controller'=>'pools', 'action'=>'poolLst' , 7)      
        );
   $ajax->remoteTimer(
    array(
    'url' => $html->url( array('controller'=>'pools', 'action'=>'poolLst', 7 ))
    ,'update' => '#pools'
    ,'frequency' => 30000
    //,'count'   => 3
    )
);
?>

</td>
<td>    

<div style="float:left" class="mats">

	<?php
	   foreach ($listing as $mat): 
	       $mid = $mat['Mat']['id'];
	 ?>
	
    <div id="mat_<?php echo $mid ?>" class="mat" ></div>
	<?php
	     $ajax->remoteTimer(
            array(
                'url' => $html->url( array( 'action'=>'mat',  $mid)),
                'update' => '#mat_'.$mid,
                'frequency' => 25000
                //,'count' => 2
            ));
        
         
        echo $ajax->dropPool( 'mat_' . $mid
            , $html->url( array('controller'=>'mats', 'action'=>'loadPool' , $mid ))      
        );
       echo $ajax->bind( 'mat_' . $mid, 'refresh'
            ,  array(  'action'=>'mat' , $mid )      
        );
     
    endforeach; 
    ?>
   </div>
    </td></tr>
    
</table>
<div style="clear:both;">&nbsp;</div>


    <h3><?php echo $ajax->link( __('Contested pools ', true)
                    , array('controller'=>'pools', 'action'=>'poolLst', 9 ,'pool_lst_wide','#pools_contested')
                    , array( 'update' => '#pools_contested')
        );?></h3>
    <div id='pools_contested'></div>
     <?php 
     echo  $ajax->remoteTimer(
    array(
        'url' => array('controller'=>'pools', 'action'=>'poolLst', 9 ,'pool_lst_wide', '#pools_contested'),
        'update' => '#pools_contested',
        'frequency' => 30000
            )
        );  
    ?>

    <h3><?php echo $ajax->link( __('Completed pools ', true)
                    , array('controller'=>'pools', 'action'=>'poolLst' ,5 ,'pool_lst_wide','#pools_completed')
                    , array( 'update' => '#pools_completed')
        );?></h3>
   <div id='pools_completed'></div>
     <?php 
     echo  $ajax->remoteTimer(
    array(
        'url' => array('controller'=>'pools', 'action'=>'poolLst', 5,'pool_lst_wide','#pools_completed' ),
        'update' => '#pools_completed',
        'frequency' => 30000
            )
        );  
    ?>

<?php 
 echo $this->Js->writeBuffer();
?>
