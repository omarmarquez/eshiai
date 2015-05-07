<?php 
$html=$this->Html; 
$ajax = $this->Js;
	$comps = array( 0 => 'BYE');
	foreach( $pool['Registration'] as $reg){
		$comps[ $reg['id'] ] = $reg['club_abbr'].':'.$reg['Competitor']['first_name'].'&nbsp;'.$reg['Competitor']['last_name'];
	}
    $status=array( 0 => 'N/A', 1 => 'R', 2 => 'C', 3 => '?', 4=> 'C');
?>
<div id="award_dialog" title="Award Match"></div>
<?php     $this->Js->buffer("$('#award_dialog').dialog({autoOpen: false});  ");
?>
<div id='matches'>
	<table>
	<tr>
	<?php
		$i = 0;
		$r = 0;
		foreach ($pool['Match'] as $match):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		
		if( $match['round'] != $r ){
			if( $r ) echo "</td>";
			echo "<td>";
			$r = $match['round'] ;
			echo "round:$r";
		}
		$p1 = $p2 = '';
		
		foreach( $match['Player'] as $p ){
			$c = array( 'first_name' => 'BYE', 'last_name' => '', 'Club' =>array( 'club_abbr' => '') );
			if( isset( $p['Registration']['Competitor']))
				$c = $p['Registration']['Competitor'];
			$w = '';
			if( $p['win_lose']){
				$w = '(' .$p['by'] . ')';
			}
			if( $p['pos'] == 1 && isset( $comps[ $p['registration_id'] ] )) {
			//	$p1 = $p['Registration']['club_abbr'] .':: ' . $c['first_name'] . ' ' . $c['last_name'] . $w;
				$p1 = $comps[ $p['registration_id'] ] .' ' . $w;
			}
			if( $p['pos'] == 2 && isset( $comps[ $p['registration_id'] ] )) {
			//	$p1 = $p['Registration']['club_abbr'] .':: ' . $c['first_name'] . ' ' . $c['last_name'] . $w;
				$p2 = $comps[ $p['registration_id'] ].' ' . $w;
			}
		}
	?>
		<table cellspacing="2" cellpadding="2" >
			<tr><th bgcolor="#CCC">
					M:<?php echo $match['id'];?> &nbsp;S:<?php echo $status[ $match['status']].$match['skip'];?> &nbsp; match&nbsp;<?php echo $match['match_num'];?> 
					<div style="float:right">
						<?php
						  if(  $match['status'] == 2 || $match['status'] == 4 )
							echo  $html->link( 'award', array( 'controller' => 'matches', 'action' => 'award', $match['id'] ),null,'Award this match?' );
						 if( $match['status'] == 1 )  
							echo  $ajax->link( 'rush', array( 'controller' => 'mats', 'action' => 'put_at_top/' .  $match['mat_id'] .'/'. $match['id'] ),null,'Rush this match?' );
						
							if( $pool['Pool']['type'] == 'rr' && $match['status'] == 2):
							echo  $html->link( 'skip', array( 'controller' => 'matches', 'action' => 'skip', $match['id'] ),null,'Skip this match?' );
							endif;
						?>					
					</div>	
			</th></tr>
			<!-- <tr><td>id <?php echo $match['id']?></td></tr>-->
			<tr id="p1_<?php echo $match['match_num']?>" class="white_row"><td>&nbsp;<?php echo  $p1?></td></tr>
			<tr id="p2_<?php echo $match['match_num']?>" class="blue_row"><td>&nbsp;<?php echo  $p2?></td></tr>
			</table>

	<?php 
		if( isset($poolView) && ( $r == 1 || $pool['Pool']['type'] == 'rr')){
			
				echo $ajax->dropRemote( 'p1_' . $match['match_num'],
				array('hoverclass' => 'Droppable'),
				array( 
					'url' => array('controller'=>'pools', 'action'=>'set_match_player'
					,  'id' => $pool['Pool']['id'] 
					,  'match' => $match['id'] 
					,  'pos' => 1 
					)
					,'with'=>'{draggedid:element.id}'
					,'update' => 'matches'
					)
			);
				echo $ajax->dropRemote( 'p2_' . $match['match_num'],
				array('hoverclass' => 'Droppable'),
				array( 
					'url' => array('controller'=>'pools', 'action'=>'set_match_player'
					,  'id' => $pool['Pool']['id'] 
					,  'match' => $match['id'] 
					,  'pos' => 2 
					)
					,'with'=>'{draggedid:element.id}'
					,'update' => 'matches'
					)
			);
			
		}
		endforeach;
	?>
	</tr>
	</table>
</div>

    