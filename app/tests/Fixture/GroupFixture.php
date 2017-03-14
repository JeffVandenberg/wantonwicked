<?php
/**
 * GroupFixture
 *
 */namespace app\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;


class GroupFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10],
		'name' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
		'group_type_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 3],
		'is_deleted' => ['type' => 'boolean', 'null' => false, 'default' => null],
		'created_by' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10],
		'_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
		'_options' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB']
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
			'group_type_id' => 1,
			'is_deleted' => 1,
			'created_by' => 1
		),
	);

}
