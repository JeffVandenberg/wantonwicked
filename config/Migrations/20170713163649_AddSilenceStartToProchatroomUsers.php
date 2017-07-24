<?php
use Migrations\AbstractMigration;

class AddSilenceStartToProchatroomUsers extends AbstractMigration
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
        $table = $this->table('prochatrooms_users');
        $table->addColumn('silence_start', 'integer', [
            'null' => false,
            'default' => 0,
            'signed' => false
        ]);
        $table->update();
    }
}
