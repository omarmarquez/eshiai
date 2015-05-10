<?php
// app/Model/User.php
class User extends AppModel {
    public $name = 'User';
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required',
                'on' => 'create', // Limit validation to 'create' or 'update' operations
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('admin', 'user', 'weights')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        ),
        'verify_password' => array(
            'equaltofield' => array(
                'rule' => array('equaltofield','password'),
                'message' => 'Require the same value to password.',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                // 'on' => 'create', // Limit validation to 'create' or 'update' operations
            )
        )
    );

    public function equaltofield($check,$otherfield)
    {
        //get name of field
        $fname = '';
        foreach ($check as $key => $value){
            $fname = $key;
            break;
        }
        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname];
    }

    public function beforeSave( $options = Array()) {

    if (isset($this->data[$this->alias]['password'])) {
        $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    }
    return true;
    }

    public $belongsTo = array(

        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
