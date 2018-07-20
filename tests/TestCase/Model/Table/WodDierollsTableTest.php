<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RollsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WodDierollsTable Test Case
 */
class WodDierollsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RollsTable
     */
    public $WodDierolls;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.wod_dierolls'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('WodDierolls') ? [] : ['className' => RollsTable::class];
        $this->WodDierolls = TableRegistry::getTableLocator()->get('WodDierolls', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->WodDierolls);

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
