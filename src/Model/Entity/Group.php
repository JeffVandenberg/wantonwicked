<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Group Entity
 *
 * @property int $id
 * @property string $name
 * @property int $group_type_id
 * @property bool $is_deleted
 * @property int $created_by
 *
 * @property \App\Model\Entity\GroupType $group_type
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\GroupIcon[] $group_icons
 * @property \App\Model\Entity\Request[] $requests
 * @property \App\Model\Entity\StGroup[] $st_groups
 * @property \App\Model\Entity\RequestType[] $request_types
 */
class Group extends Entity
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
