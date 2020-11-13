<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\GameComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\GameComponent Test Case
 */
class GameComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\GameComponent
     */
    public $Game;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Game = new GameComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Game);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
