<?php
App::uses('AppModel', 'Model');
/**
 * Pool Model
 *
 * @property Event $Event
 * @property Award $Award
 * @property Match $Match
 * @property RegCopy $RegCopy
 * @property RegCopy12 $RegCopy12
 * @property RegCopy2 $RegCopy2
 * @property Registration $Registration
 */
class Pool extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'pool_name';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
		,'Mat' => array(
			'className' => 'Mat',
			'foreignKey' => 'mat_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
		,'PoolStatus' => array(
			'className' => 'PoolStatus',
			'foreignKey' => 'status',
			'conditions' => '',
			'fields' => '',
			'order' => ''

		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Award' => array(
			'className' => 'Award',
			'foreignKey' => 'pool_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'pool_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
			'MatchOutStanding' => array('className' => 'Match',
								'foreignKey' => 'pool_id',
								'dependent' => false,
								'conditions' =>   array(  'status' => array(1 ,2), 'skip' => 0 ), // matches that are ready
								'fields' => '',
								'order' => 'match_num,round',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
			'MatchComplete' => array('className' => 'Match',
								'foreignKey' => 'pool_id',
								'dependent' => false,
								'conditions' =>   array(  'completed NOT' => null  ),
								'fields' => '',
								'order' => 'match_num,round',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),

		'Registration' => array(
			'className' => 'Registration',
			'foreignKey' => 'pool_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => '',
			'order'	=> 'weight'
		)
	);


function set_bracket_pos($id = null ) {

	$this->contain('Registration','Match');
	$this->read( null, $id );

	//debug($this->data);
	$registrations = $this->data['Registration'];
	$regs = $sep = "";
	foreach( $registrations as $r){
		$regs .= $sep . $r['id'];
		$sep = ",";
	}
	$matches = $this->data['Match'];
	$type = $this->data['Pool']['type'];

	if( $type == 'rr'){

		$sum_points = "sum(
		case `by` when 'yuko' then 5 when 'wazaari' then 7 when 'ippon' then 10 when 'fusen-gachi' then 10 else 0 end * case win_lose when 1 then 1 else -0 end )";
		$sql = <<<S1
SELECT  distinct 'id', sum(win_lose) as wins, $sum_points as points
FROM matches_registrations r
 WHERE registration_id in ( $regs )
Group by registration_id order by 2 desc ,3 desc
S1;

	$res = $this->query( $sql );
//debug($sql); debug($res); exit(0);
//	debug($res);
	$pos=0;
	foreach( $res as $posv ){
		$pos++;
		$wins = $posv[0]['wins'];
		$points = $posv[0]['points'];

		$sql = <<<S1
		UPDATE registrations SET bracket_pos = $pos
		WHERE pool_id = $id AND id IN (
			SELECT  registration_id
				FROM matches_registrations r
 				WHERE registration_id in ( $regs )
				Group by registration_id
				HAVING sum(win_lose) =$wins AND $sum_points = $points
		)
S1;
		$res = $this->query($sql);

	}
	}else{
        $sql = "UPDATE registrations SET bracket_pos = 0 WHERE pool_id = $id;";
        $res = $this->query( $sql );

		$sql = "SELECT * FROM `bracketrules` Bracket
		JOIN `pools` Pool on Bracket.bracket_id = Pool.bracketrule
		JOIN `matches` Matchh on Matchh.pool_id = Pool.id and Matchh.match_num = Bracket.match_num
		Join `matches_registrations` Registration on Registration.match_id = Matchh.id
		WHERE Pool.id = $id  AND Matchh.completed IS NOT NULL AND Matchh.skip = 0
			AND ( Bracket.win_award is not null OR Bracket.lose_award is not null )
		ORDER BY Matchh.match_num";
		$res = $this->query( $sql );

		foreach( $res as $r ){
			$wl = $r['Registration']['win_lose'];
			if(  $r['Bracket']['win_award'] && ($wl == 1) ){
				$this->query( "UPDATE registrations set bracket_pos=" . $r['Bracket']['win_award']
				. " WHERE id=". $r['Registration']['registration_id']);
			}
			if($r['Bracket']['lose_award'] &&  ($wl == 0 ) ){
				$this->query( "UPDATE registrations set bracket_pos=" . $r['Bracket']['lose_award']
				. " WHERE id=". $r['Registration']['registration_id']);
			}
		}

	}

}


function release($id = null) {

	if(!$id){
		return;
	}


		$this->id = $id;
		$this->saveField( 'auto', 0);
		$this->saveField( 'status', 2);

		$this->contain( array('MatchOutStanding' => 'Player') );
		$p = $this->read( null, $id );
		if( empty( $p['MatchOutStanding'])) return;

		$round = $p['MatchOutStanding'][0]['round'];
		foreach( $p['MatchOutStanding'] as $m){

			if( $m['round'] != $round){
				break;  //only for the current round
			}

			if( count( $m['Player']) != 2 ){
				continue;
			}
			/*
			if( $m['Player'][0]['registration_id'] == 0 ){
				$ap = $this->Pool->Match->Player->read( null, $m['Player'][1]['id'] );
				$this->Pool->Match->Player->save( array('Player' => $ap ) );
			}
			if( $m['Player'][1]['registration_id'] == 0 ){
				$ap = $this->Pool->Match->Player->read( null, $m['Player'][0]['id'] );
				$this->Pool->Match->Player->save( array('Player' => $ap ) );
			}
			*/
			if( !$m['status'] || $m['status'] == ''){
				$m['status'] = 1;
				$this->Match->save( array ('Match' => $m));
			}
			//debug($p); exit(0);
		}


}

function beforeSave( $options = Array() ) {
	if( isset( $this->data['Pool']['event_id'])){
	 $this->Event->id = $this->data['Pool']['event_id'];
	if( isset($this->data['Pool']['type']) && ! $this->data['Pool']['type'])  {
		   $this->data['Pool']['type'] = $this->Event->field('default_pool_type');
  	}
	}
	if( isset( $this->data['Pool']['division'])){
  	$div = $this->data['Pool']['division'];
  	if( $div ){
  		$this->data['Pool']['match_duration'] = $this->Event->field( "match_time_$div");
  	}
	}
	return true;
}


function afterSave( $created )
{
//	debug($this->data);
	if( isset($this->data['Pool']['auto']) && $this->data['Pool']['auto'] == 0){
		return;
	}
	if( !isset($this->data['Pool']['division']) ){
		return;
	}


//	if( $created || ( isset($this->data['Pool']['status']) &&  $this->data['Pool']['status'] == 0 )){ 	 // lets find what registrations we can bring here
	if( $created || ( $this->field("auto") == 1  &&  $this->field('status') == 0 )){
		$ranks = array();
		//debug($this->data);
		if( $this->data['Pool']['category'] ){
			$cat = $this->data['Pool']['category'];
			$res = $this->query( "SELECT rank FROM rank_category WHERE category='" . $cat ."' AND division='" . $this->data['Pool']['division'] ."'");
			foreach( $res as $rc){
				$ranks[]=$rc['rank_category']['rank'];
			}
		//	debug($ranks);exit(0);
		}
	$rids = array();
		// collect registrations
	$rconds =  array(
						 	'Registration.event_id' 	=> $this->data['Pool']['event_id']
			 				,'Registration.approved' 	=> 1
			 				,'Registration.auto_pool' 	=> 1
			 				,'Registration.pool_id' 	=> 0
			 				,'Registration.division' 	=> $this->data['Pool']['division']

		 					);
	if( $this->data['Pool']['sex'] != 'A' ){
		$rconds['Competitor.comp_sex'] = $this->data['Pool']['sex'] ;
	}

	if( $this->data['Pool']['division'] != 'open' ){
		$rconds['Registration.weight BETWEEN ? AND ?'] = array( $this->data['Pool']['min_weight'], $this->data['Pool']['max_weight'] );
	}

	if( $this->data['Pool']['division'] != 'open'  && $this->data['Pool']['division'] != 'seniors'  ){
		$rconds['Registration.age BETWEEN ? AND ?'] = array( $this->data['Pool']['min_age'], $this->data['Pool']['max_age'] );
	}

	if(!empty($ranks)){
		$rconds['Registration.rank'] = $ranks;
	}
 	$this->Registration->contain('Competitor');
	$regs = $this->Registration->find( 'all',
			 array(
			 		'conditions' => $rconds

			 	)
			);
//		debug($this->data );debug($rconds);debug($regs );

	foreach($regs  as $reg){
//	debug($this->data );debug($rconds);debug($regs );
//	exit(0);
		//debug($reg);
				if( $reg['Registration']['pool_id'] == 0 ||  $reg['Pool']['status'] == 0  ){

					 //  $reg['Registration']['pool_id'] = $this->data['Pool']['id'];
						// save registration here
						$rids[] = $reg['Registration']['id'];

				}
		}



		if( !empty($rids)){
		$idlst = implode( ",", $rids );
		$this->query( "UPDATE registrations set pool_id=" . $this->id . " WHERE id IN (" . $idlst . ")" );

		if( $created ){
			$pt = 'rr'; // round robin
			if( count($rids) > 5 ){
				$pt = 'de' ;
			}
			$this->query( "UPDATE pools set type = '$pt' WHERE id=" . $this->id  );
		}


		}

	}else{
			if( isset( $this->data['Pool']['mat_id'])){
				$this->query( "UPDATE matches set qorder=0, mat_id=" . $this->data['Pool']['mat_id']. " WHERE completed is NULL AND pool_id =" . $this->id  );
				//$this->query( "UPDATE pools set status=1,roundadded=0,roundchecked=0 WHERE id =" . $this->id  );
			}
	}
}

function beforeDelete( $cascade = True )
{

	$this->query( "UPDATE registrations set auto_pool=1, pool_id=0, match_wins=0, match_loses=0"
					." WHERE pool_id=" . $this->id  );
	$this->query( "DELETE FROM matches_registrations WHERE match_id IN ("
					. "SELECT id FROM matches where pool_id=" . $this->id . ")"  );
	$this->query( "DELETE FROM matches WHERE pool_id=" . $this->id  );

	return true;
}


function NOafterFind( $results,  $primary = true ){

	$cp = 0;
	$total = 1;
	foreach ($results as $key => $val) {
		//debug($val); exit(0);
		//return $results;
	//	debug($results);
		if( isset( $val['Pool']['id'] )){

		$res = $this->query("SELECT COUNT(id) as ct FROM matches WHERE pool_id=" . $val['Pool']['id'] )	;
		$results[$key]['Pool']['match_count'] = $res[0][0]['ct'];

		$res = $this->query("SELECT COUNT(id) as ct FROM matches WHERE pool_id=" . $val['Pool']['id'] . " AND completed IS NOT NULL" )	;
		$results[$key]['Pool']['completed'] = $res[0][0]['ct'];

		$res = $this->query("SELECT COUNT(id) as ct FROM registrations WHERE pool_id=" . $val['Pool']['id'] )	;
		$results[$key]['Pool']['reg_count'] = $res[0][0]['ct'];

		}

	}

	return $results;
}

function matchCompleted( $id = null ){

		if( ! $id )
			return;

			$this->id= $id ;
			$this->saveField( 'completed_count', $this->field('completed_count') + 1 , false );

			$this->contain( 'MatchOutStanding' );

			$pool = $this->read(null, $id  );
			if( !empty( $pool['MatchOutStanding'] ) ){
				$this->saveField( 'status', 4, false ); //running
			}else{
				$this->saveField( 'status', 5, false ); //complete
				$this->set_bracket_pos( $id );
		}

} //fn

function schedule( $id, $limit = 5){

      	if(! $id ){
    		return;
    	}
    	$this->contain( array('Mat' => 'Deck','MatchOutStanding' ));

		$p = $this->read( null, $id );
		if( empty($p['MatchOutStanding'])){
				return ;
		}
		$pool = $p['Pool'];
		$mat = $p['Mat'];
		$deck_len = count( $mat['Deck']);
		$cur_mid = 0;
//debug($pool);exit(0);
		if( $deck_len < $limit  ){

			$deck_pos = 1;
			if( $deck_len){
				$deck_pos = $mat['Deck'][ $deck_len - 1]['qorder'] + 1 ;
				$cur_mid = $mat['Deck'][0]['id'];
			}
 			$pool['roundadded']= $p['MatchOutStanding'][0]['round'] ;
			foreach( $p['MatchOutStanding'] as $m ){
				// debug(  $pool['roundadded']);
				if( $m['round'] <  $pool['roundadded'] ){
					 $pool['roundadded'] = $m['round'] ;
				}
				if( $m['round'] == $pool['roundadded']  && $m['status'] == 0 ){ //not ready yet
					return ; // go to next pool if any
				}
			}

			$added = 0;
			foreach( $p['MatchOutStanding'] as $m ){

				if( $m['round'] == $pool['roundadded'] && $m['status'] == 1 ){ //ready
					// put match in queue
					$deck_len++;
					$m['qorder'] = $deck_pos++ ;
					$m['status'] = 2 ; //queued
					$added = 1;
					if( !$cur_mid) $cur_mid = $m['id'];

					$this->Match->save( array( 'Match' => $m ) , null  ); // queued
//debug($m);exit(0);
				}

		} // each pool match
			 // save the pool now
		if( $added){
			 	$this->id=$id;
			 	$this->saveField( 'roundadded' ,  $pool['roundadded'] , false );
			 	$this->saveField( 'status' ,  4 , false ); // running

		}

		}


 //   	debug( $this->data ); exit(0);
		return;
  	} // schedule
} //class
