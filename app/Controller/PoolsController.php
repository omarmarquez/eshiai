<?php
App::uses('AppController', 'Controller');
/**
 * Pools Controller
 *
 */
class PoolsController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;
	public $helpers = array('Js' => array('className' => 'MyJs' ));
	public $components = array('RequestHandler','Ntkfdf');

	public function index() {
		$this->paginate= array( 'Pool' => array(
				'conditions' => array(
					'Pool.event_id' => $this->event['id']
				),
    			'contain' => array(  ),
    			'order' => 'Pool.min_age'
		));
      	$listing = $this->paginate();
      	$fields = array();
      	if( !empty($listing) ){
      		$fields = array_keys( $listing[0][ 'Pool' ] );
      	}
		$fields = array(
			'id', 'pool_name','status','auto','type','sex','division'
			,'min_age','max_age'
			,'min_weight','max_weight','registration_count'
			,'created','modified'
			,'match_duration'
			,'mat_id'

		);
		$this->set( compact('fields', 'listing' ) );

	}

 function competitors(  ){

    $comp_name = null;
    $pools = array();
	$event_id = $this->event['id'];
    if( !empty($this->data)):

      if( !empty( $this->data['Competitor'])):

        $comp_name = $this->data['Competitor']['name'];

        $plst = $this->Pool->query(
            "SELECT Pool.id"
            . " FROM competitors Competitor"
            ." JOIN registrations Registration ON Registration.competitor_id = Competitor.id"
            ." JOIN pools Pool ON Pool.id = Registration.pool_id"
            . " WHERE Registration.event_id = $event_id AND CONCAT_WS(' ', first_name , last_name) ='$comp_name'"
        );
        $pset = array();
        foreach( $plst as $p ):
            $pset[] = $p['Pool']['id'];
        endforeach;

            $pools = $this->Pool->find('all', array(
            'conditions' => array(  'Pool.id' => $pset),
            'containNO' => array(
                    'PoolStatus'
                    ,'Mat'
                    ,'Registration' => array('Competitor')
              )
        ));
        //debug( $pools);

          endif;
     endif;
        $this->set(compact('pools'));
  }  //fn

