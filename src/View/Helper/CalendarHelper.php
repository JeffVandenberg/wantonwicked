<?php

namespace App\View\Helper;

use Cake\View\Helper\HtmlHelper;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 1/5/16
 * Time: 1:27 PM
 * @property HtmlHelper Html
 */
class CalendarHelper extends AppHelper
{
    public $helpers = array('Html');

    public function drawCalendar($month, $year, $links)
    {
        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

        /* table headings */
        $headings = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings) . '</td></tr>';

        /* days and weeks vars now ... */
        $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
        $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
        $days_in_this_week = 1;
        $day_counter = 0;

        /* row for week one */
        $calendar .= '<tr class="calendar-row">';

        /* print "blank" days until the first of the current week */
        for ($x = 0; $x < $running_day; $x++):
            $calendar .= '<td class="calendar-day-np"> </td>';
            $days_in_this_week++;
        endfor;

        /* keep going with days.... */
        $today = date('Y-m-d');
        for ($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $todayClass = '';
            $listDayPadded = str_pad($list_day, 2, '0', STR_PAD_LEFT);
            if ($today == date($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . $listDayPadded)) {
                $todayClass = 'calendar-day-today';
            }
            $calendar .= '<td class="calendar-day ' . $todayClass . '">';
            /* add in the day number */
            $calendar .= '<div class="day-number">' . $list_day . '</div>';

            if (isset($links[$listDayPadded])) {
                foreach ($links[$listDayPadded] as $link) {
                    $calendar .= '<div class="' . $link['class'] . '">'
                        . $this->Html->link(
                            $link['title'],
                            $link['link'],
                            [
                                'title' => $link['linkTitle']
                            ]
                        )
                        . '</div>';
                }
            }

            $calendar .= '</td>';
            if ($running_day == 6):
                $calendar .= '</tr>';
                if (($day_counter + 1) != $days_in_month):
                    $calendar .= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
            endif;
            $days_in_this_week++;
            $running_day++;
            $day_counter++;
        endfor;

        /* finish the rest of the days in the week */
        if ($days_in_this_week < 8):
            for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar .= '<td class="calendar-day-np"> </td>';
            endfor;
        endif;

        /* final row */
        $calendar .= '</tr>';

        /* end the table */
        $calendar .= '</table>';

        /* all done, return result */
        return $calendar;
    }
}
