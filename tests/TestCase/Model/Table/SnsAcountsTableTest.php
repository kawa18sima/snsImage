<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SnsAcountsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SnsAcountsTable Test Case
 */
class SnsAcountsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SnsAcountsTable
     */
    public $SnsAcounts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.SnsAcounts',
        'app.Acounts',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SnsAcounts') ? [] : ['className' => SnsAcountsTable::class];
        $this->SnsAcounts = TableRegistry::getTableLocator()->get('SnsAcounts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SnsAcounts);

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
