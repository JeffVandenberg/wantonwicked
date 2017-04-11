<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SceneCharacter Entity
 *
 * @property int $id
 * @property int $scene_id
 * @property int $character_id
 * @property string $note
 * @property \Cake\I18n\Time $added_on
 *
 * @property \App\Model\Entity\Scene $scene
 * @property \App\Model\Entity\Character $character
 */
class SceneCharacter extends Entity
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
