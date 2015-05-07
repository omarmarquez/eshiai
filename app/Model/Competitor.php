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
class Competitor extends AppModel {

//	public $displayField = 'Competitor.full_name ||" " || Competitor.last_name' ;

	public $virtualFields = array(
 //  	 'Competitor.full_name' => 'CONCAT(Competitor.first_name, " ", Competitor.last_name)'
	);
	/**
 * hasMany associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Club' => array(
			'className' => 'Club',
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


/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Registration' => array(
			'className' => 'Registration',
			'foreignKey' => 'competitor_id',
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