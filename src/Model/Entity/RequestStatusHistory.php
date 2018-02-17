<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RequestStatusHistory Entity
 *
 * @property int $id
 * @property int $request_id
 * @property int $request_status_id
 * @property int $created_by_id
 * @property \Cake\I18n\Time $created_on
 *
 * @property \App\Model\Entity\Request $request
 * @property \App\Model\Entity\RequestStatus $request_status
 * @property \App\Model\Entity\User $created_by
 */
class RequestStatusHistory extends Entity
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
