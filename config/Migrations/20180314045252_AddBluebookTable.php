<?php

use Migrations\AbstractMigration;

class AddBluebookTable extends AbstractMigration
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
        $this->table('bluebooks',
            [
                'signed' => false
            ])
            ->addColumn('character_id', 'integer', [
                'signed' => false,
                'null' => false
            ])
            ->addColumn('title', 'string', [
                'default' => '',
                'null' => false,
                'limit' => 100
            ])
            ->addColumn('body', 'text', [
                'null' => false
            ])
            ->addColumn('created_by_id', 'integer', [
                'signed' => false,
                'null' => false
            ])
            ->addColumn('created_on', 'datetime', [
                'null' => false,
                'default' => null
            ])
            ->addColumn('updated_by_id', 'integer', [
                'signed' => false,
                'null' => false
            ])
            ->addColumn('updated_on', 'datetime', [
                'null' => false,
                'default' => null
            ])
            ->addIndex(['character_id'])
            ->addIndex(['created_by_id'])
            ->create();

        // migrate over existing bluebooks
        $sql = <<<SQL
INSERT INTO bluebooks (id, character_id, title, body, created_by_id, created_on, updated_by_id, updated_on) 
SELECT
  id, character_id, title, body,created_by_id, created_on, updated_by_id, updated_on
FROM
  requests
WHERE
  request_type_id = 4
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $this->table('bluebooks')->drop();
    }
}
