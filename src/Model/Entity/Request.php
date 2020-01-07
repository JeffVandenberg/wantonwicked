<?php
namespace App\Model\Entity;

use Cake\I18n\Time;
use Cake\ORM\Entity;

/**
 * Request Entity
 *
 * @property int $id
 * @property int $group_id
 * @property int $character_id
 * @property string $title
 * @property string $body
 * @property int $request_type_id
 * @property int $request_status_id
 * @property int $created_by_id
 * @property Time $created_on
 * @property int $updated_by_id
 * @property Time $updated_on
 * @property int $assigned_user_id
 *
 * @property Group $group
 * @property Character $character
 * @property RequestType $request_type
 * @property RequestStatus $request_status
 * @property User $assigned_user
 * @property User $created_by
 * @property User $updated_by
 * @property RequestBluebook[] $request_bluebooks
 * @property RequestCharacter[] $request_characters
 * @property RequestNote[] $request_notes
 * @property RequestRoll[] $request_rolls
 * @property RequestStatusHistory[] $request_status_histories
 * @property SceneRequest[] $scene_requests
 * @property RequestRequest[] $request_requests
 */
class Request extends Entity
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
        'id' => false,
    ];
}
