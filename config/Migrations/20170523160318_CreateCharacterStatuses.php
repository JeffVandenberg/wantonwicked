<?php
use Migrations\AbstractMigration;

class CreateCharacterStatuses extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('character_statuses');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('sort_order', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->create();

        $data = [
            ['id' => 1, 'name' => 'New', 'sort_order' => 4],
            ['id' => 2, 'name' => 'Active', 'sort_order' => 1],
            ['id' => 3, 'name' => 'Unsanctioned', 'sort_order' => 5],
            ['id' => 4, 'name' => 'Inactive', 'sort_order' => 3],
            ['id' => 5, 'name' => 'Deleted', 'sort_order' => 6],
            ['id' => 6, 'name' => 'Idle', 'sort_order' => 2],
        ];

        $table = $this->table('character_statuses');
        $table->insert($data)->save();
    }

    public function down()
    {
        $table = $this->table('character_statuses');
        $table->drop();
    }
}
