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
    const SANCTION      = 1;
    const XP_REQUEST     = 2;
    const NON_XP_REQUEST  = 3;
    const BLUE_BOOK      = 4;
    const CREATIVE_THAUM = 5;
    const SCENE_REQUEST  = 6;
    const XP_RECOMMEND   = 7;

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
