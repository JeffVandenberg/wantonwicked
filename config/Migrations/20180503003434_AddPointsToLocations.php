<?php
use Migrations\AbstractMigration;
use Phinx\Db\Table\Index;

class AddPointsToLocations extends AbstractMigration
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
        $table = $this->table('locations');
        $table->addColumn('point', 'text', [
            'null' => false,
            'default' => null
        ]);
        $table->addColumn('slug', 'string',[
            'length' => 255,
            'null' => false,
            'default' => null
        ]);
        $table->addIndex(
            (new Index())
                ->setColumns(['district_id'])
                ->setName('district')
        );
        $table->addIndex(
            (new Index())
                ->setColumns(['location_type_id'])
                ->setName('location_type')
        );
        $table->update();
    }
}
