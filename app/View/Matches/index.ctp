<div class="events index">
    <h2><?php echo __('Matches');?></h2>
    <table cellpadding="0" cellspacing="0">
    <tr>
        <?php foreach ($pool_fields as $field): ?>
            <th><?php echo $this->Paginator->sort(  $field );?></th>
        <?php endforeach; ?>
            <th>Competitor&nbsp;1</th>
            <th>Competitor&nbsp;2</th>
       <?php foreach ($fields as $field): ?>
            <th><?php echo $this->Paginator->sort(  $field );?></th>
        <?php endforeach; ?>

    </tr>
    <?php
    foreach ($listing as $Match): 
        
        $p0 = isset( $Match['Player'][0])?$Match['Player'][0]['Registration']:null;
        $p1 = isset( $Match['Player'][1])?$Match['Player'][1]['Registration']:null;
        
        $c0 = $c1 = '';
        if( $p0 )            
            $c0 = $p0['club_abbr'] .':&nbsp;'. $p0['Competitor']['first_name'] .'&nbsp;' . $p0['Competitor']['last_name'];
       if( $p1 )            
            $c1 = $p1['club_abbr'] .':&nbsp;'. $p1['Competitor']['first_name'] .'&nbsp;' . $p1['Competitor']['last_name'];
        
    ?>
    <tr>
       <?php foreach ($pool_fields as $field): ?>
            <td><?php echo preg_match('/name/' , $field)? $this->Html->link(__( $Match['Pool'][ $field ] ), array('controller'=>'pools','action' => 'view', $Match['Pool']['id'])):$Match['Pool'][ $field ]; ?>&nbsp;</td>
       <?php endforeach; ?>
       <td><?php echo $c0 ?></td>
       <td><?php echo $c1 ?></td>
       <?php foreach ($fields as $field): ?>
            <td><?php echo preg_match('/name/' , $field)? $this->Html->link(__( $Match['Match'][ $field ] ), array('action' => 'open', $Match['Match']['id'])):$Match['Match'][ $field ]; ?>&nbsp;</td>
       <?php endforeach; ?>
       <td>
           &nbsp;<?php  echo $this->Html->link(__( 'board' ), array('controller'=> 'mats', 'action' => 'boardMatch', $Match['Match']['id'])) ?>
           &nbsp;<?php  if( $Match['Match']['status'] == 1 || $Match['Match']['status']==2) echo $this->Html->link(__( 'skip' ), array('controller'=> 'matches', 'action' => 'skip', $Match['Match']['id']),array('confirm' =>'Skip this match')) ?>
        </td> 
    </tr>
<?php endforeach; ?>
    </table>
    <p>
    <?php
    echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
    ));
    ?>  </p>

    <div class="paging">
    <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
    ?>
    </div>
</div>

<?php //debug($Match);// debug($this->Paginator )  ; ?>
