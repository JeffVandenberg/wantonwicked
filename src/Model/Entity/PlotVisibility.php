<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PlotVisibility Entity
 *
 * @property int $id
 * @property string $name
 *
 * @property \App\Model\Entity\Plot[] $plots
 */
class PlotVisibility extends Entity
{
    const Public = 1;
    const Promoted = 2;
    const Hidden = 3;

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
