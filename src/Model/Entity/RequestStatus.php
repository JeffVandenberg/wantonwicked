<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use OAuth\Common\Exception\Exception;

/**
 * RequestStatus Entity
 *
 * @property int $id
 * @property string $name
 *
 * @property \App\Model\Entity\RequestStatusHistory[] $request_status_histories
 * @property \App\Model\Entity\Request[] $requests
 */
class RequestStatus extends Entity
{
    const NEW_REQUEST = 1;
    const SUBMITTED = 6;
    const IN_PROGRESS = 2;
    const RETURNED = 3;
    const APPROVED = 4;
    const DENIED = 5;
    const CLOSED = 7;

    public static $Player = [
        RequestStatus::NEW_REQUEST,
        RequestStatus::SUBMITTED,
        RequestStatus::IN_PROGRESS,
        RequestStatus::RETURNED,
        RequestStatus::APPROVED,
        RequestStatus::DENIED
    ];

    public static $PlayerEdit = [
        RequestStatus::NEW_REQUEST,
        RequestStatus::SUBMITTED,
        RequestStatus::RETURNED
    ];

    public static $Storyteller = [
        RequestStatus::SUBMITTED,
        RequestStatus::IN_PROGRESS
    ];

    public static $PlayerSubmit = [
        RequestStatus::NEW_REQUEST,
        RequestStatus::RETURNED
    ];

    public static $Final = [
        RequestStatus::APPROVED,
        RequestStatus::DENIED
    ];

    public static $Terminal = [
        RequestStatus::APPROVED,
        RequestStatus::DENIED,
        RequestStatus::CLOSED
    ];

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

    /**
     * @param $state
     * @return int
     * @throws Exception
     */
    public static function getIdForState($state)
    {
        switch (strtolower($state)) {
            case 'return':
                return self::RETURNED;
            case 'deny':
                return self::DENIED;
            case 'approve':
                return self::APPROVED;
            case 'new':
                return self::NEW_REQUEST;
            case 'close':
                return self::CLOSED;
            default:
                throw new Exception('Unknown Request Status: ' . $state);
        }
    }

    public static function getPastTenseForState($state)
    {
        switch(strtolower($state)) {
            case 'deny':
                return 'denied';
            case 'approve':
                return 'approved';
            case 'new':
                return 'created';
            case 'close':
                return 'closed';
            default:
                return $state . 'ed';
        }
    }
}
