<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Utility\Text;
use Cake\Filesystem\File;


class ArticlesTable extends Table
{
    public $categories = ['dev' => 'Development', 'edito' => 'Editorial',  'event' => 'Event', 'interview' => 'Interview', 'news' => 'News'];

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('articles');
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

        $this->addBehavior('Sitemap.Sitemap', ['changefreq' => 'daily']);
    }

    /** Let's get the real url of article **/
    public function getUrl(\Cake\ORM\Entity $entity)
    {
        return \Cake\Routing\Router::url('/blog/'.$entity->id.'-'.\Cake\Utility\Text::slug($entity->title), true);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('content', 'create')
            ->notEmpty('content');

        $validator
            ->requirePresence('picture', 'create')
            ->notEmpty('picture');

        $validator
            ->dateTime('dateTime')
            ->notEmpty('dateTime');

        $validator
            ->requirePresence('category', 'create')
            ->notEmpty('category');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->
            add(function($entity) {
                if(isset($entity['category']) and array_key_exists($entity['category'], $this->categories))
                {
                    return true;
                }

                else
                {
                    return false;
                }
            },
            'categoryIntegrity_rule');

        return $rules;
    }

    public function isOwnedBy($article_id, $user_id)
    {
        return $this->exists(['id' => $article_id, 'user_id' => $user_id]);
    }

    public function afterDelete(Event $event, EntityInterface $entity)
    {
        // Once the article has been deleted, let's remove the image associated
        $this->deletePicture($entity['picture']);
    }

    public function savePicture($file, $flash)
    {
        if($file['error'] === 0 && $file['size'] <= 5000000 && substr($file['type'], 0, strlen('image/')) === 'image/' && !strpos($file['type'], 'svg') && !strpos($file['type'], 'gif'))
        {
            $path = 'uploads/files/articles';

            if(!file_exists($path) and !mkdir($path, 0755))
            {
                $flash->error(__("An internal error occurred while saving your image... Please contact an administrator."));
            }

            else
            {
                $path .= '/' . Text::uuid() . '.jpg';

                if(move_uploaded_file($file['tmp_name'], $path))
                {
                    $image = new \Imagick($path);

                    if(!$image->setImageFormat('jpg') || !$image->setImageCompressionQuality(85) /*|| !$image->gaussianBlurImage(0.8, 10) */|| !$image->cropThumbnailImage(1080, 500) || !$image->writeImage($path))
                    {
                        $flash->warning(__("Your image could not be converted to JPG, compressed, resized or saved... Please contact an administrator."));
                    }

                    else
                    {
                        return $path;
                    }
                }

                else
                {
                    $flash->warning(__('Your image could not be saved.'));
                }
            }

        }

        else
        {
            $flash->warning(__("Your image does not validate our rules... Please contact an administrator."));
        }

        return null;
    }

    public function deletePicture($path)
    {
        if(!(new File($path))->delete())
        {
            // We've to figure out a way to throw this error to the user...
        }
    }
}
