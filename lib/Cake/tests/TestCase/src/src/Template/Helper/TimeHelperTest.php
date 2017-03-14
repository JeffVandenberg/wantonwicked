<?php
/**
 * TimeHelperTest file
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://book.cakephp.org/2.0/en/development/testing.html CakePHP(tm) Tests
 * @package       Cake.Test.Case.View.Helper
 * @since         CakePHP(tm) v 1.2.0.4206
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */namespace lib\Cake\Test\TestCase\Template\Helper;

use Cake\Core\App;
use Cake\Core\Plugin;


use App\View\Helper\TimeHelper;
use Cake\View\View;
use App\Utility\Time;

/**
 * TimeHelperTestObject class
 *
 * @package       Cake.Test.Case.View.Helper
 */
class TimeHelperTestObject extends TimeHelper {

	public function attach(CakeTimeMock $cakeTime) {
		$this->_engine = $cakeTime;
	}

	public function engine() {
		return $this->_engine;
	}

}

/**
 * CakeTimeMock class
 *
 * @package       Cake.Test.Case.View.Helper
 */
class CakeTimeMock {
}

/**
 * TimeHelperTest class
 *
 * @package       Cake.Test.Case.View.Helper
 */
class TimeHelperTest extends TestCase {

	public $Time = null;

	public $Time = null;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->View = new View(null);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->View);
		parent::tearDown();
	}

/**
 * test Time class methods are called correctly
 *
 * @return void
 */
	public function testTimeHelperProxyMethodCalls() {
		$methods = array(
			'convertSpecifiers', 'convert', 'serverOffset', 'fromString',
			'nice', 'niceShort', 'daysAsSql', 'dayAsSql',
			'isToday', 'isThisMonth', 'isThisYear', 'wasYesterday',
			'isTomorrow', 'toQuarter', 'toUnix', 'toAtom', 'toRSS',
			'wasWithinLast', 'gmt', 'format', 'i18nFormat',
		);
		$Time = $this->getMock('CakeTimeMock', $methods);
		$Time = new TimeHelperTestObject($this->View, array('engine' => 'CakeTimeMock'));
		$Time->attach($Time);
		foreach ($methods as $method) {
			$Time->expects($this->at(0))->method($method);
			$Time->{$method}('who', 'what', 'when', 'where', 'how');
		}

		$Time = $this->getMock('CakeTimeMock', array('timeAgoInWords'));
		$Time = new TimeHelperTestObject($this->View, array('engine' => 'CakeTimeMock'));
		$Time->attach($Time);
		$Time->expects($this->at(0))->method('timeAgoInWords');
		$Time->timeAgoInWords('who', array('what'), array('when'), array('where'), array('how'));
	}

/**
 * test engine override
 *
 * @return void
 */
	public function testEngineOverride() {
		App::build(array(
			'Utility' => array(CAKE . 'Test' . DS . 'test_app' . DS . 'Utility' . DS)
		), App::REGISTER);
		$Time = new TimeHelperTestObject($this->View, array('engine' => 'TestAppEngine'));
		$this->assertInstanceOf('TestAppEngine', $Time->engine());

		App::build(array(
			'Plugin' => array(CAKE . 'Test' . DS . 'test_app' . DS . 'Plugin' . DS)
		));
		Plugin::load('TestPlugin');
		$Time = new TimeHelperTestObject($this->View, array('engine' => 'TestPlugin.TestPluginEngine'));
		$this->assertInstanceOf('TestPluginEngine', $Time->engine());
		Plugin::unload('TestPlugin');
	}

/**
 * Test element wrapping in timeAgoInWords
 *
 * @return void
 */
	public function testTimeAgoInWords() {
		$Time = new TimeHelper($this->View);
		$timestamp = strtotime('+8 years, +4 months +2 weeks +3 days');
		$result = $Time->timeAgoInWords($timestamp, array(
			'end' => '1 years',
			'element' => 'span'
		));
		$expected = array(
			'span' => array(
				'title' => $timestamp,
				'class' => 'time-ago-in-words'
			),
			'on ' . date('j/n/y', $timestamp),
			'/span'
		);
		$this->assertTags($result, $expected);

		$result = $Time->timeAgoInWords($timestamp, array(
			'end' => '1 years',
			'element' => array(
				'title' => 'testing',
				'rel' => 'test'
			)
		));
		$expected = array(
			'span' => array(
				'title' => 'testing',
				'class' => 'time-ago-in-words',
				'rel' => 'test'
			),
			'on ' . date('j/n/y', $timestamp),
			'/span'
		);
		$this->assertTags($result, $expected);

		$timestamp = strtotime('+2 weeks');
		$result = $Time->timeAgoInWords(
			$timestamp,
			array('end' => '1 years', 'element' => 'div')
		);
		$expected = array(
			'div' => array(
				'title' => $timestamp,
				'class' => 'time-ago-in-words'
			),
			'in 2 weeks',
			'/div'
		);
		$this->assertTags($result, $expected);
	}

}
