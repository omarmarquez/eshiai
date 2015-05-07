<?php
class MatsController extends AppController {

	var $name = 'Mats';
	public $scaffold;
	var $helpers = array('Js' => array('className' => 'MyJs' ));
	var $components = array('RequestHandler');

	var $paginate = array(
				'conditions' => array(),
    			'contain' => array( 'Pool' ),
    			'order' => 'Mat.name'
		);


	public function index() {

		//$this->Match->find('all', compact('conditions'));
		$this->paginate= array( 'Mat' => array(
				'conditions' => array( 'event_id' => $this->event['id']) ,
    			'contain' => array(   ),
    			'order' => 'name'
		));
      	$listing = $this->paginate();
      	$fields = array();
      	if( !empty($listing) ){
      		$fields = array_keys( $listing[0][ 'Mat' ] );
      	}
		$fields = array(
			'id', 'name','location','mat_status'

		);

		$this->set( compact('fields', 'listing' ,'pool_fields') );

	} //fn

	public function headTable() {

		$this->paginate['conditions']['Mat.event_id'] = $this->event['id'];
      	$listing = $this->paginate();
      	$fields = array();
      	if( !empty($listing) ){
      		$fields = array_keys( $listing[0][ 'Mat' ] );
      	}

		$this->set( compact('fields', 'listing' ) );
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Mat->id = $id;
		session_write_close();
		if (!$this->Mat->exists()) {
			throw new NotFoundException(__('Invalid mat'));
		}

	//	$this->layout = 'display';
		$this->Mat->recursive=0;
		$this->Mat->contain( array(
			'Deck' => array(
						'Pool',
						'Player' => array(
									'Registration' => 'Competitor'
					))
			,'Pool'
		));
		$this->set('mat', $this->Mat->read(null, $id));

	}

	function chkRefresh( $id = null, $mid = 0 ){
		$needRefresh = false;
		$this->Mat->id = $id;
		if( $this->Mat->field('current_match_id') <> $mid ){
			$needRefresh = true;
		}
//		debug($mid );
		$this->set(compact('needRefresh'));
	}


	public function board( $id = null ){
		$this->layout='ajax';

		if(!$id ) return;

 		$this->Mat->id = $id;
 //		$this->Mat->saveField('current_match_id' , null, false)	;
		$this->Mat->contain( array(  'Deck' => array( 'limit' => 1, 'Pool', 'Player' => array( 'Registration' => 'Competitor' ) ) ));

		$mat = $this->Mat->read(null, $id);

		$this->set( compact( 'mat'  ) );

	}
	public function boardMatch( $id = null ){
		$this->layout='ajax';

		if(!$id ) return;

		//$this->Mat->contain( array(  'Deck' => array( 'limit' => 1, 'Pool', 'Player' => array( 'Registration' => 'Competitor' ) ) ));
		$this->Mat->Match->contain(  array( 'Pool', 'Player' => array( 'Registration' => 'Competitor' )  ));

		$match = $this->Mat->Match->findById($id);

		$mat=array( 'Mat' => array(
			'id' =>$match['Match']['mat_id'] , 'sound' => 0)
			,'Deck'=>array( 0 => $match['Match']));
		$mat['Deck'][0]['Pool'] =   $match['Pool'] ;
		$mat['Deck'][0]['Player'] =   $match['Player'] ;
		$this->set( compact( 'mat'  ) );
		$this->render('board');

	}

	public function add() {

		if ($this->request->is('post')) {
			$this->Mat->create();
			$this->request->data['Mat']['event_id'] = $this->event['id'];
				if ($this->Mat->save($this->request->data)) {
				$this->Session->setFlash(__('The mat has been created'));
				$this->redirect(array('action' => 'headTable',$this->event['id'] ));
			} else {
				$this->Session->setFlash(__('The mat could not be saved. Please, try again.'));
			}
		}
	} // fn

