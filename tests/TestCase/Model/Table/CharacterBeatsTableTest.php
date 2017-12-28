<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CharacterBeatsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CharacterBeatsTable Test Case
 */
class CharacterBeatsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CharacterBeatsTable
     */
    public $CharacterBeats;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.character_beats',
        'app.characters',
        'app.users',
        'app.groups',
        'app.group_types',
        'app.group_icons',
        'app.requests',
        'app.st_groups',
        'app.request_types',
        'app.groups_request_types',
        'app.roles',
        'app.permissions',
        'app.permissions_users',
        'app.permissions_roles',
        'app.updated_by',
        'app.character_status',
        'app.character_beat_records',
        'app.character_logins',
        'app.character_notes',
        'app.character_powers',
        'app.character_updates',
        'app.locations',
        'app.log_characters',
        'app.request_characters',
        'app.scene_characters',
        'app.scenes',
        'app.run_by',
        'app.created_by',
        'app.scene_statuses',
        'app.scene_requests',
        'app.territories',
        'app.characters_territories',
        'app.beat_types',
        'app.beat_statuses',
        'app.created_bies',
        'app.updated_bies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CharacterBeats') ? [] : ['className' => CharacterBeatsTable::class];
        $this->CharacterBeats = TableRegistry::get('CharacterBeats', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CharacterBeats);

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
