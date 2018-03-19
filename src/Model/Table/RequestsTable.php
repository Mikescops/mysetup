<?php
namespace App\Model\Table;

use Cake\ORM\Table;


class RequestsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('requests');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Setups', [
            'foreignKey' => 'setup_id'
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'dateTime' => 'new'
                ]
            ]
        ]);
    }
}
