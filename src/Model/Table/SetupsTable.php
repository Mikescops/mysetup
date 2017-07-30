<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

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
    public $status = ['PUBLISHED' => 'Public', 'DRAFT' => 'Private', 'REJECTED' => 'Rejected'];

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
            'foreignKey' => 'setup_id',
            'dependent' => 'true',
            'cascadeCallbacks' => 'true'
        ]);
        $this->hasMany('Likes', [
            'foreignKey' => 'setup_id',
            'dependent' => 'true',
            'cascadeCallbacks' => 'true'
        ]);
        $this->hasMany('Resources', [
            'foreignKey' => 'setup_id',
            'dependent' => 'true',
            'cascadeCallbacks' => 'true'
        ]);
        $this->hasMany('Requests', [
            'foreignKey' => 'setup_id',
            'dependent' => 'true',
            'cascadeCallbacks' => 'true'
        ]);

         $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'creationDate' => 'new',
                    'modifiedDate' => 'always'
                ]
            ]
        ]);

        $this->addBehavior('Sitemap.Sitemap', ['changefreq' => 'daily', 'conditions' => ['status' => 'PUBLISHED'] ]);
    }


    /** Let's get the real url of setup **/
    public function getUrl(\Cake\ORM\Entity $entity)
    {
        return \Cake\Routing\Router::url('/setups/'.$entity->id.'-'.\Cake\Utility\Text::slug($entity->title), true);
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
            ->notEmpty('title')
            ->add('title', 'length', [
                'rule' => ['maxLength', 48],
                'message' => __('This title is too long (more than 48 characters)')]);

        $validator
            ->allowEmpty('description')
            ->add('description', 'length', [
                'rule' => ['maxLength', 5000],
                'message' => __('This description is too long (more than 5000 characters)')]);

        $validator
            ->allowEmpty('author');

        $validator
            ->boolean('featured')
            ->notEmpty('featured');

        $validator
            ->dateTime('creationDate')
            ->notEmpty('creationDate');

        $validator
            ->dateTime('modifiedDate')
            ->notEmpty('modifiedDate');

        $validator
            ->notEmpty('status');

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

        $rules->
            add(function($entity) {
                if(isset($entity['status']) and array_key_exists($entity['status'], $this->status))
                {
                    return true;
                }

                else
                {
                    return false;
                }
            },
            'statusIntegrity_rule');

        return $rules;
    }

    public function isOwnedBy($setup_id, $user_id)
    {
        return $this->exists(['id' => $setup_id, 'user_id' => $user_id]);
    }

    public function isPublic($setup_id)
    {
        return $this->exists(['id' => $setup_id, 'status' => 'PUBLISHED']);
    }

    public function afterDelete(Event $event, EntityInterface $entity)
    {
        // Read or not, we just get rid of each notification referencing this (deleted) setup
        TableRegistry::get('Notifications')->deleteAll(['content LIKE' => '%' . $entity['id'] . '%']);
    }
}
