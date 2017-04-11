<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BeatType Entity
 *
 * @property int $id
 * @property string $name
 * @property int $number_of_beats
 * @property bool $admin_only
 * @property int $created_by_id
 * @property \Cake\I18n\Time $created
 * @property int $updated_by_id
 * @property \Cake\I18n\Time $updated
 * @property bool $may_rollover
 *
 * @property \App\Model\Entity\User $created_by
 * @property \App\Model\Entity\User $updated_by
 * @property \App\Model\Entity\CharacterBeat[] $character_beats
 */
class BeatType extends Entity
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
