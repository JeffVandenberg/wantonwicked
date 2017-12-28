<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PlotStatus Entity
 *
 * @property int $id
 * @property string $name
 *
 * @property \App\Model\Entity\Plot[] $plots
 */
class PlotStatus extends Entity
{
    const Pending = 1;
    const InProgress = 2;
    const Completed = 3;
    const Cancelled = 4;

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
