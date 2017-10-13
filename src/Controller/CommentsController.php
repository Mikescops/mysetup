<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 */
class CommentsController extends AppController
{

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($setup_id = null)
    {
        $comment = $this->Comments->newEntity();

        if($this->request->is('post'))
        {
            $data = $this->request->getData();

            if(parent::captchaValidation($data))
            {
                // Let's set the id of the current logged in user
                $data['user_id'] = $this->request->session()->read('Auth.User.id');
                $data['setup_id'] = $setup_id;

                $comment = $this->Comments->patchEntity($comment, $data);

                if($this->Comments->save($comment))
                {
                    $this->Flash->success(__('The comment has been saved.'));

                    // If it's not him, let's inform the setup owner of this new comment
                    $setup = $this->Comments->Setups->get($setup_id);
                    if($data['user_id'] !== $setup['user_id'])
                    {
                        $this->loadModel('Notifications');
                        $this->Notifications->createNotification($setup['user_id'], '<a href="' . Router::url(['controller' => 'Setups', 'action' => 'view', $data['setup_id']]) . '"><img src="' . Router::url('/') . 'uploads/files/pics/profile_picture_' . $data['user_id'] . '.png" alt="__ALT">  <span><strong>' . h($this->Comments->Users->get($data['user_id'])['name']) . '</strong> __COMMENT <strong>' . h($setup['title']) . '</strong></span></a>');
                    }
                }

                else
                {
                    $this->Flash->error(__('The comment could not be saved. Please, try again.'));
                }
            }

            else
            {
                $this->Flash->warning(__('Google\'s CAPTCHA has detected you as a bot, sorry ! If you\'re a REAL human, please re-try :)'));
            }

            return $this->redirect($this->referer() . '#comment' . ($comment->id ? '-' . $comment->id : 's'));
        }

        $this->set(compact('comment'));
        $this->set('_serialize', ['comment']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $comment = $this->Comments->get($id);

        if($this->request->is(['patch', 'post', 'put']))
        {
            $comment = $this->Comments->patchEntity($comment, $this->request->getData());

            if($this->Comments->save($comment))
            {
                $this->Flash->success(__('The comment has been saved.'));
            }

            else
            {
                $this->Flash->error(__('The comment could not be saved. Please, try again.'));
            }

            return $this->redirect($this->referer() . '#comment' . ($comment->id ? '-' . $comment->id : 's'));
        }

        $this->set(compact('comment'));
        $this->set('_serialize', ['comment']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comment = $this->Comments->get($id);
        if ($this->Comments->delete($comment)) {
            $this->Flash->success(__('The comment has been deleted.'));
        } else {
            $this->Flash->error(__('The comment could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->referer() . '#comments');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // We've to disable security stuff on comments edition because the form concerned is just totally unpredictable
        $this->Security->config('unlockedActions', ['edit']);
    }

    public function isAuthorized($user)
    {
        if(isset($user))
        {
            if(in_array($this->request->action, ['edit', 'delete']))
            {
                if($this->Comments->isOwnedBy((int)$this->request->params['pass'][0], $user['id']))
                {
                    return true;
                }
            }

            else if($this->request->action === 'add')
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}
