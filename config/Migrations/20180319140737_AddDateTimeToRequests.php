<?php
use Migrations\AbstractMigration;

class AddDateTimeToRequests extends AbstractMigration
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
        $table = $this->table('requests');
        $table->addColumn('dateTime', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
