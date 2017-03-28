<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CharacterPower Entity
 *
 * @property int $id
 * @property int $character_id
 * @property string $power_type
 * @property string $power_name
 * @property string $power_note
 * @property int $power_level
 * @property bool $is_public
 * @property string $extra
 *
 * @property \App\Model\Entity\Character $character
 */
class CharacterPower extends Entity
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
        'id' => false
    ];
}
