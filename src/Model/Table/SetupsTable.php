<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Setups Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $Comments
 * @property \Cake\ORM\Association\HasMany $Resources
 *
 * @method \App\Model\Entity\Setup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Setup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Setup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Setup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Setup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Setup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Setup findOrCreate($search, callable $callback = null, $options = [])
 */
class SetupsTable extends Table
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

        $this->setTable('setups');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'setup_id'
        ]);
        $this->hasMany('Resources', [
            'foreignKey' => 'setup_id'
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
            ->allowEmpty('description');

        $validator
            ->allowEmpty('author');

        $validator
            ->integer('counter')
            ->requirePresence('counter', 'create')
            // ->notEmpty('counter');
            ->allowEmpty('counter');

        $validator
            ->boolean('featured')
            ->requirePresence('featured', 'create')
            ->notEmpty('featured');

        $validator
            ->date('creationDate')
            ->allowEmpty('creationDate');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
