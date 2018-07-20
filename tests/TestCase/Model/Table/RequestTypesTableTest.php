<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RequestTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RequestTypesTable Test Case
 */
class RequestTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RequestTypesTable
     */
    public $RequestTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.request_types',
        'app.requests',
        'app.groups',
        'app.users',
        'app.roles',
        'app.permissions',
        'app.permissions_users',
        'app.permissions_roles',
        'app.group_types',
        'app.group_icons',
        'app.st_groups',
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
        $config = TableRegistry::getTableLocator()->exists('RequestTypes') ? [] : ['className' => 'App\Model\Table\RequestTypesTable'];
        $this->RequestTypes = TableRegistry::getTableLocator()->get('RequestTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RequestTypes);

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
