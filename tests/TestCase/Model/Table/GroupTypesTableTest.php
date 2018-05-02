<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GroupTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GroupTypesTable Test Case
 */
class GroupTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GroupTypesTable
     */
    public $GroupTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.group_types',
        'app.groups',
        'app.group_icons',
        'app.requests',
        'app.st_groups',
        'app.request_types',
        'app.groups_request_types'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('GroupTypes') ? [] : ['className' => 'App\Model\Table\GroupTypesTable'];
        $this->GroupTypes = TableRegistry::getTableLocator()->get('GroupTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GroupTypes);

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
}
