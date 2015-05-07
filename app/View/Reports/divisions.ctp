<div class="clubs index">
<h2><?php echo __('Divisions Report');?></h2>
<?php 
    $html = $this->Html;
            //debug($_SESSION);
    
    $event_title = $_SESSION['Event']['event_name'];
            // debug($eid)
?>

<table cellpadding="0" cellspacing="0">
<tr>
    <th colspan=2><?php echo ('division');?></th>
     <th><?php echo ('Competitors');?></th>
    <th><?php echo ( 'Club');?></th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
</tr>
<?php
$i = 0;
foreach ($records as $record):
    if( count( $record['Registration']) == 0 )
        continue;
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
?>
    <tr<?php echo $class;?>>
        <td>
            <?php echo $html->link(__($record['Pool']['pool_name'], true), array('controller'=>'pools', 'action'=>'view', $record['Pool']['id'])); ?>
 
        </td>
         <td>
           <?php echo  $record['Pool']['division'] .'&nbsp;'.$record['Pool']['sex'] .'&nbsp;'.$record['Pool']['category'] .'&nbsp;'.$record['Pool']['max_weight'] .'#'?>
        </td>
       <td>
            <?php echo count( $record['Registration'] );  ?>
        </td>
        <th>&nbsp;</th>
        <th><?php echo 'wins';?></th>
        <th><?php echo  'loses'; ?></th>
        <th><?php echo  'place';?></th>
        
    </tr>

    <?php  
        foreach( $record['Registration'] as $reg ):
            $comp=$reg['Competitor'];
            $club=$comp['Club'];
            
            $name = $comp['first_name'] . '&nbsp;' . $comp['last_name'];

       ?>
    <tr <?php echo $class;?>>
        <td colspan="2">&nbsp;</td>
        <td colspan="1"><?php echo $name?> </td>        
        <td colspan="1">
        <?php echo $html->link(__($club['club_name'], true), array('controller'=>'clubs','action'=>'view', $club['id'])); ?>
        </td>        
        <td colspan="1"><?php echo $reg['match_wins'] ?> </td>
        <td colspan="1"><?php echo $reg['match_loses'] ?> </td>
        <td colspan="1"><?php echo $reg['bracket_pos']  ?> </td>
    </tr>
    <?php 
        $name = "&nbsp;";
        endforeach;
     ?>
        
    

<?php endforeach; ?>
</table>
</div>

 
<?php //debug($records);?>
