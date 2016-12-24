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
            'splat1' => 'Guild'
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
