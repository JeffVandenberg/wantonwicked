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
    const NewRequest = 1;
    const Submitted = 6;
    const InProgress = 2;
    const Returned = 3;
    const Approved = 4;
    const Denied = 5;
    const Closed = 7;

    public static $Player = array(
        RequestStatus::NewRequest,
        RequestStatus::Submitted,
        RequestStatus::InProgress,
        RequestStatus::Returned,
        RequestStatus::Approved,
        RequestStatus::Denied
    );

    public static $PlayerEdit = array(
        RequestStatus::NewRequest,
        RequestStatus::Submitted,
        RequestStatus::Returned
    );

    public static $Storyteller = array(
        RequestStatus::Submitted,
        RequestStatus::InProgress
    );

    public static $PlayerSubmit = array(
        RequestStatus::NewRequest,
        RequestStatus::Returned
    );

    public static $Final = array(
        RequestStatus::Approved,
        RequestStatus::Denied
    );

    public static $Terminal = array(
        RequestStatus::Approved,
        RequestStatus::Denied,
        RequestStatus::Closed
    );

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
                return self::Returned;
            case 'deny':
                return self::Denied;
            case 'approve':
                return self::Approved;
            case 'new':
                return self::NewRequest;
            case 'close':
                return self::Closed;
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
