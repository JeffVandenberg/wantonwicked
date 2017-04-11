<?php
/**
 * CharacterBeat Fixture
 */namespace app\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;


class CharacterBeatFixture extends TestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'character_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'beat_type_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'beat_status_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'note' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created_by_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'updated_by_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'applied_on' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'beats_awarded' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 3, 'unsigned' => true),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'character_beats_character_id_applied_on_index' => array('column' => array('character_id', 'applied_on'), 'unique' => 0),
			'character_beats_character_id_created_index' => array('column' => array('character_id', 'created'), 'unique' => 0),
			'character_beats_created_by_id_index' => array('column' => 'created_by_id', 'unique' => 0)
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
			'beat_type_id' => 1,
			'beat_status_id' => 1,
			'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_by_id' => 1,
			'created' => '2017-02-25 17:01:47',
			'updated_by_id' => 1,
			'updated' => '2017-02-25 17:01:47',
			'applied_on' => '2017-02-25 17:01:47',
			'beats_awarded' => 1
		),
	);

}
