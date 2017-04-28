<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\Filesystem\File;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Resources
 * @property \Cake\ORM\Association\HasMany $Comments
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

        $this->hasMany('Resources', [
            'foreignKey' => 'user_id',
            'dependent' => 'true',
            'cascadeCallbacks' => 'true'
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'user_id',
            'dependent' => 'true',
            'cascadeCallbacks' => 'true'
        ]);
        $this->hasMany('Setups', [
            'foreignKey' => 'user_id',
            'dependent' => 'true',
            'cascadeCallbacks' => 'true'
        ]);
        $this->hasMany('Likes', [
            'foreignKey' => 'user_id',
            'dependent' => 'true',
            'cascadeCallbacks' => 'true'
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
            ->allowEmpty('name');

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
            ->notEmpty('preferredStore');

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

    public function afterDelete(Event $event, EntityInterface $entity)
    {
        if(!(new File('uploads/files/profile_picture_' . $entity['id'] . '.png'))->delete())
        {
            $flash->warning(__("Your profile picture could not be removed as well... Please contact an administrator."));
        }
    }

    public function saveDefaultProfilePicture($user, $flash)
    {
        if(!(new File('img/profile-default.png'))->copy('uploads/files/profile_picture_' . strval($user->id) . '.png'))
        {
            $flash->warning(__("Your default picture could not be set... Please contact an administrator."));
        }
    }

    public function saveProfilePicture($file, $user, $flash)
    {
        $tmp = explode('/', $file['type']);  // Still this useless variable...
        $extension = end($tmp);

        if($file['size'] <= 5000000 && substr($file['type'], 0, strlen('image/')) === 'image/')
        {
            // The result file will be in '*.png' anyway, check below the real conversion...
            $destination = 'uploads/files/profile_picture_' . strval($user->id) . '.';

            if(move_uploaded_file($file['tmp_name'], $destination . $extension))
            {
                // Here we'll check if the picture is in PNG format, and convert it if it's not the case...
                if($extension !== 'png')
                {
                    $image = new \Imagick($destination . $extension);

                    if(!$image || !$image->setImageFormat('png') || !$image->writeImage($destination . 'png'))
                    {
                        $flash->warning('Your profile picture could not be converted to PNG format...');
                    }

                    if(!(new File($destination . $extension))->delete())
                    {
                        $flash->warning(_('The original file you uploaded could not be removed from our database.'));
                    }
                }
            }

            else
            {
                $flash->warning(__('Your profile picture could not be saved.'));
            }
        }

        else
        {
            $flash->warning(__("The file you uploaded does not validate our rules... Please contact an administrator."));
        }
    }
}
