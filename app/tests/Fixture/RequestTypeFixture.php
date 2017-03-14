<?php
/**
 * RequestTypeFixture
 *
 */namespace app\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;


class RequestTypeFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 3],
		'name' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
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
			'name' => 'Lorem ipsum dolor sit amet'
		),
	);

}
