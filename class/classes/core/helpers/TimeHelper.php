<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 10/26/13
 * Time: 9:57 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\helpers;


class TimeHelper
{
    public static function toHumanTime($time) {
        if($time === null) {
            return '';
        }
        $period = 'Secs';

        // to minutes
        if(floor($time/60) != 0) {
            $time = floor($time/60);
            $period = 'Min';
            // to hours
            if(floor($time/60) != 0) {
                $time = floor($time/60);
                $period = 'Hr';
                // to Days
                if(floor($time/24) != 0) {
                    $time = floor($time/24);
                    $period = 'Days';
                    // to Weeks
                    if(floor($time/7) != 0) {
                        $time = floor($time/7);
                        $period = 'Wks';
                        // to Months
                        if(floor($time/4) != 0) {
                            $time = floor($time/4);
                            $period = 'Mth';
                        }
                    }
                }
            }
        }

        return $time . ' ' . $period;
    }
}
