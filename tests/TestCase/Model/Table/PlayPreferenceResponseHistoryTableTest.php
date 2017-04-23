<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PlayPreferenceResponseHistoryTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PlayPreferenceResponseHistoryTable Test Case
 */
class PlayPreferenceResponseHistoryTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PlayPreferenceResponseHistoryTable
     */
    public $PlayPreferenceResponseHistory;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.play_preference_response_history',
        'app.users',
        'app.groups',
        'app.roles',
        'app.play_preferences',
        'app.created_bies',
        'app.updated_bies',
        'app.play_preference_responses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PlayPreferenceResponseHistory') ? [] : ['className' => 'App\Model\Table\PlayPreferenceResponseHistoryTable'];
        $this->PlayPreferenceResponseHistory = TableRegistry::get('PlayPreferenceResponseHistory', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PlayPreferenceResponseHistory);

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
