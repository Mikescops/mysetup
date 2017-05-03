<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Setup Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $author
 * @property int $counter
 * @property bool $featured
 * @property \Cake\I18n\Time $creationDate
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Resource[] $resources
 */
class Setup extends Entity
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
        '*' => true
    ];
}
