<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PlotsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PlotsTable Test Case
 */
class PlotsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PlotsTable
     */
    public $Plots;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.plots',
        'app.plot_statuses',
        'app.created_bies',
        'app.updated_bies',
        'app.plot_characters',
        'app.plot_scenes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Plots') ? [] : ['className' => PlotsTable::class];
        $this->Plots = TableRegistry::get('Plots', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Plots);

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
