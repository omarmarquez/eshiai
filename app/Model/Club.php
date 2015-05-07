<?php
App::uses('AppModel', 'Model');
/**
 * Registration Model
 *
 * @property Award $Award
 * @property Competitor $Competitor
 * @property Event $Event
 * @property Pool $Pool
 * @property Score $Score
 * @property Match $Match
 */
class Club extends AppModel {

	/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Competitor' => array(
			'className' => 'Competitor',
			'foreignKey' => 'club_id',
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

   public $displayField = 'club_name';
}
