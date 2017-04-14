<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Filesystem\File;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\Utility\Text;

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

    public function saveResourceProducts($products, $setup)
    {
        // "Title_1;href_1;src_1,Title_2;href_2;src_2,...,Title_n;href_n;src_n"
        foreach(explode(',', $products) as $elements)
        {
            $elements = explode(';', $elements);
            if(count($elements) == 3)
            {
                // Let's create a new entity to store these data !
                $resource = $this->newEntity();

                // Let's parse the URls provided, in order to check their authenticity
                $parsing_2 = parse_url(urldecode($elements[1]));
                $parsing_3 = parse_url(urldecode($elements[2]));

                // Let's check if the resources selected by the user are from Amazon
                if(isset($parsing_2['host']) && strstr($parsing_2['host'], "amazon") && isset($parsing_3['host']) && strstr($parsing_3['host'], "amazon"))
                {
                    $resource->user_id  = null;
                    $resource->setup_id = $setup->id;
                    $resource->type     = 'SETUP_PRODUCT';
                    $resource->title    = $elements[0];
                    $resource->href     = $elements[1];
                    $resource->src      = $elements[2];

                    // If the resource does not validate its rule, we rollback and throw an error...
                    if(!$this->save($resource))
                    {
                        $this->Setups->delete($setup);
                        $this->Flash->error(__('Internal error, we couldn\'t save your setup.'));
                        return $this->redirect(['action' => 'add']);
                    }
                }
            }
        }
    }

    public function saveResourceImage($file, $setup, $type)
    {
        if($file['error'] === 0 && $file['size'] <= 5000000 && substr($file['type'], 0, strlen('image/')) === 'image/')
        {
            $tmp = explode('/', $file['type']);  // Thanks PHP for that useless variable...
            $destination = 'uploads/files/' . Text::uuid() . '.' . end($tmp);

            if(move_uploaded_file($file['tmp_name'], $destination))
            {
                $resource = $this->newEntity();
                $resource->user_id  = null;
                $resource->setup_id = $setup->id;
                $resource->type     = $type;
                $resource->title    = null;
                $resource->href     = null;
                $resource->src      = $destination;

                if(!$this->save($resource))
                {
                    $this->Setups->delete($setup);
                    $this->Flash->error(__('Internal error, we couldn\'t save your setup.'));
                    return $this->redirect(['action' => 'add']);
                }
            }
        }
    }
}
