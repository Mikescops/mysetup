<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Articles extends Entity
{
    protected $_accessible = [
        '*' => true
    ];
}
