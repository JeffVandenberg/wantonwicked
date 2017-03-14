<?php
/**
 * SceneFixture
 *
 */namespace app\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;


class SceneFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10],
		'name' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'summary' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'run_by_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
		'run_on_date' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'description' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'is_closed' => ['type' => 'boolean', 'null' => true, 'default' => null],
		'created_by_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10],
		'created_on' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'updated_by_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10],
		'updated_on' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'_indexes' => ['run_by_id' => ['unique' => 0, 'columns' => 'run_by_id']],
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
			'summary' => 'Lorem ipsum dolor sit amet',
			'run_by_id' => 1,
			'run_on_date' => '2015-07-05 03:17:10',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'is_closed' => 1,
			'created_by_id' => 1,
			'created_on' => '2015-07-05 03:17:10',
			'updated_by_id' => 1,
			'updated_on' => '2015-07-05 03:17:10'
		),
	);

}
