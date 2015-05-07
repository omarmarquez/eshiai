        <?php 
            $form=$this->Form;
            if( !isset($referer))
                $referer = '';
            echo $form->create('Match',array('controller' => 'matches', 'action' => 'award', $match_id )); 
            echo $form->hidden('id' , array('value'=>$match_id));
            echo $form->hidden('mat_id' , array('value'=>$mat_id));
            echo $form->hidden('winner' , array('value'=>''));
            echo $form->hidden('referer' , array('value'=>$referer));
         ?>


            <table>
                <tr>
                    <td colspan="2">
                    <fieldset>
                        <legend>Score</legend>
                <?php 
                $options = array( 'Ippon'=>'ippon', 'Wazaari'=>'wazaari', 'Yuko'=>'yuko', 'Hantei'=>'hantei', 'Fusen-gachi(no show)'=>'fusen-gachi','Hansoku-make (disqualification)'=>'hansoku-make');
                foreach ($options as $label => $score):
                ?>
                <div class="float_left">
                    <input id="MatchScore<?php echo $label;?>" type="radio" value="<?php echo $score;?>" name="data[Match][score]"/>
                    <label for="MatchScore<?php echo $label;?>"><?php echo $label;?></label>
                </div>
                <?php endforeach; ?>
                    </fieldset>
                </td>
                </tr>
                <tr>
                   <td class="td_white"><?php echo $form->submit(__('White Wins', true), array( 'onclick' =>'this.form["data[Match][winner]"].value=1;', 'div'=>false));   ?></td>
                   <td class="td_blue"><?php echo $form->submit(__('Blue Wins', true), array( 'onclick' =>'this.form["data[Match][winner]"].value=2;', 'div'=>false)); ?></td>
  
                </tr>
            </table>

        <?php echo $form->end();  ?>
        

<script language="javascript">
function validate_award( form ){

    var ok = false;

    var score = form['data[Match][score]'];
    for (i=0;i<score.length;i++) {
        if (score[i].checked) {
            ok = true;
        }
    }
                        if( document.winner['data[win]'].value == '' || document.winner['data[by]'].value == '' ){
                            alert("You must select competitor and score!");
                            return false;
                        }
                        
                        if(  confirm("Winner is #" + document.winner['data[win]'].value + " by " 
                            +  document.winner['data[by]'].value + " ?")){
                                
                                
                                document.winner.submit();
                            }

    if( !ok ){
        alert( "Please select the score");
        return false;
    }
    
    return true;
}
</script>

