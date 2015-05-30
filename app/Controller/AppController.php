<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * This is a placeholder class.
 * Create the same file in app/Controller/AppController.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       Cake.Controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {

    public $helpers = array('Html', 'Form', 'Js' =>  array('Jquery'), 'Session');

    public $components = array(
		'Session'
		,'Auth' => array(
            'loginRedirect' => array('controller' => 'events', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'pages', 'action' => 'display', 'home'),
            'authorize' => array('Controller')
        )
	);

    public function beforeFilter() {

        parent::beforeFilter();
        $user=$this->Auth->User();
        if( isset($user['role']) && $user['role']==='weights'){
            if( !$this->Session->check('Event') ){ $this->Session->write('Event', $user['Event']); }

            if( !in_array($this->action, array( 'index', 'logout', 'weighIn', 'autoCompetitor'))) {

                $this->redirect(array('controller'=>'registrations','action' => 'index', $user['Event']['id'] ));

            }
        }

        $this->Auth->allow('index', 'view','autoComplete2','board','open');

		if( $this->Session->check('Event')) {
			$this->Auth->deny('board');
    	    $this->event = $this->Session->read('Event');
		}
    }

	public function isAuthorized($user) {
    	if (isset($user['role']) && $user['role'] === 'admin') {
       	 	return true; //Admin can access every action
   	 	}
    	return false; // The rest don't
	}

	public function beforeRender(){

		parent::beforeRender();

		$this->set('event_id', $this->event['id'] );
		$this->set('event_name', $this->event['event_name'] );
	}
}

