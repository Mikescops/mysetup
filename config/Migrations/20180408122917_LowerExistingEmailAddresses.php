<?php
use Migrations\AbstractMigration;

class LowerExistingEmailAddresses extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        foreach($this->fetchAll('SELECT id, mail FROM users') as $user)
        {
            $this->execute('UPDATE users SET mail="' . strtolower($user['mail']) . '" WHERE id=' . $user['id']);
        }
    }
}
