<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RollsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DieRollsTable Test Case
 */
class DieRollsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RollsTable
     */
    public $DieRolls;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.die_rolls'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DieRolls') ? [] : ['className' => RollsTable::class];
        $this->DieRolls = TableRegistry::getTableLocator()->get('DieRolls', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DieRolls);

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
