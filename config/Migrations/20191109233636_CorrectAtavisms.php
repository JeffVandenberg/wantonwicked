<?php

use Migrations\AbstractMigration;

class CorrectAtavisms extends AbstractMigration
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
        $this->query(<<<EOQ
UPDATE 
    character_powers
SET
    power_type = 'atavism'
WHERE
    power_type = 'avatism';
EOQ
        );
    }
}
