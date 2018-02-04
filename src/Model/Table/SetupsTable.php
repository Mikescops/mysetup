<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\Event\Event;
use Cake\Utility\Text;
use Cake\Routing\Router;
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
        $this->addBehavior('Sitemap.Sitemap', [
            'changefreq' => 'daily',
            'conditions' => [
                'status' => 'PUBLISHED'
            ]
        ]);
    }


    /** Let's get the real url of setup **/
    public function getUrl(Entity $entity)
    {
        return Router::url('/setups/' . $entity->id . '-' . Text::slug($entity->title), true);
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

        $validator
            ->allowEmpty('like_count');

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

        // This setup has just been deleted, let's update the main setup of this user accordingly
        $user = $this->Users->get($entity['user_id']);
        if($user->mainSetup_id === $entity['id'])
        {
            // His new main setup will be his second one, or NULL if there is no other one :s
            $newMainSetup = $this->find('all', [
                'fields' => [
                    'id'
                ],
                'conditions' => [
                    'user_id' => $user->id
                ],
                'order' => [
                    'creationDate' => 'DESC'
                ],
                'limit' => 1
            ])->first();

            $user->mainSetup_id = ($newMainSetup ? $newMainSetup->id : 0);

            $user->setDirty('modificationDate', true);
            $this->Users->save($user);
        }
    }

    public function getSetups($array = [], $flash = null)
    {
        // Here we just 'merge' our default values with the parameters given
        $params = array_merge([
            'query' => null,
            'featured' => false,
            'order' => 'DESC',
            'number' => 9999,
            'offset' => 0,
            'type' => 'date',
            'weeks' => 999
        ],
        $array);

        // We'll stock some conditions in this array
        $conditions = [
            'Setups.status' => 'PUBLISHED',
            'Setups.creationDate >' => date('Y-m-d', strtotime("-" . $params['weeks'] . "weeks")),
            'Setups.creationDate <=' => date('Y-m-d', strtotime("+ 1 day"))
        ];

        // If the query specified only the featured ones...
        if($params['featured'])
        {
            // ... let's add this condition !
            $conditions += ['Setups.featured' => true];
        }

        // Some empty arrays in which we'll set the SQL conditions to match a setup... or not
        $name_cond      = [];
        $author_cond    = [];
        $title_cond     = [];
        $resources_cond = [];

        if($params['query'])
        {
            if(strlen($params['query']) < 3)
            {
                if($flash)
                {
                    $flash->warning(__('Your query does not contain enough characters.'));
                }

                return null;
            }

            // We add to the search conditions the whole query as an unique sentence...
            array_push($name_cond, ['LOWER(Users.name) LIKE' => '%' . strtolower($params['query']) . '%']);
            array_push($author_cond, ['LOWER(Setups.author) LIKE' => '%' . strtolower($params['query']) . '%']);
            array_push($title_cond, ['LOWER(Setups.title) LIKE' => '%' . strtolower($params['query']) . '%']);
            array_push($resources_cond, ['CONVERT(Resources.title USING utf8) COLLATE utf8_general_ci LIKE' => '%' . rawurlencode($params['query']) . '%']);

            // ... and each one of it words to improve matching probability (#fuzzySearch)
            $words = explode('+', urlencode($params['query']));
            // Adds "fuzzy search" only if the query contains multiple words to avoid duplicates
            if(count($words) > 1)
            {
                foreach($words as $word)
                {
                    array_push($name_cond, ['LOWER(Users.name) LIKE' => '%' . strtolower($word) . '%']);
                    array_push($author_cond, ['LOWER(Setups.author) LIKE' => '%' . strtolower($word) . '%']);
                    array_push($title_cond, ['LOWER(Setups.title) LIKE' => '%' . strtolower($word) . '%']);
                    array_push($resources_cond, ['CONVERT(Resources.title USING utf8) COLLATE utf8_general_ci LIKE' => '%' . $word . '%']);
                }
            }
        }

        // By default, we'll order the setups by creation dates
        $orders = ['Setups.creationDate' => $params['order']];

        // If the query specified a ranking by number of "likes", let's order them in the query below
        if($params['type'] === 'like')
        {
            $orders = ['Setups.like_count' => 'DESC'] + $orders;
        }

        /*
            This query is just ESSENTIAL. Some explanations are required:

                * We select only the columns that we'll need #optimization
                * Featured image (for each setup) will be directly available  ($setup['resources'][0]['src'])
                * Number of likes for each setup will be directly available ($setup->like_count)
                * We browse the Users table (in order to gather some setups with the user name)
                * We browse the Setups table (in order to gather some setups with their author name and title)
                * We browse the Resources table (in order to gather some setups with their resources title [=== product name])
                * We pick only the public setups !
        */
        $results = $this->find('all', [
            'conditions' => $conditions,
            'order' => $orders,
            'limit' => $params['number'],
            'offset' => $params['offset'],
            'fields' => [
                'id',
                'user_id',
                'title',
                'creationDate',
                'status',
                'like_count'
            ],
            'contain' => [
                'Resources' => [
                    'fields' => [
                        'setup_id',
                        'src'
                    ],
                    'conditions' => [
                        'type' => 'SETUP_FEATURED_IMAGE'
                    ]
                ],
                'Users' => [
                    'fields' => [
                        'id',
                        'name',
                        'modificationDate'
                    ]
                ]
            ]
        ])
        ->where(['OR' => [$name_cond, $author_cond, $title_cond, $resources_cond]])
        ->leftJoinWith('Resources')
        ->distinct()
        ->toArray();

        return $results;
    }
}
