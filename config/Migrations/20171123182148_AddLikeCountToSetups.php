<?php
use Migrations\AbstractMigration;

class AddLikeCountToSetups extends AbstractMigration
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
        $this->table('setups')->addColumn('like_count', 'integer', [
            'default' => 0,
            'null' => false,
        ])->save();

        $this->execute('UPDATE setups SET like_count=(SELECT COUNT(*) FROM likes WHERE setups.id = likes.setup_id)');
    }
}
