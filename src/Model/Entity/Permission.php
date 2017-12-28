<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Permission Entity
 *
 * @property int $id
 * @property string $permission_name
 *
 * @property \App\Model\Entity\PermissionsUser[] $permissions_users
 * @property \App\Model\Entity\Role[] $roles
 */
class Permission extends Entity
{
    public static $IsAdmin = 1;
    public static $IsHead = 2;
    public static $IsST = 3;
    public static $IsAsst = 4;
    public static $WikiManager = 5;
    public static $ManageRequests = 6;
    public static $ManageCharacters = 7;
    public static $ManageScenes = 8;
    public static $ManageDatabase = 9;
    public static $PlotsManage = 10;
    public static $PlotsView = 11;

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
