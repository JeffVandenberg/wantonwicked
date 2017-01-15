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
            'morality0' => 'What is the worst thing your character has ever done?',
            'morality1' => 'What is the worst thing your character can imagine themselves doing?',
            'morality2' => 'What is the worst thing your character can imagine someone else doing?',
            'morality3' => 'What has the character forgotten?',
            'morality4' => 'What is the most traumatic thing that has ever happened to the character?'
        ]
    ];

    public function translate($label, $characterType)
    {
        if(isset($this->translations[$characterType][$label])) {
            return $this->translations[$characterType][$label];
        }
        else {
            return $label;
        }
    }
}
