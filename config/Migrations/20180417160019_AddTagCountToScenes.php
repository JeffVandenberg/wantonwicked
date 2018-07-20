<?php

use Migrations\AbstractMigration;

class AddTagCountToScenes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $tagsTable = $this->table('tags');
        if ($tagsTable->exists()) {
            $tagsTable->drop();
        }
        $taggedTable = $this->table('tagged');
        if ($taggedTable->exists()) {
            $taggedTable->drop();
        }

        $table = $this->table('scenes');
        $table->addColumn('tag_count', 'integer', [
            'signed' => false,
            'null' => false,
            'default' => 0
        ]);
        $table->update();
    }

    public function down()
    {
        $table = $this->table('scenes');
        $table->removeColumn('tag_count');
        $table->update();
    }
}
