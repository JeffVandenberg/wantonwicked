<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CharacterStatus Entity
 *
 * @property int $id
 * @property string $name
 * @property int $sort_order
 *
 * @property \App\Model\Entity\Character[] $characters
 */
class CharacterStatus extends Entity
{
    const NewCharacter = 1;
    const Active = 2;
    const Unsanctioned = 3;
    const Inactive = 4;
    const Deleted = 5;
    const Idle = 6;

    const NonDeleted = [
        self::NewCharacter,
        self::Active,
        self::Unsanctioned,
        self::Inactive,
        self::Idle,
    ];

    const Sanctioned = [
        self::Active,
        self::Inactive,
        self::Idle
    ];

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
