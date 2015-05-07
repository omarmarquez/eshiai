<?php
class Player extends AppModel {

	var $name = 'Player';
	var $validate = array(
		'competitor_id' => array('numeric', 'notempty')
		,'match_id' => array('numeric', 'notempty')
	);
	var $useTable = "matches_registrations";
//	var $recursive = 1; // get Competitor info

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Match' => array('className' => 'Match',
								'foreignKey' => 'match_id',
								'conditions' => '',
								'fields' => ''
			)
/*			,'Competitor' => array('className' => 'Competitor',
								'foreignKey' => 'competitor_id',
								'conditions' => '',
								'fields' => ''
			)
*/
			,'Registration' => array('className' => 'Registration',
								'foreignKey' => 'registration_id',
								'conditions' => '',
								'fields' => ''
			)
			,'Reg' => array('className' => 'Registration',
								'foreignKey' => 'registration_id',
								'conditions' => '',
								'fields' => ''
			)
	);

	var $hasOne = array(

	);

	function beforeSave( $options = Array()  )
	{

	  //	$create = $options['created' ] ;
		$rid = $this->data['Player']['registration_id'];
		$mid = $this->data['Player']['match_id'];
		$pos = $this->data['Player']['pos'];
		$opos = $pos==1?2:1;
		$opid = 0;
		$op = null;

		$sql = "SELECT registration_id, pos FROM matches_registrations mr WHERE match_id = $mid AND pos <> $pos";
		$res = $this->query( $sql );
		if( !empty($res)){
			if( $res[0]['mr']['registration_id'] == 0 ){
				$this->data['Player']['win_lose'] = 1;
			}

		}


	return true;
	} // function

	function afterSave( $created )
	{

		$this->recursive = 0;
		$advance = 0;
		$rid = $this->data['Player']['registration_id'];
		$pos = $this->data['Player']['pos'];
		$opos = $pos==1?2:1;
		$opid = $orid = 0;
		$op = null;

		$this->Registration->id = $rid;
		$pi = $this->Registration->field('pool_id');

		//$this->Registration->Pool->id = $pi;
		//$pt = $this->Registration->Pool->field('type');


		$this->Match->contain( array( 'Player','Pool' ) );
		$match = $this->Match->findById( $this->data['Player']['match_id'] );
//debug($created);debug($this->data);debug( $match);
		$this->Match->Pool->id = $match['Match']['pool_id'];
		$pt = $this->Match->Pool->field('type');
		//	if( $match['Match']['match_num'] == 10 )	{ debug($match); exit(0); }

		if(   $created  || isset( $this->data['Player']['advance'])) { // find mat and add to queue
			if( count( $match['Player'] ) == 2 ){

				$opid = $match['Player'][$opos-1]['id'];
				$orid = $match['Player'][$opos-1]['registration_id'];
				if( !$match['Match']['status']){
					$match['Match']['status'] = 1; //ready to roll
					//$match['Match']['completed'] = '';
				}
//debug( $rid ); debug($opid);debug($orid);
				if( $created  &&  ( !$rid   || !$orid    ) ){  //   a BYE
//debug("HERE");
					$advance = 1;
					$match['Match']['skip'] = 1; //ready to roll
					$match['Match']['status'] = 4; //ready to roll
					$match['Match']['completed'] = date("Y-m-d H:i:s");

				}

				$this->Match->save( array( 'Match' => $match['Match']) , false);


			}
		}  //created
		if( $pt == 'rr' ){
		    if( $created )
				return;
			if( $this->Registration->find( 'count', array( 'conditions' => array( 'pool_id' => $pi ) ) ) == 2 ){
				if( $this->find( 'count', array( 'conditions' => array( 'registration_id' => $this->data['Player']['registration_id'] , 'win_lose' => 1 ))) == 2 ) {
				// 3matches 2 wins then skip
					$this->Match->updateAll(   array(
								'skip'		=> 1,
								'status'	=> 4,
								'completed'	=> "'" .date("Y-m-d H:i:s") ."'"
					) ,array(
								'Match.pool_id'  => $pi
								,'Match.status'  => array(1,2)
					));
			} // winner
			}

			return ;
		}

		// consider if next match should be skipped


		if( $advance || $match['Match']['completed']  ){

			$mn = $match['Match']['match_num'];
			$pi = $match['Pool']['id'];
			$br = $match['Pool']['bracketrule'];
			$sql = "";
			if(  isset($this->data['Player']['win_lose']) && $this->data['Player']['win_lose'] == 1 ){

			$sql= "SELECT m.id, win_match_pos as pos, r.special FROM bracketrules r JOIN matches m ON m.match_num = r.win_match_num
			 AND m.pool_id =$pi WHERE bracket_id = $br AND r.match_num = $mn LIMIT 1";
			}
			if(  isset($this->data['Player']['win_lose']) && $this->data['Player']['win_lose'] == 0 ){

			$sql= "SELECT m.id, lose_match_pos as pos, r.special FROM bracketrules r JOIN matches m ON m.match_num = r.lose_match_num
			 AND m.pool_id =$pi WHERE bracket_id = $br AND r.match_num = $mn LIMIT 1";

			}
			if( $sql ) {
			$res = $this->query($sql);

			if( !empty($res)){

				$final = false;
				if( isset($res[0]['r']['special'])  && $res[0]['r']['special'] == 'Optional'  && $pos == 1 &&  $this->data['Player']['win_lose'] == 1 ){
					$final = true;
				}
				if( isset($res[0]['r']['special'])  && $res[0]['r']['special'] == 'Optional'  && $pos == 2 &&  $this->data['Player']['win_lose'] == 0 ){
					$final = true;
				}

				if(  $final){
					$this->Match->read(null, $res[0]['m']['id']);
					$this->Match->set(   array(
								'skip'		=> 1,
								'status'	=> 4,
								'completed'	=> date("Y-m-d H:i:s")
					));
					$this->Match->save();
				}

				if( !$final ){
					$p1 = new Player;
					$p1->data['Player']['match_id'] = $res[0]['m']['id'];
					$p1->data['Player']['pos'] = $res[0]['r']['pos'];
					$p1->data['Player']['registration_id'] =  $rid;
					$p1->save();
				}

			//	if( $match['Match']['match_num'] == 8 )	{ debug($final); exit(0); }

			}
			}
				// Mark match as skipped if second bye
		if( $created && !$rid  && $orid ){

			$op = $this->read( null,  $opid) ;

			$op['Player']['win_lose'] = 1;
			$op['Player']['advance'] = 1;
			//if( $match['Match']['match_num'] == 14 ){  	debug($op); exit(0);}
			$this->save( $op, false);

		}


		}

	}// function

} //class

