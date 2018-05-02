<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BeatTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BeatTypesTable Test Case
 */
class BeatTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BeatTypesTable
     */
    public $BeatTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.beat_types',
        'app.created_bies',
        'app.updated_bies',
        'app.character_beats'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('BeatTypes') ? [] : ['className' => 'App\Model\Table\BeatTypesTable'];
        $this->BeatTypes = TableRegistry::getTableLocator()->get('BeatTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BeatTypes);

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
