<?php
/**
 * SceneRequestFixture
 *
 */namespace app\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;


class SceneRequestFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10],
		'scene_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10],
		'request_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10],
		'note' => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'],
		'added_on' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'_indexes' => ['scene_id' => ['unique' => 0, 'columns' => 'scene_id'], 'request_id' => ['unique' => 0, 'columns' => 'request_id']],
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
			'scene_id' => 1,
			'request_id' => 1,
			'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'added_on' => '2015-07-05 03:19:18'
		),
	);

}
