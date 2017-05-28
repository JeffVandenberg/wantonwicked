<?php
use Migrations\AbstractMigration;

class UpdateCharacterStatus extends AbstractMigration
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
        $this->execute("UPDATE characters SET character_status_id = 5 where is_deleted='Y'");
        $this->execute("UPDATE characters SET character_status_id = 3 where is_deleted='N' AND is_sanctioned='N'");
        $this->execute("UPDATE characters SET character_status_id = 2 where is_deleted='N' AND is_sanctioned='Y'");
    }

    public function down()
    {
        $this->execute("UPDATE characters SET character_status_id = 1");
    }
}
