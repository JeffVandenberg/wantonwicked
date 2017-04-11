<?php
/**
 * Condition Fixture
 */namespace app\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;


class ConditionFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
		'name' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'source' => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'is_persistent' => ['type' => 'boolean', 'null' => false, 'default' => null],
		'description' => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'resolution' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'beat' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'created_by' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'updated_by' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false],
		'updated' => ['type' => 'datetime', 'null' => false, 'default' => null],
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
			'source' => 'Lorem ipsum dolor sit amet',
			'is_persistent' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'resolution' => 'Lorem ipsum dolor sit amet',
			'beat' => 'Lorem ipsum dolor sit amet',
			'created_by' => 1,
			'created' => '2016-10-31 02:51:39',
			'updated_by' => 1,
			'updated' => '2016-10-31 02:51:39'
		),
	);

}
