<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/22/2017
 * Time: 7:42 AM
 */

namespace classes\character\repository;


use classes\character\data\CharacterNote;
use classes\core\repository\AbstractRepository;

class CharacterNoteRepository extends AbstractRepository
{
    public function __construct($className = null, $connection = null)
    {
        parent::__construct('classes\character\data\CharacterNote');
    }

    /**
     * @param $characterId
     * @return CharacterNote|null
     */
    public function getMostRecentForCharacter($characterId)
    {
        $sql = <<<SQL
SELECT
  *
FROM
  character_notes
WHERE
  character_id = ?
ORDER BY
  created DESC
SQL;

        $data = $this->query($sql)->single([$characterId]);

        if($data) {
            return $this->populateObject($data);
        } else {
            return null;
        }
    }

}
