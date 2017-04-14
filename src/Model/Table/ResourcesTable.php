<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Filesystem\File;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;

/**
 * Resources Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Setups
 *
 * @method \App\Model\Entity\Resource get($primaryKey, $options = [])
 * @method \App\Model\Entity\Resource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Resource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Resource|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Resource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Resource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Resource findOrCreate($search, callable $callback = null, $options = [])
 */
class ResourcesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('resources');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Setups', [
            'foreignKey' => 'setup_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->notEmpty('type');

        $validator
            ->allowEmpty('title');

        $Validator
            ->allowEmpty('href');

        $validator
            ->allowEmpty('src');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        // Special validation: 'user_id' XOR 'setup_id' has to be set
        // Furthermore, the 'id' no-null on the moment has to exist in the DB
        $rules->
            add(function($entity) {
                if(isset($entity['user_id']) XOR isset($entity['setup_id']))
                {
                    if(isset($entity['user_id']))
                    {
                        return $this->Users->find()->where(['id' =>  $entity['user_id']])->first() !== null;
                    }

                    else
                    {
                        return $this->Setups->find()->where(['id' =>  $entity['setup_id']])->first() !== null;
                    }
                }

                else
                {
                    return false;
                }
            },
            'foreignKey_rule');

        return $rules;
    }

    public function beforeDelete(Event $event, EntityInterface $entity)
    {
        if($entity['type'] === 'GALLERY_IMAGE')
        {
            (new File($entity['src']))->delete();
        }
    }
}
