<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/6/2015
 * Time: 12:58 PM
 */

namespace classes\character\repository;


use classes\core\repository\AbstractRepository;

class CharacterPowerRepository extends AbstractRepository
{
    /**
     * CharacterPowerRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('\classes\Character\Data\CharacterPower');
    }

    public function ListPowersForCharacter($characterId, $powerType, $orderBy)
    {
        $sql = <<<EOQ
select
  *
from
  character_powers
where
  character_id = ?
  and power_type = ?
order by
  ?
EOQ;
        $params = array(
            $characterId,
            $powerType,
            $orderBy
        );

        return $this->Query($sql)->All($params);
    }
}