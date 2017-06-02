<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Mailer\Email;
use Cake\Network\Http\Client;

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
        $this->hasMany('Notifications', [
            'foreignKey' => 'user_id',
            'dependent' => 'true',
            'cascadeCallbacks' => 'true'
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'creationDate' => 'new'
                ]
            ]
        ]);

        $this->addBehavior('Sitemap.Sitemap', ['changefreq' => 'daily']);
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
            ->notEmpty('name');

        $validator
            ->notEmpty('mail')
            ->add('mail', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'])
            ->add('mail', 'validFormat', [
                'rule' => 'email',
                'message' => __('We need a valid E-mail address')]);

        $validator
            ->notEmpty('password')
            ->add('password', 'length', [
                'rule' => ['minLength', 8],
                'message' => __('The password has to contain more than 8 characters')]);

        $validator
            ->notEmpty('preferredStore');

        $validator
            ->integer('verified')
            ->notEmpty('verified');

        $validator
            ->allowEmpty('mailVerification');

        $validator
            ->dateTime('creationDate')
            ->notEmpty('creationDate');

        $validator
            ->dateTime('lastLogginDate')
            ->allowEmpty('lastLogginDate');

        $validator
            ->allowEmpty('twitchToken');

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
        $rules->add($rules->isUnique(['mail', 'twitchToken']));

        return $rules;
    }

    public function afterDelete(Event $event, EntityInterface $entity)
    {
        if(!(new File('uploads/files/pics/profile_picture_' . $entity['id'] . '.png'))->delete())
        {
            // How can we inform the user about this error... ?
        }

        if(!(new Folder('uploads/files/' . $entity['id']))->delete())
        {
            // How can we inform the user about this error... ?
        }

        // Let's revoke the Twitch token access !
        if($entity['twitchToken'])
        {
            (new Client())->post('https://api.twitch.tv/kraken/oauth2/revoke?client_id=zym0nr99v74zljmo6z96st25rj6rzz&client_secret=b8mrbqfd9vsyjciyec560j44lh1muk&token=' . $entity['twitchToken']);
        }
    }

    public function sendEmail($receiver, $subject, $content)
    {
        Email::setConfigTransport('Zoho', [
            'host' => 'smtp.zoho.eu',
            'port' => 587,
            'username' => 'support@mysetup.co',
            'password' => 'Lsc\'etb1',
            'className' => 'Smtp',
            'tls' => true
        ]);

        $email = new Email('default');
        $email
            ->setTransport('Zoho')
            ->setFrom(['support@mysetup.co' => 'mySetup.co | Support'])
            ->setTo($receiver)
            ->setSubject("mySetup.co | " . $subject)
            ->setEmailFormat('html')
            ->send($content);
    }

    public function saveDefaultProfilePicture($user, $flash)
    {
        if(!file_exists('uploads/files/pics') and !mkdir('uploads/files/pics', 0755))
        {
            $flash->error(__('An internal error occurred while creating your profile picture. Please contact an administrator.'));
            return;
        }

        if(!(new File('img/profile-default.png'))->copy('uploads/files/pics/profile_picture_' . strval($user->id) . '.png'))
        {
            $flash->warning(__("Your default picture could not be set... Please contact an administrator."));
        }
    }

    public function saveProfilePicture($file, $user, $flash)
    {
        if($file['size'] <= 5000000 && substr($file['type'], 0, strlen('image/')) === 'image/' && !strpos($file['type'], 'svg') && !strpos($file['type'], 'gif'))
        {
            if(!file_exists('uploads/files/pics') and !mkdir('uploads/files/pics', 0755))
            {
                $flash->error(__('An internal error occurred while saving your profile picture. Please contact an administrator.'));
                return;
            }

            // A temporary path to the image, '.png' anyway (check the real conversion below...)
            $destination = 'uploads/files/pics/profile_picture_' . strval($user->id) . '.png';

            if(move_uploaded_file($file['tmp_name'], $destination))
            {
                $image = new \Imagick($destination);

                // This is the scenario: we compress the image, apply a Gaussian blur, and fall back to a PNG format before cropping & storing it...
                if(!$image || !$image->setImageCompressionQuality(85) || !$image->gaussianBlurImage(0.8, 10) || !$image->setImageFormat('png') || !$image->cropThumbnailImage(100, 100) || !$image->writeImage($destination))
                {
                    $flash->warning(__('Your profile picture could not be compressed, resized, converted to a PNG format or saved... Please contact an administrator.'));
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

    public function getNewRandomID()
    {
        $id = null;

        // Here we'll assign a random id to this new user
        do {
            $id = mt_rand() + 1;
        } while($this->find()->where(['id' => $id])->count() !== 0);

        return $id;
    }

    public function getRandomString($length = 16)
    {
        return substr(md5(mt_rand()), 0, $length);
    }
}
