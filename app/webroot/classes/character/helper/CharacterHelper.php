<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 1/5/14
 * Time: 1:10 PM
 */

namespace classes\character\helper;


class CharacterHelper {

    public static function IncreaseAttribute(&$stats, $bonusAttribute)
    {
        for($i = 0; $i < 9; $i++) {
            if(strtolower($stats['attribute'.$i.'_name']) == strtolower($bonusAttribute)) {
                $stats['attribute'.$i]++;
                break;
            }
        }
    }
}