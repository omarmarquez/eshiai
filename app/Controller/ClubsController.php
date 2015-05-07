<?php
class ClubsController extends AppController {

	public $scaffold;
	var $helpers = array('Js' => array('className' => 'MyJs' ));
	var $components = array('RequestHandler');


	function autoComplete2( $name = null ) {
		if( isset( $this->data['Club']['club_name'] )){
			$name = strtolower( $this->data['Club']['club_name'] ) ;
		}
		if( isset( $this->request->query['term'] )){
			$name = strtolower( $this->request->query['term'] ) ;
		}

		$results = $this->Club->query( "SELECT Club.club_name  AS value"
									." FROM clubs Club"
									. " WHERE LOWER( Club.club_name) LIKE '$name%'"
									. " OR LOWER( Club.club_abbr) LIKE '$name%'"
		);
		foreach( $results as $i => $v ){
			$results[$i] = $v['Club']['value'];
		}
	 //	debug($results);
		$this->set( compact('results'));
		$this->render( "/ajax/auto_complete2" );

	} //fn



function setup( $id = null ) {

		if(!$id )
		  $id = $this->event['id'];

		$this->Club->recursive =-1;
		$sex = $mina = $maxa = $minw = $maxw = '';
		$ccond = array(  );
		$rcond = array( );
		$pcond = array( 'Club.id <>' => 0);

		$filter = array(
				'fdiv' 	=> '',
				'fsex'	=> '',
				'fmina'	=> '',
				'fmaxa'	=> '',
				'fminw'	=> '',
				'fmaxw'	=> ''
		);
		if( $this->Session->check('Club.filter')){
			$filter = $this->Session->read('Club.filter');
		}
		if( !empty($this->data)){ //filter

			//debug($this->data); exit(0);
			$sex = $this->data['Club']['sex'];
			$mina = $this->data['Club']['min_age'];
			$minw = $this->data['Club']['min_weight'];
			$maxa = $this->data['Club']['max_age'];
			$maxw = $this->data['Club']['max_weight'];
			$div = $this->data['Club']['division'];

			$filter = array(
				'fdiv' 	=> $div,
				'fsex'	=> $sex,
				'fmina'	=> $mina,
				'fmaxa'	=> $maxa,
				'fminw'	=> $minw,
				'fmaxw'	=> $maxw
			);

			}
			$this->Session->write( 'Club.filter', $filter);
			extract( $filter );
			//debug($filter);
			if( $fdiv ){
				$pcond['Club.division']  = $fdiv  ;
			}

			if(  $fsex && $fsex != 'A'){
				$ccond['Competitor.comp_sex']  = $fsex  ;
				$pcond['Club.sex']  = $fsex  ;
			}

			if( $fmina ){
				// $rcond['Registration.age >=']  = $fmina  ;
				$pcond['Club.min_age >='] = $fmina;
			}
			if( $fmaxa ){
			//	$rcond['Registration.age <=']  = $fmaxa  ;
				$pcond['Club.max_age <='] = $fmaxa;
			}
			if( $fminw ){
			//	$rcond['Registration.weight >=']  = $fminw  ;
				$pcond['Club.min_weight >='] = $fminw;
			}

			if( $fmaxw ){
			//	$rcond['Registration.weight <=']  = $fmaxw  ;
				$pcond['Club.max_weight <='] = $fmaxw;
			}

		$clubs= $this->Club->find( 'all', array(
   		'order' => 'Club.club_name,Club.club_abbr',
    		'conditions' => $pcond
			)
		);
// debug($pcond);

		$this->set(compact('clubs'));

		// Loosing Session remedy
	//	$this->Session->write("Event.id", $id ) ;
	//	$this->Session->write("Event.name", $event['Event']['event_name'] ) ;
}  //fn

 function members(  $id ) {

	if( $id != 0){

     $club = $this->Club->find('first', array(
            'conditions' => array(  'Club.id' => $id),
            'contain' => array('Competitor')
         ));

	}
        $this->set(compact('club'));

    }//fn

}