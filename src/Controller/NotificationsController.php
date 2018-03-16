<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Response;
use Cake\Event\Event;

/**
 * Notifications Controller
 *
 * @property \App\Model\Table\ResourcesTable $Notifications
 */
class NotificationsController extends AppController
{
    /* AJAX CALLS */
    public function getNotifications()
    {
        if($this->request->is('ajax'))
        {
            $results = $this->Notifications->find('all', [
                'conditions' => [
                    'user_id' => $this->Auth->user('id'),
                    'new' => 1
                ],
                'order' => [
                    'dateTime' => 'DESC'
                ],
                'limit' => $this->request->getQuery('n', 4)
            ]);

            // Here we'll concatenate 'on-the-go' a "time ago with words" to the notifications content and makes some translations (it was the actually the goal of this method...)
            foreach($results as $result)
            {
                $result['content'] = str_replace('</a>', ' <span><i class="fa fa-clock-o"></i> ' . $result['dateTime']->timeAgoInWords() . '</span></a>', $result['content']);

                if(strpos($result['content'], $this->Notifications->types['like']))
                {
                    $result['content'] = str_replace($this->Notifications->types['alt'], __('Liker\'s profile picture'), $result['content']);
                    $result['content'] = str_replace($this->Notifications->types['like'], __('liked your setup'), $result['content']);
                }

                else if(strpos($result['content'], $this->Notifications->types['comment']))
                {
                    $result['content'] = str_replace($this->Notifications->types['alt'], __('Commenter\'s profile picture'), $result['content']);
                    $result['content'] = str_replace($this->Notifications->types['comment'], __('commented your setup'), $result['content']);
                }
            }

            return new Response([
                'status' => 200,
                'type' => 'json',
                'body' => json_encode($results)
            ]);
        }
    }

    public function markAsRead()
    {
        if($this->request->is('ajax'))
        {
            $status = 500;
            $body   = null;

            $notification_id = $this->request->getQuery('notification_id');

            if($this->Notifications->exists(['id' => $notification_id]))
            {
                $notification = $this->Notifications->get($notification_id);

                if($notification->user_id == $this->Auth->user('id'))
                {
                    $notification->new = 0;

                    if($this->Notifications->save($notification))
                    {
                        $status = 200;
                        $body   = 'MARKED';
                    }

                    else
                    {
                        $body = 'NOT_MARKED';
                    }
                }

                else
                {
                    $body = 'NOT_AUTHORIZED';
                }
            }

            else
            {
                $body = 'DOES_NOT_EXIST';
            }

            return new Response([
                'status' => $status,
                'type' => 'json',
                'body' => json_encode($body)
            ]);
        }
    }

    /**
     * This method is currently not used within the app.
     * Maybe in the future ?
     */
    public function markAsNonRead()
    {
        if($this->request->is('ajax'))
        {
            $status = 500;
            $body   = null;

            $notification_id = $this->request->getQuery('notification_id');

            if($this->Notifications->exists(['id' => $notification_id]))
            {
                $notification = $this->Notifications->get($notification_id);

                if($notification->user_id == $this->Auth->user('id'))
                {
                    $notification->new = 1;

                    if($this->Notifications->save($notification))
                    {
                        $status = 200;
                        $body   = 'MARKED';
                    }

                    else
                    {
                        $body = 'NOT_MARKED';
                    }
                }

                else
                {
                    $body = 'NOT_AUTHORIZED';
                }
            }

            else
            {
                $body = 'DOES_NOT_EXIST';
            }

            return new Response([
                'status' => $status,
                'type' => 'json',
                'body' => json_encode($body)
            ]);
        }
    }

    /**
     * This method is currently not used within the app.
     * Maybe in the future ?
     */
    public function deleteNotification()
    {
        if($this->request->is('ajax'))
        {
            $status = 500;
            $body   = null;

            $notification_id = $this->request->getQuery('notification_id');

            if($this->Notifications->exists(['id' => $notification_id]))
            {
                $notification = $this->Notifications->get($notification_id);

                if($notification->user_id == $this->Auth->user('id'))
                {
                    if($this->Notifications->delete($notification))
                    {
                        $status = 200;
                        $body   = 'DELETED';
                    }

                    else
                    {
                        $body = 'NOT_DELETED';
                    }
                }

                else
                {
                    $body = 'NOT_AUTHORIZED';
                }
            }

            else
            {
                $body = 'DOES_NOT_EXIST';
            }

            return new Response([
                'status' => $status,
                'type' => 'json',
                'body' => json_encode($body)
            ]);
        }
    }
    /* __________ */

    public function isAuthorized($user)
    {
        if(isset($user))
        {
            return true;
        }

        return parent::isAuthorized($user);
    }
}
