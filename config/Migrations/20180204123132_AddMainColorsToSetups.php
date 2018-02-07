<?php
use Migrations\AbstractMigration;

use App\Model\Table\ResourcesTable;


class AddMainColorsToSetups extends AbstractMigration
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
        $table = $this->table('setups');
        $table->addColumn('main_colors', 'string', [
            'default' => null,
            'limit' => 128,
            'null' => false,
        ]);
        $table->update();

        foreach($this->fetchAll('SELECT setups.id, resources.src, resources.type FROM setups, resources WHERE resources.setup_id = setups.id AND resources.type = "SETUP_FEATURED_IMAGE"') as $result)
        {
            $this->execute('UPDATE setups SET main_colors="' . (new ResourcesTable)->extractMostUsedColorsFromImage('webroot/' . $result['src']) . '" WHERE id=' . $result['id']);
        }
    }
}
