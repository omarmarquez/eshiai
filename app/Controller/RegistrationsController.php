<?php
App::uses('AppController', 'Controller');
/**
 * Registrations Controller
 *
 * @property Registration $Registration
 */
class RegistrationsController extends AppController {

	public $helpers = array('Js' => array('className' => 'MyJs' ));
	public $components = array('RequestHandler','Paypal');

	public $paginate = array(
  		'conditions' => array( 'competitor_id <>' => 0 ),
  		'fields' => array( 'id'
  		, 'Competitor.first_name',  'Competitor.last_name','Competitor.comp_sex','Competitor.club_id'
  		,'Club.club_name','Club.club_abbr'
  		,'age','weight','created','modified','rank','division'
  		,'upSkill','upWeight','upAge','approved','pool_id','competitor_id'
  		,'Pool.pool_name')

  );


  public function beforeFilter() {

        $this->Auth->allow('online','online2','online_confirm','online_confirm2','online_submit' ,'addCategory','paypalIPN');

        parent::beforeFilter();


		if( $this->Session->check('Event')) {
    	    $this->event = $this->Session->read('Event');
		}
    }

    public function isAuthorized($user){

        if ( isset($user['role']) && $user['role'] === 'weights' ){
            if( in_array($this->action,  array( 'index', 'weighIn', 'autoCompetitor'))) {
                return true; //Admin can access every action
            }
        }
        return  parent::isAuthorized($user);
    }

