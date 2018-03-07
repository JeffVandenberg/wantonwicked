<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RequestRequest Entity
 *
 * @property int $id
 * @property int $from_request_id
 * @property int $to_request_id
 *
 * @property \App\Model\Entity\Request $from_request
 * @property \App\Model\Entity\Request $to_request
 */
class RequestRequest extends Entity
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
