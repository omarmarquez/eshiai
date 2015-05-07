<?php
App::uses('JsHelper', 'View/Helper');
class MyJsHelper extends JsHelper {
    // Add your code to override the core lHelper
    function bind( $id , $event_name, $url ){

   	if( is_array( $url ))
    		$url = $this->Html->url( $url );
	$this->buffer("
      $('#$id').bind( '$event_name', function( event ){
            $.ajax( {
                 url: '$url'
                 ,cache: false
                 ,data: {}
                 ,success: function( data){
                     $('#$id').html( data )
                 }
             });
        });
       ");

    }  //fn

     function drag( $id = null ){

    	$this->buffer("$( '#$id' ).draggable({ " .
    			"containment: '#content', " .
    			"appendTo: '#content', " .
    			"revert: 'invalid'," .
    			"scroll: false," .
    			"helper: 'clone'," .
    			"start : function() { this.style.display='.dragged'; }," .
    			"stop: function() {   this.style.display=''; }
        });
    ");
    }  //fn

    function dropRemote( $id = null, $html_opts =array(), $opts = array() ){

 		$method = isset( $opts['method'])?$opts['method']:'GET';
    	$update = isset( $opts['update'])?$opts['update']:false;
    	$url = isset( $opts['url'])?$opts['url']:false;
    	if( is_array( $url ))
    		$url = $this->Html->url( $url );

    	$this->buffer( <<<SCRIPT
			$( '#$id' ).droppable({
    			activeClass: 'ui-state-hover',
    			hoverClass: 'ui-state-active',
    			drop: function( event, ui) { $.ajax({
    				url: '$url',
    				cache: false,
    				method: 'POST',
    				data: { 'data[draggedid]' :ui.draggable.attr('id') },
    				success: function( data ){ $( '#$id' ).html(data);}
    			})}
        });
SCRIPT
    );
    }  //fn


    function dropReg( $id = null, $url =null ){

   	if( is_array( $url ))
    		$url = $this->Html->url( $url );

    	$this->buffer( <<<SCRIPT
			$( '#$id' ).droppable({
    			activeClass: 'ui-state-hover',
    			hoverClass: 'ui-state-active',
    			drop: function( event, ui) { $.ajax({
    				url: '$url',
    				cache: false,
    				method: 'POST',
    				data: { 'data[draggedid]' :ui.draggable.attr('id') },
    				success: function( data ){
    					org= '#pool_' +ui.draggable.attr('pool_id');
    					$(  org ).trigger('refresh');
    					$( '#$id' ).trigger('refresh');
    					}
    			})}
        });
SCRIPT
    );
    }  //fn

    function dropPool( $id = null, $url =null ){

   	if( is_array( $url ))
    		$url = $this->Html->url( $url );

    	$this->buffer( <<<SCRIPT
			$( '#$id' ).droppable({
    			activeClass: 'ui-state-hover',
    			hoverClass: 'ui-state-active',
    			drop: function( event, ui) { $.ajax({
    				url: '$url',
    				cache: false,
    				method: 'POST',
    				data: { 'data[draggedid]' :ui.draggable.attr('id') },
    				success: function( data ){
    					org= '#' +ui.draggable.attr('container_id');
    					$(  org ).trigger('refresh');
    					$( '#$id' ).trigger('refresh');
    					}
    			})}
        });
SCRIPT
    );
    }  //fn

    function remoteTimer(   $opts = array() ){

 		$method = isset( $opts['method'])?$opts['method']:'GET';
    	$update = isset( $opts['update'])?$opts['update']:false;
    	$url = isset( $opts['url'])?$opts['url']:false;
    	if( is_array( $url ))
    		$url = $this->Html->url( $url );

     	$frequency = isset( $opts['frequency'])?$opts['frequency']:false;
    	$count = isset( $opts['count'])?$opts['count']:false;

		$id = preg_replace( '/^#/','' , $update ) . "_" . $frequency;

 		$upd_stm = "$( '$update' ).html(data);";
		$pos = isset( $opts['position'])?$opts['position']:false;
 		switch( $pos ){
			case 'before':
			case 'top':
				$upd_stm = "$( '$update' ).prepend(data);";
				break;
			case 'after':
			case 'bottom':
				$upd_stm = "$( '$update' ).append(data);";
				break;
		}

		$count_stm = '';
	//	$count=3;
		if( $count ){
			$count_stm ="c_$id ++; if( c_$id == $count ){ clearInterval(timer_$id );timer_$id = 0;}";
		}
   	$this->buffer( <<<SCRIPT

		var c_$id =0;
 		function do_time_$id(){

 			$count_stm;

     		$.ajax({
    				url: '$url',
    				cache: false,
    				method: '$method',
    				data: { },
    				success: function( data ){
    					$upd_stm
    					}
    			});
  		};
  		do_time_$id();
  		var timer_$id = setInterval(do_time_$id,  $frequency);
SCRIPT
    );
    }  //fn

    function click( $id = null, $url =null, $opts = array() ){

		$method = isset( $opts['method'])?$opts['method']:'GET';
 		$update = isset( $opts['update'])?$opts['update']:false;
 		$pos = isset( $opts['position'])?$opts['position']:false;
   		if( is_array( $url ))
    		$url = $this->Html->url( $url );

 		$upd_stm = "$( '$update' ).html(data);";
		switch( $pos ){
			case 'before':
			case 'top':
				$upd_stm = "$( '$update' ).prepend(data);";
				break;
			case 'after':
			case 'bottom':
				$upd_stm = "$( '$update' ).append(data);";
				break;
		}

    	$this->buffer( <<<SCRIPT
  			$( '$id' ).click(function(){
     			$.ajax({
    				url: '$url',
    				cache: false,
    				method: '$method',
    				data: { },
    				success: function( data ){
    					$upd_stm
    					}
    			});
  			});
SCRIPT
    );
    }  //fn

  function submit_form( $label="submit", $opts = array() ){

		$method = isset( $opts['method'])?$opts['method']:'post';
 		$update = isset( $opts['update'])?$opts['update']:false;
 		$pos = isset( $opts['position'])?$opts['position']:false;
 		$url = isset( $opts['url'])?$opts['url']:false;
   		if( is_array( $url ))
    		$url = $this->Html->url( $url );

 		$upd_stm = "$( '$update' ).html(data);";
		switch( $pos ){
			case 'before':
			case 'top':
				$upd_stm = "$( '$update' ).prepend(data);";
				break;
			case 'after':
			case 'bottom':
				$upd_stm = "$( '$update' ).append(data);";
				break;
		}

		$id = 'submit_'.time();
		echo $this->Form->button( $label,  array('id' => $id ) );
		$id = '#' . $id ;
		$formSerialize = " $('$id').closest('form').serialize()";

    	$this->buffer( <<<SCRIPT
  			$( '$id' ).click(function(){

     		 var jqXHR=	$.ajax({
    				url: '$url',
    				cache: false,
    				type: '$method',
    				data: $formSerialize,
    				dataType:'html',
    				success: function( data, textStatus ){}
    			}).done(function( data ) {	$upd_stm });

    			return false;
  			});
SCRIPT
    );

    //debug($formSerialize);
    }  //fn

} //class
?>