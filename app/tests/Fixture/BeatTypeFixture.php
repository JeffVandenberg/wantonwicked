<?php
/**
 * BeatType Fixture
 */namespace app\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;


class BeatTypeFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
		'name' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'number_of_beats' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
		'admin_only' => ['type' => 'boolean', 'null' => false, 'default' => null],
		'created_by_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'updated_by_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
		'updated' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
		'_options' => ['charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB']
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'number_of_beats' => 1,
			'admin_only' => 1,
			'created_by_id' => 1,
			'created' => '2017-02-09 16:51:17',
			'updated_by_id' => 1,
			'updated' => '2017-02-09 16:51:17'
		),
	);

}
