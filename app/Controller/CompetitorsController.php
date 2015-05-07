<?php
App::uses('AppController', 'Controller');
/**
 * Pools Controller
 *
 */
class CompetitorsController extends AppController {

	var $components = array( 'RequestHandler' ); // Auto Ajax processing
	//public $helpers = array('Js');


/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;

	public function beforeFilter() {
	
		$this->Auth->allow('autoComplet2','view','register');
	
		parent::beforeFilter();
	
	
		if( $this->Session->check('Event')) {
			$this->event = $this->Session->read('Event');
		}
	}
	
	function autoComplete2( $name = null ) {
		if( isset( $this->data['Competitor']['name'] )){
			$name = strtolower( $this->data['Competitor']['name'] ) ;
		}
		if( isset( $this->request->query['term'] )){
			$name = strtolower( $this->request->query['term'] ) ;
		}
		$club = null;
		if( $this->Session->check('Reg.Club.id')){
			$club = $this->Session->read('Reg.Club.id');
		}

		$sql = "SELECT CONCAT( Competitor.first_name, ' ', Competitor.last_name )   AS value"
									." FROM competitors Competitor"
									. " WHERE Competitor.id <> 0";

		if( $this->Session->check('Reg.Club.id')){
			$club = $this->Session->read('Reg.Club.id');
			$sql .= " AND club_id = $club";
		}

		$sql .= " AND (	LOWER( CONCAT( Competitor.first_name, ' ',Competitor.last_name )) LIKE '%$name%' )";

		$results = $this->Competitor->query( $sql );

		foreach( $results as $i => $v ){
			$results[$i] = $v[0]['value'];
		}
	 //	debug($results);
		$this->set( compact('results'));
		$this->render( "/ajax/auto_complete2" );
	}

	
	public function register($id = null) {
		
		if( !$id ){
			$record = $this->Session->read('Competitor');
			$id= $record['id'];
			$this->Competitor->contain();
		}
		$this->Competitor->id = $id;		
		
		if (!$this->Competitor->exists()) {
			throw new NotFoundException(__('Invalid competitor'));
		}
		
		$event_info = $this->Session->read('Event');
		$bcart = $event_info["paypal_button_cart"];
		$badd = $event_info["paypal_button_add"];


		$this->Competitor->contain( array('Registration' => array(
				'order' => 'Registration.created DESC'
				,'limit' => 10
				, 'Event','Pool')));
		
		$record = $this->Competitor->read(null, $id);
		
		$istr = "";
		foreach( array(
				'custom' 			=> $event_info['id'] . "_" .$id,
			//	'discount_amount'	=> "0.00",
				'discount_amount_2'	=> "10.00",
				'discount_amount_3'	=> "10.00",
				//	'discount_num'		=> 6,
				'notify_url'		=> Router::url('/', true) .'/registrations/paypalIPN/',
				'shopping_url'		=> Router::url('/', true) .'/competitors/register/',
		)  as $f => $v ){
			$istr .= "<input type='hidden' name='$f' value='$v'>";
		}
		
		$os2 = $id . " " .  $record['Competitor']['first_name'] . " " . $record['Competitor']['last_name'];
		
		$badd = str_ireplace("</form>", "$istr</form>", $badd);
		$badd = str_ireplace('name="os2"','name="os2" value="' .  $os2 .'" readonly ', $badd);
		
	
		$this->Session->write('Competitor', $record['Competitor'] );
		$this->competitor = $record['Competitor'] ;
		$this->set( compact( 'record' ,'badd','bcart') );
	}

	function search($terms = null) {
		if ( !empty( $this->data)) {
			$terms=$this->data['Competitor']['search'];
		}
		$this->Competitor->recursive = 0;
		$cont =  array( 'Club',
			'Registration' => array(
				 'Pool' => 'Mat' ,'Event', 'Match' => array(

				 	 'Player' => array(
						'Registration' => array(
							'Competitor' => 'Club'
						)
					)

			))
		);
		if( $this->data['Competitor']['event_id']){
			$cont['Registration']['conditions'] =  array( 'Registration.event_id' => $this->data['Competitor']['event_id'] );
			$this->set('terms',$terms); // so we can search again
		}

		$this->Competitor->contain( $cont );
		$conds = array( );
		foreach( split(' ', $terms) as $t ){
			$t = strtolower($t);
			$conds[] = array( 'or' => array(
				'lower(Competitor.first_name) LIKE' => '%' .$t .'%'
				,'lower(Competitor.last_name) LIKE' => '%' .$t .'%'
				)
			);
		}
		$this->set('competitors', $this->Competitor->find('all',
			array(
				'conditions' => $conds
				,'order' => 'first_name,last_name'
			)
		));

	}
	
	
	/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Competitor->id = $id;
		if (!$this->Competitor->exists()) {
			throw new NotFoundException(__('Invalid event'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Competitor->save($this->request->data)) {
				$this->Session->setFlash(__('The competitor has been saved'));
				$this->redirect(array('action' => 'view', $this->request->data['Competitor']['id']));
			} else {
				$this->Session->setFlash(__('The competitor could not be saved. Please, try again.'));
			}
		} else {
			$clubs = $this->Competitor->Club->find('list', array( 'order' => 'Club.club_name'));
			$this->set( compact( 'clubs'));
			$this->request->data = $this->Competitor->read(null, $id);
		}
	}
	

}
