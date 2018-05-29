	<!--<table  cellpadding = "0" cellspacing = "0" align="right">
	    <tr>
		<th><?php //__('Blue'); ?></th>
		<th><?php //__('White'); ?></th>		
	  </tr>	
	  -->
		<?php
		$i = 0;
		//debug($mat['Matches']);
	//	foreach ($mat['Pool'] as $pool):
        $action ='';
		foreach ($mat['Deck'] as  $m ):
			if( ++$i > 5 ){
				break;
			}
		// debug($m);
			$class = null;
		
			
  			$cn = array( 1 => '', 2 => '' );
			foreach( $m['Player'] as $p  ){
				if( isset($p['Registration']['Competitor'])):
				$c = $p['Registration']['Competitor'];
				// $c['Club']['club_abbr'] . ':&nbsp;'. 
				$init = substr( $c['first_name'], 0 , 1 ).'.';
				$cn[ $p['pos'] ] =   $c['first_name']. ' '.  $c['last_name']  
				 		.  "<br>" . $p['Registration']['club_abbr']
				 ;
				endif;
			}
            
                $pi = $m['Pool']['division'] . " " . $m['Pool']['pool_name']. " #" . $m['match_num'];
                
              $action1 = $this->Html->link( __($pi)
                ,array( 'controller' => 'mats', 'action' => 'put_at_bottom' , $m['mat_id'] ,  $m['id']  )
                , null
                ,'Send to bottom of deck?' 
                );
             $action2 = $this->Html->link( __($pi)
                ,array( 'controller' => 'mats', 'action' => 'put_at_top' , $m['mat_id'] ,  $m['id']  )
                , null
                ,'Send to top of deck?' 
                );
            
                $action= $action?$action2:$action1;
		?>
	<!--	<tr style='height:20%'> 
			<td>
		-->
				<div class="deck_row">
				<div class="deck_white"><?php echo $cn[1];?></div>
                <div class="deck_blue"><?php echo $cn[2];?></div>
				<div class="deck_pool"><a><?php echo $action?></a></div>
				</div>
		<!-- 	</td>
		</tr>
		-->
		<?php endforeach; ?>
	<!-- 	</table>
	-->
