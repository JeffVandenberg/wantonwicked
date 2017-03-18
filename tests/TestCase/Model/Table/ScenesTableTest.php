<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ScenesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ScenesTable Test Case
 */
class ScenesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ScenesTable
     */
    public $Scenes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.scenes',
        'app.run_bies',
        'app.created_bies',
        'app.updated_bies',
        'app.scene_statuses',
        'app.scene_characters',
        'app.scene_requests'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Scenes') ? [] : ['className' => 'App\Model\Table\ScenesTable'];
        $this->Scenes = TableRegistry::get('Scenes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Scenes);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
