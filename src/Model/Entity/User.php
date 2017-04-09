<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $mail
 * @property string $profileImagePath
 * @property string $facebook
 * @property string $twitter
 * @property string $mastodon
 * @property bool $verified
 *
 * @property \App\Model\Entity\Setup[] $setups
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    protected function _setPassword($password)
    {
        if(strlen($password) > 0)
        {
          return (new DefaultPasswordHasher)->hash($password);
        }
    }
}
