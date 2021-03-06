<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/13/2015
 * Time: 8:33 PM
 */

namespace classes\dice;


class WodDice
{
    public function rollWoDDice($dice, $ten_again, $nine_again, $eight_again, $one_cancel, $chance_die, $is_rote = false, $min_successes = 0)
    {
        $return = [];
        $result = '';
        $note = 'Failure';
        $num_of_successes = 0;

        // set up array for rolls
        $num_array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

        if ($chance_die == 'Y') {
            // process chance die roll
            $highlight_next_roll = false;

            do {
                // roll the dice
                $selected_index = random_int(0, sizeof($num_array) - 1);
                $roll = $num_array[$selected_index];

                // test for dramatic failure
                if (($roll === 1) && (!$num_of_successes)) {
                    $note = 'Dramatic Failure';
                }

                // count successes
                if ($roll === 10) {
                    $num_of_successes++;
                }

                // add to result, if this is a reroll, highlight
                if ($highlight_next_roll) {
                    $result .= "<b>$roll</b>, ";
                    $highlight_next_roll = false;
                } else {
                    $result .= "$roll, ";
                }

                // highlight next roll to show it was on the same die
                if ($roll === 10) {
                    $highlight_next_roll = true;
                }

                // determine if we roll again
                if (($roll === 10) && ($ten_again == 'Y')) {
                    $roll_again = true;
                } else {
                    $roll_again = false;
                }

            } while ($roll_again);
        } else {
            // process roll normally
            $num_of_successes = 0;
            $number_of_successes = 0;
            do {
                for ($i = 0; $i < $dice; $i++) {
                    $roll_again = false;
                    $is_first_roll = true;
                    $first_roll = 0;
                    $number_of_successes = 0;
                    do {
                        // do roll
                        $selected_index = random_int(0, sizeof($num_array) - 1);
                        usleep(1);
                        $roll = $num_array[$selected_index];

                        // determine success
                        if ($roll >= 8) {
                            $num_of_successes++;
                        } else if ($is_rote && $is_first_roll) {
                            $first_roll = $roll;
                            $selected_index = random_int(0, sizeof($num_array) - 1);
                            $roll = $num_array[$selected_index];

                            // determine success
                            if ($roll >= 8) {
                                $num_of_successes++;
                            }
                        }

                        // test if we remove successes
                        if (($roll == 1) && ($one_cancel == 'Y') && (!$roll_again)) {
                            $num_of_successes--;
                        }

                        // add to result, highlight if its a reroll
                        if ($first_roll && $is_first_roll) {
                            $result .= "<s>$first_roll</s>, ";
                        }

                        if ($roll_again) {
                            $result .= "<b>$roll</b>, ";
                            $roll_again = false;
                        } else {
                            $result .= "$roll, ";
                        }

                        // determine if we roll again
                        if ($roll >= 8) {
                            if (($roll == 8) && ($eight_again == 'Y')) {
                                $roll_again = true;
                            }

                            if (($roll == 9) && ($nine_again == 'Y')) {
                                $roll_again = true;
                            }

                            if (($roll == 10) && ($ten_again == 'Y')) {
                                $roll_again = true;
                            }
                            $is_first_roll = false;
                        }
                    } while ($roll_again);
                }
            } while ($number_of_successes < $min_successes);
        }

        // make sure that 1s removing from success hasn't made it go negative
        if ($num_of_successes < 0) {
            $num_of_successes = 0;
        }

        // test to see if there are any sucesses
        if ($num_of_successes > 0) {
            if ($num_of_successes > 4) {
                $note = 'Exceptional Success';
            } else {
                $note = 'Success';
            }
        }

        // trim result
        $result = substr($result, 0, strlen($result) - 2);


        $return['result'] = $result;
        $return['num_of_successes'] = $num_of_successes;
        $return['note'] = $note;
        return $return;
    }
}
