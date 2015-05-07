<?php
    
$ajax=$this->Js;
$i = 0;
$url = $this->Html->url( array( 'action' => 'poolShow'));
    
$this->Js->buffer("
        function refresh_pool( event, pid ){       
         $.ajax( {
                 url: '$url/' + pid
                 ,cache: false
                 ,data: {}
                 ,success: function( data){
                     var div ='#pool_' +pid;
                     $( div ).html( data )
                 }
             });  
        
    }");

?>
<table>
    <tr>
        <th>Pool&nbsp;name</th>
        <th>Cat</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>Count</th>
    </tr>
<?php
foreach ($pools as $pool):

    $pid = $pool['Pool']['id'];
    $cd = strtoupper(  substr( $pool['Pool']['division'] ,0,1) .substr( $pool['Pool']['category'],0,1) );
    $vLnk = $this->Html->link( 'POOL-' . $pool['Pool']['id'], array('action' => 'view', $pool['Pool']['id'])) 
?>
   <tr>
     <td ><div id='pool_<?php echo $pid?>' style='cursor:pointer' container_id='pools'><?php echo $pool['Pool']['pool_name'] ;?></div></td>
    <td><?php echo $cd?></td>
    <td><?php echo $vLnk?></td>
    <td><?php echo $this->Html->link( $this->Html->image('pdf_icon.png'), array('controller'=> 'pools', 'action'=>'pdf', $pool['Pool']['id']), array('target'=>'pool','escape' => FALSE)) ?>
    </td>
    <td><?php echo $pool['Pool']['registration_count'] ?></td>
   </tr>

<?php    
    echo $ajax->drag( 'pool_'. $pid, array('revert'=> false) );
    $this->Js->buffer("
        function show_pool_$pid(){
        
        if($('#pool_$pid').length == 0) { 
            $('#pools').append( '<div id=\"pool_$pid\" class=\"pooling\"></div>');
        
        $('#pool_$pid').bind( 'refresh', function( event ){
            $.ajax( {
                 url: '$url/$pid'
                 ,cache: false
                 ,data: {}
                 ,success: function( data){
                     $('#pool_$pid').html( data )
                 }
             });  
        });
        } 
        $('#pool_$pid').trigger( 'refresh' );
    }");
    $this->Js->buffer("
         $('#lnk_pool_$pid').click( function() { show_pool_$pid();} );
    ");


    endforeach;
    echo $this->Js->writeBuffer();
?>
</table>
