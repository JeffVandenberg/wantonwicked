<?php

use Migrations\AbstractMigration;

class AddAssignedToUserIdToRequestsTable extends AbstractMigration
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
        $table = $this->table('requests');
        $table
            ->addColumn('assigned_user_id', 'integer', [
                'signed' => false,
                'null' => true
            ])
            ->addIndex(['assigned_user_id']);
            $table->update();
    }
}