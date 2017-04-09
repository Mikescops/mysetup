<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $Setups
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Setups', [
            'foreignKey' => 'user_id'
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
            ->notEmpty('id', 'create');

        $validator
            ->notEmpty('name');

        $validator
            ->notEmpty('mail')
            ->add('mail', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'])
            ->add('mail', 'validFormat', [
                'rule' => 'email',
                'message' => 'We need a valid E-mail address']);

        $validator
            ->notEmpty('password')
            ->add('password', 'length', [
                'rule' => ['minLength', 8],
                'message' => 'The password has to contain more than 8 characters']);

        $validator
            ->allowEmpty('profileImagePath');

        $validator
            ->allowEmpty('facebook')
            ->add('facebook', 'validFormat', [
                'rule' => 'url',
                'message' => 'We need a valid link']);

        $validator
            ->allowEmpty('twitter')
            ->add('twitter', 'validFormat', [
                'rule' => 'url',
                'message' => 'We need a valid link']);

        $validator
            ->allowEmpty('mastodon')
            ->add('mastodon', 'validFormat', [
                'rule' => 'url',
                'message' => 'We need a valid link']);

        $validator
            ->boolean('verified')
            ->notEmpty('verified');

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
        $rules->add($rules->isUnique(['mail']));

        return $rules;
    }
}
