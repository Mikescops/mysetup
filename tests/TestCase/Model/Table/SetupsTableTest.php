<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SetupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SetupsTable Test Case
 */
class SetupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SetupsTable
     */
    public $Setups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.setups',
        'app.users',
        'app.resources'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Setups') ? [] : ['className' => 'App\Model\Table\SetupsTable'];
        $this->Setups = TableRegistry::get('Setups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Setups);

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
