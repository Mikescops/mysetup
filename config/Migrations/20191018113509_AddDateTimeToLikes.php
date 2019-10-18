<?php
use Migrations\AbstractMigration;

class AddDateTimeToLikes extends AbstractMigration
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
        $table = $this->table('likes');
        $table->addColumn('dateTime', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();

        // We don't know when already existing Likes entities have been created.
        // Let's set the associated Setup's creation dates.
        $this->execute('UPDATE likes SET dateTime=(SELECT creationDate FROM setups WHERE setups.id = likes.setup_id)');
    }
}
