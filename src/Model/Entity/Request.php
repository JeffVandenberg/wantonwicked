<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use function debug_backtrace;

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
 * @property \Cake\I18n\Time $created_on
 * @property int $updated_by_id
 * @property \Cake\I18n\Time $updated_on
 *
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\Character $character
 * @property \App\Model\Entity\RequestType $request_type
 * @property \App\Model\Entity\RequestStatus $request_status
 * @property \App\Model\Entity\User $created_by
 * @property \App\Model\Entity\User $updated_by
 * @property \App\Model\Entity\RequestBluebook[] $request_bluebooks
 * @property \App\Model\Entity\RequestCharacter[] $request_characters
 * @property \App\Model\Entity\RequestNote[] $request_notes
 * @property \App\Model\Entity\RequestRoll[] $request_rolls
 * @property \App\Model\Entity\RequestStatusHistory[] $request_status_histories
 * @property \App\Model\Entity\SceneRequest[] $scene_requests
 * @property \App\Model\Entity\RequestRequest[] $request_requests
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
        'id' => false
    ];

    public function setErrors(array $fields, $overwrite = false)
    {
        parent::setErrors($fields, $overwrite);
    }


}
