<?php
App::uses('AppModel', 'Model');
/**
 * Match Model
 *
 * @property Pool $Pool
 * @property Mat $Mat
 * @property Referee $Referee
 * @property Score $Score
 */
class Match extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'match_num';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Pool' => array(
			'className' => 'Pool',
			'foreignKey' => 'pool_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mat' => array(
			'className' => 'Mat',
			'foreignKey' => 'mat_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Referee' => array(
			'className' => 'Referee',
			'foreignKey' => 'referee_id',
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
		'Player' => array(
			'className' => 'Player',
			'foreignKey' => 'match_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'pos',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
		,'Score' => array(
			'className' => 'Score',
			'foreignKey' => 'match_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

function award( $id , $pos , $score  ){

	$redo = FALSE;
	$this->contain( array( 'Player' => 'Registration' ));
	$this->id =  $id  ;

	if( $this->field('status') == 4  && $this->field('winner') != $pos ) 
		$redo = true;

//debug($redo); exit(0);
	$pool_id = $this->field( 'pool_id')  ;
	$this->Pool->id= $pool_id;
	$pt = $this->Pool->field('type');

	if( $redo == true  && $pt <> 'rr' ){
		$this->invalidateMatch( $id, 0);
	}

	$this->set( array(
				'status'	=> 4,
				'winner'	=> $pos,
				'by'		=> $score,
				'completed'	=> date("Y-m-d H:i:s")
	) );
//debug( $this->data);
	$this->save();
//	debug( $this->data);exit(0);
	$this->contain( array( 'Player' => 'Registration' ));
	$this->read( null,  $id ) ;

	foreach( $this->data['Player'] as $p ){

		$p['by'] = $score;

		if( $p['pos'] == $pos ){
			$p['win_lose'] = 1;
			$p['Registration']['match_wins'] ++;
			if( $redo ) $p['Registration']['match_loses'] --;

		}else{
			$p['win_lose'] = 0;
			$p['Registration']['match_loses'] ++;
			if( $redo ) $p['Registration']['match_wins'] --;

		}

		//debug($p); exit(0);
		$this->Player->save( array( 'Player' => $p));
		if( $p['registration_id'] ) {
			$this->Player->Registration->save( array( 'Registration' => $p['Registration']));
		}
	}

	if( !$redo ){
		$this->Pool->matchCompleted( $pool_id );
		
	}

} // function

    function invalidateMatch( $id, $reg_id )
	{
	$this->id =  $id  ;
	$pool_id = $this->field( 'pool_id');
	$mn = $this->field( 'match_num');
	$thisRound = $this->field( 'round') ;

	$sql = "SELECT mr.match_id, mr.registration_id FROM matches_registrations mr " .
			" JOIN matches m ON m.id = mr.match_id ".
			" WHERE m.pool_id = $pool_id AND m.match_num > $mn " 
			." AND mr.registration_id IN (SELECT registration_id from matches_registrations WHERE match_id=$id)"
	;

	$res = $this->query( $sql );
	foreach( $res as $r ){
		$this->invalidateMatch( $r['mr']['match_id'], $r['mr']['registration_id']);
		}

	$this->id =  $id  ;
	$this->saveField('completed', null, false );  //ready
	$this->saveField('status', 1, false );  //ready

        if ($reg_id ) {
		$sql = "DELETE FROM matches_registrations WHERE registration_id=$reg_id AND match_id=". $id;
		$this->query( $sql );
		$this->saveField('status', 0, false );  // not ready
	}
	} //function
} //class
