<?php
/**
 * FlashHelperTest file
 *
 * Series of tests for flash helper.
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
 * @since         CakePHP(tm) v 2.7.0-dev
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */namespace lib\Cake\Test\TestCase\Template\Helper;

use Cake\Core\App;
use Cake\Core\Plugin;


use App\View\Helper\FlashHelper;
use Cake\View\View;
use Cake\Core\Plugin;

/**
 * FlashHelperTest class
 *
 * @package		Cake.Test.Case.View.Helper
 */
class FlashHelperTest extends TestCase {

/**
 * setupBeforeClass method
 *
 * @return void
 */
	public static function setupBeforeClass() {
		App::build(array(
			'View' => array(CAKE . 'Test' . DS . 'test_app' . DS . 'View' . DS)
		));
	}

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$controller = null;
		$this->View = new View($controller);
		$this->Flash = new FlashHelper($this->View);

		if (!Session::started()) {
			Session::start();
		}
		Session::write(array(
			'Message' => array(
				'flash' => array(
					'key' => 'flash',
					'message' => 'This is a calling',
					'element' => 'Flash/default',
					'params' => array()
				),
				'notification' => array(
					'key' => 'notification',
					'message' => 'Broadcast message testing',
					'element' => 'flash_helper',
					'params' => array(
						'title' => 'Notice!',
						'name' => 'Alert!'
					)
				),
				'classy' => array(
					'key' => 'classy',
					'message' => 'Recorded',
					'element' => 'flash_classy',
					'params' => array()
				)
			)
		));
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->View, $this->Flash);
		Session::destroy();
	}

/**
 * testFlash method
 *
 * @return void
 */
	public function testFlash() {
		$result = $this->Flash->render();
		$expected = '<div class="message">This is a calling</div>';
		$this->assertContains($expected, $result);

		$expected = '<div id="classy-message">Recorded</div>';
		$result = $this->Flash->render('classy');
		$this->assertContains($expected, $result);

		$result = $this->Flash->render('notification');
		$expected = "<div id=\"notificationLayout\">\n\t<h1>Alert!</h1>\n\t<h3>Notice!</h3>\n\t<p>Broadcast message testing</p>\n</div>";
		$this->assertContains($expected, $result);

		$this->assertNull($this->Flash->render('non-existent'));
	}

/**
 * testFlashThrowsException
 *
 * @expectedException UnexpectedValueException
 */
	public function testFlashThrowsException() {
		Session::write('Message.foo', 'bar');
		$this->Flash->render('foo');
	}

/**
 * test setting the element from the attrs.
 *
 * @return void
 */
	public function testFlashElementInAttrs() {
		$result = $this->Flash->render('notification', array(
			'element' => 'flash_helper',
			'params' => array('title' => 'Alert!', 'name' => 'Notice!')
		));

		$expected = "<div id=\"notificationLayout\">\n\t<h1>Notice!</h1>\n\t<h3>Alert!</h3>\n\t<p>Broadcast message testing</p>\n</div>";

		$this->assertContains($expected, $result);
	}

/**
 * test using elements in plugins.
 *
 * @return void
 */
	public function testFlashWithPluginElement() {
		App::build(array(
			'Plugin' => array(CAKE . 'Test' . DS . 'test_app' . DS . 'Plugin' . DS)
		));
		Plugin::load('TestPlugin');

		$result = $this->Flash->render('flash', array('element' => 'TestPlugin.plugin_element'));
		$expected = 'this is the plugin element';
		$this->assertContains($expected, $result);
	}
}
