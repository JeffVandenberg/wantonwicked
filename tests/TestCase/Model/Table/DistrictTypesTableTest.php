<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DistrictTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DistrictTypesTable Test Case
 */
class DistrictTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DistrictTypesTable
     */
    public $DistrictTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.district_types',
        'app.districts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DistrictTypes') ? [] : ['className' => DistrictTypesTable::class];
        $this->DistrictTypes = TableRegistry::getTableLocator()->get('DistrictTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DistrictTypes);

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
