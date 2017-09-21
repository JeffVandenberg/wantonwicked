<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CharacterBeat Entity
 *
 * @property int $id
 * @property int $character_id
 * @property int $beat_type_id
 * @property int $beat_status_id
 * @property string $note
 * @property int $created_by_id
 * @property \Cake\I18n\Time $created
 * @property int $updated_by_id
 * @property \Cake\I18n\Time $updated
 * @property \Cake\I18n\Time $applied_on
 * @property int $beats_awarded
 *
 * @property \App\Model\Entity\Character $character
 * @property \App\Model\Entity\BeatType $beat_type
 * @property \App\Model\Entity\BeatStatus $beat_status
 * @property \App\Model\Entity\User $created_by
 * @property \App\Model\Entity\User $updated_by
 */
class CharacterBeat extends Entity
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
