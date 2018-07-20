<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * WodDieroll Entity
 *
 * @property int $Roll_ID
 * @property int $Character_ID
 * @property \Cake\I18n\Time $Roll_Date
 * @property string $Character_Name
 * @property string $Description
 * @property int $Dice
 * @property string $10_Again
 * @property string $9_Again
 * @property string $8_Again
 * @property string $1_Cancel
 * @property string $Used_WP
 * @property string $Used_PP
 * @property string $Result
 * @property string $Note
 * @property int $Num_of_Successes
 * @property string $Chance_Die
 * @property string $Bias
 * @property string $Is_Rote
 *
 * @property \App\Model\Entity\Character $character
 */
class Roll extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'Roll_ID' => false
    ];
}
