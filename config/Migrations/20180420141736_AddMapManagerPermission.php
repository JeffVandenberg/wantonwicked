<?php
use Migrations\AbstractMigration;

class AddMapManagerPermission extends AbstractMigration
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
        $sql = <<<SQL
INSERT INTO permissions (id, permission_name) VALUES (12, 'Map Editor');
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
DELETE FROM permissions_users where permission_id = 12
SQL;
        $this->execute($sql);

        $sql = <<<SQL
DELETE FROM permissions where id = 12
SQL;
        $this->execute($sql);
    }
}
