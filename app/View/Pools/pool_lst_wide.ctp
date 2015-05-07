<?php
    $html=$this->Html;
    $ajax=$this->Js;
?>
<?php if( !empty($pools)):?>
    
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Category Name'); ?></th>
        <th><?php __('Sex'); ?></th>
        <th><?php __('Division'); ?></th>
        <th><?php __('Category'); ?></th>
        <th><?php __('Age'); ?></th>
        <th><?php __('Weight'); ?></th>
        <th><?php __('Match Duration'); ?></th>
        <th><?php __('Status'); ?></th>
        <th><?php __('Type'); ?></th>
        <th><?php __('Mat'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        $rtotal=0;
        foreach ($pools as $p):
            $pool=$p['Pool'];
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
    
        <tr<?php echo $class;?>>
            <td><?php echo $html->link(__( $pool['pool_name'], true), array('controller'=> 'pools', 'action'=>'view', $pool['id'])); ?></td>
            <td><?php echo $pool['sex'];?></td>
            <td><?php echo $pool['division'];?></td>
            <td><?php echo $pool['category'];?></td>
            <td><?php echo $pool['min_age'] . " - " . $pool['max_age'];?></td>
            <td><?php echo $pool['min_weight'] . " - " . $pool['max_weight'];?></td>
            <td><?php echo $pool['match_duration'];?></td>
            <td><?php echo $p['PoolStatus']['status'];?></td>
            <td><?php echo $pool['type'];?></td>
            <td><?php echo $p['Mat']['name'];?></td>
            <td class="actions">
                <?php if( $pool['status'] ) echo $html->link( $this->Html->image('pdf_icon.png'), array('controller'=> 'pools', 'action'=>'pdf', $pool['id']), array('target'=>'pool', 'escape' => FALSE)); ?>
                <?php if( $pool['status'] ) 
                    echo $ajax->link( __('approve', true)
                        , array('controller'=> 'pools', 'action'=>'statusReviewed',$pool['event_id'], $pool['id'], $status) 
                        , array( 'update' =>  $container 
                        , 'confirm' => "Approve '" . $pool['pool_name'] . "'?" )
                ); ?>
            </td>
        </tr>
    <?php endforeach; ?>

    </table>
<?php endif;?>

<?php  echo $this->Js->writeBuffer(); // Write cached scripts ?>
