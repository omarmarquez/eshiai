<?php
// app/Controller/UsersController.php
class UsersController extends AppController {

public $scaffold;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow( 'logout');
    }

	public function index() {
      	$listing = $this->paginate();
      	$fields = array();
      	if( !empty($listing) ){
      		$fields = array_keys( $listing[0][ $this->modelClass] );
      	}

		$this->set( compact('fields', 'listing' ) );
	}

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
	
	$this->User->contain('Event');
        $this->set('user', $this->User->read( null , $id));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
            //unset($this->request->data['User']['password']);
	$this->set('events' , $this->User->Event->find('list', array('active' => 1)));
        }
	$this->render('add');
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    public function login() {
    	if ($this->Auth->login()) {
        	$this->redirect($this->Auth->redirect());
   		 } else {
        	$this->Session->setFlash(__('Invalid username or password, try again'));
    	}
	}

	public function logout() {
//    	$this->redirect($this->Auth->logout());
		session_destroy();
    	$this->redirect(array('controller'=> 'events', 'action' =>'index'));
	}

}