    /**
 * index method
 *
 * @return void
 */
	public function index() {
	//	$this->Registration->recursive = 0;
	//	$this->Registration->contain( array( 'Competitor' => array( 'Club.club_name' ) ,'Pool' ));
		$this->paginate['Registration'] = array(
				'conditions' => array(
					'competitor_id <>' => 0
					,'Registration.event_id' => $this->event['id']
				),
    			'contain' => array( 'Competitor' => array( 'Club.club_name' ) ,'Pool' ),
    	//		'order' => 'Registration.modified DESC'
		);


		$this->set('registrations', $this->paginate());

	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Registration->id = $id;
		if (!$this->Registration->exists()) {
			throw new NotFoundException(__('Invalid registration'));
		}
		$this->set('registration', $this->Registration->read(null, $id));
	}


/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Registration->id = $id;
		if (!$this->Registration->exists()) {
			throw new NotFoundException(__('Invalid registration'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Registration->save($this->request->data)) {
				$this->Session->setFlash(__('The registration has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The registration could not be saved. Please, try again.'));
			}
		} else {
			$this->Registration->contain( array( 'Competitor' => 'Club'));
			$this->request->data = $this->Registration->read(null, $id);
			$this->request->data['Competitor']['name'] =
			 $this->request->data['Competitor']['first_name']. ' ' .
			 $this->request->data['Competitor']['last_name'];
			 $this->request->data['Club'] =$this->request->data['Competitor']['Club'] ;

		}
		$competitors = $this->Registration->Competitor->find('list');
		$events = $this->Registration->Event->find('list');
		$pools = $this->Registration->Pool->find('list');
		$matches = $this->Registration->Match->find('list');
		$this->set(compact('competitors', 'events', 'pools', 'matches'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Registration->id = $id;
		if (!$this->Registration->exists()) {
			throw new NotFoundException(__('Invalid registration'));
		}
		if ($this->Registration->delete()) {
			$this->Session->setFlash(__('Registration deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Registration was not deleted'));
		$this->redirect(array('action' => 'index'));
	}


		/**
	 * Start on-line registration
	 */
	function online( $id = null ){
	 App::import( 'Model','Resource');

		$e = $this->Registration->Event->read( null, $id );
		$this->Session->write('Event',$e['Event']);
		$event = $this->Session->read('Event');
		$this->set(compact('event' ));


	} // fn

	function online2( $id = null ){
	 App::import( 'Model','Resource');

        $event_id = $id;
		$club_name = '';
		$flash_msg = 'New Competitor';
		$competitor= array('Competitor'  => array(
            'id' => ''
            ,'first_name' => ''
            ,'last_name' => ''
            ,'comp_sex' => ''
            ,'comp_dob' => ''
            ,'dob'  => false
            ,'comp_address' => ''
            ,'comp_city' => ''
            ,'comp_state' => ''
            ,'comp_zip' => ''
            ,'comp_phone' => ''
            ,'comp_fax' => ''
            ,'email' => ''
            ,'password' => ''
            ,'comp_comments' => ''
            ,'club_id' => ''
            ,'card_type' => ''
            ,'card_number' => ''
				,'rank' => false
				,'membership_type'=>false
				,'membership_number'=>false
				, 'membership_expiration'=>false
        )
        ,'Club' => array( 'id'=> null , 'club_name' => '', 'club_abbr'=>''));

		$reg = array( 'rank'=>'','division'=>'','card_type'=>'','card_number'=>'');

		$ci = array(
				'first_name' => $this->request->data['Competitor']['first_name']
				,'last_name' => $this->request->data['Competitor']['last_name']
				,'comp_sex' => $this->request->data['Competitor']['comp_sex']
				,'comp_dob' => $this->request->data['Competitor']['comp_dob']
		);
		if( is_array($ci['comp_dob'] ) ){
			$ci['comp_dob'] = $ci['comp_dob']['year'].'-'.$ci['comp_dob']['month'].'-'.$ci['comp_dob']['day'];
		}
		
		$this->Registration->Competitor->contain('Club');
		$comp = $this->Registration->Competitor->find( 'first'
			,array( 'conditions' =>  $ci )
		);


		if($comp){
			$flash_msg = "Competitor found.";
			$club_name = $comp['Club']['club_name'];
			$competitor = $comp;
  			$this->Registration->contain();
			$reg = $this->Registration->find( 'first', array(
				'order' => array( 'created DESC')
				,'conditions' => array( 'competitor_id' => $comp['Competitor']['id'])
				));

			if( !empty($reg)){
				$reg = $reg['Registration'];
			}

		}
        $competitor['Competitor']['dob'] = $ci['comp_dob'];


		$Res = new Resource;
		$this->set('ranks', $Res->find('list'
					,array(
						'fields' => array('Resource.value','Resource.title' )
						,'order' => 'sorting'
						,'conditions' => array(
							'Resource.event_type'=> 'judo'
							,'Resource.type'=> 'rank'
						)
						)
				));

		$this->Session->setFlash(__( $flash_msg, true));

		$this->set(compact('competitor','club_name','reg','event_id'));
		$this->render('online2std');
	}// fn

 function addCategory( $div = null, $cat_id = 1){
//debug($this);
 		Configure::write('debug', 0);
     //   $this->autoRender = false;
        $reg=array('division'=>$div);
        if(  $this->Session->check('cat_id')){
            $cat_id =  $this->Session->read('cat_id'  );
        }
        $cat_id++;
        $this->Session->write('cat_id', $cat_id  );

        $this->set(compact( 'reg','cat_id'));
        $this->layout='ajax';
 }//fn

 function online_submit( $id = null ){
 
 	$this->Session->setFlash(__('Confirm your Information.', true));
 	$event_id = $id;
 	debug($this->request->data);
 	if (!empty($this->request->data)) {
 
 		$data = $this->request->data ;
 
 		$this->Registration->recursive =-1;
 		$this->Registration->Event->contain();
 		//	$event_info = $this->Registration->Event->find( 'first'
 		//			, array( 'conditions' => array( 'id' => $event_id )));
 		$event_info = $this->Session->read('Event');
 
 		$reg_info = $this->request->data['Registration'];
 		$comp_info = $this->request->data['Competitor'];
 		//    $this->event = $event_info['Event'];
 
 		$this->Registration->Competitor->Club->contain();
 		$club_info = $this->Registration->Competitor->Club->find('first'
 				, array( 'conditions' =>array( 'Club.club_name' => $this->request->data['Club']['club_name'])));
 		$data['club_info'] = array( 'Club' => array(
 				'id' => false
 				,'club_name' => $this->request->data['Club']['club_name']
 				,'club_abbr' => ''
 				,'club_state' => ''
 				, 'club_city' =>''));
 		if( !empty( $club_info)){
 			$data['club_info']=  $club_info ;
 
 		}else{
 			$club_info= $data['club_info'];
 		}
 		
 		if( ! $comp_info['id'] ){
 			$this->Registration->Competitor->create();
 		}
 		$this->Registration->Competitor->save( array( 'Competitor' => $comp_info));
 		
 		$this->redirect(array( 'controller' => 'competitors', 'action' => 'register', $this->Registration->Competitor->id));
 		
 		$cid = $this->Registration->Competitor->id;
 		

 		$this->Registration->Event->id = $id;
 		$badd = $this->Registration->Event->field("paypal_button_add");
 		$istr = "";
 		foreach( array( 
 				'custom' 			=> $id . "_" .$cid,
 				'discount_amount'	=> "0.00",
 				'discount_amount2'	=> "10.00",
 				'discount_num'		=> 6,
 				'notify_url'		=> Router::url('/', true) .'/registrations/paypalIPN/',
 				'shopping_url'		=> Router::url('/', true) .'/registrations/',
 				)  as $f => $v ){
 		 	$istr .= "<input type='hidden' name='$f' value='$v'>";
 		 }
 		 
 		 $badd = str_ireplace("</form>", "$istr</form>", $badd);
 		 //	$add_regs = count($this->request->data['Registration']['cats'] ) -1 ;
 		 $add_regs = 0; 
 		 $price = $event_info['reg_price'] + $event_info['add_reg_price'] * $add_regs;
 
 		 $data['Registration']['total_price'] = $price;
 
 		 $this->set( compact( 'event_info', 'reg_info', 'comp_info', 'club_info','price' , 'event_id', 'badd'));
 		 $this->Session->write("reg_data",$data ) ;
 		// debug($data);
 	}
 } //fn
 
 		/**
	 * Confirms on-line registration
	 */
	function online_confirm( $id = null ){

	$this->Session->setFlash(__('Confirm your Information.', true));
    $event_id = $id;
	if (!empty($this->request->data)) {

		$data = $this->request->data ;

        $this->Registration->recursive =-1;
		$this->Registration->Event->contain();
	//	$event_info = $this->Registration->Event->find( 'first'
	//			, array( 'conditions' => array( 'id' => $event_id )));
		$event_info = $this->Session->read('Event');

		$reg_info = $this->request->data['Registration'];
		$comp_info = $this->request->data['Competitor'];
    //    $this->event = $event_info['Event'];

        $this->Registration->Competitor->Club->contain();
		$club_info = $this->Registration->Competitor->Club->find('first'
				, array( 'conditions' =>array( 'Club.club_name' => $this->request->data['Club']['club_name'])));
        $data['club_info'] = array( 'Club' => array(
                'id' => false
                ,'club_name' => $this->request->data['Club']['club_name']
                ,'club_abbr' => ''
                ,'club_state' => ''
                , 'club_city' =>''));
        if( !empty( $club_info)){
             $data['club_info']=  $club_info ;

        }else{
        	 $club_info= $data['club_info'];
        }

    	$add_regs = count($this->request->data['Registration']['cats'] ) -1 ;

		$price = $event_info['reg_price'] + $event_info['add_reg_price'] * $add_regs;

        $data['Registration']['total_price'] = $price;

		$this->set( compact( 'event_info', 'reg_info', 'comp_info', 'club_info','price' , 'event_id'));
        $this->Session->write("reg_data",$data ) ;
       // debug($data);
	}
	} //fn

	function make_payment( $amount = 0.0, $desc = "" )
		{
			$paymentInfo = array(
				'Member'=> array(
					'first_name'=>trim($this->data['User']['first_name']),
					'last_name'=>trim($this->data['User']['last_name']),
					'email'=>trim($this->data['User']['email_address']),
					'billing_address'=>trim($this->data['User']['billing_address']),
					'billing_address2'=>trim($this->data['User']['billing_address2']),
					'billing_country'=>trim($this->data['User']['billing_country']),
					'billing_city'=>trim($this->data['User']['billing_city']),
					'billing_state'=>trim($this->data['User']['billing_state']),
					'billing_zip'=>trim($this->data['User']['billing_zip'])
				),
				'CreditCard'=> array(
					'credit_type'=>trim($this->data['User']['credit_type']),
					'card_number'=>trim($this->data['User']['card_number']),
					'expiration_month'=>trim($this->data['User']['exp_date']['month']),
					'expiration_year'=>trim($this->data['User']['exp_date']['year']),
					'cv_code'=>trim($this->data['User']['cv_code'])
				),
				'Order'=> array(
					'theTotal'=> $amount,
					'description' => $desc
				)
			);


			$paypal_response = $this->Paypal->processPayment($paymentInfo, "DoDirectPayment",$this->Session->read('Event'));
			return $paypal_response;
			/*
			$ack = strtoupper($paypal_response["ACK"]);
			if($ack!="SUCCESS"){
				echo $error;
				$this->Session->setFlash(__('Card not accepted.', true));
				$this->render( 'online_confirm' );
				return;
			}else{
				/* successful do something here! */
				//echo '
			/*	print_r($ack);
			} */
		} // fn

        /**
     * Confirms on-line registration
     */
    function online_confirm2( $id = null ){

     	$event_id = $id;

     	$data = $this->Session->read('reg_data');
     	$event = $this->Session->read('Event');
		$payId = null;
     	if( $event['paypal_int'] ){
			$ppres =  $this->make_payment(  $data['Registration']['total_price'], "Registration for " . $event["event_name"] );
		//	debug($ppres);
			$ack = strtoupper($ppres["ACK"]);
			if($ack!="SUCCESS"){
				$this->Session->setFlash(__('Payment Failure:', true) ."<br>" . $ppres['L_LONGMESSAGE0']);
				return;
			}
			//debug($ppres);
			/*
			array(
	'TIMESTAMP' => '2012-04-05T04:12:04Z',
	'CORRELATIONID' => '600bbc5728eda',
	'ACK' => 'Success',
	'VERSION' => '87.0',
	'BUILD' => '2649250',
	'AMT' => '2.00',
	'CURRENCYCODE' => 'USD',
	'AVSCODE' => 'X',
	'CVV2MATCH' => 'M',
	'TRANSACTIONID' => '3XM30350JW7340904'
)			*/
			$this->Registration->Payment->create( );
			$ppres['event_id'] = $event['id'];
			$this->Registration->Payment->save( array('Payment' =>  $ppres ));
			$payId = $this->Registration->Payment->getLastInsertID();
		}

       $club_id = $data['club_info']['Club']['id'];
       if( !$club_id){

        $this->Registration->Competitor->Club->create();
       if( !$this->Registration->Competitor->Club->save( array( 'Club' => $data['Club'] ))){
        	$this->Session->setFlash(__('Club could not be saved.', true));
            return;
        }
        $club_id = $this->Registration->Competitor->Club->id;

        }
        $data['Competitor']['club_id'] = $club_id;
        $data['Competitor']['card_type'] = $data['Registration']['card_type'];
        $data['Competitor']['card_number'] = $data['Registration']['card_number'];

        $comp_id = $data['Competitor']['id'];
        if( !$comp_id){
            $data['Competitor']['comp_dob']= $data['Competitor']['dob'];
            $this->Registration->Competitor->create();
        }
        if( !$this->Registration->Competitor->save( array( 'Competitor' => $data['Competitor'] ))){
                $this->Session->setFlash(__('Competitor could not be saved.', true));
                return;
        }
        $comp_id = $this->Registration->Competitor->id;

        $data['Registration']['competitor_id'] = $comp_id;
        $data['Registration']['event_id'] = $this->event['id'];
        $data['Registration']['approved'] = 0;
        $data['Registration']['payment'] = $this->event['reg_price'];
        $data['Registration']['club_name'] = $data['club_info']['Club']['club_name'];
        $data['Registration']['club_abbr'] = $data['club_info']['Club']['club_abbr'];
        $strids = '';

        $c = 0 ;
        $total = 0;
        $id_arr = array();

        foreach( $data['Registration']['cats'] as $i => $r ){

            $rdata = $data['Registration'];

            if( $c++ ){
            	$data['Registration']['payment'] = $this->event['add_reg_price'];
            }
            $total += $data['Registration']['payment'];
            $rdata[ 'division' ] = $r['division'];
            $rdata[ 'upAge' ] = $r['upAge'];
            $rdata[ 'upSkill' ] = $r['upSkill'];
            $rdata[ 'upWeight' ] = $r['upWeight'];
     	    if( $payId ){
				$rdata[ 'paid' ] = 1;
				$rdata[ 'payment_id' ] = $payId;
	    	}
            $this->Registration->create();
            if( !$this->Registration->save( array( 'Registration'=>$rdata ))){
                $this->Session->setFlash(__('Registration could not be saved.', true));
                return;
            }
            $r_id = $this->Registration->id;
            $id_arr[] = $r_id;

            $strids .= "<br>" . str_pad( $r_id, 5, '0',STR_PAD_LEFT ) . " " . $r;
        }

        $Name = "registration bot"; //senders name
        $email = "registrations@judoshiai.com"; //senders e-mail adress

        $subject = $this->event['event_name']." Registration"; //subject
        $headers = "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields
        // To send HTML mail, the Content-type header must be set
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

         $mail_body ="
<html>
<head>
<title>Registration Confirmation for ".$this->event['event_name']."</title>
</head>
<body>
<h3>".$this->event['event_name']." </h3>
<hr>
<br><b>Date:</b>&nbsp; ".$this->event['event_date']."
<br><b>Address:</b>&nbsp; ".$this->event['event_address']."
<h3>".$data['Competitor']['first_name']."  ".$data['Competitor']['last_name']." </h3>
<hr>
<br><b>DOB:</b>&nbsp;".$data['Competitor']['dob']."
<br><b>Sex:</b>&nbsp;".$data['Competitor']['comp_sex']."
<br><b>Address:</b>&nbsp;".$data['Competitor']['comp_address']."
<br><br><b>Email:</b>&nbsp;".$data['Competitor']['email']."
<br><b>Phone:</b>&nbsp;".$data['Competitor']['comp_phone']."
<br><b>Club:</b>&nbsp;".$data['Club']['club_name']."" .
"<h3>Registrations</h3><hr>$strids".
"<br><b>Registration price:</b> ". money_format('%i', $data['Registration']['total_price']).
"<br></body></html>";

 foreach( array($this->event['reg_email'], $data['Competitor']['email']) as $recipient ){
    if( !trim($recipient)){
    	continue;
    }
     mail($recipient, $subject, $mail_body, $headers); //mail command :)
 }

     $this->Session->setFlash(__('Registration complete.', true));

   // $this->redirect( array( 'action' => 'online/'. $event_id));

   		$this->set('complete',true);
     	return;

    } //fn


	function checkIn( $event_id = null ){

    $comp_name = null;
    $competitors = array();

    if( !empty($this->request->data)):


        if( !empty( $this->request->data['Competitor'])):

        $comp_name = $this->request->data['Competitor']['name'];

        $competitors = $this->Registration->query(
            "SELECT DISTINCT Competitor.id, Competitor.first_name, Competitor.last_name, Competitor.comp_city, Competitor.comp_state"
            ." , Competitor.comp_sex, Competitor.comp_dob, Registration.club_name"
            . " ,Registration.card_type, Registration.card_number, Registration.card_verified, Registration.comments, Registration.approved "
            ." , Registration.paid,  Registration.rank, Registration.weight, Registration.age, Registration.division"
            ." , Registration.upAge, Registration.upSkill, Registration.upWeight, Registration.id , Club.club_name"
            . " FROM competitors Competitor"
             ." JOIN registrations Registration ON Registration.competitor_id = Competitor.id"
              ." JOIN clubs  Club ON Competitor.club_id = Club.id"
            . " WHERE Registration.event_id = $event_id AND CONCAT_WS(' ', first_name , last_name) ='$comp_name' ORDER BY competitor_id"
        );


    endif;
  endif;
  $this->set(compact( 'event_id','comp_name','competitors'));

 }  //fn

function checkIn2( $event_id = null ){


    if( !empty($this->request->data)):

      if( isset($this->request->data['Registration'])):
     // debug($this->request->data);exit(0);
/*
            $this->Registration->contain();
            $reg = $this->Registration->read( null, $this->request->data['Registration']['id']);


            	$reg['Registration']['comments'] =  $this->request->data['Registration']['comments'] ;
            	$reg['Registration']['approved'] =  $this->request->data['Registration']['approved'] ;
            	$reg['Registration']['card_verified'] =  $this->request->data['Registration']['card_verified'] ;

            $this->Registration->save( $reg );
*/

	            $this->Registration->id =  $this->request->data['Registration']['id'];
				$this->Registration->save( $this->request->data );


        		$this->Session->setFlash(__('Registration No.:'.  $this->Registration->id .' updated for ', true) . $this->request->data['Registration']['name'] );


        endif;
 	endif;
 }  //fn

 function weighIn( $event_id = null, $comp_name = null ){


    $competitors = array();

    if( !empty($this->request->data)):

      if( isset($this->request->data['Registration'])):

      	$this->Registration->contain();
            $regs = $this->Registration->find('all', array(
					'conditions' => array(
            			'Registration.event_id' => $event_id
                        ,'Registration.rtype' => 'shiai'
            			,'Registration.competitor_id' => $this->request->data['Registration']['competitor_id']
            	)
            ));
            foreach( $regs as $r ):
				
            	$r['Registration']['weight'] = $this->request->data['Registration']['weight'];
                $this->Registration->save( $r );

            endforeach;
        $this->Session->setFlash(__('Weight-In Completed for ', true) . $this->request->data['Registration']['name'] );


        elseif( !empty( $this->request->data['Competitor'])):

        $comp_name = $this->request->data['Competitor']['name'];

        $competitors = $this->Registration->query(
            "SELECT DISTINCT Competitor.id, Competitor.first_name, Competitor.last_name, Competitor.comp_city, Competitor.comp_state"
            ." , Competitor.comp_sex, Competitor.comp_dob, Club.club_name, Registration.card_type, Registration.card_number, Registration.card_verified, Registration.approved"
            . " FROM competitors Competitor"
             ." JOIN registrations Registration ON Registration.competitor_id = Competitor.id"
              ." JOIN clubs  Club ON Competitor.club_id = Club.id"
            . " WHERE Registration.event_id = $event_id AND CONCAT_WS(' ', first_name , last_name) ='$comp_name'"
        );


		if( empty( $competitors)){
			exit(0);
		}
    endif;
  endif;
  $this->set(compact( 'event_id','comp_name','competitors'));

 }  //fn


 function award( $id = null ){

 	$this->Registration->id = $id ;
 	$this->Registration->saveField('awarded', 1 );
 } //fn


 function add( $id = null, $cid = null ) {
	 App::import( 'Model','Resource');


	if (!empty($this->request->data)) {

		$id = $this->request->data['Registration']['event_id'];
		$club = $this->Session->read('Reg.Club.id');
		 if( $club == 0 ){
				$this->request->data['Club']['club_abbr'] ='___';
				$this->Registration->Competitor->Club->create();
				$this->Registration->Competitor->Club->save($this->request->data, false);
				$club = $this->Registration->Competitor->Club->id ;
				if( ! $club ){

					$this->Session->setFlash(__('The Registration/Club could not be saved. Please, try again.', true));

				}
			 }
		$this->request->data['Competitor']['club_id'] = $club; //$this->Registration->Competitor->Club->id;

		$comp = $this->Session->read('Reg.Comp.id');

		 if(  $comp == 0 ){
		 		$cn = split( ' ', $this->request->data['Competitor']['name']);
		 		$this->request->data['Competitor']['first_name'] = $cn[0];
		 		$this->request->data['Competitor']['last_name'] = $cn[1];
				$this->Registration->Competitor->create();
				$this->Registration->Competitor->save($this->request->data, false);
				$comp = $this->Registration->Competitor->id;
				if( !$comp ){

					$this->Session->setFlash(__('The Registration/Competitor could not be saved. Please, try again.', true));

				}
			 }
			// debug($this->request->data);

			$this->request->data['Registration']['competitor_id'] = $comp; //$this->Registration->Competitor->id;
			$this->request->data['Registration']['club_abbr'] = $this->request->data['Club']['club_abbr'];
			$this->Registration->create();
			if ($this->Registration->save($this->request->data)) {
				$this->Session->setFlash(__('The Registration has been saved', true));
				$this->redirect(array('action'=>'add', $id));
			} else {
				$this->Session->setFlash(__('The Registration could not be saved. Please, try again.', true));
			}
		}

		$this->set('club_name','');
		$this->set('club_abbr','');
		$this->set('comp_name','');
		if( $cid ){
			$this->Registration->Competitor->contain( array( 'Club' ) );
			$comp = $this->Registration->Competitor->read( null, $cid );
			//$this->set('club_name', $comp['Club']['club_name'] );
			//$this->set('club_abbr', $comp['Club']['club_abbr'] );
			//$this->set('comp_name', $comp['Competitor']['first_name'] . ' ' .  $comp['Competitor']['last_name']);
			$this->request->data['Competitor']['name'] =
			$comp['Competitor']['first_name']. ' ' .$comp['Competitor']['last_name'];
			$this->request->data['Club'] =$comp['Club'] ;
				
		}
		$this->Registration->recursive = -1;
		$this->paginate = array(
				'contain'	=> array( 'Pool','Competitor'=>'Club')
				,'conditions' => array('Registration.event_id' =>  $id )
				,'order'		=> 'Registration.created DESC'
				,'limit'		=> 3
			);
		$this->set('registrations', $this->paginate());


		$this->Session->write('Reg.Club.id',0);
		$this->Session->write('Reg.Comp.id',0);
		$this->set('event', $id );

		$Res = new Resource;
		$this->set('ranks', $Res->find('list'
					,array(
						'fields' => array('Resource.value','Resource.title' )
						,'order' => 'sorting'
						,'conditions' => array(
							'Resource.event_type'=> 'judo'
							,'Resource.type'=> 'rank'
						)
						)
				));
	} //fn

	function onsiteComp( $cname = null ) {

	$this->autoRender=false;

	$this->Session->write('Reg.Comp.id', 0 );
	$comp_sex = $comp_dob = '';
	if( isset( $this->request->data['Competitor']['name'])){
		$cname =  $this->request->data['Competitor']['name'];
	}
	if( isset( $this->request->data['cname'])){
		$cname =  $this->request->data['cname'];
	}

	$sid = $this->Session->read('Reg.Club.id');
	if($cname ){

		$cname = preg_replace( "/ /", "," , $cname );
		$cname .= ",";
		$cd = preg_split( '/,/' , $cname );
		//debug($sid);
		$this->Registration->Competitor->recursive=0;

		$comp =  $this->Registration->Competitor->find('first', array(
				'conditions' => array(
						 'Competitor.first_name' => trim( $cd[0] )
						,'Competitor.last_name' => trim( $cd[1] )
						,'Competitor.club_id' => $sid
				)
				,'recursive' =>	0 , 'limit' => 1
		));
	if( $comp ){
		$lastCreated = $this->Registration->find('first', array(
        		'order' => array('Registration.created' => 'desc')
        		,'conditions' => array( 'Registration.competitor_id' =>  $comp['Competitor']['id'])
        		,'recursive' => -1
    	));
    	$ret = array();
    	$c= $comp['Competitor'];
    	$ret['comp_sex'] = $c['comp_sex'];
    	$ret['comp_dob'] = $c['comp_dob'];

    	$r = $lastCreated?$lastCreated['Registration']:null;

    	$ret['rank'] = $r?$r['rank']:'';
    	$ret['division'] = $r?$r['division']:'';
    	$ret['card_type'] = $r?$r['card_type']:'';
    	$ret['card_number'] = $r?$r['card_number']:'';

		$this->Session->write('Reg.Comp.id',$comp['Competitor']['id']);

		return json_encode( $ret );
	}
		$this->set('comp_sex', $comp_sex);
		$this->set('comp_dob', $comp_dob);

	}
	} // fn


	function onsiteClub( $cname = null ) {
	$club_id = null;
	$this->Session->write('Reg.Club.id', 0 );
	$this->autoRender=false;
	if( isset( $this->request->data['Club']['club_name'])){
		$cname =  $this->request->data['Club']['club_name'];
	}
	if( isset( $this->request->data['cname'])){
		$cname =  $this->request->data['cname'];
	}
//	debug($cname);
	if($cname ){
		$club =  $this->Registration->Competitor->Club->find('first', array(
				'conditions' => array(
						'Club.club_name' => trim( $cname  )
				//		,' Club.club_abbr' => trim( $cd[0] )
				)
				,'recursive' 	=> -1
				,'limit'		=> 1
		));
//		debug( $club );
	if( $club ){
		$this->Session->write('Reg.Club.id',$club['Club']['id']);
		$club_id = $club['Club']['id'];
		return $club['Club']['club_abbr'];
	}
	$this->set( 'club_id',$club_id);
	}
	} // fn

    function autoCompetitor( $event_id = null ) {
    //Partial strings will come from the autocomplete field as
    //$this->data['Post']['subject']
    if( isset( $this->data['Competitor']['name'] )){
        $name = strtolower($this->data['Competitor']['name']);
    }
		if( isset( $this->request->query['term'] )){
			$name = strtolower( $this->request->query['term'] ) ;
		}

		$sql = "SELECT DISTINCT CONCAT( Competitor.first_name, ' ', Competitor.last_name )   AS value"
									." FROM competitors Competitor JOIN registrations R ON Competitor.id = R.competitor_id"
									. " WHERE Competitor.id <> 0 AND R.event_id =" . $this->event['id'];


		$sql .= " AND (	LOWER( CONCAT( Competitor.first_name, ' ',Competitor.last_name )) LIKE '%$name%' )";

		$results = $this->Registration->query( $sql );
		/*
		$this->Registration->Competitor->contain("Registration.event_id = " . $this->event['id']);
		$results = $this->Registration->Competitor->find('list', array(
				'fields' => array( "CONCAT( Competitor.first_name, ' ',Competitor.last_name )" )
				,'conditions' => array(
						"LOWER(CONCAT( Competitor.first_name, ' ',Competitor.last_name )) LIKE" => "%$name%"
				)
		));
		*/
		foreach( $results as $i => $v ){
			$results[$i] = $v[0]['value'];
		}
	 //	debug($results);
		$this->set( compact('results'));
		$this->render( "/ajax/auto_complete2" );
        } //fn


	public function paypalIPN( $id=null ){
		/*
		 * Implements the PayPal Payment Notification Protocol
		 */
		
		$this->autoRender = false;
		// STEP 1: Read POST data
		
		// reading posted data from directly from $_POST causes serialization
		// issues with array data in POST
		// reading raw POST data from input stream instead.
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
				
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
			$keyval = explode ('=', $keyval);
			if (count($keyval) == 2)
				$myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) {
			if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
		}
		
		
		// STEP 2: Post IPN data back to paypal to validate
		$urlIPN = 'https://www.paypal.com/cgi-bin/webscr';
		if( isset( $_POST['test_ipn'] )  && $_POST['test_ipn']== 1){
			$urlIPN = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		$ch = curl_init( $urlIPN );
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		// In wamp like environments that do not come bundled with root authority certificates,
		// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
		// of the certificate as shown below.
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		$res = "NONE";
		if( !($res = curl_exec($ch)) ) {
			error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close($ch);
			exit;
		}
		curl_close($ch);
		
		// file_put_contents('php://stderr', print_r($_POST, TRUE));
		
		
		// STEP 3: Inspect IPN validation result and act accordingly
		
		if (strcmp ($res, "VERIFIED") == 0) {
			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your Primary PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment
			//$item_name = $_POST['item_name'];
			// assign posted variables to local variables
			/*
			file_put_contents('php://stderr', print_r( $_POST, TRUE));
			$item_name = $_POST['item_name1']?$_POST['item_name1']:"";
			$item_number = $_POST['item_number1']?$_POST['item_number1']:"";
			$payment_status = $_POST['payment_status']?$_POST['payment_status']:"";
			$payment_amount = $_POST['mc_gross']?$_POST['mc_gross']:0.0;
			$payment_currency = $_POST['mc_currency']?$_POST['mc_currency']:"USD";
		
			$txn_id = $_POST['txn_id'];
			$receiver_email = $_POST['receiver_email'];
			$payer_email = $_POST['payer_email'];
			
			*/
			file_put_contents('php://stderr', print_r(array( "DEBUG" => $_POST ) , TRUE));

			$custom = split( "_", $_POST['custom']?$_POST['custom']:$_POST['transaction_subject']?$_POST['transaction_subject']:false );
			$eventID = $custom[0];
			$compID = $custom[1];
			
			//Create Payment
			$pdata = array(
				'event_id' => $eventID,
				'competitor_id' => $compID,			
				'payment_status' => $_POST['payment_status'],
				'payer_email' => $_POST['payer_email'],
				'AMT' => $_POST['mc_gross'],
				'TRANSACTIONID' => $_POST['txn_id'],
			);
			
			$this->Registration->Payment->create();
			$this->Registration->Payment->save( array('Payment' =>  $pdata ));
			
			$payID = $this->Registration->Payment->getLastInsertID();
			// Create Registrations
			$rdata = array(
					'event_id' 		=> $eventID,
					'competitor_id' => $compID,
					'paid' 			=> 1,
					'payment_id' 	=> $payID,
			);
			
			$items=array();
			if( isset($_POST['num_cart_items'])){
				for( $i = 1; $i <= intval($_POST['num_cart_items'] ) ; $i++ ){
					$items[] = $i;
				}
			}else{
				$items[] = "";
			}
			
			foreach( $items as  $item ){
				
				$qty = intval($_POST['quantity' .$item ]);
				
				$rdata['rtype'] = strtolower($_POST['option_selection1_' .$item ] );
				$rdata['division'] = strtolower($_POST['option_selection2_' .$item ] );
				$comp = split( ' ', $_POST['option_selection3_' .$item ]);
				$rdata['competitor_id'] = $comp[0];
				$rdata['comments'] = strtolower( 'User: ' . $_POST['option_selection4_' .$item ] );
				
				file_put_contents('php://stderr', print_r( array('DEBUG' => 'Starting save '.$item, 'rdata' =>$rdata), TRUE));
				
				for( $i = 0 ; $i < $qty ; $i++ ){
				
				//unset( $this->data[ 'Registration' ] );
				
				//$this->Registration->id = false;
				$this->Registration->create();
				$this->Registration->contain();
				$reg = $this->Registration->save( array( 'Registration'=>$rdata ));
			
				file_put_contents('php://stderr', print_r($reg, TRUE));
				
				if( !$reg){
					file_put_contents('php://stderr', print_r( 'Registration could not be saved.', TRUE));
					$this->Session->setFlash(__('Registration could not be saved.', true));
					//return;
				}
			}
			}
			
		} else if (strcmp ($res, "INVALID") == 0) {
			// log for manual investigation
		}
		
	}
	
/*
 * Allows to copy registrations from an event to other for testing purporses
 */	
	public function event_dup($id = null) {

		if ($this->request->is('post') || $this->request->is('put')) {
			
			$event_info = $this->Session->read('Event');
			$sid = $this->request->data['Registration']['event'];
			
			$rlst = $this->Registration->find('all', array('conditions' => array('Registration.event_id' => $sid )));
			
			foreach( $rlst as $r ){
				
				unset($r['Registration']['id'] );
				$r['Registration']['event_id'] = $event_info['id'];
				$r['Registration']['pool_id'] = 0;
				$r['Registration']['match_wins'] = 0;
				$r['Registration']['match_loses'] = 0;
							if(!$r['Registration']['card_type'] ){
					$r['Registration']['card_type']='UNKNOWN';
				}
				if(!$r['Registration']['rtype'] ){
					$r['Registration']['rtype']='shiai';
				}
				$this->Registration->create();
				
				if( !$this->Registration->save( $r )){
					$this->Session->setFlash(__('The registration could not be saved. Please, try again.'));
					break;
				}
			}
			$this->redirect(array('action' => 'index'));
			debug($event_info);debug( $rlst); exit(0);
			
		} else {
			
			
	
	
		}

		
		$events = $this->Registration->Event->find('list'
				, array( 
						'fields' => array( 'Event.id', 'Event.event_name', 'Event.event_date'),
						'order' => 'Event.event_date DESC'
						
						)
				
				);

		$this->set(compact( 'events' ));
	}
	
	
/*
 * Allows to import registrations from WP CSV Export
 */	
	public function import_wp_regs($id = null) {


		if ($this->request->is('post') || $this->request->is('put')) {
			
			//debug( $this->request->data );
			$ucid=0;
			$uc = $this->Registration->Competitor->Club->find( 'first'
								,array( 'conditions' =>  array( 'club_name' =>  "UNKNOWN"  )
						));
			if( $uc )
				$ucid = $uc->id;
				
    		$event_info = $this->Session->read('Event');
			$row = 1;
			$csvHead = null;
			ini_set('auto_detect_line_endings',TRUE);
			if (($handle = fopen(  $this->request->data["Registration"]["cfile"]["tmp_name"], "r")) !== FALSE) {
				
    			while (($data = fgetcsv($handle, 2000)) !== FALSE) {
    				if( !$csvHead ){
    					$csvHead = $data;
    					continue;
    				}
    				$partID= $data[0];
    				if( $partID == 'End of Record!' )
    					break;
    					
    				if( $this->Registration->find( 'first', array( 'conditions' =>
                        array(
                            'event_id' =>  $event_info['id'],
                            'participant_id' =>  $partID,
                        ) ) ) )
                    {
    					
    					continue;
    				}
    				$dob = date( "Y-m-d", strtotime(  $data[ 20 ] ));
    				$gender = $data[ 21 ]=='Male'?'M':$data[ 21 ]=='M'?'M':'F';
    				$lname = trim( str_replace( '\\','', $data[ 3 ])  );
    				$fname = trim( str_replace( '\\','', $data[ 4 ] ) );
    				
    				$csearch = array(
						'LOWER(first_name)' => strtolower($fname)
						,'LOWER(last_name)' => strtolower($lname)
						,'comp_sex' => $gender
						,'comp_dob' => $dob
					);
    				$cdata = array(
						'first_name' => $fname
						,'last_name' => $lname
						,'comp_sex' => $gender
						,'comp_dob' => $dob
					);
					
    				
    				$this->Registration->Competitor->contain('Club');
					$comp = $this->Registration->Competitor->find( 'first'
						,array( 'conditions' =>  $csearch )
					);
					
					if( !$comp){
						
						$cdata['club_id'] = $ucid;  // UNKNOWN
						$club = $this->Registration->Competitor->Club->find( 'first'
								,array( 'conditions' =>  array( 'LOWER(club_name) LIKE' => '%'.strtolower( trim(  $data[25]  ) ) .'%')
						));
						if( $club )
							$cdata['club_id'] = $club['Club']['id'];
							
						$this->Registration->Competitor->create();
						$this->Registration->Competitor->save( array( 'Competitor' => $cdata ), false);
						$this->Registration->Competitor->contain('Club');
						$comp = $this->Registration->Competitor->find( 'first'
							,array( 'conditions' =>  $csearch )
						);
						
					}else{
						
						//debug( $comp );
					}
    				$tickets = $data[19];
    				$cats = $data[23];
    				//||1 REG-Shiai Registration USD 45.00||0 DSC-Additional Shiai Registrations USD 20.00||1 REG-Kata Registration USD 20.00||1 DSC-Additional Kata Registrations USD 
    				$i= $regs_shiai = $regs_kata= $shiai_ct = 0;
    				if( preg_match( "/(?P<regs>\d+) REG-Shiai /i", $tickets, $m )){
    					$regs_shiai += $m['regs'];
    				}
    				if( preg_match( "/(?P<regs>\d+) DSC-Additional Shiai /i", $tickets, $m )){
    					$regs_shiai += $m['regs'];
    				}
    				if( preg_match( "/(?P<regs>\d+) REG-Kata /i", $tickets, $m )){
    					$regs_kata += $m['regs'];
    				}
    				if( preg_match( "/(?P<regs>\d+) DSC-Additional Kata /i", $tickets, $m )){
    					$regs_kata += $m['regs'];
    				}
    				$ct = $regs_total = $regs_shiai + $regs_kata;
    				$cats_data = split(",",  $data[23]) ;  
    				$lc = count( $cats_data );
    				
    				$kata_names = split(",", $data[30] );
    				$kc = count( $kata_names) - 1 ;
    				$kata_partners = trim( str_replace( '\\','', $data[ 31 ])  );
    				$kata_count = 0;
    				
    				while( $regs_total > 0 ){   		
    					$j = $i +1;
    					$reg_data=array(
    						'approved'		=> 1,
    						'aut_pool'		=> 0,
    						'card_verified'		=> 0,
    						'event_id' 		=> $event_info['id'],
    						'participant_id'=> $partID,
    						'competitor_id' => $comp['Competitor']['id'],
    						'club_name' 	=> $comp['Club']['club_name'],
    						'club_abbr' 	=> $comp['Club']['club_abbr'],
    						'comments'		=> 'ONLINE ' .$j. ' of ' . $ct 
    											. "\n Categories: ". $cats 
    											. "\n Sensei: ". $data[26] 
    											. "\n Weight: ". $data[22] 
    											. "\n Needs: ". $data[29] 
    											. "\n". $tickets,
    						'rank'			=> $data[24],
    						'payment'		=> $data[18],
    					    'paid'			=> 0, //!!!
    					    'card_type'		=> $data[27],
    						'card_number'	=> $data[28]
    					
    					);
    					
    					$reg_data['division'] =  strtolower( $i < $lc ? $cats_data[ $i ] :  $cats_data[ $lc - 1 ] );
    					if( $regs_shiai){
    						$shiai_ct++;
    						if( $shiai_ct > 1 )
    							$reg_data['upSkill'] = 'Y';
    						$regs_shiai--;
    						$reg_data['rtype'] = 'shiai';
    					}elseif ($regs_kata) {
    						$regs_kata--;
    						$reg_data['rtype'] = 'kata';
    						$reg_data[ 'kata_name'] = strtolower(  $kata_names[ $kata_count ]);
    						$kata_count++;
    						if( $kata_count == $kc ) 
    							$kata_count = 0;
    						$reg_data[ 'kata_partner'] = $kata_partners;
    						
    					}
    					//debug( $reg_data);
    					$this->Registration->create();
    					if( !$this->Registration->save( array( 'Registration' => $reg_data ) )){
							$this->Session->setFlash(__('A registration could not be saved. Please, try again.'));
							debug( $comp );
							debug( $reg_data );
							return;
						}
    					$regs_total--;
    					$i++;
    				}  // while
    			}
    			ini_set('auto_detect_line_endings',FALSE);
    			fclose($handle);
			}
			$this->Session->setFlash(__('Import completed succesfully!.'));
			$this->redirect(array('action' => 'index'));
			//$this->redirect($this->referer());
		} else {
			
			$this->set( 'event_id', $id );	
	
		}

	} //import regs
	
	/*
 * Allows to import payments from WP CSV Export
 */	
	public function import_wp_pmts($id = null) {


		if ($this->request->is('post') || $this->request->is('put')) {
			
			//debug( $this->request->data );
			$ucid=0;
			$uc = $this->Registration->Competitor->Club->find( 'first'
								,array( 'conditions' =>  array( 'club_name' =>  "UNKNOWN"  )
						));
			if( $uc )
				$ucid = $uc->id;
				
			$event_info = $this->Session->read('Event');
			$row = 1;
			$csvHead = null;
			ini_set('auto_detect_line_endings',TRUE);
			if (($handle = fopen(  $this->request->data["Registration"]["cfile"]["tmp_name"], "r")) !== FALSE) {
				
    			while (($data = fgetcsv($handle, 2000)) !== FALSE) {
    				 //debug($data);
    				if( !$csvHead ){
    					$csvHead = $data;
    					continue;
    				}
    				$partID= $data[0];
    				$balance = (int)$data[6];
    				if( $partID == 'End of Record!' )
    					break;
    					
    				if( $balance ){
    					continue;
    				}
    				$reg = $this->Registration->find( 'first', array( 'conditions' => array( 'participant_id' =>  $partID ) ) );
    				if( $reg ){
    					
    					 //debug( $reg ); exit( 0 );
    					 $this->Registration->id = $reg['Registration']['id'] ;
 						 $this->Registration->saveField('paid', 1 );
    				}
	
    			} //while
    			
			} // if
			$this->Session->setFlash(__('Import completed succesfully!.'));
			$this->redirect(array('action' => 'index'));
			//$this->redirect($this->referer());
			
		}// if
		
	} //fn

/*
 * Fixing Clubs
 */	
	public function import_wp_clubs($id = null) {


		if ($this->request->is('post') || $this->request->is('put')) {
			
			//debug( $this->request->data );
			$ucid=0;
			$uc = $this->Registration->Competitor->Club->find( 'first'
								,array( 'conditions' =>  array( 'club_name' =>  "UNKNOWN"  )
						));
			if( $uc )
				$ucid = $uc->id;
				
			$event_info = $this->Session->read('Event');
			$row = 1;
			$csvHead = null;
			ini_set('auto_detect_line_endings',TRUE);
			if (($handle = fopen(  $this->request->data["Registration"]["cfile"]["tmp_name"], "r")) !== FALSE) {
				
    			while (($data = fgetcsv($handle, 2000)) !== FALSE) {
    				 //debug($data);
    				if( !$csvHead ){
    					$csvHead = $data;
    					continue;
    				}
    				$partID= $data[0];
    				$balance = (int)$data[6];
    				if( $partID == 'End of Record!' )
    					break;
    					
    				if( $balance ){
    					continue;
    				}
    				$reg = $this->Registration->find( 'first', array( 'conditions' => array( 'participant_id' =>  $partID ) , 'contain' => 'Competitor' ) );
    				if( $reg ){
    					
    					 if( $reg['Competitor']['club_id'] )
    					   continue;
 						$club = $this->Registration->Competitor->Club->find( 'first'
								,array( 'conditions' =>  array( 'LOWER(club_name) LIKE' => '%'.strtolower( trim(  $data[25]  ) ) .'%')
						));
						if( $club ){
							$reg['Competitor']['club_id'] = $club['Club']['id'];
							$reg['Registration']['club_name'] = $club['Club']['club_name'];
							$reg['Registration']['club_abbr'] = $club['Club']['club_abbr'];
							 $this->Registration->saveAssociated( $reg );
						}
   					 	 debug( $reg );

    				}
	
    			} //while
    			
			} // if
			$this->Session->setFlash(__('Import completed succesfully!.'));
			exit(0);
			$this->redirect(array('action' => 'index'));
			//$this->redirect($this->referer());
			
		}// if
		
	} //fn
	
/*
 * Allows to import registrations from WP CSV Export Gravity Forms
 */
	public function import_wp_regs_gf($id = null) {

        $import_errors=array();

		if ($this->request->is('post') || $this->request->is('put')) {

			//debug( $this->request->data );
			$ucid=0;
			$uc = $this->Registration->Competitor->Club->find( 'first'
								,array( 'conditions' =>  array( 'club_name' =>  "UNKNOWN"  )
						));
			if( $uc )
				$ucid = $uc->id;

    		$event_info = $this->Session->read('Event');
			$row = 1;
			$csvHead = null;
			ini_set('auto_detect_line_endings',TRUE);
			if (($handle = fopen(  $this->request->data["Registration"]["cfile"]["tmp_name"], "r")) !== FALSE) {

    			while (($data = fgetcsv($handle, 4000)) !== FALSE) {
    			   // debug($data);
    				if( !$csvHead ){
    					$csvHead = $data;
    					continue;
    				}
					$num_comps = $data[0];
					$row = array_combine( $csvHead, $data);

    				//$partID= $data[0];
					$partID = md5(implode(",", $data));
					//$partID = crc32(implode(",", $data));
    				if( $this->Registration->find( 'first', array(
                        'conditions' => array(
                            'event_id' =>  $event_info['id'],
                            'participant_id' =>  $partID
                        )
                    ) ) ) {

    					continue;
    				}

    			for( $i = 1 ; $i <= $num_comps ; $i++ ){

    				$dob = date( "Y-m-d", strtotime(  $row[ "$i Date of Birth"] )); // date( "Y-m-d", strtotime(  $data[ 20 ] ));
    				$gender =  $row["$i Gender"]=='Male'?'M':'F'; // $data[ 21 ]=='Male'?'M':$data[ 21 ]=='M'?'M':'F';
    				$lname = trim( str_replace( '\\','', $row["$i Competitor Name (Last)"])); // trim( str_replace( '\\','', $data[ 3 ])  );
    				$fname = trim( str_replace( '\\','', $row["$i Competitor Name (First)"])); // trim( str_replace( '\\','', $data[ 4 ] ) );

    				$csearch = array(
						'LOWER(first_name)' => strtolower($fname)
						,'LOWER(last_name)' => strtolower($lname)
						,'comp_sex' => $gender
						,'comp_dob' => $dob
					);
    				$cdata = array(
						'first_name' => $fname
						,'last_name' => $lname
						,'comp_sex' => $gender
						,'comp_dob' => $dob
					);


    				$this->Registration->Competitor->contain('Club');
					$comp = $this->Registration->Competitor->find( 'first'
						,array( 'conditions' =>  $csearch )
					);

					if( !$comp){

						$cdata['club_id'] = $ucid;  // UNKNOWN
						$club = $this->Registration->Competitor->Club->find( 'first'
								,array( 'conditions' =>  array( 'LOWER(club_name) LIKE' => '%'.strtolower( trim(  $row["Dojo / Club"])) .'%')
						));
						if( $club )
							$cdata['club_id'] = $club['Club']['id'];

						$this->Registration->Competitor->create();
						$this->Registration->Competitor->save( array( 'Competitor' => $cdata ), false);
						$this->Registration->Competitor->contain('Club');
						$comp = $this->Registration->Competitor->find( 'first'
							,array( 'conditions' =>  $csearch )
						);

					}
                    /*
					 else{

						debug( $comp );
					}
                    */
					$kata_count = 0;
					$shiai_count = 0;
					$prevDiv = '';
					foreach( array(
						"Please select Competitor $i's division:" => 'shiai',
						"$i Please select the second division:" => 'shiai',
						"$i Please select the third division:" => 'shiai',
						"$i Kata Form" => 'kata',
						"$i Second Kata Form" => 'kata',
						"$i Third Kata Form" => 'kata'
						) as $div => $r_type ){

						if( ! $row[$div] ) continue
						;
						$div_name = strtolower( explode(' ', $row[$div])[0] );
						if($div_name!='open'){
							$div_name .= 's';
						}
						$c_type = 'OTHER';
						switch( $row["$i Judo Affiliation"] ){

							case "USA Judo":
								$c_type = "USA Judo";
								break;

							case "United States Judo Federation":
								$c_type = "USJF";
								break;
						}

						$k_p='';
						if( $r_type =='kata') {
							$kata_count++;
							$k_p = $row[ "$i Tori Name"] .' & '. $row[ "$i Uke Name"];
							if( $kata_count > 1 )
								$k_p = $row[ "$i Tori Name $kata_count"] .' & '. $row[ "$i Uke Name $kata_count"];

						}

						//get 2nd division preference
						if( $r_type =='shiai') {
							$up_w = 0;
							$up_s = 0;
							$up_a = 0;
							$shiai_count++;
							if ( $shiai_count == 1)
								$firstDiv = $div_name;
							if ( $shiai_count > 1 && ( $div_name == $firstDiv || $div_name == $prevDiv )) {
								$prefType = '';
								switch ($shiai_count) {
									case 2:
										$prefType = $row["$i If the second division is the same as the first, what is your preference?"];
										break;
									case 3:
										$prefType = $row["$i If the third division is the same as one of the others, what is your preference?"];
										break;
								}
								switch ( $prefType ) {
									case 'No preference':
										$up_w = 1;
										$up_s = 1;
										$up_a = 1;
										break;
									case 'Up in weight':
										$up_w = 1;
										break;
									case 'Up in age':
										$up_a = 1;
										break;
									case 'Up in rank':
										$up_s = 1;
										break;
								}
							}
							$prevDiv = $div_name;
							
						}
						
    					$reg_data=array(
 						'rtype'         => $r_type,
 						'division'      => $div_name,
						'approved'		=> 1,
						'auto_pool'		=> 0,
						'card_verified'		=> 0,
						'upSkill'		=> $up_s == 1 ? 'Y' : 'N',
						'upAge'			=> $up_a == 1 ? 'Y' : 'N',
						'upWeight'		=> $up_w == 1 ? 'Y' : 'N',
 						'auto_pool'		=> $up_a == 1 || $up_w ==1 || $up_s == 1 ? 0 : 1,
						'event_id' 		=> $event_info['id'],
						'participant_id'=> $partID,
    						'competitor_id' => $comp['Competitor']['id'],
    						'club_name' 	=> $comp['Club']['club_name'],
    						'club_abbr' 	=> $comp['Club']['club_abbr'],
    						'comments'		=> 'ONLINE '
    											. "\n Category: ". $row[$div]
    											. "\n Sensei: ". $row["Sensei / Instructor"]
    											. "\n Needs: ". $row["$i Please let us know what type of assistance or accommodation is requested, and/or provide the name of the person who will be assisting you."]
    											. "\n "
    											,
    						'rank'			=> $row["$i Belt Color"],
    						'payment'		=> $row["Payment Amount"],
    						'paid'  		=> $row["Payment Status"],
 						'card_type'		=> $c_type,
    						'card_number'	=> $row["$i Judo Card Number"],
    						'kata_name'     => $r_type === 'kata'?explode(' - ',$row[$div])[0]:'',
    						'kata_partner'  => $k_p

    					);
						//debug($reg_data); continue;
						$this->Registration->create();
    					if( !$this->Registration->save( array( 'Registration' => $reg_data ) )){
							$this->Session->setFlash(__('A registration could not be saved. Please, try again.'));
                            array_push( $import_errors,
                                array( 'competitor' => $comp, 'registration'=> $reg_data )
                            );
						}
						}

	    			}
    			}
    			ini_set('auto_detect_line_endings',FALSE);
    			fclose($handle);
			}
			$this->Session->setFlash(__('Import completed succesfully!.'));
			//$this->redirect(array('action' => 'index'));
			$this->redirect($this->referer());
		} else {

			$this->set( 'event_id', $id );

		}

        debug($import_errors);
        $this->set( 'import_errors', $import_errors );
	} //import regs

}// class
