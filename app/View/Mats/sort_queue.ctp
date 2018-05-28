<style>
.fixed{
    width: 220px;
    margin-left:2px;
    display: inline;
    float: left;
}
  #deck { list-style-type: none; margin: 0; padding: 0; width: 100%; }
  #deck li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.0em; height: 18px; }
  #deck li span { position: absolute; margin-left: -1.3em; }
</style>
 <ul id="deck">
 <?php
		$i = 0; 
		//debug($mat['Deck']);
		 if( isset($mat['Deck']))
			foreach ($mat['Deck'] as  $m ):
            $cn = array( 1 => '', 2 => '' );
			foreach( $m['Player'] as $p  ){
				if( isset( $p['Registration']['Competitor'] )){
				    $c = $p['Registration']['Competitor'];
				    $cn[ $p['pos'] ] = $p['Registration']['club_abbr'] . ':&nbsp;'. $c['first_name'] . ' ' . $c['last_name'];
				}
			}
 ?>
   <li class="ui-state-default" id="<?php echo $m['id']; ?>"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
    <div class="fixed">
            <?php echo  $html->link(__( $m['Pool']['division'] .' ' . $m['Pool']['pool_name']  .' ' . $m['Pool']['category'] . ' #'.$m['match_num'], true), array('controller'=> 'pools', 'action'=>'view', $m['Pool']['id'])); ?>
    </div>
    <div class="fixed"><?php echo $cn[1]. "&nbsp;"; ?></div>
    <div class="fixed"><?php echo $cn[2]. "&nbsp;"; ?></div>
  </li>

    <?php endforeach; ?>
</ul>
  <script>
  $(function() {
    $( "#deck" ).sortable();
    $( "#deck" ).disableSelection();
    $( "#deck" ).on( "sortstop", function( event, ui ) {
       var deckOrder = $("#deck").sortable("toArray");
         $.post( "<?php echo $re_url;?>", { neworder: deckOrder } );
    });
    });
  </script>