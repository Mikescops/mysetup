<?php
use Migrations\AbstractMigration;

class RemoveTypeFromCloudTags extends AbstractMigration
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
        $table = $this->table('cloud_tags');
        $table->removeColumn('type');
        $table->update();
    }
}
