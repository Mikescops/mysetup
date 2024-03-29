<?php

namespace App\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Mailer\Email;
use Cake\Http\Client;
use Cake\Routing\Router;

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
        $this->hasMany('Requests', [
            'foreignKey' => 'user_id',
            'dependent' => 'true',
            'cascadeCallbacks' => 'true'
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'creationDate' => 'new',
                    'modificationDate' => 'always'
                ]
            ]
        ]);
    }

    /** Let's get the real url of user **/
    public function getUrl(Entity $entity)
    {
        return Router::url('/users/' . $entity->id . '-' . Text::slug($entity->name), true);
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
            ->add('mail', 'unique', [
                'rule' => function ($email, $context) {
                    if ($context['newRecord']) {
                        // For Users.{add,twitch}, we just check the non-existence of an entity already having this email address.
                        return !$this->exists(['mail' => strtolower($email)]);
                    }

                    // This is for Users.edit, we check that ONLY ONE entity got this (new ?) email address.
                    return ($this->find()->where(['mail' => strtolower($email), 'id !=' => $context['data']['id']])->count() === 0);
                },
                'message' => __('This E-mail address is already used')
            ])
            ->add('mail', 'validFormat', [
                'rule' => 'email',
                'message' => __('We need a valid E-mail address')
            ]);

        $validator
            ->notEmpty('password')
            ->add('password', 'length', [
                'rule' => ['minLength', 8],
                'message' => __('The password has to contain more than 8 characters')
            ]);

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
            ->dateTime('modificationDate')
            ->notEmpty('modificationDate');

        $validator
            ->dateTime('lastLogginDate')
            ->allowEmpty('lastLogginDate');

        $validator
            ->integer('mainSetup_id')
            ->allowEmpty('mainSetup_id');

        $validator
            ->allowEmpty('twitchToken');

        $validator
            ->allowEmpty('twitchUserId');

        $validator
            ->allowEmpty('uwebsite');

        $validator
            ->allowEmpty('ufacebook');

        $validator
            ->allowEmpty('utwitter');

        $validator
            ->allowEmpty('utwitch');

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
        $rules->add($rules->isUnique(['name']));
        $rules->add($rules->isUnique(['mail']));
        $rules->add($rules->isUnique(['twitchUserId']));

        $rules->add(
            function ($entity) {
                if (isset($entity['timeZone']) and array_key_exists($entity['timeZone'], $this->timezones)) {
                    return true;
                }

                return false;
            },
            'timeZoneIntegrity_rule'
        );

        $rules->add(
            function ($entity) {
                if (isset($entity['preferredStore']) and in_array($entity['preferredStore'], ['US', 'UK', 'ES', 'IT', 'FR', 'DE'])) {
                    return true;
                }

                return false;
            },
            'preferredStoreIntegrity_rule'
        );

        $rules->add(
            function ($entity) {
                // Here we only allow a "0" value, or an ID existing in the Setups DB
                if ($entity['mainSetup_id'] !== 0 and !$this->Setups->exists(['id' => $entity['mainSetup_id']])) {
                    return false;
                }

                return true;
            },
            'mainSetup_idIntegrity_rule'
        );

        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entity)
    {
        $entity->mail = strtolower($entity->mail);
    }

    public function afterDelete(Event $event, EntityInterface $entity)
    {
        if (!(new File('uploads/files/pics/profile_picture_' . $entity['id'] . '.png'))->delete()) {
            // How can we inform the user about this error... ?
        }

        if (!(new Folder('uploads/files/' . $entity['id']))->delete()) {
            // How can we inform the user about this error... ?
        }

        // Let's revoke the Twitch token access !
        if ($entity['twitchToken']) {
            (new Client())->post('https://api.twitch.tv/kraken/oauth2/revoke', [
                'client_id'     => $this->getTwitchAPIID(),
                'client_secret' => $this->getTwitchAPISecret(),
                'token'         => $entity['twitchToken']
            ]);
        }
    }

    // A new method to retrieve users from `Pages@search()` function
    // Please refer to `SetupsTable@getSetups()` method for advanced documentation.
    public function getUsers($query = null)
    {
        return $this->find('all', [
            'conditions' => [
                'mailVerification IS' => null,
                'LOWER(name)    LIKE' => '%' . strtolower($query) . '%'
            ],
            'fields' => [
                'id',
                'name',
                'verified',
                'modificationDate',
                'utwitch'
            ],
            'contain' => [
                'Setups' => [
                    'fields' => [
                        'id',
                        'user_id'
                    ],
                    'conditions' => [
                        'status' => 'PUBLISHED'
                    ]
                ]
            ]
        ])
            ->distinct()
            ->toArray();
    }

    public function getTwitchAPIID()
    {
        return Configure::read('Credentials.Twitch.id');
    }
    public function getTwitchAPISecret()
    {
        return Configure::read('Credentials.Twitch.secret');
    }

    public function getEmailObject($receiver, $subject, $recipient_name)
    {
        return (
            (new Email())
            ->setEmailFormat('html')
            ->setLayout('layout')
            ->setTo($receiver)
            ->setSubject($subject))->setViewVars(['recipient_name' => $recipient_name]);
    }

    public function saveDefaultProfilePicture($user, $flash)
    {
        if (!file_exists('uploads/files/pics') and !mkdir('uploads/files/pics', 0755)) {
            $flash->error(__('An internal error occurred while creating your profile picture. Please contact an administrator.'));
            return;
        }

        if (!(new File('img/profile-default.png'))->copy('uploads/files/pics/profile_picture_' . strval($user->id) . '.png')) {
            $flash->warning(__('Your default picture could not be set... Please contact an administrator.'));
        }
    }

    public function saveProfilePicture($file, $user, $flash)
    {
        if ($file['size'] <= 5000000 && substr($file['type'], 0, strlen('image/')) === 'image/' && !strpos($file['type'], 'svg') && !strpos($file['type'], 'gif')) {
            if (!file_exists('uploads/files/pics') and !mkdir('uploads/files/pics', 0755)) {
                $flash->error(__('An internal error occurred while saving your profile picture. Please contact an administrator.'));
                return;
            }

            // A temporary path to the image, '.png' anyway (check the real conversion below...)
            $destination = 'uploads/files/pics/profile_picture_' . strval($user->id) . '.png';

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $image = new \Imagick($destination);

                // This is the scenario: we compress the image, apply a Gaussian blur, and fall back to a PNG format before cropping & storing it...
                if (!$image || !$image->setImageCompressionQuality(85) || !$image->gaussianBlurImage(0.8, 10) || !$image->setImageFormat('png') || !$image->cropThumbnailImage(100, 100) || !$image->writeImage($destination)) {
                    $flash->warning(__('Your profile picture could not be compressed, resized, converted to a PNG format or saved... Please contact an administrator.'));
                }
            } else {
                $flash->warning(__('Your profile picture could not be saved.'));
            }
        } else {
            $flash->warning(__('The file you uploaded does not validate our rules... Please contact an administrator.'));
        }
    }

    public function getNewRandomID()
    {
        $id = null;

        // Here we'll assign a random id to this new user
        do {
            $id = mt_rand() + 1;
        } while ($this->find()->where(['id' => $id])->count() !== 0);

        return $id;
    }

    public function getLocaleByCountryID($country_id)
    {
        $locale = null;

        // `FR-*` locales
        if (preg_match('/^fr([-_].*)?$/i', $country_id)) {
            $locale = 'fr_FR';
        }

        // `ES-*` locales
        elseif (preg_match('/^es([-_].*)?$/i', $country_id)) {
            $locale = 'es_ES';
        }

        // `IT-*` locales
        elseif (preg_match('/^it([-_].*)?$/i', $country_id)) {
            $locale = 'it_IT';
        }

        // All the others (yet)
        else {
            $locale = 'en_GB';
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
        // This new user has been created and saved, let's keep a local copy of its profile picture
        $destination = 'uploads/files/pics/profile_picture_' . $user_id . '.png';
        $file = fopen($destination, 'w+');
        $curl = curl_init($remote_url);
        curl_setopt($curl, CURLOPT_FILE, $file);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1000);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_exec($curl);

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            return false;
        }

        curl_close($curl);
        fclose($file);

        // Let's resize (and convert ?) this new image
        $image = new \Imagick($destination);
        if (!$image || !$image->setImageFormat('png') || !$image->cropThumbnailImage(100, 100) || !$image->writeImage($destination)) {
            $flash->warning(__('Your profile picture could not be resized, converted to a PNG format or saved... Please contact an administrator.'));
            return false;
        }

        return true;
    }

    /*
        A simple getter method to retrieve the "active" users on the website.
        The returned users :
            * Have at least one PUBLISHED setup
            * Have a verified email address
            * Have logged themselves in during the past month
            * ... are shuffled !
    */
    public function getActiveUsers($n = 8)
    {
        return $this->find('all', [
            'limit' => $n,
            'order' => 'RAND()',
            'fields' => [
                'id',
                'name',
                'modificationDate',
                'utwitch'
            ],
            'conditions' => [
                'mainSetup_id !=' => 0,
                'mailVerification IS' => null,
                'lastLogginDate >=' => new \DateTime('-1 month')
            ]
        ])->matching('Setups', function ($q) {
            return $q->where(['Setups.status' => 'PUBLISHED']);
        })->distinct()->toArray();
    }

    public function synchronizeSessionWithUserEntity($session, $user = null, $admin = false)
    {
        if (!$user) {
            $user = $this->get($session->read('Auth.User.id'));
        }

        // Simply sets a boolean useful in view in the future
        if ($admin) {
            $user['admin'] = true;
        }

        $session->write('Auth.User', $user);
    }
}
