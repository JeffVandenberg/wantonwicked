<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PlayPreferenceResponsesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PlayPreferenceResponsesTable Test Case
 */
class PlayPreferenceResponsesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PlayPreferenceResponsesTable
     */
    public $PlayPreferenceResponses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.play_preference_responses',
        'app.users',
        'app.groups',
        'app.roles',
        'app.play_preferences',
        'app.created_bies',
        'app.updated_bies',
        'app.play_preference_response_history'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PlayPreferenceResponses') ? [] : ['className' => 'App\Model\Table\PlayPreferenceResponsesTable'];
        $this->PlayPreferenceResponses = TableRegistry::getTableLocator()->get('PlayPreferenceResponses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PlayPreferenceResponses);

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
