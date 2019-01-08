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

use Cake\Datasource\Exception\RecordNotFoundException;

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
            'priority'   => 0.7,
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

        $validator
            ->allowEmpty('main_colors');

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
        $this->Users->Notifications->deleteAll(['content LIKE' => '%' . $entity['id'] . '%']);

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

    public function getSetups($array = [])
    {
        // Here we just 'merge' our default values with the parameters given
        $params = array_merge([
            'query'    => null,
            'featured' => null,
            'order'    => null,
            'number'   => null,
            'offset'   => null,
            'type'     => null,
            'weeks'    => null,
            'fuzzy'    => null,
            'yearweek' => null
        ],
        $array);

        // We'll only return the `PUBLISHED` setups with this call.
        $conditions = ['Setups.status' => 'PUBLISHED'];

        if($params['weeks'] and intval($params['weeks']))
        {
            $conditions += ['Setups.creationDate >' => new \DateTime('-' . intval($params['weeks']) . ' weeks')];
        }

        if($params['yearweek']){
            $year = $params['yearweek'][0];
            $week_no = $params['yearweek'][1];

            $week_start = new \DateTime();
            $week_start->setISODate($year,$week_no);
            $week_end = clone $week_start;
            $week_end = $week_end->add(new \DateInterval("P1W"));

            $conditions += ['Setups.creationDate >=' => $week_start];
            $conditions += ['Setups.creationDate <=' => $week_end];
        }

        // If the query specified only the featured ones...
        if($params['featured'])
        {
            // ... let's add this condition !
            $conditions += ['Setups.featured' => true];
        }

        // Some empty arrays in which we'll set the SQL conditions to match a setup... or not
        $title_cond     = [];
        $resources_cond = [];

        if($params['query'])
        {
            $params['query'] = strtolower($params['query']);

            if($params['fuzzy'])
            {
                // If the "fuzzy" parameter is enabled, search for each word to improve matching probability (#fuzzySearch).
                foreach(explode('+', urlencode($params['query'])) as $word)
                {
                    array_push($title_cond,     ['LOWER(Setups.title)                                         LIKE' => '%' . $word . '%']);
                    array_push($resources_cond, ['CONVERT(Resources.title USING utf8) COLLATE utf8_general_ci LIKE' => '%' . $word . '%']);
                }
            }

            else
            {
                // If not, we add to the search conditions the whole query as an unique sentence...
                array_push($title_cond,     ['LOWER(Setups.title)                                         LIKE' => '%' .              $params['query']  . '%']);
                array_push($resources_cond, ['CONVERT(Resources.title USING utf8) COLLATE utf8_general_ci LIKE' => '%' . rawurlencode($params['query']) . '%']);
            }

        }

        /* ORDER */
        if($params['order'] === null or !in_array(strtoupper($params['order']), ['ASC', 'DESC']))
        {
            // `DESC` by default
            $params['order'] = 'DESC';
        }

        $orders = [];

        // If the query specified a ranking by number of "likes", let's order them in the query below
        if($params['type'] === 'like')
        {
            $orders += ['Setups.like_count' => $params['order']];
        }

        // But we'll order the setups by creation dates anyway
        $orders += ['Setups.creationDate' => $params['order']];
        /* _____ */

        /*
            This query is just ESSENTIAL. Some explanations are required:

                * We select only the columns that we'll need #optimization
                * Featured image (for each setup) will be directly available  ($setup['resources'][0]['src'])
                * Number of likes for each setup will be directly available ($setup->like_count)
                * We browse the Setups table (in order to gather some setups with their title)
                * We browse the Resources table (in order to gather some setups with their resources title [=== product name])
                * We pick only the public setups !
        */
        $query = $this->find('all', [
            'conditions' => [
                'AND' => [
                    'AND' => $conditions,
                    'OR'  => [
                        'OR' => $title_cond,
                        'OR' => $resources_cond
                    ]
                ]
            ],
            'order' => $orders,
            'fields' => [
                'id',
                'user_id',
                'title',
                'creationDate',
                'featured',
                'status',
                'like_count',
                'main_colors'
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
        ]);

        // Unless a query is specified, we actually don't have to join the Resources and the Setups tables !
        if($params['query'])
        {
            $query->innerJoinWith('Resources');
        }

        // Don't add useless params to query if they are actually null...
        if($params['number'])
        {
            $query->limit($params['number']);
        }
        if($params['offset'])
        {
            $query->offset($params['offset']);
        }

        return $query->distinct()->toArray();
    }

    public function fetchSetupById($setup_id)
    {
        try
        {
            $setup = $this->get($setup_id, [
                'fields' => [
                    'id',
                    'user_id',
                    'title',
                    'creationDate',
                    'featured',
                    'status',
                    'like_count',
                    'main_colors'
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
            ]);

            if($setup->status !== 'PUBLISHED')
            {
                $setup = null;
            }
        }

        catch(RecordNotFoundException $e)
        {
            $setup = null;
        }

        return $setup;
    }
}
