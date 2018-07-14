<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\ORM\TableRegistry;

/**
 * CloudTags Model
 *
 * @method \App\Model\Entity\CloudTag get($primaryKey, $options = [])
 * @method \App\Model\Entity\CloudTag newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CloudTag[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CloudTag|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CloudTag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CloudTag[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CloudTag findOrCreate($search, callable $callback = null, $options = [])
 */
class CloudTagsTable extends Table
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

        $this->setTable('cloud_tags');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
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
            ->scalar('name')
            ->maxLength('name', 64)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('type')
            ->maxLength('type', 32)
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        return $validator;
    }


    public function getSetupsByRandomTags($array = [])
    {

        $params = array_merge([
            'type'         => null,
            'number_tags'  => 1,
            'limit_setups' => 3
        ],
        $array);

        $setupTable = TableRegistry::get('Setups');

        if($params['type'])
        {
            $conditions = ['type' => $params['type']];
        }
        else
        {
            $conditions = null;
        }

        $results = [];

        $tags = $this->find('all', [
            'conditions' => $conditions,
            'order'      => 'RAND()'
        ])->toArray();

        foreach($tags as $tag)
        {
            $setups = $setupTable->getSetups([
                'query'  => $tag->name,
                'number' => $params['limit_setups'],
                'type'   => 'like',
                'fuzzy'  => false
            ]);

            if(count($setups) >= 3)
            {
                $results[$tag->name] = $setups;
            }
            else
            {
                continue;
            }

            if(count($results) >= $params['number_tags'])
            {
                break;
            }
        }

        return $results;
    }
}
