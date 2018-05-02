<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PlayPreferencesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PlayPreferencesTable Test Case
 */
class PlayPreferencesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PlayPreferencesTable
     */
    public $PlayPreferences;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.play_preferences',
        'app.created_bies',
        'app.updated_bies',
        'app.play_preference_response_history',
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
        $config = TableRegistry::getTableLocator()->exists('PlayPreferences') ? [] : ['className' => 'App\Model\Table\PlayPreferencesTable'];
        $this->PlayPreferences = TableRegistry::getTableLocator()->get('PlayPreferences', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PlayPreferences);

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
