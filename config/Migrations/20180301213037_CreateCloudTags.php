<?php
use Migrations\AbstractMigration;

use Cake\ORM\TableRegistry;

class CreateCloudTags extends AbstractMigration
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
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 64,
            'null' => false,
        ]);
        $table->addColumn('type', 'string', [
            'default' => null,
            'limit' => 32,
            'null' => false,
        ]);
        $table->create();

        $table = TableRegistry::get('cloud_tags');
        foreach([
            'INTEL',
            'Samsung',
            'AMD',
            'NVIDIA',
            'Roccat',
            'MSI',
            'BenQ',
            'iiyama',
            'Corsair',
            'Razer',
            'Asus',
            'Seagate',
            'Gigabyte',
            'Mad Catz',
            'Sapphire',
            'Logitech',
            'Hercules',
            'Crucial',
            'DELL',
            'Blue Microphones',
            'Acer',
            'HyperX',
            'GAMDIAS',
            'Western Digital',
            'LG',
            'KLIM',
            'PNY',
            'Xiaomi',
            'HP',
            'Kingstone',
            'Antec',
            'Canon',
            'Sony',
            'NIKON',
            'Pentax',
            'DJI'
        ] as $brand)
        {
            $tag = $table->newEntity();
            $tag->name = $brand;
            $tag->type = 'PRODUCTS_BRAND';
            $table->save($tag);
        }
    }
}
