<?php
use Migrations\AbstractMigration;

class RenameIconColumns extends AbstractMigration
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
        $this->table('icons')
            ->renameColumn('ID', 'id')
            ->renameColumn('Icon_Name', 'icon_name')
            ->renameColumn('Icon_ID', 'icon_id')
            ->renameColumn('Player_Viewable', 'player_viewable')
            ->renameColumn('GM_Viewable', 'staff_viewable')
            ->renameColumn('Admin_Viewable', 'admin_viewable')
            ->update();
    }

    public function down()
    {
        // do nothing
        $this->table('icons')
            ->renameColumn('id', 'ID')
            ->renameColumn('icon_name', 'Icon_Name')
            ->renameColumn('icon_id', 'Icon_ID')
            ->renameColumn('player_viewable', 'Player_Viewable')
            ->renameColumn('staff_viewable', 'GM_Viewable')
            ->renameColumn('admin_viewable', 'Admin_Viewable')
            ->update();
    }
}
