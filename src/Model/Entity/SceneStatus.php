<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SceneStatus Entity
 *
 * @property int $id
 * @property string $name
 *
 * @property \App\Model\Entity\Scene[] $scenes
 */
class SceneStatus extends Entity
{
    public const OPEN = 1;
    public const COMPLETED = 2;
    public const CANCELLED = 3;

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
