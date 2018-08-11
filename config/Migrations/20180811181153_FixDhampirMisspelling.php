<?php
use Migrations\AbstractMigration;

class FixDhampirMisspelling extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up(): void
    {
        $sql = <<<SQL
UPDATE
  characters
SET
  character_type = 'dhampir'
WHERE
  character_type = 'dhamphir'
SQL;
        $this->execute($sql);
    }

    public function down(): void
    {
        $sql = <<<SQL
UPDATE
  characters
SET
  character_type = 'dhamphir'
WHERE
  character_type = 'dhampir'
SQL;
        $this->execute($sql);
    }
}
