<?php
App::uses('AppModel', 'Model');
/**
 * Division Model
 *
 * @property Award $Award
 * @property Competitor $Competitor
 * @property Event $Event
 * @property Pool $Pool
 * @property Score $Score
 * @property Match $Match
 */
class Division extends AppModel {

	/**
 * hasMany associations
 *
 * @var array
 */
  var $useTable = 'division_setup';

 /**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Dprofile' => array(
			'className' => 'Dprofile',
			'foreignKey' => 'profile_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}