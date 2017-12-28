<?php

use Migrations\AbstractMigration;

class RenameL5RTables extends AbstractMigration
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
        $this->table('plots',
            [
                'signed' => false
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false
            ])
            ->addColumn('description', 'text', [
                'null' => false
            ])
            ->addColumn('slug', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('plot_status_id', 'integer', [
                'null' => false,
                'signed' => false
            ])
            ->addColumn('plot_visibility_id', 'integer', [
                'null' => false,
                'signed' => false
            ])
            ->addColumn('run_by_id', 'integer', [
                'signed' => false,
                'null' => false
            ])
            ->addColumn('created_by_id', 'integer', [
                'signed' => false,
                'null' => false
            ])
            ->addColumn('created', 'datetime', [
                'null' => false,
                'default' => null
            ])
            ->addColumn('updated_by_id', 'integer', [
                'signed' => false,
                'null' => false
            ])
            ->addColumn('updated', 'datetime', [
                'null' => false,
                'default' => null
            ])
            ->addIndex([
                'plot_status_id'
            ])
            ->addIndex([
                'slug'
            ])
            ->create();

        $this->table('plot_statuses',
            [
                'signed' => false
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 255
            ])
            ->create();

        $this->table('plot_characters',
            [
                'signed' => false
            ])
            ->addColumn('plot_id', 'integer', [
                'signed' => false,
                'null' => false
            ])
            ->addColumn('character_id', 'integer', [
                'signed' => false,
                'null' => false
            ])
            ->addColumn('note', 'text')
            ->create();

        $this->table('plot_scenes',
            [
                'signed' => false
            ])
            ->addColumn('plot_id', 'integer', [
                'signed' => false,
                'null' => false
            ])
            ->addColumn('scene_id', 'integer', [
                'signed' => false,
                'null' => false
            ])
            ->addColumn('note', 'text')
            ->create();

        // insert initial data
        $data = [
            ['id' => 1, 'name' => 'Pending'],
            ['id' => 2, 'name' => 'In Progress'],
            ['id' => 3, 'name' => 'Completed'],
            ['id' => 4, 'name' => 'Cancelled'],
        ];

        $this->table('plot_visibilities',
            [
                'signed' => false
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 255
            ])
            ->create();

        // insert initial data
        $data = [
            ['id' => 1, 'name' => 'Pending'],
            ['id' => 2, 'name' => 'In Progress'],
            ['id' => 3, 'name' => 'Completed'],
            ['id' => 4, 'name' => 'Cancelled'],
        ];
        $this->table('plot_statuses')->insert($data)->save();

        $data = [
            ['id' => 1, 'name' => 'Public'],
            ['id' => 2, 'name' => 'Promoted'],
            ['id' => 3, 'name' => 'Hidden'],
        ];
        $this->table('plot_visibilities')->insert($data)->save();

        $this->table('permissions')->insert([
            [
                'id' => 10,
                'permission_name' => 'Plot - Manage'
            ],
            [
                'id' => 11,
                'permission_name' => 'Plot - View All'
            ],

        ])->save();
    }

    public function down()
    {
        if ($this->hasTable('plot_characters')) {
            $this->table('plot_characters')->drop();
        }
        if ($this->hasTable('plot_scenes')) {
            $this->table('plot_scenes')->drop();
        }
        if ($this->hasTable('plot_statuses')) {
            $this->table('plot_statuses')->drop();
        }
        if ($this->hasTable('plot_visibilities')) {
            $this->table('plot_visibilities')->drop();
        }
        if ($this->hasTable('plots')) {
            $this->table('plots')->drop();
        }
        $this->execute('DELETE FROM permissions WHERE id IN (10, 11)');
    }
}
