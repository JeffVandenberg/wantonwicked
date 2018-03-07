<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RequestType Entity
 *
 * @property int $id
 * @property string $name
 *
 * @property \App\Model\Entity\Request[] $requests
 * @property \App\Model\Entity\Group[] $groups
 */
class RequestType extends Entity
{
    const Sanction      = 1;
    const XpRequest     = 2;
    const NonXpRequest  = 3;
    const BlueBook      = 4;
    const CreativeThaum = 5;
    const SceneRequest  = 6;
    const XpRecommend   = 7;

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
