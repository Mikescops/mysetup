<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
            ->allowEmpty('title');

        $validator
            ->allowEmpty('href');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

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
        $rules->add($rules->existsIn(['setup_id'], 'Setups'));

        return $rules;
    }
}
