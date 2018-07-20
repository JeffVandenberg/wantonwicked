<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CharacterPowersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CharacterPowersTable Test Case
 */
class CharacterPowersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CharacterPowersTable
     */
    public $CharacterPowers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.character_powers',
        'app.characters',
        'app.users',
        'app.groups',
        'app.roles',
        'app.updated_bies',
        'app.character_beat_records',
        'app.character_beats',
        'app.character_logins',
        'app.character_notes',
        'app.character_updates',
        'app.locations',
        'app.log_characters',
        'app.request_characters',
        'app.requests',
        'app.scene_characters',
        'app.scenes',
        'app.run_by',
        'app.created_by',
        'app.updated_by',
        'app.scene_statuses',
        'app.scene_requests',
        'app.territories',
        'app.characters_territories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CharacterPowers') ? [] : ['className' => 'App\Model\Table\CharacterPowersTable'];
        $this->CharacterPowers = TableRegistry::getTableLocator()->get('CharacterPowers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CharacterPowers);

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
