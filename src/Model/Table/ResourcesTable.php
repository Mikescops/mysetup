<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Filesystem\File;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\Utility\Text;
use Cake\Core\Configure;

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

        $validator
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
                    // ... and each set one has to correspond to an existing entity in the DB
                    if(isset($entity['user_id']) and !$this->Users->exists(['id' =>  $entity['user_id']]))
                    {
                        return false;
                    }

                    if(isset($entity['setup_id']) and !$this->Setups->exists(['id' =>  $entity['setup_id']]))
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
        // If this resource is an image, let's REALLY delete it !
        if(in_array($entity['type'], ['SETUP_GALLERY_IMAGE', 'SETUP_FEATURED_IMAGE']))
        {
            if(!(new File($entity['src']))->delete())
            {
                // We've to figure out a way to throw this error to the user...
            }
        }
    }

    // A new method to retrieve resources from `Pages@search()` function
    // Please refer to `SetupsTable@getSetups()` method for advanced documentation.
    public function getResources($query = null, $number = null)
    {
        $query = $this->find('all', [
            'conditions' => [
                    'type'                                                             => 'SETUP_PRODUCT',
                    'CONVERT(Resources.title USING utf8) COLLATE utf8_general_ci LIKE' => '%' . rawurlencode($query) . '%'
            ],
            'fields' => [
                'title',
                'href',
                'src'
            ]
        ])
        ->matching('Setups', function($q) {
            return $q->where(['Setups.status' => 'PUBLISHED']);
        });

        if($number !== null)
        {
            $query->limit($number);
        }

        return $query->distinct('Resources.title');
    }

    public function saveResourceProducts($products, $setup, $flash, $admin = false)
    {
        $is_okay = true;

        // "Title_1;href_1;src_1,Title_2;href_2;src_2,...,Title_n;href_n;src_n"
        foreach(explode(',', $products) as $elements)
        {
            $elements = explode(';', $elements);
            if(count($elements) == 3)
            {
                // Let's parse the URLs provided, in order to check their authenticity
                $parsing_2 = parse_url(urldecode($elements[1]));
                $parsing_3 = parse_url(urldecode($elements[2]));

                // Check that the domain names of the current URLs are accepted !
                if(((!isset($parsing_2['host']) || !in_array($parsing_2['host'], Configure::read('WhiteList.Resources.Products.href'))) ||
                    (!isset($parsing_3['host']) || !in_array($parsing_3['host'], Configure::read('WhiteList.Resources.Products.src')))) &&
                    !$admin)
                {
                    $flash->warning(__('One of the products you chose does not validate our rules... Please contact an administrator.'));
                    $this->Users->Notifications->warnAdminAboutUnsavedLink($setup->user_id, $elements[1] . ' OR ' . $elements[2], 'PRODUCT');
                    continue;
                }

                // Let's create a new entity to store these data !
                $resource = $this->newEntity([
                    'user_id'  => $setup->user_id,
                    'setup_id' => $setup->id,
                    'type'     => 'SETUP_PRODUCT',
                    // Here is the trick to prevent some special characters not encoded in JS
                    'title'    => rawurlencode(urldecode($elements[0])),
                    'href'     => $elements[1],
                    'src'      => $elements[2]
                ]);

                // If the resource can't be saved atm, we throw an error...
                if(!$this->save($resource))
                {
                    $flash->warning(__('One of your resources could not be saved... Please contact an administrator.'));
                    $is_okay = false;
                }
            }
        }

        // We'll be ignored for sure, but at least it'll be implemented :100:
        return $is_okay;
    }

    public function saveResourceImage($file, $setup, $type, $flash)
    {
        if(substr($file['type'], 0, strlen('image/')) === 'image/' && !strpos($file['type'], 'svg') && !strpos($file['type'], 'gif'))
        {
            if(!file_exists('uploads/files/' . $setup->user_id) and !mkdir('uploads/files/' . $setup->user_id, 0755))
            {
                $flash->error(__('An internal error occurred while saving your images... Please contact an administrator.'));
                return false;
            }

            /*
                Note to developers: The following is a bit tricky, be careful to read this to understand everything.

                As Imagick library has many problems with PNG images (compression is none, even worst sometimes, with wide images), we decided to convert them into JPG format. This is the scenario:

                * Whatever the file format is, we move the image into the owner's directory, and renamed it as 'UUID.jpg' (even if it's a PNG !!) ;
                * We convert the image into a JPG format ;
                * We compress it ;
                //* We apply a little Gaussian blur to optimize a little more without much loss ;
                * We crop the image into featured / gallery format (depends on the case) ;
                * We save the new obtained image.
            */

            $destination = 'uploads/files/' . $setup->user_id . '/' . Text::uuid() . '.jpg';

            // Simple shortcut for below ternaries
            $featured = ($type === 'SETUP_FEATURED_IMAGE' ? true : false);

            // Let's create an Imagick instance
            $image = new \Imagick();

            // We read the blob in base64 from the array
            $image->readImageBlob(base64_decode(explode(",", $file['image'])[1]));

            if(!$image->setImageFormat('jpg') || !$image->setImageCompressionQuality(85) /*|| !$image->gaussianBlurImage(0.8, 10) */|| !$image->cropThumbnailImage(($featured ? 1080 : 1366), ($featured ? 500 : 768)) || !$image->writeImage($destination))
            {
                $flash->warning(__('One of your image could not be converted to JPG, compressed, resized or saved... Please contact an administrator.'));
                return false;
            }

            // The image operations were successful, let's save this resource entity into the DB !
            $resource = $this->newEntity([
                'user_id'  => $setup->user_id,
                'setup_id' => $setup->id,
                'type'     => $type,
                'title'    => null,
                'href'     => null,
                'src'      => $destination
            ]);

            if(!$this->save($resource))
            {
                $flash->warning(__('One of your resources could not be saved... Please contact an administrator.'));
                return false;
            }

            return true;
        }

        $flash->warning(__('One of the files you uploaded does not validate our rules... Please contact an administrator.'));
        return false;
    }

    public function saveResourceVideo($video, $setup, $type, $flash)
    {
        $parsing = parse_url($video);

        if(isset($parsing['host']) &&
           in_array(str_replace('www.', '', $parsing['host']), Configure::read('WhiteList.Resources.Video')))
        {
            // Let's create a new entity to store these data !
            $resource = $this->newEntity([
                'user_id'  => $setup->user_id,
                'setup_id' => $setup->id,
                'type'     => 'SETUP_VIDEO_LINK',
                'title'    => null,
                'href'     => null,
                'src'      => $video
            ]);

            // If the resource can't be saved atm, we throw an error...
            if(!$this->save($resource))
            {
                $flash->warning(__('One of your resources could not be saved... Please contact an administrator.'));
                return false;
            }

            return true;
        }

        $flash->warning(__('The video link you chose does not validate our rules... Please contact an administrator.'));
        $this->Users->Notifications->warnAdminAboutUnsavedLink($setup->user_id, $video, 'VIDEO');
        return false;
    }

    /* This function fetches images from `gallery0, gallery1, ..., gallery4` inputs, and replaces old ones if existing ! */
    public function saveGalleryImages($setup, $data, $flash)
    {
        $is_okay = true;

        /* Here we'll compare the uploaded images to the new ones (in the 5 hidden inputs) */
        $galleries = $this->find('all', ['order' => ['id' => 'ASC']])->where(['setup_id' => $setup->id, 'user_id' => $setup->user_id, 'type' => 'SETUP_GALLERY_IMAGE'])->toArray();

        if(isset($galleries[0]))
        {
            $this->delete($galleries[0]);
        }
        if(!empty($data['gallery0'][0]))
        {
            $is_okay = $this->saveResourceImage((array) json_decode($data['gallery0'][0])->output, $setup, 'SETUP_GALLERY_IMAGE', $flash);
        }

        if(isset($galleries[1]))
        {
            $this->delete($galleries[1]);
        }
        if(!empty($data['gallery1'][0]))
        {
            $is_okay = $this->saveResourceImage((array) json_decode($data['gallery1'][0])->output, $setup, 'SETUP_GALLERY_IMAGE', $flash);
        }

        if(isset($galleries[2]))
        {
            $this->delete($galleries[2]);
        }
        if(!empty($data['gallery2'][0]))
        {
            $is_okay = $this->saveResourceImage((array) json_decode($data['gallery2'][0])->output, $setup, 'SETUP_GALLERY_IMAGE', $flash);
        }

        if(isset($galleries[3]))
        {
            $this->delete($galleries[3]);
        }
        if(!empty($data['gallery3'][0]))
        {
            $is_okay = $this->saveResourceImage((array) json_decode($data['gallery3'][0])->output, $setup, 'SETUP_GALLERY_IMAGE', $flash);
        }

        if(isset($galleries[4]))
        {
            $this->delete($galleries[4]);
        }
        if(!empty($data['gallery4'][0]))
        {
            $is_okay = $this->saveResourceImage((array) json_decode($data['gallery4'][0])->output, $setup, 'SETUP_GALLERY_IMAGE', $flash);
        }

        return $is_okay;
    }

    // This method will handle an owner change (so as to move images to the new directory)
    public function changeSetupsResourcesOwner($setup_id, $old_user_id, $new_user_id, $flash)
    {
        // First, let's check the existence (or add) a new folder to store setup images
        if(!file_exists('uploads/files/' . $new_user_id) and !mkdir('uploads/files/' . $new_user_id, 0755))
        {
            // Well... We have to hope this does not fail :/
            // Sorry for the new owner as he'll surely lose its images...
        }

        // For each resource...
        foreach($this->find('all', [
            'conditions' => [
                'setup_id' => $setup_id,
                'user_id' => $old_user_id
            ]
        ]) as $resource)
        {
            // ... we update the `user_id` with the new owner ID
            $resource->user_id = $new_user_id;

            // If this resource is an image, let's move it into the new owner's directory
            if(in_array($resource->type, ['SETUP_FEATURED_IMAGE', 'SETUP_GALLERY_IMAGE']))
            {
                // Only replaces the first value found (in order to avoid a bug with UIID members, not likely but possible).
                $new_path = preg_replace('/' . $old_user_id . '/', $new_user_id, $resource->src, 1);

                if(rename($resource->src, $new_path))
                {
                    $resource->src = $new_path;
                }

                else
                {
                    // This image couldn't be moved, let's delete it (?)
                    $this->delete($resource);

                    $flash->warning(__('One of the setup images could not be migrated.'));
                }
            }

            $this->save($resource);
        }
    }

    /*
        This method extract the most used colors from an image given (with its path).
        The returned array will be as :
        [
            [R, G, B],  // Most used color within the right area of the image (ratio=1/5 from the right)
            [R, G, B],  // First most used color of the whole image
            [R, G, B],  // Second most used color of the whole image
            [R, G, B],  // Third most used color of the whole image
            // ... and so one, because the package is broken (https://github.com/ksubileau/color-thief-php/issues/5)
        ]
    */
    public function extractMostUsedColorsFromImage($path)
    {
        try
        {
            $rgb_colors = \ColorThief\ColorThief::getPalette($path, $colorCount=3, $quality=10);

            // First item (simulates an `array_push` from the head)
            $size = (new \Imagick($path))->getImageGeometry();
            array_unshift($rgb_colors, \ColorThief\ColorThief::getPalette($path, $colorCount=3, $quality=2, $area=[
                'x' => $size['width'] * (4 / 5),
                'y' => 0,
                'w' => $size['width'] * (1 / 5),
                'h' => $size['height']
            ])[0]);
        }
        catch(\RuntimeException $e)
        {
            $rgb_colors = [
                [0, 0, 0]
            ];
        }

        return json_encode($rgb_colors);
    }
}
