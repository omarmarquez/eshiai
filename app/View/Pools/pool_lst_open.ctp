<?php

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
        <th>C</th>
    </tr>
<?php
foreach ($pools as $pool):


    $pid = $pool['Pool']['id'];
    echo "<tr>";
    echo "<td id='lnk_pool_$pid' style='cursor:pointer' >". $pool['Pool']['pool_name'] ."</td>";
    $cd = strtoupper(  substr( $pool['Pool']['division'] ,0,1) .substr( $pool['Pool']['category'],0,1) );
    echo "<td>$cd&nbsp;" . $pool['Pool']['registration_count'] . "</td>";
	echo "</tr>";
    
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
?>
</table>
