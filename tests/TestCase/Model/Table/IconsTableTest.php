<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IconsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IconsTable Test Case
 */
class IconsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\IconsTable
     */
    public $Icons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.icons'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Icons') ? [] : ['className' => IconsTable::class];
        $this->Icons = TableRegistry::getTableLocator()->get('Icons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Icons);

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
