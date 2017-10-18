<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Notifications Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Comment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Comment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Comment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Comment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Comment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Comment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Comment findOrCreate($search, callable $callback = null, $options = [])
 */
class NotificationsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('notifications');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);

        $this->addBehavior('Timestamp', [
           'events' => [
               'Model.beforeSave' => [
                   'dateTime' => 'new'
               ]
           ]
       ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('content', 'create')
            ->notEmpty('content');

        $validator
            ->dateTime('dateTime')
            ->notEmpty('dateTime');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function isOwnedBy($notification_id, $user_id)
    {
        return $this->exists(['id' => $notification_id, 'user_id' => $user_id]);
    }

    public function beforeSave()
    {
        // Well well, why not take the opportunity to "purge" old notifications not yet deleted by users ?
        // - Yeah, that's a good idea, let's get the notifications which had been created a long time ago !
        $this->deleteAll(['new' => 0, 'dateTime <' => new \DateTime('-30 days')]);
    }

    public function createNotification($user_id, $content)
    {
        // Before saving this new notification, we'll check if its content is not already present into the DB #floodDetection
        if(!$this->exists(['content' => $content]))
        {
            $notification = $this->newEntity();

            // Here we'll assign a random id to this new notification
            do {
                $notification->id = mt_rand() + 1;
            } while($this->find()->where(['id' => $notification->id])->count() !== 0);

            $notification->user_id = $user_id;
            $notification->content = $content;
            $notification->new     = 1;

            $this->save($notification);
        }
    }
}
