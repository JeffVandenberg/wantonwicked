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
        parent::__construct('\classes\character\data\CharacterPower');
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
  $orderBy
EOQ;
        $params = array(
            $characterId,
            $powerType
        );

        return $this->query($sql)->all($params);
    }
}
