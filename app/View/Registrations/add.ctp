<?php

      echo $this->Html->script( array('jquery.ui.core'
        , 'jquery.ui.widget'
        , 'jquery.ui.position' 
        , 'jquery.ui.autocomplete' 
        
        ),false );
    
    $session = $this->Session;
    $form = $this->Form;
    $html = $this->Html;
    $ajax = $this->Js;
 
    require( "form.ctp");
 ?>

 <script language="javascript">
	 	$("#ClubClubName").focus();
           $(function() {
                         $( "#ClubClubName" ).autocomplete({
                            minLength:3,
                            source:'<?php echo $this->Html->url( array( 'controller' => 'clubs' ,'action' => 'autoComplete2')) ; ?>',
                            before:  $("#busy-indicator").fadeIn(),
                            complete: $("#busy-indicator").fadeOut()
                            });
 
                          $( "#ClubClubName" ).blur(function(){
                            $.post(
                                '<?php echo $this->Html->url( array('action' => 'onsiteClub')) ; ?>',
                                { cname: $("#ClubClubName").val() },
                                function( cid ){ $("#ClubClubAbbr").val( cid );}
                            )
                          });
                           
                        $( "#CompetitorName" ).autocomplete({
                            minLength:3,
                            source:'<?php echo $this->Html->url( array( 'controller' => 'competitors' ,'action' => 'autoComplete2')) ; ?>',
                            before:  $("#busy-indicator").fadeIn(),
                            complete: $("#busy-indicator").fadeOut()
                            });
                         
                         $( "#CompetitorName" ).blur(function(){
                            $.post(
                                '<?php echo $this->Html->url( array('action' => 'onsiteComp')) ; ?>',
                                { cname: $("#CompetitorName").val() },
                                function( cid ){
                                    arrJson = eval( '(' + cid + ')');
                                    $("#CompetitorCompSex").val( arrJson['comp_sex'] );
                                    $("#CompetitorCompDob").val( arrJson['comp_dob'] );
                                    $("#RegistrationRank").val( arrJson['rank'] );
                                    $("#RegistrationDivision").val( arrJson['division'] );
                                    $("#RegistrationCardType").val( arrJson['card_type'] );
                                    $("#RegistrationCardNumber").val( arrJson['card_number'] );
                                
                                }
                            )
                          });
                          
                         });
      
</script>
<?php 
include('index.ctp');
echo $this->Js->writeBuffer();
?>