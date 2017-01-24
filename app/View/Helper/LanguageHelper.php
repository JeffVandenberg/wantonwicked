<?php

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 12/16/2016
 * Time: 4:51 PM
 */
class LanguageHelper extends AppHelper
{
    private $translations = [
        'mortal' => [
            'splat1' => 'Guild',
            'morality' => 'Integrity',
            'virtue' => 'Virtue',
            'Vice' => 'Vice',
            'break_point0' => 'What is the worst thing your character has ever done?',
            'break_point1' => 'What is the worst thing your character can imagine themselves doing?',
            'break_point2' => 'What is the worst thing your character can imagine someone else doing?',
            'break_point3' => 'What has the character forgotten?',
            'break_point4' => 'What is the most traumatic thing that has ever happened to the character?',
        ],
        'vampire' => [
            'splat1' => 'Clan',
            'splat2' => 'Covenant',
            'virtue' => 'Mask',
            'vice' => 'Dirge',
            'morality' => 'Humanity',
            'icdisc' => 'Discipline',
            'oocdisc' => 'Discipline',
            'devotion' => 'Devotion'
        ],
        'general' => [
            'merit' => 'Merit',
            'misc_power' => 'Misc'
        ]
    ];

    public function translate($label, $characterType)
    {
        if(isset($this->translations[$characterType][$label])) {
            return $this->translations[$characterType][$label];
        }
        else if(isset($this->translations['general'][$label])){
            return $this->translations['general'][$label];
        }
        return $label;
    }
}
