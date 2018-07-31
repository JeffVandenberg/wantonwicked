<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StGroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StGroupsTable Test Case
 */
class StGroupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StGroupsTable
     */
    public $StGroups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.st_groups',
        'app.users',
        'app.groups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('StGroups') ? [] : ['className' => StGroupsTable::class];
        $this->StGroups = TableRegistry::getTableLocator()->get('StGroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StGroups);

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
