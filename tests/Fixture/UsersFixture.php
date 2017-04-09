<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'comment' => '', 'precision' => null, 'fixed' => null],
        'mail' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'comment' => '', 'precision' => null, 'fixed' => null],
        'profileImagePath' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'comment' => '', 'precision' => null, 'fixed' => null],
        'facebook' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'comment' => '', 'precision' => null, 'fixed' => null],
        'twitter' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'comment' => '', 'precision' => null, 'fixed' => null],
        'mastodon' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'comment' => '', 'precision' => null, 'fixed' => null],
        'verified' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'mail' => ['type' => 'unique', 'columns' => ['mail'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_bin'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'name' => 'Lorem ipsum dolor sit amet',
            'mail' => 'Lorem ipsum dolor sit amet',
            'profileImagePath' => 'Lorem ipsum dolor sit amet',
            'facebook' => 'Lorem ipsum dolor sit amet',
            'twitter' => 'Lorem ipsum dolor sit amet',
            'mastodon' => 'Lorem ipsum dolor sit amet',
            'verified' => 1
        ],
    ];
}
