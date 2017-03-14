<?php
namespace lib\Cake\Test\test_app\config;

$config = array(
	'Read' => 'value',
	'Deep' => array(
		'Deeper' => array(
			'Deepest' => 'buried'
		)
	),
	'TestAcl' => array(
		'classname' => 'Original'
	)
);
