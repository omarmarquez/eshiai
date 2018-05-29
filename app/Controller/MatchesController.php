<?php
class MatchesController extends AppController {

	var $name = 'Matches';

	public $scaffold;
	public $helpers = array('Js' => array('className' => 'MyJs' ));
	public $components = array('RequestHandler');

	public function index() {

		$conditionsSubQuery['Pool.event_id'] = $this->event['id'];

		$db = $this->Match->getDataSource();
		$subQuery = $db->buildStatement(
    			array(
        			'fields'     => array('Pool.id'),
        			'table'      => $db->fullTableName($this->Match->Pool),
        			'alias'      => 'Pool',
        			'limit'      => null,
        			'offset'     => null,
        			'joins'      => array(),
        			'conditions' => $conditionsSubQuery,
        			'order'      => null,
        			'group'      => null
    			),
    	$this->Match->Pool
		);
		$subQuery = ' Match.pool_id  IN (' . $subQuery . ') ';
		$subQueryExpression = $db->expression($subQuery);

		$conditions[] = $subQueryExpression;

		//$this->Match->find('all', compact('conditions'));
		$this->paginate= array( 'Match' => array(
				'conditions' => $conditions ,
    			'contain' => array(   'Pool', 'Player'=>array('Registration' => 'Competitor') ),
    			'order' => 'pool_name,Match.id'
		));
      	$listing = $this->paginate();
      	$fields = array();
      	if( !empty($listing) ){
      		$fields = array_keys( $listing[0][ 'Match' ] );
      	}
		$fields = array(
			'id', 'status','round','skip'
			,'match_num','qorder','completed','winner','by'

		);
		$pool_fields = array(
			'id', 'pool_name','status','roundadded'

		);
		$this->set( compact('fields', 'listing' ,'pool_fields') );

	} //fn


	function startStop( $id = null){
		$this->layout = 'Ajax';
		$this->autoRender = false;
		if( !$id )
		 return;
		$this->Match->id = $id;
		$this->Match->saveField( 'started' ,  date("Y-m-d H:i:s"), false);
	}


	function award( $id = null  ){

		$match_id = $id;
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Match.', true));
			$this->redirect(array('action'=>'index'));
		}


		if (!empty($this->data)) {
	//debug($this->data); exit(0);

			$this->Match->award( $this->data['Match']['id'] , $this->data['Match']['winner'], $this->data['Match']['score'] );

			//debug($this->data); exit(0);
			$this->Match->id = $this->data['Match']['id'];
//			$this->Match->Pool->schedule( $this->Match->field( 'pool_id') );
			$this->Match->Mat->schedule( $this->Match->field( 'mat_id') );
				

//exit(0);
			$ref = $this->data['Match']['referer']?$this->data['Match']['referer']:$this->referer();
			$this->redirect($ref );

		}

		App::import('Model', 'Resource');
		$Res = new Resource;
		$scoring = $Res->find('list'
					,array(
						'fields' => array('Resource.value','Resource.title' )
						,'order' => 'sorting'
						,'conditions' => array(
							'Resource.event_type'=> 'judo'
							,'Resource.type'=> 'score'
							,'Resource.active'=> 1
						)
						)
					);

		$this->Match->recursive =-1;
		$this->Match->contain( array( 'Player' => array('Registration' => array( 'Competitor' => 'Club'))));

		$match =  $this->Match->read(null, $id);
		$competitors = array(1=>'White',2=>'Blue');
		foreach( $match['Player'] as $p )
		{
			$c = $p['Registration']['Competitor'];
			$competitors[$p['pos']] = $c['first_name']. " ".$c['last_name']." (".$c['Club']['club_abbr'].")";
		}
		$mat_id = $this->$match['Match']['mat_id'];

		$this->set( compact('match', 'scoring','match_id','mat_id','competitors'));
		$this->set('referer',  $this->referer() );

	}



	function view($id = null) {
			App::import('Model', 'Resource');
			App::import('Model', 'Club');

		if (!$id) {
			$this->Session->setFlash(__('Invalid Match.', true));
			$this->redirect(array('action'=>'index'));
		}


		$this->Match->recursive =1;
		$this->set('match', $this->Match->read(null, $id));

		//debug($this->Match->data['Player']);exit(0);
		$pool = $this->Match->data['Pool'];
		$comp = $this->Match->data['Competitor'];
		$score = $this->Match->data['Score'];


		$Res = new Resource;
		$scoring = $Res->find('list'
					,array(
						'fields' => array('Resource.value','Resource.title' )
						,'order' => 'sorting'
						,'conditions' => array(
							'Resource.event_type'=> 'judo'
							,'Resource.type'=> 'score'
							,'Resource.active'=> 1
						)
						)
					);

		$this->set( compact('pool','comp','clubs','score','scoring'));
	} //fn



	function skip(  $match_id = null ){

		//$match_id = $this->params['named']['match_id'];
		if(  $match_id  ){

			$this->Match->id = $match_id;
			$this->Match->saveField('skip', 1 , false);
			$this->Match->saveField('status', 4 , false);
			$this->Match->saveField('completed', 	date("Y-m-d H:i:s")	 , false);
			$this->Match->Pool->matchCompleted( $this->Match->field('pool_id') );

//			$this->Match->Pool->schedule( $this->Match->field( 'pool_id') );
			$this->Match->Mat->schedule( $this->Match->field( 'mat_id') );
				
	//		$this->redirect( array( 'controller' => 'mats', 'action'=> 'run', $mid));

		}
		$this->redirect( $this->referer());
	} //fn

}
