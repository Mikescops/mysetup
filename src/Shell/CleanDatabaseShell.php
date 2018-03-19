<?php

namespace App\Shell;

use Cake\Console\Shell;

class CleanDatabaseShell extends Shell
{
    public function initialize()
    {
        parent::initialize();

        // We'll need and use these models to clean the database.
        $this->loadModel('Users');
        $this->loadModel('Requests');
        $this->loadModel('Notifications');
    }

    public function main()
    {
        $this->_cleanDatabase();

        return true;
    }

    protected function _cleanDatabase()
    {
        $this->out($this->nl(1));

        $this->out('mySetup.co DATABASE CLEANING');
        $this->hr();
        $this->out(
            '<success>' .
            'Users deleted : ' .
            $this->_cleanUsers() .
            '</success>'
        );
        $this->out(
            '<success>' .
            'Requests deleted : ' .
            $this->_cleanRequests() .
            '</success>'
        );
        $this->out(
            '<success>' .
            'Notifications deleted : ' .
            $this->_cleanNotifications() .
            '</success>'
        );
        $this->hr();

        $this->out($this->nl(1));
    }

    protected function _cleanUsers()
    {
        // Gets rid of unverified users, which have created their account 1 month ago (or later).
        return $this->Users->deleteAll([
            'mailVerification IS NOT' => null,
            'creationDate <' => new \DateTime('-1 month')
        ]);
    }

    protected function _cleanRequests()
    {
        // Gets rid of pending requests for setup ownership, which have been emitted for more than 1 month.
        return $this->Requests->deleteAll([
            'dateTime <' => new \DateTime('-1 month')
        ]);
    }

    protected function _cleanNotifications()
    {
        // Gets rid of read notifications, older than 1 month.
        return $this->Notifications->deleteAll([
            'new' => 0,
            'dateTime <' => new \DateTime('-1 month')
        ]);
    }
}
