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
    public $timezones = ["Pacific/Samoa" => "(GMT -11:00) Midway Island, Samoa", "Pacific/Honolulu" => "(GMT -10:00) Hawaii", "America/Anchorage" => "(GMT -9:00) Alaska", "America/Los_Angeles" => "(GMT -8:00) Pacific Time (US & Canada)", "America/Denver" => "(GMT -7:00) Mountain Time (US & Canada)", "America/Chicago" => "(GMT -6:00) Central Time (US & Canada), Mexico City", "America/New_York" => "(GMT -5:00) Eastern Time (US & Canada), Bogota, Lima", "Atlantic/Bermuda" => "(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz", "Canada/Newfoundland" => "(GMT -3:30) Newfoundland", "Brazil/East" => "(GMT -3:00) Brazil, Buenos Aires, Georgetown", "Atlantic/Azores" => "(GMT -2:00) Mid-Atlantic", "Atlantic/Cape_Verde" => "(GMT -1:00 hour) Azores, Cape Verde Islands", "Europe/London" => "(GMT) Western Europe Time, London, Lisbon, Casablanca", "Europe/Brussels" => "(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris", "Europe/Helsinki" => "(GMT +2:00) Kaliningrad, South Africa", "Asia/Baghdad" => "(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg", "Asia/Tehran" => "(GMT +3:30) Tehran", "Asia/Baku" => "(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi", "Asia/Kabul" => "(GMT +4:30) Kabul", "Asia/Karachi" => "(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent", "Asia/Calcutta" => "(GMT +5:30) Bombay, Calcutta, Madras, New Delhi", "Asia/Dhaka" => "(GMT +6:00) Almaty, Dhaka, Colombo", "Asia/Bangkok" => "(GMT +7:00) Bangkok, Hanoi, Jakarta", "Asia/Hong_Kong" => "(GMT +8:00) Beijing, Perth, Singapore, Hong Kong", "Asia/Tokyo" => "(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk", "Australia/Adelaide" => "(GMT +9:30) Adelaide, Darwin", "Pacific/Guam" => "(GMT +10:00) Eastern Australia, Guam, Vladivostok", "Asia/Magadan" => "(GMT +11:00) Magadan, Solomon Islands, New Caledonia", "Pacific/Fiji" => "(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka"];

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

        $this->addBehavior('Sitemap.Sitemap', ['changefreq' => 'daily', 'lastmod' => 'lastLogginDate']);
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
            ->notEmpty('timeZone');

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

        $validator
            ->allowEmpty('uwebsite');

        $validator
            ->allowEmpty('ufacebook');

        $validator
            ->allowEmpty('utwitter');

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

        $rules->
            add(function($entity) {
                if(isset($entity['timeZone']) and array_key_exists($entity['timeZone'], $this->timezones))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            },
            'timeZoneIntegrity_rule');

        $rules->
            add(function($entity) {
                if(isset($entity['preferredStore']) and in_array($entity['preferredStore'], ['US', 'UK', 'ES', 'IT', 'FR', 'DE']))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            },
            'preferredStoreIntegrity_rule');

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

    public function getLocaleByCountryID($country_id)
    {
        $locale = null;

        switch(strtoupper($country_id))
        {
            case 'FR':
            case 'ES':
            case 'IT':
            case 'DE':
                $locale = strtolower($country_id) . '-' . strtoupper($country_id);
                break;

            case 'US':
            case 'UK':
            default:
                $locale = 'en-GB';
                break;
        }

        return $locale;
    }

    public function prepareSessionForUser($session, $user)
    {
        $session->write('Config.language', $this->getLocaleByCountryID($user['preferredStore']));
        $session->write('Config.timezone', $user['timeZone']);
    }

    public function saveRemoteProfilePicture($user_id, $remote_url, $flash)
    {
        debug($remote_url);

        // This new user has been created and saved, let's keep a local copy of its profile picture
        $destination = 'uploads/files/pics/profile_picture_' . $user_id . '.png';
        $file = fopen($destination, 'w+');
        $curl = curl_init($remote_url);
        /* curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); */
        curl_setopt($curl, CURLOPT_FILE, $file);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1000);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0');
        /* curl_setopt($curl, CURLOPT_VERBOSE, true); */
        curl_exec($curl);

        debug(curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if(curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200)
        {
            return false;
        }

        curl_close($curl);
        fclose($file);

        // Let's resize (and convert ?) this new image
        $image = new \Imagick($destination);
        if(!$image || !$image->setImageFormat('png') || !$image->cropThumbnailImage(100, 100) || !$image->writeImage($destination))
        {
            $flash->warning(__('Your profile picture could not be resized, converted to a PNG format or saved... Please contact an administrator.'));
            return false;
        }

        else
        {
            return true;
        }
    }
}
