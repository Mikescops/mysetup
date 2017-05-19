<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Notifications Controller
 *
 * @property \App\Model\Table\ResourcesTable $Notifications
 */
class NotificationsController extends AppController
{
    /*
        /!\ CAREFUL This is not the common `index()``method /!\
        With this very entity, we'll only allow 'index per user' method.
        The `$id` will represent the user id, NOT A NOTIFICATION ONE.
    */
    public function index()
    {
        if($this->request->is('get'))
        {
            $notifications = $this->Notifications->find()->where(['user_id' => $this->request->session()->read('Auth.User.id')])->all();

            $this->set(compact('notifications'));
            $this->set('_serialize', ['notifications']);
        }
    }

    /* AJAX CALLS ? */
    public function markAsRead()
    {
        if($this->request->is('get'))
        {
            $status = 500;
            $body   = null;

            $notification_id = $this->request->query['notification_id'];

            if($this->Notifications->exists(['id' => $notification_id]))
            {
                $notification = $this->Notifications->get($notification_id);
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
                $body = 'DOES_NOT_EXIST';
            }

            return new Response([
                'status' => $status,
                'body' => $body
            ]);
        }
    }

    public function markAsNonRead()
    {
        if($this->request->is('get'))
        {
            $status = 500;
            $body   = null;

            $notification_id = $this->request->query['notification_id'];

            if($this->Notifications->exists(['id' => $notification_id]))
            {
                $notification = $this->Notifications->get($notification_id);
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
                $body = 'DOES_NOT_EXIST';
            }

            return new Response([
                'status' => $status,
                'body' => $body
            ]);
        }
    }

    public function deleteNotification()
    {
        if($this->request->is('get'))
        {
            $status = 500;
            $body   = null;

            $notification_id = $this->request->query['notification_id'];

            if($this->Notifications->exists(['id' => $notification_id]))
            {
                if($this->Notifications->delete($this->Notifications->get($notification_id)))
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
                $body = 'DOES_NOT_EXIST';
            }

            return new Response([
                'status' => $status,
                'body' => $body
            ]);
        }
    }
    /* ____________ */

    public function isAuthorized($user)
    {
        if(isset($user))
        {
            if(in_array($this->request->action, ['markAsRead', 'markAsNonRead', 'deleteNotification']))
            {
                if($this->Notifications->isOwnedBy((int)$this->request->params['pass'][0], $user['id']))
                {
                    return true;
                }
            }

            else if($this->request->action === 'index')
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}
