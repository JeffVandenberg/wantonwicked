<?php
/**
 * CharacterBeatRecord Fixture
 */namespace app\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;


class CharacterBeatRecordFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'character_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'record_month' => array('type' => 'date', 'null' => true, 'default' => null),
		'experience_earned' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '4,2', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'character_id' => 1,
			'record_month' => '2017-02-25',
			'experience_earned' => 1
		),
	);

}
