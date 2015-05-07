
<?php
    $url = $this->Html->url( array( 'action' => 'poolShow'));
    foreach( $pools as $p ){
        
        $pid = $p['Pool']['id'];
        
        $this->Js->buffer("

        
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
    ");
    
 
    }
    //echo  $form->end();
    echo $this->Js->writeBuffer();
?>