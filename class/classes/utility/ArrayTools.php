<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/6/2015
 * Time: 3:54 PM
 */

namespace classes\utility;


class ArrayTools
{
    /**
     * @param $array
     * @return array
     */
    public static function arrayValuesKeys($array): array
    {
        $list = [];
        foreach ($array as $key => $value) {
            if(\is_array($value)) {
                $list[$key] = self::arrayValuesKeys($value);
            }
            else {
                $list[$value] = $value;
            }
        }
        return $list;
    }
}
