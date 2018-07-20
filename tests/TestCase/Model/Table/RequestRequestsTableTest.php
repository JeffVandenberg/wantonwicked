<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RequestRequestsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RequestRequestsTable Test Case
 */
class RequestRequestsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RequestRequestsTable
     */
    public $RequestRequests;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.request_requests',
        'app.from_requests',
        'app.to_requests'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RequestRequests') ? [] : ['className' => RequestRequestsTable::class];
        $this->RequestRequests = TableRegistry::getTableLocator()->get('RequestRequests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RequestRequests);

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
