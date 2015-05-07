<?php
App::uses('AppModel', 'Model');
/**
 * DivisionProfile Model
 *
 * @property Award $Award
 * @property Competitor $Competitor
 * @property Event $Event
 * @property Pool $Pool
 * @property Score $Score
 * @property Match $Match
 */
class Dprofile extends AppModel {

	/**
 * hasMany associations
 *
 * @var array
 */
  	var $useTable = 'division_profiles';
	var $displayField = 'title';
 /**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Division' => array(
			'className' => 'Division',
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