<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 1/5/14
 * Time: 1:10 PM
 */

namespace classes\character\helper;


use classes\character\repository\CharacterRepository;

class CharacterHelper
{
    public static function getMaxPowerPoints($power_rank = 1)
    {
        $points = 0;
        switch ($power_rank) {
            case 1:
                $points = 10;
                break;
            case 2:
                $points = 11;
                break;
            case 3:
                $points = 12;
                break;
            case 4:
                $points = 13;
                break;
            case 5:
                $points = 14;
                break;
            case 6:
                $points = 15;
                break;
            case 7:
                $points = 20;
                break;
            case 8:
                $points = 30;
                break;
            case 9:
                $points = 50;
                break;
            case 10:
                $points = 100;
                break;
            default:
                $points = 10;
                break;
        }
        return $points;
    }

    public static function determineBloodPotency($sourceCharacterId, $targetCharacter)
    {
        $bloodPotency = $targetCharacter['Power_Stat'];
        // do they have obfuscate 2?
        $repository = new CharacterRepository();
        if ($repository->doesCharacterHavePowerAtLevel($targetCharacter['id'], 'Obfuscate', 2)) {
            $bloodPotency = 'None';
        } else if ($repository->doesCharacterHavePowerAtLevel($targetCharacter['id'], 'Protean', 1)) {
            // do they have Protean 1?
            $sourceCharacter = $repository->getById($sourceCharacterId);
            if ($targetCharacter['Power_Stat'] < $sourceCharacter['Power_Stat']) {
                $bloodPotency = $sourceCharacter['Power_Stat'];
            }
        }

        // otherwise return raw power_stat
        return $bloodPotency;
    }
}