function calculatePlaces($id = null) {

		if (!$id) {
			$this->Session->setFlash(__('Invalid Pool.', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->Pool->set_bracket_pos( $id );
		$this->redirect($this->referer());
}

function poolLst( $status = array(), $view='pool_lst' ,$container  =''){

		session_write_close();

		$this->Pool->contain( array( 'PoolStatus','Mat') );

		$pools = $this->Pool->find( 'all',  array( 'conditions' => array(
				'Pool.event_id' => $this->event['id']
				,'Pool.status'  => $status
		)));
		$this->set(compact( 'pools' ,'status', 'container'));
		$this->render( $view );
} // fn

function setup( $id = null ) {

		if(!$id )
		  $id = $this->event['id'];

		$this->Pool->recursive =-1;
		$sex = $mina = $maxa = $minw = $maxw = '';
		$ccond = array(  );
		$rcond = array( );
		$pcond = array( 'Pool.status' => array(0,1,2,3));

		$filter = array(
				'fdiv' 	=> '',
				'fsex'	=> '',
				'fmina'	=> '',
				'fmaxa'	=> '',
				'fminw'	=> '',
				'fmaxw'	=> ''
		);
		if( $this->Session->check('Pool.filter')){
			$filter = $this->Session->read('Pool.filter');
		}
		if( !empty($this->data)){ //filter

			//debug($this->data); exit(0);
			$sex = $this->data['Pool']['sex'];
			$mina = $this->data['Pool']['min_age'];
			$minw = $this->data['Pool']['min_weight'];
			$maxa = $this->data['Pool']['max_age'];
			$maxw = $this->data['Pool']['max_weight'];
			$div = $this->data['Pool']['division'];

			$filter = array(
				'fdiv' 	=> $div,
				'fsex'	=> $sex,
				'fmina'	=> $mina,
				'fmaxa'	=> $maxa,
				'fminw'	=> $minw,
				'fmaxw'	=> $maxw
			);

			}
			$this->Session->write( 'Pool.filter', $filter);
			extract( $filter );
			//debug($filter);
			if( $fdiv ){
				$pcond['Pool.division']  = $fdiv  ;
			}

			if(  $fsex && $fsex != 'A'){
				$ccond['Competitor.comp_sex']  = $fsex  ;
				$pcond['Pool.sex']  = $fsex  ;
			}

			if( $fmina ){
				// $rcond['Registration.age >=']  = $fmina  ;
				$pcond['Pool.min_age >='] = $fmina;
			}
			if( $fmaxa ){
			//	$rcond['Registration.age <=']  = $fmaxa  ;
				$pcond['Pool.max_age <='] = $fmaxa;
			}
			if( $fminw ){
			//	$rcond['Registration.weight >=']  = $fminw  ;
				$pcond['Pool.min_weight >='] = $fminw;
			}

			if( $fmaxw ){
			//	$rcond['Registration.weight <=']  = $fmaxw  ;
				$pcond['Pool.max_weight <='] = $fmaxw;
			}

		$pcond['Pool.event_id']  = $id;
		$pools= $this->Pool->find( 'all', array(
			'contain' => 'PoolStatus',
    		'order' => 'Pool.min_age,Pool.min_weight',
    		'conditions' => $pcond
			)
		);
// debug($pcond);

		$dpt = $this->event['default_pool_type'];
		$this->set(compact('pools','dpt'));

		// Loosing Session remedy
	//	$this->Session->write("Event.id", $id ) ;
	//	$this->Session->write("Event.name", $event['Event']['event_name'] ) ;
}  //fn

	function unasigned( $id = null ){

		$ccond = array( );
		$rcond = array( );

		$rcond['Registration.event_id'] = $this->event['id'];
		$rcond['Registration.pool_id'] = 0;

		$this->Pool->Registration->contain( array(

					'Competitor'=>array(
						'conditions' => $ccond ,
						//'order'  => 'Registration.weight',
						)
		));
		$registrations = $this->Pool->Registration->find( 'all',
				array(
					'conditions' => array(
							'event_id'	=> $this->event['id'],
							'pool_id'	=> 0,
							'rtype'		=> 'shiai'
					),
					'order'	=> 'weight'
				)
		);
		$pool = array(
			'Pool' =>array(
				'id' => 'unassigned'
				,'status' => 0
				,'min_age' => 0
				,'min_weight' => 0
				,'max_age' => 0
				,'max_weight' => 0
				)
			,'Registration' => $registrations
		);

		$this->set(compact( 'pool', 'registrations') );


	} // fn

	function assignReady( $id = null ){

		$ccond = array( );
		$rcond = array( );

		$rcond['Registration.event_id'] = $this->event['id'];
		$rcond['Registration.pool_id'] = 0;

		$this->Pool->Registration->contain( array(

					'Competitor'=>array(
						'conditions' => $ccond ,
						//'order'  => 'Registration.weight',
						)
		));
		foreach( $this->Pool->Registration->find( 'all',
				array(
					'conditions' => array(
							'event_id'	=> $this->event['id'],
							'auto_pool'	=> 1,
							'pool_id'	=> 0,
							'rtype'		=> 'shiai'
					),
					'order'	=> 'weight'
				)
		) as $reg)
		{
			$this->Pool->Registration->save( $reg, false );
		}
		$this->redirect( $this->referer());

	} // fn

    function poolShow(  $id ) {

	if( $id != 0){

     $pool = $this->Pool->find('first', array(
            'conditions' => array(  'Pool.id' => $id),
            'contain' => array(
                    'PoolStatus'
                    ,'Mat'
                    ,'Registration' => array('Competitor')
              )
        ));

	}
        $this->set(compact('pool'));

    }//fn

	function dropReg( $id = null ) {
		$this->Pool->recursive =-1;
		// $rid = $this->request->query['data']['id'];
		$rid = $this->request->query['data']['draggedid'];

//debug($this->request->query );

		if( $id && $rid ){

			$reg = $this->Pool->Registration->read(null, $rid );
			if( $reg['Registration']['pool_id'] <> $id ){

				$reg['Registration']['auto_pool'] = 0;
				$reg['Registration']['seed'] = 0;
				$reg['Registration']['pool_id'] = $id;

				$this->Pool->Registration->save( $reg, false );
			}

		}
		$this->autoRender = false;
	//	$this->poolShow( $id );
	//	$this->render('pool_show');

	} //fn


	public function add() {
		if ($this->request->is('post')) {
			$this->Pool->create();
			$this->request->data['Pool']['event_id'] = $this->event['id'];
			if ($this->Pool->save($this->request->data)) {
				$this->Session->setFlash(__('The pool has been saved'));
				// return $this-Pool->getLastInsertID();
				$this->redirect(array('action' => 'setup'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.'));
				// return 0;
			}
		}
		$dpt = $this->event['default_pool_type'];
		$this->set(compact('dpt'));
		$this->render('edit');
	}

	function add_group( $id = null ) {

		debug($this->data);

		exit(0);
		if (!empty($this->data) && !empty($this->data['Regs'])) {

			$id = $this->data['Pool']['id'];
			$np = false;
			$maxw = 0; $minw = 500;
			$maxa = 0; $mina = 500;
			$msex = $fsex = false;

			if( !$id) { // create
					$np = false;
					$this->Pool->create();
					$this->data['Pool']['auto'] = 0;
					if(! $this->data['Pool']['pool_name']){
						$this->data['Pool']['pool_name'] = 'temp';
						$np = true;
					}
					$this->Pool->save($this->data);
					$id = $this->Pool->id;
					$this->data['Pool']['id'] = $id;
				//	debug( $this->data['Pool'] );		exit(0);
				}
			foreach( $this->data['Regs'] as $rid ){
				if( $rid != 0 ){
					$this->Pool->Registration->id = $rid;
					$this->Pool->Registration->saveField('pool_id',$id , false );
					if( $np ){
						$s = $this->Pool->Registration->field('competitor_id');
						$s = $this->Pool->Registration->Competitor->id = $s;
						$s = $this->Pool->Registration->Competitor->field('comp_sex');
						$a = $this->Pool->Registration->field('age');
						$w = $this->Pool->Registration->field('weight');
						if( $s == 'F'){
							$fsex = true;
						}
						if( $s == 'M'){
							$msex = true;
						}
						if( $w > $maxw){ $maxw = $w; }
						if( $w < $minw){ $minw = $w; }
						if( $a > $maxa){ $maxa = $a; }
						if( $a < $mina){ $mina = $a; }
					}

				}
			}
		 	$this->data['Pool']['min_age'] = $mina;
		 	$this->data['Pool']['max_age'] = $maxa;
		 	$this->data['Pool']['min_weight'] = $minw;
		 	$this->data['Pool']['max_weight'] = $maxw;
			if( $np ){
		 	$name = '';
		 	if( $fsex ){
				 	$this->data['Pool']['sex'] = 'F';
				 	$name = 'females, ';
		 	}
		 	if( $msex ){
				 	$this->data['Pool']['sex'] = 'M';
				 	$name  = 'males, ';
		 	}
		 	if( $fsex && $msex ){
				 	$this->data['Pool']['sex'] = 'A';
				 	$name  = "all, ";
		 	}
		 	$name .= $mina . ' to ' . $maxa . ' years old, ';
		 	$name .= $minw . ' to  '  . $maxw . ' lbs/kgs ';
		 	$this->data['Pool']['pool_name'] = $name;
		 	}
		  	$this->Pool->save($this->data);

		}
	//$this->redirect( array('action' => 'view', 'id' => $id ) );

	} //fn

	// add pools for standard weight divisions
	function add_std( $id = null , $profile = 1 ) {

		if( !$id ) {
			return;
		}

		$sql = <<< Q1
		SELECT * FROM division_setup Pool WHERE profile_id=$profile
		ORDER BY division desc, max_age, max_weight, pool_name
Q1;
		$res = $this->Pool->query($sql);
		foreach($res as $wd){
			$wd['Pool']['id'] = null;
			$wd['Pool']['event_id'] = $this->event['id'];;
			$wd['Pool']['type'] =  $this->event['default_pool_type'];
			//			debug($wd); exit(0);
			$this->Pool->create();
			$this->Pool->save( $wd, false);
		}

	$this->redirect( $this->referer() );

	} //fn

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Pool', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {

			if ($this->Pool->save($this->data)) {
				$this->Session->setFlash(__('The Pool has been saved', true));
				if( $this->request->is('ajax') ){
					$this->redirect(  array( 'action' => 'poolShow/' . $this->Pool->id ));
				}else{
					$this->redirect( array('action' => 'view',$this->Pool->id ) );
				}

			} else {
				$this->Session->setFlash(__('The Pool could not be saved. Please, try again.', true));
			}
		}

		$this->data = $this->Pool->read(null, $id);
			$elst = array(
				'fields' => array('Event.id','Event.event_name','Event.event_location')
				,'conditions' => array('Event.id'=> $this->event['id'])
				);
			$mlst = array(
					'fields' => array('Mat.id','Mat.name','Mat.location' )
					,'conditions' => array('Mat.event_id'=> $this->event['id'] )
				);


		$mats = $this->Pool->Mat->find('list', $mlst);
		$dpt = "";
		$this->set(compact('events','mats', 'dpt' ));

	} //fn

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Pool', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Pool->id = $id;
		$event = $this->Pool->field( 'event_id') ;
		if ($this->Pool->delete($id)) {
			$this->Session->setFlash(__('Pool deleted', true));
			$this->redirect(array( 'action'=>'index' ));
		}
		$this->render('index');
	} //fn

	function del_empty($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Pool', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Pool->query('DELETE FROM pools WHERE event_id=' . $id .' AND id NOT IN (SELECT pool_id FROM registrations WHERE event_id ='. $id . ')');

		$this->Pool->contain( array() );
		$pools = $this->Pool->find( 'all'
			, array(
				'conditions' => array(
					'Pool.event_id'	=> $id,
					'Pool.status'	=> 0  //open
				)
			)
		);
		//debug( $pools); exit(0);
		foreach( $pools as $p ){
			$this->Pool->save( $p, false );
		}
		$this->redirect( $this->referer());
	} //fn


function rem_reg( $rid = null ) {
		$this->Pool->recursive =0;
		//debug($this->params ); exit(0);
		if( $rid   ){

			$reg = $this->Pool->Registration->read(null, $rid );
			$reg['Registration']['auto_pool'] = 0;
			$pool_id = $reg['Registration']['pool_id'];
			$reg['Registration']['pool_id'] = 0;
			$this->Pool->Registration->save( $reg, false );
		}
		//$this->admin(	$reg['Registration']['event_id']);
			$this->redirect( array('action' => 'poolShow', $pool_id ));
	}

// seeds those without seed yet
function seed_all( $id = null, $rs = array() ){

	$this->Pool->id = $id;
	$nr = $this->Pool->Registration->find('count', array('conditions' =>array( 'pool_id' => $id)));

	$sql = "SELECT r.id, rand() as ra FROM registrations r JOIN competitors c ON c.id = r.competitor_id"
			. " JOIN (select d.club_id, count(d.club_id) as clubcount, rand() as rb
                    from registrations c join competitors d on c.competitor_id = d.id
                    where c.pool_id = $id
                    group by d.club_id) s ON s.club_id = c.club_id"
			. " WHERE r.seed NOT BETWEEN 1 AND $nr AND r.pool_id = $id"
			. " ORDER BY s.clubcount DESC , rb, ra"
			;
	$res = $this->Pool->query( $sql );
	$i = 0;
	foreach( $res as $r ){

		while( isset( $rs[ ++$i ]) ) ;

		$this->Pool->Registration->id = $r['r']['id'];
		$this->Pool->Registration->saveField( "seed", $i, false );
	}

}

function seed( $id = null ){

	$seeds = $this->data['Pool'];
	$rs = array();
	foreach( $seeds as $r => $s ){
		$this->Pool->Registration->id = $r;
		$this->Pool->Registration->saveField( "seed", $s, false );
		if( $s > 0 ) { $rs[ $s ] = $r; }
	}
	$this->seed_all( $id, $rs  );

	$this->redirect(array( 'action'=>'view',$id ));

}

# Seet pool to status 1
# Lock the pool
/*
 * Create Matches
*/

function createMatches($id = null) {

		App::import('Model', 'Bracketrule');
		App::import('Model', 'Player');

		if (!$id) {
			$this->Session->setFlash(__('Invalid Pool.', true));
			$this->redirect(array('action'=>'index'));
		}

		//Make sure we are seeded
		$this->seed_all( $id );

		$this->Pool->recursive = 0;
		$this->Pool->id = $id;
		$pt = $this->Pool->field('type');

		$pool = $this->Pool->read(null, $id);

		$count = $this->Pool->query(  "SELECT COUNT(*) as c FROM registrations r WHERE pool_id = $id");
		$rcount = $count[0][0]['c'];

	//	debug($this->Pool->data['Registration']);
		$bracket = $this->Pool->query( "SELECT id " .
				"FROM brackets AS Bracket WHERE bracket_size=(" .
				"	SELECT MIN(bracket_size) FROM brackets WHERE bracket_size  >=$rcount"
				. " AND pool_type='$pt') AND pool_type='$pt'"
				, false
		);
		$br = $bracket[0]["Bracket"]["id"];
		 $this->Pool->saveField( "bracketrule", $br, false);
	 	$pool = $this->Pool->read(null, $id);
//		$mp = $bracket[0][0]["mp"];
//		$this->Pool->save($this->data);

	 	$this->Pool->query("UPDATE registrations set match_wins=0, match_loses=0 where pool_id=" . $id);

		$this->Pool->Match->deleteAll(array('pool_id' => $id )); // delete all
		$sql =  "SELECT * FROM registrations r JOIN competitors c ON c.id = r.competitor_id WHERE pool_id = $id ORDER BY seed, club_id ";
		$regs = $this->Pool->query( $sql );

		$r_seed = array();
		$r_id = array();
		//$nr = count($regs  );
		foreach( $regs as $r ){
			$reg=$r['r'];
			$r_seed[ $reg['seed'] ] = $reg['id'];
			$r_id[ $reg['id'] ]  = 1;
		}

		$rules = $this->Pool->query("SELECT * FROM bracketrules r WHERE bracket_id=$br ORDER BY match_num");
		// create matches
		$maxMatch = 1;
		$seed = array();
	//	debug($rules); exit(0);
		foreach( $rules  as $r){

			$rule = $r['r'];
			$mn = $rule['match_num'];
			if( $mn > $maxMatch )
			{
				$maxMatch = $mn;
			}

			$m = new Match;

			$m->data["Match"]["match_num"] = $mn;

			$m->data["Match"]["pool_id"] = $this->Pool->data["Pool"]['id'];
			$m->data["Match"]["mat_id"] = $this->Pool->data["Pool"]['mat_id' ];
			$m->data["Match"]["round"] = $rule['round' ];

			$m->save();
			$mid = $m->id;
			if( isset( $rule['seed_pos_1'])){
				$sp = $rule['seed_pos_1'];
				if( $pt == 'rr' ) { $sp = $mn * 10 + $sp; }
				$seed[ $sp ] = array( 'match_id' => $mid , 'pos' => 1 , 'registration_id' => 0);
			}
			if( isset( $rule['seed_pos_2'])){
				$sp = $rule['seed_pos_2'];
				if( $pt == 'rr' ) { $sp = $mn * 10 + $sp; }
				$seed[ $sp ] = array( 'match_id' => $mid , 'pos' => 2 , 'registration_id' => 0);
			}


		}
		// sit players
		//if( $id == 5284 ){ debug($r_seed); exit(0); }
		foreach( $seed as $ix => $p ){
			$s = $ix;
			if( $pt == 'rr' ){
				$s = $s % 10;
			}
			if( isset( $r_seed[ $s ])){
				$p['registration_id'] = $r_seed[ $s ];
				$this->Pool->Match->Player->create();
				$this->Pool->Match->Player->save( array( 'Player' => $p ));
				unset( $r_id[  $r_seed[ $s ] ]);
				unset( $seed[ $ix ]);
			}
		}
		$i = -1;

		foreach( $r_id as $r => $v ){
			while( ! isset( $seed[ ++$i ]));
			$p = $seed[ $i ];
			$p['registration_id'] = $r;
			$this->Pool->Match->Player->create();
			$this->Pool->Match->Player->save( array( 'Player' => $p ));
			unset( $seed[ $i ] );
		}
		// Adding the Byes
		//debug($seed); exit(0);
		foreach( $seed as $ix => $p ){
			$s = $ix;
			if( $pt == 'rr' ){
				$s = $s % 10;
			}
			// if( isset( $r_seed[ $s ])){
			$p['registration_id'] = 0;
			$p['win_lose'] = 0 ;
				$this->Pool->Match->Player->create();
				$this->Pool->Match->Player->save( array( 'Player' => $p ));
			//	unset( $r_id[  $r_seed[ $s ] ]);
				unset( $seed[ $ix ]);
			// }
		}


	} // function

/*
 *  Lock Pool
 *  Called when the pooler wants to review the Pool
 */
function statusUnLock($id = null) {

		 	// a quick remedy
 		$this->Pool->id = $id;
		$this->Pool->saveField( 'status', 0, false );
		$this->Pool->saveField( 'auto', 1);

		$this->redirect( array('action'=>'view', $id ));
}
/*
 *  Lock Pool
 *  Called when the pooler wants to review the Pool
 */
function statusLock($id = null) {

		 	// a quick remedy
 		$this->Pool->id = $id;
		$this->Pool->saveField("status", 1, false );
		$this->Pool->saveField("mat_id", 0, false );
		$this->Pool->saveField( 'auto', 0);

		$this->createMatches( $id ) ;
		$rc1 = $this->Pool->Registration->find('count',
			array( 'conditions' =>array(
				'Registration.pool_id'	=> $id
			))
		);
		$this->Pool->savefield( 'registration_count', $rc1);

		$mc1 = $this->Pool->Match->find('count',
			array( 'conditions' =>array(
				'Match.pool_id'	=> $id
				,'Match.completed' => null
			))
		);
		$this->Pool->savefield( 'match_count', $mc1);
		$mc2 = $this->Pool->Match->find('count',
			array( 'conditions' =>array(
				'Match.pool_id'	=> $id
				,'Match.completed <>' => null
			))
		);
		$this->Pool->savefield( 'completed_count', $mc2);
		$this->redirect( array('action'=>'view', $id ));
}
/*
 *  Send to Table
 *  Pool is approved
 *  Called when the pooler wants to review the Pool
 */
function statusApproved($id = null) {

 		$this->Pool->id = $id;
		$this->Pool->saveField("status", 7, false );
		$rc1 = $this->Pool->Registration->find('count',
				array( 'conditions' =>array(
						'Registration.pool_id'	=> $id
				))
		);
		$this->Pool->savefield( 'registration_count', $rc1);

		$this->redirect( array('action'=>'setup' ));
}

function statusAwarded($id = null) {

		if (!$id) {
			$this->Session->setFlash(__('Invalid Pool.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Pool->id = $id;
		$this->Pool->saveField( 'status', 6);

	$this->redirect($this->referer());
}

/*
 * Release Pool
 * Called when the pool can be started on the Mat
 */
function statusRelease($id = null) {

		if (!$id) {
			$this->Session->setFlash(__('Invalid Pool.', true));
			$this->redirect(array('action'=>'index'));
		}
		

		$this->Pool->release( $id );
		$this->view($id );
		$this->render('view');
}

function statusHold($id = null) {

		if (!$id) {
			$this->Session->setFlash(__('Invalid Pool.', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->Pool->id = $id;
		$this->Pool->saveField( 'status', 3);
		$this->view($id );
		$this->render('view');
}

function statusComplete($id = null) {

		if (!$id) {
			$this->Session->setFlash(__('Invalid Pool.', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->Pool->id = $id;
		$this->Pool->saveField( 'status', 5);
		$this->calculatePlaces( $id );

} //fn

function statusContested($id = null) {

		if (!$id) {
			$this->Session->setFlash(__('Invalid Pool.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Pool->id = $id;
		$this->Pool->saveField( 'status', 9);

	$this->redirect($this->referer());
}

function statusReviewed( $event_id = null, $id = null,  $status  = 0 ) {

		if (!$id  ) {
			$this->Session->setFlash(__('Invalid Pool.', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->Pool->id = $id;

		$this->Pool->saveField( 'status', 8 );

		if( $this->request->is('ajax'))
			$this->redirect( array( 'action' => 'poolLst', $status));

		$this->redirect($this->referer());
	}


function print_roster( $id = null, $pid = null ) {
		$this->Pool->recursive = 0;
		$div = '';
		if( !empty($this->data )){
			$id = $this->data['Event']['id'];
			$div = $this->data['Pool']['division'];
		}

		$pa = array(
			'limit'	=> 200,
			'contain' => array(
							'Event'=>array(
								'EventFile' => array(
									'conditions' => array('EventFile.isLogo'=>'Y')) )
				,'Mat'
				,'Match' =>array( 'Player' => array('Registration' => array('Competitor'=> array ('Club'))))
				,'Registration' => array( 'Competitor' => array ('Club')) ),
    		'order' => 'Pool.min_age,Pool.min_weight',
    		'conditions' => array('Event.id' => $id  )
		);

		if( $div ){
			$pa['conditions']['Pool.division'] = $div;
		}
		if( $pid ){
			$pa['conditions']['Pool.id'] = $pid;
		}

		$this->paginate = array( 'Pool' => $pa) ;
		$this->set('event', $id);
		$this->set('pools', $this->paginate());
		$this->layout = 'display';

	} // fn
function awards($id=null){

	if( $id){
	//	$this->find_awards($id);
	}

		$this->Pool->recursive =0;
		$sex = $mina = $maxa = $minw = $maxw = '';
		$ccond = array(  );
		$rcond = array( 'Registration.bracket_pos <>' => 0 );
		$pcond = array( 'Pool.status' => array(8,6) );
		$icond = array( 'Player.win_lose' => 1, 'Player.by' => 'ippon' );

		if( !empty($this->data)){ //filter
			$id = $this->data['Event']['id'];
			//debug($this->data); exit(0);
			$div = $this->data['Pool']['division'];
			$cat = $this->data['Pool']['category'];

			if( $div ){
				$pcond['Pool.division']  = $div  ;
			}
			if( $cat ){
				$pcond['Pool.category']  = $cat  ;
			}


		}

		$pcond['Pool.event_id']  = $id;

		$this->paginate = array( 'Pool' => array(
			'limit'	=> 2000,
			'contain' => array( 'PoolStatus', 'Registration' => array(
					'conditions' => $rcond ,
					'order'		=> 'Registration.bracket_pos',
					'Competitor' => array (
						'conditions' => $ccond ,
						'Club')) ),
    		'order' => 'Pool.status DESC, Pool.max_age,Pool.max_weight,Pool.sex',
    		'conditions' => $pcond
		));

		$this->set('pools', $this->paginate('Pool'));


		$event = $this->Pool->Event->read(null,$id );
		$this->set('event',$event );

		// Loosing Session remedy
		//$this->Session->write("Event.id", $id ) ;
		//$this->Session->write("Event.name", $event['Event']['event_name'] ) ;


} //fn

function awards_lst($id=null){
	 $this->awards($id);
	$isql = "SELECT * FROM (SELECT registration_id AS reg_id, COUNT(match_id) AS ippons FROM matches_registrations mr JOIN matches m ON mr.match_id = m.id JOIN pools p ON m.pool_id = p.id WHERE p.event_id=$id AND win_lose=1 AND mr.by = 'ippon' GROUP BY registration_id) sq1";		
        $i_r = $this->Pool->query( $isql, false );
	$ippons=array();
	foreach( $i_r as $p ){

		$ippons[$p['sq1']['reg_id'] ] = $p['sq1']['ippons'];
	}
        $this->set('ippons', $ippons);
} //fn


function view($id = null, $view = 'view' ) {

	$this->Pool->contain( array( 'PoolStatus'
							,'Mat'
							,'Registration' => 'Competitor'
							,'Match' => array(
								'order' => 'Match.round,Match.match_num',
								'Player'
							)
							));

	$this->set('pool', $this->Pool->read(null, $id));
	$this->render( $view );
	} //fn


function set_match_player( $id = null ) {
		$this->Pool->recursive =0;
		$rid = $this->request->query['data']['draggedid'];
		$mid = $this->params['named']['match'];
		$pos = $this->params['named']['pos'];
		if( $id ){

			$reg = $this->Pool->read(null, $id );
			$match = $this->Pool->Match->read(null, $mid );
			foreach( $match['Player'] as $p ){
				if( $p['pos'] == $pos ){
					$this->Pool->Match->Player->id = $p['id'];
					$this->Pool->Match->Player->delete();
				}
			}

			$this->Pool->Match->Player->save(
				array( 'Player' => array(
					'match_id'			=> $mid,
					'registration_id' 	=> $rid,
					'pos' 				=> $pos
				) ), false );

		}
		$this->view($id );
		$this->render('view_matches','empty');
	} //fn

	// Manually Adjust Bracket Pos
function setPlaces( $id = null ){

	$npos = $this->data['Pool'];
	$rs = array();
	foreach( $npos as $r => $s ){
		$this->Pool->Registration->id = $r;
		$this->Pool->Registration->saveField( "bracket_pos", $s, false );

	}

	$this->redirect( $this->referer());

}


 function pdf( $id = null ){

 	    //App::import('Vendor', 'ntkfdf');
 	   // App::import('Helper', 'Html');

 		//$fdf = new NtkFdf();
		//$this->Ntkfdf->init();

 		//$html = new HtmlHelper();
 		$view = new View($this);
		$html = $view->loadHelper('Html');
		$this->Pool->contain('Registration');
 		$p = $this->Pool->read(null, $id);

 		//$this->Ntkfdf->set_value( "Event", $this->event['event_name']);
 		//$this->Ntkfdf->set_value( "Division", $p['Pool']['pool_name'] . " " . $p['Pool']['division']. " " . $p['Pool']['category']);
//Ilya
	 	//$this->Ntkfdf->set_value( "Timestamp", date('m/d/y H:i:s'));
		$fields = array ('Event' => $this->event['event_name'], 'Division' => $p['Pool']['pool_name'] . " " . $p['Pool']['division']. " " . $p['Pool']['category']);
 		
		$pt = $p['Pool']['type'];
 		$pstat = $p['Pool']['status'];
 		$ps = count( $p['Registration']);

 		$sql = "SELECT MIN(bracket_size) as size FROM brackets b WHERE pool_type='$pt' AND bracket_size >= $ps";
 		$res = $this->Pool->query($sql);
 		$bs= $res[0][0]['size'];
 		//debug($p); 		debug($res); exit(0);
 		$pdfFile = $html->url("/files/pdf/${bs}_${pt}.pdf", true);
 		$pdfPath = WWW_ROOT . "/files/pdf/${bs}_${pt}.pdf";
		$pdfName = $this->event['event_name'] . " " . 
 				$p['Pool']['pool_name'] . " " . $p['Pool']['division']. " " . $p['Pool']['category']
				. ".pdf";
 		//debug($pdfFile); exit(0);
 		//$fdf->ntk_fdf_set_file(  $pdfFile);
		//$this->Ntkfdf->set_file($pdfFile);

 		$sql = "SELECT match_num, m.round, m.status, pos, c.id, c.first_name, c.last_name , u.club_abbr , r.win_lose, r.by, e.seed
 		FROM pools p
JOIN matches m ON pool_id = p.id
JOIN matches_registrations r on r.match_id = m.id
JOIN registrations e ON e.id = registration_id
JOIN competitors c ON c.id = e.competitor_id
JOIN clubs u ON u.id = c.club_id
WHERE p.id =$id";

 		$res= $this->Pool->query($sql);

		//debug($res);exit(0);
		$wins=array();
		$loses=array();
		$points=array();

		foreach( $res  as $r){

			if( true||$r['c']['id'] || $r['m']['status'] ){

			$field =  $r['m']['match_num']   . "_" . $r['r']['pos'];
			$val = "";
			if( $r['m']['round'] == 1 &&  $r['c']['id'] <> 0 ){
				$val = $r['u']['club_abbr'] .": ";
			}
			$val .= $r['c']['first_name'] . " " . $r['c']['last_name'];
 			//$this->Ntkfdf->set_value( "$field", "$val");
			$fields[$field] = $val;
			
			if( $r['m']['round'] == 1 ){
 				//$this->Ntkfdf->set_value( "S_". $r['m']['match_num']."_". $r['r']['pos'], $r['e']['seed'] );
				$fields["S_". $r['m']['match_num']."_". $r['r']['pos']] = $r['e']['seed'];
			}
 			//debug( "$field $val");
 			if( $pt = 'rr' ){
 				if( !isset( $wins[$r['e']['seed']  ] )){
 					$wins[$r['e']['seed']  ] = $loses[$r['e']['seed']  ] = $points[$r['e']['seed']  ] = 0;
 				}
 			  if( $r['r']['win_lose']){
 			  	$wins[ $r['e']['seed'] ] ++ ;
 			  	$field =  $r['m']['match_num']   . "_wl_" . $r['r']['pos'];
 				$val =  $r['r']['by'];
 				//$this->Ntkfdf->set_value( "$field", "$val");
				$fields[$field] = $val;
 				$field =  $r['m']['match_num']   . "_scr_" . $r['r']['pos'];
 				$val2 = 1;
 				switch( $val ){
 					case 'ippon':
 							$val2= 10; break;
 					case 'wazaari':
 							$val2= 7; break;
 					case 'yuko':
 							$val2= 5; break;

 				}
 				//$this->Ntkfdf->set_value( "$field", "$val2");
				$fields[$field] = $val2;
 				$points[ $r['e']['seed'] ] += $val2;

 			 }elseif(  "". $r['r']['win_lose'] == "0"){
 			  	 $loses[ $r['e']['seed'] ] ++ ;
 			  }
			}
			}
		}
		if( $pt == 'rr' && (($pstat == 5) || ($pstat == 6) || ($pstat == 7) || ($pstat == 8) || ($pstat == 9) )){
			//	$seed = $r['seed'];
			//	$this->Ntkfdf->set_value( "tot_win_$seed",  $r['match_wins'] );
			//	$this->Ntkfdf->set_value( "tot_los_$seed",  $r['match_loses'] );
			foreach( $wins as $s => $v ){
			 	//$this->Ntkfdf->set_value( "tot_win_$s",  $wins[$s]);
				$fields["tot_win_$s"] = $wins[$s];
				//$this->Ntkfdf->set_value( "tot_los_$s",  $loses[$s]);
				$fields["tot_los_$s"] = $loses[$s];
				//$this->Ntkfdf->set_value( "tot_pts_$s",  $points[$s]);
				$fields["tot_pts_$s"] = $points[$s];

			}

		}


		if( ($pstat == 5) || ($pstat == 6) || ($pstat == 7) || ($pstat == 8) || ($pstat == 9)  ){
			// completed or awarded

		$rcond = array( 'Registration.bracket_pos <>' => 0 );
		$ccond = array();
		$this->Pool->contain( array(
		'Registration' => array(
					'conditions' => $rcond ,
					'order'		=> 'Registration.bracket_pos',
					'Competitor' => array (
						'conditions' => $ccond ,
						'Club'))
		));
		$pos = $this->Pool->read( null, $id );
		$i = 0;
		//debug( $pos); exit(0);
		foreach( $pos['Registration'] as $r){
			$c = $r['Competitor'];
			$i++;
			//$this->Ntkfdf->set_value( "winner_$i",  $c['first_name'] . " " . $c['last_name'] . " :" .  $c['Club']['club_name']);
			$fields["winner_$i"] = $c['first_name'] . " " . $c['last_name'] . " :" . $c['Club']['club_name'];


			}
		// debug($pos);exit(0);
		}

 		//$fdfStr=$fdf->ntk_get_fdf();
		//$fdfStr=$this->Ntkfdf->get_fdf();
		//$this->layout = "fdf";
		//$this->set( 'fdfStr' , $fdfStr );
		
		$poutput ='';
		$stderr = '';
		$pdfStr='';
		

			//$fdf_fn = tempnam(".", "fdf");
			$data_fn = tempnam(".", "dat");

			//$fp = fopen($fdf_fn, 'w');
			$dat=serialize($fields);
			$dp = fopen($data_fn, 'w');

			if($dp) {
			//fwrite($fp, $fdfStr);
			fwrite($dp, $dat);

			//fclose($fp);
			fclose($dp);

			// Send a force download header to the browser with a file MIME type
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=\"$pdfName\"");

			// Actually make the PDF by running pdftk - make sure the path to pdftk is correct
			// The PDF will be output directly to the browser - apart from the original PDF file, no actual PDF wil be saved on the server.
			// $create = "/usr/local/bin/pdftk $pdfPath fill_form $fdf_fn output - flatten";
			//$create = "/usr/bin/pdftk $pdfPath fill_form $fdf_fn output - flatten";
			$create = "perl " . WWW_ROOT . "/files/makepdf.pl " . $pdfPath . " " . $data_fn;
			//debug($create);
			error_log(  $create ) ;
			passthru( $create );

				
			// delete temporary fdf file;
			//unlink( $fdf_fn );
			unlink($data_fn); 
			}
			exit(0);

 		$this->layout = "pdf";
 		$this->set( 'pdfStr' , $pdfStr );

 } // function

} //class
