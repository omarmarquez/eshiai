<?php
class ReportsController extends AppController {

	public $scaffold;
	var $helpers = array('Js' => array('className' => 'MyJs' ));
	var $components = array('RequestHandler');


  public function beforeFilter() {

        $this->Auth->allow( 'clubs','clubs2','divisions');

        parent::beforeFilter();


		if( $this->Session->check('Event')) {
    	    $this->event = $this->Session->read('Event');
		}
    }
	function index(){

	}


	function divisions( $id = null ) {

		App::import('Model','Pool');
		if( !$id )
	  		$id = $this->event['id'];
		$RepModel = new Pool;

		//$RepModel->recursive = 0;
		$acomp = array();
		$aclub = array();
		$res = $RepModel->query( "SELECT c.id FROM competitors c JOIN registrations r ON r.competitor_id = c.id WHERE r.id <>0 AND r.event_id=" . $this->event['id'] );
		if( !empty($res)){
		foreach( $res as $r){
			$acomp[] = $r['c']['id'];
		}
		$res = $RepModel->query( "SELECT club_id FROM competitors c JOIN registrations r ON r.competitor_id = c.id WHERE r.id <>0 AND r.event_id=" . $this->event['id']);
		foreach( $res as $r){
			$aclub[] = $r['c']['club_id'];
		}

		$records = $RepModel->find('all',  array(

			'contain' => array(
					'Registration' => array(
						'Competitor' => 'Club',
						'conditions' => array(
							'Registration.id <>0' ,
							'Registration.event_id' =>  $this->event['id']
						 )
					,'order' => 'bracket_pos'
					)
					)
			,'order' => 'division,sex,max_age,max_weight'
			));

		}
//		debug($records); exit(0);


		$this->set( 'eid' , $id );
		$this->set( compact( 'records' )) ;
	//	debug($this->Session);// exit(0);

	} //fn

	function clubs( $id = null ) {

		App::import('Model','Club');
		if( !$id )
	  		$id = $this->event['id'];

		$RepModel = new Club;

		$RepModel->recursive = 0;
		$acomp = array();
		$aclub = array();
		$res = $RepModel->query( "SELECT c.id FROM competitors c JOIN registrations r ON r.competitor_id = c.id WHERE r.id <>0 AND r.event_id=$id"  );
		if( !empty($res)){
		foreach( $res as $r){
			$acomp[] = $r['c']['id'];
		}
		$res = $RepModel->query( "SELECT club_id FROM competitors c JOIN registrations r ON r.competitor_id = c.id WHERE c.id <>0 AND r.event_id=$id");
		foreach( $res as $r){
			$aclub[] = $r['c']['club_id'];
		}

		$clubs = $RepModel->find('all',  array(

			'contain' => array(
				'Competitor' => array(
					'conditions' => array('Competitor.id' => $acomp ),
					'Registration' => array(
						'conditions' => array(
							'Registration.id <>0' ,
							'Registration.event_id' =>  $id ),
						'Pool'
					),
					'order' =>array('first_name', 'last_name')
				)
			)
			,'conditions' => array(
							'Club.id' => $aclub
			)
			,'order' => 'Club.club_name'
		));

		}

		foreach( $clubs as $i => $club ){
			 $clubs[$i]['Club']['points'] = 0;
			foreach( $club['Competitor'] as $co ){
				foreach( $co['Registration'] as $r ){

					$clubs[$i]['Club']['points'] += 1;

						switch( $r['bracket_pos']){
							case 3:
									$clubs[$i]['Club']['points'] += 11;
							break;
							case 2:
									$clubs[$i]['Club']['points'] += 21;
							break;
							case 1:
									$clubs[$i]['Club']['points'] += 31;
							break;



					}

				}
			}

		}

		$this->set( 'eid' , $id );
		$this->set( compact( 'clubs' )) ;
	//	debug($this->Session);// exit(0);

	} //fn

	function clubs2( $id = null ) {


		if( !$id )
	  		$id = $this->event['id'];
		App::import('Model','Club');
		$RepModel = new Club;

		$RepModel->recursive = 0;
		$acomp = array();
		$aclub = array();
		$competitors = array();

		$sql = "SELECT * FROM (SELECT co.club_id , division , comp_sex , count(*) as total
		FROM registrations r JOIN competitors co ON co.id = r.competitor_id
			WHERE r.event_id = $id GROUP BY 1,2,3 ORDER BY 2,3) res";

		$res1 =  $RepModel->query( $sql );
		foreach( $res1 as $re ){
			$r= $re['res'];
			if( !isset( $competitors[ $r['club_id']])){
				$competitors[ $r['club_id'] ] = array(
					'juniors' => array(
							'F' => 0
							,'M' => 0
						)
					,'seniors' => array(
							'F' => 0
							,'M' => 0
						)
					,'masters' => array(
							'F' => 0
							,'M' => 0
						)
					,'open' => array(
							'F' => 0
							,'M' => 0
						)

						);

			}
			$competitors[ $r['club_id'] ][ $r['division'] ][ $r['comp_sex']] = $r['total'];
		}

		$res = $RepModel->query( "SELECT c.id FROM competitors c JOIN registrations r ON r.competitor_id = c.id WHERE r.event_id=" . $id );
		if( !empty($res)){
		foreach( $res as $r){
			$acomp[] = $r['c']['id'];
		}
		$res = $RepModel->query( "SELECT club_id FROM competitors c JOIN registrations r ON r.competitor_id = c.id WHERE c.id <> 0 AND  r.event_id=" . $id );
		foreach( $res as $r){
			$aclub[] = $r['c']['club_id'];
		}

		$clubs = $RepModel->find('all',  array(
			'contain' => array()

			,'conditions' => array(
							'Club.id' => $aclub
			)
			,'order' => 'Club.club_name'
		));

		}
		$this->set( 'eid' , $id );
		$this->set( compact( 'clubs','competitors' )) ;
 //	debug($aclub);  exit(0);

	}  // fn





}