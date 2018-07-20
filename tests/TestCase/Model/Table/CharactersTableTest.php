<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CharactersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CharactersTable Test Case
 */
class CharactersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CharactersTable
     */
    public $Characters;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.characters',
        'app.users',
        'app.groups',
        'app.roles',
        'app.updated_bies',
        'app.locations',
        'app.character_beat_records',
        'app.character_beats',
        'app.character_logins',
        'app.character_notes',
        'app.character_powers',
        'app.character_updates',
        'app.log_characters',
        'app.request_characters',
        'app.requests',
        'app.scene_characters',
        'app.supporter_characters',
        'app.territories',
        'app.characters_territories',
        'app.l5r_plots',
        'app.l5r_plots_characters'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Characters') ? [] : ['className' => 'App\Model\Table\CharactersTable'];
        $this->Characters = TableRegistry::getTableLocator()->get('Characters', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Characters);

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
