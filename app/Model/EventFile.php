<?php
App::uses('AppModel', 'Model');
/**
 * Event Model
 *
 */
class EventFile extends AppModel {

	public $belongsTo  = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
			'dependent' => true,
			'conditions' => ''
	)
	);

}