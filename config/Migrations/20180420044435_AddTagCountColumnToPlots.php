<?php
use Migrations\AbstractMigration;

class AddTagCountColumnToPlots extends AbstractMigration
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
        $table = $this->table('plots');
        $table->addColumn('tag_count', 'integer', [
            'null' => false,
            'signed' => false,
            'default' => 0
        ]);
        $table->update();
    }
}