	public function edit($id = null) {
		$this->Mat->id = $id;
		if (!$this->Mat->exists()) {
			throw new NotFoundException(__('Invalid mat'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
		    // debug( $this->request->data); exit(0);
			if ($this->Mat->save($this->request->data)) {
				$this->Session->setFlash(__('The mat has been saved'));
				if( $this->request->is('ajax')){
					$this->redirect(array('action' => 'mat', $this->request->data['Mat']['id']));
				}
				$this->redirect(array('action' => 'view', $this->request->data['Mat']['id']));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Mat->read(null, $id);
		}
		$this->render('add');
	} // fn


	/**
	 * pools Pools running in a mat
	 * Enter description here ...
	 * @param int $id mat_id
	 */
	function mat( $id = null ) {
		session_write_close();
		$this->Mat->contain( array(
				'Pool' => array(
					'conditions' => array(
							'Pool.mat_id'	=> $id,
							'Pool.status' 	=> array(
									2   // relased
									,4	// running
						))
				)
			));
		$mat = $this->Mat->read( null, $id );
		$this->set( compact('mat'));
	} // fn



	// This is the only correct way of assigning a pool to a mat
	// disable any others!

	function loadPool( $mid = null ) {
		//$pid = $this->params['form']['draggedid'];
		$pid =  str_replace('pool_' ,'', $this->request->query['data']['draggedid']) ;
		if( $mid && $pid ){

			$c = $this->Mat->Pool->find('count', array('conditions'=>array( 'Pool.mat_id' => $mid )));
			$this->Mat->Pool->id=$pid;

			$this->Mat->Pool->saveField('mat_id',$mid, false);
			$this->Mat->Pool->saveField('qnum',$c+1, false);
			$this->Mat->Pool->saveField('status',2, false);

			$sql = "UPDATE matches SET   mat_id=$mid WHERE pool_id=$pid  AND (status is NULL OR status NOT IN (3,4)) ";
			$this->Mat->Pool->query( $sql );
			$sql = "UPDATE matches SET status=1  WHERE pool_id=$pid  AND status=2";

			$this->Mat->Pool->query( $sql );
			//$this->Mat->Pool->release( $pid );
			//$this->Mat->Pool->schedule( $pid );
			$this->Mat->schedule( $mid );
		}
		$this->mat( $mid );
		$this->render('mat');
	}

	function poolMove( $id = null , $inc = 1){
		if( $id ){

			$this->Mat->Pool->id = $id;
			$qnum = $this->Mat->Pool->field('qnum') + $inc ;
			$this->Mat->Pool->saveField( 'qnum', $qnum );
		}
		$this->mat($this->Mat->Pool->field('mat_id') );
		$this->render('mat');

	}   //fn



	function deck($id = null , $layout = 'display' , $view ='deck'){
		$this->layout = $layout;
		$this->Mat->contain( array(
			'Deck' => array(
						'Pool',
						'Player' => array(
									'Registration' => 'Competitor'
					))
		));
		$this->set('mat', $this->Mat->read(null, $id));
		$this->render( $view );
	} //fn


	function put_at_top( $id = null, $match_id = null , $first = true){

		//debug($this->params ); exit(0);
		// $match_id = $this->params['named']['match_id'];
		if( $id  && $match_id ){

			$this->Mat->id = $id;
			$curMatchId = $this->Mat->field( 'current_match_id');
			//debug($deck); exit(0);
			$this->Mat->saveField('current_match_id', $match_id, false );
			$pos = 1;
			$this->Mat->contain( 'Deck');
			$m = $this->Mat->read(null, $id);
			//debug($m); exit(0);
			if(!empty($m['Deck'] )){
				$pos = $m['Deck'][0]['qorder'] - 1;
			}

			$this->Mat->Match->id = $match_id;
			$this->Mat->Match->saveField('qorder',$pos , false);

			if( $first ){
				$this->Mat->Match->id = $curMatchId;
				if( $this->Mat->Match->field('started')  && !$this->Mat->Match->field('completed')){
					$this->put_at_top( $id , $curMatchId, false );
				}
				$this->redirect( $this->referer());
			//	$this->redirect( array( 'controller' => 'mats', 'action'=> 'run', $mid));

			}

		}

		// $this->redirect( array('controller' => 'events', 'action'=> 'index'));
	}

	function put_at_bottom( $id = null, $match_id = null ){

		//$match_id = $this->params['named']['match_id'];
		if( $id && $match_id  ){

			$this->Mat->id = $id;
			//debug($deck); exit(0);
			$pos = 1;
			$this->Mat->contain( 'Deck');
			$m = $this->Mat->read(null, $id);
			//debug($m); exit(0);
			if(!empty($m['Deck'] )){
				$pos = $m['Deck'][ count($m['Deck'])  - 1 ]['qorder'] + 1;
			}
			$this->Mat->Match->id = $match_id;
			$this->Mat->Match->saveField('qorder',$pos , false);

			$m = $this->Mat->read(null, $id);
			if(!empty($m['Deck'] )){
				$this->Mat->saveField('current_match_id', $m['Deck'][0]['id'],false);
			}

			$this->redirect( $this->referer());

	//		$this->redirect( array( 'controller' => 'mats', 'action'=> 'run', $mid));

		}

		$this->redirect( array('controller' => 'events', 'action'=> 'index'));
	}



	function statusChange( $id = null, $new_stat = 0 ){

		$this->Mat->id = $id;
		$this->Mat->saveField( 'mat_status', $new_stat);
		$this->redirect( array( 'action' => 'mat/' .$id));
	} // fn

	function ladders( $id = null ) {

		$id = $this->event['id'];
		$filter_div = "";
		if( !empty($this->data )){

			$this->layout = 'display';
			$this->Mat->contain( array(
				'Deck' => array(
						'Pool',
						'Player' => array(
									'Registration' => 'Competitor'
					))
				));
			$this->set( 'matids',  $this->Mat->find('all',
				array(
					'conditions' => array('Mat.id' => $this->data['Mat']['matids'])
					,'order'		=> 'Mat.name'
					))

					);

		}

		$this->Mat->recursive = 0;

		$this->Mat->contain( );
		$this->set('mats', $this->Mat->find('all',
			array(
				'conditions' => array('Mat.event_id' => $id ),
				'order'		=> 'Mat.name'
			)
		));


	} // fn

} //class
