<?php
    $class = null;
    $html= $this->Html;
    $form=$this->Form;
    $ajax=$this->Js;
    $m = $mat['Mat'] ;
    $mid = $m['id'];
    $container = "mat_$mid";
  ?>
<div class="float_right">
<?php echo $html->image('iconEdit.png',
            array(
                'alt'   => 'edit',
                'align' => 'right',
                'id'    =>  'imgEdit'.$mid
            ))?>
<?php echo $html->image('iconRun.png',
            array(
                'alt'   => 'release',
                'align' => 'right',
                'id'    => 'imgRelease'.$mid
            ))?>
<?php echo $html->image('iconReload.png',
            array(
                'alt'   => 'reload',
                'align' => 'right',
                'id'    => 'imgReload'.$mid
            ))?>
</div>
<div id='mat_cont<?php echo $mid;?>'>
    
<?php
   echo $this->Html->link( $m['name'] ,array( 'action' => 'view' , $mid ));
?>

<div class="menu_item">
<?php 
        echo  $ajax->link( 'refresh',
                array( 'controller' => 'mats', 'action' => 'mat',  $mid),
                array( 'update'  => '#mat_' . $mid )
        );
        ?>
</div>
<div class="menu_item">
        <?php
        $stat = $m['mat_status'];
        if( $stat == 0 ){
          echo $ajax->link( 'Release',
                array( 'controller' => 'mats', 'action' => 'statusChange/'. $mid . '/1'),
                array( 'update'  => '#mat_' . $mid )
        );
            
        }else{
          echo $ajax->link( 'Hold',
                array( 'controller' => 'mats', 'action' => 'statusChange/'. $mid . '/0'),
                array( 'update'  => '#mat_' . $mid )
        );
            
        }
        ?>
      </div>
      
      <table>
       <tr>
            <th colspan="1">Pool</th>      
            <th>Age</th>       
            <th>Competitors</th>       
            <th>Matches</th>
            <th>Complete</th>
            <th>Remain</th>
            <th colspan="3">Order</th>     
        </tr>
        
      <?php 
        $mt = 0;
        $mc = 0;
        $rt = 0;
        $j=0;
        $m_time = 0;
        if( isset($mat['Pool'])):
        foreach( $mat['Pool'] as $pool ):
    
        $class = null;
        if ($j++ % 2 == 0) {
            $class = ' class="altrow"';
        }
        //debug( $pool ); exit(0);
        $pm = $pool['match_count'] ; //count ( $pool['Match'] ) ;
        $pc = $pool['completed_count'] ; //count ( $pool['MatchComplete'] ) ;
        $pr=  $pool['registration_count'] ; // count( $pool['Registration'] );
        $mt += $pm  ;
        $mc += $pm - $pc   ;
        $rt += $pr;
        $m_time +=  ($pm-$pc) * $pool['match_duration'];
        
        ?>
      <tr <?php echo $class;?>>
        <td>
        <div id='<?php echo $pool['id'];?>'  class='Draggable' container_id='<?php echo $container?>'> <?php echo  $html->link( $pool['pool_name'] .'/'. $pool['division'] .'/'. $pool['category'], array('controller'=> 'pools', 'action'=>'view', $pool['id']) ) ?></div></td>
        <td><?php echo $pool['min_age'] . ' to ' . $pool['max_age'];?></td>
        <td><?php echo $pr ;?></td>
        <td><?php echo $pm ;?></td>
        <td><?php echo $pc ;?></td>
        <td><?php echo $pm - $pc;?></td>
        <td><?php echo $pool['qnum'] ;?></td>
        <td>
        <?php echo  $ajax->link('[+]', array('controller'=> 'mats', 'action'=>'poolMove', $pool['id']), array('update' => '#mat_'. $mid) ) ?>
        </td>
        <td>
        <?php echo  $ajax->link('[-]', array('controller'=> 'mats', 'action'=>'poolMove', $pool['id'], -1 ) , array('update' => '#mat_'. $mid)) ?>
        </td>
        
      </tr>
        <?php
            echo $ajax->drag( $pool['id'], array('revert'=> false) );
            endforeach; 
            endif;
            
        ?>
      

      <tr>
        <td colspan="2"><b>Mat totals:</b></td>
        <td><b><?php echo $rt;?></b></td>
        <td><b><?php echo $mt;?></b></td>
        <td><b><?php echo $mt -$mc;?></b></td>
        <td><b><?php echo $mc;?></b></td>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5">&nbsp;</td>
        <td colspan='1'><?php  echo round( $m_time / 60, 1) . " hours"?></td>
        <td colspan="3">&nbsp;</td>
      </tr>
      </table>

</div>
<?php

 
    $url = $this->Html->url( array( 'action' => 'edit' , $mid ));
    $this->Js->buffer("
       $('#imgReload${mid}').click( function(){ $('#mat_$mid' ).trigger('refresh');});
        $('#imgEdit${mid}').click( function(){
             $.ajax({
                    url: '$url',
                    cache: false,
                    method: 'GET',
                    data: { },
                    success: function( data ){
                        $('#mat_cont$mid' ).html( data );
                        }
                })
        });
    ");
    echo $this->Js->writeBuffer();
?>