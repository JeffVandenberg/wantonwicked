<?php
use Migrations\AbstractMigration;

class AddSignupLimitToScenes extends AbstractMigration
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
        $table = $this->table('scenes');
        $table->addColumn('signup_limit', 'integer', [
            'default' => 0,
            'signed' => false,
            'null' => false,
        ]);
        $table->update();
    }
}
