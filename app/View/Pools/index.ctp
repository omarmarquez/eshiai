
<div class="pools index">
 <h2>Pools</h2> <div class="actions">
    <ul>
        <li><?php echo $this->Html->link(__('Pool Setup'), array('action' => 'setup')); ?></li>
    </ul>
</div>

<table cellpadding="0" cellspacing="0">
    <tr>
        <?php foreach ($fields as $field): ?>
            <th><?php echo $this->Paginator->sort(  $field );?></th>
        <?php endforeach; ?>

    </tr>
    <?php
    foreach ($listing as $pool): ?>
    <tr>
       <?php foreach ($fields as $field): ?>

        <td><?php echo preg_match('/name/' , $field)? $this->Html->link(__( $pool['Pool'][ $field ] ), array('action' => 'view', $pool['Pool']['id'])):$pool['Pool'][ $field ]; ?>&nbsp;</td>
       <?php endforeach; ?>


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

<?php // debug($this->Paginator )  ; ?>
