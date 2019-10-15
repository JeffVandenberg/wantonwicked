<?php

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 12/16/2016
 * Time: 4:51 PM
 */
namespace App\View\Helper;

class LanguageHelper extends AppHelper
{
    private $translations = [
        'mortal' => [
            'splat1' => 'Guild',
            'morality' => 'Integrity',
            'virtue' => 'Virtue',
            'vice' => 'Vice',
            'break_point0' => 'What is the worst thing your character has ever done?',
            'break_point1' => 'What is the worst thing your character can imagine themselves doing?',
            'break_point2' => 'What is the worst thing your character can imagine someone else doing?',
            'break_point3' => 'What has the character forgotten?',
            'break_point4' => 'What is the most traumatic thing that has ever happened to the character?',
        ],
        'vampire' => [
            'splat1' => 'Clan',
            'splat2' => 'Covenant',
            'subsplat' => 'Bloodline',
            'virtue' => 'Mask',
            'vice' => 'Dirge',
            'morality' => 'Humanity',
            'icdisc' => 'Discipline',
            'oocdisc' => 'Discipline',
            'devotion' => 'Devotion',
            'powerstat' => 'Blood Potency',
            'powerpoints' => 'Vitae',
            'friends' => 'Coterie'
        ],
        'mage' => [
            'splat1' => 'Path',
            'splat2' => 'Order',
            'subsplat' => 'Legacy',
            'morality' => 'Wisdom',
            'virtue' => 'Virtue',
            'vice' => 'Vice',
            'powerstat' => 'Gnosis',
            'powerpoints' => 'Mana',
            'arcana' => 'Arcana',
            'rote' => 'Rote',
            'friends' => 'Cabal'
        ],
        'werewolf' => [
            'splat1' => 'Auspice',
            'splat2' => 'Tribe',
            'subsplat' => 'Lodge',
            'morality' => 'Harmony',
            'powerstat' => 'Primal Urge',
            'powerpoints' => 'Essence',
            'virtue' => 'Blood',
            'vice' => 'Bone',
            'friends' => 'Pack'
        ],
        'changeling' => [
            'splat1' => 'Seeming',
            'splat2' => 'Court',
            'subsplat' => 'Entitlement',
            'virtue' => 'Mask',
            'vice' => 'Mein',
            'morality' => 'Clarity',
            'powerstat' => 'Wyrd',
            'powerpoints' => 'Glamour',
            'friends' => 'Motley'
        ],
        'ghoul' => [
            'splat1' => 'Regent Clan',
            'virtue' => 'Virtue',
            'vice' => 'Vice',
            'morality' => 'Integrity',
            'powerpoints' => 'Vitae',
            'friends' => 'Domitor'
        ],
        'fae-touched' => [
            'splat1' => 'Seeming',
            'virtue' => 'Virtue',
            'vice' => 'Vice',
            'morality' => 'Integrity',
            'powerpoints' => 'Glamour'
        ],
        'wolfblooded' => [
            'virtue' => 'Virtue',
            'vice' => 'Vice',
            'break_point0' => 'What is the worst thing your character has ever done?',
            'break_point1' => 'What is the worst thing your character can imagine themselves doing?',
            'break_point2' => 'What is the worst thing your character can imagine someone else doing?',
            'break_point3' => 'What has the character forgotten?',
            'break_point4' => 'What is the most traumatic thing that has ever happened to the character?',
        ],
        'dhampir' => [
            'splat1' => 'Parent Clan',
            'morality' => 'Integrity',
            'virtue' => 'Virtue',
            'vice' => 'Vice',
            'break_point0' => 'What is the worst thing your character has ever done?',
            'break_point1' => 'What is the worst thing your character can imagine themselves doing?',
            'break_point2' => 'What is the worst thing your character can imagine someone else doing?',
            'break_point3' => 'What has the character forgotten?',
            'break_point4' => 'What is the most traumatic thing that has ever happened to the character?',
        ],
        'beast' => [
            'virtue' => 'Legend',
            'vice' => 'Life',
            'splat1' => 'Family',
            'splat2' => 'Hunger',
            'subsplat' => 'Cult',
            'powerstat' => 'Lair',
            'powerpoints' => 'Satiety',
            'friends' => 'Brood',
        ],
        'herald' => [
            'virtue' => 'Legend',
            'vice' => 'Life',
            'morality' => 'Integrity',
            'break_point0' => 'What is the worst thing your character has ever done?',
            'break_point1' => 'What is the worst thing your character can imagine themselves doing?',
            'break_point2' => 'What is the worst thing your character can imagine someone else doing?',
            'break_point3' => 'What has the character forgotten?',
            'break_point4' => 'What is the most traumatic thing that has ever happened to the character?',
        ],
        'general' => [
            'merit' => 'Merit',
            'misc_power' => 'Misc',
        ]
    ];

    public function translate($label, $characterType)
    {
        if (isset($this->translations[$characterType][$label])) {
            return $this->translations[$characterType][$label];
        } elseif (isset($this->translations['general'][$label])) {
            return $this->translations['general'][$label];
        }
        return $label;
    }
}
