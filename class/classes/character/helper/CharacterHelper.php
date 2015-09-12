<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 1/5/14
 * Time: 1:10 PM
 */

namespace classes\character\helper;


class CharacterHelper
{

    public static function IncreaseAttribute(&$stats, $bonusAttribute)
    {
        for ($i = 0; $i < 9; $i++) {
            if (strtolower($stats['attribute' . $i . '_name']) == strtolower($bonusAttribute)) {
                $stats['attribute' . $i]++;
                break;
            }
        }
    }

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

}