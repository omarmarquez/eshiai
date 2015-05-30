<?php
App::uses('AppModel', 'Model');
/**
 * Event Model
 *
 */
class Event extends AppModel {

    var $order = "event_date DESC";

   	var $validate = array(
		'event_type' => array('notempty'),
		'event_name' => array('notempty'),
		'event_location' => array('notempty'),
		'event_address' => array('notempty'),
		'mats' => array('numeric'),
		'event_pass' => array('notempty')
	);

/**
 * Display field
 *
 * @var string
 */
	public $displayField = "event_name";

		public $hasMany = array(
		'EventFile' => array(
			'className' => 'EventFile',
			'foreignKey' => 'event_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Pool' => array(
			'className' => 'Pool',
			'foreignKey' => 'event_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Registration' => array(
			'className' => 'Registration',
			'foreignKey' => 'event_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);
}
