<?php
App::uses('AppModel', 'Model');
/**
 * Mat Model
 *
 * @property Event $Event
 * @property Match $Match
 * @property Pool $Pool
 */
class Mat extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

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
		),
		'Match' =>
     	     array('className' => 'Match',
   			'conditions' => '',
    			'order' => '',
    			'dependent' => false,
    			'foreignKey' => 'current_match_id'
   			)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(

		'Pool' => array(
			'className' => 'Pool',
			'foreignKey' => 'mat_id',
			'dependent' => false,
			'conditions' => array( 'status' => array(2,4)), // 3= Assigned, 4= running
			'fields' => '',
			'order' => 'qnum',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Deck' => array('className' => 'Match',
			'foreignKey' => 'mat_id',
			'dependent' => false,
			'conditions' =>   array( 'status' => 2 ), // 2= queued,
			'fields' => '',
			'order' => 'qorder',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			/* SELECT `Matches`.* FROM matches `Matches` WHERE `Matches`.mat_id = {$__cakeID__$} AND `Matches`.completed IS NULL AND `Matches`.skip = 0 AND (
				SELECT COUNT( 1 ) FROM matches_registrations WHERE match_id = `Matches`.id ) =2  ORDER BY qorder ' ,
			*/
			'counterQuery' => ''
			)
	);


function beforeDelete($cascade = true)
{

	if( $this->parent->beforeDelete() ){

		$this->query( "UPDATE pools set mat_id=0  WHERE mat_id=" . $this->id  );
		return true;
	}
	return false;
}

function schedule( $id,  $limit =5 ){


	if(! $id ){
		return;
	}
	$this->contain( array('Deck', 'Pool'));
	$mat = $this->read( null, $id );
	foreach( $mat['Pool'] as $pool ){
		
		$this->Pool->schedule($pool['id'], $limit );
		
		$this->contain( array('Deck', 'Pool'));
		$mat = $this->read( null, $id );
		if( count ( $mat['Deck']) > $limit )
				break;
		
	}
	
	$cmid = null;
	if( !empty($mat['Deck'])  ){
		$cmid = $mat['Deck'][0]['id'];
	}
	$this->id=$id;
	$this->saveField( 'current_match_id',$cmid );
	
}

function scheduleOld( $id,  $limit =5 ){


      	if(! $id ){
    		return;
    	}
    	$this->contain( array('Deck', 'Pool' => array('MatchOutStanding' )));
		$mat = $this->read( null, $id );
		$deck_len = count( $mat['Deck']);
//debug($mat);exit(0);
		if( $deck_len < $limit  ){

		$deck_pos = 1;
		if( $deck_len){
			$deck_pos = $mat['Deck'][ $deck_len - 1]['qorder'] + 1 ;
		}



		foreach( $mat['Pool'] as $pool ){
			//debug($pool); //exit(0);
			// add round

			$all_clear = 1 ;
			//if( !isset( $pool['MatchOutStanding'])){	continue;	}
			if( !empty( $pool['MatchOutStanding']) ){
				 $pool['roundadded'] = $pool['MatchOutStanding'][0]['round'];
			}else{
		 		//$this->Pool->id=$pool['id'];
			 	//$this->Pool->saveField( 'status' ,  5 , false );
				continue;
			}
			foreach( $pool['MatchOutStanding'] as $m ){
				// debug(  $pool['roundadded']);
				if( $m['round'] <  $pool['roundadded'] ){
					 $pool['roundadded'] = $m['round'] ;
				}
				if( $m['round'] == $pool['roundadded']  && $m['status'] == 0 ){ //not ready yet
					$all_clear = 0;
				//	debug($m) ; debug($pool);
					break; // go to next pool if any
				}
			}

			if( !$all_clear ){
				continue;  // next pool please
			}
			$added = 0;
			foreach( $pool['MatchOutStanding'] as $m ){

				if( $m['round'] == $pool['roundadded'] && $m['status'] == 1 ){ //ready
					// put match in queue
					$deck_len++;
					$m['qorder'] = $deck_pos++ ;
					$m['status'] = 2 ; //queued
					$added = 1;
					$this->Match->save( array( 'Match' => $m ) , null  ); // queued
//debug($m);exit(0);
				}

				} // each pool match
			 // save the pool now
			 if( $added){
			 	$this->Pool->id=$pool['id'];
			 	$this->Pool->saveField( 'roundadded' ,  $pool['roundadded'] , false );
			 	$this->Pool->saveField( 'status' ,  4 , false ); // running
			 }
			// bail out if we are full
			if( $deck_len > $limit ){
					break;
			}

		}

		}

		if( $deck_len  ){
			$this->contain('Deck');
			$mat = $this->read( null, $id );
			//debug($mat);exit(0);
			$mat['Mat']['current_match_id'] = $mat['Deck'][0]['id'];
    		$this->save( array( 'Mat' => $mat['Mat'] ), null );
    	}

 //   	debug( $this->data ); exit(0);
		return;
  	} // schedule

}
