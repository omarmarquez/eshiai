<?php
    
$ajax=$this->Js;
$i = 0;
$url = $this->Html->url( array( 'action' => 'members'));
    
$this->Js->buffer("
        function viewClub( pid ){       
         $.ajax( {
                 url: '$url/' + pid
                 ,cache: false
                 ,data: {}
                 ,success: function( data){
                     var div ='#club_' +pid;
                     $( div ).html( data )
                 }
             });  
        
    }");

?>
<table>
    <tr>
        <th>club&nbsp;name</th>
        <th>Cat</th>
        <th>ID</th>
        <th>&nbsp;</th>
    </tr>
<?php
foreach ($clubs as $club):

    $pid = $club['Club']['id'];
    $cd = '';
     $vLnk = $this->Html->link(   $club['Club']['club_name'], array('action' => 'view', $club['Club']['id'])) 
?>
   <tr>
     <td ><div id='lnk_club_<?php echo $pid?>' style='cursor:pointer' container_id='clubs'>CLUB-<?php echo $club['Club']['id'] ;?></div></td>
    <td><?php echo $cd?></td>
    <td><?php echo $vLnk?></td>
    <td><?php echo $this->Html->link( $this->Html->image('pdf_icon.png'), array('controller'=> 'clubs', 'action'=>'pdf', $club['Club']['id']), array('target'=>'Club','escape' => FALSE)) ?>
    </td>
   </tr>

<?php    
    echo $ajax->drag( 'club_'. $pid, array('revert'=> false) );
    
    $this->Js->buffer("
        function show_club_$pid(){
        
        if($('#club_$pid').length == 0) { 
            $('#clubs').append( '<div id=\"club_$pid\" class=\"clubing\"></div>');
        
        $('#club_$pid').bind( 'refresh', function( event ){
            $.ajax( {
                 url: '$url/$pid'
                 ,cache: false
                 ,data: {}
                 ,success: function( data){
                     $('#club_$pid').html( data )
                 }
             });  
        });
        } 
        $('#club_$pid').trigger( 'refresh' );
    }
         $('#lnk_club_$pid').click( function() { show_club_$pid();} );
    ");

    endforeach;
    echo $this->Js->writeBuffer();
?>
</table>
