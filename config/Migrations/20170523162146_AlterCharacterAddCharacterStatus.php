<?php
use Migrations\AbstractMigration;

class AlterCharacterAddCharacterStatus extends AbstractMigration
{
    public function up()
    {
        $this->table('characters')
            ->addColumn('character_status_id', 'integer', [
                'default' => 1,
                'null' => false,
            ])
            ->update();
    }

    public function down()
    {
        $this->table('characters')
            ->removeColumn('character_status_id')
            ->update();
    }

}
