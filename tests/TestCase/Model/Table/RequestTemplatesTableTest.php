<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RequestTemplatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RequestTemplatesTable Test Case
 */
class RequestTemplatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RequestTemplatesTable
     */
    public $RequestTemplates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.request_templates'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RequestTemplates') ? [] : ['className' => 'App\Model\Table\RequestTemplatesTable'];
        $this->RequestTemplates = TableRegistry::getTableLocator()->get('RequestTemplates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RequestTemplates);

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
