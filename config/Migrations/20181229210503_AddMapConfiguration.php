<?php
use Migrations\AbstractMigration;

class AddMapConfiguration extends AbstractMigration
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
INSERT INTO configurations
(`key`, value, description, data_type)
VALUES
("default_district_description", "", "District Description Template", "text"),
("default_location_description", "", "Location Description Template", "text");
SQL;

        $this->query($sql);
    }

    public function down()
    {
        $this->query('DELETE FROM configurations WHERE `key` IN ("default_district_description", "default_location_description")');
    }
}
