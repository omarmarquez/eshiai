<?php 
    $class = null;
    $html= $this->Html;
    $form=$this->Form;
    $ajax=$this->Js;
    $mid =$mat['Mat']['id'];
 ?>

<h2>Deck for <?php echo 'Mat &nbsp;' . $mat['Mat']['name'] . ' ::' .$mat['Mat']['location'] ?>
</h2>
<div class="actions">
  <table>  <tr>
        <td><?php echo  $html->link(__('Score Board', true), array('action'=>'board', $mat['Mat']['id'] )); ?></td>
        <td><?php echo  $html->link(__('Mat Deck', true), array('action'=>'deck', $mat['Mat']['id'] )); ?> </td>
 </tr></table>
</div>
<table><tr>
<td>
  <div id="mat_n<?php echo $mid;?>">
  <table>
      <?php foreach($mat['Pool'] as $pool ){?>
      <tr>
         <td><?php
                echo  $ajax->link(__( $pool['division'] .' ' . $pool['pool_name']  .' ' . $pool['category'], true) . ' (' . $pool['type'] . ')',
                array('controller'=> 'pools', 'action'=>'view', $pool['id'],'view_matches'
                ,'update' => '#pool_' . $pool['id']   )
                ); ?>
        </td>
        <td><?php
                echo  $ajax->link('[up]', array('controller'=> 'mats', 'action'=>'poolMove', $pool['id'], -1 ) , array('update' => '#mat_'. $mid, 'success' => 'window.location.reload();' )) ?>
        </td>
        <td><?php
                echo  $ajax->link('[down]', array('controller'=> 'mats', 'action'=>'poolMove', $pool['id']), array('update' => '#mat_'. $mid , 'success' => 'window.location.reload();' )) ?>
        </td>
        <td><?php
                echo $this->Html->link( $this->Html->image('pdf_icon.png'), array('controller'=> 'pools', 'action'=>'pdf', $pool['id']), array('target'=>'pool','escape' => FALSE)); ?>
        </td>
      </tr>
        <?php } ?>
        </table>
        </div>
</td>
<td>
<div id="run_queue" class="match_queue" align="right">
<?php include('run_queue.ctp'); ?>
</div>
</td>
</tr></table>
<div style="clear:both;">&nbsp;</div>
<?php   //debug($mat);?>

<?php if(!empty($mat['Pool'])): ?>

      <?php foreach($mat['Pool'] as $pool ):?>
      <h3>           
        <?php echo  $ajax->link(__( $pool['division'] .' ' . $pool['pool_name']  .' ' . $pool['category'], true) . ' (' . $pool['type'] . ')', 
                array('controller'=> 'pools', 'action'=>'view', $pool['id'],'view_matches'
                ,'update' => '#pool_' . $pool['id']   )
                ); 
		echo $this->Html->link( $this->Html->image('pdf_icon.png'), array('controller'=> 'pools', 'action'=>'pdf', $pool['id']), array('target'=>'pool','escape' => FALSE));	
		?>
      </h3>
      <div id="pool_<?php echo $pool['id'];?>"></div>
      <script type="text/javascript">
            <?php echo $ajax->request(                       
                                array( 'controller'=>'pools','action'=>'view' , $pool['id'] ,'view_matches' )
                                ,array( 'update'=>'#pool_' . $pool['id'] )
                                //,'complete' =>'document.sync_data()'
            )?>
      </script>
    
    <?php  endforeach; ?>
<?php endif; ?>
  
	
 <div id="chk_refresh"></div>
 <?php  
echo $ajax->remoteTimer(
    array(
    'url' => array( 'controller' => 'mats', 'action' => 'chkRefresh',$mat['Mat']['id'] ,$mat['Mat']['current_match_id'] ),
    'update'    => '#chk_refresh'
    ,'frequency' => 15000
//  ,'complete' => ''
//  ,'condition'=> '(rc++ < 2)'
    )
);
// debug($mat); 
?>
