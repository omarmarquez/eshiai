<table><tr><td>
<div class="Competitor" style="float:left;width:40%;">
<h2><?php  echo  $record['Competitor']['first_name'] . '&nbsp;' . $record['Competitor']['last_name'] ;?>
	&nbsp;<?php echo $this->Html->link( 'change', array( 'controller' => 'registrations', 'action' => 'online', $event_id ))?>
</h2>
	<dl>
		<dt><?php echo __('Number'); ?></dt>
		<dd>
			<?php echo h($record['Competitor']['id']); ?>
			&nbsp;
		</dd>

		<dt><?php echo __('Competitor DOB'); ?></dt>
		<dd>
			<?php echo h($record['Competitor']['comp_dob']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('First Name'); ?></dt>
		<dd>
			<?php echo h($record['Competitor']['first_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Name'); ?></dt>
		<dd>
			<?php echo h($record['Competitor']['last_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sex'); ?></dt>
		<dd>
			<?php echo h($record['Competitor']['comp_sex']); ?>
			&nbsp;
		</dd>

	</dl>

	<div id="registrations">
<h2><?php echo __('Last Registrations');?></h2>


	<table cellpadding="0" cellspacing="0">
	<tr>
			<th> Event </th>
			<th> Club </th>
			<th> type</th>	
			<th> division </th>
			<th> age </th>
			<th> weight </th>
			<th> rank </th>
			<th> modified </th>
			<th colspan=3> extra </th>
			<th> approved </th>
			<th> pool </th>
		</tr>
	<?php
	foreach ($record['Registration'] as $r): 
	
		$registration= array( 'Registration' => $r );
		//debug($registration);
	?>
	<tr>

         <td>
            <?php
             

                    echo $this->Html->link($registration['Registration']['Event']['event_name'] .' ' .$registration['Registration']['Event']['event_date']
                        , array('controller'=> 'clubs', 'action'=>'view', $registration['Registration']['Event']['id']));

              
                ?>
        </td>
        <td>
            <?php echo $registration['Registration']['club_abbr']; ?>
        </td>
                <td>
            <?php echo $registration['Registration']['rtype']; ?>
        </td>
                <td>
            <?php echo $registration['Registration']['division']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['age']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['weight']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['rank']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['modified']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['upSkill']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['upWeight']; ?>
        </td>
        <td>
            <?php echo $registration['Registration']['upAge']; ?>
        </td>
        <td>
            <?php echo $this->Html->image( $registration['Registration']['approved']?'flag_green.gif':'flag_red.gif'); ?>
        </td>
        <td>
           <?php
            if( isset( $registration['Registration']['Pool']['pool_name'])){
             echo $this->Html->link($registration['Registration']['Pool']['pool_name'], array('controller'=> 'pools', 'action'=>'view', $registration['Registration']['Pool']['id'])); 
			}?>
        </td>		
		
	</tr>
<?php endforeach; ?>
	</table>	
	
	</div>
</div>
</td><td>
<div style="float:left;width:80%;">
	<div class="actions">
    <h3><?php echo __('New Registration'); ?></h3>
    <?php echo $badd;?>
    <div style="height: 20px">&nbsp;</div>
	<?php echo $bcart;?>
	</div>
</div>
</td>
	<td>
		<h2>&nbsp;</h2>
	</td>
</tr></table>


