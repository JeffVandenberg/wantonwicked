<?php

use Migrations\AbstractMigration;

class AddDistrictTypes extends AbstractMigration
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
        $this->table('districts')
            ->addColumn('district_type_id', 'integer', [
                'default' => 0,
                'signed' => false,
                'null' => false
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'null' => false,
                'limit' => 100
            ])
            ->update();

        $this->table('district_types',
            [
                'signed' => false
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'null' => false,
                'limit' => 255
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('color', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->create();

    }
}
