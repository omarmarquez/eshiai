<?php
App::uses('AppModel', 'Model');
/**
 * Registration Model
 *
 * @property Award $Award
 * @property Competitor $Competitor
 * @property Event $Event
 * @property Pool $Pool
 * @property Score $Score
 * @property Match $Match
 */
class Registration extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'Award' => array(
			'className' => 'Award',
			'foreignKey' => 'registration_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Competitor' => array(
			'className' => 'Competitor',
			'foreignKey' => 'competitor_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Pool' => array(
			'className' => 'Pool',
			'foreignKey' => 'pool_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Payment' => array(
			'className' => 'Payment',
			'foreignKey' => 'payment_id',
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
		'Score' => array(
			'className' => 'Score',
			'foreignKey' => 'registration_id',
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


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Match' => array(
			'className' => 'Match',
			'joinTable' => 'matches_registrations',
			'foreignKey' => 'registration_id',
			'associationForeignKey' => 'match_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);


function beforeSave( $options = array() ) {

		if( empty($this->data['Registration'])  )
			return false;

		if( !isset($this->data['Registration']['event_id']) )
			return true;
		if( !isset($this->data['Registration']['competitor_id']) )
			return true;

		if( !$this->data['Registration']['approved']  
				|| $this->data['Registration']['rtype'] != 'shiai'
				){
			$this->data['Registration']['auto_pool'] = 0;
		}

		if( $this->data['Registration']['upSkill'] =='Y'
			||  $this->data['Registration']['upAge'] =='Y'
			||  $this->data['Registration']['upWeight'] =='Y' ){
			//$this->data['Registration']['auto_pool'] = 0;
		}
		if( isset($this->data['Registration']['pool_id']) && $this->data['Registration']['pool_id'] != 0 ){

			$this->Pool->id = $this->data['Registration']['pool_id'];
			if( $this->Pool->field('status') != 0 ){
				return true;
			}
		}
		//update age
		//debug($this->data['Registration']); exit(0);
		//$this->Competitor->read( null,  $this->data['Registration']['competitor_id']);
		$d = 0;
		$this->Event->id =  $this->data['Registration']['event_id'];
		$dp1=explode("-", $this->Event->field( 'event_date' ) ) ;
		$this->Competitor->id =  $this->data['Registration']['competitor_id'];
		$dp2=explode("-",  date( 'Y-m-d', strtotime( $this->Competitor->field( 'comp_dob' ) )) );

		$d =  $dp1[0] -  $dp2[0] ;
		// compare months
		if( $dp2[1] >= $dp1[1] ){ // birthday after event ?
			$d --;

			if( $dp2[1] == $dp1[1]){ //same month
				if( $dp2[2] < $dp1[2] ){ // compare days birthday before event
					$d++;
				}
			}
		}

		$this->data['Registration']['age'] = $d;

	if( !$this->data['Registration']['approved']
		 || ( isset($this->data['Registration']['auto_pool']) && $this->data['Registration']['auto_pool'] == 0 )){
		return true;
	}
	 // debug($this->data['Registration'] ); debug($res); exit(0);
	$this->data['Registration']['pool_id'] = 0;
	// find pool list

	$cat = "";
	$res = $this->query( "SELECT category FROM rank_category " .
			" WHERE rank='" . $this->data['Registration']['rank'] ."' " .
					" AND division='" . $this->data['Registration']['division'] ."'" .
					" AND " .  $this->data['Registration']['age'] . " BETWEEN age_start AND age_end" 
	);
	 // debug($this->data['Registration'] ); debug($res); exit(0);

	if( !empty($res)){
		$cat = $res[0]['rank_category']['category'];


	$pool = $this->Pool->find( 'first',
			 array('conditions' =>
			 		array(
						'Pool.event_id' => $this->data['Registration']['event_id']
						,'Pool.status' => 0
						,'Pool.division' => $this->data['Registration']['division']
						,'Pool.sex' => array('A', $this->Competitor->field('comp_sex') )
						,'Pool.min_weight <=' => $this->data['Registration']['weight']
						,'Pool.max_weight >=' => $this->data['Registration']['weight']
						,'Pool.min_age <=' => $this->data['Registration']['age']
						,'Pool.max_age >=' => $this->data['Registration']['age']
						,'Pool.category' => $cat
						)
			 	)
		);
		if( $pool &&  $this->data['Registration']['upAge'] =='Y' ){

			$p2 = $this->Pool->find( 'first',
			 	array('conditions' =>
			 		array(
						'Pool.event_id' => $this->data['Registration']['event_id']
						,'Pool.status' => 0
						,'Pool.division' => $this->data['Registration']['division']
						,'Pool.sex' => array('A', $this->Competitor->field('comp_sex') )
						,'Pool.min_weight <=' => $this->data['Registration']['weight']
						,'Pool.max_weight >=' => $this->data['Registration']['weight']
						,'Pool.min_age <=' => $pool['Pool']['max_age'] +1
						,'Pool.max_age >=' => $pool['Pool']['max_age'] +1
						,'Pool.category' => $cat
						)
			 	));
			 	if( $p2 )
			 	   $pool = $p2;
		}
		if( $pool &&  $this->data['Registration']['upWeight'] =='Y' ){

			$p2 = $this->Pool->find( 'first',
			 	array('conditions' =>
			 		array(
						'Pool.event_id' => $this->data['Registration']['event_id']
						,'Pool.status' => 0
						,'Pool.division' => $this->data['Registration']['division']
						,'Pool.sex' => array('A', $this->Competitor->field('comp_sex') )
						,'Pool.min_weight <=' => $pool['Pool']['max_weight'] + 1
						,'Pool.max_weight >=' => $pool['Pool']['max_weight'] + 1
						,'Pool.min_age <=' => $this->data['Registration']['age']
						,'Pool.max_age >=' => $this->data['Registration']['age']
						,'Pool.category' => $cat
						)
			 	));
			 	if( $p2 )
			 	   $pool = $p2;
		}

		//  debug($options);debug($cat);debug($this->data['Registration']);debug($pool);exit(0);
			if($pool){
				$this->data['Registration']['pool_id'] = $pool['Pool']['id'];
					return true;

			}
		}

		// non categorized search
		$pool = $this->Pool->find( 'first',
			 array('conditions' =>
			 		array(
						'Pool.event_id' => $this->data['Registration']['event_id']
						,'Pool.status' => 0
						,'Pool.division' => $this->data['Registration']['division']
						,'Pool.sex' => array('A', $this->Competitor->field('comp_sex') )
						,'Pool.min_weight <=' => $this->data['Registration']['weight']
						,'Pool.max_weight >=' => $this->data['Registration']['weight']
						,'Pool.min_age <=' => $this->data['Registration']['age']
						,'Pool.max_age >=' => $this->data['Registration']['age']
						)
			 	)
			);
		// debug($pool);exit(0);
			if($pool){
					$this->data['Registration']['pool_id'] = $pool['Pool']['id'];
					return true;

			}

        //debug($cat);debug($this->data );debug($pool);exit(0);
		return true;
}

function NoafterSave( $created )
{
	// debug($this->data); exit(0);
}

}
