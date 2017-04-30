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
        // Special validation: 'user_id' OR 'setup_id' has / have to be set
        // Furthermore, the 'id' no-null on the moment has / have to exist in the DB
        $rules->
            add(function($entity) {
                // At least one 'id' has to be set
                if(isset($entity['user_id']) or isset($entity['setup_id']))
                {
                    // ... and each set one has to correspond to a existing entity in the DB
                    if(isset($entity['user_id']) and $this->Users->find()->where(['id' =>  $entity['user_id']])->count() === 0)
                    {
                        return false;
                    }

                    if(isset($entity['setup_id']) and $this->Setups->find()->where(['id' =>  $entity['setup_id']])->count() === 0)
                    {
                        return false;
                    }

                    return true;
                }

                return false;
            },
            'foreignKey_rule');

        return $rules;
    }

    public function beforeDelete(Event $event, EntityInterface $entity)
    {
        if($entity['type'] === 'SETUP_GALLERY_IMAGE' or $entity['type'] === 'SETUP_FEATURED_IMAGE')
        {
            if(!(new File($entity['src']))->delete())
            {
                $flash->warning(__("An image of your setup could not be removed as well... Please contact an administrator."));
            }
        }
    }

    public function saveResourceProducts($products, $setup, $flash, $user_id)
    {
        // "Title_1;href_1;src_1,Title_2;href_2;src_2,...,Title_n;href_n;src_n"
        foreach(explode(',', $products) as $elements)
        {
            $elements = explode(';', $elements);
            if(count($elements) == 3)
            {
                // Let's parse the URls provided, in order to check their authenticity
                $parsing_2 = parse_url(urldecode($elements[1]));
                $parsing_3 = parse_url(urldecode($elements[2]));

                // Let's check if the resources selected by the user are from Amazon
                if(isset($parsing_2['host']) && strstr($parsing_2['host'], "amazon") && isset($parsing_3['host']) && strstr($parsing_3['host'], "amazon"))
                {
                    // Let's create a new entity to store these data !
                    $resource = $this->newEntity();

                    $resource->user_id  = $user_id;
                    $resource->setup_id = $setup->id;
                    $resource->type     = 'SETUP_PRODUCT';
                    $resource->title    = $elements[0];
                    $resource->href     = $elements[1];
                    $resource->src      = $elements[2];

                    // If the resource can't be saved atm, we rollback and throw an error...
                    if(!$this->save($resource))
                    {
                        $this->Setups->delete($setup);
                        $flash->error(__('Internal error, we couldn\'t save your setup.'));
                    }
                }

                else
                {
                    $flash->warning(__("One of the products you chose does not validate our rules... Please contact an administrator."));
                }
            }
        }
    }

    public function saveResourceImage($file, $setup, $type, $flash, $user_id)
    {
        if($file['error'] === 0 && $file['size'] <= 5000000 && substr($file['type'], 0, strlen('image/')) === 'image/')
        {
            $tmp = explode('/', $file['type']);  // Thanks PHP for that useless variable...
            $destination = 'uploads/files/' . Text::uuid() . '.' . end($tmp);

            if(move_uploaded_file($file['tmp_name'], $destination))
            {
                $resource = $this->newEntity();
                $resource->user_id  = $user_id;
                $resource->setup_id = $setup->id;
                $resource->type     = $type;
                $resource->title    = null;
                $resource->href     = null;
                $resource->src      = $destination;

                if(!$this->save($resource))
                {
                    $this->Setups->delete($setup);
                    $flash->error(__('Internal error, we couldn\'t save your setup.'));
                }
            }

            else
            {
                $flash->warning(__('One of the file you uploaded could not be saved.'));
            }
        }

        else
        {
            $flash->warning(__("One of the files you uploaded does not validate our rules... Please contact an administrator."));
        }
    }

    public function saveResourceVideo($video, $setup, $type, $flash, $user_id)
    {
        $parsing = parse_url($video);

        if(isset($parsing['host']))
        {
            // The host will contain only the DN without 'www.' if present
            $parsing['host'] = str_replace('www.', '', $parsing['host']);

            if(in_array($parsing['host'], ['dailymotion.com', 'dai.ly', 'flickr.com', 'flic.kr', 'youtube.com', 'youtu.be', 'vimeo.com', 'rutube.ru']))
            {
                // Let's create a new entity to store these data !
                $resource = $this->newEntity();

                $resource->user_id  = $user_id;
                $resource->setup_id = $setup->id;
                $resource->type     = 'SETUP_VIDEO_LINK';
                $resource->title    = null;
                $resource->href     = null;
                $resource->src      = $video;

                // If the resource can't be saved atm, we rollback and throw an error...
                if(!$this->save($resource))
                {
                    $this->Setups->delete($setup);
                    $flash->error(__('Internal error, we couldn\'t save your setup.'));
                }
            }

            else
            {
                $flash->warning(__("The video link you chose does not validate our rules... Please contact an administrator."));
            }
        }

        else
        {
            $flash->warning(__("The video link you chose does not validate our rules... Please contact an administrator."));
        }
    }
}
