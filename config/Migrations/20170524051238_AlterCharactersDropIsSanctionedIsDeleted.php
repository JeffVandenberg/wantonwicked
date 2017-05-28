<?php
use Migrations\AbstractMigration;

class AlterCharactersDropIsSanctionedIsDeleted extends AbstractMigration
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
        $this->table('characters')
            ->removeColumn('is_sanctioned')
            ->removeColumn('is_deleted')
            ->update();
    }
}
