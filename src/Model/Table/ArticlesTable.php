<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('articles');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);

        $this->addBehavior('Timestamp', [
           'events' => [
               'Model.beforeSave' => [
                   'dateTime' => 'new'
               ]
           ]
       ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('content', 'create')
            ->notEmpty('content');

        $validator
            ->dateTime('dateTime')
            ->notEmpty('dateTime');

        return $validator;
    }

    public function isOwnedBy($article_id, $user_id)
    {
        return $this->exists(['id' => $article_id, 'user_id' => $user_id]);
    }
}
